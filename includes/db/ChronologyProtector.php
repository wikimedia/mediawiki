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

/**
 * Class for ensuring a consistent ordering of events as seen by the user, despite replication.
 * Kind of like Hawking's [[Chronology Protection Agency]].
 */
class ChronologyProtector {
	/** @var BagOStuff */
	protected $store;

	/** @var string Storage key name */
	protected $key;
	/** @var string Hash of client parameters */
	protected $clientId;
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

	/**
	 * @param BagOStuff $store
	 * @param array $client Map of (ip: <IP>, agent: <user-agent>)
	 * @since 1.27
	 */
	public function __construct( BagOStuff $store, array $client ) {
		$this->store = $store;
		$this->clientId = md5( $client['ip'] . "\n" . $client['agent'] );
		$this->key = $store->makeGlobalKey( __CLASS__, $this->clientId );
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
	 * Initialise a LoadBalancer to give it appropriate chronology protection.
	 *
	 * If the stash has a previous master position recorded, this will try to
	 * make sure that the next query to a replica DB of that master will see changes up
	 * to that position by delaying execution. The delay may timeout and allow stale
	 * data if no non-lagged replica DBs are available.
	 *
	 * @param LoadBalancer $lb
	 * @return void
	 */
	public function initLB( LoadBalancer $lb ) {
		if ( !$this->enabled || $lb->getServerCount() <= 1 ) {
			return; // non-replicated setup or disabled
		}

		$this->initPositions();

		$masterName = $lb->getServerName( $lb->getWriterIndex() );
		if ( !empty( $this->startupPositions[$masterName] ) ) {
			$pos = $this->startupPositions[$masterName];
			wfDebugLog( 'replication', __METHOD__ . ": LB for '$masterName' set to pos $pos\n" );
			$lb->waitFor( $pos );
		}
	}

	/**
	 * Notify the ChronologyProtector that the LoadBalancer is about to shut
	 * down. Saves replication positions.
	 *
	 * @param LoadBalancer $lb
	 * @return void
	 */
	public function shutdownLB( LoadBalancer $lb ) {
		if ( !$this->enabled ) {
			return; // not enabled
		} elseif ( !$lb->hasOrMadeRecentMasterChanges( INF ) ) {
			// Only save the position if writes have been done on the connection
			return;
		}

		$masterName = $lb->getServerName( $lb->getWriterIndex() );
		if ( $lb->getServerCount() > 1 ) {
			$pos = $lb->getMasterPos();
			wfDebugLog( 'replication', __METHOD__ . ": LB for '$masterName' has pos $pos\n" );
			$this->shutdownPositions[$masterName] = $pos;
		} else {
			wfDebugLog( 'replication', __METHOD__ . ": DB '$masterName' touched\n" );
		}
		$this->shutdownTouchDBs[$masterName] = 1;
	}

	/**
	 * Notify the ChronologyProtector that the LBFactory is done calling shutdownLB() for now.
	 * May commit chronology data to persistent storage.
	 *
	 * @return DBMasterPos[] Empty on success; returns the (db name => position) map on failure
	 */
	public function shutdown() {
		if ( !$this->enabled ) {
			return [];
		}

		// Some callers might want to know if a user recently touched a DB.
		// These writes do not need to block on all datacenters receiving them.
		foreach ( $this->shutdownTouchDBs as $dbName => $unused ) {
			$this->store->set(
				$this->getTouchedKey( $this->store, $dbName ),
				microtime( true ),
				BagOStuff::TTL_DAY
			);
		}

		if ( !count( $this->shutdownPositions ) ) {
			return []; // nothing to save
		}

		wfDebugLog( 'replication',
			__METHOD__ . ": saving master pos for " .
			implode( ', ', array_keys( $this->shutdownPositions ) ) . "\n"
		);

		// CP-protected writes should overwhemingly go to the master datacenter, so get DC-local
		// lock to merge the values. Use a DC-local get() and a synchronous all-DC set(). This
		// makes it possible for the BagOStuff class to write in parallel to all DCs with one RTT.
		if ( $this->store->lock( $this->key, 3 ) ) {
			$ok = $this->store->set(
				$this->key,
				self::mergePositions( $this->store->get( $this->key ), $this->shutdownPositions ),
				BagOStuff::TTL_MINUTE,
				BagOStuff::WRITE_SYNC
			);
			$this->store->unlock( $this->key );
		} else {
			$ok = false;
		}

		if ( !$ok ) {
			// Raced out too many times or stash is down
			wfDebugLog( 'replication',
				__METHOD__ . ": failed to save master pos for " .
				implode( ', ', array_keys( $this->shutdownPositions ) ) . "\n"
			);

			return $this->shutdownPositions;
		}

		return [];
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
			$data = $this->store->get( $this->key );
			$this->startupPositions = $data ? $data['positions'] : [];
			wfDebugLog( 'replication', __METHOD__ . ": key is {$this->key} (read)\n" );
		} else {
			$this->startupPositions = [];
			wfDebugLog( 'replication', __METHOD__ . ": key is {$this->key} (unread)\n" );
		}
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
				if ( !isset( $curPositions[$db] )
					|| $pos->asOfTime() > $curPositions[$db]->asOfTime()
				) {
					$curPositions[$db] = $pos;
				}
			}
		}

		return [ 'positions' => $curPositions ];
	}
}
