<?php

/**
 * New HTMLForm-based login form.
 *
 * @author Tyler Romeo <tylerromeo@gmail.com>
 * @since 1.21
 */
class SpecialUserlogin extends FormSpecialPage {
	/**
	 * Call parent constructor and then make a FakeTemplate
	 * to accomodate old extensions.
	 */
	function __construct() {
		parent::__construct( 'Userlogin' );
		$this->template = new FakeTemplate();
		wfRunHooks( 'UserLoginForm', array( &$this->template ) );
	}

	/**
	 * Check if $wgSecureLogin is enabled and redirect to HTTPS
	 * if necessary. Otherwise, disallow user JS and hand off
	 * control to the parent.
	 *
	 * @param $par Unused
	 */
	function execute( $par ) {
		global $wgSecureLogin;

		$this->getOutput()->disallowUserJs();

		if (
			$wgSecureLogin &&
			WebRequest::detectProtocol() !== 'https'
		) {
			// Construct HTTPS redirect.
			$title = $this->getFullTitle();
			$query = array(
				'returnto' => $this->mReturnTo,
				'returntoquery' => $this->mReturnToQuery,
				'wpStickHTTPS' => $this->mStickHTTPS
			);
			$url = $title->getFullURL( $query, false, PROTO_HTTPS );
			$this->getOutput()->redirect( $url );

		} else {
			parent::execute( $par );
		}
	}

	/**
	 * Make the default header text. This includes the link to the
	 * account creation form and the login prompt.
	 *
	 * @return string
	 */
	function headerText() {
		$pretext = '';

		// Add CreateAccount link if user is allowed to.
		if ( $this->getUser()->isAllowed( 'createaccount' ) ) {
			$request = $this->getRequest();
			$title = SpecialPage::getTitleFor( 'CreateAccount' );
			$query = array(
				'returnto' => $request->getVal( 'returnto' ),
				'returntoquery' => $request->getVal( 'returntoquery' ),
				'uselang' => $request->getVal( 'uselang' )
			);
			$html = $this->msg( 'nologinlink' )->text();
			$link = Linker::linkKnown( $title, $html, array(), $query );
			$pretext .= $this->msg( 'nologin' )->rawParams( $link )->parse();
		}

		// Add login prompt.
		$pretext .= Html::rawElement(
			'div',
			array( 'id' => 'userloginprompt' ),
			$this->msg( 'loginprompt' )->parse()
		);

		return $pretext;
	}

	/**
	 * Make the default footer text. This includes the password reset link.
	 *
	 * @return string
	 */
	function footerText() {
		global $wgEnableEmail, $wgAuth, $wgPasswordResetRoutes;
		$posttext = '';

		// Add password reset link if email reset is enabled.
		if (
			$wgEnableEmail &&
			$wgAuth->allowPasswordChange() &&
			is_array( $wgPasswordResetRoutes ) &&
			in_array( true, array_values( $wgPasswordResetRoutes ) )
		) {
			$posttext .= Linker::link(
				SpecialPage::getTitleFor( 'PasswordReset' ),
				wfMessage( 'userlogin-resetlink' )
			);
		}

		$loginendMsg = $this->msg( 'loginend-https' );
		if ( $this->getRequest()->detectProtocol() == 'https' && !$loginendMsg->isBlank() ) {
			$posttext .= $loginendMsg->parse();
		} else {
			$posttext .= $this->msg( 'loginend' )->parse();
		}

		return $posttext;
	}

