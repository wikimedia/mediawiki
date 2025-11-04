<?php

// @phan-file-suppress PhanTypeComparisonFromArray It's unclear whether exec() can return false

/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\ObjectCache;

use Exception;
use Redis;
use RedisException;
use Wikimedia\ArrayUtils\ArrayUtils;

/**
 * Store data in Redis.
 *
 * This requires the php-redis PECL extension (2.2.4 or later) and
 * a Redis server (2.6.12 or later).
 *
 * @see http://redis.io/
 * @see https://github.com/phpredis/phpredis/blob/d310ed7c8/Changelog.md
 *
 * @note Avoid use of Redis::MULTI transactions for twemproxy support
 *
 * @ingroup Cache
 * @ingroup Redis
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
	 *   - prefix: A prefix that is transparently inserted before all keys.
	 *     Optional, default is to not add any additional prefixes.
	 *
	 *   - automaticFailover: If this is false, then each key will be mapped to
	 *     a single server, and if that server is down, any requests for that key
	 *     will fail. If this is true, a connection failure will cause the client
	 *     to immediately try the next server in the list (as determined by a
	 *     consistent hashing algorithm). True by default. This has the
	 *     potential to create consistency issues if a server is slow enough to
	 *     flap, for example if it is in swap death.
	 *
	 * @param array $params
	 */
	public function __construct( $params ) {
		parent::__construct( $params );
		$redisConf = [ 'serializer' => 'none' ]; // manage that in this class
		foreach ( [ 'connectTimeout', 'persistent', 'password', 'prefix' ] as $opt ) {
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

		// ...and uses rdb snapshots (redis.conf default)
		$this->attrMap[self::ATTR_DURABILITY] = self::QOS_DURABILITY_DISK;
	}

	/** @inheritDoc */
	protected function doGet( $key, $flags = 0, &$casToken = null ) {
		$getToken = ( $casToken === self::PASS_BY_REF );
		$casToken = null;

		$conn = $this->getConnection( $key );
		if ( !$conn ) {
			return false;
		}

		$e = null;
		try {
			$blob = $conn->get( $key );
			if ( $blob !== false ) {
				$value = $this->unserialize( $blob );
				$valueSize = strlen( $blob );
			} else {
				$value = false;
				$valueSize = false;
			}
			if ( $getToken && $value !== false ) {
				$casToken = $blob;
			}
		} catch ( RedisException $e ) {
			$value = false;
			$valueSize = false;
			$this->handleException( $conn, $e );
		}

		$this->logRequest( 'get', $key, $conn->getServer(), $e );

		$this->updateOpStats( self::METRIC_OP_GET, [ $key => [ 0, $valueSize ] ] );

		return $value;
	}

	/** @inheritDoc */
	protected function doSet( $key, $value, $exptime = 0, $flags = 0 ) {
		$conn = $this->getConnection( $key );
		if ( !$conn ) {
			return false;
		}

		$ttl = $this->getExpirationAsTTL( $exptime );
		$serialized = $this->getSerialized( $value, $key );
		$valueSize = strlen( $serialized );

		$e = null;
		try {
			if ( $ttl ) {
				$result = $conn->setex( $key, $ttl, $serialized );
			} else {
				$result = $conn->set( $key, $serialized );
			}
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $conn, $e );
		}

		$this->logRequest( 'set', $key, $conn->getServer(), $e );

		$this->updateOpStats( self::METRIC_OP_SET, [ $key => [ $valueSize, 0 ] ] );

		return $result;
	}

	/** @inheritDoc */
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

		$this->updateOpStats( self::METRIC_OP_DELETE, [ $key ] );

		return $result;
	}

	/** @inheritDoc */
	protected function doGetMulti( array $keys, $flags = 0 ) {
		$blobsFound = [];

		[ $keysByServer, $connByServer ] = $this->getConnectionsForKeys( $keys );
		foreach ( $keysByServer as $server => $batchKeys ) {
			$conn = $connByServer[$server];

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

				foreach ( $batchResult as $i => $blob ) {
					if ( $blob !== false ) {
						$blobsFound[$batchKeys[$i]] = $blob;
					}
				}
			} catch ( RedisException $e ) {
				$this->handleException( $conn, $e );
			}

			$this->logRequest( 'get', implode( ',', $batchKeys ), $server, $e );
		}

		// Preserve the order of $keys
		$result = [];
		$valueSizesByKey = [];
		foreach ( $keys as $key ) {
			if ( array_key_exists( $key, $blobsFound ) ) {
				$blob = $blobsFound[$key];
				$value = $this->unserialize( $blob );
				if ( $value !== false ) {
					$result[$key] = $value;
				}
				$valueSize = strlen( $blob );
			} else {
				$valueSize = false;
			}
			$valueSizesByKey[$key] = [ 0, $valueSize ];
		}

		$this->updateOpStats( self::METRIC_OP_GET, $valueSizesByKey );

		return $result;
	}

	/** @inheritDoc */
	protected function doSetMulti( array $data, $exptime = 0, $flags = 0 ) {
		$ttl = $this->getExpirationAsTTL( $exptime );
		$op = $ttl ? 'setex' : 'set';

		$keys = array_keys( $data );
		$valueSizesByKey = [];

		[ $keysByServer, $connByServer, $result ] = $this->getConnectionsForKeys( $keys );
		foreach ( $keysByServer as $server => $batchKeys ) {
			$conn = $connByServer[$server];

			$e = null;
			try {
				// Avoid mset() to reduce CPU hogging from a single request
				$conn->multi( Redis::PIPELINE );
				foreach ( $batchKeys as $key ) {
					$serialized = $this->getSerialized( $data[$key], $key );
					if ( $ttl ) {
						$conn->setex( $key, $ttl, $serialized );
					} else {
						$conn->set( $key, $serialized );
					}
					$valueSizesByKey[$key] = [ strlen( $serialized ), 0 ];
				}
				$batchResult = $conn->exec();
				if ( $batchResult === false ) {
					$result = false;
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

		$this->updateOpStats( self::METRIC_OP_SET, $valueSizesByKey );

		return $result;
	}

	/** @inheritDoc */
	protected function doDeleteMulti( array $keys, $flags = 0 ) {
		[ $keysByServer, $connByServer, $result ] = $this->getConnectionsForKeys( $keys );
		foreach ( $keysByServer as $server => $batchKeys ) {
			$conn = $connByServer[$server];

			$e = null;
			try {
				// Avoid delete() with array to reduce CPU hogging from a single request
				$conn->multi( Redis::PIPELINE );
				foreach ( $batchKeys as $key ) {
					$conn->del( $key );
				}
				$batchResult = $conn->exec();
				if ( $batchResult === false ) {
					$result = false;
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

		$this->updateOpStats( self::METRIC_OP_DELETE, array_values( $keys ) );

		return $result;
	}

	/** @inheritDoc */
	public function doChangeTTLMulti( array $keys, $exptime, $flags = 0 ) {
		$relative = $this->isRelativeExpiration( $exptime );
		$op = ( $exptime == self::TTL_INDEFINITE )
			? 'persist'
			: ( $relative ? 'expire' : 'expireAt' );

		[ $keysByServer, $connByServer, $result ] = $this->getConnectionsForKeys( $keys );
		foreach ( $keysByServer as $server => $batchKeys ) {
			$conn = $connByServer[$server];

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
					$result = false;
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

		$this->updateOpStats( self::METRIC_OP_CHANGE_TTL, array_values( $keys ) );

		return $result;
	}

	/** @inheritDoc */
	protected function doAdd( $key, $value, $exptime = 0, $flags = 0 ) {
		$conn = $this->getConnection( $key );
		if ( !$conn ) {
			return false;
		}

		$ttl = $this->getExpirationAsTTL( $exptime );
		$serialized = $this->getSerialized( $value, $key );
		$valueSize = strlen( $serialized );

		try {
			$result = $conn->set(
				$key,
				$serialized,
				$ttl ? [ 'nx', 'ex' => $ttl ] : [ 'nx' ]
			);
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $conn, $e );
		}

		$this->logRequest( 'add', $key, $conn->getServer(), $result );

		$this->updateOpStats( self::METRIC_OP_ADD, [ $key => [ $valueSize, 0 ] ] );

		return $result;
	}

	/** @inheritDoc */
	protected function doIncrWithInit( $key, $exptime, $step, $init, $flags ) {
		$conn = $this->getConnection( $key );
		if ( !$conn ) {
			return false;
		}

		$ttl = $this->getExpirationAsTTL( $exptime );
		try {
			static $script =
			/** @lang Lua */
<<<LUA
			local key = KEYS[1]
			local ttl, step, init = unpack( ARGV )
			if redis.call( 'exists', key ) == 1 then
				return redis.call( 'incrBy', key, step )
			end
			if 1 * ttl ~= 0 then
				redis.call( 'setex', key, ttl, init )
			else
				redis.call( 'set', key, init )
			end
			return 1 * init
LUA;
			$result = $conn->luaEval( $script, [ $key, $ttl, $step, $init ], 1 );
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $conn, $e );
		}
		$this->logRequest( 'incrWithInit', $key, $conn->getServer(), $result );

		return $result;
	}

	/** @inheritDoc */
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

		$this->updateOpStats( self::METRIC_OP_CHANGE_TTL, [ $key ] );

		return $result;
	}

	/**
	 * @param string[] $keys
	 *
	 * @return array ((server => redis handle wrapper), (server => key batch), success)
	 * @phan-return array{0:array<string,string[]>,1:array<string,RedisConnRef|Redis>,2:bool}
	 */
	protected function getConnectionsForKeys( array $keys ) {
		$keysByServer = [];
		$connByServer = [];
		$success = true;
		foreach ( $keys as $key ) {
			$candidateTags = $this->getCandidateServerTagsForKey( $key );

			$conn = null;
			// Find a suitable server for this key...
			// phpcs:ignore Generic.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition
			while ( ( $tag = array_shift( $candidateTags ) ) !== null ) {
				$server = $this->serverTagMap[$tag];
				// Reuse connection handles for keys mapping to the same server
				if ( isset( $connByServer[$server] ) ) {
					$conn = $connByServer[$server];
				} else {
					$conn = $this->redisPool->getConnection( $server, $this->logger );
					if ( !$conn ) {
						continue;
					}
					// If automatic failover is enabled, check that the server's link
					// to its master (if any) is up -- but only if there are other
					// viable candidates left to consider. Also, getMasterLinkStatus()
					// does not work with twemproxy, though $candidates will be empty
					// by now in such cases.
					if ( $this->automaticFailover && $candidateTags ) {
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
					// Use this connection handle
					$connByServer[$server] = $conn;
				}
				// Use this server for this key
				$keysByServer[$server][] = $key;
				break;
			}

			if ( !$conn ) {
				// No suitable server found for this key
				$success = false;
				$this->setLastError( BagOStuff::ERR_UNREACHABLE );
			}
		}

		return [ $keysByServer, $connByServer, $success ];
	}

	/**
	 * @param string $key
	 *
	 * @return RedisConnRef|Redis|null Redis handle wrapper for the key or null on failure
	 */
	protected function getConnection( $key ) {
		[ , $connByServer ] = $this->getConnectionsForKeys( [ $key ] );

		return reset( $connByServer ) ?: null;
	}

	private function getCandidateServerTagsForKey( string $key ): array {
		$candidates = array_keys( $this->serverTagMap );

		if ( count( $this->servers ) > 1 ) {
			ArrayUtils::consistentHashSort( $candidates, $key, '/' );
			if ( !$this->automaticFailover ) {
				$candidates = array_slice( $candidates, 0, 1 );
			}
		}

		return $candidates;
	}

	/**
	 * Log a fatal error
	 *
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
	 *
	 * @param RedisConnRef $conn
	 * @param RedisException $e
	 */
	protected function handleException( RedisConnRef $conn, RedisException $e ) {
		$this->setLastError( BagOStuff::ERR_UNEXPECTED );
		$this->redisPool->handleError( $conn, $e );
	}

	/**
	 * Send information about a single request to the debug log
	 *
	 * @param string $op
	 * @param string $keys
	 * @param string $server
	 * @param Exception|bool|null $e
	 */
	public function logRequest( $op, $keys, $server, $e = null ) {
		$this->debug( "$op($keys) on $server: " . ( $e ? "failure" : "success" ) );
	}
}

/** @deprecated class alias since 1.43 */
class_alias( RedisBagOStuff::class, 'RedisBagOStuff' );
