<?php
/**
 * @defgroup FileRepo File Repository
 *
 * @brief This module handles how MediaWiki interacts with filesystems.
 *
 * @details
 */

use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\User\UserIdentity;
use Wikimedia\AtEase\AtEase;

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
 * See [the architecture doc](@ref filerepoarch) for more information.
 *
 * @ingroup FileRepo
 */
class FileRepo {
	public const DELETE_SOURCE = 1;
	public const OVERWRITE = 2;
	public const OVERWRITE_SAME = 4;
	public const SKIP_LOCKING = 8;

	public const NAME_AND_TIME_ONLY = 1;

	/** @var bool Whether to fetch commons image description pages and display
	 *    them on the local wiki
	 */
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
	 *    on a 404 handler instead.
	 */
	protected $transformVia404;

	/** @var string URL of image description pages, e.g.
	 *    https://en.wikipedia.org/wiki/File:
	 */
	protected $descBaseUrl;

	/** @var string URL of the MediaWiki installation, equivalent to
	 *    $wgScriptPath, e.g. https://en.wikipedia.org/w
	 */
	protected $scriptDirUrl;

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

	/** @var string|false Public zone URL. */
	protected $url;

	/** @var string|false The base thumbnail URL. Defaults to "<url>/thumb". */
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

	/** @var null|string The URL to a favicon (optional, may be a server-local path URL). */
	protected $favicon = null;

	/** @var bool Whether all zones should be private (e.g. private wiki repo) */
	protected $isPrivate;

	/** @var callable Override these in the base class */
	protected $fileFactory = [ UnregisteredLocalFile::class, 'newFromTitle' ];
	/** @var callable|false Override these in the base class */
	protected $oldFileFactory = false;
	/** @var callable|false Override these in the base class */
	protected $fileFactoryKey = false;
	/** @var callable|false Override these in the base class */
	protected $oldFileFactoryKey = false;

	/** @var string URL of where to proxy thumb.php requests to.
	 *    Example: http://127.0.0.1:8888/wiki/dev/thumb/
	 */
	protected $thumbProxyUrl;
	/** @var string Secret key to pass as an X-Swift-Secret header to the proxied thumb service */
	protected $thumbProxySecret;

	/** @var bool Disable local image scaling */
	protected $disableLocalTransform = false;

	/** @var WANObjectCache */
	protected $wanCache;

	/**
	 * @var string
	 * @note Use $this->getName(). Public for back-compat only
	 * @todo make protected
	 */
	public $name;

	/**
	 * @see Documentation of info options at $wgLocalFileRepo
	 * @param array|null $info
	 * @throws MWException
	 * @phan-assert array $info
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
			$this->backend =
				MediaWikiServices::getInstance()->getFileBackendGroup()->get( $info['backend'] );
		}

		// Optional settings that can have no value
		$optionalSettings = [
			'descBaseUrl', 'scriptDirUrl', 'articleUrl', 'fetchDescription',
			'thumbScriptUrl', 'pathDisclosureProtection', 'descriptionCacheExpiry',
			'favicon', 'thumbProxyUrl', 'thumbProxySecret', 'disableLocalTransform'
		];
		foreach ( $optionalSettings as $var ) {
			if ( isset( $info[$var] ) ) {
				$this->$var = $info[$var];
			}
		}

		// Optional settings that have a default
		$localCapitalLinks =
			MediaWikiServices::getInstance()->getNamespaceInfo()->isCapitalized( NS_FILE );
		$this->initialCapital = $info['initialCapital'] ?? $localCapitalLinks;
		if ( $localCapitalLinks && !$this->initialCapital ) {
			// If the local wiki's file namespace requires an initial capital, but a foreign file
			// repo doesn't, complications will result. Linker code will want to auto-capitalize the
			// first letter of links to files, but those links might actually point to files on
			// foreign wikis with initial-lowercase names. This combination is not likely to be
			// used by anyone anyway, so we just outlaw it to save ourselves the bugs. If you want
			// to include a foreign file repo with initialCapital false, set your local file
			// namespace to not be capitalized either.
			throw new InvalidArgumentException(
				'File repos with initial capital false are not allowed on wikis where the File ' .
				'namespace has initial capital true' );
		}

		$this->url = $info['url'] ?? false; // a subclass may set the URL (e.g. ForeignAPIRepo)
		$defaultThumbUrl = $this->url ? $this->url . '/thumb' : false;
		$this->thumbUrl = $info['thumbUrl'] ?? $defaultThumbUrl;
		$this->hashLevels = $info['hashLevels'] ?? 2;
		$this->deletedHashLevels = $info['deletedHashLevels'] ?? $this->hashLevels;
		$this->transformVia404 = !empty( $info['transformVia404'] );
		$this->abbrvThreshold = $info['abbrvThreshold'] ?? 255;
		$this->isPrivate = !empty( $info['isPrivate'] );
		// Give defaults for the basic zones...
		$this->zones = $info['zones'] ?? [];
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

		$this->wanCache = $info['wanCache'] ?? WANObjectCache::newEmpty();
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
	 * @return string|false Returns false if the repo is not read-only
	 */
	public function getReadOnlyReason() {
		return $this->backend->getReadOnlyReason();
	}

