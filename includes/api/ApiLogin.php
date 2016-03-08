<?php
/**
 *
 *
 * Created on Sep 19, 2006
 *
 * Copyright © 2006-2007 Yuri Astrakhan "<Firstname><Lastname>@gmail.com",
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

use MediaWiki\Logger\LoggerFactory;

/**
 * Unit to authenticate log-in attempts to the current wiki.
 *
 * @ingroup API
 */
class ApiLogin extends ApiBase {

	public function __construct( ApiMain $main, $action ) {
		parent::__construct( $main, $action, 'lg' );
	}

	/**
	 * Executes the log-in attempt using the parameters passed. If
	 * the log-in succeeds, it attaches a cookie to the session
	 * and outputs the user id, username, and session token. If a
	 * log-in fails, as the result of a bad password, a nonexistent
	 * user, or any other reason, the host is cached with an expiry
	 * and no log-in attempts will be accepted until that expiry
	 * is reached. The expiry is $this->mLoginThrottle.
	 */
	public function execute() {
		// If we're in a mode that breaks the same-origin policy, no tokens can
		// be obtained
		if ( $this->lacksSameOriginSecurity() ) {
			$this->getResult()->addValue( null, 'login', [
				'result' => 'Aborted',
				'reason' => 'Cannot log in when the same-origin policy is not applied',
			] );

			return;
		}

		$params = $this->extractRequestParams();

		$result = [];

		// Make sure session is persisted
		$session = MediaWiki\Session\SessionManager::getGlobalSession();
		$session->persist();

		// Make sure it's possible to log in
		if ( !$session->canSetUser() ) {
			$this->getResult()->addValue( null, 'login', [
				'result' => 'Aborted',
				'reason' => 'Cannot log in when using ' .
					$session->getProvider()->describe( Language::factory( 'en' ) ),
			] );

			return;
		}

		$authRes = false;
		$context = new DerivativeContext( $this->getContext() );
		$loginType = 'N/A';

		// Check login token
		$token = LoginForm::getLoginToken();
		if ( $token->wasNew() || !$params['token'] ) {
			$authRes = LoginForm::NEED_TOKEN;
		} elseif ( !$token->match( $params['token'] ) ) {
			$authRes = LoginForm::WRONG_TOKEN;
		}

		// Try bot passwords
		if ( $authRes === false && $this->getConfig()->get( 'EnableBotPasswords' ) &&
			strpos( $params['name'], BotPassword::getSeparator() ) !== false
		) {
			$status = BotPassword::login(
				$params['name'], $params['password'], $this->getRequest()
			);
			if ( $status->isOK() ) {
				$session = $status->getValue();
				$authRes = LoginForm::SUCCESS;
				$loginType = 'BotPassword';
			} else {
				LoggerFactory::getInstance( 'authmanager' )->info(
					'BotPassword login failed: ' . $status->getWikiText()
				);
			}
		}

		// Normal login
		if ( $authRes === false ) {
			$context->setRequest( new DerivativeRequest(
				$this->getContext()->getRequest(),
				[
					'wpName' => $params['name'],
					'wpPassword' => $params['password'],
					'wpDomain' => $params['domain'],
					'wpLoginToken' => $params['token'],
					'wpRemember' => ''
				]
			) );
			$loginForm = new LoginForm();
			$loginForm->setContext( $context );
			$authRes = $loginForm->authenticateUserData();
			$loginType = 'LoginForm';
		}

		switch ( $authRes ) {
			case LoginForm::SUCCESS:
				$user = $context->getUser();
				$this->getContext()->setUser( $user );
				$user->setCookies( $this->getRequest(), null, true );

				ApiQueryInfo::resetTokenCache();

				// Run hooks.
				// @todo FIXME: Split back and frontend from this hook.
				// @todo FIXME: This hook should be placed in the backend
				$injected_html = '';
				Hooks::run( 'UserLoginComplete', [ &$user, &$injected_html ] );

				$result['result'] = 'Success';
				$result['lguserid'] = intval( $user->getId() );
				$result['lgusername'] = $user->getName();

				// @todo: These are deprecated, and should be removed at some
				// point (1.28 at the earliest, and see T121527). They were ok
				// when the core cookie-based login was the only thing, but
				// CentralAuth broke that a while back and
				// SessionManager/AuthManager are *really* going to break it.
				$result['lgtoken'] = $user->getToken();
				$result['cookieprefix'] = $this->getConfig()->get( 'CookiePrefix' );
				$result['sessionid'] = $session->getId();
				break;

			case LoginForm::NEED_TOKEN:
				$result['result'] = 'NeedToken';
				$result['token'] = LoginForm::getLoginToken()->toString();
				$this->setWarning( 'Fetching a token via action=login is deprecated. ' .
				   'Use action=query&meta=tokens&type=login instead.' );
				$this->logFeatureUsage( 'action=login&!lgtoken' );

				// @todo: See above about deprecation
				$result['cookieprefix'] = $this->getConfig()->get( 'CookiePrefix' );
				$result['sessionid'] = $session->getId();
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

			// bug 20223 - Treat a temporary password as wrong. Per SpecialUserLogin:
			// The e-mailed temporary password should not be used for actual logins.
			case LoginForm::RESET_PASS:
			case LoginForm::WRONG_PASS:
				$result['result'] = 'WrongPass';
				break;

			case LoginForm::EMPTY_PASS:
				$result['result'] = 'EmptyPass';
				break;

			case LoginForm::CREATE_BLOCKED:
				$result['result'] = 'CreateBlocked';
				$result['details'] = 'Your IP address is blocked from account creation';
				$block = $context->getUser()->getBlock();
				if ( $block ) {
					$result = array_merge( $result, ApiQueryUserInfo::getBlockInfo( $block ) );
				}
				break;

			case LoginForm::THROTTLED:
				$result['result'] = 'Throttled';
				$result['wait'] = intval( $loginForm->mThrottleWait );
				break;

			case LoginForm::USER_BLOCKED:
				$result['result'] = 'Blocked';
				$block = User::newFromName( $params['name'] )->getBlock();
				if ( $block ) {
					$result = array_merge( $result, ApiQueryUserInfo::getBlockInfo( $block ) );
				}
				break;

			case LoginForm::ABORTED:
				$result['result'] = 'Aborted';
				$result['reason'] = $loginForm->mAbortLoginErrorMsg;
				break;

			default:
				ApiBase::dieDebug( __METHOD__, "Unhandled case value: {$authRes}" );
		}

		$this->getResult()->addValue( null, 'login', $result );

		LoggerFactory::getInstance( 'authmanager' )->info( 'Login attempt', [
			'event' => 'login',
			'successful' => $authRes === LoginForm::SUCCESS,
			'loginType' => $loginType,
			'status' => LoginForm::$statusCodes[$authRes],
		] );
	}

	public function mustBePosted() {
		return true;
	}

	public function isReadMode() {
		return false;
	}

	public function getAllowedParams() {
		return [
			'name' => null,
			'password' => [
				ApiBase::PARAM_TYPE => 'password',
			],
			'domain' => null,
			'token' => [
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => false, // for BC
				ApiBase::PARAM_HELP_MSG => [ 'api-help-param-token', 'login' ],
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=login&lgname=user&lgpassword=password'
				=> 'apihelp-login-example-gettoken',
			'action=login&lgname=user&lgpassword=password&lgtoken=123ABC'
				=> 'apihelp-login-example-login',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Login';
	}
}
