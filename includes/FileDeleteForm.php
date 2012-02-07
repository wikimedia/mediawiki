<?php

/**
 * File deletion user interface
 *
 * @ingroup Media
 * @author Rob Church <robchur@gmail.com>
 */
class FileDeleteForm {

	/**
	 * @var Title
	 */
	private $title = null;

	/**
	 * @var File
	 */
	private $file = null;

	/**
	 * @var File
	 */
	private $oldfile = null;
	private $oldimage = '';

	/**
	 * Constructor
	 *
	 * @param $file File object we're deleting
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
		global $wgOut, $wgRequest, $wgUser, $wgUploadMaintenance;

		$permissionErrors = $this->title->getUserPermissionsErrors( 'delete', $wgUser );
		if ( count( $permissionErrors ) ) {
			throw new PermissionsError( 'delete', $permissionErrors );
		}

		if ( wfReadOnly() ) {
			throw new ReadOnlyError;
		}

		if ( $wgUploadMaintenance ) {
			throw new ErrorPageError( 'filedelete-maintenance-title', 'filedelete-maintenance' );
		}

		$this->setHeaders();

		$this->oldimage = $wgRequest->getText( 'oldimage', false );
		$token = $wgRequest->getText( 'wpEditToken' );
		# Flag to hide all contents of the archived revisions
		$suppress = $wgRequest->getVal( 'wpSuppress' ) && $wgUser->isAllowed('suppressrevision');

		if( $this->oldimage ) {
			$this->oldfile = RepoGroup::singleton()->getLocalRepo()->newFromArchiveName( $this->title, $this->oldimage );
		}

		if( !self::haveDeletableFile($this->file, $this->oldfile, $this->oldimage) ) {
			$wgOut->addHTML( $this->prepareMessage( 'filedelete-nofile' ) );
			$wgOut->addReturnTo( $this->title );
			return;
		}

		// Perform the deletion if appropriate
		if( $wgRequest->wasPosted() && $wgUser->matchEditToken( $token, $this->oldimage ) ) {
			$deleteReasonList = $wgRequest->getText( 'wpDeleteReasonList' );
			$deleteReason = $wgRequest->getText( 'wpReason' );

			if ( $deleteReasonList == 'other' ) {
				$reason = $deleteReason;
			} elseif ( $deleteReason != '' ) {
				// Entry from drop down menu + additional comment
				$reason = $deleteReasonList . wfMsgForContent( 'colon-separator' ) . $deleteReason;
			} else {
				$reason = $deleteReasonList;
			}

			$status = self::doDelete( $this->title, $this->file, $this->oldimage, $reason, $suppress, $wgUser );

			if( !$status->isGood() ) {
				$wgOut->addHTML( '<h2>' . $this->prepareMessage( 'filedeleteerror-short' ) . "</h2>\n" );
				$wgOut->addHTML( '<span class="error">' );
				$wgOut->addWikiText( $status->getWikiText( 'filedeleteerror-short', 'filedeleteerror-long' ) );
				$wgOut->addHTML( '</span>' );
			}
			if( $status->ok ) {
				$wgOut->setPageTitle( wfMessage( 'actioncomplete' ) );
				$wgOut->addHTML( $this->prepareMessage( 'filedelete-success' ) );
				// Return to the main page if we just deleted all versions of the
				// file, otherwise go back to the description page
				$wgOut->addReturnTo( $this->oldimage ? $this->title : Title::newMainPage() );

				if ( $wgRequest->getCheck( 'wpWatch' ) && $wgUser->isLoggedIn() ) {
					WatchAction::doWatch( $this->title, $wgUser );
				} elseif ( $this->title->userIsWatching() ) {
					WatchAction::doUnwatch( $this->title, $wgUser );
				}
			}
			return;
		}

		$this->showForm();
		$this->showLogEntries();
	}

	/**
	 * Really delete the file
	 *
	 * @param $title Title object
	 * @param File $file: file object
	 * @param $oldimage String: archive name
	 * @param $reason String: reason of the deletion
	 * @param $suppress Boolean: whether to mark all deleted versions as restricted
	 * @param $user User object performing the request
	 */
	public static function doDelete( &$title, &$file, &$oldimage, $reason, $suppress, User $user = null ) {
		if ( $user === null ) {
			global $wgUser;
			$user = $wgUser;
		}

		if( $oldimage ) {
			$page = null;
			$status = $file->deleteOld( $oldimage, $reason, $suppress );
			if( $status->ok ) {
				// Need to do a log item
				$log = new LogPage( 'delete' );
				$logComment = wfMsgForContent( 'deletedrevision', $oldimage );
				if( trim( $reason ) != '' ) {
					$logComment .= wfMsgForContent( 'colon-separator' ) . $reason;
				}
				$log->addEntry( 'delete', $title, $logComment );
			}
		} else {
			$status = Status::newFatal( 'cannotdelete',
				wfEscapeWikiText( $title->getPrefixedText() )
			);
			$page = WikiPage::factory( $title );
			$dbw = wfGetDB( DB_MASTER );
			try {
				// delete the associated article first
				$error = '';
				if ( $page->doDeleteArticleReal( $reason, $suppress, 0, false, $error, $user ) >= WikiPage::DELETE_SUCCESS ) {
					$status = $file->delete( $reason, $suppress );
					if( $status->isOK() ) {
						$dbw->commit();
					} else {
						$dbw->rollback();
					}
				}
			} catch ( MWException $e ) {
				// rollback before returning to prevent UI from displaying incorrect "View or restore N deleted edits?"
				$dbw->rollback();
				throw $e;
			}
		}

		if ( $status->isOK() ) {
			wfRunHooks( 'FileDeleteComplete', array( &$file, &$oldimage, &$page, &$user, &$reason ) );
		}

		return $status;
	}

