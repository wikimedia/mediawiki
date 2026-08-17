<?php
/**
 * Temporary storage for uploaded files.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Upload;

use MediaWiki\Context\RequestContext;
use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Upload\Exception\UploadStashBadPathException;
use MediaWiki\Upload\Exception\UploadStashFileException;
use MediaWiki\Upload\Exception\UploadStashFileNotFoundException;
use MediaWiki\Upload\Exception\UploadStashNoSuchKeyException;
use MediaWiki\Upload\Exception\UploadStashNotLoggedInException;
use MediaWiki\Upload\Exception\UploadStashWrongOwnerException;
use MediaWiki\Upload\Exception\UploadStashZeroLengthFileException;
use MediaWiki\User\UserIdentity;
use MediaWiki\Utils\MWFileProps;

/**
 * UploadStash provides temporary file storage for users, persisting across
 * multiple requests.
 *
 * Files are stored in FileRepo and tracked in the database.
 *
 * Stashed files are private. Read access is only provided to the creator.
 *
 * @ingroup Upload
 */
class UploadStash {
	// Format of the key for files -- has to be suitable as a filename itself (e.g. ab12cd34ef.jpg)
	public const KEY_FORMAT_REGEX = '/^[\w\-\.]+\.\w*$/';
	private const MAX_US_PROPS_SIZE = 65535;

	/**
	 * Repository that this uses to store temp files.
	 * Public because we sometimes need to get a LocalFile within the same repo.
	 *
	 * @var LocalRepo
	 */
	public $repo;

	/** @var UploadStashFile[] Array of initialized file objects */
	protected $files = [];

	/** @var array Cache of uploadstash rows indexed by stash key */
	protected $fileMetadata = [];

	/** @var array Cache of unserialized file properties (us_props) indexed by stash key */
	protected $fileProps = [];

	/** @var UserIdentity */
	private $user;

	/**
	 * @param LocalRepo $repo The repo to use for file storage
	 * @param UserIdentity|null $user The user whose stash this is, or null to
	 *  use the request context user
	 */
	public function __construct( LocalRepo $repo, ?UserIdentity $user = null ) {
		$this->repo = $repo;
		$this->user = $user ?? RequestContext::getMain()->getUser();
	}

	/**
	 * Get a file and its metadata from the stash.
	 * The noAuth param is a bit janky but is required for automated scripts
	 * which clean out the stash.
	 *
	 * @param string $key The stash key from UploadStashFile::getFileKey()
	 * @param bool $noAuth (optional) Don't check authentication. Used by maintenance scripts.
	 * @throws UploadStashNotLoggedInException
	 * @throws UploadStashWrongOwnerException
	 * @throws UploadStashBadPathException
	 * @throws UploadStashFileNotFoundException
	 * @return UploadStashFile
	 */
	public function getFile( $key, $noAuth = false ) {
		if ( !preg_match( self::KEY_FORMAT_REGEX, $key ) ) {
			throw new UploadStashBadPathException(
				wfMessage( 'uploadstash-bad-path-bad-format', $key )
			);
		}

		if ( !$noAuth && !$this->user->isRegistered() ) {
			throw new UploadStashNotLoggedInException(
				wfMessage( 'uploadstash-not-logged-in' )
			);
		}

		if ( !isset( $this->fileMetadata[$key] ) ) {
			if ( !$this->fetchFileMetadata( $key ) ) {
				// If nothing was received, it's likely due to replication lag.
				// Check the primary DB to see if the record is there.
				$this->fetchFileMetadata( $key, DB_PRIMARY );
			}

			if ( !isset( $this->fileMetadata[$key] ) ) {
				throw new UploadStashFileNotFoundException(
					wfMessage( 'uploadstash-file-not-found', $key )
				);
			}

			// Create $this->files[$key]
			$this->initFile( $key );

			// Fetch file props
			if (
				isset( $this->fileMetadata[$key]['us_props'] )
				&& strlen( $this->fileMetadata[$key]['us_props'] )
			) {
				$this->fileProps[$key] = unserialize( $this->fileMetadata[$key]['us_props'] );
			} else {
				// b/c for rows with no us_props
				wfDebug( __METHOD__ . " fetched props for $key from file" );
				$path = $this->fileMetadata[$key]['us_path'];
				$this->fileProps[$key] = $this->repo->getFileProps( $path );
			}
		}

		if ( !$noAuth && $this->fileMetadata[$key]['us_user'] != $this->user->getId() ) {
			throw new UploadStashWrongOwnerException(
				wfMessage( 'uploadstash-wrong-owner', $key )
			);
		}

		return $this->files[$key];
	}

