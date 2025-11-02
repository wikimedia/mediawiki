<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use MediaWiki\DAO\WikiAwareEntity;

/**
 * Interface for objects representing user identity.
 *
 * This represents the identity of a user in the context of page revisions and log entries.
 *
 * @note Starting MediaWiki 1.37, UserIdentity objects should no longer expose an actor ID.
 * The actor ID is considered a storage layer optimization and should not be exposed to
 * and used by application logic. Storage layer code should use ActorNormalization to
 * get an actor ID for a UserIdentity.
 *
 * @since 1.31
 * @ingroup User
 */
interface UserIdentity extends WikiAwareEntity {

	/**
	 * @since 1.31
	 *
	 * @param string|false $wikiId The wiki ID expected by the caller
	 * @return int The user ID. May be 0 for anonymous users or for users with no local account.
	 */
	public function getId( $wikiId = self::LOCAL ): int;

	/**
	 * @since 1.31
	 *
	 * @return string The user's logical name. May be an IPv4 or IPv6 address for anonymous users.
	 */
	public function getName(): string;

	/**
	 * @since 1.32
	 *
	 * @param UserIdentity|null $user
	 * @return bool
	 */
	public function equals( ?UserIdentity $user ): bool;

	/**
	 * This must be equivalent to getId() != 0 and is provided for code readability. There is no
	 * equivalent utility for checking whether a user is temporary, since that would introduce a
	 * service dependency. Use UserIdentityUtils::isTemp (or UserNameUtils::isTemp) instead.
	 *
	 * @since 1.34
	 *
	 * @return bool True if user is registered on this wiki, i.e., has a user ID. False if user is
	 *   anonymous or has no local account (which can happen when importing).
	 */
	public function isRegistered(): bool;
}
