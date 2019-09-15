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
			if ( self::recCheckCondition( $cond, $user ) ) {
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
				if ( self::recCheckCondition( $cond, $user ) ) {
					$promote[] = $group;
				}
			}
		}

		return $promote;
	}

	/**
	 * Recursively check a condition.  Conditions are in the form
	 *   [ '&' or '|' or '^' or '!', cond1, cond2, ... ]
	 * where cond1, cond2, ... are themselves conditions; *OR*
	 *   APCOND_EMAILCONFIRMED, *OR*
	 *   [ APCOND_EMAILCONFIRMED ], *OR*
	 *   [ APCOND_EDITCOUNT, number of edits ], *OR*
	 *   [ APCOND_AGE, seconds since registration ], *OR*
	 *   similar constructs defined by extensions.
	 * This function evaluates the former type recursively, and passes off to
	 * self::checkCondition for evaluation of the latter type.
	 *
	 * @param mixed $cond A condition, possibly containing other conditions
	 * @param User $user The user to check the conditions against
	 * @return bool Whether the condition is true
	 */
	private static function recCheckCondition( $cond, User $user ) {
		$validOps = [ '&', '|', '^', '!' ];

		if ( is_array( $cond ) && count( $cond ) >= 2 && in_array( $cond[0], $validOps ) ) {
			# Recursive condition
			if ( $cond[0] == '&' ) { // AND (all conds pass)
				foreach ( array_slice( $cond, 1 ) as $subcond ) {
					if ( !self::recCheckCondition( $subcond, $user ) ) {
						return false;
					}
				}

				return true;
			} elseif ( $cond[0] == '|' ) { // OR (at least one cond passes)
				foreach ( array_slice( $cond, 1 ) as $subcond ) {
					if ( self::recCheckCondition( $subcond, $user ) ) {
						return true;
					}
				}

				return false;
			} elseif ( $cond[0] == '^' ) { // XOR (exactly one cond passes)
				if ( count( $cond ) > 3 ) {
					wfWarn( 'recCheckCondition() given XOR ("^") condition on three or more conditions.' .
						' Check your $wgAutopromote and $wgAutopromoteOnce settings.' );
				}
				return self::recCheckCondition( $cond[1], $user )
					xor self::recCheckCondition( $cond[2], $user );
			} elseif ( $cond[0] == '!' ) { // NOT (no conds pass)
				foreach ( array_slice( $cond, 1 ) as $subcond ) {
					if ( self::recCheckCondition( $subcond, $user ) ) {
						return false;
					}
				}

				return true;
			}
		}
		// If we got here, the array presumably does not contain other conditions;
		// it's not recursive.  Pass it off to self::checkCondition.
		if ( !is_array( $cond ) ) {
			$cond = [ $cond ];
		}

		return self::checkCondition( $cond, $user );
	}

	/**
	 * As recCheckCondition, but *not* recursive.  The only valid conditions
	 * are those whose first element is APCOND_EMAILCONFIRMED/APCOND_EDITCOUNT/
	 * APCOND_AGE.  Other types will throw an exception if no extension evaluates them.
	 *
	 * @param array $cond A condition, which must not contain other conditions
	 * @param User $user The user to check the condition against
	 * @throws MWException
	 * @return bool Whether the condition is true for the user
	 */
	private static function checkCondition( $cond, User $user ) {
		global $wgEmailAuthentication;
		if ( count( $cond ) < 1 ) {
			return false;
		}

		switch ( $cond[0] ) {
			case APCOND_EMAILCONFIRMED:
				if ( Sanitizer::validateEmail( $user->getEmail() ) ) {
					if ( $wgEmailAuthentication ) {
						return (bool)$user->getEmailAuthenticationTimestamp();
					} else {
						return true;
					}
				}
				return false;
			case APCOND_EDITCOUNT:
				$reqEditCount = $cond[1];

				// T157718: Avoid edit count lookup if specified edit count is 0 or invalid
				if ( $reqEditCount <= 0 ) {
					return true;
				}
				return $user->getEditCount() >= $reqEditCount;
			case APCOND_AGE:
				$age = time() - (int)wfTimestampOrNull( TS_UNIX, $user->getRegistration() );
				return $age >= $cond[1];
			case APCOND_AGE_FROM_EDIT:
				$age = time() - (int)wfTimestampOrNull( TS_UNIX, $user->getFirstEditTimestamp() );
				return $age >= $cond[1];
			case APCOND_INGROUPS:
				$groups = array_slice( $cond, 1 );
				return count( array_intersect( $groups, $user->getGroups() ) ) == count( $groups );
			case APCOND_ISIP:
				return $cond[1] == $user->getRequest()->getIP();
			case APCOND_IPINRANGE:
				return IP::isInRange( $user->getRequest()->getIP(), $cond[1] );
			case APCOND_BLOCKED:
				return $user->getBlock() && $user->getBlock()->isSitewide();
			case APCOND_ISBOT:
				return in_array( 'bot', MediaWikiServices::getInstance()
					->getPermissionManager()
					->getGroupPermissions( $user->getGroups() ) );
			default:
				$result = null;
				Hooks::run( 'AutopromoteCondition', [ $cond[0],
					array_slice( $cond, 1 ), $user, &$result ] );
				if ( $result === null ) {
					throw new MWException( "Unrecognized condition {$cond[0]} for autopromotion!" );
				}

				return (bool)$result;
		}
	}
}
