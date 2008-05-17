<?php
/**
 *
 * @addtogroup SpecialPage
 */

/**
 *
 */
require_once( dirname(__FILE__) . '/ChangesList.php' );

/**
 * Constructor
 */
function wfSpecialRecentchanges( $par, $specialPage ) {
	global $wgUser, $wgOut, $wgRequest, $wgUseRCPatrol;
	global $wgRCShowWatchingUsers, $wgShowUpdatedMarker;
	global $wgAllowCategorizedRecentChanges ;
	$fname = 'wfSpecialRecentchanges';

	# Get query parameters
	$feedFormat = $wgRequest->getVal( 'feed' );

	/* Checkbox values can't be true by default, because
	 * we cannot differentiate between unset and not set at all
	 */
	$defaults = array(
	/* int  */ 'days' => $wgUser->getDefaultOption('rcdays'),
	/* int  */ 'limit' => $wgUser->getDefaultOption('rclimit'),
	/* bool */ 'hideminor' => false,
	/* bool */ 'hidebots' => true,
	/* bool */ 'hideanons' => false,
	/* bool */ 'hideliu' => false,
	/* bool */ 'hidepatrolled' => false,
	/* bool */ 'hidemyself' => false,
	/* text */ 'from' => '',
	/* text */ 'namespace' => null,
	/* bool */ 'invert' => false,
	/* bool */ 'categories_any' => false,
	);

	extract($defaults);


	$days = $wgUser->getOption( 'rcdays', $defaults['days']);
	$days = $wgRequest->getInt( 'days', $days );

	$limit = $wgUser->getOption( 'rclimit', $defaults['limit'] );

	#	list( $limit, $offset ) = wfCheckLimits( 100, 'rclimit' );
	$limit = $wgRequest->getInt( 'limit', $limit );

	/* order of selection: url > preferences > default */
	$hideminor = $wgRequest->getBool( 'hideminor', $wgUser->getOption( 'hideminor') ? true : $defaults['hideminor'] );

	# As a feed, use limited settings only
	if( $feedFormat ) {
		global $wgFeedLimit;
		if( $limit > $wgFeedLimit ) {
			$limit = $wgFeedLimit;
		}

	} else {

		$namespace = $wgRequest->getIntOrNull( 'namespace' );
		$invert = $wgRequest->getBool( 'invert', $defaults['invert'] );
		$hidebots = $wgRequest->getBool( 'hidebots', $defaults['hidebots'] );
		$hideanons = $wgRequest->getBool( 'hideanons', $defaults['hideanons'] );
		$hideliu = $wgRequest->getBool( 'hideliu', $defaults['hideliu'] );
		$hidepatrolled = $wgRequest->getBool( 'hidepatrolled', $defaults['hidepatrolled'] );
		$hidemyself = $wgRequest->getBool ( 'hidemyself', $defaults['hidemyself'] );
		$from = $wgRequest->getVal( 'from', $defaults['from'] );

		# Get query parameters from path
		if( $par ) {
			$bits = preg_split( '/\s*,\s*/', trim( $par ) );
			foreach ( $bits as $bit ) {
				if ( 'hidebots' == $bit ) $hidebots = 1;
				if ( 'bots' == $bit ) $hidebots = 0;
				if ( 'hideminor' == $bit ) $hideminor = 1;
				if ( 'minor' == $bit ) $hideminor = 0;
				if ( 'hideliu' == $bit ) $hideliu = 1;
				if ( 'hidepatrolled' == $bit ) $hidepatrolled = 1;
				if ( 'hideanons' == $bit ) $hideanons = 1;
				if ( 'hidemyself' == $bit ) $hidemyself = 1;

				if ( is_numeric( $bit ) ) {
					$limit = $bit;
				}

				$m = array();
				if ( preg_match( '/^limit=(\d+)$/', $bit, $m ) ) {
					$limit = $m[1];
				}

				if ( preg_match( '/^days=(\d+)$/', $bit, $m ) ) {
					$days = $m[1];
				}
			}
		}
	}

	if ( $limit < 0 || $limit > 5000 ) $limit = $defaults['limit'];

	# Database connection and caching
	$dbr = wfGetDB( DB_SLAVE );

	$cutoff_unixtime = time() - ( $days * 86400 );
	$cutoff_unixtime = $cutoff_unixtime - ($cutoff_unixtime % 86400);
	$cutoff = $dbr->timestamp( $cutoff_unixtime );
	if(preg_match('/^[0-9]{14}$/', $from) and $from > wfTimestamp(TS_MW,$cutoff)) {
		$cutoff = $dbr->timestamp($from);
	} else {
		$from = $defaults['from'];
	}

	# 10 seconds server-side caching max
	$wgOut->setSquidMaxage( 10 );

	# Get last modified date, for client caching
	# Don't use this if we are using the patrol feature, patrol changes don't update the timestamp
	$lastmod = $dbr->selectField( 'recentchanges', 'MAX(rc_timestamp)', false, $fname );
	if ( $feedFormat || !$wgUseRCPatrol ) {
		if( $lastmod && $wgOut->checkLastModified( $lastmod ) ){
			# Client cache fresh and headers sent, nothing more to do.
			return;
		}
	}

	# It makes no sense to hide both anons and logged-in users
	# Where this occurs, force anons to be shown
	$forcebot = false;
	if( $hideanons && $hideliu ){
		# Check if the user wants to show bots only
		if( $hidebots ){
			$hideanons = 0;
		} else {
			$forcebot = true;
			$hidebots = 0;
		}
	}

	# Form WHERE fragments for all the options
	$hidem  = $hideminor ? 'AND rc_minor = 0' : '';
	$hidem .= $hidebots ? ' AND rc_bot = 0' : '';
	$hidem .= $hideliu && !$forcebot ? ' AND rc_user = 0' : '';
	$hidem .= ($wgUser->useRCPatrol() && $hidepatrolled ) ? ' AND rc_patrolled = 0' : '';
	$hidem .= $hideanons && !$forcebot ? ' AND rc_user != 0' : '';
	$hidem .= $forcebot ? ' AND rc_bot = 1' : '';

	if( $hidemyself ) {
		if( $wgUser->getID() ) {
			$hidem .= ' AND rc_user != ' . $wgUser->getID();
		} else {
			$hidem .= ' AND rc_user_text != ' . $dbr->addQuotes( $wgUser->getName() );
		}
	}

	// JOIN on watchlist for users
	$uid = $wgUser->getID();
	if( $uid ) {
		$tables = array( 'recentchanges', 'watchlist' );
		$join_conds = array( 'watchlist' => array('LEFT JOIN',"wl_user={$uid} AND wl_title=rc_title AND wl_namespace=rc_namespace") );
	} else {
		$tables = array( 'recentchanges' );
		$join_conds = array();
	}
	
	# Namespace filtering
	$hidem .= is_null($namespace) ?  '' : ' AND rc_namespace' . ($invert ? '!=' : '=') . $namespace;
	
	// Is there either one namespace selected or excluded?
	// Also, if this is "all" or main namespace, just use timestamp index.
	if( is_null($namespace) || $invert || $namespace == NS_MAIN ) {
		$res = $dbr->select( $tables, '*',
			array( "rc_timestamp >= '{$cutoff}' {$hidem}" ),
			__METHOD__,
			array( 'ORDER BY' => 'rc_timestamp DESC', 'LIMIT' => $limit, 
				'USE INDEX' => array('recentchanges' => 'rc_timestamp') ),
			$join_conds );
	// We have a new_namespace_time index! UNION over new=(0,1) and sort result set!
	} else {
		// New pages
		$sqlNew = $dbr->selectSQLText( $tables, '*',
			array( 'rc_new' => 1,
				"rc_timestamp >= '{$cutoff}' {$hidem}" ),
			__METHOD__,
			array( 'ORDER BY' => 'rc_timestamp DESC', 'LIMIT' => $limit, 
				'USE INDEX' =>  array('recentchanges' => 'new_name_timestamp') ),
			$join_conds );
		// Old pages
		$sqlOld = $dbr->selectSQLText( $tables, '*',
			array( 'rc_new' => 0,
				"rc_timestamp >= '{$cutoff}' {$hidem}" ),
			__METHOD__,
			array( 'ORDER BY' => 'rc_timestamp DESC', 'LIMIT' => $limit, 
				'USE INDEX' =>  array('recentchanges' => 'new_name_timestamp') ),
			$join_conds );
		# Join the two fast queries, and sort the result set
		$sql = "($sqlNew) UNION ($sqlOld) ORDER BY rc_timestamp DESC LIMIT $limit";
		$res = $dbr->query( $sql, __METHOD__ );
	}
	
	// Fetch results, prepare a batch link existence check query
	$rows = array();
	$batch = new LinkBatch;
	while( $row = $dbr->fetchObject( $res ) ){
		$rows[] = $row;
		if ( !$feedFormat ) {
			// User page and talk links
			$batch->add( NS_USER, $row->rc_user_text  );
			$batch->add( NS_USER_TALK, $row->rc_user_text  );
		}

	}
	$dbr->freeResult( $res );

	if( $feedFormat ) {
		rcOutputFeed( $rows, $feedFormat, $limit, $hideminor, $lastmod );
	} else {

		# Web output...

		// Run existence checks
		$batch->execute();
		$any = $wgRequest->getBool( 'categories_any', $defaults['categories_any']);

		// Output header
		if ( !$specialPage->including() ) {
			$wgOut->addWikiText( wfMsgForContentNoTrans( "recentchangestext" ) );

			// Dump everything here
			$nondefaults = array();

			wfAppendToArrayIfNotDefault( 'days', $days, $defaults, $nondefaults);
			wfAppendToArrayIfNotDefault( 'limit', $limit , $defaults, $nondefaults);
			wfAppendToArrayIfNotDefault( 'hideminor', $hideminor, $defaults, $nondefaults);
			wfAppendToArrayIfNotDefault( 'hidebots', $hidebots, $defaults, $nondefaults);
			wfAppendToArrayIfNotDefault( 'hideanons', $hideanons, $defaults, $nondefaults );
			wfAppendToArrayIfNotDefault( 'hideliu', $hideliu, $defaults, $nondefaults);
			wfAppendToArrayIfNotDefault( 'hidepatrolled', $hidepatrolled, $defaults, $nondefaults);
			wfAppendToArrayIfNotDefault( 'hidemyself', $hidemyself, $defaults, $nondefaults);
			wfAppendToArrayIfNotDefault( 'from', $from, $defaults, $nondefaults);
			wfAppendToArrayIfNotDefault( 'namespace', $namespace, $defaults, $nondefaults);
			wfAppendToArrayIfNotDefault( 'invert', $invert, $defaults, $nondefaults);
			wfAppendToArrayIfNotDefault( 'categories_any', $any, $defaults, $nondefaults);

			// Add end of the texts
			$wgOut->addHTML( '<div class="rcoptions">' . rcOptionsPanel( $defaults, $nondefaults ) . "\n" );
			$wgOut->addHTML( rcNamespaceForm( $namespace, $invert, $nondefaults, $any ) . '</div>'."\n");
		}

		// And now for the content
		$wgOut->setSyndicated( true );

		$list = ChangesList::newFromUser( $wgUser );

		if ( $wgAllowCategorizedRecentChanges ) {
			$categories = trim ( $wgRequest->getVal ( 'categories' , "" ) ) ;
			$categories = str_replace ( "|" , "\n" , $categories ) ;
			$categories = explode ( "\n" , $categories ) ;
			rcFilterByCategories ( $rows , $categories , $any ) ;
		}

		$s = $list->beginRecentChangesList();
		$counter = 1;

		$showWatcherCount = $wgRCShowWatchingUsers && $wgUser->getOption( 'shownumberswatching' );
		$watcherCache = array();

		foreach( $rows as $obj ){
			if( $limit == 0) {
				break;
			}

			if ( ! ( $hideminor     && $obj->rc_minor     ) &&
			     ! ( $hidepatrolled && $obj->rc_patrolled ) ) {
				$rc = RecentChange::newFromRow( $obj );
				$rc->counter = $counter++;

				if ($wgShowUpdatedMarker
					&& !empty( $obj->wl_notificationtimestamp )
					&& ($obj->rc_timestamp >= $obj->wl_notificationtimestamp)) {
						$rc->notificationtimestamp = true;
				} else {
					$rc->notificationtimestamp = false;
				}

				$rc->numberofWatchingusers = 0; // Default
				if ($showWatcherCount && $obj->rc_namespace >= 0) {
					if (!isset($watcherCache[$obj->rc_namespace][$obj->rc_title])) {
						$watcherCache[$obj->rc_namespace][$obj->rc_title] =
						 	$dbr->selectField( 'watchlist',
								'COUNT(*)',
								array(
									'wl_namespace' => $obj->rc_namespace,
									'wl_title' => $obj->rc_title,
								),
								__METHOD__ . '-watchers' );
					}
					$rc->numberofWatchingusers = $watcherCache[$obj->rc_namespace][$obj->rc_title];
				}
				$s .= $list->recentChangesLine( $rc, !empty( $obj->wl_user ) );
				--$limit;
			}
		}
		$s .= $list->endRecentChangesList();
		$wgOut->addHTML( $s );
	}
}

