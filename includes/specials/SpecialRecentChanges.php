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

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\Context\IContextSource;
use MediaWiki\Html\FormOptions;
use MediaWiki\Html\Html;
use MediaWiki\Language\MessageParser;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\RecentChanges\ChangesList;
use MediaWiki\RecentChanges\ChangesListBooleanFilter;
use MediaWiki\RecentChanges\ChangesListStringOptionsFilterGroup;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\SpecialPage\ChangesListSpecialPage;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserIdentityUtils;
use MediaWiki\Utils\MWTimestamp;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use OOUI\ButtonWidget;
use OOUI\HtmlSnippet;
use Wikimedia\HtmlArmor\HtmlArmor;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\RawSQLExpression;

/**
 * List of the last changes made to the wiki
 *
 * @ingroup RecentChanges
 * @ingroup SpecialPage
 */
class SpecialRecentChanges extends ChangesListSpecialPage {

	/** @var array */
	private $watchlistFilterGroupDefinition;

	private WatchedItemStoreInterface $watchedItemStore;
	private MessageParser $messageParser;
	private UserOptionsLookup $userOptionsLookup;

	/** @var int */
	public $denseRcSizeThreshold = 10000;
	private ChangeTagsStore $changeTagsStore;

	public function __construct(
		?WatchedItemStoreInterface $watchedItemStore = null,
		?MessageParser $messageParser = null,
		?UserOptionsLookup $userOptionsLookup = null,
		?ChangeTagsStore $changeTagsStore = null,
		?UserIdentityUtils $userIdentityUtils = null,
		?TempUserConfig $tempUserConfig = null
	) {
		// This class is extended and therefor fallback to global state - T265310
		$services = MediaWikiServices::getInstance();

		parent::__construct(
			'Recentchanges',
			'',
			$userIdentityUtils ?? $services->getUserIdentityUtils(),
			$tempUserConfig ?? $services->getTempUserConfig()
		);
		$this->watchedItemStore = $watchedItemStore ?? $services->getWatchedItemStore();
		$this->messageParser = $messageParser ?? $services->getMessageParser();
		$this->userOptionsLookup = $userOptionsLookup ?? $services->getUserOptionsLookup();
		$this->changeTagsStore = $changeTagsStore ?? $services->getChangeTagsStore();

		$this->watchlistFilterGroupDefinition = [
			'name' => 'watchlist',
			'title' => 'rcfilters-filtergroup-watchlist',
			'class' => ChangesListStringOptionsFilterGroup::class,
			'priority' => -9,
			'isFullCoverage' => true,
			'filters' => [
				[
					'name' => 'watched',
					'label' => 'rcfilters-filter-watchlist-watched-label',
					'description' => 'rcfilters-filter-watchlist-watched-description',
					'cssClassSuffix' => 'watched',
					'isRowApplicableCallable' => static function ( IContextSource $ctx, RecentChange $rc ) {
						return $rc->getAttribute( 'wl_user' );
					}
				],
				[
					'name' => 'watchednew',
					'label' => 'rcfilters-filter-watchlist-watchednew-label',
					'description' => 'rcfilters-filter-watchlist-watchednew-description',
					'cssClassSuffix' => 'watchednew',
					'isRowApplicableCallable' => static function ( IContextSource $ctx, RecentChange $rc ) {
						return $rc->getAttribute( 'wl_user' ) &&
							$rc->getAttribute( 'rc_timestamp' ) &&
							$rc->getAttribute( 'wl_notificationtimestamp' ) &&
							$rc->getAttribute( 'rc_timestamp' ) >= $rc->getAttribute( 'wl_notificationtimestamp' );
					},
				],
				[
					'name' => 'notwatched',
					'label' => 'rcfilters-filter-watchlist-notwatched-label',
					'description' => 'rcfilters-filter-watchlist-notwatched-description',
					'cssClassSuffix' => 'notwatched',
					'isRowApplicableCallable' => static function ( IContextSource $ctx, RecentChange $rc ) {
						return $rc->getAttribute( 'wl_user' ) === null;
					},
				]
			],
			'default' => ChangesListStringOptionsFilterGroup::NONE,
			'queryCallable' => function ( string $specialClassName, IContextSource $ctx,
				IReadableDatabase $dbr, &$tables, &$fields, &$conds, &$query_options, &$join_conds, $selectedValues
			) {
				sort( $selectedValues );
				$notwatchedCond = $dbr->expr( 'wl_user', '=', null );
				$watchedCond = $dbr->expr( 'wl_user', '!=', null );
				if ( $this->getConfig()->get( MainConfigNames::WatchlistExpiry ) ) {
					// Expired watchlist items stay in the DB after their expiry time until they're purged,
					// so it's not enough to only check for wl_user.
					$dbNow = $dbr->timestamp();
					$notwatchedCond = $notwatchedCond
						->orExpr( $dbr->expr( 'we_expiry', '!=', null )->and( 'we_expiry', '<', $dbNow ) );
					$watchedCond = $watchedCond
						->andExpr( $dbr->expr( 'we_expiry', '=', null )->or( 'we_expiry', '>=', $dbNow ) );
				}
				$newCond = new RawSQLExpression( 'rc_timestamp >= wl_notificationtimestamp' );

				if ( $selectedValues === [ 'notwatched' ] ) {
					$conds[] = $notwatchedCond;
					return;
				}

				if ( $selectedValues === [ 'watched' ] ) {
					$conds[] = $watchedCond;
					return;
				}

				if ( $selectedValues === [ 'watchednew' ] ) {
					$conds[] = $watchedCond
						->andExpr( $newCond );
					return;
				}

				if ( $selectedValues === [ 'notwatched', 'watched' ] ) {
					// no filters
					return;
				}

				if ( $selectedValues === [ 'notwatched', 'watchednew' ] ) {
					$conds[] = $notwatchedCond
						->orExpr(
							$watchedCond
								->andExpr( $newCond )
						);
					return;
				}

				if ( $selectedValues === [ 'watched', 'watchednew' ] ) {
					$conds[] = $watchedCond;
					return;
				}

				if ( $selectedValues === [ 'notwatched', 'watched', 'watchednew' ] ) {
					// no filters
					return;
				}
			}
		];
	}

