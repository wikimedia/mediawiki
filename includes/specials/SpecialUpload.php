<?php
/**
 * Implements Special:Upload
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup SpecialPage
 * @ingroup Upload
 */

/**
 * Form for handling uploads and special page.
 *
 * @ingroup SpecialPage
 * @ingroup Upload
 */
class SpecialUpload extends SpecialPage {
	/**
	 * Constructor : initialise object
	 * Get data POSTed through the form and assign them to the object
	 * @param $request WebRequest : data posted.
	 */
	public function __construct( $request = null ) {
		global $wgRequest;

		parent::__construct( 'Upload', 'upload' );

		$this->loadRequest( is_null( $request ) ? $wgRequest : $request );
	}

	/** Misc variables **/
	public $mRequest;			// The WebRequest or FauxRequest this form is supposed to handle
	public $mSourceType;
	public $mUpload;
	public $mLocalFile;
	public $mUploadClicked;

	/** User input variables from the "description" section **/
	public $mDesiredDestName;	// The requested target file name
	public $mComment;
	public $mLicense;

	/** User input variables from the root section **/
	public $mIgnoreWarning;
	public $mWatchThis;
	public $mCopyrightStatus;
	public $mCopyrightSource;

	/** Hidden variables **/
	public $mDestWarningAck;
	public $mForReUpload;		// The user followed an "overwrite this file" link
	public $mCancelUpload;		// The user clicked "Cancel and return to upload form" button
	public $mTokenOk;
	public $mUploadSuccessful = false;	// Subclasses can use this to determine whether a file was uploaded

	/** Text injection points for hooks not using HTMLForm **/
	public $uploadFormTextTop;
	public $uploadFormTextAfterSummary;

	/**
	 * Initialize instance variables from request and create an Upload handler
	 *
	 * @param $request WebRequest: the request to extract variables from
	 */
	protected function loadRequest( $request ) {
		global $wgUser;

		$this->mRequest = $request;
		$this->mSourceType        = $request->getVal( 'wpSourceType', 'file' );
		$this->mUpload            = UploadBase::createFromRequest( $request );
		$this->mUploadClicked     = $request->wasPosted()
			&& ( $request->getCheck( 'wpUpload' )
				|| $request->getCheck( 'wpUploadIgnoreWarning' ) );

		// Guess the desired name from the filename if not provided
		$this->mDesiredDestName   = $request->getText( 'wpDestFile' );
		if( !$this->mDesiredDestName && $request->getFileName( 'wpUploadFile' ) !== null ) {
			$this->mDesiredDestName = $request->getFileName( 'wpUploadFile' );
		}
		$this->mComment           = $request->getText( 'wpUploadDescription' );
		$this->mLicense           = $request->getText( 'wpLicense' );


		$this->mDestWarningAck    = $request->getText( 'wpDestFileWarningAck' );
		$this->mIgnoreWarning     = $request->getCheck( 'wpIgnoreWarning' )
			|| $request->getCheck( 'wpUploadIgnoreWarning' );
		$this->mWatchthis         = $request->getBool( 'wpWatchthis' ) && $wgUser->isLoggedIn();
		$this->mCopyrightStatus   = $request->getText( 'wpUploadCopyStatus' );
		$this->mCopyrightSource   = $request->getText( 'wpUploadSource' );


		$this->mForReUpload       = $request->getBool( 'wpForReUpload' ); // updating a file
		$this->mCancelUpload      = $request->getCheck( 'wpCancelUpload' )
		                         || $request->getCheck( 'wpReUpload' ); // b/w compat

		// If it was posted check for the token (no remote POST'ing with user credentials)
		$token = $request->getVal( 'wpEditToken' );
		if( $this->mSourceType == 'file' && $token == null ) {
			// Skip token check for file uploads as that can't be faked via JS...
			// Some client-side tools don't expect to need to send wpEditToken
			// with their submissions, as that's new in 1.16.
			$this->mTokenOk = true;
		} else {
			$this->mTokenOk = $wgUser->matchEditToken( $token );
		}

		$this->uploadFormTextTop = '';
		$this->uploadFormTextAfterSummary = '';
	}

	/**
	 * This page can be shown if uploading is enabled.
	 * Handle permission checking elsewhere in order to be able to show
	 * custom error messages.
	 *
	 * @param $user User object
	 * @return Boolean
	 */
	public function userCanExecute( $user ) {
		return UploadBase::isEnabled() && parent::userCanExecute( $user );
	}

