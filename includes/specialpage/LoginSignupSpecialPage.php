<?php
/**
 * Holds shared logic for login and account creation pages.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup SpecialPage
 */

use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthFrontend;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\PasswordAuthenticationRequest;
use MediaWiki\Auth\Throttler;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Session\SessionManager;
use Psr\Log\LogLevel;

/**
 * Holds shared logic for login and account creation pages.
 *
 * @ingroup SpecialPage
 */
abstract class LoginSignupSpecialPage extends AuthManagerSpecialPage {
	/**
	 * Valid error and warning messages
	 *
	 * Special:Userlogin can show an error or warning message on the form when
	 * coming from another page. This is done via the ?error= or ?warning= GET
	 * parameters.
	 *
	 * This array is the list of valid message keys. All other values will be
	 * ignored.
	 *
	 * @since 1.24
	 * @var string[]
	 */
	public static $validErrorMessages = [
		'exception-nologin-text',
		'watchlistanontext',
		'changeemail-no-info',
		'resetpass-no-info',
		'confirmemail_needlogin',
		'prefsnologintext2',
	];

	public $mAbortLoginErrorMsg;
	/**
	 * @var int How many seconds user is throttled for
	 * @since 1.27
	 */
	public $mThrottleWait = '?';

	/** @deprecated */
	protected $mUsername;
	/** @deprecated */
	protected $mPassword;
	/** @deprecated */
	protected $mRetype;
	protected $mReturnTo;
	protected $mPosted;
	protected $mAction;
	/** @deprecated */
	protected $mRemember;
	/** @deprecated */
	protected $mEmail;
	/** @deprecated */
	protected $mDomain;
	protected $mLanguage;
	protected $mReturnToQuery;
	protected $mToken;
	protected $mStickHTTPS;
	/** @deprecated */
	protected $mReason;
	/** @deprecated */
	protected $mRealName;
	protected $mFromHTTP;
	protected $mEntryError = '';
	protected $mEntryErrorType = 'error';

	/** @deprecated */
	protected $mTempPasswordUsed;
	protected $mLoaded = false;
	protected $mSecureLoginUrl;

	/** @var bool True if the user if creating an account for someone else. Flag used for internal
	 * communication, only set at the very end. */
	protected $proxyAccountCreation;
	/** @var User FIXME another flag for passing data. */
	protected $createdAccount;

	/** @var HTMLForm */
	protected $authForm;

	/** @var FakeAuthTemplate */
	protected $fakeTemplate;

	abstract protected function isSignup();

	/**
	 * @param bool $direct True if the action was successful just now; false if that happened
	 *    pre-redirection (so this handler was called already)
	 * @return void
	 */
	abstract protected function successfulAction( $direct = false );

	/**
	 * Logs to the authmanager-stats channel.
	 * @param bool $success
	 * @param string|null $status Error message key
	 */
	abstract protected function logAuthResult( $success, $status = null );

	public function __construct( $name ) {
		global $wgUseMediaWikiUIEverywhere;
		parent::__construct( $name );

		// Override UseMediaWikiEverywhere to true, to force login and create form to use mw ui
		$wgUseMediaWikiUIEverywhere = true;
	}

	/**
	 * Returns an array of all valid error messages.
	 *
	 * @return array
	 */
	public static function getValidErrorMessages() {
		static $messages = null;
		if ( !$messages ) {
			$messages = self::$validErrorMessages;
			Hooks::run( 'LoginFormValidErrorMessages', [ &$messages ] );
		}

		return $messages;
	}

	/**
	 * Load data from request.
	 * @private
	 * @param string $subPage Subpage of Special:Userlogin
	 */
	protected function load( $subPage ) {
		$request = $this->getRequest();

		if ( $this->mLoaded ) {
			return;
		}
		$this->mLoaded = true;

		$this->mPosted = $request->wasPosted();
		$this->mIsReturn = $subPage === 'return';
		$this->mAction = $request->getVal( 'action' );
		$this->mRemember = $request->getCheck( 'wpRemember' );
		$this->mFromHTTP = $request->getBool( 'fromhttp', false )
			|| $request->getBool( 'wpFromhttp', false );
		$this->mStickHTTPS = ( !$this->mFromHTTP && $request->getProtocol() === 'https' )
			|| $request->getBool( 'wpForceHttps', false );
		$this->mLanguage = $request->getText( 'uselang' );
		$this->mReturnTo = $request->getVal( 'returnto', '' );
		$this->mReturnToQuery = $request->getVal( 'returntoquery', '' );

		$this->loadAuth( $subPage );

		$this->mReason = $request->getText( 'wpReason' );
		$this->mToken = $request->getVal( $this->getTokenName() );

		// Show an error or warning passed on from a previous page
		$entryError = $this->msg( $request->getVal( 'error', '' ) );
		$entryWarning = $this->msg( $request->getVal( 'warning', '' ) );
		// bc: provide login link as a parameter for messages where the translation
		// was not updated
		$loginreqlink = Linker::linkKnown(
			$this->getPageTitle(),
			$this->msg( 'loginreqlink' )->escaped(),
			[],
			[
				'returnto' => $this->mReturnTo,
				'returntoquery' => $this->mReturnToQuery,
				'uselang' => $this->mLanguage,
				'fromhttp' => $this->mFromHTTP ? '1' : '0',
			]
		);

		// Only show valid error or warning messages.
		if ( $entryError->exists()
			&& in_array( $entryError->getKey(), self::getValidErrorMessages(), true )
		) {
			$this->mEntryErrorType = 'error';
			$this->mEntryError = $entryError->rawParams( $loginreqlink )->parse();

		} elseif ( $entryWarning->exists()
			&& in_array( $entryWarning->getKey(), self::getValidErrorMessages(), true )
		) {
			$this->mEntryErrorType = 'warning';
			$this->mEntryError = $entryWarning->rawParams( $loginreqlink )->parse();
		}

		# 1. When switching accounts, it sucks to get automatically logged out
		# 2. Do not return to PasswordReset after a successful password change
		#    but goto Wiki start page (Main_Page) instead ( bug 33997 )
		$returnToTitle = Title::newFromText( $this->mReturnTo );
		if ( is_object( $returnToTitle )
			&& ( $returnToTitle->isSpecial( 'Userlogout' )
				|| $returnToTitle->isSpecial( 'PasswordReset' ) )
		) {
			$this->mReturnTo = '';
			$this->mReturnToQuery = '';
		}
	}

	/**
	 * Returns URL query parameters which can be used to reload the page (or leave and return) while
	 * preserving all information that is necessary for authentication to continue.
	 * @param bool $withToken Include CSRF token
	 * @return array
	 */
	protected function getPreservedParams( $withToken = false ) {
		$params = parent::getPreservedParams( $withToken );
		$params += [
			'returnto' => $this->mReturnTo ?: null,
			'returntoquery' => $this->mReturnToQuery ?: null,
		];
		return $params;
	}

