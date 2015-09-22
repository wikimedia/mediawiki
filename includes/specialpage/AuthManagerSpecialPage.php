<?php

use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthFrontend;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Session\SessionManager;

/**
 * A special page subclass for authentication-related special pages. It generates a form from
 * a set of AuthenticationRequest objects, submits the result to AuthManager and
 * partially handles the response.
 */
abstract class AuthManagerSpecialPage extends SpecialPage {
	/** @var string[] The list of actions this special page deals with. Subclasses should override
	 * this. */
	protected static $allowedActions = array(
		AuthManager::ACTION_LOGIN, AuthManager::ACTION_LOGIN_CONTINUE,
		AuthManager::ACTION_CREATE, AuthManager::ACTION_CREATE_CONTINUE,
		AuthManager::ACTION_LINK, AuthManager::ACTION_LINK_CONTINUE,
		AuthManager::ACTION_CHANGE, AuthManager::ACTION_REMOVE, AuthManager::ACTION_UNLINK,
	);

	/** @var string one of the AuthManager::ACTION_* constants. */
	protected $authAction;

	/** @var AuthenticationRequest[] */
	protected $authRequests;

	/** @var string Subpage of the special page. */
	protected $subPage;

	/** @var bool True if the current request is a result of returning from a redirect flow. */
	protected $isReturn;

	/** @var WebRequest|null If set, will be used instead of the real request. Used for redirection.  */
	protected $savedRequest;

	public function __construct( $name = '', $restriction = '', $listed = true, $function = false,
								 $file = '', $includable = false ) {
		parent::__construct( $name, $restriction, $listed, $function, $file, $includable );
		Hooks::register( 'AuthChangeFormFields', array( $this, 'onAuthChangeFormFields' ) );
	}

	/**
	 * Change the form descriptor that determines how a field will look in the authentication form.
	 * Called from AuthFrontend::fieldInfoToFormDescriptor().
	 * @param string $fieldInfo Field information array (union of all
	 *    AuthenticationRequest::getFieldInfo() responses).
	 * @param array $formDescriptor HTMLForm descriptor. The special key 'weight' can be set to
	 *    change the order of the fields.
	 * @param string $action Authentication type (one of the AuthManager::ACTION_* constants)
	 * @return bool
	 */
	public function onAuthChangeFormFields( $fieldInfo, &$formDescriptor, $action ) {
		return true;
	}

	/**
	 * Tells if the special page does something security-sensitive and needs extra defense against
	 * a stolen account (e.g. a reauthentication). What exactly that will mean is decided by the
	 * authentication framework.
	 * @return bool|string False or the argument for AuthManager::securitySensitiveOperationStatus().
	 * FIXME should this be on SpecialPage?
	 */
	protected function getLoginSecurityLevel() {
		return $this->getName();
	}

	public function getRequest() {
		return $this->savedRequest ?: $this->getContext()->getRequest();
	}

	/**
	 * Override the POST data, GET data from the real request is preserved.
	 *
	 * Used to preserve POST data over a HTTP redirect.
	 *
	 * @param array $data
	 * @param bool $wasPosted
	 */
	protected function setRequest( array $data, $wasPosted = null ) {
		$request = $this->getContext()->getRequest();
		if ( $wasPosted === null ) {
			$wasPosted = $request->wasPosted();
		}
		$this->savedRequest = new DerivativeRequest( $request, $data + $request->getQueryValues(),
			$wasPosted );
	}

	protected function beforeExecute( $subPage ) {
		return $this->handleReturnBeforeExecute( $subPage )
			&& $this->handleReauthBeforeExecute( $subPage )
			&& $this->handleTokenBeforeExecute( $subPage );
	}

	/**
	 * Handle redirection from the /return subpage.
	 *
	 * This is used in the redirect flow where we need
	 * to be able to process data that was sent via a GET request. We set the /return subpage as
	 * the reentry point so we know we need to treat GET as POST, but we don't want to handle all
	 * future GETs as POSTs so we need to normalize the URL. (Also we don't want to show any
	 * received parameters around in the URL; they are ugly and might be sensitive.)
	 *
	 * Thus when on the /return subpage, we stash the request data in the session, redirect, then
	 * use the session to detect that we have been redirected, recover the data and replace the
	 * real WebRequest with a fake one that contains the saved data.
	 *
	 * @param string $subPage
	 * @return bool False if execution should be stopped.
	 */
	protected function handleReturnBeforeExecute( $subPage ) {
		$authManager = AuthManager::singleton();

		if ( $subPage === 'return' ) {
			$this->loadAuth( $subPage );
			$preservedParams = $this->getPreservedParams( false );

			// FIXME save POST values only from request
			$authData = array_diff_key( $this->getRequest()->getValues(),
				$preservedParams, array( 'title' => 1 ) );
			$authManager->setAuthenticationSessionData( 'AuthManagerSpecialPage:return', $authData );

			$url = $this->getPageTitle()->getFullURL( $preservedParams, false, PROTO_HTTPS );
			$this->getOutput()->redirect( $url );
			return false;
		}

		$authData = $authManager->getAuthenticationSessionData( 'AuthManagerSpecialPage:return' );
		if ( $authData ) {
			$authManager->removeAuthenticationSessionData( 'AuthManagerSpecialPage:return' );
			$this->isReturn = true;
			$this->setRequest( $authData, true );
		}

		return true;
	}

