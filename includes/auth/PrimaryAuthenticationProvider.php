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

namespace MediaWiki\Auth;

use StatusValue;
use User;

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
 * case you'll want to be looking at a \\MediaWiki\\Session\\SessionProvider
 * instead.
 *
 * This interface also provides methods for changing authentication data such
 * as passwords and for creating new users who can later be authenticated with
 * this provider.
 *
 * @ingroup Auth
 * @since 1.27
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
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse Expected responses:
	 *  - PASS: The user is authenticated. Secondary providers will now run.
	 *  - FAIL: The user is not authenticated. Fail the authentication process.
	 *  - ABSTAIN: These $reqs are not handled. Some other primary provider may handle it.
	 *  - UI: The $reqs are accepted, no other primary provider will run.
	 *    Additional AuthenticationRequests are needed to complete the process.
	 *  - REDIRECT: The $reqs are accepted, no other primary provider will run.
	 *    Redirection to a third party is needed to complete the process.
	 */
	public function beginPrimaryAuthentication( array $reqs );

	/**
	 * Continue an authentication flow
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse Expected responses:
	 *  - PASS: The user is authenticated. Secondary providers will now run.
	 *  - FAIL: The user is not authenticated. Fail the authentication process.
	 *  - UI: Additional AuthenticationRequests are needed to complete the process.
	 *  - REDIRECT: Redirection to a third party is needed to complete the process.
	 */
	public function continuePrimaryAuthentication( array $reqs );

	/**
	 * Post-login callback
	 * @param User|null $user User that was attempted to be logged in, if known.
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param AuthenticationResponse $response Authentication response that will be returned
	 */
	public function postAuthentication( $user, AuthenticationResponse $response );

	/**
	 * Test whether the named user exists
	 * @param string $username
	 * @param int $flags Bitfield of User:READ_* constants
	 * @return bool
	 */
	public function testUserExists( $username, $flags = User::READ_NORMAL );

	/**
	 * Test whether the named user can authenticate with this provider
	 * @param string $username
	 * @return bool
	 */
	public function testUserCanAuthenticate( $username );

	/**
	 * Normalize the username for authentication
	 *
	 * Any two inputs that would result in the same user being authenticated
	 * should return the same string here, while inputs that would result in
	 * different users should return different strings.
	 *
	 * If possible, the best thing to do here is to return the canonicalized
	 * name of the local user account that would be used. If not, return
	 * something that would be invalid as a local username (e.g. wrap an email
	 * address in "<>", or append "#servicename" to the username passed to a
	 * third-party service).
	 *
	 * If the provider doesn't use a username at all in its
	 * AuthenticationRequests, return null. If the name is syntactically
	 * invalid, it's probably best to return null.
	 *
	 * @param string $username
	 * @return string|null
	 */
	public function providerNormalizeUsername( $username );

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
	 * @param AuthenticationRequest $req
	 */
	public function providerChangeAuthenticationData( AuthenticationRequest $req );

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
	 * @param AuthenticationRequest[] $reqs
	 * @return StatusValue
	 */
	public function testForAccountCreation( $user, $creator, array $reqs );

	/**
	 * Start an account creation flow
	 * @param User $user User being created (not added to the database yet).
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param User $creator User doing the creation. This may become a
	 *   "UserValue" in the future, or User may be refactored into such.
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse Expected responses:
	 *  - PASS: The user may be created. Secondary providers will now run.
	 *  - FAIL: The user may not be created. Fail the creation process.
	 *  - ABSTAIN: These $reqs are not handled. Some other primary provider may handle it.
	 *  - UI: The $reqs are accepted, no other primary provider will run.
	 *    Additional AuthenticationRequests are needed to complete the process.
	 *  - REDIRECT: The $reqs are accepted, no other primary provider will run.
	 *    Redirection to a third party is needed to complete the process.
	 */
	public function beginPrimaryAccountCreation( $user, $creator, array $reqs );

	/**
	 * Continue an account creation flow
	 * @param User $user User being created (not added to the database yet).
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param User $creator User doing the creation. This may become a
	 *   "UserValue" in the future, or User may be refactored into such.
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse Expected responses:
	 *  - PASS: The user may be created. Secondary providers will now run.
	 *  - FAIL: The user may not be created. Fail the creation process.
	 *  - UI: Additional AuthenticationRequests are needed to complete the process.
	 *  - REDIRECT: Redirection to a third party is needed to complete the process.
	 */
	public function continuePrimaryAccountCreation( $user, $creator, array $reqs );

	/**
	 * Post-creation callback
	 *
	 * Called after the user is added to the database, before secondary
	 * authentication providers are run.
	 *
	 * @param User $user User being created (has been added to the database now).
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param User $creator User doing the creation. This may become a
	 *   "UserValue" in the future, or User may be refactored into such.
	 * @param AuthenticationResponse $response PASS response returned earlier
	 * @return string|null 'newusers' log subtype to use for logging the
	 *   account creation. If null, either 'create' or 'create2' will be used
	 *   depending on $creator.
	 */
	public function finishAccountCreation( $user, $creator, AuthenticationResponse $response );

	/**
	 * Post-creation callback
	 *
	 * Called when the account creation process ends.
	 *
	 * @param User $user User that was attempted to be created.
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param User $creator User doing the creation. This may become a
	 *   "UserValue" in the future, or User may be refactored into such.
	 * @param AuthenticationResponse $response Authentication response that will be returned
	 */
	public function postAccountCreation( $user, $creator, AuthenticationResponse $response );

	/**
	 * Determine whether an account may be created
	 *
	 * @param User $user User being created (not added to the database yet).
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param bool|string $autocreate False if this is not an auto-creation, or
	 *  the source of the auto-creation passed to AuthManager::autoCreateUser().
	 * @return StatusValue
	 */
	public function testUserForCreation( $user, $autocreate );

	/**
	 * Post-auto-creation callback
	 * @param User $user User being created (has been added to the database now).
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param string $source The source of the auto-creation passed to
	 *  AuthManager::autoCreateUser().
	 */
	public function autoCreatedAccount( $user, $source );

	/**
	 * Start linking an account to an existing user
	 * @param User $user User being linked.
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse Expected responses:
	 *  - PASS: The user is linked.
	 *  - FAIL: The user is not linked. Fail the linking process.
	 *  - ABSTAIN: These $reqs are not handled. Some other primary provider may handle it.
	 *  - UI: The $reqs are accepted, no other primary provider will run.
	 *    Additional AuthenticationRequests are needed to complete the process.
	 *  - REDIRECT: The $reqs are accepted, no other primary provider will run.
	 *    Redirection to a third party is needed to complete the process.
	 */
	public function beginPrimaryAccountLink( $user, array $reqs );

	/**
	 * Continue linking an account to an existing user
	 * @param User $user User being linked.
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse Expected responses:
	 *  - PASS: The user is linked.
	 *  - FAIL: The user is not linked. Fail the linking process.
	 *  - UI: Additional AuthenticationRequests are needed to complete the process.
	 *  - REDIRECT: Redirection to a third party is needed to complete the process.
	 */
	public function continuePrimaryAccountLink( $user, array $reqs );

	/**
	 * Post-link callback
	 * @param User $user User that was attempted to be linked.
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param AuthenticationResponse $response Authentication response that will be returned
	 */
	public function postAccountLink( $user, AuthenticationResponse $response );

}
