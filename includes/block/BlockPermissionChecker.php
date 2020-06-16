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

use MediaWiki\Permissions\PermissionManager;
use MediaWiki\User\UserIdentity;
use User;

/**
 * Block permissions
 *
 * This class is responsible for making sure a user has permission to block
 *
 * This class is usable for both blocking as well as
 * the unblocking process.
 *
 * @since 1.35
 */
class BlockPermissionChecker {
	/**
	 * @var UserIdentity|string Block target
	 */
	private $target;

	/**
	 * @var int $targetType One of AbstractBlock::TYPE_* constants
	 */
	private $targetType = null;

	/**
	 * @var User Block performer
	 */
	private $performer;

	/**
	 * @var PermissionManager
	 */
	private $permissionManager;

	public function __construct(
		PermissionManager $permissionManager,
		$target,
		User $performer
	) {
		$this->permissionManager = $permissionManager;
		list( $this->target, $this->targetType ) = AbstractBlock::parseTarget( $target );
		$this->performer = $performer;
	}

	/**
	 * Checks block-related permissions (doesn't check any other permissions)
	 *
	 * T17810: Sitewide blocked admins should not be able to block/unblock
	 * others with one exception; they can block the user who blocked them,
	 * to reduce advantage of a malicious account blocking all admins (T150826).
	 *
	 * T208965: Partially blocked admins can block and unblock others as normal.
	 *
	 * @return bool|string True when checks passed, message code for failures
	 */
	public function checkBlockPermissions() {
		$block = $this->performer->getBlock();
		if ( !$block ) {
			// User is not blocked, process as normal
			return true;
		}

		if ( !$block->isSitewide() ) {
			// T208965: Partially blocked admins should have full access
			return true;
		}

		if (
			$this->target instanceof UserIdentity &&
			$this->target->getId() === $this->performer->getId()
		) {
			// Blocked admin is trying to alter their own block

			// Self-blocked admins can always remove or alter their block
			if ( $this->performer->blockedBy() === $this->performer->getName() ) {
				return true;
			}

			// Users with 'unblockself' right can unblock themselves or alter their own block
			if ( $this->permissionManager->userHasRight( $this->performer, 'unblockself' ) ) {
				return true;
			} else {
				return 'ipbnounblockself';
			}
		}

		if (
			$this->target instanceof UserIdentity &&
			$this->performer->blockedBy() === $this->target->getName()
		) {
			// T150826: Blocked admins can always block the admin who blocked them
			return true;
		}

		// User is blocked and no exception took effect
		return 'ipbblocked';
	}
}
