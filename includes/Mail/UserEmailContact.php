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
	 */
	public function getUser(): UserIdentity;

	/**
	 * Gets the user email address or an empty string if unknown.
	 */
	public function getEmail(): string;

	/**
	 * Get the user's real name or an empty string if unknown.
	 */
	public function getRealName(): string;

	/**
	 * Whether user email was confirmed.
	 */
	public function isEmailConfirmed(): bool;
}
