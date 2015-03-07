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
 * A primary authentication provider that uses the password field in the 'user' table.
 * @ingroup Auth
 * @since 1.25
 */
class LocalPrimaryAuthenticationProvider extends AbstractPasswordPrimaryAuthenticationProvider {
	/**
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function beginAuthentication( array $reqs ) {
		if ( !isset( $reqs['PasswordAuthenticationRequest'] ) ) {
			return AuthenticationResponse::newAbstain();
		}

		$req = $reqs['PasswordAuthenticationRequest'];
		if ( $req->username === null || $req->password === null ) {
			return AuthenticationResponse::newAbstain();
		}

		$fields = array_unique( array_merge(
			array( 'user_id', 'user_password', 'user_password_expires' ),
			User::selectFields()
		) );

		$dbw = wfGetDB( DB_MASTER );
		$row = $dbw->selectRow(
			'user',
			$fields,
			array( 'user_name' => $req->username ),
			__METHOD__
		);
		if ( !$row ) {
			return AuthenticationResponse::newAbstain();
		}

		$pwhash = $this->getPassword( $row->user_password );
		if ( !$pwhash->equals( $req->password ) ) {
			if ( $this->config->get( 'LegacyEncoding' ) ) {
				// Some wikis were converted from ISO 8859-1 to UTF-8, the passwords can't be converted
				// Check for this with iconv
				$cp1252Password = iconv( 'UTF-8', 'WINDOWS-1252//TRANSLIT', $req->password );
				if ( $cp1252Password === $password || !$pwhash->equals( $cp1252Password ) ) {
					return $this->failResponse( $req );
				}
			} else {
				return $this->failResponse( $req );
			}
		}

		if ( $this->getPasswordFactory()->needsUpdate( $pwhash ) ) {
			$pwhash = $this->getPasswordFactory()->newFromPlaintext( $req->password );
			$dbw->update(
				'user',
				array( 'user_password' => $pwhash->toString() ),
				array( 'user_id' => $row->user_id ),
				__METHOD__
			);
		}

		// Signal the associated SecondaryAuthenticationProvider.
		/// @todo: This logic should probably be moved out of User, probably to here.
		$user = User::newFromRow( $row );
		if ( $user->getPasswordExpired() === 'hard' ) {
			$wrap = null;
			$hard = true;
			$status = Status::newFatal( 'resetpass-expired' );
		} elseif ( $user->getPasswordExpired() === 'soft' ) {
			$wrap = null;
			$hard = false;
			$status = Status::newFatal( 'resetpass-expired-soft' );
		} elseif ( $this->config->get( 'InvalidPasswordReset' ) &&
			!$user->isValidPassword( $req->password )
		) {
			$wrap = 'resetpass-validity-soft';
			$hard = false;
			$status = $user->checkPasswordValidity();
		} else {
			$wrap = null;
			$hard = false;
			$status = Status::newGood();
		}
		$this->manager->getRequest()->setSessionData(
			'ResetLocalSecondaryAuthenticationProvider:data', (object)array(
				'status' => $status,
				'wrap' => $wrap,
				'hard' => $hard,
			)
		);

		return AuthenticationResponse::newPass( $req->username, $req );
	}

	/**
	 * @param string $username
	 * @return AuthnUserStatus
	 */
	public function userStatus( $username ) {
		$row = wfGetDB( DB_SLAVE )->selectRow(
			array( 'user', 'ipblocks' ),
			array( 'user_id', 'ipb_id', 'ipb_deleted' ),
			array( 'user_name' => $username ),
			__METHOD__,
			array(),
			array(
				'ipblocks' => array( 'LEFT JOIN', 'ipb_user=user_id' ),
			)
		);
		if ( !$row ) {
			return AuthnUserStatus::newDefaultStatus();
		} else {
			return new AuthnUserStatus( array(
				'exists' => true,
				'locked' => $row->ipb_id !== null,
				'hidden' => (bool)$row->ipb_deleted,
			) );
		}
	}

	/**
	 * @param AuthenticationRequest $req
	 * @return Status
	 */
	public function canChangeAuthenticationData( AuthenticationRequest $req ) {
		if ( $req instanceof PasswordAuthenticationRequest && $req->username !== null ) {
			$row = wfGetDB( DB_MASTER )->selectRow(
				'user',
				array( 'user_id' ),
				array( 'user_name' => $req->username ),
				__METHOD__
			);
			if ( $row ) {
				// Does that code belong here rather than in User?
				return User::newFromName( $req->username )->checkPasswordValidity( $req->password );
			}
		}

		return Status::newGood( 'ignored' );
	}

	/**
	 * @param AuthenticationRequest $req
	 */
	public function changeAuthenticationData( AuthenticationRequest $req ) {
		if ( $req instanceof PasswordAuthenticationRequest && $req->username !== null ) {
			$pwhash = $this->getPasswordFactory()->newFromPlaintext( $req->password );
			wfGetDB( DB_MASTER )->update(
				'user',
				array( 'user_password' => $pwhash->toString() ),
				array( 'user_name' => $req->username ),
				__METHOD__
			);
		}
	}

	/**
	 * @return string One of the TYPE_* constants
	 */
	public function accountCreationType() {
		return self::TYPE_CREATE;
	}

	/**
	 * @param User $user
	 * @param User $creator
	 * @param AuthenticationRequest[] $reqs
	 * @return Status
	 */
	public function testForAccountCreation( $user, $creator, array $reqs ) {
		if ( isset( $reqs['PasswordAuthenticationRequest'] ) ) {
			$req = $reqs['PasswordAuthenticationRequest'];
			return $this->canChangeAuthenticationData( $req );
		}
		return Status::newGood();
	}

	/**
	 * @param User $user
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function beginAccountCreation( $user, array $reqs ) {
		if ( isset( $reqs['PasswordAuthenticationRequest'] ) ) {
			$req = $reqs['PasswordAuthenticationRequest'];
			$status = $this->canChangeAuthenticationData( $req );
			if ( $status->isGood() ) {
				if ( $status->value === 'ignored' ) {
					return AuthenticationResponse::newAbstain();
				} else {
					// Nothing we can do yet, because the user isn't in the DB yet
					return AuthenticationResponse::newPass( $req->username, $req );
				}
			} else {
				return AuthenticationResponse::newFail( $status->getMessage() );
			}
		}
		return AuthenticationResponse::newAbstain();
	}

	/**
	 * @param User $user
	 * @param AuthenticationResponse $response
	 */
	public function finishAccountCreation( $user, AuthenticationResponse $res ) {
		// Now that the user is in the DB, set the password on it.
		$this->changeAuthenticationData( $res->createReq );
	}

}
