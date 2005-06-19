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
	$fname = 'wfSpecialStatistics';

	$dbr =& wfGetDB( DB_SLAVE );
	extract( $dbr->tableNames( 'page', 'site_stats', 'user', 'user_groups' ) );

	$row = $dbr->selectRow( 'site_stats', '*', false, 'wfSpecialStatistics' );
	$views = $row->ss_total_views;
	$edits = $row->ss_total_edits;
	$good = $row->ss_good_articles;

	# This code is somewhat schema-agnostic, because I'm changing it in a minor release -- TS
	if ( isset( $row->ss_total_pages ) && $row->ss_total_pages == -1 ) {
		# Update schema
		$u = new SiteStatsUpdate( 0, 0, 0 );
		$u->doUpdate();
		$row = $dbr->selectRow( 'site_stats', '*', false, 'wfSpecialStatistics' );
	}

	if ( isset( $row->ss_total_pages ) ) {
		$total = $row->ss_total_pages;
	} else {
		$sql = "SELECT COUNT(page_namespace) AS total FROM $page";
		$res = $dbr->query( $sql, $fname );
		$pageRow = $dbr->fetchObject( $res );
		$total = $pageRow->total;
	}

	if ( isset( $row->ss_users ) ) {
		$totalUsers = $row->ss_users;
	} else {
		$sql = "SELECT MAX(user_id) AS total FROM $user";
		$res = $dbr->query( $sql, $fname );
		$userRow = $dbr->fetchObject( $res );
		$totalUsers = $userRow->total;
	} 	


	$text = '==' . wfMsg( 'sitestats' ) . "==\n" ;
	$text .= wfMsg( 'sitestatstext',
		$wgLang->formatNum( $total ),
		$wgLang->formatNum( $good ),
		$wgLang->formatNum( $views ),
		$wgLang->formatNum( $edits ),
		$wgLang->formatNum( sprintf( '%.2f', $total ? $edits / $total : 0 ) ),
		$wgLang->formatNum( sprintf( '%.2f', $edits ? $views / $edits : 0 ) ) );

	$text .= "\n==" . wfMsg( 'userstats' ) . "==\n";

	$sql = "SELECT COUNT(*) AS total FROM $user_groups WHERE ug_group='sysop'";
	$res = $dbr->query( $sql, $fname );
	$row = $dbr->fetchObject( $res );
	$admins = $row->total;

	$text .= wfMsg( 'userstatstext',
		$wgLang->formatNum( $totalUsers ),
		$wgLang->formatNum( $admins ),
		'[[' . wfMsg( 'administrators' ) . ']]',
		// should logically be after #admins, danm backwards compatability!
		$wgLang->formatNum( round( $admins / $total * 100, 2 ) )
	);
	$wgOut->addWikiText( $text );
}
?>
