<?php
/**
 * Special page for creating/registering new user accounts.
 * @ingroup SpecialPage
 */
class SpecialCreateAccount extends SpecialPage {

	var $mUsername, $mPassword, $mRetype, $mReturnTo, $mPosted;
	var $mCreateaccountMail, $mRemember, $mEmail, $mDomain, $mLanguage;
	var $mReturnToQuery;

	public $mDomains = array();
	
	public $mUseEmail = true; # Can be switched off by AuthPlugins etc
	public $mUseRealname = true;
	public $mUseRemember = true;
	
	public $mFormHeader = '';
	public $mFormFields = array(
		'Name' => array(
			'type'          => 'text',
			'label-message' => 'yourname',
			'id'            => 'wpName2',
			'tabindex'      => '1',
			'size'          => '20',
			'required'      => '1',
			'autofocus'     => '',
		),
		'Password' => array(
			'type'          => 'password',
			'label-message' => 'yourpassword',
			'size'          => '20',
			'id'            => 'wpPassword2',
			'required'      => '',
		),
		'Retype' => array(
			'type'          => 'password',
			'label-message' => 'yourpasswordagain',
			'size'          => '20',
			'id'            => 'wpRetype',
			'required'      => '',
		),
		'Email' => array(
			'type'          => 'email',
			'label-message' => 'youremail',
			'size'          => '20',
			'id'            => 'wpEmail',
		),
		'RealName' => array(
			'type'          => 'text',
			'label-message' => 'yourrealname',
			'id'            => 'wpRealName',
			'tabindex'      => '1',
			'size'          => '20',
		),
		'Remember' => array(
			'type'          => 'check',
			'label-message' => 'remembermypassword',
			'id'            => 'wpRemember',
		),
		'Domain' => array(
			'type'          => 'select',
			'id'            => 'wpDomain',
			'label-message' => 'yourdomainname',
			'options'       => null,
			'default'       => null, 
		),
	);
	
	public function __construct(){
		parent::__construct( 'CreateAccount', 'createaccount' );
		$this->mLogin = new Login();
		$this->mFormFields['RealName']['help'] = wfMsg( 'prefs-help-realname' );
	}
	
	public function execute( $par ){
		global $wgUser, $wgOut;
		
		$this->setHeaders();
		$this->loadQuery();
		
		# Block signup here if in readonly. Keeps user from 
		# going through the process (filling out data, etc) 
		# and being informed later.
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		} 
		# Bail out straightaway on permissions errors
		if ( !$this->userCanExecute( $wgUser ) ) {
			$this->displayRestrictionError();
			return;
		} elseif ( $wgUser->isBlockedFromCreateAccount() ) {
			$this->userBlockedMessage();
			return;
		} elseif ( count( $permErrors = $this->getTitle()->getUserPermissionsErrors( 'createaccount', $wgUser, true ) )>0 ) {
			var_dump('error');
			$wgOut->showPermissionsErrorPage( $permErrors, 'createaccount' );
			return;
		}	
		
