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
 * @ingroup Auth
 */

/**
 * A base class that implements some of the boilerplate for a PrimaryAuthenticationProvider
 *
 * @ingroup Auth
 * @since 1.26
 */
abstract class AbstractPrimaryAuthenticationProvider extends AbstractAuthenticationProvider implements PrimaryAuthenticationProvider {

	/**
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function continuePrimaryAuthentication( array $reqs ) {
		throw new BadMethodCallException( __METHOD__ . ' is not implemented.' );
	}

	/**
	 * Assume it can authenticate if it exists
	 * @param string $username
	 * @return bool
	 */
	public function testUserCanAuthenticate( $username ) {
		return $this->testUserExists( $username );
	}

	/**
	 * @param string $property
	 * @return bool
	 */
	public function providerAllowsPropertyChange( $property ) {
		return true;
	}

	/**
	 * @param string $type AuthenticationRequest type
	 * @return bool False if attempts to change $type should be denied
	 */
	public function providerAllowsAuthenticationDataChangeType( $type ) {
		return true;
	}

	/**
	 * @param User $user
	 * @param User $creator
	 * @param AuthenticationRequest[] $reqs
	 * @return StatusValue
	 */
	public function testForAccountCreation( $user, $creator, array $reqs ) {
		return StatusValue::newGood();
	}

	/**
	 * @param User $user
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function continuePrimaryAccountCreation( $user, array $reqs ) {
		throw new BadMethodCallException( __METHOD__ . ' is not implemented.' );
	}

	/**
	 * @param User $user
	 * @param AuthenticationResponse PASS response returned earlier
	 */
	public function finishAccountCreation( $user, AuthenticationResponse $response ) {
	}

	/**
	 * @param User $user
	 * @return StatusValue
	 */
	public function testForAutoCreation( $user ) {
		return StatusValue::newGood();
	}

	/**
	 * @param User $user
	 */
	public function autoCreatedAccount( $user ) {
	}

}
