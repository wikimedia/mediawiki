<?php
/**
 * Special page allowing users with the appropriate permissions to
 * move revisions of a page to a new target (either an existing page or not)
 * 
 * The user selects revisions in the page history (HistoryPage.php),
 * clicks on the submit button and gets this special page.
 * A form is shown (showForm()) where the user has to enter a target page
 * name and confirm the action with a post request & edit token.
 * Then submit() is called, which does some checks and calls moveRevisions().
 * If the target doesn't exist, a new page gets created. rev_page of the
 * selected revisions is updated, after that it is determined whether page_latest
 * of the target page and the source page require an update.
 * 
 * **** NOTE: This feature is EXPERIMENTAL.  ****
 * **** Do not use on any productive system. ****
 * 
 * @file
 * @ingroup SpecialPage
 */

/* TODO In case page_deleted gets introduced some day, use it.
 *      Currently it is possible with RevisionMove to make the latest revision
 *      of a page a RevisionDeleted one. When that happens, the user is presented
 *      an empty page with no error message whatsoever (in case he is not permitted
 *      to view deleted edits).
*/

class SpecialRevisionMove extends UnlistedSpecialPage {

	# common objects
	var $mOldTitle; # Title object.
	var $mNewTitle; # Title object. Desired new title
	var $request; # WebRequest object, $wgRequest by default
	var $skin; # Skin object
	var $user; # User object

	# variables
	var $mIds; # Array of Ids to look at
	var $mRevlist; # RevDel_RevisionList object - borrowed from RevisionDelete
	var $mReason; # User-supplied reason for performing the move operation
	var $mSubmit; # Boolean: Is this a submitted request?
	var $mIsAllowedRevisionMove = false;

	public function __construct( $name = 'RevisionMove' ) {
		parent::__construct( $name );
	}

	/**
	 * @param $par subpage part, standard special page parameter, is ignored here
	 * 
	 * Mostly initializes variables and calls either showForm() or submit()
	 */
	public function execute( $par = '' ) {
		global $wgUser, $wgOut, $wgSkin;

		$this->setHeaders();
		$this->outputHeader();

		$this->mIsAllowedRevisionMove = $wgUser->isAllowed( 'revisionmove' );
		$this->user = $wgUser;
		$this->skin = $wgUser->getSkin();
		if ( !$this->request instanceof WebRequest ) {
			$this->request = $GLOBALS['wgRequest'];
		}

		# Get correct title
		if ( $this->request->getVal( 'action' ) == 'historysubmit' ) {
			$this->mOldTitle = Title::newFromText( $this->request->getVal( 'title' ) );
		} else {
			$this->mOldTitle = Title::newFromText( $this->request->getVal( 'oldTitle' ) );
		}

		if ( !$this->mOldTitle instanceof Title ) {
			$wgOut->showErrorPage( 'revmove-badparam-title', 'revmove-badparam' );
			return;
		}

		$wgOut->setPagetitle( wfMsg( 'revisionmove', $this->mOldTitle->getPrefixedText() ) );
		$oldTitleLink = $this->skin->link( $this->mOldTitle );
		$wgOut->setSubtitle( wfMsg( 'revisionmove-backlink', $oldTitleLink ) );

		$this->mReason = $this->request->getText( 'wpReason' );

		# TODO maybe not needed here? Copied from SpecialRevisiondelete.php.
		#      Keep for now, allow different inputs
		# Handle our many different possible input types for ids
		$ids = $this->request->getVal( 'ids' );
		if ( !is_null( $ids ) ) {
			# Allow CSV, for backwards compatibility, or a single ID for show/hide links
			$this->mIds = explode( ',', $ids );
		} else {
			# Array input
			$this->mIds = array_keys( $this->request->getArray( 'ids', array() ) );
		}
		$this->mIds = array_unique( array_filter( $this->mIds ) );

		if ( is_null ( $this->mIds ) ) {
			$wgOut->showErrorPage( 'revmove-badparam-title', 'revmove-badparam' );
			return;
		}
		$this->mRevlist = new RevDel_RevisionList( $this, $this->mOldTitle, $this->mIds );

		# Decide what to do: Show the form, or submit the changes
		if ( $this->request->wasPosted() ) {
			$this->submit();
		} else {
			$this->showForm();
		}

	}

