<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\EditPage\EditPageStatus;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Title\Title;

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

	public function checkConstraint(): EditPageStatus {
		if ( $this->newContentModel === $this->title->getContentModel() ) {
			return EditPageStatus::newGood();
		}

		$status = PermissionStatus::newEmpty();

		if ( !$this->performer->authorizeWrite( 'editcontentmodel', $this->title, $status ) ) {
			return $this->castPermissionStatus( $status );
		}

		// Make sure the user can edit the page under the new content model too.
		// We rely on caching in UserAuthority to avoid bumping the rate limit counter twice.
		$titleWithNewContentModel = clone $this->title;
		$titleWithNewContentModel->setContentModel( $this->newContentModel );
		if (
			!$this->performer->authorizeWrite( 'editcontentmodel', $titleWithNewContentModel, $status )
			|| !$this->performer->authorizeWrite( 'edit', $titleWithNewContentModel, $status )
		) {
			return $this->castPermissionStatus( $status );
		}

		return EditPageStatus::newGood();
	}

	private function castPermissionStatus( PermissionStatus $status ): EditPageStatus {
		$statusValue = EditPageStatus::newGood();

		if ( !$status->isGood() ) {
			if ( $status->isRateLimitExceeded() ) {
				$statusValue->setResult( false, self::AS_RATE_LIMITED );
			} else {
				$statusValue->setResult( false, self::AS_NO_CHANGE_CONTENT_MODEL );
			}
		}

		// TODO: Use error messages from the PermissionStatus ($status) here - T384399
		return $statusValue;
	}

}
