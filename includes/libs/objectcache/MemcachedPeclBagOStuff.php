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
	protected $syncClient;
	/** @var Memcached|null */
	protected $asyncClient;

	/** @var bool Whether the non-buffering client is locked from use */
	protected $syncClientIsBuffering = false;
	/** @var bool Whether the non-buffering client should be flushed before use */
	protected $hasUnflushedChanges = false;

	/** @var array Memcached options */
	private static $OPTS_SYNC_WRITES = [
		Memcached::OPT_NO_BLOCK => false, // async I/O (using TCP buffers)
		Memcached::OPT_BUFFER_WRITES => false // libmemcached buffers
	];
	/** @var array Memcached options */
	private static $OPTS_ASYNC_WRITES = [
		Memcached::OPT_NO_BLOCK => true, // async I/O (using TCP buffers)
		Memcached::OPT_BUFFER_WRITES => true // libmemcached buffers
	];

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
	public function __construct( $params ) {
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
			$syncClient = new Memcached( "$connectionPoolId-sync" );
			// Avoid clobbering the main thread-shared Memcached instance
			$asyncClient = new Memcached( "$connectionPoolId-async" );
		} else {
			$syncClient = new Memcached();
			$asyncClient = null;
		}

		$this->initializeClient( $syncClient, $params, self::$OPTS_SYNC_WRITES );
		if ( $asyncClient ) {
			$this->initializeClient( $asyncClient, $params, self::$OPTS_ASYNC_WRITES );
		}

		// Set the main client and any dedicated one for buffered writes
		$this->syncClient = $syncClient;
		$this->asyncClient = $asyncClient;
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
	 * @param array $options Base options for Memcached::setOptions()
	 * @throws RuntimeException
	 */
	private function initializeClient( Memcached $client, array $params, array $options ) {
		if ( $client->getServerList() ) {
			$this->logger->debug( __METHOD__ . ": pre-initialized client instance." );

			return; // preserve persistent handle
		}

		$this->logger->debug( __METHOD__ . ": initializing new client instance." );

		$options += [
			Memcached::OPT_NO_BLOCK => false,
			Memcached::OPT_BUFFER_WRITES => false,
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
			// @phan-suppress-next-line PhanImpossibleCondition
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

		$client = $this->acquireSyncClient();
		if ( defined( Memcached::class . '::GET_EXTENDED' ) ) { // v3.0.0
			/** @noinspection PhpUndefinedClassConstantInspection */
			$flags = Memcached::GET_EXTENDED;
			$res = $client->get( $this->validateKeyEncoding( $key ), null, $flags );
			if ( is_array( $res ) ) {
				$result = $res['value'];
				$casToken = $res['cas'];
			} else {
				$result = false;
				$casToken = null;
			}
		} else {
			$result = $client->get( $this->validateKeyEncoding( $key ), null, $casToken );
		}

		return $this->checkResult( $key, $result );
	}

	protected function doSet( $key, $value, $exptime = 0, $flags = 0 ) {
		$this->debug( "set($key)" );

		$client = $this->acquireSyncClient();
		$result = $client->set(
			$this->validateKeyEncoding( $key ),
			$value,
			$this->fixExpiry( $exptime )
		);

		return ( $result === false && $client->getResultCode() === Memcached::RES_NOTSTORED )
			// "Not stored" is always used as the mcrouter response with AllAsyncRoute
			? true
			: $this->checkResult( $key, $result );
	}

	protected function doCas( $casToken, $key, $value, $exptime = 0, $flags = 0 ) {
		$this->debug( "cas($key)" );

		$result = $this->acquireSyncClient()->cas(
			$casToken,
			$this->validateKeyEncoding( $key ),
			$value, $this->fixExpiry( $exptime )
		);

		return $this->checkResult( $key, $result );
	}

	protected function doDelete( $key, $flags = 0 ) {
		$this->debug( "delete($key)" );

		$client = $this->acquireSyncClient();
		$result = $client->delete( $this->validateKeyEncoding( $key ) );

		return ( $result === false && $client->getResultCode() === Memcached::RES_NOTFOUND )
			// "Not found" is counted as success in our interface
			? true
			: $this->checkResult( $key, $result );
	}

	protected function doAdd( $key, $value, $exptime = 0, $flags = 0 ) {
		$this->debug( "add($key)" );

		$result = $this->acquireSyncClient()->add(
			$this->validateKeyEncoding( $key ),
			$value,
			$this->fixExpiry( $exptime )
		);

		return $this->checkResult( $key, $result );
	}

	public function incr( $key, $value = 1, $flags = 0 ) {
		$this->debug( "incr($key)" );

		$result = $this->acquireSyncClient()->increment( $key, $value );

		return $this->checkResult( $key, $result );
	}

	public function decr( $key, $value = 1, $flags = 0 ) {
		$this->debug( "decr($key)" );

		$result = $this->acquireSyncClient()->decrement( $key, $value );

		return $this->checkResult( $key, $result );
	}

	public function setNewPreparedValues( array $valueByKey ) {
		// The PECL driver does the serializing and will not reuse anything from here
		$sizes = [];
		foreach ( $valueByKey as $value ) {
			$sizes[] = $this->guessSerialValueSize( $value );
		}

		return $sizes;
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

		$client = $this->syncClient;
		switch ( $client->getResultCode() ) {
			case Memcached::RES_SUCCESS:
				break;
			case Memcached::RES_DATA_EXISTS:
			case Memcached::RES_NOTSTORED:
			case Memcached::RES_NOTFOUND:
				$this->debug( "result: " . $client->getResultMessage() );
				break;
			default:
				$msg = $client->getResultMessage();
				$logCtx = [];
				if ( $key !== false ) {
					$server = $client->getServerByKey( $key );
					$logCtx['memcached-server'] = "{$server['host']}:{$server['port']}";
					$logCtx['memcached-key'] = $key;
					$msg = "Memcached error for key \"{memcached-key}\" " .
						"on server \"{memcached-server}\": $msg";
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

		// The PECL implementation uses "gets" which works as well as a pipeline
		$result = $this->acquireSyncClient()->getMulti( $keys ) ?: [];

		return $this->checkResult( false, $result );
	}

	protected function doSetMulti( array $data, $exptime = 0, $flags = 0 ) {
		$this->debug( 'setMulti(' . implode( ', ', array_keys( $data ) ) . ')' );

		$exptime = $this->fixExpiry( $exptime );
		foreach ( array_keys( $data ) as $key ) {
			$this->validateKeyEncoding( $key );
		}

		// The PECL implementation is a naïve for-loop so use async I/O to pipeline;
		// https://github.com/php-memcached-dev/php-memcached/blob/master/php_memcached.c#L1852
		if ( $this->fieldHasFlags( $flags, self::WRITE_BACKGROUND ) ) {
			$client = $this->acquireAsyncClient();
			$result = $client->setMulti( $data, $exptime );
			$this->releaseAsyncClient( $client );
		} else {
			$result = $this->acquireSyncClient()->setMulti( $data, $exptime );
		}

		return $this->checkResult( false, $result );
	}

	protected function doDeleteMulti( array $keys, $flags = 0 ) {
		$this->debug( 'deleteMulti(' . implode( ', ', $keys ) . ')' );

		foreach ( $keys as $key ) {
			$this->validateKeyEncoding( $key );
		}

		// The PECL implementation is a naïve for-loop so use async I/O to pipeline;
		// https://github.com/php-memcached-dev/php-memcached/blob/7443d16d02fb73cdba2e90ae282446f80969229c/php_memcached.c#L1852
		if ( $this->fieldHasFlags( $flags, self::WRITE_BACKGROUND ) ) {
			$client = $this->acquireAsyncClient();
			$resultArray = $client->deleteMulti( $keys ) ?: [];
			$this->releaseAsyncClient( $client );
		} else {
			$resultArray = $this->acquireSyncClient()->deleteMulti( $keys ) ?: [];
		}

		$result = true;
		foreach ( $resultArray as $code ) {
			if ( !in_array( $code, [ true, Memcached::RES_NOTFOUND ], true ) ) {
				// "Not found" is counted as success in our interface
				$result = false;
			}
		}

		return $this->checkResult( false, $result );
	}

	protected function doChangeTTL( $key, $exptime, $flags ) {
		$this->debug( "touch($key)" );

		$result = $this->acquireSyncClient()->touch( $key, $this->fixExpiry( $exptime ) );

		return $this->checkResult( $key, $result );
	}

	protected function serialize( $value ) {
		if ( is_int( $value ) ) {
			return $value;
		}

		$serializer = $this->syncClient->getOption( Memcached::OPT_SERIALIZER );
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

		$serializer = $this->syncClient->getOption( Memcached::OPT_SERIALIZER );
		if ( $serializer === Memcached::SERIALIZER_PHP ) {
			return unserialize( $value );
		} elseif ( $serializer === Memcached::SERIALIZER_IGBINARY ) {
			return igbinary_unserialize( $value );
		}

		throw new UnexpectedValueException( __METHOD__ . ": got serializer '$serializer'." );
	}

	/**
	 * @return Memcached
	 */
	private function acquireSyncClient() {
		if ( $this->syncClientIsBuffering ) {
			throw new RuntimeException( "The main (unbuffered I/O) client is locked" );
		}

		if ( $this->hasUnflushedChanges ) {
			// Force a synchronous flush of async writes so that their changes are visible
			$this->syncClient->fetch();
			if ( $this->asyncClient ) {
				$this->asyncClient->fetch();
			}
			$this->hasUnflushedChanges = false;
		}

		return $this->syncClient;
	}

	/**
	 * @return Memcached
	 */
	private function acquireAsyncClient() {
		if ( $this->asyncClient ) {
			return $this->asyncClient; // dedicated buffering instance
		}

		// Modify the main instance to temporarily buffer writes
		$this->syncClientIsBuffering = true;
		$this->syncClient->setOptions( self::$OPTS_ASYNC_WRITES );

		return $this->syncClient;
	}

	/**
	 * @param Memcached $client
	 */
	private function releaseAsyncClient( $client ) {
		$this->hasUnflushedChanges = true;

		if ( !$this->asyncClient ) {
			// This is the main instance; make it stop buffering writes again
			$client->setOptions( self::$OPTS_SYNC_WRITES );
			$this->syncClientIsBuffering = false;
		}
	}
}
