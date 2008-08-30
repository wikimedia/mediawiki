<?php
/**
 * @file
 * @ingroup SpecialPage
 */


/**
 * Entry point
 */
function wfSpecialUpload() {
	global $wgRequest;
	$form = new UploadForm( $wgRequest );
	$form->execute();
}

/**
 * implements Special:Upload
 * @ingroup SpecialPage
 */
class UploadForm {
	/**#@+
	 * @access private
	 */
	var $mComment, $mLicense, $mIgnoreWarning;
	var $mCopyrightStatus, $mCopyrightSource, $mReUpload, $mAction, $mUploadClicked;
	var $mDestWarningAck;
	var $mLocalFile;
	
	var $mUpload;	// Instance of UploadFromBase or derivative

	# Placeholders for text injection by hooks (must be HTML)
	# extensions should take care to _append_ to the present value
	var $uploadFormTextTop;
	var $uploadFormTextAfterSummary;

	/**#@-*/

	/**
	 * Constructor : initialise object
	 * Get data POSTed through the form and assign them to the object
	 * @param $request Data posted.
	 */
	function __construct( &$request ) {
		$this->mDesiredDestName   = $request->getText( 'wpDestFile' );
		$this->mIgnoreWarning     = $request->getCheck( 'wpIgnoreWarning' );
		$this->mComment           = $request->getText( 'wpUploadDescription' );

		if( !$request->wasPosted() ) {
			# GET requests just give the main form; no data except destination
			# filename and description
			return;
		}

		# Placeholders for text injection by hooks (empty per default)
		$this->uploadFormTextTop = "";
		$this->uploadFormTextAfterSummary = "";

		$this->mReUpload          = $request->getCheck( 'wpReUpload' );
		$this->mUploadClicked     = $request->getCheck( 'wpUpload' );

		$this->mLicense           = $request->getText( 'wpLicense' );
		$this->mCopyrightStatus   = $request->getText( 'wpUploadCopyStatus' );
		$this->mCopyrightSource   = $request->getText( 'wpUploadSource' );
		$this->mWatchthis         = $request->getBool( 'wpWatchthis' );
		$this->mSourceType        = $request->getText( 'wpSourceType' );
		$this->mDestWarningAck    = $request->getText( 'wpDestFileWarningAck' );

		$this->mAction            = $request->getVal( 'action' );

		$desiredDestName = $request->getText( 'wpDestFile' );
		if( !$desiredDestName )
			$desiredDestName = $request->getText( 'wpUploadFile' );

		$this->mSessionKey        = $request->getInt( 'wpSessionKey' );
		if( !empty( $this->mSessionKey ) &&
				isset( $_SESSION['wsUploadData'][$this->mSessionKey]['version'] ) &&
				$_SESSION['wsUploadData'][$this->mSessionKey]['version'] == 
					UploadFromBase::SESSION_VERSION ) {
			/**
			 * Confirming a temporarily stashed upload.
			 * We don't want path names to be forged, so we keep
			 * them in the session on the server and just give
			 * an opaque key to the user agent.
			 */
			
			$this->mUpload = new UploadFromStash( $desiredDestName );
			$data = $_SESSION['wsUploadData'][$this->mSessionKey];
			$this->mUpload->initialize( $data );
			
		} else {
			/**
			 *Check for a newly uploaded file.
			 */
			if( UploadFromUrl::isEnabled() && $this->mSourceType == 'web' ) {
				$this->mUpload = new UploadFromUrl( $desiredDestName );
				$this->mUpload->initialize( $request->getText( 'wpUploadFileURL' ) );
			} else {
				$this->mUpload = new UploadFromUpload( $desiredDestName );
				$this->mUpload->initialize(
					$request->getFileTempName( 'wpUploadFile' ),
					$request->getFileSize( 'wpUploadFile' ),
					$request->getFileName( 'wpUploadFile' )
				);
			}
		}
	}



