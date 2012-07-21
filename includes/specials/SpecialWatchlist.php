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
 * @ingroup SpecialPage
 */

/**
 * A special page that lists last changes made to the wiki,
 * limited to user-defined list of titles.
 *
 * @ingroup SpecialPage
 */
class SpecialWatchlist extends ChangesListSpecialPage {
	public function __construct( $page = 'Watchlist', $restriction = 'viewmywatchlist' ) {
		parent::__construct( $page, $restriction );
	}

	/**
	 * Main execution point
	 *
	 * @param string $subpage
	 */
	function execute( $subpage ) {
		// Anons don't get a watchlist
		$this->requireLogin( 'watchlistanontext' );

		$output = $this->getOutput();
		$request = $this->getRequest();

<<<<<<< HEAD
		$mode = SpecialEditWatchlist::getMode( $request, $subpage );
		if ( $mode !== false ) {
			if ( $mode === SpecialEditWatchlist::EDIT_RAW ) {
				$title = SpecialPage::getTitleFor( 'EditWatchlist', 'raw' );
			} elseif ( $mode === SpecialEditWatchlist::EDIT_CLEAR ) {
				$title = SpecialPage::getTitleFor( 'EditWatchlist', 'clear' );
			} else {
				$title = SpecialPage::getTitleFor( 'EditWatchlist' );
			}
=======
		# Anons don't get a watchlist - but let them through for public watchlists
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
>>>>>>> 4e55649... Watchlist grouping

			$output->redirect( $title->getLocalURL() );

			return;
		}

		$this->checkPermissions();

		$user = $this->getUser();
		$opts = $this->getOptions();

		$config = $this->getConfig();
		if ( ( $config->get( 'EnotifWatchlist' ) || $config->get( 'ShowUpdatedMarker' ) )
			&& $request->getVal( 'reset' )
			&& $request->wasPosted()
		) {
			$user->clearAllNotifications();
			$output->redirect( $this->getPageTitle()->getFullURL( $opts->getChangedValues() ) );

			return;
		}

		parent::execute( $subpage );
	}

<<<<<<< HEAD
	/**
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit = 10 ) {
		// See also SpecialEditWatchlist::prefixSearchSubpages
		return self::prefixSearchArray(
			$search,
			$limit,
			array(
				'clear',
				'edit',
				'raw',
			)
=======
		// @TODO: use FormOptions!
		$defaults = array(
		/* float */ 'days'      => floatval( $user->getOption( 'watchlistdays' ) ), /* 3.0 or 0.5, watch further below */
		/* bool  */ 'hideMinor' => (int)$user->getBoolOption( 'watchlisthideminor' ),
		/* bool  */ 'hideBots'  => (int)$user->getBoolOption( 'watchlisthidebots' ),
		/* bool  */ 'hideAnons' => (int)$user->getBoolOption( 'watchlisthideanons' ),
		/* bool  */ 'hideLiu'   => (int)$user->getBoolOption( 'watchlisthideliu' ),
		/* bool  */ 'hidePatrolled' => (int)$user->getBoolOption( 'watchlisthidepatrolled' ),
		/* bool  */ 'hideOwn'   => (int)$user->getBoolOption( 'watchlisthideown' ),
		/* bool  */ 'extended'   => (int)$user->getBoolOption( 'extendwatchlist' ),
		/* ?     */ 'namespace' => '', //means all
		/* ?     */ 'invert'    => false,
		/* bool  */ 'associated' => false,
		/* int    */ 'user_id'          => $user->getId(),
		/* string */ 'user'     => $user->getName(),
		/* int    */ 'group'         => null,
		/* string */ 'group_name'    => null,
>>>>>>> 4e55649... Watchlist grouping
		);
	}

