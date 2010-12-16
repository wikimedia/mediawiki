<?php
/**
 * @defgroup Benchmark Benchmark
 */

/**
 * Create a doxygen subgroup of Maintenance for benchmarks
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
 * @todo Report PHP version, OS ..
 * @file
 * @ingroup Maintenance Benchmark
 */

require_once( dirname( __FILE__ ) . '/../Maintenance.php' );
abstract class Benchmarker extends Maintenance {
	private $results;

	public function __construct() {
		parent::__construct();
		$this->addOption( 'count', "How many time to run a benchmark", false, true );
	}

	public function bench( array $benchs ) {
		$bench_number = 0;
		$count = $this->getOption( 'count', 100 );

		foreach( $benchs as $bench ) {
			// handle empty args
			if(!array_key_exists( 'args', $bench )) {
				$bench['args'] = array();
			}

			$bench_number++;
			$start = wfTime();
			for( $i=0; $i<$count; $i++ ) {
				call_user_func_array( $bench['function'], $bench['args'] );
			}
			$delta = wfTime() - $start;

			// function passed as a callback
			if( is_array( $bench['function'] ) ) {
				$ret = get_class( $bench['function'][0] ). '->' . $bench['function'][1];
				$bench['function'] = $ret;
			}

			$this->results[$bench_number] = array(
				'function'  => $bench['function'],
				'arguments' => $bench['args'], 
				'count'     => $count,
				'delta'     => $delta,
				'average'   => $delta / $count,
				);
		}
	}

	public function getFormattedResults( ) {
		$ret = '';
		foreach( $this->results as $res ) {
			// show function with args
			$ret .= sprintf( "%s times: function %s(%s) :\n",
				$res['count'],
				$res['function'],
				join( ', ', $res['arguments'] )
			);
			$ret .= sprintf( "   %6.2fms (%6.2fms each)\n",
				$res['delta']   * 1000,
				$res['average'] * 1000
			);
		}
		return $ret;
	}
}