	/**
	 * @param string|null $subpage
	 */
	public function execute( $subpage ) {
		// Backwards-compatibility: redirect to new feed URLs
		$feedFormat = $this->getRequest()->getVal( 'feed' );
		if ( !$this->including() && $feedFormat ) {
			$query = $this->getFeedQuery();
			$query['feedformat'] = $feedFormat === 'atom' ? 'atom' : 'rss';
			$this->getOutput()->redirect( wfAppendQuery( wfScript( 'api' ), $query ) );

			return;
		}

		// 10 seconds server-side caching max
		$out = $this->getOutput();
		$out->setCdnMaxage( 10 );
		// Check if the client has a cached version
		$lastmod = $this->checkLastModified();
		if ( $lastmod === false ) {
			return;
		}

		$this->addHelpLink(
			'https://www.mediawiki.org/wiki/Special:MyLanguage/Help:Recent_changes',
			true
		);
		parent::execute( $subpage );
	}

	/**
	 * @inheritDoc
	 */
	protected function transformFilterDefinition( array $filterDefinition ) {
		if ( isset( $filterDefinition['showHideSuffix'] ) ) {
			$filterDefinition['showHide'] = 'rc' . $filterDefinition['showHideSuffix'];
		}

		return $filterDefinition;
	}

	/**
	 * Whether or not the current query needs to use watchlist data: check that the current user can
	 * use their watchlist and that this special page isn't being transcluded.
	 */
	private function needsWatchlistFeatures(): bool {
		return !$this->including()
			&& $this->getUser()->isRegistered()
			&& $this->getAuthority()->isAllowed( 'viewmywatchlist' );
	}

	/**
	 * @inheritDoc
	 */
	protected function registerFilters() {
		parent::registerFilters();

		if ( $this->needsWatchlistFeatures() ) {
			$this->registerFiltersFromDefinitions( [ $this->watchlistFilterGroupDefinition ] );
			$watchlistGroup = $this->getFilterGroup( 'watchlist' );
			$watchlistGroup->getFilter( 'watched' )->setAsSupersetOf(
				$watchlistGroup->getFilter( 'watchednew' )
			);
		}

		$user = $this->getUser();

		$significance = $this->getFilterGroup( 'significance' );
		/** @var ChangesListBooleanFilter $hideMinor */
		$hideMinor = $significance->getFilter( 'hideminor' );
		'@phan-var ChangesListBooleanFilter $hideMinor';
		$hideMinor->setDefault( $this->userOptionsLookup->getBoolOption( $user, 'hideminor' ) );

		$automated = $this->getFilterGroup( 'automated' );
		/** @var ChangesListBooleanFilter $hideBots */
		$hideBots = $automated->getFilter( 'hidebots' );
		'@phan-var ChangesListBooleanFilter $hideBots';
		$hideBots->setDefault( true );

		/** @var ChangesListStringOptionsFilterGroup|null $reviewStatus */
		$reviewStatus = $this->getFilterGroup( 'reviewStatus' );
		'@phan-var ChangesListStringOptionsFilterGroup|null $reviewStatus';
		if ( $reviewStatus !== null ) {
			// Conditional on feature being available and rights
			if ( $this->userOptionsLookup->getBoolOption( $user, 'hidepatrolled' ) ) {
				$reviewStatus->setDefault( 'unpatrolled' );
				$legacyReviewStatus = $this->getFilterGroup( 'legacyReviewStatus' );
				/** @var ChangesListBooleanFilter $legacyHidePatrolled */
				$legacyHidePatrolled = $legacyReviewStatus->getFilter( 'hidepatrolled' );
				'@phan-var ChangesListBooleanFilter $legacyHidePatrolled';
				$legacyHidePatrolled->setDefault( true );
			}
		}

		$changeType = $this->getFilterGroup( 'changeType' );
		/** @var ChangesListBooleanFilter $hideCategorization */
		$hideCategorization = $changeType->getFilter( 'hidecategorization' );
		'@phan-var ChangesListBooleanFilter $hideCategorization';
		if ( $hideCategorization !== null ) {
			// Conditional on feature being available
			$hideCategorization->setDefault( $this->userOptionsLookup->getBoolOption( $user, 'hidecategorization' ) );
		}
	}

