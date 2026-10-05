<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

declare( strict_types=1 );

namespace Wikimedia\Stats;

use Wikimedia\Stats\Metrics\NullMetric;

/**
 * @internal
 * @since 1.48
 */
class NullStatsCache extends StatsCache {
	/**
	 * @inheritDoc
	 */
	public function get( string $component, string $name, string $expectedClass ) {
		return new NullMetric;
	}

	/**
	 * @inheritDoc
	 */
	public function set( string $component, string $name, $metric ): void {
	}

	/**
	 * @inheritDoc
	 */
	public function getAllMetrics(): array {
		return [];
	}
}
