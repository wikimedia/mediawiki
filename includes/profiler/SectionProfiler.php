<?php
/**
 * Arbitrary section name based PHP profiling.
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
 * Custom PHP profiler for parser/DB type section names that xhprof/xdebug can't handle
 *
 * @since 1.25
 */
class SectionProfiler {
	/** @var array Map of (mem,real,cpu) */
	protected $start;
	/** @var array Map of (mem,real,cpu) */
	protected $end;
	/** @var array List of resolved profile calls with start/end data */
	protected $stack = [];
	/** @var array Queue of open profile calls with start data */
	protected $workStack = [];

	/** @var array Map of (function name => aggregate data array) */
	protected $collated = [];
	/** @var bool */
	protected $collateDone = false;

	/** @var bool Whether to collect the full stack trace or just aggregates */
	protected $collateOnly = true;
	/** @var array Cache of a standard broken collation entry */
	protected $errorEntry;
	/** @var callable Cache of a profile out callback */
	protected $profileOutCallback;

	/**
	 * @param array $params
	 */
	public function __construct( array $params = [] ) {
		$this->errorEntry = $this->getErrorEntry();
		$this->collateOnly = empty( $params['trace'] );
		$this->profileOutCallback = function ( $profiler, $section ) {
			$profiler->profileOutInternal( $section );
		};
	}

	/**
	 * @param string $section
	 * @return ScopedCallback
	 */
	public function scopedProfileIn( $section ) {
		$this->profileInInternal( $section );

		return new SectionProfileCallback( $this, $section );
	}

	/**
	 * @param ScopedCallback $section
	 */
	public function scopedProfileOut( ScopedCallback &$section ) {
		$section = null;
	}

	/**
	 * Get the aggregated inclusive profiling data for each method
	 *
	 * The percent time for each time is based on the current "total" time
	 * used is based on all methods so far. This method can therefore be
	 * called several times in between several profiling calls without the
	 * delays in usage of the profiler skewing the results. A "-total" entry
	 * is always included in the results.
	 *
	 * @return array List of method entries arrays, each having:
	 *   - name    : method name
	 *   - calls   : the number of invoking calls
	 *   - real    : real time elapsed (ms)
	 *   - %real   : percent real time
	 *   - cpu     : real time elapsed (ms)
	 *   - %cpu    : percent real time
	 *   - memory  : memory used (bytes)
	 *   - %memory : percent memory used
	 *   - min_real : min real time in a call (ms)
	 *   - max_real : max real time in a call (ms)
	 */
	public function getFunctionStats() {
		$this->collateData();

		$totalCpu = max( $this->end['cpu'] - $this->start['cpu'], 0 );
		$totalReal = max( $this->end['real'] - $this->start['real'], 0 );
		$totalMem = max( $this->end['memory'] - $this->start['memory'], 0 );

		$profile = [];
		foreach ( $this->collated as $fname => $data ) {
			$profile[] = [
				'name' => $fname,
				'calls' => $data['count'],
				'real' => $data['real'] * 1000,
				'%real' => $totalReal ? 100 * $data['real'] / $totalReal : 0,
				'cpu' => $data['cpu'] * 1000,
				'%cpu' => $totalCpu ? 100 * $data['cpu'] / $totalCpu : 0,
				'memory' => $data['memory'],
				'%memory' => $totalMem ? 100 * $data['memory'] / $totalMem : 0,
				'min_real' => 1000 * $data['min_real'],
				'max_real' => 1000 * $data['max_real']
			];
		}

		$profile[] = [
			'name' => '-total',
			'calls' => 1,
			'real' => 1000 * $totalReal,
			'%real' => 100,
			'cpu' => 1000 * $totalCpu,
			'%cpu' => 100,
			'memory' => $totalMem,
			'%memory' => 100,
			'min_real' => 1000 * $totalReal,
			'max_real' => 1000 * $totalReal
		];

		return $profile;
	}

	/**
	 * Clear all of the profiling data for another run
	 */
	public function reset() {
		$this->start = null;
		$this->end = null;
		$this->stack = [];
		$this->workStack = [];
		$this->collated = [];
		$this->collateDone = false;
	}

