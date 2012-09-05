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
	protected $connectTimeout, $persistent, $password, $automaticFailover;

	/**
	 * A list of server names, from $params['servers']
	 */
	protected $servers;

	/**
	 * A cache of Redis objects, representing connections to Redis servers.
	 * The key is the server name.
	 */
	protected $conns = array();

	/**
	 * An array listing "dead" servers which have had a connection error in
	 * the past. Servers are marked dead for a limited period of time, to
	 * avoid excessive overhead from repeated connection timeouts. The key in
	 * the array is the server name, the value is the UNIX timestamp at which
	 * the server is resurrected.
	 */
	protected $deadServers = array();

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
	 */
	function __construct( $params ) {
		if ( !extension_loaded( 'redis' ) ) {
			throw new MWException( __CLASS__. ' requires the phpredis extension: ' .
				'https://github.com/nicolasff/phpredis' );
		}

		$this->servers = $params['servers'];
		$this->connectTimeout = isset( $params['connectTimeout'] )
			? $params['connectTimeout'] : 1;
		$this->persistent = !empty( $params['persistent'] );
		if ( isset( $params['password'] ) ) {
			$this->password = $params['password'];
		}
		if ( isset( $params['automaticFailover'] ) ) {
			$this->automaticFailover = $params['automaticFailover'];
		} else {
			$this->automaticFailover = true;
		}
	}

	public function get( $key ) {
		wfProfileIn( __METHOD__ );
		list( $server, $conn ) = $this->getConnection( $key );
		if ( !$conn ) {
			wfProfileOut( __METHOD__ );
			return false;
		}
		try {
			$result = $conn->get( $key );
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $server, $e );
		}
		$this->logRequest( 'get', $key, $server, $result );
		wfProfileOut( __METHOD__ );
		return $result;
	}

	public function set( $key, $value, $expiry = 0 ) {
		wfProfileIn( __METHOD__ );
		list( $server, $conn ) = $this->getConnection( $key );
		if ( !$conn ) {
			wfProfileOut( __METHOD__ );
			return false;
		}
		$expiry = $this->convertToRelative( $expiry );
		try {
			if ( !$expiry ) {
				// No expiry, that is very different from zero expiry in Redis
				$result = $conn->set( $key, $value );
			} else {
				$result = $conn->setex( $key, $expiry, $value );
			}
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $server, $e );
		}

		$this->logRequest( 'set', $key, $server, $result );
		wfProfileOut( __METHOD__ );
		return $result;
	}

	public function delete( $key, $time = 0 ) {
		wfProfileIn( __METHOD__ );
		list( $server, $conn ) = $this->getConnection( $key );
		if ( !$conn ) {
			wfProfileOut( __METHOD__ );
			return false;
		}
		try {
			$conn->delete( $key );
			// Return true even if the key didn't exist
			$result = true;
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $server, $e );
		}
		$this->logRequest( 'delete', $key, $server, $result );
		wfProfileOut( __METHOD__ );
		return $result;
	}

	public function getMulti( array $keys ) {
		wfProfileIn( __METHOD__ );
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
						$result[$batchKeys[$i]] = $value;
					}
				}
			} catch ( RedisException $e ) {
				$this->handleException( $server, $e );
			}
		}

		$this->debug( "getMulti for " . count( $keys ) . " keys " .
			"returned " . count( $result ) . " results" );
		wfProfileOut( __METHOD__ );
		return $result;
	}

	public function add( $key, $value, $expiry = 0 ) {
		wfProfileIn( __METHOD__ );
		list( $server, $conn ) = $this->getConnection( $key );
		if ( !$conn ) {
			wfProfileOut( __METHOD__ );
			return false;
		}
		$expiry = $this->convertToRelative( $expiry );
		try {
			$result = $conn->setnx( $key, $value );
			if ( $result && $expiry ) {
				$conn->expire( $key, $expiry );
			}
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $server, $e );
		}
		$this->logRequest( 'add', $key, $server, $result );
		wfProfileOut( __METHOD__ );
		return $result;
	}

	/**
	 * Non-atomic implementation of replace(). Could perhaps be done atomically
	 * with WATCH or scripting, but this function is rarely used.
	 */
	public function replace( $key, $value, $expiry = 0 ) {
		wfProfileIn( __METHOD__ );
		list( $server, $conn ) = $this->getConnection( $key );
		if ( !$conn ) {
			wfProfileOut( __METHOD__ );
			return false;
		}
		if ( !$conn->exists( $key ) ) {
			wfProfileOut( __METHOD__ );
			return false;
		}

		$expiry = $this->convertToRelative( $expiry );
		try {
			if ( !$expiry ) {
				$result = $conn->set( $key, $value );
			} else {
				$result = $conn->setex( $key, $expiry, $value );
			}
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $server, $e );
		}

		$this->logRequest( 'replace', $key, $server, $result );
		wfProfileOut( __METHOD__ );
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
	 */
	public function incr( $key, $value = 1 ) {
		wfProfileIn( __METHOD__ );
		list( $server, $conn ) = $this->getConnection( $key );
		if ( !$conn ) {
			wfProfileOut( __METHOD__ );
			return false;
		}
		if ( !$conn->exists( $key ) ) {
			wfProfileOut( __METHOD__ );
			return null;
		}
		try {
			$result = $conn->incrBy( $key, $value );
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $server, $e );
		}

		$this->logRequest( 'incr', $key, $server, $result );
		wfProfileOut( __METHOD__ );
		return $result;
	}

	/**
	 * Get a Redis object with a connection suitable for fetching the specified key
	 */
	protected function getConnection( $key ) {
		if ( count( $this->servers ) === 1 ) {
			$candidates = $this->servers;
		} else {
			// Use consistent hashing
			$hashes = array();
			foreach ( $this->servers as $server ) {
				$hashes[$server] = md5( $server . '/' . $key );
			}
			asort( $hashes );
			if ( !$this->automaticFailover ) {
				reset( $hashes );
				$candidates = array( key( $hashes ) );
			} else {
				$candidates = array_keys( $hashes );
			}
		}

		foreach ( $candidates as $server ) {
			$conn = $this->getConnectionToServer( $server );
			if ( $conn ) {
				return array( $server, $conn );
			}
		}
		return array( false, false );
	}

	/**
	 * Get a connection to the server with the specified name. Connections
	 * are cached, and failures are persistent to avoid multiple timeouts.
	 *
	 * @return Redis object, or false on failure
	 */
	protected function getConnectionToServer( $server ) {
		if ( isset( $this->deadServers[$server] ) ) {
			$now = time();
			if ( $now > $this->deadServers[$server] ) {
				// Dead time expired
				unset( $this->deadServers[$server] );
			} else {
				// Server is dead
				$this->debug( "server $server is marked down for another " .
					($this->deadServers[$server] - $now ) .
					" seconds, can't get connection" );
				return false;
			}
		}

		if ( isset( $this->conns[$server] ) ) {
			return $this->conns[$server];
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
		$conn = new Redis;
		try {
			if ( $this->persistent ) {
				$this->debug( "opening persistent connection to $host:$port" );
				$result = $conn->pconnect( $host, $port, $this->connectTimeout );
			} else {
				$this->debug( "opening non-persistent connection to $host:$port" );
				$result = $conn->connect( $host, $port, $this->connectTimeout );
			}
			if ( !$result ) {
				$this->logError( "could not connect to server $server" );
				// Mark server down for 30s to avoid further timeouts
				$this->deadServers[$server] = time() + 30;
				return false;
			}
			if ( $this->password !== null ) {
				if ( !$conn->auth( $this->password ) ) {
					$this->logError( "authentication error connecting to $server" );
				}
			}
		} catch ( RedisException $e ) {
			$this->deadServers[$server] = time() + 30;
			wfDebugLog( 'redis', "Redis exception: " . $e->getMessage() . "\n" );
			return false;
		}

		$conn->setOption( Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP );
		$this->conns[$server] = $conn;
		return $conn;
	}

	/**
	 * Log a fatal error
	 */
	protected function logError( $msg ) {
		wfDebugLog( 'redis', "Redis error: $msg\n" );
	}

	/**
	 * The redis extension throws an exception in response to various read, write
	 * and protocol errors. Sometimes it also closes the connection, sometimes
	 * not. The safest response for us is to explicitly destroy the connection
	 * object and let it be reopened during the next request.
	 */
	protected function handleException( $server, $e ) {
		wfDebugLog( 'redis', "Redis exception on server $server: " . $e->getMessage() . "\n" );
		unset( $this->conns[$server] );
	}

	/**
	 * Send information about a single request to the debug log
	 */
	public function logRequest( $method, $key, $server, $result ) {
		$this->debug( "$method $key on $server: " .
			( $result === false ? "failure" : "success" ) );
	}
}

