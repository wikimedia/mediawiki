<?php
/**
 * Backend for uploading files from previously stored file.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Upload
 */

namespace MediaWiki\Upload;

use LogicException;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\MediaWikiServices;
use MediaWiki\Request\WebRequest;
use MediaWiki\Upload\Exception\UploadStashBadPathException;
use MediaWiki\Upload\Exception\UploadStashException;
use MediaWiki\User\UserIdentity;

/**
 * Implements uploading from previously stored file.
 *
 * @ingroup Upload
 * @author Bryan Tong Minh
 */
class UploadFromStash extends UploadBase {
	/** @var string */
	protected $mFileKey;
	/** @var string */
	protected $mVirtualTempPath;
	/** @var string */
	protected $mSourceType;

	/** @var UploadStash */
	private $stash;

	/** @var LocalRepo */
	private $repo;

	/**
	 * @param UserIdentity|null $user This should always be non-null. It is
	 *  nullable for historical reasons.
	 * @param UploadStash|false $stash The UploadStash, or false to create one.
	 * @param LocalRepo|false $repo The repo, or false to get one from services.
	 */
	public function __construct( ?UserIdentity $user = null, $stash = false, $repo = false ) {
		if ( $repo ) {
			$this->repo = $repo;
		} else {
			$this->repo = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo();
		}

		if ( $stash ) {
			$this->stash = $stash;
		} else {
			if ( $user ) {
				wfDebug( __METHOD__ . " creating new UploadStash instance for " . $user->getId() );
			} else {
				wfDebug( __METHOD__ . " creating new UploadStash instance with no user" );
			}

			$this->stash = new UploadStash( $this->repo, $user );
		}
		parent::__construct();
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public static function isValidKey( $key ) {
		return (bool)preg_match( UploadStash::KEY_FORMAT_REGEX, $key );
	}

	/**
	 * @param WebRequest $request
	 * @return bool
	 */
	public static function isValidRequest( $request ) {
		// Get the stash key from wpFileKey or wpSessionKey.
		return self::isValidKey( $request->getText( 'wpFileKey', $request->getText( 'wpSessionKey' ) ) );
	}

	/**
	 * @param string $key
	 * @param string $name
	 * @param bool $initTempFile
	 * @throws UploadStashException
	 */
	public function initialize( $key, $name = 'upload_file', $initTempFile = true ) {
		// Confirm that the file key is valid.
		// We don't want path names to be forged, so we keep them in the
		// uploadstash table and just give an opaque key to the user agent.
		$metadata = $this->stash->getMetadata( $key );
		$tempPath = $initTempFile ? $this->getRealPath( $metadata['us_path'] ) : null;
		if ( $tempPath === false ) {
			throw new UploadStashBadPathException( wfMessage( 'uploadstash-bad-path' ) );
		}
		$this->initializePathInfo( $name,
			$tempPath,
			$metadata['us_size'],
			false
		);

		$this->mFileKey = $key;
		$this->mVirtualTempPath = $metadata['us_path'];
		$this->mFileProps = $this->stash->getFileProps( $key );
		$this->mSourceType = $metadata['us_source_type'];
		$this->mStashFile = $this->stash->getFile( $key );
	}

	/**
	 * @param WebRequest &$request
	 */
	public function initializeFromRequest( &$request ) {
		// Get the stash key from wpFileKey or wpSessionKey.
		// All callers apparently use wpSessionKey.
		$fileKey = $request->getText( 'wpFileKey', $request->getText( 'wpSessionKey' ) );

		// Get the dest name from wpDestFile, wpUploadFile or filename in that order.
		$desiredDestName = $request->getText(
			'wpDestFile',
			$request->getText( 'wpUploadFile', $request->getText( 'filename' ) )
		);

		$this->initialize( $fileKey, $desiredDestName );
	}

	/**
	 * @return string
	 */
	public function getSourceType() {
		return $this->mSourceType;
	}

	/**
	 * Get the base 36 SHA1 of the file
	 * @return string
	 */
	public function getTempFileSha1Base36() {
		// phan doesn't like us accessing this directly since in
		// parent class this can be null, however we always set this in
		// this class so it is safe. Add a check to keep phan happy.
		if ( !is_array( $this->mFileProps ) ) {
			throw new LogicException( "mFileProps should never be null" );
		} else {
			return $this->mFileProps['sha1'];
		}
	}

	/**
	 * Remove a temporarily kept file stashed by saveTempUploadedFile().
	 * @return bool Success
	 */
	public function unsaveUploadedFile() {
		$this->mStashFile = null;
		return $this->stash->removeFile( $this->mFileKey );
	}

	/**
	 * Remove the database record after a successful upload.
	 */
	public function postProcessUpload() {
		parent::postProcessUpload();
		$this->unsaveUploadedFile();
	}
}

/** @deprecated class alias since 1.46 */
class_alias( UploadFromStash::class, 'UploadFromStash' );
