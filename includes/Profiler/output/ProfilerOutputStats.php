<?php
/**
 * @license GPL-2.0-or-later
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
