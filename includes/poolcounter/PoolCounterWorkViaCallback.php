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

use InvalidArgumentException;

/**
 * Convenience class for dealing with PoolCounter using callbacks
 * @since 1.22
 * @newable
 * @note marked as newable in 1.35 for lack of a better alternative,
 *       but should use a factory in the future.
 */
class PoolCounterWorkViaCallback extends PoolCounterWork {
	/** @var callable|null */
	protected $doWork;
	/** @var callable|null */
	protected $doCachedWork;
	/** @var callable|null */
	protected $fallback;
	/** @var callable|null */
	protected $error;

	/**
	 * Build a PoolCounterWork class from a type, key, and callback map.
	 *
	 * The callback map must at least have a callback for the 'doWork' method.
	 * Additionally, callbacks can be provided for the 'doCachedWork', 'fallback',
	 * and 'error' methods. Methods without callbacks will be no-ops that return false.
	 * If a 'doCachedWork' callback is provided, then execute() may wait for any prior
	 * process in the pool to finish and reuse its cached result.
	 *
	 * @stable to call
	 * @param PoolCounter|string $pool The PoolCounter or PoolCounter type
	 * @param string $key
	 * @param array $callbacks Map of callbacks
	 */
	public function __construct( $pool, string $key, array $callbacks ) {
		if ( is_string( $pool ) ) {
			$type = $pool;
			$pool = null;
		} else {
			$type = $pool->getType();
		}

		parent::__construct( $type, $key, $pool );

		foreach ( [ 'doWork', 'doCachedWork', 'fallback', 'error' ] as $name ) {
			if ( isset( $callbacks[$name] ) ) {
				if ( !is_callable( $callbacks[$name] ) ) {
					throw new InvalidArgumentException( "Invalid callback provided for '$name' function." );
				}
				$this->$name = $callbacks[$name];
			}
		}
		if ( !$this->doWork ) {
			throw new InvalidArgumentException( "No callback provided for 'doWork' function." );
		}
		$this->cacheable = (bool)$this->doCachedWork;
	}

	public function doWork() {
		return ( $this->doWork )();
	}

	public function getCachedWork() {
		if ( $this->doCachedWork ) {
			return ( $this->doCachedWork )();
		}
		return false;
	}

	public function fallback( $fast ) {
		if ( $this->fallback ) {
			return ( $this->fallback )( $fast );
		}
		return false;
	}

	public function error( $status ) {
		if ( $this->error ) {
			return ( $this->error )( $status );
		}
		return false;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( PoolCounterWorkViaCallback::class, 'PoolCounterWorkViaCallback' );
