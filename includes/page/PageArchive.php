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

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Storage\SqlBlobStore;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\IDatabase;

/**
 * Used to show archived pages and eventually restore them.
 */
class PageArchive {
	/** @var Title */
	protected $title;

	/** @var Status */
	protected $fileStatus;

	/** @var Status */
	protected $revisionStatus;

	/** @var Config */
	protected $config;

	public function __construct( $title, Config $config = null ) {
		if ( is_null( $title ) ) {
			throw new MWException( __METHOD__ . ' given a null title.' );
		}
		$this->title = $title;
		if ( $config === null ) {
			wfDebug( __METHOD__ . ' did not have a Config object passed to it' );
			$config = MediaWikiServices::getInstance()->getMainConfig();
		}
		$this->config = $config;
	}

	/**
	 * @return RevisionStore
	 */
	private function getRevisionStore() {
		// TODO: Refactor: delete()/undelete() should live in a PageStore service;
		//       Methods in PageArchive and RevisionStore that deal with archive revisions
		//       should move into an ArchiveStore service (but could still be implemented
		//       together with RevisionStore).
		return MediaWikiServices::getInstance()->getRevisionStore();
	}

	public function doesWrites() {
		return true;
	}

	/**
	 * List all deleted pages recorded in the archive table. Returns result
	 * wrapper with (ar_namespace, ar_title, count) fields, ordered by page
	 * namespace/title.
	 *
	 * @deprecated since 1.32.
	 *
	 * @return IResultWrapper
	 */
	public static function listAllPages() {
		wfDeprecated( __METHOD__, '1.32' );

		$dbr = wfGetDB( DB_REPLICA );

		return self::listPages( $dbr, '' );
	}

	/**
	 * List deleted pages recorded in the archive matching the
	 * given term, using search engine archive.
	 * Returns result wrapper with (ar_namespace, ar_title, count) fields.
	 *
	 * @param string $term Search term
	 * @return IResultWrapper
	 */
	public static function listPagesBySearch( $term ) {
		$title = Title::newFromText( $term );
		if ( $title ) {
			$ns = $title->getNamespace();
			$termMain = $title->getText();
			$termDb = $title->getDBkey();
		} else {
			// Prolly won't work too good
			// @todo handle bare namespace names cleanly?
			$ns = 0;
			$termMain = $termDb = $term;
		}

		// Try search engine first
		$engine = MediaWikiServices::getInstance()->newSearchEngine();
		$engine->setLimitOffset( 100 );
		$engine->setNamespaces( [ $ns ] );
		$results = $engine->searchArchiveTitle( $termMain );
		if ( !$results->isOK() ) {
			$results = [];
		} else {
			$results = $results->getValue();
		}

		if ( !$results ) {
			// Fall back to regular prefix search
			return self::listPagesByPrefix( $term );
		}

		$dbr = wfGetDB( DB_REPLICA );
		$condTitles = array_unique( array_map( function ( Title $t ) {
			return $t->getDBkey();
		}, $results ) );
		$conds = [
			'ar_namespace' => $ns,
			$dbr->makeList( [ 'ar_title' => $condTitles ], LIST_OR ) . " OR ar_title " .
			$dbr->buildLike( $termDb, $dbr->anyString() )
		];

		return self::listPages( $dbr, $conds );
	}

	/**
	 * List deleted pages recorded in the archive table matching the
	 * given title prefix.
	 * Returns result wrapper with (ar_namespace, ar_title, count) fields.
	 *
	 * @param string $prefix Title prefix
	 * @return IResultWrapper
	 */
	public static function listPagesByPrefix( $prefix ) {
		$dbr = wfGetDB( DB_REPLICA );

		$title = Title::newFromText( $prefix );
		if ( $title ) {
			$ns = $title->getNamespace();
			$prefix = $title->getDBkey();
		} else {
			// Prolly won't work too good
			// @todo handle bare namespace names cleanly?
			$ns = 0;
		}

		$conds = [
			'ar_namespace' => $ns,
			'ar_title' . $dbr->buildLike( $prefix, $dbr->anyString() ),
		];

		return self::listPages( $dbr, $conds );
	}