	/**
	 * Special page entry point
	 */
	public function execute( $par ) {
		global $wgUser, $wgOut;

		$this->setHeaders();
		$this->outputHeader();

		# Check uploading enabled
		if( !UploadBase::isEnabled() ) {
			$wgOut->showErrorPage( 'uploaddisabled', 'uploaddisabledtext' );
			return;
		}

		# Check permissions
		global $wgGroupPermissions;
		$permissionRequired = UploadBase::isAllowed( $wgUser );
		if( $permissionRequired !== true ) {
			if( !$wgUser->isLoggedIn() && ( $wgGroupPermissions['user']['upload']
				|| $wgGroupPermissions['autoconfirmed']['upload'] ) ) {
				// Custom message if logged-in users without any special rights can upload
				$wgOut->showErrorPage( 'uploadnologin', 'uploadnologintext' );
			} else {
				$wgOut->permissionRequired( $permissionRequired );
			}
			return;
		}

		# Check blocks
		if( $wgUser->isBlocked() ) {
			$wgOut->blockedPage();
			return;
		}

		# Check whether we actually want to allow changing stuff
		if( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}

		# Unsave the temporary file in case this was a cancelled upload
		if ( $this->mCancelUpload ) {
			if ( !$this->unsaveUploadedFile() ) {
				# Something went wrong, so unsaveUploadedFile showed a warning
				return;
			}
		}

		# Process upload or show a form
		if (
			$this->mTokenOk && !$this->mCancelUpload &&
			( $this->mUpload && $this->mUploadClicked )
		)
		{
			$this->processUpload();
		} else {
			# Backwards compatibility hook
			if( !wfRunHooks( 'UploadForm:initial', array( &$this ) ) ) {
				wfDebug( "Hook 'UploadForm:initial' broke output of the upload form" );
				return;
			}
			

			$this->showUploadForm( $this->getUploadForm() );
		}

		# Cleanup
		if ( $this->mUpload ) {
			$this->mUpload->cleanupTempFile();
		}
	}

	/**
	 * Show the main upload form
	 *
	 * @param $form Mixed: an HTMLForm instance or HTML string to show
	 */
	protected function showUploadForm( $form ) {
		# Add links if file was previously deleted
		if ( !$this->mDesiredDestName ) {
			$this->showViewDeletedLinks();
		}

		if ( $form instanceof HTMLForm ) {
			$form->show();
		} else {
			global $wgOut;
			$wgOut->addHTML( $form );
		}

	}

	/**
	 * Get an UploadForm instance with title and text properly set.
	 *
	 * @param $message String: HTML string to add to the form
	 * @param $sessionKey String: session key in case this is a stashed upload
	 * @param $hideIgnoreWarning Boolean: whether to hide "ignore warning" check box
	 * @return UploadForm
	 */
	protected function getUploadForm( $message = '', $sessionKey = '', $hideIgnoreWarning = false ) {
		global $wgOut;

		# Initialize form
		$form = new UploadForm( array(
			'watch' => $this->getWatchCheck(),
			'forreupload' => $this->mForReUpload,
			'sessionkey' => $sessionKey,
			'hideignorewarning' => $hideIgnoreWarning,
			'destwarningack' => (bool)$this->mDestWarningAck,

			'description' => $this->mComment,
			'texttop' => $this->uploadFormTextTop,
			'textaftersummary' => $this->uploadFormTextAfterSummary,
			'destfile' => $this->mDesiredDestName,
		) );
		$form->setTitle( $this->getTitle() );

		# Check the token, but only if necessary
		if(
			!$this->mTokenOk && !$this->mCancelUpload &&
			( $this->mUpload && $this->mUploadClicked )
		)
		{
			$form->addPreText( wfMsgExt( 'session_fail_preview', 'parseinline' ) );
		}

		# Give a notice if the user is uploading a file that has been deleted or moved
		# Note that this is independent from the message 'filewasdeleted' that requires JS
		$desiredTitleObj = Title::newFromText( $this->mDesiredDestName, NS_FILE );
		$delNotice = ''; // empty by default
		if ( $desiredTitleObj instanceof Title && !$desiredTitleObj->exists() ) {
			LogEventsList::showLogExtract( $delNotice, array( 'delete', 'move' ), 
				$desiredTitleObj->getPrefixedText(),
				'', array( 'lim' => 10,
					   'conds' => array( "log_action != 'revision'" ),
					   'showIfEmpty' => false,
					   'msgKey' => array( 'upload-recreate-warning' ) )
			);
		}
		$form->addPreText( $delNotice );

		# Add text to form
		$form->addPreText( '<div id="uploadtext">' . 
			wfMsgExt( 'uploadtext', 'parse', array( $this->mDesiredDestName ) ) . 
			'</div>' );
		# Add upload error message
		$form->addPreText( $message );

		# Add footer to form
		$uploadFooter = wfMsgNoTrans( 'uploadfooter' );
		if ( $uploadFooter != '-' && !wfEmptyMsg( 'uploadfooter', $uploadFooter ) ) {
			$form->addPostText( '<div id="mw-upload-footer-message">'
				. $wgOut->parse( $uploadFooter ) . "</div>\n" );
		}

		return $form;

	}

