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
 * @author Ori Livneh
 */

/**
 * APC-backed and APCu-backed function memoization
 *
 * This class provides memoization for pure functions. A function is pure
 * if its result value depends on nothing other than its input parameters
 * and if invoking it does not cause any side-effects.
 *
 * The first invocation of the memoized callable with a particular set of
 * arguments will be delegated to the underlying callable. Repeat invocations
 * with the same input parameters will be served from APC or APCu.
 *
 * @par Example:
 * @code
 * $memoizedStrrev = new MemoizedCallable( 'range' );
 * $memoizedStrrev->invoke( 5, 8 );  // result: array( 5, 6, 7, 8 )
 * $memoizedStrrev->invokeArgs( array( 5, 8 ) );  // same
 * MemoizedCallable::call( 'range', array( 5, 8 ) );  // same
 * @endcode
 *
 * @since 1.27
 */
class MemoizedCallable {

	/** @var callable */
	private $callable;

	/** @var string Unique name of callable; used for cache keys. */
	private $callableName;

	/**
	 * @throws InvalidArgumentException if $callable is not a callable.
	 * @param callable $callable Function or method to memoize.
	 * @param int $ttl TTL in seconds. Defaults to 3600 (1hr). Capped at 86400 (24h).
	 */
	public function __construct( $callable, $ttl = 3600 ) {
		if ( !is_callable( $callable, false, $this->callableName ) ) {
			throw new InvalidArgumentException(
				'Argument 1 passed to MemoizedCallable::__construct() must ' .
				'be an instance of callable; ' . gettype( $callable ) . ' given'
			);
		}

		if ( $this->callableName === 'Closure::__invoke' ) {
			// Differentiate anonymous functions from one another
			$this->callableName .= uniqid();
		}

		$this->callable = $callable;
		$this->ttl = min( max( $ttl, 1 ), 86400 );
	}

	/**
	 * Fetch the result of a previous invocation from APC or APCu.
	 *
	 * @param string $key
	 * @param bool &$success
	 * @return bool
	 */
	protected function fetchResult( $key, &$success ) {
		$success = false;
		if ( function_exists( 'apc_fetch' ) ) {
			return apc_fetch( $key, $success );
		} elseif ( function_exists( 'apcu_fetch' ) ) {
			return apcu_fetch( $key, $success );
		}
		return false;
	}

	/**
	 * Store the result of an invocation in APC or APCu.
	 *
	 * @param string $key
	 * @param mixed $result
	 */
	protected function storeResult( $key, $result ) {
		if ( function_exists( 'apc_store' ) ) {
			apc_store( $key, $result, $this->ttl );
		} elseif ( function_exists( 'apcu_store' ) ) {
			apcu_store( $key, $result, $this->ttl );
		}
	}

	/**
	 * Invoke the memoized function or method.
	 *
	 * @throws InvalidArgumentException If parameters list contains non-scalar items.
	 * @param array $args Parameters for memoized function or method.
	 * @return mixed The memoized callable's return value.
	 */
	public function invokeArgs( array $args = [] ) {
		foreach ( $args as $arg ) {
			if ( $arg !== null && !is_scalar( $arg ) ) {
				throw new InvalidArgumentException(
					'MemoizedCallable::invoke() called with non-scalar ' .
					'argument'
				);
			}
		}

		$hash = md5( serialize( $args ) );
		$key = __CLASS__ . ':' . $this->callableName . ':' . $hash;
		$success = false;
		$result = $this->fetchResult( $key, $success );
		if ( !$success ) {
			$result = ( $this->callable )( ...$args );
			$this->storeResult( $key, $result );
		}

		return $result;
	}

	/**
	 * Invoke the memoized function or method.
	 *
	 * Like MemoizedCallable::invokeArgs(), but variadic.
	 *
	 * @param mixed $params,... Parameters for memoized function or method.
	 * @return mixed The memoized callable's return value.
	 */
	public function invoke() {
		return $this->invokeArgs( func_get_args() );
	}

	/**
	 * Shortcut method for creating a MemoizedCallable and invoking it
	 * with the specified arguments.
	 *
	 * @param callable $callable
	 * @param array $args
	 * @param int $ttl
	 * @return mixed
	 */
	public static function call( $callable, array $args = [], $ttl = 3600 ) {
		$instance = new self( $callable, $ttl );
		return $instance->invokeArgs( $args );
	}
}
