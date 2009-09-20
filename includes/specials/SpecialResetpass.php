<?php
/**
 * @file
 * @ingroup SpecialPage
 */

/**
 * Let users recover their password.
 * @ingroup SpecialPage
 */
class SpecialResetpass extends SpecialPage {
	public function __construct() {
		parent::__construct( 'Resetpass' );
	}
	
	public $mFormFields = array(
		'Name' => array(
			'type'          => 'info',
			'label-message' => 'yourname',
			'default'       => '',
		),
		'Password' => array(
			'type'          => 'password',
			'label-message' => 'oldpassword',
			'size'          => '20',
			'id'            => 'wpPassword',
			'required'      => '',
		),
		'NewPassword' => array(
			'type'          => 'password',
			'label-message' => 'newpassword',
			'size'          => '20',
			'id'            => 'wpNewPassword',
			'required'      => '',
		),
		'Retype' => array(
			'type'          => 'password',
			'label-message' => 'retypenew',
			'size'          => '20',
			'id'            => 'wpRetype',
			'required'      => '',
		),
		'Remember' => array(
			'type'          => 'check',
			'label-message' => 'remembermypassword',
			'id'            => 'wpRemember',
		),
	);
	public $mSubmitMsg = 'resetpass-submit-loggedin';
	public $mHeaderMsg = '';
	public $mHeaderMsgType = 'error';
	
	protected $mUsername;
	protected $mOldpass;
	protected $mNewpass;
	protected $mRetype;

	/**
	 * Main execution point
	 */
	function execute( $par ) {
		global $wgUser, $wgAuth, $wgOut, $wgRequest;

		$this->mUsername = $wgRequest->getVal( 'wpName', $wgUser->getName() );
		$this->mOldpass = $wgRequest->getVal( 'wpPassword' );
		$this->mNewpass = $wgRequest->getVal( 'wpNewPassword' );
		$this->mRetype = $wgRequest->getVal( 'wpRetype' );
		$this->mRemember = $wgRequest->getVal( 'wpRemember' );
		$this->mReturnTo = $wgRequest->getVal( 'returnto' );
		$this->mReturnToQuery = $wgRequest->getVal( 'returntoquery' );
		
		$this->setHeaders();
		$this->outputHeader();

		if( !$wgAuth->allowPasswordChange() ) {
			$wgOut->showErrorPage( 'errorpagetitle', 'resetpass_forbidden' );
			return false;
		}

		if( !$wgRequest->wasPosted() && !$wgUser->isLoggedIn() ) {
			$wgOut->showErrorPage( 'errorpagetitle', 'resetpass-no-info' );
			return false;
		}

		if( $wgRequest->wasPosted() 
		    && $wgUser->matchEditToken( $wgRequest->getVal('wpEditToken') )
			&& $this->attemptReset() )
		{
			# Log the user in if they're not already (ie we're 
			# coming from the e-mail-password-reset route
			if( !$wgUser->isLoggedIn() ) {
				$data = array(
					'wpName'     => $this->mUsername,
					'wpPassword' => $this->mNewpass,
					'returnto'   => $this->mReturnTo,
				);
				if( $this->mRemember ) {
					$data['wpRemember'] = 1;
				}
				$login = new Login( new FauxRequest( $data, true ) );
				$login->attemptLogin();
			
				# Redirect out to the appropriate target.
				SpecialUserlogin::successfulLogin( 
					'resetpass_success', 
					$this->mReturnTo, 
					$this->mReturnToQuery,
					$login->mLoginResult
				);
			} else {
				# Redirect out to the appropriate target.
				SpecialUserlogin::successfulLogin( 
					'resetpass_success', 
					$this->mReturnTo, 
					$this->mReturnToQuery
				);
			}
		} else {
			$this->showForm();
		}
	}

	function showForm() {
		global $wgOut, $wgUser;

		$wgOut->disallowUserJs();
		
		if( $wgUser->isLoggedIn() ){
			unset( $this->mFormFields['Remember'] );
		} else {
			# Request is coming from Special:UserLogin after it
			# authenticated someone with a temporary password.
			$this->mFormFields['Password']['label-message'] = 'resetpass-temp-password';
			$this->mSubmitMsg = 'resetpass_submit';
		}
		$this->mFormFields['Name']['default'] = $this->mUsername;
		
		$header = $this->mHeaderMsg
			? Xml::element( 'div', array( 'class' => "{$this->mHeaderMsgType}box" ), wfMsg( $this->mHeaderMsg ) )
			: '';
				
		$form = new HTMLForm( $this->mFormFields, '' );
		$form->suppressReset();
		$form->setSubmitText( wfMsg( $this->mSubmitMsg ) );
		$form->setTitle( $this->getTitle() );
		$form->loadData();
		
		$formContents = '' 
			. $form->getBody()
			. $form->getButtons()
			. $form->getHiddenFields()
			. Html::hidden( 'wpName', $this->mUsername )
			. Html::hidden( 'returnto', $this->mReturnTo )
			;
		$formOutput = $form->wrapForm( $formContents );
		
		$wgOut->addHTML(
			$header
			. Html::rawElement( 'fieldset', array( 'class' => 'visualClear' ), ''
				. Html::element( 'legend', array(), wfMsg( 'resetpass_header' ) )
				. $formOutput
			)
		);
	}

	/**
	 * Try to reset the user's password 
	 */
	protected function attemptReset() {
		$user = User::newFromName( $this->mUsername );
		if( !$user || $user->isAnon() ) {
			$this->mHeaderMsg = 'no such user';
			return false;
		}
		
		if( $this->mNewpass !== $this->mRetype ) {
			wfRunHooks( 'PrefsPasswordAudit', array( $user, $this->mNewpass, 'badretype' ) );
			$this->mHeaderMsg = 'badretype';
			return false;
		}

		if( !$user->checkTemporaryPassword($this->mOldpass) && !$user->checkPassword($this->mOldpass) ) {
			wfRunHooks( 'PrefsPasswordAudit', array( $user, $this->mNewpass, 'wrongpassword' ) );
			$this->mHeaderMsg = 'resetpass-wrong-oldpass';
			return false;
		}
		
		try {
			$user->setPassword( $this->mNewpass );
			wfRunHooks( 'PrefsPasswordAudit', array( $user, $this->mNewpass, 'success' ) );
			$this->mNewpass = $this->mOldpass = $this->mRetypePass = '';
		} catch( PasswordError $e ) {
			wfRunHooks( 'PrefsPasswordAudit', array( $user, $this->mNewpass, 'error' ) );
			$this->mHeaderMsg = $e->getMessage();
			return false;
		}
		
		$user->setCookies();
		$user->saveSettings();
		return true;
	}
}
