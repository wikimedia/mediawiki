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
class SpecialChangePasswordPreAuthManager extends FormSpecialPage {
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

	public function doesWrites() {
		return true;
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

		$fields = [
			'Name' => [
				'type' => 'info',
				'label-message' => 'username',
				'default' => $request->getVal( 'wpName', $user->getName() ),
			],
			'Password' => [
				'type' => 'password',
				'label-message' => $oldpassMsg,
			],
			'NewPassword' => [
				'type' => 'password',
				'label-message' => 'newpassword',
			],
			'Retype' => [
				'type' => 'password',
				'label-message' => 'retypenew',
			],
		];

		if ( !$this->getUser()->isLoggedIn() ) {
			$fields['LoginOnChangeToken'] = [
				'type' => 'hidden',
				'label' => 'Change Password Token',
				'default' => LoginForm::getLoginToken()->toString(),
			];
		}

		$extraFields = [];
		Hooks::run( 'ChangePasswordForm', [ &$extraFields ] );
		foreach ( $extraFields as $extra ) {
			list( $name, $label, $type, $default ) = $extra;
			$fields[$name] = [
				'type' => $type,
				'name' => $name,
				'label-message' => $label,
				'default' => $default,
			];
		}

		if ( !$user->isLoggedIn() ) {
			$fields['Remember'] = [
				'type' => 'check',
				'label' => $this->msg( 'remembermypassword' )
						->numParams(
							ceil( $this->getConfig()->get( 'CookieExpiration' ) / ( 3600 * 24 ) )
						)->text(),
				'default' => $request->getVal( 'wpRemember' ),
			];
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
		$form->addButton( [
			'name' => 'wpCancel',
			'value' => $this->msg( 'resetpass-submit-cancel' )->text()
		] );
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
			&& !LoginForm::getLoginToken()->match( $request->getVal( 'wpLoginOnChangeToken' ) )
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

		$this->mUserName = $request->getVal( 'wpName', $this->getUser()->getName() );
		$this->mDomain = $wgAuth->getDomain();

		if ( !$wgAuth->allowPasswordChange() ) {
			throw new ErrorPageError( 'changepassword', 'resetpass_forbidden' );
		}

		$status = $this->attemptReset( $data['Password'], $data['NewPassword'], $data['Retype'] );

		return $status;
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
			LoginForm::clearLoginToken();
			$token = LoginForm::getLoginToken()->toString();
			$data = [
				'action' => 'submitlogin',
				'wpName' => $this->mUserName,
				'wpDomain' => $this->mDomain,
				'wpLoginToken' => $token,
				'wpPassword' => $request->getVal( 'wpNewPassword' ),
			] + $request->getValues( 'wpRemember', 'returnto', 'returntoquery' );
			$login = new LoginForm( new DerivativeRequest( $request, $data, true ) );
			$login->setContext( $this->getContext() );
			$login->execute( null );
		}
	}

	/**
	 * Checks the new password if it meets the requirements for passwords and set
	 * it as a current password, otherwise set the passed Status object to fatal
	 * and doesn't change anything
	 *
	 * @param string $oldpass The current (temporary) password.
	 * @param string $newpass The password to set.
	 * @param string $retype The string of the retype password field to check with newpass
	 * @return Status
	 */
	protected function attemptReset( $oldpass, $newpass, $retype ) {
		$isSelf = ( $this->mUserName === $this->getUser()->getName() );
		if ( $isSelf ) {
			$user = $this->getUser();
		} else {
			$user = User::newFromName( $this->mUserName );
		}

		if ( !$user || $user->isAnon() ) {
			return Status::newFatal( $this->msg( 'nosuchusershort', $this->mUserName ) );
		}

		if ( $newpass !== $retype ) {
			Hooks::run( 'PrefsPasswordAudit', [ $user, $newpass, 'badretype' ] );
			return Status::newFatal( $this->msg( 'badretype' ) );
		}

		$throttleInfo = LoginForm::incrementLoginThrottle( $this->mUserName );
		if ( $throttleInfo ) {
			return Status::newFatal( $this->msg( 'changepassword-throttled' )
				->durationParams( $throttleInfo['wait'] )
			);
		}

		// @todo Make these separate messages, since the message is written for both cases
		if ( !$user->checkTemporaryPassword( $oldpass ) && !$user->checkPassword( $oldpass ) ) {
			Hooks::run( 'PrefsPasswordAudit', [ $user, $newpass, 'wrongpassword' ] );
			return Status::newFatal( $this->msg( 'resetpass-wrong-oldpass' ) );
		}

		// User is resetting their password to their old password
		if ( $oldpass === $newpass ) {
			return Status::newFatal( $this->msg( 'resetpass-recycled' ) );
		}

		// Do AbortChangePassword after checking mOldpass, so we don't leak information
		// by possibly aborting a new password before verifying the old password.
		$abortMsg = 'resetpass-abort-generic';
		if ( !Hooks::run( 'AbortChangePassword', [ $user, $oldpass, $newpass, &$abortMsg ] ) ) {
			Hooks::run( 'PrefsPasswordAudit', [ $user, $newpass, 'abortreset' ] );
			return Status::newFatal( $this->msg( $abortMsg ) );
		}

		// Please reset throttle for successful logins, thanks!
		LoginForm::clearLoginThrottle( $this->mUserName );

		try {
			$user->setPassword( $newpass );
			Hooks::run( 'PrefsPasswordAudit', [ $user, $newpass, 'success' ] );
		} catch ( PasswordError $e ) {
			Hooks::run( 'PrefsPasswordAudit', [ $user, $newpass, 'error' ] );
			return Status::newFatal( new RawMessage( $e->getMessage() ) );
		}

		if ( $isSelf ) {
			// This is needed to keep the user connected since
			// changing the password also modifies the user's token.
			$remember = $this->getRequest()->getCookie( 'Token' ) !== null;
			$user->setCookies( null, null, $remember );
		}
		$user->saveSettings();
		$this->resetPasswordExpiration( $user );
		return Status::newGood();
	}

	public function requiresUnblock() {
		return false;
	}

	protected function getGroupName() {
		return 'users';
	}

	/**
	 * For resetting user password expiration, until AuthManager comes along
	 * @param User $user
	 */
	private function resetPasswordExpiration( User $user ) {
		global $wgPasswordExpirationDays;
		$newExpire = null;
		if ( $wgPasswordExpirationDays ) {
			$newExpire = wfTimestamp(
				TS_MW,
				time() + ( $wgPasswordExpirationDays * 24 * 3600 )
			);
		}
		// Give extensions a chance to force an expiration
		Hooks::run( 'ResetPasswordExpiration', [ $this, &$newExpire ] );
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update(
			'user',
			[ 'user_password_expires' => $dbw->timestampOrNull( $newExpire ) ],
			[ 'user_id' => $user->getId() ],
			__METHOD__
		);
	}

	protected function getDisplayFormat() {
		return 'ooui';
	}
}
