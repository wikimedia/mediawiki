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

	/**
	 * @var Title
	 */
	protected $title;
	var $fileStatus;

	function __construct( $title ) {
		if( is_null( $title ) ) {
			throw new MWException( __METHOD__ . ' given a null title.' );
		}
		$this->title = $title;
	}

	/**
	 * List all deleted pages recorded in the archive table. Returns result
	 * wrapper with (ar_namespace, ar_title, count) fields, ordered by page
	 * namespace/title.
	 *
	 * @return ResultWrapper
	 */
	public static function listAllPages() {
		$dbr = wfGetDB( DB_SLAVE );
		return self::listPages( $dbr, '' );
	}

	/**
	 * List deleted pages recorded in the archive table matching the
	 * given title prefix.
	 * Returns result wrapper with (ar_namespace, ar_title, count) fields.
	 *
	 * @param $prefix String: title prefix
	 * @return ResultWrapper
	 */
	public static function listPagesByPrefix( $prefix ) {
		$dbr = wfGetDB( DB_SLAVE );

		$title = Title::newFromText( $prefix );
		if( $title ) {
			$ns = $title->getNamespace();
			$prefix = $title->getDBkey();
		} else {
			// Prolly won't work too good
			// @todo handle bare namespace names cleanly?
			$ns = 0;
		}
		$conds = array(
			'ar_namespace' => $ns,
			'ar_title' . $dbr->buildLike( $prefix, $dbr->anyString() ),
		);
		return self::listPages( $dbr, $conds );
	}

	/**
	 * @param $dbr DatabaseBase
	 * @param $condition
	 * @return bool|ResultWrapper
	 */
	protected static function listPages( $dbr, $condition ) {
		return $dbr->resultObject(
			$dbr->select(
				array( 'archive' ),
				array(
					'ar_namespace',
					'ar_title',
					'COUNT(*) AS count'
				),
				$condition,
				__METHOD__,
				array(
					'GROUP BY' => 'ar_namespace,ar_title',
					'ORDER BY' => 'ar_namespace,ar_title',
					'LIMIT' => 100,
				)
			)
		);
	}

	/**
	 * List the revisions of the given page. Returns result wrapper with
	 * (ar_minor_edit, ar_timestamp, ar_user, ar_user_text, ar_comment) fields.
	 *
	 * @return ResultWrapper
	 */
	function listRevisions() {
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( 'archive',
			array(
				'ar_minor_edit', 'ar_timestamp', 'ar_user', 'ar_user_text',
				'ar_comment', 'ar_len', 'ar_deleted', 'ar_rev_id'
			),
			array( 'ar_namespace' => $this->title->getNamespace(),
				   'ar_title' => $this->title->getDBkey() ),
			'PageArchive::listRevisions',
			array( 'ORDER BY' => 'ar_timestamp DESC' ) );
		$ret = $dbr->resultObject( $res );
		return $ret;
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
		if( $this->title->getNamespace() == NS_FILE ) {
			$dbr = wfGetDB( DB_SLAVE );
			$res = $dbr->select( 'filearchive',
				array(
					'fa_id',
					'fa_name',
					'fa_archive_name',
					'fa_storage_key',
					'fa_storage_group',
					'fa_size',
					'fa_width',
					'fa_height',
					'fa_bits',
					'fa_metadata',
					'fa_media_type',
					'fa_major_mime',
					'fa_minor_mime',
					'fa_description',
					'fa_user',
					'fa_user_text',
					'fa_timestamp',
					'fa_deleted' ),
				array( 'fa_name' => $this->title->getDBkey() ),
				__METHOD__,
				array( 'ORDER BY' => 'fa_timestamp DESC' ) );
			$ret = $dbr->resultObject( $res );
			return $ret;
		}
		return null;
	}

	/**
	 * Return a Revision object containing data for the deleted revision.
	 * Note that the result *may* or *may not* have a null page ID.
	 *
	 * @param $timestamp String
	 * @return Revision
	 */
	function getRevision( $timestamp ) {
		$dbr = wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow( 'archive',
			array(
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
				'ar_len' ),
			array( 'ar_namespace' => $this->title->getNamespace(),
				   'ar_title' => $this->title->getDBkey(),
				   'ar_timestamp' => $dbr->timestamp( $timestamp ) ),
			__METHOD__ );
		if( $row ) {
			return Revision::newFromArchiveRow( $row, array( 'page' => $this->title->getArticleId() ) );
		} else {
			return null;
		}
	}

	/**
	 * Return the most-previous revision, either live or deleted, against
	 * the deleted revision given by timestamp.
	 *
	 * May produce unexpected results in case of history merges or other
	 * unusual time issues.
	 *
	 * @param $timestamp String
	 * @return Revision or null
	 */
	function getPreviousRevision( $timestamp ) {
		$dbr = wfGetDB( DB_SLAVE );

		// Check the previous deleted revision...
		$row = $dbr->selectRow( 'archive',
			'ar_timestamp',
			array( 'ar_namespace' => $this->title->getNamespace(),
				   'ar_title' => $this->title->getDBkey(),
				   'ar_timestamp < ' .
						$dbr->addQuotes( $dbr->timestamp( $timestamp ) ) ),
			__METHOD__,
			array(
				'ORDER BY' => 'ar_timestamp DESC',
				'LIMIT' => 1 ) );
		$prevDeleted = $row ? wfTimestamp( TS_MW, $row->ar_timestamp ) : false;

		$row = $dbr->selectRow( array( 'page', 'revision' ),
			array( 'rev_id', 'rev_timestamp' ),
			array(
				'page_namespace' => $this->title->getNamespace(),
				'page_title' => $this->title->getDBkey(),
				'page_id = rev_page',
				'rev_timestamp < ' .
						$dbr->addQuotes( $dbr->timestamp( $timestamp ) ) ),
			__METHOD__,
			array(
				'ORDER BY' => 'rev_timestamp DESC',
				'LIMIT' => 1 ) );
		$prevLive = $row ? wfTimestamp( TS_MW, $row->rev_timestamp ) : false;
		$prevLiveId = $row ? intval( $row->rev_id ) : null;

		if( $prevLive && $prevLive > $prevDeleted ) {
			// Most prior revision was live
			return Revision::newFromId( $prevLiveId );
		} elseif( $prevDeleted ) {
			// Most prior revision was deleted
			return $this->getRevision( $prevDeleted );
		} else {
			// No prior revision on this page.
			return null;
		}
	}

	/**
	 * Get the text from an archive row containing ar_text, ar_flags and ar_text_id
	 *
	 * @param $row Object: database row
	 * @return Revision
	 */
	function getTextFromRow( $row ) {
		if( is_null( $row->ar_text_id ) ) {
			// An old row from MediaWiki 1.4 or previous.
			// Text is embedded in this row in classic compression format.
			return Revision::getRevisionText( $row, 'ar_' );
		} else {
			// New-style: keyed to the text storage backend.
			$dbr = wfGetDB( DB_SLAVE );
			$text = $dbr->selectRow( 'text',
				array( 'old_text', 'old_flags' ),
				array( 'old_id' => $row->ar_text_id ),
				__METHOD__ );
			return Revision::getRevisionText( $text );
		}
	}

	/**
	 * Fetch (and decompress if necessary) the stored text of the most
	 * recently edited deleted revision of the page.
	 *
	 * If there are no archived revisions for the page, returns NULL.
	 *
	 * @return String
	 */
	function getLastRevisionText() {
		$dbr = wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow( 'archive',
			array( 'ar_text', 'ar_flags', 'ar_text_id' ),
			array( 'ar_namespace' => $this->title->getNamespace(),
				   'ar_title' => $this->title->getDBkey() ),
			__METHOD__,
			array( 'ORDER BY' => 'ar_timestamp DESC' ) );
		if( $row ) {
			return $this->getTextFromRow( $row );
		} else {
			return null;
		}
	}

	/**
	 * Quick check if any archived revisions are present for the page.
	 *
	 * @return Boolean
	 */
	function isDeleted() {
		$dbr = wfGetDB( DB_SLAVE );
		$n = $dbr->selectField( 'archive', 'COUNT(ar_title)',
			array( 'ar_namespace' => $this->title->getNamespace(),
				   'ar_title' => $this->title->getDBkey() ) );
		return ( $n > 0 );
	}

	/**
	 * Restore the given (or all) text and file revisions for the page.
	 * Once restored, the items will be removed from the archive tables.
	 * The deletion log will be updated with an undeletion notice.
	 *
	 * @param $timestamps Array: pass an empty array to restore all revisions, otherwise list the ones to undelete.
	 * @param $comment String
	 * @param $fileVersions Array
	 * @param $unsuppress Boolean
	 *
	 * @return array(number of file revisions restored, number of image revisions restored, log message)
	 * on success, false on failure
	 */
	function undelete( $timestamps, $comment = '', $fileVersions = array(), $unsuppress = false ) {
		// If both the set of text revisions and file revisions are empty,
		// restore everything. Otherwise, just restore the requested items.
		$restoreAll = empty( $timestamps ) && empty( $fileVersions );

		$restoreText = $restoreAll || !empty( $timestamps );
		$restoreFiles = $restoreAll || !empty( $fileVersions );

		if( $restoreFiles && $this->title->getNamespace() == NS_FILE ) {
			$img = wfLocalFile( $this->title );
			$this->fileStatus = $img->restore( $fileVersions, $unsuppress );
			if ( !$this->fileStatus->isOk() ) {
				return false;
			}
			$filesRestored = $this->fileStatus->successCount;
		} else {
			$filesRestored = 0;
		}

		if( $restoreText ) {
			$textRestored = $this->undeleteRevisions( $timestamps, $unsuppress, $comment );
			if( $textRestored === false ) { // It must be one of UNDELETE_*
				return false;
			}
		} else {
			$textRestored = 0;
		}

		// Touch the log!
		global $wgContLang;
		$log = new LogPage( 'delete' );

		if( $textRestored && $filesRestored ) {
			$reason = wfMsgExt( 'undeletedrevisions-files', array( 'content', 'parsemag' ),
				$wgContLang->formatNum( $textRestored ),
				$wgContLang->formatNum( $filesRestored ) );
		} elseif( $textRestored ) {
			$reason = wfMsgExt( 'undeletedrevisions', array( 'content', 'parsemag' ),
				$wgContLang->formatNum( $textRestored ) );
		} elseif( $filesRestored ) {
			$reason = wfMsgExt( 'undeletedfiles', array( 'content', 'parsemag' ),
				$wgContLang->formatNum( $filesRestored ) );
		} else {
			wfDebug( "Undelete: nothing undeleted...\n" );
			return false;
		}

		if( trim( $comment ) != '' ) {
			$reason .= wfMsgForContent( 'colon-separator' ) . $comment;
		}
		$log->addEntry( 'restore', $this->title, $reason );

		return array( $textRestored, $filesRestored, $reason );
	}

	/**
	 * This is the meaty bit -- restores archived revisions of the given page
	 * to the cur/old tables. If the page currently exists, all revisions will
	 * be stuffed into old, otherwise the most recent will go into cur.
	 *
	 * @param $timestamps Array: pass an empty array to restore all revisions, otherwise list the ones to undelete.
	 * @param $comment String
	 * @param $unsuppress Boolean: remove all ar_deleted/fa_deleted restrictions of seletected revs
	 *
	 * @return Mixed: number of revisions restored or false on failure
	 */
	private function undeleteRevisions( $timestamps, $unsuppress = false, $comment = '' ) {
		if ( wfReadOnly() ) {
			return false;
		}
		$restoreAll = empty( $timestamps );

		$dbw = wfGetDB( DB_MASTER );

		# Does this page already exist? We'll have to update it...
		$article = new Article( $this->title );
		# Load latest data for the current page (bug 31179)
		$article->loadPageData( 'fromdbmaster' );
		$oldcountable = $article->isCountable();

		$options = 'FOR UPDATE'; // lock page
		$page = $dbw->selectRow( 'page',
			array( 'page_id', 'page_latest' ),
			array( 'page_namespace' => $this->title->getNamespace(),
				   'page_title'     => $this->title->getDBkey() ),
			__METHOD__,
			$options
		);
		if( $page ) {
			$makepage = false;
			# Page already exists. Import the history, and if necessary
			# we'll update the latest revision field in the record.
			$newid             = 0;
			$pageId            = $page->page_id;
			$previousRevId    = $page->page_latest;
			# Get the time span of this page
			$previousTimestamp = $dbw->selectField( 'revision', 'rev_timestamp',
				array( 'rev_id' => $previousRevId ),
				__METHOD__ );
			if( $previousTimestamp === false ) {
				wfDebug( __METHOD__.": existing page refers to a page_latest that does not exist\n" );
				return 0;
			}
		} else {
			# Have to create a new article...
			$makepage = true;
			$previousRevId = 0;
			$previousTimestamp = 0;
		}

		if( $restoreAll ) {
			$oldones = '1 = 1'; # All revisions...
		} else {
			$oldts = implode( ',',
				array_map( array( &$dbw, 'addQuotes' ),
					array_map( array( &$dbw, 'timestamp' ),
						$timestamps ) ) );

			$oldones = "ar_timestamp IN ( {$oldts} )";
		}

		/**
		 * Select each archived revision...
		 */
		$result = $dbw->select( 'archive',
			/* fields */ array(
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
				'ar_page_id',
				'ar_len' ),
			/* WHERE */ array(
				'ar_namespace' => $this->title->getNamespace(),
				'ar_title'     => $this->title->getDBkey(),
				$oldones ),
			__METHOD__,
			/* options */ array( 'ORDER BY' => 'ar_timestamp' )
		);
		$ret = $dbw->resultObject( $result );
		$rev_count = $dbw->numRows( $result );
		if( !$rev_count ) {
			wfDebug( __METHOD__ . ": no revisions to restore\n" );
			return false; // ???
		}

		$ret->seek( $rev_count - 1 ); // move to last
		$row = $ret->fetchObject(); // get newest archived rev
		$ret->seek( 0 ); // move back

		if( $makepage ) {
			// Check the state of the newest to-be version...
			if( !$unsuppress && ( $row->ar_deleted & Revision::DELETED_TEXT ) ) {
				return false; // we can't leave the current revision like this!
			}
			// Safe to insert now...
			$newid  = $article->insertOn( $dbw );
			$pageId = $newid;
		} else {
			// Check if a deleted revision will become the current revision...
			if( $row->ar_timestamp > $previousTimestamp ) {
				// Check the state of the newest to-be version...
				if( !$unsuppress && ( $row->ar_deleted & Revision::DELETED_TEXT ) ) {
					return false; // we can't leave the current revision like this!
				}
			}
		}

		$revision = null;
		$restored = 0;

		foreach ( $ret as $row ) {
			// Check for key dupes due to shitty archive integrity.
			if( $row->ar_rev_id ) {
				$exists = $dbw->selectField( 'revision', '1',
					array( 'rev_id' => $row->ar_rev_id ), __METHOD__ );
				if( $exists ) {
					continue; // don't throw DB errors
				}
			}
			// Insert one revision at a time...maintaining deletion status
			// unless we are specifically removing all restrictions...
			$revision = Revision::newFromArchiveRow( $row,
				array(
					'page' => $pageId,
					'deleted' => $unsuppress ? 0 : $row->ar_deleted
				) );

			$revision->insertOn( $dbw );
			$restored++;

			wfRunHooks( 'ArticleRevisionUndeleted', array( &$this->title, $revision, $row->ar_page_id ) );
		}
		# Now that it's safely stored, take it out of the archive
		$dbw->delete( 'archive',
			/* WHERE */ array(
				'ar_namespace' => $this->title->getNamespace(),
				'ar_title' => $this->title->getDBkey(),
				$oldones ),
			__METHOD__ );

		// Was anything restored at all?
		if ( $restored == 0 ) {
			return 0;
		}

		$created = (bool)$newid;

		// Attach the latest revision to the page...
		$wasnew = $article->updateIfNewerOn( $dbw, $revision, $previousRevId );
		if ( $created || $wasnew ) {
			// Update site stats, link tables, etc
			$user = User::newFromName( $revision->getRawUserText(), false );
			$article->doEditUpdates( $revision, $user, array( 'created' => $created, 'oldcountable' => $oldcountable ) );
		}

		wfRunHooks( 'ArticleUndelete', array( &$this->title, $created, $comment ) );

		if( $this->title->getNamespace() == NS_FILE ) {
			$update = new HTMLCacheUpdate( $this->title, 'imagelinks' );
			$update->doUpdate();
		}

		return $restored;
	}

	/**
	 * @return Status
	 */
	function getFileStatus() { return $this->fileStatus; }
}

