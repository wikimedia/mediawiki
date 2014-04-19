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
	/** @var array List of resolved profile calls with start/end data */
	protected $mStack = array();
	/** @var array Queue of open profile calls with start data */
	protected $mWorkStack = array();

	/** @var array Map of (function name => aggregate data array) */
	protected $mCollated = array();
	/** @var bool */
	protected $mCollateDone = false;
	/** @var bool */
	protected $mCollateOnly = false;
	/** @var array Cache of a standard broken collation entry */
	protected $mErrorEntry;

	/** @var string wall|cpu|user */
	protected $mTimeMetric = 'wall';
	/** @var string|bool Profiler ID for bucketing data */
	protected $mProfileID = false;
	/** @var bool Whether MediaWiki is in a SkinTemplate output context */
	protected $mTemplated = false;

	/** @var float seconds */
	protected $mDBLockThreshold = 5.0;
	/** @var array DB/server name => (active trx count,timestamp) */
	protected $mDBTrxHoldingLocks = array();
	/** @var array DB/server name => list of (function name, elapsed time) */
	protected $mDBTrxMethodTimes = array();

	/** @var Profiler */
	public static $__instance = null; // do not call this outside Profiler and ProfileSection

	/**
	 * @param array $params
	 */
	public function __construct( array $params ) {
		if ( isset( $params['timeMetric'] ) ) {
			$this->mTimeMetric = $params['timeMetric'];
		}
		if ( isset( $params['profileID'] ) ) {
			$this->mProfileID = $params['profileID'];
		}

		$this->mCollateOnly = $this->collateOnly();

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
	 * @param Profiler $p
	 */
	public static function setInstance( Profiler $p ) {
		self::$__instance = $p;
	}

	/**
	 * Return whether this a stub profiler
	 *
	 * @return bool
	 */
	public function isStub() {
		return false;
	}

	/**
	 * Return whether this profiler stores data
	 *
	 * @see Profiler::logData()
	 * @return bool
	 */
	public function isPersistent() {
		return false;
	}

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
	 * Whether to internally just track aggregates and ignore the full stack trace
	 *
	 * @return bool
	 */
	protected function collateOnly() {
		return false;
	}

	/**
	 * Add the inital item in the stack.
	 */
	protected function addInitialStack() {
		$this->mErrorEntry = $this->getErrorEntry();

		$initialTime = $this->getInitialTime( 'wall' );
		$initialCpu = $this->getInitialTime( 'cpu' );
		if ( $initialTime !== null && $initialCpu !== null ) {
			$this->mWorkStack[] = array( '-total', 0, $initialTime, $initialCpu, 0 );
			if ( $this->mCollateOnly ) {
				$this->mWorkStack[] = array( '-setup', 1, $initialTime, $initialCpu, 0 );
				$this->profileOut( '-setup' );
			} else {
				$this->mStack[] = array( '-setup', 1, $initialTime, $initialCpu, 0,
					$this->getTime( 'wall' ), $this->getTime( 'cpu' ), 0 );
			}
		} else {
			$this->profileIn( '-total' );
		}
	}

	/**
	 * @return array Initial collation entry
	 */
	protected function getZeroEntry() {
		return array(
			'cpu'      => 0.0,
			'cpu_sq'   => 0.0,
			'real'     => 0.0,
			'real_sq'  => 0.0,
			'memory'   => 0,
			'count'    => 0,
			'min_cpu'  => 0.0,
			'max_cpu'  => 0.0,
			'min_real' => 0.0,
			'max_real' => 0.0,
			'periods'  => array(), // not filled if mCollateOnly
			'overhead' => 0 // not filled if mCollateOnly
		);
	}

	/**
	 * @return array Initial collation entry for errors
	 */
	protected function getErrorEntry() {
		$entry = $this->getZeroEntry();
		$entry['count'] = 1;
		return $entry;
	}

	/**
	 * Update the collation entry for a given method name
	 *
	 * @param string $name
	 * @param float $elapsedCpu
	 * @param float $elapsedReal
	 * @param int $memChange
	 * @param int $subcalls
	 * @param array|null $period Map of ('start','end','memory','subcalls')
	 */
	protected function updateEntry(
		$name, $elapsedCpu, $elapsedReal, $memChange, $subcalls = 0, $period = null
	) {
		$entry =& $this->mCollated[$name];
		if ( !is_array( $entry ) ) {
			$entry = $this->getZeroEntry();
			$this->mCollated[$name] =& $entry;
		}
		$entry['cpu'] += $elapsedCpu;
		$entry['cpu_sq'] += $elapsedCpu * $elapsedCpu;
		$entry['real'] += $elapsedReal;
		$entry['real_sq'] += $elapsedReal * $elapsedReal;
		$entry['memory'] += $memChange > 0 ? $memChange : 0;
		$entry['count']++;
		$entry['min_cpu'] = $elapsedCpu < $entry['min_cpu'] ? $elapsedCpu : $entry['min_cpu'];
		$entry['max_cpu'] = $elapsedCpu > $entry['max_cpu'] ? $elapsedCpu : $entry['max_cpu'];
		$entry['min_real'] = $elapsedReal < $entry['min_real'] ? $elapsedReal : $entry['min_real'];
		$entry['max_real'] = $elapsedReal > $entry['max_real'] ? $elapsedReal : $entry['max_real'];
		// Apply optional fields
		$entry['overhead'] += $subcalls;
		if ( $period ) {
			$entry['periods'][] = $period;
		}
	}

	/**
	 * Called by wfProfieIn()
	 *
	 * @param string $functionname
	 */
	public function profileIn( $functionname ) {
		global $wgDebugFunctionEntry;

		if ( $wgDebugFunctionEntry ) {
			$this->debug( str_repeat( ' ', count( $this->mWorkStack ) ) .
				'Entering ' . $functionname . "\n" );
		}

		$this->mWorkStack[] = array(
			$functionname,
			count( $this->mWorkStack ),
			$this->getTime( 'time' ),
			$this->getTime( 'cpu' ),
			memory_get_usage()
		);
	}

	/**
	 * Called by wfProfieOut()
	 *
	 * @param  string $functionname
	 */
	public function profileOut( $functionname ) {
		global $wgDebugFunctionEntry;

		if ( $wgDebugFunctionEntry ) {
			$this->debug( str_repeat( ' ', count( $this->mWorkStack ) - 1 ) .
				'Exiting ' . $functionname . "\n" );
		}

		$item = array_pop( $this->mWorkStack );
		list( $ofname, /* $ocount */, $ortime, $octime, $omem ) = $item;

		if ( $item === null ) {
			$this->debugGroup( 'profileerror', "Profiling error: $functionname" );
		} else {
			if ( $functionname === 'close' ) {
				if ( $ofname !== '-total' ) {
					$message = "Profile section ended by close(): {$ofname}";
					$this->debugGroup( 'profileerror', $message );
					if ( $this->mCollateOnly ) {
						$this->mCollated[$message] = $this->mErrorEntry;
					} else {
						$this->mStack[] = array( $message, 0, 0.0, 0.0, 0, 0.0, 0.0, 0 );
					}
				}
				$functionname = $ofname;
			} elseif ( $ofname !== $functionname ) {
				$message = "Profiling error: in({$ofname}), out($functionname)";
				$this->debugGroup( 'profileerror', $message );
				if ( $this->mCollateOnly ) {
					$this->mCollated[$message] = $this->mErrorEntry;
				} else {
					$this->mStack[] = array( $message, 0, 0.0, 0.0, 0, 0.0, 0.0, 0 );
				}
			}
			$realTime = $this->getTime( 'wall' );
			$cpuTime = $this->getTime( 'cpu' );
			if ( $this->mCollateOnly ) {
				$elapsedcpu = $cpuTime - $octime;
				$elapsedreal = $realTime - $ortime;
				$memchange = memory_get_usage() - $omem;
				$this->updateEntry( $functionname, $elapsedcpu, $elapsedreal, $memchange );
			} else {
				$this->mStack[] = array_merge( $item,
					array( $realTime, $cpuTime,	memory_get_usage() ) );
			}
			$this->updateTrxProfiling( $functionname, $realTime - $ortime );
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
	 * Log the data to some store or even the page output
	 */
	public function logData() {
		/* Implement in subclasses */
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
				$this->debugGroup( 'DBPerformance', $msg );
			}
			unset( $this->mDBTrxHoldingLocks[$name] );
			unset( $this->mDBTrxMethodTimes[$name] );
		}
	}

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
	public function getOutput() {
		global $wgDebugFunctionEntry, $wgProfileCallTree;

		$wgDebugFunctionEntry = false; // hack

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
	protected function getCallTree() {
		return implode( '', array_map(
			array( &$this, 'getCallTreeLine' ), $this->remapCallTree( $this->mStack )
		) );
	}

	/**
	 * Recursive function the format the current profiling array into a tree
	 *
	 * @param array $stack profiling array
	 * @return array
	 */
	protected function remapCallTree( array $stack ) {
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
	protected function getCallTreeLine( $entry ) {
		list( $fname, $level, $startreal, , , $endreal ) = $entry;
		$delta = $endreal - $startreal;
		$space = str_repeat( ' ', $level );
		# The ugly double sprintf is to work around a PHP bug,
		# which has been fixed in recent releases.
		return sprintf( "%10s %s %s\n",
			trim( sprintf( "%7.3f", $delta * 1000.0 ) ), $space, $fname );
	}

	/**
	 * Get the initial time of the request, based either on $wgRequestTime or
	 * $wgRUstart. Will return null if not able to find data.
	 *
	 * @param string|false $metric Metric to use, with the following possibilities:
	 *   - user: User CPU time (without system calls)
	 *   - cpu: Total CPU time (user and system calls)
	 *   - wall (or any other string): elapsed time
	 *   - false (default): will fall back to default metric
	 * @return float|null
	 */
	protected function getTime( $metric = false ) {
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
	 * @param string|false $metric Metric to use, with the following possibilities:
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

	/**
	 * Populate mCollated
	 */
	protected function collateData() {
		if ( $this->mCollateDone ) {
			return;
		}
		$this->mCollateDone = true;
		$this->close(); // set "-total" entry

		if ( $this->mCollateOnly ) {
			return; // already collated as methods exited
		}

		$this->mCollated = array();

		# Estimate profiling overhead
		$profileCount = count( $this->mStack );
		self::calculateOverhead( $profileCount );

		# First, subtract the overhead!
		$overheadTotal = $overheadMemory = $overheadInternal = array();
		foreach ( $this->mStack as $entry ) {
			// $entry is (name,pos,rtime0,cputime0,mem0,rtime1,cputime1,mem1)
			$fname = $entry[0];
			$elapsed = $entry[5] - $entry[2];
			$memchange = $entry[7] - $entry[4];

			if ( $fname === '-overhead-total' ) {
				$overheadTotal[] = $elapsed;
				$overheadMemory[] = max( 0, $memchange );
			} elseif ( $fname === '-overhead-internal' ) {
				$overheadInternal[] = $elapsed;
			}
		}
		$overheadTotal = $overheadTotal ?
			array_sum( $overheadTotal ) / count( $overheadInternal ) : 0;
		$overheadMemory = $overheadMemory ?
			array_sum( $overheadMemory ) / count( $overheadInternal ) : 0;
		$overheadInternal = $overheadInternal ?
			array_sum( $overheadInternal ) / count( $overheadInternal ) : 0;

		# Collate
		foreach ( $this->mStack as $index => $entry ) {
			// $entry is (name,pos,rtime0,cputime0,mem0,rtime1,cputime1,mem1)
			$fname = $entry[0];
			$elapsedCpu = $entry[6] - $entry[3];
			$elapsedReal = $entry[5] - $entry[2];
			$memchange = $entry[7] - $entry[4];
			$subcalls = $this->calltreeCount( $this->mStack, $index );

			if ( substr( $fname, 0, 9 ) !== '-overhead' ) {
				# Adjust for profiling overhead (except special values with elapsed=0
				if ( $elapsed ) {
					$elapsed -= $overheadInternal;
					$elapsed -= ( $subcalls * $overheadTotal );
					$memchange -= ( $subcalls * $overheadMemory );
				}
			}

			$period = array( 'start' => $entry[2], 'end' => $entry[5],
				'memory' => $memchange, 'subcalls' => $subcalls );
			$this->updateEntry( $fname, $elapsedCpu, $elapsedReal, $memchange, $subcalls, $period );
		}

		$this->mCollated['-overhead-total']['count'] = $profileCount;
		arsort( $this->mCollated, SORT_NUMERIC );
	}

	/**
	 * Returns a list of profiled functions.
	 *
	 * @return string
	 */
	protected function getFunctionReport() {
		$this->collateData();

		$width = 140;
		$nameWidth = $width - 65;
		$format = "%-{$nameWidth}s %6d %13.3f %13.3f %13.3f%% %9d  (%13.3f -%13.3f) [%d]\n";
		$titleFormat = "%-{$nameWidth}s %6s %13s %13s %13s %9s\n";
		$prof = "\nProfiling data\n";
		$prof .= sprintf( $titleFormat, 'Name', 'Calls', 'Total', 'Each', '%', 'Mem' );

		$total = isset( $this->mCollated['-total'] )
			? $this->mCollated['-total']['real']
			: 0;

		foreach ( $this->mCollated as $fname => $data ) {
			$calls = $data['count'];
			$percent = $total ? 100 * $data['real'] / $total : 0;
			$memory = $data['memory'];
			$prof .= sprintf( $format,
				substr( $fname, 0, $nameWidth ),
				$calls,
				(float)( $data['real'] * 1000 ),
				(float)( $data['real'] * 1000 ) / $calls,
				$percent,
				$memory,
				( $data['min_real'] * 1000.0 ),
				( $data['max_real'] * 1000.0 ),
				$data['overhead']
			);
		}
		$prof .= "\nTotal: $total\n\n";

		return $prof;
	}

	/**
	 * @return array
	 */
	public function getRawData() {
		// This method is called before shutdown in the footer method on Skins.
		// If some outer methods have not yet called wfProfileOut(), work around
		// that by clearing anything in the work stack to just the "-total" entry.
		// Collate after doing this so the results do not include profile errors.
		if ( count( $this->mWorkStack ) > 1 ) {
			$oldWorkStack = $this->mWorkStack;
			$this->mWorkStack = array( $this->mWorkStack[0] ); // just the "-total" one
		} else {
			$oldWorkStack = null;
		}
		$this->collateData();
		// If this trick is used, then the old work stack is swapped back afterwards
		// and mCollateDone is reset to false. This means that logData() will still
		// make use of all the method data since the missing wfProfileOut() calls
		// should be made by the time it is called.
		if ( $oldWorkStack ) {
			$this->mWorkStack = $oldWorkStack;
			$this->mCollateDone = false;
		}

		$total = isset( $this->mCollated['-total'] )
			? $this->mCollated['-total']['real']
			: 0;

		$profile = array();
		foreach ( $this->mCollated as $fname => $data ) {
			$periods = array();
			foreach ( $data['periods'] as $period ) {
				$period['start'] *= 1000;
				$period['end'] *= 1000;
				$periods[] = $period;
			}
			$profile[] = array(
				'name' => $fname,
				'calls' => $data['count'],
				'elapsed' => $data['real'] * 1000,
				'percent' => $total ? 100 * $data['real'] / $total : 0,
				'memory' => $data['memory'],
				'min' => $data['min_real'] * 1000,
				'max' => $data['max_real'] * 1000,
				'overhead' => $data['overhead'],
				'periods' => $periods
			);
		}

		return $profile;
	}

	/**
	 * Dummy calls to wfProfileIn/wfProfileOut to calculate its overhead
	 *
	 * @param int $profileCount
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
	 * @param array $stack
	 * @param int $start
	 * @return int
	 */
	protected function calltreeCount( $stack, $start ) {
		$level = $stack[$start][1];
		$count = 0;
		for ( $i = $start -1; $i >= 0 && $stack[$i][1] > $level; $i-- ) {
			$count ++;
		}
		return $count;
	}

	/**
	 * Add an entry in the debug log file
	 *
	 * @param string $s String to output
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
	 * @param string $s String to output
	 */
	protected function debugGroup( $group, $s ) {
		if ( function_exists( 'wfDebugLog' ) ) {
			wfDebugLog( $group, $s );
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