	/**
	 * Get file metadata.
	 *
	 * @param string $key The stash key
	 * @return array
	 */
	public function getMetadata( $key ) {
		$this->getFile( $key );

		return $this->fileMetadata[$key];
	}

	/**
	 * Get file properties
	 *
	 * @param string $key The stash key
	 * @return array
	 */
	public function getFileProps( $key ) {
		$this->getFile( $key );

		return $this->fileProps[$key];
	}

	/**
	 * Stash a file in a temp directory and record that we did this in the
	 * database, along with other metadata.
	 *
	 * @param string $path Path to file you want stashed
	 * @param string|null $sourceType The source type from UploadBase::getSourceType()
	 * @param array|null $fileProps File props or null to regenerate
	 * @throws UploadStashFileException
	 * @throws UploadStashNotLoggedInException
	 * @throws UploadStashBadPathException
	 * @throws UploadStashZeroLengthFileException
	 * @return UploadStashFile|null File, or null on failure
	 */
	public function stashFile( $path, $sourceType = null, $fileProps = null ) {
		if ( !$this->user->isRegistered() ) {
			throw new UploadStashNotLoggedInException(
				wfMessage( 'uploadstash-not-logged-in' )
			);
		}

		if ( !is_file( $path ) ) {
			wfDebug( __METHOD__ . " tried to stash file at '$path', but it doesn't exist" );
			throw new UploadStashBadPathException(
				wfMessage( 'uploadstash-bad-path' )
			);
		}

		// File props is expensive to generate for large files, so reuse if possible.
		if ( !$fileProps ) {
			$mwProps = new MWFileProps( MediaWikiServices::getInstance()->getMimeAnalyzer() );
			$fileProps = $mwProps->getPropsFromPath( $path, true );
		}

		if ( !$fileProps['size'] ) {
			throw new UploadStashZeroLengthFileException(
				wfMessage( 'uploadstash-zero-length' )
			);
		}

		wfDebug( __METHOD__ . " stashing file at '$path'" );

		// We will be initializing from some tmpnam files that don't have extensions.
		// most of MediaWiki assumes all uploaded files have good extensions. So, we fix this.
		$extension = self::getExtensionForPath( $path );
		if ( !str_ends_with( $path, ".$extension" ) ) {
			$pathWithGoodExtension = "$path.$extension";
		} else {
			$pathWithGoodExtension = $path;
		}

		// If no key was supplied, make one.
		[ $usec, $sec ] = explode( ' ', microtime() );
		$usec = substr( $usec, 2 );
		$key = \Wikimedia\base_convert( $sec . $usec, 10, 36 ) . '.' .
			\Wikimedia\base_convert( (string)mt_rand(), 10, 36 ) . '.' .
			$this->user->getId() . '.' .
			$extension;

		$this->fileProps[$key] = $fileProps;

		if ( !preg_match( self::KEY_FORMAT_REGEX, $key ) ) {
			throw new UploadStashBadPathException(
				wfMessage( 'uploadstash-bad-path-bad-format', $key )
			);
		}

		wfDebug( __METHOD__ . " key for '$path': $key" );

		// If not already in a temporary area, put it there.
		$storeStatus = $this->repo->storeTemp( basename( $pathWithGoodExtension ), $path );

		if ( !$storeStatus->isOK() ) {
			// Choose the best error message to throw so that ApiUpload can catch it and
			// convert it to an error.
			// TODO: return a StatusValue instead and use ApiBase::addMessagesFromStatus()
			foreach ( $storeStatus->getMessages( 'error' ) as $msg ) {
				throw new UploadStashFileException( $msg );
			}
			foreach ( $storeStatus->getMessages( 'warning' ) as $msg ) {
				throw new UploadStashFileException( $msg );
			}
			// XXX: This isn't a real message, hopefully this case is unreachable
			throw new UploadStashFileException( [ 'unknown', 'no error recorded' ] );
		}
		$stashPath = $storeStatus->value;

		// Insert the file metadata into the DB.
		wfDebug( __METHOD__ . " inserting $stashPath under $key" );
		$dbw = $this->repo->getPrimaryDB();

		$serializedFileProps = serialize( $fileProps );
		if ( strlen( $serializedFileProps ) > self::MAX_US_PROPS_SIZE ) {
			// The database is going to truncate this and make the field invalid.
			// Prioritize important metadata over file handler metadata.
			// File handler should be prepared to regenerate invalid metadata if needed.
			// TODO: make us_props be a mediumblob like fr_metadata.
			$fileProps['metadata'] = [];
			$serializedFileProps = serialize( $fileProps );
		}

		$insertRow = [
			'us_user' => $this->user->getId(),
			'us_key' => $key,
			'us_orig_path' => $path,
			'us_path' => $stashPath, // virtual URL
			'us_props' => $dbw->encodeBlob( $serializedFileProps ),
			'us_size' => $fileProps['size'],
			'us_sha1' => $fileProps['sha1'],
			'us_mime' => $fileProps['mime'],
			'us_media_type' => $fileProps['media_type'],
			'us_image_width' => $fileProps['width'],
			'us_image_height' => $fileProps['height'],
			'us_image_bits' => $fileProps['bits'],
			'us_source_type' => $sourceType,
			'us_timestamp' => $dbw->timestamp(),
			'us_status' => 'finished'
		];

		$dbw->newInsertQueryBuilder()
			->insertInto( 'uploadstash' )
			->row( $insertRow )
			->caller( __METHOD__ )->execute();

		$insertRow['us_id'] = $dbw->insertId();
		$this->fileMetadata[$key] = $insertRow;

		$this->initFile( $key );
		return $this->getFile( $key );
	}

