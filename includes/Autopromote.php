<?php

/**
 * This class checks if user can get extra rights
 * because of conditions specified in $wgAutopromote
 */
class Autopromote {	
	public static function autopromoteUser( $user ) {
		global $wgAutopromote;
		$promote = array();
		foreach( $wgAutopromote as $group => $cond ) {
			if( self::recCheckCondition( $cond, $user ) )
				$promote[] = $group;
		}
		return $promote;
	}

	//@private
	static function recCheckCondition( $cond, $user ) {
		$validOps = array( '&', '|', '^' );
		if( is_array( $cond ) && count( $cond ) > 0 && in_array( $cond[0], $validOps ) ) {
			if( $cond[0] == '&' ) {
				foreach( array_slice( $cond, 1 ) as $subcond )
					if( !self::recCheckCondition( $subcond, $user ) )
						return false;
				return true;
			} elseif( $cond[0] == '|' ) {
				foreach( array_slice( $cond, 1 ) as $subcond ) 
					if( self::recCheckCondition( $subcond, $user ) )
						return true;
				return false;
			} elseif( $cond[0] == '^' ) {
				if( count( $cond ) < 3 )
					return false;
				return self::recCheckCondition( $cond[1], $user )
					xor self::recCheckCondition( $cond[2], $user );
			}
		}
		if( !is_array( $cond ) )
			$cond = array( $cond );
		return self::checkCondition( $cond, $user );
	}

	static function checkCondition( $cond, $user ) {
		if( count( $cond ) < 1 )
			return false;
		switch( $cond[0] ) {
			case APCOND_EMAILCONFIRMED:
				if( User::isValidEmailAddr( $user->getEmail() ) ) {
					global $wgEmailAuthentication;
					if( $wgEmailAuthentication ) {
						return boolval( $user->getEmailAuthenticationTimestamp() );
					} else {
						return true;
					}
				}
				return false;
			case APCOND_EDITCOUNT:
				return $user->getEditCount() > $cond[1];
			case APCOND_AGE:
				$age = time() - wfTimestampOrNull( TS_UNIX, $user->getRegistration() );
				return $age >= $cond[1];
			case APCOND_INGROUPS:
			default:
				$result = false;
				wfRunHooks( 'AutopromoteCondition', array( $cond[0], array_slice( $cond, 1 ), &$result ) );
				return $result;
		}
	}
}