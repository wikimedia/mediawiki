<?php

/**
 * Created on Mar 24, 2009
 * API for MediaWiki 1.8+
 *
 * Copyright © 2009 Roan Kattouw <Firstname>.<Lastname>@home.nl
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
 * @ingroup API
 */
class ApiUserrights extends ApiBase {

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

	private $mUser = null;

	public function execute() {
		$params = $this->extractRequestParams();

		$user = $this->getUser();

		$form = new UserrightsPage;
		$r['user'] = $user->getName();
		list( $r['added'], $r['removed'] ) =
			$form->doSaveUserGroups(
				$user, (array)$params['add'],
				(array)$params['remove'], $params['reason'] );

		$this->getResult()->setIndexedTagName( $r['added'], 'group' );
		$this->getResult()->setIndexedTagName( $r['removed'], 'group' );
		$this->getResult()->addValue( null, $this->getModuleName(), $r );
	}
	
	private function getUser() {
		if ( $this->mUser !== null ) {
			return $this->mUser;
		}

		$params = $this->extractRequestParams();
		if ( is_null( $params['user'] ) ) {
			$this->dieUsageMsg( array( 'missingparam', 'user' ) );
		}

		$form = new UserrightsPage;
		$status = $form->fetchUser( $params['user'] );
		if ( !$status->isOK() ) {
			$errors = $status->getErrorsArray();
			$this->dieUsageMsg( $errors[0] );
		} else {
			$user = $status->value;
		}

		$this->mUser = $user;
		return $user;
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return array (
			'user' => null,
			'add' => array(
				ApiBase::PARAM_TYPE => User::getAllGroups(),
				ApiBase::PARAM_ISMULTI => true
			),
			'remove' => array(
				ApiBase::PARAM_TYPE => User::getAllGroups(),
				ApiBase::PARAM_ISMULTI => true
			),
			'token' => null,
			'reason' => array(
				ApiBase::PARAM_DFLT => ''
			)
		);
	}

	public function getParamDescription() {
		return array(
			'user' => 'User name',
			'add' => 'Add the user to these groups',
			'remove' => 'Remove the user from these groups',
			'token' => 'A userrights token previously retrieved through list=users',
			'reason' => 'Reason for the change',
		);
	}

	public function getDescription() {
		return 'Add/remove a user to/from groups';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'missingparam', 'user' ),
		) );
	}

	public function getTokenSalt() {
		return $this->getUser()->getName();
	}

	protected function getExamples() {
		return array(
			'api.php?action=userrights&user=FooBot&add=bot&remove=sysop|bureaucrat&token=123ABC'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