	/**
	 * Start doing stuff
	 * @access public
	 */
	function execute() {
		global $wgUser, $wgOut;

		# Check uploading enabled
		if( !UploadFromBase::isEnabled() ) {
			$wgOut->showErrorPage( 'uploaddisabled', 'uploaddisabledtext', array( $this->mDesiredDestName ) );
			return;
		}

		# Check permissions
		if( $this->mUpload ) {
			$permission = $this->mUpload->isAllowed( $wgUser );
		} else {
			$permission = $wgUser->isAllowed( 'upload' ) ? true : 'upload';
		}
		if( $permission !== true ) {
			if( !$wgUser->isLoggedIn() ) {
				$wgOut->showErrorPage( 'uploadnologin', 'uploadnologintext' );
			} else {
				$wgOut->permissionRequired( $permission );
			}
			return;
		}

		# Check blocks
		if( $wgUser->isBlocked() ) {
			$wgOut->blockedPage();
			return;
		}

		if( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}

		if( $this->mReUpload ) {
			// User did not choose to ignore warnings
			if( !$this->mUpload->unsaveUploadedFile() ) {
				return;
			}
			# Because it is probably checked and shouldn't be
			$this->mIgnoreWarning = false;
			
			$this->mainUploadForm();
		} elseif( $this->mUpload && ( 
					'submit' == $this->mAction || 
					$this->mUploadClicked 
				) ) {
			$this->processUpload();
		} else {
			$this->mainUploadForm();
		}
		
		if( $this->mUpload )
			$this->mUpload->cleanupTempFile();
	}

	/**
	 * Do the upload
	 * Checks are made in SpecialUpload::execute()
	 *
	 * @access private
	 */
	function processUpload(){
		global $wgUser, $wgOut, $wgFileExtensions, $wgLang;
	 	$details = null;
	 	$value = null;
	 	$value = $this->internalProcessUpload( $details );

	 	switch($value) {
			case UploadFromBase::SUCCESS:
				$wgOut->redirect( $this->mLocalFile->getTitle()->getFullURL() );
				break;

			case UploadFromBase::BEFORE_PROCESSING:
				// Do... nothing? Why?
				break;

			case UploadFromBase::LARGE_FILE_SERVER:
				$this->mainUploadForm( wfMsgHtml( 'largefileserver' ) );
				break;

			case UploadFromBase::EMPTY_FILE:
				$this->mainUploadForm( wfMsgHtml( 'emptyfile' ) );
				break;

			case UploadFromBase::MIN_LENGHT_PARTNAME:
				$this->mainUploadForm( wfMsgHtml( 'minlength1' ) );
				break;

			case UploadFromBase::ILLEGAL_FILENAME:
				$this->uploadError( wfMsgExt( 'illegalfilename',
					'parseinline', $details['filtered'] ) );
				break;

			case UploadFromBase::PROTECTED_PAGE:
				$wgOut->showPermissionsErrorPage( $details['permissionserrors'] );
				break;

			case UploadFromBase::OVERWRITE_EXISTING_FILE:
				$this->uploadError( wfMsgExt( $details['overwrite'],
					'parseinline' ) );
				break;

			case UploadFromBase::FILETYPE_MISSING:
				$this->uploadError( wfMsgExt( 'filetype-missing', array ( 'parseinline' ) ) );
				break;

			case UploadFromBase::FILETYPE_BADTYPE:
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

			case UploadFromBase::VERIFICATION_ERROR:
				$args = $details['veri'];
				$code = array_shift( $args );
				$this->uploadError( wfMsgExt( $code, 'parseinline', $args ) );
				break;

			case UploadFromBase::UPLOAD_VERIFICATION_ERROR:
				$error = $details['error'];
				$this->uploadError( wfMsgExt( $error, 'parseinline' ) );
				break;

			case UploadFromBase::UPLOAD_WARNING:
				$warning = $details['warning'];
				$this->uploadWarning( $warning );
				break;

			case UploadFromBase::INTERNAL_ERROR:
				$status = $details['internal'];
				$this->showError( $wgOut->parse( $status->getWikiText() ) );
				break;

			default:
				throw new MWException( __METHOD__ . ": Unknown value `{$value}`" );
	 	}
	}

