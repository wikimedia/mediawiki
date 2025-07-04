<?php
/**
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
 */

namespace MediaWiki\Specials;

use MediaWiki\Exception\PermissionsError;
use MediaWiki\Exception\ReadOnlyError;
use MediaWiki\Exception\UserNotLoggedIn;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Language\RawMessage;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\SpecialPage\UnlistedSpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use Profiler;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\ScopedCallback;

/**
 * Email confirmation for registered users.
 *
 * This page responds to the link with the confirmation code
 * that is sent in the confirmation email.
 *
 * This page can also be accessed directly at any later time
 * to re-send the confirmation email.
 *
 * @ingroup SpecialPage
 * @author Brooke Vibber
 * @author Rob Church <robchur@gmail.com>
 */
class SpecialConfirmEmail extends UnlistedSpecialPage {

	private UserFactory $userFactory;

	public function __construct( UserFactory $userFactory ) {
		parent::__construct( 'Confirmemail', 'editmyprivateinfo' );

		$this->userFactory = $userFactory;
	}

	/** @inheritDoc */
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
	public function execute( $code ) {
		// Ignore things like primary queries/connections on GET requests.
		// It's very convenient to just allow formless link usage.
		$trxProfiler = Profiler::instance()->getTransactionProfiler();

		$this->setHeaders();
		$this->checkReadOnly();
		$this->checkPermissions();

		// This could also let someone check the current email address, so
		// require both permissions.
		if ( !$this->getAuthority()->isAllowed( 'viewmyprivateinfo' ) ) {
			throw new PermissionsError( 'viewmyprivateinfo' );
		}

		if ( $code === null || $code === '' ) {
			$this->requireNamedUser( 'confirmemail_needlogin', 'exception-nologin', true );
			if ( Sanitizer::validateEmail( $this->getUser()->getEmail() ) ) {
				$this->showRequestForm();
			} else {
				$this->getOutput()->addWikiMsg( 'confirmemail_noemail' );
			}
		} else {
			$scope = $trxProfiler->silenceForScope( $trxProfiler::EXPECTATION_REPLICAS_ONLY );
			$this->attemptConfirm( $code );
			ScopedCallback::consume( $scope );
		}
	}

	/**
	 * Show a nice form for the user to request a confirmation mail
	 */
	private function showRequestForm() {
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
				->setAction( $this->getPageTitle()->getLocalURL() )
				->setSubmitTextMsg( 'confirmemail_send' )
				->setSubmitCallback( $this->submitSend( ... ) );

			$retval = $form->show();

			if ( $retval === true || ( $retval instanceof Status && $retval->isGood() ) ) {
				$out->addWikiMsg( 'confirmemail_sent' );
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
	private function submitSend() {
		$status = $this->getUser()->sendConfirmationMail();
		if ( $status->isGood() ) {
			return Status::newGood();
		} else {
			return Status::newFatal( new RawMessage(
				$status->getWikiText( 'confirmemail_sendfailed', false, $this->getLanguage() )
			) );
		}
	}

	/**
	 * Attempt to confirm the user's email address and show success or failure
	 * as needed; if successful, take the user to log in
	 *
	 * @param string $code Confirmation code
	 */
	private function attemptConfirm( $code ) {
		$user = $this->userFactory->newFromConfirmationCode(
			$code,
			IDBAccessObject::READ_LATEST
		);

		if ( !is_object( $user ) ) {
			if ( User::isWellFormedConfirmationToken( $code ) ) {
				$this->getOutput()->addWikiMsg( 'confirmemail_invalid' );
			} else {
				$this->getOutput()->addWikiMsg( 'confirmemail_invalid_format' );
			}

			return;
		}

		// Enforce permissions, user blocks, and rate limits
		$this->authorizeAction( 'confirmemail' )->throwErrorPageError();

		$userLatest = $user->getInstanceForUpdate();
		$userLatest->confirmEmail();
		$userLatest->saveSettings();
		$message = $this->getUser()->isNamed() ? 'confirmemail_loggedin' : 'confirmemail_success';
		$this->getOutput()->addWikiMsg( $message );

		if ( !$this->getUser()->isNamed() ) {
			$title = SpecialPage::getTitleFor( 'Userlogin' );
			$this->getOutput()->returnToMain( true, $title );
		}
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialConfirmEmail::class, 'SpecialConfirmEmail' );
