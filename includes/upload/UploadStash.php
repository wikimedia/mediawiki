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
 *
 */
class UploadStash {
	// Format of the key for files -- has to be suitable as a filename itself in some cases.
	// This should encompass a sha1 content hash in hex (new style), or an integer (old style), 
	// and also thumbnails with prepended strings like "120px-". 
	// The file extension should not be part of the key.
	const KEY_FORMAT_REGEX = '/^[\w-]+$/';

	// repository that this uses to store temp files
	protected $repo; 
	
	// array of initialized objects obtained from session (lazily initialized upon getFile())
	private $files = array();  

	// the base URL for files in the stash
	private $baseUrl;
	
	// TODO: Once UploadBase starts using this, switch to use these constants rather than UploadBase::SESSION*
	// const SESSION_VERSION = 2;
	// const SESSION_KEYNAME = 'wsUploadData';

	/**
	 * Represents the session which contains temporarily stored files.
	 * Designed to be compatible with the session stashing code in UploadBase (should replace it eventually)
	 * @param {FileRepo} $repo: optional -- repo in which to store files. Will choose LocalRepo if not supplied.
	 */
	public function __construct( $repo = null ) { 

		if ( is_null( $repo ) ) {
			$repo = RepoGroup::singleton()->getLocalRepo();
		}

		$this->repo = $repo;

		if ( !isset( $_SESSION[UploadBase::SESSION_KEYNAME] ) ) {
			$_SESSION[UploadBase::SESSION_KEYNAME] = array();
		}
		
		$this->baseUrl = SpecialPage::getTitleFor( 'UploadStash' )->getLocalURL(); 
	}

	/**
	 * Get the base of URLs by which one can access the files 
	 * @return {String} url
	 */
	public function getBaseUrl() { 
		return $this->baseUrl;
	}

	/** 
	 * Get a file and its metadata from the stash.
	 * May throw exception if session data cannot be parsed due to schema change, or key not found.
	 * @param {Integer} $key: key
	 * @throws UploadStashFileNotFoundException
	 * @throws UploadStashBadVersionException
	 * @return {UploadStashItem} null if no such item or item out of date, or the item
	 */
	public function getFile( $key ) {
		if ( ! preg_match( self::KEY_FORMAT_REGEX, $key ) ) {
			throw new UploadStashBadPathException( "key '$key' is not in a proper format" );
		} 
 
		if ( !isset( $this->files[$key] ) ) {
			if ( !isset( $_SESSION[UploadBase::SESSION_KEYNAME][$key] ) ) {
				throw new UploadStashFileNotFoundException( "key '$key' not found in session" );
			}

			$data = $_SESSION[UploadBase::SESSION_KEYNAME][$key];
			// guards against PHP class changing while session data doesn't
			if ($data['version'] !== UploadBase::SESSION_VERSION ) {
				throw new UploadStashBadVersionException( $data['version'] . " does not match current version " . UploadBase::SESSION_VERSION );
			}
		
			// separate the stashData into the path, and then the rest of the data
			$path = $data['mTempPath'];
			unset( $data['mTempPath'] );

			$file = new UploadStashFile( $this, $this->repo, $path, $key, $data );
			
			$this->files[$key] = $file;

		}
		return $this->files[$key];
	}

