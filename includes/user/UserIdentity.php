<?php
/**
 * Interface for objects representing user identity.
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
 */
interface UserIdentity extends WikiAwareEntity {

	/**
	 * @since 1.31
	 *
	 * @param string|false $wikiId The wiki ID expected by the caller
	 * @return int The user ID. May be 0 for anonymous users or for users with no local account.
	 *
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
	 * @since 1.34
	 *
	 * @return bool True if user is registered on this wiki, i.e., has a user ID. False if user is
	 *   anonymous or has no local account (which can happen when importing). This must be
	 *   equivalent to getId() != 0 and is provided for code readability.
	 */
	public function isRegistered(): bool;
}
