<?php

/**
 * Special page allowing users with the appropriate permissions to view
 * and restore deleted content
 *
 * @addtogroup Special pages
 */

/**
 *
 */
function wfSpecialUndelete( $par ) {
    global $wgRequest;

	$form = new UndeleteForm( $wgRequest, $par );
	$form->execute();
}

/**
 *
 * @addtogroup SpecialPage
 */
class PageArchive {
	protected $title;

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
					'COUNT(*) AS count',
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
			array( 'ar_minor_edit', 'ar_timestamp', 'ar_user', 'ar_user_text', 'ar_comment' ),
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
	 * @fixme Does this belong in Image for fuller encapsulation?
	 */
	function listFiles() {
		if( $this->title->getNamespace() == NS_IMAGE ) {
			$dbr = wfGetDB( DB_SLAVE );
			$res = $dbr->select( 'filearchive',
				array(
					'fa_id',
					'fa_name',
					'fa_storage_key',
					'fa_size',
					'fa_width',
					'fa_height',
					'fa_description',
					'fa_user',
					'fa_user_text',
					'fa_timestamp' ),
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

	/**
	 * Return a Revision object containing data for the deleted revision.
	 * Note that the result *may* or *may not* have a null page ID.
	 * @param string $timestamp
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
				'ar_text_id' ),
			array( 'ar_namespace' => $this->title->getNamespace(),
			       'ar_title' => $this->title->getDbkey(),
			       'ar_timestamp' => $dbr->timestamp( $timestamp ) ),
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
				'text_id'    => $row->ar_text_id ) );
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
	 *
	 * @param array $timestamps Pass an empty array to restore all revisions, otherwise list the ones to undelete.
	 * @param string $comment
	 * @param array $fileVersions
	 *
	 * @return true on success.
	 */
	function undelete( $timestamps, $comment = '', $fileVersions = array() ) {
		// If both the set of text revisions and file revisions are empty,
		// restore everything. Otherwise, just restore the requested items.
		$restoreAll = empty( $timestamps ) && empty( $fileVersions );
		
		$restoreText = $restoreAll || !empty( $timestamps );
		$restoreFiles = $restoreAll || !empty( $fileVersions );
		
		if( $restoreFiles && $this->title->getNamespace() == NS_IMAGE ) {
			$img = new Image( $this->title );
			$filesRestored = $img->restore( $fileVersions );
		} else {
			$filesRestored = 0;
		}
		
		if( $restoreText ) {
			$textRestored = $this->undeleteRevisions( $timestamps );
		} else {
			$textRestored = 0;
		}
		
		// Touch the log!
		global $wgContLang;
		$log = new LogPage( 'delete' );
		
		if( $textRestored && $filesRestored ) {
			$reason = wfMsgForContent( 'undeletedrevisions-files',
				$wgContLang->formatNum( $textRestored ),
				$wgContLang->formatNum( $filesRestored ) );
		} elseif( $textRestored ) {
			$reason = wfMsgForContent( 'undeletedrevisions',
				$wgContLang->formatNum( $textRestored ) );
		} elseif( $filesRestored ) {
			$reason = wfMsgForContent( 'undeletedfiles',
				$wgContLang->formatNum( $filesRestored ) );
		} else {
			wfDebug( "Undelete: nothing undeleted...\n" );
			return false;
		}
		
		if( trim( $comment ) != '' )
			$reason .= ": {$comment}";
		$log->addEntry( 'restore', $this->title, $reason );
		
		return true;
	}
	
	/**
	 * This is the meaty bit -- restores archived revisions of the given page
	 * to the cur/old tables. If the page currently exists, all revisions will
	 * be stuffed into old, otherwise the most recent will go into cur.
	 *
	 * @param array $timestamps Pass an empty array to restore all revisions, otherwise list the ones to undelete.
	 * @param string $comment
	 * @param array $fileVersions
	 *
	 * @return int number of revisions restored
	 */
	private function undeleteRevisions( $timestamps ) {
		global $wgDBtype;

		$restoreAll = empty( $timestamps );
		
		$dbw = wfGetDB( DB_MASTER );
		$page = $dbw->tableName( 'archive' );

		# Does this page already exist? We'll have to update it...
		$article = new Article( $this->title );
		$options = ( $wgDBtype == 'postgres' )
			? '' // pg doesn't support this?
			: 'FOR UPDATE';
		$page = $dbw->selectRow( 'page',
			array( 'page_id', 'page_latest' ),
			array( 'page_namespace' => $this->title->getNamespace(),
			       'page_title'     => $this->title->getDBkey() ),
			__METHOD__,
			$options );
		if( $page ) {
			# Page already exists. Import the history, and if necessary
			# we'll update the latest revision field in the record.
			$newid             = 0;
			$pageId            = $page->page_id;
			$previousRevId     = $page->page_latest;
		} else {
			# Have to create a new article...
			$newid  = $article->insertOn( $dbw );
			$pageId = $newid;
			$previousRevId = 0;
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
		 * Restore each revision...
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
				'ar_text_id' ),
			/* WHERE */ array(
				'ar_namespace' => $this->title->getNamespace(),
				'ar_title'     => $this->title->getDBkey(),
				$oldones ),
			__METHOD__,
			/* options */ array(
				'ORDER BY' => 'ar_timestamp' )
			);
		if( $dbw->numRows( $result ) < count( $timestamps ) ) {
			wfDebug( __METHOD__.": couldn't find all requested rows\n" );
			return false;
		}
		
		$revision = null;
		$restored = 0;

		while( $row = $dbw->fetchObject( $result ) ) {
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
				) );
			$revision->insertOn( $dbw );
			$restored++;
		}

		if( $revision ) {
			# FIXME: Update latest if newer as well...
			if( $newid ) {
				// Attach the latest revision to the page...
				$article->updateRevisionOn( $dbw, $revision, $previousRevId );
				
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
		} else {
			# Something went terribly wrong!
		}

		# Now that it's safely stored, take it out of the archive
		$dbw->delete( 'archive',
			/* WHERE */ array(
				'ar_namespace' => $this->title->getNamespace(),
				'ar_title' => $this->title->getDBkey(),
				$oldones ),
			__METHOD__ );

		return $restored;
	}

}

/**
 *
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
		
		$posted = $request->wasPosted() &&
			$wgUser->matchEditToken( $request->getVal( 'wpEditToken' ) );
		$this->mRestore = $request->getCheck( 'restore' ) && $posted;
		$this->mPreview = $request->getCheck( 'preview' ) && $posted;
		$this->mComment = $request->getText( 'wpComment' );
		
		if( $par != "" ) {
			$this->mTarget = $par;
		}
		if ( $wgUser->isAllowed( 'delete' ) && !$wgUser->isBlocked() ) {
			$this->mAllowed = true;
		} else {
			$this->mAllowed = false;
			$this->mTimestamp = '';
			$this->mRestore = false;
		}
		if ( $this->mTarget !== "" ) {
			$this->mTargetObj = Title::newFromURL( $this->mTarget );
		} else {
			$this->mTargetObj = NULL;
		}
		if( $this->mRestore ) {
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

	function execute() {
		global $wgOut;
		if ( $this->mAllowed ) {
			$wgOut->setPagetitle( wfMsg( "undeletepage" ) );
		} else {
			$wgOut->setPagetitle( wfMsg( "viewdeletedpage" ) );
		}
		
		if( is_null( $this->mTargetObj ) ) {
			$this->showSearchForm();

			# List undeletable articles
			if( $this->mSearchPrefix ) {
				$result = PageArchive::listPagesByPrefix(
					$this->mSearchPrefix );
				$this->showList( $result );
			}
			return;
		}
		if( $this->mTimestamp !== '' ) {
			return $this->showRevision( $this->mTimestamp );
		}
		if( $this->mFile !== null ) {
			return $this->showFile( $this->mFile );
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

	/* private */ function showList( $result ) {
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

	/* private */ function showRevision( $timestamp ) {
		global $wgLang, $wgUser, $wgOut;
		$self = SpecialPage::getTitleFor( 'Undelete' );
		$skin = $wgUser->getSkin();

		if(!preg_match("/[0-9]{14}/",$timestamp)) return 0;

		$archive = new PageArchive( $this->mTargetObj );
		$rev = $archive->getRevision( $timestamp );
		
		$wgOut->setPageTitle( wfMsg( 'undeletepage' ) );
		$link = $skin->makeKnownLinkObj( $self, htmlspecialchars( $this->mTargetObj->getPrefixedText() ),
					'target=' . $this->mTargetObj->getPrefixedUrl() );
		$wgOut->addHtml( '<p>' . wfMsgHtml( 'undelete-revision', $link,
			htmlspecialchars( $wgLang->timeAndDate( $timestamp ) ) ) . '</p>' ); 
		
		if( !$rev ) {
			$wgOut->addWikiText( wfMsg( 'undeleterevision-missing' ) );
			return;
		}
		
		wfRunHooks( 'UndeleteShowRevision', array( $this->mTargetObj, $rev ) );
		
		if( $this->mPreview ) {
			$wgOut->addHtml( "<hr />\n" );
			$wgOut->addWikiTextTitle( $rev->getText(), $this->mTargetObj, false );
		}

		$wgOut->addHtml(
			wfElement( 'textarea', array(
					'readonly' => true,
					'cols' => intval( $wgUser->getOption( 'cols' ) ),
					'rows' => intval( $wgUser->getOption( 'rows' ) ) ),
				$rev->getText() . "\n" ) .
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
	 * Show a deleted file version requested by the visitor.
	 */
	function showFile( $key ) {
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

	/* private */ function showHistory() {
		global $wgLang, $wgUser, $wgOut;

		$sk = $wgUser->getSkin();
		if ( $this->mAllowed ) {
			$wgOut->setPagetitle( wfMsg( "undeletepage" ) );
		} else {
			$wgOut->setPagetitle( wfMsg( 'viewdeletedpage' ) );
		}

		$archive = new PageArchive( $this->mTargetObj );
		/*
		$text = $archive->getLastRevisionText();
		if( is_null( $text ) ) {
			$wgOut->addWikiText( wfMsg( "nohistory" ) );
			return;
		}
		*/
		if ( $this->mAllowed ) {
			$wgOut->addWikiText( wfMsg( "undeletehistory" ) );
		} else {
			$wgOut->addWikiText( wfMsg( "undeletehistorynoadmin" ) );
		}

		# List all stored revisions
		$revisions = $archive->listRevisions();
		$files = $archive->listFiles();
		
		$haveRevisions = $revisions && $revisions->numRows() > 0;
		$haveFiles = $files && $files->numRows() > 0;
		
		# Batch existence check on user and talk pages
		if( $haveRevisions ) {
			$batch = new LinkBatch();
			while( $row = $revisions->fetchObject() ) {
				$batch->addObj( Title::makeTitleSafe( NS_USER, $row->ar_user_text ) );
				$batch->addObj( Title::makeTitleSafe( NS_USER_TALK, $row->ar_user_text ) );
			}
			$batch->execute();
			$revisions->seek( 0 );
		}
		if( $haveFiles ) {
			$batch = new LinkBatch();
			while( $row = $files->fetchObject() ) {
				$batch->addObj( Title::makeTitleSafe( NS_USER, $row->fa_user_text ) );
				$batch->addObj( Title::makeTitleSafe( NS_USER_TALK, $row->fa_user_text ) );
			}
			$batch->execute();
			$files->seek( 0 );
		}

		if ( $this->mAllowed ) {
			$titleObj = SpecialPage::getTitleFor( "Undelete" );
			$action = $titleObj->getLocalURL( "action=submit" );
			# Start the form here
			$top = wfOpenElement( 'form', array( 'method' => 'post', 'action' => $action, 'id' => 'undelete' ) );
			$wgOut->addHtml( $top );
		}

		# Show relevant lines from the deletion log:
		$wgOut->addHTML( "<h2>" . htmlspecialchars( LogPage::logName( 'delete' ) ) . "</h2>\n" );
		$logViewer = new LogViewer(
			new LogReader(
				new FauxRequest(
					array( 'page' => $this->mTargetObj->getPrefixedText(),
						   'type' => 'delete' ) ) ) );
		$logViewer->showList( $wgOut );
		
		if( $this->mAllowed && ( $haveRevisions || $haveFiles ) ) {
			# Format the user-visible controls (comment field, submission button)
			# in a nice little table
			$table = '<fieldset><table><tr>';
			$table .= '<td colspan="2">' . wfMsgWikiHtml( 'undeleteextrahelp' ) . '</td></tr><tr>';
			$table .= '<td align="right"><strong>' . wfMsgHtml( 'undeletecomment' ) . '</strong></td>';
			$table .= '<td>' . wfInput( 'wpComment', 50, $this->mComment ) . '</td>';
			$table .= '</tr><tr><td>&nbsp;</td><td>';
			$table .= wfSubmitButton( wfMsg( 'undeletebtn' ), array( 'name' => 'restore' ) );
			$table .= wfElement( 'input', array( 'type' => 'reset', 'value' => wfMsg( 'undeletereset' ) ) );
			$table .= '</td></tr></table></fieldset>';
			$wgOut->addHtml( $table );
		}
	
		$wgOut->addHTML( "<h2>" . htmlspecialchars( wfMsg( "history" ) ) . "</h2>\n" );
		
		if( $haveRevisions ) {
			# The page's stored (deleted) history:
			$wgOut->addHTML("<ul>");
			$target = urlencode( $this->mTarget );
			while( $row = $revisions->fetchObject() ) {
				$ts = wfTimestamp( TS_MW, $row->ar_timestamp );
				if ( $this->mAllowed ) {
					$checkBox = wfCheck( "ts$ts" );
					$pageLink = $sk->makeKnownLinkObj( $titleObj,
						$wgLang->timeanddate( $ts, true ),
						"target=$target&timestamp=$ts" );
				} else {
					$checkBox = '';
					$pageLink = $wgLang->timeanddate( $ts, true );
				}
				$userLink = $sk->userLink( $row->ar_user, $row->ar_user_text ) . $sk->userToolLinks( $row->ar_user, $row->ar_user_text );
				$comment = $sk->commentBlock( $row->ar_comment );
				$wgOut->addHTML( "<li>$checkBox $pageLink . . $userLink $comment</li>\n" );
	
			}
			$revisions->free();
			$wgOut->addHTML("</ul>");
		} else {
			$wgOut->addWikiText( wfMsg( "nohistory" ) );
		}

		
		if( $haveFiles ) {
			$wgOut->addHtml( "<h2>" . wfMsgHtml( 'imghistory' ) . "</h2>\n" );
			$wgOut->addHtml( "<ul>" );
			while( $row = $files->fetchObject() ) {
				$ts = wfTimestamp( TS_MW, $row->fa_timestamp );
				if ( $this->mAllowed && $row->fa_storage_key ) {
					$checkBox = wfCheck( "fileid" . $row->fa_id );
					$key = urlencode( $row->fa_storage_key );
					$target = urlencode( $this->mTarget );
					$pageLink = $sk->makeKnownLinkObj( $titleObj,
						$wgLang->timeanddate( $ts, true ),
						"target=$target&file=$key" );
				} else {
					$checkBox = '';
					$pageLink = $wgLang->timeanddate( $ts, true );
				}
 				$userLink = $sk->userLink( $row->fa_user, $row->fa_user_text ) . $sk->userToolLinks( $row->fa_user, $row->fa_user_text );
				$data =
					wfMsgHtml( 'widthheight',
						$wgLang->formatNum( $row->fa_width ),
						$wgLang->formatNum( $row->fa_height ) ) .
					' (' .
					wfMsgHtml( 'nbytes', $wgLang->formatNum( $row->fa_size ) ) .
					')';
				$comment = $sk->commentBlock( $row->fa_description );
				$wgOut->addHTML( "<li>$checkBox $pageLink . . $userLink $data $comment</li>\n" );
			}
			$files->free();
			$wgOut->addHTML( "</ul>" );
		}
		
		if ( $this->mAllowed ) {
			# Slip in the hidden controls here
			$misc  = wfHidden( 'target', $this->mTarget );
			$misc .= wfHidden( 'wpEditToken', $wgUser->editToken() );
			$wgOut->addHtml( $misc . '</form>' );
		}

		return true;
	}

	function undelete() {
		global $wgOut, $wgUser;
		if( !is_null( $this->mTargetObj ) ) {
			$archive = new PageArchive( $this->mTargetObj );
			
			$ok = $archive->undelete(
				$this->mTargetTimestamp,
				$this->mComment,
				$this->mFileVersions );
			
			if( $ok ) {
				$skin = $wgUser->getSkin();
				$link = $skin->makeKnownLinkObj( $this->mTargetObj );
				$wgOut->addHtml( wfMsgWikiHtml( 'undeletedpage', $link ) );
				return true;
			}
		}
		$wgOut->showFatalError( wfMsg( "cannotundelete" ) );
		return false;
	}
}

?>
