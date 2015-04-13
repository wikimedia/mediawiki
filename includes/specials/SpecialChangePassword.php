<?php
/**
 * Implements Special:ChangePassword
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
class SpecialChangePassword extends FormSpecialPage {
	protected $mUserName;
	protected $mDomain;

	// Optional Wikitext Message to show above the password change form
	protected $mPreTextMessage = null;

	// label for old password input
	protected $mOldPassMsg = null;

	public function __construct() {
		parent::__construct( 'ChangePassword', 'editmyprivateinfo' );
		$this->listed( false );
	}

	/**
	 * Main execution point
	 * @param string|null $par
	 */
	function execute( $par ) {
		$this->getOutput()->disallowUserJs();

		parent::execute( $par );
	}

	protected function checkExecutePermissions( User $user ) {
		parent::checkExecutePermissions( $user );

		if ( !$this->getRequest()->wasPosted() ) {
			$this->requireLogin( 'resetpass-no-info' );
		}
	}

	/**
	 * Set a message at the top of the Change Password form
	 * @since 1.23
	 * @param Message $msg Message to parse and add to the form header
	 */
	public function setChangeMessage( Message $msg ) {
		$this->mPreTextMessage = $msg;
	}

	/**
	 * Set a message at the top of the Change Password form
	 * @since 1.23
	 * @param string $msg Message label for old/temp password field
	 */
	public function setOldPasswordMessage( $msg ) {
		$this->mOldPassMsg = $msg;
	}

	protected function getFormFields() {
		$user = $this->getUser();
		$request = $this->getRequest();

		$oldpassMsg = $this->mOldPassMsg;
		if ( $oldpassMsg === null ) {
			$oldpassMsg = $user->isLoggedIn() ? 'oldpassword' : 'resetpass-temp-password';
		}

		$fields = array(
			'Name' => array(
				'type' => 'info',
				'label-message' => 'username',
				'default' => $request->getVal( 'wpName', $user->getName() ),
			),
			'Password' => array(
				'type' => 'password',
				'label-message' => $oldpassMsg,
			),
			'NewPassword' => array(
				'type' => 'password',
				'label-message' => 'newpassword',
			),
			'Retype' => array(
				'type' => 'password',
				'label-message' => 'retypenew',
			),
		);

		if ( !$this->getUser()->isLoggedIn() ) {
			if ( !LoginForm::getLoginToken() ) {
				LoginForm::setLoginToken();
			}
			$fields['LoginOnChangeToken'] = array(
				'type' => 'hidden',
				'label' => 'Change Password Token',
				'default' => LoginForm::getLoginToken(),
			);
		}

		$extraFields = array();
		Hooks::run( 'ChangePasswordForm', array( &$extraFields ) );
		foreach ( $extraFields as $extra ) {
			list( $name, $label, $type, $default ) = $extra;
			$fields[$name] = array(
				'type' => $type,
				'name' => $name,
				'label-message' => $label,
				'default' => $default,
			);
		}

		if ( !$user->isLoggedIn() ) {
			$fields['Remember'] = array(
				'type' => 'check',
				'label' => $this->msg( 'remembermypassword' )
						->numParams(
							ceil( $this->getConfig()->get( 'CookieExpiration' ) / ( 3600 * 24 ) )
						)->text(),
				'default' => $request->getVal( 'wpRemember' ),
			);
		}

		return $fields;
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setId( 'mw-resetpass-form' );
		$form->setTableId( 'mw-resetpass-table' );
		$form->setWrapperLegendMsg( 'resetpass_header' );
		$form->setSubmitTextMsg(
			$this->getUser()->isLoggedIn()
				? 'resetpass-submit-loggedin'
				: 'resetpass_submit'
		);
		$form->addButton( 'wpCancel', $this->msg( 'resetpass-submit-cancel' )->text() );
		$form->setHeaderText( $this->msg( 'resetpass_text' )->parseAsBlock() );
		if ( $this->mPreTextMessage instanceof Message ) {
			$form->addPreText( $this->mPreTextMessage->parseAsBlock() );
		}
		$form->addHiddenFields(
			$this->getRequest()->getValues( 'wpName', 'wpDomain', 'returnto', 'returntoquery' ) );
	}

	public function onSubmit( array $data ) {
		global $wgAuth;

		$request = $this->getRequest();

		if ( $request->getCheck( 'wpLoginToken' ) ) {
			// This comes from Special:Userlogin when logging in with a temporary password
			return false;
		}

		if ( !$this->getUser()->isLoggedIn()
			&& $request->getVal( 'wpLoginOnChangeToken' ) !== LoginForm::getLoginToken()
		) {
			// Potential CSRF (bug 62497)
			return false;
		}

		if ( $request->getCheck( 'wpCancel' ) ) {
			$returnto = $request->getVal( 'returnto' );
			$titleObj = $returnto !== null ? Title::newFromText( $returnto ) : null;
			if ( !$titleObj instanceof Title ) {
				$titleObj = Title::newMainPage();
			}
			$query = $request->getVal( 'returntoquery' );
			$this->getOutput()->redirect( $titleObj->getFullURL( $query ) );

			return true;
		}

		try {
			$this->mUserName = $request->getVal( 'wpName', $this->getUser()->getName() );
			$this->mDomain = $wgAuth->getDomain();

			if ( !$wgAuth->allowPasswordChange() ) {
				throw new ErrorPageError( 'changepassword', 'resetpass_forbidden' );
			}

			$this->attemptReset( $data['Password'], $data['NewPassword'], $data['Retype'] );

			return true;
		} catch ( PasswordError $e ) {
			return $e->getMessage();
		}
	}

	public function onSuccess() {
		if ( $this->getUser()->isLoggedIn() ) {
			$this->getOutput()->wrapWikiMsg(
				"<div class=\"successbox\">\n$1\n</div>",
				'changepassword-success'
			);
			$this->getOutput()->returnToMain();
		} else {
			$request = $this->getRequest();
			LoginForm::setLoginToken();
			$token = LoginForm::getLoginToken();
			$data = array(
				'action' => 'submitlogin',
				'wpName' => $this->mUserName,
				'wpDomain' => $this->mDomain,
				'wpLoginToken' => $token,
				'wpPassword' => $request->getVal( 'wpNewPassword' ),
			) + $request->getValues( 'wpRemember', 'returnto', 'returntoquery' );
			$login = new LoginForm( new DerivativeRequest( $request, $data, true ) );
			$login->setContext( $this->getContext() );
			$login->execute( null );
		}
	}

	/**
	 * @param string $oldpass
	 * @param string $newpass
	 * @param string $retype
	 * @throws PasswordError When cannot set the new password because requirements not met.
	 */
	protected function attemptReset( $oldpass, $newpass, $retype ) {
		$isSelf = ( $this->mUserName === $this->getUser()->getName() );
		if ( $isSelf ) {
			$user = $this->getUser();
		} else {
			$user = User::newFromName( $this->mUserName );
		}

		if ( !$user || $user->isAnon() ) {
			throw new PasswordError( $this->msg( 'nosuchusershort', $this->mUserName )->text() );
		}

		if ( $newpass !== $retype ) {
			Hooks::run( 'PrefsPasswordAudit', array( $user, $newpass, 'badretype' ) );
			throw new PasswordError( $this->msg( 'badretype' )->text() );
		}

		$throttleCount = LoginForm::incLoginThrottle( $this->mUserName );
		if ( $throttleCount === true ) {
			$lang = $this->getLanguage();
			$throttleInfo = $this->getConfig()->get( 'PasswordAttemptThrottle' );
			throw new PasswordError( $this->msg( 'changepassword-throttled' )
				->params( $lang->formatDuration( $throttleInfo['seconds'] ) )
				->text()
			);
		}

		// @todo Make these separate messages, since the message is written for both cases
		if ( !$user->checkTemporaryPassword( $oldpass ) && !$user->checkPassword( $oldpass ) ) {
			Hooks::run( 'PrefsPasswordAudit', array( $user, $newpass, 'wrongpassword' ) );
			throw new PasswordError( $this->msg( 'resetpass-wrong-oldpass' )->text() );
		}

		// User is resetting their password to their old password
		if ( $oldpass === $newpass ) {
			throw new PasswordError( $this->msg( 'resetpass-recycled' )->text() );
		}

		// Do AbortChangePassword after checking mOldpass, so we don't leak information
		// by possibly aborting a new password before verifying the old password.
		$abortMsg = 'resetpass-abort-generic';
		if ( !Hooks::run( 'AbortChangePassword', array( $user, $oldpass, $newpass, &$abortMsg ) ) ) {
			Hooks::run( 'PrefsPasswordAudit', array( $user, $newpass, 'abortreset' ) );
			throw new PasswordError( $this->msg( $abortMsg )->text() );
		}

		// Please reset throttle for successful logins, thanks!
		if ( $throttleCount ) {
			LoginForm::clearLoginThrottle( $this->mUserName );
		}

		try {
			$user->setPassword( $newpass );
			Hooks::run( 'PrefsPasswordAudit', array( $user, $newpass, 'success' ) );
		} catch ( PasswordError $e ) {
			Hooks::run( 'PrefsPasswordAudit', array( $user, $newpass, 'error' ) );
			throw new PasswordError( $e->getMessage() );
		}

		if ( $isSelf ) {
			// This is needed to keep the user connected since
			// changing the password also modifies the user's token.
			$remember = $this->getRequest()->getCookie( 'Token' ) !== null;
			$user->setCookies( null, null, $remember );
		}
		$user->resetPasswordExpiration();
		$user->saveSettings();
	}

	public function requiresUnblock() {
		return false;
	}

	protected function getGroupName() {
		return 'users';
	}
}
