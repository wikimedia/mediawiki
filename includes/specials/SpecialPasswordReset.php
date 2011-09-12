<?php
/**
 * Implements Special:Blankpage
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
 * Special page for requesting a password reset email
 *
 * @ingroup SpecialPage
 */
class SpecialPasswordReset extends FormSpecialPage {

	public function __construct() {
		parent::__construct( 'PasswordReset' );
	}

	public function userCanExecute( User $user ) {
		$error = $this->canChangePassword( $user );
		if ( is_string( $error ) ) {
			throw new ErrorPageError( 'internalerror', $error );
		} else if ( !$error ) {
			throw new ErrorPageError( 'internalerror', 'resetpass_forbidden' );
		}

		return parent::userCanExecute( $user );
	}

	protected function getFormFields() {
		global $wgPasswordResetRoutes, $wgAuth;
		$a = array();
		if ( isset( $wgPasswordResetRoutes['username'] ) && $wgPasswordResetRoutes['username'] ) {
			$a['Username'] = array(
				'type' => 'text',
				'label-message' => 'passwordreset-username',
			);
		}

		if ( isset( $wgPasswordResetRoutes['email'] ) && $wgPasswordResetRoutes['email'] ) {
			$a['Email'] = array(
				'type' => 'email',
				'label-message' => 'passwordreset-email',
			);
		}

		if ( isset( $wgPasswordResetRoutes['domain'] ) && $wgPasswordResetRoutes['domain'] ) {
			$domains = $wgAuth->domainList();
			$a['Domain'] = array(
				'type' => 'select',
				'options' => $domains,
				'label-message' => 'passwordreset-domain',
			);
		}

		return $a;
	}

	public function alterForm( HTMLForm $form ) {
		$form->setSubmitText( wfMessage( "mailmypassword" ) );
	}

	protected function preText() {
		global $wgPasswordResetRoutes;
		$i = 0;
		if ( isset( $wgPasswordResetRoutes['username'] ) && $wgPasswordResetRoutes['username'] ) {
			$i++;
		}
		if ( isset( $wgPasswordResetRoutes['email'] ) && $wgPasswordResetRoutes['email'] ) {
			$i++;
		}
		if ( isset( $wgPasswordResetRoutes['domain'] ) && $wgPasswordResetRoutes['domain'] ) {
			$i++;
		}
		return wfMessage( 'passwordreset-pretext', $i )->parseAsBlock();
	}

