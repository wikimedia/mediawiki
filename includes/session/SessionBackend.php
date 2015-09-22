<?php
/**
 * MediaWiki session backend
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

use BagOStuff;
use User;
use WebRequest;

/**
 * This is the actual workhorse for Session. It should not be used directly.
 *
 * The main reasons for this split are:
 * 1. A session can be loaded from a request and by ID, and we don't want it to
 *    break if loaded by ID before being loaded from the request.
 * 2. We want reasonable garbage collection behavior, but we also want the
 *    SessionManager to hold a reference to every active session.
 *
 * @ingroup Session
 * @since 1.27
 */
final class SessionBackend {
	/** @var SessionId */
	private $id;

	private $persist = false;
	private $remember = false;
	private $forceHTTPS = false;

	/** @var array|null */
	private $data = null;

	private $forcePersist = false;
	private $metaDirty = false;
	private $dataDirty = false;

	/** @var string Used to detect subarray modifications */
	private $dataHash = null;

	/** @var BagOStuff */
	private $store;

	/** @var int */
	private $lifetime;

	/** @var User */
	private $user;

	private $curIndex = 0;

	/** @var WebRequest[] Session requests */
	private $requests = array();

	/** @var SessionProvider provider */
	private $provider;

	private $expires = 0;

	private $usePhpSessionHandling = true;

	/**
	 * @param SessionId $id Session ID object
	 * @param SessionInfo $info Session info to populate from
	 * @param BagOStuff $store Backend data store
	 * @param int $lifetime Session data lifetime
	 */
	public function __construct(
		SessionId $id, SessionInfo $info, BagOStuff $store, $lifetime
	) {
		$phpSessionHandling = \RequestContext::getMain()->getConfig()->get( 'PHPSessionHandling' );
		$this->usePhpSessionHandling = $phpSessionHandling !== 'disable';

		if ( $info->getUser() && !$info->getUser()->isAuthenticated() ) {
			throw new \InvalidArgumentException(
				"Refusing to create session for unauthenticated user {$info->getUser()}"
			);
		}
		if ( $info->getProvider() === null ) {
			throw new \InvalidArgumentException( 'Cannot create session without a provider' );
		}
		if ( $info->getId() !== $id->getId() ) {
			throw new \InvalidArgumentException( 'SessionId and SessionInfo don\'t match' );
		}

		$this->id = $id;
		$this->user = $info->getUser() ? $info->getUser()->getUser() : new User;
		$this->store = $store;
		$this->lifetime = $lifetime;
		$this->provider = $info->getProvider();
		$this->persist = $info->wasPersisted();
		$this->remember = $info->wasRemembered();
		$this->forceHTTPS = $info->forceHTTPS();

		$metadata = $store->get( wfMemcKey( 'MWSession', 'metadata', (string)$this->id ) );
		if ( $metadata === false ) {
			// If metadata isn't saved, mark it dirty to force the next save attempt to save
			$this->metaDirty = true;
		} elseif ( isset( $metadata['expires'] ) ) {
			$this->expires = (int)$metadata['expires'];
		}
	}

	/**
	 * Return a new Session for this backend
	 * @param WebRequest $request
	 * @return Session
	 */
	public function getSession( WebRequest $request ) {
		$index = ++$this->curIndex;
		$this->requests[$index] = $request;
		$session = new Session( $this, $index );
		return $session;
	}

	/**
	 * Deregister a Session
	 * @note Should only be called from Session::__destruct
	 * @param int $index
	 */
	public function deregisterSession( $index ) {
		unset( $this->requests[$index] );
		if ( !count( $this->requests ) ) {
			$this->save( true );
			$this->provider->getManager()->deregisterSessionBackend( $this );
		}
	}

	/**
	 * Returns the session ID.
	 * @return string
	 */
	public function getId() {
		return (string)$this->id;
	}

	/**
	 * Fetch the SessionId object
	 * @return SessionId
	 */
	public function getSessionId() {
		return $this->id;
	}

