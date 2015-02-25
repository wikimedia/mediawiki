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

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

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
abstract class BagOStuff implements LoggerAwareInterface {
	private $debugMode = false;

	protected $lastError = self::ERR_NONE;

	/**
	 * @var LoggerInterface
	 */
	protected $logger;

	/** Possible values for getLastError() */
	const ERR_NONE = 0; // no error
	const ERR_NO_RESPONSE = 1; // no response
	const ERR_UNREACHABLE = 2; // can't connect
	const ERR_UNEXPECTED = 3; // response gave some error

	public function __construct( array $params = array() ) {
		if ( isset( $params['logger'] ) ) {
			$this->setLogger( $params['logger'] );
		} else {
			$this->setLogger( new NullLogger() );
		}
	}

	/**
	 * @param LoggerInterface $logger
	 * @return null
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * @param bool $bool
	 */
	public function setDebug( $bool ) {
		$this->debugMode = $bool;
	}

	/**
	 * Get an item with the given key. Returns false if it does not exist.
	 * @param string $key
	 * @param mixed $casToken [optional]
	 * @return mixed Returns false on failure
	 */
	abstract public function get( $key, &$casToken = null );

	/**
	 * Set an item.
	 * @param string $key
	 * @param mixed $value
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @return bool Success
	 */
	abstract public function set( $key, $value, $exptime = 0 );

	/**
	 * Delete an item.
	 * @param string $key
	 * @return bool True if the item was deleted or not found, false on failure
	 */
	abstract public function delete( $key );

	/**
	 * Merge changes into the existing cache value (possibly creating a new one).
	 * The callback function returns the new value given the current value (possibly false),
	 * and takes the arguments: (this BagOStuff object, cache key, current value).
	 *
	 * @param string $key
	 * @param callable $callback Callback method to be executed
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $attempts The amount of times to attempt a merge in case of failure
	 * @return bool Success
	 */
	public function merge( $key, $callback, $exptime = 0, $attempts = 10 ) {
		if ( !is_callable( $callback ) ) {
			throw new Exception( "Got invalid callback." );
		}

		return $this->mergeViaLock( $key, $callback, $exptime, $attempts );
	}