	/**
	 * Handle redirection when the user needs to (re)authenticate.
	 *
	 * Send the user to the login form if needed; in case the request was a POST, stash in the
	 * session and simulate it once the user gets back.
	 *
	 * @param string $subPage
	 * @return bool False if execution should be stopped.
	 * @throws ErrorPageError When the user is not allowed to use this page.
	 */
	protected function handleReauthBeforeExecute( $subPage ) {
		$authManager = AuthManager::singleton();
		$request = $this->getRequest();

		$securityLevel = $this->getLoginSecurityLevel();
		if ( $securityLevel ) {
			$securityStatus = AuthManager::singleton()
				->securitySensitiveOperationStatus( $securityLevel );
			if ( $securityStatus === AuthManager::SEC_REAUTH ) {
				$title = SpecialPage::getTitleFor( 'Userlogin' );
				$query = array(
					'returnto' => $this->getPageTitle()->getPrefixedDBkey(),
					'returntoquery' => $request->getRawQueryString(),
					'force' => $securityLevel,
				);
				$url = $title->getFullURL( $query, false, PROTO_HTTPS );

				if ( $request->wasPosted() ) {
					$authData = array_diff_key( $request->getValues(),
							$this->getPreservedParams( false ), array( 'title' => 1 ) );
					$authManager->setAuthenticationSessionData( 'AuthManagerSpecialPage:reauth',
						$authData );
				}

				$this->getOutput()->redirect( $url );
				return false;
			} elseif ( $securityStatus !== AuthManager::SEC_OK ) {
				$titleMessage = wfMessage( 'cannotauth-not-allowed-title' );
				$errorMessage = wfMessage( 'cannotauth-not-allowed' );
				throw new ErrorPageError( $titleMessage, $errorMessage );
			}
		}

		$authData = $authManager->getAuthenticationSessionData( 'AuthManagerSpecialPage:reauth' );
		if ( $authData ) {
			$authManager->removeAuthenticationSessionData( 'AuthManagerSpecialPage:reauth' );
			$this->setRequest( $authData, true );
		}

		return true;
	}

	/**
	 * Handles simulated form submit that's done by passing the token.
	 *
	 * When the anti-CSRF token is present as a parameter in a GET request, pretend it's a POST
	 * (as HTMLForm doesn't work that well with GET requests). Also redirect to a tokenless
	 * URL to avoid similar special handling of future requests and to not expose the token in
	 * the URL; use the session to persist the token while redirecting.
	 *
	 * @param string $subPage
	 * @return bool False if execution should be stopped.
	 */
	protected function handleTokenBeforeExecute( $subPage ) {
		$authManager = AuthManager::singleton();
		$request = $this->getRequest();

		// FIXME: refactor HTMLForm to get rid of this hack
		if ( !$request->wasPosted() && $request->getCheck( $this->getTokenName() ) ) {
			$this->loadAuth( $subPage );
			$preservedParams = $this->getPreservedParams( false );

			// FIXME save POST values only from request
			$authData = array_diff_key( $this->getRequest()->getValues(),
				$preservedParams, array( 'title' => 1 ) );
			$authManager->setAuthenticationSessionData( 'AuthManagerSpecialPage:token', $authData );
			SessionManager::getGlobalSession()->persist();

			$url = $this->getPageTitle()->getFullURL( $preservedParams, false, PROTO_HTTPS );
			$this->getOutput()->redirect( $url );
			return false;
		}

		$authData = $authManager->getAuthenticationSessionData( 'AuthManagerSpecialPage:token' );
		if ( $authData ) {
			$authManager->removeAuthenticationSessionData( 'AuthManagerSpecialPage:token' );
			$this->setRequest( $authData, true );
		}

		return true;
	}


