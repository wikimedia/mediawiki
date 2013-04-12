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
 *
 * When closing a connection, the position will be saved in session. Upon opening a follow-up
 * connection, that/those position(s) will be read from session and we'll wait for the connection
 * to catch up to that/those position(s), to ensure no data is read that lags behind their
 * previous state.
 */
class ChronologyProtector {
	/**
	 * Arrays of connection positions loaded from session (startup) and positions to
	 * be saved to session (shutdown)
	 *
	 * @var array
	 */
	protected $startupPositions = array(), $shutdownPositions = array();

	/**
	 * Initialise a LoadBalancer to give it appropriate chronology protection.
	 *
	 * @param $lb LoadBalancer
	 */
	public function initLB( $lb ) {
		// Load previously saved LB positions from session
		if ( $this->startupPositions === null ) {
			if ( !empty( $_SESSION[__CLASS__] ) ) {
				$this->startupPositions = $_SESSION[__CLASS__];
			}
		}
		if ( !$this->startupPositions ) {
			return;
		}
		$masterName = $lb->getServerName( 0 );

		if ( $lb->getServerCount() > 1 && !empty( $this->startupPositions[$masterName] ) ) {
			$info = $lb->parentInfo();
			$pos = $this->startupPositions[$masterName];
			wfDebug( __METHOD__ . ": LB " . $info['id'] . " waiting for master pos $pos\n" );

			// Wait for previously saved position
			$lb->waitFor( $this->startupPositions[$masterName] );
		}
	}

	/**
	 * Notify the ChronologyProtector that the LoadBalancer is about to shut
	 * down. Saves replication positions.
	 *
	 * @param $lb LoadBalancer
	 */
	public function shutdownLB( $lb ) {
		// Don't start a session, don't bother with non-replicated setups
		if ( strval( session_id() ) == '' || $lb->getServerCount() <= 1 ) {
			return;
		}
		$masterName = $lb->getServerName( 0 );
		if ( isset( $this->shutdownPositions[$masterName] ) ) {
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
		$this->shutdownPositions[$masterName] = $pos;
	}

	/**
	 * Notify the ChronologyProtector that the LBFactory is done calling shutdownLB() for now.
	 * May commit chronology data to persistent storage.
	 */
	public function shutdown() {
		if ( session_id() != '' && count( $this->shutdownPositions ) ) {
			wfDebug( __METHOD__ . ": saving master pos for " .
				count( $this->shutdownPositions ) . " master(s)\n" );
			$_SESSION[__CLASS__] = $this->shutdownPositions;
		}
	}
}