	/**
	 * @see BagOStuff::merge()
	 *
	 * @param string $key
	 * @param callable $callback Callback method to be executed
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $attempts The amount of times to attempt a merge in case of failure
	 * @return bool Success
	 */
	protected function mergeViaCas( $key, $callback, $exptime = 0, $attempts = 10 ) {
		do {
			$casToken = null; // passed by reference
			$currentValue = $this->get( $key, $casToken );
			// Derive the new value from the old value
			$value = call_user_func( $callback, $this, $key, $currentValue );

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
	 * Check and set an item
	 *
	 * @param mixed $casToken
	 * @param string $key
	 * @param mixed $value
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @return bool Success
	 */
	protected function cas( $casToken, $key, $value, $exptime = 0 ) {
		throw new Exception( "CAS is not implemented in " . __CLASS__ );
	}

	/**
	 * @see BagOStuff::merge()
	 *
	 * @param string $key
	 * @param callable $callback Callback method to be executed
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $attempts The amount of times to attempt a merge in case of failure
	 * @return bool Success
	 */
	protected function mergeViaLock( $key, $callback, $exptime = 0, $attempts = 10 ) {
		if ( !$this->lock( $key, 6 ) ) {
			return false;
		}

		$currentValue = $this->get( $key );
		// Derive the new value from the old value
		$value = call_user_func( $callback, $this, $key, $currentValue );

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
	 * @param string $key
	 * @param int $timeout Lock wait timeout [optional]
	 * @param int $expiry Lock expiry [optional]
	 * @return bool Success
	 */
	public function lock( $key, $timeout = 6, $expiry = 6 ) {
		$this->clearLastError();
		$timestamp = microtime( true ); // starting UNIX timestamp
		if ( $this->add( "{$key}:lock", 1, $expiry ) ) {
			return true;
		} elseif ( $this->getLastError() ) {
			return false;
		}

		$uRTT = ceil( 1e6 * ( microtime( true ) - $timestamp ) ); // estimate RTT (us)
		$sleep = 2 * $uRTT; // rough time to do get()+set()

		$locked = false; // lock acquired
		$attempts = 0; // failed attempts
		do {
			if ( ++$attempts >= 3 && $sleep <= 5e5 ) {
				// Exponentially back off after failed attempts to avoid network spam.
				// About 2*$uRTT*(2^n-1) us of "sleep" happen for the next n attempts.
				$sleep *= 2;
			}
			usleep( $sleep ); // back off
			$this->clearLastError();
			$locked = $this->add( "{$key}:lock", 1, $expiry );
			if ( $this->getLastError() ) {
				return false;
			}
		} while ( !$locked && ( microtime( true ) - $timestamp ) < $timeout );

		return $locked;
	}

	/**
	 * @param string $key
	 * @return bool Success
	 */
	public function unlock( $key ) {
		return $this->delete( "{$key}:lock" );
	}

	/**
	 * Delete all objects expiring before a certain date.
	 * @param string $date The reference date in MW format
	 * @param callable|bool $progressCallback Optional, a function which will be called
	 *     regularly during long-running operations with the percentage progress
	 *     as the first parameter.
	 *
	 * @return bool Success, false if unimplemented
	 */
	public function deleteObjectsExpiringBefore( $date, $progressCallback = false ) {
		// stub
		return false;
	}

	/* *** Emulated functions *** */

	/**
	 * Get an associative array containing the item for each of the keys that have items.
	 * @param array $keys List of strings
	 * @return array
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
	 * Batch insertion
	 * @param array $data $key => $value assoc array
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @return bool Success
	 * @since 1.24
	 */
	public function setMulti( array $data, $exptime = 0 ) {
		$res = true;
		foreach ( $data as $key => $value ) {
			if ( !$this->set( $key, $value, $exptime ) ) {
				$res = false;
			}
		}
		return $res;
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @param int $exptime
	 * @return bool Success
	 */
	public function add( $key, $value, $exptime = 0 ) {
		if ( $this->get( $key ) === false ) {
			return $this->set( $key, $value, $exptime );
		}
		return false; // key already set
	}

	/**
	 * Increase stored value of $key by $value while preserving its TTL
	 * @param string $key Key to increase
	 * @param int $value Value to add to $key (Default 1)
	 * @return int|bool New value or false on failure
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
	 * @param string $key
	 * @param int $value
	 * @return int
	 */
	public function decr( $key, $value = 1 ) {
		return $this->incr( $key, - $value );
	}

	/**
	 * Increase stored value of $key by $value while preserving its TTL
	 *
	 * This will create the key with value $init and TTL $ttl if not present
	 *
	 * @param string $key
	 * @param int $ttl
	 * @param int $value
	 * @param int $init
	 * @return bool
	 * @since 1.24
	 */
	public function incrWithInit( $key, $ttl, $value = 1, $init = 1 ) {
		return $this->incr( $key, $value ) ||
			$this->add( $key, (int)$init, $ttl ) || $this->incr( $key, $value );
	}

	/**
	 * Get the "last error" registered; clearLastError() should be called manually
	 * @return int ERR_* constant for the "last error" registry
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
	 * @param int $err ERR_* constant
	 * @since 1.23
	 */
	protected function setLastError( $err ) {
		$this->lastError = $err;
	}

	/**
	 * @param string $text
	 */
	protected function debug( $text ) {
		if ( $this->debugMode ) {
			$this->logger->debug( "{class} debug: $text", array(
				'class' => get_class( $this ),
			) );
		}
	}

	/**
	 * Convert an optionally relative time to an absolute time
	 * @param int $exptime
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
	 * @param int $exptime
	 * @return int
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
	 * @param mixed $value
	 * @return bool
	 */
	protected function isInteger( $value ) {
		return ( is_int( $value ) || ctype_digit( $value ) );
	}
}
