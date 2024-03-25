<?php

namespace Wikimedia\Rdbms;

use Wikimedia\Rdbms\Database\DbQuoter;

/**
 * Raw SQL value to be used in expression builders
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
	 * Most common usecases include comparing two columns
	 * or function calls (e.g. COUNT(*))
	 *
	 * @param string $expression value of the expression
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