/**
 * Special page allowing users with the appropriate permissions to view
 * and restore deleted content.
 *
 * @ingroup SpecialPage
 */
class SpecialUndelete extends SpecialPage {
	var $mAction, $mTarget, $mTimestamp, $mRestore, $mInvert, $mFilename;
	var $mTargetTimestamp, $mAllowed, $mCanView, $mComment, $mToken;

	/**
	 * @var Title
	 */
	var $mTargetObj;

	function __construct() {
		parent::__construct( 'Undelete', 'deletedhistory' );
	}

	function loadRequest() {
		$request = $this->getRequest();
		$user = $this->getUser();

		$this->mAction = $request->getVal( 'action' );
		$this->mTarget = $request->getVal( 'target' );
		$this->mSearchPrefix = $request->getText( 'prefix' );
		$time = $request->getVal( 'timestamp' );
		$this->mTimestamp = $time ? wfTimestamp( TS_MW, $time ) : '';
		$this->mFilename = $request->getVal( 'file' );

		$posted = $request->wasPosted() &&
			$user->matchEditToken( $request->getVal( 'wpEditToken' ) );
		$this->mRestore = $request->getCheck( 'restore' ) && $posted;
		$this->mInvert = $request->getCheck( 'invert' ) && $posted;
		$this->mPreview = $request->getCheck( 'preview' ) && $posted;
		$this->mDiff = $request->getCheck( 'diff' );
		$this->mComment = $request->getText( 'wpComment' );
		$this->mUnsuppress = $request->getVal( 'wpUnsuppress' ) && $user->isAllowed( 'suppressrevision' );
		$this->mToken = $request->getVal( 'token' );

		if ( $user->isAllowed( 'undelete' ) && !$user->isBlocked() ) {
			$this->mAllowed = true; // user can restore
			$this->mCanView = true; // user can view content
		} elseif ( $user->isAllowed( 'deletedtext' ) ) {
			$this->mAllowed = false; // user cannot restore
			$this->mCanView = true; // user can view content
		} else { // user can only view the list of revisions
			$this->mAllowed = false;
			$this->mCanView = false;
			$this->mTimestamp = '';
			$this->mRestore = false;
		}

		if( $this->mRestore || $this->mInvert ) {
			$timestamps = array();
			$this->mFileVersions = array();
			foreach( $request->getValues() as $key => $val ) {
				$matches = array();
				if( preg_match( '/^ts(\d{14})$/', $key, $matches ) ) {
					array_push( $timestamps, $matches[1] );
				}

				if( preg_match( '/^fileid(\d+)$/', $key, $matches ) ) {
					$this->mFileVersions[] = intval( $matches[1] );
				}
			}
			rsort( $timestamps );
			$this->mTargetTimestamp = $timestamps;
		}
	}

