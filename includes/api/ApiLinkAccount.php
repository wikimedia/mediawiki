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
use MediaWiki\Auth\AuthenticationResponse;

/**
 * Link an account with AuthManager
 *
 * @ingroup API
 */
class ApiLinkAccount extends ApiBase {

	public function __construct( ApiMain $main, $action ) {
		parent::__construct( $main, $action, 'link' );
	}

	public function getFinalDescription() {
		// A bit of a hack to append 'api-help-authmanager-general-usage'
		$msgs = parent::getFinalDescription();
		$msgs[] = ApiBase::makeMessage( 'api-help-authmanager-general-usage', $this->getContext(), [
			$this->getModulePrefix(),
			$this->getModuleName(),
			$this->getModulePath(),
			AuthManager::ACTION_LINK,
			self::needsToken(),
		] );
		return $msgs;
	}

	public function execute() {
		if ( !$this->getUser()->isLoggedIn() ) {
			$this->dieWithError( 'apierror-mustbeloggedin-linkaccounts', 'notloggedin' );
		}

		$params = $this->extractRequestParams();

		$this->requireAtLeastOneParameter( $params, 'continue', 'returnurl' );

		if ( $params['returnurl'] !== null ) {
			$bits = wfParseUrl( $params['returnurl'] );
			if ( !$bits || $bits['scheme'] === '' ) {
				$encParamName = $this->encodeParamName( 'returnurl' );
				$this->dieWithError(
					[ 'apierror-badurl', $encParamName, wfEscapeWikiText( $params['returnurl'] ) ],
					"badurl_{$encParamName}"
				);
			}
		}

		$helper = new ApiAuthManagerHelper( $this );
		$manager = AuthManager::singleton();

		// Check security-sensitive operation status
		$helper->securitySensitiveOperation( 'LinkAccounts' );

		// Make sure it's possible to link accounts
		if ( !$manager->canLinkAccounts() ) {
			$this->getResult()->addValue( null, 'linkaccount', $helper->formatAuthenticationResponse(
				AuthenticationResponse::newFail( $this->msg( 'userlogin-cannot-' . AuthManager::ACTION_LINK ) )
			) );
			return;
		}

		// Perform the link step
		if ( $params['continue'] ) {
			$reqs = $helper->loadAuthenticationRequests( AuthManager::ACTION_LINK_CONTINUE );
			$res = $manager->continueAccountLink( $reqs );
		} else {
			$reqs = $helper->loadAuthenticationRequests( AuthManager::ACTION_LINK );
			$res = $manager->beginAccountLink( $this->getUser(), $reqs, $params['returnurl'] );
		}

		$this->getResult()->addValue( null, 'linkaccount',
			$helper->formatAuthenticationResponse( $res ) );
	}

	public function isReadMode() {
		return false;
	}

	public function isWriteMode() {
		return true;
	}

	public function needsToken() {
		return 'csrf';
	}

	public function getAllowedParams() {
		return ApiAuthManagerHelper::getStandardParams( AuthManager::ACTION_LINK,
			'requests', 'messageformat', 'mergerequestfields', 'returnurl', 'continue'
		);
	}

	public function dynamicParameterDocumentation() {
		return [ 'api-help-authmanagerhelper-additional-params', AuthManager::ACTION_LINK ];
	}

	protected function getExamplesMessages() {
		return [
			'action=linkaccount&provider=Example&linkreturnurl=http://example.org/&linktoken=123ABC'
				=> 'apihelp-linkaccount-example-link',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Linkaccount';
	}
}
