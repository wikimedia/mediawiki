<?php

use MediaWiki\Config\GlobalVarConfig;
use MediaWiki\Maintenance\Benchmarker;

require_once __DIR__ . '/../includes/Benchmarker.php';

/**
 * Benchmark GlobalVarConfig::get()
 *
 * @ingroup Benchmark
 */
class BenchmarkGlobalVarConfig extends Benchmarker {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Benchmark GlobalVarConfig::get()' );
	}

	public function execute() {
		$config = new GlobalVarConfig();
		$GLOBALS['wgBenchmarkGlobalVarConfigTest'] = 'value';
		$GLOBALS['wgBenchmarkGlobalVarConfigTestNull'] = null;

		$benches = [
			'GlobalVarConfig::get (hit)' => [
				'function' => static function () use ( $config ) {
					$config->get( 'BenchmarkGlobalVarConfigTest' );
				}
			],
			'GlobalVarConfig::get (null)' => [
				'function' => static function () use ( $config ) {
					$config->get( 'BenchmarkGlobalVarConfigTestNull' );
				}
			],
		];

		$this->bench( $benches );
	}
}

$maintClass = BenchmarkGlobalVarConfig::class;
require_once RUN_MAINTENANCE_IF_MAIN;
