<?php
/**
 * MediaWiki session info
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

use Psr\Log\LoggerInterface;

/**
 * Value object returned by MWSessionProvider
 *
 * This holds the data necessary to construct an MWSession.
 *
 * @ingroup Session
 * @since 1.27
 */
class MWSessionInfo {
	/** Minimum allowed priority */
	const MIN_PRIORITY = 1;

	/** Maximum allowed priority */
	const MAX_PRIORITY = 100;

	/** @var MWSessionProvider|null */
	private $provider;

	/** @var string */
	private $id;

	/** @var int */
	private $priority;

	/** @var MWSessionUserInfo|null */
	private $user = null;

	private $persisted = false;
	private $remembered = false;
	private $forceHTTPS = false;

	/**
	 * @param int $priority Session priority
	 * @param array $data
	 *  - provider: (MWSessionProvider|null) If not given, the provider will be
	 *    determined from the saved session data.
	 *  - id: (string|null) Session ID
	 *  - user: (MWSessionUserInfo|null) User known from the request. If
	 *    $provider->persistsUser() is false, an authenticated user must be provided.
	 *  - forceHTTPS: (bool) Whether to force HTTPS for this session
	 */
	public function __construct( $priority, array $data ) {
		if ( $priority < self::MIN_PRIORITY || $priority > self::MAX_PRIORITY ) {
			throw new InvalidArgumentException( 'Invalid priority' );
		}

		$data += array(
			'provider' => null,
			'id' => null,
			'user' => null,
			'forceHTTPS' => false,
		);

		if ( $data['id'] !== null && !MWSessionManager::validateSessionId( $data['id'] ) ) {
			throw new InvalidArgumentException( 'Invalid session ID' );
		}

		if ( $data['user'] && !$data['user'] instanceof MWSessionUserInfo ) {
			throw new InvalidArgumentException( 'Invalid user' );
		}

		if ( $data['provider'] && !$data['provider']->persistsUser() &&
			( $data['user'] === null || !$data['user']->isAuthenticated() )
		) {
			throw new InvalidArgumentException(
				'Provider ' . $data['provider'] . ' cannot set user info, ' .
					'but no authenticated user was provided'
			);
		}

		if ( !$data['provider'] && $data['id'] === null ) {
			throw new InvalidArgumentException(
				'Must supply an ID when no provider is given'
			);
		}

		$this->provider = $data['provider'];
		$this->id = $data['id'] !== null
			? $data['id']
			: $this->provider->getManager()->generateSessionId();
		$this->priority = (int)$priority;
		$this->user = $data['user'];
		if ( $data['provider'] !== null ) {
			$this->persisted = $data['id'] !== null;
			$this->remembered = $this->user !== null && !$this->user->isAnon() &&
				$this->user->isAuthenticated();
		}
		$this->forceHTTPS = !empty( $data['forceHTTPS'] );
	}

	/**
	 * Return the provider
	 * @return MWSessionProvider|null
	 */
	final public function getProvider() {
		return $this->provider;
	}

	/**
	 * Return the session ID
	 * @return string
	 */
	final public function getId() {
		return $this->id;
	}

	/**
	 * Return the priority
	 * @return int
	 */
	final public function getPriority() {
		return $this->priority;
	}

	/**
	 * Return the user
	 * @return MWSessionUserInfo|null
	 */
	final public function getUser() {
		return $this->user;
	}

	/**
	 * Return whether the session is persisted
	 *
	 * i.e. a session ID was given to the constuctor
	 *
	 * @return bool
	 */
	final public function wasPersisted() {
		return $this->persisted;
	}

	/**
	 * Return whether the user was remembered
	 *
	 * i.e. a non-anonymous authenticated user was given to the constructor
	 *
	 * @return bool
	 */
	final public function wasRemembered() {
		return $this->remembered;
	}

	/**
	 * Whether this session should only be used over HTTPS
	 * @return bool
	 */
	final public function forceHTTPS() {
		return $this->forceHTTPS;
	}

