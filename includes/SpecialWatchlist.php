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
	global $wgUser, $wgOut, $wgLang, $wgTitle, $wgMemc, $wgRequest, $wgContLang;
	global $wgUseWatchlistCache, $wgWLCacheTimeout, $wgDBname;
	global $wgRCShowWatchingUsers, $wgEnotifWatchlist, $wgShowUpdatedMarker;
	global $wgEnotifWatchlist;
	$fname = 'wfSpecialWatchlist';

	$wgOut->setPagetitle( wfMsg( 'watchlist' ) );
	$sub = htmlspecialchars( wfMsg( 'watchlistsub', $wgUser->getName() ) );
	$wgOut->setSubtitle( $sub );
	$wgOut->setRobotpolicy( 'noindex,nofollow' );

	$specialTitle = Title::makeTitle( NS_SPECIAL, 'Watchlist' );

	if( $wgUser->isAnon() ) {
		$wgOut->addWikiText( wfMsg( 'nowatchlist' ) );
		return;
	}

	$defaults = array(
	/* float */ 'days' => 3.0, /* or 0.5, watch further below */
	/* bool  */ 'hideOwn' => false,
	);

	extract($defaults);

	# Get query variables
	$days = $wgRequest->getVal( 'days' );
	$hideOwn = $wgRequest->getBool( 'hideOwn' );

	# Watchlist editing
	$action = $wgRequest->getVal( 'action' );
	$remove = $wgRequest->getVal( 'remove' );
	$id = $wgRequest->getArray( 'id' );

	$uid = $wgUser->getID();
	if( $wgEnotifWatchlist && $wgRequest->getVal( 'reset' ) && $wgRequest->wasPosted() ) {
		$wgUser->clearAllNotifications( $uid );
	}

  # Deleting items from watchlist
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
	$nitems = floor($s->n / 2);