	/**
	 * @param string|null $subPage
	 */
	public function execute( $subPage ) {
		$authManager = AuthManager::singleton();
		$session = SessionManager::getGlobalSession();

		// Session data is used for various things in the authentication process, so we must make
		// sure a session cookie or some equivalent mechanism is set.
		$session->persist();

		$this->load( $subPage );
		$this->setHeaders();
		$this->checkPermissions();

		// Make sure it's possible to log in
		if ( !$this->isSignup() && !$session->canSetUser() ) {
			throw new ErrorPageError( 'cannotloginnow-title', 'cannotloginnow-text', [
					$session->getProvider()->describe( RequestContext::getMain()->getLanguage() )
				] );
		}

		/*
		 * In the case where the user is already logged in, and was redirected to
		 * the login form from a page that requires login, do not show the login
		 * page. The use case scenario for this is when a user opens a large number
		 * of tabs, is redirected to the login page on all of them, and then logs
		 * in on one, expecting all the others to work properly.
		 *
		 * However, do show the form if it was visited intentionally (no 'returnto'
		 * is present). People who often switch between several accounts have grown
		 * accustomed to this behavior.
		 *
		 * Also make an exception when force=<level> is set in the URL, which means the user must
		 * reauthenticate for security reasons.
		 */
		$securityLevel = $this->getRequest()->getText( 'force' );
		$mustReauthenticate = false;
		if ( $securityLevel ) {
			$mustReauthenticate =
				$authManager->securitySensitiveOperationStatus( $securityLevel )
				=== AuthManager::SEC_REAUTH;
		}

		if ( !$this->isSignup() && !$this->mPosted && !$mustReauthenticate &&
			 ( $this->mReturnTo !== '' || $this->mReturnToQuery !== '' ) &&
			 $this->getUser()->isLoggedIn()
		) {
			$this->successfulAction();
		}

		// If logging in and not on HTTPS, either redirect to it or offer a link.
		global $wgSecureLogin;
		if ( $this->getRequest()->getProtocol() !== 'https' ) {
			$title = $this->getFullTitle();
			$query = $this->getPreservedParams( false ) + [
					'title' => null,
					( $this->mEntryErrorType === 'error' ? 'error'
						: 'warning' ) => $this->mEntryError,
				] + $this->getRequest()->getQueryValues();
			$url = $title->getFullURL( $query, false, PROTO_HTTPS );
			if ( $wgSecureLogin && !$this->mFromHTTP &&
				 wfCanIPUseHTTPS( $this->getRequest()->getIP() )
			) {
				// Avoid infinite redirect
				$url = wfAppendQuery( $url, 'fromhttp=1' );
				$this->getOutput()->redirect( $url );
				// Since we only do this redir to change proto, always vary
				$this->getOutput()->addVaryHeader( 'X-Forwarded-Proto' );

				return;
			} else {
				// A wiki without HTTPS login support should set $wgServer to
				// http://somehost, in which case the secure URL generated
				// above won't actually start with https://
				if ( substr( $url, 0, 8 ) === 'https://' ) {
					$this->mSecureLoginUrl = $url;
				}
			}
		}

		if ( !$this->isActionAllowed( $this->authAction ) ) {
			// FIXME how do we explain this to the user? can we handle session loss better?
			$this->mainLoginForm( [ ], 'userlogin-cannot-' . $this->authAction ); // TODO i18n
			return;
		}

		$status = $this->trySubmit();

		if ( !$status || !$status->isGood() ) {
			$this->mainLoginForm( $this->authRequests, $status ? $status->getMessage() : '', 'error' );
			return;
		}

		/** @var AuthenticationResponse $response */
		$response = $status->getValue();

		$returnToUrl = $this->getPageTitle( 'return' )
			->getFullURL( $this->getPreservedParams( true ), false, PROTO_HTTPS );
		switch ( $response->status ) {
			case AuthenticationResponse::PASS:
				$this->proxyAccountCreation = $this->isSignup() && !$this->getUser()->isAnon();
				$this->createdAccount = User::newFromName( $response->loginRequest->username );

				if (
					!$this->proxyAccountCreation
					&& $response->loginRequest
					&& $authManager->canAuthenticateNow()
				) {
					// successful registration; log the user in instantly
					$response2 = $authManager->beginAuthentication( [ $response->loginRequest ],
						$returnToUrl );
					if ( !$response2->status === AuthenticationResponse::PASS ) {
						LoggerFactory::getInstance( 'login' )
							->error( 'Could not log in after account creation' );
						// TODO error message?
					}
				}
				$this->logAuthResult( true );
				$this->successfulAction( true );
				break;
			case AuthenticationResponse::FAIL:
				// fall through
			case AuthenticationResponse::RESTART:
				unset( $this->authForm );
				if ( $response->status === AuthenticationResponse::FAIL ) {
					$action = $this->getDefaultAction( $subPage );
					$messageType = 'error';
				} else {
					$action = $this->getContinueAction( $this->authAction );
					$messageType = 'warning';
				}
				$this->logAuthResult( false, $response->message ? $response->message->getKey() : '-' );
				$this->loadAuth( $subPage, $action );
				$this->mainLoginForm( $this->authRequests, $response->message, $messageType );
				break;
			case AuthenticationResponse::REDIRECT:
				unset( $this->authForm );
				$this->getOutput()->redirect( $response->redirectTarget );
				break;
			case AuthenticationResponse::UI:
				unset( $this->authForm );
				$this->authAction = $this->isSignup() ? AuthManager::ACTION_CREATE_CONTINUE
					: AuthManager::ACTION_LOGIN_CONTINUE;
				$this->authRequests = $response->neededRequests;
				$this->mainLoginForm( $response->neededRequests, $response->message, 'warning' );
				break;
			default:
				throw new LogicException( 'invalid AuthenticationResponse' );
		}
	}

	/**
	 * Show the success page.
	 *
	 * @param string $type Condition of return to; see `executeReturnTo`
	 * @param string|Message $title Page's title
	 * @param string $msgname
	 * @param string $injected_html
	 * @param StatusValue|null $extraMessages
	 */
	protected function showSuccessPage(
		$type, $title, $msgname, $injected_html, $extraMessages
	) {
		$out = $this->getOutput();
		$out->setPageTitle( $title );
		if ( $msgname ) {
			$out->addWikiMsg( $msgname, wfEscapeWikiText( $this->getUser()->getName() ) );
		}
		if ( $extraMessages ) {
			$extraMessages = Status::wrap( $extraMessages );
			$out->addWikiText( $extraMessages->getWikiText() );
		}

		$out->addHTML( $injected_html );

		$this->executeReturnTo( $type );
	}

	/**
	 * Add a "return to" link or redirect to it.
	 * Extensions can use this to reuse the "return to" logic after
	 * inject steps (such as redirection) into the login process.
	 *
	 * @param string $type One of the following:
	 *    - error: display a return to link ignoring $wgRedirectOnLogin
	 *    - signup: display a return to link using $wgRedirectOnLogin if needed
	 *    - success: display a return to link using $wgRedirectOnLogin if needed
	 *    - successredirect: send an HTTP redirect using $wgRedirectOnLogin if needed
	 * @param string $returnTo
	 * @param array|string $returnToQuery
	 * @param bool $stickHTTPs Keep redirect link on HTTPs
	 * @since 1.22
	 */
	public function showReturnToPage(
		$type, $returnTo = '', $returnToQuery = '', $stickHTTPs = false
	) {
		$this->mReturnTo = $returnTo;
		$this->mReturnToQuery = $returnToQuery;
		$this->mStickHTTPS = $stickHTTPs;
		$this->executeReturnTo( $type );
	}

