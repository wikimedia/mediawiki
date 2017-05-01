<?php
/**
 * Implements Special:Undelete
 *
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
 * @ingroup SpecialPage
 */

/**
 * Used to show archived pages and eventually restore them.
 *
 * @ingroup SpecialPage
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

	function __construct( $title, Config $config = null ) {
		if ( is_null( $title ) ) {
			throw new MWException( __METHOD__ . ' given a null title.' );
		}
		$this->title = $title;
		if ( $config === null ) {
			wfDebug( __METHOD__ . ' did not have a Config object passed to it' );
			$config = ConfigFactory::getDefaultInstance()->makeConfig( 'main' );
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
	function listRevisions() {
		$dbr = wfGetDB( DB_REPLICA );

		$tables = [ 'archive' ];

		$fields = [
			'ar_minor_edit', 'ar_timestamp', 'ar_user', 'ar_user_text',
			'ar_comment', 'ar_len', 'ar_deleted', 'ar_rev_id', 'ar_sha1',
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
	function listFiles() {
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
	function getRevision( $timestamp ) {
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
	function getPreviousRevision( $timestamp ) {
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
	function getTextFromRow( $row ) {
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
	function getLastRevisionText() {
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
	function isDeleted() {
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
	 * @return array(number of file revisions restored, number of image revisions
	 *   restored, log message) on success, false on failure.
	 */
	function undelete( $timestamps, $comment = '', $fileVersions = [],
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

		if ( $textRestored && $filesRestored ) {
			$reason = wfMessage( 'undeletedrevisions-files' )
				->numParams( $textRestored, $filesRestored )->inContentLanguage()->text();
		} elseif ( $textRestored ) {
			$reason = wfMessage( 'undeletedrevisions' )->numParams( $textRestored )
				->inContentLanguage()->text();
		} elseif ( $filesRestored ) {
			$reason = wfMessage( 'undeletedfiles' )->numParams( $filesRestored )
				->inContentLanguage()->text();
		} else {
			wfDebug( "Undelete: nothing undeleted...\n" );

			return false;
		}

		if ( trim( $comment ) != '' ) {
			$reason .= wfMessage( 'colon-separator' )->inContentLanguage()->text() . $comment;
		}

		if ( $user === null ) {
			global $wgUser;
			$user = $wgUser;
		}

		$logEntry = new ManualLogEntry( 'delete', 'restore' );
		$logEntry->setPerformer( $user );
		$logEntry->setTarget( $this->title );
		$logEntry->setComment( $reason );
		$logEntry->setTags( $tags );

		Hooks::run( 'ArticleUndeleteLogEntry', [ $this, &$logEntry, $user ] );

		$logid = $logEntry->insert();
		$logEntry->publish( $logid );

		return [ $textRestored, $filesRestored, $reason ];
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
		# Load latest data for the current page (bug 31179)
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

			Hooks::run( 'ArticleUndelete', [ &$this->title, $created, $comment, $oldPageId ] );
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
	function getFileStatus() {
		return $this->fileStatus;
	}

	/**
	 * @return Status
	 */
	function getRevisionStatus() {
		return $this->revisionStatus;
	}
}

/**
 * Special page allowing users with the appropriate permissions to view
 * and restore deleted content.
 *
 * @ingroup SpecialPage
 */
class SpecialUndelete extends SpecialPage {
	private	$mAction;
	private	$mTarget;
	private	$mTimestamp;
	private	$mRestore;
	private	$mRevdel;
	private	$mInvert;
	private	$mFilename;
	private	$mTargetTimestamp;
	private	$mAllowed;
	private	$mCanView;
	private	$mComment;
	private	$mToken;

	/** @var Title */
	private $mTargetObj;

	function __construct() {
		parent::__construct( 'Undelete', 'deletedhistory' );
	}

	public function doesWrites() {
		return true;
	}

