<?php

function wfSpecialUndelete( $par )
{
    global $wgRequest;

	$form = new UndeleteForm( $wgRequest, $par );
	$form->execute();
}

class UndeleteForm {
	var $mAction, $mTarget, $mTimestamp, $mRestore, $mTargetObj;

	function UndeleteForm( &$request, $par = "" ) {
		$this->mAction = $request->getText( 'action' );
		$this->mTarget = $request->getText( 'target' );
		$this->mTimestamp = $request->getText( 'timestamp' );
		$this->mRestore = $request->getCheck( 'restore' );
		if( $par != "" ) {
			$this->mTarget = $par;
		}
		if ( $this->mTarget !== "" ) {
			$this->mTargetObj = Title::newFromURL( $this->mTarget );
		} else {
			$this->mTargetObj = NULL;
		}
	}

	function execute() {
		if( !is_null( $this->mTargetObj ) ) {
			$title = $this->mTargetObj->mDbkeyform;
			$namespace = $this->mTargetObj->mNamespace;
			if( $this->mTimestamp !== "" ) {
				return $this->showRevision( $namespace, $title, $this->mTimestamp );
			}
			if( $this->mRestore and $this->mAction == "submit" ) {
				return $this->undelete( $namespace, $title );
			}
			return $this->showHistory( $namespace, $title );
		} else {
			return $this->showList();
		}
	}

	/* private */ function showList() {
		global $wgLang, $wgUser, $wgOut;
		$fname = "UndeleteForm::showList";
		
		# List undeletable articles    
		$dbr =& wfGetDB( DB_SLAVE );
		$archive = $dbr->tableName( 'archive' );

		$sql = "SELECT ar_namespace,ar_title, COUNT(*) AS count FROM $archive " . 
		  "GROUP BY ar_namespace,ar_title ORDER BY ar_namespace,ar_title";

		$res = $dbr->query( $sql, $fname );
		
		$wgOut->setPagetitle( wfMsg( "undeletepage" ) );
		$wgOut->addWikiText( wfMsg( "undeletepagetext" ) );

		$special = $wgLang->getNsText( Namespace::getSpecial() );
		$sk = $wgUser->getSkin();
		$wgOut->addHTML( "<ul>\n" );
		while ($row = $dbr->fetchObject( $res )) {
			$n = ($row->ar_namespace ? 
				($wgLang->getNsText( $row->ar_namespace ) . ":") : "").
				$row->ar_title;

			$wgOut->addHTML( "<li>" .
			  $sk->makeKnownLink( $wgLang->specialPage( "Undelete" ),
			  $n, "target=" . urlencode($n) ) . " " .
			  wfMsg( "undeleterevisions", $row->count ) );
		}
		$wgOut->addHTML( "</ul>\n" );
		
		return 0;
	}
	
	/* private */ function showRevision( $namespace, $title, $timestamp ) {
		global $wgLang, $wgUser, $wgOut;
		$fname = "UndeleteForm::showRevision";

		if(!preg_match("/[0-9]{14}/",$timestamp)) return 0;

		$dbr =& wfGetDB( DB_SLAVE );
		$archive = $dbr->tableName( 'archive' );
		$sql = "SELECT ar_text,ar_flags FROM $archive ". 
		  "WHERE ar_namespace={$namespace} AND ar_title='" .
		  $dbr->strencode( $title ) . "' AND ar_timestamp='" . $dbr->strencode( $timestamp ) ."'";
		$ret = $dbr->query( $sql, $fname );
		$row = $dbr->fetchObject( $ret );
		
		$wgOut->setPagetitle( wfMsg( "undeletepage" ) );
		$wgOut->addWikiText( "(" . wfMsg( "undeleterevision", $wgLang->date($timestamp, true) )
		  . ")\n<hr>\n" . Article::getRevisionText( $row, "ar_" ) );

		return 0;
	}

