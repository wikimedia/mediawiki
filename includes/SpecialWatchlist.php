<?
global $IP;
include_once( "$IP/SpecialRecentchanges.php" );

function wfSpecialWatchlist()
{
	global $wgUser, $wgOut, $wgLang, $wgTitle;
	global $days, $limit, $target; # From query string
	$fname = "wfSpecialWatchlist";

	$wgOut->setPagetitle( wfMsg( "watchlist" ) );
	$sub = str_replace( "$1", $wgUser->getName(), wfMsg( "watchlistsub" ) );
	$wgOut->setSubtitle( $sub );
	$wgOut->setRobotpolicy( "index,follow" );

	if ( ! isset( $days ) ) {
		$days = $wgUser->getOption( "rcdays" );
		if ( ! $days ) { $days = 3; }
	}
	$days = (int)$days;
	list( $limit, $offset ) = wfCheckLimits( 100, "rclimit" );
	
	if ( $days <= 0 ) {
		$docutoff = '';
	} else {
		$docutoff = "cur_timestamp > '" .
		  wfUnix2Timestamp( time() - ( $days * 86400 ) )
		  . "' AND";
	}
	if ( $limit == 0 ) {
		$dolimit = "";
	} else {
		$dolimit = "LIMIT $limit";
	}
	
	$uid = $wgUser->getID();
	if( $uid == 0 ) {
		$wgOut->addHTML( wfMsg( "nowatchlist" ) );
		return;
	}

	$sql = "SELECT DISTINCT
  cur_id,cur_namespace,cur_title,cur_comment,
  cur_user,cur_user_text,cur_timestamp,cur_minor_edit,cur_is_new
  FROM cur,watchlist
  WHERE wl_user={$uid} AND wl_title=cur_title
        AND (cur_namespace=wl_namespace OR cur_namespace=wl_namespace+1)
  ORDER BY inverse_timestamp {$dolimit}";
	$res = wfQuery( $sql, $fname );
	if ( wfNumRows( $res ) == 0 ) {
		$wgOut->addHTML( wfMsg( "nowatchlist" ) );
		return;
	}

	$note = wfMsg( "rcnote", $limit, $days );
	$wgOut->addHTML( "\n<hr>\n{$note}\n<br>" );
	$note = rcDayLimitlinks( $days, $limit, "Watchlist", "", true );
	$wgOut->addHTML( "{$note}\n" );

	$sk = $wgUser->getSkin();
	$s = $sk->beginRecentChangesList();

	while ( $obj = wfFetchObject( $res ) ) {
		$ts = $obj->cur_timestamp;
		$u = $obj->cur_user;
		$ut = $obj->cur_user_text;
		$ns = $obj->cur_namespace;
		$ttl = $obj->cur_title;
		$com = $obj->cur_comment;
		$me = ( $obj->cur_minor_edit > 0 );
		$new = ( $obj->cur_is_new  > 0 );
		$watched = true;

		$s .= $sk->recentChangesLine( $ts, $u, $ut, $ns, $ttl, $com, $me, $new, $watched );
	}
	$s .= $sk->endRecentChangesList();

	wfFreeResult( $res );
	$wgOut->addHTML( $s );
}

?>
