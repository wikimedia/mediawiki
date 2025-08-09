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

use MediaWiki\Page\PageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use StatusValue;

/**
 * Verify authorization to edit the page (user rights, rate limits, blocks).
 *
 * @since 1.44
 * @internal
 */
class AuthorizationConstraint implements IEditConstraint {

	private PermissionStatus $status;

	public function __construct(
		private readonly Authority $performer,
		private readonly PageIdentity $target,
		private readonly bool $new,
	) {
	}

	public function checkConstraint(): string {
		$this->status = PermissionStatus::newEmpty();

		if ( $this->new && !$this->performer->authorizeWrite( 'create', $this->target, $this->status ) ) {
			return self::CONSTRAINT_FAILED;
		}

		if ( !$this->performer->authorizeWrite( 'edit', $this->target, $this->status ) ) {
			return self::CONSTRAINT_FAILED;
		}

		return self::CONSTRAINT_PASSED;
	}

	public function getLegacyStatus(): StatusValue {
		$statusValue = StatusValue::newGood();

		if ( !$this->status->isGood() ) {
			// Report the most specific errors first
			if ( $this->status->isBlocked() ) {
				$statusValue->setResult( false, self::AS_BLOCKED_PAGE_FOR_USER );
			} elseif ( $this->status->isRateLimitExceeded() ) {
				$statusValue->setResult( false, self::AS_RATE_LIMITED );
			} elseif ( $this->status->getPermission() === 'create' ) {
				$statusValue->setResult( false, self::AS_NO_CREATE_PERMISSION );
			} elseif ( !$this->performer->isRegistered() ) {
				$statusValue->setResult( false, self::AS_READ_ONLY_PAGE_ANON );
			} else {
				$statusValue->setResult( false, self::AS_READ_ONLY_PAGE_LOGGED );
			}
		}

		// TODO: Use error messages from the PermissionStatus ($this->status) here - T384399
		return $statusValue;
	}

}
