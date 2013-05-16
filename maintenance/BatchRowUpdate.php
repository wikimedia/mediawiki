<?php
/**
 * Provides components to update a tables rows via a batching process
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

/**
 * Ties together the batch update components to provide a composable method
 * of batch updating rows in a database. To use create a class implementing
 * the RowUpdateGenerator interface and configure the BatchRowIterator and
 * BatchUpdateWriter for access to the correct table. The components will
 * handle reading, writing, and waiting for slaves while your implementation
 * only needs to worry about generating update arrays for singular rows.
 * Currently the components support tables with a single integer primary key.
 *
 * Instantiate:
 *   $updater = new BatchRowUpdate(
 *       new BatchRowIterator( $dbr, 'some_table', 'primary_key_column', 500 ),
 *       new BatchRowWriter( $dbw, 'clusterName', 'some_table', 'primary_key_column' ),
 *       new MyImplementationOfRowUpdateGenerator
 *   );
 *
 * Run:
 *   $updater->execute();
 *
 * An example maintenance script utilizing the BatchRowUpdate can be located in the Echo
 * extension file maintenance/updateEchoSchemaForSuppression.php
 *
 * @ingroup Maintenance
 */
class BatchRowUpdate {
	/**
	 * @var Iterator $reader Iterator that returns an array of database rows
	 */
	protected $reader;

	/**
	 * @var BatchRowWriter $writer Writer capable of pushing row updates to the database
	 */
	protected $writer;

	/**
	 * @var RowUpdateGenerator $generator Generates single row updates based on the rows content
	 */
	protected $generator;

	/**
	 * @var callable $output Output callback
	 */
	protected $output;

	/**
	 * @param Iterator           $reader    Iterator that returns an array of database rows
	 * @param BatchRowWriter     $writer    Writer capable of pushing row updates to the database
	 * @param RowUpdateGenerator $generator Generates single row updates based on the rows content
	 */
	public function __construct( Iterator $reader, BatchRowWriter $writer, RowUpdateGenerator $generator ) {
		$this->reader = $reader;
		$this->writer = $writer;
		$this->generator = $generator;
		$this->output = function() {
		}; // nop
	}

	/**
	 * Runs the batch update process
	 */
	public function execute() {
		foreach ( $this->reader as $rows ) {
			$updates = array();
			foreach ( $rows as $row ) {
				list( $id, $update ) = $this->generator->update( $row );
				if ( $update ) {
					$updates[$id] = $update;
				}
			}

			if ( $updates ) {
				$this->output( "Processing " . count( $updates ) . " rows\n" );
				$this->writer->write( $updates );
			}
		}

		$this->output( "Completed\n" );
	}

	/**
	 * Accepts a callable which will receive a single parameter containing
	 * string status updates
	 *
	 * @param callable $output A callback taking a single string parameter to output
	 */
	public function setOutput( $output ) {
		if ( !is_callable( $output ) ) {
			throw new MWException( 'Provided $output param is required to be callable.' );
		}
		$this->output = $output;
	}

	/**
	 * Write out a status update
	 *
	 * @param string $text The value to print
	 */
	protected function output( $text ) {
		call_user_func( $this->output, $text );
	}
}

/**
 * Interface for generating updates to single rows in the database.
 *
 * @ingroup Maintenance
 */
interface RowUpdateGenerator {

	/**
	 * Given a database row, generates an array of updates to that row
	 *
	 * Sample Response:
	 *   return array(
	 *       42, // primary key
	 *       array( // column updates to execute
	 *           'some_col' => 'new value',
	 *           'other_col' => 99,
	 *       )
	 *   );
	 *
	 * @param stdClass $row A row from the database
	 * @return [int, array] Array containing the rows primary key value and an array mapping column
	 *   to update value. When no update is required return an empty array as the second value.
	 */
	public function update( $row );
}

/**
 * Updates database rows by primary key in batches. Only supports tables with a single
 * primary key.
 *
 * @ingroup Maintenance
 */
class BatchRowWriter {
	/**
	 * @var DatabaseBase $db The database to write to
	 */
	protected $db;

