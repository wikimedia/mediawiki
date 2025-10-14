<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use InvalidArgumentException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Logging\LogPage;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageReference;
use MediaWiki\Permissions\Authority;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\RecentChanges\RecentChangeLookup;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use stdClass;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\RawSQLExpression;
use Wikimedia\Rdbms\SelectQueryBuilder;
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
		...ExperienceCondition::CONSTRUCTOR_OPTIONS
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

	/** @var bool If true, include fields for constructing RecentChange objects */
	private $recentChangeFields = false;

	/** @var string[] */
	private $watchlistFields = [];

	/** @var string|null The minimum or earliest timestamp */
	private $minTimestamp = null;

	/** @var int|null The maximum number of rows to return */
	private ?int $limit = null;

	/** @var Authority|null The authority to use for deleted bitfield checks */
	private ?Authority $audience = null;

	/** @var float|int|null */
	private $maxExecutionTime = null;

	/** @var bool If true, return no results */
	private $forceEmptySet = false;

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
		private IReadableDatabase $db
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
				$config->get( MainConfigNames::WatchlistExpiry )
			),
			'seen' => new SeenCondition(
				$this->watchedItemStore
			),
			'namespace' => new FieldEqualityCondition( 'rc_namespace' ),
			'subpageof' => new SubpageOfCondition(),
		];
		$this->joinModules = [
			'actor' => new BasicJoin( 'actor', 'recentchanges_actor', 'actor_id=rc_actor' ),
			'comment' => new BasicJoin( 'comment', 'recentchanges_comment', 'comment_id=rc_comment_id' ),
			'user' => new BasicJoin( 'user', '', 'user_id=actor_user', 'actor' ),
			'page' => new BasicJoin( 'page', '', 'page_id=rc_cur_id' ),
			'watchlist' => new WatchlistJoin(),
			'watchlist_expiry' => new BasicJoin( 'watchlist_expiry', '', 'we_item=wl_id', 'watchlist' )
		];
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

	private function getWatchlistJoinModule(): WatchlistJoin {
		return $this->joinModules['watchlist'];
	}

	private function getWatchedFilter(): WatchedCondition {
		return $this->filterModules['watched'];
	}

	private function getSeenFilter(): SeenCondition {
		return $this->filterModules['seen'];
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

		if ( $this->isEmptySet() ) {
			$res = [];
		} else {
			$sqb = $this->createQueryBuilder();
			$sqb = $this->applyMutators( $sqb );
			if ( $sqb && !$this->isEmptySet() ) {
				$res = $sqb->fetchResultSet();
			} else {
				$res = [];
			}
		}

		return new ChangesListResult(
			$res,
			$this->getHighlightsFromRow( ... ),
		);
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
			->orderBy( 'rc_timestamp', SelectQueryBuilder::SORT_DESC )
			->where( $this->conds )
			->caller( $this->caller ?? __METHOD__ );

		if ( $this->minTimestamp !== null ) {
			$sqb->andWhere( $this->db->expr( 'rc_timestamp', '>=',
				$this->db->timestamp( $this->minTimestamp ) ) );
		}
		if ( $this->limit !== null ) {
			$sqb->limit( $this->limit );
		}
		if ( $this->maxExecutionTime !== null ) {
			$sqb->setMaxExecutionTime( $this->maxExecutionTime );
		}
		foreach ( $this->joinModules as $join ) {
			$join->prepare( $sqb );
		}
		return $sqb;
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

}
