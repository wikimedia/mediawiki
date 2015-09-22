<?php

use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;

class SpecialUnlinkAccounts extends AuthManagerSpecialPage {
	protected static $allowedActions = array( AuthManager::ACTION_UNLINK );

	public function __construct() {
		parent::__construct( 'UnlinkAccounts' );
	}

	protected function getLoginSecurityLevel() {
		return 'UnlinkAccount';
	}

	protected function getDefaultAction( $subPage ) {
		return AuthManager::ACTION_UNLINK;
	}

	/**
	 * Under which header this special page is listed in Special:SpecialPages.
	 */
	protected function getGroupName() {
		return 'users';
	}

	public function execute( $subPage ) {
		$this->setHeaders();
		$this->loadAuth( $subPage );
		$this->outputHeader();

		$status = $this->trySubmit();

		if ( $status === false || !$status->isOK() ) {
			$this->displayForm( $status );
			return;
		}

		/** @var AuthenticationResponse $response */
		$response = $status->getValue();

		if ( $response->status === AuthenticationResponse::FAIL ) {
			$this->displayForm( StatusValue::newFatal( $response->message ) );
			return;
		}

		$status = StatusValue::newGood();
		$status->warning( wfMessage( 'unlinkaccounts-success' ) );
		$this->displayForm( $status );
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
}
