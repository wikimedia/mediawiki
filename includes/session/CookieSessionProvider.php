<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Session;

use InvalidArgumentException;
use MediaWiki\Json\JwtCodec;
use MediaWiki\Json\JwtException;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\WebRequest;
use MediaWiki\User\User;
use MediaWiki\User\UserRigorOptions;
use MediaWiki\Utils\UrlUtils;
use Wikimedia\IPUtils;
use Wikimedia\LightweightObjectStore\ExpirationAwareness;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * The default session provider for MediaWiki which persists sessions using cookies.
 *
 * @since 1.27
 * @ingroup Session
 */
class CookieSessionProvider extends SessionProvider {

	/**
	 * Name of the JWT cookie, when enabled. Ignores $wgCookiePrefix.
	 */
	protected const JWT_COOKIE_NAME = 'sessionJwt';

	/** @var mixed[] */
	protected $params = [];

	/** @var mixed[] */
	protected $cookieOptions = [];

	/**
	 * JWT 'jti' field.
	 * FIXME This is an ugly hack to make the cookie set in setJwtCookie() deterministic within
	 *   a given request. We don't have a mechanism to avoid writing cookies several times per
	 *   request, and rely on WebResponse deduplicating cookies as long as the values are the same.
	 */
	protected ?string $jti = null;

	/**
	 * @param JwtCodec $jwtCodec
	 * @param UrlUtils $urlUtils
	 * @param array $params Keys include:
	 *  - priority: (required) Priority of the returned sessions
	 *  - sessionName: Session cookie name. Doesn't honor 'prefix'. Defaults to
	 *    $wgSessionName, or $wgCookiePrefix . '_session' if that is unset.
	 *  - cookieOptions: Options to pass to WebRequest::setCookie():
	 *    - prefix: Cookie prefix, defaults to $wgCookiePrefix
	 *    - path: Cookie path, defaults to $wgCookiePath
	 *    - domain: Cookie domain, defaults to $wgCookieDomain
	 *    - secure: Cookie secure flag, defaults to $wgCookieSecure
	 *    - httpOnly: Cookie httpOnly flag, defaults to $wgCookieHttpOnly
	 *    - sameSite: Cookie SameSite attribute, defaults to $wgCookieSameSite
	 */
	public function __construct(
		private JwtCodec $jwtCodec,
		private UrlUtils $urlUtils,
		$params = []
	) {
		parent::__construct();

		$params += [
			'cookieOptions' => [],
			// @codeCoverageIgnoreStart
		];
		// @codeCoverageIgnoreEnd

		if ( !isset( $params['priority'] ) ) {
			throw new InvalidArgumentException( __METHOD__ . ': priority must be specified' );
		}
		if ( $params['priority'] < SessionInfo::MIN_PRIORITY ||
			$params['priority'] > SessionInfo::MAX_PRIORITY
		) {
			throw new InvalidArgumentException( __METHOD__ . ': Invalid priority' );
		}

		if ( !is_array( $params['cookieOptions'] ) ) {
			throw new InvalidArgumentException( __METHOD__ . ': cookieOptions must be an array' );
		}

		$this->priority = $params['priority'];
		$this->cookieOptions = $params['cookieOptions'];
		$this->params = $params;
		unset( $this->params['priority'] );
		unset( $this->params['cookieOptions'] );
	}

	protected function postInitSetup() {
		$this->params += [
			'sessionName' =>
				$this->getConfig()->get( MainConfigNames::SessionName )
				?: $this->getConfig()->get( MainConfigNames::CookiePrefix ) . '_session',
		];

		$sameSite = $this->getConfig()->get( MainConfigNames::CookieSameSite );

		// @codeCoverageIgnoreStart
		$this->cookieOptions += [
			// @codeCoverageIgnoreEnd
			'prefix' => $this->getConfig()->get( MainConfigNames::CookiePrefix ),
			'path' => $this->getConfig()->get( MainConfigNames::CookiePath ),
			'domain' => $this->getConfig()->get( MainConfigNames::CookieDomain ),
			'secure' => $this->getConfig()->get( MainConfigNames::CookieSecure )
				|| $this->getConfig()->get( MainConfigNames::ForceHTTPS ),
			'httpOnly' => $this->getConfig()->get( MainConfigNames::CookieHttpOnly ),
			'sameSite' => $sameSite,
		];
	}

