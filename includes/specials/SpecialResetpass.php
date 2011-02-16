<?php
/**
 * Implements Special:Resetpass
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

/**
 * Let users recover their password.
 *
 * @ingroup SpecialPage
 */
class SpecialResetpass extends SpecialPage {

	public $mFormFields = array(
		'NameInfo' => array(
			'type'          => 'info',
			'label-message' => 'yourname',
			'default'       => '',
		),
		'Name' => array(
			'type'          => 'hidden',
			'name'          => 'wpName',
			'default'       => null,
		),
		'OldPassword' => array(
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
			'id'            => 'wpRemember',
		),
	);

	protected $mUsername;
	protected $mLogin;

	public function __construct() {
		global $wgRequest, $wgUser, $wgLang, $wgCookieExpiration;

		parent::__construct( 'Resetpass' );
		$this->mFormFields['Retype']['validation-callback'] = array( 'SpecialCreateAccount', 'formValidateRetype' );

		$this->mUsername = $wgRequest->getVal( 'wpName', $wgUser->getName() );
		$this->mReturnTo = $wgRequest->getVal( 'returnto' );
		$this->mReturnToQuery = $wgRequest->getVal( 'returntoquery' );

		$this->mFormFields['Remember']['label'] = wfMsgExt(
			'remembermypassword',
			'parseinline',
			$wgLang->formatNum( ceil( $wgCookieExpiration / 86400 ) )
		);
	}

	/**
	 * Main execution point
	 */
	public function execute( $par ) {
		global $wgUser, $wgAuth, $wgOut, $wgRequest;

		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}

		$this->setHeaders();
		$this->outputHeader();
		$wgOut->disallowUserJs();

		if( wfReadOnly() ){
			$wgOut->readOnlyPage();
			return false;
		}
		if( !$wgAuth->allowPasswordChange() ) {
			$wgOut->showErrorPage( 'errorpagetitle', 'resetpass_forbidden' );
			return false;
		}

		if( !$wgRequest->wasPosted() && !$wgUser->isLoggedIn() ) {
			$wgOut->showErrorPage( 'errorpagetitle', 'resetpass-no-info' );
			return false;
		}

		$this->getForm()->show();

	}

	public function formSubmitCallback( $data ){
		$data['Password'] = $data['OldPassword'];
		$this->mLogin =  new Login( $data );
		$result = $this->attemptReset( $data );

		if( $result === true ){
			# Log the user in if they're not already (ie we're
			# coming from the e-mail-password-reset route
			global $wgUser;
			if( !$wgUser->isLoggedIn() ) {
				$this->mLogin->attemptLogin( $data['NewPassword'] );
				# Redirect out to the appropriate target.
				SpecialUserlogin::successfulLogin(
					'resetpass_success',
					$this->mReturnTo,
					$this->mReturnToQuery,
					$this->mLogin->mLoginResult
				);
			} else {
				# Redirect out to the appropriate target.
				SpecialUserlogin::successfulLogin(
					'resetpass_success',
					$this->mReturnTo,
					$this->mReturnToQuery
				);
			}
			return true;
		} else {
			return $result;
		}
	}

	public function getForm( $reset=false ) {
		global $wgOut, $wgUser, $wgRequest;

		if( $reset || $wgRequest->getCheck( 'reset' ) ){
			# Request is coming from Special:UserLogin after it
			# authenticated someone with a temporary password.
			$this->mFormFields['OldPassword']['label-message'] = 'resetpass-temp-password';
			$submitMsg = 'resetpass_submit';
			$this->mFormFields['OldPassword']['default'] = $wgRequest->getText( 'wpPassword' );
			#perpetuate
			$this->mFormFields['reset'] = array(
				'type' => 'hidden',
				'default' => '1',
			);
		} else {
			unset( $this->mFormFields['Remember'] );
			$submitMsg = 'resetpass-submit-loggedin';
		}

		$this->mFormFields['Name']['default'] =
		$this->mFormFields['NameInfo']['default'] = $this->mUsername;

		$form = new HTMLForm( $this->mFormFields, '' );
		$form->suppressReset();
		$form->setSubmitText( wfMsg( $submitMsg ) );
		$form->setTitle( $this->getTitle() );
		$form->addHiddenField( 'returnto', $this->mReturnTo );
		$form->setWrapperLegend( wfMsg( 'resetpass_header' ) );

		$form->setSubmitCallback( array( $this, 'formSubmitCallback' ) );
		$form->loadData();

		return $form;
	}

	/**
	 * Try to reset the user's password
	 */
	protected function attemptReset( $data ) {

		if(    !$data['Name']
			|| !$data['OldPassword']
			|| !$data['NewPassword']
			|| !$data['Retype'] )
		{
			return false;
		}

		$user = $this->mLogin->getUser();
		if( !( $user instanceof User ) ){
			return wfMsgExt( 'nosuchuser', 'parse' );
		}

		if( $data['NewPassword'] !== $data['Retype'] ) {
			wfRunHooks( 'PrefsPasswordAudit', array( $user, $data['NewPassword'], 'badretype' ) );
			return wfMsgExt( 'badretype', 'parse' );
		}

		if( !$user->checkPassword( $data['OldPassword'] ) && !$user->checkTemporaryPassword( $data['OldPassword'] ) )
		{
			wfRunHooks( 'PrefsPasswordAudit', array( $user, $data['NewPassword'], 'wrongpassword' ) );
			return wfMsgExt( 'resetpass-wrong-oldpass', 'parse' );
		}

		try {
			$user->setPassword( $data['NewPassword'] );
			wfRunHooks( 'PrefsPasswordAudit', array( $user, $data['NewPassword'], 'success' ) );
		} catch( PasswordError $e ) {
			wfRunHooks( 'PrefsPasswordAudit', array( $user, $data['NewPassword'], 'error' ) );
			return $e->getMessage();
		}

		$user->setCookies();
		$user->saveSettings();
		return true;
	}
}
