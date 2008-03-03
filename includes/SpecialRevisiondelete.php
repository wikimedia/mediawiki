<?php

/**
 * Not quite ready for production use yet; need to fix up the restricted mode,
 * and provide for preservation across delete/undelete of the page.
 *
 * To try this out, set up extra permissions something like:
 * $wgGroupPermissions['sysop']['deleterevision'] = true;
 * $wgGroupPermissions['bureaucrat']['hiderevision'] = true;
 */

function wfSpecialRevisiondelete( $par = null ) {
	global $wgOut, $wgRequest;
	
	$target = $wgRequest->getVal( 'target' );
	$oldid = $wgRequest->getIntArray( 'oldid' );
	
	$page = Title::newFromUrl( $target );
	
	if( is_null( $page ) ) {
		$wgOut->showErrorPage( 'notargettitle', 'notargettext' );
		return;
	}
	
	if( is_null( $oldid ) ) {
		$wgOut->showErrorPage( 'revdelete-nooldid-title', 'revdelete-nooldid-text' );
		return;
	}
	
	$form = new RevisionDeleteForm( $wgRequest );
	if( $wgRequest->wasPosted() ) {
		$form->submit( $wgRequest );
	} else {
		$form->show( $wgRequest );
	}
}

class RevisionDeleteForm {
	/**
	 * @param Title $page
	 * @param int $oldid
	 */
	function __construct( $request ) {
		global $wgUser;
		
		$target = $request->getVal( 'target' );
		$this->page = Title::newFromUrl( $target );
		
		$this->revisions = $request->getIntArray( 'oldid', array() );
		
		$this->skin = $wgUser->getSkin();
		$this->checks = array(
			array( 'revdelete-hide-text', 'wpHideText', Revision::DELETED_TEXT ),
			array( 'revdelete-hide-comment', 'wpHideComment', Revision::DELETED_COMMENT ),
			array( 'revdelete-hide-user', 'wpHideUser', Revision::DELETED_USER ),
			array( 'revdelete-hide-restricted', 'wpHideRestricted', Revision::DELETED_RESTRICTED ) );
	}
	
	/**
	 * @param WebRequest $request
	 */
	function show( $request ) {
		global $wgOut, $wgUser;

		$wgOut->addWikiText( wfMsg( 'revdelete-selected', $this->page->getPrefixedText() ) );
		
		$wgOut->addHtml( "<ul>" );
		foreach( $this->revisions as $revid ) {
			$rev = Revision::newFromTitle( $this->page, $revid );
			if( !isset( $rev ) ) {
				$wgOut->showErrorPage( 'revdelete-nooldid-title', 'revdelete-nooldid-text' );
				return;
			}
			$wgOut->addHtml( $this->historyLine( $rev ) );
			$bitfields[] = $rev->mDeleted; // FIXME
		}
		$wgOut->addHtml( "</ul>" );
	
		$wgOut->addWikiText( wfMsg( 'revdelete-text' ) );
		
		$items = array(
			wfInputLabel( wfMsg( 'revdelete-log' ), 'wpReason', 'wpReason', 60 ),
			wfSubmitButton( wfMsg( 'revdelete-submit' ) ) );
		$hidden = array(
			wfHidden( 'wpEditToken', $wgUser->editToken() ),
			wfHidden( 'target', $this->page->getPrefixedText() ) );
		foreach( $this->revisions as $revid ) {
			$hidden[] = wfHidden( 'oldid[]', $revid );
		}
		
		$special = SpecialPage::getTitleFor( 'Revisiondelete' );
		$wgOut->addHtml( wfElement( 'form', array(
			'method' => 'post',
			'action' => $special->getLocalUrl( 'action=submit' ) ),
			null ) );
		
		$wgOut->addHtml( '<fieldset><legend>' . wfMsgHtml( 'revdelete-legend' ) . '</legend>' );
		foreach( $this->checks as $item ) {
			list( $message, $name, $field ) = $item;
			$wgOut->addHtml( '<div>' .
				wfCheckLabel( wfMsg( $message), $name, $name, $rev->isDeleted( $field ) ) .
				'</div>' );
		}
		$wgOut->addHtml( '</fieldset>' );
		foreach( $items as $item ) {
			$wgOut->addHtml( '<p>' . $item . '</p>' );
		}
		foreach( $hidden as $item ) {
			$wgOut->addHtml( $item );
		}
		
		$wgOut->addHtml( '</form>' );
	}
	
