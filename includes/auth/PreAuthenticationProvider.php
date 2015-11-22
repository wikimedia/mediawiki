<?php
/**
 * Pre-authentication provider interface
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
 * A pre-authentication provider is a check that must pass for authentication
 * to proceed.
 *
 * A PreAuthenticationProvider is used to supply arbitrary checks to be
 * performed before the PrimaryAuthenticationProviders are consulted during the
 * login process. Possible uses include checking that a per-IP throttle has not
 * been reached or that a captcha has been solved.
 *
 * @ingroup Auth
 * @since 1.27
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
	 * @param User|null $user User that was attempted to be logged in, if known.
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param AuthenticationResponse $response Authentication response that will be returned
	 */
	public function postAuthentication( $user, AuthenticationResponse $response );

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
	 * Post-creation callback
	 * @param User $user User that was attempted to be created.
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param User $creator User doing the creation. This may become a
	 *   "UserValue" in the future, or User may be refactored into such.
	 * @param AuthenticationResponse $response Authentication response that will be returned
	 */
	public function postAccountCreation( $user, $creator, AuthenticationResponse $response );

	/**
	 * Determine whether an account may linked to another authentication method
	 *
	 * @param User $user User being linked.
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @return StatusValue
	 */
	public function testForAccountLink( $user );

	/**
	 * Post-link callback
	 * @param User $user User that was attempted to be linked.
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param AuthenticationResponse $response Authentication response that will be returned
	 */
	public function postAccountLink( $user, AuthenticationResponse $response );

}
