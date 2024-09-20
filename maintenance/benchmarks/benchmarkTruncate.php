<?php

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../includes/Benchmarker.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that benchmarks TRUNCATE versus DELETE queries.
 *
 * @ingroup Benchmark
 */
class BenchmarkTruncate extends Benchmarker {
	public function execute() {
		$fname = __METHOD__;
		$dbw = $this->getDB( DB_PRIMARY );

		$dbw->dropTable( 'benchmark_perm_test', $fname );
		$permTable = $dbw->tableName( 'benchmark_perm_test', 'raw' );
		$dbw->duplicateTableStructure(
			$dbw->tableName( 'page', 'raw' ),
			$permTable,
			false,
			$fname
		);

		$dbw->dropTable( 'benchmark_temp_test', $fname );
		$tempTable = $dbw->tableName( 'benchmark_temp_test', 'raw' );
		$dbw->duplicateTableStructure(
			$dbw->tableName( 'page', 'raw' ),
			$tempTable,
			true,
			$fname
		);

		$this->bench( [
			'perm truncate' => static function () use ( $dbw, $fname, $permTable ) {
				$dbw->query( "TRUNCATE $permTable", $fname );
			},
			'perm delete' => static function () use ( $dbw, $fname, $permTable ) {
				$dbw->query( "DELETE FROM $permTable", $fname );
			},
			'temp truncate' => static function () use ( $dbw, $fname, $tempTable ) {
				// Use a raw query not truncate() to avoid the "pristine" check.
				// The other cases also use query() so that they will have comparable overhead.
				$dbw->query( "TRUNCATE $tempTable", $fname );
			},
			'temp delete' => static function () use ( $dbw, $fname, $tempTable ) {
				$dbw->query( "DELETE FROM $tempTable", $fname );
			}
		] );

		$dbw->dropTable( 'benchmark_perm_test', $fname );
		$dbw->dropTable( 'benchmark_temp_test', $fname );
	}
}
// @codeCoverageIgnoreStart
$maintClass = BenchmarkTruncate::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
