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

require_once __DIR__ . '/Benchmarker.php';

/**
 * Maintenance script that benchmarks JavaScriptMinifier.
 *
 * @ingroup Benchmark
 */
class BenchmarkJavaScriptMinifier extends Benchmarker {
	protected $defaultCount = 10;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Benchmark for JavaScriptMinifier.' );
		$this->addOption( 'file', 'Path to JavaScript file (may be gzipped)', false, true );
	}

	public function execute() {
		$file = $this->getOption( 'file', __DIR__ . '/jsmin/jquery-3.2.1.js.gz' );
		$filename = basename( $file );
		$content = $this->loadFile( $file );
		if ( $content === false ) {
			$this->fatalError( 'Unable to open input file' );
		}

		$this->bench( [
			"minify ($filename)" => [
				'function' => [ JavaScriptMinifier::class, 'minify' ],
				'args' => [ $content ],
			],
		] );
	}
}

$maintClass = BenchmarkJavaScriptMinifier::class;
require_once RUN_MAINTENANCE_IF_MAIN;
