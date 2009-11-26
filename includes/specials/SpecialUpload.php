<?php
/**
 * @file
 * @ingroup SpecialPage
 * @ingroup Upload
 *
 * Form for handling uploads and special page.
 *
 */

class SpecialUpload extends SpecialPage {
	/**
	 * Constructor : initialise object
	 * Get data POSTed through the form and assign them to the object
	 * @param WebRequest $request Data posted.
	 */
	public function __construct( $request = null ) {
		global $wgRequest;

		parent::__construct( 'Upload', 'upload' );

		$this->loadRequest( is_null( $request ) ? $wgRequest : $request );
	}

	/** Misc variables **/
	protected $mRequest;			// The WebRequest or FauxRequest this form is supposed to handle
	protected $mSourceType;
	protected $mUpload;
	protected $mLocalFile;
	protected $mUploadClicked;

	/** User input variables from the "description" section **/
	protected $mDesiredDestName;	// The requested target file name
	protected $mComment;
	protected $mLicense;

	/** User input variables from the root section **/
	protected $mIgnoreWarning;
	protected $mWatchThis;
	protected $mCopyrightStatus;
	protected $mCopyrightSource;

	/** Hidden variables **/
	protected $mForReUpload;		// The user followed an "overwrite this file" link
	protected $mCancelUpload;		// The user clicked "Cancel and return to upload form" button
	protected $mTokenOk;

	/**
	 * Initialize instance variables from request and create an Upload handler
	 *
	 * @param WebRequest $request The request to extract variables from
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
		if( !$this->mDesiredDestName )
			$this->mDesiredDestName = $request->getText( 'wpUploadFile' );
		$this->mComment           = $request->getText( 'wpUploadDescription' );
		$this->mLicense           = $request->getText( 'wpLicense' );


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
	}

	/**
	 * This page can be shown if uploading is enabled.
	 * Handle permission checking elsewhere in order to be able to show
	 * custom error messages.
	 *
	 * @param User $user
	 * @return bool
	 */
	public function userCanExecute( $user ) {
		return UploadBase::isEnabled() && parent::userCanExecute( $user );
	}