	/**
	 * Show the confirmation form
	 */
	private function showForm() {
		global $wgOut, $wgUser, $wgRequest;

		if( $wgUser->isAllowed( 'suppressrevision' ) ) {
			$suppress = "<tr id=\"wpDeleteSuppressRow\">
					<td></td>
					<td class='mw-input'><strong>" .
						Xml::checkLabel( wfMsg( 'revdelete-suppress' ),
							'wpSuppress', 'wpSuppress', false, array( 'tabindex' => '3' ) ) .
					"</strong></td>
				</tr>";
		} else {
			$suppress = '';
		}

		$checkWatch = $wgUser->getBoolOption( 'watchdeletion' ) || $this->title->userIsWatching();
		$form = Xml::openElement( 'form', array( 'method' => 'post', 'action' => $this->getAction(),
			'id' => 'mw-img-deleteconfirm' ) ) .
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, wfMsg( 'filedelete-legend' ) ) .
			Html::hidden( 'wpEditToken', $wgUser->getEditToken( $this->oldimage ) ) .
			$this->prepareMessage( 'filedelete-intro' ) .
			Xml::openElement( 'table', array( 'id' => 'mw-img-deleteconfirm-table' ) ) .
			"<tr>
				<td class='mw-label'>" .
					Xml::label( wfMsg( 'filedelete-comment' ), 'wpDeleteReasonList' ) .
				"</td>
				<td class='mw-input'>" .
					Xml::listDropDown( 'wpDeleteReasonList',
						wfMsgForContent( 'filedelete-reason-dropdown' ),
						wfMsgForContent( 'filedelete-reason-otherlist' ), '', 'wpReasonDropDown', 1 ) .
				"</td>
			</tr>
			<tr>
				<td class='mw-label'>" .
					Xml::label( wfMsg( 'filedelete-otherreason' ), 'wpReason' ) .
				"</td>
				<td class='mw-input'>" .
					Xml::input( 'wpReason', 60, $wgRequest->getText( 'wpReason' ),
						array( 'type' => 'text', 'maxlength' => '255', 'tabindex' => '2', 'id' => 'wpReason' ) ) .
				"</td>
			</tr>
			{$suppress}";
		if( $wgUser->isLoggedIn() ) {
			$form .= "
			<tr>
				<td></td>
				<td class='mw-input'>" .
					Xml::checkLabel( wfMsg( 'watchthis' ),
						'wpWatch', 'wpWatch', $checkWatch, array( 'tabindex' => '3' ) ) .
				"</td>
			</tr>";
		}
		$form .= "
			<tr>
				<td></td>
				<td class='mw-submit'>" .
					Xml::submitButton( wfMsg( 'filedelete-submit' ),
						array( 'name' => 'mw-filedelete-submit', 'id' => 'mw-filedelete-submit', 'tabindex' => '4' ) ) .
				"</td>
			</tr>" .
			Xml::closeElement( 'table' ) .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' );

