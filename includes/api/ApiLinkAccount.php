<?php
/**
 * Copyright © 2016 Wikimedia Foundation and contributors
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Utils\UrlUtils;

/**
 * Link an account with AuthManager
 *
 * @ingroup API
 */
class ApiLinkAccount extends ApiBase {

	private AuthManager $authManager;
	private UrlUtils $urlUtils;

	public function __construct(
		ApiMain $main,
		string $action,
		AuthManager $authManager,
		UrlUtils $urlUtils
	) {
		parent::__construct( $main, $action, 'link' );
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
			AuthManager::ACTION_LINK,
			$this->needsToken(),
		);
		return $msgs;
	}

	public function execute() {
		if ( !$this->getUser()->isNamed() ) {
			$this->dieWithError( 'apierror-mustbeloggedin-linkaccounts', 'notloggedin' );
		}

		$params = $this->extractRequestParams();

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

		// Check security-sensitive operation status
		$helper->securitySensitiveOperation( 'LinkAccounts' );

		// Make sure it's possible to link accounts
		if ( !$this->authManager->canLinkAccounts() ) {
			$this->getResult()->addValue( null, 'linkaccount', $helper->formatAuthenticationResponse(
				AuthenticationResponse::newFail( $this->msg( 'userlogin-cannot-' . AuthManager::ACTION_LINK ) )
			) );
			return;
		}

		// Perform the link step
		if ( $params['continue'] ) {
			$reqs = $helper->loadAuthenticationRequests( AuthManager::ACTION_LINK_CONTINUE );
			$res = $this->authManager->continueAccountLink( $reqs );
		} else {
			$reqs = $helper->loadAuthenticationRequests( AuthManager::ACTION_LINK );
			$res = $this->authManager->beginAccountLink( $this->getUser(), $reqs, $params['returnurl'] );
		}

		$this->getResult()->addValue( null, 'linkaccount',
			$helper->formatAuthenticationResponse( $res ) );
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
		return 'csrf';
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return ApiAuthManagerHelper::getStandardParams( AuthManager::ACTION_LINK,
			'requests', 'messageformat', 'mergerequestfields', 'returnurl', 'continue'
		);
	}

	/** @inheritDoc */
	public function dynamicParameterDocumentation() {
		return [ 'api-help-authmanagerhelper-additional-params', AuthManager::ACTION_LINK ];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=linkaccount&provider=Example&linkreturnurl=http://example.org/&linktoken=123ABC'
				=> 'apihelp-linkaccount-example-link',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Linkaccount';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiLinkAccount::class, 'ApiLinkAccount' );
