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
 * @ingroup Auth
 * @since 1.26
 */
interface PrimaryAuthenticationProvider extends AuthenticationProvider {
	const TYPE_CREATE = 'create';
	const TYPE_LINK = 'link';
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
	 * Fetch status for the named user
	 * @param string $username
	 * @return AuthnUserStatus
	 */
	public function userStatus( $username );

	/**
	 * Determine whether a property can change
	 * @see AuthManager::allowPropertyChange()
	 * @param string $property
	 * @return bool
	 */
	public function providerAllowPropertyChange( $property );

	/**
	 * Indicate whether a type is supported for authentication data change
	 * @param string $type AuthenticationRequest type
	 * @return bool False if attempts to change $type should be denied
	 */
	public function providerAllowChangingAuthenticationType( $type );

	/**
	 * Validate a change of authentication data (e.g. passwords)
	 *
	 * Return StatusValue::newGood( 'ignored' ) if you don't support this
	 * AuthenticationRequest type.
	 *
	 * @param AuthenticationRequest $req
	 * @return StatusValue
	 */
	public function providerCanChangeAuthenticationData( AuthenticationRequest $req  );

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
