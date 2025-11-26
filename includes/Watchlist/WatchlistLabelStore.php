<?php

namespace MediaWiki\Watchlist;

use InvalidArgumentException;
use MediaWiki\Config\Config;
use MediaWiki\MainConfigNames;
use MediaWiki\Status\Status;
use MediaWiki\User\UserIdentity;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Service class for storage of watchlist labels.
 *
 * @since 1.46
 */
class WatchlistLabelStore {

	public const TABLE_WATCHLIST_LABEL = 'watchlist_label';
	public const TABLE_WATCHLIST_LABEL_MEMBER = 'watchlist_label_member';
	private int $userLabelCount = 0;

	public function __construct(
		private IConnectionProvider $dbProvider,
		private LoggerInterface $logger,
		private Config $config
	) {
	}

	/**
	 * Save a watchlist label to the database.
	 * If this results in a new row, the label's ID will be set.
	 */
	public function save( WatchlistLabel $label ): Status {
		$dbw = $this->dbProvider->getPrimaryDatabase();
		if ( $label->getId() ) {
			$dbw->newUpdateQueryBuilder()
				->table( self::TABLE_WATCHLIST_LABEL )
				->set( [ 'wll_name' => $label->getName() ] )
				->where( [ 'wll_id' => $label->getId() ] )
				->caller( __METHOD__ )
				->execute();
			if ( $dbw->affectedRows() !== 1 ) {
				$this->logger->notice(
					__METHOD__ . " Watchlist label not saved. ID: {0}; Name: {1}",
					[ $label->getId(), $label->getName() ]
				);
				return Status::newFatal( 'unknown-error' );
			}
		} else {
			$userId = $label->getUser()->getId();
			if ( !$userId ) {
				throw new InvalidArgumentException( 'user ID must not be zero' );
			}
			$this->userLabelCount = $this->countAllForUser( $label->getUser() );
			// Check the user has not exceeded their label limit.
			if (
				$this->config->get( MainConfigNames::WatchlistLabelsMaxPerUser ) <= $this->userLabelCount
			) {
				return Status::newFatal(
					'watchlistlabels-limit-reached',
					$this->config->get( MainConfigNames::WatchlistLabelsMaxPerUser )
				);
			}
			$dbw->newInsertQueryBuilder()
				->insertInto( self::TABLE_WATCHLIST_LABEL )
				->row( [ 'wll_user' => $userId, 'wll_name' => $label->getName() ] )
				->ignore()
				->caller( __METHOD__ )
				->execute();
			if ( $dbw->affectedRows() > 0 ) {
				$label->setId( $dbw->insertId() );
			}
		}
		return Status::newGood();
	}

	/**
	 * Delete a set of watchlist labels, by ID.
	 *
	 * @param UserIdentity $user
	 * @param int[] $ids watchlist_label IDs to delete.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function delete( UserIdentity $user, array $ids ): bool {
		if ( $ids === [] ) {
			return true;
		}
		$dbw = $this->dbProvider->getPrimaryDatabase();
		$dbw->startAtomic( __METHOD__ );
		// Confirm that the user owns all the supplied labels.
		sort( $ids );
		$confirmedIdValues = $dbw->newSelectQueryBuilder()
			->from( self::TABLE_WATCHLIST_LABEL )
			->field( 'wll_id' )
			->where( [ 'wll_id' => $ids, 'wll_user' => $user->getId() ] )
			->orderBy( 'wll_id' )
			->caller( __METHOD__ )
			->fetchFieldValues();
		$confirmedIds = array_map( 'intval', $confirmedIdValues );
		if ( $confirmedIds !== $ids ) {
			$dbw->cancelAtomic( __METHOD__ );
			return false;
		}
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( self::TABLE_WATCHLIST_LABEL_MEMBER )
			->where( [ 'wlm_label' => $confirmedIds ] )
			->caller( __METHOD__ )
			->execute();
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( self::TABLE_WATCHLIST_LABEL )
			->where( [ 'wll_id' => $confirmedIds ] )
			->caller( __METHOD__ )
			->execute();
		$dbw->endAtomic( __METHOD__ );
		return true;
	}

	/**
	 * Load a single watchlist label by ID.
	 *
	 * @param UserIdentity $user
	 * @param int $id The watchlist_label ID.
	 *
	 * @return ?WatchlistLabel The label, or null if not found.
	 */
	public function loadById( UserIdentity $user, int $id ): ?WatchlistLabel {
		$select = $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder();
		$result = $select->table( self::TABLE_WATCHLIST_LABEL )
			->fields( [ 'wll_id', 'wll_name' ] )
			// It's not necessary to query for the user, but it adds an extra check.
			->where( [ 'wll_id' => $id, 'wll_user' => $user->getId() ] )
			->caller( __METHOD__ )
			->fetchRow();
		return $result
			? new WatchlistLabel( $user, $result->wll_name, $result->wll_id )
			: null;
	}

