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

use InvalidArgumentException;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\WebRequest;
use MediaWiki\User\User;
use MWRestrictions;
use Psr\Log\LoggerInterface;
use Wikimedia\AtEase\AtEase;
use Wikimedia\ObjectCache\CachedBagOStuff;

/**
 * This is the actual workhorse for Session.
 *
 * Most code does not need to use this class, you want \MediaWiki\Session\Session.
 * The exceptions are SessionProviders and SessionMetadata hook functions,
 * which get an instance of this class rather than Session.
 *
 * The reasons for this split are:
 * 1. A session can be attached to multiple requests, but we want the Session
 *    object to have some features that correspond to just one of those
 *    requests.
 * 2. We want reasonable garbage collection behavior, but we also want the
 *    SessionManager to hold a reference to every active session so it can be
 *    saved when the request ends.
 *
 * @ingroup Session
 * @since 1.27
 */
final class SessionBackend {
	private SessionId $id;

	/** @var bool */
	private $persist = false;

	/** @var bool */
	private $remember = false;

	/** @var bool */
	private $forceHTTPS = false;

	/** @var array|null */
	private $data = null;

	/** @var bool */
	private $forcePersist = false;

	/**
	 * The reason for the next session write to the backend. Only used for logging. Can be:
	 * - 'renew', 'resetId', 'setRememberUser', 'setUser', 'setForceHTTPS', 'setLoggedOutTimestamp',
	 *   'setProviderMetadata': triggered by a call to that method
	 * - 'manual': triggered by persist() / unpersist() call
	 * - 'no-store': the session was not found in the session store
	 * - 'no-expiry': there was no expiry in the session store data; this probably shouldn't happen
	 * - 'token': the user did not have a token
	 * - null otherwise.
	 */
	private ?string $sessionWriteReason = null;

	/** @var bool */
	private $metaDirty = false;

	/** @var bool */
	private $dataDirty = false;

	/** @var string Used to detect subarray modifications */
	private $dataHash = null;

	private CachedBagOStuff $store;
	private LoggerInterface $logger;
	private HookRunner $hookRunner;

	/** @var int */
	private $lifetime;

	private User $user;

	/** @var int */
	private $curIndex = 0;

	/** @var WebRequest[] Session requests */
	private $requests = [];

	/** @var SessionProvider provider */
	private $provider;

	/** @var array|null provider-specified metadata */
	private $providerMetadata = null;

	/** @var int */
	private $expires = 0;

	/** @var int */
	private $loggedOut = 0;

	/** @var int */
	private $delaySave = 0;
	private bool $hasDelayedSave = false;

	/** @var bool */
	private $usePhpSessionHandling;
	/** @var bool */
	private $checkPHPSessionRecursionGuard = false;

	/** @var bool */
	private $shutdown = false;

	/**
	 * @param SessionId $id
	 * @param SessionInfo $info Session info to populate from
	 * @param CachedBagOStuff $store Backend data store
	 * @param LoggerInterface $logger
	 * @param HookContainer $hookContainer
	 * @param int $lifetime Session data lifetime in seconds
	 */
	public function __construct(
		SessionId $id, SessionInfo $info, CachedBagOStuff $store, LoggerInterface $logger,
		HookContainer $hookContainer, $lifetime
	) {
		$phpSessionHandling = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::PHPSessionHandling );
		$this->usePhpSessionHandling = $phpSessionHandling !== 'disable';

		if ( $info->getUserInfo() && !$info->getUserInfo()->isVerified() ) {
			throw new InvalidArgumentException(
				"Refusing to create session for unverified user {$info->getUserInfo()}"
			);
		}
		if ( $info->getProvider() === null ) {
			throw new InvalidArgumentException( 'Cannot create session without a provider' );
		}
		if ( $info->getId() !== $id->getId() ) {
			throw new InvalidArgumentException( 'SessionId and SessionInfo don\'t match' );
		}

