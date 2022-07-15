<?php

namespace Wikimedia\WRStats;

/**
 * @since 1.39
 * @newable
 */
class LimitCondition {
	/** @var int The maximum number of events */
	public $limit;
	/** @var float|int The number of seconds over which the number of events may occur */
	public $window;

	/**
	 * @param int|float|string $limit The maximum number of events
	 * @param int|float|string $window The number of seconds over which the
	 *   number of events may occur
	 */
	public function __construct( $limit, $window ) {
		$this->limit = (int)$limit;
		$this->window = +$window;
		if ( $this->window <= 0 ) {
			throw new WRStatsError( __METHOD__ .
				': window must be positive' );
		}
	}

	/**
	 * Get the condition as a number of events per second
	 *
	 * @return float|int
	 */
	public function perSecond() {
		return $this->limit / $this->window;
	}
}
