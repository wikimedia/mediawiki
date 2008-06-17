<?php
/**
 * @file
 * @ingroup SpecialPage
 */

class SpecialRecentChanges extends SpecialPage {
	public function __construct() {
  	SpecialPage::SpecialPage( 'Recentchanges' );
		$this->includable( true );
	}

	public function getDefaultOptions() {
		global $wgUser;

		$opts = new FormOptions();

		$opts->add( 'days',  $wgUser->getDefaultOption('rcdays') );
		$opts->add( 'limit', $wgUser->getDefaultOption('rclimit') );
		$opts->add( 'from', '' );

		$opts->add( 'hideminor',     false );
		$opts->add( 'hidebots',      true  );
		$opts->add( 'hideanons',     false );
		$opts->add( 'hideliu',       false );
		$opts->add( 'hidepatrolled', false );
		$opts->add( 'hidemyself',    false );

		$opts->add( 'namespace', '', FormOptions::INTNULL );
		$opts->add( 'invert', false );

		$opts->add( 'categories', '' );
		$opts->add( 'categories_any', false );

		return $opts;
}

	public function setup( $parameters ) {
		global $wgUser, $wgRequest;

		$opts = $this->getDefaultOptions();
		$opts['days'] = $wgUser->getOption( 'rcdays', $opts['days'] );
		$opts['limit'] = $wgUser->getOption( 'rclimit', $opts['limit'] );
		$opts['hideminor'] = $wgUser->getOption( 'hideminor', $opts['hideminor'] );
		$opts->fetchValuesFromRequest( $wgRequest );

		// Give precedence to subpage syntax
		if ( $parameters !== null ) {
			$this->parseParameters( $this->par, $opts );
		}

		$opts->validateIntBounds( 'limit', 0, 5000 );
		return $opts;
	}

	public function feedSetup() {
		global $wgFeedLimit, $wgRequest;
		$opts = $this->getDefaultOptions();
		$opts->fetchValuesFromRequest( $wgRequest, array( 'days', 'limit', 'hideminor' ) );
		$opts->validateIntBounds( 'limit', 0, $wgFeedLimit );
		return $opts;
	}

	public function execute( $parameters ) {
		global $wgRequest, $wgOut;
		$feedFormat = $wgRequest->getVal( 'feed' );

		# 10 seconds server-side caching max
		$wgOut->setSquidMaxage( 10 );

		$lastmod = $this->checkLastModified( $feedFormat );
		if ( !$lastmod ) {
			return;
		}

		$opts = $feedFormat ? $this->feedSetup() : $this->setup( $parameters );
		$this->setHeaders();

		// Fetch results, prepare a batch link existence check query
		$rows = array();
		$batch = new LinkBatch;
		$conds = $this->buildMainQueryConds( $opts );
		$res = $this->doMainQuery( $conds, $opts );
		$dbr = wfGetDB( DB_SLAVE );
		while( $row = $dbr->fetchObject( $res ) ){
			$rows[] = $row;
			if ( !$feedFormat ) {
				// User page and talk links
				$batch->add( NS_USER, $row->rc_user_text  );
				$batch->add( NS_USER_TALK, $row->rc_user_text  );
			}

		}
		$dbr->freeResult( $res );

		if ( $feedFormat ) {
			$feed = new ChangesFeed( $feedFormat, 'rcfeed' );
			$feedObj = $feed->getFeedObject(
				wfMsgForContent( 'recentchanges' ),
				wfMsgForContent( 'recentchanges-feed-description' )
			);
			$feed->execute( $feedObj, $rows, $opts['limit'], $opts['hideminor'], $lastmod );
		} else {
			$batch->execute();
			$this->webOutput( $rows, $opts );
		}
  	
	}

