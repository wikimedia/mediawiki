<?php
/**
 * Copyright © 2016 Wikimedia Foundation and contributors
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

	public function execute() {
		if ( !$this->getUser()->isLoggedIn() ) {
			$this->dieWithError( 'apierror-mustbeloggedin-removeauth', 'notloggedin' );
		}

		$params = $this->extractRequestParams();
		$manager = AuthManager::singleton();

		// Check security-sensitive operation status
		ApiAuthManagerHelper::newForModule( $this )->securitySensitiveOperation( 'RemoveCredentials' );

		// Fetch the request. No need to load from the request, so don't use
		// ApiAuthManagerHelper's method.
		$blacklist = array_flip( $this->getConfig()->get( 'RemoveCredentialsBlacklist' ) );
		$reqs = array_filter(
			$manager->getAuthenticationRequests( AuthManager::ACTION_REMOVE, $this->getUser() ),
			function ( $req ) use ( $params, $blacklist ) {
				return $req->getUniqueId() === $params['request'] &&
					!isset( $blacklist[get_class( $req )] );
			}
		);
		if ( count( $reqs ) !== 1 ) {
			$this->dieWithError( 'apierror-changeauth-norequest', 'badrequest' );
		}
		$req = reset( $reqs );

		// Perform the removal
		$status = $manager->allowsAuthenticationDataChange( $req, true );
		Hooks::run( 'ChangeAuthenticationDataAudit', [ $req, $status ] );
		if ( !$status->isGood() ) {
			$this->dieStatus( $status );
		}
		$manager->changeAuthenticationData( $req );

		$this->getResult()->addValue( null, $this->getModuleName(), [ 'status' => 'success' ] );
	}

	public function isWriteMode() {
		return true;
	}

	public function needsToken() {
		return 'csrf';
	}

	public function getAllowedParams() {
		return ApiAuthManagerHelper::getStandardParams( AuthManager::ACTION_REMOVE,
			'request'
		);
	}

	protected function getExamplesMessages() {
		$path = $this->getModulePath();
		$action = $this->getModuleName();
		return [
			"action={$action}&request=FooAuthenticationRequest&token=123ABC"
				=> "apihelp-{$path}-example-simple",
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Manage_authentication_data';
	}
}
