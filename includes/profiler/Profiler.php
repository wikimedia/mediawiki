<?php
/**
 * Base class and functions for profiling.
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
 * @defgroup Profiler Profiler
 * This file is only included if profiling is enabled
 */

/**
 * Begin profiling of a function
 * @param string $functionname name of the function we will profile
 */
function wfProfileIn( $functionname ) {
	if ( Profiler::$__instance === null ) { // use this directly to reduce overhead
		Profiler::instance();
	}
	if ( !( Profiler::$__instance instanceof ProfilerStub ) ) {
		Profiler::$__instance->profileIn( $functionname );
	}
}

/**
 * Stop profiling of a function
 * @param string $functionname name of the function we have profiled
 */
function wfProfileOut( $functionname = 'missing' ) {
	if ( Profiler::$__instance === null ) { // use this directly to reduce overhead
		Profiler::instance();
	}
	if ( !( Profiler::$__instance instanceof ProfilerStub ) ) {
		Profiler::$__instance->profileOut( $functionname );
	}
}

/**
 * Class for handling function-scope profiling
 *
 * @since 1.22
 */
class ProfileSection {
	protected $name; // string; method name
	protected $enabled = false; // boolean; whether profiling is enabled

	/**
	 * Begin profiling of a function and return an object that ends profiling of
	 * the function when that object leaves scope. As long as the object is not
	 * specifically linked to other objects, it will fall out of scope at the same
	 * moment that the function to be profiled terminates.
	 *
	 * This is typically called like:
	 * <code>$section = new ProfileSection( __METHOD__ );</code>
	 *
	 * @param string $name Name of the function to profile
	 */
	public function __construct( $name ) {
		$this->name = $name;
		if ( Profiler::$__instance === null ) { // use this directly to reduce overhead
			Profiler::instance();
		}
		if ( !( Profiler::$__instance instanceof ProfilerStub ) ) {
			$this->enabled = true;
			Profiler::$__instance->profileIn( $this->name );
		}
	}

	function __destruct() {
		if ( $this->enabled ) {
			Profiler::$__instance->profileOut( $this->name );
		}
	}
}

/**
 * Profiler base class that defines the interface and some trivial functionality
 *
 * @ingroup Profiler
 */
abstract class Profiler {
	/** @var string|bool Profiler ID for bucketing data */
	protected $mProfileID = false;
	/** @var bool Whether MediaWiki is in a SkinTemplate output context */
	protected $mTemplated = false;

	/** @var TransactionProfiler */
	protected $trxProfiler;

	// @codingStandardsIgnoreStart PSR2.Classes.PropertyDeclaration.Underscore
	/** @var Profiler Do not call this outside Profiler and ProfileSection */
	public static $__instance = null;
	// @codingStandardsIgnoreEnd

	/**
	 * @param array $params
	 */
	public function __construct( array $params ) {
		if ( isset( $params['profileID'] ) ) {
			$this->mProfileID = $params['profileID'];
		}
		$this->trxProfiler = new TransactionProfiler();
	}

	/**
	 * Singleton
	 * @return Profiler
	 */
	final public static function instance() {
		if ( self::$__instance === null ) {
			global $wgProfiler;
			if ( is_array( $wgProfiler ) ) {
				if ( !isset( $wgProfiler['class'] ) ) {
					$class = 'ProfilerStub';
				} elseif ( $wgProfiler['class'] === 'Profiler'  ) {
					$class = 'ProfilerStub'; // b/c; don't explode
				} else {
					$class = $wgProfiler['class'];
				}
				self::$__instance = new $class( $wgProfiler );
			} elseif ( $wgProfiler instanceof Profiler ) {
				self::$__instance = $wgProfiler; // back-compat
			} else {
				self::$__instance = new ProfilerStub( array() );
			}
		}
		return self::$__instance;
	}

	/**
	 * Set the profiler to a specific profiler instance. Mostly for dumpHTML
	 * @param Profiler $p
	 */
	final public static function setInstance( Profiler $p ) {
		self::$__instance = $p;
	}

	/**
	 * Return whether this a stub profiler
	 *
	 * @return bool
	 */
	abstract public function isStub();

	/**
	 * Return whether this profiler stores data
	 *
	 * Called by Parser::braceSubstitution. If true, the parser will not
	 * generate per-title profiling sections, to avoid overloading the
	 * profiling data collector.
	 *
	 * @see Profiler::logData()
	 * @return bool
	 */
	abstract public function isPersistent();

	/**
	 * @param string $id
	 */
	public function setProfileID( $id ) {
		$this->mProfileID = $id;
	}

	/**
	 * @return string
	 */
	public function getProfileID() {
		if ( $this->mProfileID === false ) {
			return wfWikiID();
		} else {
			return $this->mProfileID;
		}
	}

	/**
	 * Called by wfProfieIn()
	 *
	 * @param string $functionname
	 */
	abstract public function profileIn( $functionname );

	/**
	 * Called by wfProfieOut()
	 *
	 * @param  string $functionname
	 */
	abstract public function profileOut( $functionname );

