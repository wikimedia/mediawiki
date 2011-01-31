<?php

require_once( dirname( __FILE__ ) . '/Benchmarker.php' );

class BenchmarkPurge extends Benchmarker {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Benchmarks SQL DELETE vs SQL TRUNCATE.";
	}

	public function execute() {
		$dbw = wfGetDB( DB_MASTER );

		$test = $dbw->tableName( 'test' );
		$dbw->doQuery( "CREATE TABLE IF NOT EXISTS /*_*/$test (
  test_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  text varbinary(255) NOT NULL
);" );

		$this->insertData( $dbw );

		$start = wfTime();

		$this->delete( $dbw );

		$end = wfTime();

		echo "Delete: " . $end - $start;
		echo "\r\n";

		$this->insertData( $dbw );

		$start = wfTime();

		$this->truncate( $dbw );

		$end = wfTime();

		echo "Truncate: " . $end - $start;
		echo "\r\n";

		$dbw->dropTable( 'test' );
	}

	/**
	 * @param  $dbw DatabaseBase
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
	 * @param  $dbw DatabaseBase
	 * @return void
	 */
	private function delete( $dbw ) {
		$dbw->delete( 'text', '*', __METHOD__ );
	}

	/**
	 * @param  $dbw DatabaseBase
	 * @return void
	 */
	private function truncate( $dbw ) {
		$test = $dbw->tableName( 'test' );
		$dbw->doQuery( "TRUNCATE TABLE $test" );
	}
}

$maintClass = "BenchmarkPurge";
require_once( RUN_MAINTENANCE_IF_MAIN );