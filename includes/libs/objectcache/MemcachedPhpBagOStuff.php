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
			'connect_timeout' => 0.5
		];

		$this->client = new MemcachedClient( $params );
		$this->client->set_servers( $params['servers'] );
	}

	public function setDebug( $enabled ) {
		parent::debug( $enabled );
		$this->client->set_debug( $enabled );
	}

	protected function doGet( $key, $flags = 0, &$casToken = null ) {
		$casToken = null;

		return $this->client->get( $this->validateKeyEncoding( $key ), $casToken );
	}

	protected function doSet( $key, $value, $exptime = 0, $flags = 0 ) {
		return $this->client->set(
			$this->validateKeyEncoding( $key ),
			$value,
			$this->fixExpiry( $exptime )
		);
	}

	protected function doDelete( $key, $flags = 0 ) {
		return $this->client->delete( $this->validateKeyEncoding( $key ) );
	}

	protected function doAdd( $key, $value, $exptime = 0, $flags = 0 ) {
		return $this->client->add(
			$this->validateKeyEncoding( $key ),
			$value,
			$this->fixExpiry( $exptime )
		);
	}

	protected function doCas( $casToken, $key, $value, $exptime = 0, $flags = 0 ) {
		return $this->client->cas(
			$casToken,
			$this->validateKeyEncoding( $key ),
			$value,
			$this->fixExpiry( $exptime )
		);
	}

	public function incr( $key, $value = 1, $flags = 0 ) {
		$n = $this->client->incr( $this->validateKeyEncoding( $key ), $value );

		return ( $n !== false && $n !== null ) ? $n : false;
	}

	public function decr( $key, $value = 1, $flags = 0 ) {
		$n = $this->client->decr( $this->validateKeyEncoding( $key ), $value );

		return ( $n !== false && $n !== null ) ? $n : false;
	}

	protected function doChangeTTL( $key, $exptime, $flags ) {
		return $this->client->touch(
			$this->validateKeyEncoding( $key ),
			$this->fixExpiry( $exptime )
		);
	}

	protected function doGetMulti( array $keys, $flags = 0 ) {
		foreach ( $keys as $key ) {
			$this->validateKeyEncoding( $key );
		}

		return $this->client->get_multi( $keys );
	}

	protected function serialize( $value ) {
		return is_int( $value ) ? $value : $this->client->serialize( $value );
	}

	protected function unserialize( $value ) {
		return $this->isInteger( $value ) ? (int)$value : $this->client->unserialize( $value );
	}
}