	/** @inheritDoc */
	public function provideSessionInfo( WebRequest $request ) {
		$sessionId = $this->getCookie( $request, $this->params['sessionName'], '' );
		$info = [
			'provider' => $this,
			'forceHTTPS' => $this->getCookie( $request, 'forceHTTPS', '', false )
		];
		if ( SessionManager::validateSessionId( $sessionId ) ) {
			$info['id'] = $sessionId;
			$info['persisted'] = true;
		}

		[ $userId, $userName, $token ] = $this->getUserInfoFromCookies( $request );
		if ( $userId !== null ) {
			try {
				$userInfo = UserInfo::newFromId( $userId );
			} catch ( InvalidArgumentException ) {
				return null;
			}

			if ( $userName !== null && $userInfo->getName() !== $userName ) {
				$this->logger->warning(
					'Session "{session}" requested with mismatched UserID and UserName cookies.',
					[
						'session' => $sessionId,
						'mismatch' => [
							'userid' => $userId,
							'cookie_username' => $userName,
							'username' => $userInfo->getName(),
						],
					] );
				return null;
			}

			if ( $token !== null ) {
				if ( !hash_equals( $userInfo->getToken(), $token ) ) {
					$this->logger->warning(
						'Session "{session}" requested with invalid Token cookie.',
						[
							'session' => $sessionId,
							'userid' => $userId,
							'username' => $userInfo->getName(),
						] );
					return null;
				}
				$info['userInfo'] = $userInfo->verified();
				$info['persisted'] = true; // If we have user+token, it should be
			} elseif ( isset( $info['id'] ) ) {
				$info['userInfo'] = $userInfo;
			} else {
				// No point in returning, loadSessionInfoFromStore() will
				// reject it anyway.
				return null;
			}
		} elseif ( isset( $info['id'] ) ) {
			// No UserID cookie, so insist that the session is anonymous.
			// Note: this event occurs for several normal activities:
			// * anon visits Special:UserLogin
			// * anon browsing after seeing Special:UserLogin
			// * anon browsing after edit or preview
			$this->logger->debug(
				'Session "{session}" requested without UserID cookie',
				[
					'session' => $info['id'],
				] );
			$info['userInfo'] = UserInfo::newAnonymous();
		} else {
			// No session ID and no user is the same as an empty session, so
			// there's no point.
			return null;
		}

		$sessionInfo = new SessionInfo( $this->priority, $info );

		if ( $this->useJwtCookie() ) {
			try {
				$this->verifyJwtCookie( $request, $sessionInfo );
			} catch ( JwtException $e ) {
				$this->logger->info( 'JWT validation failed: ' . $e->getNormalizedMessage(), $e->getMessageContext() );
				return null;
			}
		}

		return $sessionInfo;
	}

	/** @inheritDoc */
	public function persistsSessionId() {
		return true;
	}

	/** @inheritDoc */
	public function canChangeUser() {
		return true;
	}

	public function persistSession( SessionBackend $session, WebRequest $request ) {
		$response = $request->response();
		if ( $response->headersSent() ) {
			// Can't do anything now
			$this->logger->debug( __METHOD__ . ': Headers already sent' );
			return;
		}

		$user = $session->getUser();

		$cookies = $this->cookieDataToExport( $user, $session->shouldRememberUser() );
		$sessionData = $this->sessionDataToExport( $user );

		$options = $this->cookieOptions;

		$forceHTTPS = $session->shouldForceHTTPS() || $user->requiresHTTPS();
		if ( $forceHTTPS ) {
			$options['secure'] = $this->getConfig()->get( MainConfigNames::CookieSecure )
				|| $this->getConfig()->get( MainConfigNames::ForceHTTPS );
		}

		$response->setCookie( $this->params['sessionName'], $session->getId(), null,
			[ 'prefix' => '' ] + $options
		);

		foreach ( $cookies as $key => $value ) {
			if ( $value === false ) {
				$response->clearCookie( $key, $options );
			} else {
				$expirationDuration = $this->getLoginCookieExpiration( $key, $session->shouldRememberUser() );
				$expiration = $expirationDuration ? $expirationDuration + ConvertibleTimestamp::time() : null;
				$response->setCookie( $key, (string)$value, $expiration, $options );
			}
		}

		$this->setForceHTTPSCookie( $forceHTTPS, $session, $request );
		$this->setLoggedOutCookie( $session->getLoggedOutTimestamp(), $request );
		if ( $this->useJwtCookie() ) {
			$this->setJwtCookie( $session->getUser(), $request, $session->shouldRememberUser() );
		}

		if ( $sessionData ) {
			$session->addData( $sessionData );
		}
	}

