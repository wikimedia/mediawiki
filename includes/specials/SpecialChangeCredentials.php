<?php

use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;

class SpecialChangeCredentials extends AuthManagerSpecialPage {
	protected static $allowedActions = [ AuthManager::ACTION_CHANGE, AuthManager::ACTION_REMOVE ];

	public function __construct() {
		parent::__construct( 'ChangeCredentials', 'editmyprivateinfo' );
	}

	protected function getGroupName() {
		return 'users';
	}

	public function isListed() {
		return (bool)AuthManager::singleton()->getAuthenticationRequests( AuthManager::ACTION_CHANGE,
			$this->getUser() );
	}

	public function doesWrites() {
		return true;
	}

	protected function getDefaultAction( $subPage ) {
		return AuthManager::ACTION_CHANGE;
	}

	protected function getPreservedParams( $withToken = false ) {
		$request = $this->getRequest();
		$params = parent::getPreservedParams( $withToken );
		$params += [
			'returnto' => $request->getVal( 'returnto' ),
			'returntoquery' => $request->getVal( 'returntoquery' ),
		];
		return $params;
	}

	public function onAuthChangeFormFields( $fieldInfo, &$formDescriptor, $action ) {
		$extraFields = [];
		Hooks::run( 'ChangePasswordForm', [ &$extraFields ], '1.27' );
		foreach ( $extraFields as $extra ) {
			list( $name, $label, $type, $default ) = $extra;
			$formDescriptor[$name] = [
				'type' => $type,
				'name' => $name,
				'label-message' => $label,
				'default' => $default,
			];
		}

		parent::onAuthChangeFormFields( $fieldInfo, $formDescriptor, $action );
		return true;
	}

	public function execute( $subPage ) {
		Hooks::register( 'AuthChangeFormFields', [ $this, 'onAuthChangeFormFields' ] );

		$this->setHeaders();
		$this->outputHeader();

		if ( !$subPage ) {
			$this->showSubpageList();
			return;
		}

		if ( $this->getRequest()->getCheck( 'wpCancel' ) ) {
			$returnUrl = $this->getReturnUrl() ?: Title::newMainPage()->getFullURL();
			$this->getOutput()->redirect( $returnUrl );
			return;
		}

		$this->loadAuth( $subPage );

		if ( !$this->authRequests ) {
			$this->showSubpageList( $this->msg( 'changecredentials-invalidsubpage', $subPage ) );
			return;
		}

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
				$this->displayForm( Status::newFatal( $response->message ) );
				break;
			default:
				throw new LogicException( 'invalid AuthenticationResponse' );
		}
	}

	protected function loadAuth( $subPage, $authAction = null ) {
		parent::loadAuth( $subPage, $authAction );
		$this->authRequests = array_filter( $this->authRequests, function ( $req ) use ( $subPage ) {
			return $req->getUniqueId() === $subPage;
		} );
		if ( count( $this->authRequests ) > 1 ) {
			throw new LogicException( 'Multiple AuthenticationRequest objects with same ID!' );
		}
	}

	protected function getAuthForm( array $requests, $action ) {
		$form = parent::getAuthForm( $requests, $action );

		$form->addButton( [
			'name' => 'wpCancel',
			'value' => $this->msg( 'resetpass-submit-cancel' )->text()
		] );

		return $form;
	}

	/**
	 * @param Message|null $error
	 */
	protected function showSubpageList( $error = null ) {
		$out = $this->getOutput();

		if ( $error ) {
			$out->addHTML( $error->parse() );
		}

		$requests = AuthManager::singleton()->getAuthenticationRequests( AuthManager::ACTION_CHANGE,
			$this->getUser() );

		$out->addHTML( Html::openElement( 'ul' ) );
		foreach ( $requests as $req ) {
			$req->describeCredentials();
			$out->addHTML( Html::rawElement( 'li', [],
				Linker::link( $this->getPageTitle( $req->getUniqueId() ), $req->getUniqueId() )
			) );
		}
		$out->addHTML( Html::closeElement( 'ul' ) );
	}

	protected function success() {
		$session = $this->getRequest()->getSession();
		$user = $this->getUser();
		$out = $this->getOutput();
		$returnUrl = $this->getReturnUrl();

		// change user token and update the session
		$user->setToken();
		$user->saveSettings();
		$session->setUser( $user );
		$session->resetId();

		if ( $returnUrl ) {
			$out->redirect( $returnUrl );
		} else {
			$out->wrapWikiMsg( "<div class=\"successbox\">\n$1\n</div>", 'changecredentials-success' );
			$out->returnToMain();
		}
	}

	/**
	 * @return string|null
	 */
	protected function getReturnUrl() {
		$request = $this->getRequest();
		$returnTo = $request->getText( 'returnto' );
		$returnToQuery = $request->getText( 'returntoquery', '' );

		if ( !$returnTo ) {
			return null;
		}

		$title = Title::newFromText( $returnTo );
		return $title->getFullURL( $returnToQuery );
	}
}
