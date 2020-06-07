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

require_once __DIR__ . '/../includes/Benchmarker.php';

use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IMaintainableDatabase;

/**
 * Maintenance script that benchmarks SQL DELETE vs SQL TRUNCATE.
 *
 * @ingroup Benchmark
 */
class BenchmarkDeleteTruncate extends Benchmarker {
	protected $defaultCount = 10;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Benchmarks SQL DELETE vs SQL TRUNCATE.' );
	}

	public function execute() {
		$dbw = $this->getDB( DB_MASTER );

		$test = $dbw->tableName( 'test' );
		$dbw->query( "CREATE TABLE IF NOT EXISTS /*_*/$test (
  test_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  text varbinary(255) NOT NULL
);", __METHOD__ );

		$this->bench( [
			'Delete' => [
				'setup' => function () use ( $dbw ) {
					$this->insertData( $dbw );
				},
				'function' => function () use ( $dbw ) {
					$this->delete( $dbw );
				}
			],
			'Truncate' => [
				'setup' => function () use ( $dbw ) {
					$this->insertData( $dbw );
				},
				'function' => function () use ( $dbw ) {
					$this->truncate( $dbw );
				}
			]
		] );

		$dbw->dropTable( 'test', __METHOD__ );
	}

	/**
	 * @param IDatabase $dbw
	 * @return void
	 */
	private function insertData( $dbw ) {
		$range = range( 0, 1024 );
		$data = [];
		foreach ( $range as $r ) {
			$data[] = [ 'text' => $r ];
		}
		$dbw->insert( 'test', $data, __METHOD__ );
	}

	/**
	 * @param IDatabase $dbw
	 * @return void
	 */
	private function delete( $dbw ) {
		$dbw->delete( 'text', '*', __METHOD__ );
	}

	/**
	 * @param IMaintainableDatabase $dbw
	 * @return void
	 */
	private function truncate( $dbw ) {
		$test = $dbw->tableName( 'test' );
		$dbw->query( "TRUNCATE TABLE $test", __METHOD__ );
	}
}

$maintClass = BenchmarkDeleteTruncate::class;
require_once RUN_MAINTENANCE_IF_MAIN;
