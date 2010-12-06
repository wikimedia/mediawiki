<?php
/** 
 * UploadStash is intended to accomplish a few things:
 *   - enable applications to temporarily stash files without publishing them to the wiki.
 *      - Several parts of MediaWiki do this in similar ways: UploadBase, UploadWizard, and FirefoggChunkedExtension
 *        And there are several that reimplement stashing from scratch, in idiosyncratic ways. The idea is to unify them all here.
 *	  Mostly all of them are the same except for storing some custom fields, which we subsume into the data array.
 *   - enable applications to find said files later, as long as the session or temp files haven't been purged. 
 *   - enable the uploading user (and *ONLY* the uploading user) to access said files, and thumbnails of said files, via a URL.
 *     We accomplish this by making the session serve as a URL->file mapping, on the assumption that nobody else can access 
 *     the session, even the uploading user. See SpecialUploadStash, which implements a web interface to some files stored this way.
 */
class UploadStash {

	// Format of the key for files -- has to be suitable as a filename itself (e.g. ab12cd34ef.jpg)
	const KEY_FORMAT_REGEX = '/^[\w-]+\.\w+$/';

	// repository that this uses to store temp files
	// public because we sometimes need to get a LocalFile within the same repo.
	public $repo; 
	
	// array of initialized objects obtained from session (lazily initialized upon getFile())
	private $files = array();  
	
	// Session ID
	private $sessionID;
	
	// Cache to store stash metadata in
	private $cache;

	// TODO: Once UploadBase starts using this, switch to use these constants rather than UploadBase::SESSION*
	// const SESSION_VERSION = 2;
	// const SESSION_KEYNAME = 'wsUploadData';

	/**
	 * Represents the session which contains temporarily stored files.
	 * Designed to be compatible with the session stashing code in UploadBase (should replace it eventually)
	 *
	 * @param $repo FileRepo: optional -- repo in which to store files. Will choose LocalRepo if not supplied.
	 */
	public function __construct( $repo = null ) { 

		if ( is_null( $repo ) ) {
			$repo = RepoGroup::singleton()->getLocalRepo();
		}

		$this->repo = $repo;

		if ( session_id() === '' ) {
			// FIXME: Should we just start a session in this case?
			// Anonymous uploading could be allowed
			throw new UploadStashNotAvailableException( 'no session ID' );
		}
		$this->sessionID = '';
		$this->cache = wfGetCache( CACHE_ANYTHING );
	}

	/**
	 * Get a file and its metadata from the stash.
	 * May throw exception if session data cannot be parsed due to schema change, or key not found.
	 *
	 * @param $key Integer: key
	 * @throws UploadStashFileNotFoundException
	 * @throws UploadStashBadVersionException
	 * @return UploadStashFile
	 */
	public function getFile( $key ) {
		if ( ! preg_match( self::KEY_FORMAT_REGEX, $key ) ) {
			throw new UploadStashBadPathException( "key '$key' is not in a proper format" );
		} 

		if ( !isset( $this->files[$key] ) ) {
			$cacheKey = wfMemcKey( 'uploadstash', $this->sessionID, $key );
			$data = $this->cache->get( $cacheKey );
			if ( !$data ) {
				throw new UploadStashFileNotFoundException( "key '$key' not found in stash" );
			}

			$this->files[$key] = $this->getFileFromData( $data );

		}
		return $this->files[$key];
	}
	
	protected function getFileFromData( $data ) {
		// guards against PHP class changing while session data doesn't
		if ( $data['version'] !== UploadBase::SESSION_VERSION ) {
			throw new UploadStashBadVersionException( $data['version'] . " does not match current version " . UploadBase::SESSION_VERSION );
		}
	
		// separate the stashData into the path, and then the rest of the data
		$path = $data['mTempPath'];
		unset( $data['mTempPath'] );

		$file = new UploadStashFile( $this, $this->repo, $path, $key, $data );
		if ( $file->getSize() === 0 ) {
			throw new UploadStashZeroLengthFileException( "File is zero length" );
		}
		return $file;
	}

