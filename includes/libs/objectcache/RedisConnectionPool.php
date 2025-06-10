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

use Exception;
use InvalidArgumentException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Redis;
use RedisException;
use RuntimeException;

/**
 * Manage one or more Redis client connection.
 *
 * This can be used to get RedisConnRef objects that automatically reuses
 * connections internally after the calling function has returned (and thus
 * your RedisConnRef instance leaves scope/destructs).
 *
 * This provides an easy way to cache connection handles that may also have state,
 * such as a handle does between multi() and exec(), and without hoarding connections.
 * The wrappers use PHP magic methods so that calling functions on them calls the
 * function of the actual Redis object handle.
 *
 * @ingroup Cache
 * @since 1.21
 */
class RedisConnectionPool implements LoggerAwareInterface {
	/** @var int Connection timeout in seconds */
	protected $connectTimeout;
	/** @var string Read timeout in seconds */
	protected $readTimeout;
	/** @var string|string[]|null Plaintext auth password or array containing username and password */
	protected $password;
	/** @var string|null Key prefix automatically added to all operations */
	protected $prefix;
	/** @var bool Whether connections persist */
	protected $persistent;
	/** @var int Serializer to use (Redis::SERIALIZER_*) */
	protected $serializer;
	/** @var string ID for persistent connections */
	protected $id;

	/** @var int Current idle pool size */
	protected $idlePoolSize = 0;

	/**
	 * @var array (server name => ((connection info array),...)
	 * @phan-var array<string,array{conn:Redis,free:bool}[]>
	 */
	protected $connections = [];
	/** @var array (server name => UNIX timestamp) */
	protected $downServers = [];

	/** @var array (pool ID => RedisConnectionPool) */
	protected static $instances = [];

	/** integer; seconds to cache servers as "down". */
	private const SERVER_DOWN_TTL = 30;

	/**
	 * @var LoggerInterface
	 */
	protected $logger;

	/**
	 * @param array $options
	 * @param string $id
	 * @throws Exception
	 */
	protected function __construct( array $options, $id ) {
		if ( !class_exists( Redis::class ) ) {
			throw new RuntimeException(
				__CLASS__ . ' requires a Redis client library. ' .
				'See https://www.mediawiki.org/wiki/Redis#Setup' );
		}
		$this->logger = $options['logger'] ?? new NullLogger();
		$this->connectTimeout = $options['connectTimeout'];
		$this->readTimeout = $options['readTimeout'];
		$this->persistent = $options['persistent'];
		$this->password = $options['password'];
		$this->prefix = $options['prefix'];
		if ( !isset( $options['serializer'] ) || $options['serializer'] === 'php' ) {
			$this->serializer = Redis::SERIALIZER_PHP;
		} elseif ( $options['serializer'] === 'igbinary' ) {
			if ( !defined( 'Redis::SERIALIZER_IGBINARY' ) ) {
				throw new InvalidArgumentException(
					__CLASS__ . ': configured serializer "igbinary" not available' );
			}
			$this->serializer = Redis::SERIALIZER_IGBINARY;
		} elseif ( $options['serializer'] === 'none' ) {
			$this->serializer = Redis::SERIALIZER_NONE;
		} else {
			throw new InvalidArgumentException( "Invalid serializer specified." );
		}
		$this->id = $id;
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
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
		if ( !isset( $options['prefix'] ) ) {
			$options['prefix'] = null;
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
			self::$instances[$id] = new self( $options, $id );
		}

		return self::$instances[$id];
	}

	/**
	 * Destroy all singleton() instances
	 * @since 1.27
	 */
	public static function destroySingletons() {
		self::$instances = [];
	}

