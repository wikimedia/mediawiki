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
	/**
	 * @var string A temporary password reset token optionally provided by the user
	 */
	private $token = '';

	/**
	 * @var User The user whose password is being changed
	 */
	private $user;

	public function __construct() {
		parent::__construct( 'ChangePassword', 'editmyprivateinfo' );
		$this->listed( false );
		$this->user = $this->getUser();
	}

	public function execute( $par ) {
		global $wgSecureLogin;

		// If secure login is enabled, only allow password changes over HTTPS
		if (
			$this->getRequest()->getProtocol() !== 'https' &&
			$wgSecureLogin &&
			wfCanIPUseHTTPS( $this->getRequest()->getIP() )
		) {
			$request = $this->getRequest();
			$title = $this->getFullTitle();

			$query = array(
					'fromhttp' => '1',
					'returnto' => $request->getVal( 'returnto' ),
					'returntoquery' => $request->getVal( 'returntoquery' ),
					'title' => null,
			) + $request->getQueryValues();

			$url = $title->getFullURL( $query, false, PROTO_HTTPS );

			$this->getOutput()->redirect( $url );
			// Since we only do this redir to change proto, always vary
			$this->getOutput()->addVaryHeader( 'X-Forwarded-Proto' );

			return;
		}

		$this->getOutput()->disallowUserJs();

		parent::execute( $par );
	}

	protected function setParameter( $par ) {
		$user = User::newFromName( $par );
		if ( $user && $user->getId() > 0 ) {
			$this->user = $user;
			$this->token = $this->getRequest()->getVal( 'token' );
		}
	}

	protected function checkExecutePermissions( User $user ) {
		global $wgAuth;

		parent::checkExecutePermissions( $user );

		if ( $this->user->isAnon() ) {
			throw new UserNotLoggedIn;
		}

		if ( !$wgAuth->allowPasswordChange() ) {
			throw new ErrorPageError( 'changepassword', 'resetpass_forbidden' );
		}
	}

	protected function getFormFields() {
		$request = $this->getRequest();

		$fields = array(
			'Name' => array(
				'type' => 'info',
				'label-message' => 'username',
				'default' => $this->user->getName(),
			),
			'Password' => array(
				'type' => $this->token ? 'hidden' : 'password',
				'label-message' => 'oldpassword',
			),
			'NewPassword' => array(
				'type' => 'password',
				'label-message' => 'newpassword',
			),
			'Retype' => array(
				'type' => 'password',
				'label-message' => 'retypenew',
			),
			'ForceHttps' => array(
				'type' => 'hidden',
				'default' => !$request->getBool( 'fromhttp' ) && $request->getProtocol() === 'https'
			)
		);

		$extraFields = array();
		wfRunHooks( 'ChangePasswordForm', array( &$extraFields ) );
		foreach ( $extraFields as $extra ) {
			list( $name, $label, $type, $default ) = $extra;
			$fields[$name] = array(
				'type' => $type,
				'name' => $name,
				'label-message' => $label,
				'default' => $default,
			);
		}

		return $fields;
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setAction( $this->getFullTitle()->getLocalURL( array( 'token' => $this->token ) ) );
		$form->setId( 'mw-resetpass-form' );
		$form->setTableId( 'mw-resetpass-table' );
		$form->setWrapperLegendMsg( 'resetpass_header' );
		$form->setSubmitTextMsg(
			$this->getUser()->isLoggedIn()
				? 'resetpass-submit-loggedin'
				: 'resetpass_submit'
		);
		$form->addButton( 'wpCancel',  $this->msg( 'resetpass-submit-cancel' )->text() );
		$form->setHeaderText( $this->msg( 'resetpass_text' )->parseAsBlock() );
		$form->addHiddenFields(
			$this->getRequest()->getValues( 'wpName', 'wpDomain', 'returnto', 'returntoquery' ) );
	}

	public function onSubmit( array $data ) {
		global $wgPasswordAttemptThrottle;

		$request = $this->getRequest();
		if ( $request->getCheck( 'wpCancel' ) ) {
			$titleObj = Title::newFromText( $request->getVal( 'returnto' ) );
			if ( !$titleObj instanceof Title ) {
				$titleObj = Title::newMainPage();
			}
			$query = $request->getVal( 'returntoquery' );
			$this->getOutput()->redirect( $titleObj->getFullURL( $query ) );

			return true;
		}

		$oldPass = $data['Password'];
		$newPass = $data['NewPassword'];

		$abortMsg = 'resetpass-abort-generic';
		$status = 'success';
		$retval = true;

		if ( $newPass !== $data['Retype'] ) {
			$status = 'badretype';
			$retval =  array( 'badretype' );
		} elseif ( LoginForm::incLoginThrottle( $this->user->getName() ) === true ) {
			$status = 'throttled';
			$retval = array( $this->msg( 'login-throttled' ),
				$this->getLanguage()->formatDuration( $wgPasswordAttemptThrottle['seconds'] )
			);
		} elseif ( !wfRunHooks( 'AbortChangePassword', array( $this->user, $oldPass, $newPass, &$abortMsg ) ) ) {
			$status = 'abortreset';
			$retval = array( $abortMsg );
		} elseif ( !$this->user->checkPassword( $oldPass ) && !$this->user->checkTemporaryPassword( $this->token ) ) {
			$status = 'wrongpassword';
			$retval = array( 'resetpass-wrong-oldpass' );
		} else {
			try {
				$this->user->setPassword( $newPass );
				$this->user->saveSettings();
				LoginForm::loginUser( $this->getContext(), $this->user, $data['ForceHttps'] );
			} catch ( PasswordError $e ) {
				$status = 'error';
				$retval = $e->getMessage();
			}

			LoginForm::clearLoginThrottle( $this->user->getName() );
		}

		wfRunHooks( 'PrefsPasswordAudit', array( $this->user, $newPass, $status ) );

		return $retval;
	}

	public function onSuccess() {
		$this->getOutput()->wrapWikiMsg(
			"<div class=\"successbox\">\n$1\n</div>",
			'changepassword-success'
		);
		$this->getOutput()->returnToMain();
	}

	public function requiresUnblock() {
		return false;
	}

	protected function getGroupName() {
		return 'users';
	}
}
