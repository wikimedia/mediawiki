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
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\IDatabase;

/**
 * A special page that lists last changes made to the wiki,
 * limited to user-defined list of titles.
 *
 * @ingroup SpecialPage
 */
class SpecialWatchlist extends ChangesListSpecialPage {
	protected static $savedQueriesPreferenceName = 'rcfilters-wl-saved-queries';
	protected static $daysPreferenceName = 'watchlistdays';
	protected static $limitPreferenceName = 'wllimit';
	protected static $collapsedPreferenceName = 'rcfilters-wl-collapsed';

	private $maxDays;

	public function __construct( $page = 'Watchlist', $restriction = 'viewmywatchlist' ) {
		parent::__construct( $page, $restriction );

		$this->maxDays = $this->getConfig()->get( 'RCMaxAge' ) / ( 3600 * 24 );
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
		$output->addModuleStyles( [ 'mediawiki.special' ] );
		$output->addModules( [
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

		if ( $this->isStructuredFilterUiEnabled() ) {
			$output->addModuleStyles( [ 'mediawiki.rcfilters.highlightCircles.seenunseen.styles' ] );

			$output->addJsConfigVars(
				'wgStructuredChangeFiltersEditWatchlistUrl',
				SpecialPage::getTitleFor( 'EditWatchlist' )->getLocalURL()
			);
		}
	}

	public static function checkStructuredFilterUiEnabled( Config $config, User $user ) {
		return !$user->getOption( 'wlenhancedfilters-disable' );
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
	 * @inheritDoc
	 */
	protected function transformFilterDefinition( array $filterDefinition ) {
		if ( isset( $filterDefinition['showHideSuffix'] ) ) {
			$filterDefinition['showHide'] = 'wl' . $filterDefinition['showHideSuffix'];
		}

		return $filterDefinition;
	}

	/**
	 * @inheritDoc
	 */
	protected function registerFilters() {
		parent::registerFilters();

		// legacy 'extended' filter
		$this->registerFilterGroup( new ChangesListBooleanFilterGroup( [
			'name' => 'extended-group',
			'filters' => [
				[
					'name' => 'extended',
					'isReplacedInStructuredUi' => true,
					'activeValue' => false,
					'default' => $this->getUser()->getBoolOption( 'extendwatchlist' ),
					'queryCallable' => function ( $specialClassName, $ctx, $dbr, &$tables,
												  &$fields, &$conds, &$query_options, &$join_conds ) {
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
					},
				]
			],

		] ) );

		if ( $this->isStructuredFilterUiEnabled() ) {
			$this->getFilterGroup( 'lastRevision' )
				->getFilter( 'hidepreviousrevisions' )
				->setDefault( !$this->getUser()->getBoolOption( 'extendwatchlist' ) );
		}

		$this->registerFilterGroup( new ChangesListStringOptionsFilterGroup( [
			'name' => 'watchlistactivity',
			'title' => 'rcfilters-filtergroup-watchlistactivity',
			'class' => ChangesListStringOptionsFilterGroup::class,
			'priority' => 3,
			'isFullCoverage' => true,
			'filters' => [
				[
					'name' => 'unseen',
					'label' => 'rcfilters-filter-watchlistactivity-unseen-label',
					'description' => 'rcfilters-filter-watchlistactivity-unseen-description',
					'cssClassSuffix' => 'watchedunseen',
					'isRowApplicableCallable' => function ( $ctx, $rc ) {
						$changeTs = $rc->getAttribute( 'rc_timestamp' );
						$lastVisitTs = $rc->getAttribute( 'wl_notificationtimestamp' );
						return $lastVisitTs !== null && $changeTs >= $lastVisitTs;
					},
				],
				[
					'name' => 'seen',
					'label' => 'rcfilters-filter-watchlistactivity-seen-label',
					'description' => 'rcfilters-filter-watchlistactivity-seen-description',
					'cssClassSuffix' => 'watchedseen',
					'isRowApplicableCallable' => function ( $ctx, $rc ) {
						$changeTs = $rc->getAttribute( 'rc_timestamp' );
						$lastVisitTs = $rc->getAttribute( 'wl_notificationtimestamp' );
						return $lastVisitTs === null || $changeTs < $lastVisitTs;
					}
				],
			],
			'default' => ChangesListStringOptionsFilterGroup::NONE,
			'queryCallable' => function ( $specialPageClassName, $context, $dbr,
										  &$tables, &$fields, &$conds, &$query_options, &$join_conds, $selectedValues ) {
				if ( $selectedValues === [ 'seen' ] ) {
					$conds[] = $dbr->makeList( [
						'wl_notificationtimestamp IS NULL',
						'rc_timestamp < wl_notificationtimestamp'
					], LIST_OR );
				} elseif ( $selectedValues === [ 'unseen' ] ) {
					$conds[] = $dbr->makeList( [
						'wl_notificationtimestamp IS NOT NULL',
						'rc_timestamp >= wl_notificationtimestamp'
					], LIST_AND );
				}
			}
		] ) );

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

		// Selecting both hideanons and hideliu on watchlist preferances
		// gives mutually exclusive filters, so those are ignored
		if ( $user->getBoolOption( 'watchlisthideanons' ) &&
			!$user->getBoolOption( 'watchlisthideliu' )
		) {
			$this->getFilterGroup( 'userExpLevel' )
				->setDefault( 'registered' );
		}

		if ( $user->getBoolOption( 'watchlisthideliu' ) &&
			!$user->getBoolOption( 'watchlisthideanons' )
		) {
			$this->getFilterGroup( 'userExpLevel' )
				->setDefault( 'unregistered' );
		}

		$reviewStatus = $this->getFilterGroup( 'reviewStatus' );
		if ( $reviewStatus !== null ) {
			// Conditional on feature being available and rights
			if ( $user->getBoolOption( 'watchlisthidepatrolled' ) ) {
				$reviewStatus->setDefault( 'unpatrolled' );
				$legacyReviewStatus = $this->getFilterGroup( 'legacyReviewStatus' );
				$legacyHidePatrolled = $legacyReviewStatus->getFilter( 'hidepatrolled' );
				$legacyHidePatrolled->setDefault( true );
			}
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
			foreach ( $this->getLegacyShowHideFilters() as $filter ) {
				$allBooleansFalse[ $filter->getName() ] = false;
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
	 * @inheritDoc
	 */
	protected function doMainQuery( $tables, $fields, $conds, $query_options,
		$join_conds, FormOptions $opts
	) {
		$dbr = $this->getDB();
		$user = $this->getUser();

		$rcQuery = RecentChange::getQueryInfo();
		$tables = array_merge( $tables, $rcQuery['tables'], [ 'watchlist' ] );
		$fields = array_merge( $rcQuery['fields'], $fields );

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
			$rcQuery['joins'],
			$join_conds
		);

		$tables[] = 'page';
		$fields[] = 'page_latest';
		$join_conds['page'] = [ 'LEFT JOIN', 'rc_cur_id=page_id' ];

		$fields[] = 'wl_notificationtimestamp';

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

		$tagFilter = $opts['tagfilter'] ? explode( '|', $opts['tagfilter'] ) : [];
		ChangeTags::modifyDisplayQuery(
			$tables,
			$fields,
			$conds,
			$join_conds,
			$query_options,
			$tagFilter
		);

		$this->runMainQueryHook( $tables, $fields, $conds, $query_options, $join_conds, $opts );

		if ( $this->areFiltersInConflict() ) {
			return false;
		}

		$orderByAndLimit = [
			'ORDER BY' => 'rc_timestamp DESC',
			'LIMIT' => $opts['limit']
		];
		if ( in_array( 'DISTINCT', $query_options ) ) {
			// ChangeTags::modifyDisplayQuery() adds DISTINCT when filtering on multiple tags.
			// In order to prevent DISTINCT from causing query performance problems,
			// we have to GROUP BY the primary key. This in turn requires us to add
			// the primary key to the end of the ORDER BY, and the old ORDER BY to the
			// start of the GROUP BY
			$orderByAndLimit['ORDER BY'] = 'rc_timestamp DESC, rc_id DESC';
			$orderByAndLimit['GROUP BY'] = 'rc_timestamp, rc_id';
		}
		// array_merge() is used intentionally here so that hooks can, should
		// they so desire, override the ORDER BY / LIMIT condition(s)
		$query_options = array_merge( $orderByAndLimit, $query_options );

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
	 * @param IResultWrapper $rows Database rows
	 * @param FormOptions $opts
	 */
	public function outputChangesList( $rows, $opts ) {
		$dbr = $this->getDB();
		$user = $this->getUser();
		$output = $this->getOutput();
		$services = MediaWikiServices::getInstance();

		# Show a message about replica DB lag, if applicable
		$lag = $services->getDBLoadBalancer()->safeGetLag( $dbr );
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
		if ( $user->getOption( 'watchlistunwatchlinks' ) ) {
			$list->setChangeLinePrefixer( function ( RecentChange $rc, ChangesList $cl, $grouped ) {
				// Don't show unwatch link if the line is a grouped log entry using EnhancedChangesList,
				// since EnhancedChangesList groups log entries by performer rather than by target article
				if ( $rc->mAttribs['rc_type'] == RC_LOG && $cl instanceof EnhancedChangesList &&
					$grouped ) {
					return '';
				} else {
					return $this->getLinkRenderer()
							->makeKnownLink( $rc->getTitle(),
								$this->msg( 'watchlist-unwatch' )->text(), [
									'class' => 'mw-unwatch-link',
									'title' => $this->msg( 'tooltip-ca-unwatch' )->text()
								], [ 'action' => 'unwatch' ] ) . "\u{00A0}";
				}
			} );
		}
		$dbr->dataSeek( $rows, 0 );

		if ( $this->getConfig()->get( 'RCShowWatchingUsers' )
			&& $user->getOption( 'shownumberswatching' )
		) {
			$watchedItemStore = $services->getWatchedItemStore();
		}

		$s = $list->beginRecentChangesList();

		if ( $this->isStructuredFilterUiEnabled() ) {
			$s .= $this->makeLegend();
		}

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

		$form = '';

		$form .= Xml::openElement( 'form', [
			'method' => 'get',
			'action' => wfScript(),
			'id' => 'mw-watchlist-form'
		] );
		$form .= Html::hidden( 'title', $this->getPageTitle()->getPrefixedText() );
		$form .= Xml::openElement(
			'fieldset',
			[ 'id' => 'mw-watchlist-options', 'class' => 'cloptions' ]
		);
		$form .= Xml::element(
			'legend', null, $this->msg( 'watchlist-options' )->text()
		);

		if ( !$this->isStructuredFilterUiEnabled() ) {
			$form .= $this->makeLegend();
		}

		$lang = $this->getLanguage();
		$timestamp = wfTimestampNow();
		$wlInfo = Html::rawElement(
			'span',
			[
				'class' => 'wlinfo',
				'data-params' => json_encode( [ 'from' => $timestamp ] ),
			],
			$this->msg( 'wlnote' )->numParams( $numRows, round( $opts['days'] * 24 ) )->params(
				$lang->userDate( $timestamp, $user ), $lang->userTime( $timestamp, $user )
			)->parse()
		) . "<br />\n";

		$nondefaults = $opts->getChangedValues();
		$cutofflinks = Html::rawElement(
			'span',
			[ 'class' => 'cldays cloption' ],
			$this->msg( 'wlshowtime' ) . ' ' . $this->cutoffselector( $opts )
		);

		# Spit out some control panel links
		$links = [];
		$namesOfDisplayedFilters = [];
		foreach ( $this->getLegacyShowHideFilters() as $filterName => $filter ) {
			$namesOfDisplayedFilters[] = $filterName;
			$links[] = $this->showHideCheck(
				$nondefaults,
				$filter->getShowHide(),
				$filterName,
				$opts[ $filterName ],
				$filter->isFeatureAvailableOnStructuredUi( $this )
			);
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

		# Namespace filter and put the whole form together.
		$form .= $wlInfo;
		$form .= $cutofflinks;
		$form .= Html::rawElement(
			'span',
			[ 'class' => 'clshowhide' ],
			$this->msg( 'watchlist-hide' ) .
			$this->msg( 'colon-separator' )->escaped() .
			implode( ' ', $links )
		);
		$form .= "\n<br />\n";

		$namespaceForm = Html::namespaceSelector(
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
		$namespaceForm .= '<span class="mw-input-with-label">' . Xml::checkLabel(
			$this->msg( 'invert' )->text(),
			'invert',
			'nsinvert',
			$opts['invert'],
			[ 'title' => $this->msg( 'tooltip-invert' )->text() ]
		) . "</span>\n";
		$namespaceForm .= '<span class="mw-input-with-label">' . Xml::checkLabel(
			$this->msg( 'namespace_association' )->text(),
			'associated',
			'nsassociated',
			$opts['associated'],
			[ 'title' => $this->msg( 'tooltip-namespace_association' )->text() ]
		) . "</span>\n";
		$form .= Html::rawElement(
			'span',
			[ 'class' => 'namespaceForm cloption' ],
			$namespaceForm
		);

		$form .= Xml::submitButton(
			$this->msg( 'watchlist-submit' )->text(),
			[ 'class' => 'cloption-submit' ]
		) . "\n";
		foreach ( $hiddenFields as $key => $value ) {
			$form .= Html::hidden( $key, $value ) . "\n";
		}
		$form .= Xml::closeElement( 'fieldset' ) . "\n";
		$form .= Xml::closeElement( 'form' ) . "\n";

		// Insert a placeholder for RCFilters
		if ( $this->isStructuredFilterUiEnabled() ) {
			$rcfilterContainer = Html::element(
				'div',
				[ 'class' => 'rcfilters-container' ]
			);

			$loadingContainer = Html::rawElement(
				'div',
				[ 'class' => 'rcfilters-spinner' ],
				Html::element(
					'div',
					[ 'class' => 'rcfilters-spinner-bounce' ]
				)
			);

			// Wrap both with rcfilters-head
			$this->getOutput()->addHTML(
				Html::rawElement(
					'div',
					[ 'class' => 'rcfilters-head' ],
					$rcfilterContainer . $form
				)
			);

			// Add spinner
			$this->getOutput()->addHTML( $loadingContainer );
		} else {
			$this->getOutput()->addHTML( $form );
		}

		$this->setBottomText( $opts );
	}

	function cutoffselector( $options ) {
		$selected = (float)$options['days'];
		if ( $selected <= 0 ) {
			$selected = $this->maxDays;
		}

		$selectedHours = round( $selected * 24 );

		$hours = array_unique( array_filter( [
			1,
			2,
			6,
			12,
			24,
			72,
			168,
			24 * (float)$this->getUser()->getOption( 'watchlistdays', 0 ),
			24 * $this->maxDays,
			$selectedHours
		] ) );
		asort( $hours );

		$select = new XmlSelect( 'days', 'days', (float)( $selectedHours / 24 ) );

		foreach ( $hours as $value ) {
			if ( $value < 24 ) {
				$name = $this->msg( 'hours' )->numParams( $value )->text();
			} else {
				$name = $this->msg( 'days' )->numParams( $value / 24 )->text();
			}
			$select->addOption( $name, (float)( $value / 24 ) );
		}

		return $select->getHTML() . "\n<br />\n";
	}

	function setTopText( FormOptions $opts ) {
		$nondefaults = $opts->getChangedValues();
		$form = '';
		$user = $this->getUser();

		$numItems = $this->countItems();
		$showUpdatedMarker = $this->getConfig()->get( 'ShowUpdatedMarker' );

		// Show watchlist header
		$watchlistHeader = '';
		if ( $numItems == 0 ) {
			$watchlistHeader = $this->msg( 'nowatchlist' )->parse();
		} else {
			$watchlistHeader .= $this->msg( 'watchlist-details' )->numParams( $numItems )->parse() . "\n";
			if ( $this->getConfig()->get( 'EnotifWatchlist' )
				&& $user->getOption( 'enotifwatchlistpages' )
			) {
				$watchlistHeader .= $this->msg( 'wlheader-enotif' )->parse() . "\n";
			}
			if ( $showUpdatedMarker ) {
				$watchlistHeader .= $this->msg(
					$this->isStructuredFilterUiEnabled() ?
						'rcfilters-watchlist-showupdated' :
						'wlheader-showupdated'
				)->parse() . "\n";
			}
		}
		$form .= Html::rawElement(
			'div',
			[ 'class' => 'watchlistDetails' ],
			$watchlistHeader
		);

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

		$this->getOutput()->addHTML( $form );
	}

	protected function showHideCheck( $options, $message, $name, $value, $inStructuredUi ) {
		$options[$name] = 1 - (int)$value;

		$attribs = [ 'class' => 'mw-input-with-label clshowhideoption cloption' ];
		if ( $inStructuredUi ) {
			$attribs[ 'data-feature-in-structured-ui' ] = true;
		}

		return Html::rawElement(
			'span',
			$attribs,
			// not using Html::checkLabel because that would escape the contents
			Html::check( $name, (int)$value, [ 'id' => $name ] ) . Html::rawElement(
				'label',
				$attribs + [ 'for' => $name ],
				// <nowiki/> at beginning to avoid messages with "$1 ..." being parsed as pre tags
				$this->msg( $message, '<nowiki/>' )->parse()
			)
		);
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