<<<<<<< HEAD
	/**
	 * Get a FormOptions object containing the default options
	 *
	 * @return FormOptions
	 */
	public function getDefaultOptions() {
		$opts = parent::getDefaultOptions();
		$user = $this->getUser();
=======
		# Extract variables from the request, falling back to user preferences or
		# other default values if these don't exist
		$values = array();
		$values['user'] = $request->getText( 'user', $defaults['user'] );
		if( empty( $values['user'] ) ) {
			$values['user'] = $user->getName();
		}

		$values['group_name'] = $request->getText( 'group', $defaults['group'] );

		// Look for subpage parameters passed in the URL
		$subpages = explode( '/', $par );

		if( !empty( $subpages[0] ) ) {
			$values['user'] = $subpages[0];
		}
		if( isset( $subpages[1] ) && !empty( $subpages[1] ) ) {
			$values['group_name'] = $subpages[1];
		}

		$user_obj = User::newFromName( $values['user'] );
		$wg_obj = WatchlistGroup::newFromUser( $user_obj );
		$values['user_id'] = $user_obj->getId();
		$values['group'] = $wg_obj->getGroupFromName( $values['group_name'] );
		// for non existing group set the name to default
		if ( !$values['group'] ) {
			$values['group_name'] = $defaults['group'];
		}
		$values['days']      	 = $request->getVal( 'days', $prefs['days'] );
		$values['hideMinor'] 	 = (int)$request->getBool( 'hideMinor', $prefs['hideminor'] );
		$values['hideBots']  	 = (int)$request->getBool( 'hideBots' , $prefs['hidebots'] );
		$values['hideAnons'] 	 = (int)$request->getBool( 'hideAnons', $prefs['hideanons'] );
		$values['hideLiu']   	 = (int)$request->getBool( 'hideLiu'  , $prefs['hideliu'] );
		$values['hideOwn']   	 = (int)$request->getBool( 'hideOwn'  , $prefs['hideown'] );
		$values['hidePatrolled'] = (int)$request->getBool( 'hidePatrolled', $prefs['hidepatrolled'] );
		$values['extended'] = (int)$request->getBool( 'extended', $defaults['extended'] );

		foreach( $this->customFilters as $key => $params ) {
			$values[$key] = (int)$request->getBool( $key );
		}
>>>>>>> 4e55649... Watchlist grouping

		$opts->add( 'days', $user->getOption( 'watchlistdays' ), FormOptions::FLOAT );

		$opts->add( 'hideminor', $user->getBoolOption( 'watchlisthideminor' ) );
		$opts->add( 'hidebots', $user->getBoolOption( 'watchlisthidebots' ) );
		$opts->add( 'hideanons', $user->getBoolOption( 'watchlisthideanons' ) );
		$opts->add( 'hideliu', $user->getBoolOption( 'watchlisthideliu' ) );
		$opts->add( 'hidepatrolled', $user->getBoolOption( 'watchlisthidepatrolled' ) );
		$opts->add( 'hidemyself', $user->getBoolOption( 'watchlisthideown' ) );

		$opts->add( 'extended', $user->getBoolOption( 'extendwatchlist' ) );

		return $opts;
	}

