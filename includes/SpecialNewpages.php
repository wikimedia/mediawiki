<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
require_once( "QueryPage.php" );

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class NewPagesPage extends QueryPage {

	function getName() {
		return "Newpages";
	}

	function isExpensive() {
		# Indexed on RC, and will *not* work with querycache yet.
		return false;
		#return parent::isExpensive();
	}

	function getSQL() {
		global $wgUser, $wgOnlySysopsCanPatrol, $wgUseRCPatrol;
		$usepatrol = ( $wgUseRCPatrol && $wgUser->getID() != 0 &&
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
			        length(old_text) as length,
			        old_text as text
			FROM $recentchanges,$page,$text
			WHERE rc_cur_id=page_id AND rc_new=1
			  AND rc_namespace=0 AND page_is_redirect=0
			  AND page_latest=old_id";
	}

	function formatResult( $skin, $result ) {
		global $wgLang, $wgContLang, $wgUser, $wgOnlySysopsCanPatrol, $wgUseRCPatrol;
		$u = $result->user;
		$ut = $result->user_text;

		$length = wfMsg( "nbytes", $wgLang->formatNum( $result->length ) );
		$c = $skin->formatComment($result->comment );

		if ( $u == 0 ) { # not by a logged-in user
			$ul = $ut;
		}
		else {
			$ul = $skin->makeLink( $wgContLang->getNsText(NS_USER) . ":{$ut}", $ut );
		}

		$d = $wgLang->timeanddate( $result->timestamp, true );

		# Since there is no diff link, we need to give users a way to
		# mark the article as patrolled if it isn't already
		if ( $wgUseRCPatrol && !is_null ( $result->usepatrol ) && $result->usepatrol &&
		     $result->patrolled == 0 && $wgUser->getID() != 0 &&
		     ( $wgUser->isAllowed('patrol') || !$wgOnlySysopsCanPatrol ) )
			$link = $skin->makeKnownLink( $result->title, '', "rcid={$result->rcid}" );
		else
			$link = $skin->makeKnownLink( $result->title, '' );

		$s = "{$d} {$link} ({$length}) . . {$ul}";

		if ( "" != $c && "*" != $c ) {
			$s .= " <em>({$c})</em>";
		}

		return $s;
	}
}

/**
 * constructor
 */
function wfSpecialNewpages()
{
	global $wgRequest;
    list( $limit, $offset ) = wfCheckLimits();

    $npp = new NewPagesPage();

    if( !$npp->doFeed( $wgRequest->getVal( 'feed' ) ) ) {
	    $npp->doQuery( $offset, $limit );
	}
}

?>
