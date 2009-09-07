<?php
/**
 * SpecialPage for logging users into the wiki
 * @ingroup SpecialPage
 */

class SpecialUserLogin extends SpecialPage {

	var $mUsername, $mPassword, $mReturnTo, $mCookieCheck, $mPosted;
	var $mCreateaccount, $mCreateaccountMail, $mMailmypassword;
	var $mRemember, $mDomain, $mLanguage;
	var $mSkipCookieCheck, $mReturnToQuery;

	public $mDomains = array();

	public $mFormHeader = ''; # Can be filled by hooks etc
	public $mFormFields = array(
		'Name' => array(
			'type'          => 'text',
			'label-message' => 'yourname',
			'id'            => 'wpName1',
			'tabindex'      => '1',
			'size'          => '20',
			'required'      => '1',
		),
		'Password' => array(
			'type'          => 'password',
			'label-message' => 'yourpassword',
			'size'          => '20',
			'id'            => 'wpPassword1',
		),
		'Domain' => array(
			'type'          => 'select',
			'id'            => 'wpDomain',
			'label-message' => 'yourdomainname',
			'options'       => null,
			'default'       => null, 
		),
		'Remember' => array(
			'type'          => 'check',
			'label-message' => 'remembermypassword',
			'id'            => 'wpRemember',
		)
	);

	protected $mLogin; # Login object

	public function __construct(){
		parent::__construct( 'Userlogin' );
	}

	function execute( $par ) {
		global $wgRequest;
		$this->loadQuery();
		$this->mLogin = new Login();

		# Redirect out for account creation, for B/C
		$type = ( $par == 'signup' ) ? $par : $wgRequest->getText( 'type' );
		if( $type == 'signup' ){
			$sp = new SpecialCreateAccount();
			$sp->execute( $par );
			return;
		}

		if ( !is_null( $this->mCookieCheck ) ) {
			$this->onCookieRedirectCheck();
			return;
		} else if( $this->mPosted ) {
			if ( $this->mMailmypassword ) {
				return $this->showMailPage();
			} else {
				return $this->processLogin();
			}
		} else {
			$this->mainLoginForm( '' );
		}
	}

	/**
	 * Load member variables from the HTTP request data
	 * @param $par String the fragment passed to execute()
	 */
	protected function loadQuery(){
		global $wgRequest, $wgAuth, $wgHiddenPrefs, $wgEnableEmail, $wgRedirectOnLogin;

		$this->mUsername = $wgRequest->getText( 'wpName' );
		$this->mPassword = $wgRequest->getText( 'wpPassword' );
		$this->mDomain = $wgRequest->getText( 'wpDomain' );
		$this->mLanguage = $wgRequest->getText( 'uselang' );

		$this->mReturnTo = $wgRequest->getVal( 'returnto' );
		$this->mReturnToQuery = $wgRequest->getVal( 'returntoquery' );
		$this->mCookieCheck = $wgRequest->getVal( 'wpCookieCheck' );

		$this->mMailmypassword = $wgRequest->getCheck( 'wpMailmypassword' )
		                         && $wgEnableEmail;
		$this->mRemember = $wgRequest->getCheck( 'wpRemember' );
		$this->mSkipCookieCheck = $wgRequest->getCheck( 'wpSkipCookieCheck' );
		$this->mPosted = $wgRequest->wasPosted();

		if ( $wgRedirectOnLogin ) {
			$this->mReturnTo = $wgRedirectOnLogin;
			$this->mReturnToQuery = '';
		}

		if( !$wgAuth->validDomain( $this->mDomain ) ) {
			$this->mDomain = 'invaliddomain';
		}
		$wgAuth->setDomain( $this->mDomain );

		# When switching accounts, it sucks to get automatically logged out
		$returnToTitle = Title::newFromText( $this->mReturnTo );
		if( is_object( $returnToTitle ) && $returnToTitle->isSpecial( 'Userlogout' ) ) {
			$this->mReturnTo = '';
			$this->mReturnToQuery = '';
		}
	}

