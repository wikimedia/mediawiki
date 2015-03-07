<?php
/**
 * Cookie-based authn session
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
 * @ingroup Auth
 */

use Psr\Log\LoggerInterface;

/**
 * Implements an AuthnSession by using cookies to store the tokens
 *
 * @ingroup Auth
 * @since 1.26
 */
class CookieAuthnSession extends AuthnSession {

	/** @var WebRequest The request, used for cookie access */
	protected $request;

	/** @var array Settings */
	protected $params;

	/** @var array User info, persisted to storage. Some keys are also copied to $_SESSION. */
	protected $userInfo = array(
		'UserID' => 0,
		'UserName' => null,
		'Token' => null,
		'remember' => false,
		'forceHTTPS' => false,
	);

	/** @var string|null Source of $userInfo, for logging */
	protected $sessionFrom = null;

	/**
	 * @param WebRequest $request
	 * @param BagOStuff $store
	 * @param LoggerInterface $logger
	 * @param array $params
	 */
	public function __construct(
		WebRequest $request, BagOStuff $store, LoggerInterface $logger, array $params
	) {
		$this->request = $request;
		$this->params = $params;
		$key = null;

		if ( $params['empty'] ) {
			parent::__construct( $store, $logger, null, $params['emptyPriority'] );
			return;
		}

		$sessionId = $request->getCookie( $params['sessionName'], '', null );
		if ( $sessionId !== null ) {
			$session = $store->get( $this->getStorageKey( 'sess', $sessionId ) ) ?: array();
		} else {
			$session = array();
		}

		$cookies = $this->getUserInfoFromCookies();

		// Always honor the cookie
		$this->userInfo['forceHTTPS'] = $cookies['forceHTTPS'];

		$keys = array_flip( array( 'UserID', 'UserName', 'Token' ) );

		if ( $session && array_intersect_key( $keys, $session ) == $keys ) {
			if ( $cookies && (
				isset( $cookies['UserID'] ) && $session['UserID'] != $cookies['UserID'] ||
				isset( $cookies['UserName'] ) && $session['UserName'] != $cookies['UserName']
			) ) {
				$sId = $session['UserID'];
				$sName = $session['UserName'];
				$cId = isset( $cookies['UserID'] ) ? $cookies['UserID'] : 'null';
				$cName = isset( $cookies['UserName'] ) ? $cookies['UserName'] : 'null';
				$logger->debug(
					"Session data and cookie data don't match! $sId/$sName != $cId/$cName",
					array(
						'session' => $session,
						'cookie' => $cookies,
					)
				);
			} else {
				$this->userInfo = $session + $this->userInfo;
				$this->sessionFrom = 'session';
			}
			// Use the session for $_SESSION anyway, just not for login
			$key = $sessionId;
		} elseif ( $cookies && array_intersect_key( $keys, $cookies ) == $keys ) {
			$this->userInfo = $cookies + $this->userInfo;
			$this->sessionFrom = 'cookie';
		}

		$priority = $this->sessionFrom ? $params['priority'] : $params['emptyPriority'];
		parent::__construct( $store, $logger, $key, $priority );
	}

	/**
	 * @return string|null
	 */
	public function getAuthenticationRequestType() {
		return 'TokenSessionAuthenticationRequest';
	}

	/**
	 * @param int $lifetime
	 */
	public function setupPHPSessionHandler( $lifetime = 86400 ) {
		parent::setupPHPSessionHandler( $lifetime );
		if ( $this->userInfo['UserID'] !== 0 ) {
			$this->logger->debug( __CLASS__ . ": Logged in from $this->sessionFrom" );
		}
		$this->storeSessionData( $this->key );
	}

	/**
	 * @return bool
	 */
	protected function canResetSessionKey() {
		return true;
	}

	/**
	 * @param string $newkey
	 */
	protected function setNewSessionKey( $newkey ) {
		if ( $this->key ) {
			$this->store->delete( $this->getStorageKey( 'sess' ) );
		}
		$this->storeSessionData( $newkey );
	}

	/**
	 * @return array Array( $userId, $userName, $token )
	 */
	public function getSessionUserInfo() {
		return array(
			$this->userInfo['UserID'],
			$this->userInfo['UserName'],
			$this->userInfo['Token'],
		);
	}

	/**
	 * @return bool
	 */
	public function canSetSessionUserInfo() {
		return true;
	}

