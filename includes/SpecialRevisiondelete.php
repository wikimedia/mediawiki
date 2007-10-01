<?php

/**
 * Special page allowing users with the appropriate permissions to view
 * and hide revisions. Log items can also be hidden.
 *
 * @addtogroup SpecialPage
 */

function wfSpecialRevisiondelete( $par = null ) {
	global $wgOut, $wgRequest, $wgUser, $wgAllowLogDeletion;
	# Handle our many different possible input types
	$target = $wgRequest->getText( 'target' );
	$oldid = $wgRequest->getArray( 'oldid' );
	$artimestamp = $wgRequest->getArray( 'artimestamp' );
	$logid = $wgAllowLogDeletion ? $wgRequest->getArray( 'logid' ) : '';
	$image = $wgRequest->getArray( 'oldimage' );
	$fileid = $wgRequest->getArray( 'fileid' );
	# For reviewing deleted files...
	$file = $wgRequest->getVal( 'file' );
	# If this is a revision, then we need a target page
	$page = Title::newFromUrl( $target );
	if( is_null($page) && is_null($logid) ) {
		$wgOut->addWikiText( wfMsgHtml( 'undelete-header' ) );
		return;
	}
	# Only one target set at a time please!
	$inputs = !empty($file) + !empty($oldid) + !empty($logid) + !empty($artimestamp) + 
		!empty($fileid) + !empty($image);
	
	if( $inputs > 1 || $inputs==0 ) {
		$wgOut->showErrorPage( 'revdelete-nooldid-title', 'revdelete-nooldid-text' );
		return;
	}
	# Either submit or create our form
	$form = new RevisionDeleteForm( $page, $oldid, $logid, $artimestamp, $fileid, $image, $file );
	if( $wgRequest->wasPosted() ) {
		$form->submit( $wgRequest );
	} else if( $oldid || $artimestamp ) {
		$form->showRevs( $wgRequest );
	} else if( $fileid || $image ) {
		$form->showImages( $wgRequest );
	} else if( $logid ) {
		$form->showEvents( $wgRequest );
	}
	# Show relevant lines from the deletion log
	# This will show even if said ID does not exist...might be helpful
	if( !is_null($page) ) {
		$wgOut->addHTML( "<h2>" . htmlspecialchars( LogPage::logName( 'delete' ) ) . "</h2>\n" );
		$logViewer = new LogViewer(
			new LogReader(
				new FauxRequest(
					array( 'page' => $page->getPrefixedText(), 'type' => 'delete' ) ) ) );
		$logViewer->showList( $wgOut );
	}
}

/**
 * Implements the GUI for Revision Deletion.
 * @addtogroup SpecialPage
 */
class RevisionDeleteForm {
	/**
	 * @param Title $page
	 * @param array $oldids
	 * @param array $logids
	 * @param array $artimestamps
	 * @param array $fileids
	 * @param array $oldimages
     * @param string $file
	 */
	function __construct( $page, $oldids=null, $logids=null, $artimestamps=null, $fileids=null, $oldimages=null, $file=null ) {
		global $wgUser;

		$this->page = $page;
		$this->skin = $wgUser->getSkin();
		
		// For reviewing deleted files
		if( $file ) {
			$oimage = RepoGroup::singleton()->getLocalRepo()->newFromArchiveName( $page, $file );
			$oimage->load();
			// Check if user is allowed to see this file
			if( !$oimage->userCan(File::DELETED_FILE) ) {
				$wgOut->permissionRequired( 'hiderevision' ); 
				return false;
			} else {
				return $this->showFile( $file );
			}
		}
		// At this point, we should only have one of these
		if( $oldids ) {
			$this->revisions = $oldids;
			$hide_content_name = array( 'revdelete-hide-text', 'wpHideText', Revision::DELETED_TEXT );
			$this->deletetype='oldid';
		} else if( $artimestamps ) {
			$this->archrevs = $artimestamps;
			$hide_content_name = array( 'revdelete-hide-text', 'wpHideText', Revision::DELETED_TEXT );
			$this->deletetype='artimestamp';
		} else if( $oldimages ) {
			$this->ofiles = $oldimages;
			$hide_content_name = array( 'revdelete-hide-image', 'wpHideImage', File::DELETED_FILE );
			$this->deletetype='oldimage';
		} else if( $fileids ) {
			$this->afiles = $fileids;
			$hide_content_name = array( 'revdelete-hide-image', 'wpHideImage', File::DELETED_FILE );
			$this->deletetype='fileid';
		} else if( $logids ) {
			$this->events = $logids;
			$hide_content_name = array( 'revdelete-hide-name', 'wpHideName', LogViewer::DELETED_ACTION );
			$this->deletetype='logid';
		}
		// Our checkbox messages depends one what we are doing, 
		// e.g. we don't hide "text" for logs or images
		$this->checks = array(
			$hide_content_name,
			array( 'revdelete-hide-comment', 'wpHideComment', Revision::DELETED_COMMENT ),
			array( 'revdelete-hide-user', 'wpHideUser', Revision::DELETED_USER ),
			array( 'revdelete-hide-restricted', 'wpHideRestricted', Revision::DELETED_RESTRICTED ) );
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
		
		$store = FileStore::get( 'hidden' );
		$store->stream( $key );
	}
	
