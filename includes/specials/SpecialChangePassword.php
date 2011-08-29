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
class SpecialChangePassword extends SpecialPage {
	public function __construct() {
		parent::__construct( 'ChangePassword' );
	}

	/**
	 * Main execution point
	 */
	function execute( $par ) {
		global $wgAuth;

		if ( wfReadOnly() ) {
			throw new ReadOnlyError;
		}

		$request = $this->getRequest();
		$this->mUserName = trim( $request->getVal( 'wpName' ) );
		$this->mOldpass = $request->getVal( 'wpPassword' );
		$this->mNewpass = $request->getVal( 'wpNewPassword' );
		$this->mRetype = $request->getVal( 'wpRetype' );
		$this->mDomain = $request->getVal( 'wpDomain' );

		$this->setHeaders();
		$this->outputHeader();
		$this->getOutput()->disallowUserJs();

		$user = $this->getUser();
		if( !$request->wasPosted() && !$user->isLoggedIn() ) {
			$this->error( wfMsg( 'resetpass-no-info' ) );
			return;
		}

		if( $request->wasPosted() && $request->getBool( 'wpCancel' ) ) {
			$this->doReturnTo();
			return;
		}

		if( $request->wasPosted() && $user->matchEditToken( $request->getVal( 'token' ) ) ) {
			try {
				$wgAuth->setDomain( $this->mDomain );
				if( !$wgAuth->allowPasswordChange() ) {
					$this->error( wfMsg( 'resetpass_forbidden' ) );
					return;
				}

				$this->attemptReset( $this->mNewpass, $this->mRetype );
				$this->getOutput()->addWikiMsg( 'resetpass_success' );
				if( !$user->isLoggedIn() ) {
					LoginForm::setLoginToken();
					$token = LoginForm::getLoginToken();
					$data = array(
						'action'       => 'submitlogin',
						'wpName'       => $this->mUserName,
						'wpDomain'     => $this->mDomain,
						'wpLoginToken' => $token,
						'wpPassword'   => $this->mNewpass,
						'returnto'     => $request->getVal( 'returnto' ),
					);
					if( $request->getCheck( 'wpRemember' ) ) {
						$data['wpRemember'] = 1;
					}
					$login = new LoginForm( new FauxRequest( $data, true ) );
					$login->execute( null );
				}
				$this->doReturnTo();
			} catch( PasswordError $e ) {
				$this->error( $e->getMessage() );
			}
		}
		$this->showForm();
	}

	function doReturnTo() {
		$titleObj = Title::newFromText( $this->getRequest()->getVal( 'returnto' ) );
		if ( !$titleObj instanceof Title ) {
			$titleObj = Title::newMainPage();
		}
		$this->getOutput()->redirect( $titleObj->getFullURL() );
	}

	function error( $msg ) {
		$this->getOutput()->addHTML( Xml::element('p', array( 'class' => 'error' ), $msg ) );
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
						wfMsgExt( 'remembermypassword', 'parsemag', $this->getLang()->formatNum( ceil( $wgCookieExpiration / ( 3600 * 24 ) ) ) ),
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
		$this->getOutput()->addHTML(
			Xml::fieldset( wfMsg( 'resetpass_header' ) ) .
			Xml::openElement( 'form',
				array(
					'method' => 'post',
					'action' => $this->getTitle()->getLocalUrl(),
					'id' => 'mw-resetpass-form' ) ) . "\n" .
			Html::hidden( 'token', $user->editToken() ) . "\n" .
			Html::hidden( 'wpName', $this->mUserName ) . "\n" .
			Html::hidden( 'wpDomain', $this->mDomain ) . "\n" .
			Html::hidden( 'returnto', $this->getRequest()->getVal( 'returnto' ) ) . "\n" .
			wfMsgExt( 'resetpass_text', array( 'parse' ) ) . "\n" .
			Xml::openElement( 'table', array( 'id' => 'mw-resetpass-table' ) ) . "\n" .
			$this->pretty( array(
				array( 'wpName', 'username', 'text', $this->mUserName ),
				array( 'wpPassword', $oldpassMsg, 'password', $this->mOldpass ),
				array( 'wpNewPassword', 'newpassword', 'password', null ),
				array( 'wpRetype', 'retypenew', 'password', null ),
			) ) . "\n" .
			$rememberMe .
			"<tr>\n" .
				"<td></td>\n" .
				'<td class="mw-input">' .
					Xml::submitButton( wfMsg( $submitMsg ) ) .
					Xml::submitButton( wfMsg( 'resetpass-submit-cancel' ), array( 'name' => 'wpCancel' ) ) .
				"</td>\n" .
			"</tr>\n" .
			Xml::closeElement( 'table' ) .
			Xml::closeElement( 'form' ) .
			Xml::closeElement( 'fieldset' ) . "\n"
		);
	}

	function pretty( $fields ) {
		$out = '';
		foreach ( $fields as $list ) {
			list( $name, $label, $type, $value ) = $list;
			if( $type == 'text' ) {
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
			if ( $type != 'text' )
				$out .= Xml::label( wfMsg( $label ), $name );
			else
				$out .=  wfMsgHtml( $label );
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
		$user = User::newFromName( $this->mUserName );
		if( !$user || $user->isAnon() ) {
			throw new PasswordError( wfMsg( 'nosuchusershort', $this->mUserName ) );
		}

		if( $newpass !== $retype ) {
			wfRunHooks( 'PrefsPasswordAudit', array( $user, $newpass, 'badretype' ) );
			throw new PasswordError( wfMsg( 'badretype' ) );
		}

		$throttleCount = LoginForm::incLoginThrottle( $this->mUserName );
		if ( $throttleCount === true ) {
			throw new PasswordError( wfMsg( 'login-throttled' ) );
		}

		if( !$user->checkTemporaryPassword($this->mOldpass) && !$user->checkPassword($this->mOldpass) ) {
			wfRunHooks( 'PrefsPasswordAudit', array( $user, $newpass, 'wrongpassword' ) );
			throw new PasswordError( wfMsg( 'resetpass-wrong-oldpass' ) );
		}

		// Please reset throttle for successful logins, thanks!
		if ( $throttleCount ) {
			LoginForm::clearLoginThrottle( $this->mUserName );
		}

		try {
			$user->setPassword( $this->mNewpass );
			wfRunHooks( 'PrefsPasswordAudit', array( $user, $newpass, 'success' ) );
			$this->mNewpass = $this->mOldpass = $this->mRetypePass = '';
		} catch( PasswordError $e ) {
			wfRunHooks( 'PrefsPasswordAudit', array( $user, $newpass, 'error' ) );
			throw new PasswordError( $e->getMessage() );
		}

		$user->setCookies();
		$user->saveSettings();
	}
}
