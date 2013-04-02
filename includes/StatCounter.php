<?php
/**
 * @defgroup StatCounter StatCounter
 *
 * StatCounter is used to increment arbitrary keys for profiling reasons.
 * The key/values are persisted in several possible ways (see $wgStatsMethod).
 */

/**
 * Aggregator for wfIncrStats() that batches updates per request.
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
 * @ingroup StatCounter
 */

/**
 * Aggregator for wfIncrStats() that batches updates per request.
 * This avoids spamming the collector many times for the same key.
 *
 * @ingroup StatCounter
 */
class StatCounter {
	/** @var Array */
	protected $deltas = array(); // (key => count)

	protected function __construct() {}

	/**
	 * @return StatCounter
	 */
	public static function singleton() {
		static $instance = null;
		if ( !$instance ) {
			$instance = new self();
		}
		return $instance;
	}

	/**
	 * Increment a key by delta $count
	 *
	 * @param string $key
	 * @param integer $count
	 * @return void
	 */
	public function incr( $key, $count = 1 ) {
		$this->deltas[$key] = isset( $this->deltas[$key] ) ? $this->deltas[$key] : 0;
		$this->deltas[$key] += $count;
		if ( PHP_SAPI === 'cli' ) {
			$this->flush();
		}
	}

	/**
	 * Flush all pending deltas to persistent storage
	 *
	 * @return void
	 */
	public function flush() {
		global $wgStatsMethod, $wgMemc;
		global $wgUDPProfilerHost, $wgUDPProfilerPort, $wgAggregateStatsID;

		try {
			if ( $wgStatsMethod === 'udp' ) {
				$id = strlen( $wgAggregateStatsID ) ? $wgAggregateStatsID : wfWikiID();

				$statlines = '';
				foreach ( $this->deltas as $key => $count ) {
					if ( $count != 0 ) {
						$statlines .= "stats/{$id} - {$count} 1 1 1 1 {$key}\n";
					}
				}

				if ( $statlines != '' ) {
					static $socket = null;
					if ( !$socket ) {
						$socket = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
						$statlines = "stats/{$id} - 1 1 1 1 1 -total\n{$statlines}";
					}
					wfSuppressWarnings();
					socket_sendto(
						$socket,
						$statlines,
						strlen( $statlines ),
						0,
						$wgUDPProfilerHost,
						$wgUDPProfilerPort
					);
					wfRestoreWarnings();
				}
			} elseif ( $wgStatsMethod === 'cache' ) {
				foreach ( $this->deltas as $key => $count ) {
					if ( $count != 0 ) {
						$ckey = wfMemcKey( 'stats', $key );
						if ( $wgMemc->incr( $ckey, $count ) === null ) {
							$wgMemc->add( $ckey, $count );
						}
					}
				}
			}
		} catch ( MWException $e ) {
			trigger_error( "Caught exception: {$e->getMessage()}");
		}

		$this->deltas = array();
	}
}