	public function unpersistSession( WebRequest $request ) {
		$response = $request->response();
		if ( $response->headersSent() ) {
			// Can't do anything now
			$this->logger->debug( __METHOD__ . ': Headers already sent' );
			return;
		}

		// This intentionally does not clear the "UserName" cookie,
		// because it powers SessionProvider::suggestLoginUsername.
		// See also self::getExtendedLoginCookies, and self::cookieDataToExport.
		$cookies = [
			'UserID' => false,
			'Token' => false,
		];

		$response->clearCookie(
			$this->params['sessionName'], [ 'prefix' => '' ] + $this->cookieOptions
		);

		foreach ( $cookies as $key => $value ) {
			$response->clearCookie( $key, $this->cookieOptions );
		}

		if ( $this->useJwtCookie() ) {
			$response->clearCookie( self::JWT_COOKIE_NAME, $this->getJwtCookieOptions() );
		}

		$this->setForceHTTPSCookie( false, null, $request );
	}

	/**
	 * Set the "forceHTTPS" cookie, unless $wgForceHTTPS prevents it.
	 *
	 * @param bool $set Whether the cookie should be set or not
	 * @param SessionBackend|null $backend
	 * @param WebRequest $request
	 */
	protected function setForceHTTPSCookie( $set, ?SessionBackend $backend, WebRequest $request ) {
		if ( $this->getConfig()->get( MainConfigNames::ForceHTTPS ) ) {
			// No need to send a cookie if the wiki is always HTTPS (T256095)
			return;
		}
		$response = $request->response();
		if ( $set ) {
			if ( $backend->shouldRememberUser() ) {
				$expirationDuration = $this->getLoginCookieExpiration(
					'forceHTTPS',
					true
				);
				$expiration = $expirationDuration ? $expirationDuration + ConvertibleTimestamp::time() : null;
			} else {
				$expiration = null;
			}
			$response->setCookie( 'forceHTTPS', 'true', $expiration,
				[ 'prefix' => '', 'secure' => false ] + $this->cookieOptions );
		} else {
			$response->clearCookie( 'forceHTTPS',
				[ 'prefix' => '', 'secure' => false ] + $this->cookieOptions );
		}
	}

	/**
	 * @param int $loggedOut timestamp
	 * @param WebRequest $request
	 */
	protected function setLoggedOutCookie( $loggedOut, WebRequest $request ) {
		if ( $loggedOut + 86400 > ConvertibleTimestamp::time() &&
			$loggedOut !== (int)$this->getCookie( $request, 'LoggedOut', $this->cookieOptions['prefix'] )
		) {
			$request->response()->setCookie( 'LoggedOut', (string)$loggedOut, $loggedOut + 86400,
				$this->cookieOptions );
		}
	}

	/** @inheritDoc */
	public function getVaryCookies() {
		$cookies = [
			// Vary on token and session because those are the real authn
			// determiners. UserID and UserName don't matter without those.
			$this->cookieOptions['prefix'] . 'Token',
			$this->cookieOptions['prefix'] . 'LoggedOut',
			$this->params['sessionName'],
			'forceHTTPS',
		];
		if ( $this->useJwtCookie() ) {
			$cookies[] = $this->getJwtCookieOptions()['prefix'] . self::JWT_COOKIE_NAME;
		}
		return $cookies;
	}

	/** @inheritDoc */
	public function suggestLoginUsername( WebRequest $request ) {
		$name = $this->getCookie( $request, 'UserName', $this->cookieOptions['prefix'] );
		if ( $name !== null ) {
			if ( $this->userNameUtils->isTemp( $name ) ) {
				$name = false;
			} else {
				$name = $this->userNameUtils->getCanonical( $name, UserRigorOptions::RIGOR_USABLE );
			}
		}
		return $name === false ? null : $name;
	}

	/**
	 * Fetch the user identity from cookies
	 * @param WebRequest $request
	 * @return array (string|null $id, string|null $username, string|null $token)
	 */
	protected function getUserInfoFromCookies( $request ) {
		$prefix = $this->cookieOptions['prefix'];
		return [
			$this->getCookie( $request, 'UserID', $prefix ),
			$this->getCookie( $request, 'UserName', $prefix ),
			$this->getCookie( $request, 'Token', $prefix ),
		];
	}

	/**
	 * Get a cookie. Contains an auth-specific hack.
	 * @param WebRequest $request
	 * @param string $key
	 * @param string $prefix
	 * @param mixed|null $default
	 * @return mixed
	 */
	protected function getCookie( $request, $key, $prefix, $default = null ) {
		$value = $request->getCookie( $key, $prefix, $default );
		if ( $value === 'deleted' ) {
			// PHP uses this value when deleting cookies. A legitimate cookie will never have
			// this value (usernames start with uppercase, token is longer, other auth cookies
			// are booleans or integers). Seeing this means that in a previous request we told the
			// client to delete the cookie, but it has poor cookie handling. Pretend the cookie is
			// not there to avoid invalidating the session.
			return null;
		}
		return $value;
	}

