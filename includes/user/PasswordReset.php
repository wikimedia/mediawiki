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
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\PermissionManager;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Helper class for the password reset functionality shared by the web UI and the API.
 *
 * Requires the TemporaryPasswordPrimaryAuthenticationProvider and the
 * EmailNotificationSecondaryAuthenticationProvider (or something providing equivalent
 * functionality) to be enabled.
 */
class PasswordReset implements LoggerAwareInterface {
	use LoggerAwareTrait;

	/** @var ServiceOptions|Config */
	protected $config;

	/** @var AuthManager */
	protected $authManager;

	/** @var PermissionManager */
	protected $permissionManager;

	/** @var ILoadBalancer */
	protected $loadBalancer;

	/**
	 * In-process cache for isAllowed lookups, by username.
	 * Contains a StatusValue object
	 * @var MapCacheLRU
	 */
	private $permissionCache;

	public const CONSTRUCTOR_OPTIONS = [
		'AllowRequiringEmailForResets',
		'EnableEmail',
		'PasswordResetRoutes',
	];

	/**
	 * This class is managed by MediaWikiServices, don't instantiate directly.
	 *
	 * @param ServiceOptions|Config $config
	 * @param AuthManager $authManager
	 * @param PermissionManager $permissionManager
	 * @param ILoadBalancer|null $loadBalancer
	 * @param LoggerInterface|null $logger
	 */
	public function __construct(
		$config,
		AuthManager $authManager,
		PermissionManager $permissionManager,
		ILoadBalancer $loadBalancer = null,
		LoggerInterface $logger = null
	) {
		$this->config = $config;
		$this->authManager = $authManager;
		$this->permissionManager = $permissionManager;

		if ( !$loadBalancer ) {
			wfDeprecated( 'Not passing LoadBalancer to ' . __METHOD__, '1.34' );
			$loadBalancer = MediaWikiServices::getInstance()->getDBLoadBalancer();
		}
		$this->loadBalancer = $loadBalancer;

		if ( !$logger ) {
			wfDeprecated( 'Not passing LoggerInterface to ' . __METHOD__, '1.34' );
			$logger = LoggerFactory::getInstance( 'authentication' );
		}
		$this->logger = $logger;

		$this->permissionCache = new MapCacheLRU( 1 );
	}

	/**
	 * Check if a given user has permission to use this functionality.
	 * @param User $user
	 * @since 1.29 Second argument for displayPassword removed.
	 * @return StatusValue
	 */
	public function isAllowed( User $user ) {
		$status = $this->permissionCache->get( $user->getName() );
		if ( !$status ) {
			$resetRoutes = $this->config->get( 'PasswordResetRoutes' );
			$status = StatusValue::newGood();

			if ( !is_array( $resetRoutes ) || !in_array( true, $resetRoutes, true ) ) {
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
			} elseif ( !$this->permissionManager->userHasRight( $user, 'editmyprivateinfo' ) ) {
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
	 * @param string|null $username The user whose password is reset
	 * @param string|null $email Alternative way to specify the user
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

		$username = $username ?? '';
		$email = $email ?? '';

		$resetRoutes = $this->config->get( 'PasswordResetRoutes' )
			+ [ 'username' => false, 'email' => false ];
		if ( $resetRoutes['username'] && $username ) {
			$method = 'username';
			$users = [ $this->lookupUser( $username ) ];
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
			// Email gets set to null for backward compatibility
			'Email' => $method === 'email' ? $email : null,
		];
		if ( !Hooks::run( 'SpecialPasswordResetOnSubmit', [ &$users, $data, &$error ] ) ) {
			return StatusValue::newFatal( Message::newFromSpecifier( $error ) );
		}

		$firstUser = $users[0] ?? null;
		$requireEmail = $this->config->get( 'AllowRequiringEmailForResets' )
			&& $method === 'username'
			&& $firstUser
			&& $firstUser->getBoolOption( 'requireemail' );
		if ( $requireEmail ) {
			if ( $email === '' ) {
				return StatusValue::newFatal( 'passwordreset-username-email-required' );
			}

			if ( !Sanitizer::validateEmail( $email ) ) {
				return StatusValue::newFatal( 'passwordreset-invalidemail' );
			}
		}

		// Check against the rate limiter
		if ( $performingUser->pingLimiter( 'mailpassword' ) ) {
			return StatusValue::newFatal( 'actionthrottledtext' );
		}

		if ( !$users ) {
			if ( $method === 'email' ) {
				// Don't reveal whether or not an email address is in use
				return StatusValue::newGood( [] );
			} else {
				return StatusValue::newFatal( 'noname' );
			}
		}

		if ( !$firstUser instanceof User || !$firstUser->getId() ) {
			// Don't parse username as wikitext (T67501)
			return StatusValue::newFatal( wfMessage( 'nosuchuser', wfEscapeWikiText( $username ) ) );
		}

		// All the users will have the same email address
		if ( !$firstUser->getEmail() ) {
			// This won't be reachable from the email route, so safe to expose the username
			return StatusValue::newFatal( wfMessage( 'noemail',
				wfEscapeWikiText( $firstUser->getName() ) ) );
		}

		if ( $requireEmail && $firstUser->getEmail() !== $email ) {
			// Pretend everything's fine to avoid disclosure
			return StatusValue::newGood();
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
			// This is adding a new temporary password, not intentionally changing anything
			// (even though it might technically invalidate an old temporary password).
			$this->authManager->changeAuthenticationData( $req, /* $isAddition */ true );
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
		return $block->appliesToPasswordReset();
	}

	/**
	 * @param string $email
	 * @return User[]
	 * @throws MWException On unexpected database errors
	 */
	protected function getUsersByEmail( $email ) {
		$userQuery = User::getQueryInfo();
		$res = $this->loadBalancer->getConnectionRef( DB_REPLICA )->select(
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

	/**
	 * User object creation helper for testability
	 * @codeCoverageIgnore
	 *
	 * @param string $username
	 * @return User|false
	 */
	protected function lookupUser( $username ) {
		return User::newFromName( $username );
	}
}
