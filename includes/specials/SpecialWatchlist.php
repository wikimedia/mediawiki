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
	public function __construct( $page = 'Watchlist' ){
		parent::__construct( $page );
	}

	/**
	 * Execute
	 * @param $par Parameter passed to the page
	 */
	function execute( $par ) {
		global $wgRCShowWatchingUsers, $wgEnotifWatchlist, $wgShowUpdatedMarker;

		$user = $this->getUser();
		$output = $this->getOutput();

		# Anons don't get a watchlist
		if( $user->isAnon() ) {
			$output->setPageTitle( $this->msg( 'watchnologin' ) );
			$output->setRobotPolicy( 'noindex,nofollow' );
			$llink = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Userlogin' ),
				$this->msg( 'loginreqlink' )->escaped(),
				array(),
				array( 'returnto' => $this->getTitle()->getPrefixedText() )
			);
			$output->addHTML( $this->msg( 'watchlistanontext' )->rawParams( $llink )->parse() );
			return;
		}

		// Add feed links
		$wlToken = $user->getOption( 'watchlisttoken' );
		if ( !$wlToken ) {
			$wlToken = MWCryptRand::generateHex( 40 );
			$user->setOption( 'watchlisttoken', $wlToken );
			$user->saveSettings();
		}

		$this->addFeedLinks( array( 'action' => 'feedwatchlist', 'allrev' => 'allrev',
							'wlowner' => $user->getName(), 'wltoken' => $wlToken ) );

		$this->setHeaders();
		$this->outputHeader();

		$output->addSubtitle( $this->msg( 'watchlistfor2', $user->getName()
			)->rawParams( SpecialEditWatchlist::buildTools( null ) ) );

		$request = $this->getRequest();

		$mode = SpecialEditWatchlist::getMode( $request, $par );
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
			$output->redirect( $title->getLocalUrl() );
			return;
		}

		if( ( $wgEnotifWatchlist || $wgShowUpdatedMarker ) && $request->getVal( 'reset' ) &&
			$request->wasPosted() )
		{
			$user->clearAllNotifications();
			$output->redirect( $this->getTitle()->getFullUrl() );
			return;
		}

		$nitems = $this->countItems();
		if ( $nitems == 0 ) {
			$output->addWikiMsg( 'nowatchlist' );
			return;
		}

		// @TODO: use FormOptions!
		$defaults = array(
		/* float */ 'days'      => floatval( $user->getOption( 'watchlistdays' ) ), /* 3.0 or 0.5, watch further below */
		/* bool  */ 'hideMinor' => (int)$user->getBoolOption( 'watchlisthideminor' ),
		/* bool  */ 'hideBots'  => (int)$user->getBoolOption( 'watchlisthidebots' ),
		/* bool  */ 'hideAnons' => (int)$user->getBoolOption( 'watchlisthideanons' ),
		/* bool  */ 'hideLiu'   => (int)$user->getBoolOption( 'watchlisthideliu' ),
		/* bool  */ 'hidePatrolled' => (int)$user->getBoolOption( 'watchlisthidepatrolled' ),
		/* bool  */ 'hideOwn'   => (int)$user->getBoolOption( 'watchlisthideown' ),
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
		$prefs['days']      = floatval( $user->getOption( 'watchlistdays' ) );
		$prefs['hideminor'] = $user->getBoolOption( 'watchlisthideminor' );
		$prefs['hidebots']  = $user->getBoolOption( 'watchlisthidebots' );
		$prefs['hideanons'] = $user->getBoolOption( 'watchlisthideanons' );
		$prefs['hideliu']   = $user->getBoolOption( 'watchlisthideliu' );
		$prefs['hideown' ]  = $user->getBoolOption( 'watchlisthideown' );
		$prefs['hidepatrolled' ] = $user->getBoolOption( 'watchlisthidepatrolled' );

		# Get query variables
		$values = array();
		$values['days']      	 = $request->getVal( 'days', $prefs['days'] );
		$values['hideMinor'] 	 = (int)$request->getBool( 'hideMinor', $prefs['hideminor'] );
		$values['hideBots']  	 = (int)$request->getBool( 'hideBots' , $prefs['hidebots'] );
		$values['hideAnons'] 	 = (int)$request->getBool( 'hideAnons', $prefs['hideanons'] );
		$values['hideLiu']   	 = (int)$request->getBool( 'hideLiu'  , $prefs['hideliu'] );
		$values['hideOwn']   	 = (int)$request->getBool( 'hideOwn'  , $prefs['hideown'] );
		$values['hidePatrolled'] = (int)$request->getBool( 'hidePatrolled', $prefs['hidepatrolled'] );
		foreach( $this->customFilters as $key => $params ) {
			$values[$key] = (int)$request->getBool( $key );
		}

		# Get namespace value, if supplied, and prepare a WHERE fragment
		$nameSpace = $request->getIntOrNull( 'namespace' );
		$invert = $request->getIntOrNull( 'invert' );
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

		$dbr = wfGetDB( DB_SLAVE, 'watchlist' );

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
			$conds[] = 'rc_user != ' . $user->getId();
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
		if ( $user->useRCPatrol() && $values['hidePatrolled'] ) {
			$conds[] = 'rc_patrolled != 1';
		}
		if ( $nameSpaceClause ) {
			$conds[] = $nameSpaceClause;
		}

		# Toggle watchlist content (all recent edits or just the latest)
		if( $user->getOption( 'extendwatchlist' ) ) {
			$limitWatchlist = intval( $user->getOption( 'wllimit' ) );
			$usePage = false;
		} else {
			# Top log Ids for a page are not stored
			$conds[] = 'rc_this_oldid=page_latest OR rc_type=' . RC_LOG;
			$limitWatchlist = 0;
			$usePage = true;
		}

		# Show a message about slave lag, if applicable
		$lag = wfGetLB()->safeGetLag( $dbr );
		if( $lag > 0 ) {
			$output->showLagWarning( $lag );
		}

		# Create output form
		$form  = Xml::fieldset( $this->msg( 'watchlist-options' )->text(), false, array( 'id' => 'mw-watchlist-options' ) );

		# Show watchlist header
		$form .= $this->msg( 'watchlist-details' )->numParams( $nitems )->parse();

		if( $user->getOption( 'enotifwatchlistpages' ) && $wgEnotifWatchlist) {
			$form .= $this->msg( 'wlheader-enotif' )->parseAsBlock() . "\n";
		}
		if( $wgShowUpdatedMarker ) {
			$form .= Xml::openElement( 'form', array( 'method' => 'post',
						'action' => $this->getTitle()->getLocalUrl(),
						'id' => 'mw-watchlist-resetbutton' ) ) .
					$this->msg( 'wlheader-showupdated' )->parse() . ' ' .
					Xml::submitButton( $this->msg( 'enotif_reset' )->text(), array( 'name' => 'dummy' ) ) .
					Html::hidden( 'reset', 'all' ) .
					Xml::closeElement( 'form' );
		}
		$form .= '<hr />';

		$tables = array( 'recentchanges', 'watchlist' );
		$fields = array( $dbr->tableName( 'recentchanges' ) . '.*' );
		$join_conds = array(
			'watchlist' => array('INNER JOIN',"wl_user='{$user->getId()}' AND wl_namespace=rc_namespace AND wl_title=rc_title"),
		);
		$options = array( 'ORDER BY' => 'rc_timestamp DESC' );
		if( $wgShowUpdatedMarker ) {
			$fields[] = 'wl_notificationtimestamp';
		}
		if( $limitWatchlist ) {
			$options['LIMIT'] = $limitWatchlist;
		}

		$rollbacker = $user->isAllowed('rollback');
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

		$lang = $this->getLanguage();
		$wlInfo = '';
		if( $values['days'] > 0 ) {
			$timestamp = wfTimestampNow();
			$wlInfo = $this->msg( 'wlnote' )->numParams( $numRows, round( $values['days'] * 24 ) )->params(
				$lang->userDate( $timestamp, $user ), $lang->userTime( $timestamp, $user ) )->parse() . '<br />';
		}

		$cutofflinks = "\n" . $this->cutoffLinks( $values['days'], $nondefaults ) . "<br />\n";

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
		if ( !$user->useNPPatrol() ) {
			unset( $filters['hidePatrolled'] );
		}

		$links = array();
		foreach( $filters as $name => $msg ) {
			$links[] = $this->showHideLink( $nondefaults, $msg, $name, $values[$name] );
		}

		# Namespace filter and put the whole form together.
		$form .= $wlInfo;
		$form .= $cutofflinks;
		$form .= $lang->pipeList( $links );
		$form .= Xml::openElement( 'form', array( 'method' => 'post', 'action' => $this->getTitle()->getLocalUrl(), 'id' => 'mw-watchlist-form-namespaceselector' ) );
		$form .= '<hr /><p>';
		$form .= Xml::label( $this->msg( 'namespace' )->text(), 'namespace' ) . '&#160;';
		$form .= Xml::namespaceSelector( $nameSpace, '' ) . '&#160;';
		$form .= Xml::checkLabel( $this->msg( 'invert' )->text(), 'invert', 'nsinvert', $invert ) . '&#160;';
		$form .= Xml::submitButton( $this->msg( 'allpagessubmit' )->text() ) . '</p>';
		$form .= Html::hidden( 'days', $values['days'] );
		foreach ( $filters as $key => $msg ) {
			if ( $values[$key] ) {
				$form .= Html::hidden( $key, 1 );
			}
		}
		$form .= Xml::closeElement( 'form' );
		$form .= Xml::closeElement( 'fieldset' );
		$output->addHTML( $form );

		# If there's nothing to show, stop here
		if( $numRows == 0 ) {
			$output->addWikiMsg( 'watchnochange' );
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

		$list = ChangesList::newFromContext( $this->getContext() );
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

			if ( $wgRCShowWatchingUsers && $user->getOption( 'shownumberswatching' ) ) {
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

		$output->addHTML( $s );
	}

	protected function showHideLink( $options, $message, $name, $value ) {
		$label = $this->msg( $value ? 'show' : 'hide' )->escaped();
		$options[$name] = 1 - (int) $value;

		return $this->msg( $message )->rawParams( Linker::linkKnown( $this->getTitle(), $label, array(), $options ) )->escaped();
	}

	protected function hoursLink( $h, $options = array() ) {
		$options['days'] = ( $h / 24.0 );

		return Linker::linkKnown(
			$this->getTitle(),
			$this->getLanguage()->formatNum( $h ),
			array(),
			$options
		);
	}

	protected function daysLink( $d, $options = array() ) {
		$options['days'] = $d;
		$message = ( $d ? $this->getLanguage()->formatNum( $d ) : $this->msg( 'watchlistall2' )->escaped() );

		return Linker::linkKnown(
			$this->getTitle(),
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
	protected function cutoffLinks( $days, $options = array() ) {
		$hours = array( 1, 2, 6, 12 );
		$days = array( 1, 3, 7 );
		$i = 0;
		foreach( $hours as $h ) {
			$hours[$i++] = $this->hoursLink( $h, $options );
		}
		$i = 0;
		foreach( $days as $d ) {
			$days[$i++] = $this->daysLink( $d, $options );
		}
		return $this->msg( 'wlshowlast' )->rawParams(
			$this->getLanguage()->pipeList( $hours ),
			$this->getLanguage()->pipeList( $days ),
			$this->daysLink( 0, $options ) )->parse();
	}

	/**
	 * Count the number of items on a user's watchlist
	 *
	 * @return Integer
	 */
	protected function countItems() {
		$dbr = wfGetDB( DB_SLAVE, 'watchlist' );

		# Fetch the raw count
		$res = $dbr->select( 'watchlist', 'COUNT(*) AS count',
			array( 'wl_user' => $this->getUser()->getId() ), __METHOD__ );
		$row = $dbr->fetchObject( $res );
		$count = $row->count;

		return floor( $count / 2 );
	}
}