	/**
	 * Load a single watchlist label by (normalized) name.
	 *
	 * @param UserIdentity $user
	 * @param string $name The name to search for.
	 *
	 * @return ?WatchlistLabel The label, or null if not found.
	 */
	public function loadByName( UserIdentity $user, string $name ): ?WatchlistLabel {
		$select = $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder();
		$label = new WatchlistLabel( $user, $name );
		$result = $select->table( self::TABLE_WATCHLIST_LABEL )
			->fields( [ 'wll_id', 'wll_name' ] )
			->where( [ 'wll_user' => $label->getUser()->getId(), 'wll_name' => $label->getName() ] )
			->caller( __METHOD__ )
			->fetchRow();
		if ( $result ) {
			$label->setId( $result->wll_id );
			return $label;
		}
		return null;
	}

	/**
	 * Get all of a user's watchlist labels.
	 *
	 * @param UserIdentity $user
	 *
	 * @return WatchlistLabel[] Labels indexed by ID
	 */
	public function loadAllForUser( UserIdentity $user ): array {
		$select = $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder();
		$results = $select->table( self::TABLE_WATCHLIST_LABEL )
			->fields( [ 'wll_id', 'wll_name' ] )
			->where( [ 'wll_user' => $user->getId() ] )
			->caller( __METHOD__ )
			->fetchResultSet();
		$labels = [];
		foreach ( $results as $result ) {
			$labels[ (int)$result->wll_id ] = new WatchlistLabel( $user, $result->wll_name, $result->wll_id );
		}
		return $labels;
	}

	/**
	 * Get counts of all items with the given labels.
	 *
	 * @param int[] $labelIds
	 *
	 * @return array Keys are the label ID, values the integer count.
	 */
	public function countItems( array $labelIds ): array {
		if ( count( $labelIds ) === 0 ) {
			return [];
		}
		$select = $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder();
		$results = $select->table( self::TABLE_WATCHLIST_LABEL_MEMBER )
			->fields( [ 'wlm_label', 'item_count' => 'COUNT(wlm_label)' ] )
			->where( [ 'wlm_label' => $labelIds ] )
			->groupBy( [ 'wlm_label' ] )
			->caller( __METHOD__ )
			->fetchResultSet();
		$counts = array_combine( $labelIds, array_fill( 0, count( $labelIds ), 0 ) );
		foreach ( $results as $result ) {
			$counts[ $result->wlm_label ] = (int)$result->item_count;
		}
		return $counts;
	}

	/**
	 * Get the current total count of a user's watchlist labels.
	 *
	 * @param UserIdentity $user
	 *
	 * @return int
	 */
	public function countAllForUser( UserIdentity $user ): int {
		$select = $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder();
		return (int)$select->table( self::TABLE_WATCHLIST_LABEL )
			->field( 'COUNT(*)' )
			->where( [ 'wll_user' => $user->getId() ] )
			->caller( __METHOD__ )
			->fetchField();
	}
}
