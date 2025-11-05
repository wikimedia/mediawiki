<?php

namespace MediaWiki\Watchlist;

use InvalidArgumentException;
use MediaWiki\User\UserIdentity;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Service class for storage of watchlist labels.
 *
 * @since 1.46
 */
class WatchlistLabelStore {

	public const TABLE_WATCHLIST_LABEL = 'watchlist_label';

	public function __construct( private IConnectionProvider $dbProvider ) {
	}

	/**
	 * Save a watchlist label to the database.
	 * If this results in a new row, the label's ID will be set.
	 */
	public function save( WatchlistLabel $label ): void {
		$dbw = $this->dbProvider->getPrimaryDatabase();
		if ( $label->getId() ) {
			$dbw->newUpdateQueryBuilder()
				->table( self::TABLE_WATCHLIST_LABEL )
				->set( [ 'wll_name' => $label->getName() ] )
				->where( [ 'wll_id' => $label->getId() ] )
				->caller( __METHOD__ )
				->execute();
		} else {
			$userId = $label->getUser()->getId();
			if ( !$userId ) {
				throw new InvalidArgumentException( 'user ID must not be zero' );
			}
			$dbw->newInsertQueryBuilder()
				->insertInto( self::TABLE_WATCHLIST_LABEL )
				->row( [ 'wll_user' => $userId, 'wll_name' => $label->getName() ] )
				->caller( __METHOD__ )
				->execute();
			if ( $dbw->affectedRows() > 0 ) {
				$label->setId( $dbw->insertId() );
			}
		}
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
	 * Get all of a user's watchlist labels.
	 *
	 * @param UserIdentity $user
	 *
	 * @return WatchlistLabel[]
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
			$labels[] = new WatchlistLabel( $user, $result->wll_name, $result->wll_id );
		}
		return $labels;
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
