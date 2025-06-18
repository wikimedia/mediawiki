<?php

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Context\RequestContext;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\Event\PageLatestRevisionChangedEvent;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Storage\PageUpdater;
use MediaWiki\Storage\PageUpdaterFactory;
use MediaWiki\Title\Title;
use MediaWiki\User\UserFactory;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * @since 1.31
 */
class ImportableOldRevisionImporter implements OldRevisionImporter {

	private bool $doUpdates;
	private LoggerInterface $logger;
	private IConnectionProvider $dbProvider;
	private RevisionStore $revisionStore;
	private SlotRoleRegistry $slotRoleRegistry;
	private WikiPageFactory $wikiPageFactory;
	private PageUpdaterFactory $pageUpdaterFactory;
	private UserFactory $userFactory;

	public function __construct(
		$doUpdates,
		LoggerInterface $logger,
		IConnectionProvider $dbProvider,
		RevisionStore $revisionStore,
		SlotRoleRegistry $slotRoleRegistry,
		?WikiPageFactory $wikiPageFactory = null,
		?PageUpdaterFactory $pageUpdaterFactory = null,
		?UserFactory $userFactory = null
	) {
		$this->doUpdates = $doUpdates;
		$this->logger = $logger;
		$this->dbProvider = $dbProvider;
		$this->revisionStore = $revisionStore;
		$this->slotRoleRegistry = $slotRoleRegistry;

		$services = MediaWikiServices::getInstance();
		// @todo: temporary - remove when FileImporter extension is updated
		$this->wikiPageFactory = $wikiPageFactory ?? $services->getWikiPageFactory();
		$this->pageUpdaterFactory = $pageUpdaterFactory ?? $services->getPageUpdaterFactory();
		$this->userFactory = $userFactory ?? $services->getUserFactory();
	}