	/**
	 * Stash a file in a temp directory and record that we did this in the session, along with other metadata.
	 * We store data in a flat key-val namespace because that's how UploadBase did it. This also means we have to
	 * ensure that the key-val pairs in $data do not overwrite other required fields.
	 *
	 * @param {String} $path: path to file you want stashed
	 * @param {Array} $data: optional, other data you want associated with the file. Do not use 'mTempPath', 'mFileProps', 'mFileSize', or 'version' as keys here
	 * @param {String} $key: optional, unique key for this file in this session. Used for directory hashing when storing, otherwise not important
	 * @throws UploadStashBadPathException
	 * @throws UploadStashFileException
	 * @return {null|UploadStashFile} file, or null on failure
	 */
	public function stashFile( $path, $data = array(), $key = null ) {
		if ( ! file_exists( $path ) ) {
			throw new UploadStashBadPathException( "path '$path' doesn't exist" );
		}
                $fileProps = File::getPropsFromPath( $path );

		// If no key was supplied, use content hash. Also has the nice property of collapsing multiple identical files
		// uploaded this session, which could happen if uploads had failed.
		if ( is_null( $key ) ) {
			$key = $fileProps['sha1'];
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
		$_SESSION[UploadBase::SESSION_KEYNAME][$key] = array_merge( $data, $requiredData );
		
		return $this->getFile( $key );
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
	 * @param {UploadStash} $stash: UploadStash, useful for obtaining config, stashing transformed files
	 * @param {FileRepo} $repo: repository where we should find the path
	 * @param {String} $path: path to file
	 * @param {String} $key: key to store the path and any stashed data under
	 * @param {String} $data: any other data we want stored with this file
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
			throw new UploadStashBadPathException( "path '$path' is not valid or is not in repo temp area: '$repoTempPath'" );
		}

		// check if path exists! and is a plain file.
		if ( ! $repo->fileExists( $path, FileRepo::FILES_ONLY ) ) {
			throw new UploadStashFileNotFoundException( "cannot find path '$path'" );
		}

		parent::__construct( false, $repo, $path, false );

		// we will be initializing from some tmpnam files that don't have extensions.
		// most of MediaWiki assumes all uploaded files have good extensions. So, we fix this.
		$this->name = basename( $this->path );
		$this->setExtension();

	}

	/**
	 * A method needed by the file transforming and scaling routines in File.php
	 * We do not necessarily care about doing the description at this point
	 * However, we also can't return the empty string, as the rest of MediaWiki demands this (and calls to imagemagick
	 * convert require it to be there)
	 * @return {String} dummy value
	 */
	public function getDescriptionUrl() {
		return $this->getUrl();
	}

	/**
	 * Find or guess extension -- ensuring that our extension matches our mime type.
	 * Since these files are constructed from php tempnames they may not start off 
	 * with an extension.
	 * This does not override getExtension() because things like getMimeType() already call getExtension(),
	 * and that results in infinite recursion. So, we preemptively *set* the extension so getExtension() can find it.
	 * For obvious reasons this should be called as early as possible, as part of initialization
	 */
	public function setExtension() { 	
		// Does this have an extension?
		$n = strrpos( $this->path, '.' );
		$extension = null;
		if ( $n !== false ) {
			$extension = $n ? substr( $this->path, $n + 1 ) : '';
		} else {
			// If not, assume that it should be related to the mime type of the original file.
			//
			// This entire thing is backwards -- we *should* just create an extension based on 
			// the mime type of the transformed file, *after* transformation.  But File.php demands 
			// to know the name of the transformed file before creating it. 
			$mimeType = $this->getMimeType();
			$extensions = explode( ' ', MimeMagic::singleton()->getExtensionsForType( $mimeType ) );
			if ( count( $extensions ) ) { 
				$extension = $extensions[0];	
			}
		}

		if ( is_null( $extension ) ) {
			throw new UploadStashFileException( "extension '$extension' is null" );
		}

		$this->extension = parent::normalizeExtension( $extension );
	}

	/**
	 * Get the path for the thumbnail (actually any transformation of this file)
	 * The actual argument is the result of thumbName although we seem to have 
	 * buggy code elsewhere that expects a boolean 'suffix'
	 *
	 * @param {String|false} $thumbName: name of thumbnail (e.g. "120px-123456.jpg" ), or false to just get the path
	 * @return {String} path thumbnail should take on filesystem, or containing directory if thumbname is false
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
	 * @param {Array} $params: handler-specific parameters
	 * @return {String|null} base name for URL, like '120px-12345.jpg', or null if there is no handler
	 */
	function thumbName( $params ) {
		if ( !$this->getHandler() ) {
			return null;
		}
		$extension = $this->getExtension();
		list( $thumbExt, $thumbMime ) = $this->handler->getThumbType( $extension, $this->getMimeType(), $params );
		$thumbName = $this->getHandler()->makeParamString( $params ) . '-' . $this->getUrlName();
		if ( $thumbExt != $extension ) {
			$thumbName .= ".$thumbExt";
		}
		return $thumbName;
	}

