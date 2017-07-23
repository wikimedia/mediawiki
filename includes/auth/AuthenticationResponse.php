<?php
/**
 * Authentication response value object
 *
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

use Message;

/**
 * This is a value object to hold authentication response data
 *
 * An AuthenticationResponse represents both the status of the authentication
 * (success, failure, in progress) and it its state (what data is needed to continue).
 *
 * @ingroup Auth
 * @since 1.27
 */
class AuthenticationResponse {
	/** Indicates that the authentication succeeded. */
	const PASS = 'PASS';

	/** Indicates that the authentication failed. */
	const FAIL = 'FAIL';

	/** Indicates that third-party authentication succeeded but no user exists.
	 * Either treat this like a UI response or pass $this->createRequest to
	 * AuthManager::beginCreateAccount(). For use by AuthManager only (providers
	 * should just return a PASS with no username).
	 */
	const RESTART = 'RESTART';

	/** Indicates that the authentication provider does not handle this request. */
	const ABSTAIN = 'ABSTAIN';

	/** Indicates that the authentication needs further user input of some sort. */
	const UI = 'UI';

	/** Indicates that the authentication needs to be redirected to a third party to proceed. */
	const REDIRECT = 'REDIRECT';

	/** @var string One of the constants above */
	public $status;

	/** @var string|null URL to redirect to for a REDIRECT response */
	public $redirectTarget = null;

	/**
	 * @var mixed Data for a REDIRECT response that a client might use to
	 * query the remote site via its API rather than by following $redirectTarget.
	 * Value must be something acceptable to ApiResult::addValue().
	 */
	public $redirectApiData = null;

	/**
	 * @var AuthenticationRequest[] Needed AuthenticationRequests to continue
	 * after a UI or REDIRECT response. This plays the same role when continuing
	 * authentication as AuthManager::getAuthenticationRequests() does when
	 * beginning it.
	 */
	public $neededRequests = [];

	/** @var Message|null I18n message to display in case of UI or FAIL */
	public $message = null;

	/** @var string Whether the $message is an error or warning message, for styling reasons */
	public $messageType = 'warning';

	/**
	 * @var string|null Local user name from authentication.
	 * May be null if the authentication passed but no local user is known.
	 */
	public $username = null;

	/**
	 * @var AuthenticationRequest|null
	 *
	 * Returned with a PrimaryAuthenticationProvider login FAIL or a PASS with
	 * no username, this can be set to a request that should result in a PASS when
	 * passed to that provider's PrimaryAuthenticationProvider::beginPrimaryAccountCreation().
	 * The client will be able to send that back for expedited account creation where only
	 * the username needs to be filled.
	 *
	 * Returned with an AuthManager login FAIL or RESTART, this holds a
	 * CreateFromLoginAuthenticationRequest that may be passed to
	 * AuthManager::beginCreateAccount(), possibly in place of any
	 * "primary-required" requests. It may also be passed to
	 * AuthManager::beginAuthentication() to preserve the list of
	 * accounts which can be linked after success (see $linkRequest).
	 */
	public $createRequest = null;

	/**
	 * @var AuthenticationRequest|null When returned with a PrimaryAuthenticationProvider
	 *  login PASS with no username, the request this holds will be passed to
	 *  AuthManager::changeAuthenticationData() once the local user has been determined and the
	 *  user has confirmed the account ownership (by reviewing the information given by
	 *  $linkRequest->describeCredentials()). The provider should handle that
	 *  changeAuthenticationData() call by doing the actual linking.
	 */
	public $linkRequest = null;

	/**
	 * @var AuthenticationRequest|null Returned with an AuthManager account
	 *  creation PASS, this holds a request to pass to AuthManager::beginAuthentication()
	 *  to immediately log into the created account. All provider methods except
	 *   postAuthentication will be skipped.
	 */
	public $loginRequest = null;

	/**
	 * @param string|null $username Local username
	 * @return AuthenticationResponse
	 * @see AuthenticationResponse::PASS
	 */
	public static function newPass( $username = null ) {
		$ret = new AuthenticationResponse;
		$ret->status = self::PASS;
		$ret->username = $username;
		return $ret;
	}

	/**
	 * @param Message $msg
	 * @return AuthenticationResponse
	 * @see AuthenticationResponse::FAIL
	 */
	public static function newFail( Message $msg ) {
		$ret = new AuthenticationResponse;
		$ret->status = self::FAIL;
		$ret->message = $msg;
		$ret->messageType = 'error';
		return $ret;
	}

	/**
	 * @param Message $msg
	 * @return AuthenticationResponse
	 * @see AuthenticationResponse::RESTART
	 */
	public static function newRestart( Message $msg ) {
		$ret = new AuthenticationResponse;
		$ret->status = self::RESTART;
		$ret->message = $msg;
		return $ret;
	}

	/**
	 * @return AuthenticationResponse
	 * @see AuthenticationResponse::ABSTAIN
	 */
	public static function newAbstain() {
		$ret = new AuthenticationResponse;
		$ret->status = self::ABSTAIN;
		return $ret;
	}

	/**
	 * @param AuthenticationRequest[] $reqs AuthenticationRequests needed to continue
	 * @param Message $msg
	 * @param string $msgtype
	 * @return AuthenticationResponse
	 * @see AuthenticationResponse::UI
	 */
	public static function newUI( array $reqs, Message $msg, $msgtype = 'warning' ) {
		if ( !$reqs ) {
			throw new \InvalidArgumentException( '$reqs may not be empty' );
		}
		if ( $msgtype !== 'warning' && $msgtype !== 'error' ) {
			throw new \InvalidArgumentException( $msgtype . ' is not a valid message type.' );
		}

		$ret = new AuthenticationResponse;
		$ret->status = self::UI;
		$ret->neededRequests = $reqs;
		$ret->message = $msg;
		$ret->messageType = $msgtype;
		return $ret;
	}

	/**
	 * @param AuthenticationRequest[] $reqs AuthenticationRequests needed to continue
	 * @param string $redirectTarget URL
	 * @param mixed $redirectApiData Data suitable for adding to an ApiResult
	 * @return AuthenticationResponse
	 * @see AuthenticationResponse::REDIRECT
	 */
	public static function newRedirect( array $reqs, $redirectTarget, $redirectApiData = null ) {
		if ( !$reqs ) {
			throw new \InvalidArgumentException( '$reqs may not be empty' );
		}

		$ret = new AuthenticationResponse;
		$ret->status = self::REDIRECT;
		$ret->neededRequests = $reqs;
		$ret->redirectTarget = $redirectTarget;
		$ret->redirectApiData = $redirectApiData;
		return $ret;
	}

}
