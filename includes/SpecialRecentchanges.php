<?

function wfSpecialRecentchanges( $par )
{
	global $wgUser, $wgOut, $wgLang, $wgTitle, $wgMemc, $wgDBname;
	global $days, $hideminor, $from, $hidebots, $hideliu; # From query string
	$fname = "wfSpecialRecentchanges";

	if( $par ) {
		$bits = preg_split( '/\s*,\s*/', trim( $par ) );
		if( in_array( "hidebots", $bits ) ) $hidebots = 1;
		if( in_array( "bots", $bits ) ) $hidebots = 0;
		if( in_array( "hideminor", $bits ) ) $hideminor = 1;
		if( in_array( "minor", $bits ) ) $hideminor = 0;
		if( in_array( "hideliu", $bits) ) $hideliu = 1;
	}
	
	$sql = "SELECT MAX(rc_timestamp) AS lastmod FROM recentchanges";
	$res = wfQuery( $sql, DB_READ, $fname );
	$s = wfFetchObject( $res );
	if( $wgOut->checkLastModified( $s->lastmod ) ){
		# Client cache fresh and headers sent, nothing more to do.
		return;
	}

	$rctext = wfMsg( "recentchangestext" );
	
	# The next few lines can probably be commented out now that wfMsg can get text from the DB
	$sql = "SELECT cur_text FROM cur WHERE cur_namespace=4 AND cur_title='Recentchanges'";
	$res = wfQuery( $sql, DB_READ, $fname );
	if( ( $s = wfFetchObject( $res ) ) and ( $s->cur_text != "" ) ) {
		$rctext = $s->cur_text;
	}
	
	$wgOut->addWikiText( $rctext );

	if ( ! $days ) {
		$days = $wgUser->getOption( "rcdays" );
		if ( ! $days ) { $days = 3; }
	}
	$days = (int)$days;
	list( $limit, $offset ) = wfCheckLimits( 100, "rclimit" );
	$now = wfTimestampNow();
	$cutoff_unixtime = time() - ( $days * 86400 );
	$cutoff_unixtime = $cutoff_unixtime - ($cutoff_unixtime % 86400);
	$cutoff = wfUnix2Timestamp( $cutoff_unixtime );
	if(preg_match('/^[0-9]{14}$/', $from) and $from > $cutoff) {
		$cutoff = $from;
	} else {
		unset($from);
	}

	$sk = $wgUser->getSkin();

	if ( ! isset( $hideminor ) ) {
		$hideminor = $wgUser->getOption( "hideminor" );
	}
	$hideminor = ($hideminor ? 1 : 0);
	if ( $hideminor ) {
		$hidem = "AND rc_minor=0";
		$mltitle = wfMsg( "show" );
		$mlhide = 0;
	} else {
		$hidem = "";
		$mltitle = wfMsg( "hide" );
		$mlhide = 1;
	}

	if ( isset( $from ) ) {
		$mlparams = "from={$from}&hideminor={$mlhide}";
	} else {
		$mlparams = "days={$days}&limit={$limit}&hideminor={$mlhide}";
	}

	$mlink = $sk->makeKnownLink( $wgLang->specialPage( "Recentchanges" ),
		$mltitle, $mlparams );
	
	if ( !isset( $hidebots ) ) {
		$hidebots = 1;
	}
	if( $hidebots ) {
		$hidem .= " AND rc_bot=0";
	}
	
	if ( $hideliu ) {
		$hidem .= " AND rc_user=0";
	}

	$uid = $wgUser->getID();
	$sql2 = "SELECT recentchanges.*" . ($uid ? ",wl_user" : "") . " FROM recentchanges " .
	  ($uid ? "LEFT OUTER JOIN watchlist ON wl_user={$uid} AND wl_title=rc_title AND wl_namespace=rc_namespace & 65534 " : "") .
	  "WHERE rc_timestamp > '{$cutoff}' {$hidem} " .
	  "ORDER BY rc_timestamp DESC LIMIT {$limit}";

	$res = wfQuery( $sql2, DB_READ, $fname );
	$rows = array();
	while( $row = wfFetchObject( $res ) ){ 
		$rows[] = $row; 
	}

	if(isset($from)) {
		$note = wfMsg( "rcnotefrom", $limit,
			$wgLang->timeanddate( $from, true ) );
	} else {
		$note = wfMsg( "rcnote", $limit, $days );
	}
	$wgOut->addHTML( "\n<hr>\n{$note}\n<br>" );

	$note = rcDayLimitLinks( $days, $limit, "Recentchanges",  "hideminor={$hideminor}", false, $mlink );

	$note .= "<br>\n" . wfMsg( "rclistfrom",
	  $sk->makeKnownLink( $wgLang->specialPage( "Recentchanges" ),
	  $wgLang->timeanddate( $now, true ), "hideminor={$hideminor}&from=$now" ) );

	$wgOut->addHTML( "{$note}\n" );

	$s = $sk->beginRecentChangesList();
	foreach( $rows as $obj ){
		if( $limit == 0) {
			break; 
		}

		if ( ! ( $hideminor && $obj->rc_minor ) ) {
			$rc = RecentChange::newFromRow( $obj );
			$s .= $sk->recentChangesLine( $rc, $obj->wl_user );
			--$limit;
		}
	}
	$s .= $sk->endRecentChangesList();

	wfFreeResult( $res );
	$wgOut->addHTML( $s );
}

