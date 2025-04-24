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

namespace MediaWiki\Page;

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\ValidationParams;
use MediaWiki\Exception\ReadOnlyError;
use MediaWiki\FileRepo\File\LocalFile;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\JobQueue\JobQueueGroup;
use MediaWiki\JobQueue\Jobs\HTMLCacheUpdateJob;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\Page\Event\PageRevisionUpdatedEvent;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Revision\ArchivedRevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Status\Status;
use MediaWiki\Storage\PageUpdater;
use MediaWiki\Storage\PageUpdaterFactory;
use MediaWiki\Title\NamespaceInfo;
use Psr\Log\LoggerInterface;
use StatusValue;
use Wikimedia\Assert\Assert;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageValue;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * Backend logic for performing a page undelete action.
 *
 * @since 1.38
 */
class UndeletePage {

	// Constants used as keys in the StatusValue returned by undelete()
	public const FILES_RESTORED = 'files';
	public const REVISIONS_RESTORED = 'revs';

	/** @var Status|null */
	private $fileStatus;
	/** @var StatusValue|null */
	private $revisionStatus;
	/** @var string[] */
	private $timestamps = [];
	/** @var int[] */
	private $fileVersions = [];
	/** @var bool */
	private $unsuppress = false;
	/** @var string[] */
	private $tags = [];
	/** @var WikiPage|null If not null, it means that we have to undelete it. */
	private $associatedTalk;

	private HookRunner $hookRunner;
	private JobQueueGroup $jobQueueGroup;
	private IConnectionProvider $dbProvider;
	private ReadOnlyMode $readOnlyMode;
	private RepoGroup $repoGroup;
	private LoggerInterface $logger;
	private RevisionStore $revisionStore;
	private WikiPageFactory $wikiPageFactory;
	private PageUpdaterFactory $pageUpdaterFactory;
	private IContentHandlerFactory $contentHandlerFactory;
	private ArchivedRevisionLookup $archivedRevisionLookup;
	private NamespaceInfo $namespaceInfo;
	private ITextFormatter $contLangMsgTextFormatter;
	private ProperPageIdentity $page;
	private Authority $performer;

	/**
	 * @internal Create via the UndeletePageFactory service.
	 */
	public function __construct(
		HookContainer $hookContainer,
		JobQueueGroup $jobQueueGroup,
		IConnectionProvider $dbProvider,
		ReadOnlyMode $readOnlyMode,
		RepoGroup $repoGroup,
		LoggerInterface $logger,
		RevisionStore $revisionStore,
		WikiPageFactory $wikiPageFactory,
		PageUpdaterFactory $pageUpdaterFactory,
		IContentHandlerFactory $contentHandlerFactory,
		ArchivedRevisionLookup $archivedRevisionLookup,
		NamespaceInfo $namespaceInfo,
		ITextFormatter $contLangMsgTextFormatter,
		ProperPageIdentity $page,
		Authority $performer
	) {
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->jobQueueGroup = $jobQueueGroup;
		$this->dbProvider = $dbProvider;
		$this->readOnlyMode = $readOnlyMode;
		$this->repoGroup = $repoGroup;
		$this->logger = $logger;
		$this->revisionStore = $revisionStore;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->pageUpdaterFactory = $pageUpdaterFactory;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->archivedRevisionLookup = $archivedRevisionLookup;
		$this->namespaceInfo = $namespaceInfo;
		$this->contLangMsgTextFormatter = $contLangMsgTextFormatter;

		$this->page = $page;
		$this->performer = $performer;
	}

	/**
	 * Whether to remove all ar_deleted/fa_deleted restrictions of selected revs.
	 *
	 * @param bool $unsuppress
	 * @return self For chaining
	 */
	public function setUnsuppress( bool $unsuppress ): self {
		$this->unsuppress = $unsuppress;
		return $this;
	}

	/**
	 * Change tags to add to log entry (the user should be able to add the specified tags before this is called)
	 *
	 * @param string[] $tags
	 * @return self For chaining
	 */
	public function setTags( array $tags ): self {
		$this->tags = $tags;
		return $this;
	}

	/**
	 * If you don't want to undelete all revisions, pass an array of timestamps to undelete.
	 *
	 * @param string[] $timestamps
	 * @return self For chaining
	 */
	public function setUndeleteOnlyTimestamps( array $timestamps ): self {
		$this->timestamps = $timestamps;
		return $this;
	}

