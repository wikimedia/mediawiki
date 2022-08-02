<?php
/**
 * Temporary storage for uploaded files.
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
 */

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserIdentity;

/**
 * UploadStash is intended to accomplish a few things:
 *   - Enable applications to temporarily stash files without publishing them to
 *     the wiki.
 *      - Several parts of MediaWiki do this in similar ways: UploadBase,
 *        UploadWizard, and FirefoggChunkedExtension.
 *        And there are several that reimplement stashing from scratch, in
 *        idiosyncratic ways. The idea is to unify them all here.
 *        Mostly all of them are the same except for storing some custom fields,
 *        which we subsume into the data array.
 *   - Enable applications to find said files later, as long as the db table or
 *     temp files haven't been purged.
 *   - Enable the uploading user (and *ONLY* the uploading user) to access said
 *     files, and thumbnails of said files, via a URL. We accomplish this using
 *     a database table, with ownership checking as you might expect. See
 *     SpecialUploadStash, which implements a web interface to some files stored
 *     this way.
 *
 * UploadStash right now is *mostly* intended to show you one user's slice of
 * the entire stash. The user parameter is only optional because there are few
 * cases where we clean out the stash from an automated script. In the future we
 * might refactor this.
 *
 * UploadStash represents the entire stash of temporary files.
 * UploadStashFile is a filestore for the actual physical disk files.
 * UploadFromStash extends UploadBase, and represents a single stashed file as
 * it is moved from the stash to the regular file repository
 *
 * @ingroup Upload
 */
class UploadStash {
	// Format of the key for files -- has to be suitable as a filename itself (e.g. ab12cd34ef.jpg)
	public const KEY_FORMAT_REGEX = '/^[\w\-\.]+\.\w*$/';
	private const MAX_US_PROPS_SIZE = 65535;

	/**
	 * repository that this uses to store temp files
	 * public because we sometimes need to get a LocalFile within the same repo.
	 *
	 * @var LocalRepo
	 */
	public $repo;

	/** @var array array of initialized repo objects */
	protected $files = [];

	/** @var array cache of the file metadata that's stored in the database */
	protected $fileMetadata = [];

	/** @var array fileprops cache */
	protected $fileProps = [];

	/** @var UserIdentity */
	private $user;

	/**
	 * Represents a temporary filestore, with metadata in the database.
	 * Designed to be compatible with the session stashing code in UploadBase
	 * (should replace it eventually).
	 *
	 * @param FileRepo $repo
	 * @param UserIdentity|null $user
	 */
	public function __construct( FileRepo $repo, UserIdentity $user = null ) {
		// this might change based on wiki's configuration.
		$this->repo = $repo;

		// if a user was passed, use it. otherwise, attempt to use the global request context.
		// this keeps FileRepo from breaking when it creates an UploadStash object
		$this->user = $user ?? RequestContext::getMain()->getUser();
	}

	/**
	 * Get a file and its metadata from the stash.
	 * The noAuth param is a bit janky but is required for automated scripts
	 * which clean out the stash.
	 *
	 * @param string $key Key under which file information is stored
	 * @param bool $noAuth (optional) Don't check authentication. Used by maintenance scripts.
	 * @throws UploadStashFileNotFoundException
	 * @throws UploadStashNotLoggedInException
	 * @throws UploadStashWrongOwnerException
	 * @throws UploadStashBadPathException
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

			// create $this->files[$key]
			$this->initFile( $key );

			// fetch fileprops
			if (
				isset( $this->fileMetadata[$key]['us_props'] ) && strlen( $this->fileMetadata[$key]['us_props'] )
			) {
				$this->fileProps[$key] = unserialize( $this->fileMetadata[$key]['us_props'] );
			} else { // b/c for rows with no us_props
				wfDebug( __METHOD__ . " fetched props for $key from file" );
				$path = $this->fileMetadata[$key]['us_path'];
				$this->fileProps[$key] = $this->repo->getFileProps( $path );
			}
		}

		if ( !$this->files[$key]->exists() ) {
			wfDebug( __METHOD__ . " tried to get file at $key, but it doesn't exist" );
			// @todo Is this not an UploadStashFileNotFoundException case?
			throw new UploadStashBadPathException(
				wfMessage( 'uploadstash-bad-path' )
			);
		}

		if ( !$noAuth && $this->fileMetadata[$key]['us_user'] != $this->user->getId() ) {
			throw new UploadStashWrongOwnerException(
				wfMessage( 'uploadstash-wrong-owner', $key )
			);
		}

		return $this->files[$key];
	}

	/**
	 * Getter for file metadata.
	 *
	 * @param string $key Key under which file information is stored
	 * @return array
	 */
	public function getMetadata( $key ) {
		$this->getFile( $key );

		return $this->fileMetadata[$key];
	}

