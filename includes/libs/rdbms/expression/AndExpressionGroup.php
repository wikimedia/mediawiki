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

	// While these methods are not actually "side-effect-free" (they mutate the object),
	// we annotate them with @phan-side-effect-free for consistency with Expression.

	/**
	 * @param string $field
	 * @param-taint $field exec_sql
	 * @param string $op One of '>', '<', '!=', '=', '>=', '<=', IExpression::LIKE, IExpression::NOT_LIKE
	 * @param-taint $op exec_sql
	 * @param string|int|float|bool|Blob|null|LikeValue|non-empty-list<string|int|float|bool|Blob> $value
	 * @param-taint $value escapes_sql
	 * @phan-side-effect-free
	 */
	public function and( string $field, string $op, $value ): AndExpressionGroup {
		$expr = new Expression( $field, $op, $value );
		$this->add( $expr );
		return $this;
	}

	/**
	 * @param IExpression $expr
	 * @return AndExpressionGroup
	 * @phan-side-effect-free
	 */
	public function andExpr( IExpression $expr ): AndExpressionGroup {
		$this->add( $expr );
		return $this;
	}
}
