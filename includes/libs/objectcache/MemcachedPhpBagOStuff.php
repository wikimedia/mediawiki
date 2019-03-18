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
	/**
	 * Available parameters are:
	 *   - servers:             The list of IP:port combinations holding the memcached servers.
	 *   - debug:               Whether to set the debug flag in the underlying client.
	 *   - persistent:          Whether to use a persistent connection
	 *   - compress_threshold:  The minimum size an object must be before it is compressed
	 *   - timeout:             The read timeout in microseconds
	 *   - connect_timeout:     The connect timeout in seconds
	 *
	 * @param array $params
	 */
	function __construct( $params ) {
		parent::__construct( $params );
		$params = $this->applyDefaultParams( $params );

		$this->client = new MemcachedClient( $params );
		$this->client->set_servers( $params['servers'] );
		$this->client->set_debug( $params['debug'] );
	}

	public function setDebug( $debug ) {
		$this->client->set_debug( $debug );
	}

	public function getMulti( array $keys, $flags = 0 ) {
		foreach ( $keys as $key ) {
			$this->validateKeyEncoding( $key );
		}

		return $this->client->get_multi( $keys );
	}
}