function rcFilterByCategories ( &$rows , $categories , $any ) {
	if( empty( $categories ) ) {
		return;
	}

	# Filter categories
	$cats = array () ;
	foreach ( $categories AS $cat ) {
		$cat = trim ( $cat ) ;
		if ( $cat == "" ) continue ;
		$cats[] = $cat ;
	}

	# Filter articles
	$articles = array () ;
	$a2r = array () ;
	foreach ( $rows AS $k => $r ) {
		$nt = Title::makeTitle( $r->rc_namespace, $r->rc_title );
		$id = $nt->getArticleID() ;
		if ( $id == 0 ) continue ; # Page might have been deleted...
		if ( !in_array ( $id , $articles ) ) {
			$articles[] = $id ;
		}
		if ( !isset ( $a2r[$id] ) ) {
			$a2r[$id] = array() ;
		}
		$a2r[$id][] = $k ;
	}

	# Shortcut?
	if ( count ( $articles ) == 0 OR count ( $cats ) == 0 )
		return ;

	# Look up
	$c = new Categoryfinder ;
	$c->seed ( $articles , $cats , $any ? "OR" : "AND" ) ;
	$match = $c->run () ;

	# Filter
	$newrows = array () ;
	foreach ( $match AS $id ) {
		foreach ( $a2r[$id] AS $rev ) {
			$k = $rev ;
			$newrows[$k] = $rows[$k] ;
		}
	}
	$rows = $newrows ;
}

