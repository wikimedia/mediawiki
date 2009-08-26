<?php
/**
 * @file
 * @ingroup SpecialPage
 */

/**
 * implements Special:Upload
 * @ingroup SpecialPage
 */
class UploadForm extends SpecialPage {
	/**#@+
	 * @access private
	 */
	var $mComment, $mLicense, $mIgnoreWarning;
	var $mCopyrightStatus, $mCopyrightSource, $mReUpload, $mAction, $mUploadClicked;
	var $mDestWarningAck;
	var $mLocalFile;

	var $mUpload; // Instance of UploadBase or derivative

	# Placeholders for text injection by hooks (must be HTML)
	# extensions should take care to _append_ to the present value
	var $uploadFormTextTop;
	var $uploadFormTextAfterSummary;
    var $mTokenOk = false;
    var $mForReUpload = false;
	/**#@-*/

	/**
	 * Constructor : initialise object
	 * Get data POSTed through the form and assign them to the object
	 * @param $request Data posted.
	 */
	function __construct( $request = null ) {
		parent::__construct( 'Upload', 'upload' );
		$this->mRequest = $request;
	}

	protected function initForm() {
		global $wgRequest, $wgUser;

		if ( is_null( $this->mRequest ) ) {
			$request = $wgRequest;
		} else {
			$request = $this->mRequest;
		}
		// Guess the desired name from the filename if not provided
		$this->mDesiredDestName   = $request->getText( 'wpDestFile' );
		if( !$this->mDesiredDestName )
			$this->mDesiredDestName = $request->getText( 'wpUploadFile' );

		$this->mIgnoreWarning     = $request->getCheck( 'wpIgnoreWarning' );
		$this->mComment           = $request->getText( 'wpUploadDescription' );

		if( !$request->wasPosted() ) {
			# GET requests just give the main form; no data except destination
			# filename and description
			return;
		}
		//if it was posted check for the token (no remote POST'ing with user credentials)
		$token = $request->getVal( 'wpEditToken' );
		$this->mTokenOk = $wgUser->matchEditToken( $token );

		# Placeholders for text injection by hooks (empty per default)
		$this->uploadFormTextTop = "";
		$this->uploadFormTextAfterSummary = "";

		$this->mUploadClicked     = $request->getCheck( 'wpUpload' );

		$this->mLicense           = $request->getText( 'wpLicense' );
		$this->mCopyrightStatus   = $request->getText( 'wpUploadCopyStatus' );
		$this->mCopyrightSource   = $request->getText( 'wpUploadSource' );
		$this->mWatchthis         = $request->getBool( 'wpWatchthis' );
		$this->mSourceType        = $request->getText( 'wpSourceType' );
		$this->mDestWarningAck    = $request->getText( 'wpDestFileWarningAck' );

		$this->mForReUpload       = $request->getBool( 'wpForReUpload' );

		$this->mReUpload          = $request->getCheck( 'wpReUpload' );

		$this->mAction            = $request->getVal( 'action' );
		$this->mUpload            = UploadBase::createFromRequest( $request );
	}

	public function userCanExecute( $user ) {
		return UploadBase::isEnabled() && parent::userCanExecute( $user );
	}

