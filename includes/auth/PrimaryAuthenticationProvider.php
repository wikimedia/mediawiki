<?php
/**
 * Primary authentication provider interface
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
 * A primary authentication provider determines which user is trying to log in.
 *
 * A PrimaryAuthenticationProvider is used as part of presenting a login form
 * to authenticate a user. In particular, the PrimaryAuthenticationProvider
 * takes form data and determines the authenticated user (if any) corresponds
 * to that form data. It might do this on the basis of a username and password
 * in that data, or by interacting with an external authentication service
 * (e.g. using OpenID), or by some other mechanism.
 *
 * A PrimaryAuthenticationProvider would not be appropriate for something like
 * HTTP authentication, OAuth, or SSL client certificates where each HTTP
 * request contains all the information needed to identify the user. In that
 * case you'll want to be looking at an immutable AuthenticationSession instead.
 *
 * This interface also provides methods for changing authentication data such
 * as passwords and for creating new users who can later be authenticated with
 * this provider.
 *
 * @ingroup Auth
 * @since 1.26
 */
interface PrimaryAuthenticationProvider extends AuthenticationProvider {
	/** Provider can create accounts */
	const TYPE_CREATE = 'create';
	/** Provider can link to existing accounts elsewhere */
	const TYPE_LINK = 'link';
	/** Provider cannot create or link to accounts */
	const TYPE_NONE = 'none';

	/**
	 * Start an authentication flow
	 *
	 * If this provider will not handle $reqs, return an ABSTAIN response.
	 * Otherwise, return any other status.
	 *
	 * @param AuthenticationRequest[] $reqs Keys are class names
	 * @return AuthenticationResponse
	 */
	public function beginPrimaryAuthentication( array $reqs );

	/**
	 * Continue an authentication flow
	 * @param AuthenticationRequest[] $reqs Keys are class names
	 * @return AuthenticationResponse
	 */
	public function continuePrimaryAuthentication( array $reqs );

	/**
	 * Test whether the named user exists
	 * @param string $username
	 * @return bool
	 */
	public function testUserExists( $username );

	/**
	 * Test whether the named user can authenticate with this provider
	 * @param string $username
	 * @return bool
	 */
	public function testUserCanAuthenticate( $username );

	/**
	 * Revoke the user's credentials
	 *
	 * This may cause the user to no longer exist for the provider, or the user
	 * may continue to exist in a "disabled" state.
	 *
	 * @param string $username
	 */
	public function providerRevokeAccessForUser( $username );

	/**
	 * Determine whether a property can change
	 * @see AuthManager::allowsPropertyChange()
	 * @param string $property
	 * @return bool
	 */
	public function providerAllowsPropertyChange( $property );

	/**
	 * Indicate whether a type is supported for authentication data change
	 * @param string $type AuthenticationRequest type
	 * @return bool False if attempts to change $type should be denied
	 */
	public function providerAllowsAuthenticationDataChangeType( $type );

	/**
	 * Validate a change of authentication data (e.g. passwords)
	 *
	 * Return StatusValue::newGood( 'ignored' ) if you don't support this
	 * AuthenticationRequest type.
	 *
	 * @param AuthenticationRequest $req
	 * @return StatusValue
	 */
	public function providerAllowsAuthenticationDataChange( AuthenticationRequest $req  );

	/**
	 * Change authentication data (e.g. passwords)
	 *
	 * If the provider supports the AuthenticationRequest type, using $req
	 * should result in a successful login in the future.
	 *
	 * @param AuthenticationRequest $req
	 */
	public function providerChangeAuthenticationData( AuthenticationRequest $req  );

	/**
	 * Fetch the account-creation type
	 * @return string One of the TYPE_* constants
	 */
	public function accountCreationType();

	/**
	 * Determine whether an account creation may begin
	 *
	 * Called from AuthManager::beginAccountCreation()
	 *
	 * @note No need to test if the account exists, AuthManager checks that
	 * @param User $user User being created (not added to the database yet).
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param User $creator User doing the creation. This may become a
	 *   "UserValue" in the future, or User may be refactored into such.
	 * @param AuthenticationRequest[] $reqs Keys are class names
	 * @return StatusValue
	 */
	public function testForAccountCreation( $user, $creator, array $reqs );

	/**
	 * Start an account creation flow
	 * @param User $user User being created (not added to the database yet).
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param AuthenticationRequest[] $reqs Keys are class names
	 * @return AuthenticationResponse
	 */
	public function beginPrimaryAccountCreation( $user, array $reqs );

	/**
	 * Continue an account creation flow
	 * @param User $user User being created (not added to the database yet).
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param AuthenticationRequest[] $reqs Keys are class names
	 * @return AuthenticationResponse
	 */
	public function continuePrimaryAccountCreation( $user, array $reqs );

	/**
	 * Post-creation callback
	 * @param User $user User being created (has been added to the database now).
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param AuthenticationResponse PASS response returned earlier
	 */
	public function finishAccountCreation( $user, AuthenticationResponse $response );

	/**
	 * Determine whether an account may be auto-created
	 *
	 * Called from AuthManager::autoCreateAccount()
	 *
	 * @param User $user User being created (not added to the database yet).
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @return StatusValue
	 */
	public function testForAutoCreation( $user );

	/**
	 * Post-auto-creation callback
	 * @param User $user User being created (has been added to the database now).
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 */
	public function autoCreatedAccount( $user );

}
