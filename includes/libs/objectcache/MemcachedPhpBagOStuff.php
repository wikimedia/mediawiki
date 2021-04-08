<?php
/**
 * Object caching using memcached.
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
 * A wrapper class for the pure-PHP memcached client, exposing a BagOStuff interface.
 *
 * @ingroup Cache
 */
class MemcachedPhpBagOStuff extends MemcachedBagOStuff {
	/** @var MemcachedClient */
	protected $client;

	/**
	 * Available parameters are:
	 *   - servers:             The list of IP:port combinations holding the memcached servers.
	 *   - persistent:          Whether to use a persistent connection
	 *   - compress_threshold:  The minimum size an object must be before it is compressed
	 *   - timeout:             The read timeout in microseconds
	 *   - connect_timeout:     The connect timeout in seconds
	 *
	 * @param array $params
	 */
	public function __construct( $params ) {
		parent::__construct( $params );

		// Default class-specific parameters
		$params += [
			'compress_threshold' => 1500,
			'connect_timeout' => 0.5,
			'timeout' => 500000,
		];

		$this->client = new MemcachedClient( $params );
		$this->client->set_servers( $params['servers'] );
		$this->client->set_debug( true );
	}

	protected function doGet( $key, $flags = 0, &$casToken = null ) {
		$getToken = ( $casToken === self::PASS_BY_REF );
		$casToken = null;

		$routeKey = $this->validateKeyAndPrependRoute( $key );

		// T257003: only require "gets" (instead of "get") when a CAS token is needed
		return $getToken
			? $this->client->get( $routeKey, $casToken )
			: $this->client->get( $routeKey );
	}

	protected function doSet( $key, $value, $exptime = 0, $flags = 0 ) {
		$routeKey = $this->validateKeyAndPrependRoute( $key );

		return $this->client->set( $routeKey, $value, $this->fixExpiry( $exptime ) );
	}

	protected function doDelete( $key, $flags = 0 ) {
		$routeKey = $this->validateKeyAndPrependRoute( $key );

		return $this->client->delete( $routeKey );
	}

	protected function doAdd( $key, $value, $exptime = 0, $flags = 0 ) {
		$routeKey = $this->validateKeyAndPrependRoute( $key );

		return $this->client->add( $routeKey, $value, $this->fixExpiry( $exptime ) );
	}

	protected function doCas( $casToken, $key, $value, $exptime = 0, $flags = 0 ) {
		$routeKey = $this->validateKeyAndPrependRoute( $key );

		return $this->client->cas( $casToken, $routeKey, $value, $this->fixExpiry( $exptime ) );
	}

	public function incr( $key, $value = 1, $flags = 0 ) {
		$routeKey = $this->validateKeyAndPrependRoute( $key );
		$n = $this->client->incr( $routeKey, $value );

		return ( $n !== false && $n !== null ) ? $n : false;
	}

	public function decr( $key, $value = 1, $flags = 0 ) {
		$routeKey = $this->validateKeyAndPrependRoute( $key );
		$n = $this->client->decr( $routeKey, $value );

		return ( $n !== false && $n !== null ) ? $n : false;
	}

	protected function doIncrWithInit( $key, $exptime, $step, $init, $flags ) {
		$routeKey = $this->validateKeyAndPrependRoute( $key );

		$watchPoint = $this->watchErrors();
		$newValue = $this->client->incr( $routeKey, $step ) ?? false;
		if ( $newValue === false && !$this->getLastError( $watchPoint ) ) {
			// No key set; initialize
			$success = $this->client->add( $routeKey, $init, $this->fixExpiry( $exptime ) );
			$newValue = $success ? $init : false;
			if ( $newValue === false && !$this->getLastError( $watchPoint ) ) {
				// Raced out initializing; increment
				$newValue = $this->client->incr( $routeKey, $step ) ?? false;
			}
		}

		return $newValue;
	}

	protected function doChangeTTL( $key, $exptime, $flags ) {
		$routeKey = $this->validateKeyAndPrependRoute( $key );

		return $this->client->touch( $routeKey, $this->fixExpiry( $exptime ) );
	}

	protected function doGetMulti( array $keys, $flags = 0 ) {
		$routeKeys = [];
		foreach ( $keys as $key ) {
			$routeKeys[] = $this->validateKeyAndPrependRoute( $key );
		}

		$resByRouteKey = $this->client->get_multi( $routeKeys );

		$res = [];
		foreach ( $resByRouteKey as $routeKey => $value ) {
			$res[$this->stripRouteFromKey( $routeKey )] = $value;
		}

		return $res;
	}

	protected function serialize( $value ) {
		return is_int( $value ) ? $value : $this->client->serialize( $value );
	}

	protected function unserialize( $value ) {
		return $this->isInteger( $value ) ? (int)$value : $this->client->unserialize( $value );
	}
}
