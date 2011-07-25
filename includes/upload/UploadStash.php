<?php
/**
 * UploadStash is intended to accomplish a few things:
 *   - enable applications to temporarily stash files without publishing them to the wiki.
 *      - Several parts of MediaWiki do this in similar ways: UploadBase, UploadWizard, and FirefoggChunkedExtension
 *        And there are several that reimplement stashing from scratch, in idiosyncratic ways. The idea is to unify them all here.
 *	  Mostly all of them are the same except for storing some custom fields, which we subsume into the data array.
 *   - enable applications to find said files later, as long as the db table or temp files haven't been purged.
 *   - enable the uploading user (and *ONLY* the uploading user) to access said files, and thumbnails of said files, via a URL.
 *     We accomplish this using a database table, with ownership checking as you might expect. See SpecialUploadStash, which
 *     implements a web interface to some files stored this way.
 *
 * UploadStash represents the entire stash of temporary files.
 * UploadStashFile is a filestore for the actual physical disk files.
 * UploadFromStash extends UploadBase, and represents a single stashed file as it is moved from the stash to the regular file repository
 */
class UploadStash {

	// Format of the key for files -- has to be suitable as a filename itself (e.g. ab12cd34ef.jpg)
	const KEY_FORMAT_REGEX = '/^[\w-\.]+\.\w*$/';

	// When a given stashed file can't be loaded, wait for the slaves to catch up.  If they're more than MAX_LAG
	// behind, throw an exception instead. (at what point is broken better than slow?)
	const MAX_LAG = 30;

	// Age of the repository in hours.  That is, after how long will files be assumed abandoned and deleted?
	const REPO_AGE = 6;

	/**
	 * repository that this uses to store temp files
	 * public because we sometimes need to get a LocalFile within the same repo.
	 *
	 * @var LocalRepo
	 */
	public $repo;

	// array of initialized repo objects
	protected $files = array();

	// cache of the file metadata that's stored in the database
	protected $fileMetadata = array();

	// fileprops cache
	protected $fileProps = array();

	// current user
	protected $user, $userId, $isLoggedIn;

	/**
	 * Represents a temporary filestore, with metadata in the database.
	 * Designed to be compatible with the session stashing code in UploadBase (should replace it eventually)
	 *
	 * @param $repo FileRepo
	 */
	public function __construct( $repo, $user = null ) {
		// this might change based on wiki's configuration.
		$this->repo = $repo;

		// if a user was passed, use it. otherwise, attempt to use the global.
		// this keeps FileRepo from breaking when it creates an UploadStash object
		if ( $user ) {
			$this->user = $user;
		} else {
			global $wgUser;
			$this->user = $wgUser;
		}

		if ( is_object( $this->user ) ) {
			$this->userId = $this->user->getId();
			$this->isLoggedIn = $this->user->isLoggedIn();
		}
	}

