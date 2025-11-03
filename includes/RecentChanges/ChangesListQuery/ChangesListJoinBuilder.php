<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use LogicException;
use Wikimedia\Rdbms\Database\DbQuoter;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * An object encapsulating a single instance of a join on a table.
 *
 * @since 1.45
 */
class ChangesListJoinBuilder {
	private const VAGUE = 'vague';
	private const WEAK_LEFT = 'weak-left';
	private const STRAIGHT = 'straight';
	private const LEFT = 'left';
	private const REORDERABLE = 'reorderable';

	private string $tableName;
	private ?string $alias;

	/** @var (IExpression|string)[] */
	private array $conds;

	private string $type = self::VAGUE;
	private bool $forFields = false;
	private bool $forConds = false;

	public function __construct( string $tableName, ?string $alias, array $conds ) {
		$this->tableName = $tableName;
		$this->alias = $alias === '' ? null : $alias;
		$this->conds = $conds;
	}

	/**
	 * Declare that the join is required to provide fields in the SELECT clause.
	 * @return $this
	 */
	public function forFields(): self {
		$this->forFields = true;
		return $this;
	}

	/**
	 * Declare that the join is required to provide fields for the WHERE clause.
	 * @return $this
	 */
	public function forConds(): self {
		$this->forConds = true;
		return $this;
	}

	/**
	 * Request a straight join. This is a hint to the MariaDB optimiser that
	 * this table should not be done first. It's appropriate to use it when
	 * the conditions will match most rows in the recentchanges table.
	 *
	 * This is a weak join type -- a subsequent call to reorderable() will
	 * override it.
	 *
	 * @return $this
	 */
	public function straight(): self {
		return $this->setType( self::STRAIGHT );
	}

	/**
	 * Request a reorderable join. This allows the DBMS to place the table first
	 * if it desired. This is appropriate when the conditions will match only a
	 * few rows in the recentchanges table.
	 *
	 * This is a strong join type -- subsequent requests for a weak join type
	 * will be ignored.
	 *
	 * @return $this
	 */
	public function reorderable(): self {
		return $this->setType( self::REORDERABLE );
	}

	/**
	 * Require a left join. This is a strong join type -- subsequent requests to
	 * change the join type will throw. This is appropriate when the conditions
	 * logically require a left join.
	 *
	 * @return $this
	 */
	public function left(): self {
		return $this->setType( self::LEFT );
	}

	/**
	 * Request a left join. This is a weak join type -- subsequent requests to
	 * change to a straight or reorderable join will be allowed. This is
	 * appropriate when doing a left join for fields. A subsequent filter
	 * may upgrade the join type in order to place strict conditions on the
	 * same fields.
	 *
	 * @return $this
	 */
	public function weakLeft(): self {
		return $this->setType( self::WEAK_LEFT );
	}

	/**
	 * Add a condition to the join conditions
	 *
	 * @param IExpression $expr
	 * @return $this
	 */
	public function on( IExpression $expr ): self {
		$this->conds[] = $expr;
		return $this;
	}

	/**
	 * Try to set the type, implementing the strong/weak logic.
	 *
	 * @param string $newType
	 * @return $this
	 */
	private function setType( $newType ) {
		if ( $newType === $this->type ) {
			// no change
			return $this;
		} elseif ( $this->type === self::VAGUE || $this->type === self::WEAK_LEFT ) {
			// allow transition from vague or weak-left
			$this->type = $newType;
			return $this;
		} elseif ( $this->type === self::REORDERABLE || $this->type === self::LEFT ) {
			// from a strong type
			if ( ( $newType === self::WEAK_LEFT || $newType === self::STRAIGHT ) ) {
				// ignore change from a strong type to a weak type
				return $this;
			}
		} elseif ( $this->type === self::STRAIGHT ) {
			if ( $newType === self::LEFT || $newType === self::REORDERABLE ) {
				// allow upgrade from straight
				$this->type = $newType;
				return $this;
			} elseif ( $newType === self::WEAK_LEFT ) {
				// ignore weak left
				return $this;
			}
		}
		// disallow strong to weak transitions or any accidentally unhandled transition
		throw new LogicException(
			"Unable to change join type of {$this->tableName} " .
			"from {$this->type} to {$newType}" );
	}

	/**
	 * Implement the join on a SelectQueryBuilder
	 *
	 * @param SelectQueryBuilder $sqb
	 */
	public function prepare( SelectQueryBuilder $sqb ): void {
		switch ( $this->type ) {
			case self::VAGUE:
				if ( $this->forConds ) {
					$sqb->join( $this->tableName, $this->alias, $this->conds );
				} else {
					$sqb->straightJoin( $this->tableName, $this->alias, $this->conds );
				}
				break;
			case self::REORDERABLE:
				$sqb->join( $this->tableName, $this->alias, $this->conds );
				break;
			case self::STRAIGHT:
				$sqb->straightJoin( $this->tableName, $this->alias, $this->conds );
				break;
			case self::WEAK_LEFT:
			case self::LEFT:
				$sqb->leftJoin( $this->tableName, $this->alias, $this->conds );
				break;
			default:
				throw new \LogicException( 'Unknown join type' );
		}
	}

	/**
	 * @internal Testing/debugging helper
	 * @param DbQuoter $dbQuoter
	 * @return string
	 */
	public function toString( DbQuoter $dbQuoter ) {
		$str = "{$this->type} JOIN {$this->tableName}";
		if ( $this->alias !== null ) {
			$str .= " AS {$this->alias}";
		}
		$str .= ' ON';
		$isFirst = true;
		foreach ( $this->conds as $expr ) {
			if ( $isFirst ) {
				$isFirst = false;
				$str .= ' ';
			} else {
				$str .= ' AND ';
			}
			$str .= $expr instanceof IExpression ? $expr->toSql( $dbQuoter ) : (string)$expr;
		}
		if ( $this->forConds ) {
			$str .= ' /* for conds */';
		}
		if ( $this->forFields ) {
			$str .= ' /* for fields */';
		}
		return $str;
	}
}
