<?php
class ObjectFileCache extends FileCacheBase {

	public static function newFromKey( $key, $type ) {
		$cache = new self();

		$allowedTypes = self::cacheableTypes();
		if ( !isset( $allowedTypes[$type] ) ) {
			throw new MWException( "Invalid filecache type given." );
		}
		$cache->mKey = (string)$key;
		$cache->mType = (string)$type;
		$cache->mExt = $allowedTypes[$cache->mType];

		return $cache;
	}

	/*
	 * Get the type => extension mapping
	 * @return array
	 */
	protected static function cacheableTypes() {
		return array( 'resources-js' => 'js', 'resources-css' => 'css' );
	}

	/**
	 * Get the base file cache directory
	 * @return string
	 */
	protected function cacheDirectory() {
		global $wgCacheDirectory, $wgFileCacheDirectory, $wgFileCacheDepth;
		if ( $wgFileCacheDirectory ) {
			$dir = $wgFileCacheDirectory;
		} elseif ( $wgCacheDirectory ) {
			$dir = "$wgCacheDirectory/object";
		} else {
			throw new MWException( 'Please set $wgCacheDirectory in LocalSettings.php if you wish to use the HTML file cache' );
		}
		return $dir;
	}
}
