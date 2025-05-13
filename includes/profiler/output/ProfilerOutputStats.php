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

use MediaWiki\MediaWikiServices;

/**
 * Flush profiling data to StatsD.
 *
 * @ingroup Profiler
 * @since 1.25
 */
class ProfilerOutputStats extends ProfilerOutput {

	/**
	 * Flush profiling data to the current profiling context's stats buffer.
	 *
	 * @param array[] $stats
	 */
	public function log( array $stats ) {
		$prefix = $this->params['prefix'] ?? '';
		$statsFactory = MediaWikiServices::getInstance()->getStatsFactory();
		$posCounter = $statsFactory->getCounter( 'ProfilerOutputStats_calls_total' );
		$posCpuTimer = $statsFactory->getTiming( 'ProfilerOutputStats_cpu_seconds' );
		$posRealTimer = $statsFactory->getTiming( 'ProfilerOutputStats_real_seconds' );

		foreach ( $stats as $stat ) {
			$key = $prefix ? "{$prefix}_{$stat['name']}" : "{$stat['name']}";

			// Convert fractional seconds to whole milliseconds
			$cpu = round( $stat['cpu'] * 1000 );
			$real = round( $stat['real'] * 1000 );

			$posCounter->setLabel( 'key', $key )->increment();
			$posCpuTimer->setLabel( 'key', $key )->observe( $cpu );
			$posRealTimer->setLabel( 'key', $key )->observe( $real );
		}
	}
}
