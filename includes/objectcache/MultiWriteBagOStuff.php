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
	var $caches;

	/**
	 * Constructor. Parameters are:
	 *
	 *   - caches:   This should have a numbered array of cache parameter
	 *               structures, in the style required by $wgObjectCaches. See
	 *               the documentation of $wgObjectCaches for more detail.
	 *
	 * @param $params array
	 * @throws MWException
	 */
	public function __construct( $params ) {
		if ( !isset( $params['caches'] ) ) {
			throw new MWException( __METHOD__ . ': the caches parameter is required' );
		}

		$this->caches = array();
		foreach ( $params['caches'] as $cacheInfo ) {
			$this->caches[] = ObjectCache::newFromParams( $cacheInfo );
		}
	}

	/**
	 * @param $debug bool
	 */
	public function setDebug( $debug ) {
		$this->doWrite( 'setDebug', $debug );
	}

	/**
	 * @param $key string
	 * @param $casToken[optional] mixed
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
	 * @param $casToken mixed
	 * @param $key string
	 * @param $value mixed
	 * @param $exptime int
	 * @return bool
	 */
	public function cas( $casToken, $key, $value, $exptime = 0 ) {
		throw new MWException( "CAS is not implemented in " . __CLASS__ );
	}

	/**
	 * @param $key string
	 * @param $value mixed
	 * @param $exptime int
	 * @return bool
	 */
	public function set( $key, $value, $exptime = 0 ) {
		return $this->doWrite( 'set', $key, $value, $exptime );
	}

	/**
	 * @param $key string
	 * @param $time int
	 * @return bool
	 */
	public function delete( $key, $time = 0 ) {
		return $this->doWrite( 'delete', $key, $time );
	}

	/**
	 * @param $key string
	 * @param $value mixed
	 * @param $exptime int
	 * @return bool
	 */
	public function add( $key, $value, $exptime = 0 ) {
		return $this->doWrite( 'add', $key, $value, $exptime );
	}

	/**
	 * @param $key string
	 * @param $value mixed
	 * @param $exptime int
	 * @return bool
	 */
	public function replace( $key, $value, $exptime = 0 ) {
		return $this->doWrite( 'replace', $key, $value, $exptime );
	}

	/**
	 * @param $key string
	 * @param $value int
	 * @return bool|null
	 */
	public function incr( $key, $value = 1 ) {
		return $this->doWrite( 'incr', $key, $value );
	}

	/**
	 * @param $key string
	 * @param $value int
	 * @return bool
	 */
	public function decr( $key, $value = 1 ) {
		return $this->doWrite( 'decr', $key, $value );
	}

	/**
	 * @param $key string
	 * @param $timeout int
	 * @return bool
	 */
	public function lock( $key, $timeout = 0 ) {
		// Lock only the first cache, to avoid deadlocks
		if ( isset( $this->caches[0] ) ) {
			return $this->caches[0]->lock( $key, $timeout );
		} else {
			return true;
		}
	}

	/**
	 * @param $key string
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
	 * @param $key string
	 * @param $callback closure Callback method to be executed
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $attempts The amount of times to attempt a merge in case of failure
	 * @return bool success
	 */
	public function merge( $key, closure $callback, $exptime = 0, $attempts = 10 ) {
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
	 * @param $method string
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
	 * @param $date string
	 * @param $progressCallback bool|callback
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
