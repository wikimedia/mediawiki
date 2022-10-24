<?php

/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Block;

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\User\UserIdentity;

/**
 * Represents a block that may prevent users from performing specific operations.
 * The block may apply to a specific user, to a network address, network range,
 * or some other aspect of a web request.
 *
 * The block may apply to the entire site, or may be limited to specific pages
 * or namespaces.
 *
 * @since 1.37
 *
 * Extracted from the AbstractBlock base class, which was in turn factored out
 * of DatabaseBlock in 1.34. Block was introduced as a narrow interface for
 * Authority. It avoids legacy types such as User and Title. However, all
 * implementations should continue to extend AbstractBlock.
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

	/**
	 * Map block types to strings, to allow convenient logging.
	 */
	public const BLOCK_TYPES = [
		self::TYPE_USER => 'user',
		self::TYPE_IP => 'ip',
		self::TYPE_RANGE => 'range',
		self::TYPE_AUTO => 'autoblock',
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
	 * Get the target as an object.
	 *
	 * For autoblocks this can be either the IP address or the autoblock ID
	 * depending on how the block was loaded. Use getRedactedTarget() to safely
	 * get a target for display.
	 *
	 * @since 1.44
	 * @return BlockTarget|null
	 */
	public function getTarget(): ?BlockTarget;

	/**
	 * Get the target, with the IP address hidden behind an AutoBlockTarget
	 * if the block is an autoblock.
	 *
	 * @since 1.44
	 * @return BlockTarget|null
	 */
	public function getRedactedTarget(): ?BlockTarget;

	/**
	 * Get the UserIdentity identifying the blocked user,
	 * if the target is indeed a user (that is, if getType() returns TYPE_USER).
	 *
	 * @return ?UserIdentity
	 */
	public function getTargetUserIdentity(): ?UserIdentity;

	/**
	 * Return the name of the block target as a string.
	 * Depending on the type returned by getType(), this could be a user name,
	 * an IP address or range, an internal ID, etc.
	 *
	 * @return string
	 */
	public function getTargetName(): string;

	/**
	 * Determine whether this block is blocking the given target (and only that target).
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
	 * Is the block indefinite (with no fixed expiry)?
	 *
	 * @since 1.44
	 * @return bool
	 */
	public function isIndefinite(): bool;

	/**
	 * Get the type of target for this particular block.
	 * @return int|null Block::TYPE_ constant
	 */
	public function getType(): ?int;

	/**
	 * Get the timestamp indicating when the block was created
	 *
	 * @return string
	 */
	public function getTimestamp(): string;

	/**
	 * Get whether the block is a sitewide block. This means the user is
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
	 * Get the flag indicating whether this block blocks the target from
	 * sending emails. (Note that the flag may be overridden depending on
	 * global configs.)
	 */
	public function isEmailBlocked(): bool;

	/**
	 * Get whether the block is a hard block (affects logged-in users on a given IP/range).
	 *
	 * Note that temporary users are not considered logged-in here - they are always blocked
	 * by IP-address blocks.
	 *
	 * Note that user blocks are always hard blocks, since the target is logged in by definition.
	 *
	 * @return bool
	 */
	public function isHardblock(): bool;

	/**
	 * Convert a block to an array of blocks. If the block is a composite
	 * block, return the array of original blocks. Otherwise, return [$this].
	 *
	 * @since 1.41
	 * @return Block[]
	 */
	public function toArray(): array;

	/**
	 * Returns whether the block prevents user talk page access. If this returns true, the user
	 * will be unable to make any changes to their user talk page for the duration of the block.
	 */
	public function appliesToUsertalk(): bool;

}
