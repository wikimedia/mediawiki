<?php

class UploadBase {
	var $mTempPath;
	var $mDesiredDestName, $mDestName, $mRemoveTempFile, $mSourceType;
	var $mTitle = false, $mTitleError = 0;
	var $mFilteredName, $mFinalExtension;
	
	const SUCCESS = 0;
	const OK = 0;
	const BEFORE_PROCESSING = 1;
	const LARGE_FILE_SERVER = 2;
	const EMPTY_FILE = 3;
	const MIN_LENGTH_PARTNAME = 4;
	const ILLEGAL_FILENAME = 5;
	const PROTECTED_PAGE = 6;
	const OVERWRITE_EXISTING_FILE = 7;
	const FILETYPE_MISSING = 8;
	const FILETYPE_BADTYPE = 9;
	const VERIFICATION_ERROR = 10;
	const UPLOAD_VERIFICATION_ERROR = 11;
	const UPLOAD_WARNING = 12;
	const INTERNAL_ERROR = 13;
	
	const SESSION_VERSION = 2;
	
	/*
	 * Returns true if uploads are enabled.
	 * Can be overriden by subclasses.
	 */
	static function isEnabled() {
		global $wgEnableUploads;
		return $wgEnableUploads;
	}
	/*
	 * Returns true if the user can use this upload module or else a string 
	 * identifying the missing permission.
	 * Can be overriden by subclasses.
	 */
	static function isAllowed( $user ) {
		if( !$user->isAllowed( 'upload' ) )
			return 'upload';
		return true;
	}
	
	static $uploadHandlers = array( 'Stash', 'Upload', 'Url' );
	static function createFromRequest( &$request, $type = null ) {
		$type = $type ? $type : $request->getVal( 'wpSourceType' );
		if( !$type ) 
			return null;
		$type = ucfirst($type);
		$className = 'UploadFrom'.$type;
		if( !in_array( $type, self::$uploadHandlers ) )
			return null;
		if( !call_user_func( array( $className, 'isEnabled' ) ) )
			return null;
		if( !call_user_func( array( $className, 'isValidRequest' ), $request ) )
			return null;
		
		$handler = new $className;
		$handler->initializeFromRequest( $request );
		return $handler;
	}
	
	static function isValidRequest( $request ) {
		return false;
	}
	
	function __construct() {}
	
	function initialize( $name, $tempPath, $fileSize, $removeTempFile = false ) {
		$this->mDesiredDestName = $name;
		$this->mTempPath = $tempPath;
		$this->mFileSize = $fileSize;
		$this->mRemoveTempFile = $removeTempFile;
	}

	/**
	 * Fetch the file. Usually a no-op
	 */
	function fetchFile() {
		return self::OK;
	}

	function verifyUpload() {
		global $wgUser;
		
		/**
		 * If there was no filename or a zero size given, give up quick.
		 */
		if( empty( $this->mFileSize ) ) 
			return array( 'status' => self::EMPTY_FILE );

		$nt = $this->getTitle();
		if( is_null( $nt ) ) {
			$result = array( 'status' => $this->mTitleError );
			if( $this->mTitleError == self::ILLEGAL_FILENAME )
				$resul['filtered'] = $this->mFilteredName;
			if ( $this->mTitleError == self::FILETYPE_BADTYPE )
				$result['finalExt'] = $this->mFinalExtension;
			return $result;
		}
		$this->mLocalFile = wfLocalFile( $nt );
		$this->mDestName = $this->mLocalFile->getName();

		/**
		 * In some cases we may forbid overwriting of existing files.
		 */
		$overwrite = $this->checkOverwrite( $this->mDestName );
		if( $overwrite !== true )
			return array( 'status' => self::OVERWRITE_EXISTING_FILE, 'overwrite' => $overwrite );
		
		/**
		 * Look at the contents of the file; if we can recognize the
		 * type but it's corrupt or data of the wrong type, we should
		 * probably not accept it.
		 */
		$verification = $this->verifyFile( $this->mTempPath );

		if( $verification !== true ) {
			if( !is_array( $verification ) ) 
				$verification = array( $verification );
			$verification['status'] = self::VERIFICATION_ERROR;
			return $verification;
		}
		
		$error = '';
		if( !wfRunHooks( 'UploadVerification',
				array( $this->mDestName, $this->mTempPath, &$error ) ) ) {
			return array( 'status' => self::UPLOAD_VERIFICATION_ERROR, 'error' => $error );
		}
		
		return self::OK;
	}
	
