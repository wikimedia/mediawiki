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
function wfSpecialWatchlist( $par ) {
	global $wgUser, $wgOut, $wgLang, $wgTitle, $wgMemc, $wgRequest, $wgContLang;;
	global $wgUseWatchlistCache, $wgWLCacheTimeout, $wgDBname;
	global $wgRCShowWatchingUsers, $wgEnotifWatchlist, $wgShowUpdatedMarker;
	global $wgEnotifWatchlist;
	$fname = 'wfSpecialWatchlist';

	$wgOut->setPagetitle( wfMsg( 'watchlist' ) );
	$sub = wfMsg( 'watchlistsub', $wgUser->getName() );
	$wgOut->setSubtitle( $sub );
	$wgOut->setRobotpolicy( 'noindex,nofollow' );

	$specialTitle = Title::makeTitle( NS_SPECIAL, 'Watchlist' );

	if( $wgUser->isAnon() ) {
		$wgOut->addWikiText( wfMsg( 'nowatchlist' ) );
		return;
	}

	# Get query variables
	$days = $wgRequest->getVal( 'days' );
	$action = $wgRequest->getVal( 'action' );
	$remove = $wgRequest->getVal( 'remove' );
	$hideOwn = $wgRequest->getBool( 'hideOwn' );	
	$id = $wgRequest->getArray( 'id' );

	$uid = $wgUser->getID();
	if( $wgEnotifWatchlist && $wgRequest->getVal( 'reset' ) && $wgRequest->wasPosted() ) {
		$wgUser->clearAllNotifications( $uid );
	}


	if(($action == 'submit') && isset($remove) && is_array($id)) {
		$wgOut->addWikiText( wfMsg( 'removingchecked' ) );
		$wgOut->addHTML( '<p>' );
		foreach($id as $one) {
			$t = Title::newFromURL( $one );
			if( !is_null( $t ) ) {
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
		$npages = wfMsg( 'watchlistall1' );
	} else {
	        $docutoff = "AND rev_timestamp > '" .
		  ( $cutoff = $dbr->timestamp( time() - intval( $days * 86400 ) ) )
		  . "'";
	        $sql = "SELECT COUNT(*) AS n FROM $page, $revision  WHERE rev_timestamp>'$cutoff' AND page_id=rev_page";
		$res = $dbr->query( $sql, $fname );
		$s = $dbr->fetchObject( $res );
		$npages = $s->n;

	}

	if($wgRequest->getBool('edit') || $par == 'edit' ) {
		$wgOut->addWikiText( wfMsg( 'watchlistcontains', $wgLang->formatNum( $nitems ) ) .
			"\n\n" . wfMsg( 'watcheditlist' ) );

		$wgOut->addHTML( '<form action=\'' .
			$specialTitle->escapeLocalUrl( 'action=submit' ) .
			"' method='post'>\n" );

#		Patch A2
#		The following was proposed by KTurner 07.11.2004 to T.Gries
#		$sql = "SELECT distinct (wl_namespace & ~1),wl_title FROM $watchlist WHERE wl_user=$uid";
		$sql = "SELECT wl_namespace,wl_title FROM $watchlist WHERE wl_user=$uid";

		$res = $dbr->query( $sql, $fname );
		$sk = $wgUser->getSkin();

		$list = array();
		while( $s = $dbr->fetchObject( $res ) ) {
			$list[$s->wl_namespace][] = $s->wl_title;
		}
		
		// TODO: Display a TOC
		foreach($list as $ns => $titles) {
			if (Namespace::isTalk($ns))
				continue;
			if ($ns != NS_MAIN) 
				$wgOut->addHTML( '<h2>' . $wgContLang->getFormattedNsText( $ns ) . '</h2>' );
			$wgOut->addHTML( '<ul>' );
			foreach($titles as $title) {
				$t = Title::makeTitle( $ns, $title );
				if( is_null( $t ) ) {
					$wgOut->addHTML(
						'<!-- bad title "' .
						htmlspecialchars( $s->wl_title ) . '" in namespace ' . $s->wl_namespace . " -->\n"
					);
				} else {
					$t = $t->getPrefixedText();
					$wgOut->addHTML(
						'<li><input type="checkbox" name="id[]" value="' . htmlspecialchars($t) . '" />' .
						$sk->makeLink( $t, $t ) .
						"</li>\n"
					);
				}
			}
			$wgOut->addHTML( '</ul>' );
		}
		$wgOut->addHTML(
			"<input type='submit' name='remove' value=\"" .
			htmlspecialchars( wfMsg( "removechecked" ) ) . "\" />\n" .
			"</form>\n"
		);

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

	$andHideOwn = $hideOwn ? "AND (rev_user <> $uid)" : '';

	# Show watchlist header
	$header = '';
	if( $wgUser->getOption( 'enotifwatchlistpages' ) && $wgEnotifWatchlist) {
		$header .= wfMsg( 'wlheader-enotif' ) . "\n";
	}
	if ( $wgEnotifWatchlist && $wgShowUpdatedMarker ) {
		$header .= wfMsg( 'wlheader-showupdated' ) . "\n";
	}

	$header .= wfMsg( 'watchdetails', $wgLang->formatNum( $nitems / 2 ), 
		$wgLang->formatNum( $npages ), $y,
		$specialTitle->getFullUrl( 'edit=yes' ) );
	$wgOut->addWikiText( $header );
	
	if ( $wgEnotifWatchlist && $wgShowUpdatedMarker ) {
		$wgOut->addHTML( '<form action="' .
			$specialTitle->escapeLocalUrl() .
			'" method="post"><input type="submit" name="dummy" value="' .
			htmlspecialchars( wfMsg( 'enotif_reset' ) ) .
			'" /><input type="hidden" name="reset" value="all" /></form>' .
			"\n\n" );
	}

	$use_index = $dbr->useIndexClause( $x );
	$sql = "SELECT
  page_namespace,page_title,rev_comment, page_id,
  rev_user,rev_user_text,rev_timestamp,rev_minor_edit,page_is_new,wl_notificationtimestamp
  FROM $watchlist,$page,$revision  $use_index
  WHERE wl_user=$uid
  $andHideOwn
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
	
	$sk = $wgUser->getSkin();
	$s = $sk->makeKnownLink(
		$wgContLang->specialPage( 'Watchlist' ),
		(0 == $hideOwn) ? wfMsg( 'wlhide' ) : wfMsg( 'wlshow' ),	
	  	'hideOwn=' . $wgLang->formatNum( 1-$hideOwn ) );
	  	
	$note = wfMsg( "wlhideshowown", $s );
	$wgOut->addHTML( "\n<br />{$note}\n<br />" );
	
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

		if ( $wgShowUpdatedMarker ) {
			$updated = $obj->wl_notificationtimestamp;
		} else {
			// Same visual appearance as MW 1.4
			$updated = true;
		}

		if ($wgRCShowWatchingUsers && $wgUser->getOption( 'shownumberswatching' )) {
			$sql3 = "SELECT COUNT(*) AS n FROM $watchlist WHERE wl_title='" .wfStrencode($obj->page_title). "' AND wl_namespace='{$obj->page_namespace}'" ;
			$res3 = $dbr->query( $sql3, DB_READ, $fname );
			$x = $dbr->fetchObject( $res3 );
			$rc->numberofWatchingusers = $x->n;
		} else {
			$rc->numberofWatchingusers = 0;
		}

		$s .= $list->recentChangesLine( $rc, $updated );
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
	  ($d ? $wgLang->formatNum( $d ) : wfMsg( 'watchlistall2' ) ), "days=$d" );
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
