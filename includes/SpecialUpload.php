<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
require_once( 'Image.php' );

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
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class UploadForm {
	/**#@+
	 * @access private
	 */
	var $mUploadAffirm, $mUploadFile, $mUploadDescription, $mIgnoreWarning;
	var $mUploadSaveName, $mUploadTempName, $mUploadSize, $mUploadOldVersion;
	var $mUploadCopyStatus, $mUploadSource, $mReUpload, $mAction, $mUpload;
	var $mOname, $mSessionKey;
	/**#@- */

	/**
	 * Constructor : initialise object
	 * Get data POSTed through the form and assign them to the object
	 * @param $request Data posted.
	 */
	function UploadForm( &$request ) {
		if( !$request->wasPosted() ) {
			# GET requests just give the main form; no data.
			return;
		}
		
		$this->mUploadAffirm      = $request->getCheck( 'wpUploadAffirm' );
		$this->mIgnoreWarning     = $request->getCheck( 'wpIgnoreWarning');
		$this->mReUpload          = $request->getCheck( 'wpReUpload' );
		$this->mUpload            = $request->getCheck( 'wpUpload' );
		
		$this->mUploadDescription = $request->getText( 'wpUploadDescription' );
		$this->mUploadCopyStatus  = $request->getText( 'wpUploadCopyStatus' );
		$this->mUploadSource      = $request->getText( 'wpUploadSource');
		
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
		} else {
			/**
			 *Check for a newly uploaded file.
			 */
			$this->mUploadTempName = $request->getFileTempName( 'wpUploadFile' );
			$this->mUploadSize     = $request->getFileSize( 'wpUploadFile' );
			$this->mOname          = $request->getFileName( 'wpUploadFile' );
			$this->mSessionKey     = false;
		}
	}

	/**
	 * Start doing stuff
	 * @access public
	 */
	function execute() {
		global $wgUser, $wgOut;
		global $wgDisableUploads;

		/** Show an error message if file upload is disabled */ 
		if( $wgDisableUploads ) {
			$wgOut->addWikiText( wfMsg( 'uploaddisabled' ) );
			return;
		}
		
		/** Various rights checks */
		if( ( $wgUser->getID() == 0 )
			 OR $wgUser->isBlocked() ) {
			$wgOut->errorpage( 'uploadnologin', 'uploadnologintext' );
			return;
		}
		if( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
		
		if( $this->mReUpload ) {
			$this->unsaveUploadedFile();
			$this->mainUploadForm();
		} else if ( 'submit' == $this->mAction || $this->mUpload ) {
			$this->processUpload();
		} else {
			$this->mainUploadForm();
		}
	}

	/* -------------------------------------------------------------- */

	/**
	 * Really do the upload
	 * Checks are made in SpecialUpload::execute()
	 * @access private
	 */
	function processUpload() {
		global $wgUser, $wgOut, $wgLang, $wgContLang;
		global $wgUploadDirectory;
		global $wgUseCopyrightUpload, $wgCheckCopyrightUpload;

		/**
		 * If there was no filename or a zero size given, give up quick.
		 */
		if( ( trim( $this->mOname ) == '' ) || empty( $this->mUploadSize ) ) {
			return $this->mainUploadForm('<li>'.wfMsg( 'emptyfile' ).'</li>');
		}
		
		/**
		 * When using detailed copyright, if user filled field, assume he
		 * confirmed the upload
		 */
		if ( $wgUseCopyrightUpload ) {
			$this->mUploadAffirm = true;
			if( $wgCheckCopyrightUpload && 
			    ( trim( $this->mUploadCopyStatus ) == '' ||
			      trim( $this->mUploadSource     ) == '' ) ) {
				$this->mUploadAffirm = false;
			}
		}

		/** User need to confirm his upload */
		if( !$this->mUploadAffirm ) {
			$this->mainUploadForm( wfMsg( 'noaffirmation' ) );
			return;
		}

		# Chop off any directories in the given filename
		$basename = basename( $this->mOname );

		if( preg_match( '/^(.*)\.([^.]*)$/', $basename, $matches ) ) {
			$partname = $matches[1];
			$ext      = $matches[2];
		} else {
		    $partname = $basename;
		    $ext = '';
		}

		if ( strlen( $partname ) < 3 ) {
			$this->mainUploadForm( wfMsg( 'minlength' ) );
			return;
		}

		/**
		 * Filter out illegal characters, and try to make a legible name
		 * out of it. We'll strip some silently that Title would die on.
		 */
		$filtered = preg_replace ( "/[^".Title::legalChars()."]|:/", '-', $basename );
		$nt = Title::newFromText( $filtered );
		if( is_null( $nt ) ) {
			return $this->uploadError( wfMsg( 'illegalfilename', htmlspecialchars( $filtered ) ) );
		}
		$nt->setNamespace( NS_IMAGE );
		$this->mUploadSaveName = $nt->getDBkey();
		
		/**
		 * If the image is protected, non-sysop users won't be able
		 * to modify it by uploading a new revision.
		 */
		if( !$nt->userCanEdit() ) {
			return $this->uploadError( wfMsg( 'protectedpage' ) );
		}
		
		/* Don't allow users to override the blacklist */
		global $wgStrictFileExtensions;
		global $wgFileExtensions, $wgFileBlacklist;
		if( $this->checkFileExtension( $ext, $wgFileBlacklist ) ||
			($wgStrictFileExtensions && !$this->checkFileExtension( $ext, $wgFileExtensions ) ) ) {
			return $this->uploadError( wfMsg( 'badfiletype', htmlspecialchars( $ext ) ) );
		}
		
		/**
		 * Look at the contents of the file; if we can recognize the
		 * type but it's corrupt or data of the wrong type, we should
		 * probably not accept it.
		 */
		if( !$this->verify( $this->mUploadTempName, $ext ) ) {
			return $this->uploadError( wfMsg( 'uploadcorrupt' ) );
		}
		
		/**
		 * Check for non-fatal conditions
		 */
		if ( ! $this->mIgnoreWarning ) {
			$warning = '';
			if( $this->mUploadSaveName != ucfirst( $filtered ) ) {
				$warning .=  '<li>'.wfMsg( 'badfilename', htmlspecialchars( $this->mUploadSaveName ) ).'</li>';
			}
	
			global $wgCheckFileExtensions;
			if ( $wgCheckFileExtensions ) {
				if ( ! $this->checkFileExtension( $ext, $wgFileExtensions ) ) {
					$warning .= '<li>'.wfMsg( 'badfiletype', htmlspecialchars( $ext ) ).'</li>';
				}
			}
	
			global $wgUploadSizeWarning;
			if ( $wgUploadSizeWarning && ( $this->mUploadSize > $wgUploadSizeWarning ) ) {
				$warning .= '<li>'.wfMsg( 'largefile' ).'</li>';
			}
			if ( $this->mUploadSize == 0 ) {
				$warning .= '<li>'.wfMsg( 'emptyfile' ).'</li>';
			}
			
			if( $nt->getArticleID() ) {
				global $wgUser;
				$sk = $wgUser->getSkin();
				$dlink = $sk->makeKnownLinkObj( $nt );
				$warning .= '<li>'.wfMsg( 'fileexists', $dlink ).'</li>';
			}
			
			if( $warning != '' ) {
				/**
				 * Stash the file in a temporary location; the user can choose
				 * to let it through and we'll complete the upload then.
				 */
				return $this->uploadWarning($warning);
			}
		}
		
		/**
		 * Try actually saving the thing...
		 * It will show an error form on failure.
		 */
		if( $this->saveUploadedFile( $this->mUploadSaveName,
		                             $this->mUploadTempName,
		                             !empty( $this->mSessionKey ) ) ) {
			/**
			 * Update the upload log and create the description page
			 * if it's a new file.
			 */
			wfRecordUpload( $this->mUploadSaveName,
			                $this->mUploadOldVersion,
			                $this->mUploadSize, 
			                $this->mUploadDescription,
			                $this->mUploadCopyStatus,
			                $this->mUploadSource );
			$this->showSuccess();
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
		global $wgUploadDirectory, $wgOut;

		$dest = wfImageDir( $saveName );
		$archive = wfImageArchiveDir( $saveName );
		$this->mSavedFile = "{$dest}/{$saveName}";

		if( is_file( $this->mSavedFile ) ) {
			$this->mUploadOldVersion = gmdate( 'YmdHis' ) . "!{$saveName}";

			if( !rename( $this->mSavedFile, "${archive}/{$this->mUploadOldVersion}" ) ) { 
				$wgOut->fileRenameError( $this->mSavedFile,
				  "${archive}/{$this->mUploadOldVersion}" );
				return false;
			}
		} else {
			$this->mUploadOldVersion = '';
		}
		
		if( $useRename ) {
			if( !rename( $tempName, $this->mSavedFile ) ) {
				$wgOut->fileCopyError( $tempName, $this->mSavedFile );
				return false;
			}
		} else {
			if( !move_uploaded_file( $tempName, $this->mSavedFile ) ) {
				$wgOut->fileCopyError( $tempName, $this->mSavedFile );
				return false;
			}
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
		$stash = $archive . '/' . gmdate( "YmdHis" ) . '!' . $saveName;

		if ( !move_uploaded_file( $tempName, $stash ) ) {
			$wgOut->fileCopyError( $tempName, $stash );
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
	 */
	function unsaveUploadedFile() {
		if ( ! @unlink( $this->mUploadTempName ) ) {
			$wgOut->fileDeleteError( $this->mUploadTempName );
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
		$ilink = $sk->makeMediaLink( $this->mUploadSaveName, Image::wfImageUrl( $this->mUploadSaveName ) );
		$dname = $wgContLang->getNsText( Namespace::getImage() ) . ':'.$this->mUploadSaveName;
		$dlink = $sk->makeKnownLink( $dname, $dname );

		$wgOut->addHTML( '<h2>' . wfMsg( 'successfulupload' ) . "</h2>\n" );
		$text = wfMsg( 'fileuploaded', $ilink, $dlink );
		$wgOut->addHTML( '<p>'.$text."\n" );
		$wgOut->returnToMain( false );
	}

	/**
	 * @param string $error as HTML
	 * @access private
	 */
	function uploadError( $error ) {
		global $wgOut;
		$sub = wfMsg( 'uploadwarning' );
		$wgOut->addHTML( "<h2>{$sub}</h2>\n" );
		$wgOut->addHTML( "<h4 class='error'>{$error}</h4>\n" );
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
		global $wgOut, $wgUser, $wgLang, $wgUploadDirectory, $wgRequest;
		global $wgUseCopyrightUpload;

		$this->mSessionKey = $this->stashSession();
		if( !$this->mSessionKey ) {
			# Couldn't save file; an error has been displayed so let's go.
			return;
		}

		$sub = wfMsg( 'uploadwarning' );
		$wgOut->addHTML( "<h2>{$sub}</h2>\n" );
		$wgOut->addHTML( "<ul class='warning'>{$warning}</ul><br/>\n" );

		$save = wfMsg( 'savefile' );
		$reupload = wfMsg( 'reupload' );
		$iw = wfMsg( 'ignorewarning' );
		$reup = wfMsg( 'reuploaddesc' );
		$titleObj = Title::makeTitle( NS_SPECIAL, 'Upload' );
		$action = $titleObj->escapeLocalURL( 'action=submit' );

		if ( $wgUseCopyrightUpload )
		{
			$copyright =  "
	<input type='hidden' name=\"wpUploadCopyStatus\" value=\"" . htmlspecialchars( $this->mUploadCopyStatus ) . "\" />
	<input type='hidden' name=\"wpUploadSource\" value=\"" . htmlspecialchars( $this->mUploadSource ) . "\" />
	";
		} else {
			$copyright = "";
		}

		$wgOut->addHTML( "
	<form id=\"uploadwarning\" method=\"post\" enctype=\"multipart/form-data\"
	action=\"{$action}\">
	<input type=hidden name=\"wpUploadAffirm\" value=\"1\" />
	<input type=hidden name=\"wpIgnoreWarning\" value=\"1\" />
	<input type=hidden name=\"wpSessionKey\" value=\"" . htmlspecialchars( $this->mSessionKey ) . "\" />
	{$copyright}
	<table border='0'><tr>
	<tr><td align='right'>
	<input tabindex='2' type='submit' name=\"wpUpload\" value=\"{$save}\" />
	</td><td align='left'>{$iw}</td></tr>
	<tr><td align='right'>
	<input tabindex='2' type='submit' name=\"wpReUpload\" value=\"{$reupload}\" />
	</td><td align='left'>{$reup}</td></tr></table></form>\n" );
	}

	/**
	 * Displays the main upload form, optionally with a highlighted
	 * error message up at the top.
	 *
	 * @param string $msg as HTML
	 * @access private
	 */
	function mainUploadForm( $msg='' ) {
		global $wgOut, $wgUser, $wgLang, $wgUploadDirectory, $wgRequest;
		global $wgUseCopyrightUpload;

		if ( '' != $msg ) {
			$sub = wfMsg( 'uploaderror' );
			$wgOut->addHTML( "<h2>{$sub}</h2>\n" .
			  "<h4 class='error'>{$msg}</h4>\n" );
		} else {
			$sub = wfMsg( 'uploadfile' );
			$wgOut->addHTML( "<h2>{$sub}</h2>\n" );
		}
		$wgOut->addWikiText( wfMsg( 'uploadtext' ) );
		$sk = $wgUser->getSkin();

		$fn = wfMsg( 'filename' );
		$fd = wfMsg( 'filedesc' );
		$ulb = wfMsg( 'uploadbtn' );

		$clink = $sk->makeKnownLink( wfMsgForContent( 'copyrightpage' ),
		  wfMsg( 'copyrightpagename' ) );
		$ca = wfMsg( 'affirmation', $clink );
		$iw = wfMsg( 'ignorewarning' );

		$titleObj = Title::makeTitle( NS_SPECIAL, 'Upload' );
		$action = $titleObj->escapeLocalURL();

		$source = "
	<td align='right'>
	<input tabindex='3' type='checkbox' name=\"wpUploadAffirm\" value=\"1\" id=\"wpUploadAffirm\" />
	</td><td align='left'><label for=\"wpUploadAffirm\">{$ca}</label></td>
	" ;
		if ( $wgUseCopyrightUpload )
		  {
			$source = "
	<td align='right' nowrap='nowrap'>" . wfMsg ( 'filestatus' ) . ":</td>
	<td><input tabindex='3' type='text' name=\"wpUploadCopyStatus\" value=\"" .
	htmlspecialchars($this->mUploadCopyStatus). "\" size='40' /></td>
	</tr><tr>
	<td align='right'>". wfMsg ( 'filesource' ) . ":</td>
	<td><input tabindex='4' type='text' name=\"wpUploadSource\" value=\"" .
	htmlspecialchars($this->mUploadSource). "\" size='40' /></td>
	" ;
		  }

		$wgOut->addHTML( "
	<form id=\"upload\" method=\"post\" enctype=\"multipart/form-data\"
	action=\"{$action}\">
	<table border='0'><tr>
	<td align='right'>{$fn}:</td><td align='left'>
	<input tabindex='1' type='file' name=\"wpUploadFile\" size='40' />
	</td></tr><tr>
	<td align='right'>{$fd}:</td><td align='left'>
	<input tabindex='2' type='text' name=\"wpUploadDescription\" value=\""
	  . htmlspecialchars( $this->mUploadDescription ) . "\" size='40' />
	</td></tr><tr>
	{$source}
	</tr>
	<tr><td></td><td align='left'>
	<input tabindex='5' type='submit' name=\"wpUpload\" value=\"{$ulb}\" />
	</td></tr></table></form>\n" );
	}
	
	/* -------------------------------------------------------------- */

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
	 * Returns false if the file is of a known type but can't be recognized,
	 * indicating a corrupt file.
	 * Returns true otherwise; unknown file types are not checked if given
	 * with an unrecognized extension.
	 *
	 * @param string $tmpfile Pathname to the temporary upload file
	 * @param string $extension The filename extension that the file is to be served with
	 * @return bool
	 */
	function verify( $tmpfile, $extension ) {
		if( $this->triggersIEbug( $tmpfile ) ) {
			return false;
		}
		
		$fname = 'SpecialUpload::verify';
		$mergeExtensions = array(
			'jpg' => 'jpeg',
			'tif' => 'tiff' );
		$extensionTypes = array(
			# See http://www.php.net/getimagesize
			1 => 'gif',
			2 => 'jpeg',
			3 => 'png',
			4 => 'swf',
			5 => 'psd',
			6 => 'bmp',
			7 => 'tiff',
			8 => 'tiff',
			9 => 'jpc',
			10 => 'jp2',
			11 => 'jpx',
			12 => 'jb2',
			13 => 'swc',
			14 => 'iff',
			15 => 'wbmp',
			16 => 'xbm' );
		
		$extension = strtolower( $extension );
		if( isset( $mergeExtensions[$extension] ) ) {
			$extension = $mergeExtensions[$extension];
		}
		wfDebug( "$fname: Testing file '$tmpfile' with given extension '$extension'\n" );
		
		if( !in_array( $extension, $extensionTypes ) ) {
			# Not a recognized image type. We don't know how to verify these.
			# They're allowed by policy or they wouldn't get this far, so we'll
			# let them slide for now.
			wfDebug( "$fname: Unknown extension; passing.\n" );
			return true;
		}
		
		$data = @getimagesize( $tmpfile );
		if( false === $data ) {
			# Didn't recognize the image type.
			# Either the image is corrupt or someone's slipping us some
			# bogus data such as HTML+JavaScript trying to take advantage
			# of an Internet Explorer security flaw.
			wfDebug( "$fname: getimagesize() doesn't recognize the file; rejecting.\n" );
			return false;
		}
		
		$imageType = $data[2];
		if( !isset( $extensionTypes[$imageType] ) ) {
			# Now we're kind of confused. Perhaps new image types added
			# to PHP's support that we don't know about.
			# We'll let these slide for now.
			wfDebug( "$fname: getimagesize() knows the file, but we don't recognize the type; passing.\n" );
			return true;
		}
		
		$ext = strtolower( $extension );
		if( $extension != $extensionTypes[$imageType] ) {
			# The given filename extension doesn't match the
			# file type. Probably just a mistake, but it's a stupid
			# one and we shouldn't let it pass. KILL THEM!
			wfDebug( "$fname: file extension does not match recognized type; rejecting.\n" );
			return false;
		}
		
		wfDebug( "$fname: all clear; passing.\n" );
		return true;
	}
	
	/**
	 * Internet Explorer for Windows performs some really stupid file type
	 * autodetection which can cause it to interpret valid image files as HTML
	 * and potentially execute JavaScript, creating a cross-site scripting
	 * attack vectors.
	 *
	 * Returns true if IE is likely to mistake the given file for HTML.
	 *
	 * @param string $filename
	 * @return bool
	 */
	function triggersIEbug( $filename ) {
		$file = fopen( $filename, 'rb' );
		$chunk = strtolower( fread( $file, 200 ) );
		fclose( $file );
		
		$tags = array( '<html', '<head', '<body', '<script' );
		foreach( $tags as $tag ) {
			if( false !== strpos( $chunk, $tag ) ) {
				return true;
			}
		}
		return false;
	}
}
?>
