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

use MessageSpecifier;
use StatusValue;

/**
 * This is a value object to hold authentication response data
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
	 * AuthManager::beginCreateAccount().
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
	 *  query the remote site via its API rather than by following $redirectTarget */
	public $redirectApiData = null;

	/**
	 * @var AuthenticationRequest[] Needed AuthenticationRequests to continue
	 *  after a UI response. This can be used to e.g. build a form for user
	 *  input; the client is not necessarily required to return each of these
	 *  requests in the next step, though.
	 */
	public $neededRequests = array();

	/** @var MessageSpecifier|null I18n message to display in case of UI or FAIL */
	public $message = null;

	/**
	 * @var string|null Local user name from authentication.
	 * May be null if the authentication passed but no local user is known.
	 */
	public $username = null;

	/**
	 * @var AuthenticationRequest|null Request to pass to
	 *  AuthManager::beginCreateAccount() to create an account.
	 */
	public $createRequest = null;

	/**
	 * @var AuthenticationRequest|null After account creation, request to pass
	 * to AuthManager::beginAuthentication() to log into that account.
	 */
	public $loginRequest = null;

	/**
	 * @var AuthenticationRequest|null After successful remote authentication,
	 *  request to pass to PrimaryAuthenticationProvider::finishAccountLink()
	 *  once a local user has been created.
	 */
	public $linkRequest = null;

	/**
	 * @var StatusValue|null One or more UI messages if some accounts have been
	 *  linked, (These are set as warnings as StatusValue has no option for
	 *  "good" messages.)
	 */
	public $linkStatus = null;

	/**
	 * @param string|null $username Local username
	 * @param AuthenticationRequest $createRequest For chaining to account creation flow
	 * @param AuthenticationRequest $linkRequest For passing back to provider's finishAccountLink()
	 * @return AuthenticationResponse
	 */
	public static function newPass(
		$username = null,
		AuthenticationRequest $createRequest = null,
		AuthenticationRequest $linkRequest = null
	) {
		$ret = new AuthenticationResponse;
		$ret->status = AuthenticationResponse::PASS;
		$ret->username = $username;
		$ret->createRequest = $createRequest;
		$ret->linkRequest = $linkRequest;
		return $ret;
	}

	/**
	 * @param MessageSpecifier $msg
	 * @return AuthenticationResponse
	 */
	public static function newFail( MessageSpecifier $msg ) {
		$ret = new AuthenticationResponse;
		$ret->status = AuthenticationResponse::FAIL;
		$ret->message = $msg;
		return $ret;
	}

	/**
	 * @param MessageSpecifier $msg
	 * @return AuthenticationResponse
	 */
	public static function newRestart( MessageSpecifier $msg ) {
		$ret = new AuthenticationResponse;
		$ret->status = AuthenticationResponse::RESTART;
		$ret->message = $msg;
		return $ret;
	}

	/**
	 * @return AuthenticationResponse
	 */
	public static function newAbstain() {
		$ret = new AuthenticationResponse;
		$ret->status = AuthenticationResponse::ABSTAIN;
		return $ret;
	}

	/**
	 * @param AuthenticationRequest[] $requests
	 * @param MessageSpecifier $msg
	 * @return AuthenticationResponse
	 */
	public static function newUI( array $requests, MessageSpecifier $msg ) {
		$ret = new AuthenticationResponse;
		$ret->status = AuthenticationResponse::UI;
		$ret->neededRequests = $requests;
		$ret->message = $msg;
		return $ret;
	}

	/**
	 * @param string $redirectTarget URL
	 * @param mixed $redirectApiData Data suitable for adding to an ApiResult
	 * @return AuthenticationResponse
	 */
	public static function newRedirect( $redirectTarget, $redirectApiData = null ) {
		$ret = new AuthenticationResponse;
		$ret->status = AuthenticationResponse::REDIRECT;
		$ret->redirectTarget = $redirectTarget;
		$ret->redirectApiData = $redirectApiData;
		return $ret;
	}

}
