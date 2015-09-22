<?php

use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;

/**
 * Links/unlinks external accounts to the current user.
 *
 * To interact with this page, account providers need to register themselves with AuthManager.
 */
class SpecialLinkAccounts extends AuthManagerSpecialPage {
	protected static $allowedActions = array(
		AuthManager::ACTION_LINK, AuthManager::ACTION_LINK_CONTINUE,
	);

	public function __construct() {
		parent::__construct( 'LinkAccounts' );
	}

	/**
	 * Under which header this special page is listed in Special:SpecialPages.
	 */
	protected function getGroupName() {
		return 'users';
	}

	protected function getLoginSecurityLevel() {
		return 'LinkAccounts';
	}

	/**
	 * @param null|string $subPage
	 * @throws MWException
	 * @throws PermissionsError
	 */
	public function execute( $subPage ) {
		$this->setHeaders();
		$this->loadAuth( $subPage );

		if ( !$this->isActionAllowed( $this->authAction ) ) {
			if ( $this->authAction === AuthManager::ACTION_LINK ) {
				// looks like no linking provider is installed or willing to take this user
				$titleMessage = wfMessage( 'cannotlink-no-provider-title' );
				$errorMessage = wfMessage( 'cannotlink-no-provider' );
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
				$this->loadAuth( '', AuthManager::ACTION_LINK );
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
		$form->setSubmitTextMsg( 'linkaccountsform-submit' );
		return $form;
	}

	/**
	 * Display the link form.
	 * @param false|Status|StatusValue $status A form submit status, as in HTMLForm::trySubmit()
	 */
	protected function displayForm( $status ) {
		if ( $status instanceof StatusValue ) {
			$status = Status::wrap( $status );
		}
		$form = $this->getAuthForm( $this->authRequests, $this->authAction );
		$form->prepareForm();
		$this->getOutput()->addHTML( $form->getHTML( $status ) );
	}

	/**
	 * Show a success message.
	 */
	protected function success() {
		$this->loadAuth( '', AuthManager::ACTION_LINK );
		$this->displayForm( StatusValue::newFatal( wfMessage( 'linkaccountsform-success-text' ) ) );
	}
}
