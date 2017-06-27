<?php
/**
 * Base class for the backend of file upload.
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
 * @ingroup Upload
 */
use MediaWiki\MediaWikiServices;

/**
 * @defgroup Upload Upload related
 */

/**
 * @ingroup Upload
 *
 * UploadBase and subclasses are the backend of MediaWiki's file uploads.
 * The frontends are formed by ApiUpload and SpecialUpload.
 *
 * @author Brion Vibber
 * @author Bryan Tong Minh
 * @author Michael Dale
 */
abstract class UploadBase {
	/** @var string Local file system path to the file to upload (or a local copy) */
	protected $mTempPath;
	/** @var TempFSFile|null Wrapper to handle deleting the temp file */
	protected $tempFileObj;

	protected $mDesiredDestName, $mDestName, $mRemoveTempFile, $mSourceType;
	protected $mTitle = false, $mTitleError = 0;
	protected $mFilteredName, $mFinalExtension;
	protected $mLocalFile, $mStashFile, $mFileSize, $mFileProps;
	protected $mBlackListedExtensions;
	protected $mJavaDetected, $mSVGNSError;

	protected static $safeXmlEncodings = [
		'UTF-8',
		'ISO-8859-1',
		'ISO-8859-2',
		'UTF-16',
		'UTF-32',
		'WINDOWS-1250',
		'WINDOWS-1251',
		'WINDOWS-1252',
		'WINDOWS-1253',
		'WINDOWS-1254',
		'WINDOWS-1255',
		'WINDOWS-1256',
		'WINDOWS-1257',
		'WINDOWS-1258',
	];

	const SUCCESS = 0;
	const OK = 0;
	const EMPTY_FILE = 3;
	const MIN_LENGTH_PARTNAME = 4;
	const ILLEGAL_FILENAME = 5;
	const OVERWRITE_EXISTING_FILE = 7; # Not used anymore; handled by verifyTitlePermissions()
	const FILETYPE_MISSING = 8;
	const FILETYPE_BADTYPE = 9;
	const VERIFICATION_ERROR = 10;
	const HOOK_ABORTED = 11;
	const FILE_TOO_LARGE = 12;
	const WINDOWS_NONASCII_FILENAME = 13;
	const FILENAME_TOO_LONG = 14;

	/**
	 * @param int $error
	 * @return string
	 */
	public function getVerificationErrorCode( $error ) {
		$code_to_status = [
			self::EMPTY_FILE => 'empty-file',
			self::FILE_TOO_LARGE => 'file-too-large',
			self::FILETYPE_MISSING => 'filetype-missing',
			self::FILETYPE_BADTYPE => 'filetype-banned',
			self::MIN_LENGTH_PARTNAME => 'filename-tooshort',
			self::ILLEGAL_FILENAME => 'illegal-filename',
			self::OVERWRITE_EXISTING_FILE => 'overwrite',
			self::VERIFICATION_ERROR => 'verification-error',
			self::HOOK_ABORTED => 'hookaborted',
			self::WINDOWS_NONASCII_FILENAME => 'windows-nonascii-filename',
			self::FILENAME_TOO_LONG => 'filename-toolong',
		];
		if ( isset( $code_to_status[$error] ) ) {
			return $code_to_status[$error];
		}

		return 'unknown-error';
	}

	/**
	 * Returns true if uploads are enabled.
	 * Can be override by subclasses.
	 * @return bool
	 */
	public static function isEnabled() {
		global $wgEnableUploads;

		if ( !$wgEnableUploads ) {
			return false;
		}

		# Check php's file_uploads setting
		return wfIsHHVM() || wfIniGetBool( 'file_uploads' );
	}

	/**
	 * Returns true if the user can use this upload module or else a string
	 * identifying the missing permission.
	 * Can be overridden by subclasses.
	 *
	 * @param User $user
	 * @return bool|string
	 */
	public static function isAllowed( $user ) {
		foreach ( [ 'upload', 'edit' ] as $permission ) {
			if ( !$user->isAllowed( $permission ) ) {
				return $permission;
			}
		}

		return true;
	}

	/**
	 * Returns true if the user has surpassed the upload rate limit, false otherwise.
	 *
	 * @param User $user
	 * @return bool
	 */
	public static function isThrottled( $user ) {
		return $user->pingLimiter( 'upload' );
	}

	// Upload handlers. Should probably just be a global.
	private static $uploadHandlers = [ 'Stash', 'File', 'Url' ];

	/**
	 * Create a form of UploadBase depending on wpSourceType and initializes it
	 *
	 * @param WebRequest $request
	 * @param string|null $type
	 * @return null|UploadBase
	 */
	public static function createFromRequest( &$request, $type = null ) {
		$type = $type ? $type : $request->getVal( 'wpSourceType', 'File' );

		if ( !$type ) {
			return null;
		}

		// Get the upload class
		$type = ucfirst( $type );

		// Give hooks the chance to handle this request
		$className = null;
		Hooks::run( 'UploadCreateFromRequest', [ $type, &$className ] );
		if ( is_null( $className ) ) {
			$className = 'UploadFrom' . $type;
			wfDebug( __METHOD__ . ": class name: $className\n" );
			if ( !in_array( $type, self::$uploadHandlers ) ) {
				return null;
			}
		}

		// Check whether this upload class is enabled
		if ( !call_user_func( [ $className, 'isEnabled' ] ) ) {
			return null;
		}

		// Check whether the request is valid
		if ( !call_user_func( [ $className, 'isValidRequest' ], $request ) ) {
			return null;
		}

		/** @var UploadBase $handler */
		$handler = new $className;

		$handler->initializeFromRequest( $request );

		return $handler;
	}

	/**
	 * Check whether a request if valid for this handler
	 * @param WebRequest $request
	 * @return bool
	 */
	public static function isValidRequest( $request ) {
		return false;
	}

	public function __construct() {
	}

	/**
	 * Returns the upload type. Should be overridden by child classes
	 *
	 * @since 1.18
	 * @return string
	 */
	public function getSourceType() {
		return null;
	}

	/**
	 * Initialize the path information
	 * @param string $name The desired destination name
	 * @param string $tempPath The temporary path
	 * @param int $fileSize The file size
	 * @param bool $removeTempFile (false) remove the temporary file?
	 * @throws MWException
	 */
	public function initializePathInfo( $name, $tempPath, $fileSize, $removeTempFile = false ) {
		$this->mDesiredDestName = $name;
		if ( FileBackend::isStoragePath( $tempPath ) ) {
			throw new MWException( __METHOD__ . " given storage path `$tempPath`." );
		}

		$this->setTempFile( $tempPath, $fileSize );
		$this->mRemoveTempFile = $removeTempFile;
	}

	/**
	 * Initialize from a WebRequest. Override this in a subclass.
	 *
	 * @param WebRequest $request
	 */
	abstract public function initializeFromRequest( &$request );

	/**
	 * @param string $tempPath File system path to temporary file containing the upload
	 * @param integer $fileSize
	 */
	protected function setTempFile( $tempPath, $fileSize = null ) {
		$this->mTempPath = $tempPath;
		$this->mFileSize = $fileSize ?: null;
		if ( strlen( $this->mTempPath ) && file_exists( $this->mTempPath ) ) {
			$this->tempFileObj = new TempFSFile( $this->mTempPath );
			if ( !$fileSize ) {
				$this->mFileSize = filesize( $this->mTempPath );
			}
		} else {
			$this->tempFileObj = null;
		}
	}

	/**
	 * Fetch the file. Usually a no-op
	 * @return Status
	 */
	public function fetchFile() {
		return Status::newGood();
	}

	/**
	 * Return true if the file is empty
	 * @return bool
	 */
	public function isEmptyFile() {
		return empty( $this->mFileSize );
	}

	/**
	 * Return the file size
	 * @return int
	 */
	public function getFileSize() {
		return $this->mFileSize;
	}

	/**
	 * Get the base 36 SHA1 of the file
	 * @return string
	 */
	public function getTempFileSha1Base36() {
		return FSFile::getSha1Base36FromPath( $this->mTempPath );
	}

	/**
	 * @param string $srcPath The source path
	 * @return string|bool The real path if it was a virtual URL Returns false on failure
	 */
	public function getRealPath( $srcPath ) {
		$repo = RepoGroup::singleton()->getLocalRepo();
		if ( $repo->isVirtualUrl( $srcPath ) ) {
			/** @todo Just make uploads work with storage paths UploadFromStash
			 *  loads files via virtual URLs.
			 */
			$tmpFile = $repo->getLocalCopy( $srcPath );
			if ( $tmpFile ) {
				$tmpFile->bind( $this ); // keep alive with $this
			}
			$path = $tmpFile ? $tmpFile->getPath() : false;
		} else {
			$path = $srcPath;
		}

		return $path;
	}