	/**
	 * Getter for fileProps
	 *
	 * @param string $key Key under which file information is stored
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
	 * @param string|null $sourceType The type of upload that generated this file
	 *   (currently, I believe, 'file' or null)
	 * @throws UploadStashBadPathException
	 * @throws UploadStashFileException
	 * @throws UploadStashNotLoggedInException
	 * @return UploadStashFile|null File, or null on failure
	 */
	public function stashFile( $path, $sourceType = null ) {
		if ( !is_file( $path ) ) {
			wfDebug( __METHOD__ . " tried to stash file at '$path', but it doesn't exist" );
			throw new UploadStashBadPathException(
				wfMessage( 'uploadstash-bad-path' )
			);
		}

		$mwProps = new MWFileProps( MediaWikiServices::getInstance()->getMimeAnalyzer() );
		$fileProps = $mwProps->getPropsFromPath( $path, true );
		wfDebug( __METHOD__ . " stashing file at '$path'" );

		// we will be initializing from some tmpnam files that don't have extensions.
		// most of MediaWiki assumes all uploaded files have good extensions. So, we fix this.
		$extension = self::getExtensionForPath( $path );
		if ( !preg_match( "/\\.\\Q$extension\\E$/", $path ) ) {
			$pathWithGoodExtension = "$path.$extension";
		} else {
			$pathWithGoodExtension = $path;
		}

		// If no key was supplied, make one.  a mysql insertid would be totally
		// reasonable here, except that for historical reasons, the key is this
		// random thing instead.  At least it's not guessable.
		// Some things that when combined will make a suitably unique key.
		// see: http://www.jwz.org/doc/mid.html
		list( $usec, $sec ) = explode( ' ', microtime() );
		$usec = substr( $usec, 2 );
		$key = Wikimedia\base_convert( $sec . $usec, 10, 36 ) . '.' .
			Wikimedia\base_convert( (string)mt_rand(), 10, 36 ) . '.' .
			$this->user->getId() . '.' .
			$extension;

		$this->fileProps[$key] = $fileProps;

		if ( !preg_match( self::KEY_FORMAT_REGEX, $key ) ) {
			throw new UploadStashBadPathException(
				wfMessage( 'uploadstash-bad-path-bad-format', $key )
			);
		}

		wfDebug( __METHOD__ . " key for '$path': $key" );

		// if not already in a temporary area, put it there
		$storeStatus = $this->repo->storeTemp( basename( $pathWithGoodExtension ), $path );

		if ( !$storeStatus->isOK() ) {
			// It is a convention in MediaWiki to only return one error per API
			// exception, even if multiple errors are available. We use reset()
			// to pick the "first" thing that was wrong, preferring errors to
			// warnings. This is a bit lame, as we may have more info in the
			// $storeStatus and we're throwing it away, but to fix it means
			// redesigning API errors significantly.
			// $storeStatus->value just contains the virtual URL (if anything)
			// which is probably useless to the caller.
			$error = $storeStatus->getErrorsArray();
			$error = reset( $error );
			if ( !count( $error ) ) {
				$error = $storeStatus->getWarningsArray();
				$error = reset( $error );
				if ( !count( $error ) ) {
					$error = [ 'unknown', 'no error recorded' ];
				}
			}
			// At this point, $error should contain the single "most important"
			// error, plus any parameters.
			$errorMsg = array_shift( $error );
			throw new UploadStashFileException( wfMessage( $errorMsg, $error ) );
		}
		$stashPath = $storeStatus->value;

		// fetch the current user ID
		if ( !$this->user->isRegistered() ) {
			throw new UploadStashNotLoggedInException(
				wfMessage( 'uploadstash-not-logged-in' )
			);
		}

		// insert the file metadata into the db.
		wfDebug( __METHOD__ . " inserting $stashPath under $key" );
		$dbw = $this->repo->getPrimaryDB();

		$serializedFileProps = serialize( $fileProps );
		if ( strlen( $serializedFileProps ) > self::MAX_US_PROPS_SIZE ) {
			// Database is going to truncate this and make the field invalid.
			// Prioritize important metadata over file handler metadata.
			// File handler should be prepared to regenerate invalid metadata if needed.
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

		$dbw->insert(
			'uploadstash',
			$insertRow,
			__METHOD__
		);

		// store the insertid in the class variable so immediate retrieval
		// (possibly laggy) isn't necessary.
		$insertRow['us_id'] = $dbw->insertId();

		$this->fileMetadata[$key] = $insertRow;

		# create the UploadStashFile object for this file.
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
		$dbw->delete(
			'uploadstash',
			[ 'us_user' => $this->user->getId() ],
			__METHOD__
		);

		# destroy objects.
		$this->files = [];
		$this->fileMetadata = [];

		return true;
	}

	/**
	 * Remove a particular file from the stash.  Also removes it from the repo.
	 *
	 * @param string $key
	 * @throws UploadStashNoSuchKeyException|UploadStashNotLoggedInException
	 * @throws UploadStashWrongOwnerException
	 * @return bool Success
	 */
	public function removeFile( $key ) {
		if ( !$this->user->isRegistered() ) {
			throw new UploadStashNotLoggedInException(
				wfMessage( 'uploadstash-not-logged-in' )
			);
		}

		$dbw = $this->repo->getPrimaryDB();

		// this is a cheap query. it runs on the primary DB so that this function
		// still works when there's lag. It won't be called all that often.
		$row = $dbw->selectRow(
			'uploadstash',
			'us_user',
			[ 'us_key' => $key ],
			__METHOD__
		);

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
	 * Remove a file (see removeFile), but doesn't check ownership first.
	 *
	 * @param string $key
	 * @return bool Success
	 */
	public function removeFileNoAuth( $key ) {
		wfDebug( __METHOD__ . " clearing row $key" );

		// Ensure we have the UploadStashFile loaded for this key
		$this->getFile( $key, true );

		$dbw = $this->repo->getPrimaryDB();

		$dbw->delete(
			'uploadstash',
			[ 'us_key' => $key ],
			__METHOD__
		);

		/** @todo Look into UnregisteredLocalFile and find out why the rv here is
		 *  sometimes wrong (false when file was removed). For now, ignore.
		 */
		$this->files[$key]->remove();

		unset( $this->files[$key] );
		unset( $this->fileMetadata[$key] );

		return true;
	}

	/**
	 * List all files in the stash.
	 *
	 * @throws UploadStashNotLoggedInException
	 * @return array|false
	 */
	public function listFiles() {
		if ( !$this->user->isRegistered() ) {
			throw new UploadStashNotLoggedInException(
				wfMessage( 'uploadstash-not-logged-in' )
			);
		}

		$dbr = $this->repo->getReplicaDB();
		$res = $dbr->select(
			'uploadstash',
			'us_key',
			[ 'us_user' => $this->user->getId() ],
			__METHOD__
		);

		if ( !is_object( $res ) || $res->numRows() == 0 ) {
			// nothing to do.
			return false;
		}

		// finish the read before starting writes.
		$keys = [];
		foreach ( $res as $row ) {
			array_push( $keys, $row->us_key );
		}

		return $keys;
	}

	/**
	 * Find or guess extension -- ensuring that our extension matches our MIME type.
	 * Since these files are constructed from php tempnames they may not start off
	 * with an extension.
	 * XXX this is somewhat redundant with the checks that ApiUpload.php does with incoming
	 * uploads versus the desired filename. Maybe we can get that passed to us...
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
			// The file should already be checked for being evil.
			// However, if somehow we got here, we definitely
			// don't want to give it an extension of .php and
			// put it in a web accessible directory.
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
		// populate $fileMetadata[$key]
		if ( $readFromDB === DB_PRIMARY ) {
			// sometimes reading from the primary DB is necessary, if there's replication lag.
			$dbr = $this->repo->getPrimaryDB();
		} else {
			$dbr = $this->repo->getReplicaDB();
		}

		$row = $dbr->selectRow(
			'uploadstash',
			[
				'us_user', 'us_key', 'us_orig_path', 'us_path', 'us_props',
				'us_size', 'us_sha1', 'us_mime', 'us_media_type',
				'us_image_width', 'us_image_height', 'us_image_bits',
				'us_source_type', 'us_timestamp', 'us_status',
			],
			[ 'us_key' => $key ],
			__METHOD__
		);

		if ( !is_object( $row ) ) {
			// key wasn't present in the database. this will happen sometimes.
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
		$file = new UploadStashFile( $this->repo, $this->fileMetadata[$key]['us_path'], $key );
		if ( $file->getSize() === 0 ) {
			throw new UploadStashZeroLengthFileException(
				wfMessage( 'uploadstash-zero-length' )
			);
		}
		$this->files[$key] = $file;

		return true;
	}
}
