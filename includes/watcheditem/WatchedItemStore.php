<?php

use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\User\UserIdentity;
use Wikimedia\Assert\Assert;
use Wikimedia\ParamValidator\TypeDef\ExpiryDef;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\ScopedCallback;

/**
 * Storage layer class for WatchedItems.
 * Database interaction & caching
 * TODO caching should be factored out into a CachingWatchedItemStore class
 *
 * @author Addshore
 * @since 1.27
 */
class WatchedItemStore implements WatchedItemStoreInterface, StatsdAwareInterface {

	/**
	 * @since 1.35
	 */
	public const CONSTRUCTOR_OPTIONS = [
		'UpdateRowsPerQuery',
		'WatchlistExpiry',
		'WatchlistExpiryMaxDuration',
	];

	/**
	 * @var ILBFactory
	 */
	private $lbFactory;

	/**
	 * @var LoadBalancer
	 */
	private $loadBalancer;

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
	 * @var StatsdDataFactoryInterface
	 */
	private $stats;

	/**
	 * @var bool Correlates to $wgWatchlistExpiry feature flag.
	 */
	private $expiryEnabled;

	/**
	 * @var HookRunner
	 */
	private $hookRunner;

	/**
	 * @var string|null Maximum configured relative expiry.
	 */
	private $maxExpiryDuration;

	/**
	 * @param ServiceOptions $options
	 * @param ILBFactory $lbFactory
	 * @param JobQueueGroup $queueGroup
	 * @param BagOStuff $stash
	 * @param HashBagOStuff $cache
	 * @param ReadOnlyMode $readOnlyMode
	 * @param NamespaceInfo $nsInfo
	 * @param RevisionLookup $revisionLookup
	 * @param HookContainer $hookContainer
	 */
	public function __construct(
		ServiceOptions $options,
		ILBFactory $lbFactory,
		JobQueueGroup $queueGroup,
		BagOStuff $stash,
		HashBagOStuff $cache,
		ReadOnlyMode $readOnlyMode,
		NamespaceInfo $nsInfo,
		RevisionLookup $revisionLookup,
		HookContainer $hookContainer
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->updateRowsPerQuery = $options->get( 'UpdateRowsPerQuery' );
		$this->expiryEnabled = $options->get( 'WatchlistExpiry' );
		$this->maxExpiryDuration = $options->get( 'WatchlistExpiryMaxDuration' );

		$this->lbFactory = $lbFactory;
		$this->loadBalancer = $lbFactory->getMainLB();
		$this->queueGroup = $queueGroup;
		$this->stash = $stash;
		$this->cache = $cache;
		$this->readOnlyMode = $readOnlyMode;
		$this->stats = new NullStatsdDataFactory();
		$this->deferredUpdatesAddCallableUpdateCallback =
			[ DeferredUpdates::class, 'addCallableUpdate' ];
		$this->nsInfo = $nsInfo;
		$this->revisionLookup = $revisionLookup;
		$this->hookRunner = new HookRunner( $hookContainer );

		$this->latestUpdateCache = new HashBagOStuff( [ 'maxKeys' => 3 ] );
	}

	/**
	 * @param StatsdDataFactoryInterface $stats
	 */
	public function setStatsdDataFactory( StatsdDataFactoryInterface $stats ) {
		$this->stats = $stats;
	}

