<?php
/**
 * Contain the ObjectFileCache class
 * @file
 * @ingroup Cache
 */
class ObjectFileCache extends FileCacheBase {
	/**
	 * Construct an ObjectFileCache from a key and a type
	 * @param $key string
	 * @param $type string
	 * @return ObjectFileCache
	 */
	public static function newFromKey( $key, $type ) {
		$cache = new self();

		$cache->mKey = (string)$key;
		$cache->mType = (string)$type;

		return $cache;
	}

	/**
	 * Get the base file cache directory
	 * @return string
	 */
	protected function cacheDirectory() {
		return $this->baseCacheDirectory() . '/object';
	}
}
