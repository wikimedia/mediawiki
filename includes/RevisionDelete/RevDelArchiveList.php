<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup RevisionDelete
 */

namespace MediaWiki\RevisionDelete;

use MediaWiki\Cache\HTMLCacheUpdater;
use MediaWiki\ChangeTags\ChangeTagsFormatter;
use MediaWiki\Context\IContextSource;
use MediaWiki\DomainEvent\DomainEventDispatcher;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Logging\LogEntry;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Status\Status;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * List for archive table items, i.e. revisions deleted via action=delete
 */
class RevDelArchiveList extends RevDelRevisionList {

	public function __construct(
		IContextSource $context,
		PageIdentity $page,
		array $ids,
		LBFactory $lbFactory,
		HookContainer $hookContainer,
		HTMLCacheUpdater $htmlCacheUpdater,
		private readonly RevisionStore $revisionStore,
		DomainEventDispatcher $eventDispatcher,
		private readonly ChangeTagsFormatter $changeTagsFormatter
	) {
		parent::__construct(
			$context,
			$page,
			$ids,
			$lbFactory,
			$hookContainer,
			$htmlCacheUpdater,
			$this->revisionStore,
			$eventDispatcher,
			$this->changeTagsFormatter
		);
	}

	/** @inheritDoc */
	public function getType() {
		return 'archive';
	}

	/** @inheritDoc */
	public static function getRelationType() {
		return 'ar_timestamp';
	}

	/**
	 * @param \Wikimedia\Rdbms\IReadableDatabase $db
	 * @return IResultWrapper
	 */
	public function doQuery( $db ) {
		$timestamps = [];
		foreach ( $this->ids as $id ) {
			$timestamps[] = $db->timestamp( $id );
		}

		$queryBuilder = $this->revisionStore->newArchiveSelectQueryBuilder( $db )
			->joinComment()
			->where( [
				'ar_namespace' => $this->getPage()->getNamespace(),
				'ar_title' => $this->getPage()->getDBkey(),
				'ar_timestamp' => $timestamps,
			] )
			->orderBy( 'ar_timestamp', SelectQueryBuilder::SORT_DESC );

		MediaWikiServices::getInstance()->getChangeTagsStore()
			->addTagsToDisplayQuery( $queryBuilder, 'archive', $this->getAuthority() );

		return $queryBuilder->caller( __METHOD__ )->fetchResultSet();
	}

	/** @inheritDoc */
	public function newItem( $row ) {
		return new RevDelArchiveItem( $this, $row, $this->changeTagsFormatter );
	}

	/** @inheritDoc */
	public function doPreCommitUpdates() {
		return Status::newGood();
	}

	/** @inheritDoc */
	public function doPostCommitUpdates( array $visibilityChangeMap ) {
		return Status::newGood();
	}

	/**
	 * @param array $bitPars See RevisionDeleter::extractBitfield
	 * @param array $visibilityChangeMap [id => ['oldBits' => $oldBits, 'newBits' => $newBits], ... ]
	 * @param array $tags
	 * @param LogEntry $logEntry
	 * @param bool $suppressed
	 */
	protected function emitEvents(
		array $bitPars,
		array $visibilityChangeMap,
		array $tags,
		LogEntry $logEntry,
		bool $suppressed
	) {
		// Do not emit PageHistoryVisibilityChangedEvent for archived revisions.
		// We could emit a ArchiveVisibilityChangedEvent in the future.
		// PageHistoryVisibilityChangedEvent and ArchiveVisibilityChangedEvent
		// should then share a base class, RevisionVisibilityChangedEvent.
	}

}

/** @deprecated class alias since 1.46 */
class_alias( RevDelArchiveList::class, 'RevDelArchiveList' );
