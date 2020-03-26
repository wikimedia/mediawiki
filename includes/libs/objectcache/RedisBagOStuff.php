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
 * Redis-based caching module for redis server >= 2.6.12 and phpredis >= 2.2.4
 *
 * @see https://github.com/phpredis/phpredis/blob/d310ed7c8/Changelog.md
 * @note Avoid use of Redis::MULTI transactions for twemproxy support
 *
 * @ingroup Cache
 * @ingroup Redis
 * @phan-file-suppress PhanTypeComparisonFromArray It's unclear whether exec() can return false
 */
class RedisBagOStuff extends MediumSpecificBagOStuff {
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
	public function __construct( $params ) {
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

		$this->automaticFailover = $params['automaticFailover'] ?? true;

		$this->attrMap[self::ATTR_SYNCWRITES] = self::QOS_SYNCWRITES_NONE;
	}

	protected function doGet( $key, $flags = 0, &$casToken = null ) {
		$casToken = null;

		$conn = $this->getConnection( $key );
		if ( !$conn ) {
			return false;
		}

		$e = null;
		try {
			$value = $conn->get( $key );
			$casToken = $value;
			$result = $this->unserialize( $value );
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $conn, $e );
		}

		$this->logRequest( 'get', $key, $conn->getServer(), $e );