	/**
	 * Return the data to store in cookies
	 * @param User $user
	 * @param bool $remember
	 * @return array $cookies Set value false to unset the cookie
	 */
	protected function cookieDataToExport( $user, $remember ) {
		if ( $user->isAnon() ) {
			return [
				'UserID' => false,
				'Token' => false,
			];
		} else {
			return [
				'UserID' => $user->getId(),
				'UserName' => $user->getName(),
				'Token' => $remember ? (string)$user->getToken() : false,
			];
		}
	}

	/**
	 * Return extra data to store in the session
	 * @param User $user
	 * @return array
	 */
	protected function sessionDataToExport( $user ) {
		return [];
	}

	/** @inheritDoc */
	public function whyNoSession() {
		return wfMessage( 'sessionprovider-nocookies' );
	}

	/** @inheritDoc */
	public function getRememberUserDuration() {
		return min( $this->getLoginCookieExpiration( 'UserID', true ),
			$this->getLoginCookieExpiration( 'Token', true ) ) ?: null;
	}

	/**
	 * Gets the list of cookies that must be set to the 'remember me' duration,
	 * if $wgExtendedLoginCookieExpiration is in use.
	 *
	 * @return string[] Array of unprefixed cookie keys
	 */
	protected function getExtendedLoginCookies() {
		return [ 'UserID', 'UserName', 'Token' ];
	}

	/**
	 * Returns the lifespan of the login cookies, in seconds. 0 means until the end of the session.
	 *
	 * Cookies that are session-length do not call this function.
	 *
	 * @param string $cookieName
	 * @param bool $shouldRememberUser Whether the user should be remembered
	 *   long-term
	 * @return int Cookie expiration time in seconds; 0 for session cookies
	 */
	protected function getLoginCookieExpiration( $cookieName, $shouldRememberUser ) {
		$extendedCookies = $this->getExtendedLoginCookies();
		$normalExpiration = $this->getConfig()->get( MainConfigNames::CookieExpiration );

		if ( $cookieName === self::JWT_COOKIE_NAME ) {
			return $this->getConfig()->get( MainConfigNames::SessionCookieJwtExpiration );
		} elseif ( $shouldRememberUser && in_array( $cookieName, $extendedCookies, true ) ) {
			$extendedExpiration = $this->getConfig()->get( MainConfigNames::ExtendedLoginCookieExpiration );

			return ( $extendedExpiration !== null ) ? (int)$extendedExpiration : (int)$normalExpiration;
		} else {
			return (int)$normalExpiration;
		}
	}

	/**
	 * Tells whether the provider should emit session data as a JWT cookie, alongside of (in the
	 * future, possibly instead of) the normal session cookies.
	 */
	protected function useJwtCookie(): bool {
		return $this->config->get( MainConfigNames::UseSessionCookieJwt );
	}

	/**
	 * Emit a JWT cookie containing the user ID, token and other information.
	 */
	protected function setJwtCookie(
		User $user,
		WebRequest $request,
		bool $shouldRememberUser
	): void {
		$response = $request->response();
		$expirationDuration = $this->getLoginCookieExpiration( self::JWT_COOKIE_NAME, $shouldRememberUser );
		$expiration = $expirationDuration ? $expirationDuration + ConvertibleTimestamp::time() : null;

		// Do not set JWT cookies for anonymous sessions. Not particularly useful, and makes
		// cookie conflicts on a shared domain more likely.
		if ( IPUtils::isValid( $user->getName() ) ) {
			$response->clearCookie( self::JWT_COOKIE_NAME, $this->getJwtCookieOptions() );
			return;
		}

		$jwtData = $this->getManager()->getJwtData( $user );
		$jwtData = $this->getJwtClaimOverrides( $expirationDuration ) + $jwtData;
		$jwt = $this->jwtCodec->create( $jwtData );
		$response->setCookie( self::JWT_COOKIE_NAME, $jwt, $expiration,
			$this->getJwtCookieOptions() );
	}

