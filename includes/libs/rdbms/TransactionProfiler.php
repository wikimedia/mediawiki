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
namespace Wikimedia\Rdbms;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;
use Wikimedia\ScopedCallback;

/**
 * Detect high-contention DB queries via profiling calls.
 *
 * This class is meant to work with an IDatabase object, which manages queries.
 *
 * @internal For use by Database only
 * @since 1.24
 * @ingroup Profiler
 * @ingroup Database
 */
class TransactionProfiler implements LoggerAwareInterface {
	/** @var LoggerInterface */
	private $logger;
	/** @var array<string,array> Map of (name => threshold value) */
	private $expect;
	/** @var array<string,int> Map of (name => current hits) */
	private $hits;

	/**
	 * @var array<string,array> Map of (trx ID => (write start time, list of DBs involved))
	 * @phan-var array<string,array{start:float,conns:array<string,int>}>
	 */
	private $dbTrxHoldingLocks;

	/**
	 * @var array[][] Map of (trx ID => list of (query name, start time, end time))
	 * @phan-var array<string,array<int,array{0:string,1:float,2:float}>>
	 */
	private $dbTrxMethodTimes;

	/** @var bool Whether logging is disabled */
	private $silenced;

	/** Treat locks as long-running if they last longer than this many seconds */
	private const DB_LOCK_THRESHOLD_SEC = 3.0;
	/** Include events in any violation logs if they last longer than this many seconds */
	private const EVENT_THRESHOLD_SEC = 0.25;

	/** List of event names */
	private const EVENT_NAMES = [
		'writes',
		'queries',
		'conns',
		'masterConns',
		'maxAffected',
		'readQueryRows',
		'readQueryTime',
		'writeQueryTime'
	];

	/** List of event names with hit counters */
	private const COUNTER_EVENT_NAMES = [
		'writes',
		'queries',
		'conns',
		'masterConns'
	];

	/** Key to max expected value */
	private const FLD_LIMIT = 0;
	/** Key to the function that set the max expected value */
	private const FLD_FNAME = 1;

