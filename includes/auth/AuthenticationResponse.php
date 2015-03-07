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

/**
 * This is a value object to hold authentication response data
 * @ingroup Auth
 * @since 1.26
 */
class AuthenticationResponse {
	const PASS = 'PASS';
	const FAIL = 'FAIL';
	const ABSTAIN = 'ABSTAIN';
	const UI = 'UI';
	const REDIRECT = 'REDIRECT';

	/** @var string One of the constants above */
	public $status;

	/** @var string|null URL to redirect to for a REDIRECT response */
	public $redirectTarget = null;

	/**
	 * @var mixed Data for a REDIRECT response that a client might use to
	 * query the remote site via its API rather than by following $redirectTarget */
	public $redirectApiData = null;

	/** @var string[] Needed AuthenticationRequest types to continue after a UI response */
	public $neededRequests = array();

	/** @var MessageSpecifier|null I18n message to display in case of UI or FAIL */
	public $message = null;

	/**
	 * @var string|null Local user name from authentication.
	 * May be null if the authentication passed but no local user is known.
	 */
	public $username = null;

	/**
	 * @var AuthenticationRequest|null Request to pass to beginCreateAccount() to
	 * create an account.
	 */
	public $createRequest = null;

	/**
	 * @var AuthenticationRequest|null After account creation, request to pass
	 * to beginAuthentication() to log into that account.
	 */
	public $loginRequest = null;

	/**
	 * @param string|null $username Local username
	 * @param AuthenticationRequest $createRequest For chaining to account creation flow
	 * @return AuthenticationResponse
	 */
	public static function newPass( $username = null, AuthenticationRequest $createRequest = null ) {
		$ret = new AuthenticationResponse;
		$ret->status = AuthenticationResponse::PASS;
		$ret->username = $username;
		$ret->createRequest = $createRequest;
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
	 * @return AuthenticationResponse
	 */
	public static function newAbstain() {
		$ret = new AuthenticationResponse;
		$ret->status = AuthenticationResponse::ABSTAIN;
		return $ret;
	}

	/**
	 * @param string[] $types AuthenticationRequest types
	 * @param MessageSpecifier $msg
	 * @return AuthenticationResponse
	 */
	public static function newUI( array $types, MessageSpecifier $msg ) {
		$ret = new AuthenticationResponse;
		$ret->status = AuthenticationResponse::UI;
		$ret->neededRequests = $types;
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
