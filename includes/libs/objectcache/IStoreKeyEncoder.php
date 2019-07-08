<?php

/**
 * Generic interface for object stores with key encoding methods.
 *
 * @ingroup Cache
 * @since 1.34
 */
interface IStoreKeyEncoder {
	/**
	 * Make a global cache key.
	 *
	 * @param string $class Key class
	 * @param string|null $component [optional] Key component (starting with a key collection name)
	 * @return string Colon-delimited list of $keyspace followed by escaped components of $args
	 */
	public function makeGlobalKey( $class, $component = null );

	/**
	 * Make a cache key, scoped to this instance's keyspace.
	 *
	 * @param string $class Key class
	 * @param string|null $component [optional] Key component (starting with a key collection name)
	 * @return string Colon-delimited list of $keyspace followed by escaped components of $args
	 */
	public function makeKey( $class, $component = null );
}
