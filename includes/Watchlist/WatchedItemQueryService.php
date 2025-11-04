<?php

/**
 * @license GPL-2.0-or-later
 */

namespace MediaWiki\Watchlist;

use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\User\UserIdentity;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Class performing complex database queries related to WatchedItems.
 *
 * @since 1.28
 *
 * @ingroup Watchlist
 */
class WatchedItemQueryService {

	// FILTER_* constants are part of public API (are used in ApiQueryWatchlistRaw) and
	// should not be changed.
	// Changing values of those constants will result in a breaking change in the API
	public const FILTER_CHANGED = 'changed';
	public const FILTER_NOT_CHANGED = '!changed';

	public const SORT_ASC = 'ASC';
	public const SORT_DESC = 'DESC';

	/**
	 * @var IConnectionProvider
	 */
	private $dbProvider;

	/** @var WatchedItemStoreInterface */
	private $watchedItemStore;

	/**
	 * @var bool Correlates to $wgWatchlistExpiry feature flag.
	 */
	private $expiryEnabled;

	/**
	 * @var int Max query execution time
	 */
	private $maxQueryExecutionTime;

	public function __construct(
		IConnectionProvider $dbProvider,
		WatchedItemStoreInterface $watchedItemStore,
		bool $expiryEnabled = false,
		int $maxQueryExecutionTime = 0
	) {
		$this->dbProvider = $dbProvider;
		$this->watchedItemStore = $watchedItemStore;
		$this->expiryEnabled = $expiryEnabled;
		$this->maxQueryExecutionTime = $maxQueryExecutionTime;
	}

	/**
	 * For simple listing of user's watchlist items, see WatchedItemStore::getWatchedItemsForUser
	 *
	 * @param UserIdentity $user
	 * @param array $options Allowed keys:
	 *        'sort'         => string optional sorting by namespace ID and title
	 *                          one of the self::SORT_* constants
	 *        'namespaceIds' => int[] optional namespace IDs to filter by (defaults to all namespaces)
	 *        'limit'        => int maximum number of items to return
	 *        'filter'       => string optional filter, one of the self::FILTER_* constants
	 *        'from'         => PageReference|LinkTarget requires 'sort' key, only return items
	 *                          starting from those related to the link target
	 *        'until'        => PageReference|LinkTarget requires 'sort' key, only return items until
	 *                          those related to the link target
	 *        'startFrom'    => PageReference|LinkTarget requires 'sort' key, only return items
	 *                          starting from those related to the link target, allows to skip some
	 *                          link targets specified using the form option
	 * @return WatchedItem[]
	 */
	public function getWatchedItemsForUser( UserIdentity $user, array $options = [] ) {
		if ( !$user->isRegistered() ) {
			// TODO: should this just return an empty array or rather complain loud at this point
			// as e.g. ApiBase::getWatchlistUser does?
			return [];
		}

		$options += [ 'namespaceIds' => [] ];

		Assert::parameter(
			!isset( $options['sort'] ) || in_array( $options['sort'], [ self::SORT_ASC, self::SORT_DESC ] ),
			'$options[\'sort\']',
			'must be SORT_ASC or SORT_DESC'
		);
		Assert::parameter(
			!isset( $options['filter'] ) || in_array(
				$options['filter'], [ self::FILTER_CHANGED, self::FILTER_NOT_CHANGED ]
			),
			'$options[\'filter\']',
			'must be FILTER_CHANGED or FILTER_NOT_CHANGED'
		);
		Assert::parameter(
			( !isset( $options['from'] ) && !isset( $options['until'] ) && !isset( $options['startFrom'] ) )
				|| isset( $options['sort'] ),
			'$options[\'sort\']',
			'must be provided if any of "from", "until", "startFrom" options is provided'
		);

		$db = $this->dbProvider->getReplicaDatabase();

		$queryBuilder = $db->newSelectQueryBuilder()
			->select( [ 'wl_namespace', 'wl_title', 'wl_notificationtimestamp' ] )
			->from( 'watchlist' )
			->caller( __METHOD__ );
		$this->addQueryCondsForWatchedItemsForUser( $db, $user, $options, $queryBuilder );
		$this->addQueryDbOptionsForWatchedItemsForUser( $options, $queryBuilder );

		if ( $this->expiryEnabled ) {
			// If expiries are enabled, join with the watchlist_expiry table and exclude expired items.
			$queryBuilder->leftJoin( 'watchlist_expiry', null, 'wl_id = we_item' )
				->andWhere( $db->expr( 'we_expiry', '>', $db->timestamp() )->or( 'we_expiry', '=', null ) );
		}
		$res = $queryBuilder->fetchResultSet();

		$watchedItems = [];
		foreach ( $res as $row ) {
			$target = PageReferenceValue::localReference( (int)$row->wl_namespace, $row->wl_title );
			// todo these could all be cached at some point?
			$watchedItems[] = new WatchedItem(
				$user,
				$target,
				$this->watchedItemStore->getLatestNotificationTimestamp(
					$row->wl_notificationtimestamp, $user, $target
				),
				$row->we_expiry ?? null
			);
		}

		return $watchedItems;
	}

