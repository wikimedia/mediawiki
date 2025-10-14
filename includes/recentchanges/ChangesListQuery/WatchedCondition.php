<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use MediaWiki\User\UserIdentity;
use MediaWiki\Utils\MWTimestamp;
use stdClass;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\RawSQLExpression;

/**
 * Check if a recentchange row is watched by the current watchlist user.
 * Optionally check if the change is a "new" change.
 *
 * @since 1.45
 */
class WatchedCondition extends ChangesListConditionBase {
	private ?int $userId = null;

	public function __construct( private bool $enableExpiry ) {
	}

	public function setUser( UserIdentity $user ) {
		$this->userId = $user->getId();
	}

	/**
	 * @param string $value
	 * @return string
	 */
	public function validateValue( $value ) {
		if ( !in_array( $value, [ 'watched', 'watchednew' ] ) ) {
			throw new \InvalidArgumentException( 'value must be watched or watchednew' );
		}
		return $value;
	}

	/** @inheritDoc */
	public function evaluate( stdClass $row, $value ): bool {
		if ( !$this->userId ) {
			// Not watched
			return false;
		}
		if ( $this->enableExpiry && $row->we_expiry !== null
			&& MWTimestamp::convert( TS_UNIX, $row->we_expiry ) <= wfTimestamp()
		) {
			return false;
		}
		return match ( $value ) {
			'watched' => $row->wl_user !== null,
			'watchednew' => $row->wl_user &&
				$row->rc_timestamp &&
				$row->wl_notificationtimestamp &&
				$row->rc_timestamp >= $row->wl_notificationtimestamp,
			default => throw new \InvalidArgumentException(
				'value must be watched or watchednew' ),
		};
	}

	/** @inheritDoc */
	protected function prepareCapture( IReadableDatabase $dbr, QueryBackend $query ) {
		if ( !$this->userId ) {
			return;
		}
		$query->joinForFields( 'watchlist' )->weakLeft();
		$query->fields( [ 'wl_user', 'wl_notificationtimestamp', 'rc_timestamp' ] );
		if ( $this->enableExpiry ) {
			$query->joinForFields( 'watchlist_expiry' )->left();
			$query->fields( 'we_expiry' );
		}
	}

	/** @inheritDoc */
	protected function prepareConds( IReadableDatabase $dbr, QueryBackend $query ) {
		// Map required/excluded values to the ChangesListSpecialPage filter
		// names so that we can reuse the query building code that was
		// originally there.
		// TODO: rewrite and integrate with ChangesListCondition terminology
		$selectedValues = $this->required;
		if ( in_array( 'watched', $this->excluded ) ) {
			$selectedValues[] = 'notwatched';
		}
		sort( $selectedValues );

		if ( !$this->userId ) {
			if ( $this->required ) {
				$query->forceEmptySet();
			}
			return;
		}

		$notwatchedCond = $dbr->expr( 'wl_user', '=', null );
		$watchedCond = $dbr->expr( 'wl_user', '!=', null );
		if ( $this->enableExpiry ) {
			// Expired watchlist items stay in the DB after their expiry time until they're purged,
			// so it's not enough to only check for wl_user.
			$dbNow = $dbr->timestamp();
			$isExpiredCond = $dbr->expr( 'we_expiry', '!=', null )->and( 'we_expiry', '<=', $dbNow );
			$isNotExpiredCond = $dbr->expr( 'we_expiry', '=', null )->or( 'we_expiry', '>', $dbNow );
			$notwatchedCond = $notwatchedCond
				->orExpr( $isExpiredCond );
			$watchedCond = $watchedCond
				->andExpr( $isNotExpiredCond );
		} else {
			$isNotExpiredCond = null;
		}
		$newCond = new RawSQLExpression( 'rc_timestamp >= wl_notificationtimestamp' );

		if ( $selectedValues === [ 'notwatched' ] ) {
			$query->joinForConds( 'watchlist' )->left();
			$this->maybeJoinExpiry( $query );
			$query->where( $notwatchedCond );
			return;
		}

		if ( $selectedValues === [ 'watched' ] ) {
			$query->joinForConds( 'watchlist' )->reorderable();
			$this->maybeJoinExpiry( $query );
			if ( $isNotExpiredCond ) {
				$query->where( $isNotExpiredCond );
			}
		}

		if ( $selectedValues === [ 'watchednew' ] ) {
			$query->joinForConds( 'watchlist' )->reorderable();
			$this->maybeJoinExpiry( $query );
			if ( $isNotExpiredCond ) {
				$query->where( $isNotExpiredCond );
			}
			$query->where( $newCond );
			return;
		}

		if ( $selectedValues === [ 'notwatched', 'watched' ] ) {
			// no filters
			return;
		}

		if ( $selectedValues === [ 'notwatched', 'watchednew' ] ) {
			$query->joinForFields( 'watchlist' )->left();
			$this->maybeJoinExpiry( $query );
			$query->where( $notwatchedCond
				->orExpr(
					$watchedCond
						->andExpr( $newCond )
				) );
			return;
		}

		if ( $selectedValues === [ 'watched', 'watchednew' ] ) {
			$query->joinForConds( 'watchlist' )->reorderable();
			$this->maybeJoinExpiry( $query );
			if ( $isNotExpiredCond ) {
				$query->where( $isNotExpiredCond );
			}
			return;
		}

		// no filters
	}

	private function maybeJoinExpiry( QueryBackend $query ) {
		if ( $this->enableExpiry ) {
			$query->joinForConds( 'watchlist_expiry' )->left();
		}
	}
}
