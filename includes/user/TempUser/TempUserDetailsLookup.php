<?php
declare( strict_types=1 );
namespace MediaWiki\User\TempUser;

use ArrayIterator;
use CallbackFilterIterator;
use IteratorIterator;
use MediaWiki\User\Registration\UserRegistrationLookup;
use MediaWiki\User\UserIdentity;
use Wikimedia\MapCacheLRU\MapCacheLRU;

/**
 * Caching lookup service for metadata related to temporary accounts, such as expiration.
 * @since 1.44
 */
class TempUserDetailsLookup {
	private TempUserConfig $tempUserConfig;
	private UserRegistrationLookup $userRegistrationLookup;

	private MapCacheLRU $expiryCache;

	public function __construct(
		TempUserConfig $tempUserConfig,
		UserRegistrationLookup $userRegistrationLookup
	) {
		$this->tempUserConfig = $tempUserConfig;
		$this->userRegistrationLookup = $userRegistrationLookup;

		// Use a relatively large cache size to account for pages with a high number of user links,
		// such as Special:RecentChanges or history pages.
		$this->expiryCache = new MapCacheLRU( 1_000 );
	}

	/**
	 * Check if a temporary user account is expired.
	 * @param UserIdentity $user
	 * @return bool `true` if the account is expired, `false` otherwise.
	 */
	public function isExpired( UserIdentity $user ): bool {
		$userName = $user->getName();
		if (
			!$this->tempUserConfig->isTempName( $userName ) ||
			!$user->isRegistered()
		) {
			return false;
		}

		if ( !$this->expiryCache->has( $userName ) ) {
			$registration = $this->userRegistrationLookup->getFirstRegistration( $user );

			$this->expiryCache->set( $userName, $this->getExpirationState( $registration ) );
		}

		return $this->expiryCache->get( $userName );
	}

	/**
	 * Preload the expiration status of temporary accounts within a set of users.
	 *
	 * @param iterable<UserIdentity> $users The users to preload the expiration status for.
	 */
	public function preloadExpirationStatus( iterable $users ): void {
		// Save the user name associated with each user id for use later.
		$usersById = [];
		foreach ( $users as $user ) {
			$usersById[$user->getId()] = $user->getName();
		}

		$users = is_array( $users ) ? new ArrayIterator( $users ) : new IteratorIterator( $users );
		$timestampsById = $this->userRegistrationLookup->getFirstRegistrationBatch(
			new CallbackFilterIterator(
				$users,
				fn ( UserIdentity $user ): bool =>
					$user->isRegistered() && $this->tempUserConfig->isTempName( $user->getName() )
			)
		);

		foreach ( $timestampsById as $userId => $registrationTimestamp ) {
			// Cache expiry by user name instead of user id. See T398722.
			$this->expiryCache->set(
				$usersById[$userId],
				$this->getExpirationState( $registrationTimestamp )
			);
		}
	}

	/**
	 * Check whether a temporary account registered at the given timestamp is expired now.
	 * @param string|null|false $registration DB timestamp of the registration time.
	 * May be `null` or `false` if not known.
	 * @return bool `true` if the account is expired, `false` otherwise.
	 */
	public function getExpirationState( $registration ): bool {
		if ( !is_string( $registration ) ) {
			return false;
		}

		$expireAfterDays = $this->tempUserConfig->getExpireAfterDays();
		$expiresAt = (int)wfTimestamp( TS_UNIX, $registration ) + $expireAfterDays * 86400;
		return $expiresAt < wfTimestamp( TS_UNIX );
	}
}
