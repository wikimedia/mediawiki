<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use stdClass;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * Check if a change has a certain watchlist label. Watchlist expiry is not
 * checked here since this filter is typically used in conjunction with
 * WatchedCondition.
 *
 * @since 1.46
 */
class WatchlistLabelCondition extends ChangesListConditionBase {

	public const LABEL_IDS = 'wlm_label_summary';

	/** @inheritDoc */
	public function validateValue( $value ) {
		if ( !is_numeric( $value ) ) {
			throw new \InvalidArgumentException( "Watchlist label must be numeric" );
		}
		return (int)$value;
	}

	/** @inheritDoc */
	public function evaluate( stdClass $row, $value ): bool {
		if ( !$row->wlm_label_summary ) {
			return false;
		}
		return in_array( (string)$value, explode( ',', $row->wlm_label_summary ), true );
	}

	/** @inheritDoc */
	protected function prepareCapture( IReadableDatabase $dbr, QueryBackend $query ) {
		$subquery = $dbr->newSelectQueryBuilder()
			->select( 'wlm_label' )
			->from( 'watchlist_label_member' )
			->where( [ 'wlm_item=wl_id' ] )
			->buildGroupConcatField( ',' );
		$query->fields( [ self::LABEL_IDS => $subquery ] );
		$query->joinForFields( 'watchlist' )->weakLeft();
	}

	/** @inheritDoc */
	protected function prepareConds( IReadableDatabase $dbr, QueryBackend $query ) {
		[ $required, $excluded ] = $this->getUniqueValues();
		if ( $required === [] ) {
			$query->forceEmptySet();
		} elseif ( $required ) {
			$query->joinForConds( 'watchlist' )->reorderable();
			$query->joinForConds( 'watchlist_label_member' )->reorderable();
			$query->where( $dbr->expr( 'wlm_label', '=', $required ) );
			if ( count( $required ) > 1 ) {
				$query->distinct();
			}
		} elseif ( $excluded ) {
			$query->joinForConds( 'watchlist' )->weakLeft();
			$query->joinForConds( 'watchlist_label_member' )->left()
				->on( $dbr->expr( 'wlm_label', '=', $excluded ) );
			$query->where( $dbr->expr( 'wlm_label', '=', null ) );
		}
	}
}
