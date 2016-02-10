<?php
/**
 * MediaWiki session
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
 * @ingroup Session
 */

namespace MediaWiki\Session;

use Psr\Log\LoggerInterface;
use User;
use WebRequest;

/**
 * Manages data for an an authenticated session
 *
 * A Session represents the fact that the current HTTP request is part of a
 * session. There are two broad types of Sessions, based on whether they
 * return true or false from self::canSetUser():
 * * When true (mutable), the Session identifies multiple requests as part of
 *   a session generically, with no tie to a particular user.
 * * When false (immutable), the Session identifies multiple requests as part
 *   of a session by identifying and authenticating the request itself as
 *   belonging to a particular user.
 *
 * The Session object also serves as a replacement for PHP's $_SESSION,
 * managing access to per-session data.
 *
 * @ingroup Session
 * @since 1.27
 */
final class Session implements \Countable, \Iterator, \ArrayAccess {
	/** @var SessionBackend Session backend */
	private $backend;

	/** @var int Session index */
	private $index;

	/** @var LoggerInterface */
	private $logger;

	/**
	 * @param SessionBackend $backend
	 * @param int $index
	 * @param LoggerInterface $logger
	 */
	public function __construct( SessionBackend $backend, $index, LoggerInterface $logger ) {
		$this->backend = $backend;
		$this->index = $index;
		$this->logger = $logger;
	}

	public function __destruct() {
		$this->backend->deregisterSession( $this->index );
	}

	/**
	 * Returns the session ID
	 * @return string
	 */
	public function getId() {
		return $this->backend->getId();
	}

	/**
	 * Returns the SessionId object
	 * @private For internal use by WebRequest
	 * @return SessionId
	 */
	public function getSessionId() {
		return $this->backend->getSessionId();
	}

	/**
	 * Changes the session ID
	 * @return string New ID (might be the same as the old)
	 */
	public function resetId() {
		return $this->backend->resetId();
	}

	/**
	 * Fetch the SessionProvider for this session
	 * @return SessionProviderInterface
	 */
	public function getProvider() {
		return $this->backend->getProvider();
	}

	/**
	 * Indicate whether this session is persisted across requests
	 *
	 * For example, if cookies are set.
	 *
	 * @return bool
	 */
	public function isPersistent() {
		return $this->backend->isPersistent();
	}

	/**
	 * Make this session persisted across requests
	 *
	 * If the session is already persistent, equivalent to calling
	 * $this->renew().
	 */
	public function persist() {
		$this->backend->persist();
	}

	/**
	 * Indicate whether the user should be remembered independently of the
	 * session ID.
	 * @return bool
	 */
	public function shouldRememberUser() {
		return $this->backend->shouldRememberUser();
	}

	/**
	 * Set whether the user should be remembered independently of the session
	 * ID.
	 * @param bool $remember
	 */
	public function setRememberUser( $remember ) {
		$this->backend->setRememberUser( $remember );
	}

	/**
	 * Returns the request associated with this session
	 * @return WebRequest
	 */
	public function getRequest() {
		return $this->backend->getRequest( $this->index );
	}

	/**
	 * Returns the authenticated user for this session
	 * @return User
	 */
	public function getUser() {
		return $this->backend->getUser();
	}

	/**
	 * Fetch the rights allowed the user when this session is active.
	 * @return null|string[] Allowed user rights, or null to allow all.
	 */
	public function getAllowedUserRights() {
		return $this->backend->getAllowedUserRights();
	}

	/**
	 * Indicate whether the session user info can be changed
	 * @return bool
	 */
	public function canSetUser() {
		return $this->backend->canSetUser();
	}

	/**
	 * Set a new user for this session
	 * @note This should only be called when the user has been authenticated
	 * @param User $user User to set on the session.
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 */
	public function setUser( $user ) {
		$this->backend->setUser( $user );
	}

	/**
	 * Get a suggested username for the login form
	 * @return string|null
	 */
	public function suggestLoginUsername() {
		return $this->backend->suggestLoginUsername( $this->index );
	}

	/**
	 * Whether HTTPS should be forced
	 * @return bool
	 */
	public function shouldForceHTTPS() {
		return $this->backend->shouldForceHTTPS();
	}

	/**
	 * Set whether HTTPS should be forced
	 * @param bool $force
	 */
	public function setForceHTTPS( $force ) {
		$this->backend->setForceHTTPS( $force );
	}

	/**
	 * Fetch the "logged out" timestamp
	 * @return int
	 */
	public function getLoggedOutTimestamp() {
		return $this->backend->getLoggedOutTimestamp();
	}

	/**
	 * Set the "logged out" timestamp
	 * @param int $ts
	 */
	public function setLoggedOutTimestamp( $ts ) {
		$this->backend->setLoggedOutTimestamp( $ts );
	}

	/**
	 * Fetch provider metadata
	 * @protected For use by SessionProvider subclasses only
	 * @return mixed
	 */
	public function getProviderMetadata() {
		return $this->backend->getProviderMetadata();
	}

	/**
	 * Delete all session data and clear the user (if possible)
	 */
	public function clear() {
		$data = &$this->backend->getData();
		if ( $data ) {
			$data = array();
			$this->backend->dirty();
		}
		if ( $this->backend->canSetUser() ) {
			$this->backend->setUser( new User );
		}
		$this->backend->save();
	}

	/**
	 * Renew the session
	 *
	 * Resets the TTL in the backend store if the session is near expiring, and
	 * re-persists the session to any active WebRequests if persistent.
	 */
	public function renew() {
		$this->backend->renew();
	}