	/**
	 * Get a file and its metadata from the stash.
	 *
	 * @param $key String: key under which file information is stored
	 * @param $noAuth Boolean (optional) Don't check authentication. Used by maintenance scripts.
	 * @throws UploadStashFileNotFoundException
	 * @throws UploadStashNotLoggedInException
	 * @throws UploadStashWrongOwnerException
	 * @throws UploadStashBadPathException
	 * @return UploadStashFile
	 */
	public function getFile( $key, $noAuth = false ) {

		if ( ! preg_match( self::KEY_FORMAT_REGEX, $key ) ) {
			throw new UploadStashBadPathException( "key '$key' is not in a proper format" );
		}

		if ( !$noAuth ) {
			if ( !$this->isLoggedIn ) {
				throw new UploadStashNotLoggedInException( __METHOD__ . ' No user is logged in, files must belong to users' );
			}
		}

		$dbr = $this->repo->getSlaveDb();

		if ( !isset( $this->fileMetadata[$key] ) ) {
			// try this first.  if it fails to find the row, check for lag, wait, try again. if its still missing, throw an exception.
			// this more complex solution keeps things moving for page loads with many requests
			// (ie. validating image ownership) when replag is high
			if ( !$this->fetchFileMetadata( $key ) ) {
				$lag = $dbr->getLag();
				if ( $lag > 0 && $lag <= self::MAX_LAG ) {
					// if there's not too much replication lag, just wait for the slave to catch up to our last insert.
					sleep( ceil( $lag ) );
				} elseif ( $lag > self::MAX_LAG ) {
					// that's a lot of lag to introduce into the middle of the UI.
					throw new UploadStashMaxLagExceededException(
						'Couldn\'t load stashed file metadata, and replication lag is above threshold: (MAX_LAG=' . self::MAX_LAG . ')'
					);
				}

				// now that the waiting has happened, try again
				$this->fetchFileMetadata( $key );
			}

			if ( !isset( $this->fileMetadata[$key] ) ) {
				throw new UploadStashFileNotFoundException( "key '$key' not found in stash" );
			}

			// create $this->files[$key]
			$this->initFile( $key );

			// fetch fileprops
			$path = $this->fileMetadata[$key]['us_path'];
			if ( $this->repo->isVirtualUrl( $path ) ) {
				$path = $this->repo->resolveVirtualUrl( $path );
			}
			$this->fileProps[$key] = File::getPropsFromPath( $path );
		}

		if ( ! $this->files[$key]->exists() ) {
			wfDebug( __METHOD__ . " tried to get file at $key, but it doesn't exist\n" );
			throw new UploadStashBadPathException( "path doesn't exist" );
		}

		if ( !$noAuth ) {
			if ( $this->fileMetadata[$key]['us_user'] != $this->userId ) {
				throw new UploadStashWrongOwnerException( "This file ($key) doesn't belong to the current user." );
			}
		}

		return $this->files[$key];
	}

	/**
	 * Getter for file metadata.
	 *
	 * @param key String: key under which file information is stored
	 * @return Array
	 */
	public function getMetadata ( $key ) {
		$this->getFile( $key );
		return $this->fileMetadata[$key];
	}

	/**
	 * Getter for fileProps
	 *
	 * @param key String: key under which file information is stored
	 * @return Array
	 */
	public function getFileProps ( $key ) {
		$this->getFile( $key );
		return $this->fileProps[$key];
	}

