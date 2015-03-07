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
