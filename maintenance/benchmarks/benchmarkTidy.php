<?php

require __DIR__ . '/../Maintenance.php';

class BenchmarkTidy extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( 'file', 'A filename which contains the input text', true, true );
		$this->addOption( 'driver', 'The Tidy driver name, or false to use the configured instance',
			false,  true );
		$this->addOption( 'tidy-config', 'JSON encoded value for the tidy configuration array',
			false, true );
	}

	public function execute() {
		$html = file_get_contents( $this->getOption( 'file' ) );
		if ( $html === false ) {
			$this->error( "Unable to open input file", 1 );
		}
		if ( $this->hasOption( 'driver' ) || $this->hasOption( 'tidy-config' ) ) {
			$config = json_decode( $this->getOption( 'tidy-config', '{}' ), true );
			if ( !is_array( $config ) ) {
				$this->error( "Invalid JSON tidy config", 1 );
			}
			$config += [ 'driver' => $this->getOption( 'driver', 'RemexHtml' ) ];
			$driver = MWTidy::factory( $config );
		} else {
			$driver = MWTidy::singleton();
			if ( !$driver ) {
				$this->error( "Tidy disabled or not installed", 1 );
			}
		}

		$this->benchmark( $driver, $html );
	}

	private function benchmark( $driver, $html ) {
		global $wgContLang;

		$times = [];
		$innerCount = 10;
		$outerCount = 10;
		for ( $j = 1; $j <= $outerCount; $j++ ) {
			$t = microtime( true );
			for ( $i = 0; $i < $innerCount; $i++ ) {
				$driver->tidy( $html );
				print $wgContLang->formatSize( memory_get_usage( true ) ) . "\n";
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
			$median = $times[ ( $n - 1 ) / 2 ];
		} else {
			$median = ( $times[$n / 2] + $times[$n / 2 - 1] ) / 2;
		}
		$mean = array_sum( $times ) / $n;

		print "Minimum: $min ms\n";
		print "Median: $median ms\n";
		print "Mean: $mean ms\n";
		print "Maximum: $max ms\n";
		print "Memory usage: " .
			$wgContLang->formatSize( memory_get_usage( true ) ) . "\n";
		print "Peak memory usage: " .
			$wgContLang->formatSize( memory_get_peak_usage( true ) ) . "\n";
	}
}

$maintClass = 'BenchmarkTidy';
require RUN_MAINTENANCE_IF_MAIN;
