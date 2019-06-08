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

use User;

/**
 * A primary authentication provider that uses the password field in the 'user' table.
 * @ingroup Auth
 * @since 1.27
 */
class LocalPasswordPrimaryAuthenticationProvider
	extends AbstractPasswordPrimaryAuthenticationProvider
{

	/** @var bool If true, this instance is for legacy logins only. */
	protected $loginOnly = false;

	/**
	 * @param array $params Settings
	 *  - loginOnly: If true, the local passwords are for legacy logins only:
	 *    the local password will be invalidated when authentication is changed
	 *    and new users will not have a valid local password set.
	 */
	public function __construct( $params = [] ) {
		parent::__construct( $params );
		$this->loginOnly = !empty( $params['loginOnly'] );
	}

	/**
	 * Check if the password has expired and needs a reset
	 *
	 * @param string $username
	 * @param \stdClass $row A row from the user table
	 * @return \stdClass|null
	 */
	protected function getPasswordResetData( $username, $row ) {
		$now = wfTimestamp();
		$expiration = wfTimestampOrNull( TS_UNIX, $row->user_password_expires );
		if ( $expiration === null || $expiration >= $now ) {
			return null;
		}

		$grace = $this->config->get( 'PasswordExpireGrace' );
		if ( $expiration + $grace < $now ) {
			$data = [
				'hard' => true,
				'msg' => \Status::newFatal( 'resetpass-expired' )->getMessage(),
			];
		} else {
			$data = [
				'hard' => false,
				'msg' => \Status::newFatal( 'resetpass-expired-soft' )->getMessage(),
			];
		}

		return (object)$data;
	}

	public function beginPrimaryAuthentication( array $reqs ) {
		$req = AuthenticationRequest::getRequestByClass( $reqs, PasswordAuthenticationRequest::class );
		if ( !$req ) {
			return AuthenticationResponse::newAbstain();
		}

		if ( $req->username === null || $req->password === null ) {
			return AuthenticationResponse::newAbstain();
		}

		$username = User::getCanonicalName( $req->username, 'usable' );
		if ( $username === false ) {
			return AuthenticationResponse::newAbstain();
		}

		$fields = [
			'user_id', 'user_password', 'user_password_expires',
		];

		$dbr = wfGetDB( DB_REPLICA );
		$row = $dbr->selectRow(
			'user',
			$fields,
			[ 'user_name' => $username ],
			__METHOD__
		);
		if ( !$row ) {
			// Do not reveal whether its bad username or
			// bad password to prevent username enumeration
			// on private wikis. (T134100)
			return $this->failResponse( $req );
		}

		$oldRow = clone $row;
		// Check for *really* old password hashes that don't even have a type
		// The old hash format was just an md5 hex hash, with no type information
		if ( preg_match( '/^[0-9a-f]{32}$/', $row->user_password ) ) {
			if ( $this->config->get( 'PasswordSalt' ) ) {
				$row->user_password = ":B:{$row->user_id}:{$row->user_password}";
			} else {
				$row->user_password = ":A:{$row->user_password}";
			}
		}

		$status = $this->checkPasswordValidity( $username, $req->password );
		if ( !$status->isOK() ) {
			// Fatal, can't log in
			return AuthenticationResponse::newFail( $status->getMessage() );
		}

		$pwhash = $this->getPassword( $row->user_password );
		if ( !$pwhash->verify( $req->password ) ) {
			if ( $this->config->get( 'LegacyEncoding' ) ) {
				// Some wikis were converted from ISO 8859-1 to UTF-8, the passwords can't be converted
				// Check for this with iconv
				$cp1252Password = iconv( 'UTF-8', 'WINDOWS-1252//TRANSLIT', $req->password );
				if ( $cp1252Password === $req->password || !$pwhash->verify( $cp1252Password ) ) {
					return $this->failResponse( $req );
				}
			} else {
				return $this->failResponse( $req );
			}
		}

		// @codeCoverageIgnoreStart
		if ( $this->getPasswordFactory()->needsUpdate( $pwhash ) ) {
			$newHash = $this->getPasswordFactory()->newFromPlaintext( $req->password );
			$fname = __METHOD__;
			\DeferredUpdates::addCallableUpdate( function () use ( $newHash, $oldRow, $fname ) {
				$dbw = wfGetDB( DB_MASTER );
				$dbw->update(
					'user',
					[ 'user_password' => $newHash->toString() ],
					[
						'user_id' => $oldRow->user_id,
						'user_password' => $oldRow->user_password
					],
					$fname
				);
			} );
		}
		// @codeCoverageIgnoreEnd

		$this->setPasswordResetFlag( $username, $status, $row );

		return AuthenticationResponse::newPass( $username );
	}

	public function testUserCanAuthenticate( $username ) {
		$username = User::getCanonicalName( $username, 'usable' );
		if ( $username === false ) {
			return false;
		}

		$dbr = wfGetDB( DB_REPLICA );
		$row = $dbr->selectRow(
			'user',
			[ 'user_password' ],
			[ 'user_name' => $username ],
			__METHOD__
		);
		if ( !$row ) {
			return false;
		}

		// Check for *really* old password hashes that don't even have a type
		// The old hash format was just an md5 hex hash, with no type information
		if ( preg_match( '/^[0-9a-f]{32}$/', $row->user_password ) ) {
			return true;
		}

		return !$this->getPassword( $row->user_password ) instanceof \InvalidPassword;
	}

	public function testUserExists( $username, $flags = User::READ_NORMAL ) {
		$username = User::getCanonicalName( $username, 'usable' );
		if ( $username === false ) {
			return false;
		}

		list( $db, $options ) = \DBAccessObjectUtils::getDBOptions( $flags );
		return (bool)wfGetDB( $db )->selectField(
			[ 'user' ],
			[ 'user_id' ],
			[ 'user_name' => $username ],
			__METHOD__,
			$options
		);
	}

	public function providerAllowsAuthenticationDataChange(
		AuthenticationRequest $req, $checkData = true
	) {
		// We only want to blank the password if something else will accept the
		// new authentication data, so return 'ignore' here.
		if ( $this->loginOnly ) {
			return \StatusValue::newGood( 'ignored' );
		}

		if ( get_class( $req ) === PasswordAuthenticationRequest::class ) {
			if ( !$checkData ) {
				return \StatusValue::newGood();
			}

			$username = User::getCanonicalName( $req->username, 'usable' );
			if ( $username !== false ) {
				$row = wfGetDB( DB_MASTER )->selectRow(
					'user',
					[ 'user_id' ],
					[ 'user_name' => $username ],
					__METHOD__
				);
				if ( $row ) {
					$sv = \StatusValue::newGood();
					if ( $req->password !== null ) {
						if ( $req->password !== $req->retype ) {
							$sv->fatal( 'badretype' );
						} else {
							$sv->merge( $this->checkPasswordValidity( $username, $req->password ) );
						}
					}
					return $sv;
				}
			}
		}

		return \StatusValue::newGood( 'ignored' );
	}

	public function providerChangeAuthenticationData( AuthenticationRequest $req ) {
		$username = $req->username !== null ? User::getCanonicalName( $req->username, 'usable' ) : false;
		if ( $username === false ) {
			return;
		}

		$pwhash = null;

		if ( get_class( $req ) === PasswordAuthenticationRequest::class ) {
			if ( $this->loginOnly ) {
				$pwhash = $this->getPasswordFactory()->newFromCiphertext( null );
				$expiry = null;
			} else {
				$pwhash = $this->getPasswordFactory()->newFromPlaintext( $req->password );
				$expiry = $this->getNewPasswordExpiry( $username );
			}
		}

		if ( $pwhash ) {
			$dbw = wfGetDB( DB_MASTER );
			$dbw->update(
				'user',
				[
					'user_password' => $pwhash->toString(),
					'user_password_expires' => $dbw->timestampOrNull( $expiry ),
				],
				[ 'user_name' => $username ],
				__METHOD__
			);
		}
	}

	public function accountCreationType() {
		return $this->loginOnly ? self::TYPE_NONE : self::TYPE_CREATE;
	}

	public function testForAccountCreation( $user, $creator, array $reqs ) {
		$req = AuthenticationRequest::getRequestByClass( $reqs, PasswordAuthenticationRequest::class );

		$ret = \StatusValue::newGood();
		if ( !$this->loginOnly && $req && $req->username !== null && $req->password !== null ) {
			if ( $req->password !== $req->retype ) {
				$ret->fatal( 'badretype' );
			} else {
				$ret->merge(
					$this->checkPasswordValidity( $user->getName(), $req->password )
				);
			}
		}
		return $ret;
	}

	public function beginPrimaryAccountCreation( $user, $creator, array $reqs ) {
		if ( $this->accountCreationType() === self::TYPE_NONE ) {
			throw new \BadMethodCallException( 'Shouldn\'t call this when accountCreationType() is NONE' );
		}

		$req = AuthenticationRequest::getRequestByClass( $reqs, PasswordAuthenticationRequest::class );
		if ( $req && $req->username !== null && $req->password !== null ) {
			// Nothing we can do besides claim it, because the user isn't in
			// the DB yet
			if ( $req->username !== $user->getName() ) {
				$req = clone $req;
				$req->username = $user->getName();
			}
			$ret = AuthenticationResponse::newPass( $req->username );
			$ret->createRequest = $req;
			return $ret;
		}
		return AuthenticationResponse::newAbstain();
	}

	public function finishAccountCreation( $user, $creator, AuthenticationResponse $res ) {
		if ( $this->accountCreationType() === self::TYPE_NONE ) {
			throw new \BadMethodCallException( 'Shouldn\'t call this when accountCreationType() is NONE' );
		}

		// Now that the user is in the DB, set the password on it.
		$this->providerChangeAuthenticationData( $res->createRequest );

		return null;
	}
}
