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

namespace MediaWiki\Block;

use CommentStoreComment;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\User\UserIdentity;

/**
 * Represents a block that may prevent users from performing specific operations.
 * The block may apply to a specific user, to a network address, network range,
 * or some other aspect of a web request.
 * The block may apply to the entire site, or may be limited to specific pages
 * or namespaces.
 *
 * @since 1.37 Extracted from the AbstractBlock base class,
 * which was in turn factored out of DatabaseBlock in 1.34.
 *
 * Extends WikiAwareEntity since 1.38.
 */
interface Block extends WikiAwareEntity {

	# TYPE constants
	# Do not introduce negative constants without changing BlockUser command object.
	public const TYPE_USER = 1;
	public const TYPE_IP = 2;
	public const TYPE_RANGE = 3;
	public const TYPE_AUTO = 4;
	public const TYPE_ID = 5;

	/**
	 * Map block types to strings, to allow convenient logging.
	 */
	public const BLOCK_TYPES = [
		self::TYPE_USER => 'user',
		self::TYPE_IP => 'ip',
		self::TYPE_RANGE => 'range',
		self::TYPE_AUTO => 'autoblock',
		self::TYPE_ID => 'id',
	];

	/**
	 * Get the block ID
	 *
	 * @param string|false $wikiId (since 1.38)
	 * @return ?int
	 */
	public function getId( $wikiId = self::LOCAL ): ?int;

	/**
	 * Get the information that identifies this block, such that a user could
	 * look up everything that can be found about this block. Typically a scalar ID (integer
	 * or string), but can also return a list of IDs, or an associative array encoding a composite
	 * ID. Must be safe to serialize as JSON.
	 *
	 * @param string|false $wikiId (since 1.38)
	 * @return mixed Identifying information
	 */
	public function getIdentifier( $wikiId = self::LOCAL );

	/**
	 * Get the user who applied this block
	 *
	 * @return UserIdentity|null user identity or null. May be an external user.
	 */
	public function getBlocker(): ?UserIdentity;

	/**
	 * Get the reason for creating the block.
	 *
	 * @return CommentStoreComment
	 */
	public function getReasonComment(): CommentStoreComment;

	/**
	 * Get the UserIdentity identifying the blocked user,
	 * if the target is indeed a user (that is, if getType() returns TYPE_USER).
	 *
	 * @return ?UserIdentity
	 */
	public function getTargetUserIdentity(): ?UserIdentity;

	/**
	 * Return the name of the block target as a string.
	 * Depending on the type returned by get Type(), this could be a user name,
	 * an IP address or range, an internal ID, etc.
	 *
	 * @return string
	 */
	public function getTargetName(): string;

	/**
	 * Determines whether this block is blocking the given target (and only that target).
	 *
	 * @param UserIdentity|string $target
	 *
	 * @return bool
	 */
	public function isBlocking( $target ): bool;

	/**
	 * Get the block expiry time
	 *
	 * @return string
	 */
	public function getExpiry(): string;

	/**
	 * Get the type of target for this particular block.
	 * @return int|null Block::TYPE_ constant, will never be TYPE_ID
	 */
	public function getType(): ?int;

	/**
	 * Get the timestamp indicating when the block was created
	 *
	 * @return string
	 */
	public function getTimestamp(): string;

	/**
	 * Indicates that the block is a sitewide block. This means the user is
	 * prohibited from editing any page on the site (other than their own talk
	 * page).
	 *
	 * @return bool
	 */
	public function isSitewide(): bool;

	/**
	 * Get the flag indicating whether this block blocks the target from
	 * creating an account. (Note that the flag may be overridden depending on
	 * global configs.)
	 *
	 * @return bool
	 */
	public function isCreateAccountBlocked(): bool;

	/**
	 * Returns whether the block is a hardblock (affects logged-in users on a given IP/range)
	 *
	 * Note that users are always hardblocked, since they're logged in by definition.
	 *
	 * @return bool
	 */
	public function isHardblock(): bool;

}
