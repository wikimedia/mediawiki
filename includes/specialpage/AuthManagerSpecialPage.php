<?php

use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthFrontend;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Session\SessionManager;

/**
 * A special page subclass for authentication-related special pages. It generates a form from
 * a set of AuthenticationRequest objects or types, submits the result to AuthManager and
 * partially handles the response.
 */
class AuthManagerSpecialPage extends SpecialPage {
	/** @var string[] The list of actions this special page deals with. Subclasses should override
	 * this. */
	protected static $allowedActions = array(
		AuthManager::ACTION_LOGIN, AuthManager::ACTION_LOGIN_CONTINUE,
		AuthManager::ACTION_CREATE, AuthManager::ACTION_CREATE_CONTINUE,
		AuthManager::ACTION_LINK, AuthManager::ACTION_LINK_CONTINUE,
		AuthManager::ACTION_CHANGE,
	);

	/** @var string one of the AuthManager::ACTION_* constants. */
	protected $authAction;

	/** @var array A list of AuthenticationRequest class names */
	protected $authTypes;

	/** @var string Subpage of the special page. */
	protected $subPage;

	/** @var bool True if the current request is a result of returning from a redirect flow. */
	protected $isReturn;

	public function __construct( $name = '', $restriction = '', $listed = true, $function = false,
								 $file = '', $includable = false ) {
		parent::__construct( $name, $restriction, $listed, $function, $file,
			$includable );
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

	protected function beforeExecute( $subPage ) {
		$sessionKey = 'AuthManagerSpecialPage:return';
		$authManager = AuthManager::singleton();

		if ( $subPage === 'return' ) {
			// This is a return URL, used as POST-replacement for redirect flows, Redirect to the
			// base URL to avoid to avoid the URL parameters sticking around and causing trouble
			// in subsequent steps.

			$this->loadAuth( $subPage );
			$preservedParams = $this->getPreservedParams();
			$sessionParams = array_diff_key( $this->getRequest()->getValues(), $preservedParams );
			unset( $sessionParams['title'] );
			$url = $this->getPageTitle()->getFullURL( $preservedParams, false, PROTO_HTTPS );

			$authManager->setAuthenticationData( $sessionKey, $sessionParams );

			$this->getOutput()->redirect( $url );
			return false;
		}

		$sessionParams = $authManager->getAuthenticationData( $sessionKey );
		if ( $sessionParams ) {
			$authManager->removeAuthenticationData( $sessionKey );
			$this->isReturn = true;
			foreach ( $sessionParams as $name => $value) {
				$this->getRequest()->setVal( $name, $value );
			}
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
	 * Load or initialize $authAction, $authTypes and $subPage.
	 * Subclasses should call this from execute() or otherwise ensure the variables are initialized.
	 * @param string $subPage Subpage of the special page.
	 * @param string $authAction Override auth action specified in request (this is useful
	 *    when the form needs to be changed from <action> to <action>_CONTINUE after a successful
	 *    authentication step)
	 */
	protected function loadAuth( $subPage, $authAction = null ) {
		$this->subPage = $subPage;
		$this->authAction = $authAction ?: $this->getRequest()->getText( 'authAction' );
		if ( !in_array( $this->authAction, static::$allowedActions, true ) ) {
			$this->authAction = $this->getDefaultAction( $subPage );
		}
		$this->authTypes =
			AuthManager::singleton()->getAuthenticationRequestTypes( $this->authAction );
		if ( $this->authAction === AuthManager::ACTION_CREATE ) {
			$this->authTypes[] = 'MediaWiki\Auth\UserDataAuthenticationRequest';
		}
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

		if ( !$authManager->getAuthenticationRequestTypes( $action ) ) {
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
				return $authManager->beginAccountLink(
					SessionManager::getGlobalSession()->getUser(), $requests );
			case AuthManager::ACTION_LINK_CONTINUE:
				return $authManager->continueAccountLink( $requests );
			case AuthManager::ACTION_CHANGE:
				if ( count( $requests ) !== 1 ) {
					throw new LogicException( 'only one auth request can be changed at a time' );
				}
				$authManager->changeAuthenticationData( reset( $requests ) );
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

		$form = $this->getAuthForm( $this->authTypes, $this->authAction );
		$form->setSubmitCallback( array( $this, 'handleFormSubmit' ) );

		if ( $this->getRequest()->wasPosted() || $this->isReturn ) {
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
		$requests = AuthenticationRequest::requestsFromSubmission( $this->authTypes, $data,
			$this->getPageTitle( 'return' )->getFullURL( $this->getPreservedParams(), false,
				PROTO_HTTPS ) );

//		// FIXME should AuthenticationRequest::requestsFromSubmission do this?
//		if ( isset( $data['username'] ) ) {
//			foreach ( $requests as $request ) {
//				$request->username = $data['username'];
//			}
//		}

		$response = $this->performAuthenticationStep( $this->authAction, $requests );

		// we can't handle FAIL or similar as failure here since it might require changing the form
		return Status::newGood( $response );
	}

	/**
	 * Returns URL query parameters which can be used to reload the page (or leave and return) while
	 * preserving all information that is necessary for authentication to continue.
	 * @return array
	 */
	protected function getPreservedParams() {
		return array(
			'authAction' => $this->getContinueAction( $this->authAction ),
			// FIXME token?
		);
	}

	/**
	 * Generates a HTMLForm descriptor array from a set of authentication requests.
	 * @param string[]|AuthenticationRequest[] $requestsOrTypes A set of AuthenticationRequest
	 *    objects or class names
	 * @param string $action AuthManager action name
	 * @return array
	 */
	protected function getAuthFormDescriptor( $requestsOrTypes, $action ) {
		$fieldInfo = AuthFrontend::mergeFieldInfo( $requestsOrTypes );
		$formDescriptor = AuthFrontend::fieldInfoToFormDescriptor( $fieldInfo, $action );

		$this->addTabIndex( $formDescriptor );

		// If an AuthenticationResponse contained some requests and submitting those requests
		// resulted in a form display, preserve the data in the requests. This should not happen.
		if ( $requestsOrTypes && is_object( $requestsOrTypes[0] ) ) {
			foreach ( $requestsOrTypes as $request ) {
				foreach ( $request->getFieldInfo() as $field => $_ ) {
					if ( $formDescriptor[$field]['type'] === 'password' ) {
						continue;
					}
					$formDescriptor[$field]['default'] = $request->$field;
				}
			}
		}

		return $formDescriptor;
	}

	/**
	 * @param string[]|AuthenticationRequest[] $requestsOrTypes A set of AuthenticationRequest objects or class names
	 * @param string $action AuthManager action name, should be ACTION_LINK or ACTION_LINK_CONTINUE
	 * @return HTMLForm
	 */
	protected function getAuthForm( array $requestsOrTypes, $action ) {
		$formDescriptor = $this->getAuthFormDescriptor( $requestsOrTypes, $action );
		$form = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() );
		$form->setTokenSalt( $this->mName );
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
}
