<?php
/**
 * Primary authentication provider wrapper for AuthPlugin
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

/**
 * Primary authentication provider wrapper for AuthPlugin
 * @warning If anything depends on the wrapped AuthPlugin being $wgAuth, it won't work with this!
 * @ingroup Auth
 * @since 1.26
 * @deprecated since 1.26
 */
class AuthPluginPrimaryAuthenticationProvider extends AbstractPasswordPrimaryAuthenticationProvider {
	private $auth;
	private $requestType = null;

	public function __construct( AuthPlugin $auth, $requestType = null ) {
		parent::__construct();

		if ( $auth instanceof AuthManagerAuthPlugin ) {
			throw new InvalidArgumentException(
				'Trying to wrap AuthManagerAuthPlugin in AuthPluginPrimaryAuthenticationProvider ' .
					'makes no sense.'
			);
		}

		if ( $requestType === null ) {
			$requestType = 'PasswordAuthenticationRequest';
		} else {
			$need = 'PasswordAuthenticationRequest';
			if ( $requestType !== $need && !is_subclass_of( $requestType, $need ) ) {
				throw new InvalidArgumentException( "$requestType is not a $need" );
			}
		}

		$this->auth = $auth;
		$this->requestType = $requestType;
		$this->authoritative = $auth->strict();

		// Registering hooks from core is unusual, but is needed here to be
		// able to call the AuthPlugin methods those hooks replace.
		Hooks::register( 'UserSaveSettings', array( $this, 'onUserSaveSettings' ) );
		Hooks::register( 'UserGroupsChanged', array( $this, 'onUserGroupsChanged' ) );
		Hooks::register( 'UserLoggedIn', array( $this, 'onUserLoggedIn' ) );
		Hooks::register( 'LocalUserCreated', array( $this, 'onLocalUserCreated' ) );
	}

	/**
	 * Hook function to call AuthPlugin::updateExternalDB()
	 * @param User $user
	 * @codeCoverageIgnore
	 */
	public function onUserSaveSettings( $user ) {
		$this->auth->updateExternalDB( $user );
	}

	/**
	 * Hook function to call AuthPlugin::updateExternalDBGroups()
	 * @param User $user
	 * @param array $added
	 * @param array $removed
	 */
	public function onUserGroupsChanged( $user, $added, $removed ) {
		$this->auth->updateExternalDBGroups( $user, $added, $removed );
	}

	/**
	 * Hook function to call AuthPlugin::updateUser()
	 * @param User $user
	 */
	public function onUserLoggedIn( $user ) {
		$hookUser = $user;
		$this->auth->updateUser( $hookUser );
		if ( $hookUser !== $user ) {
			throw new UnexpectedValueException(
				get_class( $this->auth ) . '::updateUser() tried to replace $user!'
			);
		}
	}

	/**
	 * Hook function to call AuthPlugin::updateExternalDB()
	 * @param User $user
	 * @param bool $autocreated
	 */
	public function onLocalUserCreated( $user, $autocreated ) {
		$hookUser = $user;
		$this->auth->initUser( $hookUser, $autocreated );
		if ( $hookUser !== $user ) {
			throw new UnexpectedValueException(
				get_class( $this->auth ) . '::initUser() tried to replace $user!'
			);
		}
	}

	public function getUniqueId() {
		return parent::getUniqueId() . ':' . get_class( $this->auth );
	}

	public function getAuthenticationRequestTypes( $which ) {
		switch ( $which ) {
			case 'login':
			case 'create':
			case 'all':
				return array( $this->requestType );

			case 'change':
				return $this->auth->allowPasswordChange() ? array( $this->requestType ) : array();

			default:
				return array();
		}
	}