	/**
	 * Remove all files from the stash.
	 * Does not clean up files in the repo, just the record of them.
	 *
	 * @throws UploadStashNotLoggedInException
	 * @return bool Success
	 */
	public function clear() {
		if ( !$this->user->isRegistered() ) {
			throw new UploadStashNotLoggedInException(
				wfMessage( 'uploadstash-not-logged-in' )
			);
		}

		wfDebug( __METHOD__ . ' clearing all rows for user ' . $this->user->getId() );
		$dbw = $this->repo->getPrimaryDB();
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'uploadstash' )
			->where( [ 'us_user' => $this->user->getId() ] )
			->caller( __METHOD__ )->execute();

		// Destroy objects.
		$this->files = [];
		$this->fileMetadata = [];

		return true;
	}

	/**
	 * Remove a particular file from the stash and the repo.
	 *
	 * @param string $key
	 * @throws UploadStashWrongOwnerException
	 * @throws UploadStashNoSuchKeyException|UploadStashNotLoggedInException
	 * @return bool Success
	 */
	public function removeFile( $key ) {
		if ( !$this->user->isRegistered() ) {
			throw new UploadStashNotLoggedInException(
				wfMessage( 'uploadstash-not-logged-in' )
			);
		}

		$dbw = $this->repo->getPrimaryDB();

		// Load from the primary since we are in an update path.
		$row = $dbw->newSelectQueryBuilder()
			->select( 'us_user' )
			->from( 'uploadstash' )
			->where( [ 'us_key' => $key ] )
			->caller( __METHOD__ )->fetchRow();

		if ( !$row ) {
			throw new UploadStashNoSuchKeyException(
				wfMessage( 'uploadstash-no-such-key', $key )
			);
		}

		if ( $row->us_user != $this->user->getId() ) {
			throw new UploadStashWrongOwnerException(
				wfMessage( 'uploadstash-wrong-owner', $key )
			);
		}

		return $this->removeFileNoAuth( $key );
	}

	/**
	 * Remove a file, but doesn't check ownership first.
	 *
	 * @param string $key
	 * @return bool Success
	 */
	public function removeFileNoAuth( $key ) {
		wfDebug( __METHOD__ . " clearing row $key" );

		// Ensure we have the UploadStashFile loaded for this key
		$this->getFile( $key, true );

		$dbw = $this->repo->getPrimaryDB();

		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'uploadstash' )
			->where( [ 'us_key' => $key ] )
			->caller( __METHOD__ )->execute();

		$success = $this->files[$key]->remove();

		unset( $this->files[$key] );
		unset( $this->fileMetadata[$key] );

		return $success;
	}

	/**
	 * List all files in the stash.
	 *
	 * @throws UploadStashNotLoggedInException
	 * @return string[]
	 */
	public function listFiles(): array {
		if ( !$this->user->isRegistered() ) {
			throw new UploadStashNotLoggedInException(
				wfMessage( 'uploadstash-not-logged-in' )
			);
		}

		return $this->repo->getReplicaDB()->newSelectQueryBuilder()
			->select( 'us_key' )
			->from( 'uploadstash' )
			->where( [ 'us_user' => $this->user->getId() ] )
			->caller( __METHOD__ )
			->fetchFieldValues();
	}

	/**
	 * Find or guess extension, ensuring that our extension matches our MIME type.
	 * Since these files are constructed from PHP tempnames they may not start off
	 * with an extension.
	 *
	 * XXX this is somewhat redundant with the checks that ApiUpload.php does with incoming
	 * uploads versus the desired filename. Maybe we can get that passed to us...
	 *
	 * @param string $path
	 * @return string
	 */
	public static function getExtensionForPath( $path ) {
		$prohibitedFileExtensions = MediaWikiServices::getInstance()
			->getMainConfig()->get( MainConfigNames::ProhibitedFileExtensions );
		// Does this have an extension?
		$n = strrpos( $path, '.' );

		if ( $n !== false ) {
			$extension = $n ? substr( $path, $n + 1 ) : '';
		} else {
			// If not, assume that it should be related to the MIME type of the original file.
			$magic = MediaWikiServices::getInstance()->getMimeAnalyzer();
			$mimeType = $magic->guessMimeType( $path );
			$extension = $magic->getExtensionFromMimeTypeOrNull( $mimeType ) ?? '';
		}

		$extension = File::normalizeExtension( $extension );
		if ( in_array( $extension, $prohibitedFileExtensions ) ) {
			// The file should already be checked for being evil. However, if
			// somehow we got here, we definitely don't want to give it an
			// extension of .php and put it in a web accessible directory.
			return '';
		}

		return $extension;
	}

	/**
	 * Helper function: do the actual database query to fetch file metadata.
	 *
	 * @param string $key
	 * @param int $readFromDB Constant (default: DB_REPLICA)
	 * @return bool
	 */
	protected function fetchFileMetadata( $key, $readFromDB = DB_REPLICA ) {
		if ( $readFromDB === DB_PRIMARY ) {
			$dbr = $this->repo->getPrimaryDB();
		} else {
			$dbr = $this->repo->getReplicaDB();
		}

		$row = $dbr->newSelectQueryBuilder()
			->select( [
				'us_user', 'us_key', 'us_orig_path', 'us_path', 'us_props',
				'us_size', 'us_sha1', 'us_mime', 'us_media_type',
				'us_image_width', 'us_image_height', 'us_image_bits',
				'us_source_type', 'us_timestamp', 'us_status',
			] )
			->from( 'uploadstash' )
			->where( [ 'us_key' => $key ] )
			->caller( __METHOD__ )->fetchRow();

		if ( !is_object( $row ) ) {
			// The key wasn't present in the database. This will happen sometimes.
			return false;
		}

		$this->fileMetadata[$key] = (array)$row;
		$this->fileMetadata[$key]['us_props'] = $dbr->decodeBlob( $row->us_props );

		return true;
	}

	/**
	 * Helper function: Initialize the UploadStashFile for a given file.
	 *
	 * @param string $key Key under which to store the object
	 * @throws UploadStashZeroLengthFileException
	 * @return bool
	 */
	protected function initFile( $key ) {
		$file = new UploadStashFile(
			$this->repo,
			$this->fileMetadata[$key]['us_path'],
			$key,
			$this->fileMetadata[$key]['us_sha1'],
			$this->fileMetadata[$key]['us_mime'] ?? false
		);
		$this->files[$key] = $file;

		return true;
	}
}

/** @deprecated class alias since 1.46 */
class_alias( UploadStash::class, 'UploadStash' );
