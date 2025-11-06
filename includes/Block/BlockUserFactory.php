<?php

/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Block;

use MediaWiki\Permissions\Authority;
use MediaWiki\User\UserIdentity;

/**
 * @since 1.36
 */
interface BlockUserFactory {
	/**
	 * Create BlockUser
	 *
	 * @param BlockTarget|string|UserIdentity $target Target of the block
	 * @param Authority $performer Performer of the block
	 * @param string $expiry Expiry of the block (timestamp or 'infinity')
	 * @param string $reason Reason of the block
	 * @param array $blockOptions
	 * @param array $blockRestrictions
	 * @param array|null $tags Tags that should be assigned to the log entry
	 *
	 * @return BlockUser
	 */
	public function newBlockUser(
		$target,
		Authority $performer,
		string $expiry,
		string $reason = '',
		array $blockOptions = [],
		array $blockRestrictions = [],
		$tags = []
	): BlockUser;

	/**
	 * Create a BlockUser which updates a specified block
	 *
	 * @since 1.44
	 *
	 * @param DatabaseBlock $block The block to update
	 * @param Authority $performer Performer of the block
	 * @param string $expiry Expiry of the block (timestamp or 'infinity')
	 * @param string $reason Reason of the block
	 * @param array $blockOptions
	 * @param array $blockRestrictions
	 * @param array|null $tags Tags that should be assigned to the log entry
	 * @return BlockUser
	 */
	public function newUpdateBlock(
		DatabaseBlock $block,
		Authority $performer,
		string $expiry,
		string $reason = '',
		array $blockOptions = [],
		array $blockRestrictions = [],
		$tags = []
	): BlockUser;
}
