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

	private $oldfile = null;
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
		global $wgOut, $wgRequest, $wgUser;
		$this->setHeaders();

		if( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		} elseif( !$wgUser->isLoggedIn() ) {
			$wgOut->showErrorPage( 'uploadnologin', 'uploadnologintext' );
			return;
		} elseif( !$wgUser->isAllowed( 'delete' ) ) {
			$wgOut->permissionRequired( 'delete' );
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
		if( $this->oldimage )
			$this->oldfile = RepoGroup::singleton()->getLocalRepo()->newFromArchiveName( $this->title, $this->oldimage );
		
		if( !$this->haveDeletableFile() ) {
			$wgOut->addHtml( $this->prepareMessage( 'filedelete-nofile' ) );
			$wgOut->addReturnTo( $this->title );
			return;
		}
		
		// Perform the deletion if appropriate
		if( $wgRequest->wasPosted() && $wgUser->matchEditToken( $token, $this->oldimage ) ) {
			$this->DeleteReasonList = $wgRequest->getText( 'wpDeleteReasonList' );
			$this->DeleteReason = $wgRequest->getText( 'wpReason' );
			$reason = $this->DeleteReasonList;
			if ( $reason != 'other' && $this->DeleteReason != '') {
				// Entry from drop down menu + additional comment
				$reason .= ': ' . $this->DeleteReason;
			} elseif ( $reason == 'other' ) {
				$reason = $this->DeleteReason;
			}
			if( $this->oldimage ) {
				$status = $this->file->deleteOld( $this->oldimage, $reason );
				if( $status->ok ) {
					// Need to do a log item
					$log = new LogPage( 'delete' );
					$logComment = wfMsg( 'deletedrevision', $this->oldimage );
					if( trim( $reason ) != '' )
						$logComment .= ": {$reason}";
					$log->addEntry( 'delete', $this->title, $logComment );
				}
			} else {
				$status = $this->file->delete( $reason );
				if( $status->ok ) {
					// Need to delete the associated article
					$article = new Article( $this->title );
					$article->doDeleteArticle( $reason );
				}
			}
			if( !$status->isGood() )
				$wgOut->addWikiText( $status->getWikiText( 'filedeleteerror-short', 'filedeleteerror-long' ) );
			if( $status->ok ) {
				$wgOut->setPagetitle( wfMsg( 'actioncomplete' ) );
				$wgOut->addHtml( $this->prepareMessage( 'filedelete-success' ) );
				// Return to the main page if we just deleted all versions of the
				// file, otherwise go back to the description page
				$wgOut->addReturnTo( $this->oldimage ? $this->title : Title::newMainPage() );
			}
			return;
		}
		
		$this->showForm();
		$this->showLogEntries();
	}

	/**
	 * Show the confirmation form
	 */
	private function showForm() {
		global $wgOut, $wgUser, $wgRequest, $wgContLang;
		$align = $wgContLang->isRtl() ? 'left' : 'right';

		$form = Xml::openElement( 'form', array( 'method' => 'post', 'action' => $this->getAction() ) ) .
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, wfMsg( 'filedelete-legend' ) ) .
			Xml::hidden( 'wpEditToken', $wgUser->editToken( $this->oldimage ) ) .
			$this->prepareMessage( 'filedelete-intro' ) .
			Xml::openElement( 'table' ) .
			"<tr>
				<td align=$align>" .
					Xml::label( wfMsg( 'filedelete-comment' ), 'wpDeleteReasonList' ) .
				"</td>
				<td>" .
					Xml::listDropDown( 'wpDeleteReasonList',
						wfMsgForContent( 'filedelete-reason-dropdown' ), 
						wfMsgForContent( 'filedelete-reason-otherlist' ), '', 'wpReasonDropDown', 1 ) .
				"</td>
			</tr>
			<tr>
				<td align=$align>" .
					Xml::label( wfMsg( 'filedelete-otherreason' ), 'wpReason' ) .
				"</td>
				<td>" .
					Xml::input( 'wpReason', 60, $wgRequest->getText( 'wpReason' ), array( 'type' => 'text', 'maxlength' => '255', 'tabindex' => '1' ) ) .
				"</td>
			</tr>
			<tr>
				<td></td>
				<td>" .
					Xml::submitButton( wfMsg( 'filedelete-submit' ), array( 'name' => 'mw-filedelete-submit', 'id' => 'mw-filedelete-submit' ) ) .
				"</td>
			</tr>" .
			Xml::closeElement( 'table' ) .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' );

		$wgOut->addHtml( $form );
	}

	/**
	 * Show deletion log fragments pertaining to the current file
	 */
	private function showLogEntries() {
		global $wgOut;
		$wgOut->addHtml( '<h2>' . htmlspecialchars( LogPage::logName( 'delete' ) ) . "</h2>\n" );
		$reader = new LogViewer(
			new LogReader(
				new FauxRequest(
					array(
						'type' => 'delete',
						'page' => $this->title->getPrefixedText(),
					)
				)
			)
		);
		$reader->showList( $wgOut );		
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
				"{$message}-old", # To ensure grep will find them: 'filedelete-intro-old', 'filedelete-nofile-old', 'filedelete-success-old'
				'parse',
				$this->title->getText(),
				$wgLang->date( $this->getTimestamp(), true ),
				$wgLang->time( $this->getTimestamp(), true ),
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
		global $wgOut, $wgUser;
		$wgOut->setPageTitle( wfMsg( 'filedelete', $this->title->getText() ) );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->setSubtitle( wfMsg( 'filedelete-backlink', $wgUser->getSkin()->makeKnownLinkObj( $this->title ) ) );
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
		return $this->oldimage
			? $this->oldfile && $this->oldfile->exists() && $this->oldfile->isLocal()
			: $this->file && $this->file->exists() && $this->file->isLocal();
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
		return $this->oldfile->getTimestamp();
	}
	
}
