<?php

namespace Wikimedia\Rdbms;

/**
 * Representing a group of expressions chained via OR
 *
 * @since 1.42
 */
class OrExpressionGroup extends ExpressionGroup {
	protected function getType(): string {
		return 'OR';
	}

	/**
	 * @param string $field
	 * @param-taint $field exec_sql
	 * @param string $op One of '>', '<', '!=', '=', '>=', '<='
	 * @param-taint $op exec_sql
	 * @param string|int|float|null|bool|Blob|array $value
	 * @param-taint $value escapes_sql
	 */
	public function or( string $field, string $op, $value ): OrExpressionGroup {
		$expr = new Expression( $field, $op, $value );
		$this->add( $expr );
		return $this;
	}

	public function orExpr( IExpression $expr ): OrExpressionGroup {
		$this->add( $expr );
		return $this;
	}
}
