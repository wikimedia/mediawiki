<?php
/**
 * Transaction profiling for contention
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

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\NullLogger;

/**
 * Helper class that detects high-contention DB queries via profiling calls
 *
 * This class is meant to work with a DatabaseBase object, which manages queries
 *
 * @since 1.24
 */
class TransactionProfiler implements LoggerAwareInterface {
	/** @var float Seconds */
	protected $dbLockThreshold = 3.0;
	/** @var float Seconds */
	protected $eventThreshold = .25;

	/** @var array transaction ID => (write start time, list of DBs involved) */
	protected $dbTrxHoldingLocks = [];
	/** @var array transaction ID => list of (query name, start time, end time) */
	protected $dbTrxMethodTimes = [];

	/** @var array */
	protected $hits = [
		'writes'      => 0,
		'queries'     => 0,
		'conns'       => 0,
		'masterConns' => 0
	];
	/** @var array */
	protected $expect = [
		'writes'         => INF,
		'queries'        => INF,
		'conns'          => INF,
		'masterConns'    => INF,
		'maxAffected'    => INF,
		'readQueryTime'  => INF,
		'writeQueryTime' => INF
	];
	/** @var array */
	protected $expectBy = [];

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	public function __construct() {
		$this->setLogger( new NullLogger() );
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Set performance expectations
	 *
	 * With conflicting expectations, the most narrow ones will be used
	 *
	 * @param string $event (writes,queries,conns,mConns)
	 * @param integer $value Maximum count of the event
	 * @param string $fname Caller
	 * @since 1.25
	 */
	public function setExpectation( $event, $value, $fname ) {
		$this->expect[$event] = isset( $this->expect[$event] )
			? min( $this->expect[$event], $value )
			: $value;
		if ( $this->expect[$event] == $value ) {
			$this->expectBy[$event] = $fname;
		}
	}

	/**
	 * Set multiple performance expectations
	 *
	 * With conflicting expectations, the most narrow ones will be used
	 *
	 * @param array $expects Map of (event => limit)
	 * @param $fname
	 * @since 1.26
	 */
	public function setExpectations( array $expects, $fname ) {
		foreach ( $expects as $event => $value ) {
			$this->setExpectation( $event, $value, $fname );
		}
	}

	/**
	 * Reset performance expectations and hit counters
	 *
	 * @since 1.25
	 */
	public function resetExpectations() {
		foreach ( $this->hits as &$val ) {
			$val = 0;
		}
		unset( $val );
		foreach ( $this->expect as &$val ) {
			$val = INF;
		}
		unset( $val );
		$this->expectBy = [];
	}

	/**
	 * Mark a DB as having been connected to with a new handle
	 *
	 * Note that there can be multiple connections to a single DB.
	 *
	 * @param string $server DB server
	 * @param string $db DB name
	 * @param bool $isMaster
	 */
	public function recordConnection( $server, $db, $isMaster ) {
		// Report when too many connections happen...
		if ( $this->hits['conns']++ == $this->expect['conns'] ) {
			$this->reportExpectationViolated( 'conns', "[connect to $server ($db)]" );
		}
		if ( $isMaster && $this->hits['masterConns']++ == $this->expect['masterConns'] ) {
			$this->reportExpectationViolated( 'masterConns', "[connect to $server ($db)]" );
		}
	}

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
		if ( isset( $this->dbTrxHoldingLocks[$name] ) ) {
			$this->logger->info( "Nested transaction for '$name' - out of sync." );
		}
		$this->dbTrxHoldingLocks[$name] = [
			'start' => microtime( true ),
			'conns' => [], // all connections involved
		];
		$this->dbTrxMethodTimes[$name] = [];

		foreach ( $this->dbTrxHoldingLocks as $name => &$info ) {
			// Track all DBs in transactions for this transaction
			$info['conns'][$name] = 1;
		}
	}

