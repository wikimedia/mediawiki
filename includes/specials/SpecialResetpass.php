<?php
/**
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
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

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

	/**
	 * Main execution point
	 */
	function execute( $par ) {
		global $wgUser, $wgAuth, $wgOut, $wgRequest;

		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}

		$this->mUserName = $wgRequest->getVal( 'wpName' );
		$this->mOldpass = $wgRequest->getVal( 'wpPassword' );
		$this->mNewpass = $wgRequest->getVal( 'wpNewPassword' );
		$this->mRetype = $wgRequest->getVal( 'wpRetype' );
		
		$this->setHeaders();
		$this->outputHeader();
		$wgOut->disallowUserJs();

		if( !$wgAuth->allowPasswordChange() ) {
			$this->error( wfMsg( 'resetpass_forbidden' ) );
			return;
		}

		if( !$wgRequest->wasPosted() && !$wgUser->isLoggedIn() ) {
			$this->error( wfMsg( 'resetpass-no-info' ) );
			return;
		}

		if( $wgRequest->wasPosted() && $wgRequest->getBool( 'wpCancel' ) ) {
			$this->doReturnTo();
			return;
		}

		if( $wgRequest->wasPosted() && $wgUser->matchEditToken( $wgRequest->getVal('token') ) ) {
			try {
				$this->attemptReset( $this->mNewpass, $this->mRetype );
				$wgOut->addWikiMsg( 'resetpass_success' );
				if( !$wgUser->isLoggedIn() ) {
					$data = array(
						'action'     => 'submitlogin',
						'wpName'     => $this->mUserName,
						'wpPassword' => $this->mNewpass,
						'returnto'   => $wgRequest->getVal( 'returnto' ),
					);
					if( $wgRequest->getCheck( 'wpRemember' ) ) {
						$data['wpRemember'] = 1;
					}
					$login = new LoginForm( new FauxRequest( $data, true ) );
					$login->execute();
				}
				$this->doReturnTo();
			} catch( PasswordError $e ) {
				$this->error( $e->getMessage() );
			}
		}
		$this->showForm();
	}
	
	function doReturnTo() {
		global $wgRequest, $wgOut;
		$titleObj = Title::newFromText( $wgRequest->getVal( 'returnto' ) );
		if ( !$titleObj instanceof Title ) {
			$titleObj = Title::newMainPage();
		}
		$wgOut->redirect( $titleObj->getFullURL() );
	}

	function error( $msg ) {
		global $wgOut;
		$wgOut->addHTML( Xml::element('p', array( 'class' => 'error' ), $msg ) );
	}

	function showForm() {
		global $wgOut, $wgUser, $wgRequest;

		$self = $this->getTitle();
		if ( !$this->mUserName ) {
			$this->mUserName = $wgUser->getName();
		}
		$rememberMe = '';
		if ( !$wgUser->isLoggedIn() ) {
			global $wgCookieExpiration, $wgLang;
			$rememberMe = '<tr>' .
				'<td></td>' .
				'<td class="mw-input">' .
					Xml::checkLabel( 
						wfMsgExt( 'remembermypassword', 'parsemag', $wgLang->formatNum( ceil( $wgCookieExpiration / ( 3600 * 24 ) ) ) ),
						'wpRemember', 'wpRemember',
						$wgRequest->getCheck( 'wpRemember' ) ) .
				'</td>' .
			'</tr>';
			$submitMsg = 'resetpass_submit';
			$oldpassMsg = 'resetpass-temp-password';
		} else {
			$oldpassMsg = 'oldpassword';
			$submitMsg = 'resetpass-submit-loggedin';
		}
		$wgOut->addHTML(
			Xml::fieldset( wfMsg( 'resetpass_header' ) ) .
			Xml::openElement( 'form',
				array(
					'method' => 'post',
					'action' => $self->getLocalUrl(),
					'id' => 'mw-resetpass-form' ) ) . "\n" .
			Xml::hidden( 'token', $wgUser->editToken() ) . "\n" .
			Xml::hidden( 'wpName', $this->mUserName ) . "\n" .
			Xml::hidden( 'returnto', $wgRequest->getVal( 'returnto' ) ) . "\n" .
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
			throw new PasswordError( 'no such user' );
		}
		
		if( $newpass !== $retype ) {
			wfRunHooks( 'PrefsPasswordAudit', array( $user, $newpass, 'badretype' ) );
			throw new PasswordError( wfMsg( 'badretype' ) );
		}

		if( !$user->checkTemporaryPassword($this->mOldpass) && !$user->checkPassword($this->mOldpass) ) {
			wfRunHooks( 'PrefsPasswordAudit', array( $user, $newpass, 'wrongpassword' ) );
			throw new PasswordError( wfMsg( 'resetpass-wrong-oldpass' ) );
		}
		
		try {
			$user->setPassword( $this->mNewpass );
			wfRunHooks( 'PrefsPasswordAudit', array( $user, $newpass, 'success' ) );
			$this->mNewpass = $this->mOldpass = $this->mRetypePass = '';
		} catch( PasswordError $e ) {
			wfRunHooks( 'PrefsPasswordAudit', array( $user, $newpass, 'error' ) );
			throw new PasswordError( $e->getMessage() );
			return;
		}
		
		$user->setCookies();
		$user->saveSettings();
	}
}
