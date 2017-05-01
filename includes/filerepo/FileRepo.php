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

	const NAME_AND_TIME_ONLY = 1;

	/** @var bool Whether to fetch commons image description pages and display
	 *    them on the local wiki */
	public $fetchDescription;

	/** @var int */
	public $descriptionCacheExpiry;

	/** @var bool */
	protected $hasSha1Storage = false;

	/** @var bool */
	protected $supportsSha1URLs = false;

	/** @var FileBackend */
	protected $backend;

	/** @var array Map of zones to config */
	protected $zones = [];

	/** @var string URL of thumb.php */
	protected $thumbScriptUrl;

	/** @var bool Whether to skip media file transformation on parse and rely
	 *    on a 404 handler instead. */
	protected $transformVia404;

	/** @var string URL of image description pages, e.g.
	 *    https://en.wikipedia.org/wiki/File:
	 */
	protected $descBaseUrl;

	/** @var string URL of the MediaWiki installation, equivalent to
	 *    $wgScriptPath, e.g. https://en.wikipedia.org/w
	 */
	protected $scriptDirUrl;

	/** @var string Script extension of the MediaWiki installation, equivalent
	 *    to the old $wgScriptExtension, e.g. .php5 defaults to .php */
	protected $scriptExtension;

	/** @var string Equivalent to $wgArticlePath, e.g. https://en.wikipedia.org/wiki/$1 */
	protected $articleUrl;

	/** @var bool Equivalent to $wgCapitalLinks (or $wgCapitalLinkOverrides[NS_FILE],
	 *    determines whether filenames implicitly start with a capital letter.
	 *    The current implementation may give incorrect description page links
	 *    when the local $wgCapitalLinks and initialCapital are mismatched.
	 */
	protected $initialCapital;

	/** @var string May be 'paranoid' to remove all parameters from error
	 *    messages, 'none' to leave the paths in unchanged, or 'simple' to
	 *    replace paths with placeholders. Default for LocalRepo is
	 *    'simple'.
	 */
	protected $pathDisclosureProtection = 'simple';

	/** @var bool Public zone URL. */
	protected $url;

	/** @var string The base thumbnail URL. Defaults to "<url>/thumb". */
	protected $thumbUrl;

	/** @var int The number of directory levels for hash-based division of files */
	protected $hashLevels;

	/** @var int The number of directory levels for hash-based division of deleted files */
	protected $deletedHashLevels;

	/** @var int File names over this size will use the short form of thumbnail
	 *    names. Short thumbnail names only have the width, parameters, and the
	 *    extension.
	 */
	protected $abbrvThreshold;

	/** @var string The URL of the repo's favicon, if any */
	protected $favicon;

	/** @var bool Whether all zones should be private (e.g. private wiki repo) */
	protected $isPrivate;

	/** @var array callable Override these in the base class */
	protected $fileFactory = [ 'UnregisteredLocalFile', 'newFromTitle' ];
	/** @var array callable|bool Override these in the base class */
	protected $oldFileFactory = false;
	/** @var array callable|bool Override these in the base class */
	protected $fileFactoryKey = false;
	/** @var array callable|bool Override these in the base class */
	protected $oldFileFactoryKey = false;

	/**
	 * @param array|null $info
	 * @throws MWException
	 */
	public function __construct( array $info = null ) {
		// Verify required settings presence
		if (
			$info === null
			|| !array_key_exists( 'name', $info )
			|| !array_key_exists( 'backend', $info )
		) {
			throw new MWException( __CLASS__ .
				" requires an array of options having both 'name' and 'backend' keys.\n" );
		}

		// Required settings
		$this->name = $info['name'];
		if ( $info['backend'] instanceof FileBackend ) {
			$this->backend = $info['backend']; // useful for testing
		} else {
			$this->backend = FileBackendGroup::singleton()->get( $info['backend'] );
		}

		// Optional settings that can have no value
		$optionalSettings = [
			'descBaseUrl', 'scriptDirUrl', 'articleUrl', 'fetchDescription',
			'thumbScriptUrl', 'pathDisclosureProtection', 'descriptionCacheExpiry',
			'scriptExtension', 'favicon'
		];
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
		$this->zones = isset( $info['zones'] ) ? $info['zones'] : [];
		foreach ( [ 'public', 'thumb', 'transcoded', 'temp', 'deleted' ] as $zone ) {
			if ( !isset( $this->zones[$zone]['container'] ) ) {
				$this->zones[$zone]['container'] = "{$this->name}-{$zone}";
			}
			if ( !isset( $this->zones[$zone]['directory'] ) ) {
				$this->zones[$zone]['directory'] = '';
			}
			if ( !isset( $this->zones[$zone]['urlsByExt'] ) ) {
				$this->zones[$zone]['urlsByExt'] = [];
			}
		}

		$this->supportsSha1URLs = !empty( $info['supportsSha1URLs'] );
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
	protected function initZones( $doZones = [] ) {
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
	 * @param string $url
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
	 * @param string|bool $suffix
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
	 * @return string|bool
	 */
	public function getZoneUrl( $zone, $ext = null ) {
		if ( in_array( $zone, [ 'public', 'thumb', 'transcoded' ] ) ) {
			// standard public zones
			if ( $ext !== null && isset( $this->zones[$zone]['urlsByExt'][$ext] ) ) {
				// custom URL for extension/zone
				return $this->zones[$zone]['urlsByExt'][$ext];
			} elseif ( isset( $this->zones[$zone]['url'] ) ) {
				// custom URL for zone
				return $this->zones[$zone]['url'];
			}
		}
		switch ( $zone ) {
			case 'public':
				return $this->url;
			case 'temp':
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
	 * @return bool Whether non-ASCII path characters are allowed
	 */
	public function backendSupportsUnicodePaths() {
		return ( $this->getBackend()->getFeatures() & FileBackend::ATTR_UNICODE_PATHS );
	}

	/**
	 * Get the backend storage path corresponding to a virtual URL.
	 * Use this function wisely.
	 *
	 * @param string $url
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
	 * @param string $zone
	 * @return array (container, base path) or (null, null)
	 */
	protected function getZoneLocation( $zone ) {
		if ( !isset( $this->zones[$zone] ) ) {
			return [ null, null ]; // bogus
		}

		return [ $this->zones[$zone]['container'], $this->zones[$zone]['directory'] ];
	}

	/**
	 * Get the storage path corresponding to one of the zones
	 *
	 * @param string $zone
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
	 * @param Title|string $title Title object or string
	 * @param bool|string $time Time at which the image was uploaded. If this
	 *   is specified, the returned object will be an instance of the
	 *   repository's old file class instead of a current file. Repositories
	 *   not supporting version control should return false if this parameter
	 *   is set.
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
				return null;
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
	 * @param Title|string $title Title object or string
	 * @param array $options Associative array of options:
	 *   time:           requested time for a specific file version, or false for the
	 *                   current version. An image object will be returned which was
	 *                   created at the specified time (which may be archived or current).
	 *   ignoreRedirect: If true, do not follow file redirects
	 *   private:        If true, return restricted (deleted) files if the current
	 *                   user is allowed to view them. Otherwise, such files will not
	 *                   be found. If a User object, use that user instead of the current.
	 *   latest:         If true, load from the latest available data into File objects
	 * @return File|bool False on failure
	 */
	public function findFile( $title, $options = [] ) {
		$title = File::normalizeTitle( $title );
		if ( !$title ) {
			return false;
		}
		if ( isset( $options['bypassCache'] ) ) {
			$options['latest'] = $options['bypassCache']; // b/c
		}
		$time = isset( $options['time'] ) ? $options['time'] : false;
		$flags = !empty( $options['latest'] ) ? File::READ_LATEST : 0;
		# First try the current version of the file to see if it precedes the timestamp
		$img = $this->newFile( $title );
		if ( !$img ) {
			return false;
		}
		$img->load( $flags );
		if ( $img->exists() && ( !$time || $img->getTimestamp() == $time ) ) {
			return $img;
		}
		# Now try an old version of the file
		if ( $time !== false ) {
			$img = $this->newFile( $title, $time );
			if ( $img ) {
				$img->load( $flags );
				if ( $img->exists() ) {
					if ( !$img->isDeleted( File::DELETED_FILE ) ) {
						return $img; // always OK
					} elseif ( !empty( $options['private'] ) &&
						$img->userCan( File::DELETED_FILE,
							$options['private'] instanceof User ? $options['private'] : null
						)
					) {
						return $img;
					}
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
			$img->load( $flags );
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
	 *     $findItem = [ 'title' => $title, 'private' => true ];
	 *     $findBatch = [ $findItem ];
	 *     $repo->findFiles( $findBatch );
	 *
	 *    No title should appear in $items twice, as the result use titles as keys
	 * @param int $flags Supports:
	 *     - FileRepo::NAME_AND_TIME_ONLY : return a (search title => (title,timestamp)) map.
	 *       The search title uses the input titles; the other is the final post-redirect title.
	 *       All titles are returned as string DB keys and the inner array is associative.
	 * @return array Map of (file name => File objects) for matches
	 */
	public function findFiles( array $items, $flags = 0 ) {
		$result = [];
		foreach ( $items as $item ) {
			if ( is_array( $item ) ) {
				$title = $item['title'];
				$options = $item;
				unset( $options['title'] );
			} else {
				$title = $item;
				$options = [];
			}
			$file = $this->findFile( $title, $options );
			if ( $file ) {
				$searchName = File::normalizeTitle( $title )->getDBkey(); // must be valid
				if ( $flags & self::NAME_AND_TIME_ONLY ) {
					$result[$searchName] = [
						'title' => $file->getTitle()->getDBkey(),
						'timestamp' => $file->getTimestamp()
					];
				} else {
					$result[$searchName] = $file;
				}
			}
		}

		return $result;
	}

	/**
	 * Find an instance of the file with this key, created at the specified time
	 * Returns false if the file does not exist. Repositories not supporting
	 * version control should return false if the time is specified.
	 *
	 * @param string $sha1 Base 36 SHA-1 hash
	 * @param array $options Option array, same as findFile().
	 * @return File|bool False on failure
	 */
	public function findFileFromKey( $sha1, $options = [] ) {
		$time = isset( $options['time'] ) ? $options['time'] : false;
		# First try to find a matching current version of a file...
		if ( !$this->fileFactoryKey ) {
			return false; // find-by-sha1 not supported
		}
		$img = call_user_func( $this->fileFactoryKey, $sha1, $this, $time );
		if ( $img && $img->exists() ) {
			return $img;
		}
		# Now try to find a matching old version of a file...
		if ( $time !== false && $this->oldFileFactoryKey ) { // find-by-sha1 supported?
			$img = call_user_func( $this->oldFileFactoryKey, $sha1, $this, $time );
			if ( $img && $img->exists() ) {
				if ( !$img->isDeleted( File::DELETED_FILE ) ) {
					return $img; // always OK
				} elseif ( !empty( $options['private'] ) &&
					$img->userCan( File::DELETED_FILE,
						$options['private'] instanceof User ? $options['private'] : null
					)
				) {
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
	 * @param string $hash SHA-1 hash
	 * @return File[]
	 */
	public function findBySha1( $hash ) {
		return [];
	}

	/**
	 * Get an array of arrays or iterators of file objects for files that
	 * have the given SHA-1 content hashes.
	 *
	 * @param array $hashes An array of hashes
	 * @return array An Array of arrays or iterators of file objects and the hash as key
	 */
	public function findBySha1s( array $hashes ) {
		$result = [];
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
		return [];
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
	 * Get the name of a file from its title object
	 *
	 * @param Title $title
	 * @return string
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
	 * @param string $name
	 * @param int $levels
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
	 * @return int
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
	 * @param string $query Query string to append
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
	 * @param string $name
	 * @return string
	 */
	public function getDescriptionUrl( $name ) {
		$encName = wfUrlencode( $name );
		if ( !is_null( $this->descBaseUrl ) ) {
			# "http://example.com/wiki/File:"
			return $this->descBaseUrl . $encName;
		}
		if ( !is_null( $this->articleUrl ) ) {
			# "http://example.com/wiki/$1"
			# We use "Image:" as the canonical namespace for
			# compatibility across all MediaWiki versions.
			return str_replace( '$1',
				"Image:$encName", $this->articleUrl );
		}
		if ( !is_null( $this->scriptDirUrl ) ) {
			# "http://example.com/w"
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
	 * @param string $name Name of image to fetch
	 * @param string $lang Language to fetch it in, if any.
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
	 * @param string $srcPath Source file system path, storage path, or virtual URL
	 * @param string $dstZone Destination zone
	 * @param string $dstRel Destination relative path
	 * @param int $flags Bitwise combination of the following flags:
	 *   self::OVERWRITE         Overwrite an existing destination file instead of failing
	 *   self::OVERWRITE_SAME    Overwrite the file if the destination exists and has the
	 *                           same contents as the source
	 *   self::SKIP_LOCKING      Skip any file locking when doing the store
	 * @return Status
	 */
	public function store( $srcPath, $dstZone, $dstRel, $flags = 0 ) {
		$this->assertWritableRepo(); // fail out if read-only

		$status = $this->storeBatch( [ [ $srcPath, $dstZone, $dstRel ] ], $flags );
		if ( $status->successCount == 0 ) {
			$status->setOK( false );
		}

		return $status;
	}

	/**
	 * Store a batch of files
	 *
	 * @param array $triplets (src, dest zone, dest rel) triplets as per store()
	 * @param int $flags Bitwise combination of the following flags:
	 *   self::OVERWRITE         Overwrite an existing destination file instead of failing
	 *   self::OVERWRITE_SAME    Overwrite the file if the destination exists and has the
	 *                           same contents as the source
	 *   self::SKIP_LOCKING      Skip any file locking when doing the store
	 * @throws MWException
	 * @return Status
	 */
	public function storeBatch( array $triplets, $flags = 0 ) {
		$this->assertWritableRepo(); // fail out if read-only

		if ( $flags & self::DELETE_SOURCE ) {
			throw new InvalidArgumentException( "DELETE_SOURCE not supported in " . __METHOD__ );
		}

		$status = $this->newGood();
		$backend = $this->backend; // convenience

		$operations = [];
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
				$opName = 'copy';
			} else {
				$opName = 'store';
			}
			$operations[] = [
				'op' => $opName,
				'src' => $srcPath,
				'dst' => $dstPath,
				'overwrite' => $flags & self::OVERWRITE,
				'overwriteSame' => $flags & self::OVERWRITE_SAME,
			];
		}

		// Execute the store operation for each triplet
		$opts = [ 'force' => true ];
		if ( $flags & self::SKIP_LOCKING ) {
			$opts['nonLocking'] = true;
		}
		$status->merge( $backend->doOperations( $operations, $opts ) );

		return $status;
	}

	/**
	 * Deletes a batch of files.
	 * Each file can be a (zone, rel) pair, virtual url, storage path.
	 * It will try to delete each file, but ignores any errors that may occur.
	 *
	 * @param array $files List of files to delete
	 * @param int $flags Bitwise combination of the following flags:
	 *   self::SKIP_LOCKING      Skip any file locking when doing the deletions
	 * @return Status
	 */
	public function cleanupBatch( array $files, $flags = 0 ) {
		$this->assertWritableRepo(); // fail out if read-only

		$status = $this->newGood();

		$operations = [];
		foreach ( $files as $path ) {
			if ( is_array( $path ) ) {
				// This is a pair, extract it
				list( $zone, $rel ) = $path;
				$path = $this->getZonePath( $zone ) . "/$rel";
			} else {
				// Resolve source to a storage path if virtual
				$path = $this->resolveToStoragePath( $path );
			}
			$operations[] = [ 'op' => 'delete', 'src' => $path ];
		}
		// Actually delete files from storage...
		$opts = [ 'force' => true ];
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
	 * @param string|FSFile $src Source file system path, storage path, or virtual URL
	 * @param string $dst Virtual URL or storage path
	 * @param array|string|null $options An array consisting of a key named headers
	 *   listing extra headers. If a string, taken as content-disposition header.
	 *   (Support for array of options new in 1.23)
	 * @return Status
	 */
	final public function quickImport( $src, $dst, $options = null ) {
		return $this->quickImportBatch( [ [ $src, $dst, $options ] ] );
	}

	/**
	 * Purge a file from the repo. This does no locking nor journaling.
	 * This function can be used to write to otherwise read-only foreign repos.
	 * This is intended for purging thumbnails.
	 *
	 * @param string $path Virtual URL or storage path
	 * @return Status
	 */
	final public function quickPurge( $path ) {
		return $this->quickPurgeBatch( [ $path ] );
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
			[ 'dir' => $this->resolveToStoragePath( $dir ) ] ) );

		return $status;
	}

	/**
	 * Import a batch of files from the local file system into the repo.
	 * This does no locking nor journaling and overrides existing files.
	 * This function can be used to write to otherwise read-only foreign repos.
	 * This is intended for copying generated thumbnails into the repo.
	 *
	 * All path parameters may be a file system path, storage path, or virtual URL.
	 * When "headers" are given they are used as HTTP headers if supported.
	 *
	 * @param array $triples List of (source path or FSFile, destination path, disposition)
	 * @return Status
	 */
	public function quickImportBatch( array $triples ) {
		$status = $this->newGood();
		$operations = [];
		foreach ( $triples as $triple ) {
			list( $src, $dst ) = $triple;
			if ( $src instanceof FSFile ) {
				$op = 'store';
			} else {
				$src = $this->resolveToStoragePath( $src );
				$op = FileBackend::isStoragePath( $src ) ? 'copy' : 'store';
			}
			$dst = $this->resolveToStoragePath( $dst );

			if ( !isset( $triple[2] ) ) {
				$headers = [];
			} elseif ( is_string( $triple[2] ) ) {
				// back-compat
				$headers = [ 'Content-Disposition' => $triple[2] ];
			} elseif ( is_array( $triple[2] ) && isset( $triple[2]['headers'] ) ) {
				$headers = $triple[2]['headers'];
			} else {
				$headers = [];
			}

			$operations[] = [
				'op' => $op,
				'src' => $src,
				'dst' => $dst,
				'headers' => $headers
			];
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
	 * @return Status
	 */
	public function quickPurgeBatch( array $paths ) {
		$status = $this->newGood();
		$operations = [];
		foreach ( $paths as $path ) {
			$operations[] = [
				'op' => 'delete',
				'src' => $this->resolveToStoragePath( $path ),
				'ignoreMissingSource' => true
			];
		}
		$status->merge( $this->backend->doQuickOperations( $operations ) );

		return $status;
	}

	/**
	 * Pick a random name in the temp zone and store a file to it.
	 * Returns a FileRepoStatus object with the file Virtual URL in the value,
	 * file can later be disposed using FileRepo::freeTemp().
	 *
	 * @param string $originalName The base name of the file as specified
	 *   by the user. The file extension will be maintained.
	 * @param string $srcPath The current location of the file.
	 * @return Status Object with the URL in the value.
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
	 * @param string $virtualUrl The virtual URL returned by FileRepo::storeTemp()
	 * @return bool True on success, false on failure
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
	 * @param int $flags Bitwise combination of the following flags:
	 *   self::DELETE_SOURCE     Delete the source files on success
	 * @return Status
	 */
	public function concatenate( array $srcPaths, $dstPath, $flags = 0 ) {
		$this->assertWritableRepo(); // fail out if read-only

		$status = $this->newGood();

		$sources = [];
		foreach ( $srcPaths as $srcPath ) {
			// Resolve source to a storage path if virtual
			$source = $this->resolveToStoragePath( $srcPath );
			$sources[] = $source; // chunk to merge
		}

		// Concatenate the chunks into one FS file
		$params = [ 'srcs' => $sources, 'dst' => $dstPath ];
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
	 * @param string|FSFile $src The source file system path, storage path, or URL
	 * @param string $dstRel The destination relative path
	 * @param string $archiveRel The relative path where the existing file is to
	 *   be archived, if there is one. Relative to the public zone root.
	 * @param int $flags Bitfield, may be FileRepo::DELETE_SOURCE to indicate
	 *   that the source file should be deleted if possible
	 * @param array $options Optional additional parameters
	 * @return Status
	 */
	public function publish(
		$src, $dstRel, $archiveRel, $flags = 0, array $options = []
	) {
		$this->assertWritableRepo(); // fail out if read-only

		$status = $this->publishBatch(
			[ [ $src, $dstRel, $archiveRel, $options ] ], $flags );
		if ( $status->successCount == 0 ) {
			$status->setOK( false );
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
	 *   (source, dest, archive, options) 4-tuples as per publish().
	 * @param int $flags Bitfield, may be FileRepo::DELETE_SOURCE to indicate
	 *   that the source files should be deleted if possible
	 * @throws MWException
	 * @return Status
	 */
	public function publishBatch( array $ntuples, $flags = 0 ) {
		$this->assertWritableRepo(); // fail out if read-only

		$backend = $this->backend; // convenience
		// Try creating directories
		$status = $this->initZones( 'public' );
		if ( !$status->isOK() ) {
			return $status;
		}

		$status = $this->newGood( [] );

		$operations = [];
		$sourceFSFilesToDelete = []; // cleanup for disk source files
		// Validate each triplet and get the store operation...
		foreach ( $ntuples as $ntuple ) {
			list( $src, $dstRel, $archiveRel ) = $ntuple;
			$srcPath = ( $src instanceof FSFile ) ? $src->getPath() : $src;

			$options = isset( $ntuple[3] ) ? $ntuple[3] : [];
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
			$headers = isset( $options['headers'] ) ? $options['headers'] : [];

			// Archive destination file if it exists.
			// This will check if the archive file also exists and fail if does.
			// This is a sanity check to avoid data loss. On Windows and Linux,
			// copy() will overwrite, so the existence check is vulnerable to
			// race conditions unless a functioning LockManager is used.
			// LocalFile also uses SELECT FOR UPDATE for synchronization.
			$operations[] = [
				'op' => 'copy',
				'src' => $dstPath,
				'dst' => $archivePath,
				'ignoreMissingSource' => true
			];

			// Copy (or move) the source file to the destination
			if ( FileBackend::isStoragePath( $srcPath ) ) {
				if ( $flags & self::DELETE_SOURCE ) {
					$operations[] = [
						'op' => 'move',
						'src' => $srcPath,
						'dst' => $dstPath,
						'overwrite' => true, // replace current
						'headers' => $headers
					];
				} else {
					$operations[] = [
						'op' => 'copy',
						'src' => $srcPath,
						'dst' => $dstPath,
						'overwrite' => true, // replace current
						'headers' => $headers
					];
				}
			} else { // FS source path
				$operations[] = [
					'op' => 'store',
					'src' => $src, // prefer FSFile objects
					'dst' => $dstPath,
					'overwrite' => true, // replace current
					'headers' => $headers
				];
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
			MediaWiki\suppressWarnings();
			unlink( $file ); // FS cleanup
			MediaWiki\restoreWarnings();
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

		$params = [ 'dir' => $path ];
		if ( $this->isPrivate
			|| $container === $this->zones['deleted']['container']
			|| $container === $this->zones['temp']['container']
		) {
			# Take all available measures to prevent web accessibility of new deleted
			# directories, in case the user has not configured offline storage
			$params = [ 'noAccess' => true, 'noListing' => true ] + $params;
		}

		$status = $this->newGood();
		$status->merge( $this->backend->prepare( $params ) );

		return $status;
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
			[ 'dir' => $this->resolveToStoragePath( $dir ) ] ) );

		return $status;
	}

	/**
	 * Checks existence of a a file
	 *
	 * @param string $file Virtual URL (or storage path) of file to check
	 * @return bool
	 */
	public function fileExists( $file ) {
		$result = $this->fileExistsBatch( [ $file ] );

		return $result[0];
	}

	/**
	 * Checks existence of an array of files.
	 *
	 * @param array $files Virtual URLs (or storage paths) of files to check
	 * @return array Map of files and existence flags, or false
	 */
	public function fileExistsBatch( array $files ) {
		$paths = array_map( [ $this, 'resolveToStoragePath' ], $files );
		$this->backend->preloadFileStat( [ 'srcs' => $paths ] );

		$result = [];
		foreach ( $files as $key => $file ) {
			$path = $this->resolveToStoragePath( $file );
			$result[$key] = $this->backend->fileExists( [ 'src' => $path ] );
		}

		return $result;
	}

	/**
	 * Move a file to the deletion archive.
	 * If no valid deletion archive exists, this may either delete the file
	 * or throw an exception, depending on the preference of the repository
	 *
	 * @param mixed $srcRel Relative path for the file to be deleted
	 * @param mixed $archiveRel Relative path for the archive location.
	 *   Relative to a private archive directory.
	 * @return Status
	 */
	public function delete( $srcRel, $archiveRel ) {
		$this->assertWritableRepo(); // fail out if read-only

		return $this->deleteBatch( [ [ $srcRel, $archiveRel ] ] );
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
	 * @param array $sourceDestPairs Array of source/destination pairs. Each element
	 *   is a two-element array containing the source file path relative to the
	 *   public root in the first element, and the archive file path relative
	 *   to the deleted zone root in the second element.
	 * @throws MWException
	 * @return Status
	 */
	public function deleteBatch( array $sourceDestPairs ) {
		$this->assertWritableRepo(); // fail out if read-only

		// Try creating directories
		$status = $this->initZones( [ 'public', 'deleted' ] );
		if ( !$status->isOK() ) {
			return $status;
		}

		$status = $this->newGood();

		$backend = $this->backend; // convenience
		$operations = [];
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

			$operations[] = [
				'op' => 'move',
				'src' => $srcPath,
				'dst' => $archivePath,
				// We may have 2+ identical files being deleted,
				// all of which will map to the same destination file
				'overwriteSame' => true // also see bug 31792
			];
		}

		// Move the files by execute the operations for each pair.
		// We're now committed to returning an OK result, which will
		// lead to the files being moved in the DB also.
		$opts = [ 'force' => true ];
		$status->merge( $backend->doOperations( $operations, $opts ) );

		return $status;
	}

	/**
	 * Delete files in the deleted directory if they are not referenced in the filearchive table
	 *
	 * STUB
	 * @param array $storageKeys
	 */
	public function cleanupDeletedBatch( array $storageKeys ) {
		$this->assertWritableRepo();
	}

	/**
	 * Get a relative path for a deletion archive key,
	 * e.g. s/z/a/ for sza251lrxrc1jad41h5mgilp8nysje52.jpg
	 *
	 * @param string $key
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
	 * @param string $path
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
	 * @param string $virtualUrl
	 * @return TempFSFile|null Returns null on failure
	 */
	public function getLocalCopy( $virtualUrl ) {
		$path = $this->resolveToStoragePath( $virtualUrl );

		return $this->backend->getLocalCopy( [ 'src' => $path ] );
	}

	/**
	 * Get a local FS file with a given virtual URL/storage path.
	 * The file is either an original or a copy. It should not be changed.
	 * Temporary files may be purged when the file object falls out of scope.
	 *
	 * @param string $virtualUrl
	 * @return FSFile|null Returns null on failure.
	 */
	public function getLocalReference( $virtualUrl ) {
		$path = $this->resolveToStoragePath( $virtualUrl );

		return $this->backend->getLocalReference( [ 'src' => $path ] );
	}

	/**
	 * Get properties of a file with a given virtual URL/storage path.
	 * Properties should ultimately be obtained via FSFile::getProps().
	 *
	 * @param string $virtualUrl
	 * @return array
	 */
	public function getFileProps( $virtualUrl ) {
		$fsFile = $this->getLocalReference( $virtualUrl );
		$mwProps = new MWFileProps( MimeMagic::singleton() );
		if ( $fsFile ) {
			$props = $mwProps->getPropsFromPath( $fsFile->getPath(), true );
		} else {
			$props = $mwProps->newPlaceholderProps();
		}

		return $props;
	}

	/**
	 * Get the timestamp of a file with a given virtual URL/storage path
	 *
	 * @param string $virtualUrl
	 * @return string|bool False on failure
	 */
	public function getFileTimestamp( $virtualUrl ) {
		$path = $this->resolveToStoragePath( $virtualUrl );

		return $this->backend->getFileTimestamp( [ 'src' => $path ] );
	}

	/**
	 * Get the size of a file with a given virtual URL/storage path
	 *
	 * @param string $virtualUrl
	 * @return int|bool False on failure
	 */
	public function getFileSize( $virtualUrl ) {
		$path = $this->resolveToStoragePath( $virtualUrl );

		return $this->backend->getFileSize( [ 'src' => $path ] );
	}

	/**
	 * Get the sha1 (base 36) of a file with a given virtual URL/storage path
	 *
	 * @param string $virtualUrl
	 * @return string|bool
	 */
	public function getFileSha1( $virtualUrl ) {
		$path = $this->resolveToStoragePath( $virtualUrl );

		return $this->backend->getFileSha1Base36( [ 'src' => $path ] );
	}

	/**
	 * Attempt to stream a file with the given virtual URL/storage path
	 *
	 * @param string $virtualUrl
	 * @param array $headers Additional HTTP headers to send on success
	 * @param array $optHeaders HTTP request headers (if-modified-since, range, ...)
	 * @return Status
	 * @since 1.27
	 */
	public function streamFileWithStatus( $virtualUrl, $headers = [], $optHeaders = [] ) {
		$path = $this->resolveToStoragePath( $virtualUrl );
		$params = [ 'src' => $path, 'headers' => $headers, 'options' => $optHeaders ];

		$status = $this->newGood();
		$status->merge( $this->backend->streamFile( $params ) );

		return $status;
	}

	/**
	 * Attempt to stream a file with the given virtual URL/storage path
	 *
	 * @deprecated since 1.26, use streamFileWithStatus
	 * @param string $virtualUrl
	 * @param array $headers Additional HTTP headers to send on success
	 * @return bool Success
	 */
	public function streamFile( $virtualUrl, $headers = [] ) {
		return $this->streamFileWithStatus( $virtualUrl, $headers )->isOK();
	}

	/**
	 * Call a callback function for every public regular file in the repository.
	 * This only acts on the current version of files, not any old versions.
	 * May use either the database or the filesystem.
	 *
	 * @param callable $callback
	 * @return void
	 */
	public function enumFiles( $callback ) {
		$this->enumFilesInStorage( $callback );
	}

	/**
	 * Call a callback function for every public file in the repository.
	 * May use either the database or the filesystem.
	 *
	 * @param callable $callback
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
			$iterator = $this->backend->getFileList( [ 'dir' => $path ] );
			foreach ( $iterator as $name ) {
				// Each item returned is a public file
				call_user_func( $callback, "{$path}/{$name}" );
			}
		}
	}

	/**
	 * Determine if a relative path is valid, i.e. not blank or involving directory traveral
	 *
	 * @param string $filename
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
	 * @return array
	 */
	function getErrorCleanupFunction() {
		switch ( $this->pathDisclosureProtection ) {
			case 'none':
			case 'simple': // b/c
				$callback = [ $this, 'passThrough' ];
				break;
			default: // 'paranoid'
				$callback = [ $this, 'paranoidClean' ];
		}
		return $callback;
	}

	/**
	 * Path disclosure protection function
	 *
	 * @param string $param
	 * @return string
	 */
	function paranoidClean( $param ) {
		return '[hidden]';
	}

	/**
	 * Path disclosure protection function
	 *
	 * @param string $param
	 * @return string
	 */
	function passThrough( $param ) {
		return $param;
	}

	/**
	 * Create a new fatal error
	 *
	 * @param string $message
	 * @return Status
	 */
	public function newFatal( $message /*, parameters...*/ ) {
		$status = call_user_func_array( [ 'Status', 'newFatal' ], func_get_args() );
		$status->cleanCallback = $this->getErrorCleanupFunction();

		return $status;
	}

	/**
	 * Create a new good result
	 *
	 * @param null|string $value
	 * @return Status
	 */
	public function newGood( $value = null ) {
		$status = Status::newGood( $value );
		$status->cleanCallback = $this->getErrorCleanupFunction();

		return $status;
	}

	/**
	 * Checks if there is a redirect named as $title. If there is, return the
	 * title object. If not, return false.
	 * STUB
	 *
	 * @param Title $title Title of image
	 * @return bool
	 */
	public function checkRedirect( Title $title ) {
		return false;
	}

	/**
	 * Invalidates image redirect cache related to that image
	 * Doesn't do anything for repositories that don't support image redirects.
	 *
	 * STUB
	 * @param Title $title Title of image
	 */
	public function invalidateImageRedirect( Title $title ) {
	}

	/**
	 * Get the human-readable name of the repo
	 *
	 * @return string
	 */
	public function getDisplayName() {
		global $wgSitename;

		if ( $this->isLocal() ) {
			return $wgSitename;
		}

		// 'shared-repo-name-wikimediacommons' is used when $wgUseInstantCommons = true
		return wfMessageFallback( 'shared-repo-name-' . $this->name, 'shared-repo' )->text();
	}

	/**
	 * Get the portion of the file that contains the origin file name.
	 * If that name is too long, then the name "thumbnail.<ext>" will be given.
	 *
	 * @param string $name
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
	 * Get a temporary private FileRepo associated with this repo.
	 *
	 * Files will be created in the temp zone of this repo.
	 * It will have the same backend as this repo.
	 *
	 * @return TempFileRepo
	 */
	public function getTempRepo() {
		return new TempFileRepo( [
			'name' => "{$this->name}-temp",
			'backend' => $this->backend,
			'zones' => [
				'public' => [
					// Same place storeTemp() uses in the base repo, though
					// the path hashing is mismatched, which is annoying.
					'container' => $this->zones['temp']['container'],
					'directory' => $this->zones['temp']['directory']
				],
				'thumb' => [
					'container' => $this->zones['temp']['container'],
					'directory' => $this->zones['temp']['directory'] == ''
						? 'thumb'
						: $this->zones['temp']['directory'] . '/thumb'
				],
				'transcoded' => [
					'container' => $this->zones['temp']['container'],
					'directory' => $this->zones['temp']['directory'] == ''
						? 'transcoded'
						: $this->zones['temp']['directory'] . '/transcoded'
				]
			],
			'hashLevels' => $this->hashLevels, // performance
			'isPrivate' => true // all in temp zone
		] );
	}

	/**
	 * Get an UploadStash associated with this repo.
	 *
	 * @param User $user
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
	protected function assertWritableRepo() {
	}

	/**
	 * Return information about the repository.
	 *
	 * @return array
	 * @since 1.22
	 */
	public function getInfo() {
		$ret = [
			'name' => $this->getName(),
			'displayname' => $this->getDisplayName(),
			'rootUrl' => $this->getZoneUrl( 'public' ),
			'local' => $this->isLocal(),
		];

		$optionalSettings = [
			'url', 'thumbUrl', 'initialCapital', 'descBaseUrl', 'scriptDirUrl', 'articleUrl',
			'fetchDescription', 'descriptionCacheExpiry', 'scriptExtension', 'favicon'
		];
		foreach ( $optionalSettings as $k ) {
			if ( isset( $this->$k ) ) {
				$ret[$k] = $this->$k;
			}
		}

		return $ret;
	}

	/**
	 * Returns whether or not storage is SHA-1 based
	 * @return bool
	 */
	public function hasSha1Storage() {
		return $this->hasSha1Storage;
	}

	/**
	 * Returns whether or not repo supports having originals SHA-1s in the thumb URLs
	 * @return bool
	 */
	public function supportsSha1URLs() {
		return $this->supportsSha1URLs;
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