	/**
	 * Get the default action for this special page, if none is given via URL/POST data.
	 * Subclasses should override this (or override loadAuth() so this is never called).
	 * @param string $subPage Subpage of the special page.
	 * @return string an AuthManager::ACTION_* constant.
	 */
	protected function getDefaultAction( $subPage ) {
		throw new LogicException( 'Subclass did not implement getDefaultAction' );
	}

	/**
	 * Load or initialize $authAction, $authRequests and $subPage.
	 * Subclasses should call this from execute() or otherwise ensure the variables are initialized.
	 * @param string $subPage Subpage of the special page.
	 * @param string $authAction Override auth action specified in request (this is useful
	 *    when the form needs to be changed from <action> to <action>_CONTINUE after a successful
	 *    authentication step)
	 */
	protected function loadAuth( $subPage, $authAction = null ) {
		$request = $this->getRequest();

		$this->subPage = $subPage;
		$this->authAction = $authAction ?: $request->getText( 'authAction' );
		if ( !in_array( $this->authAction, static::$allowedActions, true ) ) {
			$this->authAction = $this->getDefaultAction( $subPage );
		}
		$returnToUrl = $this->getPageTitle( 'return' )->getFullURL( $this->getPreservedParams( true ),
			false, PROTO_HTTPS );
		$this->authRequests =
			AuthManager::singleton()->getAuthenticationRequests( $this->authAction, $returnToUrl );
	}

	/**
	 * Returns true if this is not the first step of the authentication.
	 * @return bool
	 */
	protected function isContinued() {
		return in_array( $this->authAction, array(
			AuthManager::ACTION_LOGIN_CONTINUE,
			AuthManager::ACTION_CREATE_CONTINUE,
			AuthManager::ACTION_LINK_CONTINUE,
		), true );
	}

	/**
	 * Gets the _CONTINUE version of an action.
	 * @param string $action An AuthManager::ACTION_* constant.
	 * @return string An AuthManager::ACTION_*_CONTINUE constant.
	 */
	protected function getContinueAction( $action ) {
		switch ( $action ) {
			case AuthManager::ACTION_LOGIN:
				$action = AuthManager::ACTION_LOGIN_CONTINUE;
				break;
			case AuthManager::ACTION_CREATE:
				$action = AuthManager::ACTION_CREATE_CONTINUE;
				break;
			case AuthManager::ACTION_LINK:
				$action = AuthManager::ACTION_LINK_CONTINUE;
				break;
		}
		return $action;
	}

	/**
	 * Checks whether AuthManager is ready to perform the action.
	 * ACTION_CHANGE needs special verification (AuthManager::allowsAuthenticationData*) which is
	 * the caller's responsibility.
	 * @param string $action One of the AuthManager::ACTION_* constants
	 * @return bool
	 * @throws LogicException if $action is invalid
	 */
	protected function isActionAllowed( $action ) {
		$authManager = AuthManager::singleton();
		if ( !in_array( $action, static::$allowedActions, true ) ) {
			throw new LogicException( 'invalid action: ' . $action );
		}

		if ( !$authManager->getAuthenticationRequests( $action ) ) {
			// no provider supports this action in the current state
			return false;
		}

		switch ( $action ) {
			case AuthManager::ACTION_LOGIN:
			case AuthManager::ACTION_LOGIN_CONTINUE:
				return $authManager->canAuthenticateNow();
			case AuthManager::ACTION_CREATE:
			case AuthManager::ACTION_CREATE_CONTINUE:
				return $authManager->canCreateAccounts();
			case AuthManager::ACTION_LINK:
			case AuthManager::ACTION_LINK_CONTINUE:
				return $authManager->canLinkAccounts();
			case AuthManager::ACTION_CHANGE:
			case AuthManager::ACTION_REMOVE:
			case AuthManager::ACTION_UNLINK:
				return true;
			default:
				// should never reach here but makes static code analyzers happy
				throw new LogicException( 'invalid action: ' . $action );
		}
	}

