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
class SpecialWatchlist extends SpecialPage {
	protected $customFilters;

	/**
	 * Constructor
	 */
	public function __construct(){
		parent::__construct( 'Watchlist' );
	}

	/**
	 * Execute
	 * @param $par Parameter passed to the page
	 */
	function execute( $par ) {
		global $wgUser, $wgOut, $wgLang, $wgRequest;
		global $wgRCShowWatchingUsers, $wgEnotifWatchlist, $wgShowUpdatedMarker;

		// Add feed links
		$wlToken = $wgUser->getOption( 'watchlisttoken' );
		if ( !$wlToken ) {
			$wlToken = sha1( mt_rand() . microtime( true ) );
			$wgUser->setOption( 'watchlisttoken', $wlToken );
			$wgUser->saveSettings();
		}

		$this->addFeedLinks( array( 'action' => 'feedwatchlist', 'allrev' => 'allrev',
							'wlowner' => $wgUser->getName(), 'wltoken' => $wlToken ) );

		$skin = $this->getSkin();
		$wgOut->setRobotPolicy( 'noindex,nofollow' );

		# Anons don't get a watchlist
		if( $wgUser->isAnon() ) {
			$wgOut->setPageTitle( wfMsg( 'watchnologin' ) );
			$llink = $skin->linkKnown(
				SpecialPage::getTitleFor( 'Userlogin' ),
				wfMsgHtml( 'loginreqlink' ),
				array(),
				array( 'returnto' => $this->getTitle()->getPrefixedText() )
			);
			$wgOut->addWikiMsgArray( 'watchlistanontext', array( $llink ), array( 'replaceafter' ) );
			return;
		}

		$wgOut->setPageTitle( wfMsg( 'watchlist' ) );

		$sub = wfMsgExt(
			'watchlistfor2',
			array( 'parseinline', 'replaceafter' ),
			$wgUser->getName(),
			SpecialEditWatchlist::buildTools( $skin )
		);
		$wgOut->setSubtitle( $sub );

		$mode = SpecialEditWatchlist::getMode( $wgRequest, $par );
		if( $mode !== false ) {
			# TODO: localise?
			switch( $mode ){
				case SpecialEditWatchlist::EDIT_CLEAR:
					$mode = 'clear';
					break;
				case SpecialEditWatchlist::EDIT_RAW:
					$mode = 'raw';
					break;
				default:
					$mode = null;
			}
			$title = SpecialPage::getTitleFor( 'EditWatchlist', $mode );
			$wgOut->redirect( $title->getLocalUrl() );
			return;
		}

		$uid = $wgUser->getId();
		if( ( $wgEnotifWatchlist || $wgShowUpdatedMarker ) && $wgRequest->getVal( 'reset' ) &&
			$wgRequest->wasPosted() )
		{
			$wgUser->clearAllNotifications( $uid );
			$wgOut->redirect( $this->getTitle()->getFullUrl() );
			return;
		}

		// @TODO: use FormOptions!
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
		$this->customFilters = array();
		wfRunHooks( 'SpecialWatchlistFilters', array( $this, &$this->customFilters ) );
		foreach( $this->customFilters as $key => $params ) {
			$defaults[$key] = $params['msg'];
		}

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
		$values = array();
		$values['days']      	 = $wgRequest->getVal( 'days', $prefs['days'] );
		$values['hideMinor'] 	 = (int)$wgRequest->getBool( 'hideMinor', $prefs['hideminor'] );
		$values['hideBots']  	 = (int)$wgRequest->getBool( 'hideBots' , $prefs['hidebots'] );
		$values['hideAnons'] 	 = (int)$wgRequest->getBool( 'hideAnons', $prefs['hideanons'] );
		$values['hideLiu']   	 = (int)$wgRequest->getBool( 'hideLiu'  , $prefs['hideliu'] );
		$values['hideOwn']   	 = (int)$wgRequest->getBool( 'hideOwn'  , $prefs['hideown'] );
		$values['hidePatrolled'] = (int)$wgRequest->getBool( 'hidePatrolled', $prefs['hidepatrolled'] );
		foreach( $this->customFilters as $key => $params ) {
			$values[$key] = (int)$wgRequest->getBool( $key );
		}

		# Get namespace value, if supplied, and prepare a WHERE fragment
		$nameSpace = $wgRequest->getIntOrNull( 'namespace' );
		$invert = $wgRequest->getIntOrNull( 'invert' );
		if ( !is_null( $nameSpace ) ) {
			$nameSpace = intval( $nameSpace ); // paranioa
			if ( $invert ) {
				$nameSpaceClause = "rc_namespace != $nameSpace";
			} else {
				$nameSpaceClause = "rc_namespace = $nameSpace";
			}
		} else {
			$nameSpace = '';
			$nameSpaceClause = '';
		}
		$values['namespace'] = $nameSpace;
		$values['invert'] = $invert;

		$dbr = wfGetDB( DB_SLAVE, 'watchlist' );
		$recentchanges = $dbr->tableName( 'recentchanges' );

		$watchlistCount = $dbr->selectField( 'watchlist', 'COUNT(*)',
			array( 'wl_user' => $uid ), __METHOD__ );
		// Adjust for page X, talk:page X, which are both stored separately,
		// but treated together
		$nitems = floor( $watchlistCount / 2 );

		if( is_null( $values['days'] ) || !is_numeric( $values['days'] ) ) {
			$big = 1000; /* The magical big */
			if( $nitems > $big ) {
				# Set default cutoff shorter
				$values['days'] = $defaults['days'] = (12.0 / 24.0); # 12 hours...
			} else {
				$values['days'] = $defaults['days']; # default cutoff for shortlisters
			}
		} else {
			$values['days'] = floatval( $values['days'] );
		}

		// Dump everything here
		$nondefaults = array();
		foreach ( $defaults as $name => $defValue ) {
			wfAppendToArrayIfNotDefault( $name, $values[$name], $defaults, $nondefaults );
		}

		if( $nitems == 0 ) {
			$wgOut->addWikiMsg( 'nowatchlist' );
			return;
		}

		# Possible where conditions
		$conds = array();

		if( $values['days'] > 0 ) {
			$conds[] = "rc_timestamp > '".$dbr->timestamp( time() - intval( $values['days'] * 86400 ) )."'";
		}

		# If the watchlist is relatively short, it's simplest to zip
		# down its entirety and then sort the results.

		# If it's relatively long, it may be worth our while to zip
		# through the time-sorted page list checking for watched items.

		# Up estimate of watched items by 15% to compensate for talk pages...

		# Toggles
		if( $values['hideOwn'] ) {
			$conds[] = "rc_user != $uid";
		}
		if( $values['hideBots'] ) {
			$conds[] = 'rc_bot = 0';
		}
		if( $values['hideMinor'] ) {
			$conds[] = 'rc_minor = 0';
		}
		if( $values['hideLiu'] ) {
			$conds[] = 'rc_user = 0';
		}
		if( $values['hideAnons'] ) {
			$conds[] = 'rc_user != 0';
		}
		if ( $wgUser->useRCPatrol() && $values['hidePatrolled'] ) {
			$conds[] = 'rc_patrolled != 1';
		}
		if ( $nameSpaceClause ) {
			$conds[] = $nameSpaceClause;
		}

		# Toggle watchlist content (all recent edits or just the latest)
		if( $wgUser->getOption( 'extendwatchlist' ) ) {
			$limitWatchlist = intval( $wgUser->getOption( 'wllimit' ) );
			$usePage = false;
		} else {
			# Top log Ids for a page are not stored
			$conds[] = 'rc_this_oldid=page_latest OR rc_type=' . RC_LOG;
			$limitWatchlist = 0;
			$usePage = true;
		}

		# Show a message about slave lag, if applicable
		$lag = $dbr->getLag();
		if( $lag > 0 ) {
			$wgOut->showLagWarning( $lag );
		}

		# Create output form
		$form  = Xml::fieldset( wfMsg( 'watchlist-options' ), false, array( 'id' => 'mw-watchlist-options' ) );

		# Show watchlist header
		$form .= wfMsgExt( 'watchlist-details', array( 'parseinline' ), $wgLang->formatNum( $nitems ) );

		if( $wgUser->getOption( 'enotifwatchlistpages' ) && $wgEnotifWatchlist) {
			$form .= wfMsgExt( 'wlheader-enotif', 'parse' ) . "\n";
		}
		if( $wgShowUpdatedMarker ) {
			$form .= Xml::openElement( 'form', array( 'method' => 'post',
						'action' => $this->getTitle()->getLocalUrl(),
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
			if ( $rollbacker ) {
				$fields[] = 'page_latest';
			}
		}

		ChangeTags::modifyDisplayQuery( $tables, $fields, $conds, $join_conds, $options, '' );
		wfRunHooks('SpecialWatchlistQuery', array(&$conds,&$tables,&$join_conds,&$fields) );

		$res = $dbr->select( $tables, $fields, $conds, __METHOD__, $options, $join_conds );
		$numRows = $dbr->numRows( $res );

		/* Start bottom header */

		$wlInfo = '';
		if( $values['days'] >= 1 ) {
			$timestamp = wfTimestampNow();
			$wlInfo = wfMsgExt( 'rcnote', 'parseinline',
					$wgLang->formatNum( $numRows ),
					$wgLang->formatNum( $values['days'] ),
					$wgLang->timeAndDate( $timestamp, true ),
					$wgLang->date( $timestamp, true ),
					$wgLang->time( $timestamp, true )
				) . '<br />';
		} elseif( $values['days'] > 0 ) {
			$wlInfo = wfMsgExt( 'wlnote', 'parseinline',
					$wgLang->formatNum( $numRows ),
					$wgLang->formatNum( round( $values['days'] * 24 ) )
				) . '<br />';
		}

		$cutofflinks = "\n" . self::cutoffLinks( $values['days'], 'Watchlist', $nondefaults ) . "<br />\n";

		$thisTitle = SpecialPage::getTitleFor( 'Watchlist' );

		# Spit out some control panel links
		$filters = array(
			'hideMinor' 	=> 'rcshowhideminor',
			'hideBots' 		=> 'rcshowhidebots',
			'hideAnons' 	=> 'rcshowhideanons',
			'hideLiu' 		=> 'rcshowhideliu',
			'hideOwn' 		=> 'rcshowhidemine',
			'hidePatrolled' => 'rcshowhidepatr'
		);
		foreach ( $this->customFilters as $key => $params ) {
			$filters[$key] = $params['msg'];
		}
		// Disable some if needed
		if ( !$wgUser->useNPPatrol() ) {
			unset( $filters['hidePatrolled'] );
		}

		$links = array();
		foreach( $filters as $name => $msg ) {
			$links[] = self::showHideLink( $nondefaults, $msg, $name, $values[$name] );
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
		$form .= Html::hidden( 'days', $values['days'] );
		foreach ( $filters as $key => $msg ) {
			if ( $values[$key] ) {
				$form .= Html::hidden( $key, 1 );
			}
		}
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

			if ( $wgRCShowWatchingUsers && $wgUser->getOption( 'shownumberswatching' ) ) {
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

	public static function showHideLink( $options, $message, $name, $value ) {
		global $wgUser;

		$showLinktext = wfMsgHtml( 'show' );
		$hideLinktext = wfMsgHtml( 'hide' );
		$title = SpecialPage::getTitleFor( 'Watchlist' );
		$skin = $wgUser->getSkin();

		$label = $value ? $showLinktext : $hideLinktext;
		$options[$name] = 1 - (int) $value;

		return wfMsgHtml( $message, $skin->linkKnown( $title, $label, array(), $options ) );
	}

	public static function hoursLink( $h, $page, $options = array() ) {
		global $wgUser, $wgLang, $wgContLang;

		$sk = $wgUser->getSkin();
		$title = Title::newFromText( $wgContLang->specialPage( $page ) );
		$options['days'] = ( $h / 24.0 );

		return $sk->linkKnown(
			$title,
			$wgLang->formatNum( $h ),
			array(),
			$options
		);
	}

	public static function daysLink( $d, $page, $options = array() ) {
		global $wgUser, $wgLang, $wgContLang;

		$sk = $wgUser->getSkin();
		$title = Title::newFromText( $wgContLang->specialPage( $page ) );
		$options['days'] = $d;
		$message = ( $d ? $wgLang->formatNum( $d ) : wfMsgHtml( 'watchlistall2' ) );

		return $sk->linkKnown(
			$title,
			$message,
			array(),
			$options
		);
	}

	/**
	 * Returns html
	 *
	 * @return string
	 */
	protected static function cutoffLinks( $days, $page = 'Watchlist', $options = array() ) {
		global $wgLang;

		$hours = array( 1, 2, 6, 12 );
		$days = array( 1, 3, 7 );
		$i = 0;
		foreach( $hours as $h ) {
			$hours[$i++] = self::hoursLink( $h, $page, $options );
		}
		$i = 0;
		foreach( $days as $d ) {
			$days[$i++] = self::daysLink( $d, $page, $options );
		}
		return wfMsgExt('wlshowlast',
			array('parseinline', 'replaceafter'),
			$wgLang->pipeList( $hours ),
			$wgLang->pipeList( $days ),
			self::daysLink( 0, $page, $options ) );
	}

	/**
	 * Count the number of items on a user's watchlist
	 *
	 * @param $user User object
	 * @param $talk Boolean: include talk pages
	 * @return Integer
	 */
	protected static function countItems( &$user, $talk = true ) {
		$dbr = wfGetDB( DB_SLAVE, 'watchlist' );

		# Fetch the raw count
		$res = $dbr->select( 'watchlist', 'COUNT(*) AS count',
			array( 'wl_user' => $user->mId ), __METHOD__ );
		$row = $dbr->fetchObject( $res );
		$count = $row->count;

		# Halve to remove talk pages if needed
		if( !$talk ) {
			$count = floor( $count / 2 );
		}

		return( $count );
	}
}
