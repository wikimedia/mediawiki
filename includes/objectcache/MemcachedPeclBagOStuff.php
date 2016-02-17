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
 * A wrapper class for the PECL memcached client
 *
 * @ingroup Cache
 */
class MemcachedPeclBagOStuff extends MemcachedBagOStuff {

	/**
	 * Constructor
	 *
	 * Available parameters are:
	 *   - servers:             The list of IP:port combinations holding the memcached servers.
	 *   - persistent:          Whether to use a persistent connection
	 *   - compress_threshold:  The minimum size an object must be before it is compressed
	 *   - timeout:             The read timeout in microseconds
	 *   - connect_timeout:     The connect timeout in seconds
	 *   - retry_timeout:       Time in seconds to wait before retrying a failed connect attempt
	 *   - server_failure_limit:  Limit for server connect failures before it is removed
	 *   - serializer:          May be either "php" or "igbinary". Igbinary produces more compact
	 *                          values, but serialization is much slower unless the php.ini option
	 *                          igbinary.compact_strings is off.
	 * @param array $params
	 * @throws InvalidArgumentException
	 */
	function __construct( $params ) {
		parent::__construct( $params );
		$params = $this->applyDefaultParams( $params );

		if ( $params['persistent'] ) {
			// The pool ID must be unique to the server/option combination.
			// The Memcached object is essentially shared for each pool ID.
			// We can only reuse a pool ID if we keep the config consistent.
			$this->client = new Memcached( md5( serialize( $params ) ) );
			if ( count( $this->client->getServerList() ) ) {
				$this->logger->debug( __METHOD__ . ": persistent Memcached object already loaded." );
				return; // already initialized; don't add duplicate servers
			}
		} else {
			$this->client = new Memcached;
		}

		if ( !isset( $params['serializer'] ) ) {
			$params['serializer'] = 'php';
		}

		if ( isset( $params['retry_timeout'] ) ) {
			$this->client->setOption( Memcached::OPT_RETRY_TIMEOUT, $params['retry_timeout'] );
		}

		if ( isset( $params['server_failure_limit'] ) ) {
			$this->client->setOption( Memcached::OPT_SERVER_FAILURE_LIMIT, $params['server_failure_limit'] );
		}

		// The compression threshold is an undocumented php.ini option for some
		// reason. There's probably not much harm in setting it globally, for
		// compatibility with the settings for the PHP client.
		ini_set( 'memcached.compression_threshold', $params['compress_threshold'] );

		// Set timeouts
		$this->client->setOption( Memcached::OPT_CONNECT_TIMEOUT, $params['connect_timeout'] * 1000 );
		$this->client->setOption( Memcached::OPT_SEND_TIMEOUT, $params['timeout'] );
		$this->client->setOption( Memcached::OPT_RECV_TIMEOUT, $params['timeout'] );
		$this->client->setOption( Memcached::OPT_POLL_TIMEOUT, $params['timeout'] / 1000 );

		// Set libketama mode since it's recommended by the documentation and
		// is as good as any. There's no way to configure libmemcached to use
		// hashes identical to the ones currently in use by the PHP client, and
		// even implementing one of the libmemcached hashes in pure PHP for
		// forwards compatibility would require MemcachedClient::get_sock() to be
		// rewritten.
		$this->client->setOption( Memcached::OPT_LIBKETAMA_COMPATIBLE, true );

		// Set the serializer
		switch ( $params['serializer'] ) {
			case 'php':
				$this->client->setOption( Memcached::OPT_SERIALIZER, Memcached::SERIALIZER_PHP );
				break;
			case 'igbinary':
				if ( !Memcached::HAVE_IGBINARY ) {
					throw new InvalidArgumentException(
						__CLASS__ . ': the igbinary extension is not available ' .
						'but igbinary serialization was requested.'
					);
				}
				$this->client->setOption( Memcached::OPT_SERIALIZER, Memcached::SERIALIZER_IGBINARY );
				break;
			default:
				throw new InvalidArgumentException(
					__CLASS__ . ': invalid value for serializer parameter'
				);
		}
		$servers = [];
		foreach ( $params['servers'] as $host ) {
			$servers[] = IP::splitHostAndPort( $host ); // (ip, port)
		}
		$this->client->addServers( $servers );
	}

