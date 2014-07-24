<?php
/**
 * Redis client connection pooling manager.
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
 * @defgroup Redis Redis
 * @author Aaron Schulz
 */

/**
 * Helper class to manage Redis connections.
 *
 * This can be used to get handle wrappers that free the handle when the wrapper
 * leaves scope. The maximum number of free handles (connections) is configurable.
 * This provides an easy way to cache connection handles that may also have state,
 * such as a handle does between multi() and exec(), and without hoarding connections.
 * The wrappers use PHP magic methods so that calling functions on them calls the
 * function of the actual Redis object handle.
 *
 * @ingroup Redis
 * @since 1.21
 */
class RedisConnectionPool {
	/**
	 * @name Pool settings.
	 * Settings there are shared for any connection made in this pool.
	 * See the singleton() method documentation for more details.
	 * @{
	 */
	/** @var string Connection timeout in seconds */
	protected $connectTimeout;
	/** @var string Read timeout in seconds */
	protected $readTimeout;
	/** @var string Plaintext auth password */
	protected $password;
	/** @var bool Whether connections persist */
	protected $persistent;
	/** @var int Serializer to use (Redis::SERIALIZER_*) */
	protected $serializer;
	/** @} */

	/** @var int Current idle pool size */
	protected $idlePoolSize = 0;

	/** @var array (server name => ((connection info array),...) */
	protected $connections = array();
	/** @var array (server name => UNIX timestamp) */
	protected $downServers = array();

	/** @var array (pool ID => RedisConnectionPool) */
	protected static $instances = array();

	/** integer; seconds to cache servers as "down". */
	const SERVER_DOWN_TTL = 30;

	/**
	 * @param array $options
	 * @throws MWException
	 */
	protected function __construct( array $options ) {
		if ( !class_exists( 'Redis' ) ) {
			throw new MWException( __CLASS__ . ' requires a Redis client library. ' .
				'See https://www.mediawiki.org/wiki/Redis#Setup' );
		}
		$this->connectTimeout = $options['connectTimeout'];
		$this->readTimeout = $options['readTimeout'];
		$this->persistent = $options['persistent'];
		$this->password = $options['password'];
		if ( !isset( $options['serializer'] ) || $options['serializer'] === 'php' ) {
			$this->serializer = Redis::SERIALIZER_PHP;
		} elseif ( $options['serializer'] === 'igbinary' ) {
			$this->serializer = Redis::SERIALIZER_IGBINARY;
		} elseif ( $options['serializer'] === 'none' ) {
			$this->serializer = Redis::SERIALIZER_NONE;
		} else {
			throw new MWException( "Invalid serializer specified." );
		}
	}

	/**
	 * @param array $options
	 * @return array
	 */
	protected static function applyDefaultConfig( array $options ) {
		if ( !isset( $options['connectTimeout'] ) ) {
			$options['connectTimeout'] = 1;
		}
		if ( !isset( $options['readTimeout'] ) ) {
			$options['readTimeout'] = 1;
		}
		if ( !isset( $options['persistent'] ) ) {
			$options['persistent'] = false;
		}
		if ( !isset( $options['password'] ) ) {
			$options['password'] = null;
		}

		return $options;
	}

	/**
	 * @param array $options
	 * $options include:
	 *   - connectTimeout : The timeout for new connections, in seconds.
	 *                      Optional, default is 1 second.
	 *   - readTimeout    : The timeout for operation reads, in seconds.
	 *                      Commands like BLPOP can fail if told to wait longer than this.
	 *                      Optional, default is 1 second.
	 *   - persistent     : Set this to true to allow connections to persist across
	 *                      multiple web requests. False by default.
	 *   - password       : The authentication password, will be sent to Redis in clear text.
	 *                      Optional, if it is unspecified, no AUTH command will be sent.
	 *   - serializer     : Set to "php", "igbinary", or "none". Default is "php".
	 * @return RedisConnectionPool
	 */
	public static function singleton( array $options ) {
		$options = self::applyDefaultConfig( $options );
		// Map the options to a unique hash...
		ksort( $options ); // normalize to avoid pool fragmentation
		$id = sha1( serialize( $options ) );
		// Initialize the object at the hash as needed...
		if ( !isset( self::$instances[$id] ) ) {
			self::$instances[$id] = new self( $options );
			wfDebug( "Creating a new " . __CLASS__ . " instance with id $id.\n" );
		}

		return self::$instances[$id];
	}

