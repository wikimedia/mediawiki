<?php
/**
 * @section LICENSE
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
 */

/**
 * Convenience class for working with XHProf
 * <https://github.com/phacility/xhprof>. XHProf can be installed as a PECL
 * package for use with PHP5 (Zend PHP) and is built-in to HHVM 3.3.0.
 *
 * @author Bryan Davis <bd808@wikimedia.org>
 * @copyright © 2014 Bryan Davis and Wikimedia Foundation.
 * @since 1.25
 */
class Xhprof {

	/**
	 * @var array $config
	 */
	protected $config;

	/**
	 * Hierarchical profiling data returned by xhprof.
	 * @var array $hieraData
	 */
	protected $hieraData;

	/**
	 * Per-function inclusive data.
	 * @var array $inclusive
	 */
	protected $inclusive;

	/**
	 * Per-function inclusive and exclusive data.
	 * @var array $complete
	 */
	protected $complete;

	/**
	 * Per-function parent and child transition data.
	 * @var array $edges
	 */
	protected $edges;

	/**
	 * Configuration data can contain:
	 * - 'flags': Optional flags to add additional information to the
	 *            profiling data collected.
	 *            (XHPROF_FLAGS_NO_BUILTINS, XHPROF_FLAGS_CPU,
	 *            XHPROF_FLAGS_MEMORY)
	 * - 'exclude': Array of function names to exclude from profiling.
	 * - 'include': Array of function names to include in profiling.
	 * - 'sort': Key to sort per-function reports on.
	 *
	 * @param array $config
	 */
	public function __construct( array $config = array() ) {
		$this->config = array_merge(
			array(
				'flags' => 0,
				'exclude' => array(),
				'include' => null,
				'sort' => 'wt',
			),
			$config
		);

		xhprof_enable( $this->config['flags'], array(
			'ignored_functions' => $this->config['exclude']
		) );
	}

	/**
	 * Stop collecting profiling data.
	 * @return array Collected profiling data
	 */
	public function stop() {
		if ( $this->hieraData === null ) {
			$this->hieraData = $this->pruneData( xhprof_disable() );
		}
		return $this->hieraData;
	}

	/**
	 * Get raw data collected by xhprof.
	 *
	 * If data collection has not been stopped yet this method will halt
	 * collection to gather the profiling data.
	 *
	 * XHProf will collect different data depending on the flags that are used:
	 * - 'ct': number of matching events seen.
	 * - 'wt': elapsed wall time for this event.
	 * - 'ut': elapsed user cpu time for this event. (XHPROF_FLAGS_CPU)
	 * - 'st': elapsed system cpu time for this event. (XHPROF_FLAGS_CPU)
	 * - 'cpu': elapsed cpu time for this event. (XHPROF_FLAGS_CPU)
	 * - 'mu': memory usage for this event. (XHPROF_FLAGS_MEMORY)
	 *
	 * @return array
	 * @see stop()
	 * @see getInclusiveMetrics()
	 * @see getCompleteMetrics()
	 */
	public function getRawData() {
		return $this->stop();
	}

	/**
	 * Convert an xhprof data key into an array of ['parent', 'child']
	 * function names.
	 *
	 * The resulting array is left padded with nulls, so a key
	 * with no parent (eg 'main()') will return [null, 'function'].
	 *
	 * @return array
	 */
	public static function splitKey( $key ) {
		return array_pad( explode( '==>', $key, 2 ), -2, null );
	}

	/**
	 * Remove data for functions that are not included in the 'include'
	 * configuration array.
	 *
	 * @param array $data Raw xhprof data
	 * @return array
	 */
	protected function pruneData( $data ) {
		if ( !$this->config['include'] ) {
			return $data;
		}

		$want = array_fill_keys( $this->config['include'], true );
		$want['main()'] = true;

		$keep = array();
		foreach ( $data as $key => $stats ) {
			list( $parent, $child ) = self::splitKey( $key );
			if ( isset( $want[$parent] ) || isset( $want[$child] ) ) {
				$keep[$key] = $stats;
			}
		}
		return $keep;
	}

