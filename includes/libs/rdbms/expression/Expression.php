<?php

namespace Wikimedia\Rdbms;

use InvalidArgumentException;
use LogicException;
use Wikimedia\Rdbms\Database\DbQuoter;

/**
 * A composite leaf representing an expression.
 *
 * @since 1.42
 */
class Expression implements IExpression {
	private string $field;
	private string $op;
	private $value;

	/**
	 * Store an expression
	 *
	 * @param string $field
	 * @param-taint $field exec_sql
	 * @param string $op One of '>', '<', '!=', '=', '>=', '<='
	 * @param-taint $op exec_sql
	 * @param string|int|float|bool|Blob|null|non-empty-list<string|int|float|bool|Blob> $value
	 * @param-taint $value escapes_sql
	 */
	public function __construct( string $field, string $op, $value ) {
		if ( !in_array( $op, IExpression::ACCEPTABLE_OPERATORS ) ) {
			throw new InvalidArgumentException( "Operator $op is not supported" );
		}
		if (
			( is_array( $value ) || $value === null ) &&
			!in_array( $op, [ '!=', '=' ] )
		) {
			throw new InvalidArgumentException( "Operator $op can't take array or null as value" );
		}

		if ( is_array( $value ) && in_array( null, $value, true ) ) {
			throw new InvalidArgumentException( "NULL can't be in the array of values" );
		}
		$this->field = $field;
		$this->op = $op;
		$this->value = $value;
	}

	/**
	 * @param string $field
	 * @param-taint $field exec_sql
	 * @param string $op One of '>', '<', '!=', '=', '>=', '<='
	 * @param-taint $op exec_sql
	 * @param string|int|float|bool|Blob|null|non-empty-list<string|int|float|bool|Blob> $value
	 * @param-taint $value escapes_sql
	 */
	public function and( string $field, string $op, $value ): AndExpressionGroup {
		$exprGroup = new AndExpressionGroup( $this );
		return $exprGroup->and( $field, $op, $value );
	}

	/**
	 * @param string $field
	 * @param-taint $field exec_sql
	 * @param string $op One of '>', '<', '!=', '=', '>=', '<='
	 * @param-taint $op exec_sql
	 * @param string|int|float|bool|Blob|null|non-empty-list<string|int|float|bool|Blob> $value
	 * @param-taint $value escapes_sql
	 */
	public function or( string $field, string $op, $value ): OrExpressionGroup {
		$exprGroup = new OrExpressionGroup( $this );
		return $exprGroup->or( $field, $op, $value );
	}

	public function andExpr( IExpression $expr ): AndExpressionGroup {
		$exprGroup = new AndExpressionGroup( $this );
		return $exprGroup->andExpr( $expr );
	}

	public function orExpr( IExpression $expr ): OrExpressionGroup {
		$exprGroup = new OrExpressionGroup( $this );
		return $exprGroup->orExpr( $expr );
	}

	/**
	 * @internal to be used by rdbms library only
	 * @return-taint none
	 */
	public function toSql( DbQuoter $dbQuoter ): string {
		if ( is_array( $this->value ) ) {
			if ( !$this->value ) {
				throw new InvalidArgumentException( "The array of values can't be empty." );
			}
			$list = implode( ',', array_map( static fn ( $value ) => $dbQuoter->addQuotes( $value ), $this->value ) );
			if ( $this->op === '=' ) {
				return $this->field . " IN ($list)";
			} elseif ( $this->op === '!=' ) {
				return $this->field . " NOT IN ($list)";
			} else {
				throw new LogicException( "Operator $this->op can't take array as value" );
			}
		}
		if ( $this->value === null ) {
			if ( $this->op === '=' ) {
				return $this->field . " IS NULL";
			} elseif ( $this->op === '!=' ) {
				return $this->field . " IS NOT NULL";
			} else {
				throw new LogicException( "Operator $this->op can't take null as value" );
			}
		}
		return $this->field . ' ' . $this->op . ' ' . $dbQuoter->addQuotes( $this->value );
	}

	/**
	 * @internal to be used by rdbms library only
	 */
	public function toGeneralizedSql(): string {
		return $this->field . ' ' . $this->op . ' ?';
	}
}