	function execute( $par ) {
		$this->setHeaders();
		if ( !$this->userCanExecute( $this->getUser() ) ) {
			$this->displayRestrictionError();
			return;
		}
		$this->outputHeader();

		$this->loadRequest();

		$out = $this->getOutput();

		if ( $this->mAllowed ) {
			$out->setPageTitle( wfMsg( 'undeletepage' ) );
		} else {
			$out->setPageTitle( wfMsg( 'viewdeletedpage' ) );
		}

		if( $par != '' ) {
			$this->mTarget = $par;
		}
		if ( $this->mTarget !== '' ) {
			$this->mTargetObj = Title::newFromURL( $this->mTarget );
			$this->getSkin()->setRelevantTitle( $this->mTargetObj );
		} else {
			$this->mTargetObj = null;
		}

		if( is_null( $this->mTargetObj ) ) {
			# Not all users can just browse every deleted page from the list
			if( $this->getUser()->isAllowed( 'browsearchive' ) ) {
				$this->showSearchForm();

				# List undeletable articles
				if( $this->mSearchPrefix ) {
					$result = PageArchive::listPagesByPrefix( $this->mSearchPrefix );
					$this->showList( $result );
				}
			} else {
				$out->addWikiMsg( 'undelete-header' );
			}
			return;
		}
		if( $this->mTimestamp !== '' ) {
			return $this->showRevision( $this->mTimestamp );
		}
		if( $this->mFilename !== null ) {
			$file = new ArchivedFile( $this->mTargetObj, '', $this->mFilename );
			// Check if user is allowed to see this file
			if ( !$file->exists() ) {
				$out->addWikiMsg( 'filedelete-nofile', $this->mFilename );
				return;
			} elseif( !$file->userCan( File::DELETED_FILE ) ) {
				if( $file->isDeleted( File::DELETED_RESTRICTED ) ) {
					$out->permissionRequired( 'suppressrevision' );
				} else {
					$out->permissionRequired( 'deletedtext' );
				}
				return false;
			} elseif ( !$this->getUser()->matchEditToken( $this->mToken, $this->mFilename ) ) {
				$this->showFileConfirmationForm( $this->mFilename );
				return false;
			} else {
				return $this->showFile( $this->mFilename );
			}
		}
		if( $this->mRestore && $this->mAction == 'submit' ) {
			global $wgUploadMaintenance;
			if( $wgUploadMaintenance && $this->mTargetObj && $this->mTargetObj->getNamespace() == NS_FILE ) {
				$out->wrapWikiMsg( "<div class='error'>\n$1\n</div>\n", array( 'filedelete-maintenance' ) );
				return;
			}
			return $this->undelete();
		}
		if( $this->mInvert && $this->mAction == 'submit' ) {
			return $this->showHistory();
		}
		return $this->showHistory();
	}