	/**
	 * Add a "return to" link or redirect to it.
	 *
	 * @param string $type One of the following:
	 *    - error: display a return to link ignoring $wgRedirectOnLogin
	 *    - signup: display a return to link using $wgRedirectOnLogin if needed
	 *    - success: display a return to link using $wgRedirectOnLogin if needed
	 *    - successredirect: send an HTTP redirect using $wgRedirectOnLogin if needed
	 */
	protected function executeReturnTo( $type ) {
		global $wgRedirectOnLogin, $wgSecureLogin;

		if ( $type !== 'error' && $wgRedirectOnLogin !== null ) {
			$returnTo = $wgRedirectOnLogin;
			$returnToQuery = [];
		} else {
			$returnTo = $this->mReturnTo;
			$returnToQuery = wfCgiToArray( $this->mReturnToQuery );
		}

		// Allow modification of redirect behavior
		Hooks::run( 'PostLoginRedirect', [ &$returnTo, &$returnToQuery, &$type ] );

		$returnToTitle = Title::newFromText( $returnTo );
		if ( !$returnToTitle ) {
			$returnToTitle = Title::newMainPage();
		}

		if ( $wgSecureLogin && !$this->mStickHTTPS ) {
			$options = [ 'http' ];
			$proto = PROTO_HTTP;
		} elseif ( $wgSecureLogin ) {
			$options = [ 'https' ];
			$proto = PROTO_HTTPS;
		} else {
			$options = [];
			$proto = PROTO_RELATIVE;
		}

		if ( $type === 'successredirect' ) {
			$redirectUrl = $returnToTitle->getFullURL( $returnToQuery, false, $proto );
			$this->getOutput()->redirect( $redirectUrl );
		} else {
			$this->getOutput()->addReturnTo( $returnToTitle, $returnToQuery, null, $options );
		}
	}

	/**
	 * Replace some globals to make sure the fact that the user has just been logged in is
	 * reflected in the current request.
	 * @param User $user
	 */
	protected function setUserForCurrentRequest( User $user ) {
		global $wgUser, $wgLang;

		$context = RequestContext::getMain();
		$localContext = $this->getContext();
		if ( $context !== $localContext ) {
			// remove AuthManagerSpecialPage context hack
			$this->setContext( $context );
		}

		$wgUser = $user;
		$context->setUser( $user );

		$code = $this->getRequest()->getVal( 'uselang', $user->getOption( 'language' ) );
		$userLang = Language::factory( $code );
		$wgLang = $userLang;
		$context->setLanguage( $userLang );
	}

	/**
	 * @param AuthenticationRequest[] $requests A list of AuthorizationRequest objects,
	 *   used to generate the form fields. An empty array means a fatal error
	 *   (authentication cannot continue).
	 * @param string|Message $msg
	 * @param string $msgtype
	 * @throws ErrorPageError
	 * @throws Exception
	 * @throws FatalError
	 * @throws MWException
	 * @throws PermissionsError
	 * @throws ReadOnlyError
	 * @private
	 */
	protected function mainLoginForm( array $requests, $msg = '', $msgtype = 'error' ) {
		$titleObj = $this->getPageTitle();
		$user = $this->getUser();
		$out = $this->getOutput();

		if ( !$requests ) {
			$this->authAction = $this->getDefaultAction( $this->subPage );
			$this->authForm = null;
			$requests = AuthManager::singleton()->getAuthenticationRequests( $this->authAction, $user );
		}

		if ( $this->isSignup() ) {
			// Block signup here if in readonly. Keeps user from
			// going through the process (filling out data, etc)
			// and being informed later.
			// FIXME should this be part of AuthManager::canCreateAccount()?
			$permErrors = $titleObj->getUserPermissionsErrors( 'createaccount', $user, true );
			if ( count( $permErrors ) ) {
				throw new PermissionsError( 'createaccount', $permErrors );
			} elseif ( $user->isBlockedFromCreateAccount() ) {
				$this->userBlockedMessage( $user->isBlockedFromCreateAccount() );
				return;
			} elseif ( wfReadOnly() ) {
				throw new ReadOnlyError;
			}
		}
		// TODO handle empty $requests - no form, just an error message

		// Generic styles and scripts for both login and signup form
		$out->addModuleStyles( [
			'mediawiki.ui',
			'mediawiki.ui.button',
			'mediawiki.ui.checkbox',
			'mediawiki.ui.input',
			'mediawiki.special.userlogin.common.styles'
		] );
		if ( $this->isSignup() ) {
			// XXX hack pending RL or JS parse() support for complex content messages T27349
			$out->addJsConfigVars( 'wgCreateacctImgcaptchaHelp',
				$this->msg( 'createacct-imgcaptcha-help' )->parse() );

			// Additional styles and scripts for signup form
			$out->addModules( [
				'mediawiki.special.userlogin.signup.js'
			] );
			$out->addModuleStyles( [
				'mediawiki.special.userlogin.signup.styles'
			] );
		} else {
			// Additional styles for login form
			$out->addModuleStyles( [
				'mediawiki.special.userlogin.login.styles'
			] );
		}
		$out->disallowUserJs(); // just in case...

		$form = $this->getAuthForm( $requests, $this->authAction, $msg, $msgtype );
		$form->prepareForm();
		$formHtml = $form->getHTML( $msg ? Status::newFatal( $msg ) : false );

		$out->addHTML( $this->getPageHtml( $formHtml ) );
	}

