<?php
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
 * @ingroup Benchmark
 */

require_once __DIR__ . '/../includes/Benchmarker.php';

/**
 * Maintenance script that benchmarks string replacement methods.
 *
 * @ingroup Benchmark
 */
class BenchmarkStringReplacement extends Benchmarker {
	protected $defaultCount = 10000;
	private $input = 'MediaWiki:Some_random_test_page';

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Benchmark for string replacement methods.' );
	}

	public function execute() {
		$this->bench( [
			'strtr' => [ $this, 'bench_strtr' ],
			'str_replace' => [ $this, 'bench_str_replace' ],
		] );
	}

	protected function bench_strtr() {
		strtr( $this->input, "_", " " );
	}

	protected function bench_str_replace() {
		str_replace( "_", " ", $this->input );
	}
}

$maintClass = BenchmarkStringReplacement::class;
require_once RUN_MAINTENANCE_IF_MAIN;
