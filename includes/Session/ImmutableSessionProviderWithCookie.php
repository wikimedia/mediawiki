<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Session;

use InvalidArgumentException;
use MediaWiki\MainConfigNames;
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
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getVaryCookies() {
		if ( $this->sessionCookieName === null ) {
			return [];
		}

		$prefix = $this->sessionCookieOptions['prefix'] ??
			$this->getConfig()->get( MainConfigNames::CookiePrefix );
		return [ $prefix . $this->sessionCookieName ];
	}

	/** @inheritDoc */
	public function whyNoSession() {
		return wfMessage( 'sessionprovider-nocookies' );
	}
}
