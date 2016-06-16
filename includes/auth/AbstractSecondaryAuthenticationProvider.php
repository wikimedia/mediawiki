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

namespace MediaWiki\Auth;

/**
 * A base class that implements some of the boilerplate for a SecondaryAuthenticationProvider
 *
 * @ingroup Auth
 * @since 1.27
 */
abstract class AbstractSecondaryAuthenticationProvider extends AbstractAuthenticationProvider
	implements SecondaryAuthenticationProvider
{

	public function continueSecondaryAuthentication( $user, array $reqs ) {
		throw new \BadMethodCallException( __METHOD__ . ' is not implemented.' );
	}

	public function postAuthentication( $user, AuthenticationResponse $response ) {
	}

	public function providerAllowsPropertyChange( $property ) {
		return true;
	}

	/**
	 * @inheritdoc
	 * @note Reimplement this if self::getAuthenticationRequests( AuthManager::ACTION_REMOVE )
	 *  doesn't return requests that will revoke all access for the user.
	 */
	public function providerRevokeAccessForUser( $username ) {
		$reqs = $this->getAuthenticationRequests(
			AuthManager::ACTION_REMOVE, [ 'username' => $username ]
		);
		foreach ( $reqs as $req ) {
			$req->username = $username;
			$this->providerChangeAuthenticationData( $req );
		}
	}

	public function providerAllowsAuthenticationDataChange(
		AuthenticationRequest $req, $checkData = true
	) {
		return \StatusValue::newGood( 'ignored' );
	}

	public function providerChangeAuthenticationData( AuthenticationRequest $req ) {
	}

	public function testForAccountCreation( $user, $creator, array $reqs ) {
		return \StatusValue::newGood();
	}

	public function continueSecondaryAccountCreation( $user, $creator, array $reqs ) {
		throw new \BadMethodCallException( __METHOD__ . ' is not implemented.' );
	}

	public function postAccountCreation( $user, $creator, AuthenticationResponse $response ) {
	}

	public function testUserForCreation( $user, $autocreate, array $options = [] ) {
		return \StatusValue::newGood();
	}

	public function autoCreatedAccount( $user, $source ) {
	}
}
