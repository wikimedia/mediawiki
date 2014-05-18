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
		// If we're in JSON callback mode, no tokens can be obtained
		if ( !is_null( $this->getMain()->getRequest()->getVal( 'callback' ) ) ) {
			$this->dieUsage( 'Cannot create account when using a callback', 'aborted' );
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
		wfRunHooks( 'AddNewAccountApiForm', array( $this, $loginForm ) );
		$loginForm->load();

		$status = $loginForm->addNewaccountInternal();
		$result = array();
		if ( $status->isGood() ) {
			// Success!
			global $wgEmailAuthentication;
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
			} elseif ( $wgEmailAuthentication && Sanitizer::validateEmail( $user->getEmail() ) ) {
				// Send out an email authentication message if needed
				$status->merge( $user->sendConfirmationMail() );
			}

			// Save settings (including confirmation token)
			$user->saveSettings();

			wfRunHooks( 'AddNewAccount', array( $user, $params['mailpassword'] ) );

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
					$apiResult->setIndexedTagName( $warning['params'], 'param' );
				}
				$apiResult->setIndexedTagName( $warnings, 'warning' );
				$result['warnings'] = $warnings;
			}
		} else {
			// Everything was fine.
			$result['result'] = 'Success';
		}

		// Give extensions a chance to modify the API result data
		wfRunHooks( 'AddNewAccountApiResult', array( $this, $loginForm, &$result ) );

		$apiResult->addValue( null, 'createaccount', $result );
	}

	public function getDescription() {
		return 'Create a new user account.';
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
		global $wgEmailConfirmToEdit;

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
				ApiBase::PARAM_REQUIRED => $wgEmailConfirmToEdit
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

	public function getParamDescription() {
		$p = $this->getModulePrefix();

		return array(
			'name' => 'Username',
			'password' => "Password (ignored if {$p}mailpassword is set)",
			'domain' => 'Domain for external authentication (optional)',
			'token' => 'Account creation token obtained in first request',
			'email' => 'Email address of user (optional)',
			'realname' => 'Real name of user (optional)',
			'mailpassword' => 'If set to any value, a random password will be emailed to the user',
			'reason' => 'Optional reason for creating the account to be put in the logs',
			'language'
				=> 'Language code to set as default for the user (optional, defaults to content language)'
		);
	}

	public function getResultProperties() {
		return array(
			'createaccount' => array(
				'result' => array(
					ApiBase::PROP_TYPE => array(
						'Success',
						'Warning',
						'NeedToken'
					)
				),
				'username' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'userid' => array(
					ApiBase::PROP_TYPE => 'int',
					ApiBase::PROP_NULLABLE => true
				),
				'token' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
			)
		);
	}

	public function getPossibleErrors() {
		// Note the following errors aren't possible and don't need to be listed:
		// sessionfailure, nocookiesfornew, badretype
		$localErrors = array(
			'wrongpassword', // Actually caused by wrong domain field. Riddle me that...
			'sorbs_create_account_reason',
			'noname',
			'userexists',
			'password-name-match', // from User::getPasswordValidity
			'password-login-forbidden', // from User::getPasswordValidity
			'noemailtitle',
			'invalidemailaddress',
			'externaldberror',
			'acct_creation_throttle_hit',
		);

		$errors = parent::getPossibleErrors();
		// All local errors are from LoginForm, which means they're actually message keys.
		foreach ( $localErrors as $error ) {
			$errors[] = array(
				'code' => $error,
				'info' => wfMessage( $error )->inLanguage( 'en' )->useDatabase( false )->parse()
			);
		}

		$errors[] = array(
			'code' => 'permdenied-createaccount',
			'info' => 'You do not have the right to create a new account'
		);
		$errors[] = array(
			'code' => 'blocked',
			'info' => 'You cannot create a new account because you are blocked'
		);
		$errors[] = array(
			'code' => 'aborted',
			'info' => 'Account creation aborted by hook (info may vary)'
		);
		$errors[] = array(
			'code' => 'langinvalid',
			'info' => 'Invalid language parameter'
		);

		// 'passwordtooshort' has parameters. :(
		global $wgMinimalPasswordLength;
		$errors[] = array(
			'code' => 'passwordtooshort',
			'info' => wfMessage( 'passwordtooshort', $wgMinimalPasswordLength )
				->inLanguage( 'en' )->useDatabase( false )->parse()
		);

		return $errors;
	}

	public function getExamples() {
		return array(
			'api.php?action=createaccount&name=testuser&password=test123',
			'api.php?action=createaccount&name=testmailuser&mailpassword=true&reason=MyReason',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Account_creation';
	}
}