		if( $this->mPosted ) {
			if ( $this->mCreateaccountMail ) {
				return $this->addNewAccountMailPassword();
			} else {
				return $this->addNewAccount();
			}
		} else {
			$this->showMainForm('');
		}
	}
	
	/**
	 * Load the member variables from the request parameters
	 */
	protected function loadQuery(){
		global $wgRequest, $wgAuth, $wgHiddenPrefs, $wgEnableEmail, $wgRedirectOnLogin;
		$this->mCreateaccountMail = $wgRequest->getCheck( 'wpCreateaccountMail' )
		                            && $wgEnableEmail;
		
		$this->mUsername = $wgRequest->getText( 'wpName' );
		$this->mPassword = $wgRequest->getText( 'wpPassword' );
		$this->mRetype = $wgRequest->getText( 'wpRetype' );
		$this->mDomain = $wgRequest->getText( 'wpDomain' );
		$this->mReturnTo = $wgRequest->getVal( 'returnto' );
		$this->mReturnToQuery = $wgRequest->getVal( 'returntoquery' );
		$this->mPosted = $wgRequest->wasPosted();
		$this->mCreateaccountMail = $wgRequest->getCheck( 'wpCreateaccountMail' )
		                            && $wgEnableEmail;
		$this->mRemember = $wgRequest->getCheck( 'wpRemember' );
		$this->mLanguage = $wgRequest->getText( 'uselang' );
		
		if ( $wgRedirectOnLogin ) {
			$this->mReturnTo = $wgRedirectOnLogin;
			$this->mReturnToQuery = '';
		}

		if( $wgEnableEmail ) {
			$this->mEmail = $wgRequest->getText( 'wpEmail' );
		} else {
			$this->mEmail = '';
		}
		if( !in_array( 'realname', $wgHiddenPrefs ) ) {
		    $this->mRealName = $wgRequest->getText( 'wpRealName' );
		} else {
		    $this->mRealName = '';
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
	 * Add a new account, and mail its password to the user
	 */
	protected function addNewAccountMailPassword() {
		global $wgOut;

		if( !$this->mEmail ) {
			$this->showMainForm( wfMsg( 'noemail', htmlspecialchars( $this->mUsername ) ) );
			return;
		}

		if( !$this->addNewaccountInternal() ) {
			return;
		}

		# Wipe the initial password 
		$this->mLogin->mUser->setPassword( null );
		$this->mLogin->mUser->saveSettings();
		
		# And mail them a temporary one
		$result = $this->mLogin->mailPassword( 'createaccount-title', 'createaccount-text' );

		wfRunHooks( 'AddNewAccount', array( $this->mLogin->mUser, true ) );
		$this->mLogin->mUser->addNewUserLogEntry();

		$wgOut->setPageTitle( wfMsg( 'accmailtitle' ) );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );

		if( $result != Login::SUCCESS ) {
			if( $result == Login::MAIL_ERROR ){
				$this->showMainForm( wfMsg( 'mailerror', $this->mLogin->mMailResult->getMessage() ) );
			} else {
				$this->showMainForm( wfMsg( 'mailerror' ) );
			}
		} else {
			$wgOut->addWikiMsg( 'accmailtext', $this->mLogin->mUserer->getName(), $this->mLogin->mUser->getEmail() );
			$wgOut->returnToMain( false );
		}
	}

	/**
	 * Create a new user account from the provided data
	 */
	protected function addNewAccount() {
		global $wgUser, $wgEmailAuthentication;

		# Create the account and abort if there's a problem doing so
		if( !$this->addNewAccountInternal() )
			return;
		$user = $this->mLogin->mUser;

		# If we showed up language selection links, and one was in use, be
		# smart (and sensible) and save that language as the user's preference
		global $wgLoginLanguageSelector;
		if( $wgLoginLanguageSelector && $this->mLanguage )
			$user->setOption( 'language', $this->mLanguage );

		# Send out an email authentication message if needed
		if( $wgEmailAuthentication && User::isValidEmailAddr( $user->getEmail() ) ) {
			global $wgOut;
			$error = $user->sendConfirmationMail();
			if( WikiError::isError( $error ) ) {
				$wgOut->addWikiMsg( 'confirmemail_sendfailed', $error->getMessage() );
			} else {
				$wgOut->addWikiMsg( 'confirmemail_oncreate' );
			}
		}

		# Save settings (including confirmation token)
		$user->saveSettings();

		# If not logged in, assume the new account as the current one and set
		# session cookies then show a "welcome" message or a "need cookies"
		# message as needed
		if( $wgUser->isAnon() ) {
			$wgUser = $user;
			$wgUser->setCookies();
			wfRunHooks( 'AddNewAccount', array( $wgUser ) );
			$wgUser->addNewUserLogEntry();
			if( $this->hasSessionCookie() ) {
				return $this->successfulCreation();
			} else {
				return $this->cookieRedirectCheck();
			}
		} else {
			# Confirm that the account was created
			global $wgOut;
			$self = SpecialPage::getTitleFor( 'Userlogin' );
			$wgOut->setPageTitle( wfMsgHtml( 'accountcreated' ) );
			$wgOut->setArticleRelated( false );
			$wgOut->setRobotPolicy( 'noindex,nofollow' );
			$wgOut->addHTML( wfMsgWikiHtml( 'accountcreatedtext', $user->getName() ) );
			$wgOut->returnToMain( false, $self );
			wfRunHooks( 'AddNewAccount', array( $user ) );
			$user->addNewUserLogEntry();
			return true;
		}
	}

	/**
	 * Deeper mechanics of initialising a new user and passing it
	 * off to Login::initUser()
	 * return Bool whether the user was successfully created
	 */
	protected function addNewAccountInternal() {
		global $wgUser, $wgOut;
		global $wgEnableSorbs, $wgProxyWhitelist;
		global $wgMemc, $wgAccountCreationThrottle;
		global $wgAuth, $wgMinimalPasswordLength;
		global $wgEmailConfirmToEdit;

		# If the user passes an invalid domain, something is fishy
		if( !$wgAuth->validDomain( $this->mDomain ) ) {
			$this->showMainForm( wfMsg( 'wrongpassword' ) );
			return false;
		}

		# If we are not allowing users to login locally, we should be checking
		# to see if the user is actually able to authenticate to the authenti-
		# cation server before they create an account (otherwise, they can
		# create a local account and login as any domain user). We only need
		# to check this for domains that aren't local.
		if(    !in_array( $this->mDomain, array( 'local', '' ) ) 
			&& !$wgAuth->canCreateAccounts() 
			&& ( !$wgAuth->userExists( $this->mUsername ) 
				|| !$wgAuth->authenticate( $this->mUsername, $this->mPassword ) 
			) ) 
		{
			$this->showMainForm( wfMsg( 'wrongpassword' ) );
			return false;
		}

		$ip = wfGetIP();
		if ( $wgEnableSorbs && !in_array( $ip, $wgProxyWhitelist ) &&
		  $wgUser->inSorbsBlacklist( $ip ) )
		{
			$this->showMainForm( wfMsg( 'sorbs_create_account_reason' ) . ' (' . htmlspecialchars( $ip ) . ')' );
			return false;
		}

		# Now create a dummy user ($user) and check if it is valid
		$name = trim( $this->mUsername );
		$user = User::newFromName( $name, 'creatable' );
		if ( is_null( $user ) ) {
			$this->showMainForm( wfMsg( 'noname' ) );
			return false;
		}

		if ( 0 != $user->idForName() ) {
			$this->showMainForm( wfMsg( 'userexists' ) );
			return false;
		}

		if ( 0 != strcmp( $this->mPassword, $this->mRetype ) ) {
			$this->showMainForm( wfMsg( 'badretype' ) );
			return false;
		}

		# check for minimal password length
		$valid = $user->isValidPassword( $this->mPassword );
		if ( $valid !== true ) {
			if ( !$this->mCreateaccountMail ) {
				$this->showMainForm( wfMsgExt( $valid, array( 'parsemag' ), $wgMinimalPasswordLength ) );
				return false;
			} else {
				# do not force a password for account creation by email
				# set invalid password, it will be replaced later by a random generated password
				$this->mPassword = null;
			}
		}

		# if you need a confirmed email address to edit, then obviously you
		# need an email address.
		if ( $wgEmailConfirmToEdit && empty( $this->mEmail ) ) {
			$this->showMainForm( wfMsg( 'noemailtitle' ) );
			return false;
		}

		if( !empty( $this->mEmail ) && !User::isValidEmailAddr( $this->mEmail ) ) {
			$this->showMainForm( wfMsg( 'invalidemailaddress' ) );
			return false;
		}

		# Set some additional data so the AbortNewAccount hook can be used for
		# more than just username validation
		$user->setEmail( $this->mEmail );
		$user->setRealName( $this->mRealName );

		$abortError = '';
		if( !wfRunHooks( 'AbortNewAccount', array( $user, &$abortError ) ) ) {
			# Hook point to add extra creation throttles and blocks
			wfDebug( "LoginForm::addNewAccountInternal: a hook blocked creation\n" );
			$this->showMainForm( $abortError );
			return false;
		}

		if ( $wgAccountCreationThrottle && $wgUser->isPingLimitable() ) {
			$key = wfMemcKey( 'acctcreate', 'ip', $ip );
			$value = $wgMemc->get( $key );
			if ( !$value ) {
				$wgMemc->set( $key, 0, 86400 );
			}
			if ( $value >= $wgAccountCreationThrottle ) {
				$this->showMainForm( wfMsgExt( 'acct_creation_throttle_hit', array( 'parseinline' ), $wgAccountCreationThrottle ) ); 
				return false;
			}
			$wgMemc->incr( $key );
		}

		if( !$wgAuth->addUser( $user, $this->mPassword, $this->mEmail, $this->mRealName ) ) {
			$this->showMainForm( wfMsg( 'externaldberror' ) );
			return false;
		}

		$this->mLogin->mUser = $user;
		$this->mLogin->initUser( false );
		return true;
	}

	/**
	 * Run any hooks registered for logins, then 
	 * display a message welcoming the user.
	 */
	protected function successfulCreation(){
		global $wgUser, $wgOut;

		# Run any hooks; display injected HTML
		$injected_html = '';
		wfRunHooks('UserLoginComplete', array(&$wgUser, &$injected_html));

		SpecialUserLogin::displaySuccessfulLogin( 
			'welcomecreation', 
			$injected_html,
			$this->mReturnTo,
			$this->mReturnToQuery );
	}

	/**
	 * Display a message indicating that account creation from their IP has 
	 * been blocked by a (range)block with 'block account creation' enabled. 
	 * It's likely that this feature will be used for blocking large numbers 
	 * of innocent people, e.g. range blocks on schools. Don't blame it on 
	 * the user. There's a small chance that it really is the user's fault, 
	 * i.e. the username is blocked and they haven't bothered to log out 
	 * before trying to create an account to evade it, but we'll leave that 
	 * to their guilty conscience to figure out...
	 */
	protected function userBlockedMessage() {
		global $wgOut, $wgUser;

		$wgOut->setPageTitle( wfMsg( 'cantcreateaccounttitle' ) );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->setArticleRelated( false );

		$ip = wfGetIP();
		$blocker = User::whoIs( $wgUser->mBlock->mBy );
		$block_reason = $wgUser->mBlock->mReason;

		if ( strval( $block_reason ) === '' ) {
			$block_reason = wfMsg( 'blockednoreason' );
		}
		$wgOut->addWikiMsg( 'cantcreateaccount-text', $ip, $block_reason, $blocker );
		$wgOut->returnToMain( false );
	}

	/**
	 * Show the main input form, with an appropriate error message
	 * from a previous iteration, if necessary
	 * @param $msg String HTML of message received previously
	 * @param $msgtype String type of message, usually 'error'
	 */
	protected function showMainForm( $msg, $msgtype = 'error' ) {
		global $wgUser, $wgOut, $wgHiddenPrefs, $wgEnableEmail;
		global $wgCookiePrefix, $wgLoginLanguageSelector;
		global $wgAuth, $wgEmailConfirmToEdit, $wgCookieExpiration;
		
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
			SpecialPage::getTitleFor( 'Userlogin' ),
			wfMsgHtml( 'gotaccountlink' ),
			array(),
			$linkq );
		$link = $wgUser->isLoggedIn()
			? ''
			: wfMsgWikiHtml( 'gotaccount', $link );
		
		# Prepare language selection links as needed
		$langSelector = $wgLoginLanguageSelector 
			? Html::rawElement( 
				'div',
				array( 'id' => 'languagelinks' ),
				SpecialUserLogin::makeLanguageSelector( $this->getTitle(), $this->mReturnTo ) )
			: '';
		
		# Add a  'send password by email' button if available
		$buttons = '';
		if( $wgEnableEmail && $wgUser->isLoggedIn() ){
			$buttons = Html::element(
				'input',
				array( 
					'type'  => 'submit',
					'name'  => 'wpCreateaccountMail',
					'value' => wfMsg( 'createaccountmail' ),
					'id'    => 'wpCreateaccountMail',
				) 
			);
		}
		
		# Give authentication and captcha plugins a chance to 
		# modify the form, by hook or by using $wgAuth
		$wgAuth->modifyUITemplate( $this, 'new' );
		wfRunHooks( 'UserCreateForm', array( &$this ) );
		
		# The most likely use of the hook is to enable domains;
		# check that now, and add fields if necessary
		if( $this->mDomains ){
			$this->mFormFields['Domain']['options'] = $this->mDomains;
			$this->mFormFields['Domain']['default'] = $this->mDomain;
		} else {
			unset( $this->mFormFields['Domain'] );
		}
		
		# Or to switch email on or off
		if( !$wgEnableEmail || !$this->mUseEmail ){
			unset( $this->mFormFields['Email'] );
		} else {
			if( $wgEmailConfirmToEdit ){
				$this->mFormFields['Email']['help'] = wfMsg( 'prefs-help-email-required' );
				$this->mFormFields['Email']['required'] = '';
			} else {
				$this->mFormFields['Email']['help'] = wfMsg( 'prefs-help-email' );
			}
		}
		
		# Or to play with realname
		if( in_array( 'realname', $wgHiddenPrefs ) || !$this->mUseRealname ){
			unset( $this->mFormFields['Realname'] );
		}
		
		# Or to tweak the 'remember my password' checkbox
		if( !($wgCookieExpiration > 0) || !$this->mUseRemember ){
			# Remove it altogether
			unset( $this->mFormFields['Remember'] );
		} elseif( $wgUser->getOption( 'rememberpassword' ) || $this->mRemember ){
			# Or check it by default
			# FIXME: this doesn't always work?
			$this->mFormFields['Remember']['checked'] = '1';
		}
		
		$form = new HTMLForm( $this->mFormFields, '' );
		$form->setTitle( $this->getTitle() );
		$form->setSubmitText( wfMsg( 'createaccount' ) );
		$form->setSubmitId( 'wpCreateaccount' );
		$form->suppressReset();
		$form->loadData();
		
		$formContents = '' 
			. Html::rawElement( 'p', array( 'id' => 'userloginlink' ),
				$link )
			. $this->mFormHeader
			. $langSelector
			. $form->getBody() 
			. $form->getButtons()
			. $buttons
			. Xml::hidden( 'returnto', $this->mReturnTo )
			. Xml::hidden( 'returntoquery', $this->mReturnToQuery )
		;

		$wgOut->setPageTitle( wfMsg( 'createaccount' ) );
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

		$query = array( 'wpCookieCheck' => '1' );
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
			return $this->mainLoginForm( wfMsgExt( 'nocookiesnew', array( 'parseinline' ) ) );
		} else {
			return SpecialUserLogin::successfulLogin( 
				'welcomecreate', 
				$this->mReturnTo, 
				$this->mReturnToQuery );
		}
	}
	
	/**
	 * Since the UserCreateForm hook was changed to pass a SpecialPage
	 * instead of a QuickTemplate derivative, old extensions might
	 * easily try calling these methods expecing them to exist.  Tempting
	 * though it is to let them have the fatal error, let's at least
	 * fail gracefully...
	 * @deprecated
	 */
	public function set(){
		wfDeprecated( __METHOD__ );
	}
	public function addInputItem(){
		wfDeprecated( __METHOD__ );
	}
}
