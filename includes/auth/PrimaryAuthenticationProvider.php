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
 * @since 1.25
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
	 * @param AuthManager $manager
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function beginAuthentication( AuthManager $manager, array $reqs );

	/**
	 * Continue an authentication flow
	 * @param AuthManager $manager
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function continueAuthentication( AuthManager $manager, array $reqs );

	/**
	 * Validate an authentication token
	 * @param string $username
	 * @param mixed $token
	 * @return bool
	 */
	public function validateAuthenticationToken( $username, $token );

	/**
	 * Invalidate an authentication token
	 *
	 * After this call, a call to validateAuthenticationToken with these
	 * parameters will return false.
	 *
	 * @param string $username
	 * @param mixed $token
	 */
	public function invalidateAuthenticationToken( $username, $token );

	/**
	 * Determine whether a username exists
	 * @param string $username
	 * @return bool
	 */
	public function userExists( $username );

	/**
	 * Determine whether a property can change
	 * @see AuthManager::allowPropertyChange()
	 * @param string $property
	 * @return bool
	 */
	public function allowPropertyChange( $property );

	/**
	 * Validate a change of authentication data (e.g. passwords)
	 *
	 * Return a 'good' status if you don't support this AuthenticationRequest
	 * type.
	 *
	 * @param AuthenticationRequest $req
	 * @return StatusValue
	 */
	public function canChangeAuthenticationData( AuthenticationRequest $req  );

	/**
	 * Change authentication data (e.g. passwords)
	 *
	 * If the provider supports the AuthenticationRequest type, using $req
	 * should result in a successful login in the future.
	 *
	 * @param AuthenticationRequest $req
	 */
	public function changeAuthenticationData( AuthenticationRequest $req  );

	/**
	 * Fetch the account-creation type
	 * @return string One of the TYPE_* constants
	 */
	public function accountCreationType();

	/**
	 * Start an account creation flow
	 * @param AuthManager $manager
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function beginAccountCreation( AuthManager $manager, array $reqs );

	/**
	 * Continue an authentication flow
	 * @param AuthManager $manager
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function continueAccountCreation( AuthManager $manager, array $reqs );

}
