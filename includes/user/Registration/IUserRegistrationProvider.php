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
	 * @return string|false|null Registration timestamp, null if not available or false if it
	 * cannot be fetched (anonymous users, for example).
	 */
	public function fetchRegistration( UserIdentity $user );
}