	/**
	 * @param int $id
	 * @param string|null $name
	 * @param string|null $token
	 * @param AuthenticationRequest|null $req
	 */
	public function setSessionUserInfo( $id, $name, $token, $req ) {
		if ( !$req instanceof TokenSessionAuthenticationRequest ) {
			$req = new TokenSessionAuthenticationRequest();

			// Preserve settings if it's somehow the same user
			if ( $id == $this->userInfo['UserID'] ) {
				$req->remember = $this->userInfo['remember'];
				$req->forceHTTPS = $this->userInfo['forceHTTPS'];
			}
		}
		$this->sessionFrom = 'set';
		$this->userInfo = array(
			'UserID' => $id,
			'UserName' => $name,
			'Token' => $token,
			'remember' => $req->remember,
			'forceHTTPS' => $req->forceHTTPS,
		);

		$this->storeSessionData( $this->key, $id === 0 );
	}

	/**
	 * @return string|null
	 */
	public function suggestLoginUsername() {
		$prefix = $this->params['prefix'];
		return $this->request->getCookie( 'UserName', $prefix );
	}

	/**
	 * @return bool
	 */
	public function forceHTTPS() {
		return $this->userInfo['forceHTTPS'] || (bool)$this->request->getCookie( 'forceHTTPS', '' );
	}

	/**
	 * Load an array for $this->userInfo from cookies
	 * @return array
	 */
	protected function getUserInfoFromCookies() {
		$request = $this->request;
		$prefix = $this->params['prefix'];
		return array_filter( array(
			'UserID' => $request->getCookie( 'UserID', $prefix ),
			'UserName' => $request->getCookie( 'UserName', $prefix ),
			'Token' => $request->getCookie( 'Token', $prefix ),
			'remember' => $request->getCookie( 'Token', $prefix ) !== null,
			'forceHTTPS' => (bool)$request->getCookie( 'forceHTTPS', '' ),
		), function ( $v ) { return $v !== null; } );
	}

	/**
	 * Return the data to store in cookies
	 * @return array $cookies
	 */
	protected function cookieDataToExport() {
		if ( $this->userInfo['UserID'] === 0 ) {
			return array(
				'UserID' => false,
				'Token' => false,
			);
		} else {
			return array(
				'UserID' => $this->userInfo['UserID'],
				'UserName' => $this->userInfo['UserName'],
				'Token' => $this->userInfo['remember'] ? (string)$this->userInfo['Token'] : false,
			);
		}
	}

	/**
	 * Return the data to store in $_SESSION
	 * @return array $session
	 */
	protected function sessionDataToExport() {
		// If we're calling the legacy hook, we should populate $session
		// like User::setCookies() did.
		if ( $this->userInfo['UserID'] !== 0 && $this->params['callUserSetCookiesHook'] ) {
			$user = User::newFromId( $this->userInfo['UserID'] );
			return array(
				'wsUserID' => $user->getId(),
				'wsToken' => $user->getToken( true ),
				'wsUserName' => $user->getName(),
			);
		}

		return array();
	}

	/**
	 * Save data to storage and to cookies
	 * @param string|null $key Session key
	 * @param bool $logout Whether this seems to be a logout
	 */
	protected function storeSessionData( $key, $logout = false ) {
		if ( $key ) {
			$storeKey = $this->getStorageKey( 'sess', $key );
			if ( $this->store->get( $storeKey ) !== $this->userInfo ) {
				$this->store->set( $storeKey, $this->userInfo, $this->lifetime );
			}
		}

		$cookies = $this->cookieDataToExport();
		$session = $this->sessionDataToExport();

		// Legacy hook
		if ( $this->params['callUserSetCookiesHook'] && $this->userInfo['UserID'] !== 0 ) {
			$user = User::newFromId( $this->userInfo['UserID'] );
			Hooks::run( 'UserSetCookies', array( $user, &$session, &$cookies ) );
		}

		$response = $this->request->response();
		$params = array_intersect_key(
			$this->params, array_flip( array( 'prefix', 'domain', 'path', 'secure', 'httpOnly' ) )
		);

		$expiry = $key ? null : time() - 86400;
		$response->setcookie( $this->params['sessionName'], (string)$key, $expiry,
			array( 'prefix' => '' ) + $params
		);

		foreach ( $cookies as $key => $value ) {
			$expiry = $value === false ? time() - 86400 : 0;
			$response->setcookie( $key, (string)$value, $expiry, $params );
		}

		if ( $this->userInfo['forceHTTPS'] ) {
			$response->setcookie( 'forceHTTPS', 'true', $this->userInfo['remember'] ? 0 : null,
				array( 'prefix' => '', 'secure' => false ) + $params );
		}

		foreach ( $session as $key => $value ) {
			$_SESSION[$key] = $value;
		}

		if ( $logout ) {
			$response->setcookie( 'forceHTTPS', '', time() - 86400,
				array( 'prefix' => '', 'secure' => false ) + $params );
		}
	}

	public function __toString() {
		$ret = parent::__toString();
		if ( $this->sessionFrom ) {
			$ret .= "<from=$this->sessionFrom>";
		}
		return $ret;
	}

}