	/**
	 * Stash a file in a temp directory and record that we did this in the database, along with other metadata.
	 *
	 * @param $path String: path to file you want stashed
	 * @param $sourceType String: the type of upload that generated this file (currently, I believe, 'file' or null)
	 * @param $key String: optional, unique key for this file. Used for directory hashing when storing, otherwise not important
	 * @throws UploadStashBadPathException
	 * @throws UploadStashFileException
	 * @throws UploadStashNotLoggedInException
	 * @return UploadStashFile: file, or null on failure
	 */
	public function stashFile( $path, $sourceType = null, $key = null ) {
		if ( ! file_exists( $path ) ) {
			wfDebug( __METHOD__ . " tried to stash file at '$path', but it doesn't exist\n" );
			throw new UploadStashBadPathException( "path doesn't exist" );
		}
		$fileProps = File::getPropsFromPath( $path );
		wfDebug( __METHOD__ . " stashing file at '$path'\n" );

		// we will be initializing from some tmpnam files that don't have extensions.
		// most of MediaWiki assumes all uploaded files have good extensions. So, we fix this.
		$extension = self::getExtensionForPath( $path );
		if ( ! preg_match( "/\\.\\Q$extension\\E$/", $path ) ) {
			$pathWithGoodExtension = "$path.$extension";
			if ( ! rename( $path, $pathWithGoodExtension ) ) {
				throw new UploadStashFileException( "couldn't rename $path to have a better extension at $pathWithGoodExtension" );
			}
			$path = $pathWithGoodExtension;
		}

		// If no key was supplied, make one.  a mysql insertid would be totally reasonable here, except
		// that some users of this function might expect to supply the key instead of using the generated one.
		if ( is_null( $key ) ) {
			// some things that when combined will make a suitably unique key.
			// see: http://www.jwz.org/doc/mid.html
			list ($usec, $sec) = explode( ' ', microtime() );
			$usec = substr($usec, 2);
			$key = wfBaseConvert( $sec . $usec, 10, 36 ) . '.' .
				wfBaseConvert( mt_rand(), 10, 36 ) . '.'.
				$this->userId . '.' . 
				$extension;
		}

		$this->fileProps[$key] = $fileProps;

		if ( ! preg_match( self::KEY_FORMAT_REGEX, $key ) ) {
			throw new UploadStashBadPathException( "key '$key' is not in a proper format" );
		}

		wfDebug( __METHOD__ . " key for '$path': $key\n" );

		// if not already in a temporary area, put it there
		$storeStatus = $this->repo->storeTemp( basename( $path ), $path );

		if ( ! $storeStatus->isOK() ) {
			// It is a convention in MediaWiki to only return one error per API exception, even if multiple errors
			// are available. We use reset() to pick the "first" thing that was wrong, preferring errors to warnings.
			// This is a bit lame, as we may have more info in the $storeStatus and we're throwing it away, but to fix it means
			// redesigning API errors significantly.
			// $storeStatus->value just contains the virtual URL (if anything) which is probably useless to the caller
			$error = $storeStatus->getErrorsArray();
			$error = reset( $error );
			if ( ! count( $error ) ) {
				$error = $storeStatus->getWarningsArray();
				$error = reset( $error );
				if ( ! count( $error ) ) {
					$error = array( 'unknown', 'no error recorded' );
				}
			}
			throw new UploadStashFileException( "error storing file in '$path': " . implode( '; ', $error ) );
		}
		$stashPath = $storeStatus->value;

		// fetch the current user ID
		if ( !$this->isLoggedIn ) {
			throw new UploadStashNotLoggedInException( __METHOD__ . ' No user is logged in, files must belong to users' );
		}

		// insert the file metadata into the db.
		wfDebug( __METHOD__ . " inserting $stashPath under $key\n" );
		$dbw = $this->repo->getMasterDb();

		// select happens on the master so this can all be in a transaction, which
		// avoids a race condition that's likely with multiple people uploading from the same
		// set of files
		$dbw->begin();
		// first, check to see if it's already there.
		$row = $dbw->selectRow(
			'uploadstash',
			'us_user, us_timestamp',
			array( 'us_key' => $key ),
			__METHOD__
		);

		// The current user can't have this key if:
		// - the key is owned by someone else and
		// - the age of the key is less than REPO_AGE
		if ( is_object( $row ) ) {
			if ( $row->us_user != $this->userId &&
				$row->wfTimestamp( TS_UNIX, $row->us_timestamp ) > time() - UploadStash::REPO_AGE * 3600
			) {
				$dbw->rollback();
				throw new UploadStashWrongOwnerException( "Attempting to upload a duplicate of a file that someone else has stashed" );
			}
		}

		$this->fileMetadata[$key] = array(
			'us_user' => $this->userId,
			'us_key' => $key,
			'us_orig_path' => $path,
			'us_path' => $stashPath,
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
		);

		// if a row exists but previous checks on it passed, let the current user take over this key.
		$dbw->replace(
			'uploadstash',
			'us_key',
			$this->fileMetadata[$key],
			__METHOD__
		);
		$dbw->commit();

		// store the insertid in the class variable so immediate retrieval (possibly laggy) isn't necesary.
		$this->fileMetadata[$key]['us_id'] = $dbw->insertId();

		# create the UploadStashFile object for this file.
		$this->initFile( $key );

		return $this->getFile( $key );
	}

	/**
	 * Remove all files from the stash.
	 * Does not clean up files in the repo, just the record of them.
	 *
	 * @throws UploadStashNotLoggedInException
	 * @return boolean: success
	 */
	public function clear() {
		if ( !$this->isLoggedIn ) {
			throw new UploadStashNotLoggedInException( __METHOD__ . ' No user is logged in, files must belong to users' );
		}

		wfDebug( __METHOD__ . " clearing all rows for user $userId\n" );
		$dbw = $this->repo->getMasterDb();
		$dbw->delete(
			'uploadstash',
			array( 'us_user' => $this->userId ),
			__METHOD__
		);

		# destroy objects.
		$this->files = array();
		$this->fileMetadata = array();

		return true;
	}

