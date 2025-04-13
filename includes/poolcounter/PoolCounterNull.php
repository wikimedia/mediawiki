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

	public function acquireForMe( $timeout = null ) {
		return Status::newGood( PoolCounter::LOCKED );
	}

	public function acquireForAnyone( $timeout = null ) {
		return Status::newGood( PoolCounter::LOCKED );
	}

	public function release() {
		return Status::newGood( PoolCounter::RELEASED );
	}
}

/** @deprecated class alias since 1.42 */
class_alias( PoolCounterNull::class, 'PoolCounterNull' );
