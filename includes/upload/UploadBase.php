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

use MediaWiki\Api\ApiMessage;
use MediaWiki\Api\ApiResult;
use MediaWiki\Api\ApiUpload;
use MediaWiki\Context\RequestContext;
use MediaWiki\FileRepo\File\ArchivedFile;
use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\File\LocalFile;
use MediaWiki\FileRepo\FileRepo;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Request\WebRequest;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\Upload\UploadVerification;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use Wikimedia\FileBackend\FileBackend;
use Wikimedia\FileBackend\FSFile\FSFile;
use Wikimedia\FileBackend\FSFile\TempFSFile;
use Wikimedia\Message\ListType;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * @defgroup Upload Upload related
 */

/**
 * @ingroup Upload
 *
 * UploadBase and subclasses are the backend of MediaWiki's file uploads.
 * The frontends are formed by ApiUpload and SpecialUpload.
 *
 * @stable to extend
 *
 * @author Brooke Vibber
 * @author Bryan Tong Minh
 * @author Michael Dale
 */
abstract class UploadBase {
	use ProtectedHookAccessorTrait;

	/** @var string|null Local file system path to the file to upload (or a local copy) */
	protected $mTempPath;
	/** @var TempFSFile|null Wrapper to handle deleting the temp file */
	protected $tempFileObj;
	/** @var string|null */
	protected $mDesiredDestName;
	/** @var string|null */
	protected $mDestName;
	/** @var bool|null */
	protected $mRemoveTempFile;
	/** @var string|null */
	protected $mSourceType;
	/** @var Title|false|null */
	protected $mTitle = false;
	/** @var int */
	protected $mTitleError = 0;
	/** @var string|null */
	protected $mFilteredName;
	/** @var string|null */
	protected $mFinalExtension;
	/** @var LocalFile|null */
	protected $mLocalFile;
	/** @var UploadStashFile|null */
	protected $mStashFile;
	/** @var int|null */
	protected $mFileSize;
	/** @var array|null */
	protected $mFileProps;
	/** @var string[] */
	protected $mBlackListedExtensions;

	private UploadVerification $uploadVerification;

	public const SUCCESS = 0;
	public const OK = 0;
	public const EMPTY_FILE = 3;
	public const MIN_LENGTH_PARTNAME = 4;
	public const ILLEGAL_FILENAME = 5;
	public const FILETYPE_MISSING = 8;
	public const FILETYPE_BADTYPE = 9;
	public const VERIFICATION_ERROR = 10;
	public const FILE_TOO_LARGE = 12;
	public const WINDOWS_NONASCII_FILENAME = 13;
	public const FILENAME_TOO_LONG = 14;

	private const CODE_TO_STATUS = [
		self::EMPTY_FILE => 'empty-file',
		self::FILE_TOO_LARGE => 'file-too-large',
		self::FILETYPE_MISSING => 'filetype-missing',
		self::FILETYPE_BADTYPE => 'filetype-banned',
		self::MIN_LENGTH_PARTNAME => 'filename-tooshort',
		self::ILLEGAL_FILENAME => 'illegal-filename',
		self::VERIFICATION_ERROR => 'verification-error',
		self::WINDOWS_NONASCII_FILENAME => 'windows-nonascii-filename',
		self::FILENAME_TOO_LONG => 'filename-toolong',
	];

	/**
	 * @param int $error
	 * @return string
	 */
	public function getVerificationErrorCode( $error ) {
		return self::CODE_TO_STATUS[$error] ?? 'unknown-error';
	}

	/**
	 * Returns true if uploads are enabled.
	 * Can be override by subclasses.
	 * @stable to override
	 * @return bool
	 */
	public static function isEnabled() {
		$enableUploads = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::EnableUploads );

