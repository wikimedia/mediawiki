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
 * This file is only included if profiling is enabled
 */

/**
 * @defgroup Profiler Profiler
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
 * @ingroup Profiler
 * @todo document
 */
class Profiler {
	protected $mStack = array(), $mWorkStack = array(), $mCollated = array(),
		$mCalls = array(), $mTotals = array();
	protected $mTimeMetric = 'wall';
	protected $mProfileID = false, $mCollateDone = false, $mTemplated = false;

	protected $mDBLockThreshold = 5.0; // float; seconds
	/** @var Array DB/server name => (active trx count,timestamp) */
	protected $mDBTrxHoldingLocks = array();
	/** @var Array DB/server name => list of (method, elapsed time) */
	protected $mDBTrxMethodTimes = array();

	/** @var Profiler */
	public static $__instance = null; // do not call this outside Profiler and ProfileSection

	function __construct( $params ) {
		if ( isset( $params['timeMetric'] ) ) {
			$this->mTimeMetric = $params['timeMetric'];
		}
		if ( isset( $params['profileID'] ) ) {
			$this->mProfileID = $params['profileID'];
		}

		$this->addInitialStack();
	}

	/**
	 * Singleton
	 * @return Profiler
	 */
	public static function instance() {
		if ( self::$__instance === null ) {
			global $wgProfiler;
			if ( is_array( $wgProfiler ) ) {
				if ( !isset( $wgProfiler['class'] ) ) {
					$class = 'ProfilerStub';
				} else {
					$class = $wgProfiler['class'];
				}
				self::$__instance = new $class( $wgProfiler );
			} elseif ( $wgProfiler instanceof Profiler ) {
				self::$__instance = $wgProfiler; // back-compat
			} else {
				self::$__instance = new ProfilerStub( $wgProfiler );
			}
		}
		return self::$__instance;
	}

	/**
	 * Set the profiler to a specific profiler instance. Mostly for dumpHTML
	 * @param $p Profiler object
	 */
	public static function setInstance( Profiler $p ) {
		self::$__instance = $p;
	}

	/**
	 * Return whether this a stub profiler
	 *
	 * @return Boolean
	 */
	public function isStub() {
		return false;
	}

	/**
	 * Return whether this profiler stores data
	 *
	 * @see Profiler::logData()
	 * @return Boolean
	 */
	public function isPersistent() {
		return true;
	}

	public function setProfileID( $id ) {
		$this->mProfileID = $id;
	}

	public function getProfileID() {
		if ( $this->mProfileID === false ) {
			return wfWikiID();
		} else {
			return $this->mProfileID;
		}
	}

	/**
	 * Add the inital item in the stack.
	 */
	protected function addInitialStack() {
		// Push an entry for the pre-profile setup time onto the stack
		$initial = $this->getInitialTime();
		if ( $initial !== null ) {
			$this->mWorkStack[] = array( '-total', 0, $initial, 0 );
			$this->mStack[] = array( '-setup', 1, $initial, 0, $this->getTime(), 0 );
		} else {
			$this->profileIn( '-total' );
		}
	}

	/**
	 * Called by wfProfieIn()
	 *
	 * @param $functionname String
	 */
	public function profileIn( $functionname ) {
		global $wgDebugFunctionEntry;
		if ( $wgDebugFunctionEntry ) {
			$this->debug( str_repeat( ' ', count( $this->mWorkStack ) ) . 'Entering ' . $functionname . "\n" );
		}

		$this->mWorkStack[] = array( $functionname, count( $this->mWorkStack ), $this->getTime(), memory_get_usage() );
	}

	/**
	 * Called by wfProfieOut()
	 *
	 * @param $functionname String
	 */
	public function profileOut( $functionname ) {
		global $wgDebugFunctionEntry;
		$memory = memory_get_usage();
		$time = $this->getTime();

		if ( $wgDebugFunctionEntry ) {
			$this->debug( str_repeat( ' ', count( $this->mWorkStack ) - 1 ) . 'Exiting ' . $functionname . "\n" );
		}

		$bit = array_pop( $this->mWorkStack );

		if ( !$bit ) {
			$this->debug( "Profiling error, !\$bit: $functionname\n" );
		} else {
			if ( $functionname == 'close' ) {
				$message = "Profile section ended by close(): {$bit[0]}";
				$this->debug( "$message\n" );
				$this->mStack[] = array( $message, 0, 0.0, 0, 0.0, 0 );
			} elseif ( $bit[0] != $functionname ) {
				$message = "Profiling error: in({$bit[0]}), out($functionname)";
				$this->debug( "$message\n" );
				$this->mStack[] = array( $message, 0, 0.0, 0, 0.0, 0 );
			}
			$bit[] = $time;
			$bit[] = $memory;
			$this->mStack[] = $bit;
			$this->updateTrxProfiling( $functionname, $time );
		}
	}

