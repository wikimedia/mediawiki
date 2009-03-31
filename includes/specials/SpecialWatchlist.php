<?php
/**
 * @file
 * @ingroup SpecialPage Watchlist
 */

/**
 * Constructor
 *
 * @param $par Parameter passed to the page
 */
function wfSpecialWatchlist( $par ) {
	global $wgUser, $wgOut, $wgLang, $wgRequest;
	global $wgRCShowWatchingUsers, $wgEnotifWatchlist, $wgShowUpdatedMarker;
	global $wgEnotifWatchlist;

	$skin = $wgUser->getSkin();
	$specialTitle = SpecialPage::getTitleFor( 'Watchlist' );
	$wgOut->setRobotPolicy( 'noindex,nofollow' );

	# Anons don't get a watchlist
	if( $wgUser->isAnon() ) {
		$wgOut->setPageTitle( wfMsg( 'watchnologin' ) );
		$llink = $skin->makeKnownLinkObj( SpecialPage::getTitleFor( 'Userlogin' ), 
			wfMsgHtml( 'loginreqlink' ), 'returnto=' . $specialTitle->getPrefixedUrl() );
		$wgOut->addHTML( wfMsgWikiHtml( 'watchlistanontext', $llink ) );
		return;
	}

	$wgOut->setPageTitle( wfMsg( 'watchlist' ) );

	$sub  = wfMsgExt( 'watchlistfor', 'parseinline', $wgUser->getName() );
	$sub .= '<br />' . WatchlistEditor::buildTools( $wgUser->getSkin() );
	$wgOut->setSubtitle( $sub );

	if( ( $mode = WatchlistEditor::getMode( $wgRequest, $par ) ) !== false ) {
		$editor = new WatchlistEditor();
		$editor->execute( $wgUser, $wgOut, $wgRequest, $mode );
		return;
	}

	$uid = $wgUser->getId();
	if( ($wgEnotifWatchlist || $wgShowUpdatedMarker) && $wgRequest->getVal( 'reset' ) && 
		$wgRequest->wasPosted() )
	{
		$wgUser->clearAllNotifications( $uid );
		$wgOut->redirect( $specialTitle->getFullUrl() );
		return;
	}

	$defaults = array(
	/* float */ 'days'      => floatval( $wgUser->getOption( 'watchlistdays' ) ), /* 3.0 or 0.5, watch further below */
	/* bool  */ 'hideMinor' => (int)$wgUser->getBoolOption( 'watchlisthideminor' ),
	/* bool  */ 'hideBots'  => (int)$wgUser->getBoolOption( 'watchlisthidebots' ),
	/* bool  */ 'hideAnons' => (int)$wgUser->getBoolOption( 'watchlisthideanons' ),
	/* bool  */ 'hideLiu'   => (int)$wgUser->getBoolOption( 'watchlisthideliu' ),
	/* bool  */ 'hidePatrolled' => (int)$wgUser->getBoolOption( 'watchlisthidepatrolled' ),
	/* bool  */ 'hideOwn'   => (int)$wgUser->getBoolOption( 'watchlisthideown' ),
	/* ?     */ 'namespace' => 'all',
	/* ?     */ 'invert'    => false,
	);

	extract($defaults);

	# Extract variables from the request, falling back to user preferences or
	# other default values if these don't exist
	$prefs['days']      = floatval( $wgUser->getOption( 'watchlistdays' ) );
	$prefs['hideminor'] = $wgUser->getBoolOption( 'watchlisthideminor' );
	$prefs['hidebots']  = $wgUser->getBoolOption( 'watchlisthidebots' );
	$prefs['hideanons'] = $wgUser->getBoolOption( 'watchlisthideanon' );
	$prefs['hideliu']   = $wgUser->getBoolOption( 'watchlisthideliu' );
	$prefs['hideown' ]  = $wgUser->getBoolOption( 'watchlisthideown' );
	$prefs['hidepatrolled' ] = $wgUser->getBoolOption( 'watchlisthidepatrolled' );

	# Get query variables
	$days      = $wgRequest->getVal(  'days'     , $prefs['days'] );
	$hideMinor = $wgRequest->getBool( 'hideMinor', $prefs['hideminor'] );
	$hideBots  = $wgRequest->getBool( 'hideBots' , $prefs['hidebots'] );
	$hideAnons = $wgRequest->getBool( 'hideAnons', $prefs['hideanons'] );
	$hideLiu   = $wgRequest->getBool( 'hideLiu'  , $prefs['hideliu'] );
	$hideOwn   = $wgRequest->getBool( 'hideOwn'  , $prefs['hideown'] );
	$hidePatrolled   = $wgRequest->getBool( 'hidePatrolled'  , $prefs['hidepatrolled'] );

	# Get namespace value, if supplied, and prepare a WHERE fragment
	$nameSpace = $wgRequest->getIntOrNull( 'namespace' );
	$invert = $wgRequest->getIntOrNull( 'invert' );
	if( !is_null( $nameSpace ) ) {
		$nameSpace = intval( $nameSpace );
		if( $invert && $nameSpace !== 'all' )
			$nameSpaceClause = "rc_namespace != $nameSpace";
		else
			$nameSpaceClause = "rc_namespace = $nameSpace";
	} else {
		$nameSpace = '';
		$nameSpaceClause = '';
	}

	$dbr = wfGetDB( DB_SLAVE, 'watchlist' );
	$recentchanges = $dbr->tableName( 'recentchanges' );

	$watchlistCount = $dbr->selectField( 'watchlist', 'COUNT(*)',
		array( 'wl_user' => $uid ), __METHOD__ );
	// Adjust for page X, talk:page X, which are both stored separately,
	// but treated together
	$nitems = floor($watchlistCount / 2);

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

	wfAppendToArrayIfNotDefault( 'days'     , $days          , $defaults, $nondefaults);
	wfAppendToArrayIfNotDefault( 'hideMinor', (int)$hideMinor, $defaults, $nondefaults );
	wfAppendToArrayIfNotDefault( 'hideBots' , (int)$hideBots , $defaults, $nondefaults);
	wfAppendToArrayIfNotDefault( 'hideAnons', (int)$hideAnons, $defaults, $nondefaults );
	wfAppendToArrayIfNotDefault( 'hideLiu'  , (int)$hideLiu  , $defaults, $nondefaults );
	wfAppendToArrayIfNotDefault( 'hideOwn'  , (int)$hideOwn  , $defaults, $nondefaults);
	wfAppendToArrayIfNotDefault( 'namespace', $nameSpace     , $defaults, $nondefaults);
	wfAppendToArrayIfNotDefault( 'hidePatrolled', (int)$hidePatrolled, $defaults, $nondefaults );

	if( $nitems == 0 ) {
		$wgOut->addWikiMsg( 'nowatchlist' );
		return;
	}

	if( $days <= 0 ) {
		$andcutoff = '';
	} else {
		$andcutoff = "rc_timestamp > '".$dbr->timestamp( time() - intval( $days * 86400 ) )."'";
	}

	# If the watchlist is relatively short, it's simplest to zip
	# down its entirety and then sort the results.

	# If it's relatively long, it may be worth our while to zip
	# through the time-sorted page list checking for watched items.

	# Up estimate of watched items by 15% to compensate for talk pages...

	# Toggles
	$andHideOwn   = $hideOwn   ? "rc_user != $uid" : '';
	$andHideBots  = $hideBots  ? "rc_bot = 0" : '';
	$andHideMinor = $hideMinor ? "rc_minor = 0" : '';
	$andHideLiu   = $hideLiu   ? "rc_user = 0" : '';
	$andHideAnons = $hideAnons ? "rc_user != 0" : '';
	$andHidePatrolled = $wgUser->useRCPatrol() && $hidePatrolled ? "rc_patrolled != 1" : '';

	# Toggle watchlist content (all recent edits or just the latest)
	if( $wgUser->getOption( 'extendwatchlist' )) {
		$andLatest='';
 		$limitWatchlist = intval( $wgUser->getOption( 'wllimit' ) );
		$usePage = false;
	} else {
	# Top log Ids for a page are not stored
		$andLatest = 'rc_this_oldid=page_latest OR rc_type=' . RC_LOG;
		$limitWatchlist = 0;
		$usePage = true;
	}

	# Show a message about slave lag, if applicable
	if( ( $lag = $dbr->getLag() ) > 0 )
		$wgOut->showLagWarning( $lag );

	# Create output form
	$form  = Xml::fieldset( wfMsg( 'watchlist-options' ), false, array( 'id' => 'mw-watchlist-options' ) );

	# Show watchlist header
	$form .= wfMsgExt( 'watchlist-details', array( 'parseinline' ), $wgLang->formatNum( $nitems ) );

	if( $wgUser->getOption( 'enotifwatchlistpages' ) && $wgEnotifWatchlist) {
		$form .= wfMsgExt( 'wlheader-enotif', 'parse' ) . "\n";
	}
	if( $wgShowUpdatedMarker ) {
		$form .= Xml::openElement( 'form', array( 'method' => 'post',
					'action' => $specialTitle->getLocalUrl(),
					'id' => 'mw-watchlist-resetbutton' ) ) .
				wfMsgExt( 'wlheader-showupdated', array( 'parseinline' ) ) . ' ' .
				Xml::submitButton( wfMsg( 'enotif_reset' ), array( 'name' => 'dummy' ) ) .
				Xml::hidden( 'reset', 'all' ) .
				Xml::closeElement( 'form' );
	}
	$form .= '<hr />';
	
	$tables = array( 'recentchanges', 'watchlist' );
	$fields = array( "{$recentchanges}.*" );
	$conds = array();
	$join_conds = array(
		'watchlist' => array('INNER JOIN',"wl_user='{$uid}' AND wl_namespace=rc_namespace AND wl_title=rc_title"),
	);
	$options = array( 'ORDER BY' => 'rc_timestamp DESC' );
	if( $wgShowUpdatedMarker ) {
		$fields[] = 'wl_notificationtimestamp';
	}
	if( $limitWatchlist ) {
		$options['LIMIT'] = $limitWatchlist;
	}
	if( $andcutoff ) $conds[] = $andcutoff;
	if( $andLatest ) $conds[] = $andLatest;
	if( $andHideOwn ) $conds[] = $andHideOwn;
	if( $andHideBots ) $conds[] = $andHideBots;
	if( $andHideMinor ) $conds[] = $andHideMinor;
	if( $andHideLiu ) $conds[] = $andHideLiu;
	if( $andHideAnons ) $conds[] = $andHideAnons;
	if( $andHidePatrolled ) $conds[] = $andHidePatrolled;
	if( $nameSpaceClause ) $conds[] = $nameSpaceClause;

	$rollbacker = $wgUser->isAllowed('rollback');
	if ( $usePage || $rollbacker ) {
		$tables[] = 'page';
		$join_conds['page'] = array('LEFT JOIN','rc_cur_id=page_id');
		if ($rollbacker) 
			$fields[] = 'page_latest';
	}

	ChangeTags::modifyDisplayQuery( $tables, $fields, $conds, $join_conds, $options, '' );
	wfRunHooks('SpecialWatchlistQuery', array(&$conds,&$tables,&$join_conds,&$fields) );
	
	$res = $dbr->select( $tables, $fields, $conds, __METHOD__, $options, $join_conds );
	$numRows = $dbr->numRows( $res );

	/* Start bottom header */

	$wlInfo = '';
	if( $days >= 1 ) {
		$wlInfo = wfMsgExt( 'rcnote', 'parseinline',
				$wgLang->formatNum( $numRows ),
				$wgLang->formatNum( $days ),
				$wgLang->timeAndDate( wfTimestampNow(), true ),
				$wgLang->date( wfTimestampNow(), true ),
				$wgLang->time( wfTimestampNow(), true )
			) . '<br />';
	} elseif( $days > 0 ) {
		$wlInfo = wfMsgExt( 'wlnote', 'parseinline',
				$wgLang->formatNum( $numRows ),
				$wgLang->formatNum( round($days*24) )
			) . '<br />';
	}

	$cutofflinks = "\n" . wlCutoffLinks( $days, 'Watchlist', $nondefaults ) . "<br />\n";

	# Spit out some control panel links
	$thisTitle = SpecialPage::getTitleFor( 'Watchlist' );
	$skin = $wgUser->getSkin();

	$showLinktext = wfMsgHtml( 'show' );
	$hideLinktext = wfMsgHtml( 'hide' );
	# Hide/show minor edits
	$label = $hideMinor ? $showLinktext : $hideLinktext;
	$linkBits = wfArrayToCGI( array( 'hideMinor' => 1 - (int)$hideMinor ), $nondefaults );
	$links[] = wfMsgHtml( 'rcshowhideminor', $skin->makeKnownLinkObj( $thisTitle, $label, $linkBits ) );

	# Hide/show bot edits
	$label = $hideBots ? $showLinktext : $hideLinktext;
	$linkBits = wfArrayToCGI( array( 'hideBots' => 1 - (int)$hideBots ), $nondefaults );
	$links[] = wfMsgHtml( 'rcshowhidebots', $skin->makeKnownLinkObj( $thisTitle, $label, $linkBits ) );

	# Hide/show anonymous edits
	$label = $hideAnons ? $showLinktext : $hideLinktext;
	$linkBits = wfArrayToCGI( array( 'hideAnons' => 1 - (int)$hideAnons ), $nondefaults );
	$links[] = wfMsgHtml( 'rcshowhideanons', $skin->makeKnownLinkObj( $thisTitle, $label, $linkBits ) );

	# Hide/show logged in edits
	$label = $hideLiu ? $showLinktext : $hideLinktext;
	$linkBits = wfArrayToCGI( array( 'hideLiu' => 1 - (int)$hideLiu ), $nondefaults );
	$links[] = wfMsgHtml( 'rcshowhideliu', $skin->makeKnownLinkObj( $thisTitle, $label, $linkBits ) );

	# Hide/show own edits
	$label = $hideOwn ? $showLinktext : $hideLinktext;
	$linkBits = wfArrayToCGI( array( 'hideOwn' => 1 - (int)$hideOwn ), $nondefaults );
	$links[] = wfMsgHtml( 'rcshowhidemine', $skin->makeKnownLinkObj( $thisTitle, $label, $linkBits ) );

	# Hide/show patrolled edits
	if( $wgUser->useRCPatrol() ) {
		$label = $hidePatrolled ? $showLinktext : $hideLinktext;
		$linkBits = wfArrayToCGI( array( 'hidePatrolled' => 1 - (int)$hidePatrolled ), $nondefaults );
		$links[] = wfMsgHtml( 'rcshowhidepatr', $skin->makeKnownLinkObj( $thisTitle, $label, $linkBits ) );
	}

	# Namespace filter and put the whole form together.
	$form .= $wlInfo;
	$form .= $cutofflinks;
	$form .= $wgLang->pipeList( $links );
	$form .= Xml::openElement( 'form', array( 'method' => 'post', 'action' => $thisTitle->getLocalUrl() ) );
	$form .= '<hr /><p>';
	$form .= Xml::label( wfMsg( 'namespace' ), 'namespace' ) . '&nbsp;';
	$form .= Xml::namespaceSelector( $nameSpace, '' ) . '&nbsp;';
	$form .= Xml::checkLabel( wfMsg('invert'), 'invert', 'nsinvert', $invert ) . '&nbsp;';
	$form .= Xml::submitButton( wfMsg( 'allpagessubmit' ) ) . '</p>';
	$form .= Xml::hidden( 'days', $days );
	if( $hideMinor )
		$form .= Xml::hidden( 'hideMinor', 1 );
	if( $hideBots )
		$form .= Xml::hidden( 'hideBots', 1 );
	if( $hideAnons )
		$form .= Xml::hidden( 'hideAnons', 1 );
	if( $hideLiu )
		$form .= Xml::hidden( 'hideLiu', 1 );
	if( $hideOwn )
		$form .= Xml::hidden( 'hideOwn', 1 );
	$form .= Xml::closeElement( 'form' );
	$form .= Xml::closeElement( 'fieldset' );
	$wgOut->addHTML( $form );

	# If there's nothing to show, stop here
	if( $numRows == 0 ) {
		$wgOut->addWikiMsg( 'watchnochange' );
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

		$linkBatch->add( $row->rc_namespace, $row->rc_title );
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
			$updated = false;
		}

		if ($wgRCShowWatchingUsers && $wgUser->getOption( 'shownumberswatching' )) {
			$rc->numberofWatchingusers = $dbr->selectField( 'watchlist',
				'COUNT(*)',
				array(
					'wl_namespace' => $obj->rc_namespace,
					'wl_title' => $obj->rc_title,
				),
				__METHOD__ );
		} else {
			$rc->numberofWatchingusers = 0;
		}

		$s .= $list->recentChangesLine( $rc, $updated, $counter );
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
	global $wgLang;

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
		$wgLang->pipeList( $hours ),
		$wgLang->pipeList( $days ),
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
	$res = $dbr->select( 'watchlist', 'COUNT(*) AS count', 
		array( 'wl_user' => $user->mId ), 'wlCountItems' );
	$row = $dbr->fetchObject( $res );
	$count = $row->count;
	$dbr->freeResult( $res );

	# Halve to remove talk pages if needed
	if( !$talk )
		$count = floor( $count / 2 );

	return( $count );
}
