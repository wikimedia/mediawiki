<?php
/**
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
 */

/**
 * Backwards-compatibility wrapper for AuthManager via $wgAuth
 * @since 1.26
 * @deprecated since 1.26
 */
class AuthManagerAuthPlugin extends AuthPlugin {
	/** @var string|null */
	protected $domain = null;

	public function userExists( $name ) {
		return AuthManager::singleton()->exists( $name );
	}

	public function authenticate( $username, $password ) {
		$data = array(
			'username' => $username,
			'password' => $password,
		);
		if ( $this->domain !== null ) {
			$data['username'] .= '@' . $this->domain;
		}
		$types = AuthManager::singleton()->getAuthenticationRequestTypes( AuthManager::ACTION_LOGIN );
		$reqs = AuthenticationRequest::requestsFromSubmission( $types, $data, null );

		$res = AuthManager::singleton()->beginAuthentication( $reqs );
		switch ( $res->status ) {
			case AuthenticationResponse::PASS:
				return true;
			case AuthenticationResponse::FAIL:
				// Hope it's not a PreAuthenticationProvider that failed...
				wfDebugLog( 'auth', __METHOD__ . ": Authentication failed: " . $res->message->plain() );
				return false;
			default:
				throw new BadMethodCallException(
					'AuthManager does not support such simplified authentication'
				);
		}
	}

	public function modifyUITemplate( &$template, &$type ) {
		// AuthManager does not support direct UI screwing-around-with
	}

	public function setDomain( $domain ) {
		$this->domain = $domain;
	}

	public function getDomain() {
		if ( isset( $this->domain ) ) {
			return $this->domain;
		} else {
			return 'invaliddomain';
		}
	}

	public function validDomain( $domain ) {
		$domainList = $this->domainList();
		return $domainList ? in_array( $domain, $domainList ) : $domain === '';
	}

	public function updateUser( &$user ) {
		Hooks::run( 'UserLoggedIn', array( $user ) );
		return true;
	}

	public function autoCreate() {
		return true;
	}

	public function allowPropChange( $prop = '' ) {
		return AuthManager::singleton()->allowsPropertyChange( $prop );
	}

	public function allowPasswordChange() {
		$need = 'PasswordAuthenticationRequest';

		foreach ( AuthManager::singleton()->getAuthenticationRequestTypes( AuthManager::ACTION_CHANGE ) as $type ) {
			if ( $type === $need || is_subclass_of( $type, $need ) ) {
				return true;
			}
		}

		return false;
	}

	public function allowSetLocalPassword() {
		// There should be a PrimaryAuthenticationProvider that does this, if necessary
		return false;
	}

	public function setPassword( $user, $password ) {
		$data = array(
			'username' => $user->getName(),
			'password' => $password,
		);
		if ( $this->domain !== null ) {
			$data['username'] .= '@' . $this->domain;
		}
		$types = AuthManager::singleton()->getAuthenticationRequestTypes( AuthManager::ACTION_CHANGE );
		$reqs = AuthenticationRequest::requestsFromSubmission( $types, $data, null );
		foreach ( $reqs as $req ) {
			$status = AuthManager::singleton()->allowsAuthenticationDataChange( $req );
			if ( !$status->isOk() ) {
				wfDebugLog( 'auth', __METHOD__ . ": Password change rejected: " . $status->getWikiText() );
				return false;
			}
		}
		foreach ( $reqs as $req ) {
			AuthManager::singleton()->changeAuthenticationData( $req );
		}
		return true;
	}

	public function updateExternalDB( $user ) {
		// This fires the necessary hook
		$user->saveSettings();
		return true;
	}

	public function updateExternalDBGroups( $user, $addgroups, $delgroups = array() ) {
		Hooks::run( 'UserGroupsChanged', array( $user, $addgroups, $delgroups ) );
		return true;
	}

	public function canCreateAccounts() {
		return AuthenticationManager::singleton()->canCreateAccounts();
	}

	public function addUser( $user, $password, $email = '', $realname = '' ) {
		global $wgUser;

		$data = array(
			'username' => $user->getName(),
			'password' => $password,
		);
		if ( $this->domain !== null ) {
			$data['username'] .= '@' . $this->domain;
		}
		$types = AuthManager::singleton()->getAuthenticationRequestTypes( AuthManager::ACTION_CREATE );
		$reqs = AuthenticationRequest::requestsFromSubmission( $types, $data, null );

		$userData = new UserDataAuthenticationRequest();
		$userData->email = $email;
		$userData->realname = $realname;
		$reqs[] = $userData;

		$res = AuthManager::singleton()->beginAccountCreation( $user->getName(), $wgUser, $reqs );
		switch ( $res->status ) {
			case AuthenticationResponse::PASS:
				return true;
			case AuthenticationResponse::FAIL:
				// Hope it's not a PreAuthenticationProvider that failed...
				wfDebugLog( 'auth', __METHOD__ . ": Account creation failed: " . $res->message->plain() );
				return false;
			default:
				throw new BadMethodCallException(
					'AuthManager does not support such simplified account creation'
				);
		}
	}

	public function strict() {
		// There should be a PrimaryAuthenticationProvider that does this, if necessary
		return true;
	}

	public function strictUserAuth( $username ) {
		// There should be a PrimaryAuthenticationProvider that does this, if necessary
		return true;
	}

	public function initUser( &$user, $autocreate = false ) {
		Hooks::run( 'LocalUserCreated', array( $user, $autocreate ) );
	}

	public function getCanonicalName( $username ) {
		// AuthManager doesn't support restrictions beyond MediaWiki's
		return $username;
	}

	public function getUserInstance( User &$user ) {
		return new AuthManagerAuthPluginUser( $user );
	}

	public function domainList() {
		return array();
	}
}

/**
 * @since 1.26
 * @deprecated since 1.26
 */
class AuthManagerAuthPluginUser extends AuthPluginUser {
	private $user;

	function __construct( $user ) {
		$this->user = $user;
	}

	public function getId() {
		return $this->user->getId();
	}

	public function isLocked() {
		return $this->user->isLocked();
	}

	public function isHidden() {
		return $this->user->isHidden();
	}

	public function resetAuthToken() {
		AuthManager::singleton()->invalidateAuthenticationToken( $this->user->getName() );
		return true;
	}
}