	/**
	 * Verify whether the upload is sane.
	 * @return mixed Const self::OK or else an array with error information
	 */
	public function verifyUpload() {

		/**
		 * If there was no filename or a zero size given, give up quick.
		 */
		if ( $this->isEmptyFile() ) {
			return [ 'status' => self::EMPTY_FILE ];
		}

		/**
		 * Honor $wgMaxUploadSize
		 */
		$maxSize = self::getMaxUploadSize( $this->getSourceType() );
		if ( $this->mFileSize > $maxSize ) {
			return [
				'status' => self::FILE_TOO_LARGE,
				'max' => $maxSize,
			];
		}

		/**
		 * Look at the contents of the file; if we can recognize the
		 * type but it's corrupt or data of the wrong type, we should
		 * probably not accept it.
		 */
		$verification = $this->verifyFile();
		if ( $verification !== true ) {
			return [
				'status' => self::VERIFICATION_ERROR,
				'details' => $verification
			];
		}

		/**
		 * Make sure this file can be created
		 */
		$result = $this->validateName();
		if ( $result !== true ) {
			return $result;
		}

		$error = '';
		if ( !Hooks::run( 'UploadVerification',
			[ $this->mDestName, $this->mTempPath, &$error ], '1.28' )
		) {
			return [ 'status' => self::HOOK_ABORTED, 'error' => $error ];
		}

		return [ 'status' => self::OK ];
	}

	/**
	 * Verify that the name is valid and, if necessary, that we can overwrite
	 *
	 * @return mixed True if valid, otherwise and array with 'status'
	 * and other keys
	 */
	public function validateName() {
		$nt = $this->getTitle();
		if ( is_null( $nt ) ) {
			$result = [ 'status' => $this->mTitleError ];
			if ( $this->mTitleError == self::ILLEGAL_FILENAME ) {
				$result['filtered'] = $this->mFilteredName;
			}
			if ( $this->mTitleError == self::FILETYPE_BADTYPE ) {
				$result['finalExt'] = $this->mFinalExtension;
				if ( count( $this->mBlackListedExtensions ) ) {
					$result['blacklistedExt'] = $this->mBlackListedExtensions;
				}
			}

			return $result;
		}
		$this->mDestName = $this->getLocalFile()->getName();

		return true;
	}

	/**
	 * Verify the MIME type.
	 *
	 * @note Only checks that it is not an evil MIME. The "does it have
	 *  correct extension given its MIME type?" check is in verifyFile.
	 *  in `verifyFile()` that MIME type and file extension correlate.
	 * @param string $mime Representing the MIME
	 * @return mixed True if the file is verified, an array otherwise
	 */
	protected function verifyMimeType( $mime ) {
		global $wgVerifyMimeType;
		if ( $wgVerifyMimeType ) {
			wfDebug( "mime: <$mime> extension: <{$this->mFinalExtension}>\n" );
			global $wgMimeTypeBlacklist;
			if ( $this->checkFileExtension( $mime, $wgMimeTypeBlacklist ) ) {
				return [ 'filetype-badmime', $mime ];
			}

			# Check what Internet Explorer would detect
			$fp = fopen( $this->mTempPath, 'rb' );
			$chunk = fread( $fp, 256 );
			fclose( $fp );

			$magic = MimeMagic::singleton();
			$extMime = $magic->guessTypesForExtension( $this->mFinalExtension );
			$ieTypes = $magic->getIEMimeTypes( $this->mTempPath, $chunk, $extMime );
			foreach ( $ieTypes as $ieType ) {
				if ( $this->checkFileExtension( $ieType, $wgMimeTypeBlacklist ) ) {
					return [ 'filetype-bad-ie-mime', $ieType ];
				}
			}
		}

		return true;
	}

	/**
	 * Verifies that it's ok to include the uploaded file
	 *
	 * @return mixed True of the file is verified, array otherwise.
	 */
	protected function verifyFile() {
		global $wgVerifyMimeType, $wgDisableUploadScriptChecks;

		$status = $this->verifyPartialFile();
		if ( $status !== true ) {
			return $status;
		}

		$mwProps = new MWFileProps( MimeMagic::singleton() );
		$this->mFileProps = $mwProps->getPropsFromPath( $this->mTempPath, $this->mFinalExtension );
		$mime = $this->mFileProps['mime'];

		if ( $wgVerifyMimeType ) {
			# XXX: Missing extension will be caught by validateName() via getTitle()
			if ( $this->mFinalExtension != '' && !$this->verifyExtension( $mime, $this->mFinalExtension ) ) {
				return [ 'filetype-mime-mismatch', $this->mFinalExtension, $mime ];
			}
		}

		# check for htmlish code and javascript
		if ( !$wgDisableUploadScriptChecks ) {
			if ( $this->mFinalExtension == 'svg' || $mime == 'image/svg+xml' ) {
				$svgStatus = $this->detectScriptInSvg( $this->mTempPath, false );
				if ( $svgStatus !== false ) {
					return $svgStatus;
				}
			}
		}

		$handler = MediaHandler::getHandler( $mime );
		if ( $handler ) {
			$handlerStatus = $handler->verifyUpload( $this->mTempPath );
			if ( !$handlerStatus->isOK() ) {
				$errors = $handlerStatus->getErrorsArray();

				return reset( $errors );
			}
		}

		$error = true;
		Hooks::run( 'UploadVerifyFile', [ $this, $mime, &$error ] );
		if ( $error !== true ) {
			if ( !is_array( $error ) ) {
				$error = [ $error ];
			}
			return $error;
		}

		wfDebug( __METHOD__ . ": all clear; passing.\n" );

		return true;
	}

	/**
	 * A verification routine suitable for partial files
	 *
	 * Runs the blacklist checks, but not any checks that may
	 * assume the entire file is present.
	 *
	 * @return mixed True for valid or array with error message key.
	 */
	protected function verifyPartialFile() {
		global $wgAllowJavaUploads, $wgDisableUploadScriptChecks;

		# getTitle() sets some internal parameters like $this->mFinalExtension
		$this->getTitle();

		$mwProps = new MWFileProps( MimeMagic::singleton() );
		$this->mFileProps = $mwProps->getPropsFromPath( $this->mTempPath, $this->mFinalExtension );

		# check MIME type, if desired
		$mime = $this->mFileProps['file-mime'];
		$status = $this->verifyMimeType( $mime );
		if ( $status !== true ) {
			return $status;
		}

		# check for htmlish code and javascript
		if ( !$wgDisableUploadScriptChecks ) {
			if ( self::detectScript( $this->mTempPath, $mime, $this->mFinalExtension ) ) {
				return [ 'uploadscripted' ];
			}
			if ( $this->mFinalExtension == 'svg' || $mime == 'image/svg+xml' ) {
				$svgStatus = $this->detectScriptInSvg( $this->mTempPath, true );
				if ( $svgStatus !== false ) {
					return $svgStatus;
				}
			}
		}

		# Check for Java applets, which if uploaded can bypass cross-site
		# restrictions.
		if ( !$wgAllowJavaUploads ) {
			$this->mJavaDetected = false;
			$zipStatus = ZipDirectoryReader::read( $this->mTempPath,
				[ $this, 'zipEntryCallback' ] );
			if ( !$zipStatus->isOK() ) {
				$errors = $zipStatus->getErrorsArray();
				$error = reset( $errors );
				if ( $error[0] !== 'zip-wrong-format' ) {
					return $error;
				}
			}
			if ( $this->mJavaDetected ) {
				return [ 'uploadjava' ];
			}
		}

		# Scan the uploaded file for viruses
		$virus = $this->detectVirus( $this->mTempPath );
		if ( $virus ) {
			return [ 'uploadvirus', $virus ];
		}

		return true;
	}

	/**
	 * Callback for ZipDirectoryReader to detect Java class files.
	 *
	 * @param array $entry
	 */
	public function zipEntryCallback( $entry ) {
		$names = [ $entry['name'] ];

		// If there is a null character, cut off the name at it, because JDK's
		// ZIP_GetEntry() uses strcmp() if the name hashes match. If a file name
		// were constructed which had ".class\0" followed by a string chosen to
		// make the hash collide with the truncated name, that file could be
		// returned in response to a request for the .class file.
		$nullPos = strpos( $entry['name'], "\000" );
		if ( $nullPos !== false ) {
			$names[] = substr( $entry['name'], 0, $nullPos );
		}

		// If there is a trailing slash in the file name, we have to strip it,
		// because that's what ZIP_GetEntry() does.
		if ( preg_grep( '!\.class/?$!', $names ) ) {
			$this->mJavaDetected = true;
		}
	}