	/**
	 * If you don't want to undelete all file versions, pass an array of versions to undelete.
	 *
	 * @param int[] $fileVersions
	 * @return self For chaining
	 */
	public function setUndeleteOnlyFileVersions( array $fileVersions ): self {
		$this->fileVersions = $fileVersions;
		return $this;
	}

	/**
	 * Tests whether it's probably possible to undelete the associated talk page. This checks the replica,
	 * so it may not see the latest master change, and is useful e.g. for building the UI.
	 */
	public function canProbablyUndeleteAssociatedTalk(): StatusValue {
		if ( $this->namespaceInfo->isTalk( $this->page->getNamespace() ) ) {
			return StatusValue::newFatal( 'undelete-error-associated-alreadytalk' );
		}
		// @todo FIXME: NamespaceInfo should work with PageIdentity
		$thisWikiPage = $this->wikiPageFactory->newFromTitle( $this->page );
		$talkPage = $this->wikiPageFactory->newFromLinkTarget(
			$this->namespaceInfo->getTalkPage( $thisWikiPage->getTitle() )
		);
		// NOTE: The talk may exist, but have some deleted revision. That's fine.
		if ( !$this->archivedRevisionLookup->hasArchivedRevisions( $talkPage ) ) {
			return StatusValue::newFatal( 'undelete-error-associated-notdeleted' );
		}
		return StatusValue::newGood();
	}

	/**
	 * Whether to delete the associated talk page with the subject page
	 *
	 * @param bool $undelete
	 * @return self For chaining
	 */
	public function setUndeleteAssociatedTalk( bool $undelete ): self {
		if ( !$undelete ) {
			$this->associatedTalk = null;
			return $this;
		}

		// @todo FIXME: NamespaceInfo should accept PageIdentity
		$thisWikiPage = $this->wikiPageFactory->newFromTitle( $this->page );
		$this->associatedTalk = $this->wikiPageFactory->newFromLinkTarget(
			$this->namespaceInfo->getTalkPage( $thisWikiPage->getTitle() )
		);
		return $this;
	}

	/**
	 * Same as undeleteUnsafe, but checks permissions.
	 *
	 * @param string $comment
	 * @return StatusValue
	 */
	public function undeleteIfAllowed( string $comment ): StatusValue {
		$status = $this->authorizeUndeletion();
		if ( !$status->isGood() ) {
			return $status;
		}

		return $this->undeleteUnsafe( $comment );
	}

	private function authorizeUndeletion(): PermissionStatus {
		$status = PermissionStatus::newEmpty();
		$this->performer->authorizeWrite( 'undelete', $this->page, $status );
		if ( $this->associatedTalk ) {
			$this->performer->authorizeWrite( 'undelete', $this->associatedTalk, $status );
		}
		if ( $this->tags ) {
			$status->merge( ChangeTags::canAddTagsAccompanyingChange( $this->tags, $this->performer ) );
		}
		return $status;
	}

