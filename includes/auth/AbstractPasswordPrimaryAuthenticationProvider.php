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
 * Basic framework for a primary authentication provider that uses passwords
 * @ingroup Auth
 * @since 1.26
 */
abstract class AbstractPasswordPrimaryAuthenticationProvider extends AbstractPrimaryAuthenticationProvider {
	/** @var bool Whether this provider should ABSTAIN (false) or FAIL (true) on password failure */
	protected $authoritative = true;

	private $passwordFactory = null;

	/**
	 * @param array $params Settings
	 *  - authoritative: Whether this provider should ABSTAIN (false) or FAIL
	 *    (true) on password failure
	 */
	public function __construct( array $params = array() ) {
		$this->authoritative = !isset( $params['authoritative'] ) || (bool)$params['authoritative'];
	}

	/**
	 * Get the PasswordFactory
	 * @return PasswordFactory
	 */
	protected function getPasswordFactory() {
		if ( $this->passwordFactory === null ) {
			$this->passwordFactory = new PasswordFactory();
			$this->passwordFactory->init( $this->config );
		}
		return $this->passwordFactory;
	}

	/**
	 * Get a Password object from the hash
	 * @param string $hash
	 * @return Password
	 */
	protected function getPassword( $hash ) {
		$passwordFactory = $this->getPasswordFactory();
		try {
			return $passwordFactory->newFromCiphertext( $hash );
		} catch ( PasswordError $e ) {
			$class = get_class( $this );
			$this->logger->debug( "Invalid password hash in {$class}::getPassword()" );
			return $passwordFactory->newFromCiphertext( null );
		}
	}

	/**
	 * Return the appropriate response for failure
	 * @param PasswordAuthenticationRequest $req
	 * @return AuthenticationResponse
	 */
	protected function failResponse( PasswordAuthenticationRequest $req ) {
		if ( $this->authoritative ) {
			return AuthenticationResponse::newFail(
				wfMessage( $req->password === '' ? 'wrongpasswordempty' : 'wrongpassword' )
			);
		} else {
			return AuthenticationResponse::newAbstain();
		}
	}

	/**
	 * Check that the password is valid
	 *
	 * This should be called *before* validating the password. If the result is
	 * not ok, login should fail immediately.
	 *
	 * @param string $username
	 * @param string $password
	 * @return Status
	 */
	protected function checkPasswordValidity( $username, $password ) {
		return User::newFromName( $username )->checkPasswordValidity( $password );
	}

	/**
	 * Check if the password should be reset
	 *
	 * This should be called after a successful login. It sets 'reset-pass'
	 * authentication data if necessary, see
	 * ResetPassSecondaryAuthenticationProvider.
	 *
	 * @param string $username
	 * @param Status $status From $this->checkPasswordValidity()
	 * @param mixed $data Passed through to $this->getPasswordResetData()
	 */
	protected function setPasswordResetFlag( $username, Status $status, $data = null ) {
		$reset = $this->getPasswordResetData( $username, $data );

		if ( !$reset && $this->config->get( 'InvalidPasswordReset' ) && !$status->isGood() ) {
			$reset = (object)array(
				'msg' => $status->getMessage( 'resetpass-validity-soft' ),
				'hard' => false,
			);
		}

		if ( $reset ) {
			$this->manager->setAuthenticationData( 'reset-pass', $reset );
		}
	}

	/**
	 * Get password reset data, if any
	 *
	 * @param string $username
	 * @param mixed $data
	 * @return stdClass|null { 'hard' => bool, 'msg' => MessageSpecifier }
	 */
	protected function getPasswordResetData( $username, $data ) {
		return null;
	}

	public function getAuthenticationRequestTypes( $which ) {
		switch ( $which ) {
			case AuthManager::ACTION_LOGIN:
			case AuthManager::ACTION_CREATE:
			case AuthManager::ACTION_CHANGE:
			case AuthManager::ACTION_ALL:
				return array( 'PasswordAuthenticationRequest' );

			default:
				return array();
		}
	}

	public function providerRevokeAccessForUser( $username ) {
		$req = new PasswordAuthenticationRequest;
		$req->username = $username;
		$req->password = null;
		$this->providerChangeAuthenticationData( $req );
	}

}
