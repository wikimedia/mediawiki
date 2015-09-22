<?php
/**
 * Implements Special:PasswordReset
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
use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\PasswordAuthenticationRequest;
use MediaWiki\Auth\TemporaryPasswordAuthenticationRequest;

/**
 * Special page for requesting a password reset email.
 *
 * Requires the TemporaryPasswordPrimaryAuthenticationProvider and the
 * EmailNotificationSecondaryAuthenticationProvider (or something providing equivalent functionality)
 * to be enabled.
 *
 * @ingroup SpecialPage
 */
class SpecialPasswordReset extends FormSpecialPage {
	/**
	 * @var string[] Temporary storage for the passwords which have been sent out, keyed by username.
	 */
	private $passwords;

	/**
	 * @var Status
	 */
	private $result;

	/**
	 * @var string $method Identifies which password reset field was specified by the user.
	 */
	private $method;

	public function __construct() {
		parent::__construct( 'PasswordReset', 'editmyprivateinfo' );
	}

	public function doesWrites() {
		return true;
	}

	public function userCanExecute( User $user ) {
		return $this->canChangePassword( $user ) === true && parent::userCanExecute( $user );
	}

	public function checkExecutePermissions( User $user ) {
		$error = $this->canChangePassword( $user );
		if ( is_string( $error ) ) {
			throw new ErrorPageError( 'internalerror', $error );
		} elseif ( !$error ) {
			throw new ErrorPageError( 'internalerror', 'resetpass_forbidden' );
		}

		parent::checkExecutePermissions( $user );
	}

	protected function getFormFields() {
		$resetRoutes = $this->getConfig()->get( 'PasswordResetRoutes' );
		$a = [];
		if ( isset( $resetRoutes['username'] ) && $resetRoutes['username'] ) {
			$a['Username'] = [
				'type' => 'text',
				'label-message' => 'passwordreset-username',
			];

			if ( $this->getUser()->isLoggedIn() ) {
				$a['Username']['default'] = $this->getUser()->getName();
			}
		}

		if ( isset( $resetRoutes['email'] ) && $resetRoutes['email'] ) {
			$a['Email'] = [
				'type' => 'email',
				'label-message' => 'passwordreset-email',
			];
		}

		if ( $this->getUser()->isAllowed( 'passwordreset' ) ) {
			$a['Capture'] = [
				'type' => 'check',
				'label-message' => 'passwordreset-capture',
				'help-message' => 'passwordreset-capture-help',
			];
		}

		return $a;
	}

	protected function getDisplayFormat() {
		return 'ooui';
	}

	public function alterForm( HTMLForm $form ) {
		$resetRoutes = $this->getConfig()->get( 'PasswordResetRoutes' );

		$form->addHiddenFields( $this->getRequest()->getValues( 'returnto', 'returntoquery' ) );

		$i = 0;
		if ( isset( $resetRoutes['username'] ) && $resetRoutes['username'] ) {
			$i++;
		}
		if ( isset( $resetRoutes['email'] ) && $resetRoutes['email'] ) {
			$i++;
		}

		$message = ( $i > 1 ) ? 'passwordreset-text-many' : 'passwordreset-text-one';

		$form->setHeaderText( $this->msg( $message, $i )->parseAsBlock() );
		$form->setSubmitTextMsg( 'mailmypassword' );
	}