	function showSearchForm() {
		global $wgScript;

		$this->getOutput()->addWikiMsg( 'undelete-header' );

		$this->getOutput()->addHTML(
			Xml::openElement( 'form', array(
				'method' => 'get',
				'action' => $wgScript ) ) .
			Xml::fieldset( wfMsg( 'undelete-search-box' ) ) .
			Html::hidden( 'title',
				$this->getTitle()->getPrefixedDbKey() ) .
			Xml::inputLabel( wfMsg( 'undelete-search-prefix' ),
				'prefix', 'prefix', 20,
				$this->mSearchPrefix ) . ' ' .
			Xml::submitButton( wfMsg( 'undelete-search-submit' ) ) .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' )
		);
	}

	/**
	 * Generic list of deleted pages
	 *
	 * @param $result ResultWrapper
	 * @return bool
	 */
	private function showList( $result ) {
		$out = $this->getOutput();

		if( $result->numRows() == 0 ) {
			$out->addWikiMsg( 'undelete-no-results' );
			return;
		}

		$out->addWikiMsg( 'undeletepagetext', $this->getLang()->formatNum( $result->numRows() ) );

		$undelete = $this->getTitle();
		$out->addHTML( "<ul>\n" );
		foreach ( $result as $row ) {
			$title = Title::makeTitleSafe( $row->ar_namespace, $row->ar_title );
			$link = Linker::linkKnown(
				$undelete,
				htmlspecialchars( $title->getPrefixedText() ),
				array(),
				array( 'target' => $title->getPrefixedText() )
			);
			$revs = wfMsgExt( 'undeleterevisions',
				array( 'parseinline' ),
				$this->getLang()->formatNum( $row->count ) );
			$out->addHTML( "<li>{$link} ({$revs})</li>\n" );
		}
		$result->free();
		$out->addHTML( "</ul>\n" );

		return true;
	}

