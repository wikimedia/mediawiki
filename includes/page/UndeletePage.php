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

use File;
use HTMLCacheUpdateJob;
use JobQueueGroup;
use LocalFile;
use ManualLogEntry;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use Psr\Log\LoggerInterface;
use ReadOnlyError;
use ReadOnlyMode;
use RepoGroup;
use Status;
use Title;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;
use WikiPage;

/**
 * @since 1.38
 * @package MediaWiki\Page
 * @unstable
 */
class UndeletePage {
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
	/** @var UserFactory */
	private $userFactory;
	/** @var WikiPageFactory */
	private $wikiPageFactory;

	/** @var Title */
	private $title;
	/** @var Status|null */
	private $fileStatus;
	/** @var Status|null */
	private $revisionStatus;

	/**
	 * @param Title $title
	 */
	public function __construct( Title $title ) {
		$services = MediaWikiServices::getInstance();

		$this->hookRunner = new HookRunner( $services->getHookContainer() );
		$this->jobQueueGroup = $services->getJobQueueGroup();
		$this->loadBalancer = $services->getDBLoadBalancer();
		$this->readOnlyMode = $services->getReadOnlyMode();
		$this->repoGroup = $services->getRepoGroup();
		$this->logger = LoggerFactory::getInstance( 'PageArchive' );
		$this->revisionStore = $services->getRevisionStore();
		$this->userFactory = $services->getUserFactory();
		$this->wikiPageFactory = $services->getWikiPageFactory();

		$this->title = $title;
	}

	/**
	 * Restore the given (or all) text and file revisions for the page.
	 * Once restored, the items will be removed from the archive tables.
	 * The deletion log will be updated with an undeletion notice.
	 *
	 * This also sets Status objects, $this->fileStatus and $this->revisionStatus
	 * (depending what operations are attempted).
	 *
	 * @param array $timestamps Pass an empty array to restore all revisions,
	 *   otherwise list the ones to undelete.
	 * @param UserIdentity $user
	 * @param string $comment
	 * @param array $fileVersions
	 * @param bool $unsuppress
	 * @param string|string[]|null $tags Change tags to add to log entry
	 *   ($user should be able to add the specified tags before this is called)
	 * @return array|bool [ number of file revisions restored, number of image revisions
	 *   restored, log message ] on success, false on failure.
	 */
	public function undeleteAsUser(
		$timestamps,
		UserIdentity $user,
		$comment = '',
		$fileVersions = [],
		$unsuppress = false,
		$tags = null
	) {
		// If both the set of text revisions and file revisions are empty,
		// restore everything. Otherwise, just restore the requested items.
		$restoreAll = empty( $timestamps ) && empty( $fileVersions );

		$restoreText = $restoreAll || !empty( $timestamps );
		$restoreFiles = $restoreAll || !empty( $fileVersions );

		if ( $restoreFiles && $this->title->getNamespace() === NS_FILE ) {
			/** @var LocalFile $img */
			$img = $this->repoGroup->getLocalRepo()->newFile( $this->title );
			$img->load( File::READ_LATEST );
			$this->fileStatus = $img->restore( $fileVersions, $unsuppress );
			if ( !$this->fileStatus->isOK() ) {
				return false;
			}
			$filesRestored = $this->fileStatus->successCount;
		} else {
			$filesRestored = 0;
		}

		if ( $restoreText ) {
			$this->revisionStatus = $this->undeleteRevisions( $timestamps, $unsuppress, $comment );
			if ( !$this->revisionStatus->isOK() ) {
				return false;
			}

			$textRestored = $this->revisionStatus->getValue();
		} else {
			$textRestored = 0;
		}

		// Touch the log!

		if ( !$textRestored && !$filesRestored ) {
			$this->logger->debug( "Undelete: nothing undeleted..." );

			return false;
		}

		$logEntry = new ManualLogEntry( 'delete', 'restore' );
		$logEntry->setPerformer( $user );
		$logEntry->setTarget( $this->title );
		$logEntry->setComment( $comment );
		$logEntry->addTags( $tags );
		$logEntry->setParameters( [
			':assoc:count' => [
				'revisions' => $textRestored,
				'files' => $filesRestored,
			],
		] );

		$logid = $logEntry->insert();
		$logEntry->publish( $logid );

		return [ $textRestored, $filesRestored, $comment ];
	}

