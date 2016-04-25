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

use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthenticationResponse;
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

	protected function getDescriptionMessage() {
		if ( $this->getConfig()->get( 'DisableAuthManager' ) ) {
			return 'apihelp-login-description-nonauthmanager';
		} elseif ( $this->getConfig()->get( 'EnableBotPasswords' ) ) {
			return 'apihelp-login-description';
		} else {
			return 'apihelp-login-description-nobotpasswords';
		}
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
		$token = $session->getToken( '', 'login' );
		if ( $token->wasNew() || !$params['token'] ) {
			$authRes = 'NeedToken';
		} elseif ( !$token->match( $params['token'] ) ) {
			$authRes = 'WrongToken';
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
				$authRes = 'Success';
				$loginType = 'BotPassword';
			} else {
				$authRes = 'Failed';
				$message = $status->getMessage();
				LoggerFactory::getInstance( 'authmanager' )->info(
					'BotPassword login failed: ' . $status->getWikiText( false, false, 'en' )
				);
			}
		}

		if ( $authRes === false ) {
			if ( $this->getConfig()->get( 'DisableAuthManager' ) ) {
				// Non-AuthManager login
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

				switch ( $authRes ) {
					case LoginForm::SUCCESS:
						$authRes = 'Success';
						break;
					case LoginForm::NEED_TOKEN:
						$authRes = 'NeedToken';
						break;
				}
			} else {
				// Simplified AuthManager login, for backwards compatibility
				$manager = AuthManager::singleton();
				$reqs = AuthenticationRequest::loadRequestsFromSubmission(
					$manager->getAuthenticationRequests( AuthManager::ACTION_LOGIN, $this->getUser() ),
					[
						'username' => $params['name'],
						'password' => $params['password'],
						'domain' => $params['domain'],
						'rememberMe' => true,
					]
				);
				$res = AuthManager::singleton()->beginAuthentication( $reqs, 'null:' );
				switch ( $res->status ) {
					case AuthenticationResponse::PASS:
						if ( $this->getConfig()->get( 'EnableBotPasswords' ) ) {
							$warn = 'Main-account login via action=login is deprecated and may stop working ' .
								'without warning.';
							$warn .= ' To continue login with action=login, see [[Special:BotPasswords]].';
							$warn .= ' To safely continue using main-account login, see action=clientlogin.';
						} else {
							$warn = 'Login via action=login is deprecated and may stop working without warning.';
							$warn .= ' To safely log in, see action=clientlogin.';
						}
						$this->setWarning( $warn );
						$authRes = 'Success';
						$loginType = 'AuthManager';
						break;

					case AuthenticationResponse::FAIL:
						// Hope it's not a PreAuthenticationProvider that failed...
						$authRes = 'Failed';
						$message = $res->message;
						\MediaWiki\Logger\LoggerFactory::getInstance( 'authentication' )
							->info( __METHOD__ . ': Authentication failed: ' . $message->plain() );
						break;

					default:
						$authRes = 'Aborted';
						break;
				}
			}
		}

		$result['result'] = $authRes;
		switch ( $authRes ) {
			case 'Success':
				if ( $this->getConfig()->get( 'DisableAuthManager' ) ) {
					$user = $context->getUser();
					$this->getContext()->setUser( $user );
					$user->setCookies( $this->getRequest(), null, true );
				} else {
					$user = $session->getUser();
				}

				ApiQueryInfo::resetTokenCache();

				// Deprecated hook
				$injected_html = '';
				Hooks::run( 'UserLoginComplete', [ &$user, &$injected_html ] );

				$result['lguserid'] = intval( $user->getId() );
				$result['lgusername'] = $user->getName();

				// @todo: These are deprecated, and should be removed at some
				// point (1.28 at the earliest, and see T121527). They were ok
				// when the core cookie-based login was the only thing, but
				// CentralAuth broke that a while back and
				// SessionManager/AuthManager *really* break it.
				$result['lgtoken'] = $user->getToken();
				$result['cookieprefix'] = $this->getConfig()->get( 'CookiePrefix' );
				$result['sessionid'] = $session->getId();
				break;

			case 'NeedToken':
				$result['token'] = $token->toString();
				$this->setWarning( 'Fetching a token via action=login is deprecated. ' .
				   'Use action=query&meta=tokens&type=login instead.' );
				$this->logFeatureUsage( 'action=login&!lgtoken' );

				// @todo: See above about deprecation
				$result['cookieprefix'] = $this->getConfig()->get( 'CookiePrefix' );
				$result['sessionid'] = $session->getId();
				break;

			case 'WrongToken':
				break;

			case 'Failed':
				$result['reason'] = $message->useDatabase( 'false' )->inLanguage( 'en' )->text();
				break;

			case 'Aborted':
				$result['reason'] = 'Authentication requires user interaction, ' .
				   'which is not supported by action=login.';
				if ( $this->getConfig()->get( 'EnableBotPasswords' ) ) {
					$result['reason'] .= ' To be able to login with action=login, see [[Special:BotPasswords]].';
					$result['reason'] .= ' To continue using main-account login, see action=clientlogin.';
				} else {
					$result['reason'] .= ' To log in, see action=clientlogin.';
				}
				break;

			// Results from LoginForm for when $wgDisableAuthManager is true
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

		if ( $loginType === 'LoginForm' && isset( LoginForm::$statusCodes[$authRes] ) ) {
			$authRes = LoginForm::$statusCodes[$authRes];
		}
		LoggerFactory::getInstance( 'authmanager' )->info( 'Login attempt', [
			'event' => 'login',
			'successful' => $authRes === 'Success',
			'loginType' => $loginType,
			'status' => $authRes,
		] );
	}

	public function isDeprecated() {
		return !$this->getConfig()->get( 'DisableAuthManager' ) &&
			!$this->getConfig()->get( 'EnableBotPasswords' );
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
