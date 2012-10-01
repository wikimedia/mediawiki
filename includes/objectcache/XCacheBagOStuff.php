<?php
/**
 * Object caching using XCache.
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
 * Wrapper for XCache object caching functions; identical interface
 * to the APC wrapper
 *
 * @ingroup Cache
 */
class XCacheBagOStuff extends BagOStuff {
	/**
	 * Get a value from the XCache object cache
	 *
	 * @param $key String: cache key
	 * @param $casToken mixed: cas token
	 * @return mixed
	 */
	public function get( $key, &$casToken = null ) {
		$val = xcache_get( $key );

		if ( is_string( $val ) ) {
			if ( $this->isInteger( $val ) ) {
				$val = intval( $val );
			} else {
				$val = unserialize( $val );
			}
		}

		return $val;
	}

	/**
	 * Store a value in the XCache object cache
	 *
	 * @param $key String: cache key
	 * @param $value Mixed: object to store
	 * @param $expire Int: expiration time
	 * @return bool
	 */
	public function set( $key, $value, $expire = 0 ) {
		if ( !$this->isInteger( $value ) ) {
			$value = serialize( $value );
		}

		xcache_set( $key, $value, $expire );
		return true;
	}

	/**
	 * Store a value in the XCache object cache
	 *
	 * @param $casToken mixed: cache token
	 * @param $key String: cache key
	 * @param $value Mixed: object to store
	 * @param $expire Int: expiration time
	 * @return bool
	 */
	public function cas( $casToken, $key, $value, $expire = 0 ) {
		// can't find any documentation on xcache cas
		return false;
	}

	/**
	 * Remove a value from the XCache object cache
	 *
	 * @param $key String: cache key
	 * @param $time Int: not used in this implementation
	 * @return bool
	 */
	public function delete( $key, $time = 0 ) {
		xcache_unset( $key );
		return true;
	}

	/**
	 * Merge an item.
	 * XCache does not seem to support any way of performing CAS - this however will
	 * provide a way to perform CAS-like functionality.
	 *
	 * @param $key string
	 * @param $callback closure Callback method to be executed
	 * @param $exptime int Either an interval in seconds or a unix timestamp for expiry
	 * @return bool success
	 */
	public function merge( $key, closure $callback, $exptime = 0 ) {
		/*
		 * Use file locking to make sure no 2 merges against the same key can be
		 * performed at the same time. If we are unable to get the lock, it means
		 * a concurrent merge is happening for this cache key.
		 */
		$lock = @fopen( sys_get_temp_dir() . '/' . md5( $key ) . '.lock', 'w' );
		if ( !$lock && !flock( $lock, LOCK_EX ) ) {
			return false;
		}

		$currentValue = $this->get( $key, $casToken );
		$value = $callback( $this, $key, $currentValue );
		$success = $this->set( $key, $value, $exptime );

		// release lock
		flock( $lock, LOCK_UN );
		fclose( $lock );
		unlink( $lock );

		return $success;
	}

	public function incr( $key, $value = 1 ) {
		return xcache_inc( $key, $value );
	}

	public function decr( $key, $value = 1 ) {
		return xcache_dec( $key, $value );
	}
}
