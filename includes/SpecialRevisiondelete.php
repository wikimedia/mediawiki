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
	function __construct( $request, $oldid, $logid, $arid, $fileid ) {
		global $wgUser;
		
		$target = $request->getText( 'target' );
		$this->page = Title::newFromUrl( $target, false );
		
		$this->revisions = $request->getIntArray( 'oldid', array() );
		$this->events = $request->getIntArray( 'logid', array() );
		$this->archrevs = $request->getIntArray( 'arid', array() );
		$this->files = $request->getIntArray( 'fileid', array() );
		
		$this->skin = $wgUser->getSkin();
	
		// log events don't have text to hide, but hiding the page name is useful
		if ( $fileid ) {
		   $hide_text_name = array( 'revdelete-hide-image', 'wpHideImage', Image::DELETED_FILE );
		   $this->deletetype='file';
		} else if ( $logid ) {
		   $hide_text_name = array( 'revdelete-hide-name', 'wpHideName', LogViewer::DELETED_ACTION );
		   $this->deletetype='log';
		} else {
		   $hide_text_name = array( 'revdelete-hide-text', 'wpHideText', Revision::DELETED_TEXT );
		   if ( $arid ) $this->deletetype='ar';
		   else $this->deletetype='old';
		}
		$this->checks = array(
			$hide_text_name,
			array( 'revdelete-hide-comment', 'wpHideComment', Revision::DELETED_COMMENT ),
			array( 'revdelete-hide-user', 'wpHideUser', Revision::DELETED_USER ),
			array( 'revdelete-hide-restricted', 'wpHideRestricted', Revision::DELETED_RESTRICTED ) );
	}
	
	/**
	 * This sets any fields that are true to a bitfield to true on a given bitfield
	 * @param $bitfield, running bitfield
	 * @param $nbitfield, new bitfiled
	 */	
	function setBitfield( $bitfield, $nbitfield ) {
		if ( $nbitfield & Revision::DELETED_TEXT) $bitfield |= Revision::DELETED_TEXT;
		if ( $nbitfield & LogViewer::DELETED_ACTION) $bitfield |= LogViewer::DELETED_ACTION;
		if ( $nbitfield & Image::DELETED_FILE) $bitfield |= Image::DELETED_FILE;
		if ( $nbitfield & Revision::DELETED_COMMENT) $bitfield |= Revision::DELETED_COMMENT;
		if ( $nbitfield & Revision::DELETED_USER) $bitfield |= Revision::DELETED_USER;
		if ( $nbitfield & Revision::DELETED_RESTRICTED) $bitfield |= Revision::DELETED_RESTRICTED;
		return $bitfield;
	}
	
	/**
	 * This lets a user set restrictions for live and archived revisions
	 * @param WebRequest $request
	 */
	function showRevs( $request ) {
		global $wgOut, $wgUser, $action;

		$UserAllowed = true;
		$wgOut->addWikiText( wfMsgExt( 'revdelete-selected', 'parseinline', $this->page->getPrefixedText(), count( $this->revisions) ) );

		$bitfields = 0;
		$wgOut->addHtml( "<ul>" );
		if ( $this->deletetype=='old') {
			foreach( $this->revisions as $revid ) {
				$rev = Revision::newFromTitle( $this->page, $revid );
				// Hiding top revisison is bad
				if( !isset( $rev ) || $rev->isCurrent() ) {
					$wgOut->showErrorPage( 'revdelete-nooldid-title', 'revdelete-nooldid-text' );
					return;
				} else if( !$rev->userCan(Revision::DELETED_RESTRICTED) ) {
				// If a rev is hidden from sysops
					if ( $action != 'submit') {
						$wgOut->permissionRequired( 'hiderevision' ); return;
					}
					$UserAllowed=false;
				}
			$wgOut->addHtml( $this->historyLine( $rev ) );
			$bitfields = $this->setBitfield( $bitfields, $rev->mDeleted );
			}
		} else if ( $this->deletetype=='ar') {
		   $archive = new PageArchive( $this->page );
			foreach( $this->archrevs as $revid ) {
    			$rev = $archive->getRevision('', $revid );
				if( !isset( $rev ) ) {
					$wgOut->showErrorPage( 'revdelete-nooldid-title', 'revdelete-nooldid-text' );
					return;
				} else if( !$rev->userCan(Revision::DELETED_RESTRICTED) ) {
				//if a rev is hidden from sysops
					if ( $action != 'submit') {
						$wgOut->permissionRequired( 'hiderevision' ); return;
					}
					$UserAllowed=false;
				}
			$wgOut->addHtml( $this->historyLine( $rev ) );
			$bitfields = $this->setBitfield( $bitfields, $rev->mDeleted );
			}
		} 
		$wgOut->addHtml( "</ul>" );
		
		$wgOut->addWikiText( wfMsgHtml( 'revdelete-text' ) );
		//Normal sysops can always see what they did, but can't always change it
		if ( !$UserAllowed ) return;
		
		$items = array(
			wfInputLabel( wfMsgHtml( 'revdelete-log' ), 'wpReason', 'wpReason', 60 ),
			wfSubmitButton( wfMsgHtml( 'revdelete-submit' ) ) );
		$hidden = array(
			wfHidden( 'wpEditToken', $wgUser->editToken() ),
			wfHidden( 'target', $this->page->getPrefixedText() ),
			wfHidden( 'type', $this->deletetype ) );
		if( $this->deletetype=='old' ) {
			foreach( $this->revisions as $revid ) {
				$hidden[] = wfHidden( 'oldid[]', $revid );
			}	
		} else if( $this->deletetype=='ar' ) {	
			foreach( $this->archrevs as $revid ) {
				$hidden[] = wfHidden( 'arid[]', $revid );
			}
		}
		$special = SpecialPage::getTitleFor( 'Revisiondelete' );
		$wgOut->addHtml( wfElement( 'form', array(
			'method' => 'post',
			'action' => $special->getLocalUrl( 'action=submit' ) ),
			null ) );
		
		$wgOut->addHtml( '<fieldset><legend>' . wfMsgHtml( 'revdelete-legend' ) . '</legend>' );
		// FIXME: all items checked for just one rev are checked, even if not set for the others
		foreach( $this->checks as $item ) {
			list( $message, $name, $field ) = $item;
			$wgOut->addHtml( '<div>' .
				wfCheckLabel( wfMsgHtml( $message), $name, $name, $bitfields & $field ) .
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
	 * This lets a user set restrictions for archived images
	 * @param WebRequest $request
	 */
	function showImages( $request ) {
		global $wgOut, $wgUser, $action;

		$UserAllowed = true;
		$wgOut->addWikiText( wfMsgExt( 'revdelete-selected', 'parseline', $this->page->getPrefixedText(), count( $this->files ) ) );
		
		$bitfields = 0;
		$wgOut->addHtml( "<ul>" );
			foreach( $this->files as $fileid ) {
				$file = new FSarchivedFile( $this->page, $fileid );
				if( !isset( $file->mId ) ) {
					$wgOut->showErrorPage( 'revdelete-nooldid-title', 'revdelete-nooldid-text' );
					return;
				} else if( !$file->userCan(Revision::DELETED_RESTRICTED) ) {
				// If a rev is hidden from sysops
					if ( $action != 'submit') {
						$wgOut->permissionRequired( 'hiderevision' ); return;
					}
					$UserAllowed=false;
				}
			$wgOut->addHtml( $this->uploadLine( $file ) );
			$bitfields = $this->setBitfield( $bitfields, $file->mDeleted );
			}
		$wgOut->addHtml( "</ul>" );
		
		$wgOut->addWikiText( wfMsgHtml( 'revdelete-text' ) );
		//Normal sysops can always see what they did, but can't always change it
		if ( !$UserAllowed ) return;
		
		$items = array(
			wfInputLabel( wfMsgHtml( 'revdelete-log' ), 'wpReason', 'wpReason', 60 ),
			wfSubmitButton( wfMsgHtml( 'revdelete-submit' ) ) );
		$hidden = array(
			wfHidden( 'wpEditToken', $wgUser->editToken() ),
			wfHidden( 'target', $this->page->getPrefixedText() ),
			wfHidden( 'type', $this->deletetype ) );
		foreach( $this->files as $fileid ) {
			$hidden[] = wfHidden( 'fileid[]', $fileid );
		}	
		$special = SpecialPage::getTitleFor( 'Revisiondelete' );
		$wgOut->addHtml( wfElement( 'form', array(
			'method' => 'post',
			'action' => $special->getLocalUrl( 'action=submit' ) ),
			null ) );
		
		$wgOut->addHtml( '<fieldset><legend>' . wfMsgHtml( 'revdelete-legend' ) . '</legend>' );
		// FIXME: all items checked for just one file are checked, even if not set for the others
		foreach( $this->checks as $item ) {
			list( $message, $name, $field ) = $item;
			$wgOut->addHtml( '<div>' .
				wfCheckLabel( wfMsgHtml( $message), $name, $name, $bitfields & $field ) .
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
	 * This lets a user set restrictions for log items
	 * @param WebRequest $request
	 */
	function showEvents( $request ) {
		global $wgOut, $wgUser, $action;

		$UserAllowed = true;
		$wgOut->addWikiText( wfMsgExt( 'logdelete-selected', 'parseinline', $this->page->getPrefixedText(), count( $this->events ) ) );
		
		$bitfields = 0;
		$wgOut->addHtml( "<ul>" );
		foreach( $this->events as $logid ) {
			$log = new LogViewer( $wgRequest );
			$event = LogReader::newFromTitle( $this->page, $logid );
			// Don't hide from oversight log!!!
			if( !isset( $event ) || $event->log_type == 'oversight' ) {
				$wgOut->showErrorPage( 'revdelete-nooldid-title', 'revdelete-nooldid-text' );
				return;
			} else if( !$log->userCan($event, Revision::DELETED_RESTRICTED) ) {
			// If an event is hidden from sysops
				if ( $action != 'submit') {
					$wgOut->permissionRequired( 'hiderevision' ); return;
				}
				$UserAllowed=false;
			}
			$wgOut->addHtml( $this->logLine( $log, $event ) );
			$bitfields = $this->setBitfield( $bitfields, $event->log_deleted );
		}
		$wgOut->addHtml( "</ul>" );

		$wgOut->addWikiText( wfMsgHtml( 'revdelete-text' ) );
		//Normal sysops can always see what they did, but can't always change it
		if ( !$UserAllowed ) return;
		
		$items = array(
			wfInputLabel( wfMsgHtml( 'revdelete-log' ), 'wpReason', 'wpReason', 60 ),
			wfSubmitButton( wfMsgHtml( 'revdelete-submit' ) ) );
		$hidden = array(
			wfHidden( 'wpEditToken', $wgUser->editToken() ),
			wfHidden( 'target', $this->page->getPrefixedText() ),
			wfHidden( 'type', $this->deletetype ) );
		foreach( $this->events as $logid ) {
			$hidden[] = wfHidden( 'logid[]', $logid );
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
