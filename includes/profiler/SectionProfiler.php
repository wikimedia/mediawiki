<?php
/**
 * Arbitrary section name based PHP profiling.
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
 * @ingroup Profiler
 * @author Aaron Schulz
 */

/**
 * Custom PHP profiler for parser/DB type section names that xhprof/xdebug can't handle
 *
 * @TODO: refactor implementation by moving Profiler code to here when non-automatic
 * profiler support is dropped.
 *
 * @since 1.25
 */
class SectionProfiler {
	/** @var ProfilerStandard */
	protected $profiler;

	public function __construct() {
		// This does *not* care about PHP request start time
		$this->profiler = new ProfilerStandard( array( 'initTotal' => false ) );
	}

	/**
	 * @param string $section
	 * @return ScopedCallback
	 */
	public function scopedProfileIn( $section ) {
		$profiler = $this->profiler;
		$sc = new ScopedCallback( function() use ( $profiler, $section ) {
			$profiler->profileOut( $section );
		} );
		$profiler->profileIn( $section );

		return $sc;
	}

	/**
	 * @param ScopedCallback $section
	 */
	public function scopedProfileOut( ScopedCallback $section ) {
		$section = null;
	}

	/**
	 * Get the raw and collated breakdown data for each method
	 *
	 * The percent time for each time is based on the current "total" time
	 * used is based on all methods so far. This method can therefore be
	 * called several times in between several profiling calls without the
	 * delays in usage of the profiler skewing the results. A "-total" entry
	 * is always included in the results.
	 *
	 * @return array List of method entries arrays, each having:
	 *   - name     : method name
	 *   - calls    : the number of method calls
	 *   - elapsed  : real time ellapsed (ms)
	 *   - percent  : percent real time
	 *   - memory   : memory used (bytes)
	 *   - min      : min real time of all calls (ms)
	 *   - max      : max real time of all calls (ms)
	 */
	public function getFunctionStats() {
		$data = $this->profiler->getRawData();

		$memoryTotal = 0;
		$elapsedTotal = 0;
		foreach ( $data as $item ) {
			$memoryTotal += $item['memory'];
			$elapsedTotal += $item['elapsed'];
		}

		foreach ( $data as &$item ) {
			$item['percent'] = $item['elapsed'] / $elapsedTotal * 100;
		}
		unset( $item );

		$data[] = array(
			'name' => '-total',
			'calls' => 1,
			'elapsed' => $elapsedTotal,
			'percent' => 100,
			'memory' => $memoryTotal,
			'min' => $elapsedTotal,
			'max' => $elapsedTotal
		);

		return $data;
	}
}