function rcOutputFeed( $rows, $feedFormat, $limit, $hideminor, $lastmod ) {
	global $messageMemc, $wgFeedCacheTimeout;
	global $wgFeedClasses, $wgTitle, $wgSitename, $wgContLanguageCode;
	global $wgFeed;

	if ( !$wgFeed ) {
		global $wgOut;
		$wgOut->addWikiMsg( 'feed-unavailable' );
		return;
	}

	if( !isset( $wgFeedClasses[$feedFormat] ) ) {
		wfHttpError( 500, "Internal Server Error", "Unsupported feed type." );
		return false;
	}

	$timekey = wfMemcKey( 'rcfeed', $feedFormat, 'timestamp' );
	$key = wfMemcKey( 'rcfeed', $feedFormat, 'limit', $limit, 'minor', $hideminor );

	$feedTitle = $wgSitename . ' - ' . wfMsgForContent( 'recentchanges' ) .
		' [' . $wgContLanguageCode . ']';
	$feed = new $wgFeedClasses[$feedFormat](
		$feedTitle,
		htmlspecialchars( wfMsgForContent( 'recentchanges-feed-description' ) ),
		$wgTitle->getFullUrl() );

	//purge cache if requested
	global $wgRequest, $wgUser;
	$purge = $wgRequest->getVal( 'action' ) == 'purge';
	if ( $purge && $wgUser->isAllowed('purge') ) {
		$messageMemc->delete( $timekey );
		$messageMemc->delete( $key );
	}

	/**
	 * Bumping around loading up diffs can be pretty slow, so where
	 * possible we want to cache the feed output so the next visitor
	 * gets it quick too.
	 */
	$cachedFeed = false;
	if( ( $wgFeedCacheTimeout > 0 ) && ( $feedLastmod = $messageMemc->get( $timekey ) ) ) {
		/**
		 * If the cached feed was rendered very recently, we may
		 * go ahead and use it even if there have been edits made
		 * since it was rendered. This keeps a swarm of requests
		 * from being too bad on a super-frequently edited wiki.
		 */
		if( time() - wfTimestamp( TS_UNIX, $feedLastmod )
				< $wgFeedCacheTimeout
			|| wfTimestamp( TS_UNIX, $feedLastmod )
				> wfTimestamp( TS_UNIX, $lastmod ) ) {
			wfDebug( "RC: loading feed from cache ($key; $feedLastmod; $lastmod)...\n" );
			$cachedFeed = $messageMemc->get( $key );
		} else {
			wfDebug( "RC: cached feed timestamp check failed ($feedLastmod; $lastmod)\n" );
		}
	}
	if( is_string( $cachedFeed ) ) {
		wfDebug( "RC: Outputting cached feed\n" );
		$feed->httpHeaders();
		echo $cachedFeed;
	} else {
		wfDebug( "RC: rendering new feed and caching it\n" );
		ob_start();
		rcDoOutputFeed( $rows, $feed );
		$cachedFeed = ob_get_contents();
		ob_end_flush();

		$expire = 3600 * 24; # One day
		$messageMemc->set( $key, $cachedFeed );
		$messageMemc->set( $timekey, wfTimestamp( TS_MW ), $expire );
	}
	return true;
}

