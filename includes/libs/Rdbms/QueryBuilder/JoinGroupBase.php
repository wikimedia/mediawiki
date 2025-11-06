<?php

namespace Wikimedia\Rdbms;

use InvalidArgumentException;

/**
 * Shared code between SelectQueryBuilder and JoinGroup to represent tables and join conditions.
 *
 * @internal
 */
abstract class JoinGroupBase {
	/** @var array */
	protected $tables = [];

	/** @var array */
	protected $joinConds = [];

	/** @var string|null */
	protected $lastAlias;

	/**
	 * Add a single table or a single parenthesized group.
	 *
	 * @param string|JoinGroup|Subquery|SelectQueryBuilder $table The unqualified name of a table,
	 *   a table name of the form "information_schema.<unquoted identifier>", a JoinGroup
	 *   containing multiple tables, a Subquery instance, or a SelectQueryBuilder representing a subquery.
	 * @param-taint $table exec_sql
	 * @param string|null $alias The table alias, or null for no alias
	 * @param-taint $alias exec_sql
	 * @return $this
	 */
	public function table( $table, $alias = null ) {
		if ( $table instanceof JoinGroup ) {
			$alias ??= $table->getAlias();
			$table = $table->getRawTables();
		} elseif ( $table instanceof SelectQueryBuilder ) {
			$alias ??= $this->getAutoAlias();
			$table = new Subquery( $table->getSQL() );
		} elseif ( $table instanceof Subquery ) {
			if ( $alias === null ) {
				throw new InvalidArgumentException( __METHOD__ .
					': Subquery as table must provide an alias.' );
			}
		} elseif ( !is_string( $table ) ) {
			throw new InvalidArgumentException( __METHOD__ .
				': $table must be either string, JoinGroup or SelectQueryBuilder' );
		}
		if ( $alias === null ) {
			$this->tables[] = $table;
			$this->lastAlias = $table;
		} else {
			$this->tables[$alias] = $table;
			$this->lastAlias = $alias;
		}
		return $this;
	}

	/**
	 * Left join a table or group of tables. This should be called after table().
	 *
	 * @param string|JoinGroup|SelectQueryBuilder $table The unqualified name of a table,
	 *   a table name of the form "information_schema.<unquoted identifier>", a JoinGroup
	 *   containing multiple tables, or a SelectQueryBuilder representing a subquery.
	 * @param-taint $table exec_sql
	 * @param string|null $alias The alias name, or null to automatically
	 *   generate an alias which will be unique to this builder
	 * @param-taint $alias exec_sql
	 * @param string|array $conds The conditions for the ON clause
	 * @param-taint $conds exec_sql_numkey
	 * @return $this
	 */
	public function leftJoin( $table, $alias = null, $conds = [] ) {
		$this->addJoin( 'LEFT JOIN', $table, $alias, $conds );
		return $this;
	}

	/**
	 * Inner join a table or group of tables. This should be called after table().
	 *
	 * @param string|JoinGroup|SelectQueryBuilder $table The unqualified name of a table,
	 *   a table name of the form "information_schema.<unquoted identifier>", a JoinGroup
	 *   containing multiple tables, or a SelectQueryBuilder representing a subquery.
	 * @param-taint $table exec_sql
	 * @param string|null $alias The alias name, or null to automatically
	 *   generate an alias which will be unique to this builder
	 * @param-taint $alias exec_sql
	 * @param string|array $conds The conditions for the ON clause
	 * @param-taint $conds exec_sql_numkey
	 * @return $this
	 */
	public function join( $table, $alias = null, $conds = [] ) {
		$this->addJoin( 'JOIN', $table, $alias, $conds );
		return $this;
	}

	/**
	 * Straight join a table or group of tables. This should be called after table().
	 *
	 * @param string|JoinGroup|SelectQueryBuilder $table The unqualified name of a table,
	 *   a table name of the form "information_schema.<unquoted identifier>", a JoinGroup
	 *   containing multiple tables, or a SelectQueryBuilder representing a subquery.
	 * @param-taint $table exec_sql
	 * @param string|null $alias The alias name, or null to automatically
	 *   generate an alias which will be unique to this builder
	 * @param-taint $alias exec_sql
	 * @param string|array $conds The conditions for the ON clause
	 * @param-taint $conds exec_sql_numkey
	 * @return $this
	 */
	public function straightJoin( $table, $alias = null, $conds = [] ) {
		$this->addJoin( 'STRAIGHT_JOIN', $table, $alias, $conds );
		return $this;
	}

	/**
	 * Private helper for functions that add joins
	 * @param string $type
	 * @param string|JoinGroup|SelectQueryBuilder $table
	 * @param string|null $alias
	 * @param string|array $joinConds
	 */
	private function addJoin( $type, $table, $alias, $joinConds ) {
		if ( !$this->tables ) {
			throw new \LogicException( __METHOD__ .
				': cannot add a join unless a regular table is added first' );
		}
		if ( $alias === null ) {
			if ( is_string( $table ) ) {
				$alias = $table;
			} else {
				$alias = $this->getAutoAlias();
			}
		}
		if ( isset( $this->joinConds[$alias] ) ) {
			throw new \LogicException( __METHOD__ .
				": a join with alias \"$alias\" has already been added" );
		}
		if ( $table instanceof JoinGroup ) {
			$conflicts = array_intersect_key( $this->joinConds, $table->getRawJoinConds() );
			if ( $conflicts ) {
				$conflict = reset( $conflicts );
				throw new \LogicException( __METHOD__ .
					": a join with alias \"$conflict\" has already been added" );
			}
			$this->tables[$alias] = $table->getRawTables();
			$this->joinConds += $table->getRawJoinConds();
		} elseif ( $table instanceof SelectQueryBuilder ) {
			$this->tables[$alias] = new Subquery( $table->getSQL() );
		} elseif ( is_string( $table ) ) {
			$this->tables[$alias] = $table;
		} else {
			throw new InvalidArgumentException( __METHOD__ .
				': $table must be either string, JoinGroup or SelectQueryBuilder' );
		}
		$this->joinConds[$alias] = [ $type, $joinConds ];
		$this->lastAlias = $alias;
	}

	/**
	 * @return string
	 */
	abstract protected function getAutoAlias();
}
