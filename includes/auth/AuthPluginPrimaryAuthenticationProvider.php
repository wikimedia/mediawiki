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
 * @since 1.25
 * @deprecated since 1.25
 */
class AuthPluginPrimaryAuthenticationProvider extends PrimaryAuthenticationProvider {
	private $auth;
	private $requestType = null;

	public function __construct( AuthPlugin $auth, $requestType = null ) {
		if ( $auth instanceof AuthManagerAuthPlugin ) {
			throw new InvalidArgumentException(
				'Trying to wrap AuthManagerAuthPlugin in AuthPluginPrimaryAuthenticationProvider ' .
					'makes no sense.'
			);
		}

		if ( $auth->listDomains() ) {
			$domains = $auth->domainList();
			if ( $requestType === null ) {
				if ( !function_exists( 'eval' ) ) {
					throw new InvalidArgumentException(
						'eval() is disabled. You will need to manually create a subclass of ' .
							'PasswordDomainAuthenticationRequest that returns the domains of ' .
							get_class( $auth ) . ' and pass that classname to ' . __METHOD__
					);
				}

				// EEEE-VIL! Use eval() to create a subclass at runtime...
				$phpDomains = var_export( $domains, true );
				$requestType = 'PasswordDomainAuthenticationRequest_' . md5( $phpDomains );
				if ( !class_exists( $requestType ) ) {
					eval( <<<EVIL
						class $requestType extends PasswordDomainAuthenticationRequest {
							protected static function domainList() {
								return $phpDomains;
							}
						}
EVIL
					);
				}
			}

			$need = 'PasswordDomainAuthenticationRequest';
			if ( !is_subclass_of( $requestType, $need ) ) {
				throw new InvalidArgumentException( "$requestType is not a subclass of $need" );
			}
			$info = call_user_func( array( $requestType, 'getFieldInfo' ) );
			if ( !isset( $info['domain'] ) || $info['domain']['type'] !== 'select' ) {
				throw new InvalidArgumentException(
					"$requestType does not specify a correct 'domain' field"
				);
			}
			sort( $domains );
			$infoDomains = array_keys( $info['domain']['options'] );
			sort( $infoDomains );
			if ( $domains != $infoDomains ) {
				throw new InvalidArgumentException(
					"Domain list for $requestType does not match that for " . get_class( $auth )
				);
			}
		} else {
			if ( $requestType === null ) {
				$requestType = 'PasswordAuthenticationRequest';
			} else {
				$need = 'PasswordAuthenticationRequest';
				if ( $requestType !== $need && !is_subclass_of( $requestType, $need ) ) {
					throw new InvalidArgumentException( "$requestType is not a subclass of $need" );
				}
			}
		}

		$this->auth = $auth;
		$this->requestType = $requestType;

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

	/**
	 * @return string
	 */
	public function getUniqueId() {
		return __CLASS__ . ':' . get_class( $this->auth );
	}

	/**
	 * @param string $which
	 * @return string[]
	 */
	public function getAuthenticationRequestTypes( $which ) {
		/** @todo Implement this! */
	}

	/**
	 * @param AuthManager $manager
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function beginAuthentication( AuthManager $manager, array $reqs ) {
		/** @todo Implement this */
		// Find the $this->requestType, if any
		// Extract the stuff
		// call $this->auth->authenticate()
		// return PASS or FAIL
	}

	/**
	 * @param AuthManager $manager
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function continueAuthentication( AuthManager $manager, array $reqs ) {
		throw new BadMethodCallException( __METHOD__ . ' should never be reached. Huh?' );
	}

	/**
	 * @param string $username
	 * @return AuthnUserStatus
	 */
	public function userStatus( $username ) {
		$user = $this->auth->getUserInstance( $username );
		return new AuthnUserStatus( array(
			'exists' => $this->auth->userExists( $username ),
			'locked' => $user->isLocked(),
			'hidden' => $user->isHidden(),
		) );
	}

	/**
	 * @param string $property
	 * @return bool
	 */
	public function allowPropertyChange( $property ) {
		return $this->auth->allowPropChange( $property );
	}

	/**
	 * @param string $type AuthenticationRequest type
	 * @return bool true if supported or ignored
	 */
	public function allowChangingAuthenticationType( $type ) {
		if ( $type === $this->requestType || is_subclass_of( $type, $this->requestType ) ) {
			return $this->auth->allowPasswordChange();
		} else {
			return true;
		}
	}

	/**
	 * @param AuthenticationRequest $req
	 * @return StatusValue
	 */
	public function canChangeAuthenticationData( AuthenticationRequest $req  ) {
		/** @todo Figure out WTF to do here, AuthPlugin doesn't have a verify/do split */
	}

	/**
	 * @param AuthenticationRequest $req
	 */
	public function changeAuthenticationData( AuthenticationRequest $req  ) {
		/** @todo Figure out WTF to do here, AuthPlugin doesn't have a verify/do split */
	}

	/**
	 * @return string One of the TYPE_* constants
	 */
	public function accountCreationType() {
		return $this->canCreateAccounts() ? self::TYPE_CREATE : self::TYPE_NONE;
	}

	/**
	 * @param AuthManager $manager
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function beginAccountCreation( AuthManager $manager, array $reqs ) {
		/** @todo Implement this */
		// Find the $this->requestType, if any
		// Extract the stuff
		// call $this->auth->addUser()
		// return PASS or FAIL
	}

	/**
	 * @param AuthManager $manager
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function continueAccountCreation( AuthManager $manager, array $reqs ) {
		throw new BadMethodCallException( __METHOD__ . ' should never be reached. Huh?' );
	}

}
