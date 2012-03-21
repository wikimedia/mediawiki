<?php
/**
 * @defgroup FileRepo File Repository
 *
 * @brief This module handles how MediaWiki interacts with filesystems.
 *
 * @details
 */

/**
 * Base code for file repositories.
 *
 * @file
 * @ingroup FileRepo
 */

/**
 * Base class for file repositories
 *
 * @ingroup FileRepo
 */
class FileRepo {
	const FILES_ONLY = 1;

	const DELETE_SOURCE = 1;
	const OVERWRITE = 2;
	const OVERWRITE_SAME = 4;
	const SKIP_LOCKING = 8;

	/** @var FileBackend */
	protected $backend;
	/** @var Array Map of zones to config */
	protected $zones = array();

	var $thumbScriptUrl, $transformVia404;
	var $descBaseUrl, $scriptDirUrl, $scriptExtension, $articleUrl;
	var $fetchDescription, $initialCapital;
	var $pathDisclosureProtection = 'simple'; // 'paranoid'
	var $descriptionCacheExpiry, $url, $thumbUrl;
	var $hashLevels, $deletedHashLevels;

	/**
	 * Factory functions for creating new files
	 * Override these in the base class
	 */
	var $fileFactory = array( 'UnregisteredLocalFile', 'newFromTitle' );
	var $oldFileFactory = false;
	var $fileFactoryKey = false, $oldFileFactoryKey = false;

	function __construct( Array $info = null ) {
		// Verify required settings presence
		if(
			$info === null
			|| !array_key_exists( 'name', $info )
			|| !array_key_exists( 'backend', $info )
		) {
			throw new MWException( __CLASS__ . " requires an array of options having both 'name' and 'backend' keys.\n" );
		}

		// Required settings
		$this->name = $info['name'];
		if ( $info['backend'] instanceof FileBackend ) {
			$this->backend = $info['backend']; // useful for testing
		} else {
			$this->backend = FileBackendGroup::singleton()->get( $info['backend'] );
		}

		// Optional settings that can have no value
		$optionalSettings = array(
			'descBaseUrl', 'scriptDirUrl', 'articleUrl', 'fetchDescription',
			'thumbScriptUrl', 'pathDisclosureProtection', 'descriptionCacheExpiry',
			'scriptExtension'
		);
		foreach ( $optionalSettings as $var ) {
			if ( isset( $info[$var] ) ) {
				$this->$var = $info[$var];
			}
		}

		// Optional settings that have a default
		$this->initialCapital = isset( $info['initialCapital'] )
			? $info['initialCapital']
			: MWNamespace::isCapitalized( NS_FILE );
		$this->url = isset( $info['url'] )
			? $info['url']
			: false; // a subclass may set the URL (e.g. ForeignAPIRepo)
		if ( isset( $info['thumbUrl'] ) ) {
			$this->thumbUrl = $info['thumbUrl'];
		} else {
			$this->thumbUrl = $this->url ? "{$this->url}/thumb" : false;
		}
		$this->hashLevels = isset( $info['hashLevels'] )
			? $info['hashLevels']
			: 2;
		$this->deletedHashLevels = isset( $info['deletedHashLevels'] )
			? $info['deletedHashLevels']
			: $this->hashLevels;
		$this->transformVia404 = !empty( $info['transformVia404'] );
		$this->zones = isset( $info['zones'] )
			? $info['zones']
			: array();
		// Give defaults for the basic zones...
		foreach ( array( 'public', 'thumb', 'temp', 'deleted' ) as $zone ) {
			if ( !isset( $this->zones[$zone] ) ) {
				$this->zones[$zone] = array(
					'container' => "{$this->name}-{$zone}",
					'directory' => '' // container root
				);
			}
		}
	}

	/**
	 * Get the file backend instance
	 *
	 * @return FileBackend
	 */
	public function getBackend() {
		return $this->backend;
	}

	/**
	 * Get an explanatory message if this repo is read-only
	 * 
	 * @return string|bool Returns false if the repo is not read-only
	 */
	public function getReadOnlyReason() {
		return $this->backend->getReadOnlyReason();
	}

	/**
	 * Prepare a single zone or list of zones for usage.
	 * See initDeletedDir() for additional setup needed for the 'deleted' zone.
	 * 
	 * @param $doZones Array Only do a particular zones
	 * @return Status
	 */
	protected function initZones( $doZones = array() ) {
		$status = $this->newGood();
		foreach ( (array)$doZones as $zone ) {
			$root = $this->getZonePath( $zone );
			if ( $root === null ) {
				throw new MWException( "No '$zone' zone defined in the {$this->name} repo." );
			}
		}
		return $status;
	}

	/**
	 * Take all available measures to prevent web accessibility of new deleted
	 * directories, in case the user has not configured offline storage
	 *
	 * @param $dir string
	 * @return void
	 */
	protected function initDeletedDir( $dir ) {
		$this->backend->secure( // prevent web access & dir listings
			array( 'dir' => $dir, 'noAccess' => true, 'noListing' => true ) );
	}

	/**
	 * Determine if a string is an mwrepo:// URL
	 *
	 * @param $url string
	 * @return bool
	 */
	public static function isVirtualUrl( $url ) {
		return substr( $url, 0, 9 ) == 'mwrepo://';
	}