/**
 * @todo document
 * @param $rows Database resource with recentchanges rows
 */
function rcDoOutputFeed( $rows, &$feed ) {
	wfProfileIn( __METHOD__ );

	$feed->outHeader();

	# Merge adjacent edits by one user
	$sorted = array();
	$n = 0;
	foreach( $rows as $obj ) {
		if( $n > 0 &&
			$obj->rc_namespace >= 0 &&
			$obj->rc_cur_id == $sorted[$n-1]->rc_cur_id &&
			$obj->rc_user_text == $sorted[$n-1]->rc_user_text ) {
			$sorted[$n-1]->rc_last_oldid = $obj->rc_last_oldid;
		} else {
			$sorted[$n] = $obj;
			$n++;
		}
	}

	foreach( $sorted as $obj ) {
		$title = Title::makeTitle( $obj->rc_namespace, $obj->rc_title );
		$talkpage = $title->getTalkPage();
		$item = new FeedItem(
			$title->getPrefixedText(),
			rcFormatDiff( $obj ),
			$title->getFullURL( 'diff=' . $obj->rc_this_oldid . '&oldid=prev' ),
			$obj->rc_timestamp,
			($obj->rc_deleted & Revision::DELETED_USER) ? wfMsgHtml('rev-deleted-user') : $obj->rc_user_text,
			$talkpage->getFullURL()
			);
		$feed->outItem( $item );
	}
	$feed->outFooter();
	wfProfileOut( __METHOD__ );
}

