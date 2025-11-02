<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * Interface for the persistence layer of LocalisationCache.
 *
 * The persistence layer is a two-level hierarchical cache. The first level
 * is the language, the second level is the item or subitem.
 *
 * Since the data for a whole language is rebuilt in one operation, it needs
 * to have a fast and atomic method for deleting or replacing all the
 * current data for a given language. The interface reflects this bulk update
 * operation. Callers writing to the cache must first call startWrite(), then
 * will call set() a couple of thousand times, then will call finishWrite()
 * to commit the operation. When finishWrite() is called, the cache is
 * expected to delete all data previously stored for that language.
 *
 * The values stored are PHP variables suitable for serialize(). Implementations
 * of LCStore are responsible for serializing and unserializing.
 *
 * @ingroup Language
 */
interface LCStore {

	/**
	 * Get a value.
	 *
	 * @param string $code Language code
	 * @param string $key Cache key
	 */
	public function get( $code, $key );

	/**
	 * Start a cache write transaction.
	 *
	 * @param string $code Language code
	 */
	public function startWrite( $code );

	/**
	 * Finish a cache write transaction.
	 */
	public function finishWrite();

	/**
	 * Set a key to a given value. startWrite() must be called before this
	 * is called, and finishWrite() must be called after.
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function set( $key, $value );

}
