<?php

use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Session\SessionManager;

/**
 * Special change to change credentials (such as the password).
 *
 * Also does most of the work for SpecialRemoveCredentials.
 */
class SpecialChangeCredentials extends AuthManagerSpecialPage {
	protected static $allowedActions = [ AuthManager::ACTION_CHANGE ];

	protected static $messagePrefix = 'changecredentials';

	/** Change action needs user data; remove action does not */
	protected static $loadUserData = true;

	public function __construct( $name = 'ChangeCredentials' ) {
		parent::__construct( $name, 'editmyprivateinfo' );
	}

	protected function getGroupName() {
		return 'users';
	}

	public function isListed() {
		$this->loadAuth( '' );
		return (bool)$this->authRequests;
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

	public function onAuthChangeFormFields(
		array $requests, array $fieldInfo, array &$formDescriptor, $action
	) {
		// This method is never called for remove actions.

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

		return parent::onAuthChangeFormFields( $requests, $fieldInfo, $formDescriptor, $action );
	}

	public function execute( $subPage ) {
		$this->setHeaders();
		$this->outputHeader();

		$this->loadAuth( $subPage );

		if ( !$subPage ) {
			$this->showSubpageList();
			return;
		}

		if ( !$this->authRequests ) {
			// messages used: changecredentials-invalidsubpage, removecredentials-invalidsubpage
			$this->showSubpageList( $this->msg( static::$messagePrefix . '-invalidsubpage', $subPage ) );
			return;
		}

		$this->getOutput()->addBacklinkSubtitle( $this->getPageTitle() );

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

	protected function loadAuth( $subPage, $authAction = null, $reset = false ) {
		parent::loadAuth( $subPage, $authAction );
		if ( $subPage ) {
			$this->authRequests = array_filter( $this->authRequests, function ( $req ) use ( $subPage ) {
				return $req->getUniqueId() === $subPage;
			} );
			if ( count( $this->authRequests ) > 1 ) {
				throw new LogicException( 'Multiple AuthenticationRequest objects with same ID!' );
			}
		}
	}

	protected function getAuthFormDescriptor( $requests, $action ) {
		if ( !static::$loadUserData ) {
			return [];
		} else {
			$descriptor = parent::getAuthFormDescriptor( $requests, $action );

			$any = false;
			foreach ( $descriptor as &$field ) {
				if ( $field['type'] === 'password' && $field['name'] !== 'retype' ) {
					$any = true;
					if ( isset( $field['cssclass'] ) ) {
						$field['cssclass'] .= ' mw-changecredentials-validate-password';
					} else {
						$field['cssclass'] = 'mw-changecredentials-validate-password';
					}
				}
			}

			if ( $any ) {
				$this->getOutput()->addModules( [
					'mediawiki.special.changecredentials.js'
				] );
			}

			return $descriptor;
		}
	}

	protected function getAuthForm( array $requests, $action ) {
		$form = parent::getAuthForm( $requests, $action );
		$req = reset( $requests );
		$info = $req->describeCredentials();

		$form->addPreText(
			Html::openElement( 'dl' )
			. Html::element( 'dt', [], $this->msg( 'credentialsform-provider' )->text() )
			. Html::element( 'dd', [], $info['provider'] )
			. Html::element( 'dt', [], $this->msg( 'credentialsform-account' )->text() )
			. Html::element( 'dd', [], $info['account'] )
			. Html::closeElement( 'dl' )
		);

		// messages used: changecredentials-submit removecredentials-submit
		$form->setSubmitTextMsg( static::$messagePrefix . '-submit' );
		$form->showCancel()->setCancelTarget( $this->getReturnUrl() ?: Title::newMainPage() );

		return $form;
	}

	protected function needsSubmitButton( array $requests ) {
		// Change/remove forms show are built from a single AuthenticationRequest and do not allow
		// for redirect flow; they always need a submit button.
		return true;
	}

	public function handleFormSubmit( $data ) {
		// remove requests do not accept user input
		$requests = $this->authRequests;
		if ( static::$loadUserData ) {
			$requests = AuthenticationRequest::loadRequestsFromSubmission( $this->authRequests, $data );
		}

		$response = $this->performAuthenticationStep( $this->authAction, $requests );

		// we can't handle FAIL or similar as failure here since it might require changing the form
		return Status::newGood( $response );
	}

	/**
	 * @param Message|null $error
	 */
	protected function showSubpageList( $error = null ) {
		$out = $this->getOutput();

		if ( $error ) {
			$out->addHTML( $error->parse() );
		}

		$groupedRequests = [];
		foreach ( $this->authRequests as $req ) {
			$info = $req->describeCredentials();
			$groupedRequests[(string)$info['provider']][] = $req;
		}

		$linkRenderer = $this->getLinkRenderer();
		$out->addHTML( Html::openElement( 'dl' ) );
		foreach ( $groupedRequests as $group => $members ) {
			$out->addHTML( Html::element( 'dt', [], $group ) );
			foreach ( $members as $req ) {
				/** @var AuthenticationRequest $req */
				$info = $req->describeCredentials();
				$out->addHTML( Html::rawElement( 'dd', [],
					$linkRenderer->makeLink(
						$this->getPageTitle( $req->getUniqueId() ),
						$info['account']
					)
				) );
			}
		}
		$out->addHTML( Html::closeElement( 'dl' ) );
	}

	protected function success() {
		$session = $this->getRequest()->getSession();
		$user = $this->getUser();
		$out = $this->getOutput();
		$returnUrl = $this->getReturnUrl();

		// change user token and update the session
		SessionManager::singleton()->invalidateSessionsForUser( $user );
		$session->setUser( $user );
		$session->resetId();

		if ( $returnUrl ) {
			$out->redirect( $returnUrl );
		} else {
			// messages used: changecredentials-success removecredentials-success
			$out->wrapWikiMsg( "<div class=\"successbox\">\n$1\n</div>", static::$messagePrefix
				. '-success' );
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
		return $title->getFullUrlForRedirect( $returnToQuery );
	}

	protected function getRequestBlacklist() {
		return $this->getConfig()->get( 'ChangeCredentialsBlacklist' );
	}
}
