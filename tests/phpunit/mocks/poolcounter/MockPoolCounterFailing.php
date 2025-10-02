<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\PoolCounter\PoolCounter;
use MediaWiki\Status\Status;

/**
 * Fake PoolCounter that always fails
 */
class MockPoolCounterFailing extends PoolCounter {
	/** @var Status|null */
	private $mockAcquire;
	/** @var Status|null */
	private $mockRelease;

	/** @inheritDoc */
	public function __construct( $conf, $type, $key ) {
		$conf += [
			'timeout' => 15,
			'workers' => 2,
			'maxqueue' => 50,
		];
		parent::__construct( $conf, $type, $key );

		$this->mockAcquire = $conf['mockAcquire'] ?? null;
		$this->mockRelease = $conf['mockRelease'] ?? null;
	}

	/** @inheritDoc */
	public function acquireForMe( $timeout = null ) {
		return $this->mockAcquire ?? Status::newGood( PoolCounter::QUEUE_FULL );
	}

	/** @inheritDoc */
	public function acquireForAnyone( $timeout = null ) {
		return $this->mockAcquire ?? Status::newGood( PoolCounter::QUEUE_FULL );
	}

	/** @inheritDoc */
	public function release() {
		return $this->mockRelease ?? Status::newGood( PoolCounter::NOT_LOCKED );
	}
}
