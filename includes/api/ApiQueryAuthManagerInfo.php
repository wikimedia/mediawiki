<?php
/**
 * Copyright © 2016 Brad Jorsch <bjorsch@wikimedia.org>
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
 * @since 1.27
 */

use MediaWiki\Auth\AuthManager;

/**
 * A query action to return meta information about AuthManager state.
 *
 * @ingroup API
 */
class ApiQueryAuthManagerInfo extends ApiQueryBase {

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'ami' );
	}

	public function execute() {
		$params = $this->extractRequestParams();
		$helper = new ApiAuthManagerHelper( $this );

		$manager = AuthManager::singleton();
		$ret = [
			'canauthenticatenow' => $manager->canAuthenticateNow(),
			'cancreateaccounts' => $manager->canCreateAccounts(),
			'canlinkaccounts' => $manager->canLinkAccounts(),
		];

		if ( $params['securitysensitiveoperation'] !== null ) {
			$ret['securitysensitiveoperationstatus'] = $manager->securitySensitiveOperationStatus(
				$params['securitysensitiveoperation']
			);
		}

		if ( $params['requestsfor'] ) {
			$action = $params['requestsfor'];

			$preservedReq = $helper->getPreservedRequest();
			if ( $preservedReq ) {
				$ret += [
					'haspreservedstate' => $preservedReq->hasStateForAction( $action ),
					'hasprimarypreservedstate' => $preservedReq->hasPrimaryStateForAction( $action ),
					'preservedusername' => (string)$preservedReq->username,
				];
			} else {
				$ret += [
					'haspreservedstate' => false,
					'hasprimarypreservedstate' => false,
					'preservedusername' => '',
				];
			}

			$reqs = $manager->getAuthenticationRequests( $action, $this->getUser() );

			// Filter out blacklisted requests, depending on the action
			switch ( $action ) {
				case AuthManager::ACTION_CHANGE:
					$reqs = ApiAuthManagerHelper::blacklistAuthenticationRequests(
						$reqs, $this->getConfig()->get( 'ChangeCredentialsBlacklist' )
					);
					break;
				case AuthManager::ACTION_REMOVE:
					$reqs = ApiAuthManagerHelper::blacklistAuthenticationRequests(
						$reqs, $this->getConfig()->get( 'RemoveCredentialsBlacklist' )
					);
					break;
			}

			$ret += $helper->formatRequests( $reqs );
		}

		$this->getResult()->addValue( [ 'query' ], $this->getModuleName(), $ret );
	}

	public function isReadMode() {
		return false;
	}

	public function getAllowedParams() {
		return [
			'securitysensitiveoperation' => null,
			'requestsfor' => [
				ApiBase::PARAM_TYPE => [
					AuthManager::ACTION_LOGIN,
					AuthManager::ACTION_LOGIN_CONTINUE,
					AuthManager::ACTION_CREATE,
					AuthManager::ACTION_CREATE_CONTINUE,
					AuthManager::ACTION_LINK,
					AuthManager::ACTION_LINK_CONTINUE,
					AuthManager::ACTION_CHANGE,
					AuthManager::ACTION_REMOVE,
					AuthManager::ACTION_UNLINK,
				],
			],
		] + ApiAuthManagerHelper::getStandardParams( '', 'mergerequestfields', 'messageformat' );
	}

	protected function getExamplesMessages() {
		return [
			'action=query&meta=authmanagerinfo&amirequestsfor=' . urlencode( AuthManager::ACTION_LOGIN )
				=> 'apihelp-query+filerepoinfo-example-login',
			'action=query&meta=authmanagerinfo&amirequestsfor=' . urlencode( AuthManager::ACTION_LOGIN ) .
				'&amimergerequestfields=1'
				=> 'apihelp-query+filerepoinfo-example-login-merged',
			'action=query&meta=authmanagerinfo&amisecuritysensitiveoperation=foo'
				=> 'apihelp-query+filerepoinfo-example-securitysensitiveoperation',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Authmanagerinfo';
	}
}
