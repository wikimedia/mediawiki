<?php
/**
 *
 *
 * Created on Sep 19, 2006
 *
 * Copyright © 2006-2007 Yuri Astrakhan <Firstname><Lastname>@gmail.com,
 * Daniel Cannon (cannon dot danielc at gmail dot com)
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
 */

/**
 * Unit to authenticate log-in attempts to the current wiki.
 *
 * @ingroup API
 */
class ApiLogin extends ApiBase {

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action, 'lg' );
	}

	/**
	 * Executes the log-in attempt using the parameters passed. If
	 * the log-in succeeeds, it attaches a cookie to the session
	 * and outputs the user id, username, and session token. If a
	 * log-in fails, as the result of a bad password, a nonexistent
	 * user, or any other reason, the host is cached with an expiry
	 * and no log-in attempts will be accepted until that expiry
	 * is reached. The expiry is $this->mLoginThrottle.
	 */
	public function execute() {
		$params = $this->extractRequestParams();

		$result = array();

		// Init session if necessary
		if ( session_id() == '' ) {
			wfSetupSession();
		}

		$context = new DerivativeContext( $this->getContext() );
		$context->setRequest( new DerivativeRequest(
			$this->getContext()->getRequest(),
			array(
				'wpName' => $params['name'],
				'wpPassword' => $params['password'],
				'wpDomain' => $params['domain'],
				'wpLoginToken' => $params['token'],
				'wpRemember' => ''
			)
		) );
		$loginForm = new LoginForm();
		$loginForm->setContext( $context );

		global $wgCookiePrefix, $wgPasswordAttemptThrottle;

		$authRes = $loginForm->authenticateUserData();
		switch ( $authRes ) {
			case LoginForm::SUCCESS:
				$user = $context->getUser();
				$this->getContext()->setUser( $user );
				$user->setOption( 'rememberpassword', 1 );
				$user->setCookies( $this->getRequest() );

				// Run hooks.
				// @todo FIXME: Split back and frontend from this hook.
				// @todo FIXME: This hook should be placed in the backend
				$injected_html = '';
				wfRunHooks( 'UserLoginComplete', array( &$user, &$injected_html ) );

				$result['result'] = 'Success';
				$result['lguserid'] = intval( $user->getId() );
				$result['lgusername'] = $user->getName();
				$result['lgtoken'] = $user->getToken();
				$result['cookieprefix'] = $wgCookiePrefix;
				$result['sessionid'] = session_id();
				break;

			case LoginForm::NEED_TOKEN:
				$result['result'] = 'NeedToken';
				$result['token'] = $loginForm->getLoginToken();
				$result['cookieprefix'] = $wgCookiePrefix;
				$result['sessionid'] = session_id();
				break;

			case LoginForm::WRONG_TOKEN:
				$result['result'] = 'WrongToken';
				break;

			case LoginForm::NO_NAME:
				$result['result'] = 'NoName';
				break;

			case LoginForm::ILLEGAL:
				$result['result'] = 'Illegal';
				break;

			case LoginForm::WRONG_PLUGIN_PASS:
				$result['result'] = 'WrongPluginPass';
				break;

			case LoginForm::NOT_EXISTS:
				$result['result'] = 'NotExists';
				break;

			case LoginForm::RESET_PASS: // bug 20223 - Treat a temporary password as wrong. Per SpecialUserLogin - "The e-mailed temporary password should not be used for actual logins;"
			case LoginForm::WRONG_PASS:
				$result['result'] = 'WrongPass';
				break;

			case LoginForm::EMPTY_PASS:
				$result['result'] = 'EmptyPass';
				break;

			case LoginForm::CREATE_BLOCKED:
				$result['result'] = 'CreateBlocked';
				$result['details'] = 'Your IP address is blocked from account creation';
				break;

			case LoginForm::THROTTLED:
				$result['result'] = 'Throttled';
				$result['wait'] = intval( $wgPasswordAttemptThrottle['seconds'] );
				break;

			case LoginForm::USER_BLOCKED:
				$result['result'] = 'Blocked';
				break;

			case LoginForm::ABORTED:
				$result['result'] = 'Aborted';
				$result['reason'] =  $loginForm->mAbortLoginErrorMsg;
				break;

			default:
				ApiBase::dieDebug( __METHOD__, "Unhandled case value: {$authRes}" );
		}

		$this->getResult()->addValue( null, 'login', $result );
	}

	public function mustBePosted() {
		return true;
	}

	public function isReadMode() {
		return false;
	}

	public function getAllowedParams() {
		return array(
			'name' => null,
			'password' => null,
			'domain' => null,
			'token' => null,
		);
	}

	public function getParamDescription() {
		return array(
			'name' => 'User Name',
			'password' => 'Password',
			'domain' => 'Domain (optional)',
			'token' => 'Login token obtained in first request',
		);
	}

	public function getDescription() {
		return array(
			'Log in and get the authentication tokens. ',
			'In the event of a successful log-in, a cookie will be attached',
			'to your session. In the event of a failed log-in, you will not ',
			'be able to attempt another log-in through this method for 5 seconds.',
			'This is to prevent password guessing by automated password crackers'
		);
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'NeedToken', 'info' => 'You need to resubmit your login with the specified token. See https://bugzilla.wikimedia.org/show_bug.cgi?id=23076' ),
			array( 'code' => 'WrongToken', 'info' => 'You specified an invalid token' ),
			array( 'code' => 'NoName', 'info' => 'You didn\'t set the lgname parameter' ),
			array( 'code' => 'Illegal', 'info' => ' You provided an illegal username' ),
			array( 'code' => 'NotExists', 'info' => ' The username you provided doesn\'t exist' ),
			array( 'code' => 'EmptyPass', 'info' => ' You didn\'t set the lgpassword parameter or you left it empty' ),
			array( 'code' => 'WrongPass', 'info' => ' The password you provided is incorrect' ),
			array( 'code' => 'WrongPluginPass', 'info' => 'Same as "WrongPass", returned when an authentication plugin rather than MediaWiki itself rejected the password' ),
			array( 'code' => 'CreateBlocked', 'info' => 'The wiki tried to automatically create a new account for you, but your IP address has been blocked from account creation' ),
			array( 'code' => 'Throttled', 'info' => 'You\'ve logged in too many times in a short time' ),
			array( 'code' => 'Blocked', 'info' => 'User is blocked' ),
		) );
	}

	public function getExamples() {
		return array(
			'api.php?action=login&lgname=user&lgpassword=password'
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Login';
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