	/**
	 * Remove a particular file from the stash.  Also removes it from the repo.
	 *
	 * @throws UploadStashNotLoggedInException
	 * @throws UploadStashWrongOwnerException
	 * @return boolean: success
	 */
	public function removeFile( $key ) {
		if ( !$this->isLoggedIn ) {
			throw new UploadStashNotLoggedInException( __METHOD__ . ' No user is logged in, files must belong to users' );
		}

		$dbw = $this->repo->getMasterDb();

		// this is a cheap query. it runs on the master so that this function still works when there's lag.
		// it won't be called all that often.
		$row = $dbw->selectRow(
			'uploadstash',
			'us_user',
			array( 'us_key' => $key ),
			__METHOD__
		);

		if( !$row ) {
			throw new UploadStashNoSuchKeyException( "No such key ($key), cannot remove" );
		}

		if ( $row->us_user != $this->userId ) {
			throw new UploadStashWrongOwnerException( "Can't delete: the file ($key) doesn't belong to this user." );
		}

		return $this->removeFileNoAuth( $key );
	}


	/**
	 * Remove a file (see removeFile), but doesn't check ownership first.
	 *
	 * @return boolean: success
	 */
	public function removeFileNoAuth( $key ) {
		wfDebug( __METHOD__ . " clearing row $key\n" );

		$dbw = $this->repo->getMasterDb();

		// this gets its own transaction since it's called serially by the cleanupUploadStash maintenance script
		$dbw->begin();
		$dbw->delete(
			'uploadstash',
			array( 'us_key' => $key ),
			__METHOD__
		);
		$dbw->commit();

		// TODO: look into UnregisteredLocalFile and find out why the rv here is sometimes wrong (false when file was removed)
		// for now, ignore.
		$this->files[$key]->remove();

		unset( $this->files[$key] );
		unset( $this->fileMetadata[$key] );

		return true;
	}

	/**
	 * List all files in the stash.
	 *
	 * @throws UploadStashNotLoggedInException
	 * @return Array
	 */
	public function listFiles() {
		if ( !$this->isLoggedIn ) {
			throw new UploadStashNotLoggedInException( __METHOD__ . ' No user is logged in, files must belong to users' );
		}

		$dbr = $this->repo->getSlaveDb();
		$res = $dbr->select(
			'uploadstash',
			'us_key',
			array( 'us_key' => $key ),
			__METHOD__
		);

		if ( !is_object( $res ) || $res->numRows() == 0 ) {
			// nothing to do.
			return false;
		}

		// finish the read before starting writes.
		$keys = array();
		foreach ( $res as $row ) {
			array_push( $keys, $row->us_key );
		}

		return $keys;
	}

	/**
	 * Find or guess extension -- ensuring that our extension matches our mime type.
	 * Since these files are constructed from php tempnames they may not start off
	 * with an extension.
	 * XXX this is somewhat redundant with the checks that ApiUpload.php does with incoming
	 * uploads versus the desired filename. Maybe we can get that passed to us...
	 */
	public static function getExtensionForPath( $path ) {
		// Does this have an extension?
		$n = strrpos( $path, '.' );
		$extension = null;
		if ( $n !== false ) {
			$extension = $n ? substr( $path, $n + 1 ) : '';
		} else {
			// If not, assume that it should be related to the mime type of the original file.
			$magic = MimeMagic::singleton();
			$mimeType = $magic->guessMimeType( $path );
			$extensions = explode( ' ', MimeMagic::singleton()->getExtensionsForType( $mimeType ) );
			if ( count( $extensions ) ) {
				$extension = $extensions[0];
			}
		}

		if ( is_null( $extension ) ) {
			throw new UploadStashFileException( "extension is null" );
		}

		return File::normalizeExtension( $extension );
	}