	/**
	 * Get a URL referring to this repository, with the private mwrepo protocol.
	 * The suffix, if supplied, is considered to be unencoded, and will be
	 * URL-encoded before being returned.
	 *
	 * @param $suffix string
	 * @return string
	 */
	public function getVirtualUrl( $suffix = false ) {
		$path = 'mwrepo://' . $this->name;
		if ( $suffix !== false ) {
			$path .= '/' . rawurlencode( $suffix );
		}
		return $path;
	}

	/**
	 * Get the URL corresponding to one of the four basic zones
	 *
	 * @param $zone String: one of: public, deleted, temp, thumb
	 * @return String or false
	 */
	public function getZoneUrl( $zone ) {
		switch ( $zone ) {
			case 'public':
				return $this->url;
			case 'temp':
				return "{$this->url}/temp";
			case 'deleted':
				return false; // no public URL
			case 'thumb':
				return $this->thumbUrl;
			default:
				return false;
		}
	}

	/**
	 * Get the backend storage path corresponding to a virtual URL
	 *
	 * @param $url string
	 * @return string
	 */
	function resolveVirtualUrl( $url ) {
		if ( substr( $url, 0, 9 ) != 'mwrepo://' ) {
			throw new MWException( __METHOD__.': unknown protocol' );
		}
		$bits = explode( '/', substr( $url, 9 ), 3 );
		if ( count( $bits ) != 3 ) {
			throw new MWException( __METHOD__.": invalid mwrepo URL: $url" );
		}
		list( $repo, $zone, $rel ) = $bits;
		if ( $repo !== $this->name ) {
			throw new MWException( __METHOD__.": fetching from a foreign repo is not supported" );
		}
		$base = $this->getZonePath( $zone );
		if ( !$base ) {
			throw new MWException( __METHOD__.": invalid zone: $zone" );
		}
		return $base . '/' . rawurldecode( $rel );
	}

	/**
	 * The the storage container and base path of a zone
	 * 
	 * @param $zone string
	 * @return Array (container, base path) or (null, null)
	 */
	protected function getZoneLocation( $zone ) {
		if ( !isset( $this->zones[$zone] ) ) {
			return array( null, null ); // bogus
		}
		return array( $this->zones[$zone]['container'], $this->zones[$zone]['directory'] );
	}

