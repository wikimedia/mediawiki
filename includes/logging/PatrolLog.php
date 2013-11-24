<?php
/**
 * Specific methods for the patrol log.
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
 * @author Rob Church <robchur@gmail.com>
 * @author Niklas Laxström
 */

/**
 * Class containing static functions for working with
 * logs of patrol events
 */
class PatrolLog {

	/**
	 * Record a log event for a change being patrolled
	 *
	 * @param $rc Mixed: change identifier or RecentChange object
	 * @param $auto Boolean: was this patrol event automatic?
	 * @param $user User: user performing the action or null to use $wgUser
	 *
	 * @return bool
	 */
	public static function record( $rc, $auto = false, User $user = null ) {
		global $wgLogAutopatrol;

		// do not log autopatrolled edits if setting disables it
		if ( $auto && !$wgLogAutopatrol ) {
			return false;
		}

		if ( !$rc instanceof RecentChange ) {
			$rc = RecentChange::newFromId( $rc );
			if ( !is_object( $rc ) ) {
				return false;
			}
		}

		if ( !$user ) {
			global $wgUser;
			$user = $wgUser;
		}

		$entry = new ManualLogEntry( 'patrol', 'patrol' );
		$entry->setTarget( $rc->getTitle() );
		$entry->setParameters( self::buildParams( $rc, $auto ) );
		$entry->setPerformer( $user );
		$logid = $entry->insert();
		if ( !$auto ) {
			$entry->publish( $logid, 'udp' );
		}
		return true;
	}

	/**
	 * Prepare log parameters for a patrolled change
	 *
	 * @param $change RecentChange to represent
	 * @param $auto Boolean: whether the patrol event was automatic
	 * @return Array
	 */
	private static function buildParams( $change, $auto ) {
		return array(
			'4::curid' => $change->getAttribute( 'rc_this_oldid' ),
			'5::previd' => $change->getAttribute( 'rc_last_oldid' ),
			'6::auto' => (int)$auto
		);
	}

}
