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
 * Reset the local password, if signalled via $this->manager->setAuthenticationData()
 *
 * The authentication data key is 'reset-pass'; the data is an object with the
 * following properties:
 * - msg: Message object to display to the user
 * - hard: Boolean, if true the reset cannot be skipped.
 * - reqType: Optional string. AuthenticationRequest class to use
 * - reqData: Optional array. Data to set on reqType.
 *
 * @ingroup Auth
 * @since 1.26
 */
class ResetPasswordSecondaryAuthenticationProvider extends AbstractAuthenticationProvider implements SecondaryAuthenticationProvider {

	/**
	 * @param string $which
	 * @return string[] AuthenticationRequest class names
	 */
	public function getAuthenticationRequestTypes( $which ) {
		switch ( $which ) {
			case 'login-continue':
				return (array)$this->getUIType();

			case 'all':
				return array(
					'SoftResetPasswordAuthenticationRequest',
					'HardResetPasswordAuthenticationRequest'
				);

			default:
				return array();
		}
	}

	/**
	 * Determine the UI needed based on the session
	 * @return string|null AuthenticationRequest class name
	 */
	private function getUIType() {
		$data = $this->manager->getAuthenticationData( 'reset-pass' );
		if ( !is_object( $data ) || !isset( $data->msg ) ) {
			return null;
		} else {
			return $data->hard
				? 'HardResetPasswordAuthenticationRequest'
				: 'SoftResetPasswordAuthenticationRequest';
		}
	}

	/**
	 * @param User $user
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function beginSecondaryAuthentication( $user, array $reqs ) {
		return $this->continueSecondaryAuthentication( $user, $reqs );
	}

	/**
	 * @param User $user
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function continueSecondaryAuthentication( $user, array $reqs ) {
		$type = $this->getUIType();
		if ( !$type ) {
			return AuthenticationResponse::newAbstain();
		}

		$data = $this->manager->getAuthenticationData( 'reset-pass' );
		if ( !isset( $reqs[$type] ) ) {
			return AuthenticationResponse::newUI( array( $type ), $data->msg );
		}

		$req = $reqs[$type];
		if ( !empty( $req->skip ) ) {
			if ( $data->hard ) {
				// Should never happen, but just in case...
				return AuthenticationResponse::newUI( array( $type ), $data->msg );
			}

			$this->manager->removeAuthenticationData( 'reset-pass' );
			return AuthenticationResponse::newPass();
		}

		if ( $req->password !== $req->retype ) {
			return AuthenticationResponse::newUI(
				array( $type ),
				new Message( 'badretype' )
			);
		}

		if ( isset( $data->reqType ) ) {
			$type = $data->reqType;
			$changeReq = new $type();
			if ( isset( $data->reqData ) ) {
				foreach ( $data->reqData as $k => $v ) {
					$changeReq->$k = $v;
				}
			}
		} else {
			$changeReq = new PasswordAuthenticationRequest();
		}
		$changeReq->username = $user->getName();
		$changeReq->password = $req->password;
		$status = $this->manager->canChangeAuthenticationData( $changeReq );
		if ( !$status->isGood() ) {
			return AuthenticationResponse::newUI(
				array( $type ),
				$status->getMessage()
			);
		}
		$this->manager->changeAuthenticationData( $changeReq );

		$this->manager->removeAuthenticationData( 'reset-pass' );
		return AuthenticationResponse::newPass();
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
	public function beginSecondaryAccountCreation( $user, array $reqs ) {
		return AuthenticationResponse::newAbstain();
	}

	/**
	 * @param User $user
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function continueSecondaryAccountCreation( $user, array $reqs ) {
		return AuthenticationResponse::newAbstain();
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
