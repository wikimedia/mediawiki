<?php
/**
 * MediaWiki cookie-based session provider interface
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

use Config;
use User;
use WebRequest;

/**
 * A CookieSessionProvider persists sessions using cookies
 *
 * @ingroup Session
 * @since 1.27
 */
class CookieSessionProvider extends SessionProvider {

	protected $params = [];
	protected $cookieOptions = [];

	/**
	 * @param array $params Keys include:
	 *  - priority: (required) Priority of the returned sessions
	 *  - callUserSetCookiesHook: Whether to call the deprecated hook
	 *  - sessionName: Session cookie name. Doesn't honor 'prefix'. Defaults to
	 *    $wgSessionName, or $wgCookiePrefix . '_session' if that is unset.
	 *  - cookieOptions: Options to pass to WebRequest::setCookie():
	 *    - prefix: Cookie prefix, defaults to $wgCookiePrefix
	 *    - path: Cookie path, defaults to $wgCookiePath
	 *    - domain: Cookie domain, defaults to $wgCookieDomain
	 *    - secure: Cookie secure flag, defaults to $wgCookieSecure
	 *    - httpOnly: Cookie httpOnly flag, defaults to $wgCookieHttpOnly
	 */
	public function __construct( $params = [] ) {
		parent::__construct();

		$params += [
			'cookieOptions' => [],
			// @codeCoverageIgnoreStart
		];
		// @codeCoverageIgnoreEnd

		if ( !isset( $params['priority'] ) ) {
			throw new \InvalidArgumentException( __METHOD__ . ': priority must be specified' );
		}
		if ( $params['priority'] < SessionInfo::MIN_PRIORITY ||
			$params['priority'] > SessionInfo::MAX_PRIORITY
		) {
			throw new \InvalidArgumentException( __METHOD__ . ': Invalid priority' );
		}

		if ( !is_array( $params['cookieOptions'] ) ) {
			throw new \InvalidArgumentException( __METHOD__ . ': cookieOptions must be an array' );
		}

		$this->priority = $params['priority'];
		$this->cookieOptions = $params['cookieOptions'];
		$this->params = $params;
		unset( $this->params['priority'] );
		unset( $this->params['cookieOptions'] );
	}

	public function setConfig( Config $config ) {
		parent::setConfig( $config );

		// @codeCoverageIgnoreStart
		$this->params += [
			// @codeCoverageIgnoreEnd
			'callUserSetCookiesHook' => false,
			'sessionName' =>
				$config->get( 'SessionName' ) ?: $config->get( 'CookiePrefix' ) . '_session',
		];

		// @codeCoverageIgnoreStart
		$this->cookieOptions += [
			// @codeCoverageIgnoreEnd
			'prefix' => $config->get( 'CookiePrefix' ),
			'path' => $config->get( 'CookiePath' ),
			'domain' => $config->get( 'CookieDomain' ),
			'secure' => $config->get( 'CookieSecure' ),
			'httpOnly' => $config->get( 'CookieHttpOnly' ),
		];
	}

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

		list( $userId, $userName, $token ) = $this->getUserInfoFromCookies( $request );
		if ( $userId !== null ) {
			try {
				$userInfo = UserInfo::newFromId( $userId );
			} catch ( \InvalidArgumentException $ex ) {
				return null;
			}

			// Sanity check
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

		return new SessionInfo( $this->priority, $info );
	}

	public function persistsSessionId() {
		return true;
	}

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

		// Legacy hook
		if ( $this->params['callUserSetCookiesHook'] && !$user->isAnon() ) {
			\Hooks::run( 'UserSetCookies', [ $user, &$sessionData, &$cookies ] );
		}

		$options = $this->cookieOptions;

		$forceHTTPS = $session->shouldForceHTTPS() || $user->requiresHTTPS();
		if ( $forceHTTPS ) {
			// Don't set the secure flag if the request came in
			// over "http", for backwards compat.
			// @todo Break that backwards compat properly.
			$options['secure'] = $this->config->get( 'CookieSecure' );
		}

		$response->setCookie( $this->params['sessionName'], $session->getId(), null,
			[ 'prefix' => '' ] + $options
		);