	/**
	 * Add page elements which are outside the form.
	 * FIXME this should probably be a template, but use a sane language (handlebars?)
	 * @param string $formHtml
	 * @return string
	 */
	protected function getPageHtml( $formHtml ) {
		global $wgLoginLanguageSelector;

		$loginPrompt = $this->isSignup() ? '' : Html::rawElement( 'div',
			[ 'id' => 'userloginprompt' ], $this->msg( 'loginprompt' )->parseAsBlock() );
		$languageLinks = $wgLoginLanguageSelector ? $this->makeLanguageSelector() : '';
		$signupStartMsg = $this->msg( 'signupstart' );
		$signupStart = ( $this->isSignup() && !$signupStartMsg->isDisabled() )
			? Html::rawElement( 'div', [ 'id' => 'signupstart' ], $signupStartMsg->parseAsBlock() ) : '';
		if ( $languageLinks ) {
			$languageLinks = Html::rawElement( 'div', [ 'id' => 'languagelinks' ],
				Html::rawElement( 'p', [], $languageLinks )
			);
		}

		$benefitsContainer = '';
		if ( $this->isSignup() ) {
			// messages used:
			// createacct-benefit-icon1 createacct-benefit-head1 createacct-benefit-body1
			// createacct-benefit-icon2 createacct-benefit-head2 createacct-benefit-body2
			// createacct-benefit-icon3 createacct-benefit-head3 createacct-benefit-body3
			$benefitCount = 3;
			$benefitList = '';
			for ( $benefitIdx = 1; $benefitIdx <= $benefitCount; $benefitIdx++ ) {
				$headUnescaped = $this->msg( "createacct-benefit-head$benefitIdx" )->text();
				$iconClass = $this->msg( "createacct-benefit-icon$benefitIdx" )->escaped();
				$benefitList .= Html::rawElement( 'div', [ 'class' => "mw-number-text $iconClass"],
					Html::rawElement( 'h3', [],
						$this->msg( "createacct-benefit-head$benefitIdx" )->escaped()
					)
					. Html::rawElement( 'p', [],
						$this->msg( "createacct-benefit-body$benefitIdx" )->params( $headUnescaped )->escaped()
					)
				);
			}
			$benefitsContainer = Html::rawElement( 'div', [ 'class' => 'mw-createacct-benefits-container' ],
				Html::rawElement( 'h2', [], $this->msg( 'createacct-benefit-heading' )->escaped() )
				. Html::rawElement( 'div', [ 'class' => 'mw-createacct-benefits-list' ],
					$benefitList
				)
			);
		}

		$html = Html::rawElement( 'div', [ 'class' => 'mw-ui-container' ],
			$loginPrompt
			. $languageLinks
			. $signupStart
			. Html::rawElement( 'div', [ 'id' => 'userloginForm' ],
				$formHtml
			)
			. $benefitsContainer
		);

		return $html;
	}

	/**
	 * Generates a form from the given request.
	 * @param AuthenticationRequest[] $requests
	 * @param string $action AuthManager action name
	 * @param string|Message $msg
	 * @param string $msgType
	 * @return HTMLForm
	 */
	protected function getAuthForm( array $requests, $action, $msg = '', $msgType = 'error' ) {
		global $wgSecureLogin, $wgPasswordResetRoutes, $wgEnableEmail, $wgLoginLanguageSelector;
		// FIXME merge this with parent

		if ( isset( $this->authForm ) ) {
			return $this->authForm;
		}

		$usingHTTPS = $this->getRequest()->getProtocol() === 'https';

		// get basic form description from the auth logic
		$fieldInfo = AuthenticationRequest::mergeFieldInfo( $requests );
		$fakeTemplate = $this->getFakeTemplate( $this, $msg, $msgType );
		$this->fakeTemplate = $fakeTemplate; // FIXME there should be a saner way to pass this to the hook
		// this will call onAuthChangeFormFields()
		$formDescriptor = AuthFrontend::fieldInfoToFormDescriptor( $fieldInfo, $this->authAction );
		$this->postProcessFormDescriptor( $formDescriptor );

		$context = $this->getContext();
		if ( $context->getRequest() !== $this->getRequest() ) {
			// We have overridden the request, need to make sure the form uses that too.
			$context = new DerivativeContext( $this->getContext() );
			$context->setRequest( $this->getRequest() );
		}
		$form = HTMLForm::factory( 'vform', $formDescriptor, $context );

		$form->addHiddenField( 'authAction', $this->authAction );
		if ( $wgLoginLanguageSelector ) {
			$form->addHiddenField( 'uselang', $this->mLanguage );
		}
		$form->addHiddenField( $this->getTokenName(), $this->getToken()->toString() );
		if ( $wgSecureLogin === true ) {
			// If using HTTPS coming from HTTP, then the 'fromhttp' parameter must be preserved
			if ( !$this->isSignup() ) {
				$form->addHiddenField( 'wpForceHttps', (int)$this->mStickHTTPS );
				$form->addHiddenField( 'wpFromhttp', $usingHTTPS );
			}
		}

		// set properties of the form itself
		$form->setAction( $this->getPageTitle()->getLocalURL( $this->getReturnToQueryStringFragment() ) );
		$form->setName( 'userlogin' . ( $this->isSignup() ? '2' : '' ) );
		if ( $this->isSignup() ) {
			$form->setId( 'userlogin2' );
		}

		// add pre/post text
		// header used by ConfirmEdit, CondfirmAccount, Persona, WikimediaIncubator, SemanticSignup
		// should be above the error message but HTMLForm doesn't support that
		$form->addHeaderText( $fakeTemplate->html( 'header' ) );

		// FIXME the old form used this for error/warning messages which does not play well with
		// HTMLForm (maybe it could with a subclass?); for now only display it for signups
		// (where the JS username validation needs it) and alway empty
		if ( $this->isSignup() ) {
			// used by the mediawiki.special.userlogin.signup.js module
			$statusAreaAttribs = [ 'id' => 'mw-createacct-status-area' ];
			// $statusAreaAttribs += $msg ? [ 'class' => "{$msgType}box" ] : [ 'style' => 'display: none;' ];
			$form->addHeaderText( Html::element( 'div', $statusAreaAttribs ) );
		}

		$form->addHeaderText( $fakeTemplate->html( 'formheader' ) ); // header used by MobileFrontend
		if ( $this->isSignup() ) {
			// Use signupend-https for HTTPS requests if it's not blank, signupend otherwise
			$signupendMsg = $this->msg( 'signupend' );
			$signupendHttpsMsg = $this->msg( 'signupend-https' );
			if ( !$signupendMsg->isDisabled() ) {
				$signupendText = ( $usingHTTPS && !$signupendHttpsMsg->isBlank() )
					? $signupendHttpsMsg ->parse() : $signupendMsg->parse();
				$form->addPostText( Html::rawElement( 'div', [ 'id' => 'signupend' ], $signupendText ) );
			}
		}
		if ( !$this->isSignup() && $this->getUser()->isLoggedIn() ) {
			$form->addHeaderText( Html::rawElement( 'div', [ 'class' => 'warningbox' ],
				$this->msg( 'userlogin-loggedin' )->params( $this->getUser()->getName() )->parse() ) );
		}
		if ( !$this->isSignup() ) {
			$allowedChangeRequests = AuthManager::singleton()->getAuthenticationRequests(
				AuthManager::ACTION_CHANGE );
			$allowPasswordChange = (bool)array_filter( $allowedChangeRequests, function ( $req ) {
				return $req instanceof PasswordAuthenticationRequest;
			} );
			if (
				$wgEnableEmail && $allowPasswordChange && is_array( $wgPasswordResetRoutes )
				&& in_array( true, array_values( $wgPasswordResetRoutes ), true )
			) {
				$form->addFooterText( Html::rawElement(
					'div',
					[ 'class' => 'mw-ui-vform-field mw-form-related-link-container' ],
					Linker::link(
						SpecialPage::getTitleFor( 'PasswordReset' ),
						$this->msg( 'userlogin-resetpassword-link' )->escaped()
					)
				) );
			}

			// Don't show a "create account" link if the user can't.
			if ( $this->showCreateAccountLink() ) {
				// link to the other action
				$linkq = $this->isSignup() ? 'type=login' : 'type=signup';
				$linkq .= $this->getReturnToQueryStringFragment();
				// Pass any language selection on to the mode switch link
				if ( $wgLoginLanguageSelector && $this->mLanguage ) {
					$linkq .= '&uselang=' . $this->mLanguage;
				}
				$createOrLoginHref = $this->getPageTitle()->getLocalURL( $linkq );

				if ( $this->getUser()->isLoggedIn() ) {
					$createOrLoginHtml = Html::rawElement( 'div',
						[ 'class' => 'mw-ui-vform-field mw-form-related-link-container' ],
						Html::element( 'a',
							[
								'id' => 'mw-createaccount-join',
								'href' => $createOrLoginHref,
								// put right after all auth inputs in the tab order
								'tabindex' => 100,
							],
							$this->msg( 'userlogin-createanother' )->escaped()
						)
					);
				} else {
					$createOrLoginHtml = Html::rawElement( 'div',
						[ 'id' => 'mw-createaccount-cta',
							'class' => 'mw-ui-vform-field mw-form-related-link-container' ],
						$this->msg( 'userlogin-noaccount' )->escaped()
						. Html::element( 'a',
							[
								'id' => 'mw-createaccount-join',
								'href' => $createOrLoginHref,
								'class' => 'mw-ui-button mw-ui-progressive',
								'tabindex' => 100,
							],
							$this->msg( 'userlogin-joinproject' )->escaped()
						)
					);
				}
				$form->addFooterText( $createOrLoginHtml );
			}
		}

		$form->suppressDefaultSubmit();

		$this->authForm = $form;

		return $form;
	}

