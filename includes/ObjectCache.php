<?php
/**
 * @package MediaWiki
 * @subpackage Cache
 */

if (!defined('MEDIAWIKI')) die( "Not a valid entry point\n");


# FakeMemCachedClient imitates the API of memcached-client v. 0.1.2.
# It acts as a memcached server with no RAM, that is, all objects are
# cleared the moment they are set. All set operations succeed and all
# get operations return null.
class FakeMemCachedClient {
	function add ($key, $val, $exp = 0) { return true; }
	function decr ($key, $amt=1) { return null; }
	function delete ($key, $time = 0) { return false; }
	function disconnect_all () { }
	function enable_compress ($enable) { }
	function forget_dead_hosts () { }
	function get ($key) { return null; }
	function get_multi ($keys) { return array_pad(array(), count($keys), null); }
	function incr ($key, $amt=1) { return null; }
	function replace ($key, $value, $exp=0) { return false; }
	function run_command ($sock, $cmd) { return null; }
	function set ($key, $value, $exp=0){ return true; }
	function set_compress_threshold ($thresh){ }
	function set_debug ($dbg) { }
	function set_servers ($list) { }
}

global $wgCaches;
$wgCaches = array();

function &wfGetCache( $inputType ) {
	global $wgCaches, $wgMemCachedServers, $wgMemCachedDebug;
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
		if ( !array_key_exists( CACHE_MEMCACHED, $wgCaches ) ){ 
			require_once( 'memcached-client.php' );
			
			class MemCachedClientforWiki extends memcached {
				function _debugprint( $text ) {
					wfDebug( "memcached: $text\n" );
				}
			}

			$wgCaches[CACHE_DB] = new MemCachedClientforWiki( 
				array('persistant' => true, 'compress_threshold' => 1500 ) );
			$cache =& $wgCaches[CACHE_DB];
			$cache->set_servers( $wgMemCachedServers );
			$cache->set_debug( $wgMemCachedDebug );
		}
	} elseif ( $type == CACHE_ACCEL ) {
		if ( !array_key_exists( CACHE_ACCEL, $wgCaches ) ) {
			if ( function_exists( 'eaccelerator_get' ) ) {
				require_once( 'BagOStuff.php' );
				$wgCaches[CACHE_ACCEL] = new eAccelBagOStuff;
			} elseif ( function_exists( 'mmcache_get' ) ) {
				require_once( 'BagOStuff.php' );
				$wgCaches[CACHE_ACCEL] = new TurckBagOStuff;
			} else {
				$wgCaches[CACHE_ACCEL] = false;
			}
		}
		if ( $wgCaches[CACHE_ACCEL] !== false ) {
			$cache =& $wgCaches[CACHE_ACCEL];
		}
	}

	if ( $type == CACHE_DB || ( $inputType == CACHE_ANYTHING && $cache === false ) ) {
		if ( !array_key_exists( CACHE_DB, $wgCaches ) ) {
			require_once( 'BagOStuff.php' );
			$wgCaches[CACHE_DB] = new MediaWikiBagOStuff('objectcache');
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

function &wfGetMainCache() {
	global $wgMainCacheType;
	return wfGetCache( $wgMainCacheType );
}

function &wfGetMessageCacheStorage() {
	global $wgMessageCacheType;
	return wfGetCache( $wgMessageCacheType );
}

function wfGetParserCacheStorage() {
	global $wgParserCacheType;
	return wfGetCache( $wgParserCacheType );
}

?>
