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

	private $mSelfChange = true; // Usually, but sometimes not :)

	public function __construct() {
		parent::__construct( 'Resetpass' );
	}

	/**
	 * Main execution point
	 */
	function execute( $par ) {
		global $wgUser, $wgAuth, $wgOut, $wgRequest;

		$this->mUserName = $wgRequest->getVal( 'wpName', $par );
		$this->mOldpass = $wgRequest->getVal( 'wpPassword' );
		$this->mNewpass = $wgRequest->getVal( 'wpNewPassword' );
		$this->mRetype = $wgRequest->getVal( 'wpRetype' );
		
		$this->setHeaders();
		$this->outputHeader();

		if( !$wgAuth->allowPasswordChange() ) {
			$this->error( wfMsg( 'resetpass_forbidden' ) );
			return;
		}
		
		// Default to our own username when not given one
		if ( !$this->mUserName ) {
			$this->mUserName = $wgUser->getName();
		}
		
		// Are we changing our own?
		if ( $wgUser->getName() != $this->mUserName  ) {
			$this->mSelfChange = false; // We're changing someone else
		}

		if( !$wgRequest->wasPosted() && !$wgUser->isLoggedIn() ) {
			$this->error( wfMsg( 'resetpass-no-info' ) );
			return;
		}

		if ( !$this->mSelfChange && !$wgUser->isAllowed( 'reset-passwords' ) ) {
			$this->error( wfMsg( 'resetpass-no-others' ) );
			return;
		}

		if( $wgRequest->wasPosted() && $wgUser->matchEditToken( $wgRequest->getVal('token') ) ) {
			try {
				$this->attemptReset( $this->mNewpass, $this->mRetype );
				$wgOut->addWikiMsg( 'resetpass_success' );
				// Only attempt this login session if we're changing our own password
				if( $this->mSelfChange && !$wgUser->isLoggedIn() ) {
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

		$rememberMe = '';
		if ( !$wgUser->isLoggedIn() ) {
			$rememberMe = '<tr>' .
				'<td></td>' .
				'<td class="mw-input">' .
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
			Xml::fieldset( wfMsg( 'resetpass_header' ) ) .
			Xml::openElement( 'form',
				array(
					'method' => 'post',
					'action' => $self->getLocalUrl(),
					'id' => 'mw-resetpass-form' ) ) .	
			Xml::hidden( 'token', $wgUser->editToken() ) .
			Xml::hidden( 'returnto', $wgRequest->getVal( 'returnto' ) ) .
			wfMsgExt( 'resetpass_text', array( 'parse' ) ) .
			Xml::openElement( 'table', array( 'id' => 'mw-resetpass-table' ) ) .
			$this->pretty( array(
				array( 'wpName', 'username', 'text', $this->mUserName, $wgUser->isAllowed( 'reset-passwords' ) ),
				array( 'wpPassword', $oldpassMsg, 'password', $this->mOldpass, $this->mSelfChange ),
				array( 'wpNewPassword', 'newpassword', 'password', '', true ),
				array( 'wpRetype', 'retypenew', 'password', '', true ),
			) ) .
			$rememberMe .
			'<tr>' .
				'<td></td>' .
				'<td class="mw-input">' .
					Xml::submitButton( wfMsg( $submitMsg ) ) .
				'</td>' .
			'</tr>' .
			Xml::closeElement( 'table' ) .
			Xml::closeElement( 'form' ) .
			Xml::closeElement( 'fieldset' )
		);
	}

	function pretty( $fields ) {
		$out = '';
		foreach( $fields as $list ) {
			list( $name, $label, $type, $value, $enabled ) = $list;
			$params = array( 'id' => $name, 'type' => $type );
			if ( !$enabled )
				$params['disabled'] = 'disabled';
			$field = Xml::input( $name, 20, $value, $params );
			$out .= '<tr>';
			$out .= '<td class="mw-label">';
			$out .= Xml::label( wfMsg( $label ), $name );
			$out .= '</td>';
			$out .= '<td class="mw-input">';
			$out .= $field;
			$out .= '</td>';
			$out .= '</tr>';
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

		if ( $this->mSelfChange ) {
			if( !$user->checkTemporaryPassword($this->mOldpass) && !$user->checkPassword($this->mOldpass) ) {
				wfRunHooks( 'PrefsPasswordAudit', array( $user, $newpass, 'wrongpassword' ) );
				throw new PasswordError( wfMsg( 'resetpass-wrong-oldpass' ) );
			}
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
