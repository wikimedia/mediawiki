<?php
/**
 * Transaction profiling.
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
 * Helper class that detects high-contention DB queries via profiling calls
 *
 * This class is meant to work with a Profiler, as the later already knows
 * when methods start and finish (which may take place during transactions).
 *
 * @since 1.24
 */
class TransactionProfiler {
	/** @var float Seconds */
	protected $mDBLockThreshold = 3.0;
	/** @var array DB/server name => (active trx count, time, DBs involved) */
	protected $mDBTrxHoldingLocks = array();
	/** @var array DB/server name => list of (function name, elapsed time) */
	protected $mDBTrxMethodTimes = array();

	/**
	 * Mark a DB as in a transaction with one or more writes pending
	 *
	 * Note that there can be multiple connections to a single DB.
	 *
	 * @param string $server DB server
	 * @param string $db DB name
	 * @param string $id ID string of transaction
	 */
	public function transactionWritingIn( $server, $db, $id ) {
		$name = "{$server} ({$db}) (TRX#$id)";
		if ( isset( $this->mDBTrxHoldingLocks[$name] ) ) {
			wfDebugLog( 'DBPerformance', "Nested transaction for '$name' - out of sync." );
		}
		$this->mDBTrxHoldingLocks[$name] = array(
			'start' => microtime( true ),
			'conns' => array(),
		);
		$this->mDBTrxMethodTimes[$name] = array();

		foreach ( $this->mDBTrxHoldingLocks as $name => &$info ) {
			// Track all DBs in transactions for this transaction
			$info['conns'][$name] = 1;
		}
	}

	/**
	 * Register the name and time of a method for slow DB trx detection
	 *
	 * This method is only to be called by the Profiler class as methods finish
	 *
	 * @param string $method Function name
	 * @param float $realtime Wall time ellapsed
	 */
	public function recordFunctionCompletion( $method, $realtime ) {
		if ( !$this->mDBTrxHoldingLocks ) {
			// Short-circuit
			return;
		// @todo hardcoded check is a tad janky
		} elseif ( !preg_match( '/^query-m: /', $method ) && $realtime < 1.0 ) {
			// Not a DB master query nor slow enough
			return;
		}

		$now = microtime( true );
		foreach ( $this->mDBTrxHoldingLocks as $name => $info ) {
			// Hacky check to exclude entries from before the first TRX write
			if ( ( $now - $realtime ) >= $info['start'] ) {
				$this->mDBTrxMethodTimes[$name][] = array( $method, $realtime );
			}
		}
	}

	/**
	 * Mark a DB as no longer in a transaction
	 *
	 * This will check if locks are possibly held for longer than
	 * needed and log any affected transactions to a special DB log.
	 * Note that there can be multiple connections to a single DB.
	 *
	 * @param string $server DB server
	 * @param string $db DB name
	 * @param string $id ID string of transaction
	 */
	public function transactionWritingOut( $server, $db, $id ) {
		$name = "{$server} ({$db}) (TRX#$id)";
		if ( !isset( $this->mDBTrxMethodTimes[$name] ) ) {
			wfDebugLog( 'DBPerformance', "Detected no transaction for '$name' - out of sync." );
			return;
		}
		$slow = false;
		foreach ( $this->mDBTrxMethodTimes[$name] as $info ) {
			$realtime = $info[1];
			if ( $realtime >= $this->mDBLockThreshold ) {
				$slow = true;
				break;
			}
		}
		if ( $slow ) {
			$dbs = implode( ', ', array_keys( $this->mDBTrxHoldingLocks[$name]['conns'] ) );
			$msg = "Sub-optimal transaction on DB(s) [{$dbs}]:\n";
			foreach ( $this->mDBTrxMethodTimes[$name] as $i => $info ) {
				list( $method, $realtime ) = $info;
				$msg .= sprintf( "%d\t%.6f\t%s\n", $i, $realtime, $method );
			}
			wfDebugLog( 'DBPerformance', $msg );
		}
		unset( $this->mDBTrxHoldingLocks[$name] );
		unset( $this->mDBTrxMethodTimes[$name] );
	}
}