function rcCountLink( $lim, $d, $page="Recentchanges", $more="" )
{
	global $wgUser, $wgLang;
	$sk = $wgUser->getSkin();
	$s = $sk->makeKnownLink( $wgLang->specialPage( $page ),
	  ($lim ? "{$lim}" : wfMsg( "all" ) ), "{$more}" .
	  ($d ? "days={$d}&" : "") . "limit={$lim}" );
	return $s;
}

function rcDaysLink( $lim, $d, $page="Recentchanges", $more="" )
{
	global $wgUser, $wgLang;
	$sk = $wgUser->getSkin();
	$s = $sk->makeKnownLink( $wgLang->specialPage( $page ),
	  ($d ? "{$d}" : wfMsg( "all" ) ), "{$more}days={$d}" .
	  ($lim ? "&limit={$lim}" : "") );
	return $s;
}

function rcDayLimitLinks( $days, $limit, $page="Recentchanges", $more="", $doall = false, $mlink = "" )
{
	if ($more != "") $more .= "&";
	$cl = rcCountLink( 50, $days, $page, $more ) . " | " .
	  rcCountLink( 100, $days, $page, $more  ) . " | " .
	  rcCountLink( 250, $days, $page, $more  ) . " | " .
	  rcCountLink( 500, $days, $page, $more  ) .
	  ( $doall ? ( " | " . rcCountLink( 0, $days, $page, $more ) ) : "" );
	$dl = rcDaysLink( $limit, 1, $page, $more  ) . " | " .
	  rcDaysLink( $limit, 3, $page, $more  ) . " | " .
	  rcDaysLink( $limit, 7, $page, $more  ) . " | " .
	  rcDaysLink( $limit, 14, $page, $more  ) . " | " .
	  rcDaysLink( $limit, 30, $page, $more  ) .
	  ( $doall ? ( " | " . rcDaysLink( $limit, 0, $page, $more ) ) : "" );
        $shm = wfMsg( "showhideminor", $mlink );
	$note = wfMsg( "rclinks", $cl, $dl, $shm );
	return $note;
}

function rcLimitLinks( $page="Recentchanges", $more="", $doall = false )
{
	if ($more != "") $more .= "&";
	$cl = rcCountLink( 50, 0, $page, $more ) . " | " .
	  rcCountLink( 100, 0, $page, $more  ) . " | " .
	  rcCountLink( 250, 0, $page, $more  ) . " | " .
	  rcCountLink( 500, 0, $page, $more  ) .
	  ( $doall ? ( " | " . rcCountLink( 0, $days, $page, $more ) ) : "" );
	$note = wfMsg( "rclinks", $cl, "", $mlink );
	return $note;
}

?>
