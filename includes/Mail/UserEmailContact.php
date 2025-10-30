<?php

namespace MediaWiki\Mail;

use MediaWiki\User\UserIdentity;

/**
 * @since 1.36
 * @ingroup Mail
 */
interface UserEmailContact {

	/**
	 * Get the identity of the user this contact belongs to.
	 *
	 * @return UserIdentity
	 */
	public function getUser(): UserIdentity;

	/**
	 * Get user email address an empty string if unknown.
	 *
	 * @return string
	 */
	public function getEmail(): string;

	/**
	 * Get user real name or an empty string if unknown.
	 *
	 * @return string
	 */
	public function getRealName(): string;

	/**
	 * Whether user email was confirmed.
	 *
	 * @return bool
	 */
	public function isEmailConfirmed(): bool;
}