	/**
	 * Process the subpage $par and put options found in $opts.
	 *
	 * This is a legacy feature predating query parameter emulation in the
	 * Parser which was introduced in MW 1.19. Before that time, it was
	 * necessary to do something like
	 *
	 *   {{Special:RecentChanges/days=3}}
	 *
	 * In MediaWiki 1.19+ you can do:
	 *
	 *   {{Special:RecentChanges | days=3}}
	 *
	 * The latter syntax allows the injection of any query parameter. So it is
	 * not necessary  to add new options here, users should be encouraged to
	 * use the latter syntax instead.
	 *
	 * @param string $par
	 * @param FormOptions $opts
	 */
	public function parseParameters( $par, FormOptions $opts ) {
		parent::parseParameters( $par, $opts );

		$bits = preg_split( '/\s*,\s*/', trim( $par ) );
		foreach ( $bits as $bit ) {
			if ( is_numeric( $bit ) ) {
				$opts['limit'] = $bit;
			}

			$m = [];
			if ( preg_match( '/^limit=(\d+)$/', $bit, $m ) ) {
				$opts['limit'] = $m[1];
			}
			if ( preg_match( '/^days=(\d+(?:\.\d+)?)$/', $bit, $m ) ) {
				$opts['days'] = $m[1];
			}
			if ( preg_match( '/^namespace=(.*)$/', $bit, $m ) ) {
				$opts['namespace'] = $m[1];
			}
			if ( preg_match( '/^tagfilter=(.*)$/', $bit, $m ) ) {
				$opts['tagfilter'] = $m[1];
			}
		}
	}

	/**
	 * Add required values to a query's $tables, $fields, $joinConds, and $conds arrays to join to
	 * the watchlist and watchlist_expiry tables where appropriate.
	 *
	 * SpecialRecentChangesLinked should also be updated accordingly when something changed here.
	 *
	 * @param IReadableDatabase $dbr
	 * @param string[] &$tables
	 * @param string[] &$fields
	 * @param mixed[] &$joinConds
	 * @param mixed[] &$conds
	 */
	protected function addWatchlistJoins( IReadableDatabase $dbr, &$tables, &$fields, &$joinConds, &$conds ) {
		if ( !$this->needsWatchlistFeatures() ) {
			return;
		}

		// Join on watchlist table.
		$tables[] = 'watchlist';
		$fields[] = 'wl_user';
		$fields[] = 'wl_notificationtimestamp';
		$joinConds['watchlist'] = [ 'LEFT JOIN', [
			'wl_user' => $this->getUser()->getId(),
			'wl_title=rc_title',
			'wl_namespace=rc_namespace'
		] ];

		// Exclude expired watchlist items.
		if ( $this->getConfig()->get( MainConfigNames::WatchlistExpiry ) ) {
			$tables[] = 'watchlist_expiry';
			$fields[] = 'we_expiry';
			$joinConds['watchlist_expiry'] = [ 'LEFT JOIN', 'wl_id = we_item' ];
		}
	}

