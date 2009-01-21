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
	 * @param mixed $change Change identifier or RecentChange object
	 * @param bool $auto Was this patrol event automatic?
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
	 * @param Title $title Title of the page that was patrolled
	 * @param array $params Log parameters (from logging.log_params)
	 * @param Skin $skin Skin to use for building links, etc.
	 * @return string
	 */
	public static function makeActionText( $title, $params, $skin ) {
		list( $cur, /* $prev */, $auto ) = $params;
		if( is_object( $skin ) ) {
			# Standard link to the page in question
			$link = $skin->makeLinkObj( $title );
			if( $title->exists() ) {
				# Generate a diff link
				$bits[] = 'oldid=' . urlencode( $cur );
				$bits[] = 'diff=prev';
				$bits = implode( '&', $bits );
				$diff = $skin->makeKnownLinkObj( $title, htmlspecialchars( wfMsg( 'patrol-log-diff', $cur ) ), $bits );
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
	 * @param RecentChange $change RecentChange to represent
	 * @param bool $auto Whether the patrol event was automatic
	 * @return array
	 */
	private static function buildParams( $change, $auto ) {
		return array(
			$change->getAttribute( 'rc_this_oldid' ),
			$change->getAttribute( 'rc_last_oldid' ),
			(int)$auto
		);
	}
}