	/**
	 * Ensure that the JWT cookie (if it exists) matches the SessionInfo. The SessionInfo must
	 * contain a non-null UserInfo (anonymous UserInfo is fine).
	 * A missing JWT cookie is always treated as success.
	 *
	 * If necessary, marks the session to be refreshed.
	 *
	 * @throws JwtException on error
	 */
	protected function verifyJwtCookie( WebRequest $request, SessionInfo &$sessionInfo ): void {
		$jwt = $this->getCookie( $request, self::JWT_COOKIE_NAME, $this->getJwtCookieOptions()['prefix'] );
		if ( $jwt === null ) {
			// This is normal: the JWT cookie has a shorter lifetime and will expire before the other cookies.
			if ( $sessionInfo->wasPersisted() ) {
				// Make sure it's re-persisted so the JWT cookie is updated.
				$sessionInfo = new SessionInfo( $sessionInfo->getPriority(), [
					'needsRefresh' => true,
					'copyFrom' => $sessionInfo,
				] );
			}
			return;
		}

		$data = $this->jwtCodec->parse( $jwt );
		$expectedUser = ( $sessionInfo->getUserInfo() ?? UserInfo::newAnonymous() )->getUser();
		$this->manager->validateJwtSubject( $data, $expectedUser );

		[ 'iss' => $issuer, 'sxp' => $softExpiry, 'exp' => $hardExpiry ] = $data + [ 'exp' => PHP_INT_MAX ];
		[ 'iss' => $expectedIssuer ] = $this->getJwtClaimOverrides( 0 );
		if ( $issuer !== $expectedIssuer ) {
			throw new JwtException( 'JWT error: wrong issuer', [
				'expected_issuer' => $expectedIssuer,
				'issuer' => $issuer,
			] );
		}

		if ( $hardExpiry < ConvertibleTimestamp::time() ) {
			throw new JwtException( 'JWT error: hard-expired', [
				'jti' => $data['jti'],
				'expiry' => $hardExpiry,
				'expired_by' => ConvertibleTimestamp::time() - $hardExpiry,
			] + $request->getSecurityLogContext( $expectedUser ) );
		}

		// Valid JWT. We could use this to make the UserInfo in the SessionInfo verified if it
		// isn't already, or to make a SessionInfo in the first place if the other cookies weren't
		// sufficient for a valid session, but for now we avoid using the JWT to make a session
		// valid if it wouldn't be without it.

		// Refresh the JWT cookie if it's about to expire. We can't rely on the normal session refresh
		// mechanism because the expiry time is different.
		$expirationDuration = $this->getLoginCookieExpiration( self::JWT_COOKIE_NAME, $sessionInfo->wasRemembered() );
		if ( $sessionInfo->wasPersisted()
			&& $softExpiry < ConvertibleTimestamp::time() + 0.75 * $expirationDuration
		) {
			$sessionInfo = new SessionInfo( $sessionInfo->getPriority(), [
				'needsRefresh' => true,
				'copyFrom' => $sessionInfo,
			] );
			if ( $softExpiry < ConvertibleTimestamp::time() - ExpirationAwareness::TTL_MINUTE ) {
				// Already expired (we add a one-minute fudge factor for slow network etc).
				// This shouldn't happen since the cookie expiry and the JWT expiry are synced,
				// but some clients might not honor cookie expiry; we want to know about those.
				LoggerFactory::getInstance( 'session-sampled' )->warning( 'Soft-expired JWT cookie', [
					'jti' => $data['jti'],
					'expiry' => $softExpiry,
					'expired_by' => ConvertibleTimestamp::time() - $softExpiry,
					'subject' => $data['sub'],
				] + $request->getSecurityLogContext( $expectedUser ) );
			}
		}
	}

	/**
	 * Helper method to make it easy for subclasses to alter claims.
	 * @param int $expirationDuration Session lifetime in seconds.
	 */
	protected function getJwtClaimOverrides( int $expirationDuration ): array {
		$this->jti ??= base64_encode( random_bytes( 16 ) );
		return [
			'iss' => $this->urlUtils->getCanonicalServer(),
			// FIXME Omit 'exp' for now. In theory we could just set it to something larger than
			//   the cookie expiration, but want to be careful about clients which don't honor
			//   cookie expiries and the possibility that JWTs with an invalid 'exp' field will be
			//   hard-rejected at the edge.
			// 'exp' => ConvertibleTimestamp::time() + $expirationDuration + ExpirationAwareness::TTL_MINUTE,
			'sxp' => ConvertibleTimestamp::time() + $expirationDuration,
			'jti' => $this->jti,
		];
	}

	/**
	 * Helper method to make it easy for subclasses to alter JWT cookie options (as multiple wikis
	 * are expected to share the same cookie for some providers).
	 */
	protected function getJwtCookieOptions(): array {
		return [ 'prefix' => '' ] + $this->cookieOptions;
	}

}
