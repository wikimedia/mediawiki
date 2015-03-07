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
 * @since 1.25
 * @deprecated since 1.25
 */
class AuthManagerAuthPlugin extends AuthPlugin {
	/** @var string|null */
	protected $domain = null;

	/**
	 * @param string $name
	 * @return bool
	 */
	public function userExists( $name ) {
		return AuthManager::singleton()->exists( $name );
		# Override this!
		return false;
	}

	/**
	 * @param string $username
	 * @param string $password
	 * @return bool
	 */
	public function authenticate( $username, $password ) {
		/** @todo Implement this! */
		// Create a PasswordAuthenticationRequest, or PasswordDomainAuthenticationRequest, or whatever
		$res = AuthManager::singleton()->beginAuthentication( $reqs );
		switch ( $res->status ) {
			case AuthenticationResponse::PASS:
				return true;
			case AuthenticationResponse::FAIL:
				// Hope it's not a PreAuthenticationProvider that failed...
				return false;
			default:
				throw new BadMethodCallException(
					'AuthManager does not support such simplified authentication'
				);
		}
	}

	/**
	 * @param UserLoginTemplate $template
	 * @param string $type
	 */
	public function modifyUITemplate( &$template, &$type ) {
		throw new BadMethodCallException(
			'AuthManager does not support direct UI screwing-around-with'
		);
	}

	/**
	 * @param string $domain Authentication domain.
	 */
	public function setDomain( $domain ) {
		$this->domain = $domain;
	}

	/**
	 * @return string
	 */
	public function getDomain() {
		if ( isset( $this->domain ) ) {
			return $this->domain;
		} else {
			return 'invaliddomain';
		}
	}

	/**
	 * @param string $domain Authentication domain.
	 * @return bool
	 */
	public function validDomain( $domain ) {
		return in_array( $domain, $this->domainList() );
	}

	/**
	 * @param User $user
	 * @return bool
	 */
	public function updateUser( &$user ) {
		Hooks::run( 'UserLoggedIn', array( $user ) );
		return true;
	}

	/**
	 * @return bool
	 */
	public function autoCreate() {
		return true;
	}

	/**
	 * @param string $prop
	 * @return bool
	 */
	public function allowPropChange( $prop = '' ) {
		return AuthManager::singleton()->allowPropertyChange( $prop );
	}

	/**
	 * @return bool
	 */
	public function allowPasswordChange() {
		/** @todo Implement this! */
		// Call AuthManager->allowChangingAuthenticationType() for
		// PasswordAuthenticationRequest or PasswordDomainAuthenticationRequest
	}

	/**
	 * @return bool
	 */
	public function allowSetLocalPassword() {
		// There should be a PrimaryAuthenticationProvider that does this, if necessary
		return false;
	}

	/**
	 * @param User $user
	 * @param string $password Password.
	 * @return bool
	 */
	public function setPassword( $user, $password ) {
		/** @todo Implement this! */
		// Create a PasswordAuthenticationRequest, or PasswordDomainAuthenticationRequest, or whatever
		if ( !AuthManager::singleton()->canChangeAuthenticationData( $req )->isGood() ) {
			return false;
		}
		AuthManager::singleton()->changeAuthenticationData( $req );
		return true;
	}

	/**
	 * @param User $user
	 * @return bool
	 */
	public function updateExternalDB( $user ) {
		// This fires the necessary hook
		$user->saveSettings();
		return true;
	}

	/**
	 * @param User $user
	 * @param array $addgroups
	 * @param array $delgroups
	 * @return bool
	 */
	public function updateExternalDBGroups( $user, $addgroups, $delgroups = array() ) {
		Hooks::run( 'UserGroupsChanged', array( $user, $addgroups, $delgroups ) );
		return true;
	}

	/**
	 * @return bool
	 */
	public function canCreateAccounts() {
		return AuthenticationManager::singleton()->canCreateAccounts();
	}

	/**
	 * @param User $user
	 * @param string $password
	 * @param string $email
	 * @param string $realname
	 * @return bool
	 */
	public function addUser( $user, $password, $email = '', $realname = '' ) {
		// Create a PasswordAuthenticationRequest, or PasswordDomainAuthenticationRequest, or whatever
		$res = AuthManager::singleton()->beginAccountCreation( $reqs );
		switch ( $res->status ) {
			case AuthenticationResponse::PASS:
				return true;
			case AuthenticationResponse::FAIL:
				// Hope it's not a PreAuthenticationProvider that failed...
				return false;
			default:
				throw new BadMethodCallException(
					'AuthManager does not support such simplified account creation'
				);
		}
	}

	/**
	 * @return bool
	 */
	public function strict() {
		// There should be a PrimaryAuthenticationProvider that does this, if necessary
		return true;
	}

	/**
	 * @param string $username
	 * @return bool
	 */
	public function strictUserAuth( $username ) {
		// There should be a PrimaryAuthenticationProvider that does this, if necessary
		return true;
	}

	/**
	 * @param User $user
	 * @param bool $autocreate True if user is being autocreated on login
	 */
	public function initUser( &$user, $autocreate = false ) {
		Hooks::register( 'LocalUserCreated', array( $user, $autocreate ) );
	}

	/**
	 * @param string $username
	 * @return string
	 */
	public function getCanonicalName( $username ) {
		// AuthManager doesn't support restrictions beyond MediaWiki's
		return $username;
	}

	/**
	 * @param User $user
	 * @return AuthPluginUser
	 */
	public function getUserInstance( User &$user ) {
		return new AuthManagerAuthPluginUser( $user );
	}

	/**
	 * @return array
	 */
	public function domainList() {
		/** @todo Implement this */
		// Probably fetch all the AuthenticationRequest types, find any
		// PasswordDomainAuthenticationRequests, get their lists of domains,
		// and merge them.
	}
}

/**
 * @since 1.25
 * @deprecated since 1.25
 */
class AuthManagerAuthPluginUser extends AuthPluginUser {
	private $id, $name, $status;

	/**
	 * @param string $name
	 */
	function __construct( $user ) {
		$this->id = $user->getId();
		$this->name = $user->getName();
		$this->status = AuthManager::singleton()->getUserStatus( $this->name );
	}

	public function getId() {
		return $this->id;
	}

	public function isLocked() {
		return $this->status->locked();
	}

	public function isHidden() {
		return $this->status->hidden();
	}

	public function resetAuthToken() {
		AuthManager::singleton()->invalidateAuthenticationToken( $this->name );
		return true;
	}
}
