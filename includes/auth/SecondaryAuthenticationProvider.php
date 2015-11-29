<?php
/**
 * Secondary authentication provider interface
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

use StatusValue;
use User;

/**
 * A secondary authentication provider performs additional authentication steps
 * after a PrimaryAuthenticationProvider has done its thing.
 *
 * A SecondaryAuthenticationProvider is used to perform arbitrary checks on an
 * authentication request after the user itself has been authenticated. For
 * example, it might implement a password reset, request the second factor for
 * two-factor auth, or prevent the login if the account is blocked.
 *
 * @ingroup Auth
 * @since 1.27
 */
interface SecondaryAuthenticationProvider extends AuthenticationProvider {

	/**
	 * Start an authentication flow
	 *
	 * Note that this may be called for a user even if
	 * beginSecondaryAccountCreation() was never called. The module should take
	 * the opportunity to do any necessary setup in that case.
	 *
	 * @param User $user User being authenticated. This may become a
	 *   "UserValue" in the future, or User may be refactored into such.
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse Expected responses:
	 *  - PASS: The user is authenticated. Additional secondary providers may run.
	 *  - FAIL: The user is not authenticated. Fail the authentication process.
	 *  - ABSTAIN: Additional secondary providers may run.
	 *  - UI: Additional AuthenticationRequests are needed to complete the process.
	 *  - REDIRECT: Redirection to a third party is needed to complete the process.
	 */
	public function beginSecondaryAuthentication( $user, array $reqs );

	/**
	 * Continue an authentication flow
	 * @param User $user User being authenticated. This may become a
	 *   "UserValue" in the future, or User may be refactored into such.
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse Expected responses:
	 *  - PASS: The user is authenticated. Additional secondary providers may run.
	 *  - FAIL: The user is not authenticated. Fail the authentication process.
	 *  - ABSTAIN: Additional secondary providers may run.
	 *  - UI: Additional AuthenticationRequests are needed to complete the process.
	 *  - REDIRECT: Redirection to a third party is needed to complete the process.
	 */
	public function continueSecondaryAuthentication( $user, array $reqs );

	/**
	 * Determine whether a property can change
	 * @see AuthManager::allowsPropertyChange()
	 * @param string $property
	 * @return bool
	 */
	public function providerAllowsPropertyChange( $property );

	/**
	 * Validate a change of authentication data (e.g. passwords)
	 * @param AuthenticationRequest $req
	 * @return StatusValue
	 */
	public function providerAllowsAuthenticationDataChange( AuthenticationRequest $req );

	/**
	 * Change authentication data.
	 *
	 * Getting a request from getAuthenticationRequests( AuthManager::ACTION_CHANGE ),
	 * modifying it and passing it to this method should result in the stored
	 * authenticaton data being updated accordingly.
	 *
	 * @param AuthenticationRequest $req
	 */
	public function providerChangeAuthenticationData( AuthenticationRequest $req );

	/**
	 * Validate a removal of authentication data (e.g. passwords)
	 * @param AuthenticationRequest $req
	 * @return StatusValue
	 */
	public function providerAllowsAuthenticationDataRemoval( AuthenticationRequest $req );

	/**
	 * Remove authentication data.
	 *
	 * Getting a request from getAuthenticationRequests( AuthManager::ACTION_REMOVE ), modifying
	 * it and passing it to this method should result in the stored authenticaton data identified
	 * by that request being deleted.
	 *
	 * @param AuthenticationRequest $req
	 */
	public function providerRemoveAuthenticationData( AuthenticationRequest $req );

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
	 * @param AuthenticationRequest[] $reqs
	 * @return StatusValue
	 */
	public function testForAccountCreation( $user, $creator, array $reqs );

	/**
	 * Start an account creation flow
	 * @param User $user User being created (has been added to the database).
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse Expected responses:
	 *  - PASS: The user creation is ok. Additional secondary providers may run.
	 *  - ABSTAIN: Additional secondary providers may run.
	 *  - UI: Additional AuthenticationRequests are needed to complete the process.
	 *  - REDIRECT: Redirection to a third party is needed to complete the process.
	 */
	public function beginSecondaryAccountCreation( $user, array $reqs );

	/**
	 * Continue an authentication flow
	 * @param User $user User being created (has been added to the database).
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse Expected responses:
	 *  - PASS: The user creation is ok. Additional secondary providers may run.
	 *  - ABSTAIN: Additional secondary providers may run.
	 *  - UI: Additional AuthenticationRequests are needed to complete the process.
	 *  - REDIRECT: Redirection to a third party is needed to complete the process.
	 */
	public function continueSecondaryAccountCreation( $user, array $reqs );

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
