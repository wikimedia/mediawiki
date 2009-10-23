<?php
/**
 * @file 
 * @ingroup upload
 * 
 * UploadBase and subclasses are the backend of MediaWiki's file uploads.
 * The frontends are formed by ApiUpload and SpecialUpload.
 * 
 * See also includes/docs/upload.txt
 * 
 * @author Brion Vibber
 * @author Bryan Tong Minh
 * @author Michael Dale
 */
 
abstract class UploadBase {
	protected $mTempPath;
	protected $mDesiredDestName, $mDestName, $mRemoveTempFile, $mSourceType;
	protected $mTitle = false, $mTitleError = 0;
	protected $mFilteredName, $mFinalExtension;
	protected $mLocalFile;

	const SUCCESS = 0;
	const OK = 0;
	const EMPTY_FILE = 3;
	const MIN_LENGTH_PARTNAME = 4;
	const ILLEGAL_FILENAME = 5;
	const OVERWRITE_EXISTING_FILE = 7;
	const FILETYPE_MISSING = 8;
	const FILETYPE_BADTYPE = 9;
	const VERIFICATION_ERROR = 10;
	const UPLOAD_VERIFICATION_ERROR = 11;
	const HOOK_ABORTED = 11;
	const MIN_LENGHT_PARTNAME = 14;

	const SESSION_VERSION = 2;