	/** @inheritDoc */
	public function import( ImportableOldRevision $importableRevision, $doUpdates = true ) {
		$dbw = $this->dbProvider->getPrimaryDatabase();

		# Sneak a single revision into place
		$user = $importableRevision->getUserObj() ?: $this->userFactory->newFromName( $importableRevision->getUser() );
		if ( $user ) {
			$userId = $user->getId();
			$userText = $user->getName();
		} else {
			$userId = 0;
			$userText = $importableRevision->getUser();
			$user = $this->userFactory->newAnonymous();
		}

		// avoid memory leak...?
		Title::clearCaches();

		$page = $this->wikiPageFactory->newFromTitle( $importableRevision->getTitle() );
		$page->loadPageData( IDBAccessObject::READ_LATEST );
		$mustCreatePage = !$page->exists();
		if ( $mustCreatePage ) {
			$pageId = $page->insertOn( $dbw );
		} else {
			$pageId = $page->getId();

			// Note: sha1 has been in XML dumps since 2012. If you have an
			// older dump, the duplicate detection here won't work.
			if ( $importableRevision->getSha1Base36() !== false ) {
				$prior = (bool)$dbw->newSelectQueryBuilder()
					->select( '1' )
					->from( 'revision' )
					->where( [
						'rev_page' => $pageId,
						'rev_timestamp' => $dbw->timestamp( $importableRevision->getTimestamp() ),
						'rev_sha1' => $importableRevision->getSha1Base36()
					] )
					->caller( __METHOD__ )->fetchField();
				if ( $prior ) {
					// @todo FIXME: This could fail slightly for multiple matches :P
					$this->logger->debug( __METHOD__ . ": skipping existing revision for [[" .
						$importableRevision->getTitle()->getPrefixedText() . "]], timestamp " .
						$importableRevision->getTimestamp() . "\n" );
					return false;
				}
			}
		}

		if ( !$pageId ) {
			// This seems to happen if two clients simultaneously try to import the
			// same page
			$this->logger->debug( __METHOD__ . ': got invalid $pageId when importing revision of [[' .
				$importableRevision->getTitle()->getPrefixedText() . ']], timestamp ' .
				$importableRevision->getTimestamp() . "\n" );
			return false;
		}

		// Select previous version to make size diffs correct
		// @todo This assumes that multiple revisions of the same page are imported
		// in order from oldest to newest.
		$queryBuilder = $this->revisionStore->newSelectQueryBuilder( $dbw )
			->joinComment()
			->where( [ 'rev_page' => $pageId ] )
			->andWhere( $dbw->expr(
				'rev_timestamp', '<=', $dbw->timestamp( $importableRevision->getTimestamp() )
			) )
			->orderBy( [ 'rev_timestamp', 'rev_id' ], SelectQueryBuilder::SORT_DESC );
		$prevRevRow = $queryBuilder->caller( __METHOD__ )->fetchRow();

		# @todo FIXME: Use original rev_id optionally (better for backups)
		# Insert the row
		$revisionRecord = new MutableRevisionRecord( $importableRevision->getTitle() );
		$revisionRecord->setParentId( $prevRevRow ? (int)$prevRevRow->rev_id : 0 );
		$revisionRecord->setComment(
			CommentStoreComment::newUnsavedComment( $importableRevision->getComment() )
		);

		try {
			$revUser = $this->userFactory->newFromAnyId( $userId, $userText );
		} catch ( InvalidArgumentException ) {
			$revUser = RequestContext::getMain()->getUser();
		}
		$revisionRecord->setUser( $revUser );

		$originalRevision = $prevRevRow
			? $this->revisionStore->newRevisionFromRow(
				$prevRevRow,
				IDBAccessObject::READ_LATEST,
				$importableRevision->getTitle()
			)
			: null;

		foreach ( $importableRevision->getSlotRoles() as $role ) {
			if ( !$this->slotRoleRegistry->isDefinedRole( $role ) ) {
				throw new RuntimeException( "Undefined slot role $role" );
			}

			$newContent = $importableRevision->getContent( $role );
			if ( !$originalRevision || !$originalRevision->hasSlot( $role ) ) {
				$revisionRecord->setContent( $role, $newContent );
			} else {
				$originalSlot = $originalRevision->getSlot( $role );
				if ( !$originalSlot->hasSameContent( $importableRevision->getSlot( $role ) ) ) {
					$revisionRecord->setContent( $role, $newContent );
				} else {
					$revisionRecord->inheritSlot( $originalRevision->getSlot( $role ) );
				}
			}
		}

		$revisionRecord->setTimestamp( $importableRevision->getTimestamp() );
		$revisionRecord->setMinorEdit( $importableRevision->getMinor() );
		$revisionRecord->setPageId( $pageId );

		$updater = $this->pageUpdaterFactory->newDerivedPageDataUpdater( $page );
		$latestRev = $updater->grabCurrentRevision();
		$latestRevId = $latestRev ? $latestRev->getId() : null;

		$inserted = $this->revisionStore->insertRevisionOn( $revisionRecord, $dbw );
		if ( $latestRev ) {
			// If not found (false), cast to 0 so that the page is updated
			// Just to be on the safe side, even though it should always be found
			$latestRevTimestamp = $latestRev->getTimestamp();
		} else {
			$latestRevTimestamp = 0;
		}
		if ( $importableRevision->getTimestamp() >= $latestRevTimestamp ) {
			$changed = $page->updateRevisionOn( $dbw, $inserted, $latestRevId );
		} else {
			$changed = false;
		}

		$tags = $importableRevision->getTags();
		if ( $tags !== [] ) {
			MediaWikiServices::getInstance()->getChangeTagsStore()->addTags( $tags, null, $inserted->getId() );
		}

		if ( $changed !== false && $this->doUpdates ) {
			$this->logger->debug( __METHOD__ . ": running updates" );
			// countable/oldcountable stuff is handled in WikiImporter::finishImportPage

			$options = [
				PageLatestRevisionChangedEvent::FLAG_SILENT => true,
				PageLatestRevisionChangedEvent::FLAG_IMPLICIT => true,
				'created' => $mustCreatePage,
				'oldcountable' => 'no-change',
			];

			$updater->setCause( PageUpdater::CAUSE_IMPORT );
			$updater->setPerformer( $user ); // TODO: get the actual performer, not the revision author.
			$updater->prepareUpdate( $inserted, $options );
			$updater->doUpdates();
		}

		return true;
	}

}
