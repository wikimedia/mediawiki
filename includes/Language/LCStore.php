<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Language;

/**
 * Base class for the persistence layer of LocalisationCache.
 *
 * The persistence layer is a two-level hierarchical cache. The first level
 * is the language, the second level is the item or subitem.
 *
 * Since the data for a whole language is rebuilt in one operation, it needs
 * to have a fast and atomic method for deleting or replacing all the
 * current data for a given language. The interface reflects this bulk update
 * operation. Callers writing to the cache must first call startWrite(), then
 * will call set() a couple of thousand times (or setMessages() once for all
 * messages), then will call finishWrite()
 * to commit the operation. When finishWrite() is called, the cache is
 * expected to delete all data previously stored for that language.
 *
 * The values stored are PHP variables suitable for serialize(). Implementations
 * of LCStore are responsible for serializing and unserializing.
 *
 * @ingroup Language
 */
abstract class LCStore {

	/**
	 * Get a value.
	 *
	 * @param string $code Language code
	 * @param string $key Cache key
	 */
	abstract public function get( $code, $key );

	/**
	 * Start a cache write transaction.
	 *
	 * @param string $code Language code
	 */
	abstract public function startWrite( $code );

	/**
	 * Finish a cache write transaction.
	 */
	abstract public function finishWrite();

	/**
	 * Set a key to a given value. startWrite() must be called before this
	 * is called, and finishWrite() must be called after.
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	abstract public function set( $key, $value );

	/**
	 * Can this cache type delay fallback lookups to read time?
	 *
	 * Returning true will cause the fallbacks to be skipped when
	 * recaching data, and instead performed via multiple reads if
	 * necessary at runtime.
	 *
	 * This is best when performing lookups is extremely low-cost,
	 * such as LCStoreStaticArray with the `lateFallback` option.
	 *
	 * When reading from disk or network, trading off the space
	 * for fewer lookups may still be a win. Returning false will
	 * keep the classic behavior of pre-filling all the fallback
	 * chains for each language.
	 */
	abstract public function lateFallback(): bool;

	/**
	 * Set all messages at once. startWrite() must be called before this
	 * is called, and finishWrite() must be called after.
	 *
	 * This is equivalent to calling set( "messages:$key", $value ) for each
	 * message. Subclasses can override this to avoid the per-message overhead.
	 *
	 * @since 1.48
	 * @param array $messages Message values by message key
	 */
	public function setMessages( array $messages ): void {
		foreach ( $messages as $key => $value ) {
			$this->set( "messages:$key", $value );
		}
	}
}

/** @deprecated class alias since 1.46 */
class_alias( LCStore::class, 'LCStore' );
