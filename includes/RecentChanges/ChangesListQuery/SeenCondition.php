<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use MediaWiki\Page\PageReferenceValue;
use MediaWiki\User\UserIdentity;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use stdClass;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\RawSQLExpression;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * Check if the recentchange row has been seen by the current watchlist user.
 *
 * @since 1.45
 */
class SeenCondition extends ChangesListConditionBase {
	private ?UserIdentity $user = null;

	public function __construct(
		private WatchedItemStoreInterface $watchedItemStore
	) {
	}

	public function setUser( UserIdentity $user ) {
		if ( $user->getId() ) {
			$this->user = $user;
		} else {
			$this->user = null;
		}
	}

	/** @inheritDoc */
	public function evaluate( stdClass $row, $value ): bool {
		if ( !$this->user ) {
			return false;
		}
		$firstUnseen = $this->getLatestNotificationTimestamp( $row );
		return $firstUnseen === null
			|| $firstUnseen > ConvertibleTimestamp::convert( TS_MW, $row->rc_timestamp );
	}

	/**
	 * @param stdClass $row
	 * @return string|null TS_MW timestamp of first unseen revision or null if there isn't one
	 */
	private function getLatestNotificationTimestamp( $row ) {
		if ( $row->rc_title === '' ) {
			return null;
		}
		return $this->watchedItemStore->getLatestNotificationTimestamp(
			$row->wl_notificationtimestamp,
			$this->user,
			new PageReferenceValue( (int)$row->rc_namespace, $row->rc_title, PageReferenceValue::LOCAL )
		);
	}

	/**
	 * @param null $value
	 * @return null
	 */
	public function validateValue( $value ) {
		if ( $value !== null ) {
			throw new \InvalidArgumentException(
				'boolean filter "named" does not take a value' );
		}
		return $value;
	}

	/** @inheritDoc */
	protected function prepareCapture( IReadableDatabase $dbr, QueryBackend $query ) {
		if ( $this->user ) {
			$query->joinForFields( 'watchlist' )->weakLeft();
			$query->fields( [ 'rc_timestamp', 'rc_namespace', 'rc_title', 'wl_notificationtimestamp' ] );
		}
	}

	/** @inheritDoc */
	protected function prepareConds( IReadableDatabase $dbr, QueryBackend $query ) {
		if ( !$this->user ) {
			if ( $this->required ) {
				$query->forceEmptySet();
			}
			return;
		}

		[ $required, $excluded ] = $this->getUniqueValues();
		if ( $required === [] ) {
			$query->forceEmptySet();
		} elseif ( $required ) {
			$query->joinForConds( 'watchlist' )->weakLeft();
			$query->where( $dbr->expr( 'wl_notificationtimestamp', '=', null )
				->orExpr( new RawSQLExpression( 'rc_timestamp < wl_notificationtimestamp' ) ) );
		} elseif ( $excluded ) {
			$query->joinForConds( 'watchlist' )->weakLeft();
			$query->where( $dbr->expr( 'wl_notificationtimestamp', '!=', null )
				->andExpr( new RawSQLExpression( 'rc_timestamp >= wl_notificationtimestamp' ) ) );
		}
	}
}
