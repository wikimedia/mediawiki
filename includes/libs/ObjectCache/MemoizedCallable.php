<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * APCu-backed function memoization
 *
 * This class provides memoization for pure functions. A function is pure
 * if its result value depends on nothing other than its input parameters
 * and if invoking it does not cause any side-effects.
 *
 * The first invocation of the memoized callable with a particular set of
 * arguments will be delegated to the underlying callable. Repeat invocations
 * with the same input parameters will be served from APCu.
 *
 * @par Example:
 * @code
 * $memoizedRange = new MemoizedCallable( 'range' );
 * $memoizedRange->invoke( 5, 8 );  // result: array( 5, 6, 7, 8 )
 * $memoizedRange->invokeArgs( array( 5, 8 ) );  // same
 * MemoizedCallable::call( 'range', array( 5, 8 ) );  // same
 * @endcode
 *
 * @since 1.27
 * @ingroup Cache
 * @author Ori Livneh
 */
class MemoizedCallable {

	/** @var callable */
	private $callable;

	/** @var string Unique name of callable; used for cache keys. */
	private $callableName;

	/** @var int */
	private $ttl;

	/**
	 * @param callable&string|callable&array $callable Function or method to memoize.
	 *   This class does not support Closure objects, as it uses the function/method name as a cache key.
	 *   To memoize a closure, use Wikimedia\ObjectCache\APCUBagOStuff::getWithSetCallback() instead
	 *   and pass a unique name for your closure and its parameters to `makeGlobalKey`.
	 * @param int $ttl TTL in seconds. Defaults to 3600 (1 hour). Capped at 86400 (24 hours).
	 */
	public function __construct( $callable, $ttl = 3600 ) {
		if ( !is_callable( $callable, false, $this->callableName ) ) {
			throw new InvalidArgumentException(
				'Argument 1 passed to MemoizedCallable::__construct() must ' .
				'be a callable; ' . get_debug_type( $callable ) . ' given'
			);
		}

		if ( $callable instanceof Closure ) {
			throw new InvalidArgumentException( 'Cannot memoize unnamed closure' );
		}

		$this->callable = $callable;
		$this->ttl = min( max( $ttl, 1 ), 86400 );
	}

	/**
	 * Fetch the result of a previous invocation.
	 *
	 * @param string $key
	 * @param bool &$success
	 * @return bool
	 */
	protected function fetchResult( $key, &$success ) {
		$success = false;
		if ( function_exists( 'apcu_fetch' ) ) {
			return apcu_fetch( $key, $success );
		}
		return false;
	}

	/**
	 * Store the result of an invocation.
	 *
	 * @param string $key
	 * @param mixed $result
	 */
	protected function storeResult( $key, $result ) {
		if ( function_exists( 'apcu_store' ) ) {
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
	 * @param mixed ...$params Parameters for memoized function or method.
	 * @return mixed The memoized callable's return value.
	 */
	public function invoke( ...$params ) {
		return $this->invokeArgs( $params );
	}

	/**
	 * Shortcut method for creating a MemoizedCallable and invoking it
	 * with the specified arguments.
	 *
	 * @param callable&string|callable&array $callable
	 * @param array $args
	 * @param int $ttl
	 * @return mixed
	 */
	public static function call( $callable, array $args = [], $ttl = 3600 ) {
		$instance = new self( $callable, $ttl );
		return $instance->invokeArgs( $args );
	}
}
