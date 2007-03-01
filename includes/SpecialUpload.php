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
 *
 * @addtogroup SpecialPage
 */
class UploadForm {
	/**#@+
	 * @access private
	 */
	var $mUploadFile, $mUploadDescription, $mLicense ,$mIgnoreWarning, $mUploadError;
	var $mUploadSaveName, $mUploadTempName, $mUploadSize, $mUploadOldVersion;
	var $mUploadCopyStatus, $mUploadSource, $mReUpload, $mAction, $mUpload;
	var $mOname, $mSessionKey, $mStashed, $mDestFile, $mRemoveTempFile, $mSourceType;
	var $mUploadTempFileSize = 0;

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
	function UploadForm( &$request ) {
		global $wgAllowCopyUploads;
		$this->mDestFile          = $request->getText( 'wpDestFile' );

		if( !$request->wasPosted() ) {
			# GET requests just give the main form; no data except wpDestfile.
			return;
		}

		# Placeholders for text injection by hooks (empty per default)
		$this->uploadFormTextTop = "";
		$this->uploadFormTextAfterSummary = "";

		$this->mIgnoreWarning     = $request->getCheck( 'wpIgnoreWarning' );
		$this->mReUpload          = $request->getCheck( 'wpReUpload' );
		$this->mUpload            = $request->getCheck( 'wpUpload' );

		$this->mUploadDescription = $request->getText( 'wpUploadDescription' );
		$this->mLicense           = $request->getText( 'wpLicense' );
		$this->mUploadCopyStatus  = $request->getText( 'wpUploadCopyStatus' );
		$this->mUploadSource      = $request->getText( 'wpUploadSource' );
		$this->mWatchthis         = $request->getBool( 'wpWatchthis' );
		$this->mSourceType         = $request->getText( 'wpSourceType' );
		wfDebug( "UploadForm: watchthis is: '$this->mWatchthis'\n" );

		$this->mAction            = $request->getVal( 'action' );

		$this->mSessionKey        = $request->getInt( 'wpSessionKey' );
		if( !empty( $this->mSessionKey ) &&
			isset( $_SESSION['wsUploadData'][$this->mSessionKey] ) ) {
			/**
			 * Confirming a temporarily stashed upload.
			 * We don't want path names to be forged, so we keep
			 * them in the session on the server and just give
			 * an opaque key to the user agent.
			 */
			$data = $_SESSION['wsUploadData'][$this->mSessionKey];
			$this->mUploadTempName   = $data['mUploadTempName'];
			$this->mUploadSize       = $data['mUploadSize'];
			$this->mOname            = $data['mOname'];
			$this->mUploadError      = 0/*UPLOAD_ERR_OK*/;
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
		$this->mUploadTempName = $request->getFileTempName( 'wpUploadFile' );
		$this->mUploadSize     = $request->getFileSize( 'wpUploadFile' );
		$this->mOname          = $request->getFileName( 'wpUploadFile' );
		$this->mUploadError    = $request->getUploadError( 'wpUploadFile' );
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

		$this->mUploadTempName = $local_file;
		$this->mUploadError    = $this->curlCopy( $url, $local_file );
		$this->mUploadSize     = $this->mUploadTempFileSize;
		$this->mOname          = array_pop( explode( '/', $url ) );
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
		$this->mUploadTempFile = @fopen( $this->mUploadTempName, "wb" );
		if( $this->mUploadTempFile === false ) {
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

		fclose( $this->mUploadTempFile );
		unset( $this->mUploadTempFile );
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
		$this->mUploadTempFileSize += $length;
		if( $this->mUploadTempFileSize > $wgMaxUploadSize ) {
			return 0;
		}
		fwrite( $this->mUploadTempFile, $data );
		return $length;
	}

	/**
	 * Start doing stuff
	 * @access public
	 */
	function execute() {
		global $wgUser, $wgOut;
		global $wgEnableUploads, $wgUploadDirectory;

		# Check uploading enabled
		if( !$wgEnableUploads ) {
			$wgOut->showErrorPage( 'uploaddisabled', 'uploaddisabledtext' );
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

		/** Check if the image directory is writeable, this is a common mistake */
		if( !is_writeable( $wgUploadDirectory ) ) {
			$wgOut->addWikiText( wfMsg( 'upload_directory_read_only', $wgUploadDirectory ) );
			return;
		}

		if( $this->mReUpload ) {
			if( !$this->unsaveUploadedFile() ) {
				return;
			}
			$this->mainUploadForm();
		} else if( 'submit' == $this->mAction || $this->mUpload ) {
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
		if( $this->mUploadError == 1/*UPLOAD_ERR_INI_SIZE*/ ) {
			$this->mainUploadForm( wfMsgHtml( 'largefileserver' ) );
			return;
		}

		/**
		 * If there was no filename or a zero size given, give up quick.
		 */
		if( trim( $this->mOname ) == '' || empty( $this->mUploadSize ) ) {
			$this->mainUploadForm( wfMsgHtml( 'emptyfile' ) );
			return;
		}

		# Chop off any directories in the given filename
		if( $this->mDestFile ) {
			$basename = wfBaseName( $this->mDestFile );
		} else {
			$basename = wfBaseName( $this->mOname );
		}

		/**
		 * We'll want to blacklist against *any* 'extension', and use
		 * only the final one for the whitelist.
		 */
		list( $partname, $ext ) = $this->splitExtensions( $basename );

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

		if( strlen( $partname ) < 3 ) {
			$this->mainUploadForm( wfMsgHtml( 'minlength' ) );
			return;
		}

		/**
		 * Filter out illegal characters, and try to make a legible name
		 * out of it. We'll strip some silently that Title would die on.
		 */
		$filtered = preg_replace ( "/[^".Title::legalChars()."]|:/", '-', $basename );
		$nt = Title::newFromText( $filtered );
		if( is_null( $nt ) ) {
			$this->uploadError( wfMsgWikiHtml( 'illegalfilename', htmlspecialchars( $filtered ) ) );
			return;
		}
		$nt =& Title::makeTitle( NS_IMAGE, $nt->getDBkey() );
		$this->mUploadSaveName = $nt->getDBkey();

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
		$overwrite = $this->checkOverwrite( $this->mUploadSaveName );
		if( WikiError::isError( $overwrite ) ) {
			return $this->uploadError( $overwrite->toString() );
		}

		/* Don't allow users to override the blacklist (check file extension) */
		global $wgStrictFileExtensions;
		global $wgFileExtensions, $wgFileBlacklist;
		if ($finalExt == '') {
			return $this->uploadError( wfMsgExt( 'filetype-missing', array ( 'parseinline' ) ) );
		} elseif ( $this->checkFileExtensionList( $ext, $wgFileBlacklist ) ||
				($wgStrictFileExtensions &&
					!$this->checkFileExtension( $finalExt, $wgFileExtensions ) ) ) {
			return $this->uploadError( wfMsgExt( 'filetype-badtype', array ( 'parseinline' ), htmlspecialchars( $finalExt ), implode ( ', ', $wgFileExtensions ) ) );
		}

		/**
		 * Look at the contents of the file; if we can recognize the
		 * type but it's corrupt or data of the wrong type, we should
		 * probably not accept it.
		 */
		if( !$this->mStashed ) {
			$this->checkMacBinary();
			$veri = $this->verify( $this->mUploadTempName, $finalExt );

			if( $veri !== true ) { //it's a wiki error...
				return $this->uploadError( $veri->toString() );
			}
		}

		/**
		 * Provide an opportunity for extensions to add futher checks
		 */
		$error = '';
		if( !wfRunHooks( 'UploadVerification',
				array( $this->mUploadSaveName, $this->mUploadTempName, &$error ) ) ) {
			return $this->uploadError( $error );
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
			if( $this->mUploadSaveName != $filtered ) {
				$warning .=  '<li>'.wfMsgHtml( 'badfilename', htmlspecialchars( $this->mUploadSaveName ) ).'</li>';
			}

			global $wgCheckFileExtensions;
			if ( $wgCheckFileExtensions ) {
				if ( ! $this->checkFileExtension( $finalExt, $wgFileExtensions ) ) {
					$warning .= '<li>'.wfMsgExt( 'filetype-badtype', array ( 'parseinline' ), htmlspecialchars( $finalExt ), implode ( ', ', $wgFileExtensions ) ).'</li>';
				}
			}

			global $wgUploadSizeWarning;
			if ( $wgUploadSizeWarning && ( $this->mUploadSize > $wgUploadSizeWarning ) ) {
				$skin = $wgUser->getSkin();
				$wsize = $skin->formatSize( $wgUploadSizeWarning );
				$asize = $skin->formatSize( $this->mUploadSize );
				$warning .= '<li>' . wfMsgHtml( 'large-file', $wsize, $asize ) . '</li>';
			}
			if ( $this->mUploadSize == 0 ) {
				$warning .= '<li>'.wfMsgHtml( 'emptyfile' ).'</li>';
			}

			if( $nt->getArticleID() ) {
				global $wgUser;
				$sk = $wgUser->getSkin();
				$dlink = $sk->makeKnownLinkObj( $nt );
				$warning .= '<li>'.wfMsgHtml( 'fileexists', $dlink ).'</li>';
			} else {
				# If the file existed before and was deleted, warn the user of this
				# Don't bother doing so if the image exists now, however
				$image = new Image( $nt );
				if( $image->wasDeleted() ) {
					$skin = $wgUser->getSkin();
					$ltitle = SpecialPage::getTitleFor( 'Log' );
					$llink = $skin->makeKnownLinkObj( $ltitle, wfMsgHtml( 'deletionlog' ), 'type=delete&page=' . $nt->getPrefixedUrl() );
					$warning .= wfOpenElement( 'li' ) . wfMsgWikiHtml( 'filewasdeleted', $llink ) . wfCloseElement( 'li' );
				}
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
		$hasBeenMunged = !empty( $this->mSessionKey ) || $this->mRemoveTempFile;
		if( $this->saveUploadedFile( $this->mUploadSaveName,
		                             $this->mUploadTempName,
		                             $hasBeenMunged ) ) {
			/**
			 * Update the upload log and create the description page
			 * if it's a new file.
			 */
			$img = Image::newFromName( $this->mUploadSaveName );
			$success = $img->recordUpload( $this->mUploadOldVersion,
			                                $this->mUploadDescription,
			                                $this->mLicense,
			                                $this->mUploadCopyStatus,
			                                $this->mUploadSource,
			                                $this->mWatchthis );

			if ( $success ) {
				$this->showSuccess();
				wfRunHooks( 'UploadComplete', array( &$img ) );
			} else {
				// Image::recordUpload() fails if the image went missing, which is
				// unlikely, hence the lack of a specialised message
				$wgOut->showFileNotFoundError( $this->mUploadSaveName );
			}
		}
	}

	/**
	 * Move the uploaded file from its temporary location to the final
	 * destination. If a previous version of the file exists, move
	 * it into the archive subdirectory.
	 *
	 * @todo If the later save fails, we may have disappeared the original file.
	 *
	 * @param string $saveName
	 * @param string $tempName full path to the temporary file
	 * @param bool $useRename if true, doesn't check that the source file
	 *                        is a PHP-managed upload temporary
	 */
	function saveUploadedFile( $saveName, $tempName, $useRename = false ) {
		global $wgOut, $wgAllowCopyUploads;

		if ( !$useRename AND $wgAllowCopyUploads AND $this->mSourceType == 'web' ) $useRename = true;

		$fname= "SpecialUpload::saveUploadedFile";

		$dest = wfImageDir( $saveName );
		$archive = wfImageArchiveDir( $saveName );
		if ( !is_dir( $dest ) ) wfMkdirParents( $dest );
		if ( !is_dir( $archive ) ) wfMkdirParents( $archive );

		$this->mSavedFile = "{$dest}/{$saveName}";

		if( is_file( $this->mSavedFile ) ) {
			$this->mUploadOldVersion = gmdate( 'YmdHis' ) . "!{$saveName}";
			wfSuppressWarnings();
			$success = rename( $this->mSavedFile, "${archive}/{$this->mUploadOldVersion}" );
			wfRestoreWarnings();

			if( ! $success ) {
				$wgOut->showFileRenameError( $this->mSavedFile,
				  "${archive}/{$this->mUploadOldVersion}" );
				return false;
			}
			else wfDebug("$fname: moved file ".$this->mSavedFile." to ${archive}/{$this->mUploadOldVersion}\n");
		}
		else {
			$this->mUploadOldVersion = '';
		}

		wfSuppressWarnings();
		$success = $useRename
			? rename( $tempName, $this->mSavedFile )
			: move_uploaded_file( $tempName, $this->mSavedFile );
		wfRestoreWarnings();

		if( ! $success ) {
			$wgOut->showFileCopyError( $tempName, $this->mSavedFile );
			return false;
		} else {
			wfDebug("$fname: wrote tempfile $tempName to ".$this->mSavedFile."\n");
		}

		chmod( $this->mSavedFile, 0644 );
		return true;
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
		$archive = wfImageArchiveDir( $saveName, 'temp' );
		if ( !is_dir ( $archive ) ) wfMkdirParents( $archive );
		$stash = $archive . '/' . gmdate( "YmdHis" ) . '!' . $saveName;

		$success = $this->mRemoveTempFile
			? rename( $tempName, $stash )
			: move_uploaded_file( $tempName, $stash );
		if ( !$success ) {
			$wgOut->showFileCopyError( $tempName, $stash );
			return false;
		}

		return $stash;
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
		$stash = $this->saveTempUploadedFile(
			$this->mUploadSaveName, $this->mUploadTempName );

		if( !$stash ) {
			# Couldn't save the file.
			return false;
		}

		$key = mt_rand( 0, 0x7fffffff );
		$_SESSION['wsUploadData'][$key] = array(
			'mUploadTempName' => $stash,
			'mUploadSize'     => $this->mUploadSize,
			'mOname'          => $this->mOname );
		return $key;
	}

	/**
	 * Remove a temporarily kept file stashed by saveTempUploadedFile().
	 * @access private
	 * @return success
	 */
	function unsaveUploadedFile() {
		global $wgOut;
		wfSuppressWarnings();
		$success = unlink( $this->mUploadTempName );
		wfRestoreWarnings();
		if ( ! $success ) {
			$wgOut->showFileDeleteError( $this->mUploadTempName );
			return false;
		} else {
			return true;
		}
	}

	/* -------------------------------------------------------------- */

	/**
	 * Show some text and linkage on successful upload.
	 * @access private
	 */
	function showSuccess() {
		global $wgUser, $wgOut, $wgContLang;

		$sk = $wgUser->getSkin();
		$ilink = $sk->makeMediaLink( $this->mUploadSaveName, Image::imageUrl( $this->mUploadSaveName ) );
		$dname = $wgContLang->getNsText( NS_IMAGE ) . ':'.$this->mUploadSaveName;
		$dlink = $sk->makeKnownLink( $dname, $dname );

		$wgOut->addHTML( '<h2>' . wfMsgHtml( 'successfulupload' ) . "</h2>\n" );
		$text = wfMsgWikiHtml( 'fileuploaded', $ilink, $dlink );
		$wgOut->addHTML( $text );
		$wgOut->returnToMain( false );
	}

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
		global $wgOut;
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

		if ( $wgUseCopyrightUpload )
		{
			$copyright =  "
	<input type='hidden' name='wpUploadCopyStatus' value=\"" . htmlspecialchars( $this->mUploadCopyStatus ) . "\" />
	<input type='hidden' name='wpUploadSource' value=\"" . htmlspecialchars( $this->mUploadSource ) . "\" />
	";
		} else {
			$copyright = "";
		}

		$wgOut->addHTML( "
	<form id='uploadwarning' method='post' enctype='multipart/form-data' action='$action'>
		<input type='hidden' name='wpIgnoreWarning' value='1' />
		<input type='hidden' name='wpSessionKey' value=\"" . htmlspecialchars( $this->mSessionKey ) . "\" />
		<input type='hidden' name='wpUploadDescription' value=\"" . htmlspecialchars( $this->mUploadDescription ) . "\" />
		<input type='hidden' name='wpLicense' value=\"" . htmlspecialchars( $this->mLicense ) . "\" />
		<input type='hidden' name='wpDestFile' value=\"" . htmlspecialchars( $this->mDestFile ) . "\" />
		<input type='hidden' name='wpWatchthis' value=\"" . htmlspecialchars( intval( $this->mWatchthis ) ) . "\" />
	{$copyright}
	<table border='0'>
		<tr>
			<tr>
				<td align='right'>
					<input tabindex='2' type='submit' name='wpUpload' value=\"$save\" />
				</td>
				<td align='left'>$iw</td>
			</tr>
			<tr>
				<td align='right'>
					<input tabindex='2' type='submit' name='wpReUpload' value=\"{$reupload}\" />
				</td>
				<td align='left'>$reup</td>
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
		global $wgOut, $wgUser;
		global $wgUseCopyrightUpload;
		global $wgRequest, $wgAllowCopyUploads;

		if( !wfRunHooks( 'UploadForm:initial', array( &$this ) ) )
		{
			wfDebug( "Hook 'UploadForm:initial' broke output of the upload form" );
			return false;
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
		$wgOut->addWikiText( wfMsgNoTrans( 'uploadtext', $this->mDestFile ) );
		$wgOut->addHTML( '</div>' );

		$sourcefilename = wfMsgHtml( 'sourcefilename' );
		$destfilename = wfMsgHtml( 'destfilename' );
		$summary = wfMsgWikiHtml( 'fileuploadsummary' );

		$licenses = new Licenses();
		$license = wfMsgHtml( 'license' );
		$nolicense = wfMsgHtml( 'nolicense' );
		$licenseshtml = $licenses->getHtml();

		$ulb = wfMsgHtml( 'uploadbtn' );


		$titleObj = SpecialPage::getTitleFor( 'Upload' );
		$action = $titleObj->escapeLocalURL();

		$encDestFile = htmlspecialchars( $this->mDestFile );

		$watchChecked =
			( $wgUser->getOption( 'watchdefault' ) ||
				( $wgUser->getOption( 'watchcreations' ) && $this->mDestFile == '' ) )
			? 'checked="checked"'
			: '';

		// Prepare form for upload or upload/copy
		if( $wgAllowCopyUploads && $wgUser->isAllowed( 'upload_by_url' ) ) {
			$filename_form =
				"<input type='radio' id='wpSourceTypeFile' name='wpSourceType' value='file' onchange='toggle_element_activation(\"wpUploadFileURL\",\"wpUploadFile\")' checked />" .
				"<input tabindex='1' type='file' name='wpUploadFile' id='wpUploadFile' onfocus='toggle_element_activation(\"wpUploadFileURL\",\"wpUploadFile\");toggle_element_check(\"wpSourceTypeFile\",\"wpSourceTypeURL\")'" .
				($this->mDestFile?"":"onchange='fillDestFilename(\"wpUploadFile\")' ") . "size='40' />" .
				wfMsgHTML( 'upload_source_file' ) . "<br/>" .
				"<input type='radio' id='wpSourceTypeURL' name='wpSourceType' value='web' onchange='toggle_element_activation(\"wpUploadFile\",\"wpUploadFileURL\")' />" .
				"<input tabindex='1' type='text' name='wpUploadFileURL' id='wpUploadFileURL' onfocus='toggle_element_activation(\"wpUploadFile\",\"wpUploadFileURL\");toggle_element_check(\"wpSourceTypeURL\",\"wpSourceTypeFile\")'" .
				($this->mDestFile?"":"onchange='fillDestFilename(\"wpUploadFileURL\")' ") . "size='40' DISABLED />" .
				wfMsgHtml( 'upload_source_url' ) ;
		} else {
			$filename_form =
				"<input tabindex='1' type='file' name='wpUploadFile' id='wpUploadFile' " .
				($this->mDestFile?"":"onchange='fillDestFilename(\"wpUploadFile\")' ") .
				"size='40' />" .
				"<input type='hidden' name='wpSourceType' value='file' />" ;
		}

		$wgOut->addHTML( "
	<form id='upload' method='post' enctype='multipart/form-data' action=\"$action\">
		<table border='0'>
		<tr>
	  {$this->uploadFormTextTop}
			<td align='right' valign='top'><label for='wpUploadFile'>{$sourcefilename}:</label></td>
			<td align='left'>
				{$filename_form}
			</td>
		</tr>
		<tr>
			<td align='right'><label for='wpDestFile'>{$destfilename}:</label></td>
			<td align='left'>
				<input tabindex='2' type='text' name='wpDestFile' id='wpDestFile' size='40' value=\"$encDestFile\" />
			</td>
		</tr>
		<tr>
			<td align='right'><label for='wpUploadDescription'>{$summary}</label></td>
			<td align='left'>
				<textarea tabindex='3' name='wpUploadDescription' id='wpUploadDescription' rows='6' cols='{$cols}'{$ew}>" . htmlspecialchars( $this->mUploadDescription ) . "</textarea>
	   {$this->uploadFormTextAfterSummary}
			</td>
		</tr>
		<tr>" );

		if ( $licenseshtml != '' ) {
			global $wgStylePath;
			$wgOut->addHTML( "
			<td align='right'><label for='wpLicense'>$license:</label></td>
			<td align='left'>
				<script type='text/javascript' src=\"$wgStylePath/common/upload.js\"></script>
				<select name='wpLicense' id='wpLicense' tabindex='4'
					onchange='licenseSelectorCheck()'>
					<option value=''>$nolicense</option>
					$licenseshtml
				</select>
			</td>
			</tr>
			<tr>
		");
		}

		if ( $wgUseCopyrightUpload ) {
			$filestatus = wfMsgHtml ( 'filestatus' );
			$copystatus =  htmlspecialchars( $this->mUploadCopyStatus );
			$filesource = wfMsgHtml ( 'filesource' );
			$uploadsource = htmlspecialchars( $this->mUploadSource );

			$wgOut->addHTML( "
			        <td align='right' nowrap='nowrap'><label for='wpUploadCopyStatus'>$filestatus:</label></td>
			        <td><input tabindex='5' type='text' name='wpUploadCopyStatus' id='wpUploadCopyStatus' value=\"$copystatus\" size='40' /></td>
		        </tr>
			<tr>
		        	<td align='right'><label for='wpUploadCopyStatus'>$filesource:</label></td>
			        <td><input tabindex='6' type='text' name='wpUploadSource' id='wpUploadCopyStatus' value=\"$uploadsource\" size='40' /></td>
			</tr>
			<tr>
		");
		}


		$wgOut->addHtml( "
		<td></td>
		<td>
			<input tabindex='7' type='checkbox' name='wpWatchthis' id='wpWatchthis' $watchChecked value='true' />
			<label for='wpWatchthis'>" . wfMsgHtml( 'watchthisupload' ) . "</label>
			<input tabindex='8' type='checkbox' name='wpIgnoreWarning' id='wpIgnoreWarning' value='true' />
			<label for='wpIgnoreWarning'>" . wfMsgHtml( 'ignorewarnings' ) . "</label>
		</td>
	</tr>
	<tr>
		<td></td>
		<td align='left'><input tabindex='9' type='submit' name='wpUpload' value=\"{$ulb}\" /></td>
	</tr>

	<tr>
		<td></td>
		<td align='left'>
		" );
		$wgOut->addWikiText( wfMsgForContent( 'edittools' ) );
		$wgOut->addHTML( "
		</td>
	</tr>

	</table>
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

		$fname= "SpecialUpload::verify";

		#check mime type, if desired
		global $wgVerifyMimeType;
		if ($wgVerifyMimeType) {

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

		wfDebug( "$fname: all clear; passing.\n" );
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
		$fname = 'SpecialUpload::verifyExtension';

		$magic =& MimeMagic::singleton();

		if ( ! $mime || $mime == 'unknown' || $mime == 'unknown/unknown' )
			if ( ! $magic->isRecognizableExtension( $extension ) ) {
				wfDebug( "$fname: passing file with unknown detected mime type; unrecognized extension '$extension', can't verify\n" );
				return true;
			} else {
				wfDebug( "$fname: rejecting file with unknown detected mime type; recognized extension '$extension', so probably invalid file\n" );
				return false;
			}

		$match= $magic->isMatchingExtension($extension,$mime);

		if ($match===NULL) {
			wfDebug( "$fname: no file extension known for mime type $mime, passing file\n" );
			return true;
		} elseif ($match===true) {
			wfDebug( "$fname: mime type $mime matches extension $extension, passing file\n" );

			#TODO: if it's a bitmap, make sure PHP or ImageMagic resp. can handle it!
			return true;

		} else {
			wfDebug( "$fname: mime type $mime mismatches file extension $extension, rejecting file\n" );
			return false;
		}
	}

	/** Heuristig for detecting files that *could* contain JavaScript instructions or
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
		if (preg_match('!type\s*=\s*[\'"]?\s*(\w*/)?(ecma|java)!sim',$chunk)) return true;

		#look for html-style script-urls
		if (preg_match('!(href|src|data)\s*=\s*[\'"]?\s*(ecma|java)script:!sim',$chunk)) return true;

		#look for css-style script-urls
		if (preg_match('!url\s*\(\s*[\'"]?\s*(ecma|java)script:!sim',$chunk)) return true;

		wfDebug("SpecialUpload::detectScript: no scripts found\n");
		return false;
	}

	/** Generic wrapper function for a virus scanner program.
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

		$fname= "SpecialUpload::detectVirus";

		if (!$wgAntivirus) { #disabled?
			wfDebug("$fname: virus scanner disabled\n");

			return NULL;
		}

		if (!$wgAntivirusSetup[$wgAntivirus]) {
			wfDebug("$fname: unknown virus scanner: $wgAntivirus\n");

			$wgOut->addHTML( "<div class='error'>Bad configuration: unknown virus scanner: <i>$wgAntivirus</i></div>\n" ); #LOCALIZE

			return "unknown antivirus: $wgAntivirus";
		}

		#look up scanner configuration
		$virus_scanner= $wgAntivirusSetup[$wgAntivirus]["command"]; #command pattern
		$virus_scanner_codes= $wgAntivirusSetup[$wgAntivirus]["codemap"]; #exit-code map
		$msg_pattern= $wgAntivirusSetup[$wgAntivirus]["messagepattern"]; #message pattern

		$scanner= $virus_scanner; #copy, so we can resolve the pattern

		if (strpos($scanner,"%f")===false) $scanner.= " ".wfEscapeShellArg($file); #simple pattern: append file to scan
		else $scanner= str_replace("%f",wfEscapeShellArg($file),$scanner); #complex pattern: replace "%f" with file to scan

		wfDebug("$fname: running virus scan: $scanner \n");

		#execute virus scanner
		$code= false;

		#NOTE: there's a 50 line workaround to make stderr redirection work on windows, too.
		#      that does not seem to be worth the pain.
		#      Ask me (Duesentrieb) about it if it's ever needed.
		$output = array();
		if (wfIsWindows()) exec("$scanner",$output,$code);
		else exec("$scanner 2>&1",$output,$code);

		$exit_code= $code; #remember for user feedback

		if ($virus_scanner_codes) { #map exit code to AV_xxx constants.
			if (isset($virus_scanner_codes[$code])) {
				$code= $virus_scanner_codes[$code]; # explicit mapping
			} else if (isset($virus_scanner_codes["*"])) {
				$code= $virus_scanner_codes["*"];   # fallback mapping
			}
		}

		if ($code===AV_SCAN_FAILED) { #scan failed (code was mapped to false by $virus_scanner_codes)
			wfDebug("$fname: failed to scan $file (code $exit_code).\n");

			if ($wgAntivirusRequired) { return "scan failed (code $exit_code)"; }
			else { return NULL; }
		}
		else if ($code===AV_SCAN_ABORTED) { #scan failed because filetype is unknown (probably imune)
			wfDebug("$fname: unsupported file type $file (code $exit_code).\n");
			return NULL;
		}
		else if ($code===AV_NO_VIRUS) {
			wfDebug("$fname: file passed virus scan.\n");
			return false; #no virus found
		}
		else {
			$output= join("\n",$output);
			$output= trim($output);

			if (!$output) $output= true; #if there's no output, return true
			else if ($msg_pattern) {
				$groups= array();
				if (preg_match($msg_pattern,$output,$groups)) {
					if ($groups[1]) $output= $groups[1];
				}
			}

			wfDebug("$fname: FOUND VIRUS! scanner feedback: $output");
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
		$macbin = new MacBinary( $this->mUploadTempName );
		if( $macbin->isValid() ) {
			$dataFile = tempnam( wfTempDir(), "WikiMacBinary" );
			$dataHandle = fopen( $dataFile, 'wb' );

			wfDebug( "SpecialUpload::checkMacBinary: Extracting MacBinary data fork to $dataFile\n" );
			$macbin->extractData( $dataHandle );

			$this->mUploadTempName = $dataFile;
			$this->mUploadSize = $macbin->dataForkLength();

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
		if( $this->mRemoveTempFile && file_exists( $this->mUploadTempName ) ) {
			wfDebug( "SpecialUpload::cleanupTempFile: Removing temporary file $this->mUploadTempName\n" );
			unlink( $this->mUploadTempName );
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
		$img = Image::newFromName( $name );
		if( is_null( $img ) ) {
			// Uh... this shouldn't happen ;)
			// But if it does, fall through to previous behavior
			return false;
		}

		$error = '';
		if( $img->exists() ) {
			global $wgUser, $wgOut;
			if( $img->isLocal() ) {
				if( !$wgUser->isAllowed( 'reupload' ) ) {
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

}
?>
