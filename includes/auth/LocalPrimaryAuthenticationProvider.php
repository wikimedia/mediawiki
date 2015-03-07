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
 * @since 1.26
 */
class LocalPrimaryAuthenticationProvider extends AbstractPasswordPrimaryAuthenticationProvider {
	/**
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function beginPrimaryAuthentication( array $reqs ) {
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
		$user = User::newFromRow( $row );

		$status = $this->checkPasswordValidity( $user, $req->password );
		if ( !$status->isOk() ) {
			// Fatal, can't log in
			return AuthenticationResponse::newFail( $status->getMessage() );
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

		$this->setPasswordResetFlag( $user, $status );

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
	 * @return StatusValue
	 */
	public function providerCanChangeAuthenticationData( AuthenticationRequest $req ) {
		if ( $req instanceof PasswordAuthenticationRequest && $req->username !== null ) {
			$row = wfGetDB( DB_MASTER )->selectRow(
				'user',
				array( 'user_id' ),
				array( 'user_name' => $req->username ),
				__METHOD__
			);
			if ( $row ) {
				$sv = StatusValue::newGood();
				$sv->merge(
					$this->checkPasswordValidity( User::newFromName( $req->username ), $req->password )
				);
				return $sv;
			}
		}

		return StatusValue::newGood( 'ignored' );
	}

	/**
	 * @param AuthenticationRequest $req
	 */
	public function providerChangeAuthenticationData( AuthenticationRequest $req ) {
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
			return $this->providerCanChangeAuthenticationData( $req );
		}
		return Status::newGood();
	}

	/**
	 * @param User $user
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function beginPrimaryAccountCreation( $user, array $reqs ) {
		if ( isset( $reqs['PasswordAuthenticationRequest'] ) ) {
			$req = $reqs['PasswordAuthenticationRequest'];
			$status = $this->providerCanChangeAuthenticationData( $req );
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
		$this->providerChangeAuthenticationData( $res->createRequest );
	}

}