	/**
	 * Restore the given (or all) text and file revisions for the page.
	 * Once restored, the items will be removed from the archive tables.
	 * The deletion log will be updated with an undeletion notice.
	 *
	 * This also sets Status objects, $this->fileStatus and $this->revisionStatus
	 * (depending what operations are attempted).
	 *
	 * @note This method doesn't check user permissions. Use undeleteIfAllowed for that.
	 *
	 * @param string $comment
	 * @return StatusValue Good Status with the following value on success:
	 *   [
	 *     self::REVISIONS_RESTORED => number of text revisions restored,
	 *     self::FILES_RESTORED => number of file revisions restored
	 *   ]
	 *   Fatal Status on failure.
	 */
	public function undeleteUnsafe( string $comment ): StatusValue {
		$hookStatus = $this->runPreUndeleteHook( $comment );
		if ( !$hookStatus->isGood() ) {
			return $hookStatus;
		}
		// If both the set of text revisions and file revisions are empty,
		// restore everything. Otherwise, just restore the requested items.
		$restoreAll = $this->timestamps === [] && $this->fileVersions === [];

		$restoreText = $restoreAll || $this->timestamps !== [];
		$restoreFiles = $restoreAll || $this->fileVersions !== [];

		$resStatus = StatusValue::newGood();
		$filesRestored = 0;
		if ( $restoreFiles && $this->page->getNamespace() === NS_FILE ) {
			/** @var LocalFile $img */
			$img = $this->repoGroup->getLocalRepo()->newFile( $this->page );
			$img->load( IDBAccessObject::READ_LATEST );
			$this->fileStatus = $img->restore( $this->fileVersions, $this->unsuppress );
			if ( !$this->fileStatus->isOK() ) {
				return $this->fileStatus;
			}
			$filesRestored = $this->fileStatus->successCount;
			$resStatus->merge( $this->fileStatus );
		}

		$textRestored = 0;
		$pageCreated = false;
		$restoredRevision = null;
		$restoredPageIds = [];
		if ( $restoreText ) {
			// If we already restored files, then don't bail if there isn't any text to restore
			$acceptNoRevisions = $filesRestored > 0;
			$this->revisionStatus = $this->undeleteRevisions(
				$this->page, $this->timestamps,
				$comment, $acceptNoRevisions
			);
			if ( !$this->revisionStatus->isOK() ) {
				return $this->revisionStatus;
			}

			[ $textRestored, $pageCreated, $restoredRevision, $restoredPageIds ] = $this->revisionStatus->getValue();
			$resStatus->merge( $this->revisionStatus );
		}

		$talkRestored = 0;
		$talkCreated = false;
		$restoredTalkRevision = null;
		$restoredTalkPageIds = [];
		if ( $this->associatedTalk ) {
			$talkStatus = $this->canProbablyUndeleteAssociatedTalk();
			// if undeletion of the page fails we don't want to undelete the talk page
			if ( $talkStatus->isGood() && $resStatus->isGood() ) {
				$talkStatus = $this->undeleteRevisions( $this->associatedTalk, [], $comment, false );
				if ( !$talkStatus->isOK() ) {
					return $talkStatus;
				}
				[ $talkRestored, $talkCreated, $restoredTalkRevision, $restoredTalkPageIds ] = $talkStatus->getValue();

			} else {
				// Add errors as warnings since the talk page is secondary to the main action
				foreach ( $talkStatus->getMessages() as $msg ) {
					$resStatus->warning( $msg );
				}
			}
		}

		$resStatus->value = [
			self::REVISIONS_RESTORED => $textRestored + $talkRestored,
			self::FILES_RESTORED => $filesRestored
		];

		if ( !$textRestored && !$filesRestored && !$talkRestored ) {
			$this->logger->debug( "Undelete: nothing undeleted..." );
			return $resStatus;
		}

		if ( $textRestored || $filesRestored ) {
			$logEntry = $this->addLogEntry( $this->page, $comment, $textRestored, $filesRestored );

			if ( $textRestored ) {
				$this->hookRunner->onPageUndeleteComplete(
					$this->page,
					$this->performer,
					$comment,
					$restoredRevision,
					$logEntry,
					$textRestored,
					$pageCreated,
					$restoredPageIds
				);
			}
		}

		if ( $talkRestored ) {
			$talkRestoredComment = $this->contLangMsgTextFormatter->format(
				MessageValue::new( 'undelete-talk-summary-prefix' )->plaintextParams( $comment )
			);
			$logEntry = $this->addLogEntry( $this->associatedTalk, $talkRestoredComment, $talkRestored, 0 );

			$this->hookRunner->onPageUndeleteComplete(
				$this->associatedTalk,
				$this->performer,
				$talkRestoredComment,
				$restoredTalkRevision,
				$logEntry,
				$talkRestored,
				$talkCreated,
				$restoredTalkPageIds
			);
		}

		return $resStatus;
	}

	private function runPreUndeleteHook( string $comment ): StatusValue {
		$checkPages = [ $this->page ];
		if ( $this->associatedTalk ) {
			$checkPages[] = $this->associatedTalk;
		}
		foreach ( $checkPages as $page ) {
			$hookStatus = StatusValue::newGood();
			$hookRes = $this->hookRunner->onPageUndelete(
				$page,
				$this->performer,
				$comment,
				$this->unsuppress,
				$this->timestamps,
				$this->fileVersions,
				$hookStatus
			);
			if ( !$hookRes && !$hookStatus->isGood() ) {
				// Note: as per the PageUndeleteHook documentation, `return false` is ignored if $status is good.
				return $hookStatus;
			}
		}
		return Status::newGood();
	}

	/**
	 * @param ProperPageIdentity $page
	 * @param string $comment
	 * @param int $textRestored
	 * @param int $filesRestored
	 *
	 * @return ManualLogEntry
	 */
	private function addLogEntry(
		ProperPageIdentity $page,
		string $comment,
		int $textRestored,
		int $filesRestored
	): ManualLogEntry {
		$logEntry = new ManualLogEntry( 'delete', 'restore' );
		$logEntry->setPerformer( $this->performer->getUser() );
		$logEntry->setTarget( $page );
		$logEntry->setComment( $comment );
		$logEntry->addTags( $this->tags );
		$logEntry->setParameters( [
			':assoc:count' => [
				'revisions' => $textRestored,
				'files' => $filesRestored,
			],
		] );

		$logid = $logEntry->insert();
		$logEntry->publish( $logid );

		return $logEntry;
	}