	/**
	 * Returns true if uploads are enabled.
	 * Can be override by subclasses.
	 */
	public static function isEnabled() {
		global $wgEnableUploads;
		if ( !$wgEnableUploads )
			return false;

		# Check php's file_uploads setting
		if( !wfIniGetBool( 'file_uploads' ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Returns true if the user can use this upload module or else a string
	 * identifying the missing permission.
	 * Can be overriden by subclasses.
	 */
	public static function isAllowed( $user ) {
		if( !$user->isAllowed( 'upload' ) )
			return 'upload';
		return true;
	}

	// Upload handlers. Should probably just be a global
	static $uploadHandlers = array( 'Stash', 'File', 'Url' );

	/**
	 * Create a form of UploadBase depending on wpSourceType and initializes it
	 */
	public static function createFromRequest( &$request, $type = null ) {
		$type = $type ? $type : $request->getVal( 'wpSourceType', 'File' );

		if( !$type )
			return null;

		// Get the upload class
		$type = ucfirst( $type );
		
		// Give hooks the chance to handle this request
		$className = null;
		wfRunHooks( 'UploadCreateFromRequest', array( $type, &$className ) );
		if ( is_null( $className ) ) {
			$className = 'UploadFrom' . $type;
			wfDebug( __METHOD__ . ": class name: $className\n" );
			if( !in_array( $type, self::$uploadHandlers ) )
				return null;
		}

		// Check whether this upload class is enabled
		if( !call_user_func( array( $className, 'isEnabled' ) ) )
			return null;

		// Check whether the request is valid
		if( !call_user_func( array( $className, 'isValidRequest' ), $request ) )
			return null;

		$handler = new $className;

		$handler->initializeFromRequest( $request );
		return $handler;
	}

	/**
	 * Check whether a request if valid for this handler
	 */
	public static function isValidRequest( $request ) {
		return false;
	}

	public function __construct() {}

	/**
	 * Do the real variable initialization
	 */
	public function initialize( $name, $tempPath, $fileSize, $removeTempFile = false ) {
		$this->mDesiredDestName = $name;
		$this->mTempPath = $tempPath;
		$this->mFileSize = $fileSize;
		$this->mRemoveTempFile = $removeTempFile;
	}
	
	/**
	 * Initialize from a WebRequest. Override this in a subclass.
	 */
	public abstract function initializeFromRequest( &$request );

	/**
	 * Fetch the file. Usually a no-op
	 */
	public function fetchFile() {
		return Status::newGood();
	}

	/**
	 * Return the file size
	 */
	public function isEmptyFile(){
		return empty( $this->mFileSize );
	}
	
	/**
     * getRealPath
     * @param string $srcPath the source path
     * @returns the real path if it was a virtual url
     */
	function getRealPath( $srcPath ){
		$repo = RepoGroup::singleton()->getLocalRepo();
		if ( $repo->isVirtualUrl( $srcPath ) ) {
			return $repo->resolveVirtualUrl( $srcPath );
		}
		return $srcPath;
	}

	/**
	 * Verify whether the upload is sane.
	 * Returns self::OK or else an array with error information
	 */
	public function verifyUpload() {
		/**
		 * If there was no filename or a zero size given, give up quick.
		 */
		if( $this->isEmptyFile() )
			return array( 'status' => self::EMPTY_FILE );
		
		/**
		 * Look at the contents of the file; if we can recognize the
		 * type but it's corrupt or data of the wrong type, we should
		 * probably not accept it.
		 */
		$verification = $this->verifyFile();
		if( $verification !== true ) {
			if( !is_array( $verification ) )
				$verification = array( $verification );
			return array( 'status' => self::VERIFICATION_ERROR,
					'details' => $verification );

		}
		
		$nt = $this->getTitle();
		if( is_null( $nt ) ) {
			$result = array( 'status' => $this->mTitleError );
			if( $this->mTitleError == self::ILLEGAL_FILENAME )
				$result['filtered'] = $this->mFilteredName;
			if ( $this->mTitleError == self::FILETYPE_BADTYPE )
				$result['finalExt'] = $this->mFinalExtension;
			return $result;
		}
		$this->mDestName = $this->getLocalFile()->getName();

		/**
		 * In some cases we may forbid overwriting of existing files.
		 */
		$overwrite = $this->checkOverwrite();
		if( $overwrite !== true )
			return array( 'status' => self::OVERWRITE_EXISTING_FILE, 'overwrite' => $overwrite );

		$error = '';
		if( !wfRunHooks( 'UploadVerification',
				array( $this->mDestName, $this->mTempPath, &$error ) ) ) {
			// This status needs another name...
			return array( 'status' => self::HOOK_ABORTED, 'error' => $error );
		}

		return array( 'status' => self::OK );
	}

	/**
	 * Verifies that it's ok to include the uploaded file
	 *
	 * FIXME: this function seems to intermixes tmpfile and $this->mTempPath .. no idea why this is
	 *
	 * @param string $tmpfile the full path of the temporary file to verify
	 * @return mixed true of the file is verified, a string or array otherwise.
	 */
	protected function verifyFile() {
		$this->mFileProps = File::getPropsFromPath( $this->mTempPath, $this->mFinalExtension );
		$this->checkMacBinary();

		#magically determine mime type
		$magic = MimeMagic::singleton();
		$mime = $magic->guessMimeType( $this->mTempPath, false );

		#check mime type, if desired
		global $wgVerifyMimeType;
		if ( $wgVerifyMimeType ) {		
			global $wgMimeTypeBlacklist;
			if ( $this->checkFileExtension( $mime, $wgMimeTypeBlacklist ) )
				return array( 'filetype-badmime', $mime );

			# Check IE type
			$fp = fopen( $this->mTempPath, 'rb' );
			$chunk = fread( $fp, 256 );
			fclose( $fp );
			$extMime = $magic->guessTypesForExtension( $this->mFinalExtension );
			$ieTypes = $magic->getIEMimeTypes( $this->mTempPath, $chunk, $extMime );
			foreach ( $ieTypes as $ieType ) {
				if ( $this->checkFileExtension( $ieType, $wgMimeTypeBlacklist ) ) {
					return array( 'filetype-bad-ie-mime', $ieType );
				}
			}
		}

		#check for htmlish code and javascript
		if( self::detectScript( $this->mTempPath, $mime, $this->mFinalExtension ) ) {
			return 'uploadscripted';
		}
		if( $this->mFinalExtension == 'svg' || $mime == 'image/svg+xml' ) {
			if( self::detectScriptInSvg( $this->mTempPath ) ) {
				return 'uploadscripted';
			}
		}

		/**
		* Scan the uploaded file for viruses
		*/
		$virus = $this->detectVirus( $this->mTempPath );
		if ( $virus ) {
			return array( 'uploadvirus', $virus );
		}
		wfDebug( __METHOD__ . ": all clear; passing.\n" );
		return true;
	}

	/**
	 * Check whether the user can edit, upload and create the image.
	 * 
	 * @param User $user the user to verify the permissions against
	 * @return mixed An array as returned by getUserPermissionsErrors or true
	 *               in case the user has proper permissions.
	 */
	public function verifyPermissions( $user ) {
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

	/**
	 * Check for non fatal problems with the file
	 * 
	 * @return array Array of warnings
	 */
	public function checkWarnings() {
		$warnings = array();

		$localFile = $this->getLocalFile();
		$filename = $localFile->getName();
		$n = strrpos( $filename, '.' );
		$partname = $n ? substr( $filename, 0, $n ) : $filename;

		/*
		 * Check whether the resulting filename is different from the desired one,
		 * but ignore things like ucfirst() and spaces/underscore things
		 */
		$comparableName = str_replace( ' ', '_', $this->mDesiredDestName );
		global $wgCapitalLinks, $wgContLang;
		if ( $wgCapitalLinks ) {
			$comparableName = $wgContLang->ucfirst( $comparableName );
		}
		if( $this->mDesiredDestName != $filename && $comparableName != $filename )
			$warnings['badfilename'] = $filename;

		// Check whether the file extension is on the unwanted list
		global $wgCheckFileExtensions, $wgFileExtensions;
		if ( $wgCheckFileExtensions ) {
			if ( !$this->checkFileExtension( $this->mFinalExtension, $wgFileExtensions ) )
				$warnings['filetype-unwanted-type'] = $this->mFinalExtension;
		}

		global $wgUploadSizeWarning;
		if ( $wgUploadSizeWarning && ( $this->mFileSize > $wgUploadSizeWarning ) )
			$warnings['large-file'] = $wgUploadSizeWarning;

		if ( $this->mFileSize == 0 )
			$warnings['emptyfile'] = true;


		$exists = self::getExistsWarning( $localFile );
		if( $exists !== false )
			$warnings['exists'] = $exists;

		// Check dupes against existing files
		$hash = File::sha1Base36( $this->mTempPath );
		$dupes = RepoGroup::singleton()->findBySha1( $hash );
		$title = $this->getTitle();
		// Remove all matches against self
		foreach ( $dupes as $key => $dupe ) {
			if( $title->equals( $dupe->getTitle() ) )
				unset( $dupes[$key] );
		}
		if( $dupes )
			$warnings['duplicate'] = $dupes;

		// Check dupes against archives
		$archivedImage = new ArchivedFile( null, 0, "{$hash}.{$this->mFinalExtension}" );
		if ( $archivedImage->getID() > 0 )
			$warnings['duplicate-archive'] = $archivedImage->getName();

		return $warnings;
	}

	/**
	 * Really perform the upload. Stores the file in the local repo, watches 
	 * if necessary and runs the UploadComplete hook.
	 * 
	 * @return mixed Status indicating the whether the upload succeeded.
	 */
	public function performUpload( $comment, $pageText, $watch, $user ) {
		wfDebug( "\n\n\performUpload: sum:" . $comment . ' c: ' . $pageText . ' w:' . $watch );
		$status = $this->getLocalFile()->upload( $this->mTempPath, $comment, $pageText,
			File::DELETE_SOURCE, $this->mFileProps, false, $user );

		if( $status->isGood() && $watch )
			$user->addWatch( $this->getLocalFile()->getTitle() );

		if( $status->isGood() )
			wfRunHooks( 'UploadComplete', array( &$this ) );

		return $status;
	}

	/**
	 * Returns the title of the file to be uploaded. Sets mTitleError in case
	 * the name was illegal.
	 * 
	 * @return Title The title of the file or null in case the name was illegal
	 */
	public function getTitle() {
		if ( $this->mTitle !== false )
			return $this->mTitle;

		/**
		 * Chop off any directories in the given filename. Then
		 * filter out illegal characters, and try to make a legible name
		 * out of it. We'll strip some silently that Title would die on.
		 */
		$basename = $this->mDesiredDestName;

		$this->mFilteredName = wfStripIllegalFilenameChars( $basename );
		/* Normalize to title form before we do any further processing */
		$nt = Title::makeTitleSafe( NS_FILE, $this->mFilteredName );
		if( is_null( $nt ) ) {
			$this->mTitleError = self::ILLEGAL_FILENAME;
			return $this->mTitle = null;
		}
		$this->mFilteredName = $nt->getDBkey();

		/**
		 * We'll want to blacklist against *any* 'extension', and use
		 * only the final one for the whitelist.
		 */
		list( $partname, $ext ) = $this->splitExtensions( $this->mFilteredName );

		if( count( $ext ) ) {
			$this->mFinalExtension = trim( $ext[count( $ext ) - 1] );
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

		$nt = Title::makeTitleSafe( NS_FILE, $this->mFilteredName );
		if( is_null( $nt ) ) {
			$this->mTitleError = self::ILLEGAL_FILENAME;
			return $this->mTitle = null;
		}
		return $this->mTitle = $nt;
	}

	/**
	 * Return the local file and initializes if necessary. 
	 */
	public function getLocalFile() {
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
	 * @param string $tempSrc - the source temporary file to save
	 * @return string - full path the stashed file, or false on failure
	 * @access private
	 */
	protected function saveTempUploadedFile( $saveName, $tempSrc ) {
		$repo = RepoGroup::singleton()->getLocalRepo();
		$status = $repo->storeTemp( $saveName, $tempSrc );
		return $status;
	}

	/** 
	 * Append a file to a stashed file.
	 * 
	 * @param string $srcPath Path to file to append from
	 * @param string $toAppendPath Path to file to append to
	 * @return Status Status
	 */
	public function appendToUploadFile( $srcPath, $toAppendPath ){
		$repo = RepoGroup::singleton()->getLocalRepo();
		$status = $repo->append( $srcPath, $toAppendPath );
		return $status;
	}

	/**
	 * Stash a file in a temporary directory for later processing,
	 * and save the necessary descriptive info into the session.
	 * Returns a key value which will be passed through a form
	 * to pick up the path info on a later invocation.
	 *
	 * @return int Session key
	 */
	public function stashSession() {
		$status = $this->saveTempUploadedFile( $this->mDestName, $this->mTempPath );
		if( !$status->isOK() ) {
			# Couldn't save the file.
			return false;
		}
		if(!isset($_SESSION))
			session_start(); // start up the session (might have been previously closed to prevent php session locking)
		$key = $this->getSessionKey();
		$_SESSION['wsUploadData'][$key] = array(
			'mTempPath'       => $status->value,
			'mFileSize'       => $this->mFileSize,
			'mFileProps'      => $this->mFileProps,
			'version'         => self::SESSION_VERSION,
		);		
		return $key;
	}

	/**
	 * Generate a random session key from stash in cases where we want to start an upload without much information
	 */
	protected function getSessionKey(){
		$key = mt_rand( 0, 0x7fffffff );
		$_SESSION['wsUploadData'][$key] = array();
		return $key;
	}


	/**
	 * If we've modified the upload file we need to manually remove it
	 * on exit to clean up.
	 * @access private
	 */
	public function cleanupTempFile() {
		if ( $this->mRemoveTempFile && $this->mTempPath && file_exists( $this->mTempPath ) ) {
			wfDebug( __METHOD__ . ": Removing temporary file {$this->mTempPath}\n" );
			unlink( $this->mTempPath );
		}
	}

	public function getTempPath() {
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
	public static function splitExtensions( $filename ) {
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
	public static function checkFileExtension( $ext, $list ) {
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
	public static function checkFileExtensionList( $ext, $list ) {
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

		if ( !$mime || $mime == 'unknown' || $mime == 'unknown/unknown' )
			if ( !$magic->isRecognizableExtension( $extension ) ) {
				wfDebug( __METHOD__ . ": passing file with unknown detected mime type; " .
					"unrecognized extension '$extension', can't verify\n" );
				return true;
			} else {
				wfDebug( __METHOD__ . ": rejecting file with unknown detected mime type; ".
					"recognized extension '$extension', so probably invalid file\n" );
				return false;
			}

		$match = $magic->isMatchingExtension( $extension, $mime );

		if ( $match === NULL ) {
			wfDebug( __METHOD__ . ": no file extension known for mime type $mime, passing file\n" );
			return true;
		} elseif( $match === true ) {
			wfDebug( __METHOD__ . ": mime type $mime matches extension $extension, passing file\n" );

			#TODO: if it's a bitmap, make sure PHP or ImageMagic resp. can handle it!
			return true;

		} else {
			wfDebug( __METHOD__ . ": mime type $mime mismatches file extension $extension, rejecting file\n" );
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
	public static function detectScript( $file, $mime, $extension ) {
		global $wgAllowTitlesInSVG;

		#ugly hack: for text files, always look at the entire file.
		#For binary field, just check the first K.

		if( strpos( $mime,'text/' ) === 0 )
			$chunk = file_get_contents( $file );
		else {
			$fp = fopen( $file, 'rb' );
			$chunk = fread( $fp, 1024 );
			fclose( $fp );
		}

		$chunk = strtolower( $chunk );

		if( !$chunk )
			return false;

		#decode from UTF-16 if needed (could be used for obfuscation).
		if( substr( $chunk, 0, 2 ) == "\xfe\xff" )
			$enc = "UTF-16BE";
		elseif( substr( $chunk, 0, 2 ) == "\xff\xfe" )
			$enc = "UTF-16LE";
		else
			$enc = NULL;

		if( $enc )
			$chunk = iconv( $enc, "ASCII//IGNORE", $chunk );

		$chunk = trim( $chunk );

		#FIXME: convert from UTF-16 if necessarry!
		wfDebug( __METHOD__ . ": checking for embedded scripts and HTML stuff\n" );

		#check for HTML doctype
		if ( preg_match( "/<!DOCTYPE *X?HTML/i", $chunk ) )
			return true;

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
			'<a href',
			'<body',
			'<head',
			'<html',   #also in safari
			'<img',
			'<pre',
			'<script', #also in safari
			'<table'
		);

		if( !$wgAllowTitlesInSVG && $extension !== 'svg' && $mime !== 'image/svg' ) {
			$tags[] = '<title';
		}

		foreach( $tags as $tag ) {
			if( false !== strpos( $chunk, $tag ) ) {
				return true;
			}
		}

		/*
		 * look for JavaScript
		 */

		#resolve entity-refs to look at attributes. may be harsh on big files... cache result?
		$chunk = Sanitizer::decodeCharReferences( $chunk );

		#look for script-types
		if( preg_match( '!type\s*=\s*[\'"]?\s*(?:\w*/)?(?:ecma|java)!sim', $chunk ) )
			return true;

		#look for html-style script-urls
		if( preg_match( '!(?:href|src|data)\s*=\s*[\'"]?\s*(?:ecma|java)script:!sim', $chunk ) )
			return true;

		#look for css-style script-urls
		if( preg_match( '!url\s*\(\s*[\'"]?\s*(?:ecma|java)script:!sim', $chunk ) )
			return true;

		wfDebug( __METHOD__ . ": no scripts found\n" );
		return false;
	}

	protected function detectScriptInSvg( $filename ) {
		$check = new XmlTypeCheck( $filename, array( $this, 'checkSvgScriptCallback' ) );
		return $check->filterMatch;
	}

	/**
	 * @todo Replace this with a whitelist filter!
	 */
	public function checkSvgScriptCallback( $element, $attribs ) {
		$stripped = $this->stripXmlNamespace( $element );

		if( $stripped == 'script' ) {
			wfDebug( __METHOD__ . ": Found script element '$element' in uploaded file.\n" );
			return true;
		}

		foreach( $attribs as $attrib => $value ) {
			$stripped = $this->stripXmlNamespace( $attrib );
			if( substr( $stripped, 0, 2 ) == 'on' ) {
				wfDebug( __METHOD__ . ": Found script attribute '$attrib'='value' in uploaded file.\n" );
				return true;
			}
			if( $stripped == 'href' && strpos( strtolower( $value ), 'javascript:' ) !== false ) {
				wfDebug( __METHOD__ . ": Found script href attribute '$attrib'='$value' in uploaded file.\n" );
				return true;
			}
		}
	}

	private function stripXmlNamespace( $name ) {
		// 'http://www.w3.org/2000/svg:script' -> 'script'
		$parts = explode( ':', strtolower( $name ) );
		return array_pop( $parts );
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
	public static function detectVirus( $file ) {
		global $wgAntivirus, $wgAntivirusSetup, $wgAntivirusRequired, $wgOut;

		if ( !$wgAntivirus ) {
			wfDebug( __METHOD__ . ": virus scanner disabled\n" );
			return NULL;
		}

		if ( !$wgAntivirusSetup[$wgAntivirus] ) {
			wfDebug( __METHOD__ . ": unknown virus scanner: $wgAntivirus\n" );
			$wgOut->wrapWikiMsg( '<div class="error">$1</div>', array( 'virus-badscanner', $wgAntivirus ) );
			return wfMsg( 'virus-unknownscanner' ) . " $wgAntivirus";
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

		wfDebug( __METHOD__ . ": running virus scan: $command \n" );

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
			wfDebug( __METHOD__ . ": failed to scan $file (code $exitCode).\n" );

			if ( $wgAntivirusRequired ) {
				return wfMsg( 'virus-scanfailed', array( $exitCode ) );
			} else {
				return NULL;
			}
		} else if ( $mappedCode === AV_SCAN_ABORTED ) {
			# scan failed because filetype is unknown (probably imune)
			wfDebug( __METHOD__ . ": unsupported file type $file (code $exitCode).\n" );
			return NULL;
		} else if ( $mappedCode === AV_NO_VIRUS ) {
			# no virus found
			wfDebug( __METHOD__ . ": file passed virus scan.\n" );
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

			wfDebug( __METHOD__ . ": FOUND VIRUS! scanner feedback: $output \n" );
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
	private function checkMacBinary() {
		$macbin = new MacBinary( $this->mTempPath );
		if( $macbin->isValid() ) {
			$dataFile = tempnam( wfTempDir(), 'WikiMacBinary' );
			$dataHandle = fopen( $dataFile, 'wb' );

			wfDebug( __METHOD__ . ": Extracting MacBinary data fork to $dataFile\n" );
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
	 * @return mixed true on success, error string on failure
	 * @access private
	 */
	private function checkOverwrite() {
		global $wgUser;
		// First check whether the local file can be overwritten
		$file = $this->getLocalFile();
		if( $file->exists() ) {
			if( !self::userCanReUpload( $wgUser, $file ) )
				return 'fileexists-forbidden';
			else
				return true;
		}

		/* Check shared conflicts: if the local file does not exist, but 
		 * wfFindFile finds a file, it exists in a shared repository. 
		 */ 
		$file = wfFindFile( $this->getTitle() );
		if ( $file && !$wgUser->isAllowed( 'reupload-shared' ) )
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

	/**
	 * Helper function that does various existence checks for a file.
	 * The following checks are performed: 
	 * - The file exists
	 * - Article with the same name as the file exists
	 * - File exists with normalized extension
	 * - The file looks like a thumbnail and the original exists
	 * 
	 * @param File $file The file to check 
	 * @return mixed False if the file does not exists, else an array
	 */
	public static function getExistsWarning( $file ) {
		if( $file->exists() )
			return array( 'warning' => 'exists', 'file' => $file );

		if( $file->getTitle()->getArticleID() )
			return array( 'warning' => 'page-exists', 'file' => $file );
		
		if ( $file->wasDeleted() && !$file->exists() )
			return array( 'warning' => 'was-deleted', 'file' => $file );		
			
		if( strpos( $file->getName(), '.' ) == false ) {
			$partname = $file->getName();
			$extension = '';
		} else {
			$n = strrpos( $file->getName(), '.' );
			$extension = substr( $file->getName(), $n + 1 );
			$partname = substr( $file->getName(), 0, $n );
		}
		$normalizedExtension = File::normalizeExtension( $extension );

		if ( $normalizedExtension != $extension ) {
			// We're not using the normalized form of the extension.
			// Normal form is lowercase, using most common of alternate
			// extensions (eg 'jpg' rather than 'JPEG').
			//
			// Check for another file using the normalized form...
			$nt_lc = Title::makeTitle( NS_FILE, "{$partname}.{$normalizedExtension}" );
			$file_lc = wfLocalFile( $nt_lc );

			if( $file_lc->exists() )
				return array( 'warning' => 'exists-normalized', 'file' => $file, 'normalizedFile' => $file_lc );
		}

		if ( self::isThumbName( $file->getName() ) ) {
			# Check for filenames like 50px- or 180px-, these are mostly thumbnails
			$nt_thb = Title::newFromText( substr( $partname , strpos( $partname , '-' ) +1 ) . '.' . $extension, NS_FILE );
			$file_thb = wfLocalFile( $nt_thb );
			if( $file_thb->exists() )
				return array( 'warning' => 'thumb', 'file' => $file, 'thumbFile' => $file_thb );
			else
				// File does not exist, but we just don't like the name
				return array( 'warning' => 'thumb-name', 'file' => $file, 'thumbFile' => $file_thb );
		}
		

		foreach( self::getFilenamePrefixBlacklist() as $prefix ) {
			if ( substr( $partname, 0, strlen( $prefix ) ) == $prefix )
				return array( 'warning' => 'bad-prefix', 'file' => $file, 'prefix' => $prefix );
		}
		


		return false;
	}

	/**
	 * Helper function that checks whether the filename looks like a thumbnail
	 */
	public static function isThumbName( $filename ) {
		$n = strrpos( $filename, '.' );
		$partname = $n ? substr( $filename, 0, $n ) : $filename;
		return (
					substr( $partname , 3, 3 ) == 'px-' ||
					substr( $partname , 2, 3 ) == 'px-'
				) &&
				preg_match( "/[0-9]{2}/" , substr( $partname , 0, 2 ) );
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