	/**
	 * Ensure that a single zone or list of zones is defined for usage
	 *
	 * @param string[]|string $doZones Only do a particular zones
	 * @throws MWException
	 */
	protected function initZones( $doZones = [] ): void {
		foreach ( (array)$doZones as $zone ) {
			$root = $this->getZonePath( $zone );
			if ( $root === null ) {
				throw new MWException( "No '$zone' zone defined in the {$this->name} repo." );
			}
		}
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
	 * @param string|false $suffix
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
	 * @return string|false
	 */
	public function getZoneUrl( $zone, $ext = null ) {
		if ( in_array( $zone, [ 'public', 'thumb', 'transcoded' ] ) ) {
			// standard public zones
			if ( $ext !== null && isset( $this->zones[$zone]['urlsByExt'][$ext] ) ) {
				// custom URL for extension/zone
				// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
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
		return (bool)( $this->getBackend()->getFeatures() & FileBackend::ATTR_UNICODE_PATHS );
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
	 * The storage container and base path of a zone
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
	 * @param PageIdentity|LinkTarget|string $title
	 * @param string|false $time Time at which the image was uploaded. If this
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
	 * @param PageIdentity|LinkTarget|string $title
	 * @param array $options Associative array of options:
	 *   time:           requested time for a specific file version, or false for the
	 *                   current version. An image object will be returned which was
	 *                   created at the specified time (which may be archived or current).
	 *   ignoreRedirect: If true, do not follow file redirects
	 *   private:        If an Authority object, return restricted (deleted) files if the
	 *                   performer is allowed to view them. Otherwise, such files will not
	 *                   be found. If set and not an Authority object, throws an exception.
	 *                   Authority is only accepted since 1.37, User was required before.
	 *   latest:         If true, load from the latest available data into File objects
	 * @return File|false False on failure
	 * @throws InvalidArgumentException
	 */
	public function findFile( $title, $options = [] ) {
		if ( !empty( $options['private'] ) && !( $options['private'] instanceof Authority ) ) {
			throw new InvalidArgumentException(
				__METHOD__ . ' called with the `private` option set to something ' .
				'other than an Authority object'
			);
		}

		$title = File::normalizeTitle( $title );
		if ( !$title ) {
			return false;
		}
		if ( isset( $options['bypassCache'] ) ) {
			$options['latest'] = $options['bypassCache']; // b/c
		}
		$time = $options['time'] ?? false;
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
					} elseif (
						// If its not empty, its an Authority object
						!empty( $options['private'] ) &&
						$img->userCan( File::DELETED_FILE, $options['private'] )
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
		if ( $redir && $title->getNamespace() === NS_FILE ) {
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
	 * @return array Map of (file name => File objects) for matches or (search title => (title,timestamp))
	 */
	public function findFiles( array $items, $flags = 0 ) {
		$result = [];
		foreach ( $items as $item ) {
			if ( is_array( $item ) ) {
				$title = $item['title'];
				$options = $item;
				unset( $options['title'] );

				if (
					!empty( $options['private'] ) &&
					!( $options['private'] instanceof Authority )
				) {
					$options['private'] = RequestContext::getMain()->getAuthority();
				}
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
	 * @return File|false False on failure
	 * @throws InvalidArgumentException if the `private` option is set and not an Authority object
	 */
	public function findFileFromKey( $sha1, $options = [] ) {
		if ( !empty( $options['private'] ) && !( $options['private'] instanceof Authority ) ) {
			throw new InvalidArgumentException(
				__METHOD__ . ' called with the `private` option set to something ' .
				'other than an Authority object'
			);
		}

		$time = $options['time'] ?? false;
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
				} elseif (
					// If its not empty, its an Authority object
					!empty( $options['private'] ) &&
					$img->userCan( File::DELETED_FILE, $options['private'] )
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
	 * @param string[] $hashes An array of hashes
	 * @return File[][] An Array of arrays or iterators of file objects and the hash as key
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
	 * @return LocalFile[]
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
	 * Get the URL thumb.php requests are being proxied to
	 *
	 * @return string
	 */
	public function getThumbProxyUrl() {
		return $this->thumbProxyUrl;
	}

	/**
	 * Get the secret key for the proxied thumb service
	 *
	 * @return string
	 */
	public function getThumbProxySecret() {
		return $this->thumbProxySecret;
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
	 * Returns true if the repository can transform files locally.
	 *
	 * @since 1.36
	 * @return bool
	 */
	public function canTransformLocally() {
		return !$this->disableLocalTransform;
	}

	/**
	 * Get the name of a file from its title
	 *
	 * @param PageIdentity|LinkTarget $title
	 * @return string
	 */
	public function getNameFromTitle( $title ) {
		if (
			$this->initialCapital !=
			MediaWikiServices::getInstance()->getNamespaceInfo()->isCapitalized( NS_FILE )
		) {
			$name = $title->getDBkey();
			if ( $this->initialCapital ) {
				$name = MediaWikiServices::getInstance()->getContentLanguage()->ucfirst( $name );
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
		$name = $parts[1] ?? $suffix; // hash path is not based on timestamp
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
	 * @param string|array $query Query string to append
	 * @param string $entry Entry point; defaults to index
	 * @return string|false False on failure
	 */
	public function makeUrl( $query = '', $entry = 'index' ) {
		if ( isset( $this->scriptDirUrl ) ) {
			return wfAppendQuery( "{$this->scriptDirUrl}/{$entry}.php", $query );
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
	 * @return string|false
	 */
	public function getDescriptionUrl( $name ) {
		$encName = wfUrlencode( $name );
		if ( $this->descBaseUrl !== null ) {
			# "http://example.com/wiki/File:"
			return $this->descBaseUrl . $encName;
		}
		if ( $this->articleUrl !== null ) {
			# "http://example.com/wiki/$1"
			# We use "Image:" as the canonical namespace for
			# compatibility across all MediaWiki versions.
			return str_replace( '$1',
				"Image:$encName", $this->articleUrl );
		}
		if ( $this->scriptDirUrl !== null ) {
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
	 * @param string|null $lang Language to fetch it in, if any.
	 * @return string|false
	 */
	public function getDescriptionRenderUrl( $name, $lang = null ) {
		$query = 'action=render';
		if ( $lang !== null ) {
			$query .= '&uselang=' . urlencode( $lang );
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
	 * @return string|false False on failure
	 */
	public function getDescriptionStylesheetUrl() {
		if ( isset( $this->scriptDirUrl ) ) {
			// Must match canonical query parameter order for optimum caching
			// See HtmlCacheUpdater::getUrls
			return $this->makeUrl( 'title=MediaWiki:Filepage.css&action=raw&ctype=text/css' );
		}

		return false;
	}

	/**
	 * Store a file to a given destination.
	 *
	 * Using FSFile/TempFSFile can improve performance via caching.
	 * Using TempFSFile can further improve performance by signalling that it is safe
	 * to touch the source file or write extended attribute metadata to it directly.
	 *
	 * @param string|FSFile $srcPath Source file system path, storage path, or virtual URL
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
	 * @see FileRepo::store()
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
			list( $src, $dstZone, $dstRel ) = $triplet;
			$srcPath = ( $src instanceof FSFile ) ? $src->getPath() : $src;
			wfDebug( __METHOD__
				. "( \$src='$srcPath', \$dstZone='$dstZone', \$dstRel='$dstRel' )"
			);
			// Resolve source path
			if ( $src instanceof FSFile ) {
				$op = 'store';
			} else {
				$src = $this->resolveToStoragePathIfVirtual( $src );
				$op = FileBackend::isStoragePath( $src ) ? 'copy' : 'store';
			}
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

			// Copy the source file to the destination
			$operations[] = [
				'op' => $op,
				'src' => $src, // storage path (copy) or local file path (store)
				'dst' => $dstPath,
				'overwrite' => ( $flags & self::OVERWRITE ) ? true : false,
				'overwriteSame' => ( $flags & self::OVERWRITE_SAME ) ? true : false,
			];
		}

		// Execute the store operation for each triplet
		$opts = [ 'force' => true ];
		if ( $flags & self::SKIP_LOCKING ) {
			$opts['nonLocking'] = true;
		}

		return $status->merge( $backend->doOperations( $operations, $opts ) );
	}

	/**
	 * Deletes a batch of files.
	 * Each file can be a (zone, rel) pair, virtual url, storage path.
	 * It will try to delete each file, but ignores any errors that may occur.
	 *
	 * @param string[] $files List of files to delete
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
				$path = $this->resolveToStoragePathIfVirtual( $path );
			}
			$operations[] = [ 'op' => 'delete', 'src' => $path ];
		}
		// Actually delete files from storage...
		$opts = [ 'force' => true ];
		if ( $flags & self::SKIP_LOCKING ) {
			$opts['nonLocking'] = true;
		}

		return $status->merge( $this->backend->doOperations( $operations, $opts ) );
	}

	/**
	 * Import a file from the local file system into the repo.
	 * This does no locking and overrides existing files.
	 * This function can be used to write to otherwise read-only foreign repos.
	 * This is intended for copying generated thumbnails into the repo.
	 *
	 * Using FSFile/TempFSFile can improve performance via caching.
	 * Using TempFSFile can further improve performance by signalling that it is safe
	 * to touch the source file or write extended attribute metadata to it directly.
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
	 * Import a batch of files from the local file system into the repo.
	 * This does no locking and overrides existing files.
	 * This function can be used to write to otherwise read-only foreign repos.
	 * This is intended for copying generated thumbnails into the repo.
	 *
	 * @see FileRepo::quickImport()
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
				$src = $this->resolveToStoragePathIfVirtual( $src );
				$op = FileBackend::isStoragePath( $src ) ? 'copy' : 'store';
			}
			$dst = $this->resolveToStoragePathIfVirtual( $dst );

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
				'src' => $src, // storage path (copy) or local path/FSFile (store)
				'dst' => $dst,
				'headers' => $headers
			];
			$status->merge( $this->initDirectory( dirname( $dst ) ) );
		}

		return $status->merge( $this->backend->doQuickOperations( $operations ) );
	}

	/**
	 * Purge a file from the repo. This does no locking.
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
		return $this->newGood()->merge(
			$this->backend->clean(
				[ 'dir' => $this->resolveToStoragePathIfVirtual( $dir ) ]
			)
		);
	}

	/**
	 * Purge a batch of files from the repo.
	 * This function can be used to write to otherwise read-only foreign repos.
	 * This does no locking and is intended for purging thumbnails.
	 *
	 * @param string[] $paths List of virtual URLs or storage paths
	 * @return Status
	 */
	public function quickPurgeBatch( array $paths ) {
		$status = $this->newGood();
		$operations = [];
		foreach ( $paths as $path ) {
			$operations[] = [
				'op' => 'delete',
				'src' => $this->resolveToStoragePathIfVirtual( $path ),
				'ignoreMissingSource' => true
			];
		}
		$status->merge( $this->backend->doQuickOperations( $operations ) );

		return $status;
	}

	/**
	 * Pick a random name in the temp zone and store a file to it.
	 * Returns a Status object with the file Virtual URL in the value,
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
		if ( !str_starts_with( $virtualUrl, $temp ) ) {
			wfDebug( __METHOD__ . ": Invalid temp virtual URL" );

			return false;
		}

		return $this->quickPurge( $virtualUrl )->isOK();
	}

	/**
	 * Concatenate a list of temporary files into a target file location.
	 *
	 * @param string[] $srcPaths Ordered list of source virtual URLs/storage paths
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
			$source = $this->resolveToStoragePathIfVirtual( $srcPath );
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
	 * Returns a Status object. On success, the value contains "new" or
	 * "archived", to indicate whether the file was new with that name.
	 *
	 * Using FSFile/TempFSFile can improve performance via caching.
	 * Using TempFSFile can further improve performance by signalling that it is safe
	 * to touch the source file or write extended attribute metadata to it directly.
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
		$status->value = $status->value[0] ?? false;

		return $status;
	}

	/**
	 * Publish a batch of files
	 *
	 * @see FileRepo::publish()
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
		$this->initZones( 'public' );

		$status = $this->newGood( [] );

		$operations = [];
		$sourceFSFilesToDelete = []; // cleanup for disk source files
		// Validate each triplet and get the store operation...
		foreach ( $ntuples as $ntuple ) {
			list( $src, $dstRel, $archiveRel ) = $ntuple;
			$srcPath = ( $src instanceof FSFile ) ? $src->getPath() : $src;

			$options = $ntuple[3] ?? [];
			// Resolve source to a storage path if virtual
			$srcPath = $this->resolveToStoragePathIfVirtual( $srcPath );
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
			$headers = $options['headers'] ?? [];

			// Archive destination file if it exists.
			// This will check if the archive file also exists and fail if does.
			// This is a check to avoid data loss. On Windows and Linux,
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
				$operations[] = [
					'op' => ( $flags & self::DELETE_SOURCE ) ? 'move' : 'copy',
					'src' => $srcPath,
					'dst' => $dstPath,
					'overwrite' => true, // replace current
					'headers' => $headers
				];
			} else {
				$operations[] = [
					'op' => 'store',
					'src' => $src, // storage path (copy) or local path/FSFile (store)
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
			AtEase::suppressWarnings();
			unlink( $file ); // FS cleanup
			AtEase::restoreWarnings();
		}

		return $status;
	}

	/**
	 * Creates a directory with the appropriate zone permissions.
	 * Callers are responsible for doing read-only and "writable repo" checks.
	 *
	 * @param string $dir Virtual URL (or storage path) of directory to clean
	 * @return Status Good status without value for success, fatal otherwise.
	 */
	protected function initDirectory( $dir ) {
		$path = $this->resolveToStoragePathIfVirtual( $dir );
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

		return $this->newGood()->merge( $this->backend->prepare( $params ) );
	}

	/**
	 * Deletes a directory if empty.
	 *
	 * @param string $dir Virtual URL (or storage path) of directory to clean
	 * @return Status
	 */
	public function cleanDir( $dir ) {
		$this->assertWritableRepo(); // fail out if read-only

		return $this->newGood()->merge(
			$this->backend->clean(
				[ 'dir' => $this->resolveToStoragePathIfVirtual( $dir ) ]
			)
		);
	}

	/**
	 * Checks existence of a file
	 *
	 * @param string $file Virtual URL (or storage path) of file to check
	 * @return bool|null Whether the file exists, or null in case of I/O errors
	 */
	public function fileExists( $file ) {
		$result = $this->fileExistsBatch( [ $file ] );

		return $result[0];
	}

	/**
	 * Checks existence of an array of files.
	 *
	 * @param string[] $files Virtual URLs (or storage paths) of files to check
	 * @return array<string|int,bool|null> Map of files and either bool indicating whether the files exist,
	 *   or null in case of I/O errors
	 */
	public function fileExistsBatch( array $files ) {
		$paths = array_map( [ $this, 'resolveToStoragePathIfVirtual' ], $files );
		$this->backend->preloadFileStat( [ 'srcs' => $paths ] );

		$result = [];
		foreach ( $files as $key => $file ) {
			$path = $this->resolveToStoragePathIfVirtual( $file );
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
		$this->initZones( [ 'public', 'deleted' ] );

		$status = $this->newGood();

		$backend = $this->backend; // convenience
		$operations = [];
		// Validate filenames and create archive directories
		foreach ( $sourceDestPairs as [ $srcRel, $archiveRel ] ) {
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
			if ( !$this->initDirectory( $archiveDir )->isGood() ) {
				return $this->newFatal( 'directorycreateerror', $archiveDir );
			}

			$operations[] = [
				'op' => 'move',
				'src' => $srcPath,
				'dst' => $archivePath,
				// We may have 2+ identical files being deleted,
				// all of which will map to the same destination file
				'overwriteSame' => true // also see T33792
			];
		}

		// Move the files by execute the operations for each pair.
		// We're now committed to returning an OK result, which will
		// lead to the files being moved in the DB also.
		$opts = [ 'force' => true ];
		return $status->merge( $backend->doOperations( $operations, $opts ) );
	}

	/**
	 * Delete files in the deleted directory if they are not referenced in the filearchive table
	 *
	 * STUB
	 * @param string[] $storageKeys
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
	protected function resolveToStoragePathIfVirtual( $path ) {
		if ( self::isVirtualUrl( $path ) ) {
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
		$path = $this->resolveToStoragePathIfVirtual( $virtualUrl );

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
		$path = $this->resolveToStoragePathIfVirtual( $virtualUrl );

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
		$mwProps = new MWFileProps( MediaWikiServices::getInstance()->getMimeAnalyzer() );
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
	 * @return string|false False on failure
	 */
	public function getFileTimestamp( $virtualUrl ) {
		$path = $this->resolveToStoragePathIfVirtual( $virtualUrl );

		return $this->backend->getFileTimestamp( [ 'src' => $path ] );
	}

	/**
	 * Get the size of a file with a given virtual URL/storage path
	 *
	 * @param string $virtualUrl
	 * @return int|false
	 */
	public function getFileSize( $virtualUrl ) {
		$path = $this->resolveToStoragePathIfVirtual( $virtualUrl );

		return $this->backend->getFileSize( [ 'src' => $path ] );
	}

	/**
	 * Get the sha1 (base 36) of a file with a given virtual URL/storage path
	 *
	 * @param string $virtualUrl
	 * @return string|false
	 */
	public function getFileSha1( $virtualUrl ) {
		$path = $this->resolveToStoragePathIfVirtual( $virtualUrl );

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
		$path = $this->resolveToStoragePathIfVirtual( $virtualUrl );
		$params = [ 'src' => $path, 'headers' => $headers, 'options' => $optHeaders ];

		// T172851: HHVM does not flush the output properly, causing OOM
		ob_start( null, 1048576 );
		ob_implicit_flush( true );

		$status = $this->newGood()->merge( $this->backend->streamFile( $params ) );

		// T186565: Close the buffer, unless it has already been closed
		// in HTTPFileStreamer::resetOutputBuffers().
		if ( ob_get_status() ) {
			ob_end_flush();
		}

		return $status;
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
			if ( $iterator === null ) {
				throw new MWException( __METHOD__ . ': could not get file listing for ' . $path );
			}
			foreach ( $iterator as $name ) {
				// Each item returned is a public file
				call_user_func( $callback, "{$path}/{$name}" );
			}
		}
	}

	/**
	 * Determine if a relative path is valid, i.e. not blank or involving directory traversal
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
	 * @return callable
	 */
	private function getErrorCleanupFunction() {
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
	public function paranoidClean( $param ) {
		return '[hidden]';
	}

	/**
	 * Path disclosure protection function
	 *
	 * @param string $param
	 * @return string
	 */
	public function passThrough( $param ) {
		return $param;
	}

	/**
	 * Create a new fatal error
	 *
	 * @param string $message
	 * @param mixed ...$parameters
	 * @return Status
	 */
	public function newFatal( $message, ...$parameters ) {
		$status = Status::newFatal( $message, ...$parameters );
		$status->cleanCallback = $this->getErrorCleanupFunction();

		return $status;
	}

	/**
	 * Create a new good result
	 *
	 * @param null|mixed $value
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
	 * @param PageIdentity|LinkTarget $title Title of image
	 * @return Title|false
	 */
	public function checkRedirect( $title ) {
		return false;
	}

	/**
	 * Invalidates image redirect cache related to that image
	 * Doesn't do anything for repositories that don't support image redirects.
	 *
	 * STUB
	 * @param PageIdentity|LinkTarget $title Title of image
	 */
	public function invalidateImageRedirect( $title ) {
	}

	/**
	 * Get the human-readable name of the repo
	 *
	 * @return string
	 */
	public function getDisplayName() {
		$sitename = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::Sitename );

		if ( $this->isLocal() ) {
			return $sitename;
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
	 * Get a global, repository-qualified, WAN cache key
	 *
	 * This might be called from either the site context of the wiki that owns the repo or
	 * the site context of another wiki that simply has access to the repo. This returns
	 * false if the repository's cache is not accessible from the current site context.
	 *
	 * @param string $kClassSuffix Key collection name suffix (added to this repo class)
	 * @param mixed ...$components Additional key components
	 * @return string|false
	 */
	public function getSharedCacheKey( $kClassSuffix, ...$components ) {
		return false;
	}

	/**
	 * Get a site-local, repository-qualified, WAN cache key
	 *
	 * These cache keys are not shared among different site context and thus cannot be
	 * directly invalidated when repo objects are modified. These are useful when there
	 * is no accessible global cache or the values depend on the current site context.
	 *
	 * @param string $kClassSuffix Key collection name suffix (added to this repo class)
	 * @param mixed ...$components Additional key components
	 * @return string
	 */
	public function getLocalCacheKey( $kClassSuffix, ...$components ) {
		return $this->wanCache->makeKey(
			'filerepo-' . $kClassSuffix,
			$this->getName(),
			...$components
		);
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
	 * @param UserIdentity|null $user
	 * @return UploadStash
	 */
	public function getUploadStash( UserIdentity $user = null ) {
		return new UploadStash( $this, $user );
	}

	/**
	 * Throw an exception if this repo is read-only by design.
	 * This does not and should not check getReadOnlyReason().
	 *
	 * @return void|never
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
			'url',
			'thumbUrl',
			'initialCapital',
			'descBaseUrl',
			'scriptDirUrl',
			'articleUrl',
			'fetchDescription',
			'descriptionCacheExpiry',
		];
		foreach ( $optionalSettings as $k ) {
			if ( isset( $this->$k ) ) {
				$ret[$k] = $this->$k;
			}
		}
		if ( isset( $this->favicon ) ) {
			// Expand any local path to full URL to improve API usability (T77093).
			$ret['favicon'] = wfExpandUrl( $this->favicon );
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
