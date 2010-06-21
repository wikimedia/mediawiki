<?php

/**
 * Created on June 1, 2008
 * API for MediaWiki 1.8+
 *
 * Copyright © 2008 Bryan Tong Minh <Bryan.TongMinh@Gmail.com>
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
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( "ApiBase.php" );
}

/**
 * API Module to facilitate sending of emails to users
 * @ingroup API
 */
class ApiEmailUser extends ApiBase {

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

	public function execute() {
		global $wgUser;

		$params = $this->extractRequestParams();
		// Check required parameters
		if ( !isset( $params['target'] ) ) {
			$this->dieUsageMsg( array( 'missingparam', 'target' ) );
		}
		if ( !isset( $params['text'] ) ) {
			$this->dieUsageMsg( array( 'missingparam', 'text' ) );
		}

		// Validate target
		$targetUser = SpecialEmailuser::getTarget( $params['target'] );
		if ( !( $targetUser instanceof User ) ) {
			$this->dieUsageMsg( array( $targetUser ) );
		}

		// Check permissions and errors
		$error = SpecialEmailuser::getPermissionsError( $wgUser, $params['token'] );
		if ( $error ) {
			$this->dieUsageMsg( array( $error ) );
		}

		$data = array(
			'Target' => $targetUser->getName(),
			'Text' => $params['text'],
			'Subject' => $params['subject'],
			'CCMe' => $params['ccme'],
		);
		$retval = SpecialEmailuser::submit( $data );
		if ( $retval === true ) {
			$result = array( 'result' => 'Success' );
		} else {
			$result = array(
				'result' => 'Failure',
				'message' => $retval
			);
		}

		$this->getResult()->addValue( null, $this->getModuleName(), $result );
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return array(
			'target' => null,
			'subject' => null,
			'text' => null,
			'token' => null,
			'ccme' => false,
		);
	}

	public function getParamDescription() {
		return array(
			'target' => 'User to send email to',
			'subject' => 'Subject header',
			'text' => 'Mail body',
			'token' => 'A token previously acquired via prop=info',
			'ccme' => 'Send a copy of this mail to me',
		);
	}

	public function getDescription() {
		return 'Email a user.';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'usermaildisabled' ),
			array( 'missingparam', 'target' ),
			array( 'missingparam', 'text' ),
		) );
	}

	public function getTokenSalt() {
		return '';
	}

	protected function getExamples() {
		return array(
			'api.php?action=emailuser&target=WikiSysop&text=Content'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
