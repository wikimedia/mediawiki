<?php
/**
 * Copyright 2014 Wikimedia Foundation and contributors
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
 */

/**
 * Profiler that captures all function calls from the XHProf PHP extension.
 *
 * This extension can be installed via PECL or your operating system's package manager.
 * This also supports the Tideways-XHProf PHP extension.
 *
 * @ingroup Profiler
 * @see $wgProfiler
 * @see https://php.net/xhprof
 * @see https://github.com/tideways/php-xhprof-extension
 */
class ProfilerXhprof extends Profiler {
	/**
	 * @var XhprofData|null
	 */
	protected $xhprofData;

	/**
	 * Profiler for explicit, arbitrary, frame labels
	 * @var SectionProfiler
	 */
	protected $sprofiler;

	/**
	 * @see $wgProfiler
	 * @param array $params Associative array of parameters:
	 *  - int flags: Bitmask of constants from the XHProf or Tideways-XHProf extension
	 *    that will be passed to its enable function,
	 *    such as `XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY | XHPROF_FLAGS_NO_BUILTINS`.
	 *    With Tideways-XHProf, use `TIDEWAYS_XHPROF_FLAGS_*` instead.
	 *  - bool running: If true, it is assumed that the enable function was already
	 *    called. The `flags` option is ignored in this case.
	 *    This exists for use with a custom web entrypoint from which the profiler
	 *    is started before MediaWiki is included.
	 *  - array include: If set, only function names matching a pattern in this
	 *    array will be reported. The pattern strings will be matched using
	 *    the PHP fnmatch() function.
	 *  - array exclude: If set, function names matching an exact name in this
	 *    will be skipped over by XHProf. Ignored functions become transparent
	 *    in the profile. For example, `foo=>ignored=>bar` becomes `foo=>bar`.
	 *    This option is backed by XHProf's `ignored_functions` option.
	 *
	 *    **Note:** The `exclude` option is not supported in Tideways-XHProf.
	 */
	public function __construct( array $params = [] ) {
		parent::__construct( $params );

		// See T180183 and T247332 for why we need the 'running' option.
		if ( empty( $params['running'] ) ) {
			$flags = $params['flags'] ?? 0;
			if ( function_exists( 'xhprof_enable' ) ) {
				$options = isset( $params['exclude'] )
					? [ 'ignored_functions' => $params['exclude'] ]
					: [];
				xhprof_enable( $flags, $options );
			} elseif ( function_exists( 'tideways_xhprof_enable' ) ) {
				if ( isset( $params['exclude'] ) ) {
					throw new RuntimeException( 'The exclude option is not supported in tideways_xhprof' );
				}
				tideways_xhprof_enable( $flags );
			} else {
				throw new RuntimeException( 'Neither xhprof nor tideways_xhprof is installed' );
			}
		}

		$this->sprofiler = new SectionProfiler();
	}

	/**
	 * @return XhprofData
	 */
	public function getXhprofData() {
		if ( !$this->xhprofData ) {
			if ( function_exists( 'xhprof_disable' ) ) {
				$data = xhprof_disable();
			} elseif ( function_exists( 'tideways_xhprof_disable' ) ) {
				$data = tideways_xhprof_disable();
			} else {
				throw new RuntimeException( 'Neither xhprof nor tideways_xhprof is installed' );
			}
			$this->xhprofData = new XhprofData( $data, $this->params );
		}
		return $this->xhprofData;
	}

	/** @inheritDoc */
	public function scopedProfileIn( $section ) {
		$key = 'section.' . ltrim( $section, '.' );
		return $this->sprofiler->scopedProfileIn( $key );
	}

	/**
	 * No-op for xhprof profiling.
	 */
	public function close() {
	}

	/**
	 * Check if a function or section should be excluded from the output.
	 *
	 * @param string $name Function or section name.
	 * @return bool
	 */
	private function shouldExclude( $name ) {
		if ( $name === '-total' ) {
			return true;
		}
		if ( !empty( $this->params['include'] ) ) {
			foreach ( $this->params['include'] as $pattern ) {
				if ( fnmatch( $pattern, $name, FNM_NOESCAPE ) ) {
					return false;
				}
			}
			return true;
		}
		if ( !empty( $this->params['exclude'] ) ) {
			foreach ( $this->params['exclude'] as $pattern ) {
				if ( fnmatch( $pattern, $name, FNM_NOESCAPE ) ) {
					return true;
				}
			}
		}
		return false;
	}

	/** @inheritDoc */
	public function getFunctionStats() {
		$metrics = $this->getXhprofData()->getCompleteMetrics();
		$profile = [];

		$main = null; // units in ms
		foreach ( $metrics as $fname => $stats ) {
			if ( $this->shouldExclude( $fname ) ) {
				continue;
			}
			// Convert elapsed times from μs to ms to match interface
			$entry = [
				'name' => $fname,
				'calls' => $stats['ct'],
				'real' => $stats['wt']['total'] / 1000,
				'%real' => $stats['wt']['percent'],
				'cpu' => ( $stats['cpu']['total'] ?? 0 ) / 1000,
				'%cpu' => $stats['cpu']['percent'] ?? 0,
				'memory' => $stats['mu']['total'] ?? 0,
				'%memory' => $stats['mu']['percent'] ?? 0,
				'min_real' => $stats['wt']['min'] / 1000,
				'max_real' => $stats['wt']['max'] / 1000
			];
			$profile[] = $entry;
			if ( $fname === 'main()' ) {
				$main = $entry;
			}
		}

		// Merge in all of the custom profile sections
		foreach ( $this->sprofiler->getFunctionStats() as $stats ) {
			if ( $this->shouldExclude( $stats['name'] ) ) {
				continue;
			}

			// @note: getFunctionStats() values already in ms
			$stats['%real'] = $main['real'] ? $stats['real'] / $main['real'] * 100 : 0;
			$stats['%cpu'] = $main['cpu'] ? $stats['cpu'] / $main['cpu'] * 100 : 0;
			$stats['%memory'] = $main['memory'] ? $stats['memory'] / $main['memory'] * 100 : 0;
			$profile[] = $stats; // assume no section names collide with $metrics
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
		$data = $this->getFunctionStats();
		usort( $data, static function ( $a, $b ) {
			return $b['real'] <=> $a['real']; // descending
		} );

		$width = 140;
		$nameWidth = $width - 65;
		$format = "%-{$nameWidth}s %6d %9d %9d %9d %9d %7.3f%% %9d";
		$out = [];
		$out[] = sprintf( "%-{$nameWidth}s %6s %9s %9s %9s %9s %7s %9s",
			'Name', 'Calls', 'Total', 'Min', 'Each', 'Max', '%', 'Mem'
		);
		foreach ( $data as $stats ) {
			$out[] = sprintf( $format,
				$stats['name'],
				$stats['calls'],
				$stats['real'] * 1000,
				$stats['min_real'] * 1000,
				$stats['real'] / $stats['calls'] * 1000,
				$stats['max_real'] * 1000,
				$stats['%real'],
				$stats['memory']
			);
		}
		return implode( "\n", $out );
	}

	/**
	 * Retrieve raw data from xhprof
	 * @return array
	 */
	public function getRawData() {
		return $this->getXhprofData()->getRawData();
	}
}