	/**
	 * Process the form.  At this point we know that the user passes all the criteria in
	 * userCanExecute(), and if the data array contains 'Username', etc, then Username
	 * resets are allowed.
	 * @param array $data
	 * @throws MWException
	 * @throws ThrottledError|PermissionsError
	 * @return bool|array
	 */
	public function onSubmit( array $data ) {
		global $wgMinimalPasswordLength;
		$authManager = AuthManager::singleton();

		if ( isset( $data['Capture'] ) && !$this->getUser()->isAllowed( 'passwordreset' ) ) {
			// The user knows they don't have the passwordreset permission,
			// but they tried to spoof the form. That's naughty
			throw new PermissionsError( 'passwordreset' );
		}

		/**
		 * @var $firstUser User
		 * @var $users User[]
		 */

		if ( isset( $data['Username'] ) && $data['Username'] !== '' ) {
			$method = 'username';
			$users = [ User::newFromName( $data['Username'] ) ];
		} elseif ( isset( $data['Email'] )
			&& $data['Email'] !== ''
			&& Sanitizer::validateEmail( $data['Email'] )
		) {
			$method = 'email';
			$res = wfGetDB( DB_SLAVE )->select(
				'user',
				User::selectFields(),
				[ 'user_email' => $data['Email'] ],
				__METHOD__
			);

			if ( $res ) {
				$users = [];

				foreach ( $res as $row ) {
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
		$error = [];
		if ( !Hooks::run( 'SpecialPasswordResetOnSubmit', [ &$users, $data, &$error ] ) ) {
			return [ $error ];
		}

		$this->method = $method;

		if ( count( $users ) == 0 ) {
			if ( $method == 'email' ) {
				// Don't reveal whether or not an email address is in use
				return true;
			} else {
				return [ 'noname' ];
			}
		}

		$firstUser = $users[0];

		if ( !$firstUser instanceof User || !$firstUser->getId() ) {
			// Don't parse username as wikitext (bug 65501)
			return [ [ 'nosuchuser', wfEscapeWikiText( $data['Username'] ) ] ];
		}

		// Check against the rate limiter
		if ( $this->getUser()->pingLimiter( 'mailpassword' ) ) {
			throw new ThrottledError;
		}

		// All the users will have the same email address
		if ( $firstUser->getEmail() == '' ) {
			// This won't be reachable from the email route, so safe to expose the username
			return [ [ 'noemail', wfEscapeWikiText( $firstUser->getName() ) ] ];
		}

		// We need to have a valid IP address for the hook, but per bug 18347, we should
		// send the user's name if they're logged in.
		$ip = $this->getRequest()->getIP();
		if ( !$ip ) {
			return [ 'badipaddress' ];
		}

		$caller = $this->getUser();
		Hooks::run( 'User::mailPasswordInternal', [ &$caller, &$ip, &$firstUser ] );

		$this->result = Status::newGood();
		foreach ( $users as $user ) {
			$req = TemporaryPasswordAuthenticationRequest::newRandom();
			$req->username = $user->getName();
			$req->mailpassword = true;
			$req->hasBackchannel = !empty( $data['Capture'] );
			$req->caller = $this->getUser()->getName();
			$status = $authManager->allowsAuthenticationDataChange( $req, true );
			$this->result->merge( $status );
			if ( $status->isGood() ) {
				$authManager->changeAuthenticationData( $req );

				// TODO record mail sending errors
				if ( !empty( $data['Capture'] ) ) {
					$this->passwords[$req->username] = $req->password;
				}
			}
		}

		if ( $this->result->isGood() ) {
			return true;
		} elseif ( isset( $data['Capture'] ) && $data['Capture'] ) {
			// The email didn't send, but maybe they knew that and that's why they captured it
			return true;
		} else {
			// @todo FIXME: The email wasn't sent, but we have already set
			// the password throttle timestamp, so they won't be able to try
			// again until it expires...  :(
			return [ [ 'mailerror', $this->result->getMessage() ] ];
		}
	}

	public function onSuccess() {
		if ( $this->getUser()->isAllowed( 'passwordreset' ) && $this->passwords ) {
			// @todo Logging

			if ( $this->result->isGood() ) {
				$this->getOutput()->addWikiMsg( 'passwordreset-emailsent-capture',
					count( $this->passwords ) );
			} else {
				$this->getOutput()->addWikiMsg( 'passwordreset-emailerror-capture',
					$this->result->getMessage(), $this->firstUser->getName(), count( $this->passwords ) );
			}

			$passwords = '';
			foreach ( $this->passwords as $username => $pwd ) {
				$passwords .= htmlspecialchars( $username, ENT_QUOTES )
					. $this->msg( 'colon-separator' )->text()
					. htmlspecialchars( $pwd, ENT_QUOTES )
					. "\n";
			}

			$this->getOutput()->addHTML( Html::rawElement( 'pre', [], $passwords ) );
		}

		if ( $this->method === 'email' ) {
			$this->getOutput()->addWikiMsg( 'passwordreset-emailsentemail' );
		} else {
			$this->getOutput()->addWikiMsg( 'passwordreset-emailsentusername' );
		}

		$this->getOutput()->returnToMain();
	}

	protected function canChangePassword( User $user ) {
		$authManager = AuthManager::singleton();
		$resetRoutes = $this->getConfig()->get( 'PasswordResetRoutes' );

		// Maybe password resets are disabled, or there are no allowable routes
		if ( !is_array( $resetRoutes ) ||
			!in_array( true, array_values( $resetRoutes ) )
		) {
			return 'passwordreset-disabled';
		}

		// Maybe the external auth plugin won't allow local password changes
		if ( !$authManager->allowsAuthenticationDataChange( new PasswordAuthenticationRequest ) ) {
			return 'resetpass_forbidden';
		}

		// Maybe email features have been disabled
		if ( !$this->getConfig()->get( 'EnableEmail' ) ) {
			return 'passwordreset-emaildisabled';
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
	 * @return bool
	 */
	function isListed() {
		if ( $this->canChangePassword( $this->getUser() ) === true ) {
			return parent::isListed();
		}

		return false;
	}

	protected function getGroupName() {
		return 'users';
	}
}
