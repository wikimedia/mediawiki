<?php
/**
 * Functions to get cache objects
 *
 * @file
 * @ingroup Cache
 */
class ObjectCache {
	static $instances = array();

	/**
	 * Get a cached instance of the specified type of cache object.
	 */
	static function getInstance( $id ) {
		if ( isset( self::$instances[$id] ) ) {
			return self::$instances[$id];
		}

		$object = self::newFromId( $id );
		self::$instances[$id] = $object;
		return $object;
	}

	/**
	 * Create a new cache object of the specified type.
	 */
	static function newFromId( $id ) {
		global $wgObjectCaches;

		if ( !isset( $wgObjectCaches[$id] ) ) {
			throw new MWException( "Invalid object cache type \"$id\" requested. " . 
				"It is not present in \$wgObjectCaches." );
		}

		return self::newFromParams( $wgObjectCaches[$id] );
	}

	/**
	 * Create a new cache object from parameters
	 */
	static function newFromParams( $params ) {
		if ( isset( $params['factory'] ) ) {
			return call_user_func( $params['factory'], $params );
		} elseif ( isset( $params['class'] ) ) {
			$class = $params['class'];
			return new $class( $params );
		} else {
			throw new MWException( "The definition of cache type \"$id\" lacks both " . 
				"factory and class parameters." );
		}
	}

	/**
	 * Factory function referenced from DefaultSettings.php for CACHE_ANYTHING
	 */
	static function newAnything( $params ) {
		global $wgMainCacheType, $wgMessageCacheType, $wgParserCacheType;
		$candidates = array( $wgMainCacheType, $wgMessageCacheType, $wgParserCacheType );
		foreach ( $candidates as $candidate ) {
			if ( $candidate !== CACHE_NONE && $candidate !== CACHE_ANYTHING ) {
				return self::getInstance( $candidate );
			}
		}
		return self::getInstance( CACHE_DB );
	}

	/**
	 * Factory function referenced from DefaultSettings.php for CACHE_ACCEL.
	 */
	static function newAccelerator( $params ) {
		if ( function_exists( 'eaccelerator_get' ) ) {
			$id = 'eaccelerator';
		} elseif ( function_exists( 'apc_fetch') ) {
			$id = 'apc';
		} elseif( function_exists( 'xcache_get' ) ) {
			$id = 'xcache';
		} elseif( function_exists( 'wincache_ucache_get' ) ) {
			$id = 'wincache';
		} else {
			throw new MWException( "CACHE_ACCEL requested but no suitable object " .
				"cache is present. You may want to install APC." );
		}
		return self::newFromId( $id );
	}

	/**
	 * Factory function that creates a memcached client object.
	 * The idea of this is that it might eventually detect and automatically 
	 * support the PECL extension, assuming someone can get it to compile.
	 */
	static function newMemcached( $params ) {
		return new MemcachedPhpBagOStuff( $params );
	}
}
