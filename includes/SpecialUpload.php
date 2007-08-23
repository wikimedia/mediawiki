<?php
/**
 *
 * @addtogroup SpecialPage
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
 * @addtogroup SpecialPage
 */
class UploadForm {
	/**#@+
	 * @access private
	 */
	var $mComment, $mLicense, $mIgnoreWarning, $mCurlError;
	var $mDestName, $mTempPath, $mFileSize, $mFileProps;
	var $mCopyrightStatus, $mCopyrightSource, $mReUpload, $mAction, $mUploadClicked;
	var $mSrcName, $mSessionKey, $mStashed, $mDesiredDestName, $mRemoveTempFile, $mSourceType;
	var $mDestWarningAck, $mCurlDestHandle;
	var $mLocalFile;

	# Placeholders for text injection by hooks (must be HTML)
	# extensions should take care to _append_ to the present value
	var $uploadFormTextTop;
	var $uploadFormTextAfterSummary;

	const SESSION_VERSION = 1;
	/**#@-*/

	/**
	 * Constructor : initialise object
	 * Get data POSTed through the form and assign them to the object
	 * @param $request Data posted.
	 */
	function UploadForm( &$request ) {
		global $wgAllowCopyUploads;
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

		$this->mSessionKey        = $request->getInt( 'wpSessionKey' );
		if( !empty( $this->mSessionKey ) &&
			isset( $_SESSION['wsUploadData'][$this->mSessionKey]['version'] ) && 
			$_SESSION['wsUploadData'][$this->mSessionKey]['version'] == self::SESSION_VERSION ) {
			/**
			 * Confirming a temporarily stashed upload.
			 * We don't want path names to be forged, so we keep
			 * them in the session on the server and just give
			 * an opaque key to the user agent.
			 */
			$data = $_SESSION['wsUploadData'][$this->mSessionKey];
			$this->mTempPath         = $data['mTempPath'];
			$this->mFileSize         = $data['mFileSize'];
			$this->mSrcName          = $data['mSrcName'];
			$this->mFileProps        = $data['mFileProps'];
			$this->mCurlError        = 0/*UPLOAD_ERR_OK*/;
			$this->mStashed          = true;
			$this->mRemoveTempFile   = false;
		} else {
			/**
			 *Check for a newly uploaded file.
			 */
			if( $wgAllowCopyUploads && $this->mSourceType == 'web' ) {
				$this->initializeFromUrl( $request );
			} else {
				$this->initializeFromUpload( $request );
			}
		}
	}

	/**
	 * Initialize the uploaded file from PHP data
	 * @access private
	 */
	function initializeFromUpload( $request ) {
		$this->mTempPath       = $request->getFileTempName( 'wpUploadFile' );
		$this->mFileSize       = $request->getFileSize( 'wpUploadFile' );
		$this->mSrcName        = $request->getFileName( 'wpUploadFile' );
		$this->mCurlError      = $request->getUploadError( 'wpUploadFile' );
		$this->mSessionKey     = false;
		$this->mStashed        = false;
		$this->mRemoveTempFile = false; // PHP will handle this
	}

	/**
	 * Copy a web file to a temporary file
	 * @access private
	 */
	function initializeFromUrl( $request ) {
		global $wgTmpDirectory;
		$url = $request->getText( 'wpUploadFileURL' );
		$local_file = tempnam( $wgTmpDirectory, 'WEBUPLOAD' );

		$this->mTempPath       = $local_file;
		$this->mFileSize       = 0; # Will be set by curlCopy
		$this->mCurlError      = $this->curlCopy( $url, $local_file );
		$this->mSrcName        = array_pop( explode( '/', $url ) );
		$this->mSessionKey     = false;
		$this->mStashed        = false;

		// PHP won't auto-cleanup the file
		$this->mRemoveTempFile = file_exists( $local_file );
	}

	/**
	 * Safe copy from URL
	 * Returns true if there was an error, false otherwise
	 */
	private function curlCopy( $url, $dest ) {
		global $wgUser, $wgOut;

		if( !$wgUser->isAllowed( 'upload_by_url' ) ) {
			$wgOut->permissionRequired( 'upload_by_url' );
			return true;
		}

		# Maybe remove some pasting blanks :-)
		$url =  trim( $url );
		if( stripos($url, 'http://') !== 0 && stripos($url, 'ftp://') !== 0 ) {
			# Only HTTP or FTP URLs
			$wgOut->errorPage( 'upload-proto-error', 'upload-proto-error-text' );
			return true;
		}

		# Open temporary file
		$this->mCurlDestHandle = @fopen( $this->mTempPath, "wb" );
		if( $this->mCurlDestHandle === false ) {
			# Could not open temporary file to write in
			$wgOut->errorPage( 'upload-file-error', 'upload-file-error-text');
			return true;
		}

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_HTTP_VERSION, 1.0); # Probably not needed, but apparently can work around some bug
		curl_setopt( $ch, CURLOPT_TIMEOUT, 10); # 10 seconds timeout
		curl_setopt( $ch, CURLOPT_LOW_SPEED_LIMIT, 512); # 0.5KB per second minimum transfer speed
		curl_setopt( $ch, CURLOPT_URL, $url);
		curl_setopt( $ch, CURLOPT_WRITEFUNCTION, array( $this, 'uploadCurlCallback' ) );
		curl_exec( $ch );
		$error = curl_errno( $ch ) ? true : false;
		$errornum =  curl_errno( $ch );
		// if ( $error ) print curl_error ( $ch ) ; # Debugging output
		curl_close( $ch );

		fclose( $this->mCurlDestHandle );
		unset( $this->mCurlDestHandle );
		if( $error ) {
			unlink( $dest );
			if( wfEmptyMsg( "upload-curl-error$errornum", wfMsg("upload-curl-error$errornum") ) )
				$wgOut->errorPage( 'upload-misc-error', 'upload-misc-error-text' );
			else
				$wgOut->errorPage( "upload-curl-error$errornum", "upload-curl-error$errornum-text" );
		}

