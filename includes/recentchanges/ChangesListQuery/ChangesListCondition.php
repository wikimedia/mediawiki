<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use stdClass;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * Interface for modules implementing a boolean condition which may match a row
 * in a ChangesListResult.
 *
 * @since 1.45
 */
interface ChangesListCondition {
	/**
	 * Add a value to the set of required values. This may be called multiple
	 * times to require multiple values.
	 *
	 * The type of the value is defined by the subclass.
	 *
	 * The query should be modified such that each row returned matches at least
	 * one of the required values AND does not match the excluded values. If the
	 * set of required values is empty, the excluded condition is evaluated
	 * alone, as if all possible values were required.
	 *
	 * @param mixed $value
	 */
	public function require( $value ): void;

	/**
	 * Add a value to the set of excluded values. This may be called multiple
	 * times to exclude multiple values.
	 *
	 * The type of the value is defined by the subclass.
	 *
	 * The query should be modified such that each row returned matches at least
	 * one of the required values AND does not match the excluded values. If the
	 * set of required values is empty, the excluded condition is evaluated
	 * alone, as if all possible values were required.
	 *
	 * @param mixed $value
	 */
	public function exclude( $value ): void;

	/**
	 * Validate a value and return its normalized form.
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public function validateValue( $value );

	/**
	 * Evaluate the filter condition against a row, determining whether it is
	 * true or false. Ignore the values set with require() and exclude(), use
	 * only the value passed as a parameter.
	 *
	 * To ensure that $row has the required fields present, capture() must be
	 * called before evaluate(). This will signal to prepareQuery() that the
	 * fields should be added.
	 *
	 * @param stdClass $row
	 * @param mixed $value The validated value
	 * @return bool
	 */
	public function evaluate( stdClass $row, $value ): bool;

	/**
	 * Set a flag indicating that evaluate() will be called with rows from the
	 * query result.
	 */
	public function capture(): void;

	/**
	 * Check whether capture() has been called.
	 * @return bool
	 */
	public function isCaptured(): bool;

	/**
	 * Add conditions and joins to the query in order to implement require()
	 * and exclude(). Add fields and joins to the query so that evaluate() can
	 * be implemented.
	 *
	 * @param IReadableDatabase $dbr
	 * @param QueryBackend $query
	 */
	public function prepareQuery( IReadableDatabase $dbr, QueryBackend $query ): void;
}
