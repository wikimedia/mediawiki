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

use LoginForm;
use StatusValue;
use User;

/**
 * A pre-authentication provider to call some legacy hooks.
 * @ingroup Auth
 * @since 1.27
 * @deprecated since 1.27
 */
class LegacyHookPreAuthenticationProvider extends AbstractPreAuthenticationProvider {

	public function testForAuthentication( array $reqs ) {
		$req = AuthenticationRequest::getRequestByClass( $reqs, PasswordAuthenticationRequest::class );
		if ( $req ) {
			$user = User::newFromName( $req->username );
			$password = $req->password;
		} else {
			$user = null;
			foreach ( $reqs as $req ) {
				if ( $req->username !== null ) {
					$user = User::newFromName( $req->username );
					break;
				}
			}
			if ( !$user ) {
				$this->logger->debug( __METHOD__ . ': No username in $reqs, skipping hooks' );
				return StatusValue::newGood();
			}

			// Something random for the 'AbortLogin' hook.
			$password = wfRandomString( 32 );
		}

		$msg = null;
		if ( !\Hooks::run( 'LoginUserMigrated', [ $user, &$msg ] ) ) {
			return $this->makeFailResponse(
				$user, null, LoginForm::USER_MIGRATED, $msg, 'LoginUserMigrated'
			);
		}

		$abort = LoginForm::ABORTED;
		$msg = null;
		if ( !\Hooks::run( 'AbortLogin', [ $user, $password, &$abort, &$msg ] ) ) {
			return $this->makeFailResponse( $user, null, $abort, $msg, 'AbortLogin' );
		}

		return StatusValue::newGood();
	}

	public function testForAccountCreation( $user, $creator, array $reqs ) {
		$abortError = '';
		$abortStatus = null;
		if ( !\Hooks::run( 'AbortNewAccount', [ $user, &$abortError, &$abortStatus ] ) ) {
			// Hook point to add extra creation throttles and blocks
			$this->logger->debug( __METHOD__ . ': a hook blocked creation' );
			if ( $abortStatus === null ) {
				// Report back the old string as a raw message status.
				// This will report the error back as 'createaccount-hook-aborted'
				// with the given string as the message.
				// To return a different error code, return a StatusValue object.
				$msg = wfMessage( 'createaccount-hook-aborted' )->rawParams( $abortError );
				return StatusValue::newFatal( $msg );
			} else {
				// For MediaWiki 1.23+ and updated hooks, return the Status object
				// returned from the hook.
				$ret = StatusValue::newGood();
				$ret->merge( $abortStatus );
				return $ret;
			}
		}

		return StatusValue::newGood();
	}

	public function testUserForCreation( $user, $autocreate, array $options = [] ) {
		if ( $autocreate !== false ) {
			$abortError = '';
			if ( !\Hooks::run( 'AbortAutoAccount', [ $user, &$abortError ] ) ) {
				// Hook point to add extra creation throttles and blocks
				$this->logger->debug( __METHOD__ . ": a hook blocked auto-creation: $abortError\n" );
				return $this->makeFailResponse(
					$user, $user, LoginForm::ABORTED, $abortError, 'AbortAutoAccount'
				);
			}
		}

		return StatusValue::newGood();
	}

	/**
	 * Construct an appropriate failure response
	 * @param User $user
	 * @param User|null $creator
	 * @param int $constant LoginForm constant
	 * @param string|null $msg Message
	 * @param string $hook Hook
	 * @return StatusValue
	 */
	protected function makeFailResponse( $user, $creator, $constant, $msg, $hook ) {
		switch ( $constant ) {
			case LoginForm::SUCCESS:
				// WTF?
				$this->logger->debug( "$hook is SUCCESS?!" );
				return StatusValue::newGood();

			case LoginForm::NEED_TOKEN:
				return StatusValue::newFatal( $msg ?: 'nocookiesforlogin' );

			case LoginForm::WRONG_TOKEN:
				return StatusValue::newFatal( $msg ?: 'sessionfailure' );

			case LoginForm::NO_NAME:
			case LoginForm::ILLEGAL:
				return StatusValue::newFatal( $msg ?: 'noname' );

			case LoginForm::WRONG_PLUGIN_PASS:
			case LoginForm::WRONG_PASS:
				return StatusValue::newFatal( $msg ?: 'wrongpassword' );

			case LoginForm::NOT_EXISTS:
				return StatusValue::newFatal( $msg ?: 'nosuchusershort', wfEscapeWikiText( $user->getName() ) );

			case LoginForm::EMPTY_PASS:
				return StatusValue::newFatal( $msg ?: 'wrongpasswordempty' );

			case LoginForm::RESET_PASS:
				return StatusValue::newFatal( $msg ?: 'resetpass_announce' );

			case LoginForm::THROTTLED:
				$throttle = $this->config->get( 'PasswordAttemptThrottle' );
				return StatusValue::newFatal(
					$msg ?: 'login-throttled',
					\Message::durationParam( $throttle['seconds'] )
				);

			case LoginForm::USER_BLOCKED:
				return StatusValue::newFatal(
					$msg ?: 'login-userblocked', wfEscapeWikiText( $user->getName() )
				);

			case LoginForm::ABORTED:
				return StatusValue::newFatal(
					$msg ?: 'login-abort-generic', wfEscapeWikiText( $user->getName() )
				);

			case LoginForm::USER_MIGRATED:
				$error = $msg ?: 'login-migrated-generic';
				return call_user_func_array( 'StatusValue::newFatal', (array)$error );

			// @codeCoverageIgnoreStart
			case LoginForm::CREATE_BLOCKED: // Can never happen
			default:
				throw new \DomainException( __METHOD__ . ": Unhandled case value from $hook" );
		}
			// @codeCoverageIgnoreEnd
	}
}
