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

use ChangeTags;
use File;
use HTMLCacheUpdateJob;
use JobQueueGroup;
use LocalFile;
use ManualLogEntry;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\ValidationParams;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Storage\PageUpdaterFactory;
use Psr\Log\LoggerInterface;
use ReadOnlyError;
use ReadOnlyMode;
use RepoGroup;
use Status;
use StatusValue;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use WikiPage;

/**
 * @since 1.38
 * @package MediaWiki\Page
 */
class UndeletePage {

	// Constants used as keys in the StatusValue returned by undelete()
	public const FILES_RESTORED = 'files';
	public const REVISIONS_RESTORED = 'revs';

	/** @var HookRunner */
	private $hookRunner;
	/** @var JobQueueGroup */
	private $jobQueueGroup;
	/** @var ILoadBalancer */
	private $loadBalancer;
	/** @var LoggerInterface */
	private $logger;
	/** @var ReadOnlyMode */
	private $readOnlyMode;
	/** @var RepoGroup */
	private $repoGroup;
	/** @var RevisionStore */
	private $revisionStore;
	/** @var WikiPageFactory */
	private $wikiPageFactory;
	/** @var PageUpdaterFactory */
	private $pageUpdaterFactory;
	/** @var IContentHandlerFactory */
	private $contentHandlerFactory;

	/** @var ProperPageIdentity */
	private $page;
	/** @var Authority */
	private $performer;
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

	/**
	 * @param HookContainer $hookContainer
	 * @param JobQueueGroup $jobQueueGroup
	 * @param ILoadBalancer $loadBalancer
	 * @param ReadOnlyMode $readOnlyMode
	 * @param RepoGroup $repoGroup
	 * @param LoggerInterface $logger
	 * @param RevisionStore $revisionStore
	 * @param WikiPageFactory $wikiPageFactory
	 * @param PageUpdaterFactory $pageUpdaterFactory
	 * @param IContentHandlerFactory $contentHandlerFactory
	 * @param ProperPageIdentity $page
	 * @param Authority $performer
	 */
	public function __construct(
		HookContainer $hookContainer,
		JobQueueGroup $jobQueueGroup,
		ILoadBalancer $loadBalancer,
		ReadOnlyMode $readOnlyMode,
		RepoGroup $repoGroup,
		LoggerInterface $logger,
		RevisionStore $revisionStore,
		WikiPageFactory $wikiPageFactory,
		PageUpdaterFactory $pageUpdaterFactory,
		IContentHandlerFactory $contentHandlerFactory,
		ProperPageIdentity $page,
		Authority $performer
	) {
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->jobQueueGroup = $jobQueueGroup;
		$this->loadBalancer = $loadBalancer;
		$this->readOnlyMode = $readOnlyMode;
		$this->repoGroup = $repoGroup;
		$this->logger = $logger;
		$this->revisionStore = $revisionStore;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->pageUpdaterFactory = $pageUpdaterFactory;
		$this->contentHandlerFactory = $contentHandlerFactory;

		$this->page = $page;
		$this->performer = $performer;
	}