	/**
	 * Special page entry point
	 */
	public function execute( $par ) {
		global $wgUser, $wgOut, $wgRequest;

		$this->setHeaders();
		$this->outputHeader();

		# Check uploading enabled
		if( !UploadBase::isEnabled() ) {
			$wgOut->showErrorPage( 'uploaddisabled', 'uploaddisabledtext' );
			return;
		}

		# Check permissions
		global $wgGroupPermissions;
		if( !$wgUser->isAllowed( 'upload' ) ) {
			if( !$wgUser->isLoggedIn() && ( $wgGroupPermissions['user']['upload']
				|| $wgGroupPermissions['autoconfirmed']['upload'] ) ) {
				// Custom message if logged-in users without any special rights can upload
				$wgOut->showErrorPage( 'uploadnologin', 'uploadnologintext' );
			} else {
				$wgOut->permissionRequired( 'upload' );
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
			if ( !$this->unsaveUploadedFile() )
				# Something went wrong, so unsaveUploadedFile showed a warning
				return;
		}

		# Process upload or show a form
		if ( $this->mTokenOk && !$this->mCancelUpload
				&& ( $this->mUpload && $this->mUploadClicked ) ) {
			$this->processUpload();
		} else {
			$this->showUploadForm( $this->getUploadForm() );
		}

		# Cleanup
		if ( $this->mUpload )
			$this->mUpload->cleanupTempFile();
	}

	/**
	 * Show the main upload form and optionally add the session key to the
	 * output. This hides the source selection.
	 *
	 * @param string $message HTML message to be shown at top of form
	 * @param string $sessionKey Session key of the stashed upload
	 */
	protected function showUploadForm( $form ) {
		# Add links if file was previously deleted
		if ( !$this->mDesiredDestName )
			$this->showViewDeletedLinks();

		$form->show();
	}

	/**
	 * Get an UploadForm instance with title and text properly set.
	 *
	 * @param string $message HTML string to add to the form
	 * @param string $sessionKey Session key in case this is a stashed upload
	 * @return UploadForm
	 */
	protected function getUploadForm( $message = '', $sessionKey = '', $hideIgnoreWarning = false ) {
		global $wgOut;
		
		# Initialize form
		$form = new UploadForm( array(
			'watch' => $this->watchCheck(), 
			'forreupload' => $this->mForReUpload, 
			'sessionkey' => $sessionKey,
			'hideignorewarning' => $hideIgnoreWarning,
		) );
		$form->setTitle( $this->getTitle() );

		# Check the token, but only if necessary
		if( !$this->mTokenOk && !$this->mCancelUpload
				&& ( $this->mUpload && $this->mUploadClicked ) )
			$form->addPreText( wfMsgExt( 'session_fail_preview', 'parseinline' ) );

		# Add text to form
		$form->addPreText( '<div id="uploadtext">' . wfMsgExt( 'uploadtext', 'parse' ) . '</div>');
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
		if( $title instanceof Title && ( $count = $title->isDeleted() ) > 0 && $wgUser->isAllowed( 'deletedhistory' ) ) {
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
	 * @param string $message HTML message to be passed to mainUploadForm
	 */
	protected function recoverableUploadError( $message ) {
		$sessionKey = $this->mUpload->stashSession();
		$message = '<h2>' . wfMsgHtml( 'uploadwarning' ) . "</h2>\n" .
			'<div class="error">' . $message . "</div>\n";
		
		$form = $this->getUploadForm( $message, $sessionKey );
		$form->setSubmitText( wfMsg( 'upload-tryagain' ) );
		$this->showUploadForm( $form );
	}
	/**
	 * Stashes the upload, shows the main form, but adds an "continue anyway button"
	 *
	 * @param array $warnings
	 */
	protected function uploadWarning( $warnings ) {
		global $wgUser;

		$sessionKey = $this->mUpload->stashSession();

		$sk = $wgUser->getSkin();

		$warningHtml = '<h2>' . wfMsgHtml( 'uploadwarning' ) . "</h2>\n"
			. '<ul class="warning">';
		foreach( $warnings as $warning => $args ) {
				$msg = '';
				if( $warning == 'exists' ) {
					$msg = self::getExistsWarning( $args );
				} elseif( $warning == 'duplicate' ) {
					$msg = self::getDupeWarning( $args );
				} elseif( $warning == 'duplicate-archive' ) {
					$msg = "\t<li>" . wfMsgExt( 'file-deleted-duplicate', 'parseinline',
							array( Title::makeTitle( NS_FILE, $args )->getPrefixedText() ) )
						. "</li>\n";
				} else {
					if ( is_bool( $args ) )
						$args = array();
					elseif ( !is_array( $args ) )
						$args = array( $args );
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
	}

	/**
	 * Show the upload form with error message, but do not stash the file.
	 *
	 * @param string $message
	 */
	protected function uploadError( $message ) {
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

		// Verify permissions
		$permErrors = $this->mUpload->verifyPermissions( $wgUser );
		if( $permErrors !== true )
			return $wgOut->showPermissionsErrorPage( $permErrors );

		// Fetch the file if required
		$status = $this->mUpload->fetchFile();
		if( !$status->isOK() )
			return $this->showUploadForm( $this->getUploadForm( $wgOut->parse( $status->getWikiText() ) ) );

		// Upload verification
		$details = $this->mUpload->verifyUpload();
		if ( $details['status'] != UploadBase::OK )
			return $this->processVerificationError( $details );

		$this->mLocalFile = $this->mUpload->getLocalFile();

		// Check warnings if necessary
		if( !$this->mIgnoreWarning ) {
			$warnings = $this->mUpload->checkWarnings();
			if( count( $warnings ) )
				return $this->uploadWarning( $warnings );
		}

		// Get the page text if this is not a reupload
		if( !$this->mForReUpload ) {
			$pageText = self::getInitialPageText( $this->mComment, $this->mLicense,
				$this->mCopyrightStatus, $this->mCopyrightSource );
		} else {
			$pageText = false;
		}
		$status = $this->mUpload->performUpload( $this->mComment, $pageText, $this->mWatchthis, $wgUser );
		if ( !$status->isGood() )
			return $this->uploadError( $wgOut->parse( $status->getWikiText() ) );

		// Success, redirect to description page
		wfRunHooks( 'SpecialUploadComplete', array( &$this ) );
		$wgOut->redirect( $this->mLocalFile->getTitle()->getFullURL() );

	}

	/**
	 * Get the initial image page text based on a comment and optional file status information
	 */
	public static function getInitialPageText( $comment = '', $license = '', $copyStatus = '', $source = '' ) {
		global $wgUseCopyrightUpload;
		if ( $wgUseCopyrightUpload ) {
			$licensetxt = '';
			if ( $license != '' ) {
				$licensetxt = '== ' . wfMsgForContent( 'license-header' ) . " ==\n" . '{{' . $license . '}}' . "\n";
			}
			$pageText = '== ' . wfMsgForContent ( 'filedesc' ) . " ==\n" . $comment . "\n" .
			  '== ' . wfMsgForContent ( 'filestatus' ) . " ==\n" . $copyStatus . "\n" .
			  "$licensetxt" .
			  '== ' . wfMsgForContent ( 'filesource' ) . " ==\n" . $source ;
		} else {
			if ( $license != '' ) {
				$filedesc = $comment == '' ? '' : '== ' . wfMsgForContent ( 'filedesc' ) . " ==\n" . $comment . "\n";
				 $pageText = $filedesc .
					 '== ' . wfMsgForContent ( 'license-header' ) . " ==\n" . '{{' . $license . '}}' . "\n";
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
	protected function watchCheck() {
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
	 * @param array $details Result of UploadBase::verifyUpload
	 */
	protected function processVerificationError( $details ) {
		global $wgFileExtensions, $wgLang;

		switch( $details['status'] ) {

			/** Statuses that only require name changing **/
			case UploadBase::MIN_LENGTH_PARTNAME:
				$this->recoverableUploadError( wfMsgHtml( 'minlength1' ) );
				break;
			case UploadBase::ILLEGAL_FILENAME:
				$this->recoverableUploadError( wfMsgExt( 'illegalfilename',
					'parseinline', $details['filtered'] ) );
				break;
			case UploadBase::OVERWRITE_EXISTING_FILE:
				$this->recoverableUploadError( wfMsgExt( $details['overwrite'],
					'parseinline' ) );
				break;
			case UploadBase::FILETYPE_MISSING:
				$this->recoverableUploadError( wfMsgExt( 'filetype-missing',
					'parseinline' ) );
				break;

			/** Statuses that require reuploading **/
			case UploadBase::EMPTY_FILE:
				$this->showUploadForm( $this->getUploadForm( wfMsgHtml( 'emptyfile' ) ) );
				break;
			case UploadBase::FILETYPE_BADTYPE:
				$finalExt = $details['finalExt'];
				$this->uploadError(
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
				$this->uploadError( wfMsgExt( $code, 'parseinline', $details['details'] ) );
				break;
			case UploadBase::HOOK_ABORTED:
				if ( is_array( $details['error'] ) ) { # allow hooks to return error details in an array
					$args = $details['error'];
					$error = array_shift( $args );
				} else {
					$error = $details['error'];
					$args = null;
				}

				$this->uploadError( wfMsgExt( $error, 'parseinline', $args ) );
				break;
			default:
				throw new MWException( __METHOD__ . ": Unknown value `{$details['status']}`" );
		}
	}

	/**
	 * Remove a temporarily kept file stashed by saveTempUploadedFile().
	 * @access private
	 * @return success
	 */
	protected function unsaveUploadedFile() {
		global $wgOut;
		if ( !( $this->mUpload instanceof UploadFromStash ) )
			return true;
		$success = $this->mUpload->unsaveUploadedFile();
		if ( ! $success ) {
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
	 * @param array $exists The result of UploadBase::getExistsWarning
	 * @return string Empty string if there is no warning or an HTML fragment
	 * consisting of one or more <li> elements if there is a warning.
	 */
	public static function getExistsWarning( $exists ) {
		global $wgUser, $wgContLang;

		if ( !$exists )
			return '';

		$file = $exists['file'];
		$filename = $file->getTitle()->getPrefixedText();
		$warning = array();

		$sk = $wgUser->getSkin();

		if( $exists['warning'] == 'exists' ) {
			// Exact match
			$warning[] = '<li>' . wfMsgExt( 'fileexists', 'parseinline', $filename ) . '</li>';
		} elseif( $exists['warning'] == 'page-exists' ) {
			// Page exists but file does not
			$warning[] = '<li>' . wfMsgExt( 'filepageexists', 'parseinline', $filename ) . '</li>';
		} elseif ( $exists['warning'] == 'exists-normalized' ) {
			$warning[] = '<li>' . wfMsgExt( 'fileexists-extension', 'parseinline', $filename,
				$exists['normalizedFile']->getTitle()->getPrefixedText() ) . '</li>';
		} elseif ( $exists['warning'] == 'thumb' ) {
			// Swapped argument order compared with other messages for backwards compatibility
			$warning[] = '<li>' . wfMsgExt( 'fileexists-thumbnail-yes', 'parseinline',
				$exists['thumbFile']->getTitle()->getPrefixedText(), $filename ) . '</li>';
		} elseif ( $exists['warning'] == 'thumb-name' ) {
			// Image w/o '180px-' does not exists, but we do not like these filenames
			$name = $file->getName();
			$badPart = substr( $name, 0, strpos( $name, '-' ) + 1 );
			$warning[] = '<li>' . wfMsgExt( 'file-thumbnail-no', 'parseinline', $badPart ) . '</li>';
		} elseif ( $exists['warning'] == 'bad-prefix' ) {
			$warning[] = '<li>' . wfMsgExt( 'filename-bad-prefix', 'parseinline', $exists['prefix'] ) . '</li>';
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
			$warning[] = '<li>' . wfMsgWikiHtml( 'filewasdeleted', $llink ) . '</li>';
		}

		return implode( "\n", $warning );
	}

	/**
	 * Get a list of warnings
	 *
	 * @param string local filename, e.g. 'file exists', 'non-descriptive filename'
	 * @return array list of warning messages
	 */
	public static function ajaxGetExistsWarning( $filename ) {
		$file = wfFindFile( $filename );
		if( !$file ) {
			// Force local file so we have an object to do further checks against
			// if there isn't an exact match...
			$file = wfLocalFile( $filename );
		}
		$s = '&nbsp;';
		if ( $file ) {
			$exists = UploadBase::getExistsWarning( $file );
			$warning = self::getExistsWarning( $exists );
			if ( $warning !== '' ) {
				$s = "<ul>$warning</ul>";
			}
		}
		return $s;
	}

	/**
	 * Render a preview of a given license for the AJAX preview on upload
	 *
	 * @param string $license
	 * @return string
	 */
	public static function ajaxGetLicensePreview( $license ) {
		global $wgParser, $wgUser;
		$text = '{{' . $license . '}}';
		$title = Title::makeTitle( NS_FILE, 'Sample.jpg' );
		$options = ParserOptions::newFromUser( $wgUser );

		// Expand subst: first, then live templates...
		$text = $wgParser->preSaveTransform( $text, $title, $wgUser, $options );
		$output = $wgParser->parse( $text, $title, $options );

		return $output->getText();
	}

	/**
	 * Construct a warning and a gallery from an array of duplicate files.
	 */
	public static function getDupeWarning( $dupes ) {
		if( $dupes ) {
			global $wgOut;
			$msg = "<gallery>";
			foreach( $dupes as $file ) {
				$title = $file->getTitle();
				$msg .= $title->getPrefixedText() .
					"|" . $title->getText() . "\n";
			}
			$msg .= "</gallery>";
			return "<li>" .
				wfMsgExt( "file-exists-duplicate", array( "parse" ), count( $dupes ) ) .
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
	
	protected $mSourceIds;

	public function __construct( $options = array() ) {
		global $wgLang;

		$this->mWatch = !empty( $options['watch'] );
		$this->mForReUpload = !empty( $options['forreupload'] );
		$this->mSessionKey = isset( $options['sessionkey'] ) 
				? $options['sessionkey'] : '';
		$this->mHideIgnoreWarning = !empty( $options['hideignorewarning'] );

		$sourceDescriptor = $this->getSourceSection();
		$descriptor = $sourceDescriptor
			+ $this->getDescriptionSection()
			+ $this->getOptionsSection();

		wfRunHooks( 'UploadFormInitDescriptor', array( $descriptor ) );
		parent::__construct( $descriptor, 'upload' );

		# Set some form properties
		$this->setSubmitText( wfMsg( 'uploadbtn' ) );
		$this->setSubmitName( 'wpUpload' );
		$this->setSubmitTooltip( 'upload' );
		$this->setId( 'mw-upload-form' );

		# Build a list of IDs for javascript insertion
		$this->mSourceIds = array();
		foreach ( $sourceDescriptor as $key => $field ) {
			if ( !empty( $field['id'] ) )
				$this->mSourceIds[] = $field['id'];
		}

	}

	/**
	 * Get the descriptor of the fieldset that contains the file source 
	 * selection. The section is 'source'
	 * 
	 * @return array Descriptor array
	 */
	protected function getSourceSection() {
		global $wgLang, $wgUser, $wgRequest;

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
							wfShorthandToInteger( ini_get( 'upload_max_filesize' ) )
						)
					) . ' ' . wfMsgHtml( 'upload_source_file' ),
				'checked' => $selectedSourceType == 'file',
		);
		if ( $canUploadByUrl ) {
			global $wgMaxUploadSize;
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
	 * @return string HTML string containing the message
	 */
	protected function getExtensionsMessage() {
		# Print a list of allowed file extensions, if so configured.  We ignore
		# MIME type here, it's incomprehensible to most people and too long.
		global $wgLang, $wgCheckFileExtensions, $wgStrictFileExtensions,
		$wgFileExtensions, $wgFileBlacklist;

		$allowedExtensions = '';
		if( $wgCheckFileExtensions ) {
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
	 * @return array Descriptor array
	 */
	protected function getDescriptionSection() {
		global $wgUser, $wgOut;

		$cols = intval( $wgUser->getOption( 'cols' ) );
		if( $wgUser->getOption( 'editwidth' ) ) {
			$wgOut->addInlineStyle( '#mw-htmlform-description { width: 100%; }' );
		}

		$descriptor = array(
			'DestFile' => array(
				'type' => 'text',
				'section' => 'description',
				'id' => 'wpDestFile',
				'label-message' => 'destfilename',
				'size' => 60,
			),
			'UploadDescription' => array(
				'type' => 'textarea',
				'section' => 'description',
				'id' => 'wpUploadDescription',
				'label-message' => $this->mForReUpload
					? 'filereuploadsummary'
					: 'fileuploadsummary',
				'cols' => $cols,
				'rows' => 8,
			),
			'EditTools' => array(
				'type' => 'edittools',
				'section' => 'description',
			),
			'License' => array(
				'type' => 'select',
				'class' => 'Licenses',
				'section' => 'description',
				'id' => 'wpLicense',
				'label-message' => 'license',
			),
		);
		if ( $this->mForReUpload )
			$descriptor['DestFile']['readonly'] = true;

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
	 * @return array Descriptor array
	 */
	protected function getOptionsSection() {
		global $wgUser, $wgOut;

		if( $wgUser->isLoggedIn() ) {
			$descriptor = array(
				'Watchthis' => array(
					'type' => 'check',
					'id' => 'wpWatchthis',
					'label-message' => 'watchthisupload',
					'section' => 'options',
				)
			);
		}
		if( !$this->mHideIgnoreWarning ) {
			$descriptor['IgnoreWarning'] = array(
				'type' => 'check',
				'id' => 'wpIgnoreWarning',
				'label-message' => 'ignorewarnings',
				'section' => 'options',
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
	 * 
	 * @param bool $autofill Whether or not to autofill the destination
	 * 	filename text box
	 */
	protected function addUploadJS( ) {
		global $wgUseAjax, $wgAjaxUploadDestCheck, $wgAjaxLicensePreview;
		global $wgOut;

		$useAjaxDestCheck = $wgUseAjax && $wgAjaxUploadDestCheck;
		$useAjaxLicensePreview = $wgUseAjax && $wgAjaxLicensePreview;

		$scriptVars = array(
			'wgAjaxUploadDestCheck' => $wgUseAjax && $wgAjaxUploadDestCheck,
			'wgAjaxLicensePreview' => $wgUseAjax && $wgAjaxLicensePreview,
			'wgUploadAutoFill' => !$this->mForReUpload,
			'wgUploadSourceIds' => $this->mSourceIds,
		);

		$wgOut->addScript( Skin::makeVariablesScript( $scriptVars ) );
		
		// For <charinsert> support
		$wgOut->addScriptFile( 'edit.js' );
		$wgOut->addScriptFile( 'upload.js' );
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
	function getLabelHtml() {
		$id = "wpSourceType{$this->mParams['upload-type']}";
		$label = Html::rawElement( 'label', array( 'for' => $id ), $this->mLabel  );

		if ( !empty( $this->mParams['radio'] ) ) {
			$attribs = array(
				'name' => 'wpSourceType',
				'type' => 'radio',
				'id' => $id,
				'value' => $this->mParams['upload-type'],
			);
			if ( !empty( $this->mParams['checked'] ) )
				$attribs['checked'] = 'checked';
			$label .= Html::element( 'input', $attribs );
		}

		return Html::rawElement( 'td', array( 'class' => 'mw-label' ), $label );
	}
	function getSize() {
		return isset( $this->mParams['size'] )
			? $this->mParams['size']
			: 60;
	}
}

