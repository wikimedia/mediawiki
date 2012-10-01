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
	 * This will contain a mapping of 1 cas-token (that's returned to
	 * the feature implementing this class) to all cas tokens of the
	 * real caches
	 *
	 * @var array
	 */
	var $cas = array();

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
			throw new MWException( __METHOD__.': the caches parameter is required' );
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
		/*
		 * if cas-parameter is actually passed along, we need to loop
		 * all caches, just to get the cache token for all of them
		 */
		$args = func_get_args();
		if ( array_key_exists( 1, $args ) ) {
			$casToken = uniqid();
		}

		$value = false;

		foreach ( $this->caches as $i => $cache ) {
			$value = $cache->get( $key, $token );
			if ( $value !== false ) {
				// stop at first result unless we need to collect all cas tokens
				if ( $casToken === null ) {
					break;
				} else {
					// @todo: sanity check to make sure $token is set for a certain cache
					$this->cas[$casToken][$i] = $token;
				}
			}
		}
		return $value;
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
	 * @param $casToken mixed
	 * @param $key string
	 * @param $value mixed
	 * @param $exptime int
	 * @return bool
	 */
	public function cas( $casToken, $key, $value, $exptime = 0 ) {
		$ret = true;
		$backup = array();

		// try cas'ing all caches (unless one fails)
		foreach ( $this->caches as $i => $cache ) {
			$backup[$i] = $cache->get( $key );
			if ( !$cache->cas( $this->cas[$casToken][$i], $key, $value, $exptime ) ) {
				$ret = false;
				break;
			}
		}

		// cas failed on some cache, reset values we cas'ed on other cache instances
		if ( $ret === false ) {
			foreach ( $backup as $i => $value ) {
				$this->caches[$i]->set( $key, $value );
			}
		}

		// cas tokens are no longer valid
		unset( $this->cas[$casToken] );

		return $ret;
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
	 * @param $callback closure Callback method to be executed
	 * @param $exptime int Either an interval in seconds or a unix timestamp for expiry
	 * @return bool success
	 */
	public function merge( $key, closure $callback, $exptime = 0 ) {
		return $this->doWrite( 'merge', $key, $callback, $exptime );
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
