<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
require_once( 'Feed.php' );
require_once( 'ChangesList.php' );
require_once( 'Revision.php' );

/**
 * Constructor
 */
function wfSpecialRecentchanges( $par ) {
	global $wgUser, $wgOut, $wgLang, $wgContLang, $wgTitle, $wgMemc, $wgDBname;
	global $wgRequest, $wgSitename, $wgLanguageCode, $wgContLanguageCode;
	global $wgFeedClasses, $wgUseRCPatrol;
	global $wgRCShowWatchingUsers, $wgShowUpdatedMarker;
	global $wgLinkCache;
	$fname = 'wfSpecialRecentchanges';

	# Get query parameters
	$feedFormat = $wgRequest->getVal( 'feed' );

	$defaultDays = $wgUser->getOption( 'rcdays' );
	if ( !$defaultDays ) { $defaultDays = 3; }

	$days = $wgRequest->getInt( 'days', $defaultDays );
	$hideminor = $wgRequest->getBool( 'hideminor', $wgUser->getOption( 'hideminor' ) ) ? 1 : 0;
	list( $limit, $offset ) = wfCheckLimits( 100, 'rclimit' );
	
	# As a feed, use limited settings only
	if( $feedFormat ) {
		$from = null;
		$hidebots = 1;
		$hideliu = 0;
		$hidepatrolled = 0;
		$invert = 0;
		$namespace = '';
		global $wgFeedLimit;
		if( $limit > $wgFeedLimit ) {
			$limit = $wgFeedLimit;
		}
	} else {
		$from = $wgRequest->getText( 'from' );
		$hidebots = $wgRequest->getBool( 'hidebots', true );
		$hideliu = $wgRequest->getBool( 'hideliu' );
		$hidepatrolled = $wgRequest->getBool( 'hidepatrolled' );
		$namespace = $wgRequest->getVal( 'namespace', '' );
		$namespace = $namespace === '' ? NULL : $namespace;
		$invert = $wgRequest->getBool( 'invert' );
		
		# Get query parameters from path
		if( $par ) {
			$bits = preg_split( '/\s*,\s*/', trim( $par ) );
			if( in_array( 'hidebots', $bits ) ) $hidebots = 1;
			if( in_array( 'bots', $bits ) ) $hidebots = 0;
			if( in_array( 'hideminor', $bits ) ) $hideminor = 1;
			if( in_array( 'minor', $bits ) ) $hideminor = 0;
			if( in_array( 'hideliu', $bits) ) $hideliu = 1;
			if( in_array( 'hidepatrolled', $bits) ) $hidepatrolled = 1;
		}
	}


	# Database connection and caching
	$dbr =& wfGetDB( DB_SLAVE );
	extract( $dbr->tableNames( 'recentchanges', 'watchlist' ) );

	
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

	# Output header
	$rctext = wfMsgForContent( "recentchangestext" );
	$wgOut->addWikiText( $rctext );

	
	$cutoff_unixtime = time() - ( $days * 86400 );
	$cutoff_unixtime = $cutoff_unixtime - ($cutoff_unixtime % 86400);
	$cutoff = $dbr->timestamp( $cutoff_unixtime );
	if(preg_match('/^[0-9]{14}$/', $from) and $from > wfTimestamp(TS_MW,$cutoff)) {
		$cutoff = $dbr->timestamp($from);
	} else {
		$from = '';
	}

	$sk = $wgUser->getSkin();

	$showhide = array( wfMsg( 'show' ), wfMsg( 'hide' ));

	$hidem  = $hideminor ? 'AND rc_minor=0' : '';
	$hidem .= $hidebots ? ' AND rc_bot=0' : '';
	$hidem .= $hideliu ? ' AND rc_user=0' : '';
	$hidem .= $hidepatrolled ? ' AND rc_patrolled=0' : '';
	$hidem .= is_null($namespace) ? '' : ' AND rc_namespace' . ($invert ? '!=' : '=') . $namespace;

	$options = array(
		'hideminor'	=> $hideminor,
		'hideliu'	=> $hideliu,
		'hidebots'	=> $hidebots,
		'hidepatrolled'	=> $hidepatrolled,
		'days'		=> $days,
		'limit'		=> $limit,
		'from'          => $from,
		'namespace'	=> $namespace,
		'invert'	=> $invert,
	);

	$uid = $wgUser->getID();

	// Perform query
	$sql2 = "SELECT $recentchanges.*" . ($uid ? ",wl_user,wl_notificationtimestamp" : "") . " FROM $recentchanges " .
	  ($uid ? "LEFT OUTER JOIN $watchlist ON wl_user={$uid} AND wl_title=rc_title AND wl_namespace=rc_namespace " : "") .
	  "WHERE rc_timestamp > '{$cutoff}' {$hidem} " .
	  "ORDER BY rc_timestamp DESC LIMIT {$limit}";
	$res = $dbr->query( $sql2, $fname );

	// Fetch results, prepare a batch link existence check query
	$rows = array();
	$batch = new LinkBatch;
	while( $row = $dbr->fetchObject( $res ) ){
		$rows[] = $row;
		// User page link
		$title = Title::makeTitleSafe( NS_USER, $row->rc_user_text );
		$batch->addObj( $title );

		// User talk
		$title = Title::makeTitleSafe( NS_USER_TALK, $row->rc_user_text );
		$batch->addObj( $title );

	}
	$dbr->freeResult( $res );

	// Run existence checks
	$batch->execute( $wgLinkCache );

	$wgOut->addHTML( '<div class="rcoptions">' . rcOptionsPanel( $options ) . '</div>' );
	$wgOut->addHTML( namespaceForm( $namespace, $invert) );
	foreach ( $options as $key => $value ) {
	if ($value != 'namespace' && $value != 'invert')
		$wgOut->addHTML( "<input type='hidden' name='$key' value='$value' />" );
        }


	if( $feedFormat ) {
		rcOutputFeed( $rows, $feedFormat, $limit, $hideminor, $lastmod );
	} else {
# Web output...
		$wgOut->setSyndicated( true );
		$list =& new ChangesList( $sk );
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
					&& $wgUser->getOption( 'showupdated' )
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

function rcOutputFeed( $rows, $feedFormat, $limit, $hideminor, $lastmod ) {
	global $messageMemc, $wgDBname, $wgFeedCacheTimeout;
	global $wgFeedClasses, $wgTitle, $wgSitename, $wgContLanguageCode;
	
	if( !isset( $wgFeedClasses[$feedFormat] ) ) {
		wfHttpError( 500, "Internal Server Error", "Unsupported feed type." );
		return false;
	}
	
	$timekey = "$wgDBname:rcfeed:$feedFormat:timestamp";
	$key = "$wgDBname:rcfeed:$feedFormat:limit:$limit:minor:$hideminor";
	
	$feedTitle = $wgSitename . ' - ' . wfMsgForContent( 'recentchanges' ) .
		' [' . $wgContLanguageCode . ']';
	$feed = new $wgFeedClasses[$feedFormat](
		$feedTitle,
		htmlspecialchars( wfMsgForContent( 'recentchangestext' ) ),
		$wgTitle->getFullUrl() );

	/**
	 * Bumping around loading up diffs can be pretty slow, so where
	 * possible we want to cache the feed output so the next visitor
	 * gets it quick too.
	 */
	$cachedFeed = false;
	if( $feedLastmod = $messageMemc->get( $timekey ) ) {
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
	global $wgSitename, $wgFeedClasses, $wgContLanguageCode;
	
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
		$first = false;
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
	$botLink = '', $liuLink = '', $patrLink = '' ) {
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
	$shm = wfMsg( 'showhideminor', $minorLink, $botLink, $liuLink, $patrLink );
	$note = wfMsg( 'rclinks', $cl, $dl, $shm );
	return $note;
}


/**
 * Makes change an option link which carries all the other options
 */
function makeOptionsLink( $title, $override, $options ) {
	global $wgUser, $wgLang, $wgContLang;
	$sk = $wgUser->getSkin();
	return $sk->makeKnownLink( $wgContLang->specialPage( 'Recentchanges' ),
		$title, wfArrayToCGI( $override, $options ) );
}

/**
 * Creates the options panel
 */
function rcOptionsPanel( $options ) {
	global $wgLang;

	if( $options['from'] )
		$note = wfMsg( 'rcnotefrom', $wgLang->formatNum( $options['limit'] ), $wgLang->timeanddate( $options['from'], true ) );
	else
		$note = wfMsg( 'rcnote', $wgLang->formatNum( $options['limit'] ), $wgLang->formatNum( $options['days'] ) );

	// limit links
	$cl = '';
	$options_limit = array(50, 100, 250, 500);
	$i = 0;
	while ( $i+1 < count($options_limit) ) {
		$cl .=  makeOptionsLink( $options_limit[$i], array( 'limit' => $options_limit[$i] ), $options) . ' | ' ;
		$i++;
	}
	$cl .=  makeOptionsLink( $options_limit[$i], array( 'limit' => $options_limit[$i] ), $options) ;

	// day links, reset 'from' to none
	$dl = '';
	$options_days = array(1, 3, 7, 14, 30);
	$i = 0;
	while ( $i+1 < count($options_days) ) {
		$dl .=  makeOptionsLink( $options_days[$i], array( 'days' => $options_days[$i], 'from' => '' ), $options) . ' | ' ;
		$i++;
	}
	$dl .=  makeOptionsLink( $options_days[$i], array( 'days' => $options_days[$i], 'from' => '' ), $options) ;

	// show/hide links
	$showhide = array( wfMsg( 'show' ), wfMsg( 'hide' ));
	$minorLink = makeOptionsLink( $showhide[1-$options['hideminor']],
		array( 'hideminor' => 1-$options['hideminor'] ), $options);
	$botLink = makeOptionsLink( $showhide[1-$options['hidebots']],
		array( 'hidebots' => 1-$options['hidebots'] ), $options);
	$liuLink   = makeOptionsLink( $showhide[1-$options['hideliu']],
		array( 'hideliu' => 1-$options['hideliu'] ), $options);
	$patrLink  = makeOptionsLink( $showhide[1-$options['hidepatrolled']],
		array( 'hidepatrolled' => 1-$options['hidepatrolled'] ), $options);

	$hl = wfMsg( 'showhideminor', $minorLink, $botLink, $liuLink, $patrLink );
	
	// show from this onward link
	$now = $wgLang->timeanddate( wfTimestampNow(), true );
	$tl =  makeOptionsLink( $now, array( 'from' => $now), $options );
	
	$rclinks = wfMsg( 'rclinks', $cl, $dl, $hl );
	$rclistfrom = wfMsg( 'rclistfrom', $tl );
	return "$note<br />$rclinks<br />$rclistfrom";

}

/**<F2>
 * Creates the choose namespace selection
 *
 * @access private
 *
 * @param mixed $namespace The key of the currently selected namespace, NULL
 *              if there is none
 * @param bool $invert Whether to invert the namespace selection
 *
 * @return string
 */
function namespaceForm ( $namespace, $invert ) {
	global $wgContLang, $wgScript;
	$t = Title::makeTitle( NS_SPECIAL, 'Recentchanges' );

	$namespaceselect = "<select name='namespace' id='nsselectbox'>";
	$namespaceselect .= '<option value="" ' . (is_null($namespace) ? ' selected="selected"' : '') . '>' . wfMsg( 'contributionsall' ) . '</option>';
	$arr =  $wgContLang->getFormattedNamespaces();
	foreach ( $arr as $ns => $name ) {
		if( $ns < NS_MAIN )
			continue;
		$n = $ns === NS_MAIN ? wfMsg ( 'blanknamespace' ) : $name;
		$sel = $namespace === (string)$ns ? ' selected="selected"' : '';
		$namespaceselect .= "<option value='$ns'$sel>$n</option>";
	}
	$namespaceselect .= '</select>';

	$submitbutton = '<input type="submit" value="' . wfMsg( 'allpagessubmit' ) . '" />';
	$invertbox = "<input type='checkbox' name='invert' value='1' id='nsinvert'" . ( $invert ? ' checked="checked"' : '' ) . ' />';
	
	$out = "<div class='namespaceselector'><form method='get' action='{$wgScript}'>\n";
	$out .= '<input type="hidden" name="title" value="'.$t->getPrefixedText().'" />';
	$out .= "
<table id='nsselect' class='recentchanges'>
	<tr>
		<td align='right'><label for='nsselectbox'>" . wfMsg('namespace') . "</label></td>
		<td align='left'>$namespaceselect $invertbox <label for='nsinvert'>" . wfMsg('invert') . "</label> $submitbutton </td>
	</tr>
</table>";
	$out .= '</form></div>';
	return $out;
}


/**
 * Format a diff for the newsfeed
 */
function rcFormatDiff( $row ) {
	$fname = 'rcFormatDiff';
	wfProfileIn( $fname );
	
	require_once( 'DifferenceEngine.php' );
	$comment = "<p>" . htmlspecialchars( $row->rc_comment ) . "</p>\n";
	
	if( $row->rc_namespace >= 0 ) {
		global $wgContLang;
		
		#$diff =& new DifferenceEngine( $row->rc_this_oldid, $row->rc_last_oldid, $row->rc_id );
		#$diff->showDiffPage();
		
		$titleObj = Title::makeTitle( $row->rc_namespace, $row->rc_title );
		$dbr =& wfGetDB( DB_SLAVE );
		$newrev =& Revision::newFromTitle( $titleObj, $row->rc_this_oldid );
		if( $newrev ) {
			$newtext = $newrev->getText();
		} else {
			$diffText = "<p>Can't load revision $row->rc_this_oldid</p>";
			wfProfileOut( $fname );
			return $comment . $diffText;
		}

		if( $row->rc_last_oldid ) {
			wfProfileIn( "$fname-dodiff" );
			$oldrev =& Revision::newFromId( $row->rc_last_oldid );
			if( !$oldrev ) {
				$diffText = "<p>Can't load old revision $row->rc_last_oldid</p>";
				wfProfileOut( $fname );
				return $comment . $diffText;
			}
			$oldtext = $oldrev->getText();
				
			# Old entries may contain illegal characters
			# which will damage output
			$oldtext = UtfNormal::cleanUp( $oldtext );
			
			global $wgFeedDiffCutoff;
			if( strlen( $newtext ) > $wgFeedDiffCutoff ||
				strlen( $oldtext ) > $wgFeedDiffCutoff ) {
				$diffLink = $titleObj->escapeFullUrl(
					'diff=' . $row->rc_this_oldid .
					'&oldid=' . $row->rc_last_oldid );
				$diffText = '<a href="' .
					$diffLink .
					'">' .
					htmlspecialchars( wfMsgForContent( 'difference' ) ) .
					'</a>';
			} else {
				$diffText = DifferenceEngine::getDiff( $oldtext, $newtext,
				  wfMsg( 'revisionasof', $wgContLang->timeanddate( $row->rc_timestamp ) ),
				  wfMsg( 'currentrev' ) );
			}
			wfProfileOut( "$fname-dodiff" );
		} else {
			$diffText = '<p><b>' . wfMsg( 'newpage' ) . '</b></p>' . 
				'<div>' . nl2br( htmlspecialchars( $newtext ) ) . '</div>';
		}
		
		wfProfileOut( $fname );
		return $comment . $diffText;
	}
	
	wfProfileOut( $fname );
	return $comment;	
}

?>