	/**
	 * Changes the session ID
	 * @return string New ID (might be the same as the old)
	 */
	public function resetId() {
		if ( $this->provider->persistsSessionId() ) {
			$oldId = (string)$this->id;
			$restart = $this->usePhpSessionHandling && $oldId === session_id();

			if ( $restart ) {
				// If this session is the one behind PHP's $_SESSION, we need
				// to close then reopen it.
				session_write_close();
			}

			$this->provider->getManager()->changeBackendId( $this );
			$this->metaDirty = true;
			$this->save();

			if ( $restart ) {
				session_id( (string)$this->id );
				\MediaWiki\quietCall( 'session_start' );
			}

			// Delete the data for the old session ID now
			$this->store->delete( wfMemcKey( 'MWSession', 'data', $oldId ) );
			$this->store->delete( wfMemcKey( 'MWSession', 'metadata', $oldId ) );
		}
	}

	/**
	 * Fetch the SessionProvider for this session
	 * @return SessionProvider
	 */
	public function getProvider() {
		return $this->provider;
	}

	/**
	 * Indicate whether this session is persisted across requests
	 *
	 * For example, if cookies are set.
	 *
	 * @return bool
	 */
	public function isPersistent() {
		return $this->persist;
	}

	/**
	 * Make this session persisted across requests
	 *
	 * It's safe to call this even if the session is already persistent.
	 */
	public function persist() {
		if ( !$this->persist ) {
			$this->persist = true;
			$this->forcePersist = true;
			$this->save();
		}
		$this->checkPHPSession();
	}

	/**
	 * Indicate whether the user should be remembered independently of the
	 * session ID.
	 * @return bool
	 */
	public function rememberUser() {
		return $this->remember;
	}

	/**
	 * Set whether the user should be remembered independently of the session
	 * ID.
	 * @param bool $remember
	 */
	public function setRememberUser( $remember ) {
		if ( $this->remember !== (bool)$remember ) {
			$this->remember = (bool)$remember;
			$this->metaDirty = true;
			$this->save();
		}
	}

	/**
	 * Returns the request associated with a Session
	 * @param int $index Session index
	 * @return WebRequest
	 */
	public function getRequest( $index ) {
		if ( !isset( $this->requests[$index] ) ) {
			throw new \InvalidArgumentException( 'Invalid session index' );
		}
		return $this->requests[$index];
	}

	/**
	 * Returns the authenticated user for this session
	 * @return User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * Indicate whether the session user info can be changed
	 * @return bool
	 */
	public function canSetUser() {
		return $this->provider->persistsUser();
	}

	/**
	 * Set a new user for this session
	 * @note This should only be called when the user has been authenticated via a login process
	 * @param User $user User to set on the session.
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 */
	public function setUser( $user ) {
		if ( !$this->canSetUser() ) {
			throw new \BadMethodCallException(
				'Cannot set user on this session; check $session->canSetUser() first'
			);
		}

		$this->user = $user;
		$this->metaDirty = true;
		$this->save();
	}

	/**
	 * Get a suggested username for the login form
	 * @param int $index Session index
	 * @return string|null
	 */
	public function suggestLoginUsername( $index ) {
		if ( !isset( $this->requests[$index] ) ) {
			throw new \InvalidArgumentException( 'Invalid session index' );
		}
		return $this->provider->suggestLoginUsername( $this->requests[$index] );
	}

	/**
	 * Whether HTTPS should be forced
	 * @return bool
	 */
	public function forceHTTPS() {
		return $this->forceHTTPS;
	}

	/**
	 * Set whether HTTPS should be forced
	 * @param bool $force
	 */
	public function setForceHTTPS( $force ) {
		if ( $this->forceHTTPS !== (bool)$force ) {
			$this->forceHTTPS = (bool)$force;
			$this->metaDirty = true;
			$this->save();
		}
	}

