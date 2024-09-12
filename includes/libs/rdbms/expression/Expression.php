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
	/** @var ?scalar|RawSQLValue|Blob|LikeValue|non-empty-list<scalar|Blob> */
	private $value;

	/**
	 * Store an expression
	 *
	 * @param string $field
	 * @param-taint $field exec_sql
	 * @param string $op One of '>', '<', '!=', '=', '>=', '<=', IExpression::LIKE, IExpression::NOT_LIKE
	 * @phan-param '\x3E'|'\x3C'|'!='|'='|'\x3E='|'\x3C='|'LIKE'|'NOT LIKE' $op
	 * @param-taint $op exec_sql
	 * @param ?scalar|RawSQLValue|Blob|LikeValue|non-empty-list<scalar|Blob> $value
	 * @param-taint $value escapes_sql
	 * @internal Outside of rdbms, Use IReadableDatabase::expr() to create an expression object.
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

		if ( is_array( $value ) ) {
			if ( !$value ) {
				throw new InvalidArgumentException( "The array of values can't be empty" );
			} elseif ( !array_is_list( $value ) ) {
				throw new InvalidArgumentException( "The array of values must be a list" );
			} elseif ( in_array( null, $value, true ) ) {
				throw new InvalidArgumentException( "NULL can't be in the array of values" );
			}
		}

		if ( in_array( $op, [ IExpression::LIKE, IExpression::NOT_LIKE ] ) && !( $value instanceof LikeValue ) ) {
			throw new InvalidArgumentException( "Value for 'LIKE' expression must be of LikeValue type" );
		}
		if ( !in_array( $op, [ IExpression::LIKE, IExpression::NOT_LIKE ] ) && ( $value instanceof LikeValue ) ) {
			throw new InvalidArgumentException( "LikeValue may only be used with 'LIKE' expression" );
		}

		$field = trim( $field );
		if ( !preg_match( '/^[A-Za-z\d\._]+$/', $field ) ) {
			throw new InvalidArgumentException( "$field might contain SQL injection" );
		}
		$this->field = $field;
		$this->op = $op;
		$this->value = $value;
	}

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
	public function and( string $field, string $op, $value ): AndExpressionGroup {
		$exprGroup = new AndExpressionGroup( $this );
		return $exprGroup->and( $field, $op, $value );
	}

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
		$exprGroup = new OrExpressionGroup( $this );
		return $exprGroup->or( $field, $op, $value );
	}

	/**
	 * @param IExpression $expr
	 * @return AndExpressionGroup
	 * @phan-side-effect-free
	 */
	public function andExpr( IExpression $expr ): AndExpressionGroup {
		$exprGroup = new AndExpressionGroup( $this );
		return $exprGroup->andExpr( $expr );
	}

	/**
	 * @param IExpression $expr
	 * @return OrExpressionGroup
	 * @phan-side-effect-free
	 */
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
			if ( count( $this->value ) === 1 ) {
				$value = $this->value[0];
				if ( $this->op === '=' ) {
					return $this->field . ' = ' . $dbQuoter->addQuotes( $value );
				} elseif ( $this->op === '!=' ) {
					return $this->field . ' != ' . $dbQuoter->addQuotes( $value );
				} else {
					throw new LogicException( "Operator $this->op can't take array as value" );
				}
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
		if ( $this->value instanceof LikeValue ) {
			// implies that `op` is LIKE or NOT_LIKE, checked in constructor
			return $this->field . ' ' . $this->op . ' ' . $this->value->toSql( $dbQuoter );
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
