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
	 * @param string $op One of '>', '<', '!=', '=', '>=', '<=', IExpression::LIKE, IExpression::NOT_LIKE
	 * @phan-param '\x3E'|'\x3C'|'!='|'='|'\x3E='|'\x3C='|'LIKE'|'NOT LIKE' $op
	 * @param-taint $op exec_sql
	 * @param ?scalar|RawSQLValue|Blob|LikeValue|non-empty-list<scalar|Blob> $value
	 * @param-taint $value escapes_sql
	 */
	#[\NoDiscard]
	public function and( string $field, string $op, $value ): AndExpressionGroup {
		$expr = new Expression( $field, $op, $value );
		$this->add( $expr );
		return $this;
	}

	/**
	 * @param IExpression $expr
	 * @return AndExpressionGroup
	 */
	#[\NoDiscard]
	public function andExpr( IExpression $expr ): AndExpressionGroup {
		$this->add( $expr );
		return $this;
	}
}
