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
	 * No authentication errors.
	 *
	 * @var constant
	 */
	const AUTH_NO_ERROR = 200;

	/**
	 * Temporary authentication error; recovered by reauthenticating.
	 *
	 * @var constant
	 */
	const AUTH_ERROR_TEMPORARY = 201;

	/**
	 * Authentication error was permanent and could not be recovered.
	 *
	 * @var constant
	 */
	const AUTH_ERROR_PERMANENT = 202;

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

	/**
	 * Magic __call handler for most Redis functions.
	 *
	 * @param string $name
	 * @param array $arguments
	 * @return mixed $res
	 * @throws RedisException
	 */
	public function __call( $name, $arguments ) {
		// Work around https://github.com/nicolasff/phpredis/issues/70
		$lname = strtolower( $name );
		if (
			( $lname === 'blpop' || $lname === 'brpop' || $lname === 'brpoplpush' )
			&& count( $arguments ) > 1
		) {
			// Get timeout off the end since the timeout is always required and argument length can vary.
			$timeout = end( $arguments );
			///Only give the additional one second buffer if not requesting an infinite timeout.
			$this->pool->resetTimeout( $this->conn, ( $timeout > 0 ? $timeout + 1 : $timeout ) );
		}

		$res = $this->tryCall( $name, $arguments );

		return $res;
	}

	/**
	 * Do the method call in the common try catch handler.
	 *
	 * @param string Redis method name.
	 * @param array Arguments
	 * @return mixed Results
	 * @throws RedisException
	 */
	private function tryCall( $method, $arguments ) {
		$this->conn->clearLastError();
		try {
			$res = call_user_func_array( [ $this->conn, $method ], $arguments );
			$authError = $this->handleAuthError();
			if ( $authError === self::AUTH_ERROR_TEMPORARY ) {
				$res = call_user_func_array( [ $this->conn, $method ], $arguments );
			}
			if ( $authError === self::AUTH_ERROR_PERMANENT ) {
				throw new RedisException( "There was a permanent failure reauthenticating to Redis." );
			}
		} catch ( RedisException $e ) {
			$this->postCallCleanup();
			throw $e;
		}

		$this->postCallCleanup();

		return $res;
	}

	/**
	 * Key Scan
	 * Handle this explicity due to needing the iterator passed by reference.
	 * See: https://github.com/phpredis/phpredis#scan
	 *
	 * @param integer Iterator reference
	 * @param string [Optional] Pattern Match - Use null to skip this parameter.
	 * @param integer [Optional] Iterator reference
	 * @return array Results for this pass.
	 */
	public function scan( &$iterator, $pattern = null, $count = null ) {
		$res = $this->tryCall( 'scan', [ &$iterator, $pattern, $count ] );

		return $res;
	}

	/**
	 * Set Scan
	 * Handle this explicity due to needing the iterator passed by reference.
	 * See: https://github.com/phpredis/phpredis#sScan
	 *
	 * @param string Redis set to scan against.
	 * @param integer Iterator reference
	 * @param string [Optional] Pattern Match - Use null to skip this parameter.
	 * @param integer [Optional] Iterator reference
	 * @return array Results for this pass.
	 */
	public function sScan( $key, &$iterator, $pattern = null, $count = null ) {
		$res = $this->tryCall( 'sScan', [ $key, &$iterator, $pattern, $count ] );

		return $res;
	}

	/**
	 * Hash Scan
	 * Handle this explicity due to needing the iterator passed by reference.
	 * See: https://github.com/phpredis/phpredis#hScan
	 *
	 * @param string Redis hash to scan against.
	 * @param integer Iterator reference
	 * @param string [Optional] Pattern Match - Use null to skip this parameter.
	 * @param integer [Optional] Iterator reference
	 * @return array Results for this pass.
	 */
	public function hScan( $key, &$iterator, $pattern = null, $count = null ) {
		$res = $this->tryCall( 'hScan', [ $key, &$iterator, $pattern, $count ] );

		return $res;
	}

	/**
	 * Sorted Set Scan
	 * Handle this explicity due to needing the iterator passed by reference.
	 * See: https://github.com/phpredis/phpredis#hScan
	 *
	 * @param string Redis sort set to scan against.
	 * @param integer Iterator reference
	 * @param string [Optional] Pattern Match - Use null to skip this parameter.
	 * @param integer [Optional] Iterator reference
	 * @return array Results for this pass.
	 */
	public function zScan( $key, &$iterator, $pattern = null, $count = null ) {
		$res = $this->tryCall( 'zScan', [ $key, &$iterator, $pattern, $count ] );

		return $res;
	}

	/**
	 * Handle authentication errors and automatically reauthenticate.
	 *
	 * @return constant	self::AUTH_NO_ERROR, self::AUTH_ERROR_TEMPORARY, or self::AUTH_ERROR_PERMANENT
	 */
	private function handleAuthError() {
		if ( preg_match( '/^ERR operation not permitted\b/', $this->conn->getLastError() ) ) {
			if ( !$this->pool->reauthenticateConnection( $this->server, $this->conn ) ) {
				return self::AUTH_ERROR_PERMANENT;
			}
			$this->conn->clearLastError();
			$this->logger->info(
				"Used automatic re-authentication for Redis.",
				[ 'redis_server' => $this->server ]
			);
			return self::AUTH_ERROR_TEMPORARY;
		}
		return self::AUTH_NO_ERROR;
	}

	/**
	 * Post Redis call cleanup.
	 *
	 * @return void
	 */
	private function postCallCleanup() {
		$this->lastError = $this->conn->getLastError() ?: $this->lastError;

		// Restore original timeout in the case of blocking calls.
		$this->pool->resetTimeout( $this->conn );
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