			if ( $wgUser->isAllowed( 'editinterface' ) ) {
				$title = Title::makeTitle( NS_MEDIAWIKI, 'Filedelete-reason-dropdown' );
				$link = Linker::link(
					$title,
					wfMsgHtml( 'filedelete-edit-reasonlist' ),
					array(),
					array( 'action' => 'edit' )
				);
				$form .= '<p class="mw-filedelete-editreasons">' . $link . '</p>';
			}

		$wgOut->addHTML( $form );
	}

	/**
	 * Show deletion log fragments pertaining to the current file
	 */
	private function showLogEntries() {
		global $wgOut;
		$wgOut->addHTML( '<h2>' . htmlspecialchars( LogPage::logName( 'delete' ) ) . "</h2>\n" );
		LogEventsList::showLogExtract( $wgOut, 'delete', $this->title );
	}

	/**
	 * Prepare a message referring to the file being deleted,
	 * showing an appropriate message depending upon whether
	 * it's a current file or an old version
	 *
	 * @param $message String: message base
	 * @return String
	 */
	private function prepareMessage( $message ) {
		global $wgLang;
		if( $this->oldimage ) {
			return wfMsgExt(
				"{$message}-old", # To ensure grep will find them: 'filedelete-intro-old', 'filedelete-nofile-old', 'filedelete-success-old'
				'parse',
				wfEscapeWikiText( $this->title->getText() ),
				$wgLang->date( $this->getTimestamp(), true ),
				$wgLang->time( $this->getTimestamp(), true ),
				wfExpandUrl( $this->file->getArchiveUrl( $this->oldimage ), PROTO_CURRENT ) );
		} else {
			return wfMsgExt(
				$message,
				'parse',
				wfEscapeWikiText( $this->title->getText() )
			);
		}
	}

	/**
	 * Set headers, titles and other bits
	 */
	private function setHeaders() {
		global $wgOut;
		$wgOut->setPageTitle( wfMessage( 'filedelete', $this->title->getText() ) );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->addBacklinkSubtitle( $this->title );
	}

	/**
	 * Is the provided `oldimage` value valid?
	 *
	 * @return bool
	 */
	public static function isValidOldSpec($oldimage) {
		return strlen( $oldimage ) >= 16
			&& strpos( $oldimage, '/' ) === false
			&& strpos( $oldimage, '\\' ) === false;
	}

	/**
	 * Could we delete the file specified? If an `oldimage`
	 * value was provided, does it correspond to an
	 * existing, local, old version of this file?
	 *
	 * @param $file File
	 * @param $oldfile File
	 * @param $oldimage File
	 * @return bool
	 */
	public static function haveDeletableFile(&$file, &$oldfile, $oldimage) {
		return $oldimage
			? $oldfile && $oldfile->exists() && $oldfile->isLocal()
			: $file && $file->exists() && $file->isLocal();
	}

	/**
	 * Prepare the form action
	 *
	 * @return string
	 */
	private function getAction() {
		$q = array();
		$q['action'] = 'delete';

		if( $this->oldimage )
			$q['oldimage'] = $this->oldimage;

		return $this->title->getLocalUrl( $q );
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
