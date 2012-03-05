<?php
/**
 * Base code for file repositories.
 *
 * @file
 * @ingroup FileRepo
 */

/**
 * Base class for file repositories.
 * Do not instantiate, use a derived class.
 *
 * @ingroup FileRepo
 */
abstract class FileRepo {
	const FILES_ONLY = 1;
	const DELETE_SOURCE = 1;
	const OVERWRITE = 2;
	const OVERWRITE_SAME = 4;

	var $thumbScriptUrl, $transformVia404;
	var $descBaseUrl, $scriptDirUrl, $scriptExtension, $articleUrl;
	var $fetchDescription, $initialCapital;
	var $pathDisclosureProtection = 'paranoid';
	var $descriptionCacheExpiry, $hashLevels, $url, $thumbUrl;

	/**
	 * Factory functions for creating new files
	 * Override these in the base class
	 */
	var $fileFactory = false, $oldFileFactory = false;
	var $fileFactoryKey = false, $oldFileFactoryKey = false;

	function __construct( $info ) {
		// Required settings
		$this->name = $info['name'];

		// Optional settings
		$this->initialCapital = MWNamespace::isCapitalized( NS_FILE );
		foreach ( array( 'descBaseUrl', 'scriptDirUrl', 'articleUrl', 'fetchDescription',
			'thumbScriptUrl', 'initialCapital', 'pathDisclosureProtection',
			'descriptionCacheExpiry', 'hashLevels', 'url', 'thumbUrl', 'scriptExtension' ) 
			as $var )
		{
			if ( isset( $info[$var] ) ) {
				$this->$var = $info[$var];
			}
		}
		$this->transformVia404 = !empty( $info['transformVia404'] );
	}

	/**
	 * Determine if a string is an mwrepo:// URL
	 */
	static function isVirtualUrl( $url ) {
		return substr( $url, 0, 9 ) == 'mwrepo://';
	}

	/**
	 * Create a new File object from the local repository
	 *
	 * @param $title Mixed: Title object or string
	 * @param $time Mixed: Time at which the image was uploaded.
	 *              If this is specified, the returned object will be an
	 *              instance of the repository's old file class instead of a
	 *              current file. Repositories not supporting version control
	 *              should return false if this parameter is set.
	 */
	function newFile( $title, $time = false ) {
		if ( !($title instanceof Title) ) {
			$title = Title::makeTitleSafe( NS_FILE, $title );
			if ( !is_object( $title ) ) {
				return null;
			}
		}
		if ( $time ) {
			if ( $this->oldFileFactory ) {
				return call_user_func( $this->oldFileFactory, $title, $this, $time );
			} else {
				return false;
			}
		} else {
			return call_user_func( $this->fileFactory, $title, $this );
		}
	}

	/**
	 * Find an instance of the named file created at the specified time
	 * Returns false if the file does not exist. Repositories not supporting
	 * version control should return false if the time is specified.
	 *
	 * @param $title Mixed: Title object or string
	 * @param $options Associative array of options:
	 *     time:           requested time for an archived image, or false for the
	 *                     current version. An image object will be returned which was
	 *                     created at the specified time.
	 *
	 *     ignoreRedirect: If true, do not follow file redirects
	 *
	 *     private:        If true, return restricted (deleted) files if the current
	 *                     user is allowed to view them. Otherwise, such files will not
	 *                     be found.
	 */
	function findFile( $title, $options = array() ) {
		if ( !is_array( $options ) ) {
			// MW 1.15 compat
			$time = $options;
		} else {
			$time = isset( $options['time'] ) ? $options['time'] : false;
		}
		if ( !($title instanceof Title) ) {
			$title = Title::makeTitleSafe( NS_FILE, $title );
			if ( !is_object( $title ) ) {
				return false;
			}
		}
		# First try the current version of the file to see if it precedes the timestamp
		$img = $this->newFile( $title );
		if ( !$img ) {
			return false;
		}
		if ( $img->exists() && ( !$time || $img->getTimestamp() == $time ) ) {
			return $img;
		}
		# Now try an old version of the file
		if ( $time !== false ) {
			$img = $this->newFile( $title, $time );
			if ( $img && $img->exists() ) {
				if ( !$img->isDeleted(File::DELETED_FILE) ) {
					return $img;
				} else if ( !empty( $options['private'] )  && $img->userCan(File::DELETED_FILE) ) {
					return $img;
				}
			}
		}

		# Now try redirects
		if ( !empty( $options['ignoreRedirect'] ) ) {
			return false;
		}
		$redir = $this->checkRedirect( $title );
		if( $redir && $redir->getNamespace() == NS_FILE) {
			$img = $this->newFile( $redir );
			if( !$img ) {
				return false;
			}
			if( $img->exists() ) {
				$img->redirectedFrom( $title->getDBkey() );
				return $img;
			}
		}
		return false;
	}