	/**
	 * Start doing stuff
	 * @access public
	 */
	function execute( $par ) {
		global $wgUser, $wgOut, $wgRequest;

		$this->setHeaders();
		$this->outputHeader();

		$this->initForm();

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

		if( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
		//check token if uploading or reUploading
		if( !$this->mTokenOk && !$this->mReUpload && ($this->mUpload && (
						'submit' == $this->mAction || $this->mUploadClicked ) )
		){
		    $this->mainUploadForm ( wfMsg( 'session_fail_preview' ) );
		    return ;
		}


		if( $this->mReUpload &&  $this->mUpload) {
			// User choose to cancel upload
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
	 * FIXME this should really use the standard Status class (instead of associative array)
	 * FIXME would be nice if we refactored this into the upload api.
	 *  (the special upload page is not the only response point that needs clean localized error msgs)
	 *
	 * @access private
	 */
	function processUpload(){
		global $wgOut, $wgFileExtensions, $wgLang;
		$details = $this->internalProcessUpload();
		switch( $details['status'] ) {
			case UploadBase::SUCCESS:
				$wgOut->redirect( $this->mLocalFile->getTitle()->getFullURL() );
				break;

			case UploadBase::BEFORE_PROCESSING:
				$this->uploadError( $details['error'] );
				break;
			case UploadBase::LARGE_FILE_SERVER:
				$this->mainUploadForm( wfMsgHtml( 'largefileserver' ) );
				break;

			case UploadBase::EMPTY_FILE:
				$this->mainUploadForm( wfMsgHtml( 'emptyfile' ) );
				break;

			case UploadBase::MIN_LENGTH_PARTNAME:
				$this->mainUploadForm( wfMsgHtml( 'minlength1' ) );
				break;

			case UploadBase::ILLEGAL_FILENAME:
				$this->uploadError( wfMsgExt( 'illegalfilename',
					'parseinline', $details['filtered'] ) );
				break;

			case UploadBase::PROTECTED_PAGE:
				$wgOut->showPermissionsErrorPage( $details['permissionserrors'] );
				break;

			case UploadBase::OVERWRITE_EXISTING_FILE:
				$this->uploadError( wfMsgExt( $details['overwrite'],
					'parseinline' ) );
				break;

			case UploadBase::FILETYPE_MISSING:
				$this->uploadError( wfMsgExt( 'filetype-missing', array ( 'parseinline' ) ) );
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

			case UploadBase::UPLOAD_VERIFICATION_ERROR:
				$error = $details['error'];
				$this->uploadError( wfMsgExt( $error, 'parseinline' ) );
				break;

			case UploadBase::UPLOAD_WARNING:
				unset( $details['status'] );
				$this->uploadWarning( $details );
				break;

			case UploadBase::INTERNAL_ERROR:
				$status = $details['internal'];
				$this->showError( $wgOut->parse( $status->getWikiText() ) );
				break;

			default:
				throw new MWException( __METHOD__ . ": Unknown value `{$details['status']}`" );
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
	function internalProcessUpload() {
		global $wgUser;

		if( !wfRunHooks( 'UploadForm:BeforeProcessing', array( &$this ) ) )
		{
			wfDebug( "Hook 'UploadForm:BeforeProcessing' broke processing the file.\n" );
			return array( 'status' => UploadBase::BEFORE_PROCESSING );
		}

		/**
		 * If the image is protected, non-sysop users won't be able
		 * to modify it by uploading a new revision.
		 */
		$permErrors = $this->mUpload->verifyPermissions( $wgUser );
		if( $permErrors !== true ) {
			return array( 'status' => UploadBase::PROTECTED_PAGE, 'permissionserrors' => $permErrors );
		}

		// Fetch the file if required
		$status = $this->mUpload->fetchFile();
		if( !$status->isOK() ){
			return array( 'status' =>UploadBase::BEFORE_PROCESSING, 'error'=>$status->getWikiText() );
		}

		// Check whether this is a sane upload
		$result = $this->mUpload->verifyUpload();
		if( $result['status'] != UploadBase::OK )
			return $result;

		$this->mLocalFile = $this->mUpload->getLocalFile();

		if( !$this->mIgnoreWarning ) {
			$warnings = $this->mUpload->checkWarnings();

			if( count( $warnings ) ) {
				$warnings['status'] = UploadBase::UPLOAD_WARNING;
				return $warnings;
			}
		}


		/**
		 * Try actually saving the thing...
		 * It will show an error form on failure. No it will not.
		 */
		if( !$this->mForReUpload ) {
			$pageText = self::getInitialPageText( $this->mComment, $this->mLicense,
				$this->mCopyrightStatus, $this->mCopyrightSource );
		} else {
			$pageText = false;
		}
		$status = $this->mUpload->performUpload( $this->mComment, $pageText, $this->mWatchthis, $wgUser );

		if ( !$status->isGood() ) {
			return array( 'status' => UploadBase::INTERNAL_ERROR, 'internal' => $status );
		} else {
			// Success, redirect to description page
			wfRunHooks( 'SpecialUploadComplete', array( &$this ) );
			return UploadBase::SUCCESS;
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
		// Check for uppercase extension. We allow these filenames but check if an image
		// with lowercase extension exists already
		if( $exists === false )
			return '';

		$warning = '';
		$align = $wgContLang->alignEnd();

		list( $existsType, $file ) = $exists;

		if( strpos( $file->getName(), '.' ) == false ) {
			$partname = $file->getName();
			$rawExtension = '';
		} else {
			$n = strrpos( $file->getName(), '.' );
			$rawExtension = substr( $file->getName(), $n + 1 );
			$partname = substr( $file->getName(), 0, $n );
		}

		$sk = $wgUser->getSkin();

		if( $existsType == 'exists' ) {
			// Exact match
			$dlink = $sk->linkKnown( $file->getTitle() );
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
			$lnk = $sk->linkKnown( $file->getTitle(), '', '',array('redirect'=>'no') );
			$warning .= '<li>' . wfMsgExt( 'filepageexists', array( 'parseinline', 'replaceafter' ), $lnk ) . '</li>';
		} elseif ( $existsType == 'exists-normalized' ) {
			# Check if image with lowercase extension exists.
			# It's not forbidden but in 99% it makes no sense to upload the same filename with uppercase extension
			$dlink = $sk->linkKnown( $nt_lc );
			if ( $file_lc->allowInlineDisplay() ) {
				// FIXME: replace deprecated makeImageLinkObj by link()
				$dlink2 = $sk->makeImageLinkObj( $nt_lc, wfMsgExt( 'fileexists-thumb', 'parseinline' ),
					$nt_lc->getText(), $align, array(), false, true );
			} elseif ( !$file_lc->allowInlineDisplay() && $file_lc->isSafeFile() ) {
				$icon = $file_lc->iconThumb();
				$dlink2 = '<div style="float:' . $align . '" id="mw-media-icon">' .
					$icon->toHtml( array( 'desc-link' => true ) ) . '<br />' . $dlink . '</div>';
			} else {
				$dlink2 = '';
			}

			$warning .= '<li>' .
				wfMsgExt( 'fileexists-extension', 'parsemag',
					$file->getTitle()->getPrefixedText(), $dlink ) .
				'</li>' . $dlink2;

		} elseif ( ( substr( $partname , 3, 3 ) == 'px-' || substr( $partname , 2, 3 ) == 'px-' )
			&& preg_match( "/[0-9]{2}/" , substr( $partname , 0, 2 ) ) )
		{
			# Check for filenames like 50px- or 180px-, these are mostly thumbnails
			$nt_thb = Title::newFromText( substr( $partname , strpos( $partname , '-' ) +1 ) . '.' . $rawExtension );
			$file_thb = wfLocalFile( $nt_thb );
			if ($file_thb->exists() ) {
				# Check if an image without leading '180px-' (or similiar) exists
				$dlink = $sk->linkKnown( $nt_thb );
				if ( $file_thb->allowInlineDisplay() ) {
					// FIXME: replace deprecated makeImageLinkObj by link()
					$dlink2 = $sk->makeImageLinkObj( $nt_thb,
						wfMsgExt( 'fileexists-thumb', 'parseinline' ),
						$nt_thb->getText(), $align, array(), false, true );
				} elseif ( !$file_thb->allowInlineDisplay() && $file_thb->isSafeFile() ) {
					$icon = $file_thb->iconThumb();
					$dlink2 = '<div style="float:' . $align . '" id="mw-media-icon">' .
						$icon->toHtml( array( 'desc-link' => true ) ) . '<br />' .
						$dlink . '</div>';
				} else {
					$dlink2 = '';
				}

				$warning .= '<li>' . wfMsgExt( 'fileexists-thumbnail-yes', 'parsemag', $dlink ) .
					'</li>' . $dlink2;
			} else {
				# Image w/o '180px-' does not exists, but we do not like these filenames
				$warning .= '<li>' . wfMsgExt( 'file-thumbnail-no', 'parseinline' ,
					substr( $partname , 0, strpos( $partname , '-' ) +1 ) ) . '</li>';
			}
		}

		$filenamePrefixBlacklist = UploadBase::getFilenamePrefixBlacklist();
		# Do the match
		if(!isset($partname))
			$partname = '';
		foreach( $filenamePrefixBlacklist as $prefix ) {
			if ( substr( $partname, 0, strlen( $prefix ) ) == $prefix ) {
				$warning .= '<li>' . wfMsgExt( 'filename-bad-prefix', 'parseinline', $prefix ) . '</li>';
				break;
			}
		}

		if ( $file->wasDeleted() && !$file->exists() ) {
			# If the file existed before and was deleted, warn the user of this
			# Don't bother doing so if the file exists now, however
			$ltitle = SpecialPage::getTitleFor( 'Log' );
			$llink = $sk->linkKnown(
				$ltitle,
				wfMsgHtml( 'deletionlog' ),
				array(),
				array(
					'type' => 'delete',
					'page' => $file->getTitle()->getPrefixedText()
				)
			);
			$warning .= '<li>' . wfMsgWikiHtml( 'filewasdeleted', $llink ) . '</li>';
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
			$exists = UploadBase::getExistsWarning( $file );
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
		$title = Title::makeTitle( NS_FILE, 'Sample.jpg' );
		$options = ParserOptions::newFromUser( $wgUser );

		// Expand subst: first, then live templates...
		$text = $wgParser->preSaveTransform( $text, $title, $wgUser, $options );
		$output = $wgParser->parse( $text, $title, $options );

		return $output->getText();
	}

	/**
	 * Construct the human readable warning message from an array of duplicate files
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

	/*              Interface code starts below this line             *
	 * -------------------------------------------------------------- */


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

		if( $this->mSessionKey === false ) {
			# Couldn't save file; an error has been displayed so let's go.
			return;
		}

		$sk = $wgUser->getSkin();

		$wgOut->addHTML( '<h2>' . wfMsgHtml( 'uploadwarning' ) . "</h2>\n" );
		$wgOut->addHTML( '<ul class="warning">' );
		foreach( $warnings as $warning => $args ) {
				$msg = null;
				if( $warning == 'exists' ) {

					//we should not have produced this warning if the user already acknowledged the destination warning
					//at any rate I don't see why we should hid this warning if mDestWarningAck has been checked
					//(it produces an empty warning page when no other warnings are fired)
					//if ( !$this->mDestWarningAck )
						$msg = self::getExistsWarning( $args );

				} elseif( $warning == 'duplicate' ) {
					$msg = $this->getDupeWarning( $args );
				} elseif( $warning == 'duplicate-archive' ) {
					$titleText = Title::makeTitle( NS_FILE, $args )->getPrefixedText();
					$msg = Xml::tags( 'li', null, wfMsgExt( 'file-deleted-duplicate', array( 'parseinline' ), array( $titleText ) ) );
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
			Xml::hidden('wpEditToken', $wgUser->editToken(), array("id" => 'wpEditToken')) .
			Xml::hidden( 'wpIgnoreWarning', '1' ) . "\n" .
			Xml::hidden( 'wpSourceType', 'stash' ) . "\n" .
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
		global $wgOut, $wgUser, $wgLang, $wgMaxUploadSize, $wgEnableFirefogg;
		global $wgUseCopyrightUpload, $wgUseAjax, $wgAjaxUploadDestCheck, $wgAjaxLicensePreview;
		global $wgRequest;
		global $wgStylePath, $wgStyleVersion;
		global $wgEnableJS2system;

		$useAjaxDestCheck = $wgUseAjax && $wgAjaxUploadDestCheck;
		$useAjaxLicensePreview = $wgUseAjax && $wgAjaxLicensePreview;

		$adc = wfBoolToStr( $useAjaxDestCheck );
		$alp = wfBoolToStr( $useAjaxLicensePreview );
		$uef = wfBoolToStr( $wgEnableFirefogg );
		$autofill = wfBoolToStr( $this->mDesiredDestName == '' );


		$wgOut->addScript( "<script type=\"text/javascript\">
wgAjaxUploadDestCheck = {$adc};
wgAjaxLicensePreview = {$alp};
wgEnableFirefogg = {$uef};
wgUploadAutoFill = {$autofill};
</script>" );

		if( $wgEnableJS2system ){
			//js2version of upload page:
		    $wgOut->addScriptClass( 'uploadPage' );
		}else{
			//legacy upload code:
			$wgOut->addScriptFile( 'upload.js' );
			$wgOut->addScriptFile( 'edit.js' ); // For <charinsert> support
		}

		if( !wfRunHooks( 'UploadForm:initial', array( &$this ) ) )
		{
			wfDebug( "Hook 'UploadForm:initial' broke output of the upload form" );
			return false;
		}

		if( $this->mDesiredDestName != '' ) {
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
					wfMsgWikiHtml( 'upload-prohibited', $wgLang->commaList( $wgFileExtensions ) ) .
					"</div>\n";
			}
		} else {
			# Everything is permitted.
			$extensionsList = '';
		}

		# Get the maximum file size from php.ini as $wgMaxUploadSize works for uploads from URL via CURL only
		# See http://www.php.net/manual/en/ini.core.php#ini.upload-max-filesize for possible values of upload_max_filesize
		$val = wfShorthandToInteger( ini_get( 'upload_max_filesize' ) );
		$maxUploadSize = '<div id="mw-upload-maxfilesize">' .
			wfMsgExt( 'upload-maxfilesize', array( 'parseinline', 'escapenoentities' ),
				$wgLang->formatSize( $val ) ) .
				"</div>\n";
		//add a hidden filed for upload by url (uses the $wgMaxUploadSize var)
		if( UploadFromUrl::isEnabled() ){
			$maxUploadSize.='<div id="mw-upload-maxfilesize-url" style="display:none">' .
			wfMsgExt( 'upload-maxfilesize', array( 'parseinline', 'escapenoentities' ),
				$wgLang->formatSize( $wgMaxUploadSize ) ) .
				"</div>\n";
		}

		$sourcefilename = wfMsgExt( 'sourcefilename', array( 'parseinline', 'escapenoentities' ) );
        $destfilename = wfMsgExt( 'destfilename', array( 'parseinline', 'escapenoentities' ) );

		$msg = ( $this->mForReUpload )  ? 'filereuploadsummary' : 'fileuploadsummary';
		$summary = wfMsgExt( $msg, 'parseinline' );

		$licenses = new Licenses();
		$license = wfMsgExt( 'license', array( 'parseinline' ) );
		$nolicense = wfMsgHtml( 'nolicense' );
		$licenseshtml = $licenses->getHtml();

		$ulb = wfMsgHtml( 'uploadbtn' );


		$titleObj = SpecialPage::getTitleFor( 'Upload' );

		$encDestName = htmlspecialchars( $this->mDesiredDestName );

		$watchChecked = $this->watchCheck() ? 'checked="checked"' : '';
		# Re-uploads should not need "file exist already" warnings
		$warningChecked = ($this->mIgnoreWarning || $this->mForReUpload) ? 'checked="checked"' : '';

		// Prepare form for upload or upload/copy
		//javascript moved from inline calls to setup:
		if( UploadFromUrl::isEnabled() && $wgUser->isAllowed( 'upload_by_url' ) ) {
		    if($wgEnableJS2system){
		        $filename_form =
		        	Xml::input( 'wpSourceType', false, 'file', array( 'id'=>'wpSourceTypeFile', 'type' => 'radio', 'checked' => 'checked' ) ) .
		        	Xml::input( 'wpUploadFile', 60, false, array( 'id'=>'wpUploadFile', 'type'=>'file', 'tabindex' => '1' ) ) .
						wfMsgHTML( 'upload_source_file' ) . "<br/>" .
					Xml::input( 'wpSourceType', false, 'Url', array( 'id'=>'wpSourceTypeURL', 'type' => 'radio' )) .
					Xml::input( 'wpUploadFileURL', 60, false, array( 'id'=>'wpUploadFileURL', 'type' => 'text', 'tabindex' => '1')) .
				wfMsgHtml( 'upload_source_url' ) ;
		    }else{
		         //@@todo depreciate (not needed once $wgEnableJS2system is turned on)
               $filename_form =
				"<input type='radio' id='wpSourceTypeFile' name='wpSourceType' value='file' " .
				   "onchange='toggle_element_activation(\"wpUploadFileURL\",\"wpUploadFile\")' checked='checked' />" .
				 "<input tabindex='1' type='file' name='wpUploadFile' id='wpUploadFile' " .
				   " onfocus='" .
				     "toggle_element_activation(\"wpUploadFileURL\",\"wpUploadFile\");" .
				     "toggle_element_check(\"wpSourceTypeFile\",\"wpSourceTypeURL\")' " .
				     "onchange='fillDestFilename(\"wpUploadFile\")' size='60' />" .
				wfMsgHTML( 'upload_source_file' ) . "<br/>" .
				"<input type='radio' id='wpSourceTypeURL' name='wpSourceType' value='Url' " .
				  "onchange='toggle_element_activation(\"wpUploadFile\",\"wpUploadFileURL\")' />" .
				"<input tabindex='1' type='text' name='wpUploadFileURL' id='wpUploadFileURL' " .
				  "onfocus='" .
				    "toggle_element_activation(\"wpUploadFile\",\"wpUploadFileURL\");" .
				    "toggle_element_check(\"wpSourceTypeURL\",\"wpSourceTypeFile\")' " .
				    "onchange='fillDestFilename(\"wpUploadFileURL\")' size='60' disabled='disabled' />" .
				wfMsgHtml( 'upload_source_url' ) ;

		    }
		} else {
			if($wgEnableJS2system){
				$filename_form =
					Xml::input( 'wpUploadFile', 60, false, array( 'id'=>'wpUploadFile', 'type'=>'file', 'tabindex' => '1' ) ) .
					Xml::hidden( 'wpSourceType', 'file');
			}else{
				$filename_form =
				"<input tabindex='1' type='file' name='wpUploadFile' id='wpUploadFile' size='60' ".
				"onchange='fillDestFilename(\"wpUploadFile\")' />" .
				"<input type='hidden' name='wpSourceType' value='file' />" ;
			}
		}
		$warningRow = '';
		$destOnkeyup = '';
		if($wgEnableJS2system){
			$warningRow = "<tr><td colspan='2' id='wpDestFile-warning'>&nbsp;</td></tr>";
		}else{
			if ( $useAjaxDestCheck ) {
				$warningRow = "<tr><td colspan='2' id='wpDestFile-warning'>&nbsp;</td></tr>";
				$destOnkeyup = 'onchange=\'wgUploadWarningObj.checkNow(this.value);\'';
			}
		}
		# Uploading a new version? If so, the name is fixed.
		$on = $this->mForReUpload ? "readonly='readonly'" : "";

		$encComment = htmlspecialchars( $this->mComment );

	    //add the wpEditToken
		$wgOut->addHTML(
			 Xml::openElement( 'form', array( 'method' => 'post', 'action' => $titleObj->getLocalURL( 'action=submit' ),
				 'enctype' => 'multipart/form-data', 'id' => 'mw-upload-form' ) ) .
			 Xml::hidden('wpEditToken', $wgUser->editToken(), array('id' => 'wpEditToken')) .
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
				<td class='mw-input'>"
		);
		if( $this->mForReUpload ) {
			$wgOut->addHTML(
				Xml::hidden( 'wpDestFile', $this->mDesiredDestName, array('id'=>'wpDestFile','tabindex'=>2) ) .
				"<tt>" .
				$encDestName .
				"</tt>"
			);
		}
		else {
			$wgOut->addHTML(
				"<input tabindex='2' type='text' name='wpDestFile' id='wpDestFile' size='60'
						value=\"{$encDestName}\" $destOnkeyup />"
			);
		}


		$wgOut->addHTML(
				"</td>
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

		# Re-uploads should not need license info
		if ( !$this->mForReUpload && $licenseshtml != '' ) {
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
				$wgOut->addHTML( "
						<td></td>
						<td id=\"mw-license-preview\"></td>
					</tr>
					<tr>"
				);
			}
		}

		if ( !$this->mForReUpload && $wgUseCopyrightUpload ) {
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

		$wgOut->addHTML( "
				<td></td>
				<td>
					<input tabindex='7' type='checkbox' name='wpWatchthis' id='wpWatchthis' $watchChecked value='true' />
					<label for='wpWatchthis'>" . wfMsgHtml( 'watchthisupload' ) . "</label>
					<input tabindex='8' type='checkbox' name='wpIgnoreWarning' id='wpIgnoreWarning' value='true' $warningChecked />
					<label for='wpIgnoreWarning'>" . wfMsgHtml( 'ignorewarnings' ) . "</label>
				</td>
			</tr>
			$warningRow
			<tr>
				<td></td>
					<td class='mw-input'>
						<input tabindex='9' type='submit' name='wpUpload' value=\"{$ulb}\"" .
							$wgUser->getSkin()->tooltipAndAccesskey( 'upload' ) . " />
					</td>
			</tr>
			<tr>
				<td></td>
				<td class='mw-input'>"
		);
		$wgOut->addHTML( '<div class="mw-editTools">' );
		$wgOut->addWikiMsgArray( 'edittools', array(), array( 'content' ) );
		$wgOut->addHTML( '</div>' );
		$wgOut->addHTML( "
				</td>
			</tr>" .
			Xml::closeElement( 'table' ) .
			Xml::hidden( 'wpDestFileWarningAck', '', array( 'id' => 'wpDestFileWarningAck' ) ) .
			Xml::hidden( 'wpForReUpload', $this->mForReUpload, array( 'id' => 'wpForReUpload' ) ) .
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
	 * @deprecated Use UploadBase::userCanReUpload
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
	static function getInitialPageText( $comment='', $license='', $copyStatus='', $source='' ) {
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
			$out->addHTML( '<div class="mw-warning-with-logexcerpt">' );
			$out->addWikiMsg( 'upload-wasdeleted' );
			$out->addHTML(
				$loglist->beginLogEventsList() .
				$pager->getBody() .
				$loglist->endLogEventsList()
			);
			$out->addHTML( '</div>' );
		}
	}
}
