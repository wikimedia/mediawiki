<?php

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\PageEdit\PageEditAttemptState;
use MediaWiki\PageEdit\PageEditStatus;
use MediaWiki\Permissions\Authority;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStoreRecord;
use MediaWiki\Title\Title;

/**
 * This constraint is used to display an error if the user loses access to the revision while
 * editing it (T301947), or if it goes missing.
 *
 * @since 1.46
 * @internal
 */
class RevisionDeletedConstraint extends EditConstraint {

	public function __construct(
		private readonly PageEditAttemptState $editAttemptState,
		private readonly int $oldId,
		private readonly ?RevisionRecord $revisionRecord,
		private readonly string $section,
		private readonly Title $title,
		private readonly Authority $authority,
	) {
	}

	/**
	 * @inheritDoc
	 */
	public function checkConstraint(): PageEditStatus {
		if ( $this->section === 'new' ) {
			return PageEditStatus::newGood();
		}

		if ( $this->revisionRecord instanceof RevisionStoreRecord ) {
			if ( !$this->revisionRecord->userCan( RevisionRecord::DELETED_TEXT, $this->authority ) ) {
				return PageEditStatus::newFatal( 'rev-deleted-text-permission', $this->title->getPrefixedURL() )
					->setValue( self::AS_REVISION_WAS_DELETED );
			} elseif ( $this->revisionRecord->isDeleted( RevisionRecord::DELETED_TEXT ) ) {
				// Let sysop know that this will make private content public if saved
				$status = PageEditStatus::newGood( self::AS_REVISION_WAS_DELETED )
					->setOK( $this->editAttemptState->shouldIgnoreRevisionDeletedWarning() );
				$this->editAttemptState->setIgnoreRevisionDeletedWarning( true );
				return $status
					->warning( 'rev-deleted-text-view', $this->title->getPrefixedURL() )
					->setCanResubmit( true );
			}
		} elseif ( $this->title->exists() ) {
			// Something went wrong, and the revision is missing
			return PageEditStatus::newFatal( 'missing-revision', $this->oldId )
				->setValue( self::AS_REVISION_MISSING );
		}

		return PageEditStatus::newGood();
	}
}
