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
	public function __construct( $page = 'Watchlist', $restriction = 'viewmywatchlist' ) {
		parent::__construct( $page, $restriction );
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
		if ( $user->isAnon() ) {
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

		// Check permissions
		$this->checkPermissions();

		// Add feed links
		$wlToken = $user->getTokenFromOption( 'watchlisttoken' );
		if ( $wlToken ) {
			$this->addFeedLinks( array( 'action' => 'feedwatchlist', 'allrev' => 'allrev',
								'wlowner' => $user->getName(), 'wltoken' => $wlToken ) );
		}

		$this->setHeaders();
		$this->outputHeader();

		$output->addSubtitle( $this->msg( 'watchlistfor2', $user->getName()
			)->rawParams( SpecialEditWatchlist::buildTools( null ) ) );

		$request = $this->getRequest();

		$mode = SpecialEditWatchlist::getMode( $request, $par );
		if ( $mode !== false ) {
			# TODO: localise?
			switch ( $mode ) {
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
			$output->redirect( $title->getLocalURL() );
			return;
		}

		$dbr = wfGetDB( DB_SLAVE, 'watchlist' );

		$nitems = $this->countItems( $dbr );
		if ( $nitems == 0 ) {
			$output->addWikiMsg( 'nowatchlist' );
			return;
		}

		// @todo use FormOptions!
		$defaults = array(
		/* float */ 'days' => floatval( $user->getOption( 'watchlistdays' ) ),
		/* bool  */ 'hideMinor' => (int)$user->getBoolOption( 'watchlisthideminor' ),
		/* bool  */ 'hideBots' => (int)$user->getBoolOption( 'watchlisthidebots' ),
		/* bool  */ 'hideAnons' => (int)$user->getBoolOption( 'watchlisthideanons' ),
		/* bool  */ 'hideLiu' => (int)$user->getBoolOption( 'watchlisthideliu' ),
		/* bool  */ 'hidePatrolled' => (int)$user->getBoolOption( 'watchlisthidepatrolled' ),
		/* bool  */ 'hideOwn' => (int)$user->getBoolOption( 'watchlisthideown' ),
		/* bool  */ 'extended' => (int)$user->getBoolOption( 'extendwatchlist' ),
		/* ?     */ 'namespace' => '', //means all
		/* ?     */ 'invert' => false,
		/* bool  */ 'associated' => false,
		);
		$this->customFilters = array();
		wfRunHooks( 'SpecialWatchlistFilters', array( $this, &$this->customFilters ) );
		foreach ( $this->customFilters as $key => $params ) {
			$defaults[$key] = $params['default'];
		}

		# Extract variables from the request, falling back to user preferences or
		# other default values if these don't exist
		$values = array();
		$values['days'] = floatval( $request->getVal( 'days', $defaults['days'] ) );
		$values['hideMinor'] = (int)$request->getBool( 'hideMinor', $defaults['hideMinor'] );
		$values['hideBots'] = (int)$request->getBool( 'hideBots', $defaults['hideBots'] );
		$values['hideAnons'] = (int)$request->getBool( 'hideAnons', $defaults['hideAnons'] );
		$values['hideLiu'] = (int)$request->getBool( 'hideLiu', $defaults['hideLiu'] );
		$values['hideOwn'] = (int)$request->getBool( 'hideOwn', $defaults['hideOwn'] );
		$values['hidePatrolled'] = (int)$request->getBool( 'hidePatrolled', $defaults['hidePatrolled'] );
		$values['extended'] = (int)$request->getBool( 'extended', $defaults['extended'] );
		foreach ( $this->customFilters as $key => $params ) {
			$values[$key] = (int)$request->getBool( $key, $defaults[$key] );
		}

		# Get namespace value, if supplied, and prepare a WHERE fragment
		$nameSpace = $request->getIntOrNull( 'namespace' );
		$invert = $request->getBool( 'invert' );
		$associated = $request->getBool( 'associated' );
		if ( !is_null( $nameSpace ) ) {
			$eq_op = $invert ? '!=' : '=';
			$bool_op = $invert ? 'AND' : 'OR';
			$nameSpace = intval( $nameSpace ); // paranioa
			if ( !$associated ) {
				$nameSpaceClause = "rc_namespace $eq_op $nameSpace";
			} else {
				$associatedNS = MWNamespace::getAssociated( $nameSpace );
				$nameSpaceClause =
					"rc_namespace $eq_op $nameSpace " .
					$bool_op .
					" rc_namespace $eq_op $associatedNS";
			}
		} else {
			$nameSpace = '';
			$nameSpaceClause = '';
		}
		$values['namespace'] = $nameSpace;
		$values['invert'] = $invert;
		$values['associated'] = $associated;

		// Dump everything here
		$nondefaults = array();
		foreach ( $defaults as $name => $defValue ) {
			wfAppendToArrayIfNotDefault( $name, $values[$name], $defaults, $nondefaults );
		}

		if ( ( $wgEnotifWatchlist || $wgShowUpdatedMarker ) && $request->getVal( 'reset' ) &&
			$request->wasPosted() )
		{
			$user->clearAllNotifications();
			$output->redirect( $this->getTitle()->getFullURL( $nondefaults ) );
			return;
		}

		# Possible where conditions
		$conds = array();

		if ( $values['days'] > 0 ) {
			$conds[] = 'rc_timestamp > ' . $dbr->addQuotes( $dbr->timestamp( time() - intval( $values['days'] * 86400 ) ) );
		}

		# Toggles
		if ( $values['hideOwn'] ) {
			$conds[] = 'rc_user != ' . $user->getId();
		}
		if ( $values['hideBots'] ) {
			$conds[] = 'rc_bot = 0';
		}
		if ( $values['hideMinor'] ) {
			$conds[] = 'rc_minor = 0';
		}
		if ( $values['hideLiu'] ) {
			$conds[] = 'rc_user = 0';
		}
		if ( $values['hideAnons'] ) {
			$conds[] = 'rc_user != 0';
		}
		if ( $user->useRCPatrol() && $values['hidePatrolled'] ) {
			$conds[] = 'rc_patrolled != 1';
		}
		if ( $nameSpaceClause ) {
			$conds[] = $nameSpaceClause;
		}

		# Toggle watchlist content (all recent edits or just the latest)
		if ( $values['extended'] ) {
			$limitWatchlist = $user->getIntOption( 'wllimit' );
			$usePage = false;
		} else {
			# Top log Ids for a page are not stored
			$nonRevisionTypes = array( RC_LOG );
			wfRunHooks( 'SpecialWatchlistGetNonRevisionTypes', array( &$nonRevisionTypes ) );
			if ( $nonRevisionTypes ) {
				if ( count( $nonRevisionTypes ) === 1 ) {
					// if only one use an equality instead of IN condition
					$nonRevisionTypes = reset( $nonRevisionTypes );
				}
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

		# Show a message about slave lag, if applicable
		$lag = wfGetLB()->safeGetLag( $dbr );
		if ( $lag > 0 ) {
			$output->showLagWarning( $lag );
		}

		# Create output
		$form = '';

		# Show watchlist header
		$form .= "<p>";
		$form .= $this->msg( 'watchlist-details' )->numParams( $nitems )->parse() . "\n";
		if ( $wgEnotifWatchlist && $user->getOption( 'enotifwatchlistpages' ) ) {
			$form .= $this->msg( 'wlheader-enotif' )->parse() . "\n";
		}
		if ( $wgShowUpdatedMarker ) {
			$form .= $this->msg( 'wlheader-showupdated' )->parse() . "\n";
		}
		$form .= "</p>";

		if ( $wgShowUpdatedMarker ) {
			$form .= Xml::openElement( 'form', array( 'method' => 'post',
				'action' => $this->getTitle()->getLocalURL(),
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
			'action' => $this->getTitle()->getLocalURL(),
			'id' => 'mw-watchlist-form'
		) );
		$form .= Xml::fieldset(
			$this->msg( 'watchlist-options' )->text(),
			false,
			array( 'id' => 'mw-watchlist-options' )
		);

		$tables = array( 'recentchanges', 'watchlist' );
		$fields = RecentChange::selectFields();
		$join_conds = array(
			'watchlist' => array(
				'INNER JOIN',
				array(
					'wl_user' => $user->getId(),
					'wl_namespace=rc_namespace',
					'wl_title=rc_title'
				),
			),
		);
		$options = array( 'ORDER BY' => 'rc_timestamp DESC' );
		if ( $wgShowUpdatedMarker ) {
			$fields[] = 'wl_notificationtimestamp';
		}
		if ( $limitWatchlist ) {
			$options['LIMIT'] = $limitWatchlist;
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
		} elseif ( !$user->isAllowed( 'suppressrevision' ) ) {
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

		ChangeTags::modifyDisplayQuery( $tables, $fields, $conds, $join_conds, $options, '' );
		wfRunHooks( 'SpecialWatchlistQuery', array( &$conds, &$tables, &$join_conds, &$fields, $values ) );

		$res = $dbr->select( $tables, $fields, $conds, __METHOD__, $options, $join_conds );
		$numRows = $res->numRows();

		/* Start bottom header */

		$lang = $this->getLanguage();
		$wlInfo = '';
		if ( $values['days'] > 0 ) {
			$timestamp = wfTimestampNow();
			$wlInfo = $this->msg( 'wlnote' )->numParams( $numRows, round( $values['days'] * 24 ) )->params(
				$lang->userDate( $timestamp, $user ), $lang->userTime( $timestamp, $user ) )->parse() . "<br />\n";
		}

		$cutofflinks = $this->cutoffLinks( $values['days'], $nondefaults ) . "<br />\n";

		# Spit out some control panel links
		$filters = array(
			'hideMinor' => 'rcshowhideminor',
			'hideBots' => 'rcshowhidebots',
			'hideAnons' => 'rcshowhideanons',
			'hideLiu' => 'rcshowhideliu',
			'hideOwn' => 'rcshowhidemine',
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
		foreach ( $filters as $name => $msg ) {
			$links[] = $this->showHideLink( $nondefaults, $msg, $name, $values[$name] );
		}

		$hiddenFields = $nondefaults;
		unset( $hiddenFields['namespace'] );
		unset( $hiddenFields['invert'] );
		unset( $hiddenFields['associated'] );

		# Namespace filter and put the whole form together.
		$form .= $wlInfo;
		$form .= $cutofflinks;
		$form .= $lang->pipeList( $links ) . "\n";
		$form .= "<hr />\n<p>";
		$form .= Html::namespaceSelector(
			array(
				'selected' => $nameSpace,
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
			$invert,
			array( 'title' => $this->msg( 'tooltip-invert' )->text() )
		) . '&#160;';
		$form .= Xml::checkLabel(
			$this->msg( 'namespace_association' )->text(),
			'associated',
			'associated',
			$associated,
			array( 'title' => $this->msg( 'tooltip-namespace_association' )->text() )
		) . '&#160;';
		$form .= Xml::submitButton( $this->msg( 'allpagessubmit' )->text() ) . "</p>\n";
		foreach ( $hiddenFields as $key => $value ) {
			$form .= Html::hidden( $key, $value ) . "\n";
		}
		$form .= Xml::closeElement( 'fieldset' ) . "\n";
		$form .= Xml::closeElement( 'form' ) . "\n";
		$output->addHTML( $form );

		# If there's nothing to show, stop here
		if ( $numRows == 0 ) {
			$output->wrapWikiMsg(
				"<div class='mw-changeslist-empty'>\n$1\n</div>", 'recentchanges-noresult'
			);
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

			$changeLine = $list->recentChangesLine( $rc, $updated, $counter );
			if ( $changeLine !== false ) {
				$s .= $changeLine;
			}
		}
		$s .= $list->endRecentChangesList();

		$output->addHTML( $s );
	}

	protected function showHideLink( $options, $message, $name, $value ) {
		$label = $this->msg( $value ? 'show' : 'hide' )->escaped();
		$options[$name] = 1 - (int)$value;

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
	 * @param int $days This gets overwritten, so is not used
	 * @param array $options Query parameters for URL
	 * @return string
	 */
	protected function cutoffLinks( $days, $options = array() ) {
		$hours = array( 1, 2, 6, 12 );
		$days = array( 1, 3, 7 );
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
			$this->getLanguage()->pipeList( $days ),
			$this->daysLink( 0, $options ) )->parse();
	}

	/**
	 * Count the number of items on a user's watchlist
	 *
	 * @param DatabaseBase $dbr A database connection
	 * @return Integer
	 */
	protected function countItems( $dbr ) {
		# Fetch the raw count
		$res = $dbr->select( 'watchlist', array( 'count' => 'COUNT(*)' ),
			array( 'wl_user' => $this->getUser()->getId() ), __METHOD__ );
		$row = $dbr->fetchObject( $res );
		$count = $row->count;

		return floor( $count / 2 );
	}

	protected function getGroupName() {
		return 'changes';
	}
}
