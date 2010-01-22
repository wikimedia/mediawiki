<?php

/**
 * Class containing static functions for working with
 * logs of patrol events
 *
 * @author Rob Church <robchur@gmail.com>
 */
class PatrolLog {

	/**
	 * Record a log event for a change being patrolled
	 *
	 * @param $rc Mixed: change identifier or RecentChange object
	 * @param $auto Boolean: was this patrol event automatic?
	 */
	public static function record( $rc, $auto = false ) {
		if( !( $rc instanceof RecentChange ) ) {
			$rc = RecentChange::newFromId( $rc );
			if( !is_object( $rc ) )
				return false;
		}
		$title = Title::makeTitleSafe( $rc->getAttribute( 'rc_namespace' ), $rc->getAttribute( 'rc_title' ) );
		if( is_object( $title ) ) {
			$params = self::buildParams( $rc, $auto );
			$log = new LogPage( 'patrol', false, $auto ? "skipUDP" : "UDP" ); # False suppresses RC entries
			$log->addEntry( 'patrol', $title, '', $params );
			return true;
		}
		return false;
	}

	/**
	 * Generate the log action text corresponding to a patrol log item
	 *
	 * @param $title Title of the page that was patrolled
	 * @param $params Array: log parameters (from logging.log_params)
	 * @param $skin Skin to use for building links, etc.
	 * @return String
	 */
	public static function makeActionText( $title, $params, $skin ) {
		list( $cur, /* $prev */, $auto ) = $params;
		if( is_object( $skin ) ) {
			# Standard link to the page in question
			$link = $skin->link( $title );
			if( $title->exists() ) {
				# Generate a diff link
				$query = array(
					'oldid' => $cur,
					'diff' => 'prev'
				);

				$diff = $skin->link(
					$title,
					htmlspecialchars( wfMsg( 'patrol-log-diff', $cur ) ),
					array(),
					$query,
					array( 'known', 'noclasses' )
				);
			} else {
				# Don't bother with a diff link, it's useless
				$diff = htmlspecialchars( wfMsg( 'patrol-log-diff', $cur ) );
			}
			# Indicate whether or not the patrolling was automatic
			$auto = $auto ? wfMsgHtml( 'patrol-log-auto' ) : '';
			# Put it all together
			return wfMsgHtml( 'patrol-log-line', $diff, $link, $auto );
		} else {
			$text = $title->getPrefixedText();
			return wfMsgForContent( 'patrol-log-line', wfMsgHtml('patrol-log-diff',$cur), "[[$text]]", '' );
		}
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
			$change->getAttribute( 'rc_this_oldid' ),
			$change->getAttribute( 'rc_last_oldid' ),
			(int)$auto
		);
	}
}
