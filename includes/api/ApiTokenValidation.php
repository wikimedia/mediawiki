<?php
/**
 *
 *
 * Created on Aug 16, 2012
 *
 * Copyright © 2012 Ashish Dubey <Firstname><Lastname>@gmail.com
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
 * @defgroup API API
 */

/**
 * API Module for third party validation of logged-in users' tokens.
 * @ingroup API
 */

class ApiTokenValidation extends ApiBase {

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

	public function execute() {
		$params = $this->extractRequestParams();
		$mode = $params['mode'];
		$result = $this->getResult();
		$result_array = array();

		switch ( $mode ) {
			case 'generate':
				$token = $this->generateToken();
				$result_array['token'] = $token;

				break;
			case 'verify':
				$req_token = $params['token'];
				$req_username = $params['username'];

				if ( is_null( $req_username ) ) {
					$result_array['matches'] = 'false';
					break;
				}

				if ( $this->verifyToken( $req_username, $req_token ) ) {
					$result_array['matches'] = 'true';
				} else {
					$result_array['matches'] = 'false';
				}

				break;
			case 'destroy':
				if ( $this->destroyToken() ) {
					$result_array['result'] = 'true';
				} else {
					$result_array['result'] = 'false';
				}

				break;
		}
		$result->addValue( null, 'TokenValidationResponse', $result_array );
	}

	public function generateToken() {
		global $wgUser;

		$token = MWCryptRand::generateHex( 32 );
		$wgUser->setOption( 'validation_token', md5( $token ) );
		$wgUser->saveSettings();

		return $token;
	}

	public function verifyToken( $userName, $token ) {
		$user = User::newFromName( $userName );

		if ( $user->isAnon() ) {
			return false;
		}

		$validationToken = $user->getOption( 'validation_token' );
		if ( md5( $token ) === $validationToken ) {
			return true;
		} else {
			return false;
		}
	}

	public function destroyToken() {
		global $wgUser;
		if ( $wgUser->isAnon() ) {
			return false;
		}

		$wgUser->setOption( 'validation_token', null );
		$wgUser->saveSettings();
		return true;
	}

	public function getAllowedParams() {
		return array(
			'mode' => array(
				ApiBase::PARAM_TYPE => array(
					'generate',
					'verify',
					'destroy'
				),
				ApiBase::PARAM_REQUIRED => true
			),
			'username' => array(
				ApiBase::PARAM_TYPE => 'string'
			),
			'token' => array(
				ApiBase::PARAM_TYPE => 'string'
			)
		);
	}

	public function getParamDescription() {
		return array(
			'mode' => 'Specifies the operation.',
			'username' => 'Name of the user whose token is to be validated(mode=verify).',
			'token' => 'Token to validate against(mode=verify).'
		);
	}

	public function getExamples() {
		return array(
			'api.php?action=validatetoken&mode=generate' => 'Generates a new token for the currently logged in user.',
			'api.php?action=validatetoken&mode=verify&username=Foo&token=bar' => 'Checks if token of the user "Foo" is "bar".',
			'api.php?action=validatetoken&mode=destroy' => 'Destroys the token stored for the currently logged in user.'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}