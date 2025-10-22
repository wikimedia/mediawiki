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
use MediaWiki\Title\TitleValue;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use ObjectCacheFactory;
use Psr\Log\LoggerInterface;
use stdClass;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\Platform\ISQLPlatform;
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

	/** @var ChangesListHighlight[] */
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

	/** @var bool If true, include fields for constructing RecentChange objects */
	private $recentChangeFields = false;

	/** @var string[] */
	private $watchlistFields = [];

	/** @var string|null The minimum or earliest timestamp */
	private $minTimestamp = null;

	/** @var int|null The maximum number of rows to return */
	private ?int $limit = null;

	/** @var float|int A naïve estimate of the fraction of rows matched by the conditions */
	private $density = 1;

	/** @var Authority|null The authority to use for deleted bitfield checks */
	private ?Authority $audience = null;

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
		private ObjectCacheFactory $objectCacheFactory,
		private StatsFactory $statsFactory,
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
			'subpageof' => new SubpageOfCondition(),
		];

		// ChangeTagsCondition consumes the density heuristic so it has to
		// be prepared after the other modules. Putting it late in the list
		// serves that purpose.
		$this->filterModules['changeTags'] = new ChangeTagsCondition(
			$this->changeTagsStore,
			$this->rcStats,
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
	public function requireSubpageOf( $page ) {
		$this->getFilter( 'subpageof' )->require( $page );
		return $this;
	}

	/**
	 * Require that the changed page is watched by the watchlist user specified
	 * in a call to watchlistUser().
	 *
	 * @return $this
	 */
	public function requireWatched() {
		$this->getWatchedFilter()->require( 'watched' );
		return $this;
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
	 * Add the change tag summary field ts_tags
	 *
	 * @return $this
	 */
	public function addChangeTagSummaryField(): self {
		$this->getChangeTagsFilter()->capture();
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
	 * Require that the change is viewable by the specified authority.
	 * Or pass null to disable authority checks.
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
		$this->highlights[$name] = new ChangesListHighlight( $sense, $moduleName, $value );
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

	private function getWatchedFilter(): WatchedCondition {
		return $this->filterModules['watched'];
	}

	private function getSeenFilter(): SeenCondition {
		return $this->filterModules['seen'];
	}

	private function getChangeTagsFilter(): ChangeTagsCondition {
		return $this->filterModules['changeTags'];
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
	 * Add fields to the query sufficient for the subsequent construction of
	 * RecentChange objects from the returned rows.
	 *
	 * @return $this
	 */
	public function recentChangeFields() {
		$this->recentChangeFields = true;
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
		$this->watchlistFields = $fields;
		return $this;
	}

	/**
	 * Add a callback which will be called when building an SQL query. It should
	 * have a signature like
	 *
	 *    function mutator( &$tables, &$fields, &$conds, &$join_conds, &$options )
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
			->setLabel( 'union', (string)count( $queries ) );

		if ( $shouldPartition ) {
			$timer->setLabel( 'strategy', 'partition' );
			$timer->start();
			$res = $this->doPartitionUnion( $queries );
			$timer->stop();
		} else {
			$timer->setLabel( 'strategy', 'simple' );
			$timer->start();
			$res = $this->maybeEmulateUnion( $queries );
			$timer->stop();
		}

		return $this->newResult( $res );
	}

	/**
	 * Call all modules asking them to populate fields, joins, etc.
	 */
	private function prepare() {
		if ( $this->linkTables ) {
			$this->adjustDensity( self::DENSITY_LINKS );
		}
		foreach ( $this->filterModules as $module ) {
			$module->prepareQuery( $this->db, $this );
		}
		if ( $this->recentChangeFields ) {
			$this->fields( RecentChange::getQueryInfo()['fields'] );
			$this->joinForFields( 'actor' )->straight();
			$this->joinForFields( 'comment' )->straight();
		}
		if ( $this->watchlistFields ) {
			$wlFields = array_diff( $this->watchlistFields, [ 'we_expiry' ] );
			$weFields = array_intersect( $this->watchlistFields, [ 'we_expiry' ] );
			if ( $wlFields ) {
				$this->fields( $wlFields );
			}
			$this->joinForFields( 'watchlist' )->weakLeft();
			if ( $weFields && $this->config->get( MainConfigNames::WatchlistExpiry ) ) {
				$this->fields( $weFields );
				$this->joinForFields( 'watchlist_expiry' )->weakLeft();
			}
		}
		if ( $this->audience ) {
			$this->prepareAudienceCondition( $this->audience );
		}
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
	 * @param Authority $authority
	 */
	private function prepareAudienceCondition( Authority $authority ) {
		// Log entries with DELETED_ACTION must not show up unless the user has
		// the necessary rights.
		if ( !$authority->isAllowed( 'deletedhistory' ) ) {
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
			// we have to GROUP BY the primary key. This in turn requires us to add
			// the primary key to the end of the ORDER BY, and the old ORDER BY to the
			// start of the GROUP BY.
			$sqb->groupBy( [ 'rc_timestamp', 'rc_id' ] )
				->orderBy( [ 'rc_timestamp DESC', 'rc_id DESC' ] );
		} else {
			$sqb->orderBy( 'rc_timestamp', SelectQueryBuilder::SORT_DESC );
		}
		$sqb->caller( $this->caller ?? __CLASS__ );
		if ( $this->limit !== null ) {
			$sqb->limit( $this->limit );
		}
		if ( $this->maxExecutionTime !== null ) {
			$sqb->setMaxExecutionTime( $this->maxExecutionTime );
		}
	}

	/**
	 * Add conditions on rc_timestamp
	 *
	 * @param SelectQueryBuilder $sqb
	 */
	private function applyTimestampFilter( SelectQueryBuilder $sqb ) {
		if ( $this->minTimestamp !== null ) {
			$sqb->andWhere( $this->db->expr( 'rc_timestamp', '>=',
				$this->db->timestamp( $this->minTimestamp ) ) );
		}
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
	 * @return bool
	 */
	private function shouldDoPartitioning(): bool {
		return $this->forcePartitioning
			|| ( $this->enablePartitioning
				&& $this->limit !== null
				&& $this->minTimestamp !== null
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
	 * @param SelectQueryBuilder $sqb
	 * @param stdClass[] &$rows
	 */
	private function doPartitionQuery( SelectQueryBuilder $sqb, &$rows ) {
		$queryHash = $this->getCondsHash( $sqb );
		$now = ConvertibleTimestamp::time();
		$minTime = (int)ConvertibleTimestamp::convert( TS_UNIX,
			$this->minTimestamp ?? $now - $this->rcMaxAge );
		$limit = $this->limit ?? 10_000;
		$rateStore = $this->newRateEstimator( $queryHash );
		$countsByBucket = [];
		$bucketPeriod = $rateStore->getBucketPeriod();
		$rate = $rateStore->fetchRate( $minTime, $now );
		$rcSize = $this->rcStats->getIdDelta();

		$this->logger->debug( 'Beginning partition request with ' .
			'rate={rate}, density={density}, period={period}',
			[
				'period' => $now - $minTime,
				'limit' => $limit,
				'rate' => $rate,
				'density' => $this->density,
				'rcSize' => $rcSize,
			]
		);

		$partitioner = new TimestampRangePartitioner( $minTime, $now, $limit,
			$rate, $this->density, $rcSize, $this->rcMaxAge );
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

			$time = null;
			$res = $partitionQuery->fetchResultSet();
			foreach ( $res as $row ) {
				$rows[] = $row;
				$time = (int)ConvertibleTimestamp::convert( TS_UNIX, $row->rc_timestamp );
				$bucket = (int)( $time / $bucketPeriod );
				$countsByBucket[$bucket] = ( $countsByBucket[$bucket] ?? 0 ) + 1;
			}
			$partitioner->notifyResult( $time, $res->numRows() );
		} while ( !$partitioner->isDone() );

		$rateStore->storeCounts(
			$countsByBucket,
				$partitioner->getMinFoundTime() ?? $minTime,
			$now
		);

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
	 * Get a string hash describing the query conditions
	 *
	 * @param SelectQueryBuilder $sqb
	 * @return string
	 */
	private function getCondsHash( SelectQueryBuilder $sqb ): string {
		$info = $sqb->getQueryInfo();
		$blob = $this->db->makeList( $info['conds'], ISQLPlatform::LIST_AND );
		foreach ( $info['join_conds'] as $join ) {
			if ( is_string( $join[1] ) ) {
				$joinCond = $join[1];
			} else {
				$joinCond = $this->db->makeList( $join[1], ISQLPlatform::LIST_AND );
			}
			$blob .= ' AND ' . $joinCond;
		}
		return md5( $blob );
	}

	private function newRateEstimator( string $key ): QueryRateEstimator {
		return new QueryRateEstimator(
			$this->objectCacheFactory->getLocalClusterInstance(),
			$this->rcMaxAge,
			$key
		);
	}

	/**
	 * This is called from ChangesListResult (via a closure) to evaluate the
	 * highlights.
	 *
	 * @param stdClass $row
	 * @return array<string,bool>
	 */
	private function getHighlightsFromRow( stdClass $row ) {
		$highlights = [];
		foreach ( $this->highlights as $name => $hl ) {
			$module = $this->getFilter( $hl->moduleName );
			if ( $module->evaluate( $row, $hl->value ) === $hl->sense ) {
				$highlights[$name] = true;
			}
		}
		return $highlights;
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