	/**
	 * Fetch the session data array
	 *
	 * Note the caller is responsible for calling $this->dirty() if anything in
	 * the array is changed. Generally this shouldn't be used outside of the
	 * implementation of Session.
	 *
	 * @return array
	 */
	public function &getData() {
		if ( $this->data === null ) {
			$key = wfMemcKey( 'MWSession', 'data', (string)$this->id );
			$this->data = $this->store->get( $key );
			if ( $this->data === false ) {
				$this->data = array();
				$this->dataDirty = true;
			}
			$this->dataHash = md5( serialize( $this->data ) );
		}

		return $this->data;
	}

	/**
	 * Add data to the session.
	 *
	 * Overwrites any existing data under the same keys.
	 *
	 * @param array $newData Key-value pairs to add to the session
	 */
	public function addData( array $newData ) {
		$data = &$this->getData();
		foreach ( $newData as $key => $value ) {
			if ( !array_key_exists( $key, $data ) || $data[$key] !== $value ) {
				$data[$key] = $value;
				$this->dataDirty = true;
			}
		}
	}

	/**
	 * Mark data as dirty
	 */
	public function dirty() {
		$this->dataDirty = true;
	}

	/**
	 * Renew the session by resaving everything
	 */
	public function renew() {
		if ( time() + $this->lifetime / 2 > $this->expires ) {
			$this->metaDirty = true;
			$this->dataDirty = true;
		}
		if ( $this->persist ) {
			$this->forcePersist = true;
		}
		$this->save();
	}

	/**
	 * Save and persist session data
	 * @param bool $closing Whether the session is being closed
	 */
	public function save( $closing = false ) {
		// Ensure the user has a token
		// @codeCoverageIgnoreStart
		$anon = $this->user->isAnon();
		if ( !$anon && !$this->user->getToken() ) {
			$this->user->setToken();
			if ( !wfReadOnly() ) {
				$this->user->saveSettings();
			}
			$this->metaDirty = true;
		}
		// @codeCoverageIgnoreEnd

		// Ensure data is loaded
		$this->getData();

		$dirty = $this->metaDirty || $this->dataDirty ||
			$this->dataHash !== md5( serialize( $this->data ) );

		if ( !$dirty && !$this->forcePersist ) {
			return;
		}

		// Persist to the provider, if flagged
		if ( $this->persist && ( $this->metaDirty || $this->forcePersist ) ) {
			foreach ( $this->requests as $request ) {
				$this->provider->persistSession( $this, $request );
			}
			if ( !$closing ) {
				$this->checkPHPSession();
			}
		}

		$this->forcePersist = false;

		if ( !$dirty ) {
			return;
		}

		// Save session data to store, if necessary
		$metadata = $origMetadata = array(
			'provider' => (string)$this->provider,
			'userId' => $anon ? 0 : $this->user->getId(),
			'userName' => $anon ? null : $this->user->getName(),
			'userToken' => $anon ? null : $this->user->getToken(),
			'remember' => !$anon && $this->remember,
			'forceHTTPS' => $this->forceHTTPS,
			'expires' => time() + $this->lifetime,
		);

		\Hooks::run( 'SessionMetadata', array( $this, &$metadata, $this->requests ) );

		$this->store->setMulti(
			array(
				wfMemcKey( 'MWSession', 'data', (string)$this->id ) => $this->data,
				wfMemcKey( 'MWSession', 'metadata', (string)$this->id ) => $metadata,
			),
			$metadata['expires']
		);

		$this->metaDirty = false;
		$this->dataDirty = false;
		$this->dataHash = md5( serialize( $this->data ) );
		$this->expires = $metadata['expires'];
	}

	/**
	 * For backwards compatibility, open the PHP session when the global
	 * session is persisted
	 */
	private function checkPHPSession() {
		if ( $this->usePhpSessionHandling && session_id() === '' &&
			SessionManager::getGlobalSession()->getId() === (string)$this->id
		) {
			session_id( (string)$this->id );
			\MediaWiki\quietCall( 'session_start' );
		}
	}

}
