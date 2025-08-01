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
use MediaWiki\Auth\CreateFromLoginAuthenticationRequest;
use MediaWiki\Utils\UrlUtils;

/**
 * Log in to the wiki with AuthManager
 *
 * @ingroup API
 */
class ApiClientLogin extends ApiBase {

	private AuthManager $authManager;
	private UrlUtils $urlUtils;

	public function __construct(
		ApiMain $main,
		string $action,
		AuthManager $authManager,
		UrlUtils $urlUtils
	) {
		parent::__construct( $main, $action, 'login' );
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
			AuthManager::ACTION_LOGIN,
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

		// Make sure it's possible to log in
		if ( !$this->authManager->canAuthenticateNow() ) {
			$res = AuthenticationResponse::newFail( $this->msg( 'userlogin-cannot-' . AuthManager::ACTION_LOGIN ) );
			$this->getResult()->addValue( null, 'clientlogin',
				$helper->formatAuthenticationResponse( $res ) );
			$helper->logAuthenticationResult( 'login', $performer, $res );
			return;
		}

		// Perform the login step
		if ( $params['continue'] ) {
			$reqs = $helper->loadAuthenticationRequests( AuthManager::ACTION_LOGIN_CONTINUE );
			$res = $this->authManager->continueAuthentication( $reqs );
		} else {
			$reqs = $helper->loadAuthenticationRequests( AuthManager::ACTION_LOGIN );
			if ( $params['preservestate'] ) {
				$req = $helper->getPreservedRequest();
				if ( $req ) {
					$reqs[] = $req;
				}
			}
			$res = $this->authManager->beginAuthentication( $reqs, $params['returnurl'] );
		}

		// Remove CreateFromLoginAuthenticationRequest from $res->neededRequests.
		// It's there so a RESTART treated as UI will work right, but showing
		// it to the API client is just confusing.
		$res->neededRequests = ApiAuthManagerHelper::blacklistAuthenticationRequests(
			$res->neededRequests, [ CreateFromLoginAuthenticationRequest::class ]
		);

		$this->getResult()->addValue( null, 'clientlogin',
			$helper->formatAuthenticationResponse( $res ) );
		$helper->logAuthenticationResult( 'login', $performer, $res );
	}

	/** @inheritDoc */
	public function isReadMode() {
		return false;
	}

	/** @inheritDoc */
	public function isWriteMode() {
		// (T283394) Logging in triggers some database writes, so should be marked appropriately.
		return true;
	}

	/** @inheritDoc */
	public function needsToken() {
		return 'login';
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return ApiAuthManagerHelper::getStandardParams( AuthManager::ACTION_LOGIN,
			'requests', 'messageformat', 'mergerequestfields', 'preservestate', 'returnurl', 'continue'
		);
	}

	/** @inheritDoc */
	public function dynamicParameterDocumentation() {
		return [ 'api-help-authmanagerhelper-additional-params', AuthManager::ACTION_LOGIN ];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=clientlogin&username=Example&password=ExamplePassword&'
				. 'loginreturnurl=http://example.org/&logintoken=123ABC'
				=> 'apihelp-clientlogin-example-login',
			'action=clientlogin&logincontinue=1&OATHToken=987654&logintoken=123ABC'
				=> 'apihelp-clientlogin-example-login2',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Login';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiClientLogin::class, 'ApiClientLogin' );
