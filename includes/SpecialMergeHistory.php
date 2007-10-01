<?php

/**
 * Special page allowing users with the appropriate permissions to 
 * merge article histories, with some restrictions
 *
 * @addtogroup SpecialPage
 */

/**
 * Constructor
 */
function wfSpecialMergehistory( $par ) {
	global $wgRequest;

	$form = new MergehistoryForm( $wgRequest, $par );
	$form->execute();
}

/**
 * The HTML form for Special:MergeHistory, which allows users with the appropriate
 * permissions to view and restore deleted content.
 * @addtogroup SpecialPage
 */
class MergehistoryForm {
	var $mAction, $mTarget, $mDest, $mTimestamp, $mTargetID, $mDestID, $mComment;
	var $mTargetObj, $mDestObj;

	function MergehistoryForm( $request, $par = "" ) {
		global $wgUser;
		
		$this->mAction = $request->getVal( 'action' );
		$this->mTarget = $request->getVal( 'target' );
		$this->mDest = $request->getVal( 'dest' );
		
		$this->mTargetID = intval( $request->getVal( 'targetID' ) );
		$this->mDestID = intval( $request->getVal( 'destID' ) );
		$this->mTimestamp = $request->getVal( 'mergepoint' );
		$this->mComment = $request->getText( 'wpComment' );
		
		$this->mMerge = $request->wasPosted() && $wgUser->matchEditToken( $request->getVal( 'wpEditToken' ) );
		// target page
		if( $this->mTarget !== "" ) {
			$this->mTargetObj = Title::newFromURL( $this->mTarget );
		} else {
			$this->mTargetObj = NULL;
		}
		# Destination
		if( $this->mDest !== "" ) {
			$this->mDestObj = Title::newFromURL( $this->mDest );
		} else {
			$this->mDestObj = NULL;
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
			$this->message['last'] = wfMsgExt( 'last', array( 'escape') );
		}
	}

	function execute() {
		global $wgOut, $wgUser;
		
		$wgOut->setPagetitle( wfMsgHtml( "mergehistory" ) );
		
		if( $this->mTargetID && $this->mDestID && $this->mAction=="submit" && $this->mMerge ) {
			return $this->merge();
		}
		
		if( is_object($this->mTargetObj) && is_object($this->mDestObj) ) {
			return $this->showHistory();
		}
		
		return $this->showMergeForm();
	}

	function showMergeForm() {
		global $wgOut, $wgScript;
		
		$wgOut->addWikiText( wfMsg( 'mergehistory-header' ) );
		
		$wgOut->addHtml(
			Xml::openElement( 'form', array(
				'method' => 'get',
				'action' => $wgScript ) ) .
			'<fieldset>' .
			Xml::element( 'legend', array(),
				wfMsg( 'mergehistory-box' ) ) .
			Xml::hidden( 'title',
				SpecialPage::getTitleFor( 'Mergehistory' )->getPrefixedDbKey() ) .
			Xml::openElement( 'table' ) .
			"<tr>
				<td>".Xml::Label( wfMsg( 'mergehistory-from' ), 'target' )."</td>
				<td>".Xml::input( 'target', 30, $this->mTarget, array('id'=>'target') )."</td>
			</tr><tr>
				<td>".Xml::Label( wfMsg( 'mergehistory-into' ), 'dest' )."</td>
				<td>".Xml::input( 'dest', 30, $this->mDest, array('id'=>'dest') )."</td>
			</tr><tr><td>" .
			Xml::submitButton( wfMsg( 'mergehistory-go' ) ) .
			"</td></tr>" .
			Xml::closeElement( 'table' ) .
			'</fieldset>' .
			'</form>' );
	}