	/**
	 * Get the storage path corresponding to one of the zones
	 *
	 * @param $zone string
	 * @return string|null
	 */
	public function getZonePath( $zone ) {
		list( $container, $base ) = $this->getZoneLocation( $zone );
		if ( $container === null || $base === null ) {
			return null;
		}
		$backendName = $this->backend->getName();
		if ( $base != '' ) { // may not be set
			$base = "/{$base}";
		}
		return "mwstore://$backendName/{$container}{$base}";
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
	 * @return File|null A File, or null if passed an invalid Title
	 */
	public function newFile( $title, $time = false ) {
		$title = File::normalizeTitle( $title );
		if ( !$title ) {
			return null;
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
	 * @param $options array Associative array of options:
	 *     time:           requested time for an archived image, or false for the
	 *                     current version. An image object will be returned which was
	 *                     created at the specified time.
	 *
	 *     ignoreRedirect: If true, do not follow file redirects
	 *
	 *     private:        If true, return restricted (deleted) files if the current
	 *                     user is allowed to view them. Otherwise, such files will not
	 *                     be found.
	 * @return File|bool False on failure
	 */
	public function findFile( $title, $options = array() ) {
		$title = File::normalizeTitle( $title );
		if ( !$title ) {
			return false;
		}
		$time = isset( $options['time'] ) ? $options['time'] : false;
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
				if ( !$img->isDeleted( File::DELETED_FILE ) ) {
					return $img; // always OK
				} elseif ( !empty( $options['private'] ) && $img->userCan( File::DELETED_FILE ) ) {
					return $img;
				}
			}
		}

		# Now try redirects
		if ( !empty( $options['ignoreRedirect'] ) ) {
			return false;
		}
		$redir = $this->checkRedirect( $title );
		if ( $redir && $title->getNamespace() == NS_FILE) {
			$img = $this->newFile( $redir );
			if ( !$img ) {
				return false;
			}
			if ( $img->exists() ) {
				$img->redirectedFrom( $title->getDBkey() );
				return $img;
			}
		}
		return false;
	}

	/**
	 * Find many files at once.
	 *
	 * @param $items array An array of titles, or an array of findFile() options with
	 *    the "title" option giving the title. Example:
	 *
	 *     $findItem = array( 'title' => $title, 'private' => true );
	 *     $findBatch = array( $findItem );
	 *     $repo->findFiles( $findBatch );
	 * @return array
	 */
	public function findFiles( $items ) {
		$result = array();
		foreach ( $items as $item ) {
			if ( is_array( $item ) ) {
				$title = $item['title'];
				$options = $item;
				unset( $options['title'] );
			} else {
				$title = $item;
				$options = array();
			}
			$file = $this->findFile( $title, $options );
			if ( $file ) {
				$result[$file->getTitle()->getDBkey()] = $file;
			}
		}
		return $result;
	}

	/**
	 * Find an instance of the file with this key, created at the specified time
	 * Returns false if the file does not exist. Repositories not supporting
	 * version control should return false if the time is specified.
	 *
	 * @param $sha1 String base 36 SHA-1 hash
	 * @param $options array Option array, same as findFile().
	 * @return File|bool False on failure
	 */
	public function findFileFromKey( $sha1, $options = array() ) {
		$time = isset( $options['time'] ) ? $options['time'] : false;

		# First try to find a matching current version of a file...
		if ( $this->fileFactoryKey ) {
			$img = call_user_func( $this->fileFactoryKey, $sha1, $this, $time );
		} else {
			return false; // find-by-sha1 not supported
		}
		if ( $img && $img->exists() ) {
			return $img;
		}
		# Now try to find a matching old version of a file...
		if ( $time !== false && $this->oldFileFactoryKey ) { // find-by-sha1 supported?
			$img = call_user_func( $this->oldFileFactoryKey, $sha1, $this, $time );
			if ( $img && $img->exists() ) {
				if ( !$img->isDeleted( File::DELETED_FILE ) ) {
					return $img; // always OK
				} elseif ( !empty( $options['private'] ) && $img->userCan( File::DELETED_FILE ) ) {
					return $img;
				}
			}
		}
		return false;
	}

	/**
	 * Get an array or iterator of file objects for files that have a given
	 * SHA-1 content hash.
	 *
	 * STUB
	 * @return array
	 */
	public function findBySha1( $hash ) {
		return array();
	}

	/**
	 * Get the public root URL of the repository
	 *
	 * @return string
	 */
	public function getRootUrl() {
		return $this->url;
	}

	/**
	 * Returns true if the repository uses a multi-level directory structure
	 *
	 * @return string
	 */
	public function isHashed() {
		return (bool)$this->hashLevels;
	}

	/**
	 * Get the URL of thumb.php
	 *
	 * @return string
	 */
	public function getThumbScriptUrl() {
		return $this->thumbScriptUrl;
	}

	/**
	 * Returns true if the repository can transform files via a 404 handler
	 *
	 * @return bool
	 */
	public function canTransformVia404() {
		return $this->transformVia404;
	}

	/**
	 * Get the name of an image from its title object
	 *
	 * @param $title Title
	 * @return String
	 */
	public function getNameFromTitle( Title $title ) {
		global $wgContLang;
		if ( $this->initialCapital != MWNamespace::isCapitalized( NS_FILE ) ) {
			$name = $title->getUserCaseDBKey();
			if ( $this->initialCapital ) {
				$name = $wgContLang->ucfirst( $name );
			}
		} else {
			$name = $title->getDBkey();
		}
		return $name;
	}

	/**
	 * Get the public zone root storage directory of the repository
	 *
	 * @return string
	 */
	public function getRootDirectory() {
		return $this->getZonePath( 'public' );
	}

	/**
	 * Get a relative path including trailing slash, e.g. f/fa/
	 * If the repo is not hashed, returns an empty string
	 *
	 * @param $name string
	 * @return string
	 */
	public function getHashPath( $name ) {
		return self::getHashPathForLevel( $name, $this->hashLevels );
	}

	/**
	 * @param $name
	 * @param $levels
	 * @return string
	 */
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
	 * Get the number of hash directory levels
	 *
	 * @return integer
	 */
	public function getHashLevels() {
		return $this->hashLevels;
	}

	/**
	 * Get the name of this repository, as specified by $info['name]' to the constructor
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Make an url to this repo
	 *
	 * @param $query mixed Query string to append
	 * @param $entry string Entry point; defaults to index
	 * @return string|bool False on failure
	 */
	public function makeUrl( $query = '', $entry = 'index' ) {
		if ( isset( $this->scriptDirUrl ) ) {
			$ext = isset( $this->scriptExtension ) ? $this->scriptExtension : '.php';
			return wfAppendQuery( "{$this->scriptDirUrl}/{$entry}{$ext}", $query );
		}
		return false;
	}

	/**
	 * Get the URL of an image description page. May return false if it is
	 * unknown or not applicable. In general this should only be called by the
	 * File class, since it may return invalid results for certain kinds of
	 * repositories. Use File::getDescriptionUrl() in user code.
	 *
	 * In particular, it uses the article paths as specified to the repository
	 * constructor, whereas local repositories use the local Title functions.
	 *
	 * @param $name string
	 * @return string
	 */
	public function getDescriptionUrl( $name ) {
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
	 *
	 * @param $name String: name of image to fetch
	 * @param $lang String: language to fetch it in, if any.
	 * @return string
	 */
	public function getDescriptionRenderUrl( $name, $lang = null ) {
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
	 *
	 * @return string|bool False on failure
	 */
	public function getDescriptionStylesheetUrl() {
		if ( isset( $this->scriptDirUrl ) ) {
			return $this->makeUrl( 'title=MediaWiki:Filepage.css&' .
				wfArrayToCGI( Skin::getDynamicStylesheetQuery() ) );
		}
		return false;
	}

	/**
	 * Store a file to a given destination.
	 *
	 * @param $srcPath String: source FS path, storage path, or virtual URL
	 * @param $dstZone String: destination zone
	 * @param $dstRel String: destination relative path
	 * @param $flags Integer: bitwise combination of the following flags:
	 *     self::DELETE_SOURCE     Delete the source file after upload
	 *     self::OVERWRITE         Overwrite an existing destination file instead of failing
	 *     self::OVERWRITE_SAME    Overwrite the file if the destination exists and has the
	 *                             same contents as the source
	 *     self::SKIP_LOCKING      Skip any file locking when doing the store
	 * @return FileRepoStatus
	 */
	public function store( $srcPath, $dstZone, $dstRel, $flags = 0 ) {
		$status = $this->storeBatch( array( array( $srcPath, $dstZone, $dstRel ) ), $flags );
		if ( $status->successCount == 0 ) {
			$status->ok = false;
		}
		return $status;
	}

	/**
	 * Store a batch of files
	 *
	 * @param $triplets Array: (src, dest zone, dest rel) triplets as per store()
	 * @param $flags Integer: bitwise combination of the following flags:
	 *     self::DELETE_SOURCE     Delete the source file after upload
	 *     self::OVERWRITE         Overwrite an existing destination file instead of failing
	 *     self::OVERWRITE_SAME    Overwrite the file if the destination exists and has the
	 *                             same contents as the source
	 *     self::SKIP_LOCKING      Skip any file locking when doing the store
	 * @return FileRepoStatus
	 */
	public function storeBatch( $triplets, $flags = 0 ) {
		$backend = $this->backend; // convenience

		$status = $this->newGood();

		$operations = array();
		$sourceFSFilesToDelete = array(); // cleanup for disk source files
		// Validate each triplet and get the store operation...
		foreach ( $triplets as $triplet ) {
			list( $srcPath, $dstZone, $dstRel ) = $triplet;
			wfDebug( __METHOD__
				. "( \$src='$srcPath', \$dstZone='$dstZone', \$dstRel='$dstRel' )\n"
			);

			// Resolve destination path
			$root = $this->getZonePath( $dstZone );
			if ( !$root ) {
				throw new MWException( "Invalid zone: $dstZone" );
			}
			if ( !$this->validateFilename( $dstRel ) ) {
				throw new MWException( 'Validation error in $dstRel' );
			}
			$dstPath = "$root/$dstRel";
			$dstDir  = dirname( $dstPath );
			// Create destination directories for this triplet
			if ( !$backend->prepare( array( 'dir' => $dstDir ) )->isOK() ) {
				return $this->newFatal( 'directorycreateerror', $dstDir );
			}

			if ( $dstZone == 'deleted' ) {
				$this->initDeletedDir( $dstDir );
			}

			// Resolve source to a storage path if virtual
			if ( self::isVirtualUrl( $srcPath ) ) {
				$srcPath = $this->resolveVirtualUrl( $srcPath );
			}

			// Get the appropriate file operation
			if ( FileBackend::isStoragePath( $srcPath ) ) {
				$opName = ( $flags & self::DELETE_SOURCE ) ? 'move' : 'copy';
			} else {
				$opName = 'store';
				if ( $flags & self::DELETE_SOURCE ) {
					$sourceFSFilesToDelete[] = $srcPath;
				}
			}
			$operations[] = array(
				'op'            => $opName,
				'src'           => $srcPath,
				'dst'           => $dstPath,
				'overwrite'     => $flags & self::OVERWRITE,
				'overwriteSame' => $flags & self::OVERWRITE_SAME,
			);
		}

		// Execute the store operation for each triplet
		$opts = array( 'force' => true );
		if ( $flags & self::SKIP_LOCKING ) {
			$opts['nonLocking'] = true;
		}
		$status->merge( $backend->doOperations( $operations, $opts ) );
		// Cleanup for disk source files...
		foreach ( $sourceFSFilesToDelete as $file ) {
			wfSuppressWarnings();
			unlink( $file ); // FS cleanup
			wfRestoreWarnings();
		}

		return $status;
	}

	/**
	 * Deletes a batch of files.
	 * Each file can be a (zone, rel) pair, virtual url, storage path, or FS path.
	 * It will try to delete each file, but ignores any errors that may occur.
	 *
	 * @param $pairs array List of files to delete
	 * @param $flags Integer: bitwise combination of the following flags:
	 *     self::SKIP_LOCKING      Skip any file locking when doing the deletions
	 * @return void
	 */
	public function cleanupBatch( $files, $flags = 0 ) {
		$operations = array();
		$sourceFSFilesToDelete = array(); // cleanup for disk source files
		foreach ( $files as $file ) {
			if ( is_array( $file ) ) {
				// This is a pair, extract it
				list( $zone, $rel ) = $file;
				$root = $this->getZonePath( $zone );
				$path = "$root/$rel";
			} else {
				if ( self::isVirtualUrl( $file ) ) {
					// This is a virtual url, resolve it
					$path = $this->resolveVirtualUrl( $file );
				} else {
					// This is a full file name
					$path = $file;
				}
			}
			// Get a file operation if needed
			if ( FileBackend::isStoragePath( $path ) ) {
				$operations[] = array(
					'op'           => 'delete',
					'src'          => $path,
				);
			} else {
				$sourceFSFilesToDelete[] = $path;
			}
		}
		// Actually delete files from storage...
		$opts = array( 'force' => true );
		if ( $flags & self::SKIP_LOCKING ) {
			$opts['nonLocking'] = true;
		}
		$this->backend->doOperations( $operations, $opts );
		// Cleanup for disk source files...
		foreach ( $sourceFSFilesToDelete as $file ) {
			wfSuppressWarnings();
			unlink( $file ); // FS cleanup
			wfRestoreWarnings();
		}
	}

	/**
	 * Pick a random name in the temp zone and store a file to it.
	 * Returns a FileRepoStatus object with the file Virtual URL in the value,
	 * file can later be disposed using FileRepo::freeTemp().
	 *
	 *
	 * @param $originalName String: the base name of the file as specified
	 *     by the user. The file extension will be maintained.
	 * @param $srcPath String: the current location of the file.
	 * @return FileRepoStatus object with the URL in the value.
	 */
	public function storeTemp( $originalName, $srcPath ) {
		$date      = gmdate( "YmdHis" );
		$hashPath  = $this->getHashPath( $originalName );
		$dstRel    = "{$hashPath}{$date}!{$originalName}";
		$dstUrlRel = $hashPath . $date . '!' . rawurlencode( $originalName );

		$result = $this->store( $srcPath, 'temp', $dstRel, self::SKIP_LOCKING );
		$result->value = $this->getVirtualUrl( 'temp' ) . '/' . $dstUrlRel;
		return $result;
	}

	/**
	 * Concatenate a list of files into a target file location. 
	 * 
	 * @param $srcPaths Array Ordered list of source virtual URLs/storage paths
	 * @param $dstPath String Target file system path
	 * @param $flags Integer: bitwise combination of the following flags:
	 *     self::DELETE_SOURCE     Delete the source files
	 * @return FileRepoStatus
	 */
	function concatenate( $srcPaths, $dstPath, $flags = 0 ) {
		$status = $this->newGood();

		$sources = array();
		$deleteOperations = array(); // post-concatenate ops
		foreach ( $srcPaths as $srcPath ) {
			// Resolve source to a storage path if virtual
			$source = $this->resolveToStoragePath( $srcPath );
			$sources[] = $source; // chunk to merge
			if ( $flags & self::DELETE_SOURCE ) {
				$deleteOperations[] = array( 'op' => 'delete', 'src' => $source );
			}
		}

		// Concatenate the chunks into one FS file
		$params = array( 'srcs' => $sources, 'dst' => $dstPath );
		$status->merge( $this->backend->concatenate( $params ) );
		if ( !$status->isOK() ) {
			return $status;
		}

		// Delete the sources if required
		if ( $deleteOperations ) {
			$opts = array( 'force' => true );
			$status->merge( $this->backend->doOperations( $deleteOperations, $opts ) );
		}

		// Make sure status is OK, despite any $deleteOperations fatals
		$status->setResult( true );

		return $status;
	}

	/**
	 * Remove a temporary file or mark it for garbage collection
	 *
	 * @param $virtualUrl String: the virtual URL returned by FileRepo::storeTemp()
	 * @return Boolean: true on success, false on failure
	 */
	public function freeTemp( $virtualUrl ) {
		$temp = "mwrepo://{$this->name}/temp";
		if ( substr( $virtualUrl, 0, strlen( $temp ) ) != $temp ) {
			wfDebug( __METHOD__.": Invalid temp virtual URL\n" );
			return false;
		}
		$path   = $this->resolveVirtualUrl( $virtualUrl );
		$op     = array( 'op' => 'delete', 'src' => $path );
		$status = $this->backend->doOperation( $op );
		return $status->isOK();
	}

	/**
	 * Copy or move a file either from a storage path, virtual URL,
	 * or FS path, into this repository at the specified destination location.
	 *
	 * Returns a FileRepoStatus object. On success, the value contains "new" or
	 * "archived", to indicate whether the file was new with that name.
	 *
	 * @param $srcPath String: the source FS path, storage path, or URL
	 * @param $dstRel String: the destination relative path
	 * @param $archiveRel String: the relative path where the existing file is to
	 *        be archived, if there is one. Relative to the public zone root.
	 * @param $flags Integer: bitfield, may be FileRepo::DELETE_SOURCE to indicate
	 *        that the source file should be deleted if possible
	 * @return FileRepoStatus
	 */
	public function publish( $srcPath, $dstRel, $archiveRel, $flags = 0 ) {
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
	 *
	 * @param $triplets Array: (source, dest, archive) triplets as per publish()
	 * @param $flags Integer: bitfield, may be FileRepo::DELETE_SOURCE to indicate
	 *        that the source files should be deleted if possible
	 * @return FileRepoStatus
	 */
	public function publishBatch( $triplets, $flags = 0 ) {
		$backend = $this->backend; // convenience

		// Try creating directories
		$status = $this->initZones( 'public' );
		if ( !$status->isOK() ) {
			return $status;
		}

		$status = $this->newGood( array() );

		$operations = array();
		$sourceFSFilesToDelete = array(); // cleanup for disk source files
		// Validate each triplet and get the store operation...
		foreach ( $triplets as $i => $triplet ) {
			list( $srcPath, $dstRel, $archiveRel ) = $triplet;
			// Resolve source to a storage path if virtual
			if ( substr( $srcPath, 0, 9 ) == 'mwrepo://' ) {
				$srcPath = $this->resolveVirtualUrl( $srcPath );
			}
			if ( !$this->validateFilename( $dstRel ) ) {
				throw new MWException( 'Validation error in $dstRel' );
			}
			if ( !$this->validateFilename( $archiveRel ) ) {
				throw new MWException( 'Validation error in $archiveRel' );
			}

			$publicRoot = $this->getZonePath( 'public' );
			$dstPath = "$publicRoot/$dstRel";
			$archivePath = "$publicRoot/$archiveRel";

			$dstDir = dirname( $dstPath );
			$archiveDir = dirname( $archivePath );
			// Abort immediately on directory creation errors since they're likely to be repetitive
			if ( !$backend->prepare( array( 'dir' => $dstDir ) )->isOK() ) {
				return $this->newFatal( 'directorycreateerror', $dstDir );
			}
			if ( !$backend->prepare( array( 'dir' => $archiveDir ) )->isOK() ) {
				return $this->newFatal( 'directorycreateerror', $archiveDir );
			}

			// Archive destination file if it exists
			if ( $backend->fileExists( array( 'src' => $dstPath ) ) ) {
				// Check if the archive file exists
				// This is a sanity check to avoid data loss. In UNIX, the rename primitive
				// unlinks the destination file if it exists. DB-based synchronisation in
				// publishBatch's caller should prevent races. In Windows there's no
				// problem because the rename primitive fails if the destination exists.
				if ( $backend->fileExists( array( 'src' => $archivePath ) ) ) {
					$operations[] = array( 'op' => 'null' );
					continue;
				} else {
					$operations[] = array(
						'op'           => 'move',
						'src'          => $dstPath,
						'dst'          => $archivePath
					);
				}
				$status->value[$i] = 'archived';
			} else {
				$status->value[$i] = 'new';
			}
			// Copy (or move) the source file to the destination
			if ( FileBackend::isStoragePath( $srcPath ) ) {
				if ( $flags & self::DELETE_SOURCE ) {
					$operations[] = array(
						'op'           => 'move',
						'src'          => $srcPath,
						'dst'          => $dstPath
					);
				} else {
					$operations[] = array(
						'op'           => 'copy',
						'src'          => $srcPath,
						'dst'          => $dstPath
					);
				}
			} else { // FS source path
				$operations[] = array(
					'op'           => 'store',
					'src'          => $srcPath,
					'dst'          => $dstPath
				);
				if ( $flags & self::DELETE_SOURCE ) {
					$sourceFSFilesToDelete[] = $srcPath;
				}
			}
		}

		// Execute the operations for each triplet
		$opts = array( 'force' => true );
		$status->merge( $backend->doOperations( $operations, $opts ) );
		// Cleanup for disk source files...
		foreach ( $sourceFSFilesToDelete as $file ) {
			wfSuppressWarnings();
			unlink( $file ); // FS cleanup
			wfRestoreWarnings();
		}

		return $status;
	}

	/**
	 * Checks existence of a a file
	 *
	 * @param $file string Virtual URL (or storage path) of file to check
	 * @param $flags Integer: bitwise combination of the following flags:
	 *     self::FILES_ONLY     Mark file as existing only if it is a file (not directory)
	 * @return bool
	 */
	public function fileExists( $file, $flags = 0 ) {
		$result = $this->fileExistsBatch( array( $file ), $flags );
		return $result[0];
	}

	/**
	 * Checks existence of an array of files.
	 *
	 * @param $files Array: Virtual URLs (or storage paths) of files to check
	 * @param $flags Integer: bitwise combination of the following flags:
	 *     self::FILES_ONLY     Mark file as existing only if it is a file (not directory)
	 * @return array|bool Either array of files and existence flags, or false
	 */
	public function fileExistsBatch( $files, $flags = 0 ) {
		$result = array();
		foreach ( $files as $key => $file ) {
			if ( self::isVirtualUrl( $file ) ) {
				$file = $this->resolveVirtualUrl( $file );
			}
			if ( FileBackend::isStoragePath( $file ) ) {
				$result[$key] = $this->backend->fileExists( array( 'src' => $file ) );
			} else {
				if ( $flags & self::FILES_ONLY ) {
					$result[$key] = is_file( $file ); // FS only
				} else {
					$result[$key] = file_exists( $file ); // FS only
				}
			}
		}

		return $result;
	}

	/**
	 * Move a file to the deletion archive.
	 * If no valid deletion archive exists, this may either delete the file
	 * or throw an exception, depending on the preference of the repository
	 *
	 * @param $srcRel Mixed: relative path for the file to be deleted
	 * @param $archiveRel Mixed: relative path for the archive location.
	 *        Relative to a private archive directory.
	 * @return FileRepoStatus object
	 */
	public function delete( $srcRel, $archiveRel ) {
		return $this->deleteBatch( array( array( $srcRel, $archiveRel ) ) );
	}

	/**
	 * Move a group of files to the deletion archive.
	 *
	 * If no valid deletion archive is configured, this may either delete the
	 * file or throw an exception, depending on the preference of the repository.
	 *
	 * The overwrite policy is determined by the repository -- currently LocalRepo
	 * assumes a naming scheme in the deleted zone based on content hash, as
	 * opposed to the public zone which is assumed to be unique.
	 *
	 * @param $sourceDestPairs Array of source/destination pairs. Each element
	 *        is a two-element array containing the source file path relative to the
	 *        public root in the first element, and the archive file path relative
	 *        to the deleted zone root in the second element.
	 * @return FileRepoStatus
	 */
	public function deleteBatch( $sourceDestPairs ) {
		$backend = $this->backend; // convenience

		// Try creating directories
		$status = $this->initZones( array( 'public', 'deleted' ) );
		if ( !$status->isOK() ) {
			return $status;
		}

		$status = $this->newGood();

		$operations = array();
		// Validate filenames and create archive directories
		foreach ( $sourceDestPairs as $pair ) {
			list( $srcRel, $archiveRel ) = $pair;
			if ( !$this->validateFilename( $srcRel ) ) {
				throw new MWException( __METHOD__.':Validation error in $srcRel' );
			}
			if ( !$this->validateFilename( $archiveRel ) ) {
				throw new MWException( __METHOD__.':Validation error in $archiveRel' );
			}

			$publicRoot = $this->getZonePath( 'public' );
			$srcPath = "{$publicRoot}/$srcRel";

			$deletedRoot = $this->getZonePath( 'deleted' );
			$archivePath = "{$deletedRoot}/{$archiveRel}";
			$archiveDir = dirname( $archivePath ); // does not touch FS

			// Create destination directories
			if ( !$backend->prepare( array( 'dir' => $archiveDir ) )->isOK() ) {
				return $this->newFatal( 'directorycreateerror', $archiveDir );
			}
			$this->initDeletedDir( $archiveDir );

			$operations[] = array(
				'op'            => 'move',
				'src'           => $srcPath,
				'dst'           => $archivePath,
				// We may have 2+ identical files being deleted,
				// all of which will map to the same destination file
				'overwriteSame' => true // also see bug 31792
			);
		}

		// Move the files by execute the operations for each pair.
		// We're now committed to returning an OK result, which will
		// lead to the files being moved in the DB also.
		$opts = array( 'force' => true );
		$status->merge( $backend->doOperations( $operations, $opts ) );

		return $status;
	}

	/**
	 * Get a relative path for a deletion archive key,
	 * e.g. s/z/a/ for sza251lrxrc1jad41h5mgilp8nysje52.jpg
	 *
	 * @return string
	 */
	public function getDeletedHashPath( $key ) {
		$path = '';
		for ( $i = 0; $i < $this->deletedHashLevels; $i++ ) {
			$path .= $key[$i] . '/';
		}
		return $path;
	}

	/**
	 * If a path is a virtual URL, resolve it to a storage path.
	 * Otherwise, just return the path as it is.
	 *
	 * @param $path string
	 * @return string
	 * @throws MWException
	 */
	protected function resolveToStoragePath( $path ) {
		if ( $this->isVirtualUrl( $path ) ) {
			return $this->resolveVirtualUrl( $path );
		}
		return $path;
	}

	/**
	 * Get a local FS copy of a file with a given virtual URL/storage path.
	 * Temporary files may be purged when the file object falls out of scope.
	 * 
	 * @param $virtualUrl string
	 * @return TempFSFile|null Returns null on failure
	 */
	public function getLocalCopy( $virtualUrl ) {
		$path = $this->resolveToStoragePath( $virtualUrl );
		return $this->backend->getLocalCopy( array( 'src' => $path ) );
	}

	/**
	 * Get a local FS file with a given virtual URL/storage path.
	 * The file is either an original or a copy. It should not be changed.
	 * Temporary files may be purged when the file object falls out of scope.
	 * 
	 * @param $virtualUrl string
	 * @return FSFile|null Returns null on failure.
	 */
	public function getLocalReference( $virtualUrl ) {
		$path = $this->resolveToStoragePath( $virtualUrl );
		return $this->backend->getLocalReference( array( 'src' => $path ) );
	}

	/**
	 * Get properties of a file with a given virtual URL/storage path.
	 * Properties should ultimately be obtained via FSFile::getProps().
	 *
	 * @param $virtualUrl string
	 * @return Array
	 */
	public function getFileProps( $virtualUrl ) {
		$path = $this->resolveToStoragePath( $virtualUrl );
		return $this->backend->getFileProps( array( 'src' => $path ) );
	}

	/**
	 * Get the timestamp of a file with a given virtual URL/storage path
	 *
	 * @param $virtualUrl string
	 * @return string|bool False on failure
	 */
	public function getFileTimestamp( $virtualUrl ) {
		$path = $this->resolveToStoragePath( $virtualUrl );
		return $this->backend->getFileTimestamp( array( 'src' => $path ) );
	}

	/**
	 * Get the sha1 of a file with a given virtual URL/storage path
	 *
	 * @param $virtualUrl string
	 * @return string|bool
	 */
	public function getFileSha1( $virtualUrl ) {
		$path = $this->resolveToStoragePath( $virtualUrl );
		$tmpFile = $this->backend->getLocalReference( array( 'src' => $path ) );
		if ( !$tmpFile ) {
			return false;
		}
		return $tmpFile->getSha1Base36();
	}

	/**
	 * Attempt to stream a file with the given virtual URL/storage path
	 *
	 * @param $virtualUrl string
	 * @param $headers Array Additional HTTP headers to send on success
	 * @return bool Success
	 */
	public function streamFile( $virtualUrl, $headers = array() ) {
		$path = $this->resolveToStoragePath( $virtualUrl );
		$params = array( 'src' => $path, 'headers' => $headers );
		return $this->backend->streamFile( $params )->isOK();
	}

	/**
	 * Call a callback function for every public regular file in the repository.
	 * This only acts on the current version of files, not any old versions.
	 * May use either the database or the filesystem.
	 *
	 * @param $callback Array|string
	 * @return void
	 */
	public function enumFiles( $callback ) {
		$this->enumFilesInStorage( $callback );
	}

	/**
	 * Call a callback function for every public file in the repository.
	 * May use either the database or the filesystem.
	 *
	 * @param $callback Array|string
	 * @return void
	 */
	protected function enumFilesInStorage( $callback ) {
		$publicRoot = $this->getZonePath( 'public' );
		$numDirs = 1 << ( $this->hashLevels * 4 );
		// Use a priori assumptions about directory structure
		// to reduce the tree height of the scanning process.
		for ( $flatIndex = 0; $flatIndex < $numDirs; $flatIndex++ ) {
			$hexString = sprintf( "%0{$this->hashLevels}x", $flatIndex );
			$path = $publicRoot;
			for ( $hexPos = 0; $hexPos < $this->hashLevels; $hexPos++ ) {
				$path .= '/' . substr( $hexString, 0, $hexPos + 1 );
			}
			$iterator = $this->backend->getFileList( array( 'dir' => $path ) );
			foreach ( $iterator as $name ) {
				// Each item returned is a public file
				call_user_func( $callback, "{$path}/{$name}" );
			}
		}
	}

	/**
	 * Determine if a relative path is valid, i.e. not blank or involving directory traveral
	 *
	 * @param $filename string
	 * @return bool
	 */
	public function validateFilename( $filename ) {
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

	/**
	 * Get a callback function to use for cleaning error message parameters
	 *
	 * @return Array
	 */
	function getErrorCleanupFunction() {
		switch ( $this->pathDisclosureProtection ) {
			case 'none':
				$callback = array( $this, 'passThrough' );
				break;
			case 'simple':
				$callback = array( $this, 'simpleClean' );
				break;
			default: // 'paranoid'
				$callback = array( $this, 'paranoidClean' );
		}
		return $callback;
	}

	/**
	 * Path disclosure protection function
	 *
	 * @param $param string
	 * @return string
	 */
	function paranoidClean( $param ) {
		return '[hidden]';
	}

	/**
	 * Path disclosure protection function
	 *
	 * @param $param string
	 * @return string
	 */
	function simpleClean( $param ) {
		global $IP;
		if ( !isset( $this->simpleCleanPairs ) ) {
			$this->simpleCleanPairs = array(
				$IP => '$IP', // sanity
			);
		}
		return strtr( $param, $this->simpleCleanPairs );
	}

	/**
	 * Path disclosure protection function
	 *
	 * @param $param string
	 * @return string
	 */
	function passThrough( $param ) {
		return $param;
	}

	/**
	 * Create a new fatal error
	 *
	 * @return FileRepoStatus
	 */
	function newFatal( $message /*, parameters...*/ ) {
		$params = func_get_args();
		array_unshift( $params, $this );
		return MWInit::callStaticMethod( 'FileRepoStatus', 'newFatal', $params );
	}

	/**
	 * Create a new good result
	 *
	 * @return FileRepoStatus
	 */
	function newGood( $value = null ) {
		return FileRepoStatus::newGood( $this, $value );
	}

	/**
	 * Delete files in the deleted directory if they are not referenced in the filearchive table
	 *
	 * STUB
	 */
	public function cleanupDeletedBatch( $storageKeys ) {}

	/**
	 * Checks if there is a redirect named as $title. If there is, return the
	 * title object. If not, return false.
	 * STUB
	 *
	 * @param $title Title of image
	 * @return Bool
	 */
	public function checkRedirect( Title $title ) {
		return false;
	}

	/**
	 * Invalidates image redirect cache related to that image
	 * Doesn't do anything for repositories that don't support image redirects.
	 *
	 * STUB
	 * @param $title Title of image
	 */
	public function invalidateImageRedirect( Title $title ) {}

	/**
	 * Get the human-readable name of the repo
	 *
	 * @return string
	 */
	public function getDisplayName() {
		// We don't name our own repo, return nothing
		if ( $this->isLocal() ) {
			return null;
		}
		// 'shared-repo-name-wikimediacommons' is used when $wgUseInstantCommons = true
		return wfMessageFallback( 'shared-repo-name-' . $this->name, 'shared-repo' )->text();
	}

	/**
	 * Returns true if this the local file repository.
	 *
	 * @return bool
	 */
	public function isLocal() {
		return $this->getName() == 'local';
	}

	/**
	 * Get a key on the primary cache for this repository.
	 * Returns false if the repository's cache is not accessible at this site.
	 * The parameters are the parts of the key, as for wfMemcKey().
	 *
	 * STUB
	 * @return bool
	 */
	function getSharedCacheKey( /*...*/ ) {
		return false;
	}

	/**
	 * Get a key for this repo in the local cache domain. These cache keys are
	 * not shared with remote instances of the repo.
	 * The parameters are the parts of the key, as for wfMemcKey().
	 *
	 * @return string
	 */
	function getLocalCacheKey( /*...*/ ) {
		$args = func_get_args();
		array_unshift( $args, 'filerepo', $this->getName() );
		return call_user_func_array( 'wfMemcKey', $args );
	}

	/**
	 * Get an UploadStash associated with this repo.
	 *
	 * @return UploadStash
	 */
	public function getUploadStash() {
		return new UploadStash( $this );
	}
}
