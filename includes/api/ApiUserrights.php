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

	/**
	 * Get a UserrightsPage object, or subclass.
	 * @return UserrightsPage
	 */
	protected function getUserRightsPage() {
		return new UserrightsPage;
	}

	/**
	 * Get all available groups.
	 * @return array
	 */
	protected function getAllGroups() {
		return User::getAllGroups();
	}

	public function execute() {
		$params = $this->extractRequestParams();

		$user = $this->getUrUser( $params );

		$form = $this->getUserRightsPage();
		$form->setContext( $this->getContext() );
		$r['user'] = $user->getName();
		$r['userid'] = $user->getId();
		list( $r['added'], $r['removed'] ) = $form->doSaveUserGroups(
			$user, (array)$params['add'],
			(array)$params['remove'], $params['reason']
		);

		$result = $this->getResult();
		ApiResult::setIndexedTagName( $r['added'], 'group' );
		ApiResult::setIndexedTagName( $r['removed'], 'group' );
		$result->addValue( null, $this->getModuleName(), $r );
	}

	/**
	 * @param array $params
	 * @return User
	 */
	private function getUrUser( array $params ) {
		if ( $this->mUser !== null ) {
			return $this->mUser;
		}

		$this->requireOnlyOneParameter( $params, 'user', 'userid' );

		$user = isset( $params['user'] ) ? $params['user'] : '#' . $params['userid'];

		$form = $this->getUserRightsPage();
		$form->setContext( $this->getContext() );
		$status = $form->fetchUser( $user );
		if ( !$status->isOK() ) {
			$this->dieStatus( $status );
		}

		$this->mUser = $status->value;

		return $status->value;
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
			),
			'userid' => array(
				ApiBase::PARAM_TYPE => 'integer',
			),
			'add' => array(
				ApiBase::PARAM_TYPE => $this->getAllGroups(),
				ApiBase::PARAM_ISMULTI => true
			),
			'remove' => array(
				ApiBase::PARAM_TYPE => $this->getAllGroups(),
				ApiBase::PARAM_ISMULTI => true
			),
			'reason' => array(
				ApiBase::PARAM_DFLT => ''
			),
			'token' => array(
				// Standard definition automatically inserted
				ApiBase::PARAM_HELP_MSG_APPEND => array( 'api-help-param-token-webui' ),
			),
		);
	}

	public function needsToken() {
		return 'userrights';
	}

	protected function getWebUITokenSalt( array $params ) {
		return $this->getUrUser( $params )->getName();
	}

	protected function getExamplesMessages() {
		return array(
			'action=userrights&user=FooBot&add=bot&remove=sysop|bureaucrat&token=123ABC'
				=> 'apihelp-userrights-example-user',
			'action=userrights&userid=123&add=bot&remove=sysop|bureaucrat&token=123ABC'
				=> 'apihelp-userrights-example-userid',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:User_group_membership';
	}
}