	/**
	 * Set a throttle on the form and add the necessary header and
	 * footer text.
	 *
	 * @param $form HTMLForm object
	 */
	function alterForm( HTMLForm $form ) {
		global $wgPasswordAttemptThrottle;

		// Set login throttle.
		if ( is_array( $wgPasswordAttemptThrottle ) ) {
			$throttle = new Throttle(
				array( 'userlogin' ),
				$wgPasswordAttemptThrottle['count'],
				$wgPasswordAttemptThrottle['seconds'],
				Throttle::PER_USER | Throttle::PER_IP
			);
			$form->setThrottle( $throttle, true );
		}


		$headerText = $this->headerText();
		$footerText = $this->footerText();

		// Legacy code to support old hooks.
		if ( $this->template->haveData( 'header' ) ) {
			$headerText .= $this->template->getData( 'header' );
		}

		wfRunHooks( 'UserLoginAlterForm', array( $form, &$headerText, &$footerText ) );

		$form->addHeaderText( $headerText );
		$form->addFooterText( $footerText );
	}

	/**
	 * Get the form fields for the login form, including those added
	 * by the UserLoginFields hook.
	 *
	 * @return array
	 */
	function getFormFields() {
		global $wgLoginLanguageSelector, $wgCookieExpiration, $wgSecureLogin;

		$a = array();
		$user = $this->getUser();
		$request = $this->getRequest();

		// Language selector if enabled.
		if ( $wgLoginLanguageSelector ) {
			$a['Language'] = array(
				'type' => 'hidden'
			);

			$a['LanguageInfo'] = array(
				'type' => 'info',
				'label-message' => 'yourlanguage',
				'default' => $this->makeLanguageSelector(),
				'raw' => true
			);
		}

		if ( $user->isLoggedIn() ) {
			$name = $user->getName();
		} else {
			$name = $request->getSessionData( 'wsUserName', $request->getCookie( 'UserName' ) );
		}

		// Standard fields
		$a['Name'] = array(
			'type' => 'text',
			'label-message' => 'yourname',
			'default' => $name,
			'size' => 20,
			'required' => true,
			'filter-callback' => function( $val ) { return (string)User::getCanonicalName( $val, false ); },
			'validation-callback' => 'User::isUsableName',
		);

		$a['Password'] = array(
			'type' => 'password',
			'label-message' => 'yourpassword',
			'size' => 20,
			'required' => true
		);

		// Support legacy extra fields.
		if ( $this->template->haveData( 'extrafields' ) ) {
			$a['Extra'] = array(
				'type' => 'info',
				'raw' => true,
				'rawrow' => true,
				'default' => $this->template->getData( 'extrafields' ),
			);
		}

		// Remember password checkbox if cookies persist outside of browser session.
		if ( $wgCookieExpiration > 0 ) {
			$expirationDays = ceil( $wgCookieExpiration / ( 3600 * 24 ) );
			$a['Remember'] = array(
				'type' => 'check',
				'label' => $this->msg( 'remembermypassword' )->numParams( $expirationDays )->text(),
			);
		}

		// Stick HTTPS checkbox if secure login is enabled.
		if ( $wgSecureLogin === true ) {
			$a['StickHTTPS'] = array(
				'type' => 'check',
				'label-message' => 'securelogin-stick-https'
			);
		}

		wfRunHooks( 'UserLoginFields', array( &$a ) );

		return $a;
	}

