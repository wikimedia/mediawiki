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
