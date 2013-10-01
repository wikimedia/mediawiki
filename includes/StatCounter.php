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
 * @author Aaron Schulz
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
		global $wgStatsMethod;

		$deltas = array_filter( $this->deltas ); // remove 0 valued entries
		if ( $wgStatsMethod === 'udp' ) {
			$this->sendDeltasUDP( $deltas );
		} elseif ( $wgStatsMethod === 'cache' ) {
			$this->sendDeltasMemc( $deltas );
		} else {
			// disabled
		}
		$this->deltas = array();
	}

	/**
	 * @param array $deltas
	 * @return void
	 */
	protected function sendDeltasUDP( array $deltas ) {
		global $wgUDPProfilerHost, $wgUDPProfilerPort, $wgAggregateStatsID,
			$wgStatsFormatString;

		$id = strlen( $wgAggregateStatsID ) ? $wgAggregateStatsID : wfWikiID();

		$lines = array();
		foreach ( $deltas as $key => $count ) {
			$lines[] = sprintf( $wgStatsFormatString, $id, $count, $key );
		}

		if ( count( $lines ) ) {
			static $socket = null;
			if ( !$socket ) {
				$socket = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
			}
			$packet = '';
			$packets = array();
			foreach ( $lines as $line ) {
				if ( ( strlen( $packet ) + strlen( $line ) ) > 1450 ) {
					$packets[] = $packet;
					$packet = '';
				}
				$packet .= $line;
			}
			if ( $packet != '' ) {
				$packets[] = $packet;
			}
			foreach ( $packets as $packet ) {
				wfSuppressWarnings();
				socket_sendto(
					$socket,
					$packet,
					strlen( $packet ),
					0,
					$wgUDPProfilerHost,
					$wgUDPProfilerPort
				);
				wfRestoreWarnings();
			}
		}
	}

	/**
	 * @param array $deltas
	 * @return void
	 */
	protected function sendDeltasMemc( array $deltas ) {
		global $wgMemc;

		foreach ( $deltas as $key => $count ) {
			$ckey = wfMemcKey( 'stats', $key );
			if ( $wgMemc->incr( $ckey, $count ) === null ) {
				$wgMemc->add( $ckey, $count );
			}
		}
	}
}
