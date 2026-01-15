<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * Profiler that only tracks explicit profiling sections
 *
 * @ingroup Profiler
 * @since 1.25
 * @see $wgProfiler
 */
class ProfilerSectionOnly extends Profiler {
	/** @var SectionProfiler */
	protected $sprofiler;

	public function __construct( array $params = [] ) {
		parent::__construct( $params );
		$this->sprofiler = new SectionProfiler();
	}

	/** @inheritDoc */
	public function scopedProfileIn( $section ): ?SectionProfileCallback {
		return $this->sprofiler->scopedProfileIn( $section );
	}

	public function close() {
	}

	/** @inheritDoc */
	public function getFunctionStats() {
		return $this->sprofiler->getFunctionStats();
	}

	/** @inheritDoc */
	public function getOutput() {
		return $this->getFunctionReport();
	}

	/**
	 * Get a report of profiled functions sorted by inclusive wall clock time
	 * in descending order.
	 *
	 * Each line of the report includes this data:
	 * - Function name
	 * - Number of times function was called
	 * - Total wall clock time spent in function in microseconds
	 * - Minimum wall clock time spent in function in microseconds
	 * - Average wall clock time spent in function in microseconds
	 * - Maximum wall clock time spent in function in microseconds
	 * - Percentage of total wall clock time spent in function
	 * - Total delta of memory usage from start to end of function in bytes
	 *
	 * @return string
	 */
	protected function getFunctionReport() {
		$data = $this->getFunctionStats();
		usort( $data, static function ( $a, $b ) {
			return $b['real'] <=> $a['real']; // descending
		} );

		$width = 140;
		$nameWidth = $width - 65;
		$format = "%-{$nameWidth}s %6d %9d %9d %9d %9d %7.3f%% %9d";
		$out = [];
		$out[] = sprintf( "%-{$nameWidth}s %6s %9s %9s %9s %9s %7s %9s",
			'Name', 'Calls', 'Total', 'Min', 'Each', 'Max', '%', 'Mem'
		);
		foreach ( $data as $stats ) {
			$out[] = sprintf( $format,
				$stats['name'],
				$stats['calls'],
				$stats['real'] * 1000,
				$stats['min_real'] * 1000,
				$stats['real'] / $stats['calls'] * 1000,
				$stats['max_real'] * 1000,
				$stats['%real'],
				$stats['memory']
			);
		}
		return implode( "\n", $out );
	}
}
