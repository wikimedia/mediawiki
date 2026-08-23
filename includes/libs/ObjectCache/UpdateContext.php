<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\ObjectCache;

use Wikimedia\LightweightObjectStore\ExpirationAwareness;

/**
 * Mutable state handed to the value generation callback of WANGetWithSetCallbackBuilder
 *
 * This replaces the by-reference `$ttl` and `$setOpts` parameters of the callback used by
 * WANObjectCache::getWithSetCallback(). Instead of writing to out-parameters, a callback
 * that needs to influence how its return value is stored calls the setters here, e.g.
 *
 * @code
 *     function ( $oldValue, CacheRegenerationContext $context ) {
 *         $row = $this->loadRow();
 *         if ( !$row ) {
 *             // Nothing to cache yet; ask again on the next request
 *             $context->doNotCache();
 *             return null;
 *         }
 *
 *         return $row;
 *     }
 * @endcode
 *
 * @since 1.47
 * @ingroup Cache
 */
class UpdateContext {
	/**
	 * @param int $lifetime Seconds that the newly generated value may be reused for
	 * @param mixed $oldValue Value found at the key, or false if there was none
	 * @param float|null $oldAsOf UNIX timestamp of $oldValue, or null if there was none
	 * @param array $params Custom field/value map provided by the caller
	 * @internal Use WANGetWithSetCallbackBuilder::getWithSetCallback()
	 */
	public function __construct(
		private int $lifetime,
		private $oldValue,
		private ?float $oldAsOf,
		private array $params
	) {
	}

	/**
	 * Get the value that is about to be replaced, if any
	 *
	 * @return mixed Previous value, or false if there was none
	 */
	public function getOldValue() {
		return $this->oldValue;
	}

	/**
	 * Check whether a previous value was found at the key
	 */
	public function hasOldValue(): bool {
		return $this->oldValue !== false;
	}

	/**
	 * Get the UNIX timestamp of when the previous value was generated
	 *
	 * @return float|null Null if there was no previous value
	 */
	public function getOldAsOf(): ?float {
		return $this->oldAsOf;
	}

	/**
	 * Get the custom field/value map given to WANGetWithSetCallbackBuilder::callbackParams()
	 */
	public function getParams(): array {
		return $this->params;
	}

	/**
	 * Get a single field of the map given to WANGetWithSetCallbackBuilder::callbackParams()
	 *
	 * @param string $name
	 * @param mixed|null $default Value to use if the field was not provided
	 * @return mixed
	 */
	public function getParam( string $name, $default = null ) {
		return $this->params[$name] ?? $default;
	}

	/**
	 * Get how long the newly generated value may be reused for
	 *
	 * @return int Seconds, or ExpirationAwareness::TTL_INDEFINITE/TTL_UNCACHEABLE
	 */
	public function getLifetime(): int {
		return $this->lifetime;
	}

	/**
	 * Override how long the newly generated value may be reused for
	 *
	 * This takes precedence over WANGetWithSetCallbackBuilder::lifetime(), which is what
	 * a callback that does not call this method ends up with.
	 *
	 * @param int $seconds
	 * @return $this
	 */
	public function setLifetime( int $seconds ) {
		$this->lifetime = $seconds;

		return $this;
	}

	/**
	 * Reuse the newly generated value indefinitely, subject to LRU-style evictions
	 *
	 * @return $this
	 */
	public function keepIndefinitely() {
		return $this->setLifetime( ExpirationAwareness::TTL_INDEFINITE );
	}

	/**
	 * Do not store the newly generated value at all
	 *
	 * Any value that already exists at the key is left alone.
	 *
	 * @return $this
	 */
	public function doNotCache() {
		return $this->setLifetime( ExpirationAwareness::TTL_UNCACHEABLE );
	}
}