	/**
	 * Close opened profiling sections
	 */
	public function close() {
		while ( count( $this->mWorkStack ) ) {
			$this->profileOut( 'close' );
		}
	}

	/**
	 * Mark a DB as in a transaction with one or more writes pending
	 *
	 * Note that there can be multiple connections to a single DB.
	 *
	 * @param string $server DB server
	 * @param string $db DB name
	 */
	public function transactionWritingIn( $server, $db ) {
		$name = "{$server} ({$db})";
		if ( isset( $this->mDBTrxHoldingLocks[$name] ) ) {
			++$this->mDBTrxHoldingLocks[$name]['refs'];
		} else {
			$this->mDBTrxHoldingLocks[$name] = array( 'refs' => 1, 'start' => microtime( true ) );
			$this->mDBTrxMethodTimes[$name] = array();
		}
	}

	/**
	 * Register the name and time of a method for slow DB trx detection
	 *
	 * @param string $method Function name
	 * @param float $realtime Wal time ellapsed
	 */
	protected function updateTrxProfiling( $method, $realtime ) {
		if ( !$this->mDBTrxHoldingLocks ) {
			return; // short-circuit
		// @TODO: hardcoded check is a tad janky (what about FOR UPDATE?)
		} elseif ( !preg_match( '/^query-m: (?!SELECT)/', $method )
			&& $realtime < $this->mDBLockThreshold )
		{
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
	 */
	public function transactionWritingOut( $server, $db ) {
		$name = "{$server} ({$db})";
		if ( --$this->mDBTrxHoldingLocks[$name]['refs'] <= 0 ) {
			$slow = false;
			foreach ( $this->mDBTrxMethodTimes[$name] as $info ) {
				list( $method, $realtime ) = $info;
				if ( $realtime >= $this->mDBLockThreshold ) {
					$slow = true;
					break;
				}
			}
			if ( $slow ) {
				$dbs = implode( ', ', array_keys( $this->mDBTrxHoldingLocks ) );
				$msg = "Sub-optimal transaction on DB(s) {$dbs}:\n";
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

	/**
	 * Mark this call as templated or not
	 *
	 * @param $t Boolean
	 */
	function setTemplated( $t ) {
		$this->mTemplated = $t;
	}

	/**
	 * Returns a profiling output to be stored in debug file
	 *
	 * @return String
	 */
	public function getOutput() {
		global $wgDebugFunctionEntry, $wgProfileCallTree;
		$wgDebugFunctionEntry = false;

		if ( !count( $this->mStack ) && !count( $this->mCollated ) ) {
			return "No profiling output\n";
		}

		if ( $wgProfileCallTree ) {
			return $this->getCallTree();
		} else {
			return $this->getFunctionReport();
		}
	}

	/**
	 * Returns a tree of function call instead of a list of functions
	 * @return string
	 */
	function getCallTree() {
		return implode( '', array_map( array( &$this, 'getCallTreeLine' ), $this->remapCallTree( $this->mStack ) ) );
	}

	/**
	 * Recursive function the format the current profiling array into a tree
	 *
	 * @param array $stack profiling array
	 * @return array
	 */
	function remapCallTree( $stack ) {
		if ( count( $stack ) < 2 ) {
			return $stack;
		}
		$outputs = array();
		for ( $max = count( $stack ) - 1; $max > 0; ) {
			/* Find all items under this entry */
			$level = $stack[$max][1];
			$working = array();
			for ( $i = $max -1; $i >= 0; $i-- ) {
				if ( $stack[$i][1] > $level ) {
					$working[] = $stack[$i];
				} else {
					break;
				}
			}
			$working = $this->remapCallTree( array_reverse( $working ) );
			$output = array();
			foreach ( $working as $item ) {
				array_push( $output, $item );
			}
			array_unshift( $output, $stack[$max] );
			$max = $i;

			array_unshift( $outputs, $output );
		}
		$final = array();
		foreach ( $outputs as $output ) {
			foreach ( $output as $item ) {
				$final[] = $item;
			}
		}
		return $final;
	}

	/**
	 * Callback to get a formatted line for the call tree
	 * @return string
	 */
	function getCallTreeLine( $entry ) {
		list( $fname, $level, $start, /* $x */, $end ) = $entry;
		$delta = $end - $start;
		$space = str_repeat( ' ', $level );
		# The ugly double sprintf is to work around a PHP bug,
		# which has been fixed in recent releases.
		return sprintf( "%10s %s %s\n", trim( sprintf( "%7.3f", $delta * 1000.0 ) ), $space, $fname );
	}

	/**
	 * Get the initial time of the request, based either on $wgRequestTime or
	 * $wgRUstart. Will return null if not able to find data.
	 *
	 * @param string|false $metric metric to use, with the following possibilities:
	 *   - user: User CPU time (without system calls)
	 *   - cpu: Total CPU time (user and system calls)
	 *   - wall (or any other string): elapsed time
	 *   - false (default): will fall back to default metric
	 * @return float|null
	 */
	function getTime( $metric = false ) {
		if ( $metric === false ) {
			$metric = $this->mTimeMetric;
		}

		if ( $metric === 'cpu' || $this->mTimeMetric === 'user' ) {
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
	 * @param string|false $metric metric to use, with the following possibilities:
	 *   - user: User CPU time (without system calls)
	 *   - cpu: Total CPU time (user and system calls)
	 *   - wall (or any other string): elapsed time
	 *   - false (default): will fall back to default metric
	 * @return float|null
	 */
	protected function getInitialTime( $metric = false ) {
		global $wgRequestTime, $wgRUstart;

		if ( $metric === false ) {
			$metric = $this->mTimeMetric;
		}

		if ( $metric === 'cpu' || $this->mTimeMetric === 'user' ) {
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

	protected function collateData() {
		if ( $this->mCollateDone ) {
			return;
		}
		$this->mCollateDone = true;

		$this->close();

		$this->mCollated = array();
		$this->mCalls = array();
		$this->mMemory = array();

		# Estimate profiling overhead
		$profileCount = count( $this->mStack );
		self::calculateOverhead( $profileCount );

		# First, subtract the overhead!
		$overheadTotal = $overheadMemory = $overheadInternal = array();
		foreach ( $this->mStack as $entry ) {
			$fname = $entry[0];
			$start = $entry[2];
			$end = $entry[4];
			$elapsed = $end - $start;
			$memory = $entry[5] - $entry[3];

			if ( $fname == '-overhead-total' ) {
				$overheadTotal[] = $elapsed;
				$overheadMemory[] = $memory;
			} elseif ( $fname == '-overhead-internal' ) {
				$overheadInternal[] = $elapsed;
			}
		}
		$overheadTotal = $overheadTotal ? array_sum( $overheadTotal ) / count( $overheadInternal ) : 0;
		$overheadMemory = $overheadMemory ? array_sum( $overheadMemory ) / count( $overheadInternal ) : 0;
		$overheadInternal = $overheadInternal ? array_sum( $overheadInternal ) / count( $overheadInternal ) : 0;

		# Collate
		foreach ( $this->mStack as $index => $entry ) {
			$fname = $entry[0];
			$start = $entry[2];
			$end = $entry[4];
			$elapsed = $end - $start;

			$memory = $entry[5] - $entry[3];
			$subcalls = $this->calltreeCount( $this->mStack, $index );

			if ( !preg_match( '/^-overhead/', $fname ) ) {
				# Adjust for profiling overhead (except special values with elapsed=0
				if ( $elapsed ) {
					$elapsed -= $overheadInternal;
					$elapsed -= ( $subcalls * $overheadTotal );
					$memory -= ( $subcalls * $overheadMemory );
				}
			}

			if ( !array_key_exists( $fname, $this->mCollated ) ) {
				$this->mCollated[$fname] = 0;
				$this->mCalls[$fname] = 0;
				$this->mMemory[$fname] = 0;
				$this->mMin[$fname] = 1 << 24;
				$this->mMax[$fname] = 0;
				$this->mOverhead[$fname] = 0;
			}

			$this->mCollated[$fname] += $elapsed;
			$this->mCalls[$fname]++;
			$this->mMemory[$fname] += $memory;
			$this->mMin[$fname] = min( $this->mMin[$fname], $elapsed );
			$this->mMax[$fname] = max( $this->mMax[$fname], $elapsed );
			$this->mOverhead[$fname] += $subcalls;
		}

		$this->mCalls['-overhead-total'] = $profileCount;
		arsort( $this->mCollated, SORT_NUMERIC );
	}

	/**
	 * Returns a list of profiled functions.
	 *
	 * @return string
	 */
	function getFunctionReport() {
		$this->collateData();

		$width = 140;
		$nameWidth = $width - 65;
		$format = "%-{$nameWidth}s %6d %13.3f %13.3f %13.3f%% %9d  (%13.3f -%13.3f) [%d]\n";
		$titleFormat = "%-{$nameWidth}s %6s %13s %13s %13s %9s\n";
		$prof = "\nProfiling data\n";
		$prof .= sprintf( $titleFormat, 'Name', 'Calls', 'Total', 'Each', '%', 'Mem' );

		$total = isset( $this->mCollated['-total'] ) ? $this->mCollated['-total'] : 0;

		foreach ( $this->mCollated as $fname => $elapsed ) {
			$calls = $this->mCalls[$fname];
			$percent = $total ? 100. * $elapsed / $total : 0;
			$memory = $this->mMemory[$fname];
			$prof .= sprintf( $format,
				substr( $fname, 0, $nameWidth ),
				$calls,
				(float)( $elapsed * 1000 ),
				(float)( $elapsed * 1000 ) / $calls,
				$percent,
				$memory,
				( $this->mMin[$fname] * 1000.0 ),
				( $this->mMax[$fname] * 1000.0 ),
				$this->mOverhead[$fname]
			);
		}
		$prof .= "\nTotal: $total\n\n";

		return $prof;
	}

	/**
	 * Dummy calls to wfProfileIn/wfProfileOut to calculate its overhead
	 */
	protected static function calculateOverhead( $profileCount ) {
		wfProfileIn( '-overhead-total' );
		for ( $i = 0; $i < $profileCount; $i++ ) {
			wfProfileIn( '-overhead-internal' );
			wfProfileOut( '-overhead-internal' );
		}
		wfProfileOut( '-overhead-total' );
	}

	/**
	 * Counts the number of profiled function calls sitting under
	 * the given point in the call graph. Not the most efficient algo.
	 *
	 * @param $stack Array:
	 * @param $start Integer:
	 * @return Integer
	 * @private
	 */
	function calltreeCount( $stack, $start ) {
		$level = $stack[$start][1];
		$count = 0;
		for ( $i = $start -1; $i >= 0 && $stack[$i][1] > $level; $i-- ) {
			$count ++;
		}
		return $count;
	}

	/**
	 * Log the whole profiling data into the database.
	 */
	public function logData() {
		global $wgProfilePerHost, $wgProfileToDatabase;

		# Do not log anything if database is readonly (bug 5375)
		if ( wfReadOnly() || !$wgProfileToDatabase ) {
			return;
		}

		$dbw = wfGetDB( DB_MASTER );
		if ( !is_object( $dbw ) ) {
			return;
		}

		if ( $wgProfilePerHost ) {
			$pfhost = wfHostname();
		} else {
			$pfhost = '';
		}

		try {
			$this->collateData();

			foreach ( $this->mCollated as $name => $elapsed ) {
				$eventCount = $this->mCalls[$name];
				$timeSum = (float)( $elapsed * 1000 );
				$memorySum = (float)$this->mMemory[$name];
				$name = substr( $name, 0, 255 );

				// Kludge
				$timeSum = $timeSum >= 0 ? $timeSum : 0;
				$memorySum = $memorySum >= 0 ? $memorySum : 0;

				$dbw->update( 'profiling',
					array(
						"pf_count=pf_count+{$eventCount}",
						"pf_time=pf_time+{$timeSum}",
						"pf_memory=pf_memory+{$memorySum}",
					),
					array(
						'pf_name' => $name,
						'pf_server' => $pfhost,
					),
					__METHOD__ );

				$rc = $dbw->affectedRows();
				if ( $rc == 0 ) {
					$dbw->insert( 'profiling', array( 'pf_name' => $name, 'pf_count' => $eventCount,
						'pf_time' => $timeSum, 'pf_memory' => $memorySum, 'pf_server' => $pfhost ),
						__METHOD__, array( 'IGNORE' ) );
				}
				// When we upgrade to mysql 4.1, the insert+update
				// can be merged into just a insert with this construct added:
				//     "ON DUPLICATE KEY UPDATE ".
				//     "pf_count=pf_count + VALUES(pf_count), ".
				//     "pf_time=pf_time + VALUES(pf_time)";
			}
		} catch ( DBError $e ) {}
	}

	/**
	 * Get the function name of the current profiling section
	 * @return
	 */
	function getCurrentSection() {
		$elt = end( $this->mWorkStack );
		return $elt[0];
	}

	/**
	 * Add an entry in the debug log file
	 *
	 * @param string $s to output
	 */
	function debug( $s ) {
		if ( function_exists( 'wfDebug' ) ) {
			wfDebug( $s );
		}
	}

	/**
	 * Get the content type sent out to the client.
	 * Used for profilers that output instead of store data.
	 * @return string
	 */
	protected function getContentType() {
		foreach ( headers_list() as $header ) {
			if ( preg_match( '#^content-type: (\w+/\w+);?#i', $header, $m ) ) {
				return $m[1];
			}
		}
		return null;
	}
}
