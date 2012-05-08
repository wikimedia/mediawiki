<?php
/**
 * Session storage in object cache.
 *
 * This file gets included if $wgSessionsInMemcache is set in the config.
 * It redirects session handling functions to store their data in memcached
 * instead of the local filesystem. Depending on circumstances, it may also
 * be necessary to change the cookie settings to work across hostnames.
 * See: http://www.php.net/manual/en/function.session-set-save-handler.php
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
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

function memsess_write_close() {
	session_write_close();
}

