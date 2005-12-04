<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
require_once( 'QueryPage.php' );

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class NewPagesPage extends QueryPage {

	function getName() {
		return 'Newpages';
	}

	function isExpensive() {
		# Indexed on RC, and will *not* work with querycache yet.
		return false;
		#return parent::isExpensive();
	}

	function getSQL() {
		global $wgUser, $wgOnlySysopsCanPatrol, $wgUseRCPatrol;
		$usepatrol = ( $wgUseRCPatrol && $wgUser->isLoggedIn() &&
		               ( $wgUser->isAllowed('patrol') || !$wgOnlySysopsCanPatrol ) ) ? 1 : 0;
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'recentchanges', 'page', 'text' ) );

		# FIXME: text will break with compression
		return
			"SELECT 'Newpages' as type,
			        rc_namespace AS namespace,
			        rc_title AS title,
			        rc_cur_id AS value,
			        rc_user AS user,
			        rc_user_text AS user_text,
			        rc_comment as comment,
			        rc_timestamp AS timestamp,
			        '{$usepatrol}' as usepatrol,
			        rc_patrolled AS patrolled,
			        rc_id AS rcid,
			        page_len as length,
			        page_latest as rev_id
			FROM $recentchanges,$page
			WHERE rc_cur_id=page_id AND rc_new=1
			  AND rc_namespace=".NS_MAIN." AND page_is_redirect=0";
	}

	function formatResult( $skin, $result ) {
		global $wgLang, $wgContLang, $wgUser, $wgOnlySysopsCanPatrol, $wgUseRCPatrol;
		$u = $result->user;
		$ut = $result->user_text;

		$length = wfMsg( 'nbytes', $wgLang->formatNum( $result->length ) );

		if ( $u == 0 ) { # not by a logged-in user
			$userPage = Title::makeTitle( NS_SPECIAL, 'Contributions' );
			$linkParams = 'target=' . urlencode( $ut );
		} else {
			$userPage = Title::makeTitle( NS_USER, $ut );
			$linkParams = '';
		}
		$ul = $skin->makeLinkObj( $userPage, htmlspecialchars( $ut ), $linkParams );

		$d = $wgLang->timeanddate( $result->timestamp, true );

		# Since there is no diff link, we need to give users a way to
		# mark the article as patrolled if it isn't already
		if ( $wgUseRCPatrol && !is_null ( $result->usepatrol ) && $result->usepatrol &&
		     $result->patrolled == 0 && $wgUser->isLoggedIn() &&
		     ( $wgUser->isAllowed('patrol') || !$wgOnlySysopsCanPatrol ) )
			$link = $skin->makeKnownLink( $result->title, '', "rcid={$result->rcid}" );
		else
			$link = $skin->makeKnownLink( $result->title, '' );

		$s = "{$d} {$link} ({$length}) . . {$ul}";

		$s .= $skin->commentBlock( $result->comment );

		return $s;
	}
	
	function feedItemDesc( $row ) {
		if( isset( $row->rev_id ) ) {
			$revision = Revision::newFromId( $row->rev_id );
			if( $revision ) {
				return '<p>' . htmlspecialchars( wfMsg( 'summary' ) ) . ': ' . $text . "</p>\n<hr />\n<div>" .
					nl2br( htmlspecialchars( $revision->getText() ) ) . "</div>";
			}
		}
		return parent::feedItemDesc( $row );
	}
}

/**
 * constructor
 */
function wfSpecialNewpages($par, $specialPage)
{
	global $wgRequest;
	list( $limit, $offset ) = wfCheckLimits();
	if( $par ) {
		$bits = preg_split( '/\s*,\s*/', trim( $par ) );
		foreach ( $bits as $bit ) {
			if ( 'shownav' == $bit ) $shownavigation = 1;
			if ( is_numeric( $bit ) ) {
				$limit = $bit;
			}

			if ( preg_match( '/^limit=(\d+)$/', $bit, $m ) ) {
				$limit = intval($m[1]);
			}
			if ( preg_match( '/^offset=(\d+)$/', $bit, $m ) ) {
				$offset = intval($m[1]);
			}
		}
	}
	if(!isset($shownavigation)) {
		$shownavigation=!$specialPage->including();
	}

	$npp = new NewPagesPage();

	if( !$npp->doFeed( $wgRequest->getVal( 'feed' ) ) ) {
		$npp->doQuery( $offset, $limit, $shownavigation );
	}
}

?>