	/**
	 * @inheritDoc
	 */
	protected function doMainQuery( $tables, $fields, $conds, $query_options,
		$join_conds, FormOptions $opts
	) {
		$dbr = $this->getDB();

		$rcQuery = RecentChange::getQueryInfo();
		$tables = array_merge( $rcQuery['tables'], $tables );
		$fields = array_merge( $rcQuery['fields'], $fields );
		$join_conds = array_merge( $rcQuery['joins'], $join_conds );

		// Join with watchlist and watchlist_expiry tables to highlight watched rows.
		$this->addWatchlistJoins( $dbr, $tables, $fields, $join_conds, $conds );

		// JOIN on page, used for 'last revision' filter highlight
		$tables[] = 'page';
		$fields[] = 'page_latest';
		$join_conds['page'] = [ 'LEFT JOIN', 'rc_cur_id=page_id' ];

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

		if ( !$this->runMainQueryHook( $tables, $fields, $conds, $query_options, $join_conds,
			$opts )
		) {
			return false;
		}

		if ( $this->areFiltersInConflict() ) {
			return false;
		}

		$orderByAndLimit = [
			'ORDER BY' => 'rc_timestamp DESC',
			'LIMIT' => $opts['limit']
		];

		// Workaround for T298225: MySQL's lack of awareness of LIMIT when
		// choosing the join order.
		$ctTableName = ChangeTags::DISPLAY_TABLE_ALIAS;
		if ( isset( $join_conds[$ctTableName] )
			&& $this->isDenseTagFilter( $conds["$ctTableName.ct_tag_id"] ?? [], $opts['limit'] )
		) {
			$join_conds[$ctTableName][0] = 'STRAIGHT_JOIN';
		}

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
		// they so desire, override the ORDER BY / LIMIT condition(s); prior to
		// MediaWiki 1.26 this used to use the plus operator instead, which meant
		// that extensions weren't able to change these conditions
		$query_options = array_merge( $orderByAndLimit, $query_options );
		return $dbr->newSelectQueryBuilder()
			->tables( $tables )
			->fields( $fields )
			->conds( $conds )
			->caller( __METHOD__ )
			->options( $query_options )
			->joinConds( $join_conds )
			->setMaxExecutionTime( $this->getConfig()->get( MainConfigNames::MaxExecutionTimeForExpensiveQueries ) )
			->fetchResultSet();
	}

	/**
	 * Determine whether a tag filter matches a high proportion of the rows in
	 * recentchanges. If so, it is more efficient to scan recentchanges,
	 * filtering out non-matching rows, rather than scanning change_tag and
	 * then filesorting on rc_timestamp. MySQL is especially bad at making this
	 * judgement (T298225).
	 *
	 * @param int[] $tagIds
	 * @param int $limit
	 * @return bool
	 */
	protected function isDenseTagFilter( $tagIds, $limit ) {
		$dbr = $this->getDB();
		if ( !$tagIds
			// This is a MySQL-specific hack
			|| $dbr->getType() !== 'mysql'
			// Unnecessary for small wikis
			|| !$this->getConfig()->get( MainConfigNames::MiserMode )
		) {
			return false;
		}

		$rcInfo = $dbr->newSelectQueryBuilder()
			->select( [
				'min_id' => 'MIN(rc_id)',
				'max_id' => 'MAX(rc_id)',
			] )
			->from( 'recentchanges' )
			->caller( __METHOD__ )
			->fetchRow();
		if ( !$rcInfo || $rcInfo->min_id === null ) {
			return false;
		}
		$rcSize = $rcInfo->max_id - $rcInfo->min_id;
		if ( $rcSize < $this->denseRcSizeThreshold ) {
			// RC is too small to worry about
			return false;
		}
		$tagCount = $dbr->newSelectQueryBuilder()
			->table( 'change_tag' )
			->where( [
				$dbr->expr( 'ct_rc_id', '>=', $rcInfo->min_id ),
				'ct_tag_id' => $tagIds
			] )
			->caller( __METHOD__ )
			->estimateRowCount();

		// If we scan recentchanges first, the number of rows examined will be
		// approximately the limit divided by the proportion of tagged rows,
		// i.e. $limit / ( $tagCount / $rcSize ). If that's less than $tagCount,
		// use a straight join. The inequality below is rearranged for
		// simplicity and to avoid division by zero.
		$isDense = $limit * $rcSize < $tagCount * $tagCount;

		wfDebug( __METHOD__ . ": rcSize = $rcSize, tagCount = $tagCount, limit = $limit => " .
			( $isDense ? 'dense' : 'sparse' ) );
		return $isDense;
	}

	public function outputFeedLinks() {
		$this->addFeedLinks( $this->getFeedQuery() );
	}

	/**
	 * Get URL query parameters for action=feedrecentchanges API feed of current recent changes view.
	 *
	 * @return array
	 */
	protected function getFeedQuery() {
		$query = array_filter( $this->getOptions()->getAllValues(), static function ( $value ) {
			// API handles empty parameters in a different way
			return $value !== '';
		} );
		$query['action'] = 'feedrecentchanges';
		$feedLimit = $this->getConfig()->get( MainConfigNames::FeedLimit );
		if ( $query['limit'] > $feedLimit ) {
			$query['limit'] = $feedLimit;
		}

		return $query;
	}