	/**
	 * Really do the upload
	 * Checks are made in SpecialUpload::execute()
	 *
	 * @param array $resultDetails contains result-specific dict of additional values
	 *
	 * @access private
	 */
	function internalProcessUpload( &$resultDetails ) {
		global $wgUser;

		if( !wfRunHooks( 'UploadForm:BeforeProcessing', array( &$this ) ) )
		{
			wfDebug( "Hook 'UploadForm:BeforeProcessing' broke processing the file." );
			return UploadFromBase::BEFORE_PROCESSING;
		}

		/**
		 * If the image is protected, non-sysop users won't be able
		 * to modify it by uploading a new revision.
		 */
		$permErrors = $this->mUpload->verifyPermissions( $wgUser );
		if( $permErrors !== true ) {
			$resultDetails = array( 'permissionserrors' => $permErrors );
			return UploadFromBase::PROTECTED_PAGE;
		}

		// Check whether this is a sane upload
		$result = $this->mUpload->verifyUpload( $resultDetails );
		if( $result != UploadFromBase::OK )
			return $result;

		$this->mLocalFile = $this->mUpload->getLocalFile();

		if( !$this->mIgnoreWarning ) {
			$warnings = $this->mUpload->checkWarnings();
	
			if( count( $warnings ) ) {
				$resultDetails = array( 'warning' => $warnings );
				return UploadFromBase::UPLOAD_WARNING;
			}
		}


		/**
		 * Try actually saving the thing...
		 * It will show an error form on failure. No it will not.
		 */
		$pageText = self::getInitialPageText( $this->mComment, $this->mLicense,
			$this->mCopyrightStatus, $this->mCopyrightSource );

		$status = $this->mUpload->performUpload( $this->mComment, $pageText, $this->mWatchthis, $wgUser );

		if ( !$status->isGood() ) {
			$resultDetails = array( 'internal' => $status );
			return UploadFromBase::INTERNAL_ERROR;
		} else {
			// Success, redirect to description page
			// WTF WTF WTF?
			$img = null; // @todo: added to avoid passing a ref to null - should this be defined somewhere?
			wfRunHooks( 'SpecialUploadComplete', array( &$this ) );
			return UploadFromBase::SUCCESS;
		}
	}

	/**
	 * Do existence checks on a file and produce a warning
	 * This check is static and can be done pre-upload via AJAX
	 * Returns an HTML fragment consisting of one or more LI elements if there is a warning
	 * Returns an empty string if there is no warning
	 */
	static function getExistsWarning( $exists ) {
		global $wgUser, $wgContLang;
		
		if( $exists === false )
			return '';
		
		$warning = '';
		$align = $wgContLang->isRtl() ? 'left' : 'right';

		list( $existsType, $file ) = $exists;

		$sk = $wgUser->getSkin();

		if( $existsType == 'exists' ) {
			// Exact match
			$dlink = $sk->makeKnownLinkObj( $file->getTitle() );
			if ( $file->allowInlineDisplay() ) {
				$dlink2 = $sk->makeImageLinkObj( $file->getTitle(), wfMsgExt( 'fileexists-thumb', 'parseinline' ),
					$file->getName(), $align, array(), false, true );
			} elseif ( !$file->allowInlineDisplay() && $file->isSafeFile() ) {
				$icon = $file->iconThumb();
				$dlink2 = '<div style="float:' . $align . '" id="mw-media-icon">' .
					$icon->toHtml( array( 'desc-link' => true ) ) . '<br />' . $dlink . '</div>';
			} else {
				$dlink2 = '';
			}

			$warning .= '<li>' . wfMsgExt( 'fileexists', array('parseinline','replaceafter'), $dlink ) . '</li>' . $dlink2;

		} elseif( $existsType == 'page-exists' ) {
			$lnk = $sk->makeKnownLinkObj( $file->getTitle(), '', 'redirect=no' );
			$warning .= '<li>' . wfMsgExt( 'filepageexists', array( 'parseinline', 'replaceafter' ), $lnk ) . '</li>';
		} elseif ( $existsType == 'exists-normalized' ) {
			# Check if image with lowercase extension exists.
			# It's not forbidden but in 99% it makes no sense to upload the same filename with uppercase extension
			$dlink = $sk->makeKnownLinkObj( $file->getTitle() );
			if ( $file->allowInlineDisplay() ) {
				$dlink2 = $sk->makeImageLinkObj( $file->getTitle(), wfMsgExt( 'fileexists-thumb', 'parseinline' ),
					$file->getTitle()->getText(), $align, array(), false, true );
			} elseif ( !$file->allowInlineDisplay() && $file->isSafeFile() ) {
				$icon = $file->iconThumb();
				$dlink2 = '<div style="float:' . $align . '" id="mw-media-icon">' .
					$icon->toHtml( array( 'desc-link' => true ) ) . '<br />' . $dlink . '</div>';
			} else {
				$dlink2 = '';
			}

			$warning .= '<li>' .
				wfMsgExt( 'fileexists-extension', 'parsemag',
					$file->getTitle()->getPrefixedText(), $dlink ) .
				'</li>' . $dlink2;

		} elseif ( $existsType == 'thumb' ) {
			# Check if an image without leading '180px-' (or similiar) exists
			$dlink = $sk->makeKnownLinkObj( $file->getTitle() );
			if ( $file->allowInlineDisplay() ) {
				$dlink2 = $sk->makeImageLinkObj( $file->getTitle(),
					wfMsgExt( 'fileexists-thumb', 'parseinline' ),
					$file->getTitle()->getText(), $align, array(), false, true );
			} elseif ( !$file->allowInlineDisplay() && $file->isSafeFile() ) {
				$icon = $file->iconThumb();
				$dlink2 = '<div style="float:' . $align . '" id="mw-media-icon">' .
					$icon->toHtml( array( 'desc-link' => true ) ) . '<br />' .
					$dlink . '</div>';
			} else {
				$dlink2 = '';
			}
			$warning .= '<li>' . wfMsgExt( 'fileexists-thumbnail-yes', 'parsemag', $dlink ) .
				'</li>' . $dlink2;
		}
		return $warning;
	}
	
