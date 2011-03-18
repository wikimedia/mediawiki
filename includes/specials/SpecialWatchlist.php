<?php
/**
 * Implements Special:Watchlist
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
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

	// Add feed links
	$wlToken = $wgUser->getOption( 'watchlisttoken' );
	if (!$wlToken) {
		$wlToken = sha1( mt_rand() . microtime( true ) );
		$wgUser->setOption( 'watchlisttoken', $wlToken );
		$wgUser->saveSettings();
	}

	global $wgFeedClasses;
	$apiParams = array( 'action' => 'feedwatchlist', 'allrev' => 'allrev',
						'wlowner' => $wgUser->getName(), 'wltoken' => $wlToken );
	$feedTemplate = wfScript('api').'?';

	foreach( $wgFeedClasses as $format => $class ) {
		$theseParams = $apiParams + array( 'feedformat' => $format );
		$url = $feedTemplate . wfArrayToCGI( $theseParams );
		$wgOut->addFeedLink( $format, $url );
	}

	$skin = $wgUser->getSkin();
	$specialTitle = SpecialPage::getTitleFor( 'Watchlist' );
	$wgOut->setRobotPolicy( 'noindex,nofollow' );

	# Anons don't get a watchlist
	if( $wgUser->isAnon() ) {
		$wgOut->setPageTitle( wfMsg( 'watchnologin' ) );
		$llink = $skin->linkKnown(
			SpecialPage::getTitleFor( 'Userlogin' ),
			wfMsgHtml( 'loginreqlink' ),
			array(),
			array( 'returnto' => $specialTitle->getPrefixedText() )
		);
		$wgOut->addWikiMsgArray( 'watchlistanontext', array( $llink ), array( 'replaceafter' ) );
		return;
	}

	$wgOut->setPageTitle( wfMsg( 'watchlist' ) );

	$sub  = wfMsgExt( 'watchlistfor2', array( 'parseinline', 'replaceafter' ), $wgUser->getName(), WatchlistEditor::buildTools( $wgUser->getSkin() ) );
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

	# Extract variables from the request, falling back to user preferences or
	# other default values if these don't exist
	$prefs['days']      = floatval( $wgUser->getOption( 'watchlistdays' ) );
	$prefs['hideminor'] = $wgUser->getBoolOption( 'watchlisthideminor' );
	$prefs['hidebots']  = $wgUser->getBoolOption( 'watchlisthidebots' );
	$prefs['hideanons'] = $wgUser->getBoolOption( 'watchlisthideanons' );
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

	# Possible where conditions
	$conds = array();

	if( $days > 0 ) {
		$conds[] = "rc_timestamp > '".$dbr->timestamp( time() - intval( $days * 86400 ) )."'";
	}

	# If the watchlist is relatively short, it's simplest to zip
	# down its entirety and then sort the results.

	# If it's relatively long, it may be worth our while to zip
	# through the time-sorted page list checking for watched items.

	# Up estimate of watched items by 15% to compensate for talk pages...

	# Toggles
	if( $hideOwn ) {
		$conds[] = "rc_user != $uid";
	}
	if( $hideBots ) {
		$conds[] = 'rc_bot = 0';
	}
	if( $hideMinor ) {
		$conds[] = 'rc_minor = 0';
	}
	if( $hideLiu ) {
		$conds[] = 'rc_user = 0';
	}
	if( $hideAnons ) {
		$conds[] = 'rc_user != 0';
	}
	if ( $wgUser->useRCPatrol() && $hidePatrolled ) {
		$conds[] = 'rc_patrolled != 1';
	}
	if( $nameSpaceClause ) {
		$conds[] = $nameSpaceClause;
	}

	# Toggle watchlist content (all recent edits or just the latest)
	if( $wgUser->getOption( 'extendwatchlist' )) {
		$limitWatchlist = intval( $wgUser->getOption( 'wllimit' ) );
		$usePage = false;
	} else {
	# Top log Ids for a page are not stored
		$conds[] = 'rc_this_oldid=page_latest OR rc_type=' . RC_LOG;
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
				Html::hidden( 'reset', 'all' ) .
				Xml::closeElement( 'form' );
	}
	$form .= '<hr />';

	$tables = array( 'recentchanges', 'watchlist' );
	$fields = array( "{$recentchanges}.*" );
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

	$thisTitle = SpecialPage::getTitleFor( 'Watchlist' );

	# Spit out some control panel links
	$links[] = wlShowHideLink( $nondefaults, 'rcshowhideminor', 'hideMinor', $hideMinor );
	$links[] = wlShowHideLink( $nondefaults, 'rcshowhidebots', 'hideBots', $hideBots );
	$links[] = wlShowHideLink( $nondefaults, 'rcshowhideanons', 'hideAnons', $hideAnons );
	$links[] = wlShowHideLink( $nondefaults, 'rcshowhideliu', 'hideLiu', $hideLiu );
	$links[] = wlShowHideLink( $nondefaults, 'rcshowhidemine', 'hideOwn', $hideOwn );

	if( $wgUser->useRCPatrol() ) {
		$links[] = wlShowHideLink( $nondefaults, 'rcshowhidepatr', 'hidePatrolled', $hidePatrolled );
	}

	# Namespace filter and put the whole form together.
	$form .= $wlInfo;
	$form .= $cutofflinks;
	$form .= $wgLang->pipeList( $links );
	$form .= Xml::openElement( 'form', array( 'method' => 'post', 'action' => $thisTitle->getLocalUrl(), 'id' => 'mw-watchlist-form-namespaceselector' ) );
	$form .= '<hr /><p>';
	$form .= Xml::label( wfMsg( 'namespace' ), 'namespace' ) . '&#160;';
	$form .= Xml::namespaceSelector( $nameSpace, '' ) . '&#160;';
	$form .= Xml::checkLabel( wfMsg('invert'), 'invert', 'nsinvert', $invert ) . '&#160;';
	$form .= Xml::submitButton( wfMsg( 'allpagessubmit' ) ) . '</p>';
	$form .= Html::hidden( 'days', $days );
	if( $hideMinor )
		$form .= Html::hidden( 'hideMinor', 1 );
	if( $hideBots )
		$form .= Html::hidden( 'hideBots', 1 );
	if( $hideAnons )
		$form .= Html::hidden( 'hideAnons', 1 );
	if( $hideLiu )
		$form .= Html::hidden( 'hideLiu', 1 );
	if( $hideOwn )
		$form .= Html::hidden( 'hideOwn', 1 );
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
	foreach ( $res as $row ) {
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
	$list->setWatchlistDivs();

	$s = $list->beginRecentChangesList();
	$counter = 1;
	foreach ( $res as $obj ) {
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

	$wgOut->addHTML( $s );
}

function wlShowHideLink( $options, $message, $name, $value ) {
	global $wgUser;

	$showLinktext = wfMsgHtml( 'show' );
	$hideLinktext = wfMsgHtml( 'hide' );
	$title = SpecialPage::getTitleFor( 'Watchlist' );
	$skin = $wgUser->getSkin();

	$label = $value ? $showLinktext : $hideLinktext;
	$options[$name] = 1 - (int) $value;

	return wfMsgHtml( $message, $skin->linkKnown( $title, $label, array(), $options ) );
}


function wlHoursLink( $h, $page, $options = array() ) {
	global $wgUser, $wgLang, $wgContLang;

	$sk = $wgUser->getSkin();
	$title = Title::newFromText( $wgContLang->specialPage( $page ) );
	$options['days'] = ($h / 24.0);

	$s = $sk->linkKnown(
		$title,
		$wgLang->formatNum( $h ),
		array(),
		$options
	);

	return $s;
}

function wlDaysLink( $d, $page, $options = array() ) {
	global $wgUser, $wgLang, $wgContLang;

	$sk = $wgUser->getSkin();
	$title = Title::newFromText( $wgContLang->specialPage( $page ) );
	$options['days'] = $d;
	$message = ($d ? $wgLang->formatNum( $d ) : wfMsgHtml( 'watchlistall2' ) );

	$s = $sk->linkKnown(
		$title,
		$message,
		array(),
		$options
	);

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
 * @param $user User object
 * @param $talk Boolean: include talk pages
 * @return Integer
 */
function wlCountItems( &$user, $talk = true ) {
	$dbr = wfGetDB( DB_SLAVE, 'watchlist' );

	# Fetch the raw count
	$res = $dbr->select( 'watchlist', 'COUNT(*) AS count',
		array( 'wl_user' => $user->mId ), 'wlCountItems' );
	$row = $dbr->fetchObject( $res );
	$count = $row->count;

	# Halve to remove talk pages if needed
	if( !$talk )
		$count = floor( $count / 2 );

	return( $count );
}
