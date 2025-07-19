<?php
/**
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
 */

namespace MediaWiki\Session;

use IDBAccessObject;
use InvalidArgumentException;
use MediaWiki\Config\Config;
use MediaWiki\Context\RequestContext;
use MediaWiki\Exception\MWException;
use MediaWiki\Hook\GetSessionJwtDataHook;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\ProxyLookup;
use MediaWiki\Request\WebRequest;
use MediaWiki\User\CentralId\CentralIdLookup;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserNameUtils;
use MediaWiki\Utils\MWTimestamp;
use MediaWiki\Utils\UrlUtils;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * This serves as the entry point to the MediaWiki session handling system.
 *
 * Most methods here are for internal use by session handling code. Other callers
 * should only use the methods of SessionManagerInterface;
 * the rest of the functionality is exposed via MediaWiki\Session\Session methods,
 * which can be accessed from WebRequest::getSession().
 *
 * To provide custom session handling, implement a MediaWiki\Session\SessionProvider.
 *
 * @anchor SessionManager-storage-expectations
 *
 * ## Storage expectations
 *
 * The SessionManager should be configured with a very fast storage system that is
 * optimized for holding key-value pairs. It expects:
 *
 * - Low latencies. Session data is read or written to during nearly all web requests from
 *   people that have contributed to or otherwise engaged with the site, including those not
 *   logged in with a registered account.
 *
 * - Locally writable data. The data must be writable from both primary and secondary
 *   data centres.
 *
 * - Locally latest reads. Writes must by default be immediately consistent within
 *   the local data centre, and visible to other reads from web servers in that data centre.
 *
 * - Replication. The data must be eventually consistent across all data centres. Writes
 *   are either synced to all remote data centres, or locally overwritten by another write
 *   that is.
 *
 * The SessionManager uses `set()` and `delete()` for write operations, which should be
 * synchronous in the local data centre, and replicate asynchronously to any others.
 *
 * @see https://www.mediawiki.org/wiki/Manual:SessionManager_and_AuthManager
 * @since 1.27
 * @ingroup Session
 */
class SessionManager implements SessionManagerInterface {
	private LoggerInterface $logger;
	private HookContainer $hookContainer;
	private HookRunner $hookRunner;
	private Config $config;
	private CentralIdLookup $centralIdLookup;
	private UrlUtils $urlUtils;
	private UserNameUtils $userNameUtils;
	private SessionStore $sessionStore;
	private ObjectFactory $objectFactory;
	private ProxyLookup $proxyLookup;

	/** @var SessionProvider[] */
	private $sessionProviders = null;

	/** @var string[] */
	private $varyCookies = null;

	/** @var array */
	private $varyHeaders = null;

	/** @var SessionBackend[] */
	private $allSessionBackends = [];

	/** @var true[] */
	private $preventUsers = [];

	/** @var string */
	public const JWT_SUB_ANON = 'anon';

	/**
	 * Get the global SessionManager
	 * @deprecated since 1.45 Use MediaWikiServices::getInstance()->getSessionManager() instead
	 * @return self
	 * @suppress PhanTypeMismatchReturnSuperType
	 */
	public static function singleton() {
		return MediaWikiServices::getInstance()->getSessionManager();
	}

	/**
	 * @deprecated since 1.45 Use RequestContext::getMain()->getRequest()->getSession() instead
	 */
	public static function getGlobalSession(): Session {
		return RequestContext::getMain()->getRequest()->getSession();
	}

	public function __construct(
		Config $config,
		LoggerInterface $logger,
		CentralIdLookup $centralIdLookup,
		HookContainer $hookContainer,
		ObjectFactory $objectFactory,
		ProxyLookup $proxyLookup,
		UrlUtils $urlUtils,
		UserNameUtils $userNameUtils,
		SessionStore $sessionStore
	) {
		$this->config = $config;
		$this->setLogger( $logger );
		$this->centralIdLookup = $centralIdLookup;
		$this->setHookContainer( $hookContainer );
		$this->objectFactory = $objectFactory;
		$this->sessionStore = $sessionStore;

		$this->proxyLookup = $proxyLookup;
		$this->urlUtils = $urlUtils;
		$this->userNameUtils = $userNameUtils;
	}

