<?php

namespace Wikimedia\WRStats;

/**
 * Class representation of normalized sequence specifications.
 *
 * @internal
 */
class SequenceSpec {
	/** The default time bucket size (seconds) */
	public const DEFAULT_TIME_STEP = 600;

	/** The default expiry time (seconds) */
	public const DEFAULT_EXPIRY = 3600;

	/** @var string */
	public $name;
	/** @var float|int */
	public $timeStep;
	/** @var float|int */
	public $softExpiry;
	/** @var int */
	public $hardExpiry;

	/**
	 * @param array $spec
	 */
	public function __construct( array $spec ) {
		$this->timeStep = $spec['timeStep'] ?? self::DEFAULT_TIME_STEP;
		$this->softExpiry = $spec['expiry'] ?? self::DEFAULT_EXPIRY;
		$this->hardExpiry = (int)ceil( $this->softExpiry + $this->timeStep );
		$this->name = $spec['name'] ?? '';
	}
}