	/**
	 * @param string $action One of the AuthManager::ACTION_* constants
	 * @param AuthenticationRequest[] $requests
	 * @return AuthenticationResponse
	 * @throws LogicException if $action is invalid
	 */
	protected function performAuthenticationStep( $action, array $requests ) {
		$authManager = AuthManager::singleton();
		if ( !in_array( $action, static::$allowedActions, true ) ) {
			throw new LogicException( 'invalid action: ' . $action );
		}

		switch ( $action ) {
			case AuthManager::ACTION_LOGIN:
				return $authManager->beginAuthentication( $requests );
			case AuthManager::ACTION_LOGIN_CONTINUE:
				return $authManager->continueAuthentication( $requests );
			case AuthManager::ACTION_CREATE:
				return $authManager->beginAccountCreation(
					AuthFrontend::getUsernameFromRequests( $requests ),
					$this->mContext->getUser(), $requests );
			case AuthManager::ACTION_CREATE_CONTINUE:
				return $authManager->continueAccountCreation( $requests );
			case AuthManager::ACTION_LINK:
				return $authManager->beginAccountLink( $this->getUser(), $requests );
			case AuthManager::ACTION_LINK_CONTINUE:
				return $authManager->continueAccountLink( $requests );
			case AuthManager::ACTION_CHANGE:
				if ( count( $requests ) !== 1 ) {
					throw new LogicException( 'only one auth request can be changed at a time' );
				}
				$req = reset( $requests );
				$status = $authManager->allowsAuthenticationDataChange( $req );
				if ( !$status->isOK() ) {
					return AuthenticationResponse::newFail( $status->getMessage() );
				}
				$authManager->changeAuthenticationData( $req );
				return AuthenticationResponse::newPass();
			case AuthManager::ACTION_REMOVE:
			case AuthManager::ACTION_UNLINK:
				if ( count( $requests ) !== 1 ) {
					throw new LogicException( 'only one auth request can be changed at a time' );
				}
				$req = reset( $requests );
				$status = $authManager->allowsAuthenticationDataRemoval( $req );
				if ( !$status->isOK() ) {
					return AuthenticationResponse::newFail( $status->getMessage() );
				}
				$authManager->removeAuthenticationData( reset( $requests ) );
				return AuthenticationResponse::newPass();
			default:
				// should never reach here but makes static code analyzers happy
				throw new LogicException( 'invalid action: ' . $action );
		}
	}

	/**
	 * Attempts to do an authentication step with the submitted data.
	 * Subclasses should probably call this from execute().
	 * @return false|Status
	 *    - false if there was no submit at all
	 *    - a good Status wrapping an AuthenticationResponse if the form submit was successful.
	 *      This does not necessarily mean that the authentication itself was successful; see the
	 *      response for that.
	 *    - a bad Status for form errors.
	 */
	protected function trySubmit() {
		$status = false;

		$form = $this->getAuthForm( $this->authRequests, $this->authAction );
		$form->setSubmitCallback( array( $this, 'handleFormSubmit' ) );

		if ( $this->getRequest()->wasPosted() ) {
			$token = $this->getRequest()->getVal( $this->getTokenName() );
			if ( $token !== $this->getToken() ) {
				return Status::newFatal( 'authform-notoken' );
			}

			$form->prepareForm();
			$status = $form->trySubmit();

			// HTMLForm submit return values are a mess; let's ensure it is false or a Status
			// FIXME this probably should be in HTMLForm
			if ( $status === true ) {
				// not supposed to happen since our submit handler should always return a Status
				throw new LogicException( 'HTMLForm::trySubmit() returned true' );
			} elseif ( $status === false ) {
				// form was not submitted; nothing to do
			} elseif ( $status instanceof Status ) {
				// successful submit; nothing to do
			} elseif ( $status instanceof StatusValue ) {
				// in theory not an allowed return type but nothing stops the submit handler from
				// accidentally returning it so best check and fix
				$status = Status::wrap( $status );
			} elseif ( is_string( $status ) ) {
				$status = Status::newFatal( new RawMessage( $status ) );
			} elseif ( is_array( $status ) ) {
				if ( is_string( reset( $status ) ) ) {
					$status = Status::newFatal( wfMessage( $status ) );
				} elseif ( is_array( reset( $status ) ) ) {
					$status = Status::newGood();
					foreach ( $status as $message ) {
						$message = wfMessage( $message );
						$status->fatal( $message );
					}
				} else {
					throw new LogicException( 'invalid HTMLForm::trySubmit() return value: '
						. 'first element of array is ' . getType( reset( $status ) ) );
				}
			} else {
				// not supposed to happen but HTMLForm does not actually verify the return type
				// from the submit callback; better safe then sorry
				throw new LogicException( 'invalid HTMLForm::trySubmit() return type: '
					. getType( $status ) );
			}

			if ( ( !$status || !$status->isOK() ) && $this->isReturn ) {
				// This is awkward. There was a form validation error, which means the data was not
				// passed to AuthManager. Normally we would display the form with an error message,
				// but for the data we received via the redirect flow that would not be helpful at all.
				// Let's just submit the data to AuthManager directly instead.
				LoggerFactory::getInstance( 'authmanager' )
					->warning( 'Validation error on return', array( 'data' => $form->mFieldData,
						'status' => $status->getWikiText() ) );
				$status = $this->handleFormSubmit( $form->mFieldData );
			}
		}

		return $status;
	}