		return $enableUploads && wfIniGetBool( 'file_uploads' );
	}

	/**
	 * Returns true if the user can use this upload module or else a string
	 * identifying the missing permission.
	 * Can be overridden by subclasses.
	 *
	 * @param Authority $performer
	 * @return bool|string
	 */
	public static function isAllowed( Authority $performer ) {
		foreach ( [ 'upload', 'edit' ] as $permission ) {
			if ( !$performer->isAllowed( $permission ) ) {
				return $permission;
			}
		}

		return true;
	}

	/**
	 * Returns true if the user has surpassed the upload rate limit, false otherwise.
	 *
	 * @deprecated since 1.41, use authorizeUpload() instead.
	 * Rate limit checks are now implicit in permission checks.
	 *
	 * @param User $user
	 * @return bool
	 */
	public static function isThrottled( $user ) {
		wfDeprecated( __METHOD__, '1.41' );
		return $user->pingLimiter( 'upload' );
	}

	/** @var string[] Upload handlers. Should probably just be a configuration variable. */
	private static $uploadHandlers = [ 'Stash', 'File', 'Url' ];

	/**
	 * Create a form of UploadBase depending on wpSourceType and initializes it.
	 *
	 * @param WebRequest &$request
	 * @param string|null $type
	 * @return null|self
	 */
	public static function createFromRequest( &$request, $type = null ) {
		$type = $type ?: $request->getVal( 'wpSourceType', 'File' );

		if ( !$type ) {
			return null;
		}

		// Get the upload class
		$type = ucfirst( $type );

		// Give hooks the chance to handle this request
		/** @var self|null $className */
		$className = null;
		( new HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) )
			// @phan-suppress-next-line PhanTypeMismatchArgument Type mismatch on pass-by-ref args
			->onUploadCreateFromRequest( $type, $className );
		if ( $className === null ) {
			$className = 'UploadFrom' . $type;
			wfDebug( __METHOD__ . ": class name: $className" );
			if ( !in_array( $type, self::$uploadHandlers ) ) {
				return null;
			}
		}

		if ( !$className::isEnabled() || !$className::isValidRequest( $request ) ) {
			return null;
		}

		/** @var self $handler */
		$handler = new $className;

		$handler->initializeFromRequest( $request );

		return $handler;
	}

	/**
	 * Check whether a request if valid for this handler.
	 * @param WebRequest $request
	 * @return bool
	 */
	public static function isValidRequest( $request ) {
		return false;
	}

	/**
	 * Get the desired destination name.
	 * @return string|null
	 */
	public function getDesiredDestName() {
		return $this->mDesiredDestName;
	}

	/**
	 * @stable to call
	 */
	public function __construct() {
		$this->uploadVerification = MediaWikiServices::getInstance()->getUploadVerification();
	}

	/**
	 * Returns the upload type. Should be overridden by child classes.
	 *
	 * @since 1.18
	 * @stable to override
	 * @return string|null
	 */
	public function getSourceType() {
		return null;
	}

	/**
	 * @param string $name The desired destination name
	 * @param string|null $tempPath Callers should make sure this is not a storage path
	 * @param int|null $fileSize
	 * @param bool $removeTempFile (false) remove the temporary file?
	 */
	public function initializePathInfo( $name, $tempPath, $fileSize, $removeTempFile = false ) {
		$this->mDesiredDestName = $name;
		if ( FileBackend::isStoragePath( $tempPath ) ) {
			throw new InvalidArgumentException( __METHOD__ . " given storage path `$tempPath`." );
		}

		$this->setTempFile( $tempPath, $fileSize );
		$this->mRemoveTempFile = $removeTempFile;
	}

	/**
	 * Initialize from a WebRequest. Override this in a subclass.
	 *
	 * @param WebRequest &$request
	 */
	abstract public function initializeFromRequest( &$request );

	/**
	 * @param string|null $tempPath File system path to temporary file containing the upload
	 * @param int|null $fileSize
	 */
	protected function setTempFile( $tempPath, $fileSize = null ) {
		$this->mTempPath = $tempPath ?? '';
		$this->mFileSize = $fileSize ?: null;
		$this->mFileProps = null;
		if ( $this->mTempPath !== '' && file_exists( $this->mTempPath ) ) {
			$this->tempFileObj = new TempFSFile( $this->mTempPath );
			if ( !$fileSize ) {
				$this->mFileSize = filesize( $this->mTempPath );
			}
		} else {
			$this->tempFileObj = null;
		}
	}

	/**
	 * Fetch the file. Usually a no-op.
	 * @stable to override
	 * @return Status
	 */
	public function fetchFile() {
		return Status::newGood();
	}

	/**
	 * Perform checks to see if the file can be fetched. Usually a no-op.
	 * @stable to override
	 * @return Status
	 */
	public function canFetchFile() {
		return Status::newGood();
	}

	/**
	 * Return true if the file is empty.
	 * @return bool
	 */
	public function isEmptyFile() {
		return !$this->mFileSize;
	}

	/**
	 * Return the file size.
	 * @return int
	 */
	public function getFileSize() {
		return $this->mFileSize;
	}

	/**
	 * Get the base 36 SHA1 of the file.
	 * @stable to override
	 * @return string|false
	 */
	public function getTempFileSha1Base36() {
		// Use cached version if we already have it.
		if ( $this->mFileProps && is_string( $this->mFileProps['sha1'] ) ) {
			return $this->mFileProps['sha1'];
		}
		return FSFile::getSha1Base36FromPath( $this->mTempPath );
	}

	/**
	 * @param string $srcPath The source path
	 * @return string|false The real path if it was a virtual URL Returns false on failure
	 */
	public function getRealPath( $srcPath ) {
		$repo = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo();
		if ( FileRepo::isVirtualUrl( $srcPath ) ) {
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
	 * Verify whether the upload is sensible.
	 *
	 * Return a status array representing the outcome of the verification.
	 * Possible keys are:
	 * - 'status': set to self::OK in case of success, or to one of the error constants defined in
	 *   this class in case of failure
	 * - 'max': set to the maximum allowed file size ($wgMaxUploadSize) if the upload is too large
	 * - 'details': set to error details if the file type is valid but contents are corrupt
	 * - 'filtered': set to the sanitized file name if the requested file name is invalid
	 * - 'finalExt': set to the file's file extension if it is not an allowed file extension
	 * - 'blacklistedExt': set to the list of disallowed file extensions if the current file extension
	 *    is not allowed for uploads and the list is not empty
	 *
	 * @stable to override
	 * @return mixed[] array representing the result of the verification
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
		 * type, but it's corrupt or data of the wrong type, we should
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

		return [ 'status' => self::OK ];
	}

	/**
	 * Verify that the name is valid and, if necessary, that we can overwrite
	 *
	 * @return array|bool True if valid, otherwise an array with 'status'
	 * and other keys
	 */
	public function validateName() {
		$nt = $this->getTitle();
		if ( $nt === null ) {
			$result = [ 'status' => $this->mTitleError ];
			if ( $this->mTitleError === self::ILLEGAL_FILENAME ) {
				$result['filtered'] = $this->mFilteredName;
			}
			if ( $this->mTitleError === self::FILETYPE_BADTYPE ) {
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
	 * @note Only checks that it is not an evil MIME.
	 *  The "does it have the correct file extension given its MIME type?" check is in verifyFile.
	 * @param string $mime Representing the MIME
	 * @return array|bool True if the file is verified, an array otherwise
	 */
	protected function verifyMimeType( $mime ) {
		$verifyMimeType = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::VerifyMimeType );
		if ( $verifyMimeType ) {
			wfDebug( "mime: <$mime> extension: <{$this->mFinalExtension}>" );
			$mimeTypeExclusions = MediaWikiServices::getInstance()->getMainConfig()
				->get( MainConfigNames::MimeTypeExclusions );
			if ( self::checkFileExtension( $mime, $mimeTypeExclusions ) ) {
				return [ 'filetype-badmime', $mime ];
			}
		}

		return true;
	}

	/**
	 * Verifies that it's ok to include the uploaded file
	 *
	 * @return array|true True of the file is verified, array otherwise.
	 */
	protected function verifyFile() {
		$status = $this->verifyPartialFile();
		if ( $status !== true ) {
			return $status;
		}

		$res = $this->uploadVerification->verifyFile(
			$this->mTempPath,
			(string)$this->mFinalExtension,
			$this->getFileProps()
		);
		if ( $res !== true ) {
			return $res;
		}

		$error = true;
		$mime = $this->getFileProps()['mime'];
		$this->getHookRunner()->onUploadVerifyFile( $this, $mime, $error );
		if ( $error !== true ) {
			if ( !is_array( $error ) ) {
				$error = [ $error ];
			}
			return $error;
		}

		return true;
	}

	/**
	 * File props is very expensive on large files (due to sha1 calc)
	 * so it is important we save the result to reuse
	 *
	 * @return array List of file properties
	 */
	protected function getFileProps(): array {
		// Force $this->mFinalExtension to be populated.
		$this->getTitle();

		if ( !is_array( $this->mFileProps ) ) {
			$mwProps = new MWFileProps( MediaWikiServices::getInstance()->getMimeAnalyzer() );
			$this->mFileProps = $mwProps->getPropsFromPath( $this->mTempPath, $this->mFinalExtension );
		}
		return $this->mFileProps;
	}

	/**
	 * A verification routine suitable for partial files
	 *
	 * Runs the deny list checks, but not any checks that may
	 * assume the entire file is present.
	 *
	 * @return array|true True, if the file is valid, else an array with error message key.
	 * @phan-return non-empty-array|true
	 */
	protected function verifyPartialFile() {
		// Needed to set mFinalExtension.
		$this->getTitle();
		return $this->uploadVerification->verifyPartialFile(
			$this->mTempPath,
			(string)$this->mFinalExtension,
			$this->getFileProps()
		);
	}

	/**
	 * Check whether the user can upload the image. This method checks against the current title.
	 * Use verifyUpload() or validateName() first to check that the title is valid.
	 */
	public function authorizeUpload( Authority $performer ): PermissionStatus {
		$status = PermissionStatus::newEmpty();

		$nt = $this->getTitle();
		if ( $nt === null ) {
			throw new LogicException( __METHOD__ . ' must only be called with valid title' );
		}

		$performer->authorizeWrite( 'edit', $nt, $status );
		$performer->authorizeWrite( 'upload', $nt, $status );
		if ( !$status->isGood() ) {
			// If the user can't upload at all, don't display additional errors about re-uploading
			return $status;
		}

		$overwriteError = $this->checkOverwrite( $performer );
		if ( $overwriteError !== true ) {
			$status->fatal( ...$overwriteError );
		}

		return $status;
	}

	/**
	 * Check for non fatal problems with the file.
	 *
	 * This should not assume that mTempPath is set.
	 *
	 * @param User|null $user Accepted since 1.35
	 *
	 * @return mixed[] Array of warnings
	 */
	public function checkWarnings( $user = null ) {
		if ( $user === null ) {
			// TODO check uses and hard deprecate
			$user = RequestContext::getMain()->getUser();
		}

		$warnings = [];

		$localFile = $this->getLocalFile();
		$localFile->load( IDBAccessObject::READ_LATEST );
		$filename = $localFile->getName();
		$hash = $this->getTempFileSha1Base36();

		$badFileName = $this->checkBadFileName( $filename, $this->mDesiredDestName );
		if ( $badFileName !== null ) {
			$warnings['badfilename'] = $badFileName;
		}

		$unwantedFileExtensionDetails = $this->checkUnwantedFileExtensions( (string)$this->mFinalExtension );
		if ( $unwantedFileExtensionDetails !== null ) {
			$warnings['filetype-unwanted-type'] = $unwantedFileExtensionDetails;
		}

		$fileSizeWarnings = $this->checkFileSize( $this->mFileSize );
		if ( $fileSizeWarnings ) {
			$warnings = array_merge( $warnings, $fileSizeWarnings );
		}

		$localFileExistsWarnings = $this->checkLocalFileExists( $localFile, $hash );
		if ( $localFileExistsWarnings ) {
			$warnings = array_merge( $warnings, $localFileExistsWarnings );
		}

		if ( $this->checkLocalFileWasDeleted( $localFile ) ) {
			$warnings['was-deleted'] = $filename;
		}

		// If a file with the same name exists locally then the local file has already been tested
		// for duplication of content
		$ignoreLocalDupes = isset( $warnings['exists'] );
		$dupes = $this->checkAgainstExistingDupes( $hash, $ignoreLocalDupes );
		if ( $dupes ) {
			$warnings['duplicate'] = $dupes;
		}

		$archivedDupes = $this->checkAgainstArchiveDupes( $hash, $user );
		if ( $archivedDupes !== null ) {
			$warnings['duplicate-archive'] = $archivedDupes;
		}

		return $warnings;
	}

	/**
	 * Convert the warnings array returned by checkWarnings() to something that
	 * can be serialized, and that is suitable for inclusion directly in action API results.
	 *
	 * File objects will be converted to an associative array with the following keys:
	 *
	 *   - fileName: The name of the file
	 *   - timestamp: The upload timestamp
	 *
	 * @param mixed[] $warnings
	 * @return mixed[]
	 */
	public static function makeWarningsSerializable( $warnings ) {
		array_walk_recursive( $warnings, static function ( &$param, $key ) {
			if ( $param instanceof File ) {
				$param = [
					'fileName' => $param->getName(),
					'timestamp' => $param->getTimestamp()
				];
			} elseif ( $param instanceof MessageParam ) {
				// Do nothing (T390001)
			} elseif ( is_object( $param ) ) {
				throw new InvalidArgumentException(
					'UploadBase::makeWarningsSerializable: ' .
					'Unexpected object of class ' . get_class( $param ) );
			}
		} );
		return $warnings;
	}

	/**
	 * Convert the serialized warnings array created by makeWarningsSerializable()
	 * back to the output of checkWarnings().
	 *
	 * @param mixed[] $warnings
	 * @return mixed[]
	 */
	public static function unserializeWarnings( $warnings ) {
		foreach ( $warnings as $key => $value ) {
			if ( is_array( $value ) ) {
				if ( isset( $value['fileName'] ) && isset( $value['timestamp'] ) ) {
					$warnings[$key] = MediaWikiServices::getInstance()->getRepoGroup()->findFile(
						$value['fileName'],
						[ 'time' => $value['timestamp'] ]
					);
				} else {
					$warnings[$key] = self::unserializeWarnings( $value );
				}
			}
		}
		return $warnings;
	}

	/**
	 * Check whether the resulting filename is different from the desired one,
	 * but ignore things like ucfirst() and spaces/underscore things
	 *
	 * @param string $filename
	 * @param string $desiredFileName
	 *
	 * @return string|null String that was determined to be bad or null if the filename is okay
	 */
	private function checkBadFileName( $filename, $desiredFileName ) {
		$comparableName = str_replace( ' ', '_', $desiredFileName );
		$comparableName = Title::capitalize( $comparableName, NS_FILE );

		if ( $desiredFileName != $filename && $comparableName != $filename ) {
			return $filename;
		}

		return null;
	}

	/**
	 * @param string $fileExtension The file extension to check
	 *
	 * @return array|null array with the following keys:
	 *                    0 => string The final extension being used
	 *                    1 => string[] The extensions that are allowed
	 *                    2 => int The number of extensions that are allowed.
	 */
	private function checkUnwantedFileExtensions( $fileExtension ) {
		$checkFileExtensions = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::CheckFileExtensions );
		$fileExtensions = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::FileExtensions );
		if ( $checkFileExtensions ) {
			$extensions = array_unique( $fileExtensions );
			if ( !self::checkFileExtension( $fileExtension, $extensions ) ) {
				return [
					$fileExtension,
					Message::listParam( $extensions, 'comma' ),
					count( $extensions )
				];
			}
		}

		return null;
	}

	/**
	 * @param int $fileSize
	 *
	 * @return array warnings
	 */
	private function checkFileSize( $fileSize ) {
		$uploadSizeWarning = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::UploadSizeWarning );

		$warnings = [];

		if ( $uploadSizeWarning && ( $fileSize > $uploadSizeWarning ) ) {
			$warnings['large-file'] = [
				Message::sizeParam( $uploadSizeWarning ),
				Message::sizeParam( $fileSize ),
			];
		}

		if ( $fileSize == 0 ) {
			$warnings['empty-file'] = true;
		}

		return $warnings;
	}

	/**
	 * @param LocalFile $localFile
	 * @param string|false $hash sha1 hash of the file to check
	 *
	 * @return array warnings
	 */
	private function checkLocalFileExists( LocalFile $localFile, $hash ) {
		$warnings = [];

		$exists = self::getExistsWarning( $localFile );
		if ( $exists !== false ) {
			$warnings['exists'] = $exists;

			// check if file is an exact duplicate of current file version
			if ( $hash !== false && $hash === $localFile->getSha1() ) {
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

		return $warnings;
	}

	private function checkLocalFileWasDeleted( LocalFile $localFile ): bool {
		return $localFile->wasDeleted() && !$localFile->exists();
	}

	/**
	 * @param string|false $hash sha1 hash of the file to check
	 * @param bool $ignoreLocalDupes True to ignore local duplicates
	 *
	 * @return File[] Duplicate files, if found.
	 */
	private function checkAgainstExistingDupes( $hash, $ignoreLocalDupes ) {
		if ( $hash === false ) {
			return [];
		}
		$dupes = MediaWikiServices::getInstance()->getRepoGroup()->findBySha1( $hash );
		$title = $this->getTitle();
		foreach ( $dupes as $key => $dupe ) {
			if (
				( $dupe instanceof LocalFile ) &&
				$ignoreLocalDupes &&
				$title->equals( $dupe->getTitle() )
			) {
				unset( $dupes[$key] );
			}
		}

		return $dupes;
	}

	/**
	 * @param string|false $hash sha1 hash of the file to check
	 * @param Authority $performer
	 *
	 * @return string|null Name of the dupe or empty string if discovered (depending on visibility)
	 *                     null if the check discovered no dupes.
	 */
	private function checkAgainstArchiveDupes( $hash, Authority $performer ) {
		if ( $hash === false ) {
			return null;
		}
		$archivedFile = new ArchivedFile( null, 0, '', $hash );
		if ( $archivedFile->getID() > 0 ) {
			if ( $archivedFile->userCan( File::DELETED_FILE, $performer ) ) {
				return $archivedFile->getName();
			}
			return '';
		}

		return null;
	}

	/**
	 * Really perform the upload. Stores the file in the local repo, watches
	 * if necessary and runs the UploadComplete hook.
	 *
	 * @param string $comment
	 * @param string|false $pageText
	 * @param bool $watch Whether the file page should be added to user's watchlist.
	 *   (This doesn't check $user's permissions.)
	 * @param User $user
	 * @param string[] $tags Change tags to add to the log entry and page revision.
	 *   (This doesn't check $user's permissions.)
	 * @param string|null $watchlistExpiry Optional watchlist expiry timestamp in any format
	 *   acceptable to wfTimestamp().
	 * @return Status Indicating the whether the upload succeeded.
	 *
	 * @since 1.35 Accepts $watchlistExpiry parameter.
	 */
	public function performUpload(
		$comment, $pageText, $watch, $user, $tags = [], ?string $watchlistExpiry = null
	) {
		$this->getLocalFile()->load( IDBAccessObject::READ_LATEST );
		$props = $this->mFileProps;

		$error = null;
		$this->getHookRunner()->onUploadVerifyUpload( $this, $user, $props, $comment, $pageText, $error );
		if ( $error ) {
			if ( !is_array( $error ) ) {
				$error = [ $error ];
			}
			return Status::newFatal( ...$error );
		}

		$status = $this->getLocalFile()->upload(
			$this->mTempPath,
			$comment,
			$pageText !== false ? $pageText : '',
			File::DELETE_SOURCE,
			$props,
			false,
			$user,
			$tags
		);

		if ( $status->isGood() ) {
			if ( $watch ) {
				MediaWikiServices::getInstance()->getWatchlistManager()->addWatchIgnoringRights(
					$user,
					$this->getLocalFile()->getTitle(),
					$watchlistExpiry
				);
			}
			$this->getHookRunner()->onUploadComplete( $this );

			$this->postProcessUpload();
		}

		return $status;
	}

	/**
	 * Perform extra steps after a successful upload.
	 *
	 * @stable to override
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
		if ( $title && $title->getNamespace() === NS_FILE ) {
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
		if ( $nt === null ) {
			$this->mTitleError = self::ILLEGAL_FILENAME;
			$this->mTitle = null;

			return $this->mTitle;
		}
		$this->mFilteredName = $nt->getDBkey();

		/**
		 * We'll want to prevent against *any* 'extension', and use
		 * only the final one for the allow list.
		 */
		[ $partname, $ext ] = self::splitExtensions( $this->mFilteredName );

		if ( $ext !== [] ) {
			$this->mFinalExtension = trim( end( $ext ) );
		} else {
			$this->mFinalExtension = '';

			// No extension, try guessing one from the temporary file
			// FIXME: Sometimes we mTempPath isn't set yet here, possibly due to an unrealistic
			// or incomplete test case in UploadBaseTest (T272328)
			if ( $this->mTempPath !== null ) {
				$magic = MediaWikiServices::getInstance()->getMimeAnalyzer();
				$mime = $magic->guessMimeType( $this->mTempPath );
				if ( $mime !== 'unknown/unknown' ) {
					# Get a space separated list of extensions
					$mimeExt = $magic->getExtensionFromMimeTypeOrNull( $mime );
					if ( $mimeExt !== null ) {
						# Set the extension to the canonical extension
						$this->mFinalExtension = $mimeExt;

						# Fix up the other variables
						$this->mFilteredName .= ".{$this->mFinalExtension}";
						$nt = Title::makeTitleSafe( NS_FILE, $this->mFilteredName );
						$ext = [ $this->mFinalExtension ];
					}
				}
			}
		}

		// Don't allow users to override the list of prohibited file extensions (check file extension)
		$config = MediaWikiServices::getInstance()->getMainConfig();
		$checkFileExtensions = $config->get( MainConfigNames::CheckFileExtensions );
		$strictFileExtensions = $config->get( MainConfigNames::StrictFileExtensions );
		$fileExtensions = $config->get( MainConfigNames::FileExtensions );
		$prohibitedFileExtensions = $config->get( MainConfigNames::ProhibitedFileExtensions );

		$badList = self::checkFileExtensionList( $ext, $prohibitedFileExtensions );

		if ( $this->mFinalExtension == '' ) {
			$this->mTitleError = self::FILETYPE_MISSING;
			$this->mTitle = null;

			return $this->mTitle;
		}

		if ( $badList ||
			( $checkFileExtensions && $strictFileExtensions &&
				!self::checkFileExtension( $this->mFinalExtension, $fileExtensions ) )
		) {
			$this->mBlackListedExtensions = $badList;
			$this->mTitleError = self::FILETYPE_BADTYPE;
			$this->mTitle = null;

			return $this->mTitle;
		}

		// Windows may be broken with special characters, see T3780
		if ( !preg_match( '/^[\x0-\x7f]*$/', $nt->getText() )
			&& !MediaWikiServices::getInstance()->getRepoGroup()
				->getLocalRepo()->backendSupportsUnicodePaths()
		) {
			$this->mTitleError = self::WINDOWS_NONASCII_FILENAME;
			$this->mTitle = null;

			return $this->mTitle;
		}

		# If there was more than one file "extension", reassemble the base
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
	 * @stable to override
	 * @return LocalFile|null
	 */
	public function getLocalFile() {
		if ( $this->mLocalFile === null ) {
			$nt = $this->getTitle();
			$this->mLocalFile = $nt === null
				? null
				: MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo()->newFile( $nt );
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
	 * @stable to override
	 * @param User $user
	 * @param bool $isPartial Pass `true` if this is a part of a chunked upload (not a complete file).
	 * @return Status If successful, value is an UploadStashFile instance
	 */
	public function tryStashFile( User $user, $isPartial = false ) {
		if ( !$isPartial ) {
			$error = $this->runUploadStashFileHook( $user );
			if ( $error ) {
				return Status::newFatal( ...$error );
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
		$this->getHookRunner()->onUploadStashFile( $this, $user, $props, $error );
		if ( $error && !is_array( $error ) ) {
			$error = [ $error ];
		}
		return $error;
	}

	/**
	 * Implementation for stashFile() and tryStashFile().
	 *
	 * @stable to override
	 * @param User|null $user
	 * @return UploadStashFile Stashed file
	 */
	protected function doStashFile( ?User $user = null ) {
		$stash = MediaWikiServices::getInstance()->getRepoGroup()
			->getLocalRepo()->getUploadStash( $user );
		$file = $stash->stashFile( $this->mTempPath, $this->getSourceType(), $this->mFileProps );
		$this->mStashFile = $file;

		return $file;
	}

	/**
	 * If we've modified the upload file, then we need to manually remove it
	 * on exit to clean up.
	 */
	public function cleanupTempFile() {
		if ( $this->mRemoveTempFile && $this->tempFileObj ) {
			// Delete when all relevant TempFSFile handles go out of scope
			wfDebug( __METHOD__ . ": Marked temporary file '{$this->mTempPath}' for removal" );
			$this->tempFileObj->autocollect();
		}
	}

	/**
	 * @return string|null
	 */
	public function getTempPath() {
		return $this->mTempPath;
	}

	/**
	 * Split a file into a base name and all dot-delimited 'extensions'
	 * on the end. Some web server configurations will fall back to
	 * earlier pseudo-'extensions' to determine type and execute
	 * scripts, so we need to check them all.
	 *
	 * @param string $filename
	 * @return array [ string, string[] ]
	 */
	public static function splitExtensions( $filename ) {
		$bits = explode( '.', $filename );
		$basename = array_shift( $bits );

		return [ $basename, $bits ];
	}

	/**
	 * Perform case-insensitive match against a list of file extensions.
	 *
	 * @param string $ext File extension
	 * @param array $list
	 * @return bool Returns true if the extension is in the list.
	 */
	public static function checkFileExtension( $ext, $list ) {
		return in_array( strtolower( $ext ?? '' ), $list, true );
	}

	/**
	 * Perform case-insensitive match against a list of file extensions.
	 * Returns an array of matching extensions.
	 *
	 * @param string[] $ext File extensions
	 * @param string[] $list
	 * @return string[]
	 */
	public static function checkFileExtensionList( $ext, $list ) {
		return array_intersect( array_map( 'strtolower', $ext ), $list );
	}

	/**
	 * Checks if the MIME type of the uploaded file matches the file extension.
	 *
	 * @deprecated 1.45
	 * @param string $mime The MIME type of the uploaded file
	 * @param string $extension The filename extension that the file is to be served with
	 * @return bool
	 */
	public static function verifyExtension( $mime, $extension ) {
		wfDeprecated( __METHOD__, '1.45' );
		// External callers should probably be using verifyFile and not this method.
		$verify = MediaWikiServices::getInstance()->getUploadVerification();
		return $verify->verifyExtension( $mime, $extension );
	}

	/**
	 * Heuristic for detecting files that *could* contain JavaScript instructions or
	 * things that may look like HTML to a browser and are thus
	 * potentially harmful. The present implementation will produce false
	 * positives in some situations.
	 *
	 * @warning This only does some of the checks and should not be used to verify files by itself.
	 *
	 * @deprecated 1.45 use UploadVerification::verifyFile() instead
	 * @param string|null $file Pathname to the temporary upload file
	 * @param string $mime The MIME type of the file
	 * @param string|null $extension The extension of the file
	 * @return bool True if the file contains something looking like embedded scripts
	 */
	public static function detectScript( $file, $mime, $extension ) {
		wfDeprecated( __METHOD__, '1.45' );
		// When replacing usage of this in extensions, use UploadVerification::verifyFile.
		// detectScript is unlikely to be the method you want.
		$verify = MediaWikiServices::getInstance()->getUploadVerification();
		return $verify->detectScript( $file, $mime, $extension );
	}

	/**
	 * Generic wrapper function for a virus scanner program.
	 * This relies on the $wgAntivirus and $wgAntivirusSetup variables.
	 * $wgAntivirusRequired may be used to deny upload if the scan fails.
	 *
	 * @param string $file Pathname to the temporary upload file
	 * @return bool|null|string False if not virus is found, null if the scan fails or is disabled,
	 *   or a string containing feedback from the virus scanner if a virus was found.
	 *   If textual feedback is missing but a virus was found, this function returns true.
	 * @deprecated 1.45 Use UploadVerification->detectVirus() directly.
	 */
	public static function detectVirus( $file ) {
		wfDeprecated( __METHOD__, '1.45' );
		$uploadVerification = MediaWikiServices::getInstance()->getUploadVerification();
		return $uploadVerification->detectVirus( $file );
	}

	/**
	 * Check if there's a file overwrite conflict and, if so, if restrictions
	 * forbid this user from performing the upload.
	 *
	 * @param Authority $performer
	 *
	 * @return bool|array
	 * @phan-return true|non-empty-array
	 */
	private function checkOverwrite( Authority $performer ) {
		// First check whether the local file can be overwritten
		$file = $this->getLocalFile();
		$file->load( IDBAccessObject::READ_LATEST );
		if ( $file->exists() ) {
			if ( !self::userCanReUpload( $performer, $file ) ) {
				return [ 'fileexists-forbidden', $file->getName() ];
			}

			return true;
		}

		$services = MediaWikiServices::getInstance();

		/* Check shared conflicts: if the local file does not exist, but
		 * RepoGroup::findFile finds a file, it exists in a shared repository.
		 */
		$file = $services->getRepoGroup()->findFile( $this->getTitle(), [ 'latest' => true ] );
		if ( $file && !$performer->isAllowed( 'reupload-shared' ) ) {
			return [ 'fileexists-shared-forbidden', $file->getName() ];
		}

		return true;
	}

	/**
	 * Check if a user is the last uploader
	 *
	 * @param Authority $performer
	 * @param File $img
	 * @return bool
	 */
	public static function userCanReUpload( Authority $performer, File $img ) {
		if ( $performer->isAllowed( 'reupload' ) ) {
			return true; // non-conditional
		}

		if ( !$performer->isAllowed( 'reupload-own' ) ) {
			return false;
		}

		if ( !( $img instanceof LocalFile ) ) {
			return false;
		}

		return $performer->getUser()->equals( $img->getUploader( File::RAW ) );
	}

	/**
	 * Helper function that does various existence checks for a file.
	 * The following checks are performed:
	 * - If the file exists
	 * - If an article with the same name as the file exists
	 * - If a file exists with normalized extension
	 * - If the file looks like a thumbnail and the original exists
	 *
	 * @param File $file The File object to check
	 * @return array|false False if the file does not exist, else an array
	 */
	public static function getExistsWarning( $file ) {
		if ( $file->exists() ) {
			return [ 'warning' => 'exists', 'file' => $file ];
		}

		if ( $file->getTitle()->getArticleID() ) {
			return [ 'warning' => 'page-exists', 'file' => $file ];
		}

		$n = strrpos( $file->getName(), '.' );
		if ( $n > 0 ) {
			$partname = substr( $file->getName(), 0, $n );
			$extension = substr( $file->getName(), $n + 1 );
		} else {
			$partname = $file->getName();
			$extension = '';
		}
		$normalizedExtension = File::normalizeExtension( $extension );
		$localRepo = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo();

		if ( $normalizedExtension != $extension ) {
			// We're not using the normalized form of the extension.
			// Normal form is lowercase, using most common of alternate
			// extensions (e.g. 'jpg' rather than 'JPEG').

			// Check for another file using the normalized form...
			$nt_lc = Title::makeTitle( NS_FILE, "{$partname}.{$normalizedExtension}" );
			$file_lc = $localRepo->newFile( $nt_lc );

			if ( $file_lc->exists() ) {
				return [
					'warning' => 'exists-normalized',
					'file' => $file,
					'normalizedFile' => $file_lc
				];
			}
		}

		// Check for files with the same name but a different extension
		$similarFiles = $localRepo->findFilesByPrefix( "{$partname}.", 1 );
		if ( count( $similarFiles ) ) {
			return [
				'warning' => 'exists-normalized',
				'file' => $file,
				'normalizedFile' => $similarFiles[0],
			];
		}

		if ( self::isThumbName( $file->getName() ) ) {
			// Check for filenames like 50px- or 180px-, these are mostly thumbnails
			$nt_thb = Title::newFromText(
				substr( $partname, strpos( $partname, '-' ) + 1 ) . '.' . $extension,
				NS_FILE
			);
			$file_thb = $localRepo->newFile( $nt_thb );
			if ( $file_thb->exists() ) {
				return [
					'warning' => 'thumb',
					'file' => $file,
					'thumbFile' => $file_thb
				];
			}

			// The file does not exist, but we just don't like the name
			return [
				'warning' => 'thumb-name',
				'file' => $file,
				'thumbFile' => $file_thb
			];
		}

		foreach ( self::getFilenamePrefixBlacklist() as $prefix ) {
			if ( str_starts_with( $partname, $prefix ) ) {
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
			substr( $partname, 3, 3 ) === 'px-' ||
			substr( $partname, 2, 3 ) === 'px-'
		) && preg_match( "/[0-9]{2}/", substr( $partname, 0, 2 ) );
	}

	/**
	 * Get a list of disallowed filename prefixes from [[MediaWiki:Filename-prefix-blacklist]]
	 *
	 * @return string[] List of prefixes
	 */
	public static function getFilenamePrefixBlacklist() {
		$list = [];
		$message = wfMessage( 'filename-prefix-blacklist' )->inContentLanguage();
		if ( !$message->isDisabled() ) {
			$lines = explode( "\n", $message->plain() );
			foreach ( $lines as $line ) {
				// Remove comment lines
				$comment = substr( trim( $line ), 0, 1 );
				if ( $comment === '#' || $comment == '' ) {
					continue;
				}
				// Remove additional comments after a prefix
				$comment = strpos( $line, '#' );
				if ( $comment > 0 ) {
					$line = substr( $line, 0, $comment - 1 );
				}
				$list[] = trim( $line );
			}
		}

		return $list;
	}

	/**
	 * Gets image info about the file just uploaded.
	 *
	 * @deprecated since 1.42, subclasses of ApiUpload can use
	 * ApiUpload::getUploadImageInfo() instead.
	 *
	 * @param ?ApiResult $result unused since 1.42
	 * @return array Image info
	 */
	public function getImageInfo( $result = null ) {
		$apiUpload = ApiUpload::getDummyInstance();
		return $apiUpload->getUploadImageInfo( $this );
	}

	public function convertVerifyErrorToStatus( array $error ): UploadVerificationStatus {
		switch ( $error['status'] ) {
			/** Statuses that only require name changing */
			case self::MIN_LENGTH_PARTNAME:
				return UploadVerificationStatus::newFatal( 'filename-tooshort' )
					->setRecoverableError( true )
					->setInvalidParameter( 'filename' );

			case self::ILLEGAL_FILENAME:
				return UploadVerificationStatus::newFatal( 'illegal-filename' )
					->setRecoverableError( true )
					->setInvalidParameter( 'filename' )
					->setApiData( [ 'filename' => $error['filtered'] ] );

			case self::FILENAME_TOO_LONG:
				return UploadVerificationStatus::newFatal( 'filename-toolong' )
					->setRecoverableError( true )
					->setInvalidParameter( 'filename' );

			case self::FILETYPE_MISSING:
				return UploadVerificationStatus::newFatal( 'filetype-missing' )
					->setRecoverableError( true )
					->setInvalidParameter( 'filename' );

			case self::WINDOWS_NONASCII_FILENAME:
				return UploadVerificationStatus::newFatal( 'windows-nonascii-filename' )
					->setRecoverableError( true )
					->setInvalidParameter( 'filename' );

			/** Statuses that require reuploading */
			case self::EMPTY_FILE:
				return UploadVerificationStatus::newFatal( 'empty-file' );

			case self::FILE_TOO_LARGE:
				return UploadVerificationStatus::newFatal( 'file-too-large' );

			case self::FILETYPE_BADTYPE:
				$extensions = array_unique( MediaWikiServices::getInstance()
					->getMainConfig()->get( MainConfigNames::FileExtensions ) );
				$extradata = [
					'filetype' => $error['finalExt'],
					'allowed' => array_values( $extensions ),
				];
				ApiResult::setIndexedTagName( $extradata['allowed'], 'ext' );
				if ( isset( $error['blacklistedExt'] ) ) {
					$bannedTypes = $error['blacklistedExt'];
					$extradata['blacklisted'] = array_values( $bannedTypes );
					ApiResult::setIndexedTagName( $extradata['blacklisted'], 'ext' );
				} else {
					$bannedTypes = [ $error['finalExt'] ];
				}
				'@phan-var string[] $bannedTypes';
				return UploadVerificationStatus::newFatal(
						'filetype-banned-type',
						Message::listParam( $bannedTypes, ListType::COMMA ),
						Message::listParam( $extensions, ListType::COMMA ),
						count( $extensions ),
						// Add PLURAL support for the first parameter. This results
						// in a bit unlogical parameter sequence, but does not break
						// old translations
						count( $bannedTypes )
					)
					->setApiCode( 'filetype-banned' )
					->setApiData( $extradata );

			case self::VERIFICATION_ERROR:
				$msg = ApiMessage::create( $error['details'], 'verification-error' );
				if ( $error['details'][0] instanceof MessageSpecifier ) {
					$apiDetails = [ $msg->getKey(), ...$msg->getParams() ];
				} else {
					$apiDetails = $error['details'];
				}
				ApiResult::setIndexedTagName( $apiDetails, 'detail' );
				$msg->setApiData( $msg->getApiData() + [ 'details' => $apiDetails ] );
				return UploadVerificationStatus::newFatal( $msg );

			default:
				// @codeCoverageIgnoreStart
				return UploadVerificationStatus::newFatal( 'upload-unknownerror-nocode' )
					->setApiCode( 'unknown-error' )
					->setApiData( [ 'details' => [ 'code' => $error['status'] ] ] );
				// @codeCoverageIgnoreEnd
		}
	}

	/**
	 * Get MediaWiki's maximum uploaded file size for a given type of upload, based on
	 * $wgMaxUploadSize.
	 *
	 * @param null|string $forType
	 * @return int
	 */
	public static function getMaxUploadSize( $forType = null ) {
		$maxUploadSize = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::MaxUploadSize );

		if ( is_array( $maxUploadSize ) ) {
			return $maxUploadSize[$forType] ?? $maxUploadSize['*'];
		}
		return intval( $maxUploadSize );
	}

	/**
	 * Get the PHP maximum uploaded file size, based on ini settings. If there is no limit or the
	 * limit can't be guessed, return a very large number (PHP_INT_MAX) instead.
	 *
	 * @since 1.27
	 * @return int
	 */
	public static function getMaxPhpUploadSize() {
		$phpMaxFileSize = wfShorthandToInteger(
			ini_get( 'upload_max_filesize' ),
			PHP_INT_MAX
		);
		$phpMaxPostSize = wfShorthandToInteger(
			ini_get( 'post_max_size' ),
			PHP_INT_MAX
		) ?: PHP_INT_MAX;
		return min( $phpMaxFileSize, $phpMaxPostSize );
	}

	/**
	 * Get the current status of a chunked upload (used for polling).
	 *
	 * This should only be called during POST requests since we
	 * fetch from dc-local MainStash, and from a GET request we can't
	 * know that the value is available or up-to-date.
	 *
	 * @param UserIdentity $user
	 * @param string $statusKey
	 * @return mixed[]|false
	 */
	public static function getSessionStatus( UserIdentity $user, $statusKey ) {
		$store = self::getUploadSessionStore();
		$key = self::getUploadSessionKey( $store, $user, $statusKey );

		return $store->get( $key );
	}

	/**
	 * Set the current status of a chunked upload (used for polling).
	 *
	 * The value will be set in cache for 1 day.
	 *
	 * This should only be called during POST requests.
	 *
	 * @param UserIdentity $user
	 * @param string $statusKey
	 * @param array|false $value
	 * @return void
	 */
	public static function setSessionStatus( UserIdentity $user, $statusKey, $value ) {
		$store = self::getUploadSessionStore();
		$key = self::getUploadSessionKey( $store, $user, $statusKey );
		$logger = LoggerFactory::getInstance( 'upload' );

		if ( is_array( $value ) && ( $value['result'] ?? '' ) === 'Failure' ) {
			$logger->info( 'Upload session {key} for {user} set to failure {status} at {stage}',
				[
					'result' => $value['result'] ?? '',
					'stage' => $value['stage'] ?? 'unknown',
					'user' => $user->getName(),
					'status' => (string)( $value['status'] ?? '-' ),
					'filekey' => $value['filekey'] ?? '',
					'key' => $statusKey
				]
			);
		} elseif ( is_array( $value ) ) {
			$logger->debug( 'Upload session {key} for {user} changed {status} at {stage}',
				[
					'result' => $value['result'] ?? '',
					'stage' => $value['stage'] ?? 'unknown',
					'user' => $user->getName(),
					'status' => (string)( $value['status'] ?? '-' ),
					'filekey' => $value['filekey'] ?? '',
					'key' => $statusKey
				]
			);
		} else {
			$logger->debug( "Upload session {key} deleted for {user}",
				[
					'value' => $value,
					'key' => $statusKey,
					'user' => $user->getName()
				]
			);
		}

		if ( $value === false ) {
			$store->delete( $key );
		} else {
			$store->set( $key, $value, $store::TTL_DAY );
		}
	}

	/**
	 * @param BagOStuff $store
	 * @param UserIdentity $user
	 * @param string $statusKey
	 * @return string
	 */
	private static function getUploadSessionKey( BagOStuff $store, UserIdentity $user, $statusKey ) {
		return $store->makeKey(
			'uploadstatus',
			$user->isRegistered() ? $user->getId() : md5( $user->getName() ),
			$statusKey
		);
	}

	/**
	 * @return BagOStuff
	 */
	private static function getUploadSessionStore() {
		return MediaWikiServices::getInstance()->getMainObjectStash();
	}
}
