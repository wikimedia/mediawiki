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
 * Maintenance script that benchmarks JSMinPlus.
 *
 * @ingroup Benchmark
 */
class BenchmarkJSMinPlus extends Benchmarker {
	protected $defaultCount = 10;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Benchmarks JSMinPlus.' );
		$this->addOption( 'file', 'Path to JS file', true, true );
	}

	public function execute() {
		Wikimedia\suppressWarnings();
		$content = file_get_contents( $this->getOption( 'file' ) );
		Wikimedia\restoreWarnings();
		if ( $content === false ) {
			$this->fatalError( 'Unable to open input file' );
		}

		$filename = basename( $this->getOption( 'file' ) );
		$parser = new JSParser();

		$this->bench( [
			"JSParser::parse ($filename)" => [
				'function' => function ( $parser, $content, $filename ) {
					$parser->parse( $content, $filename, 1 );
				},
				'args' => [ $parser, $content, $filename ]
			]
		] );
	}
}

$maintClass = BenchmarkJSMinPlus::class;
require_once RUN_MAINTENANCE_IF_MAIN;