	/**
	 * Get a connection to a redis server. Based on code in RedisBagOStuff.php.
	 *
	 * @param string $server A hostname/port combination or the absolute path of a UNIX socket.
	 *                       If a hostname is specified but no port, port 6379 will be used.
	 * @param LoggerInterface|null $logger PSR-3 logger instance. [optional]
	 * @return RedisConnRef|Redis|false Returns false on failure
	 * @throws InvalidArgumentException
	 */
	public function getConnection( $server, ?LoggerInterface $logger = null ) {
		// The above @return also documents 'Redis' for convenience with IDEs.
		// RedisConnRef uses PHP magic methods, which wouldn't be recognised.

		$logger = $logger ?: $this->logger;
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
				$logger->debug(
					'Server "{redis_server}" is marked down for another ' .
					( $this->downServers[$server] - $now ) . ' seconds',
					[ 'redis_server' => $server ]
				);

				return false;
			}
		}

		// Check if a connection is already free for use
		if ( isset( $this->connections[$server] ) ) {
			foreach ( $this->connections[$server] as &$connection ) {
				if ( $connection['free'] ) {
					$connection['free'] = false;
					--$this->idlePoolSize;

					return new RedisConnRef(
						$this, $server, $connection['conn'], $logger
					);
				}
			}
		}

		if ( !$server ) {
			throw new InvalidArgumentException(
				__CLASS__ . ": invalid configured server \"$server\"" );
		} elseif ( substr( $server, 0, 1 ) === '/' ) {
			// UNIX domain socket
			// These are required by the redis extension to start with a slash, but
			// we still need to set the port to a special value to make it work.
			$host = $server;
			$port = 0;
		} else {
			// TCP connection
			if ( preg_match( '/^\[(.+)\]:(\d+)$/', $server, $m ) ) {
				// (ip, port)
				[ $host, $port ] = [ $m[1], (int)$m[2] ];
			} elseif ( preg_match( '/^((?:[\w]+\:\/\/)?[^:]+):(\d+)$/', $server, $m ) ) {
				// (ip, uri or path, port)
				[ $host, $port ] = [ $m[1], (int)$m[2] ];
				if (
					substr( $host, 0, 6 ) === 'tls://'
					&& version_compare( phpversion( 'redis' ), '5.0.0' ) < 0
				) {
					throw new RuntimeException(
						'A newer version of the Redis client library is required to use TLS. ' .
						'See https://www.mediawiki.org/wiki/Redis#Setup' );
				}
			} else {
				// (ip or path, port)
				[ $host, $port ] = [ $server, 6379 ];
			}
		}

		$conn = new Redis();
		try {
			if ( $this->persistent ) {
				$result = $conn->pconnect( $host, $port, $this->connectTimeout, $this->id );
			} else {
				$result = $conn->connect( $host, $port, $this->connectTimeout );
			}
			if ( !$result ) {
				$logger->error(
					'Could not connect to server "{redis_server}"',
					[ 'redis_server' => $server ]
				);
				// Mark server down for some time to avoid further timeouts
				$this->downServers[$server] = time() + self::SERVER_DOWN_TTL;

				return false;
			}
			if ( ( $this->password !== null ) && !$conn->auth( $this->password ) ) {
				$logger->error(
					'Authentication error connecting to "{redis_server}"',
					[ 'redis_server' => $server ]
				);
			}
		} catch ( RedisException $e ) {
			$this->downServers[$server] = time() + self::SERVER_DOWN_TTL;
			$logger->error(
				'Redis exception connecting to "{redis_server}"',
				[
					'redis_server' => $server,
					'exception' => $e,
				]
			);

			return false;
		}

		if ( $this->prefix !== null ) {
			$conn->setOption( Redis::OPT_PREFIX, $this->prefix );
		}

		$conn->setOption( Redis::OPT_READ_TIMEOUT, $this->readTimeout );
		$conn->setOption( Redis::OPT_SERIALIZER, $this->serializer );
		$this->connections[$server][] = [ 'conn' => $conn, 'free' => false ];

		return new RedisConnRef( $this, $server, $conn, $logger );
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
	 * @param RedisConnRef $cref
	 * @param RedisException $e
	 */
	public function handleError( RedisConnRef $cref, RedisException $e ) {
		$server = $cref->getServer();
		$this->logger->error(
			'Redis exception on server "{redis_server}"',
			[
				'redis_server' => $server,
				'exception' => $e,
			]
		);
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
		if ( $this->password !== null && !$conn->auth( $this->password ) ) {
			$this->logger->error(
				'Authentication error connecting to "{redis_server}"',
				[ 'redis_server' => $server ]
			);

			return false;
		}

		return true;
	}

	/**
	 * Adjust or reset the connection handle read timeout value
	 *
	 * @param Redis $conn
	 * @param int|null $timeout Optional
	 */
	public function resetTimeout( Redis $conn, $timeout = null ) {
		$conn->setOption( Redis::OPT_READ_TIMEOUT, $timeout ?: $this->readTimeout );
	}

	/**
	 * Make sure connections are closed
	 */
	public function __destruct() {
		foreach ( $this->connections as &$serverConnections ) {
			foreach ( $serverConnections as &$connection ) {
				try {
					/** @var Redis $conn */
					$conn = $connection['conn'];
					$conn->close();
				} catch ( RedisException ) {
					// The destructor can be called on shutdown when random parts of the system
					// have been destructed already, causing weird errors. Ignore them.
				}
			}
		}
	}
}

/** @deprecated class alias since 1.43 */
class_alias( RedisConnectionPool::class, 'RedisConnectionPool' );
