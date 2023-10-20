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
	 * @internal
	 * @param IExpression ...$children
	 */
	public function __construct( IExpression ...$children ) {
		$this->children = $children;
	}

	final protected function add( IExpression $expression ) {
		$this->children[] = $expression;
	}

	abstract protected function getType(): string;

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