/**
 *
 */
function rcCountLink( $lim, $d, $page='Recentchanges', $more='', $active = false ) {
	global $wgUser, $wgLang, $wgContLang;
	$sk = $wgUser->getSkin();
	$s = $sk->makeKnownLink( $wgContLang->specialPage( $page ),
	  ($lim ? $wgLang->formatNum( "{$lim}" ) : wfMsg( 'recentchangesall' ) ), "{$more}" .
	  ($d ? "days={$d}&" : '') . 'limit='.$lim, '', '',
	  $active ? 'style="font-weight: bold;"' : '' );
	return $s;
}

/**
 *
 */
function rcDaysLink( $lim, $d, $page='Recentchanges', $more='', $active = false ) {
	global $wgUser, $wgLang, $wgContLang;
	$sk = $wgUser->getSkin();
	$s = $sk->makeKnownLink( $wgContLang->specialPage( $page ),
	  ($d ? $wgLang->formatNum( "{$d}" ) : wfMsg( 'recentchangesall' ) ), $more.'days='.$d .
	  ($lim ? '&limit='.$lim : ''), '', '',
	  $active ? 'style="font-weight: bold;"' : '' );
	return $s;
}

/**
 * Used by Recentchangeslinked
 */
function rcDayLimitLinks( $days, $limit, $page='Recentchanges', $more='', $doall = false, $minorLink = '',
	$botLink = '', $liuLink = '', $patrLink = '', $myselfLink = '' ) {
	global $wgRCLinkLimits, $wgRCLinkDays;
	if ($more != '') $more .= '&';
	
	# Sort data for display and make sure it's unique after we've added user data.
	$wgRCLinkLimits[] = $limit;
	$wgRCLinkDays[] = $days;
	sort(&$wgRCLinkLimits);
	sort(&$wgRCLinkDays);
	$wgRCLinkLimits = array_unique($wgRCLinkLimits);
	$wgRCLinkDays = array_unique($wgRCLinkDays);
	
	$cl = array();
	foreach( $wgRCLinkLimits as $countLink ) {
		$cl[] = rcCountLink( $countLink, $days, $page, $more, $countLink == $limit );
	}
	if( $doall ) $cl[] = rcCountLink( 0, $days, $page, $more );
	$cl = implode( ' | ', $cl);
	
	$dl = array();
	foreach( $wgRCLinkDays as $daysLink ) {
		$dl[] = rcDaysLink( $limit, $daysLink, $page, $more, $daysLink == $days );
	}
	if( $doall ) $dl[] = rcDaysLink( $limit, 0, $page, $more );
	$dl = implode( ' | ', $dl);
	
	$linkParts = array( 'minorLink' => 'minor', 'botLink' => 'bots', 'liuLink' => 'liu', 'patrLink' => 'patr', 'myselfLink' => 'mine' );
	foreach( $linkParts as $linkVar => $linkMsg ) {
		if( $$linkVar != '' )
			$links[] = wfMsgHtml( 'rcshowhide' . $linkMsg, $$linkVar );
	}

	$shm = implode( ' | ', $links );
	$note = wfMsg( 'rclinks', $cl, $dl, $shm );
	return $note;
}


