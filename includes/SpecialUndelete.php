<?php

/**
 * Special page allowing users with the appropriate permissions to view
 * and restore deleted content
 *
 * @addtogroup SpecialPage
 */

/**
 * Constructor
 */
function wfSpecialUndelete( $par ) {
	global $wgRequest;

	$form = new UndeleteForm( $wgRequest, $par );
	$form->execute();
}

/**
 * Used to show archived pages and eventually restore them.
 * @addtogroup SpecialPage
 */
class PageArchive {
	protected $title;
	var $fileStatus;

	function __construct( $title ) {
		if( is_null( $title ) ) {
			throw new MWException( 'Archiver() given a null title.');
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
	 * @return ResultWrapper
	 */
	public static function listPagesByPrefix( $prefix ) {
		$dbr = wfGetDB( DB_SLAVE );
		
		$title = Title::newFromText( $prefix );
		if( $title ) {
			$ns = $title->getNamespace();
			$encPrefix = $dbr->escapeLike( $title->getDbKey() );
		} else {
			// Prolly won't work too good
			// @todo handle bare namespace names cleanly?
			$ns = 0;
			$encPrefix = $dbr->escapeLike( $prefix );
		}
		$conds = array(
			'ar_namespace' => $ns,
			"ar_title LIKE '$encPrefix%'",
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
	 * List the deleted file revisions for this page, if it's a file page.
	 * Returns a result wrapper with various filearchive fields, or null
	 * if not a file page.
	 *
	 * @return ResultWrapper
	 * @todo Does this belong in Image for fuller encapsulation?
	 */
	function listFiles() {
		if( $this->title->getNamespace() == NS_IMAGE ) {
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
				array( 'fa_name' => $this->title->getDbKey() ),
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
	 * @return string
	 * @deprecated Use getRevision() for more flexible information
	 */
	function getRevisionText( $timestamp ) {
		$rev = $this->getRevision( $timestamp );
		return $rev ? $rev->getText() : null;
	}
	
	function getRevisionConds( $timestamp, $id ) {
		if( $id ) {
			$id = intval($id);
			return "ar_rev_id=$id";
		} else if( $timestamp ) {
			return "ar_timestamp=$timestamp";
		} else {
			return 'ar_rev_id=0';
		}
	}

	/**
	 * Return a Revision object containing data for the deleted revision.
	 * Note that the result *may* have a null page ID.
	 * @param string $timestamp or $id
	 * @return Revision
	 */
	function getRevision( $timestamp, $id=null ) {
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
			       'ar_title' => $this->title->getDbkey(),
			       $this->getRevisionConds( $dbr->timestamp($timestamp), $id ) ),
			__METHOD__ );
		if( $row ) {
			return new Revision( array(
				'page'       => $this->title->getArticleId(),
				'id'         => $row->ar_rev_id,
				'text'       => ($row->ar_text_id
					? null
					: Revision::getRevisionText( $row, 'ar_' ) ),
				'comment'    => $row->ar_comment,
				'user'       => $row->ar_user,
				'user_text'  => $row->ar_user_text,
				'timestamp'  => $row->ar_timestamp,
				'minor_edit' => $row->ar_minor_edit,
				'text_id'    => $row->ar_text_id,
				'deleted'    => $row->ar_deleted,
				'len'        => $row->ar_len) );
		} else {
			return null;
		}
	}

	/**
	 * Get the text from an archive row containing ar_text, ar_flags and ar_text_id
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
	 * @return string
	 */
	function getLastRevisionText() {
		$dbr = wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow( 'archive',
			array( 'ar_text', 'ar_flags', 'ar_text_id' ),
			array( 'ar_namespace' => $this->title->getNamespace(),
			       'ar_title' => $this->title->getDBkey() ),
			'PageArchive::getLastRevisionText',
			array( 'ORDER BY' => 'ar_timestamp DESC' ) );
		if( $row ) {
			return $this->getTextFromRow( $row );
		} else {
			return NULL;
		}
	}

	/**
	 * Quick check if any archived revisions are present for the page.
	 * @return bool
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
	 * Use -1 for the one of the timestamps to only restore files or text
	 *
	 * @param string $pagetimestamp, restore all revisions since this time
	 * @param string $comment
	 * @param string $filetimestamp, restore all revision from this time on
	 * @param bool $Unsuppress
	 *
	 * @return true on success.
	 */
	function undelete( $pagetimestamp = 0, $comment = '', $filetimestamp = 0, $Unsuppress = false) {
		// If both the set of text revisions and file revisions are empty,
		// restore everything. Otherwise, just restore the requested items.
		$restoreAll = ($pagetimestamp==0 && $filetimestamp==0);
		
		$restoreText = ($restoreAll || $pagetimestamp );
		$restoreFiles = ($restoreAll || $filetimestamp );
		
		if( $restoreText && $pagetimestamp >= 0 ) {
			$textRestored = $this->undeleteRevisions( $pagetimestamp, $Unsuppress );
 		} else {
			$textRestored = 0;
		}
		
		if( $restoreFiles && $filetimestamp >= 0 && $this->title->getNamespace()==NS_IMAGE ) {
			$img = wfLocalFile( $this->title );
			$this->fileStatus = $img->restore( $filetimestamp, $Unsuppress );
			$filesRestored = $this->fileStatus->successCount;
		} else {
			$filesRestored = 0;
		}

		// Touch the log!
		global $wgContLang;
		$log = new LogPage( 'delete' );
		
		if( $textRestored && $filesRestored ) {
			$reason = wfMsgExt( 'undeletedrevisions-files', array('parsemag'),
				$wgContLang->formatNum( $textRestored ),
				$wgContLang->formatNum( $filesRestored ) );
		} elseif( $textRestored ) {
			$reason = wfMsgExt( 'undeletedrevisions', array('parsemag'),
				$wgContLang->formatNum( $textRestored ) );
		} elseif( $filesRestored ) {
			$reason = wfMsgExt( 'undeletedfiles', array('parsemag'),
				$wgContLang->formatNum( $filesRestored ) );
		} else {
			wfDebug( "Undelete: nothing undeleted...\n" );
			return false;
		}
		
		if( trim( $comment ) != '' )
			$reason .= ": {$comment}";
		$log->addEntry( 'restore', $this->title, $reason, array($pagetimestamp,$filetimestamp) );

		if ( $this->fileStatus && !$this->fileStatus->ok ) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * This is the meaty bit -- restores archived revisions of the given page
	 * to the cur/old tables. If the page currently exists, all revisions will
	 * be stuffed into old, otherwise the most recent will go into cur.
	 *
	 * @param string $timestamps, restore all revisions since this time
	 * @param string $comment
	 * @param array $fileVersions
	 * @param bool $Unsuppress, remove all ar_deleted/fa_deleted restrictions of seletected revs
	 *
	 * @return int number of revisions restored
	 */
	private function undeleteRevisions( $timestamp, $Unsuppress = false ) {
		$restoreAll = ($timestamp==0);
		
		$dbw = wfGetDB( DB_MASTER );
		$makepage = false; // Do we need to make a new page?

		# Does this page already exist? We'll have to update it...
		$article = new Article( $this->title );
		$options = 'FOR UPDATE';
		$page = $dbw->selectRow( 'page',
			array( 'page_id', 'page_latest' ),
			array( 'page_namespace' => $this->title->getNamespace(), 
				'page_title' => $this->title->getDBkey() ),
			__METHOD__,
			$options );
		
		if( $page ) {
			# Page already exists. Import the history, and if necessary
			# we'll update the latest revision field in the record.
			$newid             = 0;
			$pageId            = $page->page_id;
			$previousRevId     = $page->page_latest;
			# Get the time span of this page
			$previousTimestamp = $dbw->selectField( 'revision', 'rev_timestamp',
				array( 'rev_id' => $previousRevId ),
				__METHOD__ );
			
			if( $previousTimestamp === false ) {
				wfDebug( __METHOD__.": existing page refers to a page_latest that does not exist\n" );
				return false;
			}
			# Do not fuck up histories by merging them in annoying, unrevertable ways
			# This page id should match any deleted ones (excepting NULL values)
			# We can allow restoration into redirect pages with no edit history
			$otherpages = $dbw->selectField( 'archive', 'COUNT(*)',
				array( 'ar_namespace' => $this->title->getNamespace(), 
					'ar_title' => $this->title->getDBkey(), 
					'ar_page_id IS NOT NULL', "ar_page_id != $pageId" ),
				__METHOD__,
				array('LIMIT' => 1) );
			if( $otherpages && !$this->title->isValidRestoreOverTarget() ) {
				return false;
			}
			
		} else {
			# Have to create a new article...
			$makepage = true;
			$previousRevId = 0;
			$previousTimestamp = 0;
		}

		$conditions = array( 
			'ar_namespace' => $this->title->getNamespace(), 
			'ar_title' => $this->title->getDBkey() );
		if( $timestamp ) {
			$conditions[] = "ar_timestamp >= {$timestamp}";
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
				'ar_len' ),
			/* WHERE */ 
				$conditions,
			__METHOD__,
			/* options */ array(
				'ORDER BY' => 'ar_timestamp' )
			);
		$ret = $dbw->resultObject( $result );
		
		$rev_count = $dbw->numRows( $result );	
		if( $rev_count ) {
			# We need to seek around as just using DESC in the ORDER BY
			# would leave the revisions inserted in the wrong order
			$first = $ret->fetchObject();
			$ret->seek( $rev_count - 1 );
			$last = $ret->fetchObject();
			// We don't handle well changing the top revision's settings
			if( !$Unsuppress && $last->ar_deleted && $last->ar_timestamp > $previousTimestamp ) {
				wfDebug( __METHOD__.": restoration would result in a deleted top revision\n" );
				return false;
			}
			$ret->seek( 0 );
		}
		
		if( $makepage ) {
			$newid  = $article->insertOn( $dbw );
			$pageId = $newid;
		}

		$revision = null;
		$restored = 0;
		
		while( $row = $ret->fetchObject() ) {
			if( $row->ar_text_id ) {
				// Revision was deleted in 1.5+; text is in
				// the regular text table, use the reference.
				// Specify null here so the so the text is
				// dereferenced for page length info if needed.
				$revText = null;
			} else {
				// Revision was deleted in 1.4 or earlier.
				// Text is squashed into the archive row, and
				// a new text table entry will be created for it.
				$revText = Revision::getRevisionText( $row, 'ar_' );
			}
			$revision = new Revision( array(
				'page'       => $pageId,
				'id'         => $row->ar_rev_id,
				'text'       => $revText,
				'comment'    => $row->ar_comment,
				'user'       => $row->ar_user,
				'user_text'  => $row->ar_user_text,
				'timestamp'  => $row->ar_timestamp,
				'minor_edit' => $row->ar_minor_edit,
				'text_id'    => $row->ar_text_id,
				'deleted' 	 => $Unsuppress ? 0 : $row->ar_deleted,
				'len'        => $row->ar_len
				) );
			$revision->insertOn( $dbw );
			$restored++;
		}
		
		# If there were any revisions restored...
		if( $revision ) {
			// Attach the latest revision to the page...
			$wasnew = $article->updateIfNewerOn( $dbw, $revision, $previousRevId );

			if( $newid || $wasnew ) {
				// Update site stats, link tables, etc
				$article->createUpdates( $revision );
			}
			
			if( $newid ) {
				wfRunHooks( 'ArticleUndelete', array( &$this->title, true ) );
				Article::onArticleCreate( $this->title );
			} else {
				wfRunHooks( 'ArticleUndelete', array( &$this->title, false ) );
				Article::onArticleEdit( $this->title );
			}
		}

		# Now that it's safely stored, take it out of the archive
		$dbw->delete( 'archive',
			/* WHERE */ 
			$conditions,
			__METHOD__ );
		# Update any revision left to reflect the page they belong to.
		# If a page was deleted, and a new one created over it, then deleted,
		# selective restore acts as a way to seperate the two. Nevertheless, we
		# still want the rest to be restorable, in case some mistake was made.
		$dbw->update( 'archive', 
			array( 'ar_page_id' => $newid ),
			array( 'ar_namespace' => $this->title->getNamespace(), 
					'ar_title' => $this->title->getDBkey() ),
			__METHOD__ );

		return $restored;
	}

	function getFileStatus() { return $this->fileStatus; }
}

/**
 * The HTML form for Special:Undelete, which allows users with the appropriate
 * permissions to view and restore deleted content.
 * @addtogroup SpecialPage
 */
class UndeleteForm {
	var $mAction, $mTarget, $mTimestamp, $mRestore, $mTargetObj;
	var $mTargetTimestamp, $mAllowed, $mComment;

	function UndeleteForm( $request, $par = "" ) {
		global $wgUser;
		$this->mAction = $request->getVal( 'action' );
		$this->mTarget = $request->getVal( 'target' );
		$this->mSearchPrefix = $request->getText( 'prefix' );
		$time = $request->getVal( 'timestamp' );
		$this->mTimestamp = $time ? wfTimestamp( TS_MW, $time ) : '';
		$this->mFile = $request->getVal( 'file' );
		$this->mDiff = $request->getVal( 'diff' );
		$this->mOldid = $request->getVal( 'oldid' );
		
		$posted = $request->wasPosted() && $wgUser->matchEditToken( $request->getVal( 'wpEditToken' ) );
		$this->mRestore = $request->getCheck( 'restore' ) && $posted;
		$this->mPreview = $request->getCheck( 'preview' ) && $posted;
		$this->mComment = $request->getText( 'wpComment' );
		$this->mUnsuppress = $request->getVal( 'wpUnsuppress' ) && $wgUser->isAllowed( 'oversight' );
		
		if( $par != "" ) {
			$this->mTarget = $par;
			$_GET['target'] = $par; // hack for Pager
		}
		if( $wgUser->isAllowed( 'delete' ) && !$wgUser->isBlocked() ) {
			$this->mAllowed = true;
		} else {
			$this->mAllowed = false;
			$this->mTimestamp = '';
			$this->mRestore = false;
		}
		if( $this->mTarget !== "" ) {
			$this->mTargetObj = Title::newFromURL( $this->mTarget );
		} else {
			$this->mTargetObj = NULL;
		}
		if( $this->mRestore ) {
			$this->mFileTimestamp = $request->getVal('imgrestorepoint');
			$this->mPageTimestamp = $request->getVal('restorepoint');
		}
		$this->preCacheMessages();
	}
	
	/**
	 * As we use the same small set of messages in various methods and that
	 * they are called often, we call them once and save them in $this->message
	 */
	function preCacheMessages() {
		// Precache various messages
		if( !isset( $this->message ) ) {
			foreach( explode(' ', 'last rev-delundel' ) as $msg ) {
				$this->message[$msg] = wfMsgExt( $msg, array( 'escape') );
			}
		}
	}

	function execute() {
		global $wgOut, $wgUser;
		if( $this->mAllowed ) {
			$wgOut->setPagetitle( wfMsgHtml( "undeletepage" ) );
		} else {
			$wgOut->setPagetitle( wfMsgHtml( "viewdeletedpage" ) );
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
				$wgOut->addWikiText( wfMsgHtml( 'undelete-header' ) );
			}
			return;
		}
		if( $this->mTimestamp !== '' ) {
			return $this->showRevision( $this->mTimestamp );
		}
		
		if( $this->mDiff && $this->mOldid )
			return $this->showDiff( $this->mDiff, $this->mOldid );
		
		if( $this->mFile !== null ) {
			$file = new ArchivedFile( $this->mTargetObj, '', $this->mFile );
			// Check if user is allowed to see this file
			if( !$file->userCan( File::DELETED_FILE ) ) {
				$wgOut->permissionRequired( 'hiderevision' ); 
				return false;
			} else {
				return $this->showFile( $this->mFile );
			}
		}
		
		if( $this->mRestore && $this->mAction == "submit" ) {
			return $this->undelete();
		}
		return $this->showHistory();
	}

	function showSearchForm() {
		global $wgOut, $wgScript;
		$wgOut->addWikiText( wfMsg( 'undelete-header' ) );
		
		$wgOut->addHtml(
			Xml::openElement( 'form', array(
				'method' => 'get',
				'action' => $wgScript ) ) .
			'<fieldset>' .
			Xml::element( 'legend', array(),
				wfMsg( 'undelete-search-box' ) ) .
			Xml::hidden( 'title',
				SpecialPage::getTitleFor( 'Undelete' )->getPrefixedDbKey() ) .
			Xml::inputLabel( wfMsg( 'undelete-search-prefix' ),
				'prefix', 'prefix', 20,
				$this->mSearchPrefix ) .
			Xml::submitButton( wfMsg( 'undelete-search-submit' ) ) .
			'</fieldset>' .
			'</form>' );
	}

	// Generic list of deleted pages
	private function showList( $result ) {
		global $wgLang, $wgContLang, $wgUser, $wgOut;
		
		if( $result->numRows() == 0 ) {
			$wgOut->addWikiText( wfMsg( 'undelete-no-results' ) );
			return;
		}

		$wgOut->addWikiText( wfMsg( "undeletepagetext" ) );

		$sk = $wgUser->getSkin();
		$undelete = SpecialPage::getTitleFor( 'Undelete' );
		$wgOut->addHTML( "<ul>\n" );
		while( $row = $result->fetchObject() ) {
			$title = Title::makeTitleSafe( $row->ar_namespace, $row->ar_title );
			$link = $sk->makeKnownLinkObj( $undelete, htmlspecialchars( $title->getPrefixedText() ), 'target=' . $title->getPrefixedUrl() );
			#$revs = wfMsgHtml( 'undeleterevisions', $wgLang->formatNum( $row->count ) );
			$revs = wfMsgExt( 'undeleterevisions',
				array( 'parseinline' ),
				$wgLang->formatNum( $row->count ) );
			$wgOut->addHtml( "<li>{$link} ({$revs})</li>\n" );
		}
		$result->free();
		$wgOut->addHTML( "</ul>\n" );

		return true;
	}

	private function showRevision( $timestamp ) {
		global $wgLang, $wgUser, $wgOut;
		$self = SpecialPage::getTitleFor( 'Undelete' );
		$skin = $wgUser->getSkin();

		if(!preg_match("/[0-9]{14}/",$timestamp)) return 0;

		$archive = new PageArchive( $this->mTargetObj );
		$rev = $archive->getRevision( $timestamp );
		
		if( !$rev ) {
			$wgOut->addWikiText( wfMsg( 'undeleterevision-missing' ) );
			return;
		}
		
		if( $rev->isDeleted(Revision::DELETED_TEXT) ) {
			if( !$rev->userCan(Revision::DELETED_TEXT) ) {
				$wgOut->addWikiText( wfMsg( 'rev-deleted-text-permission' ) );
				return;
			} else {
				$wgOut->addWikiText( wfMsg( 'rev-deleted-text-view' ) );
				$wgOut->addHTML( '<br/>' );
				// and we are allowed to see...
			}
		}
		
		$wgOut->setPageTitle( wfMsg( 'undeletepage' ) );
		
		$link = $skin->makeKnownLinkObj( $self,
			htmlspecialchars( $this->mTargetObj->getPrefixedText() ),
			'target=' . $this->mTargetObj->getPrefixedUrl()
		);
		$time = htmlspecialchars( $wgLang->timeAndDate( $timestamp ) );
		$user = $skin->userLink( $rev->getUser(), $rev->getUserText() )
			. $skin->userToolLinks( $rev->getUser(), $rev->getUserText() );
			
		$wgOut->addHtml( '<p>' . wfMsgHtml( 'undelete-revision', $link, $time, $user ) . '</p>' );
		
		wfRunHooks( 'UndeleteShowRevision', array( $this->mTargetObj, $rev ) );
		
		if( $this->mPreview ) {
			$wgOut->addHtml( "<hr />\n" );
			$wgOut->addWikiTextTitleTidy( $rev->revText(), $this->mTargetObj, false );
		}

		$wgOut->addHtml(
			wfElement( 'textarea', array(
					'readonly' => 'readonly',
					'cols' => intval( $wgUser->getOption( 'cols' ) ),
					'rows' => intval( $wgUser->getOption( 'rows' ) ) ),
				$rev->revText() . "\n" ) .
			wfOpenElement( 'div' ) .
			wfOpenElement( 'form', array(
				'method' => 'post',
				'action' => $self->getLocalURL( "action=submit" ) ) ) .
			wfElement( 'input', array(
				'type' => 'hidden',
				'name' => 'target',
				'value' => $this->mTargetObj->getPrefixedDbKey() ) ) .
			wfElement( 'input', array(
				'type' => 'hidden',
				'name' => 'timestamp',
				'value' => $timestamp ) ) .
			wfElement( 'input', array(
				'type' => 'hidden',
				'name' => 'wpEditToken',
				'value' => $wgUser->editToken() ) ) .
			wfElement( 'input', array(
				'type' => 'hidden',
				'name' => 'preview',
				'value' => '1' ) ) .
			wfElement( 'input', array(
				'type' => 'submit',
				'value' => wfMsg( 'showpreview' ) ) ) .
			wfCloseElement( 'form' ) .
			wfCloseElement( 'div' ) );
	}

	/**
	 * Show the changes between two deleted revisions
	 */	
	private function showDiff( $newid, $oldid ) {
		global $wgOut, $wgUser, $wgLang;
	
		if( is_null($this->mTargetObj) )
			return;
		$skin = $wgUser->getSkin();
		
		$archive = new PageArchive( $this->mTargetObj );
		$oldRev = $archive->getRevision( null, $oldid );
		$newRev = $archive->getRevision( null, $newid );
		
		if( !$oldRev || !$newRev )
			return;
			
		$oldTitle = $this->mTargetObj->getPrefixedText();
		$wgOut->addHtml( "<center><h3>$oldTitle</h3></center>" );
		
		$oldminor = $newminor = '';
		
		if($oldRev->mMinorEdit == 1) {
			$oldminor = wfElement( 'span', array( 'class' => 'minor' ),
				wfMsg( 'minoreditletter') ) . ' ';
		}

		if($newRev->mMinorEdit == 1) {
			$newminor = wfElement( 'span', array( 'class' => 'minor' ),
			wfMsg( 'minoreditletter') ) . ' ';
		}
		
		$ot = $wgLang->timeanddate( $oldRev->getTimestamp(), true );
		$nt = $wgLang->timeanddate( $newRev->getTimestamp(), true );
		$oldHeader = htmlspecialchars( wfMsg( 'revisionasof', $ot ) ) . "<br />" .
			$skin->revUserTools( $oldRev, true ) . "<br />" .
			$oldminor . $skin->revComment( $oldRev, false ) . "<br />";
		$newHeader = htmlspecialchars( wfMsg( 'revisionasof', $nt ) ) . "<br />" .
			$skin->revUserTools( $newRev, true ) . " <br />" .
			$newminor . $skin->revComment( $newRev, false ) . "<br />";
		
		$otext = $oldRev->revText();
		$ntext = $newRev->revText();
		
		$wgOut->addStyle( 'common/diff.css' );
        $wgOut->addHtml(
            "<div>" .
            "<table border='0' width='98%' cellpadding='0' cellspacing='4' class='diff'>" .
            "<col class='diff-marker' />" .
            "<col class='diff-content' />" .
            "<col class='diff-marker' />" .
            "<col class='diff-content' />" .
            "<tr>" .
                "<td colspan='2' width='50%' align='center' class='diff-otitle'>" . $oldHeader . "</td>" .
                "<td colspan='2' width='50%' align='center' class='diff-ntitle'>" . $newHeader . "</td>" .
            "</tr>" .
            DifferenceEngine::generateDiffBody( $otext, $ntext ) .
            "</table>" .
            "</div>\n" );
			
		return true;
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
		
		$store = FileStore::get( 'deleted' );
		$store->stream( $key );
	}

	private function showHistory() {
		global $wgLang, $wgContLang, $wgUser, $wgOut;

		$this->sk = $wgUser->getSkin();
		if( $this->mAllowed ) {
			$wgOut->setPagetitle( wfMsg( "undeletepage" ) );
		} else {
			$wgOut->setPagetitle( wfMsg( 'viewdeletedpage' ) );
		}
		
		$wgOut->addWikiText( wfMsgHtml( 'undeletepagetitle', $this->mTargetObj->getPrefixedText()) );

		$archive = new PageArchive( $this->mTargetObj );

		if( $this->mAllowed ) {
			$wgOut->addWikiText( '<p>' . wfMsgHtml( "undeletehistory" ) . '</p>' );
			$wgOut->addHtml( '<p>' . wfMsgHtml( "undeleterevdel" ) . '</p>' );
		} else {
			$wgOut->addWikiText( wfMsgHtml( "undeletehistorynoadmin" ) );
		}

		# List all stored revisions
		$revisions = new UndeleteRevisionsPager( $this, array(), $this->mTargetObj );	
		$files = $archive->listFiles();

		$haveRevisions = $revisions && $revisions->getNumRows() > 0;
		$haveFiles = $files && $files->numRows() > 0;

		# Batch existence check on user and talk pages
		if( $haveFiles ) {
			$batch = new LinkBatch();
			while( $row = $files->fetchObject() ) {
				$batch->addObj( Title::makeTitleSafe( NS_USER, $row->fa_user_text ) );
				$batch->addObj( Title::makeTitleSafe( NS_USER_TALK, $row->fa_user_text ) );
			}
			$batch->execute();
			$files->seek( 0 );
		}

		if( $this->mAllowed ) {
			$titleObj = SpecialPage::getTitleFor( "Undelete" );
			$action = $titleObj->getLocalURL( "action=submit" );
			# Start the form here
			$top = Xml::openElement( 'form', array( 'method' => 'post', 'action' => $action, 'id' => 'undelete' ) );
			$wgOut->addHtml( $top );
		}

		if( $this->mAllowed && ( $haveRevisions || $haveFiles ) ) {
			# Format the user-visible controls (comment field, submission button)
			# in a nice little table
			$align = $wgContLang->isRtl() ? 'left' : 'right';
			$table =
				Xml::openElement( 'fieldset' ) .
				Xml::openElement( 'table' ) .
					"<tr>
						<td colspan='2'>" .
							wfMsgWikiHtml( 'undeleteextrahelp' ) .
						"</td>
					</tr>
					<tr>
						<td align='$align'>" .
							Xml::label( wfMsg( 'undeletecomment' ), 'wpComment' ) .
						"</td>
						<td>" .
							Xml::input( 'wpComment', 50, $this->mComment ) .
						"</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>" .
							Xml::submitButton( wfMsg( 'undeletebtn' ), array( 'name' => 'restore', 'id' => 'mw-undelete-submit' ) ) .
							Xml::element( 'input', array( 'type' => 'reset', 'value' => wfMsg( 'undeletereset' ), 'id' => 'mw-undelete-reset' ) ) .
							Xml::openElement( 'p' ) .
							Xml::check( 'wpUnsuppress', $this->mUnsuppress, array('id' => 'mw-undelete-unsupress') ) . ' ' .
							Xml::label( wfMsgHtml('revdelete-unsuppress'), 'mw-undelete-unsupress' ) .
							Xml::closeElement( 'p' ) .
						"</td>
					</tr>" .
				Xml::closeElement( 'table' ) .
				Xml::closeElement( 'fieldset' );

			$wgOut->addHtml( $table );
		}

		$wgOut->addHTML( "<h2 id=\"pagehistory\">" . wfMsgHtml( "history" ) . "</h2>\n" );

		if( $haveRevisions ) {
			$wgOut->addHTML( '<p>' . wfMsgHtml( "restorepoint" ) . '</p>' );
			$wgOut->addHTML( $revisions->getNavigationBar() );
			$wgOut->addHTML( "<ul>" );
			$wgOut->addHTML( "<li>" . wfRadio( "restorepoint", -1, false ) . " " . wfMsgHtml('restorenone') . "</li>" );
			$wgOut->addHTML( $revisions->getBody() );
			$wgOut->addHTML( "</ul>" );
			$wgOut->addHTML( $revisions->getNavigationBar() );
		} else {
			$wgOut->addWikiText( wfMsg( "nohistory" ) );
		}
		if( $haveFiles ) {
			$wgOut->addHtml( "<h2 id=\"filehistory\">" . wfMsgHtml( 'filehist' ) . "</h2>\n" );
			$wgOut->addHTML( wfMsgHtml( "restorepoint" ) );
			$wgOut->addHtml( "<ul>" );
			$wgOut->addHTML( "<li>" . wfRadio( "imgrestorepoint", -1, false ) . " " . wfMsgHtml('restorenone') . "</li>" );
			while( $row = $files->fetchObject() ) {
				$file = ArchivedFile::newFromRow( $row );
			
				$ts = wfTimestamp( TS_MW, $row->fa_timestamp );
				if( $this->mAllowed && $row->fa_storage_key ) {
					$checkBox = wfRadio( "imgrestorepoint", $ts, false );
					$key = urlencode( $row->fa_storage_key );
					$target = urlencode( $this->mTarget );
					$pageLink = $this->getFileLink( $file, $titleObj, $ts, $target, $key );
				} else {
					$checkBox = '';
					$pageLink = $wgLang->timeanddate( $ts, true );
				}
 				$userLink = $this->getFileUser( $file );
				$data =
					wfMsgHtml( 'widthheight',
						$wgLang->formatNum( $row->fa_width ),
						$wgLang->formatNum( $row->fa_height ) ) .
					' (' .
					wfMsgHtml( 'nbytes', $wgLang->formatNum( $row->fa_size ) ) .
					')';
				$comment = $this->getFileComment( $file );
				$rd='';
				if( $wgUser->isAllowed( 'deleterevision' ) ) {
					$revdel = SpecialPage::getTitleFor( 'Revisiondelete' );
					if( !$file->userCan(File::DELETED_RESTRICTED ) ) {
					// If revision was hidden from sysops
						$del = $this->message['rev-delundel'];
					} else {
						$del = $this->sk->makeKnownLinkObj( $revdel,
							$this->message['rev-delundel'],
							'target=' . urlencode( $this->mTarget ) .
							'&fileid=' . urlencode( $row->fa_id ) );
						// Bolden oversighted content
						if( $file->isDeleted( File::DELETED_RESTRICTED ) )
							$del = "<strong>$del</strong>";
					}
					$rd = "<tt>(<small>$del</small>)</tt>";
				}
				$wgOut->addHTML( "<li>$checkBox $rd $pageLink . . $userLink $data $comment</li>\n" );
			}
			$files->free();
			$wgOut->addHTML( "</ul>" );
		}

		# Show relevant lines from the deletion log:
		$wgOut->addHTML( "<h2>" . htmlspecialchars( LogPage::logName( 'delete' ) ) . "</h2>\n" );
		$logViewer = new LogViewer(
			new LogReader(
				new FauxRequest(
					array( 'page' => $this->mTargetObj->getPrefixedText(),
						   'type' => 'delete' ) ) ) );
		$logViewer->showList( $wgOut );
		
		if( $this->mAllowed ) {
			# Slip in the hidden controls here
			$misc  = Xml::hidden( 'target', $this->mTarget );
			$misc .= Xml::hidden( 'wpEditToken', $wgUser->editToken() );
			$misc .= Xml::closeElement( 'form' );
			$wgOut->addHtml( $misc );
		}

		return true;
	}
	
	function formatRevisionRow( $row ) {
		global $wgUser, $wgLang;
		
		$rev = new Revision( array(
				'page'       => $this->mTargetObj->getArticleId(),
				'id'         => $row->ar_rev_id,
				'comment'    => $row->ar_comment,
				'user'       => $row->ar_user,
				'user_text'  => $row->ar_user_text,
				'timestamp'  => $row->ar_timestamp,
				'minor_edit' => $row->ar_minor_edit,
				'text_id'    => $row->ar_text_id,
				'deleted'    => $row->ar_deleted,
				'len'        => $row->ar_len) );
		
		$stxt = ''; 
		$last = $this->message['last'];
		
		if( $this->mAllowed ) {
			$ts = wfTimestamp( TS_MW, $row->ar_timestamp );
			$checkBox = wfRadio( "restorepoint", $ts, false );
			$titleObj = SpecialPage::getTitleFor( "Undelete" );
			$pageLink = $this->getPageLink( $rev, $titleObj, $ts, $this->mTarget );
			# Last link
			if( !$rev->userCan( Revision::DELETED_TEXT ) )
				$last = $this->message['last'];
			else if( isset($this->prevId[$row->ar_rev_id]) )
				$last = $this->sk->makeKnownLinkObj( $titleObj, $this->message['last'], "target=" . $this->mTarget .
				"&diff=" . $row->ar_rev_id . "&oldid=" . $this->prevId[$row->ar_rev_id] );
		} else {
			$checkBox = '';
			$pageLink = $wgLang->timeanddate( $ts, true );
		}
		$userLink = $this->sk->revUserTools( $rev );
		
		if(!is_null($size = $row->ar_len)) {
			if($size == 0)
				$stxt = wfMsgHtml('historyempty');
			else
				$stxt = wfMsgHtml('historysize', $wgLang->formatNum( $size ) );
		}
		$comment = $this->sk->revComment( $rev );
		$revd='';
		if( $wgUser->isAllowed( 'deleterevision' ) ) {
			$revdel = SpecialPage::getTitleFor( 'Revisiondelete' );
			if( !$rev->userCan( Revision::DELETED_RESTRICTED ) ) {
			// If revision was hidden from sysops
				$del = $this->message['rev-delundel'];			
			} else {
				$del = $this->sk->makeKnownLinkObj( $revdel,
					$this->message['rev-delundel'],
					'target=' . urlencode( $this->mTarget ) .
					'&artimestamp=' . urlencode( $row->ar_timestamp ) );
				// Bolden oversighted content
				if( $rev->isDeleted( Revision::DELETED_RESTRICTED ) )
					$del = "<strong>$del</strong>";
			}
			$revd = "<tt>(<small>$del</small>)</tt>";
		}
		
		return "<li>$checkBox $revd ($last) $pageLink . . $userLink $stxt $comment</li>";
	}

	/**
	 * Fetch revision text link if it's available to all users
	 * @return string
	 */
	function getPageLink( $rev, $titleObj, $ts, $target ) {
		global $wgLang;
		
		if( !$rev->userCan(Revision::DELETED_TEXT) ) {
			return '<span class="history-deleted">' . $wgLang->timeanddate( $ts, true ) . '</span>';
		} else {
			$link = $this->sk->makeKnownLinkObj( $titleObj, $wgLang->timeanddate( $ts, true ), "target=$target&timestamp=$ts" );
			if( $rev->isDeleted(Revision::DELETED_TEXT) )
				$link = '<span class="history-deleted">' . $link . '</span>';
			return $link;
		}
	}
	
	/**
	 * Fetch image view link if it's available to all users
	 * @return string
	 */
	function getFileLink( $file, $titleObj, $ts, $target, $key ) {
		global $wgLang;

		if( !$file->userCan(File::DELETED_FILE) ) {
			return '<span class="history-deleted">' . $wgLang->timeanddate( $ts, true ) . '</span>';
		} else {
			$link = $this->sk->makeKnownLinkObj( $titleObj, $wgLang->timeanddate( $ts, true ), "target=$target&file=$key" );
			if( $file->isDeleted(File::DELETED_FILE) )
				$link = '<span class="history-deleted">' . $link . '</span>';
			return $link;
		}
	}

	/**
	 * Fetch file's user id if it's available to this user
	 * @return string
	 */
	function getFileUser( $file ) {	
		if( !$file->userCan(File::DELETED_USER) ) {
			return '<span class="history-deleted">' . wfMsgHtml( 'rev-deleted-user' ) . '</span>';
		} else {
			$link = $this->sk->userLink( $file->user, $file->userText ) . 
				$this->sk->userToolLinks( $file->user, $file->userText );
			if( $file->isDeleted(File::DELETED_USER) )
				$link = '<span class="history-deleted">' . $link . '</span>';
			return $link;
		}
	}

	/**
	 * Fetch file upload comment if it's available to this user
	 * @return string
	 */
	function getFileComment( $file ) {
		if( !$file->userCan(File::DELETED_COMMENT) ) {
			return '<span class="history-deleted"><span class="comment">' . wfMsgHtml( 'rev-deleted-comment' ) . '</span></span>';
		} else {
			$link = $this->sk->commentBlock( $file->description );
			if( $file->isDeleted(File::DELETED_COMMENT) )
				$link = '<span class="history-deleted">' . $link . '</span>';
			return $link;
		}
	}

	function undelete() {
		global $wgOut, $wgUser;
		if( !is_null( $this->mTargetObj ) ) {
			$archive = new PageArchive( $this->mTargetObj );
			
			$ok = $archive->undelete(
				$this->mPageTimestamp,
				$this->mComment,
				$this->mFileTimestamp,
				$this->mUnsuppress );
			if( $ok ) {
				$skin = $wgUser->getSkin();
				$link = $skin->makeKnownLinkObj( $this->mTargetObj, $this->mTargetObj->getPrefixedText(), 'redirect=no' );
				$wgOut->addHtml( wfMsgWikiHtml( 'undeletedpage', $link ) );
			} else {
				$wgOut->showFatalError( wfMsg( "cannotundelete" ) );
				$wgOut->addHtml( '<p>' . wfMsgHtml( "undeleterevdel" ) . '</p>' );
			}

			// Show file deletion warnings and errors
			$status = $archive->getFileStatus();
			if ( $status && !$status->isGood() ) {
				$wgOut->addWikiText( $status->getWikiText( 'undelete-error-short', 'undelete-error-long' ) );
			}
		} else {
			$wgOut->showFatalError( wfMsg( "cannotundelete" ) );
		}
		return false;
	}
}

class UndeleteRevisionsPager extends ReverseChronologicalPager {
	public $mForm, $mConds;

	function __construct( $form, $conds = array(), $title ) {
		$this->mForm = $form;
		$this->mConds = $conds;
		$this->title = $title;
		parent::__construct();
	}
	
	function getStartBody() {
		wfProfileIn( __METHOD__ );
		# Do a link batch query
		$this->mResult->seek( 0 );
		$batch = new LinkBatch();
		# Give some pointers to make (last) links
		$this->mForm->prevId = array();
		while( $row = $this->mResult->fetchObject() ) {
			$batch->addObj( Title::makeTitleSafe( NS_USER, $row->ar_user_text ) );
			$batch->addObj( Title::makeTitleSafe( NS_USER_TALK, $row->ar_user_text ) );
			
			$rev_id = isset($rev_id) ? $rev_id : $row->ar_rev_id;
			if( $rev_id > $row->ar_rev_id )
				$this->mForm->prevId[$rev_id] = $row->ar_rev_id;
			else if( $rev_id < $row->ar_rev_id )
				$this->mForm->prevId[$row->ar_rev_id] = $rev_id;
			
			$rev_id = $row->ar_rev_id;
		}
		
		$batch->execute();
		$this->mResult->seek( 0 );

		wfProfileOut( __METHOD__ );
		return '';
	}
	
	function formatRow( $row ) {
		$block = new Block;
		return $this->mForm->formatRevisionRow( $row );
	}

	function getQueryInfo() {
		$conds = $this->mConds;
		$conds['ar_namespace'] = $this->title->getNamespace();
		$conds['ar_title'] = $this->title->getDBkey();
		return array(
			'tables' => array('archive'),
			'fields' => array( 'ar_minor_edit', 'ar_timestamp', 'ar_user', 'ar_user_text', 'ar_comment', 
				'ar_rev_id', 'ar_text_id', 'ar_len', 'ar_deleted' ),
			'conds' => $conds
		);
	}

	function getIndexField() {
		return 'ar_timestamp';
	}
}
