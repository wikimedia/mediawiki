<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
require_once( 'SpecialRecentchanges.php' );
require_once( 'WatchedItem.php' );

/**
 * constructor
 */
function wfSpecialWatchlist() {
	global $wgUser, $wgOut, $wgLang, $wgTitle, $wgMemc, $wgRequest;
	global $wgUseWatchlistCache, $wgWLCacheTimeout, $wgDBname;
	global $wgEnotif, $wgShowUpdatedMarker, $wgRCShowWatchingUsers;
	$fname = 'wfSpecialWatchlist';

	$wgOut->setPagetitle( wfMsg( 'watchlist' ) );
	$sub = wfMsg( 'watchlistsub', $wgUser->getName() );
	$wgOut->setSubtitle( $sub );
	$wgOut->setRobotpolicy( 'noindex,nofollow' );

	$specialTitle = Title::makeTitle( NS_SPECIAL, 'Watchlist' );

	$uid = $wgUser->getID();
	if( $uid == 0 ) {
		$wgOut->addWikiText( wfMsg( 'nowatchlist' ) );
		return;
	}

	# Get query variables
	$days = $wgRequest->getVal( 'days' );
	$action = $wgRequest->getVal( 'action' );
	$remove = $wgRequest->getVal( 'remove' );
	$id = $wgRequest->getArray( 'id' );

	if( $wgUser->getOption( 'enotifwatchlistpages' ) ) {
		$wgOut->addHTML( "<div class='enotifinfo'>\n" );
		
		$wgOut->addWikiText( wfMsg( 'email_notification_infotext' ) );

		$wgOut->addHTML( '<form action="' .
			$specialTitle->escapeLocalUrl( 'action=submit&magic=yes' ) .
			'" method="post"><input type="submit" name="dummy" value="' .
			htmlspecialchars( wfMsg( 'email_notification_reset' ) ) .
			'" /><input type="hidden" name="reset" value="all" /></form>' .
			"</div>\n\n" );
	}

	if( $wgRequest->getVal( 'reset' ) == 'all' ) {
		$wgUser->clearAllNotifications( $uid );
	}

	if(($action == 'submit') && isset($remove) && is_array($id)) {
		$wgOut->addWikiText( wfMsg( 'removingchecked' ) );
		$wgOut->addHTML( '<p>' );
		foreach($id as $one) {
			$t = Title::newFromURL( $one );
			if($t->getDBkey() != '') {
				$wl = WatchedItem::fromUserTitle( $wgUser, $t );
				if( $wl->removeWatch() === false ) {
					$wgOut->addHTML( "<br />\n" . wfMsg( 'couldntremove', htmlspecialchars($one) ) );
				} else {
					$wgOut->addHTML( ' (' . htmlspecialchars($one) . ')' );
				}
			} else {
				$wgOut->addHTML( "<br />\n" . wfMsg( 'iteminvalidname', htmlspecialchars($one) ) );
			}
		}
		$wgOut->addHTML( "done.</p>\n" );
	}

	if ( $wgUseWatchlistCache ) {
		$memckey = "$wgDBname:watchlist:id:" . $wgUser->getId();
		$cache_s = @$wgMemc->get( $memckey );
		if( $cache_s ){
			$wgOut->addWikiText( wfMsg('wlsaved') );
			$wgOut->addHTML( $cache_s );
			return;
		}
	}

	$dbr =& wfGetDB( DB_SLAVE );
	extract( $dbr->tableNames( 'page', 'revision', 'watchlist', 'recentchanges' ) );

	$sql = "SELECT COUNT(*) AS n FROM $watchlist WHERE wl_user=$uid";
	$res = $dbr->query( $sql, $fname );
	$s = $dbr->fetchObject( $res );

#	Patch *** A1 *** (see A2 below)
#	adjust for page X, talk:page X, which are both stored separately, but treated together
#	$nitems = $s->n / 2;
	$nitems = $s->n;

	if($nitems == 0) {
        $wgOut->addWikiText( wfMsg( 'nowatchlist' ) );
        return;
	}
	
	if ( is_null( $days ) ) {
		$big = 1000;
		if($nitems > $big) {
			# Set default cutoff shorter
			$days = (12.0 / 24.0); # 12 hours...
		} else {
			$days = 3; # longer cutoff for shortlisters
		}
	} else {
		$days = floatval($days);
	}

	if ( $days <= 0 ) {
		$docutoff = '';
		$cutoff = false;
		$npages = wfMsg( 'all' );
	} else {
	        $docutoff = "AND rev_timestamp > '" .
		  ( $cutoff = $dbr->timestamp( time() - intval( $days * 86400 ) ) )
		  . "'";
	        $sql = "SELECT COUNT(*) AS n FROM $page, $revision  WHERE rev_timestamp>'$cutoff' AND page_id=rev_page";
		$res = $dbr->query( $sql, $fname );
		$s = $dbr->fetchObject( $res );
		$npages = $s->n;

	}

	if(isset($_REQUEST['magic'])) {
		$wgOut->addWikiText( wfMsg( 'watchlistcontains', $wgLang->formatNum( $nitems ) ) .
			'<p>' . wfMsg( 'watcheditlist' ) . "</p>\n" );

		$wgOut->addHTML( '<form action=\'' .
			$specialTitle->escapeLocalUrl( 'action=submit' ) .
			"' method='post'>\n" .
			"<ul>\n" );

#		Patch A2
#		The following was proposed by KTurner 07.11.2004 to T.Gries
#		$sql = "SELECT distinct (wl_namespace & ~1),wl_title FROM $watchlist WHERE wl_user=$uid";
		$sql = "SELECT wl_namespace,wl_title FROM $watchlist WHERE wl_user=$uid";

		$res = $dbr->query( $sql, $fname );
		$sk = $wgUser->getSkin();
		while( $s = $dbr->fetchObject( $res ) ) {
			$t = Title::makeTitle( $s->wl_namespace, $s->wl_title );
			if( is_null( $t ) ) {
				$wgOut->addHTML( '<!-- bad title "' . htmlspecialchars( $s->wl_title ) . '" in namespace ' . IntVal( $s->wl_namespace ) . " -->\n" );
			} else {
				$t = $t->getPrefixedText();
				$wgOut->addHTML( '<li><input type="checkbox" name="id[]" value="' . htmlspecialchars($t) . '" />' .
					$sk->makeLink( $t, $t ) .
					"</li>\n" );
			}
		}
		$wgOut->addHTML( "</ul>\n" .
			"<input type='submit' name='remove' value=\"" .
			htmlspecialchars( wfMsg( "removechecked" ) ) . "\" />\n" .
			"</form>\n" );

		return;
	}

	# If the watchlist is relatively short, it's simplest to zip
	# down its entirety and then sort the results.

	# If it's relatively long, it may be worth our while to zip
	# through the time-sorted page list checking for watched items.

	# Up estimate of watched items by 15% to compensate for talk pages...
	if( $cutoff && ( $nitems*1.15 > $npages ) ) {
		$x = 'rev_timestamp';
		$y = wfMsg( 'watchmethod-recent' );
		# TG patch: here we do not consider pages and their talk pages equivalent - why should we ?
		# The change results in talk-pages not automatically included in watchlists, when their parent page is included
		# $z = "wl_namespace=cur_namespace & ~1";
		$z = 'wl_namespace=page_namespace';
	} else {
		$x = 'page_timestamp';
		$y = wfMsg( 'watchmethod-list' );
		# TG patch: here we do not consider pages and their talk pages equivalent - why should we ?
		# The change results in talk-pages not automatically included in watchlists, when their parent page is included
		# $z = "(wl_namespace=cur_namespace OR wl_namespace+1=cur_namespace)";
		$z = 'wl_namespace=page_namespace';
	}


	$wgOut->addHTML( '<i>' . wfMsg( 'watchdetails',
		$wgLang->formatNum( $nitems ), $wgLang->formatNum( $npages ), $y,
		$specialTitle->escapeLocalUrl( 'magic=yes' ) ) . "</i><br />\n" );

	$use_index = $dbr->useIndexClause( $x );
	$sql = "SELECT
  page_namespace,page_title,rev_comment, page_id,
  rev_user,rev_user_text,rev_timestamp,rev_minor_edit,page_is_new,wl_notificationtimestamp
  FROM $watchlist,$page,$revision  $use_index
  WHERE wl_user=$uid
  AND $z
  AND wl_title=page_title
  AND page_latest=rev_id
  $docutoff
  ORDER BY rev_timestamp DESC";


	$res = $dbr->query( $sql, $fname );
	$numRows = $dbr->numRows( $res );
	if($days >= 1)
		$note = wfMsg( 'rcnote', $wgLang->formatNum( $numRows ), $wgLang->formatNum( $days ) );
	elseif($days > 0)
		$note = wfMsg( 'wlnote', $wgLang->formatNum( $numRows ), $wgLang->formatNum( round($days*24) ) );
	else
		$note = '';
	$wgOut->addHTML( "\n<hr />\n{$note}\n<br />" );
	$note = wlCutoffLinks( $days );
	$wgOut->addHTML( "{$note}\n" );

	if ( $numRows == 0 ) {
		$wgOut->addHTML( '<p><i>' . wfMsg( 'watchnochange' ) . '</i></p>' );
		return;
	}

	$sk = $wgUser->getSkin();
	$list =& new ChangesList( $sk );
	$s = $list->beginRecentChangesList();
	$counter = 1;
	while ( $obj = $dbr->fetchObject( $res ) ) {
		# Make fake RC entry
		$rc = RecentChange::newFromCurRow( $obj );
		$rc->counter = $counter++;

		if ($wgShowUpdatedMarker && $wgUser->getOption( 'showupdated' )) {
			$rc->notificationtimestamp = $obj->wl_notificationtimestamp;
		} else {
			$rc->notificationtimestamp = false;
		}

		if ($wgRCShowWatchingUsers && $wgUser->getOption( 'shownumberswatching' )) {
			$sql3 = "SELECT COUNT(*) AS n FROM $watchlist WHERE wl_title='" .wfStrencode($obj->cur_title). "' AND wl_namespace='{$obj->cur_namespace}'" ;
			$res3 = $dbr->query( $sql3, DB_READ, $fname );
			$x = $dbr->fetchObject( $res3 );
			$rc->numberofWatchingusers = $x->n;
		} else {
			$rc->numberofWatchingusers = 0;
		}

		$s .= $list->recentChangesLine( $rc, true);
	}
	$s .= $list->endRecentChangesList();

	$dbr->freeResult( $res );
	$wgOut->addHTML( $s );

	if ( $wgUseWatchlistCache ) {
		$wgMemc->set( $memckey, $s, $wgWLCacheTimeout);
	}

}