/**
 * Makes change an option link which carries all the other options
 * @param $title see Title
 * @param $override
 * @param $options
 */
function makeOptionsLink( $title, $override, $options, $active = false ) {
	global $wgUser, $wgContLang;
	$sk = $wgUser->getSkin();
	return $sk->makeKnownLink( $wgContLang->specialPage( 'Recentchanges' ),
		htmlspecialchars( $title ), wfArrayToCGI( $override, $options ), '', '',
		$active ? 'style="font-weight: bold;"' : '' );
}

/**
 * Creates the options panel.
 * @param $defaults
 * @param $nondefaults
 */
function rcOptionsPanel( $defaults, $nondefaults ) {
	global $wgLang, $wgUser, $wgRCLinkLimits, $wgRCLinkDays;

	$options = $nondefaults + $defaults;

	if( $options['from'] )
		$note = wfMsgExt( 'rcnotefrom', array( 'parseinline' ),
			$wgLang->formatNum( $options['limit'] ),
			$wgLang->timeanddate( $options['from'], true ) );
	else
		$note = wfMsgExt( 'rcnote', array( 'parseinline' ),
			$wgLang->formatNum( $options['limit'] ),
			$wgLang->formatNum( $options['days'] ),
			$wgLang->timeAndDate( wfTimestampNow(), true ) );

	# Sort data for display and make sure it's unique after we've added user data.
	$wgRCLinkLimits[] = $options['limit'];
	$wgRCLinkDays[] = $options['days'];
	sort(&$wgRCLinkLimits);
	sort(&$wgRCLinkDays);
	$wgRCLinkLimits = array_unique($wgRCLinkLimits);
	$wgRCLinkDays = array_unique($wgRCLinkDays);
	
	// limit links
	foreach( $wgRCLinkLimits as $value ) {
		$cl[] = makeOptionsLink( $wgLang->formatNum( $value ),
			array( 'limit' => $value ), $nondefaults, $value == $options['limit'] ) ;
	}
	$cl = implode( ' | ', $cl);

	// day links, reset 'from' to none
	foreach( $wgRCLinkDays as $value ) {
		$dl[] = makeOptionsLink( $wgLang->formatNum( $value ),
			array( 'days' => $value, 'from' => '' ), $nondefaults, $value == $options['days'] ) ;
	}
	$dl = implode( ' | ', $dl);


	// show/hide links
	$showhide = array( wfMsg( 'show' ), wfMsg( 'hide' ));
	$minorLink = makeOptionsLink( $showhide[1-$options['hideminor']],
		array( 'hideminor' => 1-$options['hideminor'] ), $nondefaults);
	$botLink = makeOptionsLink( $showhide[1-$options['hidebots']],
		array( 'hidebots' => 1-$options['hidebots'] ), $nondefaults);
	$anonsLink = makeOptionsLink( $showhide[ 1 - $options['hideanons'] ],
		array( 'hideanons' => 1 - $options['hideanons'] ), $nondefaults );
	$liuLink   = makeOptionsLink( $showhide[1-$options['hideliu']],
		array( 'hideliu' => 1-$options['hideliu'] ), $nondefaults);
	$patrLink  = makeOptionsLink( $showhide[1-$options['hidepatrolled']],
		array( 'hidepatrolled' => 1-$options['hidepatrolled'] ), $nondefaults);
	$myselfLink = makeOptionsLink( $showhide[1-$options['hidemyself']],
		array( 'hidemyself' => 1-$options['hidemyself'] ), $nondefaults);

	$links[] = wfMsgHtml( 'rcshowhideminor', $minorLink );
	$links[] = wfMsgHtml( 'rcshowhidebots', $botLink );
	$links[] = wfMsgHtml( 'rcshowhideanons', $anonsLink );
	$links[] = wfMsgHtml( 'rcshowhideliu', $liuLink );
	if( $wgUser->useRCPatrol() )
		$links[] = wfMsgHtml( 'rcshowhidepatr', $patrLink );
	$links[] = wfMsgHtml( 'rcshowhidemine', $myselfLink );
	$hl = implode( ' | ', $links );

	// show from this onward link
	$now = $wgLang->timeanddate( wfTimestampNow(), true );
	$tl =  makeOptionsLink( $now, array( 'from' => wfTimestampNow()), $nondefaults );

	$rclinks = wfMsgExt( 'rclinks', array( 'parseinline', 'replaceafter'),
		$cl, $dl, $hl );
	$rclistfrom = wfMsgExt( 'rclistfrom', array( 'parseinline', 'replaceafter'), $tl );
	return "$note<br />$rclinks<br />$rclistfrom";

}

