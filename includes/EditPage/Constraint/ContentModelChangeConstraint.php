<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Title\Title;
use StatusValue;

/**
 * Verify user permissions if changing content model:
 *    Must have editcontentmodel rights
 *    Must be able to edit under the new content model
 *    Must not have exceeded the rate limit
 *
 * @since 1.36
 * @internal
 * @author DannyS712
 */
class ContentModelChangeConstraint implements IEditConstraint {

	private PermissionStatus $status;

	/**
	 * @param Authority $performer
	 * @param Title $title
	 * @param string $newContentModel
	 */
	public function __construct(
		private readonly Authority $performer,
		private readonly Title $title,
		private readonly string $newContentModel,
	) {
	}

	public function checkConstraint(): string {
		$this->status = PermissionStatus::newEmpty();

		if ( $this->newContentModel === $this->title->getContentModel() ) {
			// No change
			return self::CONSTRAINT_PASSED;
		}

		if ( !$this->performer->authorizeWrite( 'editcontentmodel', $this->title, $this->status ) ) {
			return self::CONSTRAINT_FAILED;
		}

		// Make sure the user can edit the page under the new content model too.
		// We rely on caching in UserAuthority to avoid bumping the rate limit counter twice.
		$titleWithNewContentModel = clone $this->title;
		$titleWithNewContentModel->setContentModel( $this->newContentModel );
		if (
			!$this->performer->authorizeWrite( 'editcontentmodel', $titleWithNewContentModel, $this->status )
			|| !$this->performer->authorizeWrite( 'edit', $titleWithNewContentModel, $this->status )
		) {
			return self::CONSTRAINT_FAILED;
		}

		return self::CONSTRAINT_PASSED;
	}

	public function getLegacyStatus(): StatusValue {
		$statusValue = StatusValue::newGood();

		if ( !$this->status->isGood() ) {
			if ( $this->status->isRateLimitExceeded() ) {
				$statusValue->setResult( false, self::AS_RATE_LIMITED );
			} else {
				$statusValue->setResult( false, self::AS_NO_CHANGE_CONTENT_MODEL );
			}
		}

		// TODO: Use error messages from the PermissionStatus ($this->status) here - T384399
		return $statusValue;
	}

}
