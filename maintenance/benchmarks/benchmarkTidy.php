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

use MediaWiki\MediaWikiServices;

require __DIR__ . '/../includes/Benchmarker.php';

class BenchmarkTidy extends Benchmarker {
	public function __construct() {
		parent::__construct();
		$this->addOption( 'file', 'Path to file containing the input text', false, true );
		$this->addOption( 'driver', 'The Tidy driver name, or false to use the configured instance',
			false,  true );
		$this->addOption( 'tidy-config', 'JSON encoded value for the tidy configuration array',
			false, true );
	}

	public function execute() {
		$file = $this->getOption( 'file', __DIR__ . '/tidy/australia-untidy.html.gz' );
		$html = $this->loadFile( $file );
		if ( $html === false ) {
			$this->fatalError( "Unable to open input file" );
		}
		if ( $this->hasOption( 'driver' ) || $this->hasOption( 'tidy-config' ) ) {
			$config = json_decode( $this->getOption( 'tidy-config', '{}' ), true );
			if ( !is_array( $config ) ) {
				$this->fatalError( "Invalid JSON tidy config" );
			}
			$config += [ 'driver' => $this->getOption( 'driver', 'RemexHtml' ) ];
			$driver = MWTidy::factory( $config );
		} else {
			$driver = MWTidy::singleton();
			if ( !$driver ) {
				$this->fatalError( "Tidy disabled or not installed" );
			}
		}

		$this->benchmark( $driver, $html );
	}

	private function benchmark( $driver, $html ) {
		$contLang = MediaWikiServices::getInstance()->getContentLanguage();
		$times = [];
		$innerCount = 10;
		$outerCount = 10;
		for ( $j = 1; $j <= $outerCount; $j++ ) {
			$t = microtime( true );
			for ( $i = 0; $i < $innerCount; $i++ ) {
				$driver->tidy( $html );
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
require RUN_MAINTENANCE_IF_MAIN;
