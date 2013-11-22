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
class SpecialChangePassword extends UnlistedSpecialPage {

	protected $mUserName, $mOldpass, $mNewpass, $mRetype, $mDomain;

	public function __construct() {
		parent::__construct( 'ChangePassword', 'editmyprivateinfo' );
	}

	/**
	 * Main execution point
	 */
	function execute( $par ) {
		global $wgAuth;

		$this->setHeaders();
		$this->outputHeader();
		$this->getOutput()->disallowUserJs();

		$request = $this->getRequest();
		$this->mUserName = trim( $request->getVal( 'wpName' ) );
		$this->mOldpass = $request->getVal( 'wpPassword' );
		$this->mNewpass = $request->getVal( 'wpNewPassword' );
		$this->mRetype = $request->getVal( 'wpRetype' );
		$this->mDomain = $request->getVal( 'wpDomain' );

		$user = $this->getUser();
		if ( !$request->wasPosted() && !$user->isLoggedIn() ) {
			$this->error( $this->msg( 'resetpass-no-info' )->text() );

			return;
		}

		if ( $request->wasPosted() && $request->getBool( 'wpCancel' ) ) {
			$titleObj = Title::newFromText( $request->getVal( 'returnto' ) );
			if ( !$titleObj instanceof Title ) {
				$titleObj = Title::newMainPage();
			}
			$query = $request->getVal( 'returntoquery' );
			$this->getOutput()->redirect( $titleObj->getFullURL( $query ) );

			return;
		}

		$this->checkReadOnly();
		$this->checkPermissions();

		if ( $request->wasPosted() && $user->matchEditToken( $request->getVal( 'token' ) ) ) {
			try {
				$this->mDomain = $wgAuth->getDomain();
				if ( !$wgAuth->allowPasswordChange() ) {
					$this->error( $this->msg( 'resetpass_forbidden' )->text() );

					return;
				}

				$this->attemptReset( $this->mNewpass, $this->mRetype );

				if ( $user->isLoggedIn() ) {
					$this->getOutput()->wrapWikiMsg(
							"<div class=\"successbox\">\n$1\n</div>",
							'changepassword-success'
					);
					$this->getOutput()->returnToMain();
				} else {
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

				return;
			} catch ( PasswordError $e ) {
				$this->error( $e->getMessage() );
			}
		}
		$this->showForm();
	}

	/**
	 * @param $msg string
	 */
	function error( $msg ) {
		$this->getOutput()->addHTML( Xml::element( 'p', array( 'class' => 'error' ), $msg ) );
	}

	function showForm() {
		global $wgCookieExpiration;

		$user = $this->getUser();
		if ( !$this->mUserName ) {
			$this->mUserName = $user->getName();
		}
		$rememberMe = '';
		if ( !$user->isLoggedIn() ) {
			$rememberMe = '<tr>' .
				'<td></td>' .
				'<td class="mw-input">' .
				Xml::checkLabel(
					$this->msg( 'remembermypassword' )->numParams( ceil( $wgCookieExpiration / ( 3600 * 24 ) ) )->text(),
					'wpRemember', 'wpRemember',
					$this->getRequest()->getCheck( 'wpRemember' ) ) .
				'</td>' .
				'</tr>';
			$submitMsg = 'resetpass_submit';
			$oldpassMsg = 'resetpass-temp-password';
		} else {
			$oldpassMsg = 'oldpassword';
			$submitMsg = 'resetpass-submit-loggedin';
		}
		$extraFields = array();
		wfRunHooks( 'ChangePasswordForm', array( &$extraFields ) );
		$prettyFields = array(
			array( 'wpName', 'username', 'text', $this->mUserName ),
			array( 'wpPassword', $oldpassMsg, 'password', $this->mOldpass ),
			array( 'wpNewPassword', 'newpassword', 'password', null ),
			array( 'wpRetype', 'retypenew', 'password', null ),
		);
		$prettyFields = array_merge( $prettyFields, $extraFields );
		$hiddenFields = array(
			'token' => $user->getEditToken(),
			'wpName' => $this->mUserName,
			'wpDomain' => $this->mDomain,
		) + $this->getRequest()->getValues( 'returnto', 'returntoquery' );
		$hiddenFieldsStr = '';
		foreach ( $hiddenFields as $fieldname => $fieldvalue ) {
			$hiddenFieldsStr .= Html::hidden( $fieldname, $fieldvalue ) . "\n";
		}
		$this->getOutput()->addHTML(
			Xml::fieldset( $this->msg( 'resetpass_header' )->text() ) .
				Xml::openElement( 'form',
					array(
						'method' => 'post',
						'action' => $this->getTitle()->getLocalURL(),
						'id' => 'mw-resetpass-form' ) ) . "\n" .
				$hiddenFieldsStr .
				$this->msg( 'resetpass_text' )->parseAsBlock() . "\n" .
				Xml::openElement( 'table', array( 'id' => 'mw-resetpass-table' ) ) . "\n" .
				$this->pretty( $prettyFields ) . "\n" .
				$rememberMe .
				"<tr>\n" .
				"<td></td>\n" .
				'<td class="mw-input">' .
				Xml::submitButton( $this->msg( $submitMsg )->text() ) .
				Xml::submitButton( $this->msg( 'resetpass-submit-cancel' )->text(), array( 'name' => 'wpCancel' ) ) .
				"</td>\n" .
				"</tr>\n" .
				Xml::closeElement( 'table' ) .
				Xml::closeElement( 'form' ) .
				Xml::closeElement( 'fieldset' ) . "\n"
		);
	}

	/**
	 * @param $fields array
	 * @return string
	 */
	function pretty( $fields ) {
		$out = '';
		foreach ( $fields as $list ) {
			list( $name, $label, $type, $value ) = $list;
			if ( $type == 'text' ) {
				$field = htmlspecialchars( $value );
			} else {
				$attribs = array( 'id' => $name );
				if ( $name == 'wpNewPassword' || $name == 'wpRetype' ) {
					$attribs = array_merge( $attribs,
						User::passwordChangeInputAttribs() );
				}
				if ( $name == 'wpPassword' ) {
					$attribs[] = 'autofocus';
				}
				$field = Html::input( $name, $value, $type, $attribs );
			}
			$out .= "<tr>\n";
			$out .= "\t<td class='mw-label'>";

			if ( $type != 'text' ) {
				$out .= Xml::label( $this->msg( $label )->text(), $name );
			} else {
				$out .= $this->msg( $label )->escaped();
			}

			$out .= "</td>\n";
			$out .= "\t<td class='mw-input'>";
			$out .= $field;
			$out .= "</td>\n";
			$out .= "</tr>";
		}

		return $out;
	}

	/**
	 * @throws PasswordError when cannot set the new password because requirements not met.
	 */
	protected function attemptReset( $newpass, $retype ) {
		global $wgPasswordAttemptThrottle;

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
			wfRunHooks( 'PrefsPasswordAudit', array( $user, $newpass, 'badretype' ) );
			throw new PasswordError( $this->msg( 'badretype' )->text() );
		}

		$throttleCount = LoginForm::incLoginThrottle( $this->mUserName );
		if ( $throttleCount === true ) {
			$lang = $this->getLanguage();
			throw new PasswordError( $this->msg( 'login-throttled' )
				->params( $lang->formatDuration( $wgPasswordAttemptThrottle['seconds'] ) )
				->text()
			);
		}

		$abortMsg = 'resetpass-abort-generic';
		if ( !wfRunHooks( 'AbortChangePassword', array( $user, $this->mOldpass, $newpass, &$abortMsg ) ) ) {
			wfRunHooks( 'PrefsPasswordAudit', array( $user, $newpass, 'abortreset' ) );
			throw new PasswordError( $this->msg( $abortMsg )->text() );
		}

		if ( !$user->checkTemporaryPassword( $this->mOldpass ) && !$user->checkPassword( $this->mOldpass ) ) {
			wfRunHooks( 'PrefsPasswordAudit', array( $user, $newpass, 'wrongpassword' ) );
			throw new PasswordError( $this->msg( 'resetpass-wrong-oldpass' )->text() );
		}

		// Please reset throttle for successful logins, thanks!
		if ( $throttleCount ) {
			LoginForm::clearLoginThrottle( $this->mUserName );
		}

		try {
			$user->setPassword( $this->mNewpass );
			wfRunHooks( 'PrefsPasswordAudit', array( $user, $newpass, 'success' ) );
			$this->mNewpass = $this->mOldpass = $this->mRetype = '';
		} catch ( PasswordError $e ) {
			wfRunHooks( 'PrefsPasswordAudit', array( $user, $newpass, 'error' ) );
			throw new PasswordError( $e->getMessage() );
		}

		if ( $isSelf ) {
			// This is needed to keep the user connected since
			// changing the password also modifies the user's token.
			$user->setCookies();
		}

		$user->saveSettings();
	}

	protected function getGroupName() {
		return 'users';
	}
}
