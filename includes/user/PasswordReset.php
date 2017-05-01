<?php
/**
 * User password reset helper for MediaWiki.
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
 */

use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\TemporaryPasswordAuthenticationRequest;

/**
 * Helper class for the password reset functionality shared by the web UI and the API.
 *
 * Requires the TemporaryPasswordPrimaryAuthenticationProvider and the
 * EmailNotificationSecondaryAuthenticationProvider (or something providing equivalent
 * functionality) to be enabled.
 */
class PasswordReset {
	/** @var Config */
	protected $config;

	/** @var AuthManager */
	protected $authManager;

	/**
	 * In-process cache for isAllowed lookups, by username. Contains pairs of StatusValue objects
	 * (for false and true value of $displayPassword, respectively).
	 * @var HashBagOStuff
	 */
	private $permissionCache;

	public function __construct( Config $config, AuthManager $authManager ) {
		$this->config = $config;
		$this->authManager = $authManager;
		$this->permissionCache = new HashBagOStuff( [ 'maxKeys' => 1 ] );
	}

	/**
	 * Check if a given user has permission to use this functionality.
	 * @param User $user
	 * @param bool $displayPassword If set, also check whether the user is allowed to reset the
	 *   password of another user and see the temporary password.
	 * @return StatusValue
	 */
	public function isAllowed( User $user, $displayPassword = false ) {
		$statuses = $this->permissionCache->get( $user->getName() );
		if ( $statuses ) {
			list ( $status, $status2 ) = $statuses;
		} else {
			$resetRoutes = $this->config->get( 'PasswordResetRoutes' );
			$status = StatusValue::newGood();

			if ( !is_array( $resetRoutes ) ||
				 !in_array( true, array_values( $resetRoutes ), true )
			) {
				// Maybe password resets are disabled, or there are no allowable routes
				$status = StatusValue::newFatal( 'passwordreset-disabled' );
			} elseif (
				( $providerStatus = $this->authManager->allowsAuthenticationDataChange(
					new TemporaryPasswordAuthenticationRequest(), false ) )
				&& !$providerStatus->isGood()
			) {
				// Maybe the external auth plugin won't allow local password changes
				$status = StatusValue::newFatal( 'resetpass_forbidden-reason',
					$providerStatus->getMessage() );
			} elseif ( !$this->config->get( 'EnableEmail' ) ) {
				// Maybe email features have been disabled
				$status = StatusValue::newFatal( 'passwordreset-emaildisabled' );
			} elseif ( !$user->isAllowed( 'editmyprivateinfo' ) ) {
				// Maybe not all users have permission to change private data
				$status = StatusValue::newFatal( 'badaccess' );
			} elseif ( $user->isBlocked() ) {
				// Maybe the user is blocked (check this here rather than relying on the parent
				// method as we have a more specific error message to use here
				$status = StatusValue::newFatal( 'blocked-mailpassword' );
			}

			$status2 = StatusValue::newGood();
			if ( !$user->isAllowed( 'passwordreset' ) ) {
				$status2 = StatusValue::newFatal( 'badaccess' );
			}

			$this->permissionCache->set( $user->getName(), [ $status, $status2 ] );
		}

		if ( !$displayPassword || !$status->isGood() ) {
			return $status;
		} else {
			return $status2;
		}
	}

