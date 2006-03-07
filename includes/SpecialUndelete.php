<?php
/**
 * @todo document
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/** */
require_once( 'Revision.php' );

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
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class PageArchive {
	var $title;

	function PageArchive( &$title ) {
		if( is_null( $title ) ) {
			wfDebugDieBacktrace( 'Archiver() given a null title.');
		}
		$this->title =& $title;
	}

	/**
	 * List all deleted pages recorded in the archive table. Returns result
	 * wrapper with (ar_namespace, ar_title, count) fields, ordered by page
	 * namespace/title. Can be called staticaly.
	 *
	 * @return ResultWrapper
	 */
	/* static */ function listAllPages() {
		$dbr =& wfGetDB( DB_SLAVE );
		$archive = $dbr->tableName( 'archive' );

		$sql = "SELECT ar_namespace,ar_title, COUNT(*) AS count FROM $archive " .
		  "GROUP BY ar_namespace,ar_title ORDER BY ar_namespace,ar_title";

		return $dbr->resultObject( $dbr->query( $sql, 'PageArchive::listAllPages' ) );
	}

	/**
	 * List the revisions of the given page. Returns result wrapper with
	 * (ar_minor_edit, ar_timestamp, ar_user, ar_user_text, ar_comment) fields.
	 *
	 * @return ResultWrapper
	 */
	function listRevisions() {
		$dbr =& wfGetDB( DB_SLAVE );
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
	 * Fetch (and decompress if necessary) the stored text for the deleted
	 * revision of the page with the given timestamp.
	 *
	 * @return string
	 */
	function getRevisionText( $timestamp ) {
		$fname = 'PageArchive::getRevisionText';
		$dbr =& wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow( 'archive',
			array( 'ar_text', 'ar_flags', 'ar_text_id' ),
			array( 'ar_namespace' => $this->title->getNamespace(),
			       'ar_title' => $this->title->getDbkey(),
			       'ar_timestamp' => $dbr->timestamp( $timestamp ) ),
			$fname );
		return $this->getTextFromRow( $row );
	}

	/**
	 * Get the text from an archive row containing ar_text, ar_flags and ar_text_id
	 */
	function getTextFromRow( $row ) {
		$fname = 'PageArchive::getTextFromRow';

		if( is_null( $row->ar_text_id ) ) {
			// An old row from MediaWiki 1.4 or previous.
			// Text is embedded in this row in classic compression format.
			return Revision::getRevisionText( $row, "ar_" );
		} else {
			// New-style: keyed to the text storage backend.
			$dbr =& wfGetDB( DB_SLAVE );
			$text = $dbr->selectRow( 'text',
				array( 'old_text', 'old_flags' ),
				array( 'old_id' => $row->ar_text_id ),
				$fname );
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
		$dbr =& wfGetDB( DB_SLAVE );
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
		$dbr =& wfGetDB( DB_SLAVE );
		$n = $dbr->selectField( 'archive', 'COUNT(ar_title)',
			array( 'ar_namespace' => $this->title->getNamespace(),
			       'ar_title' => $this->title->getDBkey() ) );
		return ($n > 0);
	}

	/**
	 * This is the meaty bit -- restores archived revisions of the given page
	 * to the cur/old tables. If the page currently exists, all revisions will
	 * be stuffed into old, otherwise the most recent will go into cur.
	 * The deletion log will be updated with an undeletion notice.
	 *
	 * Returns true on success.
	 *
	 * @param array $timestamps Pass an empty array to restore all revisions, otherwise list the ones to undelete.
	 * @return bool
	 */
	function undelete( $timestamps ) {
		global $wgParser, $wgDBtype;

		$fname = "doUndeleteArticle";
		$restoreAll = empty( $timestamps );
		$restoreRevisions = count( $timestamps );

		$dbw =& wfGetDB( DB_MASTER );
		extract( $dbw->tableNames( 'page', 'archive' ) );

		# Does this page already exist? We'll have to update it...
		$article = new Article( $this->title );
		$options = ( $wgDBtype == 'PostgreSQL' )
			? '' // pg doesn't support this?
			: 'FOR UPDATE';
		$page = $dbw->selectRow( 'page',
			array( 'page_id', 'page_latest' ),
			array( 'page_namespace' => $this->title->getNamespace(),
			       'page_title'     => $this->title->getDBkey() ),
			$fname,
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
			$fname,
			/* options */ array(
				'ORDER BY' => 'ar_timestamp' )
			);
		$revision = null;
		$newRevId = $previousRevId;

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
			$newRevId = $revision->insertOn( $dbw );
		}

		if( $revision ) {
			# FIXME: Update latest if newer as well...
			if( $newid ) {
				# FIXME: update article count if changed...
				$article->updateRevisionOn( $dbw, $revision, $previousRevId );

				# Finally, clean up the link tables
				$options = new ParserOptions;
				$parserOutput = $wgParser->parse( $revision->getText(), $this->title, $options,
					true, true, $newRevId );
				$u = new LinksUpdate( $this->title, $parserOutput );
				$u->doUpdate();

				#TODO: SearchUpdate, etc.
			}

			if( $newid ) {
				Article::onArticleCreate( $this->title );
			} else {
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
			$fname );

		# Touch the log!
		$log = new LogPage( 'delete' );
		if( $restoreAll ) {
			$reason = '';
		} else {
			$reason = wfMsgForContent( 'undeletedrevisions', $restoreRevisions );
		}
		$log->addEntry( 'restore', $this->title, $reason );

		return true;
	}
}

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class UndeleteForm {
	var $mAction, $mTarget, $mTimestamp, $mRestore, $mTargetObj;
	var $mTargetTimestamp, $mAllowed;

	function UndeleteForm( &$request, $par = "" ) {
		global $wgUser;
		$this->mAction = $request->getText( 'action' );
		$this->mTarget = $request->getText( 'target' );
		$this->mTimestamp = $request->getText( 'timestamp' );
		
		$posted = $request->wasPosted() &&
			$wgUser->matchEditToken( $request->getVal( 'wpEditToken' ) );
		$this->mRestore = $request->getCheck( 'restore' ) && $posted;
		$this->mPreview = $request->getCheck( 'preview' ) && $posted;
		
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
			foreach( $_REQUEST as $key => $val ) {
				if( preg_match( '/^ts(\d{14})$/', $key, $matches ) ) {
					array_push( $timestamps, $matches[1] );
				}
			}
			rsort( $timestamps );
			$this->mTargetTimestamp = $timestamps;
		}
	}

	function execute() {

		if( is_null( $this->mTargetObj ) ) {
			return $this->showList();
		}
		if( $this->mTimestamp !== '' ) {
			return $this->showRevision( $this->mTimestamp );
		}
		if( $this->mRestore && $this->mAction == "submit" ) {
			return $this->undelete();
		}
		return $this->showHistory();
	}

	/* private */ function showList() {
		global $wgLang, $wgContLang, $wgUser, $wgOut;
		$fname = "UndeleteForm::showList";

		# List undeletable articles
		$result = PageArchive::listAllPages();

		if ( $this->mAllowed ) {
			$wgOut->setPagetitle( wfMsg( "undeletepage" ) );
		} else {
			$wgOut->setPagetitle( wfMsg( "viewdeletedpage" ) );
		}
		$wgOut->addWikiText( wfMsg( "undeletepagetext" ) );

		$sk = $wgUser->getSkin();
		$undelete =& Title::makeTitle( NS_SPECIAL, 'Undelete' );
		$wgOut->addHTML( "<ul>\n" );
		while( $row = $result->fetchObject() ) {
			$n = ($row->ar_namespace ?
				($wgContLang->getNsText( $row->ar_namespace ) . ":") : "").
				$row->ar_title;
			$link = $sk->makeKnownLinkObj( $undelete,
				htmlspecialchars( $n ), "target=" . urlencode( $n ) );
			$revisions = htmlspecialchars( wfMsg( "undeleterevisions",
				$wgLang->formatNum( $row->count ) ) );
			$wgOut->addHTML( "<li>$link $revisions</li>\n" );
		}
		$result->free();
		$wgOut->addHTML( "</ul>\n" );

		return true;
	}

	/* private */ function showRevision( $timestamp ) {
		global $wgLang, $wgUser, $wgOut;
		$fname = "UndeleteForm::showRevision";

		if(!preg_match("/[0-9]{14}/",$timestamp)) return 0;

		$archive =& new PageArchive( $this->mTargetObj );
		$text = $archive->getRevisionText( $timestamp );

		$wgOut->setPagetitle( wfMsg( "undeletepage" ) );
		$wgOut->addWikiText( "(" . wfMsg( "undeleterevision",
			$wgLang->date( $timestamp ) ) . ")\n" );
		
		if( $this->mPreview ) {
			$wgOut->addHtml( "<hr />\n" );
			$wgOut->addWikiText( $text );
		}
		
		$self = Title::makeTitle( NS_SPECIAL, "Undelete" );
		
		$wgOut->addHtml(
			wfElement( 'textarea', array(
					'readonly' => true,
					'cols' => intval( $wgUser->getOption( 'cols' ) ),
					'rows' => intval( $wgUser->getOption( 'rows' ) ) ),
				$text . "\n" ) .
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

	/* private */ function showHistory() {
		global $wgLang, $wgUser, $wgOut;

		$sk = $wgUser->getSkin();
		if ( $this->mAllowed ) {
			$wgOut->setPagetitle( wfMsg( "undeletepage" ) );
		} else {
			$wgOut->setPagetitle( wfMsg( 'viewdeletedpage' ) );
		}

		$archive = new PageArchive( $this->mTargetObj );
		$text = $archive->getLastRevisionText();
		if( is_null( $text ) ) {
			$wgOut->addWikiText( wfMsg( "nohistory" ) );
			return;
		}
		if ( $this->mAllowed ) {
			$wgOut->addWikiText( wfMsg( "undeletehistory" ) );
		} else {
			$wgOut->addWikiText( wfMsg( "undeletehistorynoadmin" ) );
		}

		# List all stored revisions
		$revisions = $archive->listRevisions();

		if ( $this->mAllowed ) {
			$titleObj = Title::makeTitle( NS_SPECIAL, "Undelete" );
			$action = $titleObj->escapeLocalURL( "action=submit" );
			$encTarget = htmlspecialchars( $this->mTarget );
			$button = htmlspecialchars( wfMsg("undeletebtn") );
			$token = htmlspecialchars( $wgUser->editToken() );

			$wgOut->addHTML("
				<form id=\"undelete\" method=\"post\" action=\"{$action}\">
				<input type=\"hidden\" name=\"target\" value=\"{$encTarget}\" />
				<input type=\"submit\" name=\"restore\" value=\"{$button}\" />
				<input type='hidden' name='wpEditToken' value=\"{$token}\" />
				");
		}

		# Show relevant lines from the deletion log:
		$wgOut->addHTML( "<h2>" . htmlspecialchars( LogPage::logName( 'delete' ) ) . "</h2>\n" );
		require_once( 'SpecialLog.php' );
		$logViewer =& new LogViewer(
			new LogReader(
				new FauxRequest(
					array( 'page' => $this->mTargetObj->getPrefixedText(),
					       'type' => 'delete' ) ) ) );
		$logViewer->showList( $wgOut );

		# The page's stored (deleted) history:
		$wgOut->addHTML( "<h2>" . htmlspecialchars( wfMsg( "history" ) ) . "</h2>\n" );
		$wgOut->addHTML("<ul>");
		$target = urlencode( $this->mTarget );
		while( $row = $revisions->fetchObject() ) {
			$ts = wfTimestamp( TS_MW, $row->ar_timestamp );
			if ( $this->mAllowed ) {
				$checkBox = "<input type=\"checkbox\" name=\"ts$ts\" value=\"1\" />";
				$pageLink = $sk->makeKnownLinkObj( $titleObj,
					$wgLang->timeanddate( $ts, true ),
					"target=$target&timestamp=$ts" );
			} else {
				$checkBox = '';
				$pageLink = $wgLang->timeanddate( $ts, true );
			}
			$userLink = htmlspecialchars( $row->ar_user_text );
			if( $row->ar_user ) {
				$userLink = $sk->makeKnownLinkObj(
					Title::makeTitle( NS_USER, $row->ar_user_text ),
					$userLink );
			} else {
				$userLink = $sk->makeKnownLinkObj(
					Title::makeTitle( NS_SPECIAL, 'Contributions' ),
					$userLink, 'target=' . $row->ar_user_text );
			}
			$comment = $sk->commentBlock( $row->ar_comment );
			$wgOut->addHTML( "<li>$checkBox $pageLink . . $userLink $comment</li>\n" );

		}
		$revisions->free();
		$wgOut->addHTML("</ul>");
		if ( $this->mAllowed ) {
			$wgOut->addHTML( "\n</form>" );
		}

		return true;
	}

	function undelete() {
		global $wgOut;
		if( !is_null( $this->mTargetObj ) ) {
			$archive = new PageArchive( $this->mTargetObj );
			if( $archive->undelete( $this->mTargetTimestamp ) ) {
				$wgOut->addWikiText( wfMsg( "undeletedtext", $this->mTarget ) );

				if (NS_IMAGE == $this->mTargetObj->getNamespace()) {
					/* refresh image metadata cache */
					new Image( $this->mTargetObj );
				}

				return true;
			}
		}
		$wgOut->fatalError( wfMsg( "cannotundelete" ) );
		return false;
	}
}

?>
