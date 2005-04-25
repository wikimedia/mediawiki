<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * constructor
 */
function wfSpecialStatistics() {
	global $wgUser, $wgOut, $wgLang;
	$fname = "wfSpecialStatistics";

	$wgOut->addHTML( "<h2>" . wfMsg( "sitestats" ) . "</h2>\n" );
	
	$dbr =& wfGetDB( DB_SLAVE, array( 'SpecialStatistics', 'vslow'));
	extract( $dbr->tableNames( 'cur', 'site_stats', 'user', 'user_rights' ) );

	$row = $dbr->selectRow( 'site_stats', '*', false, 'wfSpecialStatistics' );
	$views = $row->ss_total_views;
	$edits = $row->ss_total_edits;
	$good = $row->ss_good_articles;

	# This code is somewhat schema-agnostic, because I'm changing it in a minor release -- TS
	if ( isset( $row->ss_total_pages ) && $row->ss_total_pages == -1 ) {
		# Update schema
		$u = new SiteStatsUpdate( 0, 0, 0 );
		$u->doUpdate();
		$dbw =& wfGetDB( DB_MASTER );
		$row = $dbw->selectRow( 'site_stats', '*', false, 'wfSpecialStatistics' );

	}

	if ( isset( $row->ss_total_pages ) ) {
		$total = $row->ss_total_pages;
	} else {
		$sql = "SELECT COUNT(cur_namespace) AS total FROM $cur";
		$res = $dbr->query( $sql, $fname );
		$curRow = $dbr->fetchObject( $res );
		$total = $curRow->total;
	}

	if ( isset( $row->ss_users ) ) {
		$totalUsers = $row->ss_users;
	} else {
		$sql = "SELECT MAX(user_id) AS total FROM $user";
		$res = $dbr->query( $sql, $fname );
		$userRow = $dbr->fetchObject( $res );
		$totalUsers = $userRow->total;
	}

	if ( isset( $row->ss_admins ) ) {
		$admins = $row->ss_admins;
	} else {
		$sql = "SELECT COUNT(ur_user) AS total FROM $user_rights WHERE ur_rights LIKE '%sysop%'";
		$res = $dbr->query( $sql, $fname );
		$urRow = $dbr->fetchObject( $res );
		$admins = $urRow->total;
	}
	
	$text = wfMsg( "sitestatstext",
		$wgLang->formatNum( $total ),
		$wgLang->formatNum( $good ),
		$wgLang->formatNum( $views ),
		$wgLang->formatNum( $edits ),
		$wgLang->formatNum( sprintf( "%.2f", $total ? $edits / $total : 0 ) ),
		$wgLang->formatNum( sprintf( "%.2f", $edits ? $views / $edits : 0 ) ) );

	$wgOut->addWikiText( $text );
	$wgOut->addHTML( "<h2>" . wfMsg( "userstats" ) . "</h2>\n" );

	$sk = $wgUser->getSkin();
	$ap = "[[" . wfMsg( "administrators" ) . "]]";

	$text = wfMsg( "userstatstext",
		$wgLang->formatNum( $totalUsers ),
		$wgLang->formatNum( $admins ), $ap );
	$wgOut->addWikiText( $text );

}

?>