	/**
	 * Build and output the actual changes list.
	 *
	 * @param IResultWrapper $rows Database rows
	 * @param FormOptions $opts
	 */
	public function outputChangesList( $rows, $opts ) {
		$limit = $opts['limit'];

		$showWatcherCount = $this->getConfig()->get( MainConfigNames::RCShowWatchingUsers )
			&& $this->userOptionsLookup->getBoolOption( $this->getUser(), 'shownumberswatching' );
		$watcherCache = [];

		$counter = 1;
		$list = ChangesList::newFromContext( $this->getContext(), $this->filterGroups );
		$list->initChangesListRows( $rows );

		$userShowHiddenCats = $this->userOptionsLookup->getBoolOption( $this->getUser(), 'showhiddencats' );
		$rclistOutput = $list->beginRecentChangesList();
		if ( $this->isStructuredFilterUiEnabled() ) {
			$rclistOutput .= $this->makeLegend();
		}

		foreach ( $rows as $obj ) {
			if ( $limit == 0 ) {
				break;
			}
			$rc = RecentChange::newFromRow( $obj );

			# Skip CatWatch entries for hidden cats based on user preference
			if (
				$rc->getAttribute( 'rc_source' ) == RecentChange::SRC_CATEGORIZE &&
				!$userShowHiddenCats &&
				$rc->getParam( 'hidden-cat' )
			) {
				continue;
			}

			$rc->counter = $counter++;
			# Check if the page has been updated since the last visit
			if ( $this->getConfig()->get( MainConfigNames::ShowUpdatedMarker )
				&& !empty( $obj->wl_notificationtimestamp )
			) {
				$rc->notificationtimestamp = ( $obj->rc_timestamp >= $obj->wl_notificationtimestamp );
			} else {
				$rc->notificationtimestamp = false; // Default
			}
			# Check the number of users watching the page
			$rc->numberofWatchingusers = 0; // Default
			if ( $showWatcherCount && $obj->rc_namespace >= 0 ) {
				if ( !isset( $watcherCache[$obj->rc_namespace][$obj->rc_title] ) ) {
					$watcherCache[$obj->rc_namespace][$obj->rc_title] =
						$this->watchedItemStore->countWatchers(
							new TitleValue( (int)$obj->rc_namespace, $obj->rc_title )
						);
				}
				$rc->numberofWatchingusers = $watcherCache[$obj->rc_namespace][$obj->rc_title];
			}

			$watched = !empty( $obj->wl_user );
			if ( $watched && $this->getConfig()->get( MainConfigNames::WatchlistExpiry ) ) {
				$notExpired = $obj->we_expiry === null
					|| MWTimestamp::convert( TS_UNIX, $obj->we_expiry ) > wfTimestamp();
				$watched = $watched && $notExpired;
			}
			$changeLine = $list->recentChangesLine( $rc, $watched, $counter );
			if ( $changeLine !== false ) {
				$rclistOutput .= $changeLine;
				--$limit;
			}
		}
		$rclistOutput .= $list->endRecentChangesList();

		if ( $rows->numRows() === 0 ) {
			$this->outputNoResults();
			if ( !$this->including() ) {
				$this->getOutput()->setStatusCode( 404 );
			}
		} else {
			$this->getOutput()->addHTML( $rclistOutput );
		}
	}

