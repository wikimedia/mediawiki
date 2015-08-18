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

use MediaWiki\Logger\LoggerFactory;

/**
 * Session storage in object cache.
 * Used if $wgSessionsInObjectCache is true.
 *
 * @ingroup Cache
 */
class ObjectCacheSessionHandler {
	/** @var array Map of (session ID => SHA-1 of the data) */
	protected static $hashCache = array();

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
	 * @return BagOStuff
	 */
	protected static function getCache() {
		global $wgSessionCacheType;

		return ObjectCache::getInstance( $wgSessionCacheType );
	}

	/**
	 * Get a cache key for the given session id.
	 *
	 * @param string $id Session id
	 * @return string Cache key
	 */
	protected static function getKey( $id ) {
		return wfMemcKey( 'session', $id );
	}

	/**
	 * @param mixed $data
	 * @return string
	 */
	protected static function getHash( $data ) {
		return sha1( serialize( $data ) );
	}

	/**
	 * Callback when opening a session.
	 *
	 * @param string $save_path Path used to store session files, unused
	 * @param string $session_name Session name
	 * @return bool Success
	 */
	static function open( $save_path, $session_name ) {
		return true;
	}

	/**
	 * Callback when closing a session.
	 * NOP.
	 *
	 * @return bool Success
	 */
	static function close() {
		return true;
	}

	/**
	 * Callback when reading session data.
	 *
	 * @param string $id Session id
	 * @return mixed Session data
	 */
	static function read( $id ) {
		$stime = microtime( true );
		$data = self::getCache()->get( self::getKey( $id ) );
		$real = microtime( true ) - $stime;

		RequestContext::getMain()->getStats()->timing( "session.read", 1000 * $real );

		self::$hashCache = array( $id => self::getHash( $data ) );

		return ( $data === false ) ? '' : $data;
	}

	/**
	 * Callback when writing session data.
	 *
	 * @param string $id Session id
	 * @param string $data Session data
	 * @return bool Success
	 */
	static function write( $id, $data ) {
		global $wgObjectCacheSessionExpiry;

		// Only issue a write if anything changed (PHP 5.6 already does this)
		if ( !isset( self::$hashCache[$id] )
			|| self::getHash( $data ) !== self::$hashCache[$id]
		) {
			$stime = microtime( true );
			self::getCache()->set( self::getKey( $id ), $data, $wgObjectCacheSessionExpiry );
			$real = microtime( true ) - $stime;

			RequestContext::getMain()->getStats()->timing( "session.write", 1000 * $real );
		}

		return true;
	}

	/**
	 * Callback to destroy a session when calling session_destroy().
	 *
	 * @param string $id Session id
	 * @return bool Success
	 */
	static function destroy( $id ) {
		$stime = microtime( true );
		self::getCache()->delete( self::getKey( $id ) );
		$real = microtime( true ) - $stime;

		RequestContext::getMain()->getStats()->timing( "session.destroy", 1000 * $real );

		return true;
	}

	/**
	 * Callback to execute garbage collection.
	 * NOP: Object caches perform garbage collection implicitly
	 *
	 * @param int $maxlifetime Maximum session life time
	 * @return bool Success
	 */
	static function gc( $maxlifetime ) {
		return true;
	}

	/**
	 * Shutdown function.
	 * See the comment inside ObjectCacheSessionHandler::install for rationale.
	 */
	static function handleShutdown() {
		session_write_close();
	}

	/**
	 * Pre-emptive session renewal function
	 */
	static function renewCurrentSession() {
		global $wgObjectCacheSessionExpiry;

		// Once a session is at half TTL, renew it
		$window = $wgObjectCacheSessionExpiry / 2;
		$logger = LoggerFactory::getInstance( 'SessionHandler' );

		$now = microtime( true );
		// Session are only written in object stores when $_SESSION changes,
		// which also renews the TTL ($wgObjectCacheSessionExpiry). If a user
		// is active but not causing session data changes, it may suddenly
		// expire as they view a form, blocking the first submission.
		// Make a dummy change every so often to avoid this.
		if ( !isset( $_SESSION['wsExpiresUnix'] ) ) {
			$_SESSION['wsExpiresUnix'] = $now + $wgObjectCacheSessionExpiry;

			$logger->info( "Set expiry for session " . session_id(), array() );
		} elseif ( ( $now + $window ) > $_SESSION['wsExpiresUnix'] ) {
			$_SESSION['wsExpiresUnix'] = $now + $wgObjectCacheSessionExpiry;

			$logger->info( "Renewed session " . session_id(), array() );
		}
	}
}
