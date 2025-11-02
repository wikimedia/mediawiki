<?php

namespace MediaWiki\User\Registration;

use MediaWiki\User\UserIdentity;

/**
 * @since 1.41
 * @stable to implement
 */
interface IUserRegistrationProvider {

	/**
	 * Get user registration timestamp
	 *
	 * @param UserIdentity $user
	 * @return string|false|null Registration timestamp (TS_MW), null if not available or false if it
	 * cannot be fetched (anonymous users, for example).
	 */
	public function fetchRegistration( UserIdentity $user );

	/**
	 * Get user registration timestamps for a batch of users.
	 *
	 * @since 1.44
	 * @param iterable<UserIdentity> $users
	 * @return string[]|null[] Map of registration timestamps in MediaWiki format
	 * (or `null` if not available) keyed by user ID.
	 */
	public function fetchRegistrationBatch( iterable $users ): array;
}
