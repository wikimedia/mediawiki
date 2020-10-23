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
 *    If creating a redirect in the file namespace, must have upload rights
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class ImageRedirectConstraint implements IEditConstraint {

	/** @var PermissionManager */
	private $permissionManager;

	/** @var LinkTarget */
	private $title;

	/** @var bool */
	private $isRedirect;

	/** @var User */
	private $user;

	/** @var string|null */
	private $result;

	/**
	 * @param PermissionManager $permissionManager
	 * @param LinkTarget $title
	 * @param bool $isRedirect
	 * @param User $user
	 */
	public function __construct(
		PermissionManager $permissionManager,
		LinkTarget $title,
		bool $isRedirect,
		User $user
	) {
		$this->permissionManager = $permissionManager;
		$this->title = $title;
		$this->isRedirect = $isRedirect;
		$this->user = $user;
	}

	public function checkConstraint() : string {
		// Check isn't simple enough to just repeat when getting the status
		if ( $this->title->getNamespace() === NS_FILE &&
			$this->isRedirect &&
			!$this->permissionManager->userHasRight( $this->user, 'upload' )
		) {
			$this->result = self::CONSTRAINT_FAILED;
			return self::CONSTRAINT_FAILED;
		}

		$this->result = self::CONSTRAINT_PASSED;
		return self::CONSTRAINT_PASSED;
	}

	public function getLegacyStatus() : StatusValue {
		$statusValue = StatusValue::newGood();

		if ( $this->result === self::CONSTRAINT_FAILED ) {
			$errorCode = $this->user->isAnon() ?
				self::AS_IMAGE_REDIRECT_ANON :
				self::AS_IMAGE_REDIRECT_LOGGED;
			$statusValue->setResult( false, $errorCode );
		}

		return $statusValue;
	}

}
