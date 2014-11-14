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
 * Profiler wrapper for XHProf extension.
 *
 * Mimics the output of ProfilerSimpleText, ProfilerSimpleUDP or
 * ProfilerSimpleTrace but using data collected via the XHProf PHP extension.
 * This Profiler also produces data compatable with the debugging toolbar.
 *
 * To mimic ProfilerSimpleText results:
 * @code
 * $wgProfiler['class'] = 'ProfilerXhprof';
 * $wgProfiler['flags'] = XHPROF_FLAGS_NO_BUILTINS;
 * $wgProfiler['log'] = 'text';
 * $wgProfiler['visible'] = true;
 * @endcode
 *
 * To mimic ProfilerSimpleUDP results:
 * @code
 * $wgProfiler['class'] = 'ProfilerXhprof';
 * $wgProfiler['flags'] = XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY | XHPROF_FLAGS_NO_BUILTINS;
 * $wgProfiler['log'] = 'udpprofile';
 * @endcode
 *
 * Similar to ProfilerSimpleTrace results, report the most expensive path
 * through the application:
 * @code
 * $wgProfiler['class'] = 'ProfilerXhprof';
 * $wgProfiler['flags'] = XHPROF_FLAGS_NO_BUILTINS;
 * $wgProfiler['log'] = 'critpath';
 * $wgProfiler['visible'] = true;
 * @endcode
 *
 * Rather than obeying wfProfileIn() and wfProfileOut() calls placed in the
 * application code, ProfilerXhprof profiles all functions using the XHProf
 * PHP extenstion. For PHP5 users, this extension can be installed via PECL or
 * your operating system's package manager. XHProf support is built into HHVM.
 *
 * To restrict the functions for which profiling data is collected, you can
 * use either a whitelist ($wgProfiler['include']) or a blacklist
 * ($wgProfiler['exclude']) containing an array of function names. The
 * blacklist funcitonality is built into HHVM and will completely exclude the
 * named functions from profiling collection. The whitelist is implemented by
 * Xhprof class and will filter the data collected by XHProf before reporting.
 * See documentation for the Xhprof class and the XHProf extension for
 * additional information.
 *
 * @author Bryan Davis <bd808@wikimedia.org>
 * @copyright © 2014 Bryan Davis and Wikimedia Foundation.
 * @ingroup Profiler
 * @see Xhprof
 * @see https://php.net/xhprof
 * @see https://github.com/facebook/hhvm/blob/master/hphp/doc/profiling.md
 */
class ProfilerXhprof extends Profiler {

	/**
	 * @var Xhprof $xhprof
	 */
	protected $xhprof;

	/**
	 * Type of report to send when logData() is called.
	 * @var string $logType
	 */
	protected $logType;

	/**
	 * Should profile report sent to in page content be visible?
	 * @var bool $visible
	 */
	protected $visible;

	/**
	 * @param array $params
	 * @see Xhprof::__construct()
	 */
	public function __construct( array $params = array() ) {
		$params = array_merge(
			array(
				'log' => 'text',
				'visible' => false
			),
			$params
		);
		parent::__construct( $params );
		$this->logType = $params['log'];
		$this->visible = $params['visible'];
		$this->xhprof = new Xhprof( $params );
	}

	public function isStub() {
		return false;
	}

	/**
	 * No-op for xhprof profiling.
	 *
	 * Use the 'include' configuration key instead if you need to constrain
	 * the functions that are profiled.
	 *
	 * @param string $functionname
	 */
	public function profileIn( $functionname ) {
		global $wgDebugFunctionEntry;
		if ( $wgDebugFunctionEntry ) {
			$this->debug( "Entering {$functionname}" );
		}
	}

	/**
	 * No-op for xhprof profiling.
	 *
	 * Use the 'include' configuration key instead if you need to constrain
	 * the functions that are profiled.
	 *
	 * @param string $functionname
	 */
	public function profileOut( $functionname ) {
		global $wgDebugFunctionEntry;
		if ( $wgDebugFunctionEntry ) {
			$this->debug( "Exiting {$functionname}" );
		}
	}

