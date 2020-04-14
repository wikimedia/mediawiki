<?php

/**
 * Generic interface for object stores with key encoding methods.
 *
 * @ingroup Cache
 * @since 1.34
 */
interface IStoreKeyEncoder {
	/**
	 * Make a cache key using the "global" keyspace for the given components
	 *
	 * @param string $class Key collection name component
	 * @param string|int ...$components Key components for entity IDs
	 * @return string Keyspace-prepended list of encoded components as a colon-separated value
	 */
	public function makeGlobalKey( $class, ...$components );

	/**
	 * Make a cache key using the default keyspace for the given components
	 *
	 * @param string $class Key collection name component
	 * @param string|int ...$components Key components for entity IDs
	 * @return string Keyspace-prepended list of encoded components as a colon-separated value
	 */
	public function makeKey( $class, ...$components );
}
