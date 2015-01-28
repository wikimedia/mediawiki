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
class ApiResetPassword extends ApiBase {

	/**
	 * Patrols the article or provides the reason the patrol failed.
	 */
	public function execute() {
		$params = $this->extractRequestParams();

		$status = UserManager::canResetPassword( $this );
		if ( !$status->isGood() ) {
			$this->dieStatus( $status );
		}

		if ( isset( $params['email'] ) && $params['email'] !== '' &&
			!Sanitizer::validateEmail( $params['email'] )
		) {
			$this->dieUsage( 'The specified email address is not valid', 'badvalue_email' );
		}

		$resetRoutes = $this->getConfig()->get( 'PasswordResetRoutes' );
		$data = array_intersect_key( $params, $resetRoutes );
		$status = UserManager::resetPassword( $this, $data, $params['capture'] );

		if ( $status === false ) {
			$this->dieUsage( 'No user selection parameters were given non-empty values', 'missingparam' );
		}

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

		if ( $params['capture'] && $status->value->email instanceof Message ) {
			$ret['email'] = $status->value->email->text();
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
		$resetRoutes = $this->getConfig()->get( 'PasswordResetRoutes' );

		$ret = array();
		if ( !empty( $resetRoutes['username'] ) ) {
			$ret['username'] = array(
				ApiBase::PARAM_TYPE => 'user',
			);
		}
		if ( !empty( $resetRoutes['email'] ) ) {
			$ret['email'] = array(
				ApiBase::PARAM_TYPE => 'string',
			);
		}
		if ( !empty( $resetRoutes['domain'] ) ) {
			$ret['domain'] = array(
				ApiBase::PARAM_TYPE => 'string',
			);
		}
		$ret += array(
			'capture' => array(
				ApiBase::PARAM_TYPE => 'boolean',
			),
		);

		return $ret;
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getExamplesMessages() {
		return array(
			'action=resetpassword&user=Example&token=123ABC'
				=> 'apihelp-resetpassword-example-user',
			'action=resetpassword&email=user@example.com&token=123ABC'
				=> 'apihelp-resetpassword-example-email',
			'action=resetpassword&user=Example&capture=1&token=123ABC'
				=> 'apihelp-resetpassword-example-user-capture',
		);
	}
}
