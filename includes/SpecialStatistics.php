<?php

function wfSpecialStatistics()
{
	global $wgUser, $wgOut, $wgLang, $wgIsPg, $wgLoadBalancer;
	$fname = "wfSpecialStatistics";

	$wgLoadBalancer->force(-1);

	$wgOut->addHTML( "<h2>" . wfMsg( "sitestats" ) . "</h2>\n" );

	$sql = "SELECT COUNT(cur_id) AS total FROM cur";
	$res = wfQuery( $sql, DB_READ, $fname );
	$row = wfFetchObject( $res );
	$total = $row->total;

	$sql = "SELECT ss_total_views, ss_total_edits, ss_good_articles " .
	  "FROM site_stats WHERE ss_row_id=1";
	$res = wfQuery( $sql, DB_READ, $fname );
	$row = wfFetchObject( $res );
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

	$usertable=$wgIsPg?'"user"':'user';
	$sql = "SELECT COUNT(user_id) AS total FROM $usertable";
	$res = wfQuery( $sql, DB_READ, $fname );
	$row = wfFetchObject( $res );
	$total = $row->total;

	$sql = "SELECT COUNT(user_id) AS total FROM $usertable " .
	  "WHERE user_rights LIKE '%sysop%'";
	$res = wfQuery( $sql, DB_READ, $fname );
	$row = wfFetchObject( $res );
	$admins = $row->total;

	$sk = $wgUser->getSkin();
	$ap = "[[" . wfMsg( "administrators" ) . "]]";

	$text = wfMsg( "userstatstext",
		$wgLang->formatNum( $total ),
		$wgLang->formatNum( $admins ), $ap );
	$wgOut->addWikiText( $text );

	$wgLoadBalancer->force(0);
}

?>
