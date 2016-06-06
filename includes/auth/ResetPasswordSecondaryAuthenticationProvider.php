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
 * - req: Optional PasswordAuthenticationRequest to use to actually reset the
 *   password. Won't be displayed to the user.
 *
 * @ingroup Auth
 * @since 1.27
 */
class ResetPasswordSecondaryAuthenticationProvider extends AbstractSecondaryAuthenticationProvider {

	public function getAuthenticationRequests( $action, array $options ) {
		return [];
	}

	public function beginSecondaryAuthentication( $user, array $reqs ) {
		return $this->tryReset( $user, $reqs );
	}

	public function continueSecondaryAuthentication( $user, array $reqs ) {
		return $this->tryReset( $user, $reqs );
	}

	public function beginSecondaryAccountCreation( $user, $creator, array $reqs ) {
		return $this->tryReset( $user, $reqs );
	}

	public function continueSecondaryAccountCreation( $user, $creator, array $reqs ) {
		return $this->tryReset( $user, $reqs );
	}

	/**
	 * Try to reset the password
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	protected function tryReset( \User $user, array $reqs ) {
		$data = $this->manager->getAuthenticationSessionData( 'reset-pass' );
		if ( !$data ) {
			return AuthenticationResponse::newAbstain();
		}

		if ( is_array( $data ) ) {
			$data = (object)$data;
		}
		if ( !is_object( $data ) ) {
			throw new \UnexpectedValueException( 'reset-pass is not valid' );
		}

		if ( !isset( $data->msg ) ) {
			throw new \UnexpectedValueException( 'reset-pass msg is missing' );
		} elseif ( !$data->msg instanceof \Message ) {
			throw new \UnexpectedValueException( 'reset-pass msg is not valid' );
		} elseif ( !isset( $data->hard ) ) {
			throw new \UnexpectedValueException( 'reset-pass hard is missing' );
		} elseif ( isset( $data->req ) && (
			!$data->req instanceof PasswordAuthenticationRequest ||
			!array_key_exists( 'retype', $data->req->getFieldInfo() )
		) ) {
			throw new \UnexpectedValueException( 'reset-pass req is not valid' );
		}

		if ( !$data->hard ) {
			$req = ButtonAuthenticationRequest::getRequestByName( $reqs, 'skipReset' );
			if ( $req ) {
				$this->manager->removeAuthenticationSessionData( 'reset-pass' );
				return AuthenticationResponse::newPass();
			}
		}

		$needReq = isset( $data->req ) ? $data->req : new PasswordAuthenticationRequest();
		if ( !$needReq->action ) {
			$needReq->action = AuthManager::ACTION_CHANGE;
		}
		$needReq->required = $data->hard ? AuthenticationRequest::REQUIRED
			: AuthenticationRequest::OPTIONAL;
		$needReqs = [ $needReq ];
		if ( !$data->hard ) {
			$needReqs[] = new ButtonAuthenticationRequest(
				'skipReset',
				wfMessage( 'authprovider-resetpass-skip-label' ),
				wfMessage( 'authprovider-resetpass-skip-help' )
			);
		}

		$req = AuthenticationRequest::getRequestByClass( $reqs, get_class( $needReq ) );
		if ( !$req || !array_key_exists( 'retype', $req->getFieldInfo() ) ) {
			return AuthenticationResponse::newUI( $needReqs, $data->msg );
		}

		if ( $req->password !== $req->retype ) {
			return AuthenticationResponse::newUI( $needReqs, new \Message( 'badretype' ) );
		}

		$req->username = $user->getName();
		$status = $this->manager->allowsAuthenticationDataChange( $req );
		if ( !$status->isGood() ) {
			return AuthenticationResponse::newUI( $needReqs, $status->getMessage() );
		}
		$this->manager->changeAuthenticationData( $req );

		$this->manager->removeAuthenticationSessionData( 'reset-pass' );
		return AuthenticationResponse::newPass();
	}
}
