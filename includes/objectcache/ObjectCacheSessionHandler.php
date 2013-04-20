<?php
/**
 * Session storage in object cache.
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
 * Session storage in object cache.
 * Used if $wgSessionsInObjectCache is true.
 *
 * @ingroup Cache
 */
class ObjectCacheSessionHandler {
	/**
	 * Install a session handler for the current web request
	 */
	static function install() {
		session_set_save_handler(
			array( __CLASS__, 'open' ),
			array( __CLASS__, 'close' ),
			array( __CLASS__, 'read' ),
			array( __CLASS__, 'write' ),
			array( __CLASS__, 'destroy' ),
			array( __CLASS__, 'gc' ) );

		// It's necessary to register a shutdown function to call session_write_close(),
		// because by the time the request shutdown function for the session module is
		// called, $wgMemc has already been destroyed. Shutdown functions registered
		// this way are called before object destruction.
		register_shutdown_function( array( __CLASS__, 'handleShutdown' ) );
	}

	/**
	 * Get the cache storage object to use for session storage
	 */
	static function getCache() {
		global $wgSessionCacheType;
		return ObjectCache::getInstance( $wgSessionCacheType );
	}

	/**
	 * Get a cache key for the given session id.
	 *
	 * @param string $id session id
	 * @return String: cache key
	 */
	static function getKey( $id ) {
		return wfMemcKey( 'session', $id );
	}

	/**
	 * Callback when opening a session.
	 *
	 * @param $save_path String: path used to store session files, unused
	 * @param $session_name String: session name
	 * @return Boolean: success
	 */
	static function open( $save_path, $session_name ) {
		return true;
	}

	/**
	 * Callback when closing a session.
	 * NOP.
	 *
	 * @return Boolean: success
	 */
	static function close() {
		return true;
	}

	/**
	 * Callback when reading session data.
	 *
	 * @param string $id session id
	 * @return Mixed: session data
	 */
	static function read( $id ) {
		$data = self::getCache()->get( self::getKey( $id ) );
		if ( $data === false ) {
			return '';
		}
		return $data;
	}

	/**
	 * Callback when writing session data.
	 *
	 * @param string $id session id
	 * @param $data Mixed: session data
	 * @return Boolean: success
	 */
	static function write( $id, $data ) {
		global $wgObjectCacheSessionExpiry;
		self::getCache()->set( self::getKey( $id ), $data, $wgObjectCacheSessionExpiry );
		return true;
	}

	/**
	 * Callback to destroy a session when calling session_destroy().
	 *
	 * @param string $id session id
	 * @return Boolean: success
	 */
	static function destroy( $id ) {
		self::getCache()->delete( self::getKey( $id ) );
		return true;
	}

	/**
	 * Callback to execute garbage collection.
	 * NOP: Object caches perform garbage collection implicitly
	 *
	 * @param $maxlifetime Integer: maximum session life time
	 * @return Boolean: success
	 */
	static function gc( $maxlifetime ) {
		return true;
	}

	/**
	 * Shutdown function. See the comment inside ObjectCacheSessionHandler::install
	 * for rationale.
	 */
	static function handleShutdown() {
		session_write_close();
	}
}
