<?php
/**
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
 */

namespace MediaWiki\Specials;

use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\Context\IContextSource;
use MediaWiki\Exception\UserNotLoggedIn;
use MediaWiki\Html\FormOptions;
use MediaWiki\Html\Html;
use MediaWiki\Logging\LogPage;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\RecentChanges\ChangesList;
use MediaWiki\RecentChanges\ChangesListBooleanFilterGroup;
use MediaWiki\RecentChanges\ChangesListStringOptionsFilterGroup;
use MediaWiki\RecentChanges\EnhancedChangesList;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Request\DerivativeRequest;
use MediaWiki\SpecialPage\ChangesListSpecialPage;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityUtils;
use MediaWiki\Watchlist\WatchedItem;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use MediaWiki\Watchlist\WatchlistManager;
use MediaWiki\Xml\XmlSelect;
use Wikimedia\Message\MessageValue;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\RawSQLExpression;
use Wikimedia\Rdbms\RawSQLValue;

/**
 * @defgroup Watchlist Users watchlist handling
 */

/**
 * A special page that lists last changes made to the wiki,
 * limited to user-defined list of titles.
 *
 * @ingroup SpecialPage
 * @ingroup Watchlist
 */
class SpecialWatchlist extends ChangesListSpecialPage {

	public const WATCHLIST_TAB_PATHS = [
		'Special:Watchlist',
		'Special:EditWatchlist',
		'Special:EditWatchlist/raw',
		'Special:EditWatchlist/clear'
	];

	private WatchedItemStoreInterface $watchedItemStore;
	private WatchlistManager $watchlistManager;
	private UserOptionsLookup $userOptionsLookup;

	/**
	 * @var int|false where the value is one of the SpecialEditWatchlist:EDIT_ prefixed
	 * constants (e.g. EDIT_NORMAL)
	 */
	private $currentMode;
	private ChangeTagsStore $changeTagsStore;

	public function __construct(
		WatchedItemStoreInterface $watchedItemStore,
		WatchlistManager $watchlistManager,
		UserOptionsLookup $userOptionsLookup,
		ChangeTagsStore $changeTagsStore,
		UserIdentityUtils $userIdentityUtils,
		TempUserConfig $tempUserConfig
	) {
		parent::__construct(
			'Watchlist',
			'viewmywatchlist',
			$userIdentityUtils,
			$tempUserConfig
		);

		$this->watchedItemStore = $watchedItemStore;
		$this->watchlistManager = $watchlistManager;
		$this->userOptionsLookup = $userOptionsLookup;
		$this->changeTagsStore = $changeTagsStore;
	}

	public function doesWrites() {
		return true;
	}

	/**
	 * Main execution point
	 *
	 * @param string|null $subpage
	 */
	public function execute( $subpage ) {
		$user = $this->getUser();
		if (
			// Anons don't get a watchlist
			!$user->isRegistered()
			// Redirect temp users to login if they're not allowed
			|| ( $user->isTemp() && !$user->isAllowed( 'viewmywatchlist' ) )
		) {
			throw new UserNotLoggedIn( 'watchlistanontext' );
		}

		$output = $this->getOutput();
		$request = $this->getRequest();
		$this->addHelpLink( 'Help:Watching pages' );
		$output->addModuleStyles( [ 'mediawiki.special' ] );
		$output->addModules( [ 'mediawiki.special.watchlist' ] );

		$mode = SpecialEditWatchlist::getMode( $request, $subpage );
		$this->currentMode = $mode;

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

		$opts = $this->getOptions();

		$config = $this->getConfig();
		if ( ( $config->get( MainConfigNames::EnotifWatchlist ) ||
				$config->get( MainConfigNames::ShowUpdatedMarker ) )
			&& $request->getVal( 'reset' )
			&& $request->wasPosted()
			&& $user->matchEditToken( $request->getVal( 'token' ) )
		) {
			$this->watchlistManager->clearAllUserNotifications( $user );
			$output->redirect( $this->getPageTitle()->getFullURL( $opts->getChangedValues() ) );

			return;
		}

		parent::execute( $subpage );

		if ( $this->isStructuredFilterUiEnabled() ) {
			$output->addModuleStyles( [ 'mediawiki.rcfilters.highlightCircles.seenunseen.styles' ] );
		}
	}

