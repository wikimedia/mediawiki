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
	extract( $dbr->tableNames( 'page', 'site_stats', 'user', 'user_rights' ) );

	$sql = "SELECT COUNT(page_namespace) AS total FROM $page";
	$res = $dbr->query( $sql, $fname );
	$row = $dbr->fetchObject( $res );
	$total = $row->total;

	$sql = "SELECT ss_total_views, ss_total_edits, ss_good_articles " .
	  "FROM $site_stats WHERE ss_row_id=1";
	$res = $dbr->query( $sql, $fname );
	$row = $dbr->fetchObject( $res );
	$views = $row->ss_total_views;
	$edits = $row->ss_total_edits;
	$good = $row->ss_good_articles;

	$text = '==' . wfMsg( 'sitestats' ) . "==\n" ;
	$text .= wfMsg( 'sitestatstext',
		$wgLang->formatNum( $total ),
		$wgLang->formatNum( $good ),
		$wgLang->formatNum( $views ),
		$wgLang->formatNum( $edits ),
		$wgLang->formatNum( sprintf( '%.2f', $total ? $edits / $total : 0 ) ),
		$wgLang->formatNum( sprintf( '%.2f', $edits ? $views / $edits : 0 ) ) );

	$text .= "\n==" . wfMsg( 'userstats' ) . "==\n";

	$sql = "SELECT COUNT(user_id) AS total FROM $user";
	$res = $dbr->query( $sql, $fname );
	$row = $dbr->fetchObject( $res );
	$total = $row->total;

	$sql = "SELECT COUNT(ur_user) AS total FROM $user_rights WHERE ur_rights LIKE '%sysop%'";
	$res = $dbr->query( $sql, $fname );
	$row = $dbr->fetchObject( $res );
	$admins = $row->total;

	$text .= wfMsg( 'userstatstext',
		$wgLang->formatNum( $total ),
		$wgLang->formatNum( $admins ),
		'[[' . wfMsg( 'administrators' ) . ']]',
		// should logically be after #admins, danm backwards compatability!
		$wgLang->formatNum( round( $admins / $total * 100, 2 ) )
	);
	$wgOut->addWikiText( $text );
}
?>
