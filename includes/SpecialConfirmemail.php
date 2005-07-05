<?php
/**
 * Entry point to confirm a user's e-mail address.
 * When a new address is entered, a random unique code is generated and
 * mailed to the user. A clickable link to this page is provided.
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/** @todo document */
function wfSpecialConfirmemail( $code ) {
	$form = new ConfirmationForm();
	$form->show( $code );
}

/** @package MediaWiki */
class ConfirmationForm {
	/** */
	function show( $code ) {
		if( empty( $code ) ) {
			$this->showEmpty( $this->checkAndSend() );
		} else {
			$this->showCode( $code );
		}
	}

	/** */
	function showCode( $code ) {
		$user = User::newFromConfirmationCode( $code );
		if( is_null( $user ) ) {
			$this->showInvalidCode();
		} else {
			$this->confirmAndShow( $user );
		}
	}

	/** */	
	function confirmAndShow( $user ) {
		if( $user->confirmEmail() ) {
			$this->showSuccess();
		} else {
			$this->showError();
		}
	}

	/** */
	function checkAndSend() {
		global $wgUser, $wgRequest;
		if( $wgRequest->wasPosted() &&
			$wgUser->isLoggedIn() &&
			$wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ) ) ) {
			$result = $wgUser->sendConfirmationMail();
			if( WikiError::isError( $result ) ) {
				return 'confirmemail_sendfailed';
			} else {
				return 'confirmemail_sent';
			}
		} else {
			# boo
			return '';
		}
	}

	/** */
	function showEmpty( $err ) {
		require_once( 'templates/Confirmemail.php' );
		global $wgOut, $wgUser;
		
		$tpl = new ConfirmemailTemplate();
		$tpl->set( 'error', $err );
		$tpl->set( 'edittoken', $wgUser->editToken() );
		
		$title = Title::makeTitle( NS_SPECIAL, 'Confirmemail' );
		$tpl->set( 'action', $title->getLocalUrl() );
		
		
		$wgOut->addTemplate( $tpl );
	}

	/** */
	function showInvalidCode() {
		global $wgOut;
		$wgOut->addWikiText( wfMsg( 'confirmemail_invalid' ) );
	}

	/** */
	function showError() {
		global $wgOut;
		$wgOut->addWikiText( wfMsg( 'confirmemail_error' ) );
	}

	/** */
	function showSuccess() {
		global $wgOut, $wgRequest, $wgUser;
		
		if( $wgUser->isLoggedIn() ) {
			$wgOut->addWikiText( wfMsg( 'confirmemail_loggedin' ) );
		} else {
			$wgOut->addWikiText( wfMsg( 'confirmemail_success' ) );
			require_once( 'SpecialUserlogin.php' );
			$form = new LoginForm( $wgRequest );
			$form->execute();
		}
	}
}

?>
