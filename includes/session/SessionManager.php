<?php
/**
 * MediaWiki\Session entry point
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

use MWException;
use Psr\Log\LoggerInterface;
use BagOStuff;
use CachedBagOStuff;
use Config;
use FauxRequest;
use User;
use WebRequest;

/**
 * This serves as the entry point to the MediaWiki session handling system.
 *
 * Most methods here are for internal use by session handling code. Other callers
 * should only use getGlobalSession and the methods of SessionManagerInterface;
 * the rest of the functionality is exposed via MediaWiki\Session\Session methods.
 *
 * To provide custom session handling, implement a MediaWiki\Session\SessionProvider.
 *
 * @ingroup Session
 * @since 1.27
 * @see https://www.mediawiki.org/wiki/Manual:SessionManager_and_AuthManager
 */
final class SessionManager implements SessionManagerInterface {
	/** @var SessionManager|null */
	private static $instance = null;

	/** @var Session|null */
	private static $globalSession = null;

	/** @var WebRequest|null */
	private static $globalSessionRequest = null;

	/** @var LoggerInterface */
	private $logger;

	/** @var Config */
	private $config;

	/** @var CachedBagOStuff|null */
	private $store;

	/** @var SessionProvider[] */
	private $sessionProviders = null;

	/** @var string[] */
	private $varyCookies = null;

	/** @var array */
	private $varyHeaders = null;

	/** @var SessionBackend[] */
	private $allSessionBackends = [];

	/** @var SessionId[] */
	private $allSessionIds = [];

	/** @var string[] */
	private $preventUsers = [];

	/**
	 * Get the global SessionManager
	 * @return SessionManagerInterface
	 *  (really a SessionManager, but this is to make IDEs less confused)
	 */
	public static function singleton() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Get the "global" session
	 *
	 * If PHP's session_id() has been set, returns that session. Otherwise
	 * returns the session for RequestContext::getMain()->getRequest().
	 *
	 * @return Session
	 */
	public static function getGlobalSession() {
		if ( !PHPSessionHandler::isEnabled() ) {
			$id = '';
		} else {
			$id = session_id();
		}

		$request = \RequestContext::getMain()->getRequest();
		if (
			!self::$globalSession // No global session is set up yet
			|| self::$globalSessionRequest !== $request // The global WebRequest changed
			|| $id !== '' && self::$globalSession->getId() !== $id // Someone messed with session_id()
		) {
			self::$globalSessionRequest = $request;
			if ( $id === '' ) {
				// session_id() wasn't used, so fetch the Session from the WebRequest.
				// We use $request->getSession() instead of $singleton->getSessionForRequest()
				// because doing the latter would require a public
				// "$request->getSessionId()" method that would confuse end
				// users by returning SessionId|null where they'd expect it to
				// be short for $request->getSession()->getId(), and would
				// wind up being a duplicate of the code in
				// $request->getSession() anyway.
				self::$globalSession = $request->getSession();
			} else {
				// Someone used session_id(), so we need to follow suit.
				// Note this overwrites whatever session might already be
				// associated with $request with the one for $id.
				self::$globalSession = self::singleton()->getSessionById( $id, true, $request )
					?: $request->getSession();
			}
		}
		return self::$globalSession;
	}

