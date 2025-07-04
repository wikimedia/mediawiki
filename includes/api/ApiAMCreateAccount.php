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

namespace MediaWiki\Api;

use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Utils\UrlUtils;

/**
 * Create an account with AuthManager
 *
 * @ingroup API
 */
class ApiAMCreateAccount extends ApiBase {

	private AuthManager $authManager;
	private UrlUtils $urlUtils;

	public function __construct(
		ApiMain $main,
		string $action,
		AuthManager $authManager,
		UrlUtils $urlUtils
	) {
		parent::__construct( $main, $action, 'create' );
		$this->authManager = $authManager;
		$this->urlUtils = $urlUtils;
	}

	/** @inheritDoc */
	public function getFinalDescription() {
		// A bit of a hack to append 'api-help-authmanager-general-usage'
		$msgs = parent::getFinalDescription();
		$msgs[] = $this->msg( 'api-help-authmanager-general-usage',
			$this->getModulePrefix(),
			$this->getModuleName(),
			$this->getModulePath(),
			AuthManager::ACTION_CREATE,
			$this->needsToken(),
		);
		return $msgs;
	}

	public function execute() {
		$params = $this->extractRequestParams();
		$performer = $this->getUser();

		$this->requireAtLeastOneParameter( $params, 'continue', 'returnurl' );

		if ( $params['returnurl'] !== null ) {
			$bits = $this->urlUtils->parse( $params['returnurl'] );
			if ( !$bits || $bits['scheme'] === '' ) {
				$encParamName = $this->encodeParamName( 'returnurl' );
				$this->dieWithError(
					[ 'apierror-badurl', $encParamName, wfEscapeWikiText( $params['returnurl'] ) ],
					"badurl_{$encParamName}"
				);
			}
		}

		$helper = new ApiAuthManagerHelper( $this, $this->authManager );

		// Make sure it's possible to create accounts
		if ( !$this->authManager->canCreateAccounts() ) {
			$res = AuthenticationResponse::newFail( $this->msg( 'userlogin-cannot-' . AuthManager::ACTION_CREATE ) );
			$this->getResult()->addValue( null, 'createaccount',
				$helper->formatAuthenticationResponse( $res ) );
			$helper->logAuthenticationResult( 'accountcreation', $performer, $res );
			return;
		}

		// Perform the create step
		if ( $params['continue'] ) {
			$reqs = $helper->loadAuthenticationRequests( AuthManager::ACTION_CREATE_CONTINUE );
			$res = $this->authManager->continueAccountCreation( $reqs );
		} else {
			$reqs = $helper->loadAuthenticationRequests( AuthManager::ACTION_CREATE );
			if ( $params['preservestate'] ) {
				$req = $helper->getPreservedRequest();
				if ( $req ) {
					$reqs[] = $req;
				}
			}
			$res = $this->authManager->beginAccountCreation(
				$this->getAuthority(),
				$reqs,
				$params['returnurl']
			);
		}

		$this->getResult()->addValue( null, 'createaccount',
			$helper->formatAuthenticationResponse( $res ) );
		$helper->logAuthenticationResult( 'accountcreation', $performer, $res );
	}

	/** @inheritDoc */
	public function isReadMode() {
		return false;
	}

	/** @inheritDoc */
	public function isWriteMode() {
		return true;
	}

	/** @inheritDoc */
	public function needsToken() {
		return 'createaccount';
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		$ret = ApiAuthManagerHelper::getStandardParams( AuthManager::ACTION_CREATE,
			'requests', 'messageformat', 'mergerequestfields', 'preservestate', 'returnurl', 'continue'
		);
		$ret['preservestate'][ApiBase::PARAM_HELP_MSG_APPEND][] =
			'apihelp-createaccount-param-preservestate';
		return $ret;
	}

	/** @inheritDoc */
	public function dynamicParameterDocumentation() {
		return [ 'api-help-authmanagerhelper-additional-params', AuthManager::ACTION_CREATE ];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=createaccount&username=Example&password=ExamplePassword&retype=ExamplePassword'
				. '&createreturnurl=http://example.org/&createtoken=123ABC'
				=> 'apihelp-createaccount-example-create',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Account_creation';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiAMCreateAccount::class, 'ApiAMCreateAccount' );
