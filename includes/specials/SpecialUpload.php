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
		parent::__construct( 'Upload', 'upload' );
	}

	/** Misc variables **/
	public $mRequest; // The WebRequest or FauxRequest this form is supposed to handle
	public $mSourceType;

	/**
	 * @var UploadBase
	 */
	public $mUpload;

	/**
	 * @var LocalFile
	 */
	public $mLocalFile;
	public $mUploadClicked;

	/** User input variables from the "description" section **/
	public $mDesiredDestName; // The requested target file name
	public $mComment;
	public $mLicense;

	/** User input variables from the root section **/
	public $mIgnoreWarning;
	public $mWatchThis;
	public $mCopyrightStatus;
	public $mCopyrightSource;

	/** Hidden variables **/
	public $mDestWarningAck;
	public $mForReUpload; // The user followed an "overwrite this file" link
	public $mCancelUpload; // The user clicked "Cancel and return to upload form" button
	public $mTokenOk;
	public $mUploadSuccessful = false; // Subclasses can use this to determine whether a file was uploaded

	/** Text injection points for hooks not using HTMLForm **/
	public $uploadFormTextTop;
	public $uploadFormTextAfterSummary;

	public $mWatchthis;

	/**
	 * Initialize instance variables from request and create an Upload handler
	 */
	protected function loadRequest() {
		$this->mRequest = $request = $this->getRequest();
		$this->mSourceType = $request->getVal( 'wpSourceType', 'file' );
		$this->mUpload = UploadBase::createFromRequest( $request );
		$this->mUploadClicked = $request->wasPosted()
			&& ( $request->getCheck( 'wpUpload' )
				|| $request->getCheck( 'wpUploadIgnoreWarning' ) );

		// Guess the desired name from the filename if not provided
		$this->mDesiredDestName = $request->getText( 'wpDestFile' );
		if ( !$this->mDesiredDestName && $request->getFileName( 'wpUploadFile' ) !== null ) {
			$this->mDesiredDestName = $request->getFileName( 'wpUploadFile' );
		}
		$this->mLicense = $request->getText( 'wpLicense' );

		$this->mDestWarningAck = $request->getText( 'wpDestFileWarningAck' );
		$this->mIgnoreWarning = $request->getCheck( 'wpIgnoreWarning' )
			|| $request->getCheck( 'wpUploadIgnoreWarning' );
		$this->mWatchthis = $request->getBool( 'wpWatchthis' ) && $this->getUser()->isLoggedIn();
		$this->mCopyrightStatus = $request->getText( 'wpUploadCopyStatus' );
		$this->mCopyrightSource = $request->getText( 'wpUploadSource' );

		$this->mForReUpload = $request->getBool( 'wpForReUpload' ); // updating a file

		$commentDefault = '';
		$commentMsg = wfMessage( 'upload-default-description' )->inContentLanguage();
		if ( !$this->mForReUpload && !$commentMsg->isDisabled() ) {
			$commentDefault = $commentMsg->plain();
		}
		$this->mComment = $request->getText( 'wpUploadDescription', $commentDefault );

		$this->mCancelUpload = $request->getCheck( 'wpCancelUpload' )
			|| $request->getCheck( 'wpReUpload' ); // b/w compat

		// If it was posted check for the token (no remote POST'ing with user credentials)
		$token = $request->getVal( 'wpEditToken' );
		$this->mTokenOk = $this->getUser()->matchEditToken( $token );

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
	public function userCanExecute( User $user ) {
		return UploadBase::isEnabled() && parent::userCanExecute( $user );
	}

	/**
	 * Special page entry point
	 */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		# Check uploading enabled
		if ( !UploadBase::isEnabled() ) {
			throw new ErrorPageError( 'uploaddisabled', 'uploaddisabledtext' );
		}

		# Check permissions
		$user = $this->getUser();
		$permissionRequired = UploadBase::isAllowed( $user );
		if ( $permissionRequired !== true ) {
			throw new PermissionsError( $permissionRequired );
		}

		# Check blocks
		if ( $user->isBlocked() ) {
			throw new UserBlockedError( $user->getBlock() );
		}

		# Check whether we actually want to allow changing stuff
		$this->checkReadOnly();

		$this->loadRequest();

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
		) {
			$this->processUpload();
		} else {
			# Backwards compatibility hook
			if ( !wfRunHooks( 'UploadForm:initial', array( &$this ) ) ) {
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
		if ( $this->mDesiredDestName ) {
			$this->showViewDeletedLinks();
		}

		if ( $form instanceof HTMLForm ) {
			$form->show();
		} else {
			$this->getOutput()->addHTML( $form );
		}

	}

	/**
	 * Get an UploadForm instance with title and text properly set.
	 *
	 * @param string $message HTML string to add to the form
	 * @param string $sessionKey session key in case this is a stashed upload
	 * @param $hideIgnoreWarning Boolean: whether to hide "ignore warning" check box
	 * @return UploadForm
	 */
	protected function getUploadForm( $message = '', $sessionKey = '', $hideIgnoreWarning = false ) {
		# Initialize form
		$context = new DerivativeContext( $this->getContext() );
		$context->setTitle( $this->getTitle() ); // Remove subpage
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
		), $context );

		# Check the token, but only if necessary
		if (
			!$this->mTokenOk && !$this->mCancelUpload &&
			( $this->mUpload && $this->mUploadClicked )
		) {
			$form->addPreText( $this->msg( 'session_fail_preview' )->parse() );
		}

		# Give a notice if the user is uploading a file that has been deleted or moved
		# Note that this is independent from the message 'filewasdeleted' that requires JS
		$desiredTitleObj = Title::makeTitleSafe( NS_FILE, $this->mDesiredDestName );
		$delNotice = ''; // empty by default
		if ( $desiredTitleObj instanceof Title && !$desiredTitleObj->exists() ) {
			LogEventsList::showLogExtract( $delNotice, array( 'delete', 'move' ),
				$desiredTitleObj,
				'', array( 'lim' => 10,
					'conds' => array( "log_action != 'revision'" ),
					'showIfEmpty' => false,
					'msgKey' => array( 'upload-recreate-warning' ) )
			);
		}
		$form->addPreText( $delNotice );

		# Add text to form
		$form->addPreText( '<div id="uploadtext">' .
			$this->msg( 'uploadtext', array( $this->mDesiredDestName ) )->parseAsBlock() .
			'</div>' );
		# Add upload error message
		$form->addPreText( $message );

		# Add footer to form
		$uploadFooter = $this->msg( 'uploadfooter' );
		if ( !$uploadFooter->isDisabled() ) {
			$form->addPostText( '<div id="mw-upload-footer-message">'
				. $uploadFooter->parseAsBlock() . "</div>\n" );
		}

		return $form;
	}

	/**
	 * Shows the "view X deleted revivions link""
	 */
	protected function showViewDeletedLinks() {
		$title = Title::makeTitleSafe( NS_FILE, $this->mDesiredDestName );
		$user = $this->getUser();
		// Show a subtitle link to deleted revisions (to sysops et al only)
		if ( $title instanceof Title ) {
			$count = $title->isDeleted();
			if ( $count > 0 && $user->isAllowed( 'deletedhistory' ) ) {
				$restorelink = Linker::linkKnown(
					SpecialPage::getTitleFor( 'Undelete', $title->getPrefixedText() ),
					$this->msg( 'restorelink' )->numParams( $count )->escaped()
				);
				$link = $this->msg( $user->isAllowed( 'delete' ) ? 'thisisdeleted' : 'viewdeleted' )
					->rawParams( $restorelink )->parseAsBlock();
				$this->getOutput()->addHTML( "<div id=\"contentSub2\">{$link}</div>" );
			}
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
	 * @param string $message HTML message to be passed to mainUploadForm
	 */
	protected function showRecoverableUploadError( $message ) {
		$sessionKey = $this->mUpload->stashSession();
		$message = '<h2>' . $this->msg( 'uploaderror' )->escaped() . "</h2>\n" .
			'<div class="error">' . $message . "</div>\n";

		$form = $this->getUploadForm( $message, $sessionKey );
		$form->setSubmitText( $this->msg( 'upload-tryagain' )->escaped() );
		$this->showUploadForm( $form );
	}
	/**
	 * Stashes the upload, shows the main form, but adds a "continue anyway button".
	 * Also checks whether there are actually warnings to display.
	 *
	 * @param $warnings Array
	 * @return boolean true if warnings were displayed, false if there are no
	 *         warnings and it should continue processing
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

		$warningHtml = '<h2>' . $this->msg( 'uploadwarning' )->escaped() . "</h2>\n"
			. '<ul class="warning">';
		foreach ( $warnings as $warning => $args ) {
			if ( $warning == 'badfilename' ) {
				$this->mDesiredDestName = Title::makeTitle( NS_FILE, $args )->getText();
			}
			if ( $warning == 'exists' ) {
				$msg = "\t<li>" . self::getExistsWarning( $args ) . "</li>\n";
			} elseif ( $warning == 'duplicate' ) {
				$msg = $this->getDupeWarning( $args );
			} elseif ( $warning == 'duplicate-archive' ) {
				$msg = "\t<li>" . $this->msg( 'file-deleted-duplicate',
						Title::makeTitle( NS_FILE, $args )->getPrefixedText() )->parse()
					. "</li>\n";
			} else {
				if ( $args === true ) {
					$args = array();
				} elseif ( !is_array( $args ) ) {
					$args = array( $args );
				}
				$msg = "\t<li>" . $this->msg( $warning, $args )->parse() . "</li>\n";
			}
			$warningHtml .= $msg;
		}
		$warningHtml .= "</ul>\n";
		$warningHtml .= $this->msg( 'uploadwarning-text' )->parseAsBlock();

		$form = $this->getUploadForm( $warningHtml, $sessionKey, /* $hideIgnoreWarning */ true );
		$form->setSubmitText( $this->msg( 'upload-tryagain' )->text() );
		$form->addButton( 'wpUploadIgnoreWarning', $this->msg( 'ignorewarning' )->text() );
		$form->addButton( 'wpCancelUpload', $this->msg( 'reuploaddesc' )->text() );

		$this->showUploadForm( $form );

		# Indicate that we showed a form
		return true;
	}

	/**
	 * Show the upload form with error message, but do not stash the file.
	 *
	 * @param string $message HTML string
	 */
	protected function showUploadError( $message ) {
		$message = '<h2>' . $this->msg( 'uploadwarning' )->escaped() . "</h2>\n" .
			'<div class="error">' . $message . "</div>\n";
		$this->showUploadForm( $this->getUploadForm( $message ) );
	}

	/**
	 * Do the upload.
	 * Checks are made in SpecialUpload::execute()
	 */
	protected function processUpload() {
		// Fetch the file if required
		$status = $this->mUpload->fetchFile();
		if ( !$status->isOK() ) {
			$this->showUploadError( $this->getOutput()->parse( $status->getWikiText() ) );
			return;
		}

		if ( !wfRunHooks( 'UploadForm:BeforeProcessing', array( &$this ) ) ) {
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
		$permErrors = $this->mUpload->verifyTitlePermissions( $this->getUser() );
		if ( $permErrors !== true ) {
			$code = array_shift( $permErrors[0] );
			$this->showRecoverableUploadError( $this->msg( $code, $permErrors[0] )->parse() );
			return;
		}

		$this->mLocalFile = $this->mUpload->getLocalFile();

		// Check warnings if necessary
		if ( !$this->mIgnoreWarning ) {
			$warnings = $this->mUpload->checkWarnings();
			if ( $this->showUploadWarning( $warnings ) ) {
				return;
			}
		}

		// Get the page text if this is not a reupload
		if ( !$this->mForReUpload ) {
			$pageText = self::getInitialPageText( $this->mComment, $this->mLicense,
				$this->mCopyrightStatus, $this->mCopyrightSource );
		} else {
			$pageText = false;
		}
		$status = $this->mUpload->performUpload( $this->mComment, $pageText, $this->mWatchthis, $this->getUser() );
		if ( !$status->isGood() ) {
			$this->showUploadError( $this->getOutput()->parse( $status->getWikiText() ) );
			return;
		}

		// Success, redirect to description page
		$this->mUploadSuccessful = true;
		wfRunHooks( 'SpecialUploadComplete', array( &$this ) );
		$this->getOutput()->redirect( $this->mLocalFile->getTitle()->getFullURL() );
	}

	/**
	 * Get the initial image page text based on a comment and optional file status information
	 * @param $comment string
	 * @param $license string
	 * @param $copyStatus string
	 * @param $source string
	 * @return string
	 */
	public static function getInitialPageText( $comment = '', $license = '', $copyStatus = '', $source = '' ) {
		global $wgUseCopyrightUpload, $wgForceUIMsgAsContentMsg;

		$msg = array();
		/* These messages are transcluded into the actual text of the description page.
		 * Thus, forcing them as content messages makes the upload to produce an int: template
		 * instead of hardcoding it there in the uploader language.
		 */
		foreach ( array( 'license-header', 'filedesc', 'filestatus', 'filesource' ) as $msgName ) {
			if ( in_array( $msgName, (array)$wgForceUIMsgAsContentMsg ) ) {
				$msg[$msgName] = "{{int:$msgName}}";
			} else {
				$msg[$msgName] = wfMessage( $msgName )->inContentLanguage()->text();
			}
		}

		if ( $wgUseCopyrightUpload ) {
			$licensetxt = '';
			if ( $license != '' ) {
				$licensetxt = '== ' . $msg['license-header'] . " ==\n" . '{{' . $license . '}}' . "\n";
			}
			$pageText = '== ' . $msg['filedesc'] . " ==\n" . $comment . "\n" .
				'== ' . $msg['filestatus'] . " ==\n" . $copyStatus . "\n" .
				"$licensetxt" .
				'== ' . $msg['filesource'] . " ==\n" . $source;
		} else {
			if ( $license != '' ) {
				$filedesc = $comment == '' ? '' : '== ' . $msg['filedesc'] . " ==\n" . $comment . "\n";
					$pageText = $filedesc .
					'== ' . $msg['license-header'] . " ==\n" . '{{' . $license . '}}' . "\n";
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
	 * @return Bool|String
	 */
	protected function getWatchCheck() {
		if ( $this->getUser()->getOption( 'watchdefault' ) ) {
			// Watch all edits!
			return true;
		}

		$local = wfLocalFile( $this->mDesiredDestName );
		if ( $local && $local->exists() ) {
			// We're uploading a new version of an existing file.
			// No creation, so don't watch it if we're not already.
			return $this->getUser()->isWatched( $local->getTitle() );
		} else {
			// New page should get watched if that's our option.
			return $this->getUser()->getOption( 'watchcreations' );
		}
	}

	/**
	 * Provides output to the user for a result of UploadBase::verifyUpload
	 *
	 * @param array $details result of UploadBase::verifyUpload
	 * @throws MWException
	 */
	protected function processVerificationError( $details ) {
		global $wgFileExtensions;

		switch ( $details['status'] ) {

			/** Statuses that only require name changing **/
			case UploadBase::MIN_LENGTH_PARTNAME:
				$this->showRecoverableUploadError( $this->msg( 'minlength1' )->escaped() );
				break;
			case UploadBase::ILLEGAL_FILENAME:
				$this->showRecoverableUploadError( $this->msg( 'illegalfilename',
					$details['filtered'] )->parse() );
				break;
			case UploadBase::FILENAME_TOO_LONG:
				$this->showRecoverableUploadError( $this->msg( 'filename-toolong' )->escaped() );
				break;
			case UploadBase::FILETYPE_MISSING:
				$this->showRecoverableUploadError( $this->msg( 'filetype-missing' )->parse() );
				break;
			case UploadBase::WINDOWS_NONASCII_FILENAME:
				$this->showRecoverableUploadError( $this->msg( 'windows-nonascii-filename' )->parse() );
				break;

			/** Statuses that require reuploading **/
			case UploadBase::EMPTY_FILE:
				$this->showUploadError( $this->msg( 'emptyfile' )->escaped() );
				break;
			case UploadBase::FILE_TOO_LARGE:
				$this->showUploadError( $this->msg( 'largefileserver' )->escaped() );
				break;
			case UploadBase::FILETYPE_BADTYPE:
				$msg = $this->msg( 'filetype-banned-type' );
				if ( isset( $details['blacklistedExt'] ) ) {
					$msg->params( $this->getLanguage()->commaList( $details['blacklistedExt'] ) );
				} else {
					$msg->params( $details['finalExt'] );
				}
				$extensions = array_unique( $wgFileExtensions );
				$msg->params( $this->getLanguage()->commaList( $extensions ),
					count( $extensions ) );

				// Add PLURAL support for the first parameter. This results
				// in a bit unlogical parameter sequence, but does not break
				// old translations
				if ( isset( $details['blacklistedExt'] ) ) {
					$msg->params( count( $details['blacklistedExt'] ) );
				} else {
					$msg->params( 1 );
				}

				$this->showUploadError( $msg->parse() );
				break;
			case UploadBase::VERIFICATION_ERROR:
				unset( $details['status'] );
				$code = array_shift( $details['details'] );
				$this->showUploadError( $this->msg( $code, $details['details'] )->parse() );
				break;
			case UploadBase::HOOK_ABORTED:
				if ( is_array( $details['error'] ) ) { # allow hooks to return error details in an array
					$args = $details['error'];
					$error = array_shift( $args );
				} else {
					$error = $details['error'];
					$args = null;
				}

				$this->showUploadError( $this->msg( $error, $args )->parse() );
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
		if ( !( $this->mUpload instanceof UploadFromStash ) ) {
			return true;
		}
		$success = $this->mUpload->unsaveUploadedFile();
		if ( !$success ) {
			$this->getOutput()->showFileDeleteError( $this->mUpload->getTempPath() );
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
	 * @param array $exists the result of UploadBase::getExistsWarning
	 * @return String: empty string if there is no warning or an HTML fragment
	 */
	public static function getExistsWarning( $exists ) {
		if ( !$exists ) {
			return '';
		}

		$file = $exists['file'];
		$filename = $file->getTitle()->getPrefixedText();
		$warning = '';

		if ( $exists['warning'] == 'exists' ) {
			// Exact match
			$warning = wfMessage( 'fileexists', $filename )->parse();
		} elseif ( $exists['warning'] == 'page-exists' ) {
			// Page exists but file does not
			$warning = wfMessage( 'filepageexists', $filename )->parse();
		} elseif ( $exists['warning'] == 'exists-normalized' ) {
			$warning = wfMessage( 'fileexists-extension', $filename,
				$exists['normalizedFile']->getTitle()->getPrefixedText() )->parse();
		} elseif ( $exists['warning'] == 'thumb' ) {
			// Swapped argument order compared with other messages for backwards compatibility
			$warning = wfMessage( 'fileexists-thumbnail-yes',
				$exists['thumbFile']->getTitle()->getPrefixedText(), $filename )->parse();
		} elseif ( $exists['warning'] == 'thumb-name' ) {
			// Image w/o '180px-' does not exists, but we do not like these filenames
			$name = $file->getName();
			$badPart = substr( $name, 0, strpos( $name, '-' ) + 1 );
			$warning = wfMessage( 'file-thumbnail-no', $badPart )->parse();
		} elseif ( $exists['warning'] == 'bad-prefix' ) {
			$warning = wfMessage( 'filename-bad-prefix', $exists['prefix'] )->parse();
		} elseif ( $exists['warning'] == 'was-deleted' ) {
			# If the file existed before and was deleted, warn the user of this
			$ltitle = SpecialPage::getTitleFor( 'Log' );
			$llink = Linker::linkKnown(
				$ltitle,
				wfMessage( 'deletionlog' )->escaped(),
				array(),
				array(
					'type' => 'delete',
					'page' => $filename
				)
			);
			$warning = wfMessage( 'filewasdeleted' )->rawParams( $llink )->parseAsBlock();
		}

		return $warning;
	}

	/**
	 * Construct a warning and a gallery from an array of duplicate files.
	 * @param $dupes array
	 * @return string
	 */
	public function getDupeWarning( $dupes ) {
		if ( !$dupes ) {
			return '';
		}

		$gallery = ImageGalleryBase::factory();
		$gallery->setContext( $this->getContext() );
		$gallery->setShowBytes( false );
		foreach ( $dupes as $file ) {
			$gallery->add( $file->getTitle() );
		}
		return '<li>' .
			wfMessage( 'file-exists-duplicate' )->numParams( count( $dupes ) )->parse() .
			$gallery->toHtml() . "</li>\n";
	}

	protected function getGroupName() {
		return 'media';
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

	protected $mMaxFileSize = array();

	protected $mMaxUploadSize = array();

	public function __construct( array $options = array(), IContextSource $context = null ) {
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
		parent::__construct( $descriptor, $context, 'upload' );

		# Set some form properties
		$this->setSubmitText( $this->msg( 'uploadbtn' )->text() );
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
		global $wgCopyUploadsFromSpecialUpload;

		if ( $this->mSessionKey ) {
			return array(
				'SessionKey' => array(
					'type' => 'hidden',
					'default' => $this->mSessionKey,
				),
				'SourceType' => array(
					'type' => 'hidden',
					'default' => 'Stash',
				),
			);
		}

		$canUploadByUrl = UploadFromUrl::isEnabled()
			&& UploadFromUrl::isAllowed( $this->getUser() )
			&& $wgCopyUploadsFromSpecialUpload;
		$radio = $canUploadByUrl;
		$selectedSourceType = strtolower( $this->getRequest()->getText( 'wpSourceType', 'File' ) );

		$descriptor = array();
		if ( $this->mTextTop ) {
			$descriptor['UploadFormTextTop'] = array(
				'type' => 'info',
				'section' => 'source',
				'default' => $this->mTextTop,
				'raw' => true,
			);
		}

		$this->mMaxUploadSize['file'] = UploadBase::getMaxUploadSize( 'file' );
		# Limit to upload_max_filesize unless we are running under HipHop and
		# that setting doesn't exist
		if ( !wfIsHipHop() ) {
			$this->mMaxUploadSize['file'] = min( $this->mMaxUploadSize['file'],
				wfShorthandToInteger( ini_get( 'upload_max_filesize' ) ),
				wfShorthandToInteger( ini_get( 'post_max_size' ) )
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
			'help' => $this->msg( 'upload-maxfilesize',
				$this->getContext()->getLanguage()->formatSize( $this->mMaxUploadSize['file'] ) )
				->parse() .
				$this->msg( 'word-separator' )->escaped() .
				$this->msg( 'upload_source_file' )->escaped(),
			'checked' => $selectedSourceType == 'file',
		);

		if ( $canUploadByUrl ) {
			$this->mMaxUploadSize['url'] = UploadBase::getMaxUploadSize( 'url' );
			$descriptor['UploadFileURL'] = array(
				'class' => 'UploadSourceField',
				'section' => 'source',
				'id' => 'wpUploadFileURL',
				'label-message' => 'sourceurl',
				'upload-type' => 'url',
				'radio' => &$radio,
				'help' => $this->msg( 'upload-maxfilesize',
					$this->getContext()->getLanguage()->formatSize( $this->mMaxUploadSize['url'] ) )
					->parse() .
					$this->msg( 'word-separator' )->escaped() .
					$this->msg( 'upload_source_url' )->escaped(),
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
		global $wgCheckFileExtensions, $wgStrictFileExtensions,
		$wgFileExtensions, $wgFileBlacklist;

		if ( $wgCheckFileExtensions ) {
			if ( $wgStrictFileExtensions ) {
				# Everything not permitted is banned
				$extensionsList =
					'<div id="mw-upload-permitted">' .
					$this->msg( 'upload-permitted', $this->getContext()->getLanguage()->commaList( array_unique( $wgFileExtensions ) ) )->parseAsBlock() .
					"</div>\n";
			} else {
				# We have to list both preferred and prohibited
				$extensionsList =
					'<div id="mw-upload-preferred">' .
						$this->msg( 'upload-preferred', $this->getContext()->getLanguage()->commaList( array_unique( $wgFileExtensions ) ) )->parseAsBlock() .
					"</div>\n" .
					'<div id="mw-upload-prohibited">' .
						$this->msg( 'upload-prohibited', $this->getContext()->getLanguage()->commaList( array_unique( $wgFileBlacklist ) ) )->parseAsBlock() .
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
		if ( $this->mSessionKey ) {
			$stash = RepoGroup::singleton()->getLocalRepo()->getUploadStash();
			try {
				$file = $stash->getFile( $this->mSessionKey );
			} catch ( MWException $e ) {
				$file = null;
			}
			if ( $file ) {
				global $wgContLang;

				$mto = $file->transform( array( 'width' => 120 ) );
				$this->addHeaderText(
					'<div class="thumb t' . $wgContLang->alignEnd() . '">' .
					Html::element( 'img', array(
						'src' => $mto->getUrl(),
						'class' => 'thumbimage',
					) ) . '</div>', 'description' );
			}
		}

		$descriptor = array(
			'DestFile' => array(
				'type' => 'text',
				'section' => 'description',
				'id' => 'wpDestFile',
				'label-message' => 'destfilename',
				'size' => 60,
				'default' => $this->mDestFile,
				# @todo FIXME: Hack to work around poor handling of the 'default' option in HTMLForm
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
				'cols' => $this->getUser()->getIntOption( 'cols' ),
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
				'message' => 'edittools-upload',
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
		$user = $this->getUser();
		if ( $user->isLoggedIn() ) {
			$descriptor = array(
				'Watchthis' => array(
					'type' => 'check',
					'id' => 'wpWatchthis',
					'label-message' => 'watchthisupload',
					'section' => 'options',
					'default' => $user->getOption( 'watchcreations' ),
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

		$descriptor['DestFileWarningAck'] = array(
			'type' => 'hidden',
			'id' => 'wpDestFileWarningAck',
			'default' => $this->mDestWarningAck ? '1' : '',
		);

		if ( $this->mForReUpload ) {
			$descriptor['ForReUpload'] = array(
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
	 * Add upload JS to the OutputPage
	 */
	protected function addUploadJS() {
		global $wgUseAjax, $wgAjaxUploadDestCheck, $wgAjaxLicensePreview, $wgEnableAPI, $wgStrictFileExtensions;

		$useAjaxDestCheck = $wgUseAjax && $wgAjaxUploadDestCheck;
		$useAjaxLicensePreview = $wgUseAjax && $wgAjaxLicensePreview && $wgEnableAPI;
		$this->mMaxUploadSize['*'] = UploadBase::getMaxUploadSize();

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
			'wgMaxUploadSize' => $this->mMaxUploadSize,
		);

		$out = $this->getOutput();
		$out->addJsConfigVars( $scriptVars );

		$out->addModules( array(
			'mediawiki.action.edit', // For <charinsert> support
			'mediawiki.legacy.upload', // Old form stuff...
			'mediawiki.special.upload', // Newer extras for thumbnail preview.
		) );
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

	/**
	 * @param $cellAttributes array
	 * @return string
	 */
	function getLabelHtml( $cellAttributes = array() ) {
		$id = $this->mParams['id'];
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

	/**
	 * @return int
	 */
	function getSize() {
		return isset( $this->mParams['size'] )
			? $this->mParams['size']
			: 60;
	}
}