	/**
	 * Get a connection to a redis server. Based on code in RedisBagOStuff.php.
	 *
	 * @param string $server A hostname/port combination or the absolute path of a UNIX socket.
	 *                       If a hostname is specified but no port, port 6379 will be used.
	 * @return RedisConnRef|bool Returns false on failure
	 * @throws MWException
	 */
	public function getConnection( $server ) {
		// Check the listing "dead" servers which have had a connection errors.
		// Servers are marked dead for a limited period of time, to
		// avoid excessive overhead from repeated connection timeouts.
		if ( isset( $this->downServers[$server] ) ) {
			$now = time();
			if ( $now > $this->downServers[$server] ) {
				// Dead time expired
				unset( $this->downServers[$server] );
			} else {
				// Server is dead
				wfDebug( "server $server is marked down for another " .
					( $this->downServers[$server] - $now ) . " seconds, can't get connection\n" );

				return false;
			}
		}

		// Check if a connection is already free for use
		if ( isset( $this->connections[$server] ) ) {
			foreach ( $this->connections[$server] as &$connection ) {
				if ( $connection['free'] ) {
					$connection['free'] = false;
					--$this->idlePoolSize;

					return new RedisConnRef( $this, $server, $connection['conn'] );
				}
			}
		}

		if ( substr( $server, 0, 1 ) === '/' ) {
			// UNIX domain socket
			// These are required by the redis extension to start with a slash, but
			// we still need to set the port to a special value to make it work.
			$host = $server;
			$port = 0;
		} else {
			// TCP connection
			$hostPort = IP::splitHostAndPort( $server );
			if ( !$hostPort ) {
				throw new MWException( __CLASS__ . ": invalid configured server \"$server\"" );
			}
			list( $host, $port ) = $hostPort;
			if ( $port === false ) {
				$port = 6379;
			}
		}

		$conn = new Redis();
		try {
			if ( $this->persistent ) {
				$result = $conn->pconnect( $host, $port, $this->connectTimeout );
			} else {
				$result = $conn->connect( $host, $port, $this->connectTimeout );
			}
			if ( !$result ) {
				wfDebugLog( 'redis', "Could not connect to server $server" );
				// Mark server down for some time to avoid further timeouts
				$this->downServers[$server] = time() + self::SERVER_DOWN_TTL;

				return false;
			}
			if ( $this->password !== null ) {
				if ( !$conn->auth( $this->password ) ) {
					wfDebugLog( 'redis', "Authentication error connecting to $server" );
				}
			}
		} catch ( RedisException $e ) {
			$this->downServers[$server] = time() + self::SERVER_DOWN_TTL;
			wfDebugLog( 'redis', "Redis exception connecting to $server: " . $e->getMessage() );

			return false;
		}

		if ( $conn ) {
			$conn->setOption( Redis::OPT_READ_TIMEOUT, $this->readTimeout );
			$conn->setOption( Redis::OPT_SERIALIZER, $this->serializer );
			$this->connections[$server][] = array( 'conn' => $conn, 'free' => false );

			return new RedisConnRef( $this, $server, $conn );
		} else {
			return false;
		}
	}

	/**
	 * Mark a connection to a server as free to return to the pool
	 *
	 * @param string $server
	 * @param Redis $conn
	 * @return bool
	 */
	public function freeConnection( $server, Redis $conn ) {
		$found = false;

		foreach ( $this->connections[$server] as &$connection ) {
			if ( $connection['conn'] === $conn && !$connection['free'] ) {
				$connection['free'] = true;
				++$this->idlePoolSize;
				break;
			}
		}

		$this->closeExcessIdleConections();

		return $found;
	}