	/**
	 * Stash a file in a temp directory and record that we did this in the session, along with other metadata.
	 * We store data in a flat key-val namespace because that's how UploadBase did it. This also means we have to
	 * ensure that the key-val pairs in $data do not overwrite other required fields.
	 *
	 * @param $path String: path to file you want stashed
	 * @param $data Array: optional, other data you want associated with the file. Do not use 'mTempPath', 'mFileProps', 'mFileSize', or 'version' as keys here
	 * @param $key String: optional, unique key for this file in this session. Used for directory hashing when storing, otherwise not important
	 * @throws UploadStashBadPathException
	 * @throws UploadStashFileException
	 * @return UploadStashFile: file, or null on failure
	 */
	public function stashFile( $path, $data = array(), $key = null ) {
		if ( ! file_exists( $path ) ) {
			wfDebug( "UploadStash: tried to stash file at '$path', but it doesn't exist\n" );
			throw new UploadStashBadPathException( "path doesn't exist" );
		}
                $fileProps = File::getPropsFromPath( $path );

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

		// If no key was supplied, use content hash. Also has the nice property of collapsing multiple identical files
		// uploaded this session, which could happen if uploads had failed.
		if ( is_null( $key ) ) {
			$key = $fileProps['sha1'] . "." . $extension;
		}

		if ( ! preg_match( self::KEY_FORMAT_REGEX, $key ) ) {
			throw new UploadStashBadPathException( "key '$key' is not in a proper format" );
		} 


		// if not already in a temporary area, put it there
		$status = $this->repo->storeTemp( basename( $path ), $path );

		if( ! $status->isOK() ) {
			// It is a convention in MediaWiki to only return one error per API exception, even if multiple errors
			// are available. We use reset() to pick the "first" thing that was wrong, preferring errors to warnings.
			// This is a bit lame, as we may have more info in the $status and we're throwing it away, but to fix it means
			// redesigning API errors significantly.
			// $status->value just contains the virtual URL (if anything) which is probably useless to the caller
			$error = reset( $status->getErrorsArray() );
			if ( ! count( $error ) ) {
				$error = reset( $status->getWarningsArray() );
				if ( ! count( $error ) ) {
					$error = array( 'unknown', 'no error recorded' );
				}
			}
			throw new UploadStashFileException( "error storing file in '$path': " . implode( '; ', $error ) );
		}
		$stashPath = $status->value;

		// required info we always store. Must trump any other application info in $data
		// 'mTempPath', 'mFileSize', and 'mFileProps' are arbitrary names
		// chosen for compatibility with UploadBase's way of doing this.
		$requiredData = array( 
			'mTempPath' => $stashPath,
			'mFileSize' => $fileProps['size'],
			'mFileProps' =>	$fileProps,
			'version' => UploadBase::SESSION_VERSION
		);

		// now, merge required info and extra data into the session. (The extra data changes from application to application.
		// UploadWizard wants different things than say FirefoggChunkedUpload.)
		$finalData = array_merge( $data, $requiredData );
		
		global $wgUploadStashExpiry;
		wfDebug( __METHOD__ . " storing under $key\n" );
		$cacheKey = wfMemcKey( 'uploadstash', $this->sessionID, $key );
		$this->cache->set( $cacheKey, array_merge( $data, $requiredData ), $wgUploadStashExpiry );
		
		$this->files[$key] = $this->getFileFromData( $data );
		return $this->files[$key];
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

}

class UploadStashFile extends UnregisteredLocalFile {
	private $sessionStash;
	private $sessionKey;
	private $sessionData;
	private $urlName;

	/**
	 * A LocalFile wrapper around a file that has been temporarily stashed, so we can do things like create thumbnails for it
	 * Arguably UnregisteredLocalFile should be handling its own file repo but that class is a bit retarded currently
	 *
	 * @param $stash UploadStash: useful for obtaining config, stashing transformed files
	 * @param $repo FileRepo: repository where we should find the path
	 * @param $path String: path to file
	 * @param $key String: key to store the path and any stashed data under
	 * @param $data String: any other data we want stored with this file
	 * @throws UploadStashBadPathException
	 * @throws UploadStashFileNotFoundException
	 */
	public function __construct( $stash, $repo, $path, $key, $data ) { 
		$this->sessionStash = $stash;
		$this->sessionKey = $key;
		$this->sessionData = $data;

		// resolve mwrepo:// urls
		if ( $repo->isVirtualUrl( $path ) ) {
			$path = $repo->resolveVirtualUrl( $path );	
		}

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
	 * Return the file/url base name of a thumbnail with the specified parameters
	 *
	 * @param $params Array: handler-specific parameters
	 * @return String: base name for URL, like '120px-12345.jpg', or null if there is no handler
	 */
	function thumbName( $params ) {
		return $this->getParamThumbName( $this->getUrlName(), $params );
	}


	/**
	 * Given the name of the original, i.e. Foo.jpg, and scaling parameters, returns filename with appropriate extension
	 * This is abstracted from getThumbName because we also use it to calculate the thumbname the file should have on 
	 * remote image scalers	
	 *
	 * @param String $urlName: A filename, like MyMovie.ogx
	 * @param Array $parameters: scaling parameters, like array( 'width' => '120' );
	 * @return String|null parameterized thumb name, like 120px-MyMovie.ogx.jpg, or null if no handler found
	 */
	function getParamThumbName( $urlName, $params ) {
		if ( !$this->getHandler() ) {
			return null;
		}
		$extension = $this->getExtension();
		list( $thumbExt, ) = $this->handler->getThumbType( $extension, $this->getMimeType(), $params );
		$thumbName = $this->getHandler()->makeParamString( $params ) . '-' . $urlName;
		if ( $thumbExt != $extension ) {
			$thumbName .= ".$thumbExt";
		}
		return $thumbName;
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
	 * (that's hidden in the session)
	 *
	 * @param $thumbName String: basename of thumbnail file -- however, we don't want to use the file exactly
	 * @return String: URL to access thumbnail, or URL with partial path
	 */
	public function getThumbUrl( $thumbName = false ) { 
		wfDebug( __METHOD__ . " getting for $thumbName \n" );
		return $this->getSpecialUrl( $thumbName );
	}

	/** 
	 * The basename for the URL, which we want to not be related to the filename.
	 * Will also be used as the lookup key for a thumbnail file.
	 *
	 * @return String: base url name, like '120px-123456.jpg'
	 */
	public function getUrlName() { 
		if ( ! $this->urlName ) {
			$this->urlName = $this->sessionKey;
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
			$this->url = $this->getSpecialUrl( $this->getUrlName() );
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
	 * Getter for session key (the session-unique id by which this file's location & metadata is stored in the session)
	 *
	 * @return String: session key
	 */
	public function getSessionKey() {
		return $this->sessionKey;
	}

	/**
	 * Remove the associated temporary file
	 * @return Status: success
	 */
	public function remove() {
		return $this->repo->freeTemp( $this->path );
	}

}

class UploadStashNotAvailableException extends MWException {};
class UploadStashFileNotFoundException extends MWException {};
class UploadStashBadPathException extends MWException {};
class UploadStashBadVersionException extends MWException {};
class UploadStashFileException extends MWException {};
class UploadStashZeroLengthFileException extends MWException {};

