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
 * Data reported to debug toolbar via getRawData() can be restricted by
 * setting $wgProfiler['toolbarCutoff'] to a minumum cumulative wall clock
 * percentage. Functions in the call graph which contribute less than this
 * percentage to the total wall clock time measured will be excluded from the
 * data sent to debug toolbar. The default cutoff is 0.1 (1/1000th of the
 * total time measured).
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
	 * Minimum wall time precentage for a fucntion to be reported to the debug
	 * toolbar via getRawData().
	 * @var float $toolbarCutoff
	 */
	protected $toolbarCutoff;

	/**
	 * @param array $params
	 * @see Xhprof::__construct()
	 */
	public function __construct( array $params = array() ) {
		$params = array_merge(
			array(
				'log' => 'text',
				'visible' => false,
				'toolbarCutoff' => 0.1,
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

	public function isPersistent() {
		// Disable per-title profiling sections
		return true;
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

	/**
	 * Get data for the debugging toolbar.
	 *
	 * @return array
	 * @see https://www.mediawiki.org/wiki/Debugging_toolbar
	 */
	public function getRawData() {
		$metrics = $this->xhprof->getCompleteMetrics();
		$endEpoch = $this->getTime();
		$profile = array();

		foreach ( $metrics as $fname => $stats ) {
			if ( $stats['wt']['percent'] < $this->toolbarCutoff ) {
				// Ignore functions which are not significant contributors
				// to the total elapsed time.
				continue;
			}

			$record = array(
				'name' => $fname,
				'calls' => $stats['ct'],
				'elapsed' => $stats['wt']['total'] / 1000,
				'percent' => $stats['wt']['percent'],
				'memory' => isset( $stats['mu'] ) ? $stats['mu']['total'] : 0,
				'min' => $stats['wt']['min'] / 1000,
				'max' => $stats['wt']['max'] / 1000,
				'overhead' => array_reduce(
					$stats['subcalls'],
					function( $carry, $item ) {
						return $carry + $item['ct'];
					},
					0
				),
				'periods' => array(),
			);

			// We are making up periods based on the call segments we have.
			// What we don't have is the start time for each call (which
			// actually may be a collection of multiple calls from the
			// caller). We will pretend that all the calls happen sequentially
			// and finish at the end of the end of the request.
			foreach ( $stats['calls'] as $call ) {
				$avgElapsed = $call['wt'] / 1000 / $call['ct'];
				$avgMem = isset( $call['mu'] ) ? $call['mu'] / $call['ct'] : 0;
				$start = $endEpoch - $avgElapsed;
				for ( $i = 0; $i < $call['ct']; $i++ ) {
					$callStart = $start + ( $avgElapsed * $i );
					$record['periods'][] = array(
						'start' => $callStart,
						'end' => $callStart + $avgElapsed,
						'memory' => $avgMem,
						'subcalls' => 0,
					);
				}
			}

			$profile[] = $record;
		}
		return $profile;
	}

	/**
	 * Log the data to some store or even the page output.
	 */
	public function logData() {
		if ( $this->logType === 'text' ) {
			$this->logText();
		} elseif ( $this->logType === 'udpprofile' ) {
			$this->logToUdpprofile();
		} elseif ( $this->logType === 'critpath' ){
			$this->logCriticalPath();
		} else {
			wfLogWarning(
				"Unknown ProfilerXhprof log type '{$this->logType}'"
			);
		}
	}

	/**
	 * Write a brief profile report to stdout.
	 */
	protected function logText() {
		if ( $this->mTemplated ) {
			$ct = $this->getContentType();
			if ( $ct === 'text/html' && $this->visible ) {
				$prefix = '<pre>';
				$suffix = '</pre>';
			} elseif ( $ct === 'text/javascript' || $ct === 'text/css' ) {
				$prefix = "\n/*";
				$suffix = "*/\n";
			} else {
				$prefix = "<!--";
				$suffix = "-->\n";
			}

			print $this->getSummaryReport( $prefix, $suffix );
		}
	}

	/**
	 * Send collected information to a udpprofile daemon.
	 *
	 * @see http://git.wikimedia.org/tree/operations%2Fsoftware.git/master/udpprofile
	 * @see $wgUDPProfilerHost
	 * @see $wgUDPProfilerPort
	 * @see $wgUDPProfilerFormatString
	 */
	protected function logToUdpprofile() {
		global $wgUDPProfilerHost, $wgUDPProfilerPort;
		global $wgUDPProfilerFormatString;

		if ( !function_exists( 'socket_create' ) ) {
			return;
		}

		$metrics = $this->xhprof->getInclusiveMetrics();

		$sock = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
		$buffer = '';
		$bufferSize = 0;
		foreach ( $metrics as $func => $data ) {
			$line = sprintf( $wgUDPProfilerFormatString,
				$this->getProfileID(),
				$data['ct'],
				isset( $data['cpu'] ) ? $data['cpu']['total'] : 0,
				isset( $data['cpu'] ) ? $data['cpu']['variance'] : 0,
				$data['wt']['total'] / 1000,
				$data['wt']['variance'],
				$func,
				isset( $data['mu'] ) ? $data['mu']['total'] : 0
			);
			$lineLength = strlen( $line );
			if ( $lineLength + $bufferSize > 1400 ) {
				// Line would exceed max packet size, send packet before
				// appending to buffer.
				socket_sendto( $sock, $buffer, $bufferSize, 0,
					$wgUDPProfilerHost, $wgUDPProfilerPort
				);
				$buffer = '';
				$bufferSize = 0;
			}
			$buffer .= $line;
			$bufferSize += $lineLength;
		}
		// Send final buffer
		socket_sendto( $sock, $buffer, $bufferSize, 0x100 /* EOF */,
			$wgUDPProfilerHost, $wgUDPProfilerPort
		);
	}

	/**
	 * Write a critical path report to stdout.
	 */
	protected function logCriticalPath() {
		if ( $this->mTemplated ) {
			$ct = $this->getContentType();
			if ( $ct === 'text/html' && $this->visible ) {
				$prefix = '<pre>Critical path:';
				$suffix = '</pre>';
			} elseif ( $ct === 'text/javascript' || $ct === 'text/css' ) {
				$prefix = "\n/* Critical path:";
				$suffix = "*/\n";
			} else {
				$prefix = "<!-- Critical path:";
				$suffix = "-->\n";
			}

			print $this->getCriticalPathReport( $prefix, $suffix );
		}
	}

	/**
	 * Get the content type of the current request.
	 * @return string
	 */
	protected function getContentType() {
		foreach ( headers_list() as $header ) {
			if ( preg_match( '#^content-type: (\w+/\w+);?#i', $header, $m ) ) {
				return $m[1];
			}
		}
		return 'application/octet-stream';
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

	/**
	 * Get a brief report of the most costly code path by wall clock time.
	 *
	 * Each line of the report includes this data:
	 * - Total wall clock time spent in function in seconds
	 * - Function name
	 *
	 * @param string $header Header text to prepend to report
	 * @param string $footer Footer text to append to report
	 * @return string
	 */
	protected function getCriticalPathReport( $header = '', $footer = '' ) {
		$data = $this->xhprof->getCriticalPath();

		$out = array( $header );
		$out[] = sprintf( "%7s %9s %9s %6s %4s",
			'Time%', 'Time', 'Mem', 'Calls', 'Name'
		);

		$format = '%6.2f%% %9.6f %9d %6d %s%s';
		$total = null;
		$nest = 0;
		foreach ( $data as $key => $stats ) {
			list( $parent, $child ) = Xhprof::splitKey( $key );
			if ( $total === null ) {
				$total = $stats;
			}
			$out[] = sprintf( $format,
				100 * $stats['wt'] / $total['wt'],
				$stats['wt'] / 1e6,
				isset( $stats['mu'] ) ? $stats['mu'] : 0,
				$stats['ct'],
				//$nest ? str_repeat( ' ', $nest - 1 ) . '┗ ' : '',
				$nest ? str_repeat( ' ', $nest - 1 ) . '└─' : '',
				$child
			);
			$nest++;
		}
		$out[] = $footer;
		return implode( "\n", $out );
	}
}
