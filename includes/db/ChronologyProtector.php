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
	var $startupPos;
	var $shutdownPos = array();

	/**
	 * Initialise a LoadBalancer to give it appropriate chronology protection.
	 *
	 * @param $lb LoadBalancer
	 */
	function initLB( $lb ) {
		if ( $this->startupPos === null ) {
			if ( !empty( $_SESSION[__CLASS__] ) ) {
				$this->startupPos = $_SESSION[__CLASS__];
			}
		}
		if ( !$this->startupPos ) {
			return;
		}
		$masterName = $lb->getServerName( 0 );

		if ( $lb->getServerCount() > 1 && !empty( $this->startupPos[$masterName] ) ) {
			$info = $lb->parentInfo();
			$pos = $this->startupPos[$masterName];
			wfDebug( __METHOD__ . ": LB " . $info['id'] . " waiting for master pos $pos\n" );
			$lb->waitFor( $this->startupPos[$masterName] );
		}
	}

	/**
	 * Notify the ChronologyProtector that the LoadBalancer is about to shut
	 * down. Saves replication positions.
	 *
	 * @param $lb LoadBalancer
	 */
	function shutdownLB( $lb ) {
		// Don't start a session, don't bother with non-replicated setups
		if ( strval( session_id() ) == '' || $lb->getServerCount() <= 1 ) {
			return;
		}
		$masterName = $lb->getServerName( 0 );
		if ( isset( $this->shutdownPos[$masterName] ) ) {
			// Already done
			return;
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
		$this->shutdownPos[$masterName] = $pos;
	}

	/**
	 * Notify the ChronologyProtector that the LBFactory is done calling shutdownLB() for now.
	 * May commit chronology data to persistent storage.
	 */
	function shutdown() {
		if ( session_id() != '' && count( $this->shutdownPos ) ) {
			wfDebug( __METHOD__ . ": saving master pos for " .
				count( $this->shutdownPos ) . " master(s)\n" );
			$_SESSION[__CLASS__] = $this->shutdownPos;
		}
	}
}
