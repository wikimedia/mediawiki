<?php
/**
 * This class checks if user can get extra rights
 * because of conditions specified in $wgAutopromote
 */

class Autopromote {
	/**
	 * A function which may be assigned to a hook in order to check 
	 *   autopromotion of the current user (\ref $wgUser) to the specified 
	 *   group.
	 *    
	 * Contrary to autopromotion by \ref $wgAutopromote, the group will be 
	 *   possible to remove manually via Special:UserRights. In such case it
	 *   will not be re-added autmoatically. The user will also not lose the
	 *   group if they no longer meet the criteria.
	 *   
	 * Example configuration: 
	 * \code $wgHooks['ArticleSaveComplete'][] = array (
	 *     'Autopromote::autopromoteOnceHook', 
	 *     array( 'somegroup' => array(APCOND_EDITCOUNT, 200) )
	 * ); \endcode
	 * 
	 * The second array should be of the same format as \ref $wgAutopromote.
	 * 
	 * This funciton simply runs User::autopromoteOnce() on $wgUser. You may
	 *   run this method from your custom function if you wish.  
	 * 
	 * @param $criteria array Groups and conditions which must be met in order to
	 *   aquire these groups. Array of the same format as \ref $wgAutopromote.
	 *               
	 * @return Always true.
	 * 
	 * @see User::autopromoteOnce()
	 * @see $wgAutopromote
	 */
	public static function autopromoteOnceHook($criteria) {
		global $wgUser; 
		$wgUser->autopromoteOnce($criteria); 
		return true; 
	}
	
	/**
	 * Get the groups for the given user based on $wgAutopromote.
	 *
	 * @param $user User The user to get the groups for
	 * @return array Array of groups to promote to.
	 */
	public static function getAutopromoteGroups( User $user ) {
		global $wgAutopromote;

		$promote = array();

		foreach ( $wgAutopromote as $group => $cond ) {
			if ( self::recCheckCondition( $cond, $user ) ) {
				$promote[] = $group;
			}
		}

		wfRunHooks( 'GetAutoPromoteGroups', array( $user, &$promote ) );

		return $promote;
	}
	
	/**
	 * Get the groups for the given user based on the given criteria.
	 * 
	 * Does not return groups the user already belongs to or has once belonged.
	 * 
	 * @param $user The user to get the groups for
	 * @param $criteria array Groups and conditions the user must meet in order
	 *   to be promoted to these groups. Array of the same format as 
	 *   \ref $wgAutopromote. 
	 *               
	 * @return array Groups the user should be promoted to.
	 */
	public static function getAutopromoteOnceGroups( User $user, $criteria ) {
		$promote = array();
		
		//get the current groups 
		$currentGroups = $user->getGroups();
		
		foreach( $criteria as $group => $cond ) {
			//do not check if the user's already a member
			if ( in_array($group, $currentGroups))
				continue;
		
			//do not autopromote if the user has belonged to the group
			$formerGroups = $user->getFormerGroups();
			if ( in_array($group, $formerGroups) )
				continue;
				
			//finally - check the conditions 
			if ( self::recCheckCondition($cond, $user) )
				$promote[] = $group; 
		}
		return $promote;
	}

	/**
	 * Recursively check a condition.  Conditions are in the form
	 *   array( '&' or '|' or '^', cond1, cond2, ... )
	 * where cond1, cond2, ... are themselves conditions; *OR*
	 *   APCOND_EMAILCONFIRMED, *OR*
	 *   array( APCOND_EMAILCONFIRMED ), *OR*
	 *   array( APCOND_EDITCOUNT, number of edits ), *OR*
	 *   array( APCOND_AGE, seconds since registration ), *OR*
	 *   similar constructs defined by extensions.
	 * This function evaluates the former type recursively, and passes off to
	 * self::checkCondition for evaluation of the latter type.
	 *
	 * @param $cond Mixed: a condition, possibly containing other conditions
	 * @param $user User The user to check the conditions against
	 * @return bool Whether the condition is true
	 */
	private static function recCheckCondition( $cond, User $user ) {
		$validOps = array( '&', '|', '^', '!' );

		if ( is_array( $cond ) && count( $cond ) >= 2 && in_array( $cond[0], $validOps ) ) {
			# Recursive condition
			if ( $cond[0] == '&' ) {
				foreach ( array_slice( $cond, 1 ) as $subcond ) {
					if ( !self::recCheckCondition( $subcond, $user ) ) {
						return false;
					}
				}

				return true;
			} elseif ( $cond[0] == '|' ) {
				foreach ( array_slice( $cond, 1 ) as $subcond ) {
					if ( self::recCheckCondition( $subcond, $user ) ) {
						return true;
					}
				}

				return false;
			} elseif ( $cond[0] == '^' ) {
				$res = null;
				foreach ( array_slice( $cond, 1 ) as $subcond ) {
					if ( is_null( $res ) ) {
						$res = self::recCheckCondition( $subcond, $user );
					} else {
						$res = ( $res xor self::recCheckCondition( $subcond, $user ) );
					}
				}

				return $res;
			} elseif ( $cond[0] == '!' ) {
				foreach ( array_slice( $cond, 1 ) as $subcond ) {
					if ( self::recCheckCondition( $subcond, $user ) ) {
						return false;
					}
				}

				return true;
			}
		}
		# If we got here, the array presumably does not contain other condi-
		# tions; it's not recursive.  Pass it off to self::checkCondition.
		if ( !is_array( $cond ) ) {
			$cond = array( $cond );
		}

		return self::checkCondition( $cond, $user );
	}

	/**
	 * As recCheckCondition, but *not* recursive.  The only valid conditions
	 * are those whose first element is APCOND_EMAILCONFIRMED/APCOND_EDITCOUNT/
	 * APCOND_AGE.  Other types will throw an exception if no extension evalu-
	 * ates them.
	 *
	 * @param $cond Array: A condition, which must not contain other conditions
	 * @param $user User The user to check the condition against
	 * @return bool Whether the condition is true for the user
	 */
	private static function checkCondition( $cond, User $user ) {
		global $wgEmailAuthentication;
		if ( count( $cond ) < 1 ) {
			return false;
		}

		switch( $cond[0] ) {
			case APCOND_EMAILCONFIRMED:
				if ( User::isValidEmailAddr( $user->getEmail() ) ) {
					if ( $wgEmailAuthentication ) {
						return (bool)$user->getEmailAuthenticationTimestamp();
					} else {
						return true;
					}
				}
				return false;
			case APCOND_EDITCOUNT:
				return $user->getEditCount() >= $cond[1];
			case APCOND_AGE:
				$age = time() - wfTimestampOrNull( TS_UNIX, $user->getRegistration() );
				return $age >= $cond[1];
			case APCOND_AGE_FROM_EDIT:
				$age = time() - wfTimestampOrNull( TS_UNIX, $user->getFirstEditTimestamp() );
				return $age >= $cond[1];
			case APCOND_INGROUPS:
				$groups = array_slice( $cond, 1 );
				return count( array_intersect( $groups, $user->getGroups() ) ) == count( $groups );
			case APCOND_ISIP:
				return $cond[1] == wfGetIP();
			case APCOND_IPINRANGE:
				return IP::isInRange( wfGetIP(), $cond[1] );
			case APCOND_BLOCKED:
				return $user->isBlocked();
			default:
				$result = null;
				wfRunHooks( 'AutopromoteCondition', array( $cond[0], array_slice( $cond, 1 ), $user, &$result ) );
				if ( $result === null ) {
					throw new MWException( "Unrecognized condition {$cond[0]} for autopromotion!" );
				}

				return (bool)$result;
		}
	}
}
