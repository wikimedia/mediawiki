<?php

namespace Wikimedia\Rdbms;

/**
 * Parenthesized group of table names and their join types and conditions.
 *
 * @internal
 */
class JoinGroup extends JoinGroupBase {
	/** @var string */
	private $alias;

	/** @var int */
	private $nextAutoAlias = 0;

	/**
	 * Use SelectQueryBuilder::newJoinGroup() to create a join group
	 *
	 * @internal
	 * @param string $alias
	 */
	public function __construct( $alias ) {
		$this->alias = $alias;
	}

	/**
	 * Get a table alias which is unique to the parent SelectQueryBuilder
	 *
	 * @return string
	 */
	protected function getAutoAlias() {
		return $this->alias . '_' . ( $this->nextAutoAlias++ );
	}

	/**
	 * @internal
	 * @return array
	 */
	public function getRawTables() {
		return $this->tables;
	}

	/**
	 * @internal
	 * @return array
	 */
	public function getRawJoinConds() {
		return $this->joinConds;
	}

	/**
	 * @internal
	 * @return string
	 */
	public function getAlias() {
		return $this->alias;
	}
}