	/**
	 * Shows the "view X deleted revivions link""
	 */
	protected function showViewDeletedLinks() {
		global $wgOut, $wgUser;

		$title = Title::makeTitleSafe( NS_FILE, $this->mDesiredDestName );
		// Show a subtitle link to deleted revisions (to sysops et al only)
		if( $title instanceof Title ) {
			$count = $title->isDeleted();
			if ( $count > 0 && $wgUser->isAllowed( 'deletedhistory' ) ) {
				$link = wfMsgExt(
					$wgUser->isAllowed( 'delete' ) ? 'thisisdeleted' : 'viewdeleted',
					array( 'parse', 'replaceafter' ),
					$wgUser->getSkin()->linkKnown(
						SpecialPage::getTitleFor( 'Undelete', $title->getPrefixedText() ),
						wfMsgExt( 'restorelink', array( 'parsemag', 'escape' ), $count )
					)
				);
				$wgOut->addHTML( "<div id=\"contentSub2\">{$link}</div>" );
			}
		}

		// Show the relevant lines from deletion log (for still deleted files only)
		if( $title instanceof Title && $title->isDeletedQuick() && !$title->exists() ) {
			$this->showDeletionLog( $wgOut, $title->getPrefixedText() );
		}
	}

	/**
	 * Stashes the upload and shows the main upload form.
	 *
	 * Note: only errors that can be handled by changing the name or
	 * description should be redirected here. It should be assumed that the
	 * file itself is sane and has passed UploadBase::verifyFile. This
	 * essentially means that UploadBase::VERIFICATION_ERROR and
	 * UploadBase::EMPTY_FILE should not be passed here.
	 *
	 * @param $message String: HTML message to be passed to mainUploadForm
	 */
	protected function showRecoverableUploadError( $message ) {
		$sessionKey = $this->mUpload->stashSession();
		$message = '<h2>' . wfMsgHtml( 'uploadwarning' ) . "</h2>\n" .
			'<div class="error">' . $message . "</div>\n";

		$form = $this->getUploadForm( $message, $sessionKey );
		$form->setSubmitText( wfMsg( 'upload-tryagain' ) );
		$this->showUploadForm( $form );
	}
	/**
	 * Stashes the upload, shows the main form, but adds an "continue anyway button".
	 * Also checks whether there are actually warnings to display.
	 *
	 * @param $warnings Array
	 * @return boolean true if warnings were displayed, false if there are no
	 * 	warnings and the should continue processing like there was no warning
	 */
	protected function showUploadWarning( $warnings ) {
		# If there are no warnings, or warnings we can ignore, return early.
		# mDestWarningAck is set when some javascript has shown the warning
		# to the user. mForReUpload is set when the user clicks the "upload a
		# new version" link.
		if ( !$warnings || ( count( $warnings ) == 1 && 
			isset( $warnings['exists'] ) && 
			( $this->mDestWarningAck || $this->mForReUpload ) ) )
		{
			return false;
		}

		$sessionKey = $this->mUpload->stashSession();

		$warningHtml = '<h2>' . wfMsgHtml( 'uploadwarning' ) . "</h2>\n"
			. '<ul class="warning">';
		foreach( $warnings as $warning => $args ) {
			if( $warning == 'exists' ) {
				$msg = "\t<li>" . self::getExistsWarning( $args ) . "</li>\n";
			} elseif( $warning == 'duplicate' ) {
				$msg = self::getDupeWarning( $args );
			} elseif( $warning == 'duplicate-archive' ) {
				$msg = "\t<li>" . wfMsgExt( 'file-deleted-duplicate', 'parseinline',
						array( Title::makeTitle( NS_FILE, $args )->getPrefixedText() ) )
					. "</li>\n";
			} else {
				if ( $args === true ) {
					$args = array();
				} elseif ( !is_array( $args ) ) {
					$args = array( $args );
				}
				$msg = "\t<li>" . wfMsgExt( $warning, 'parseinline', $args ) . "</li>\n";
			}
			$warningHtml .= $msg;
		}
		$warningHtml .= "</ul>\n";
		$warningHtml .= wfMsgExt( 'uploadwarning-text', 'parse' );

		$form = $this->getUploadForm( $warningHtml, $sessionKey, /* $hideIgnoreWarning */ true );
		$form->setSubmitText( wfMsg( 'upload-tryagain' ) );
		$form->addButton( 'wpUploadIgnoreWarning', wfMsg( 'ignorewarning' ) );
		$form->addButton( 'wpCancelUpload', wfMsg( 'reuploaddesc' ) );

		$this->showUploadForm( $form );

		# Indicate that we showed a form
		return true;
	}

	/**
	 * Show the upload form with error message, but do not stash the file.
	 *
	 * @param $message HTML string
	 */
	protected function showUploadError( $message ) {
		$message = '<h2>' . wfMsgHtml( 'uploadwarning' ) . "</h2>\n" .
			'<div class="error">' . $message . "</div>\n";
		$this->showUploadForm( $this->getUploadForm( $message ) );
	}

