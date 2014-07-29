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
		global $wgEnotifWatchlist, $wgShowUpdatedMarker;

		// Anons don't get a watchlist
		$this->requireLogin( 'watchlistanontext' );

		$output = $this->getOutput();
		$request = $this->getRequest();

		$mode = SpecialEditWatchlist::getMode( $request, $subpage );
		if ( $mode !== false ) {
			if ( $mode === SpecialEditWatchlist::EDIT_RAW ) {
				$title = SpecialPage::getTitleFor( 'EditWatchlist', 'raw' );
			} elseif ( $mode === SpecialEditWatchlist::EDIT_CLEAR ) {
                                $title = SpecialPage::getTitleFor( 'EditWatchlist', 'clear' );
			} else {
				$title = SpecialPage::getTitleFor( 'EditWatchlist' );
			}

			$output->redirect( $title->getLocalURL() );

			return;
		}

		$this->checkPermissions();

		$user = $this->getUser();
		$opts = $this->getOptions();

		if ( ( $wgEnotifWatchlist || $wgShowUpdatedMarker )
			&& $request->getVal( 'reset' )
			&& $request->wasPosted()
		) {
			$user->clearAllNotifications();
			$output->redirect( $this->getPageTitle()->getFullURL( $opts->getChangedValues() ) );

			return;
		}

		parent::execute( $subpage );
	}

	/**
	 * Get a FormOptions object containing the default options
	 *
	 * @return FormOptions
	 */
	public function getDefaultOptions() {
		$opts = parent::getDefaultOptions();
		$user = $this->getUser();

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

	/**
	 * Get custom show/hide filters
	 *
	 * @return array Map of filter URL param names to properties (msg/default)
	 */
	protected function getCustomFilters() {
		if ( $this->customFilters === null ) {
			$this->customFilters = parent::getCustomFilters();
			wfRunHooks( 'SpecialWatchlistFilters', array( $this, &$this->customFilters ), '1.23' );
		}

		return $this->customFilters;
	}

	/**
	 * Fetch values for a FormOptions object from the WebRequest associated with this instance.
	 *
	 * Maps old pre-1.23 request parameters Watchlist used to use (different from Recentchanges' ones)
	 * to the current ones.
	 *
	 * @param FormOptions $parameters
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
		global $wgShowUpdatedMarker;

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

		$tables = array( 'recentchanges', 'watchlist' );
		$fields = RecentChange::selectFields();
		$query_options = array( 'ORDER BY' => 'rc_timestamp DESC' );
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

		if ( $wgShowUpdatedMarker ) {
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

		ChangeTags::modifyDisplayQuery(
			$tables,
			$fields,
			$conds,
			$join_conds,
			$query_options,
			''
		);

		wfRunHooks( 'SpecialWatchlistQuery',
			array( &$conds, &$tables, &$join_conds, &$fields, $opts ),
			'1.23' );

		return $dbr->select(
			$tables,
			$fields,
			$conds,
			__METHOD__,
			$query_options,
			$join_conds
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
		global $wgShowUpdatedMarker, $wgRCShowWatchingUsers;

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
		$form .= Xml::submitButton( $this->msg( 'allpagessubmit' )->text() ) . "</p>\n";
		foreach ( $hiddenFields as $key => $value ) {
			$form .= Html::hidden( $key, $value ) . "\n";
		}
		$form .= Xml::closeElement( 'fieldset' ) . "\n";
		$form .= Xml::closeElement( 'form' ) . "\n";
		$this->getOutput()->addHTML( $form );

		$this->setBottomText( $opts );
	}

	function setTopText( FormOptions $opts ) {
		global $wgEnotifWatchlist, $wgShowUpdatedMarker;

		$nondefaults = $opts->getChangedValues();
		$form = "";
		$user = $this->getUser();

		$dbr = $this->getDB();
		$numItems = $this->countItems( $dbr );

		// Show watchlist header
		$form .= "<p>";
		if ( $numItems == 0 ) {
			$form .= $this->msg( 'nowatchlist' )->parse() . "\n";
		} else {
			$form .= $this->msg( 'watchlist-details' )->numParams( $numItems )->parse() . "\n";
			if ( $wgEnotifWatchlist && $user->getOption( 'enotifwatchlistpages' ) ) {
				$form .= $this->msg( 'wlheader-enotif' )->parse() . "\n";
			}
			if ( $wgShowUpdatedMarker ) {
				$form .= $this->msg( 'wlheader-showupdated' )->parse() . "\n";
			}
		}
		$form .= "</p>";

		if ( $numItems > 0 && $wgShowUpdatedMarker ) {
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
		$message = $d ? $this->getLanguage()->formatNum( $d )
			: $this->msg( 'watchlistall2' )->escaped();

		return Linker::linkKnown(
			$this->getPageTitle(),
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
		$rows = $dbr->select( 'watchlist', array( 'count' => 'COUNT(*)' ),
			array( 'wl_user' => $this->getUser()->getId() ), __METHOD__ );
		$row = $dbr->fetchObject( $rows );
		$count = $row->count;

		return floor( $count / 2 );
	}
}
