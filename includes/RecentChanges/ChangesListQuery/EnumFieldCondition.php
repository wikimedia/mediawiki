<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use stdClass;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * An equals or not-equals comparison with a field that is known to have a
 * small fixed set of values. The generated condition will typically have the
 * form `field IN (values)`, even when a value is excluded.
 *
 * This is only appropriate when the set of all values is small and the field
 * is indexed.
 *
 * This class works with either integer or string types, taking advantage of
 * the fact that DB results are conventionally cast to strings.
 *
 * @since 1.45
 */
class EnumFieldCondition extends ChangesListConditionBase {
	/**
	 * @param string $fieldName The field name
	 * @param (int|string)[] $allValues All possible values for the field
	 */
	public function __construct(
		private string $fieldName,
		private array $allValues
	) {
	}

	/**
	 * @param int|string $value
	 * @return int|string
	 */
	public function validateValue( $value ) {
		if ( !is_int( $value ) && !is_string( $value ) ) {
			throw new \InvalidArgumentException(
				"{$this->fieldName} must be a int or string" );
		}
		return $value;
	}

	/** @inheritDoc */
	public function evaluate( stdClass $row, $value ): bool {
		return (string)$row->{$this->fieldName} === (string)$value;
	}

	/** @inheritDoc */
	protected function prepareCapture( IReadableDatabase $dbr, QueryBackend $query ) {
		$query->fields( $this->fieldName );
	}

	/** @inheritDoc */
	protected function prepareConds( IReadableDatabase $dbr, QueryBackend $query ) {
		$values = $this->getEnumValues( $this->allValues );
		if ( $values === null ) {
			// all values selected
		} elseif ( $values ) {
			$query->where( $dbr->expr( $this->fieldName, '=', $values ) );
		} else {
			$query->forceEmptySet();
		}
	}
}