	private function showRevision( $timestamp ) {
		$out = $this->getOutput();

		if( !preg_match( '/[0-9]{14}/', $timestamp ) ) {
			return 0;
		}

		$archive = new PageArchive( $this->mTargetObj );
		wfRunHooks( 'UndeleteForm::showRevision', array( &$archive, $this->mTargetObj ) );
		$rev = $archive->getRevision( $timestamp );

		if( !$rev ) {
			$out->addWikiMsg( 'undeleterevision-missing' );
			return;
		}

		if( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
			if( !$rev->userCan( Revision::DELETED_TEXT ) ) {
				$out->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n", 'rev-deleted-text-permission' );
				return;
			} else {
				$out->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n", 'rev-deleted-text-view' );
				$out->addHTML( '<br />' );
				// and we are allowed to see...
			}
		}

		$out->setPageTitle( wfMsg( 'undeletepage' ) );

		if( $this->mDiff ) {
			$previousRev = $archive->getPreviousRevision( $timestamp );
			if( $previousRev ) {
				$this->showDiff( $previousRev, $rev );
				if( $this->getUser()->getOption( 'diffonly' ) ) {
					return;
				} else {
					$out->addHTML( '<hr />' );
				}
			} else {
				$out->addWikiMsg( 'undelete-nodiff' );
			}
		}

		$link = Linker::linkKnown(
			$this->getTitle( $this->mTargetObj->getPrefixedDBkey() ),
			htmlspecialchars( $this->mTargetObj->getPrefixedText() )
		);

		// date and time are separate parameters to facilitate localisation.
		// $time is kept for backward compat reasons.
		$time = $this->getLang()->timeAndDate( $timestamp, true );
		$d = $this->getLang()->date( $timestamp, true );
		$t = $this->getLang()->time( $timestamp, true );
		$user = Linker::revUserTools( $rev );

		if( $this->mPreview ) {
			$openDiv = '<div id="mw-undelete-revision" class="mw-warning">';
		} else {
			$openDiv = '<div id="mw-undelete-revision">';
		}
		$out->addHTML( $openDiv );

		// Revision delete links
		if ( !$this->mDiff ) {
			$revdel = $this->revDeleteLink( $rev );
			if ( $revdel ) {
				$out->addHTML( $revdel );
			}
		}

		$out->addHTML( wfMessage( 'undelete-revision' )->rawParams( $link )->params(
			$time )->rawParams( $user )->params( $d, $t )->parse() . '</div>' );
		wfRunHooks( 'UndeleteShowRevision', array( $this->mTargetObj, $rev ) );

		if( $this->mPreview ) {
			// Hide [edit]s
			$popts = $out->parserOptions();
			$popts->setEditSection( false );
			$out->parserOptions( $popts );
			$out->addWikiTextTitleTidy( $rev->getText( Revision::FOR_THIS_USER ), $this->mTargetObj, true );
		}

		$out->addHTML(
			Xml::element( 'textarea', array(
					'readonly' => 'readonly',
					'cols' => intval( $this->getUser()->getOption( 'cols' ) ),
					'rows' => intval( $this->getUser()->getOption( 'rows' ) ) ),
				$rev->getText( Revision::FOR_THIS_USER ) . "\n" ) .
			Xml::openElement( 'div' ) .
			Xml::openElement( 'form', array(
				'method' => 'post',
				'action' => $this->getTitle()->getLocalURL( array( 'action' => 'submit' ) ) ) ) .
			Xml::element( 'input', array(
				'type' => 'hidden',
				'name' => 'target',
				'value' => $this->mTargetObj->getPrefixedDbKey() ) ) .
			Xml::element( 'input', array(
				'type' => 'hidden',
				'name' => 'timestamp',
				'value' => $timestamp ) ) .
			Xml::element( 'input', array(
				'type' => 'hidden',
				'name' => 'wpEditToken',
				'value' => $this->getUser()->editToken() ) ) .
			Xml::element( 'input', array(
				'type' => 'submit',
				'name' => 'preview',
				'value' => wfMsg( 'showpreview' ) ) ) .
			Xml::element( 'input', array(
				'name' => 'diff',
				'type' => 'submit',
				'value' => wfMsg( 'showdiff' ) ) ) .
			Xml::closeElement( 'form' ) .
			Xml::closeElement( 'div' ) );
	}

	/**
	 * Get a revision-deletion link, or disabled link, or nothing, depending
	 * on user permissions & the settings on the revision.
	 *
	 * Will use forward-compatible revision ID in the Special:RevDelete link
	 * if possible, otherwise the timestamp-based ID which may break after
	 * undeletion.
	 *
	 * @param Revision $rev
	 * @return string HTML fragment
	 */
	function revDeleteLink( $rev ) {
		$canHide = $this->getUser()->isAllowed( 'deleterevision' );
		if( $canHide || ( $rev->getVisibility() && $this->getUser()->isAllowed( 'deletedhistory' ) ) ) {
			if( !$rev->userCan( Revision::DELETED_RESTRICTED ) ) {
				$revdlink = Linker::revDeleteLinkDisabled( $canHide ); // revision was hidden from sysops
			} else {
				if ( $rev->getId() ) {
					// RevDelete links using revision ID are stable across
					// page deletion and undeletion; use when possible.
					$query = array(
						'type'   => 'revision',
						'target' => $this->mTargetObj->getPrefixedDBkey(),
						'ids'    => $rev->getId()
					);
				} else {
					// Older deleted entries didn't save a revision ID.
					// We have to refer to these by timestamp, ick!
					$query = array(
						'type'   => 'archive',
						'target' => $this->mTargetObj->getPrefixedDBkey(),
						'ids'    => $rev->getTimestamp()
					);
				}
				return Linker::revDeleteLink( $query,
					$rev->isDeleted( File::DELETED_RESTRICTED ), $canHide );
			}
		} else {
			return '';
		}
	}

