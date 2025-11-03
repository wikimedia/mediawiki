<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use MediaWiki\User\UserIdentity;

/**
 * A watchlist join with a settable condition on wl_user
 *
 * @since 1.45
 */
class WatchlistJoin extends BasicJoin {
	private ?int $userId = null;

	public function __construct() {
		parent::__construct(
			'watchlist',
			'',
			[
				'wl_namespace=rc_namespace',
				'wl_title=rc_title'
			]
		);
	}

	/**
	 * Set the user whose watchlist will be queried.
	 *
	 * @param UserIdentity $user
	 */
	public function setUser( UserIdentity $user ) {
		$this->userId = $user->getId();
	}

	/**
	 * Add the wl_user condition
	 *
	 * @param string|null $alias
	 * @return array
	 */
	protected function getExtraConds( ?string $alias ) {
		if ( $this->userId === null ) {
			// Call ChangesListQuery::watchlistUser() before executing the query
			throw new \LogicException( 'User ID must be set for watchlist join' );
		}
		if ( $this->userId === 0 ) {
			// Don't ask for a watchlist join when the user is unregistered
			throw new \LogicException( "Can't join on watchlist with wl_user=0" );
		}
		$field = 'wl_user';
		if ( $alias !== null && $alias !== '' ) {
			$field = "$alias.$field";
		}
		return [ $field => $this->userId ];
	}
}
