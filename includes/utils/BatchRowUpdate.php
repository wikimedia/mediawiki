<?php
/*
 * Ties together the batch update components to provide a composable
 * method of batch updating rows in a database. To use create a class
 * implementing the RowUpdateGenerator interface and configure the
 * BatchRowIterator and BatchRowWriter for access to the correct table.
 * The components will handle reading, writing, and waiting for replica DBs
 * while the generator implementation handles generating update arrays
 * for singular rows.
 *
 * Instantiate:
 *   $updater = new BatchRowUpdate(
 *       new BatchRowIterator( $dbr, 'some_table', 'primary_key_column', 500 ),
 *       new BatchRowWriter( $dbw, 'some_table', 'clusterName' ),
 *       new MyImplementationOfRowUpdateGenerator
 *   );
 *
 * Run:
 *   $updater->execute();
 *
 * An example maintenance script utilizing the BatchRowUpdate can be
 * located in the Echo extension file maintenance/updateSchema.php
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
class BatchRowUpdate {
	/**
	 * @var BatchRowIterator $reader Iterator that returns an array of
	 *  database rows
	 */
	protected $reader;

	/**
	 * @var BatchRowWriter $writer Writer capable of pushing row updates
	 *  to the database
	 */
	protected $writer;

	/**
	 * @var RowUpdateGenerator $generator Generates single row updates
	 *  based on the rows content
	 */
	protected $generator;

	/**
	 * @var callable $output Output callback
	 */
	protected $output;

	/**
	 * @param BatchRowIterator $reader Iterator that returns an
	 *  array of database rows
	 * @param BatchRowWriter $writer Writer capable of pushing
	 *  row updates to the database
	 * @param RowUpdateGenerator $generator Generates single row updates
	 *  based on the rows content
	 */
	public function __construct(
		BatchRowIterator $reader, BatchRowWriter $writer, RowUpdateGenerator $generator
	) {
		$this->reader = $reader;
		$this->writer = $writer;
		$this->generator = $generator;
		$this->output = function ( $text ) {
		}; // nop
	}

	/**
	 * Runs the batch update process
	 */
	public function execute() {
		foreach ( $this->reader as $rows ) {
			$updates = [];
			foreach ( $rows as $row ) {
				$update = $this->generator->update( $row );
				if ( $update ) {
					$updates[] = [
						'primaryKey' => $this->reader->extractPrimaryKeys( $row ),
						'changes' => $update,
					];
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
	 * Accepts a callable which will receive a single parameter
	 * containing string status updates
	 *
	 * @param callable $output A callback taking a single string
	 *  parameter to output
	 */
	public function setOutput( callable $output ) {
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