	/**
	 * Close any extra idle connections if there are more than the limit
	 */
	protected function closeExcessIdleConections() {
		if ( $this->idlePoolSize <= count( $this->connections ) ) {
			return; // nothing to do (no more connections than servers)
		}

		foreach ( $this->connections as &$serverConnections ) {
			foreach ( $serverConnections as $key => &$connection ) {
				if ( $connection['free'] ) {
					unset( $serverConnections[$key] );
					if ( --$this->idlePoolSize <= count( $this->connections ) ) {
						return; // done (no more connections than servers)
					}
				}
			}
		}
	}

	/**
	 * The redis extension throws an exception in response to various read, write
	 * and protocol errors. Sometimes it also closes the connection, sometimes
	 * not. The safest response for us is to explicitly destroy the connection
	 * object and let it be reopened during the next request.
	 *
	 * @param string $server
	 * @param RedisConnRef $cref
	 * @param RedisException $e
	 * @deprecated since 1.23
	 */
	public function handleException( $server, RedisConnRef $cref, RedisException $e ) {
		return $this->handleError( $cref, $e );
	}

	/**
	 * The redis extension throws an exception in response to various read, write
	 * and protocol errors. Sometimes it also closes the connection, sometimes
	 * not. The safest response for us is to explicitly destroy the connection
	 * object and let it be reopened during the next request.
	 *
	 * @param RedisConnRef $cref
	 * @param RedisException $e
	 */
	public function handleError( RedisConnRef $cref, RedisException $e ) {
		$server = $cref->getServer();
		wfDebugLog( 'redis', "Redis exception on server $server: " . $e->getMessage() . "\n" );
		foreach ( $this->connections[$server] as $key => $connection ) {
			if ( $cref->isConnIdentical( $connection['conn'] ) ) {
				$this->idlePoolSize -= $connection['free'] ? 1 : 0;
				unset( $this->connections[$server][$key] );
				break;
			}
		}
	}

	/**
	 * Re-send an AUTH request to the redis server (useful after disconnects).
	 *
	 * This works around an upstream bug in phpredis. phpredis hides disconnects by transparently
	 * reconnecting, but it neglects to re-authenticate the new connection. To the user of the
	 * phpredis client API this manifests as a seemingly random tendency of connections to lose
	 * their authentication status.
	 *
	 * This method is for internal use only.
	 *
	 * @see https://github.com/nicolasff/phpredis/issues/403
	 *
	 * @param string $server
	 * @param Redis $conn
	 * @return bool Success
	 */
	public function reauthenticateConnection( $server, Redis $conn ) {
		if ( $this->password !== null ) {
			if ( !$conn->auth( $this->password ) ) {
				wfDebugLog( 'redis', "Authentication error connecting to $server" );

				return false;
			}
		}

		return true;
	}

	/**
	 * Adjust or reset the connection handle read timeout value
	 *
	 * @param Redis $conn
	 * @param int $timeout Optional
	 */
	public function resetTimeout( Redis $conn, $timeout = null ) {
		$conn->setOption( Redis::OPT_READ_TIMEOUT, $timeout ?: $this->readTimeout );
	}

	/**
	 * Make sure connections are closed for sanity
	 */
	function __destruct() {
		foreach ( $this->connections as $server => &$serverConnections ) {
			foreach ( $serverConnections as $key => &$connection ) {
				$connection['conn']->close();
			}
		}
	}
}

/**
 * Helper class to handle automatically marking connectons as reusable (via RAII pattern)
 *
 * This class simply wraps the Redis class and can be used the same way
 *
 * @ingroup Redis
 * @since 1.21
 */