	/** 
	 * Get a URL to access the thumbnail 
	 * This is required because the model of how files work requires that 
	 * the thumbnail urls be predictable. However, in our model the URL is not based on the filename
	 * (that's hidden in the session)
	 *
	 * @param {String} $thumbName: basename of thumbnail file -- however, we don't want to use the file exactly
	 * @return {String} URL to access thumbnail, or URL with partial path
	 */
	public function getThumbUrl( $thumbName = false ) { 
		$path = $this->sessionStash->getBaseUrl();
		if ( $thumbName !== false ) {
			$path .= '/' . rawurlencode( $thumbName );
		}
		return $path;
	}

	/** 
	 * The basename for the URL, which we want to not be related to the filename.
	 * Will also be used as the lookup key for a thumbnail file.
	 * @return {String} base url name, like '120px-123456.jpg'
	 */
	public function getUrlName() { 
		if ( ! $this->urlName ) {
			$this->urlName = $this->sessionKey . '.' . $this->getExtension();
		}
		return $this->urlName;
	}

	/**
	 * Return the URL of the file, if for some reason we wanted to download it
 	 * We tend not to do this for the original file, but we do want thumb icons
	 * @return {String} url
	 */
	public function getUrl() {
		if ( !isset( $this->url ) ) {
			$this->url = $this->sessionStash->getBaseUrl() . '/' . $this->getUrlName();
		}
		return $this->url;
	}

	/**
	 * Parent classes use this method, for no obvious reason, to return the path (relative to wiki root, I assume). 
	 * But with this class, the URL is unrelated to the path.
	 *
	 * @return {String} url
	 */
	public function getFullUrl() { 
		return $this->getUrl();
	}


	/**
	 * Getter for session key (the session-unique id by which this file's location & metadata is stored in the session)
	 * @return {String} session key
	 */
	public function getSessionKey() {
		return $this->sessionKey;
	}

	/**
	 * Typically, transform() returns a ThumbnailImage, which you can think of as being the exact
	 * equivalent of an HTML thumbnail on Wikipedia. So its URL is the full-size file, not the thumbnail's URL.
	 *
	 * Here we override transform() to stash the thumbnail file, and then 
	 * provide a way to get at the stashed thumbnail file to extract properties such as its URL
	 *
	 * @param {Array} $params: parameters suitable for File::transform()
	 * @param {Bitmask} $flags: flags suitable for File::transform()
	 * @return {ThumbnailImage} with additional File thumbnailFile property
	 */
	public function transform( $params, $flags = 0 ) { 

		// force it to get a thumbnail right away
		$flags |= self::RENDER_NOW;

		// returns a ThumbnailImage object containing the url and path. Note. NOT A FILE OBJECT.
		$thumb = parent::transform( $params, $flags );
		$key = $this->thumbName($params);

		// remove extension, so it's stored in the session under '120px-123456'
		// this makes it uniform with the other session key for the original, '123456'
		$n = strrpos( $key, '.' );	
		if ( $n !== false ) {
			$key = substr( $key, 0, $n );
		}

		// stash the thumbnail File, and provide our caller with a way to get at its properties
		$stashedThumbFile = $this->sessionStash->stashFile( $thumb->getPath(), array(), $key );
		$thumb->thumbnailFile = $stashedThumbFile;

		return $thumb;	

	}

	/**
	 * Remove the associated temporary file
	 * @return {Status} success
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

