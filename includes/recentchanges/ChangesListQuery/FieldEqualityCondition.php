<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use stdClass;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * A filter condition module which uses equals or not-equals operators
 *
 * @since 1.45
 */
class FieldEqualityCondition extends ChangesListConditionBase {
	/**
	 * @param string $fieldName The field name
	 * @param bool $isNullable Whether the field is nullable
	 */
	public function __construct(
		private $fieldName,
		private $isNullable = false,
	) {
	}

	/** @inheritDoc */
	public function validateValue( $value ) {
		if ( !is_int( $value ) && !is_string( $value ) ) {
			throw new \InvalidArgumentException(
				"{$this->fieldName} must be int or string" );
		}
		return $value;
	}

	/** @inheritDoc */
	public function evaluate( stdClass $row, $value ): bool {
		return (string)$row->{ $this->fieldName } === (string)$value;
	}

	/** @inheritDoc */
	protected function prepareCapture( IReadableDatabase $dbr, QueryBackend $query ) {
		$query->fields( $this->fieldName );
	}

	/** @inheritDoc */
	protected function prepareConds( IReadableDatabase $dbr, QueryBackend $query ) {
		[ $required, $excluded ] = $this->getUniqueValues();
		if ( $required === [] ) {
			$query->forceEmptySet();
		} elseif ( $required ) {
			$query->where( $dbr->expr( $this->fieldName, '=', $required ) );
		} elseif ( $excluded ) {
			$cond = $dbr->expr( $this->fieldName, '!=', $excluded );
			if ( $this->isNullable ) {
				$cond = $cond->or( $this->fieldName, '=', null );
			}
			$query->where( $cond );
		}
	}
}
