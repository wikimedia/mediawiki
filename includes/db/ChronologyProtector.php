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
	/** @var array Map of (ip: <IP>, agent: <user-agent>) */
	protected $client;
	/** @var DBMasterPos[] Map of (DB master name => position) */
	protected $startupPositions = array();
	/** @var DBMasterPos[] Map of (DB master name => position) */
	protected $shutdownPositions = array();

	/** @var bool Whether to actually wait on and record positions */
	protected $enabled = true;
	/** @var bool Whether the session data was loaded */
	protected $initialized = false;

	/**
	 * @param BagOStuff $store
	 * @param array $client Map of (ip: <IP>, agent: <user-agent>)
	 * @since 1.27
	 */
	public function __construct( BagOStuff $store, array $client ) {
		$this->store = $store;
		$this->client = $client;
		$this->key = $store->makeGlobalKey(
			'ChronologyProtector',
			md5( $client['ip'] . "\n" . $client['agent'] )
		);
	}

	/**
	 * @param bool $enabled
	 * @since 1.27
	 */
	public function setEnabled( $enabled ) {
		$this->enabled = $enabled;
	}

	/**
	 * Initialise a LoadBalancer to give it appropriate chronology protection.
	 *
	 * If the session has a previous master position recorded, this will try to
	 * make sure that the next query to a slave of that master will see changes up
	 * to that position by delaying execution. The delay may timeout and allow stale
	 * data if no non-lagged slaves are available.
	 *
	 * @param LoadBalancer $lb
	 * @return void
	 */
	public function initLB( LoadBalancer $lb ) {
		if ( !$this->enabled || $lb->getServerCount() <= 1 ) {
			return; // non-replicated setup or disabled
		}

		$this->initialize();

		$masterName = $lb->getServerName( 0 );
		if ( !empty( $this->startupPositions[$masterName] ) ) {
			$info = $lb->parentInfo();
			$pos = $this->startupPositions[$masterName];
			wfDebug( __METHOD__ . ": LB " . $info['id'] . " waiting for master pos $pos\n" );
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
		if ( !$this->enabled || $lb->getServerCount() <= 1 ) {
			return; // non-replicated setup or disabled
		}

		$info = $lb->parentInfo();
		$masterName = $lb->getServerName( 0 );
		if ( isset( $this->shutdownPositions[$masterName] ) ) {
			wfDebug( __METHOD__ . ": LB {$info['id']} already shut down\n" );

			return; // already done
		}

		// Only save the position if writes have been done on the connection
		$db = $lb->getAnyOpenConnection( 0 );
		if ( !$db || !$db->doneWrites() ) {
			wfDebug( __METHOD__ . ": LB {$info['id']}, no writes done\n" );

			return; // nothing to do
		}

		$pos = $db->getMasterPos();
		wfDebug( __METHOD__ . ": LB {$info['id']} has master pos $pos\n" );
		$this->shutdownPositions[$masterName] = $pos;
	}

	/**
	 * Notify the ChronologyProtector that the LBFactory is done calling shutdownLB() for now.
	 * May commit chronology data to persistent storage.
	 *
	 * @return void
	 */
	public function shutdown() {
		if ( !count( $this->shutdownPositions ) ) {
			return; // nothing to save
		}

		wfDebug( __METHOD__ . ": saving master pos for " .
			implode( ', ', array_keys( $this->shutdownPositions ) ) . "\n" );

		$thesePositions = $this->shutdownPositions;
		$this->store->merge(
			$this->key,
			function ( $store, $key, $curValue ) use ( $thesePositions ) {
				/** @var $newPositions DBMasterPos[] */
				if ( $curValue === false ) {
					$newPositions = $thesePositions;
				} else {
					$newPositions = $curValue['positions'];
					// Use the newest positions for each DB master
					foreach ( $thesePositions as $db => $pos ) {
						if ( !isset( $newPositions[$db] )
							|| $pos->asOfTime() > $newPositions[$db]->asOfTime()
						) {
							$newPositions[$db] = $pos;
						}
					}
				}

				return array( 'positions' => $newPositions );
			},
			3600,
			3
		);
	}

	/**
	 * Load in previous master positions for the session
	 */
	protected function initialize() {
		if ( !$this->initialized ) {
			$this->initialized = true;
			$data = $this->store->get( $this->key );
			$this->startupPositions = $data ? $data['positions'] : array();
			wfDebug( __METHOD__ . ": initialized for key {$this->key}\n" );
		}
	}
}