	/**
	 * Temporary B/C method to handle extensions using the UserLoginForm/UserCreateForm hooks.
	 * @param object $data
	 * @param string|Message $msg
	 * @param string $msgType
	 * @return FakeAuthTemplate
	 */
	protected function getFakeTemplate( $data, $msg, $msgType ) {
		global $wgAuth, $wgEnableEmail, $wgHiddenPrefs, $wgEmailConfirmToEdit, $wgEnableUserEmail,
			   $wgCookieExpiration, $wgExtendedLoginCookieExpiration, $wgSecureLogin,
			   $wgLoginLanguageSelector, $wgPasswordResetRoutes;

		// Preserves a bunch of logic from the old code that was rewritten in getAuthForm().
		// There is no code reuse to make this easier to remove .
		// If an extension tries to change any of these values, they are out of luck - we only
		// actually use the domain/usedomain/domainnames, extraInput and extrafields keys.

		$titleObj = $this->getPageTitle();
		$user = $this->getUser();
		$template = new FakeAuthTemplate();

		// Pre-fill username (if not creating an account, bug 44775).
		if ( $data->mUsername == '' && $this->isSignup() ) {
			if ( $user->isLoggedIn() ) {
				$data->mUsername = $user->getName();
			} else {
				$data->mUsername = $this->getRequest()->getSession()->suggestLoginUsername();
			}
		}

		if ( $this->isSignup() ) {
			// Must match number of benefits defined in messages
			$template->set( 'benefitCount', 3 );

			$q = 'action=submitlogin&type=signup';
			$linkq = 'type=login';
		} else {
			$q = 'action=submitlogin&type=login';
			$linkq = 'type=signup';
		}

		if ( $data->mReturnTo !== '' ) {
			$returnto = '&returnto=' . wfUrlencode( $data->mReturnTo );
			if ( $data->mReturnToQuery !== '' ) {
				$returnto .= '&returntoquery=' .
							 wfUrlencode( $data->mReturnToQuery );
			}
			$q .= $returnto;
			$linkq .= $returnto;
		}

		# Don't show a "create account" link if the user can't.
		if ( $this->showCreateAccountLink() ) {
			# Pass any language selection on to the mode switch link
			if ( $wgLoginLanguageSelector && $data->mLanguage ) {
				$linkq .= '&uselang=' . $data->mLanguage;
			}
			// Supply URL, login template creates the button.
			$template->set( 'createOrLoginHref', $titleObj->getLocalURL( $linkq ) );
		} else {
			$template->set( 'link', '' );
		}

		$resetLink = $this->isSignup()
			? null
			: is_array( $wgPasswordResetRoutes )
			  && in_array( true, array_values( $wgPasswordResetRoutes ), true );

		$template->set( 'header', '' );
		$template->set( 'formheader', '' );
		$template->set( 'skin', $this->getSkin() );

		$template->set( 'name', $data->mUsername );
		$template->set( 'password', $data->mPassword );
		$template->set( 'retype', $data->mRetype );
		$template->set( 'createemailset', false ); // no easy way to get that from AuthManager
		$template->set( 'email', $data->mEmail );
		$template->set( 'realname', $data->mRealName );
		$template->set( 'domain', $data->mDomain );
		$template->set( 'reason', $data->mReason );
		$template->set( 'remember', $data->mRemember );

		$template->set( 'action', $titleObj->getLocalURL( $q ) );
		$template->set( 'message', $msg );
		$template->set( 'messagetype', $msgType );
		$template->set( 'createemail', $wgEnableEmail && $user->isLoggedIn() );
		$template->set( 'userealname', !in_array( 'realname', $wgHiddenPrefs, true ) );
		$template->set( 'useemail', $wgEnableEmail );
		$template->set( 'emailrequired', $wgEmailConfirmToEdit );
		$template->set( 'emailothers', $wgEnableUserEmail );
		$template->set( 'canreset', $wgAuth->allowPasswordChange() );
		$template->set( 'resetlink', $resetLink );
		$template->set( 'canremember', $wgExtendedLoginCookieExpiration === null ?
			( $wgCookieExpiration > 0 ) :
			( $wgExtendedLoginCookieExpiration > 0 ) );
		$template->set( 'usereason', $user->isLoggedIn() );
		$template->set( 'cansecurelogin', ( $wgSecureLogin === true ) );
		$template->set( 'stickhttps', (int)$this->mStickHTTPS );
		$template->set( 'loggedin', $user->isLoggedIn() );
		$template->set( 'loggedinuser', $user->getName() );
		$template->set( 'token', $this->getToken()->toString() );

		$action = $this->isSignup() ? 'signup' : 'login';
		$wgAuth->modifyUITemplate( $template, $action );

		$oldTemplate = $template;
		$hookName = $this->isSignup() ? 'UserCreateForm' : 'UserLoginForm';
		Hooks::run( $hookName, [ &$template ] );
		if ( $oldTemplate !== $template ) {
			wfDeprecated( "reference in $hookName hook", '1.27' );
		}

		return $template;

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
		$coreFieldDescriptors = $this->getFieldDefinitions( $this->fakeTemplate );

		// keep the ordering from getCoreFieldDescriptors() where there is no explicit weight
		foreach ( $coreFieldDescriptors as $fieldName => $coreField ) {
			$requestField = isset( $formDescriptor[$fieldName] ) ?
				$formDescriptor[$fieldName] : [];

			// remove everything that is not in the fieldinfo, is not marked as a supplemental field
			// to something in the fieldinfo, and is not a generic or B/C field or a submit button
			$specialFields = [ 'extraInput', 'extrafields', 'linkcontainer' ];
			if (
				!isset( $fieldInfo[$fieldName] )
				&& (
					!isset( $coreField['baseField'] )
					|| !isset( $fieldInfo[$coreField['baseField']] )
				) && !in_array( $fieldName, $specialFields, true )
				&& $coreField['type'] !== 'submit'
			) {
				$coreFieldDescriptors[$fieldName] = null;
				continue;
			}

			// core message labels should always take priority
			if ( isset( $coreField['label'] ) || isset( $coreField['label-message'] ) ) {
				unset( $requestField['label'], $requestField['label-message'] );
			}

			$coreFieldDescriptors[$fieldName] += $requestField;
		}

		$formDescriptor = array_filter( $coreFieldDescriptors + $formDescriptor );
		return true;
	}

