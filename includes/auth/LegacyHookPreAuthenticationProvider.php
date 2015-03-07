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
 * A pre-authentication provider to call some legacy hooks.
 * @ingroup Auth
 * @since 1.26
 * @deprecated since 1.26
 */
class LegacyHookPreAuthenticationProvider extends AbstractAuthenticationProvider implements PreAuthenticationProvider {

	/**
	 * @param string $which
	 * @return string[] AuthenticationRequest class names
	 */
	public function getAuthenticationRequestTypes( $which ) {
		return array();
	}

	/**
	 * @param AuthenticationRequest[] $reqs Keys are class names
	 * @return Status
	 */
	public function testForAuthentication( array $reqs ) {
		if ( isset( $reqs['PasswordAuthenticationRequest'] ) ) {
			$req = $reqs['PasswordAuthenticationRequest'];
			$user = User::newFromName( $req->username );
			$password = $req->password;
		} else {
			$user = null;
			$password = '*NoPa$$wOrD123*';
			foreach ( $reqs as $req ) {
				if ( $req->username !== null ) {
					$user = User::newFromName( $req->username );
					break;
				}
			}
			if ( !$user ) {
				$this->logger->debug( __METHOD__ . ': No username in $reqs, skipping hooks' );
				return Status::newGood();
			}
		}

		$msg = null;
		if ( !Hooks::run( 'LoginUserMigrated', array( $user, &$msg ) ) ) {
			return $this->makeFailResponse( $user, null, LoginForm::USER_MIGRATED, $msg, 'LoginUserMigrated' );
		}

		$abort = LoginForm::ABORTED;
		$msg = null;
		if ( !Hooks::run( 'AbortLogin', array( $user, $password, &$abort, &$msg ) ) ) {
			return $this->makeFailResponse( $user, null, $abort, $msg, 'AbortLogin' );
		}

		return Status::newGood();
	}

	/**
	 * @param User $user User being created (not added to the database yet).
	 *   This may become a "UserValue" in the future, or User may be refactored
	 *   into such.
	 * @param User $creator User doing the creation. This may become a
	 *   "UserValue" in the future, or User may be refactored into such.
	 * @param AuthenticationRequest[] $reqs Keys are class names
	 * @return Status
	 */
	public function testForAccountCreation( $user, $creator, array $reqs ) {
		$abortError = '';
		$abortStatus = null;
		if ( !Hooks::run( 'AbortNewAccount', array( $user, &$abortError, &$abortStatus ) ) ) {
			// Hook point to add extra creation throttles and blocks
			$this->logger->debug( __METHOD__ . ': a hook blocked creation' );
			if ( $abortStatus === null ) {
				// Report back the old string as a raw message status.
				// This will report the error back as 'createaccount-hook-aborted'
				// with the given string as the message.
				// To return a different error code, return a Status object.
				return Status::newFatal( 'createaccount-hook-aborted', $abortError );
			} else {
				// For MediaWiki 1.23+ and updated hooks, return the Status object
				// returned from the hook.
				return $abortStatus;
			}
		}

		return Status::newGood();
	}

	/**
	 * @param User $user
	 * @return Status
	 */
	public function testForAutoCreation( $user ) {
		$abortError = '';
		if ( !Hooks::run( 'AbortAutoAccount', array( $user, &$abortError ) ) ) {
			// Hook point to add extra creation throttles and blocks
			$this->logger->debug( __METHOD__ . ": a hook blocked auto-creation: $abortError\n" );
			return $this->makeFailResponse( $user, $user, LoginForm::ABORTED, $msg, 'AbortAutoAccount' );
		}

		return Status::newGood();
	}

	/**
	 * @param User $user
	 * @param User|null $creator
	 * @param int $constant LoginForm constant
	 * @param string|null $msg Message
	 * @param string $hook Hook
	 * @return AuthenticationResponse
	 */
	protected function makeFailResponse( $user, $creator, $constant, $msg, $hook ) {
		global $wgMemc, $wgLang, $wgSecureLogin, $wgPasswordAttemptThrottle,
			$wgInvalidPasswordReset;

		switch ( $constant ) {
			case LoginForm::SUCCESS:
				// WTF?
				$this->logger->debug( "$hook is SUCCESS?!" );
				return Status::newGood();

			case LoginForm::NEED_TOKEN:
				return Status::newFatal( $msg ?: 'nocookiesforlogin' );

			case LoginForm::WRONG_TOKEN:
				return Status::newFatal( $msg ?: 'sessionfailure' );

			case LoginForm::NO_NAME:
			case LoginForm::ILLEGAL:
				return Status::newFatal( $msg ?: 'noname' );

			case LoginForm::WRONG_PLUGIN_PASS:
			case LoginForm::WRONG_PASS:
				return Status::newFatal( $msg ?: 'wrongpassword' );

			case LoginForm::NOT_EXISTS:
				return Status::newFatal( $msg ?: 'nosuchusershort', wfEscapeWikiText( $user->getName() ) );

			case LoginForm::EMPTY_PASS:
				return Status::newFatal( $msg ?: 'wrongpasswordempty' );

			case LoginForm::RESET_PASS:
				return Status::newFatal( $msg ?: 'resetpass_announce' );

			case LoginForm::CREATE_BLOCKED:
				$ip = $this->manager->getRequest()->getIP();
				$block = $creator->isBlockedFromCreateAccount();
				$errorParams = array(
					$block->getTarget(),
					$block->mReason ? $block->mReason : wfMessage( 'blockednoreason' )->text(),
					$block->getByName(),
				);
				if ( $block->getType() === Block::TYPE_RANGE ) {
					$errorMessage = 'cantcreateaccount-range-text';
					$errorParams[] = $this->manager->getRequest()->getIP();
				} else {
					$errorMessage = 'cantcreateaccount-text';
				}
				return Status::newFatal( $errorMessage, $errorParams );

			case LoginForm::THROTTLED:
				$throttle = $this->config->get( 'PasswordAttemptThrottle' );
				return Status::newFatal(
					$msg ?: 'login-throttled',
					Message::durationParam( $throttle['seconds'] )
				);

			case LoginForm::USER_BLOCKED:
				return Status::newFatal( $msg ?: 'login-userblocked', wfEscapeWikiText( $user->getName() ) );

			case LoginForm::ABORTED:
				return Status::newFatal( $msg ?: 'login-abort-generic', wfEscapeWikiText( $user->getName() ) );

			case LoginForm::USER_MIGRATED:
				$error = $msg ?: 'login-migrated-generic';
				return call_user_func_array( 'Status::newFatal', (array)$error );

			default:
				throw new DomainException( __METHOD__ . ': Unhandled case value' );
		}
	}

}
