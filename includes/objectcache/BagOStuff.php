<?php
/**
 * Classes to cache objects in PHP accelerators, SQL database or DBA files
 *
 * Copyright © 2003-2004 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
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

	protected $lastError = self::ERR_NONE;

	/** Possible values for getLastError() */
	const ERR_NONE        = 0; // no error
	const ERR_NO_RESPONSE = 1; // no response
	const ERR_UNREACHABLE = 2; // can't connect
	const ERR_UNEXPECTED  = 3; // response gave some error

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
	 * @param $casToken[optional] mixed
	 * @return mixed Returns false on failure
	 */
	abstract public function get( $key, &$casToken = null );

	/**
	 * Set an item.
	 * @param $key string
	 * @param $value mixed
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @return bool success
	 */
	abstract public function set( $key, $value, $exptime = 0 );

	/**
	 * Check and set an item.
	 * @param $casToken mixed
	 * @param $key string
	 * @param $value mixed
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @return bool success
	 */
	abstract public function cas( $casToken, $key, $value, $exptime = 0 );

	/**
	 * Delete an item.
	 * @param $key string
	 * @param int $time Amount of time to delay the operation (mostly memcached-specific)
	 * @return bool True if the item was deleted or not found, false on failure
	 */
	abstract public function delete( $key, $time = 0 );

	/**
	 * Merge changes into the existing cache value (possibly creating a new one).
	 * The callback function returns the new value given the current value (possibly false),
	 * and takes the arguments: (this BagOStuff object, cache key, current value).
	 *
	 * @param $key string
	 * @param $callback closure Callback method to be executed
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $attempts The amount of times to attempt a merge in case of failure
	 * @return bool success
	 */
	public function merge( $key, closure $callback, $exptime = 0, $attempts = 10 ) {
		return $this->mergeViaCas( $key, $callback, $exptime, $attempts );
	}

	/**
	 * @see BagOStuff::merge()
	 *
	 * @param $key string
	 * @param $callback closure Callback method to be executed
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $attempts The amount of times to attempt a merge in case of failure
	 * @return bool success
	 */
	protected function mergeViaCas( $key, closure $callback, $exptime = 0, $attempts = 10 ) {
		do {
			$casToken = null; // passed by reference
			$currentValue = $this->get( $key, $casToken ); // get the old value
			$value = $callback( $this, $key, $currentValue ); // derive the new value

			if ( $value === false ) {
				$success = true; // do nothing
			} elseif ( $currentValue === false ) {
				// Try to create the key, failing if it gets created in the meantime
				$success = $this->add( $key, $value, $exptime );
			} else {
				// Try to update the key, failing if it gets changed in the meantime
				$success = $this->cas( $casToken, $key, $value, $exptime );
			}
		} while ( !$success && --$attempts );

		return $success;
	}

	/**
	 * @see BagOStuff::merge()
	 *
	 * @param $key string
	 * @param $callback closure Callback method to be executed
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $attempts The amount of times to attempt a merge in case of failure
	 * @return bool success
	 */
	protected function mergeViaLock( $key, closure $callback, $exptime = 0, $attempts = 10 ) {
		if ( !$this->lock( $key, 6 ) ) {
			return false;
		}

		$currentValue = $this->get( $key ); // get the old value
		$value = $callback( $this, $key, $currentValue ); // derive the new value

		if ( $value === false ) {
			$success = true; // do nothing
		} else {
			$success = $this->set( $key, $value, $exptime ); // set the new value
		}

		if ( !$this->unlock( $key ) ) {
			// this should never happen
			trigger_error( "Could not release lock for key '$key'." );
		}

		return $success;
	}

	/**
	 * @param $key string
	 * @param $timeout integer [optional]
	 * @return bool success
	 */
	public function lock( $key, $timeout = 6 ) {
		$this->clearLastError();
		$timestamp = microtime( true ); // starting UNIX timestamp
		if ( $this->add( "{$key}:lock", 1, $timeout ) ) {
			return true;
		} elseif ( $this->getLastError() ) {
			return false;
		}

		$uRTT = ceil( 1e6 * ( microtime( true ) - $timestamp ) ); // estimate RTT (us)
		$sleep = 2 * $uRTT; // rough time to do get()+set()

		$locked = false; // lock acquired
		$attempts = 0; // failed attempts
		do {
			if ( ++$attempts >= 3 && $sleep <= 1e6 ) {
				// Exponentially back off after failed attempts to avoid network spam.
				// About 2*$uRTT*(2^n-1) us of "sleep" happen for the next n attempts.
				$sleep *= 2;
			}
			usleep( $sleep ); // back off
			$this->clearLastError();
			$locked = $this->add( "{$key}:lock", 1, $timeout );
			if ( $this->getLastError() ) {
				return false;
			}
		} while ( !$locked );

		return $locked;
	}

	/**
	 * @param $key string
	 * @return bool success
	 */
	public function unlock( $key ) {
		return $this->delete( "{$key}:lock" );
	}

	/**
	 * Delete all objects expiring before a certain date.
	 * @param string $date The reference date in MW format
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
	 * @param array $keys List of strings
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
	 * @deprecated 1.23
	 */
	public function replace( $key, $value, $exptime = 0 ) {
		wfDeprecated( __METHOD__, '1.23' );
		if ( $this->get( $key ) !== false ) {
			return $this->set( $key, $value, $exptime );
		}
		return false; // key not already set
	}

	/**
	 * Increase stored value of $key by $value while preserving its TTL
	 * @param string $key Key to increase
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
	 * Get the "last error" registered; clearLastError() should be called manually
	 * @return integer ERR_* constant for the "last error" registry
	 * @since 1.23
	 */
	public function getLastError() {
		return $this->lastError;
	}

	/**
	 * Clear the "last error" registry
	 * @since 1.23
	 */
	public function clearLastError() {
		$this->lastError = self::ERR_NONE;
	}

	/**
	 * Set the "last error" registry
	 * @param $err integer ERR_* constant
	 * @since 1.23
	 */
	protected function setLastError( $err ) {
		$this->lastError = $err;
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