	/**
	 * Do the upload.
	 * Checks are made in SpecialUpload::execute()
	 */
	protected function processUpload() {
		global $wgUser, $wgOut;

		// Fetch the file if required
		$status = $this->mUpload->fetchFile();
		if( !$status->isOK() ) {
			$this->showUploadError( $wgOut->parse( $status->getWikiText() ) );
			return;
		}

		if( !wfRunHooks( 'UploadForm:BeforeProcessing', array( &$this ) ) ) {
			wfDebug( "Hook 'UploadForm:BeforeProcessing' broke processing the file.\n" );
			// This code path is deprecated. If you want to break upload processing
			// do so by hooking into the appropriate hooks in UploadBase::verifyUpload
			// and UploadBase::verifyFile.
			// If you use this hook to break uploading, the user will be returned
			// an empty form with no error message whatsoever.
			return;
		}


		// Upload verification
		$details = $this->mUpload->verifyUpload();
		if ( $details['status'] != UploadBase::OK ) {
			$this->processVerificationError( $details );
			return;
		}
		
		// Verify permissions for this title
		$permErrors = $this->mUpload->verifyPermissions( $wgUser );
		if( $permErrors !== true ) {
			$code = array_shift( $permErrors[0] );
			$this->showRecoverableUploadError( wfMsgExt( $code,
					'parseinline', $permErrors[0] ) );
			return;
		}

		$this->mLocalFile = $this->mUpload->getLocalFile();

		// Check warnings if necessary
		if( !$this->mIgnoreWarning ) {
			$warnings = $this->mUpload->checkWarnings();
			if( $this->showUploadWarning( $warnings ) ) {
				return;
			}
		}

		// Get the page text if this is not a reupload
		if( !$this->mForReUpload ) {
			$pageText = self::getInitialPageText( $this->mComment, $this->mLicense,
				$this->mCopyrightStatus, $this->mCopyrightSource );
		} else {
			$pageText = false;
		}
		$status = $this->mUpload->performUpload( $this->mComment, $pageText, $this->mWatchthis, $wgUser );
		if ( !$status->isGood() ) {
			$this->showUploadError( $wgOut->parse( $status->getWikiText() ) );
			return;
		}

		// Success, redirect to description page
		$this->mUploadSuccessful = true;
		wfRunHooks( 'SpecialUploadComplete', array( &$this ) );
		$wgOut->redirect( $this->mLocalFile->getTitle()->getFullURL() );
	}

	/**
	 * Get the initial image page text based on a comment and optional file status information
	 */
	public static function getInitialPageText( $comment = '', $license = '', $copyStatus = '', $source = '' ) {
		global $wgUseCopyrightUpload, $wgForceUIMsgAsContentMsg;
		$wgForceUIMsgAsContentMsg = (array) $wgForceUIMsgAsContentMsg;

		/* These messages are transcluded into the actual text of the description page.
		 * Thus, forcing them as content messages makes the upload to produce an int: template
		 * instead of hardcoding it there in the uploader language.
		 */
		foreach( array( 'license-header', 'filedesc', 'filestatus', 'filesource' ) as $msgName ) {
			if ( in_array( $msgName, $wgForceUIMsgAsContentMsg ) ) {
				$msg[$msgName] = "{{int:$msgName}}";
			} else {
				$msg[$msgName] = wfMsgForContent( $msgName );
			}
		}

		if ( $wgUseCopyrightUpload ) {
			$licensetxt = '';
			if ( $license != '' ) {
				$licensetxt = '== ' . $msg[ 'license-header' ] . " ==\n" . '{{' . $license . '}}' . "\n";
			}
			$pageText = '== ' . $msg[ 'filedesc' ] . " ==\n" . $comment . "\n" .
				'== ' . $msg[ 'filestatus' ] . " ==\n" . $copyStatus . "\n" .
				"$licensetxt" .
				'== ' . $msg[ 'filesource' ] . " ==\n" . $source;
		} else {
			if ( $license != '' ) {
				$filedesc = $comment == '' ? '' : '== ' . $msg[ 'filedesc' ] . " ==\n" . $comment . "\n";
					$pageText = $filedesc .
					'== ' . $msg[ 'license-header' ] . " ==\n" . '{{' . $license . '}}' . "\n";
			} else {
				$pageText = $comment;
			}
		}
		return $pageText;
	}

	/**
	 * See if we should check the 'watch this page' checkbox on the form
	 * based on the user's preferences and whether we're being asked
	 * to create a new file or update an existing one.
	 *
	 * In the case where 'watch edits' is off but 'watch creations' is on,
	 * we'll leave the box unchecked.
	 *
	 * Note that the page target can be changed *on the form*, so our check
	 * state can get out of sync.
	 */
	protected function getWatchCheck() {
		global $wgUser;
		if( $wgUser->getOption( 'watchdefault' ) ) {
			// Watch all edits!
			return true;
		}

		$local = wfLocalFile( $this->mDesiredDestName );
		if( $local && $local->exists() ) {
			// We're uploading a new version of an existing file.
			// No creation, so don't watch it if we're not already.
			return $local->getTitle()->userIsWatching();
		} else {
			// New page should get watched if that's our option.
			return $wgUser->getOption( 'watchcreations' );
		}
	}


