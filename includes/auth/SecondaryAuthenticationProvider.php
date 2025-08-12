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

namespace MediaWiki\Auth;

use MediaWiki\User\User;
use StatusValue;

/**
 * Secondary providers act after input data is already associated with a MediaWiki account.
 *
 * For login, a secondary provider performs additional authentication steps
 * after a PrimaryAuthenticationProvider has identified which MediaWiki user is
 * trying to log in. For example, it might implement a password reset, request
 * the second factor for two-factor auth, or prevent the login if the account is blocked.
 *
 * For account creation, a secondary provider performs optional extra steps after
 * a PrimaryAuthenticationProvider has created the user; for example, it can collect
 * further user information such as a biography.
 *
 * (For account linking, secondary providers are not involved.)
 *
 * This interface also provides methods for changing authentication data such
 * as a second-factor token, and callbacks that are invoked after login / account creation
 * succeeded or failed.
 *
 * @ingroup Auth
 * @since 1.27
 * @see https://www.mediawiki.org/wiki/Manual:SessionManager_and_AuthManager
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
	 *
	 * @param User $user User being authenticated. This may become a "UserIdentity" in the future.
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
	 * Post-login callback
	 *
	 * This will be called at the end of a login attempt. It will not be called for unfinished
	 * login attempts that fail by the session timing out.
	 *
	 * @param User|null $user User that was attempted to be logged in, if known.
	 *  This may become a "UserIdentity" in the future.
	 * @param AuthenticationResponse $response Authentication response that will be returned
	 *  (PASS or FAIL)
	 */
	public function postAuthentication( $user, AuthenticationResponse $response );

	/**
	 * Revoke the user's credentials
	 *
	 * This may cause the user to no longer exist for the provider, or the user
	 * may continue to exist in a "disabled" state.
	 *
	 * The intention is that the named account will never again be usable for
	 * normal login (i.e. there is no way to undo the revocation of access).
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
	 * Validate a change of authentication data (e.g. passwords)
	 *
	 * Return StatusValue::newGood( 'ignored' ) if you don't support this
	 * AuthenticationRequest type.
	 *
	 * @param AuthenticationRequest $req
	 * @param bool $checkData If false, $req hasn't been loaded from the
	 *  submission so checks on user-submitted fields should be skipped.
	 *  $req->username is considered user-submitted for this purpose, even
	 *  if it cannot be changed via $req->loadFromSubmission.
	 * @return StatusValue
	 */
	public function providerAllowsAuthenticationDataChange(
		AuthenticationRequest $req, $checkData = true
	);

	/**
	 * Change or remove authentication data (e.g. passwords)
	 *
	 * If $req was returned for AuthManager::ACTION_CHANGE, the corresponding
	 * credentials should result in a successful login in the future.
	 *
	 * If $req was returned for AuthManager::ACTION_REMOVE, the corresponding
	 * credentials should no longer result in a successful login.
	 *
	 * It can be assumed that providerAllowsAuthenticationDataChange with $checkData === true
	 * was called before this, and passed. This method should never fail (other than throwing an
	 * exception).
	 */
	public function providerChangeAuthenticationData( AuthenticationRequest $req );

	/**
	 * Determine whether an account creation may begin
	 *
	 * Called from AuthManager::beginAccountCreation()
	 *
	 * @note No need to test if the account exists, AuthManager checks that
	 * @param User $user User being created (not added to the database yet).
	 *  This may become a "UserIdentity" in the future.
	 * @param User $creator User doing the creation. This may become a "UserIdentity" in the future.
	 * @param AuthenticationRequest[] $reqs
	 * @return StatusValue
	 */
	public function testForAccountCreation( $user, $creator, array $reqs );

	/**
	 * Start an account creation flow
	 *
	 * @note There is no guarantee this will be called in a successful account
	 *   creation process as the user can just abandon the process at any time
	 *   after the primary provider has issued a PASS and still have a valid
	 *   account. Be prepared to handle any database inconsistencies that result
	 *   from this or continueSecondaryAccountCreation() not being called.
	 * @param User $user User being created (has been added to the database).
	 *  This may become a "UserIdentity" in the future.
	 * @param User $creator User doing the creation. This may become a "UserIdentity" in the future.
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse Expected responses:
	 *  - PASS: The user creation is ok. Additional secondary providers may run.
	 *  - ABSTAIN: Additional secondary providers may run.
	 *  - UI: Additional AuthenticationRequests are needed to complete the process.
	 *  - REDIRECT: Redirection to a third party is needed to complete the process.
	 */
	public function beginSecondaryAccountCreation( $user, $creator, array $reqs );

	/**
	 * Continue an authentication flow
	 *
	 * @param User $user User being created (has been added to the database).
	 *  This may become a "UserIdentity" in the future.
	 * @param User $creator User doing the creation. This may become a "UserIdentity" in the future.
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse Expected responses:
	 *  - PASS: The user creation is ok. Additional secondary providers may run.
	 *  - ABSTAIN: Additional secondary providers may run.
	 *  - UI: Additional AuthenticationRequests are needed to complete the process.
	 *  - REDIRECT: Redirection to a third party is needed to complete the process.
	 */
	public function continueSecondaryAccountCreation( $user, $creator, array $reqs );

	/**
	 * Post-creation callback
	 *
	 * This will be called at the end of an account creation attempt. It will not be called if
	 * the account creation process results in a session timeout (possibly after a successful
	 * user creation, while a secondary provider is waiting for a response).
	 *
	 * @param User $user User that was attempted to be created.
	 *  This may become a "UserIdentity" in the future.
	 * @param User $creator User doing the creation. This may become a "UserIdentity" in the future.
	 * @param AuthenticationResponse $response Authentication response that will be returned
	 *   (PASS or FAIL)
	 */
	public function postAccountCreation( $user, $creator, AuthenticationResponse $response );

	/**
	 * Determine whether an account may be created
	 *
	 * @param User $user User being created (not added to the database yet).
	 *  This may become a "UserIdentity" in the future.
	 * @param bool|string $autocreate False if this is not an auto-creation, or
	 *  the source of the auto-creation passed to AuthManager::autoCreateUser().
	 * @param array $options
	 *  - flags: (int) Bitfield of IDBAccessObject::READ_* constants, default IDBAccessObject::READ_NORMAL
	 *  - creating: (bool) If false (or missing), this call is only testing if
	 *    a user could be created. If set, this (non-autocreation) is for
	 *    actually creating an account and will be followed by a call to
	 *    testForAccountCreation(). In this case, the provider might return
	 *    StatusValue::newGood() here and let the later call to
	 *    testForAccountCreation() do a more thorough test.
	 *  - canAlwaysAutocreate: (bool) If true the session provider is exempt from
	 *    autocreate user permissions checks.
	 * @return StatusValue
	 */
	public function testUserForCreation( $user, $autocreate, array $options = [] );

	/**
	 * Post-auto-creation callback
	 *
	 * @param User $user User being created (has been added to the database now).
	 *  This may become a "UserIdentity" in the future.
	 * @param string $source The source of the auto-creation passed to
	 *  AuthManager::autoCreateUser().
	 */
	public function autoCreatedAccount( $user, $source );

}
