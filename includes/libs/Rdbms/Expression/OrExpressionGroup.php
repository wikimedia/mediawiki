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

	// While these methods are not actually "side-effect-free" (they mutate the object),
	// we annotate them with @phan-side-effect-free for consistency with Expression.

	/**
	 * @param string $field
	 * @param-taint $field exec_sql
	 * @param string $op One of '>', '<', '!=', '=', '>=', '<=', IExpression::LIKE, IExpression::NOT_LIKE
	 * @phan-param '\x3E'|'\x3C'|'!='|'='|'\x3E='|'\x3C='|'LIKE'|'NOT LIKE' $op
	 * @param-taint $op exec_sql
	 * @param ?scalar|RawSQLValue|Blob|LikeValue|non-empty-list<scalar|Blob> $value
	 * @param-taint $value escapes_sql
	 * @phan-side-effect-free
	 */
	public function or( string $field, string $op, $value ): OrExpressionGroup {
		$expr = new Expression( $field, $op, $value );
		$this->add( $expr );
		return $this;
	}

	/**
	 * @param IExpression $expr
	 * @return OrExpressionGroup
	 * @phan-side-effect-free
	 */
	public function orExpr( IExpression $expr ): OrExpressionGroup {
		$this->add( $expr );
		return $this;
	}
}