	/**
	 * Provides output to the user for a result of UploadBase::verifyUpload
	 *
	 * @param $details Array: result of UploadBase::verifyUpload
	 */
	protected function processVerificationError( $details ) {
		global $wgFileExtensions, $wgLang;

		switch( $details['status'] ) {

			/** Statuses that only require name changing **/
			case UploadBase::MIN_LENGTH_PARTNAME:
				$this->showRecoverableUploadError( wfMsgHtml( 'minlength1' ) );
				break;
			case UploadBase::ILLEGAL_FILENAME:
				$this->showRecoverableUploadError( wfMsgExt( 'illegalfilename',
					'parseinline', $details['filtered'] ) );
				break;
			case UploadBase::FILETYPE_MISSING:
				$this->showRecoverableUploadError( wfMsgExt( 'filetype-missing',
					'parseinline' ) );
				break;

			/** Statuses that require reuploading **/
			case UploadBase::EMPTY_FILE:
				$this->showUploadError( wfMsgHtml( 'emptyfile' ) );
				break;
			case UploadBase::FILE_TOO_LARGE:
				$this->showUploadError( wfMsgHtml( 'largefileserver' ) );
				break;
			case UploadBase::FILETYPE_BADTYPE:
				$finalExt = $details['finalExt'];
				$this->showUploadError(
					wfMsgExt( 'filetype-banned-type',
						array( 'parseinline' ),
						htmlspecialchars( $finalExt ),
						implode(
							wfMsgExt( 'comma-separator', array( 'escapenoentities' ) ),
							$wgFileExtensions
						),
						$wgLang->formatNum( count( $wgFileExtensions ) )
					)
				);
				break;
			case UploadBase::VERIFICATION_ERROR:
				unset( $details['status'] );
				$code = array_shift( $details['details'] );
				$this->showUploadError( wfMsgExt( $code, 'parseinline', $details['details'] ) );
				break;
			case UploadBase::HOOK_ABORTED:
				if ( is_array( $details['error'] ) ) { # allow hooks to return error details in an array
					$args = $details['error'];
					$error = array_shift( $args );
				} else {
					$error = $details['error'];
					$args = null;
				}

				$this->showUploadError( wfMsgExt( $error, 'parseinline', $args ) );
				break;
			default:
				throw new MWException( __METHOD__ . ": Unknown value `{$details['status']}`" );
		}
	}

	/**
	 * Remove a temporarily kept file stashed by saveTempUploadedFile().
	 *
	 * @return Boolean: success
	 */
	protected function unsaveUploadedFile() {
		global $wgOut;
		if ( !( $this->mUpload instanceof UploadFromStash ) ) {
			return true;
		}
		$success = $this->mUpload->unsaveUploadedFile();
		if ( !$success ) {
			$wgOut->showFileDeleteError( $this->mUpload->getTempPath() );
			return false;
		} else {
			return true;
		}
	}

	/*** Functions for formatting warnings ***/

	/**
	 * Formats a result of UploadBase::getExistsWarning as HTML
	 * This check is static and can be done pre-upload via AJAX
	 *
	 * @param $exists Array: the result of UploadBase::getExistsWarning
	 * @return String: empty string if there is no warning or an HTML fragment
	 */
	public static function getExistsWarning( $exists ) {
		global $wgUser;

		if ( !$exists ) {
			return '';
		}

		$file = $exists['file'];
		$filename = $file->getTitle()->getPrefixedText();
		$warning = '';

		$sk = $wgUser->getSkin();

		if( $exists['warning'] == 'exists' ) {
			// Exact match
			$warning = wfMsgExt( 'fileexists', 'parseinline', $filename );
		} elseif( $exists['warning'] == 'page-exists' ) {
			// Page exists but file does not
			$warning = wfMsgExt( 'filepageexists', 'parseinline', $filename );
		} elseif ( $exists['warning'] == 'exists-normalized' ) {
			$warning = wfMsgExt( 'fileexists-extension', 'parseinline', $filename,
				$exists['normalizedFile']->getTitle()->getPrefixedText() );
		} elseif ( $exists['warning'] == 'thumb' ) {
			// Swapped argument order compared with other messages for backwards compatibility
			$warning = wfMsgExt( 'fileexists-thumbnail-yes', 'parseinline',
				$exists['thumbFile']->getTitle()->getPrefixedText(), $filename );
		} elseif ( $exists['warning'] == 'thumb-name' ) {
			// Image w/o '180px-' does not exists, but we do not like these filenames
			$name = $file->getName();
			$badPart = substr( $name, 0, strpos( $name, '-' ) + 1 );
			$warning = wfMsgExt( 'file-thumbnail-no', 'parseinline', $badPart );
		} elseif ( $exists['warning'] == 'bad-prefix' ) {
			$warning = wfMsgExt( 'filename-bad-prefix', 'parseinline', $exists['prefix'] );
		} elseif ( $exists['warning'] == 'was-deleted' ) {
			# If the file existed before and was deleted, warn the user of this
			$ltitle = SpecialPage::getTitleFor( 'Log' );
			$llink = $sk->linkKnown(
				$ltitle,
				wfMsgHtml( 'deletionlog' ),
				array(),
				array(
					'type' => 'delete',
					'page' => $filename
				)
			);
			$warning = wfMsgWikiHtml( 'filewasdeleted', $llink );
		}

		return $warning;
	}