	/**
	 * Verifies that it's ok to include the uploaded file
	 *
	 * @param string $tmpfile the full path of the temporary file to verify
	 * @return mixed true of the file is verified, a string or array otherwise.
	 */
	protected function verifyFile( $tmpfile ) {
		$this->mFileProps = File::getPropsFromPath( $this->mTempPath, 
		$this->mFinalExtension );
		$this->checkMacBinary();
		
		#magically determine mime type
		$magic = MimeMagic::singleton();
		$mime = $magic->guessMimeType( $tmpfile, false );

		#check mime type, if desired
		global $wgVerifyMimeType;
		if ( $wgVerifyMimeType ) {

		  wfDebug ( "\n\nmime: <$mime> extension: <{$this->mFinalExtension}>\n\n");
			#check mime type against file extension
			if( !self::verifyExtension( $mime, $this->mFinalExtension ) ) {
				return 'uploadcorrupt';
			}

			#check mime type blacklist
			global $wgMimeTypeBlacklist;
			if( isset($wgMimeTypeBlacklist) && !is_null($wgMimeTypeBlacklist)
				&& $this->checkFileExtension( $mime, $wgMimeTypeBlacklist ) ) {
				return array( 'filetype-badmime', $mime );
			}
		}

		#check for htmlish code and javascript
		if( $this->detectScript ( $tmpfile, $mime, $this->mFinalExtension ) ) {
			return 'uploadscripted';
		}

		/**
		* Scan the uploaded file for viruses
		*/
		$virus = $this->detectVirus($tmpfile);
		if ( $virus ) {
			return array( 'uploadvirus', $virus );
		}

		wfDebug( __METHOD__.": all clear; passing.\n" );
		return true;
	}
	
	function verifyPermissions( $user ) {
		/**
		 * If the image is protected, non-sysop users won't be able
		 * to modify it by uploading a new revision.
		 */
		$nt = $this->getTitle();
		if( is_null( $nt ) )
			return true;
		$permErrors = $nt->getUserPermissionsErrors( 'edit', $user );
		$permErrorsUpload = $nt->getUserPermissionsErrors( 'upload', $user );
		$permErrorsCreate = ( $nt->exists() ? array() : $nt->getUserPermissionsErrors( 'create', $user ) );
		if( $permErrors || $permErrorsUpload || $permErrorsCreate ) {
			$permErrors = array_merge( $permErrors, wfArrayDiff2( $permErrorsUpload, $permErrors ) );
			$permErrors = array_merge( $permErrors, wfArrayDiff2( $permErrorsCreate, $permErrors ) );
			return $permErrors;
		}
		return true;
	}	
	
	function checkWarnings() {
		$warning = array();

		$filename = $this->mLocalFile->getName();
		$n = strrpos( $filename, '.' );		
		$partname = $n ? substr( $filename, 0, $n ) : $filename;

		global $wgCapitalLinks;
		if( $this->mDesiredDestName != $filename )
			$warning['badfilename'] = $filename;

		global $wgCheckFileExtensions, $wgFileExtensions;
		if ( $wgCheckFileExtensions ) {
			if ( !$this->checkFileExtension( $this->mFinalExtension, $wgFileExtensions ) )
				$warning['filetype-unwanted-type'] = $this->mFinalExtension; 
		}
		
		global $wgUploadSizeWarning;
		if ( $wgUploadSizeWarning && ( $this->mFileSize > $wgUploadSizeWarning ) )
			$warning['large-file'] = $wgUploadSizeWarning;

		if ( $this->mFileSize == 0 )
			$warning['emptyfile'] = true;
		
		$exists = self::getExistsWarning( $this->mLocalFile );
		if( $exists !== false )
			$warning['exists'] = $exists;
		
		
		if( $exists !== false && $exists[0] != 'thumb' 
				&& self::isThumbName( $this->mLocalFile->getName() ) )
			$warning['file-thumbnail-no'] = substr( $filename , 0, 
				strpos( $nt->getText() , '-' ) +1 );
		
		$hash = File::sha1Base36( $this->mTempPath );
		$dupes = RepoGroup::singleton()->findBySha1( $hash );
		if( $dupes )
			$warning['duplicate'] = $dupes;
			
		$filenamePrefixBlacklist = self::getFilenamePrefixBlacklist();
		foreach( $filenamePrefixBlacklist as $prefix ) {
			if ( substr( $partname, 0, strlen( $prefix ) ) == $prefix ) {
				$warning['filename-bad-prefix'] = $prefix;
				break;
			}
		}
		
		# If the file existed before and was deleted, warn the user of this
		# Don't bother doing so if the file exists now, however
		if( $this->mLocalFile->wasDeleted() && !$this->mLocalFile->exists() )
			$warning['filewasdeleted'] = $this->mLocalFile->getTitle();
			
		return $warning;
	}

