<?php
/**
 * Generator of database load balancing objects.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Database
 */

namespace Wikimedia\Rdbms;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\WaitConditionLoop;
use BagOStuff;

/**
 * Class for ensuring a consistent ordering of events as seen by the user, despite replication.
 * Kind of like Hawking's [[Chronology Protection Agency]].
 */
class ChronologyProtector implements LoggerAwareInterface {
	/** @var BagOStuff */
	protected $store;
	/** @var LoggerInterface */
	protected $logger;

	/** @var string Storage key name */
	protected $key;
	/** @var string Hash of client parameters */
	protected $clientId;
	/** @var float|null Minimum UNIX timestamp of 1+ expected startup positions */
	protected $waitForPosTime;
	/** @var int Max seconds to wait on positions to appear */
	protected $waitForPosTimeout = self::POS_WAIT_TIMEOUT;
	/** @var bool Whether to no-op all method calls */
	protected $enabled = true;
	/** @var bool Whether to check and wait on positions */
	protected $wait = true;

	/** @var bool Whether the client data was loaded */
	protected $initialized = false;
	/** @var DBMasterPos[] Map of (DB master name => position) */
	protected $startupPositions = [];
	/** @var DBMasterPos[] Map of (DB master name => position) */
	protected $shutdownPositions = [];
	/** @var float[] Map of (DB master name => 1) */
	protected $shutdownTouchDBs = [];

	/** @var integer Seconds to store positions */
	const POSITION_TTL = 60;
	/** @var integer Max time to wait for positions to appear */
	const POS_WAIT_TIMEOUT = 5;

