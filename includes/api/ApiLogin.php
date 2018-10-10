<?php
/**
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

	protected function getExtendedDescription() {
		if ( $this->getConfig()->get( 'EnableBotPasswords' ) ) {
			return 'apihelp-login-extended-description';
		} else {
			return 'apihelp-login-extended-description-nobotpasswords';
		}
	}

	/**
	 * Format a message for the response
	 * @param Message|string|array $message
	 * @return string|array
	 */
	private function formatMessage( $message ) {
		$message = Message::newFromSpecifier( $message );
		$errorFormatter = $this->getErrorFormatter();
		if ( $errorFormatter instanceof ApiErrorFormatter_BackCompat ) {
			return ApiErrorFormatter::stripMarkup(
				$message->useDatabase( false )->inLanguage( 'en' )->text()
			);
		} else {
			return $errorFormatter->formatMessage( $message );
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
				'reason' => $this->formatMessage( 'api-login-fail-sameorigin' ),
			] );

			return;
		}

		$this->requirePostedParameters( [ 'password', 'token' ] );

		$params = $this->extractRequestParams();

		$result = [];

		// Make sure session is persisted
		$session = MediaWiki\Session\SessionManager::getGlobalSession();
		$session->persist();

		// Make sure it's possible to log in
		if ( !$session->canSetUser() ) {
			$this->getResult()->addValue( null, 'login', [
				'result' => 'Aborted',
				'reason' => $this->formatMessage( [
					'api-login-fail-badsessionprovider',
					$session->getProvider()->describe( $this->getErrorFormatter()->getLanguage() ),
				] )
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
		if (
			$authRes === false && $this->getConfig()->get( 'EnableBotPasswords' ) &&
			( $botLoginData = BotPassword::canonicalizeLoginData( $params['name'], $params['password'] ) )
		) {
			$status = BotPassword::login(
				$botLoginData[0], $botLoginData[1], $this->getRequest()
			);
			if ( $status->isOK() ) {
				$session = $status->getValue();
				$authRes = 'Success';
				$loginType = 'BotPassword';
			} elseif (
				$status->hasMessage( 'login-throttled' ) ||
				$status->hasMessage( 'botpasswords-needs-reset' ) ||
				$status->hasMessage( 'botpasswords-locked' )
			) {
				$authRes = 'Failed';
				$message = $status->getMessage();
				LoggerFactory::getInstance( 'authentication' )->info(
					'BotPassword login failed: ' . $status->getWikiText( false, false, 'en' )
				);
			}
			// For other errors, let's see if it's a valid non-bot login
		}

		if ( $authRes === false ) {
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
						$this->addDeprecation( 'apiwarn-deprecation-login-botpw', 'main-account-login' );
					} else {
						$this->addDeprecation( 'apiwarn-deprecation-login-nobotpw', 'main-account-login' );
					}
					$authRes = 'Success';
					$loginType = 'AuthManager';
					break;

				case AuthenticationResponse::FAIL:
					// Hope it's not a PreAuthenticationProvider that failed...
					$authRes = 'Failed';
					$message = $res->message;
					\MediaWiki\Logger\LoggerFactory::getInstance( 'authentication' )
						->info( __METHOD__ . ': Authentication failed: '
						. $message->inLanguage( 'en' )->plain() );
					break;

				default:
					\MediaWiki\Logger\LoggerFactory::getInstance( 'authentication' )
						->info( __METHOD__ . ': Authentication failed due to unsupported response type: '
						. $res->status, $this->getAuthenticationResponseLogData( $res ) );
					$authRes = 'Aborted';
					break;
			}
		}

		$result['result'] = $authRes;
		switch ( $authRes ) {
			case 'Success':
				$user = $session->getUser();

				ApiQueryInfo::resetTokenCache();

				// Deprecated hook
				$injected_html = '';
				Hooks::run( 'UserLoginComplete', [ &$user, &$injected_html, true ] );

				$result['lguserid'] = intval( $user->getId() );
				$result['lgusername'] = $user->getName();
				break;

			case 'NeedToken':
				$result['token'] = $token->toString();
				$this->addDeprecation( 'apiwarn-deprecation-login-token', 'action=login&!lgtoken' );
				break;

			case 'WrongToken':
				break;

			case 'Failed':
				$result['reason'] = $this->formatMessage( $message );
				break;

			case 'Aborted':
				$result['reason'] = $this->formatMessage(
					$this->getConfig()->get( 'EnableBotPasswords' )
						? 'api-login-fail-aborted'
						: 'api-login-fail-aborted-nobotpw'
				);
				break;

			// @codeCoverageIgnoreStart
			// Unreachable
			default:
				ApiBase::dieDebug( __METHOD__, "Unhandled case value: {$authRes}" );
			// @codeCoverageIgnoreEnd
		}

		$this->getResult()->addValue( null, 'login', $result );

		LoggerFactory::getInstance( 'authevents' )->info( 'Login attempt', [
			'event' => 'login',
			'successful' => $authRes === 'Success',
			'loginType' => $loginType,
			'status' => $authRes,
		] );
	}

	public function isDeprecated() {
		return !$this->getConfig()->get( 'EnableBotPasswords' );
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
				ApiBase::PARAM_SENSITIVE => true,
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
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Login';
	}

	/**
	 * Turns an AuthenticationResponse into a hash suitable for passing to Logger
	 * @param AuthenticationResponse $response
	 * @return array
	 */
	protected function getAuthenticationResponseLogData( AuthenticationResponse $response ) {
		$ret = [
			'status' => $response->status,
		];
		if ( $response->message ) {
			$ret['message'] = $response->message->inLanguage( 'en' )->plain();
		};
		$reqs = [
			'neededRequests' => $response->neededRequests,
			'createRequest' => $response->createRequest,
			'linkRequest' => $response->linkRequest,
		];
		foreach ( $reqs as $k => $v ) {
			if ( $v ) {
				$v = is_array( $v ) ? $v : [ $v ];
				$reqClasses = array_unique( array_map( 'get_class', $v ) );
				sort( $reqClasses );
				$ret[$k] = implode( ', ', $reqClasses );
			}
		}
		return $ret;
	}
}