	public function parseParameters( $par, FormOptions $opts ) {
		$bits = preg_split( '/\s*,\s*/', trim( $par ) );
		foreach ( $bits as $bit ) {
			if ( 'hidebots' === $bit ) $opts['hidebots'] = true;
			if ( 'bots' === $bit ) $opts['hidebots'] = false;
			if ( 'hideminor' === $bit ) $opts['hideminor'] = true;
			if ( 'minor' === $bit ) $opts['hideminor'] = false;
			if ( 'hideliu' === $bit ) $opts['hideliu'] = true;
			if ( 'hidepatrolled' === $bit ) $opts['hidepatrolled'] = true;
			if ( 'hideanons' === $bit ) $opts['hideanons'] = true;
			if ( 'hidemyself' === $bit ) $opts['hidemyself'] = true;

			if ( is_numeric( $bit ) ) $opts['limit'] =  $bit;

			$m = array();
			if ( preg_match( '/^limit=(\d+)$/', $bit, $m ) ) $opts['limit'] = $m[1];
			if ( preg_match( '/^days=(\d+)$/', $bit, $m ) ) $opts['days'] = $m[1];
		}
	}

	# Get last modified date, for client caching
	# Don't use this if we are using the patrol feature, patrol changes don't update the timestamp
	public function checkLastModified( $feedFormat ) {
		global $wgUseRCPatrol, $wgOut;
		$dbr = wfGetDB( DB_SLAVE );
		$lastmod = $dbr->selectField( 'recentchanges', 'MAX(rc_timestamp)', false, __FUNCTION__ );
		if ( $feedFormat || !$wgUseRCPatrol ) {
			if( $lastmod && $wgOut->checkLastModified( $lastmod ) ){
				# Client cache fresh and headers sent, nothing more to do.
				return false;
			}
		}
		return $lastmod;
	}

	public function buildMainQueryConds( FormOptions $opts ) {
		global $wgUser;

		$dbr = wfGetDB( DB_SLAVE );
		$conds = array();

		# It makes no sense to hide both anons and logged-in users
		# Where this occurs, force anons to be shown
		$forcebot = false;
		if( $opts['hideanons'] && $opts['hideliu'] ){
			# Check if the user wants to show bots only
			if( $opts['hidebots'] ){
				$opts['hideanons'] = false;
			} else {
				$forcebot = true;
				$opts['hidebots'] = false;
			}
		}

		// Calculate cutoff
		$cutoff_unixtime = time() - ( $opts['days'] * 86400 );
		$cutoff_unixtime = $cutoff_unixtime - ($cutoff_unixtime % 86400);
		$cutoff = $dbr->timestamp( $cutoff_unixtime );

		$fromValid = preg_match('/^[0-9]{14}$/', $opts['from']);
		if( $fromValid && $opts['from'] > wfTimestamp(TS_MW,$cutoff) ) {
			$cutoff = $dbr->timestamp($opts['from']);
		} else {
			$opts->reset( 'from' );
		}

		$conds[] = 'rc_timestamp >= ' . $dbr->addQuotes( $cutoff );


		$hidePatrol = $wgUser->useRCPatrol() && $opts['hidepatrolled'];
		$hideLoggedInUsers = $opts['hideliu'] && !$forcebot;
		$hideAnonymousUsers = $opts['hideanons'] && !$forcebot;

		if ( $opts['hideminor'] )  $conds['rc_minor'] = 0;
		if ( $opts['hidebots'] )   $conds['rc_bot'] = 0;
		if ( $hidePatrol )         $conds['rc_patrolled'] = 0;
		if ( $forcebot )           $conds['rc_bot'] = 1;
		if ( $hideLoggedInUsers )  $conds[] = 'rc_user = 0';
		if ( $hideAnonymousUsers ) $conds[] = 'rc_user != 0';

		if( $opts['hidemyself'] ) {
			if( $wgUser->getId() ) {
				$conds[] = 'rc_user != ' . $dbr->addQuotes( $wgUser->getId() );
			} else {
				$conds[] = 'rc_user_text != ' . $dbr->addQuotes( $wgUser->getName() );
			}
		}
		
		# Namespace filtering
		if ( $opts['namespace'] !== '' ) {
			if ( !$opts['invert'] ) {
				$conds[] = 'rc_namespace = ' . $dbr->addQuotes( $opts['namespace'] );
			} else {
				$conds[] = 'rc_namespace != ' . $dbr->addQuotes( $opts['namespace'] );
			}
		}

		return $conds;
	}

