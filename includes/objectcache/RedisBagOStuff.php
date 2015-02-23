<?php
/**
 * Object caching using Redis (http://redis.io/).
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
 */

class RedisBagOStuff extends BagOStuff {
	/** @var RedisConnectionPool */
	protected $redisPool;
	/** @var array List of server names */
	protected $servers;
	/** @var bool */
	protected $automaticFailover;

	/**
	 * Construct a RedisBagOStuff object. Parameters are:
	 *
	 *   - servers: An array of server names. A server name may be a hostname,
	 *     a hostname/port combination or the absolute path of a UNIX socket.
	 *     If a hostname is specified but no port, the standard port number
	 *     6379 will be used. Required.
	 *
	 *   - connectTimeout: The timeout for new connections, in seconds. Optional,
	 *     default is 1 second.
	 *
	 *   - persistent: Set this to true to allow connections to persist across
	 *     multiple web requests. False by default.
	 *
	 *   - password: The authentication password, will be sent to Redis in
	 *     clear text. Optional, if it is unspecified, no AUTH command will be
	 *     sent.
	 *
	 *   - automaticFailover: If this is false, then each key will be mapped to
	 *     a single server, and if that server is down, any requests for that key
	 *     will fail. If this is true, a connection failure will cause the client
	 *     to immediately try the next server in the list (as determined by a
	 *     consistent hashing algorithm). True by default. This has the
	 *     potential to create consistency issues if a server is slow enough to
	 *     flap, for example if it is in swap death.
	 * @param array $params
	 */
	function __construct( $params ) {
		parent::__construct( $params );
		$redisConf = array( 'serializer' => 'none' ); // manage that in this class
		foreach ( array( 'connectTimeout', 'persistent', 'password' ) as $opt ) {
			if ( isset( $params[$opt] ) ) {
				$redisConf[$opt] = $params[$opt];
			}
		}
		$this->redisPool = RedisConnectionPool::singleton( $redisConf );

		$this->servers = $params['servers'];
		if ( isset( $params['automaticFailover'] ) ) {
			$this->automaticFailover = $params['automaticFailover'];
		} else {
			$this->automaticFailover = true;
		}
	}

	public function get( $key, &$casToken = null ) {

		list( $server, $conn ) = $this->getConnection( $key );
		if ( !$conn ) {
			return false;
		}
		try {
			$value = $conn->get( $key );
			$casToken = $value;
			$result = $this->unserialize( $value );
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $conn, $e );
		}