	/**
	 * Check the login information, authenticate, and login if appropriate.
	 *
	 * @param $data Array of data from HTMLForm
	 * @return Status
	 */
	function onSubmit( array $data ) {
		global $wgAuth, $wgExternalAuthType, $wgAutocreatePolicy,
			$wgBlockDisablesLogin, $wgUser, $wgDisableCookieCheck;

		// Check if already logged in.
		if ( $this->getUser()->getName() == $data['Name'] ) {
			return true;
		}

		$status = Status::newGood();

		$user = User::newFromName( $data['Name'] );
		if ( !( $user instanceof User ) ) {
			// Make a fake User object for the hooks.
			$user = new User;
			$user->mName = $data['Name'];
			$user->mId = 0;
			$user->mFrom = false;
			$user->setItemLoaded( 'id' );
			$user->setItemLoaded( 'name' );

			$status->fatal( 'noname' );
		}

		$status->value = $user;
		// Deprecated stuff
		$oldStatus = LoginForm::SUCCESS;

		$isAutoCreated = false;
		if ( $status->isOK() && $user->isAnon() ) {
			// User does not exist. Try to autocreate.
			$abortError = '';
			if ( $this->getUser()->isBlockedFromCreateAccount() ) {
				$status->fatal( 'login-userblocked', $data['Name'] );
				$oldStatus = LoginForm::CREATE_BLOCKED;
			} elseif ( !$wgAuth->autoCreate() || !$wgAuth->userExists( $user->getName() ) ) {
				$oldStatus = LoginForm::NOT_EXISTS;
				$status->fatal( 'nosuchuser', $data['Name'] );
			} elseif ( !$wgAuth->authenticate( $user->getName(), $data['Password'] ) ) {
				$oldStatus = LoginForm::WRONG_PASS;
				$status->fatal( 'wrongpassword' );
			} elseif ( !wfRunHooks( 'AbortAutoAccount', array( $user, &$error ) ) ) {
				$oldStatus = LoginForm::NOT_EXISTS;
				$status->fatal( $error );
			} else {
				// Make the user and continue with authentication.
				if ( $wgAuth->allowPasswordChange() ) {
					$user->setPassword( $data['Password'] );
				}

				$user->setToken();
				$user->setOption( 'rememberpassword', $data['Remember'] ? 1 : 0 );
				$user->addToDatabase();
				$wgAuth->initUser( $u, true );

				DeferredUpdates::addUpdate( new SiteStatsUpdate( 0, 0, 0, 0, 1 ) );
				$isAutoCreated = true;
			}
		}

		$abort = LoginForm::ABORTED;
		$message = null;
		if ( !$status->isOK() ) {
			// User does not exists. Don't attempt to authenticate.
		} elseif ( !wfRunHooks( 'AbortLogin', array( $user, $data['Password'], &$abort, &$message ) ) ) {
			$status->fatal( $message );
			$oldStatus = $abort;
		} elseif ( $wgBlockDisablesLogin && $user->isBlocked() ) {
			$status->fatal( 'login-userblocked', $data['Name'] );
			$oldStatus = LoginForm::USER_BLOCKED;
		} elseif ( $user->checkPassword( $data['Password'] ) ) {
			// Password is correct
			$wgAuth->setDomain( $data['Domain'] );
			$wgAuth->updateUser( $user );
			$wgUser = $user;
			$this->getContext()->setUser( $user );

			if ( $isAutoCreated ) {
				wfRunHooks( 'AuthPluginAutoCreate', array( $user ) );
			}

			// Set options and cookies.
			if ( $data['Remember'] != $user->getOptions( 'rememberpassword' ) ) {
				$user->setOption( 'rememberpassword', $data['Remember'] ? 1 : 0 );
				$user->saveSettings();
			} else {
				$user->invalidateCache();
			}
			$user->setCookies();

			// Set the language if necessary.
			$request = $this->getRequest();
			$code = $request->getVal( 'uselang', $user->getOption( 'language' ) );
			$lang = Language::factory( $code );
			$wgLang = $lang;
			$this->getContext()->setLanguage( $lang );

			// Check for session cookie, and handle appropriately.
			if ( !$wgDisableCookieCheck && $this->getRequest()->checkSessionCookie() ) {
				if ( $request->getCheck( 'wpCookie' ) ) {
					// We already attempted to load the cookie.
					$status->fatal( 'nocookieslogin' );
				} else {
					// Try redirecting to self so that cookie is re-sent.
					$title = $this->getFullTitle();
					$query = array(
						'returnto' => $request->getVal( 'returnto' ),
						'returntoquery' => $request->getVal( 'returntoquery' ),
						'uselang' => $request->getVal( 'uselang' )
					);
					$url = $title->getFullURL( $query );
					$this->getOutput()->redirect( $url );
				}
			}
		} elseif ( $user->checkTemporaryPassword( $data['Password'] ) ) {
			// Temporary password. Force password reset.
			if ( !$user->isEmailConfirmed() ) {
				$user->confirmEmail();
				$user->saveSettings();
			}
			$reset = new SpecialChangePassword();
			$reset->setContext( $this->getContext() );
			$reset->execute( null );
			$status->fatal( 'resetpass_announce' );
		} else {
			// Wrong password.
			$status->fatal( 'wrongpassword' );
			$oldStatus = LoginForm::WRONG_PASS;
		}

		wfRunHooks( 'LoginAuthenticateAudit', array( $user, $data['Password'], $oldStatus, $status ) );

		return $status;
	}