	/**
	 * This is the meaty bit -- It restores archived revisions of the given page
	 * to the revision table.
	 *
	 * @param ProperPageIdentity $page
	 * @param string[] $timestamps
	 * @param string $comment
	 * @param bool $acceptNoRevisions Whether to return a good status rather than an error
	 * 	if no revisions are undeleted.
	 * @throws ReadOnlyError
	 * @return StatusValue Status object containing the number of revisions restored on success
	 */
	private function undeleteRevisions(
		ProperPageIdentity $page, array $timestamps,
		string $comment, bool $acceptNoRevisions
	): StatusValue {
		if ( $this->readOnlyMode->isReadOnly() ) {
			throw new ReadOnlyError();
		}

		$dbw = $this->dbProvider->getPrimaryDatabase();
		$dbw->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );

		$oldWhere = [
			'ar_namespace' => $page->getNamespace(),
			'ar_title' => $page->getDBkey(),
		];
		if ( $timestamps ) {
			$oldWhere['ar_timestamp'] = array_map( [ $dbw, 'timestamp' ], $timestamps );
		}

		$revisionStore = $this->revisionStore;
		$result = $revisionStore->newArchiveSelectQueryBuilder( $dbw )
			->joinComment()
			->leftJoin( 'revision', null, 'ar_rev_id=rev_id' )
			->field( 'rev_id' )
			->where( $oldWhere )
			->orderBy( 'ar_timestamp' )
			->caller( __METHOD__ )->fetchResultSet();

		$rev_count = $result->numRows();
		if ( !$rev_count ) {
			$this->logger->debug( __METHOD__ . ": no revisions to restore" );

			// Status value is count of revisions, whether the page has been created,
			// last revision undeleted and all undeleted pages
			$status = Status::newGood( [ 0, false, null, [] ] );
			if ( !$acceptNoRevisions ) {
				$status->error( "undelete-no-results" );
			}
			$dbw->endAtomic( __METHOD__ );

			return $status;
		}

		$result->seek( $rev_count - 1 );
		$latestRestorableRow = $result->current();

		// move back
		$result->seek( 0 );

		$wikiPage = $this->wikiPageFactory->newFromTitle( $page );

		$created = true;
		$oldcountable = false;
		$updatedCurrentRevision = false;
		$restoredRevCount = 0;
		$restoredPages = [];

		// pass this to ArticleUndelete hook
		$oldPageId = (int)$latestRestorableRow->ar_page_id;

		// Grab the content to check consistency with global state before restoring the page.
		// XXX: The only current use case is Wikibase, which tries to enforce uniqueness of
		// certain things across all pages. There may be a better way to do that.
		$revision = $revisionStore->newRevisionFromArchiveRow(
			$latestRestorableRow,
			0,
			$page
		);

		foreach ( $revision->getSlotRoles() as $role ) {
			$content = $revision->getContent( $role, RevisionRecord::RAW );
			// NOTE: article ID may not be known yet. validateSave() should not modify the database.
			$contentHandler = $this->contentHandlerFactory->getContentHandler( $content->getModel() );
			$validationParams = new ValidationParams( $wikiPage, 0 );
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable RAW never returns null
			$status = $contentHandler->validateSave( $content, $validationParams );
			if ( !$status->isOK() ) {
				$dbw->endAtomic( __METHOD__ );

				return $status;
			}
		}

		// Grab page state before changing it
		$updater = $this->pageUpdaterFactory->newDerivedPageDataUpdater( $wikiPage );
		$updater->grabCurrentRevision();

		$pageId = $wikiPage->insertOn( $dbw, $latestRestorableRow->ar_page_id );
		if ( $pageId === false ) {
			// The page ID is reserved; let's pick another
			$pageId = $wikiPage->insertOn( $dbw );
			if ( $pageId === false ) {
				// The page title must be already taken (race condition)
				$created = false;
			}
		}