	/**
	 * Get a list of warnings
	 *
	 * @param string local filename, e.g. 'file exists', 'non-descriptive filename'
	 * @return array list of warning messages
	 */
	static function ajaxGetExistsWarning( $filename ) {
		$file = wfFindFile( $filename );
		if( !$file ) {
			// Force local file so we have an object to do further checks against
			// if there isn't an exact match...
			$file = wfLocalFile( $filename );
		}
		$s = '&nbsp;';
		if ( $file ) {
			$exists = UploadFromBase::getExistsWarning( $file );			
			$warning = self::getExistsWarning( $exists );
			// FIXME: We probably also want the prefix blacklist and the wasdeleted check here
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
		$title = Title::makeTitle( NS_IMAGE, 'Sample.jpg' );
		$options = ParserOptions::newFromUser( $wgUser );

		// Expand subst: first, then live templates...
		$text = $wgParser->preSaveTransform( $text, $title, $wgUser, $options );
		$output = $wgParser->parse( $text, $title, $options );

		return $output->getText();
	}
	
	/**
	 * Check for duplicate files and throw up a warning before the upload
	 * completes.
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

	/**
	 * Remove a temporarily kept file stashed by saveTempUploadedFile().
	 * @access private
	 * @return success
	 */
	function unsaveUploadedFile() {
		global $wgOut;
		$success = $this->mUpload->unsaveUploadedFile();
		if ( ! $success ) {
			$wgOut->showFileDeleteError( $this->mUpload->getTempPath() );
			return false;
		} else {
			return true;
		}
	}

	/* -------------------------------------------------------------- */

	/**
	 * @param string $error as HTML
	 * @access private
	 */
	function uploadError( $error ) {
		global $wgOut;
		$wgOut->addHTML( '<h2>' . wfMsgHtml( 'uploadwarning' ) . "</h2>\n" );
		$wgOut->addHTML( '<span class="error">' . $error . '</span>' );
	}

	/**
	 * There's something wrong with this file, not enough to reject it
	 * totally but we require manual intervention to save it for real.
	 * Stash it away, then present a form asking to confirm or cancel.
	 *
	 * @param string $warning as HTML
	 * @access private
	 */
	function uploadWarning( $warnings ) {
		global $wgOut, $wgUser;
		global $wgUseCopyrightUpload;

		$this->mSessionKey = $this->mUpload->stashSession();
		if( !$this->mSessionKey ) {
			# Couldn't save file; an error has been displayed so let's go.
			return;
		}
		
		$sk = $wgUser->getSkin();

		$wgOut->addHTML( '<h2>' . wfMsgHtml( 'uploadwarning' ) . "</h2>\n" );
		$wgOut->addHTML( '<ul class="warning">' );
		foreach( $warnings as $warning => $args ) {
				$msg = null;
				if( $warning == 'exists' ) {
					if ( !$this->mDestWarningAck )
						$msg = self::getExistsWarning( $args );
				} elseif( $warning == 'duplicate' ) {
					$msg = $this->getDupeWarning( $args );
				} elseif( $warning == 'filewasdeleted' ) {
					$ltitle = SpecialPage::getTitleFor( 'Log' );
					$llink = $sk->makeKnownLinkObj( $ltitle, wfMsgHtml( 'deletionlog' ),
						'type=delete&page=' . $args->getPrefixedUrl() );
					$msg = "\t<li>" . wfMsgWikiHtml( 'filewasdeleted', $llink ) . "</li>\n";
				} else {
					if( is_bool( $args ) )
						$args = array();
					elseif( !is_array( $args ) ) 
						$args = array( $args );
					$msg = "\t<li>" . wfMsgExt( $warning, 'parseinline', $args ) . "</li>\n";
				}
				if( $msg )
					$wgOut->addHTML( $msg );
		}
		
		$titleObj = SpecialPage::getTitleFor( 'Upload' );

		if ( $wgUseCopyrightUpload ) {
			$copyright = Xml::hidden( 'wpUploadCopyStatus', $this->mCopyrightStatus ) . "\n" .
					Xml::hidden( 'wpUploadSource', $this->mCopyrightSource ) . "\n";
		} else {
			$copyright = '';
		}

		$wgOut->addHTML(
			Xml::openElement( 'form', array( 'method' => 'post', 'action' => $titleObj->getLocalURL( 'action=submit' ),
				 'enctype' => 'multipart/form-data', 'id' => 'uploadwarning' ) ) . "\n" .
			Xml::hidden( 'wpIgnoreWarning', '1' ) . "\n" .
			Xml::hidden( 'wpSessionKey', $this->mSessionKey ) . "\n" .
			Xml::hidden( 'wpUploadDescription', $this->mComment ) . "\n" .
			Xml::hidden( 'wpLicense', $this->mLicense ) . "\n" .
			Xml::hidden( 'wpDestFile', $this->mDesiredDestName ) . "\n" .
			Xml::hidden( 'wpWatchthis', $this->mWatchthis ) . "\n" .
			"{$copyright}<br />" .
			Xml::submitButton( wfMsg( 'ignorewarning' ), array ( 'name' => 'wpUpload', 'id' => 'wpUpload', 'checked' => 'checked' ) ) . ' ' .
			Xml::submitButton( wfMsg( 'reuploaddesc' ), array ( 'name' => 'wpReUpload', 'id' => 'wpReUpload' ) ) .
			Xml::closeElement( 'form' ) . "\n"
		);
	}

	/**
	 * Displays the main upload form, optionally with a highlighted
	 * error message up at the top.
	 *
	 * @param string $msg as HTML
	 * @access private
	 */
	function mainUploadForm( $msg='' ) {
		global $wgOut, $wgUser, $wgLang, $wgMaxUploadSize;
		global $wgUseCopyrightUpload, $wgUseAjax, $wgAjaxUploadDestCheck, $wgAjaxLicensePreview;
		global $wgRequest;
		global $wgStylePath, $wgStyleVersion;

		$useAjaxDestCheck = $wgUseAjax && $wgAjaxUploadDestCheck;
		$useAjaxLicensePreview = $wgUseAjax && $wgAjaxLicensePreview;

		$adc = wfBoolToStr( $useAjaxDestCheck );
		$alp = wfBoolToStr( $useAjaxLicensePreview );
		$autofill = wfBoolToStr( $this->mDesiredDestName == '' );

		$wgOut->addScript( "<script type=\"text/javascript\">
wgAjaxUploadDestCheck = {$adc};
wgAjaxLicensePreview = {$alp};
wgUploadAutoFill = {$autofill};
</script>" );
		$wgOut->addScriptFile( 'upload.js' );
		$wgOut->addScriptFile( 'edit.js' ); // For <charinsert> support

		if( !wfRunHooks( 'UploadForm:initial', array( &$this ) ) )
		{
			wfDebug( "Hook 'UploadForm:initial' broke output of the upload form" );
			return false;
		}

		if( $this->mDesiredDestName ) {
			$title = Title::makeTitleSafe( NS_IMAGE, $this->mDesiredDestName );
			// Show a subtitle link to deleted revisions (to sysops et al only)
			if( $title instanceof Title && ( $count = $title->isDeleted() ) > 0 && $wgUser->isAllowed( 'deletedhistory' ) ) {
				$link = wfMsgExt(
					$wgUser->isAllowed( 'delete' ) ? 'thisisdeleted' : 'viewdeleted',
					array( 'parse', 'replaceafter' ),
					$wgUser->getSkin()->makeKnownLinkObj(
						SpecialPage::getTitleFor( 'Undelete', $title->getPrefixedText() ),
						wfMsgExt( 'restorelink', array( 'parsemag', 'escape' ), $count )
					)
				);
				$wgOut->addHtml( "<div id=\"contentSub2\">{$link}</div>" );
			}

			// Show the relevant lines from deletion log (for still deleted files only)
			if( $title instanceof Title && $title->isDeleted() > 0 && !$title->exists() ) {
				$this->showDeletionLog( $wgOut, $title->getPrefixedText() );
			}
		}

		$cols = intval($wgUser->getOption( 'cols' ));

		if( $wgUser->getOption( 'editwidth' ) ) {
			$width = " style=\"width:100%\"";
		} else {
			$width = '';
		}

		if ( '' != $msg ) {
			$sub = wfMsgHtml( 'uploaderror' );
			$wgOut->addHTML( "<h2>{$sub}</h2>\n" .
			  "<span class='error'>{$msg}</span>\n" );
		}
		$wgOut->addHTML( '<div id="uploadtext">' );
		$wgOut->addWikiMsg( 'uploadtext', $this->mDesiredDestName );
		$wgOut->addHTML( "</div>\n" );

		# Print a list of allowed file extensions, if so configured.  We ignore
		# MIME type here, it's incomprehensible to most people and too long.
		global $wgCheckFileExtensions, $wgStrictFileExtensions,
		$wgFileExtensions, $wgFileBlacklist;

		$allowedExtensions = '';
		if( $wgCheckFileExtensions ) {
			$delim = wfMsgExt( 'comma-separator', array( 'escapenoentities' ) );
			if( $wgStrictFileExtensions ) {
				# Everything not permitted is banned
				$extensionsList =
					'<div id="mw-upload-permitted">' .
					wfMsgWikiHtml( 'upload-permitted', implode( $wgFileExtensions, $delim ) ) .
					"</div>\n";
			} else {
				# We have to list both preferred and prohibited
				$extensionsList =
					'<div id="mw-upload-preferred">' .
					wfMsgWikiHtml( 'upload-preferred', implode( $wgFileExtensions, $delim ) ) .
					"</div>\n" .
					'<div id="mw-upload-prohibited">' .
					wfMsgWikiHtml( 'upload-prohibited', implode( $wgFileBlacklist, $delim ) ) .
					"</div>\n";
			}
		} else {
			# Everything is permitted.
			$extensionsList = '';
		}

		# Get the maximum file size from php.ini as $wgMaxUploadSize works for uploads from URL via CURL only
		# See http://www.php.net/manual/en/ini.core.php#ini.upload-max-filesize for possible values of upload_max_filesize
		$val = trim( ini_get( 'upload_max_filesize' ) );
		$last = strtoupper( ( substr( $val, -1 ) ) );
		switch( $last ) {
			case 'G':
				$val2 = substr( $val, 0, -1 ) * 1024 * 1024 * 1024;
				break;
			case 'M':
				$val2 = substr( $val, 0, -1 ) * 1024 * 1024;
				break;
			case 'K':
				$val2 = substr( $val, 0, -1 ) * 1024;
				break;
			default:
				$val2 = $val;
		}
		$val2 = UploadFromUrl::isEnabled() ? min( $wgMaxUploadSize, $val2 ) : $val2;
		$maxUploadSize = '<div id="mw-upload-maxfilesize">' . 
			wfMsgExt( 'upload-maxfilesize', array( 'parseinline', 'escapenoentities' ), 
				$wgLang->formatSize( $val2 ) ) .
				"</div>\n";

		$sourcefilename = wfMsgExt( 'sourcefilename', array( 'parseinline', 'escapenoentities' ) );
        $destfilename = wfMsgExt( 'destfilename', array( 'parseinline', 'escapenoentities' ) ); 
		
		$summary = wfMsgExt( 'fileuploadsummary', 'parseinline' );

		$licenses = new Licenses();
		$license = wfMsgExt( 'license', array( 'parseinline' ) );
		$nolicense = wfMsgHtml( 'nolicense' );
		$licenseshtml = $licenses->getHtml();

		$ulb = wfMsgHtml( 'uploadbtn' );


		$titleObj = SpecialPage::getTitleFor( 'Upload' );

		$encDestName = htmlspecialchars( $this->mDesiredDestName );

		$watchChecked = $this->watchCheck()
			? 'checked="checked"'
			: '';
		$warningChecked = $this->mIgnoreWarning ? 'checked' : '';

		// Prepare form for upload or upload/copy
		if( UploadFromUrl::isEnabled() && $wgUser->isAllowed( 'upload_by_url' ) ) {
			$filename_form =
				"<input type='radio' id='wpSourceTypeFile' name='wpSourceType' value='file' " .
				   "onchange='toggle_element_activation(\"wpUploadFileURL\",\"wpUploadFile\")' checked='checked' />" .
				 "<input tabindex='1' type='file' name='wpUploadFile' id='wpUploadFile' " .
				   "onfocus='" .
				     "toggle_element_activation(\"wpUploadFileURL\",\"wpUploadFile\");" .
				     "toggle_element_check(\"wpSourceTypeFile\",\"wpSourceTypeURL\")' " .
				     "onchange='fillDestFilename(\"wpUploadFile\")' size='60' />" .
				wfMsgHTML( 'upload_source_file' ) . "<br/>" .
				"<input type='radio' id='wpSourceTypeURL' name='wpSourceType' value='web' " .
				  "onchange='toggle_element_activation(\"wpUploadFile\",\"wpUploadFileURL\")' />" .
				"<input tabindex='1' type='text' name='wpUploadFileURL' id='wpUploadFileURL' " .
				  "onfocus='" .
				    "toggle_element_activation(\"wpUploadFile\",\"wpUploadFileURL\");" .
				    "toggle_element_check(\"wpSourceTypeURL\",\"wpSourceTypeFile\")' " .
				    "onchange='fillDestFilename(\"wpUploadFileURL\")' size='60' disabled='disabled' />" .
				wfMsgHtml( 'upload_source_url' ) ;
		} else {
			$filename_form =
				"<input tabindex='1' type='file' name='wpUploadFile' id='wpUploadFile' " .
				($this->mDesiredDestName?"":"onchange='fillDestFilename(\"wpUploadFile\")' ") .
				"size='60' />" .
				"<input type='hidden' name='wpSourceType' value='file' />" ;
		}
		if ( $useAjaxDestCheck ) {
			$warningRow = "<tr><td colspan='2' id='wpDestFile-warning'>&nbsp;</td></tr>";
			$destOnkeyup = 'onkeyup="wgUploadWarningObj.keypress();"';
		} else {
			$warningRow = '';
			$destOnkeyup = '';
		}

		$encComment = htmlspecialchars( $this->mComment );

		$wgOut->addHTML(
			 Xml::openElement( 'form', array( 'method' => 'post', 'action' => $titleObj->getLocalURL(),
				 'enctype' => 'multipart/form-data', 'id' => 'mw-upload-form' ) ) .
			 Xml::openElement( 'fieldset' ) .
			 Xml::element( 'legend', null, wfMsg( 'upload' ) ) .
			 Xml::openElement( 'table', array( 'border' => '0', 'id' => 'mw-upload-table' ) ) .
			 "<tr>
			 	{$this->uploadFormTextTop}
				<td class='mw-label'>
					<label for='wpUploadFile'>{$sourcefilename}</label>
				</td>
				<td class='mw-input'>
					{$filename_form}
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					{$maxUploadSize}
					{$extensionsList}
				</td>
			</tr>
			<tr>
				<td class='mw-label'>
					<label for='wpDestFile'>{$destfilename}</label>
				</td>
				<td class='mw-input'>
					<input tabindex='2' type='text' name='wpDestFile' id='wpDestFile' size='60'
						value=\"{$encDestName}\" onchange='toggleFilenameFiller()' $destOnkeyup />
				</td>
			</tr>
			<tr>
				<td class='mw-label'>
					<label for='wpUploadDescription'>{$summary}</label>
				</td>
				<td class='mw-input'>
					<textarea tabindex='3' name='wpUploadDescription' id='wpUploadDescription' rows='6'
						cols='{$cols}'{$width}>$encComment</textarea>
					{$this->uploadFormTextAfterSummary}
				</td>
			</tr>
			<tr>"
		);

		if ( $licenseshtml != '' ) {
			global $wgStylePath;
			$wgOut->addHTML( "
					<td class='mw-label'>
						<label for='wpLicense'>$license</label>
					</td>
					<td class='mw-input'>
						<select name='wpLicense' id='wpLicense' tabindex='4'
							onchange='licenseSelectorCheck()'>
							<option value=''>$nolicense</option>
							$licenseshtml
						</select>
					</td>
				</tr>
				<tr>"
			);
			if( $useAjaxLicensePreview ) {
				$wgOut->addHtml( "
						<td></td>
						<td id=\"mw-license-preview\"></td>
					</tr>
					<tr>"
				);
			}
		}

		if ( $wgUseCopyrightUpload ) {
			$filestatus = wfMsgExt( 'filestatus', 'escapenoentities' );
			$copystatus =  htmlspecialchars( $this->mCopyrightStatus );
			$filesource = wfMsgExt( 'filesource', 'escapenoentities' );
			$uploadsource = htmlspecialchars( $this->mCopyrightSource );

			$wgOut->addHTML( "
					<td class='mw-label' style='white-space: nowrap;'>
						<label for='wpUploadCopyStatus'>$filestatus</label></td>
					<td class='mw-input'>
						<input tabindex='5' type='text' name='wpUploadCopyStatus' id='wpUploadCopyStatus'
							value=\"$copystatus\" size='60' />
					</td>
				</tr>
				<tr>
					<td class='mw-label'>
						<label for='wpUploadCopyStatus'>$filesource</label>
					</td>
					<td class='mw-input'>
						<input tabindex='6' type='text' name='wpUploadSource' id='wpUploadCopyStatus'
							value=\"$uploadsource\" size='60' />
					</td>
				</tr>
				<tr>"
			);
		}

		$wgOut->addHtml( "
				<td></td>
				<td>
					<input tabindex='7' type='checkbox' name='wpWatchthis' id='wpWatchthis' $watchChecked value='true' />
					<label for='wpWatchthis'>" . wfMsgHtml( 'watchthisupload' ) . "</label>
					<input tabindex='8' type='checkbox' name='wpIgnoreWarning' id='wpIgnoreWarning' value='true' $warningChecked/>
					<label for='wpIgnoreWarning'>" . wfMsgHtml( 'ignorewarnings' ) . "</label>
				</td>
			</tr>
			$warningRow
			<tr>
				<td></td>
					<td class='mw-input'>
						<input tabindex='9' type='submit' name='wpUpload' value=\"{$ulb}\"" . $wgUser->getSkin()->tooltipAndAccesskey( 'upload' ) . " />
					</td>
			</tr>
			<tr>
				<td></td>
				<td class='mw-input'>"
		);
		$wgOut->addWikiText( wfMsgForContent( 'edittools' ) );
		$wgOut->addHTML( "
				</td>
			</tr>" .
			Xml::closeElement( 'table' ) .
			Xml::hidden( 'wpDestFileWarningAck', '', array( 'id' => 'wpDestFileWarningAck' ) ) .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' )
		);
		$uploadfooter = wfMsgNoTrans( 'uploadfooter' );
		if( $uploadfooter != '-' && !wfEmptyMsg( 'uploadfooter', $uploadfooter ) ){
			$wgOut->addWikiText( '<div id="mw-upload-footer-message">' . $uploadfooter . '</div>' );
		}
	}

	/* -------------------------------------------------------------- */
	
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
	function watchCheck() {
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
	 * Check if a user is the last uploader
	 *
	 * @param User $user
	 * @param string $img, image name
	 * @return bool
	 * @deprecated Use UploadFromBase::userCanReUpload
	 */
	public static function userCanReUpload( User $user, $img ) {
		wfDeprecated( __METHOD__ );
		
		if( $user->isAllowed( 'reupload' ) )
			return true; // non-conditional
		if( !$user->isAllowed( 'reupload-own' ) )
			return false;

		$dbr = wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow('image',
		/* SELECT */ 'img_user',
		/* WHERE */ array( 'img_name' => $img )
		);
		if ( !$row )
			return false;

		return $user->getId() == $row->img_user;
	}

	/**
	 * Display an error with a wikitext description
	 */
	function showError( $description ) {
		global $wgOut;
		$wgOut->setPageTitle( wfMsg( "internalerror" ) );
		$wgOut->setRobotPolicy( "noindex,nofollow" );
		$wgOut->setArticleRelated( false );
		$wgOut->enableClientCache( false );
		$wgOut->addWikiText( $description );
	}

	/**
	 * Get the initial image page text based on a comment and optional file status information
	 */
	static function getInitialPageText( $comment, $license, $copyStatus, $source ) {
		global $wgUseCopyrightUpload;
		if ( $wgUseCopyrightUpload ) {
			if ( $license != '' ) {
				$licensetxt = '== ' . wfMsgForContent( 'license' ) . " ==\n" . '{{' . $license . '}}' . "\n";
			}
			$pageText = '== ' . wfMsg ( 'filedesc' ) . " ==\n" . $comment . "\n" .
			  '== ' . wfMsgForContent ( 'filestatus' ) . " ==\n" . $copyStatus . "\n" .
			  "$licensetxt" .
			  '== ' . wfMsgForContent ( 'filesource' ) . " ==\n" . $source ;
		} else {
			if ( $license != '' ) {
				$filedesc = $comment == '' ? '' : '== ' . wfMsg ( 'filedesc' ) . " ==\n" . $comment . "\n";
				 $pageText = $filedesc .
					 '== ' . wfMsgForContent ( 'license' ) . " ==\n" . '{{' . $license . '}}' . "\n";
			} else {
				$pageText = $comment;
			}
		}
		return $pageText;
	}

	/**
	 * If there are rows in the deletion log for this file, show them,
	 * along with a nice little note for the user
	 *
	 * @param OutputPage $out
	 * @param string filename
	 */
	private function showDeletionLog( $out, $filename ) {
		global $wgUser;
		$loglist = new LogEventsList( $wgUser->getSkin(), $out );
		$pager = new LogPager( $loglist, 'delete', false, $filename );
		if( $pager->getNumRows() > 0 ) {
			$out->addHtml( '<div id="mw-upload-deleted-warn">' );
			$out->addWikiMsg( 'upload-wasdeleted' );
			$out->addHTML(
				$loglist->beginLogEventsList() .
				$pager->getBody() .
				$loglist->endLogEventsList()
			);
			$out->addHtml( '</div>' );
		}
	}
}
