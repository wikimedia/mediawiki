<?php

/**
 * Class containing static functions for working with
 * logs of patrol events
 *
 * @author Rob Church <robchur@gmail.com>
 * @author Niklas Laxström
 */
class PatrolLog {

	/**
	 * Record a log event for a change being patrolled
	 *
	 * @param $rc Mixed: change identifier or RecentChange object
	 * @param $auto Boolean: was this patrol event automatic?
	 * @param $performer User: user performing the action or null to use $wgUser
	 *
	 * @return bool
	 */
	public static function record( $rc, $auto = false, User $user = null ) {
		if ( !$rc instanceof RecentChange ) {
			$rc = RecentChange::newFromId( $rc );
			if ( !is_object( $rc ) ) {
				return false;
			}
		}

		$title = Title::makeTitleSafe( $rc->getAttribute( 'rc_namespace' ), $rc->getAttribute( 'rc_title' ) );
		if( $title ) {
			if ( !$user ) {
				global $wgUser;
				$user = $wgUser;
			}

			$entry = new ManualLogEntry( 'patrol', 'patrol' );
			$entry->setTarget( $title );
			$entry->setParameters( self::buildParams( $rc, $auto ) );
			$entry->setPerformer( $user );
			$logid = $entry->insert();
			if ( !$auto ) {
				$entry->publish( $logid, 'udp' );
			}
			return true;
		}
		return false;
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