	/**
	 * @param array $options
	 *  - config: Config to fetch configuration from. Defaults to the default 'main' config.
	 *  - logger: LoggerInterface to use for logging. Defaults to the 'session' channel.
	 *  - store: BagOStuff to store session data in.
	 */
	public function __construct( $options = [] ) {
		if ( isset( $options['config'] ) ) {
			$this->config = $options['config'];
			if ( !$this->config instanceof Config ) {
				throw new \InvalidArgumentException(
					'$options[\'config\'] must be an instance of Config'
				);
			}
		} else {
			$this->config = \ConfigFactory::getDefaultInstance()->makeConfig( 'main' );
		}

		if ( isset( $options['logger'] ) ) {
			if ( !$options['logger'] instanceof LoggerInterface ) {
				throw new \InvalidArgumentException(
					'$options[\'logger\'] must be an instance of LoggerInterface'
				);
			}
			$this->setLogger( $options['logger'] );
		} else {
			$this->setLogger( \MediaWiki\Logger\LoggerFactory::getInstance( 'session' ) );
		}

		if ( isset( $options['store'] ) ) {
			if ( !$options['store'] instanceof BagOStuff ) {
				throw new \InvalidArgumentException(
					'$options[\'store\'] must be an instance of BagOStuff'
				);
			}
			$store = $options['store'];
		} else {
			$store = \ObjectCache::getInstance( $this->config->get( 'SessionCacheType' ) );
		}
		$this->store = $store instanceof CachedBagOStuff ? $store : new CachedBagOStuff( $store );

		register_shutdown_function( [ $this, 'shutdown' ] );
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	public function getSessionForRequest( WebRequest $request ) {
		$info = $this->getSessionInfoForRequest( $request );

		if ( !$info ) {
			$session = $this->getEmptySession( $request );
		} else {
			$session = $this->getSessionFromInfo( $info, $request );
		}
		return $session;
	}

	public function getSessionById( $id, $create = false, WebRequest $request = null ) {
		if ( !self::validateSessionId( $id ) ) {
			throw new \InvalidArgumentException( 'Invalid session ID' );
		}
		if ( !$request ) {
			$request = new FauxRequest;
		}

		$session = null;
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [ 'id' => $id, 'idIsSafe' => true ] );

		// If we already have the backend loaded, use it directly
		if ( isset( $this->allSessionBackends[$id] ) ) {
			return $this->getSessionFromInfo( $info, $request );
		}

		// Test if the session is in storage, and if so try to load it.
		$key = wfMemcKey( 'MWSession', $id );
		if ( is_array( $this->store->get( $key ) ) ) {
			$create = false; // If loading fails, don't bother creating because it probably will fail too.
			if ( $this->loadSessionInfoFromStore( $info, $request ) ) {
				$session = $this->getSessionFromInfo( $info, $request );
			}
		}

		if ( $create && $session === null ) {
			$ex = null;
			try {
				$session = $this->getEmptySessionInternal( $request, $id );
			} catch ( \Exception $ex ) {
				$this->logger->error( 'Failed to create empty session: {exception}',
					[
						'method' => __METHOD__,
						'exception' => $ex,
				] );
				$session = null;
			}
		}

		return $session;
	}

	public function getEmptySession( WebRequest $request = null ) {
		return $this->getEmptySessionInternal( $request );
	}

	/**
	 * @see SessionManagerInterface::getEmptySession
	 * @param WebRequest|null $request
	 * @param string|null $id ID to force on the new session
	 * @return Session
	 */
	private function getEmptySessionInternal( WebRequest $request = null, $id = null ) {
		if ( $id !== null ) {
			if ( !self::validateSessionId( $id ) ) {
				throw new \InvalidArgumentException( 'Invalid session ID' );
			}

			$key = wfMemcKey( 'MWSession', $id );
			if ( is_array( $this->store->get( $key ) ) ) {
				throw new \InvalidArgumentException( 'Session ID already exists' );
			}
		}
		if ( !$request ) {
			$request = new FauxRequest;
		}

		$infos = [];
		foreach ( $this->getProviders() as $provider ) {
			$info = $provider->newSessionInfo( $id );
			if ( !$info ) {
				continue;
			}
			if ( $info->getProvider() !== $provider ) {
				throw new \UnexpectedValueException(
					"$provider returned an empty session info for a different provider: $info"
				);
			}
			if ( $id !== null && $info->getId() !== $id ) {
				throw new \UnexpectedValueException(
					"$provider returned empty session info with a wrong id: " .
						$info->getId() . ' != ' . $id
				);
			}
			if ( !$info->isIdSafe() ) {
				throw new \UnexpectedValueException(
					"$provider returned empty session info with id flagged unsafe"
				);
			}
			$compare = $infos ? SessionInfo::compare( $infos[0], $info ) : -1;
			if ( $compare > 0 ) {
				continue;
			}
			if ( $compare === 0 ) {
				$infos[] = $info;
			} else {
				$infos = [ $info ];
			}
		}

		// Make sure there's exactly one
		if ( count( $infos ) > 1 ) {
			throw new \UnexpectedValueException(
				'Multiple empty sessions tied for top priority: ' . implode( ', ', $infos )
			);
		} elseif ( count( $infos ) < 1 ) {
			throw new \UnexpectedValueException( 'No provider could provide an empty session!' );
		}

		return $this->getSessionFromInfo( $infos[0], $request );
	}