	/**
	 * Set the text to be displayed above the changes
	 *
	 * @param FormOptions $opts
	 * @param int $numRows Number of rows in the result to show after this header
	 */
	public function doHeader( $opts, $numRows ) {
		$this->setTopText( $opts );

		$defaults = $opts->getAllValues();
		$nondefaults = $opts->getChangedValues();

		$panel = [];
		if ( !$this->isStructuredFilterUiEnabled() ) {
			$panel[] = $this->makeLegend();
		}
		$panel[] = $this->optionsPanel( $defaults, $nondefaults, $numRows );
		$panel[] = '<hr />';

		$extraOpts = $this->getExtraOptions( $opts );
		$extraOptsCount = count( $extraOpts );
		$count = 0;
		$submit = ' ' . Html::submitButton( $this->msg( 'recentchanges-submit' )->text() );

		$out = Html::openElement( 'table', [ 'class' => 'mw-recentchanges-table' ] );
		foreach ( $extraOpts as $name => $optionRow ) {
			# Add submit button to the last row only
			++$count;
			$addSubmit = ( $count === $extraOptsCount ) ? $submit : '';

			$out .= Html::openElement( 'tr', [ 'class' => $name . 'Form' ] );
			if ( is_array( $optionRow ) ) {
				$out .= Html::rawElement(
					'td',
					[ 'class' => [ 'mw-label', 'mw-' . $name . '-label' ] ],
					$optionRow[0]
				);
				$out .= Html::rawElement(
					'td',
					[ 'class' => 'mw-input' ],
					$optionRow[1] . $addSubmit
				);
			} else {
				$out .= Html::rawElement(
					'td',
					[ 'class' => 'mw-input', 'colspan' => 2 ],
					$optionRow . $addSubmit
				);
			}
			$out .= Html::closeElement( 'tr' );
		}
		$out .= Html::closeElement( 'table' );

		$unconsumed = $opts->getUnconsumedValues();
		foreach ( $unconsumed as $key => $value ) {
			$out .= Html::hidden( $key, $value );
		}

		$t = $this->getPageTitle();
		$out .= Html::hidden( 'title', $t->getPrefixedText() );
		$form = Html::rawElement( 'form', [ 'action' => wfScript() ], $out );
		$panel[] = $form;
		$panelString = implode( "\n", $panel );

		$rcoptions = Html::rawElement(
			'fieldset',
			[ 'class' => 'rcoptions cloptions' ],
			Html::element(
				'legend', [],
				$this->msg( 'recentchanges-legend' )->text()
			) . $panelString
		);

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
					$rcfilterContainer . $rcoptions
				)
			);

			// Add spinner
			$this->getOutput()->addHTML( $loadingContainer );
		} else {
			$this->getOutput()->addHTML( $rcoptions );
		}

		$this->setBottomText( $opts );
	}

	/**
	 * Send the text to be displayed above the options
	 *
	 * @param FormOptions $opts Unused
	 */
	public function setTopText( FormOptions $opts ) {
		$message = $this->msg( 'recentchangestext' )->inContentLanguage();
		if ( !$message->isDisabled() ) {
			$contLang = $this->getContentLanguage();
			// Parse the message in this weird ugly way to preserve the ability to include interlanguage
			// links in it (T172461). In the future when T66969 is resolved, perhaps we can just use
			// $message->parse() instead. This code is copied from Message::parseText().
			$parserOutput = $this->messageParser->parse(
				$message->plain(),
				$this->getPageTitle(),
				/*linestart*/ true,
				// Message class sets the interface flag to false when parsing in a language different than
				// user language, and this is wiki content language
				/*interface*/ false,
				$contLang
			);
			$content = $parserOutput->getContentHolderText();
			// Add only metadata here (including the language links), text is added below
			$this->getOutput()->addParserOutputMetadata( $parserOutput );

			$langAttributes = [
				'lang' => $contLang->getHtmlCode(),
				'dir' => $contLang->getDir(),
			];

			$topLinksAttributes = [ 'class' => 'mw-recentchanges-toplinks' ];

			if ( $this->isStructuredFilterUiEnabled() ) {
				// Check whether the widget is already collapsed or expanded
				$collapsedState = $this->getRequest()->getCookie( 'rcfilters-toplinks-collapsed-state' );
				// Note that an empty/unset cookie means collapsed, so check for !== 'expanded'
				$topLinksAttributes[ 'class' ] .= $collapsedState !== 'expanded' ?
					' mw-recentchanges-toplinks-collapsed' : '';

				$this->getOutput()->enableOOUI();
				$contentTitle = new ButtonWidget( [
					'classes' => [ 'mw-recentchanges-toplinks-title' ],
					'label' => new HtmlSnippet( $this->msg( 'rcfilters-other-review-tools' )->parse() ),
					'framed' => false,
					'indicator' => $collapsedState !== 'expanded' ? 'down' : 'up',
					'flags' => [ 'progressive' ],
				] );

				$contentWrapper = Html::rawElement( 'div',
					array_merge(
						[ 'class' => [ 'mw-recentchanges-toplinks-content', 'mw-collapsible-content' ] ],
						$langAttributes
					),
					$content
				);
				$content = $contentTitle . $contentWrapper;
			} else {
				// Language direction should be on the top div only
				// if the title is not there. If it is there, it's
				// interface direction, and the language/dir attributes
				// should be on the content itself
				$topLinksAttributes = array_merge( $topLinksAttributes, $langAttributes );
			}

			$this->getOutput()->addHTML(
				Html::rawElement( 'div', $topLinksAttributes, $content )
			);
		}
	}

	/**
	 * Get options to be displayed in a form
	 *
	 * @param FormOptions $opts
	 * @return array
	 */
	public function getExtraOptions( $opts ) {
		$opts->consumeValues( [
			'namespace', 'invert', 'associated', 'tagfilter', 'inverttags'
		] );

		$extraOpts = [];
		$extraOpts['namespace'] = $this->namespaceFilterForm( $opts );

		$tagFilter = ChangeTags::buildTagFilterSelector(
			$opts['tagfilter'], false, $this->getContext()
		);
		if ( $tagFilter ) {
			$tagFilter[1] .= ' ' . Html::rawElement( 'span', [ 'class' => [ 'mw-input-with-label' ] ],
				Html::element( 'input', [
					'type' => 'checkbox', 'name' => 'inverttags', 'value' => '1', 'checked' => $opts['inverttags'],
					'id' => 'inverttags'
				] ) . '&nbsp;' . Html::label( $this->msg( 'invert' )->text(), 'inverttags' )
			);
			$extraOpts['tagfilter'] = $tagFilter;
		}

		// Don't fire the hook for subclasses. (Or should we?)
		if ( $this->getName() === 'Recentchanges' ) {
			$this->getHookRunner()->onSpecialRecentChangesPanel( $extraOpts, $opts );
		}

		return $extraOpts;
	}

	/**
	 * Get last modified date, for client caching
	 * Don't use this if we are using the patrol feature, patrol changes don't
	 * update the timestamp
	 *
	 * @return string|bool
	 */
	public function checkLastModified() {
		$dbr = $this->getDB();
		$lastmod = $dbr->newSelectQueryBuilder()
			->select( 'MAX(rc_timestamp)' )
			->from( 'recentchanges' )
			->caller( __METHOD__ )->fetchField();

		return $lastmod;
	}

	/**
	 * Creates the choose namespace selection
	 *
	 * @param FormOptions $opts
	 * @return string[]
	 */
	protected function namespaceFilterForm( FormOptions $opts ) {
		$nsSelect = Html::namespaceSelector(
			[ 'selected' => $opts['namespace'], 'all' => '', 'in-user-lang' => true ],
			[ 'name' => 'namespace', 'id' => 'namespace' ]
		);
		$nsLabel = Html::label( $this->msg( 'namespace' )->text(), 'namespace' );
		$invert = Html::rawElement( 'label', [
			'class' => 'mw-input-with-label', 'title' => $this->msg( 'tooltip-invert' )->text(),
		], Html::element( 'input', [
			'type' => 'checkbox', 'name' => 'invert', 'value' => '1', 'checked' => $opts['invert'],
		] ) . '&nbsp;' . $this->msg( 'invert' )->escaped() );
		$associated = Html::rawElement( 'label', [
			'class' => 'mw-input-with-label', 'title' => $this->msg( 'tooltip-namespace_association' )->text(),
		], Html::element( 'input', [
			'type' => 'checkbox', 'name' => 'associated', 'value' => '1', 'checked' => $opts['associated'],
		] ) . '&nbsp;' . $this->msg( 'namespace_association' )->escaped() );

		return [ $nsLabel, "$nsSelect $invert $associated" ];
	}

	/**
	 * Makes change an option link which carries all the other options
	 *
	 * @param string $title
	 * @param array $override Options to override
	 * @param array $options Current options
	 * @param bool $active Whether to show the link in bold
	 * @return string
	 * Annotations needed to tell taint about HtmlArmor
	 * @param-taint $title escapes_html
	 */
	private function makeOptionsLink( $title, $override, $options, $active = false ) {
		$params = $this->convertParamsForLink( $override + $options );

		if ( $active ) {
			$title = new HtmlArmor( '<strong>' . htmlspecialchars( $title ) . '</strong>' );
		}

		return $this->getLinkRenderer()->makeKnownLink( $this->getPageTitle(), $title, [
			'data-params' => json_encode( $override ),
			'data-keys' => implode( ',', array_keys( $override ) ),
			'title' => false
		], $params );
	}

	/**
	 * Creates the options panel.
	 *
	 * @param array $defaults
	 * @param array $nondefaults
	 * @param int $numRows Number of rows in the result to show after this header
	 * @return string
	 */
	private function optionsPanel( $defaults, $nondefaults, $numRows ) {
		$options = $nondefaults + $defaults;

		$note = '';
		$msg = $this->msg( 'rclegend' );
		if ( !$msg->isDisabled() ) {
			$note .= Html::rawElement(
				'div',
				[ 'class' => 'mw-rclegend' ],
				$msg->parse()
			);
		}

		$lang = $this->getLanguage();
		$user = $this->getUser();
		$config = $this->getConfig();
		if ( $options['from'] ) {
			$resetLink = $this->makeOptionsLink( $this->msg( 'rclistfromreset' )->text(),
				[ 'from' => '' ], $nondefaults );

			$noteFromMsg = $this->msg( 'rcnotefrom' )
				->numParams( $options['limit'] )
				->params(
					$lang->userTimeAndDate( $options['from'], $user ),
					$lang->userDate( $options['from'], $user ),
					$lang->userTime( $options['from'], $user )
				)
				->numParams( $numRows );
			$note .= Html::rawElement(
					'span',
					[ 'class' => 'rcnotefrom' ],
					$noteFromMsg->parse()
				) .
				' ' .
				Html::rawElement(
					'span',
					[ 'class' => 'rcoptions-listfromreset' ],
					$this->msg( 'parentheses' )->rawParams( $resetLink )->parse()
				) .
				'<br />';
		}

		# Sort data for display and make sure it's unique after we've added user data.
		$linkLimits = $config->get( MainConfigNames::RCLinkLimits );
		$linkLimits[] = $options['limit'];
		sort( $linkLimits );
		$linkLimits = array_unique( $linkLimits );

		$linkDays = $this->getLinkDays();
		$linkDays[] = $options['days'];
		sort( $linkDays );
		$linkDays = array_unique( $linkDays );

		// limit links
		$cl = [];
		foreach ( $linkLimits as $value ) {
			$cl[] = $this->makeOptionsLink( $lang->formatNum( $value ),
				[ 'limit' => $value ], $nondefaults, $value == $options['limit'] );
		}
		$cl = $lang->pipeList( $cl );

		// day links, reset 'from' to none
		$dl = [];
		foreach ( $linkDays as $value ) {
			$dl[] = $this->makeOptionsLink( $lang->formatNum( $value ),
				[ 'days' => $value, 'from' => '' ], $nondefaults, $value == $options['days'] );
		}
		$dl = $lang->pipeList( $dl );

		$showhide = [ 'show', 'hide' ];

		$links = [];

		foreach ( $this->getLegacyShowHideFilters() as $key => $filter ) {
			if ( !MediaWikiServices::getInstance()
				->getPermissionManager()
				->isEveryoneAllowed( "edit" ) &&
				( $filter->getName() == "hideliu" || $filter->getName() == "hideanons" ) ) {
				continue;
			}
			$msg = $filter->getShowHide();
			$linkMessage = $this->msg( $msg . '-' . $showhide[1 - $options[$key]] );
			// Extensions can define additional filters, but don't need to define the corresponding
			// messages. If they don't exist, just fall back to 'show' and 'hide'.
			if ( !$linkMessage->exists() ) {
				$linkMessage = $this->msg( $showhide[1 - $options[$key]] );
			}

			$link = $this->makeOptionsLink( $linkMessage->text(),
				[ $key => 1 - $options[$key] ], $nondefaults );

			$attribs = [
				'class' => "$msg rcshowhideoption clshowhideoption",
				'data-filter-name' => $filter->getName(),
			];

			if ( $filter->isFeatureAvailableOnStructuredUi() ) {
				$attribs['data-feature-in-structured-ui'] = true;
			}

			$links[] = Html::rawElement(
				'span',
				$attribs,
				$this->msg( $msg )->rawParams( $link )->parse()
			);
		}

		// show from this onward link
		$timestamp = wfTimestampNow();
		$now = $lang->userTimeAndDate( $timestamp, $user );
		$timenow = $lang->userTime( $timestamp, $user );
		$datenow = $lang->userDate( $timestamp, $user );
		$pipedLinks = '<span class="rcshowhide">' . $lang->pipeList( $links ) . '</span>';

		$rclinks = Html::rawElement(
			'span',
			[ 'class' => 'rclinks' ],
			$this->msg( 'rclinks' )->rawParams( $cl, $dl, '' )->parse()
		);

		$rclistfrom = Html::rawElement(
			'span',
			[ 'class' => 'rclistfrom' ],
			$this->makeOptionsLink(
				$this->msg( 'rclistfrom' )->plaintextParams( $now, $timenow, $datenow )->text(),
				[ 'from' => $timestamp, 'fromFormatted' => $now ],
				$nondefaults
			)
		);

		return "{$note}$rclinks<br />$pipedLinks<br />$rclistfrom";
	}

	/** @inheritDoc */
	public function isIncludable() {
		return true;
	}

	/** @inheritDoc */
	protected function getCacheTTL() {
		return 60 * 5;
	}

	public function getDefaultLimit(): int {
		$systemPrefValue = $this->userOptionsLookup->getIntOption( $this->getUser(), 'rclimit' );
		// Prefer the RCFilters-specific preference if RCFilters is enabled
		if ( $this->isStructuredFilterUiEnabled() ) {
			return $this->userOptionsLookup->getIntOption(
				$this->getUser(), $this->getLimitPreferenceName(), $systemPrefValue
			);
		}

		// Otherwise, use the system rclimit preference value
		return $systemPrefValue;
	}

	protected function getLimitPreferenceName(): string {
		return 'rcfilters-limit'; // Use RCFilters-specific preference
	}

	protected function getSavedQueriesPreferenceName(): string {
		return 'rcfilters-saved-queries';
	}

	protected function getDefaultDaysPreferenceName(): string {
		return 'rcdays'; // Use general RecentChanges preference
	}

	protected function getCollapsedPreferenceName(): string {
		return 'rcfilters-rc-collapsed';
	}

}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialRecentChanges::class, 'SpecialRecentChanges' );
