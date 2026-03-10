<?php

/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Session;

use MediaWiki\Config\Config;
use MediaWiki\Json\JwtCodec;
use MediaWiki\Json\JwtException;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Request\WebRequest;
use MediaWiki\User\CentralId\CentralIdLookup;
use MediaWiki\User\User;
use MediaWiki\Utils\UrlUtils;
use Wikimedia\IPUtils;
use Wikimedia\LightweightObjectStore\ExpirationAwareness;
use Wikimedia\Timestamp\ConvertibleTimestamp;

class JwtSessionCookieHelper {

	/**
	 * Name of the JWT cookie, when enabled. Ignores $wgCookiePrefix.
	 */
	public const JWT_COOKIE_NAME = 'sessionJwt';

	/**
	 * JWT 'jti' field.
	 * FIXME This is an ugly hack to make the cookie set in setJwtCookie() deterministic within
	 *   a given request. We don't have a mechanism to avoid writing cookies several times per
	 *   request, and rely on WebResponse deduplicating cookies as long as the values are the same.
	 */
	protected ?string $jti = null;

	/**
	 * @param UrlUtils $urlUtils
	 * @param Config $config
	 * @param JwtCodec $jwtCodec
	 * @param SessionManager $sessionManager
	 * @param string $sessionProviderType
	 */
	public function __construct(
		private readonly UrlUtils $urlUtils,
		private readonly Config $config,
		protected JwtCodec $jwtCodec,
		private readonly SessionManager $sessionManager,
		private readonly string $sessionProviderType
	) {
	}

	/**
	 * Tells whether the provider should emit session data as a JWT cookie, alongside of (in the
	 * future, possibly instead of) the normal session cookies.
	 */
	public function useJwtCookie(): bool {
		return $this->config->get( MainConfigNames::UseSessionCookieJwt );
	}

	/**
	 * Emit a JWT cookie containing the user ID, token and other information.
	 */
	public function setJwtCookie(
		User $user,
		WebRequest $request,
		array $jwtCookieOptions,
		array $jwtClaimOverrides
	): void {
		$response = $request->response();
		$expirationDuration = $this->getJwtCookieSessionExpiration();
		$expiration = $expirationDuration ? $expirationDuration + ConvertibleTimestamp::time() : null;

		// Do not set JWT cookies for anonymous sessions. Not particularly useful, and makes
		// cookie conflicts on a shared domain more likely.
		if ( IPUtils::isValid( $user->getName() ) ) {
			$response->clearCookie( self::JWT_COOKIE_NAME, $jwtCookieOptions );
			return;
		}

		$jwtData = $this->sessionManager->getJwtData( $user );
		$jwtData = $jwtClaimOverrides + $jwtData;
		$jwt = $this->jwtCodec->create( $jwtData );
		$response->setCookie( self::JWT_COOKIE_NAME, $jwt, $expiration,
			$jwtCookieOptions );
	}

	/**
	 * @throws JwtException
	 */
	protected function getUserFromJwtData( array $jwtData ): User {
		$subUser = substr( $jwtData['sub'], 3 );
		// To avoid duplicating the logic in SessionManager::validateJwtSubject(),
		// just guess the user and then validate it (FIXME improve this)
		if ( $subUser === SessionManager::JWT_SUB_ANON ) {
			$user = new User();
		} else {
			$centralId = array_last( explode( ':', $subUser ) );
			// TODO: Dependency inject this appropriately.
			$userName = MediaWikiServices::getInstance()->getCentralIdLookup()
				->nameFromCentralId( (int)$centralId, CentralIdLookup::AUDIENCE_RAW );
			// No need to directly handle $userName === null, validateJwtSubject() will throw
			$user = $userName === null ? new User() : User::newFromName( $userName );
		}
		// TODO: Dependency inject this appropriately.
		SessionManager::singleton()->validateJwtSubject( $jwtData, $user );
		return $user;
	}