	/*
	 * Find many files at once.
	 * @param $items An array of titles, or an array of findFile() options with
	 *    the "title" option giving the title. Example:
	 *
	 *     $findItem = array( 'title' => $title, 'private' => true );
	 *     $findBatch = array( $findItem );
	 *     $repo->findFiles( $findBatch );
	 */
	function findFiles( $items ) {
		$result = array();
		foreach ( $items as $index => $item ) {
			if ( is_array( $item ) ) {
				$title = $item['title'];
				$options = $item;
				unset( $options['title'] );
			} else {
				$title = $item;
				$options = array();
			}
			$file = $this->findFile( $title, $options );
			if ( $file )
				$result[$file->getTitle()->getDBkey()] = $file;
		}
		return $result;
	}

	/**
	 * Create a new File object from the local repository
	 * @param $sha1 Mixed: SHA-1 key
	 * @param $time Mixed: time at which the image was uploaded.
	 *              If this is specified, the returned object will be an
	 *              of the repository's old file class instead of a current
	 *              file. Repositories not supporting version control should
	 *              return false if this parameter is set.
	 */
	function newFileFromKey( $sha1, $time = false ) {
		if ( $time ) {
			if ( $this->oldFileFactoryKey ) {
				return call_user_func( $this->oldFileFactoryKey, $sha1, $this, $time );
			} else {
				return false;
			}
		} else {
			return call_user_func( $this->fileFactoryKey, $sha1, $this );
		}
	}

	/**
	 * Find an instance of the file with this key, created at the specified time
	 * Returns false if the file does not exist. Repositories not supporting
	 * version control should return false if the time is specified.
	 *
	 * @param $sha1 String
	 * @param $options Option array, same as findFile().
	 */
	function findFileFromKey( $sha1, $options = array() ) {
		if ( !is_array( $options ) ) {
			# MW 1.15 compat
			$time = $options;
		} else {
			$time = isset( $options['time'] ) ? $options['time'] : false;
		}

		# First try the current version of the file to see if it precedes the timestamp
		$img = $this->newFileFromKey( $sha1 );
		if ( !$img ) {
			return false;
		}
		if ( $img->exists() && ( !$time || $img->getTimestamp() == $time ) ) {
			return $img;
		}
		# Now try an old version of the file
		if ( $time !== false ) {
			$img = $this->newFileFromKey( $sha1, $time );
			if ( $img->exists() ) {
				if ( !$img->isDeleted(File::DELETED_FILE) ) {
					return $img;
				} else if ( !empty( $options['private'] ) && $img->userCan(File::DELETED_FILE) ) {
					return $img;
				}
			}
		}
		return false;
	}

	/**
	 * Get the URL of thumb.php
	 */
	function getThumbScriptUrl() {
		return $this->thumbScriptUrl;
	}

	/**
	 * Get the URL corresponding to one of the four basic zones
	 * @param $zone String: one of: public, deleted, temp, thumb
	 * @return String or false
	 */
	function getZoneUrl( $zone ) {
		return false;
	}

	/**
	 * Returns true if the repository can transform files via a 404 handler
	 */
	function canTransformVia404() {
		return $this->transformVia404;
	}

	/**
	 * Get the name of an image from its title object
	 */
	function getNameFromTitle( $title ) {
		if ( $this->initialCapital != MWNamespace::isCapitalized( NS_FILE ) ) {
			global $wgContLang;
			$name = $title->getUserCaseDBKey();
			if ( $this->initialCapital ) {
				$name = $wgContLang->ucfirst( $name );
			}
		} else {
			$name = $title->getDBkey();
		}
		return $name;
	}

	static function getHashPathForLevel( $name, $levels ) {
		if ( $levels == 0 ) {
			return '';
		} else {
			$hash = md5( $name );
			$path = '';
			for ( $i = 1; $i <= $levels; $i++ ) {
				$path .= substr( $hash, 0, $i ) . '/';
			}
			return $path;
		}
	}