	/**
	 * Submit handler callback for HTMLForm
	 * @private
	 * @param $data array Submitted data
	 * @return Status
	 */
	public function handleFormSubmit( $data ) {
		$requests = AuthenticationRequest::loadRequestsFromSubmission( $this->authRequests, $data );
		// FIXME how do we set the username?
		$response = $this->performAuthenticationStep( $this->authAction, $requests );

		// we can't handle FAIL or similar as failure here since it might require changing the form
		return Status::newGood( $response );
	}

	/**
	 * Returns URL query parameters which can be used to reload the page (or leave and return) while
	 * preserving all information that is necessary for authentication to continue.
	 * @param bool $withToken Include CSRF token
	 * @return array
	 */
	protected function getPreservedParams( $withToken = true ) {
		$params =  array(
			'authAction' => $this->getContinueAction( $this->authAction ),
		);
		if ( $withToken ) {
			$params[$this->getTokenName()] = $this->getToken();
		}
		return $params;
	}

	/**
	 * Generates a HTMLForm descriptor array from a set of authentication requests.
	 * @param AuthenticationRequest[] $requests
	 * @param string $action AuthManager action name
	 * @return array
	 */
	protected function getAuthFormDescriptor( $requests, $action ) {
		$fieldInfo = AuthFrontend::mergeFieldInfo( $requests );
		$formDescriptor = AuthFrontend::fieldInfoToFormDescriptor( $fieldInfo, $action );

		$this->addTabIndex( $formDescriptor );

		return $formDescriptor;
	}

	/**
	 * @param AuthenticationRequest[] $requests
	 * @param string $action AuthManager action name, should be ACTION_LINK or ACTION_LINK_CONTINUE
	 * @return HTMLForm
	 */
	protected function getAuthForm( array $requests, $action ) {
		$formDescriptor = $this->getAuthFormDescriptor( $requests, $action );
		$context = $this->getContext();
		if ( $context->getRequest() !== $this->getRequest() ) {
			// We have overridden the request, need to make sure the form uses that too.
			$context = new DerivativeContext( $this->getContext() );
			$context->setRequest( $this->getRequest() );
		}
		$form = HTMLForm::factory( 'ooui', $formDescriptor, $context );
		$form->addHiddenField( $this->getTokenName(), $this->getToken() );
		$form->addHiddenField( 'authAction', $this->authAction );
		$form->suppressDefaultSubmit( !$this->needsSubmitButton( $formDescriptor ) );

		return $form;
	}

	/**
	 * Returns true if the form has fields which take values. If all available providers use the
	 * redirect flow, the form might contain nothing but submit buttons, in which case we should
	 * not add an extra submit button which does nothing.
	 *
	 * @param array $formDescriptor A HTMLForm descriptor
	 * @return bool
	 */
	protected function needsSubmitButton( $formDescriptor ) {
		return (bool)array_filter( $formDescriptor, function ( $item ) {
			$class = false;
			if ( array_key_exists( 'class', $item ) ) {
				$class = $item['class'];
			} elseif ( array_key_exists( 'type', $item ) ) {
				$class = HTMLForm::$typeMappings[$item['type']];
			}
			return !in_array( $class, array( 'HTMLInfoField', 'HTMLSubmitField' ), true );
		} );
	}

	/**
	 * Adds a sequential tabindex starting from 1 to all form elements. This way the user can
	 * use the tab key to traverse the form without having to step through all links and such.
	 * @param $formDescriptor
	 */
	protected function addTabIndex( &$formDescriptor ) {
		$i = 1;
		foreach ( $formDescriptor as $field => &$definition ) {
			$class = false;
			if ( array_key_exists( 'class', $definition ) ) {
				$class = $definition['class'];
			} elseif ( array_key_exists( 'type', $definition ) ) {
				$class = HTMLForm::$typeMappings[$definition['type']];
			}
			if ( $class !== 'HTMLInfoField' ) {
				$definition['tabindex'] = $i;
				$i++;
			}
		}
	}

	/**
	 * Returns the CSRF token. Subclassess that can be used by anonymous users must override this.
	 * @return string
	 */
	protected function getToken() {
		if ( $this->getUser()->isAnon() ) {
			throw new LogicException( 'trying to get token for anonymous user' );
		}
		return $this->getUser()->getEditToken( $this->getName() );
	}

	/**
	 * Returns the name of the CSRF token (under which it should be found in the POST or GET data).
	 * @return string
	 */
	protected function getTokenName() {
		return 'wpEditToken';
	}
}