	/**
	 * Load and verify the session info against the store
	 *
	 * Will fill in the provider and user from the store, if they weren't
	 * originally provided.
	 *
	 * @param MWSessionManager $manager
	 * @param BagOStuff $store
	 * @param LoggerInterface $logger
	 * @param WebRequest $request
	 * @return bool Whether the session info matches the stored data (if any)
	 */
	final public function loadFromStore(
		MWSessionManager $manager, BagOStuff $store, LoggerInterface $logger, WebRequest $request
	) {
		$data = $store->get( wfMemcKey( 'MWSession', 'data', $this->id ) );
		$metadata = $store->get( wfMemcKey( 'MWSession', 'metadata', $this->id ) );

		// Sanity check: data must be an array, if it's saved at all
		if ( $data !== false && !is_array( $data ) ) {
			$logger->debug( "Session $this: Bad data" );
			return false;
		}

		// Sanity check: metadata must be an array and must contain certain
		// keys, if it's saved at all
		if ( $metadata !== false && (
			!is_array( $metadata ) ||
			!array_key_exists( 'userId', $metadata ) ||
			!array_key_exists( 'userName', $metadata ) ||
			!array_key_exists( 'userToken', $metadata ) ||
			!array_key_exists( 'provider', $metadata )
		) ) {
			$logger->debug( "Session $this: Bad metadata" );
			return false;
		}

		// Sanity check: Either we have both data and metadata, or we have neither.
		if ( ( $data === false ) !== ( $metadata === false ) ) {
			$logger->debug( "Session $this: Has data or metadata but not both" );
			return false;
		}

		if ( $metadata !== false ) {
			// First, load the provider from metadata, or validate it against the metadata.
			if ( $this->provider === null ) {
				$this->provider = $manager->getProvider( $metadata['provider'] );
				if ( !$this->provider ) {
					$logger->debug( "Session $this: Unknown provider, " . $metadata['provider'] );
					return false;
				}
			} elseif ( $metadata['provider'] !== (string)$this->provider ) {
				$logger->debug( "Session $this: Wrong provider, " .
					$metadata['provider'] . ' !== ' . $this->provider );
				return false;
			}

			// Next, load the user from metadata, or validate it against the metadata.
			if ( !$this->user ) {
				// For loading, id is preferred to name.
				try {
					if ( $metadata['userId'] ) {
						$this->user = MWSessionUserInfo::newFromId( $metadata['userId'] );
					} elseif ( $metadata['userName'] !== null ) { // @todo: Should we even do this?
						$this->user = MWSessionUserInfo::newFromName( $metadata['userName'] );
					} else {
						$this->user = MWSessionUserInfo::newAnonymous();
					}
				} catch ( InvalidArgumentException $ex ) {
					$logger->debug( "Session $this: " . $ex->getMessage() );
					return false;
				}
			} else {
				// User validation passes if user ID matches, or if there
				// is no saved ID and the names match.
				if ( $metadata['userId'] ) {
					if ( $metadata['userId'] !== $this->user->getId() ) {
						$logger->debug( "Session $this: User ID mismatch, " .
							$metadata['userId'] . ' !== ' . $this->user->getId() );
						return false;
					}

					// @todo: User::loadFromSession would additionally check
					// $this->user->getName() against $metadata['userName']
					// here, if the latter is available. Should that still be done?

				} elseif ( $metadata['userName'] !== null ) { // @todo: Should we even do this?
					if ( $metadata['userName'] !== $this->user->getName() ) {
						$logger->debug( "Session $this: User name mismatch, " .
							$metadata['userName'] . ' !== ' . $this->user->getName() );
						return false;
					}
				} elseif ( !$this->user->isAnon() ) {
					// Metadata specifies an anonymous user, but the passed-in
					// user isn't anonymous.
					$logger->debug(
						"Session $this: Metadata has an anonymous user, " .
							'but a non-anon user was provided'
					);
					return false;
				}
			}

			// And if we have a token in the metadata, it must match the loaded/provided user.
			if ( $metadata['userToken'] !== null &&
				$this->user->getToken() !== $metadata['userToken']
			) {
				$logger->debug( "Session $this: User token mismatch" );
				return false;
			}
			$this->user = $this->user->authenticated();

			if ( !empty( $metadata['remember'] ) ) {
				$this->remembered = true;
			}
			if ( !empty( $metadata['forceHTTPS'] ) ) {
				$this->forceHTTPS = true;
			}
		} else {
			// No metadata, so we can't load the provider if one wasn't given.
			if ( $this->provider === null ) {
				$logger->debug( "Session $this: Null provider and no metadata" );
				return false;
			}

			// If no user was provided and no metadata, it must be anon.
			if ( !$this->user ) {
				$this->user = MWSessionUserInfo::newAnonymous();
			}
			if ( !$this->user->isAuthenticated() ) {
				$logger->debug(
					"Session $this: Unauthenticated user provided and no metadata to auth it"
				);
				return false;
			}
		}

		// Give hooks a chance to abort. Combined with the MWSessionMetadata
		// hook, this can allow for tying a session to an IP address or the
		// like.
		$reason = 'Hook aborted';
		if ( !Hooks::run(
			'MWSessionCheckInfo',
			array( &$reason, $this, $request, $metadata, $data )
		) ) {
			$logger->debug( "Session $this: $reason" );
			return false;
		}

		return true;
	}

	public function __toString() {
		return '[' . $this->getPriority() . ']' .
			( $this->getProvider() ?: 'null' ) .
			( $this->user ?: '<null>' ) . $this->getId();
	}

	public static function compare( $a, $b ) {
		return $a->getPriority() - $b->getPriority();
	}

}