	public function beginPrimaryAuthentication( array $reqs ) {
		if ( !isset( $reqs[$this->requestType] ) ) {
			return AuthenticationResponse::newAbstain();
		}

		$req = $reqs[$this->requestType];
		if ( $req->username === null || $req->password === null ) {
			return AuthenticationResponse::newAbstain();
		}

		$username = $this->removeDomain( $req->username );
		if ( $this->testUserCanAuthenticate( $username ) &&
			$this->auth->authenticate( $username, $req->password )
		) {
			return AuthenticationResponse::newPass( $username, $req );
		} else {
			$this->authoritative = $this->auth->strict() || $this->auth->strictUserAuth( $username );
			return $this->failResponse( $req );
		}
	}

	public function testUserCanAuthenticate( $username ) {
		if ( $this->auth->userExists( $username ) ) {
			$user = User::newFromName( $username );
			return !$this->auth->getUserInstance( $user )->isLocked();
		} else {
			return false;
		}
	}

	public function providerRevokeAccessForUser( $username ) {
		$this->auth->setPassword( $username, null );
	}

	public function testUserExists( $username ) {
		return $this->auth->userExists( $username );
	}

	public function providerAllowsPropertyChange( $property ) {
		return $this->auth->allowPropChange( $property );
	}

	public function providerAllowsAuthenticationDataChangeType( $type ) {
		if ( $type === $this->requestType || is_subclass_of( $type, $this->requestType ) ) {
			return $this->auth->allowPasswordChange();
		} else {
			return true;
		}
	}

	public function providerAllowsAuthenticationDataChange( AuthenticationRequest $req  ) {
		// Hope it works, AuthPlugin gives us no way to do this.
		if ( $this->providerAllowsAuthenticationDataChangeType( get_class( $req ) ) &&
			$req->username !== null && $req->password !== null
		) {
			return StatusValue::newGood();
		} else {
			return StatusValue::newFatal( 'authmanager-authplugin-setpass-fail' );
		}
	}

	public function providerChangeAuthenticationData( AuthenticationRequest $req  ) {
		if ( is_a( $req, $this->requestType ) ) {
			$username = $this->removeDomain( $req->username );
			if ( !$this->auth->setPassword( $username, $req->password ) ) {
				// This is totally unfriendly and leaves other
				// AuthenticationProviders in an uncertain state, but what else
				// can we do?
				throw new ErrorPageError(
					'authmanager-authplugin-setpass-failed-title',
					'authmanager-authplugin-setpass-failed-message'
				);
			}
		}
	}

	public function accountCreationType() {
		return $this->auth->canCreateAccounts() ? self::TYPE_CREATE : self::TYPE_NONE;
	}

	public function testForAccountCreation( $user, $creator, array $reqs ) {
		return StatusValue::newGood();
	}

	public function beginPrimaryAccountCreation( $user, array $reqs ) {
		if ( $this->accountCreationType() === self::TYPE_NONE ) {
			throw new BadMethodCallException( 'Shouldn\'t call this when accountCreationType() is NONE' );
		}

		if ( !isset( $reqs[$this->requestType] ) ) {
			return AuthenticationResponse::newAbstain();
		}

		$req = $reqs[$this->requestType];
		if ( $req->username === null || $req->password === null ) {
			return AuthenticationResponse::newAbstain();
		}

		$username = $this->removeDomain( $req->username );
		if ( $this->auth->addUser(
			$username, $req->password, $user->getEmail(), $user->getRealName()
		) ) {
			return AuthenticationResponse::newPass();
		} else {
			return AuthenticationResponse::newFail(
				new Message( 'authmanager-authplugin-create-fail' )
			);
		}
	}

	/**
	 * Split a valid domain from a "user@domain" string
	 * @param string $userAndDomain
	 * @return string Username without domain. Side effect is that $this->auth->setDomain() was called.
	 */
	private function removeDomain( $userAndDomain ) {
		$domains = $this->auth->domainList();
		if ( !$domains ) {
			return $userAndDomain;
		}

		if ( strpos( $userAndDomain, '@' ) !== false ) {
			list( $user, $domain ) = explode( '@', $userAndDomain, 2 );
			if ( $this->auth->validDomain( $domain ) ) {
				$this->auth->setDomain( $domain );
				return $user;
			}
		}

		$this->auth->setDomain( reset( $domains ) );
		return $userAndDomain;
	}

}