<<<<<<< HEAD
	/**
	 * Get custom show/hide filters
	 *
	 * @return array Map of filter URL param names to properties (msg/default)
	 */
	protected function getCustomFilters() {
		if ( $this->customFilters === null ) {
			$this->customFilters = parent::getCustomFilters();
			wfRunHooks( 'SpecialWatchlistFilters', array( $this, &$this->customFilters ), '1.23' );
=======
		// Backup conditions to restrict access
		$conds[] = 'wl_group = 0 OR wg_perm = 1 OR (wg_perm = 0 AND wg_user = ' . $user->getId() . ')';
		if( intval( $values['group'] ) > 0 || $values['group'] === 0 ) {
			$conds[] = 'wl_group = '. intval( $values['group'] );
		}

		if( $values['days'] > 0 ) {
			$conds[] = 'rc_timestamp > ' . $dbr->addQuotes( $dbr->timestamp( time() - intval( $values['days'] * 86400 ) ) );
>>>>>>> 4e55649... Watchlist grouping
		}

		return $this->customFilters;
	}

	/**
	 * Fetch values for a FormOptions object from the WebRequest associated with this instance.
	 *
	 * Maps old pre-1.23 request parameters Watchlist used to use (different from Recentchanges' ones)
	 * to the current ones.
	 *
	 * @param FormOptions $opts
	 * @return FormOptions
	 */
	protected function fetchOptionsFromRequest( $opts ) {
		static $compatibilityMap = array(
			'hideMinor' => 'hideminor',
			'hideBots' => 'hidebots',
			'hideAnons' => 'hideanons',
			'hideLiu' => 'hideliu',
			'hidePatrolled' => 'hidepatrolled',
			'hideOwn' => 'hidemyself',
		);

		$params = $this->getRequest()->getValues();
		foreach ( $compatibilityMap as $from => $to ) {
			if ( isset( $params[$from] ) ) {
				$params[$to] = $params[$from];
				unset( $params[$from] );
			}
		}

		// Not the prettiest way to achieve this… FormOptions internally depends on data sanitization
		// methods defined on WebRequest and removing this dependency would cause some code duplication.
		$request = new DerivativeRequest( $this->getRequest(), $params );
		$opts->fetchValuesFromRequest( $request );

		return $opts;
	}

	/**
	 * Return an array of conditions depending of options set in $opts
	 *
	 * @param FormOptions $opts
	 * @return array
	 */
	public function buildMainQueryConds( FormOptions $opts ) {
		$dbr = $this->getDB();
		$conds = parent::buildMainQueryConds( $opts );

		// Calculate cutoff
		if ( $opts['days'] > 0 ) {
			$conds[] = 'rc_timestamp > ' .
				$dbr->addQuotes( $dbr->timestamp( time() - intval( $opts['days'] * 86400 ) ) );
		}

		return $conds;
	}

	/**
	 * Process the query
	 *
	 * @param array $conds
	 * @param FormOptions $opts
	 * @return bool|ResultWrapper Result or false (for Recentchangeslinked only)
	 */
	public function doMainQuery( $conds, $opts ) {
		$dbr = $this->getDB();
		$user = $this->getUser();

		# Toggle watchlist content (all recent edits or just the latest)
		if ( $opts['extended'] ) {
			$limitWatchlist = $user->getIntOption( 'wllimit' );
			$usePage = false;
		} else {
			# Top log Ids for a page are not stored
			$nonRevisionTypes = array( RC_LOG );
			wfRunHooks( 'SpecialWatchlistGetNonRevisionTypes', array( &$nonRevisionTypes ) );
			if ( $nonRevisionTypes ) {
				$conds[] = $dbr->makeList(
					array(
						'rc_this_oldid=page_latest',
						'rc_type' => $nonRevisionTypes,
					),
					LIST_OR
				);
			}
			$limitWatchlist = 0;
			$usePage = true;
		}

<<<<<<< HEAD
		$tables = array( 'recentchanges', 'watchlist' );
		$fields = RecentChange::selectFields();
		$query_options = array( 'ORDER BY' => 'rc_timestamp DESC' );
=======
		# Show a message about slave lag, if applicable
		$lag = wfGetLB()->safeGetLag( $dbr );
		if( $lag > 0 ) {
			$output->showLagWarning( $lag );
		}

		# ADD USER WATCHLIST/GROUP SELECTION
		$fields['user'] = array(
			'type' => 'text',
			'label' => $this->msg( 'watchlist-user' )->escaped(),
			'value' => $values['user_id']
		);
		$fields['group'] = array(
			'type' => 'text',
			'label' => $this->msg( 'watchlist-group' )->escaped(),
			'value' => $values['group']
		);

		# Create output form
		$form = Xml::fieldset( $this->msg( 'watchlist-options' )->text(), false, array( 'id' => 'mw-watchlist-options' ) );

		# Show watchlist header
		$form .= $this->msg( 'watchlist-details' )->numParams( $nitems )->parse() . "\n";

		if( $user->getOption( 'enotifwatchlistpages' ) && $wgEnotifWatchlist) {
			$form .= $this->msg( 'wlheader-enotif' )->parseAsBlock() . "\n";
		}
		if( $wgShowUpdatedMarker ) {
			$form .= Xml::openElement( 'form', array( 'method' => 'post',
						'action' => $this->getTitle()->getLocalUrl(),
						'id' => 'mw-watchlist-resetbutton' ) ) . "\n" .
					$this->msg( 'wlheader-showupdated' )->parse() .
					Xml::submitButton( $this->msg( 'enotif_reset' )->text(), array( 'name' => 'dummy' ) ) . "\n" .
					Html::hidden( 'reset', 'all' ) . "\n";
					foreach ( $nondefaults as $key => $value ) {
						$form .= Html::hidden( $key, $value ) . "\n";
					}
					$form .= Xml::closeElement( 'form' ) . "\n";
		}
		$form .= "<hr />\n";

		$tables = array( 'recentchanges', 'watchlist', 'watchlist_groups' );
		$fields = array_merge( RecentChange::selectFields(), array( $dbr->tableName( 'watchlist_groups' ) . '.*' ) );

>>>>>>> 4e55649... Watchlist grouping
		$join_conds = array(
			'watchlist' => array(
				'INNER JOIN',
				array(
					'wl_user' => $values['user_id'],
					'wl_namespace=rc_namespace',
					'wl_title=rc_title'
				),
			),
			'watchlist_groups' => array(
				'LEFT JOIN',
				array(
					'wg_id=wl_group'
				),
			)
		);

<<<<<<< HEAD
		if ( $this->getConfig()->get( 'ShowUpdatedMarker' ) ) {
=======
		$options = array( 'ORDER BY' => 'rc_timestamp DESC' );
		if( $wgShowUpdatedMarker ) {
>>>>>>> 4e55649... Watchlist grouping
			$fields[] = 'wl_notificationtimestamp';
		}
		if ( $limitWatchlist ) {
			$query_options['LIMIT'] = $limitWatchlist;
		}

		$rollbacker = $user->isAllowed( 'rollback' );
		if ( $usePage || $rollbacker ) {
			$tables[] = 'page';
			$join_conds['page'] = array( 'LEFT JOIN', 'rc_cur_id=page_id' );
			if ( $rollbacker ) {
				$fields[] = 'page_latest';
			}
		}

		// Log entries with DELETED_ACTION must not show up unless the user has
		// the necessary rights.
		if ( !$user->isAllowed( 'deletedhistory' ) ) {
			$bitmask = LogPage::DELETED_ACTION;
		} elseif ( !$user->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
			$bitmask = LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED;
		} else {
			$bitmask = 0;
		}
		if ( $bitmask ) {
			$conds[] = $dbr->makeList( array(
				'rc_type != ' . RC_LOG,
				$dbr->bitAnd( 'rc_deleted', $bitmask ) . " != $bitmask",
			), LIST_OR );
		}

		ChangeTags::modifyDisplayQuery(
			$tables,
			$fields,
			$conds,
			$join_conds,
			$query_options,
			''
		);

		$this->runMainQueryHook( $tables, $fields, $conds, $query_options, $join_conds, $opts );

		return $dbr->select(
			$tables,
			$fields,
			$conds,
			__METHOD__,
			$query_options,
			$join_conds
		);
	}

	protected function runMainQueryHook( &$tables, &$fields, &$conds, &$query_options,
		&$join_conds, $opts
	) {
		return parent::runMainQueryHook( $tables, $fields, $conds, $query_options, $join_conds, $opts )
			&& wfRunHooks(
				'SpecialWatchlistQuery',
				array( &$conds, &$tables, &$join_conds, &$fields, $opts ),
				'1.23'
			);
	}

	/**
	 * Return a DatabaseBase object for reading
	 *
	 * @return DatabaseBase
	 */
	protected function getDB() {
		return wfGetDB( DB_SLAVE, 'watchlist' );
	}

	/**
	 * Output feed links.
	 */
	public function outputFeedLinks() {
		$user = $this->getUser();
		$wlToken = $user->getTokenFromOption( 'watchlisttoken' );
		if ( $wlToken ) {
			$this->addFeedLinks( array(
				'action' => 'feedwatchlist',
				'allrev' => 1,
				'wlowner' => $user->getName(),
				'wltoken' => $wlToken,
			) );
		}
	}

	/**
	 * Build and output the actual changes list.
	 *
	 * @param ResultWrapper $rows Database rows
	 * @param FormOptions $opts
	 */
	public function outputChangesList( $rows, $opts ) {
		$dbr = $this->getDB();
		$user = $this->getUser();
		$output = $this->getOutput();

		# Show a message about slave lag, if applicable
		$lag = wfGetLB()->safeGetLag( $dbr );
		if ( $lag > 0 ) {
			$output->showLagWarning( $lag );
		}

		# If no rows to display, show message before try to render the list
		if ( $rows->numRows() == 0 ) {
			$output->wrapWikiMsg(
				"<div class='mw-changeslist-empty'>\n$1\n</div>", 'recentchanges-noresult'
			);
			return;
		}

		$dbr->dataSeek( $rows, 0 );

		$list = ChangesList::newFromContext( $this->getContext() );
		$list->setWatchlistDivs();
		$list->initChangesListRows( $rows );
		$dbr->dataSeek( $rows, 0 );

		$s = $list->beginRecentChangesList();
		$counter = 1;
		foreach ( $rows as $obj ) {
			# Make RC entry
			$rc = RecentChange::newFromRow( $obj );
			$rc->counter = $counter++;

			if ( $this->getConfig()->get( 'ShowUpdatedMarker' ) ) {
				$updated = $obj->wl_notificationtimestamp;
			} else {
				$updated = false;
			}

			if ( $this->getConfig()->get( 'RCShowWatchingUsers' )
				&& $user->getOption( 'shownumberswatching' )
			) {
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

			$changeLine = $list->recentChangesLine( $rc, $updated, $counter );
			if ( $changeLine !== false ) {
				$s .= $changeLine;
			}
		}
		$s .= $list->endRecentChangesList();

		$output->addHTML( $s );
	}

	/**
	 * Set the text to be displayed above the changes
	 *
	 * @param FormOptions $opts
	 * @param int $numRows Number of rows in the result to show after this header
	 */
	public function doHeader( $opts, $numRows ) {
		$user = $this->getUser();

		$this->getOutput()->addSubtitle(
			$this->msg( 'watchlistfor2', $user->getName() )
				->rawParams( SpecialEditWatchlist::buildTools( null ) )
		);

		$this->setTopText( $opts );

		$lang = $this->getLanguage();
		$wlInfo = '';
		if ( $opts['days'] > 0 ) {
			$timestamp = wfTimestampNow();
			$wlInfo = $this->msg( 'wlnote' )->numParams( $numRows, round( $opts['days'] * 24 ) )->params(
				$lang->userDate( $timestamp, $user ), $lang->userTime( $timestamp, $user )
			)->parse() . "<br />\n";
		}

		$nondefaults = $opts->getChangedValues();
		$cutofflinks = $this->cutoffLinks( $opts['days'], $nondefaults ) . "<br />\n";

		# Spit out some control panel links
		$filters = array(
			'hideminor' => 'rcshowhideminor',
			'hidebots' => 'rcshowhidebots',
			'hideanons' => 'rcshowhideanons',
			'hideliu' => 'rcshowhideliu',
			'hidemyself' => 'rcshowhidemine',
			'hidepatrolled' => 'rcshowhidepatr'
		);
		foreach ( $this->getCustomFilters() as $key => $params ) {
			$filters[$key] = $params['msg'];
		}
		// Disable some if needed
		if ( !$user->useNPPatrol() ) {
			unset( $filters['hidepatrolled'] );
		}

		$links = array();
		foreach ( $filters as $name => $msg ) {
			$links[] = $this->showHideLink( $nondefaults, $msg, $name, $opts[$name] );
		}

		$hiddenFields = $nondefaults;
		unset( $hiddenFields['namespace'] );
		unset( $hiddenFields['invert'] );
		unset( $hiddenFields['associated'] );

		# Create output
		$form = '';

		# Namespace filter and put the whole form together.
		$form .= $wlInfo;
		$form .= $cutofflinks;
		$form .= $lang->pipeList( $links ) . "\n";
		$form .= "<hr />\n<p>";
		$form .= Html::namespaceSelector(
			array(
				'selected' => $opts['namespace'],
				'all' => '',
				'label' => $this->msg( 'namespace' )->text()
			), array(
				'name' => 'namespace',
				'id' => 'namespace',
				'class' => 'namespaceselector',
			)
		) . '&#160;';
		$form .= Xml::checkLabel(
			$this->msg( 'invert' )->text(),
			'invert',
			'nsinvert',
			$opts['invert'],
			array( 'title' => $this->msg( 'tooltip-invert' )->text() )
		) . '&#160;';
		$form .= Xml::checkLabel(
			$this->msg( 'namespace_association' )->text(),
			'associated',
			'nsassociated',
			$opts['associated'],
			array( 'title' => $this->msg( 'tooltip-namespace_association' )->text() )
		) . '&#160;';

		$form .= '</p><p>';
		$form .= Xml::openElement( 'label', array( 'for' => 'user_search' ) ) . $this->msg( 'watchlist-user' )->escaped() . Xml::closeElement( 'label' );
		$form .= Html::input( 'user', $values['user'], 'text', array( 'id' => 'user_search' ) ) . '&#160;';
		$form .= Xml::openElement( 'label', array( 'for' => 'group_search' ) ) . $this->msg( 'watchlist-group' )->escaped() . Xml::closeElement( 'label' );
		$form .= Html::input( 'group', $values['group_name'], 'text', array( 'id' => 'group_search' ) ) . '&#160;';

		$form .= Xml::submitButton( $this->msg( 'allpagessubmit' )->text() ) . "</p>\n";
		$form .= Html::hidden( 'days', $values['days'] ) . "\n";
		foreach ( $filters as $key => $msg ) {
			if ( $values[$key] ) {
				$form .= Html::hidden( $key, 1 ) . "\n";
			}
		}
		$form .= Xml::closeElement( 'fieldset' ) . "\n";
<<<<<<< HEAD
		$form .= Xml::closeElement( 'form' ) . "\n";
		$this->getOutput()->addHTML( $form );
=======

		$output->addHTML( $form );

		if( $values['user_id'] == 0 ) {
			$output->addWikiMsg( 'wlfilter-nouser' );
			return;
		} else {
			// Check permissions
			$hasPerm = ( $values['user_id'] == $user->getId() ) // if the user is self
			          || $wg_obj->isGroup( $values['group'], true );
			// If the user doesn't have permission or there's nothing to show, stop here
			if( !$hasPerm ) {
				$output->addWikiMsg( 'wlfilter-permdenied' );
				return;
			}
			// No changes for the given criteria
			if( $numRows == 0 ) {
				$output->addWikiMsg( 'watchnochange' );
				return;
			}
		}

		// The filter message might be repetitive since this info is already available in the filter form fields.
		/*$filter_status = '<p>';
		if( isset( $values['user'] ) ) {
			$filter_status .= $this->msg( 'wlfilter' )->rawParams( $values['user'] )->escaped();
		}
		if( isset( $values['group_name'] ) ) {
		    $filter_status .= ' ' . $this->msg( 'wlfilter-group' )->rawParams( $values['group_name'] )->escaped();
		}
		$filter_status .= '</p>';
		$output->addHTML( $filter_status );*/
		/* End bottom header */

		/* Do link batch query */
		$linkBatch = new LinkBatch;
		foreach ( $res as $row ) {
			$userNameUnderscored = str_replace( ' ', '_', $row->rc_user_text );
			if ( $row->rc_user != 0 ) {
				$linkBatch->add( NS_USER, $userNameUnderscored );
			}
			$linkBatch->add( NS_USER_TALK, $userNameUnderscored );
>>>>>>> 4e55649... Watchlist grouping

		$this->setBottomText( $opts );
	}

	function setTopText( FormOptions $opts ) {
		$nondefaults = $opts->getChangedValues();
		$form = "";
		$user = $this->getUser();

		$dbr = $this->getDB();
		$numItems = $this->countItems( $dbr );
		$showUpdatedMarker = $this->getConfig()->get( 'ShowUpdatedMarker' );

		// Show watchlist header
		$form .= "<p>";
		if ( $numItems == 0 ) {
			$form .= $this->msg( 'nowatchlist' )->parse() . "\n";
		} else {
			$form .= $this->msg( 'watchlist-details' )->numParams( $numItems )->parse() . "\n";
			if ( $this->getConfig()->get( 'EnotifWatchlist' )
				&& $user->getOption( 'enotifwatchlistpages' )
			) {
				$form .= $this->msg( 'wlheader-enotif' )->parse() . "\n";
			}
			if ( $showUpdatedMarker ) {
				$form .= $this->msg( 'wlheader-showupdated' )->parse() . "\n";
			}
		}
		$form .= "</p>";

		if ( $numItems > 0 && $showUpdatedMarker ) {
			$form .= Xml::openElement( 'form', array( 'method' => 'post',
				'action' => $this->getPageTitle()->getLocalURL(),
				'id' => 'mw-watchlist-resetbutton' ) ) . "\n" .
			Xml::submitButton( $this->msg( 'enotif_reset' )->text(), array( 'name' => 'dummy' ) ) . "\n" .
			Html::hidden( 'reset', 'all' ) . "\n";
			foreach ( $nondefaults as $key => $value ) {
				$form .= Html::hidden( $key, $value ) . "\n";
			}
			$form .= Xml::closeElement( 'form' ) . "\n";
		}

		$form .= Xml::openElement( 'form', array(
			'method' => 'post',
			'action' => $this->getPageTitle()->getLocalURL(),
			'id' => 'mw-watchlist-form'
		) );
		$form .= Xml::fieldset(
			$this->msg( 'watchlist-options' )->text(),
			false,
			array( 'id' => 'mw-watchlist-options' )
		);

		$form .= SpecialRecentChanges::makeLegend( $this->getContext() );

		$this->getOutput()->addHTML( $form );
	}

	protected function showHideLink( $options, $message, $name, $value ) {
		$label = $this->msg( $value ? 'show' : 'hide' )->escaped();
		$options[$name] = 1 - (int)$value;

		return $this->msg( $message )
			->rawParams( Linker::linkKnown( $this->getPageTitle(), $label, array(), $options ) )
			->escaped();
	}

	protected function hoursLink( $h, $options = array() ) {
		$options['days'] = ( $h / 24.0 );

		return Linker::linkKnown(
			$this->getPageTitle(),
			$this->getLanguage()->formatNum( $h ),
			array(),
			$options
		);
	}

	protected function daysLink( $d, $options = array() ) {
		$options['days'] = $d;

		return Linker::linkKnown(
			$this->getPageTitle(),
			$this->getLanguage()->formatNum( $d ),
			array(),
			$options
		);
	}

	/**
	 * Returns html
	 *
	 * @param int $days This gets overwritten, so is not used
	 * @param array $options Query parameters for URL
	 * @return string
	 */
	protected function cutoffLinks( $days, $options = array() ) {
		global $wgRCMaxAge;
		$watchlistMaxDays = ceil( $wgRCMaxAge / ( 3600 * 24 ) );

		$hours = array( 1, 2, 6, 12 );
		$days = array( 1, 3, 7, $watchlistMaxDays );
		$i = 0;
		foreach ( $hours as $h ) {
			$hours[$i++] = $this->hoursLink( $h, $options );
		}
		$i = 0;
		foreach ( $days as $d ) {
			$days[$i++] = $this->daysLink( $d, $options );
		}

		return $this->msg( 'wlshowlast' )->rawParams(
			$this->getLanguage()->pipeList( $hours ),
			$this->getLanguage()->pipeList( $days ) )->parse();
	}

	/**
	 * Count the number of items on a user's watchlist
	 *
	 * @param DatabaseBase $dbr A database connection
	 * @return int
	 */
	protected function countItems( $dbr ) {
		# Fetch the raw count
		$rows = $dbr->select( 'watchlist', array( 'count' => 'COUNT(*)' ),
			array( 'wl_user' => $this->getUser()->getId() ), __METHOD__ );
		$row = $dbr->fetchObject( $rows );
		$count = $row->count;

		return floor( $count / 2 );
	}
}
