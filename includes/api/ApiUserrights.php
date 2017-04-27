<?php

/**
 * API userrights module
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
		$pUser = $this->getUser();

		// Deny if the user is blocked and doesn't have the full 'userrights' permission.
		// This matches what Special:UserRights does for the web UI.
		if ( $pUser->isBlocked() && !$pUser->isAllowed( 'userrights' ) ) {
			$this->dieBlocked( $pUser->getBlock() );
		}

		$params = $this->extractRequestParams();

		// Figure out expiry times from the input
		// $params['expiry'] may not be set in subclasses
		if ( isset( $params['expiry'] ) ) {
			$expiry = (array)$params['expiry'];
		} else {
			$expiry = [ 'infinity' ];
		}
		if ( count( $expiry ) !== count( $params['add'] ) ) {
			if ( count( $expiry ) === 1 ) {
				$expiry = array_fill( 0, count( $params['add'] ), $expiry[0] );
			} else {
				$this->dieWithError( [
					'apierror-toofewexpiries',
					count( $expiry ),
					count( $params['add'] )
				] );
			}
		}

		// Validate the expiries
		$groupExpiries = [];
		foreach ( $expiry as $index => $expiryValue ) {
			$group = $params['add'][$index];
			$groupExpiries[$group] = UserrightsPage::expiryToTimestamp( $expiryValue );

			if ( $groupExpiries[$group] === false ) {
				$this->dieWithError( [ 'apierror-invalidexpiry', wfEscapeWikiText( $expiryValue ) ] );
			}

			// not allowed to have things expiring in the past
			if ( $groupExpiries[$group] && $groupExpiries[$group] < wfTimestampNow() ) {
				$this->dieWithError( [ 'apierror-pastexpiry', wfEscapeWikiText( $expiryValue ) ] );
			}
		}

		$user = $this->getUrUser( $params );

		$tags = $params['tags'];

		// Check if user can add tags
		if ( !is_null( $tags ) ) {
			$ableToTag = ChangeTags::canAddTagsAccompanyingChange( $tags, $pUser );
			if ( !$ableToTag->isOK() ) {
				$this->dieStatus( $ableToTag );
			}
		}

		$form = $this->getUserRightsPage();
		$form->setContext( $this->getContext() );
		$r['user'] = $user->getName();
		$r['userid'] = $user->getId();
		list( $r['added'], $r['removed'] ) = $form->doSaveUserGroups(
			$user, (array)$params['add'], (array)$params['remove'],
			$params['reason'], $tags, $groupExpiries
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
		$a = [
			'user' => [
				ApiBase::PARAM_TYPE => 'user',
			],
			'userid' => [
				ApiBase::PARAM_TYPE => 'integer',
			],
			'add' => [
				ApiBase::PARAM_TYPE => $this->getAllGroups(),
				ApiBase::PARAM_ISMULTI => true
			],
			'expiry' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_ALLOW_DUPLICATES => true,
				ApiBase::PARAM_DFLT => 'infinite',
			],
			'remove' => [
				ApiBase::PARAM_TYPE => $this->getAllGroups(),
				ApiBase::PARAM_ISMULTI => true
			],
			'reason' => [
				ApiBase::PARAM_DFLT => ''
			],
			'token' => [
				// Standard definition automatically inserted
				ApiBase::PARAM_HELP_MSG_APPEND => [ 'api-help-param-token-webui' ],
			],
			'tags' => [
				ApiBase::PARAM_TYPE => 'tags',
				ApiBase::PARAM_ISMULTI => true
			],
		];
		if ( !$this->getUserRightsPage()->canProcessExpiries() ) {
			unset( $a['expiry'] );
		}
		return $a;
	}

	public function needsToken() {
		return 'userrights';
	}

	protected function getWebUITokenSalt( array $params ) {
		return $this->getUrUser( $params )->getName();
	}

	protected function getExamplesMessages() {
		$a = [
			'action=userrights&user=FooBot&add=bot&remove=sysop|bureaucrat&token=123ABC'
				=> 'apihelp-userrights-example-user',
			'action=userrights&userid=123&add=bot&remove=sysop|bureaucrat&token=123ABC'
				=> 'apihelp-userrights-example-userid',
		];
		if ( $this->getUserRightsPage()->canProcessExpiries() ) {
			$a['action=userrights&user=SometimeSysop&add=sysop&expiry=1%20month&token=123ABC']
				= 'apihelp-userrights-example-expiry';
		}
		return $a;
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:User_group_membership';
	}
}