	/**
	 * Ensure that the JWT cookie (if it exists) matches the SessionInfo.
	 * A missing JWT cookie is treated as a success.
	 *
	 * If the SessionInfo has a UserInfo, verifies that the JWT cookie contains the same user.
	 * If the SessionInfo does not have a UserInfo, an unverified UserInfo will be added to it
	 * based on the JWT data (so the verification will happen in
	 * SessionManager::loadSessionInfoFromStore()).
	 *
	 * Marks the session to be refreshed if the JWT cookie is missing (but based on the SessionInfo
	 * should be there) or is near expiry.
	 *
	 * @throws JwtException on error
	 */
	public function verifyJwtCookie(
		WebRequest $request,
		SessionInfo &$sessionInfo,
		array $jwtCookieOptions,
		array $jwtClaimOverrides
	): void {
		$jwt = $this->getCookie( $request, self::JWT_COOKIE_NAME, $jwtCookieOptions['prefix'] );

		if ( $jwt === null ) {
			// This can be a valid-authenticated session; the JWT cookie has a shorter lifetime and
			// will expire before the other cookies. If so, re-persist so the JWT cookie is updated.
			// But do not re-persist anonymous sessions, since we don't use JWT cookies for those.
			//
			// Some providers don't know the user identity at this point (as we haven't loaded the
			// session data from the store yet). We'll assume that providers which cannot change
			// users only deal with authenticated users, so it's safe to refresh those, and safe to
			// ignore the rest. This is not 100% reliable but close enough to cover all real-world
			// use cases in which we need JWT session cookies.
			$isAnonUser = $sessionInfo->getUserInfo()?->isAnon();
			$providerCanChangeUser = $sessionInfo->getProvider()?->canChangeUser();
			if ( ( $isAnonUser === false || $providerCanChangeUser === false ) && $sessionInfo->wasPersisted() ) {
				$sessionInfo = new SessionInfo( $sessionInfo->getPriority(), [
					'needsRefresh' => true,
					'copyFrom' => $sessionInfo,
				] );
			}
			return;
		}

		$data = $this->jwtCodec->parse( $jwt );
		$expectedUser = $sessionInfo->getUserInfo()?->getUser();
		// note this is not the same as $expectedUser->isAnon() - we might be dealing with a
		// centrally-existing, locally-not-existing user
		$expectedUserIsAnon = $sessionInfo->getUserInfo()?->isAnon() ?? false;
		if ( $expectedUserIsAnon ) {
			// Anonymous sessions should not have a JWT cookie; we refresh the session to delete the
			// cookie. We do not invalidate the session; there is no security risk with an anonymous
			// session, cookies can easily bleed over from other sessions, and anonymous sessions
			// are required for logging in, so breaking them might trap API clients with weird
			// cookie handling in a doom loop.
			try {
				if ( $expectedUser !== null ) {
					$this->sessionManager->validateJwtSubject( $data, $expectedUser );
				}
			} catch ( JwtException $e ) {
				LoggerFactory::getInstance( 'session-sampled' )->warning( 'Non-anon JWT cookie for anon session', [
					'jwt_error' => $e->getNormalizedMessage(),
					'jti' => $data['jti'],
					'subject' => $data['sub'],
					'session_provider_type' => $this->sessionProviderType,
				] + $e->getMessageContext() + $request->getSecurityLogContext() );
				$sessionInfo = new SessionInfo( $sessionInfo->getPriority(), [
					'needsRefresh' => true,
					'copyFrom' => $sessionInfo,
				] );
				return;
			}
		} elseif ( $expectedUser ) {
			$this->sessionManager->validateJwtSubject( $data, $expectedUser );
		} else {
			$sessionInfo = new SessionInfo( $sessionInfo->getPriority(), [
				'userInfo' => UserInfo::newFromUser( $this->getUserFromJwtData( $data ), false ),
				'copyFrom' => $sessionInfo,
			] );
		}

		[ 'iss' => $issuer, 'sxp' => $softExpiry, 'exp' => $hardExpiry ] = $data + [ 'exp' => PHP_INT_MAX ];
		[ 'iss' => $expectedIssuer ] = $jwtClaimOverrides;
		if ( $issuer !== $expectedIssuer ) {
			throw new JwtException( 'JWT error: wrong issuer', [
				'expected_issuer' => $expectedIssuer,
				'issuer' => $issuer,
				'session_provider_type' => $this->sessionProviderType,
			] );
		}

		if ( $hardExpiry < ConvertibleTimestamp::time() ) {
			throw new JwtException( 'JWT error: hard-expired', [
					'jti' => $data['jti'],
					'expiry' => $hardExpiry,
					'expired_by' => ConvertibleTimestamp::time() - $hardExpiry,
					'session_provider_type' => $this->sessionProviderType,
				] + $request->getSecurityLogContext( $expectedUser ) );
		}

		// Valid JWT. We could use this to make the UserInfo in the SessionInfo verified if it
		// isn't already, or to make a SessionInfo in the first place if the other cookies weren't
		// sufficient for a valid session, but for now we avoid using the JWT to make a session
		// valid if it wouldn't be without it.

		// Refresh the JWT cookie if it's about to expire. We can't rely on the normal session refresh
		// mechanism because the expiry time is different.
		$expirationDuration = $this->getJwtCookieSessionExpiration();
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
						'session_provider_type' => $this->sessionProviderType,
					] + $request->getSecurityLogContext( $expectedUser ) );
			}
		}
	}

	/**
	 * Get a cookie. Contains an auth-specific hack.
	 * @param WebRequest $request
	 * @param string $key
	 * @param string $prefix
	 * @param mixed|null $default
	 * @return mixed
	 */
	private function getCookie( $request, $key, $prefix, $default = null ) {
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
	 * Expiration duration for the JWT session cookie.
	 *
	 * @return mixed
	 */
	public function getJwtCookieSessionExpiration() {
		return $this->config->get( MainConfigNames::SessionCookieJwtExpiration );
	}

	public function getJwtIssuer(): string {
		return $this->config->get( MainConfigNames::JwtSessionCookieIssuer ) ??
			$this->urlUtils->getCanonicalServer();
	}

	/**
	 * Helper method to make it easy for subclasses to alter claims.
	 *
	 * @param int $expirationDuration Session lifetime in seconds.
	 * @return array
	 */
	public function getJwtClaimOverrides( int $expirationDuration ): array {
		$this->jti ??= base64_encode( random_bytes( 16 ) );
		return [
			// FIXME Omit 'exp' for now. In theory we could just set it to something larger than
			//   the cookie expiration, but want to be careful about clients which don't honor
			//   cookie expiries and the possibility that JWTs with an invalid 'exp' field will be
			//   hard-rejected at the edge.
			// 'exp' => ConvertibleTimestamp::time() + $expirationDuration + ExpirationAwareness::TTL_MINUTE,
			'sxp' => ConvertibleTimestamp::time() + $expirationDuration,
			'jti' => $this->jti,
		];
	}
}