	public function invalidateSessionsForUser( User $user ) {
		$user->setToken();
		$user->saveSettings();

		$authUser = \MediaWiki\Auth\AuthManager::callLegacyAuthPlugin( 'getUserInstance', [ &$user ] );
		if ( $authUser ) {
			$authUser->resetAuthToken();
		}

		foreach ( $this->getProviders() as $provider ) {
			$provider->invalidateSessionsForUser( $user );
		}
	}

	public function getVaryHeaders() {
		// @codeCoverageIgnoreStart
		if ( defined( 'MW_NO_SESSION' ) && MW_NO_SESSION !== 'warn' ) {
			return [];
		}
		// @codeCoverageIgnoreEnd
		if ( $this->varyHeaders === null ) {
			$headers = [];
			foreach ( $this->getProviders() as $provider ) {
				foreach ( $provider->getVaryHeaders() as $header => $options ) {
					if ( !isset( $headers[$header] ) ) {
						$headers[$header] = [];
					}
					if ( is_array( $options ) ) {
						$headers[$header] = array_unique( array_merge( $headers[$header], $options ) );
					}
				}
			}
			$this->varyHeaders = $headers;
		}
		return $this->varyHeaders;
	}

	public function getVaryCookies() {
		// @codeCoverageIgnoreStart
		if ( defined( 'MW_NO_SESSION' ) && MW_NO_SESSION !== 'warn' ) {
			return [];
		}
		// @codeCoverageIgnoreEnd
		if ( $this->varyCookies === null ) {
			$cookies = [];
			foreach ( $this->getProviders() as $provider ) {
				$cookies = array_merge( $cookies, $provider->getVaryCookies() );
			}
			$this->varyCookies = array_values( array_unique( $cookies ) );
		}
		return $this->varyCookies;
	}

	/**
	 * Validate a session ID
	 * @param string $id
	 * @return bool
	 */
	public static function validateSessionId( $id ) {
		return is_string( $id ) && preg_match( '/^[a-zA-Z0-9_-]{32,}$/', $id );
	}

	/**
	 * @name Internal methods
	 * @{
	 */

	/**
	 * Auto-create the given user, if necessary
	 * @private Don't call this yourself. Let Setup.php do it for you at the right time.
	 * @deprecated since 1.27, use MediaWiki\Auth\AuthManager::autoCreateUser instead
	 * @param User $user User to auto-create
	 * @return bool Success
	 * @codeCoverageIgnore
	 */
	public static function autoCreateUser( User $user ) {
		wfDeprecated( __METHOD__, '1.27' );
		return \MediaWiki\Auth\AuthManager::singleton()->autoCreateUser(
			$user,
			\MediaWiki\Auth\AuthManager::AUTOCREATE_SOURCE_SESSION,
			false
		)->isGood();
	}

	/**
	 * Prevent future sessions for the user
	 *
	 * The intention is that the named account will never again be usable for
	 * normal login (i.e. there is no way to undo the prevention of access).
	 *
	 * @private For use from \User::newSystemUser only
	 * @param string $username
	 */
	public function preventSessionsForUser( $username ) {
		$this->preventUsers[$username] = true;

		// Instruct the session providers to kill any other sessions too.
		foreach ( $this->getProviders() as $provider ) {
			$provider->preventSessionsForUser( $username );
		}
	}

	/**
	 * Test if a user is prevented
	 * @private For use from SessionBackend only
	 * @param string $username
	 * @return bool
	 */
	public function isUserSessionPrevented( $username ) {
		return !empty( $this->preventUsers[$username] );
	}

	/**
	 * Get the available SessionProviders
	 * @return SessionProvider[]
	 */
	protected function getProviders() {
		if ( $this->sessionProviders === null ) {
			$this->sessionProviders = [];
			foreach ( $this->config->get( 'SessionProviders' ) as $spec ) {
				$provider = \ObjectFactory::getObjectFromSpec( $spec );
				$provider->setLogger( $this->logger );
				$provider->setConfig( $this->config );
				$provider->setManager( $this );
				if ( isset( $this->sessionProviders[(string)$provider] ) ) {
					throw new \UnexpectedValueException( "Duplicate provider name \"$provider\"" );
				}
				$this->sessionProviders[(string)$provider] = $provider;
			}
		}
		return $this->sessionProviders;
	}

