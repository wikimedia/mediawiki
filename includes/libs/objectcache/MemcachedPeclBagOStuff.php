<?php
/**
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
 */
namespace Wikimedia\ObjectCache;

use Memcached;
use RuntimeException;
use UnexpectedValueException;
use Wikimedia\ScopedCallback;

/**
 * Store data on memcached server(s) via the php-memcached PECL extension.
 *
 * To use memcached out of the box without any PECL dependency, use the
 * MemcachedPhpBagOStuff class instead.
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
	 *
	 * @param array $params
	 */
	public function __construct( $params ) {
		parent::__construct( $params );

		// Default class-specific parameters
		$params += [
			'compress_threshold' => 1500,
			'connect_timeout' => 0.5,
			'timeout' => 500_000,
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
		} else {
			$client = new Memcached();
		}

		$this->initializeClient( $client, $params );

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
	 *
	 * @throws RuntimeException
	 */
	private function initializeClient( Memcached $client, array $params ) {
		if ( $client->getServerList() ) {
			$this->logger->debug( __METHOD__ . ": pre-initialized client instance." );

			return; // preserve persistent handle
		}

		$this->logger->debug( __METHOD__ . ": initializing new client instance." );

		$options = [
			Memcached::OPT_NO_BLOCK => false,
			Memcached::OPT_BUFFER_WRITES => false,
			Memcached::OPT_NOREPLY => false,
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

	/**
	 * If $flags is true or is an integer with the WRITE_BACKGROUND bit set,
	 * enable no-reply mode, and disable it when the scope object is destroyed.
	 * This makes writes much faster.
	 *
	 * @param bool|int $flags
	 *
	 * @return ScopedCallback|null
	 */
	private function noReplyScope( $flags ) {
		if ( $flags !== true && !( $flags & self::WRITE_BACKGROUND ) ) {
			return null;
		}
		$client = $this->client;
		$client->setOption( Memcached::OPT_NOREPLY, true );

		return new ScopedCallback( static function () use ( $client ) {
			$client->setOption( Memcached::OPT_NOREPLY, false );
		} );
	}

	/** @inheritDoc */
	protected function doGet( $key, $flags = 0, &$casToken = null ) {
		$getToken = ( $casToken === self::PASS_BY_REF );
		$casToken = null;

		$this->debug( "get($key)" );

		$routeKey = $this->validateKeyAndPrependRoute( $key );

		// T257003: only require "gets" (instead of "get") when a CAS token is needed
		if ( $getToken ) {
			/** @noinspection PhpUndefinedClassConstantInspection */
			$flags = Memcached::GET_EXTENDED;
			$res = $this->client->get( $routeKey, null, $flags );
			if ( is_array( $res ) ) {
				$result = $res['value'];
				$casToken = $res['cas'];
			} else {
				$result = false;
			}
		} else {
			$result = $this->client->get( $routeKey );
		}

		return $this->checkResult( $key, $result );
	}

	/** @inheritDoc */
	protected function doSet( $key, $value, $exptime = 0, $flags = 0 ) {
		$this->debug( "set($key)" );

		$routeKey = $this->validateKeyAndPrependRoute( $key );

		$noReplyScope = $this->noReplyScope( $flags );
		$result = $this->client->set( $routeKey, $value, $this->fixExpiry( $exptime ) );
		ScopedCallback::consume( $noReplyScope );

		return ( !$result && $this->client->getResultCode() === Memcached::RES_NOTSTORED )
			// "Not stored" is always used as the mcrouter response with AllAsyncRoute
			? true
			: $this->checkResult( $key, $result );
	}

	/** @inheritDoc */
	protected function doCas( $casToken, $key, $value, $exptime = 0, $flags = 0 ) {
		$this->debug( "cas($key)" );

		$routeKey = $this->validateKeyAndPrependRoute( $key );
		$result = $this->client->cas(
			$casToken,
			$routeKey,
			$value, $this->fixExpiry( $exptime )
		);

		return $this->checkResult( $key, $result );
	}

	/** @inheritDoc */
	protected function doDelete( $key, $flags = 0 ) {
		$this->debug( "delete($key)" );

		$routeKey = $this->validateKeyAndPrependRoute( $key );
		$noReplyScope = $this->noReplyScope( $flags );
		$result = $this->client->delete( $routeKey );
		ScopedCallback::consume( $noReplyScope );

		return ( !$result && $this->client->getResultCode() === Memcached::RES_NOTFOUND )
			// "Not found" is counted as success in our interface
			? true
			: $this->checkResult( $key, $result );
	}

	/** @inheritDoc */
	protected function doAdd( $key, $value, $exptime = 0, $flags = 0 ) {
		$this->debug( "add($key)" );

		$routeKey = $this->validateKeyAndPrependRoute( $key );
		$noReplyScope = $this->noReplyScope( $flags );
		$result = $this->client->add(
			$routeKey,
			$value,
			$this->fixExpiry( $exptime )
		);
		ScopedCallback::consume( $noReplyScope );

		return $this->checkResult( $key, $result );
	}

	/** @inheritDoc */
	protected function doIncrWithInitAsync( $key, $exptime, $step, $init ) {
		$this->debug( "incrWithInit($key)" );
		$routeKey = $this->validateKeyAndPrependRoute( $key );
		$watchPoint = $this->watchErrors();
		$scope = $this->noReplyScope( true );
		$this->checkResult( $key, $this->client->add( $routeKey, $init - $step, $this->fixExpiry( $exptime ) ) );
		$this->checkResult( $key, $this->client->increment( $routeKey, $step ) );
		ScopedCallback::consume( $scope );
		$lastError = $this->getLastError( $watchPoint );

		return !$lastError;
	}

	/** @inheritDoc */
	protected function doIncrWithInitSync( $key, $exptime, $step, $init ) {
		$this->debug( "incrWithInit($key)" );
		$routeKey = $this->validateKeyAndPrependRoute( $key );
		$watchPoint = $this->watchErrors();
		$result = $this->client->increment( $routeKey, $step );
		$newValue = $this->checkResult( $key, $result );
		if ( $newValue === false && !$this->getLastError( $watchPoint ) ) {
			// No key set; initialize
			$result = $this->client->add( $routeKey, $init, $this->fixExpiry( $exptime ) );
			$newValue = $this->checkResult( $key, $result ) ? $init : false;
			if ( $newValue === false && !$this->getLastError( $watchPoint ) ) {
				// Raced out initializing; increment
				$result = $this->client->increment( $routeKey, $step );
				$newValue = $this->checkResult( $key, $result );
			}
		}

		return $newValue;
	}

	/**
	 * Check the return value from a client method call and take any necessary
	 * action. Returns the value that the wrapper function should return. At
	 * present, the return value is always the same as the return value from
	 * the client, but some day we might find a case where it should be
	 * different.
	 *
	 * @param string|false $key The key used by the caller, or false if there wasn't one.
	 * @param mixed $result The return value
	 *
	 * @return mixed
	 */
	protected function checkResult( $key, $result ) {
		static $statusByCode = [
			Memcached::RES_HOST_LOOKUP_FAILURE => self::ERR_UNREACHABLE,
			Memcached::RES_SERVER_MARKED_DEAD => self::ERR_UNREACHABLE,
			Memcached::RES_SERVER_TEMPORARILY_DISABLED => self::ERR_UNREACHABLE,
			Memcached::RES_UNKNOWN_READ_FAILURE => self::ERR_NO_RESPONSE,
			Memcached::RES_WRITE_FAILURE => self::ERR_NO_RESPONSE,
			Memcached::RES_PARTIAL_READ => self::ERR_NO_RESPONSE,
			// Hard-code values that only exist in recent versions of the PECL extension.
			// https://github.com/JetBrains/phpstorm-stubs/blob/master/memcached/memcached.php
			3 /* Memcached::RES_CONNECTION_FAILURE */ => self::ERR_UNREACHABLE,
			27 /* Memcached::RES_FAIL_UNIX_SOCKET */ => self::ERR_UNREACHABLE,
			6 /* Memcached::RES_READ_FAILURE */ => self::ERR_NO_RESPONSE
		];

		if ( $result !== false ) {
			return $result;
		}

		$client = $this->client;
		$code = $client->getResultCode();
		switch ( $code ) {
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
				$this->setLastError( $statusByCode[$code] ?? self::ERR_UNEXPECTED );
		}

		return $result;
	}

	/** @inheritDoc */
	protected function doGetMulti( array $keys, $flags = 0 ) {
		$this->debug( 'getMulti(' . implode( ', ', $keys ) . ')' );

		$routeKeys = [];
		foreach ( $keys as $key ) {
			$routeKeys[] = $this->validateKeyAndPrependRoute( $key );
		}

		// The PECL implementation uses multi-key "get"/"gets"; no need to pipeline.
		// T257003: avoid Memcached::GET_EXTENDED; no tokens are needed and that requires "gets"
		// https://github.com/libmemcached/libmemcached/blob/eda2becbec24363f56115fa5d16d38a2d1f54775/libmemcached/get.cc#L272
		$resByRouteKey = $this->client->getMulti( $routeKeys );

		if ( is_array( $resByRouteKey ) ) {
			$res = [];
			foreach ( $resByRouteKey as $routeKey => $value ) {
				$res[$this->stripRouteFromKey( $routeKey )] = $value;
			}
		} else {
			$res = false;
		}

		$res = $this->checkResult( false, $res );

		return $res !== false ? $res : [];
	}

	/** @inheritDoc */
	protected function doSetMulti( array $data, $exptime = 0, $flags = 0 ) {
		$this->debug( 'setMulti(' . implode( ', ', array_keys( $data ) ) . ')' );

		$exptime = $this->fixExpiry( $exptime );
		$dataByRouteKey = [];
		foreach ( $data as $key => $value ) {
			$dataByRouteKey[$this->validateKeyAndPrependRoute( $key )] = $value;
		}

		$noReplyScope = $this->noReplyScope( $flags );

		// Ignore "failed to set" warning from php-memcached 3.x (T251450)
		// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
		$result = @$this->client->setMulti( $dataByRouteKey, $exptime );
		ScopedCallback::consume( $noReplyScope );

		return $this->checkResult( false, $result );
	}

	/** @inheritDoc */
	protected function doDeleteMulti( array $keys, $flags = 0 ) {
		$this->debug( 'deleteMulti(' . implode( ', ', $keys ) . ')' );

		$routeKeys = [];
		foreach ( $keys as $key ) {
			$routeKeys[] = $this->validateKeyAndPrependRoute( $key );
		}

		$noReplyScope = $this->noReplyScope( $flags );
		$resultArray = $this->client->deleteMulti( $routeKeys ) ?: [];
		ScopedCallback::consume( $noReplyScope );

		$result = true;
		foreach ( $resultArray as $code ) {
			if ( !in_array( $code, [ true, Memcached::RES_NOTFOUND ], true ) ) {
				// "Not found" is counted as success in our interface
				$result = false;
			}
		}

		return $this->checkResult( false, $result );
	}

	/** @inheritDoc */
	protected function doChangeTTL( $key, $exptime, $flags ) {
		$this->debug( "touch($key)" );

		$routeKey = $this->validateKeyAndPrependRoute( $key );
		// Avoid NO_REPLY due to libmemcached hang
		// https://phabricator.wikimedia.org/T310662#8031692
		$result = $this->client->touch( $routeKey, $this->fixExpiry( $exptime ) );

		return $this->checkResult( $key, $result );
	}

	/** @inheritDoc */
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

	/** @inheritDoc */
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

/** @deprecated class alias since 1.43 */
class_alias( MemcachedPeclBagOStuff::class, 'MemcachedPeclBagOStuff' );
