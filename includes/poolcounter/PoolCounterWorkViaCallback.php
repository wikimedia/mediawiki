<?php
/**
 * Provides of semaphore semantics for restricting the number
 * of workers that may be concurrently performing the same task.
 *
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

/**
 * Convenience class for dealing with PoolCounters using callbacks
 * @since 1.22
 */
class PoolCounterWorkViaCallback extends PoolCounterWork {
	/** @var callable */
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
	 * @param string $type
	 * @param string $key
	 * @param array $callbacks Map of callbacks
	 * @throws MWException
	 */
	public function __construct( $type, $key, array $callbacks ) {
		parent::__construct( $type, $key );
		foreach ( array( 'doWork', 'doCachedWork', 'fallback', 'error' ) as $name ) {
			if ( isset( $callbacks[$name] ) ) {
				if ( !is_callable( $callbacks[$name] ) ) {
					throw new MWException( "Invalid callback provided for '$name' function." );
				}
				$this->$name = $callbacks[$name];
			}
		}
		if ( !isset( $this->doWork ) ) {
			throw new MWException( "No callback provided for 'doWork' function." );
		}
		$this->cacheable = isset( $this->doCachedWork );
	}

	public function doWork() {
		return call_user_func_array( $this->doWork, array() );
	}

	public function getCachedWork() {
		if ( $this->doCachedWork ) {
			return call_user_func_array( $this->doCachedWork, array() );
		}
		return false;
	}

	public function fallback() {
		if ( $this->fallback ) {
			return call_user_func_array( $this->fallback, array() );
		}
		return false;
	}

	public function error( $status ) {
		if ( $this->error ) {
			return call_user_func_array( $this->error, array( $status ) );
		}
		return false;
	}
}
