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
	var $namespace;

	function NewPagesPage( $namespace = NS_MAIN ) {
		$this->namespace = $namespace;
	}

	function getName() {
		return 'Newpages';
	}

	function isExpensive() {
		# Indexed on RC, and will *not* work with querycache yet.
		return false;
	}

	function getSQL() {
		global $wgUser, $wgUseRCPatrol;
		$usepatrol = ( $wgUseRCPatrol && $wgUser->isAllowed( 'patrol' ) ) ? 1 : 0;
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'recentchanges', 'page', 'text' ) );

		# FIXME: text will break with compression
		return
			"SELECT 'Newpages' as type,
				rc_namespace AS namespace,
				rc_title AS title,
				rc_cur_id AS cur_id,
				rc_user AS user,
				rc_user_text AS user_text,
				rc_comment as comment,
				rc_timestamp AS timestamp,
				rc_timestamp AS value,
				'{$usepatrol}' as usepatrol,
				rc_patrolled AS patrolled,
				rc_id AS rcid,
				page_len as length,
				page_latest as rev_id
			FROM $recentchanges,$page
			WHERE rc_cur_id=page_id AND rc_new=1
			AND rc_namespace=" . $this->namespace . " AND page_is_redirect=0";
	}
	
	function preprocessResults( &$dbo, &$res ) {
		# Do a batch existence check on the user and talk pages
		$linkBatch = new LinkBatch();
		while( $row = $dbo->fetchObject( $res ) ) {
			$linkBatch->addObj( Title::makeTitleSafe( NS_USER, $row->user_text ) );
			$linkBatch->addObj( Title::makeTitleSafe( NS_USER_TALK, $row->user_text ) );
		}
		$linkBatch->execute();
		# Seek to start
		if( $dbo->numRows( $res ) > 0 )
			$dbo->dataSeek( $res, 0 );
	}

	function formatResult( $skin, $result ) {
		global $wgLang, $wgContLang, $wgUser, $wgUseRCPatrol;
		$u = $result->user;
		$ut = $result->user_text;

		$length = wfMsgHtml( 'nbytes', htmlspecialchars( $wgLang->formatNum( $result->length ) ) );
		$d = $wgLang->timeanddate( $result->timestamp, true );

		# Since there is no diff link, we need to give users a way to
		# mark the article as patrolled if it isn't already
		$ns = $wgContLang->getNsText( $result->namespace );
		if( $wgUseRCPatrol && !is_null( $result->usepatrol ) && $result->usepatrol && $result->patrolled == 0 && $wgUser->isAllowed( 'patrol' ) ) {
			$link = $skin->makeKnownLink( $ns . ':' . $result->title, '', "rcid={$result->rcid}" );
		} else {
			$link = $skin->makeKnownLink( $ns . ':' . $result->title, '' );
		}

		$userTools = $skin->userLink( $u, $ut ) . $skin->userToolLinks( $u, $ut );

		$s = "{$d} {$link} ({$length}) . . {$userTools}";
		$s .= $skin->commentBlock( $result->comment );
		return $s;
	}

	function feedItemDesc( $row ) {
		if( isset( $row->rev_id ) ) {
			$revision = Revision::newFromId( $row->rev_id );
			if( $revision ) {
				return '<p>' . htmlspecialchars( wfMsg( 'summary' ) ) . ': ' .
					htmlspecialchars( $revision->getComment() ) . "</p>\n<hr />\n<div>" .
					nl2br( htmlspecialchars( $revision->getText() ) ) . "</div>";
			}
		}
		return parent::feedItemDesc( $row );
	}
}

/**
 * constructor
 */
function wfSpecialNewpages($par, $specialPage) {
	global $wgRequest, $wgContLang;

	list( $limit, $offset ) = wfCheckLimits();
	$namespace = NS_MAIN;

	if ( $par ) {
		$bits = preg_split( '/\s*,\s*/', trim( $par ) );
		foreach ( $bits as $bit ) {
			if ( 'shownav' == $bit )
				$shownavigation = true;
			if ( is_numeric( $bit ) )
				$limit = $bit;

			if ( preg_match( '/^limit=(\d+)$/', $bit, $m ) )
				$limit = intval($m[1]);
			if ( preg_match( '/^offset=(\d+)$/', $bit, $m ) )
				$offset = intval($m[1]);
			if ( preg_match( '/^namespace=(.*)$/', $bit, $m ) ) {
				$ns = $wgContLang->getNsIndex( $m[1] );
				if( $ns !== false ) {
					$namespace = $ns;
				}
			}
		}
	}
	if ( ! isset( $shownavigation ) )
		$shownavigation = ! $specialPage->including();

	$npp = new NewPagesPage( $namespace );

	if ( ! $npp->doFeed( $wgRequest->getVal( 'feed' ) ) )
		$npp->doQuery( $offset, $limit, $shownavigation );
}

?>
