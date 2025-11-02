<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use Wikimedia\ObjectCache\BagOStuff;

/**
 * Store an arbitrary value whilst representing several CacheDependency objects as one.
 *
 * You should typically only use DependencyWrapper::getValueFromCache(),
 * rather than instantiating one of these objects directly.
 *
 * @ingroup Language
 */
class DependencyWrapper {
	/** @var mixed */
	private $value;
	/** @var CacheDependency[] */
	private $deps;

	/**
	 * @param mixed $value The user-supplied value
	 * @param CacheDependency|CacheDependency[] $deps A dependency or dependency
	 *   array. All dependencies must be objects implementing CacheDependency.
	 */
	public function __construct( $value = false, $deps = [] ) {
		$this->value = $value;

		if ( !is_array( $deps ) ) {
			$deps = [ $deps ];
		}

		$this->deps = $deps;
	}

	/**
	 * Returns true if any of the dependencies have expired
	 *
	 * @return bool
	 */
	public function isExpired() {
		foreach ( $this->deps as $dep ) {
			if ( $dep->isExpired() ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Initialise dependency values in preparation for storing. This must be
	 * called before serialization.
	 */
	public function initialiseDeps() {
		foreach ( $this->deps as $dep ) {
			$dep->loadDependencyValues();
		}
	}

	/**
	 * Get the user-defined value
	 * @return bool|mixed
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Store the wrapper to a cache
	 *
	 * @param BagOStuff $cache
	 * @param string $key
	 * @param int $expiry
	 */
	public function storeToCache( $cache, $key, $expiry = 0 ) {
		$this->initialiseDeps();
		$cache->set( $key, $this, $expiry );
	}

	/**
	 * Attempt to get a value from the cache. If the value is expired or missing,
	 * it will be generated with the callback function (if present), and the newly
	 * calculated value will be stored to the cache in a wrapper.
	 *
	 * @param BagOStuff $cache
	 * @param string $key The cache key
	 * @param int $expiry The expiry timestamp or interval in seconds
	 * @param callable|false $callback The callback for generating the value, or false
	 * @param array $callbackParams The function parameters for the callback
	 * @param array $deps The dependencies to store on a cache miss. Note: these
	 *    are not the dependencies used on a cache hit! Cache hits use the stored
	 *    dependency array.
	 *
	 * @return mixed The value, or null if it was not present in the cache and no
	 *    callback was defined.
	 */
	public static function getValueFromCache( $cache, $key, $expiry = 0, $callback = false,
		$callbackParams = [], $deps = []
	) {
		$obj = $cache->get( $key );

		if ( is_object( $obj ) && $obj instanceof DependencyWrapper && !$obj->isExpired() ) {
			$value = $obj->value;
		} elseif ( $callback ) {
			$value = $callback( ...$callbackParams );
			# Cache the newly-generated value
			$wrapper = new DependencyWrapper( $value, $deps );
			$wrapper->storeToCache( $cache, $key, $expiry );
		} else {
			$value = null;
		}

		return $value;
	}
}
