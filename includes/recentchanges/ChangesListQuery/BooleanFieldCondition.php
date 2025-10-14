<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use Wikimedia\Rdbms\IReadableDatabase;

/**
 * A filter module which builds conditions for a boolean field.
 *
 * @since 1.45
 */
class BooleanFieldCondition extends ChangesListConditionBase {
	/**
	 * @param string $fieldName
	 */
	public function __construct( private $fieldName ) {
	}

	/** @inheritDoc */
	public function validateValue( $value ) {
		if ( $value !== null && !is_bool( $value ) ) {
			throw new \InvalidArgumentException(
				"value for {$this->fieldName} must be bool or null"
			);
		}
		return $value ?? true;
	}

	/** @inheritDoc */
	public function evaluate( $row, $value ): bool {
		return (bool)$row->{$this->fieldName} === $value;
	}

	/** @inheritDoc */
	protected function prepareCapture( IReadableDatabase $dbr, QueryBackend $query ) {
		$query->fields( $this->fieldName );
	}

	/** @inheritDoc */
	public function prepareConds( IReadableDatabase $dbr, QueryBackend $query ) {
		$set = $this->getEnumValues( [ false, true ] );
		if ( $set === null ) {
			return;
		} elseif ( $set === [ true ] ) {
			$query->where( $dbr->expr( $this->fieldName, '=', 1 ) );
		} elseif ( $set === [ false ] ) {
			$query->where( $dbr->expr( $this->fieldName, '=', 0 ) );
		}
	}
}
