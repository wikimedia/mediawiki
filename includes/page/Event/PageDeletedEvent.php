<?php

namespace MediaWiki\Page\Event;

use MediaWiki\Page\ExistingPageRecord;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Storage\PageUpdateCauses;
use MediaWiki\User\UserIdentity;
use Wikimedia\Assert\Assert;

/**
 * Domain event representing page deletion.
 *
 * @see PageCreatedEvent
 *
 * @unstable until 1.45
 */
class PageDeletedEvent extends PageEvent {

	public const TYPE = 'PageDeleted';

	/**
	 * Whether the deleted revisions and log have been suppressed, so they
	 * are not visible in the regular deletion log.
	 */
	public const FLAG_SUPPRESSED = 'suppressed';

	private ExistingPageRecord $pageStateBefore;
	private RevisionRecord $latestRevisionBefore;
	private string $reason;
	private int $archivedRevisionCount;

	public function __construct(
		ProperPageIdentity $pageAfterDeletion,
		ExistingPageRecord $pageStateBefore,
		RevisionRecord $latestRevisionBefore,
		UserIdentity $performer,
		array $tags,
		array $flags,
		$timestamp,
		string $reason,
		int $archivedRevisionCount
	) {
		parent::__construct(
			PageUpdateCauses::CAUSE_DELETE,
			$pageAfterDeletion,
			$performer,
			$tags,
			$flags,
			$timestamp
		);

		Assert::parameter(
			!$pageAfterDeletion->exists(),
			'$page',
			'must represent the page after deletion'
		);

		$this->declareEventType( self::TYPE );
		$this->pageStateBefore = $pageStateBefore;
		$this->latestRevisionBefore = $latestRevisionBefore;
		$this->reason = $reason;
		$this->archivedRevisionCount = $archivedRevisionCount;
	}

	/**
	 * Returns the revision that was the page's latest revision when the
	 * page was deleted.
	 */
	public function getPageStateBefore(): ExistingPageRecord {
		return $this->pageStateBefore;
	}

	/**
	 * Returns the revision that was the page's latest revision when the
	 * page was deleted.
	 */
	public function getLatestRevisionBefore(): RevisionRecord {
		return $this->latestRevisionBefore;
	}

	/**
	 * Returns the reason for deletion, as supplied by the user.
	 */
	public function getReason(): string {
		return $this->reason;
	}

	/**
	 * Returns the number of revisions archived by the deletion.
	 */
	public function getArchivedRevisionCount(): int {
		return $this->archivedRevisionCount;
	}

	/**
	 * Whether the deleted revisions and log have been suppressed, so they
	 * are not visible in the regular deletion log.
	 */
	public function isSuppressed(): bool {
		return $this->hasFlag( self::FLAG_SUPPRESSED );
	}

}