	/**
	 * Whether to remove all ar_deleted/fa_deleted restrictions of seletected revs.
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

	/**
	 * @return PermissionStatus
	 */
	private function authorizeUndeletion(): PermissionStatus {
		$status = PermissionStatus::newEmpty();
		$this->performer->authorizeWrite( 'undelete', $this->page, $status );
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
		$hookStatus = StatusValue::newGood();
		$hookRes = $this->hookRunner->onPageUndelete(
			$this->page,
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
		// If both the set of text revisions and file revisions are empty,
		// restore everything. Otherwise, just restore the requested items.
		$restoreAll = $this->timestamps === [] && $this->fileVersions === [];

		$restoreText = $restoreAll || $this->timestamps !== [];
		$restoreFiles = $restoreAll || $this->fileVersions !== [];

		$resStatus = StatusValue::newGood();
		if ( $restoreFiles && $this->page->getNamespace() === NS_FILE ) {
			/** @var LocalFile $img */
			$img = $this->repoGroup->getLocalRepo()->newFile( $this->page );
			$img->load( File::READ_LATEST );
			$this->fileStatus = $img->restore( $this->fileVersions, $this->unsuppress );
			if ( !$this->fileStatus->isOK() ) {
				return $this->fileStatus;
			}
			$filesRestored = $this->fileStatus->successCount;
			$resStatus->merge( $this->fileStatus );
		} else {
			$filesRestored = 0;
		}

		if ( $restoreText ) {
			$this->revisionStatus = $this->undeleteRevisions( $comment );
			if ( !$this->revisionStatus->isOK() ) {
				return $this->revisionStatus;
			}

			$textRestored = $this->revisionStatus->getValue();
			$resStatus->merge( $this->revisionStatus );
		} else {
			$textRestored = 0;
		}

		$resStatus->value = [
			self::REVISIONS_RESTORED => $textRestored,
			self::FILES_RESTORED => $filesRestored
		];

		if ( !$textRestored && !$filesRestored ) {
			$this->logger->debug( "Undelete: nothing undeleted..." );
			return $resStatus;
		}

		$logEntry = new ManualLogEntry( 'delete', 'restore' );
		$logEntry->setPerformer( $this->performer->getUser() );
		$logEntry->setTarget( $this->page );
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

		return $resStatus;
	}

	/**
	 * This is the meaty bit -- It restores archived revisions of the given page
	 * to the revision table.
	 *
	 * @param string $comment
	 * @throws ReadOnlyError
	 * @return StatusValue Status object containing the number of revisions restored on success
	 */
	private function undeleteRevisions( string $comment ): StatusValue {
		if ( $this->readOnlyMode->isReadOnly() ) {
			throw new ReadOnlyError();
		}

		$dbw = $this->loadBalancer->getConnectionRef( DB_PRIMARY );
		$dbw->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );

		$oldWhere = [
			'ar_namespace' => $this->page->getNamespace(),
			'ar_title' => $this->page->getDBkey(),
		];
		if ( $this->timestamps ) {
			$oldWhere['ar_timestamp'] = array_map( [ $dbw, 'timestamp' ], $this->timestamps );
		}

		$revisionStore = $this->revisionStore;
		$queryInfo = $revisionStore->getArchiveQueryInfo();
		$queryInfo['tables'][] = 'revision';
		$queryInfo['fields'][] = 'rev_id';
		$queryInfo['joins']['revision'] = [ 'LEFT JOIN', 'ar_rev_id=rev_id' ];

		$result = $dbw->select(
			$queryInfo['tables'],
			$queryInfo['fields'],
			$oldWhere,
			__METHOD__,
			[ 'ORDER BY' => 'ar_timestamp' ],
			$queryInfo['joins']
		);

		$rev_count = $result->numRows();
		if ( !$rev_count ) {
			$this->logger->debug( __METHOD__ . ": no revisions to restore" );

			$status = Status::newGood( 0 );
			$status->warning( "undelete-no-results" );
			$dbw->endAtomic( __METHOD__ );

			return $status;
		}

		// We use ar_id because there can be duplicate ar_rev_id even for the same
		// page.  In this case, we may be able to restore the first one.
		$restoreFailedArIds = [];

		// Map rev_id to the ar_id that is allowed to use it.  When checking later,
		// if it doesn't match, the current ar_id can not be restored.

		// Value can be an ar_id or -1 (-1 means no ar_id can use it, since the
		// rev_id is taken before we even start the restore).
		$allowedRevIdToArIdMap = [];

		$latestRestorableRow = null;

		foreach ( $result as $row ) {
			if ( $row->ar_rev_id ) {
				// rev_id is taken even before we start restoring.
				if ( $row->ar_rev_id === $row->rev_id ) {
					$restoreFailedArIds[] = $row->ar_id;
					$allowedRevIdToArIdMap[$row->ar_rev_id] = -1;
				} else {
					// rev_id is not taken yet in the DB, but it might be taken
					// by a prior revision in the same restore operation. If
					// not, we need to reserve it.
					if ( isset( $allowedRevIdToArIdMap[$row->ar_rev_id] ) ) {
						$restoreFailedArIds[] = $row->ar_id;
					} else {
						$allowedRevIdToArIdMap[$row->ar_rev_id] = $row->ar_id;
						$latestRestorableRow = $row;
					}
				}
			} else {
				// If ar_rev_id is null, there can't be a collision, and a
				// rev_id will be chosen automatically.
				$latestRestorableRow = $row;
			}
		}

		// move back
		$result->seek( 0 );

		$wikiPage = $this->wikiPageFactory->newFromTitle( $this->page );

		$oldPageId = 0;
		/** @var RevisionRecord|null $revision */
		$revision = null;
		$created = true;
		$oldcountable = false;
		$updatedCurrentRevision = false;
		$restoredRevCount = 0;
		$restoredPages = [];

		// If there are no restorable revisions, we can skip most of the steps.
		if ( $latestRestorableRow === null ) {
			$failedRevisionCount = $rev_count;
		} else {
			// pass this to ArticleUndelete hook
			$oldPageId = (int)$latestRestorableRow->ar_page_id;

			// Grab the content to check consistency with global state before restoring the page.
			// XXX: The only current use case is Wikibase, which tries to enforce uniqueness of
			// certain things across all pages. There may be a better way to do that.
			$revision = $revisionStore->newRevisionFromArchiveRow(
				$latestRestorableRow,
				0,
				$this->page
			);

			foreach ( $revision->getSlotRoles() as $role ) {
				$content = $revision->getContent( $role, RevisionRecord::RAW );
				// NOTE: article ID may not be known yet. validateSave() should not modify the database.
				$contentHandler = $this->contentHandlerFactory->getContentHandler( $content->getModel() );
				$validationParams = new ValidationParams( $wikiPage, 0 );
				$status = $contentHandler->validateSave( $content, $validationParams );
				if ( !$status->isOK() ) {
					$dbw->endAtomic( __METHOD__ );

					return $status;
				}
			}

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
				$wikiPage->loadPageData( WikiPage::READ_EXCLUSIVE );
				$pageId = $wikiPage->getId();
				$oldcountable = $wikiPage->isCountable();

				$previousTimestamp = false;
				$latestRevId = $wikiPage->getLatest();
				if ( $latestRevId ) {
					$previousTimestamp = $revisionStore->getTimestampFromId(
						$latestRevId,
						RevisionStore::READ_LATEST
					);
				}
				if ( $previousTimestamp === false ) {
					$this->logger->debug( __METHOD__ . ": existing page refers to a page_latest that does not exist" );

					$status = Status::newGood( 0 );
					$status->warning( 'undeleterevision-missing' );
					$dbw->cancelAtomic( __METHOD__ );

					return $status;
				}
			} else {
				$previousTimestamp = 0;
			}

			// Check if a deleted revision will become the current revision...
			if ( $latestRestorableRow->ar_timestamp > $previousTimestamp ) {
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
				// Check for key dupes due to needed archive integrity.
				if ( $row->ar_rev_id && $allowedRevIdToArIdMap[$row->ar_rev_id] !== $row->ar_id ) {
					continue;
				}
				// Insert one revision at a time...maintaining deletion status
				// unless we are specifically removing all restrictions...
				$revision = $revisionStore->newRevisionFromArchiveRow(
					$row,
					0,
					$this->page,
					[
						'page_id' => $pageId,
						'deleted' => $this->unsuppress ? 0 : $row->ar_deleted
					]
				);

				// This will also copy the revision to ip_changes if it was an IP edit.
				$revisionStore->insertRevisionOn( $revision, $dbw );

				$restoredRevCount++;

				$this->hookRunner->onRevisionUndeleted( $revision, $row->ar_page_id );

				$restoredPages[$row->ar_page_id] = true;
			}

			// Now that it's safely stored, take it out of the archive
			// Don't delete rows that we failed to restore
			$toDeleteConds = $oldWhere;
			$failedRevisionCount = count( $restoreFailedArIds );
			if ( $failedRevisionCount > 0 ) {
				$toDeleteConds[] = 'ar_id NOT IN ( ' . $dbw->makeList( $restoreFailedArIds ) . ' )';
			}

			$dbw->delete( 'archive',
				$toDeleteConds,
				__METHOD__ );
		}

		$status = Status::newGood( $restoredRevCount );

		if ( $failedRevisionCount > 0 ) {
			$status->warning( 'undeleterevision-duplicate-revid', $failedRevisionCount );
		}

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
				$user = $revision->getUser( RevisionRecord::RAW );
				$options = [
					'created' => $created,
					'oldcountable' => $oldcountable,
					'restored' => true,
					'causeAction' => 'edit-page',
					'causeAgent' => $user->getName(),
				];

				$updater = $this->pageUpdaterFactory->newDerivedPageDataUpdater( $wikiPage );
				$updater->prepareUpdate( $revision, $options );
				$updater->doUpdates();
			}

			$this->hookRunner->onArticleUndelete(
				$wikiPage->getTitle(), $created, $comment, $oldPageId, $restoredPages );

			if ( $this->page->getNamespace() === NS_FILE ) {
				$job = HTMLCacheUpdateJob::newForBacklinks(
					$this->page,
					'imagelinks',
					[ 'causeAction' => 'file-restore' ]
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
