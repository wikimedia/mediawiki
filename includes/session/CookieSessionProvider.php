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

	/**
	 * @param array $params Keys include:
	 *  - priority: (required) Priority of the returned sessions
	 *  - callUserSetCookiesHook: Whether to call the deprecated hook
	 *  - prefix: Cookie prefix, defaults to $wgCookiePrefix
	 *  - path: Cookie path, defaults to $wgCookiePath
	 *  - domain: Cookie domain, defaults to $wgCookieDomain
	 *  - secure: Cookie secure flag, defaults to $wgCookieSecure
	 *  - httpOnly: Cookie httpOnly flag, defaults to $wgCookieHttpOnly
	 *  - sessionName: Session cookie name. Doesn't honor 'prefix'. Defaults to
	 *    $wgSessionName, or $wgCookiePrefix . '_session' if that is unset.
	 */
	public function __construct( $params = array() ) {
		if ( !isset( $params['priority'] ) ) {
			throw new \InvalidArgumentException( __METHOD__ . ': priority must be specified' );
		}
		$this->params = $params;
	}

	public function setConfig( Config $config ) {
		parent::setConfig( $config );

		$this->params += array(
			'callUserSetCookiesHook' => false,
			'prefix' => $config->get( 'CookiePrefix' ),
			'path' => $config->get( 'CookiePath' ),
			'domain' => $config->get( 'CookieDomain' ),
			'secure' => $config->get( 'CookieSecure' ),
			'httpOnly' => $config->get( 'CookieHttpOnly' ),
			'sessionName' =>
				$config->get( 'SessionName' ) ?: $config->get( 'CookiePrefix' ) . '_session',
		);
	}

	public function provideSessionInfo( WebRequest $request ) {
		$info = array(
			'id' => $request->getCookie( $this->params['sessionName'], '' )
		);
		if ( !SessionManager::validateSessionId( $info['id'] ) ) {
			unset( $info['id'] );
		}

		list( $userId, $token ) = $this->getUserInfoFromCookies( $request );
		if ( $userId !== null ) {
			try {
				$user = UserInfo::newFromId( $userId );
			} catch ( \InvalidArgumentException $ex ) {
				return null;
			}

			if ( $token !== null ) {
				if ( !hash_equals( $user->getToken(), $token ) ) {
					return null;
				}
				// @todo: User::loadFromSession would maybe check $user->getName()
				// against the UserName cookie here. Should that still be done?
				// We can't do the "maybe" part though.
				$info['user'] = $user->authenticated();
			} elseif ( isset( $info['id'] ) ) { // No point if no session ID
				$info['user'] = $user;
			}
		}

		if ( !$info ) {
			return null;
		}

		$info += array(
			'provider' => $this,
			'persisted' => isset( $info['id'] ),
			'forceHTTPS' => $request->getCookie( 'forceHTTPS', '', false )
		);

		return new SessionInfo( $this->params['priority'], $info );
	}

	public function persistsSessionId() {
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

		$cookies = $this->cookieDataToExport( $user, $session->rememberUser() );
		$sessionData = $this->sessionDataToExport( $user );

		// Legacy hook
		if ( $this->params['callUserSetCookiesHook'] && !$user->isAnon() ) {
			\Hooks::run( 'UserSetCookies', array( $user, &$sessionData, &$cookies ) );
		}

		$params = array_intersect_key(
			$this->params, array_flip( array( 'prefix', 'domain', 'path', 'secure', 'httpOnly' ) )
		);

		$response->setCookie( $this->params['sessionName'], $session->getId(), null,
			array( 'prefix' => '' ) + $params
		);

		$extendedCookies = $this->config->get( 'ExtendedLoginCookies' );
		$extendedExpiry = $this->config->get( 'ExtendedLoginCookieExpiration' );

		foreach ( $cookies as $key => $value ) {
			if ( $value === false ) {
				$expiry = time() - 86400;
			} elseif ( $extendedExpiry !== null && in_array( $key, $extendedCookies ) ) {
				$expiry = time() + (int)$extendedExpiry;
			} else {
				$expiry = 0;
			}
			$response->setCookie( $key, (string)$value, $expiry, $params );
		}

		if ( $session->forceHTTPS() || $user->requiresHTTPS() ) {
			$response->setCookie( 'forceHTTPS', 'true', $session->rememberUser() ? 0 : null,
				array( 'prefix' => '', 'secure' => false ) + $params );
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

		$cookies = array(
			'UserID' => false,
			'Token' => false,
		);

		$params = array_intersect_key(
			$this->params, array_flip( array( 'prefix', 'domain', 'path', 'secure', 'httpOnly' ) )
		);

		$expiry = time() - 86400;
		$response->setCookie( $this->params['sessionName'], '', $expiry,
			array( 'prefix' => '' ) + $params
		);

		foreach ( $cookies as $key => $value ) {
			$response->setCookie( $key, '', $expiry, $params );
		}

		$response->setCookie( 'forceHTTPS', '', $expiry,
			array( 'prefix' => '', 'secure' => false ) + $params );
	}

	public function getVaryCookies() {
		return array(
			// Vary on token and session because those are the real authn
			// determiners. UserID and UserName don't matter without those.
			$this->params['prefix'] . 'Token',
			$this->params['sessionName'],
			'forceHTTPS',
		);
	}

	public function suggestLoginUsername( WebRequest $request ) {
		 $name = $request->getCookie( 'UserName', $this->params['prefix'] );
		 if ( $name !== null ) {
			 $name = User::getCanonicalName( $name, 'usable' );
		 }
		 return $name === false ? null : $name;
	}

	/**
	 * Fetch the user identity from cookies
	 * @return array (int|null $id, string|null $token)
	 */
	protected function getUserInfoFromCookies( $request ) {
		$prefix = $this->params['prefix'];
		return array(
			$request->getCookie( 'UserID', $prefix ),
			$request->getCookie( 'Token', $prefix ),
		);
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

}