	/**
	 * @param BagOStuff $store
	 * @param array $client Map of (ip: <IP>, agent: <user-agent>)
	 * @param float $posTime UNIX timestamp
	 * @since 1.27
	 */
	public function __construct( BagOStuff $store, array $client, $posTime = null ) {
		$this->store = $store;
		$this->clientId = md5( $client['ip'] . "\n" . $client['agent'] );
		$this->key = $store->makeGlobalKey( __CLASS__, $this->clientId, 'v1' );
		$this->waitForPosTime = $posTime;
		$this->logger = new NullLogger();
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * @param bool $enabled Whether to no-op all method calls
	 * @since 1.27
	 */
	public function setEnabled( $enabled ) {
		$this->enabled = $enabled;
	}

	/**
	 * @param bool $enabled Whether to check and wait on positions
	 * @since 1.27
	 */
	public function setWaitEnabled( $enabled ) {
		$this->wait = $enabled;
	}

	/**
	 * Initialise a ILoadBalancer to give it appropriate chronology protection.
	 *
	 * If the stash has a previous master position recorded, this will try to
	 * make sure that the next query to a replica DB of that master will see changes up
	 * to that position by delaying execution. The delay may timeout and allow stale
	 * data if no non-lagged replica DBs are available.
	 *
	 * @param ILoadBalancer $lb
	 * @return void
	 */
	public function initLB( ILoadBalancer $lb ) {
		if ( !$this->enabled || $lb->getServerCount() <= 1 ) {
			return; // non-replicated setup or disabled
		}

		$this->initPositions();

		$masterName = $lb->getServerName( $lb->getWriterIndex() );
		if (
			isset( $this->startupPositions[$masterName] ) &&
			$this->startupPositions[$masterName] instanceof DBMasterPos
		) {
			$pos = $this->startupPositions[$masterName];
			$this->logger->info( __METHOD__ . ": LB for '$masterName' set to pos $pos\n" );
			$lb->waitFor( $pos );
		}
	}

	/**
	 * Notify the ChronologyProtector that the ILoadBalancer is about to shut
	 * down. Saves replication positions.
	 *
	 * @param ILoadBalancer $lb
	 * @return void
	 */
	public function shutdownLB( ILoadBalancer $lb ) {
		if ( !$this->enabled ) {
			return; // not enabled
		} elseif ( !$lb->hasOrMadeRecentMasterChanges( INF ) ) {
			// Only save the position if writes have been done on the connection
			return;
		}

		$masterName = $lb->getServerName( $lb->getWriterIndex() );
		if ( $lb->getServerCount() > 1 ) {
			$pos = $lb->getMasterPos();
			$this->logger->info( __METHOD__ . ": LB for '$masterName' has pos $pos\n" );
			$this->shutdownPositions[$masterName] = $pos;
		} else {
			$this->logger->info( __METHOD__ . ": DB '$masterName' touched\n" );
		}
		$this->shutdownTouchDBs[$masterName] = 1;
	}

	/**
	 * Notify the ChronologyProtector that the LBFactory is done calling shutdownLB() for now.
	 * May commit chronology data to persistent storage.
	 *
	 * @param callable|null $workCallback Work to do instead of waiting on syncing positions
	 * @param string $mode One of (sync, async); whether to wait on remote datacenters
	 * @return DBMasterPos[] Empty on success; returns the (db name => position) map on failure
	 */
	public function shutdown( callable $workCallback = null, $mode = 'sync' ) {
		if ( !$this->enabled ) {
			return [];
		}

		$store = $this->store;
		// Some callers might want to know if a user recently touched a DB.
		// These writes do not need to block on all datacenters receiving them.
		foreach ( $this->shutdownTouchDBs as $dbName => $unused ) {
			$store->set(
				$this->getTouchedKey( $this->store, $dbName ),
				microtime( true ),
				$store::TTL_DAY
			);
		}

		if ( !count( $this->shutdownPositions ) ) {
			return []; // nothing to save
		}

		$this->logger->info( __METHOD__ . ": saving master pos for " .
			implode( ', ', array_keys( $this->shutdownPositions ) ) . "\n"
		);

		// CP-protected writes should overwhemingly go to the master datacenter, so get DC-local
		// lock to merge the values. Use a DC-local get() and a synchronous all-DC set(). This
		// makes it possible for the BagOStuff class to write in parallel to all DCs with one RTT.
		if ( $store->lock( $this->key, 3 ) ) {
			if ( $workCallback ) {
				// Let the store run the work before blocking on a replication sync barrier. By the
				// time it's done with the work, the barrier should be fast if replication caught up.
				$store->addBusyCallback( $workCallback );
			}
			$ok = $store->set(
				$this->key,
				self::mergePositions( $store->get( $this->key ), $this->shutdownPositions ),
				self::POSITION_TTL,
				( $mode === 'sync' ) ? $store::WRITE_SYNC : 0
			);
			$store->unlock( $this->key );
		} else {
			$ok = false;
		}

		if ( !$ok ) {
			$bouncedPositions = $this->shutdownPositions;
			// Raced out too many times or stash is down
			$this->logger->warning( __METHOD__ . ": failed to save master pos for " .
				implode( ', ', array_keys( $this->shutdownPositions ) ) . "\n"
			);
		} elseif ( $mode === 'sync' &&
			$store->getQoS( $store::ATTR_SYNCWRITES ) < $store::QOS_SYNCWRITES_BE
		) {
			// Positions may not be in all datacenters, force LBFactory to play it safe
			$this->logger->info( __METHOD__ . ": store may not support synchronous writes." );
			$bouncedPositions = $this->shutdownPositions;
		} else {
			$bouncedPositions = [];
		}

		return $bouncedPositions;
	}

	/**
	 * @param string $dbName DB master name (e.g. "db1052")
	 * @return float|bool UNIX timestamp when client last touched the DB; false if not on record
	 * @since 1.28
	 */
	public function getTouched( $dbName ) {
		return $this->store->get( $this->getTouchedKey( $this->store, $dbName ) );
	}

	/**
	 * @param BagOStuff $store
	 * @param string $dbName
	 * @return string
	 */
	private function getTouchedKey( BagOStuff $store, $dbName ) {
		return $store->makeGlobalKey( __CLASS__, 'mtime', $this->clientId, $dbName );
	}

	/**
	 * Load in previous master positions for the client
	 */
	protected function initPositions() {
		if ( $this->initialized ) {
			return;
		}

		$this->initialized = true;
		if ( $this->wait ) {
			// If there is an expectation to see master positions with a certain min
			// timestamp, then block until they appear, or until a timeout is reached.
			if ( $this->waitForPosTime > 0.0 ) {
				$data = null;
				$loop = new WaitConditionLoop(
					function () use ( &$data ) {
						$data = $this->store->get( $this->key );

						return ( self::minPosTime( $data ) >= $this->waitForPosTime )
							? WaitConditionLoop::CONDITION_REACHED
							: WaitConditionLoop::CONDITION_CONTINUE;
					},
					$this->waitForPosTimeout
				);
				$result = $loop->invoke();
				$waitedMs = $loop->getLastWaitTime() * 1e3;

				if ( $result == $loop::CONDITION_REACHED ) {
					$msg = "expected and found pos time {$this->waitForPosTime} ({$waitedMs}ms)";
					$this->logger->debug( $msg );
				} else {
					$msg = "expected but missed pos time {$this->waitForPosTime} ({$waitedMs}ms)";
					$this->logger->info( $msg );
				}
			} else {
				$data = $this->store->get( $this->key );
			}

			$this->startupPositions = $data ? $data['positions'] : [];
			$this->logger->info( __METHOD__ . ": key is {$this->key} (read)\n" );
		} else {
			$this->startupPositions = [];
			$this->logger->info( __METHOD__ . ": key is {$this->key} (unread)\n" );
		}
	}

	/**
	 * @param array|bool $data
	 * @return float|null
	 */
	private static function minPosTime( $data ) {
		if ( !isset( $data['positions'] ) ) {
			return null;
		}

		$min = null;
		foreach ( $data['positions'] as $pos ) {
			if ( $pos instanceof DBMasterPos ) {
				$min = $min ? min( $pos->asOfTime(), $min ) : $pos->asOfTime();
			}
		}

		return $min;
	}

	/**
	 * @param array|bool $curValue
	 * @param DBMasterPos[] $shutdownPositions
	 * @return array
	 */
	private static function mergePositions( $curValue, array $shutdownPositions ) {
		/** @var $curPositions DBMasterPos[] */
		if ( $curValue === false ) {
			$curPositions = $shutdownPositions;
		} else {
			$curPositions = $curValue['positions'];
			// Use the newest positions for each DB master
			foreach ( $shutdownPositions as $db => $pos ) {
				if (
					!isset( $curPositions[$db] ) ||
					!( $curPositions[$db] instanceof DBMasterPos ) ||
					$pos->asOfTime() > $curPositions[$db]->asOfTime()
				) {
					$curPositions[$db] = $pos;
				}
			}
		}

		return [ 'positions' => $curPositions ];
	}
}
