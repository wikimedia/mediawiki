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
 * Pre-authentication providers can prevent authentication early on.
 *
 * A PreAuthenticationProvider is used to supply arbitrary checks to be
 * performed before the PrimaryAuthenticationProviders are consulted during the
 * login / account creation / account linking process. Possible uses include
 * checking that a per-IP throttle has not been reached or that a captcha has been solved.
 *
 * This interface also provides callbacks that are invoked after login / account creation
 * / account linking succeeded or failed.
 *
 * @ingroup Auth
 * @since 1.27
 * @see https://www.mediawiki.org/wiki/Manual:SessionManager_and_AuthManager
 */
interface PreAuthenticationProvider extends AuthenticationProvider {

	/**
	 * Determine whether an authentication may begin
	 *
	 * Called from AuthManager::beginAuthentication()
	 *
	 * @param AuthenticationRequest[] $reqs
	 * @return StatusValue
	 */
	public function testForAuthentication( array $reqs );

	/**
	 * Post-login callback
	 *
	 * This will be called at the end of a login attempt. It will not be called for unfinished
	 * login attempts that fail by the session timing out.
	 *
	 * @note Under certain circumstances, this can be called even when testForAuthentication
	 *   was not; see AuthenticationRequest::$loginRequest.
	 * @param User|null $user User that was attempted to be logged in, if known.
	 *   This may become a "UserIdentity" in the future.
	 * @param AuthenticationResponse $response Authentication response that will be returned
	 *   (PASS or FAIL)
	 */
	public function postAuthentication( $user, AuthenticationResponse $response );

	/**
	 * Determine whether an account creation may begin
	 *
	 * Called from AuthManager::beginAccountCreation()
	 *
	 * @note No need to test if the account exists, AuthManager checks that
	 * @param User $user User being created (not added to the database yet).
	 *   This may become a "UserIdentity" in the future.
	 * @param User $creator User doing the creation. This may become a "UserIdentity" in the future.
	 * @param AuthenticationRequest[] $reqs
	 * @return StatusValue
	 */
	public function testForAccountCreation( $user, $creator, array $reqs );

	/**
	 * Determine whether an account may be created
	 *
	 * @param User $user User being created (not added to the database yet).
	 *   This may become a "UserIdentity" in the future.
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
	 * Post-creation callback
	 *
	 * This will be called at the end of an account creation attempt. It will not be called if
	 * the account creation process results in a session timeout (possibly after a successful
	 * user creation, while a secondary provider is waiting for a response).
	 *
	 * @param User $user User that was attempted to be created.
	 *   This may become a "UserIdentity" in the future.
	 * @param User $creator User doing the creation. This may become a "UserIdentity" in the future.
	 * @param AuthenticationResponse $response Authentication response that will be returned
	 *   (PASS or FAIL)
	 */
	public function postAccountCreation( $user, $creator, AuthenticationResponse $response );

	/**
	 * Determine whether an account may linked to another authentication method
	 *
	 * @param User $user User being linked.
	 *   This may become a "UserIdentity" in the future.
	 * @return StatusValue
	 */
	public function testForAccountLink( $user );

	/**
	 * Post-link callback
	 *
	 * This will be called at the end of an account linking attempt.
	 *
	 * @param User $user User that was attempted to be linked.
	 *   This may become a "UserIdentity" in the future.
	 * @param AuthenticationResponse $response Authentication response that will be returned
	 *   (PASS or FAIL)
	 */
	public function postAccountLink( $user, AuthenticationResponse $response );

}