	/**
	 * Build a diff display between this and the previous either deleted
	 * or non-deleted edit.
	 *
	 * @param $previousRev Revision
	 * @param $currentRev Revision
	 * @return String: HTML
	 */
	function showDiff( $previousRev, $currentRev ) {
		$diffEngine = new DifferenceEngine( $previousRev->getTitle() );
		$diffEngine->showDiffStyle();
		$this->getOutput()->addHTML(
			"<div>" .
			"<table border='0' width='98%' cellpadding='0' cellspacing='4' class='diff'>" .
			"<col class='diff-marker' />" .
			"<col class='diff-content' />" .
			"<col class='diff-marker' />" .
			"<col class='diff-content' />" .
			"<tr>" .
				"<td colspan='2' width='50%' align='center' class='diff-otitle'>" .
				$this->diffHeader( $previousRev, 'o' ) .
				"</td>\n" .
				"<td colspan='2' width='50%' align='center' class='diff-ntitle'>" .
				$this->diffHeader( $currentRev, 'n' ) .
				"</td>\n" .
			"</tr>" .
			$diffEngine->generateDiffBody(
				$previousRev->getText(), $currentRev->getText() ) .
			"</table>" .
			"</div>\n"
		);
	}

	/**
	 * @param $rev Revision
	 * @param $prefix
	 * @return string
	 */
	private function diffHeader( $rev, $prefix ) {
		$isDeleted = !( $rev->getId() && $rev->getTitle() );
		if( $isDeleted ) {
			/// @todo FIXME: $rev->getTitle() is null for deleted revs...?
			$targetPage = $this->getTitle();
			$targetQuery = array(
				'target' => $this->mTargetObj->getPrefixedText(),
				'timestamp' => wfTimestamp( TS_MW, $rev->getTimestamp() )
			);
		} else {
			/// @todo FIXME: getId() may return non-zero for deleted revs...
			$targetPage = $rev->getTitle();
			$targetQuery = array( 'oldid' => $rev->getId() );
		}
		// Add show/hide deletion links if available
		$del = $this->revDeleteLink( $rev );
		return
			'<div id="mw-diff-' . $prefix . 'title1"><strong>' .
				Linker::link(
					$targetPage,
					wfMsgExt(
						'revisionasof',
						array( 'escape' ),
						$this->getLang()->timeanddate( $rev->getTimestamp(), true ),
						$this->getLang()->date( $rev->getTimestamp(), true ),
						$this->getLang()->time( $rev->getTimestamp(), true )
					),
					array(),
					$targetQuery
				) .
			'</strong></div>' .
			'<div id="mw-diff-'.$prefix.'title2">' .
				Linker::revUserTools( $rev ) . '<br />' .
			'</div>' .
			'<div id="mw-diff-'.$prefix.'title3">' .
				Linker::revComment( $rev ) . $del . '<br />' .
			'</div>';
	}

	/**
	 * Show a form confirming whether a tokenless user really wants to see a file
	 */
	private function showFileConfirmationForm( $key ) {
		$file = new ArchivedFile( $this->mTargetObj, '', $this->mFilename );
		$this->getOutput()->addWikiMsg( 'undelete-show-file-confirm',
			$this->mTargetObj->getText(),
			$this->getLang()->date( $file->getTimestamp() ),
			$this->getLang()->time( $file->getTimestamp() ) );
		$this->getOutput()->addHTML(
			Xml::openElement( 'form', array(
				'method' => 'POST',
				'action' => $this->getTitle()->getLocalURL(
					'target=' . urlencode( $this->mTarget ) .
					'&file=' . urlencode( $key ) .
					'&token=' . urlencode( $this->getUser()->editToken( $key ) ) )
				)
			) .
			Xml::submitButton( wfMsg( 'undelete-show-file-submit' ) ) .
			'</form>'
		);
	}

	/**
	 * Show a deleted file version requested by the visitor.
	 */
	private function showFile( $key ) {
		$this->getOutput()->disable();

		# We mustn't allow the output to be Squid cached, otherwise
		# if an admin previews a deleted image, and it's cached, then
		# a user without appropriate permissions can toddle off and
		# nab the image, and Squid will serve it
		$response = $this->getRequest()->response();
		$response->header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', 0 ) . ' GMT' );
		$response->header( 'Cache-Control: no-cache, no-store, max-age=0, must-revalidate' );
		$response->header( 'Pragma: no-cache' );

		global $IP;
		require_once( "$IP/includes/StreamFile.php" );
		$repo = RepoGroup::singleton()->getLocalRepo();
		$path = $repo->getZonePath( 'deleted' ) . '/' . $repo->getDeletedHashPath( $key ) . $key;
		wfStreamFile( $path );
	}

