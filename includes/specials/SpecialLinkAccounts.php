<?php

namespace MediaWiki\Specials;

use LogicException;
use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\MainConfigNames;
use MediaWiki\SpecialPage\AuthManagerSpecialPage;
use StatusValue;

/**
 * Link/unlink external accounts to the current user.
 *
 * To interact with this page, account providers need to register themselves with AuthManager.
 *
 * @ingroup SpecialPage
 * @ingroup Auth
 */
class SpecialLinkAccounts extends AuthManagerSpecialPage {
	/** @inheritDoc */
	protected static $allowedActions = [
		AuthManager::ACTION_LINK, AuthManager::ACTION_LINK_CONTINUE,
	];

	public function __construct( AuthManager $authManager ) {
		parent::__construct( 'LinkAccounts' );
		$this->setAuthManager( $authManager );
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'login';
	}

	/** @inheritDoc */
	public function isListed() {
		return $this->getAuthManager()->canLinkAccounts();
	}

	/** @inheritDoc */
	protected function getRequestBlacklist() {
		return $this->getConfig()->get( MainConfigNames::ChangeCredentialsBlacklist );
	}

	/**
	 * @param null|string $subPage
	 * @throws ErrorPageError
	 */
	public function execute( $subPage ) {
		$this->setHeaders();
		$this->loadAuth( $subPage );

		if ( !$this->isActionAllowed( $this->authAction ) ) {
			if ( $this->authAction === AuthManager::ACTION_LINK ) {
				// looks like no linking provider is installed or willing to take this user
				$titleMessage = $this->msg( 'cannotlink-no-provider-title' );
				$errorMessage = $this->msg( 'cannotlink-no-provider' );
				throw new ErrorPageError( $titleMessage, $errorMessage );
			} else {
				// user probably back-button-navigated into an auth session that no longer exists
				// FIXME would be nice to show a message
				$this->getOutput()->redirect( $this->getPageTitle()->getFullURL( '', false,
					PROTO_HTTPS ) );
				return;
			}
		}

		$this->outputHeader();

		$status = $this->trySubmit();

		if ( $status === false || !$status->isOK() ) {
			$this->displayForm( $status );
			return;
		}

		$response = $status->getValue();

		switch ( $response->status ) {
			case AuthenticationResponse::PASS:
				$this->success();
				break;
			case AuthenticationResponse::FAIL:
				$this->loadAuth( '', AuthManager::ACTION_LINK, true );
				$this->displayForm( StatusValue::newFatal( $response->message ) );
				break;
			case AuthenticationResponse::REDIRECT:
				$this->getOutput()->redirect( $response->redirectTarget );
				break;
			case AuthenticationResponse::UI:
				$this->authAction = AuthManager::ACTION_LINK_CONTINUE;
				$this->authRequests = $response->neededRequests;
				$this->displayForm( StatusValue::newFatal( $response->message ) );
				break;
			default:
				throw new LogicException( 'invalid AuthenticationResponse' );
		}
	}

	/** @inheritDoc */
	protected function getDefaultAction( $subPage ) {
		return AuthManager::ACTION_LINK;
	}

	/**
	 * @param AuthenticationRequest[] $requests
	 * @param string $action AuthManager action name, should be ACTION_LINK or ACTION_LINK_CONTINUE
	 * @return HTMLForm
	 */
	protected function getAuthForm( array $requests, $action ) {
		$form = parent::getAuthForm( $requests, $action );
		$form->setSubmitTextMsg( 'linkaccounts-submit' );
		return $form;
	}

	/**
	 * Show a success message.
	 */
	protected function success() {
		$this->loadAuth( '', AuthManager::ACTION_LINK, true );
		$this->displayForm( StatusValue::newFatal( $this->msg( 'linkaccounts-success-text' ) ) );
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialLinkAccounts::class, 'SpecialLinkAccounts' );