	/**
	 * Process the form.  At this point we know that the user passes all the criteria in
	 * userCanExecute(), and if the data array contains 'Username', etc, then Username
	 * resets are allowed.
	 * @param $data array
	 * @return Bool|Array
	 */
	public function onSubmit( array $data ) {
		global $wgAuth;

		if ( isset( $data['Domain'] ) ) {
			if ( $wgAuth->validDomain( $data['Domain'] ) ) {
				$wgAuth->setDomain( $data['Domain'] );
			} else {
				$wgAuth->setDomain( 'invaliddomain' );
			}
		}

		if ( isset( $data['Username'] ) && $data['Username'] !== '' ) {
			$method = 'username';
			$users = array( User::newFromName( $data['Username'] ) );
		} elseif ( isset( $data['Email'] )
			&& $data['Email'] !== ''
			&& Sanitizer::validateEmail( $data['Email'] ) )
		{
			$method = 'email';
			$res = wfGetDB( DB_SLAVE )->select(
				'user',
				'*',
				array( 'user_email' => $data['Email'] ),
				__METHOD__
			);
			if ( $res ) {
				$users = array();
				foreach( $res as $row ){
					$users[] = User::newFromRow( $row );
				}
			} else {
				// Some sort of database error, probably unreachable
				throw new MWException( 'Unknown database error in ' . __METHOD__ );
			}
		} else {
			// The user didn't supply any data
			return false;
		}

		// Check for hooks (captcha etc), and allow them to modify the users list
		$error = array();
		if ( !wfRunHooks( 'SpecialPasswordResetOnSubmit', array( &$users, $data, &$error ) ) ) {
			return array( $error );
		}

		if( count( $users ) == 0 ){
			if( $method == 'email' ){
				// Don't reveal whether or not an email address is in use
				return true;
			} else {
				return array( 'noname' );
			}
		}

		$firstUser = $users[0];

		if ( !$firstUser instanceof User || !$firstUser->getID() ) {
			return array( array( 'nosuchuser', $data['Username'] ) );
		}

		// Check against the rate limiter
		if ( $this->getUser()->pingLimiter( 'mailpassword' ) ) {
			throw new ThrottledError;
		}

		// Check against password throttle
		foreach ( $users as $user ) {
			if ( $user->isPasswordReminderThrottled() ) {
				global $wgPasswordReminderResendTime;
				# Round the time in hours to 3 d.p., in case someone is specifying
				# minutes or seconds.
				return array( array( 'throttled-mailpassword', round( $wgPasswordReminderResendTime, 3 ) ) );
			}
		}

		global $wgNewPasswordExpiry;

		// All the users will have the same email address
		if ( $firstUser->getEmail() == '' ) {
			// This won't be reachable from the email route, so safe to expose the username
			return array( array( 'noemail', $firstUser->getName() ) );
		}

		// We need to have a valid IP address for the hook, but per bug 18347, we should
		// send the user's name if they're logged in.
		$ip = wfGetIP();
		if ( !$ip ) {
			return array( 'badipaddress' );
		}
		$caller = $this->getUser();
		wfRunHooks( 'User::mailPasswordInternal', array( &$caller, &$ip, &$firstUser ) );
		$username = $caller->getName();
		$msg = IP::isValid( $username )
			? 'passwordreset-emailtext-ip'
			: 'passwordreset-emailtext-user';

		$passwords = array();
		foreach ( $users as $user ) {
			$password = $user->randomPassword();
			$user->setNewpassword( $password );
			$user->saveSettings();
			$passwords[] = wfMessage( 'passwordreset-emailelement', $user->getName(), $password );
		}
		$passwordBlock = implode( "\n\n", $passwords );

		// Send in the user's language; which should hopefully be the same
		$userLanguage = $firstUser->getOption( 'language' );

		$body = wfMessage( $msg )->inLanguage( $userLanguage );
		$body->params(
			$username,
			$passwordBlock,
			count( $passwords ),
			Title::newMainPage()->getCanonicalUrl(),
			round( $wgNewPasswordExpiry / 86400 )
		);

		$title = wfMessage( 'passwordreset-emailtitle' );

		$result = $firstUser->sendMail( $title->text(), $body->text() );

		if ( $result->isGood() ) {
			return true;
		} else {
			// @todo FIXME: The email didn't send, but we have already set the password throttle
			// timestamp, so they won't be able to try again until it expires...  :(
			return array( array( 'mailerror', $result->getMessage() ) );
		}
	}

	public function onSuccess() {
		$this->getOutput()->addWikiMsg( 'passwordreset-emailsent' );
		$this->getOutput()->returnToMain();
	}

	function canChangePassword(User $user) {
		global $wgPasswordResetRoutes, $wgAuth;

		// Maybe password resets are disabled, or there are no allowable routes
		if ( !is_array( $wgPasswordResetRoutes ) ||
			 !in_array( true, array_values( $wgPasswordResetRoutes ) ) ) {
			return 'passwordreset-disabled';
		}

		// Maybe the external auth plugin won't allow local password changes
		if ( !$wgAuth->allowPasswordChange() ) {
			return 'resetpass_forbidden';
		}

		// Maybe the user is blocked (check this here rather than relying on the parent
		// method as we have a more specific error message to use here
		if ( $user->isBlocked() ) {
			return 'blocked-mailpassword';
		}

		return true;
	}


	/**
	 * Hide the password reset page if resets are disabled.
	 * @return Bool
	 */
	function isListed() {
		global $wgUser;

		if ( $this->canChangePassword( $wgUser ) === true ) {
			return parent::isListed();
		}

		return false;
	}
}