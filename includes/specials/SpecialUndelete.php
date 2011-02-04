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
			array( 'ar_minor_edit', 'ar_timestamp', 'ar_user', 'ar_user_text', 'ar_comment', 'ar_len', 'ar_deleted' ),
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
	 * Fetch (and decompress if necessary) the stored text for the deleted
	 * revision of the page with the given timestamp.
	 *
	 * @param $timestamp String
	 * @return String
	 * @deprecated Use getRevision() for more flexible information
	 */
	function getRevisionText( $timestamp ) {
		$rev = $this->getRevision( $timestamp );
		return $rev ? $rev->getText() : null;
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
			return Revision::getRevisionText( $row, "ar_" );
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
		return ($n > 0);
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
			if($textRestored === false) // It must be one of UNDELETE_*
				return false;
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

		if( trim( $comment ) != '' )
			$reason .= wfMsgForContent( 'colon-separator' ) . $comment;
		$log->addEntry( 'restore', $this->title, $reason );

		return array($textRestored, $filesRestored, $reason);
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
		if ( wfReadOnly() )
			return false;
		$restoreAll = empty( $timestamps );

		$dbw = wfGetDB( DB_MASTER );

		# Does this page already exist? We'll have to update it...
		$article = new Article( $this->title );
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
			wfDebug( __METHOD__.": no revisions to restore\n" );
			return false; // ???
		}

		$ret->seek( $rev_count - 1 ); // move to last
		$row = $ret->fetchObject(); // get newest archived rev
		$ret->seek( 0 ); // move back

		if( $makepage ) {
			// Check the state of the newest to-be version...
			if( !$unsuppress && ($row->ar_deleted & Revision::DELETED_TEXT) ) {
				return false; // we can't leave the current revision like this!
			}
			// Safe to insert now...
			$newid  = $article->insertOn( $dbw );
			$pageId = $newid;
		} else {
			// Check if a deleted revision will become the current revision...
			if( $row->ar_timestamp > $previousTimestamp ) {
				// Check the state of the newest to-be version...
				if( !$unsuppress && ($row->ar_deleted & Revision::DELETED_TEXT) ) {
					return false; // we can't leave the current revision like this!
				}
			}
		}

		$revision = null;
		$restored = 0;

		foreach ( $ret as $row ) {
			// Check for key dupes due to shitty archive integrity.
			if( $row->ar_rev_id ) {
				$exists = $dbw->selectField( 'revision', '1', array('rev_id' => $row->ar_rev_id), __METHOD__ );
				if( $exists ) continue; // don't throw DB errors
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
		if( $restored == 0 )
			return 0;

		if( $revision ) {
			// Attach the latest revision to the page...
			$wasnew = $article->updateIfNewerOn( $dbw, $revision, $previousRevId );
			if( $newid || $wasnew ) {
				// Update site stats, link tables, etc
				$article->createUpdates( $revision );
			}

			if( $newid ) {
				wfRunHooks( 'ArticleUndelete', array( &$this->title, true, $comment ) );
				Article::onArticleCreate( $this->title );
			} else {
				wfRunHooks( 'ArticleUndelete', array( &$this->title, false, $comment ) );
				Article::onArticleEdit( $this->title );
			}

			if( $this->title->getNamespace() == NS_FILE ) {
				$update = new HTMLCacheUpdate( $this->title, 'imagelinks' );
				$update->doUpdate();
			}
		} else {
			// Revision couldn't be created. This is very weird
			wfDebug( "Undelete: unknown error...\n" );
			return false;
		}

		return $restored;
	}

	function getFileStatus() { return $this->fileStatus; }
}

/**
 * Special page allowing users with the appropriate permissions to view
 * and restore deleted content.
 *
 * @ingroup SpecialPage
 */
class SpecialUndelete extends SpecialPage {
	var $mAction, $mTarget, $mTimestamp, $mRestore, $mInvert, $mTargetObj;
	var $mTargetTimestamp, $mAllowed, $mCanView, $mComment, $mToken, $mRequest;