/**
 * Creates the choose namespace selection
 *
 * @private
 *
 * @param $namespace Mixed: the key of the currently selected namespace, empty string
 *              if there is none
 * @param $invert Bool: whether to invert the namespace selection
 * @param $nondefaults Array: an array of non default options to be remembered
 * @param $categories_any Bool: Default value for the checkbox
 *
 * @return string
 */
function rcNamespaceForm( $namespace, $invert, $nondefaults, $categories_any ) {
	global $wgScript, $wgAllowCategorizedRecentChanges, $wgRequest;
	$t = SpecialPage::getTitleFor( 'Recentchanges' );

	$namespaceselect = HTMLnamespaceselector($namespace, '');
	$submitbutton = '<input type="submit" value="' . wfMsgHtml( 'allpagessubmit' ) . "\" />\n";
	$invertbox = "<input type='checkbox' name='invert' value='1' id='nsinvert'" . ( $invert ? ' checked="checked"' : '' ) . ' />';

	if ( $wgAllowCategorizedRecentChanges ) {
		$categories = trim ( $wgRequest->getVal ( 'categories' , "" ) ) ;
		$cb_arr = array( 'type' => 'checkbox', 'name' => 'categories_any', 'value' => "1" ) ;
		if ( $categories_any ) $cb_arr['checked'] = "checked" ;
		$catbox = "<br />" ;
		$catbox .= wfMsgExt('rc_categories', array('parseinline')) . " ";
		$catbox .= wfElement('input', array( 'type' => 'text', 'name' => 'categories', 'value' => $categories));
		$catbox .= " &nbsp;" ;
		$catbox .= wfElement('input', $cb_arr );
		$catbox .= wfMsgExt('rc_categories_any', array('parseinline'));
	} else {
		$catbox = "" ;
	}

	$out = "<div class='namespacesettings'><form method='get' action='{$wgScript}'>\n";

	foreach ( $nondefaults as $key => $value ) {
		if ($key != 'namespace' && $key != 'invert')
			$out .= wfElement('input', array( 'type' => 'hidden', 'name' => $key, 'value' => $value));
	}

	$out .= '<input type="hidden" name="title" value="'.$t->getPrefixedText().'" />';
	$out .= "
<div id='nsselect' class='recentchanges'>
	<label for='namespace'>" . wfMsgHtml('namespace') . "</label>
	{$namespaceselect}{$submitbutton}{$invertbox} <label for='nsinvert'>" . wfMsgHtml('invert') . "</label>{$catbox}\n</div>";
	$out .= '</form></div>';
	return $out;
}


/**
 * Format a diff for the newsfeed
 */
