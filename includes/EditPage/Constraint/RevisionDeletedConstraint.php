<?php

namespace MediaWiki\EditPage\Constraint;

use MediaWiki\EditPage\EditPageStatus;
use MediaWiki\Page\Article;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStoreRecord;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use Wikimedia\Message\MessageValue;

/**
 * This constraint is used to display an error if the user loses access to the revision while
 * editing it (T301947), or if it goes missing.
 *
 * @since 1.46
 * @internal
 */
class RevisionDeletedConstraint implements IEditConstraint {

	public function __construct(
		private readonly Article $article,
		private readonly bool $ignoreWarning,
		private readonly int $oldId,
		private readonly string $section,
		private readonly Title $title,
		private readonly User $user,
		private readonly ?MessageValue $warningMessageWrapper = null,
	) {
	}

	/**
	 * @inheritDoc
	 */
	public function checkConstraint(): EditPageStatus {
		if ( $this->section === 'new' ) {
			return EditPageStatus::newGood();
		}

		$revRecord = $this->article->fetchRevisionRecord();
		if ( $revRecord instanceof RevisionStoreRecord ) {
			if ( !$revRecord->userCan( RevisionRecord::DELETED_TEXT, $this->user ) ) {
				return EditPageStatus::newFatal( 'rev-deleted-text-permission', $this->title->getPrefixedURL() )
					->setValue( self::AS_REVISION_WAS_DELETED );
			} elseif ( $revRecord->isDeleted( RevisionRecord::DELETED_TEXT ) ) {
				// Let sysop know that this will make private content public if saved
				$status = EditPageStatus::newGood( self::AS_REVISION_WAS_DELETED );
				$warningMessage = MessageValue::new(
					'rev-deleted-text-view',
					[ $this->title->getPrefixedURL() ]
				);
				// When saving the edit, explain the user how to proceed by wrapping the warning in another message
				if ( $this->warningMessageWrapper !== null ) {
					$warningMessage = $this->warningMessageWrapper->params( $warningMessage );
				}
				return $status->warning( $warningMessage )->setOK( $this->ignoreWarning );
			}
		} elseif ( $this->title->exists() ) {
			// Something went wrong, and the revision is missing
			return EditPageStatus::newFatal( 'missing-revision', $this->oldId )
				->setValue( self::AS_REVISION_MISSING );
		}

		return EditPageStatus::newGood();
	}
}