	/**
	 * Do a password reset. Authorization is the caller's responsibility.
	 *
	 * Process the form.  At this point we know that the user passes all the criteria in
	 * userCanExecute(), and if the data array contains 'Username', etc, then Username
	 * resets are allowed.
	 * @param User $performingUser The user that does the password reset
	 * @param string $username The user whose password is reset
	 * @param string $email Alternative way to specify the user
	 * @param bool $displayPassword Whether to display the password
	 * @return StatusValue Will contain the passwords as a username => password array if the
	 *   $displayPassword flag was set
	 * @throws LogicException When the user is not allowed to perform the action
	 * @throws MWException On unexpected DB errors
	 */
	public function execute(
		User $performingUser, $username = null, $email = null, $displayPassword = false
	) {
		if ( !$this->isAllowed( $performingUser, $displayPassword )->isGood() ) {
			$action = $this->isAllowed( $performingUser )->isGood() ? 'display' : 'reset';
			throw new LogicException( 'User ' . $performingUser->getName()
				. ' is not allowed to ' . $action . ' passwords' );
		}

		$resetRoutes = $this->config->get( 'PasswordResetRoutes' )
			+ [ 'username' => false, 'email' => false ];
		if ( $resetRoutes['username'] && $username ) {
			$method = 'username';
			$users = [ User::newFromName( $username ) ];
		} elseif ( $resetRoutes['email'] && $email ) {
			if ( !Sanitizer::validateEmail( $email ) ) {
				return StatusValue::newFatal( 'passwordreset-invalidemail' );
			}
			$method = 'email';
			$users = $this->getUsersByEmail( $email );
		} else {
			// The user didn't supply any data
			return StatusValue::newFatal( 'passwordreset-nodata' );
		}

		// Check for hooks (captcha etc), and allow them to modify the users list
		$error = [];
		$data = [
			'Username' => $username,
			'Email' => $email,
			'Capture' => $displayPassword ? '1' : null,
		];
		if ( !Hooks::run( 'SpecialPasswordResetOnSubmit', [ &$users, $data, &$error ] ) ) {
			return StatusValue::newFatal( Message::newFromSpecifier( $error ) );
		}

		if ( !$users ) {
			if ( $method === 'email' ) {
				// Don't reveal whether or not an email address is in use
				return StatusValue::newGood( [] );
			} else {
				return StatusValue::newFatal( 'noname' );
			}
		}

		$firstUser = $users[0];

		if ( !$firstUser instanceof User || !$firstUser->getId() ) {
			// Don't parse username as wikitext (bug 65501)
			return StatusValue::newFatal( wfMessage( 'nosuchuser', wfEscapeWikiText( $username ) ) );
		}

		// Check against the rate limiter
		if ( $performingUser->pingLimiter( 'mailpassword' ) ) {
			return StatusValue::newFatal( 'actionthrottledtext' );
		}

		// All the users will have the same email address
		if ( !$firstUser->getEmail() ) {
			// This won't be reachable from the email route, so safe to expose the username
			return StatusValue::newFatal( wfMessage( 'noemail',
				wfEscapeWikiText( $firstUser->getName() ) ) );
		}

		// We need to have a valid IP address for the hook, but per bug 18347, we should
		// send the user's name if they're logged in.
		$ip = $performingUser->getRequest()->getIP();
		if ( !$ip ) {
			return StatusValue::newFatal( 'badipaddress' );
		}

		Hooks::run( 'User::mailPasswordInternal', [ &$performingUser, &$ip, &$firstUser ] );

		$result = StatusValue::newGood();
		$reqs = [];
		foreach ( $users as $user ) {
			$req = TemporaryPasswordAuthenticationRequest::newRandom();
			$req->username = $user->getName();
			$req->mailpassword = true;
			$req->hasBackchannel = $displayPassword;
			$req->caller = $performingUser->getName();
			$status = $this->authManager->allowsAuthenticationDataChange( $req, true );
			if ( $status->isGood() && $status->getValue() !== 'ignored' ) {
				$reqs[] = $req;
			} elseif ( $result->isGood() ) {
				// only record the first error, to avoid exposing the number of users having the
				// same email address
				if ( $status->getValue() === 'ignored' ) {
					$status = StatusValue::newFatal( 'passwordreset-ignored' );
				}
				$result->merge( $status );
			}
		}

		if ( !$result->isGood() ) {
			return $result;
		}

		$passwords = [];
		foreach ( $reqs as $req ) {
			$this->authManager->changeAuthenticationData( $req );
			// TODO record mail sending errors
			if ( $displayPassword ) {
				$passwords[$req->username] = $req->password;
			}
		}

		return StatusValue::newGood( $passwords );
	}

	/**
	 * @param string $email
	 * @return User[]
	 * @throws MWException On unexpected database errors
	 */
	protected function getUsersByEmail( $email ) {
		$res = wfGetDB( DB_REPLICA )->select(
			'user',
			User::selectFields(),
			[ 'user_email' => $email ],
			__METHOD__
		);

		if ( !$res ) {
			// Some sort of database error, probably unreachable
			throw new MWException( 'Unknown database error in ' . __METHOD__ );
		}

		$users = [];
		foreach ( $res as $row ) {
			$users[] = User::newFromRow( $row );
		}
		return $users;
	}
}
