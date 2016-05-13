<?php
/**
 * MediaWiki session provider base class
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

use WebRequest;

/**
 * An ImmutableSessionProviderWithCookie doesn't persist the user, but
 * optionally can use a cookie to support multiple IDs per session.
 *
 * As mentioned in the documentation for SessionProvider, many methods that are
 * technically "cannot persist ID" could be turned into "can persist ID but
 * not changing User" using a session cookie. This class implements such an
 * optional session cookie.
 *
 * @ingroup Session
 * @since 1.27
 */
abstract class ImmutableSessionProviderWithCookie extends SessionProvider {

	/** @var string|null */
	protected $sessionCookieName = null;
	protected $sessionCookieOptions = [];

	/**
	 * @param array $params Keys include:
	 *  - sessionCookieName: Session cookie name, if multiple sessions per
	 *    client are to be supported.
	 *  - sessionCookieOptions: Options to pass to WebResponse::setCookie().
	 */
	public function __construct( $params = [] ) {
		parent::__construct();

		if ( isset( $params['sessionCookieName'] ) ) {
			if ( !is_string( $params['sessionCookieName'] ) ) {
				throw new \InvalidArgumentException( 'sessionCookieName must be a string' );
			}
			$this->sessionCookieName = $params['sessionCookieName'];
		}
		if ( isset( $params['sessionCookieOptions'] ) ) {
			if ( !is_array( $params['sessionCookieOptions'] ) ) {
				throw new \InvalidArgumentException( 'sessionCookieOptions must be an array' );
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

		$prefix = isset( $this->sessionCookieOptions['prefix'] )
			? $this->sessionCookieOptions['prefix']
			: $this->config->get( 'CookiePrefix' );
		$id = $request->getCookie( $this->sessionCookieName, $prefix );
		return SessionManager::validateSessionId( $id ) ? $id : null;
	}

	public function persistsSessionId() {
		return $this->sessionCookieName !== null;
	}

	public function canChangeUser() {
		return false;
	}

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
			$response->setCookie( 'forceHTTPS', 'true', null,
				[ 'prefix' => '', 'secure' => false ] + $options );
			$options['secure'] = true;
		}

		$response->setCookie( $this->sessionCookieName, $session->getId(), null, $options );
	}

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

	public function getVaryCookies() {
		if ( $this->sessionCookieName === null ) {
			return [];
		}

		$prefix = isset( $this->sessionCookieOptions['prefix'] )
			? $this->sessionCookieOptions['prefix']
			: $this->config->get( 'CookiePrefix' );
		return [ $prefix . $this->sessionCookieName ];
	}

	public function whyNoSession() {
		return wfMessage( 'sessionprovider-nocookies' );
	}
}