	function performUpload( $comment, $pageText, $watch, $user ) {
		$status = $this->mLocalFile->upload( $this->mTempPath, $comment, $pageText,
			File::DELETE_SOURCE, $this->mFileProps, false, $user );
		
		if( $status->isGood() && $watch ) {
			$user->addWatch( $this->mLocalFile->getTitle() );
		}
		
		if( $status->isGood() )
			wfRunHooks( 'UploadComplete', array( &$this ) );
		
		return $status;
	}

	/**
	 * Returns a title or null
	 */
	function getTitle() {
		if ( $this->mTitle !== false )
			return $this->mTitle;
		
		/**
		 * Chop off any directories in the given filename. Then
		 * filter out illegal characters, and try to make a legible name
		 * out of it. We'll strip some silently that Title would die on.
		 */

		$basename = $this->mDesiredDestName;

		$this->mFilteredName = wfStripIllegalFilenameChars( $basename );

		/**
		 * We'll want to blacklist against *any* 'extension', and use
		 * only the final one for the whitelist.
		 */
		list( $partname, $ext ) = $this->splitExtensions( $this->mFilteredName );

		if( count( $ext ) ) {
			$this->mFinalExtension = $ext[count( $ext ) - 1];
		} else {
			$this->mFinalExtension = '';
		}

		/* Don't allow users to override the blacklist (check file extension) */
		global $wgCheckFileExtensions, $wgStrictFileExtensions;
		global $wgFileExtensions, $wgFileBlacklist;
		if ( $this->mFinalExtension == '' ) {
			$this->mTitleError = self::FILETYPE_MISSING;
			return $this->mTitle = null;
		} elseif ( $this->checkFileExtensionList( $ext, $wgFileBlacklist ) ||
				( $wgCheckFileExtensions && $wgStrictFileExtensions &&
					!$this->checkFileExtension( $this->mFinalExtension, $wgFileExtensions ) ) ) {
			$this->mTitleError = self::FILETYPE_BADTYPE;
			return $this->mTitle = null;
		}

		# If there was more than one "extension", reassemble the base
		# filename to prevent bogus complaints about length
		if( count( $ext ) > 1 ) {
			for( $i = 0; $i < count( $ext ) - 1; $i++ )
				$partname .= '.' . $ext[$i];
		}

		if( strlen( $partname ) < 1 ) {
			$this->mTitleError =  self::MIN_LENGTH_PARTNAME;
			return $this->mTitle = null;
		}
		
		$nt = Title::makeTitleSafe( NS_IMAGE, $this->mFilteredName );
		if( is_null( $nt ) ) {
			$this->mTitleError = self::ILLEGAL_FILENAME;
			return $this->mTitle = null;
		}
		return $this->mTitle = $nt;
	}
	
	function getLocalFile() {
		if( is_null( $this->mLocalFile ) ) {
			$nt = $this->getTitle();
			$this->mLocalFile = is_null( $nt ) ? null : wfLocalFile( $nt );
		}	
		return $this->mLocalFile;
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
		return $status;
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
		$status = $this->saveTempUploadedFile( $this->mDestName, $this->mTempPath );

		if( !$status->isGood() ) {
			# Couldn't save the file.
			return false;
		}

		return array(
			'mTempPath'       => $status->value,
			'mFileSize'       => $this->mFileSize,
			'mFileProps'      => $this->mFileProps,
			'version'         => self::SESSION_VERSION,
	   	);
	}
	
	/**
	 * Remove a temporarily kept file stashed by saveTempUploadedFile().
	 * @return success
	 */
	function unsaveUploadedFile() {
		$repo = RepoGroup::singleton()->getLocalRepo();
		$success = $repo->freeTemp( $this->mTempPath );
		return $success;
	}
	
	/**
	 * If we've modified the upload file we need to manually remove it
	 * on exit to clean up.
	 * @access private
	 */
	function cleanupTempFile() {
		if ( $this->mRemoveTempFile && file_exists( $this->mTempPath ) ) {
			wfDebug( __METHOD__.": Removing temporary file {$this->mTempPath}\n" );
			unlink( $this->mTempPath );
		}
	}
	
