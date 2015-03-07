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
 * @since 1.25
 */
interface SecondaryAuthenticationProvider extends AuthenticationProvider {

	/**
	 * Start an authentication flow
	 * @param AuthManager $manager
	 * @param User $user User being authenticated. This may become a
	 *   "UserValue" in the future, or User may be refactored into such.
	 * @param AuthenticationRequest[] $reqs Keys are class names
	 * @return AuthenticationResponse
	 */
	public function beginSecondaryAuthentication( AuthManager $manager, $user, array $reqs );

	/**
	 * Continue an authentication flow
	 * @param AuthManager $manager
	 * @param User $user User being authenticated. This may become a
	 *   "UserValue" in the future, or User may be refactored into such.
	 * @param AuthenticationRequest[] $reqs Keys are class names
	 * @return AuthenticationResponse
	 */
	public function continueSecondaryAuthentication( AuthManager $manager, $user, array $reqs );

	/**
	 * Determine whether a property can change
	 * @see AuthManager::allowPropertyChange()
	 * @param string $property
	 * @return bool
	 */
	public function allowPropertyChange( $property );

	/**
	 * Validate a change of authentication data (e.g. passwords)
	 * @param AuthenticationRequest $req
	 * @return StatusValue
	 */
	public function canChangeAuthenticationData( AuthenticationRequest $req  );

	/**
	 * Start an account creation flow
	 * @param AuthManager $manager
	 * @param User $user User being created. This may become a "UserValue" in
	 *   the future, or User may be refactored into such.
	 * @param AuthenticationRequest[] $reqs Keys are class names
	 * @return AuthenticationResponse
	 */
	public function beginSecondaryAccountCreation( AuthManager $manager, $user, array $reqs );

	/**
	 * Continue an authentication flow
	 * @param AuthManager $manager
	 * @param User $user User being created. This may become a "UserValue" in
	 *   the future, or User may be refactored into such.
	 * @param AuthenticationRequest[] $reqs Keys are class names
	 * @return AuthenticationResponse
	 */
	public function continueSecondaryAccountCreation( AuthManager $manager, $user, array $reqs );

}