	protected function getWithToken( $key, &$casToken, $flags = 0 ) {
		$this->debugLog( "get($key)" );
		$result = $this->client->get( $this->validateKeyEncoding( $key ), null, $casToken );
		$result = $this->checkResult( $key, $result );
		return $result;
	}

	public function set( $key, $value, $exptime = 0, $flags = 0 ) {
		$this->debugLog( "set($key)" );
		return $this->checkResult( $key, parent::set( $key, $value, $exptime ) );
	}

	protected function cas( $casToken, $key, $value, $exptime = 0 ) {
		$this->debugLog( "cas($key)" );
		return $this->checkResult( $key, parent::cas( $casToken, $key, $value, $exptime ) );
	}

	public function delete( $key ) {
		$this->debugLog( "delete($key)" );
		$result = parent::delete( $key );
		if ( $result === false && $this->client->getResultCode() === Memcached::RES_NOTFOUND ) {
			// "Not found" is counted as success in our interface
			return true;
		} else {
			return $this->checkResult( $key, $result );
		}
	}

	public function add( $key, $value, $exptime = 0 ) {
		$this->debugLog( "add($key)" );
		return $this->checkResult( $key, parent::add( $key, $value, $exptime ) );
	}

	public function incr( $key, $value = 1 ) {
		$this->debugLog( "incr($key)" );
		$result = $this->client->increment( $key, $value );
		return $this->checkResult( $key, $result );
	}

	public function decr( $key, $value = 1 ) {
		$this->debugLog( "decr($key)" );
		$result = $this->client->decrement( $key, $value );
		return $this->checkResult( $key, $result );
	}

	/**
	 * Check the return value from a client method call and take any necessary
	 * action. Returns the value that the wrapper function should return. At
	 * present, the return value is always the same as the return value from
	 * the client, but some day we might find a case where it should be
	 * different.
	 *
	 * @param string $key The key used by the caller, or false if there wasn't one.
	 * @param mixed $result The return value
	 * @return mixed
	 */
	protected function checkResult( $key, $result ) {
		if ( $result !== false ) {
			return $result;
		}
		switch ( $this->client->getResultCode() ) {
			case Memcached::RES_SUCCESS:
				break;
			case Memcached::RES_DATA_EXISTS:
			case Memcached::RES_NOTSTORED:
			case Memcached::RES_NOTFOUND:
				$this->debugLog( "result: " . $this->client->getResultMessage() );
				break;
			default:
				$msg = $this->client->getResultMessage();
				$logCtx = [];
				if ( $key !== false ) {
					$server = $this->client->getServerByKey( $key );
					$logCtx['memcached-server'] = "{$server['host']}:{$server['port']}";
					$logCtx['memcached-key'] = $key;
					$msg = "Memcached error for key \"{memcached-key}\" on server \"{memcached-server}\": $msg";
				} else {
					$msg = "Memcached error: $msg";
				}
				$this->logger->error( $msg, $logCtx );
				$this->setLastError( BagOStuff::ERR_UNEXPECTED );
		}
		return $result;
	}

	public function getMulti( array $keys, $flags = 0 ) {
		$this->debugLog( 'getMulti(' . implode( ', ', $keys ) . ')' );
		foreach ( $keys as $key ) {
			$this->validateKeyEncoding( $key );
		}
		$result = $this->client->getMulti( $keys ) ?: [];
		return $this->checkResult( false, $result );
	}

	/**
	 * @param array $data
	 * @param int $exptime
	 * @return bool
	 */
	public function setMulti( array $data, $exptime = 0 ) {
		$this->debugLog( 'setMulti(' . implode( ', ', array_keys( $data ) ) . ')' );
		foreach ( array_keys( $data ) as $key ) {
			$this->validateKeyEncoding( $key );
		}
		$result = $this->client->setMulti( $data, $this->fixExpiry( $exptime ) );
		return $this->checkResult( false, $result );
	}
}
