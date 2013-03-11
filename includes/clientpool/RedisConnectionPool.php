<?php
/**
 * PhpRedis client connection pooling manager.
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
 * Helper class to manage redis connections using PhpRedis.
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
	// Settings for all connections in this pool
	protected $connectTimeout; // string; connection timeout
	protected $persistent; // bool; whether connections persist
	protected $password; // string; plaintext auth password
	protected $poolSize; // integer; maximum number of idle connections
	protected $serializer; // integer; the serializer to use (Redis::SERIALIZER_*)

	protected $idlePoolSize = 0; // integer; current idle pool size

	/** @var Array (server name => ((connection info array),...) */
	protected $connections = array();
	/** @var Array (server name => UNIX timestamp) */
	protected $downServers = array();

	/** @var Array */
	protected static $instances = array(); // (pool ID => RedisConnectionPool)

	const SERVER_DOWN_TTL = 30; // integer; seconds to cache servers as "down"

	/**
	 * $options include:
	 *   - connectTimeout : The timeout for new connections, in seconds.
	 *                      Optional, default is 1 second.
	 *   - persistent     : Set this to true to allow connections to persist across
	 *                      multiple web requests. False by default.
	 *   - poolSize       : Maximim number of idle connections. Default is 5.
	 *   - password       : The authentication password, will be sent to Redis in clear text.
	 *                      Optional, if it is unspecified, no AUTH command will be sent.
	 *   - serializer     : Set to "php" or "igbinary". Default is "php".
	 * @param array $options
	 */
	protected function __construct( array $options ) {
		if ( !extension_loaded( 'redis' ) ) {
			throw new MWException( __CLASS__. ' requires the phpredis extension: ' .
				'https://github.com/nicolasff/phpredis' );
		}
		$this->connectTimeout = isset( $options['connectTimeout'] )
			? $options['connectTimeout']
			: 1;
		$this->persistent = isset( $options['persistent'] )
			? $options['persistent']
			: false;
		$this->password = isset( $options['password'] )
			? $options['password']
			: null;
		$this->poolSize = isset( $options['poolSize'] )
			? $options['poolSize']
			: 5;
		if ( !isset( $options['serializer'] ) || $options['serializer'] === 'php' ) {
			$this->serializer = Redis::SERIALIZER_PHP;
		} elseif ( $options['serializer'] === 'igbinary' ) {
			$this->serializer = Redis::SERIALIZER_IGBINARY;
		} else {
			throw new MWException( "Invalid serializer specified." );
		}
	}

	/**
	 * @param $options Array
	 * @return RedisConnectionPool
	 */
	public static function singleton( array $options ) {
		// Map the options to a unique hash...
		$poolOptions = $options;
		unset( $poolOptions['poolSize'] ); // avoid pool fragmentation
		ksort( $poolOptions ); // normalize to avoid pool fragmentation
		$id = sha1( serialize( $poolOptions ) );
		// Initialize the object at the hash as needed...
		if ( !isset( self::$instances[$id] ) ) {
			self::$instances[$id] = new self( $options );
			wfDebug( "Creating a new " . __CLASS__ . " instance with id $id." );
		}
		// Simply grow the pool size if the existing one is too small
		$psize = isset( $options['poolSize'] ) ? $options['poolSize'] : 1; // size requested
		self::$instances[$id]->poolSize = max( $psize, self::$instances[$id]->poolSize );

		return self::$instances[$id];
	}

	/**
	 * Get a connection to a redis server. Based on code in RedisBagOStuff.php.
	 *
	 * @param $server string A hostname/port combination or the absolute path of a UNIX socket.
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
					( $this->downServers[$server] - $now ) . " seconds, can't get connection" );
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
				throw new MWException( __CLASS__.": invalid configured server \"$server\"" );
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
			wfDebugLog( 'redis', "Redis exception: " . $e->getMessage() . "\n" );
			return false;
		}

		if ( $conn ) {
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
	 * @param $server string
	 * @param $conn Redis
	 * @return boolean
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
	 *
	 * @return void
	 */
	protected function closeExcessIdleConections() {
		if ( $this->idlePoolSize <= $this->poolSize ) {
			return; // nothing to do
		}

		foreach ( $this->connections as $server => &$serverConnections ) {
			foreach ( $serverConnections as $key => &$connection ) {
				if ( $connection['free'] ) {
					unset( $serverConnections[$key] );
					if ( --$this->idlePoolSize <= $this->poolSize ) {
						return; // done
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
	 * @param $server string
	 * @param $conn RedisConnRef
	 * @param $e RedisException
	 * @return void
	 */
	public function handleException( $server, RedisConnRef $conn, RedisException $e ) {
		wfDebugLog( 'redis',
			"Redis exception on server $server: " . $e->getMessage() . "\n" );
		foreach ( $this->connections[$server] as $key => $connection ) {
			if ( $connection['conn'] === $conn ) {
				$this->idlePoolSize -= $connection['free'] ? 1 : 0;
				unset( $this->connections[$server][$key] );
				break;
			}
		}
	}
}

/**
 * Helper class to handle automatically marking connectons as reusable (via RAII pattern)
 *
 * @ingroup Redis
 * @since 1.21
 */
class RedisConnRef {
	/** @var RedisConnectionPool */
	protected $pool;

	protected $server; // string

	/** @var Redis */
	protected $conn;

	/**
	 * @param $pool RedisConnectionPool
	 * @param $server string
	 * @param $conn Redis
	 */
	public function __construct( RedisConnectionPool $pool, $server, Redis $conn ) {
		$this->pool = $pool;
		$this->server = $server;
		$this->conn = $conn;
	}

	public function __call( $name, $arguments ) {
		return call_user_func_array( array( $this->conn, $name ), $arguments );
	}

	function __destruct() {
		$this->pool->freeConnection( $this->server, $this->conn );
	}
}