	/**
	 * Alias for verifyTitlePermissions. The function was originally
	 * 'verifyPermissions', but that suggests it's checking the user, when it's
	 * really checking the title + user combination.
	 *
	 * @param User $user User object to verify the permissions against
	 * @return mixed An array as returned by getUserPermissionsErrors or true
	 *   in case the user has proper permissions.
	 */
	public function verifyPermissions( $user ) {
		return $this->verifyTitlePermissions( $user );
	}

	/**
	 * Check whether the user can edit, upload and create the image. This
	 * checks only against the current title; if it returns errors, it may
	 * very well be that another title will not give errors. Therefore
	 * isAllowed() should be called as well for generic is-user-blocked or
	 * can-user-upload checking.
	 *
	 * @param User $user User object to verify the permissions against
	 * @return mixed An array as returned by getUserPermissionsErrors or true
	 *   in case the user has proper permissions.
	 */
	public function verifyTitlePermissions( $user ) {
		/**
		 * If the image is protected, non-sysop users won't be able
		 * to modify it by uploading a new revision.
		 */
		$nt = $this->getTitle();
		if ( is_null( $nt ) ) {
			return true;
		}
		$permErrors = $nt->getUserPermissionsErrors( 'edit', $user );
		$permErrorsUpload = $nt->getUserPermissionsErrors( 'upload', $user );
		if ( !$nt->exists() ) {
			$permErrorsCreate = $nt->getUserPermissionsErrors( 'create', $user );
		} else {
			$permErrorsCreate = [];
		}
		if ( $permErrors || $permErrorsUpload || $permErrorsCreate ) {
			$permErrors = array_merge( $permErrors, wfArrayDiff2( $permErrorsUpload, $permErrors ) );
			$permErrors = array_merge( $permErrors, wfArrayDiff2( $permErrorsCreate, $permErrors ) );

			return $permErrors;
		}

		$overwriteError = $this->checkOverwrite( $user );
		if ( $overwriteError !== true ) {
			return [ $overwriteError ];
		}

		return true;
	}

	/**
	 * Check for non fatal problems with the file.
	 *
	 * This should not assume that mTempPath is set.
	 *
	 * @return array Array of warnings
	 */
	public function checkWarnings() {
		global $wgLang;

		$warnings = [];

		$localFile = $this->getLocalFile();
		$localFile->load( File::READ_LATEST );
		$filename = $localFile->getName();

		/**
		 * Check whether the resulting filename is different from the desired one,
		 * but ignore things like ucfirst() and spaces/underscore things
		 */
		$comparableName = str_replace( ' ', '_', $this->mDesiredDestName );
		$comparableName = Title::capitalize( $comparableName, NS_FILE );

		if ( $this->mDesiredDestName != $filename && $comparableName != $filename ) {
			$warnings['badfilename'] = $filename;
		}

		// Check whether the file extension is on the unwanted list
		global $wgCheckFileExtensions, $wgFileExtensions;
		if ( $wgCheckFileExtensions ) {
			$extensions = array_unique( $wgFileExtensions );
			if ( !$this->checkFileExtension( $this->mFinalExtension, $extensions ) ) {
				$warnings['filetype-unwanted-type'] = [ $this->mFinalExtension,
					$wgLang->commaList( $extensions ), count( $extensions ) ];
			}
		}

		global $wgUploadSizeWarning;
		if ( $wgUploadSizeWarning && ( $this->mFileSize > $wgUploadSizeWarning ) ) {
			$warnings['large-file'] = [ $wgUploadSizeWarning, $this->mFileSize ];
		}

		if ( $this->mFileSize == 0 ) {
			$warnings['empty-file'] = true;
		}

		$hash = $this->getTempFileSha1Base36();
		$exists = self::getExistsWarning( $localFile );
		if ( $exists !== false ) {
			$warnings['exists'] = $exists;

			// check if file is an exact duplicate of current file version
			if ( $hash === $localFile->getSha1() ) {
				$warnings['no-change'] = $localFile;
			}

			// check if file is an exact duplicate of older versions of this file
			$history = $localFile->getHistory();
			foreach ( $history as $oldFile ) {
				if ( $hash === $oldFile->getSha1() ) {
					$warnings['duplicate-version'][] = $oldFile;
				}
			}
		}

		if ( $localFile->wasDeleted() && !$localFile->exists() ) {
			$warnings['was-deleted'] = $filename;
		}

		// Check dupes against existing files
		$dupes = RepoGroup::singleton()->findBySha1( $hash );
		$title = $this->getTitle();
		// Remove all matches against self
		foreach ( $dupes as $key => $dupe ) {
			if ( $title->equals( $dupe->getTitle() ) ) {
				unset( $dupes[$key] );
			}
		}
		if ( $dupes ) {
			$warnings['duplicate'] = $dupes;
		}

		// Check dupes against archives
		$archivedFile = new ArchivedFile( null, 0, '', $hash );
		if ( $archivedFile->getID() > 0 ) {
			if ( $archivedFile->userCan( File::DELETED_FILE ) ) {
				$warnings['duplicate-archive'] = $archivedFile->getName();
			} else {
				$warnings['duplicate-archive'] = '';
			}
		}

		return $warnings;
	}

	/**
	 * Really perform the upload. Stores the file in the local repo, watches
	 * if necessary and runs the UploadComplete hook.
	 *
	 * @param string $comment
	 * @param string $pageText
	 * @param bool $watch Whether the file page should be added to user's watchlist.
	 *   (This doesn't check $user's permissions.)
	 * @param User $user
	 * @param string[] $tags Change tags to add to the log entry and page revision.
	 *   (This doesn't check $user's permissions.)
	 * @return Status Indicating the whether the upload succeeded.
	 */
	public function performUpload( $comment, $pageText, $watch, $user, $tags = [] ) {
		$this->getLocalFile()->load( File::READ_LATEST );
		$props = $this->mFileProps;

		$error = null;
		Hooks::run( 'UploadVerifyUpload', [ $this, $user, $props, $comment, $pageText, &$error ] );
		if ( $error ) {
			if ( !is_array( $error ) ) {
				$error = [ $error ];
			}
			return call_user_func_array( 'Status::newFatal', $error );
		}

		$status = $this->getLocalFile()->upload(
			$this->mTempPath,
			$comment,
			$pageText,
			File::DELETE_SOURCE,
			$props,
			false,
			$user,
			$tags
		);

		if ( $status->isGood() ) {
			if ( $watch ) {
				WatchAction::doWatch(
					$this->getLocalFile()->getTitle(),
					$user,
					User::IGNORE_USER_RIGHTS
				);
			}
			// Avoid PHP 7.1 warning of passing $this by reference
			$uploadBase = $this;
			Hooks::run( 'UploadComplete', [ &$uploadBase ] );

			$this->postProcessUpload();
		}

		return $status;
	}

	/**
	 * Perform extra steps after a successful upload.
	 *
	 * @since  1.25
	 */
	public function postProcessUpload() {
	}

