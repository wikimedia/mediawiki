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
	 * Must not return failure if $req was returned by
	 * $this->getAuthenticationRequests( AuthManager::ACTION_REMOVE ).
	 *
	 * @param AuthenticationRequest $req
	 * @param bool $checkData If false, $req hasn't been loaded from the
	 *  submission so checks on user-submitted fields should be skipped.
	 * @return StatusValue
	 */
	public function providerAllowsAuthenticationDataChange(
		AuthenticationRequest $req, $checkData = true
	);

	/**
	 * Change or remove authentication data (e.g. passwords)
	 *
	 * If $req was returned for AuthManager::ACTION_CHANGE, using $req should
	 * result in a successful login in the future.
	 *
	 * If $req was returned for AuthManager::ACTION_REMOVE, using $req should
	 * no longer result in a successful login.
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
	public function beginPrimaryAccountCreation( $user, array $reqs );

	/**
	 * Continue an account creation flow
	 * @param User $user User being created (not added to the database yet).
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse Expected responses:
	 *  - PASS: The user may be created. Secondary providers will now run.
	 *  - FAIL: The user may not be created. Fail the creation process.
	 *  - UI: Additional AuthenticationRequests are needed to complete the process.
	 *  - REDIRECT: Redirection to a third party is needed to complete the process.
	 */
	public function continuePrimaryAccountCreation( $user, array $reqs );

	/**
	 * Post-creation callback
	 * @param User $user User being created (has been added to the database now).
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param AuthenticationResponse $response PASS response returned earlier
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

}