	function getTempPath() {
		return $this->mTempPath;
	}
	
	
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
	 * Checks if the mime type of the uploaded file matches the file extension.
	 *
	 * @param string $mime the mime type of the uploaded file
	 * @param string $extension The filename extension that the file is to be served with
	 * @return bool
	 */
	public static function verifyExtension( $mime, $extension ) {
		$magic = MimeMagic::singleton();

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
		#For binary field, just check the first K.

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
			$wgOut->wrapWikiMsg( '<div class="error">$1</div>', array( 'virus-badscanner', $wgAntivirus ) );
			return wfMsg('virus-unknownscanner') . " $wgAntivirus";
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
				return wfMsg('virus-scanfailed', array( $exitCode ) );
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
	 * Check if there's an overwrite conflict and, if so, if restrictions
	 * forbid this user from performing the upload.
	 *
	 * @return mixed true on success, WikiError on failure
	 * @access private
	 */
	function checkOverwrite() {
		global $wgUser;
		// First check whether the local file can be overwritten
		if( $this->mLocalFile->exists() )
			if( !self::userCanReUpload( $wgUser, $this->mLocalFile ) )
				return 'fileexists-forbidden';
		
		// Check shared conflicts
		$file = wfFindFile( $this->mLocalFile->getName() );
		if ( $file && ( !$wgUser->isAllowed( 'reupload' ) ||
				!$wgUser->isAllowed( 'reupload-shared' ) ) )
			return 'fileexists-shared-forbidden';
		
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
		if( is_string( $img ) )
			$img = wfLocalFile( $img );
		if ( !( $img instanceof LocalFile ) )
			return false;

		return $user->getId() == $img->getUser( 'id' );
	}
	
	public static function getExistsWarning( $file ) {
		if( $file->exists() )
			return array( 'exists', $file );
		
		if( $file->getTitle()->getArticleID() )
			return array( 'page-exists', $file );
		
		if( strpos( $file->getName(), '.' ) == false ) {
			$partname = $file->getName();
			$rawExtension = '';
		} else {
			$n = strrpos( $file->getName(), '.' );
			$rawExtension = substr( $file->getName(), $n + 1 );
			$partname = substr( $file->getName(), 0, $n );
		}
		
		if ( $rawExtension != $file->getExtension() ) {
			// We're not using the normalized form of the extension.
			// Normal form is lowercase, using most common of alternate
			// extensions (eg 'jpg' rather than 'JPEG').
			//
			// Check for another file using the normalized form...
			$nt_lc = Title::makeTitle( NS_IMAGE, $partname . '.' . $file->getExtension() );
			$file_lc = wfLocalFile( $nt_lc );
			
			if( $file_lc->exists() )
				return array( 'exists-normalized', $file_lc );
		} 
		
		if ( self::isThumbName( $file->getName() ) ) {
			# Check for filenames like 50px- or 180px-, these are mostly thumbnails
			$nt_thb = Title::newFromText( substr( $partname , strpos( $partname , '-' ) +1 ) . '.' . $rawExtension );
			$file_thb = wfLocalFile( $nt_thb );
			if( $file_thb->exists() )
				return array( 'thumb', $file_thb );
		}
		
		return false;
	}
	
	public static function isThumbName( $filename ) {
		$n = strrpos( $filename, '.' );
		$partname = $n ? substr( $filename, 0, $n ) : $filename;
		return ( 
					substr( $partname , 3, 3 ) == 'px-' || 
					substr( $partname , 2, 3 ) == 'px-' 
				) && 
				ereg( "[0-9]{2}" , substr( $partname , 0, 2) );	
	}
	
	/**
	 * Get a list of blacklisted filename prefixes from [[MediaWiki:filename-prefix-blacklist]]
	 *
	 * @return array list of prefixes
	 */
	public static function getFilenamePrefixBlacklist() {
		$blacklist = array();
		$message = wfMsgForContent( 'filename-prefix-blacklist' );
		if( $message && !( wfEmptyMsg( 'filename-prefix-blacklist', $message ) || $message == '-' ) ) {
			$lines = explode( "\n", $message );
			foreach( $lines as $line ) {
				// Remove comment lines
				$comment = substr( trim( $line ), 0, 1 );
				if ( $comment == '#' || $comment == '' ) {
					continue;
				}
				// Remove additional comments after a prefix
				$comment = strpos( $line, '#' );
				if ( $comment > 0 ) {
					$line = substr( $line, 0, $comment-1 );
				}
				$blacklist[] = trim( $line );
			}
		}
		return $blacklist;
	}
	
	
}
