<?php
/**
 * Wrapper for object caching in different caches.
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
 * @ingroup Cache
 */

/**
 * A cache class that replicates all writes to multiple child caches. Reads
 * are implemented by reading from the caches in the order they are given in
 * the configuration until a cache gives a positive result.
 *
 * @ingroup Cache
 */
class MultiWriteBagOStuff extends BagOStuff {
	/** @var array BagOStuff[] */
	protected $caches;

	/**
	 * Constructor. Parameters are:
	 *
	 *   - caches:   This should have a numbered array of cache parameter
	 *               structures, in the style required by $wgObjectCaches. See
	 *               the documentation of $wgObjectCaches for more detail.
	 *
	 * @param array $params
	 * @throws InvalidArgumentException
	 */
	public function __construct( $params ) {
		parent::__construct( $params );
		if ( !isset( $params['caches'] ) ) {
			throw new InvalidArgumentException( __METHOD__ . ': the caches parameter is required' );
		}

		$this->caches = array();
		foreach ( $params['caches'] as $cacheInfo ) {
			$this->caches[] = ObjectCache::newFromParams( $cacheInfo );
		}
	}

	/**
	 * @param bool $debug
	 */
	public function setDebug( $debug ) {
		$this->doWrite( 'setDebug', $debug );
	}

	/**
	 * @param string $key
	 * @param mixed $casToken [optional]
	 * @return bool|mixed
	 */
	public function get( $key, &$casToken = null ) {
		foreach ( $this->caches as $cache ) {
			$value = $cache->get( $key );
			if ( $value !== false ) {
				return $value;
			}
		}
		return false;
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @param int $exptime
	 * @return bool
	 */
	public function set( $key, $value, $exptime = 0 ) {
		return $this->doWrite( 'set', $key, $value, $exptime );
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function delete( $key ) {
		return $this->doWrite( 'delete', $key );
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @param int $exptime
	 * @return bool
	 */
	public function add( $key, $value, $exptime = 0 ) {
		return $this->doWrite( 'add', $key, $value, $exptime );
	}

	/**
	 * @param string $key
	 * @param int $value
	 * @return bool|null
	 */
	public function incr( $key, $value = 1 ) {
		return $this->doWrite( 'incr', $key, $value );
	}

	/**
	 * @param string $key
	 * @param int $value
	 * @return bool
	 */
	public function decr( $key, $value = 1 ) {
		return $this->doWrite( 'decr', $key, $value );
	}

	/**
	 * @param string $key
	 * @param int $timeout
	 * @param int $expiry
	 * @return bool
	 */
	public function lock( $key, $timeout = 6, $expiry = 6 ) {
		// Lock only the first cache, to avoid deadlocks
		if ( isset( $this->caches[0] ) ) {
			return $this->caches[0]->lock( $key, $timeout, $expiry );
		} else {
			return true;
		}
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function unlock( $key ) {
		if ( isset( $this->caches[0] ) ) {
			return $this->caches[0]->unlock( $key );
		} else {
			return true;
		}
	}

	/**
	 * @param string $key
	 * @param callable $callback Callback method to be executed
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $attempts The amount of times to attempt a merge in case of failure
	 * @return bool Success
	 */
	public function merge( $key, $callback, $exptime = 0, $attempts = 10 ) {
		return $this->doWrite( 'merge', $key, $callback, $exptime );
	}

	public function getLastError() {
		return isset( $this->caches[0] ) ? $this->caches[0]->getLastError() : self::ERR_NONE;
	}

	public function clearLastError() {
		if ( isset( $this->caches[0] ) ) {
			$this->caches[0]->clearLastError();
		}
	}

	/**
	 * @param string $method
	 * @return bool
	 */
	protected function doWrite( $method /*, ... */ ) {
		$ret = true;
		$args = func_get_args();
		array_shift( $args );

		foreach ( $this->caches as $cache ) {
			if ( !call_user_func_array( array( $cache, $method ), $args ) ) {
				$ret = false;
			}
		}
		return $ret;
	}

	/**
	 * Delete objects expiring before a certain date.
	 *
	 * Succeed if any of the child caches succeed.
	 * @param string $date
	 * @param bool|callable $progressCallback
	 * @return bool
	 */
	public function deleteObjectsExpiringBefore( $date, $progressCallback = false ) {
		$ret = false;
		foreach ( $this->caches as $cache ) {
			if ( $cache->deleteObjectsExpiringBefore( $date, $progressCallback ) ) {
				$ret = true;
			}
		}
		return $ret;
	}
}