		$this->id = $id;
		$this->user = $info->getUserInfo()
			? $info->getUserInfo()->getUser()
			: MediaWikiServices::getInstance()->getUserFactory()->newAnonymous();
		$this->store = $store;
		$this->logger = $logger;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->lifetime = $lifetime;
		$this->provider = $info->getProvider();
		$this->persist = $info->wasPersisted();
		$this->remember = $info->wasRemembered();
		$this->forceHTTPS = $info->forceHTTPS();
		$this->providerMetadata = $info->getProviderMetadata();

		$blob = $store->get( $store->makeKey( 'MWSession', (string)$this->id ) );
		if ( !is_array( $blob ) ||
			!isset( $blob['metadata'] ) || !is_array( $blob['metadata'] ) ||
			!isset( $blob['data'] ) || !is_array( $blob['data'] )
		) {
			$this->data = [];
			$this->dataDirty = true;
			$this->metaDirty = true;
			$this->sessionWriteReason = 'no-store';
			$this->logger->debug(
				'SessionBackend "{session}" is unsaved, marking dirty in constructor',
				[
					'session' => $this->id->__toString(),
				] );
		} else {
			$this->data = $blob['data'];
			if ( isset( $blob['metadata']['loggedOut'] ) ) {
				$this->loggedOut = (int)$blob['metadata']['loggedOut'];
			}
			if ( isset( $blob['metadata']['expires'] ) ) {
				$this->expires = (int)$blob['metadata']['expires'];
			} else {
				$this->metaDirty = true;
				$this->sessionWriteReason = 'no-expiry';
				$this->logger->debug(
					'SessionBackend "{session}" metadata dirty due to missing expiration timestamp',
					[
						'session' => $this->id->__toString(),
					] );
			}
		}
		$this->dataHash = md5( serialize( $this->data ) );
	}

	/**
	 * Return a new Session for this backend
	 * @param WebRequest $request
	 * @return Session
	 */
	public function getSession( WebRequest $request ) {
		$index = ++$this->curIndex;
		$this->requests[$index] = $request;
		$session = new Session( $this, $index, $this->logger );
		return $session;
	}

	/**
	 * Deregister a Session
	 * @internal For use by \MediaWiki\Session\Session::__destruct() only
	 * @param int $index
	 */
	public function deregisterSession( $index ) {
		if ( !$this->shutdown && count( $this->requests ) <= 1 ) {
			$this->save( true );
			$this->provider->getManager()->deregisterSessionBackend( $this );
		}
		unset( $this->requests[$index] );
	}

	/**
	 * Shut down a session
	 * @internal For use by \MediaWiki\Session\SessionManager::shutdown() only
	 */
	public function shutdown() {
		$this->save( true );
		$this->shutdown = true;
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
	 * @internal For internal use by WebRequest
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
			$restart = $this->usePhpSessionHandling && $oldId === session_id() &&
				PHPSessionHandler::isEnabled();

			if ( $restart ) {
				// If this session is the one behind PHP's $_SESSION, we need
				// to close then reopen it.
				session_write_close();
			}

			$this->provider->getManager()->changeBackendId( $this );
			$this->provider->sessionIdWasReset( $this, $oldId );
			$this->metaDirty = true;
			$this->sessionWriteReason ??= 'resetId';
			$this->logger->debug(
				'SessionBackend "{session}" metadata dirty due to ID reset (formerly "{oldId}")',
				[
					'session' => $this->id->__toString(),
					'oldId' => $oldId,
				] );

			if ( $restart ) {
				session_id( (string)$this->id );
				AtEase::quietCall( 'session_start' );
			}

			$this->autosave();

			$this->logSessionWrite( [
				'old_session_id' => $oldId,
				'action' => 'delete',
				'reason' => 'ID reset',
			] );
			// Delete the data for the old session ID now
			$this->store->delete( $this->store->makeKey( 'MWSession', $oldId ) );
		}

		return (string)$this->id;
	}

	/**
	 * Fetch the SessionProvider for this session
	 * @return SessionProviderInterface
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
	 * If the session is already persistent, equivalent to calling
	 * $this->renew().
	 */
	public function persist() {
		if ( !$this->persist ) {
			$this->persist = true;
			$this->forcePersist = true;
			$this->metaDirty = true;
			$this->sessionWriteReason ??= 'manual';
			$this->logger->debug(
				'SessionBackend "{session}" force-persist due to persist()',
				[
					'session' => $this->id->__toString(),
				] );
			$this->autosave();
		} else {
			$this->renew();
		}
	}

	/**
	 * Make this session not persisted across requests
	 */
	public function unpersist() {
		if ( $this->persist ) {
			// Close the PHP session, if we're the one that's open
			if ( $this->usePhpSessionHandling && PHPSessionHandler::isEnabled() &&
				session_id() === (string)$this->id
			) {
				$this->logger->debug(
					'SessionBackend "{session}" Closing PHP session for unpersist',
					[ 'session' => $this->id->__toString() ]
				);
				session_write_close();
				session_id( '' );
			}

			$this->persist = false;
			$this->forcePersist = true;
			$this->metaDirty = true;
			$this->sessionWriteReason ??= 'manual';

			$this->logSessionWrite( [
				'action' => 'delete',
				'reason' => 'unpersist',
			] );
			// Delete the session data, so the local cache-only write in
			// self::save() doesn't get things out of sync with the backend.
			$this->store->delete( $this->store->makeKey( 'MWSession', (string)$this->id ) );

			$this->autosave();
		}
	}

	/**
	 * Indicate whether the user should be remembered independently of the
	 * session ID.
	 * @return bool
	 */
	public function shouldRememberUser() {
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
			$this->sessionWriteReason ??= 'setRememberUser';
			$this->logger->debug(
				'SessionBackend "{session}" metadata dirty due to remember-user change',
				[
					'session' => $this->id->__toString(),
				] );
			$this->autosave();
		}
	}

	/**
	 * Returns the request associated with a Session
	 * @param int $index Session index
	 * @return WebRequest
	 */
	public function getRequest( $index ) {
		if ( !isset( $this->requests[$index] ) ) {
			throw new InvalidArgumentException( 'Invalid session index' );
		}
		return $this->requests[$index];
	}

	/**
	 * Returns the authenticated user for this session
	 */
	public function getUser(): User {
		return $this->user;
	}

	/**
	 * Fetch the rights allowed the user when this session is active.
	 * @return null|string[] Allowed user rights, or null to allow all.
	 */
	public function getAllowedUserRights() {
		return $this->provider->getAllowedUserRights( $this );
	}

	/**
	 * Fetch any restrictions imposed on logins or actions when this
	 * session is active.
	 * @return MWRestrictions|null
	 */
	public function getRestrictions(): ?MWRestrictions {
		return $this->provider->getRestrictions( $this->providerMetadata );
	}

	/**
	 * Indicate whether the session user info can be changed
	 * @return bool
	 */
	public function canSetUser() {
		return $this->provider->canChangeUser();
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
		$this->sessionWriteReason ??= 'setUser';
		$this->logger->debug(
			'SessionBackend "{session}" metadata dirty due to user change',
			[
				'session' => $this->id->__toString(),
			] );
		$this->autosave();
	}

	/**
	 * Get a suggested username for the login form
	 * @param int $index Session index
	 * @return string|null
	 */
	public function suggestLoginUsername( $index ) {
		if ( !isset( $this->requests[$index] ) ) {
			throw new InvalidArgumentException( 'Invalid session index' );
		}
		return $this->provider->suggestLoginUsername( $this->requests[$index] );
	}

	/**
	 * Whether HTTPS should be forced
	 * @return bool
	 */
	public function shouldForceHTTPS() {
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
			$this->sessionWriteReason ??= 'setForceHTTPS';
			$this->logger->debug(
				'SessionBackend "{session}" metadata dirty due to force-HTTPS change',
				[
					'session' => $this->id->__toString(),
				] );
			$this->autosave();
		}
	}

	/**
	 * Fetch the "logged out" timestamp
	 * @return int
	 */
	public function getLoggedOutTimestamp() {
		return $this->loggedOut;
	}

	/**
	 * @param int|null $ts
	 */
	public function setLoggedOutTimestamp( $ts = null ) {
		$ts = (int)$ts;
		if ( $this->loggedOut !== $ts ) {
			$this->loggedOut = $ts;
			$this->metaDirty = true;
			$this->sessionWriteReason ??= 'setLoggedOutTimestamp';
			$this->logger->debug(
				'SessionBackend "{session}" metadata dirty due to logged-out-timestamp change',
				[
					'session' => $this->id->__toString(),
				] );
			$this->autosave();
		}
	}

	/**
	 * Fetch provider metadata
	 * @note For use by SessionProvider subclasses only
	 * @return array|null
	 */
	public function getProviderMetadata() {
		return $this->providerMetadata;
	}

	/**
	 * @note For use by SessionProvider subclasses only
	 * @param array|null $metadata
	 */
	public function setProviderMetadata( $metadata ) {
		if ( $metadata !== null && !is_array( $metadata ) ) {
			throw new InvalidArgumentException( '$metadata must be an array or null' );
		}
		if ( $this->providerMetadata !== $metadata ) {
			$this->providerMetadata = $metadata;
			$this->metaDirty = true;
			$this->sessionWriteReason ??= 'setProviderMetadata';
			$this->logger->debug(
				'SessionBackend "{session}" metadata dirty due to provider metadata change',
				[
					'session' => $this->id->__toString(),
				] );
			$this->autosave();
		}
	}

	/**
	 * Fetch the session data array
	 *
	 * Note the caller is responsible for calling $this->dirty() if anything in
	 * the array is changed.
	 *
	 * @internal For use by \MediaWiki\Session\Session only.
	 * @return array
	 */
	public function &getData() {
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
				$this->logger->debug(
					'SessionBackend "{session}" data dirty due to addData(): {callers}',
					[
						'session' => $this->id->__toString(),
						'callers' => wfGetAllCallers( 5 ),
					] );
			}
		}
	}

	/**
	 * Mark data as dirty
	 * @internal For use by \MediaWiki\Session\Session only.
	 */
	public function dirty() {
		$this->dataDirty = true;
		$this->logger->debug(
			'SessionBackend "{session}" data dirty due to dirty(): {callers}',
			[
				'session' => $this->id->__toString(),
				'callers' => wfGetAllCallers( 5 ),
			] );
	}

	/**
	 * Renew the session by resaving everything
	 *
	 * Resets the TTL in the backend store if the session is near expiring, and
	 * re-persists the session to any active WebRequests if persistent.
	 */
	public function renew() {
		if ( time() + $this->lifetime / 2 > $this->expires ) {
			$this->metaDirty = true;
			$this->sessionWriteReason ??= 'renew';
			$this->logger->debug(
				'SessionBackend "{callers}" metadata dirty for renew(): {callers}',
				[
					'session' => $this->id->__toString(),
					'callers' => wfGetAllCallers( 5 ),
				] );
			if ( $this->persist ) {
				$this->forcePersist = true;
				$this->logger->debug(
					'SessionBackend "{session}" force-persist for renew(): {callers}',
					[
						'session' => $this->id->__toString(),
						'callers' => wfGetAllCallers( 5 ),
					] );
			}
		}
		$this->autosave();
	}

	/**
	 * Delay automatic saving while multiple updates are being made
	 *
	 * Calls to save() will not be delayed.
	 *
	 * @return \Wikimedia\ScopedCallback When this goes out of scope, a save will be triggered
	 */
	public function delaySave() {
		$this->delaySave++;
		return new \Wikimedia\ScopedCallback( function () {
			if ( --$this->delaySave <= 0 ) {
				$this->delaySave = 0;
				if ( $this->hasDelayedSave ) {
					$this->hasDelayedSave = false;
					$this->save();
				}
			}
		} );
	}

	/**
	 * Save the session, unless delayed
	 * @see SessionBackend::save()
	 */
	private function autosave() {
		if ( $this->delaySave <= 0 ) {
			$this->save();
		} else {
			$this->hasDelayedSave = true;
		}
	}

	/**
	 * Save the session
	 *
	 * Update both the backend data and the associated WebRequest(s) to
	 * reflect the state of the SessionBackend. This might include
	 * persisting or unpersisting the session.
	 *
	 * @param bool $closing Whether the session is being closed
	 */
	public function save( $closing = false ) {
		$anon = $this->user->isAnon();

		if ( !$anon && $this->provider->getManager()->isUserSessionPrevented( $this->user->getName() ) ) {
			$this->logger->debug(
				'SessionBackend "{session}" not saving, user {user} was ' .
				'passed to SessionManager::preventSessionsForUser',
				[
					'session' => $this->id->__toString(),
					'user' => $this->user->__toString(),
				] );
			return;
		}

		// Ensure the user has a token
		// @codeCoverageIgnoreStart
		if ( !$anon && defined( 'MW_PHPUNIT_TEST' ) && MediaWikiServices::getInstance()->isStorageDisabled() ) {
			// Avoid making DB queries in non-database tests. We don't need to save the token when using
			// fake users, and it would probably be ignored anyway.
			return;
		}
		if ( !$anon && !$this->user->getToken( false ) ) {
			$this->logger->debug(
				'SessionBackend "{session}" creating token for user {user} on save',
				[
					'session' => $this->id->__toString(),
					'user' => $this->user->__toString(),
				] );
			$this->user->setToken();
			if ( !MediaWikiServices::getInstance()->getReadOnlyMode()->isReadOnly() ) {
				// Promise that the token set here will be valid; save it at end of request
				$user = $this->user;
				DeferredUpdates::addCallableUpdate( static function () use ( $user ) {
					$user->saveSettings();
				} );
			}
			$this->metaDirty = true;
			$this->sessionWriteReason ??= 'token';
		}
		// @codeCoverageIgnoreEnd

		if ( !$this->metaDirty && !$this->dataDirty &&
			$this->dataHash !== md5( serialize( $this->data ) )
		) {
			$this->logger->debug(
				'SessionBackend "{session}" data dirty due to hash mismatch, {expected} !== {got}',
				[
					'session' => $this->id->__toString(),
					'expected' => $this->dataHash,
					'got' => md5( serialize( $this->data ) ),
				] );
			$this->dataDirty = true;
		}

		if ( !$this->metaDirty && !$this->dataDirty && !$this->forcePersist ) {
			return;
		}

		$this->logger->debug(
			'SessionBackend "{session}" save: dataDirty={dataDirty} ' .
			'metaDirty={metaDirty} forcePersist={forcePersist}',
			[
				'session' => $this->id->__toString(),
				'dataDirty' => (int)$this->dataDirty,
				'metaDirty' => (int)$this->metaDirty,
				'forcePersist' => (int)$this->forcePersist,
			] );

		// Persist or unpersist to the provider, if necessary
		if ( $this->metaDirty || $this->forcePersist ) {
			if ( $this->persist ) {
				foreach ( $this->requests as $request ) {
					$request->setSessionId( $this->getSessionId() );
					$this->provider->persistSession( $this, $request );
				}
				if ( !$closing ) {
					$this->checkPHPSession();
				}
			} else {
				foreach ( $this->requests as $request ) {
					if ( $request->getSessionId() === $this->id ) {
						$this->provider->unpersistSession( $request );
					}
				}
			}
		}

		$forcePersist = $this->forcePersist;
		$persistenceChangeReason = $this->sessionWriteReason;
		$this->forcePersist = false;
		$this->sessionWriteReason = null;

		if ( !$this->metaDirty && !$this->dataDirty ) {
			return;
		}

		// Save session data to store, if necessary
		$metadata = $origMetadata = [
			'provider' => (string)$this->provider,
			'providerMetadata' => $this->providerMetadata,
			'userId' => $anon ? 0 : $this->user->getId(),
			'userName' => MediaWikiServices::getInstance()->getUserNameUtils()
				->isValid( $this->user->getName() ) ? $this->user->getName() : null,
			'userToken' => $anon ? null : $this->user->getToken(),
			'remember' => !$anon && $this->remember,
			'forceHTTPS' => $this->forceHTTPS,
			'expires' => time() + $this->lifetime,
			'loggedOut' => $this->loggedOut,
			'persisted' => $this->persist,
		];

		$this->hookRunner->onSessionMetadata( $this, $metadata, $this->requests );

		foreach ( $origMetadata as $k => $v ) {
			if ( $metadata[$k] !== $v ) {
				throw new \UnexpectedValueException( "SessionMetadata hook changed metadata key \"$k\"" );
			}
		}

		if ( $this->persist ) {
			$this->logSessionWrite( [
				'remember' => $metadata['remember'],
				'metaDirty' => $this->metaDirty,
				'dataDirty' => $this->dataDirty,
				'forcePersist' => $forcePersist,
				'action' => 'write',
				// 'other' probably means the session had dirty data.
				// @phan-suppress-next-line PhanCoalescingNeverNull
				'reason' => $persistenceChangeReason ?? 'other',
			] );

		}

		$flags = $this->persist ? 0 : CachedBagOStuff::WRITE_CACHE_ONLY;
		$this->store->set(
			$this->store->makeKey( 'MWSession', (string)$this->id ),
			[
				'data' => $this->data,
				'metadata' => $metadata,
			],
			$metadata['expires'],
			$flags
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
		if ( !$this->checkPHPSessionRecursionGuard ) {
			$this->checkPHPSessionRecursionGuard = true;
			$reset = new \Wikimedia\ScopedCallback( function () {
				$this->checkPHPSessionRecursionGuard = false;
			} );

			if ( $this->usePhpSessionHandling && session_id() === '' && PHPSessionHandler::isEnabled() &&
				SessionManager::getGlobalSession()->getId() === (string)$this->id
			) {
				$this->logger->debug(
					'SessionBackend "{session}" Taking over PHP session',
					[
						'session' => $this->id->__toString(),
					] );
				session_id( (string)$this->id );
				AtEase::quietCall( 'session_start' );
			}
		}
	}

	/**
	 * @param array $data Additional log context. Should have at least the following keys:
	 *   - action: 'write' or 'delete'.
	 *   - reason: why the write happened
	 * @see SessionManager::logSessionWrite()
	 */
	private function logSessionWrite( array $data = [] ): void {
		$id = $this->getId();
		$user = $this->getUser()->isAnon() ? '<anon>' : $this->getUser()->getName();
		// No great way to find out what request SessionBackend is being called for, but it's
		// rare to have multiple ones which are significantly different, and even rarer for the
		// first of those not to be the real one.
		$request = reset( $this->requests );
		// Don't require $this->requests to be non-empty for unit tests, as it's hard to enforce
		if ( $request === false && defined( 'MW_PHPUNIT_TEST' ) ) {
			$request = new FauxRequest();
		}
		$this->logger->info( 'Session store: {action} for {reason}', $data + [
				'id' => $id,
				'provider' => get_class( $this->getProvider() ),
				'user' => $user,
				'clientip' => $request->getIP(),
				'userAgent' => $request->getHeader( 'user-agent' ),
			] );
	}

}
