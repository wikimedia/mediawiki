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
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use MediaWiki\Logger\LoggerFactory;

/**
 * Helper class for the password reset functionality shared by the web UI and the API.
 *
 * Requires the TemporaryPasswordPrimaryAuthenticationProvider and the
 * EmailNotificationSecondaryAuthenticationProvider (or something providing equivalent
 * functionality) to be enabled.
 */
class PasswordReset implements LoggerAwareInterface {
	/** @var Config */
	protected $config;

	/** @var AuthManager */
	protected $authManager;

	/** @var LoggerInterface */
	protected $logger;

	/**
	 * In-process cache for isAllowed lookups, by username.
	 * Contains a StatusValue object
	 * @var HashBagOStuff
	 */
	private $permissionCache;

	public function __construct( Config $config, AuthManager $authManager ) {
		$this->config = $config;
		$this->authManager = $authManager;
		$this->permissionCache = new HashBagOStuff( [ 'maxKeys' => 1 ] );
		$this->logger = LoggerFactory::getInstance( 'authentication' );
	}

	/**
	 * Set the logger instance to use.
	 *
	 * @param LoggerInterface $logger
	 * @since 1.29
	 */
	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Check if a given user has permission to use this functionality.
	 * @param User $user
	 * @param bool $displayPassword If set, also check whether the user is allowed to reset the
	 *   password of another user and see the temporary password.
	 * @since 1.29 Second argument for displayPassword removed.
	 * @return StatusValue
	 */
	public function isAllowed( User $user ) {
		$status = $this->permissionCache->get( $user->getName() );
		if ( !$status ) {
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
			} elseif ( $this->isBlocked( $user ) ) {
				// Maybe the user is blocked (check this here rather than relying on the parent
				// method as we have a more specific error message to use here and we want to
				// ignore some types of blocks)
				$status = StatusValue::newFatal( 'blocked-mailpassword' );
			}

			$this->permissionCache->set( $user->getName(), $status );
		}

		return $status;
	}

	/**
	 * Do a password reset. Authorization is the caller's responsibility.
	 *
	 * Process the form.  At this point we know that the user passes all the criteria in
	 * userCanExecute(), and if the data array contains 'Username', etc, then Username
	 * resets are allowed.
	 *
	 * @since 1.29 Fourth argument for displayPassword removed.
	 * @param User $performingUser The user that does the password reset
	 * @param string $username The user whose password is reset
	 * @param string $email Alternative way to specify the user
	 * @return StatusValue Will contain the passwords as a username => password array if the
	 *   $displayPassword flag was set
	 * @throws LogicException When the user is not allowed to perform the action
	 * @throws MWException On unexpected DB errors
	 */
	public function execute(
		User $performingUser, $username = null, $email = null
	) {
		if ( !$this->isAllowed( $performingUser )->isGood() ) {
			throw new LogicException( 'User ' . $performingUser->getName()
				. ' is not allowed to reset passwords' );
		}

		$resetRoutes = $this->config->get( 'PasswordResetRoutes' )
			+ [ 'username' => false, 'email' => false ];
		if ( $resetRoutes['username'] && $username ) {
			$method = 'username';
			$users = [ User::newFromName( $username ) ];
			$email = null;
		} elseif ( $resetRoutes['email'] && $email ) {
			if ( !Sanitizer::validateEmail( $email ) ) {
				return StatusValue::newFatal( 'passwordreset-invalidemail' );
			}
			$method = 'email';
			$users = $this->getUsersByEmail( $email );
			$username = null;
		} else {
			// The user didn't supply any data
			return StatusValue::newFatal( 'passwordreset-nodata' );
		}

		// Check for hooks (captcha etc), and allow them to modify the users list
		$error = [];
		$data = [
			'Username' => $username,
			'Email' => $email,
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
			// Don't parse username as wikitext (T67501)
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

		// We need to have a valid IP address for the hook, but per T20347, we should
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

		$logContext = [
			'requestingIp' => $ip,
			'requestingUser' => $performingUser->getName(),
			'targetUsername' => $username,
			'targetEmail' => $email,
			'actualUser' => $firstUser->getName(),
		];

		if ( !$result->isGood() ) {
			$this->logger->info(
				"{requestingUser} attempted password reset of {actualUser} but failed",
				$logContext + [ 'errors' => $result->getErrors() ]
			);
			return $result;
		}

		$passwords = [];
		foreach ( $reqs as $req ) {
			$this->authManager->changeAuthenticationData( $req );
		}

		$this->logger->info(
			"{requestingUser} did password reset of {actualUser}",
			$logContext
		);

		return StatusValue::newGood( $passwords );
	}

	/**
	 * Check whether the user is blocked.
	 * Ignores certain types of system blocks that are only meant to force users to log in.
	 * @param User $user
	 * @return bool
	 * @since 1.30
	 */
	protected function isBlocked( User $user ) {
		$block = $user->getBlock() ?: $user->getGlobalBlock();
		if ( !$block ) {
			return false;
		}
		$type = $block->getSystemBlockType();
		if ( in_array( $type, [ null, 'global-block' ], true ) ) {
			// Normal block. Maybe it was meant for someone else and the user just needs to log in;
			// or maybe it was issued specifically to prevent some IP from messing with password
			// reset? Go out on a limb and use the registration allowed flag to decide.
			return $block->prevents( 'createaccount' );
		} elseif ( $type === 'proxy' ) {
			// we disallow actions through proxy even if the user is logged in
			// so it makes sense to disallow password resets as well
			return true;
		} elseif ( in_array( $type, [ 'dnsbl', 'wgSoftBlockRanges' ], true ) ) {
			// these are just meant to force login so let's not prevent that
			return false;
		} else {
			// some extension - we'll have to guess
			return true;
		}
	}

	/**
	 * @param string $email
	 * @return User[]
	 * @throws MWException On unexpected database errors
	 */
	protected function getUsersByEmail( $email ) {
		$userQuery = User::getQueryInfo();
		$res = wfGetDB( DB_REPLICA )->select(
			$userQuery['tables'],
			$userQuery['fields'],
			[ 'user_email' => $email ],
			__METHOD__,
			[],
			$userQuery['joins']
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
