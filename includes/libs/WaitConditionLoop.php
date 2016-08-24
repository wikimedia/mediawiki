<?php
/**
 * Wait loop that reaches a condition or times out.
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
 * @author Aaron Schulz
 */

/**
 * Wait loop that reaches a condition or times out
 * @since 1.29
 */
class WaitConditionLoop {
	/** @var callable */
	private $condition;
	/** @var callable[] */
	private $busyCallbacks = [];
	/** @var float Seconds */
	private $timeout;
	/** @var float Seconds */
	private $lastWaitTime;

	/**
	 * Keep checking $condition() until the StatusValue it returns is "good" or not "OK"
	 * per isGood() and isOK(), or until the timeout specified by $timeout is reached
	 *
	 * @param callable $condition Callback that returns a StatusValue
	 * @param float $timeout Timeout in seconds
	 * @param array &$busyCallbacks List of callbacks to do useful work (by reference)
	 */
	public function __construct( callable $condition, $timeout = 5.0, &$busyCallbacks = [] ) {
		$this->condition = $condition;
		$this->timeout = $timeout;
		$this->busyCallbacks =& $busyCallbacks;
	}

	/**
	 * Invoke the loop
	 *
	 * The resulting status will fall into three possibilities:
	 *   - If isGood(), then the condition was reached
	 *   - If not isGood() but isOK(), then a timeout was reached
	 *   - If not isOK(), then some serious error occured in the callback
	 *
	 * @return StatusValue
	 */
	public function invoke() {
		$elapsed = 0.0; // seconds
		$sleepUs = 0; // microseconds to sleep each time
		do {
			$checkStartTime = microtime( true );
			// Check if the condition is met yet
			$realStart = microtime( true );
			$cpuStart = $this->getCpuTime();
			/** @var StatusValue $status */
			$status = call_user_func( $this->condition );
			$cpu = $this->getCpuTime() - $cpuStart;
			$real = microtime( true ) - $realStart;
			// Exit if the condition is reached
			if ( $status->isGood() || !$status->isOK() ) {
				$this->lastWaitTime = $elapsed;

				return $status;
			}
			// Detect if the callback seems to block or if justs burns CPU
			$callbackBlocksViaIO = ( $real > 0.100 && $cpu <= $real * .03 );
			if ( $this->runBusyCallbacks( 1 ) == 0 && !$callbackBlocksViaIO ) {
				// 10 queries = 10(10+100)/2 ms = 550ms, 14 queries = 1050ms
				$sleepUs = min( $sleepUs + 10 * 1e3, 1e6 ); // stop incrementing at ~1s
				usleep( $sleepUs );
			}
			$checkEndTime = microtime( true );
			// The max() protects against the clock getting set back
			$elapsed += max( $checkEndTime - $checkStartTime, 0.010 );
		} while ( $elapsed < $this->timeout );

		$status->error( 'timeout' );
		$this->lastWaitTime = $elapsed;

		return $status;
	}

	/**
	 * @return float Seconds
	 */
	public function getLastWaitTime() {
		return $this->lastWaitTime;
	}

	/**
	 * @return float Returns 0.0 if not supported (Windows on PHP < 7)
	 */
	private function getCpuTime() {
		$time = 0.0;

		if ( defined( 'HHVM_VERSION' ) && PHP_OS === 'Linux' ) {
			$ru = getrusage( 2 /* RUSAGE_THREAD */ );
		} else {
			$ru = getrusage( 0 /* RUSAGE_SELF */ );
		}
		if ( $ru ) {
			$time += $ru['ru_utime.tv_sec'] + $ru['ru_utime.tv_usec'] / 1e6;
			$time += $ru['ru_stime.tv_sec'] + $ru['ru_stime.tv_usec'] / 1e6;
		}

		return $time;
	}

	/**
	 * Run callbacks to due useful things instead of blocking for stuff to happen
	 *
	 * @param integer $maxRun Maximum number of tasks to run
	 * @return integer Number of callbacks run this time
	 * @since 1.28
	 */
	protected function runBusyCallbacks( $maxRun = INF ) {
		$runCount = 0;
		foreach ( $this->busyCallbacks as $i => $workCallback ) {
			if ( $runCount++ >= $maxRun ) {
				break;
			}
			unset( $this->busyCallbacks[$i] ); // consume
			$workCallback();
		}

		return $runCount;
	}

}