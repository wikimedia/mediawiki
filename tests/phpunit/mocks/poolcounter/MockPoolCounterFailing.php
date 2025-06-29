<?php
/**
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
