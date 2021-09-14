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

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\Linker\LinkTarget;
use MediaWiki\Permissions\PermissionManager;
use StatusValue;
use User;

/**
 * Verify user permissions:
 *    Must not be blocked from the page
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class UserBlockConstraint implements IEditConstraint {

	/** @var PermissionManager */
	private $permissionManager;

	/** @var LinkTarget */
	private $title;

	/** @var User */
	private $user;

	/** @var string|null */
	private $result;

	/**
	 * @param PermissionManager $permissionManager
	 * @param LinkTarget $title
	 * @param User $user
	 */
	public function __construct(
		PermissionManager $permissionManager,
		LinkTarget $title,
		User $user
	) {
		$this->permissionManager = $permissionManager;
		$this->title = $title;
		$this->user = $user;
	}

	public function checkConstraint(): string {
		// Check isn't simple enough to just repeat when getting the status
		if ( $this->permissionManager->isBlockedFrom( $this->user, $this->title ) ) {
			$this->result = self::CONSTRAINT_FAILED;
			return self::CONSTRAINT_FAILED;
		}

		$this->result = self::CONSTRAINT_PASSED;
		return self::CONSTRAINT_PASSED;
	}

	public function getLegacyStatus(): StatusValue {
		$statusValue = StatusValue::newGood();

		if ( $this->result === self::CONSTRAINT_FAILED ) {
			$statusValue->setResult( false, self::AS_BLOCKED_PAGE_FOR_USER );
		}

		return $statusValue;
	}

}
