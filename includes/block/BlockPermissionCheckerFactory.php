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
use User;

/**
 * Factory class for BlockPermissionChecker
 *
 * @since 1.35
 */
class BlockPermissionCheckerFactory {

	/**
	 * @var PermissionManager
	 */
	private $permissionManager;

	/**
	 * @param PermissionManager $permissionManager
	 */
	public function __construct( PermissionManager $permissionManager ) {
		$this->permissionManager = $permissionManager;
	}

	/**
	 * @param User|string|int $target Target of the validated block
	 * @param User $performer Performer of the validated block
	 *
	 * @return BlockPermissionChecker
	 */
	public function newBlockPermissionChecker(
		$target,
		User $performer
	) {
		return new BlockPermissionChecker(
			$this->permissionManager,
			$target,
			$performer
		);
	}
}
