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

	/**
	 * @suppress PhanTypeMismatchArgumentProbablyReal While PermissionStatus is documented to never have a
	 * value, it is still possible to set one, which avoids duplicating error logic to EditPage.php (see
	 * gerrit change 1226380).
	 */
	public function getLegacyStatus(): StatusValue {
		$status = $this->status;

		if ( $status->isGood() ) {
			return $status;
		}

		// Report the most specific errors first
		if ( $status->isBlocked() ) {
			$status->setResult( false, self::AS_BLOCKED_PAGE_FOR_USER );
		} elseif ( $status->isRateLimitExceeded() ) {
			$status->setResult( false, self::AS_RATE_LIMITED );
		} elseif ( $status->getPermission() === 'create' ) {
			$status->setResult( false, self::AS_NO_CREATE_PERMISSION );
		} elseif ( !$this->performer->isRegistered() ) {
			$status->setResult( false, self::AS_READ_ONLY_PAGE_ANON );
		} else {
			$status->setResult( false, self::AS_READ_ONLY_PAGE_LOGGED );
		}

		return $status;
	}

}
