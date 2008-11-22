<?php
/**
 * @file
 * @ingroup SpecialPage
 */

/** Constructor */
function wfSpecialResetpass( $par ) {
	$form = new PasswordResetForm();
	$form->execute( $par );
}

/**
 * Let users recover their password.
 * @ingroup SpecialPage
 */
class PasswordResetForm extends SpecialPage {
	function __construct( $name=null, $reset=null ) {
		if( $name !== null ) {
			$this->mName = $name;
			$this->mOldpass = $reset;
		} else {
			global $wgRequest;
			$this->mName = $wgRequest->getVal( 'wpName' );
			$this->mOldpass = $wgRequest->getVal( 'wpPassword' );
		}
	}

	/**
	 * Main execution point
	 */
	function execute( $par ) {
		global $wgUser, $wgAuth, $wgOut, $wgRequest;

		if( !$wgAuth->allowPasswordChange() ) {
			$this->error( wfMsg( 'resetpass_forbidden' ) );
			return;
		}

		if( !$wgRequest->wasPosted() && !$wgUser->isLoggedIn() ) {
			$this->error( wfMsg( 'resetpass-no-info' ) );
			return;
		}

		if( $wgRequest->wasPosted() && $wgUser->matchEditToken( $wgRequest->getVal( 'token' ) ) ) {
			$newpass = $wgRequest->getVal( 'wpNewPassword' );
			$retype = $wgRequest->getVal( 'wpRetype' );
			try {
				$this->attemptReset( $newpass, $retype );
				$wgOut->addWikiMsg( 'resetpass_success' );
				if( !$wgUser->isLoggedIn() ) {
					$data = array(
						'action' => 'submitlogin',
						'wpName' => $this->mName,
						'wpPassword' => $newpass,
						'returnto' => $wgRequest->getVal( 'returnto' ),
					);
					if( $wgRequest->getCheck( 'wpRemember' ) ) {
						$data['wpRemember'] = 1;
					}
					$login = new LoginForm( new FauxRequest( $data, true ) );
					$login->execute();
				}
				$titleObj = Title::newFromText( $wgRequest->getVal( 'returnto' ) );
				if ( !$titleObj instanceof Title ) {
					$titleObj = Title::newMainPage();
				}
				$wgOut->redirect( $titleObj->getFullURL() );
			} catch( PasswordError $e ) {
				$this->error( $e->getMessage() );
			}
		}
		$this->showForm();
	}

	function error( $msg ) {
		global $wgOut;
		$wgOut->addHTML( Xml::element('p', array( 'class' => 'error' ), $msg ) );
	}

	function showForm() {
		global $wgOut, $wgUser, $wgRequest;

		$wgOut->disallowUserJs();

		$self = SpecialPage::getTitleFor( 'Resetpass' );
		if ( !$this->mName ) {
			$this->mName = $wgUser->getName();
		}
		$rememberMe = '';
		if ( !$wgUser->isLoggedIn() ) {
			$rememberMe = '<tr>' .
				'<td></td>' .
				'<td>' .
					Xml::checkLabel( wfMsg( 'remembermypassword' ),
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
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, wfMsg( 'resetpass_header' ) ) .
			Xml::openElement( 'form',
				array(
					'method' => 'post',
					'action' => $self->getLocalUrl(),
					'id' => 'mw-resetpass-form' ) ) .	
			Xml::hidden( 'token', $wgUser->editToken() ) .
			Xml::hidden( 'wpName', $this->mName ) .
			Xml::hidden( 'returnto', $wgRequest->getVal( 'returnto' ) ) .
			wfMsgExt( 'resetpass_text', array( 'parse' ) ) .
			'<table>' .
			$this->pretty( array(
				array( 'wpName', 'username', 'text', $this->mName ),
				array( 'wpPassword', $oldpassMsg, 'password', $this->mOldpass ),
				array( 'wpNewPassword', 'newpassword', 'password', '' ),
				array( 'wpRetype', 'yourpasswordagain', 'password', '' ),
			) ) .
			$rememberMe .
			'<tr>' .
				'<td></td>' .
				'<td>' .
					wfSubmitButton( wfMsgHtml( $submitMsg ) ) .
				'</td>' .
			'</tr>' .
			'</table>' .
			Xml::closeElement( 'form' ) .
			Xml::closeElement( 'fieldset' ) );
	}

	function pretty( $fields ) {
		$out = '';
		foreach( $fields as $list ) {
			list( $name, $label, $type, $value ) = $list;
			if( $type == 'text' ) {
				$field = htmlspecialchars( $value );
			} else {
				$field = Xml::input( $name, 20, $value,
					array( 'id' => $name, 'type' => $type ) );
			}
			$out .= '<tr>';
			$out .= "<td class='mw-label'>";
			if ( $type != 'text' )
				$out .= Xml::label( wfMsg( $label ), $name );
			else 
				$out .=  wfMsg( $label );
			$out .= '</td>';
			$out .= "<td class='mw-input'>";
			$out .= $field;
			$out .= '</td>';
			$out .= '</tr>';
		}
		return $out;
	}

	/**
	 * @throws PasswordError when cannot set the new password because requirements not met.
	 */
	function attemptReset( $newpass, $retype ) {
		$user = User::newFromName( $this->mName );
		if( $user->isAnon() ) {
			throw new PasswordError( 'no such user' );
		}

		if( !$user->checkTemporaryPassword( $this->mOldpass ) && !$user->checkPassword( $this->mOldpass ) ) {
			throw new PasswordError( wfMsg( 'resetpass-wrong-oldpass' ) );
		}

		if( $newpass !== $retype ) {
			throw new PasswordError( wfMsg( 'badretype' ) );
		}

		$user->setPassword( $newpass );
		$user->setCookies();
		$user->saveSettings();
	}
}