	/**
	 * Returns the title of the file to be uploaded. Sets mTitleError in case
	 * the name was illegal.
	 *
	 * @return Title|null The title of the file or null in case the name was illegal
	 */
	public function getTitle() {
		if ( $this->mTitle !== false ) {
			return $this->mTitle;
		}
		if ( !is_string( $this->mDesiredDestName ) ) {
			$this->mTitleError = self::ILLEGAL_FILENAME;
			$this->mTitle = null;

			return $this->mTitle;
		}
		/* Assume that if a user specified File:Something.jpg, this is an error
		 * and that the namespace prefix needs to be stripped of.
		 */
		$title = Title::newFromText( $this->mDesiredDestName );
		if ( $title && $title->getNamespace() == NS_FILE ) {
			$this->mFilteredName = $title->getDBkey();
		} else {
			$this->mFilteredName = $this->mDesiredDestName;
		}

		# oi_archive_name is max 255 bytes, which include a timestamp and an
		# exclamation mark, so restrict file name to 240 bytes.
		if ( strlen( $this->mFilteredName ) > 240 ) {
			$this->mTitleError = self::FILENAME_TOO_LONG;
			$this->mTitle = null;

			return $this->mTitle;
		}

		/**
		 * Chop off any directories in the given filename. Then
		 * filter out illegal characters, and try to make a legible name
		 * out of it. We'll strip some silently that Title would die on.
		 */
		$this->mFilteredName = wfStripIllegalFilenameChars( $this->mFilteredName );
		/* Normalize to title form before we do any further processing */
		$nt = Title::makeTitleSafe( NS_FILE, $this->mFilteredName );
		if ( is_null( $nt ) ) {
			$this->mTitleError = self::ILLEGAL_FILENAME;
			$this->mTitle = null;

			return $this->mTitle;
		}
		$this->mFilteredName = $nt->getDBkey();

		/**
		 * We'll want to blacklist against *any* 'extension', and use
		 * only the final one for the whitelist.
		 */
		list( $partname, $ext ) = $this->splitExtensions( $this->mFilteredName );

		if ( count( $ext ) ) {
			$this->mFinalExtension = trim( $ext[count( $ext ) - 1] );
		} else {
			$this->mFinalExtension = '';

			# No extension, try guessing one
			$magic = MimeMagic::singleton();
			$mime = $magic->guessMimeType( $this->mTempPath );
			if ( $mime !== 'unknown/unknown' ) {
				# Get a space separated list of extensions
				$extList = $magic->getExtensionsForType( $mime );
				if ( $extList ) {
					# Set the extension to the canonical extension
					$this->mFinalExtension = strtok( $extList, ' ' );

					# Fix up the other variables
					$this->mFilteredName .= ".{$this->mFinalExtension}";
					$nt = Title::makeTitleSafe( NS_FILE, $this->mFilteredName );
					$ext = [ $this->mFinalExtension ];
				}
			}
		}

		/* Don't allow users to override the blacklist (check file extension) */
		global $wgCheckFileExtensions, $wgStrictFileExtensions;
		global $wgFileExtensions, $wgFileBlacklist;

		$blackListedExtensions = $this->checkFileExtensionList( $ext, $wgFileBlacklist );

		if ( $this->mFinalExtension == '' ) {
			$this->mTitleError = self::FILETYPE_MISSING;
			$this->mTitle = null;

			return $this->mTitle;
		} elseif ( $blackListedExtensions ||
			( $wgCheckFileExtensions && $wgStrictFileExtensions &&
				!$this->checkFileExtension( $this->mFinalExtension, $wgFileExtensions ) )
		) {
			$this->mBlackListedExtensions = $blackListedExtensions;
			$this->mTitleError = self::FILETYPE_BADTYPE;
			$this->mTitle = null;

			return $this->mTitle;
		}

		// Windows may be broken with special characters, see T3780
		if ( !preg_match( '/^[\x0-\x7f]*$/', $nt->getText() )
			&& !RepoGroup::singleton()->getLocalRepo()->backendSupportsUnicodePaths()
		) {
			$this->mTitleError = self::WINDOWS_NONASCII_FILENAME;
			$this->mTitle = null;

			return $this->mTitle;
		}

		# If there was more than one "extension", reassemble the base
		# filename to prevent bogus complaints about length
		if ( count( $ext ) > 1 ) {
			$iterations = count( $ext ) - 1;
			for ( $i = 0; $i < $iterations; $i++ ) {
				$partname .= '.' . $ext[$i];
			}
		}

		if ( strlen( $partname ) < 1 ) {
			$this->mTitleError = self::MIN_LENGTH_PARTNAME;
			$this->mTitle = null;

			return $this->mTitle;
		}

		$this->mTitle = $nt;

		return $this->mTitle;
	}

	/**
	 * Return the local file and initializes if necessary.
	 *
	 * @return LocalFile|null
	 */
	public function getLocalFile() {
		if ( is_null( $this->mLocalFile ) ) {
			$nt = $this->getTitle();
			$this->mLocalFile = is_null( $nt ) ? null : wfLocalFile( $nt );
		}

		return $this->mLocalFile;
	}

	/**
	 * @return UploadStashFile|null
	 */
	public function getStashFile() {
		return $this->mStashFile;
	}

	/**
	 * Like stashFile(), but respects extensions' wishes to prevent the stashing. verifyUpload() must
	 * be called before calling this method (unless $isPartial is true).
	 *
	 * Upload stash exceptions are also caught and converted to an error status.
	 *
	 * @since 1.28
	 * @param User $user
	 * @param bool $isPartial Pass `true` if this is a part of a chunked upload (not a complete file).
	 * @return Status If successful, value is an UploadStashFile instance
	 */
	public function tryStashFile( User $user, $isPartial = false ) {
		if ( !$isPartial ) {
			$error = $this->runUploadStashFileHook( $user );
			if ( $error ) {
				return call_user_func_array( 'Status::newFatal', $error );
			}
		}
		try {
			$file = $this->doStashFile( $user );
			return Status::newGood( $file );
		} catch ( UploadStashException $e ) {
			return Status::newFatal( 'uploadstash-exception', get_class( $e ), $e->getMessage() );
		}
	}

	/**
	 * @param User $user
	 * @return array|null Error message and parameters, null if there's no error
	 */
	protected function runUploadStashFileHook( User $user ) {
		$props = $this->mFileProps;
		$error = null;
		Hooks::run( 'UploadStashFile', [ $this, $user, $props, &$error ] );
		if ( $error ) {
			if ( !is_array( $error ) ) {
				$error = [ $error ];
			}
		}
		return $error;
	}

	/**
	 * If the user does not supply all necessary information in the first upload
	 * form submission (either by accident or by design) then we may want to
	 * stash the file temporarily, get more information, and publish the file
	 * later.
	 *
	 * This method will stash a file in a temporary directory for later
	 * processing, and save the necessary descriptive info into the database.
	 * This method returns the file object, which also has a 'fileKey' property
	 * which can be passed through a form or API request to find this stashed
	 * file again.
	 *
	 * @deprecated since 1.28 Use tryStashFile() instead
	 * @param User $user
	 * @return UploadStashFile Stashed file
	 * @throws UploadStashBadPathException
	 * @throws UploadStashFileException
	 * @throws UploadStashNotLoggedInException
	 */
	public function stashFile( User $user = null ) {
		return $this->doStashFile( $user );
	}

	/**
	 * Implementation for stashFile() and tryStashFile().
	 *
	 * @param User $user
	 * @return UploadStashFile Stashed file
	 */
	protected function doStashFile( User $user = null ) {
		$stash = RepoGroup::singleton()->getLocalRepo()->getUploadStash( $user );
		$file = $stash->stashFile( $this->mTempPath, $this->getSourceType() );
		$this->mStashFile = $file;

		return $file;
	}

	/**
	 * Stash a file in a temporary directory, returning a key which can be used
	 * to find the file again. See stashFile().
	 *
	 * @deprecated since 1.28
	 * @return string File key
	 */
	public function stashFileGetKey() {
		wfDeprecated( __METHOD__, '1.28' );
		return $this->doStashFile()->getFileKey();
	}

	/**
	 * alias for stashFileGetKey, for backwards compatibility
	 *
	 * @deprecated since 1.28
	 * @return string File key
	 */
	public function stashSession() {
		wfDeprecated( __METHOD__, '1.28' );
		return $this->doStashFile()->getFileKey();
	}