	/**
	 * Overrides the DeferredUpdates::addCallableUpdate callback
	 * This is intended for use while testing and will fail if MW_PHPUNIT_TEST is not defined.
	 *
	 * @param callable $callback
	 *
	 * @see DeferredUpdates::addCallableUpdate for callback signiture
	 *
	 * @return ScopedCallback to reset the overridden value
	 * @throws MWException
	 */
	public function overrideDeferredUpdatesAddCallableUpdateCallback( callable $callback ) {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new MWException(
				'Cannot override DeferredUpdates::addCallableUpdate callback in operation.'
			);
		}
		$previousValue = $this->deferredUpdatesAddCallableUpdateCallback;
		$this->deferredUpdatesAddCallableUpdateCallback = $callback;
		return new ScopedCallback( function () use ( $previousValue ) {
			$this->deferredUpdatesAddCallableUpdateCallback = $previousValue;
		} );
	}

	private function getCacheKey( UserIdentity $user, LinkTarget $target ) {
		return $this->cache->makeKey(
			(string)$target->getNamespace(),
			$target->getDBkey(),
			(string)$user->getId()
		);
	}

	private function cache( WatchedItem $item ) {
		$user = $item->getUserIdentity();
		$target = $item->getLinkTarget();
		$key = $this->getCacheKey( $user, $target );
		$this->cache->set( $key, $item );
		$this->cacheIndex[$target->getNamespace()][$target->getDBkey()][$user->getId()] = $key;
		$this->stats->increment( 'WatchedItemStore.cache' );
	}

	private function uncache( UserIdentity $user, LinkTarget $target ) {
		$this->cache->delete( $this->getCacheKey( $user, $target ) );
		unset( $this->cacheIndex[$target->getNamespace()][$target->getDBkey()][$user->getId()] );
		$this->stats->increment( 'WatchedItemStore.uncache' );
	}

	private function uncacheLinkTarget( LinkTarget $target ) {
		$this->stats->increment( 'WatchedItemStore.uncacheLinkTarget' );
		if ( !isset( $this->cacheIndex[$target->getNamespace()][$target->getDBkey()] ) ) {
			return;
		}
		foreach ( $this->cacheIndex[$target->getNamespace()][$target->getDBkey()] as $key ) {
			$this->stats->increment( 'WatchedItemStore.uncacheLinkTarget.items' );
			$this->cache->delete( $key );
		}
	}

	private function uncacheUser( UserIdentity $user ) {
		$this->stats->increment( 'WatchedItemStore.uncacheUser' );
		foreach ( $this->cacheIndex as $ns => $dbKeyArray ) {
			foreach ( $dbKeyArray as $dbKey => $userArray ) {
				if ( isset( $userArray[$user->getId()] ) ) {
					$this->stats->increment( 'WatchedItemStore.uncacheUser.items' );
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
	 * @param LinkTarget $target
	 *
	 * @return WatchedItem|false
	 */
	private function getCached( UserIdentity $user, LinkTarget $target ) {
		return $this->cache->get( $this->getCacheKey( $user, $target ) );
	}

	/**
	 * @param int $dbIndex DB_MASTER or DB_REPLICA
	 *
	 * @return IDatabase
	 */
	private function getConnectionRef( $dbIndex ) {
		return $this->loadBalancer->getConnectionRef( $dbIndex, [ 'watchlist' ] );
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
	public function clearUserWatchedItems( UserIdentity $user ) {
		if ( $this->mustClearWatchedItemsUsingJobQueue( $user ) ) {
			return false;
		}

		$dbw = $this->loadBalancer->getConnectionRef( DB_MASTER );

		if ( $this->expiryEnabled ) {
			$ticket = $this->lbFactory->getEmptyTransactionTicket( __METHOD__ );
			// First fetch the wl_ids.
			$wlIds = $dbw->selectFieldValues( 'watchlist', 'wl_id', [
				'wl_user' => $user->getId()
			], __METHOD__ );

			if ( $wlIds ) {
				// Delete rows from both the watchlist and watchlist_expiry tables.
				$dbw->delete(
					'watchlist',
					[ 'wl_id' => $wlIds ],
					__METHOD__
				);

				$dbw->delete(
					'watchlist_expiry',
					[ 'we_item' => $wlIds ],
					__METHOD__
				);
			}
			$this->lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );
		} else {
			$dbw->delete(
				'watchlist',
				[ 'wl_user' => $user->getId() ],
				__METHOD__
			);
		}

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
	public function enqueueWatchlistExpiryJob( float $watchlistPurgeRate ): void {
		$max = mt_getrandmax();
		if ( mt_rand( 0, $max ) < $max * $watchlistPurgeRate ) {
			// The higher the watchlist purge rate, the more likely we are to enqueue a job.
			$this->queueGroup->push( new WatchlistExpiryJob() );
		}
	}

	/**
	 * @since 1.31
	 * @return int The maximum current wl_id
	 */
	public function getMaxId() {
		$dbr = $this->getConnectionRef( DB_REPLICA );
		return (int)$dbr->selectField(
			'watchlist',
			'MAX(wl_id)',
			'',
			__METHOD__
		);
	}

	/**
	 * @since 1.31
	 * @param UserIdentity $user
	 * @return int
	 */
	public function countWatchedItems( UserIdentity $user ) {
		$dbr = $this->getConnectionRef( DB_REPLICA );
		$tables = [ 'watchlist' ];
		$conds = [ 'wl_user' => $user->getId() ];
		$joinConds = [];

		if ( $this->expiryEnabled ) {
			$tables[] = 'watchlist_expiry';
			$joinConds[ 'watchlist_expiry' ] = [ 'LEFT JOIN', 'wl_id = we_item' ];
			$conds[] = 'we_expiry IS NULL OR we_expiry > ' . $dbr->addQuotes( $dbr->timestamp() );
		}

		$return = (int)$dbr->selectField(
			$tables,
			'COUNT(*)',
			$conds,
			__METHOD__,
			[],
			$joinConds
		);

		return $return;
	}

	/**
	 * @since 1.27
	 * @param LinkTarget $target
	 * @return int
	 */
	public function countWatchers( LinkTarget $target ) {
		$dbr = $this->getConnectionRef( DB_REPLICA );
		$tables = [ 'watchlist' ];
		$conds = [
			'wl_namespace' => $target->getNamespace(),
			'wl_title' => $target->getDBkey()
		];
		$joinConds = [];

		if ( $this->expiryEnabled ) {
			$tables[] = 'watchlist_expiry';
			$joinConds[ 'watchlist_expiry' ] = [ 'LEFT JOIN', 'wl_id = we_item' ];
			$conds[] = 'we_expiry IS NULL OR we_expiry > ' . $dbr->addQuotes( $dbr->timestamp() );
		}

		$return = (int)$dbr->selectField(
			$tables,
			'COUNT(*)',
			$conds,
			__METHOD__,
			[],
			$joinConds
		);

		return $return;
	}

	/**
	 * @since 1.27
	 * @param LinkTarget $target
	 * @param string|int $threshold
	 * @return int
	 */
	public function countVisitingWatchers( LinkTarget $target, $threshold ) {
		$dbr = $this->getConnectionRef( DB_REPLICA );
		$tables = [ 'watchlist' ];
		$conds = [
			'wl_namespace' => $target->getNamespace(),
			'wl_title' => $target->getDBkey(),
			'wl_notificationtimestamp >= ' .
			$dbr->addQuotes( $dbr->timestamp( $threshold ) ) .
			' OR wl_notificationtimestamp IS NULL'
		];
		$joinConds = [];

		if ( $this->expiryEnabled ) {
			$tables[] = 'watchlist_expiry';
			$joinConds[ 'watchlist_expiry' ] = [ 'LEFT JOIN', 'wl_id = we_item' ];
			$conds[] = 'we_expiry IS NULL OR we_expiry > ' . $dbr->addQuotes( $dbr->timestamp() );
		}

		$visitingWatchers = (int)$dbr->selectField(
			$tables,
			'COUNT(*)',
			$conds,
			__METHOD__,
			[],
			$joinConds
		);

		return $visitingWatchers;
	}

	/**
	 * @param UserIdentity $user
	 * @param LinkTarget[] $titles
	 * @return bool
	 */
	public function removeWatchBatchForUser( UserIdentity $user, array $titles ) {
		if ( $this->readOnlyMode->isReadOnly() ) {
			return false;
		}
		if ( !$user->isRegistered() ) {
			return false;
		}
		if ( !$titles ) {
			return true;
		}

		$rows = $this->getTitleDbKeysGroupedByNamespace( $titles );
		$this->uncacheTitlesForUser( $user, $titles );

		$dbw = $this->getConnectionRef( DB_MASTER );
		$ticket = count( $titles ) > $this->updateRowsPerQuery ?
			$this->lbFactory->getEmptyTransactionTicket( __METHOD__ ) : null;
		$affectedRows = 0;

		// Batch delete items per namespace.
		foreach ( $rows as $namespace => $namespaceTitles ) {
			$rowBatches = array_chunk( $namespaceTitles, $this->updateRowsPerQuery );
			foreach ( $rowBatches as $toDelete ) {
				// First fetch the wl_ids.
				$wlIds = $dbw->selectFieldValues( 'watchlist', 'wl_id', [
					'wl_user' => $user->getId(),
					'wl_namespace' => $namespace,
					'wl_title' => $toDelete
				], __METHOD__ );

				if ( $wlIds ) {
					// Delete rows from both the watchlist and watchlist_expiry tables.
					$dbw->delete(
						'watchlist',
						[ 'wl_id' => $wlIds ],
						__METHOD__
					);
					$affectedRows += $dbw->affectedRows();

					if ( $this->expiryEnabled ) {
						$dbw->delete(
							'watchlist_expiry',
							[ 'we_item' => $wlIds ],
							__METHOD__
						);
						$affectedRows += $dbw->affectedRows();
					}
				}

				if ( $ticket ) {
					$this->lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );
				}
			}
		}

		return (bool)$affectedRows;
	}

	/**
	 * @since 1.27
	 * @param LinkTarget[] $targets
	 * @param array $options
	 * @return array
	 */
	public function countWatchersMultiple( array $targets, array $options = [] ) {
		$dbOptions = [ 'GROUP BY' => [ 'wl_namespace', 'wl_title' ] ];

		$dbr = $this->getConnectionRef( DB_REPLICA );

		if ( array_key_exists( 'minimumWatchers', $options ) ) {
			$dbOptions['HAVING'] = 'COUNT(*) >= ' . (int)$options['minimumWatchers'];
		}

		$lb = new LinkBatch( $targets );

		$tables = [ 'watchlist' ];
		$conds = [ $lb->constructSet( 'wl', $dbr ) ];
		$joinConds = [];

		if ( $this->expiryEnabled ) {
			$tables[] = 'watchlist_expiry';
			$joinConds[ 'watchlist_expiry' ] = [ 'LEFT JOIN', 'wl_id = we_item' ];
			$conds[] = 'we_expiry IS NULL OR we_expiry > ' . $dbr->addQuotes( $dbr->timestamp() );
		}

		$res = $dbr->select(
			$tables,
			[ 'wl_title', 'wl_namespace', 'watchers' => 'COUNT(*)' ],
			$conds,
			__METHOD__,
			$dbOptions,
			$joinConds
		);

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
	 * @param array $targetsWithVisitThresholds
	 * @param int|null $minimumWatchers
	 * @return array
	 */
	public function countVisitingWatchersMultiple(
		array $targetsWithVisitThresholds,
		$minimumWatchers = null
	) {
		if ( $targetsWithVisitThresholds === [] ) {
			// No titles requested => no results returned
			return [];
		}

		$dbr = $this->getConnectionRef( DB_REPLICA );

		$conds = [ $this->getVisitingWatchersCondition( $dbr, $targetsWithVisitThresholds ) ];

		$dbOptions = [ 'GROUP BY' => [ 'wl_namespace', 'wl_title' ] ];
		if ( $minimumWatchers !== null ) {
			$dbOptions['HAVING'] = 'COUNT(*) >= ' . (int)$minimumWatchers;
		}

		$tables = [ 'watchlist' ];
		$joinConds = [];

		if ( $this->expiryEnabled ) {
			$tables[] = 'watchlist_expiry';
			$joinConds[ 'watchlist_expiry' ] = [ 'LEFT JOIN', 'wl_id = we_item' ];
			$conds[] = 'we_expiry IS NULL OR we_expiry > ' . $dbr->addQuotes( $dbr->timestamp() );
		}

		$res = $dbr->select(
			$tables,
			[ 'wl_namespace', 'wl_title', 'watchers' => 'COUNT(*)' ],
			$conds,
			__METHOD__,
			$dbOptions,
			$joinConds
		);

		$watcherCounts = [];
		foreach ( $targetsWithVisitThresholds as list( $target ) ) {
			/* @var LinkTarget $target */
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
	 * @param IDatabase $db
	 * @param array $targetsWithVisitThresholds array of pairs (LinkTarget, last visit threshold)
	 * @return string
	 */
	private function getVisitingWatchersCondition(
		IDatabase $db,
		array $targetsWithVisitThresholds
	) {
		$missingTargets = [];
		$namespaceConds = [];
		foreach ( $targetsWithVisitThresholds as list( $target, $threshold ) ) {
			if ( $threshold === null ) {
				$missingTargets[] = $target;
				continue;
			}
			/* @var LinkTarget $target */
			$namespaceConds[$target->getNamespace()][] = $db->makeList( [
				'wl_title = ' . $db->addQuotes( $target->getDBkey() ),
				$db->makeList( [
					'wl_notificationtimestamp >= ' . $db->addQuotes( $db->timestamp( $threshold ) ),
					'wl_notificationtimestamp IS NULL'
				], LIST_OR )
			], LIST_AND );
		}

		$conds = [];
		foreach ( $namespaceConds as $namespace => $pageConds ) {
			$conds[] = $db->makeList( [
				'wl_namespace = ' . $namespace,
				'(' . $db->makeList( $pageConds, LIST_OR ) . ')'
			], LIST_AND );
		}

		if ( $missingTargets ) {
			$lb = new LinkBatch( $missingTargets );
			$conds[] = $lb->constructSet( 'wl', $db );
		}

		return $db->makeList( $conds, LIST_OR );
	}

	/**
	 * @since 1.27
	 * @param UserIdentity $user
	 * @param LinkTarget $target
	 * @return WatchedItem|false
	 */
	public function getWatchedItem( UserIdentity $user, LinkTarget $target ) {
		if ( !$user->isRegistered() ) {
			return false;
		}

		$cached = $this->getCached( $user, $target );
		if ( $cached && !$cached->isExpired() ) {
			$this->stats->increment( 'WatchedItemStore.getWatchedItem.cached' );
			return $cached;
		}
		$this->stats->increment( 'WatchedItemStore.getWatchedItem.load' );
		return $this->loadWatchedItem( $user, $target );
	}

	/**
	 * @since 1.27
	 * @param UserIdentity $user
	 * @param LinkTarget $target
	 * @return WatchedItem|false
	 */
	public function loadWatchedItem( UserIdentity $user, LinkTarget $target ) {
		// Only registered user can have a watchlist
		if ( !$user->isRegistered() ) {
			return false;
		}

		$dbr = $this->getConnectionRef( DB_REPLICA );

		$row = $this->fetchWatchedItems(
			$dbr,
			$user,
			[ 'wl_notificationtimestamp' ],
			[],
			$target
		);

		if ( !$row ) {
			return false;
		}

		$item = $this->getWatchedItemFromRow( $user, $target, $row );
		$this->cache( $item );

		return $item;
	}

	/**
	 * @since 1.27
	 * @param UserIdentity $user
	 * @param array $options
	 * @return WatchedItem[]
	 */
	public function getWatchedItemsForUser( UserIdentity $user, array $options = [] ) {
		$options += [ 'forWrite' => false ];
		$vars = [ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp' ];
		$dbOptions = [];
		$db = $this->getConnectionRef( $options['forWrite'] ? DB_MASTER : DB_REPLICA );
		if ( array_key_exists( 'sort', $options ) ) {
			Assert::parameter(
				( in_array( $options['sort'], [ self::SORT_ASC, self::SORT_DESC ] ) ),
				'$options[\'sort\']',
				'must be SORT_ASC or SORT_DESC'
			);
			$dbOptions['ORDER BY'][] = "wl_namespace {$options['sort']}";
			if ( $this->expiryEnabled
				&& array_key_exists( 'sortByExpiry', $options )
				&& $options['sortByExpiry']
			) {
				// Add `wl_has_expiry` column to allow sorting by watched titles that have an expiration date first.
				$vars['wl_has_expiry'] = $db->conditional( 'we_expiry IS NULL', 0, 1 );
				// Display temporarily watched titles first.
				// Order by expiration date, with the titles that will expire soonest at the top.
				$dbOptions['ORDER BY'][] = "wl_has_expiry DESC";
				$dbOptions['ORDER BY'][] = "we_expiry ASC";
			}

			$dbOptions['ORDER BY'][] = "wl_title {$options['sort']}";
		}

		$res = $this->fetchWatchedItems(
			$db,
			$user,
			$vars,
			$dbOptions
		);

		$watchedItems = [];
		foreach ( $res as $row ) {
			$target = new TitleValue( (int)$row->wl_namespace, $row->wl_title );
			// @todo: Should we add these to the process cache?
			$watchedItems[] = $this->getWatchedItemFromRow( $user, $target, $row );
		}

		return $watchedItems;
	}

	/**
	 * Construct a new WatchedItem given a row from watchlist/watchlist_expiry.
	 * @param UserIdentity $user
	 * @param LinkTarget $target
	 * @param stdClass $row
	 * @return WatchedItem
	 */
	private function getWatchedItemFromRow(
		UserIdentity $user,
		LinkTarget $target,
		stdClass $row
	): WatchedItem {
		return new WatchedItem(
			$user,
			$target,
			$this->getLatestNotificationTimestamp(
				$row->wl_notificationtimestamp, $user, $target ),
			wfTimestampOrNull( TS_ISO_8601, $row->we_expiry ?? null )
		);
	}

	/**
	 * Fetches either a single or all watched items for the given user.
	 * If a $target is given, IDatabase::selectRow() is called, otherwise select().
	 * If $wgWatchlistExpiry is enabled, expired items are not returned.
	 *
	 * @param IDatabase $db
	 * @param UserIdentity $user
	 * @param array $vars we_expiry is added when $wgWatchlistExpiry is enabled.
	 * @param array $options
	 * @param LinkTarget|null $target null if selecting all watched items.
	 * @return IResultWrapper|stdClass|false
	 */
	private function fetchWatchedItems(
		IDatabase $db,
		UserIdentity $user,
		array $vars,
		array $options = [],
		?LinkTarget $target = null
	) {
		$dbMethod = 'select';
		$conds = [ 'wl_user' => $user->getId() ];

		if ( $target ) {
			$dbMethod = 'selectRow';
			$conds = array_merge( $conds, [
				'wl_namespace' => $target->getNamespace(),
				'wl_title' => $target->getDBkey(),
			] );
		}

		if ( $this->expiryEnabled ) {
			$vars[] = 'we_expiry';
			$conds[] = 'we_expiry IS NULL OR we_expiry > ' . $db->addQuotes( $db->timestamp() );

			return $db->{$dbMethod}(
				[ 'watchlist', 'watchlist_expiry' ],
				$vars,
				$conds,
				__METHOD__,
				$options,
				[ 'watchlist_expiry' => [ 'LEFT JOIN', [ 'wl_id = we_item' ] ] ]
			);
		}

		return $db->{$dbMethod}(
			'watchlist',
			$vars,
			$conds,
			__METHOD__,
			$options
		);
	}

	/**
	 * @since 1.27
	 * @param UserIdentity $user
	 * @param LinkTarget $target
	 * @return bool
	 */
	public function isWatched( UserIdentity $user, LinkTarget $target ) {
		return (bool)$this->getWatchedItem( $user, $target );
	}

	/**
	 * Check if the user is temporarily watching the page.
	 * @since 1.35
	 * @param UserIdentity $user
	 * @param LinkTarget $target
	 * @return bool
	 */
	public function isTempWatched( UserIdentity $user, LinkTarget $target ): bool {
		$item = $this->getWatchedItem( $user, $target );
		return $item && $item->getExpiry();
	}

	/**
	 * @since 1.27
	 * @param UserIdentity $user
	 * @param LinkTarget[] $targets
	 * @return array
	 */
	public function getNotificationTimestampsBatch( UserIdentity $user, array $targets ) {
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

		$dbr = $this->getConnectionRef( DB_REPLICA );

		$lb = new LinkBatch( $targetsToLoad );
		$res = $dbr->select(
			'watchlist',
			[ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp' ],
			[
				$lb->constructSet( 'wl', $dbr ),
				'wl_user' => $user->getId(),
			],
			__METHOD__
		);

		foreach ( $res as $row ) {
			$target = new TitleValue( (int)$row->wl_namespace, $row->wl_title );
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
	 * @param LinkTarget $target
	 * @param string|null $expiry Optional expiry in any format acceptable to wfTimestamp().
	 *   null will not create an expiry, or leave it unchanged should one already exist.
	 */
	public function addWatch( UserIdentity $user, LinkTarget $target, ?string $expiry = null ) {
		$this->addWatchBatchForUser( $user, [ $target ], $expiry );

		if ( $this->expiryEnabled && !$expiry ) {
			// When re-watching a page with a null $expiry, any existing expiry is left unchanged.
			// However we must re-fetch the preexisting expiry or else the cached WatchedItem will
			// incorrectly have a null expiry. Note that loadWatchedItem() does the caching.
			// See T259379
			$this->loadWatchedItem( $user, $target );
		} else {
			// Create a new WatchedItem and add it to the process cache.
			// In this case we don't need to re-fetch the expiry.
			$expiry = ExpiryDef::normalizeUsingMaxExpiry( $expiry, $this->maxExpiryDuration, TS_ISO_8601 );
			$item = new WatchedItem(
				$user,
				$target,
				null,
				$expiry
			);
			$this->cache( $item );
		}
	}

	/**
	 * Add multiple items to the user's watchlist.
	 * If you know you're adding a single page (and/or its talk page) use self::addWatch(),
	 * since it will add the WatchedItem to the process cache.
	 *
	 * @since 1.27 Method added.
	 * @since 1.35 Accepts $expiry parameter.
	 * @param UserIdentity $user
	 * @param LinkTarget[] $targets
	 * @param string|null $expiry Optional expiry in a format acceptable to wfTimestamp(),
	 *   null will not create expiries, or leave them unchanged should they already exist.
	 * @return bool Whether database transactions were performed.
	 */
	public function addWatchBatchForUser(
		UserIdentity $user,
		array $targets,
		?string $expiry = null
	) {
		if ( $this->readOnlyMode->isReadOnly() ) {
			return false;
		}
		// Only registered user can have a watchlist
		if ( !$user->isRegistered() ) {
			return false;
		}

		if ( !$targets ) {
			return true;
		}
		$expiry = ExpiryDef::normalizeUsingMaxExpiry( $expiry, $this->maxExpiryDuration, TS_ISO_8601 );
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

		$dbw = $this->getConnectionRef( DB_MASTER );
		$ticket = count( $targets ) > $this->updateRowsPerQuery ?
			$this->lbFactory->getEmptyTransactionTicket( __METHOD__ ) : null;
		$affectedRows = 0;
		$rowBatches = array_chunk( $rows, $this->updateRowsPerQuery );
		foreach ( $rowBatches as $toInsert ) {
			// Use INSERT IGNORE to avoid overwriting the notification timestamp
			// if there's already an entry for this page
			$dbw->insert( 'watchlist', $toInsert, __METHOD__, [ 'IGNORE' ] );
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
		$wlIds = (array)$dbw->selectFieldValues( 'watchlist', 'wl_id', $cond, __METHOD__ );

		$expiry = $dbw->timestamp( $expiry );

		$weRows = array_map( function ( $wlId ) use ( $expiry, $dbw ) {
			return [
				'we_item' => $wlId,
				'we_expiry' => $expiry
			];
		}, $wlIds );

		// Insert into watchlist_expiry, updating the expiry for duplicate rows.
		$dbw->upsert(
			'watchlist_expiry',
			$weRows,
			'we_item',
			[ 'we_expiry' => $expiry ],
			__METHOD__
		);

		return $dbw->affectedRows();
	}

	/**
	 * @since 1.27
	 * @param UserIdentity $user
	 * @param LinkTarget $target
	 * @return bool
	 */
	public function removeWatch( UserIdentity $user, LinkTarget $target ) {
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
	 * @param LinkTarget[] $targets Titles to set the timestamp for; [] means the entire watchlist
	 * @return bool
	 */
	public function setNotificationTimestampsForUser(
		UserIdentity $user, $timestamp, array $targets = []
	) {
		// Only registered user can have a watchlist
		if ( !$user->isRegistered() || $this->readOnlyMode->isReadOnly() ) {
			return false;
		}

		if ( !$targets ) {
			// Backwards compatibility
			$this->resetAllNotificationTimestampsForUser( $user, $timestamp );
			return true;
		}

		$rows = $this->getTitleDbKeysGroupedByNamespace( $targets );

		$dbw = $this->getConnectionRef( DB_MASTER );
		if ( $timestamp !== null ) {
			$timestamp = $dbw->timestamp( $timestamp );
		}
		$ticket = $this->lbFactory->getEmptyTransactionTicket( __METHOD__ );
		$affectedSinceWait = 0;

		// Batch update items per namespace
		foreach ( $rows as $namespace => $namespaceTitles ) {
			$rowBatches = array_chunk( $namespaceTitles, $this->updateRowsPerQuery );
			foreach ( $rowBatches as $toUpdate ) {
				$dbw->update(
					'watchlist',
					[ 'wl_notificationtimestamp' => $timestamp ],
					[
						'wl_user' => $user->getId(),
						'wl_namespace' => $namespace,
						'wl_title' => $toUpdate
					],
					__METHOD__
				);
				$affectedSinceWait += $dbw->affectedRows();
				// Wait for replication every time we've touched updateRowsPerQuery rows
				if ( $affectedSinceWait >= $this->updateRowsPerQuery ) {
					$this->lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );
					$affectedSinceWait = 0;
				}
			}
		}

		$this->uncacheUser( $user );

		return true;
	}

	public function getLatestNotificationTimestamp(
		$timestamp, UserIdentity $user, LinkTarget $target
	) {
		$timestamp = wfTimestampOrNull( TS_MW, $timestamp );
		if ( $timestamp === null ) {
			return null; // no notification
		}

		$seenTimestamps = $this->getPageSeenTimestamps( $user );
		if (
			$seenTimestamps &&
			$seenTimestamps->get( $this->getPageSeenKey( $target ) ) >= $timestamp
		) {
			// If a reset job did not yet run, then the "seen" timestamp will be higher
			return null;
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
		call_user_func(
			$this->deferredUpdatesAddCallableUpdateCallback,
			function () use ( $job ) {
				$job->run();
			}
		);
	}

	/**
	 * @since 1.27
	 * @param UserIdentity $editor
	 * @param LinkTarget $target
	 * @param string|int $timestamp
	 * @return int[]
	 */
	public function updateNotificationTimestamp(
		UserIdentity $editor, LinkTarget $target, $timestamp
	) {
		$dbw = $this->getConnectionRef( DB_MASTER );
		$selectTables = [ 'watchlist' ];
		$selectConds = [
			'wl_user != ' . intval( $editor->getId() ),
			'wl_namespace' => $target->getNamespace(),
			'wl_title' => $target->getDBkey(),
			'wl_notificationtimestamp IS NULL',
		];
		$selectJoin = [];

		if ( $this->expiryEnabled ) {
			$selectTables[] = 'watchlist_expiry';
			$selectConds[] = 'we_expiry IS NULL OR we_expiry > ' .
				$dbw->addQuotes( $dbw->timestamp() );
			$selectJoin = [ 'watchlist_expiry' => [ 'LEFT JOIN', 'wl_id = we_item' ] ];
		}

		$uids = $dbw->selectFieldValues(
			$selectTables,
			'wl_user',
			$selectConds,
			__METHOD__,
			[],
			$selectJoin
		);

		$watchers = array_map( 'intval', $uids );
		if ( $watchers ) {
			// Update wl_notificationtimestamp for all watching users except the editor
			$fname = __METHOD__;
			DeferredUpdates::addCallableUpdate(
				function () use ( $timestamp, $watchers, $target, $fname ) {
					$dbw = $this->getConnectionRef( DB_MASTER );
					$ticket = $this->lbFactory->getEmptyTransactionTicket( $fname );

					$watchersChunks = array_chunk( $watchers, $this->updateRowsPerQuery );
					foreach ( $watchersChunks as $watchersChunk ) {
						$dbw->update( 'watchlist',
							[ /* SET */
								'wl_notificationtimestamp' => $dbw->timestamp( $timestamp )
							], [ /* WHERE - TODO Use wl_id T130067 */
								'wl_user' => $watchersChunk,
								'wl_namespace' => $target->getNamespace(),
								'wl_title' => $target->getDBkey(),
							], $fname
						);
						if ( count( $watchersChunks ) > 1 ) {
							$this->lbFactory->commitAndWaitForReplication(
								$fname, $ticket, [ 'domain' => $dbw->getDomainID() ]
							);
						}
					}
					$this->uncacheLinkTarget( $target );
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
	 * @param LinkTarget $title
	 * @param string $force
	 * @param int $oldid
	 * @return bool
	 */
	public function resetNotificationTimestamp(
		UserIdentity $user, LinkTarget $title, $force = '', $oldid = 0
	) {
		$time = time();

		// Only registered user can have a watchlist
		if ( $this->readOnlyMode->isReadOnly() || !$user->isRegistered() ) {
			return false;
		}

		// Hook expects User and Title, not UserIdentity and LinkTarget
		$userObj = User::newFromId( $user->getId() );
		$titleObj = Title::castFromLinkTarget( $title );
		if ( !$this->hookRunner->onBeforeResetNotificationTimestamp(
			$userObj, $titleObj, $force, $oldid )
		) {
			return false;
		}
		if ( !$userObj->equals( $user ) ) {
			$user = $userObj;
		}
		if ( !$titleObj->equals( $title ) ) {
			$title = $titleObj;
		}

		$item = null;
		if ( $force != 'force' ) {
			$item = $this->loadWatchedItem( $user, $title );
			if ( !$item || $item->getNotificationTimestamp() === null ) {
				return false;
			}
		}

		// Get the timestamp (TS_MW) of this revision to track the latest one seen
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
			$seenTime = $this->revisionLookup->getTimestampFromId( $id );
		}

		// Mark the item as read immediately in lightweight storage
		$this->stash->merge(
			$this->getPageSeenTimestampsKey( $user ),
			function ( $cache, $key, $current ) use ( $title, $seenTime ) {
				$value = $current ?: new MapCacheLRU( 300 );
				$subKey = $this->getPageSeenKey( $title );

				if ( $seenTime > $value->get( $subKey ) ) {
					// Revision is newer than the last one seen
					$value->set( $subKey, $seenTime );
					$this->latestUpdateCache->set( $key, $value, BagOStuff::TTL_PROC_LONG );
				} elseif ( $seenTime === false ) {
					// Revision does not exist
					$value->set( $subKey, wfTimestamp( TS_MW ) );
					$this->latestUpdateCache->set( $key, $value, BagOStuff::TTL_PROC_LONG );
				} else {
					return false; // nothing to update
				}

				return $value;
			},
			BagOStuff::TTL_HOUR
		);

		// If the page is watched by the user (or may be watched), update the timestamp
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
	 * @return MapCacheLRU|null The map contains prefixed title keys and TS_MW values
	 */
	private function getPageSeenTimestamps( UserIdentity $user ) {
		$key = $this->getPageSeenTimestampsKey( $user );

		return $this->latestUpdateCache->getWithSetCallback(
			$key,
			BagOStuff::TTL_PROC_LONG,
			function () use ( $key ) {
				return $this->stash->get( $key ) ?: null;
			}
		);
	}

	/**
	 * @param UserIdentity $user
	 * @return string
	 */
	private function getPageSeenTimestampsKey( UserIdentity $user ) {
		return $this->stash->makeGlobalKey(
			'watchlist-recent-updates',
			$this->lbFactory->getLocalDomainID(),
			$user->getId()
		);
	}

	/**
	 * @param LinkTarget $target
	 * @return string
	 */
	private function getPageSeenKey( LinkTarget $target ) {
		return "{$target->getNamespace()}:{$target->getDBkey()}";
	}

	/**
	 * @param UserIdentity $user
	 * @param LinkTarget $title
	 * @param WatchedItem $item
	 * @param bool $force
	 * @param int|bool $oldid The ID of the last revision that the user viewed
	 * @return bool|string|null
	 */
	private function getNotificationTimestamp(
		UserIdentity $user, LinkTarget $title, $item, $force, $oldid
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

		if ( $item === null ) {
			$item = $this->loadWatchedItem( $user, $title );
		}

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
		$notificationTimestamp = $ts->getTimestamp( TS_MW );

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
		$dbr = $this->getConnectionRef( DB_REPLICA );

		$queryOptions = [];
		if ( $unreadLimit !== null ) {
			$unreadLimit = (int)$unreadLimit;
			$queryOptions['LIMIT'] = $unreadLimit;
		}

		$conds = [
			'wl_user' => $user->getId(),
			'wl_notificationtimestamp IS NOT NULL'
		];

		$rowCount = $dbr->selectRowCount( 'watchlist', '1', $conds, __METHOD__, $queryOptions );

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
	 * @param LinkTarget $oldTarget
	 * @param LinkTarget $newTarget
	 */
	public function duplicateAllAssociatedEntries( LinkTarget $oldTarget, LinkTarget $newTarget ) {
		// Duplicate first the subject page, then the talk page
		$this->duplicateEntry(
			$this->nsInfo->getSubjectPage( $oldTarget ),
			$this->nsInfo->getSubjectPage( $newTarget )
		);
		$this->duplicateEntry(
			$this->nsInfo->getTalkPage( $oldTarget ),
			$this->nsInfo->getTalkPage( $newTarget )
		);
	}

	/**
	 * @since 1.27
	 * @param LinkTarget $oldTarget
	 * @param LinkTarget $newTarget
	 */
	public function duplicateEntry( LinkTarget $oldTarget, LinkTarget $newTarget ) {
		$dbw = $this->getConnectionRef( DB_MASTER );
		$result = $this->fetchWatchedItemsForPage( $dbw, $oldTarget );
		$newNamespace = $newTarget->getNamespace();
		$newDBkey = $newTarget->getDBkey();

		# Construct array to replace into the watchlist
		$values = [];
		$expiries = [];
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
		}

		if ( empty( $values ) ) {
			return;
		}

		// Perform a replace on the watchlist table rows.
		// Note that multi-row replace is very efficient for MySQL but may be inefficient for
		// some other DBMSes, mostly due to poor simulation by us.
		$dbw->replace(
			'watchlist',
			[ [ 'wl_user', 'wl_namespace', 'wl_title' ] ],
			$values,
			__METHOD__
		);

		if ( $this->expiryEnabled ) {
			$this->updateExpiriesAfterMove( $dbw, $expiries, $newNamespace, $newDBkey );
		}
	}

	/**
	 * @param IDatabase $dbw
	 * @param LinkTarget $target
	 * @return IResultWrapper
	 */
	private function fetchWatchedItemsForPage(
		IDatabase $dbw,
		LinkTarget $target
	) : IResultWrapper {
		$tables = [ 'watchlist' ];
		$fields = [ 'wl_user', 'wl_notificationtimestamp' ];
		$joins = [];

		if ( $this->expiryEnabled ) {
			$tables[] = 'watchlist_expiry';
			$fields[] = 'we_expiry';
			$joins['watchlist_expiry'] = [ 'LEFT JOIN', [ 'wl_id = we_item' ] ];
		}

		return $dbw->select(
			$tables,
			$fields,
			[
				'wl_namespace' => $target->getNamespace(),
				'wl_title' => $target->getDBkey(),
			],
			__METHOD__,
			[ 'FOR UPDATE' ],
			$joins
		);
	}

	/**
	 * @param IDatabase $dbw
	 * @param array $expiries
	 * @param int $namespace
	 * @param string $dbKey
	 */
	private function updateExpiriesAfterMove(
		IDatabase $dbw,
		array $expiries,
		int $namespace,
		string $dbKey
	): void {
		$method = __METHOD__;
		DeferredUpdates::addCallableUpdate(
			function () use ( $dbw, $expiries, $namespace, $dbKey, $method ) {
				// First fetch new wl_ids.
				$res = $dbw->select(
					'watchlist',
					[ 'wl_user', 'wl_id' ],
					[
						'wl_namespace' => $namespace,
						'wl_title' => $dbKey,
					],
					$method
				);

				// Build new array to INSERT into multiple rows at once.
				$expiryData = [];
				foreach ( $res as $row ) {
					if ( !empty( $expiries[$row->wl_user] ) ) {
						$expiryData[] = [
							'we_item' => $row->wl_id,
							'we_expiry' => $expiries[$row->wl_user],
						];
					}
				}

				// Batch the insertions.
				$batches = array_chunk( $expiryData, $this->updateRowsPerQuery );
				foreach ( $batches as $toInsert ) {
					$dbw->insert(
						'watchlist_expiry',
						$toInsert,
						$method
					);
				}
			},
			DeferredUpdates::POSTSEND,
			$dbw
		);
	}

	/**
	 * @param LinkTarget[] $titles
	 * @return array
	 */
	private function getTitleDbKeysGroupedByNamespace( array $titles ) {
		$rows = [];
		foreach ( $titles as $title ) {
			// Group titles by namespace.
			$rows[ $title->getNamespace() ][] = $title->getDBkey();
		}
		return $rows;
	}

	/**
	 * @param UserIdentity $user
	 * @param LinkTarget[] $titles
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
		$dbr = $this->getConnectionRef( DB_REPLICA );
		return $dbr->selectRowCount(
			'watchlist_expiry',
			'*',
			[ 'we_expiry <= ' . $dbr->addQuotes( $dbr->timestamp() ) ],
			__METHOD__
		);
	}

	/**
	 * @inheritDoc
	 */
	public function removeExpired( int $limit, bool $deleteOrphans = false ): void {
		$dbr = $this->getConnectionRef( DB_REPLICA );
		$dbw = $this->getConnectionRef( DB_MASTER );
		$ticket = $this->lbFactory->getEmptyTransactionTicket( __METHOD__ );

		// Get a batch of watchlist IDs to delete.
		$toDelete = $dbr->selectFieldValues(
			'watchlist_expiry',
			'we_item',
			[ 'we_expiry <= ' . $dbr->addQuotes( $dbr->timestamp() ) ],
			__METHOD__,
			[ 'LIMIT' => $limit ]
		);
		if ( count( $toDelete ) > 0 ) {
			// Delete them from the watchlist and watchlist_expiry table.
			$dbw->delete(
				'watchlist',
				[ 'wl_id' => $toDelete ],
				__METHOD__
			);
			$dbw->delete(
				'watchlist_expiry',
				[ 'we_item' => $toDelete ],
				__METHOD__
			);
		}

		// Also delete any orphaned or null-expiry watchlist_expiry rows
		// (they should not exist, but might because not everywhere knows about the expiry table yet).
		if ( $deleteOrphans ) {
			$expiryToDelete = $dbr->selectFieldValues(
				[ 'watchlist_expiry', 'watchlist' ],
				'we_item',
				$dbr->makeList(
					[ 'wl_id' => null, 'we_expiry' => null ],
					$dbr::LIST_OR
				),
				__METHOD__,
				[],
				[ 'watchlist' => [ 'LEFT JOIN', 'wl_id = we_item' ] ]
			);
			if ( count( $expiryToDelete ) > 0 ) {
				$dbw->delete(
					'watchlist_expiry',
					[ 'we_item' => $expiryToDelete ],
					__METHOD__
				);
			}
		}

		$this->lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );
	}
}
