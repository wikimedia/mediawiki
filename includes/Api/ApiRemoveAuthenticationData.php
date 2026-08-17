<?php
/**
 * Copyright © 2016 Wikimedia Foundation and contributors
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthManager;
use MediaWiki\MainConfigNames;
use MediaWiki\Session\SessionManager;

/**
 * Remove authentication data from AuthManager
 *
 * @ingroup API
 */
class ApiRemoveAuthenticationData extends ApiBase {

	/** @var string */
	private $authAction;
	/** @var string */
	private $operation;

	public function __construct(
		ApiMain $main,
		string $action,
		private readonly AuthManager $authManager,
		private readonly SessionManager $sessionManager,
	) {
		parent::__construct( $main, $action );

		$this->authAction = $action === 'unlinkaccount'
			? AuthManager::ACTION_UNLINK
			: AuthManager::ACTION_REMOVE;
		$this->operation = $action === 'unlinkaccount'
			? 'UnlinkAccount'
			: 'RemoveCredentials';
	}

	public function execute() {
		if ( !$this->getUser()->isNamed() ) {
			$this->dieWithError( 'apierror-mustbeloggedin-removeauth', 'notloggedin' );
		}

		$this->checkUserRightsAny( 'editmyprivateinfo' );

		$params = $this->extractRequestParams();

		// Check security-sensitive operation status
		ApiAuthManagerHelper::newForModule( $this, $this->authManager )
			->securitySensitiveOperation( $this->operation );

		// Fetch the request. No need to load from the request, so don't use
		// ApiAuthManagerHelper's method.
		$remove = $this->authAction === AuthManager::ACTION_REMOVE
			? array_fill_keys( $this->getConfig()->get(
				MainConfigNames::RemoveCredentialsBlacklist ), true )
			: [];
		$reqs = array_filter(
			$this->authManager->getAuthenticationRequests( $this->authAction, $this->getUser() ),
			static function ( AuthenticationRequest $req ) use ( $params, $remove ) {
				return $req->getUniqueId() === $params['request'] &&
					!isset( $remove[get_class( $req )] );
			}
		);
		if ( count( $reqs ) !== 1 ) {
			$this->dieWithError( 'apierror-changeauth-norequest', 'badrequest' );
		}
		$req = reset( $reqs );

		// Perform the removal
		$status = $this->authManager->allowsAuthenticationDataChange( $req, true );
		$this->getHookRunner()->onChangeAuthenticationDataAudit( $req, $status );
		if ( !$status->isGood() ) {
			$this->dieStatus( $status );
		}
		$this->authManager->changeAuthenticationData( $req );

		// Reset sessions - if the user removed a credential or unlinked an account
		// because it was compromised, log attackers out from sessions obtained that way.
		$session = $this->getRequest()->getSession();
		$user = $this->getUser();
		$this->sessionManager->invalidateSessionsForUser( $user );
		$session->setUser( $user );
		$session->resetId();

		$this->getResult()->addValue( null, $this->getModuleName(), [ 'status' => 'success' ] );
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
		return ApiAuthManagerHelper::getStandardParams( $this->authAction,
			'request'
		);
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		$path = $this->getModulePath();
		$action = $this->getModuleName();
		return [
			"action={$action}&request=FooAuthenticationRequest&token=123ABC"
				=> "apihelp-{$path}-example-simple",
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Manage_authentication_data';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiRemoveAuthenticationData::class, 'ApiRemoveAuthenticationData' );
