<?php
/**
 * This file gets included if $wgSessionsInMemcache is set in the config.
 * It redirects session handling functions to store their data in memcached
 * instead of the local filesystem. Depending on circumstances, it may also
 * be necessary to change the cookie settings to work across hostnames.
 * See: http://www.php.net/manual/en/function.session-set-save-handler.php
 *
 * @file
 * @ingroup Cache
 */

/**
 * Get a cache key for the given session id.
 *
 * @param $id String: session id
 * @return String: cache key
 */
function memsess_key( $id ) {
	return wfMemcKey( 'session', $id );
}

/**
 * Callback when opening a session.
 * NOP: $wgMemc should be set up already.
 *
 * @param $save_path String: path used to store session files, unused
 * @param $session_name String: session name
 * @return Boolean: success
 */
function memsess_open( $save_path, $session_name ) {
	return true;
}

/**
 * Callback when closing a session.
 * NOP.
 *
 * @return Boolean: success
 */
function memsess_close() {
	return true;
}

/**
 * Callback when reading session data.
 *
 * @param $id String: session id
 * @return Mixed: session data
 */
function memsess_read( $id ) {
	global $wgMemc;
	$data = $wgMemc->get( memsess_key( $id ) );
	if( ! $data ) return '';
	return $data;
}

/**
 * Callback when writing session data.
 *
 * @param $id String: session id
 * @param $data Mixed: session data
 * @return Boolean: success
 */
function memsess_write( $id, $data ) {
	global $wgMemc;
	$wgMemc->set( memsess_key( $id ), $data, 3600 );
	return true;
}

/**
 * Callback to destroy a session when calling session_destroy().
 *
 * @param $id String: session id
 * @return Boolean: success
 */
function memsess_destroy( $id ) {
	global $wgMemc;

	$wgMemc->delete( memsess_key( $id ) );
	return true;
}

/**
 * Callback to execute garbage collection.
 * NOP: Memcached performs garbage collection.
 *
 * @param $maxlifetime Integer: maximum session life time
 * @return Boolean: success
 */
function memsess_gc( $maxlifetime ) {
	return true;
}

session_set_save_handler( 'memsess_open', 'memsess_close', 'memsess_read', 'memsess_write', 'memsess_destroy', 'memsess_gc' );
