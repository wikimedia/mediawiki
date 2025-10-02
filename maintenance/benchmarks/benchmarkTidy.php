<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Benchmark
 */

use MediaWiki\Maintenance\Benchmarker;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../includes/Benchmarker.php';
// @codeCoverageIgnoreEnd

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

	private function benchmark( string $html ) {
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

// @codeCoverageIgnoreStart
$maintClass = BenchmarkTidy::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
