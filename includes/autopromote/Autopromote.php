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

/**
 * This class checks if user can get extra rights
 * because of conditions specified in $wgAutopromote
 */
class Autopromote {
	/**
	 * Get the groups for the given user based on $wgAutopromote.
	 *
	 * @param User $user The user to get the groups for
	 * @return array Array of groups to promote to.
	 */
	public static function getAutopromoteGroups( User $user ) {
		global $wgAutopromote;

		$promote = [];

		foreach ( $wgAutopromote as $group => $cond ) {
			$condObj = AutopromoteConditionBase::newFromArray( $cond );
			if ( $condObj->evaluate( $user ) ) {
				$promote[] = $group;
			}
		}

		Hooks::run( 'GetAutoPromoteGroups', [ $user, &$promote ] );

		return $promote;
	}

	/**
	 * Get the groups for the given user based on the given criteria.
	 *
	 * Does not return groups the user already belongs to or has once belonged.
	 *
	 * @param User $user The user to get the groups for
	 * @param string $event Key in $wgAutopromoteOnce (each one has groups/criteria)
	 *
	 * @return array Groups the user should be promoted to.
	 *
	 * @see $wgAutopromoteOnce
	 */
	public static function getAutopromoteOnceGroups( User $user, $event ) {
		global $wgAutopromoteOnce;

		$promote = [];

		if ( isset( $wgAutopromoteOnce[$event] ) && count( $wgAutopromoteOnce[$event] ) ) {
			$currentGroups = $user->getGroups();
			$formerGroups = $user->getFormerGroups();
			foreach ( $wgAutopromoteOnce[$event] as $group => $cond ) {
				// Do not check if the user's already a member
				if ( in_array( $group, $currentGroups ) ) {
					continue;
				}
				// Do not autopromote if the user has belonged to the group
				if ( in_array( $group, $formerGroups ) ) {
					continue;
				}
				// Finally - check the conditions
				$condObj = AutopromoteConditionBase::newFromArray( $cond );
				if ( $condObj->evaluate( $user ) ) {
					$promote[] = $group;
				}
			}
		}

		return $promote;
	}
}
