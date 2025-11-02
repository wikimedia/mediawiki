<?php
/**
 * @license GPL-2.0-or-later
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
