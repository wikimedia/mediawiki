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
abstract class AbstractPasswordPrimaryAuthenticationProvider extends AbstractAuthenticationProvider implements PrimaryAuthenticationProvider {
	/** @var bool Whether this provider should ABSTAIN (false) or FAIL (true) on password failure */
	protected $authoritative = true;

	private $passwordFactory = null;

	/**
	 * @param array $params Settings
	 *  - authoritative: Whether this provider should ABSTAIN (false) or FAIL
	 *    (true) on password failure
	 */
	public function __construct( $params = array() ) {
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
	 * Get a Password from the hash
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
	 * @param User $user
	 * @param string $password
	 * @return Status
	 */
	protected function checkPasswordValidity( User $user, $password ) {
		/// @todo: Move the logic from User to here.
		return $user->checkPasswordValidity( $password );
	}

	/**
	 * Check if the password should be reset
	 *
	 * This should be called after a successful login. It sets 'reset-pass'
	 * authentication data if necessary, see
	 * ResetPassSecondaryAuthenticationProvider.
	 *
	 * @param User $user
	 * @param Status $status From $this->checkPasswordValidity()
	 */
	protected function setPasswordResetFlag( User $user, Status $status ) {
		/// @todo: The expiry logic should be moved out of User too
		$expired = $user->getPasswordExpired();
		if ( $expired === 'hard' ) {
			$hard = true;
			$msg = Status::newFatal( 'resetpass-expired' )->getMessage();
		} elseif ( $expired === 'soft' ) {
			$hard = false;
			$msg = Status::newFatal( 'resetpass-expired-soft' )->getMessage();
		} elseif ( $this->config->get( 'InvalidPasswordReset' ) && !$status->isGood() ) {
			$msg = $status->getMessage( 'resetpass-validity-soft' );
			$hard = false;
		} else {
			return;
		}

		$this->manager->setAuthenticationData( 'reset-pass', (object)array(
			'msg' => $msg,
			'hard' => $hard,
		) );
	}

	/**
	 * @param string $which
	 * @return string[] AuthenticationRequest class names
	 */
	public function getAuthenticationRequestTypes( $which ) {
		switch ( $which ) {
			case 'login':
			case 'create':
			case 'change':
			case 'all':
				return array( 'PasswordAuthenticationRequest' );

			default:
				return array();
		}
	}

	/**
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	abstract public function beginPrimaryAuthentication( array $reqs );

	/**
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function continuePrimaryAuthentication( array $reqs ) {
		throw new BadMethodCallException( __METHOD__ . ' should never be reached.' );
	}

	/**
	 * @param string $username
	 * @return AuthnUserStatus
	 */
	abstract public function userStatus( $username );

	/**
	 * @param string $property
	 * @return bool
	 */
	public function providerAllowPropertyChange( $property ) {
		return true;
	}

	/**
	 * @param string $type AuthenticationRequest type
	 * @return bool False if attempts to change $type should be denied
	 */
	public function providerAllowChangingAuthenticationType( $type ) {
		return true;
	}

	/**
	 * @param AuthenticationRequest $req
	 * @return StatusValue
	 */
	abstract public function providerCanChangeAuthenticationData( AuthenticationRequest $req );

	/**
	 * @param AuthenticationRequest $req
	 */
	abstract public function providerChangeAuthenticationData( AuthenticationRequest $req );

	/**
	 * @return string One of the TYPE_* constants
	 */
	abstract public function accountCreationType();

	/**
	 * @param User $user
	 * @param User $creator
	 * @param AuthenticationRequest[] $reqs
	 * @return StatusValue
	 */
	abstract public function testForAccountCreation( $user, $creator, array $reqs );

	/**
	 * @param User $user
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	abstract public function beginPrimaryAccountCreation( $user, array $reqs );

	/**
	 * @param User $user
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function continuePrimaryAccountCreation( $user, array $reqs ) {
		throw new BadMethodCallException( __METHOD__ . ' should never be reached.' );
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
	 * Post-auto-creation callback
	 * @param User $user
	 */
	public function autoCreatedAccount( $user ) {
	}

}
