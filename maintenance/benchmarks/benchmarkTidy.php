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

class BenchmarkTidy extends Benchmarker {
	public function __construct() {
		parent::__construct();
		$this->addOption( 'file', 'Path to file containing the input text', false, true );
	}

	public function execute() {
		$file = $this->getOption( 'file', __DIR__ . '/data/tidy/australia-untidy.html.gz' );
		$html = $this->loadFile( $file );
		if ( $html === false ) {
			$this->fatalError( "Unable to open input file" );
		}

		$this->benchmark( $html );
	}

	private function benchmark( $html ) {
		$services = $this->getServiceContainer();
		$contLang = $services->getContentLanguage();
		$tidy = $services->getTidy();
		$times = [];
		$innerCount = 10;
		$outerCount = 10;
		for ( $j = 1; $j <= $outerCount; $j++ ) {
			$t = microtime( true );
			for ( $i = 0; $i < $innerCount; $i++ ) {
				$tidy->tidy( $html );
				print $contLang->formatSize( memory_get_usage( true ) ) . "\n";
			}
			$t = ( ( microtime( true ) - $t ) / $innerCount ) * 1000;
			$times[] = $t;
			print "Run $j: $t\n";
		}
		print "\n";

		sort( $times, SORT_NUMERIC );
		$n = $outerCount;
		$min = $times[0];
		$max = end( $times );
		if ( $n % 2 ) {
			// @phan-suppress-next-line PhanTypeMismatchDimFetch
			$median = $times[ ( $n - 1 ) / 2 ];
		} else {
			$median = ( $times[$n / 2] + $times[$n / 2 - 1] ) / 2;
		}
		$mean = array_sum( $times ) / $n;

		print "Minimum: $min ms\n";
		print "Median: $median ms\n";
		print "Mean: $mean ms\n";
		print "Maximum: $max ms\n";
		print "Memory usage: " . $contLang->formatSize( memory_get_usage( true ) ) . "\n";
		print "Peak memory usage: " .
			$contLang->formatSize( memory_get_peak_usage( true ) ) . "\n";
	}
}

$maintClass = BenchmarkTidy::class;
require_once RUN_MAINTENANCE_IF_MAIN;
