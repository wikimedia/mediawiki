<?php
/**
 * Created on Jan 30, 2015
 *
 * Copyright © 2015 Brad Jorsch <bjorsch@wikimedia.org>
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
 * @ingroup API
 * @since 1.25
 */
class ApiChangePassword extends ApiBase {

	/**
	 * Patrols the article or provides the reason the patrol failed.
	 */
	public function execute() {
		$params = $this->extractRequestParams();

		if ( $params['user'] !== null ) {
			$user = User::newFromName( $params['user'] );
			if ( !$user || $user->isAnon() ) {
				$this->dieUsage( 'Specified user does not exist', 'baduser_user' );
			}
		} else {
			$user = $this->getUser();
			if ( $user->isAnon() ) {
				$this->dieUsage( 'Please log in or use the "user" parameter', 'notloggedin' );
			}
		}

		// Make sure we have a token
		$token = LoginForm::getLoginToken();
		if ( !$token ) {
			LoginForm::setLoginToken();
			$token = LoginForm::getLoginToken();
		}

		if ( $params['token'] === null ) {
			$ret = array(
				'result' => 'needtoken',
				'token' => $token,
			);
		} elseif ( $params['token'] !== $token ) {
			$ret = array(
				'result' => 'wrongtoken',
			);
		} else {
			if ( $params['oldpassword'] === null || $params['oldpassword'] === '' ) {
				$this->dieUsageMsg( array( 'missingparam', 'oldpassword' ) );
			}
			if ( $params['newpassword'] === null || $params['newpassword'] === '' ) {
				$this->dieUsageMsg( array( 'missingparam', 'newpassword' ) );
			}

			$mangler = new UserMangler( $user, $this );
			$status = $mangler->changePassword(
				$params['oldpassword'], $params['newpassword'], $params['newpassword']
			);

			$ret = array();
			if ( $status->isGood() ) {
				$ret['status'] = 'success';
			} else {
				$ret['status'] = $status->isOk() ? 'warnings' : 'failure';
				$warnings = $this->getResult()->convertStatusToArray( $status, 'warning' );
				if ( $warnings ) {
					$ret['warnings'] = $warnings;
				}
				$errors = $this->getResult()->convertStatusToArray( $status, 'error' );
				if ( $errors ) {
					$ret['errors'] = $errors;
				}
			}
		}

		$this->getResult()->addValue( null, $this->getModuleName(), $ret );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return array(
			'user' => array(
				ApiBase::PARAM_TYPE => 'user',
			),
			'token' => array(
				ApiBase::PARAM_TYPE => 'string',
			),
			'oldpassword' => array(
				ApiBase::PARAM_TYPE => 'string',
			),
			'newpassword' => array(
				ApiBase::PARAM_TYPE => 'string',
			),
		);
	}

	protected function getExamplesMessages() {
		return array(
			'action=changepassword'
				=> 'apihelp-changepassword-example-token',
			'action=changepassword&oldpassword=old&newpassword=new&token=123ABC'
				=> 'apihelp-changepassword-example-simple',
		);
	}
}