function rcFormatDiff( $row ) {
	global $wgUser;

	$titleObj = Title::makeTitle( $row->rc_namespace, $row->rc_title );
	$timestamp = wfTimestamp( TS_MW, $row->rc_timestamp );
	$actiontext = '';
	if( $row->rc_type == RC_LOG ) {
		if( $row->rc_deleted & LogPage::DELETED_ACTION ) {
			$actiontext = wfMsgHtml('rev-deleted-event');
		} else {
			$actiontext = LogPage::actionText( $row->rc_log_type, $row->rc_log_action,
				$titleObj, $wgUser->getSkin(), LogPage::extractParams($row->rc_params,true,true) );
		}
	}
	return rcFormatDiffRow( $titleObj,
		$row->rc_last_oldid, $row->rc_this_oldid,
		$timestamp,
		($row->rc_deleted & Revision::DELETED_COMMENT) ? wfMsgHtml('rev-deleted-comment') : $row->rc_comment,
		$actiontext );
}

function rcFormatDiffRow( $title, $oldid, $newid, $timestamp, $comment, $actiontext='' ) {
	global $wgFeedDiffCutoff, $wgContLang, $wgUser;
	$fname = 'rcFormatDiff';
	wfProfileIn( $fname );

	$skin = $wgUser->getSkin();
	# log enties
	if( $actiontext ) {
		$comment = "$actiontext $comment";
	}
	$completeText = '<p>' . $skin->formatComment( $comment ) . "</p>\n";

	//NOTE: Check permissions for anonymous users, not current user.
	//      No "privileged" version should end up in the cache.
	//      Most feed readers will not log in anway.
	$anon = new User();
	$accErrors = $title->getUserPermissionsErrors( 'read', $anon, true );

	if( $title->getNamespace() >= 0 && !$accErrors ) {
		if( $oldid ) {
			wfProfileIn( "$fname-dodiff" );

			$de = new DifferenceEngine( $title, $oldid, $newid );
			#$diffText = $de->getDiff( wfMsg( 'revisionasof',
			#	$wgContLang->timeanddate( $timestamp ) ),
			#	wfMsg( 'currentrev' ) );
			$diffText = $de->getDiff(
				wfMsg( 'previousrevision' ), // hack
				wfMsg( 'revisionasof',
					$wgContLang->timeanddate( $timestamp ) ) );


			if ( strlen( $diffText ) > $wgFeedDiffCutoff ) {
				// Omit large diffs
				$diffLink = $title->escapeFullUrl(
					'diff=' . $newid .
					'&oldid=' . $oldid );
				$diffText = '<a href="' .
					$diffLink .
					'">' .
					htmlspecialchars( wfMsgForContent( 'difference' ) ) .
					'</a>';
			} elseif ( $diffText === false ) {
				// Error in diff engine, probably a missing revision
				$diffText = "<p>Can't load revision $newid</p>";
			} else {
				// Diff output fine, clean up any illegal UTF-8
				$diffText = UtfNormal::cleanUp( $diffText );
				$diffText = rcApplyDiffStyle( $diffText );
			}
			wfProfileOut( "$fname-dodiff" );
		} else {
			$rev = Revision::newFromId( $newid );
			if( is_null( $rev ) ) {
				$newtext = '';
			} else {
				$newtext = $rev->getText();
			}
			$diffText = '<p><b>' . wfMsg( 'newpage' ) . '</b></p>' .
				'<div>' . nl2br( htmlspecialchars( $newtext ) ) . '</div>';
		}
		$completeText .= $diffText;
	}

	wfProfileOut( $fname );
	return $completeText;
}

/**
 * Hacky application of diff styles for the feeds.
 * Might be 'cleaner' to use DOM or XSLT or something,
 * but *gack* it's a pain in the ass.
 *
 * @param $text String:
 * @return string
 * @private
 */
function rcApplyDiffStyle( $text ) {
	$styles = array(
		'diff'             => 'background-color: white; color:black;',
		'diff-otitle'      => 'background-color: white; color:black;',
		'diff-ntitle'      => 'background-color: white; color:black;',
		'diff-addedline'   => 'background: #cfc; color:black; font-size: smaller;',
		'diff-deletedline' => 'background: #ffa; color:black; font-size: smaller;',
		'diff-context'     => 'background: #eee; color:black; font-size: smaller;',
		'diffchange'       => 'color: red; font-weight: bold; text-decoration: none;',
	);

	foreach( $styles as $class => $style ) {
		$text = preg_replace( "/(<[^>]+)class=(['\"])$class\\2([^>]*>)/",
			"\\1style=\"$style\"\\3", $text );
	}

	return $text;
}
