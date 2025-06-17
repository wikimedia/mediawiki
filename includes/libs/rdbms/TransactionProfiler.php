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

use InvalidArgumentException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RuntimeException;
use Wikimedia\ScopedCallback;
use Wikimedia\Stats\StatsFactory;

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
	/** @var StatsFactory */
	private $statsFactory;
	/** @var array<string,array> Map of (event name => map of FLD_* class constants) */
	private $expect;
	/** @var array<string,int> Map of (event name => current hits) */
	private $hits;
	/** @var array<string,int> Map of (event name => violation counter) */
	private $violations;
	/** @var array<string,int> Map of (event name => silence counter) */
	private $silenced;

	/**
	 * @var array<string,array> Map of (trx ID => (write start time, list of DBs involved))
	 * @phan-var array<string,array{start:float,conns:array<string,int>}>
	 */
	private $dbTrxHoldingLocks;

	/**
	 * @var array[][] Map of (trx ID => list of (query name, start time, end time))
	 * @phan-var array<string,array<int,array{0:string|GeneralizedSQL,1:float,2:float}>>
	 */
	private $dbTrxMethodTimes;

	/** @var string|null HTTP request method; null for CLI mode */
	private $method;

	/** @var float|null */
	private $wallClockOverride;

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

	/** Any type of expectation */
	public const EXPECTATION_ANY = 'any';
	/** Any expectations about replica usage never occurring */
	public const EXPECTATION_REPLICAS_ONLY = 'replicas-only';

	public function __construct() {
		$this->initPlaceholderExpectations();

		$this->dbTrxHoldingLocks = [];
		$this->dbTrxMethodTimes = [];

		$this->silenced = array_fill_keys( self::EVENT_NAMES, 0 );

		$this->setLogger( new NullLogger() );
		$this->statsFactory = StatsFactory::newNull();
	}

	public function setLogger( LoggerInterface $logger ): void {
		$this->logger = $logger;
	}

	/**
	 * Set statsFactory
	 *
	 * @param StatsFactory $statsFactory
	 * @return void
	 */
	public function setStatsFactory( StatsFactory $statsFactory ) {
		$this->statsFactory = $statsFactory;
	}

	/**
	 * @param ?string $method HTTP method; null for CLI mode
	 * @return void
	 */
	public function setRequestMethod( ?string $method ) {
		$this->method = $method;
	}

	/**
	 * Temporarily ignore expectations until the returned object goes out of scope
	 *
	 * During this time, violation of expectations will not be logged and counters
	 * for expectations (e.g. "conns") will not be incremented.
	 *
	 * This will suppress warnings about event counters which have a limit of zero.
	 * The main use case is too avoid warnings about primary connections/writes and
	 * warnings about getting any primary/replica connections at all.
	 *
	 * @param string $type Class EXPECTATION_* constant [default: TransactionProfiler::EXPECTATION_ANY]
	 * @return ScopedCallback
	 */
	public function silenceForScope( string $type = self::EXPECTATION_ANY ) {
		if ( $type === self::EXPECTATION_REPLICAS_ONLY ) {
			$events = [];
			foreach ( [ 'writes', 'masterConns' ] as $event ) {
				if ( $this->expect[$event][self::FLD_LIMIT] === 0 ) {
					$events[] = $event;
				}
			}
		} else {
			$events = self::EVENT_NAMES;
		}

		foreach ( $events as $event ) {
			++$this->silenced[$event];
		}

		return new ScopedCallback( function () use ( $events ) {
			foreach ( $events as $event ) {
				--$this->silenced[$event];
			}
		} );
	}

	/**
	 * Set performance expectations
	 *
	 * With conflicting expectations, the most narrow ones will be used
	 *
	 * @param string $event Event name, {@see self::EVENT_NAMES}
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
	 * @param array $expects Map of (event name => limit), {@see self::EVENT_NAMES}
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
	 * @param array $expects Map of (event name => limit), {@see self::EVENT_NAMES}
	 * @param string $fname
	 * @since 1.33
	 */
	public function redefineExpectations( array $expects, string $fname ) {
		$this->initPlaceholderExpectations();
		$this->setExpectations( $expects, $fname );
	}

	/**
	 * Get the expectation associated with a specific event name.
	 *
	 * This will return the value of the expectation even if the event is silenced.
	 *
	 * Use this to check if a specific event is allowed before performing it, such as checking
	 * if the request will allow writes before performing them and instead deferring the writes
	 * to outside the request.
	 *
	 * @since 1.44
	 * @param string $event Event name. Valid event names are defined in {@see self::EVENT_NAMES}
	 * @return float|int Maximum event count, event value, or total event value
	 *    depending on the type of event.
	 * @throws InvalidArgumentException If the provided event name is not one in {@see self::EVENT_NAMES}
	 */
	public function getExpectation( string $event ) {
		if ( !isset( $this->expect[$event] ) ) {
			throw new InvalidArgumentException( "Unrecognised event name '$event' provided." );
		}

		return $this->expect[$event][self::FLD_LIMIT];
	}

	/**
	 * Mark a DB as having been connected to with a new handle
	 *
	 * Note that there can be multiple connections to a single DB.
	 *
	 * @param string $server DB server
	 * @param string|null $db DB name
	 * @param bool $isPrimaryWithReplicas If the server is the primary and there are replicas
	 */
	public function recordConnection( $server, $db, bool $isPrimaryWithReplicas ) {
		// Report when too many connections happen...
		if ( $this->pingAndCheckThreshold( 'conns' ) ) {
			$this->reportExpectationViolated(
				'conns',
				"[connect to $server ($db)]",
				$this->hits['conns']
			);
		}

		// Report when too many primary connections happen...
		if ( $isPrimaryWithReplicas && $this->pingAndCheckThreshold( 'masterConns' ) ) {
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
	 * @param float $startTime UNIX timestamp
	 */
	public function transactionWritingIn( $server, $db, string $id, float $startTime ) {
		$name = "{$db} {$server} TRX#$id";
		if ( isset( $this->dbTrxHoldingLocks[$name] ) ) {
			$this->logger->warning( "Nested transaction for '$name' - out of sync." );
		}
		$this->dbTrxHoldingLocks[$name] = [
			'start' => $startTime,
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
		$eTime = $this->getCurrentTime();
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
			$now = $this->getCurrentTime();
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
			$this->logger->warning( "Suboptimal transaction [{dbs}]:\n{trace}", [
				'dbs' => implode( ', ', array_keys( $this->dbTrxHoldingLocks[$name]['conns'] ) ),
				'trace' => mb_substr( $trace, 0, 2000 )
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
		$this->violations = array_fill_keys( self::EVENT_NAMES, 0 );
	}

	/**
	 * @param float|int $value
	 * @param string $event
	 * @return bool
	 */
	private function isAboveThreshold( $value, string $event ) {
		if ( $this->silenced[$event] > 0 ) {
			return false;
		}

		return ( $value > $this->expect[$event][self::FLD_LIMIT] );
	}

	/**
	 * @param string $event
	 * @return bool
	 */
	private function pingAndCheckThreshold( string $event ) {
		if ( $this->silenced[$event] > 0 ) {
			return false;
		}

		$newValue = ++$this->hits[$event];
		$limit = $this->expect[$event][self::FLD_LIMIT];

		return ( $newValue > $limit );
	}

	/**
	 * @param string $event
	 * @param string|GeneralizedSql $query
	 * @param float|int $actual
	 * @param string|null $trxId Transaction id
	 * @param string|null $serverName db host name like db1234
	 */
	private function reportExpectationViolated(
		$event,
		$query,
		$actual,
		?string $trxId = null,
		?string $serverName = null
	) {
		$violations = ++$this->violations[$event];
		// First violation; check if this is a web request
		if ( $violations === 1 && $this->method !== null ) {
			$this->statsFactory->getCounter( 'rdbms_trxprofiler_warnings_total' )
				->setLabel( 'event', $event )
				->setLabel( 'method', $this->method )
				->copyToStatsdAt( "rdbms_trxprofiler_warnings.$event.{$this->method}" )
				->increment();
		}

		$max = $this->expect[$event][self::FLD_LIMIT];
		$by = $this->expect[$event][self::FLD_FNAME];

		$message = "Expectation ($event <= $max) by $by not met (actual: {actualSeconds})";
		if ( $trxId ) {
			$message .= ' in trx #{trxId}';
		}
		$message .= ":\n{query}\n";

		$this->logger->warning(
			$message,
			[
				'db_log_category' => 'performance',
				'measure' => $event,
				'maxSeconds' => $max,
				'by' => $by,
				'actualSeconds' => $actual,
				'query' => $this->getGeneralizedSql( $query ),
				'exception' => new RuntimeException(),
				'trxId' => $trxId,
				// Avoid truncated JSON in Logstash (T349140)
				'fullQuery' => mb_substr( $this->getRawSql( $query ), 0, 2000 ),
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

	/**
	 * @return float UNIX timestamp
	 * @codeCoverageIgnore
	 */
	private function getCurrentTime() {
		return $this->wallClockOverride ?: microtime( true );
	}

	/**
	 * @param float|null &$time Mock UNIX timestamp for testing
	 * @codeCoverageIgnore
	 */
	public function setMockTime( &$time ) {
		$this->wallClockOverride =& $time;
	}
}