	/**
	 * @param Revision $rev
	 * @returns string
	 */
	function historyLine( $rev ) {
		global $wgContLang;
		$date = $wgContLang->timeanddate( $rev->getTimestamp() );
		return
			"<li>" .
			$this->skin->makeLinkObj( $this->page, $date, 'oldid=' . $rev->getId() ) .
			" " .
			$this->skin->revUserLink( $rev ) .
			" " .
			$this->skin->revComment( $rev ) .
			"</li>";
	}
	
	/**
	 * @param WebRequest $request
	 */
	function submit( $request ) {
		$bitfield = $this->extractBitfield( $request );
		$comment = $request->getText( 'wpReason' );
		if( $this->save( $bitfield, $comment ) ) {
			return $this->success( $request );
		} else {
			return $this->show( $request );
		}
	}
	
	function success( $request ) {
		global $wgOut;
		$wgOut->addWikiText( 'woo' );
	}
	
	/**
	 * Put together a rev_deleted bitfield from the submitted checkboxes
	 * @param WebRequest $request
	 * @return int
	 */
	function extractBitfield( $request ) {
		$bitfield = 0;
		foreach( $this->checks as $item ) {
			list( /* message */ , $name, $field ) = $item;
			if( $request->getCheck( $name ) ) {
				$bitfield |= $field;
			}
		}
		return $bitfield;
	}
	
	function save( $bitfield, $reason ) {
		$dbw = wfGetDB( DB_MASTER );
		$deleter = new RevisionDeleter( $dbw );
		$deleter->setVisibility( $this->revisions, $bitfield, $reason );
	}
}


class RevisionDeleter {
	function __construct( $db ) {
		$this->db = $db;
	}
	
	/**
	 * @param array $items list of revision ID numbers
	 * @param int $bitfield new rev_deleted value
	 * @param string $comment Comment for log records
	 */
	function setVisibility( $items, $bitfield, $comment ) {
		$pages = array();
		
		// To work!
		foreach( $items as $revid ) {
			$rev = Revision::newFromId( $revid );
			if( !isset( $rev ) ) {
				return false;
			}
			$this->updateRevision( $rev, $bitfield );
			$this->updateRecentChanges( $rev, $bitfield );
			
			// For logging, maintain a count of revisions per page
			$pageid = $rev->getPage();
			if( isset( $pages[$pageid] ) ) {
				$pages[$pageid]++;
			} else {
				$pages[$pageid] = 1;
			}
		}
		
		// Clear caches...
		foreach( $pages as $pageid => $count ) {
			$title = Title::newFromId( $pageid );
			$this->updatePage( $title );
			$this->updateLog( $title, $count, $bitfield, $comment );
		}
		
		return true;
	}
	
	/**
	 * Update the revision's rev_deleted field
	 * @param Revision $rev
	 * @param int $bitfield new rev_deleted bitfield value
	 */
	function updateRevision( $rev, $bitfield ) {
		$this->db->update( 'revision',
			array( 'rev_deleted' => $bitfield ),
			array( 'rev_id' => $rev->getId() ),
			'RevisionDeleter::updateRevision' );
	}
	
	/**
	 * Update the revision's recentchanges record if fields have been hidden
	 * @param Revision $rev
	 * @param int $bitfield new rev_deleted bitfield value
	 */
	function updateRecentChanges( $rev, $bitfield ) {
		$this->db->update( 'recentchanges',
			array(
				'rc_user' => ($bitfield & Revision::DELETED_USER) ? 0 : $rev->getUser(),
				'rc_user_text' => ($bitfield & Revision::DELETED_USER) ? wfMsg( 'rev-deleted-user' ) : $rev->getUserText(),
				'rc_comment' => ($bitfield & Revision::DELETED_COMMENT) ? wfMsg( 'rev-deleted-comment' ) : $rev->getComment() ),
			array(
				'rc_this_oldid' => $rev->getId() ),
			'RevisionDeleter::updateRecentChanges' );
	}
	
	/**
	 * Touch the page's cache invalidation timestamp; this forces cached
	 * history views to refresh, so any newly hidden or shown fields will
	 * update properly.
	 * @param Title $title
	 */
	function updatePage( $title ) {
		$title->invalidateCache();
	}
	
	/**
	 * Record a log entry on the action
	 * @param Title $title
	 * @param int $count the number of revisions altered for this page
	 * @param int $bitfield the new rev_deleted value
	 * @param string $comment
	 */
	function updateLog( $title, $count, $bitfield, $comment ) {
		$log = new LogPage( 'delete' );
		$reason = "changed $count revisions to $bitfield";
		$reason .= ": $comment";
		$log->addEntry( 'revision', $title, $reason );
	}
}

?>
