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

/**
 * Holds basic information for an authentication session
 *
 * @ingroup Auth
 * @since 1.26
 */
abstract class AuthnSession implements LoggerAwareInterface {
	const MAX_PRIORITY = 100;

	/** @var string|null */
	protected $key;

	/** @var int */
	protected $priority;

	/** @var BagOStuff|null */
	protected $store = null;

	/** @var int Session data lifetime, in seconds */
	protected $lifetime = 86400;

	/** @var string|null */
	private $dataHash = null;

	/** @var LoggerInterface */
	protected $logger;

	/**
	 * @param BagOStuff $store Backend data store
	 * @param LoggerInterface $logger
	 * @param string|null $key Existing key, if any
	 * @param int $priority Priority, 0 to MAX_PRIORITY
	 */
	public function __construct( BagOStuff $store, LoggerInterface $logger, $key, $priority ) {
		if ( $priority < 0 || $priority > self::MAX_PRIORITY ) {
			throw new InvalidArgumentException( __METHOD__ . ': Invalid priority' );
		}
		$this->store = $store;
		$this->key = $key;
		$this->priority = $priority;
		$this->setLogger( $logger );
		if ( $key === null && !$this->canResetSessionKey() ) {
			throw new InvalidArgumentException( 'Cannot set null key with ' . get_class( $this ) );
		}
	}

	/**
	 * @param LoggerInterface $logger
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Set this session as the handler for $_SESSION
	 * @param int $lifetime
	 */
	public function setupPHPSessionHandler( $lifetime = 86400 ) {
		$this->lifetime = $lifetime;

		// Close any auto-started session, before we replace it
		session_write_close();

		// Tell PHP we want to use this session to back $_SESSION.
		ini_set( 'session.use_cookies', 0 );
		ini_set( 'session.use_trans_sid', 0 );

		// Setup the new session handler
		/// @todo In PHP 5.4, use SessionHandlerInterface
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
	 * If null, the session cannot be persisted.
	 *
	 * @return string|null
	 */
	public function getSessionKey() {
		return $this->key;
	}

	/**
	 * Returns the priority for this session.
	 * @return int
	 */
	public final function getSessionPriority() {
		return $this->priority;
	}

	/**
	 * Returns an AuthenticationRequest type to ask for additional data to pass
	 * to $this->setSessionUserInfo().
	 * @return string|null
	 */
	public function getAuthenticationRequestType() {
		return null;
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
		if ( is_callable( 'session_status' ) ) {
			$active = session_status() === PHP_SESSION_ACTIVE;
		} else {
			// @codeCoverageIgnoreStart
			$active = session_id() !== ''; // Wrong, but best we can do for 5.3
			// @codeCoverageIgnoreEnd
		}
		if ( $active && session_id() === $this->key ) {
			$oldSession = $_SESSION;
			session_destroy();
		} else {
			if ( $active ) {
				$this->logger->error( __METHOD__ . ' called with a different session active' );
				session_write_close();
			}
			$oldSession = false;
		}
		$this->key = $newKey;
		session_id( $this->key );
		wfSuppressWarnings(); // Headers may already have been sent
		session_start();
		wfRestoreWarnings();
		if ( $oldSession !== false ) {
			$_SESSION = $oldSession;
		}

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
	 * @param string $newkey New session key
	 */
	protected function setNewSessionKey( $newkey ) {
		if ( $this->canResetSessionKey() ) {
			throw new BadMethodCallException(
				__METHOD__ . ' must be implemented if ' . get_class( $this ) . '::canResetSessionKey() returns true'
			);
		} else {
			throw new LogicException(
				__METHOD__ . ' was called when ' . get_class( $this ) . '::canResetSessionKey() returns false'
			);
		}
	}

	/**
	 * Returns the user name and info for this session, if any
	 *
	 * Return value is ostensibly the $id, $name, and $token that were passed
	 * to $this->setSessionUserInfo(). If no info is known, return
	 * array( 0, null, null).
	 *
	 * If $this->canSetSessionUserInfo() returns false, $token is ignored and
	 * should be null. Either $id or $name may be 0/null.
	 *
	 * If $this->canSetSessionUserInfo() returns true and an account should be
	 * created for a user that doesn't yet exist locally, return
	 * array( 0, $name, null ).
	 *
	 * @return array Array( int, string|null, string|null )
	 */
	abstract public function getSessionUserInfo();

	/**
	 * Indicate whether the session user info can be changed
	 * @return bool
	 */
	abstract public function canSetSessionUserInfo();

	/**
	 * Set a new user name and token for this session
	 * @param int $id User id, or 0 if no user
	 * @param string|null $name User name, or null if no user
	 * @param string|null $token User token, or null if no user
	 * @param AuthenticationRequest|null $req Optionally, an instance of the
	 *   type returned by $this->getAuthenticationRequestType().
	 */
	public function setSessionUserInfo( $id, $name, $token, $req ) {
		if ( $this->canSetSessionUserInfo() ) {
			throw new BadMethodCallException(
				__METHOD__ . ' must be implemented if ' . get_class( $this ) . '::canSetSessionUserInfo() returns true'
			);
		} else {
			throw new LogicException(
				__METHOD__ . ' was called when ' . get_class( $this ) . '::canSetSessionUserInfo() returns false'
			);
		}
	}

	/**
	 * Get a suggested username for the login form
	 * @return string|null
	 */
	public function suggestLoginUsername() {
		return null;
	}

	/**
	 * Whether HTTPS should be forced
	 * @return bool
	 */
	public function forceHTTPS() {
		return false;
	}

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
			// @codeCoverageIgnoreStart
			throw new RuntimeException( __METHOD__ . ': Session key is not set' );
			// @codeCoverageIgnoreEnd
		}
		return get_class( $this ) . ":{$key}:{$suffix}";
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
			$this->resetSessionKey();
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

	public function __toString() {
		list( $id, $name, $token ) = $this->getSessionUserInfo();
		$class = get_class( $this );
		if ( $id ) {
			return "$class<$id=$name>";
		} else {
			return "$class<anon>";
		}
	}

}
