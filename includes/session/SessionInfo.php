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

namespace MediaWiki\Session;

use Psr\Log\LoggerInterface;
use BagOStuff;
use WebRequest;

/**
 * Value object returned by SessionProvider
 *
 * This holds the data necessary to construct a Session.
 *
 * @ingroup Session
 * @since 1.27
 */
class SessionInfo {
	/** Minimum allowed priority */
	const MIN_PRIORITY = 1;

	/** Maximum allowed priority */
	const MAX_PRIORITY = 100;

	/** @var SessionProvider|null */
	private $provider;

	/** @var string */
	private $id;

	/** @var int */
	private $priority;

	/** @var UserInfo|null */
	private $user = null;

	private $persisted = false;
	private $remembered = false;
	private $forceHTTPS = false;
	private $idIsSafe = false;

	/** @var array|null */
	private $providerMetadata = null;

	/**
	 * @param int $priority Session priority
	 * @param array $data
	 *  - provider: (SessionProvider|null) If not given, the provider will be
	 *    determined from the saved session data.
	 *  - id: (string|null) Session ID
	 *  - user: (UserInfo|null) User known from the request. If
	 *    $provider->canChangeUser() is false, an authenticated user
	 *    must be provided.
	 *  - persisted: (bool) Whether this session was persisted
	 *  - remembered: (bool) Whether the authenticated user was remembered.
	 *    Defaults to true.
	 *  - forceHTTPS: (bool) Whether to force HTTPS for this session
	 *  - metadata: (array) Provider metadata, to be returned by
	 *    Session::getProviderMetadata().
	 *  - byId: (bool) Set true when called from SessionManager::getSessionById()
	 *    and SessionProvider::newEmptySession() only. Never specify this
	 *    otherwise.
	 */
	public function __construct( $priority, array $data ) {
		if ( $priority < self::MIN_PRIORITY || $priority > self::MAX_PRIORITY ) {
			throw new \InvalidArgumentException( 'Invalid priority' );
		}

		$data += array(
			'provider' => null,
			'id' => null,
			'user' => null,
			'persisted' => false,
			'remembered' => true,
			'forceHTTPS' => false,
			'metadata' => null,
			'byId' => false,
			// @codeCoverageIgnoreStart
		);
		// @codeCoverageIgnoreEnd

		if ( $data['id'] !== null && !SessionManager::validateSessionId( $data['id'] ) ) {
			throw new \InvalidArgumentException( 'Invalid session ID' );
		}

		if ( $data['user'] !== null && !$data['user'] instanceof UserInfo ) {
			throw new \InvalidArgumentException( 'Invalid user' );
		}

		if ( $data['provider'] !== null && !$data['provider']->canChangeUser() &&
			( $data['user'] === null || !$data['user']->isAuthenticated() )
		) {
			throw new \InvalidArgumentException(
				'Provider ' . $data['provider'] . ' cannot set user info, ' .
					'but no authenticated user was provided'
			);
		}

		if ( !$data['provider'] && $data['id'] === null ) {
			throw new \InvalidArgumentException(
				'Must supply an ID when no provider is given'
			);
		}

		if ( $data['metadata'] !== null && !is_array( $data['metadata'] ) ) {
			throw new \InvalidArgumentException( 'Invalid metadata' );
		}

		$this->provider = $data['provider'];
		if ( $data['id'] !== null ) {
			$this->id = $data['id'];
			$this->idIsSafe = $data['byId'];
		} else {
			$this->id = $this->provider->getManager()->generateSessionId();
			$this->idIsSafe = true;
		}
		$this->priority = (int)$priority;
		$this->user = $data['user'];
		$this->persisted = (bool)$data['persisted'];
		if ( $data['provider'] !== null ) {
			if ( $this->user !== null && !$this->user->isAnon() && $this->user->isAuthenticated() ) {
				$this->remembered = (bool)$data['remembered'];
			}
			$this->providerMetadata = $data['metadata'];
		}
		$this->forceHTTPS = (bool)$data['forceHTTPS'];

		// @codeCoverageIgnoreStart
		if ( defined( 'MW_PHPUNIT_TEST' ) && isset( $data['testIdIsSafe'] ) ) {
			$this->idIsSafe = $data['testIdIsSafe'];
		}
		// @codeCoverageIgnoreEnd
	}

