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
 * @since 1.27
 */
class ResetPasswordSecondaryAuthenticationProvider extends AbstractSecondaryAuthenticationProvider {

	public function getAuthenticationRequests( $action ) {
		switch ( $action ) {
			case AuthManager::ACTION_LOGIN_CONTINUE:
				$request = $this->getRequest();
				return $request ? array( $request ) : array();

			default:
				return array();
		}
	}

	/**
	 * Determine the UI needed based on the session
	 * @return string|null AuthenticationRequest class name
	 */
	private function getRequest() {
		$data = $this->manager->getAuthenticationData( 'reset-pass' );
		if ( !is_object( $data ) || !isset( $data->msg ) ) {
			return null;
		} else {
			return $data->hard
				? new HardResetPasswordAuthenticationRequest()
				: new SoftResetPasswordAuthenticationRequest();
		}
	}

	public function beginSecondaryAuthentication( $user, array $reqs ) {
		return $this->continueSecondaryAuthentication( $user, $reqs );
	}

	public function continueSecondaryAuthentication( $user, array $reqs ) {
		$neededRequest = $this->getRequest();
		if ( !$neededRequest ) {
			return AuthenticationResponse::newAbstain();
		}

		$req = AuthenticationRequest::getRequestByClass( $reqs, get_class( $neededRequest ) );
		$data = $this->manager->getAuthenticationData( 'reset-pass' );
		if ( !$req ) {
			return AuthenticationResponse::newUI( array( $neededRequest ), $data->msg );
		}

		if ( !empty( $req->skip ) ) {
			if ( $data->hard ) {
				// Should never happen, but just in case...
				return AuthenticationResponse::newUI( array( $neededRequest ), $data->msg );
			}

			$this->manager->removeAuthenticationData( 'reset-pass' );
			return AuthenticationResponse::newPass();
		}

		if ( $req->password !== $req->retype ) {
			return AuthenticationResponse::newUI(
				array( $neededRequest ),
				new \Message( 'badretype' )
			);
		}

		if ( isset( $data->reqType ) ) {
			$neededRequest = new $data->reqType;
			$changeReq = new $data->reqType;
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
		$status = $this->manager->allowsAuthenticationDataChange( $changeReq );
		if ( !$status->isGood() ) {
			return AuthenticationResponse::newUI(
				array( $neededRequest ),
				$status->getMessage()
			);
		}
		$this->manager->changeAuthenticationData( $changeReq );

		$this->manager->removeAuthenticationData( 'reset-pass' );
		return AuthenticationResponse::newPass();
	}

	public function beginSecondaryAccountCreation( $user, array $reqs ) {
		return AuthenticationResponse::newAbstain();
	}

}
