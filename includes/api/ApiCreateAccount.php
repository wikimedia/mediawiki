<?php
/**
 * Created on August 7, 2012
 *
 * Copyright © 2012 Tyler Romeo <tylerromeo@gmail.com>
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
 * Unit to authenticate account registration attempts to the current wiki.
 *
 * @ingroup API
 */
class ApiCreateAccount extends ApiBase {
	public function execute() {
		// If we're in a mode that breaks the same-origin policy, no tokens can
		// be obtained
		if ( $this->lacksSameOriginSecurity() ) {
			$this->dieUsage(
				'Cannot create account when the same-origin policy is not applied', 'aborted'
			);
		}

		// $loginForm->addNewaccountInternal will throw exceptions
		// if wiki is read only (already handled by api), user is blocked or does not have rights.
		// Use userCan in order to hit GlobalBlock checks (according to Special:userlogin)
		$loginTitle = SpecialPage::getTitleFor( 'Userlogin' );
		if ( !$loginTitle->userCan( 'createaccount', $this->getUser() ) ) {
			$this->dieUsage(
				'You do not have the right to create a new account',
				'permdenied-createaccount'
			);
		}
		if ( $this->getUser()->isBlockedFromCreateAccount() ) {
			$this->dieUsage( 'You cannot create a new account because you are blocked', 'blocked' );
		}

		$params = $this->extractRequestParams();

		// Init session if necessary
		if ( session_id() == '' ) {
			wfSetupSession();
		}

		if ( $params['mailpassword'] && !$params['email'] ) {
			$this->dieUsageMsg( 'noemail' );
		}

		if ( $params['language'] && !Language::isSupportedLanguage( $params['language'] ) ) {
			$this->dieUsage( 'Invalid language parameter', 'langinvalid' );
		}

		$context = new DerivativeContext( $this->getContext() );
		$context->setRequest( new DerivativeRequest(
			$this->getContext()->getRequest(),
			array(
				'type' => 'signup',
				'uselang' => $params['language'],
				'wpName' => $params['name'],
				'wpPassword' => $params['password'],
				'wpRetype' => $params['password'],
				'wpDomain' => $params['domain'],
				'wpEmail' => $params['email'],
				'wpRealName' => $params['realname'],
				'wpCreateaccountToken' => $params['token'],
				'wpCreateaccount' => $params['mailpassword'] ? null : '1',
				'wpCreateaccountMail' => $params['mailpassword'] ? '1' : null
			)
		) );

		$loginForm = new LoginForm();
		$loginForm->setContext( $context );
		Hooks::run( 'AddNewAccountApiForm', array( $this, $loginForm ) );
		$loginForm->load();

		$status = $loginForm->addNewaccountInternal();
		$result = array();
		if ( $status->isGood() ) {
			// Success!
			$user = $status->getValue();

			if ( $params['language'] ) {
				$user->setOption( 'language', $params['language'] );
			}

			if ( $params['mailpassword'] ) {
				// If mailpassword was set, disable the password and send an email.
				$user->setPassword( null );
				$status->merge( $loginForm->mailPasswordInternal(
					$user,
					false,
					'createaccount-title',
					'createaccount-text'
				) );
			} elseif ( $this->getConfig()->get( 'EmailAuthentication' ) && Sanitizer::validateEmail( $user->getEmail() ) ) {
				// Send out an email authentication message if needed
				$status->merge( $user->sendConfirmationMail() );
			}

			// Save settings (including confirmation token)
			$user->saveSettings();

			Hooks::run( 'AddNewAccount', array( $user, $params['mailpassword'] ) );

			if ( $params['mailpassword'] ) {
				$logAction = 'byemail';
			} elseif ( $this->getUser()->isLoggedIn() ) {
				$logAction = 'create2';
			} else {
				$logAction = 'create';
			}
			$user->addNewUserLogEntry( $logAction, (string)$params['reason'] );

			// Add username, id, and token to result.
			$result['username'] = $user->getName();
			$result['userid'] = $user->getId();
			$result['token'] = $user->getToken();
		}

		$apiResult = $this->getResult();

		if ( $status->hasMessage( 'sessionfailure' ) || $status->hasMessage( 'nocookiesfornew' ) ) {
			// Token was incorrect, so add it to result, but don't throw an exception
			// since not having the correct token is part of the normal
			// flow of events.
			$result['token'] = LoginForm::getCreateaccountToken();
			$result['result'] = 'NeedToken';
		} elseif ( !$status->isOK() ) {
			// There was an error. Die now.
			$this->dieStatus( $status );
		} elseif ( !$status->isGood() ) {
			// Status is not good, but OK. This means warnings.
			$result['result'] = 'Warning';

			// Add any warnings to the result
			$warnings = $status->getErrorsByType( 'warning' );
			if ( $warnings ) {
				foreach ( $warnings as &$warning ) {
					ApiResult::setIndexedTagName( $warning['params'], 'param' );
				}
				ApiResult::setIndexedTagName( $warnings, 'warning' );
				$result['warnings'] = $warnings;
			}
		} else {
			// Everything was fine.
			$result['result'] = 'Success';
		}

		// Give extensions a chance to modify the API result data
		Hooks::run( 'AddNewAccountApiResult', array( $this, $loginForm, &$result ) );

		$apiResult->addValue( null, 'createaccount', $result );
	}

	public function mustBePosted() {
		return true;
	}

	public function isReadMode() {
		return false;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return array(
			'name' => array(
				ApiBase::PARAM_TYPE => 'user',
				ApiBase::PARAM_REQUIRED => true
			),
			'password' => null,
			'domain' => null,
			'token' => null,
			'email' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => $this->getConfig()->get( 'EmailConfirmToEdit' ),
			),
			'realname' => null,
			'mailpassword' => array(
				ApiBase::PARAM_TYPE => 'boolean',
				ApiBase::PARAM_DFLT => false
			),
			'reason' => null,
			'language' => null
		);
	}

	protected function getExamplesMessages() {
		return array(
			'action=createaccount&name=testuser&password=test123'
				=> 'apihelp-createaccount-example-pass',
			'action=createaccount&name=testmailuser&mailpassword=true&reason=MyReason'
				=> 'apihelp-createaccount-example-mail',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Account_creation';
	}
}