	/**
	 * Get a list of warnings
	 *
	 * @param $filename String: local filename, e.g. 'file exists', 'non-descriptive filename'
	 * @return Array: list of warning messages
	 */
	public static function ajaxGetExistsWarning( $filename ) {
		$file = wfFindFile( $filename );
		if( !$file ) {
			// Force local file so we have an object to do further checks against
			// if there isn't an exact match...
			$file = wfLocalFile( $filename );
		}
		$s = '&#160;';
		if ( $file ) {
			$exists = UploadBase::getExistsWarning( $file );
			$warning = self::getExistsWarning( $exists );
			if ( $warning !== '' ) {
				$s = "<div>$warning</div>";
			}
		}
		return $s;
	}

	/**
	 * Construct a warning and a gallery from an array of duplicate files.
	 */
	public static function getDupeWarning( $dupes ) {
		if( $dupes ) {
			global $wgOut;
			$msg = '<gallery>';
			foreach( $dupes as $file ) {
				$title = $file->getTitle();
				$msg .= $title->getPrefixedText() .
					'|' . $title->getText() . "\n";
			}
			$msg .= '</gallery>';
			return '<li>' .
				wfMsgExt( 'file-exists-duplicate', array( 'parse' ), count( $dupes ) ) .
				$wgOut->parse( $msg ) .
				"</li>\n";
		} else {
			return '';
		}
	}

}

/**
 * Sub class of HTMLForm that provides the form section of SpecialUpload
 */
class UploadForm extends HTMLForm {
	protected $mWatch;
	protected $mForReUpload;
	protected $mSessionKey;
	protected $mHideIgnoreWarning;
	protected $mDestWarningAck;
	protected $mDestFile;

	protected $mComment;
	protected $mTextTop;
	protected $mTextAfterSummary;

	protected $mSourceIds;

	public function __construct( $options = array() ) {
		$this->mWatch = !empty( $options['watch'] );
		$this->mForReUpload = !empty( $options['forreupload'] );
		$this->mSessionKey = isset( $options['sessionkey'] )
				? $options['sessionkey'] : '';
		$this->mHideIgnoreWarning = !empty( $options['hideignorewarning'] );
		$this->mDestWarningAck = !empty( $options['destwarningack'] );
		$this->mDestFile = isset( $options['destfile'] ) ? $options['destfile'] : '';

		$this->mComment = isset( $options['description'] ) ?
			$options['description'] : '';

		$this->mTextTop = isset( $options['texttop'] )
			? $options['texttop'] : '';

		$this->mTextAfterSummary = isset( $options['textaftersummary'] )
			? $options['textaftersummary'] : ''; 

		$sourceDescriptor = $this->getSourceSection();
		$descriptor = $sourceDescriptor
			+ $this->getDescriptionSection()
			+ $this->getOptionsSection();

		wfRunHooks( 'UploadFormInitDescriptor', array( &$descriptor ) );
		parent::__construct( $descriptor, 'upload' );

		# Set some form properties
		$this->setSubmitText( wfMsg( 'uploadbtn' ) );
		$this->setSubmitName( 'wpUpload' );
		# Used message keys: 'accesskey-upload', 'tooltip-upload'
		$this->setSubmitTooltip( 'upload' );
		$this->setId( 'mw-upload-form' );

		# Build a list of IDs for javascript insertion
		$this->mSourceIds = array();
		foreach ( $sourceDescriptor as $field ) {
			if ( !empty( $field['id'] ) ) {
				$this->mSourceIds[] = $field['id'];
			}
		}

	}

