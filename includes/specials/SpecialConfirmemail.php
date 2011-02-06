<?php
/**
 * Implements Special:Confirmemail and Special:Invalidateemail
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
 * Special page allows users to request email confirmation message, and handles
 * processing of the confirmation code when the link in the email is followed
 *
 * @ingroup SpecialPage
 * @author Brion Vibber
 * @author Rob Church <robchur@gmail.com>
 */
class EmailConfirmation extends UnlistedSpecialPage {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( 'Confirmemail' );
	}

	/**
	 * Main execution point
	 *
	 * @param $code Confirmation code passed to the page
	 */
	function execute( $code ) {
		global $wgUser, $wgOut;
		$this->setHeaders();
		
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
		
		if( empty( $code ) ) {
			if( $wgUser->isLoggedIn() ) {
				if( User::isValidEmailAddr( $wgUser->getEmail() ) ) {
					$this->showRequestForm();
				} else {
					$wgOut->addWikiMsg( 'confirmemail_noemail' );
				}
			} else {
				$title = SpecialPage::getTitleFor( 'Userlogin' );
				$skin = $wgUser->getSkin();
				$llink = $skin->linkKnown(
					$title,
					wfMsgHtml( 'loginreqlink' ),
					array(),
					array( 'returnto' => $this->getTitle()->getPrefixedText() )
				);
				$wgOut->addWikiMsgArray( 'confirmemail_needlogin', array( $llink ), array( 'replaceafter' ) );
			}
		} else {
			$this->attemptConfirm( $code );
		}
	}

	/**
	 * Show a nice form for the user to request a confirmation mail
	 */
	function showRequestForm() {
		global $wgOut, $wgUser, $wgLang, $wgRequest;
		if( $wgRequest->wasPosted() && $wgUser->matchEditToken( $wgRequest->getText( 'token' ) ) ) {
			$status = $wgUser->sendConfirmationMail();
			if ( $status->isGood() ) {
				$wgOut->addWikiMsg( 'confirmemail_sent' );
			} else {
				$wgOut->addWikiText( $status->getWikiText( 'confirmemail_sendfailed' ) );
			}
		} else {
			if( $wgUser->isEmailConfirmed() ) {
				// date and time are separate parameters to facilitate localisation.
				// $time is kept for backward compat reasons.
				// 'emailauthenticated' is also used in SpecialPreferences.php
				$time = $wgLang->timeAndDate( $wgUser->mEmailAuthenticated, true );
				$d = $wgLang->date( $wgUser->mEmailAuthenticated, true );
				$t = $wgLang->time( $wgUser->mEmailAuthenticated, true );
				$wgOut->addWikiMsg( 'emailauthenticated', $time, $d, $t );
			}
			if( $wgUser->isEmailConfirmationPending() ) {
				$wgOut->wrapWikiMsg( "<div class=\"error mw-confirmemail-pending\">\n$1\n</div>", 'confirmemail_pending' );
			}
			$wgOut->addWikiMsg( 'confirmemail_text' );
			$form  = Xml::openElement( 'form', array( 'method' => 'post', 'action' => $this->getTitle()->getLocalUrl() ) );
			$form .= Html::hidden( 'token', $wgUser->editToken() );
			$form .= Xml::submitButton( wfMsg( 'confirmemail_send' ) );
			$form .= Xml::closeElement( 'form' );
			$wgOut->addHTML( $form );
		}
	}

	/**
	 * Attempt to confirm the user's email address and show success or failure
	 * as needed; if successful, take the user to log in
	 *
	 * @param $code Confirmation code
	 */
	function attemptConfirm( $code ) {
		global $wgUser, $wgOut;
		$user = User::newFromConfirmationCode( $code );
		if( is_object( $user ) ) {
			$user->confirmEmail();
			$user->saveSettings();
			$message = $wgUser->isLoggedIn() ? 'confirmemail_loggedin' : 'confirmemail_success';
			$wgOut->addWikiMsg( $message );
			if( !$wgUser->isLoggedIn() ) {
				$title = SpecialPage::getTitleFor( 'Userlogin' );
				$wgOut->returnToMain( true, $title );
			}
		} else {
			$wgOut->addWikiMsg( 'confirmemail_invalid' );
		}
	}

}

/**
 * Special page allows users to cancel an email confirmation using the e-mail
 * confirmation code
 *
 * @ingroup SpecialPage
 */
class EmailInvalidation extends UnlistedSpecialPage {

	public function __construct() {
		parent::__construct( 'Invalidateemail' );
	}

	function execute( $code ) {
		$this->setHeaders();

		if ( wfReadOnly() ) {
			global $wgOut;         
			$wgOut->readOnlyPage();
			return;
		}
		
		$this->attemptInvalidate( $code );
	}

	/**
	 * Attempt to invalidate the user's email address and show success or failure
	 * as needed; if successful, link to main page
	 *
	 * @param $code Confirmation code
	 */
	function attemptInvalidate( $code ) {
		global $wgUser, $wgOut;
		$user = User::newFromConfirmationCode( $code );
		if( is_object( $user ) ) {
			$user->invalidateEmail();
			$user->saveSettings();
			$wgOut->addWikiMsg( 'confirmemail_invalidated' );
			if( !$wgUser->isLoggedIn() ) {
				$wgOut->returnToMain();
			}
		} else {
			$wgOut->addWikiMsg( 'confirmemail_invalid' );
		}
	}
}
