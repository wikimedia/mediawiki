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

namespace MediaWiki\Auth;

use AuthPlugin;
use User;

/**
 * Primary authentication provider wrapper for AuthPlugin
 * @warning If anything depends on the wrapped AuthPlugin being $wgAuth, it won't work with this!
 * @ingroup Auth
 * @since 1.27
 * @deprecated since 1.27
 */
class AuthPluginPrimaryAuthenticationProvider
	extends AbstractPasswordPrimaryAuthenticationProvider
{
	private $auth;
	private $hasDomain;
	private $requestType = null;

	/**
	 * @param AuthPlugin $auth AuthPlugin to wrap
	 * @param string|null $requestType Class name of the
	 *  PasswordAuthenticationRequest to use. If $auth->domainList() returns
	 *  more than one domain, this must be a PasswordDomainAuthenticationRequest.
	 */
	public function __construct( AuthPlugin $auth, $requestType = null ) {
		parent::__construct();

		if ( $auth instanceof AuthManagerAuthPlugin ) {
			throw new \InvalidArgumentException(
				'Trying to wrap AuthManagerAuthPlugin in AuthPluginPrimaryAuthenticationProvider ' .
					'makes no sense.'
			);
		}

		$need = count( $auth->domainList() ) > 1
			? PasswordDomainAuthenticationRequest::class
			: PasswordAuthenticationRequest::class;
		if ( $requestType === null ) {
			$requestType = $need;
		} elseif ( $requestType !== $need && !is_subclass_of( $requestType, $need ) ) {
			throw new \InvalidArgumentException( "$requestType is not a $need" );
		}

		$this->auth = $auth;
		$this->requestType = $requestType;
		$this->hasDomain = (
			$requestType === PasswordDomainAuthenticationRequest::class ||
			is_subclass_of( $requestType, PasswordDomainAuthenticationRequest::class )
		);
		$this->authoritative = $auth->strict();

		// Registering hooks from core is unusual, but is needed here to be
		// able to call the AuthPlugin methods those hooks replace.
		\Hooks::register( 'UserSaveSettings', [ $this, 'onUserSaveSettings' ] );
		\Hooks::register( 'UserGroupsChanged', [ $this, 'onUserGroupsChanged' ] );
		\Hooks::register( 'UserLoggedIn', [ $this, 'onUserLoggedIn' ] );
		\Hooks::register( 'LocalUserCreated', [ $this, 'onLocalUserCreated' ] );
	}

	/**
	 * Create an appropriate AuthenticationRequest
	 * @return PasswordAuthenticationRequest
	 */
	protected function makeAuthReq() {
		$class = $this->requestType;
		if ( $this->hasDomain ) {
			return new $class( $this->auth->domainList() );
		} else {
			return new $class();
		}
	}

	/**
	 * Call $this->auth->setDomain()
	 * @param PasswordAuthenticationRequest $req
	 */
	protected function setDomain( $req ) {
		if ( $this->hasDomain ) {
			$domain = $req->domain;
		} else {
			// Just grab the first one.
			$domainList = $this->auth->domainList();
			$domain = reset( $domainList );
		}

		// Special:UserLogin does this. Strange.
		if ( !$this->auth->validDomain( $domain ) ) {
			$domain = $this->auth->getDomain();
		}
		$this->auth->setDomain( $domain );
	}

	/**
	 * Hook function to call AuthPlugin::updateExternalDB()
	 * @param User $user
	 * @codeCoverageIgnore
	 */
	public function onUserSaveSettings( $user ) {
		// No way to know the domain, just hope the provider handles that.
		$this->auth->updateExternalDB( $user );
	}

	/**
	 * Hook function to call AuthPlugin::updateExternalDBGroups()
	 * @param User $user
	 * @param array $added
	 * @param array $removed
	 */
	public function onUserGroupsChanged( $user, $added, $removed ) {
		// No way to know the domain, just hope the provider handles that.
		$this->auth->updateExternalDBGroups( $user, $added, $removed );
	}

	/**
	 * Hook function to call AuthPlugin::updateUser()
	 * @param User $user
	 */
	public function onUserLoggedIn( $user ) {
		$hookUser = $user;
		// No way to know the domain, just hope the provider handles that.
		$this->auth->updateUser( $hookUser );
		if ( $hookUser !== $user ) {
			throw new \UnexpectedValueException(
				get_class( $this->auth ) . '::updateUser() tried to replace $user!'
			);
		}
	}

	/**
	 * Hook function to call AuthPlugin::initUser()
	 * @param User $user
	 * @param bool $autocreated
	 */
	public function onLocalUserCreated( $user, $autocreated ) {
		// For $autocreated, see self::autoCreatedAccount()
		if ( !$autocreated ) {
			$hookUser = $user;
			// No way to know the domain, just hope the provider handles that.
			$this->auth->initUser( $hookUser, $autocreated );
			if ( $hookUser !== $user ) {
				throw new \UnexpectedValueException(
					get_class( $this->auth ) . '::initUser() tried to replace $user!'
				);
			}
		}
	}

	public function getUniqueId() {
		return parent::getUniqueId() . ':' . get_class( $this->auth );
	}

	public function getAuthenticationRequests( $action, array $options ) {
		switch ( $action ) {
			case AuthManager::ACTION_LOGIN:
			case AuthManager::ACTION_CREATE:
				return [ $this->makeAuthReq() ];

			case AuthManager::ACTION_CHANGE:
			case AuthManager::ACTION_REMOVE:
				// No way to know the domain, just hope the provider handles that.
				return $this->auth->allowPasswordChange() ? [ $this->makeAuthReq() ] : [];

			default:
				return [];
		}
	}

	public function beginPrimaryAuthentication( array $reqs ) {
		$req = AuthenticationRequest::getRequestByClass( $reqs, $this->requestType );
		if ( !$req || $req->username === null || $req->password === null ||
			( $this->hasDomain && $req->domain === null )
		) {
			return AuthenticationResponse::newAbstain();
		}

		$username = User::getCanonicalName( $req->username, 'usable' );
		if ( $username === false ) {
			return AuthenticationResponse::newAbstain();
		}

		$this->setDomain( $req );
		if ( $this->testUserCanAuthenticateInternal( User::newFromName( $username ) ) &&
			$this->auth->authenticate( $username, $req->password )
		) {
			return AuthenticationResponse::newPass( $username );
		} else {
			$this->authoritative = $this->auth->strict() || $this->auth->strictUserAuth( $username );
			return $this->failResponse( $req );
		}
	}

	public function testUserCanAuthenticate( $username ) {
		$username = User::getCanonicalName( $username, 'usable' );
		if ( $username === false ) {
			return false;
		}

		// We have to check every domain, because at least LdapAuthentication
		// interprets AuthPlugin::userExists() as applying only to the current
		// domain.
		$curDomain = $this->auth->getDomain();
		$domains = $this->auth->domainList() ?: [ '' ];
		foreach ( $domains as $domain ) {
			$this->auth->setDomain( $domain );
			if ( $this->testUserCanAuthenticateInternal( User::newFromName( $username ) ) ) {
				$this->auth->setDomain( $curDomain );
				return true;
			}
		}
		$this->auth->setDomain( $curDomain );
		return false;
	}

	/**
	 * @see self::testUserCanAuthenticate
	 * @note The caller is responsible for calling $this->auth->setDomain()
	 * @param User $user
	 * @return bool
	 */
	private function testUserCanAuthenticateInternal( $user ) {
		if ( $this->auth->userExists( $user->getName() ) ) {
			return !$this->auth->getUserInstance( $user )->isLocked();
		} else {
			return false;
		}
	}

	public function providerRevokeAccessForUser( $username ) {
		$username = User::getCanonicalName( $username, 'usable' );
		if ( $username === false ) {
			return;
		}
		$user = User::newFromName( $username );
		if ( $user ) {
			// Reset the password on every domain.
			$curDomain = $this->auth->getDomain();
			$domains = $this->auth->domainList() ?: [ '' ];
			$failed = [];
			foreach ( $domains as $domain ) {
				$this->auth->setDomain( $domain );
				if ( $this->testUserCanAuthenticateInternal( $user ) &&
					!$this->auth->setPassword( $user, null )
				) {
					$failed[] = $domain === '' ? '(default)' : $domain;
				}
			}
			$this->auth->setDomain( $curDomain );
			if ( $failed ) {
				throw new \UnexpectedValueException(
					"AuthPlugin failed to reset password for $username in the following domains: "
						. implode( ' ', $failed )
				);
			}
		}
	}

	public function testUserExists( $username, $flags = User::READ_NORMAL ) {
		$username = User::getCanonicalName( $username, 'usable' );
		if ( $username === false ) {
			return false;
		}

		// We have to check every domain, because at least LdapAuthentication
		// interprets AuthPlugin::userExists() as applying only to the current
		// domain.
		$curDomain = $this->auth->getDomain();
		$domains = $this->auth->domainList() ?: [ '' ];
		foreach ( $domains as $domain ) {
			$this->auth->setDomain( $domain );
			if ( $this->auth->userExists( $username ) ) {
				$this->auth->setDomain( $curDomain );
				return true;
			}
		}
		$this->auth->setDomain( $curDomain );
		return false;
	}

	public function providerAllowsPropertyChange( $property ) {
		// No way to know the domain, just hope the provider handles that.
		return $this->auth->allowPropChange( $property );
	}

	public function providerAllowsAuthenticationDataChange(
		AuthenticationRequest $req, $checkData = true
	) {
		if ( get_class( $req ) !== $this->requestType ) {
			return \StatusValue::newGood( 'ignored' );
		}

		// Hope it works, AuthPlugin gives us no way to do this.
		$curDomain = $this->auth->getDomain();
		$this->setDomain( $req );
		try {
			// If !$checkData the domain might be wrong. Nothing we can do about that.
			if ( !$this->auth->allowPasswordChange() ) {
				return \StatusValue::newFatal( 'authmanager-authplugin-setpass-denied' );
			}

			if ( !$checkData ) {
				return \StatusValue::newGood();
			}

			if ( $this->hasDomain ) {
				if ( $req->domain === null ) {
					return \StatusValue::newGood( 'ignored' );
				}
				if ( !$this->auth->validDomain( $req->domain ) ) {
					return \StatusValue::newFatal( 'authmanager-authplugin-setpass-bad-domain' );
				}
			}

			$username = User::getCanonicalName( $req->username, 'usable' );
			if ( $username !== false ) {
				$sv = \StatusValue::newGood();
				if ( $req->password !== null ) {
					if ( $req->password !== $req->retype ) {
						$sv->fatal( 'badretype' );
					} else {
						$sv->merge( $this->checkPasswordValidity( $username, $req->password ) );
					}
				}
				return $sv;
			} else {
				return \StatusValue::newGood( 'ignored' );
			}
		} finally {
			$this->auth->setDomain( $curDomain );
		}
	}

	public function providerChangeAuthenticationData( AuthenticationRequest $req ) {
		if ( get_class( $req ) === $this->requestType ) {
			$username = $req->username !== null ? User::getCanonicalName( $req->username, 'usable' ) : false;
			if ( $username === false ) {
				return;
			}

			if ( $this->hasDomain && $req->domain === null ) {
				return;
			}

			$this->setDomain( $req );
			$user = User::newFromName( $username );
			if ( !$this->auth->setPassword( $user, $req->password ) ) {
				// This is totally unfriendly and leaves other
				// AuthenticationProviders in an uncertain state, but what else
				// can we do?
				throw new \ErrorPageError(
					'authmanager-authplugin-setpass-failed-title',
					'authmanager-authplugin-setpass-failed-message'
				);
			}
		}
	}

	public function accountCreationType() {
		// No way to know the domain, just hope the provider handles that.
		return $this->auth->canCreateAccounts() ? self::TYPE_CREATE : self::TYPE_NONE;
	}

	public function testForAccountCreation( $user, $creator, array $reqs ) {
		return \StatusValue::newGood();
	}

	public function beginPrimaryAccountCreation( $user, $creator, array $reqs ) {
		if ( $this->accountCreationType() === self::TYPE_NONE ) {
			throw new \BadMethodCallException( 'Shouldn\'t call this when accountCreationType() is NONE' );
		}

		$req = AuthenticationRequest::getRequestByClass( $reqs, $this->requestType );
		if ( !$req || $req->username === null || $req->password === null ||
			( $this->hasDomain && $req->domain === null )
		) {
			return AuthenticationResponse::newAbstain();
		}

		$username = User::getCanonicalName( $req->username, 'usable' );
		if ( $username === false ) {
			return AuthenticationResponse::newAbstain();
		}

		$this->setDomain( $req );
		if ( $this->auth->addUser(
			$user, $req->password, $user->getEmail(), $user->getRealName()
		) ) {
			return AuthenticationResponse::newPass();
		} else {
			return AuthenticationResponse::newFail(
				new \Message( 'authmanager-authplugin-create-fail' )
			);
		}
	}

	public function autoCreatedAccount( $user, $source ) {
		$hookUser = $user;
		// No way to know the domain, just hope the provider handles that.
		$this->auth->initUser( $hookUser, true );
		if ( $hookUser !== $user ) {
			throw new \UnexpectedValueException(
				get_class( $this->auth ) . '::initUser() tried to replace $user!'
			);
		}
	}
}
