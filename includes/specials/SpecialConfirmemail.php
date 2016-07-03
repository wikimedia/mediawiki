<?php
/**
 * Implements Special:Confirmemail
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
	public function __construct() {
		parent::__construct( 'Confirmemail', 'editmyprivateinfo' );
	}

	public function doesWrites() {
		return true;
	}

	/**
	 * Main execution point
	 *
	 * @param null|string $code Confirmation code passed to the page
	 * @throws PermissionsError
	 * @throws ReadOnlyError
	 * @throws UserNotLoggedIn
	 */
	function execute( $code ) {
		// Ignore things like master queries/connections on GET requests.
		// It's very convenient to just allow formless link usage.
		Profiler::instance()->getTransactionProfiler()->resetExpectations();

		$this->setHeaders();

		$this->checkReadOnly();
		$this->checkPermissions();

		// This could also let someone check the current email address, so
		// require both permissions.
		if ( !$this->getUser()->isAllowed( 'viewmyprivateinfo' ) ) {
			throw new PermissionsError( 'viewmyprivateinfo' );
		}

		if ( $code === null || $code === '' ) {
			$this->requireLogin( 'confirmemail_needlogin' );
			if ( Sanitizer::validateEmail( $this->getUser()->getEmail() ) ) {
				$this->showRequestForm();
			} else {
				$this->getOutput()->addWikiMsg( 'confirmemail_noemail' );
			}
		} else {
			$this->attemptConfirm( $code );
		}
	}

	/**
	 * Show a nice form for the user to request a confirmation mail
	 */
	function showRequestForm() {
		$user = $this->getUser();
		$out = $this->getOutput();

		if ( !$user->isEmailConfirmed() ) {
			$descriptor = [];
			if ( $user->isEmailConfirmationPending() ) {
				$descriptor += [
					'pending' => [
						'type' => 'info',
						'raw' => true,
						'default' => "<div class=\"error mw-confirmemail-pending\">\n" .
							$this->msg( 'confirmemail_pending' )->escaped() .
							"\n</div>",
					],
				];
			}

			$out->addWikiMsg( 'confirmemail_text' );
			$form = HTMLForm::factory( 'ooui', $descriptor, $this->getContext() );
			$form
				->setMethod( 'post' )
				->setAction( $this->getPageTitle()->getLocalURL() )
				->setSubmitTextMsg( 'confirmemail_send' )
				->setSubmitCallback( [ $this, 'submitSend' ] );

			$retval = $form->show();

			if ( $retval === true ) {
				// should never happen, but if so, don't let the user without any message
				$out->addWikiMsg( 'confirmemail_sent' );
			} elseif ( $retval instanceof Status && $retval->isGood() ) {
				$out->addWikiText( $retval->getValue() );
			}
		} else {
			// date and time are separate parameters to facilitate localisation.
			// $time is kept for backward compat reasons.
			// 'emailauthenticated' is also used in SpecialPreferences.php
			$lang = $this->getLanguage();
			$emailAuthenticated = $user->getEmailAuthenticationTimestamp();
			$time = $lang->userTimeAndDate( $emailAuthenticated, $user );
			$d = $lang->userDate( $emailAuthenticated, $user );
			$t = $lang->userTime( $emailAuthenticated, $user );
			$out->addWikiMsg( 'emailauthenticated', $time, $d, $t );
		}
	}

	/**
	 * Callback for HTMLForm send confirmation mail.
	 *
	 * @return Status Status object with the result
	 */
	public function submitSend() {
		$status = $this->getUser()->sendConfirmationMail();
		if ( $status->isGood() ) {
			return Status::newGood( $this->msg( 'confirmemail_sent' )->text() );
		} else {
			return Status::newFatal( new RawMessage(
				$status->getWikiText( 'confirmemail_sendfailed' )
			) );
		}
	}

	/**
	 * Attempt to confirm the user's email address and show success or failure
	 * as needed; if successful, take the user to log in
	 *
	 * @param string $code Confirmation code
	 */
	function attemptConfirm( $code ) {
		$user = User::newFromConfirmationCode( $code, User::READ_LATEST );
		if ( !is_object( $user ) ) {
			$this->getOutput()->addWikiMsg( 'confirmemail_invalid' );

			return;
		}

		$user->confirmEmail();
		$user->saveSettings();
		$message = $this->getUser()->isLoggedIn() ? 'confirmemail_loggedin' : 'confirmemail_success';
		$this->getOutput()->addWikiMsg( $message );

		if ( !$this->getUser()->isLoggedIn() ) {
			$title = SpecialPage::getTitleFor( 'Userlogin' );
			$this->getOutput()->returnToMain( true, $title );
		}
	}
}
