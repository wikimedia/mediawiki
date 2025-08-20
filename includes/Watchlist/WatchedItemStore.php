<?php

namespace MediaWiki\Watchlist;

use DateInterval;
use InvalidArgumentException;
use LogicException;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\JobQueue\JobQueueGroup;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\User\UserIdentity;
use MediaWiki\Utils\MWTimestamp;
use stdClass;
use Wikimedia\MapCacheLRU\MapCacheLRU;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\ParamValidator\TypeDef\ExpiryDef;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\ReadOnlyMode;
use Wikimedia\Rdbms\SelectQueryBuilder;
use Wikimedia\ScopedCallback;
use Wikimedia\Timestamp\ConvertibleTimestamp;
use Wikimedia\Timestamp\TimestampFormat as TS;

/**
 * Storage layer class for WatchedItems.
 * Database interaction & caching
 * TODO caching should be factored out into a CachingWatchedItemStore class
 *
 * @author Addshore
 * @since 1.27
 */
class WatchedItemStore implements WatchedItemStoreInterface {

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::UpdateRowsPerQuery,
		MainConfigNames::WatchlistExpiry,
		MainConfigNames::WatchlistExpiryMaxDuration,
		MainConfigNames::WatchlistPurgeRate,
		MainConfigNames::EnableWatchlistLabels,
	];

	/**
	 * @var ILBFactory
	 */
	private $lbFactory;

	/**
	 * @var JobQueueGroup
	 */
	private $queueGroup;

	/**
	 * @var BagOStuff
	 */
	private $stash;

	/**
	 * @var ReadOnlyMode
	 */
	private $readOnlyMode;

	/**
	 * @var HashBagOStuff
	 */
	private $cache;

	/**
	 * @var HashBagOStuff
	 */
	private $latestUpdateCache;

	/**
	 * @var array[][] Looks like $cacheIndex[Namespace ID][Target DB Key][User Id] => 'key'
	 * The index is needed so that on mass changes all relevant items can be un-cached.
	 * For example: Clearing a users watchlist of all items or updating notification timestamps
	 *              for all users watching a single target.
	 * @phan-var array<int,array<string,array<int,string>>>
	 */
	private $cacheIndex = [];

	/**
	 * @var callable|null
	 */
	private $deferredUpdatesAddCallableUpdateCallback;

	/**
	 * @var int
	 */
	private $updateRowsPerQuery;

	/**
	 * @var NamespaceInfo
	 */
	private $nsInfo;

	/**
	 * @var RevisionLookup
	 */
	private $revisionLookup;

	/**
	 * @var bool Correlates to $wgWatchlistExpiry feature flag.
	 */
	private $expiryEnabled;

	private bool $labelsEnabled;

	/**
	 * @var LinkBatchFactory
	 */
	private $linkBatchFactory;

	/** @var WatchlistLabelStore */
	private $labelStore;

	/**
	 * @var string|null Maximum configured relative expiry.
	 */
	private $maxExpiryDuration;

	/** @var float corresponds to $wgWatchlistPurgeRate value */
	private $watchlistPurgeRate;

	public function __construct(
		ServiceOptions $options,
		ILBFactory $lbFactory,
		JobQueueGroup $queueGroup,
		BagOStuff $stash,
		HashBagOStuff $cache,
		ReadOnlyMode $readOnlyMode,
		NamespaceInfo $nsInfo,
		RevisionLookup $revisionLookup,
		LinkBatchFactory $linkBatchFactory,
		WatchlistLabelStore $labelStore,
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->updateRowsPerQuery = $options->get( MainConfigNames::UpdateRowsPerQuery );
		$this->expiryEnabled = $options->get( MainConfigNames::WatchlistExpiry );
		$this->maxExpiryDuration = $options->get( MainConfigNames::WatchlistExpiryMaxDuration );
		$this->watchlistPurgeRate = $options->get( MainConfigNames::WatchlistPurgeRate );
		$this->labelsEnabled = $options->get( MainConfigNames::EnableWatchlistLabels );
		$this->labelStore = $labelStore;

		$this->lbFactory = $lbFactory;
		$this->queueGroup = $queueGroup;
		$this->stash = $stash;
		$this->cache = $cache;
		$this->readOnlyMode = $readOnlyMode;
		$this->deferredUpdatesAddCallableUpdateCallback =
			DeferredUpdates::addCallableUpdate( ... );
		$this->nsInfo = $nsInfo;
		$this->revisionLookup = $revisionLookup;
		$this->linkBatchFactory = $linkBatchFactory;

		$this->latestUpdateCache = new HashBagOStuff( [ 'maxKeys' => 3 ] );
	}

	/**
	 * Overrides the DeferredUpdates::addCallableUpdate callback
	 * This is intended for use while testing and will fail if MW_PHPUNIT_TEST is not defined.
	 *
	 * @param callable $callback
	 *
	 * @see DeferredUpdates::addCallableUpdate for callback signature
	 */
	#[\NoDiscard]
	public function overrideDeferredUpdatesAddCallableUpdateCallback( callable $callback ): ScopedCallback {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new LogicException(
				'Cannot override DeferredUpdates::addCallableUpdate callback in operation.'
			);
		}
		$previousValue = $this->deferredUpdatesAddCallableUpdateCallback;
		$this->deferredUpdatesAddCallableUpdateCallback = $callback;
		return new ScopedCallback( function () use ( $previousValue ) {
			$this->deferredUpdatesAddCallableUpdateCallback = $previousValue;
		} );
	}

	/**
	 * @param UserIdentity $user
	 * @param PageReference $target
	 * @return string
	 */
	private function getCacheKey( UserIdentity $user, PageReference $target ): string {
		return $this->cache->makeKey(
			(string)$target->getNamespace(),
			$target->getDBkey(),
			(string)$user->getId()
		);
	}

	private function cache( WatchedItem $item ) {
		$user = $item->getUserIdentity();
		$target = $item->getTarget();
		$key = $this->getCacheKey( $user, $target );
		$this->cache->set( $key, $item );
		$this->cacheIndex[$target->getNamespace()][$target->getDBkey()][$user->getId()] = $key;
	}

	private function uncache( UserIdentity $user, PageReference $target ) {
		$this->cache->delete( $this->getCacheKey( $user, $target ) );
		unset( $this->cacheIndex[$target->getNamespace()][$target->getDBkey()][$user->getId()] );
	}

	private function uncacheTitle( PageReference $target ) {
		if ( !isset( $this->cacheIndex[$target->getNamespace()][$target->getDBkey()] ) ) {
			return;
		}

		foreach ( $this->cacheIndex[$target->getNamespace()][$target->getDBkey()] as $key ) {
			$this->cache->delete( $key );
		}
	}

	private function uncacheUser( UserIdentity $user ) {
		foreach ( $this->cacheIndex as $dbKeyArray ) {
			foreach ( $dbKeyArray as $userArray ) {
				if ( isset( $userArray[$user->getId()] ) ) {
					$this->cache->delete( $userArray[$user->getId()] );
				}
			}
		}

		$pageSeenKey = $this->getPageSeenTimestampsKey( $user );
		$this->latestUpdateCache->delete( $pageSeenKey );
		$this->stash->delete( $pageSeenKey );
	}

	/**
	 * @param UserIdentity $user
	 * @param PageReference $target
	 *
	 * @return WatchedItem|false
	 */
	private function getCached( UserIdentity $user, PageReference $target ) {
		return $this->cache->get( $this->getCacheKey( $user, $target ) );
	}

	/**
	 * Helper method to deduplicate logic around queries that need to be modified
	 * if watchlist expiration is enabled
	 *
	 * @param SelectQueryBuilder $queryBuilder
	 * @param IReadableDatabase $db
	 */
	private function modifyQueryBuilderForExpiry(
		SelectQueryBuilder $queryBuilder,
		IReadableDatabase $db
	) {
		if ( $this->expiryEnabled ) {
			$queryBuilder->where( $db->expr( 'we_expiry', '=', null )->or( 'we_expiry', '>', $db->timestamp() ) );
			$queryBuilder->leftJoin( 'watchlist_expiry', null, 'wl_id = we_item' );
		}
	}

	/**
	 * Add wlm_label_summary to a watchlist table query, giving a list of label IDs
	 *
	 * @param SelectQueryBuilder $queryBuilder
	 * @param IReadableDatabase $db
	 */
	private function addLabelSummaryField(
		SelectQueryBuilder $queryBuilder,
		IReadableDatabase $db
	) {
		$subquery = $db->newSelectQueryBuilder()
			->select( 'wlm_label' )
			->distinct()
			->from( 'watchlist_label_member' )
			->where( [ 'wlm_item=wl_id' ] )
			->buildGroupConcatField( ',' );
		$queryBuilder->fields( [ 'wlm_label_summary' => $subquery ] );
	}

	/**
	 * Deletes ALL watched items for the given user when under
	 * $updateRowsPerQuery entries exist.
	 *
	 * @since 1.30
	 *
	 * @param UserIdentity $user
	 *
	 * @return bool true on success, false when too many items are watched
	 */
	public function clearUserWatchedItems( UserIdentity $user ): bool {
		if ( $this->mustClearWatchedItemsUsingJobQueue( $user ) ) {
			return false;
		}

		$dbw = $this->lbFactory->getPrimaryDatabase();

		$ticket = $this->lbFactory->getEmptyTransactionTicket( __METHOD__ );
		// First fetch the wl_ids.
		$wlIds = $dbw->newSelectQueryBuilder()
			->select( 'wl_id' )
			->from( 'watchlist' )
			->where( [ 'wl_user' => $user->getId() ] )
			->caller( __METHOD__ )
			->fetchFieldValues();
		if ( $wlIds ) {
			// Delete rows from both the watchlist and watchlist_expiry tables.
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'watchlist' )
				->where( [ 'wl_id' => $wlIds ] )
				->caller( __METHOD__ )->execute();

			if ( $this->expiryEnabled ) {
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'watchlist_expiry' )
					->where( [ 'we_item' => $wlIds ] )
					->caller( __METHOD__ )->execute();
			}
			if ( $this->labelsEnabled ) {
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'watchlist_label_member' )
					->where( [ 'wlm_item' => $wlIds ] )
					->caller( __METHOD__ )->execute();
			}
		}
		$this->lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );
		$this->uncacheAllItemsForUser( $user );

		return true;
	}

	public function mustClearWatchedItemsUsingJobQueue( UserIdentity $user ): bool {
		return $this->countWatchedItems( $user ) > $this->updateRowsPerQuery;
	}

	private function uncacheAllItemsForUser( UserIdentity $user ) {
		$userId = $user->getId();
		foreach ( $this->cacheIndex as $ns => $dbKeyIndex ) {
			foreach ( $dbKeyIndex as $dbKey => $userIndex ) {
				if ( array_key_exists( $userId, $userIndex ) ) {
					$this->cache->delete( $userIndex[$userId] );
					unset( $this->cacheIndex[$ns][$dbKey][$userId] );
				}
			}
		}

		// Cleanup empty cache keys
		foreach ( $this->cacheIndex as $ns => $dbKeyIndex ) {
			foreach ( $dbKeyIndex as $dbKey => $userIndex ) {
				if ( empty( $this->cacheIndex[$ns][$dbKey] ) ) {
					unset( $this->cacheIndex[$ns][$dbKey] );
				}
			}
			if ( empty( $this->cacheIndex[$ns] ) ) {
				unset( $this->cacheIndex[$ns] );
			}
		}
	}

	/**
	 * Queues a job that will clear the users watchlist using the Job Queue.
	 *
	 * @since 1.31
	 *
	 * @param UserIdentity $user
	 */
	public function clearUserWatchedItemsUsingJobQueue( UserIdentity $user ) {
		$job = ClearUserWatchlistJob::newForUser( $user, $this->getMaxId() );
		$this->queueGroup->push( $job );
	}

	/**
	 * @inheritDoc
	 */
	public function maybeEnqueueWatchlistExpiryJob(): void {
		if ( !$this->expiryEnabled ) {
			// No need to purge expired entries if there are none
			return;
		}

		$max = mt_getrandmax();
		if ( mt_rand( 0, $max ) < $max * $this->watchlistPurgeRate ) {
			// The higher the watchlist purge rate, the more likely we are to enqueue a job.
			$this->queueGroup->lazyPush( new WatchlistExpiryJob() );
		}
	}

	/**
	 * @since 1.31
	 * @return int The maximum current wl_id
	 */
	public function getMaxId(): int {
		return (int)$this->lbFactory->getReplicaDatabase()->newSelectQueryBuilder()
			->select( 'MAX(wl_id)' )
			->from( 'watchlist' )
			->caller( __METHOD__ )
			->fetchField();
	}

	/**
	 * @since 1.31
	 * @param UserIdentity $user
	 * @return int
	 */
	public function countWatchedItems( UserIdentity $user ): int {
		$dbr = $this->lbFactory->getReplicaDatabase();
		$queryBuilder = $dbr->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'watchlist' )
			->where( [ 'wl_user' => $user->getId() ] )
			->caller( __METHOD__ );

		$this->modifyQueryBuilderForExpiry( $queryBuilder, $dbr );

		return (int)$queryBuilder->fetchField();
	}

	/**
	 * @since 1.27
	 * @param PageReference $target
	 * @return int
	 */
	public function countWatchers( PageReference $target ): int {
		$dbr = $this->lbFactory->getReplicaDatabase();
		$queryBuilder = $dbr->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'watchlist' )
			->where( [
				'wl_namespace' => $target->getNamespace(),
				'wl_title' => $target->getDBkey()
			] )
			->caller( __METHOD__ );

		$this->modifyQueryBuilderForExpiry( $queryBuilder, $dbr );

		return (int)$queryBuilder->fetchField();
	}

	/**
	 * @since 1.27
	 * @param PageReference $target
	 * @param string|int $threshold
	 * @return int
	 */
	public function countVisitingWatchers( PageReference $target, $threshold ): int {
		$dbr = $this->lbFactory->getReplicaDatabase();
		$queryBuilder = $dbr->newSelectQueryBuilder()
			->select( 'COUNT(*)' )
			->from( 'watchlist' )
			->where( [
				'wl_namespace' => $target->getNamespace(),
				'wl_title' => $target->getDBkey(),
				$dbr->expr( 'wl_notificationtimestamp', '>=', $dbr->timestamp( $threshold ) )
					->or( 'wl_notificationtimestamp', '=', null )
			] )
			->caller( __METHOD__ );

		$this->modifyQueryBuilderForExpiry( $queryBuilder, $dbr );

		return (int)$queryBuilder->fetchField();
	}

	/**
	 * @param UserIdentity $user
	 * @param PageReference[] $titles
	 * @return bool
	 */
	public function removeWatchBatchForUser( UserIdentity $user, array $titles ): bool {
		if ( !$user->isRegistered() || $this->readOnlyMode->isReadOnly() ) {
			return false;
		}
		if ( !$titles ) {
			return true;
		}

		$this->uncacheTitlesForUser( $user, $titles );

		$dbw = $this->lbFactory->getPrimaryDatabase();
		$ticket = count( $titles ) > $this->updateRowsPerQuery ?
			$this->lbFactory->getEmptyTransactionTicket( __METHOD__ ) : null;
		$affectedRows = 0;

		$wlIds = $this->loadIdsForTargets( $dbw, $user, $titles );
		foreach ( $this->batch( $wlIds ) as $ids ) {
			// Delete rows from the watchlist and associated tables.
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'watchlist' )
				->where( [ 'wl_id' => $ids ] )
				->caller( __METHOD__ )->execute();
			$affectedRows += $dbw->affectedRows();

			if ( $this->expiryEnabled ) {
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'watchlist_expiry' )
					->where( [ 'we_item' => $ids ] )
					->caller( __METHOD__ )->execute();
			}
			if ( $this->labelsEnabled ) {
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'watchlist_label_member' )
					->where( [ 'wlm_item' => $ids ] )
					->caller( __METHOD__ )->execute();
			}

			if ( $ticket ) {
				$this->lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );
			}
		}

		return (bool)$affectedRows;
	}

	/**
	 * @since 1.27
	 * @param PageReference[] $targets
	 * @param array $options Supported options are:
	 *  - 'minimumWatchers': filter for pages that have at least a minimum number of watchers
	 * @return array
	 */
	public function countWatchersMultiple( array $targets, array $options = [] ): array {
		$lb = $this->linkBatchFactory->newLinkBatch( $targets );
		$dbr = $this->lbFactory->getReplicaDatabase();
		$queryBuilder = $dbr->newSelectQueryBuilder();
		$queryBuilder
			->select( [ 'wl_title', 'wl_namespace', 'watchers' => 'COUNT(*)' ] )
			->from( 'watchlist' )
			->where( [ $lb->constructSet( 'wl', $dbr ) ] )
			->groupBy( [ 'wl_namespace', 'wl_title' ] )
			->caller( __METHOD__ );

		if ( array_key_exists( 'minimumWatchers', $options ) ) {
			$queryBuilder->having( 'COUNT(*) >= ' . (int)$options['minimumWatchers'] );
		}

		$this->modifyQueryBuilderForExpiry( $queryBuilder, $dbr );

		$res = $queryBuilder->fetchResultSet();

		$watchCounts = [];
		foreach ( $targets as $linkTarget ) {
			$watchCounts[$linkTarget->getNamespace()][$linkTarget->getDBkey()] = 0;
		}

		foreach ( $res as $row ) {
			$watchCounts[$row->wl_namespace][$row->wl_title] = (int)$row->watchers;
		}

		return $watchCounts;
	}

	/**
	 * @since 1.27
	 * @param array $targetsWithVisitThresholds array of pairs (PageReference,
	 *               last visit threshold)
	 * @param int|null $minimumWatchers
	 * @return int[][] two dimensional array, first is namespace, second is database key,
	 *                 value is the number of watchers
	 */
	public function countVisitingWatchersMultiple(
		array $targetsWithVisitThresholds,
		$minimumWatchers = null
	): array {
		if ( $targetsWithVisitThresholds === [] ) {
			// No titles requested => no results returned
			return [];
		}

		$dbr = $this->lbFactory->getReplicaDatabase();
		$queryBuilder = $dbr->newSelectQueryBuilder()
			->select( [ 'wl_namespace', 'wl_title', 'watchers' => 'COUNT(*)' ] )
			->from( 'watchlist' )
			->where( [ $this->getVisitingWatchersCondition( $dbr, $targetsWithVisitThresholds ) ] )
			->groupBy( [ 'wl_namespace', 'wl_title' ] )
			->caller( __METHOD__ );
		if ( $minimumWatchers !== null ) {
			$queryBuilder->having( 'COUNT(*) >= ' . (int)$minimumWatchers );
		}
		$this->modifyQueryBuilderForExpiry( $queryBuilder, $dbr );

		$res = $queryBuilder->fetchResultSet();

		$watcherCounts = [];
		foreach ( $targetsWithVisitThresholds as [ $target ] ) {
			$watcherCounts[$target->getNamespace()][$target->getDBkey()] = 0;
		}

		foreach ( $res as $row ) {
			$watcherCounts[$row->wl_namespace][$row->wl_title] = (int)$row->watchers;
		}

		return $watcherCounts;
	}

	/**
	 * Generates condition for the query used in a batch count visiting watchers.
	 *
	 * @param IReadableDatabase $db
	 * @param array $targetsWithVisitThresholds array of pairs (PageReference,
	 *              last visit threshold)
	 * @return string
	 */
	private function getVisitingWatchersCondition(
		IReadableDatabase $db,
		array $targetsWithVisitThresholds
	): string {
		$missingTargets = [];
		$namespaceConds = [];
		foreach ( $targetsWithVisitThresholds as [ $target, $threshold ] ) {
			if ( $threshold === null ) {
				$missingTargets[] = $target;
				continue;
			}
			/** @var PageReference $target */
			$namespaceConds[$target->getNamespace()][] = $db->expr( 'wl_title', '=', $target->getDBkey() )
				->andExpr(
					$db->expr( 'wl_notificationtimestamp', '>=', $db->timestamp( $threshold ) )
						->or( 'wl_notificationtimestamp', '=', null )
				);
		}

		$conds = [];
		foreach ( $namespaceConds as $namespace => $pageConds ) {
			$conds[] = $db->makeList( [
				'wl_namespace = ' . $namespace,
				'(' . $db->makeList( $pageConds, LIST_OR ) . ')'
			], LIST_AND );
		}

		if ( $missingTargets ) {
			$lb = $this->linkBatchFactory->newLinkBatch( $missingTargets );
			$conds[] = $lb->constructSet( 'wl', $db );
		}

		return $db->makeList( $conds, LIST_OR );
	}

	/**
	 * @since 1.27
	 * @param UserIdentity $user
	 * @param PageReference $target
	 * @return WatchedItem|false
	 */
	public function getWatchedItem( UserIdentity $user, PageReference $target ) {
		if ( !$user->isRegistered() ) {
			return false;
		}

		$cached = $this->getCached( $user, $target );
		if ( $cached && !$cached->isExpired() ) {
			return $cached;
		}
		return $this->loadWatchedItem( $user, $target );
	}

	/**
	 * @since 1.27
	 * @param UserIdentity $user
	 * @param PageReference $target
	 * @return WatchedItem|false
	 */
	public function loadWatchedItem( UserIdentity $user, PageReference $target ) {
		$item = $this->loadWatchedItemsBatch( $user, [ $target ] );
		return $item ? $item[0] : false;
	}

	/**
	 * @since 1.36
	 * @param UserIdentity $user
	 * @param PageReference[] $targets
	 * @return WatchedItem[]
	 */
	public function loadWatchedItemsBatch( UserIdentity $user, array $targets ) {
		// Only registered user can have a watchlist
		if ( !$user->isRegistered() ) {
			return [];
		}

		$dbr = $this->lbFactory->getReplicaDatabase();

		$rows = $this->fetchWatchedItemRows(
			$dbr,
			$user,
			$targets,
			[],
		);

		if ( $this->labelsEnabled && $rows->numRows() ) {
			$labels = $this->labelStore->loadAllForUser( $user );
		} else {
			$labels = [];
		}

		$items = [];
		foreach ( $rows as $row ) {
			$item = $this->getWatchedItemFromRow( $user, $row, $labels );
			$this->cache( $item );
			$items[] = $item;
		}

		return $items;
	}

	/**
	 * @since 1.27
	 * @param UserIdentity $user
	 * @param array $options Supported options are:
	 *  - 'forWrite': bool optional whether to use the primary database instead of a replica (defaults to false)
	 *  - 'sort': string optional self::SORT_ASC or self:SORT_DESC (defaults to self::SORT_ASC)
	 *  - 'offsetConds': optional array SQL conditions that the watched items must match
	 *  - 'namespaces': array
	 *  - 'limit': int max number of watched items to return
	 * @return WatchedItem[]
	 */
	public function getWatchedItemsForUser( UserIdentity $user, array $options = [] ): array {
		$options += [ 'forWrite' => false, 'sort' => self::SORT_ASC ];
		if ( $options['forWrite'] ) {
			$db = $this->lbFactory->getPrimaryDatabase();
		} else {
			$db = $this->lbFactory->getReplicaDatabase();
		}

		if ( $options['sort'] == self::SORT_ASC ) {
			$orderBy = [ 'wl_namespace', 'wl_title' ];
		} else {
			$orderBy = [ 'wl_namespace DESC', 'wl_title DESC' ];
		}
		return $this->fetchWatchedItems( $db, $user, $options, $orderBy );
	}

	/**
	 * @param IDatabase $db
	 * @param UserIdentity $user
	 * @param array $options Supported options are:
	 *  - 'offsetConds': array SQL conditions that the watched items must match
	 *  - 'limit': int max number of watched items to return
	 *  - 'namespaces': array
	 * @param array $orderBy SQL order by
	 * @param array $extraConditions SQL conditions
	 * @return WatchedItem[]
	 */
	private function fetchWatchedItems(
		IDatabase $db, UserIdentity $user, array $options, array $orderBy, array $extraConditions = []
	): array {
		$fetchOptions = [];
		$fetchOptions['orderBy'] = $orderBy;
		if ( isset( $options['limit'] ) ) {
			$fetchOptions['limit'] = $options['limit'];
		}
		if ( isset( $options['offsetConds'] ) ) {
			$offsetConds = is_array( $options['offsetConds'] )
				? $options['offsetConds'] :
				[ $options['offsetConds'] ];
			$extraConditions = array_merge( $extraConditions, $offsetConds );
		}
		if ( isset( $options['namespaces'] ) ) {
			$extraConditions['wl_namespace'] = $options['namespaces'];
		}
		$fetchOptions['extraConds'] = $extraConditions;
		$res = $this->fetchWatchedItemRows( $db, $user, null, $fetchOptions );

		// Load label names
		if ( $this->labelsEnabled && $res->numRows() ) {
			$labels = $this->labelStore->loadAllForUser( $user );
		} else {
			$labels = [];
		}

		$watchedItems = [];
		foreach ( $res as $row ) {
			$watchedItems[] = $this->getWatchedItemFromRow( $user, $row, $labels );
		}
		return $watchedItems;
	}

	/**
	 * Construct a new WatchedItem given a row from watchlist/watchlist_expiry.
	 * @param UserIdentity $user
	 * @param \stdClass $row
	 * @param WatchlistLabel[] $labelsForUser Labels for this user indexed by ID
	 * @return WatchedItem
	 */
	private function getWatchedItemFromRow(
		UserIdentity $user,
		stdClass $row,
		array $labelsForUser
	): WatchedItem {
		$target = PageReferenceValue::localReference( (int)$row->wl_namespace, $row->wl_title );
		if ( ( $row->wlm_label_summary ?? '' ) !== '' ) {
			$labelIds = explode( ',', $row->wlm_label_summary );
			$labels = array_intersect_key( $labelsForUser, array_fill_keys( $labelIds, true ) );
		} else {
			$labels = [];
		}
		return new WatchedItem(
			$user,
			$target,
			$this->getLatestNotificationTimestamp(
				$row->wl_notificationtimestamp, $user, $target ),
			wfTimestampOrNull( TS::ISO_8601, $row->we_expiry ?? null ),
			array_values( $labels )
		);
	}

	/**
	 * Fetches either a single or all watched items for the given user, or a specific set of items.
	 * If a $target is given, IDatabase::selectRow() is called, otherwise select().
	 * If $wgWatchlistExpiry is enabled, expired items are not returned.
	 *
	 * @param IReadableDatabase $db
	 * @param UserIdentity $user
	 * @param PageReference[]|null $target null if selecting all watched items
	 * @param array $options Supported options are:
	 *  - 'orderBy': an array of SQL `order by` strings
	 *  - 'extraConds': an array of SQL condition strings
	 *  - 'limit': integer value for use in an SQL `limit`
	 * @return IResultWrapper
	 */
	private function fetchWatchedItemRows(
		IReadableDatabase $db,
		UserIdentity $user,
		$target = null,
		array $options = [],
	) {
		$fieldNames = [ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp' ];
		if ( $this->expiryEnabled ) {
			$fieldNames[] = 'we_expiry';
		}
		$queryBuilder = $db->newSelectQueryBuilder()
			->select( $fieldNames )
			->from( 'watchlist' )
			->where( [ 'wl_user' => $user->getId() ] )
			->caller( __METHOD__ );
		if ( $target ) {
			$queryBuilder->where( $this->getTargetsCond( $target ) );
		}
		$this->modifyQueryBuilderForExpiry( $queryBuilder, $db );
		if ( $this->labelsEnabled ) {
			$this->addLabelSummaryField( $queryBuilder, $db );
		}

		if ( array_key_exists( 'orderBy', $options ) && is_array( $options['orderBy'] ) ) {
			$queryBuilder->orderBy( $options['orderBy'] );
		}
		if ( array_key_exists( 'extraConds', $options ) && is_array( $options['extraConds'] ) ) {
			$queryBuilder->where( $options['extraConds'] );
		}
		if ( array_key_exists( 'limit', $options ) && ( intval( $options['limit'] ) > 0 ) ) {
			$queryBuilder->limit( $options['limit'] );
		}

		return $queryBuilder->fetchResultSet();
	}

	/**
	 * @since 1.27
	 * @param UserIdentity $user
	 * @param PageReference $target
	 * @return bool
	 */
	public function isWatched( UserIdentity $user, PageReference $target ): bool {
		return (bool)$this->getWatchedItem( $user, $target );
	}

	/**
	 * Check if the user is temporarily watching the page.
	 * @since 1.35
	 * @param UserIdentity $user
	 * @param PageReference $target
	 * @return bool
	 */
	public function isTempWatched( UserIdentity $user, PageReference $target ): bool {
		$item = $this->getWatchedItem( $user, $target );
		return $item && $item->getExpiry();
	}

	/**
	 * @since 1.27
	 * @param UserIdentity $user
	 * @param PageReference[] $targets
	 * @return (string|null|false)[][] two dimensional array, first is namespace, second is database key,
	 *                 value is the notification timestamp or null, or false if not available
	 */
	public function getNotificationTimestampsBatch( UserIdentity $user, array $targets ): array {
		$timestamps = [];
		foreach ( $targets as $target ) {
			$timestamps[$target->getNamespace()][$target->getDBkey()] = false;
		}

		if ( !$user->isRegistered() ) {
			return $timestamps;
		}

		$targetsToLoad = [];
		foreach ( $targets as $target ) {
			$cachedItem = $this->getCached( $user, $target );
			if ( $cachedItem ) {
				$timestamps[$target->getNamespace()][$target->getDBkey()] =
					$cachedItem->getNotificationTimestamp();
			} else {
				$targetsToLoad[] = $target;
			}
		}

		if ( !$targetsToLoad ) {
			return $timestamps;
		}

		$dbr = $this->lbFactory->getReplicaDatabase();

		$res = $dbr->newSelectQueryBuilder()
			->select( [ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp' ] )
			->from( 'watchlist' )
			->where( [
				$this->getTargetsCond( $targetsToLoad ),
				'wl_user' => $user->getId(),
			] )
			->caller( __METHOD__ )
			->fetchResultSet();

		foreach ( $res as $row ) {
			$target = PageReferenceValue::localReference( (int)$row->wl_namespace, $row->wl_title );
			$timestamps[$row->wl_namespace][$row->wl_title] =
				$this->getLatestNotificationTimestamp(
					$row->wl_notificationtimestamp, $user, $target );
		}

		return $timestamps;
	}

	/**
	 * @since 1.27 Method added.
	 * @since 1.35 Accepts $expiry parameter.
	 * @param UserIdentity $user
	 * @param PageReference $target
	 * @param string|null $expiry Optional expiry in any format acceptable to wfTimestamp().
	 *   null will not create an expiry, or leave it unchanged should one already exist.
	 */
	public function addWatch( UserIdentity $user, PageReference $target, ?string $expiry = null ) {
		$this->addWatchBatchForUser( $user, [ $target ], $expiry );

		if ( $this->expiryEnabled ) {
			// When re-watching a page with a null $expiry, any existing expiry is left unchanged.
			// However we must re-fetch the preexisting expiry or else the cached WatchedItem will
			// incorrectly have a null expiry. Note that loadWatchedItem() does the caching.
			// See T259379
			if ( !$expiry ) {
				$this->loadWatchedItem( $user, $target );
				return;
			}

			$expiry = ExpiryDef::normalizeUsingMaxExpiry( $expiry, $this->maxExpiryDuration, TS::ISO_8601 );
		} else {
			$expiry = null;
		}

		// Create a new WatchedItem and add it to the process cache.
		$item = new WatchedItem(
			$user,
			$target,
			null,
			$expiry
		);
		$this->cache( $item );
	}

	/**
	 * Add multiple items to the user's watchlist.
	 * If you know you're adding a single page (and/or its talk page) use self::addWatch(),
	 * since it will add the WatchedItem to the process cache.
	 *
	 * @since 1.27 Method added.
	 * @since 1.35 Accepts $expiry parameter.
	 * @param UserIdentity $user
	 * @param PageReference[] $targets
	 * @param string|null $expiry Optional expiry in a format acceptable to wfTimestamp(),
	 *   null will not create expiries, or leave them unchanged should they already exist.
	 * @return bool Whether database transactions were performed.
	 */
	public function addWatchBatchForUser(
		UserIdentity $user,
		array $targets,
		?string $expiry = null
	): bool {
		// Only registered user can have a watchlist
		if ( !$user->isRegistered() || $this->readOnlyMode->isReadOnly() ) {
			return false;
		}

		if ( !$targets ) {
			return true;
		}
		$expiry = ExpiryDef::normalizeUsingMaxExpiry( $expiry, $this->maxExpiryDuration, TS::ISO_8601 );
		$rows = [];
		foreach ( $targets as $target ) {
			$rows[] = [
				'wl_user' => $user->getId(),
				'wl_namespace' => $target->getNamespace(),
				'wl_title' => $target->getDBkey(),
				'wl_notificationtimestamp' => null,
			];
			$this->uncache( $user, $target );
		}

		$dbw = $this->lbFactory->getPrimaryDatabase();
		$ticket = count( $targets ) > $this->updateRowsPerQuery ?
			$this->lbFactory->getEmptyTransactionTicket( __METHOD__ ) : null;
		$affectedRows = 0;
		$rowBatches = array_chunk( $rows, $this->updateRowsPerQuery );
		foreach ( $rowBatches as $toInsert ) {
			// Use INSERT IGNORE to avoid overwriting the notification timestamp
			// if there's already an entry for this page
			$dbw->newInsertQueryBuilder()
				->insertInto( 'watchlist' )
				->ignore()
				->rows( $toInsert )
				->caller( __METHOD__ )->execute();
			$affectedRows += $dbw->affectedRows();

			if ( $this->expiryEnabled ) {
				$affectedRows += $this->updateOrDeleteExpiries( $dbw, $user->getId(), $toInsert, $expiry );
			}

			if ( $ticket ) {
				$this->lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );
			}
		}

		return (bool)$affectedRows;
	}

	/**
	 * Insert/update expiries, or delete them if the expiry is 'infinity'.
	 *
	 * @param IDatabase $dbw
	 * @param int $userId
	 * @param array $rows
	 * @param string|null $expiry
	 * @return int Number of affected rows.
	 */
	private function updateOrDeleteExpiries(
		IDatabase $dbw,
		int $userId,
		array $rows,
		?string $expiry = null
	): int {
		if ( !$expiry ) {
			// if expiry is null (shouldn't change), 0 rows affected.
			return 0;
		}

		// Build the giant `(...) OR (...)` part to be used with WHERE.
		$conds = [];
		foreach ( $rows as $row ) {
			$conds[] = $dbw->makeList(
				[
					'wl_user' => $userId,
					'wl_namespace' => $row['wl_namespace'],
					'wl_title' => $row['wl_title']
				],
				$dbw::LIST_AND
			);
		}
		$cond = $dbw->makeList( $conds, $dbw::LIST_OR );

		if ( wfIsInfinity( $expiry ) ) {
			// Rows should be deleted rather than updated.
			$dbw->deleteJoin(
				'watchlist_expiry',
				'watchlist',
				'we_item',
				'wl_id',
				[ $cond ],
				__METHOD__
			);

			return $dbw->affectedRows();
		}

		return $this->updateExpiries( $dbw, $expiry, $cond );
	}

	/**
	 * Update the expiries for items found with the given $cond.
	 * @param IDatabase $dbw
	 * @param string $expiry
	 * @param string $cond
	 * @return int Number of affected rows.
	 */
	private function updateExpiries( IDatabase $dbw, string $expiry, string $cond ): int {
		// First fetch the wl_ids from the watchlist table.
		// We'd prefer to do a INSERT/SELECT in the same query with IDatabase::insertSelect(),
		// but it doesn't allow us to use the "ON DUPLICATE KEY UPDATE" clause.
		$wlIds = $dbw->newSelectQueryBuilder()
			->select( 'wl_id' )
			->from( 'watchlist' )
			->where( $cond )
			->caller( __METHOD__ )
			->fetchFieldValues();

		if ( !$wlIds ) {
			return 0;
		}

		$expiry = $dbw->timestamp( $expiry );
		$weRows = [];
		foreach ( $wlIds as $wlId ) {
			$weRows[] = [
				'we_item' => $wlId,
				'we_expiry' => $expiry
			];
		}

		// Insert into watchlist_expiry, updating the expiry for duplicate rows.
		$dbw->newInsertQueryBuilder()
			->insertInto( 'watchlist_expiry' )
			->rows( $weRows )
			->onDuplicateKeyUpdate()
			->uniqueIndexFields( [ 'we_item' ] )
			->set( [ 'we_expiry' => $expiry ] )
			->caller( __METHOD__ )->execute();

		return $dbw->affectedRows();
	}

	/**
	 * @since 1.27
	 * @param UserIdentity $user
	 * @param PageReference $target
	 * @return bool
	 */
	public function removeWatch( UserIdentity $user, PageReference $target ): bool {
		return $this->removeWatchBatchForUser( $user, [ $target ] );
	}

	/**
	 * Set the "last viewed" timestamps for certain titles on a user's watchlist.
	 *
	 * If the $targets parameter is omitted or set to [], this method simply wraps
	 * resetAllNotificationTimestampsForUser(), and in that case you should instead call that method
	 * directly; support for omitting $targets is for backwards compatibility.
	 *
	 * If $targets is omitted or set to [], timestamps will be updated for every title on the user's
	 * watchlist, and this will be done through a DeferredUpdate. If $targets is a non-empty array,
	 * only the specified titles will be updated, and this will be done immediately (not deferred).
	 *
	 * @since 1.27
	 * @param UserIdentity $user
	 * @param string|int $timestamp Value to set the "last viewed" timestamp to (null to clear)
	 * @param PageReference[] $targets Titles to set the timestamp for; [] means the entire watchlist
	 * @return bool
	 */
	public function setNotificationTimestampsForUser(
		UserIdentity $user,
		$timestamp,
		array $targets = []
	): bool {
		// Only registered user can have a watchlist
		if ( !$user->isRegistered() || $this->readOnlyMode->isReadOnly() ) {
			return false;
		}

		if ( !$targets ) {
			// Backwards compatibility
			$this->resetAllNotificationTimestampsForUser( $user, $timestamp );
			return true;
		}

		$dbw = $this->lbFactory->getPrimaryDatabase();
		if ( $timestamp !== null ) {
			$timestamp = $dbw->timestamp( $timestamp );
		}
		$ticket = $this->lbFactory->getEmptyTransactionTicket( __METHOD__ );
		$affectedSinceWait = 0;

		$wlIds = $this->loadIdsForTargets( $dbw, $user, $targets );
		foreach ( $this->batch( $wlIds ) as $ids ) {
			$dbw->newUpdateQueryBuilder()
				->update( 'watchlist' )
				->set( [ 'wl_notificationtimestamp' => $timestamp ] )
				->where( [ 'wl_id' => $ids ] )
				->caller( __METHOD__ )->execute();

			$affectedSinceWait += $dbw->affectedRows();
			// Wait for replication every time we've touched updateRowsPerQuery rows
			if ( $affectedSinceWait >= $this->updateRowsPerQuery ) {
				$this->lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );
				$affectedSinceWait = 0;
			}
		}

		$this->uncacheUser( $user );

		return true;
	}

	/**
	 * @param string|null $timestamp
	 * @param UserIdentity $user
	 * @param PageReference $target
	 * @return bool|string|null
	 */
	public function getLatestNotificationTimestamp(
		$timestamp,
		UserIdentity $user,
		PageReference $target
	) {
		$timestamp = wfTimestampOrNull( TS::MW, $timestamp );
		if ( $timestamp === null ) {
			return null; // no notification
		}

		$seenTimestamps = $this->getPageSeenTimestamps( $user );
		if ( $seenTimestamps ) {
			$seenKey = $this->getPageSeenKey( $target );
			if ( isset( $seenTimestamps[$seenKey] ) && $seenTimestamps[$seenKey] >= $timestamp ) {
				// If a reset job did not yet run, then the "seen" timestamp will be higher
				return null;
			}
		}

		return $timestamp;
	}

	/**
	 * Schedule a DeferredUpdate that sets all of the "last viewed" timestamps for a given user
	 * to the same value.
	 * @param UserIdentity $user
	 * @param string|int|null $timestamp Value to set all timestamps to, null to clear them
	 */
	public function resetAllNotificationTimestampsForUser( UserIdentity $user, $timestamp = null ) {
		// Only registered user can have a watchlist
		if ( !$user->isRegistered() ) {
			return;
		}

		// If the page is watched by the user (or may be watched), update the timestamp
		$job = new ClearWatchlistNotificationsJob( [
			'userId'  => $user->getId(), 'timestamp' => $timestamp, 'casTime' => time()
		] );

		// Try to run this post-send
		// Calls DeferredUpdates::addCallableUpdate in normal operation
		( $this->deferredUpdatesAddCallableUpdateCallback )(
			static function () use ( $job ) {
				$job->run();
			}
		);
	}

	/**
	 * Update wl_notificationtimestamp for all watching users except the editor
	 * @since 1.27
	 * @param UserIdentity $editor
	 * @param PageReference $target
	 * @param string|int $timestamp
	 * @return int[]
	 */
	public function updateNotificationTimestamp(
		UserIdentity $editor,
		PageReference $target,
		$timestamp
	): array {
		$dbw = $this->lbFactory->getPrimaryDatabase();
		$queryBuilder = $dbw->newSelectQueryBuilder()
			->select( [ 'wl_id', 'wl_user' ] )
			->from( 'watchlist' )
			->where(
				[
					'wl_user != ' . $editor->getId(),
					'wl_namespace' => $target->getNamespace(),
					'wl_title' => $target->getDBkey(),
					'wl_notificationtimestamp' => null,
				]
			)
			->caller( __METHOD__ );

		$this->modifyQueryBuilderForExpiry( $queryBuilder, $dbw );

		$res = $queryBuilder->fetchResultSet();
		$watchers = [];
		$wlIds = [];
		foreach ( $res as $row ) {
			$watchers[] = (int)$row->wl_user;
			$wlIds[] = (int)$row->wl_id;
		}

		if ( $wlIds ) {
			$fname = __METHOD__;
			// Try to run this post-send
			// Calls DeferredUpdates::addCallableUpdate in normal operation
			( $this->deferredUpdatesAddCallableUpdateCallback )(
				function () use ( $timestamp, $wlIds, $target, $fname ) {
					$dbw = $this->lbFactory->getPrimaryDatabase();
					$ticket = $this->lbFactory->getEmptyTransactionTicket( $fname );

					$wlIdsChunks = array_chunk( $wlIds, $this->updateRowsPerQuery );
					foreach ( $wlIdsChunks as $wlIdsChunk ) {
						$dbw->newUpdateQueryBuilder()
							->update( 'watchlist' )
							->set( [ 'wl_notificationtimestamp' => $dbw->timestamp( $timestamp ) ] )
							->where( [ 'wl_id' => $wlIdsChunk ] )
							->caller( $fname )->execute();

						if ( count( $wlIdsChunks ) > 1 ) {
							$this->lbFactory->commitAndWaitForReplication( $fname, $ticket );
						}
					}
					$this->uncacheTitle( $target );
				},
				DeferredUpdates::POSTSEND,
				$dbw
			);
		}

		return $watchers;
	}

	/**
	 * @since 1.27
	 * @param UserIdentity $user
	 * @param PageReference $title
	 * @param string $force
	 * @param int $oldid
	 * @return bool
	 */
	public function resetNotificationTimestamp(
		UserIdentity $user,
		PageReference $title,
		$force = '',
		$oldid = 0
	): bool {
		$time = time();

		// Only registered user can have a watchlist
		if ( !$user->isRegistered() || $this->readOnlyMode->isReadOnly() ) {
			return false;
		}

		$item = null;
		if ( $force != 'force' ) {
			$item = $this->getWatchedItem( $user, $title );
			if ( !$item || $item->getNotificationTimestamp() === null ) {
				return false;
			}
		}

		// Get the timestamp (TS::MW) of this revision to track the latest one seen
		$id = $oldid;
		$seenTime = null;
		if ( !$id ) {
			$latestRev = $this->revisionLookup->getRevisionByTitle( $title );
			if ( $latestRev ) {
				$id = $latestRev->getId();
				// Save a DB query
				$seenTime = $latestRev->getTimestamp();
			}
		}
		if ( $seenTime === null ) {
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable getId does not return null here
			$seenTime = $this->revisionLookup->getTimestampFromId( $id );
		}

		// Mark the item as read immediately in lightweight storage
		$this->stash->merge(
			$this->getPageSeenTimestampsKey( $user ),
			function ( $cache, $key, $current ) use ( $title, $seenTime ) {
				if ( !$current ) {
					$value = new MapCacheLRU( 300 );
				} elseif ( is_array( $current ) ) {
					$value = MapCacheLRU::newFromArray( $current, 300 );
				} else {
					// Backwards compatibility for T282105
					$value = $current;
				}
				$subKey = $this->getPageSeenKey( $title );

				if ( $seenTime > $value->get( $subKey ) ) {
					// Revision is newer than the last one seen
					$value->set( $subKey, $seenTime );

					$this->latestUpdateCache->set( $key, $value->toArray(), BagOStuff::TTL_PROC_LONG );
				} elseif ( $seenTime === false ) {
					// Revision does not exist
					$value->set( $subKey, ConvertibleTimestamp::now( TS::MW ) );
					$this->latestUpdateCache->set( $key,
						$value->toArray(),
						BagOStuff::TTL_PROC_LONG );
				} else {
					return false; // nothing to update
				}

				return $value->toArray();
			},
			BagOStuff::TTL_HOUR
		);

		// If the page is watched by the user (or may be watched), update the timestamp
		// ActivityUpdateJob accepts both LinkTarget and PageReference
		$job = new ActivityUpdateJob(
			$title,
			[
				'type'      => 'updateWatchlistNotification',
				'userid'    => $user->getId(),
				'notifTime' => $this->getNotificationTimestamp( $user, $title, $item, $force, $oldid ),
				'curTime'   => $time
			]
		);
		// Try to enqueue this post-send
		$this->queueGroup->lazyPush( $job );

		$this->uncache( $user, $title );

		return true;
	}

	/**
	 * @param UserIdentity $user
	 * @return array|null The map contains prefixed title keys and TS::MW values
	 */
	private function getPageSeenTimestamps( UserIdentity $user ) {
		$key = $this->getPageSeenTimestampsKey( $user );

		$cache = $this->latestUpdateCache->getWithSetCallback(
			$key,
			BagOStuff::TTL_PROC_LONG,
			function () use ( $key ) {
				return $this->stash->get( $key ) ?: null;
			}
		);
		// Backwards compatibility for T282105
		if ( $cache instanceof MapCacheLRU ) {
			$cache = $cache->toArray();
		}
		return $cache;
	}

	private function getPageSeenTimestampsKey( UserIdentity $user ): string {
		return $this->stash->makeGlobalKey(
			'watchlist-recent-updates',
			$this->lbFactory->getLocalDomainID(),
			$user->getId()
		);
	}

	/**
	 * @param PageReference $target
	 * @return string
	 */
	private function getPageSeenKey( $target ): string {
		return "{$target->getNamespace()}:{$target->getDBkey()}";
	}

	/**
	 * @param UserIdentity $user
	 * @param PageReference $title
	 * @param WatchedItem|null $item
	 * @param string $force
	 * @param int|false $oldid The ID of the last revision that the user viewed
	 * @return string|null|false
	 */
	private function getNotificationTimestamp(
		UserIdentity $user,
		PageReference $title,
		$item,
		$force,
		$oldid
	) {
		if ( !$oldid ) {
			// No oldid given, assuming latest revision; clear the timestamp.
			return null;
		}

		$oldRev = $this->revisionLookup->getRevisionById( $oldid );
		if ( !$oldRev ) {
			// Oldid given but does not exist (probably deleted)
			return false;
		}

		$nextRev = $this->revisionLookup->getNextRevision( $oldRev );
		if ( !$nextRev ) {
			// Oldid given and is the latest revision for this title; clear the timestamp.
			return null;
		}

		$item ??= $this->loadWatchedItem( $user, $title );
		if ( !$item ) {
			// This can only happen if $force is enabled.
			return null;
		}

		// Oldid given and isn't the latest; update the timestamp.
		// This will result in no further notification emails being sent!
		$notificationTimestamp = $this->revisionLookup->getTimestampFromId( $oldid );
		// @FIXME: this should use getTimestamp() for consistency with updates on new edits
		// $notificationTimestamp = $nextRev->getTimestamp(); // first unseen revision timestamp

		// We need to go one second to the future because of various strict comparisons
		// throughout the codebase
		$ts = new MWTimestamp( $notificationTimestamp );
		$ts->timestamp->add( new DateInterval( 'PT1S' ) );
		$notificationTimestamp = $ts->getTimestamp( TS::MW );

		if ( $notificationTimestamp < $item->getNotificationTimestamp() ) {
			if ( $force != 'force' ) {
				return false;
			} else {
				// This is a little silly…
				return $item->getNotificationTimestamp();
			}
		}

		return $notificationTimestamp;
	}

	/**
	 * @since 1.27
	 * @param UserIdentity $user
	 * @param int|null $unreadLimit
	 * @return int|bool
	 */
	public function countUnreadNotifications( UserIdentity $user, $unreadLimit = null ) {
		$queryBuilder = $this->lbFactory->getReplicaDatabase()->newSelectQueryBuilder()
			->select( '1' )
			->from( 'watchlist' )
			->where( [
				'wl_user' => $user->getId(),
				'wl_notificationtimestamp IS NOT NULL'
			] )
			->caller( __METHOD__ );
		if ( $unreadLimit !== null ) {
			$unreadLimit = (int)$unreadLimit;
			$queryBuilder->limit( $unreadLimit );
		}

		$rowCount = $queryBuilder->fetchRowCount();

		if ( $unreadLimit === null ) {
			return $rowCount;
		}

		if ( $rowCount >= $unreadLimit ) {
			return true;
		}

		return $rowCount;
	}

	/**
	 * @since 1.27
	 * @param PageReference $oldTarget
	 * @param PageReference $newTarget
	 */
	public function duplicateAllAssociatedEntries(
		PageReference $oldTarget,
		PageReference $newTarget
	) {
		// Duplicate first the subject page, then the talk page
		$this->duplicateEntry(
			PageReferenceValue::localReference(
				$this->nsInfo->getSubject( $oldTarget->getNamespace() ),
				$oldTarget->getDBkey(),
			),
			PageReferenceValue::localReference(
				$this->nsInfo->getSubject( $newTarget->getNamespace() ),
				$newTarget->getDBkey()
			)
		);
		$this->duplicateEntry(
			PageReferenceValue::localReference(
				$this->nsInfo->getTalk( $oldTarget->getNamespace() ),
				$oldTarget->getDBkey()
			),
			PageReferenceValue::localReference(
				$this->nsInfo->getTalk( $newTarget->getNamespace() ),
				$newTarget->getDBkey()
			)
		);
	}

	/**
	 * @since 1.27
	 * @param PageReference $oldTarget
	 * @param PageReference $newTarget
	 */
	public function duplicateEntry( PageReference $oldTarget, PageReference $newTarget ) {
		$dbw = $this->lbFactory->getPrimaryDatabase();
		$result = $this->fetchWatchedItemsForPage( $dbw, $oldTarget );
		$newNamespace = $newTarget->getNamespace();
		$newDBkey = $newTarget->getDBkey();

		# Construct array to replace into the watchlist
		$values = [];
		$expiries = [];
		$labels = [];
		foreach ( $result as $row ) {
			$values[] = [
				'wl_user' => $row->wl_user,
				'wl_namespace' => $newNamespace,
				'wl_title' => $newDBkey,
				'wl_notificationtimestamp' => $row->wl_notificationtimestamp,
			];

			if ( $this->expiryEnabled && $row->we_expiry ) {
				$expiries[$row->wl_user] = $row->we_expiry;
			}
			if ( $this->labelsEnabled && $row->wlm_label_summary !== '' ) {
				$labels[$row->wl_user] = $row->wlm_label_summary;
			}
		}

		if ( !$values ) {
			return;
		}

		// Perform a replace on the watchlist table rows.
		// Note that multi-row replace is very efficient for MySQL but may be inefficient for
		// some other DBMSes, mostly due to poor simulation by us.
		$dbw->newReplaceQueryBuilder()
			->replaceInto( 'watchlist' )
			->uniqueIndexFields( [ 'wl_user', 'wl_namespace', 'wl_title' ] )
			->rows( $values )
			->caller( __METHOD__ )->execute();

		if ( $expiries || $labels ) {
			$this->updateAssociationsAfterMove( $dbw, $expiries, $labels, $newNamespace, $newDBkey );
		}
	}

	/**
	 * @param IReadableDatabase $dbr
	 * @param PageReference $target
	 * @return IResultWrapper
	 */
	private function fetchWatchedItemsForPage(
		IReadableDatabase $dbr,
		PageReference $target
	): IResultWrapper {
		$queryBuilder = $dbr->newSelectQueryBuilder()
			->select( [ 'wl_user', 'wl_notificationtimestamp' ] )
			->from( 'watchlist' )
			->where( [
				'wl_namespace' => $target->getNamespace(),
				'wl_title' => $target->getDBkey(),
			] )
			->caller( __METHOD__ )
			->forUpdate();

		if ( $this->expiryEnabled ) {
			$queryBuilder->leftJoin( 'watchlist_expiry', null, [ 'wl_id = we_item' ] )
				->field( 'we_expiry' );
		}
		if ( $this->labelsEnabled ) {
			$this->addLabelSummaryField( $queryBuilder, $dbr );
		}

		return $queryBuilder->fetchResultSet();
	}

	/**
	 * @param IDatabase $dbw
	 * @param string[] $expiries Expiry times by user_id
	 * @param string[] $labels Label summary strings by user_id
	 * @param int $namespace
	 * @param string $dbKey
	 */
	private function updateAssociationsAfterMove(
		IDatabase $dbw,
		array $expiries,
		array $labels,
		int $namespace,
		string $dbKey
	): void {
		DeferredUpdates::addCallableUpdate(
			function ( $fname ) use ( $dbw, $expiries, $labels, $namespace, $dbKey ) {
				// First fetch new wl_ids.
				$res = $dbw->newSelectQueryBuilder()
					->select( [ 'wl_user', 'wl_id' ] )
					->from( 'watchlist' )
					->where( [
						'wl_namespace' => $namespace,
						'wl_title' => $dbKey,
					] )
					->caller( $fname )
					->fetchResultSet();

				// Build new array to INSERT into multiple rows at once.
				$expiryData = [];
				$labelData = [];
				foreach ( $res as $row ) {
					if ( !empty( $expiries[$row->wl_user] ) ) {
						$expiryData[] = [
							'we_item' => $row->wl_id,
							'we_expiry' => $expiries[$row->wl_user],
						];
					}
					if ( isset( $labels[$row->wl_user] ) ) {
						foreach ( explode( ',', $labels[$row->wl_user] ) as $labelId ) {
							$labelData[] = [
								'wlm_item' => $row->wl_id,
								'wlm_label' => (int)$labelId,
							];
						}
					}
				}

				foreach ( $this->batch( $expiryData ) as $toInsert ) {
					$dbw->newReplaceQueryBuilder()
						->replaceInto( 'watchlist_expiry' )
						->uniqueIndexFields( [ 'we_item' ] )
						->rows( $toInsert )
						->caller( $fname )
						->execute();
				}
				foreach ( $this->batch( $labelData ) as $toInsert ) {
					$dbw->newReplaceQueryBuilder()
						->replaceInto( 'watchlist_label_member' )
						->uniqueIndexFields( [ 'wlm_label', 'wlm_item' ] )
						->rows( $toInsert )
						->caller( $fname )
						->execute();
				}
			},
			DeferredUpdates::POSTSEND,
			$dbw
		);
	}

	/**
	 * Split an array of rows to insert into batches of an appropriate size
	 *
	 * @param array $data
	 * @return array
	 */
	private function batch( array $data ) {
		return array_chunk( $data, $this->updateRowsPerQuery );
	}

	/**
	 * @param UserIdentity $user
	 * @param PageReference[] $titles
	 */
	private function uncacheTitlesForUser( UserIdentity $user, array $titles ) {
		foreach ( $titles as $title ) {
			$this->uncache( $user, $title );
		}
	}

	/**
	 * @inheritDoc
	 */
	public function countExpired(): int {
		$dbr = $this->lbFactory->getReplicaDatabase();
		return $dbr->newSelectQueryBuilder()
			->select( '*' )
			->from( 'watchlist_expiry' )
			->where( $dbr->expr( 'we_expiry', '<=', $dbr->timestamp() ) )
			->caller( __METHOD__ )
			->fetchRowCount();
	}

	/**
	 * @inheritDoc
	 */
	public function removeExpired( int $limit, bool $deleteOrphans = false ): void {
		$dbr = $this->lbFactory->getReplicaDatabase();
		$dbw = $this->lbFactory->getPrimaryDatabase();
		$ticket = $this->lbFactory->getEmptyTransactionTicket( __METHOD__ );

		// Get a batch of watchlist IDs to delete.
		$toDelete = $dbr->newSelectQueryBuilder()
			->select( 'we_item' )
			->from( 'watchlist_expiry' )
			->where( $dbr->expr( 'we_expiry', '<=', $dbr->timestamp() ) )
			->limit( $limit )
			->caller( __METHOD__ )
			->fetchFieldValues();

		if ( count( $toDelete ) > 0 ) {
			// Delete them from the watchlist and associated tables
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'watchlist' )
				->where( [ 'wl_id' => $toDelete ] )
				->caller( __METHOD__ )->execute();
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'watchlist_expiry' )
				->where( [ 'we_item' => $toDelete ] )
				->caller( __METHOD__ )->execute();
			if ( $this->labelsEnabled ) {
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'watchlist_label_member' )
					->where( [ 'wlm_item' => $toDelete ] )
					->caller( __METHOD__ )->execute();
			}
		}

		// Also delete any orphaned or null-expiry watchlist_expiry rows
		// (they should not exist, but might because not everywhere knows about the expiry table yet).
		if ( $deleteOrphans ) {
			$expiryToDelete = $dbr->newSelectQueryBuilder()
				->select( 'we_item' )
				->from( 'watchlist_expiry' )
				->leftJoin( 'watchlist', null, 'wl_id = we_item' )
				->where( $dbr->makeList(
					[ 'wl_id' => null, 'we_expiry' => null ],
					$dbr::LIST_OR
				) )
				->caller( __METHOD__ )
				->fetchFieldValues();
			if ( count( $expiryToDelete ) > 0 ) {
				$dbw->newDeleteQueryBuilder()
					->deleteFrom( 'watchlist_expiry' )
					->where( [ 'we_item' => $expiryToDelete ] )
					->caller( __METHOD__ )->execute();
			}
		}

		$this->lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );
	}

	/** @inheritDoc */
	public function addLabels( UserIdentity $user, array $targets, array $labels ): void {
		if ( !$labels ) {
			return;
		}
		if ( !$this->labelsEnabled ) {
			throw new LogicException( 'addLabels was called when ' .
				'$wgEnableWatchlistLabels was false -- caller should check' );
		}
		$labelIds = $this->getLabelIds( $labels );
		$dbw = $this->lbFactory->getPrimaryDatabase();
		$wlIds = $this->loadIdsForTargets( $dbw, $user, $targets );

		foreach ( $this->batch( $wlIds ) as $wlIdsBatch ) {
			$rows = [];
			foreach ( $wlIdsBatch as $wlId ) {
				foreach ( $labelIds as $labelId ) {
					$rows[] = [
						'wlm_label' => $labelId,
						'wlm_item' => $wlId,
					];
				}
			}

			$dbw->newInsertQueryBuilder()
				->insertInto( 'watchlist_label_member' )
				->ignore()
				->rows( $rows )
				->caller( __METHOD__ )
				->execute();
		}
	}

	/** @inheritDoc */
	public function removeLabels( UserIdentity $user, array $targets, array $labels ): void {
		if ( !$labels ) {
			return;
		}
		if ( !$this->labelsEnabled ) {
			throw new LogicException( 'removeLabels was called when ' .
				'$wgEnableWatchlistLabels was false -- caller should check' );
		}
		$labelIds = $this->getLabelIds( $labels );
		$dbw = $this->lbFactory->getPrimaryDatabase();
		$wlIds = $this->loadIdsForTargets( $dbw, $user, $targets );
		foreach ( $this->batch( $wlIds ) as $wlIdsBatch ) {
			$dbw->newDeleteQueryBuilder()
				->deleteFrom( 'watchlist_label_member' )
				->where( [
					'wlm_item' => $wlIdsBatch,
					'wlm_label' => $labelIds
				] );
		}
	}

	/**
	 * @param (int|WatchlistLabel)[] $labels
	 * @return int[]
	 */
	private function getLabelIds( array $labels ) {
		$labelIds = [];
		foreach ( $labels as $label ) {
			if ( $label instanceof WatchlistLabel ) {
				$labelId = $label->getId();
				if ( !$labelId ) {
					throw new InvalidArgumentException( 'WatchlistLabel has no label ID -- ' .
						'it must be loaded before adding it to an item' );
				}
				$labelIds[] = $labelId;
			} else {
				$labelIds[] = (int)$label;
			}
		}
		return $labelIds;
	}

	/**
	 * Load wl_id values for watched items for a given user and a list of page titles
	 *
	 * @param IReadableDatabase $db
	 * @param UserIdentity $user
	 * @param PageReference[] $targets
	 * @return int[]
	 */
	private function loadIdsForTargets( IReadableDatabase $db, UserIdentity $user, array $targets ) {
		$idStrings = $db->newSelectQueryBuilder()
			->select( 'wl_id' )
			->from( 'watchlist' )
			->where( [
				$this->getTargetsCond( $targets ),
				'wl_user' => $user->getId(),
			] )
			->caller( __METHOD__ )
			->fetchFieldValues();
		return array_map( 'intval', $idStrings );
	}

	/**
	 * Construct an SQL expression string matching any of a list of titles
	 *
	 * @param PageReference[] $targets
	 * @return string SQL
	 */
	private function getTargetsCond( array $targets ) {
		return $this->linkBatchFactory->newLinkBatch( $targets )
			->constructSet( 'wl', $this->lbFactory->getReplicaDatabase() );
	}

}
/** @deprecated class alias since 1.43 */
class_alias( WatchedItemStore::class, 'WatchedItemStore' );