	/* private */ function showHistory( $namespace, $title ) {
		global $wgLang, $wgUser, $wgOut;
		
		$sk = $wgUser->getSkin();
		$wgOut->setPagetitle( wfMsg( "undeletepage" ) );
		$dbr =& wfGetDB( DB_SLAVE );
		$archive = $dbr->tableName( 'archive' );
		
		# Get text of first revision
		$sql = "SELECT ar_text FROM $archive WHERE ar_namespace={$namespace} AND ar_title='" .
		  $dbr->strencode( $title ) . "' ORDER BY ar_timestamp DESC LIMIT 1";
		$ret = $dbr->query( $sql );

		if( $dbr->numRows( $ret ) == 0 ) {
			$wgOut->addWikiText( wfMsg( "nohistory" ) );
			return 0;
		}
		$row = $dbr->fetchObject( $ret );
		$wgOut->addWikiText( wfMsg( "undeletehistory" ) . "\n<hr>\n" . $row->ar_text );

		# List all stored revisions
		$sql = "SELECT ar_minor_edit,ar_timestamp,ar_user,ar_user_text,ar_comment
		  FROM archive WHERE ar_namespace={$namespace} AND ar_title='" . $dbr->strencode( $title ) .
		  "' ORDER BY ar_timestamp DESC";
		$ret = $dbr->query( $sql );

		$titleObj = Title::makeTitle( NS_SPECIAL, "Undelete" );
		$action = $titleObj->escapeLocalURL( "action=submit" );
		$encTarget = htmlspecialchars( $this->mTarget );
		
		$wgOut->addHTML("<p>
	<form id=\"undelete\" method=\"post\" action=\"{$action}\">
	<input type=hidden name=\"target\" value=\"{$encTarget}\">
	<input type=submit name=\"restore\" value=\"".wfMsg("undeletebtn")."\">
	</form>");

		$log = $dbr->selectField( "cur", "cur_text", 
			array( 'cur_namespace' => NS_PROJECT, 'cur_title' => wfMsg("dellogpage") ) );
		if(preg_match("/^(.*".
			preg_quote( ($namespace ? ($wgLang->getNsText($namespace) . ":") : "")
			. str_replace("_", " ", $title), "/" ).".*)$/m", $log, $m)) {
			$wgOut->addWikiText( $m[1] );
		}
		
		$special = $wgLang->getNsText( Namespace::getSpecial() );
		$wgOut->addHTML("<ul>");
		while( $row = $dbr->fetchObject( $ret ) ) {
			$wgOut->addHTML( "<li>" .
			  $sk->makeKnownLink( $wgLang->specialPage( "Undelete" ),
			  $wgLang->timeanddate( $row->ar_timestamp, true ),
			  "target=" . urlencode($this->mTarget) . "&timestamp={$row->ar_timestamp}" ) . " " .
			  ". . " . htmlspecialchars( $row->ar_user_text )  .
			  " <i>(" . htmlspecialchars($row->ar_comment) . "</i>)\n");

		}
		$wgOut->addHTML("</ul>");
		
		return 0;
	}	

	/* private */ function undelete( $namespace, $title )
	{
		global $wgUser, $wgOut, $wgLang, $wgDeferredUpdateList;
		global  $wgUseSquid, $wgInternalServer, $wgLinkCache;

		$fname = "doUndeleteArticle";

		if ( "" == $title ) {
			$wgOut->fatalError( wfMsg( "cannotundelete" ) );
			return;
		}
		$dbw =& wfGetDB( DB_MASTER );
		extract( $dbw->tableNames( 'cur', 'archive', 'old' ) );
		$t = $dbw->strencode($title);

		# Move article and history from the "archive" table
		$sql = "SELECT COUNT(*) AS count FROM $cur WHERE cur_namespace={$namespace} AND cur_title='{$t}' FOR UPDATE";
		$res = $dbw->query( $sql, $fname );
		$row = $dbw->fetchObject( $res );
		$now = wfTimestampNow();

		if( $row->count == 0) {
			# Have to create new article...
			$sql = "SELECT ar_text,ar_timestamp,ar_flags FROM $archive WHERE ar_namespace={$namespace} AND ar_title='{$t}' " .
				"ORDER BY ar_timestamp DESC LIMIT 1 FOR UPDATE";
			$res = $dbw->query( $sql, $fname );
			$s = $dbw->fetchObject( $res );
			$max = $s->ar_timestamp;
			$redirect = MagicWord::get( MAG_REDIRECT );
			$redir = $redirect->matchStart( $s->ar_text ) ? 1 : 0;
			
			$seqVal = $dbw->addQuotes( $dbw->nextSequenceValue( 'cur_cur_id_seq' ) );

			$sql = "INSERT INTO $cur (cur_id,cur_namespace,cur_title,cur_text," .
			  "cur_comment,cur_user,cur_user_text,cur_timestamp,inverse_timestamp,cur_minor_edit,cur_is_redirect,cur_random,cur_touched)" .
			  "SELECT $seqVal,ar_namespace,ar_title,ar_text,ar_comment," .
			  "ar_user,ar_user_text,ar_timestamp,99999999999999-ar_timestamp,ar_minor_edit,{$redir},RAND(),'{$now}' FROM $archive " .
			  "WHERE ar_namespace={$namespace} AND ar_title='{$t}' AND ar_timestamp={$max}";
			$dbw->query( $sql, $fname );
			$newid = $dbw->insertId();
			$oldones = "AND ar_timestamp<{$max}";
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
		
		$sql = "INSERT INTO $old (old_namespace,old_title,old_text," .
		  "old_comment,old_user,old_user_text,old_timestamp,inverse_timestamp,old_minor_edit," .
		  "old_flags) SELECT ar_namespace,ar_title,ar_text,ar_comment," .
		  "ar_user,ar_user_text,ar_timestamp,99999999999999-ar_timestamp,ar_minor_edit,ar_flags " .
		  "FROM $archive WHERE ar_namespace={$namespace} AND ar_title='{$t}' {$oldones}";
		$dbw->query( $sql, $fname );

		# Finally, clean up the link tables 
		if( $newid ) {
			$wgLinkCache = new LinkCache();
			# Select for update
			$wgLinkCache->forUpdate( true );
			# Create a dummy OutputPage to update the outgoing links
			$dummyOut = new OutputPage();
			# Get the text
			$text = $dbw->selectField( 'cur', 'cur_text', 
				array( 'cur_id' => $newid, 'cur_namespace' => $namespace ), 
				$fname, 'FOR UPDATE' 
			);
			$dummyOut->addWikiText( $text );

			$u = new LinksUpdate( $newid, $this->mTargetObj->getPrefixedDBkey() );
			array_push( $wgDeferredUpdateList, $u );
			
			Article::onArticleCreate( $this->mTargetObj );

			#TODO: SearchUpdate, etc.
		}

		# Now that it's safely stored, take it out of the archive
		$sql = "DELETE FROM $archive WHERE ar_namespace={$namespace} AND " .
		  "ar_title='{$t}'";
		$dbw->query( $sql, $fname );

		
		# Touch the log?
		$log = new LogPage( 'delete' );
		$log->addEntry( 'restore', Title::makeTitle( $namespace, $title ), "" );

		$wgOut->addWikiText( wfMsg( "undeletedtext", $this->mTarget ) );
		return 0;
	}
}
?>
