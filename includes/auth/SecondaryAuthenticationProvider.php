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

/**
 * A secondary authentication provider performs additional authentication steps
 * after a PrimaryAuthenticationProvider has done its thing.
 * @ingroup Auth
 * @since 1.26
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
	 * @param AuthenticationRequest[] $reqs Keys are class names
	 * @return AuthenticationResponse
	 */
	public function beginSecondaryAuthentication( $user, array $reqs );

	/**
	 * Continue an authentication flow
	 * @param User $user User being authenticated. This may become a
	 *   "UserValue" in the future, or User may be refactored into such.
	 * @param AuthenticationRequest[] $reqs Keys are class names
	 * @return AuthenticationResponse
	 */
	public function continueSecondaryAuthentication( $user, array $reqs );

	/**
	 * Determine whether a property can change
	 * @see AuthManager::allowPropertyChange()
	 * @param string $property
	 * @return bool
	 */
	public function providerAllowPropertyChange( $property );

	/**
	 * Validate a change of authentication data (e.g. passwords)
	 * @param AuthenticationRequest $req
	 * @return StatusValue
	 */
	public function providerCanChangeAuthenticationData( AuthenticationRequest $req  );

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
	 * @param User $user User being created (has been added to the database).
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param AuthenticationRequest[] $reqs Keys are class names
	 * @return AuthenticationResponse FAIL response is not allowed
	 */
	public function beginSecondaryAccountCreation( $user, array $reqs );

	/**
	 * Continue an authentication flow
	 * @param User $user User being created (has been added to the database).
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param AuthenticationRequest[] $reqs Keys are class names
	 * @return AuthenticationResponse FAIL response is not allowed
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
