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
class ApiChangeEmail extends ApiBase {

	/**
	 * Patrols the article or provides the reason the patrol failed.
	 */
	public function execute() {
		if ( $this->getUser()->isAnon() ) {
			$this->dieUsage( 'Anonymous users cannot change preferences', 'notloggedin' );
		}

		$params = $this->extractRequestParams();
		if ( $params['user'] !== null ) {
			$user = User::newFromName( $params['user'] );
			if ( !$user || $user->isAnon() ) {
				$this->dieUsage( 'Specified user does not exist', 'baduser_user' );
			}
		} else {
			$user = $this->getUser();
		}

		if ( $params['email'] !== '' && !Sanitizer::validateEmail( $params['email'] ) ) {
			$this->dieUsage( 'The specified email address is not valid', 'badvalue_email' );
		}

		$manager = new UserManager( $user, $this );
		$status = $manager->changeEmail( $params['password'], $params['email'] );

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

		$this->getResult()->addValue( null, $this->getModuleName(), $ret );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		$ret = array(
			'user' => array(
				ApiBase::PARAM_TYPE => 'user',
			),
			'password' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
			),
			'email' => array(
				ApiBase::PARAM_REQUIRED => true,
			),
		);

		if ( !$this->getConfig()->get( 'RequirePasswordforEmailChange' ) ) {
			unset( $ret['password'] );
		}

		return $ret;
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getExamplesMessages() {
		$pass = $this->getConfig()->get( 'RequirePasswordforEmailChange' )
			? '&password=secret'
			: '';
		return array(
			"action=changeemail&email=user@example.com{$pass}&token=123ABC"
				=> 'apihelp-changeemail-example-simple',
		);
	}
}
