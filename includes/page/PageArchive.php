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
use Wikimedia\Rdbms\ResultWrapper;
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

	public function doesWrites() {
		return true;
	}

	/**
	 * List all deleted pages recorded in the archive table. Returns result
	 * wrapper with (ar_namespace, ar_title, count) fields, ordered by page
	 * namespace/title.
	 *
	 * @return ResultWrapper
	 */
	public static function listAllPages() {
		$dbr = wfGetDB( DB_REPLICA );

		return self::listPages( $dbr, '' );
	}

	/**
	 * List deleted pages recorded in the archive matching the
	 * given term, using search engine archive.
	 * Returns result wrapper with (ar_namespace, ar_title, count) fields.
	 *
	 * @param string $term Search term
	 * @return ResultWrapper
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
	 * @return ResultWrapper
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
	 * @return bool|ResultWrapper
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
	 * (ar_minor_edit, ar_timestamp, ar_user, ar_user_text, ar_comment) fields.
	 *
	 * @return ResultWrapper
	 */
	public function listRevisions() {
		$dbr = wfGetDB( DB_REPLICA );

		$tables = [ 'archive' ];

		$fields = [
			'ar_minor_edit', 'ar_timestamp', 'ar_user', 'ar_user_text',
			'ar_comment', 'ar_len', 'ar_deleted', 'ar_rev_id', 'ar_sha1',
			'ar_page_id'
		];

		if ( $this->config->get( 'ContentHandlerUseDB' ) ) {
			$fields[] = 'ar_content_format';
			$fields[] = 'ar_content_model';
		}

		$conds = [ 'ar_namespace' => $this->title->getNamespace(),
			'ar_title' => $this->title->getDBkey() ];

		$options = [ 'ORDER BY' => 'ar_timestamp DESC' ];

		$join_conds = [];

		ChangeTags::modifyDisplayQuery(
			$tables,
			$fields,
			$conds,
			$join_conds,
			$options,
			''
		);

		return $dbr->select( $tables,
			$fields,
			$conds,
			__METHOD__,
			$options,
			$join_conds
		);
	}

	/**
	 * List the deleted file revisions for this page, if it's a file page.
	 * Returns a result wrapper with various filearchive fields, or null
	 * if not a file page.
	 *
	 * @return ResultWrapper
	 * @todo Does this belong in Image for fuller encapsulation?
	 */
	public function listFiles() {
		if ( $this->title->getNamespace() != NS_FILE ) {
			return null;
		}

		$dbr = wfGetDB( DB_REPLICA );
		return $dbr->select(
			'filearchive',
			ArchivedFile::selectFields(),
			[ 'fa_name' => $this->title->getDBkey() ],
			__METHOD__,
			[ 'ORDER BY' => 'fa_timestamp DESC' ]
		);
	}

	/**
	 * Return a Revision object containing data for the deleted revision.
	 * Note that the result *may* or *may not* have a null page ID.
	 *
	 * @param string $timestamp
	 * @return Revision|null
	 */
	public function getRevision( $timestamp ) {
		$dbr = wfGetDB( DB_REPLICA );

		$fields = [
			'ar_rev_id',
			'ar_text',
			'ar_comment',
			'ar_user',
			'ar_user_text',
			'ar_timestamp',
			'ar_minor_edit',
			'ar_flags',
			'ar_text_id',
			'ar_deleted',
			'ar_len',
			'ar_sha1',
		];

		if ( $this->config->get( 'ContentHandlerUseDB' ) ) {
			$fields[] = 'ar_content_format';
			$fields[] = 'ar_content_model';
		}

		$row = $dbr->selectRow( 'archive',
			$fields,
			[ 'ar_namespace' => $this->title->getNamespace(),
				'ar_title' => $this->title->getDBkey(),
				'ar_timestamp' => $dbr->timestamp( $timestamp ) ],
			__METHOD__ );

		if ( $row ) {
			return Revision::newFromArchiveRow( $row, [ 'title' => $this->title ] );
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
			'ar_timestamp',
			[ 'ar_namespace' => $this->title->getNamespace(),
				'ar_title' => $this->title->getDBkey(),
				'ar_timestamp < ' .
				$dbr->addQuotes( $dbr->timestamp( $timestamp ) ) ],
			__METHOD__,
			[
				'ORDER BY' => 'ar_timestamp DESC',
				'LIMIT' => 1 ] );
		$prevDeleted = $row ? wfTimestamp( TS_MW, $row->ar_timestamp ) : false;

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
			return Revision::newFromId( $prevLiveId );
		} elseif ( $prevDeleted ) {
			// Most prior revision was deleted
			return $this->getRevision( $prevDeleted );
		}

		// No prior revision on this page.
		return null;
	}

	/**
	 * Get the text from an archive row containing ar_text, ar_flags and ar_text_id
	 *
	 * @param object $row Database row
	 * @return string
	 */
	public function getTextFromRow( $row ) {
		if ( is_null( $row->ar_text_id ) ) {
			// An old row from MediaWiki 1.4 or previous.
			// Text is embedded in this row in classic compression format.
			return Revision::getRevisionText( $row, 'ar_' );
		}

		// New-style: keyed to the text storage backend.
		$dbr = wfGetDB( DB_REPLICA );
		$text = $dbr->selectRow( 'text',
			[ 'old_text', 'old_flags' ],
			[ 'old_id' => $row->ar_text_id ],
			__METHOD__ );

		return Revision::getRevisionText( $text );
	}

	/**
	 * Fetch (and decompress if necessary) the stored text of the most
	 * recently edited deleted revision of the page.
	 *
	 * If there are no archived revisions for the page, returns NULL.
	 *
	 * @return string|null
	 */
	public function getLastRevisionText() {
		$dbr = wfGetDB( DB_REPLICA );
		$row = $dbr->selectRow( 'archive',
			[ 'ar_text', 'ar_flags', 'ar_text_id' ],
			[ 'ar_namespace' => $this->title->getNamespace(),
				'ar_title' => $this->title->getDBkey() ],
			__METHOD__,
			[ 'ORDER BY' => 'ar_timestamp DESC' ] );

		if ( $row ) {
			return $this->getTextFromRow( $row );
		}

		return null;
	}

	/**
	 * Quick check if any archived revisions are present for the page.
	 *
	 * @return bool
	 */
	public function isDeleted() {
		$dbr = wfGetDB( DB_REPLICA );
		$n = $dbr->selectField( 'archive', 'COUNT(ar_title)',
			[ 'ar_namespace' => $this->title->getNamespace(),
				'ar_title' => $this->title->getDBkey() ],
			__METHOD__
		);

		return ( $n > 0 );
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
	 * @param User $user User performing the action, or null to use $wgUser
	 * @param string|string[] $tags Change tags to add to log entry
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

		$fields = [
			'ar_id',
			'ar_rev_id',
			'rev_id',
			'ar_text',
			'ar_comment',
			'ar_user',
			'ar_user_text',
			'ar_timestamp',
			'ar_minor_edit',
			'ar_flags',
			'ar_text_id',
			'ar_deleted',
			'ar_page_id',
			'ar_len',
			'ar_sha1'
		];

		if ( $this->config->get( 'ContentHandlerUseDB' ) ) {
			$fields[] = 'ar_content_format';
			$fields[] = 'ar_content_model';
		}

		/**
		 * Select each archived revision...
		 */
		$result = $dbw->select(
			[ 'archive', 'revision' ],
			$fields,
			$oldWhere,
			__METHOD__,
			/* options */
			[ 'ORDER BY' => 'ar_timestamp' ],
			[ 'revision' => [ 'LEFT JOIN', 'ar_rev_id=rev_id' ] ]
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

			// grab the content to check consistency with global state before restoring the page.
			$revision = Revision::newFromArchiveRow( $latestRestorableRow,
				[
					'title' => $article->getTitle(), // used to derive default content model
				]
			);
			$user = User::newFromName( $revision->getUserText( Revision::RAW ), false );
			$content = $revision->getContent( Revision::RAW );

			// NOTE: article ID may not be known yet. prepareSave() should not modify the database.
			$status = $content->prepareSave( $article, 0, -1, $user );
			if ( !$status->isOK() ) {
				$dbw->endAtomic( __METHOD__ );

				return $status;
			}
		}

		$newid = false; // newly created page ID
		$restored = 0; // number of revisions restored
		/** @var Revision $revision */
		$revision = null;
		$restoredPages = [];
		// If there are no restorable revisions, we can skip most of the steps.
		if ( $latestRestorableRow === null ) {
			$failedRevisionCount = $rev_count;
		} else {
			if ( $makepage ) {
				// Check the state of the newest to-be version...
				if ( !$unsuppress
					&& ( $latestRestorableRow->ar_deleted & Revision::DELETED_TEXT )
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
						&& ( $latestRestorableRow->ar_deleted & Revision::DELETED_TEXT )
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
				$revision = Revision::newFromArchiveRow( $row,
					[
						'page' => $pageId,
						'title' => $this->title,
						'deleted' => $unsuppress ? 0 : $row->ar_deleted
					] );

				$revision->insertOn( $dbw );
				$restored++;

				Hooks::run( 'ArticleRevisionUndeleted',
					[ &$this->title, $revision, $row->ar_page_id ] );
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
			$wasnew = $article->updateIfNewerOn( $dbw, $revision );
			if ( $created || $wasnew ) {
				// Update site stats, link tables, etc
				$article->doEditUpdates(
					$revision,
					User::newFromName( $revision->getUserText( Revision::RAW ), false ),
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
				DeferredUpdates::addUpdate( new HTMLCacheUpdate( $this->title, 'imagelinks' ) );
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
