<?

function wfSpecialStatistics()
{
	global $wgUser, $wgOut;
	$fname = "wfSpecialStatistics";

	$wgOut->addHTML( "<h2>" . wfMsg( "sitestats" ) . "</h2>\n" );

	$sql = "SELECT COUNT(cur_id) AS total FROM cur";
	$res = wfQuery( $sql, $fname );
	$row = wfFetchObject( $res );
	$total = $row->total;

	$sql = "SELECT ss_total_views, ss_total_edits, ss_good_articles " .
	  "FROM site_stats WHERE ss_row_id=1";
	$res = wfQuery( $sql, $fname );
	$row = wfFetchObject( $res );
	$views = $row->ss_total_views;
	$edits = $row->ss_total_edits;
	$good = $row->ss_good_articles;

	$text = str_replace( "$1", $total, wfMsg( "sitestatstext" ) );
	$text = str_replace( "$2", $good, $text );
	$text = str_replace( "$3", $views, $text );
	$text = str_replace( "$4", $edits, $text );
	$text = str_replace( "$5", sprintf( "%.2f", $edits / $total ), $text );
	$text = str_replace( "$6", sprintf( "%.2f", $views / $edits ), $text );

	$wgOut->addHTML( $text );
	$wgOut->addHTML( "<h2>" . wfMsg( "userstats" ) . "</h2>\n" );

	$sql = "SELECT COUNT(user_id) AS total FROM user";
	$res = wfQuery( $sql, $fname );
	$row = wfFetchObject( $res );
	$total = $row->total;

	$sql = "SELECT COUNT(user_id) AS total FROM user " .
	  "WHERE user_rights LIKE '%sysop%'";
	$res = wfQuery( $sql, $fname );
	$row = wfFetchObject( $res );
	$admins = $row->total;

	$sk = $wgUser->getSkin();
	$ap = $sk->makeKnownLink( wfMsg( "administrators" ), "" );

	$text = str_replace( "$1", $total, wfMsg( "userstatstext" ) );
	$text = str_replace( "$2", $admins, $text );
	$text = str_replace( "$3", $ap, $text );
	$wgOut->addHTML( $text );
}

?>
