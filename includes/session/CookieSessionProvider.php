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

	protected $params = array();
	protected $cookieOptions = array();

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
	public function __construct( $params = array() ) {
		parent::__construct();

		$params += array(
			'cookieOptions' => array(),
			// @codeCoverageIgnoreStart
		);
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
		$this->params += array(
			// @codeCoverageIgnoreEnd
			'callUserSetCookiesHook' => false,
			'sessionName' =>
				$config->get( 'SessionName' ) ?: $config->get( 'CookiePrefix' ) . '_session',
		);

		// @codeCoverageIgnoreStart
		$this->cookieOptions += array(
			// @codeCoverageIgnoreEnd
			'prefix' => $config->get( 'CookiePrefix' ),
			'path' => $config->get( 'CookiePath' ),
			'domain' => $config->get( 'CookieDomain' ),
			'secure' => $config->get( 'CookieSecure' ),
			'httpOnly' => $config->get( 'CookieHttpOnly' ),
		);
	}

	public function provideSessionInfo( WebRequest $request ) {
		$info = array(
			'id' => $this->getCookie( $request, $this->params['sessionName'], '' )
		);
		if ( !SessionManager::validateSessionId( $info['id'] ) ) {
			unset( $info['id'] );
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
				return null;
			}

			if ( $token !== null ) {
				if ( !hash_equals( $userInfo->getToken(), $token ) ) {
					return null;
				}
				$info['userInfo'] = $userInfo->verified();
			} elseif ( isset( $info['id'] ) ) { // No point if no session ID
				$info['userInfo'] = $userInfo;
			}
		}

		if ( !$info ) {
			return null;
		}

		$info += array(
			'provider' => $this,
			'persisted' => isset( $info['id'] ),
			'forceHTTPS' => $this->getCookie( $request, 'forceHTTPS', '', false )
		);

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
			\Hooks::run( 'UserSetCookies', array( $user, &$sessionData, &$cookies ) );
		}

		$options = $this->cookieOptions;

		$forceHTTPS = $session->shouldForceHTTPS() || $user->requiresHTTPS();
		if ( $forceHTTPS ) {
			$options['secure'] = true;
		}

		$response->setCookie( $this->params['sessionName'], $session->getId(), null,
			array( 'prefix' => '' ) + $options
		);

		$extendedCookies = $this->config->get( 'ExtendedLoginCookies' );
		$extendedExpiry = $this->config->get( 'ExtendedLoginCookieExpiration' );

		foreach ( $cookies as $key => $value ) {
			if ( $value === false ) {
				$response->clearCookie( $key, $options );
			} else {
				if ( $extendedExpiry !== null && in_array( $key, $extendedCookies ) ) {
					$expiry = time() + (int)$extendedExpiry;
				} else {
					$expiry = 0; // Default cookie expiration
				}
				$response->setCookie( $key, (string)$value, $expiry, $options );
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

		$cookies = array(
			'UserID' => false,
			'Token' => false,
		);

		$response->clearCookie(
			$this->params['sessionName'], array( 'prefix' => '' ) + $this->cookieOptions
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
			$response->setCookie( 'forceHTTPS', 'true', $backend->shouldRememberUser() ? 0 : null,
				array( 'prefix' => '', 'secure' => false ) + $this->cookieOptions );
		} else {
			$response->clearCookie( 'forceHTTPS',
				array( 'prefix' => '', 'secure' => false ) + $this->cookieOptions );
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
		return array(
			// Vary on token and session because those are the real authn
			// determiners. UserID and UserName don't matter without those.
			$this->cookieOptions['prefix'] . 'Token',
			$this->cookieOptions['prefix'] . 'LoggedOut',
			$this->params['sessionName'],
			'forceHTTPS',
		);
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
		return array(
			$this->getCookie( $request, 'UserID', $prefix ),
			$this->getCookie( $request, 'UserName', $prefix ),
			$this->getCookie( $request, 'Token', $prefix ),
		);
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
			return array(
				'UserID' => false,
				'Token' => false,
			);
		} else {
			return array(
				'UserID' => $user->getId(),
				'UserName' => $user->getName(),
				'Token' => $remember ? (string)$user->getToken() : false,
			);
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
			return array(
				'wsUserID' => $user->getId(),
				'wsToken' => $user->getToken(),
				'wsUserName' => $user->getName(),
			);
		}

		return array();
	}

	public function whyNoSession() {
		return wfMessage( 'sessionprovider-nocookies' );
	}

}
