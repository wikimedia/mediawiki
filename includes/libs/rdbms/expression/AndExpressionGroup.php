<?php

namespace Wikimedia\Rdbms;

/**
 * Representing a group of expressions chained via AND
 *
 * @since 1.42
 */
class AndExpressionGroup extends ExpressionGroup {
	protected function getType(): string {
		return 'AND';
	}

	/**
	 * @param string $field
	 * @param-taint $field exec_sql
	 * @param string $op One of '>', '<', '!=', '=', '>=', '<='
	 * @param-taint $op exec_sql
	 * @param string|int|float|null|bool|Blob|array $value
	 * @param-taint $value escapes_sql
	 */
	public function and( string $field, string $op, $value ): AndExpressionGroup {
		$expr = new Expression( $field, $op, $value );
		$this->add( $expr );
		return $this;
	}

	public function andExpr( IExpression $expr ): AndExpressionGroup {
		$this->add( $expr );
		return $this;
	}
}