	function __construct( $request = null ) {
		parent::__construct( 'Undelete', 'deletedhistory' );

		if ( $request === null ) {
			global $wgRequest;
			$this->mRequest = $wgRequest;
		} else {
			$this->mRequest = $request;
		}
	}

	function loadRequest() {
		global $wgUser;
		$this->mAction = $this->mRequest->getVal( 'action' );
		$this->mTarget = $this->mRequest->getVal( 'target' );
		$this->mSearchPrefix = $this->mRequest->getText( 'prefix' );
		$time = $this->mRequest->getVal( 'timestamp' );
		$this->mTimestamp = $time ? wfTimestamp( TS_MW, $time ) : '';
		$this->mFile = $this->mRequest->getVal( 'file' );

		$posted = $this->mRequest->wasPosted() &&
			$wgUser->matchEditToken( $this->mRequest->getVal( 'wpEditToken' ) );
		$this->mRestore = $this->mRequest->getCheck( 'restore' ) && $posted;
		$this->mInvert = $this->mRequest->getCheck( 'invert' ) && $posted;
		$this->mPreview = $this->mRequest->getCheck( 'preview' ) && $posted;
		$this->mDiff = $this->mRequest->getCheck( 'diff' );
		$this->mComment = $this->mRequest->getText( 'wpComment' );
		$this->mUnsuppress = $this->mRequest->getVal( 'wpUnsuppress' ) && $wgUser->isAllowed( 'suppressrevision' );
		$this->mToken = $this->mRequest->getVal( 'token' );

		if ( $wgUser->isAllowed( 'undelete' ) && !$wgUser->isBlocked() ) {
			$this->mAllowed = true; // user can restore
			$this->mCanView = true; // user can view content
		} elseif ( $wgUser->isAllowed( 'deletedtext' ) ) {
			$this->mAllowed = false; // user cannot restore
			$this->mCanView = true; // user can view content
		}  else { // user can only view the list of revisions
			$this->mAllowed = false;
			$this->mCanView = false;
			$this->mTimestamp = '';
			$this->mRestore = false;
		}

		if( $this->mRestore || $this->mInvert ) {
			$timestamps = array();
			$this->mFileVersions = array();
			foreach( $_REQUEST as $key => $val ) {
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
		global $wgOut, $wgUser;

		$this->setHeaders();
		if ( !$this->userCanExecute( $wgUser ) ) {
			$this->displayRestrictionError();
			return;
		}
		$this->outputHeader();

		$this->loadRequest();

		if ( $this->mAllowed ) {
			$wgOut->setPagetitle( wfMsg( "undeletepage" ) );
		} else {
			$wgOut->setPagetitle( wfMsg( "viewdeletedpage" ) );
		}

		if( $par != '' ) {
			$this->mTarget = $par;
		}
		if ( $this->mTarget !== '' ) {
			$this->mTargetObj = Title::newFromURL( $this->mTarget );
			$wgUser->getSkin()->setRelevantTitle( $this->mTargetObj );
		} else {
			$this->mTargetObj = null;
		}

		if( is_null( $this->mTargetObj ) ) {
		# Not all users can just browse every deleted page from the list
			if( $wgUser->isAllowed( 'browsearchive' ) ) {
				$this->showSearchForm();

				# List undeletable articles
				if( $this->mSearchPrefix ) {
					$result = PageArchive::listPagesByPrefix( $this->mSearchPrefix );
					$this->showList( $result );
				}
			} else {
				$wgOut->addWikiMsg( 'undelete-header' );
			}
			return;
		}
		if( $this->mTimestamp !== '' ) {
			return $this->showRevision( $this->mTimestamp );
		}
		if( $this->mFile !== null ) {
			$file = new ArchivedFile( $this->mTargetObj, '', $this->mFile );
			// Check if user is allowed to see this file
			if ( !$file->exists() ) {
				$wgOut->addWikiMsg( 'filedelete-nofile', $this->mFile );
				return;
			} else if( !$file->userCan( File::DELETED_FILE ) ) {
				if( $file->isDeleted( File::DELETED_RESTRICTED ) ) {
					$wgOut->permissionRequired( 'suppressrevision' );
				} else {
					$wgOut->permissionRequired( 'deletedtext' );
				}
				return false;
			} elseif ( !$wgUser->matchEditToken( $this->mToken, $this->mFile ) ) {
				$this->showFileConfirmationForm( $this->mFile );
				return false;
			} else {
				return $this->showFile( $this->mFile );
			}
		}
		if( $this->mRestore && $this->mAction == "submit" ) {
			global $wgUploadMaintenance;
			if( $wgUploadMaintenance && $this->mTargetObj && $this->mTargetObj->getNamespace() == NS_FILE ) {
				$wgOut->wrapWikiMsg( "<div class='error'>\n$1\n</div>\n", array( 'filedelete-maintenance' ) );
				return;
			}
			return $this->undelete();
		}
		if( $this->mInvert && $this->mAction == "submit" ) {
			return $this->showHistory( );
		}
		return $this->showHistory();
	}

	function showSearchForm() {
		global $wgOut, $wgScript;
		$wgOut->addWikiMsg( 'undelete-header' );

		$wgOut->addHTML(
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

	// Generic list of deleted pages
	private function showList( $result ) {
		global $wgLang, $wgUser, $wgOut;

		if( $result->numRows() == 0 ) {
			$wgOut->addWikiMsg( 'undelete-no-results' );
			return;
		}

		$wgOut->addWikiMsg( 'undeletepagetext', $wgLang->formatNum( $result->numRows() ) );

		$sk = $wgUser->getSkin();
		$undelete = $this->getTitle();
		$wgOut->addHTML( "<ul>\n" );
		foreach ( $result as $row ) {
			$title = Title::makeTitleSafe( $row->ar_namespace, $row->ar_title );
			$link = $sk->linkKnown(
				$undelete,
				htmlspecialchars( $title->getPrefixedText() ),
				array(),
				array( 'target' => $title->getPrefixedText() )
			);
			$revs = wfMsgExt( 'undeleterevisions',
				array( 'parseinline' ),
				$wgLang->formatNum( $row->count ) );
			$wgOut->addHTML( "<li>{$link} ({$revs})</li>\n" );
		}
		$result->free();
		$wgOut->addHTML( "</ul>\n" );

		return true;
	}

	private function showRevision( $timestamp ) {
		global $wgLang, $wgUser, $wgOut;

		$skin = $wgUser->getSkin();

		if(!preg_match("/[0-9]{14}/",$timestamp)) return 0;

		$archive = new PageArchive( $this->mTargetObj );
		$rev = $archive->getRevision( $timestamp );

		if( !$rev ) {
			$wgOut->addWikiMsg( 'undeleterevision-missing' );
			return;
		}

		if( $rev->isDeleted(Revision::DELETED_TEXT) ) {
			if( !$rev->userCan(Revision::DELETED_TEXT) ) {
				$wgOut->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n", 'rev-deleted-text-permission' );
				return;
			} else {
				$wgOut->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n", 'rev-deleted-text-view' );
				$wgOut->addHTML( '<br />' );
				// and we are allowed to see...
			}
		}

		$wgOut->setPageTitle( wfMsg( 'undeletepage' ) );

		$link = $skin->linkKnown(
			$this->getTitle( $this->mTargetObj->getPrefixedDBkey() ),
			htmlspecialchars( $this->mTargetObj->getPrefixedText() )
		);

		if( $this->mDiff ) {
			$previousRev = $archive->getPreviousRevision( $timestamp );
			if( $previousRev ) {
				$this->showDiff( $previousRev, $rev );
				if( $wgUser->getOption( 'diffonly' ) ) {
					return;
				} else {
					$wgOut->addHTML( '<hr />' );
				}
			} else {
				$wgOut->addWikiMsg( 'undelete-nodiff' );
			}
		}

		// date and time are separate parameters to facilitate localisation.
		// $time is kept for backward compat reasons.
		$time = htmlspecialchars( $wgLang->timeAndDate( $timestamp, true ) );
		$d = htmlspecialchars( $wgLang->date( $timestamp, true ) );
		$t = htmlspecialchars( $wgLang->time( $timestamp, true ) );
		$user = $skin->revUserTools( $rev );

		if( $this->mPreview ) {
			$openDiv = '<div id="mw-undelete-revision" class="mw-warning">';
		} else {
			$openDiv = '<div id="mw-undelete-revision">';
		}

		// Revision delete links
		$canHide = $wgUser->isAllowed( 'deleterevision' );
		if( $this->mDiff ) {
			$revdlink = ''; // diffs already have revision delete links
		} else if( $canHide || ($rev->getVisibility() && $wgUser->isAllowed('deletedhistory')) ) {
			if( !$rev->userCan(Revision::DELETED_RESTRICTED ) ) {
				$revdlink = $skin->revDeleteLinkDisabled( $canHide ); // revision was hidden from sysops
			} else {
				$query = array(
					'type'   => 'archive',
					'target' => $this->mTargetObj->getPrefixedDBkey(),
					'ids'    => $rev->getTimestamp()
				);
				$revdlink = $skin->revDeleteLink( $query,
					$rev->isDeleted( File::DELETED_RESTRICTED ), $canHide );
			}
		} else {
			$revdlink = '';
		}

		$wgOut->addHTML( $openDiv . $revdlink . wfMsgWikiHtml( 'undelete-revision', $link, $time, $user, $d, $t ) . '</div>' );
		wfRunHooks( 'UndeleteShowRevision', array( $this->mTargetObj, $rev ) );

		if( $this->mPreview ) {
			//Hide [edit]s
			$popts = $wgOut->parserOptions();
			$popts->setEditSection( false );
			$wgOut->parserOptions( $popts );
			$wgOut->addWikiTextTitleTidy( $rev->getText( Revision::FOR_THIS_USER ), $this->mTargetObj, true );
		}

		$wgOut->addHTML(
			Xml::element( 'textarea', array(
					'readonly' => 'readonly',
					'cols' => intval( $wgUser->getOption( 'cols' ) ),
					'rows' => intval( $wgUser->getOption( 'rows' ) ) ),
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
				'value' => $wgUser->editToken() ) ) .
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
	 * Build a diff display between this and the previous either deleted
	 * or non-deleted edit.
	 *
	 * @param $previousRev Revision
	 * @param $currentRev Revision
	 * @return String: HTML
	 */
	function showDiff( $previousRev, $currentRev ) {
		global $wgOut;

		$diffEngine = new DifferenceEngine( $previousRev->getTitle() );
		$diffEngine->showDiffStyle();
		$wgOut->addHTML(
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

	private function diffHeader( $rev, $prefix ) {
		global $wgUser, $wgLang;
		$sk = $wgUser->getSkin();
		$isDeleted = !( $rev->getId() && $rev->getTitle() );
		if( $isDeleted ) {
			/// @todo Fixme: $rev->getTitle() is null for deleted revs...?
			$targetPage = $this->getTitle();
			$targetQuery = array(
				'target' => $this->mTargetObj->getPrefixedText(),
				'timestamp' => wfTimestamp( TS_MW, $rev->getTimestamp() )
			);
		} else {
			/// @todo Fixme getId() may return non-zero for deleted revs...
			$targetPage = $rev->getTitle();
			$targetQuery = array( 'oldid' => $rev->getId() );
		}
		// Add show/hide deletion links if available
		$canHide = $wgUser->isAllowed( 'deleterevision' );
		if( $canHide || ($rev->getVisibility() && $wgUser->isAllowed('deletedhistory')) ) {
			$del = ' ';
			if( !$rev->userCan( Revision::DELETED_RESTRICTED ) ) {
				$del .= $sk->revDeleteLinkDisabled( $canHide ); // revision was hidden from sysops
			} else {
				$query = array(
					'type'   => 'archive',
					'target' => $this->mTargetObj->getPrefixedDbkey(),
					'ids'    => $rev->getTimestamp()
				);
				$del .= $sk->revDeleteLink( $query,
					$rev->isDeleted( Revision::DELETED_RESTRICTED ), $canHide );
			}
		} else {
			$del = '';
		}
		return
			'<div id="mw-diff-'.$prefix.'title1"><strong>' .
				$sk->link(
					$targetPage,
					wfMsgHtml(
						'revisionasof',
						htmlspecialchars( $wgLang->timeanddate( $rev->getTimestamp(), true ) ),
						htmlspecialchars( $wgLang->date( $rev->getTimestamp(), true ) ),
						htmlspecialchars( $wgLang->time( $rev->getTimestamp(), true ) )
					),
					array(),
					$targetQuery
				) .
			'</strong></div>' .
			'<div id="mw-diff-'.$prefix.'title2">' .
				$sk->revUserTools( $rev ) . '<br />' .
			'</div>' .
			'<div id="mw-diff-'.$prefix.'title3">' .
				$sk->revComment( $rev ) . $del . '<br />' .
			'</div>';
	}

	/**
	 * Show a form confirming whether a tokenless user really wants to see a file
	 */
	private function showFileConfirmationForm( $key ) {
		global $wgOut, $wgUser, $wgLang;
		$file = new ArchivedFile( $this->mTargetObj, '', $this->mFile );
		$wgOut->addWikiMsg( 'undelete-show-file-confirm',
			$this->mTargetObj->getText(),
			$wgLang->date( $file->getTimestamp() ),
			$wgLang->time( $file->getTimestamp() ) );
		$wgOut->addHTML(
			Xml::openElement( 'form', array(
				'method' => 'POST',
				'action' => $this->getTitle()->getLocalUrl(
					'target=' . urlencode( $this->mTarget ) .
					'&file=' . urlencode( $key ) .
					'&token=' . urlencode( $wgUser->editToken( $key ) ) )
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
		global $wgOut, $wgRequest;
		$wgOut->disable();

		# We mustn't allow the output to be Squid cached, otherwise
		# if an admin previews a deleted image, and it's cached, then
		# a user without appropriate permissions can toddle off and
		# nab the image, and Squid will serve it
		$wgRequest->response()->header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', 0 ) . ' GMT' );
		$wgRequest->response()->header( 'Cache-Control: no-cache, no-store, max-age=0, must-revalidate' );
		$wgRequest->response()->header( 'Pragma: no-cache' );

		global $IP;
		require_once( "$IP/includes/StreamFile.php" );
		$repo = RepoGroup::singleton()->getLocalRepo();
		$path = $repo->getZonePath( 'deleted' ) . '/' . $repo->getDeletedHashPath( $key ) . $key;
		wfStreamFile( $path );
	}

	private function showHistory( ) {
		global $wgUser, $wgOut;

		$sk = $wgUser->getSkin();
		if( $this->mAllowed ) {
			$wgOut->setPagetitle( wfMsg( "undeletepage" ) );
		} else {
			$wgOut->setPagetitle( wfMsg( 'viewdeletedpage' ) );
		}

		$wgOut->wrapWikiMsg(  "<div class='mw-undelete-pagetitle'>\n$1\n</div>\n", array ( 'undeletepagetitle', $this->mTargetObj->getPrefixedText() ) );

		$archive = new PageArchive( $this->mTargetObj );
		/*
		$text = $archive->getLastRevisionText();
		if( is_null( $text ) ) {
			$wgOut->addWikiMsg( "nohistory" );
			return;
		}
		*/
		$wgOut->addHTML( '<div class="mw-undelete-history">' );
		if ( $this->mAllowed ) {
			$wgOut->addWikiMsg( "undeletehistory" );
			$wgOut->addWikiMsg( "undeleterevdel" );
		} else {
			$wgOut->addWikiMsg( "undeletehistorynoadmin" );
		}
		$wgOut->addHTML( '</div>' );

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
			$wgOut->addHTML( $top );
		}

		# Show relevant lines from the deletion log:
		$wgOut->addHTML( Xml::element( 'h2', null, LogPage::logName( 'delete' ) ) . "\n" );
		LogEventsList::showLogExtract( $wgOut, 'delete', $this->mTargetObj->getPrefixedText() );
		# Show relevant lines from the suppression log:
		if( $wgUser->isAllowed( 'suppressionlog' ) ) {
			$wgOut->addHTML( Xml::element( 'h2', null, LogPage::logName( 'suppress' ) ) . "\n" );
			LogEventsList::showLogExtract( $wgOut, 'suppress', $this->mTargetObj->getPrefixedText() );
		}

		if( $this->mAllowed && ( $haveRevisions || $haveFiles ) ) {
			# Format the user-visible controls (comment field, submission button)
			# in a nice little table
			if( $wgUser->isAllowed( 'suppressrevision' ) ) {
				$unsuppressBox =
					"<tr>
						<td>&#160;</td>
						<td class='mw-input'>" .
							Xml::checkLabel( wfMsg('revdelete-unsuppress'), 'wpUnsuppress',
								'mw-undelete-unsuppress', $this->mUnsuppress ).
						"</td>
					</tr>";
			} else {
				$unsuppressBox = "";
			}
			$table =
				Xml::fieldset( wfMsg( 'undelete-fieldset-title' ) ) .
				Xml::openElement( 'table', array( 'id' => 'mw-undelete-table' ) ) .
					"<tr>
						<td colspan='2' class='mw-undelete-extrahelp'>" .
							wfMsgWikiHtml( 'undeleteextrahelp' ) .
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
							Xml::element( 'input', array( 'type' => 'reset', 'value' => wfMsg( 'undeletereset' ), 'id' => 'mw-undelete-reset' ) ) . ' ' .
							Xml::submitButton( wfMsg( 'undeleteinvert' ), array( 'name' => 'invert', 'id' => 'mw-undelete-invert' ) ) .
						"</td>
					</tr>" .
					$unsuppressBox .
				Xml::closeElement( 'table' ) .
				Xml::closeElement( 'fieldset' );

			$wgOut->addHTML( $table );
		}

		$wgOut->addHTML( Xml::element( 'h2', null, wfMsg( 'history' ) ) . "\n" );

		if( $haveRevisions ) {
			# The page's stored (deleted) history:
			$wgOut->addHTML("<ul>");
			$remaining = $revisions->numRows();
			$earliestLiveTime = $this->mTargetObj->getEarliestRevTime();

			foreach ( $revisions as $row ) {
				$remaining--;
				$wgOut->addHTML( $this->formatRevisionRow( $row, $earliestLiveTime, $remaining, $sk ) );
			}
			$revisions->free();
			$wgOut->addHTML("</ul>");
		} else {
			$wgOut->addWikiMsg( "nohistory" );
		}

		if( $haveFiles ) {
			$wgOut->addHTML( Xml::element( 'h2', null, wfMsg( 'filehist' ) ) . "\n" );
			$wgOut->addHTML( "<ul>" );
			foreach ( $files as $row ) {
				$wgOut->addHTML( $this->formatFileRow( $row, $sk ) );
			}
			$files->free();
			$wgOut->addHTML( "</ul>" );
		}

		if ( $this->mAllowed ) {
			# Slip in the hidden controls here
			$misc  = Html::hidden( 'target', $this->mTarget );
			$misc .= Html::hidden( 'wpEditToken', $wgUser->editToken() );
			$misc .= Xml::closeElement( 'form' );
			$wgOut->addHTML( $misc );
		}

		return true;
	}

	private function formatRevisionRow( $row, $earliestLiveTime, $remaining, $sk ) {
		global $wgUser, $wgLang;

		$rev = Revision::newFromArchiveRow( $row,
			array( 'page' => $this->mTargetObj->getArticleId() ) );
		$stxt = '';
		$ts = wfTimestamp( TS_MW, $row->ar_timestamp );
		// Build checkboxen...
		if( $this->mAllowed ) {
			if( $this->mInvert ) {
				if( in_array( $ts, $this->mTargetTimestamp ) ) {
					$checkBox = Xml::check( "ts$ts");
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
				$pageLink = htmlspecialchars( $wgLang->timeanddate( $ts, true ) );
				$last = wfMsgHtml('diff');
			} else if( $remaining > 0 || ($earliestLiveTime && $ts > $earliestLiveTime) ) {
				$pageLink = $this->getPageLink( $rev, $titleObj, $ts, $sk );
				$last = $sk->linkKnown(
					$titleObj,
					wfMsgHtml('diff'),
					array(),
					array(
						'target' => $this->mTargetObj->getPrefixedText(),
						'timestamp' => $ts,
						'diff' => 'prev'
					)
				);
			} else {
				$pageLink = $this->getPageLink( $rev, $titleObj, $ts, $sk );
				$last = wfMsgHtml('diff');
			}
		} else {
			$pageLink = htmlspecialchars( $wgLang->timeanddate( $ts, true ) );
			$last = wfMsgHtml('diff');
		}
		// User links
		$userLink = $sk->revUserTools( $rev );
		// Revision text size
		if( !is_null($size = $row->ar_len) ) {
			$stxt = $sk->formatRevisionSize( $size );
		}
		// Edit summary
		$comment = $sk->revComment( $rev );
		// Revision delete links
		$canHide = $wgUser->isAllowed( 'deleterevision' );
		if( $canHide || ($rev->getVisibility() && $wgUser->isAllowed('deletedhistory')) ) {
			if( !$rev->userCan( Revision::DELETED_RESTRICTED ) ) {
				$revdlink = $sk->revDeleteLinkDisabled( $canHide ); // revision was hidden from sysops
			} else {
				$query = array(
					'type'   => 'archive',
					'target' => $this->mTargetObj->getPrefixedDBkey(),
					'ids'    => $ts
				);
				$revdlink = $sk->revDeleteLink( $query,
					$rev->isDeleted( Revision::DELETED_RESTRICTED ), $canHide );
			}
		} else {
			$revdlink = '';
		}
		return "<li>$checkBox $revdlink ($last) $pageLink . . $userLink $stxt $comment</li>";
	}

	private function formatFileRow( $row, $sk ) {
		global $wgUser, $wgLang;

		$file = ArchivedFile::newFromRow( $row );

		$ts = wfTimestamp( TS_MW, $row->fa_timestamp );
		if( $this->mAllowed && $row->fa_storage_key ) {
			$checkBox = Xml::check( "fileid" . $row->fa_id );
			$key = urlencode( $row->fa_storage_key );
			$pageLink = $this->getFileLink( $file, $this->getTitle(), $ts, $key, $sk );
		} else {
			$checkBox = '';
			$pageLink = $wgLang->timeanddate( $ts, true );
		}
		$userLink = $this->getFileUser( $file, $sk );
		$data =
			wfMsg( 'widthheight',
				$wgLang->formatNum( $row->fa_width ),
				$wgLang->formatNum( $row->fa_height ) ) .
			' (' .
			wfMsg( 'nbytes', $wgLang->formatNum( $row->fa_size ) ) .
			')';
		$data = htmlspecialchars( $data );
		$comment = $this->getFileComment( $file, $sk );
		// Add show/hide deletion links if available
		$canHide = $wgUser->isAllowed( 'deleterevision' );
		if( $canHide || ($file->getVisibility() && $wgUser->isAllowed('deletedhistory')) ) {
			if( !$file->userCan(File::DELETED_RESTRICTED ) ) {
				$revdlink = $sk->revDeleteLinkDisabled( $canHide ); // revision was hidden from sysops
			} else {
				$query = array(
					'type' => 'filearchive',
					'target' => $this->mTargetObj->getPrefixedDBkey(),
					'ids' => $row->fa_id
				);
				$revdlink = $sk->revDeleteLink( $query,
					$file->isDeleted( File::DELETED_RESTRICTED ), $canHide );
			}
		} else {
			$revdlink = '';
		}
		return "<li>$checkBox $revdlink $pageLink . . $userLink $data $comment</li>\n";
	}

	/**
	 * Fetch revision text link if it's available to all users
	 * @return string
	 */
	function getPageLink( $rev, $titleObj, $ts, $sk ) {
		global $wgLang;

		$time = htmlspecialchars( $wgLang->timeanddate( $ts, true ) );

		if( !$rev->userCan(Revision::DELETED_TEXT) ) {
			return '<span class="history-deleted">' . $time . '</span>';
		} else {
			$link = $sk->linkKnown(
				$titleObj,
				$time,
				array(),
				array(
					'target' => $this->mTargetObj->getPrefixedText(),
					'timestamp' => $ts
				)
			);
			if( $rev->isDeleted(Revision::DELETED_TEXT) )
				$link = '<span class="history-deleted">' . $link . '</span>';
			return $link;
		}
	}

	/**
	 * Fetch image view link if it's available to all users
	 *
	 * @return String: HTML fragment
	 */
	function getFileLink( $file, $titleObj, $ts, $key, $sk ) {
		global $wgLang, $wgUser;

		if( !$file->userCan(File::DELETED_FILE) ) {
			return '<span class="history-deleted">' . $wgLang->timeanddate( $ts, true ) . '</span>';
		} else {
			$link = $sk->linkKnown(
				$titleObj,
				$wgLang->timeanddate( $ts, true ),
				array(),
				array(
					'target' => $this->mTargetObj->getPrefixedText(),
					'file' => $key,
					'token' => $wgUser->editToken( $key )
				)
			);
			if( $file->isDeleted(File::DELETED_FILE) )
				$link = '<span class="history-deleted">' . $link . '</span>';
			return $link;
		}
	}

	/**
	 * Fetch file's user id if it's available to this user
	 *
	 * @return String: HTML fragment
	 */
	function getFileUser( $file, $sk ) {
		if( !$file->userCan(File::DELETED_USER) ) {
			return '<span class="history-deleted">' . wfMsgHtml( 'rev-deleted-user' ) . '</span>';
		} else {
			$link = $sk->userLink( $file->getRawUser(), $file->getRawUserText() ) .
				$sk->userToolLinks( $file->getRawUser(), $file->getRawUserText() );
			if( $file->isDeleted(File::DELETED_USER) )
				$link = '<span class="history-deleted">' . $link . '</span>';
			return $link;
		}
	}

	/**
	 * Fetch file upload comment if it's available to this user
	 *
	 * @return String: HTML fragment
	 */
	function getFileComment( $file, $sk ) {
		if( !$file->userCan(File::DELETED_COMMENT) ) {
			return '<span class="history-deleted"><span class="comment">' . wfMsgHtml( 'rev-deleted-comment' ) . '</span></span>';
		} else {
			$link = $sk->commentBlock( $file->getRawDescription() );
			if( $file->isDeleted(File::DELETED_COMMENT) )
				$link = '<span class="history-deleted">' . $link . '</span>';
			return $link;
		}
	}

	function undelete() {
		global $wgOut, $wgUser;
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
		if( !is_null( $this->mTargetObj ) ) {
			$archive = new PageArchive( $this->mTargetObj );
			$ok = $archive->undelete(
				$this->mTargetTimestamp,
				$this->mComment,
				$this->mFileVersions,
				$this->mUnsuppress );

			if( is_array($ok) ) {
				if ( $ok[1] ) // Undeleted file count
					wfRunHooks( 'FileUndeleteComplete', array(
						$this->mTargetObj, $this->mFileVersions,
						$wgUser, $this->mComment) );

				$skin = $wgUser->getSkin();
				$link = $skin->linkKnown( $this->mTargetObj );
				$wgOut->addHTML( wfMsgWikiHtml( 'undeletedpage', $link ) );
			} else {
				$wgOut->showFatalError( wfMsg( "cannotundelete" ) );
				$wgOut->addHTML( '<p>' . wfMsgHtml( "undeleterevdel" ) . '</p>' );
			}

			// Show file deletion warnings and errors
			$status = $archive->getFileStatus();
			if( $status && !$status->isGood() ) {
				$wgOut->addWikiText( $status->getWikiText( 'undelete-error-short', 'undelete-error-long' ) );
			}
		} else {
			$wgOut->showFatalError( wfMsg( "cannotundelete" ) );
		}
		return false;
	}
}
