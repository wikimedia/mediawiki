<?php
/**
 * Functions to get cache objects
 *
 * @file
 * @ingroup Cache
 */

global $wgCaches;
$wgCaches = array();

/**
 * Get a cache object.
 * @param $inputType Integer: cache type, one the the CACHE_* constants.
 *
 * @return BagOStuff
 */
function &wfGetCache( $inputType ) {
	global $wgCaches, $wgMemCachedServers, $wgMemCachedDebug, $wgMemCachedPersistent;
	$cache = false;

	if ( $inputType == CACHE_ANYTHING ) {
		reset( $wgCaches );
		$type = key( $wgCaches );
		if ( $type === false || $type === CACHE_NONE ) {
			$type = CACHE_DB;
		}
	} else {
		$type = $inputType;
	}

	if ( $type == CACHE_MEMCACHED ) {
		if ( !array_key_exists( CACHE_MEMCACHED, $wgCaches ) ) {
			$wgCaches[CACHE_MEMCACHED] = new MemCachedClientforWiki(
				array('persistant' => $wgMemCachedPersistent, 'compress_threshold' => 1500 ) );
			$wgCaches[CACHE_MEMCACHED]->set_servers( $wgMemCachedServers );
			$wgCaches[CACHE_MEMCACHED]->set_debug( $wgMemCachedDebug );
		}
		$cache =& $wgCaches[CACHE_MEMCACHED];
	} elseif ( $type == CACHE_ACCEL ) {
		if ( !array_key_exists( CACHE_ACCEL, $wgCaches ) ) {
			if ( function_exists( 'eaccelerator_get' ) ) {
				$wgCaches[CACHE_ACCEL] = new eAccelBagOStuff;
			} elseif ( function_exists( 'apc_fetch') ) {
				$wgCaches[CACHE_ACCEL] = new APCBagOStuff;
			} elseif( function_exists( 'xcache_get' ) ) {
				$wgCaches[CACHE_ACCEL] = new XCacheBagOStuff();
			} elseif( function_exists( 'wincache_ucache_get' ) ) {
				$wgCaches[CACHE_ACCEL] = new WinCacheBagOStuff();
			} else {
				$wgCaches[CACHE_ACCEL] = false;
			}
		}
		if ( $wgCaches[CACHE_ACCEL] !== false ) {
			$cache =& $wgCaches[CACHE_ACCEL];
		}
	} elseif ( $type == CACHE_DBA ) {
		if ( !array_key_exists( CACHE_DBA, $wgCaches ) ) {
			$wgCaches[CACHE_DBA] = new DBABagOStuff;
		}
		$cache =& $wgCaches[CACHE_DBA];
	}

	if ( $type == CACHE_DB || ( $inputType == CACHE_ANYTHING && $cache === false ) ) {
		if ( !array_key_exists( CACHE_DB, $wgCaches ) ) {
			$wgCaches[CACHE_DB] = new SqlBagOStuff('objectcache');
		}
		$cache =& $wgCaches[CACHE_DB];
	}

	if ( $cache === false ) {
		if ( !array_key_exists( CACHE_NONE, $wgCaches ) ) {
			$wgCaches[CACHE_NONE] = new FakeMemCachedClient;
		}
		$cache =& $wgCaches[CACHE_NONE];
	}

	return $cache;
}

/** Get the main cache object */
function &wfGetMainCache() {
	global $wgMainCacheType;
	$ret =& wfGetCache( $wgMainCacheType );
	return $ret;
}

/** Get the cache object used by the message cache */
function &wfGetMessageCacheStorage() {
	global $wgMessageCacheType;
	$ret =& wfGetCache( $wgMessageCacheType );
	return $ret;
}

/** Get the cache object used by the parser cache */
function &wfGetParserCacheStorage() {
	global $wgParserCacheType;
	$ret =& wfGetCache( $wgParserCacheType );
	return $ret;
}