	/**
	 * Get the descriptor of the fieldset that contains the file source
	 * selection. The section is 'source'
	 *
	 * @return Array: descriptor array
	 */
	protected function getSourceSection() {
		global $wgLang, $wgUser, $wgRequest;
		global $wgMaxUploadSize;

		if ( $this->mSessionKey ) {
			return array(
				'wpSessionKey' => array(
					'type' => 'hidden',
					'default' => $this->mSessionKey,
				),
				'wpSourceType' => array(
					'type' => 'hidden',
					'default' => 'Stash',
				),
			);
		}

		$canUploadByUrl = UploadFromUrl::isEnabled() && $wgUser->isAllowed( 'upload_by_url' );
		$radio = $canUploadByUrl;
		$selectedSourceType = strtolower( $wgRequest->getText( 'wpSourceType', 'File' ) );

		$descriptor = array();
		if ( $this->mTextTop ) {
			$descriptor['UploadFormTextTop'] = array(
				'type' => 'info',
				'section' => 'source',
				'default' => $this->mTextTop,
				'raw' => true,
			);
		}

		$descriptor['UploadFile'] = array(
			'class' => 'UploadSourceField',
			'section' => 'source',
			'type' => 'file',
			'id' => 'wpUploadFile',
			'label-message' => 'sourcefilename',
			'upload-type' => 'File',
			'radio' => &$radio,
			'help' => wfMsgExt( 'upload-maxfilesize',
					array( 'parseinline', 'escapenoentities' ),
					$wgLang->formatSize(
						wfShorthandToInteger( min( 
							wfShorthandToInteger(
								ini_get( 'upload_max_filesize' )
							), $wgMaxUploadSize
						) )
					)
				) . ' ' . wfMsgHtml( 'upload_source_file' ),
			'checked' => $selectedSourceType == 'file',
		);
		if ( $canUploadByUrl ) {
			$descriptor['UploadFileURL'] = array(
				'class' => 'UploadSourceField',
				'section' => 'source',
				'id' => 'wpUploadFileURL',
				'label-message' => 'sourceurl',
				'upload-type' => 'url',
				'radio' => &$radio,
				'help' => wfMsgExt( 'upload-maxfilesize',
						array( 'parseinline', 'escapenoentities' ),
						$wgLang->formatSize( $wgMaxUploadSize )
					) . ' ' . wfMsgHtml( 'upload_source_url' ),
				'checked' => $selectedSourceType == 'url',
			);
		}
		wfRunHooks( 'UploadFormSourceDescriptors', array( &$descriptor, &$radio, $selectedSourceType ) );

		$descriptor['Extensions'] = array(
			'type' => 'info',
			'section' => 'source',
			'default' => $this->getExtensionsMessage(),
			'raw' => true,
		);
		return $descriptor;
	}

	/**
	 * Get the messages indicating which extensions are preferred and prohibitted.
	 *
	 * @return String: HTML string containing the message
	 */
	protected function getExtensionsMessage() {
		# Print a list of allowed file extensions, if so configured.  We ignore
		# MIME type here, it's incomprehensible to most people and too long.
		global $wgLang, $wgCheckFileExtensions, $wgStrictFileExtensions,
		$wgFileExtensions, $wgFileBlacklist;

		if( $wgCheckFileExtensions ) {
			//don't show blacklisted types as permitted
			$wgFileExtensions = array_diff ( $wgFileExtensions, $wgFileBlacklist );
			
			if( $wgStrictFileExtensions ) {
				# Everything not permitted is banned
				$extensionsList =
					'<div id="mw-upload-permitted">' .
					wfMsgWikiHtml( 'upload-permitted', $wgLang->commaList( $wgFileExtensions ) ) .
					"</div>\n";
			} else {
				# We have to list both preferred and prohibited
				$extensionsList =
					'<div id="mw-upload-preferred">' .
					wfMsgWikiHtml( 'upload-preferred', $wgLang->commaList( $wgFileExtensions ) ) .
					"</div>\n" .
					'<div id="mw-upload-prohibited">' .
					wfMsgWikiHtml( 'upload-prohibited', $wgLang->commaList( $wgFileBlacklist ) ) .
					"</div>\n";
			}
		} else {
			# Everything is permitted.
			$extensionsList = '';
		}
		return $extensionsList;
	}

	/**
	 * Get the descriptor of the fieldset that contains the file description
	 * input. The section is 'description'
	 *
	 * @return Array: descriptor array
	 */
	protected function getDescriptionSection() {
		global $wgUser;

		$descriptor = array(
			'DestFile' => array(
				'type' => 'text',
				'section' => 'description',
				'id' => 'wpDestFile',
				'label-message' => 'destfilename',
				'size' => 60,
				'default' => $this->mDestFile,
				# FIXME: hack to work around poor handling of the 'default' option in HTMLForm
				'nodata' => strval( $this->mDestFile ) !== '',
			),
			'UploadDescription' => array(
				'type' => 'textarea',
				'section' => 'description',
				'id' => 'wpUploadDescription',
				'label-message' => $this->mForReUpload
					? 'filereuploadsummary'
					: 'fileuploadsummary',
				'default' => $this->mComment,
				'cols' => intval( $wgUser->getOption( 'cols' ) ),
				'rows' => 8,
			)
		);
		if ( $this->mTextAfterSummary ) {
			$descriptor['UploadFormTextAfterSummary'] = array(
				'type' => 'info',
				'section' => 'description',
				'default' => $this->mTextAfterSummary,
				'raw' => true,
			);
		}

		$descriptor += array(
			'EditTools' => array(
				'type' => 'edittools',
				'section' => 'description',
			)
		);

		if ( $this->mForReUpload ) {
			$descriptor['DestFile']['readonly'] = true;
		} else {
			$descriptor['License'] = array(
				'type' => 'select',
				'class' => 'Licenses',
				'section' => 'description',
				'id' => 'wpLicense',
				'label-message' => 'license',
			);
		}

		global $wgUseCopyrightUpload;
		if ( $wgUseCopyrightUpload ) {
			$descriptor['UploadCopyStatus'] = array(
				'type' => 'text',
				'section' => 'description',
				'id' => 'wpUploadCopyStatus',
				'label-message' => 'filestatus',
			);
			$descriptor['UploadSource'] = array(
				'type' => 'text',
				'section' => 'description',
				'id' => 'wpUploadSource',
				'label-message' => 'filesource',
			);
		}

		return $descriptor;
	}

