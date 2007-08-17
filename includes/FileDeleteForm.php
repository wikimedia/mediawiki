<?php

/**
 * File deletion user interface
 *
 * @addtogroup Media
 * @author Rob Church <robchur@gmail.com>
 */
class FileDeleteForm {

	private $title = null;
	private $file = null;
	private $oldimage = '';
	
	/**
	 * Constructor
	 *
	 * @param File $file File we're deleting
	 */
	public function __construct( $file ) {
		$this->title = $file->getTitle();
		$this->file = $file;
	}
	
	/**
	 * Fulfil the request; shows the form or deletes the file,
	 * pending authentication, confirmation, etc.
	 */
	public function execute() {
		global $wgOut, $wgRequest, $wgUser, $wgLang, $wgServer;
		$this->setHeaders();

		if( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		} elseif( !$wgUser->isLoggedIn() ) {
			$wgOut->showErrorPage( 'uploadnologin', 'uploadnologintext' );
			return;
		} elseif( !$wgUser->isAllowed( 'delete' ) ) {
			$wgOut->permissionError( 'delete' );
			return;
		} elseif( $wgUser->isBlocked() ) {
			$wgOut->blockedPage();
			return;
		}
		
		$this->oldimage = $wgRequest->getText( 'oldimage', false );
		$token = $wgRequest->getText( 'wpEditToken' );
		if( $this->oldimage && !$this->isValidOldSpec() ) {
			$wgOut->showUnexpectedValueError( 'oldimage', htmlspecialchars( $this->oldimage ) );
			return;
		}
		
		if( !$this->haveDeletableFile() ) {
			$wgOut->addHtml( $this->prepareMessage( 'filedelete-nofile' ) );
			$wgOut->addReturnTo( $this->title );
			return;
		}
		
		// Don't allow accidental deletion of a single file revision
		// if this is, in fact, the current revision; things might break
		if( $this->oldimage && $this->file->getTimestamp() == $this->getTimestamp() ) {
			$wgOut->addHtml( wfMsgExt( 'filedelete-iscurrent', 'parse' ) );
			$wgOut->addReturnTo( $this->title );
			return;
		}
		
		// Perform the deletion if appropriate
		if( $wgRequest->wasPosted() && $wgUser->matchEditToken( $token, $this->oldimage ) ) {
			$comment = $wgRequest->getText( 'wpComment' );
			if( $this->oldimage ) {
				$status = $this->file->deleteOld( $this->oldimage, $comment );
				if( $status->ok ) {
					// Need to do a log item
					$log = new LogPage( 'delete' );
					$log->addEntry( 'delete', $this->title, wfMsg( 'deletedrevision' , $this->oldimage ) );
				}
			} else {
				$status = $this->file->delete( $comment );
				if( $status->ok ) {
					// Need to delete the associated article
					$article = new Article( $this->title );
					$article->doDeleteArticle( $comment );
				}
			}
			if( !$status->isGood() )
				$wgOut->addWikiText( $status->getWikiText( 'filedeleteerror-short', 'filedeleteerror-long' ) );
			if( $status->ok ) {
				$wgOut->addHtml( $this->prepareMessage( 'filedelete-success' ) );
				// Return to the main page if we just deleted all versions of the
				// file, otherwise go back to the description page
				$wgOut->addReturnTo( $this->oldimage ? $this->title : Title::newMainPage() );
			}
			return;
		}
		
		// Show the form
		$this->showForm();		
	}
	
	/**
	 * Show the confirmation form
	 */
	private function showForm() {
		global $wgOut, $wgUser;
		
		$form  = Xml::openElement( 'form', array( 'method' => 'post', 'action' => $this->getAction() ) );
		$form .= Xml::hidden( 'wpEditToken', $wgUser->editToken( $this->oldimage ) );
		$form .= '<fieldset><legend>' . wfMsgHtml( 'filedelete-legend' ) . '</legend>';
		$form .= $this->prepareMessage( 'filedelete-intro' );
		
		$form .= '<p>' . Xml::inputLabel( wfMsg( 'filedelete-comment' ), 'wpComment', 'wpComment', 60 ) . '</p>';
		$form .= '<p>' . Xml::submitButton( wfMsg( 'filedelete-submit' ) ) . '</p>';
		$form .= '</fieldset>';
		$form .= '</form>';
		
		$wgOut->addHtml( $form );
	}
	
	/**
	 * Prepare a message referring to the file being deleted,
	 * showing an appropriate message depending upon whether
	 * it's a current file or an old version
	 *
	 * @param string $message Message base
	 * @return string
	 */
	private function prepareMessage( $message ) {
		global $wgLang, $wgServer;
		if( $this->oldimage ) {
			return wfMsgExt(
				"{$message}-old",
				'parse',
				$this->title->getText(),
				$wgLang->date( $this->getTimestamp() ),
				$wgLang->time( $this->getTimestamp() ),
				$wgServer . $this->file->getArchiveUrl( $this->oldimage )
			);
		} else {
			return wfMsgExt(
				$message,
				'parse',
				$this->title->getText()
			);
		}
	}
	
	/**
	 * Set headers, titles and other bits
	 */
	private function setHeaders() {
		global $wgOut;
		$wgOut->setPageTitle( wfMsg( 'filedelete', $this->title->getText() ) );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
	}
	
	/**
	 * Is the provided `oldimage` value valid?
	 *
	 * @return bool
	 */
	private function isValidOldSpec() {
		return strlen( $this->oldimage ) >= 16
			&& strpos( $this->oldimage, '/' ) === false
			&& strpos( $this->oldimage, '\\' ) === false;
	}
	
	/**
	 * Could we delete the file specified? If an `oldimage`
	 * value was provided, does it correspond to an
	 * existing, local, old version of this file?
	 *
	 * @return bool
	 */
	private function haveDeletableFile() {
		$file = wfFindFile( $this->title, $this->oldimage );
		return $file && $file->exists() && $file->isLocal();
	}
	
	/**
	 * Prepare the form action
	 *
	 * @return string
	 */
	private function getAction() {
		$q = array();
		$q[] = 'action=delete';
		if( $this->oldimage )
			$q[] = 'oldimage=' . urlencode( $this->oldimage );
		return $this->title->getLocalUrl( implode( '&', $q ) );
	}
	
	/**
	 * Extract the timestamp of the old version
	 *
	 * @return string
	 */
	private function getTimestamp() {
		return substr( $this->oldimage, 0, 14 );
	}
	
}