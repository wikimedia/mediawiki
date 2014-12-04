<?php
/**
 * Common implementation class for profiling.
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
 */

/**
 * Standard profiler that tracks real time, cpu time, and memory deltas
 *
 * This supports profile reports, the debug toolbar, and high-contention
 * DB query warnings. This does not persist the profiling data though.
 *
 * @ingroup Profiler
 * @since 1.24
 */
class ProfilerStandard extends Profiler {
	/** @var array List of resolved profile calls with start/end data */
	protected $stack = array();
	/** @var array Queue of open profile calls with start data */
	protected $workStack = array();

	/** @var array Map of (function name => aggregate data array) */
	protected $collated = array();
	/** @var bool */
	protected $collateDone = false;
	/** @var bool Whether to collect the full stack trace or just aggregates */
	protected $collateOnly = true;
	/** @var array Cache of a standard broken collation entry */
	protected $errorEntry;

	/**
	 * @param array $params
	 *   - initTotal : set to false to omit -total/-setup entries (which use request start time)
	 */
	public function __construct( array $params ) {
		parent::__construct( $params );

		if ( !isset( $params['initTotal'] ) || $params['initTotal'] ) {
			$this->addInitialStack();
		}
	}

	/**
	 * Add the inital item in the stack.
	 */
	protected function addInitialStack() {
		$this->errorEntry = $this->getErrorEntry();

		$initialTime = $this->getInitialTime( 'wall' );
		$initialCpu = $this->getInitialTime( 'cpu' );
		if ( $initialTime !== null && $initialCpu !== null ) {
			$this->workStack[] = array( '-total', 0, $initialTime, $initialCpu, 0 );
			if ( $this->collateOnly ) {
				$this->workStack[] = array( '-setup', 1, $initialTime, $initialCpu, 0 );
				$this->profileOut( '-setup' );
			} else {
				$this->stack[] = array( '-setup', 1, $initialTime, $initialCpu, 0,
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
			'periods'  => array(), // not filled if collateOnly
			'overhead' => 0 // not filled if collateOnly
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
		$entry =& $this->collated[$name];
		if ( !is_array( $entry ) ) {
			$entry = $this->getZeroEntry();
			$this->collated[$name] =& $entry;
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
			$this->debug( str_repeat( ' ', count( $this->workStack ) ) .
				'Entering ' . $functionname . "\n" );
		}

		$this->workStack[] = array(
			$functionname,
			count( $this->workStack ),
			$this->getTime( 'time' ),
			$this->getTime( 'cpu' ),
			memory_get_usage()
		);
	}

	/**
	 * Called by wfProfieOut()
	 *
	 * @param string $functionname
	 */
	public function profileOut( $functionname ) {
		global $wgDebugFunctionEntry;

		if ( $wgDebugFunctionEntry ) {
			$this->debug( str_repeat( ' ', count( $this->workStack ) - 1 ) .
				'Exiting ' . $functionname . "\n" );
		}

		$item = array_pop( $this->workStack );
		list( $ofname, /* $ocount */, $ortime, $octime, $omem ) = $item;

		if ( $item === null ) {
			$this->debugGroup( 'profileerror', "Profiling error: $functionname" );
		} else {
			if ( $functionname === 'close' ) {
				if ( $ofname !== '-total' ) {
					$message = "Profile section ended by close(): {$ofname}";
					$this->debugGroup( 'profileerror', $message );
					if ( $this->collateOnly ) {
						$this->collated[$message] = $this->errorEntry;
					} else {
						$this->stack[] = array( $message, 0, 0.0, 0.0, 0, 0.0, 0.0, 0 );
					}
				}
				$functionname = $ofname;
			} elseif ( $ofname !== $functionname ) {
				$message = "Profiling error: in({$ofname}), out($functionname)";
				$this->debugGroup( 'profileerror', $message );
				if ( $this->collateOnly ) {
					$this->collated[$message] = $this->errorEntry;
				} else {
					$this->stack[] = array( $message, 0, 0.0, 0.0, 0, 0.0, 0.0, 0 );
				}
			}
			$realTime = $this->getTime( 'wall' );
			$cpuTime = $this->getTime( 'cpu' );
			if ( $this->collateOnly ) {
				$elapsedcpu = $cpuTime - $octime;
				$elapsedreal = $realTime - $ortime;
				$memchange = memory_get_usage() - $omem;
				$this->updateEntry( $functionname, $elapsedcpu, $elapsedreal, $memchange );
			} else {
				$this->stack[] = array_merge( $item,
					array( $realTime, $cpuTime,	memory_get_usage() ) );
			}
		}
	}

	public function scopedProfileIn( $section ) {
		$this->profileIn( $section );

		$that = $this;
		return new ScopedCallback( function() use ( $that, $section ) {
			$that->profileOut( $section );
		} );
	}

	/**
	 * Close opened profiling sections
	 */
	public function close() {
		while ( count( $this->workStack ) ) {
			$this->profileOut( 'close' );
		}
	}

	/**
	 * Returns a profiling output to be stored in debug file
	 *
	 * @return string
	 */
	public function getOutput() {
		global $wgDebugFunctionEntry, $wgProfileCallTree;

		$wgDebugFunctionEntry = false; // hack

		if ( !count( $this->stack ) && !count( $this->collated ) ) {
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
			array( &$this, 'getCallTreeLine' ), $this->remapCallTree( $this->stack )
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
	 * @param array $entry
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
	 * Return the collated data, collating first if need be
	 * @return array
	 */
	public function getCollatedData() {
		if ( !$this->collateDone ) {
			$this->collateData();
		}
		return $this->collated;
	}

	/**
	 * Populate collated
	 */
	protected function collateData() {
		if ( $this->collateDone ) {
			return;
		}
		$this->collateDone = true;
		$this->close(); // set "-total" entry

		if ( $this->collateOnly ) {
			return; // already collated as methods exited
		}

		$this->collated = array();

		# Estimate profiling overhead
		$profileCount = count( $this->stack );
		self::calculateOverhead( $profileCount );

		# First, subtract the overhead!
		$overheadTotal = $overheadMemory = $overheadInternal = array();
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

		$this->collated['-overhead-total']['count'] = $profileCount;
		arsort( $this->collated, SORT_NUMERIC );
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

		$total = isset( $this->collated['-total'] )
			? $this->collated['-total']['real']
			: 0;

		foreach ( $this->collated as $fname => $data ) {
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

	public function getFunctionStats() {
		// This method is called before shutdown in the footer method on Skins.
		// If some outer methods have not yet called wfProfileOut(), work around
		// that by clearing anything in the work stack to just the "-total" entry.
		// Collate after doing this so the results do not include profile errors.
		if ( count( $this->workStack ) > 1 ) {
			$oldWorkStack = $this->workStack;
			$this->workStack = array( $this->workStack[0] ); // just the "-total" one
		} else {
			$oldWorkStack = null;
		}
		$this->collateData();
		// If this trick is used, then the old work stack is swapped back afterwards
		// and collateDone is reset to false. This means that logData() will still
		// make use of all the method data since the missing wfProfileOut() calls
		// should be made by the time it is called.
		if ( $oldWorkStack ) {
			$this->workStack = $oldWorkStack;
			$this->collateDone = false;
		}

		$totalCpu = isset( $this->collated['-total'] )
			? $this->collated['-total']['cpu']
			: 0;
		$totalReal = isset( $this->collated['-total'] )
			? $this->collated['-total']['real']
			: 0;
		$totalMem = isset( $this->collated['-total'] )
			? $this->collated['-total']['memory']
			: 0;

		$profile = array();
		foreach ( $this->collated as $fname => $data ) {
			$profile[] = array(
				'name' => $fname,
				'calls' => $data['count'],
				'real' => $data['real'] * 1000,
				'%real' => $totalReal ? 100 * $data['real'] / $totalReal : 0,
				'cpu' => $data['cpu'] * 1000,
				'%cpu' => $totalCpu ? 100 * $data['cpu'] / $totalCpu : 0,
				'memory' => $data['memory'],
				'%memory' => $totalMem ? 100 * $data['memory'] / $totalMem : 0,
				'min' => $data['min_real'] * 1000,
				'max' => $data['max_real'] * 1000
			);
		}

		return $profile;
	}

	/**
	 * Dummy calls to wfProfileIn/wfProfileOut to calculate its overhead
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
