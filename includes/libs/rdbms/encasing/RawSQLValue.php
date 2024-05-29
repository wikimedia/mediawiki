<?php

namespace Wikimedia\Rdbms;

/**
 * Raw SQL value to be used in query builders
 *
 * @note This should be used very rarely and NEVER with user input.
 *
 * @newable
 * @since 1.43
 */
class RawSQLValue {
	private string $value = '';

	/**
	 * This should be used very rarely and NEVER with user input.
	 *
	 * Most common usecases is the value in a SET clause of UPDATE,
	 * e.g. for updates like `total_pages = total_pages + 1`:
	 *
	 *   $queryBuilder->set( [ 'total_pages' => new RawSQLValue( 'total_pages + 1' ) ] )
	 *
	 * …or as one side of a comparison in a WHERE condition,
	 * e.g. for conditions like `range_start = range_end`, `range_start != range_end`:
	 *
	 *   $queryBuilder->where( [ 'range_start' => new RawSQLValue( 'range_end' ) ] )
	 *   $queryBuilder->where( $db->expr( 'range_start', '!=', new RawSQLValue( 'range_end' ) ) )
	 *
	 * (When all values are literals, consider whether using RawSQLExpression is more readable.)
	 *
	 * @param string $value Value (SQL fragment)
	 * @param-taint $value exec_sql
	 * @since 1.43
	 */
	public function __construct( string $value ) {
		$this->value = $value;
	}

	/**
	 * @internal to be used by rdbms library only
	 */
	public function toSql(): string {
		return $this->value;
	}
}
