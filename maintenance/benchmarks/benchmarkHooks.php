<?php
/**
 * Benchmark %MediaWiki hooks.
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
 * Maintenance script that benchmarks %MediaWiki hooks.
 *
 * @ingroup Benchmark
 */
class BenchmarkHooks extends Benchmarker {
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
		foreach ( $cases as $label => $load ) {
			$benches[$label] = [
				'setup' => function () use ( $load ) {
					global $wgHooks;
					$wgHooks['Test'] = [];
					for ( $i = 1; $i <= $load; $i++ ) {
						$wgHooks['Test'][] = [ $this, 'test' ];
					}
				},
				'function' => function () {
					Hooks::run( 'Test' );
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

$maintClass = BenchmarkHooks::class;
require_once RUN_MAINTENANCE_IF_MAIN;