	/**
	 * Get the descriptor of the fieldset that contains the upload options,
	 * such as "watch this file". The section is 'options'
	 *
	 * @return Array: descriptor array
	 */
	protected function getOptionsSection() {
		global $wgUser;

		if ( $wgUser->isLoggedIn() ) {
			$descriptor = array(
				'Watchthis' => array(
					'type' => 'check',
					'id' => 'wpWatchthis',
					'label-message' => 'watchthisupload',
					'section' => 'options',
					'default' => $wgUser->getOption( 'watchcreations' ),
				)
			);
		}
		if ( !$this->mHideIgnoreWarning ) {
			$descriptor['IgnoreWarning'] = array(
				'type' => 'check',
				'id' => 'wpIgnoreWarning',
				'label-message' => 'ignorewarnings',
				'section' => 'options',
			);
		}

		$descriptor['wpDestFileWarningAck'] = array(
			'type' => 'hidden',
			'id' => 'wpDestFileWarningAck',
			'default' => $this->mDestWarningAck ? '1' : '',
		);
		
		if ( $this->mForReUpload ) {
			$descriptor['wpForReUpload'] = array(
				'type' => 'hidden',
				'id' => 'wpForReUpload',
				'default' => '1',
			);
		}

		return $descriptor;
	}

	/**
	 * Add the upload JS and show the form.
	 */
	public function show() {
		$this->addUploadJS();
		parent::show();
	}

	/**
	 * Add upload JS to $wgOut
	 */
	protected function addUploadJS() {
		global $wgUseAjax, $wgAjaxUploadDestCheck, $wgAjaxLicensePreview, $wgEnableAPI, $wgStrictFileExtensions;
		global $wgOut;

		$useAjaxDestCheck = $wgUseAjax && $wgAjaxUploadDestCheck;
		$useAjaxLicensePreview = $wgUseAjax && $wgAjaxLicensePreview && $wgEnableAPI;

		$scriptVars = array(
			'wgAjaxUploadDestCheck' => $useAjaxDestCheck,
			'wgAjaxLicensePreview' => $useAjaxLicensePreview,
			'wgUploadAutoFill' => !$this->mForReUpload &&
				// If we received mDestFile from the request, don't autofill
				// the wpDestFile textbox
				$this->mDestFile === '',
			'wgUploadSourceIds' => $this->mSourceIds,
			'wgStrictFileExtensions' => $wgStrictFileExtensions,
			'wgCapitalizeUploads' => MWNamespace::isCapitalized( NS_FILE ),
		);

		$wgOut->addScript( Skin::makeVariablesScript( $scriptVars ) );

		// For <charinsert> support
		$wgOut->addModules( array( 'mediawiki.legacy.edit', 'mediawiki.legacy.upload' ) );
	}

	/**
	 * Empty function; submission is handled elsewhere.
	 *
	 * @return bool false
	 */
	function trySubmit() {
		return false;
	}

}

/**
 * A form field that contains a radio box in the label
 */
class UploadSourceField extends HTMLTextField {
	function getLabelHtml( $cellAttributes = array() ) {
		$id = "wpSourceType{$this->mParams['upload-type']}";
		$label = Html::rawElement( 'label', array( 'for' => $id ), $this->mLabel );

		if ( !empty( $this->mParams['radio'] ) ) {
			$attribs = array(
				'name' => 'wpSourceType',
				'type' => 'radio',
				'id' => $id,
				'value' => $this->mParams['upload-type'],
			);
			if ( !empty( $this->mParams['checked'] ) ) {
				$attribs['checked'] = 'checked';
			}
			$label .= Html::element( 'input', $attribs );
		}

		return Html::rawElement( 'td', array( 'class' => 'mw-label' ) + $cellAttributes, $label );
	}

	function getSize() {
		return isset( $this->mParams['size'] )
			? $this->mParams['size']
			: 60;
	}
}