	/**
	 * This lets a user set restrictions for live and archived revisions
	 * @param WebRequest $request
	 */
	function showRevs( $request ) {
		global $wgOut, $wgUser, $action;

		$UserAllowed = true;
		
		$count = ($this->deletetype=='oldid') ? 
			count($this->revisions) : count($this->archrevs);
		$wgOut->addWikiText( wfMsgExt( 'revdelete-selected', array('parsemag'), 
			$this->page->getPrefixedText(), $count ) );
		
		$bitfields = 0;
		$wgOut->addHtml( "<ul>" );
		
		$where = $revObjs = array();
		$dbr = wfGetDB( DB_SLAVE );
		// Live revisions...
		if( $this->deletetype=='oldid' ) {
			// Run through and pull all our data in one query
			foreach( $this->revisions as $revid ) {
				$where[] = intval($revid);
			}
			$whereClause = 'rev_id IN(' . implode(',',$where) . ')';
			$result = $dbr->select( 'revision', '*',
				array( 'rev_page' => $this->page->getArticleID(), 
					$whereClause ),
				__METHOD__ );
			while( $row = $dbr->fetchObject( $result ) ) {
				$revObjs[$row->rev_id] = new Revision( $row );
			}
			foreach( $this->revisions as $revid ) {
				// Hiding top revisison is bad
				if( !is_object($revObjs[$revid]) || $revObjs[$revid]->isCurrent() ) {
					$wgOut->showErrorPage( 'revdelete-nooldid-title', 'revdelete-nooldid-text' );
					return;
				} else if( !$revObjs[$revid]->userCan(Revision::DELETED_RESTRICTED) ) {
				// If a rev is hidden from sysops
					if( $action != 'submit') {
						$wgOut->permissionRequired( 'hiderevision' ); 
						return;
					}
					$UserAllowed = false;
				}
				$wgOut->addHtml( $this->historyLine( $revObjs[$revid] ) );
				$bitfields |= $revObjs[$revid]->mDeleted;
			}
		// The archives...
		} else {
			// Run through and pull all our data in one query
			foreach( $this->archrevs as $timestamp ) {
				$where[] = $dbr->addQuotes( $timestamp );
			}
			$whereClause = 'ar_timestamp IN(' . implode(',',$where) . ')';
			$result = $dbr->select( 'archive', '*',
				array( 'ar_namespace' => $this->page->getNamespace(),
					'ar_title' => $this->page->getDBKey(), 
						$whereClause ),
				__METHOD__ );
			while( $row = $dbr->fetchObject( $result ) ) {
				$revObjs[$row->ar_timestamp] = new Revision( array(
				'page'       => $this->page->getArticleId(),
				'id'         => $row->ar_rev_id,
				'text'       => $row->ar_text_id,
				'comment'    => $row->ar_comment,
				'user'       => $row->ar_user,
				'user_text'  => $row->ar_user_text,
				'timestamp'  => $row->ar_timestamp,
				'minor_edit' => $row->ar_minor_edit,
				'text_id'    => $row->ar_text_id,
				'deleted'    => $row->ar_deleted,
				'len'        => $row->ar_len) );
			}
			foreach( $this->archrevs as $timestamp ) {
				if( !is_object($revObjs[$timestamp]) ) {
					$wgOut->showErrorPage( 'revdelete-nooldid-title', 'revdelete-nooldid-text' );
					return;
				}
			}
			foreach( $revObjs as $rev ) {
				if( !$rev->userCan(Revision::DELETED_RESTRICTED) ) {
				//if a rev is hidden from sysops
					if( $action != 'submit') {
						$wgOut->permissionRequired( 'hiderevision' ); 
						return;
					}
					$UserAllowed = false;
				}
				$wgOut->addHtml( $this->historyLine( $rev ) );
				$bitfields |= $rev->mDeleted;
			}
		} 
		$wgOut->addHtml( "</ul>" );
		
		$wgOut->addWikiText( wfMsgHtml( 'revdelete-text' ) );
		//Normal sysops can always see what they did, but can't always change it
		if( !$UserAllowed ) return;
		
		$items = array(
			wfInputLabel( wfMsgHtml( 'revdelete-log' ), 'wpReason', 'wpReason', 60 ),
			wfSubmitButton( wfMsgHtml( 'revdelete-submit' ) ) );
		$hidden = array(
			wfHidden( 'wpEditToken', $wgUser->editToken() ),
			wfHidden( 'target', $this->page->getPrefixedText() ),
			wfHidden( 'type', $this->deletetype ) );
		if( $this->deletetype=='oldid' ) {
			foreach( $revObjs as $rev )
				$hidden[] = wfHidden( 'oldid[]', $rev->getID() );
		} else {	
			foreach( $revObjs as $rev )
				$hidden[] = wfHidden( 'artimestamp[]', $rev->getTimestamp() );
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
			$wgOut->addHtml( "<div>" .
				wfCheckLabel( wfMsgHtml( $message), $name, $name, $bitfields & $field ) .
				"</div>\n" );
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
		
		$count = ($this->deletetype=='oldimage') ? count($this->ofiles) : count($this->afiles);
		$wgOut->addWikiText( wfMsgExt( 'revdelete-selected', array('parsemag'), $this->page->getPrefixedText(), $count ) );
		
		$bitfields = 0;
		$wgOut->addHtml( "<ul>" );
		
		$where = $filesObjs = array();
		$dbr = wfGetDB( DB_SLAVE );
		// Live old revisions...
		if( $this->deletetype=='oldimage' ) {
			// Run through and pull all our data in one query
			foreach( $this->ofiles as $timestamp ) {
				$where[] = $dbr->addQuotes( $timestamp.'!'.$this->page->getDbKey() );
			}
			$whereClause = 'oi_archive_name IN(' . implode(',',$where) . ')';
			$result = $dbr->select( 'oldimage', '*',
				array( 'oi_name' => $this->page->getDbKey(),
					$whereClause ),
				__METHOD__ );
			while( $row = $dbr->fetchObject( $result ) ) {
				$filesObjs[$row->oi_archive_name] = RepoGroup::singleton()->getLocalRepo()->newFileFromRow( $row );
				$filesObjs[$row->oi_archive_name]->user = $row->oi_user;
				$filesObjs[$row->oi_archive_name]->userText = $row->oi_user_text;
			}
			// Check through our images
			foreach( $this->ofiles as $timestamp ) {
				$archivename = $timestamp.'!'.$this->page->getDbKey();
				if( !isset($filesObjs[$archivename]) ) {
					$wgOut->showErrorPage( 'revdelete-nooldid-title', 'revdelete-nooldid-text' );
					return;
				}
			}
			foreach( $filesObjs as $file ) {
				if( !isset($file) ) {
					$wgOut->showErrorPage( 'revdelete-nooldid-title', 'revdelete-nooldid-text' );
					return;
				} else if( !$file->userCan(File::DELETED_RESTRICTED) ) {
					// If a rev is hidden from sysops
					if( $action != 'submit' ) {
						$wgOut->permissionRequired( 'hiderevision' );
						return;
					}
					$UserAllowed = false;
				}
				// Inject history info
				$wgOut->addHtml( $this->uploadLine( $file ) );
				$bitfields |= $file->deleted;
			}	
		// Archived files...
		} else {
			// Run through and pull all our data in one query
			foreach( $this->afiles as $id ) {
				$where[] = intval($id);
			}
			$whereClause = 'fa_id IN(' . implode(',',$where) . ')';
			$result = $dbr->select( 'filearchive', '*',
				array( 'fa_name' => $this->page->getDbKey(),
					$whereClause ),
				__METHOD__ );
			while( $row = $dbr->fetchObject( $result ) ) {
				$filesObjs[$row->fa_id] = ArchivedFile::newFromRow( $row );
			}
			
			foreach( $this->afiles as $fileid ) {
				if( !isset($filesObjs[$fileid]) ) {
					$wgOut->showErrorPage( 'revdelete-nooldid-title', 'revdelete-nooldid-text' );
					return;
				} else if( !$filesObjs[$fileid]->userCan(File::DELETED_RESTRICTED) ) {
					// If a rev is hidden from sysops
					if( $action != 'submit' ) {
						$wgOut->permissionRequired( 'hiderevision' );
						return;
					}
					$UserAllowed = false;
				}
				// Inject history info
				$wgOut->addHtml( $this->uploadLine( $filesObjs[$fileid] ) );
				$bitfields |= $filesObjs[$fileid]->deleted;
			}
		}
		$wgOut->addHtml( "</ul>" );
		
		$wgOut->addWikiText( wfMsgHtml( 'revdelete-text' ) );
		//Normal sysops can always see what they did, but can't always change it
		if( !$UserAllowed ) return;
		
		$items = array(
			wfInputLabel( wfMsgHtml( 'revdelete-log' ), 'wpReason', 'wpReason', 60 ),
			wfSubmitButton( wfMsgHtml( 'revdelete-submit' ) ) );
		$hidden = array(
			wfHidden( 'wpEditToken', $wgUser->editToken() ),
			wfHidden( 'target', $this->page->getPrefixedText() ),
			wfHidden( 'type', $this->deletetype ) );
		if( $this->deletetype=='oldimage' ) {
			foreach( $this->ofiles as $filename )
				$hidden[] = wfHidden( 'oldimage[]', $filename );
		} else {
			foreach( $this->afiles as $fileid )
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
		$wgOut->addWikiText( wfMsgExt( 'logdelete-selected', array('parsemag'), count($this->events) ) );
		
		$bitfields = 0;
		$wgOut->addHtml( "<ul>" );
		
		$where = $logRows = array();
		$dbr = wfGetDB( DB_SLAVE );
		// Run through and pull all our data in one query
		foreach( $this->events as $logid ) {
			$where[] = intval($logid);
		}
		$whereClause = 'log_id IN(' . implode(',',$where) . ')';
		$result = $dbr->select( 'logging', '*', 
			array( $whereClause ),
			__METHOD__ );
		while( $row = $dbr->fetchObject( $result ) ) {
			$logRows[$row->log_id] = $row;
		}
		foreach( $this->events as $logid ) {
			// Don't hide from oversight log!!!
			if( !isset( $logRows[$logid] ) || $logRows[$logid]->log_type=='oversight' ) {
				$wgOut->showErrorPage( 'revdelete-nooldid-title', 'revdelete-nooldid-text' );
				return;
			} else if( !LogViewer::userCan( $logRows[$logid],Revision::DELETED_RESTRICTED) ) {
			// If an event is hidden from sysops
				if( $action != 'submit') {
					$wgOut->permissionRequired( 'hiderevision' );
					return;
				}
				$UserAllowed = false;
			}
			$wgOut->addHtml( $this->logLine( $logRows[$logid] ) );
			$bitfields |= $logRows[$logid]->log_deleted;
		}
		$wgOut->addHtml( "</ul>" );

		$wgOut->addWikiText( wfMsgHtml( 'revdelete-text' ) );
		//Normal sysops can always see what they did, but can't always change it
		if( !$UserAllowed ) return;
		
		$items = array(
			wfInputLabel( wfMsgHtml( 'revdelete-log' ), 'wpReason', 'wpReason', 60 ),
			wfSubmitButton( wfMsgHtml( 'revdelete-submit' ) ) );
		$hidden = array(
			wfHidden( 'wpEditToken', $wgUser->editToken() ),
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
		// Live revisions
		if( $this->deletetype=='oldid' ) {
			$difflink = '(' . $this->skin->makeKnownLinkObj( $this->page, wfMsgHtml('diff'), 
				'diff=' . $rev->getId() . '&oldid=prev' ) . ')';
			$revlink = $this->skin->makeLinkObj( $this->page, $date, 'oldid=' . $rev->getId() );
		} else {
		// Archived revisions
			$undelete = SpecialPage::getTitleFor( 'Undelete' );
			$target = $this->page->getPrefixedText();
			$revlink = $this->skin->makeLinkObj( $undelete, $date, "target=$target&timestamp=" . $rev->getTimestamp() );
		}
	
		if( $rev->isDeleted(Revision::DELETED_TEXT) ) {
			$revlink = '<span class="history-deleted">'.$revlink.'</span>';
			$del = ' <tt>' . wfMsgHtml( 'deletedrev' ) . '</tt>';
			if( !$rev->userCan(Revision::DELETED_TEXT) ) {
				$revlink = '<span class="history-deleted">'.$date.'</span>';
			}
		}
		
		return
			"<li> $difflink $revlink " . $this->skin->revUserLink( $rev ) . " " . $this->skin->revComment( $rev ) . "$del</li>";
	}
	
	/**
	 * @param File $file
	 * This can work for old or archived revisions
	 * @returns string
	 */	
	function uploadLine( $file ) {
		global $wgContLang, $wgTitle;
		
		$target = $this->page->getPrefixedText();
		$date = $wgContLang->timeanddate( $file->timestamp, true  );
	
		$del = '';
		// Special:Undelete for viewing archived images
		if( $this->deletetype=='fileid' ) {
			$undelete = SpecialPage::getTitleFor( 'Undelete' );
			$pageLink = $this->skin->makeKnownLinkObj( $undelete, $date, "target=$target&file=$file->key" );
		// Revisiondelete for viewing images
		} else {
			# Hidden files...
			if( $file->isDeleted(File::DELETED_FILE) ) {
				$del = ' <tt>' . wfMsgHtml( 'deletedrev' ) . '</tt>';
				if( !$file->userCan(File::DELETED_FILE) ) {
					$pageLink = $date;
				} else {
					$pageLink = $this->skin->makeKnownLinkObj( $wgTitle, $date, 
						"target=$target&file=$file->sha1.".$file->getExtension() );
				}
				$pageLink = '<span class="history-deleted">' . $pageLink . '</span>';
			# Regular files...
			} else {
				$url = $file->getUrlRel();
				$pageLink = "<a href=\"{$url}\">{$date}</a>";
			}
		}
		
		$data = wfMsgHtml( 'widthheight',
					$wgContLang->formatNum( $file->width ),
					$wgContLang->formatNum( $file->height ) ) .
			' (' . wfMsgHtml( 'nbytes', $wgContLang->formatNum( $file->size ) ) . ')';	
	
		return "<li> $pageLink " . $this->fileUserLink( $file ) . " $data " . $this->fileComment( $file ) . "$del</li>";
	}
	
	/**
	 * @param Array $event row
	 * @returns string
	 */
	function logLine( $event ) {
		global $wgContLang;

		$date = $wgContLang->timeanddate( $event->log_timestamp );
		$paramArray = LogPage::extractParams( $event->log_params );

		if( !LogViewer::userCan($event,LogViewer::DELETED_ACTION) ) {
			$action = '<span class="history-deleted">' . wfMsgHtml('rev-deleted-event') . '</span>';	
		} else {
			$title = Title::makeTitle( $event->log_namespace, $event->log_title );
			$action = LogPage::actionText( $event->log_type, $event->log_action, $title, $this->skin, $paramArray, true, true );
			if( $event->log_deleted & LogViewer::DELETED_ACTION )
				$action = '<span class="history-deleted">' . $action . '</span>';
		}
		return
			"<li>$date" . " " . $this->skin->logUserLink( $event ) . " $action " . $this->skin->logComment( $event ) . "</li>";
	}
	
	/**
	 * Generate a user link if the current user is allowed to view it
	 * @param ArchivedFile $file
	 * @param $isPublic, bool, show only if all users can see it
	 * @return string HTML
	 */
	function fileUserLink( $file, $isPublic = false ) {
		if( $file->isDeleted( File::DELETED_USER ) && $isPublic ) {
			$link = wfMsgHtml( 'rev-deleted-user' );
		} else if( $file->userCan( File::DELETED_USER ) ) {
			$link = $this->skin->userLink( $file->user, $file->userText );
		} else {
			$link = wfMsgHtml( 'rev-deleted-user' );
		}
		if( $file->isDeleted( File::DELETED_USER ) ) {
			return '<span class="history-deleted">' . $link . '</span>';
		}
		return $link;
	}
	
	/**
	 * Generate a user tool link cluster if the current user is allowed to view it
	 * @param ArchivedFile $file
	 * @param $isPublic, bool, show only if all users can see it
	 * @return string HTML
	 */
	function fileUserTools( $file, $isPublic = false ) {
		if( $file->isDeleted( Revision::DELETED_USER ) && $isPublic ) {
			$link = wfMsgHtml( 'rev-deleted-user' );
		} else if( $file->userCan( Revision::DELETED_USER ) ) {
			$link = $this->skin->userLink( $file->user, $file->userText ) .
			$this->userToolLinks( $file->user, $file->userText );
		} else {
			$link = wfMsgHtml( 'rev-deleted-user' );
		}
		if( $file->isDeleted( Revision::DELETED_USER ) ) {
			return '<span class="history-deleted">' . $link . '</span>';
		}
		return $link;
	}
	
	/**
	 * Wrap and format the given file's comment block, if the current
	 * user is allowed to view it.
	 *
	 * @param ArchivedFile $file
	 * @return string HTML
	 */
	function fileComment( $file, $isPublic = false ) {
		if( $file->isDeleted( File::DELETED_COMMENT ) && $isPublic ) {
			$block = ' ' . wfMsgHtml( 'rev-deleted-comment' );
		} else if( $file->userCan( File::DELETED_COMMENT ) ) {
			$block = $this->skin->commentBlock( $file->description );
		} else {
			$block = ' ' . wfMsgHtml( 'rev-deleted-comment' );
		}
		if( $file->isDeleted( File::DELETED_COMMENT ) ) {
			return "<span class=\"history-deleted\">$block</span>";
		}
		return $block;
	}
	
	/**
	 * @param WebRequest $request
	 */
	function submit( $request ) {
		$bitfield = $this->extractBitfield( $request );
		$comment = $request->getText( 'wpReason' );
		
		$this->target = $request->getText( 'target' );
		$this->title = Title::newFromURL( $this->target );
		
		if( $this->save( $bitfield, $comment, $this->title ) ) {
			$this->success( $request );
		} else if( $request->getCheck( 'oldid' ) || $request->getCheck( 'artimestamp' ) ) {
			return $this->showRevs( $request );
		} else if( $request->getCheck( 'logid' ) ) {
			return $this->showLogs( $request );
		} else if( $request->getCheck( 'oldimage' ) || $request->getCheck( 'fileid' ) ) {
			return $this->showImages( $request );
		}
	}
	
	function success( $request ) {
		global $wgOut;
		
		$wgOut->setPagetitle( wfMsgHtml( 'actioncomplete' ) );
		# Give a link to the log for this page
		$logtitle = SpecialPage::getTitleFor( 'Log' );
        $loglink = $this->skin->makeKnownLinkObj( $logtitle, wfMsgHtml( 'viewpagelogs' ),
			wfArrayToCGI( array('page' => $this->target ) ) );
		# Give a link to the page history	
		$histlink = $this->skin->makeKnownLinkObj( $this->title, wfMsgHtml( 'revhistory' ),
			wfArrayToCGI( array('action' => 'history' ) ) );
		# Link to deleted edits
		$undelete = SpecialPage::getTitleFor( 'Undelete' );
		$dellink = $this->skin->makeKnownLinkObj( $undelete, wfMsgHtml( 'undeleterevs' ),
			wfArrayToCGI( array('target' => $this->target) ) );
		# Logs themselves don't have histories or archived revisions
		if( !is_null($this->title) && $this->title->getNamespace() > -1)
			$wgOut->setSubtitle( '<p>'.$histlink.' / '.$loglink.' / '.$dellink.'</p>' );
		
		if( $this->deletetype=='logid' ) {
			$wgOut->addWikiText( wfMsgHtml('logdelete-success'), false );
			$this->showEvents( $request );
		} else if( $this->deletetype=='oldid' || $this->deletetype=='artimestamp' ) {
		  	$wgOut->addWikiText( wfMsgHtml('revdelete-success'), false );
		  	$this->showRevs( $request );
		} else if( $this->deletetype=='fileid' ) {
			$wgOut->addWikiText( wfMsgHtml('revdelete-success'), false );
		  	$this->showImages( $request );
		} else if( $this->deletetype=='oldimage' ) {
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
		
		// Don't allow simply locking the interface for no reason
		if( $bitfield == Revision::DELETED_RESTRICTED )
			$bitfield = 0;
		
		$deleter = new RevisionDeleter( $dbw );
		// By this point, only one of the below should be set
		if( isset($this->revisions) ) {
			return $deleter->setRevVisibility( $title, $this->revisions, $bitfield, $reason );
		} else if( isset($this->archrevs) ) {
			return $deleter->setArchiveVisibility( $title, $this->archrevs, $bitfield, $reason );
		} else if( isset($this->ofiles) ) {
			return $deleter->setOldImgVisibility( $title, $this->ofiles, $bitfield, $reason );
		} else if( isset($this->afiles) ) {
			return $deleter->setArchFileVisibility( $title, $this->afiles, $bitfield, $reason );
		} else if( isset($this->events) ) {
			return $deleter->setEventVisibility( $this->events, $bitfield, $reason );
		}
	}
}

/**
 * Implements the actions for Revision Deletion.
 * @addtogroup SpecialPage
 */
class RevisionDeleter {
	function __construct( $db ) {
		$this->dbw = $db;
	}
	
	/**
	 * @param $title, the page these events apply to
	 * @param array $items list of revision ID numbers
	 * @param int $bitfield new rev_deleted value
	 * @param string $comment Comment for log records
	 */
	function setRevVisibility( $title, $items, $bitfield, $comment ) {
		global $wgOut;
		
		$userAllowedAll = $success = true;
		$revIDs = array();
		$revCount = 0;
		// Run through and pull all our data in one query
		foreach( $items as $revid ) {
			$where[] = intval($revid);
		}
		$whereClause = 'rev_id IN(' . implode(',',$where) . ')';
		$result = $this->dbw->select( 'revision', '*',
			array( 'rev_page' => $title->getArticleID(), 
				$whereClause ),
			__METHOD__ );
		while( $row = $this->dbw->fetchObject( $result ) ) {
			$revObjs[$row->rev_id] = new Revision( $row );
		}
		// To work!
		foreach( $items as $revid ) {
			if( !isset($revObjs[$revid]) || $revObjs[$revid]->isCurrent() ) {
				$success = false;
				continue; // Must exist
			} else if( !$revObjs[$revid]->userCan(Revision::DELETED_RESTRICTED) ) {
    			$userAllowedAll=false; 
				continue;
			}
			// For logging, maintain a count of revisions
			if( $revObjs[$revid]->mDeleted != $bitfield ) {
				$revCount++;
				$revIDs[]=$revid;
				
			   	$this->updateRevision( $revObjs[$revid], $bitfield );
				$this->updateRecentChangesEdits( $revObjs[$revid], $bitfield, false );
			}
		}
		// Clear caches...
		// Don't log or touch if nothing changed
		if( $revCount > 0 ) {
			$this->updatePage( $title );
			$this->updateLog( $title, $revCount, $bitfield, $revObjs[$revid]->mDeleted, 
				$comment, $title, 'oldid', $revIDs );
		}
		// Where all revs allowed to be set?
		if( !$userAllowedAll ) {
			//FIXME: still might be confusing???
			$wgOut->permissionRequired( 'hiderevision' );
			return false;
		}
		
		return $success;
	}
	
	 /**
	 * @param $title, the page these events apply to
	 * @param array $items list of revision ID numbers
	 * @param int $bitfield new rev_deleted value
	 * @param string $comment Comment for log records
	 */
	function setArchiveVisibility( $title, $items, $bitfield, $comment ) {
		global $wgOut;
		
		$userAllowedAll = $success = true;
		$count = 0; 
		$Id_set = array();
		// Run through and pull all our data in one query
		foreach( $items as $timestamp ) {
			$where[] = $this->dbw->addQuotes( $timestamp );
		}
		$whereClause = 'ar_timestamp IN(' . implode(',',$where) . ')';
		$result = $this->dbw->select( 'archive', '*',
			array( 'ar_namespace' => $title->getNamespace(),
				'ar_title' => $title->getDBKey(), 
					$whereClause ),
			__METHOD__ );
		while( $row = $this->dbw->fetchObject( $result ) ) {
			$revObjs[$row->ar_timestamp] = new Revision( array(
			'page'       => $title->getArticleId(),
			'id'         => $row->ar_rev_id,
			'text'       => $row->ar_text_id,
			'comment'    => $row->ar_comment,
			'user'       => $row->ar_user,
			'user_text'  => $row->ar_user_text,
			'timestamp'  => $row->ar_timestamp,
			'minor_edit' => $row->ar_minor_edit,
			'text_id'    => $row->ar_text_id,
			'deleted'    => $row->ar_deleted,
			'len'        => $row->ar_len) );
		}
		// To work!
		foreach( $items as $timestamp ) {
			// This will only select the first revision with this timestamp.
			// Since they are all selected/deleted at once, we can just check the 
			// permissions of one. UPDATE is done via timestamp, so all revs are set.
			if( !is_object($revObjs[$timestamp]) ) {
				$success = false;
				continue; // Must exist
			} else if( !$revObjs[$timestamp]->userCan(Revision::DELETED_RESTRICTED) ) {
    			$userAllowedAll=false;
				continue;
			}
			// Which revisions did we change anything about?
			if( $revObjs[$timestamp]->mDeleted != $bitfield ) {
			   $Id_set[]=$timestamp;
			   $count++;
			   
			   $this->updateArchive( $revObjs[$timestamp], $bitfield );
			}
		}
		// For logging, maintain a count of revisions
		if( $count > 0 ) {
			$this->updateLog( $title, $count, $bitfield, $revObjs[$timestamp]->mDeleted, 
				$comment, $title, 'artimestamp', $Id_set );
		}
		// Where all revs allowed to be set?
		if( !$userAllowedAll ) {
			$wgOut->permissionRequired( 'hiderevision' ); 
			return false;
		}
		
		return $success;
	}
	
	 /**
	 * @param $title, the page these events apply to
	 * @param array $items list of revision ID numbers
	 * @param int $bitfield new rev_deleted value
	 * @param string $comment Comment for log records
	 */
	function setOldImgVisibility( $title, $items, $bitfield, $comment ) {
		global $wgOut;
		
		$userAllowedAll = $success = true;
		$count = 0; 
		$set = array();
		// Run through and pull all our data in one query
		foreach( $items as $timestamp ) {
			$where[] = $this->dbw->addQuotes( $timestamp.'!'.$title->getDbKey() );
		}
		$whereClause = 'oi_archive_name IN(' . implode(',',$where) . ')';
		$result = $this->dbw->select( 'oldimage', '*',
			array( 'oi_name' => $title->getDbKey(),
				$whereClause ),
			__METHOD__ );
		while( $row = $this->dbw->fetchObject( $result ) ) {
			$filesObjs[$row->oi_archive_name] = RepoGroup::singleton()->getLocalRepo()->newFileFromRow( $row );
			$filesObjs[$row->oi_archive_name]->user = $row->oi_user;
			$filesObjs[$row->oi_archive_name]->userText = $row->oi_user_text;
		}
		// To work!
		foreach( $items as $timestamp ) {
			$archivename = $timestamp.'!'.$title->getDbKey();
			if( !isset($filesObjs[$archivename]) ) {
				$success = false;
				continue; // Must exist
			} else if( !$filesObjs[$archivename]->userCan(File::DELETED_RESTRICTED) ) {
    			$userAllowedAll=false;
				continue;
			}
			
			$transaction = true;
			// Which revisions did we change anything about?
			if( $filesObjs[$archivename]->deleted != $bitfield ) {
				$count++;
				
				$this->dbw->begin();
				$this->updateOldFiles( $filesObjs[$archivename], $bitfield );
				// If this image is currently hidden...
				if( $filesObjs[$archivename]->deleted & File::DELETED_FILE ) {
					if( $bitfield & File::DELETED_FILE ) {
						# Leave it alone if we are not changing this...
						$set[]=$name;
						$transaction = true;
					} else {
						# We are moving this out
						$transaction = $this->makeOldImagePublic( $filesObjs[$archivename] );
						$set[]=$transaction;
					}
				// Is it just now becoming hidden?
				} else if( $bitfield & File::DELETED_FILE ) {
					$transaction = $this->makeOldImagePrivate( $filesObjs[$archivename] );
					$set[]=$transaction;
				} else {
					$set[]=$name;
				}
				// If our file operations fail, then revert back the db
				if( $transaction==false ) {
					$this->dbw->rollback();
					return false;
				}
				$this->dbw->commit();
				// Purge page/history
				$filesObjs[$archivename]->purgeCache();
				$filesObjs[$archivename]->purgeHistory();
				// Invalidate cache for all pages using this file
				$update = new HTMLCacheUpdate( $oimage->getTitle(), 'imagelinks' );
				$update->doUpdate();
			}
		}
		
		// Log if something was changed
		if( $count > 0 ) {
			$this->updateLog( $title, $count, $bitfield, $filesObjs[$archivename]->deleted, 
				$comment, $title, 'oldimage', $set );
		}
		// Where all revs allowed to be set?
		if( !$userAllowedAll ) {
			$wgOut->permissionRequired( 'hiderevision' ); 
			return false;
		}
		
		return $success;
	}
	
	 /**
	 * @param $title, the page these events apply to
	 * @param array $items list of revision ID numbers
	 * @param int $bitfield new rev_deleted value
	 * @param string $comment Comment for log records
	 */
	function setArchFileVisibility( $title, $items, $bitfield, $comment ) {
		global $wgOut;
		
		$userAllowedAll = $success = true;
		$count = 0; 
		$Id_set = array();
		
		// Run through and pull all our data in one query
		foreach( $items as $id ) {
			$where[] = intval($id);
		}
		$whereClause = 'fa_id IN(' . implode(',',$where) . ')';
		$result = $this->dbw->select( 'filearchive', '*',
			array( 'fa_name' => $title->getDbKey(),
				$whereClause ),
			__METHOD__ );
		while( $row = $this->dbw->fetchObject( $result ) ) {
			$filesObjs[$row->fa_id] = ArchivedFile::newFromRow( $row );
		}
		// To work!
		foreach( $items as $fileid ) {
			if( !isset($filesObjs[$fileid]) ) {
				$success = false;
				continue; // Must exist
			} else if( !$filesObjs[$fileid]->userCan(File::DELETED_RESTRICTED) ) {
    			$userAllowedAll=false;
				continue;
			}
			// Which revisions did we change anything about?
			if( $filesObjs[$fileid]->deleted != $bitfield ) {
			   $Id_set[]=$fileid;
			   $count++;
			   
			   $this->updateArchFiles( $filesObjs[$fileid], $bitfield );
			}
		}
		// Log if something was changed
		if( $count > 0 ) {
			$this->updateLog( $title, $count, $bitfield, $comment, 
				$filesObjs[$fileid]->deleted, $title, 'fileid', $Id_set );
		}
		// Where all revs allowed to be set?
		if( !$userAllowedAll ) {
			$wgOut->permissionRequired( 'hiderevision' );
			return false;
		}
		
		return $success;
	}

	/**
	 * @param $title, the page these events apply to
	 * @param array $items list of log ID numbers
	 * @param int $bitfield new log_deleted value
	 * @param string $comment Comment for log records
	 */
	function setEventVisibility( $items, $bitfield, $comment ) {
		global $wgOut;
		
		$userAllowedAll = $success = true;
		$logs_count = array(); 
		$logs_Ids = array();
		
		// Run through and pull all our data in one query
		foreach( $items as $logid ) {
			$where[] = intval($logid);
		}
		$whereClause = 'log_id IN(' . implode(',',$where) . ')';
		$result = $this->dbw->select( 'logging', '*', 
			array( $whereClause ),
			__METHOD__ );
		while( $row = $this->dbw->fetchObject( $result ) ) {
			$logRows[$row->log_id] = $row;
		}
		// To work!
		foreach( $items as $logid ) {
			if( !isset($logRows[$logid]) ) {
				$success = false;
				continue; // Must exist
			} else if( !LogViewer::userCan($logRows[$logid], Revision::DELETED_RESTRICTED)
				 || $logRows[$logid]->log_type=='oversight' ) {
			// Don't hide from oversight log!!!
    			$userAllowedAll=false;
    			continue;
			}
			$logtype = $logRows[$logid]->log_type;
			// For logging, maintain a count of events per log type
			if( !isset( $logs_count[$logtype] ) ) {
				$logs_count[$logtype]=0;
				$logs_Ids[$logtype]=array();
			}
			// Which logs did we change anything about?
			if( $logRows[$logid]->log_deleted != $bitfield ) {
				$logs_Ids[$logtype][]=$logid;
				$logs_count[$logtype]++;
			   
			   	$this->updateLogs( $logRows[$logid], $bitfield );
				$this->updateRecentChangesLog( $logRows[$logid], $bitfield, true );
			}
		}
		foreach( $logs_count as $logtype => $count ) {
			//Don't log or touch if nothing changed
			if( $count > 0 ) {
				$target = SpecialPage::getTitleFor( 'Log', $logtype );
				$this->updateLog( $target, $count, $bitfield, $logRows[$logid]->log_deleted, 
				$comment, $target, 'logid', $logs_Ids[$logtype] );
			}
		}
		// Where all revs allowed to be set?
		if( !$userAllowedAll ) {
			$wgOut->permissionRequired( 'hiderevision' ); 
			return false;
		}
		
		return $success;
	}

	/**
	 * Moves an image to a safe private location
	 * Caller is responsible for clearing caches
	 * @param File $oimage
	 * @returns string, timestamp on success, false on failure
	 */	
	function makeOldImagePrivate( $oimage ) {
		global $wgFileStore, $wgUseSquid;
	
		$transaction = new FSTransaction();
		if( !FileStore::lock() ) {
			wfDebug( __METHOD__.": failed to acquire file store lock, aborting\n" );
			return false;
		}
		$oldpath = $oimage->getArchivePath() . DIRECTORY_SEPARATOR . $oimage->archive_name;
		// Dupe the file into the file store
		if( file_exists( $oldpath ) ) {
			// Is our directory configured?
			if( $store = FileStore::get( 'hidden' ) ) {
				if( !$oimage->sha1 )
					$oimage->upgradeRow();
				
				$key = $oimage->sha1.'.'.$oimage->getExtension();
				$transaction->add( $store->insert( $key, $oldpath, FileStore::DELETE_ORIGINAL ) );
			} else {
				$group = null;
				$key = null;
				$transaction = false; // Return an error and do nothing
			}
		} else {
			wfDebug( __METHOD__." deleting already-missing '$oldpath'; moving on to database\n" );
			$group = null;
			$key = '';
			$transaction = new FSTransaction(); // empty
		}

		if( $transaction === false ) {
			// Fail to restore?
			wfDebug( __METHOD__.": import to file store failed, aborting\n" );
			throw new MWException( "Could not archive and delete file $oldpath" );
			return false;
		}
		
		wfDebug( __METHOD__.": set db items, applying file transactions\n" );
		$transaction->commit();
		FileStore::unlock();
		
		$m = explode('!',$oimage->archive_name,2);
		$timestamp = $m[0];
		
		return $timestamp;
	}

	/**
	 * Moves an image from a safe private location
	 * Caller is responsible for clearing caches
	 * @param File $oimage
	 * @returns string, timestamp on success, false on failure
	 */		
	function makeOldImagePublic( $oimage ) {
		global $wgFileStore;
	
		$transaction = new FSTransaction();
		if( !FileStore::lock() ) {
			wfDebug( __METHOD__." could not acquire filestore lock\n" );
			return false;
		}
		
		$store = FileStore::get( 'hidden' );
		if( !$store ) {
			wfDebug( __METHOD__.": skipping row with no file.\n" );
			return false;
		}
		
		$key = $oimage->sha1.'.'.$oimage->getExtension();
		$destDir = $oimage->getArchivePath();
		if( !is_dir( $destDir ) ) {
			wfMkdirParents( $destDir );
		}
		$destPath = $destDir . DIRECTORY_SEPARATOR . $oimage->archive_name;
		// Check if any other stored revisions use this file;
		// if so, we shouldn't remove the file from the hidden
		// archives so they will still work.
		$useCount = $this->dbw->selectField( 'oldimage','COUNT(*)',
			array( 'oi_sha1' => $oimage->sha1,
				'oi_deleted & '.File::DELETED_FILE => File::DELETED_FILE ),
			__METHOD__ );
			
		if( $useCount == 0 ) {
			wfDebug( __METHOD__.": nothing else using {$oimage->sha1}, will deleting after\n" );
			$flags = FileStore::DELETE_ORIGINAL;
		} else {
			$flags = 0;
		}
		$transaction->add( $store->export( $key, $destPath, $flags ) );
		
		wfDebug( __METHOD__.": set db items, applying file transactions\n" );
		$transaction->commit();
		FileStore::unlock();
		
		$m = explode('!',$oimage->archive_name,2);
		$timestamp = $m[0];
		
		return $timestamp;
	}
	
	/**
	 * Moves an image from a safe private location to deleted archives
	 * Groups should be 'deleted' and 'hidden'
	 * @param File $oimage
	 * @param string $group1, old group
	 * @param string $group2, new group
	 * @returns bool, success
	 */	
	function moveImageFromFileRepos( $oimage, $group1, $group2 ) {
		global $wgFileStore;
		
		$transaction = new FSTransaction();
		if( !FileStore::lock() ) {
			wfDebug( __METHOD__." could not acquire filestore lock\n" );
			return false;
		}
		
		$storeOld = FileStore::get( $group1 );
		if( !$storeOld ) {
			wfDebug( __METHOD__.": skipping row with no file.\n" );
			return false;
		}
		$key = $oimage->sha1.'.'.$oimage->getExtension();
		
		$oldPath = $storeOld->filePath( $key );	
		// Check if any other stored revisions use this file;
		// if so, we shouldn't remove the file from the hidden
		// archives so they will still work.
		if( $group1=='hidden' ) {
			$useCount = $this->dbw->selectField( 'oldimage','COUNT(*)',
				array( 'oi_sha1' => $oimage->sha1 ),
				__METHOD__ );
		} else if( $group1=='deleted' ) {
			$useCount = $this->dbw->selectField( 'filearchive','COUNT(*)',
				array( 'fa_storage_key' => $key, 'fa_storage_group' => 'deleted' ),
				__METHOD__ );
		}
			
		if( $useCount == 0 ) {
			wfDebug( __METHOD__.": nothing else using $key, will deleting after\n" );
			$flags = FileStore::DELETE_ORIGINAL;
		} else {
			$flags = 0;
		}
		
		$storeNew = FileStore::get( $group2 );
		$transaction->add( $storeNew->insert( $key, $oldPath, $flags ) );
		
		wfDebug( __METHOD__.": set db items, applying file transactions\n" );
		$transaction->commit();
		FileStore::unlock();
		
		return true;
	}
	
	/**
	 * Update the revision's rev_deleted field
	 * @param Revision $rev
	 * @param int $bitfield new rev_deleted bitfield value
	 */
	function updateRevision( $rev, $bitfield ) {
		$this->dbw->update( 'revision',
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
		$this->dbw->update( 'archive',
			array( 'ar_deleted' => $bitfield ),
			array( 'ar_rev_id' => $rev->getId() ),
			'RevisionDeleter::updateArchive' );
	}

	/**
	 * Update the images's oi_deleted field
	 * @param File $oimage
	 * @param int $bitfield new rev_deleted bitfield value
	 */
	function updateOldFiles( $oimage, $bitfield ) {
		$this->dbw->update( 'oldimage',
			array( 'oi_deleted' => $bitfield ),
			array( 'oi_archive_name' => $oimage->archive_name ),
			'RevisionDeleter::updateOldFiles' );
	}
	
	/**
	 * Update the images's fa_deleted field
	 * @param ArchivedFile $file
	 * @param int $bitfield new rev_deleted bitfield value
	 */
	function updateArchFiles( $file, $bitfield ) {
		$this->dbw->update( 'filearchive',
			array( 'fa_deleted' => $bitfield ),
			array( 'fa_id' => $file->id ),
			'RevisionDeleter::updateArchFiles' );
	}	
	
	/**
	 * Update the logging log_deleted field
	 * @param Row $event
	 * @param int $bitfield new rev_deleted bitfield value
	 */
	function updateLogs( $event, $bitfield ) {
		$this->dbw->update( 'logging',
			array( 'log_deleted' => $bitfield ),
			array( 'log_id' => $event->log_id ),
			'RevisionDeleter::updateLogs' );
	}	
	
	/**
	 * Update the revision's recentchanges record if fields have been hidden
	 * @param Revision $rev
	 * @param int $bitfield new rev_deleted bitfield value
	 */
	function updateRecentChangesEdits( $rev, $bitfield ) {
		$this->dbw->update( 'recentchanges',
			array( 'rc_deleted' => $bitfield,
				   'rc_patrolled' => 1 ),
			array( 'rc_this_oldid' => $rev->getId() ),
			'RevisionDeleter::updateRecentChangesEdits' );
	}
	
	/**
	 * Update the revision's recentchanges record if fields have been hidden
	 * @param Row $event
	 * @param int $bitfield new rev_deleted bitfield value
	 */
	function updateRecentChangesLog( $event, $bitfield ) {
		$this->dbw->update( 'recentchanges',
			array( 'rc_deleted' => $bitfield,
				   'rc_patrolled' => 1 ),
			array( 'rc_logid' => $event->log_id ),
			'RevisionDeleter::updateRecentChangesLog' );
	}
	
	/**
	 * Touch the page's cache invalidation timestamp; this forces cached
	 * history views to refresh, so any newly hidden or shown fields will
	 * update properly.
	 * @param Title $title
	 */
	function updatePage( $title ) {
		$title->invalidateCache();
		$title->purgeSquid();
		
		// Extensions that require referencing previous revisions may need this
		wfRunHooks( 'ArticleRevisionVisiblitySet', array( &$title ) );
	}
	
	/**
	 * Record a log entry on the action
	 * @param Title $title, page where item was removed from
	 * @param int $count the number of revisions altered for this page
	 * @param int $nbitfield the new _deleted value
	 * @param int $obitfield the old _deleted value
	 * @param string $comment
	 * @param Title $target, the relevant page
	 * @param string $param, URL param
	 * @param Array $items
	 */
	function updateLog( $title, $count, $nbitfield, $obitfield, $comment, $target, $param, $items = array() ) {
		// Put things hidden from sysops in the oversight log
		$logtype = ( ($nbitfield | $obitfield) & Revision::DELETED_RESTRICTED ) ? 'oversight' : 'delete';
		$log = new LogPage( $logtype );
		// FIXME: do this better
		if( $param=='logid' ) {
			$params = array( implode( ',', $items) );
    		$reason = wfMsgExt('logdelete-logaction', array('parsemag'), $count, $nbitfield );
			if($comment) $reason .= ": $comment";
			$log->addEntry( 'event', $title, $reason, $params );
		} else {
			// Add params for effected page and ids
			$params = array( $target->getPrefixedText(), $param, implode( ',', $items) );
    		$reason = wfMsgExt('revdelete-logaction', array('parsemag'), $count, $nbitfield );
			if($comment) $reason .= ": $comment";
			$log->addEntry( 'revision', $title, $reason, $params );
		}
	}
}