	/**
	 * Helper function: do the actual database query to fetch file metadata.
	 *
	 * @param $key String: key
	 * @return boolean
	 */
	protected function fetchFileMetadata( $key ) {
		// populate $fileMetadata[$key]
		$dbr = $this->repo->getSlaveDb();
		$row = $dbr->selectRow(
			'uploadstash',
			'*',
			array( 'us_key' => $key ),
			__METHOD__
		);

		if ( !is_object( $row ) ) {
			// key wasn't present in the database. this will happen sometimes.
			return false;
		}

		$this->fileMetadata[$key] = array(
			'us_user' => $row->us_user,
			'us_key' => $row->us_key,
			'us_orig_path' => $row->us_orig_path,
			'us_path' => $row->us_path,
			'us_size' => $row->us_size,
			'us_sha1' => $row->us_sha1,
			'us_mime' => $row->us_mime,
			'us_media_type' => $row->us_media_type,
			'us_image_width' => $row->us_image_width,
			'us_image_height' => $row->us_image_height,
			'us_image_bits' => $row->us_image_bits,
			'us_source_type' => $row->us_source_type,
			'us_timestamp' => $row->us_timestamp,
			'us_status' => $row->us_status
		);

		return true;
	}

	/**
	 * Helper function: Initialize the UploadStashFile for a given file.
	 *
	 * @param $path String: path to file
	 * @param $key String: key under which to store the object
	 * @throws UploadStashZeroLengthFileException
	 * @return bool
	 */
	protected function initFile( $key ) {
		$file = new UploadStashFile( $this->repo, $this->fileMetadata[$key]['us_path'], $key );
		if ( $file->getSize() === 0 ) {
			throw new UploadStashZeroLengthFileException( "File is zero length" );
		}
		$this->files[$key] = $file;
		return true;
	}
}

class UploadStashFile extends UnregisteredLocalFile {
	private $fileKey;
	private $urlName;
	protected $url;

	/**
	 * A LocalFile wrapper around a file that has been temporarily stashed, so we can do things like create thumbnails for it
	 * Arguably UnregisteredLocalFile should be handling its own file repo but that class is a bit retarded currently
	 *
	 * @param $repo FSRepo: repository where we should find the path
	 * @param $path String: path to file
	 * @param $key String: key to store the path and any stashed data under
	 * @throws UploadStashBadPathException
	 * @throws UploadStashFileNotFoundException
	 */
	public function __construct( $repo, $path, $key ) {
		$this->fileKey = $key;

		// resolve mwrepo:// urls
		if ( $repo->isVirtualUrl( $path ) ) {
			$path = $repo->resolveVirtualUrl( $path );
		} else {

			// check if path appears to be sane, no parent traversals, and is in this repo's temp zone.
			$repoTempPath = $repo->getZonePath( 'temp' );
			if ( ( ! $repo->validateFilename( $path ) ) ||
					( strpos( $path, $repoTempPath ) !== 0 ) ) {
				wfDebug( "UploadStash: tried to construct an UploadStashFile from a file that should already exist at '$path', but path is not valid\n" );
				throw new UploadStashBadPathException( 'path is not valid' );
			}

			// check if path exists! and is a plain file.
			if ( ! $repo->fileExists( $path, FileRepo::FILES_ONLY ) ) {
				wfDebug( "UploadStash: tried to construct an UploadStashFile from a file that should already exist at '$path', but path is not found\n" );
				throw new UploadStashFileNotFoundException( 'cannot find path, or not a plain file' );
			}
		}

		parent::__construct( false, $repo, $path, false );

		$this->name = basename( $this->path );
	}

	/**
	 * A method needed by the file transforming and scaling routines in File.php
	 * We do not necessarily care about doing the description at this point
	 * However, we also can't return the empty string, as the rest of MediaWiki demands this (and calls to imagemagick
	 * convert require it to be there)
	 *
	 * @return String: dummy value
	 */
	public function getDescriptionUrl() {
		return $this->getUrl();
	}