	/**
	 * No-op for xhprof profiling.
	 */
	public function close() {
	}

	public function getFunctionStats() {
		$metrics = $this->xhprof->getCompleteMetrics();
		$profile = array();

		foreach ( $metrics as $fname => $stats ) {
			$profile[] = array(
				'name' => $fname,
				'calls' => $stats['ct'],
				'real' => $stats['wt']['total'] / 1000, // us => ms
				'%real' => $stats['wt']['percent'],
				'cpu' => isset( $stats['cpu'] ) ? $stats['cpu']['total'] / 1000 : 0, // us => ms
				'%cpu' => isset( $stats['cpu'] ) ? $stats['cpu']['percent'] : 0,
				'memory' => isset( $stats['mu'] ) ? $stats['mu']['total'] : 0,
				'%memory' => isset( $stats['mu'] ) ? $stats['mu']['percent'] : 0,
				'min' => $stats['wt']['min'] / 1000,
				'max' => $stats['wt']['max'] / 1000
			);
		}

		return $profile;
	}

	/**
	 * Returns a profiling output to be stored in debug file
	 *
	 * @return string
	 */
	public function getOutput() {
		return $this->getFunctionReport();
	}

	/**
	 * Get a report of profiled functions sorted by inclusive wall clock time
	 * in descending order.
	 *
	 * Each line of the report includes this data:
	 * - Function name
	 * - Number of times function was called
	 * - Total wall clock time spent in function in microseconds
	 * - Minimum wall clock time spent in function in microseconds
	 * - Average wall clock time spent in function in microseconds
	 * - Maximum wall clock time spent in function in microseconds
	 * - Percentage of total wall clock time spent in function
	 * - Total delta of memory usage from start to end of function in bytes
	 *
	 * @return string
	 */
	protected function getFunctionReport() {
		$data = $this->xhprof->getInclusiveMetrics();
		uasort( $data, Xhprof::makeSortFunction( 'wt', 'total' ) );

		$width = 140;
		$nameWidth = $width - 65;
		$format = "%-{$nameWidth}s %6d %9d %9d %9d %9d %7.3f%% %9d";
		$out = array();
		$out[] = sprintf( "%-{$nameWidth}s %6s %9s %9s %9s %9s %7s %9s",
			'Name', 'Calls', 'Total', 'Min', 'Each', 'Max', '%', 'Mem'
		);
		foreach ( $data as $func => $stats ) {
			$out[] = sprintf( $format,
				$func,
				$stats['ct'],
				$stats['wt']['total'],
				$stats['wt']['min'],
				$stats['wt']['mean'],
				$stats['wt']['max'],
				$stats['wt']['percent'],
				isset( $stats['mu'] ) ? $stats['mu']['total'] : 0
			);
		}
		return implode( "\n", $out );
	}

	/**
	 * Get a brief report of profiled functions sorted by inclusive wall clock
	 * time in descending order.
	 *
	 * Each line of the report includes this data:
	 * - Percentage of total wall clock time spent in function
	 * - Total wall clock time spent in function in seconds
	 * - Number of times function was called
	 * - Function name
	 *
	 * @param string $header Header text to prepend to report
	 * @param string $footer Footer text to append to report
	 * @return string
	 */
	protected function getSummaryReport( $header = '', $footer = '' ) {
		$data = $this->xhprof->getInclusiveMetrics();
		uasort( $data, Xhprof::makeSortFunction( 'wt', 'total' ) );

		$format = '%6.2f%% %3.6f %6d - %s';
		$out = array( $header );
		foreach ( $data as $func => $stats ) {
			$out[] = sprintf( $format,
				$stats['wt']['percent'],
				$stats['wt']['total'] / 1e6,
				$stats['ct'],
				$func
			);
		}
		$out[] = $footer;
		return implode( "\n", $out );
	}
}
