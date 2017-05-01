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

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\ResultWrapper;
use Wikimedia\Rdbms\IDatabase;

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

	public function doesWrites() {
		return true;
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
		$this->addHelpLink( 'Help:Watching pages' );
		$output->addModules( [
			'mediawiki.special.changeslist.visitedstatus',
			'mediawiki.special.watchlist',
		] );

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

		$config = $this->getConfig();
		if ( ( $config->get( 'EnotifWatchlist' ) || $config->get( 'ShowUpdatedMarker' ) )
			&& $request->getVal( 'reset' )
			&& $request->wasPosted()
			&& $user->matchEditToken( $request->getVal( 'token' ) )
		) {
			$user->clearAllNotifications();
			$output->redirect( $this->getPageTitle()->getFullURL( $opts->getChangedValues() ) );

			return;
		}

		parent::execute( $subpage );
	}

	/**
	 * Return an array of subpages that this special page will accept.
	 *
	 * @see also SpecialEditWatchlist::getSubpagesForPrefixSearch
	 * @return string[] subpages
	 */
	public function getSubpagesForPrefixSearch() {
		return [
			'clear',
			'edit',
			'raw',
		];
	}

	/**
	 * @inheritdoc
	 */
	protected function transformFilterDefinition( array $filterDefinition ) {
		if ( isset( $filterDefinition['showHideSuffix'] ) ) {
			$filterDefinition['showHide'] = 'wl' . $filterDefinition['showHideSuffix'];
		}

		return $filterDefinition;
	}

	/**
	 * @inheritdoc
	 */
	protected function registerFilters() {
		parent::registerFilters();

		$user = $this->getUser();

		$significance = $this->getFilterGroup( 'significance' );
		$hideMinor = $significance->getFilter( 'hideminor' );
		$hideMinor->setDefault( $user->getBoolOption( 'watchlisthideminor' ) );

		$automated = $this->getFilterGroup( 'automated' );
		$hideBots = $automated->getFilter( 'hidebots' );
		$hideBots->setDefault( $user->getBoolOption( 'watchlisthidebots' ) );

		$registration = $this->getFilterGroup( 'registration' );
		$hideAnons = $registration->getFilter( 'hideanons' );
		$hideAnons->setDefault( $user->getBoolOption( 'watchlisthideanons' ) );
		$hideLiu = $registration->getFilter( 'hideliu' );
		$hideLiu->setDefault( $user->getBoolOption( 'watchlisthideliu' ) );

		$reviewStatus = $this->getFilterGroup( 'reviewStatus' );
		if ( $reviewStatus !== null ) {
			// Conditional on feature being available and rights
			$hidePatrolled = $reviewStatus->getFilter( 'hidepatrolled' );
			$hidePatrolled->setDefault( $user->getBoolOption( 'watchlisthidepatrolled' ) );
		}

		$authorship = $this->getFilterGroup( 'authorship' );
		$hideMyself = $authorship->getFilter( 'hidemyself' );
		$hideMyself->setDefault( $user->getBoolOption( 'watchlisthideown' ) );

		$changeType = $this->getFilterGroup( 'changeType' );
		$hideCategorization = $changeType->getFilter( 'hidecategorization' );
		if ( $hideCategorization !== null ) {
			// Conditional on feature being available
			$hideCategorization->setDefault( $user->getBoolOption( 'watchlisthidecategorization' ) );
		}
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
		$opts->add( 'extended', $user->getBoolOption( 'extendwatchlist' ) );

		return $opts;
	}

	/**
	 * Get all custom filters
	 *
	 * @return array Map of filter URL param names to properties (msg/default)
	 */
	protected function getCustomFilters() {
		if ( $this->customFilters === null ) {
			$this->customFilters = parent::getCustomFilters();
			Hooks::run( 'SpecialWatchlistFilters', [ $this, &$this->customFilters ], '1.23' );
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
		static $compatibilityMap = [
			'hideMinor' => 'hideminor',
			'hideBots' => 'hidebots',
			'hideAnons' => 'hideanons',
			'hideLiu' => 'hideliu',
			'hidePatrolled' => 'hidepatrolled',
			'hideOwn' => 'hidemyself',
		];

		$params = $this->getRequest()->getValues();
		foreach ( $compatibilityMap as $from => $to ) {
			if ( isset( $params[$from] ) ) {
				$params[$to] = $params[$from];
				unset( $params[$from] );
			}
		}

		if ( $this->getRequest()->getVal( 'action' ) == 'submit' ) {
			$allBooleansFalse = [];

			// If the user submitted the form, start with a baseline of "all
			// booleans are false", then change the ones they checked.  This
			// means we ignore the defaults.

			// This is how we handle the fact that HTML forms don't submit
			// unchecked boxes.
			foreach ( $this->filterGroups as $filterGroup ) {
				if ( $filterGroup instanceof ChangesListBooleanFilterGroup ) {
					foreach ( $filterGroup->getFilters() as $filter ) {
						$allBooleansFalse[$filter->getName()] = false;
					}
				}
			}

			$params = $params + $allBooleansFalse;
		}

		// Not the prettiest way to achieve this… FormOptions internally depends on data sanitization
		// methods defined on WebRequest and removing this dependency would cause some code duplication.
		$request = new DerivativeRequest( $this->getRequest(), $params );
		$opts->fetchValuesFromRequest( $request );

		return $opts;
	}

	/**
	 * @inheritdoc
	 */
	protected function buildQuery( &$tables, &$fields, &$conds, &$query_options,
		&$join_conds, FormOptions $opts ) {

		$dbr = $this->getDB();
		parent::buildQuery( $tables, $fields, $conds, $query_options, $join_conds,
			$opts );

		// Calculate cutoff
		if ( $opts['days'] > 0 ) {
			$conds[] = 'rc_timestamp > ' .
				$dbr->addQuotes( $dbr->timestamp( time() - intval( $opts['days'] * 86400 ) ) );
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function doMainQuery( $tables, $fields, $conds, $query_options,
		$join_conds, FormOptions $opts ) {

		$dbr = $this->getDB();
		$user = $this->getUser();

		# Toggle watchlist content (all recent edits or just the latest)
		if ( $opts['extended'] ) {
			$limitWatchlist = $user->getIntOption( 'wllimit' );
			$usePage = false;
		} else {
			# Top log Ids for a page are not stored
			$nonRevisionTypes = [ RC_LOG ];
			Hooks::run( 'SpecialWatchlistGetNonRevisionTypes', [ &$nonRevisionTypes ] );
			if ( $nonRevisionTypes ) {
				$conds[] = $dbr->makeList(
					[
						'rc_this_oldid=page_latest',
						'rc_type' => $nonRevisionTypes,
					],
					LIST_OR
				);
			}
			$limitWatchlist = 0;
			$usePage = true;
		}

		$tables = array_merge( [ 'recentchanges', 'watchlist' ], $tables );
		$fields = array_merge( RecentChange::selectFields(), $fields );

		$query_options = array_merge( [ 'ORDER BY' => 'rc_timestamp DESC' ], $query_options );
		$join_conds = array_merge(
			[
				'watchlist' => [
					'INNER JOIN',
					[
						'wl_user' => $user->getId(),
						'wl_namespace=rc_namespace',
						'wl_title=rc_title'
					],
				],
			],
			$join_conds
		);

		if ( $this->getConfig()->get( 'ShowUpdatedMarker' ) ) {
			$fields[] = 'wl_notificationtimestamp';
		}
		if ( $limitWatchlist ) {
			$query_options['LIMIT'] = $limitWatchlist;
		}

		$rollbacker = $user->isAllowed( 'rollback' );
		if ( $usePage || $rollbacker ) {
			$tables[] = 'page';
			$join_conds['page'] = [ 'LEFT JOIN', 'rc_cur_id=page_id' ];
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
			$conds[] = $dbr->makeList( [
				'rc_type != ' . RC_LOG,
				$dbr->bitAnd( 'rc_deleted', $bitmask ) . " != $bitmask",
			], LIST_OR );
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
			&& Hooks::run(
				'SpecialWatchlistQuery',
				[ &$conds, &$tables, &$join_conds, &$fields, $opts ],
				'1.23'
			);
	}

	/**
	 * Return a IDatabase object for reading
	 *
	 * @return IDatabase
	 */
	protected function getDB() {
		return wfGetDB( DB_REPLICA, 'watchlist' );
	}

	/**
	 * Output feed links.
	 */
	public function outputFeedLinks() {
		$user = $this->getUser();
		$wlToken = $user->getTokenFromOption( 'watchlisttoken' );
		if ( $wlToken ) {
			$this->addFeedLinks( [
				'action' => 'feedwatchlist',
				'allrev' => 1,
				'wlowner' => $user->getName(),
				'wltoken' => $wlToken,
			] );
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

		# Show a message about replica DB lag, if applicable
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

		$list = ChangesList::newFromContext( $this->getContext(), $this->filterGroups );
		$list->setWatchlistDivs();
		$list->initChangesListRows( $rows );
		$dbr->dataSeek( $rows, 0 );

		if ( $this->getConfig()->get( 'RCShowWatchingUsers' )
			&& $user->getOption( 'shownumberswatching' )
		) {
			$watchedItemStore = MediaWikiServices::getInstance()->getWatchedItemStore();
		}

		$s = $list->beginRecentChangesList();
		$userShowHiddenCats = $this->getUser()->getBoolOption( 'showhiddencats' );
		$counter = 1;
		foreach ( $rows as $obj ) {
			# Make RC entry
			$rc = RecentChange::newFromRow( $obj );

			# Skip CatWatch entries for hidden cats based on user preference
			if (
				$rc->getAttribute( 'rc_type' ) == RC_CATEGORIZE &&
				!$userShowHiddenCats &&
				$rc->getParam( 'hidden-cat' )
			) {
				continue;
			}

			$rc->counter = $counter++;

			if ( $this->getConfig()->get( 'ShowUpdatedMarker' ) ) {
				$updated = $obj->wl_notificationtimestamp;
			} else {
				$updated = false;
			}

			if ( isset( $watchedItemStore ) ) {
				$rcTitleValue = new TitleValue( (int)$obj->rc_namespace, $obj->rc_title );
				$rc->numberofWatchingusers = $watchedItemStore->countWatchers( $rcTitleValue );
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
		$out = $this->getOutput();

		$out->addSubtitle(
			$this->msg( 'watchlistfor2', $user->getName() )
				->rawParams( SpecialEditWatchlist::buildTools(
					$this->getLanguage(),
					$this->getLinkRenderer()
				) )
		);

		$this->setTopText( $opts );

		$lang = $this->getLanguage();
		if ( $opts['days'] > 0 ) {
			$days = $opts['days'];
		} else {
			$days = $this->getConfig()->get( 'RCMaxAge' ) / ( 3600 * 24 );
		}
		$timestamp = wfTimestampNow();
		$wlInfo = $this->msg( 'wlnote' )->numParams( $numRows, round( $days * 24 ) )->params(
			$lang->userDate( $timestamp, $user ), $lang->userTime( $timestamp, $user )
		)->parse() . "<br />\n";

		$nondefaults = $opts->getChangedValues();
		$cutofflinks = $this->msg( 'wlshowtime' ) . ' ' . $this->cutoffselector( $opts );

		# Spit out some control panel links
		$links = [];
		$context = $this->getContext();
		$namesOfDisplayedFilters = [];
		foreach ( $this->getFilterGroups() as $groupName => $group ) {
			if ( !$group->isPerGroupRequestParameter() ) {
				foreach ( $group->getFilters() as $filterName => $filter ) {
					if ( $filter->displaysOnUnstructuredUi( $this ) ) {
						$namesOfDisplayedFilters[] = $filterName;
						$links[] = $this->showHideCheck(
							$nondefaults,
							$filter->getShowHide(),
							$filterName,
							$opts[$filterName]
						);
					}
				}
			}
		}

		$hiddenFields = $nondefaults;
		$hiddenFields['action'] = 'submit';
		unset( $hiddenFields['namespace'] );
		unset( $hiddenFields['invert'] );
		unset( $hiddenFields['associated'] );
		unset( $hiddenFields['days'] );
		foreach ( $namesOfDisplayedFilters as $filterName ) {
			unset( $hiddenFields[$filterName] );
		}

		# Create output
		$form = '';

		# Namespace filter and put the whole form together.
		$form .= $wlInfo;
		$form .= $cutofflinks;
		$form .= $this->msg( 'watchlist-hide' ) .
			$this->msg( 'colon-separator' )->escaped() .
			implode( ' ', $links );
		$form .= "\n<br />\n";
		$form .= Html::namespaceSelector(
			[
				'selected' => $opts['namespace'],
				'all' => '',
				'label' => $this->msg( 'namespace' )->text()
			], [
				'name' => 'namespace',
				'id' => 'namespace',
				'class' => 'namespaceselector',
			]
		) . "\n";
		$form .= '<span class="mw-input-with-label">' . Xml::checkLabel(
			$this->msg( 'invert' )->text(),
			'invert',
			'nsinvert',
			$opts['invert'],
			[ 'title' => $this->msg( 'tooltip-invert' )->text() ]
		) . "</span>\n";
		$form .= '<span class="mw-input-with-label">' . Xml::checkLabel(
			$this->msg( 'namespace_association' )->text(),
			'associated',
			'nsassociated',
			$opts['associated'],
			[ 'title' => $this->msg( 'tooltip-namespace_association' )->text() ]
		) . "</span>\n";
		$form .= Xml::submitButton( $this->msg( 'watchlist-submit' )->text() ) . "\n";
		foreach ( $hiddenFields as $key => $value ) {
			$form .= Html::hidden( $key, $value ) . "\n";
		}
		$form .= Xml::closeElement( 'fieldset' ) . "\n";
		$form .= Xml::closeElement( 'form' ) . "\n";
		$this->getOutput()->addHTML( $form );

		$this->setBottomText( $opts );
	}

	function cutoffselector( $options ) {
		// Cast everything to strings immediately, so that we know all of the values have the same
		// precision, and can be compared with '==='. 2/24 has a few more decimal places than its
		// default string representation, for example, and would confuse comparisons.

		// Misleadingly, the 'days' option supports hours too.
		$days = array_map( 'strval', [ 1/24, 2/24, 6/24, 12/24, 1, 3, 7 ] );

		$userWatchlistOption = (string)$this->getUser()->getOption( 'watchlistdays' );
		// add the user preference, if it isn't available already
		if ( !in_array( $userWatchlistOption, $days ) && $userWatchlistOption !== '0' ) {
			$days[] = $userWatchlistOption;
		}

		$maxDays = (string)( $this->getConfig()->get( 'RCMaxAge' ) / ( 3600 * 24 ) );
		// add the maximum possible value, if it isn't available already
		if ( !in_array( $maxDays, $days ) ) {
			$days[] = $maxDays;
		}

		$selected = (string)$options['days'];
		if ( $selected <= 0 ) {
			$selected = $maxDays;
		}

		// add the currently selected value, if it isn't available already
		if ( !in_array( $selected, $days ) ) {
			$days[] = $selected;
		}

		$select = new XmlSelect( 'days', 'days', $selected );

		asort( $days );
		foreach ( $days as $value ) {
			if ( $value < 1 ) {
				$name = $this->msg( 'hours' )->numParams( $value * 24 )->text();
			} else {
				$name = $this->msg( 'days' )->numParams( $value )->text();
			}
			$select->addOption( $name, $value );
		}

		return $select->getHTML() . "\n<br />\n";
	}

	function setTopText( FormOptions $opts ) {
		$nondefaults = $opts->getChangedValues();
		$form = "";
		$user = $this->getUser();

		$numItems = $this->countItems();
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
			$form .= Xml::openElement( 'form', [ 'method' => 'post',
				'action' => $this->getPageTitle()->getLocalURL(),
				'id' => 'mw-watchlist-resetbutton' ] ) . "\n" .
			Xml::submitButton( $this->msg( 'enotif_reset' )->text(),
				[ 'name' => 'mw-watchlist-reset-submit' ] ) . "\n" .
			Html::hidden( 'token', $user->getEditToken() ) . "\n" .
			Html::hidden( 'reset', 'all' ) . "\n";
			foreach ( $nondefaults as $key => $value ) {
				$form .= Html::hidden( $key, $value ) . "\n";
			}
			$form .= Xml::closeElement( 'form' ) . "\n";
		}

		$form .= Xml::openElement( 'form', [
			'method' => 'get',
			'action' => wfScript(),
			'id' => 'mw-watchlist-form'
		] );
		$form .= Html::hidden( 'title', $this->getPageTitle()->getPrefixedText() );
		$form .= Xml::fieldset(
			$this->msg( 'watchlist-options' )->text(),
			false,
			[ 'id' => 'mw-watchlist-options' ]
		);

		$form .= $this->makeLegend();

		$this->getOutput()->addHTML( $form );
	}

	protected function showHideCheck( $options, $message, $name, $value ) {
		$options[$name] = 1 - (int)$value;

		return '<span class="mw-input-with-label">' . Xml::checkLabel(
			$this->msg( $message, '' )->text(),
			$name,
			$name,
			(int)$value
		) . '</span>';
	}

	/**
	 * Count the number of paired items on a user's watchlist.
	 * The assumption made here is that when a subject page is watched a talk page is also watched.
	 * Hence the number of individual items is halved.
	 *
	 * @return int
	 */
	protected function countItems() {
		$store = MediaWikiServices::getInstance()->getWatchedItemStore();
		$count = $store->countWatchedItems( $this->getUser() );
		return floor( $count / 2 );
	}
}