		return $error;
	}

	/**
	 * Callback function for CURL-based web transfer
	 * Write data to file unless we've passed the length limit;
	 * if so, abort immediately.
	 * @access private
	 */
	function uploadCurlCallback( $ch, $data ) {
		global $wgMaxUploadSize;
		$length = strlen( $data );
		$this->mFileSize += $length;
		if( $this->mFileSize > $wgMaxUploadSize ) {
			return 0;
		}
		fwrite( $this->mCurlDestHandle, $data );
		return $length;
	}

	/**
	 * Start doing stuff
	 * @access public
	 */
	function execute() {
		global $wgUser, $wgOut;
		global $wgEnableUploads;

		# Check uploading enabled
		if( !$wgEnableUploads ) {
			$wgOut->showErrorPage( 'uploaddisabled', 'uploaddisabledtext', array( $this->mDesiredDestName ) );
			return;
		}

		# Check permissions
		if( !$wgUser->isAllowed( 'upload' ) ) {
			if( !$wgUser->isLoggedIn() ) {
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

		if( $this->mReUpload ) {
			if( !$this->unsaveUploadedFile() ) {
				return;
			}
			$this->mainUploadForm();
		} else if( 'submit' == $this->mAction || $this->mUploadClicked ) {
			$this->processUpload();
		} else {
			$this->mainUploadForm();
		}

		$this->cleanupTempFile();
	}

	/* -------------------------------------------------------------- */

	/**
	 * Really do the upload
	 * Checks are made in SpecialUpload::execute()
	 * @access private
	 */
	function processUpload() {
		global $wgUser, $wgOut;

		if( !wfRunHooks( 'UploadForm:BeforeProcessing', array( &$this ) ) )
		{
			wfDebug( "Hook 'UploadForm:BeforeProcessing' broke processing the file." );
			return false;
		}

		/* Check for PHP error if any, requires php 4.2 or newer */
		if( $this->mCurlError == 1/*UPLOAD_ERR_INI_SIZE*/ ) {
			$this->mainUploadForm( wfMsgHtml( 'largefileserver' ) );
			return;
		}

		/**
		 * If there was no filename or a zero size given, give up quick.
		 */
		if( trim( $this->mSrcName ) == '' || empty( $this->mFileSize ) ) {
			$this->mainUploadForm( wfMsgHtml( 'emptyfile' ) );
			return;
		}

		# Chop off any directories in the given filename
		if( $this->mDesiredDestName ) {
			$basename = $this->mDesiredDestName;
		} else {
			$basename = $this->mSrcName;
		}
		$filtered = wfBaseName( $basename );

		/**
		 * We'll want to blacklist against *any* 'extension', and use
		 * only the final one for the whitelist.
		 */
		list( $partname, $ext ) = $this->splitExtensions( $filtered );

		if( count( $ext ) ) {
			$finalExt = $ext[count( $ext ) - 1];
		} else {
			$finalExt = '';
		}

		# If there was more than one "extension", reassemble the base
		# filename to prevent bogus complaints about length
		if( count( $ext ) > 1 ) {
			for( $i = 0; $i < count( $ext ) - 1; $i++ )
				$partname .= '.' . $ext[$i];
		}

		if( strlen( $partname ) < 1 ) {
			$this->mainUploadForm( wfMsgHtml( 'minlength1' ) );
			return;
		}

		/**
		 * Filter out illegal characters, and try to make a legible name
		 * out of it. We'll strip some silently that Title would die on.
		 */
		$filtered = preg_replace ( "/[^".Title::legalChars()."]|:/", '-', $filtered );
		$nt = Title::makeTitleSafe( NS_IMAGE, $filtered );
		if( is_null( $nt ) ) {
			$this->uploadError( wfMsgWikiHtml( 'illegalfilename', htmlspecialchars( $filtered ) ) );
			return;
		}
		$this->mLocalFile = wfLocalFile( $nt );
		$this->mDestName = $this->mLocalFile->getName();

		/**
		 * If the image is protected, non-sysop users won't be able
		 * to modify it by uploading a new revision.
		 */
		if( !$nt->userCan( 'edit' ) ) {
			return $this->uploadError( wfMsgWikiHtml( 'protectedpage' ) );
		}

		/**
		 * In some cases we may forbid overwriting of existing files.
		 */
		$overwrite = $this->checkOverwrite( $this->mDestName );
		if( WikiError::isError( $overwrite ) ) {
			return $this->uploadError( $overwrite->toString() );
		}

		/* Don't allow users to override the blacklist (check file extension) */
		global $wgStrictFileExtensions;
		global $wgFileExtensions, $wgFileBlacklist;
		if ($finalExt == '') {
			return $this->uploadError( wfMsgExt( 'filetype-missing', array ( 'parseinline' ) ) );
		} elseif ( $this->checkFileExtensionList( $ext, $wgFileBlacklist ) ||
				($wgStrictFileExtensions && !$this->checkFileExtension( $finalExt, $wgFileExtensions ) ) ) {
			return $this->uploadError( wfMsgExt( 'filetype-badtype', array ( 'parseinline' ), 
				htmlspecialchars( $finalExt ), implode ( ', ', $wgFileExtensions ) ) );
		}

		/**
		 * Look at the contents of the file; if we can recognize the
		 * type but it's corrupt or data of the wrong type, we should
		 * probably not accept it.
		 */
		if( !$this->mStashed ) {
			$this->mFileProps = File::getPropsFromPath( $this->mTempPath, $finalExt );
			$this->checkMacBinary();
			$veri = $this->verify( $this->mTempPath, $finalExt );

			if( $veri !== true ) { //it's a wiki error...
				return $this->uploadError( $veri->toString() );
			}

			/**
			 * Provide an opportunity for extensions to add further checks
			 */
			$error = '';
			if( !wfRunHooks( 'UploadVerification',
					array( $this->mDestName, $this->mTempPath, &$error ) ) ) {
				return $this->uploadError( $error );
			}
		}


		/**
		 * Check for non-fatal conditions
		 */
		if ( ! $this->mIgnoreWarning ) {
			$warning = '';

			global $wgCapitalLinks;
			if( $wgCapitalLinks ) {
				$filtered = ucfirst( $filtered );
			}
			if( $basename != $filtered ) {
				$warning .=  '<li>'.wfMsgHtml( 'badfilename', htmlspecialchars( $this->mDestName ) ).'</li>';
			}

			global $wgCheckFileExtensions;
			if ( $wgCheckFileExtensions ) {
				if ( ! $this->checkFileExtension( $finalExt, $wgFileExtensions ) ) {
					$warning .= '<li>'.wfMsgExt( 'filetype-badtype', array ( 'parseinline' ), 
						htmlspecialchars( $finalExt ), implode ( ', ', $wgFileExtensions ) ).'</li>';
				}
			}

			global $wgUploadSizeWarning;
			if ( $wgUploadSizeWarning && ( $this->mFileSize > $wgUploadSizeWarning ) ) {
				$skin = $wgUser->getSkin();
				$wsize = $skin->formatSize( $wgUploadSizeWarning );
				$asize = $skin->formatSize( $this->mFileSize );
				$warning .= '<li>' . wfMsgHtml( 'large-file', $wsize, $asize ) . '</li>';
			}
			if ( $this->mFileSize == 0 ) {
				$warning .= '<li>'.wfMsgHtml( 'emptyfile' ).'</li>';
			}

			if ( !$this->mDestWarningAck ) {
				$warning .= self::getExistsWarning( $this->mLocalFile );
			}
			if( $warning != '' ) {
				/**
				 * Stash the file in a temporary location; the user can choose
				 * to let it through and we'll complete the upload then.
				 */
				return $this->uploadWarning( $warning );
			}
		}

		/**
		 * Try actually saving the thing...
		 * It will show an error form on failure.
		 */
		$pageText = self::getInitialPageText( $this->mComment, $this->mLicense,
			$this->mCopyrightStatus, $this->mCopyrightSource );

		$status = $this->mLocalFile->upload( $this->mTempPath, $this->mComment, $pageText, 
			File::DELETE_SOURCE, $this->mFileProps );
		if ( !$status->isGood() ) {
			$this->showError( $status->getWikiText() );
		} else {
			if ( $this->mWatchthis ) {
				global $wgUser;
				$wgUser->addWatch( $this->mLocalFile->getTitle() );
			}
			// Success, redirect to description page
			$wgOut->redirect( $this->mLocalFile->getTitle()->getFullURL() );
			$img = null; // @todo: added to avoid passing a ref to null - should this be defined somewhere?
			wfRunHooks( 'UploadComplete', array( &$img ) );
		}
	}

	/**
	 * Do existence checks on a file and produce a warning
	 * This check is static and can be done pre-upload via AJAX
	 * Returns an HTML fragment consisting of one or more LI elements if there is a warning
	 * Returns an empty string if there is no warning
	 */
	static function getExistsWarning( $file ) {
		global $wgUser;
		// Check for uppercase extension. We allow these filenames but check if an image
		// with lowercase extension exists already
		$warning = '';
		$ext = $file->getExtension();
		$sk = $wgUser->getSkin();
		if ( $ext !== '' ) {
			$partname = substr( $file->getName(), 0, -strlen( $ext ) - 1 );
		} else {
			$partname = $file->getName();
		}

		if ( $ext != strtolower( $ext ) ) {
			$nt_lc = Title::newFromText( $partname . '.' . strtolower( $ext ) );
			$file_lc = wfLocalFile( $nt_lc );
		} else {
			$file_lc = false;
		}

		if( $file->exists() ) {
			$dlink = $sk->makeKnownLinkObj( $file->getTitle() );
			if ( $file->allowInlineDisplay() ) {
				$dlink2 = $sk->makeImageLinkObj( $file->getTitle(), wfMsgExt( 'fileexists-thumb', 'parseinline', $dlink ), 
					$file->getName(), 'right', array(), false, true );
			} elseif ( !$file->allowInlineDisplay() && $file->isSafeFile() ) {
				$icon = $file->iconThumb();
				$dlink2 = '<div style="float:right" id="mw-media-icon"><a href="' . $file->getURL() . '">' . 
					$icon->toHtml() . '</a><br />' . $dlink . '</div>';
			} else {
				$dlink2 = '';
			}

			$warning .= '<li>' . wfMsgExt( 'fileexists', 'parseline', $dlink ) . '</li>' . $dlink2;

		} elseif ( $file_lc && $file_lc->exists() ) {
			# Check if image with lowercase extension exists.
			# It's not forbidden but in 99% it makes no sense to upload the same filename with uppercase extension
			$dlink = $sk->makeKnownLinkObj( $nt_lc );
			if ( $file_lc->allowInlineDisplay() ) {
				$dlink2 = $sk->makeImageLinkObj( $nt_lc, wfMsgExt( 'fileexists-thumb', 'parseinline', $dlink ), 
					$nt_lc->getText(), 'right', array(), false, true );
			} elseif ( !$file_lc->allowInlineDisplay() && $file_lc->isSafeFile() ) {
				$icon = $file_lc->iconThumb();
				$dlink2 = '<div style="float:right" id="mw-media-icon"><a href="' . $file_lc->getURL() . '">' . 
					$icon->toHtml() . '</a><br />' . $dlink . '</div>';
			} else {
				$dlink2 = '';
			}

			$warning .= '<li>' . wfMsgExt( 'fileexists-extension', 'parsemag' , $partname . '.' 
				. $ext , $dlink ) . '</li>' . $dlink2;				

		} elseif ( ( substr( $partname , 3, 3 ) == 'px-' || substr( $partname , 2, 3 ) == 'px-' ) 
			&& ereg( "[0-9]{2}" , substr( $partname , 0, 2) ) )
		{
			# Check for filenames like 50px- or 180px-, these are mostly thumbnails
			$nt_thb = Title::newFromText( substr( $partname , strpos( $partname , '-' ) +1 ) . '.' . $ext );
			$file_thb = wfLocalFile( $nt_thb );
			if ($file_thb->exists() ) {
				# Check if an image without leading '180px-' (or similiar) exists
				$dlink = $sk->makeKnownLinkObj( $nt_thb);
				if ( $file_thb->allowInlineDisplay() ) {
					$dlink2 = $sk->makeImageLinkObj( $nt_thb, 
						wfMsgExt( 'fileexists-thumb', 'parseinline', $dlink ), 
						$nt_thb->getText(), 'right', array(), false, true );
				} elseif ( !$file_thb->allowInlineDisplay() && $file_thb->isSafeFile() ) {
					$icon = $file_thb->iconThumb();
					$dlink2 = '<div style="float:right" id="mw-media-icon"><a href="' . 
						$file_thb->getURL() . '">' . $icon->toHtml() . '</a><br />' . 
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
		if ( $file->wasDeleted() ) {
			# If the file existed before and was deleted, warn the user of this
			# Don't bother doing so if the image exists now, however
			$ltitle = SpecialPage::getTitleFor( 'Log' );
			$llink = $sk->makeKnownLinkObj( $ltitle, wfMsgHtml( 'deletionlog' ), 
				'type=delete&page=' . $file->getTitle()->getPrefixedUrl() );
			$warning .= '<li>' . wfMsgWikiHtml( 'filewasdeleted', $llink ) . '</li>';
		}
		return $warning;
	}

	static function ajaxGetExistsWarning( $filename ) {
		$file = wfFindFile( $filename );
		$s = '&nbsp;';
		if ( $file ) {
			$warning = self::getExistsWarning( $file );
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
	 * Stash a file in a temporary directory for later processing
	 * after the user has confirmed it.
	 *
	 * If the user doesn't explicitly cancel or accept, these files
	 * can accumulate in the temp directory.
	 *
	 * @param string $saveName - the destination filename
	 * @param string $tempName - the source temporary file to save
	 * @return string - full path the stashed file, or false on failure
	 * @access private
	 */
	function saveTempUploadedFile( $saveName, $tempName ) {
		global $wgOut;
		$repo = RepoGroup::singleton()->getLocalRepo();
		$status = $repo->storeTemp( $saveName, $tempName );
		if ( !$status->isGood() ) {
			$this->showError( $status->getWikiText() );
			return false;
		} else {
			return $status->value;
		}
	}

	/**
	 * Stash a file in a temporary directory for later processing,
	 * and save the necessary descriptive info into the session.
	 * Returns a key value which will be passed through a form
	 * to pick up the path info on a later invocation.
	 *
	 * @return int
	 * @access private
	 */
	function stashSession() {
		$stash = $this->saveTempUploadedFile( $this->mDestName, $this->mTempPath );

		if( !$stash ) {
			# Couldn't save the file.
			return false;
		}

		$key = mt_rand( 0, 0x7fffffff );
		$_SESSION['wsUploadData'][$key] = array(
			'mTempPath'       => $stash,
			'mFileSize'       => $this->mFileSize,
			'mSrcName'        => $this->mSrcName,
			'mFileProps'      => $this->mFileProps,
			'version'         => self::SESSION_VERSION,
	   	);
		return $key;
	}

	/**
	 * Remove a temporarily kept file stashed by saveTempUploadedFile().
	 * @access private
	 * @return success
	 */
	function unsaveUploadedFile() {
		global $wgOut;
		$repo = RepoGroup::singleton()->getLocalRepo();
		$success = $repo->freeTemp( $this->mTempPath );
		if ( ! $success ) {
			$wgOut->showFileDeleteError( $this->mTempPath );
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
		$wgOut->addHTML( "<h2>" . wfMsgHtml( 'uploadwarning' ) . "</h2>\n" );
		$wgOut->addHTML( "<span class='error'>{$error}</span>\n" );
	}

	/**
	 * There's something wrong with this file, not enough to reject it
	 * totally but we require manual intervention to save it for real.
	 * Stash it away, then present a form asking to confirm or cancel.
	 *
	 * @param string $warning as HTML
	 * @access private
	 */
	function uploadWarning( $warning ) {
		global $wgOut, $wgContLang;
		global $wgUseCopyrightUpload;

		$this->mSessionKey = $this->stashSession();
		if( !$this->mSessionKey ) {
			# Couldn't save file; an error has been displayed so let's go.
			return;
		}

		$wgOut->addHTML( "<h2>" . wfMsgHtml( 'uploadwarning' ) . "</h2>\n" );
		$wgOut->addHTML( "<ul class='warning'>{$warning}</ul><br />\n" );

		$save = wfMsgHtml( 'savefile' );
		$reupload = wfMsgHtml( 'reupload' );
		$iw = wfMsgWikiHtml( 'ignorewarning' );
		$reup = wfMsgWikiHtml( 'reuploaddesc' );
		$titleObj = SpecialPage::getTitleFor( 'Upload' );
		$action = $titleObj->escapeLocalURL( 'action=submit' );
		$align1 = $wgContLang->isRTL() ? 'left' : 'right';
		$align2 = $wgContLang->isRTL() ? 'right' : 'left';

		if ( $wgUseCopyrightUpload )
		{
			$copyright =  "
	<input type='hidden' name='wpUploadCopyStatus' value=\"" . htmlspecialchars( $this->mCopyrightStatus ) . "\" />
	<input type='hidden' name='wpUploadSource' value=\"" . htmlspecialchars( $this->mCopyrightSource ) . "\" />
	";
		} else {
			$copyright = "";
		}

		$wgOut->addHTML( "
	<form id='uploadwarning' method='post' enctype='multipart/form-data' action='$action'>
		<input type='hidden' name='wpIgnoreWarning' value='1' />
		<input type='hidden' name='wpSessionKey' value=\"" . htmlspecialchars( $this->mSessionKey ) . "\" />
		<input type='hidden' name='wpUploadDescription' value=\"" . htmlspecialchars( $this->mComment ) . "\" />
		<input type='hidden' name='wpLicense' value=\"" . htmlspecialchars( $this->mLicense ) . "\" />
		<input type='hidden' name='wpDestFile' value=\"" . htmlspecialchars( $this->mDesiredDestName ) . "\" />
		<input type='hidden' name='wpWatchthis' value=\"" . htmlspecialchars( intval( $this->mWatchthis ) ) . "\" />
	{$copyright}
	<table border='0'>
		<tr>
			<tr>
				<td align='$align1'>
					<input tabindex='2' type='submit' name='wpUpload' value=\"$save\" />
				</td>
				<td align='$align2'>$iw</td>
			</tr>
			<tr>
				<td align='$align1'>
					<input tabindex='2' type='submit' name='wpReUpload' value=\"{$reupload}\" />
				</td>
				<td align='$align2'>$reup</td>
			</tr>
		</tr>
	</table></form>\n" );
	}

	/**
	 * Displays the main upload form, optionally with a highlighted
	 * error message up at the top.
	 *
	 * @param string $msg as HTML
	 * @access private
	 */
	function mainUploadForm( $msg='' ) {
		global $wgOut, $wgUser, $wgContLang;
		global $wgUseCopyrightUpload, $wgUseAjax, $wgAjaxUploadDestCheck, $wgAjaxLicensePreview;
		global $wgRequest, $wgAllowCopyUploads;
		global $wgStylePath, $wgStyleVersion;

		$useAjaxDestCheck = $wgUseAjax && $wgAjaxUploadDestCheck;
		$useAjaxLicensePreview = $wgUseAjax && $wgAjaxLicensePreview;
		
		$adc = wfBoolToStr( $useAjaxDestCheck );
		$alp = wfBoolToStr( $useAjaxLicensePreview );
		
		$wgOut->addScript( "<script type=\"text/javascript\">
wgAjaxUploadDestCheck = {$adc};
wgAjaxLicensePreview = {$alp};
</script>
<script type=\"text/javascript\" src=\"{$wgStylePath}/common/upload.js?{$wgStyleVersion}\"></script>
		" );

		if( !wfRunHooks( 'UploadForm:initial', array( &$this ) ) )
		{
			wfDebug( "Hook 'UploadForm:initial' broke output of the upload form" );
			return false;
		}
		
		if( $this->mDesiredDestName && $wgUser->isAllowed( 'deletedhistory' ) ) {
			$title = Title::makeTitleSafe( NS_IMAGE, $this->mDesiredDestName );
			if( $title instanceof Title && ( $count = $title->isDeleted() ) > 0 ) {
				$link = wfMsgExt(
					$wgUser->isAllowed( 'delete' ) ? 'thisisdeleted' : 'viewdeleted',
					array( 'parse', 'replaceafter' ),
					$wgUser->getSkin()->makeKnownLinkObj(
						SpecialPage::getTitleFor( 'Undelete', $title->getPrefixedText() ),
						wfMsgHtml( 'restorelink', $count )
					)
				);
				$wgOut->addHtml( "<div id=\"contentSub2\">{$link}</div>" );
			}				
		}

		$cols = intval($wgUser->getOption( 'cols' ));
		$ew = $wgUser->getOption( 'editwidth' );
		if ( $ew ) $ew = " style=\"width:100%\"";
		else $ew = '';

		if ( '' != $msg ) {
			$sub = wfMsgHtml( 'uploaderror' );
			$wgOut->addHTML( "<h2>{$sub}</h2>\n" .
			  "<span class='error'>{$msg}</span>\n" );
		}
		$wgOut->addHTML( '<div id="uploadtext">' );
		$wgOut->addWikiText( wfMsgNoTrans( 'uploadtext', $this->mDesiredDestName ) );
		$wgOut->addHTML( '</div>' );

		$sourcefilename = wfMsgHtml( 'sourcefilename' );
		$destfilename = wfMsgHtml( 'destfilename' );
		$summary = wfMsgExt( 'fileuploadsummary', 'parseinline' );

		$licenses = new Licenses();
		$license = wfMsgExt( 'license', array( 'parseinline' ) );
		$nolicense = wfMsgHtml( 'nolicense' );
		$licenseshtml = $licenses->getHtml();

		$ulb = wfMsgHtml( 'uploadbtn' );
		$ulbKey = wfMsgHtml( 'accesskey-upload' );
		$ulbTip = wfMsgHtml( 'tooltip-upload' ) .' ['.wfMsg( 'accesskey-upload' ) . ']';


		$titleObj = SpecialPage::getTitleFor( 'Upload' );
		$action = $titleObj->escapeLocalURL();

		$encDestName = htmlspecialchars( $this->mDesiredDestName );

		$watchChecked =
			( $wgUser->getOption( 'watchdefault' ) ||
				( $wgUser->getOption( 'watchcreations' ) && $this->mDesiredDestName == '' ) )
			? 'checked="checked"'
			: '';
		$warningChecked = $this->mIgnoreWarning ? 'checked' : '';

		// Prepare form for upload or upload/copy
		if( $wgAllowCopyUploads && $wgUser->isAllowed( 'upload_by_url' ) ) {
			$filename_form =
				"<input type='radio' id='wpSourceTypeFile' name='wpSourceType' value='file' " .
				   "onchange='toggle_element_activation(\"wpUploadFileURL\",\"wpUploadFile\")' checked />" .
				 "<input tabindex='1' type='file' name='wpUploadFile' id='wpUploadFile' " .
				   "onfocus='" . 
				     "toggle_element_activation(\"wpUploadFileURL\",\"wpUploadFile\");" .
				     "toggle_element_check(\"wpSourceTypeFile\",\"wpSourceTypeURL\")'" .
				($this->mDesiredDestName?"":"onchange='fillDestFilename(\"wpUploadFile\")' ") . "size='40' />" .
				wfMsgHTML( 'upload_source_file' ) . "<br/>" .
				"<input type='radio' id='wpSourceTypeURL' name='wpSourceType' value='web' " .
				  "onchange='toggle_element_activation(\"wpUploadFile\",\"wpUploadFileURL\")' />" .
				"<input tabindex='1' type='text' name='wpUploadFileURL' id='wpUploadFileURL' " .
				  "onfocus='" .
				    "toggle_element_activation(\"wpUploadFile\",\"wpUploadFileURL\");" .
				    "toggle_element_check(\"wpSourceTypeURL\",\"wpSourceTypeFile\")'" .
				($this->mDesiredDestName?"":"onchange='fillDestFilename(\"wpUploadFileURL\")' ") . "size='40' DISABLED />" .
				wfMsgHtml( 'upload_source_url' ) ;
		} else {
			$filename_form =
				"<input tabindex='1' type='file' name='wpUploadFile' id='wpUploadFile' " .
				($this->mDesiredDestName?"":"onchange='fillDestFilename(\"wpUploadFile\")' ") .
				"size='40' />" .
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
		$align1 = $wgContLang->isRTL() ? 'left' : 'right';
		$align2 = $wgContLang->isRTL() ? 'right' : 'left';

		$wgOut->addHTML( <<<EOT
	<form id='upload' method='post' enctype='multipart/form-data' action="$action">
		<table border='0'>
		<tr>
	  {$this->uploadFormTextTop}
			<td align='$align1' valign='top'><label for='wpUploadFile'>{$sourcefilename}:</label></td>
			<td align='$align2'>
				{$filename_form}
			</td>
		</tr>
		<tr>
			<td align='$align1'><label for='wpDestFile'>{$destfilename}:</label></td>
			<td align='$align2'>
				<input tabindex='2' type='text' name='wpDestFile' id='wpDestFile' size='40' 
					value="$encDestName" $destOnkeyup />
			</td>
		</tr>
		<tr>
			<td align='$align1'><label for='wpUploadDescription'>{$summary}</label></td>
			<td align='$align2'>
				<textarea tabindex='3' name='wpUploadDescription' id='wpUploadDescription' rows='6' 
					cols='{$cols}'{$ew}>$encComment</textarea>
	   {$this->uploadFormTextAfterSummary}
			</td>
		</tr>
		<tr>
EOT
		);

		if ( $licenseshtml != '' ) {
			global $wgStylePath;
			$wgOut->addHTML( "
			<td align='$align1'><label for='wpLicense'>$license:</label></td>
			<td align='$align2'>
				<select name='wpLicense' id='wpLicense' tabindex='4'
					onchange='licenseSelectorCheck()'>
					<option value=''>$nolicense</option>
					$licenseshtml
				</select>
			</td>
			</tr>
			<tr>" );
			if( $useAjaxLicensePreview ) {
				$wgOut->addHtml( "
					<td></td>
					<td id=\"mw-license-preview\"></td>
				</tr>
				<tr>" );
			}
		}

		if ( $wgUseCopyrightUpload ) {
			$filestatus = wfMsgHtml ( 'filestatus' );
			$copystatus =  htmlspecialchars( $this->mCopyrightStatus );
			$filesource = wfMsgHtml ( 'filesource' );
			$uploadsource = htmlspecialchars( $this->mCopyrightSource );

			$wgOut->addHTML( "
			        <td align='$align1' nowrap='nowrap'><label for='wpUploadCopyStatus'>$filestatus:</label></td>
					<td><input tabindex='5' type='text' name='wpUploadCopyStatus' id='wpUploadCopyStatus' 
					  value=\"$copystatus\" size='40' /></td>
		        </tr>
			<tr>
		        	<td align='$align1'><label for='wpUploadCopyStatus'>$filesource:</label></td>
					<td><input tabindex='6' type='text' name='wpUploadSource' id='wpUploadCopyStatus' 
					  value=\"$uploadsource\" size='40' /></td>
			</tr>
			<tr>
		");
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
		<td align='$align2'><input tabindex='9' type='submit' name='wpUpload' value=\"{$ulb}\" title=\"{$ulbTip}\" accesskey=\"{$ulbKey}\" /></td>
	</tr>
	<tr>
		<td></td>
		<td align='$align2'>
		" );
		$wgOut->addWikiText( wfMsgForContent( 'edittools' ) );
		$wgOut->addHTML( "
		</td>
	</tr>

	</table>
	<input type='hidden' name='wpDestFileWarningAck' id='wpDestFileWarningAck' value=''/>
	</form>" );
	}

	/* -------------------------------------------------------------- */

	/**
	 * Split a file into a base name and all dot-delimited 'extensions'
	 * on the end. Some web server configurations will fall back to
	 * earlier pseudo-'extensions' to determine type and execute
	 * scripts, so the blacklist needs to check them all.
	 *
	 * @return array
	 */
	function splitExtensions( $filename ) {
		$bits = explode( '.', $filename );
		$basename = array_shift( $bits );
		return array( $basename, $bits );
	}

	/**
	 * Perform case-insensitive match against a list of file extensions.
	 * Returns true if the extension is in the list.
	 *
	 * @param string $ext
	 * @param array $list
	 * @return bool
	 */
	function checkFileExtension( $ext, $list ) {
		return in_array( strtolower( $ext ), $list );
	}

	/**
	 * Perform case-insensitive match against a list of file extensions.
	 * Returns true if any of the extensions are in the list.
	 *
	 * @param array $ext
	 * @param array $list
	 * @return bool
	 */
	function checkFileExtensionList( $ext, $list ) {
		foreach( $ext as $e ) {
			if( in_array( strtolower( $e ), $list ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Verifies that it's ok to include the uploaded file
	 *
	 * @param string $tmpfile the full path of the temporary file to verify
	 * @param string $extension The filename extension that the file is to be served with
	 * @return mixed true of the file is verified, a WikiError object otherwise.
	 */
	function verify( $tmpfile, $extension ) {
		#magically determine mime type
		$magic=& MimeMagic::singleton();
		$mime= $magic->guessMimeType($tmpfile,false);

		#check mime type, if desired
		global $wgVerifyMimeType;
		if ($wgVerifyMimeType) {

		  wfDebug ( "\n\nmime: <$mime> extension: <$extension>\n\n");
			#check mime type against file extension
			if( !$this->verifyExtension( $mime, $extension ) ) {
				return new WikiErrorMsg( 'uploadcorrupt' );
			}

			#check mime type blacklist
			global $wgMimeTypeBlacklist;
			if( isset($wgMimeTypeBlacklist) && !is_null($wgMimeTypeBlacklist)
				&& $this->checkFileExtension( $mime, $wgMimeTypeBlacklist ) ) {
				return new WikiErrorMsg( 'filetype-badmime', htmlspecialchars( $mime ) );
			}
		}

		#check for htmlish code and javascript
		if( $this->detectScript ( $tmpfile, $mime, $extension ) ) {
			return new WikiErrorMsg( 'uploadscripted' );
		}

		/**
		* Scan the uploaded file for viruses
		*/
		$virus= $this->detectVirus($tmpfile);
		if ( $virus ) {
			return new WikiErrorMsg( 'uploadvirus', htmlspecialchars($virus) );
		}

		wfDebug( __METHOD__.": all clear; passing.\n" );
		return true;
	}

	/**
	 * Checks if the mime type of the uploaded file matches the file extension.
	 *
	 * @param string $mime the mime type of the uploaded file
	 * @param string $extension The filename extension that the file is to be served with
	 * @return bool
	 */
	function verifyExtension( $mime, $extension ) {
		$magic =& MimeMagic::singleton();

		if ( ! $mime || $mime == 'unknown' || $mime == 'unknown/unknown' )
			if ( ! $magic->isRecognizableExtension( $extension ) ) {
				wfDebug( __METHOD__.": passing file with unknown detected mime type; " .
					"unrecognized extension '$extension', can't verify\n" );
				return true;
			} else {
				wfDebug( __METHOD__.": rejecting file with unknown detected mime type; ".
					"recognized extension '$extension', so probably invalid file\n" );
				return false;
			}

		$match= $magic->isMatchingExtension($extension,$mime);

		if ($match===NULL) {
			wfDebug( __METHOD__.": no file extension known for mime type $mime, passing file\n" );
			return true;
		} elseif ($match===true) {
			wfDebug( __METHOD__.": mime type $mime matches extension $extension, passing file\n" );

			#TODO: if it's a bitmap, make sure PHP or ImageMagic resp. can handle it!
			return true;

		} else {
			wfDebug( __METHOD__.": mime type $mime mismatches file extension $extension, rejecting file\n" );
			return false;
		}
	}

	/** 
	 * Heuristic for detecting files that *could* contain JavaScript instructions or
	 * things that may look like HTML to a browser and are thus
	 * potentially harmful. The present implementation will produce false positives in some situations.
	 *
	 * @param string $file Pathname to the temporary upload file
	 * @param string $mime The mime type of the file
	 * @param string $extension The extension of the file
	 * @return bool true if the file contains something looking like embedded scripts
	 */
	function detectScript($file, $mime, $extension) {
		global $wgAllowTitlesInSVG;

		#ugly hack: for text files, always look at the entire file.
		#For binarie field, just check the first K.

		if (strpos($mime,'text/')===0) $chunk = file_get_contents( $file );
		else {
			$fp = fopen( $file, 'rb' );
			$chunk = fread( $fp, 1024 );
			fclose( $fp );
		}

		$chunk= strtolower( $chunk );

		if (!$chunk) return false;

		#decode from UTF-16 if needed (could be used for obfuscation).
		if (substr($chunk,0,2)=="\xfe\xff") $enc= "UTF-16BE";
		elseif (substr($chunk,0,2)=="\xff\xfe") $enc= "UTF-16LE";
		else $enc= NULL;

		if ($enc) $chunk= iconv($enc,"ASCII//IGNORE",$chunk);

		$chunk= trim($chunk);

		#FIXME: convert from UTF-16 if necessarry!

		wfDebug("SpecialUpload::detectScript: checking for embedded scripts and HTML stuff\n");

		#check for HTML doctype
		if (eregi("<!DOCTYPE *X?HTML",$chunk)) return true;

		/**
		* Internet Explorer for Windows performs some really stupid file type
		* autodetection which can cause it to interpret valid image files as HTML
		* and potentially execute JavaScript, creating a cross-site scripting
		* attack vectors.
		*
		* Apple's Safari browser also performs some unsafe file type autodetection
		* which can cause legitimate files to be interpreted as HTML if the
		* web server is not correctly configured to send the right content-type
		* (or if you're really uploading plain text and octet streams!)
		*
		* Returns true if IE is likely to mistake the given file for HTML.
		* Also returns true if Safari would mistake the given file for HTML
		* when served with a generic content-type.
		*/

		$tags = array(
			'<body',
			'<head',
			'<html',   #also in safari
			'<img',
			'<pre',
			'<script', #also in safari
			'<table'
			);
		if( ! $wgAllowTitlesInSVG && $extension !== 'svg' && $mime !== 'image/svg' ) {
			$tags[] = '<title';
		}

		foreach( $tags as $tag ) {
			if( false !== strpos( $chunk, $tag ) ) {
				return true;
			}
		}

		/*
		* look for javascript
		*/

		#resolve entity-refs to look at attributes. may be harsh on big files... cache result?
		$chunk = Sanitizer::decodeCharReferences( $chunk );

		#look for script-types
		if (preg_match('!type\s*=\s*[\'"]?\s*(?:\w*/)?(?:ecma|java)!sim',$chunk)) return true;

		#look for html-style script-urls
		if (preg_match('!(?:href|src|data)\s*=\s*[\'"]?\s*(?:ecma|java)script:!sim',$chunk)) return true;

		#look for css-style script-urls
		if (preg_match('!url\s*\(\s*[\'"]?\s*(?:ecma|java)script:!sim',$chunk)) return true;

		wfDebug("SpecialUpload::detectScript: no scripts found\n");
		return false;
	}

	/** 
	 * Generic wrapper function for a virus scanner program.
	 * This relies on the $wgAntivirus and $wgAntivirusSetup variables.
	 * $wgAntivirusRequired may be used to deny upload if the scan fails.
	 *
	 * @param string $file Pathname to the temporary upload file
	 * @return mixed false if not virus is found, NULL if the scan fails or is disabled,
	 *         or a string containing feedback from the virus scanner if a virus was found.
	 *         If textual feedback is missing but a virus was found, this function returns true.
	 */
	function detectVirus($file) {
		global $wgAntivirus, $wgAntivirusSetup, $wgAntivirusRequired, $wgOut;

		if ( !$wgAntivirus ) {
			wfDebug( __METHOD__.": virus scanner disabled\n");
			return NULL;
		}

		if ( !$wgAntivirusSetup[$wgAntivirus] ) {
			wfDebug( __METHOD__.": unknown virus scanner: $wgAntivirus\n" );
			# @TODO: localise
			$wgOut->addHTML( "<div class='error'>Bad configuration: unknown virus scanner: <i>$wgAntivirus</i></div>\n" ); 
			return "unknown antivirus: $wgAntivirus";
		}

		# look up scanner configuration
		$command = $wgAntivirusSetup[$wgAntivirus]["command"];
		$exitCodeMap = $wgAntivirusSetup[$wgAntivirus]["codemap"];
		$msgPattern = isset( $wgAntivirusSetup[$wgAntivirus]["messagepattern"] ) ?
			$wgAntivirusSetup[$wgAntivirus]["messagepattern"] : null;

		if ( strpos( $command,"%f" ) === false ) {
			# simple pattern: append file to scan
			$command .= " " . wfEscapeShellArg( $file ); 
		} else {
			# complex pattern: replace "%f" with file to scan
			$command = str_replace( "%f", wfEscapeShellArg( $file ), $command ); 
		}

		wfDebug( __METHOD__.": running virus scan: $command \n" );

		# execute virus scanner
		$exitCode = false;

		#NOTE: there's a 50 line workaround to make stderr redirection work on windows, too.
		#      that does not seem to be worth the pain.
		#      Ask me (Duesentrieb) about it if it's ever needed.
		$output = array();
		if ( wfIsWindows() ) {
			exec( "$command", $output, $exitCode );
		} else {
			exec( "$command 2>&1", $output, $exitCode );
		}

		# map exit code to AV_xxx constants.
		$mappedCode = $exitCode;
		if ( $exitCodeMap ) { 
			if ( isset( $exitCodeMap[$exitCode] ) ) {
				$mappedCode = $exitCodeMap[$exitCode];
			} elseif ( isset( $exitCodeMap["*"] ) ) {
				$mappedCode = $exitCodeMap["*"];
			}
		}

		if ( $mappedCode === AV_SCAN_FAILED ) { 
			# scan failed (code was mapped to false by $exitCodeMap)
			wfDebug( __METHOD__.": failed to scan $file (code $exitCode).\n" );

			if ( $wgAntivirusRequired ) { 
				return "scan failed (code $exitCode)"; 
			} else { 
				return NULL; 
			}
		} else if ( $mappedCode === AV_SCAN_ABORTED ) { 
			# scan failed because filetype is unknown (probably imune)
			wfDebug( __METHOD__.": unsupported file type $file (code $exitCode).\n" );
			return NULL;
		} else if ( $mappedCode === AV_NO_VIRUS ) {
			# no virus found
			wfDebug( __METHOD__.": file passed virus scan.\n" );
			return false;
		} else {
			$output = join( "\n", $output );
			$output = trim( $output );

			if ( !$output ) {
				$output = true; #if there's no output, return true
			} elseif ( $msgPattern ) {
				$groups = array();
				if ( preg_match( $msgPattern, $output, $groups ) ) {
					if ( $groups[1] ) {
						$output = $groups[1];
					}
				}
			}

			wfDebug( __METHOD__.": FOUND VIRUS! scanner feedback: $output" );
			return $output;
		}
	}

	/**
	 * Check if the temporary file is MacBinary-encoded, as some uploads
	 * from Internet Explorer on Mac OS Classic and Mac OS X will be.
	 * If so, the data fork will be extracted to a second temporary file,
	 * which will then be checked for validity and either kept or discarded.
	 *
	 * @access private
	 */
	function checkMacBinary() {
		$macbin = new MacBinary( $this->mTempPath );
		if( $macbin->isValid() ) {
			$dataFile = tempnam( wfTempDir(), "WikiMacBinary" );
			$dataHandle = fopen( $dataFile, 'wb' );

			wfDebug( "SpecialUpload::checkMacBinary: Extracting MacBinary data fork to $dataFile\n" );
			$macbin->extractData( $dataHandle );

			$this->mTempPath = $dataFile;
			$this->mFileSize = $macbin->dataForkLength();

			// We'll have to manually remove the new file if it's not kept.
			$this->mRemoveTempFile = true;
		}
		$macbin->close();
	}

	/**
	 * If we've modified the upload file we need to manually remove it
	 * on exit to clean up.
	 * @access private
	 */
	function cleanupTempFile() {
		if ( $this->mRemoveTempFile && file_exists( $this->mTempPath ) ) {
			wfDebug( "SpecialUpload::cleanupTempFile: Removing temporary file {$this->mTempPath}\n" );
			unlink( $this->mTempPath );
		}
	}

	/**
	 * Check if there's an overwrite conflict and, if so, if restrictions
	 * forbid this user from performing the upload.
	 *
	 * @return mixed true on success, WikiError on failure
	 * @access private
	 */
	function checkOverwrite( $name ) {
		$img = wfFindFile( $name );

		$error = '';
		if( $img ) {
			global $wgUser, $wgOut;
			if( $img->isLocal() ) {
				if( !self::userCanReUpload( $wgUser, $img->name ) ) {
					$error = 'fileexists-forbidden';
				}
			} else {
				if( !$wgUser->isAllowed( 'reupload' ) ||
				    !$wgUser->isAllowed( 'reupload-shared' ) ) {
					$error = "fileexists-shared-forbidden";
				}
			}
		}

		if( $error ) {
			$errorText = wfMsg( $error, wfEscapeWikiText( $img->getName() ) );
			return new WikiError( $wgOut->parse( $errorText ) );
		}

		// Rockin', go ahead and upload
		return true;
	}

	 /**
	 * Check if a user is the last uploader
	 *
	 * @param User $user
	 * @param string $img, image name
	 * @return bool
	 */
	public static function userCanReUpload( User $user, $img ) {
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

		return $user->getID() == $row->img_user;
	}

	/**
	 * Display an error with a wikitext description
	 */
	function showError( $description ) {
		global $wgOut;
		$wgOut->setPageTitle( wfMsg( "internalerror" ) );
		$wgOut->setRobotpolicy( "noindex,nofollow" );
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
}
