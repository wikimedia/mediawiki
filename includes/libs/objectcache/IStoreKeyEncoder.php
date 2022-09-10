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
	 * Callers should:
	 *   - Limit the collection name (first component) to 48 characters
	 *   - Use hashes for any components based on user-supplied input
	 *
	 * Encoding is limited to the escaping of delimiter (":") and escape ("%") characters.
	 * Any backend-specific encoding should be delegated to methods that use the network.
	 *
	 * @param string $collection Key collection name component
	 * @param string|int ...$components Additional, ordered, key components for entity IDs
	 * @return string Colon-separated, keyspace-prepended, ordered list of encoded components
	 */
	public function makeGlobalKey( $collection, ...$components );

	/**
	 * Make a cache key using the default keyspace for the given components
	 *
	 * Callers should:
	 *   - Limit the collection name (first component) to 48 characters
	 *   - Use hashes for any components based on user-supplied input
	 *
	 * Encoding is limited to the escaping of delimiter (":") and escape ("%") characters.
	 * Any backend-specific encoding should be delegated to methods that use the network.
	 *
	 * @param string $collection Key collection name component
	 * @param string|int ...$components Additional, ordered, key components for entity IDs
	 * @return string Colon-separated, keyspace-prepended, ordered list of encoded components
	 */
	public function makeKey( $collection, ...$components );
}