	/**
	 * Get a session provider by name
	 *
	 * Generally, this will only be used by internal implementation of some
	 * special session-providing mechanism. General purpose code, if it needs
	 * to access a SessionProvider at all, will use Session::getProvider().
	 *
	 * @param string $name
	 * @return SessionProvider|null
	 */
	public function getProvider( $name ) {
		$providers = $this->getProviders();
		return isset( $providers[$name] ) ? $providers[$name] : null;
	}

	/**
	 * Save all active sessions on shutdown
	 * @private For internal use with register_shutdown_function()
	 */
	public function shutdown() {
		if ( $this->allSessionBackends ) {
			$this->logger->debug( 'Saving all sessions on shutdown' );
			if ( session_id() !== '' ) {
				// @codeCoverageIgnoreStart
				session_write_close();
			}
			// @codeCoverageIgnoreEnd
			foreach ( $this->allSessionBackends as $backend ) {
				$backend->shutdown();
			}
		}
	}

	/**
	 * Fetch the SessionInfo(s) for a request
	 * @param WebRequest $request
	 * @return SessionInfo|null
	 */
	private function getSessionInfoForRequest( WebRequest $request ) {
		// Call all providers to fetch "the" session
		$infos = [];
		foreach ( $this->getProviders() as $provider ) {
			$info = $provider->provideSessionInfo( $request );
			if ( !$info ) {
				continue;
			}
			if ( $info->getProvider() !== $provider ) {
				throw new \UnexpectedValueException(
					"$provider returned session info for a different provider: $info"
				);
			}
			$infos[] = $info;
		}

		// Sort the SessionInfos. Then find the first one that can be
		// successfully loaded, and then all the ones after it with the same
		// priority.
		usort( $infos, 'MediaWiki\\Session\\SessionInfo::compare' );
		$retInfos = [];
		while ( $infos ) {
			$info = array_pop( $infos );
			if ( $this->loadSessionInfoFromStore( $info, $request ) ) {
				$retInfos[] = $info;
				while ( $infos ) {
					$info = array_pop( $infos );
					if ( SessionInfo::compare( $retInfos[0], $info ) ) {
						// We hit a lower priority, stop checking.
						break;
					}
					if ( $this->loadSessionInfoFromStore( $info, $request ) ) {
						// This is going to error out below, but we want to
						// provide a complete list.
						$retInfos[] = $info;
					} else {
						// Session load failed, so unpersist it from this request
						$info->getProvider()->unpersistSession( $request );
					}
				}
			} else {
				// Session load failed, so unpersist it from this request
				$info->getProvider()->unpersistSession( $request );
			}
		}

		if ( count( $retInfos ) > 1 ) {
			$ex = new \OverflowException(
				'Multiple sessions for this request tied for top priority: ' . implode( ', ', $retInfos )
			);
			$ex->sessionInfos = $retInfos;
			throw $ex;
		}

		return $retInfos ? $retInfos[0] : null;
	}

