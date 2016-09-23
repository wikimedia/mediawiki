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
 * @author Aaron Schulz
 */
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareInterface;

/**
 * Helper class to handle automatically marking connectons as reusable (via RAII pattern)
 *
 * This class simply wraps the Redis class and can be used the same way
 *
 * @ingroup Redis
 * @since 1.21
 */
class RedisConnRef implements LoggerAwareInterface {
	/** @var RedisConnectionPool */
	protected $pool;
	/** @var Redis */
	protected $conn;

	protected $server; // string
	protected $lastError; // string

	/**
	 * @var LoggerInterface
	 */
	protected $logger;

	/**
	 * @param RedisConnectionPool $pool
	 * @param string $server
	 * @param Redis $conn
	 * @param LoggerInterface $logger
	 */
	public function __construct(
		RedisConnectionPool $pool, $server, Redis $conn, LoggerInterface $logger
	) {
		$this->pool = $pool;
		$this->server = $server;
		$this->conn = $conn;
		$this->logger = $logger;
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
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
			$res = call_user_func_array( [ $conn, $name ], $arguments );
			if ( preg_match( '/^ERR operation not permitted\b/', $conn->getLastError() ) ) {
				$this->pool->reauthenticateConnection( $this->server, $conn );
				$conn->clearLastError();
				$res = call_user_func_array( [ $conn, $name ], $arguments );
				$this->logger->info(
					"Used automatic re-authentication for method '$name'.",
					[ 'redis_server' => $this->server ]
				);
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
			$this->logger->info(
				"Used automatic re-authentication for Lua script '$sha1'.",
				[ 'redis_server' => $server ]
			);
		}
		// If the script is not in cache, use eval() to retry and cache it
		if ( preg_match( '/^NOSCRIPT/', $conn->getLastError() ) ) {
			$conn->clearLastError();
			$res = $conn->eval( $script, $params, $numKeys );
			$this->logger->info(
				"Used eval() for Lua script '$sha1'.",
				[ 'redis_server' => $server ]
			);
		}

		if ( $conn->getLastError() ) { // script bug?
			$this->logger->error(
				'Lua script error on server "{redis_server}": {lua_error}',
				[
					'redis_server' => $server,
					'lua_error' => $conn->getLastError()
				]
			);
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
