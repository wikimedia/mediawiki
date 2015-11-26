<?php
/**
 * Allows iterating a large number of rows in batches transparently.
 * By default when iterated over returns the full query result as an
 * array of rows.  Can be wrapped in RecursiveIteratorIterator to
 * collapse those arrays into a single stream of rows queried in batches.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Maintenance
 */
class BatchRowIterator implements RecursiveIterator {

	/**
	 * @var DatabaseBase $db The database to read from
	 */
	protected $db;

	/**
	 * @var string $table The name of the table to read from
	 */
	protected $table;

	/**
	 * @var array $primaryKey The name of the primary key(s)
	 */
	protected $primaryKey;

	/**
	 * @var integer $batchSize The number of rows to fetch per iteration
	 */
	protected $batchSize;

	/**
	 * @var array $conditions Array of strings containing SQL conditions
	 *  to add to the query
	 */
	protected $conditions = array();

	/**
	 * @var array $joinConditions
	 */
	protected $joinConditions = array();

	/**
	 * @var array $fetchColumns List of column names to select from the
	 *  table suitable for use with DatabaseBase::select()
	 */
	protected $fetchColumns;

	/**
	 * @var string $orderBy SQL Order by condition generated from $this->primaryKey
	 */
	protected $orderBy;

	/**
	 * @var array $current The current iterator value
	 */
	private $current = array();

	/**
	 * @var integer key 0-indexed number of pages fetched since self::reset()
	 */
	private $key;

	/**
	 * @param DatabaseBase $db         The database to read from
	 * @param string       $table      The name of the table to read from
	 * @param string|array $primaryKey The name or names of the primary key columns
	 * @param integer      $batchSize  The number of rows to fetch per iteration
	 * @throws MWException
	 */
	public function __construct( DatabaseBase $db, $table, $primaryKey, $batchSize ) {
		if ( $batchSize < 1 ) {
			throw new MWException( 'Batch size must be at least 1 row.' );
		}
		$this->db = $db;
		$this->table = $table;
		$this->primaryKey = (array) $primaryKey;
		$this->fetchColumns = $this->primaryKey;
		$this->orderBy = implode( ' ASC,', $this->primaryKey ) . ' ASC';
		$this->batchSize = $batchSize;
	}

	/**
	 * @param array $condition Query conditions suitable for use with
	 *  DatabaseBase::select
	 */
	public function addConditions( array $conditions ) {
		$this->conditions = array_merge( $this->conditions, $conditions );
	}

	/**
	 * @param array $condition Query join conditions suitable for use
	 *  with DatabaseBase::select
	 */
	public function addJoinConditions( array $conditions ) {
		$this->joinConditions = array_merge( $this->joinConditions, $conditions );
	}

	/**
	 * @param array $columns List of column names to select from the
	 *  table suitable for use with DatabaseBase::select()
	 */
	public function setFetchColumns( array $columns ) {
		// If it's not the all column selector merge in the primary keys we need
		if ( count( $columns ) === 1 && reset( $columns ) === '*' ) {
			$this->fetchColumns = $columns;
		} else {
			$this->fetchColumns = array_unique( array_merge(
				$this->primaryKey,
				$columns
			) );
		}
	}

	/**
	 * Extracts the primary key(s) from a database row.
	 *
	 * @param stdClass $row An individual database row from this iterator
	 * @return array Map of primary key column to value within the row
	 */
	public function extractPrimaryKeys( $row ) {
		$pk = array();
		foreach ( $this->primaryKey as $column ) {
			$pk[$column] = $row->$column;
		}
		return $pk;
	}

	/**
	 * @return array The most recently fetched set of rows from the database
	 */
	public function current() {
		return $this->current;
	}

	/**
	 * @return integer 0-indexed count of the page number fetched
	 */
	public function key() {
		return $this->key;
	}

	/**
	 * Reset the iterator to the begining of the table.
	 */
	public function rewind() {
		$this->key = -1; // self::next() will turn this into 0
		$this->current = array();
		$this->next();
	}

	/**
	 * @return boolean True when the iterator is in a valid state
	 */
	public function valid() {
		return (bool) $this->current;
	}

	/**
	 * @return boolean True when this result set has rows
	 */
	public function hasChildren() {
		return $this->current && count( $this->current );
	}

	/**
	 * @return RecursiveIterator
	 */
	public function getChildren() {
		return new NotRecursiveIterator( new ArrayIterator( $this->current ) );
	}

	/**
	 * Fetch the next set of rows from the database.
	 */
	public function next() {
		$res = $this->db->select(
			$this->table,
			$this->fetchColumns,
			$this->buildConditions(),
			__METHOD__,
			array(
				'LIMIT' => $this->batchSize,
				'ORDER BY' => $this->orderBy,
			),
			$this->joinConditions
		);

		// The iterator is converted to an array because in addition to
		// returning it in self::current() we need to use the end value
		// in self::buildConditions()
		$this->current = iterator_to_array( $res );
		$this->key++;
	}

	/**
	 * Uses the primary key list and the maximal result row from the
	 * previous iteration to build an SQL condition sufficient for
	 * selecting the next page of results.  All except the final key use
	 * `=` conditions while the final key uses a `>` condition
	 *
	 * Example output:
	 * 	  array( '( foo = 42 AND bar > 7 ) OR ( foo > 42 )' )
	 *
	 * @return array The SQL conditions necessary to select the next set
	 *  of rows in the batched query
	 */
	protected function buildConditions() {
		if ( !$this->current ) {
			return $this->conditions;
		}

		$maxRow = end( $this->current );
		$maximumValues = array();
		foreach ( $this->primaryKey as $column ) {
			$maximumValues[$column] = $this->db->addQuotes( $maxRow->$column );
		}

		$pkConditions = array();
		// For example: If we have 3 primary keys
		// first run through will generate
		//   col1 = 4 AND col2 = 7 AND col3 > 1
		// second run through will generate
		//   col1 = 4 AND col2 > 7
		// and the final run through will generate
		//   col1 > 4
		while ( $maximumValues ) {
			$pkConditions[] = $this->buildGreaterThanCondition( $maximumValues );
			array_pop( $maximumValues );
		}

		$conditions = $this->conditions;
		$conditions[] = sprintf( '( %s )', implode( ' ) OR ( ', $pkConditions ) );

		return $conditions;
	}

	/**
	 * Given an array of column names and their maximum value  generate
	 * an SQL condition where all keys except the last match $quotedMaximumValues
	 * exactly and the last column is greater than the matching value in
	 * $quotedMaximumValues
	 *
	 * @param array $quotedMaximumValues The maximum values quoted with
	 *  $this->db->addQuotes()
	 * @return string An SQL condition that will select rows where all
	 *  columns match the maximum value exactly except the last column
	 *  which must be greater than the provided maximum value
	 */
	protected function buildGreaterThanCondition( array $quotedMaximumValues ) {
		$keys = array_keys( $quotedMaximumValues );
		$lastColumn = end( $keys );
		$lastValue = array_pop( $quotedMaximumValues );
		$conditions = array();
		foreach ( $quotedMaximumValues as $column => $value ) {
			$conditions[] = "$column = $value";
		}
		$conditions[] = "$lastColumn > $lastValue";

		return implode( ' AND ', $conditions );
	}
}