	/**
	 * Load and verify the session info against the store
	 *
	 * @param SessionInfo &$info Will likely be replaced with an updated SessionInfo instance
	 * @param WebRequest $request
	 * @return bool Whether the session info matches the stored data (if any)
	 */
	private function loadSessionInfoFromStore( SessionInfo &$info, WebRequest $request ) {
		$key = wfMemcKey( 'MWSession', $info->getId() );
		$blob = $this->store->get( $key );

		// If we got data from the store and the SessionInfo says to force use,
		// "fail" means to delete the data from the store and retry. Otherwise,
		// "fail" is just return false.
		if ( $info->forceUse() && $blob !== false ) {
			$failHandler = function () use ( $key, &$info, $request ) {
				$this->store->delete( $key );
				return $this->loadSessionInfoFromStore( $info, $request );
			};
		} else {
			$failHandler = function () {
				return false;
			};
		}

		$newParams = [];

		if ( $blob !== false ) {
			// Sanity check: blob must be an array, if it's saved at all
			if ( !is_array( $blob ) ) {
				$this->logger->warning( 'Session "{session}": Bad data', [
					'session' => $info,
				] );
				$this->store->delete( $key );
				return $failHandler();
			}

			// Sanity check: blob has data and metadata arrays
			if ( !isset( $blob['data'] ) || !is_array( $blob['data'] ) ||
				!isset( $blob['metadata'] ) || !is_array( $blob['metadata'] )
			) {
				$this->logger->warning( 'Session "{session}": Bad data structure', [
					'session' => $info,
				] );
				$this->store->delete( $key );
				return $failHandler();
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
				$this->logger->warning( 'Session "{session}": Bad metadata', [
					'session' => $info,
				] );
				$this->store->delete( $key );
				return $failHandler();
			}

			// First, load the provider from metadata, or validate it against the metadata.
			$provider = $info->getProvider();
			if ( $provider === null ) {
				$newParams['provider'] = $provider = $this->getProvider( $metadata['provider'] );
				if ( !$provider ) {
					$this->logger->warning(
						'Session "{session}": Unknown provider ' . $metadata['provider'],
						[
							'session' => $info,
						]
					);
					$this->store->delete( $key );
					return $failHandler();
				}
			} elseif ( $metadata['provider'] !== (string)$provider ) {
				$this->logger->warning( 'Session "{session}": Wrong provider ' .
					$metadata['provider'] . ' !== ' . $provider,
					[
						'session' => $info,
				] );
				return $failHandler();
			}

			// Load provider metadata from metadata, or validate it against the metadata
			$providerMetadata = $info->getProviderMetadata();
			if ( isset( $metadata['providerMetadata'] ) ) {
				if ( $providerMetadata === null ) {
					$newParams['metadata'] = $metadata['providerMetadata'];
				} else {
					try {
						$newProviderMetadata = $provider->mergeMetadata(
							$metadata['providerMetadata'], $providerMetadata
						);
						if ( $newProviderMetadata !== $providerMetadata ) {
							$newParams['metadata'] = $newProviderMetadata;
						}
					} catch ( MetadataMergeException $ex ) {
						$this->logger->warning(
							'Session "{session}": Metadata merge failed: {exception}',
							[
								'session' => $info,
								'exception' => $ex,
							] + $ex->getContext()
						);
						return $failHandler();
					}
				}
			}

			// Next, load the user from metadata, or validate it against the metadata.
			$userInfo = $info->getUserInfo();
			if ( !$userInfo ) {
				// For loading, id is preferred to name.
				try {
					if ( $metadata['userId'] ) {
						$userInfo = UserInfo::newFromId( $metadata['userId'] );
					} elseif ( $metadata['userName'] !== null ) { // Shouldn't happen, but just in case
						$userInfo = UserInfo::newFromName( $metadata['userName'] );
					} else {
						$userInfo = UserInfo::newAnonymous();
					}
				} catch ( \InvalidArgumentException $ex ) {
					$this->logger->error( 'Session "{session}": {exception}', [
						'session' => $info,
						'exception' => $ex,
					] );
					return $failHandler();
				}
				$newParams['userInfo'] = $userInfo;
			} else {
				// User validation passes if user ID matches, or if there
				// is no saved ID and the names match.
				if ( $metadata['userId'] ) {
					if ( $metadata['userId'] !== $userInfo->getId() ) {
						$this->logger->warning(
							'Session "{session}": User ID mismatch, {uid_a} !== {uid_b}',
							[
								'session' => $info,
								'uid_a' => $metadata['userId'],
								'uid_b' => $userInfo->getId(),
						] );
						return $failHandler();
					}

					// If the user was renamed, probably best to fail here.
					if ( $metadata['userName'] !== null &&
						$userInfo->getName() !== $metadata['userName']
					) {
						$this->logger->warning(
							'Session "{session}": User ID matched but name didn\'t (rename?), {uname_a} !== {uname_b}',
							[
								'session' => $info,
								'uname_a' => $metadata['userName'],
								'uname_b' => $userInfo->getName(),
						] );
						return $failHandler();
					}

				} elseif ( $metadata['userName'] !== null ) { // Shouldn't happen, but just in case
					if ( $metadata['userName'] !== $userInfo->getName() ) {
						$this->logger->warning(
							'Session "{session}": User name mismatch, {uname_a} !== {uname_b}',
							[
								'session' => $info,
								'uname_a' => $metadata['userName'],
								'uname_b' => $userInfo->getName(),
						] );
						return $failHandler();
					}
				} elseif ( !$userInfo->isAnon() ) {
					// Metadata specifies an anonymous user, but the passed-in
					// user isn't anonymous.
					$this->logger->warning(
						'Session "{session}": Metadata has an anonymous user, but a non-anon user was provided',
						[
							'session' => $info,
					] );
					return $failHandler();
				}
			}

			// And if we have a token in the metadata, it must match the loaded/provided user.
			if ( $metadata['userToken'] !== null &&
				$userInfo->getToken() !== $metadata['userToken']
			) {
				$this->logger->warning( 'Session "{session}": User token mismatch', [
					'session' => $info,
				] );
				return $failHandler();
			}
			if ( !$userInfo->isVerified() ) {
				$newParams['userInfo'] = $userInfo->verified();
			}

			if ( !empty( $metadata['remember'] ) && !$info->wasRemembered() ) {
				$newParams['remembered'] = true;
			}
			if ( !empty( $metadata['forceHTTPS'] ) && !$info->forceHTTPS() ) {
				$newParams['forceHTTPS'] = true;
			}
			if ( !empty( $metadata['persisted'] ) && !$info->wasPersisted() ) {
				$newParams['persisted'] = true;
			}

			if ( !$info->isIdSafe() ) {
				$newParams['idIsSafe'] = true;
			}
		} else {
			// No metadata, so we can't load the provider if one wasn't given.
			if ( $info->getProvider() === null ) {
				$this->logger->warning(
					'Session "{session}": Null provider and no metadata',
					[
						'session' => $info,
				] );
				return $failHandler();
			}

			// If no user was provided and no metadata, it must be anon.
			if ( !$info->getUserInfo() ) {
				if ( $info->getProvider()->canChangeUser() ) {
					$newParams['userInfo'] = UserInfo::newAnonymous();
				} else {
					$this->logger->info(
						'Session "{session}": No user provided and provider cannot set user',
						[
							'session' => $info,
					] );
					return $failHandler();
				}
			} elseif ( !$info->getUserInfo()->isVerified() ) {
				$this->logger->warning(
					'Session "{session}": Unverified user provided and no metadata to auth it',
					[
						'session' => $info,
				] );
				return $failHandler();
			}

			$data = false;
			$metadata = false;

			if ( !$info->getProvider()->persistsSessionId() && !$info->isIdSafe() ) {
				// The ID doesn't come from the user, so it should be safe
				// (and if not, nothing we can do about it anyway)
				$newParams['idIsSafe'] = true;
			}
		}

		// Construct the replacement SessionInfo, if necessary
		if ( $newParams ) {
			$newParams['copyFrom'] = $info;
			$info = new SessionInfo( $info->getPriority(), $newParams );
		}

		// Allow the provider to check the loaded SessionInfo
		$providerMetadata = $info->getProviderMetadata();
		if ( !$info->getProvider()->refreshSessionInfo( $info, $request, $providerMetadata ) ) {
			return $failHandler();
		}
		if ( $providerMetadata !== $info->getProviderMetadata() ) {
			$info = new SessionInfo( $info->getPriority(), [
				'metadata' => $providerMetadata,
				'copyFrom' => $info,
			] );
		}

		// Give hooks a chance to abort. Combined with the SessionMetadata
		// hook, this can allow for tying a session to an IP address or the
		// like.
		$reason = 'Hook aborted';
		if ( !\Hooks::run(
			'SessionCheckInfo',
			[ &$reason, $info, $request, $metadata, $data ]
		) ) {
			$this->logger->warning( 'Session "{session}": ' . $reason, [
				'session' => $info,
			] );
			return $failHandler();
		}

		return true;
	}

