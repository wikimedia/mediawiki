<?php

function wfSpecialStatistics()
{
	global $wgUser, $wgOut;
	$fname = "wfSpecialStatistics";

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
		$total, $good, $views, $edits,
		sprintf( "%.2f", $total ? $edits / $total : 0 ),
		sprintf( "%.2f", $edits ? $views / $edits : 0 ) );

	$wgOut->addHTML( $text );
	$wgOut->addHTML( "<h2>" . wfMsg( "userstats" ) . "</h2>\n" );

	$sql = "SELECT COUNT(user_id) AS total FROM user";
	$res = wfQuery( $sql, DB_READ, $fname );
	$row = wfFetchObject( $res );
	$total = $row->total;

	$sql = "SELECT COUNT(user_id) AS total FROM user " .
	  "WHERE user_rights LIKE '%sysop%'";
	$res = wfQuery( $sql, DB_READ, $fname );
	$row = wfFetchObject( $res );
	$admins = $row->total;

	$sk = $wgUser->getSkin();
	$ap = $sk->makeKnownLink( wfMsg( "administrators" ), "" );

	$text = wfMsg( "userstatstext", $total, $admins, $ap );
	$wgOut->addHTML( $text );
}

?>