		return $result;
	}

	protected function doSet( $key, $value, $exptime = 0, $flags = 0 ) {
		$conn = $this->getConnection( $key );
		if ( !$conn ) {
			return false;
		}

		$ttl = $this->getExpirationAsTTL( $exptime );

		$e = null;
		try {
			if ( $ttl ) {
				$result = $conn->setex( $key, $ttl, $this->getSerialized( $value, $key ) );
			} else {
				$result = $conn->set( $key, $this->getSerialized( $value, $key ) );
			}
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $conn, $e );
		}

		$this->logRequest( 'set', $key, $conn->getServer(), $e );

		return $result;
	}

	protected function doDelete( $key, $flags = 0 ) {
		$conn = $this->getConnection( $key );
		if ( !$conn ) {
			return false;
		}

		$e = null;
		try {
			// Note that redis does not return false if the key was not there
			$result = ( $conn->del( $key ) !== false );
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $conn, $e );
		}

		$this->logRequest( 'delete', $key, $conn->getServer(), $e );

		return $result;
	}

	protected function doGetMulti( array $keys, $flags = 0 ) {
		/** @var RedisConnRef[]|Redis[] $conns */
		$conns = [];
		$batches = [];
		foreach ( $keys as $key ) {
			$conn = $this->getConnection( $key );
			if ( $conn ) {
				$server = $conn->getServer();
				$conns[$server] = $conn;
				$batches[$server][] = $key;
			}
		}

		$result = [];
		foreach ( $batches as $server => $batchKeys ) {
			$conn = $conns[$server];

			$e = null;
			try {
				// Avoid mget() to reduce CPU hogging from a single request
				$conn->multi( Redis::PIPELINE );
				foreach ( $batchKeys as $key ) {
					$conn->get( $key );
				}
				$batchResult = $conn->exec();
				if ( $batchResult === false ) {
					$this->logRequest( 'get', implode( ',', $batchKeys ), $server, true );
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

			$this->logRequest( 'get', implode( ',', $batchKeys ), $server, $e );
		}

		return $result;
	}

	protected function doSetMulti( array $data, $exptime = 0, $flags = 0 ) {
		/** @var RedisConnRef[]|Redis[] $conns */
		$conns = [];
		$batches = [];
		foreach ( $data as $key => $value ) {
			$conn = $this->getConnection( $key );
			if ( $conn ) {
				$server = $conn->getServer();
				$conns[$server] = $conn;
				$batches[$server][] = $key;
			}
		}

		$ttl = $this->getExpirationAsTTL( $exptime );
		$op = $ttl ? 'setex' : 'set';

		$result = true;
		foreach ( $batches as $server => $batchKeys ) {
			$conn = $conns[$server];

			$e = null;
			try {
				// Avoid mset() to reduce CPU hogging from a single request
				$conn->multi( Redis::PIPELINE );
				foreach ( $batchKeys as $key ) {
					if ( $ttl ) {
						$conn->setex( $key, $ttl, $this->getSerialized( $data[$key], $key ) );
					} else {
						$conn->set( $key, $this->getSerialized( $data[$key], $key ) );
					}
				}
				$batchResult = $conn->exec();
				if ( $batchResult === false ) {
					$this->logRequest( $op, implode( ',', $batchKeys ), $server, true );
					continue;
				}
				$result = $result && !in_array( false, $batchResult, true );
			} catch ( RedisException $e ) {
				$this->handleException( $conn, $e );
				$result = false;
			}

			$this->logRequest( $op, implode( ',', $batchKeys ), $server, $e );
		}

		return $result;
	}

	protected function doDeleteMulti( array $keys, $flags = 0 ) {
		/** @var RedisConnRef[]|Redis[] $conns */
		$conns = [];
		$batches = [];
		foreach ( $keys as $key ) {
			$conn = $this->getConnection( $key );
			if ( $conn ) {
				$server = $conn->getServer();
				$conns[$server] = $conn;
				$batches[$server][] = $key;
			}
		}

		$result = true;
		foreach ( $batches as $server => $batchKeys ) {
			$conn = $conns[$server];

			$e = null;
			try {
				// Avoid delete() with array to reduce CPU hogging from a single request
				$conn->multi( Redis::PIPELINE );
				foreach ( $batchKeys as $key ) {
					$conn->del( $key );
				}
				$batchResult = $conn->exec();
				if ( $batchResult === false ) {
					$this->logRequest( 'delete', implode( ',', $batchKeys ), $server, true );
					continue;
				}
				// Note that redis does not return false if the key was not there
				$result = $result && !in_array( false, $batchResult, true );
			} catch ( RedisException $e ) {
				$this->handleException( $conn, $e );
				$result = false;
			}

			$this->logRequest( 'delete', implode( ',', $batchKeys ), $server, $e );
		}

		return $result;
	}

	public function changeTTLMulti( array $keys, $exptime, $flags = 0 ) {
		/** @var RedisConnRef[]|Redis[] $conns */
		$conns = [];
		$batches = [];
		foreach ( $keys as $key ) {
			$conn = $this->getConnection( $key );
			if ( $conn ) {
				$server = $conn->getServer();
				$conns[$server] = $conn;
				$batches[$server][] = $key;
			}
		}

		$relative = $this->isRelativeExpiration( $exptime );
		$op = ( $exptime == self::TTL_INDEFINITE )
			? 'persist'
			: ( $relative ? 'expire' : 'expireAt' );

		$result = true;
		foreach ( $batches as $server => $batchKeys ) {
			$conn = $conns[$server];

			$e = null;
			try {
				$conn->multi( Redis::PIPELINE );
				foreach ( $batchKeys as $key ) {
					if ( $exptime == self::TTL_INDEFINITE ) {
						$conn->persist( $key );
					} elseif ( $relative ) {
						$conn->expire( $key, $this->getExpirationAsTTL( $exptime ) );
					} else {
						$conn->expireAt( $key, $this->getExpirationAsTimestamp( $exptime ) );
					}
				}
				$batchResult = $conn->exec();
				if ( $batchResult === false ) {
					$this->logRequest( $op, implode( ',', $batchKeys ), $server, true );
					continue;
				}
				$result = in_array( false, $batchResult, true ) ? false : $result;
			} catch ( RedisException $e ) {
				$this->handleException( $conn, $e );
				$result = false;
			}

			$this->logRequest( $op, implode( ',', $batchKeys ), $server, $e );
		}

		return $result;
	}

	protected function doAdd( $key, $value, $expiry = 0, $flags = 0 ) {
		$conn = $this->getConnection( $key );
		if ( !$conn ) {
			return false;
		}

		$ttl = $this->getExpirationAsTTL( $expiry );
		try {
			$result = $conn->set(
				$key,
				$this->getSerialized( $value, $key ),
				$ttl ? [ 'nx', 'ex' => $ttl ] : [ 'nx' ]
			);
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $conn, $e );
		}

		$this->logRequest( 'add', $key, $conn->getServer(), $result );

		return $result;
	}

	public function incr( $key, $value = 1, $flags = 0 ) {
		$conn = $this->getConnection( $key );
		if ( !$conn ) {
			return false;
		}

		try {
			if ( !$conn->exists( $key ) ) {
				return false;
			}
			// @FIXME: on races, the key may have a 0 TTL
			$result = $conn->incrBy( $key, $value );
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $conn, $e );
		}

		$this->logRequest( 'incr', $key, $conn->getServer(), $result );

		return $result;
	}

	public function decr( $key, $value = 1, $flags = 0 ) {
		$conn = $this->getConnection( $key );
		if ( !$conn ) {
			return false;
		}

		try {
			if ( !$conn->exists( $key ) ) {
				return false;
			}
			// @FIXME: on races, the key may have a 0 TTL
			$result = $conn->decrBy( $key, $value );
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $conn, $e );
		}

		$this->logRequest( 'decr', $key, $conn->getServer(), $result );

		return $result;
	}

	protected function doChangeTTL( $key, $exptime, $flags ) {
		$conn = $this->getConnection( $key );
		if ( !$conn ) {
			return false;
		}

		$relative = $this->isRelativeExpiration( $exptime );
		try {
			if ( $exptime == self::TTL_INDEFINITE ) {
				$result = $conn->persist( $key );
				$this->logRequest( 'persist', $key, $conn->getServer(), $result );
			} elseif ( $relative ) {
				$result = $conn->expire( $key, $this->getExpirationAsTTL( $exptime ) );
				$this->logRequest( 'expire', $key, $conn->getServer(), $result );
			} else {
				$result = $conn->expireAt( $key, $this->getExpirationAsTimestamp( $exptime ) );
				$this->logRequest( 'expireAt', $key, $conn->getServer(), $result );
			}
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $conn, $e );
		}

		return $result;
	}

	/**
	 * @param string $key
	 * @return RedisConnRef|Redis|null Redis handle wrapper for the key or null on failure
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
					/** @var string[] $info */
					$info = $conn->info();
					if ( ( $info['master_link_status'] ?? null ) === 'down' ) {
						// If the master cannot be reached, fail-over to the next server.
						// If masters are in data-center A, and replica DBs in data-center B,
						// this helps avoid the case were fail-over happens in A but not
						// to the corresponding server in B (e.g. read/write mismatch).
						continue;
					}
				} catch ( RedisException $e ) {
					// Server is not accepting commands
					$this->redisPool->handleError( $conn, $e );
					continue;
				}
			}

			return $conn;
		}

		$this->setLastError( BagOStuff::ERR_UNREACHABLE );

		return null;
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
	 * @param RedisException $e
	 */
	protected function handleException( RedisConnRef $conn, RedisException $e ) {
		$this->setLastError( BagOStuff::ERR_UNEXPECTED );
		$this->redisPool->handleError( $conn, $e );
	}

	/**
	 * Send information about a single request to the debug log
	 * @param string $op
	 * @param string $keys
	 * @param string $server
	 * @param Exception|bool|null $e
	 */
	public function logRequest( $op, $keys, $server, $e = null ) {
		$this->debug( "$op($keys) on $server: " . ( $e ? "failure" : "success" ) );
	}
}