	/**
	 * Create a Session corresponding to the passed SessionInfo
	 * @private For use by a SessionProvider that needs to specially create its
	 *  own Session. Most session providers won't need this.
	 * @param SessionInfo $info
	 * @param WebRequest $request
	 * @return Session
	 */
	public function getSessionFromInfo( SessionInfo $info, WebRequest $request ) {
		// @codeCoverageIgnoreStart
		if ( defined( 'MW_NO_SESSION' ) ) {
			if ( MW_NO_SESSION === 'warn' ) {
				// Undocumented safety case for converting existing entry points
				$this->logger->error( 'Sessions are supposed to be disabled for this entry point', [
					'exception' => new \BadMethodCallException( 'Sessions are disabled for this entry point' ),
				] );
			} else {
				throw new \BadMethodCallException( 'Sessions are disabled for this entry point' );
			}
		}
		// @codeCoverageIgnoreEnd

		$id = $info->getId();

		if ( !isset( $this->allSessionBackends[$id] ) ) {
			if ( !isset( $this->allSessionIds[$id] ) ) {
				$this->allSessionIds[$id] = new SessionId( $id );
			}
			$backend = new SessionBackend(
				$this->allSessionIds[$id],
				$info,
				$this->store,
				$this->logger,
				$this->config->get( 'ObjectCacheSessionExpiry' )
			);
			$this->allSessionBackends[$id] = $backend;
			$delay = $backend->delaySave();
		} else {
			$backend = $this->allSessionBackends[$id];
			$delay = $backend->delaySave();
			if ( $info->wasPersisted() ) {
				$backend->persist();
			}
			if ( $info->wasRemembered() ) {
				$backend->setRememberUser( true );
			}
		}

		$request->setSessionId( $backend->getSessionId() );
		$session = $backend->getSession( $request );

		if ( !$info->isIdSafe() ) {
			$session->resetId();
		}

		\Wikimedia\ScopedCallback::consume( $delay );
		return $session;
	}

