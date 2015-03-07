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
 * A base class that implements some of the boilerplate for a SecondaryAuthenticationProvider
 *
 * @ingroup Auth
 * @since 1.26
 */
abstract class AbstractSecondaryAuthenticationProvider extends AbstractAuthenticationProvider implements SecondaryAuthenticationProvider {

	/**
	 * @param User $user
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function continueSecondaryAuthentication( $user, array $reqs ) {
		throw new BadMethodCallException( __METHOD__ . ' is not implemented.' );
	}

	/**
	 * @param string $property
	 * @return bool
	 */
	public function providerAllowPropertyChange( $property ) {
		return true;
	}

	/**
	 * @param AuthenticationRequest $req
	 * @return StatusValue
	 */
	public function providerCanChangeAuthenticationData( AuthenticationRequest $req ) {
		return StatusValue::newGood( 'ignored' );
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
	public function continueSecondaryAccountCreation( $user, array $reqs ) {
		throw new BadMethodCallException( __METHOD__ . ' is not implemented.' );
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