	/**
	 * @inheritDoc
	 */
	public static function checkStructuredFilterUiEnabled( UserIdentity $user ) {
		return !MediaWikiServices::getInstance()
			->getUserOptionsLookup()
			->getOption( $user, 'wlenhancedfilters-disable' );
	}

	/**
	 * Return an array of subpages that this special page will accept.
	 *
	 * @see also SpecialEditWatchlist::getSubpagesForPrefixSearch
	 * @return string[] subpages
	 */
	public function getSubpagesForPrefixSearch() {
		return [
			'edit',
			'raw',
			'clear',
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
	 * @suppress PhanUndeclaredMethod
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
					'default' => $this->userOptionsLookup->getBoolOption( $this->getUser(), 'extendwatchlist' ),
					'queryCallable' => function ( string $specialClassName, IContextSource $ctx,
						IReadableDatabase $dbr, &$tables, &$fields, &$conds, &$query_options, &$join_conds
					) {
						$nonRevisionTypes = [ RC_LOG ];
						$this->getHookRunner()->onSpecialWatchlistGetNonRevisionTypes( $nonRevisionTypes );
						if ( $nonRevisionTypes ) {
							$conds[] = $dbr->expr( 'rc_this_oldid', '=', new RawSQLValue( 'page_latest' ) )
								->or( 'rc_type', '=', $nonRevisionTypes );
						}
					},
				]
			],

		] ) );

		if ( $this->isStructuredFilterUiEnabled() ) {
			$this->getFilterGroup( 'lastRevision' )
				->getFilter( 'hidepreviousrevisions' )
				->setDefault( !$this->userOptionsLookup->getBoolOption( $this->getUser(), 'extendwatchlist' ) );
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
					'isRowApplicableCallable' => function ( IContextSource $ctx, RecentChange $rc ) {
						return !$this->isChangeEffectivelySeen( $rc );
					},
				],
				[
					'name' => 'seen',
					'label' => 'rcfilters-filter-watchlistactivity-seen-label',
					'description' => 'rcfilters-filter-watchlistactivity-seen-description',
					'cssClassSuffix' => 'watchedseen',
					'isRowApplicableCallable' => function ( IContextSource $ctx, RecentChange $rc ) {
						return $this->isChangeEffectivelySeen( $rc );
					}
				],
			],
			'default' => ChangesListStringOptionsFilterGroup::NONE,
			'queryCallable' => static function (
				string $specialPageClassName,
				IContextSource $context,
				IReadableDatabase $dbr,
				&$tables,
				&$fields,
				&$conds,
				&$query_options,
				&$join_conds,
				$selectedValues
			) {
				if ( $selectedValues === [ 'seen' ] ) {
					$conds[] = $dbr->expr( 'wl_notificationtimestamp', '=', null )
						->orExpr( new RawSQLExpression( 'rc_timestamp < wl_notificationtimestamp' ) );
				} elseif ( $selectedValues === [ 'unseen' ] ) {
					$conds[] = $dbr->expr( 'wl_notificationtimestamp', '!=', null )
						->andExpr( new RawSQLExpression( 'rc_timestamp >= wl_notificationtimestamp' ) );
				}
			}
		] ) );

		$user = $this->getUser();

		$significance = $this->getFilterGroup( 'significance' );
		$hideMinor = $significance->getFilter( 'hideminor' );
		$hideMinor->setDefault( $this->userOptionsLookup->getBoolOption( $user, 'watchlisthideminor' ) );

		$automated = $this->getFilterGroup( 'automated' );
		$hideBots = $automated->getFilter( 'hidebots' );
		$hideBots->setDefault( $this->userOptionsLookup->getBoolOption( $user, 'watchlisthidebots' ) );

		$registration = $this->getFilterGroup( 'registration' );
		$hideAnons = $registration->getFilter( 'hideanons' );
		$hideAnons->setDefault( $this->userOptionsLookup->getBoolOption( $user, 'watchlisthideanons' ) );
		$hideLiu = $registration->getFilter( 'hideliu' );
		$hideLiu->setDefault( $this->userOptionsLookup->getBoolOption( $user, 'watchlisthideliu' ) );

		// Selecting both hideanons and hideliu on watchlist preferences
		// gives mutually exclusive filters, so those are ignored
		if ( $this->userOptionsLookup->getBoolOption( $user, 'watchlisthideanons' ) &&
			!$this->userOptionsLookup->getBoolOption( $user, 'watchlisthideliu' )
		) {
			$this->getFilterGroup( 'userExpLevel' )
				->setDefault( 'registered' );
		}

		if ( $this->userOptionsLookup->getBoolOption( $user, 'watchlisthideliu' ) &&
			!$this->userOptionsLookup->getBoolOption( $user, 'watchlisthideanons' )
		) {
			$this->getFilterGroup( 'userExpLevel' )
				->setDefault( 'unregistered' );
		}

		$reviewStatus = $this->getFilterGroup( 'reviewStatus' );
		if ( $reviewStatus !== null ) {
			// Conditional on feature being available and rights
			if ( $this->userOptionsLookup->getBoolOption( $user, 'watchlisthidepatrolled' ) ) {
				$reviewStatus->setDefault( 'unpatrolled' );
				$legacyReviewStatus = $this->getFilterGroup( 'legacyReviewStatus' );
				$legacyHidePatrolled = $legacyReviewStatus->getFilter( 'hidepatrolled' );
				$legacyHidePatrolled->setDefault( true );
			}
		}

		$authorship = $this->getFilterGroup( 'authorship' );
		$hideMyself = $authorship->getFilter( 'hidemyself' );
		$hideMyself->setDefault( $this->userOptionsLookup->getBoolOption( $user, 'watchlisthideown' ) );

		$changeType = $this->getFilterGroup( 'changeType' );
		$hideCategorization = $changeType->getFilter( 'hidecategorization' );
		if ( $hideCategorization !== null ) {
			// Conditional on feature being available
			$hideCategorization->setDefault(
				$this->userOptionsLookup->getBoolOption( $user, 'watchlisthidecategorization' )
			);
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

		if ( $this->getRequest()->getRawVal( 'action' ) == 'submit' ) {
			$allBooleansFalse = [];

			// If the user submitted the form, start with a baseline of "all
			// booleans are false", then change the ones they checked.  This
			// means we ignore the defaults.

			// This is how we handle the fact that HTML forms don't submit
			// unchecked boxes.
			foreach ( $this->getLegacyShowHideFilters() as $filter ) {
				$allBooleansFalse[ $filter->getName() ] = false;
			}

			$params += $allBooleansFalse;
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
		$tables = array_merge( $rcQuery['tables'], $tables, [ 'watchlist' ] );
		$fields = array_merge( $rcQuery['fields'], $fields );

		$join_conds = array_merge(
			[
				'watchlist' => [
					'JOIN',
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

		if ( $this->getConfig()->get( MainConfigNames::WatchlistExpiry ) ) {
			$tables[] = 'watchlist_expiry';
			$fields[] = 'we_expiry';
			$join_conds['watchlist_expiry'] = [ 'LEFT JOIN', 'wl_id = we_item' ];
			$conds[] = $dbr->expr( 'we_expiry', '=', null )->or( 'we_expiry', '>', $dbr->timestamp() );
		}

		$tables[] = 'page';
		$fields[] = 'page_latest';
		$join_conds['page'] = [ 'LEFT JOIN', 'rc_cur_id=page_id' ];

		$fields[] = 'wl_notificationtimestamp';

		// Log entries with DELETED_ACTION must not show up unless the user has
		// the necessary rights.
		$authority = $this->getAuthority();
		if ( !$authority->isAllowed( 'deletedhistory' ) ) {
			$bitmask = LogPage::DELETED_ACTION;
		} elseif ( !$authority->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
			$bitmask = LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED;
		} else {
			$bitmask = 0;
		}
		if ( $bitmask ) {
			$conds[] = $dbr->expr( 'rc_type', '!=', RC_LOG )
				->orExpr( new RawSQLExpression( $dbr->bitAnd( 'rc_deleted', $bitmask ) . " != $bitmask" ) );
		}

		$tagFilter = $opts['tagfilter'] !== '' ? explode( '|', $opts['tagfilter'] ) : [];
		$this->changeTagsStore->modifyDisplayQuery(
			$tables,
			$fields,
			$conds,
			$join_conds,
			$query_options,
			$tagFilter,
			$opts['inverttags']
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
			// ChangeTagsStore::modifyDisplayQuery() adds DISTINCT when filtering on multiple tags.
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
		$query_options['MAX_EXECUTION_TIME'] =
			$this->getConfig()->get( MainConfigNames::MaxExecutionTimeForExpensiveQueries );
		$queryBuilder = $dbr->newSelectQueryBuilder()
			->tables( $tables )
			->conds( $conds )
			->fields( $fields )
			->options( $query_options )
			->joinConds( $join_conds )
			->caller( __METHOD__ );

		return $queryBuilder->fetchResultSet();
	}

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

		// Show a message about replica DB lag, if applicable
		$lag = $dbr->getSessionLagStatus()['lag'];
		if ( $lag > 0 ) {
			$output->showLagWarning( $lag );
		}

		// If there are no rows to display, show message before trying to render the list
		if ( $rows->numRows() == 0 ) {
			$output->wrapWikiMsg(
				"<div class='mw-changeslist-empty'>\n$1\n</div>", 'recentchanges-noresult'
			);
			return;
		}

		$rows->seek( 0 );

		$list = ChangesList::newFromContext( $this->getContext(), $this->filterGroups );
		$list->setWatchlistDivs();
		$list->initChangesListRows( $rows );

		if ( $this->userOptionsLookup->getBoolOption( $user, 'watchlistunwatchlinks' ) ) {
			$list->setChangeLinePrefixer( function ( RecentChange $rc, ChangesList $cl, $grouped ) {
				$unwatch = $this->msg( 'watchlist-unwatch' )->text();
				// Don't show unwatch link if the line is a grouped log entry using EnhancedChangesList,
				// since EnhancedChangesList groups log entries by performer rather than by target article
				if ( $rc->mAttribs['rc_type'] == RC_LOG && $cl instanceof EnhancedChangesList &&
					$grouped ) {
					return "<span style='visibility:hidden'>$unwatch</span>\u{00A0}";
				} else {
					$unwatchTooltipMessage = 'tooltip-ca-unwatch';
					// Check if the watchlist expiry flag is enabled to show new tooltip message
					if ( $this->getConfig()->get( MainConfigNames::WatchlistExpiry ) ) {
						$watchedItem = $this->watchedItemStore->getWatchedItem( $this->getUser(), $rc->getTitle() );
						if ( $watchedItem instanceof WatchedItem && $watchedItem->getExpiry() !== null ) {
							$diffInDays = $watchedItem->getExpiryInDays();

							if ( $diffInDays > 0 ) {
								$unwatchTooltipMessage = MessageValue::new( 'tooltip-ca-unwatch-expiring' )
									->numParams( $diffInDays );
							} else {
								$unwatchTooltipMessage = 'tooltip-ca-unwatch-expiring-hours';
							}
						}
					}

					return $this->getLinkRenderer()
							->makeKnownLink( $rc->getTitle(),
								$unwatch, [
									'class' => 'mw-unwatch-link',
									'title' => $this->msg( $unwatchTooltipMessage )->text()
								], [ 'action' => 'unwatch' ] ) . "\u{00A0}";
				}
			} );
		}
		$rows->seek( 0 );

		$s = $list->beginRecentChangesList();

		if ( $this->isStructuredFilterUiEnabled() ) {
			$s .= $this->makeLegend();
		}

		$userShowHiddenCats = $this->userOptionsLookup->getBoolOption( $user, 'showhiddencats' );
		$counter = 1;
		foreach ( $rows as $obj ) {
			// Make RC entry
			$rc = RecentChange::newFromRow( $obj );

			// Skip CatWatch entries for hidden cats based on user preference
			if (
				$rc->getAttribute( 'rc_type' ) == RC_CATEGORIZE &&
				!$userShowHiddenCats &&
				$rc->getParam( 'hidden-cat' )
			) {
				continue;
			}

			$rc->counter = $counter++;

			if ( $this->getConfig()->get( MainConfigNames::ShowUpdatedMarker ) ) {
				$unseen = !$this->isChangeEffectivelySeen( $rc );
			} else {
				$unseen = false;
			}

			if ( $this->getConfig()->get( MainConfigNames::RCShowWatchingUsers )
				&& $this->userOptionsLookup->getBoolOption( $user, 'shownumberswatching' )
			) {
				$rcTitleValue = new TitleValue( (int)$obj->rc_namespace, $obj->rc_title );
				$rc->numberofWatchingusers = $this->watchedItemStore->countWatchers( $rcTitleValue );
			} else {
				$rc->numberofWatchingusers = 0;
			}

			// XXX: this treats pages with no unseen changes as "not on the watchlist" since
			// everything is on the watchlist and it is an easy way to make pages with unseen
			// changes appear bold. @TODO: clean this up.
			$changeLine = $list->recentChangesLine( $rc, $unseen, $counter );
			if ( $changeLine !== false ) {
				$s .= $changeLine;
			}
		}
		$s .= $list->endRecentChangesList();

		$output->addHTML( $s );
	}

	/**
	 * @inheritDoc
	 */
	public function getAssociatedNavigationLinks() {
		return self::WATCHLIST_TAB_PATHS;
	}

	/**
	 * @param SpecialPage $specialPage
	 * @param string $path
	 * @return string
	 */
	public static function getShortDescriptionHelper( SpecialPage $specialPage, string $path = '' ): string {
		switch ( $path ) {
			case 'Watchlist':
				return $specialPage->msg( 'watchlisttools-view' )->text();
			case 'EditWatchlist':
				return $specialPage->msg( 'watchlisttools-edit' )->text();
			case 'EditWatchlist/raw':
				return $specialPage->msg( 'watchlisttools-raw' )->text();
			case 'EditWatchlist/clear':
				return $specialPage->msg( 'watchlisttools-clear' )->text();
			default:
				return $path;
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getShortDescription( string $path = '' ): string {
		return self::getShortDescriptionHelper( $this, $path );
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
		$skin = $this->getSkin();
		// For legacy skins render the tabs in the subtitle
		$subpageSubtitle = $skin->supportsMenu( 'associated-pages' ) ? '' :
			' ' .
			SpecialEditWatchlist::buildTools(
					null,
					$this->getLinkRenderer(),
					$this->currentMode
				);

		$out->addSubtitle(
			Html::element(
				'span',
				[
					'class' => 'mw-watchlist-owner'
				],
				// Previously the watchlistfor2 message took 2 parameters.
				// It now only takes 1 so empty string is passed.
				// Empty string parameter can be removed when all messages
				// are updated to not use $2
				$this->msg( 'watchlistfor2', $this->getUser()->getName(), '' )->text()
			) . $subpageSubtitle
		);

		$this->setTopText( $opts );

		$form = '';

		$form .= Html::openElement( 'form', [
			'method' => 'get',
			'action' => wfScript(),
			'id' => 'mw-watchlist-form'
		] );
		$form .= Html::hidden( 'title', $this->getPageTitle()->getPrefixedText() );
		$form .= Html::openElement(
			'fieldset',
			[ 'id' => 'mw-watchlist-options', 'class' => 'cloptions' ]
		);
		$form .= Html::element(
			'legend', [], $this->msg( 'watchlist-options' )->text()
		);

		if ( !$this->isStructuredFilterUiEnabled() ) {
			$form .= $this->makeLegend();
		}

		$lang = $this->getLanguage();
		$timestamp = wfTimestampNow();
		$now = $lang->userTimeAndDate( $timestamp, $user );
		$wlInfo = Html::rawElement(
			'span',
			[
				'class' => 'wlinfo',
				'data-params' => json_encode( [ 'from' => $timestamp, 'fromFormatted' => $now ] ),
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

		// Spit out some control panel links
		$links = [];
		$namesOfDisplayedFilters = [];
		foreach ( $this->getLegacyShowHideFilters() as $filterName => $filter ) {
			$namesOfDisplayedFilters[] = $filterName;
			$links[] = $this->showHideCheck(
				$nondefaults,
				$filter->getShowHide(),
				$filterName,
				$opts[ $filterName ],
				$filter->isFeatureAvailableOnStructuredUi()
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

		// Namespace filter and put the whole form together.
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
				'label' => $this->msg( 'namespace' )->text(),
				'in-user-lang' => true,
			], [
				'name' => 'namespace',
				'id' => 'namespace',
				'class' => 'namespaceselector',
			]
		) . "\n";
		$namespaceForm .= Html::rawElement( 'label', [
			'class' => 'mw-input-with-label', 'title' => $this->msg( 'tooltip-invert' )->text(),
		], Html::element( 'input', [
			'type' => 'checkbox', 'name' => 'invert', 'value' => '1', 'checked' => $opts['invert'],
		] ) . '&nbsp;' . $this->msg( 'invert' )->escaped() ) . "\n";
		$namespaceForm .= Html::rawElement( 'label', [
			'class' => 'mw-input-with-label', 'title' => $this->msg( 'tooltip-namespace_association' )->text(),
		], Html::element( 'input', [
			'type' => 'checkbox', 'name' => 'associated', 'value' => '1', 'checked' => $opts['associated'],
		] ) . '&nbsp;' . $this->msg( 'namespace_association' )->escaped() ) . "\n";
		$form .= Html::rawElement(
			'span',
			[ 'class' => 'namespaceForm cloption' ],
			$namespaceForm
		);

		$form .= Html::submitButton(
			$this->msg( 'watchlist-submit' )->text(),
			[ 'class' => 'cloption-submit' ]
		) . "\n";
		foreach ( $hiddenFields as $key => $value ) {
			$form .= Html::hidden( $key, $value ) . "\n";
		}
		$form .= Html::closeElement( 'fieldset' ) . "\n";
		$form .= Html::closeElement( 'form' ) . "\n";

		// Insert a placeholder for RCFilters
		if ( $this->isStructuredFilterUiEnabled() ) {
			$rcfilterContainer = Html::element(
				'div',
				[ 'class' => 'mw-rcfilters-container' ]
			);

			$loadingContainer = Html::rawElement(
				'div',
				[ 'class' => 'mw-rcfilters-spinner' ],
				Html::element(
					'div',
					[ 'class' => 'mw-rcfilters-spinner-bounce' ]
				)
			);

			// Wrap both with mw-rcfilters-head
			$this->getOutput()->addHTML(
				Html::rawElement(
					'div',
					[ 'class' => 'mw-rcfilters-head' ],
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

	private function cutoffselector( FormOptions $options ): string {
		$selected = (float)$options['days'];
		$maxDays = $this->getConfig()->get( MainConfigNames::RCMaxAge ) / ( 3600 * 24 );
		if ( $selected <= 0 ) {
			$selected = $maxDays;
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
			24 * (float)$this->userOptionsLookup->getOption( $this->getUser(), 'watchlistdays', 0 ),
			24 * $maxDays,
			$selectedHours
		] ) );
		asort( $hours );

		$select = new XmlSelect( 'days', 'days', $selectedHours / 24 );

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

	public function setTopText( FormOptions $opts ) {
		$nondefaults = $opts->getChangedValues();
		$form = '';
		$user = $this->getUser();

		$numItems = $this->countItems();
		$showUpdatedMarker = $this->getConfig()->get( MainConfigNames::ShowUpdatedMarker );

		// Show watchlist header
		$watchlistHeader = '';
		if ( $numItems == 0 ) {
			$watchlistHeader = $this->msg( 'nowatchlist' )->parse();
		} else {
			$watchlistHeader .= $this->msg( 'watchlist-details' )->numParams( $numItems )->parse()
				. $this->msg( 'word-separator' )->escaped();
			if ( $this->getConfig()->get( MainConfigNames::EnotifWatchlist )
				&& $this->userOptionsLookup->getBoolOption( $user, 'enotifwatchlistpages' )
			) {
				$watchlistHeader .= $this->msg( 'wlheader-enotif' )->parse()
					. $this->msg( 'word-separator' )->escaped();
			}
			if ( $showUpdatedMarker ) {
				$watchlistHeader .= $this->msg(
					$this->isStructuredFilterUiEnabled() ?
						'rcfilters-watchlist-showupdated' :
						'wlheader-showupdated'
				)->parse() . $this->msg( 'word-separator' )->escaped();
			}
		}
		$form .= Html::rawElement(
			'div',
			[ 'class' => 'watchlistDetails' ],
			$watchlistHeader
		);

		if ( $numItems > 0 && $showUpdatedMarker ) {
			$form .= Html::openElement( 'form', [ 'method' => 'post',
				'action' => $this->getPageTitle()->getLocalURL(),
				'id' => 'mw-watchlist-resetbutton' ] ) . "\n" .
			Html::submitButton( $this->msg( 'enotif_reset' )->text(),
				[ 'name' => 'mw-watchlist-reset-submit' ] ) . "\n" .
			Html::hidden( 'token', $user->getEditToken() ) . "\n" .
			Html::hidden( 'reset', 'all' ) . "\n";
			foreach ( $nondefaults as $key => $value ) {
				$form .= Html::hidden( $key, $value ) . "\n";
			}
			$form .= Html::closeElement( 'form' ) . "\n";
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
			// not using Html::label because that would escape the contents
			Html::check( $name, (bool)$value, [ 'id' => $name ] ) . "\n" . Html::rawElement(
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
		$count = $this->watchedItemStore->countWatchedItems( $this->getUser() );
		return (int)floor( $count / 2 );
	}

	/**
	 * @param RecentChange $rc
	 * @return bool User viewed the revision or a newer one
	 */
	protected function isChangeEffectivelySeen( RecentChange $rc ) {
		$firstUnseen = $this->getLatestNotificationTimestamp( $rc );

		return ( $firstUnseen === null || $firstUnseen > $rc->getAttribute( 'rc_timestamp' ) );
	}

	/**
	 * @param RecentChange $rc
	 * @return string|null TS_MW timestamp of first unseen revision or null if there isn't one
	 */
	private function getLatestNotificationTimestamp( RecentChange $rc ) {
		return $this->watchedItemStore->getLatestNotificationTimestamp(
			$rc->getAttribute( 'wl_notificationtimestamp' ),
			$this->getUser(),
			$rc->getTitle()
		);
	}

	protected function getLimitPreferenceName(): string {
		return 'wllimit';
	}

	protected function getSavedQueriesPreferenceName(): string {
		return 'rcfilters-wl-saved-queries';
	}

	protected function getDefaultDaysPreferenceName(): string {
		return 'watchlistdays';
	}

	protected function getCollapsedPreferenceName(): string {
		return 'rcfilters-wl-collapsed';
	}

}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialWatchlist::class, 'SpecialWatchlist' );