class RedisConnRef {
	/** @var RedisConnectionPool */
	protected $pool;
	/** @var Redis */
	protected $conn;

	protected $server; // string
	protected $lastError; // string

	/**
	 * @param RedisConnectionPool $pool
	 * @param string $server
	 * @param Redis $conn
	 */
	public function __construct( RedisConnectionPool $pool, $server, Redis $conn ) {
		$this->pool = $pool;
		$this->server = $server;
		$this->conn = $conn;
	}

	/**
	 * @return string
	 * @since 1.23
	 */
	public function getServer() {
		return $this->server;
	}

	public function getLastError() {
		return $this->lastError;
	}

	public function clearLastError() {
		$this->lastError = null;
	}

	public function __call( $name, $arguments ) {
		$conn = $this->conn; // convenience

		// Work around https://github.com/nicolasff/phpredis/issues/70
		$lname = strtolower( $name );
		if ( ( $lname === 'blpop' || $lname == 'brpop' )
			&& is_array( $arguments[0] ) && isset( $arguments[1] )
		) {
			$this->pool->resetTimeout( $conn, $arguments[1] + 1 );
		} elseif ( $lname === 'brpoplpush' && isset( $arguments[2] ) ) {
			$this->pool->resetTimeout( $conn, $arguments[2] + 1 );
		}

		$conn->clearLastError();
		try {
			$res = call_user_func_array( array( $conn, $name ), $arguments );
			if ( preg_match( '/^ERR operation not permitted\b/', $conn->getLastError() ) ) {
				$this->pool->reauthenticateConnection( $this->server, $conn );
				$conn->clearLastError();
				$res = call_user_func_array( array( $conn, $name ), $arguments );
				wfDebugLog( 'redis', "Used automatic re-authentication for method '$name'." );
			}
		} catch ( RedisException $e ) {
			$this->pool->resetTimeout( $conn ); // restore
			throw $e;
		}

		$this->lastError = $conn->getLastError() ?: $this->lastError;

		$this->pool->resetTimeout( $conn ); // restore

		return $res;
	}

	/**
	 * @param string $script
	 * @param array $params
	 * @param int $numKeys
	 * @return mixed
	 * @throws RedisException
	 */
	public function luaEval( $script, array $params, $numKeys ) {
		$sha1 = sha1( $script ); // 40 char hex
		$conn = $this->conn; // convenience
		$server = $this->server; // convenience

		// Try to run the server-side cached copy of the script
		$conn->clearLastError();
		$res = $conn->evalSha( $sha1, $params, $numKeys );
		// If we got a permission error reply that means that (a) we are not in
		// multi()/pipeline() and (b) some connection problem likely occurred. If
		// the password the client gave was just wrong, an exception should have
		// been thrown back in getConnection() previously.
		if ( preg_match( '/^ERR operation not permitted\b/', $conn->getLastError() ) ) {
			$this->pool->reauthenticateConnection( $server, $conn );
			$conn->clearLastError();
			$res = $conn->eval( $script, $params, $numKeys );
			wfDebugLog( 'redis', "Used automatic re-authentication for Lua script $sha1." );
		}
		// If the script is not in cache, use eval() to retry and cache it
		if ( preg_match( '/^NOSCRIPT/', $conn->getLastError() ) ) {
			$conn->clearLastError();
			$res = $conn->eval( $script, $params, $numKeys );
			wfDebugLog( 'redis', "Used eval() for Lua script $sha1." );
		}

		if ( $conn->getLastError() ) { // script bug?
			wfDebugLog( 'redis', "Lua script error on server $server: " . $conn->getLastError() );
		}

		$this->lastError = $conn->getLastError() ?: $this->lastError;

		return $res;
	}

	/**
	 * @param Redis $conn
	 * @return bool
	 */
	public function isConnIdentical( Redis $conn ) {
		return $this->conn === $conn;
	}

	function __destruct() {
		$this->pool->freeConnection( $this->server, $this->conn );
	}
}