	/**
	 * Get a relative path including trailing slash, e.g. f/fa/
	 * If the repo is not hashed, returns an empty string
	 */
	function getHashPath( $name ) {
		return self::getHashPathForLevel( $name, $this->hashLevels );
	}

	/**
	 * Get the name of this repository, as specified by $info['name]' to the constructor
	 */
	function getName() {
		return $this->name;
	}
	
	/**
	 * Make an url to this repo
	 * 
	 * @param $query mixed Query string to append
	 * @param $entry string Entry point; defaults to index
	 * @return string
	 */
	function makeUrl( $query = '', $entry = 'index' ) {
		$ext = isset( $this->scriptExtension ) ? $this->scriptExtension : '.php';
		return wfAppendQuery( "{$this->scriptDirUrl}/{$entry}{$ext}", $query ); 
	} 

	/**
	 * Get the URL of an image description page. May return false if it is
	 * unknown or not applicable. In general this should only be called by the
	 * File class, since it may return invalid results for certain kinds of
	 * repositories. Use File::getDescriptionUrl() in user code.
	 *
	 * In particular, it uses the article paths as specified to the repository
	 * constructor, whereas local repositories use the local Title functions.
	 */
	function getDescriptionUrl( $name ) {
		$encName = wfUrlencode( $name );
		if ( !is_null( $this->descBaseUrl ) ) {
			# "http://example.com/wiki/Image:"
			return $this->descBaseUrl . $encName;
		}
		if ( !is_null( $this->articleUrl ) ) {
			# "http://example.com/wiki/$1"
			#
			# We use "Image:" as the canonical namespace for
			# compatibility across all MediaWiki versions.
			return str_replace( '$1',
				"Image:$encName", $this->articleUrl );
		}
		if ( !is_null( $this->scriptDirUrl ) ) {
			# "http://example.com/w"
			#
			# We use "Image:" as the canonical namespace for
			# compatibility across all MediaWiki versions,
			# and just sort of hope index.php is right. ;)
			return $this->makeUrl( "title=Image:$encName" );
		}
		return false;
	}

	/**
	 * Get the URL of the content-only fragment of the description page. For
	 * MediaWiki this means action=render. This should only be called by the
	 * repository's file class, since it may return invalid results. User code
	 * should use File::getDescriptionText().
	 * @param $name String: name of image to fetch
	 * @param $lang String: language to fetch it in, if any.
	 */
	function getDescriptionRenderUrl( $name, $lang = null ) {
		$query = 'action=render';
		if ( !is_null( $lang ) ) {
			$query .= '&uselang=' . $lang;
		}
		if ( isset( $this->scriptDirUrl ) ) {
			return $this->makeUrl( 
				'title=' .
				wfUrlencode( 'Image:' . $name ) .
				"&$query" );
		} else {
			$descUrl = $this->getDescriptionUrl( $name );
			if ( $descUrl ) {
				return wfAppendQuery( $descUrl, $query );
			} else {
				return false;
			}
		}
	}
	
	/**
	 * Get the URL of the stylesheet to apply to description pages
	 * @return string
	 */
	function getDescriptionStylesheetUrl() {
		if ( $this->scriptDirUrl ) {
			return $this->makeUrl( 'title=MediaWiki:Filepage.css&' .
				wfArrayToCGI( Skin::getDynamicStylesheetQuery() ) );
		}
	}

	/**
	 * Store a file to a given destination.
	 *
	 * @param $srcPath String: source path or virtual URL
	 * @param $dstZone String: destination zone
	 * @param $dstRel String: destination relative path
	 * @param $flags Integer: bitwise combination of the following flags:
	 *     self::DELETE_SOURCE     Delete the source file after upload
	 *     self::OVERWRITE         Overwrite an existing destination file instead of failing
	 *     self::OVERWRITE_SAME    Overwrite the file if the destination exists and has the
	 *                             same contents as the source
	 * @return FileRepoStatus
	 */
	function store( $srcPath, $dstZone, $dstRel, $flags = 0 ) {
		$status = $this->storeBatch( array( array( $srcPath, $dstZone, $dstRel ) ), $flags );
		if ( $status->successCount == 0 ) {
			$status->ok = false;
		}
		return $status;
	}

	/**
	 * Store a batch of files
	 *
	 * @param $triplets Array: (src,zone,dest) triplets as per store()
	 * @param $flags Integer: flags as per store
	 */
	abstract function storeBatch( $triplets, $flags = 0 );