	/**
	 * Show a list of items that we will operate on and a field for the target name
	 */
	public function showForm() {
		global $wgOut, $wgUser;

		if ( !$this->mIsAllowedRevisionMove ) {
			$permErrors = $this->mOldTitle->getUserPermissionsErrors( 'revisionmove', $this->user );
			$wgOut->showPermissionsErrorPage( $permErrors, 'revisionmove' );
			return false;
		}

		$wgOut->addWikiMsg( 'revmove-explain', $this->mOldTitle->getPrefixedText() );
		$listNotEmpty = $this->listItems();
		if ( !$listNotEmpty ) {
			return; # we're done, we already displayed an error earlier
		}

		$out = Xml::openElement( 'form', array( 'method' => 'post',
				'action' => $this->getTitle()->getLocalUrl( array( 'action' => 'submit' ) ), 
				'id' => 'mw-revmove-form' ) ) .
			Xml::fieldset( wfMsg( 'revmove-legend' ) ) .
			Xml::hidden( 'wpEditToken', $wgUser->editToken() ) .
			Xml::hidden( 'oldTitle', $this->mOldTitle->getPrefixedText() ) .
			'<div>' . Xml::inputLabel( wfMsg( 'revmove-reasonfield' ), 'wpReason', 'revmove-reasonfield', 60 ) . '</div>' .
			Xml::inputLabel( wfMsg( 'revmove-titlefield' ), 'newTitle', 'revmove-titlefield', 20, $this->mOldTitle->getPrefixedText() ) .
			Xml::hidden( 'ids', implode( ',', $this->mIds ) ) . 
			Xml::submitButton( wfMsg( 'revmove-submit' ),
							array( 'name' => 'wpSubmit' ) ) .
			Xml::closeElement( 'fieldset' ) . "\n" .
			Xml::closeElement( 'form' ) . "\n";
		$wgOut->addHTML( $out );
	}

	/**
	 * Show a list of selected revisions and check the input
	 */
	protected function listItems() {
		global $wgOut;

		$wgOut->addHTML( "<ul>" );

		$numRevisions = 0;

		# No revisions specified at all
		if ( $this->mIds == array() ) {
			$wgOut->showErrorPage( 'revmove-norevisions-title', 'revmove-norevisions' );
			return false;
		}

		for ( $this->mRevlist->reset(); $this->mRevlist->current(); $this->mRevlist->next() ) {
			$item = $this->mRevlist->current();
			$numRevisions++;
			$wgOut->addHTML( $item->getHTML() );
		}

		# No valid revisions specified (e.g. only revs belonging to another page)
		if( $numRevisions == 0 ) {
			$wgOut->showErrorPage( 'revmove-norevisions-title', 'revmove-norevisions' );
			return false;
		}
		
		$wgOut->addHTML( "</ul>" );
		return true;
	}

	/**
	 * Submit the posted changes (in $this->request).
	 * 
	 * This function does some checks and then calls moveRevisions(), which does the real work
	 */
	public function submit() {
		global $wgUser, $wgOut;

		# Confirm permissions
		if ( !$this->mIsAllowedRevisionMove ) {
			$permErrors = $this->mOldTitle->getUserPermissionsErrors( 'revisionmove', $this->user );
			$wgOut->showPermissionsErrorPage( $permErrors, 'revisionmove' );
			return false;
		}
		# Confirm Token
		if( !$wgUser->matchEditToken( $this->request->getVal( 'wpEditToken' ) ) ) {
			$wgOut->showErrorPage( 'sessionfailure-title', 'sessionfailure' );
			return false;
		}

		$this->mNewTitle = Title::newFromText( $this->request->getVal( 'newTitle' ) );
		if ( !$this->mNewTitle instanceof Title ) {
			$wgOut->showErrorPage( 'badtitle', 'badtitletext' );
			return false;
		}

		if ( $this->mNewTitle->getPrefixedText() == $this->mOldTitle->getPrefixedText() ) {
			$pagename = array( $this->mOldTitle->getPrefixedText() );
			$wgOut->showErrorPage( 'revmove-nullmove-title', 'revmove-nullmove', $pagename );
			return;
		}

		$this->moveRevisions();
	}

