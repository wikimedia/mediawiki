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

/**
 * A pre-authentication provider is a check that must pass for authentication
 * to proceed.
 * @ingroup Auth
 * @since 1.25
 */
interface PreAuthenticationProvider extends AuthenticationProvider {

	/**
	 * Determine whether an authentication may begin
	 *
	 * Called from AuthManager::beginAuthentication()
	 *
	 * @param AuthManager $manager
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse PASS, FAIL, or ABSTAIN statuses only
	 */
	public function testForAuthentication( AuthManager $manager, array $reqs );

	/**
	 * Determine whether an account creation may begin
	 *
	 * Called from AuthManager::beginAccountCreation()
	 *
	 * @param AuthManager $manager
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse PASS, FAIL, or ABSTAIN statuses only
	 */
	public function testForAccountCreation( AuthManager $manager, array $reqs );

}