	private function showHistory() {
		global $wgLang, $wgContLang, $wgUser, $wgOut;

		$this->sk = $wgUser->getSkin();
		
		$wgOut->setPagetitle( wfMsg( "mergehistory" ) );
		
		$this->showMergeForm();
		
		# List all stored revisions
		$revisions = new MergeHistoryPager( $this, array(), $this->mTargetObj, $this->mDestObj );
		$haveRevisions = $revisions && $revisions->getNumRows() > 0;

		$titleObj = SpecialPage::getTitleFor( "Mergehistory" );
		$action = $titleObj->getLocalURL( "action=submit" );
		# Start the form here
		$top = Xml::openElement( 'form', array( 'method' => 'post', 'action' => $action, 'id' => 'merge' ) );
		$wgOut->addHtml( $top );

		if( $haveRevisions ) {
			# Format the user-visible controls (comment field, submission button)
			# in a nice little table
			$align = $wgContLang->isRtl() ? 'left' : 'right';
			$table =
				Xml::openElement( 'fieldset' ) .
				Xml::openElement( 'table' ) .
					"<tr>
						<td colspan='2'>" .
							wfMsgExt( 'mergehistory-merge', array('parseinline'),
								$this->mTargetObj->getPrefixedText(), $this->mDestObj->getPrefixedText() ) .
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
							Xml::submitButton( wfMsg( 'mergehistory-submit' ), array( 'name' => 'merge', 'id' => 'mw-merge-submit' ) ) .
						"</td>
					</tr>" .
				Xml::closeElement( 'table' ) .
				Xml::closeElement( 'fieldset' );

			$wgOut->addHtml( $table );
		}

		$wgOut->addHTML( "<h2 id=\"mergehistory\">" . wfMsgHtml( "mergehistory-list" ) . "</h2>\n" );

		if( $haveRevisions ) {
			$wgOut->addHTML( $revisions->getNavigationBar() );
			$wgOut->addHTML( "<ul>" );
			$wgOut->addHTML( $revisions->getBody() );
			$wgOut->addHTML( "</ul>" );
			$wgOut->addHTML( $revisions->getNavigationBar() );
		} else {
			$wgOut->addWikiText( wfMsg( "mergehistory-empty" ) );
		}

		# Show relevant lines from the deletion log:
		$wgOut->addHTML( "<h2>" . htmlspecialchars( LogPage::logName( 'merge' ) ) . "</h2>\n" );
		$logViewer = new LogViewer(
			new LogReader(
				new FauxRequest(
					array( 'page' => $this->mTargetObj->getPrefixedText(),
						   'type' => 'merge' ) ) ) );
		$logViewer->showList( $wgOut );
		
		# Slip in the hidden controls here
		# When we submit, go by page ID to avoid some nasty but unlikely collisions.
		# Such would happen if a page was renamed after the form loaded, but before submit
		$misc = Xml::hidden( 'targetID', $this->mTargetObj->getArticleID() );
		$misc .= Xml::hidden( 'destID', $this->mDestObj->getArticleID() );
		$misc .= Xml::hidden( 'target', $this->mTarget );
		$misc .= Xml::hidden( 'dest', $this->mDest );
		$misc .= Xml::hidden( 'wpEditToken', $wgUser->editToken() );
		$misc .= Xml::closeElement( 'form' );
		$wgOut->addHtml( $misc );

		return true;
	}
	
	function formatRevisionRow( $row ) {
		global $wgUser, $wgLang;
		
		$rev = new Revision( $row );
		
		$stxt = ''; 
		$last = $this->message['last'];
		
		$ts = wfTimestamp( TS_MW, $row->rev_timestamp );
		$checkBox = wfRadio( "mergepoint", $ts, false );
		
		$pageLink = $this->sk->makeKnownLinkObj( $rev->getTitle(), $wgLang->timeanddate( $ts ), 'oldid=' . $rev->getID() );
		if( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
			$pageLink = '<span class="history-deleted">' . $pageLink . '</span>';
		}
		
		# Last link
		if( !$rev->userCan( Revision::DELETED_TEXT ) )
			$last = $this->message['last'];
		else if( isset($this->prevId[$row->rev_id]) )
			$last = $this->sk->makeKnownLinkObj( $rev->getTitle(), $this->message['last'], 
				"&diff=" . $row->rev_id . "&oldid=" . $this->prevId[$row->rev_id] );
		
		$userLink = $this->sk->userLink( $rev->getUser(), $rev->getUserText() )
			. $this->sk->userToolLinks( $rev->getUser(), $rev->getUserText() );
		
		if(!is_null($size = $row->rev_len)) {
			if($size == 0)
				$stxt = wfMsgHtml('historyempty');
			else
				$stxt = wfMsgHtml('historysize', $wgLang->formatNum( $size ) );
		}
		$comment = $this->sk->revComment( $rev );
		
		return "<li>$checkBox ($last) $pageLink . . $userLink $stxt $comment</li>";
	}

	/**
	 * Fetch revision text link if it's available to all users
	 * @return string
	 */
	function getPageLink( $row, $titleObj, $ts, $target ) {
		global $wgLang;
		
		if( !$this->userCan($row, Revision::DELETED_TEXT) ) {
			return '<span class="history-deleted">' . $wgLang->timeanddate( $ts, true ) . '</span>';
		} else {
			$link = $this->sk->makeKnownLinkObj( $titleObj, $wgLang->timeanddate( $ts, true ), "target=$target&timestamp=$ts" );
			if( $this->isDeleted($row, Revision::DELETED_TEXT) )
				$link = '<span class="history-deleted">' . $link . '</span>';
			return $link;
		}
	}

	/**
	 * Fetch revision's user id if it's available to this user
	 * @return string
	 */
	function getUser( $row ) {	
		if( !$this->userCan($row, Revision::DELETED_USER) ) {
			return '<span class="history-deleted">' . wfMsgHtml( 'rev-deleted-user' ) . '</span>';
		} else {
			$link = $this->sk->userLink( $row->rev_user, $row->rev_user_text ) . $this->sk->userToolLinks( $row->rev_user, $row->rev_user_text );
			if( $this->isDeleted($row, Revision::DELETED_USER) )
				$link = '<span class="history-deleted">' . $link . '</span>';
			return $link;
		}
	}