	public function doMainQuery( $conds, $opts ) {
		global $wgUser;

		$tables = array( 'recentchanges' );
		$join_conds = array();

		$uid = $wgUser->getId();
		$dbr = wfGetDB( DB_SLAVE );
		$limit = $opts['limit'];
		$namespace = $opts['namespace'];
		$invert = $opts['invert'];

		// JOIN on watchlist for users
		if( $wgUser->getId() ) {
			$tables[] = 'watchlist';
			$join_conds = array( 'watchlist' => array('LEFT JOIN',"wl_user={$uid} AND wl_title=rc_title AND wl_namespace=rc_namespace") );
		}

		wfRunHooks('SpecialRecentChangesQuery', array( &$conds, &$tables, &$join_conds, $opts ) );

		// Is there either one namespace selected or excluded?
		// Also, if this is "all" or main namespace, just use timestamp index.
		if( is_null($namespace) || $invert || $namespace == NS_MAIN ) {
			$res = $dbr->select( $tables, '*', $conds, __METHOD__,
				array( 'ORDER BY' => 'rc_timestamp DESC', 'LIMIT' => $limit, 
					'USE INDEX' => array('recentchanges' => 'rc_timestamp') ),
				$join_conds );
		// We have a new_namespace_time index! UNION over new=(0,1) and sort result set!
		} else {
			// New pages
			$sqlNew = $dbr->selectSQLText( $tables, '*',
				array( 'rc_new' => 1 ) + $conds,
				__METHOD__,
				array( 'ORDER BY' => 'rc_timestamp DESC', 'LIMIT' => $limit, 
					'USE INDEX' =>  array('recentchanges' => 'new_name_timestamp') ),
				$join_conds );
			// Old pages
			$sqlOld = $dbr->selectSQLText( $tables, '*',
				array( 'rc_new' => 0 ) + $conds,
				__METHOD__,
				array( 'ORDER BY' => 'rc_timestamp DESC', 'LIMIT' => $limit, 
					'USE INDEX' =>  array('recentchanges' => 'new_name_timestamp') ),
				$join_conds );
			# Join the two fast queries, and sort the result set
			$sql = "($sqlNew) UNION ($sqlOld) ORDER BY rc_timestamp DESC LIMIT $limit";
			$res = $dbr->query( $sql, __METHOD__ );
		}

		return $res;
	}

