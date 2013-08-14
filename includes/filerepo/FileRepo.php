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
 * @ingroup FileRepo
 */

/**
 * Base class for file repositories
 *
 * @ingroup FileRepo
 */
class FileRepo {
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
	protected $abbrvThreshold;

	/**
	 * Factory functions for creating new files
	 * Override these in the base class
	 */
	var $fileFactory = array( 'UnregisteredLocalFile', 'newFromTitle' );
	var $oldFileFactory = false;
	var $fileFactoryKey = false, $oldFileFactoryKey = false;

	/**
	 * @param $info array|null
	 * @throws MWException
	 */
	public function __construct( array $info = null ) {
		// Verify required settings presence
		if (
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
		$this->abbrvThreshold = isset( $info['abbrvThreshold'] )
			? $info['abbrvThreshold']
			: 255;
		$this->isPrivate = !empty( $info['isPrivate'] );
		// Give defaults for the basic zones...
		$this->zones = isset( $info['zones'] ) ? $info['zones'] : array();
		foreach ( array( 'public', 'thumb', 'transcoded', 'temp', 'deleted' ) as $zone ) {
			if ( !isset( $this->zones[$zone]['container'] ) ) {
				$this->zones[$zone]['container'] = "{$this->name}-{$zone}";
			}
			if ( !isset( $this->zones[$zone]['directory'] ) ) {
				$this->zones[$zone]['directory'] = '';
			}
			if ( !isset( $this->zones[$zone]['urlsByExt'] ) ) {
				$this->zones[$zone]['urlsByExt'] = array();
			}
		}
	}

	/**
	 * Get the file backend instance. Use this function wisely.
	 *
	 * @return FileBackend
	 */
	public function getBackend() {
		return $this->backend;
	}

	/**
	 * Get an explanatory message if this repo is read-only.
	 * This checks if an administrator disabled writes to the backend.
	 *
	 * @return string|bool Returns false if the repo is not read-only
	 */
	public function getReadOnlyReason() {
		return $this->backend->getReadOnlyReason();
	}

	/**
	 * Check if a single zone or list of zones is defined for usage
	 *
	 * @param array $doZones Only do a particular zones
	 * @throws MWException
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
	 * @param $suffix string|bool
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
	 * @param string $zone One of: public, deleted, temp, thumb
	 * @param string|null $ext Optional file extension
	 * @return String or false
	 */
	public function getZoneUrl( $zone, $ext = null ) {
		if ( in_array( $zone, array( 'public', 'temp', 'thumb', 'transcoded' ) ) ) { // standard public zones
			if ( $ext !== null && isset( $this->zones[$zone]['urlsByExt'][$ext] ) ) {
				return $this->zones[$zone]['urlsByExt'][$ext]; // custom URL for extension/zone
			} elseif ( isset( $this->zones[$zone]['url'] ) ) {
				return $this->zones[$zone]['url']; // custom URL for zone
			}
		}
		switch ( $zone ) {
			case 'public':
				return $this->url;
			case 'temp':
				return "{$this->url}/temp";
			case 'deleted':
				return false; // no public URL
			case 'thumb':
				return $this->thumbUrl;
			case 'transcoded':
				return "{$this->url}/transcoded";
			default:
				return false;
		}
	}

	/**
	 * Get the thumb zone URL configured to be handled by scripts like thumb_handler.php.
	 * This is probably only useful for internal requests, such as from a fast frontend server
	 * to a slower backend server.
	 *
	 * Large sites may use a different host name for uploads than for wikis. In any case, the
	 * wiki configuration is needed in order to use thumb.php. To avoid extracting the wiki ID
	 * from the URL path, one can configure thumb_handler.php to recognize a special path on the
	 * same host name as the wiki that is used for viewing thumbnails.
	 *
	 * @param string $zone one of: public, deleted, temp, thumb
	 * @return String or false
	 */
	public function getZoneHandlerUrl( $zone ) {
		if ( isset( $this->zones[$zone]['handlerUrl'] )
			&& in_array( $zone, array( 'public', 'temp', 'thumb', 'transcoded' ) ) )
		{
			return $this->zones[$zone]['handlerUrl'];
		}
		return false;
	}

	/**
	 * Get the backend storage path corresponding to a virtual URL.
	 * Use this function wisely.
	 *
	 * @param $url string
	 * @throws MWException
	 * @return string
	 */
	public function resolveVirtualUrl( $url ) {
		if ( substr( $url, 0, 9 ) != 'mwrepo://' ) {
			throw new MWException( __METHOD__ . ': unknown protocol' );
		}
		$bits = explode( '/', substr( $url, 9 ), 3 );
		if ( count( $bits ) != 3 ) {
			throw new MWException( __METHOD__ . ": invalid mwrepo URL: $url" );
		}
		list( $repo, $zone, $rel ) = $bits;
		if ( $repo !== $this->name ) {
			throw new MWException( __METHOD__ . ": fetching from a foreign repo is not supported" );
		}
		$base = $this->getZonePath( $zone );
		if ( !$base ) {
			throw new MWException( __METHOD__ . ": invalid zone: $zone" );
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
	 * @return string|null Returns null if the zone is not defined
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
	 * @param array $options Associative array of options:
	 *     time:           requested time for a specific file version, or false for the
	 *                     current version. An image object will be returned which was
	 *                     created at the specified time (which may be archived or current).
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
		if ( $redir && $title->getNamespace() == NS_FILE ) {
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
	 * @param array $items An array of titles, or an array of findFile() options with
	 *    the "title" option giving the title. Example:
	 *
	 *     $findItem = array( 'title' => $title, 'private' => true );
	 *     $findBatch = array( $findItem );
	 *     $repo->findFiles( $findBatch );
	 * @return array
	 */
	public function findFiles( array $items ) {
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
	 * @param string $sha1 base 36 SHA-1 hash
	 * @param array $options Option array, same as findFile().
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
	 * @param $hash
	 * @return array
	 */
	public function findBySha1( $hash ) {
		return array();
	}

	/**
	 * Get an array of arrays or iterators of file objects for files that
	 * have the given SHA-1 content hashes.
	 *
	 * @param array $hashes An array of hashes
	 * @return array An Array of arrays or iterators of file objects and the hash as key
	 */
	public function findBySha1s( array $hashes ) {
		$result = array();
		foreach ( $hashes as $hash ) {
			$files = $this->findBySha1( $hash );
			if ( count( $files ) ) {
				$result[$hash] = $files;
			}
		}
		return $result;
	}

	/**
	 * Return an array of files where the name starts with $prefix.
	 *
	 * STUB
	 * @param string $prefix The prefix to search for
	 * @param int $limit The maximum amount of files to return
	 * @return array
	 */
	public function findFilesByPrefix( $prefix, $limit ) {
		return array();
	}

	/**
	 * Get the public root URL of the repository
	 *
	 * @deprecated since 1.20
	 * @return string
	 */
	public function getRootUrl() {
		return $this->getZoneUrl( 'public' );
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
	 * @param string $name Name of file
	 * @return string
	 */
	public function getHashPath( $name ) {
		return self::getHashPathForLevel( $name, $this->hashLevels );
	}

	/**
	 * Get a relative path including trailing slash, e.g. f/fa/
	 * If the repo is not hashed, returns an empty string
	 *
	 * @param string $suffix Basename of file from FileRepo::storeTemp()
	 * @return string
	 */
	public function getTempHashPath( $suffix ) {
		$parts = explode( '!', $suffix, 2 ); // format is <timestamp>!<name> or just <name>
		$name = isset( $parts[1] ) ? $parts[1] : $suffix; // hash path is not based on timestamp
		return self::getHashPathForLevel( $name, $this->hashLevels );
	}

	/**
	 * @param $name
	 * @param $levels
	 * @return string
	 */
	protected static function getHashPathForLevel( $name, $levels ) {
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
	 * @param string $entry Entry point; defaults to index
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
	 * @param string $name name of image to fetch
	 * @param string $lang language to fetch it in, if any.
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
				wfArrayToCgi( Skin::getDynamicStylesheetQuery() ) );
		}
		return false;
	}

	/**
	 * Store a file to a given destination.
	 *
	 * @param string $srcPath source file system path, storage path, or virtual URL
	 * @param string $dstZone destination zone
	 * @param string $dstRel destination relative path
	 * @param $flags Integer: bitwise combination of the following flags:
	 *     self::DELETE_SOURCE     Delete the source file after upload
	 *     self::OVERWRITE         Overwrite an existing destination file instead of failing
	 *     self::OVERWRITE_SAME    Overwrite the file if the destination exists and has the
	 *                             same contents as the source
	 *     self::SKIP_LOCKING      Skip any file locking when doing the store
	 * @return FileRepoStatus
	 */
	public function store( $srcPath, $dstZone, $dstRel, $flags = 0 ) {
		$this->assertWritableRepo(); // fail out if read-only

		$status = $this->storeBatch( array( array( $srcPath, $dstZone, $dstRel ) ), $flags );
		if ( $status->successCount == 0 ) {
			$status->ok = false;
		}

		return $status;
	}

	/**
	 * Store a batch of files
	 *
	 * @param array $triplets (src, dest zone, dest rel) triplets as per store()
	 * @param $flags Integer: bitwise combination of the following flags:
	 *     self::DELETE_SOURCE     Delete the source file after upload
	 *     self::OVERWRITE         Overwrite an existing destination file instead of failing
	 *     self::OVERWRITE_SAME    Overwrite the file if the destination exists and has the
	 *                             same contents as the source
	 *     self::SKIP_LOCKING      Skip any file locking when doing the store
	 * @throws MWException
	 * @return FileRepoStatus
	 */
	public function storeBatch( array $triplets, $flags = 0 ) {
		$this->assertWritableRepo(); // fail out if read-only

		$status = $this->newGood();
		$backend = $this->backend; // convenience

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
			$dstDir = dirname( $dstPath );
			// Create destination directories for this triplet
			if ( !$this->initDirectory( $dstDir )->isOK() ) {
				return $this->newFatal( 'directorycreateerror', $dstDir );
			}

			// Resolve source to a storage path if virtual
			$srcPath = $this->resolveToStoragePath( $srcPath );

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
				'op' => $opName,
				'src' => $srcPath,
				'dst' => $dstPath,
				'overwrite' => $flags & self::OVERWRITE,
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
	 * Each file can be a (zone, rel) pair, virtual url, storage path.
	 * It will try to delete each file, but ignores any errors that may occur.
	 *
	 * @param array $files List of files to delete
	 * @param $flags Integer: bitwise combination of the following flags:
	 *     self::SKIP_LOCKING      Skip any file locking when doing the deletions
	 * @return FileRepoStatus
	 */
	public function cleanupBatch( array $files, $flags = 0 ) {
		$this->assertWritableRepo(); // fail out if read-only

		$status = $this->newGood();

		$operations = array();
		foreach ( $files as $path ) {
			if ( is_array( $path ) ) {
				// This is a pair, extract it
				list( $zone, $rel ) = $path;
				$path = $this->getZonePath( $zone ) . "/$rel";
			} else {
				// Resolve source to a storage path if virtual
				$path = $this->resolveToStoragePath( $path );
			}
			$operations[] = array( 'op' => 'delete', 'src' => $path );
		}
		// Actually delete files from storage...
		$opts = array( 'force' => true );
		if ( $flags & self::SKIP_LOCKING ) {
			$opts['nonLocking'] = true;
		}
		$status->merge( $this->backend->doOperations( $operations, $opts ) );

		return $status;
	}

	/**
	 * Import a file from the local file system into the repo.
	 * This does no locking nor journaling and overrides existing files.
	 * This function can be used to write to otherwise read-only foreign repos.
	 * This is intended for copying generated thumbnails into the repo.
	 *
	 * @param string $src Source file system path, storage path, or virtual URL
	 * @param string $dst Virtual URL or storage path
	 * @param string|null $disposition Content-Disposition if given and supported
	 * @return FileRepoStatus
	 */
	final public function quickImport( $src, $dst, $disposition = null ) {
		return $this->quickImportBatch( array( array( $src, $dst, $disposition ) ) );
	}

	/**
	 * Purge a file from the repo. This does no locking nor journaling.
	 * This function can be used to write to otherwise read-only foreign repos.
	 * This is intended for purging thumbnails.
	 *
	 * @param string $path Virtual URL or storage path
	 * @return FileRepoStatus
	 */
	final public function quickPurge( $path ) {
		return $this->quickPurgeBatch( array( $path ) );
	}

	/**
	 * Deletes a directory if empty.
	 * This function can be used to write to otherwise read-only foreign repos.
	 *
	 * @param string $dir Virtual URL (or storage path) of directory to clean
	 * @return Status
	 */
	public function quickCleanDir( $dir ) {
		$status = $this->newGood();
		$status->merge( $this->backend->clean(
			array( 'dir' => $this->resolveToStoragePath( $dir ) ) ) );

		return $status;
	}

	/**
	 * Import a batch of files from the local file system into the repo.
	 * This does no locking nor journaling and overrides existing files.
	 * This function can be used to write to otherwise read-only foreign repos.
	 * This is intended for copying generated thumbnails into the repo.
	 *
	 * All path parameters may be a file system path, storage path, or virtual URL.
	 * When "dispositions" are given they are used as Content-Disposition if supported.
	 *
	 * @param array $triples List of (source path, destination path, disposition)
	 * @return FileRepoStatus
	 */
	public function quickImportBatch( array $triples ) {
		$status = $this->newGood();
		$operations = array();
		foreach ( $triples as $triple ) {
			list( $src, $dst ) = $triple;
			$src = $this->resolveToStoragePath( $src );
			$dst = $this->resolveToStoragePath( $dst );
			$operations[] = array(
				'op' => FileBackend::isStoragePath( $src ) ? 'copy' : 'store',
				'src' => $src,
				'dst' => $dst,
				'disposition' => isset( $triple[2] ) ? $triple[2] : null
			);
			$status->merge( $this->initDirectory( dirname( $dst ) ) );
		}
		$status->merge( $this->backend->doQuickOperations( $operations ) );

		return $status;
	}

	/**
	 * Purge a batch of files from the repo.
	 * This function can be used to write to otherwise read-only foreign repos.
	 * This does no locking nor journaling and is intended for purging thumbnails.
	 *
	 * @param array $paths List of virtual URLs or storage paths
	 * @return FileRepoStatus
	 */
	public function quickPurgeBatch( array $paths ) {
		$status = $this->newGood();
		$operations = array();
		foreach ( $paths as $path ) {
			$operations[] = array(
				'op' => 'delete',
				'src' => $this->resolveToStoragePath( $path ),
				'ignoreMissingSource' => true
			);
		}
		$status->merge( $this->backend->doQuickOperations( $operations ) );

		return $status;
	}

	/**
	 * Pick a random name in the temp zone and store a file to it.
	 * Returns a FileRepoStatus object with the file Virtual URL in the value,
	 * file can later be disposed using FileRepo::freeTemp().
	 *
	 * @param string $originalName the base name of the file as specified
	 *     by the user. The file extension will be maintained.
	 * @param string $srcPath the current location of the file.
	 * @return FileRepoStatus object with the URL in the value.
	 */
	public function storeTemp( $originalName, $srcPath ) {
		$this->assertWritableRepo(); // fail out if read-only

		$date = MWTimestamp::getInstance()->format( 'YmdHis' );
		$hashPath = $this->getHashPath( $originalName );
		$dstUrlRel = $hashPath . $date . '!' . rawurlencode( $originalName );
		$virtualUrl = $this->getVirtualUrl( 'temp' ) . '/' . $dstUrlRel;

		$result = $this->quickImport( $srcPath, $virtualUrl );
		$result->value = $virtualUrl;

		return $result;
	}

	/**
	 * Remove a temporary file or mark it for garbage collection
	 *
	 * @param string $virtualUrl the virtual URL returned by FileRepo::storeTemp()
	 * @return Boolean: true on success, false on failure
	 */
	public function freeTemp( $virtualUrl ) {
		$this->assertWritableRepo(); // fail out if read-only

		$temp = $this->getVirtualUrl( 'temp' );
		if ( substr( $virtualUrl, 0, strlen( $temp ) ) != $temp ) {
			wfDebug( __METHOD__ . ": Invalid temp virtual URL\n" );
			return false;
		}

		return $this->quickPurge( $virtualUrl )->isOK();
	}

	/**
	 * Concatenate a list of temporary files into a target file location.
	 *
	 * @param array $srcPaths Ordered list of source virtual URLs/storage paths
	 * @param string $dstPath Target file system path
	 * @param $flags Integer: bitwise combination of the following flags:
	 *     self::DELETE_SOURCE     Delete the source files
	 * @return FileRepoStatus
	 */
	public function concatenate( array $srcPaths, $dstPath, $flags = 0 ) {
		$this->assertWritableRepo(); // fail out if read-only

		$status = $this->newGood();

		$sources = array();
		foreach ( $srcPaths as $srcPath ) {
			// Resolve source to a storage path if virtual
			$source = $this->resolveToStoragePath( $srcPath );
			$sources[] = $source; // chunk to merge
		}

		// Concatenate the chunks into one FS file
		$params = array( 'srcs' => $sources, 'dst' => $dstPath );
		$status->merge( $this->backend->concatenate( $params ) );
		if ( !$status->isOK() ) {
			return $status;
		}

		// Delete the sources if required
		if ( $flags & self::DELETE_SOURCE ) {
			$status->merge( $this->quickPurgeBatch( $srcPaths ) );
		}

		// Make sure status is OK, despite any quickPurgeBatch() fatals
		$status->setResult( true );

		return $status;
	}

	/**
	 * Copy or move a file either from a storage path, virtual URL,
	 * or file system path, into this repository at the specified destination location.
	 *
	 * Returns a FileRepoStatus object. On success, the value contains "new" or
	 * "archived", to indicate whether the file was new with that name.
	 *
	 * Options to $options include:
	 *   - headers : name/value map of HTTP headers to use in response to GET/HEAD requests
	 *
	 * @param string $srcPath the source file system path, storage path, or URL
	 * @param string $dstRel the destination relative path
	 * @param string $archiveRel the relative path where the existing file is to
	 *        be archived, if there is one. Relative to the public zone root.
	 * @param $flags Integer: bitfield, may be FileRepo::DELETE_SOURCE to indicate
	 *        that the source file should be deleted if possible
	 * @param array $options Optional additional parameters
	 * @return FileRepoStatus
	 */
	public function publish(
		$srcPath, $dstRel, $archiveRel, $flags = 0, array $options = array()
	) {
		$this->assertWritableRepo(); // fail out if read-only

		$status = $this->publishBatch(
			array( array( $srcPath, $dstRel, $archiveRel, $options ) ), $flags );
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
	 * @param array $ntuples (source, dest, archive) triplets or
	 *        (source, dest, archive, options) 4-tuples as per publish().
	 * @param $flags Integer: bitfield, may be FileRepo::DELETE_SOURCE to indicate
	 *        that the source files should be deleted if possible
	 * @throws MWException
	 * @return FileRepoStatus
	 */
	public function publishBatch( array $ntuples, $flags = 0 ) {
		$this->assertWritableRepo(); // fail out if read-only

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
		foreach ( $ntuples as $ntuple ) {
			list( $srcPath, $dstRel, $archiveRel ) = $ntuple;
			$options = isset( $ntuple[3] ) ? $ntuple[3] : array();
			// Resolve source to a storage path if virtual
			$srcPath = $this->resolveToStoragePath( $srcPath );
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
			if ( !$this->initDirectory( $dstDir )->isOK() ) {
				return $this->newFatal( 'directorycreateerror', $dstDir );
			}
			if ( !$this->initDirectory( $archiveDir )->isOK() ) {
				return $this->newFatal( 'directorycreateerror', $archiveDir );
			}

			// Set any desired headers to be use in GET/HEAD responses
			$headers = isset( $options['headers'] ) ? $options['headers'] : array();

			// Archive destination file if it exists.
			// This will check if the archive file also exists and fail if does.
			// This is a sanity check to avoid data loss. On Windows and Linux,
			// copy() will overwrite, so the existence check is vulnerable to
			// race conditions unless an functioning LockManager is used.
			// LocalFile also uses SELECT FOR UPDATE for synchronization.
			$operations[] = array(
				'op' => 'copy',
				'src' => $dstPath,
				'dst' => $archivePath,
				'ignoreMissingSource' => true
			);

			// Copy (or move) the source file to the destination
			if ( FileBackend::isStoragePath( $srcPath ) ) {
				if ( $flags & self::DELETE_SOURCE ) {
					$operations[] = array(
						'op' => 'move',
						'src' => $srcPath,
						'dst' => $dstPath,
						'overwrite' => true, // replace current
						'headers' => $headers
					);
				} else {
					$operations[] = array(
						'op' => 'copy',
						'src' => $srcPath,
						'dst' => $dstPath,
						'overwrite' => true, // replace current
						'headers' => $headers
					);
				}
			} else { // FS source path
				$operations[] = array(
					'op' => 'store',
					'src' => $srcPath,
					'dst' => $dstPath,
					'overwrite' => true, // replace current
					'headers' => $headers
				);
				if ( $flags & self::DELETE_SOURCE ) {
					$sourceFSFilesToDelete[] = $srcPath;
				}
			}
		}

		// Execute the operations for each triplet
		$status->merge( $backend->doOperations( $operations ) );
		// Find out which files were archived...
		foreach ( $ntuples as $i => $ntuple ) {
			list( , , $archiveRel ) = $ntuple;
			$archivePath = $this->getZonePath( 'public' ) . "/$archiveRel";
			if ( $this->fileExists( $archivePath ) ) {
				$status->value[$i] = 'archived';
			} else {
				$status->value[$i] = 'new';
			}
		}
		// Cleanup for disk source files...
		foreach ( $sourceFSFilesToDelete as $file ) {
			wfSuppressWarnings();
			unlink( $file ); // FS cleanup
			wfRestoreWarnings();
		}

		return $status;
	}

	/**
	 * Creates a directory with the appropriate zone permissions.
	 * Callers are responsible for doing read-only and "writable repo" checks.
	 *
	 * @param string $dir Virtual URL (or storage path) of directory to clean
	 * @return Status
	 */
	protected function initDirectory( $dir ) {
		$path = $this->resolveToStoragePath( $dir );
		list( , $container, ) = FileBackend::splitStoragePath( $path );

		$params = array( 'dir' => $path );
		if ( $this->isPrivate || $container === $this->zones['deleted']['container'] ) {
			# Take all available measures to prevent web accessibility of new deleted
			# directories, in case the user has not configured offline storage
			$params = array( 'noAccess' => true, 'noListing' => true ) + $params;
		}

		return $this->backend->prepare( $params );
	}

	/**
	 * Deletes a directory if empty.
	 *
	 * @param string $dir Virtual URL (or storage path) of directory to clean
	 * @return Status
	 */
	public function cleanDir( $dir ) {
		$this->assertWritableRepo(); // fail out if read-only

		$status = $this->newGood();
		$status->merge( $this->backend->clean(
			array( 'dir' => $this->resolveToStoragePath( $dir ) ) ) );

		return $status;
	}

	/**
	 * Checks existence of a a file
	 *
	 * @param string $file Virtual URL (or storage path) of file to check
	 * @return bool
	 */
	public function fileExists( $file ) {
		$result = $this->fileExistsBatch( array( $file ) );
		return $result[0];
	}

	/**
	 * Checks existence of an array of files.
	 *
	 * @param array $files Virtual URLs (or storage paths) of files to check
	 * @return array|bool Either array of files and existence flags, or false
	 */
	public function fileExistsBatch( array $files ) {
		$result = array();
		foreach ( $files as $key => $file ) {
			$file = $this->resolveToStoragePath( $file );
			$result[$key] = $this->backend->fileExists( array( 'src' => $file ) );
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
		$this->assertWritableRepo(); // fail out if read-only

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
	 * @param array $sourceDestPairs of source/destination pairs. Each element
	 *        is a two-element array containing the source file path relative to the
	 *        public root in the first element, and the archive file path relative
	 *        to the deleted zone root in the second element.
	 * @throws MWException
	 * @return FileRepoStatus
	 */
	public function deleteBatch( array $sourceDestPairs ) {
		$this->assertWritableRepo(); // fail out if read-only

		// Try creating directories
		$status = $this->initZones( array( 'public', 'deleted' ) );
		if ( !$status->isOK() ) {
			return $status;
		}

		$status = $this->newGood();

		$backend = $this->backend; // convenience
		$operations = array();
		// Validate filenames and create archive directories
		foreach ( $sourceDestPairs as $pair ) {
			list( $srcRel, $archiveRel ) = $pair;
			if ( !$this->validateFilename( $srcRel ) ) {
				throw new MWException( __METHOD__ . ':Validation error in $srcRel' );
			} elseif ( !$this->validateFilename( $archiveRel ) ) {
				throw new MWException( __METHOD__ . ':Validation error in $archiveRel' );
			}

			$publicRoot = $this->getZonePath( 'public' );
			$srcPath = "{$publicRoot}/$srcRel";

			$deletedRoot = $this->getZonePath( 'deleted' );
			$archivePath = "{$deletedRoot}/{$archiveRel}";
			$archiveDir = dirname( $archivePath ); // does not touch FS

			// Create destination directories
			if ( !$this->initDirectory( $archiveDir )->isOK() ) {
				return $this->newFatal( 'directorycreateerror', $archiveDir );
			}

			$operations[] = array(
				'op' => 'move',
				'src' => $srcPath,
				'dst' => $archivePath,
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
	 * Delete files in the deleted directory if they are not referenced in the filearchive table
	 *
	 * STUB
	 */
	public function cleanupDeletedBatch( array $storageKeys ) {
		$this->assertWritableRepo();
	}

	/**
	 * Get a relative path for a deletion archive key,
	 * e.g. s/z/a/ for sza251lrxrc1jad41h5mgilp8nysje52.jpg
	 *
	 * @param $key string
	 * @throws MWException
	 * @return string
	 */
	public function getDeletedHashPath( $key ) {
		if ( strlen( $key ) < 31 ) {
			throw new MWException( "Invalid storage key '$key'." );
		}
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
	 * Get the size of a file with a given virtual URL/storage path
	 *
	 * @param $virtualUrl string
	 * @return integer|bool False on failure
	 */
	public function getFileSize( $virtualUrl ) {
		$path = $this->resolveToStoragePath( $virtualUrl );
		return $this->backend->getFileSize( array( 'src' => $path ) );
	}

	/**
	 * Get the sha1 (base 36) of a file with a given virtual URL/storage path
	 *
	 * @param $virtualUrl string
	 * @return string|bool
	 */
	public function getFileSha1( $virtualUrl ) {
		$path = $this->resolveToStoragePath( $virtualUrl );
		return $this->backend->getFileSha1Base36( array( 'src' => $path ) );
	}

	/**
	 * Attempt to stream a file with the given virtual URL/storage path
	 *
	 * @param $virtualUrl string
	 * @param array $headers Additional HTTP headers to send on success
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
		return FileBackend::isPathTraversalFree( $filename );
	}

	/**
	 * Get a callback function to use for cleaning error message parameters
	 *
	 * @return Array
	 */
	function getErrorCleanupFunction() {
		switch ( $this->pathDisclosureProtection ) {
			case 'none':
			case 'simple': // b/c
				$callback = array( $this, 'passThrough' );
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
	function passThrough( $param ) {
		return $param;
	}

	/**
	 * Create a new fatal error
	 *
	 * @return FileRepoStatus
	 */
	public function newFatal( $message /*, parameters...*/ ) {
		$params = func_get_args();
		array_unshift( $params, $this );
		return call_user_func_array( array( 'FileRepoStatus', 'newFatal' ), $params );
	}

	/**
	 * Create a new good result
	 *
	 * @param $value null|string
	 * @return FileRepoStatus
	 */
	public function newGood( $value = null ) {
		return FileRepoStatus::newGood( $this, $value );
	}

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
	 * Get the portion of the file that contains the origin file name.
	 * If that name is too long, then the name "thumbnail.<ext>" will be given.
	 *
	 * @param $name string
	 * @return string
	 */
	public function nameForThumb( $name ) {
		if ( strlen( $name ) > $this->abbrvThreshold ) {
			$ext = FileBackend::extensionFromPath( $name );
			$name = ( $ext == '' ) ? 'thumbnail' : "thumbnail.$ext";
		}
		return $name;
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
	public function getSharedCacheKey( /*...*/ ) {
		return false;
	}

	/**
	 * Get a key for this repo in the local cache domain. These cache keys are
	 * not shared with remote instances of the repo.
	 * The parameters are the parts of the key, as for wfMemcKey().
	 *
	 * @return string
	 */
	public function getLocalCacheKey( /*...*/ ) {
		$args = func_get_args();
		array_unshift( $args, 'filerepo', $this->getName() );
		return call_user_func_array( 'wfMemcKey', $args );
	}

	/**
	 * Get an temporary FileRepo associated with this repo.
	 * Files will be created in the temp zone of this repo and
	 * thumbnails in a /temp subdirectory in thumb zone of this repo.
	 * It will have the same backend as this repo.
	 *
	 * @return TempFileRepo
	 */
	public function getTempRepo() {
		return new TempFileRepo( array(
			'name' => "{$this->name}-temp",
			'backend' => $this->backend,
			'zones' => array(
				'public' => array(
					'container' => $this->zones['temp']['container'],
					'directory' => $this->zones['temp']['directory']
				),
				'thumb' => array(
					'container' => $this->zones['thumb']['container'],
					'directory' => ( $this->zones['thumb']['directory'] == '' )
						? 'temp'
						: $this->zones['thumb']['directory'] . '/temp'
				),
				'transcoded' => array(
					'container' => $this->zones['transcoded']['container'],
					'directory' => ( $this->zones['transcoded']['directory'] == '' )
						? 'temp'
						: $this->zones['transcoded']['directory'] . '/temp'
				)
			),
			'url' => $this->getZoneUrl( 'temp' ),
			'thumbUrl' => $this->getZoneUrl( 'thumb' ) . '/temp',
			'transcodedUrl' => $this->getZoneUrl( 'transcoded' ) . '/temp',
			'hashLevels' => $this->hashLevels // performance
		) );
	}

	/**
	 * Get an UploadStash associated with this repo.
	 *
	 * @param $user User
	 * @return UploadStash
	 */
	public function getUploadStash( User $user = null ) {
		return new UploadStash( $this, $user );
	}

	/**
	 * Throw an exception if this repo is read-only by design.
	 * This does not and should not check getReadOnlyReason().
	 *
	 * @return void
	 * @throws MWException
	 */
	protected function assertWritableRepo() {}


	/**
	 * Return information about the repository.
	 *
	 * @return array
	 * @since 1.22
	 */
	public function getInfo() {
		return array(
			'name' => $this->getName(),
			'displayname' => $this->getDisplayName(),
			'rootUrl' => $this->getRootUrl(),
			'local' => $this->isLocal(),
		);
	}
}

/**
 * FileRepo for temporary files created via FileRepo::getTempRepo()
 */
class TempFileRepo extends FileRepo {
	public function getTempRepo() {
		throw new MWException( "Cannot get a temp repo from a temp repo." );
	}
}
