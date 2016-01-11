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
 */

use MediaWiki\Auth\AuthManager;

/**
 * Remove authentication data from AuthManager
 *
 * @ingroup API
 */
class ApiRemoveAuthenticationData extends ApiBase {

	private $authAction;

	public function __construct( ApiMain $main, $action ) {
		parent::__construct( $main, $action );

		$this->authAction = $action === 'unlinkaccount'
			? AuthManager::ACTION_UNLINK
			: AuthManager::ACTION_REMOVE;
	}

	public function execute() {
		if ( !$this->getUser()->isLoggedIn() ) {
			$this->dieUsage( 'Must be logged in to remove authentication data', 'notloggedin' );
		}

		$params = $this->module->extractRequestParams();
		$manager = AuthManager::singleton();

		// Fetch the request. No need to load from the request, so don't use
		// ApiAuthManagerHelper's method.
		$reqs = array_filter(
			$manager->getAuthenticationRequests( $this->authAction ), function ( $req ) use ( $params ) {
				return $req->getUniqueId() === $params['request'];
			}
		);
		if ( count( $reqs ) !== 1 ) {
			$this->dieUsage( 'Failed to create change request', 'badrequest' );
		}
		$req = reset( $reqs );

		// Perform the removal
		$status = $manager->allowsAuthenticationDataChange( $req, true );
		if ( !$status->isGood() ) {
			$this->dieStatus( $status );
		}
		$manager->changeAuthenticationData( $req );

		$this->getResult()->addValue( null, $this->getModuleName(), array( 'status' => 'success' ) );
	}

	public function isWriteMode() {
		return true;
	}

	public function needsToken() {
		return 'csrf';
	}

	public function getAllowedParams() {
		return ApiAuthManagerHelper::getStandardParams( $this->authAction,
			'request'
		);
	}

	protected function getExamplesMessages() {
		$path = $this->getModulePath();
		$action = $this->getModuleName();
		return array(
			"action={$action}&request=FooAuthenticationRequest&token=123ABC"
				=> "apihelp-{$path}-example-simple",
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Manage_authentication_data';
	}
}
