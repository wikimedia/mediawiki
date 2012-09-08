<?php
/**
 * Classes to cache objects in PHP accelerators, SQL database or DBA files
 *
 * Copyright © 2003-2004 Brion Vibber <brion@pobox.com>
 * http://www.mediawiki.org/
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
 * @defgroup Cache Cache
 */

/**
 * interface is intended to be more or less compatible with
 * the PHP memcached client.
 *
 * backends for local hash array and SQL table included:
 * <code>
 *   $bag = new HashBagOStuff();
 *   $bag = new SqlBagOStuff(); # connect to db first
 * </code>
 *
 * @ingroup Cache
 */
abstract class BagOStuff {
	private $debugMode = false;

	/**
	 * @param $bool bool
	 */
	public function setDebug( $bool ) {
		$this->debugMode = $bool;
	}

	/* *** THE GUTS OF THE OPERATION *** */
	/* Override these with functional things in subclasses */

	/**
	 * Get an item with the given key. Returns false if it does not exist.
	 * @param $key string
	 * @return mixed Returns false on failure
	 */
	abstract public function get( $key );

	/**
	 * Set an item.
	 * @param $key string
	 * @param $value mixed
	 * @param $exptime int Either an interval in seconds or a unix timestamp for expiry
	 * @return bool success
	 */
	abstract public function set( $key, $value, $exptime = 0 );

	/**
	 * Delete an item.
	 * @param $key string
	 * @param $time int Amount of time to delay the operation (mostly memcached-specific)
	 * @return bool True if the item was deleted or not found, false on failure
	 */
	abstract public function delete( $key, $time = 0 );

	/**
	 * @param $key string
	 * @param $timeout integer
	 * @return bool success
	 */
	public function lock( $key, $timeout = 0 ) {
		/* stub */
		return true;
	}

	/**
	 * @param $key string
	 * @return bool success
	 */
	public function unlock( $key ) {
		/* stub */
		return true;
	}

	/**
	 * @todo: what is this?
	 * @return Array
	 */
	public function keys() {
		/* stub */
		return array();
	}

	/**
	 * Delete all objects expiring before a certain date.
	 * @param $date string The reference date in MW format
	 * @param $progressCallback callback|bool Optional, a function which will be called
	 *     regularly during long-running operations with the percentage progress
	 *     as the first parameter.
	 *
	 * @return bool on success, false if unimplemented
	 */
	public function deleteObjectsExpiringBefore( $date, $progressCallback = false ) {
		// stub
		return false;
	}

	/* *** Emulated functions *** */

	/**
	 * Get an associative array containing the item for each of the keys that have items.
	 * @param $keys Array List of strings
	 * @return Array
	 */
	public function getMulti( array $keys ) {
		$res = array();
		foreach ( $keys as $key ) {
			$val = $this->get( $key );
			if ( $val !== false ) {
				$res[$key] = $val;
			}
		}
		return $res;
	}

	/**
	 * @param $key string
	 * @param $value mixed
	 * @param $exptime integer
	 * @return bool success
	 */
	public function add( $key, $value, $exptime = 0 ) {
		if ( $this->get( $key ) === false ) {
			return $this->set( $key, $value, $exptime );
		}
		return false; // key already set
	}

	/**
	 * @param $key string
	 * @param $value mixed
	 * @param $exptime int
	 * @return bool success
	 */
	public function replace( $key, $value, $exptime = 0 ) {
		if ( $this->get( $key ) !== false ) {
			return $this->set( $key, $value, $exptime );
		}
		return false; // key not already set
	}

	/**
	 * Increase stored value of $key by $value while preserving its TTL
	 * @param $key String: Key to increase
	 * @param $value Integer: Value to add to $key (Default 1)
	 * @return integer|bool New value or false on failure
	 */
	public function incr( $key, $value = 1 ) {
		if ( !$this->lock( $key ) ) {
			return false;
		}
		$n = $this->get( $key );
		if ( $this->isInteger( $n ) ) { // key exists?
			$n += intval( $value );
			$this->set( $key, max( 0, $n ) ); // exptime?
		} else {
			$n = false;
		}
		$this->unlock( $key );

		return $n;
	}

	/**
	 * Decrease stored value of $key by $value while preserving its TTL
	 * @param $key String
	 * @param $value Integer
	 * @return integer
	 */
	public function decr( $key, $value = 1 ) {
		return $this->incr( $key, - $value );
	}

	/**
	 * @param $text string
	 */
	public function debug( $text ) {
		if ( $this->debugMode ) {
			$class = get_class( $this );
			wfDebug( "$class debug: $text\n" );
		}
	}

	/**
	 * Convert an optionally relative time to an absolute time
	 * @param $exptime integer
	 * @return int
	 */
	protected function convertExpiry( $exptime ) {
		if ( ( $exptime != 0 ) && ( $exptime < 86400 * 3650 /* 10 years */ ) ) {
			return time() + $exptime;
		} else {
			return $exptime;
		}
	}

	/**
	 * Convert an optionally absolute expiry time to a relative time. If an
	 * absolute time is specified which is in the past, use a short expiry time.
	 *
	 * @param $exptime integer
	 * @return integer
	 */
	protected function convertToRelative( $exptime ) {
		if ( $exptime >= 86400 * 3650 /* 10 years */ ) {
			$exptime -= time();
			if ( $exptime <= 0 ) {
				$exptime = 1;
			}
			return $exptime;
		} else {
			return $exptime;
		}
	}

	/**
	 * Check if a value is an integer
	 *
	 * @param $value mixed
	 * @return bool
	 */
	protected function isInteger( $value ) {
		return ( is_int( $value ) || ctype_digit( $value ) );
	}
}
