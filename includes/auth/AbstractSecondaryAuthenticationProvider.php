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
 * @stable to extend
 * @ingroup Auth
 * @since 1.27
 */
abstract class AbstractSecondaryAuthenticationProvider extends AbstractAuthenticationProvider
	implements SecondaryAuthenticationProvider
{

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function continueSecondaryAuthentication( $user, array $reqs ) {
		throw new \BadMethodCallException( __METHOD__ . ' is not implemented.' );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function postAuthentication( $user, AuthenticationResponse $response ) {
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function providerAllowsPropertyChange( $property ) {
		return true;
	}

	/**
	 * @inheritDoc
	 * @stable to override
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

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function providerAllowsAuthenticationDataChange(
		AuthenticationRequest $req, $checkData = true
	) {
		return \StatusValue::newGood( 'ignored' );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function providerChangeAuthenticationData( AuthenticationRequest $req ) {
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function testForAccountCreation( $user, $creator, array $reqs ) {
		return \StatusValue::newGood();
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function continueSecondaryAccountCreation( $user, $creator, array $reqs ) {
		throw new \BadMethodCallException( __METHOD__ . ' is not implemented.' );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function postAccountCreation( $user, $creator, AuthenticationResponse $response ) {
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function testUserForCreation( $user, $autocreate, array $options = [] ) {
		return \StatusValue::newGood();
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function autoCreatedAccount( $user, $source ) {
	}
}
