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
class LocalPasswordPrimaryAuthenticationProvider extends AbstractPasswordPrimaryAuthenticationProvider {

	/** @var bool If true, this instance is for legacy logins only. */
	protected $loginOnly = false;

	/**
	 * @param array $params Settings
	 *  - loginOnly: If true, the local passwords are for legacy logins only:
	 *    the local password will be invalidated when authentication is changed
	 *    and new users will not have a valid local password set.
	 */
	public function __construct( $params = array() ) {
		parent::__construct( $params );
		$this->loginOnly = !empty( $params['loginOnly'] );
	}

	/**
	 * @param string $username
	 * @param array $row Database row
	 * @return stdClass|null
	 */
	protected function getPasswordResetData( $username, $row ) {
		/// @todo: The expiry logic should be moved out of User to here
		$expired = User::newFromRow( $row )->getPasswordExpired();
		if ( $expired === 'hard' ) {
			return (object)array(
				'hard' => true,
				'msg' => Status::newFatal( 'resetpass-expired' )->getMessage(),
			);
		}
		if ( $expired === 'soft' ) {
			return (object)array(
				'hard' => false,
				'msg' => Status::newFatal( 'resetpass-expired-soft' )->getMessage(),
			);
		}

		return null;
	}

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
			array(
				'user_id', 'user_password', 'user_password_expires',
				// Needed to keep User from blowing up, for now anyway.
				'user_newpassword', 'user_newpass_time',
			),
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

		// Check for *really* old password hashes that don't even have a type
		// The old hash format was just an md5 hex hash, with no type information
		if ( preg_match( '/^[0-9a-f]{32}$/', $row->user_password ) ) {
			if ( $this->config->get( 'PasswordSalt' ) ) {
				$row->user_password = ":A:{$row->user_id}:{$row->user_password}";
			} else {
				$row->user_password = ":A:{$row->user_password}";
			}
		}

		$status = $this->checkPasswordValidity( $req->username, $req->password );
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
				if ( $cp1252Password === $req->password || !$pwhash->equals( $cp1252Password ) ) {
					return $this->failResponse( $req );
				}
			} else {
				return $this->failResponse( $req );
			}
		}

		// @codeCoverageIgnoreStart
		if ( $this->getPasswordFactory()->needsUpdate( $pwhash ) ) {
			$pwhash = $this->getPasswordFactory()->newFromPlaintext( $req->password );
			$dbw->update(
				'user',
				array( 'user_password' => $pwhash->toString() ),
				array( 'user_id' => $row->user_id ),
				__METHOD__
			);
		}
		// @codeCoverageIgnoreEnd

		$this->setPasswordResetFlag( $req->username, $status, $row );

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
		// We only want to blank the password if something else will accept the
		// new authentication data, so return 'ignore' here.
		if ( $this->loginOnly ) {
			return StatusValue::newGood( 'ignored' );
		}

		if ( $req instanceof PasswordAuthenticationRequest && $req->username !== null && $req->password !== null ) {
			$row = wfGetDB( DB_MASTER )->selectRow(
				'user',
				array( 'user_id' ),
				array( 'user_name' => $req->username ),
				__METHOD__
			);
			if ( $row ) {
				$sv = StatusValue::newGood();
				if ( !$this->loginOnly ) {
					$sv->merge(
						$this->checkPasswordValidity( $req->username, $req->password )
					);
				}
				return $sv;
			}
		}

		return StatusValue::newGood( 'ignored' );
	}

	/**
	 * @param AuthenticationRequest $req
	 */
	public function providerChangeAuthenticationData( AuthenticationRequest $req ) {
		$pwhash = null;

		if ( $this->loginOnly ) {
			$pwhash = $this->getPasswordFactory()->newFromCiphertext( null );
		} elseif ( $req instanceof PasswordAuthenticationRequest && $req->username !== null ) {
			$pwhash = $this->getPasswordFactory()->newFromPlaintext( $req->password );
		}

		if ( $pwhash ) {
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
		return $this->loginOnly ? self::TYPE_NONE : self::TYPE_CREATE;
	}

	/**
	 * @param User $user
	 * @param User $creator
	 * @param AuthenticationRequest[] $reqs
	 * @return StatusValue
	 */
	public function testForAccountCreation( $user, $creator, array $reqs ) {
		$ret = StatusValue::newGood();
		if ( !$this->loginOnly && isset( $reqs['PasswordAuthenticationRequest'] ) ) {
			$req = $reqs['PasswordAuthenticationRequest'];
			$ret->merge(
				$this->checkPasswordValidity( $req->username, $req->password )
			);
		}
		return $ret;
	}

	/**
	 * @param User $user
	 * @param AuthenticationRequest[] $reqs
	 * @return AuthenticationResponse
	 */
	public function beginPrimaryAccountCreation( $user, array $reqs ) {
		if ( $this->accountCreationType() === self::TYPE_NONE ) {
			throw new BadMethodCallException( 'Shouldn\'t call this when accountCreationType() is NONE' );
		}

		if ( isset( $reqs['PasswordAuthenticationRequest'] ) ) {
			$req = $reqs['PasswordAuthenticationRequest'];
			if ( $req->username !== null && $req->password !== null ) {
				// Nothing we can do besides claim it, because the user isn't in
				// the DB yet
				if ( $req->username !== $user->getName() ) {
					$req = clone( $req );
					$req->username = $user->getName();
				}
				return AuthenticationResponse::newPass( $req->username, $req );
			}
		}
		return AuthenticationResponse::newAbstain();
	}

	/**
	 * @param User $user
	 * @param AuthenticationResponse $response
	 */
	public function finishAccountCreation( $user, AuthenticationResponse $res ) {
		if ( $this->accountCreationType() === self::TYPE_NONE ) {
			throw new BadMethodCallException( 'Shouldn\'t call this when accountCreationType() is NONE' );
		}

		// Now that the user is in the DB, set the password on it.
		$this->providerChangeAuthenticationData( $res->createRequest );
	}

}
