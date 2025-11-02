<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

/**
 * Arbitrary section name based PHP profiling.
 *
 * This custom profiler can track code execution that doesn't cleanly map to a
 * function call and thus can't be handled by ProfilerXhprof or ProfilerExcimer.
 * For example, parser invocations or DB queries.
 *
 * @since 1.25
 * @ingroup Profiler
 */
class SectionProfiler {
	/** @var array|null Map of (mem,real,cpu) */
	protected $start;
	/** @var array|null Map of (mem,real,cpu) */
	protected $end;
	/** @var array[] List of resolved profile calls with start/end data */
	protected $stack = [];
	/** @var array Queue of open profile calls with start data */
	protected $workStack = [];
	/** @var array[] Map of (function name => aggregate data array) */
	protected $collated = [];
	/** @var bool */
	protected $collateDone = false;

	/** @var array Cache of a standard broken collation entry */
	protected $errorEntry;
	/** @var LoggerInterface */
	protected $logger;

	/**
	 * @param array $params
	 */
	public function __construct( array $params = [] ) {
		$this->errorEntry = $this->getErrorEntry();
		$this->logger = LoggerFactory::getInstance( 'profiler' );
	}

	/**
	 * @param string $section
	 */
	public function scopedProfileIn( $section ): ?SectionProfileCallback {
		$this->profileInInternal( $section );

		return new SectionProfileCallback( $this, $section );
	}

	public function scopedProfileOut( ?SectionProfileCallback &$section ) {
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
	 * @return array[] List of method entries arrays, each having:
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

		if ( is_array( $this->start ) && is_array( $this->end ) ) {
			$totalCpu = max( $this->end['cpu'] - $this->start['cpu'], 0 );
			$totalReal = max( $this->end['real'] - $this->start['real'], 0 );
			$totalMem = max( $this->end['memory'] - $this->start['memory'], 0 );
		} else {
			$totalCpu = 0;
			$totalReal = 0;
			$totalMem = 0;
		}

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
			$this->logger->error( "Profiling error: $functionname" );
			return;
		}
		[ $ofname, /* $ocount */, $ortime, $octime, $omem ] = $item;

		if ( $functionname === 'close' ) {
			$message = "Profile section ended by close(): {$ofname}";
			$this->logger->error( $message );
			$this->collated[$message] = $this->errorEntry;
			$functionname = $ofname;
		} elseif ( $ofname !== $functionname ) {
			$message = "Profiling error: in({$ofname}), out($functionname)";
			$this->logger->error( $message );
			$this->collated[$message] = $this->errorEntry;
		}

		$realTime = $this->getTime( 'wall' );
		$cpuTime = $this->getTime( 'cpu' );
		$memUsage = memory_get_usage();

		$elapsedcpu = $cpuTime - $octime;
		$elapsedreal = $realTime - $ortime;
		$memchange = $memUsage - $omem;
		$this->updateEntry( $functionname, $elapsedcpu, $elapsedreal, $memchange );

		$this->end = [
			'cpu'      => $cpuTime,
			'real'     => $realTime,
			'memory'   => $memUsage
		];
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
			$ru = getrusage( 0 /* RUSAGE_SELF */ );
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
}
