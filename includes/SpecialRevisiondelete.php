<?php

/**
 * Not quite ready for production use yet; need to fix up the restricted mode,
 * and provide for preservation across delete/undelete of images.
 */

function wfSpecialRevisiondelete( $par = null ) {
	global $wgOut, $wgRequest;
	
	$target = $wgRequest->getText( 'target' );
	// handle our many different possible input types
	$oldid = $wgRequest->getIntArray( 'oldid' );
	$logid = $wgRequest->getIntArray( 'logid' );
	$arid = $wgRequest->getIntArray( 'arid' );
	$fileid = $wgRequest->getIntArray( 'fileid' );

	$page = Title::newFromUrl( $target, false );
	if( is_null( $page ) ) {
		$wgOut->showErrorPage( 'notargettitle', 'notargettext' );
		return;
	}

	$input_types = !is_null( $oldid ) + !is_null( $logid ) + !is_null( $arid ) + !is_null( $fileid );
	if( $input_types > 1 || $input_types==0 ) {
	//one target set at a time please!
		$wgOut->showErrorPage( 'revdelete-nooldid-title', 'revdelete-nooldid-text' );
		return;
	}
	
	$form = new RevisionDeleteForm( $wgRequest, $oldid, $logid, $arid, $fileid );
	if( $wgRequest->wasPosted() ) {
		$form->submit( $wgRequest );
	} else if( $oldid || $arid ) {
		$form->showRevs( $wgRequest );
	} else if( $logid ) {
		$form->showEvents( $wgRequest );
	} else if( $fileid ) {
		$form->showImages( $wgRequest );
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
				$file = new ArchivedFile( $this->page, $fileid );
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
		// FIXME: all items checked for just on event are checked, even if not set for the others
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
	 * @param Revision $rev
	 * @returns string
	 */
	function historyLine( $rev ) {
		global $wgContLang;
		$date = $wgContLang->timeanddate( $rev->getTimestamp() );
		
		$difflink=''; $del = '';
		if( $this->deletetype=='old' ) {
			$difflink = '(' . $this->skin->makeKnownLinkObj( $this->page, wfMsgHtml('diff'), 
		'&diff=' . $rev->getId() . '&oldid=prev' ) . ')';
			$revlink = $this->skin->makeLinkObj( $this->page, $date, 'oldid=' . $rev->getId() );
		} else if( $this->deletetype=='ar' ) {
			$undelete = SpecialPage::getTitleFor( 'Undelete' );
			$target = $this->page->getPrefixedText();
			$revlink = $this->skin->makeLinkObj( $undelete, $date, "target=$target&timestamp=" . $rev->getTimestamp() );
		}
	
		if ( $rev->isDeleted(Revision::DELETED_TEXT) ) {
			$revlink = '<span class="history-deleted">'.$revlink.'</span>';
			$del = ' <tt>' . wfMsgHtml( 'deletedrev' ) . '</tt>';
			if ( !$rev->userCan(Revision::DELETED_TEXT) ) {
				$revlink = '<span class="history-deleted">'.$date.'</span>';
			}
		}
		
		return
			"<li> $difflink $revlink " . $this->skin->revUserLink( $rev ) . " " . $this->skin->revComment( $rev ) . "$del</li>";
	}
	
	/**
	 * @param Image $file
	 * @returns string
	 */	
	function uploadLine( $file ) {
		global $wgContLang;
		
		$target = $this->page->getPrefixedText();
		$date = $wgContLang->timeanddate( $file->mTimestamp, true  );

		$del = '';
		if ( $file->mGroup == 'deleted' ) {
			$undelete = SpecialPage::getTitleFor( 'Undelete' );
			$pageLink = $this->skin->makeKnownLinkObj( $undelete, $date, "target=$target&file=$file->mKey" );
		} else {
			$pageLink = $this->skin->makeKnownLinkObj( $this->page, $date, "file=$file->mKey" );
		}
		if ( $file->isDeleted(Image::DELETED_FILE) ) {
			$pageLink = '<span class="history-deleted">' . $pageLink . '</span>';
			$del = ' <tt>' . wfMsgHtml( 'deletedrev' ) . '</tt>';
			if ( !$file->userCan(Image::DELETED_FILE) ) {
				$pageLink = '<span class="history-deleted">'.$date.'</span>';
			}
		}
		
		$data = wfMsgHtml( 'widthheight',
						$wgContLang->formatNum( $file->mWidth ),
						$wgContLang->formatNum( $file->mHeight ) ) .
				' (' . wfMsgHtml( 'nbytes', $wgContLang->formatNum( $file->mSize ) ) . ')';	
	
		return
			"<li> $pageLink " . $this->skin->fileUserLink( $file ) . " $data " . $this->skin->fileComment( $file ) . "$del</li>";
	}
	
	/**
	 * @param Revision $rev
	 * @returns string
	 */
	function logLine( $log, $event ) {
		global $wgContLang;

		$date = $wgContLang->timeanddate( $event->log_timestamp );
		$paramArray = LogPage::extractParams( $event->log_params );

		if ( !LogViewer::userCan($event,LogViewer::DELETED_ACTION) ) {
			$action = '<span class="history-deleted">' . wfMsgHtml('rev-deleted-event') . '</span>';	
		} else {	
			$action = LogPage::actionText( $event->log_type, $event->log_action, $this->page, $this->skin, $paramArray, true, true );
			if( $event->log_deleted & LogViewer::DELETED_ACTION )
				$action = '<span class="history-deleted">' . $action . '</span>';
		}
		return
			"<li>$date" . " " . $this->skin->logUserLink( $event ) . " $action " . $this->skin->logComment( $event ) . "</li>";
	}
	
	/**
	 * @param WebRequest $request
	 */
	function submit( $request ) {
		$bitfield = $this->extractBitfield( $request );
		$comment = $request->getText( 'wpReason' );
		
		$target = $request->getText( 'target' );
		$title = Title::newFromURL( $target, false );
		
		if( $this->save( $bitfield, $comment, $title ) ) {
			$this->success( $request );
		} else if( $request->getCheck( 'oldid' ) || $request->getCheck( 'arid' ) ) {
			return $this->showRevs( $request );
		} else if( $request->getCheck( 'logid' ) ) {
			return $this->showLogs( $request );
		} else if( $request->getCheck( 'fileid' ) ) {
			return $this->showImages( $request );
		} 
	}
	
	function success( $request ) {
		global $wgOut;
		
		$wgOut->setPagetitle( wfMsgHtml( 'actioncomplete' ) );
		
		$target = $request->getText( 'target' );
		$type = $request->getText( 'type' );

		$title = Title::newFromURL( $target, false );
		$name = $title->makeName( $title->getNamespace(), $title->getText() );
		
		$logtitle = SpecialPage::getTitleFor( 'Log' );
        $loglink = $this->skin->makeKnownLinkObj( $logtitle, wfMsgHtml( 'viewpagelogs' ),
		wfArrayToCGI( array('page' => $name ) ) );
		$histlink = $this->skin->makeKnownLinkObj( $title, wfMsgHtml( 'revhistory' ),
		wfArrayToCGI( array('action' => 'history' ) ) );
		
		if ( $title->getNamespace() > -1)
			$wgOut->setSubtitle( '<p>'.$histlink.' / '.$loglink.'</p>' );
		
		if( $type=='log' ) {
			$wgOut->addWikiText( wfMsgHtml('logdelete-success', $target), false );
			$this->showEvents( $request );
		} else if( $type=='old' || $type=='ar' ) {
		  	$wgOut->addWikiText( wfMsgHtml('revdelete-success', $target), false );
		  	$this->showRevs( $request );
		} else if ( $type=='file' ) {
		  	$wgOut->addWikiText( wfMsgHtml('revdelete-success', $target), false );
		  	$this->showImages( $request );
		}
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
	
	function save( $bitfield, $reason, $title ) {
		$dbw = wfGetDB( DB_MASTER );
		$deleter = new RevisionDeleter( $dbw );

		if( $this->revisions ) {
			return $deleter->setRevVisibility( $title, $this->revisions, $bitfield, $reason );
		} else if( $this->events ) {
			return $deleter->setEventVisibility( $title, $this->events, $bitfield, $reason );
		} else if( $this->archrevs ) {
			return $deleter->setArchiveVisibility( $title, $this->archrevs, $bitfield, $reason );
		} else if( $this->files ) {
			return $deleter->setFileVisibility( $title, $this->files, $bitfield, $reason );
		}
	}
}


class RevisionDeleter {
	function __construct( $db ) {
		$this->db = $db;
	}
	
	/**
	 * @param $title, the page these events apply to
	 * @param array $items list of revision ID numbers
	 * @param int $bitfield new rev_deleted value
	 * @param string $comment Comment for log records
	 */
	function setRevVisibility( $title, $items, $bitfield, $comment ) {
		global $wgOut;
		
		$UserAllowedAll = true;
		$pages_count = array(); $pages_revIds = array();
		// To work!
		foreach( $items as $revid ) {
			$rev = Revision::newFromTitle( $title, $revid );
			if( !isset( $rev ) || $rev->isCurrent() ) {
				return false;
			} else if( !$rev->userCan(Revision::DELETED_RESTRICTED) ) {
    			$UserAllowedAll=false; 
				continue;
			}
			$pageid = $rev->getPage();
			// For logging, maintain a count of revisions per page
			if ( !isset($pages_count[$pageid]) ) {
				$pages_count[$pageid]=0;
				$pages_revIds[$pageid]=array();
			}
			// Which pages did we change anything about?
			if ( $rev->mDeleted != $bitfield ) {
				$pages_count[$pageid]++;
				$pages_revIds[$pageid][]=$revid;
				
			   	$this->updateRevision( $rev, $bitfield );
				$this->updateRecentChangesEdits( $rev, $bitfield, false );
			}
		}
		
		// Clear caches...
		foreach( $pages_count as $pageid => $count ) {
			//Don't log or touch if nothing changed
			if ( $count > 0 ) {
			   $title = Title::newFromId( $pageid );
			   $this->updatePage( $title );
			   $this->updateLog( $title, $count, $bitfield, $comment, $title, 'old', $pages_revIds[$pageid] );
			}
		}
		// Where all revs allowed to be set?
		if ( !$UserAllowedAll ) {
		//FIXME: still might be confusing???
			$wgOut->permissionRequired( 'hiderevision' ); return false;
		}
		
		return true;
	}
	
	 /**
	 * @param $title, the page these events apply to
	 * @param array $items list of revision ID numbers
	 * @param int $bitfield new rev_deleted value
	 * @param string $comment Comment for log records
	 */
	function setArchiveVisibility( $title, $items, $bitfield, $comment ) {
		global $wgOut;
		
		$UserAllowedAll = true;
		$count = 0; $Id_set = array();
		// To work!
		$archive = new PageArchive( $title );
		foreach( $items as $revid ) {
			$rev = $archive->getRevision( '', $revid );
			if( !isset( $rev ) ) {
				return false;
			} else if( !$rev->userCan(Revision::DELETED_RESTRICTED) ) {
    			$UserAllowedAll=false;
				continue;
			}
			// For logging, maintain a count of revisions
			if ( $rev->mDeleted != $bitfield ) {
			   $Id_set[]=$revid;
			   $count++;
			}		
			$this->updateArchive( $rev, $bitfield );
		}
		
		// Log if something was changed
		if ( $count > 0 ) {
			$this->updateLog( $title, $count, $bitfield, $comment, $title, 'ar', $Id_set );
		}
		// Where all revs allowed to be set?
		if ( !$UserAllowedAll ) {
			$wgOut->permissionRequired( 'hiderevision' ); return false;
		}
		
		return true;
	}
	
	 /**
	 * @param $title, the page these events apply to
	 * @param array $items list of revision ID numbers
	 * @param int $bitfield new rev_deleted value
	 * @param string $comment Comment for log records
	 */
	function setFileVisibility( $title, $items, $bitfield, $comment ) {
		global $wgOut;
		
		$UserAllowedAll = true;
		$count = 0; $Id_set = array();
		// To work!
		foreach( $items as $fileid ) {
			$file = new ArchivedFile( $title, $fileid );
			if( !isset( $file ) ) {
				return false;
			} else if( !$file->userCan(Revision::DELETED_RESTRICTED) ) {
    			$UserAllowedAll=false;
				continue;
			}
			// For logging, maintain a count of revisions
			if ( $file->mDeleted != $bitfield ) {
			   $Id_set[]=$fileid;
			   $count++;
			}		
			$this->updateFiles( $file, $bitfield );
		}
		
		// Log if something was changed
		if ( $count > 0 ) {
			$this->updateLog( $title, $count, $bitfield, $comment, $title, 'file', $Id_set );
		}
		// Where all revs allowed to be set?
		if ( !$UserAllowedAll ) {
			$wgOut->permissionRequired( 'hiderevision' ); return false;
		}
		
		return true;
	}

	/**
	 * @param $title, the page these events apply to
	 * @param array $items list of log ID numbers
	 * @param int $bitfield new log_deleted value
	 * @param string $comment Comment for log records
	 */
	function setEventVisibility( $title, $items, $bitfield, $comment ) {
		global $wgOut;
		
		$UserAllowedAll = true;
		$logs_count = array(); $logs_Ids = array();
		// To work!
		foreach( $items as $logid ) {
			$event = LogReader::newFromTitle( $title, $logid );
			if( !isset( $event ) ) {
				return false;
			} else if( !LogViewer::userCan($event, Revision::DELETED_RESTRICTED) || $event->log_type == 'oversight' ) {
			// Don't hide from oversight log!!!
    			$UserAllowedAll=false;
    			continue;
			}
			$logtype = $event->log_type;
			// For logging, maintain a count of events per log type
			if( !isset( $logs_count[$logtype] ) ) {
				$logs_count[$logtype]=0;
				$logs_Ids[$logtype]=array();
			}
			// Which logs did we change anything about?
			if ( $event->log_deleted != $bitfield ) {
				$logs_Ids[$logtype][]=$logid;
				$logs_count[$logtype]++;
			   
			   	$this->updateLogs( $event, $bitfield );
				$this->updateRecentChangesLog( $event, $bitfield, true );
			}
		}
		foreach( $logs_count as $logtype => $count ) {
			//Don't log or touch if nothing changed
			if ( $count > 0 ) {
			   $target = SpecialPage::getTitleFor( 'Log', $logtype );
			   $this->updateLog( $target, $count, $bitfield, $comment, $title, 'log', $logs_Ids[$logtype] );
			}
		}
		// Where all revs allowed to be set?
		if ( !$UserAllowedAll ) {
			$wgOut->permissionRequired( 'hiderevision' ); return false;
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
	 * Update the revision's rev_deleted field
	 * @param Revision $rev
	 * @param int $bitfield new rev_deleted bitfield value
	 */
	function updateArchive( $rev, $bitfield ) {
		$this->db->update( 'archive',
			array( 'ar_deleted' => $bitfield ),
			array( 'ar_rev_id' => $rev->getId() ),
			'RevisionDeleter::updateArchive' );
	}

	/**
	 * Update the images's fa_deleted field
	 * @param Revision $file
	 * @param int $bitfield new rev_deleted bitfield value
	 */
	function updateFiles( $file, $bitfield ) {
		$this->db->update( 'filearchive',
			array( 'fa_deleted' => $bitfield ),
			array( 'fa_id' => $file->mId ),
			'RevisionDeleter::updateFiles' );
	}	
	
	/**
	 * Update the logging log_deleted field
	 * @param Revision $rev
	 * @param int $bitfield new rev_deleted bitfield value
	 */
	function updateLogs( $event, $bitfield ) {
		$this->db->update( 'logging',
			array( 'log_deleted' => $bitfield ),
			array( 'log_id' => $event->log_id ),
			'RevisionDeleter::updateLogs' );
	}
	
	/**
	 * Update the revision's recentchanges record if fields have been hidden
	 * @param Revision $event
	 * @param int $bitfield new rev_deleted bitfield value
	 */
	function updateRecentChangesLog( $event, $bitfield ) {
		$this->db->update( 'recentchanges',
			array( 'rc_deleted' => $bitfield,
				   'rc_patrolled' => 1),
			array( 'rc_logid' => $event->log_id ),
			'RevisionDeleter::updateRecentChangesLog' );
	}
	
	/**
	 * Update the revision's recentchanges record if fields have been hidden
	 * @param Revision $rev
	 * @param int $bitfield new rev_deleted bitfield value
	 */
	function updateRecentChangesEdits( $rev, $bitfield ) {
		$this->db->update( 'recentchanges',
			array( 'rc_deleted' => $bitfield,
				   'rc_patrolled' => 1),
			array( 'rc_this_oldid' => $rev->getId() ),
			'RevisionDeleter::updateRecentChangesEdits' );
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
	function updateLog( $title, $count, $bitfield, $comment, $target, $prefix, $items = array() ) {
		// Put things hidden from sysops in the oversight log
		$logtype = ( $bitfield & Revision::DELETED_RESTRICTED ) ? 'oversight' : 'delete';
		// Add params for effected page and ids
		$params = array( $target->getPrefixedText(), $prefix, implode( ',', $items) );
		$log = new LogPage( $logtype );	
		if ( $prefix=='log' ) {
    		$reason = wfMsgExt('logdelete-logaction', array('parsemag'), $count, $bitfield, $target->getPrefixedText() );
			if ($comment) $reason .= ": $comment";
			$log->addEntry( 'event', $title, $reason, $params );
		} else if ( $prefix=='old' ) {
    		$reason = wfMsgExt('revdelete-logaction', array('parsemag'), $count, $bitfield );
			if ($comment) $reason .= ": $comment";
			$log->addEntry( 'revision', $title, $reason, $params );
		} else if ( $prefix=='file' ) {
    		$reason = wfMsgExt('revdelete-logaction', array('parsemag'), $count, $bitfield );
			if ($comment) $reason .= ": $comment";
			$log->addEntry( 'file', $title, $reason, $params );
		}
	}
}

?>