	private function showHistory() {
		$out = $this->getOutput();
		if( $this->mAllowed ) {
			$out->addModules( 'mediawiki.special.undelete' );
			$out->setPageTitle( wfMsg( 'undeletepage' ) );
		} else {
			$out->setPageTitle( wfMsg( 'viewdeletedpage' ) );
		}
		$out->wrapWikiMsg(
			"<div class='mw-undelete-pagetitle'>\n$1\n</div>\n",
			array( 'undeletepagetitle', $this->mTargetObj->getPrefixedText() )
		);

		$archive = new PageArchive( $this->mTargetObj );
		wfRunHooks( 'UndeleteForm::showHistory', array( &$archive, $this->mTargetObj ) );
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
		if( $haveRevisions ) {
			$batch = new LinkBatch();
			foreach ( $revisions as $row ) {
				$batch->addObj( Title::makeTitleSafe( NS_USER, $row->ar_user_text ) );
				$batch->addObj( Title::makeTitleSafe( NS_USER_TALK, $row->ar_user_text ) );
			}
			$batch->execute();
			$revisions->seek( 0 );
		}
		if( $haveFiles ) {
			$batch = new LinkBatch();
			foreach ( $files as $row ) {
				$batch->addObj( Title::makeTitleSafe( NS_USER, $row->fa_user_text ) );
				$batch->addObj( Title::makeTitleSafe( NS_USER_TALK, $row->fa_user_text ) );
			}
			$batch->execute();
			$files->seek( 0 );
		}

		if ( $this->mAllowed ) {
			$action = $this->getTitle()->getLocalURL( array( 'action' => 'submit' ) );
			# Start the form here
			$top = Xml::openElement( 'form', array( 'method' => 'post', 'action' => $action, 'id' => 'undelete' ) );
			$out->addHTML( $top );
		}

		# Show relevant lines from the deletion log:
		$out->addHTML( Xml::element( 'h2', null, LogPage::logName( 'delete' ) ) . "\n" );
		LogEventsList::showLogExtract( $out, 'delete', $this->mTargetObj->getPrefixedText() );
		# Show relevant lines from the suppression log:
		if( $this->getUser()->isAllowed( 'suppressionlog' ) ) {
			$out->addHTML( Xml::element( 'h2', null, LogPage::logName( 'suppress' ) ) . "\n" );
			LogEventsList::showLogExtract( $out, 'suppress', $this->mTargetObj->getPrefixedText() );
		}

		if( $this->mAllowed && ( $haveRevisions || $haveFiles ) ) {
			# Format the user-visible controls (comment field, submission button)
			# in a nice little table
			if( $this->getUser()->isAllowed( 'suppressrevision' ) ) {
				$unsuppressBox =
					"<tr>
						<td>&#160;</td>
						<td class='mw-input'>" .
							Xml::checkLabel( wfMsg( 'revdelete-unsuppress' ), 'wpUnsuppress',
								'mw-undelete-unsuppress', $this->mUnsuppress ).
						"</td>
					</tr>";
			} else {
				$unsuppressBox = '';
			}
			$table =
				Xml::fieldset( wfMsg( 'undelete-fieldset-title' ) ) .
				Xml::openElement( 'table', array( 'id' => 'mw-undelete-table' ) ) .
					"<tr>
						<td colspan='2' class='mw-undelete-extrahelp'>" .
							wfMsgExt( 'undeleteextrahelp', 'parse' ) .
						"</td>
					</tr>
					<tr>
						<td class='mw-label'>" .
							Xml::label( wfMsg( 'undeletecomment' ), 'wpComment' ) .
						"</td>
						<td class='mw-input'>" .
							Xml::input( 'wpComment', 50, $this->mComment, array( 'id' =>  'wpComment' ) ) .
						"</td>
					</tr>
					<tr>
						<td>&#160;</td>
						<td class='mw-submit'>" .
							Xml::submitButton( wfMsg( 'undeletebtn' ), array( 'name' => 'restore', 'id' => 'mw-undelete-submit' ) ) . ' ' .
							Xml::submitButton( wfMsg( 'undeleteinvert' ), array( 'name' => 'invert', 'id' => 'mw-undelete-invert' ) ) .
						"</td>
					</tr>" .
					$unsuppressBox .
				Xml::closeElement( 'table' ) .
				Xml::closeElement( 'fieldset' );

			$out->addHTML( $table );
		}

		$out->addHTML( Xml::element( 'h2', null, wfMsg( 'history' ) ) . "\n" );

		if( $haveRevisions ) {
			# The page's stored (deleted) history:
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

		if( $haveFiles ) {
			$out->addHTML( Xml::element( 'h2', null, wfMsg( 'filehist' ) ) . "\n" );
			$out->addHTML( '<ul>' );
			foreach ( $files as $row ) {
				$out->addHTML( $this->formatFileRow( $row ) );
			}
			$files->free();
			$out->addHTML( '</ul>' );
		}

		if ( $this->mAllowed ) {
			# Slip in the hidden controls here
			$misc  = Html::hidden( 'target', $this->mTarget );
			$misc .= Html::hidden( 'wpEditToken', $this->getUser()->editToken() );
			$misc .= Xml::closeElement( 'form' );
			$out->addHTML( $misc );
		}

		return true;
	}

	private function formatRevisionRow( $row, $earliestLiveTime, $remaining ) {
		$rev = Revision::newFromArchiveRow( $row,
			array( 'page' => $this->mTargetObj->getArticleId() ) );
		$stxt = '';
		$ts = wfTimestamp( TS_MW, $row->ar_timestamp );
		// Build checkboxen...
		if( $this->mAllowed ) {
			if( $this->mInvert ) {
				if( in_array( $ts, $this->mTargetTimestamp ) ) {
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
		if( $this->mCanView ) {
			$titleObj = $this->getTitle();
			# Last link
			if( !$rev->userCan( Revision::DELETED_TEXT ) ) {
				$pageLink = htmlspecialchars( $this->getLang()->timeanddate( $ts, true ) );
				$last = wfMsgHtml( 'diff' );
			} elseif( $remaining > 0 || ( $earliestLiveTime && $ts > $earliestLiveTime ) ) {
				$pageLink = $this->getPageLink( $rev, $titleObj, $ts );
				$last = Linker::linkKnown(
					$titleObj,
					wfMsgHtml( 'diff' ),
					array(),
					array(
						'target' => $this->mTargetObj->getPrefixedText(),
						'timestamp' => $ts,
						'diff' => 'prev'
					)
				);
			} else {
				$pageLink = $this->getPageLink( $rev, $titleObj, $ts );
				$last = wfMsgHtml( 'diff' );
			}
		} else {
			$pageLink = htmlspecialchars( $this->getLang()->timeanddate( $ts, true ) );
			$last = wfMsgHtml( 'diff' );
		}
		// User links
		$userLink = Linker::revUserTools( $rev );
		// Revision text size
		$size = $row->ar_len;
		if( !is_null( $size ) ) {
			$stxt = Linker::formatRevisionSize( $size );
		}
		// Edit summary
		$comment = Linker::revComment( $rev );
		// Revision delete links
		$revdlink = $this->revDeleteLink( $rev );
		return "<li>$checkBox $revdlink ($last) $pageLink . . $userLink $stxt $comment</li>";
	}

	private function formatFileRow( $row ) {
		$file = ArchivedFile::newFromRow( $row );

		$ts = wfTimestamp( TS_MW, $row->fa_timestamp );
		if( $this->mAllowed && $row->fa_storage_key ) {
			$checkBox = Xml::check( 'fileid' . $row->fa_id );
			$key = urlencode( $row->fa_storage_key );
			$pageLink = $this->getFileLink( $file, $this->getTitle(), $ts, $key );
		} else {
			$checkBox = '';
			$pageLink = $this->getLang()->timeanddate( $ts, true );
		}
		$userLink = $this->getFileUser( $file );
		$data =
			wfMsg( 'widthheight',
				$this->getLang()->formatNum( $row->fa_width ),
				$this->getLang()->formatNum( $row->fa_height ) ) .
			' (' .
			wfMsg( 'nbytes', $this->getLang()->formatNum( $row->fa_size ) ) .
			')';
		$data = htmlspecialchars( $data );
		$comment = $this->getFileComment( $file );
		// Add show/hide deletion links if available
		$canHide = $this->getUser()->isAllowed( 'deleterevision' );
		if( $canHide || ( $file->getVisibility() && $this->getUser()->isAllowed( 'deletedhistory' ) ) ) {
			if( !$file->userCan( File::DELETED_RESTRICTED ) ) {
				$revdlink = Linker::revDeleteLinkDisabled( $canHide ); // revision was hidden from sysops
			} else {
				$query = array(
					'type' => 'filearchive',
					'target' => $this->mTargetObj->getPrefixedDBkey(),
					'ids' => $row->fa_id
				);
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
	 * @param $rev Revision
	 * @return string
	 */
	function getPageLink( $rev, $titleObj, $ts ) {
		$time = htmlspecialchars( $this->getLang()->timeanddate( $ts, true ) );

		if( !$rev->userCan( Revision::DELETED_TEXT ) ) {
			return '<span class="history-deleted">' . $time . '</span>';
		} else {
			$link = Linker::linkKnown(
				$titleObj,
				$time,
				array(),
				array(
					'target' => $this->mTargetObj->getPrefixedText(),
					'timestamp' => $ts
				)
			);
			if( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
				$link = '<span class="history-deleted">' . $link . '</span>';
			}
			return $link;
		}
	}

	/**
	 * Fetch image view link if it's available to all users
	 *
	 * @param $file File
	 * @return String: HTML fragment
	 */
	function getFileLink( $file, $titleObj, $ts, $key ) {
		if( !$file->userCan( File::DELETED_FILE ) ) {
			return '<span class="history-deleted">' . $this->getLang()->timeanddate( $ts, true ) . '</span>';
		} else {
			$link = Linker::linkKnown(
				$titleObj,
				$this->getLang()->timeanddate( $ts, true ),
				array(),
				array(
					'target' => $this->mTargetObj->getPrefixedText(),
					'file' => $key,
					'token' => $this->getUser()->editToken( $key )
				)
			);
			if( $file->isDeleted( File::DELETED_FILE ) ) {
				$link = '<span class="history-deleted">' . $link . '</span>';
			}
			return $link;
		}
	}

	/**
	 * Fetch file's user id if it's available to this user
	 *
	 * @param $file File
	 * @return String: HTML fragment
	 */
	function getFileUser( $file ) {
		if( !$file->userCan( File::DELETED_USER ) ) {
			return '<span class="history-deleted">' . wfMsgHtml( 'rev-deleted-user' ) . '</span>';
		} else {
			$link = Linker::userLink( $file->getRawUser(), $file->getRawUserText() ) .
				Linker::userToolLinks( $file->getRawUser(), $file->getRawUserText() );
			if( $file->isDeleted( File::DELETED_USER ) ) {
				$link = '<span class="history-deleted">' . $link . '</span>';
			}
			return $link;
		}
	}

	/**
	 * Fetch file upload comment if it's available to this user
	 *
	 * @param $file File
	 * @return String: HTML fragment
	 */
	function getFileComment( $file ) {
		if( !$file->userCan( File::DELETED_COMMENT ) ) {
			return '<span class="history-deleted"><span class="comment">' .
				wfMsgHtml( 'rev-deleted-comment' ) . '</span></span>';
		} else {
			$link = Linker::commentBlock( $file->getRawDescription() );
			if( $file->isDeleted( File::DELETED_COMMENT ) ) {
				$link = '<span class="history-deleted">' . $link . '</span>';
			}
			return $link;
		}
	}

	function undelete() {
		if ( wfReadOnly() ) {
			throw new ReadOnlyError;
		}

		if( !is_null( $this->mTargetObj ) ) {
			$archive = new PageArchive( $this->mTargetObj );
			wfRunHooks( 'UndeleteForm::undelete', array( &$archive, $this->mTargetObj ) );
			$ok = $archive->undelete(
				$this->mTargetTimestamp,
				$this->mComment,
				$this->mFileVersions,
				$this->mUnsuppress );

			if( is_array( $ok ) ) {
				if ( $ok[1] ) { // Undeleted file count
					wfRunHooks( 'FileUndeleteComplete', array(
						$this->mTargetObj, $this->mFileVersions,
						$this->getUser(), $this->mComment ) );
				}

				$link = Linker::linkKnown( $this->mTargetObj );
				$this->getOutput()->addHTML( wfMessage( 'undeletedpage' )->rawParams( $link )->parse() );
			} else {
				$this->getOutput()->showFatalError( wfMsg( 'cannotundelete' ) );
				$this->getOutput()->addWikiMsg( 'undeleterevdel' );
			}

			// Show file deletion warnings and errors
			$status = $archive->getFileStatus();
			if( $status && !$status->isGood() ) {
				$this->getOutput()->addWikiText( $status->getWikiText( 'undelete-error-short', 'undelete-error-long' ) );
			}
		} else {
			$this->getOutput()->showFatalError( wfMsg( 'cannotundelete' ) );
		}
		return false;
	}
}