	/**
	 * Deregister a SessionBackend
	 * @private For use from \MediaWiki\Session\SessionBackend only
	 * @param SessionBackend $backend
	 */
	public function deregisterSessionBackend( SessionBackend $backend ) {
		$id = $backend->getId();
		if ( !isset( $this->allSessionBackends[$id] ) || !isset( $this->allSessionIds[$id] ) ||
			$this->allSessionBackends[$id] !== $backend ||
			$this->allSessionIds[$id] !== $backend->getSessionId()
		) {
			throw new \InvalidArgumentException( 'Backend was not registered with this SessionManager' );
		}

		unset( $this->allSessionBackends[$id] );
		// Explicitly do not unset $this->allSessionIds[$id]
	}

	/**
	 * Change a SessionBackend's ID
	 * @private For use from \MediaWiki\Session\SessionBackend only
	 * @param SessionBackend $backend
	 */
	public function changeBackendId( SessionBackend $backend ) {
		$sessionId = $backend->getSessionId();
		$oldId = (string)$sessionId;
		if ( !isset( $this->allSessionBackends[$oldId] ) || !isset( $this->allSessionIds[$oldId] ) ||
			$this->allSessionBackends[$oldId] !== $backend ||
			$this->allSessionIds[$oldId] !== $sessionId
		) {
			throw new \InvalidArgumentException( 'Backend was not registered with this SessionManager' );
		}

		$newId = $this->generateSessionId();

		unset( $this->allSessionBackends[$oldId], $this->allSessionIds[$oldId] );
		$sessionId->setId( $newId );
		$this->allSessionBackends[$newId] = $backend;
		$this->allSessionIds[$newId] = $sessionId;
	}

	/**
	 * Generate a new random session ID
	 * @return string
	 */
	public function generateSessionId() {
		do {
			$id = \Wikimedia\base_convert( \MWCryptRand::generateHex( 40 ), 16, 32, 32 );
			$key = wfMemcKey( 'MWSession', $id );
		} while ( isset( $this->allSessionIds[$id] ) || is_array( $this->store->get( $key ) ) );
		return $id;
	}

	/**
	 * Call setters on a PHPSessionHandler
	 * @private Use PhpSessionHandler::install()
	 * @param PHPSessionHandler $handler
	 */
	public function setupPHPSessionHandler( PHPSessionHandler $handler ) {
		$handler->setManager( $this, $this->store, $this->logger );
	}

	/**
	 * Reset the internal caching for unit testing
	 * @protected Unit tests only
	 */
	public static function resetCache() {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			// @codeCoverageIgnoreStart
			throw new MWException( __METHOD__ . ' may only be called from unit tests!' );
			// @codeCoverageIgnoreEnd
		}

		self::$globalSession = null;
		self::$globalSessionRequest = null;
	}

	/**@}*/

}
