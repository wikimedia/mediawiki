<?php

/**
 *
 *
 * Created on Mar 24, 2009
 *
 * Copyright © 2009 Roan Kattouw "<Firstname>.<Lastname>@gmail.com"
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
 */
class ApiUserrights extends ApiBase {

	private $mUser = null;

	public function execute() {
		$params = $this->extractRequestParams();

		$user = $this->getUrUser();

		$form = new UserrightsPage;
		$form->setContext( $this->getContext() );
		$r['user'] = $user->getName();
		$r['userid'] = $user->getId();
		list( $r['added'], $r['removed'] ) =
			$form->doSaveUserGroups(
				$user, (array)$params['add'],
				(array)$params['remove'], $params['reason'] );

		$result = $this->getResult();
		$result->setIndexedTagName( $r['added'], 'group' );
		$result->setIndexedTagName( $r['removed'], 'group' );
		$result->addValue( null, $this->getModuleName(), $r );
	}

	/**
	 * @return User
	 */
	private function getUrUser() {
		if ( $this->mUser !== null ) {
			return $this->mUser;
		}

		$params = $this->extractRequestParams();

		$form = new UserrightsPage;
		$form->setContext( $this->getContext() );
		$status = $form->fetchUser( $params['user'] );
		if ( !$status->isOK() ) {
			$this->dieStatus( $status );
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
		return array(
			'user' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'add' => array(
				ApiBase::PARAM_TYPE => User::getAllGroups(),
				ApiBase::PARAM_ISMULTI => true
			),
			'remove' => array(
				ApiBase::PARAM_TYPE => User::getAllGroups(),
				ApiBase::PARAM_ISMULTI => true
			),
			'token' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
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

	public function needsToken() {
		return true;
	}

	public function getTokenSalt() {
		return $this->getUrUser()->getName();
	}

	public function getExamples() {
		return array(
			'api.php?action=userrights&user=FooBot&add=bot&remove=sysop|bureaucrat&token=123ABC'
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:User_group_membership';
	}
}