		$this->logRequest( 'get', $key, $server, $result );
		return $result;
	}

	public function set( $key, $value, $expiry = 0 ) {

		list( $server, $conn ) = $this->getConnection( $key );
		if ( !$conn ) {
			return false;
		}
		$expiry = $this->convertToRelative( $expiry );
		try {
			if ( $expiry ) {
				$result = $conn->setex( $key, $expiry, $this->serialize( $value ) );
			} else {
				// No expiry, that is very different from zero expiry in Redis
				$result = $conn->set( $key, $this->serialize( $value ) );
			}
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $conn, $e );
		}

		$this->logRequest( 'set', $key, $server, $result );
		return $result;
	}

	protected function cas( $casToken, $key, $value, $expiry = 0 ) {

		list( $server, $conn ) = $this->getConnection( $key );
		if ( !$conn ) {
			return false;
		}
		$expiry = $this->convertToRelative( $expiry );
		try {
			$conn->watch( $key );

			if ( $this->serialize( $this->get( $key ) ) !== $casToken ) {
				$conn->unwatch();
				return false;
			}

			// multi()/exec() will fail atomically if the key changed since watch()
			$conn->multi();
			if ( $expiry ) {
				$conn->setex( $key, $expiry, $this->serialize( $value ) );
			} else {
				// No expiry, that is very different from zero expiry in Redis
				$conn->set( $key, $this->serialize( $value ) );
			}
			$result = ( $conn->exec() == array( true ) );
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $conn, $e );
		}

		$this->logRequest( 'cas', $key, $server, $result );
		return $result;
	}

	public function delete( $key ) {

		list( $server, $conn ) = $this->getConnection( $key );
		if ( !$conn ) {
			return false;
		}
		try {
			$conn->delete( $key );
			// Return true even if the key didn't exist
			$result = true;
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $conn, $e );
		}

		$this->logRequest( 'delete', $key, $server, $result );
		return $result;
	}

	public function getMulti( array $keys ) {

		$batches = array();
		$conns = array();
		foreach ( $keys as $key ) {
			list( $server, $conn ) = $this->getConnection( $key );
			if ( !$conn ) {
				continue;
			}
			$conns[$server] = $conn;
			$batches[$server][] = $key;
		}
		$result = array();
		foreach ( $batches as $server => $batchKeys ) {
			$conn = $conns[$server];
			try {
				$conn->multi( Redis::PIPELINE );
				foreach ( $batchKeys as $key ) {
					$conn->get( $key );
				}
				$batchResult = $conn->exec();
				if ( $batchResult === false ) {
					$this->debug( "multi request to $server failed" );
					continue;
				}
				foreach ( $batchResult as $i => $value ) {
					if ( $value !== false ) {
						$result[$batchKeys[$i]] = $this->unserialize( $value );
					}
				}
			} catch ( RedisException $e ) {
				$this->handleException( $conn, $e );
			}
		}

		$this->debug( "getMulti for " . count( $keys ) . " keys " .
			"returned " . count( $result ) . " results" );
		return $result;
	}

	/**
	 * @param array $data
	 * @param int $expiry
	 * @return bool
	 */
	public function setMulti( array $data, $expiry = 0 ) {

		$batches = array();
		$conns = array();
		foreach ( $data as $key => $value ) {
			list( $server, $conn ) = $this->getConnection( $key );
			if ( !$conn ) {
				continue;
			}
			$conns[$server] = $conn;
			$batches[$server][] = $key;
		}

		$expiry = $this->convertToRelative( $expiry );
		$result = true;
		foreach ( $batches as $server => $batchKeys ) {
			$conn = $conns[$server];
			try {
				$conn->multi( Redis::PIPELINE );
				foreach ( $batchKeys as $key ) {
					if ( $expiry ) {
						$conn->setex( $key, $expiry, $this->serialize( $data[$key] ) );
					} else {
						$conn->set( $key, $this->serialize( $data[$key] ) );
					}
				}
				$batchResult = $conn->exec();
				if ( $batchResult === false ) {
					$this->debug( "setMulti request to $server failed" );
					continue;
				}
				foreach ( $batchResult as $value ) {
					if ( $value === false ) {
						$result = false;
					}
				}
			} catch ( RedisException $e ) {
				$this->handleException( $server, $conn, $e );
				$result = false;
			}
		}

		return $result;
	}



	public function add( $key, $value, $expiry = 0 ) {

		list( $server, $conn ) = $this->getConnection( $key );
		if ( !$conn ) {
			return false;
		}
		$expiry = $this->convertToRelative( $expiry );
		try {
			if ( $expiry ) {
				$conn->multi();
				$conn->setnx( $key, $this->serialize( $value ) );
				$conn->expire( $key, $expiry );
				$result = ( $conn->exec() == array( true, true ) );
			} else {
				$result = $conn->setnx( $key, $this->serialize( $value ) );
			}
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $conn, $e );
		}

		$this->logRequest( 'add', $key, $server, $result );
		return $result;
	}

	/**
	 * Non-atomic implementation of incr().
	 *
	 * Probably all callers actually want incr() to atomically initialise
	 * values to zero if they don't exist, as provided by the Redis INCR
	 * command. But we are constrained by the memcached-like interface to
	 * return null in that case. Once the key exists, further increments are
	 * atomic.
	 * @param string $key Key to increase
	 * @param int $value Value to add to $key (Default 1)
	 * @return int|bool New value or false on failure
	 */
	public function incr( $key, $value = 1 ) {

		list( $server, $conn ) = $this->getConnection( $key );
		if ( !$conn ) {
			return false;
		}
		if ( !$conn->exists( $key ) ) {
			return null;
		}
		try {
			$result = $conn->incrBy( $key, $value );
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $conn, $e );
		}

		$this->logRequest( 'incr', $key, $server, $result );
		return $result;
	}

	public function merge( $key, $callback, $exptime = 0, $attempts = 10 ) {
		if ( !is_callable( $callback ) ) {
			throw new Exception( "Got invalid callback." );
		}

		return $this->mergeViaCas( $key, $callback, $exptime, $attempts );
	}

	/**
	 * @param mixed $data
	 * @return string
	 */
	protected function serialize( $data ) {
		// Serialize anything but integers so INCR/DECR work
		// Do not store integer-like strings as integers to avoid type confusion (bug 60563)
		return is_int( $data ) ? $data : serialize( $data );
	}

	/**
	 * @param string $data
	 * @return mixed
	 */
	protected function unserialize( $data ) {
		return ctype_digit( $data ) ? intval( $data ) : unserialize( $data );
	}

	/**
	 * Get a Redis object with a connection suitable for fetching the specified key
	 * @param string $key
	 * @return array (server, RedisConnRef) or (false, false)
	 */
	protected function getConnection( $key ) {
		if ( count( $this->servers ) === 1 ) {
			$candidates = $this->servers;
		} else {
			$candidates = $this->servers;
			ArrayUtils::consistentHashSort( $candidates, $key, '/' );
			if ( !$this->automaticFailover ) {
				$candidates = array_slice( $candidates, 0, 1 );
			}
		}

		foreach ( $candidates as $server ) {
			$conn = $this->redisPool->getConnection( $server );
			if ( $conn ) {
				return array( $server, $conn );
			}
		}
		$this->setLastError( BagOStuff::ERR_UNREACHABLE );
		return array( false, false );
	}

	/**
	 * Log a fatal error
	 * @param string $msg
	 */
	protected function logError( $msg ) {
		$this->logger->error( "Redis error: $msg" );
	}

	/**
	 * The redis extension throws an exception in response to various read, write
	 * and protocol errors. Sometimes it also closes the connection, sometimes
	 * not. The safest response for us is to explicitly destroy the connection
	 * object and let it be reopened during the next request.
	 * @param RedisConnRef $conn
	 * @param Exception $e
	 */
	protected function handleException( RedisConnRef $conn, $e ) {
		$this->setLastError( BagOStuff::ERR_UNEXPECTED );
		$this->redisPool->handleError( $conn, $e );
	}

	/**
	 * Send information about a single request to the debug log
	 * @param string $method
	 * @param string $key
	 * @param string $server
	 * @param bool $result
	 */
	public function logRequest( $method, $key, $server, $result ) {
		$this->debug( "$method $key on $server: " .
			( $result === false ? "failure" : "success" ) );
	}
}