	/**
	 * This function actually move the revision. NEVER call this function, call submit()
	 */
	protected function moveRevisions() {
		global $wgOut;

		$oldArticle = new Article( $this->mOldTitle );
		$newArticle = new Article( $this->mNewTitle );

		$idstring = implode( ", ", $this->mIds );

		# Get DB connection and begin transaction
		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin();

		# Check if the target exists. If not, try creating it
		if ( !$this->mNewTitle->exists() ) {
			$newArticle->insertOn( $dbw );
			$this->createArticle = true;
		} else {
			$this->createArticle = false;
		}

		# This is where the magic happens:
		# Update revision table
		$dbw->update( 'revision',
			array( 'rev_page' => $this->mNewTitle->getArticleID() ),
			array( 
				'rev_id IN (' . $idstring . ')',
				'rev_page' => $this->mOldTitle->getArticleID(),
			),
			__METHOD__
		);
		$modifiedRevsNum = $dbw->affectedRows();

		# Check if we need to update page_latest
		# Get the latest version of the revisions we are moving
		$timestampNewPage = $this->queryLatestTimestamp( 
			$dbw,
			$this->mNewTitle->getArticleID(),
			array( 'rev_id IN (' . $idstring . ')' )
		);

		# Compare the new page's page_latest against db query.
		# If we create a new page, we have to update anyway

		$currentNewPageRev = Revision::newFromId( $this->mNewTitle->getLatestRevID() );
		if ( $this->createArticle || $timestampNewPage > $currentNewPageRev->getTimestamp() ) {
			# we have to set page_latest to $timestampNewPage's revid
			$this->updatePageLatest( 
				$dbw, 
				$this->mNewTitle,
				$newArticle,
				$timestampNewPage, 
				array( 'rev_id IN (' . $idstring . ')' )
			);
		}

		# Update the old page's page_latest field
		$timestampOldPage = $this->queryLatestTimestamp( 
			$dbw,
			$this->mOldTitle->getArticleID()
		);

		# If the timestamp is null that means the page doesn't have
		# any revisions associated and should be deleted. In other words,
		# someone misused revisionmove for the normal move function.
		if ( is_null( $timestampOldPage ) ) {
			$dbw->delete(
				'page',
				array( 'page_id = ' . $this->mOldTitle->getArticleID() ),
				__METHOD__
			);
			$deletedOldPage = true;
		} else {
			# page_latest has to be updated
			$currentOldPageRev = Revision::newFromId( $this->mOldTitle->getLatestRevID() );
			if ( $timestampOldPage < $currentOldPageRev->getTimestamp() ) {
				$this->updatePageLatest( 
					$dbw, 
					$this->mOldTitle,
					$oldArticle,
					$timestampOldPage
				);
			}
			# Purge the old one only if it hasn't been deleted
			$oldArticle->doPurge();
		}

		# All done, commit
		$dbw->commit();

		$this->logMove( $modifiedRevsNum );

		# Purge new article
		$newArticle->doPurge();

		# If noting went wrong (i.e. returned), we are successful
		$this->showSuccess( $modifiedRevsNum );
	}

	/**
	 * Query for the latest timestamp in order to update page_latest and
	 * page_timestamp.
	 * @param &$dbw Database object (Master)
	 * @param $articleId Integer page_id
	 * @param $conds array database conditions
	 * 
	 * @return String timestamp
	 */
	protected function queryLatestTimestamp( &$dbw, $articleId, $conds = array() ) {
		$timestampNewRow = $dbw->selectRow( 
			'revision', 
			'max(rev_timestamp) as maxtime',
			array_merge( array( 'rev_page' => $articleId ), $conds ),
			__METHOD__
		);
		return $timestampNewRow->maxtime;
	}

	/**
	 * Updates page_latest and similar database fields (see Article::updateRevisionOn).
	 * Called two times, for the new and the old page
	 * 
	 * @param &$dbw Database object (Master)
	 * @param $articleTitle Title object of the page
	 * @param $articleObj Article object of the page
	 * @param $timestamp to search for (use queryLatestTimestamp to get the latest)
	 * @param $conds array database conditions
	 * 
	 * @return boolean indicating success
	 */
	protected function updatePageLatest( &$dbw, $articleTitle, &$articleObj, $timestamp, $conds = array() ) {
		# Query to find out the rev_id
		$revisionRow = $dbw->selectRow( 
			'revision', 
			'rev_id',
			array_merge( array(
				'rev_timestamp' => $timestamp,
				'rev_page' => $articleTitle->getArticleID(),
			), $conds ),
			__METHOD__
		);
		# Update page_latest
		$latestRev = Revision::newFromId( $revisionRow->rev_id );
		return $articleObj->updateRevisionOn( $dbw, $latestRev, $articleTitle->getLatestRevID(), null, /* set new page flag */  true );
	}

	/**
	 * Add a log entry for the revision move
	*/
	protected function logMove( $modifiedRevsNum ) {
		$paramArray = array(
			$this->mNewTitle->getPrefixedText(),
			$modifiedRevsNum
		);
		$log = new LogPage( 'move' );
		$log->addEntry( 'move_rev', $this->mOldTitle, $this->mReason, $paramArray, $this->user );

	}

	protected function showSuccess( $modifiedRevsNum ) {
		global $wgOut;

		if ( $this->createArticle ) {
			$wgOut->addWikiMsg( 'revmove-success-created', $modifiedRevsNum, 
				$this->mOldTitle->getPrefixedText(), $this->mNewTitle->getPrefixedText() );
		} else {
			$wgOut->addWikiMsg( 'revmove-success-existing', $modifiedRevsNum, 
				$this->mOldTitle->getPrefixedText(), $this->mNewTitle->getPrefixedText() );
		}
	}
}
