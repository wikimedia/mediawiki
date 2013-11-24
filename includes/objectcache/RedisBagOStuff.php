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
	/** @var Array List of server names */
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
	 */
	function __construct( $params ) {
		$redisConf = array( 'serializer' => 'php' );
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
			$this->handleException( $server, $conn, $e );
		}
		$casToken = $result;
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
			$this->handleException( $server, $conn, $e );
		}

		$this->logRequest( 'set', $key, $server, $result );
		wfProfileOut( __METHOD__ );
		return $result;
	}

	public function cas( $casToken, $key, $value, $expiry = 0 ) {
		wfProfileIn( __METHOD__ );
		list( $server, $conn ) = $this->getConnection( $key );
		if ( !$conn ) {
			wfProfileOut( __METHOD__ );
			return false;
		}
		$expiry = $this->convertToRelative( $expiry );
		try {
			$conn->watch( $key );

			if ( $this->get( $key ) !== $casToken ) {
				wfProfileOut( __METHOD__ );
				return false;
			}

			$conn->multi();

			if ( !$expiry ) {
				// No expiry, that is very different from zero expiry in Redis
				$conn->set( $key, $value );
			} else {
				$conn->setex( $key, $expiry, $value );
			}

			/*
			 * multi()/exec() (transactional mode) allows multiple values to
			 * be set/get at once and will return an array of results, in
			 * the order they were set/get. In this case, we only set 1
			 * value, which should (in case of success) result in true.
			 */
			$result = ( $conn->exec() == array( true ) );
		} catch ( RedisException $e ) {
			$result = false;
			$this->handleException( $server, $conn, $e );
		}

		$this->logRequest( 'cas', $key, $server, $result );
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
			$this->handleException( $server, $conn, $e );
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
				$this->handleException( $server, $conn, $e );
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
			$this->handleException( $server, $conn, $e );
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
			$this->handleException( $server, $conn, $e );
		}

		$this->logRequest( 'replace', $key, $server, $result );
		wfProfileOut( __METHOD__ );
		return $result;
	}

	/**
	 * Get a Redis object with a connection suitable for fetching the specified key
	 * @return Array (server, RedisConnRef) or (false, false)
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
		return array( false, false );
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
	protected function handleException( $server, RedisConnRef $conn, $e ) {
		$this->redisPool->handleException( $server, $conn, $e );
	}

	/**
	 * Send information about a single request to the debug log
	 */
	public function logRequest( $method, $key, $server, $result ) {
		$this->debug( "$method $key on $server: " .
			( $result === false ? "failure" : "success" ) );
	}
}