	public function __construct() {
		$this->initPlaceholderExpectations();

		$this->dbTrxHoldingLocks = [];
		$this->dbTrxMethodTimes = [];

		$this->silenced = false;

		$this->setLogger( new NullLogger() );
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * @param bool $value
	 * @return bool Old value
	 * @since 1.28
	 */
	public function setSilenced( bool $value ) {
		$old = $this->silenced;
		$this->silenced = $value;

		return $old;
	}

	/**
	 * Disable the logging of warnings until the returned object goes out of scope or is consumed.
	 * @return ScopedCallback
	 */
	public function silenceForScope() {
		$oldSilenced = $this->setSilenced( true );
		return new ScopedCallback( function () use ( $oldSilenced ) {
			$this->setSilenced( $oldSilenced );
		} );
	}

	/**
	 * Set performance expectations
	 *
	 * With conflicting expectations, the most narrow ones will be used
	 *
	 * @param string $event One of the names in TransactionProfiler::EVENT_NAMES
	 * @param float|int $limit Maximum event count, event value, or total event value
	 * @param string $fname Caller
	 * @since 1.25
	 */
	public function setExpectation( string $event, $limit, string $fname ) {
		if ( !isset( $this->expect[$event] ) ) {
			return; // obsolete/bogus expectation
		}

		if ( $limit <= $this->expect[$event][self::FLD_LIMIT] ) {
			// New limit is more restrictive
			$this->expect[$event] = [
				self::FLD_LIMIT => $limit,
				self::FLD_FNAME => $fname
			];
		}
	}

	/**
	 * Set one or multiple performance expectations
	 *
	 * With conflicting expectations, the most narrow ones will be used
	 *
	 * Use this to initialize expectations or make them stricter mid-request
	 *
	 * @param array $expects Map of (event => limit)
	 * @param string $fname
	 * @since 1.26
	 */
	public function setExpectations( array $expects, string $fname ) {
		foreach ( $expects as $event => $value ) {
			$this->setExpectation( $event, $value, $fname );
		}
	}

	/**
	 * Reset all performance expectations and hit counters
	 *
	 * Use this for unit testing or before applying a totally different set of expectations
	 * for a different part of the request, such as during "post-send" (execution after HTTP
	 * response completion)
	 *
	 * @since 1.25
	 */
	public function resetExpectations() {
		$this->initPlaceholderExpectations();
	}

	/**
	 * Clear all expectations and hit counters and set new performance expectations
	 *
	 * Use this to apply a totally different set of expectations for a different part
	 * of the request, such as during "post-send" (execution after HTTP response completion)
	 *
	 * @param array $expects Map of (event => limit)
	 * @param string $fname
	 * @since 1.33
	 */
	public function redefineExpectations( array $expects, string $fname ) {
		$this->initPlaceholderExpectations();
		$this->setExpectations( $expects, $fname );
	}

	/**
	 * Mark a DB as having been connected to with a new handle
	 *
	 * Note that there can be multiple connections to a single DB.
	 *
	 * @param string $server DB server
	 * @param string|null $db DB name
	 * @param bool $isPrimary
	 */
	public function recordConnection( $server, $db, bool $isPrimary ) {
		// Report when too many connections happen...
		if ( $this->pingAndCheckThreshold( 'conns' ) ) {
			$this->reportExpectationViolated(
				'conns',
				"[connect to $server ($db)]",
				$this->hits['conns']
			);
		}

		// Report when too many primary connections happen...
		if ( $isPrimary && $this->pingAndCheckThreshold( 'masterConns' ) ) {
			$this->reportExpectationViolated(
				'masterConns',
				"[connect to $server ($db)]",
				$this->hits['masterConns']
			);
		}
	}

	/**
	 * Mark a DB as in a transaction with one or more writes pending
	 *
	 * Note that there can be multiple connections to a single DB.
	 *
	 * @param string $server DB server
	 * @param string|null $db DB name
	 * @param string $id ID string of transaction
	 */
	public function transactionWritingIn( $server, $db, string $id ) {
		$name = "{$db} {$server} TRX#$id";
		if ( isset( $this->dbTrxHoldingLocks[$name] ) ) {
			$this->logger->warning( "Nested transaction for '$name' - out of sync." );
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
	 * @param string|GeneralizedSql $query Function name or generalized SQL
	 * @param float $sTime Starting UNIX wall time
	 * @param bool $isWrite Whether this is a write query
	 * @param int|null $rowCount Number of affected/read rows
	 * @param string $trxId Transaction id
	 * @param string|null $serverName db host name like db1234
	 */
	public function recordQueryCompletion(
		$query,
		float $sTime,
		bool $isWrite,
		?int $rowCount,
		string $trxId,
		?string $serverName = null
	) {
		$eTime = microtime( true );
		$elapsed = ( $eTime - $sTime );

		if ( $isWrite && $this->isAboveThreshold( $rowCount, 'maxAffected' ) ) {
			$this->reportExpectationViolated( 'maxAffected', $query, $rowCount, $trxId, $serverName );
		} elseif ( !$isWrite && $this->isAboveThreshold( $rowCount, 'readQueryRows' ) ) {
			$this->reportExpectationViolated( 'readQueryRows', $query, $rowCount, $trxId, $serverName );
		}

		// Report when too many writes/queries happen...
		if ( $this->pingAndCheckThreshold( 'queries' ) ) {
			$this->reportExpectationViolated( 'queries', $query, $this->hits['queries'], $trxId, $serverName );
		}
		if ( $isWrite && $this->pingAndCheckThreshold( 'writes' ) ) {
			$this->reportExpectationViolated( 'writes', $query, $this->hits['writes'], $trxId, $serverName );
		}
		// Report slow queries...
		if ( !$isWrite && $this->isAboveThreshold( $elapsed, 'readQueryTime' ) ) {
			$this->reportExpectationViolated( 'readQueryTime', $query, $elapsed, $trxId, $serverName );
		}
		if ( $isWrite && $this->isAboveThreshold( $elapsed, 'writeQueryTime' ) ) {
			$this->reportExpectationViolated( 'writeQueryTime', $query, $elapsed, $trxId, $serverName );
		}

		if ( !$this->dbTrxHoldingLocks ) {
			// Short-circuit
			return;
		} elseif ( !$isWrite && $elapsed < self::EVENT_THRESHOLD_SEC ) {
			// Not an important query nor slow enough
			return;
		}

		foreach ( $this->dbTrxHoldingLocks as $name => $info ) {
			$lastQuery = end( $this->dbTrxMethodTimes[$name] );
			if ( $lastQuery ) {
				// Additional query in the trx...
				$lastEnd = $lastQuery[2];
				if ( $sTime >= $lastEnd ) {
					if ( ( $sTime - $lastEnd ) > self::EVENT_THRESHOLD_SEC ) {
						// Add an entry representing the time spent doing non-queries
						$this->dbTrxMethodTimes[$name][] = [ '...delay...', $lastEnd, $sTime ];
					}
					$this->dbTrxMethodTimes[$name][] = [ $query, $sTime, $eTime ];
				}
			} else {
				// First query in the trx...
				if ( $sTime >= $info['start'] ) {
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
	 * @param string|null $db DB name
	 * @param string $id ID string of transaction
	 * @param float $writeTime Time spent in write queries
	 * @param int $affected Number of rows affected by writes
	 */
	public function transactionWritingOut(
		$server,
		$db,
		string $id,
		float $writeTime,
		int $affected
	) {
		// Must match $name in transactionWritingIn()
		$name = "{$db} {$server} TRX#$id";
		if ( !isset( $this->dbTrxMethodTimes[$name] ) ) {
			$this->logger->warning( "Detected no transaction for '$name' - out of sync." );
			return;
		}

		$slow = false;

		// Warn if too much time was spend writing...
		if ( $this->isAboveThreshold( $writeTime, 'writeQueryTime' ) ) {
			$this->reportExpectationViolated(
				'writeQueryTime',
				"[transaction writes to {$db} at {$server}]",
				$writeTime,
				$id
			);
			$slow = true;
		}
		// Warn if too many rows were changed...
		if ( $this->isAboveThreshold( $affected, 'maxAffected' ) ) {
			$this->reportExpectationViolated(
				'maxAffected',
				"[transaction writes to {$db} at {$server}]",
				$affected,
				$id
			);
		}
		// Fill in the last non-query period...
		$lastQuery = end( $this->dbTrxMethodTimes[$name] );
		if ( $lastQuery ) {
			$now = microtime( true );
			$lastEnd = $lastQuery[2];
			if ( ( $now - $lastEnd ) > self::EVENT_THRESHOLD_SEC ) {
				$this->dbTrxMethodTimes[$name][] = [ '...delay...', $lastEnd, $now ];
			}
		}
		// Check for any slow queries or non-query periods...
		foreach ( $this->dbTrxMethodTimes[$name] as $info ) {
			$elapsed = ( $info[2] - $info[1] );
			if ( $elapsed >= self::DB_LOCK_THRESHOLD_SEC ) {
				$slow = true;
				break;
			}
		}
		if ( $slow ) {
			$trace = '';
			foreach ( $this->dbTrxMethodTimes[$name] as $i => [ $query, $sTime, $end ] ) {
				$trace .= sprintf(
					"%-2d %.3fs %s\n", $i, ( $end - $sTime ), $this->getGeneralizedSql( $query ) );
			}
			$this->logger->warning( "Sub-optimal transaction [{dbs}]:\n{trace}", [
				'dbs' => implode( ', ', array_keys( $this->dbTrxHoldingLocks[$name]['conns'] ) ),
				'trace' => $trace
			] );
		}
		unset( $this->dbTrxHoldingLocks[$name] );
		unset( $this->dbTrxMethodTimes[$name] );
	}

	private function initPlaceholderExpectations() {
		$this->expect = array_fill_keys(
			self::EVENT_NAMES,
			[ self::FLD_LIMIT => INF, self::FLD_FNAME => null ]
		);

		$this->hits = array_fill_keys( self::COUNTER_EVENT_NAMES, 0 );
	}

	/**
	 * @param float|int $value
	 * @param string $expectation
	 * @return bool
	 */
	private function isAboveThreshold( $value, string $expectation ) {
		return ( $value > $this->expect[$expectation][self::FLD_LIMIT] );
	}

	/**
	 * @param string $expectation
	 * @return bool
	 */
	private function pingAndCheckThreshold( string $expectation ) {
		$newValue = ++$this->hits[$expectation];

		return ( $newValue > $this->expect[$expectation][self::FLD_LIMIT] );
	}

	/**
	 * @param string $expectation
	 * @param string|GeneralizedSql $query
	 * @param float|int $actual
	 * @param string|null $trxId Transaction id
	 * @param string|null $serverName db host name like db1234
	 */
	private function reportExpectationViolated(
		$expectation,
		$query,
		$actual,
		?string $trxId = null,
		?string $serverName = null
	) {
		if ( $this->silenced ) {
			return;
		}

		$max = $this->expect[$expectation][self::FLD_LIMIT];
		$by = $this->expect[$expectation][self::FLD_FNAME];
		$message = "Expectation ($expectation <= $max) by $by not met (actual: {actualSeconds})";
		if ( $trxId ) {
			$message .= ' in trx #{trxId}';
		}
		$message .= ":\n{query}\n";
		$this->logger->warning(
			$message,
			[
				'measure' => $expectation,
				'maxSeconds' => $max,
				'by' => $by,
				'actualSeconds' => $actual,
				'query' => $this->getGeneralizedSql( $query ),
				'exception' => new RuntimeException(),
				'trxId' => $trxId,
				'fullQuery' => $this->getRawSql( $query ),
				'dbHost' => $serverName
			]
		);
	}

	/**
	 * @param GeneralizedSql|string $query
	 * @return string
	 */
	private function getGeneralizedSql( $query ) {
		return $query instanceof GeneralizedSql ? $query->stringify() : $query;
	}

	/**
	 * @param GeneralizedSql|string $query
	 * @return string
	 */
	private function getRawSql( $query ) {
		return $query instanceof GeneralizedSql ? $query->getRawSql() : $query;
	}
}