	/**
	 * Pick a random name in the temp zone and store a file to it.
	 * Returns a FileRepoStatus object with the URL in the value.
	 *
	 * @param $originalName String: the base name of the file as specified
	 *     by the user. The file extension will be maintained.
	 * @param $srcPath String: the current location of the file.
	 */
	abstract function storeTemp( $originalName, $srcPath );


	/**
	 * Append the contents of the source path to the given file.
	 * @param $srcPath String: location of the source file
	 * @param $toAppendPath String: path to append to.
	 * @param $flags Integer: bitfield, may be FileRepo::DELETE_SOURCE to indicate
	 *        that the source file should be deleted if possible
	 * @return mixed Status or false
	 */
	abstract function append( $srcPath, $toAppendPath, $flags = 0 );

	/**
	 * Remove a temporary file or mark it for garbage collection
	 * @param $virtualUrl String: the virtual URL returned by storeTemp
	 * @return Boolean: true on success, false on failure
	 * STUB
	 */
	function freeTemp( $virtualUrl ) {
		return true;
	}

	/**
	 * Copy or move a file either from the local filesystem or from an mwrepo://
	 * virtual URL, into this repository at the specified destination location.
	 *
	 * Returns a FileRepoStatus object. On success, the value contains "new" or
	 * "archived", to indicate whether the file was new with that name.
	 *
	 * @param $srcPath String: the source path or URL
	 * @param $dstRel String: the destination relative path
	 * @param $archiveRel String: rhe relative path where the existing file is to
	 *        be archived, if there is one. Relative to the public zone root.
	 * @param $flags Integer: bitfield, may be FileRepo::DELETE_SOURCE to indicate
	 *        that the source file should be deleted if possible
	 */
	function publish( $srcPath, $dstRel, $archiveRel, $flags = 0 ) {
		$status = $this->publishBatch( array( array( $srcPath, $dstRel, $archiveRel ) ), $flags );
		if ( $status->successCount == 0 ) {
			$status->ok = false;
		}
		if ( isset( $status->value[0] ) ) {
			$status->value = $status->value[0];
		} else {
			$status->value = false;
		}
		return $status;
	}

	/**
	 * Publish a batch of files
	 * @param $triplets Array: (source,dest,archive) triplets as per publish()
	 * @param $flags Integer: bitfield, may be FileRepo::DELETE_SOURCE to indicate
	 *        that the source files should be deleted if possible
	 */
	abstract function publishBatch( $triplets, $flags = 0 );

	function fileExists( $file, $flags = 0 ) {
		$result = $this->fileExistsBatch( array( $file ), $flags );
		return $result[0];
	}

	/**
	 * Checks existence of an array of files.
	 *
	 * @param $files Array: URLs (or paths) of files to check
	 * @param $flags Integer: bitwise combination of the following flags:
	 *     self::FILES_ONLY     Mark file as existing only if it is a file (not directory)
	 * @return Either array of files and existence flags, or false
	 */
	abstract function fileExistsBatch( $files, $flags = 0 );

	/**
	 * Move a group of files to the deletion archive.
	 *
	 * If no valid deletion archive is configured, this may either delete the
	 * file or throw an exception, depending on the preference of the repository.
	 *
	 * The overwrite policy is determined by the repository -- currently FSRepo
	 * assumes a naming scheme in the deleted zone based on content hash, as
	 * opposed to the public zone which is assumed to be unique.
	 *
	 * @param $sourceDestPairs Array of source/destination pairs. Each element
	 *        is a two-element array containing the source file path relative to the
	 *        public root in the first element, and the archive file path relative
	 *        to the deleted zone root in the second element.
	 * @return FileRepoStatus
	 */
	abstract function deleteBatch( $sourceDestPairs );

	/**
	 * Move a file to the deletion archive.
	 * If no valid deletion archive exists, this may either delete the file
	 * or throw an exception, depending on the preference of the repository
	 * @param $srcRel Mixed: relative path for the file to be deleted
	 * @param $archiveRel Mixed: relative path for the archive location.
	 *        Relative to a private archive directory.
	 * @return FileRepoStatus object
	 */
	function delete( $srcRel, $archiveRel ) {
		return $this->deleteBatch( array( array( $srcRel, $archiveRel ) ) );
	}