	public function webOutput( $rows, $opts ) {
		global $wgOut, $wgUser, $wgRCShowWatchingUsers, $wgShowUpdatedMarker;
		global $wgAllowCategorizedRecentChanges;

		$limit = $opts['limit'];

		if ( !$this->including() ) {
			// Output options box
			$this->doHeader( $opts );
		}

		// And now for the content
		$wgOut->setSyndicated( true );

		$list = ChangesList::newFromUser( $wgUser );

		if ( $wgAllowCategorizedRecentChanges ) {
			rcFilterByCategories( $rows, $opts );
		}

		$s = $list->beginRecentChangesList();
		$counter = 1;

		$showWatcherCount = $wgRCShowWatchingUsers && $wgUser->getOption( 'shownumberswatching' );
		$watcherCache = array();

		$dbr = wfGetDB( DB_SLAVE );

		foreach( $rows as $obj ){
			if( $limit == 0) {
				break;
			}

			if ( ! ( $opts['hideminor']     && $obj->rc_minor     ) &&
			     ! ( $opts['hidepatrolled'] && $obj->rc_patrolled ) ) {
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

	public function doHeader( $opts ) {
		global $wgScript, $wgOut;
		$wgOut->addWikiText( wfMsgForContentNoTrans( 'recentchangestext' ) );

		$defaults = $opts->getAllValues();
		$nondefaults = $opts->getChangedValues();
		$opts->consumeValues( array( 'namespace', 'invert' ) );

		$panel = array();
		$panel[] = rcOptionsPanel( $defaults, $nondefaults );
		$panel[] = '<hr >';

		$extraOpts = array();
		$extraOpts['namespace'] = $this->namespaceFilterForm( $opts );

		global $wgAllowCategorizedRecentChanges;
		if ( $wgAllowCategorizedRecentChanges ) {
			$extraOpts['category'] = $this->categoryFilterForm( $opts );
		}

		wfRunHooks( 'SpecialRecentChangesPanel', array( &$extraOpts, $opts ) );
		$extraOpts['submit'] = Xml::submitbutton( wfMsg('allpagessubmit') );

		$out = Xml::openElement( 'table' );
		foreach ( $extraOpts as $optionRow ) {
			$out .= Xml::openElement( 'tr' );
			if ( is_array($optionRow) ) {
				$out .= Xml::tags( 'td', null, $optionRow[0] );
				$out .= Xml::tags( 'td', null, $optionRow[1] );
			} else {
				$out .= Xml::tags( 'td', array( 'colspan' => 2 ), $optionRow );
			}
			$out .= Xml::closeElement( 'tr' );
		}
		$out .= Xml::closeElement( 'table' );

		$unconsumed = $opts->getUnconsumedValues();
		foreach ( $unconsumed as $key => $value ) {
			$out .= Xml::hidden( $key, $value );
		}

		$t = $this->getTitle();
		$out .= Xml::hidden( 'title', $t->getPrefixedText() );
		$form = Xml::tags( 'form', array( 'action' => $wgScript ), $out );
		$panel[] = $form;
		$panelString = implode( "\n", $panel );

		$wgOut->addHTML(
			Xml::fieldset( wfMsg( 'recentchanges' ), $panelString, array( 'class' => 'rcoptions' ) )
		);
	}

	/**
	* Creates the choose namespace selection
	*
	* @return string
	*/
	protected function namespaceFilterForm( FormOptions $opts ) {
		$nsSelect = HTMLnamespaceselector( $opts['namespace'], '' );
		$nsLabel = Xml::label( wfMsg('namespace'), 'namespace' );
		$invert = Xml::checkLabel( wfMsg('invert'), 'invert', 'nsinvert', $opts['invert'] );
		return array( $nsLabel, "$nsSelect $invert" );
	}

	protected function categoryFilterForm( FormOptions $opts ) {
		list( $label, $input ) = Xml::inputLabelSep( wfMsg('rc_categories'),
			'categories', 'mw-categories', false, $opts['categories'] );

		$input .= ' ' . Xml::checkLabel( wfMsg('rc_categories_any'),
			'categories_any', 'mw-categories_any', $opts['categories_any'] );

		return array( $label, $input );
	}

}

function rcFilterByCategories ( &$rows, FormOptions $opts ) {
	$categories = array_map( 'trim', explode( "|" , $categories ) );

	if( empty($categories) ) {
		return;
	}

	# Filter categories
	$cats = array();
	foreach ( $opts['categories'] AS $cat ) {
		$cat = trim( $cat );
		if ( $cat == "" ) continue;
		$cats[] = $cat;
	}

	# Filter articles
	$articles = array();
	$a2r = array();
	foreach ( $rows AS $k => $r ) {
		$nt = Title::makeTitle( $r->rc_namespace, $r->rc_title );
		$id = $nt->getArticleID();
		if ( $id == 0 ) continue; # Page might have been deleted...
		if ( !in_array($id, $articles) ) {
			$articles[] = $id;
		}
		if ( !isset($a2r[$id]) ) {
			$a2r[$id] = array();
		}
		$a2r[$id][] = $k;
	}

	# Shortcut?
	if ( !count($articles) || !count($cats) )
		return ;

	# Look up
	$c = new Categoryfinder ;
	$c->seed( $articles, $cats, $opts['categories_any'] ? "OR" : "AND" ) ;
	$match = $c->run();

	# Filter
	$newrows = array();
	foreach ( $match AS $id ) {
		foreach ( $a2r[$id] AS $rev ) {
			$k = $rev;
			$newrows[$k] = $rows[$k];
		}
	}
	$rows = $newrows;
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
	# FIXME: why does this piss around with globals like this? Why is $limit added on globally?
	$wgRCLinkLimits[] = $limit;
	$wgRCLinkDays[] = $days;
	sort($wgRCLinkLimits);
	sort($wgRCLinkDays);
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
	sort($wgRCLinkLimits);
	sort($wgRCLinkDays);
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