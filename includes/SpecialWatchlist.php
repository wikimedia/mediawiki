<?php
/**
 *
 * @addtogroup SpecialPage
 */

/**
 *
 */
require_once( 'SpecialRecentchanges.php' );

/**
 * Constructor
 *
 * @param $par Parameter passed to the page
 */
function wfSpecialWatchlist( $par ) {
	global $wgUser, $wgOut, $wgLang, $wgRequest, $wgContLang;
	global $wgRCShowWatchingUsers, $wgEnotifWatchlist, $wgShowUpdatedMarker;
	global $wgEnotifWatchlist;
	$fname = 'wfSpecialWatchlist';

	$skin = $wgUser->getSkin();
	$specialTitle = SpecialPage::getTitleFor( 'Watchlist' );
	$wgOut->setRobotPolicy( 'noindex,nofollow' );

	# Anons don't get a watchlist
	if( $wgUser->isAnon() ) {
		$wgOut->setPageTitle( wfMsg( 'watchnologin' ) );
		$llink = $skin->makeKnownLinkObj( SpecialPage::getTitleFor( 'Userlogin' ), wfMsgHtml( 'loginreqlink' ), 'returnto=' . $specialTitle->getPrefixedUrl() );
		$wgOut->addHtml( wfMsgWikiHtml( 'watchlistanontext', $llink ) );
		return;
	} else {
		$wgOut->setPageTitle( wfMsg( 'watchlist' ) );
		$wgOut->setSubtitle( wfMsgWikiHtml( 'watchlistfor', htmlspecialchars( $wgUser->getName() ) ) );
	}

	if( wlHandleClear( $wgOut, $wgRequest, $par ) ) {
		return;
	}

	$defaults = array(
	/* float */ 'days' => floatval( $wgUser->getOption( 'watchlistdays' ) ), /* 3.0 or 0.5, watch further below */
	/* bool  */ 'hideOwn' => (int)$wgUser->getBoolOption( 'watchlisthideown' ),
	/* bool  */ 'hideBots' => (int)$wgUser->getBoolOption( 'watchlisthidebots' ),
	/* bool */ 'hideMinor' => (int)$wgUser->getBoolOption( 'watchlisthideminor' ),
	/* ?     */ 'namespace' => 'all',
	);

	extract($defaults);

	# Extract variables from the request, falling back to user preferences or
	# other default values if these don't exist
	$prefs['days'    ] = floatval( $wgUser->getOption( 'watchlistdays' ) );
	$prefs['hideown' ] = $wgUser->getBoolOption( 'watchlisthideown' );
	$prefs['hidebots'] = $wgUser->getBoolOption( 'watchlisthidebots' );
	$prefs['hideminor'] = $wgUser->getBoolOption( 'watchlisthideminor' );

	# Get query variables
	$days     = $wgRequest->getVal(  'days', $prefs['days'] );
	$hideOwn  = $wgRequest->getBool( 'hideOwn', $prefs['hideown'] );
	$hideBots = $wgRequest->getBool( 'hideBots', $prefs['hidebots'] );
	$hideMinor = $wgRequest->getBool( 'hideMinor', $prefs['hideminor'] );

	# Get namespace value, if supplied, and prepare a WHERE fragment
	$nameSpace = $wgRequest->getIntOrNull( 'namespace' );
	if( !is_null( $nameSpace ) ) {
		$nameSpace = intval( $nameSpace );
		$nameSpaceClause = " AND rc_namespace = $nameSpace";
	} else {
		$nameSpace = '';
		$nameSpaceClause = '';
	}

	# Watchlist editing
	$action = $wgRequest->getVal( 'action' );
	$remove = $wgRequest->getVal( 'remove' );
	$id     = $wgRequest->getArray( 'id' );

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
					$wgOut->addHTML( wfMsg( 'couldntremove', htmlspecialchars($one) ) . "<br />\n" );
				} else {
					wfRunHooks('UnwatchArticle', array(&$wgUser, new Article($t)));
					$wgOut->addHTML( '(' . htmlspecialchars($one) . ')<br />' );
				}
			} else {
				$wgOut->addHTML( wfMsg( 'iteminvalidname', htmlspecialchars($one) ) . "<br />\n" );
			}
		}
		$wgOut->addHTML( "</p>\n<p>" . wfMsg( 'wldone' ) . "</p>\n" );
	}

	$dbr = wfGetDB( DB_SLAVE, 'watchlist' );
	list( $page, $watchlist, $recentchanges ) = $dbr->tableNamesN( 'page', 'watchlist', 'recentchanges' );

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

	wfAppendToArrayIfNotDefault('days'     , $days         , $defaults, $nondefaults);
	wfAppendToArrayIfNotDefault('hideOwn'  , (int)$hideOwn , $defaults, $nondefaults);
	wfAppendToArrayIfNotDefault('hideBots' , (int)$hideBots, $defaults, $nondefaults);
	wfAppendToArrayIfNotDefault( 'hideMinor', (int)$hideMinor, $defaults, $nondefaults );
	wfAppendToArrayIfNotDefault('namespace', $nameSpace    , $defaults, $nondefaults);

	if ( $days <= 0 ) {
		$andcutoff = '';
		$npages = wfMsg( 'watchlistall1' );
	} else {
		$andcutoff = "AND rc_timestamp > '".$dbr->timestamp( time() - intval( $days * 86400 ) )."'";
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
		$sql = "SELECT wl_namespace, wl_title, page_is_redirect FROM $watchlist LEFT JOIN $page ON wl_namespace = page_namespace AND wl_title = page_title WHERE wl_user=$uid";

		$res = $dbr->query( $sql, $fname );

		# Batch existence check
		$linkBatch = new LinkBatch();
		while( $row = $dbr->fetchObject( $res ) )
			$linkBatch->addObj( Title::makeTitleSafe( $row->wl_namespace, $row->wl_title ) );
		$linkBatch->execute();

		if( $dbr->numRows( $res ) > 0 )
			$dbr->dataSeek( $res, 0 ); # Let's do the time warp again!

		$sk = $wgUser->getSkin();

		$list = array();
		while( $s = $dbr->fetchObject( $res ) ) {
			$list[$s->wl_namespace][$s->wl_title] = $s->page_is_redirect;
		}

		// TODO: Display a TOC
		foreach($list as $ns => $titles) {
			if (Namespace::isTalk($ns))
				continue;
			if ($ns != NS_MAIN)
				$wgOut->addHTML( '<h2>' . $wgContLang->getFormattedNsText( $ns ) . '</h2>' );
			$wgOut->addHTML( '<ul>' );
			foreach( $titles as $title => $redir ) {
				$titleObj = Title::makeTitle( $ns, $title );
				if( is_null( $titleObj ) ) {
					$wgOut->addHTML(
						'<!-- bad title "' .
						htmlspecialchars( $s->wl_title ) . '" in namespace ' . $s->wl_namespace . " -->\n"
					);
				} else {
					global $wgContLang;
					$toolLinks = array();
					$pageLink = $sk->makeLinkObj( $titleObj );
					$toolLinks[] = $sk->makeLinkObj( $titleObj->getTalkPage(), $wgLang->getNsText( NS_TALK ) );
					if( $titleObj->exists() )
						$toolLinks[] = $sk->makeKnownLinkObj( $titleObj, wfMsgHtml( 'history_short' ), 'action=history' );
					$toolLinks = '(' . implode( ' | ', $toolLinks ) . ')';
					$checkbox = '<input type="checkbox" name="id[]" value="' . htmlspecialchars( $titleObj->getPrefixedText() ) . '" /> ' . ( $wgContLang->isRTL() ? '&rlm;' : '&lrm;' );
					if( $redir ) {
						$spanopen = '<span class="watchlistredir">';
						$spanclosed = '</span>';
					} else {
						$spanopen = $spanclosed = '';
					}

					$wgOut->addHTML( "<li>{$checkbox}{$spanopen}{$pageLink}{$spanclosed} {$toolLinks}</li>\n" );
				}
			}
			$wgOut->addHTML( '</ul>' );
		}
		$wgOut->addHTML(
			wfSubmitButton( wfMsg('removechecked'), array('name' => 'remove') ) .
			"\n</form>\n"
		);

		return;
	}

	# If the watchlist is relatively short, it's simplest to zip
	# down its entirety and then sort the results.

	# If it's relatively long, it may be worth our while to zip
	# through the time-sorted page list checking for watched items.

	# Up estimate of watched items by 15% to compensate for talk pages...

	# Toggles
	$andHideOwn = $hideOwn ? "AND (rc_user <> $uid)" : '';
	$andHideBots = $hideBots ? "AND (rc_bot = 0)" : '';
	$andHideMinor = $hideMinor ? 'AND rc_minor = 0' : '';

	# Show watchlist header
	$header = '';
	if( $wgUser->getOption( 'enotifwatchlistpages' ) && $wgEnotifWatchlist) {
		$header .= wfMsg( 'wlheader-enotif' ) . "\n";
	}
	if ( $wgEnotifWatchlist && $wgShowUpdatedMarker ) {
		$header .= wfMsg( 'wlheader-showupdated' ) . "\n";
	}

  # Toggle watchlist content (all recent edits or just the latest)
	if( $wgUser->getOption( 'extendwatchlist' )) {
		$andLatest='';
 		$limitWatchlist = 'LIMIT ' . intval( $wgUser->getOption( 'wllimit' ) );
	} else {
		$andLatest= 'AND rc_this_oldid=page_latest';
		$limitWatchlist = '';
	}

	# TODO: Consider removing the third parameter
	$header .= wfMsgExt( 'watchdetails', array( 'parsemag' ), $wgLang->formatNum( $nitems ),
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

	$sql = "SELECT *
	  FROM $watchlist,$recentchanges,$page
	  WHERE wl_user=$uid
	  AND wl_namespace=rc_namespace
	  AND wl_title=rc_title
	  AND rc_cur_id=page_id
	  $andcutoff
	  $andLatest
	  $andHideOwn
	  $andHideBots
	  $andHideMinor
	  $nameSpaceClause
	  ORDER BY rc_timestamp DESC
	  $limitWatchlist";

	$res = $dbr->query( $sql, $fname );
	$numRows = $dbr->numRows( $res );

	/* Start bottom header */
	$wgOut->addHTML( "<hr />\n" );

	if($days >= 1) {
		$wgOut->addWikiText( wfMsg( 'rcnote', $wgLang->formatNum( $numRows ),
			$wgLang->formatNum( $days ), $wgLang->timeAndDate( wfTimestampNow(), true ) ) . '<br />' , false );
	} elseif($days > 0) {
		$wgOut->addWikiText( wfMsg( 'wlnote', $wgLang->formatNum( $numRows ),
			$wgLang->formatNum( round($days*24) ) ) . '<br />' , false );
	}

	$wgOut->addHTML( "\n" . wlCutoffLinks( $days, 'Watchlist', $nondefaults ) . "<br />\n" );

	# Spit out some control panel links
	$thisTitle = SpecialPage::getTitleFor( 'Watchlist' );
	$skin = $wgUser->getSkin();

	# Hide/show bot edits
	$label = $hideBots ? wfMsgHtml( 'watchlist-show-bots' ) : wfMsgHtml( 'watchlist-hide-bots' );
	$linkBits = wfArrayToCGI( array( 'hideBots' => 1 - (int)$hideBots ), $nondefaults );
	$links[] = $skin->makeKnownLinkObj( $thisTitle, $label, $linkBits );
	
	# Hide/show own edits
	$label = $hideOwn ? wfMsgHtml( 'watchlist-show-own' ) : wfMsgHtml( 'watchlist-hide-own' );
	$linkBits = wfArrayToCGI( array( 'hideOwn' => 1 - (int)$hideOwn ), $nondefaults );
	$links[] = $skin->makeKnownLinkObj( $thisTitle, $label, $linkBits );

	# Hide/show minor edits
	$label = $hideMinor ? wfMsgHtml( 'watchlist-show-minor' ) : wfMsgHtml( 'watchlist-hide-minor' );
	$linkBits = wfArrayToCGI( array( 'hideMinor' => 1 - (int)$hideMinor ), $nondefaults );
	$links[] = $skin->makeKnownLinkObj( $thisTitle, $label, $linkBits );

	$wgOut->addHTML( implode( ' | ', $links ) );

	# Form for namespace filtering
	$form  = Xml::openElement( 'form', array( 'method' => 'post', 'action' => $thisTitle->getLocalUrl() ) );
	$form .= '<p>';
	$form .= Xml::label( wfMsg( 'namespace' ), 'namespace' ) . '&nbsp;';
	$form .= Xml::namespaceSelector( $nameSpace, '' ) . '&nbsp;';
	$form .= Xml::submitButton( wfMsg( 'allpagessubmit' ) ) . '</p>';
	$form .= Xml::hidden( 'days', $days );
	if( $hideOwn )
		$form .= Xml::hidden( 'hideOwn', 1 );
	if( $hideBots )
		$form .= Xml::hidden( 'hideBots', 1 );
	if( $hideMinor )
		$form .= Xml::hidden( 'hideMinor', 1 );
	$form .= Xml::closeElement( 'form' );
	$wgOut->addHtml( $form );

	# If there's nothing to show, stop here
	if( $numRows == 0 ) {
		$wgOut->addWikiText( wfMsgNoTrans( 'watchnochange' ) );
		return;
	}

	/* End bottom header */

	/* Do link batch query */
	$linkBatch = new LinkBatch;
	while ( $row = $dbr->fetchObject( $res ) ) {
		$userNameUnderscored = str_replace( ' ', '_', $row->rc_user_text );
		if ( $row->rc_user != 0 ) {
			$linkBatch->add( NS_USER, $userNameUnderscored );
		}
		$linkBatch->add( NS_USER_TALK, $userNameUnderscored );
	}
	$linkBatch->execute();
	$dbr->dataSeek( $res, 0 );

	$list = ChangesList::newFromUser( $wgUser );

	$s = $list->beginRecentChangesList();
	$counter = 1;
	while ( $obj = $dbr->fetchObject( $res ) ) {
		# Make RC entry
		$rc = RecentChange::newFromRow( $obj );
		$rc->counter = $counter++;

		if ( $wgShowUpdatedMarker ) {
			$updated = $obj->wl_notificationtimestamp;
		} else {
			// Same visual appearance as MW 1.4
			$updated = true;
		}

		if ($wgRCShowWatchingUsers && $wgUser->getOption( 'shownumberswatching' )) {
			$sql3 = "SELECT COUNT(*) AS n FROM $watchlist WHERE wl_title='" .$dbr->strencode($obj->page_title). "' AND wl_namespace='{$obj->page_namespace}'" ;
			$res3 = $dbr->query( $sql3, $fname );
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

/**
 * Returns html
 */
function wlCutoffLinks( $days, $page = 'Watchlist', $options = array() ) {
	$hours = array( 1, 2, 6, 12 );
	$days = array( 1, 3, 7 );
	$i = 0;
	foreach( $hours as $h ) {
		$hours[$i++] = wlHoursLink( $h, $page, $options );
	}
	$i = 0;
	foreach( $days as $d ) {
		$days[$i++] = wlDaysLink( $d, $page, $options );
	}
	return wfMsgExt('wlshowlast',
		array('parseinline', 'replaceafter'),
		implode(' | ', $hours),
		implode(' | ', $days),
		wlDaysLink( 0, $page, $options ) );
}

/**
 * Count the number of items on a user's watchlist
 *
 * @param $talk Include talk pages
 * @return integer
 */
function wlCountItems( &$user, $talk = true ) {
	$dbr = wfGetDB( DB_SLAVE, 'watchlist' );

	# Fetch the raw count
	$res = $dbr->select( 'watchlist', 'COUNT(*) AS count', array( 'wl_user' => $user->mId ), 'wlCountItems' );
	$row = $dbr->fetchObject( $res );
	$count = $row->count;
	$dbr->freeResult( $res );

	# Halve to remove talk pages if needed
	if( !$talk )
		$count = floor( $count / 2 );

	return( $count );
}

/**
 * Allow the user to clear their watchlist
 *
 * @param $out Output object
 * @param $request Request object
 * @param $par Parameters passed to the watchlist page
 * @return bool True if it's been taken care of; false indicates the watchlist
 * 				code needs to do something further
 */
function wlHandleClear( &$out, &$request, $par ) {
	global $wgLang;

	# Check this function has something to do
	if( $request->getText( 'action' ) == 'clear' || $par == 'clear' ) {
		global $wgUser;
		$out->setPageTitle( wfMsgHtml( 'clearwatchlist' ) );
		$count = wlCountItems( $wgUser );
		if( $count > 0 ) {
			# See if we're clearing or confirming
			if( $request->wasPosted() && $wgUser->matchEditToken( $request->getText( 'token' ), 'clearwatchlist' ) ) {
				# Clearing, so do it and report the result
				$dbw = wfGetDB( DB_MASTER );
				$dbw->delete( 'watchlist', array( 'wl_user' => $wgUser->mId ), 'wlHandleClear' );
				$out->addWikiText( wfMsgExt( 'watchlistcleardone', array( 'parsemag', 'escape'), $wgLang->formatNum( $count ) ) );
				$out->returnToMain();
			} else {
				# Confirming, so show a form
				$wlTitle = SpecialPage::getTitleFor( 'Watchlist' );
				$out->addHTML( wfElement( 'form', array( 'method' => 'post', 'action' => $wlTitle->getLocalUrl( 'action=clear' ) ), NULL ) );
				$out->addWikiText( wfMsgExt( 'watchlistcount', array( 'parsemag', 'escape'), $wgLang->formatNum( $count ) ) );
				$out->addWikiText( wfMsg( 'watchlistcleartext' ) );
				$out->addHTML(
					wfHidden( 'token', $wgUser->editToken( 'clearwatchlist' ) ) .
					wfElement( 'input', array( 'type' => 'submit', 'name' => 'submit', 'value' => wfMsgHtml( 'watchlistclearbutton' ) ), '' ) .
					wfCloseElement( 'form' )
				);
			}
			return( true );
		} else {
			# Nothing on the watchlist; nothing to do here
			$out->addWikiText( wfMsg( 'nowatchlist' ) );
			$out->returnToMain();
			return( true );
		}
	} else {
		return( false );
	}
}
?>
