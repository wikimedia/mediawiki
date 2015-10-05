<?php
/**
 * APC-backed function memoization for idempotent / referentially-transparent
 * functions.
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
class MemoizedCallable {

	/** @var callable */
	private $callable;

	/** @var string */
	private $callableName;

	/**
	 * Constructor.
	 *
	 * @throws InvalidArgumentException if $callable is not a callable.
	 * @param $callable Function or method to memoize. Should be idempotent /
	 *  referentially transparent.
	 * @param $ttl TTL in seconds. Defaults to 3600 (1hr). Capped at 86400 (24h).
	 */
	public function __construct( $callable, $ttl = 3600 ) {
		if ( !is_callable( $callable, false, $this->callableName ) ) {
			throw new InvalidArgumentException( 'Argument 1 passed to MemoizedCallable::__construct()' .
			   ' must be an instance of callable; ' . gettype( $callable ) . ' given' );
		}
		if ( $this->callableName === 'Closure::__invoke' ) {
			$this->callableName .= uniqid();
		}
		$this->callable = $callable;
		$this->ttl = min( $ttl, 86400 );
	}

	/**
	 * Invoke the memoized function or method.
	 *
	 * @throws InvalidArgumentException If parameters list contains non-scalar items.
	 * @param mixed ...$params Parameters for memoized function or method.
	 * @return mixed The memoized callable's return value.
	 */
	public function invoke() {
		$args = func_get_args();
		foreach ( $args as $arg ) {
			if ( $arg !== null && !is_scalar( $arg ) ) {
				throw new InvalidArgumentException( 'MemoizedCallable::invoke() called with non-scalar argument.' );
			}
		}

		if ( !function_exists( 'apc_fetch' ) ) {
			return call_user_func_array( $this->callable, $args );
		}

		$hash = md5( serialize( $args ) );
		$key = __CLASS__ . ':' . $this->callableName . ':' . $hash;
		$success = false;
		$rv = apc_fetch( $key, $success );
		if ( !$success ) {
			$rv = call_user_func_array( $this->callable, $args );
			apc_store( $key, $rv, $this->ttl );
		}
		return $rv;
	}
}