	/**
	 * Get the path for the thumbnail (actually any transformation of this file)
	 * The actual argument is the result of thumbName although we seem to have
	 * buggy code elsewhere that expects a boolean 'suffix'
	 *
	 * @param $thumbName String: name of thumbnail (e.g. "120px-123456.jpg" ), or false to just get the path
	 * @return String: path thumbnail should take on filesystem, or containing directory if thumbname is false
	 */
	public function getThumbPath( $thumbName = false ) {
		$path = dirname( $this->path );
		if ( $thumbName !== false ) {
			$path .= "/$thumbName";
		}
		return $path;
	}

	/**
	 * Return the file/url base name of a thumbnail with the specified parameters.
	 * We override this because we want to use the pretty url name instead of the
	 * ugly file name.
	 *
	 * @param $params Array: handler-specific parameters
	 * @return String: base name for URL, like '120px-12345.jpg', or null if there is no handler
	 */
	function thumbName( $params ) {
		return $this->generateThumbName( $this->getUrlName(), $params );
	}

	/**
	 * Helper function -- given a 'subpage', return the local URL e.g. /wiki/Special:UploadStash/subpage
	 * @param {String} $subPage
	 * @return {String} local URL for this subpage in the Special:UploadStash space.
	 */
	private function getSpecialUrl( $subPage ) {
		return SpecialPage::getTitleFor( 'UploadStash', $subPage )->getLocalURL();
	}

	/**
	 * Get a URL to access the thumbnail
	 * This is required because the model of how files work requires that
	 * the thumbnail urls be predictable. However, in our model the URL is not based on the filename
	 * (that's hidden in the db)
	 *
	 * @param $thumbName String: basename of thumbnail file -- however, we don't want to use the file exactly
	 * @return String: URL to access thumbnail, or URL with partial path
	 */
	public function getThumbUrl( $thumbName = false ) {
		wfDebug( __METHOD__ . " getting for $thumbName \n" );
		return $this->getSpecialUrl( 'thumb/' . $this->getUrlName() . '/' . $thumbName );
	}

	/**
	 * The basename for the URL, which we want to not be related to the filename.
	 * Will also be used as the lookup key for a thumbnail file.
	 *
	 * @return String: base url name, like '120px-123456.jpg'
	 */
	public function getUrlName() {
		if ( ! $this->urlName ) {
			$this->urlName = $this->fileKey;
		}
		return $this->urlName;
	}

	/**
	 * Return the URL of the file, if for some reason we wanted to download it
	 * We tend not to do this for the original file, but we do want thumb icons
	 *
	 * @return String: url
	 */
	public function getUrl() {
		if ( !isset( $this->url ) ) {
			$this->url = $this->getSpecialUrl( 'file/' . $this->getUrlName() );
		}
		return $this->url;
	}

	/**
	 * Parent classes use this method, for no obvious reason, to return the path (relative to wiki root, I assume).
	 * But with this class, the URL is unrelated to the path.
	 *
	 * @return String: url
	 */
	public function getFullUrl() {
		return $this->getUrl();
	}

	/**
	 * Getter for file key (the unique id by which this file's location & metadata is stored in the db)
	 *
	 * @return String: file key
	 */
	public function getFileKey() {
		return $this->fileKey;
	}

	/**
	 * Remove the associated temporary file
	 * @return Status: success
	 */
	public function remove() {
		if ( !$this->repo->fileExists( $this->path, FileRepo::FILES_ONLY ) ) {
			// Maybe the file's already been removed? This could totally happen in UploadBase.
			return true;
		}

		return $this->repo->freeTemp( $this->path );
	}

	public function exists() {
		return $this->repo->fileExists( $this->path, FileRepo::FILES_ONLY );
	}

}

class UploadStashNotAvailableException extends MWException {};
class UploadStashFileNotFoundException extends MWException {};
class UploadStashBadPathException extends MWException {};
class UploadStashFileException extends MWException {};
class UploadStashZeroLengthFileException extends MWException {};
class UploadStashNotLoggedInException extends MWException {};
class UploadStashWrongOwnerException extends MWException {};
class UploadStashMaxLagExceededException extends MWException {};
class UploadStashNoSuchKeyException extends MWException {};
