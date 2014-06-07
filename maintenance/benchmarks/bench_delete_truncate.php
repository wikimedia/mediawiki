<?php
/**
 * Benchmark SQL DELETE vs SQL TRUNCATE.
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
 * @ingroup Benchmark
 */

require_once __DIR__ . '/Benchmarker.php';

/**
 * Maintenance script that benchmarks SQL DELETE vs SQL TRUNCATE.
 *
 * @ingroup Benchmark
 */
class BenchmarkDeleteTruncate extends Benchmarker {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Benchmarks SQL DELETE vs SQL TRUNCATE.";
	}

	public function execute() {
		$dbw = wfGetDB( DB_MASTER );

		$test = $dbw->tableName( 'test' );
		$dbw->query( "CREATE TABLE IF NOT EXISTS /*_*/$test (
  test_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  text varbinary(255) NOT NULL
);" );

		$this->insertData( $dbw );

		$start = microtime( true );

		$this->delete( $dbw );

		$end = microtime( true );

		echo "Delete: " . sprintf( "%6.3fms", ( $end - $start ) * 1000 );
		echo "\r\n";

		$this->insertData( $dbw );

		$start = microtime( true );

		$this->truncate( $dbw );

		$end = microtime( true );

		echo "Truncate: " . sprintf( "%6.3fms", ( $end - $start ) * 1000 );
		echo "\r\n";

		$dbw->dropTable( 'test' );
	}

	/**
	 * @param $dbw DatabaseBase
	 * @return void
	 */
	private function insertData( $dbw ) {
		$range = range( 0, 1024 );
		$data = array();
		foreach( $range as $r ) {
			$data[] = array( 'text' => $r );
		}
		$dbw->insert( 'test', $data, __METHOD__ );
	}

	/**
	 * @param $dbw DatabaseBase
	 * @return void
	 */
	private function delete( $dbw ) {
		$dbw->delete( 'text', '*', __METHOD__ );
	}

	/**
	 * @param $dbw DatabaseBase
	 * @return void
	 */
	private function truncate( $dbw ) {
		$test = $dbw->tableName( 'test' );
		$dbw->query( "TRUNCATE TABLE $test" );
	}
}

$maintClass = "BenchmarkDeleteTruncate";
require_once RUN_MAINTENANCE_IF_MAIN;