	/**
	 * Register the name and time of a method for slow DB trx detection
	 *
	 * This assumes that all queries are synchronous (non-overlapping)
	 *
	 * @param string $query Function name or generalized SQL
	 * @param float $sTime Starting UNIX wall time
	 * @param bool $isWrite Whether this is a write query
	 * @param integer $n Number of affected rows
	 */
	public function recordQueryCompletion( $query, $sTime, $isWrite = false, $n = 0 ) {
		$eTime = microtime( true );
		$elapsed = ( $eTime - $sTime );

		if ( $isWrite && $n > $this->expect['maxAffected'] ) {
			$this->logger->info( "Query affected $n row(s):\n" . $query . "\n" .
				wfBacktrace( true ) );
		}

		// Report when too many writes/queries happen...
		if ( $this->hits['queries']++ == $this->expect['queries'] ) {
			$this->reportExpectationViolated( 'queries', $query );
		}
		if ( $isWrite && $this->hits['writes']++ == $this->expect['writes'] ) {
			$this->reportExpectationViolated( 'writes', $query );
		}
		// Report slow queries...
		if ( !$isWrite && $elapsed > $this->expect['readQueryTime'] ) {
			$this->reportExpectationViolated( 'readQueryTime', $query, $elapsed );
		}
		if ( $isWrite && $elapsed > $this->expect['writeQueryTime'] ) {
			$this->reportExpectationViolated( 'writeQueryTime', $query, $elapsed );
		}

		if ( !$this->dbTrxHoldingLocks ) {
			// Short-circuit
			return;
		} elseif ( !$isWrite && $elapsed < $this->eventThreshold ) {
			// Not an important query nor slow enough
			return;
		}

		foreach ( $this->dbTrxHoldingLocks as $name => $info ) {
			$lastQuery = end( $this->dbTrxMethodTimes[$name] );
			if ( $lastQuery ) {
				// Additional query in the trx...
				$lastEnd = $lastQuery[2];
				if ( $sTime >= $lastEnd ) { // sanity check
					if ( ( $sTime - $lastEnd ) > $this->eventThreshold ) {
						// Add an entry representing the time spent doing non-queries
						$this->dbTrxMethodTimes[$name][] = [ '...delay...', $lastEnd, $sTime ];
					}
					$this->dbTrxMethodTimes[$name][] = [ $query, $sTime, $eTime ];
				}
			} else {
				// First query in the trx...
				if ( $sTime >= $info['start'] ) { // sanity check
					$this->dbTrxMethodTimes[$name][] = [ $query, $sTime, $eTime ];
				}
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
	 * @param float $writeTime Time spent in write queries
	 */
	public function transactionWritingOut( $server, $db, $id, $writeTime = 0.0 ) {
		$name = "{$server} ({$db}) (TRX#$id)";
		if ( !isset( $this->dbTrxMethodTimes[$name] ) ) {
			$this->logger->info( "Detected no transaction for '$name' - out of sync." );
			return;
		}

		$slow = false;

		// Warn if too much time was spend writing...
		if ( $writeTime > $this->expect['writeQueryTime'] ) {
			$this->reportExpectationViolated(
				'writeQueryTime',
				"[transaction $id writes to {$server} ({$db})]",
				$writeTime
			);
			$slow = true;
		}
		// Fill in the last non-query period...
		$lastQuery = end( $this->dbTrxMethodTimes[$name] );
		if ( $lastQuery ) {
			$now = microtime( true );
			$lastEnd = $lastQuery[2];
			if ( ( $now - $lastEnd ) > $this->eventThreshold ) {
				$this->dbTrxMethodTimes[$name][] = [ '...delay...', $lastEnd, $now ];
			}
		}
		// Check for any slow queries or non-query periods...
		foreach ( $this->dbTrxMethodTimes[$name] as $info ) {
			$elapsed = ( $info[2] - $info[1] );
			if ( $elapsed >= $this->dbLockThreshold ) {
				$slow = true;
				break;
			}
		}
		if ( $slow ) {
			$dbs = implode( ', ', array_keys( $this->dbTrxHoldingLocks[$name]['conns'] ) );
			$msg = "Sub-optimal transaction on DB(s) [{$dbs}]:\n";
			foreach ( $this->dbTrxMethodTimes[$name] as $i => $info ) {
				list( $query, $sTime, $end ) = $info;
				$msg .= sprintf( "%d\t%.6f\t%s\n", $i, ( $end - $sTime ), $query );
			}
			$this->logger->info( $msg );
		}
		unset( $this->dbTrxHoldingLocks[$name] );
		unset( $this->dbTrxMethodTimes[$name] );
	}

	/**
	 * @param string $expect
	 * @param string $query
	 * @param string|float|int $actual [optional]
	 */
	protected function reportExpectationViolated( $expect, $query, $actual = null ) {
		$n = $this->expect[$expect];
		$by = $this->expectBy[$expect];
		$actual = ( $actual !== null ) ? " (actual: $actual)" : "";

		$this->logger->info(
			"Expectation ($expect <= $n) by $by not met$actual:\n$query\n" .
			wfBacktrace( true )
		);
	}
}
