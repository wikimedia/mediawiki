<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
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
	
	/* static */ function &listAllPages() {
		$dbr =& wfGetDB( DB_SLAVE );
		$archive = $dbr->tableName( 'archive' );

		$sql = "SELECT ar_namespace,ar_title, COUNT(*) AS count FROM $archive " . 
		  "GROUP BY ar_namespace,ar_title ORDER BY ar_namespace,ar_title";

		return $dbr->resultObject( $dbr->query( $sql, 'PageArchive::listAllPages' ) );
	}
	
	function &listRevisions() {
		$dbr =& wfGetDB( DB_SLAVE );
		$sql = "SELECT ar_minor_edit,ar_timestamp,ar_user,ar_user_text,ar_comment
		  FROM archive WHERE ar_namespace=" . $this->title->getNamespace() .
		  " AND ar_title='" . $dbr->strencode( $this->title->getDBkey() ) .
		  "' ORDER BY ar_timestamp DESC";
		return $dbr->resultObject( $dbr->query( $sql ) );
	}
	
	function getRevisionText( $timestamp ) {
		$dbr =& wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow( 'archive',
			array( 'ar_text', 'ar_flags' ),
			array( 'ar_namespace' => $this->title->getNamespace(),
			       'ar_title' => $this->title->getDbkey(),
			       'ar_timestamp' => $timestamp ) );
		return Article::getRevisionText( $row, "ar_" );
	}
	
	function getLastRevisionText() {
		$dbr =& wfGetDB( DB_SLAVE );
		$archive = $dbr->tableName( 'archive' );
		
		# Get text of first revision
		$sql = "SELECT ar_text,ar_flags FROM $archive WHERE ar_namespace=" . $this->title->getNamespace() . " AND ar_title='" .
		  $dbr->strencode( $this->title->getDBkey() ) . "' ORDER BY ar_timestamp DESC LIMIT 1";
		$ret = $dbr->query( $sql );
		$row = $dbr->fetchObject( $ret );
		$dbr->freeResult( $ret );
		if( $row ) {
			return Article::getRevisionText( $row, "ar_" );
		} else {
			return NULL;
		}
	}
	
	function isDeleted() {
		$dbr =& wfGetDB( DB_SLAVE );
		$n = $dbr->selectField( 'archive', 'COUNT(ar_title)',
			array( 'ar_namespace' => $this->title->getNamespace(),
			       'ar_title' => $this->title->getDBkey() ) );
		return ($n > 0);
	}
	
	function undelete( $timestamps ) {
		global $wgUser, $wgOut, $wgLang, $wgDeferredUpdateList;
		global $wgUseSquid, $wgInternalServer, $wgLinkCache;

		$fname = "doUndeleteArticle";
		$restoreAll = empty( $timestamps );
		$restoreRevisions = count( $timestamps );

		$dbw =& wfGetDB( DB_MASTER );
		extract( $dbw->tableNames( 'cur', 'archive', 'old' ) );
		$namespace = $this->title->getNamespace();
		$ttl = $this->title->getDBkey();
		$t = $dbw->strencode( $ttl );

		# Move article and history from the "archive" table
		$sql = "SELECT COUNT(*) AS count FROM $cur WHERE cur_namespace={$namespace} AND cur_title='{$t}' FOR UPDATE";
		$res = $dbw->query( $sql, $fname );
		$row = $dbw->fetchObject( $res );
		$now = wfTimestampNow();

		if( $row->count == 0) {
			# Have to create new article...
			$sql = "SELECT ar_text,ar_comment,ar_user,ar_user_text,ar_timestamp,ar_minor_edit,ar_flags FROM $archive WHERE ar_namespace={$namespace} AND ar_title='{$t}' ";
			if( !$restoreAll ) {
				$max = $dbw->addQuotes( $dbw->timestamp( array_shift( $timestamps ) ) );
				$sql .= "AND ar_timestamp={$max} ";
			}
			$sql .= "ORDER BY ar_timestamp DESC LIMIT 1 FOR UPDATE";
			$res = $dbw->query( $sql, $fname );
			$s = $dbw->fetchObject( $res );
			if( $restoreAll ) {
				$max = $s->ar_timestamp;
			}
			$text = Article::getRevisionText( $s, "ar_" );
			
			$redirect = MagicWord::get( MAG_REDIRECT );
			$redir = $redirect->matchStart( $text ) ? 1 : 0;
			
			$rand = number_format( mt_rand() / mt_getrandmax(), 12, '.', '' );
			$dbw->insertArray( 'cur', array(
				'cur_id' => $dbw->nextSequenceValue( 'cur_cur_id_seq' ),
				'cur_namespace' => $namespace,
				'cur_title' => $ttl,
				'cur_text' => $text,
				'cur_comment' => $s->ar_comment,
				'cur_user' => $s->ar_user,
				'cur_timestamp' => $s->ar_timestamp,
				'cur_minor_edit' => $s->ar_minor_edit,
				'cur_user_text' => $s->ar_user_text,
				'cur_is_redirect' => $redir,
				'cur_random' => $rand,
				'cur_touched' => $dbw->timestamp( $now ),
				'inverse_timestamp' => wfInvertTimestamp( wfTimestamp( TS_MW, $s->ar_timestamp ) ),
			), $fname );
			
			$newid = $dbw->insertId();
			if( $restoreAll ) {
				$oldones = "AND ar_timestamp<{$max}";
			}
		} else {
			# If already exists, put history entirely into old table
			$oldones = "";
			$newid = 0;
			
			# But to make the history list show up right, we need to touch it.
			$sql = "UPDATE $cur SET cur_touched='{$now}' WHERE cur_namespace={$namespace} AND cur_title='{$t}'";
			$dbw->query( $sql, $fname );
			
			# FIXME: Sometimes restored entries will be _newer_ than the current version.
			# We should merge.
		}
		
		if( !$restoreAll ) {
			$oldts = array();
			foreach( $timestamps as $ts ) {
				array_push( $oldts, $dbw->addQuotes( $dbw->timestamp( $ts ) ) );
			}
			$oldts = join( ",", $oldts );
			$oldones = "AND ar_timestamp IN ( {$oldts} )";
		}
		$sql = "INSERT INTO $old (old_namespace,old_title,old_text," .
		  "old_comment,old_user,old_user_text,old_timestamp,inverse_timestamp,old_minor_edit," .
		  "old_flags) SELECT ar_namespace,ar_title,ar_text,ar_comment," .
		  "ar_user,ar_user_text,ar_timestamp,99999999999999-ar_timestamp,ar_minor_edit,ar_flags " .
		  "FROM $archive WHERE ar_namespace={$namespace} AND ar_title='{$t}' {$oldones}";
		if( $restoreAll || !empty( $oldts ) ) {
			$dbw->query( $sql, $fname );
		}

		# Finally, clean up the link tables 
		if( $newid ) {
			$wgLinkCache = new LinkCache();
			# Select for update
			$wgLinkCache->forUpdate( true );
			# Create a dummy OutputPage to update the outgoing links
			$dummyOut = new OutputPage();
			$dummyOut->addWikiText( $text );

			$u = new LinksUpdate( $newid, $this->title->getPrefixedDBkey() );
			array_push( $wgDeferredUpdateList, $u );
			
			Article::onArticleCreate( $this->title );

			#TODO: SearchUpdate, etc.
		}

		# Now that it's safely stored, take it out of the archive
		$sql = "DELETE FROM $archive WHERE ar_namespace={$namespace} AND " .
		  "ar_title='{$t}'";
		if( !$restoreAll ) {
			$sql .= " AND ar_timestamp IN ( {$oldts}";
			if( $newid ) {
				if( !empty( $oldts ) ) $sql .= ",";
				$sql .= $max;
			}
			$sql .= ")";
		}
		$dbw->query( $sql, $fname );

		
		# Touch the log?
		$log = new LogPage( 'delete' );
		if( $restoreAll ) {
			$reason = "";
		} else {
			$reason = wfMsg( 'undeletedrevisions', $restoreRevisions );
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
	var $mTargetTimestamp;

	function UndeleteForm( &$request, $par = "" ) {
		$this->mAction = $request->getText( 'action' );
		$this->mTarget = $request->getText( 'target' );
		$this->mTimestamp = $request->getText( 'timestamp' );
		$this->mRestore = $request->getCheck( 'restore' ) && $request->wasPosted();
		if( $par != "" ) {
			$this->mTarget = $par;
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
		if( $this->mTimestamp !== "" ) {
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
		
		$wgOut->setPagetitle( wfMsg( "undeletepage" ) );
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
			$wgLang->date( $timestamp ) ) . ")\n<hr />\n" . $text );
	}

	/* private */ function showHistory() {
		global $wgLang, $wgUser, $wgOut;
		
		$sk = $wgUser->getSkin();
		$wgOut->setPagetitle( wfMsg( "undeletepage" ) );

		$archive = new PageArchive( $this->mTargetObj );
		$text = $archive->getLastRevisionText();
		if( is_null( $text ) ) {
			$wgOut->addWikiText( wfMsg( "nohistory" ) );
			return;
		}
		$wgOut->addWikiText( wfMsg( "undeletehistory" ) . "\n----\n" . $text );

		# List all stored revisions
		$revisions = $archive->listRevisions();
		
		$titleObj = Title::makeTitle( NS_SPECIAL, "Undelete" );
		$action = $titleObj->escapeLocalURL( "action=submit" );
		$encTarget = htmlspecialchars( $this->mTarget );
		$button = htmlspecialchars( wfMsg("undeletebtn") );
		
		$wgOut->addHTML("
	<form id=\"undelete\" method=\"post\" action=\"{$action}\">
	<input type=\"hidden\" name=\"target\" value=\"{$encTarget}\" />
	<input type=\"submit\" name=\"restore\" value=\"{$button}\" />
	");

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
			$checkBox = "<input type=\"checkbox\" name=\"ts{$row->ar_timestamp}\" value=\"1\" />";
			$pageLink = $sk->makeKnownLinkObj( $titleObj,
				$wgLang->timeanddate( $row->ar_timestamp, true ),
				"target=$target&timestamp={$row->ar_timestamp}" );
			$userLink = htmlspecialchars( $row->ar_user_text );
			if( $row->ar_user ) {
				$userLink = $sk->makeKnownLinkObj(
					Title::makeTitle( NS_USER, $row->ar_user_text ),
					$userLink );
			}
			$comment = $sk->formatComment( $row->ar_comment );
			$wgOut->addHTML( "<li>$checkBox $pageLink . . $userLink (<i>$comment</i>)</li>\n" );

		}
		$revisions->free();
		$wgOut->addHTML("</ul>\n</form>");
		
		return true;
	}

	function undelete() {
		global $wgOut;
		if( !is_null( $this->mTargetObj ) ) {
			$archive = new PageArchive( $this->mTargetObj );
			if( $archive->undelete( $this->mTargetTimestamp ) ) {
				$wgOut->addWikiText( wfMsg( "undeletedtext", $this->mTarget ) );
				return true;
			}
		}
		$wgOut->fatalError( wfMsg( "cannotundelete" ) );
		return false;
	}
}

?>