#	$nitems = $s->n;

	if($nitems == 0) {
		$wgOut->addWikiText( wfMsg( 'nowatchlist' ) );
		return;
	}

	if( is_null($days) || !is_numeric($days) ) {
		$big = 1000; /* The magical big */
		if($nitems > $big) {
			# Set default cutoff shorter
			$days = $defaults['days'] = (12.0 / 24.0); # 12 hours...
		} else {
			$days = $defaults['days']; # default cutoff for shortlisters
		}
	} else {
		$days = floatval($days);
	}

	// Dump everything here
	$nondefaults = array();

	wfAppendToArrayIfNotDefault( 'days', $days, $defaults, $nondefaults);
	wfAppendToArrayIfNotDefault( 'hideOwn', $hideOwn, $defaults, $nondefaults);

	if ( $days <= 0 ) {
		$docutoff = '';
		$cutoff = false;
		$npages = wfMsg( 'watchlistall1' );
	} else {
	        $docutoff = "AND rev_timestamp > '" .
		  ( $cutoff = $dbr->timestamp( time() - intval( $days * 86400 ) ) )
		  . "'";
                  /* 
                  $sql = "SELECT COUNT(*) AS n FROM $page, $revision  WHERE rev_timestamp>'$cutoff' AND page_id=rev_page";
                  $res = $dbr->query( $sql, $fname );
                  $s = $dbr->fetchObject( $res );
                  $npages = $s->n;
                  */
                  $npages = 40000 * $days;

	}

	/* Edit watchlist form */
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

	$andHideOwn = $hideOwn ? "AND (rc_user <> $uid)" : '';

	# Show watchlist header
	$header = '';
	if( $wgUser->getOption( 'enotifwatchlistpages' ) && $wgEnotifWatchlist) {
		$header .= wfMsg( 'wlheader-enotif' ) . "\n";
	}
	if ( $wgEnotifWatchlist && $wgShowUpdatedMarker ) {
		$header .= wfMsg( 'wlheader-showupdated' ) . "\n";
	}

	# TODO: Consider removing the third parameter
	$header .= wfMsg( 'watchdetails', $wgLang->formatNum( $nitems ), 
		$wgLang->formatNum( $npages ), '',
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

        $sql = "SELECT
          rc_namespace page_namespace,rc_title page_title,
          rc_comment rev_comment, rc_cur_id page_id,
          rc_user rev_user,rc_user_text rev_user_text,
          rc_timestamp rev_timestamp,rc_minor rev_minor_edit,
          rc_this_oldid rev_id,
          rc_last_oldid,
          rc_new page_is_new,wl_notificationtimestamp
          FROM $watchlist,$recentchanges,$page
          WHERE wl_user=$uid
          AND wl_namespace=rc_namespace
          AND wl_title=rc_title
          AND rc_timestamp > '$cutoff'
          AND rc_cur_id=page_id
          AND rc_this_oldid=page_latest
          $andHideOwn
          ORDER BY rc_timestamp DESC";

	$res = $dbr->query( $sql, $fname );
	$numRows = $dbr->numRows( $res );

	/* Start bottom header */
	$wgOut->addHTML( "<hr />\n<p>" );

	if($days >= 1)
		$wgOut->addWikiText( wfMsg( 'rcnote', $wgLang->formatNum( $numRows ),
			$wgLang->formatNum( $days ) ) . '<br />' , false );
	elseif($days > 0)
		$wgOut->addWikiText( wfMsg( 'wlnote', $wgLang->formatNum( $numRows ),
			$wgLang->formatNum( round($days*24) ) ) . '<br />' , false );

	$wgOut->addHTML( "\n" . wlCutoffLinks( $days, 'Watchlist', $nondefaults ) . "<br />\n" );
	
	$sk = $wgUser->getSkin();
	$s = $sk->makeKnownLink(
		$wgContLang->specialPage( 'Watchlist' ),
		(0 == $hideOwn) ? wfMsgHtml( 'wlhide' ) : wfMsgHtml( 'wlshow' ),
		wfArrayToCGI( array('hideOwn' => 1-$hideOwn ), $nondefaults ) );

	$wgOut->addHTML( wfMsgHtml( "wlhideshowown", $s ) );
	
	if ( $numRows == 0 ) {
		$wgOut->addWikitext( "<br />" . wfMsg( 'watchnochange' ), false );
		$wgOut->addHTML( "</p>\n" );
		return;
	}

	$wgOut->addHTML( "</p>\n" );
	/* End bottom header */

	$sk = $wgUser->getSkin();
	$list =& new ChangesList( $sk );
	$s = $list->beginRecentChangesList();
	$counter = 1;
	while ( $obj = $dbr->fetchObject( $res ) ) {
		# Make fake RC entry
		$rc = RecentChange::newFromCurRow( $obj, $obj->rc_last_oldid );
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

function wlHoursLink( $h, $page, $options = array() ) {
	global $wgUser, $wgLang, $wgContLang;
	$sk = $wgUser->getSkin();
	$s = $sk->makeKnownLink(
	  $wgContLang->specialPage( $page ),
	  $wgLang->formatNum( $h ),
	  wfArrayToCGI( array('days' => ($h / 24.0)), $options ) );
	return $s;
}

function wlDaysLink( $d, $page, $options = array() ) {
	global $wgUser, $wgLang, $wgContLang;
	$sk = $wgUser->getSkin();
	$s = $sk->makeKnownLink(
	  $wgContLang->specialPage( $page ),
	  ($d ? $wgLang->formatNum( $d ) : wfMsgHtml( 'watchlistall2' ) ),
	  wfArrayToCGI( array('days' => $d), $options ) );
	return $s;
}

function wlCutoffLinks( $days, $page = 'Watchlist', $options = array() ) {
	$hours = array( 1, 2, 6, 12 );
	$days = array( 1, 3, 7 );
	$cl = '';
	$i = 0;
	foreach( $hours as $h ) {
		$hours[$i++] = wlHoursLink( $h, $page, $options );
	}
	$i = 0;
	foreach( $days as $d ) {
		$days[$i++] = wlDaysLink( $d, $page, $options );
	}
	return wfMsg ('wlshowlast',
		implode(' | ', $hours),
		implode(' | ', $days),
		wlDaysLink( 0, $page, $options ) );
}

?>