	/**
	 * Fetch a copy of this session attached to an alternative WebRequest
	 *
	 * Actions on the copy will affect this session too, and vice versa.
	 *
	 * @param WebRequest $request Any existing session associated with this
	 *  WebRequest object will be overwritten.
	 * @return Session
	 */
	public function sessionWithRequest( WebRequest $request ) {
		$request->setSessionId( $this->backend->getSessionId() );
		return $this->backend->getSession( $request );
	}

	/**
	 * Fetch a value from the session
	 * @param string|int $key
	 * @param mixed $default Returned if $this->exists( $key ) would be false
	 * @return mixed
	 */
	public function get( $key, $default = null ) {
		$data = &$this->backend->getData();
		return array_key_exists( $key, $data ) ? $data[$key] : $default;
	}

	/**
	 * Test if a value exists in the session
	 * @note Unlike isset(), null values are considered to exist.
	 * @param string|int $key
	 * @return bool
	 */
	public function exists( $key ) {
		$data = &$this->backend->getData();
		return array_key_exists( $key, $data );
	}

	/**
	 * Set a value in the session
	 * @param string|int $key
	 * @param mixed $value
	 */
	public function set( $key, $value ) {
		$data = &$this->backend->getData();
		if ( !array_key_exists( $key, $data ) || $data[$key] !== $value ) {
			$data[$key] = $value;
			$this->backend->dirty();
		}
	}

	/**
	 * Remove a value from the session
	 * @param string|int $key
	 */
	public function remove( $key ) {
		$data = &$this->backend->getData();
		if ( array_key_exists( $key, $data ) ) {
			unset( $data[$key] );
			$this->backend->dirty();
		}
	}

	/**
	 * Fetch a CSRF token from the session
	 *
	 * Note that this does not persist the session, which you'll probably want
	 * to do if you want the token to actually be useful.
	 *
	 * @param string|string[] $salt Token salt
	 * @param string $key Token key
	 * @return MediaWiki\\Session\\SessionToken
	 */
	public function getToken( $salt = '', $key = 'default' ) {
		$new = false;
		$secrets = $this->get( 'wsTokenSecrets' );
		if ( !is_array( $secrets ) ) {
			$secrets = array();
		}
		if ( isset( $secrets[$key] ) && is_string( $secrets[$key] ) ) {
			$secret = $secrets[$key];
		} else {
			$secret = \MWCryptRand::generateHex( 32 );
			$secrets[$key] = $secret;
			$this->set( 'wsTokenSecrets', $secrets );
			$new = true;
		}
		if ( is_array( $salt ) ) {
			$salt = join( '|', $salt );
		}
		return new Token( $secret, (string)$salt, $new );
	}

	/**
	 * Remove a CSRF token from the session
	 *
	 * The next call to self::getToken() with $key will generate a new secret.
	 *
	 * @param string $key Token key
	 */
	public function resetToken( $key = 'default' ) {
		$secrets = $this->get( 'wsTokenSecrets' );
		if ( is_array( $secrets ) && isset( $secrets[$key] ) ) {
			unset( $secrets[$key] );
			$this->set( 'wsTokenSecrets', $secrets );
		}
	}

	/**
	 * Remove all CSRF tokens from the session
	 */
	public function resetAllTokens() {
		$this->remove( 'wsTokenSecrets' );
	}

	/**
	 * Delay automatic saving while multiple updates are being made
	 *
	 * Calls to save() or clear() will not be delayed.
	 *
	 * @return \ScopedCallback When this goes out of scope, a save will be triggered
	 */
	public function delaySave() {
		return $this->backend->delaySave();
	}

	/**
	 * Save the session
	 */
	public function save() {
		$this->backend->save();
	}

	/**
	 * @name Interface methods
	 * @{
	 */

	public function count() {
		$data = &$this->backend->getData();
		return count( $data );
	}

	public function current() {
		$data = &$this->backend->getData();
		return current( $data );
	}

	public function key() {
		$data = &$this->backend->getData();
		return key( $data );
	}

	public function next() {
		$data = &$this->backend->getData();
		next( $data );
	}

	public function rewind() {
		$data = &$this->backend->getData();
		reset( $data );
	}

	public function valid() {
		$data = &$this->backend->getData();
		return key( $data ) !== null;
	}

	/**
	 * @note Despite the name, this seems to be intended to implement isset()
	 *  rather than array_key_exists(). So do that.
	 */
	public function offsetExists( $offset ) {
		$data = &$this->backend->getData();
		return isset( $data[$offset] );
	}

	/**
	 * @note This supports indirect modifications but can't mark the session
	 *  dirty when those happen. SessionBackend::save() checks the hash of the
	 *  data to detect such changes.
	 * @note Accessing a nonexistent key via this mechanism causes that key to
	 *  be created with a null value, and does not raise a PHP warning.
	 */
	public function &offsetGet( $offset ) {
		$data = &$this->backend->getData();
		if ( !array_key_exists( $offset, $data ) ) {
			$ex = new \Exception( "Undefined index (auto-adds to session with a null value): $offset" );
			$this->logger->debug( $ex->getMessage(), array( 'exception' => $ex ) );
		}
		return $data[$offset];
	}

	public function offsetSet( $offset, $value ) {
		$this->set( $offset, $value );
	}

	public function offsetUnset( $offset ) {
		$this->remove( $offset );
	}

	/**@}*/

}
