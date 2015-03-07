<?php
/**
 * Authn session
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
 * @ingroup Auth
 */

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Holds basic information for an authentication session
 *
 * @ingroup Auth
 * @since 1.25
 */
abstract class AuthnSession implements LoggerAwareInterface {
	const MAX_PRIORITY = 100;

	/** @var string|null */
	protected $key;

	/** @var int */
	protected $priority;

	/** @var string|null */
	protected $key;

	/** @var BagOStuff|null */
	protected $store = null;

	/** @var int Session data lifetime, in seconds */
	protected $lifetime = 86400;

	/** @var string|null */
	private $dataHash = null;

	/** @var LoggerInterface */
	protected $logger;

	/**
	 * @param string|null $key Existing key, if any
	 * @param int $priority Priority, 0 to MAX_PRIORITY
	 */
	public function __construct( $key, $priority ) {
		if ( $priority < 0 || $priority > self::MAX_PRIORITY ) {
			throw new RuntimeException( __METHOD__ . ': Invalid priority' );
		}
		$this->key = $key;
		$this->priority = $priority;
		$this->setLogger( new NullLogger() );
		if ( $key === null && !$this->canResetSessionKey() ) {
			throw new RuntimeException( 'Cannot set null key with ' . __CLASS__ );
		}
	}

	/**
	 * @param LoggerInterface $logger
	 * @return null
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Set the storage backend for this session and uses it for $_SESSION
	 * @param BagOStuff $store
	 * @param int $lifetime
	 */
	public function setupPHPSessionHandler( BagOStuff $store, $lifetime = 86400 ) {
		$this->store = $store;
		$this->lifetime = $lifetime;

		// Tell PHP we want to use this session to back $_SESSION.
		ini_set( 'session.use_cookies', 0 );
		ini_set( 'session.use_trans_sid', 0 );

		/** @todo In PHP 5.4, use SessionHandlerInterface */
		session_set_save_handler(
			array( $this, 'open' ),
			array( $this, 'close' ),
			array( $this, 'read' ),
			array( $this, 'write' ),
			array( $this, 'destroy' ),
			array( $this, 'gc' )
		);
		register_shutdown_function( 'session_write_close' );

		session_cache_limiter( 'private, must-revalidate' );

		// Activate session if we have a key
		if ( $this->key ) {
			session_id( $this->key );
			session_start();
		}
	}

	/**
	 * Returns the key for this session.
	 *
	 * Different sessions from the same provider must not share a key.
	 * If null, the session will not be persisted.
	 *
	 * @return string|null
	 */
	public function getSessionKey() {
		return $this->key;
	}

	/**
	 * Reset the user-visible key for this session. The session remains valid.
	 *
	 * This may or may not actually change the key, depending on whether
	 * session hijacking is even possible with this session type.
	 *
	 * @return string New key
	 * @throws RuntimeException
	 */
	public function resetSessionKey() {
		if ( $this->store === null ) {
			throw new RuntimeException( __METHOD__ . ': Data store is not set' );
		}

		// See if the backend actually supports changing keys
		if ( !$this->canResetSessionKey() ) {
			return $this->key;
		}

		// Find a new key that's not already in use
		do {
			$newKey = MWCryptRand::generateHex( 32 );
		} while ( $this->store->get( $this->getStorageKey( 'data', $newKey ) ) !== false );

		// Give the subclass a chance to alter its data
		$this->setNewSessionKey( $newKey );

		// Copy session data from the old key to the new
		$tmp = $_SESSION;
		session_destroy();
		$this->key = $newKey;
		session_id( $this->key );
		session_start();
		$_SESSION = $tmp;

		return $this->key;
	}

	/**
	 * Called from resetSessionKey() to check if the key can be reset
	 * @return bool
	 */
	abstract protected function canResetSessionKey();

	/**
	 * Called from resetSessionKey() to actually reset the key
	 *
	 * For example, CookieAuthnSession might set a new cookie while
	 * OAuthAuthnSession would just return false since the key can't be
	 * hijacked without hijacking the whole account.
	 *
	 * @return bool Whether the key was actually set
	 */
	abstract protected function setNewSessionKey( $newkey );

	/**
	 * Returns the authenticated user name for this session, if any
	 * @return string|null
	 */
	abstract public function getAuthenticatedUserName();

	/**
	 * Indicate whether the authenticated username can be changed
	 * @return bool
	 */
	abstract public function canSetAuthenticatedUserName();

	/**
	 * Set a new authenticated user name for this session
	 * @param string|null
	 */
	abstract public function setAuthenticatedUserName( $username );

	/**
	 * Get a key for use with $this->store
	 * @param string $suffix
	 * @param string $key Override $this->key
	 * @return string
	 * @throws RuntimeException
	 */
	protected function getStorageKey( $suffix, $key = null ) {
		if ( $key === null ) {
			$key = $this->key;
		}
		if ( $key === null ) {
			throw new RuntimeException( __METHOD__ . ': Session key is not set' );
		}
		return __CLASS__ . ":{$key}:{$suffix}";
	}

	/**
	 * @name Session handler methods
	 * @{
	 */

	/**
	 * "Constructor" for session handling
	 * @param string $savePath Ignored
	 * @param string $sessionName Ignored
	 * @return bool
	 */
	public function open( $savePath, $sessionName ) {
		if ( $this->key === null ) {
			$this->logger->error( __METHOD__ . ' called with null session key!' );
		}
		return $this->key !== null;
	}

	/**
	 * "Destructor" for session handling
	 * @return bool
	 */
	public function close() {
		return true;
	}

	/**
	 * Calculate a hash of $data, to avoid unnecessary writes
	 * @param string $data
	 * @return string
	 */
	private static function getHash( $data ) {
		return sha1( serialize( $data ) );
	}

	/**
	 * Load serialized session data
	 * @param string $sessionId
	 * @return string
	 */
	public function read( $sessionId ) {
		if ( $this->key !== $sessionId ) {
			$this->logger->error( __METHOD__ . ' called with mismatched session ID!' );
			return '';
		}

		$data = $this->store->get( $this->getStorageKey( 'data' ) );
		$this->dataHash = self::getHash( $data );
		if ( $data === false ) {
			$data = '';
		}
		return $data;
	}

	/**
	 * Save serialized session data
	 * @param string $sessionId
	 * @param string $data
	 * @return bool
	 */
	public function write( $sessionId, $data ) {
		if ( $this->key !== $sessionId ) {
			$this->logger->error( __METHOD__ . ' called with mismatched session ID!' );
			return false;
		}

		$hash = self::getHash( $data );
		if ( $this->dataHash !== $hash ) {
			if ( $this->key === null ) {
				$this->resetSessionKey();
			}
			$this->store->set( $this->getStorageKey( 'data' ), $data, $this->lifetime );
			$this->dataHash = $hash;
		}
		return true;
	}

	/**
	 * Delete saved session data
	 * @param string $sessionId
	 * @return bool
	 */
	public function destroy( $sessionId ) {
		if ( $this->key !== $sessionId ) {
			$this->logger->error( __METHOD__ . ' called with mismatched session ID!' );
			return false;
		}

		$this->store->delete( $this->getStorageKey( 'data' ) );
		$this->dataHash = self::getHash( '' );
		return true;
	}

	/**
	 * Callback to execute garbage collection.
	 * @param int $maxlifetime Maximum session life time (ignored)
	 * @return bool
	 */
	public function gc( $maxlifetime ) {
		$before = date( 'YmdHis', time() );
		$this->store->deleteObjectsExpiringBefore( $before );
		return true;
	}

	/**@}*/
}