	/**
	 * @param IDatabase $dbr
	 * @param string|array $condition
	 * @return bool|IResultWrapper
	 */
	protected static function listPages( $dbr, $condition ) {
		return $dbr->select(
			[ 'archive' ],
			[
				'ar_namespace',
				'ar_title',
				'count' => 'COUNT(*)'
			],
			$condition,
			__METHOD__,
			[
				'GROUP BY' => [ 'ar_namespace', 'ar_title' ],
				'ORDER BY' => [ 'ar_namespace', 'ar_title' ],
				'LIMIT' => 100,
			]
		);
	}

	/**
	 * List the revisions of the given page. Returns result wrapper with
	 * various archive table fields.
	 *
	 * @return IResultWrapper
	 */
	public function listRevisions() {
		$revisionStore = $this->getRevisionStore();
		$queryInfo = $revisionStore->getArchiveQueryInfo();

		$conds = [
			'ar_namespace' => $this->title->getNamespace(),
			'ar_title' => $this->title->getDBkey(),
		];

		// NOTE: ordering by ar_timestamp and ar_id, to remove ambiguity.
		// XXX: Ideally, we would be ordering by ar_timestamp and ar_rev_id, but since we
		// don't have an index on ar_rev_id, that causes a file sort.
		$options = [ 'ORDER BY' => 'ar_timestamp DESC, ar_id DESC' ];

		ChangeTags::modifyDisplayQuery(
			$queryInfo['tables'],
			$queryInfo['fields'],
			$conds,
			$queryInfo['joins'],
			$options,
			''
		);

		$dbr = wfGetDB( DB_REPLICA );
		return $dbr->select(
			$queryInfo['tables'],
			$queryInfo['fields'],
			$conds,
			__METHOD__,
			$options,
			$queryInfo['joins']
		);
	}

	/**
	 * List the deleted file revisions for this page, if it's a file page.
	 * Returns a result wrapper with various filearchive fields, or null
	 * if not a file page.
	 *
	 * @return IResultWrapper
	 * @todo Does this belong in Image for fuller encapsulation?
	 */
	public function listFiles() {
		if ( $this->title->getNamespace() != NS_FILE ) {
			return null;
		}

		$dbr = wfGetDB( DB_REPLICA );
		$fileQuery = ArchivedFile::getQueryInfo();
		return $dbr->select(
			$fileQuery['tables'],
			$fileQuery['fields'],
			[ 'fa_name' => $this->title->getDBkey() ],
			__METHOD__,
			[ 'ORDER BY' => 'fa_timestamp DESC' ],
			$fileQuery['joins']
		);
	}

	/**
	 * Return a Revision object containing data for the deleted revision.
	 *
	 * @deprecated since 1.32, use getArchivedRevision() instead.
	 *
	 * @param string $timestamp
	 * @return Revision|null
	 */
	public function getRevision( $timestamp ) {
		$dbr = wfGetDB( DB_REPLICA );
		$rec = $this->getRevisionByConditions(
			[ 'ar_timestamp' => $dbr->timestamp( $timestamp ) ]
		);
		return $rec ? new Revision( $rec ) : null;
	}

	/**
	 * Return the archived revision with the given ID.
	 *
	 * @param int $revId
	 * @return Revision|null
	 */
	public function getArchivedRevision( $revId ) {
		// Protect against code switching from getRevision() passing in a timestamp.
		Assert::parameterType( 'integer', $revId, '$revId' );

		$rec = $this->getRevisionByConditions( [ 'ar_rev_id' => $revId ] );
		return $rec ? new Revision( $rec ) : null;
	}

	/**
	 * @param array $conditions
	 * @param array $options
	 *
	 * @return RevisionRecord|null
	 */
	private function getRevisionByConditions( array $conditions, array $options = [] ) {
		$dbr = wfGetDB( DB_REPLICA );
		$arQuery = $this->getRevisionStore()->getArchiveQueryInfo();

		$conditions = $conditions + [
			'ar_namespace' => $this->title->getNamespace(),
			'ar_title' => $this->title->getDBkey(),
		];

		$row = $dbr->selectRow(
			$arQuery['tables'],
			$arQuery['fields'],
			$conditions,
			__METHOD__,
			$options,
			$arQuery['joins']
		);

		if ( $row ) {
			return $this->getRevisionStore()->newRevisionFromArchiveRow( $row, 0, $this->title );
		}

		return null;
	}

