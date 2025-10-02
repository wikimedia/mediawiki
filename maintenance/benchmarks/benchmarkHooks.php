<?php
/**
 * Benchmark %MediaWiki hooks.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Benchmark
 */

use MediaWiki\Maintenance\Benchmarker;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../includes/Benchmarker.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that benchmarks %MediaWiki hooks.
 *
 * @ingroup Benchmark
 */
class BenchmarkHooks extends Benchmarker {
	/** @inheritDoc */
	protected $defaultCount = 10;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Benchmark MediaWiki Hooks.' );
	}

	public function execute() {
		$cases = [
			'Loaded 0 hooks' => 0,
			'Loaded 1 hook' => 1,
			'Loaded 10 hooks' => 10,
			'Loaded 100 hooks' => 100,
		];
		$benches = [];
		$hookContainer = $this->getHookContainer();
		foreach ( $cases as $label => $load ) {
			$benches[$label] = [
				'setup' => function () use ( $load, $hookContainer ) {
					for ( $i = 1; $i <= $load; $i++ ) {
						$hookContainer->register( 'Test', [ $this, 'test' ] );
					}
				},
				'function' => static function () use ( $hookContainer ) {
					$hookContainer->run( 'Test' );
				}
			];
		}
		$this->bench( $benches );
	}

	/**
	 * @return bool
	 */
	public function test() {
		return true;
	}
}

// @codeCoverageIgnoreStart
$maintClass = BenchmarkHooks::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
