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
	/** @var Array (DB master name => position) */
	protected $startupPositions = array();
	/** @var Array (DB master name => position) */
	protected $shutdownPositions = array();

	protected $initialized = false; // bool; whether the session data was loaded

	/**
	 * Initialise a LoadBalancer to give it appropriate chronology protection.
	 *
	 * If the session has a previous master position recorded, this will try to
	 * make sure that the next query to a slave of that master will see changes up
	 * to that position by delaying execution. The delay may timeout and allow stale
	 * data if no non-lagged slaves are available.
	 *
	 * @param $lb LoadBalancer
	 * @return void
	 */
	public function initLB( LoadBalancer $lb ) {
		if ( $lb->getServerCount() <= 1 ) {
			return; // non-replicated setup
		}
		if ( !$this->initialized ) {
			$this->initialized = true;
			if ( isset( $_SESSION[__CLASS__] ) && is_array( $_SESSION[__CLASS__] ) ) {
				$this->startupPositions = $_SESSION[__CLASS__];
			}
		}
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
	 * @param $lb LoadBalancer
	 * @return void
	 */
	public function shutdownLB( LoadBalancer $lb ) {
		if ( session_id() == '' || $lb->getServerCount() <= 1 ) {
			return; // don't start a session; don't bother with non-replicated setups
		}
		$masterName = $lb->getServerName( 0 );
		if ( isset( $this->shutdownPositions[$masterName] ) ) {
			return; // already done
		}
		// Only save the position if writes have been done on the connection
		$db = $lb->getAnyOpenConnection( 0 );
		$info = $lb->parentInfo();
		if ( !$db || !$db->doneWrites() ) {
			wfDebug( __METHOD__ . ": LB {$info['id']}, no writes done\n" );
			return;
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
		if ( session_id() != '' && count( $this->shutdownPositions ) ) {
			wfDebug( __METHOD__ . ": saving master pos for " .
				count( $this->shutdownPositions ) . " master(s)\n" );
			$_SESSION[__CLASS__] = $this->shutdownPositions;
		}
	}
}