	/**
	 * Fetch revision comment if it's available to this user
	 * @return string
	 */
	function getComment( $row ) {
		if( !$this->userCan($row, Revision::DELETED_COMMENT) ) {
			return '<span class="history-deleted"><span class="comment">' . wfMsgHtml( 'rev-deleted-comment' ) . '</span></span>';
		} else {
			$link = $this->sk->commentBlock( $row->rev_comment );
			if( $this->isDeleted($row, Revision::DELETED_COMMENT) )
				$link = '<span class="history-deleted">' . $link . '</span>';
			return $link;
		}
	}

	function merge() {
		global $wgOut, $wgUser;
		# Get the titles directly from the IDs, in case the target page params 
		# were spoofed. The queries are done based on the IDs, so it's best to 
		# keep it consistent...
		$targetTitle = Title::newFromID( $this->mTargetID );
		$destTitle = Title::newFromID( $this->mDestID );
		if( is_null($targetTitle) || is_null($destTitle) )
			return false; // validate these
		# Verify that this timestamp is valid
		# Must be older than the destination page
		$dbw = wfGetDB( DB_MASTER );
		$maxtimestamp = $dbw->selectField( 'revision', 'MIN(rev_timestamp)',
			array('rev_page' => $this->mDestID ),
			__METHOD__ );
		# Destination page must exist with revisions
		if( !$maxtimestamp ) {
			$wgOut->addHtml( wfMsg('mergehistory-fail') );
			return false;
		}
		# Leave the latest version no matter what
		$lasttime = $dbw->selectField( array('page','revision'), 
			'rev_timestamp',
			array('page_id' => $this->mTargetID, 'page_latest = rev_id' ),
			__METHOD__ );
		# Take the most restrictive of the twain
		$maxtimestamp = ($lasttime < $maxtimestamp) ? $lasttime : $maxtimestamp;
		if( $this->mTimestamp && $this->mTimestamp >= $maxtimestamp ) {
			$wgOut->addHtml( wfMsg('mergehistory-fail') );
			return false;
		}
		# Update the revisions
		if( $this->mTimestamp )
			$timewhere = "rev_timestamp <= {$this->mTimestamp}";
		else
			$timewhere = '1 = 1';
		
		$dbw->update( 'revision',
			array( 'rev_page' => $this->mDestID ),
			array( 'rev_page' => $this->mTargetID, 
				"rev_timestamp < {$maxtimestamp}",
				$timewhere ),
			__METHOD__ );
		# Check if this did anything
		$count = $dbw->affectedRows();
		if( !$count ) {
			$wgOut->addHtml( wfMsg('mergehistory-fail') );
			return false;
		}
		# Update our logs
		$log = new LogPage( 'merge' );
		$log->addEntry( 'merge', $targetTitle, $this->mComment, 
			array($destTitle->getPrefixedText(),$this->mTimestamp) );
		
		$wgOut->addHtml( wfMsgExt( 'mergehistory-success', array('parseinline'),
			$targetTitle->getPrefixedText(), $destTitle->getPrefixedText(), $count ) );
		
		wfRunHooks( 'ArticleMergeComplete', array( $targetTitle, $destTitle ) );
		
		return true;
	}
}

class MergeHistoryPager extends ReverseChronologicalPager {
	public $mForm, $mConds;

	function __construct( $form, $conds = array(), $title, $title2 ) {
		$this->mForm = $form;
		$this->mConds = $conds;
		$this->title = $title;
		$this->articleID = $title->getArticleID();
		
		$dbw = wfGetDB( DB_SLAVE );
		$maxtimestamp = $dbw->selectField('revision', 'MIN(rev_timestamp)',
			array('rev_page' => $title2->getArticleID() ),
			__METHOD__ );
		$maxtimestamp = $maxtimestamp ? $maxtimestamp : 0;
		$this->maxTimestamp = $maxtimestamp;
		
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
			$batch->addObj( Title::makeTitleSafe( NS_USER, $row->rev_user_text ) );
			$batch->addObj( Title::makeTitleSafe( NS_USER_TALK, $row->rev_user_text ) );
			
			$rev_id = isset($rev_id) ? $rev_id : $row->rev_id;
			if( $rev_id > $row->rev_id )
				$this->mForm->prevId[$rev_id] = $row->rev_id;
			else if( $rev_id < $row->rev_id )
				$this->mForm->prevId[$row->rev_id] = $rev_id;
			
			$rev_id = $row->rev_id;
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
		$conds['rev_page'] = $this->articleID;
		$conds[] = "rev_timestamp < {$this->maxTimestamp}";
		# Skip the latest one, as that could cause problems
		if( $page = $this->title->getLatestRevID() )
			$conds[] = "rev_id != {$page}";
		
		return array(
			'tables' => array('revision'),
			'fields' => array( 'rev_minor_edit', 'rev_timestamp', 'rev_user', 'rev_user_text', 'rev_comment', 
				 'rev_id', 'rev_page', 'rev_text_id', 'rev_len', 'rev_deleted' ),
			'conds' => $conds
		);
	}

	function getIndexField() {
		return 'rev_timestamp';
	}
}
