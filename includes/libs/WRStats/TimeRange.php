<?php

namespace Wikimedia\WRStats;

/**
 * A time range
 *
 * @since 1.39
 */
class TimeRange {
	/** @var float|int UNIX start time */
	public $start;
	/** @var float|int UNIX end time */
	public $end;

	/**
	 * @internal
	 *
	 * @param float|int $start
	 * @param float|int $end
	 */
	public function __construct( $start, $end ) {
		$this->start = $start;
		$this->end = $end;
	}

	/**
	 * Get the duration of the time range in seconds.
	 *
	 * @return float|int
	 */
	public function getDuration() {
		return $this->end - $this->start;
	}
}
