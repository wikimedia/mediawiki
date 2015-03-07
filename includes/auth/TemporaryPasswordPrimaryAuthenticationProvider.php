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
 * A primary authentication provider that uses the temporary password field in
 * the 'user' table.
 *
 * A successful login will force a password reset.
 *
 * @ingroup Auth
 * @since 1.26
 */
class TemporaryPasswordPrimaryAuthenticationProvider extends AbstractPasswordPrimaryAuthenticationProvider {

	protected function getPasswordResetData( $username, $data ) {
		// Always reset
		return (object)array(
			'msg' => wfMessage( 'resetpass-temp-emailed' ),
			'hard' => true,
		);
	}

	public function getAuthenticationRequestTypes( $which ) {
		switch ( $which ) {
			case 'login':
				return array( 'PasswordAuthenticationRequest' );

			case 'change':
			case 'create':
				return array( 'TemporaryPasswordAuthenticationRequest' );

			case 'all':
				return array(
					'PasswordAuthenticationRequest',
					'TemporaryPasswordAuthenticationRequest'
				);

			default:
				return array();
		}
	}

	public function beginPrimaryAuthentication( array $reqs ) {
		if ( !isset( $reqs['PasswordAuthenticationRequest'] ) ) {
			return AuthenticationResponse::newAbstain();
		}

		$req = $reqs['PasswordAuthenticationRequest'];
		if ( $req->username === null || $req->password === null ) {
			return AuthenticationResponse::newAbstain();
		}

		$dbw = wfGetDB( DB_MASTER );
		$row = $dbw->selectRow(
			'user',
			array(
				'user_id', 'user_newpassword', 'user_newpass_time',
			),
			array( 'user_name' => $req->username ),
			__METHOD__
		);
		if ( !$row ) {
			return AuthenticationResponse::newAbstain();
		}

		$status = $this->checkPasswordValidity( $req->username, $req->password );
		if ( !$status->isOk() ) {
			// Fatal, can't log in
			return AuthenticationResponse::newFail( $status->getMessage() );
		}

		$pwhash = $this->getPassword( $row->user_newpassword );
		if ( !$pwhash->equals( $req->password ) ) {
			return $this->failResponse( $req );
		}

		$time = wfTimestampOrNull( TS_MW, $row->user_newpass_time );
		if ( $time !== null ) {
			$expiry = wfTimestamp( TS_UNIX, $time ) + $this->config->get( 'NewPasswordExpiry' );
			if ( time() >= $expiry ) {
				return $this->failResponse( $req );
			}
		}

		$this->setPasswordResetFlag( $req->username, $status );

		return AuthenticationResponse::newPass( $req->username, $req );
	}

	public function testUserCanAuthenticate( $username ) {
		$dbw = wfGetDB( DB_MASTER );
		$row = $dbw->selectRow(
			'user',
			array( 'user_newpassword', 'user_newpass_time' ),
			array( 'user_name' => $username ),
			__METHOD__
		);
		if ( !$row ) {
			return false;
		}

		if ( $this->getPassword( $row->user_newpassword ) instanceof InvalidPassword ) {
			return false;
		}

		$time = wfTimestampOrNull( TS_MW, $row->user_newpass_time );
		if ( $time !== null ) {
			$expiry = wfTimestamp( TS_UNIX, $time ) + $this->config->get( 'NewPasswordExpiry' );
			if ( time() >= $expiry ) {
				return false;
			}
		}

		return true;
	}

	public function testUserExists( $username ) {
		return (bool)wfGetDB( DB_SLAVE )->selectField(
			array( 'user' ),
			array( 'user_id' ),
			array( 'user_name' => $username ),
			__METHOD__
		);
	}

	public function providerAllowsAuthenticationDataChange( AuthenticationRequest $req ) {
		if ( $req instanceof TemporaryPasswordAuthenticationRequest && $req->username !== null && $req->password !== null ) {
			$row = wfGetDB( DB_MASTER )->selectRow(
				'user',
				array( 'user_id' ),
				array( 'user_name' => $req->username ),
				__METHOD__
			);
			if ( $row ) {
				$sv = StatusValue::newGood();
				$sv->merge( $this->checkPasswordValidity( $req->username, $req->password ) );
				return $sv;
			}
		}
		return StatusValue::newGood( 'ignored' );
	}

	public function providerChangeAuthenticationData( AuthenticationRequest $req ) {
		if ( $req instanceof TemporaryPasswordAuthenticationRequest && $req->username !== null ) {
			$pwhash = $this->getPasswordFactory()->newFromPlaintext( $req->password );
		} else {
			// Invalidate the temporary password when any other auth is reset
			$pwhash = $this->getPasswordFactory()->newFromCiphertext( null );
		}

		$dbw = wfGetDB( DB_MASTER );
		$dbw->update(
			'user',
			array(
				'user_newpassword' => $pwhash->toString(),
				'user_newpass_time' => $dbw->timestamp(),
			),
			array( 'user_name' => $req->username ),
			__METHOD__
		);
	}

	public function accountCreationType() {
		return self::TYPE_CREATE;
	}

	public function testForAccountCreation( $user, $creator, array $reqs ) {
		$ret = StatusValue::newGood();
		if ( isset( $reqs['TemporaryPasswordAuthenticationRequest'] ) ) {
			$req = $reqs['TemporaryPasswordAuthenticationRequest'];
			$ret->merge(
				$this->checkPasswordValidity( $req->username, $req->password )
			);
		}
		return $ret;
	}

	public function beginPrimaryAccountCreation( $user, array $reqs ) {
		if ( isset( $reqs['TemporaryPasswordAuthenticationRequest'] ) ) {
			$req = $reqs['TemporaryPasswordAuthenticationRequest'];
			if ( $req->username !== null && $req->password !== null ) {
				// Nothing we can do yet, because the user isn't in the DB yet
				if ( $req->username !== $user->getName() ) {
					$req = clone( $req );
					$req->username = $user->getName();
				}
				return AuthenticationResponse::newPass( $req->username, $req );
			}
		}
		return AuthenticationResponse::newAbstain();
	}

	public function finishAccountCreation( $user, AuthenticationResponse $res ) {
		// Now that the user is in the DB, set the password on it.
		$this->providerChangeAuthenticationData( $res->createRequest );
	}

}
