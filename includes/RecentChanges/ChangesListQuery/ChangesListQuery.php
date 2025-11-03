<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use InvalidArgumentException;
use LogicException;
use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Linker\LinkTargetLookup;
use MediaWiki\Logging\LogPage;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
use MediaWiki\Permissions\Authority;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\RecentChanges\RecentChangeLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Storage\NameTableAccessException;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use Psr\Log\LoggerInterface;
use stdClass;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\RawSQLExpression;
use Wikimedia\Rdbms\SelectQueryBuilder;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\Timestamp\ConvertibleTimestamp;
use function array_key_exists;

/**
 * Build and execute a query on the recentchanges table, optionally with joins
 * and conditions.
 *
 * @since 1.45
 */
class ChangesListQuery implements QueryBackend, JoinDependencyProvider {
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::WatchlistExpiry,
		MainConfigNames::MiserMode,
		MainConfigNames::RCMaxAge,
		MainConfigNames::EnableChangesListQueryPartitioning,
		...ExperienceCondition::CONSTRUCTOR_OPTIONS
	];

	public const LINKS_FROM = 'from';
	public const LINKS_TO = 'to';

	private const LINK_TABLE_PREFIXES = [
		'pagelinks' => 'pl',
		'templatelinks' => 'tl',
		'categorylinks' => 'cl',
		'imagelinks' => 'il'
	];

	/** Minimum number of estimated rows before timestamp partitioning is considered */
	public const PARTITION_THRESHOLD = 10000;

	public const SORT_TIMESTAMP_DESC = 'timestamp-desc';
	public const SORT_TIMESTAMP_ASC = 'timestamp-asc';

	private int|float $rcMaxAge;
	private bool $enablePartitioning;
	private bool $forcePartitioning = false;

	private array $densityTunables = [
		self::DENSITY_LINKS => 0.1,
		self::DENSITY_WATCHLIST => 0.1,
		self::DENSITY_USER => 0.1,
		self::DENSITY_CHANGE_TAG_THRESHOLD => 0.5,
	];

	/** @var ChangesListCondition[] */
	private $filterModules;

	/** @var ChangesListJoinModule[] */
	private $joinModules;

	/** @var ChangesListHighlight[][] */
	private $highlights = [];

	/** @var string[] */
	private $fields = [];
	/** @var IExpression[] */
	private $conds = [];

	/** @var string|null */
	private $linkDirection = null;
	/** @var string[] */
	private $linkTables = [];
	/** @var PageIdentity|null */
	private $linkTarget = null;

	/** @var bool Whether the query was prepared for an emulated union */
	private $preparedEmulatedUnion = false;

	/**
	 * Internal functions to call during the prepare stage.
	 * @var array<string,callable>
	 */
	private $prepareCallbacks = [];

	/** @var string|null The minimum or earliest timestamp */
	private $minTimestamp = null;

	/** @var string|null The timestamp to start at */
	private $startTimestamp = null;
	/** @var int|null The ID to start at */
	private $startId = null;
	/** @var string|null The timestamp to end at */
	private $endTimestamp = null;
	/** @var int|null The ID to end at */
	private $endId = null;
	/** @var string The sort order */
	private $sort = self::SORT_TIMESTAMP_DESC;

	/** @var int|null The maximum number of rows to return */
	private ?int $limit = null;

	/** @var float|int A naïve estimate of the fraction of rows matched by the conditions */
	private $density = 1;

	/**
	 * @var string Whether recentchanges or some other table will likely be
	 *   first in the join.
	 */
	private $joinOrderHint = self::JOIN_ORDER_RECENTCHANGES;

	/** @var Authority|null The authority to use for deleted bitfield checks */
	private ?Authority $audience = null;

	/** @var bool Whether to exclude log entries with deleted actions */
	private $excludeDeletedAction = false;
	/** @var bool Whether to exclude rows with deleted users */
	private $excludeDeletedUser = false;

	/** @var float|int|null */
	private $maxExecutionTime = null;

	/** @var bool If true, return no results */
	private $forceEmptySet = false;

	/** @var bool If true, add DISTINCT and GROUP BY */
	private $distinct = false;

	/** @var string|null The caller to pass down to the DBMS */
	private ?string $caller = null;

	/** @var callable[] */
	private $legacyMutators = [];
	/** @var callable[] */
	private $sqbMutators = [];

	public function __construct(
		private ServiceOptions $config,
		private RecentChangeLookup $recentChangeLookup,
		private WatchedItemStoreInterface $watchedItemStore,
		private TempUserConfig $tempUserConfig,
		private UserFactory $userFactory,
		private LinkTargetLookup $linkTargetLookup,
		private ChangeTagsStore $changeTagsStore,
		private StatsFactory $statsFactory,
		private NameTableStore $slotRoleStore,
		private LoggerInterface $logger,
		private IReadableDatabase $db,
		private TableStatsProvider $rcStats,
	) {
		$this->filterModules = [
			'experience' => new ExperienceCondition(
				$config,
				$this->tempUserConfig,
				$this->userFactory,
			),
			'user' => new UserCondition(),
			'named' => new NamedCondition( $this->tempUserConfig ),
			'bot' => new BooleanFieldCondition( 'rc_bot' ),
			'minor' => new BooleanFieldCondition( 'rc_minor' ),
			'redirect' => new BooleanJoinFieldCondition( 'page_is_redirect', 'page' ),
			'revisionType' => new RevisionTypeCondition(),
			'source' => new EnumFieldCondition(
				'rc_source',
				$this->recentChangeLookup->getAllSources()
			),
			'logType' => new FieldEqualityCondition( 'rc_log_type', true ),
			'patrolled' => new EnumFieldCondition(
				'rc_patrolled',
				[
					RecentChange::PRC_UNPATROLLED,
					RecentChange::PRC_PATROLLED,
					RecentChange::PRC_AUTOPATROLLED,
				]
			),
			'watched' => new WatchedCondition(
				(bool)$config->get( MainConfigNames::WatchlistExpiry )
			),
			'seen' => new SeenCondition(
				$this->watchedItemStore
			),
			'namespace' => new FieldEqualityCondition( 'rc_namespace' ),
			'title' => new TitleCondition(),
			'subpageof' => new SubpageOfCondition(),
		];

		// ChangeTagsCondition consumes the density heuristic so it has to
		// be prepared after the other modules. Putting it late in the list
		// serves that purpose.
		$this->filterModules['changeTags'] = new ChangeTagsCondition(
			$this->changeTagsStore,
			$this->rcStats,
			$this->logger,
			(bool)$config->get( MainConfigNames::MiserMode ),
		);

		$this->joinModules = [
			'actor' => new BasicJoin( 'actor', 'recentchanges_actor', 'actor_id=rc_actor' ),
			'change_tag' => new BasicJoin(
				'change_tag',
				'changetagdisplay',
				'changetagdisplay.ct_rc_id=rc_id'
			),
			'comment' => new BasicJoin( 'comment', 'recentchanges_comment', 'comment_id=rc_comment_id' ),
			'page' => new BasicJoin( 'page', '', 'page_id=rc_cur_id' ),
			'revision' => new BasicJoin( 'revision', '', 'rev_id=rc_this_oldid' ),
			'slots' => new SlotsJoin(),
			'user' => new BasicJoin( 'user', '', 'user_id=actor_user', 'actor' ),
			'watchlist' => new WatchlistJoin(),
			'watchlist_expiry' => new BasicJoin( 'watchlist_expiry', '', 'we_item=wl_id', 'watchlist' )
		];

		$this->rcMaxAge = (int)$config->get( MainConfigNames::RCMaxAge );
		$this->enablePartitioning = (bool)$config->get( MainConfigNames::EnableChangesListQueryPartitioning );
	}

	/**
	 * Apply an arbitrary action. This is used to implement filter definitions
	 * in ChangesListSpecialPage. Other callers should add/use a separate
	 * mutator method. The details of the module names and values are internal
	 * and unstable.
	 *
	 * Note regarding implicit unions:
	 *
	 * Conventionally, if you require two things of the same kind, like two
	 * namespaces, you will get results matching either condition. But if you
	 * require two different kinds of condition, like a namespace and a minor
	 * edit, you will only get results matching both conditions. In other words,
	 * filter modules implement an implicit union of required values.
	 *
	 * However, exclusions intersect with requirements of the same kind, so if
	 * you require minor edits, and also exclude minor edits, you get no
	 * results.
	 *
	 * This convention is flexible, consistent, and works well with the UI.
	 *
	 * @internal
	 * @param string $verb May be "require" or "exclude"
	 * @param string $moduleName The name of the module, the thing to be required
	 * @param mixed $value An optional value to pass to the module
	 * @return $this
	 */
	public function applyAction( string $verb, string $moduleName, $value = null ) {
		$module = $this->getFilter( $moduleName );
		switch ( $verb ) {
			case 'require':
				$module->require( $value );
				break;
			case 'exclude':
				$module->exclude( $value );
				break;
			default:
				throw new InvalidArgumentException(
					"Unknown filter action verb: \"$verb\"" );
		}
		return $this;
	}

	/**
	 * Require namespaces by ID
	 *
	 * @param int[] $namespaces
	 * @return $this
	 */
	public function requireNamespaces( array $namespaces ) {
		return $this->applyArrayAction( 'require', 'namespace', $namespaces );
	}

	/**
	 * Exclude namespaces by ID
	 *
	 * @param int[] $namespaces
	 * @return $this
	 */
	public function excludeNamespaces( array $namespaces ) {
		return $this->applyArrayAction( 'exclude', 'namespace', $namespaces );
	}

	/**
	 * Apply an action multiple times, once for each of the values in the array
	 *
	 * @param string $verb
	 * @param string $moduleName
	 * @param array $values
	 * @return $this
	 */
	private function applyArrayAction( string $verb, string $moduleName, array $values ) {
		foreach ( $values as $value ) {
			$this->applyAction( $verb, $moduleName, $value );
		}
		return $this;
	}

	/**
	 * Require that changed titles are subpages of a given page.
	 *
	 * @param LinkTarget|PageReference $page
	 * @return $this
	 */
	public function requireSubpageOf( LinkTarget|PageReference $page ) {
		$this->getSubpageOfCondition()->require( $page );
		return $this;
	}

	/**
	 * Return only changes to a given page.
	 *
	 * @param LinkTarget|PageReference $title
	 * @return $this
	 */
	public function requireTitle( LinkTarget|PageReference $title ) {
		$this->getTitleCondition()->require( $title );
		return $this;
	}

	/**
	 * Require that the changed page is watched by the watchlist user specified
	 * in a call to watchlistUser().
	 *
	 * @param string[] $watchTypes
	 * @return $this
	 */
	public function requireWatched( $watchTypes = [ 'watchedold', 'watchednew' ] ) {
		return $this->applyArrayAction( 'require', 'watched', $watchTypes );
	}

	/**
	 * Require that the changed page links from or to the specified page, via
	 * the specified links tables.
	 *
	 * @param string $direction Either self::LINKS_FROM or self::LINKS_TO
	 * @param string[] $tables
	 * @param PageIdentity $page
	 * @return $this
	 */
	public function requireLink( string $direction, array $tables, PageIdentity $page ) {
		if ( count( $tables ) == 0 ) {
			throw new InvalidArgumentException( 'Need at least one link table' );
		}
		$unknownTables = array_diff( $tables, array_keys( self::LINK_TABLE_PREFIXES ) );
		if ( $unknownTables ) {
			throw new InvalidArgumentException( 'Unknown link table(s): ' .
				implode( ', ', $unknownTables ) );
		}

		$this->linkDirection = $direction;
		$this->linkTables = $tables;
		$this->linkTarget = $page;
		return $this;
	}

	/**
	 * Require that the changes come from the specified sources, e.g. RecentChange::SRC_EDIT
	 *
	 * @param array $sources
	 * @return $this
	 */
	public function requireSources( array $sources ): self {
		return $this->applyArrayAction( 'require', 'source', $sources );
	}

	/**
	 * Require changes by a specific user.
	 *
	 * @param UserIdentity $user
	 * @return $this
	 */
	public function requireUser( UserIdentity $user ): self {
		$this->getUserFilter()->require( $user );
		return $this;
	}

	/**
	 * Exclude changes by a specific user.
	 *
	 * @param UserIdentity $user
	 * @return $this
	 */
	public function excludeUser( UserIdentity $user ): self {
		$this->getUserFilter()->exclude( $user );
		return $this;
	}

	/**
	 * Require a patrolled status.
	 *
	 * @param int $value One of the RecentChange::PRC_xxx constants
	 * @return $this
	 */
	public function requirePatrolled( $value ): self {
		$this->getPatrolledFilter()->require( $value );
		return $this;
	}

	/**
	 * Require that the change has one of the specified change tags.
	 *
	 * @param string[] $tagNames
	 * @return $this
	 */
	public function requireChangeTags( $tagNames ): self {
		return $this->applyArrayAction( 'require', 'changeTags', $tagNames );
	}

	/**
	 * Exclude changes matching any of the specified change tags.
	 *
	 * @param string[] $tagNames
	 * @return $this
	 */
	public function excludeChangeTags( $tagNames ): self {
		return $this->applyArrayAction( 'exclude', 'changeTags', $tagNames );
	}

	/**
	 * Require that the change is the latest change to the page.
	 * Changes that do not link to a page will not be shown.
	 *
	 * @return $this
	 */
	public function requireLatest(): self {
		$this->getRevisionTypeFilter()->require( 'latest' );
		return $this;
	}

	/**
	 * Exclude old revisions. Latest revisions and changes that do not
	 * link to a revision, such as log entries, are allowed by this filter.
	 *
	 * @return self
	 */
	public function excludeOldRevisions(): self {
		$this->getRevisionTypeFilter()->exclude( 'old' );
		return $this;
	}

	/**
	 * Require that a specified slot role was modified
	 *
	 * @param string $role
	 * @return $this
	 */
	public function requireSlotChanged( string $role ): self {
		try {
			$roleId = $this->slotRoleStore->getId( $role );
		} catch ( NameTableAccessException ) {
			// No revisions changed this role yet
			$this->forceEmptySet();
			return $this;
		}

		$this->prepareCallbacks['slotChanged'] = function () use ( $roleId ) {
			$slotsJoin = $this->getSlotsJoinModule();
			$slotsJoin->setRoleId( $roleId );
			$slotsJoin->forConds( $this )
				->left();

			$slotsJoin->parentAlias()
				->forConds()
				->left();

			// Detecting whether the slot has been touched as follows:
			// 1. if slot_origin=slot_revision_id then the slot has been newly created or edited
			// with this revision
			// 2. otherwise if the content of a slot is different to the content of its parent slot,
			// then the content of the slot has been changed in this revision
			// (probably by a revert)
			$this->where( $this->db->orExpr( [
				new RawSQLExpression( 'slot.slot_origin = slot.slot_revision_id' ),
				new RawSQLExpression( 'slot.slot_content_id != parent_slot.slot_content_id' ),
				$this->db->expr( 'slot.slot_content_id', '=', null )->and( 'parent_slot.slot_content_id', '!=', null ),
				$this->db->expr( 'slot.slot_content_id', '!=', null )->and( 'parent_slot.slot_content_id', '=', null ),
			] ) );
		};
		return $this;
	}

	/**
	 * Exclude rows relating to log entries that have the DELETED_ACTION bit
	 * set, unless the configured audience has permission to view such rows.
	 *
	 * @return $this
	 */
	public function excludeDeletedLogAction(): self {
		$this->excludeDeletedAction = true;
		return $this;
	}

	/**
	 * Override a previous call to excludeDeletedLogAction(), allowing deleted
	 * log rows to be shown.
	 *
	 * @return $this
	 */
	public function allowDeletedLogAction(): self {
		$this->excludeDeletedAction = false;
		return $this;
	}

	/**
	 * Exclude rows with the DELETED_USER bit set, unless the configured
	 * audience has permission to view such rows.
	 *
	 * @return $this
	 */
	public function excludeDeletedUser(): self {
		$this->excludeDeletedUser = true;
		return $this;
	}

	/**
	 * Set the minimum size of the recentchanges table at which change tag
	 * queries will be conditionally modified based on estimated density.
	 *
	 * @param float|int $threshold
	 * @return self
	 */
	public function denseRcSizeThreshold( $threshold ): self {
		$this->getChangeTagsFilter()->setDenseRcSizeThreshold( $threshold );
		return $this;
	}

	/**
	 * Set the Authority used for rc_deleted filters.
	 *
	 * @param Authority|null $authority
	 * @return $this
	 */
	public function audience( ?Authority $authority ) {
		$this->audience = $authority;
		return $this;
	}

	/**
	 * Add a highlight to the query. A highlight is a client-side evaluation of
	 * a filter condition, given a caller-defined name. Results are available
	 * via ChangesListResult::getHighlightsFromRow().
	 *
	 * If this is called more than once with the same name, the name will be
	 * available in the result if any of the actions matched.
	 *
	 * This has no effect if it is called after the query is executed.
	 *
	 * @internal For ChangesListSpecialPage. The module names and values are
	 *   internal and are subject to change.
	 *
	 * @param string $name The arbitrary highlight name
	 * @param string $verb The filter action verb, "require" or "exclude"
	 * @param string $moduleName The module name, e.g. "bot"
	 * @param mixed $value An optional value to pass to the filter module
	 * @return $this
	 */
	public function highlight( string $name, string $verb, string $moduleName, $value = null ) {
		$module = $this->getFilter( $moduleName );
		// Validate now while the responsible caller is in the stack
		$value = $module->validateValue( $value );
		$module->capture();
		$sense = match ( $verb ) {
			'require' => true,
			'exclude' => false,
		};
		$this->highlights[$name][] = new ChangesListHighlight( $sense, $moduleName, $value );
		return $this;
	}

	/**
	 * Set the minimum (earliest) rc_timestamp value.
	 *
	 * @param string $timestamp MW 14-char timestamp
	 * @return $this
	 */
	public function minTimestamp( $timestamp ) {
		$this->minTimestamp = $timestamp;
		return $this;
	}

	/**
	 * Set the timestamp and ID for the start of the query results. If the sort
	 * order is descending (the default) this is the maximum timestamp and ID.
	 * If the sort order is ascending, this is the minimum timestamp and ID. The
	 * ID, if specified, is used to break ties between results with equal
	 * timestamps.
	 *
	 * @param string $timestamp
	 * @param int|null $id
	 * @return $this
	 */
	public function startAt( string $timestamp, ?int $id = null ): self {
		$this->startTimestamp = $timestamp;
		$this->startId = $id;
		return $this;
	}

	/**
	 * Set the timestamp and ID for the end of the query results. If the sort
	 * order is descending (the default) this is the minimum timestamp and ID.
	 * If the sort order is ascending, this is the maximum timestamp and ID. The
	 * ID, if specified, is used to break ties between results with equal
	 * timestamps.
	 *
	 * @param string $timestamp
	 * @param int|null $id
	 * @return $this
	 */
	public function endAt( string $timestamp, ?int $id = null ): self {
		$this->endTimestamp = $timestamp;
		$this->endId = $id;
		return $this;
	}

	/**
	 * Set the sort order. Must be one of the SORT_xxx constants.
	 *
	 * @param string $sort
	 * @return $this
	 */
	public function orderBy( $sort ) {
		$this->sort = $sort;
		return $this;
	}

	/**
	 * Set the maximum number of rows to return.
	 *
	 * @param int $limit
	 * @return $this
	 */
	public function limit( int $limit ) {
		$this->limit = $limit;
		$this->getChangeTagsFilter()->setLimit( $limit );
		return $this;
	}

	/** @inheritDoc */
	public function adjustDensity( $density ): self {
		if ( is_string( $density ) ) {
			if ( isset( $this->densityTunables[$density] ) ) {
				$density = $this->densityTunables[$density];
			} else {
				throw new \InvalidArgumentException( "Unknown density \"$density\"" );
			}
		}
		$this->density *= $density;
		$this->getChangeTagsFilter()->setDensityThresholdReached(
			$this->density >= $this->densityTunables[self::DENSITY_CHANGE_TAG_THRESHOLD]
		);
		return $this;
	}

	/** @inheritDoc */
	public function joinOrderHint( $order ): self {
		$this->joinOrderHint = $order;
		return $this;
	}

	/**
	 * Set a flag forcing the query to return no rows when it is executed. Like
	 * adding a 0=1 condition.
	 *
	 * @return $this
	 */
	public function forceEmptySet(): self {
		$this->forceEmptySet = true;
		return $this;
	}

	/**
	 * Check whether forceEmptySet() has been called. Note that the query may
	 * still return no rows even if this is false.
	 *
	 * @return bool
	 */
	public function isEmptySet(): bool {
		return $this->forceEmptySet;
	}

	/**
	 * Set the maximum query execution time in seconds, or null to disable the
	 * time limit.
	 *
	 * @param float|int|null $time
	 * @return $this
	 */
	public function maxExecutionTime( float|int|null $time ) {
		$this->maxExecutionTime = $time;
		return $this;
	}

	/**
	 * Enable query partitioning by timestamp, overriding the config
	 *
	 * @return $this
	 */
	public function enablePartitioning(): self {
		$this->enablePartitioning = true;
		return $this;
	}

	/**
	 * Force partitioning, for testing
	 *
	 * @return $this
	 */
	public function forcePartitioning(): self {
		$this->forcePartitioning = true;
		return $this;
	}

	private function getWatchlistJoinModule(): WatchlistJoin {
		return $this->joinModules['watchlist'];
	}

	private function getWatchlistExpiryJoinModule(): BasicJoin {
		return $this->joinModules['watchlist_expiry'];
	}

	private function getSlotsJoinModule(): SlotsJoin {
		return $this->joinModules['slots'];
	}

	private function getUserFilter(): UserCondition {
		return $this->filterModules['user'];
	}

	private function getWatchedFilter(): WatchedCondition {
		return $this->filterModules['watched'];
	}

	private function getSeenFilter(): SeenCondition {
		return $this->filterModules['seen'];
	}

	private function getChangeTagsFilter(): ChangeTagsCondition {
		return $this->filterModules['changeTags'];
	}

	private function getRedirectFilter(): BooleanJoinFieldCondition {
		return $this->filterModules['redirect'];
	}

	private function getRevisionTypeFilter(): RevisionTypeCondition {
		return $this->filterModules['revisionType'];
	}

	private function getPatrolledFilter(): EnumFieldCondition {
		return $this->filterModules['patrolled'];
	}

	private function getTitleCondition(): TitleCondition {
		return $this->filterModules['title'];
	}

	private function getSubpageOfCondition(): SubpageOfCondition {
		return $this->filterModules['subpageof'];
	}

	/**
	 * Set the user to be used for watchlist joins.
	 *
	 * @param UserIdentity $user
	 * @return $this
	 */
	public function watchlistUser( UserIdentity $user ) {
		$this->getWatchlistJoinModule()->setUser( $user );
		$this->getSeenFilter()->setUser( $user );
		$this->getWatchedFilter()->setUser( $user );
		return $this;
	}

	/**
	 * Add fields to the query.
	 *
	 * @param string|string[] $fields
	 * @param-taint $fields exec_sql
	 * @return $this
	 */
	public function fields( $fields ): self {
		$fields = is_array( $fields ) ? $fields : [ $fields ];
		$this->fields = array_merge( $this->fields, $fields );
		return $this;
	}

	/** @inheritDoc */
	public function rcUserFields(): QueryBackend {
		$this->getJoin( 'actor' )->forFields( $this )->straight();
		$this->fields['rc_user'] = 'recentchanges_actor.actor_user';
		$this->fields['rc_user_text'] = 'recentchanges_actor.actor_name';
		return $this;
	}

	/**
	 * Add the change tag summary field ts_tags
	 *
	 * @return $this
	 */
	public function addChangeTagSummaryField(): self {
		$this->getChangeTagsFilter()->capture();
		return $this;
	}

	/**
	 * Add fields to the query sufficient for the subsequent construction of
	 * RecentChange objects from the returned rows.
	 *
	 * @return $this
	 */
	public function recentChangeFields() {
		$this->prepareCallbacks['recentChangeFields'] = function () {
			$this->fields( RecentChange::getQueryInfo()['fields'] );
			$this->joinForFields( 'actor' )->straight();
			$this->joinForFields( 'comment' )->straight();
		};
		return $this;
	}

	/**
	 * Add watchlist fields to the query, and the relevant join.
	 * If watchlist expiry is disabled, the we_expiry field will be omitted.
	 *
	 * @param string[] $fields The fields to add
	 * @return $this
	 */
	public function watchlistFields(
		$fields = [ 'wl_user', 'wl_notificationtimestamp', 'we_expiry' ]
	) {
		$this->prepareCallbacks['watchlistFields'] = function () use ( $fields ) {
			$wlFields = array_diff( $fields, [ 'we_expiry' ] );
			$weFields = array_intersect( $fields, [ 'we_expiry' ] );
			if ( $wlFields ) {
				$this->fields( $wlFields );
			}
			$this->joinForFields( 'watchlist' )->weakLeft();
			if ( $weFields && $this->config->get( MainConfigNames::WatchlistExpiry ) ) {
				$this->fields( $weFields );
				$this->joinForFields( 'watchlist_expiry' )->weakLeft();
			}
		};
		return $this;
	}

	/**
	 * Add the rev_deleted and rev_slot_pair fields, used by ApiQueryRecentChanges
	 * to deliver SHA-1 hashes for modified content.
	 *
	 * @return self
	 */
	public function sha1Fields() {
		$this->sqbMutators['sha1Fields'] = $this->applySha1Fields( ... );
		return $this;
	}

	private function applySha1Fields( SelectQueryBuilder $query ) {
		$pairExpr = $this->db->buildGroupConcat(
			$this->db->buildConcat( [ 'sr.role_name', $this->db->addQuotes( ':' ), 'c.content_sha1' ] ),
			','
		);
		$revSha1Subquery = $this->db->newSelectQueryBuilder()
			->select( [
				'rev_id',
				'rev_deleted',
				'rev_slot_pairs' => $pairExpr,
			] )
			->from( 'revision' )
			->join( 'slots', 's', [ 'rev_id = s.slot_revision_id' ] )
			->join( 'content', 'c', [ 's.slot_content_id = c.content_id' ] )
			->join( 'slot_roles', 'sr', [ 's.slot_role_id = sr.role_id' ] )
			->groupBy( [ 'rev_id', 'rev_deleted' ] )
			->caller( __METHOD__ );

		$query->leftJoin(
			$revSha1Subquery,
			'revsha1',
			[ 'rc_this_oldid = revsha1.rev_id' ]
		);
		$query->fields( [
			'rev_deleted' => 'revsha1.rev_deleted',
			'rev_slot_pairs' => 'revsha1.rev_slot_pairs'
		] );
	}

	/**
	 * Add the page_is_redirect field
	 *
	 * @return $this
	 */
	public function addRedirectField(): self {
		$this->getRedirectFilter()->capture();
		return $this;
	}

	/**
	 * Add CommentStore fields: rc_comment_text, rc_comment_data, rc_comment_cid.
	 *
	 * Note that recentChangeFields() also adds rc_comment_text and
	 * rc_comment_data, but for comment_id it uses the alias rc_comment_id.
	 * If you call both then you will get both aliases. But the joins will be
	 * deduplicated.
	 *
	 * @return $this
	 */
	public function commentFields(): self {
		$this->prepareCallbacks['commentFields'] = function () {
			$this->joinForFields( 'comment' )->straight();
			$this->fields( [
				'rc_comment_text' => 'recentchanges_comment.comment_text',
				'rc_comment_data' => 'recentchanges_comment.comment_data',
				'rc_comment_cid' => 'recentchanges_comment.comment_id'
			] );
		};
		return $this;
	}

	/**
	 * Add the we_expiry field and its related join, if watchlist expiry is enabled
	 *
	 * @return $this
	 */
	public function maybeAddWatchlistExpiryField(): self {
		if ( $this->config->get( MainConfigNames::WatchlistExpiry ) ) {
			$this->getWatchlistExpiryJoinModule()->forFields( $this )->weakLeft();
			$this->fields( 'we_expiry' );
		}
		return $this;
	}

	/**
	 * Add a callback which will be called when building an SQL query. It should
	 * have a signature like
	 *
	 *    function mutator( &$tables, &$fields, &$conds, &$options, &$join_conds )
	 *
	 * @see IReadableDatabase::select()
	 *
	 * The mutator function may either return void, or it may return false to
	 * indicate that the forceEmptySet flag should be set.
	 *
	 * This should not be used in new code.
	 *
	 * @param callable $callback
	 * @return $this
	 */
	public function legacyMutator( callable $callback ) {
		$this->legacyMutators[] = $callback;
		return $this;
	}

	/**
	 * Add a callback which will be called with a SelectQueryBuilder during
	 * query construction. It should have a signature like
	 *
	 *   function mutator( SelectQueryBuilder $queryBuilder ): void
	 *
	 * The parameter may optionally be passed by reference and may be
	 * reassigned.
	 *
	 * Instead consider integrating the functionality with this class.
	 *
	 * @param callable $callback
	 * @return $this
	 */
	public function sqbMutator( callable $callback ) {
		$this->sqbMutators[] = $callback;
		return $this;
	}

	/**
	 * Execute the query and return the result.
	 *
	 * @return ChangesListResult
	 */
	public function fetchResult(): ChangesListResult {
		$this->prepare();
		if ( $this->isEmptySet() ) {
			return $this->newResult();
		}

		$shouldPartition = $this->shouldDoPartitioning();
		if ( $shouldPartition ) {
			$this->prepareEmulatedUnion();
		}

		$sqb = $this->createQueryBuilder();
		if ( !$shouldPartition ) {
			$this->applyTimestampFilter( $sqb );
		}
		$sqb = $this->applyMutators( $sqb );
		if ( !$sqb || $this->isEmptySet() ) {
			return $this->newResult();
		}

		$queries = $this->applyLinkTarget( $sqb );

		$timer = $this->statsFactory->getTiming( 'ChangesListQuery_query_seconds' )
			->setLabel( 'caller', $this->caller ?? 'unknown' )
			->setLabel( 'union', (string)count( $queries ) )
			->start();

		if ( $shouldPartition ) {
			$timer->setLabel( 'strategy', 'partition' );
			$res = $this->doPartitionUnion( $queries );
		} else {
			$timer->setLabel( 'strategy', 'simple' );
			$res = $this->maybeEmulateUnion( $queries );
		}

		$timer->stop();

		return $this->newResult( $res );
	}

	/**
	 * Call all modules asking them to populate fields, joins, etc.
	 */
	private function prepare() {
		if ( $this->linkTables ) {
			$this->adjustDensity( self::DENSITY_LINKS )
				->joinOrderHint( self::JOIN_ORDER_OTHER );
		}
		foreach ( $this->filterModules as $module ) {
			$module->prepareQuery( $this->db, $this );
		}
		foreach ( $this->prepareCallbacks as $callback ) {
			$callback();
		}
		$this->prepareAudienceCondition( $this->audience );
		if ( count( $this->linkTables ) > 1 ) {
			$this->prepareEmulatedUnion();
		}
	}

	/**
	 * Add fields needed to support an emulated union
	 */
	private function prepareEmulatedUnion() {
		$this->preparedEmulatedUnion = true;
		$this->fields( [ 'rc_timestamp', 'rc_id' ] );
	}

	/**
	 * @param stdClass[]|IResultWrapper $rows
	 * @return ChangesListResult
	 */
	private function newResult( $rows = [] ): ChangesListResult {
		return new ChangesListResult( $rows, $this->getHighlightsFromRow( ... ) );
	}

	/**
	 * Add conditions such that rows that cannot be viewed by the given authority
	 * will not be returned.
	 *
	 * @param Authority|null $authority
	 */
	private function prepareAudienceCondition( ?Authority $authority ) {
		if ( $this->excludeDeletedUser ) {
			if ( !$authority || !$authority->isAllowed( 'deletedhistory' ) ) {
				$bitmask = RevisionRecord::DELETED_USER;
			} elseif ( !$authority->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
				$bitmask = RevisionRecord::DELETED_USER | RevisionRecord::DELETED_RESTRICTED;
			} else {
				$bitmask = 0;
			}
			if ( $bitmask ) {
				$this->where( new RawSQLExpression(
					$this->db->bitAnd( 'rc_deleted', $bitmask ) . " != $bitmask"
				) );
			}
		}
		if ( $this->excludeDeletedAction ) {
			// Log entries with DELETED_ACTION must not show up unless the user has
			// the necessary rights.
			if ( !$authority || !$authority->isAllowed( 'deletedhistory' ) ) {
				$bitmask = LogPage::DELETED_ACTION;
			} elseif ( !$authority->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
				$bitmask = LogPage::DELETED_ACTION | LogPage::DELETED_RESTRICTED;
			} else {
				$bitmask = 0;
			}
			if ( $bitmask ) {
				$this->where( $this->db->expr( 'rc_source', '!=', RecentChange::SRC_LOG )
					->orExpr( new RawSQLExpression(
						$this->db->bitAnd( 'rc_deleted', $bitmask ) . " != $bitmask"
					) )
				);
			}
		}
	}

	private function createQueryBuilder(): SelectQueryBuilder {
		$sqb = $this->db->newSelectQueryBuilder()
			->select( $this->getUniqueFields() )
			->from( 'recentchanges' )
			->where( $this->conds );

		$this->applyOptions( $sqb );

		foreach ( $this->joinModules as $join ) {
			$join->prepare( $sqb );
		}
		return $sqb;
	}

	/**
	 * Set the ORDER BY, LIMIT, etc. on a query
	 *
	 * @param SelectQueryBuilder $sqb
	 */
	private function applyOptions( SelectQueryBuilder $sqb ) {
		if ( $this->distinct ) {
			$sqb->distinct();
			// In order to prevent DISTINCT from causing query performance problems,
			// we have to GROUP BY the primary key.
			$sqb->groupBy( [ 'rc_timestamp', 'rc_id' ] );
		}
		$dir = $this->sort === self::SORT_TIMESTAMP_ASC ? 'ASC' : 'DESC';
		$sqb->orderBy( [ "rc_timestamp $dir", "rc_id $dir" ] );

		$sqb->caller( $this->caller ?? __CLASS__ );
		if ( $this->limit !== null ) {
			$sqb->limit( $this->limit );
		}
		if ( $this->maxExecutionTime !== null ) {
			$sqb->setMaxExecutionTime( $this->maxExecutionTime );
		}
	}

	/**
	 * Add conditions on rc_timestamp and rc_id
	 *
	 * @param SelectQueryBuilder $sqb
	 */
	private function applyTimestampFilter( SelectQueryBuilder $sqb ) {
		if ( $this->minTimestamp !== null ) {
			$sqb->andWhere( $this->db->expr( 'rc_timestamp', '>=',
				$this->db->timestamp( $this->minTimestamp ) ) );
		}
		$this->applyStartOrEnd( $sqb, true, $this->startTimestamp, $this->startId );
		$this->applyStartOrEnd( $sqb, false, $this->endTimestamp, $this->endId );
	}

	/**
	 * @param SelectQueryBuilder $sqb
	 * @param bool $isStart True for the start, false for the end
	 * @param string $ts
	 * @param int $id
	 */
	private function applyStartOrEnd( SelectQueryBuilder $sqb, $isStart, $ts, $id ) {
		if ( $ts === null ) {
			return;
		}
		$op = ( $isStart === ( $this->sort === self::SORT_TIMESTAMP_ASC ) ) ? '>=' : '<=';
		$conds = [ 'rc_timestamp' => $this->db->timestamp( $ts ) ];
		if ( $id !== null ) {
			$conds['rc_id'] = $id;
		}
		$sqb->andWhere( $this->db->buildComparison( $op, $conds ) );
	}

	/**
	 * Get the field list, and deduplicate it.
	 *
	 * @return array
	 */
	private function getUniqueFields() {
		$seen = [];
		$fields = [];
		foreach ( $this->fields as $index => $value ) {
			if ( is_numeric( $index ) ) {
				if ( !array_key_exists( $index, $seen ) ) {
					$fields[] = $value;
					$seen[$index] = true;
				}
			} else {
				$fields[$index] = $value;
			}
		}
		return $fields;
	}

	/**
	 * Apply mutator callbacks to a SelectQueryBuilder
	 *
	 * @param SelectQueryBuilder $sqb
	 * @return SelectQueryBuilder|null
	 */
	private function applyMutators( SelectQueryBuilder $sqb ) {
		if ( $this->legacyMutators ) {
			$queryInfo = $sqb->getQueryInfo();
			foreach ( $this->legacyMutators as $mutator ) {
				$ret = $mutator(
					$queryInfo['tables'],
					$queryInfo['fields'],
					$queryInfo['conds'],
					$queryInfo['options'],
					$queryInfo['join_conds']
				);
				if ( $ret === false ) {
					$this->forceEmptySet();
					return null;
				}
			}
			$sqb = $this->db->newSelectQueryBuilder()
				->queryInfo( $queryInfo );
		}
		foreach ( $this->sqbMutators as $mutator ) {
			// Note $sqb may be passed by reference and reassigned
			$mutator( $sqb );
		}
		return $sqb;
	}

	/**
	 * If necessary, generate a set of queries which each join on a link table,
	 * restricting the results to those that link from/to a page. The results
	 * from these queries need to be combined with an emulated union.
	 *
	 * @param SelectQueryBuilder $mainQueryBuilder
	 * @return SelectQueryBuilder[]
	 */
	private function applyLinkTarget( SelectQueryBuilder $mainQueryBuilder ) {
		if ( !$this->linkTarget ) {
			return [ $mainQueryBuilder ];
		}

		$queries = [];
		foreach ( $this->linkTables as $linkTable ) {
			$queryBuilder = clone $mainQueryBuilder;
			if ( $this->linkDirection === self::LINKS_TO ) {
				$ok = $this->applyLinksToCondition( $queryBuilder, $this->linkTarget, $linkTable );
			} else {
				$ok = $this->applyLinksFromCondition( $queryBuilder, $this->linkTarget, $linkTable );
			}
			if ( $ok ) {
				$queries[] = $queryBuilder;
			}
		}
		return $queries;
	}

	/**
	 * Add joins and conditions for links to a page.
	 *
	 * @param SelectQueryBuilder $queryBuilder
	 * @param PageIdentity $page
	 * @param string $linkTable
	 * @return bool True for OK, false to force an empty result set
	 */
	private function applyLinksToCondition(
		SelectQueryBuilder $queryBuilder,
		PageIdentity $page,
		string $linkTable
	) {
		$prefix = self::LINK_TABLE_PREFIXES[$linkTable];
		$queryBuilder->join( $linkTable, null, "rc_cur_id = {$prefix}_from" );
		if ( $linkTable === 'imagelinks' ) {
			// The imagelinks table has no xx_namespace field and has xx_to instead of xx_target_id
			if ( $page->getNamespace() !== NS_FILE ) {
				// No imagelinks to a non-image page
				return false;
			}
			$queryBuilder->where( $this->db->expr( 'il_to', '=', $page->getDBkey() ) );
		} else {
			$linkTarget = $page instanceof LinkTarget ? $page : TitleValue::newFromPage( $page );
			$targetId = $this->linkTargetLookup->getLinkTargetId( $linkTarget );
			if ( !$targetId ) {
				return false;
			}
			$queryBuilder->where( $this->db->expr( "{$prefix}_target_id", '=', $targetId ) );
		}
		return true;
	}

	/**
	 * Add joins and conditions for links from a page.
	 *
	 * @param SelectQueryBuilder $queryBuilder
	 * @param PageIdentity $page
	 * @param string $linkTable
	 * @return bool True for OK, false to force an empty result set
	 */
	private function applyLinksFromCondition(
		SelectQueryBuilder $queryBuilder,
		PageIdentity $page,
		string $linkTable
	) {
		if ( !$page->getId() ) {
			// No links from a non-existent page
			return false;
		}
		$prefix = self::LINK_TABLE_PREFIXES[$linkTable];
		$queryBuilder->where( [ "{$prefix}_from" => $page->getId() ] );
		if ( $linkTable == 'imagelinks' ) {
			$queryBuilder->join( 'imagelinks', null, "rc_title = il_to" )
				->where( [ "rc_namespace" => $page->getNamespace() ] );
		} else {
			$queryBuilder
				->join( 'linktarget', null,
					[ 'rc_namespace = lt_namespace', 'rc_title = lt_title' ] )
				->join( $linkTable, null, "{$prefix}_target_id = lt_id" );
		}
		return true;
	}

	/**
	 * If there is one query, run it directly. Otherwise, emulate a union.
	 *
	 * @param SelectQueryBuilder[] $queries
	 * @return IResultWrapper|stdClass[]
	 */
	private function maybeEmulateUnion( $queries ) {
		if ( !$queries ) {
			return [];
		} elseif ( count( $queries ) === 1 ) {
			return $queries[0]->fetchResultSet();
		} else {
			$rows = [];
			$this->emulateUnion( $queries, $this->limit, $rows );
			return $rows;
		}
	}

	/**
	 * Perform a set of queries and merge the results as if a UNION were done.
	 *
	 * @param SelectQueryBuilder[] $queries
	 * @param int|null $limit
	 * @param stdClass[] &$rows
	 */
	private function emulateUnion( array $queries, ?int $limit, &$rows ) {
		if ( !$this->preparedEmulatedUnion ) {
			throw new LogicException(
				'emulateUnion() was called but not prepareEmulatedUnion()' );
		}
		$unsortedRows = [];
		foreach ( $queries as $query ) {
			foreach ( $query->fetchResultSet() as $row ) {
				$unsortedRows[] = $row;
			}
		}
		$this->sortAndTruncate( $unsortedRows, $limit, $rows );
	}

	/**
	 * Sort rows by rc_timestamp/rc_id, remove any duplicates, and then truncate
	 * to the current query limit.
	 *
	 * @internal public for testing
	 * @param stdClass[] $inRows
	 * @param int|null $limit
	 * @param stdClass[] &$outRows
	 */
	public function sortAndTruncate( array $inRows, ?int $limit, &$outRows ) {
		usort( $inRows, static function ( $a, $b ) {
			if ( $a->rc_timestamp === $b->rc_timestamp ) {
				return $b->rc_id <=> $a->rc_id;
			} else {
				return $b->rc_timestamp <=> $a->rc_timestamp;
			}
		} );
		// Remove duplicates and slice
		$prevId = null;
		$numOut = 0;
		foreach ( $inRows as $row ) {
			if ( $prevId !== $row->rc_id ) {
				$outRows[] = $row;
				$numOut++;
				if ( $numOut === $limit ) {
					break;
				}
			}
			$prevId = $row->rc_id;
		}
	}

	/**
	 * Determine whether to partition the query by timestamp range.
	 *
	 * Partitioning is a useful strategy on Special:Watchlist and
	 * Special:RecentChangesLinked when the DB decides to put the other table
	 * first despite there being a large number of matching rows in it. The size
	 * of the resulting temporary table can be limited by choosing a small
	 * timestamp range.
	 *
	 * @return bool
	 */
	private function shouldDoPartitioning(): bool {
		return $this->forcePartitioning
			|| ( $this->enablePartitioning
				&& $this->limit !== null
				&& $this->minTimestamp !== null
				&& $this->joinOrderHint === self::JOIN_ORDER_OTHER
				&& $this->sort === self::SORT_TIMESTAMP_DESC
				&& $this->estimateSize() > self::PARTITION_THRESHOLD
			);
	}

	/**
	 * Estimate the number of rows likely to be matched by the query, ignoring
	 * the limit.
	 *
	 * @return float|int
	 */
	private function estimateSize() {
		$now = ConvertibleTimestamp::time();
		$min = (int)ConvertibleTimestamp::convert( TS_UNIX, $this->minTimestamp );
		$period = min( $now - $min, $this->rcMaxAge );
		return $this->rcStats->getIdDelta() * $this->density * $period;
	}

	/**
	 * @param SelectQueryBuilder[] $queries
	 * @return stdClass[]
	 */
	private function doPartitionUnion( array $queries ) {
		if ( !$this->preparedEmulatedUnion ) {
			throw new LogicException(
				'doPartitionUnion() was called but not prepareEmulatedUnion()' );
		}
		$unsortedRows = [];
		foreach ( $queries as $query ) {
			$this->doPartitionQuery( $query, $unsortedRows );
		}
		if ( count( $queries ) > 1 ) {
			$rows = [];
			$this->sortAndTruncate( $unsortedRows, $this->limit, $rows );
			return $rows;
		} else {
			return $unsortedRows;
		}
	}

	/**
	 * Partition a query into timestamp ranges and run it separately on each
	 * range, building up the result.
	 *
	 * @see shouldDoPartitioning
	 *
	 * @param SelectQueryBuilder $sqb
	 * @param stdClass[] &$rows
	 */
	private function doPartitionQuery( SelectQueryBuilder $sqb, &$rows ) {
		$now = ConvertibleTimestamp::time();
		$minTime = (int)ConvertibleTimestamp::convert( TS_UNIX,
			$this->minTimestamp ?? $now - $this->rcMaxAge );
		$limit = $this->limit ?? 10_000;
		$rcSize = $this->rcStats->getIdDelta();

		$this->logger->debug( 'Beginning partition request with density={density}, period={period}',
			[
				'period' => $now - $minTime,
				'limit' => $limit,
				'density' => $this->density,
				'rcSize' => $rcSize,
			]
		);

		$partitioner = new TimestampRangePartitioner( $minTime, $now, $limit,
			null, $this->density, $rcSize, $this->rcMaxAge );
		do {
			[ $min, $max, $limit ] = $partitioner->getNextPartition();

			$partitionQuery = clone $sqb;
			if ( $min !== null ) {
				$partitionQuery->where( $this->db->expr(
					'rc_timestamp', '>=', $this->db->timestamp( $min ) ) );
			}
			if ( $max !== null ) {
				$partitionQuery->where( $this->db->expr(
					'rc_timestamp', '<=', $this->db->timestamp( $max ) ) );
			}
			$partitionQuery->limit( $limit );

			$row = null;
			$res = $partitionQuery->fetchResultSet();
			foreach ( $res as $row ) {
				$rows[] = $row;
			}
			$partitioner->notifyResult(
				$row ? (int)ConvertibleTimestamp::convert( TS_UNIX, $row->rc_timestamp ) : null,
				$res->numRows()
			);
		} while ( !$partitioner->isDone() );

		$m = $partitioner->getMetrics();
		$this->logger->debug( 'Finished partition request: ' .
			'got {actualRows} rows in {queryCount} queries, period={actualPeriod}',
			$m
		);

		$this->statsFactory->getCounter( 'ChangesListQuery_partition_queries_total' )
			->incrementBy( $m['queryCount'] );
		$this->statsFactory->getCounter( 'ChangesListQuery_partition_requests_total' )
			->increment();
		$this->statsFactory->getCounter( 'ChangesListQuery_partition_rows_total' )
			->incrementBy( $m['actualRows' ] );
		$this->statsFactory->getCounter( 'ChangesListQuery_partition_overrun_total' )
			->incrementBy( $m['actualRows'] * ( $m['queryPeriod'] / $m['actualPeriod'] - 1 ) );
	}

	/**
	 * This is called from ChangesListResult (via a closure) to evaluate the
	 * highlights.
	 *
	 * @param stdClass $row
	 * @return array<string,bool>
	 */
	private function getHighlightsFromRow( stdClass $row ) {
		$activeHighlights = [];
		foreach ( $this->highlights as $name => $highlights ) {
			foreach ( $highlights as $hl ) {
				$module = $this->getFilter( $hl->moduleName );
				if ( $module->evaluate( $row, $hl->value ) === $hl->sense ) {
					$activeHighlights[$name] = true;
				}
			}
		}
		return $activeHighlights;
	}

	private function getFilter( string $name ): ChangesListCondition {
		if ( !isset( $this->filterModules[$name] ) ) {
			throw new InvalidArgumentException( "Unknown filter module \"$name\"" );
		}
		return $this->filterModules[$name];
	}

	/**
	 * Add a condition to the query
	 *
	 * @param IExpression $expr
	 * @return $this
	 */
	public function where( IExpression $expr ): self {
		$this->conds[] = $expr;
		return $this;
	}

	/**
	 * Set the caller name to be passed down to the DBMS
	 *
	 * @param string $caller
	 * @return $this
	 */
	public function caller( string $caller ): self {
		$this->caller = $caller;
		return $this;
	}

	/** @inheritDoc */
	public function joinForFields( string $table ): ChangesListJoinBuilder {
		return $this->getJoin( $table )->forFields( $this );
	}

	/** @inheritDoc */
	public function joinForConds( string $table ): ChangesListJoinBuilder {
		return $this->getJoin( $table )->forConds( $this );
	}

	private function getJoin( string $name ): ChangesListJoinModule {
		if ( !isset( $this->joinModules[$name] ) ) {
			throw new InvalidArgumentException( "Unknown join module \"$name\"" );
		}
		return $this->joinModules[$name];
	}

	/** @inheritDoc */
	public function distinct(): QueryBackend {
		$this->distinct = true;
		return $this;
	}

	/**
	 * @internal
	 * @param string $name
	 * @param ChangesListCondition $module
	 */
	public function registerFilter( $name, ChangesListCondition $module ) {
		$this->filterModules[$name] = $module;
	}

}
