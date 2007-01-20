<?php
/**
 *
 * @addtogroup SpecialPage
 */

/**
 *
 */
require_once( 'ChangesList.php' );

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

	/* Checkbox values can't be true be default, because
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
			$options['limit'] = $wgFeedLimit;
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
	$dbr =& wfGetDB( DB_SLAVE );
	list( $recentchanges, $watchlist ) = $dbr->tableNamesN( 'recentchanges', 'watchlist' );


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
	if( $hideanons && $hideliu )
		$hideanons = false;

	# Form WHERE fragments for all the options
	$hidem  = $hideminor ? 'AND rc_minor = 0' : '';
	$hidem .= $hidebots ? ' AND rc_bot = 0' : '';
	$hidem .= $hideliu ? ' AND rc_user = 0' : '';
	$hidem .= ( $wgUseRCPatrol && $hidepatrolled ) ? ' AND rc_patrolled = 0' : '';
	$hidem .= $hideanons ? ' AND rc_user != 0' : '';
	
	if( $hidemyself ) {
		if( $wgUser->getID() ) {
			$hidem .= ' AND rc_user != ' . $wgUser->getID();
		} else {
			$hidem .= ' AND rc_user_text != ' . $dbr->addQuotes( $wgUser->getName() );
		}
	}

	# Namespace filtering
	$hidem .= is_null( $namespace ) ?  '' : ' AND rc_namespace' . ($invert ? '!=' : '=') . $namespace;

	// This is the big thing!

	$uid = $wgUser->getID();

	// Perform query
	$forceclause = $dbr->useIndexClause("rc_timestamp");
	$sql2 = "SELECT * FROM $recentchanges $forceclause".
	  ($uid ? "LEFT OUTER JOIN $watchlist ON wl_user={$uid} AND wl_title=rc_title AND wl_namespace=rc_namespace " : "") .
	  "WHERE rc_timestamp >= '{$cutoff}' {$hidem} " .
	  "ORDER BY rc_timestamp DESC";
	$sql2 = $dbr->limitResult($sql2, $limit, 0);
	$res = $dbr->query( $sql2, $fname );

	// Fetch results, prepare a batch link existence check query
	$rows = array();
	$batch = new LinkBatch;
	while( $row = $dbr->fetchObject( $res ) ){
		$rows[] = $row;
		if ( !$feedFormat ) {
			// User page link
			$title = Title::makeTitleSafe( NS_USER, $row->rc_user_text );
			$batch->addObj( $title );

			// User talk
			$title = Title::makeTitleSafe( NS_USER_TALK, $row->rc_user_text );
			$batch->addObj( $title );
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

				if ($wgRCShowWatchingUsers && $wgUser->getOption( 'shownumberswatching' )) {
					$sql3 = "SELECT COUNT(*) AS n FROM $watchlist WHERE wl_title='" . $dbr->strencode($obj->rc_title) ."' AND wl_namespace=$obj->rc_namespace" ;
					$res3 = $dbr->query( $sql3, 'wfSpecialRecentChanges');
					$x = $dbr->fetchObject( $res3 );
					$rc->numberofWatchingusers = $x->n;
				} else {
					$rc->numberofWatchingusers = 0;
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
	require_once ( 'Categoryfinder.php' ) ;
	
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
		$nt = Title::newFromText ( $r->rc_title , $r->rc_namespace ) ;
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

function rcDoOutputFeed( $rows, &$feed ) {
	$fname = 'rcDoOutputFeed';
	wfProfileIn( $fname );

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
			$title->getFullURL(),
			$obj->rc_timestamp,
			$obj->rc_user_text,
			$talkpage->getFullURL()
			);
		$feed->outItem( $item );
	}
	$feed->outFooter();
	wfProfileOut( $fname );
}

/**
 *
 */
function rcCountLink( $lim, $d, $page='Recentchanges', $more='' ) {
	global $wgUser, $wgLang, $wgContLang;
	$sk = $wgUser->getSkin();
	$s = $sk->makeKnownLink( $wgContLang->specialPage( $page ),
	  ($lim ? $wgLang->formatNum( "{$lim}" ) : wfMsg( 'recentchangesall' ) ), "{$more}" .
	  ($d ? "days={$d}&" : '') . 'limit='.$lim );
	return $s;
}

/**
 *
 */