	function loadRequest( $par ) {
		$request = $this->getRequest();
		$user = $this->getUser();

		$this->mAction = $request->getVal( 'action' );
		if ( $par !== null && $par !== '' ) {
			$this->mTarget = $par;
		} else {
			$this->mTarget = $request->getVal( 'target' );
		}

		$this->mTargetObj = null;

		if ( $this->mTarget !== null && $this->mTarget !== '' ) {
			$this->mTargetObj = Title::newFromText( $this->mTarget );
		}

		$this->mSearchPrefix = $request->getText( 'prefix' );
		$time = $request->getVal( 'timestamp' );
		$this->mTimestamp = $time ? wfTimestamp( TS_MW, $time ) : '';
		$this->mFilename = $request->getVal( 'file' );

		$posted = $request->wasPosted() &&
			$user->matchEditToken( $request->getVal( 'wpEditToken' ) );
		$this->mRestore = $request->getCheck( 'restore' ) && $posted;
		$this->mRevdel = $request->getCheck( 'revdel' ) && $posted;
		$this->mInvert = $request->getCheck( 'invert' ) && $posted;
		$this->mPreview = $request->getCheck( 'preview' ) && $posted;
		$this->mDiff = $request->getCheck( 'diff' );
		$this->mDiffOnly = $request->getBool( 'diffonly', $this->getUser()->getOption( 'diffonly' ) );
		$this->mComment = $request->getText( 'wpComment' );
		$this->mUnsuppress = $request->getVal( 'wpUnsuppress' ) && $user->isAllowed( 'suppressrevision' );
		$this->mToken = $request->getVal( 'token' );

		if ( $this->isAllowed( 'undelete' ) && !$user->isBlocked() ) {
			$this->mAllowed = true; // user can restore
			$this->mCanView = true; // user can view content
		} elseif ( $this->isAllowed( 'deletedtext' ) ) {
			$this->mAllowed = false; // user cannot restore
			$this->mCanView = true; // user can view content
			$this->mRestore = false;
		} else { // user can only view the list of revisions
			$this->mAllowed = false;
			$this->mCanView = false;
			$this->mTimestamp = '';
			$this->mRestore = false;
		}

		if ( $this->mRestore || $this->mInvert ) {
			$timestamps = [];
			$this->mFileVersions = [];
			foreach ( $request->getValues() as $key => $val ) {
				$matches = [];
				if ( preg_match( '/^ts(\d{14})$/', $key, $matches ) ) {
					array_push( $timestamps, $matches[1] );
				}

				if ( preg_match( '/^fileid(\d+)$/', $key, $matches ) ) {
					$this->mFileVersions[] = intval( $matches[1] );
				}
			}
			rsort( $timestamps );
			$this->mTargetTimestamp = $timestamps;
		}
	}

	/**
	 * Checks whether a user is allowed the permission for the
	 * specific title if one is set.
	 *
	 * @param string $permission
	 * @param User $user
	 * @return bool
	 */
	protected function isAllowed( $permission, User $user = null ) {
		$user = $user ?: $this->getUser();
		if ( $this->mTargetObj !== null ) {
			return $this->mTargetObj->userCan( $permission, $user );
		} else {
			return $user->isAllowed( $permission );
		}
	}

	function userCanExecute( User $user ) {
		return $this->isAllowed( $this->mRestriction, $user );
	}

	function execute( $par ) {
		$this->useTransactionalTimeLimit();

		$user = $this->getUser();

		$this->setHeaders();
		$this->outputHeader();

		$this->loadRequest( $par );
		$this->checkPermissions(); // Needs to be after mTargetObj is set

		$out = $this->getOutput();

		if ( is_null( $this->mTargetObj ) ) {
			$out->addWikiMsg( 'undelete-header' );

			# Not all users can just browse every deleted page from the list
			if ( $user->isAllowed( 'browsearchive' ) ) {
				$this->showSearchForm();
			}

			return;
		}

		$this->addHelpLink( 'Help:Undelete' );
		if ( $this->mAllowed ) {
			$out->setPageTitle( $this->msg( 'undeletepage' ) );
		} else {
			$out->setPageTitle( $this->msg( 'viewdeletedpage' ) );
		}

		$this->getSkin()->setRelevantTitle( $this->mTargetObj );

		if ( $this->mTimestamp !== '' ) {
			$this->showRevision( $this->mTimestamp );
		} elseif ( $this->mFilename !== null && $this->mTargetObj->inNamespace( NS_FILE ) ) {
			$file = new ArchivedFile( $this->mTargetObj, '', $this->mFilename );
			// Check if user is allowed to see this file
			if ( !$file->exists() ) {
				$out->addWikiMsg( 'filedelete-nofile', $this->mFilename );
			} elseif ( !$file->userCan( File::DELETED_FILE, $user ) ) {
				if ( $file->isDeleted( File::DELETED_RESTRICTED ) ) {
					throw new PermissionsError( 'suppressrevision' );
				} else {
					throw new PermissionsError( 'deletedtext' );
				}
			} elseif ( !$user->matchEditToken( $this->mToken, $this->mFilename ) ) {
				$this->showFileConfirmationForm( $this->mFilename );
			} else {
				$this->showFile( $this->mFilename );
			}
		} elseif ( $this->mAction === "submit" ) {
			if ( $this->mRestore ) {
				$this->undelete();
			} elseif ( $this->mRevdel ) {
				$this->redirectToRevDel();
			}

		} else {
			$this->showHistory();
		}
	}

	/**
	 * Convert submitted form data to format expected by RevisionDelete and
	 * redirect the request
	 */
	private function redirectToRevDel() {
		$archive = new PageArchive( $this->mTargetObj );

		$revisions = [];

		foreach ( $this->getRequest()->getValues() as $key => $val ) {
			$matches = [];
			if ( preg_match( "/^ts(\d{14})$/", $key, $matches ) ) {
				$revisions[ $archive->getRevision( $matches[1] )->getId() ] = 1;
			}
		}
		$query = [
			"type" => "revision",
			"ids" => $revisions,
			"target" => $this->mTargetObj->getPrefixedText()
		];
		$url = SpecialPage::getTitleFor( 'Revisiondelete' )->getFullURL( $query );
		$this->getOutput()->redirect( $url );
	}

