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
	/** @var Memcached */
	protected $client;

	/**
	 * Available parameters are:
	 *   - servers:              List of IP:port combinations holding the memcached servers.
	 *   - persistent:           Whether to use a persistent connection
	 *   - compress_threshold:   The minimum size an object must be before it is compressed
	 *   - timeout:              The read timeout in microseconds
	 *   - connect_timeout:      The connect timeout in seconds
	 *   - retry_timeout:        Time in seconds to wait before retrying a failed connect attempt
	 *   - server_failure_limit: Limit for server connect failures before it is removed
	 *   - serializer:           Either "php" or "igbinary". Igbinary produces more compact
	 *                           values, but serialization is much slower unless the php.ini
	 *                           option igbinary.compact_strings is off.
	 *   - use_binary_protocol   Whether to enable the binary protocol (default is ASCII)
	 *   - allow_tcp_nagle_delay Whether to permit Nagle's algorithm for reducing packet count
	 * @param array $params
	 */
	function __construct( $params ) {
		parent::__construct( $params );

		// Default class-specific parameters
		$params += [
			'compress_threshold' => 1500,
			'connect_timeout' => 0.5,
			'serializer' => 'php',
			'use_binary_protocol' => false,
			'allow_tcp_nagle_delay' => true
		];

		if ( $params['persistent'] ) {
			// The pool ID must be unique to the server/option combination.
			// The Memcached object is essentially shared for each pool ID.
			// We can only reuse a pool ID if we keep the config consistent.
			$connectionPoolId = md5( serialize( $params ) );
			$client = new Memcached( $connectionPoolId );
			$this->initializeClient( $client, $params );
		} else {
			$client = new Memcached;
			$this->initializeClient( $client, $params );
		}

		$this->client = $client;

		// The compression threshold is an undocumented php.ini option for some
		// reason. There's probably not much harm in setting it globally, for
		// compatibility with the settings for the PHP client.
		ini_set( 'memcached.compression_threshold', $params['compress_threshold'] );
	}

	/**
	 * Initialize the client only if needed and reuse it otherwise.
	 * This avoids duplicate servers in the list and new connections.
	 *
	 * @param Memcached $client
	 * @param array $params
	 * @throws RuntimeException
	 */
	private function initializeClient( Memcached $client, array $params ) {
		if ( $client->getServerList() ) {
			$this->logger->debug( __METHOD__ . ": pre-initialized client instance." );

			return; // preserve persistent handle
		}

		$this->logger->debug( __METHOD__ . ": initializing new client instance." );

		$options = [
			// Network protocol (ASCII or binary)
			Memcached::OPT_BINARY_PROTOCOL => $params['use_binary_protocol'],
			// Set various network timeouts
			Memcached::OPT_CONNECT_TIMEOUT => $params['connect_timeout'] * 1000,
			Memcached::OPT_SEND_TIMEOUT => $params['timeout'],
			Memcached::OPT_RECV_TIMEOUT => $params['timeout'],
			Memcached::OPT_POLL_TIMEOUT => $params['timeout'] / 1000,
			// Avoid pointless delay when sending/fetching large blobs
			Memcached::OPT_TCP_NODELAY => !$params['allow_tcp_nagle_delay'],
			// Set libketama mode since it's recommended by the documentation
			Memcached::OPT_LIBKETAMA_COMPATIBLE => true
		];
		if ( isset( $params['retry_timeout'] ) ) {
			$options[Memcached::OPT_RETRY_TIMEOUT] = $params['retry_timeout'];
		}
		if ( isset( $params['server_failure_limit'] ) ) {
			$options[Memcached::OPT_SERVER_FAILURE_LIMIT] = $params['server_failure_limit'];
		}
		if ( $params['serializer'] === 'php' ) {
			$options[Memcached::OPT_SERIALIZER] = Memcached::SERIALIZER_PHP;
		} elseif ( $params['serializer'] === 'igbinary' ) {
			if ( !Memcached::HAVE_IGBINARY ) {
				throw new RuntimeException(
					__CLASS__ . ': the igbinary extension is not available ' .
					'but igbinary serialization was requested.'
				);
			}
			$options[Memcached::OPT_SERIALIZER] = Memcached::SERIALIZER_IGBINARY;
		}

		if ( !$client->setOptions( $options ) ) {
			throw new RuntimeException(
				"Invalid options: " . json_encode( $options, JSON_PRETTY_PRINT )
			);
		}

		$servers = [];
		foreach ( $params['servers'] as $host ) {
			if ( preg_match( '/^\[(.+)\]:(\d+)$/', $host, $m ) ) {
				$servers[] = [ $m[1], (int)$m[2] ]; // (ip, port)
			} elseif ( preg_match( '/^([^:]+):(\d+)$/', $host, $m ) ) {
				$servers[] = [ $m[1], (int)$m[2] ]; // (ip or path, port)
			} else {
				$servers[] = [ $host, false ]; // (ip or path, port)
			}
		}

		if ( !$client->addServers( $servers ) ) {
			throw new RuntimeException( "Failed to inject server address list" );
		}
	}

	protected function doGet( $key, $flags = 0, &$casToken = null ) {
		$this->debug( "get($key)" );
		if ( defined( Memcached::class . '::GET_EXTENDED' ) ) { // v3.0.0
			/** @noinspection PhpUndefinedClassConstantInspection */
			$flags = Memcached::GET_EXTENDED;
			$res = $this->client->get( $this->validateKeyEncoding( $key ), null, $flags );
			if ( is_array( $res ) ) {
				$result = $res['value'];
				$casToken = $res['cas'];
			} else {
				$result = false;
				$casToken = null;
			}
		} else {
			$result = $this->client->get( $this->validateKeyEncoding( $key ), null, $casToken );
		}
		$result = $this->checkResult( $key, $result );
		return $result;
	}

	protected function doSet( $key, $value, $exptime = 0, $flags = 0 ) {
		$this->debug( "set($key)" );
		$result = $this->client->set(
			$this->validateKeyEncoding( $key ),
			$value,
			$this->fixExpiry( $exptime )
		);
		if ( $result === false && $this->client->getResultCode() === Memcached::RES_NOTSTORED ) {
			// "Not stored" is always used as the mcrouter response with AllAsyncRoute
			return true;
		}
		return $this->checkResult( $key, $result );
	}

	protected function cas( $casToken, $key, $value, $exptime = 0, $flags = 0 ) {
		$this->debug( "cas($key)" );
		$result = $this->client->cas( $casToken, $this->validateKeyEncoding( $key ),
			$value, $this->fixExpiry( $exptime ) );
		return $this->checkResult( $key, $result );
	}

	protected function doDelete( $key, $flags = 0 ) {
		$this->debug( "delete($key)" );
		$result = $this->client->delete( $this->validateKeyEncoding( $key ) );
		if ( $result === false && $this->client->getResultCode() === Memcached::RES_NOTFOUND ) {
			// "Not found" is counted as success in our interface
			return true;
		}
		return $this->checkResult( $key, $result );
	}

	public function add( $key, $value, $exptime = 0, $flags = 0 ) {
		$this->debug( "add($key)" );
		$result = $this->client->add(
			$this->validateKeyEncoding( $key ),
			$value,
			$this->fixExpiry( $exptime )
		);
		return $this->checkResult( $key, $result );
	}

	public function incr( $key, $value = 1 ) {
		$this->debug( "incr($key)" );
		$result = $this->client->increment( $key, $value );
		return $this->checkResult( $key, $result );
	}

	public function decr( $key, $value = 1 ) {
		$this->debug( "decr($key)" );
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
				$this->debug( "result: " . $this->client->getResultMessage() );
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

	protected function doGetMulti( array $keys, $flags = 0 ) {
		$this->debug( 'getMulti(' . implode( ', ', $keys ) . ')' );
		foreach ( $keys as $key ) {
			$this->validateKeyEncoding( $key );
		}
		$result = $this->client->getMulti( $keys ) ?: [];
		return $this->checkResult( false, $result );
	}

	protected function doSetMulti( array $data, $exptime = 0, $flags = 0 ) {
		$this->debug( 'setMulti(' . implode( ', ', array_keys( $data ) ) . ')' );
		foreach ( array_keys( $data ) as $key ) {
			$this->validateKeyEncoding( $key );
		}
		$result = $this->client->setMulti( $data, $this->fixExpiry( $exptime ) );
		return $this->checkResult( false, $result );
	}

	protected function doDeleteMulti( array $keys, $flags = 0 ) {
		$this->debug( 'deleteMulti(' . implode( ', ', $keys ) . ')' );
		foreach ( $keys as $key ) {
			$this->validateKeyEncoding( $key );
		}
		$result = $this->client->deleteMulti( $keys ) ?: [];
		$ok = true;
		foreach ( $result as $code ) {
			if ( !in_array( $code, [ true, Memcached::RES_NOTFOUND ], true ) ) {
				// "Not found" is counted as success in our interface
				$ok = false;
			}
		}
		return $this->checkResult( false, $ok );
	}

	protected function doChangeTTL( $key, $exptime, $flags ) {
		$this->debug( "touch($key)" );
		$result = $this->client->touch( $key, $exptime );
		return $this->checkResult( $key, $result );
	}

	protected function serialize( $value ) {
		if ( is_int( $value ) ) {
			return $value;
		}

		$serializer = $this->client->getOption( Memcached::OPT_SERIALIZER );
		if ( $serializer === Memcached::SERIALIZER_PHP ) {
			return serialize( $value );
		} elseif ( $serializer === Memcached::SERIALIZER_IGBINARY ) {
			return igbinary_serialize( $value );
		}

		throw new UnexpectedValueException( __METHOD__ . ": got serializer '$serializer'." );
	}

	protected function unserialize( $value ) {
		if ( $this->isInteger( $value ) ) {
			return (int)$value;
		}

		$serializer = $this->client->getOption( Memcached::OPT_SERIALIZER );
		if ( $serializer === Memcached::SERIALIZER_PHP ) {
			return unserialize( $value );
		} elseif ( $serializer === Memcached::SERIALIZER_IGBINARY ) {
			return igbinary_unserialize( $value );
		}

		throw new UnexpectedValueException( __METHOD__ . ": got serializer '$serializer'." );
	}
}
