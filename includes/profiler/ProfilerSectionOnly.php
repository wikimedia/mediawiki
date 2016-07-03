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
 */

/**
 * Profiler that only tracks explicit profiling sections
 *
 * @code
 * $wgProfiler['class'] = 'ProfilerSectionOnly';
 * $wgProfiler['output'] = 'text';
 * $wgProfiler['visible'] = true;
 * @endcode
 *
 * @author Aaron Schulz
 * @ingroup Profiler
 * @since 1.25
 */
class ProfilerSectionOnly extends Profiler {
	/** @var SectionProfiler */
	protected $sprofiler;

	public function __construct( array $params = [] ) {
		parent::__construct( $params );
		$this->sprofiler = new SectionProfiler();
	}

	public function scopedProfileIn( $section ) {
		return $this->sprofiler->scopedProfileIn( $section );
	}

	public function close() {
	}

	public function getFunctionStats() {
		return $this->sprofiler->getFunctionStats();
	}

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
		usort( $data, function( $a, $b ) {
			if ( $a['real'] === $b['real'] ) {
				return 0;
			}
			return ( $a['real'] > $b['real'] ) ? -1 : 1; // descending
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
}
