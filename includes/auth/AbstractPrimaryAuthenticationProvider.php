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

	public function continuePrimaryAuthentication( array $reqs ) {
		throw new BadMethodCallException( __METHOD__ . ' is not implemented.' );
	}

	public function testUserCanAuthenticate( $username ) {
		// Assume it can authenticate if it exists
		return $this->testUserExists( $username );
	}

	public function providerAllowsPropertyChange( $property ) {
		return true;
	}

	public function providerAllowsAuthenticationDataChangeType( $type ) {
		return true;
	}

	public function testForAccountCreation( $user, $creator, array $reqs ) {
		return StatusValue::newGood();
	}

	public function continuePrimaryAccountCreation( $user, array $reqs ) {
		throw new BadMethodCallException( __METHOD__ . ' is not implemented.' );
	}

	public function finishAccountCreation( $user, AuthenticationResponse $response ) {
	}

	public function testForAutoCreation( $user ) {
		return StatusValue::newGood();
	}

	public function autoCreatedAccount( $user ) {
	}

}
