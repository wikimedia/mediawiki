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
	global $wgOut, $wgLang, $wgRequest;
	$fname = 'wfSpecialStatistics';

	$action = $wgRequest->getVal( 'action' );

	$dbr =& wfGetDB( DB_SLAVE );
	extract( $dbr->tableNames( 'page', 'site_stats', 'user', 'user_groups' ) );

	$row = $dbr->selectRow( 'site_stats', '*', false, $fname );
	$views = $row->ss_total_views;
	$edits = $row->ss_total_edits;
	$good = $row->ss_good_articles;
	$images = $row->ss_images;

	# This code is somewhat schema-agnostic, because I'm changing it in a minor release -- TS
	if ( isset( $row->ss_total_pages ) && $row->ss_total_pages == -1 ) {
		# Update schema
		$u = new SiteStatsUpdate( 0, 0, 0 );
		$u->doUpdate();
		$row = $dbr->selectRow( 'site_stats', '*', false, $fname );
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
		$users = $row->ss_users;
	} else {
		$sql = "SELECT MAX(user_id) AS total FROM $user";
		$res = $dbr->query( $sql, $fname );
		$userRow = $dbr->fetchObject( $res );
		$users = $userRow->total;
	}

	$admins = $dbr->selectField( 'user_groups', 'COUNT(*)', array( 'ug_group' => 'sysop' ), $fname );
	$numJobs = $dbr->selectField( 'job', 'COUNT(*)', '', $fname );

	if ($action == 'raw') {
		$wgOut->disable();
		header( 'Pragma: nocache' );
		echo "total=$total;good=$good;views=$views;edits=$edits;users=$users;admins=$admins;images=$images;jobs=$numJobs\n";
		return;
	} else {
		$text = '==' . wfMsg( 'sitestats' ) . "==\n" ;
		$text .= wfMsg( 'sitestatstext',
			$wgLang->formatNum( $total ),
			$wgLang->formatNum( $good ),
			$wgLang->formatNum( $views ),
			$wgLang->formatNum( $edits ),
			$wgLang->formatNum( sprintf( '%.2f', $total ? $edits / $total : 0 ) ),
			$wgLang->formatNum( sprintf( '%.2f', $edits ? $views / $edits : 0 ) ),
			$wgLang->formatNum( $numJobs ),
			$wgLang->formatNum( $images )
	   	);

		$text .= "\n==" . wfMsg( 'userstats' ) . "==\n";

		$text .= wfMsg( 'userstatstext',
			$wgLang->formatNum( $users ),
			$wgLang->formatNum( $admins ),
			'[[' . wfMsgForContent( 'administrators' ) . ']]',
			// should logically be after #admins, damn backwards compatability!
			$wgLang->formatNum( sprintf( '%.2f', $admins / $users * 100 ) )
		);

		$wgOut->addWikiText( $text );
	}
}
?>
