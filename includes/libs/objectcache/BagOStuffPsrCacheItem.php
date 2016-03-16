<?php

use Psr\Cache\CacheItemInterface;

/**
 * @author Addshore
 *
 * @ingroup Cache
 *
 * @since 1.27
 */
class BagOStuffPsrCacheItem implements CacheItemInterface {

	/**
	 * @var string
	 */
	private $key;

	/**
	 * @var mixed
	 */
	private $value = null;

	/**
	 * @var bool
	 */
	private $isHit = false;

	/**
	 * @var DateTimeInterface|null
	 */
	private $expiration = null;

	/**
	 * @var bool Has the value been changed since construction / wakeup
	 */
	private $changed = false;

	/**
	 * @todo The spec says that calling libraries MUST NOT instantiate this directly, but how to
	 *     enforce that?
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param bool $isHit
	 */
	public function __construct( $key, $value, $isHit ) {
		$this->key = $key;
		$this->value = $isHit ? $value : null;
		$this->isHit = $isHit;
	}

	/**
	 * Returns the key for the current cache item.
	 *
	 * The key is loaded by the Implementing Library, but should be available to
	 * the higher level callers when needed.
	 *
	 * @return string
	 *   The key string for this cache item.
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * Retrieves the value of the item from the cache associated with this object's key.
	 *
	 * The value returned must be identical to the value originally stored by set().
	 *
	 * If isHit() returns false, this method MUST return null. Note that null
	 * is a legitimate cached value, so the isHit() method SHOULD be used to
	 * differentiate between "null value was found" and "no value was found."
	 *
	 * @return mixed
	 *   The value corresponding to this cache item's key, or null if not found.
	 */
	public function get() {
		return $this->value;
	}

	/**
	 * Confirms if the cache item lookup resulted in a cache hit.
	 *
	 * Note: This method MUST NOT have a race condition between calling isHit()
	 * and calling get().
	 *
	 * @return bool
	 *   True if the request resulted in a cache hit. False otherwise.
	 */
	public function isHit() {
		return $this->isHit;
	}

	/**
	 * Sets the value represented by this cache item.
	 *
	 * The $value argument may be any item that can be serialized by PHP,
	 * although the method of serialization is left up to the Implementing
	 * Library.
	 *
	 * @param mixed $value
	 *   The serializable value to be stored.
	 *
	 * @return static
	 *   The invoked object.
	 */
	public function set( $value ) {
		$this->value = $value;
		$this->changed = true;
		return $this;
	}

	/**
	 * Sets the expiration time for this cache item.
	 *
	 * @param \DateTimeInterface $expiration
	 *   The point in time after which the item MUST be considered expired.
	 *   If null is passed explicitly, a default value MAY be used. If none is set,
	 *   the value should be stored permanently or for as long as the
	 *   implementation allows.
	 *
	 * @return static
	 *   The called object.
	 */
	public function expiresAt( $expiration ) {
		$this->expiration = $expiration;

		return $this;
	}

	/**
	 * Sets the expiration time for this cache item.
	 *
	 * @param int|\DateInterval $time
	 *   The period of time from the present after which the item MUST be considered
	 *   expired. An integer parameter is understood to be the time in seconds until
	 *   expiration. If null is passed explicitly, a default value MAY be used.
	 *   If none is set, the value should be stored permanently or for as long as the
	 *   implementation allows.
	 *
	 * @return static
	 *   The called object.
	 */
	public function expiresAfter( $time ) {
		if ( $time instanceof DateInterval ) {
			$interval = $time;
		} elseif ( (int)$time == $time ) {
			$interval = new DateInterval( 'PT' . $time . 'S' );
		} else {
			throw new InvalidArgumentException( sprintf( 'Invalid $time "%s"', gettype( $time ) ) );
		}
		$this->expiration = new DateTime();
		$this->expiration->add( $interval );

		return $this;
	}

	/**
	 * Private
	 *
	 * This should ONLY be called by BagOStuffPsrCache
	 *
	 * @return DateTimeInterface|null
	 */
	public function getExpiration() {
		return $this->expiration;
	}

}