	/**
	 * Show the main login form
	 * @param $msg String a message key for a warning/error message
	 * that may have been generated on a previous iteration
	 */
	protected function mainLoginForm( $msg, $msgtype = 'error' ) {
		global $wgUser, $wgOut, $wgEnableEmail;
		global $wgCookiePrefix, $wgLoginLanguageSelector;
		global $wgAuth, $wgCookieExpiration;

		# Preload the name field with something if we can
		if ( '' == $this->mUsername ) {
			if ( $wgUser->isLoggedIn() ) {
				$this->mUsername = $wgUser->getName();
			} elseif( isset( $_COOKIE[$wgCookiePrefix.'UserName'] ) ) {
				$this->mUsername = $_COOKIE[$wgCookiePrefix.'UserName'];
			}
		}
		if( $this->mUsername ){
			$this->mFormFields['Name']['default'] = $this->mUsername;
			$this->mFormFields['Password']['autofocus'] = '1';
		} else {
			$this->mFormFields['Name']['autofocus'] = '1';
		}

		# Parse the error message if we got one
		if( $msg ){
			if( $msgtype == 'error' ){
				$msg = wfMsg( 'loginerror' ) . ' ' . $msg;
			}
			$msg = Html::rawElement(
				'div',
				array( 'class' => $msgtype . 'box' ),
				$msg
			);
		} else {
			$msg = '';
		}

		# Make sure the returnTo strings don't get lost if the
		# user changes language, etc
		$linkq = array();
		if ( !empty( $this->mReturnTo ) ) {
			$linkq['returnto'] = wfUrlencode( $this->mReturnTo );
			if ( !empty( $this->mReturnToQuery ) )
				$linkq['returntoquery'] = wfUrlencode( $this->mReturnToQuery );
		}

		# Pass any language selection on to the mode switch link
		if( $wgLoginLanguageSelector && $this->mLanguage )
			$linkq['uselang'] = $this->mLanguage;

		$skin = $wgUser->getSkin();
		$link = $skin->link( 
			SpecialPage::getTitleFor( 'CreateAccount' ),
			wfMsgHtml( 'nologinlink' ),
			array(),
			$linkq );

		# Don't show a "create account" link if the user can't
		$link = $wgUser->isAllowed( 'createaccount' ) && !$wgUser->isLoggedIn()
			? wfMsgWikiHtml( 'nologin', $link )
			: '';

		# Prepare language selection links as needed
		$langSelector = $wgLoginLanguageSelector 
			? Html::rawElement( 
				'div',
				array( 'id' => 'languagelinks' ),
				self::makeLanguageSelector( $this->getTitle(), $this->mReturnTo ) )
			: '';
		
		# Add a  'mail reset' button if available
		$buttons = '';
		if( $wgEnableEmail && $wgAuth->allowPasswordChange() ){
			$buttons = Html::element(
				'input',
				array( 
					'type'  => 'submit',
					'name'  => 'wpMailmypassword',
					'value' => wfMsg( 'mailmypassword' ),
					'id'    => 'wpMailmypassword',
				) 
			);
		}

		# Give authentication and captcha plugins a chance to 
		# modify the form, by hook or by using $wgAuth
		$wgAuth->modifyUITemplate( $this, 'login' );
		wfRunHooks( 'UserLoginForm', array( &$this ) );
	
		# The most likely use of the hook is to enable domains;
		# check that now, and add fields if necessary
		if( $this->mDomains ){
			$this->mFormFields['Domain']['options'] = $this->mDomains;
			$this->mFormFields['Domain']['default'] = $this->mDomain;
		} else {
			unset( $this->mFormFields['Domain'] );
		}
		
		# Or to tweak the 'remember my password' checkbox
		if( !($wgCookieExpiration > 0) ){
			# Remove it altogether
			unset( $this->mFormFields['Remember'] );
		} elseif( $wgUser->getOption( 'rememberpassword' ) || $this->mRemember ){
			# Or check it by default
			# FIXME: this doesn't always work?
			$this->mFormFields['Remember']['checked'] = '1';
		}
		
		$form = new HTMLForm( $this->mFormFields, '' );
		$form->setTitle( $this->getTitle() );
		$form->setSubmitText( wfMsg( 'login' ) );
		$form->setSubmitId( 'wpLoginAttempt' );
		$form->suppressReset();
		$form->loadData();
		
		$formContents = '' 
			. Html::rawElement( 'p', array( 'id' => 'userloginlink' ),
				$link )
			. Html::rawElement( 'div', array( 'id' => 'userloginprompt' ),
				wfMsgExt( 'loginprompt', array( 'parseinline' ) ) )
			. $this->mFormHeader
			. $langSelector
			. $form->getBody() 
			. $form->getButtons()
			. $buttons
			. Xml::hidden( 'returnto', $this->mReturnTo )
			. Xml::hidden( 'returntoquery', $this->mReturnToQuery )
		;

		$wgOut->setPageTitle( wfMsg( 'login' ) );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );
		$wgOut->disallowUserJs();  # Stop malicious userscripts sniffing passwords

