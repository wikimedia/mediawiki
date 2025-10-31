<?php
/**
 * Copyright © 2006-2007 Yuri Astrakhan "<Firstname><Lastname>@gmail.com",
 * Daniel Cannon (cannon dot danielc at gmail dot com)
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\User\BotPassword;
use MediaWiki\User\UserIdentityUtils;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Unit to authenticate log-in attempts to the current wiki.
 *
 * @ingroup API
 */
class ApiLogin extends ApiBase {

	private AuthManager $authManager;

	private UserIdentityUtils $identityUtils;

	/**
	 * @param ApiMain $main
	 * @param string $action
	 * @param AuthManager $authManager
	 * @param UserIdentityUtils $identityUtils IdentityUtils to retrieve account type
	 */
	public function __construct(
		ApiMain $main,
		string $action,
		AuthManager $authManager,
		UserIdentityUtils $identityUtils
	) {
		parent::__construct( $main, $action, 'lg' );
		$this->authManager = $authManager;
		$this->identityUtils = $identityUtils;
	}

	/** @inheritDoc */
	protected function getExtendedDescription() {
		if ( $this->getConfig()->get( MainConfigNames::EnableBotPasswords ) ) {
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
	 * Obtain an error code from a message, used for internal logs.
	 * @param Message|string|array $message
	 * @return string
	 */
	private function getErrorCode( $message ) {
		$message = Message::newFromSpecifier( $message );
		if ( $message instanceof ApiMessage ) {
			return $message->getApiCode();
		} else {
			return $message->getKey();
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
		$session = $this->getRequest()->getSession();
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
		$loginType = 'N/A';
		$performer = $this->getUser();

		// Check login token
		$token = $session->getToken( '', 'login' );
		if ( !$params['token'] ) {
			$authRes = 'NeedToken';
		} elseif ( $token->wasNew() ) {
			$authRes = 'Failed';
			$message = ApiMessage::create( 'authpage-cannot-login-continue', 'sessionlost' );
		} elseif ( !$token->match( $params['token'] ) ) {
			$authRes = 'WrongToken';
		}

		// Try bot passwords
		if ( $authRes === false && $this->getConfig()->get( MainConfigNames::EnableBotPasswords ) ) {
			$botLoginData = BotPassword::canonicalizeLoginData( $params['name'] ?? '', $params['password'] ?? '' );
			if ( $botLoginData ) {
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
			}
			// For other errors, let's see if it's a valid non-bot login
		}

		if ( $authRes === false ) {
			// Simplified AuthManager login, for backwards compatibility
			$reqs = AuthenticationRequest::loadRequestsFromSubmission(
				$this->authManager->getAuthenticationRequests(
					AuthManager::ACTION_LOGIN,
					$this->getUser()
				),
				[
					'username' => $params['name'],
					'password' => $params['password'],
					'domain' => $params['domain'],
					'rememberMe' => true,
				]
			);
			$res = $this->authManager->beginAuthentication( $reqs, 'null:' );
			switch ( $res->status ) {
				case AuthenticationResponse::PASS:
					if ( $this->getConfig()->get( MainConfigNames::EnableBotPasswords ) ) {
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
					LoggerFactory::getInstance( 'authentication' )
						->info( __METHOD__ . ': Authentication failed: '
						. $message->inLanguage( 'en' )->plain() );
					break;

				default:
					LoggerFactory::getInstance( 'authentication' )
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
				$user->debouncedDBTouch();

				// Deprecated hook
				$injected_html = '';
				$this->getHookRunner()->onUserLoginComplete( $user, $injected_html, true );

				$result['lguserid'] = $user->getId();
				$result['lgusername'] = $user->getName();
				break;

			case 'NeedToken':
				$result['token'] = $token->toString();
				$this->addDeprecation( 'apiwarn-deprecation-login-token', 'action=login&!lgtoken' );
				break;

			case 'WrongToken':
				break;

			case 'Failed':
				// @phan-suppress-next-next-line PhanTypeMismatchArgumentNullable,PhanPossiblyUndeclaredVariable
				// message set on error
				$result['reason'] = $this->formatMessage( $message );
				break;

			case 'Aborted':
				$result['reason'] = $this->formatMessage(
					$this->getConfig()->get( MainConfigNames::EnableBotPasswords )
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
			'accountType' => $this->identityUtils->getShortUserTypeInternal( $performer ),
			'loginType' => $loginType,
			'status' => ( $authRes === 'Failed' && isset( $message ) ) ? $this->getErrorCode( $message ) : $authRes,
			'full_message' => isset( $message ) ? $this->formatMessage( $message ) : '',
		] );
	}

	/** @inheritDoc */
	public function isDeprecated() {
		return !$this->getConfig()->get( MainConfigNames::EnableBotPasswords );
	}

	/** @inheritDoc */
	public function mustBePosted() {
		return true;
	}

	/** @inheritDoc */
	public function isReadMode() {
		return false;
	}

	/** @inheritDoc */
	public function isWriteMode() {
		// (T283394) Logging in triggers some database writes, so should be marked appropriately.
		return true;
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'name' => null,
			'password' => [
				ParamValidator::PARAM_TYPE => 'password',
			],
			'domain' => null,
			'token' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false, // for BC
				ParamValidator::PARAM_SENSITIVE => true,
				ApiBase::PARAM_HELP_MSG => [ 'api-help-param-token', 'login' ],
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=login&lgname=user&lgpassword=password&lgtoken=123ABC'
				=> 'apihelp-login-example-login',
		];
	}

	/** @inheritDoc */
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
			$ret['responseMessage'] = $response->message->inLanguage( 'en' )->plain();
		}
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

/** @deprecated class alias since 1.43 */
class_alias( ApiLogin::class, 'ApiLogin' );