	/**
	 * @return array Initial collation entry
	 */
	protected function getZeroEntry() {
		return [
			'cpu'      => 0.0,
			'real'     => 0.0,
			'memory'   => 0,
			'count'    => 0,
			'min_real' => 0.0,
			'max_real' => 0.0
		];
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
	 */
	protected function updateEntry( $name, $elapsedCpu, $elapsedReal, $memChange ) {
		$entry =& $this->collated[$name];
		if ( !is_array( $entry ) ) {
			$entry = $this->getZeroEntry();
			$this->collated[$name] =& $entry;
		}
		$entry['cpu'] += $elapsedCpu;
		$entry['real'] += $elapsedReal;
		$entry['memory'] += $memChange > 0 ? $memChange : 0;
		$entry['count']++;
		$entry['min_real'] = min( $entry['min_real'], $elapsedReal );
		$entry['max_real'] = max( $entry['max_real'], $elapsedReal );
	}

	/**
	 * This method should not be called outside SectionProfiler
	 *
	 * @param string $functionname
	 */
	public function profileInInternal( $functionname ) {
		// Once the data is collated for reports, any future calls
		// should clear the collation cache so the next report will
		// reflect them. This matters when trace mode is used.
		$this->collateDone = false;

		$cpu = $this->getTime( 'cpu' );
		$real = $this->getTime( 'wall' );
		$memory = memory_get_usage();

		if ( $this->start === null ) {
			$this->start = [ 'cpu' => $cpu, 'real' => $real, 'memory' => $memory ];
		}

		$this->workStack[] = [
			$functionname,
			count( $this->workStack ),
			$real,
			$cpu,
			$memory
		];
	}

	/**
	 * This method should not be called outside SectionProfiler
	 *
	 * @param string $functionname
	 */
	public function profileOutInternal( $functionname ) {
		$item = array_pop( $this->workStack );
		if ( $item === null ) {
			$this->debugGroup( 'profileerror', "Profiling error: $functionname" );
			return;
		}
		list( $ofname, /* $ocount */, $ortime, $octime, $omem ) = $item;

		if ( $functionname === 'close' ) {
			$message = "Profile section ended by close(): {$ofname}";
			$this->debugGroup( 'profileerror', $message );
			if ( $this->collateOnly ) {
				$this->collated[$message] = $this->errorEntry;
			} else {
				$this->stack[] = [ $message, 0, 0.0, 0.0, 0, 0.0, 0.0, 0 ];
			}
			$functionname = $ofname;
		} elseif ( $ofname !== $functionname ) {
			$message = "Profiling error: in({$ofname}), out($functionname)";
			$this->debugGroup( 'profileerror', $message );
			if ( $this->collateOnly ) {
				$this->collated[$message] = $this->errorEntry;
			} else {
				$this->stack[] = [ $message, 0, 0.0, 0.0, 0, 0.0, 0.0, 0 ];
			}
		}

		$realTime = $this->getTime( 'wall' );
		$cpuTime = $this->getTime( 'cpu' );
		$memUsage = memory_get_usage();

		if ( $this->collateOnly ) {
			$elapsedcpu = $cpuTime - $octime;
			$elapsedreal = $realTime - $ortime;
			$memchange = $memUsage - $omem;
			$this->updateEntry( $functionname, $elapsedcpu, $elapsedreal, $memchange );
		} else {
			$this->stack[] = array_merge( $item, [ $realTime, $cpuTime, $memUsage ] );
		}

		$this->end = [
			'cpu'      => $cpuTime,
			'real'     => $realTime,
			'memory'   => $memUsage
		];
	}

	/**
	 * Returns a tree of function calls with their real times
	 * @return string
	 * @throws Exception
	 */
	public function getCallTreeReport() {
		if ( $this->collateOnly ) {
			throw new Exception( "Tree is only available for trace profiling." );
		}
		return implode( '', array_map(
			[ $this, 'getCallTreeLine' ], $this->remapCallTree( $this->stack )
		) );
	}

	/**
	 * Recursive function the format the current profiling array into a tree
	 *
	 * @param array $stack Profiling array
	 * @return array
	 */
	protected function remapCallTree( array $stack ) {
		if ( count( $stack ) < 2 ) {
			return $stack;
		}
		$outputs = [];
		for ( $max = count( $stack ) - 1; $max > 0; ) {
			/* Find all items under this entry */
			$level = $stack[$max][1];
			$working = [];
			for ( $i = $max -1; $i >= 0; $i-- ) {
				if ( $stack[$i][1] > $level ) {
					$working[] = $stack[$i];
				} else {
					break;
				}
			}
			$working = $this->remapCallTree( array_reverse( $working ) );
			$output = [];
			foreach ( $working as $item ) {
				array_push( $output, $item );
			}
			array_unshift( $output, $stack[$max] );
			$max = $i;

			array_unshift( $outputs, $output );
		}
		$final = [];
		foreach ( $outputs as $output ) {
			foreach ( $output as $item ) {
				$final[] = $item;
			}
		}
		return $final;
	}

	/**
	 * Callback to get a formatted line for the call tree
	 * @param array $entry
	 * @return string
	 */
	protected function getCallTreeLine( $entry ) {
		// $entry has (name, level, stime, scpu, smem, etime, ecpu, emem)
		list( $fname, $level, $startreal, , , $endreal ) = $entry;
		$delta = $endreal - $startreal;
		$space = str_repeat( ' ', $level );
		# The ugly double sprintf is to work around a PHP bug,
		# which has been fixed in recent releases.
		return sprintf( "%10s %s %s\n",
			trim( sprintf( "%7.3f", $delta * 1000.0 ) ), $space, $fname );
	}

	/**
	 * Populate collated data
	 */
	protected function collateData() {
		if ( $this->collateDone ) {
			return;
		}
		$this->collateDone = true;
		// Close opened profiling sections
		while ( count( $this->workStack ) ) {
			$this->profileOutInternal( 'close' );
		}

		if ( $this->collateOnly ) {
			return; // already collated as methods exited
		}

		$this->collated = [];

		# Estimate profiling overhead
		$oldEnd = $this->end;
		$profileCount = count( $this->stack );
		$this->calculateOverhead( $profileCount );

		# First, subtract the overhead!
		$overheadTotal = $overheadMemory = $overheadInternal = [];
		foreach ( $this->stack as $entry ) {
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
		foreach ( $this->stack as $index => $entry ) {
			// $entry is (name,pos,rtime0,cputime0,mem0,rtime1,cputime1,mem1)
			$fname = $entry[0];
			$elapsedCpu = $entry[6] - $entry[3];
			$elapsedReal = $entry[5] - $entry[2];
			$memchange = $entry[7] - $entry[4];
			$subcalls = $this->calltreeCount( $this->stack, $index );

			if ( substr( $fname, 0, 9 ) !== '-overhead' ) {
				# Adjust for profiling overhead (except special values with elapsed=0)
				if ( $elapsed ) {
					$elapsed -= $overheadInternal;
					$elapsed -= ( $subcalls * $overheadTotal );
					$memchange -= ( $subcalls * $overheadMemory );
				}
			}

			$this->updateEntry( $fname, $elapsedCpu, $elapsedReal, $memchange );
		}

		$this->collated['-overhead-total']['count'] = $profileCount;
		arsort( $this->collated, SORT_NUMERIC );

		// Unclobber the end info map (the overhead checking alters it)
		$this->end = $oldEnd;
	}

	/**
	 * Dummy calls to calculate profiling overhead
	 *
	 * @param int $profileCount
	 */
	protected function calculateOverhead( $profileCount ) {
		$this->profileInInternal( '-overhead-total' );
		for ( $i = 0; $i < $profileCount; $i++ ) {
			$this->profileInInternal( '-overhead-internal' );
			$this->profileOutInternal( '-overhead-internal' );
		}
		$this->profileOutInternal( '-overhead-total' );
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
	 * Get the initial time of the request, based on getrusage()
	 *
	 * @param string|bool $metric Metric to use, with the following possibilities:
	 *   - user: User CPU time (without system calls)
	 *   - cpu: Total CPU time (user and system calls)
	 *   - wall (or any other string): elapsed time
	 *   - false (default): will fall back to default metric
	 * @return float
	 */
	protected function getTime( $metric = 'wall' ) {
		if ( $metric === 'cpu' || $metric === 'user' ) {
			$ru = wfGetRusage();
			if ( !$ru ) {
				return 0;
			}
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
}

/**
 * Subclass ScopedCallback to avoid call_user_func_array(), which is slow
 *
 * This class should not be used outside of SectionProfiler
 */
class SectionProfileCallback extends ScopedCallback {
	/** @var SectionProfiler */
	protected $profiler;
	/** @var string */
	protected $section;

	/**
	 * @param SectionProfiler $profiler
	 * @param string $section
	 */
	public function __construct( SectionProfiler $profiler, $section ) {
		parent::__construct( null );
		$this->profiler = $profiler;
		$this->section = $section;
	}

	function __destruct() {
		$this->profiler->profileOutInternal( $this->section );
	}
}