function wlHoursLink( $h, $page ) {
	global $wgUser, $wgLang, $wgContLang;
	$sk = $wgUser->getSkin();
	$s = $sk->makeKnownLink(
	  $wgContLang->specialPage( $page ),
	  $wgLang->formatNum( $h ),
	  'days=' . ($h / 24.0) );
	return $s;
}


function wlDaysLink( $d, $page ) {
	global $wgUser, $wgLang, $wgContLang;
	$sk = $wgUser->getSkin();
	$s = $sk->makeKnownLink(
	  $wgContLang->specialPage( $page ),
	  ($d ? $wgLang->formatNum( $d ) : wfMsg( 'all' ) ), "days=$d" );
	return $s;
}

function wlCutoffLinks( $days, $page = 'Watchlist' )
{
	$hours = array( 1, 2, 6, 12 );
	$days = array( 1, 3, 7 );
	$cl = '';
	$i = 0;
	foreach( $hours as $h ) {
		$hours[$i++] = wlHoursLink( $h, $page );
	}
	$i = 0;
	foreach( $days as $d ) {
		$days[$i++] = wlDaysLink( $d, $page );
	}
	return wfMsg ('wlshowlast',
		implode(' | ', $hours),
		implode(' | ', $days),
		wlDaysLink( 0, $page ) );
}

?>
