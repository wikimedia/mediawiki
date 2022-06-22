<?php

namespace Wikimedia\WRStats;

/**
 * The narrow interface WRStats needs into a memcached-like key-value store.
 *
 * @since 1.39
 */
interface StatsStore {
	/**
	 * Construct a string key from its components
	 *
	 * @param array $prefix The prefix components.
	 * @param array $internals The internal components.
	 * @param EntityKey $entity The entity components. If $entity->isGlobal()
	 *   is true, the key as a whole should be treated as global.
	 * @return string
	 */
	public function makeKey( $prefix, $internals, $entity );

	/**
	 * Perform a batch of increment operations.
	 *
	 * @param int[] $values The deltas to add, indexed by the key as returned by makeKey()
	 * @param int $ttl The expiry time of any new entries, in seconds. This is
	 *   a hint, allowing the storage layer to control space usage. Implementing
	 *   expiry is not a requirement.
	 */
	public function incr( array $values, $ttl );

	/**
	 * Perform a batch of delete operations.
	 *
	 * @param string[] $keys Keys to delete; strings returned by makeKey()
	 */
	public function delete( array $keys );

	/**
	 * Perform a batch of fetch operations.
	 *
	 * @param string[] $keys Keys to get; strings returned by makeKey()
	 * @return int[] Integers
	 */
	public function query( array $keys );
}
