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
		$params = $this->extractRequestParams();

		$result = array();

		// Init session if necessary
		if ( session_id() == '' ) {
			wfSetupSession();
		}

		if( $params['mailpassword'] && !$params['email'] ) {
			$this->dieUsageMsg( 'noemail' );
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
				'wpCreateaccountMail' => $params['mailpassword'] ? '1' : null,
				'wpRemember' => ''
			)
		) );

		$loginForm = new LoginForm();
		$loginForm->setContext( $context );
		$loginForm->load();

		global $wgCookiePrefix, $wgPasswordAttemptThrottle;

		$status = $loginForm->addNewaccountInternal();
		$result = array();
		if( $status->isGood() ) {
			// Success!
			$user = $status->getValue();

			// If we showed up language selection links, and one was in use, be
			// smart (and sensible) and save that language as the user's preference
			global $wgLoginLanguageSelector, $wgEmailAuthentication;
			if( $wgLoginLanguageSelector && $params['language'] ) {
				$user->setOption( 'language', $params['language'] );
			}

			if( $params['mailpassword'] ) {
				// If mailpassword was set, disable the password and send an email.
				$user->setPassword( null );
				$status->merge( $loginForm->mailPasswordInternal( $user, false, 'createaccount-title', 'createaccount-text' ) );
			} elseif( $wgEmailAuthentication && Sanitizer::validateEmail( $user->getEmail() ) ) {
				// Send out an email authentication message if needed
				$status->merge( $user->sendConfirmationMail() );
			}

			// Save settings (including confirmation token)
			$user->saveSettings();

			wfRunHooks( 'AddNewAccount', array( $user, false ) );
			$user->addNewUserLogEntry( $this->getUser()->isAnon(), $params['reason'] );

			// Add username, id, and token to result.
			$result['username'] = $user->getName();
			$result['userid'] = $user->getId();
			$result['token'] = $user->getToken();
		}

		$apiResult = $this->getResult();

		if( $status->hasMessage( 'sessionfailure' ) ) {
			// Token was incorrect, so add it to result, but don't throw an exception.
			$result['token'] = LoginForm::getCreateaccountToken();
			$result['result'] = 'needtoken';
		} elseif( !$status->isOK() ) {
			// There was an error. Die now.
			// Cannot use dieUsageMsg() directly because extensions
			// might return custom error messages.
			$errors = $status->getErrorsArray();
			$code = array_shift( $errors[0] );
			$desc = wfMessage( $code, $errors[0] );
			$this->dieUsage( $desc, $code );
		} elseif( !$status->isGood() ) {
			// Status is not good, but OK. This means warnings.
			$result['result'] = 'warning';

			// Add any warnings to the result
			$warnings = $status->getErrorsByType( 'warning' );
			if( $warnings ) {
				foreach( $warnings as &$warning ) {
					$apiResult->setIndexedTagName( $warning['params'], 'param' );
				}
				$apiResult->setIndexedTagName( $warnings, 'warning' );
				$result['warnings'] = $warnings;
			}
		} else {
			// Everything was fine.
			$result['result'] = 'success';
		}

		$apiResult->addValue( null, 'createaccount', $result );
	}

	public function getDescription() {
		return 'Create a new user account.';
	}

	public function needsToken() {
		return true;
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
			'name' => 'User Name',
			'password' => "Password (ignored if {$p}mailpassword is set)",
			'domain' => 'Domain (optional)',
			'token' => 'Account creation token obtained in first request',
			'email' => 'Email address of user',
			'realname' => 'Real Name of user',
			'mailpassword' => 'Whether to generate and mail a random password to the user',
			'reason' => "Optional reason for creating the account (used when {$p}mailpassword is set)",
			'language' => 'Language code to set for the user.'
		);
	}

	public function getResultProperties() {
		return array(
			'createaccount' => array(
				'result' => array(
					ApiBase::PROP_TYPE => array(
						'success',
						'warning',
						'needtoken'
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
		$localErrors = array(
			'wrongpassword',
			'sessionfailure',
			'sorbs_create_account_reason',
			'noname',
			'userexists',
			'password-name-match',
			'password-login-forbidden',
			'noemailtitle',
			'invalidemailaddress',
			'externaldberror'
		);

		$errors = parent::getPossibleErrors();
		// All local errors are from LoginForm, which means they're actually message keys.
		foreach( $localErrors as $error ) {
			$errors[] = array( 'code' => $error, 'info' => wfMessage( $error )->parse() );
		}

		// 'passwordtooshort' has parameters. :(
		global $wgMinimalPasswordLength;
		$errors[] = array(
			'code' => 'passwordtooshort',
			'info' => wfMessage( 'passwordtooshort', $wgMinimalPasswordLength )->parse()
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
		return 'https://www.mediawiki.org/wiki/API:Account creation';
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
