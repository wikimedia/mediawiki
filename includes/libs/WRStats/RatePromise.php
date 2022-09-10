<?php

namespace Wikimedia\WRStats;

/**
 * A WRStats query result promise. It contains the input parameters to a query.
 * When an accessor method is called, it triggers batch query execution in the
 * parent WRStatsReader.
 *
 * @since 1.39
 */
class RatePromise {
	/** @var WRStatsReader */
	private $reader;
	/** @var string */
	private $name;
	/** @var EntityKey */
	private $entity;
	/** @var MetricSpec */
	private $metricSpec;
	/** @var SequenceSpec */
	private $seqSpec;
	/** @var TimeRange */
	private $range;
	/** @var int|float|null Lazy-initialised query result */
	private $total;

	/**
	 * @internal Constructed by WRStatsReader
	 *
	 * @param WRStatsReader $reader
	 * @param string $name
	 * @param EntityKey $entity
	 * @param MetricSpec $metricSpec
	 * @param SequenceSpec $seqSpec
	 * @param TimeRange $range
	 */
	public function __construct(
		WRStatsReader $reader,
		string $name,
		EntityKey $entity,
		MetricSpec $metricSpec,
		SequenceSpec $seqSpec,
		TimeRange $range
	) {
		$this->reader = $reader;
		$this->name = $name;
		$this->entity = $entity;
		$this->metricSpec = $metricSpec;
		$this->seqSpec = $seqSpec;
		$this->range = $range;
	}

	/**
	 * Get the total counter value summed over the specified time range.
	 *
	 * @return float|int
	 */
	public function total() {
		if ( $this->total === null ) {
			$this->total = $this->reader->internalGetCount(
				$this->name,
				$this->entity,
				$this->metricSpec,
				$this->seqSpec,
				$this->range
			);
		}
		return $this->total;
	}

	/**
	 * Get the counter value as a rate per second.
	 *
	 * @return float
	 */
	public function perSecond() {
		return $this->total() / $this->range->getDuration();
	}

	/**
	 * Get the counter value as a rate per minute
	 *
	 * @return float
	 */
	public function perMinute() {
		return $this->perSecond() * 60;
	}

	/**
	 * Get the counter value as a rate per hour
	 *
	 * @return float
	 */
	public function perHour() {
		return $this->perSecond() * 3600;
	}

	/**
	 * Get the counter value as a rate per day
	 *
	 * @return float
	 */
	public function perDay() {
		return $this->perSecond() * 86400;
	}
}