	/**
	 * Return the most-previous revision, either live or deleted, against
	 * the deleted revision given by timestamp.
	 *
	 * May produce unexpected results in case of history merges or other
	 * unusual time issues.
	 *
	 * @param string $timestamp
	 * @return Revision|null Null when there is no previous revision
	 */
	public function getPreviousRevision( $timestamp ) {
		$dbr = wfGetDB( DB_REPLICA );

		// Check the previous deleted revision...
		$row = $dbr->selectRow( 'archive',
			[ 'ar_rev_id', 'ar_timestamp' ],
			[ 'ar_namespace' => $this->title->getNamespace(),
				'ar_title' => $this->title->getDBkey(),
				'ar_timestamp < ' .
				$dbr->addQuotes( $dbr->timestamp( $timestamp ) ) ],
			__METHOD__,
			[
				'ORDER BY' => 'ar_timestamp DESC',
				'LIMIT' => 1 ] );
		$prevDeleted = $row ? wfTimestamp( TS_MW, $row->ar_timestamp ) : false;
		$prevDeletedId = $row ? intval( $row->ar_rev_id ) : null;

		$row = $dbr->selectRow( [ 'page', 'revision' ],
			[ 'rev_id', 'rev_timestamp' ],
			[
				'page_namespace' => $this->title->getNamespace(),
				'page_title' => $this->title->getDBkey(),
				'page_id = rev_page',
				'rev_timestamp < ' .
				$dbr->addQuotes( $dbr->timestamp( $timestamp ) ) ],
			__METHOD__,
			[
				'ORDER BY' => 'rev_timestamp DESC',
				'LIMIT' => 1 ] );
		$prevLive = $row ? wfTimestamp( TS_MW, $row->rev_timestamp ) : false;
		$prevLiveId = $row ? intval( $row->rev_id ) : null;

		if ( $prevLive && $prevLive > $prevDeleted ) {
			// Most prior revision was live
			$rec = $this->getRevisionStore()->getRevisionById( $prevLiveId );
			$rec = $rec ? new Revision( $rec ) : null;
		} elseif ( $prevDeleted ) {
			// Most prior revision was deleted
			$rec = $this->getArchivedRevision( $prevDeletedId );
		} else {
			$rec = null;
		}

		return $rec;
	}

	/**
	 * Get the text from an archive row containing ar_text_id.
	 *
	 * @deprecated since 1.32. In the MCR schema, ar_text_id no longer exists.
	 * Calling code should switch to getArchiveRevision().
	 *
	 * @todo remove in 1.33
	 *
	 * @param object $row Database row
	 * @return string
	 */
	public function getTextFromRow( $row ) {
		wfDeprecated( __METHOD__, '1.32' );

		if ( empty( $row->ar_text_id ) ) {
			throw new InvalidArgumentException( '$row->ar_text_id must be set and not empty!' );
		}

		$address = SqlBlobStore::makeAddressFromTextId( $row->ar_text_id );
		$blobStore = MediaWikiServices::getInstance()->getBlobStore();

		return $blobStore->getBlob( $address );
	}

	/**
	 * Fetch (and decompress if necessary) the stored text of the most
	 * recently edited deleted revision of the page.
	 *
	 * If there are no archived revisions for the page, returns NULL.
	 *
	 * @note this bypasses any audience checks.
	 *
	 * @deprecated since 1.32. For compatibility with the MCR schema,
	 * calling code should switch to getLastRevisionId() and getArchiveRevision().
	 *
	 * @todo remove in 1.33
	 *
	 * @return string|null
	 */
	public function getLastRevisionText() {
		wfDeprecated( __METHOD__, '1.32' );

		$revId = $this->getLastRevisionId();

		if ( $revId ) {
			$rev = $this->getArchivedRevision( $revId );
			$content = $rev->getContent( RevisionRecord::RAW );
			return $content->serialize();
		}

		return null;
	}

	/**
	 * Returns the ID of the latest deleted revision.
	 *
	 * @return int|false The revision's ID, or false if there is no deleted revision.
	 */
	public function getLastRevisionId() {
		$dbr = wfGetDB( DB_REPLICA );
		$revId = $dbr->selectField(
			'archive',
			'ar_rev_id',
			[ 'ar_namespace' => $this->title->getNamespace(),
				'ar_title' => $this->title->getDBkey() ],
			__METHOD__,
			[ 'ORDER BY' => 'ar_timestamp DESC, ar_id DESC' ]
		);

		return $revId ? intval( $revId ) : false;
	}

