<?php
/**
 * Automatic user rights promotion based on conditions specified
 * in $wgAutopromote.
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
 */

use MediaWiki\MediaWikiServices;

/**
 * This class checks if user can get extra rights
 * because of conditions specified in $wgAutopromote
 * @deprecated since 1.35 Use UserGroupManager instead.
 */
class Autopromote {
	/**
	 * Get the groups for the given user based on $wgAutopromote.
	 *
	 * @param User $user The user to get the groups for
	 * @return array Array of groups to promote to.
	 *
	 * @deprecated since 1.35. Use UserGroupManager::getUserAutopromoteGroups.
	 */
	public static function getAutopromoteGroups( User $user ) {
		return MediaWikiServices::getInstance()
			->getUserGroupManager()
			->getUserAutopromoteGroups( $user );
	}

	/**
	 * Get the groups for the given user based on the given criteria.
	 *
	 * Does not return groups the user already belongs to or has once belonged.
	 *
	 *
	 * @param User $user The user to get the groups for
	 * @param string $event Key in $wgAutopromoteOnce (each one has groups/criteria)
	 *
	 * @return array Groups the user should be promoted to.
	 *
	 * @see $wgAutopromoteOnce
	 *
	 * @deprecated since 1.35. Use UserGroupManager::getUserAutopromoteOnceGroups.
	 */
	public static function getAutopromoteOnceGroups( User $user, $event ) {
		return MediaWikiServices::getInstance()
			->getUserGroupManager()
			->getUserAutopromoteOnceGroups( $user, $event );
	}
}
