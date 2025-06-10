<?php

namespace MediaWiki\SpecialPage;

use InvalidArgumentException;
use LogicException;
use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\HTMLForm\Field\HTMLInfoField;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Language\RawMessage;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Request\DerivativeRequest;
use MediaWiki\Request\WebRequest;
use MediaWiki\Session\Token;
use MediaWiki\Status\Status;
use MWCryptRand;
use Profiler;
use StatusValue;
use UnexpectedValueException;

/**
 * A special page subclass for authentication-related special pages. It generates a form from
 * a set of AuthenticationRequest objects, submits the result to AuthManager and
 * partially handles the response.
 *
 * @note Call self::setAuthManager from special page constructor when extending
 *
 * @stable to extend
 * @ingroup Auth
 */
abstract class AuthManagerSpecialPage extends SpecialPage {
	/** @var string[] The list of actions this special page deals with. Subclasses should override
	 * this.
	 */
	protected static $allowedActions = [
		AuthManager::ACTION_LOGIN, AuthManager::ACTION_LOGIN_CONTINUE,
		AuthManager::ACTION_CREATE, AuthManager::ACTION_CREATE_CONTINUE,
		AuthManager::ACTION_LINK, AuthManager::ACTION_LINK_CONTINUE,
		AuthManager::ACTION_CHANGE, AuthManager::ACTION_REMOVE, AuthManager::ACTION_UNLINK,
	];

	/** @var array Customized messages */
	protected static $messages = [];

	/** @var string one of the AuthManager::ACTION_* constants. */
	protected $authAction;

	/** @var AuthenticationRequest[] */
	protected $authRequests;

	/** @var string Subpage of the special page. */
	protected $subPage;

	/** @var bool True if the current request is a result of returning from a redirect flow. */
	protected $isReturn;

	/** @var WebRequest|null If set, will be used instead of the real request. Used for redirection. */
	protected $savedRequest;

	/**
	 * Change the form descriptor that determines how a field will look in the authentication form.
	 * Called from fieldInfoToFormDescriptor().
	 * @stable to override
	 *
	 * @param AuthenticationRequest[] $requests
	 * @param array $fieldInfo Field information array (union of all
	 *    AuthenticationRequest::getFieldInfo() responses).
	 * @param array &$formDescriptor HTMLForm descriptor. The special key 'weight' can be set to
	 *    change the order of the fields.
	 * @param string $action Authentication type (one of the AuthManager::ACTION_* constants)
	 */
	public function onAuthChangeFormFields(
		array $requests, array $fieldInfo, array &$formDescriptor, $action
	) {
	}

	/**
	 * @stable to override
	 * @return bool|string
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
	 * @stable to override
	 *
	 * @param array $data
	 * @param bool|null $wasPosted
	 */
	protected function setRequest( array $data, $wasPosted = null ) {
		$request = $this->getContext()->getRequest();
		$this->savedRequest = new DerivativeRequest(
			$request,
			$data + $request->getQueryValues(),
			$wasPosted ?? $request->wasPosted()
		);
	}

	/** @inheritDoc */
	protected function beforeExecute( $subPage ) {
		$this->getOutput()->disallowUserJs();

		return $this->handleReturnBeforeExecute( $subPage )
			&& $this->handleReauthBeforeExecute( $subPage );
	}