	/**
	 * Return the provider
	 * @return SessionProvider|null
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
	 * Indicate whether the ID is "safe"
	 *
	 * The ID is safe in the following cases:
	 * - The ID was randomly generated by the constructor.
	 * - The ID was found in the backend data store.
	 * - $this->getProvider()->persistsSessionId() is false.
	 * - The constructor was explicitly told it's safe using the 'byId'
	 *   parameter.
	 *
	 * @return bool
	 */
	final public function isIdSafe() {
		return $this->idIsSafe;
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
	 * @return UserInfo|null
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
	 * Return provider metadata
	 * @return array|null
	 */
	final public function getProviderMetadata() {
		return $this->providerMetadata;
	}

	/**
	 * Return whether the user was remembered
	 *
	 * For providers that can persist the user separately from the session,
	 * the human using it may not actually *want* that to be done. For example,
	 * a cookie-based provider can set cookies that are longer-lived than the
	 * backend session data, but on a public terminal the human likely doesn't
	 * want those cookies set.
	 *
	 * This is false unless a non-anonymous authenticated user was passed to
	 * the SessionInfo constructor by the provider, and the provider didn't
	 * pass false for the 'remembered' data item.
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
	 * @private For use by \\MediaWiki\\Session\\SessionManager only
	 * @param SessionManager $manager
	 * @param BagOStuff $store
	 * @param LoggerInterface $logger
	 * @param WebRequest $request
	 * @return bool Whether the session info matches the stored data (if any)
	 */
	final public function loadFromStore(
		SessionManager $manager, BagOStuff $store, LoggerInterface $logger, WebRequest $request
	) {
		$blob = $store->get( wfMemcKey( 'MWSession', $this->id ) );

		if ( $blob !== false ) {
			// Sanity check: blob must be an array, if it's saved at all
			if ( !is_array( $blob ) ) {
				$logger->warning( "Session $this: Bad data" );
				return false;
			}

			// Sanity check: blob has data and metadata arrays
			if ( !isset( $blob['data'] ) || !is_array( $blob['data'] ) ||
				!isset( $blob['metadata'] ) || !is_array( $blob['metadata'] )
			) {
				$logger->warning( "Session $this: Bad data structure" );
				return false;
			}

			$data = $blob['data'];
			$metadata = $blob['metadata'];

			// Sanity check: metadata must be an array and must contain certain
			// keys, if it's saved at all
			if ( !array_key_exists( 'userId', $metadata ) ||
				!array_key_exists( 'userName', $metadata ) ||
				!array_key_exists( 'userToken', $metadata ) ||
				!array_key_exists( 'provider', $metadata )
			) {
				$logger->warning( "Session $this: Bad metadata" );
				return false;
			}

			// First, load the provider from metadata, or validate it against the metadata.
			if ( $this->provider === null ) {
				$this->provider = $manager->getProvider( $metadata['provider'] );
				if ( !$this->provider ) {
					$logger->warning( "Session $this: Unknown provider, " . $metadata['provider'] );
					return false;
				}
			} elseif ( $metadata['provider'] !== (string)$this->provider ) {
				$logger->warning( "Session $this: Wrong provider, " .
					$metadata['provider'] . ' !== ' . $this->provider );
				return false;
			}

			// Load provider metadata from metadata, or validate it against the metadata
			if ( isset( $metadata['providerMetadata'] ) ) {
				if ( $this->providerMetadata === null ) {
					$this->providerMetadata = $metadata['providerMetadata'];
				} else {
					try {
						$this->providerMetadata = $this->provider->mergeMetadata(
							$metadata['providerMetadata'],
							$this->providerMetadata
						);
					} catch ( \UnexpectedValueException $ex ) {
						$logger->warning( "Session $this: Metadata merge failed: " . $ex->getMessage() );
						return false;
					}
				}
			}

			// Next, load the user from metadata, or validate it against the metadata.
			if ( !$this->user ) {
				// For loading, id is preferred to name.
				try {
					if ( $metadata['userId'] ) {
						$this->user = UserInfo::newFromId( $metadata['userId'] );
					} elseif ( $metadata['userName'] !== null ) { // Shouldn't happen, but just in case
						$this->user = UserInfo::newFromName( $metadata['userName'] );
					} else {
						$this->user = UserInfo::newAnonymous();
					}
				} catch ( \InvalidArgumentException $ex ) {
					$logger->error( "Session $this: " . $ex->getMessage() );
					return false;
				}
			} else {
				// User validation passes if user ID matches, or if there
				// is no saved ID and the names match.
				if ( $metadata['userId'] ) {
					if ( $metadata['userId'] !== $this->user->getId() ) {
						$logger->warning( "Session $this: User ID mismatch, " .
							$metadata['userId'] . ' !== ' . $this->user->getId() );
						return false;
					}

					// If the user was renamed, probably best to fail here.
					if ( $metadata['userName'] !== null &&
						$this->user->getName() !== $metadata['userName']
					) {
						$logger->warning( "Session $this: User ID matched but name didn't (rename?), " .
							$metadata['userName'] . ' !== ' . $this->user->getName() );
						return false;
					}

				} elseif ( $metadata['userName'] !== null ) { // Shouldn't happen, but just in case
					if ( $metadata['userName'] !== $this->user->getName() ) {
						$logger->warning( "Session $this: User name mismatch, " .
							$metadata['userName'] . ' !== ' . $this->user->getName() );
						return false;
					}
				} elseif ( !$this->user->isAnon() ) {
					// Metadata specifies an anonymous user, but the passed-in
					// user isn't anonymous.
					$logger->warning(
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
				$logger->warning( "Session $this: User token mismatch" );
				return false;
			}
			$this->user = $this->user->authenticated();

			if ( !empty( $metadata['remember'] ) ) {
				$this->remembered = true;
			}
			if ( !empty( $metadata['forceHTTPS'] ) ) {
				$this->forceHTTPS = true;
			}

			$this->idIsSafe = true;
		} else {
			// No metadata, so we can't load the provider if one wasn't given.
			if ( $this->provider === null ) {
				$logger->warning( "Session $this: Null provider and no metadata" );
				return false;
			}

			// If no user was provided and no metadata, it must be anon.
			if ( !$this->user ) {
				$this->user = UserInfo::newAnonymous();
			}
			if ( !$this->user->isAuthenticated() ) {
				$logger->warning(
					"Session $this: Unauthenticated user provided and no metadata to auth it"
				);
				return false;
			}

			$data = false;
			$metadata = false;

			if ( !$this->provider->persistsSessionId() ) {
				// The ID doesn't come from the user, so it should be safe
				// (and if not, nothing we can do about it anyway)
				$this->idIsSafe = true;
			}
		}

		// Give hooks a chance to abort. Combined with the SessionMetadata
		// hook, this can allow for tying a session to an IP address or the
		// like.
		$reason = 'Hook aborted';
		if ( !\Hooks::run(
			'SessionCheckInfo',
			array( &$reason, $this, $request, $metadata, $data )
		) ) {
			$logger->warning( "Session $this: $reason" );
			return false;
		}

		return true;
	}

	public function __toString() {
		return '[' . $this->getPriority() . ']' .
			( $this->getProvider() ?: 'null' ) .
			( $this->user ?: '<null>' ) . $this->getId();
	}

	/**
	 * Compare two SessionInfo objects by priority
	 * @param SessionInfo $a
	 * @param SessionInfo $b
	 * @return int Negative if $a < $b, positive if $a > $b, zero if equal
	 */
	public static function compare( $a, $b ) {
		return $a->getPriority() - $b->getPriority();
	}

}
