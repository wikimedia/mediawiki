<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\PoolCounter;

use MediaWiki\Status\Status;

/**
 * A default PoolCounter, which provides no locking.
 *
 * @internal
 * @since 1.33
 */
class PoolCounterNull extends PoolCounter {

	public function __construct() {
		$conf = [ // not used for anything, but the parent constructor needs this
			'workers' => 100,
			'maxqueue' => 1000,
			'timeout' => 120,
			'fastStale' => true
		];
		parent::__construct( $conf, 'Null', 'none' );
	}

	/** @inheritDoc */
	public function acquireForMe( $timeout = null ) {
		return Status::newGood( PoolCounter::LOCKED );
	}

	/** @inheritDoc */
	public function acquireForAnyone( $timeout = null ) {
		return Status::newGood( PoolCounter::LOCKED );
	}

	/** @inheritDoc */
	public function release() {
		return Status::newGood( PoolCounter::RELEASED );
	}
}

/** @deprecated class alias since 1.42 */
class_alias( PoolCounterNull::class, 'PoolCounterNull' );
