<?php

namespace Wikimedia\Rdbms;

use Wikimedia\Rdbms\Database\DbQuoter;

/**
 * Raw SQL expression to be used in query builders
 *
 * @note This should be used very rarely and NEVER with user input.
 *
 * @newable
 * @since 1.42
 */
class RawSQLExpression extends Expression {
	private string $expression = '';

	/**
	 * This should be used very rarely and NEVER with user input.
	 *
	 * It can be used as a boolean expression in a WHERE condition, in cases where `$db->expr()`
	 * can't be used because the expression doesn't have a left side, operator and right side,
	 * e.g. for conditions like `WHERE EXISTS( SELECT ... )`.
	 *
	 *   $queryBuilder->where( new RawSQLExpression( 'EXISTS(' . $subqueryBuilder->getSQL() . ')' ) )
	 *
	 * Or when the condition is a simple SQL literal and writing it as SQL is the easiest:
	 *
	 *   $queryBuilder->where( new RawSQLExpression( 'range_start != range_end' ) )
	 *
	 * (See also RawSQLValue, which may be more readable in other cases.)
	 *
	 * @param string $expression Expression (SQL fragment)
	 * @param-taint $expression exec_sql
	 * @since 1.42
	 */
	public function __construct( string $expression ) {
		$this->expression = $expression;
	}

	/**
	 * @internal to be used by rdbms library only
	 */
	public function toSql( DbQuoter $dbQuoter ): string {
		return $this->expression;
	}

	public function toGeneralizedSql(): string {
		return $this->expression;
	}
}
