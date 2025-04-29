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

namespace MediaWiki\User;

use Iterator;
use LogicException;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\TemporaryPasswordAuthenticationRequest;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Deferred\SendPasswordResetEmailUpdate;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\User\Options\UserOptionsLookup;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use StatusValue;
use Wikimedia\MapCacheLRU\MapCacheLRU;

/**
 * Password reset helper for functionality shared by the web UI and the API.
 *
 * Requires the TemporaryPasswordPrimaryAuthenticationProvider and the
 * EmailNotificationSecondaryAuthenticationProvider (or something providing equivalent
 * functionality) to be enabled.
 */
class PasswordReset implements LoggerAwareInterface {
	use LoggerAwareTrait;

	private ServiceOptions $config;
	private AuthManager $authManager;
	private HookRunner $hookRunner;
	private UserIdentityLookup $userIdentityLookup;
	private UserFactory $userFactory;
	private UserNameUtils $userNameUtils;
	private UserOptionsLookup $userOptionsLookup;

	/**
	 * In-process cache for isAllowed lookups, by username.
	 * Contains a StatusValue object
	 */
	private MapCacheLRU $permissionCache;

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::EnableEmail,
		MainConfigNames::PasswordResetRoutes,
	];

	/**
	 * This class is managed by MediaWikiServices, don't instantiate directly.
	 *
	 * @param ServiceOptions $config
	 * @param LoggerInterface $logger
	 * @param AuthManager $authManager
	 * @param HookContainer $hookContainer
	 * @param UserIdentityLookup $userIdentityLookup
	 * @param UserFactory $userFactory
	 * @param UserNameUtils $userNameUtils
	 * @param UserOptionsLookup $userOptionsLookup
	 */
	public function __construct(
		ServiceOptions $config,
		LoggerInterface $logger,
		AuthManager $authManager,
		HookContainer $hookContainer,
		UserIdentityLookup $userIdentityLookup,
		UserFactory $userFactory,
		UserNameUtils $userNameUtils,
		UserOptionsLookup $userOptionsLookup
	) {
		$config->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->config = $config;
		$this->logger = $logger;

		$this->authManager = $authManager;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->userIdentityLookup = $userIdentityLookup;
		$this->userFactory = $userFactory;
		$this->userNameUtils = $userNameUtils;
		$this->userOptionsLookup = $userOptionsLookup;

		$this->permissionCache = new MapCacheLRU( 1 );
	}

	/**
	 * Check if a given user has permission to use this functionality.
	 * @param User $user
	 * @since 1.29 Second argument for displayPassword removed.
	 * @return StatusValue
	 */
	public function isAllowed( User $user ) {
		return $this->permissionCache->getWithSetCallback(
			$user->getName(),
			function () use ( $user ) {
				return $this->computeIsAllowed( $user );
			}
		);
	}

	/**
	 * @since 1.42
	 * @return StatusValue
	 */
	public function isEnabled(): StatusValue {
		$resetRoutes = $this->config->get( MainConfigNames::PasswordResetRoutes );
		if ( !is_array( $resetRoutes ) || !in_array( true, $resetRoutes, true ) ) {
			// Maybe password resets are disabled, or there are no allowable routes
			return StatusValue::newFatal( 'passwordreset-disabled' );
		}

		$providerStatus = $this->authManager->allowsAuthenticationDataChange(
			new TemporaryPasswordAuthenticationRequest(), false );
		if ( !$providerStatus->isGood() ) {
			// Maybe the external auth plugin won't allow local password changes
			return StatusValue::newFatal( 'resetpass_forbidden-reason',
				$providerStatus->getMessage() );
		}
		if ( !$this->config->get( MainConfigNames::EnableEmail ) ) {
			// Maybe email features have been disabled
			return StatusValue::newFatal( 'passwordreset-emaildisabled' );
		}
		return StatusValue::newGood();
	}

	private function computeIsAllowed( User $user ): StatusValue {
		$enabledStatus = $this->isEnabled();
		if ( !$enabledStatus->isGood() ) {
			return $enabledStatus;
		}
		if ( !$user->isAllowed( 'editmyprivateinfo' ) ) {
			// Maybe not all users have permission to change private data
			return StatusValue::newFatal( 'badaccess' );
		}
		if ( $this->isBlocked( $user ) ) {
			// Maybe the user is blocked (check this here rather than relying on the parent
			// method as we have a more specific error message to use here, and we want to
			// ignore some types of blocks)
			return StatusValue::newFatal( 'blocked-mailpassword' );
		}
		return StatusValue::newGood();
	}

	/**
	 * Do a password reset. Authorization is the caller's responsibility.
	 *
	 * Process the form.
	 *
	 * At this point, we know that the user passes all the criteria in
	 * userCanExecute(), and if the data array contains 'Username', etc., then Username
	 * resets are allowed.
	 *
	 * @since 1.29 Fourth argument for displayPassword removed.
	 * @param User $performingUser The user that does the password reset
	 * @param string|null $username The user whose password is reset
	 * @param string|null $email Alternative way to specify the user
	 * @return StatusValue
	 */
	public function execute(
		User $performingUser,
		$username = null,
		$email = null
	) {
		if ( !$this->isAllowed( $performingUser )->isGood() ) {
			throw new LogicException(
				'User ' . $performingUser->getName() . ' is not allowed to reset passwords'
			);
		}

		// Check against the rate limiter. If the $wgRateLimit is reached, we want to pretend
		// that the request was good to avoid displaying an error message.
		if ( $performingUser->pingLimiter( 'mailpassword' ) ) {
			return StatusValue::newGood();
		}

		// We need to have a valid IP address for the hook 'User::mailPasswordInternal', but per T20347,
		// we should send the user's name if they're logged in.
		$ip = $performingUser->getRequest()->getIP();
		if ( !$ip ) {
			return StatusValue::newFatal( 'badipaddress' );
		}

		$resetRoutes = $this->config->get( MainConfigNames::PasswordResetRoutes )
			+ [ 'username' => false, 'email' => false ];
		if ( !$resetRoutes['username'] || $username === '' ) {
			$username = null;
		}
		if ( !$resetRoutes['email'] || $email === '' ) {
			$email = null;
		}

		if ( $username !== null && !$this->userNameUtils->getCanonical( $username ) ) {
			return StatusValue::newFatal( 'noname' );
		}
		if ( $email !== null && !Sanitizer::validateEmail( $email ) ) {
			return StatusValue::newFatal( 'passwordreset-invalidemail' );
		}
		// At this point, $username and $email are either valid or not provided

		/** @var User[] $users */
		$users = [];

		if ( $username !== null ) {
			$user = $this->userFactory->newFromName( $username );
			// User must have an email address to attempt sending a password reset email
			if ( $user && $user->isRegistered() && $user->getEmail() && (
				!$this->userOptionsLookup->getBoolOption( $user, 'requireemail' ) ||
				$user->getEmail() === $email
			) ) {
				// Either providing the email in the form is not required to request a reset,
				// or the correct email was provided
				$users[] = $user;
			}

		} elseif ( $email !== null ) {
			foreach ( $this->getUsersByEmail( $email ) as $userIdent ) {
				// Skip users whose preference 'requireemail' is on since the username was not submitted
				if ( $this->userOptionsLookup->getBoolOption( $userIdent, 'requireemail' ) ) {
					continue;
				}
				$users[] = $this->userFactory->newFromUserIdentity( $userIdent );
			}

		} else {
			// The user didn't supply any data
			return StatusValue::newFatal( 'passwordreset-nodata' );
		}

		// Check for hooks (captcha etc.), and allow them to modify the list of users
		$data = [
			'Username' => $username,
			'Email' => $email,
		];

		$error = [];
		if ( !$this->hookRunner->onSpecialPasswordResetOnSubmit( $users, $data, $error ) ) {
			return StatusValue::newFatal( Message::newFromSpecifier( $error ) );
		}

		if ( !$users ) {
			// Don't reveal whether a username or email address is in use
			return StatusValue::newGood();
		}

		// Get the first element in $users by using `reset` function since
		// the key '0' might have been unset from $users array by a hook handler.
		$firstUser = reset( $users );

		$this->hookRunner->onUser__mailPasswordInternal( $performingUser, $ip, $firstUser );

		$result = StatusValue::newGood();
		$reqs = [];
		foreach ( $users as $user ) {
			$req = TemporaryPasswordAuthenticationRequest::newRandom();
			$req->username = $user->getName();
			$req->mailpassword = true;
			$req->caller = $performingUser->getName();

			$status = $this->authManager->allowsAuthenticationDataChange( $req, true );
			// If the status is good and the value is 'throttled-mailpassword', we want to pretend
			// that the request was good to avoid displaying an error message and disclose
			// if a reset password was previously sent.
			if ( $status->isGood() && $status->getValue() === 'throttled-mailpassword' ) {
				return StatusValue::newGood();
			}

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
		];

		if ( !$result->isGood() ) {
			$this->logger->info(
				"{requestingUser} attempted password reset of {targetUsername} but failed",
				$logContext + [ 'errors' => $result->getErrors() ]
			);
			return $result;
		}

		DeferredUpdates::addUpdate(
			new SendPasswordResetEmailUpdate( $this->authManager, $reqs, $logContext ),
			DeferredUpdates::POSTSEND
		);

		return StatusValue::newGood();
	}

	/**
	 * Check whether the user is blocked.
	 * Ignores certain types of system blocks that are only meant to force users to log in.
	 * @param User $user
	 * @return bool
	 * @since 1.30
	 */
	private function isBlocked( User $user ) {
		$block = $user->getBlock();
		return $block && $block->appliesToPasswordReset();
	}

	/**
	 * @note This is protected to allow configuring in tests. This class is not stable to extend.
	 *
	 * @param string $email
	 *
	 * @return Iterator<UserIdentity>
	 */
	protected function getUsersByEmail( $email ) {
		return $this->userIdentityLookup->newSelectQueryBuilder()
			->join( 'user', null, [ "actor_user=user_id" ] )
			->where( [ 'user_email' => $email ] )
			->caller( __METHOD__ )
			->fetchUserIdentities();
	}

}

/** @deprecated class alias since 1.41 */
class_alias( PasswordReset::class, 'PasswordReset' );
