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

use MediaWiki\Page\PageReferenceValue;
use MediaWiki\User\UserIdentity;

/**
 * Class containing static functions for working with
 * logs of patrol events
 */
class PatrolLog {

	/**
	 * Record a log event for a change being patrolled
	 *
	 * @param int|RecentChange $rc Change identifier or RecentChange object
	 * @param bool $auto Was this patrol event automatic?
	 * @param UserIdentity $user User performing the action
	 * @param string|string[]|null $tags Change tags to add to the patrol log entry
	 *   ($user should be able to add the specified tags before this is called)
	 *
	 * @return bool
	 */
	public static function record( $rc, $auto, UserIdentity $user, $tags = null ) {
		// Do not log autopatrol actions: T184485
		if ( $auto ) {
			return false;
		}

		if ( !$rc instanceof RecentChange ) {
			$rc = RecentChange::newFromId( $rc );
			if ( !is_object( $rc ) ) {
				return false;
			}
		}

		$entry = new ManualLogEntry( 'patrol', 'patrol' );

		// B/C: ->getPage() on RC will return a page reference or null, reconcile this in
		//      $entry->setTarget() call so we don't throw.
		$page = $rc->getPage() ?? PageReferenceValue::localReference( NS_SPECIAL, 'Badtitle' );
		$entry->setTarget( $page );
		$entry->setParameters( self::buildParams( $rc ) );
		$entry->setPerformer( $user );
		$entry->addTags( $tags );
		$logid = $entry->insert();
		$entry->publish( $logid, 'udp' );

		return true;
	}

	/**
	 * Prepare log parameters for a patrolled change
	 *
	 * @param RecentChange $change RecentChange to represent
	 * @return array
	 */
	private static function buildParams( $change ) {
		return [
			'4::curid' => $change->getAttribute( 'rc_this_oldid' ),
			'5::previd' => $change->getAttribute( 'rc_last_oldid' ),
			'6::auto' => 0
		];
	}
}
