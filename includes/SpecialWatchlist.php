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
	$wgOut->setRobotpolicy( "noindex,nofollow" );

	$uid = $wgUser->getID();
	if( $uid == 0 ) {
		$wgOut->addHTML( wfMsg( "nowatchlist" ) );
		return;
	}

	global $action,$remove,$id;
	if(($action == "submit") && isset($remove) && is_array($id)) {
		$wgOut->addHTML( wfMsg( "removingchecked" ) );
		foreach($id as $one) {
			$t = Title::newFromURL( $one );
			if($t->getDBkey() != "") {
				$sql = "DELETE FROM watchlist WHERE wl_user=$uid AND " .
					"wl_namespace=" . $t->getNamespace() . " AND " .
					"wl_title='" . wfStrencode( $t->getDBkey() ) . "'";
				$res = wfQuery( $sql, DB_WRITE );
				if($res === FALSE) {
					$wgOut->addHTML( "<br />\n" . wfMsg( "couldntremove", htmlspecialchars($one) ) );
				} else {
					$wgOut->addHTML( " (" . htmlspecialchars($one) . ")" );
				}
			} else {
				$wgOut->addHTML( "<br />\n" . wfMsg( "iteminvalidname", htmlspecialchars($one) ) );
			}
		}
		$wgOut->addHTML( "done.\n<p>" );
	}

	$sql = "SELECT COUNT(*) AS n FROM watchlist WHERE wl_user=$uid";
	$res = wfQuery( $sql, DB_READ );
	$s = wfFetchObject( $res );
	$nitems = $s->n;
	
	if($nitems == 0) {
        $wgOut->addHTML( wfMsg( "nowatchlist" ) );
        return;
	}

	if ( ! isset( $days ) ) {
		$big = 250;
		if($nitems > $big) {
			# Set default cutoff shorter
			$days = (1.0 / 24.0); # 1 hour...
		} else {
			$days = 0; # no time cutoff for shortlisters
		}
	} else {
		$days = floatval($days);
	}
	
	if ( $days <= 0 ) {
		$docutoff = '';
		$cutoff = false;
		$npages = wfMsg( "all" );
	} else {
		$docutoff = "AND cur_timestamp > '" .
		  ( $cutoff = wfUnix2Timestamp( time() - intval( $days * 86400 ) ) )
		  . "'";
		$sql = "SELECT COUNT(*) AS n FROM cur WHERE cur_timestamp>'$cutoff'";
		$res = wfQuery( $sql, DB_READ );
		$s = wfFetchObject( $res );
		$npages = $s->n;
	}
	
	if(isset($_REQUEST['magic'])) {
		$wgOut->addHTML( wfMsg( "watchlistcontains", $nitems ) .
			"<p>" . wfMsg( "watcheditlist" ) . "</p>\n" );
		
		$wgOut->addHTML( "<form action='" .
			wfLocalUrl( $wgLang->specialPage( "Watchlist" ), "action=submit" ) .
			"' method='post'>\n" .
			"<ul>\n" );
		$sql = "SELECT wl_namespace,wl_title FROM watchlist WHERE wl_user=$uid";
		$res = wfQuery( $sql, DB_READ );
		global $wgUser, $wgLang;
		$sk = $wgUser->getSkin();
		while( $s = wfFetchObject( $res ) ) {
			$t = Title::makeTitle( $s->wl_namespace, $s->wl_title );
			$t = $t->getPrefixedText();
			$wgOut->addHTML( "<li><input type='checkbox' name='id[]' value=\"" . htmlspecialchars($t) . "\">" .
				$sk->makeKnownLink( $t, $t ) .
				"</li>\n" );
		}
		$wgOut->addHTML( "</ul>\n" .
			"<input type='submit' name='remove' value='" .
			wfMsg( "removechecked" ) . "'>\n" .
			"</form>\n" );
		
		return;
	}
	
	# If the watchlist is relatively short, it's simplest to zip
	# down its entirety and then sort the results.
	
	# If it's relatively long, it may be worth our while to zip
	# through the time-sorted page list checking for watched items.
	
	# Up estimate of watched items by 15% to compensate for talk pages...
	if( $cutoff && ( $nitems*1.15 > $npages ) ) {
		$x = "cur_timestamp";
		$y = wfMsg( "watchmethod-recent" );
		$z = "wl_namespace=cur_namespace&65534";
	} else {
		$x = "name_title_timestamp";
		$y = wfMsg( "watchmethod-list" );
		$z = "(wl_namespace=cur_namespace OR wl_namespace+1=cur_namespace)";
	}

	$wgOut->addHTML( "<i>" . wfMsg( "watchdetails", $nitems, $npages, $y,
		wfLocalUrl( $wgLang->specialPage("Watchlist"),"magic=yes" ) ) . "</i><br>\n" );
	 

	$sql = "SELECT
  cur_namespace,cur_title,cur_comment,
  cur_user,cur_user_text,cur_timestamp,cur_minor_edit,cur_is_new
  FROM watchlist,cur USE INDEX ($x)
  WHERE wl_user=$uid
  AND $z
  AND wl_title=cur_title
  $docutoff
  ORDER BY cur_timestamp DESC";


	$res = wfQuery( $sql, DB_READ, $fname );

	if($days >= 1)
		$note = wfMsg( "rcnote", $limit, $days );
	elseif($days > 0)
		$note = wfMsg( "wlnote", $limit, round($days*24) );
	else
		$note = "";
	$wgOut->addHTML( "\n<hr>\n{$note}\n<br>" );
	$note = wlCutoffLinks( $days, $limit );
	$wgOut->addHTML( "{$note}\n" );

	if ( wfNumRows( $res ) == 0 ) {
		$wgOut->addHTML( "<p><i>" . wfMsg( "watchnochange" ) . "</i></p>" );
		return;
	}

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


function wlHoursLink( $h, $page ) {
	global $wgUser, $wgLang;
	$sk = $wgUser->getSkin();
	$s = $sk->makeKnownLink(
	  $wgLang->specialPage( $page ),
	  $h, "days=" . ($h / 24.0) );
	return $s;
}


function wlDaysLink( $d, $page ) {
	global $wgUser, $wgLang;
	$sk = $wgUser->getSkin();
	$s = $sk->makeKnownLink(
	  $wgLang->specialPage( $page ),
	  ($d ? $d : wfMsg( "all" ) ), "days=$d" );
	return $s;
}

function wlCutoffLinks( $days, $limit, $page = "Watchlist" )
{
	$hours = array( 1, 2, 6, 12 );
	$days = array( 1, 3, 7 );
	$cl = "";
	$i = 0;
	foreach( $hours as $h ) {
		$hours[$i++] = wlHoursLink( $h, $page );
	}
	$i = 0;
	foreach( $days as $d ) {
		$days[$i++] = wlDaysLink( $d, $page );
	}
	return
		wfMsg ("wlshowlast") .
		implode(" | ", $hours) . wfMsg("wlhours") .
		implode(" | ", $days) . wfMsg("wldays") .
		wlDaysLink( 0, $page );
#	$note = wfMsg( "rclinks", $cl, $dl, $mlink );
}

?>
