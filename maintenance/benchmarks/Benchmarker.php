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

require_once __DIR__ . '/../Maintenance.php';

/**
 * Base class for benchmark scripts.
 *
 * @ingroup Benchmark
 */
abstract class Benchmarker extends Maintenance {
	protected $defaultCount = 100;
	private $lang;

	public function __construct() {
		parent::__construct();
		$this->addOption( 'count', 'How many times to run a benchmark', false, true );
		$this->addOption( 'verbose', 'Verbose logging of resource usage', false, false, 'v' );
	}

	public function bench( array $benchs ) {
		$this->lang = Language::factory( 'en' );

		$this->startBench();
		$count = $this->getOption( 'count', $this->defaultCount );
		$verbose = $this->hasOption( 'verbose' );
		foreach ( $benchs as $key => $bench ) {
			// Shortcut for simple functions
			if ( is_callable( $bench ) ) {
				$bench = [ 'function' => $bench ];
			}

			// Default to no arguments
			if ( !isset( $bench['args'] ) ) {
				$bench['args'] = [];
			}

			// Optional setup called outside time measure
			if ( isset( $bench['setup'] ) ) {
				call_user_func( $bench['setup'] );
			}

			// Run benchmarks
			$times = [];
			for ( $i = 0; $i < $count; $i++ ) {
				$t = microtime( true );
				call_user_func_array( $bench['function'], $bench['args'] );
				$t = ( microtime( true ) - $t ) * 1000;
				if ( $verbose ) {
					$this->verboseRun( $i );
				}
				$times[] = $t;
			}

			// Collect metrics
			sort( $times, SORT_NUMERIC );
			$min = $times[0];
			$max = end( $times );
			if ( $count % 2 ) {
				$median = $times[ ( $count - 1 ) / 2 ];
			} else {
				$median = ( $times[$count / 2] + $times[$count / 2 - 1] ) / 2;
			}
			$total = array_sum( $times );
			$mean = $total / $count;

			// Name defaults to name of called function
			if ( is_string( $key ) ) {
				$name = $key;
			} else {
				if ( is_array( $bench['function'] ) ) {
					$name = get_class( $bench['function'][0] ) . '::' . $bench['function'][1];
				} else {
					$name = strval( $bench['function'] );
				}
				$name = sprintf( "%s(%s)",
					$name,
					implode( ', ', $bench['args'] )
				);
			}

			$this->addResult( [
				'name' => $name,
				'count' => $count,
				'total' => $total,
				'min' => $min,
				'median' => $median,
				'mean' => $mean,
				'max' => $max,
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

	public function addResult( $res ) {
		$ret = sprintf( "%s\n  %' 6s: %d\n",
			$res['name'],
			'times',
			$res['count']
		);

		foreach ( [ 'total', 'min', 'median', 'mean', 'max' ] as $metric ) {
			$ret .= sprintf( "  %' 6s: %6.2fms\n",
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
				$this->lang->formatSize( $res['usage'][$key] )
			);
		}

		$this->output( "$ret\n" );
	}

	protected function verboseRun( $iteration ) {
		$this->output( sprintf( "#%3d - memory: %-10s - peak: %-10s\n",
			$iteration,
			$this->lang->formatSize( memory_get_usage( true ) ),
			$this->lang->formatSize( memory_get_peak_usage( true ) )
		) );
	}
}
