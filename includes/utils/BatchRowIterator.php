<?php

use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
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

/**
 * Allows iterating a large number of rows in batches transparently.
 * By default when iterated over returns the full query result as an
 * array of rows.  Can be wrapped in RecursiveIteratorIterator to
 * collapse those arrays into a single stream of rows queried in batches.
 *
 * @newable
 */
class BatchRowIterator implements RecursiveIterator {

	protected IReadableDatabase $db;

	/**
	 * @var array The name of the primary key(s)
	 */
	protected $primaryKey;

	/**
	 * @var int The number of rows to fetch per iteration
	 */
	protected $batchSize;

	/**
	 * @var array The current iterator value
	 */
	private $current = [];

	/**
	 * @var int 0-indexed number of pages fetched since self::reset()
	 */
	private $key = -1;

	/**
	 * Underlying database query builder, may be mutated before iteration begins.
	 */
	public SelectQueryBuilder $sqb;

	/**
	 * @stable to call
	 *
	 * @param IReadableDatabase $db
	 * @param SelectQueryBuilder|string|array $sqb The query to split into batches (or table name/names)
	 * @param string|array $primaryKey The name or names of the primary key columns
	 * @param int $batchSize The number of rows to fetch per iteration
	 */
	public function __construct( IReadableDatabase $db, $sqb, $primaryKey, $batchSize ) {
		if ( $batchSize < 1 ) {
			throw new InvalidArgumentException( 'Batch size must be at least 1 row.' );
		}
		$this->db = $db;
		$this->primaryKey = (array)$primaryKey;
		$this->batchSize = $batchSize;

		if ( $sqb instanceof SelectQueryBuilder ) {
			$this->sqb = $sqb;
		} else {
			$this->sqb = $db->newSelectQueryBuilder()
				->tables( is_array( $sqb ) ? $sqb : [ $sqb ] )
				->caller( __CLASS__ );
		}
		$this->sqb->fields( $this->primaryKey );
	}

	/**
	 * @deprecated since 1.44 Use the SelectQueryBuilder object directly
	 * @param array $conditions Query conditions suitable for use with
	 *  IDatabase::select
	 */
	public function addConditions( array $conditions ) {
		$this->sqb->conds( $conditions );
	}

	/**
	 * @deprecated since 1.44 Use the SelectQueryBuilder object directly
	 * @param array $options Query options suitable for use with
	 *  IDatabase::select
	 */
	public function addOptions( array $options ) {
		$this->sqb->options( $options );
	}

	/**
	 * @deprecated since 1.44 Use the SelectQueryBuilder object directly
	 * @param array $conditions Query join conditions suitable for use
	 *  with IDatabase::select
	 */
	public function addJoinConditions( array $conditions ) {
		$this->sqb->joinConds( $conditions );
	}

	/**
	 * @deprecated since 1.44 Use the SelectQueryBuilder object directly
	 * @param array $columns List of column names to select from the
	 *  table suitable for use with IDatabase::select()
	 */
	public function setFetchColumns( array $columns ) {
		// If it's not the all column selector merge in the primary keys we need
		if ( count( $columns ) === 1 && reset( $columns ) === '*' ) {
			$fetchColumns = $columns;
		} else {
			$fetchColumns = array_unique( array_merge(
				$this->primaryKey,
				$columns
			) );
		}
		$this->sqb->clearFields()->fields( $fetchColumns );
	}

	/**
	 * Use ->setCaller( __METHOD__ ) to indicate which code is using this
	 * class. Only used in debugging output.
	 * @since 1.36
	 * @deprecated since 1.44 Use the SelectQueryBuilder object directly
	 *
	 * @param string $caller
	 * @return self
	 */
	public function setCaller( $caller ) {
		$this->sqb->caller( $caller );

		return $this;
	}

	/**
	 * Extracts the primary key(s) from a database row.
	 *
	 * @param stdClass $row An individual database row from this iterator
	 * @return array Map of primary key column to value within the row
	 */
	public function extractPrimaryKeys( $row ) {
		$pk = [];
		foreach ( $this->primaryKey as $alias => $column ) {
			$name = is_numeric( $alias ) ? $column : $alias;
			$pk[$name] = $row->{$name};
		}
		return $pk;
	}

	/**
	 * @return array The most recently fetched set of rows from the database
	 */
	public function current(): array {
		return $this->current;
	}

	/**
	 * @return int 0-indexed count of the page number fetched
	 */
	public function key(): int {
		return $this->key;
	}

	/**
	 * Reset the iterator to the beginning of the table.
	 */
	public function rewind(): void {
		$this->key = -1; // self::next() will turn this into 0
		$this->current = [];
		$this->next();
	}

	/**
	 * @return bool True when the iterator is in a valid state
	 */
	public function valid(): bool {
		return (bool)$this->current;
	}

	/**
	 * @return bool True when this result set has rows
	 */
	public function hasChildren(): bool {
		return $this->current && count( $this->current );
	}

	public function getChildren(): ?RecursiveIterator {
		return new NotRecursiveIterator( new ArrayIterator( $this->current ) );
	}

	/**
	 * Fetch the next set of rows from the database.
	 */
	public function next(): void {
		$res = ( clone $this->sqb )
			->andWhere( $this->buildConditions() )
			->limit( $this->batchSize )
			->orderBy( $this->primaryKey, SelectQueryBuilder::SORT_ASC )
			->fetchResultSet();

		// The iterator is converted to an array because in addition to
		// returning it in self::current() we need to use the end value
		// in self::buildConditions()
		$this->current = iterator_to_array( $res );
		$this->key++;
	}

	/**
	 * Uses the primary key list and the maximal result row from the
	 * previous iteration to build an SQL condition sufficient for
	 * selecting the next page of results.
	 *
	 * @return array The SQL conditions necessary to select the next set
	 *  of rows in the batched query
	 */
	protected function buildConditions() {
		if ( !$this->current ) {
			return [];
		}

		$maxRow = end( $this->current );
		$maximumValues = [];
		foreach ( $this->primaryKey as $alias => $column ) {
			$name = is_numeric( $alias ) ? $column : $alias;
			$maximumValues[$column] = $maxRow->$name;
		}

		return [ $this->db->buildComparison( '>', $maximumValues ) ];
	}
}