	/**
	 * This is the meaty bit -- It restores archived revisions of the given page
	 * to the revision table.
	 *
	 * @param array $timestamps Pass an empty array to restore all revisions,
	 *   otherwise list the ones to undelete.
	 * @param bool $unsuppress Remove all ar_deleted/fa_deleted restrictions of seletected revs
	 * @param string $comment
	 * @throws ReadOnlyError
	 * @return Status Status object containing the number of revisions restored on success
	 */
	private function undeleteRevisions( $timestamps, $unsuppress = false, $comment = '' ) {
		if ( $this->readOnlyMode->isReadOnly() ) {
			throw new ReadOnlyError();
		}

		$dbw = $this->loadBalancer->getConnectionRef( DB_PRIMARY );
		$dbw->startAtomic( __METHOD__, IDatabase::ATOMIC_CANCELABLE );

		$restoreAll = empty( $timestamps );

		$oldWhere = [
			'ar_namespace' => $this->title->getNamespace(),
			'ar_title' => $this->title->getDBkey(),
		];
		if ( !$restoreAll ) {
			$oldWhere['ar_timestamp'] = array_map( [ &$dbw, 'timestamp' ], $timestamps );
		}

		$revisionStore = $this->revisionStore;
		$queryInfo = $revisionStore->getArchiveQueryInfo();
		$queryInfo['tables'][] = 'revision';
		$queryInfo['fields'][] = 'rev_id';
		$queryInfo['joins']['revision'] = [ 'LEFT JOIN', 'ar_rev_id=rev_id' ];

		/**
		 * Select each archived revision...
		 */
		$result = $dbw->select(
			$queryInfo['tables'],
			$queryInfo['fields'],
			$oldWhere,
			__METHOD__,
			/* options */
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

		$result->seek( 0 ); // move back

		$article = $this->wikiPageFactory->newFromTitle( $this->title );

		$oldPageId = 0;
		/** @var RevisionRecord|null $revision */
		$revision = null;
		$created = true;
		$oldcountable = false;
		$updatedCurrentRevision = false;
		$restored = 0; // number of revisions restored
		$restoredPages = [];

		// If there are no restorable revisions, we can skip most of the steps.
		if ( $latestRestorableRow === null ) {
			$failedRevisionCount = $rev_count;
		} else {
			$oldPageId = (int)$latestRestorableRow->ar_page_id; // pass this to ArticleUndelete hook

			// Grab the content to check consistency with global state before restoring the page.
			// XXX: The only current use case is Wikibase, which tries to enforce uniqueness of
			// certain things across all pages. There may be a better way to do that.
			$revision = $revisionStore->newRevisionFromArchiveRow(
				$latestRestorableRow,
				0,
				$this->title
			);

			// TODO: use UserFactory::newFromUserIdentity from If610c68f4912e
			// TODO: The User isn't used for anything in prepareSave()! We should drop it.
			$user = $this->userFactory->newFromName(
				$revision->getUser( RevisionRecord::RAW )->getName(),
				UserFactory::RIGOR_NONE
			);

			foreach ( $revision->getSlotRoles() as $role ) {
				$content = $revision->getContent( $role, RevisionRecord::RAW );

				// NOTE: article ID may not be known yet. prepareSave() should not modify the database.
				$status = $content->prepareSave( $article, 0, -1, $user );
				if ( !$status->isOK() ) {
					$dbw->endAtomic( __METHOD__ );

					return $status;
				}
			}

			$pageId = $article->insertOn( $dbw, $latestRestorableRow->ar_page_id );
			if ( $pageId === false ) {
				// The page ID is reserved; let's pick another
				$pageId = $article->insertOn( $dbw );
				if ( $pageId === false ) {
					// The page title must be already taken (race condition)
					$created = false;
				}
			}

			# Does this page already exist? We'll have to update it...
			if ( !$created ) {
				# Load latest data for the current page (T33179)
				$article->loadPageData( WikiPage::READ_EXCLUSIVE );
				$pageId = $article->getId();
				$oldcountable = $article->isCountable();

				$previousTimestamp = false;
				$latestRevId = $article->getLatest();
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
				if ( !$unsuppress
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
					$this->title,
					[
						'page_id' => $pageId,
						'deleted' => $unsuppress ? 0 : $row->ar_deleted
					]
				);

				// This will also copy the revision to ip_changes if it was an IP edit.
				$revisionStore->insertRevisionOn( $revision, $dbw );

				$restored++;

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

		$status = Status::newGood( $restored );

		if ( $failedRevisionCount > 0 ) {
			$status->warning(
				wfMessage( 'undeleterevision-duplicate-revid', $failedRevisionCount ) );
		}

		// Was anything restored at all?
		if ( $restored ) {

			if ( $updatedCurrentRevision ) {
				// Attach the latest revision to the page...
				// XXX: updateRevisionOn should probably move into a PageStore service.
				$wasnew = $article->updateRevisionOn(
					$dbw,
					$revision,
					$created ? 0 : $article->getLatest()
				);
			} else {
				$wasnew = false;
			}

			if ( $created || $wasnew ) {
				// Update site stats, link tables, etc
				// TODO: use DerivedPageDataUpdater from If610c68f4912e!
				// TODO use UserFactory::newFromUserIdentity
				$article->doEditUpdates(
					$revision,
					$revision->getUser( RevisionRecord::RAW ),
					[
						'created' => $created,
						'oldcountable' => $oldcountable,
						'restored' => true
					]
				);
			}

			$this->hookRunner->onArticleUndelete(
				$this->title, $created, $comment, $oldPageId, $restoredPages );

			if ( $this->title->getNamespace() === NS_FILE ) {
				$job = HTMLCacheUpdateJob::newForBacklinks(
					$this->title,
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
	 * @return Status|null
	 */
	public function getFileStatus() {
		return $this->fileStatus;
	}

	/**
	 * @return Status|null
	 */
	public function getRevisionStatus() {
		return $this->revisionStatus;
	}
}
