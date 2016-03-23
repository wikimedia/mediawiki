<?php

interface IBagOStuff {

	/**
	 * @param bool $bool
	 */
	public function setDebug( $bool );

	/**
	 * Get an item with the given key, regenerating and setting it if not found
	 *
	 * If the callback returns false, then nothing is stored.
	 *
	 * @param string $key
	 * @param int $ttl Time-to-live (seconds)
	 * @param callable $callback Callback that derives the new value
	 * @param integer $flags Bitfield of BagOStuff::READ_* constants [optional]
	 *
	 * @return mixed The cached value if found or the result of $callback otherwise
	 * @since 1.27
	 */
	public function getWithSetCallback( $key, $ttl, $callback, $flags = 0 );

	/**
	 * Get an item with the given key
	 *
	 * If the key includes a determistic input hash (e.g. the key can only have
	 * the correct value) or complete staleness checks are handled by the caller
	 * (e.g. nothing relies on the TTL), then the READ_VERIFIED flag should be set.
	 * This lets tiered backends know they can safely upgrade a cached value to
	 * higher tiers using standard TTLs.
	 *
	 * @param string $key
	 * @param integer $flags Bitfield of BagOStuff::READ_* constants [optional]
	 * @param integer $oldFlags [unused]
	 *
	 * @return mixed Returns false on failure and if the item does not exist
	 */
	public function get( $key, $flags = 0, $oldFlags = null );

	/**
	 * Set an item
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants
	 *
	 * @return bool Success
	 */
	public function set( $key, $value, $exptime = 0, $flags = 0 );

	/**
	 * Delete an item
	 *
	 * @param string $key
	 *
	 * @return bool True if the item was deleted or not found, false on failure
	 */
	public function delete( $key );

	/**
	 * Merge changes into the existing cache value (possibly creating a new one).
	 * The callback function returns the new value given the current value
	 * (which will be false if not present), and takes the arguments:
	 * (this BagOStuff, cache key, current value).
	 *
	 * @param string $key
	 * @param callable $callback Callback method to be executed
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $attempts The amount of times to attempt a merge in case of failure
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants
	 *
	 * @return bool Success
	 * @throws InvalidArgumentException
	 */
	public function merge( $key, $callback, $exptime = 0, $attempts = 10, $flags = 0 );

	/**
	 * Acquire an advisory lock on a key string
	 *
	 * Note that if reentry is enabled, duplicate calls ignore $expiry
	 *
	 * @param string $key
	 * @param int $timeout Lock wait timeout; 0 for non-blocking [optional]
	 * @param int $expiry Lock expiry [optional]; 1 day maximum
	 * @param string $rclass Allow reentry if set and the current lock used this value
	 *
	 * @return bool Success
	 */
	public function lock( $key, $timeout = 6, $expiry = 6, $rclass = '' );

	/**
	 * Release an advisory lock on a key string
	 *
	 * @param string $key
	 *
	 * @return bool Success
	 */
	public function unlock( $key );

	/**
	 * Get a lightweight exclusive self-unlocking lock
	 *
	 * Note that the same lock cannot be acquired twice.
	 *
	 * This is useful for task de-duplication or to avoid obtrusive
	 * (though non-corrupting) DB errors like INSERT key conflicts
	 * or deadlocks when using LOCK IN SHARE MODE.
	 *
	 * @param string $key
	 * @param int $timeout Lock wait timeout; 0 for non-blocking [optional]
	 * @param int $expiry Lock expiry [optional]; 1 day maximum
	 * @param string $rclass Allow reentry if set and the current lock used this value
	 *
	 * @return ScopedCallback|null Returns null on failure
	 * @since 1.26
	 */
	public function getScopedLock( $key, $timeout = 6, $expiry = 30, $rclass = '' );

	/**
	 * Delete all objects expiring before a certain date.
	 *
	 * @param string $date The reference date in MW format
	 * @param callable|bool $progressCallback Optional, a function which will be called
	 *     regularly during long-running operations with the percentage progress
	 *     as the first parameter.
	 *
	 * @return bool Success, false if unimplemented
	 */
	public function deleteObjectsExpiringBefore( $date, $progressCallback = false );

	/**
	 * Get an associative array containing the item for each of the keys that have items.
	 *
	 * @param array $keys List of strings
	 * @param integer $flags Bitfield; supports READ_LATEST [optional]
	 *
	 * @return array
	 */
	public function getMulti( array $keys, $flags = 0 );

	/**
	 * Batch insertion
	 *
	 * @param array $data $key => $value assoc array
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 *
	 * @return bool Success
	 * @since 1.24
	 */
	public function setMulti( array $data, $exptime = 0 );

	/**
	 * @param string $key
	 * @param mixed $value
	 * @param int $exptime
	 *
	 * @return bool Success
	 */
	public function add( $key, $value, $exptime = 0 );

	/**
	 * Increase stored value of $key by $value while preserving its TTL
	 *
	 * @param string $key Key to increase
	 * @param int $value Value to add to $key (Default 1)
	 *
	 * @return int|bool New value or false on failure
	 */
	public function incr( $key, $value = 1 );

	/**
	 * Decrease stored value of $key by $value while preserving its TTL
	 *
	 * @param string $key
	 * @param int $value
	 *
	 * @return int|bool New value or false on failure
	 */
	public function decr( $key, $value = 1 );

	/**
	 * Increase stored value of $key by $value while preserving its TTL
	 *
	 * This will create the key with value $init and TTL $ttl instead if not present
	 *
	 * @param string $key
	 * @param int $ttl
	 * @param int $value
	 * @param int $init
	 *
	 * @return int|bool New value or false on failure
	 * @since 1.24
	 */
	public function incrWithInit( $key, $ttl, $value = 1, $init = 1 );

	/**
	 * Get the "last error" registered; clearLastError() should be called manually
	 * @return int ERR_* constant for the "last error" registry
	 * @since 1.23
	 */
	public function getLastError();

	/**
	 * Clear the "last error" registry
	 * @since 1.23
	 */
	public function clearLastError();

	/**
	 * Modify a cache update operation array for EventRelayer::notify()
	 *
	 * This is used for relayed writes, e.g. for broadcasting a change
	 * to multiple data-centers. If the array contains a 'val' field
	 * then the command involves setting a key to that value. Note that
	 * for simplicity, 'val' is always a simple scalar value. This method
	 * is used to possibly serialize the value and add any cache-specific
	 * key/values needed for the relayer daemon (e.g. memcached flags).
	 *
	 * @param array $event
	 *
	 * @return array
	 * @since 1.26
	 */
	public function modifySimpleRelayEvent( array $event );

	/**
	 * Construct a cache key.
	 *
	 * @since 1.27
	 *
	 * @param string $keyspace
	 * @param array $args
	 *
	 * @return string
	 */
	public function makeKeyInternal( $keyspace, $args );

	/**
	 * Make a global cache key.
	 *
	 * @since 1.27
	 *
	 * @param string ... Key component (variadic)
	 *
	 * @return string
	 */
	public function makeGlobalKey();

	/**
	 * Make a cache key, scoped to this instance's keyspace.
	 *
	 * @since 1.27
	 *
	 * @param string ... Key component (variadic)
	 *
	 * @return string
	 */
	public function makeKey();

}