	/**
	 * Get the inclusive metrics for each function call. Inclusive metrics
	 * for given function include the metrics for all functions that were
	 * called from that function during the measurement period.
	 *
	 * If data collection has not been stopped yet this method will halt
	 * collection to gather the profiling data.
	 *
	 * @return array
	 * @see getRawData()
	 * @see getCompleteMetrics()
	 */
	public function getInclusiveMetrics() {
		if ( $this->inclusive === null ) {
			$this->stop();
			$this->inclusive = array();
			foreach ( $this->hieraData as $key => $stats ) {
				list( $parent, $child ) = self::splitKey( $key );
				if ( isset( $this->inclusive[$child] ) ) {
					foreach ( $stats as $stat => $value ) {
						$this->inclusive[$child][$stat] += $value;
					}
				} else {
					$this->inclusive[$child] = $stats;
				}
			}
			uasort( $this->inclusive, self::makeSortFuction(
				$this->config['sort']
			) );
		}
		return $this->inclusive;
	}

	/**
	 * Get the inclusive and exclusive metrics for each function call.
	 *
	 * If data collection has not been stopped yet this method will halt
	 * collection to gather the profiling data.
	 *
	 * @return array
	 * @see getRawData()
	 * @see getInclusiveMetrics()
	 */
	public function getCompleteMetrics() {
		if ( $this->complete === null ) {
			// Start with inclusive data
			$this->complete = $this->getInclusiveMetrics();

			// Initialize exclusive data with inclusive totals
			foreach ( $this->complete as $func => $stats ) {
				foreach ( $stats as $stat => $value ) {
					if ( $stat !== 'ct' ) {
						$this->complete[$func]["excl_{$stat}"] = $value;
					}
				}
			}

			// Deduct child inclusive data from exclusive data
			foreach( $this->hieraData as $key => $stats ) {
				list( $parent, $child ) = self::splitKey( $key );
				if ( isset( $this->complete[$parent] ) ) {
					foreach ( $stats as $stat => $value ) {
						if ( $stat !== 'ct' ) {
							$this->complete[$parent]["excl_{$stat}"] -= $value;
						}
					}
				}
			}

			uasort( $this->complete, self::makeSortFuction(
				( $this->config['sort'] === 'ct' ) ?
					'ct' : "excl_{$this->config['sort']}"
			) );
		}
		return $this->complete;
	}

	/**
	 * Get an array mapping all of the inbound and outbound edges for each
	 * function. Each element in the array will be an array containing 'in'
	 * and 'out' values. These will in turn be arrays where the keys are
	 * function names and the values are all true.
	 *
	 * @return array
	 * @see getCallers()
	 * @see getCallees()
	 */
	public function getEdges() {
		if ( $this->edges === null ) {
			$this->edges = array();
			foreach ( $this->hieraData as $key => $info ) {
				list( $parent, $child ) = self::splitKey( $key );
				if ( $parent !== null ) {
					if ( !isset( $this->edges[$parent] ) ) {
						$this->edges[$parent] = array(
							'in' => array(),
							'out' => array(),
						);
					}
					$this->edges[$parent]['out'][$child] = true;

					if ( !isset( $this->edges[$child] ) ) {
						$this->edges[$child] = array(
							'in' => array(),
							'out' => array(),
						);
					}
					$this->edges[$child]['in'][$parent] = true;
				}
			}
		}
		return $this->edges;
	}

	/**
	 * Get a list of all callers of a given function.
	 *
	 * @param string $function Function name
	 * @return array
	 * @see getEdges()
	 */
	public function getCallers( $function ) {
		$edges = $this->getEdges();
		if ( isset( $edges[$function] ) ) {
			return array_keys( $edges[$function]['in'] );
		} else {
			return array();
		}
	}

	/**
	 * Get a list of all callees from a given function.
	 *
	 * @param string $function Function name
	 * @return array
	 * @see getEdges()
	 */
	public function getCallees( $function ) {
		$edges = $this->getEdges();
		if ( isset( $edges[$function] ) ) {
			return array_keys( $edges[$function]['out'] );
		} else {
			return array();
		}
	}

	/**
	 * Make a closure to use as a sort function. The resulting function will
	 * sort by descending numeric values (largest value first).
	 *
	 * @param string $sortKey Data key to sort on
	 * @return Closure
	 */
	protected static function makeSortFuction( $sortKey ) {
		return function ( $a, $b ) use ( $sortKey ) {
			if ( isset( $a[$sortKey] ) && isset( $b[$sortKey] ) ) {
				// Descending sort: larger values will be first in result.
				// Assumes all values are numeric
				return $b[$sortKey] - $a[$sortKey];
			} else {
				// Sort datum with the key before those without
				return isset( $a[$sortKey] ) ? -1 : 1;
			}
		};
	}
}