	/**
	 * Mark a DB as in a transaction with one or more writes pending
	 *
	 * Note that there can be multiple connections to a single DB.
	 *
	 * @param string $server DB server
	 * @param string $db DB name
	 * @param string $id Resource ID string of connection
	 */
	public function transactionWritingIn( $server, $db, $id = '' ) {
		$this->trxProfiler->transactionWritingIn( $server, $db, $id );
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
	 * @param string $id Resource ID string of connection
	 */
	public function transactionWritingOut( $server, $db, $id = '' ) {
		$this->trxProfiler->transactionWritingOut( $server, $db, $id );
	}

	/**
	 * Close opened profiling sections
	 */
	abstract public function close();

	/**
	 * Log the data to some store or even the page output
	 */
	abstract public function logData();

	/**
	 * Mark this call as templated or not
	 *
	 * @param bool $t
	 */
	public function setTemplated( $t ) {
		$this->mTemplated = $t;
	}

	/**
	 * Returns a profiling output to be stored in debug file
	 *
	 * @return string
	 */
	abstract public function getOutput();

	/**
	 * @return array
	 */
	abstract public function getRawData();

	/**
	 * Get the initial time of the request, based either on $wgRequestTime or
	 * $wgRUstart. Will return null if not able to find data.
	 *
	 * @param string|bool $metric Metric to use, with the following possibilities:
	 *   - user: User CPU time (without system calls)
	 *   - cpu: Total CPU time (user and system calls)
	 *   - wall (or any other string): elapsed time
	 *   - false (default): will fall back to default metric
	 * @return float|null
	 */
	protected function getTime( $metric = 'wall' ) {
		if ( $metric === 'cpu' || $metric === 'user' ) {
			if ( !function_exists( 'getrusage' ) ) {
				return 0;
			}
			$ru = getrusage();
			$time = $ru['ru_utime.tv_sec'] + $ru['ru_utime.tv_usec'] / 1e6;
			if ( $metric === 'cpu' ) {
				# This is the time of system calls, added to the user time
				# it gives the total CPU time
				$time += $ru['ru_stime.tv_sec'] + $ru['ru_stime.tv_usec'] / 1e6;
			}
			return $time;
		} else {
			return microtime( true );
		}
	}

	/**
	 * Get the initial time of the request, based either on $wgRequestTime or
	 * $wgRUstart. Will return null if not able to find data.
	 *
	 * @param string|bool $metric Metric to use, with the following possibilities:
	 *   - user: User CPU time (without system calls)
	 *   - cpu: Total CPU time (user and system calls)
	 *   - wall (or any other string): elapsed time
	 *   - false (default): will fall back to default metric
	 * @return float|null
	 */
	protected function getInitialTime( $metric = 'wall' ) {
		global $wgRequestTime, $wgRUstart;

		if ( $metric === 'cpu' || $metric === 'user' ) {
			if ( !count( $wgRUstart ) ) {
				return null;
			}

			$time = $wgRUstart['ru_utime.tv_sec'] + $wgRUstart['ru_utime.tv_usec'] / 1e6;
			if ( $metric === 'cpu' ) {
				# This is the time of system calls, added to the user time
				# it gives the total CPU time
				$time += $wgRUstart['ru_stime.tv_sec'] + $wgRUstart['ru_stime.tv_usec'] / 1e6;
			}
			return $time;
		} else {
			if ( empty( $wgRequestTime ) ) {
				return null;
			} else {
				return $wgRequestTime;
			}
		}
	}

	/**
	 * Add an entry in the debug log file
	 *
	 * @param string $s to output
	 */
	protected function debug( $s ) {
		if ( function_exists( 'wfDebug' ) ) {
			wfDebug( $s );
		}
	}

	/**
	 * Add an entry in the debug log group
	 *
	 * @param string $group Group to send the message to
	 * @param string $s to output
	 */
	protected function debugGroup( $group, $s ) {
		if ( function_exists( 'wfDebugLog' ) ) {
			wfDebugLog( $group, $s );
		}
	}
}

/**
 * Helper class that detects high-contention DB queries via profiling calls
 *
 * This class is meant to work with a Profiler, as the later already knows
 * when methods start and finish (which may take place during transactions).
 *
 * @since 1.24
 */
class TransactionProfiler {
	/** @var float seconds */
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
		$this->mDBTrxHoldingLocks[$name] =
			array( 'start' => microtime( true ), 'conns' => array() );
		$this->mDBTrxMethodTimes[$name] = array();

		foreach ( $this->mDBTrxHoldingLocks as $name => &$info ) {
			$info['conns'][$name] = 1; // track all DBs in transactions for this transaction
		}
	}

	/**
	 * Register the name and time of a method for slow DB trx detection
	 *
	 * This method is only to be called by the Profiler class as methods finish
	 *
	 * @param string $method Function name
	 * @param float $realtime Wal time ellapsed
	 */
	public function recordFunctionCompletion( $method, $realtime ) {
		if ( !$this->mDBTrxHoldingLocks ) {
			return; // short-circuit
		// @TODO: hardcoded check is a tad janky (what about FOR UPDATE?)
		} elseif ( !preg_match( '/^query-m: (?!SELECT)/', $method )
			&& $realtime < $this->mDBLockThreshold
		) {
			return; // not a DB master query nor slow enough
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