		$wgOut->addHTML( 
			Html::rawElement( 
				'div', 
				array( 'id' => 'loginstart' ), 
				wfMsgExt( 'loginstart', array( 'parseinline' ) )
			) . 
			$msg . 
			Html::rawElement(
				'div',
				array( 'id' => 'userloginForm' ),
				$form->wrapForm( $formContents )
			) . 
			Html::rawElement( 
				'div', 
				array( 'id' => 'loginend' ), 
				wfMsgExt( 'loginend', array( 'parseinline' ) )
			)
		);

	}	

	/**
	 * Check if a session cookie is present.
	 *
	 * This will not pick up a cookie set during _this_ request, but is meant
	 * to ensure that the client is returning the cookie which was set on a
	 * previous pass through the system.
	 *
	 * @private
	 */
	protected function hasSessionCookie() {
		global $wgDisableCookieCheck, $wgRequest;
		return $wgDisableCookieCheck ? true : $wgRequest->checkSessionCookie();
	}

	/**
	 * Do a redirect back to the same page, so we can check any
	 * new session cookies.
	 */
	protected function cookieRedirectCheck() {
		global $wgOut;

		$query = array( 'wpCookieCheck' => '1');
		if ( $this->mReturnTo ) $query['returnto'] = $this->mReturnTo;
		$check = $this->getTitle()->getFullURL( $query );

		return $wgOut->redirect( $check );
	}

	/**
	 * Check the cookies and show errors if they're not enabled.
	 * @param $type String action being performed
	 */
	protected function onCookieRedirectCheck() {
		if ( !$this->hasSessionCookie() ) {
			return $this->mainLoginForm( wfMsgExt( 'nocookieslogin', array( 'parseinline' ) ) );
		} else {
			return self::successfulLogin( 'loginsuccess', $this->mReturnTo, $this->mReturnToQuery );
		}
	}

	/**
	 * Produce a bar of links which allow the user to select another language
	 * during login/registration but retain "returnto"
	 * @param $title Title to use in the link
	 * @param $returnTo query string to append
	 * @return String HTML for bar
	 */
	public static function makeLanguageSelector( $title, $returnTo=false ) {
		global $wgLang;

		$msg = wfMsgForContent( 'loginlanguagelinks' );
		if( $msg != '' && !wfEmptyMsg( 'loginlanguagelinks', $msg ) ) {
			$langs = explode( "\n", $msg );
			$links = array();
			foreach( $langs as $lang ) {
				$lang = trim( $lang, '* ' );
				$parts = explode( '|', $lang );
				if (count($parts) >= 2) {
					$links[] = SpecialUserLogin::makeLanguageSelectorLink( 
							$parts[0], $parts[1], $title, $returnTo );
				}
			}
			return count( $links ) > 0 ? wfMsgHtml( 'loginlanguagelabel', $wgLang->pipeList( $links ) ) : '';
		} else {
			return '';
		}
	}

	/**
	 * Create a language selector link for a particular language
	 * Links back to this page preserving type and returnto
	 * @param $text Link text
	 * @param $lang Language code
	 * @param $title Title to link to
	 * @param $returnTo String returnto query
	 */
	public static function makeLanguageSelectorLink( $text, $lang, $title, $returnTo=false ) {
		global $wgUser;
		$attr = array( 'uselang' => $lang );
		if( $returnTo )
			$attr['returnto'] = $returnTo;
		$skin = $wgUser->getSkin();
		return $skin->linkKnown(
			$title,
			htmlspecialchars( $text ),
			array(),
			$attr
		);
	}

	/**
	 * Display a "login successful" page.
	 * @param $msgname String message key to display
	 * @param $html String HTML to optionally add
	 * @param $returnto Title to returnto
	 * @param $returntoQuery String query string for returnto link
	 */
	public static function displaySuccessfulLogin( $msgname, $injected_html='', $returnto=false, $returntoQuery=false ) {
		global $wgOut, $wgUser;

		$wgOut->setPageTitle( wfMsg( 'loginsuccesstitle' ) );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );
		$wgOut->addWikiMsg( $msgname, $wgUser->getName() );
		$wgOut->addHTML( $injected_html );

		if ( $returnto ) {
			$wgOut->returnToMain( null, $returnto, $this->mReturnToQuery );
		} else {
			$wgOut->returnToMain( null );
		}
	}

	/**
	 * Run any hooks registered for logins, then HTTP redirect to
	 * $this->mReturnTo (or Main Page if that's undefined).  Formerly we had a
	 * nice message here, but that's really not as useful as just being sent to
	 * wherever you logged in from.  It should be clear that the action was
	 * successful, given the lack of error messages plus the appearance of your
	 * name in the upper right.
	 */
	public static function successfulLogin( $message, $returnTo='', $returnToQuery='' ) {
		global $wgUser, $wgOut;

		# Run any hooks; display injected HTML if any, else redirect
		$injected_html = '';
		wfRunHooks('UserLoginComplete', array(&$wgUser, &$injected_html));

		if( $injected_html !== '' ) {
			SpecialUserLogin::displaySuccessfulLogin( $message, $injected_html );
		} else {
			$titleObj = Title::newFromText( $returnTo );
			if ( !$titleObj instanceof Title ) {
				$titleObj = Title::newMainPage();
			}
			$wgOut->redirect( $titleObj->getFullURL( $returnToQuery ) );
		}
	}
	

	protected function processLogin(){
		global $wgUser, $wgAuth;
		$result = $this->mLogin->attemptLogin();
		switch ( $result ) {
			case Login::SUCCESS:
				if( $this->hasSessionCookie() || $this->mSkipCookieCheck ) {
					# Replace the language object to provide user interface in
					# correct language immediately on this first page load.
					global $wgLang, $wgRequest;
					$code = $wgRequest->getVal( 'uselang', $wgUser->getOption( 'language' ) );
					$wgLang = Language::factory( $code );
					return self::successfulLogin( 'loginsuccess', $this->mReturnTo, $this->mReturnToQuery );
				} else {
					# Do a redirect check to ensure that the cookies are 
					# being retained by the user's browser.
					return $this->cookieRedirectCheck();
				}
				break;

			case Login::NO_NAME:
			case Login::ILLEGAL:
				$this->mainLoginForm( wfMsg( 'noname' ) );
				break;
			case Login::WRONG_PLUGIN_PASS:
				$this->mainLoginForm( wfMsg( 'wrongpassword' ) );
				break;
			case Login::NOT_EXISTS:
				if( $wgUser->isAllowed( 'createaccount' ) ){
					$this->mainLoginForm( wfMsgWikiHtml( 'nosuchuser', htmlspecialchars( $this->mName ) ) );
				} else {
					$this->mainLoginForm( wfMsg( 'nosuchusershort', htmlspecialchars( $this->mName ) ) );
				}
				break;
			case Login::WRONG_PASS:
				$this->mainLoginForm( wfMsg( 'wrongpassword' ) );
				break;
			case Login::EMPTY_PASS:
				$this->mainLoginForm( wfMsg( 'wrongpasswordempty' ) );
				break;
			case Login::RESET_PASS:
				$this->resetLoginForm( wfMsg( 'resetpass_announce' ) );
				break;
			case Login::CREATE_BLOCKED:
				$this->userBlockedMessage();
				break;
			case Login::THROTTLED:
				$this->mainLoginForm( wfMsg( 'login-throttled' ) );
				break;
			default:
				throw new MWException( "Unhandled case value" );
		}
	}

	/**
	 * 'Shell out' to Special:ResetPass to get the user to 
	 * set a new permanent password from a temporary one.
	 * @param $error String message
	 */
	function resetLoginForm( $error ) {
		global $wgOut;
		$wgOut->addHTML( Xml::element('p', array( 'class' => 'error' ), $error ) );
		$reset = new SpecialResetpass();
		$reset->execute( null );
	}

	/**
	 * Attempt to send the user a password-reset mail, and display
	 * the results (good, bad or ugly).
	 * @return unknown_type
	 */
	protected function showMailPage(){
		global $wgOut;
		$result = $this->mLogin->mailPassword();

		switch( $result ){
			case Login::MAIL_READ_ONLY : 
				$wgOut->readOnlyPage();
				return;
			case Login::MAIL_PASSCHANGE_FORBIDDEN:
				$this->mainLoginForm( wfMsg( 'resetpass_forbidden' ) );
				return;
			case Login::MAIL_BLOCKED: 
				$this->mainLoginForm( wfMsg( 'blocked-mailpassword' ) );
				return;
			case Login::MAIL_PING_THROTTLED: 
				$wgOut->rateLimited();
				return;
			case Login::MAIL_PASS_THROTTLED: 
				global $wgPasswordReminderResendTime;
				# Round the time in hours to 3 d.p., in case someone 
				# is specifying minutes or seconds.
				$this->mainLoginForm( wfMsgExt( 
					'throttled-mailpassword', 
					array( 'parsemag' ),
					round( $wgPasswordReminderResendTime, 3 )
				) );
				return;
			case Login::NO_NAME: 
				$this->mainLoginForm( wfMsg( 'noname' ) );
				return;
			case Login::NOT_EXISTS: 
				$this->mainLoginForm( wfMsgWikiHtml( 'nosuchuser', htmlspecialchars( $this->mLogin->mUser->getName() ) ) );
				return;
			case Login::MAIL_EMPTY_EMAIL: 
				$this->mainLoginForm( wfMsg( 'noemail', $this->mLogin->mUser->getName() ) );
				return;
			case Login::MAIL_BAD_IP: 
				$this->mainLoginForm( wfMsg( 'badipaddress' ) );
				return;
			case Login::MAIL_ERROR: 
				$this->mainLoginForm( wfMsg( 'mailerror', $this->mLogin->mMailResult->getMessage() ) );
				return;
			case Login::SUCCESS:
				$this->mainLoginForm( wfMsg( 'passwordsent', $this->mLogin->mUser->getName() ), 'success' );
				return;
		}
	}

	/**
	 * Since the UserLoginForm hook was changed to pass a SpecialPage
	 * instead of a QuickTemplate derivative, old extensions might
	 * easily try calling this method expecing it to exist.  Tempting
	 * though it is to let them have the fatal error, let's at least
	 * fail gracefully...
	 * @deprecated
	 */
	public function set(){
		wfDeprecated( __METHOD__ );
	}
}
