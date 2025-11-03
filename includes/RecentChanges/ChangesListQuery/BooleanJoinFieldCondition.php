<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use stdClass;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * A tri-state boolean from a field of a potentially left-joined table.
 * May be either true, false or null.
 *
 * @since 1.45
 */
class BooleanJoinFieldCondition extends ChangesListConditionBase {
	public function __construct(
		private string $fieldName,
		private string $tableName
	) {
	}

	/** @inheritDoc */
	public function validateValue( $value ) {
		if ( $value === null || is_bool( $value ) ) {
			return $value;
		}
		throw new \InvalidArgumentException( 'Invalid value for tri-state boolean' );
	}

	/** @inheritDoc */
	public function evaluate( stdClass $row, $value ): bool {
		$rowValue = $row->{ $this->fieldName };
		if ( $rowValue !== null ) {
			$rowValue = (bool)$rowValue;
		}
		return $rowValue === $value;
	}

	/** @inheritDoc */
	protected function prepareCapture( IReadableDatabase $dbr, QueryBackend $query ) {
		$query->joinForFields( $this->tableName )->weakLeft();
		$query->fields( $this->fieldName );
	}

	/** @inheritDoc */
	protected function prepareConds( IReadableDatabase $dbr, QueryBackend $query ) {
		$set = $this->getEnumValues( [ false, true, null ] );
		if ( $set === null ) {
			return;
		} elseif ( $set === [] ) {
			$query->forceEmptySet();
		}
		$null = in_array( null, $set, true );
		$false = in_array( false, $set, true );
		$true = in_array( true, $set, true );
		if ( $null ) {
			$query->joinForConds( $this->tableName )->left();
		} else {
			$query->joinForConds( $this->tableName )->reorderable();
		}

		if ( !$true || !$false || $null ) {
			$orConds = [];
			foreach ( $set as $value ) {
				$orConds[] = $dbr->expr( $this->fieldName, '=', $value );
			}
			if ( count( $orConds ) > 1 ) {
				$query->where( $dbr->orExpr( $orConds ) );
			} else {
				$query->where( $orConds[0] );
			}
		} /* else implied by the join conditions */
	}
}