	/**
	 * Get properties of a file with a given virtual URL
	 * The virtual URL must refer to this repo
	 * Properties should ultimately be obtained via File::getPropsFromPath()
	 */
	abstract function getFileProps( $virtualUrl );

	/**
	 * Call a callback function for every file in the repository
	 * May use either the database or the filesystem
	 * STUB
	 */
	function enumFiles( $callback ) {
		throw new MWException( 'enumFiles is not supported by ' . get_class( $this ) );
	}

	/**
	 * Determine if a relative path is valid, i.e. not blank or involving directory traveral
	 */
	function validateFilename( $filename ) {
		if ( strval( $filename ) == '' ) {
			return false;
		}
		if ( wfIsWindows() ) {
			$filename = strtr( $filename, '\\', '/' );
		}
		/**
		 * Use the same traversal protection as Title::secureAndSplit()
		 */
		if ( strpos( $filename, '.' ) !== false &&
		     ( $filename === '.' || $filename === '..' ||
		       strpos( $filename, './' ) === 0  ||
		       strpos( $filename, '../' ) === 0 ||
		       strpos( $filename, '/./' ) !== false ||
		       strpos( $filename, '/../' ) !== false ) )
		{
			return false;
		} else {
			return true;
		}
	}

	/**#@+
	 * Path disclosure protection functions
	 */
	function paranoidClean( $param ) { return '[hidden]'; }
	function passThrough( $param ) { return $param; }

	/**
	 * Get a callback function to use for cleaning error message parameters
	 */
	function getErrorCleanupFunction() {
		switch ( $this->pathDisclosureProtection ) {
			case 'none':
				$callback = array( $this, 'passThrough' );
				break;
			default: // 'paranoid'
				$callback = array( $this, 'paranoidClean' );
		}
		return $callback;
	}
	/**#@-*/

	/**
	 * Create a new fatal error
	 */
	function newFatal( $message /*, parameters...*/ ) {
		$params = func_get_args();
		array_unshift( $params, $this );
		return call_user_func_array( array( 'FileRepoStatus', 'newFatal' ), $params );
	}

	/**
	 * Create a new good result
	 */
	function newGood( $value = null ) {
		return FileRepoStatus::newGood( $this, $value );
	}

	/**
	 * Delete files in the deleted directory if they are not referenced in the filearchive table
	 * STUB
	 */
	function cleanupDeletedBatch( $storageKeys ) {}

	/**
	 * Checks if there is a redirect named as $title. If there is, return the
	 * title object. If not, return false.
	 * STUB
	 *
	 * @param $title Title of image
	 */
	function checkRedirect( $title ) {
		return false;
	}

	/**
	 * Invalidates image redirect cache related to that image
	 * Doesn't do anything for repositories that don't support image redirects.
	 *
	 * STUB
	 * @param $title Title of image
	 */
	function invalidateImageRedirect( $title ) {}

	/**
	 * Get an array or iterator of file objects for files that have a given
	 * SHA-1 content hash.
	 *
	 * STUB
	 */
	function findBySha1( $hash ) {
		return array();
	}

	/**
	 * Get the human-readable name of the repo.
	 * @return string
	 */
	public function getDisplayName() {
		// We don't name our own repo, return nothing
		if ( $this->isLocal() ) {
			return null;
		}
		// 'shared-repo-name-wikimediacommons' is used when $wgUseInstantCommons = true
		$repoName = wfMsg( 'shared-repo-name-' . $this->name );
		if ( !wfEmptyMsg( 'shared-repo-name-' . $this->name, $repoName ) ) {
			return $repoName;
		}
		return wfMsg( 'shared-repo' );
	}

	/**
	 * Returns true if this the local file repository.
	 *
	 * @return bool
	 */
	function isLocal() {
		return $this->getName() == 'local';
	}


	/**
	 * Get a key on the primary cache for this repository.
	 * Returns false if the repository's cache is not accessible at this site.
	 * The parameters are the parts of the key, as for wfMemcKey().
	 *
	 * STUB
	 */
	function getSharedCacheKey( /*...*/ ) {
		return false;
	}

	/**
	 * Get a key for this repo in the local cache domain. These cache keys are
	 * not shared with remote instances of the repo.
	 * The parameters are the parts of the key, as for wfMemcKey().
	 */
	function getLocalCacheKey( /*...*/ ) {
		$args = func_get_args();
		array_unshift( $args, 'filerepo', $this->getName() );
		return call_user_func_array( 'wfMemcKey', $args );
	}
}
