<?php

/**
 * File reversion user interface
 *
 * @addtogroup Media
 * @author Rob Church <robchur@gmail.com>
 */
class FileRevertForm {

	private $title = null;
	private $file = null;
	private $oldimage = '';
	
	/**
	 * Constructor
	 *
	 * @param File $file File we're reverting
	 */
	public function __construct( $file ) {
		$this->title = $file->getTitle();
		$this->file = $file;
	}
	
	/**
	 * Fulfil the request; shows the form or reverts the file,
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
		} elseif( !$this->title->userCan( 'edit' ) ) {
			// The standard read-only thing doesn't make a whole lot of sense
			// here; surely it should show the image or something? -- RC
			$article = new Article( $this->title );
			$wgOut->readOnlyPage( $article->getContent(), true );
			return;
		} elseif( $wgUser->isBlocked() ) {
			$wgOut->blockedPage();
			return;
		}
		
		$this->oldimage = $wgRequest->getText( 'oldimage' );
		$token = $wgRequest->getText( 'wpEditToken' );
		if( !$this->isValidOldSpec() ) {
			$wgOut->showUnexpectedValueError( 'oldimage', htmlspecialchars( $this->oldimage ) );
			return;
		}
		
		if( !$this->haveOldVersion() ) {
			$wgOut->addHtml( wfMsgExt( 'filerevert-badversion', 'parse' ) );
			$wgOut->returnToMain( false, $this->title );
			return;
		}
		
		// Perform the reversion if appropriate
		if( $wgRequest->wasPosted() && $wgUser->matchEditToken( $token, $this->oldimage ) ) {
			$source = $this->file->getArchiveVirtualUrl( $this->oldimage );
			$comment = $wgRequest->getText( 'wpComment' );
			// TODO: Preserve file properties from database instead of reloading from file
			$status = $this->file->upload( $source, $comment, $comment );
			if( $status->isGood() ) {
				$wgOut->addHtml( wfMsgExt( 'filerevert-success', 'parse', $this->title->getText(),
					$wgLang->date( $this->getTimestamp(), true ),
					$wgLang->time( $this->getTimestamp(), true ),
					$wgServer . $this->file->getArchiveUrl( $this->oldimage ) ) );
				$wgOut->returnToMain( false, $this->title );
			} else {
				$wgOut->addWikiText( $status->getWikiText() );
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
		global $wgOut, $wgUser, $wgRequest, $wgLang, $wgContLang, $wgServer;
		$timestamp = $this->getTimestamp();

		$form  = Xml::openElement( 'form', array( 'method' => 'post', 'action' => $this->getAction() ) );
		$form .= Xml::hidden( 'wpEditToken', $wgUser->editToken( $this->oldimage ) );
		$form .= '<fieldset><legend>' . wfMsgHtml( 'filerevert-legend' ) . '</legend>';
		$form .= wfMsgExt( 'filerevert-intro', 'parse', $this->title->getText(),
			$wgLang->date( $timestamp, true ), $wgLang->time( $timestamp, true ), $wgServer . $this->file->getArchiveUrl( $this->oldimage ) );
		$form .= '<p>' . Xml::inputLabel( wfMsg( 'filerevert-comment' ), 'wpComment', 'wpComment',
			60, wfMsgForContent( 'filerevert-defaultcomment',
			$wgContLang->date( $timestamp, false, false ), $wgContLang->time( $timestamp, false, false ) ) ) . '</p>';
		$form .= '<p>' . Xml::submitButton( wfMsg( 'filerevert-submit' ) ) . '</p>';
		$form .= '</fieldset>';
		$form .= '</form>';
		
		$wgOut->addHtml( $form );
	}
	
	/**
	 * Set headers, titles and other bits
	 */
	private function setHeaders() {
		global $wgOut;
		$wgOut->setPageTitle( wfMsg( 'filerevert', $this->title->getText() ) );
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
	 * Does the provided `oldimage` value correspond
	 * to an existing, local, old version of this file?
	 *
	 * @return bool
	 */
	private function haveOldVersion() {
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
		$q[] = 'action=revert';
		$q[] = 'oldimage=' . urlencode( $this->oldimage );
		return $this->title->getLocalUrl( implode( '&', $q ) );
	}
	
	/**
	 * Extract the timestamp of the old version
	 *
	 * @return string
	 */
	private function getTimestamp() {
		static $timestamp = false;
		if( $timestamp === false ) {
			$file = RepoGroup::singleton()->getLocalRepo()->newFromArchiveName( $this->title, $this->oldimage );
			$timestamp = $file->getTimestamp();
		}
		return $timestamp;
	}
	
}