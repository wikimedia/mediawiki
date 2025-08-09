<?php

namespace Wikimedia\Rdbms;

use InvalidArgumentException;
use Wikimedia\Rdbms\Database\DbQuoter;

/**
 * A composite node representing a group of expressions.
 *
 * @since 1.42
 */
abstract class ExpressionGroup implements IExpression {
	/**
	 * @var IExpression[]
	 */
	protected array $children = [];

	/**
	 * @param IExpression ...$children
	 * @internal Outside of rdbms, Use IReadableDatabase::andExpr() or ::orExpr to create an expression group object
	 */
	public function __construct( IExpression ...$children ) {
		$this->children = $children;
	}

	final protected function add( IExpression $expression ) {
		$this->children[] = $expression;
	}

	abstract protected function getType(): string;

	/**
	 * @internal to rdbms
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param non-empty-array<string,?scalar|RawSQLValue|Blob|LikeValue|non-empty-list<scalar|Blob>>|non-empty-array<int,IExpression> $conds
	 * @param-taint $conds exec_sql_numkey
	 */
	public static function newFromArray( array $conds ): static {
		if ( !$conds ) {
			throw new InvalidArgumentException( "The array of conditions can't be empty." );
		}
		$exprs = [];
		foreach ( $conds as $field => $cond ) {
			if ( is_numeric( $field ) ) {
				if ( !$cond instanceof IExpression ) {
					throw new InvalidArgumentException(
						__METHOD__ . ": Only IExpression are allowed with numeric key." );
				}
				$exprs[] = $cond;
			} else {
				if ( $cond instanceof IExpression ) {
					throw new InvalidArgumentException( __METHOD__ . ": unexpected key $field for IExpression value" );
				}
				$exprs[] = new Expression( $field, '=', $cond );
			}
		}
		// @phan-suppress-next-line PhanTypeInstantiateAbstractStatic
		return new static( ...$exprs );
	}

	/**
	 * @param DbQuoter $dbQuoter
	 * @return string
	 * @return-taint none
	 */
	final public function toSql( DbQuoter $dbQuoter ): string {
		if ( !$this->children ) {
			throw new InvalidArgumentException( "The array of values can't be empty." );
		}
		$sqls = array_map( static fn ( $value ) => $value->toSql( $dbQuoter ), $this->children );
		return '(' . implode( ' ' . $this->getType() . ' ', $sqls ) . ')';
	}

	final public function toGeneralizedSql(): string {
		if ( !$this->children ) {
			throw new InvalidArgumentException( "The array of values can't be empty." );
		}
		$sqls = array_map( static fn ( $value ) => $value->toGeneralizedSql(), $this->children );
		return '(' . implode( ' ' . $this->getType() . ' ', $sqls ) . ')';
	}
}
