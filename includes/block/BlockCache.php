<?php

namespace MediaWiki\Block;

use MediaWiki\User\UserIdentity;

/**
 * @internal For use by BlockManager
 */
class BlockCache {
	/** @var BlockCacheEntry[] The cache entries indexed by partial key */
	private $cache = [];

	/**
	 * Get a cached Block for the given key, or null if there is no cache entry,
	 * or false if there is a cache entry indicating that the given target is
	 * not blocked.
	 *
	 * @param BlockCacheKey $key
	 * @return AbstractBlock|false|null
	 */
	public function get( BlockCacheKey $key ) {
		$entry = $this->cache[$key->getPartialKey()] ?? null;
		if ( $entry === null ) {
			return null;
		}
		return $key->matchesStored( $entry->key ) ? $entry->block : null;
	}

	/**
	 * Set a block cache entry
	 *
	 * @param BlockCacheKey $key The bundled block cache parameters
	 * @param AbstractBlock|false $value The block, or false to indicate that
	 *   the target is not blocked.
	 */
	public function set( BlockCacheKey $key, $value ) {
		$this->cache[$key->getPartialKey()] = new BlockCacheEntry( $key, $value );
	}

	/**
	 * Clear all block cache entries associated with a user
	 */
	public function clearUser( UserIdentity $user ) {
		foreach ( $this->cache as $partialKey => $entry ) {
			if ( $entry->key->isUser( $user ) ) {
				unset( $this->cache[$partialKey] );
			}
		}
	}
}