	/**
	 * Create a HTMLForm descriptor for the core login fields.
	 * @param FakeAuthTemplate $template B/C data (not used but needed by getBCFieldDefinitions)
	 * @return array
	 */
	protected function getFieldDefinitions( $template ) {
		global $wgEmailConfirmToEdit, $wgCookieExpiration, $wgExtendedLoginCookieExpiration,
			$wgHiddenPrefs, $wgEnableEmail;

		$isLoggedIn = $this->getUser()->isLoggedIn();
		$continuePart = $this->isContinued() ? 'continue-' : '';
		$anotherPart = $isLoggedIn ? 'another-' : '';
		$expirationDays = ceil( $wgCookieExpiration / ( 3600 * 24 ) );
		$secureLoginLink = '';
		if ( $this->mSecureLoginUrl ) {
			$secureLoginLink = Html::element( 'a', [
				'href' => $this->mSecureLoginUrl,
				'class' => 'mw-ui-flush-right mw-secure',
			], $this->msg( 'userlogin-signwithsecure' )->text() );
		}

		if ( $this->isSignup() ) {
			$fieldDefinitions = [
				'username' => [
					'label-message' => 'userlogin-yourname',
					'help-message' => 'createacct-helpusername', // FIXME help-message does not match old formatting
					'id' => 'wpName2',
					'placeholder-message' => $isLoggedIn ? 'createacct-another-username-ph'
						: 'userlogin-yourname-ph',
				],
				'mailpassword' => [
					// create account without providing password, a temporary one will be mailed
					'type' => 'check',
					'label-message' => 'createaccountmail',
					'name' => 'wpCreateaccountMail',
					'id' => 'wpCreateaccountMail',
				],
				'password' => [
					'id' => 'wpPassword2',
					'hide-if' => [ '===', 'wpCreateaccountMail', '1' ],
				],
				'domain' => [],
				'retype' => [
					'baseField' => 'password',
					'type' => 'password',
					'label-message' => 'createacct-yourpasswordagain',
					'id' => 'wpRetype',
					'cssclass' => 'loginPassword',
					'size' => 20,
					'validation-callback' => function ( $value, $alldata ) {
						if ( empty( $alldata['mailpassword'] ) && !empty( $alldata['password'] ) ) {
							if ( !$value ) {
								return $this->msg( 'htmlform-required' );
							} elseif ( $value !== $alldata['password'] ) {
								return $this->msg( 'badretype' );
							}
						}
						return true;
					},
					'hide-if' => [ '===', 'wpCreateaccountMail', '1' ],
					'placeholder-message' => 'createacct-yourpasswordagain-ph',
				],
				'email' => [
					'type' => 'email',
					'label-message' => $wgEmailConfirmToEdit ? 'createacct-emailrequired'
						: 'createacct-emailoptional',
					'id' => 'wpEmail',
					'cssclass' => 'loginText',
					'size' => '20',
					// FIXME will break non-standard providers
					'required' => $wgEmailConfirmToEdit,
					'validation-callback' => function ( $value, $alldata ) {
						global $wgEmailConfirmToEdit;

						// AuthManager will check most of these, but that will make the auth
						// session fail and this won't, so nicer to do it this way
						if ( !$value && $wgEmailConfirmToEdit ) {
							// no point in allowing registration without email when email is
							// required to edit
							return $this->msg( 'noemailtitle' );
						} elseif ( !$value && !empty( $alldata['mailpassword'] ) ) {
							// cannot send password via email when there is no email address
							return $this->msg( 'noemailcreate' );
						} elseif ( $value && !Sanitizer::validateEmail( $value ) ) {
							return $this->msg( 'invalidemailaddress' );
						}
						return true;
					},
					'placeholder-message' => 'createacct-' . $anotherPart . 'email-ph',
				],
				'realname' => [
					'type' => 'text',
					'help-message' => $isLoggedIn ? 'createacct-another-realname-tip'
						: 'prefs-help-realname',
					'label-message' => 'createacct-realname',
					'cssclass' => 'loginText',
					'size' => 20,
					'id' => 'wpRealName',
				],
				'reason' => [
					// comment for the user creation log
					'type' => 'text',
					'label-message' => 'createacct-reason',
					'cssclass' => 'loginText',
					'id' => 'wpReason',
					'size' => '20',
					'placeholder-message' => 'createacct-reason-ph',
				],
				'extrainput' => [], // placeholder for fields coming from the template
				'createaccount' => [
					// submit button
					'type' => 'submit',
					'default' => $this->msg( 'createacct-' . $anotherPart . $continuePart .
						'submit' )->text(),
					'name' => 'wpCreateaccount',
					'id' => 'wpCreateaccount',
					'weight' => 100,
				],
			];
		} else {
			$fieldDefinitions = [
				'username' => [
					'label' => $this->msg( 'userlogin-yourname' ) . $secureLoginLink,
					'id' => 'wpName1',
					'placeholder-message' => 'userlogin-yourname-ph',
				],
				'password' => [
					'id' => 'wpPassword1',
				],
				'domain' => [],
				'extrainput' => [],
				'remember' => [
					// option for saving the user token to a cookie
					'type' => 'check',
					'label-message' => $this->msg( 'userlogin-remembermypassword' )
						->numParams( $expirationDays ),
					'id' => 'wpRemember',
				],
				'loginattempt' => [
					// submit button
					'type' => 'submit',
					'default' => $this->msg( 'pt-login-' . $continuePart . 'button' )->text(),
					'id' => 'wpLoginAttempt',
					'weight' => 100,
				],
				'linkcontainer' => [
					// help link
					'type' => 'info',
					'cssclass' => 'mw-form-related-link-container',
					'id' => 'mw-userlogin-help',
					'raw' => true,
					'default' => Html::element( 'a', [
						'href' => Skin::makeInternalOrExternalUrl( wfMessage( 'helplogin-url' )
							->inContentLanguage()
							->text() ),
					], $this->msg( 'userlogin-helplink2' )->text() ),
					'weight' => 200,
				],
			];
		}
		$fieldDefinitions['username'] += [
			'type' => 'text',
			'name' => 'wpName',
			'cssclass' => 'loginText',
			'size' => 20,
			// 'required' => true,
		];
		$fieldDefinitions['password'] += [
			'type' => 'password',
			// 'label-message' => 'userlogin-yourpassword', // would override the changepassword label
			'name' => 'wpPassword',
			'cssclass' => 'loginPassword',
			'size' => 20,
			// 'required' => true,
			'placeholder-message' => 'createacct-yourpassword-ph',
		];

		// FIXME this is provider business
		$canRemember = $wgExtendedLoginCookieExpiration === null ? ( $wgCookieExpiration > 0 )
			: ( $wgExtendedLoginCookieExpiration > 0 );
		$createEmail = $wgEnableEmail && $isLoggedIn;
		$useRealName = !in_array( 'realname', $wgHiddenPrefs, true );
		if ( !$canRemember ) {
			unset( $fieldDefinitions['remember'] );
		}
		if ( !$createEmail ) {
			unset( $fieldDefinitions['mailpassword'] );
		}
		if ( !$isLoggedIn ) {
			unset( $fieldDefinitions['reason'] );
		}
		if ( !$useRealName ) {
			unset( $fieldDefinitions['realname'] );
		}
		if ( !$wgEnableEmail ) {
			unset( $fieldDefinitions['email'] );
		}

		$fieldDefinitions = $this->getBCFieldDefinitions( $fieldDefinitions, $template );
		$fieldDefinitions = array_filter( $fieldDefinitions );

		return $fieldDefinitions;
	}

