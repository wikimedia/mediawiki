<?php

function wfSpecialStatistics()
{
	global $wgUser, $wgOut, $wgLang;
	$fname = "wfSpecialStatistics";

	$wgOut->addHTML( "<h2>" . wfMsg( "sitestats" ) . "</h2>\n" );
	
	$db =& wfGetDB( DB_READ );

	$sql = "SELECT COUNT(cur_id) AS total FROM cur";
	$res = $db->query( $sql, $fname );
	$row = $db->fetchObject( $res );
	$total = $row->total;

	$sql = "SELECT ss_total_views, ss_total_edits, ss_good_articles " .
	  "FROM site_stats WHERE ss_row_id=1";
	$res = $db->query( $sql, $fname );
	$row = $db->fetchObject( $res );
	$views = $row->ss_total_views;
	$edits = $row->ss_total_edits;
	$good = $row->ss_good_articles;

	$text = wfMsg( "sitestatstext",
		$wgLang->formatNum( $total ),
		$wgLang->formatNum( $good ),
		$wgLang->formatNum( $views ),
		$wgLang->formatNum( $edits ),
		$wgLang->formatNum( sprintf( "%.2f", $total ? $edits / $total : 0 ) ),
		$wgLang->formatNum( sprintf( "%.2f", $edits ? $views / $edits : 0 ) ) );

	$wgOut->addWikiText( $text );
	$wgOut->addHTML( "<h2>" . wfMsg( "userstats" ) . "</h2>\n" );

	$usertable = $db->tableName( 'user' );
	$sql = "SELECT COUNT(user_id) AS total FROM $usertable";
	$res = $db->query( $sql, $fname );
	$row = $db->fetchObject( $res );
	$total = $row->total;

	$sql = "SELECT COUNT(user_id) AS total FROM $usertable " .
	  "WHERE user_rights LIKE '%sysop%'";
	$res = $db->query( $sql, $fname );
	$row = $db->fetchObject( $res );
	$admins = $row->total;

	$sk = $wgUser->getSkin();
	$ap = "[[" . wfMsg( "administrators" ) . "]]";

	$text = wfMsg( "userstatstext",
		$wgLang->formatNum( $total ),
		$wgLang->formatNum( $admins ), $ap );
	$wgOut->addWikiText( $text );

}

?>