	/**
	 * Handle redirection from the /return subpage.
	 *
	 * This is used in the redirect flow where we need
	 * to be able to process data that was sent via a GET request. We set the /return subpage as
	 * the reentry point, so we know we need to treat GET as POST, but we don't want to handle all
	 * future GETs requests as POSTs, so we need to normalize the URL. (Also, we don't want to show any
	 * received parameters around in the URL; they are ugly and might be sensitive.)
	 *
	 * Thus, when on the /return subpage, we stash the request data in the session, redirect, then
	 * use the session to detect that we have been redirected, recover the data and replace the
	 * real WebRequest with a fake one that contains the saved data.
	 *
	 * @param string $subPage
	 * @return bool False if execution should be stopped.
	 */
	protected function handleReturnBeforeExecute( $subPage ) {
		$authManager = $this->getAuthManager();
		$key = 'AuthManagerSpecialPage:return:' . $this->getName();

		if ( $subPage === 'return' ) {
			$this->loadAuth( $subPage );
			$preservedParams = $this->getPreservedParams();

			// FIXME save POST values only from request
			$authData = array_diff_key( $this->getRequest()->getValues(),
				$preservedParams, [ 'title' => 1 ] );
			$uniqueId = MWCryptRand::generateHex( 6 );
			$preservedParams['authUniqueId'] = $uniqueId;
			$key .= ':' . $uniqueId;
			$authManager->setAuthenticationSessionData( $key, $authData );

			$url = $this->getPageTitle()->getFullURL( $preservedParams, false, PROTO_HTTPS );
			$this->getOutput()->redirect( $url );
			return false;
		} elseif ( $this->getRequest()->getCheck( 'authUniqueId' ) ) {
			$uniqueId = $this->getRequest()->getVal( 'authUniqueId' );
			$key .= ':' . $uniqueId;
			$authData = $authManager->getAuthenticationSessionData( $key );
			if ( $authData ) {
				$authManager->removeAuthenticationSessionData( $key );
				$this->isReturn = true;
				$this->setRequest( $authData, true );
				$this->setPostTransactionProfilerExpectations( __METHOD__ );
			}
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
		$authManager = $this->getAuthManager();
		$request = $this->getRequest();
		$key = 'AuthManagerSpecialPage:reauth:' . $this->getName();

		$securityLevel = $this->getLoginSecurityLevel();
		if ( $securityLevel ) {
			$securityStatus = $authManager->securitySensitiveOperationStatus( $securityLevel );
			if ( $securityStatus === AuthManager::SEC_REAUTH ) {
				$queryParams = array_diff_key( $request->getQueryValues(), [ 'title' => true ] );

				if ( $request->wasPosted() ) {
					// unique ID in case the same special page is open in multiple browser tabs
					$uniqueId = MWCryptRand::generateHex( 6 );
					$key .= ':' . $uniqueId;

					$queryParams = [ 'authUniqueId' => $uniqueId ] + $queryParams;
					$authData = array_diff_key( $request->getValues(),
							$this->getPreservedParams(), [ 'title' => 1 ] );
					$authManager->setAuthenticationSessionData( $key, $authData );
				}

				// Copied from RedirectSpecialPage::getRedirectQuery()
				// Would using $this->getPreservedParams() be appropriate here?
				$keepParams = [ 'uselang', 'useskin', 'useformat', 'variant', 'debug', 'safemode' ];

				$title = SpecialPage::getTitleFor( 'Userlogin' );
				$url = $title->getFullURL( [
					'returnto' => $this->getFullTitle()->getPrefixedDBkey(),
					'returntoquery' => wfArrayToCgi( $queryParams ),
					'force' => $securityLevel,
				] + array_intersect_key( $queryParams, array_fill_keys( $keepParams, true ) ), false, PROTO_HTTPS );

				$this->getOutput()->redirect( $url );
				return false;
			}

			if ( $securityStatus !== AuthManager::SEC_OK ) {
				throw new ErrorPageError( 'cannotauth-not-allowed-title', 'cannotauth-not-allowed' );
			}
		}

		$uniqueId = $request->getVal( 'authUniqueId' );
		if ( $uniqueId ) {
			$key .= ':' . $uniqueId;
			$authData = $authManager->getAuthenticationSessionData( $key );
			if ( $authData ) {
				$authManager->removeAuthenticationSessionData( $key );
				$this->setRequest( $authData, true );
				$this->setPostTransactionProfilerExpectations( __METHOD__ );
			}
		}

		return true;
	}

	private function setPostTransactionProfilerExpectations( string $fname ) {
		$trxLimits = $this->getConfig()->get( MainConfigNames::TrxProfilerLimits );
		$trxProfiler = Profiler::instance()->getTransactionProfiler();
		$trxProfiler->redefineExpectations( $trxLimits['POST'], $fname );
		DeferredUpdates::addCallableUpdate( static function () use ( $trxProfiler, $trxLimits, $fname ) {
			$trxProfiler->redefineExpectations( $trxLimits['PostSend-POST'], $fname );
		} );
	}

	/**
	 * Get the default action for this special page if none is given via URL/POST data.
	 * Subclasses should override this (or override loadAuth() so this is never called).
	 * @stable to override
	 * @param string $subPage Subpage of the special page.
	 * @return string an AuthManager::ACTION_* constant.
	 */
	abstract protected function getDefaultAction( $subPage );

	/**
	 * Return custom message key.
	 * Allows subclasses to customize messages.
	 * @param string $defaultKey
	 * @return string
	 */
	protected function messageKey( $defaultKey ) {
		return array_key_exists( $defaultKey, static::$messages )
			? static::$messages[$defaultKey] : $defaultKey;
	}

	/**
	 * Allows blacklisting certain request types.
	 * @stable to override
	 * @return array A list of AuthenticationRequest subclass names
	 */
	protected function getRequestBlacklist() {
		return [];
	}

	/**
	 * Load or initialize $authAction, $authRequests and $subPage.
	 * Subclasses should call this from execute() or otherwise ensure the variables are initialized.
	 * @stable to override
	 * @param string $subPage Subpage of the special page.
	 * @param string|null $authAction Override auth action specified in request (this is useful
	 *    when the form needs to be changed from <action> to <action>_CONTINUE after a successful
	 *    authentication step)
	 * @param bool $reset Regenerate the requests even if a cached version is available
	 */
	protected function loadAuth( $subPage, $authAction = null, $reset = false ) {
		// Do not load if already loaded, to cut down on the number of getAuthenticationRequests
		// calls. This is important for requests which have hidden information, so any
		// getAuthenticationRequests call would mean putting data into some cache.
		if (
			!$reset && $this->subPage === $subPage && $this->authAction
			&& ( !$authAction || $authAction === $this->authAction )
		) {
			return;
		}

		$request = $this->getRequest();
		$this->subPage = $subPage;
		$this->authAction = $authAction ?: $request->getText( 'authAction' );
		if ( !in_array( $this->authAction, static::$allowedActions, true ) ) {
			$this->authAction = $this->getDefaultAction( $subPage );
			if ( $request->wasPosted() ) {
				$continueAction = $this->getContinueAction( $this->authAction );
				if ( in_array( $continueAction, static::$allowedActions, true ) ) {
					$this->authAction = $continueAction;
				}
			}
		}

		$allReqs = $this->getAuthManager()->getAuthenticationRequests(
			$this->authAction, $this->getUser() );
		$this->authRequests = array_filter( $allReqs, function ( $req ) {
			return !in_array( get_class( $req ), $this->getRequestBlacklist(), true );
		} );
	}

	/**
	 * Returns true if this is not the first step of the authentication.
	 * @return bool
	 */
	protected function isContinued() {
		return in_array( $this->authAction, [
			AuthManager::ACTION_LOGIN_CONTINUE,
			AuthManager::ACTION_CREATE_CONTINUE,
			AuthManager::ACTION_LINK_CONTINUE,
		], true );
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
	 * @param string $action One of the AuthManager::ACTION_* constants in static::$allowedActions
	 * @return bool
	 */
	protected function isActionAllowed( $action ) {
		$authManager = $this->getAuthManager();
		if ( !in_array( $action, static::$allowedActions, true ) ) {
			throw new InvalidArgumentException( 'invalid action: ' . $action );
		}

		// calling getAuthenticationRequests can be expensive, avoid if possible
		$requests = ( $action === $this->authAction ) ? $this->authRequests
			: $authManager->getAuthenticationRequests( $action );
		if ( !$requests ) {
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
				throw new InvalidArgumentException( 'invalid action: ' . $action );
		}
	}

	/**
	 * @param string $action One of the AuthManager::ACTION_* constants
	 * @param AuthenticationRequest[] $requests
	 * @return AuthenticationResponse
	 * @throws LogicException if $action is invalid
	 */
	protected function performAuthenticationStep( $action, array $requests ) {
		if ( !in_array( $action, static::$allowedActions, true ) ) {
			throw new InvalidArgumentException( 'invalid action: ' . $action );
		}

		$authManager = $this->getAuthManager();
		$returnToUrl = $this->getPageTitle( 'return' )
			->getFullURL( $this->getPreservedParams( [ 'withToken' => true ] ), false, PROTO_HTTPS );

		switch ( $action ) {
			case AuthManager::ACTION_LOGIN:
				return $authManager->beginAuthentication( $requests, $returnToUrl );
			case AuthManager::ACTION_LOGIN_CONTINUE:
				return $authManager->continueAuthentication( $requests );
			case AuthManager::ACTION_CREATE:
				return $authManager->beginAccountCreation( $this->getAuthority(), $requests,
					$returnToUrl );
			case AuthManager::ACTION_CREATE_CONTINUE:
				return $authManager->continueAccountCreation( $requests );
			case AuthManager::ACTION_LINK:
				return $authManager->beginAccountLink( $this->getUser(), $requests, $returnToUrl );
			case AuthManager::ACTION_LINK_CONTINUE:
				return $authManager->continueAccountLink( $requests );
			case AuthManager::ACTION_CHANGE:
			case AuthManager::ACTION_REMOVE:
			case AuthManager::ACTION_UNLINK:
				if ( count( $requests ) > 1 ) {
					throw new InvalidArgumentException( 'only one auth request can be changed at a time' );
				}

				if ( !$requests ) {
					throw new InvalidArgumentException( 'no auth request' );
				}
				$req = reset( $requests );
				$status = $authManager->allowsAuthenticationDataChange( $req );
				$this->getHookRunner()->onChangeAuthenticationDataAudit( $req, $status );
				if ( !$status->isGood() ) {
					return AuthenticationResponse::newFail( $status->getMessage() );
				}
				$authManager->changeAuthenticationData( $req );
				return AuthenticationResponse::newPass();
			default:
				// should never reach here but makes static code analyzers happy
				throw new InvalidArgumentException( 'invalid action: ' . $action );
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
		$form->setSubmitCallback( $this->handleFormSubmit( ... ) );

		if ( $this->getRequest()->wasPosted() ) {
			// handle tokens manually; $form->tryAuthorizedSubmit only works for logged-in users
			$requestTokenValue = $this->getRequest()->getVal( $this->getTokenName() );
			$sessionToken = $this->getToken();
			if ( $sessionToken->wasNew() ) {
				return Status::newFatal( $this->messageKey( 'authform-newtoken' ) );
			} elseif ( !$requestTokenValue ) {
				return Status::newFatal( $this->messageKey( 'authform-notoken' ) );
			} elseif ( !$sessionToken->match( $requestTokenValue ) ) {
				return Status::newFatal( $this->messageKey( 'authform-wrongtoken' ) );
			}

			$form->prepareForm();
			$status = $form->trySubmit();

			// HTMLForm submit return values are a mess; let's ensure it is false or a Status
			// FIXME this probably should be in HTMLForm
			if ( $status === true ) {
				// not supposed to happen since our submit handler should always return a Status
				throw new UnexpectedValueException( 'HTMLForm::trySubmit() returned true' );
			} elseif ( $status === false ) {
				// form was not submitted; nothing to do
			} elseif ( $status instanceof Status ) {
				// already handled by the form; nothing to do
			} elseif ( $status instanceof StatusValue ) {
				// in theory not an allowed return type but nothing stops the submit handler from
				// accidentally returning it so best check and fix
				$status = Status::wrap( $status );
			} elseif ( is_string( $status ) ) {
				$status = Status::newFatal( new RawMessage( '$1', [ $status ] ) );
			} elseif ( is_array( $status ) ) {
				if ( is_string( reset( $status ) ) ) {
					// @phan-suppress-next-line PhanParamTooFewUnpack
					$status = Status::newFatal( ...$status );
				} elseif ( is_array( reset( $status ) ) ) {
					$ret = Status::newGood();
					foreach ( $status as $message ) {
						// @phan-suppress-next-line PhanParamTooFewUnpack
						$ret->fatal( ...$message );
					}
					$status = $ret;
				} else {
					throw new UnexpectedValueException( 'invalid HTMLForm::trySubmit() return value: '
						. 'first element of array is ' . get_debug_type( reset( $status ) ) );
				}
			} else {
				// not supposed to happen, but HTMLForm does not verify the return type
				// from the submit callback; better safe then sorry!
				throw new UnexpectedValueException( 'invalid HTMLForm::trySubmit() return type: '
					. get_debug_type( $status ) );
			}

			if ( ( !$status || !$status->isOK() ) && $this->isReturn ) {
				// This is awkward. There was a form validation error, which means the data was not
				// passed to AuthManager. Normally we would display the form with an error message,
				// but for the data we received via the redirect flow that would not be helpful at all.
				// Let's just submit the data to AuthManager directly instead.
				LoggerFactory::getInstance( 'authentication' )
					->warning( 'Validation error on return', [ 'data' => $form->mFieldData,
						'status' => $status->getWikiText( false, false, 'en' ) ] );
				$status = $this->handleFormSubmit( $form->mFieldData );
			}
		}

		$changeActions = [
			AuthManager::ACTION_CHANGE, AuthManager::ACTION_REMOVE, AuthManager::ACTION_UNLINK
		];
		if ( in_array( $this->authAction, $changeActions, true ) && $status && !$status->isOK() ) {
			$this->getHookRunner()->onChangeAuthenticationDataAudit( reset( $this->authRequests ), $status );
		}

		return $status;
	}

	/**
	 * Submit handler callback for HTMLForm
	 * @internal
	 * @param array $data Submitted data
	 * @return Status
	 */
	public function handleFormSubmit( $data ) {
		$requests = AuthenticationRequest::loadRequestsFromSubmission( $this->authRequests, $data );
		$response = $this->performAuthenticationStep( $this->authAction, $requests );

		// we can't handle FAIL or similar as failure here since it might require changing the form
		return Status::newGood( $response );
	}

	/**
	 * Returns URL query parameters which should be preserved between authentication requests.
	 * These should be used when generating links such as form submit or language switch.
	 *
	 * These parameters will be preserved in:
	 * - successive authentication steps (the form submit URL and the return URL for redirecting
	 *   providers);
	 * - links that reload the same form somehow (e.g. language switcher links);
	 * - links for switching between the login and create account forms.
	 *
	 * @stable to override
	 * @param array $options (since 1.43)
	 *   - reset (bool, default false): Reset the authentication process, i.e. omit parameters
	 *     which are related to continuing in-progress authentication.
	 *   - withToken (bool, default false): Include CSRF token
	 *   Before 1.43, this was a boolean flag identical to the current 'withToken' option.
	 *   That usage is deprecated.
	 * @phan-param array{reset?: bool, withToken?: bool}|bool $options
	 * @return array Array of parameter name => parameter value.
	 */
	protected function getPreservedParams( $options = [] ) {
		if ( is_bool( $options ) ) {
			wfDeprecated( __METHOD__ . ' boolean $options', '1.43' );
			$options = [ 'withToken' => $options ];
		}
		$options += [
			'reset' => false,
			'withToken' => false,
		];
		// Help Phan figure out that these fields are now definitely set - https://github.com/phan/phan/issues/4864
		'@phan-var array{reset: bool, withToken: bool} $options';
		$params = [];
		$request = $this->getRequest();

		$params += [
			'uselang' => $request->getVal( 'uselang' ),
			'variant' => $request->getVal( 'variant' ),
			'returnto' => $request->getVal( 'returnto' ),
			'returntoquery' => $request->getVal( 'returntoquery' ),
			'returntoanchor' => $request->getVal( 'returntoanchor' ),
		];

		if ( !$options['reset'] && $this->authAction !== $this->getDefaultAction( $this->subPage ) ) {
			$params['authAction'] = $this->getContinueAction( $this->authAction );
		}

		if ( $options['withToken'] ) {
			$params[$this->getTokenName()] = $this->getToken()->toString();
		}

		// Allow authentication extensions like CentralAuth to preserve their own
		// query params during and after the authentication process.
		$this->getHookRunner()->onAuthPreserveQueryParams(
			$params, [ 'request' => $request, 'reset' => $options['reset'] ]
		);

		return array_filter( $params, static fn ( $val ) => $val !== null );
	}

	/**
	 * Generates a HTMLForm descriptor array from a set of authentication requests.
	 * @stable to override
	 * @param AuthenticationRequest[] $requests
	 * @param string $action AuthManager action name (one of the AuthManager::ACTION_* constants)
	 * @return array[]
	 */
	protected function getAuthFormDescriptor( $requests, $action ) {
		$fieldInfo = AuthenticationRequest::mergeFieldInfo( $requests );
		$formDescriptor = $this->fieldInfoToFormDescriptor( $requests, $fieldInfo, $action );

		$this->addTabIndex( $formDescriptor );

		return $formDescriptor;
	}

	/**
	 * @stable to override
	 * @param AuthenticationRequest[] $requests
	 * @param string $action AuthManager action name (one of the AuthManager::ACTION_* constants)
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
		$form->setAction( $this->getFullTitle()->getFullURL( $this->getPreservedParams() ) );
		$form->addHiddenField( $this->getTokenName(), $this->getToken()->toString() );
		$form->addHiddenField( 'authAction', $this->authAction );
		$form->suppressDefaultSubmit( !$this->needsSubmitButton( $requests ) );

		return $form;
	}

	/**
	 * Display the form.
	 * @param false|Status|StatusValue $status A form submit status, as in HTMLForm::trySubmit()
	 */
	protected function displayForm( $status ) {
		if ( $status instanceof StatusValue ) {
			$status = Status::wrap( $status );
		}
		$form = $this->getAuthForm( $this->authRequests, $this->authAction );
		$form->prepareForm()->displayForm( $status );
	}

	/**
	 * Returns true if the form built from the given AuthenticationRequests needs a submit button.
	 * Providers using redirect flow (e.g. Google login) need their own submit buttons; if using
	 * one of those custom buttons is the only way to proceed, there is no point in displaying the
	 * default button which won't do anything useful.
	 * @stable to override
	 *
	 * @param AuthenticationRequest[] $requests An array of AuthenticationRequests from which the
	 *  form will be built
	 * @return bool
	 */
	protected function needsSubmitButton( array $requests ) {
		$customSubmitButtonPresent = false;

		// Secondary and preauth providers always need their data; they will not care what button
		// is used, so they can be ignored. So can OPTIONAL buttons createdby primary providers;
		// that's the point in being optional. Se we need to check whether all primary providers
		// have their own buttons and whether there is at least one button present.
		foreach ( $requests as $req ) {
			if ( $req->required === AuthenticationRequest::PRIMARY_REQUIRED ) {
				if ( $this->hasOwnSubmitButton( $req ) ) {
					$customSubmitButtonPresent = true;
				} else {
					return true;
				}
			}
		}
		return !$customSubmitButtonPresent;
	}

	/**
	 * Checks whether the given AuthenticationRequest has its own submit button.
	 * @param AuthenticationRequest $req
	 * @return bool
	 */
	protected function hasOwnSubmitButton( AuthenticationRequest $req ) {
		foreach ( $req->getFieldInfo() as $info ) {
			if ( $info['type'] === 'button' ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Adds a sequential tabindex starting from 1 to all form elements. This way the user can
	 * use the tab key to traverse the form without having to step through all links and such.
	 * @param array[] &$formDescriptor
	 */
	protected function addTabIndex( &$formDescriptor ) {
		$i = 1;
		foreach ( $formDescriptor as &$definition ) {
			$class = false;
			if ( array_key_exists( 'class', $definition ) ) {
				$class = $definition['class'];
			} elseif ( array_key_exists( 'type', $definition ) ) {
				$class = HTMLForm::$typeMappings[$definition['type']];
			}
			if ( $class !== HTMLInfoField::class ) {
				$definition['tabindex'] = $i;
				$i++;
			}
		}
	}

	/**
	 * Returns the CSRF token.
	 * @stable to override
	 * @return Token
	 */
	protected function getToken() {
		return $this->getRequest()->getSession()->getToken( 'AuthManagerSpecialPage:'
			. $this->getName() );
	}

	/**
	 * Returns the name of the CSRF token (under which it should be found in the POST or GET data).
	 * @stable to override
	 * @return string
	 */
	protected function getTokenName() {
		return 'wpAuthToken';
	}

	/**
	 * Turns a field info array into a form descriptor. Behavior can be modified by the
	 * AuthChangeFormFields hook.
	 * @param AuthenticationRequest[] $requests
	 * @param array $fieldInfo Field information, in the format used by
	 *   AuthenticationRequest::getFieldInfo()
	 * @param string $action One of the AuthManager::ACTION_* constants
	 * @return array A form descriptor that can be passed to HTMLForm
	 */
	protected function fieldInfoToFormDescriptor( array $requests, array $fieldInfo, $action ) {
		$formDescriptor = [];
		foreach ( $fieldInfo as $fieldName => $singleFieldInfo ) {
			$formDescriptor[$fieldName] = self::mapSingleFieldInfo( $singleFieldInfo, $fieldName );
		}

		$requestSnapshot = serialize( $requests );
		$this->onAuthChangeFormFields( $requests, $fieldInfo, $formDescriptor, $action );
		$this->getHookRunner()->onAuthChangeFormFields( $requests, $fieldInfo,
			$formDescriptor, $action );
		if ( $requestSnapshot !== serialize( $requests ) ) {
			LoggerFactory::getInstance( 'authentication' )->warning(
				'AuthChangeFormFields hook changed auth requests' );
		}

		// Process the special 'weight' property, which is a way for AuthChangeFormFields hook
		// subscribers (who only see one field at a time) to influence ordering.
		self::sortFormDescriptorFields( $formDescriptor );

		return $formDescriptor;
	}

	/**
	 * Maps an authentication field configuration for a single field (as returned by
	 * AuthenticationRequest::getFieldInfo()) to a HTMLForm field descriptor.
	 * @param array $singleFieldInfo
	 * @param string $fieldName
	 * @return array
	 */
	protected static function mapSingleFieldInfo( $singleFieldInfo, $fieldName ) {
		$type = self::mapFieldInfoTypeToFormDescriptorType( $singleFieldInfo['type'] );
		$descriptor = [
			'type' => $type,
			// Do not prefix input name with 'wp'. This is important for the redirect flow.
			'name' => $fieldName,
		];

		if ( $type === 'submit' && isset( $singleFieldInfo['label'] ) ) {
			$descriptor['default'] = $singleFieldInfo['label']->plain();
		} elseif ( $type !== 'submit' ) {
			$descriptor += array_filter( [
				// help-message is omitted as it is usually not really useful for a web interface
				'label-message' => self::getField( $singleFieldInfo, 'label' ),
			] );

			if ( isset( $singleFieldInfo['options'] ) ) {
				$descriptor['options'] = array_flip( array_map( static function ( $message ) {
					/** @var Message $message */
					return $message->parse();
				}, $singleFieldInfo['options'] ) );
			}

			if ( isset( $singleFieldInfo['value'] ) ) {
				$descriptor['default'] = $singleFieldInfo['value'];
			}

			if ( empty( $singleFieldInfo['optional'] ) ) {
				$descriptor['required'] = true;
			}
		}

		return $descriptor;
	}

	/**
	 * Sort the fields of a form descriptor by their 'weight' property. (Fields with higher weight
	 * are shown closer to the bottom; weight defaults to 0. Negative weight is allowed.)
	 * Keep order if weights are equal.
	 */
	protected static function sortFormDescriptorFields( array &$formDescriptor ) {
		$i = 0;
		foreach ( $formDescriptor as &$field ) {
			$field['__index'] = $i++;
		}
		unset( $field );
		uasort( $formDescriptor, static function ( $first, $second ) {
			return self::getField( $first, 'weight', 0 ) <=> self::getField( $second, 'weight', 0 )
				?: $first['__index'] <=> $second['__index'];
		} );
		foreach ( $formDescriptor as &$field ) {
			unset( $field['__index'] );
		}
	}

	/**
	 * Get an array value, or a default if it does not exist.
	 * @param array $array
	 * @param string $fieldName
	 * @param mixed|null $default
	 * @return mixed
	 */
	protected static function getField( array $array, $fieldName, $default = null ) {
		if ( array_key_exists( $fieldName, $array ) ) {
			return $array[$fieldName];
		} else {
			return $default;
		}
	}

	/**
	 * Maps AuthenticationRequest::getFieldInfo() types to HTMLForm types
	 *
	 * @param string $type
	 *
	 * @return string
	 */
	protected static function mapFieldInfoTypeToFormDescriptorType( $type ) {
		$map = [
			'string' => 'text',
			'password' => 'password',
			'select' => 'select',
			'checkbox' => 'check',
			'multiselect' => 'multiselect',
			'button' => 'submit',
			'hidden' => 'hidden',
			'null' => 'info',
		];
		if ( !array_key_exists( $type, $map ) ) {
			throw new InvalidArgumentException( 'invalid field type: ' . $type );
		}
		return $map[$type];
	}

	/**
	 * Apply defaults to a form descriptor, without creating non-existent fields.
	 *
	 * Overrides $formDescriptor fields with their $defaultFormDescriptor equivalent, but
	 * only if the field is defined in $fieldInfo, uses the special 'basefield' property to
	 * refer to a $fieldInfo field, or it is not a real field (e.g. help text). Applies some
	 * common-sense behaviors to ensure related fields are overridden in a consistent manner.
	 * @param array $fieldInfo
	 * @param array $formDescriptor
	 * @param array $defaultFormDescriptor
	 * @return array
	 */
	protected static function mergeDefaultFormDescriptor(
		array $fieldInfo, array $formDescriptor, array $defaultFormDescriptor
	) {
		// keep the ordering from $defaultFormDescriptor where there is no explicit weight
		foreach ( $defaultFormDescriptor as $fieldName => $defaultField ) {
			// remove everything that is not in the fieldinfo, is not marked as a supplemental field
			// to something in the fieldinfo, and is not an info field or a submit button
			if (
				!isset( $fieldInfo[$fieldName] )
				&& (
					!isset( $defaultField['baseField'] )
					|| !isset( $fieldInfo[$defaultField['baseField']] )
				)
				&& (
					!isset( $defaultField['type'] )
					|| !in_array( $defaultField['type'], [ 'submit', 'info' ], true )
				)
			) {
				$defaultFormDescriptor[$fieldName] = null;
				continue;
			}

			// default message labels should always take priority
			$requestField = $formDescriptor[$fieldName] ?? [];
			if (
				isset( $defaultField['label'] )
				|| isset( $defaultField['label-message'] )
				|| isset( $defaultField['label-raw'] )
			) {
				unset( $requestField['label'], $requestField['label-message'], $defaultField['label-raw'] );
			}

			$defaultFormDescriptor[$fieldName] += $requestField;
		}

		return array_filter( $defaultFormDescriptor + $formDescriptor );
	}
}

/** @deprecated class alias since 1.41 */
class_alias( AuthManagerSpecialPage::class, 'AuthManagerSpecialPage' );