		# Does this page already exist? We'll have to update it...
		if ( !$created ) {
			# Load latest data for the current page (T33179)
			$wikiPage->loadPageData( IDBAccessObject::READ_EXCLUSIVE );
			$pageId = $wikiPage->getId();
			$oldcountable = $wikiPage->isCountable();

			$previousTimestamp = false;
			$latestRevId = $wikiPage->getLatest();
			if ( $latestRevId ) {
				$previousTimestamp = $revisionStore->getTimestampFromId(
					$latestRevId,
					IDBAccessObject::READ_LATEST
				);
			}
			if ( $previousTimestamp === false ) {
				$this->logger->debug( __METHOD__ . ": existing page refers to a page_latest that does not exist" );

				// Status value is count of revisions, whether the page has been created,
				// last revision undeleted and all undeleted pages
				$status = Status::newGood( [ 0, false, null, [] ] );
				$status->error( 'undeleterevision-missing' );
				$dbw->cancelAtomic( __METHOD__ );

				return $status;
			}
		} else {
			$previousTimestamp = 0;
		}

		// Re-create the PageIdentity using $pageId
		$page = PageIdentityValue::localIdentity(
			$pageId,
			$page->getNamespace(),
			$page->getDBkey()
		);

		Assert::postcondition( $page->exists(), 'The page should exist now' );

		// Check if a deleted revision will become the current revision...
		$latestRestorableRowTimestamp = wfTimestamp( TS_MW, $latestRestorableRow->ar_timestamp );
		if ( $latestRestorableRowTimestamp > $previousTimestamp ) {
			// Check the state of the newest to-be version...
			if ( !$this->unsuppress
				&& ( $latestRestorableRow->ar_deleted & RevisionRecord::DELETED_TEXT )
			) {
				$dbw->cancelAtomic( __METHOD__ );

				return Status::newFatal( "undeleterevdel" );
			}
			$updatedCurrentRevision = true;
		}

		foreach ( $result as $row ) {
			// Insert one revision at a time...maintaining deletion status
			// unless we are specifically removing all restrictions...
			$revision = $revisionStore->newRevisionFromArchiveRow(
				$row,
				0,
				$page,
				[
					'page_id' => $pageId,
					'deleted' => $this->unsuppress ? 0 : $row->ar_deleted
				]
			);

			// This will also copy the revision to ip_changes if it was an IP edit.
			$revision = $revisionStore->insertRevisionOn( $revision, $dbw );

			$restoredRevCount++;

			$this->hookRunner->onRevisionUndeleted( $revision, $row->ar_page_id );

			$restoredPages[$row->ar_page_id] = true;
		}

		// Now that it's safely stored, take it out of the archive
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'archive' )
			->where( $oldWhere )
			->caller( __METHOD__ )->execute();

		// Status value is count of revisions, whether the page has been created,
		// last revision undeleted and all undeleted pages
		$status = Status::newGood( [ $restoredRevCount, $created, $revision, $restoredPages ] );

		// Was anything restored at all?
		if ( $restoredRevCount ) {

			if ( $updatedCurrentRevision ) {
				// Attach the latest revision to the page...
				// XXX: updateRevisionOn should probably move into a PageStore service.
				$wasnew = $wikiPage->updateRevisionOn(
					$dbw,
					$revision,
					$created ? 0 : $wikiPage->getLatest()
				);
			} else {
				$wasnew = false;
			}

			if ( $created || $wasnew ) {
				// Update site stats, link tables, etc
				$options = [
					PageRevisionUpdatedEvent::FLAG_SILENT => true,
					PageRevisionUpdatedEvent::FLAG_IMPLICIT => true,
					'created' => $created,
					'oldcountable' => $oldcountable,
				];

				$updater->setCause( PageUpdater::CAUSE_UNDELETE );
				$updater->setPerformer( $this->performer->getUser() );
				$updater->prepareUpdate( $revision, $options );
				$updater->doUpdates();
			}

			$this->hookRunner->onArticleUndelete(
				$wikiPage->getTitle(), $created, $comment, $oldPageId, $restoredPages );

			if ( $page->getNamespace() === NS_FILE ) {
				$job = HTMLCacheUpdateJob::newForBacklinks(
					$page,
					'imagelinks',
					[ 'causeAction' => 'undelete-file' ]
				);
				$this->jobQueueGroup->lazyPush( $job );
			}
		}

		$dbw->endAtomic( __METHOD__ );

		return $status;
	}

	/**
	 * @internal BC method to be used by PageArchive only
	 * @return Status|null
	 */
	public function getFileStatus(): ?Status {
		return $this->fileStatus;
	}

	/**
	 * @internal BC methods to be used by PageArchive only
	 * @return StatusValue|null
	 */
	public function getRevisionStatus(): ?StatusValue {
		return $this->revisionStatus;
	}
}