	/**
	 * Quick check if any archived revisions are present for the page.
	 * This says nothing about whether the page currently exists in the page table or not.
	 *
	 * @return bool
	 */
	public function isDeleted() {
		$dbr = wfGetDB( DB_REPLICA );
		$row = $dbr->selectRow(
			[ 'archive' ],
			'1', // We don't care about the value. Allow the database to optimize.
			[ 'ar_namespace' => $this->title->getNamespace(),
				'ar_title' => $this->title->getDBkey() ],
			__METHOD__
		);

		return (bool)$row;
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
	 * @param string $comment
	 * @param array $fileVersions
	 * @param bool $unsuppress
	 * @param User|null $user User performing the action, or null to use $wgUser
	 * @param string|string[]|null $tags Change tags to add to log entry
	 *   ($user should be able to add the specified tags before this is called)
	 * @return array|bool array(number of file revisions restored, number of image revisions
	 *   restored, log message) on success, false on failure.
	 */
	public function undelete( $timestamps, $comment = '', $fileVersions = [],
		$unsuppress = false, User $user = null, $tags = null
	) {
		// If both the set of text revisions and file revisions are empty,
		// restore everything. Otherwise, just restore the requested items.
		$restoreAll = empty( $timestamps ) && empty( $fileVersions );

		$restoreText = $restoreAll || !empty( $timestamps );
		$restoreFiles = $restoreAll || !empty( $fileVersions );

		if ( $restoreFiles && $this->title->getNamespace() == NS_FILE ) {
			$img = wfLocalFile( $this->title );
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
			wfDebug( "Undelete: nothing undeleted...\n" );

			return false;
		}

		if ( $user === null ) {
			global $wgUser;
			$user = $wgUser;
		}

		$logEntry = new ManualLogEntry( 'delete', 'restore' );
		$logEntry->setPerformer( $user );
		$logEntry->setTarget( $this->title );
		$logEntry->setComment( $comment );
		$logEntry->setTags( $tags );
		$logEntry->setParameters( [
			':assoc:count' => [
				'revisions' => $textRestored,
				'files' => $filesRestored,
			],
		] );

		Hooks::run( 'ArticleUndeleteLogEntry', [ $this, &$logEntry, $user ] );

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
		if ( wfReadOnly() ) {
			throw new ReadOnlyError();
		}

		$dbw = wfGetDB( DB_MASTER );
		$dbw->startAtomic( __METHOD__ );

		$restoreAll = empty( $timestamps );

		# Does this page already exist? We'll have to update it...
		$article = WikiPage::factory( $this->title );
		# Load latest data for the current page (T33179)
		$article->loadPageData( 'fromdbmaster' );
		$oldcountable = $article->isCountable();

		$page = $dbw->selectRow( 'page',
			[ 'page_id', 'page_latest' ],
			[ 'page_namespace' => $this->title->getNamespace(),
				'page_title' => $this->title->getDBkey() ],
			__METHOD__,
			[ 'FOR UPDATE' ] // lock page
		);

		if ( $page ) {
			$makepage = false;
			# Page already exists. Import the history, and if necessary
			# we'll update the latest revision field in the record.

			# Get the time span of this page
			$previousTimestamp = $dbw->selectField( 'revision', 'rev_timestamp',
				[ 'rev_id' => $page->page_latest ],
				__METHOD__ );

			if ( $previousTimestamp === false ) {
				wfDebug( __METHOD__ . ": existing page refers to a page_latest that does not exist\n" );

				$status = Status::newGood( 0 );
				$status->warning( 'undeleterevision-missing' );
				$dbw->endAtomic( __METHOD__ );

				return $status;
			}
		} else {
			# Have to create a new article...
			$makepage = true;
			$previousTimestamp = 0;
		}

		$oldWhere = [
			'ar_namespace' => $this->title->getNamespace(),
			'ar_title' => $this->title->getDBkey(),
		];
		if ( !$restoreAll ) {
			$oldWhere['ar_timestamp'] = array_map( [ &$dbw, 'timestamp' ], $timestamps );
		}

		$revisionStore = $this->getRevisionStore();
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
			wfDebug( __METHOD__ . ": no revisions to restore\n" );

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

		$oldPageId = 0;
		if ( $latestRestorableRow !== null ) {
			$oldPageId = (int)$latestRestorableRow->ar_page_id; // pass this to ArticleUndelete hook

			// Grab the content to check consistency with global state before restoring the page.
			// XXX: The only current use case is Wikibase, which tries to enforce uniqueness of
			// certain things across all pages. There may be a better way to do that.
			$revision = $revisionStore->newRevisionFromArchiveRow(
				$latestRestorableRow,
				0,
				$this->title
			);

			// TODO: use User::newFromUserIdentity from If610c68f4912e
			// TODO: The User isn't used for anything in prepareSave()! We should drop it.
			$user = User::newFromName( $revision->getUser( RevisionRecord::RAW )->getName(), false );

			foreach ( $revision->getSlotRoles() as $role ) {
				$content = $revision->getContent( $role, RevisionRecord::RAW );

				// NOTE: article ID may not be known yet. prepareSave() should not modify the database.
				$status = $content->prepareSave( $article, 0, -1, $user );
				if ( !$status->isOK() ) {
					$dbw->endAtomic( __METHOD__ );

					return $status;
				}
			}
		}

		$newid = false; // newly created page ID
		$restored = 0; // number of revisions restored
		/** @var RevisionRecord|null $revision */
		$revision = null;
		$restoredPages = [];
		// If there are no restorable revisions, we can skip most of the steps.
		if ( $latestRestorableRow === null ) {
			$failedRevisionCount = $rev_count;
		} else {
			if ( $makepage ) {
				// Check the state of the newest to-be version...
				if ( !$unsuppress
					&& ( $latestRestorableRow->ar_deleted & RevisionRecord::DELETED_TEXT )
				) {
					$dbw->endAtomic( __METHOD__ );

					return Status::newFatal( "undeleterevdel" );
				}
				// Safe to insert now...
				$newid = $article->insertOn( $dbw, $latestRestorableRow->ar_page_id );
				if ( $newid === false ) {
					// The old ID is reserved; let's pick another
					$newid = $article->insertOn( $dbw );
				}
				$pageId = $newid;
			} else {
				// Check if a deleted revision will become the current revision...
				if ( $latestRestorableRow->ar_timestamp > $previousTimestamp ) {
					// Check the state of the newest to-be version...
					if ( !$unsuppress
						&& ( $latestRestorableRow->ar_deleted & RevisionRecord::DELETED_TEXT )
					) {
						$dbw->endAtomic( __METHOD__ );

						return Status::newFatal( "undeleterevdel" );
					}
				}

				$newid = false;
				$pageId = $article->getId();
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

				$legacyRevision = new Revision( $revision );
				Hooks::run( 'ArticleRevisionUndeleted',
					[ &$this->title, $legacyRevision, $row->ar_page_id ] );
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
			$created = (bool)$newid;
			// Attach the latest revision to the page...
			// XXX: updateRevisionOn should probably move into a PageStore service.
			$wasnew = $article->updateIfNewerOn( $dbw, $legacyRevision );
			if ( $created || $wasnew ) {
				// Update site stats, link tables, etc
				// TODO: use DerivedPageDataUpdater from If610c68f4912e!
				$article->doEditUpdates(
					$legacyRevision,
					User::newFromName( $revision->getUser( RevisionRecord::RAW )->getName(), false ),
					[
						'created' => $created,
						'oldcountable' => $oldcountable,
						'restored' => true
					]
				);
			}

			Hooks::run( 'ArticleUndelete',
				[ &$this->title, $created, $comment, $oldPageId, $restoredPages ] );
			if ( $this->title->getNamespace() == NS_FILE ) {
				DeferredUpdates::addUpdate(
					new HTMLCacheUpdate( $this->title, 'imagelinks', 'file-restore' )
				);
			}
		}

		$dbw->endAtomic( __METHOD__ );

		return $status;
	}

	/**
	 * @return Status
	 */
	public function getFileStatus() {
		return $this->fileStatus;
	}

	/**
	 * @return Status
	 */
	public function getRevisionStatus() {
		return $this->revisionStatus;
	}
}
