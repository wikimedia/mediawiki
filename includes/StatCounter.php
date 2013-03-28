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
		if ( PHP_SAPI === 'cli' ) {
			$this->sendDelta( $key, $count );
		} else {
			if ( !isset( $this->deltas[$key] ) ) {
				$this->deltas[$key] = 0;
			}
			$this->deltas[$key] += $count;
		}
	}

	/**
	 * Flush all pending deltas to persistent storage
	 *
	 * @return void
	 */
	public function flush() {
		try {
			foreach ( $this->deltas as $key => $count ) {
				$this->sendDelta( $key, $count );
			}
		} catch ( MWException $e ) {
			trigger_error( "Caught exception: {$e->getMessage()}");
		}
		$this->deltas = array();
	}

	/**
	 * @param string $key
	 * @param string $count
	 * @return void
	 */
	protected function sendDelta( $key, $count ) {
		global $wgStatsMethod;

		$count = intval( $count );
		if ( $count == 0 ) {
			return;
		}

		if ( $wgStatsMethod == 'udp' ) {
			global $wgUDPProfilerHost, $wgUDPProfilerPort, $wgAggregateStatsID;
			static $socket;

			$id = $wgAggregateStatsID !== false ? $wgAggregateStatsID : wfWikiID();

			if ( !$socket ) {
				$socket = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
				$statline = "stats/{$id} - 1 1 1 1 1 -total\n";
				socket_sendto(
					$socket,
					$statline,
					strlen( $statline ),
					0,
					$wgUDPProfilerHost,
					$wgUDPProfilerPort
				);
			}
			$statline = "stats/{$id} - {$count} 1 1 1 1 {$key}\n";
			wfSuppressWarnings();
			socket_sendto(
				$socket,
				$statline,
				strlen( $statline ),
				0,
				$wgUDPProfilerHost,
				$wgUDPProfilerPort
			);
			wfRestoreWarnings();
		} elseif ( $wgStatsMethod == 'cache' ) {
			global $wgMemc;
			$key = wfMemcKey( 'stats', $key );
			if ( is_null( $wgMemc->incr( $key, $count ) ) ) {
				$wgMemc->add( $key, $count );
			}
		} else {
			// Disabled
		}
	}

	function __destruct() {
		$this->flush();
	}
}
