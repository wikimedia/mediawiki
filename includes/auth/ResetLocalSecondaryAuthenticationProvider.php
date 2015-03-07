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
 * Reset the local password, if signalled via the session.
 * @ingroup Auth
 * @since 1.25
 */
class ResetLocalSecondaryAuthenticationProvider extends AbstractAuthenticationProvider implements SecondaryAuthenticationProvider {

	/**
	 * @param string $which
	 * @return string[] AuthenticationRequest class names
	 */
	public function getAuthenticationRequestTypes( $which ) {
		switch ( $which ) {
			case 'login':
			case 'create':
			case 'create-continue':
			case 'change':
				return array();

			case 'login-continue':
				return (array)$this->getUIType();

			case 'all':
				return array(
					'SoftResetPasswordAuthenticationRequest',
					'HardResetPasswordAuthenticationRequest'
				);
		}
	}

	/**
	 * Determine the UI needed based on the session
	 * @return string|null AuthenticationRequest class name
	 */
	private function getUIType() {
		$data = $this->manager->getRequest()->getSessionData(
			'ResetLocalSecondaryAuthenticationProvider:data'
		);
		if ( !is_object( $data ) || $data->status->isGood() ) {
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
		$data = $this->manager->getRequest()->getSessionData(
			'ResetLocalSecondaryAuthenticationProvider:data'
		);

		$type = $this->getUIType();
		if ( !$type ) {
			return AuthenticationResponse::newAbstain();
		}

		if ( !isset( $reqs[$type] ) ) {
			return AuthenticationResponse::newUI(
				array( $type ),
				$data->status->getMessage( $data->wrap )
			);
		}

		$req = $reqs[$type];
		if ( !empty( $req->skip ) ) {
			if ( $data->hard ) {
				// Should never happen, but just in case...
				return AuthenticationResponse::newUI(
					array( $type ),
					$data->status->getMessage( $data->wrap )
				);
			}

			return AuthenticationResponse::newPass();
		}

		if ( $req->password !== $req->retype ) {
			return AuthenticationResponse::newUI(
				array( $type ),
				new Message( 'badretype' )
			);
		}

		$changeReq = new PasswordAuthenticationRequest();
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

		$this->manager->getRequest()->getSessionData(
			'ResetLocalSecondaryAuthenticationProvider:data', null
		);
		return AuthenticationResponse::newPass();
	}

	/**
	 * @param string $property
	 * @return bool
	 */
	public function allowPropertyChange( $property ) {
		return true;
	}

	/**
	 * @param AuthenticationRequest $req
	 * @return StatusValue
	 */
	public function canChangeAuthenticationData( AuthenticationRequest $req ) {
		return Status::newGood( 'ignored' );
	}

	/**
	 * @param User $user
	 * @param User $creator
	 * @param AuthenticationRequest[] $reqs
	 * @return StatusValue
	 */
	public function testForAccountCreation( $user, $creator, array $reqs ) {
		return Status::newGood( 'ignored' );
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
	 * @param User $creator
	 * @return StatusValue
	 */
	public function testForAutoCreation( $user, $creator ) {
		return Status::newGood( 'ignored' );
	}

	/**
	 * @param User $user
	 */
	public function autoCreatedAccount( $user ) {
	}

}