	public function setLogger( LoggerInterface $logger ): void {
		$this->logger = $logger;
	}

	/**
	 * @internal
	 * @param HookContainer $hookContainer
	 */
	public function setHookContainer( HookContainer $hookContainer ) {
		$this->hookContainer = $hookContainer;
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/** @inheritDoc */
	public function getSessionForRequest( WebRequest $request ) {
		$info = $this->getSessionInfoForRequest( $request );

		if ( !$info ) {
			$session = $this->getInitialSession( $request );
		} else {
			$session = $this->getSessionFromInfo( $info, $request );
		}
		return $session;
	}

	/** @inheritDoc */
	public function getSessionById( $id, $create = false, ?WebRequest $request = null ) {
		if ( !self::validateSessionId( $id ) ) {
			throw new InvalidArgumentException( 'Invalid session ID' );
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
		if ( is_array( $this->sessionStore->get( $info ) ) ) {
			$create = false; // If loading fails, don't bother creating because it probably will fail too.
			if ( $this->loadSessionInfoFromStore( $info, $request ) ) {
				$session = $this->getSessionFromInfo( $info, $request );
			}
		}

		if ( $create && $session === null ) {
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

	/** @inheritDoc */
	public function getEmptySession( ?WebRequest $request = null ) {
		return $this->getEmptySessionInternal( $request );
	}

	/**
	 * @see SessionManagerInterface::getEmptySession
	 * @param WebRequest|null $request
	 * @param string|null $id ID to force on the new session
	 * @return Session
	 */
	private function getEmptySessionInternal( ?WebRequest $request = null, $id = null ) {
		if ( $id !== null ) {
			if ( !self::validateSessionId( $id ) ) {
				throw new InvalidArgumentException( 'Invalid session ID' );
			}

			$info = new SessionInfo(
				SessionInfo::MIN_PRIORITY,
				[
					'id' => $id,
					'idIsSafe' => true,
					'userInfo' => UserInfo::newAnonymous(),
				]
			);
			if ( is_array( $this->sessionStore->get( $info ) ) ) {
				throw new InvalidArgumentException( 'Session ID already exists' );
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

		// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
		return $this->getSessionFromInfo( $infos[0], $request );
	}

	/**
	 * Create a new session. Populate it with a default secret token, to avoid
	 * a session replication race on a subsequent edit/save cycle (e.g. in
	 * a multi-dc setup, ref https://phabricator.wikimedia.org/T279664#8139533).
	 *
	 * @param WebRequest|null $request Corresponding request. Any existing
	 *  session associated with this WebRequest object will be overwritten.
	 * @return Session
	 */
	private function getInitialSession( ?WebRequest $request = null ) {
		$session = $this->getEmptySession( $request );
		$session->getToken();
		return $session;
	}

	public function invalidateSessionsForUser( User $user ) {
		$user->setToken();
		$user->saveSettings();

		foreach ( $this->getProviders() as $provider ) {
			$provider->invalidateSessionsForUser( $user );
		}
	}

	/**
	 * @return array<string,null>
	 */
	public function getVaryHeaders() {
		// @codeCoverageIgnoreStart
		if ( defined( 'MW_NO_SESSION' ) && MW_NO_SESSION !== 'warn' ) {
			return [];
		}
		// @codeCoverageIgnoreEnd
		if ( $this->varyHeaders === null ) {
			$headers = [];
			foreach ( $this->getProviders() as $provider ) {
				foreach ( $provider->getVaryHeaders() as $header => $_ ) {
					$headers[$header] = null;
				}
			}
			$this->varyHeaders = $headers;
		}
		return $this->varyHeaders;
	}

	/** @inheritDoc */
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
	 * Return a set of key-value pairs applicable for use as claims in a JSON Web Token.
	 *
	 * This can be used to standardize some session credentials as JWTs. It is up to individual
	 * session providers whether and how to use JWTs, but if they use them, they should make use
	 * of this mechanism so that infrastructure outside MediaWiki that makes use of such JWTs
	 * can be standardized.
	 *
	 * Providers which call this method are free to extend or replace the values whenever that
	 * makes sense, but are encouraged to add at least the following fields:
	 *   - exp: hard expiry (as a UNIX timestamp), requests using this session token should be
	 *     rejected after this point in time.
	 *   - sxp: soft expiry (if different from hard expiry), the session token should be ignored
	 *     and the session treated as anonymous after this point.
	 *
	 * @param UserIdentity $user The user who is the subject of the claim.
	 * @return array A set of JWT claims (key-value pairs) as a JSON array.
	 *
	 * @since 1.45
	 * @see GetSessionJwtDataHook
	 */
	public function getJwtData( UserIdentity $user ): array {
		$centralId = null;
		$userName = $this->userNameUtils->getCanonical( $user->getName() );
		if ( $userName === false ) {
			$user = null;
		} else {
			$nameToId = [ $userName => 0 ];
			[ $userName => $centralId ] = $this->centralIdLookup->lookupOwnedUserNames( $nameToId,
				CentralIdLookup::AUDIENCE_RAW );
			if ( !$centralId ) {
				[ $userName => $centralId ] = $this->centralIdLookup->lookupOwnedUserNames( $nameToId,
					CentralIdLookup::AUDIENCE_RAW, IDBAccessObject::READ_LATEST );
			}
		}
		// Use a fake scheme for 'sub' since the spec says it should be an URI or a string that
		// cannot be mistaken for an URI, and the first seems less hassle
		$scheme = 'mw:';
		$userValue = $centralId
			? $this->centralIdLookup->getScope() . ':' . $centralId
			: self::JWT_SUB_ANON;
		$jwtData = [
			'iss' => $this->urlUtils->getCanonicalServer(),
			'sub' => $scheme . $userValue,
			// Omit 'exp', it depends on the provider.
			// Omit 'nbf', no different from 'iat' and RFC 7519 doesn't require it.
			'iat' => MWTimestamp::time(),
			'jti' => base64_encode( random_bytes( 16 ) ),
		];

		$this->hookRunner->onGetSessionJwtData( $user, $jwtData );
		return $jwtData;
	}

	/**
	 * Validate a session ID
	 * @param string $id
	 * @return bool
	 */
	public static function validateSessionId( $id ) {
		return is_string( $id ) && preg_match( '/^[a-zA-Z0-9_-]{32,}$/', $id );
	}

	/***************************************************************************/
	// region   Internal methods
	/** @name   Internal methods */

	/**
	 * Prevent future sessions for the user
	 *
	 * The intention is that the named account will never again be usable for
	 * normal login (i.e. there is no way to undo the prevention of access).
	 *
	 * @internal For use from \MediaWiki\User\User::newSystemUser only
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
	 * @internal For use from SessionBackend only
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
			foreach ( $this->config->get( MainConfigNames::SessionProviders ) as $spec ) {
				/** @var SessionProvider $provider */
				$provider = $this->objectFactory->createObject( $spec );
				$provider->init(
					$this->logger,
					$this->config,
					$this,
					$this->hookContainer,
					$this->userNameUtils
				);
				if ( isset( $this->sessionProviders[(string)$provider] ) ) {
					// @phan-suppress-next-line PhanTypeSuspiciousStringExpression
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
		return $providers[$name] ?? null;
	}

	/**
	 * Save all active sessions on shutdown
	 *
	 * @internal For use by PHPSessionHandler::install
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

		// https://www.php.net/manual/en/session.configuration.php#ini.session.gc-divisor
		// Doing this here because of how Session::gc() works in PHP session handler. The
		// only difference here is that this is done at the end of the request rather than
		// the beginning. But we want to preserve behavior for 1 in every 100 requests.
		if ( random_int( 1, 100 ) === 1 ) {
			// Do any garbage collection if we have expired entries
			$this->sessionStore->shutdown();
		}
	}

	/**
	 * Fetch the SessionInfo(s) for a request
	 *
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
		usort( $infos, [ SessionInfo::class, 'compare' ] );
		$retInfos = [];
		while ( $infos ) {
			$info = array_pop( $infos );
			if ( $this->loadSessionInfoFromStore( $info, $request ) ) {
				$retInfos[] = $info;
				while ( $infos ) {
					/** @var SessionInfo $info */
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
						$this->logUnpersist( $info, $request );
						$info->getProvider()->unpersistSession( $request );
					}
				}
			} else {
				// Session load failed, so unpersist it from this request
				$this->logUnpersist( $info, $request );
				$info->getProvider()->unpersistSession( $request );
			}
		}

		if ( count( $retInfos ) > 1 ) {
			throw new SessionOverflowException(
				$retInfos,
				'Multiple sessions for this request tied for top priority: ' . implode( ', ', $retInfos )
			);
		}

		return $retInfos[0] ?? null;
	}

	/**
	 * Load and verify the session info against the store
	 *
	 * @param SessionInfo &$info Will likely be replaced with an updated SessionInfo instance
	 * @param WebRequest $request
	 * @return bool Whether the session info matches the stored data (if any)
	 */
	private function loadSessionInfoFromStore( SessionInfo &$info, WebRequest $request ) {
		$blob = $this->sessionStore->get( $info );
		$oldInfo = $info;

		// If we got data from the store and the SessionInfo says to force use,
		// "fail" means to delete the data from the store and retry. Otherwise,
		// "fail" is just return false.
		if ( $info->forceUse() && $blob !== false ) {
			$failHandler = function () use ( $oldInfo, &$info, $request ) {
				$this->logSessionWrite( $request, $info, [
					'type' => 'delete',
					'reason' => 'loadSessionInfo fail',
				] );
				$this->sessionStore->delete( $oldInfo );
				return $this->loadSessionInfoFromStore( $info, $request );
			};
		} else {
			$failHandler = static function () {
				return false;
			};
		}

		$newParams = [];

		if ( $blob !== false ) {
			// Double check: blob must be an array, if it's saved at all
			if ( !is_array( $blob ) ) {
				$this->logSessionWrite( $request, $info, [
					'type' => 'delete',
					'reason' => 'bad data',
				], LogLevel::WARNING );
				$this->sessionStore->delete( $info );
				return $failHandler();
			}

			// Double check: blob has data and metadata arrays
			if ( !isset( $blob['data'] ) || !is_array( $blob['data'] ) ||
				!isset( $blob['metadata'] ) || !is_array( $blob['metadata'] )
			) {
				$this->logSessionWrite( $request, $info, [
					'type' => 'delete',
					'reason' => 'bad data structure',
				], LogLevel::WARNING );
				$this->sessionStore->delete( $info );
				return $failHandler();
			}

			$data = $blob['data'];
			$metadata = $blob['metadata'];

			// Double check: metadata must be an array and must contain certain
			// keys, if it's saved at all
			if ( !array_key_exists( 'userId', $metadata ) ||
				!array_key_exists( 'userName', $metadata ) ||
				!array_key_exists( 'userToken', $metadata ) ||
				!array_key_exists( 'provider', $metadata )
			) {
				$this->logSessionWrite( $request, $info, [
					'type' => 'delete',
					'reason' => 'bad metadata',
				], LogLevel::WARNING );
				$this->sessionStore->delete( $info );
				return $failHandler();
			}

			// First, load the provider from metadata, or validate it against the metadata.
			$provider = $info->getProvider();
			if ( $provider === null ) {
				$newParams['provider'] = $provider = $this->getProvider( $metadata['provider'] );
				if ( !$provider ) {
					$this->logSessionWrite( $request, $info, [
						'type' => 'delete',
						'reason' => 'unknown provider',
						'provider' => $metadata['provider'],
					], LogLevel::WARNING );
					$this->sessionStore->delete( $info );
					return $failHandler();
				}
			} elseif ( $metadata['provider'] !== (string)$provider ) {
				$this->logger->warning( 'Session "{session}": Wrong provider ' .
					$metadata['provider'] . ' !== ' . $provider,
					[
						'session' => $info->__toString(),
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
							'Session "{session}": Metadata merge failed: ' . $ex->getMessage(),
							[
								'session' => $info->__toString(),
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
				} catch ( InvalidArgumentException $ex ) {
					$this->logger->error( 'Session "{session}": {exception}', [
						'session' => $info->__toString(),
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
						// Maybe something like UserMerge changed the user ID. Or it's manual tampering.
						$this->logger->warning(
							'Session "{session}": User ID mismatch, {uid_a} !== {uid_b}',
							[
								'session' => $info->__toString(),
								'uid_a' => $metadata['userId'],
								'uid_b' => $userInfo->getId(),
								'uname_a' => $metadata['userName'] ?? '<null>',
								'uname_b' => $userInfo->getName() ?? '<null>',
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
								'session' => $info->__toString(),
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
								'session' => $info->__toString(),
								'uname_a' => $metadata['userName'],
								'uname_b' => $userInfo->getName(),
							] );
						return $failHandler();
					}
				} elseif ( !$userInfo->isAnon() ) {
					// The metadata in the session store entry indicates this is an anonymous session,
					// but the request metadata (e.g. the username cookie) says otherwise. Maybe the
					// user logged out but unsetting the cookies failed?
					$this->logger->warning(
						'Session "{session}": the session store entry is for an anonymous user, '
							. 'but the session metadata indicates a non-anonymous user',
						[
							'session' => $info->__toString(),
						] );
					return $failHandler();
				}
			}

			// And if we have a token in the metadata, it must match the loaded/provided user.
			// A mismatch probably means the session was invalidated.
			if ( $metadata['userToken'] !== null &&
				$userInfo->getToken() !== $metadata['userToken']
			) {
				$this->logger->warning( 'Session "{session}": User token mismatch', [
					'session' => $info->__toString(),
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
						'session' => $info->__toString(),
					] );
				return $failHandler();
			}

			// If no user was provided and no metadata, it must be anon.
			if ( !$info->getUserInfo() ) {
				if ( $info->getProvider()->canChangeUser() ) {
					$newParams['userInfo'] = UserInfo::newAnonymous();
				} else {
					// This is a session provider bug - providers with canChangeUser() === false
					// should never return an anonymous SessionInfo.
					$this->logger->info(
						'Session "{session}": No user provided and provider cannot set user',
						[
							'session' => $info->__toString(),
						] );
					return $failHandler();
				}
			} elseif ( !$info->getUserInfo()->isVerified() ) {
				// The session was not found in the session store, and the request contains no
				// information beyond the session ID that could be used to verify it.
				// Probably just a session timeout.
				$this->logger->info(
					'Session "{session}": Unverified user provided and no metadata to auth it',
					[
						'session' => $info->__toString(),
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
		if ( !$this->hookRunner->onSessionCheckInfo(
			$reason, $info, $request, $metadata, $data )
		) {
			$this->logger->warning( 'Session "{session}": ' . $reason, [
				'session' => $info->__toString(),
			] );
			return $failHandler();
		}

		return true;
	}

	/**
	 * Create a Session corresponding to the passed SessionInfo
	 *
	 * @internal For use by a SessionProvider that needs to specially create its
	 *  own Session. Most session providers won't need this.
	 * @param SessionInfo $info
	 * @param WebRequest $request
	 * @return Session
	 */
	public function getSessionFromInfo( SessionInfo $info, WebRequest $request ) {
		// @codeCoverageIgnoreStart
		if ( defined( 'MW_NO_SESSION' ) ) {
			$ep = defined( 'MW_ENTRY_POINT' ) ? MW_ENTRY_POINT : 'this';

			if ( MW_NO_SESSION === 'warn' ) {
				// Undocumented safety case for converting existing entry points
				$this->logger->error( 'Sessions are supposed to be disabled for this entry point', [
					'exception' => new \BadMethodCallException( "Sessions are disabled for $ep entry point" ),
				] );
			} else {
				throw new \BadMethodCallException( "Sessions are disabled for $ep entry point" );
			}
		}
		// @codeCoverageIgnoreEnd

		$id = $info->getId();

		if ( !isset( $this->allSessionBackends[$id] ) ) {
			$backend = new SessionBackend(
				new SessionId( $id ),
				$info,
				$this->sessionStore,
				$this->logger,
				$this->hookContainer,
				$this->config->get( MainConfigNames::ObjectCacheSessionExpiry )
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
	 * Change a SessionBackend's ID
	 *
	 * @internal For use from \MediaWiki\Session\SessionBackend only
	 * @param SessionBackend $backend
	 */
	public function changeBackendId( SessionBackend $backend ) {
		$sessionId = $backend->getSessionId();
		$oldId = (string)$sessionId;
		if ( !isset( $this->allSessionBackends[$oldId] ) ||
			$this->allSessionBackends[$oldId] !== $backend
		) {
			throw new InvalidArgumentException( 'Backend was not registered with this SessionManager' );
		}

		$newId = $this->generateSessionId();

		unset( $this->allSessionBackends[$oldId] );
		$sessionId->setId( $newId );
		$this->allSessionBackends[$newId] = $backend;
	}

	/**
	 * Generate a new random session ID
	 *
	 * @return string
	 */
	public function generateSessionId() {
		$id = \Wikimedia\base_convert( \MWCryptRand::generateHex( 40 ), 16, 32, 32 );
		// Cache non-existence to avoid a later fetch
		$info = new SessionInfo( SessionInfo::MIN_PRIORITY, [ 'id' => $id, 'idIsSafe' => true ] );
		$this->sessionStore->set( $info, false, 0, BagOStuff::WRITE_CACHE_ONLY );
		return $id;
	}

	/**
	 * Call setters on a PHPSessionHandler
	 *
	 * @internal Use PhpSessionHandler::install() instead.
	 * @param PHPSessionHandler $handler
	 */
	public function setupPHPSessionHandler( PHPSessionHandler $handler ) {
		$handler->setManager(
			$this,
			$this->logger
		);
	}

	private function logUnpersist( SessionInfo $info, WebRequest $request ) {
		$logData = [
			'id' => $info->getId(),
			'provider' => get_class( $info->getProvider() ),
			'user' => '<anon>',
			'supposedUser' => $info->getUserInfo() ? $info->getUserInfo()->getName() : null,
			'clientip' => $request->getIP(),
			'userAgent' => $request->getHeader( 'user-agent' ),
		];
		if ( $info->getUserInfo() ) {
			if ( !$info->getUserInfo()->isAnon() ) {
				$logData['user'] = $info->getUserInfo()->getName();
			}
			$logData['userVerified'] = $info->getUserInfo()->isVerified();
		}
		$this->logger->info( 'Failed to load session, unpersisting', $logData );
	}

	/**
	 * Write debug logs if the current request may have experienced session leak bug.
	 *
	 * If the same session is suddenly used from a different IP, that's potentially due
	 * to a session leak bug, so log it for investigation. In most cases, these are
	 * false positives simply due to a user switching connections. Noisy logs are fine,
	 * because we only use this audit trail by looking up a specific username.
	 *
	 * This also logs for unexpected changes to the "mwuser" cookie, an analytics cookie
	 * set from mediawiki.user.js, which should be a little less noisy.
	 *
	 * @internal For use in Setup.php only
	 * @param Session|null $session For testing only
	 */
	public function logPotentialSessionLeakage( ?Session $session = null ) {
		$session = $session ?: self::getGlobalSession();
		$suspiciousIpExpiry = $this->config->get( MainConfigNames::SuspiciousIpExpiry );

		if ( $suspiciousIpExpiry === false
			// We only care about logged-in users.
			|| !$session->isPersistent() || $session->getUser()->isAnon()
			// We only care about cookie-based sessions.
			|| !( $session->getProvider() instanceof CookieSessionProvider )
		) {
			return;
		}
		try {
			$ip = $session->getRequest()->getIP();
		} catch ( MWException ) {
			return;
		}
		if ( $ip === '127.0.0.1' || $this->proxyLookup->isConfiguredProxy( $ip ) ) {
			return;
		}
		$mwuser = $session->getRequest()->getCookie( 'mwuser-sessionId' );
		$now = (int)\MediaWiki\Utils\MWTimestamp::now( TS_UNIX );

		// Record (and possibly log) that the IP is using the current session.
		// Don't touch the stored data unless we are changing the IP or re-adding an expired one.
		// This is slightly inaccurate (when an existing IP is seen again, the expiry is not
		// extended) but that shouldn't make much difference and limits the session write frequency.
		$data = $session->get( 'SessionManager-logPotentialSessionLeakage', [] )
			+ [ 'ip' => null, 'mwuser' => null, 'timestamp' => 0 ];
		// Ignore old IP records; users change networks over time. mwuser is a session cookie and the
		// SessionManager session id is also a session cookie so there shouldn't be any problem there.
		if ( $data['ip'] &&
			( $now - $data['timestamp'] > $suspiciousIpExpiry )
		) {
			$data['ip'] = $data['timestamp'] = null;
		}

		if ( $data['ip'] !== $ip || $data['mwuser'] !== $mwuser ) {
			$session->set( 'SessionManager-logPotentialSessionLeakage',
				[ 'ip' => $ip, 'mwuser' => $mwuser, 'timestamp' => $now ] );
		}

		$ipChanged = ( $data['ip'] && $data['ip'] !== $ip );
		$mwuserChanged = ( $data['mwuser'] && $data['mwuser'] !== $mwuser );
		$logLevel = $message = null;
		$logData = [];
		// IPs change all the time. mwuser is a session cookie that's only set when missing,
		// so it should only change when the browser session ends which ends the SessionManager
		// session as well. Unless we are dealing with a very weird client, such as a bot that
		//manipulates cookies and can run Javascript, it should not change.
		// IP and mwuser changing at the same time would be *very* suspicious.
		if ( $ipChanged ) {
			$logLevel = LogLevel::INFO;
			$message = 'IP change within the same session';
			$logData += [
				'oldIp' => $data['ip'],
				'oldIpRecorded' => $data['timestamp'],
			];
		}
		if ( $mwuserChanged ) {
			$logLevel = LogLevel::NOTICE;
			$message = 'mwuser change within the same session';
			$logData += [
				'oldMwuser' => $data['mwuser'],
				'newMwuser' => $mwuser,
			];
		}
		if ( $ipChanged && $mwuserChanged ) {
			$logLevel = LogLevel::WARNING;
			$message = 'IP and mwuser change within the same session';
		}
		if ( $logLevel ) {
			$logData += [
				'session' => $session->getId(),
				'user' => $session->getUser()->getName(),
				'clientip' => $ip,
				'userAgent' => $session->getRequest()->getHeader( 'user-agent' ),
			];
			$logger = LoggerFactory::getInstance( 'session-ip' );
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable message is set when used here
			$logger->log( $logLevel, $message, $logData );
		}
	}

	/**
	 * @param WebRequest $request
	 * @param SessionInfo $info
	 * @param array $data Additional log context. Should have at least the following keys:
	 *   - type: 'write' or 'delete'.
	 *   - reason: why the write happened
	 * @param string $level PSR LogLevel constant (defaults to info)
	 * @see SessionBackend::logSessionWrite()
	 */
	private function logSessionWrite(
		WebRequest $request,
		SessionInfo $info,
		array $data,
		string $level = LogLevel::INFO
	): void {
		$user = ( !$info->getUserInfo() || $info->getUserInfo()->isAnon() )
			? '<anon>'
			: $info->getUserInfo()->getName();
		LoggerFactory::getInstance( 'session-sampled' )->log( $level, 'Session store: {action} for {reason}', $data + [
				'action' => $data['type'],
				'reason' => $data['reason'],
				'id' => $info->getId(),
				'provider' => $info->getProvider() ? get_class( $info->getProvider() ) : 'null',
				'user' => $user,
				'clientip' => $request->getIP(),
				'userAgent' => $request->getHeader( 'user-agent' ),
			] );
	}

	// endregion -- end of Internal methods

}