	private function addQueryCondsForWatchedItemsForUser(
		IReadableDatabase $db, UserIdentity $user, array $options, SelectQueryBuilder $queryBuilder
	) {
		$queryBuilder->where( [ 'wl_user' => $user->getId() ] );
		if ( $options['namespaceIds'] ) {
			$queryBuilder->where( [ 'wl_namespace' => array_map( 'intval', $options['namespaceIds'] ) ] );
		}
		if ( isset( $options['filter'] ) ) {
			$filter = $options['filter'];
			if ( $filter === self::FILTER_CHANGED ) {
				$queryBuilder->where( 'wl_notificationtimestamp IS NOT NULL' );
			} else {
				$queryBuilder->where( 'wl_notificationtimestamp IS NULL' );
			}
		}

		if ( isset( $options['from'] ) ) {
			$op = $options['sort'] === self::SORT_ASC ? '>=' : '<=';
			$queryBuilder->where( $this->getFromUntilTargetConds( $db, $options['from'], $op ) );
		}
		if ( isset( $options['until'] ) ) {
			$op = $options['sort'] === self::SORT_ASC ? '<=' : '>=';
			$queryBuilder->where( $this->getFromUntilTargetConds( $db, $options['until'], $op ) );
		}
		if ( isset( $options['startFrom'] ) ) {
			$op = $options['sort'] === self::SORT_ASC ? '>=' : '<=';
			$queryBuilder->where( $this->getFromUntilTargetConds( $db, $options['startFrom'], $op ) );
		}
	}

	/**
	 * Creates a query condition part for getting only items before or after the given link target
	 * (while ordering using $sort mode)
	 *
	 * @param IReadableDatabase $db
	 * @param PageReference|LinkTarget $target
	 * @param string $op comparison operator to use in the conditions
	 * @return string
	 */
	private function getFromUntilTargetConds( IReadableDatabase $db, PageReference|LinkTarget $target, $op ) {
		return $db->buildComparison( $op, [
			'wl_namespace' => $target->getNamespace(),
			'wl_title' => $target->getDBkey(),
		] );
	}

	private function addQueryDbOptionsForWatchedItemsForUser( array $options, SelectQueryBuilder $queryBuilder ) {
		if ( array_key_exists( 'sort', $options ) ) {
			if ( count( $options['namespaceIds'] ) !== 1 ) {
				$queryBuilder->orderBy( 'wl_namespace', $options['sort'] );
			}
			$queryBuilder->orderBy( 'wl_title', $options['sort'] );
		}
		if ( array_key_exists( 'limit', $options ) ) {
			$queryBuilder->limit( (int)$options['limit'] );
		}
		if ( $this->maxQueryExecutionTime ) {
			$queryBuilder->setMaxExecutionTime( $this->maxQueryExecutionTime );
		}
	}

}
/** @deprecated class alias since 1.43 */
class_alias( WatchedItemQueryService::class, 'WatchedItemQueryService' );