function rcDaysLink( $lim, $d, $page='Recentchanges', $more='' ) {
	global $wgUser, $wgLang, $wgContLang;
	$sk = $wgUser->getSkin();
	$s = $sk->makeKnownLink( $wgContLang->specialPage( $page ),
	  ($d ? $wgLang->formatNum( "{$d}" ) : wfMsg( 'recentchangesall' ) ), $more.'days='.$d .
	  ($lim ? '&limit='.$lim : '') );
	return $s;
}

/**
 * Used by Recentchangeslinked
 */
function rcDayLimitLinks( $days, $limit, $page='Recentchanges', $more='', $doall = false, $minorLink = '',
	$botLink = '', $liuLink = '', $patrLink = '', $myselfLink = '' ) {
	if ($more != '') $more .= '&';
	$cl = rcCountLink( 50, $days, $page, $more ) . ' | ' .
	  rcCountLink( 100, $days, $page, $more  ) . ' | ' .
	  rcCountLink( 250, $days, $page, $more  ) . ' | ' .
	  rcCountLink( 500, $days, $page, $more  ) .
	  ( $doall ? ( ' | ' . rcCountLink( 0, $days, $page, $more ) ) : '' );
	$dl = rcDaysLink( $limit, 1, $page, $more  ) . ' | ' .
	  rcDaysLink( $limit, 3, $page, $more  ) . ' | ' .
	  rcDaysLink( $limit, 7, $page, $more  ) . ' | ' .
	  rcDaysLink( $limit, 14, $page, $more  ) . ' | ' .
	  rcDaysLink( $limit, 30, $page, $more  ) .
	  ( $doall ? ( ' | ' . rcDaysLink( $limit, 0, $page, $more ) ) : '' );
	
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
 * @param $title @see Title
 * @param $override
 * @param $options
 */
function makeOptionsLink( $title, $override, $options ) {
	global $wgUser, $wgContLang;
	$sk = $wgUser->getSkin();
	return $sk->makeKnownLink( $wgContLang->specialPage( 'Recentchanges' ),
		htmlspecialchars( $title ), wfArrayToCGI( $override, $options ) );
}

/**
 * Creates the options panel.
 * @param $defaults
 * @param $nondefaults
 */
function rcOptionsPanel( $defaults, $nondefaults ) {
	global $wgLang, $wgUseRCPatrol;

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

	// limit links
	$options_limit = array(50, 100, 250, 500);
	foreach( $options_limit as $value ) {
		$cl[] = makeOptionsLink( $wgLang->formatNum( $value ),
			array( 'limit' => $value ), $nondefaults) ;
	}
	$cl = implode( ' | ', $cl);

	// day links, reset 'from' to none
	$options_days = array(1, 3, 7, 14, 30);
	foreach( $options_days as $value ) {
		$dl[] = makeOptionsLink( $wgLang->formatNum( $value ),
			array( 'days' => $value, 'from' => ''  ), $nondefaults) ;
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
	if( $wgUseRCPatrol )
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
	$titleObj = Title::makeTitle( $row->rc_namespace, $row->rc_title );
	$timestamp = wfTimestamp( TS_MW, $row->rc_timestamp );
	return rcFormatDiffRow( $titleObj,
		$row->rc_last_oldid, $row->rc_this_oldid,
		$timestamp,
		$row->rc_comment );
}

function rcFormatDiffRow( $title, $oldid, $newid, $timestamp, $comment ) {
	global $wgFeedDiffCutoff, $wgContLang, $wgUser;
	$fname = 'rcFormatDiff';
	wfProfileIn( $fname );

	$skin = $wgUser->getSkin();
	$completeText = '<p>' . $skin->formatComment( $comment ) . "</p>\n";

	if( $title->getNamespace() >= 0 ) {
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
		'diff'             => 'background-color: white;',
		'diff-otitle'      => 'background-color: white;',
		'diff-ntitle'      => 'background-color: white;',
		'diff-addedline'   => 'background: #cfc; font-size: smaller;',
		'diff-deletedline' => 'background: #ffa; font-size: smaller;',
		'diff-context'     => 'background: #eee; font-size: smaller;',
		'diffchange'       => 'color: red; font-weight: bold;',
	);
	
	foreach( $styles as $class => $style ) {
		$text = preg_replace( "/(<[^>]+)class=(['\"])$class\\2([^>]*>)/",
			"\\1style=\"$style\"\\3", $text );
	}
	
	return $text;
}

?>
