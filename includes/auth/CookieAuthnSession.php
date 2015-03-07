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
 * @since 1.25
 */
class CookieAuthnSession extends AuthnSession {

	protected $request, $params;
	protected $userInfo, $sessionFrom;

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

		$this->sessionFrom = null;
		$this->userInfo = array(
			'UserID' => 0,
			'UserName' => null,
			'Token' => null,
			'remember' => false,
		);
		$key = null;

		if ( $params['empty'] ) {
			parent::__construct( $store, $logger, null, $params['priority'] );
			return;
		}

		$sessionId = $request->getCookie( $params['sessionName'], '', null );
		if ( $sessionId !== null ) {
			$session = $store->get( $this->getStorageKey( 'sess', $sessionId ) ) ?: array();
		} else {
			$session = array();
		}

		$prefix = $this->params['prefix'];
		$cookies = array_filter( array(
			'UserID' => $request->getCookie( 'UserID', $prefix ),
			'UserName' => $request->getCookie( 'UserName', $prefix ),
			'Token' => $request->getCookie( 'Token', $prefix ),
			'remember' => $request->getCookie( 'Token', $prefix ) !== null,
		), function ( $v ) { return $v !== null; } );

		$keys = array_flip( array( 'UserID', 'UserName', 'Token' ) );

		if ( $session && array_intersect_key( $keys, $session ) == $keys ) {
			if ( $cookies && (
				isset( $cookies['UserID'] ) && $session['UserID'] != $cookies['UserID'] ||
				isset( $cookies['UserName'] ) && $session['UserName'] != $cookies['UserName']
			) ) {
				$logger->debug( "Session data and cookie data don't match!", array(
					'session' => $session,
					'cookie' => $cookie,
				) );
			} else {
				$this->userInfo = $session + $this->userInfo;
				$this->sessionFrom = 'session';
				$key = $sessionId;
			}
		} elseif ( $cookies && array_intersect_key( $keys, $cookies ) == $keys ) {
			$this->userInfo = $cookies + $this->userInfo;
			$this->sessionFrom = 'cookie';
		}

		parent::__construct( $store, $logger, $key, $params['priority'] );
	}

	public function getAuthenticationRequestType() {
		return 'CookieAuthnSessionAuthenticationRequest';
	}

	public function setupPHPSessionHandler( $lifetime = 86400 ) {
		parent::setupPHPSessionHandler( $lifetime );
		if ( $this->userInfo['UserID'] !== 0 ) {
			$this->logger->debug( __CLASS__ . ": Logged in from $this->sessionFrom" );
		}
		$this->storeSessionData( $this->key );
	}

	protected function canResetSessionKey() {
		return true;
	}

	protected function setNewSessionKey( $newkey ) {
		if ( $this->key ) {
			$this->store->delete( $this->getStorageKey( 'sess' ) );
		}
		$this->storeSessionData( $newkey );
	}

	public function getSessionUserInfo() {
		return array(
			$this->userInfo['UserID'],
			$this->userInfo['UserName'],
			$this->userInfo['Token'],
		);
	}

	public function canSetSessionUserInfo() {
		return true;
	}

	public function setSessionUserInfo( $id, $name, $token, $req ) {
		if ( !$req instanceof CookieAuthnSessionAuthenticationRequest ) {
			$req = new CookieAuthnSessionAuthenticationRequest();
		}
		$this->sessionFrom = 'set';
		$this->userInfo = array(
			'UserID' => $id,
			'UserName' => $name,
			'Token' => $token,
			'remember' => $req->remember,
		);
		$this->storeSessionData( $this->key );
	}

	protected function storeSessionData( $key ) {
		if ( $key ) {
			$storeKey = $this->getStorageKey( 'sess', $key );
			if ( $this->store->get( $storeKey ) !== $this->userInfo ) {
				$this->store->set( $storeKey, $this->userInfo, $this->lifetime );
			}
		}

		$response = $this->request->response();
		$params = array_intersect_key(
			$this->params, array_flip( array( 'prefix', 'domain', 'path', 'secure', 'httpOnly' ) )
		);

		$expiry = $key ? null : time() - 86400;
		$response->setcookie( $this->params['sessionName'], (string)$key, $expiry,
			array( 'prefix' => '' ) + $params
		);

		$expiry = ( $this->userInfo['UserID'] === 0 ) ? time() - 86400 : 0;
		$response->setcookie( 'UserID', (string)$this->userInfo['UserID'], $expiry, $params );
		$response->setcookie( 'UserName', (string)$this->userInfo['UserName'], $expiry, $params );

		if ( $this->userInfo['remember'] ) {
			$response->setcookie( 'Token', '', time() - 86400, $params );
		} else {
			$response->setcookie( 'Token', (string)$this->userInfo['Token'], $expiry, $params );
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
