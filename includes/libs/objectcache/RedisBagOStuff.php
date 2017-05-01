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

/**
 * Redis-based caching module for redis server >= 2.6.12
 *
 * @note: avoid use of Redis::MULTI transactions for twemproxy support
 */
class RedisBagOStuff extends BagOStuff {
	/** @var RedisConnectionPool */
	protected $redisPool;
	/** @var array List of server names */
	protected $servers;
	/** @var array Map of (tag => server name) */
	protected $serverTagMap;
	/** @var bool */
	protected $automaticFailover;

	/**
	 * Construct a RedisBagOStuff object. Parameters are:
	 *
	 *   - servers: An array of server names. A server name may be a hostname,
	 *     a hostname/port combination or the absolute path of a UNIX socket.
	 *     If a hostname is specified but no port, the standard port number
	 *     6379 will be used. Arrays keys can be used to specify the tag to
	 *     hash on in place of the host/port. Required.
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
		$redisConf = [ 'serializer' => 'none' ]; // manage that in this class
		foreach ( [ 'connectTimeout', 'persistent', 'password' ] as $opt ) {
			if ( isset( $params[$opt] ) ) {
				$redisConf[$opt] = $params[$opt];
			}
		}
		$this->redisPool = RedisConnectionPool::singleton( $redisConf );

		$this->servers = $params['servers'];
		foreach ( $this->servers as $key => $server ) {
			$this->serverTagMap[is_int( $key ) ? $server : $key] = $server;
		}

		if ( isset( $params['automaticFailover'] ) ) {
			$this->automaticFailover = $params['automaticFailover'];
		} else {
			$this->automaticFailover = true;
		}

		$this->attrMap[self::ATTR_SYNCWRITES] = self::QOS_SYNCWRITES_NONE;
	}

	protected function doGet( $key, $flags = 0 ) {
		list( $server, $conn ) = $this->getConnection( $key );
		if ( !$conn ) {
			return false;
		}
		try {
			$value = $conn->get( $key );
			$result = $this->unserialize( $value );
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $conn, $e );
		}

		$this->logRequest( 'get', $key, $server, $result );
		return $result;
	}

	public function set( $key, $value, $expiry = 0, $flags = 0 ) {
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

	public function getMulti( array $keys, $flags = 0 ) {
		$batches = [];
		$conns = [];
		foreach ( $keys as $key ) {
			list( $server, $conn ) = $this->getConnection( $key );
			if ( !$conn ) {
				continue;
			}
			$conns[$server] = $conn;
			$batches[$server][] = $key;
		}
		$result = [];
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
		$batches = [];
		$conns = [];
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
				$result = $conn->set(
					$key,
					$this->serialize( $value ),
					[ 'nx', 'ex' => $expiry ]
				);
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
		try {
			if ( !$conn->exists( $key ) ) {
				return null;
			}
			// @FIXME: on races, the key may have a 0 TTL
			$result = $conn->incrBy( $key, $value );
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $conn, $e );
		}

		$this->logRequest( 'incr', $key, $server, $result );
		return $result;
	}

	public function changeTTL( $key, $expiry = 0 ) {
		list( $server, $conn ) = $this->getConnection( $key );
		if ( !$conn ) {
			return false;
		}

		$expiry = $this->convertToRelative( $expiry );
		try {
			$result = $conn->expire( $key, $expiry );
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $conn, $e );
		}

		$this->logRequest( 'expire', $key, $server, $result );
		return $result;
	}

	public function modifySimpleRelayEvent( array $event ) {
		if ( array_key_exists( 'val', $event ) ) {
			$event['val'] = serialize( $event['val'] ); // this class uses PHP serialization
		}

		return $event;
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
		$int = intval( $data );
		return $data === (string)$int ? $int : unserialize( $data );
	}

	/**
	 * Get a Redis object with a connection suitable for fetching the specified key
	 * @param string $key
	 * @return array (server, RedisConnRef) or (false, false)
	 */
	protected function getConnection( $key ) {
		$candidates = array_keys( $this->serverTagMap );

		if ( count( $this->servers ) > 1 ) {
			ArrayUtils::consistentHashSort( $candidates, $key, '/' );
			if ( !$this->automaticFailover ) {
				$candidates = array_slice( $candidates, 0, 1 );
			}
		}

		while ( ( $tag = array_shift( $candidates ) ) !== null ) {
			$server = $this->serverTagMap[$tag];
			$conn = $this->redisPool->getConnection( $server, $this->logger );
			if ( !$conn ) {
				continue;
			}

			// If automatic failover is enabled, check that the server's link
			// to its master (if any) is up -- but only if there are other
			// viable candidates left to consider. Also, getMasterLinkStatus()
			// does not work with twemproxy, though $candidates will be empty
			// by now in such cases.
			if ( $this->automaticFailover && $candidates ) {
				try {
					if ( $this->getMasterLinkStatus( $conn ) === 'down' ) {
						// If the master cannot be reached, fail-over to the next server.
						// If masters are in data-center A, and replica DBs in data-center B,
						// this helps avoid the case were fail-over happens in A but not
						// to the corresponding server in B (e.g. read/write mismatch).
						continue;
					}
				} catch ( RedisException $e ) {
					// Server is not accepting commands
					$this->handleException( $conn, $e );
					continue;
				}
			}

			return [ $server, $conn ];
		}

		$this->setLastError( BagOStuff::ERR_UNREACHABLE );

		return [ false, false ];
	}

	/**
	 * Check the master link status of a Redis server that is configured as a replica DB.
	 * @param RedisConnRef $conn
	 * @return string|null Master link status (either 'up' or 'down'), or null
	 *  if the server is not a replica DB.
	 */
	protected function getMasterLinkStatus( RedisConnRef $conn ) {
		$info = $conn->info();
		return isset( $info['master_link_status'] )
			? $info['master_link_status']
			: null;
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