	/**
	 * Adds fields provided via the deprecated UserLoginForm / UserCreateForm hooks
	 * @param $fieldDefinitions array
	 * @param FakeAuthTemplate $template
	 * @return array
	 */
	protected function getBCFieldDefinitions( $fieldDefinitions, $template ) {
		if ( $template->get( 'usedomain', false ) ) {
			// TODO probably should be translated to the new domain notation in AuthManager
			$fieldDefinitions['domain'] = [
				'type' => 'select',
				'label-message' => 'yourdomainname',
				'options' => array_combine( $template->get( 'domainnames', [] ),
					$template->get( 'domainnames', [] ) ),
				'default' => $template->get( 'domain', '' ),
				'name' => 'wpDomain',
				// FIXME id => 'mw-user-domain-section' on the parent div
			];
		}

		// poor man's associative array_splice
		$extraInputPos = array_search( 'extrainput', array_keys( $fieldDefinitions ), true );
		$fieldDefinitions = array_slice( $fieldDefinitions, 0, $extraInputPos, true )
							+ $template->getExtraInputDefinitions()
							+ array_slice( $fieldDefinitions, $extraInputPos + 1, null, true );

		return $fieldDefinitions;
	}

	/**
	 * Check if a session cookie is present.
	 *
	 * This will not pick up a cookie set during _this_ request, but is meant
	 * to ensure that the client is returning the cookie which was set on a
	 * previous pass through the system.
	 *
	 * @return bool
	 */
	protected function hasSessionCookie() {
		global $wgDisableCookieCheck, $wgInitialSessionId;

		return $wgDisableCookieCheck || (
			$wgInitialSessionId &&
			$this->getRequest()->getSession()->getId() === (string)$wgInitialSessionId
		);
	}

	/**
	 * Returns a string that can be appended to the URL (without encoding) to preserve the
	 * return target.
	 */
	protected function getReturnToQueryStringFragment() {
		$returnto = '';
		if ( $this->mReturnTo !== '' ) {
			$returnto = '&returnto=' . wfUrlencode( $this->mReturnTo );
			if ( $this->mReturnToQuery !== '' ) {
				$returnto .= '&returntoquery=' . wfUrlencode( $this->mReturnToQuery );
			}
		}
		return $returnto;
	}

	/**
	 * Whether the login/create account form should display a link to the
	 * other form (in addition to whatever the skin provides).
	 * @return bool
	 */
	private function showCreateAccountLink() {
		if ( $this->isSignup() ) {
			return true;
		} elseif ( $this->getUser()->isAllowed( 'createaccount' ) ) {
			return true;
		} else {
			return false;
		}
	}

	protected function getTokenName() {
		return $this->isSignup() ? 'wpCreateaccountToken' : 'wpLoginToken';
	}

	/**
	 * Produce a bar of links which allow the user to select another language
	 * during login/registration but retain "returnto"
	 *
	 * @return string
	 */
	protected function makeLanguageSelector() {
		$msg = $this->msg( 'loginlanguagelinks' )->inContentLanguage();
		if ( $msg->isBlank() ) {
			return '';
		}
		$langs = explode( "\n", $msg->text() );
		$links = [];
		foreach ( $langs as $lang ) {
			$lang = trim( $lang, '* ' );
			$parts = explode( '|', $lang );
			if ( count( $parts ) >= 2 ) {
				$links[] = $this->makeLanguageSelectorLink( $parts[0], trim( $parts[1] ) );
			}
		}

		return count( $links ) > 0 ? $this->msg( 'loginlanguagelabel' )->rawParams(
			$this->getLanguage()->pipeList( $links ) )->escaped() : '';
	}

	/**
	 * Create a language selector link for a particular language
	 * Links back to this page preserving type and returnto
	 *
	 * @param string $text Link text
	 * @param string $lang Language code
	 * @return string
	 */
	protected function makeLanguageSelectorLink( $text, $lang ) {
		if ( $this->getLanguage()->getCode() == $lang ) {
			// no link for currently used language
			return htmlspecialchars( $text );
		}
		$query = [ 'uselang' => $lang ];
		if ( $this->isSignup() ) {
			$query['type'] = 'signup';
		}
		if ( $this->mReturnTo !== '' ) {
			$query['returnto'] = $this->mReturnTo;
			$query['returntoquery'] = $this->mReturnToQuery;
		}

		$attr = [];
		$targetLanguage = Language::factory( $lang );
		$attr['lang'] = $attr['hreflang'] = $targetLanguage->getHtmlCode();

		return Linker::linkKnown(
			$this->getPageTitle(),
			htmlspecialchars( $text ),
			$attr,
			$query
		);
	}

	protected function getGroupName() {
		return 'login';
	}

	/**
	 * @param array $formDescriptor
	 */
	protected function postProcessFormDescriptor( &$formDescriptor ) {
		// Pre-fill username (if not creating an account, T46775).
		if (
			isset( $formDescriptor['username'] ) &&
			!isset( $formDescriptor['username']['default'] ) &&
			!$this->isSignup()
		) {
			$user = $this->getUser();
			if ( $user->isLoggedIn() ) {
				$formDescriptor['username']['default'] = $user->getName();
			} else {
				$formDescriptor['username']['default'] =
					$this->getRequest()->getSession()->suggestLoginUsername();
			}
		}

		// don't show a submit button if there is nothing to submit (i.e. the only form content
		// is other submit buttons, for redirect flows)
		if ( !$this->needsSubmitButton( $formDescriptor ) ) {
			unset( $formDescriptor['createaccount'], $formDescriptor['loginattempt'] );
		}

		if ( !$this->isSignup() ) {
			// FIXME HACK don't focus on non-empty field
			// maybe there should be an autofocus-if similar to hide-if?
			if (
				isset( $formDescriptor['username'] )
				&& empty( $formDescriptor['username']['default'] )
				&& !$this->getRequest()->getCheck( 'wpName' )
			) {
				$formDescriptor['username']['autofocus'] = true;
			} elseif ( isset( $formDescriptor['password'] ) ) {
				$formDescriptor['password']['autofocus'] = true;
			}
		}

		$this->addTabIndex( $formDescriptor );
	}
}

