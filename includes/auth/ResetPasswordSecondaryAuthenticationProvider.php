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
 * Reset the local password, if signalled via $this->manager->setAuthenticationSessionData()
 *
 * The authentication data key is 'reset-pass'; the data is an object with the
 * following properties:
 * - msg: Message object to display to the user
 * - hard: Boolean, if true the reset cannot be skipped.
 * - req: an optional AuthenticationRequest object, which will be used to generate the form.
 *   If omitted, a PasswordAuthenticationRequest is used.
 *
 * @ingroup Auth
 * @since 1.27
 */
class ResetPasswordSecondaryAuthenticationProvider extends AbstractSecondaryAuthenticationProvider {

	public function getAuthenticationRequests( $action ) {
		switch ( $action ) {
			case AuthManager::ACTION_LOGIN_CONTINUE:
				$request = $this->getResetRequest();
				return $request ? array( $request ) : array();

			default:
				return array();
		}
	}

	/**
	 * Returns the request which governs the password reset behavior.
	 * @return ResetPasswordAuthenticationRequest|null
	 */
	private function getResetRequest() {
		$data = $this->manager->getAuthenticationSessionData( 'reset-pass' );
		if ( !is_object( $data ) || !isset( $data->msg ) ) {
			return null;
		} else {
			return new ResetPasswordAuthenticationRequest( $data->hard );
		}
	}

	/**
	 * Returns the request which is used for the password change.
	 * @return AuthenticationRequest
	 */
	private function getChangeRequest() {
		$data = $this->manager->getAuthenticationSessionData( 'reset-pass' );
		if ( is_object( $data ) && isset( $data->req ) ) {
			return $data->req;
		} else {
			return new PasswordAuthenticationRequest();
		}
	}

	public function beginSecondaryAuthentication( $user, array $reqs ) {
		return $this->continueSecondaryAuthentication( $user, $reqs );
	}

	public function continueSecondaryAuthentication( $user, array $reqs ) {
		$data = $this->manager->getAuthenticationSessionData( 'reset-pass' );
		$emptyResetRequest = $this->getResetRequest();
		$emptyChangeRequest = $this->getChangeRequest();
		if ( !$emptyResetRequest ) {
			return AuthenticationResponse::newAbstain();
		}

		$resetRequest = AuthenticationRequest::getRequestByClass( $reqs,
			'ResetPasswordAuthenticationRequest' );
		$changeRequest = AuthenticationRequest::getRequestByClass( $reqs,
			get_class( $emptyChangeRequest ) );
		if ( !$resetRequest ) {
			return AuthenticationResponse::newUI(
				array( $emptyResetRequest, $emptyChangeRequest ),
				$data->msg
			);
		}


		if ( !empty( $resetRequest->skip ) ) {
			if ( $data->hard ) {
				// Should never happen, but just in case...
				return AuthenticationResponse::newUI(
					array( $emptyResetRequest, $emptyChangeRequest ),
					$data->msg
				);
			}

			$this->manager->removeAuthenticationSessionData( 'reset-pass' );
			return AuthenticationResponse::newPass();
		}

		if ( $resetRequest->password !== $resetRequest->retype ) {
			return AuthenticationResponse::newUI(
				array( $emptyResetRequest, $emptyChangeRequest ),
				new \Message( 'badretype' )
			);
		}

		$status = $this->manager->allowsAuthenticationDataChange( $changeRequest );
		if ( !$status->isGood() ) {
			return AuthenticationResponse::newUI(
				array( $emptyResetRequest, $emptyChangeRequest ),
				$status->getMessage()
			);
		}
		$this->manager->changeAuthenticationData( $changeRequest );

		$this->manager->removeAuthenticationSessionData( 'reset-pass' );
		return AuthenticationResponse::newPass();
	}

	public function beginSecondaryAccountCreation( $user, array $reqs ) {
		return AuthenticationResponse::newAbstain();
	}

}