	function showSearchForm() {
		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'undelete-search-title' ) );
		$out->addHTML(
			Xml::openElement( 'form', [ 'method' => 'get', 'action' => wfScript() ] ) .
				Xml::fieldset( $this->msg( 'undelete-search-box' )->text() ) .
				Html::hidden( 'title', $this->getPageTitle()->getPrefixedDBkey() ) .
				Html::rawElement(
					'label',
					[ 'for' => 'prefix' ],
					$this->msg( 'undelete-search-prefix' )->parse()
				) .
				Xml::input(
					'prefix',
					20,
					$this->mSearchPrefix,
					[ 'id' => 'prefix', 'autofocus' => '' ]
				) . ' ' .
				Xml::submitButton( $this->msg( 'undelete-search-submit' )->text() ) .
				Xml::closeElement( 'fieldset' ) .
				Xml::closeElement( 'form' )
		);

		# List undeletable articles
		if ( $this->mSearchPrefix ) {
			$result = PageArchive::listPagesByPrefix( $this->mSearchPrefix );
			$this->showList( $result );
		}
	}

	/**
	 * Generic list of deleted pages
	 *
	 * @param ResultWrapper $result
	 * @return bool
	 */
	private function showList( $result ) {
		$out = $this->getOutput();

		if ( $result->numRows() == 0 ) {
			$out->addWikiMsg( 'undelete-no-results' );

			return false;
		}

		$out->addWikiMsg( 'undeletepagetext', $this->getLanguage()->formatNum( $result->numRows() ) );

		$undelete = $this->getPageTitle();
		$out->addHTML( "<ul>\n" );
		foreach ( $result as $row ) {
			$title = Title::makeTitleSafe( $row->ar_namespace, $row->ar_title );
			if ( $title !== null ) {
				$item = Linker::linkKnown(
					$undelete,
					htmlspecialchars( $title->getPrefixedText() ),
					[],
					[ 'target' => $title->getPrefixedText() ]
				);
			} else {
				// The title is no longer valid, show as text
				$item = Html::element(
					'span',
					[ 'class' => 'mw-invalidtitle' ],
					Linker::getInvalidTitleDescription(
						$this->getContext(),
						$row->ar_namespace,
						$row->ar_title
					)
				);
			}
			$revs = $this->msg( 'undeleterevisions' )->numParams( $row->count )->parse();
			$out->addHTML( "<li>{$item} ({$revs})</li>\n" );
		}
		$result->free();
		$out->addHTML( "</ul>\n" );

		return true;
	}

	private function showRevision( $timestamp ) {
		if ( !preg_match( '/[0-9]{14}/', $timestamp ) ) {
			return;
		}

		$archive = new PageArchive( $this->mTargetObj, $this->getConfig() );
		if ( !Hooks::run( 'UndeleteForm::showRevision', [ &$archive, $this->mTargetObj ] ) ) {
			return;
		}
		$rev = $archive->getRevision( $timestamp );

		$out = $this->getOutput();
		$user = $this->getUser();

		if ( !$rev ) {
			$out->addWikiMsg( 'undeleterevision-missing' );

			return;
		}

		if ( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
			if ( !$rev->userCan( Revision::DELETED_TEXT, $user ) ) {
				$out->wrapWikiMsg(
					"<div class='mw-warning plainlinks'>\n$1\n</div>\n",
				$rev->isDeleted( Revision::DELETED_RESTRICTED ) ?
					'rev-suppressed-text-permission' : 'rev-deleted-text-permission'
				);

				return;
			}

			$out->wrapWikiMsg(
				"<div class='mw-warning plainlinks'>\n$1\n</div>\n",
				$rev->isDeleted( Revision::DELETED_RESTRICTED ) ?
					'rev-suppressed-text-view' : 'rev-deleted-text-view'
			);
			$out->addHTML( '<br />' );
			// and we are allowed to see...
		}

		if ( $this->mDiff ) {
			$previousRev = $archive->getPreviousRevision( $timestamp );
			if ( $previousRev ) {
				$this->showDiff( $previousRev, $rev );
				if ( $this->mDiffOnly ) {
					return;
				}

				$out->addHTML( '<hr />' );
			} else {
				$out->addWikiMsg( 'undelete-nodiff' );
			}
		}

		$link = Linker::linkKnown(
			$this->getPageTitle( $this->mTargetObj->getPrefixedDBkey() ),
			htmlspecialchars( $this->mTargetObj->getPrefixedText() )
		);

		$lang = $this->getLanguage();

		// date and time are separate parameters to facilitate localisation.
		// $time is kept for backward compat reasons.
		$time = $lang->userTimeAndDate( $timestamp, $user );
		$d = $lang->userDate( $timestamp, $user );
		$t = $lang->userTime( $timestamp, $user );
		$userLink = Linker::revUserTools( $rev );

		$content = $rev->getContent( Revision::FOR_THIS_USER, $user );

		$isText = ( $content instanceof TextContent );

		if ( $this->mPreview || $isText ) {
			$openDiv = '<div id="mw-undelete-revision" class="mw-warning">';
		} else {
			$openDiv = '<div id="mw-undelete-revision">';
		}
		$out->addHTML( $openDiv );

		// Revision delete links
		if ( !$this->mDiff ) {
			$revdel = Linker::getRevDeleteLink( $user, $rev, $this->mTargetObj );
			if ( $revdel ) {
				$out->addHTML( "$revdel " );
			}
		}

		$out->addHTML( $this->msg( 'undelete-revision' )->rawParams( $link )->params(
			$time )->rawParams( $userLink )->params( $d, $t )->parse() . '</div>' );

		if ( !Hooks::run( 'UndeleteShowRevision', [ $this->mTargetObj, $rev ] ) ) {
			return;
		}

		if ( ( $this->mPreview || !$isText ) && $content ) {
			// NOTE: non-text content has no source view, so always use rendered preview

			// Hide [edit]s
			$popts = $out->parserOptions();
			$popts->setEditSection( false );

			$pout = $content->getParserOutput( $this->mTargetObj, $rev->getId(), $popts, true );
			$out->addParserOutput( $pout );
		}

		if ( $isText ) {
			// source view for textual content
			$sourceView = Xml::element(
				'textarea',
				[
					'readonly' => 'readonly',
					'cols' => $user->getIntOption( 'cols' ),
					'rows' => $user->getIntOption( 'rows' )
				],
				$content->getNativeData() . "\n"
			);

			$previewButton = Xml::element( 'input', [
				'type' => 'submit',
				'name' => 'preview',
				'value' => $this->msg( 'showpreview' )->text()
			] );
		} else {
			$sourceView = '';
			$previewButton = '';
		}

		$diffButton = Xml::element( 'input', [
			'name' => 'diff',
			'type' => 'submit',
			'value' => $this->msg( 'showdiff' )->text() ] );

		$out->addHTML(
			$sourceView .
				Xml::openElement( 'div', [
					'style' => 'clear: both' ] ) .
				Xml::openElement( 'form', [
					'method' => 'post',
					'action' => $this->getPageTitle()->getLocalURL( [ 'action' => 'submit' ] ) ] ) .
				Xml::element( 'input', [
					'type' => 'hidden',
					'name' => 'target',
					'value' => $this->mTargetObj->getPrefixedDBkey() ] ) .
				Xml::element( 'input', [
					'type' => 'hidden',
					'name' => 'timestamp',
					'value' => $timestamp ] ) .
				Xml::element( 'input', [
					'type' => 'hidden',
					'name' => 'wpEditToken',
					'value' => $user->getEditToken() ] ) .
				$previewButton .
				$diffButton .
				Xml::closeElement( 'form' ) .
				Xml::closeElement( 'div' )
		);
	}

	/**
	 * Build a diff display between this and the previous either deleted
	 * or non-deleted edit.
	 *
	 * @param Revision $previousRev
	 * @param Revision $currentRev
	 * @return string HTML
	 */
	function showDiff( $previousRev, $currentRev ) {
		$diffContext = clone $this->getContext();
		$diffContext->setTitle( $currentRev->getTitle() );
		$diffContext->setWikiPage( WikiPage::factory( $currentRev->getTitle() ) );

		$diffEngine = $currentRev->getContentHandler()->createDifferenceEngine( $diffContext );
		$diffEngine->showDiffStyle();

		$formattedDiff = $diffEngine->generateContentDiffBody(
			$previousRev->getContent( Revision::FOR_THIS_USER, $this->getUser() ),
			$currentRev->getContent( Revision::FOR_THIS_USER, $this->getUser() )
		);

		$formattedDiff = $diffEngine->addHeader(
			$formattedDiff,
			$this->diffHeader( $previousRev, 'o' ),
			$this->diffHeader( $currentRev, 'n' )
		);

		$this->getOutput()->addHTML( "<div>$formattedDiff</div>\n" );
	}

	/**
	 * @param Revision $rev
	 * @param string $prefix
	 * @return string
	 */
	private function diffHeader( $rev, $prefix ) {
		$isDeleted = !( $rev->getId() && $rev->getTitle() );
		if ( $isDeleted ) {
			/// @todo FIXME: $rev->getTitle() is null for deleted revs...?
			$targetPage = $this->getPageTitle();
			$targetQuery = [
				'target' => $this->mTargetObj->getPrefixedText(),
				'timestamp' => wfTimestamp( TS_MW, $rev->getTimestamp() )
			];
		} else {
			/// @todo FIXME: getId() may return non-zero for deleted revs...
			$targetPage = $rev->getTitle();
			$targetQuery = [ 'oldid' => $rev->getId() ];
		}

		// Add show/hide deletion links if available
		$user = $this->getUser();
		$lang = $this->getLanguage();
		$rdel = Linker::getRevDeleteLink( $user, $rev, $this->mTargetObj );

		if ( $rdel ) {
			$rdel = " $rdel";
		}

		$minor = $rev->isMinor() ? ChangesList::flag( 'minor' ) : '';

		$tags = wfGetDB( DB_REPLICA )->selectField(
			'tag_summary',
			'ts_tags',
			[ 'ts_rev_id' => $rev->getId() ],
			__METHOD__
		);
		$tagSummary = ChangeTags::formatSummaryRow( $tags, 'deleteddiff', $this->getContext() );

		// FIXME This is reimplementing DifferenceEngine#getRevisionHeader
		// and partially #showDiffPage, but worse
		return '<div id="mw-diff-' . $prefix . 'title1"><strong>' .
			Linker::link(
				$targetPage,
				$this->msg(
					'revisionasof',
					$lang->userTimeAndDate( $rev->getTimestamp(), $user ),
					$lang->userDate( $rev->getTimestamp(), $user ),
					$lang->userTime( $rev->getTimestamp(), $user )
				)->escaped(),
				[],
				$targetQuery
			) .
			'</strong></div>' .
			'<div id="mw-diff-' . $prefix . 'title2">' .
			Linker::revUserTools( $rev ) . '<br />' .
			'</div>' .
			'<div id="mw-diff-' . $prefix . 'title3">' .
			$minor . Linker::revComment( $rev ) . $rdel . '<br />' .
			'</div>' .
			'<div id="mw-diff-' . $prefix . 'title5">' .
			$tagSummary[0] . '<br />' .
			'</div>';
	}

	/**
	 * Show a form confirming whether a tokenless user really wants to see a file
	 * @param string $key
	 */
	private function showFileConfirmationForm( $key ) {
		$out = $this->getOutput();
		$lang = $this->getLanguage();
		$user = $this->getUser();
		$file = new ArchivedFile( $this->mTargetObj, '', $this->mFilename );
		$out->addWikiMsg( 'undelete-show-file-confirm',
			$this->mTargetObj->getText(),
			$lang->userDate( $file->getTimestamp(), $user ),
			$lang->userTime( $file->getTimestamp(), $user ) );
		$out->addHTML(
			Xml::openElement( 'form', [
					'method' => 'POST',
					'action' => $this->getPageTitle()->getLocalURL( [
						'target' => $this->mTarget,
						'file' => $key,
						'token' => $user->getEditToken( $key ),
					] ),
				]
			) .
				Xml::submitButton( $this->msg( 'undelete-show-file-submit' )->text() ) .
				'</form>'
		);
	}

	/**
	 * Show a deleted file version requested by the visitor.
	 * @param string $key
	 */
	private function showFile( $key ) {
		$this->getOutput()->disable();

		# We mustn't allow the output to be CDN cached, otherwise
		# if an admin previews a deleted image, and it's cached, then
		# a user without appropriate permissions can toddle off and
		# nab the image, and CDN will serve it
		$response = $this->getRequest()->response();
		$response->header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', 0 ) . ' GMT' );
		$response->header( 'Cache-Control: no-cache, no-store, max-age=0, must-revalidate' );
		$response->header( 'Pragma: no-cache' );

		$repo = RepoGroup::singleton()->getLocalRepo();
		$path = $repo->getZonePath( 'deleted' ) . '/' . $repo->getDeletedHashPath( $key ) . $key;
		$repo->streamFile( $path );
	}

	protected function showHistory() {
		$this->checkReadOnly();

		$out = $this->getOutput();
		if ( $this->mAllowed ) {
			$out->addModules( 'mediawiki.special.undelete' );
		}
		$out->wrapWikiMsg(
			"<div class='mw-undelete-pagetitle'>\n$1\n</div>\n",
			[ 'undeletepagetitle', wfEscapeWikiText( $this->mTargetObj->getPrefixedText() ) ]
		);

		$archive = new PageArchive( $this->mTargetObj, $this->getConfig() );
		Hooks::run( 'UndeleteForm::showHistory', [ &$archive, $this->mTargetObj ] );
		/*
		$text = $archive->getLastRevisionText();
		if( is_null( $text ) ) {
			$out->addWikiMsg( 'nohistory' );
			return;
		}
		*/
		$out->addHTML( '<div class="mw-undelete-history">' );
		if ( $this->mAllowed ) {
			$out->addWikiMsg( 'undeletehistory' );
			$out->addWikiMsg( 'undeleterevdel' );
		} else {
			$out->addWikiMsg( 'undeletehistorynoadmin' );
		}
		$out->addHTML( '</div>' );

		# List all stored revisions
		$revisions = $archive->listRevisions();
		$files = $archive->listFiles();

		$haveRevisions = $revisions && $revisions->numRows() > 0;
		$haveFiles = $files && $files->numRows() > 0;

		# Batch existence check on user and talk pages
		if ( $haveRevisions ) {
			$batch = new LinkBatch();
			foreach ( $revisions as $row ) {
				$batch->addObj( Title::makeTitleSafe( NS_USER, $row->ar_user_text ) );
				$batch->addObj( Title::makeTitleSafe( NS_USER_TALK, $row->ar_user_text ) );
			}
			$batch->execute();
			$revisions->seek( 0 );
		}
		if ( $haveFiles ) {
			$batch = new LinkBatch();
			foreach ( $files as $row ) {
				$batch->addObj( Title::makeTitleSafe( NS_USER, $row->fa_user_text ) );
				$batch->addObj( Title::makeTitleSafe( NS_USER_TALK, $row->fa_user_text ) );
			}
			$batch->execute();
			$files->seek( 0 );
		}

		if ( $this->mAllowed ) {
			$action = $this->getPageTitle()->getLocalURL( [ 'action' => 'submit' ] );
			# Start the form here
			$top = Xml::openElement(
				'form',
				[ 'method' => 'post', 'action' => $action, 'id' => 'undelete' ]
			);
			$out->addHTML( $top );
		}

		# Show relevant lines from the deletion log:
		$deleteLogPage = new LogPage( 'delete' );
		$out->addHTML( Xml::element( 'h2', null, $deleteLogPage->getName()->text() ) . "\n" );
		LogEventsList::showLogExtract( $out, 'delete', $this->mTargetObj );
		# Show relevant lines from the suppression log:
		$suppressLogPage = new LogPage( 'suppress' );
		if ( $this->getUser()->isAllowed( 'suppressionlog' ) ) {
			$out->addHTML( Xml::element( 'h2', null, $suppressLogPage->getName()->text() ) . "\n" );
			LogEventsList::showLogExtract( $out, 'suppress', $this->mTargetObj );
		}

		if ( $this->mAllowed && ( $haveRevisions || $haveFiles ) ) {
			# Format the user-visible controls (comment field, submission button)
			# in a nice little table
			if ( $this->getUser()->isAllowed( 'suppressrevision' ) ) {
				$unsuppressBox =
					"<tr>
						<td>&#160;</td>
						<td class='mw-input'>" .
						Xml::checkLabel( $this->msg( 'revdelete-unsuppress' )->text(),
							'wpUnsuppress', 'mw-undelete-unsuppress', $this->mUnsuppress ) .
						"</td>
					</tr>";
			} else {
				$unsuppressBox = '';
			}

			$table = Xml::fieldset( $this->msg( 'undelete-fieldset-title' )->text() ) .
				Xml::openElement( 'table', [ 'id' => 'mw-undelete-table' ] ) .
				"<tr>
					<td colspan='2' class='mw-undelete-extrahelp'>" .
				$this->msg( 'undeleteextrahelp' )->parseAsBlock() .
				"</td>
			</tr>
			<tr>
				<td class='mw-label'>" .
				Xml::label( $this->msg( 'undeletecomment' )->text(), 'wpComment' ) .
				"</td>
				<td class='mw-input'>" .
				Xml::input(
					'wpComment',
					50,
					$this->mComment,
					[ 'id' => 'wpComment', 'autofocus' => '' ]
				) .
				"</td>
			</tr>
			<tr>
				<td>&#160;</td>
				<td class='mw-submit'>" .
				Xml::submitButton(
					$this->msg( 'undeletebtn' )->text(),
					[ 'name' => 'restore', 'id' => 'mw-undelete-submit' ]
				) . ' ' .
				Xml::submitButton(
					$this->msg( 'undeleteinvert' )->text(),
					[ 'name' => 'invert', 'id' => 'mw-undelete-invert' ]
				) .
				"</td>
			</tr>" .
				$unsuppressBox .
				Xml::closeElement( 'table' ) .
				Xml::closeElement( 'fieldset' );

			$out->addHTML( $table );
		}

		$out->addHTML( Xml::element( 'h2', null, $this->msg( 'history' )->text() ) . "\n" );

		if ( $haveRevisions ) {
			# Show the page's stored (deleted) history

			if ( $this->getUser()->isAllowed( 'deleterevision' ) ) {
				$out->addHTML( Html::element(
					'button',
					[
						'name' => 'revdel',
						'type' => 'submit',
						'class' => 'deleterevision-log-submit mw-log-deleterevision-button'
					],
					$this->msg( 'showhideselectedversions' )->text()
				) . "\n" );
			}

			$out->addHTML( '<ul>' );
			$remaining = $revisions->numRows();
			$earliestLiveTime = $this->mTargetObj->getEarliestRevTime();

			foreach ( $revisions as $row ) {
				$remaining--;
				$out->addHTML( $this->formatRevisionRow( $row, $earliestLiveTime, $remaining ) );
			}
			$revisions->free();
			$out->addHTML( '</ul>' );
		} else {
			$out->addWikiMsg( 'nohistory' );
		}

		if ( $haveFiles ) {
			$out->addHTML( Xml::element( 'h2', null, $this->msg( 'filehist' )->text() ) . "\n" );
			$out->addHTML( '<ul>' );
			foreach ( $files as $row ) {
				$out->addHTML( $this->formatFileRow( $row ) );
			}
			$files->free();
			$out->addHTML( '</ul>' );
		}

		if ( $this->mAllowed ) {
			# Slip in the hidden controls here
			$misc = Html::hidden( 'target', $this->mTarget );
			$misc .= Html::hidden( 'wpEditToken', $this->getUser()->getEditToken() );
			$misc .= Xml::closeElement( 'form' );
			$out->addHTML( $misc );
		}

		return true;
	}

	protected function formatRevisionRow( $row, $earliestLiveTime, $remaining ) {
		$rev = Revision::newFromArchiveRow( $row,
			[
				'title' => $this->mTargetObj
			] );

		$revTextSize = '';
		$ts = wfTimestamp( TS_MW, $row->ar_timestamp );
		// Build checkboxen...
		if ( $this->mAllowed ) {
			if ( $this->mInvert ) {
				if ( in_array( $ts, $this->mTargetTimestamp ) ) {
					$checkBox = Xml::check( "ts$ts" );
				} else {
					$checkBox = Xml::check( "ts$ts", true );
				}
			} else {
				$checkBox = Xml::check( "ts$ts" );
			}
		} else {
			$checkBox = '';
		}

		// Build page & diff links...
		$user = $this->getUser();
		if ( $this->mCanView ) {
			$titleObj = $this->getPageTitle();
			# Last link
			if ( !$rev->userCan( Revision::DELETED_TEXT, $this->getUser() ) ) {
				$pageLink = htmlspecialchars( $this->getLanguage()->userTimeAndDate( $ts, $user ) );
				$last = $this->msg( 'diff' )->escaped();
			} elseif ( $remaining > 0 || ( $earliestLiveTime && $ts > $earliestLiveTime ) ) {
				$pageLink = $this->getPageLink( $rev, $titleObj, $ts );
				$last = Linker::linkKnown(
					$titleObj,
					$this->msg( 'diff' )->escaped(),
					[],
					[
						'target' => $this->mTargetObj->getPrefixedText(),
						'timestamp' => $ts,
						'diff' => 'prev'
					]
				);
			} else {
				$pageLink = $this->getPageLink( $rev, $titleObj, $ts );
				$last = $this->msg( 'diff' )->escaped();
			}
		} else {
			$pageLink = htmlspecialchars( $this->getLanguage()->userTimeAndDate( $ts, $user ) );
			$last = $this->msg( 'diff' )->escaped();
		}

		// User links
		$userLink = Linker::revUserTools( $rev );

		// Minor edit
		$minor = $rev->isMinor() ? ChangesList::flag( 'minor' ) : '';

		// Revision text size
		$size = $row->ar_len;
		if ( !is_null( $size ) ) {
			$revTextSize = Linker::formatRevisionSize( $size );
		}

		// Edit summary
		$comment = Linker::revComment( $rev );

		// Tags
		$attribs = [];
		list( $tagSummary, $classes ) = ChangeTags::formatSummaryRow(
			$row->ts_tags,
			'deletedhistory',
			$this->getContext()
		);
		if ( $classes ) {
			$attribs['class'] = implode( ' ', $classes );
		}

		$revisionRow = $this->msg( 'undelete-revision-row2' )
			->rawParams(
				$checkBox,
				$last,
				$pageLink,
				$userLink,
				$minor,
				$revTextSize,
				$comment,
				$tagSummary
			)
			->escaped();

		return Xml::tags( 'li', $attribs, $revisionRow ) . "\n";
	}

	private function formatFileRow( $row ) {
		$file = ArchivedFile::newFromRow( $row );
		$ts = wfTimestamp( TS_MW, $row->fa_timestamp );
		$user = $this->getUser();

		$checkBox = '';
		if ( $this->mCanView && $row->fa_storage_key ) {
			if ( $this->mAllowed ) {
				$checkBox = Xml::check( 'fileid' . $row->fa_id );
			}
			$key = urlencode( $row->fa_storage_key );
			$pageLink = $this->getFileLink( $file, $this->getPageTitle(), $ts, $key );
		} else {
			$pageLink = $this->getLanguage()->userTimeAndDate( $ts, $user );
		}
		$userLink = $this->getFileUser( $file );
		$data = $this->msg( 'widthheight' )->numParams( $row->fa_width, $row->fa_height )->text();
		$bytes = $this->msg( 'parentheses' )
			->rawParams( $this->msg( 'nbytes' )->numParams( $row->fa_size )->text() )
			->plain();
		$data = htmlspecialchars( $data . ' ' . $bytes );
		$comment = $this->getFileComment( $file );

		// Add show/hide deletion links if available
		$canHide = $this->isAllowed( 'deleterevision' );
		if ( $canHide || ( $file->getVisibility() && $this->isAllowed( 'deletedhistory' ) ) ) {
			if ( !$file->userCan( File::DELETED_RESTRICTED, $user ) ) {
				// Revision was hidden from sysops
				$revdlink = Linker::revDeleteLinkDisabled( $canHide );
			} else {
				$query = [
					'type' => 'filearchive',
					'target' => $this->mTargetObj->getPrefixedDBkey(),
					'ids' => $row->fa_id
				];
				$revdlink = Linker::revDeleteLink( $query,
					$file->isDeleted( File::DELETED_RESTRICTED ), $canHide );
			}
		} else {
			$revdlink = '';
		}

		return "<li>$checkBox $revdlink $pageLink . . $userLink $data $comment</li>\n";
	}

	/**
	 * Fetch revision text link if it's available to all users
	 *
	 * @param Revision $rev
	 * @param Title $titleObj
	 * @param string $ts Timestamp
	 * @return string
	 */
	function getPageLink( $rev, $titleObj, $ts ) {
		$user = $this->getUser();
		$time = $this->getLanguage()->userTimeAndDate( $ts, $user );

		if ( !$rev->userCan( Revision::DELETED_TEXT, $user ) ) {
			return '<span class="history-deleted">' . $time . '</span>';
		}

		$link = Linker::linkKnown(
			$titleObj,
			htmlspecialchars( $time ),
			[],
			[
				'target' => $this->mTargetObj->getPrefixedText(),
				'timestamp' => $ts
			]
		);

		if ( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
			$link = '<span class="history-deleted">' . $link . '</span>';
		}

		return $link;
	}

	/**
	 * Fetch image view link if it's available to all users
	 *
	 * @param File|ArchivedFile $file
	 * @param Title $titleObj
	 * @param string $ts A timestamp
	 * @param string $key A storage key
	 *
	 * @return string HTML fragment
	 */
	function getFileLink( $file, $titleObj, $ts, $key ) {
		$user = $this->getUser();
		$time = $this->getLanguage()->userTimeAndDate( $ts, $user );

		if ( !$file->userCan( File::DELETED_FILE, $user ) ) {
			return '<span class="history-deleted">' . $time . '</span>';
		}

		$link = Linker::linkKnown(
			$titleObj,
			htmlspecialchars( $time ),
			[],
			[
				'target' => $this->mTargetObj->getPrefixedText(),
				'file' => $key,
				'token' => $user->getEditToken( $key )
			]
		);

		if ( $file->isDeleted( File::DELETED_FILE ) ) {
			$link = '<span class="history-deleted">' . $link . '</span>';
		}

		return $link;
	}

	/**
	 * Fetch file's user id if it's available to this user
	 *
	 * @param File|ArchivedFile $file
	 * @return string HTML fragment
	 */
	function getFileUser( $file ) {
		if ( !$file->userCan( File::DELETED_USER, $this->getUser() ) ) {
			return '<span class="history-deleted">' .
				$this->msg( 'rev-deleted-user' )->escaped() .
				'</span>';
		}

		$link = Linker::userLink( $file->getRawUser(), $file->getRawUserText() ) .
			Linker::userToolLinks( $file->getRawUser(), $file->getRawUserText() );

		if ( $file->isDeleted( File::DELETED_USER ) ) {
			$link = '<span class="history-deleted">' . $link . '</span>';
		}

		return $link;
	}

	/**
	 * Fetch file upload comment if it's available to this user
	 *
	 * @param File|ArchivedFile $file
	 * @return string HTML fragment
	 */
	function getFileComment( $file ) {
		if ( !$file->userCan( File::DELETED_COMMENT, $this->getUser() ) ) {
			return '<span class="history-deleted"><span class="comment">' .
				$this->msg( 'rev-deleted-comment' )->escaped() . '</span></span>';
		}

		$link = Linker::commentBlock( $file->getRawDescription() );

		if ( $file->isDeleted( File::DELETED_COMMENT ) ) {
			$link = '<span class="history-deleted">' . $link . '</span>';
		}

		return $link;
	}

	function undelete() {
		if ( $this->getConfig()->get( 'UploadMaintenance' )
			&& $this->mTargetObj->getNamespace() == NS_FILE
		) {
			throw new ErrorPageError( 'undelete-error', 'filedelete-maintenance' );
		}

		$this->checkReadOnly();

		$out = $this->getOutput();
		$archive = new PageArchive( $this->mTargetObj, $this->getConfig() );
		Hooks::run( 'UndeleteForm::undelete', [ &$archive, $this->mTargetObj ] );
		$ok = $archive->undelete(
			$this->mTargetTimestamp,
			$this->mComment,
			$this->mFileVersions,
			$this->mUnsuppress,
			$this->getUser()
		);

		if ( is_array( $ok ) ) {
			if ( $ok[1] ) { // Undeleted file count
				Hooks::run( 'FileUndeleteComplete', [
					$this->mTargetObj, $this->mFileVersions,
					$this->getUser(), $this->mComment ] );
			}

			$link = Linker::linkKnown( $this->mTargetObj );
			$out->addHTML( $this->msg( 'undeletedpage' )->rawParams( $link )->parse() );
		} else {
			$out->setPageTitle( $this->msg( 'undelete-error' ) );
		}

		// Show revision undeletion warnings and errors
		$status = $archive->getRevisionStatus();
		if ( $status && !$status->isGood() ) {
			$out->addWikiText( '<div class="error">' .
				$status->getWikiText(
					'cannotundelete',
					'cannotundelete'
				) . '</div>'
			);
		}

		// Show file undeletion warnings and errors
		$status = $archive->getFileStatus();
		if ( $status && !$status->isGood() ) {
			$out->addWikiText( '<div class="error">' .
				$status->getWikiText(
					'undelete-error-short',
					'undelete-error-long'
				) . '</div>'
			);
		}
	}

	/**
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return (usually 10)
	 * @param int $offset Number of results to skip (usually 0)
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit, $offset ) {
		return $this->prefixSearchString( $search, $limit, $offset );
	}

	protected function getGroupName() {
		return 'pagetools';
	}
}