	/**
	 * If we've modified the upload file we need to manually remove it
	 * on exit to clean up.
	 */
	public function cleanupTempFile() {
		if ( $this->mRemoveTempFile && $this->tempFileObj ) {
			// Delete when all relevant TempFSFile handles go out of scope
			wfDebug( __METHOD__ . ": Marked temporary file '{$this->mTempPath}' for removal\n" );
			$this->tempFileObj->autocollect();
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
	 * @param string $filename
	 * @return array
	 */
	public static function splitExtensions( $filename ) {
		$bits = explode( '.', $filename );
		$basename = array_shift( $bits );

		return [ $basename, $bits ];
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
	 * Returns an array of matching extensions.
	 *
	 * @param array $ext
	 * @param array $list
	 * @return bool
	 */
	public static function checkFileExtensionList( $ext, $list ) {
		return array_intersect( array_map( 'strtolower', $ext ), $list );
	}

	/**
	 * Checks if the MIME type of the uploaded file matches the file extension.
	 *
	 * @param string $mime The MIME type of the uploaded file
	 * @param string $extension The filename extension that the file is to be served with
	 * @return bool
	 */
	public static function verifyExtension( $mime, $extension ) {
		$magic = MimeMagic::singleton();

		if ( !$mime || $mime == 'unknown' || $mime == 'unknown/unknown' ) {
			if ( !$magic->isRecognizableExtension( $extension ) ) {
				wfDebug( __METHOD__ . ": passing file with unknown detected mime type; " .
					"unrecognized extension '$extension', can't verify\n" );

				return true;
			} else {
				wfDebug( __METHOD__ . ": rejecting file with unknown detected mime type; " .
					"recognized extension '$extension', so probably invalid file\n" );

				return false;
			}
		}

		$match = $magic->isMatchingExtension( $extension, $mime );

		if ( $match === null ) {
			if ( $magic->getTypesForExtension( $extension ) !== null ) {
				wfDebug( __METHOD__ . ": No extension known for $mime, but we know a mime for $extension\n" );

				return false;
			} else {
				wfDebug( __METHOD__ . ": no file extension known for mime type $mime, passing file\n" );

				return true;
			}
		} elseif ( $match === true ) {
			wfDebug( __METHOD__ . ": mime type $mime matches extension $extension, passing file\n" );

			/** @todo If it's a bitmap, make sure PHP or ImageMagick resp. can handle it! */
			return true;
		} else {
			wfDebug( __METHOD__
				. ": mime type $mime mismatches file extension $extension, rejecting file\n" );

			return false;
		}
	}

	/**
	 * Heuristic for detecting files that *could* contain JavaScript instructions or
	 * things that may look like HTML to a browser and are thus
	 * potentially harmful. The present implementation will produce false
	 * positives in some situations.
	 *
	 * @param string $file Pathname to the temporary upload file
	 * @param string $mime The MIME type of the file
	 * @param string $extension The extension of the file
	 * @return bool True if the file contains something looking like embedded scripts
	 */
	public static function detectScript( $file, $mime, $extension ) {
		global $wgAllowTitlesInSVG;

		# ugly hack: for text files, always look at the entire file.
		# For binary field, just check the first K.

		if ( strpos( $mime, 'text/' ) === 0 ) {
			$chunk = file_get_contents( $file );
		} else {
			$fp = fopen( $file, 'rb' );
			$chunk = fread( $fp, 1024 );
			fclose( $fp );
		}

		$chunk = strtolower( $chunk );

		if ( !$chunk ) {
			return false;
		}

		# decode from UTF-16 if needed (could be used for obfuscation).
		if ( substr( $chunk, 0, 2 ) == "\xfe\xff" ) {
			$enc = 'UTF-16BE';
		} elseif ( substr( $chunk, 0, 2 ) == "\xff\xfe" ) {
			$enc = 'UTF-16LE';
		} else {
			$enc = null;
		}

		if ( $enc ) {
			$chunk = iconv( $enc, "ASCII//IGNORE", $chunk );
		}

		$chunk = trim( $chunk );

		/** @todo FIXME: Convert from UTF-16 if necessary! */
		wfDebug( __METHOD__ . ": checking for embedded scripts and HTML stuff\n" );

		# check for HTML doctype
		if ( preg_match( "/<!DOCTYPE *X?HTML/i", $chunk ) ) {
			return true;
		}

		// Some browsers will interpret obscure xml encodings as UTF-8, while
		// PHP/expat will interpret the given encoding in the xml declaration (T49304)
		if ( $extension == 'svg' || strpos( $mime, 'image/svg' ) === 0 ) {
			if ( self::checkXMLEncodingMissmatch( $file ) ) {
				return true;
			}
		}

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
		$tags = [
			'<a href',
			'<body',
			'<head',
			'<html', # also in safari
			'<img',
			'<pre',
			'<script', # also in safari
			'<table'
		];

		if ( !$wgAllowTitlesInSVG && $extension !== 'svg' && $mime !== 'image/svg' ) {
			$tags[] = '<title';
		}

		foreach ( $tags as $tag ) {
			if ( false !== strpos( $chunk, $tag ) ) {
				wfDebug( __METHOD__ . ": found something that may make it be mistaken for html: $tag\n" );

				return true;
			}
		}

		/*
		 * look for JavaScript
		 */

		# resolve entity-refs to look at attributes. may be harsh on big files... cache result?
		$chunk = Sanitizer::decodeCharReferences( $chunk );

		# look for script-types
		if ( preg_match( '!type\s*=\s*[\'"]?\s*(?:\w*/)?(?:ecma|java)!sim', $chunk ) ) {
			wfDebug( __METHOD__ . ": found script types\n" );

			return true;
		}

		# look for html-style script-urls
		if ( preg_match( '!(?:href|src|data)\s*=\s*[\'"]?\s*(?:ecma|java)script:!sim', $chunk ) ) {
			wfDebug( __METHOD__ . ": found html-style script urls\n" );

			return true;
		}

		# look for css-style script-urls
		if ( preg_match( '!url\s*\(\s*[\'"]?\s*(?:ecma|java)script:!sim', $chunk ) ) {
			wfDebug( __METHOD__ . ": found css-style script urls\n" );

			return true;
		}

		wfDebug( __METHOD__ . ": no scripts found\n" );

		return false;
	}

	/**
	 * Check a whitelist of xml encodings that are known not to be interpreted differently
	 * by the server's xml parser (expat) and some common browsers.
	 *
	 * @param string $file Pathname to the temporary upload file
	 * @return bool True if the file contains an encoding that could be misinterpreted
	 */
	public static function checkXMLEncodingMissmatch( $file ) {
		global $wgSVGMetadataCutoff;
		$contents = file_get_contents( $file, false, null, -1, $wgSVGMetadataCutoff );
		$encodingRegex = '!encoding[ \t\n\r]*=[ \t\n\r]*[\'"](.*?)[\'"]!si';

		if ( preg_match( "!<\?xml\b(.*?)\?>!si", $contents, $matches ) ) {
			if ( preg_match( $encodingRegex, $matches[1], $encMatch )
				&& !in_array( strtoupper( $encMatch[1] ), self::$safeXmlEncodings )
			) {
				wfDebug( __METHOD__ . ": Found unsafe XML encoding '{$encMatch[1]}'\n" );

				return true;
			}
		} elseif ( preg_match( "!<\?xml\b!si", $contents ) ) {
			// Start of XML declaration without an end in the first $wgSVGMetadataCutoff
			// bytes. There shouldn't be a legitimate reason for this to happen.
			wfDebug( __METHOD__ . ": Unmatched XML declaration start\n" );

			return true;
		} elseif ( substr( $contents, 0, 4 ) == "\x4C\x6F\xA7\x94" ) {
			// EBCDIC encoded XML
			wfDebug( __METHOD__ . ": EBCDIC Encoded XML\n" );

			return true;
		}

		// It's possible the file is encoded with multi-byte encoding, so re-encode attempt to
		// detect the encoding in case is specifies an encoding not whitelisted in self::$safeXmlEncodings
		$attemptEncodings = [ 'UTF-16', 'UTF-16BE', 'UTF-32', 'UTF-32BE' ];
		foreach ( $attemptEncodings as $encoding ) {
			MediaWiki\suppressWarnings();
			$str = iconv( $encoding, 'UTF-8', $contents );
			MediaWiki\restoreWarnings();
			if ( $str != '' && preg_match( "!<\?xml\b(.*?)\?>!si", $str, $matches ) ) {
				if ( preg_match( $encodingRegex, $matches[1], $encMatch )
					&& !in_array( strtoupper( $encMatch[1] ), self::$safeXmlEncodings )
				) {
					wfDebug( __METHOD__ . ": Found unsafe XML encoding '{$encMatch[1]}'\n" );

					return true;
				}
			} elseif ( $str != '' && preg_match( "!<\?xml\b!si", $str ) ) {
				// Start of XML declaration without an end in the first $wgSVGMetadataCutoff
				// bytes. There shouldn't be a legitimate reason for this to happen.
				wfDebug( __METHOD__ . ": Unmatched XML declaration start\n" );

				return true;
			}
		}

		return false;
	}

	/**
	 * @param string $filename
	 * @param bool $partial
	 * @return mixed False of the file is verified (does not contain scripts), array otherwise.
	 */
	protected function detectScriptInSvg( $filename, $partial ) {
		$this->mSVGNSError = false;
		$check = new XmlTypeCheck(
			$filename,
			[ $this, 'checkSvgScriptCallback' ],
			true,
			[
				'processing_instruction_handler' => 'UploadBase::checkSvgPICallback',
				'external_dtd_handler' => 'UploadBase::checkSvgExternalDTD',
			]
		);
		if ( $check->wellFormed !== true ) {
			// Invalid xml (T60553)
			// But only when non-partial (T67724)
			return $partial ? false : [ 'uploadinvalidxml' ];
		} elseif ( $check->filterMatch ) {
			if ( $this->mSVGNSError ) {
				return [ 'uploadscriptednamespace', $this->mSVGNSError ];
			}

			return $check->filterMatchType;
		}

		return false;
	}

	/**
	 * Callback to filter SVG Processing Instructions.
	 * @param string $target Processing instruction name
	 * @param string $data Processing instruction attribute and value
	 * @return bool (true if the filter identified something bad)
	 */
	public static function checkSvgPICallback( $target, $data ) {
		// Don't allow external stylesheets (T59550)
		if ( preg_match( '/xml-stylesheet/i', $target ) ) {
			return [ 'upload-scripted-pi-callback' ];
		}

		return false;
	}

	/**
	 * Verify that DTD urls referenced are only the standard dtds
	 *
	 * Browsers seem to ignore external dtds. However just to be on the
	 * safe side, only allow dtds from the svg standard.
	 *
	 * @param string $type PUBLIC or SYSTEM
	 * @param string $publicId The well-known public identifier for the dtd
	 * @param string $systemId The url for the external dtd
	 */
	public static function checkSvgExternalDTD( $type, $publicId, $systemId ) {
		// This doesn't include the XHTML+MathML+SVG doctype since we don't
		// allow XHTML anyways.
		$allowedDTDs = [
			'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd',
			'http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd',
			'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11-basic.dtd',
			'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11-tiny.dtd',
			// https://phabricator.wikimedia.org/T168856
			'http://www.w3.org/TR/2001/PR-SVG-20010719/DTD/svg10.dtd',
		];
		if ( $type !== 'PUBLIC'
			|| !in_array( $systemId, $allowedDTDs )
			|| strpos( $publicId, "-//W3C//" ) !== 0
		) {
			return [ 'upload-scripted-dtd' ];
		}
		return false;
	}

	/**
	 * @todo Replace this with a whitelist filter!
	 * @param string $element
	 * @param array $attribs
	 * @return bool
	 */
	public function checkSvgScriptCallback( $element, $attribs, $data = null ) {

		list( $namespace, $strippedElement ) = $this->splitXmlNamespace( $element );

		// We specifically don't include:
		// http://www.w3.org/1999/xhtml (T62771)
		static $validNamespaces = [
			'',
			'adobe:ns:meta/',
			'http://creativecommons.org/ns#',
			'http://inkscape.sourceforge.net/dtd/sodipodi-0.dtd',
			'http://ns.adobe.com/adobeillustrator/10.0/',
			'http://ns.adobe.com/adobesvgviewerextensions/3.0/',
			'http://ns.adobe.com/extensibility/1.0/',
			'http://ns.adobe.com/flows/1.0/',
			'http://ns.adobe.com/illustrator/1.0/',
			'http://ns.adobe.com/imagereplacement/1.0/',
			'http://ns.adobe.com/pdf/1.3/',
			'http://ns.adobe.com/photoshop/1.0/',
			'http://ns.adobe.com/saveforweb/1.0/',
			'http://ns.adobe.com/variables/1.0/',
			'http://ns.adobe.com/xap/1.0/',
			'http://ns.adobe.com/xap/1.0/g/',
			'http://ns.adobe.com/xap/1.0/g/img/',
			'http://ns.adobe.com/xap/1.0/mm/',
			'http://ns.adobe.com/xap/1.0/rights/',
			'http://ns.adobe.com/xap/1.0/stype/dimensions#',
			'http://ns.adobe.com/xap/1.0/stype/font#',
			'http://ns.adobe.com/xap/1.0/stype/manifestitem#',
			'http://ns.adobe.com/xap/1.0/stype/resourceevent#',
			'http://ns.adobe.com/xap/1.0/stype/resourceref#',
			'http://ns.adobe.com/xap/1.0/t/pg/',
			'http://purl.org/dc/elements/1.1/',
			'http://purl.org/dc/elements/1.1',
			'http://schemas.microsoft.com/visio/2003/svgextensions/',
			'http://sodipodi.sourceforge.net/dtd/sodipodi-0.dtd',
			'http://taptrix.com/inkpad/svg_extensions',
			'http://web.resource.org/cc/',
			'http://www.freesoftware.fsf.org/bkchem/cdml',
			'http://www.inkscape.org/namespaces/inkscape',
			'http://www.opengis.net/gml',
			'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
			'http://www.w3.org/2000/svg',
			'http://www.w3.org/tr/rec-rdf-syntax/',
			'http://www.w3.org/2000/01/rdf-schema#',
		];

		// Inkscape mangles namespace definitions created by Adobe Illustrator.
		// This is nasty but harmless. (T144827)
		$isBuggyInkscape = preg_match( '/^&(#38;)*ns_[a-z_]+;$/', $namespace );

		if ( !( $isBuggyInkscape || in_array( $namespace, $validNamespaces ) ) ) {
			wfDebug( __METHOD__ . ": Non-svg namespace '$namespace' in uploaded file.\n" );
			/** @todo Return a status object to a closure in XmlTypeCheck, for MW1.21+ */
			$this->mSVGNSError = $namespace;

			return true;
		}

		/*
		 * check for elements that can contain javascript
		 */
		if ( $strippedElement == 'script' ) {
			wfDebug( __METHOD__ . ": Found script element '$element' in uploaded file.\n" );

			return [ 'uploaded-script-svg', $strippedElement ];
		}

		# e.g., <svg xmlns="http://www.w3.org/2000/svg">
		#  <handler xmlns:ev="http://www.w3.org/2001/xml-events" ev:event="load">alert(1)</handler> </svg>
		if ( $strippedElement == 'handler' ) {
			wfDebug( __METHOD__ . ": Found scriptable element '$element' in uploaded file.\n" );

			return [ 'uploaded-script-svg', $strippedElement ];
		}

		# SVG reported in Feb '12 that used xml:stylesheet to generate javascript block
		if ( $strippedElement == 'stylesheet' ) {
			wfDebug( __METHOD__ . ": Found scriptable element '$element' in uploaded file.\n" );

			return [ 'uploaded-script-svg', $strippedElement ];
		}

		# Block iframes, in case they pass the namespace check
		if ( $strippedElement == 'iframe' ) {
			wfDebug( __METHOD__ . ": iframe in uploaded file.\n" );

			return [ 'uploaded-script-svg', $strippedElement ];
		}

		# Check <style> css
		if ( $strippedElement == 'style'
			&& self::checkCssFragment( Sanitizer::normalizeCss( $data ) )
		) {
			wfDebug( __METHOD__ . ": hostile css in style element.\n" );
			return [ 'uploaded-hostile-svg' ];
		}

		foreach ( $attribs as $attrib => $value ) {
			$stripped = $this->stripXmlNamespace( $attrib );
			$value = strtolower( $value );

			if ( substr( $stripped, 0, 2 ) == 'on' ) {
				wfDebug( __METHOD__
					. ": Found event-handler attribute '$attrib'='$value' in uploaded file.\n" );

				return [ 'uploaded-event-handler-on-svg', $attrib, $value ];
			}

			# Do not allow relative links, or unsafe url schemas.
			# For <a> tags, only data:, http: and https: and same-document
			# fragment links are allowed. For all other tags, only data:
			# and fragment are allowed.
			if ( $stripped == 'href'
				&& $value !== ''
				&& strpos( $value, 'data:' ) !== 0
				&& strpos( $value, '#' ) !== 0
			) {
				if ( !( $strippedElement === 'a'
					&& preg_match( '!^https?://!i', $value ) )
				) {
					wfDebug( __METHOD__ . ": Found href attribute <$strippedElement "
						. "'$attrib'='$value' in uploaded file.\n" );

					return [ 'uploaded-href-attribute-svg', $strippedElement, $attrib, $value ];
				}
			}

			# only allow data: targets that should be safe. This prevents vectors like,
			# image/svg, text/xml, application/xml, and text/html, which can contain scripts
			if ( $stripped == 'href' && strncasecmp( 'data:', $value, 5 ) === 0 ) {
				// rfc2397 parameters. This is only slightly slower than (;[\w;]+)*.
				// @codingStandardsIgnoreStart Generic.Files.LineLength
				$parameters = '(?>;[a-zA-Z0-9\!#$&\'*+.^_`{|}~-]+=(?>[a-zA-Z0-9\!#$&\'*+.^_`{|}~-]+|"(?>[\0-\x0c\x0e-\x21\x23-\x5b\x5d-\x7f]+|\\\\[\0-\x7f])*"))*(?:;base64)?';
				// @codingStandardsIgnoreEnd

				if ( !preg_match( "!^data:\s*image/(gif|jpeg|jpg|png)$parameters,!i", $value ) ) {
					wfDebug( __METHOD__ . ": Found href to unwhitelisted data: uri "
						. "\"<$strippedElement '$attrib'='$value'...\" in uploaded file.\n" );
					return [ 'uploaded-href-unsafe-target-svg', $strippedElement, $attrib, $value ];
				}
			}

			# Change href with animate from (http://html5sec.org/#137).
			if ( $stripped === 'attributename'
				&& $strippedElement === 'animate'
				&& $this->stripXmlNamespace( $value ) == 'href'
			) {
				wfDebug( __METHOD__ . ": Found animate that might be changing href using from "
					. "\"<$strippedElement '$attrib'='$value'...\" in uploaded file.\n" );

				return [ 'uploaded-animate-svg', $strippedElement, $attrib, $value ];
			}

			# use set/animate to add event-handler attribute to parent
			if ( ( $strippedElement == 'set' || $strippedElement == 'animate' )
				&& $stripped == 'attributename'
				&& substr( $value, 0, 2 ) == 'on'
			) {
				wfDebug( __METHOD__ . ": Found svg setting event-handler attribute with "
					. "\"<$strippedElement $stripped='$value'...\" in uploaded file.\n" );

				return [ 'uploaded-setting-event-handler-svg', $strippedElement, $stripped, $value ];
			}

			# use set to add href attribute to parent element
			if ( $strippedElement == 'set'
				&& $stripped == 'attributename'
				&& strpos( $value, 'href' ) !== false
			) {
				wfDebug( __METHOD__ . ": Found svg setting href attribute '$value' in uploaded file.\n" );

				return [ 'uploaded-setting-href-svg' ];
			}

			# use set to add a remote / data / script target to an element
			if ( $strippedElement == 'set'
				&& $stripped == 'to'
				&& preg_match( '!(http|https|data|script):!sim', $value )
			) {
				wfDebug( __METHOD__ . ": Found svg setting attribute to '$value' in uploaded file.\n" );

				return [ 'uploaded-wrong-setting-svg', $value ];
			}

			# use handler attribute with remote / data / script
			if ( $stripped == 'handler' && preg_match( '!(http|https|data|script):!sim', $value ) ) {
				wfDebug( __METHOD__ . ": Found svg setting handler with remote/data/script "
					. "'$attrib'='$value' in uploaded file.\n" );

				return [ 'uploaded-setting-handler-svg', $attrib, $value ];
			}

			# use CSS styles to bring in remote code
			if ( $stripped == 'style'
				&& self::checkCssFragment( Sanitizer::normalizeCss( $value ) )
			) {
				wfDebug( __METHOD__ . ": Found svg setting a style with "
					. "remote url '$attrib'='$value' in uploaded file.\n" );
				return [ 'uploaded-remote-url-svg', $attrib, $value ];
			}

			# Several attributes can include css, css character escaping isn't allowed
			$cssAttrs = [ 'font', 'clip-path', 'fill', 'filter', 'marker',
				'marker-end', 'marker-mid', 'marker-start', 'mask', 'stroke' ];
			if ( in_array( $stripped, $cssAttrs )
				&& self::checkCssFragment( $value )
			) {
				wfDebug( __METHOD__ . ": Found svg setting a style with "
					. "remote url '$attrib'='$value' in uploaded file.\n" );
				return [ 'uploaded-remote-url-svg', $attrib, $value ];
			}

			# image filters can pull in url, which could be svg that executes scripts
			if ( $strippedElement == 'image'
				&& $stripped == 'filter'
				&& preg_match( '!url\s*\(!sim', $value )
			) {
				wfDebug( __METHOD__ . ": Found image filter with url: "
					. "\"<$strippedElement $stripped='$value'...\" in uploaded file.\n" );

				return [ 'uploaded-image-filter-svg', $strippedElement, $stripped, $value ];
			}
		}

		return false; // No scripts detected
	}

	/**
	 * Check a block of CSS or CSS fragment for anything that looks like
	 * it is bringing in remote code.
	 * @param string $value a string of CSS
	 * @param bool $propOnly only check css properties (start regex with :)
	 * @return bool true if the CSS contains an illegal string, false if otherwise
	 */
	private static function checkCssFragment( $value ) {

		# Forbid external stylesheets, for both reliability and to protect viewer's privacy
		if ( stripos( $value, '@import' ) !== false ) {
			return true;
		}

		# We allow @font-face to embed fonts with data: urls, so we snip the string
		# 'url' out so this case won't match when we check for urls below
		$pattern = '!(@font-face\s*{[^}]*src:)url(\("data:;base64,)!im';
		$value = preg_replace( $pattern, '$1$2', $value );

		# Check for remote and executable CSS. Unlike in Sanitizer::checkCss, the CSS
		# properties filter and accelerator don't seem to be useful for xss in SVG files.
		# Expression and -o-link don't seem to work either, but filtering them here in case.
		# Additionally, we catch remote urls like url("http:..., url('http:..., url(http:...,
		# but not local ones such as url("#..., url('#..., url(#....
		if ( preg_match( '!expression
				| -o-link\s*:
				| -o-link-source\s*:
				| -o-replace\s*:!imx', $value ) ) {
			return true;
		}

		if ( preg_match_all(
				"!(\s*(url|image|image-set)\s*\(\s*[\"']?\s*[^#]+.*?\))!sim",
				$value,
				$matches
			) !== 0
		) {
			# TODO: redo this in one regex. Until then, url("#whatever") matches the first
			foreach ( $matches[1] as $match ) {
				if ( !preg_match( "!\s*(url|image|image-set)\s*\(\s*(#|'#|\"#)!im", $match ) ) {
					return true;
				}
			}
		}

		if ( preg_match( '/[\000-\010\013\016-\037\177]/', $value ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Divide the element name passed by the xml parser to the callback into URI and prifix.
	 * @param string $element
	 * @return array Containing the namespace URI and prefix
	 */
	private static function splitXmlNamespace( $element ) {
		// 'http://www.w3.org/2000/svg:script' -> [ 'http://www.w3.org/2000/svg', 'script' ]
		$parts = explode( ':', strtolower( $element ) );
		$name = array_pop( $parts );
		$ns = implode( ':', $parts );

		return [ $ns, $name ];
	}

	/**
	 * @param string $name
	 * @return string
	 */
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
	 * @return mixed False if not virus is found, null if the scan fails or is disabled,
	 *   or a string containing feedback from the virus scanner if a virus was found.
	 *   If textual feedback is missing but a virus was found, this function returns true.
	 */
	public static function detectVirus( $file ) {
		global $wgAntivirus, $wgAntivirusSetup, $wgAntivirusRequired, $wgOut;

		if ( !$wgAntivirus ) {
			wfDebug( __METHOD__ . ": virus scanner disabled\n" );

			return null;
		}

		if ( !$wgAntivirusSetup[$wgAntivirus] ) {
			wfDebug( __METHOD__ . ": unknown virus scanner: $wgAntivirus\n" );
			$wgOut->wrapWikiMsg( "<div class=\"error\">\n$1\n</div>",
				[ 'virus-badscanner', $wgAntivirus ] );

			return wfMessage( 'virus-unknownscanner' )->text() . " $wgAntivirus";
		}

		# look up scanner configuration
		$command = $wgAntivirusSetup[$wgAntivirus]['command'];
		$exitCodeMap = $wgAntivirusSetup[$wgAntivirus]['codemap'];
		$msgPattern = isset( $wgAntivirusSetup[$wgAntivirus]['messagepattern'] ) ?
			$wgAntivirusSetup[$wgAntivirus]['messagepattern'] : null;

		if ( strpos( $command, "%f" ) === false ) {
			# simple pattern: append file to scan
			$command .= " " . wfEscapeShellArg( $file );
		} else {
			# complex pattern: replace "%f" with file to scan
			$command = str_replace( "%f", wfEscapeShellArg( $file ), $command );
		}

		wfDebug( __METHOD__ . ": running virus scan: $command \n" );

		# execute virus scanner
		$exitCode = false;

		# NOTE: there's a 50 line workaround to make stderr redirection work on windows, too.
		#      that does not seem to be worth the pain.
		#      Ask me (Duesentrieb) about it if it's ever needed.
		$output = wfShellExecWithStderr( $command, $exitCode );

		# map exit code to AV_xxx constants.
		$mappedCode = $exitCode;
		if ( $exitCodeMap ) {
			if ( isset( $exitCodeMap[$exitCode] ) ) {
				$mappedCode = $exitCodeMap[$exitCode];
			} elseif ( isset( $exitCodeMap["*"] ) ) {
				$mappedCode = $exitCodeMap["*"];
			}
		}

		/* NB: AV_NO_VIRUS is 0 but AV_SCAN_FAILED is false,
		 * so we need the strict equalities === and thus can't use a switch here
		 */
		if ( $mappedCode === AV_SCAN_FAILED ) {
			# scan failed (code was mapped to false by $exitCodeMap)
			wfDebug( __METHOD__ . ": failed to scan $file (code $exitCode).\n" );

			$output = $wgAntivirusRequired
				? wfMessage( 'virus-scanfailed', [ $exitCode ] )->text()
				: null;
		} elseif ( $mappedCode === AV_SCAN_ABORTED ) {
			# scan failed because filetype is unknown (probably imune)
			wfDebug( __METHOD__ . ": unsupported file type $file (code $exitCode).\n" );
			$output = null;
		} elseif ( $mappedCode === AV_NO_VIRUS ) {
			# no virus found
			wfDebug( __METHOD__ . ": file passed virus scan.\n" );
			$output = false;
		} else {
			$output = trim( $output );

			if ( !$output ) {
				$output = true; # if there's no output, return true
			} elseif ( $msgPattern ) {
				$groups = [];
				if ( preg_match( $msgPattern, $output, $groups ) ) {
					if ( $groups[1] ) {
						$output = $groups[1];
					}
				}
			}

			wfDebug( __METHOD__ . ": FOUND VIRUS! scanner feedback: $output \n" );
		}

		return $output;
	}

	/**
	 * Check if there's an overwrite conflict and, if so, if restrictions
	 * forbid this user from performing the upload.
	 *
	 * @param User $user
	 *
	 * @return mixed True on success, array on failure
	 */
	private function checkOverwrite( $user ) {
		// First check whether the local file can be overwritten
		$file = $this->getLocalFile();
		$file->load( File::READ_LATEST );
		if ( $file->exists() ) {
			if ( !self::userCanReUpload( $user, $file ) ) {
				return [ 'fileexists-forbidden', $file->getName() ];
			} else {
				return true;
			}
		}

		/* Check shared conflicts: if the local file does not exist, but
		 * wfFindFile finds a file, it exists in a shared repository.
		 */
		$file = wfFindFile( $this->getTitle(), [ 'latest' => true ] );
		if ( $file && !$user->isAllowed( 'reupload-shared' ) ) {
			return [ 'fileexists-shared-forbidden', $file->getName() ];
		}

		return true;
	}

	/**
	 * Check if a user is the last uploader
	 *
	 * @param User $user
	 * @param File $img
	 * @return bool
	 */
	public static function userCanReUpload( User $user, File $img ) {
		if ( $user->isAllowed( 'reupload' ) ) {
			return true; // non-conditional
		} elseif ( !$user->isAllowed( 'reupload-own' ) ) {
			return false;
		}

		if ( !( $img instanceof LocalFile ) ) {
			return false;
		}

		$img->load();

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
	 * @param File $file The File object to check
	 * @return mixed False if the file does not exists, else an array
	 */
	public static function getExistsWarning( $file ) {
		if ( $file->exists() ) {
			return [ 'warning' => 'exists', 'file' => $file ];
		}

		if ( $file->getTitle()->getArticleID() ) {
			return [ 'warning' => 'page-exists', 'file' => $file ];
		}

		if ( strpos( $file->getName(), '.' ) == false ) {
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

			// Check for another file using the normalized form...
			$nt_lc = Title::makeTitle( NS_FILE, "{$partname}.{$normalizedExtension}" );
			$file_lc = wfLocalFile( $nt_lc );

			if ( $file_lc->exists() ) {
				return [
					'warning' => 'exists-normalized',
					'file' => $file,
					'normalizedFile' => $file_lc
				];
			}
		}

		// Check for files with the same name but a different extension
		$similarFiles = RepoGroup::singleton()->getLocalRepo()->findFilesByPrefix(
			"{$partname}.", 1 );
		if ( count( $similarFiles ) ) {
			return [
				'warning' => 'exists-normalized',
				'file' => $file,
				'normalizedFile' => $similarFiles[0],
			];
		}

		if ( self::isThumbName( $file->getName() ) ) {
			# Check for filenames like 50px- or 180px-, these are mostly thumbnails
			$nt_thb = Title::newFromText(
				substr( $partname, strpos( $partname, '-' ) + 1 ) . '.' . $extension,
				NS_FILE
			);
			$file_thb = wfLocalFile( $nt_thb );
			if ( $file_thb->exists() ) {
				return [
					'warning' => 'thumb',
					'file' => $file,
					'thumbFile' => $file_thb
				];
			} else {
				// File does not exist, but we just don't like the name
				return [
					'warning' => 'thumb-name',
					'file' => $file,
					'thumbFile' => $file_thb
				];
			}
		}

		foreach ( self::getFilenamePrefixBlacklist() as $prefix ) {
			if ( substr( $partname, 0, strlen( $prefix ) ) == $prefix ) {
				return [
					'warning' => 'bad-prefix',
					'file' => $file,
					'prefix' => $prefix
				];
			}
		}

		return false;
	}

	/**
	 * Helper function that checks whether the filename looks like a thumbnail
	 * @param string $filename
	 * @return bool
	 */
	public static function isThumbName( $filename ) {
		$n = strrpos( $filename, '.' );
		$partname = $n ? substr( $filename, 0, $n ) : $filename;

		return (
			substr( $partname, 3, 3 ) == 'px-' ||
			substr( $partname, 2, 3 ) == 'px-'
		) &&
		preg_match( "/[0-9]{2}/", substr( $partname, 0, 2 ) );
	}

	/**
	 * Get a list of blacklisted filename prefixes from [[MediaWiki:Filename-prefix-blacklist]]
	 *
	 * @return array List of prefixes
	 */
	public static function getFilenamePrefixBlacklist() {
		$blacklist = [];
		$message = wfMessage( 'filename-prefix-blacklist' )->inContentLanguage();
		if ( !$message->isDisabled() ) {
			$lines = explode( "\n", $message->plain() );
			foreach ( $lines as $line ) {
				// Remove comment lines
				$comment = substr( trim( $line ), 0, 1 );
				if ( $comment == '#' || $comment == '' ) {
					continue;
				}
				// Remove additional comments after a prefix
				$comment = strpos( $line, '#' );
				if ( $comment > 0 ) {
					$line = substr( $line, 0, $comment - 1 );
				}
				$blacklist[] = trim( $line );
			}
		}

		return $blacklist;
	}

	/**
	 * Gets image info about the file just uploaded.
	 *
	 * Also has the effect of setting metadata to be an 'indexed tag name' in
	 * returned API result if 'metadata' was requested. Oddly, we have to pass
	 * the "result" object down just so it can do that with the appropriate
	 * format, presumably.
	 *
	 * @param ApiResult $result
	 * @return array Image info
	 */
	public function getImageInfo( $result ) {
		$localFile = $this->getLocalFile();
		$stashFile = $this->getStashFile();
		// Calling a different API module depending on whether the file was stashed is less than optimal.
		// In fact, calling API modules here at all is less than optimal. Maybe it should be refactored.
		if ( $stashFile ) {
			$imParam = ApiQueryStashImageInfo::getPropertyNames();
			$info = ApiQueryStashImageInfo::getInfo( $stashFile, array_flip( $imParam ), $result );
		} else {
			$imParam = ApiQueryImageInfo::getPropertyNames();
			$info = ApiQueryImageInfo::getInfo( $localFile, array_flip( $imParam ), $result );
		}

		return $info;
	}

	/**
	 * @param array $error
	 * @return Status
	 */
	public function convertVerifyErrorToStatus( $error ) {
		$code = $error['status'];
		unset( $code['status'] );

		return Status::newFatal( $this->getVerificationErrorCode( $code ), $error );
	}

	/**
	 * Get the MediaWiki maximum uploaded file size for given type of upload, based on
	 * $wgMaxUploadSize.
	 *
	 * @param null|string $forType
	 * @return int
	 */
	public static function getMaxUploadSize( $forType = null ) {
		global $wgMaxUploadSize;

		if ( is_array( $wgMaxUploadSize ) ) {
			if ( !is_null( $forType ) && isset( $wgMaxUploadSize[$forType] ) ) {
				return $wgMaxUploadSize[$forType];
			} else {
				return $wgMaxUploadSize['*'];
			}
		} else {
			return intval( $wgMaxUploadSize );
		}
	}

	/**
	 * Get the PHP maximum uploaded file size, based on ini settings. If there is no limit or the
	 * limit can't be guessed, returns a very large number (PHP_INT_MAX).
	 *
	 * @since 1.27
	 * @return int
	 */
	public static function getMaxPhpUploadSize() {
		$phpMaxFileSize = wfShorthandToInteger(
			ini_get( 'upload_max_filesize' ) ?: ini_get( 'hhvm.server.upload.upload_max_file_size' ),
			PHP_INT_MAX
		);
		$phpMaxPostSize = wfShorthandToInteger(
			ini_get( 'post_max_size' ) ?: ini_get( 'hhvm.server.max_post_size' ),
			PHP_INT_MAX
		) ?: PHP_INT_MAX;
		return min( $phpMaxFileSize, $phpMaxPostSize );
	}

	/**
	 * Get the current status of a chunked upload (used for polling)
	 *
	 * The value will be read from cache.
	 *
	 * @param User $user
	 * @param string $statusKey
	 * @return Status[]|bool
	 */
	public static function getSessionStatus( User $user, $statusKey ) {
		$key = wfMemcKey( 'uploadstatus', $user->getId() ?: md5( $user->getName() ), $statusKey );

		return MediaWikiServices::getInstance()->getMainObjectStash()->get( $key );
	}

	/**
	 * Set the current status of a chunked upload (used for polling)
	 *
	 * The value will be set in cache for 1 day
	 *
	 * @param User $user
	 * @param string $statusKey
	 * @param array|bool $value
	 * @return void
	 */
	public static function setSessionStatus( User $user, $statusKey, $value ) {
		$key = wfMemcKey( 'uploadstatus', $user->getId() ?: md5( $user->getName() ), $statusKey );

		$cache = MediaWikiServices::getInstance()->getMainObjectStash();
		if ( $value === false ) {
			$cache->delete( $key );
		} else {
			$cache->set( $key, $value, $cache::TTL_DAY );
		}
	}
}
