<?php
/**
 * @defgroup Benchmark Benchmark
 * @ingroup  Maintenance
 */

/**
 * Base code for benchmark scripts.
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

namespace MediaWiki\Maintenance;

use Wikimedia\RunningStat;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Base class for benchmark scripts.
 *
 * @ingroup Benchmark
 */
abstract class Benchmarker extends Maintenance {
	/** @var int */
	protected $defaultCount = 100;

	public function __construct() {
		parent::__construct();
		$this->addOption( 'count', "How many times to run a benchmark. Default: {$this->defaultCount}", false, true );
		$this->addOption( 'verbose', 'Verbose logging of resource usage', false, false, 'v' );
	}

	public function bench( array $benchs ) {
		$this->startBench();
		$count = $this->getOption( 'count', $this->defaultCount );
		$verbose = $this->hasOption( 'verbose' );

		$normBenchs = [];
		$shortNames = [];

		// Normalise
		foreach ( $benchs as $key => $bench ) {
			// Shortcut for simple functions
			if ( is_callable( $bench ) ) {
				$bench = [ 'function' => $bench ];
			}

			// Default to no arguments
			if ( !isset( $bench['args'] ) ) {
				$bench['args'] = [];
			}

			// Name defaults to name of called function
			if ( is_string( $key ) ) {
				$name = $key;
			} else {
				// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
				if ( is_array( $bench['function'] ) ) {
					$class = $bench['function'][0];
					if ( is_object( $class ) ) {
						$class = get_class( $class );
					}
					$name = $class . '::' . $bench['function'][1];
				} else {
					// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
					$name = strval( $bench['function'] );
				}
				$argsText = implode(
					', ',
					array_map(
						static function ( $a ) {
							return var_export( $a, true );
						},
						// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
						$bench['args']
					)
				);
				$index = $shortNames[$name] = ( $shortNames[$name] ?? 0 ) + 1;
				$shorten = strlen( $argsText ) > 80 || str_contains( $argsText, "\n" );
				if ( !$shorten ) {
					$name = "$name($argsText)";
				}
				if ( $shorten || $index > 1 ) {
					$name = "$name@$index";
				}
			}

			$normBenchs[$name] = $bench;
		}

		foreach ( $normBenchs as $name => $bench ) {
			// Optional setup called outside time measure
			if ( isset( $bench['setup'] ) ) {
				$bench['setup']();
			}

			// Run benchmarks
			$stat = new RunningStat();
			for ( $i = 0; $i < $count; $i++ ) {
				// Setup outside of time measure for each loop
				if ( isset( $bench['setupEach'] ) ) {
					$bench['setupEach']();
				}
				$t = microtime( true );
				// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
				$bench['function']( ...$bench['args'] );
				$t = ( microtime( true ) - $t ) * 1000;
				if ( $verbose ) {
					$this->verboseRun( $i );
				}
				$stat->addObservation( $t );
			}

			$this->addResult( [
				'name' => $name,
				'count' => $stat->getCount(),
				// Get rate per second from mean (in ms)
				'rate' => $stat->getMean() == 0 ? INF : ( 1.0 / ( $stat->getMean() / 1000.0 ) ),
				'total' => $stat->getMean() * $stat->getCount(),
				'mean' => $stat->getMean(),
				'max' => $stat->max,
				'stddev' => $stat->getStdDev(),
				'usage' => [
					'mem' => memory_get_usage( true ),
					'mempeak' => memory_get_peak_usage( true ),
				],
			] );
		}
	}

	public function startBench() {
		$this->output(
			sprintf( "Running PHP version %s (%s) on %s %s %s\n\n",
				phpversion(),
				php_uname( 'm' ),
				php_uname( 's' ),
				php_uname( 'r' ),
				php_uname( 'v' )
			)
		);
	}

	public function addResult( array $res ) {
		$ret = sprintf( "%s\n  %' 6s: %d\n",
			$res['name'],
			'count',
			$res['count']
		);
		$ret .= sprintf( "  %' 6s: %8.1f/s\n",
			'rate',
			$res['rate']
		);
		foreach ( [ 'total', 'mean', 'max', 'stddev' ] as $metric ) {
			$ret .= sprintf( "  %' 6s: %8.2fms\n",
				$metric,
				$res[$metric]
			);
		}

		foreach ( [
			'mem' => 'Current memory usage',
			'mempeak' => 'Peak memory usage'
		] as $key => $label ) {
			$ret .= sprintf( "%' 20s: %s\n",
				$label,
				$this->formatSize( $res['usage'][$key] )
			);
		}

		$this->output( "$ret\n" );
	}

	protected function verboseRun( int $iteration ) {
		$this->output( sprintf( "#%3d - memory: %-10s - peak: %-10s\n",
			$iteration,
			$this->formatSize( memory_get_usage( true ) ),
			$this->formatSize( memory_get_peak_usage( true ) )
		) );
	}

	/**
	 * Format an amount of bytes into short human-readable string.
	 *
	 * This is simplified version of Language::formatSize() to avoid pulling
	 * all the general MediaWiki services, which can significantly influence
	 * measured memory use.
	 *
	 * @param int|float $bytes
	 * @return string Formatted in using IEC bytes (multiples of 1024)
	 */
	private function formatSize( $bytes ): string {
		if ( $bytes >= ( 1024 ** 3 ) ) {
			return number_format( $bytes / ( 1024 ** 3 ), 2 ) . ' GiB';
		}
		if ( $bytes >= ( 1024 ** 2 ) ) {
			return number_format( $bytes / ( 1024 ** 2 ), 2 ) . ' MiB';
		}
		if ( $bytes >= 1024 ) {
			return number_format( $bytes / 1024, 1 ) . ' KiB';
		}
		return $bytes . ' B';
	}

	/**
	 * @since 1.32
	 * @param string $file Path to file (maybe compressed with gzip)
	 * @return string|false Contents of file, or false if file not found
	 */
	protected function loadFile( $file ) {
		$content = file_get_contents( $file );
		// Detect GZIP compression header
		if ( str_starts_with( $content, "\037\213" ) ) {
			$content = gzdecode( $content );
		}
		return $content;
	}
}

/** @deprecated class alias since 1.43 */
class_alias( Benchmarker::class, 'Benchmarker' );