	/**
	 * On successful login, either redirect or show injected HTML and a returnto.
	 */
	function onSuccess() {
		global $wgRedirectOnLogin, $wgSecureLogin;

		$user = $this->getUser();
		$html = '';
		wfRunHooks( 'UserLoginComplete', array( &$user, &$html ) );

		if ( $html !== '' ) {
			$out = $this->getOutput();
			$out->setPageTitle( $this->msg( 'loginsuccesstitle' ) );
			$out->addWikiMsg( 'loginsuccess', wfEscapeWikiText( $this->getUser()->getName() ) );
			$out->addHTML( $html );
		}

		if ( $wgRedirectOnLogin !== null ) {
			$returnTo = $wgRedirectOnLogin;
			$returnToQuery = array();
		} else {
			$returnTo = $this->getRequest()->getVal( 'returnto' );
			$returnToQuery = wfCgiToArray( $this->getRequest()->getVal( 'returnto' ) );
		}

		$title = Title::newFromText( $returnTo );
		if ( !$title || $title->isSpecial( 'Userlogout' ) || $title->isSpecial( 'PasswordReset' ) ) {
			$title = Title::newMainPage();
		}

		if ( $wgSecureLogin ) {
			if ( $this->getRequest()->getCheck( 'wpStickHTTPS' ) ) {
				$options = array( 'https' );
				$proto = PROTO_HTTPS;
			} else {
				$options = array( 'http' );
				$proto = PROTO_HTTP;
			}
		} else {
			$options = array();
			$proto = PROTO_CURRENT;
		}

		if ( $html === '' ) {
			$url = $title->getFullURL( $returnToQuery, false, $proto );
			$this->getOutput()->redirect( $url );
		} else {
			$this->getOutput()->addReturnTo( $title, $returnToQuery, $options );
		}
	}

	/**
	 * Make a pipe-separated list of language links for the login form.
	 *
	 * @return string
	 */
	private function makeLanguageSelector() {
		$msg = $this->msg( 'loginlanguagelinks' )->inContentLanguage();
		$currentLang = $this->getLanguage()->getCode();
		$langs = explode( "\n", $msg->text() );
		$links = array();
		foreach ( $langs as $lang ) {
			$lang = trim( $lang, '*' );
			$parts = explode( '|', $lang );
			if ( count( $parts ) < 2 ) {
				continue;
			}

			$text = $parts[0];
			$code = trim( $parts[1] );
			if ( $code == $currentLang ) {
				$links[] = htmlspecialchars( $text );
			} else {
				$request = $this->getRequest();
				$query = array(
					'returnto' => $request->getVal( 'returnto' ),
					'returntoquery' => $request->getVal( 'returntoquery' ),
					'uselang' => $code
				);

				$attrs = array();
				$targetLanguage = Language::factory( $code );
				$attrs['lang'] = $attrs['hreflang'] = $targetLanguage->getHtmlCode();

				$links[] = Linker::linkKnown(
					$this->getFullTitle(),
					htmlspecialchars( $text ),
					$attrs,
					$query
				);
			}
		}

		if ( !$links ) {
			return '';
		} else {
			$list = $this->getLanguage()->pipeList( $links );
			return $list;
		}
	}
}

/**
 * Fake template to accomodate for legacy extensions.
 */
class FakeTemplate extends QuickTemplate {
	function execute() {}
}
