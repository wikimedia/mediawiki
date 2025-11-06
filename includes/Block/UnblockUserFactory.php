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
interface UnblockUserFactory {
	/**
	 * @param BlockTarget|UserIdentity|string $target
	 * @param Authority $performer
	 * @param string $reason
	 * @param string[] $tags
	 *
	 * @return UnblockUser
	 */
	public function newUnblockUser(
		$target,
		Authority $performer,
		string $reason,
		array $tags = []
	): UnblockUser;

	/**
	 * Creates UnblockUser to remove a specific block
	 *
	 * @since 1.44
	 *
	 * @param DatabaseBlock $block
	 * @param Authority $performer
	 * @param string $reason
	 * @param array $tags
	 *
	 * @return UnblockUser
	 */
	public function newRemoveBlock(
		DatabaseBlock $block,
		Authority $performer,
		string $reason,
		array $tags = []
	): UnblockUser;
}
