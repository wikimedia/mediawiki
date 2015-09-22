<?php

class MailPasswordPrimaryAuthenticationProvider extends AbstractPrimaryAuthenticationProvider {

	/**
	 * Add a "use a random password and mail it" option if a logged-in user is creating
	 * the new account.
	 *
	 * @see AuthManager::getAuthenticationRequestTypes()
	 * @param string $action One of the AuthManager::ACTION_* constants
	 * @return string[] AuthenticationRequest class names
	 */
	public function getAuthenticationRequestTypes( $action ) {
		list( $userId, $userName ) = AuthManager::singleton()->getAuthenticatedUserInfo();
		switch ( $action ) {
			case AuthManager::ACTION_CREATE:
				return $userId ? array( 'MailPasswordAuthenticationRequest' ) : array();
			case AuthManager::ACTION_ALL:
				return array( 'MailPasswordAuthenticationRequest' );
			default:
				return array();
		}
	}

	/**
	 * @param AuthenticationRequest[] $reqs Keys are class names
	 * @return AuthenticationResponse
	 */
	public function beginPrimaryAuthentication( array $reqs ) {
		return AuthenticationResponse::newAbstain();
	}

	/**
	 * Test whether the named user exists
	 * @param string $username
	 * @return bool
	 */
	public function testUserExists( $username ) {
		// TODO: Implement testUserExists() method.
	}

	/**
	 * Revoke the user's credentials
	 *
	 * This may cause the user to no longer exist for the provider, or the user
	 * may continue to exist in a "disabled" state.
	 *
	 * @param string $username
	 */
	public function providerRevokeAccessForUser( $username ) {
		// TODO: Implement providerRevokeAccessForUser() method.
	}

	/**
	 * Validate a change of authentication data (e.g. passwords)
	 *
	 * Return StatusValue::newGood( 'ignored' ) if you don't support this
	 * AuthenticationRequest type.
	 *
	 * @param AuthenticationRequest $req
	 * @return StatusValue
	 */
	public function providerAllowsAuthenticationDataChange( AuthenticationRequest $req ) {
		// TODO: Implement providerAllowsAuthenticationDataChange() method.
	}

	/**
	 * Change authentication data (e.g. passwords)
	 *
	 * If the provider supports the AuthenticationRequest type, using $req
	 * should result in a successful login in the future.
	 *
	 * @param AuthenticationRequest $req
	 */
	public function providerChangeAuthenticationData( AuthenticationRequest $req ) {
		// TODO: Implement providerChangeAuthenticationData() method.
	}

	/**
	 * Fetch the account-creation type
	 * @return string One of the TYPE_* constants
	 */
	public function accountCreationType() {
		// TODO: Implement accountCreationType() method.
	}

	/**
	 * Start an account creation flow
	 * @param User $user User being created (not added to the database yet).
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param AuthenticationRequest[] $reqs Keys are class names
	 * @return AuthenticationResponse
	 */
	public function beginPrimaryAccountCreation( $user, array $reqs ) {
		// TODO: Implement beginPrimaryAccountCreation() method.
	}
}