	/**
	 * @var string $clusterName A cluster name valid for use with LBFactory
	 */
	protected $clusterName;

	/**
	 * @var string $table The name of the table to update
	 */
	protected $table;

	/**
	 * @var string $primaryKey The name of the primary key
	 */
	protected $primaryKey;

	/**
	 * @param DatabaseBase $db          The database to write to
	 * @param string       $clusterName A cluster name valid for use with LBFactory
	 * @param string       $table       The name of the table to update
	 * @param string       $primaryKey     The name of the primary key
	 */
	public function __construct( DatabaseBase $db, $clusterName, $table, $primaryKey ) {
		$this->db = $db;
		$this->clusterName = $clusterName;
		$this->table = $table;
		$this->primaryKey = $primaryKey;
	}

	/**
	 * @param [int => array] $updates Array mapping primary key to an array
	 *   mapping column name to updated value
	 */
	public function write( array $updates ) {
		$this->db->begin();

		foreach ( $updates as $id => $update ) {
			$this->db->update(
				$this->table,
				$update,
				array( $this->primaryKey => $id ),
				__METHOD__
			);
		}

		$this->db->commit();
		wfWaitForSlaves( false, false, $this->clusterName );
	}
}

/**
 * Fetches rows batched into groups from the database in ascending
 * order of the primary key.
 *
 * @ingroup Maintenance
 */
class BatchRowIterator implements Iterator {

	/**
	 * @var DatabaseBase $db The database to read from
	 */
	protected $db;

	/**
	 * @var string $table The name of the table to read from
	 */
	protected $table;

	/**
	 * @var string $primaryKey The name of the primary key
	 */
	protected $primaryKey;

	/**
	 * @var integer $batchSize The number of rows to fetch per iteration
	 */
	protected $batchSize;

	/**
	 * @var array $conditions Array of strings containing SQL conditions to add to the query
	 */
	protected $conditions = array();

	/**
	 * @var integer $maxId The maximum primary key seen since self::reset()
	 */
	private $maxId = 0;

	/**
	 * @var array $current The current iterator value
	 */
	private $current = array();

	/**
	 * @param DatabaseBase $db         The database to read from
	 * @param string       $table      The name of the table to read from
	 * @param string       $primaryKey The name of the primary key
	 * @param integer      $batchSize  The number of rows to fetch per iteration
	 */
	public function __construct( DatabaseBase $db, $table, $primaryKey, $batchSize ) {
		if ( $batchSize < 1 ) {
			throw new MWException( 'Batch size must be at least 1 row.' );
		}
		$this->db = $db;
		$this->table = $table;
		$this->primaryKey = $primaryKey;
		$this->batchSize = $batchSize;
	}

	/**
	 * @param string $condition An sql query condition
	 */
	public function addCondition( $condition ) {
		$this->conditions[] = $condition;
	}

	/**
	 * @return array The most recently fetched set of rows from the database
	 */
	public function current() {
		return $this->current;
	}

	/**
	 * @return integer The maximum primary key value seen so far
	 */
	public function key() {
		return $this->maxId;
	}

	/**
	 * Reset the iterator to the begining of the table.
	 */
	public function rewind() {
		$this->current = array();
		$this->maxId = 0;
		$this->next();
	}

	/**
	 * @return boolean True when the iterator is in a valid state
	 */
	public function valid() {
		return (bool) $this->current;
	}

	/**
	 * Fetch the next set of rows from the database.
	 */
	public function next() {
		$conditions = $this->conditions;
		$conditions[] = "{$this->primaryKey} > {$this->maxId}";

		$res = $this->db->select(
			$this->table,
			array( '*' ),
			$conditions,
			__METHOD__,
			array(
				'LIMIT' => $this->batchSize,
				"ORDER BY {$this->primaryKey} ASC",
			)
		);

		$this->current = iterator_to_array( $res );

		// If $this->current is empty then self::valid() will return false
		// so no need to handle edge case
		if ( $this->current ) {
			$row = end( $this->current );
			$this->maxId = $row->{$this->primaryKey};
		}
	}
}