/**
 * B/C class to try handling login/signup template modifications even though login/signup does not
 * actually happen through a template anymore. Just collects extra field definitions and allows
 * some other class to do decide what to do with threm..
 * TODO find the right place for adding extra fields... maybe an abstract pre-auth provider?
 */
class FakeAuthTemplate extends BaseTemplate {
	public function execute() {
		throw new LogicException( 'not used' );
	}

	/**
	 * Extensions (AntiSpoof and TitleBlacklist) call this in response to
	 * UserCreateForm hook to add checkboxes to the create account form.
	 */
	public function addInputItem( $name, $value, $type, $msg, $helptext = false ) {
		// use the same indexes as UserCreateForm just in case someone adds an item manually
		$this->data['extrainput'][] = [
			'name' => $name,
			'value' => $value,
			'type' => $type,
			'msg' => $msg,
			'helptext' => $helptext,
		];
	}

	/**
	 * Turns addInputItem-style field definitions into HTMLForm field definitions.
	 * @return array
	 */
	public function getExtraInputDefinitions() {
		$definitions = [];

		foreach ( $this->get( 'extrainput', [] ) as $field ) {
			$definition = [
				'type' => $field['type'] === 'checkbox' ? 'check' : $field['type'],
				'name' => $field['name'],
				'value' => $field['value'],
				'id' => $field['name'],
			];
			if ( $field['msg'] ) {
				$definition['label-message'] = $this->getMsg( $field['msg'] );
			}
			if ( $field['helptext'] ) {
				$definition['help'] = $this->msgWiki( $field['helptext'] );
			}

			// the array key doesn't matter much when name is defined explicitly but
			// let's try and follow HTMLForm conventions
			$name = preg_replace( '/^wp(?=[A-Z])/', '', $field['name'] );
			$definitions[$name] = $definition;
		}

		if ( $this->haveData( 'extrafields' ) ) {
			$definitions['extrafields'] = [
				'type' => 'info',
				'raw' => true,
				'default' => $this->get( 'extrafields' ),
			];
		}

		return $definitions;
	}
}

/**
 * LoginForm as a special page has been replaced by SpecialUserLogin and SpecialCreateAccount,
 * but some extensions called its public methods directly, so the class is retained as a
 * B/C wrapper. Anything that used it before should use AuthManager instead.
 */
class LoginForm extends SpecialPage {
	const SUCCESS = 0;
	const NO_NAME = 1;
	const ILLEGAL = 2;
	const WRONG_PLUGIN_PASS = 3;
	const NOT_EXISTS = 4;
	const WRONG_PASS = 5;
	const EMPTY_PASS = 6;
	const RESET_PASS = 7;
	const ABORTED = 8;
	const CREATE_BLOCKED = 9;
	const THROTTLED = 10;
	const USER_BLOCKED = 11;
	const NEED_TOKEN = 12;
	const WRONG_TOKEN = 13;
	const USER_MIGRATED = 14;

	public static $statusCodes = [
		self::SUCCESS => 'success',
		self::NO_NAME => 'no_name',
		self::ILLEGAL => 'illegal',
		self::WRONG_PLUGIN_PASS => 'wrong_plugin_pass',
		self::NOT_EXISTS => 'not_exists',
		self::WRONG_PASS => 'wrong_pass',
		self::EMPTY_PASS => 'empty_pass',
		self::RESET_PASS => 'reset_pass',
		self::ABORTED => 'aborted',
		self::CREATE_BLOCKED => 'create_blocked',
		self::THROTTLED => 'throttled',
		self::USER_BLOCKED => 'user_blocked',
		self::NEED_TOKEN => 'need_token',
		self::WRONG_TOKEN => 'wrong_token',
		self::USER_MIGRATED => 'user_migrated',
	];

	/**
	 * @param WebRequest $request
	 */
	public function __construct( $request = null ) {
		wfDeprecated( 'LoginForm', '1.27' );
		parent::__construct();

		if ( $request ) {
			$this->setRequest( $request->getValues(), $request->wasPosted() );
		}

	}

	/**
	 * @deprecated since 1.27 - don't use LoginForm, use AuthManager instead
	 */
	public static function incrementLoginThrottle( $username ) {
		wfDeprecated( __METHOD__, "1.27" );
		global $wgRequest;
		$username = User::getCanonicalName( $username, 'usable' ) ?: $username;
		return Throttler::getInstance()->increase( $username, $wgRequest->getIP(), __METHOD__ );
	}

	/**
	 * @deprecated since 1.27 - don't use LoginForm, use AuthManager instead
	 */
	public static function incLoginThrottle( $username ) {
		wfDeprecated( __METHOD__, "1.27" );
		$res = self::incrementLoginThrottle( $username );
		return is_array( $res ) ? true : 0;
	}

	/**
	 * @deprecated since 1.27 - don't use LoginForm, use AuthManager instead
	 */
	public static function clearLoginThrottle( $username ) {
		wfDeprecated( __METHOD__, "1.27" );
		global $wgRequest;
		$username = User::getCanonicalName( $username, 'usable' ) ?: $username;
		return Throttler::getInstance()->clear( $username, $wgRequest->getIP() );
	}

	/**
	 * @deprecated since 1.27 - don't use LoginForm, use AuthManager instead
	 */
	public static function getLoginToken() {
		wfDeprecated( __METHOD__, '1.27' );
		global $wgRequest;
		return $wgRequest->getSession()->getToken( '', 'login' )->toString();
	}

	/**
	 * @deprecated since 1.27 - don't use LoginForm, use AuthManager instead
	 */
	public static function setLoginToken() {
		wfDeprecated( __METHOD__, '1.27' );
	}

	/**
	 * @deprecated since 1.27 - don't use LoginForm, use AuthManager instead
	 */
	public static function clearLoginToken() {
		wfDeprecated( __METHOD__, '1.27' );
		global $wgRequest;
		$wgRequest->getSession()->resetToken( 'login' );
	}

	/**
	 * @deprecated since 1.27 - don't use LoginForm, use AuthManager instead
	 */
	public static function getCreateaccountToken() {
		wfDeprecated( __METHOD__, '1.27' );
		global $wgRequest;
		return $wgRequest->getSession()->getToken( '', 'createaccount' )->toString();
	}

	/**
	 * @deprecated since 1.27 - don't use LoginForm, use AuthManager instead
	 */
	public static function setCreateaccountToken() {
		wfDeprecated( __METHOD__, '1.27' );
	}

	/**
	 * @deprecated since 1.27 - don't use LoginForm, use AuthManager instead
	 */
	public static function clearCreateaccountToken() {
		wfDeprecated( __METHOD__, '1.27' );
		global $wgRequest;
		$wgRequest->getSession()->resetToken( 'createaccount' );
	}
}
