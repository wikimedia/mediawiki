<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Session;

use InvalidArgumentException;
use MediaWiki\Json\JwtException;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Request\WebRequest;

/**
 * An ImmutableSessionProviderWithCookie doesn't persist the user, but
 * optionally can use a cookie to support multiple IDs per session.
 *
 * As mentioned in the documentation for SessionProvider, many methods that are
 * technically "cannot persist ID" could be turned into "can persist ID but
 * not changing User" using a session cookie. This class implements such an
 * optional session cookie.
 *
 * @stable to extend
 * @ingroup Session
 * @since 1.27
 */
abstract class ImmutableSessionProviderWithCookie extends SessionProvider {

	/** @var string|null */
	protected $sessionCookieName = null;
	/** @var mixed[] */
	protected $sessionCookieOptions = [];
	protected ?JwtSessionCookieHelper $jwtSessionCookieHelper = null;

	/**
	 * @stable to call
	 * @param array $params Keys include:
	 *  - sessionCookieName: Session cookie name, if multiple sessions per
	 *    client are to be supported.
	 *  - sessionCookieOptions: Options to pass to WebResponse::setCookie().
	 */
	public function __construct( $params = [] ) {
		parent::__construct();

		if ( isset( $params['sessionCookieName'] ) ) {
			if ( !is_string( $params['sessionCookieName'] ) ) {
				throw new InvalidArgumentException( 'sessionCookieName must be a string' );
			}
			$this->sessionCookieName = $params['sessionCookieName'];
		}
		if ( isset( $params['sessionCookieOptions'] ) ) {
			if ( !is_array( $params['sessionCookieOptions'] ) ) {
				throw new InvalidArgumentException( 'sessionCookieOptions must be an array' );
			}
			$this->sessionCookieOptions = $params['sessionCookieOptions'];
		}
	}

	private function createJwtSessionCookieHelper(): JwtSessionCookieHelper {
		$services = MediaWikiServices::getInstance();

		return new JwtSessionCookieHelper(
			$services->getUrlUtils(),
			$this->getConfig(),
			$services->getJwtCodec(),
			$this->manager,
			get_class( $this ),
			$services->getCentralIdLookup(),
			$services->getUserFactory(),
		);
	}

	protected function postInitSetup() {
		$this->jwtSessionCookieHelper = $this->createJwtSessionCookieHelper();
	}

	/**
	 * Get the session ID from the cookie, if any.
	 *
	 * Only call this if $this->sessionCookieName !== null. If
	 * sessionCookieName is null, do some logic (probably involving a call to
	 * $this->hashToSessionId()) to create the single session ID corresponding
	 * to this WebRequest instead of calling this method.
	 *
	 * @param WebRequest $request
	 * @return string|null
	 */
	protected function getSessionIdFromCookie( WebRequest $request ) {
		if ( $this->sessionCookieName === null ) {
			throw new \BadMethodCallException(
				__METHOD__ . ' may not be called when $this->sessionCookieName === null'
			);
		}

		$prefix = $this->sessionCookieOptions['prefix']
			?? $this->getConfig()->get( MainConfigNames::CookiePrefix );
		$id = $request->getCookie( $this->sessionCookieName, $prefix );
		return SessionManager::validateSessionId( $id ) ? $id : null;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function persistsSessionId() {
		return $this->sessionCookieName !== null;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function canChangeUser() {
		return false;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function persistSession( SessionBackend $session, WebRequest $request ) {
		if ( $this->sessionCookieName === null ) {
			return;
		}

		$response = $request->response();
		if ( $response->headersSent() ) {
			// Can't do anything now
			$this->logger->debug( __METHOD__ . ': Headers already sent' );
			return;
		}

		$options = $this->sessionCookieOptions;
		if ( $session->shouldForceHTTPS() || $session->getUser()->requiresHTTPS() ) {
			// Send a cookie unless $wgForceHTTPS is set (T256095)
			if ( !$this->getConfig()->get( MainConfigNames::ForceHTTPS ) ) {
				$response->setCookie( 'forceHTTPS', 'true', null,
					[ 'prefix' => '', 'secure' => false ] + $options );
			}
			$options['secure'] = true;
		}

		$response->setCookie( $this->sessionCookieName, $session->getId(), null, $options );
		if ( $this->useJwtCookie() ) {
			$this->jwtSessionCookieHelper->setJwtCookie(
				$session->getUser(),
				$request,
				$this->getJwtCookieOptions(),
				$this->getJwtClaimOverrides( $this->jwtSessionCookieHelper->getJwtCookieSessionExpiration() )
			);
		}
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function unpersistSession( WebRequest $request ) {
		if ( $this->sessionCookieName === null ) {
			return;
		}

		$response = $request->response();
		if ( $response->headersSent() ) {
			// Can't do anything now
			$this->logger->debug( __METHOD__ . ': Headers already sent' );
			return;
		}

		$response->clearCookie( $this->sessionCookieName, $this->sessionCookieOptions );

		$response = $request->response();
		if ( $this->useJwtCookie() ) {
			$response->clearCookie( $this->jwtSessionCookieHelper::JWT_COOKIE_NAME, $this->getJwtCookieOptions() );
		}
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getVaryCookies() {
		$cookies = [];

		$prefix = $this->sessionCookieOptions['prefix'] ??
			$this->getConfig()->get( MainConfigNames::CookiePrefix );
		if ( $this->useJwtCookie() ) {
			$cookies[] = $this->getJwtCookieOptions()['prefix'] . $this->jwtSessionCookieHelper::JWT_COOKIE_NAME;
		}

		if ( $this->sessionCookieName !== null ) {
			$cookies[] = $prefix . $this->sessionCookieName;
		}

		return $cookies;
	}

	/** @inheritDoc */
	public function whyNoSession() {
		return wfMessage( 'sessionprovider-nocookies' );
	}

	/**
	 * Helper method to make it easy for subclasses to alter JWT cookie options (as multiple wikis
	 * are expected to share the same cookie for some providers).
	 */
	protected function getJwtCookieOptions(): array {
		return [ 'prefix' => '' ] + $this->sessionCookieOptions;
	}

	/**
	 * @see JwtSessionCookieHelper::getJwtClaimOverrides()
	 *
	 * @param int $expirationDuration Session lifetime in seconds.
	 * @return array
	 */
	protected function getJwtClaimOverrides( int $expirationDuration ): array {
		return array_merge( [
			'iss' => $this->jwtSessionCookieHelper->getJwtIssuer(),
		], $this->jwtSessionCookieHelper->getJwtClaimOverrides( $expirationDuration ) );
	}

	/**
	 * Override this method to return true if you want the provider
	 * to use a JWT cookie. If you override this method, you'll need
	 * to verify the JWT cookie by calling verifyJwtCookie() at the
	 * end of the provideSessionInfo() and explicitly handle any thrown
	 * exception(s).
	 *
	 * @see verifyJwtCookie
	 * @see SessionProvider::provideSessionInfo()
	 * @return bool
	 */
	protected function useJwtCookie(): bool {
		return false;
	}

	/**
	 * When the JWT nears expiry, we'll refresh and re-persist it to
	 * update the token.
	 *
	 * @throws JwtException Throws an error if the JWT issuer doesn't match
	 * or if the JWT token has expired.
	 */
	protected function verifyJwtCookie(
		WebRequest $request,
		SessionInfo &$sessionInfo,
		array $jwtCookieOptions,
		array $jwtClaimOverrides
	): void {
		$this->jwtSessionCookieHelper->verifyJwtCookie(
			$request, $sessionInfo, $jwtCookieOptions, $jwtClaimOverrides
		);
	}
}
