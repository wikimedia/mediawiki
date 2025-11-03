<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use Stringable;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * Shared functionality for filter condition modules.
 *
 * @since 1.45
 */
abstract class ChangesListConditionBase implements ChangesListCondition {
	/** @var bool */
	private $captured = false;
	/** @var array */
	protected $required = [];
	/** @var array */
	protected $excluded = [];

	/** @inheritDoc */
	public function capture(): void {
		$this->captured = true;
	}

	/** @inheritDoc */
	public function isCaptured(): bool {
		return $this->captured;
	}

	/** @inheritDoc */
	public function require( $value ): void {
		$this->required[] = $this->validateValue( $value );
	}

	/** @inheritDoc */
	public function exclude( $value ): void {
		$this->excluded[] = $this->validateValue( $value );
	}

	/**
	 * Interpret the required and excluded values, combining them and removing
	 * duplicates. If a value is both required and excluded, forceEmptySet()
	 * should be called, and this is flagged by returning an empty array for
	 * the first list element (required). If no values were required, this is
	 * flagged by returning null for the first list element.
	 *
	 * @return array{?array,1:array} Required and excluded values
	 */
	protected function getUniqueValues() {
		$required = $this->required;
		$excluded = $this->excluded;
		sort( $required );
		sort( $excluded );
		$required = array_unique( $required );
		$excluded = array_unique( $excluded );
		if ( $required ) {
			// Remove excluded values from the required set
			$diff = array_diff( $required, $excluded );
			return [ array_values( $diff ), [] ];
		}
		return [ null, array_values( $excluded ) ];
	}

	/**
	 * Interpret the required and excluded values using a known set of all
	 * possible values. Return the values which should be included in the
	 * result, or null if all values can be included and thus the condition
	 * can be omitted.
	 *
	 * @param array $allValues
	 * @return array|null
	 */
	protected function getEnumValues( $allValues ) {
		$values = array_udiff( $allValues, $this->excluded, $this->compareStrict( ... ) );
		if ( $this->required ) {
			$values = array_uintersect( $values, $this->required, $this->compareStrict( ... ) );
		}
		if ( count( $allValues ) === count( $values ) ) {
			return null;
		} else {
			return array_values( $values );
		}
	}

	/**
	 * @param mixed $a
	 * @param mixed $b
	 * @return int
	 */
	private function compareStrict( $a, $b ) {
		$typeA = gettype( $a );
		$typeB = gettype( $b );
		if ( $typeA < $typeB ) {
			return -1;
		} elseif ( $typeA > $typeB ) {
			return 1;
		} elseif ( $a instanceof Stringable && $b instanceof Stringable ) {
			return (string)$a <=> (string)$b;
		} else {
			return $a <=> $b;
		}
	}

	public function prepareQuery( IReadableDatabase $dbr, QueryBackend $query ): void {
		if ( $this->isCaptured() ) {
			$this->prepareCapture( $dbr, $query );
		}
		$this->prepareConds( $dbr, $query );
	}

	/**
	 * Prepare the query for evaluate(). This is called only if the capture
	 * flag is true.
	 *
	 * @param IReadableDatabase $dbr
	 * @param QueryBackend $query
	 */
	abstract protected function prepareCapture( IReadableDatabase $dbr, QueryBackend $query );

	/**
	 * Add conditions to the query according to the values passed to require()
	 * and exclude(). Subclasses may use getUniqueValuesWithUnion() to get the
	 * required and excluded values.
	 *
	 * @param IReadableDatabase $dbr
	 * @param QueryBackend $query
	 */
	abstract protected function prepareConds( IReadableDatabase $dbr, QueryBackend $query );
}