		foreach ( $cookies as $key => $value ) {
			if ( $value === false ) {
				$response->clearCookie( $key, $options );
			} else {
				$expirationDuration = $this->getLoginCookieExpiration( $key );
				$expiration = $expirationDuration ? $expirationDuration + time() : null;
				$response->setCookie( $key, (string)$value, $expiration, $options );
			}
		}

		$this->setForceHTTPSCookie( $forceHTTPS, $session, $request );
		$this->setLoggedOutCookie( $session->getLoggedOutTimestamp(), $request );

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

		$this->setForceHTTPSCookie( false, null, $request );
	}

	/**
	 * Set the "forceHTTPS" cookie
	 * @param bool $set Whether the cookie should be set or not
	 * @param SessionBackend|null $backend
	 * @param WebRequest $request
	 */
	protected function setForceHTTPSCookie(
		$set, SessionBackend $backend = null, WebRequest $request
	) {
		$response = $request->response();
		if ( $set ) {
			if ( $backend->shouldRememberUser() ) {
				$expirationDuration = $this->getLoginCookieExpiration( 'forceHTTPS' );
				$expiration = $expirationDuration ? $expirationDuration + time() : null;
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
	 * Set the "logged out" cookie
	 * @param int $loggedOut timestamp
	 * @param WebRequest $request
	 */
	protected function setLoggedOutCookie( $loggedOut, WebRequest $request ) {
		if ( $loggedOut + 86400 > time() &&
			$loggedOut !== (int)$this->getCookie( $request, 'LoggedOut', $this->cookieOptions['prefix'] )
		) {
			$request->response()->setCookie( 'LoggedOut', $loggedOut, $loggedOut + 86400,
				$this->cookieOptions );
		}
	}

	public function getVaryCookies() {
		return [
			// Vary on token and session because those are the real authn
			// determiners. UserID and UserName don't matter without those.
			$this->cookieOptions['prefix'] . 'Token',
			$this->cookieOptions['prefix'] . 'LoggedOut',
			$this->params['sessionName'],
			'forceHTTPS',
		];
	}

	public function suggestLoginUsername( WebRequest $request ) {
		 $name = $this->getCookie( $request, 'UserName', $this->cookieOptions['prefix'] );
		 if ( $name !== null ) {
			 $name = User::getCanonicalName( $name, 'usable' );
		 }
		 return $name === false ? null : $name;
	}

	/**
	 * Fetch the user identity from cookies
	 * @param \WebRequest $request
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
	 * @param \WebRequest $request
	 * @param string $key
	 * @param string $prefix
	 * @param mixed $default
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
	 * @return array $session
	 */
	protected function sessionDataToExport( $user ) {
		// If we're calling the legacy hook, we should populate $session
		// like User::setCookies() did.
		if ( !$user->isAnon() && $this->params['callUserSetCookiesHook'] ) {
			return [
				'wsUserID' => $user->getId(),
				'wsToken' => $user->getToken(),
				'wsUserName' => $user->getName(),
			];
		}

		return [];
	}

	public function whyNoSession() {
		return wfMessage( 'sessionprovider-nocookies' );
	}

	public function getRememberUserDuration() {
		return min( $this->getLoginCookieExpiration( 'UserID' ),
			$this->getLoginCookieExpiration( 'Token' ) ) ?: null;
	}

	/**
	 * Returns the lifespan of the login cookies, in seconds. 0 means until the end of the session.
	 * @param string $cookieName
	 * @return int Cookie expiration time in seconds; 0 for session cookies
	 */
	protected function getLoginCookieExpiration( $cookieName ) {
		$normalExpiration = $this->config->get( 'CookieExpiration' );
		$extendedExpiration = $this->config->get( 'ExtendedLoginCookieExpiration' );
		$extendedCookies = $this->config->get( 'ExtendedLoginCookies' );

		if ( !in_array( $cookieName, $extendedCookies, true ) ) {
			return (int)$normalExpiration;
		}
		return ( $extendedExpiration !== null ) ? (int)$extendedExpiration : (int)$normalExpiration;
	}
}
