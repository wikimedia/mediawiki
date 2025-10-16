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

	private const ALL_VALUES = [ 'notwatched', 'watchedold', 'watchednew' ];

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
		if ( !in_array( $value, self::ALL_VALUES, true ) ) {
			throw new \InvalidArgumentException( 'unknown value for watched filter' );
		}
		return $value;
	}

	/** @inheritDoc */
	public function evaluate( stdClass $row, $value ): bool {
		if ( !$this->userId
			|| ( $this->enableExpiry && $row->we_expiry !== null
				&& MWTimestamp::convert( TS_UNIX, $row->we_expiry ) <= wfTimestamp() )
			|| $row->wl_user === null
		) {
			return $value === 'notwatched';
		} elseif ( $row->rc_timestamp &&
			$row->wl_notificationtimestamp &&
			$row->rc_timestamp >= $row->wl_notificationtimestamp
		) {
			return $value === 'watchednew';
		} else {
			return $value === 'watchedold';
		}
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
		$set = $this->getEnumValues( self::ALL_VALUES );
		if ( $set === null ) {
			return;
		} elseif ( $set === [] ) {
			$query->forceEmptySet();
			return;
		}
		$notwatched = in_array( 'notwatched', $set, true );
		$watchedold = in_array( 'watchedold', $set, true );
		$watchednew = in_array( 'watchednew', $set, true );

		if ( !$this->userId ) {
			if ( !$notwatched ) {
				$query->forceEmptySet();
			}
			return;
		}

		$orConds = [];
		if ( $notwatched ) {
			// Permit not watched: left join, allow join failure or expired
			$query->joinForConds( 'watchlist' )->left();
			$orConds[] = $dbr->expr( 'wl_user', '=', null );
			if ( $this->enableExpiry ) {
				$query->joinForConds( 'watchlist_expiry' )->left();
				$orConds[] = $dbr->expr( 'we_expiry', '<=', $dbr->timestamp() );
			}
		} else {
			// Require watched: reorderable join, do not allow expired
			$query->adjustDensity( QueryBackend::DENSITY_WATCHLIST )
				->joinOrderHint( QueryBackend::JOIN_ORDER_OTHER );
			$query->joinForConds( 'watchlist' )->reorderable();
			if ( $this->enableExpiry ) {
				$query->joinForConds( 'watchlist_expiry' )->left();
				$query->where(
					$dbr->expr( 'we_expiry', '=', null )
						->or( 'we_expiry', '>', $dbr->timestamp() )
				);
			}
		}

		if ( $watchedold xor $watchednew ) {
			if ( $watchedold ) {
				$oldExpr = ( new RawSQLExpression( 'rc_timestamp < wl_notificationtimestamp' ) )
					->or( 'wl_notificationtimestamp', '=', null );
			} else {
				$oldExpr = new RawSQLExpression( 'rc_timestamp >= wl_notificationtimestamp' );
			}
			if ( $notwatched ) {
				// Technically redundant since comparison with null wl_notificationtimestamp
				// is always false
				$oldExpr = $dbr->expr( 'wl_user', '!=', null )->andExpr( $oldExpr );
			}
			$orConds[] = $oldExpr;
		}

		if ( count( $orConds ) === 1 ) {
			$query->where( $orConds[0] );
		} elseif ( count( $orConds ) ) {
			$query->where( $dbr->orExpr( $orConds ) );
		}
	}
}
