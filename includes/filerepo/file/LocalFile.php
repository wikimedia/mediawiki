<?php
/**
 * Local file in the wiki's own database.
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
 * @ingroup FileAbstraction
 */

use \MediaWiki\Logger\LoggerFactory;

/**
 * Class to represent a local file in the wiki's own database
 *
 * Provides methods to retrieve paths (physical, logical, URL),
 * to generate image thumbnails or for uploading.
 *
 * Note that only the repo object knows what its file class is called. You should
 * never name a file class explictly outside of the repo class. Instead use the
 * repo's factory functions to generate file objects, for example:
 *
 * RepoGroup::singleton()->getLocalRepo()->newFile( $title );
 *
 * The convenience functions wfLocalFile() and wfFindFile() should be sufficient
 * in most cases.
 *
 * @ingroup FileAbstraction
 */
class LocalFile extends File {
	const VERSION = 10; // cache version

	const CACHE_FIELD_MAX_LEN = 1000;

	/** @var bool Does the file exist on disk? (loadFromXxx) */
	protected $fileExists;

	/** @var int Image width */
	protected $width;

	/** @var int Image height */
	protected $height;

	/** @var int Returned by getimagesize (loadFromXxx) */
	protected $bits;

	/** @var string MEDIATYPE_xxx (bitmap, drawing, audio...) */
	protected $media_type;

	/** @var string MIME type, determined by MimeMagic::guessMimeType */
	protected $mime;

	/** @var int Size in bytes (loadFromXxx) */
	protected $size;

	/** @var string Handler-specific metadata */
	protected $metadata;

	/** @var string SHA-1 base 36 content hash */
	protected $sha1;

	/** @var bool Whether or not core data has been loaded from the database (loadFromXxx) */
	protected $dataLoaded;

	/** @var bool Whether or not lazy-loaded data has been loaded from the database */
	protected $extraDataLoaded;

	/** @var int Bitfield akin to rev_deleted */
	protected $deleted;

	/** @var string */
	protected $repoClass = 'LocalRepo';

	/** @var int Number of line to return by nextHistoryLine() (constructor) */
	private $historyLine;

	/** @var int Result of the query for the file's history (nextHistoryLine) */
	private $historyRes;

	/** @var string Major MIME type */
	private $major_mime;

	/** @var string Minor MIME type */
	private $minor_mime;

	/** @var string Upload timestamp */
	private $timestamp;

	/** @var int User ID of uploader */
	private $user;

	/** @var string User name of uploader */
	private $user_text;

	/** @var string Description of current revision of the file */
	private $description;

	/** @var string TS_MW timestamp of the last change of the file description */
	private $descriptionTouched;

	/** @var bool Whether the row was upgraded on load */
	private $upgraded;

	/** @var bool Whether the row was scheduled to upgrade on load */
	private $upgrading;

	/** @var bool True if the image row is locked */
	private $locked;

	/** @var bool True if the image row is locked with a lock initiated transaction */
	private $lockedOwnTrx;

	/** @var bool True if file is not present in file system. Not to be cached in memcached */
	private $missing;

	// @note: higher than IDBAccessObject constants
	const LOAD_ALL = 16; // integer; load all the lazy fields too (like metadata)

	const ATOMIC_SECTION_LOCK = 'LocalFile::lockingTransaction';

	/**
	 * Create a LocalFile from a title
	 * Do not call this except from inside a repo class.
	 *
	 * Note: $unused param is only here to avoid an E_STRICT
	 *
	 * @param Title $title
	 * @param FileRepo $repo
	 * @param null $unused
	 *
	 * @return LocalFile
	 */
	static function newFromTitle( $title, $repo, $unused = null ) {
		return new self( $title, $repo );
	}

	/**
	 * Create a LocalFile from a title
	 * Do not call this except from inside a repo class.
	 *
	 * @param stdClass $row
	 * @param FileRepo $repo
	 *
	 * @return LocalFile
	 */
	static function newFromRow( $row, $repo ) {
		$title = Title::makeTitle( NS_FILE, $row->img_name );
		$file = new self( $title, $repo );
		$file->loadFromRow( $row );

		return $file;
	}

	/**
	 * Create a LocalFile from a SHA-1 key
	 * Do not call this except from inside a repo class.
	 *
	 * @param string $sha1 Base-36 SHA-1
	 * @param LocalRepo $repo
	 * @param string|bool $timestamp MW_timestamp (optional)
	 * @return bool|LocalFile
	 */
	static function newFromKey( $sha1, $repo, $timestamp = false ) {
		$dbr = $repo->getSlaveDB();

		$conds = [ 'img_sha1' => $sha1 ];
		if ( $timestamp ) {
			$conds['img_timestamp'] = $dbr->timestamp( $timestamp );
		}

		$row = $dbr->selectRow( 'image', self::selectFields(), $conds, __METHOD__ );
		if ( $row ) {
			return self::newFromRow( $row, $repo );
		} else {
			return false;
		}
	}

	/**
	 * Fields in the image table
	 * @return array
	 */
	static function selectFields() {
		return [
			'img_name',
			'img_size',
			'img_width',
			'img_height',
			'img_metadata',
			'img_bits',
			'img_media_type',
			'img_major_mime',
			'img_minor_mime',
			'img_description',
			'img_user',
			'img_user_text',
			'img_timestamp',
			'img_sha1',
		];
	}

	/**
	 * Constructor.
	 * Do not call this except from inside a repo class.
	 * @param Title $title
	 * @param FileRepo $repo
	 */
	function __construct( $title, $repo ) {
		parent::__construct( $title, $repo );

		$this->metadata = '';
		$this->historyLine = 0;
		$this->historyRes = null;
		$this->dataLoaded = false;
		$this->extraDataLoaded = false;

		$this->assertRepoDefined();
		$this->assertTitleDefined();
	}

	/**
	 * Get the memcached key for the main data for this file, or false if
	 * there is no access to the shared cache.
	 * @return string|bool
	 */
	function getCacheKey() {
		return $this->repo->getSharedCacheKey( 'file', sha1( $this->getName() ) );
	}

	/**
	 * Try to load file metadata from memcached, falling back to the database
	 */
	private function loadFromCache() {
		$this->dataLoaded = false;
		$this->extraDataLoaded = false;

		$key = $this->getCacheKey();
		if ( !$key ) {
			$this->loadFromDB( self::READ_NORMAL );

			return;
		}

		$cache = ObjectCache::getMainWANInstance();
		$cachedValues = $cache->getWithSetCallback(
			$key,
			$cache::TTL_WEEK,
			function ( $oldValue, &$ttl, array &$setOpts ) use ( $cache ) {
				$setOpts += Database::getCacheSetOptions( $this->repo->getSlaveDB() );

				$this->loadFromDB( self::READ_NORMAL );

				$fields = $this->getCacheFields( '' );
				$cacheVal['fileExists'] = $this->fileExists;
				if ( $this->fileExists ) {
					foreach ( $fields as $field ) {
						$cacheVal[$field] = $this->$field;
					}
				}
				// Strip off excessive entries from the subset of fields that can become large.
				// If the cache value gets to large it will not fit in memcached and nothing will
				// get cached at all, causing master queries for any file access.
				foreach ( $this->getLazyCacheFields( '' ) as $field ) {
					if ( isset( $cacheVal[$field] )
						&& strlen( $cacheVal[$field] ) > 100 * 1024
					) {
						unset( $cacheVal[$field] ); // don't let the value get too big
					}
				}

				if ( $this->fileExists ) {
					$ttl = $cache->adaptiveTTL( wfTimestamp( TS_UNIX, $this->timestamp ), $ttl );
				} else {
					$ttl = $cache::TTL_DAY;
				}

				return $cacheVal;
			},
			[ 'version' => self::VERSION ]
		);

		$this->fileExists = $cachedValues['fileExists'];
		if ( $this->fileExists ) {
			$this->setProps( $cachedValues );
		}

		$this->dataLoaded = true;
		$this->extraDataLoaded = true;
		foreach ( $this->getLazyCacheFields( '' ) as $field ) {
			$this->extraDataLoaded = $this->extraDataLoaded && isset( $cachedValues[$field] );
		}
	}

	/**
	 * Purge the file object/metadata cache
	 */
	public function invalidateCache() {
		$key = $this->getCacheKey();
		if ( !$key ) {
			return;
		}

		$this->repo->getMasterDB()->onTransactionPreCommitOrIdle(
			function () use ( $key ) {
				ObjectCache::getMainWANInstance()->delete( $key );
			},
			__METHOD__
		);
	}

	/**
	 * Load metadata from the file itself
	 */
	function loadFromFile() {
		$props = $this->repo->getFileProps( $this->getVirtualUrl() );
		$this->setProps( $props );
	}

	/**
	 * @param string $prefix
	 * @return array
	 */
	function getCacheFields( $prefix = 'img_' ) {
		static $fields = [ 'size', 'width', 'height', 'bits', 'media_type',
			'major_mime', 'minor_mime', 'metadata', 'timestamp', 'sha1', 'user',
			'user_text', 'description' ];
		static $results = [];

		if ( $prefix == '' ) {
			return $fields;
		}

		if ( !isset( $results[$prefix] ) ) {
			$prefixedFields = [];
			foreach ( $fields as $field ) {
				$prefixedFields[] = $prefix . $field;
			}
			$results[$prefix] = $prefixedFields;
		}

		return $results[$prefix];
	}

	/**
	 * @param string $prefix
	 * @return array
	 */
	function getLazyCacheFields( $prefix = 'img_' ) {
		static $fields = [ 'metadata' ];
		static $results = [];

		if ( $prefix == '' ) {
			return $fields;
		}

		if ( !isset( $results[$prefix] ) ) {
			$prefixedFields = [];
			foreach ( $fields as $field ) {
				$prefixedFields[] = $prefix . $field;
			}
			$results[$prefix] = $prefixedFields;
		}

		return $results[$prefix];
	}

	/**
	 * Load file metadata from the DB
	 * @param int $flags
	 */
	function loadFromDB( $flags = 0 ) {
		$fname = get_class( $this ) . '::' . __FUNCTION__;

		# Unconditionally set loaded=true, we don't want the accessors constantly rechecking
		$this->dataLoaded = true;
		$this->extraDataLoaded = true;

		$dbr = ( $flags & self::READ_LATEST )
			? $this->repo->getMasterDB()
			: $this->repo->getSlaveDB();

		$row = $dbr->selectRow( 'image', $this->getCacheFields( 'img_' ),
			[ 'img_name' => $this->getName() ], $fname );

		if ( $row ) {
			$this->loadFromRow( $row );
		} else {
			$this->fileExists = false;
		}
	}

	/**
	 * Load lazy file metadata from the DB.
	 * This covers fields that are sometimes not cached.
	 */
	protected function loadExtraFromDB() {
		$fname = get_class( $this ) . '::' . __FUNCTION__;

		# Unconditionally set loaded=true, we don't want the accessors constantly rechecking
		$this->extraDataLoaded = true;

		$fieldMap = $this->loadFieldsWithTimestamp( $this->repo->getSlaveDB(), $fname );
		if ( !$fieldMap ) {
			$fieldMap = $this->loadFieldsWithTimestamp( $this->repo->getMasterDB(), $fname );
		}

		if ( $fieldMap ) {
			foreach ( $fieldMap as $name => $value ) {
				$this->$name = $value;
			}
		} else {
			throw new MWException( "Could not find data for image '{$this->getName()}'." );
		}
	}

	/**
	 * @param IDatabase $dbr
	 * @param string $fname
	 * @return array|bool
	 */
	private function loadFieldsWithTimestamp( $dbr, $fname ) {
		$fieldMap = false;

		$row = $dbr->selectRow( 'image', $this->getLazyCacheFields( 'img_' ), [
				'img_name' => $this->getName(),
				'img_timestamp' => $dbr->timestamp( $this->getTimestamp() )
			], $fname );
		if ( $row ) {
			$fieldMap = $this->unprefixRow( $row, 'img_' );
		} else {
			# File may have been uploaded over in the meantime; check the old versions
			$row = $dbr->selectRow( 'oldimage', $this->getLazyCacheFields( 'oi_' ), [
					'oi_name' => $this->getName(),
					'oi_timestamp' => $dbr->timestamp( $this->getTimestamp() )
				], $fname );
			if ( $row ) {
				$fieldMap = $this->unprefixRow( $row, 'oi_' );
			}
		}

		return $fieldMap;
	}

	/**
	 * @param array|object $row
	 * @param string $prefix
	 * @throws MWException
	 * @return array
	 */
	protected function unprefixRow( $row, $prefix = 'img_' ) {
		$array = (array)$row;
		$prefixLength = strlen( $prefix );

		// Sanity check prefix once
		if ( substr( key( $array ), 0, $prefixLength ) !== $prefix ) {
			throw new MWException( __METHOD__ . ': incorrect $prefix parameter' );
		}

		$decoded = [];
		foreach ( $array as $name => $value ) {
			$decoded[substr( $name, $prefixLength )] = $value;
		}

		return $decoded;
	}

	/**
	 * Decode a row from the database (either object or array) to an array
	 * with timestamps and MIME types decoded, and the field prefix removed.
	 * @param object $row
	 * @param string $prefix
	 * @throws MWException
	 * @return array
	 */
	function decodeRow( $row, $prefix = 'img_' ) {
		$decoded = $this->unprefixRow( $row, $prefix );

		$decoded['timestamp'] = wfTimestamp( TS_MW, $decoded['timestamp'] );

		$decoded['metadata'] = $this->repo->getSlaveDB()->decodeBlob( $decoded['metadata'] );

		if ( empty( $decoded['major_mime'] ) ) {
			$decoded['mime'] = 'unknown/unknown';
		} else {
			if ( !$decoded['minor_mime'] ) {
				$decoded['minor_mime'] = 'unknown';
			}
			$decoded['mime'] = $decoded['major_mime'] . '/' . $decoded['minor_mime'];
		}

		// Trim zero padding from char/binary field
		$decoded['sha1'] = rtrim( $decoded['sha1'], "\0" );

		// Normalize some fields to integer type, per their database definition.
		// Use unary + so that overflows will be upgraded to double instead of
		// being trucated as with intval(). This is important to allow >2GB
		// files on 32-bit systems.
		foreach ( [ 'size', 'width', 'height', 'bits' ] as $field ) {
			$decoded[$field] = +$decoded[$field];
		}

		return $decoded;
	}

	/**
	 * Load file metadata from a DB result row
	 *
	 * @param object $row
	 * @param string $prefix
	 */
	function loadFromRow( $row, $prefix = 'img_' ) {
		$this->dataLoaded = true;
		$this->extraDataLoaded = true;

		$array = $this->decodeRow( $row, $prefix );

		foreach ( $array as $name => $value ) {
			$this->$name = $value;
		}

		$this->fileExists = true;
		$this->maybeUpgradeRow();
	}

	/**
	 * Load file metadata from cache or DB, unless already loaded
	 * @param int $flags
	 */
	function load( $flags = 0 ) {
		if ( !$this->dataLoaded ) {
			if ( $flags & self::READ_LATEST ) {
				$this->loadFromDB( $flags );
			} else {
				$this->loadFromCache();
			}
		}

		if ( ( $flags & self::LOAD_ALL ) && !$this->extraDataLoaded ) {
			// @note: loads on name/timestamp to reduce race condition problems
			$this->loadExtraFromDB();
		}
	}

	/**
	 * Upgrade a row if it needs it
	 */
	function maybeUpgradeRow() {
		global $wgUpdateCompatibleMetadata;

		if ( wfReadOnly() || $this->upgrading ) {
			return;
		}

		$upgrade = false;
		if ( is_null( $this->media_type ) || $this->mime == 'image/svg' ) {
			$upgrade = true;
		} else {
			$handler = $this->getHandler();
			if ( $handler ) {
				$validity = $handler->isMetadataValid( $this, $this->getMetadata() );
				if ( $validity === MediaHandler::METADATA_BAD ) {
					$upgrade = true;
				} elseif ( $validity === MediaHandler::METADATA_COMPATIBLE ) {
					$upgrade = $wgUpdateCompatibleMetadata;
				}
			}
		}

		if ( $upgrade ) {
			$this->upgrading = true;
			// Defer updates unless in auto-commit CLI mode
			DeferredUpdates::addCallableUpdate( function() {
				$this->upgrading = false; // avoid duplicate updates
				try {
					$this->upgradeRow();
				} catch ( LocalFileLockError $e ) {
					// let the other process handle it (or do it next time)
				}
			} );
		}
	}

	/**
	 * @return bool Whether upgradeRow() ran for this object
	 */
	function getUpgraded() {
		return $this->upgraded;
	}

	/**
	 * Fix assorted version-related problems with the image row by reloading it from the file
	 */
	function upgradeRow() {
		$this->lock(); // begin

		$this->loadFromFile();

		# Don't destroy file info of missing files
		if ( !$this->fileExists ) {
			$this->unlock();
			wfDebug( __METHOD__ . ": file does not exist, aborting\n" );

			return;
		}

		$dbw = $this->repo->getMasterDB();
		list( $major, $minor ) = self::splitMime( $this->mime );

		if ( wfReadOnly() ) {
			$this->unlock();

			return;
		}
		wfDebug( __METHOD__ . ': upgrading ' . $this->getName() . " to the current schema\n" );

		$dbw->update( 'image',
			[
				'img_size' => $this->size, // sanity
				'img_width' => $this->width,
				'img_height' => $this->height,
				'img_bits' => $this->bits,
				'img_media_type' => $this->media_type,
				'img_major_mime' => $major,
				'img_minor_mime' => $minor,
				'img_metadata' => $dbw->encodeBlob( $this->metadata ),
				'img_sha1' => $this->sha1,
			],
			[ 'img_name' => $this->getName() ],
			__METHOD__
		);

		$this->invalidateCache();

		$this->unlock(); // done
		$this->upgraded = true; // avoid rework/retries
	}

	/**
	 * Set properties in this object to be equal to those given in the
	 * associative array $info. Only cacheable fields can be set.
	 * All fields *must* be set in $info except for getLazyCacheFields().
	 *
	 * If 'mime' is given, it will be split into major_mime/minor_mime.
	 * If major_mime/minor_mime are given, $this->mime will also be set.
	 *
	 * @param array $info
	 */
	function setProps( $info ) {
		$this->dataLoaded = true;
		$fields = $this->getCacheFields( '' );
		$fields[] = 'fileExists';

		foreach ( $fields as $field ) {
			if ( isset( $info[$field] ) ) {
				$this->$field = $info[$field];
			}
		}

		// Fix up mime fields
		if ( isset( $info['major_mime'] ) ) {
			$this->mime = "{$info['major_mime']}/{$info['minor_mime']}";
		} elseif ( isset( $info['mime'] ) ) {
			$this->mime = $info['mime'];
			list( $this->major_mime, $this->minor_mime ) = self::splitMime( $this->mime );
		}
	}

	/** splitMime inherited */
	/** getName inherited */
	/** getTitle inherited */
	/** getURL inherited */
	/** getViewURL inherited */
	/** getPath inherited */
	/** isVisible inherited */

	/**
	 * @return bool
	 */
	function isMissing() {
		if ( $this->missing === null ) {
			list( $fileExists ) = $this->repo->fileExists( $this->getVirtualUrl() );
			$this->missing = !$fileExists;
		}

		return $this->missing;
	}

	/**
	 * Return the width of the image
	 *
	 * @param int $page
	 * @return int
	 */
	public function getWidth( $page = 1 ) {
		$this->load();

		if ( $this->isMultipage() ) {
			$handler = $this->getHandler();
			if ( !$handler ) {
				return 0;
			}
			$dim = $handler->getPageDimensions( $this, $page );
			if ( $dim ) {
				return $dim['width'];
			} else {
				// For non-paged media, the false goes through an
				// intval, turning failure into 0, so do same here.
				return 0;
			}
		} else {
			return $this->width;
		}
	}

	/**
	 * Return the height of the image
	 *
	 * @param int $page
	 * @return int
	 */
	public function getHeight( $page = 1 ) {
		$this->load();

		if ( $this->isMultipage() ) {
			$handler = $this->getHandler();
			if ( !$handler ) {
				return 0;
			}
			$dim = $handler->getPageDimensions( $this, $page );
			if ( $dim ) {
				return $dim['height'];
			} else {
				// For non-paged media, the false goes through an
				// intval, turning failure into 0, so do same here.
				return 0;
			}
		} else {
			return $this->height;
		}
	}

	/**
	 * Returns ID or name of user who uploaded the file
	 *
	 * @param string $type 'text' or 'id'
	 * @return int|string
	 */
	function getUser( $type = 'text' ) {
		$this->load();

		if ( $type == 'text' ) {
			return $this->user_text;
		} else { // id
			return (int)$this->user;
		}
	}

	/**
	 * Get short description URL for a file based on the page ID.
	 *
	 * @return string|null
	 * @throws MWException
	 * @since 1.27
	 */
	public function getDescriptionShortUrl() {
		$pageId = $this->title->getArticleID();

		if ( $pageId !== null ) {
			$url = $this->repo->makeUrl( [ 'curid' => $pageId ] );
			if ( $url !== false ) {
				return $url;
			}
		}
		return null;
	}

	/**
	 * Get handler-specific metadata
	 * @return string
	 */
	function getMetadata() {
		$this->load( self::LOAD_ALL ); // large metadata is loaded in another step
		return $this->metadata;
	}

	/**
	 * @return int
	 */
	function getBitDepth() {
		$this->load();

		return (int)$this->bits;
	}

	/**
	 * Returns the size of the image file, in bytes
	 * @return int
	 */
	public function getSize() {
		$this->load();

		return $this->size;
	}

	/**
	 * Returns the MIME type of the file.
	 * @return string
	 */
	function getMimeType() {
		$this->load();

		return $this->mime;
	}

	/**
	 * Returns the type of the media in the file.
	 * Use the value returned by this function with the MEDIATYPE_xxx constants.
	 * @return string
	 */
	function getMediaType() {
		$this->load();

		return $this->media_type;
	}

	/** canRender inherited */
	/** mustRender inherited */
	/** allowInlineDisplay inherited */
	/** isSafeFile inherited */
	/** isTrustedFile inherited */

	/**
	 * Returns true if the file exists on disk.
	 * @return bool Whether file exist on disk.
	 */
	public function exists() {
		$this->load();

		return $this->fileExists;
	}

	/** getTransformScript inherited */
	/** getUnscaledThumb inherited */
	/** thumbName inherited */
	/** createThumb inherited */
	/** transform inherited */

	/** getHandler inherited */
	/** iconThumb inherited */
	/** getLastError inherited */

	/**
	 * Get all thumbnail names previously generated for this file
	 * @param string|bool $archiveName Name of an archive file, default false
	 * @return array First element is the base dir, then files in that base dir.
	 */
	function getThumbnails( $archiveName = false ) {
		if ( $archiveName ) {
			$dir = $this->getArchiveThumbPath( $archiveName );
		} else {
			$dir = $this->getThumbPath();
		}

		$backend = $this->repo->getBackend();
		$files = [ $dir ];
		try {
			$iterator = $backend->getFileList( [ 'dir' => $dir ] );
			foreach ( $iterator as $file ) {
				$files[] = $file;
			}
		} catch ( FileBackendError $e ) {
		} // suppress (bug 54674)

		return $files;
	}

	/**
	 * Refresh metadata in memcached, but don't touch thumbnails or CDN
	 */
	function purgeMetadataCache() {
		$this->invalidateCache();
	}

	/**
	 * Delete all previously generated thumbnails, refresh metadata in memcached and purge the CDN.
	 *
	 * @param array $options An array potentially with the key forThumbRefresh.
	 *
	 * @note This used to purge old thumbnails by default as well, but doesn't anymore.
	 */
	function purgeCache( $options = [] ) {
		// Refresh metadata cache
		$this->purgeMetadataCache();

		// Delete thumbnails
		$this->purgeThumbnails( $options );

		// Purge CDN cache for this file
		DeferredUpdates::addUpdate(
			new CdnCacheUpdate( [ $this->getUrl() ] ),
			DeferredUpdates::PRESEND
		);
	}

	/**
	 * Delete cached transformed files for an archived version only.
	 * @param string $archiveName Name of the archived file
	 */
	function purgeOldThumbnails( $archiveName ) {
		// Get a list of old thumbnails and URLs
		$files = $this->getThumbnails( $archiveName );

		// Purge any custom thumbnail caches
		Hooks::run( 'LocalFilePurgeThumbnails', [ $this, $archiveName ] );

		// Delete thumbnails
		$dir = array_shift( $files );
		$this->purgeThumbList( $dir, $files );

		// Purge the CDN
		$urls = [];
		foreach ( $files as $file ) {
			$urls[] = $this->getArchiveThumbUrl( $archiveName, $file );
		}
		DeferredUpdates::addUpdate( new CdnCacheUpdate( $urls ), DeferredUpdates::PRESEND );
	}

	/**
	 * Delete cached transformed files for the current version only.
	 * @param array $options
	 */
	public function purgeThumbnails( $options = [] ) {
		$files = $this->getThumbnails();
		// Always purge all files from CDN regardless of handler filters
		$urls = [];
		foreach ( $files as $file ) {
			$urls[] = $this->getThumbUrl( $file );
		}
		array_shift( $urls ); // don't purge directory

		// Give media handler a chance to filter the file purge list
		if ( !empty( $options['forThumbRefresh'] ) ) {
			$handler = $this->getHandler();
			if ( $handler ) {
				$handler->filterThumbnailPurgeList( $files, $options );
			}
		}

		// Purge any custom thumbnail caches
		Hooks::run( 'LocalFilePurgeThumbnails', [ $this, false ] );

		// Delete thumbnails
		$dir = array_shift( $files );
		$this->purgeThumbList( $dir, $files );

		// Purge the CDN
		DeferredUpdates::addUpdate( new CdnCacheUpdate( $urls ), DeferredUpdates::PRESEND );
	}

	/**
	 * Prerenders a configurable set of thumbnails
	 *
	 * @since 1.28
	 */
	public function prerenderThumbnails() {
		global $wgUploadThumbnailRenderMap;

		$jobs = [];

		$sizes = $wgUploadThumbnailRenderMap;
		rsort( $sizes );

		foreach ( $sizes as $size ) {
			if ( $this->isVectorized() || $this->getWidth() > $size ) {
				$jobs[] = new ThumbnailRenderJob(
					$this->getTitle(),
					[ 'transformParams' => [ 'width' => $size ] ]
				);
			}
		}

		if ( $jobs ) {
			JobQueueGroup::singleton()->lazyPush( $jobs );
		}
	}

	/**
	 * Delete a list of thumbnails visible at urls
	 * @param string $dir Base dir of the files.
	 * @param array $files Array of strings: relative filenames (to $dir)
	 */
	protected function purgeThumbList( $dir, $files ) {
		$fileListDebug = strtr(
			var_export( $files, true ),
			[ "\n" => '' ]
		);
		wfDebug( __METHOD__ . ": $fileListDebug\n" );

		$purgeList = [];
		foreach ( $files as $file ) {
			# Check that the base file name is part of the thumb name
			# This is a basic sanity check to avoid erasing unrelated directories
			if ( strpos( $file, $this->getName() ) !== false
				|| strpos( $file, "-thumbnail" ) !== false // "short" thumb name
			) {
				$purgeList[] = "{$dir}/{$file}";
			}
		}

		# Delete the thumbnails
		$this->repo->quickPurgeBatch( $purgeList );
		# Clear out the thumbnail directory if empty
		$this->repo->quickCleanDir( $dir );
	}

	/** purgeDescription inherited */
	/** purgeEverything inherited */

	/**
	 * @param int $limit Optional: Limit to number of results
	 * @param int $start Optional: Timestamp, start from
	 * @param int $end Optional: Timestamp, end at
	 * @param bool $inc
	 * @return OldLocalFile[]
	 */
	function getHistory( $limit = null, $start = null, $end = null, $inc = true ) {
		$dbr = $this->repo->getSlaveDB();
		$tables = [ 'oldimage' ];
		$fields = OldLocalFile::selectFields();
		$conds = $opts = $join_conds = [];
		$eq = $inc ? '=' : '';
		$conds[] = "oi_name = " . $dbr->addQuotes( $this->title->getDBkey() );

		if ( $start ) {
			$conds[] = "oi_timestamp <$eq " . $dbr->addQuotes( $dbr->timestamp( $start ) );
		}

		if ( $end ) {
			$conds[] = "oi_timestamp >$eq " . $dbr->addQuotes( $dbr->timestamp( $end ) );
		}

		if ( $limit ) {
			$opts['LIMIT'] = $limit;
		}

		// Search backwards for time > x queries
		$order = ( !$start && $end !== null ) ? 'ASC' : 'DESC';
		$opts['ORDER BY'] = "oi_timestamp $order";
		$opts['USE INDEX'] = [ 'oldimage' => 'oi_name_timestamp' ];

		Hooks::run( 'LocalFile::getHistory', [ &$this, &$tables, &$fields,
			&$conds, &$opts, &$join_conds ] );

		$res = $dbr->select( $tables, $fields, $conds, __METHOD__, $opts, $join_conds );
		$r = [];

		foreach ( $res as $row ) {
			$r[] = $this->repo->newFileFromRow( $row );
		}

		if ( $order == 'ASC' ) {
			$r = array_reverse( $r ); // make sure it ends up descending
		}

		return $r;
	}

	/**
	 * Returns the history of this file, line by line.
	 * starts with current version, then old versions.
	 * uses $this->historyLine to check which line to return:
	 *  0      return line for current version
	 *  1      query for old versions, return first one
	 *  2, ... return next old version from above query
	 * @return bool
	 */
	public function nextHistoryLine() {
		# Polymorphic function name to distinguish foreign and local fetches
		$fname = get_class( $this ) . '::' . __FUNCTION__;

		$dbr = $this->repo->getSlaveDB();

		if ( $this->historyLine == 0 ) { // called for the first time, return line from cur
			$this->historyRes = $dbr->select( 'image',
				[
					'*',
					"'' AS oi_archive_name",
					'0 as oi_deleted',
					'img_sha1'
				],
				[ 'img_name' => $this->title->getDBkey() ],
				$fname
			);

			if ( 0 == $dbr->numRows( $this->historyRes ) ) {
				$this->historyRes = null;

				return false;
			}
		} elseif ( $this->historyLine == 1 ) {
			$this->historyRes = $dbr->select( 'oldimage', '*',
				[ 'oi_name' => $this->title->getDBkey() ],
				$fname,
				[ 'ORDER BY' => 'oi_timestamp DESC' ]
			);
		}
		$this->historyLine++;

		return $dbr->fetchObject( $this->historyRes );
	}

	/**
	 * Reset the history pointer to the first element of the history
	 */
	public function resetHistory() {
		$this->historyLine = 0;

		if ( !is_null( $this->historyRes ) ) {
			$this->historyRes = null;
		}
	}

	/** getHashPath inherited */
	/** getRel inherited */
	/** getUrlRel inherited */
	/** getArchiveRel inherited */
	/** getArchivePath inherited */
	/** getThumbPath inherited */
	/** getArchiveUrl inherited */
	/** getThumbUrl inherited */
	/** getArchiveVirtualUrl inherited */
	/** getThumbVirtualUrl inherited */
	/** isHashed inherited */

	/**
	 * Upload a file and record it in the DB
	 * @param string|FSFile $src Source storage path, virtual URL, or filesystem path
	 * @param string $comment Upload description
	 * @param string $pageText Text to use for the new description page,
	 *   if a new description page is created
	 * @param int|bool $flags Flags for publish()
	 * @param array|bool $props File properties, if known. This can be used to
	 *   reduce the upload time when uploading virtual URLs for which the file
	 *   info is already known
	 * @param string|bool $timestamp Timestamp for img_timestamp, or false to use the
	 *   current time
	 * @param User|null $user User object or null to use $wgUser
	 * @param string[] $tags Change tags to add to the log entry and page revision.
	 *   (This doesn't check $user's permissions.)
	 * @return Status On success, the value member contains the
	 *     archive name, or an empty string if it was a new file.
	 */
	function upload( $src, $comment, $pageText, $flags = 0, $props = false,
		$timestamp = false, $user = null, $tags = []
	) {
		global $wgContLang;

		if ( $this->getRepo()->getReadOnlyReason() !== false ) {
			return $this->readOnlyFatalStatus();
		}

		$srcPath = ( $src instanceof FSFile ) ? $src->getPath() : $src;
		if ( !$props ) {
			if ( $this->repo->isVirtualUrl( $srcPath )
				|| FileBackend::isStoragePath( $srcPath )
			) {
				$props = $this->repo->getFileProps( $srcPath );
			} else {
				$mwProps = new MWFileProps( MimeMagic::singleton() );
				$props = $mwProps->getPropsFromPath( $srcPath, true );
			}
		}

		$options = [];
		$handler = MediaHandler::getHandler( $props['mime'] );
		if ( $handler ) {
			$options['headers'] = $handler->getStreamHeaders( $props['metadata'] );
		} else {
			$options['headers'] = [];
		}

		// Trim spaces on user supplied text
		$comment = trim( $comment );

		// Truncate nicely or the DB will do it for us
		// non-nicely (dangling multi-byte chars, non-truncated version in cache).
		$comment = $wgContLang->truncate( $comment, 255 );
		$this->lock(); // begin
		$status = $this->publish( $src, $flags, $options );

		if ( $status->successCount >= 2 ) {
			// There will be a copy+(one of move,copy,store).
			// The first succeeding does not commit us to updating the DB
			// since it simply copied the current version to a timestamped file name.
			// It is only *preferable* to avoid leaving such files orphaned.
			// Once the second operation goes through, then the current version was
			// updated and we must therefore update the DB too.
			$oldver = $status->value;
			if ( !$this->recordUpload2( $oldver, $comment, $pageText, $props, $timestamp, $user, $tags ) ) {
				$status->fatal( 'filenotfound', $srcPath );
			}
		}

		$this->unlock(); // done

		return $status;
	}

	/**
	 * Record a file upload in the upload log and the image table
	 * @param string $oldver
	 * @param string $desc
	 * @param string $license
	 * @param string $copyStatus
	 * @param string $source
	 * @param bool $watch
	 * @param string|bool $timestamp
	 * @param User|null $user User object or null to use $wgUser
	 * @return bool
	 */
	function recordUpload( $oldver, $desc, $license = '', $copyStatus = '', $source = '',
		$watch = false, $timestamp = false, User $user = null ) {
		if ( !$user ) {
			global $wgUser;
			$user = $wgUser;
		}

		$pageText = SpecialUpload::getInitialPageText( $desc, $license, $copyStatus, $source );

		if ( !$this->recordUpload2( $oldver, $desc, $pageText, false, $timestamp, $user ) ) {
			return false;
		}

		if ( $watch ) {
			$user->addWatch( $this->getTitle() );
		}

		return true;
	}

	/**
	 * Record a file upload in the upload log and the image table
	 * @param string $oldver
	 * @param string $comment
	 * @param string $pageText
	 * @param bool|array $props
	 * @param string|bool $timestamp
	 * @param null|User $user
	 * @param string[] $tags
	 * @return bool
	 */
	function recordUpload2(
		$oldver, $comment, $pageText, $props = false, $timestamp = false, $user = null, $tags = []
	) {
		if ( is_null( $user ) ) {
			global $wgUser;
			$user = $wgUser;
		}

		$dbw = $this->repo->getMasterDB();

		# Imports or such might force a certain timestamp; otherwise we generate
		# it and can fudge it slightly to keep (name,timestamp) unique on re-upload.
		if ( $timestamp === false ) {
			$timestamp = $dbw->timestamp();
			$allowTimeKludge = true;
		} else {
			$allowTimeKludge = false;
		}

		$props = $props ?: $this->repo->getFileProps( $this->getVirtualUrl() );
		$props['description'] = $comment;
		$props['user'] = $user->getId();
		$props['user_text'] = $user->getName();
		$props['timestamp'] = wfTimestamp( TS_MW, $timestamp ); // DB -> TS_MW
		$this->setProps( $props );

		# Fail now if the file isn't there
		if ( !$this->fileExists ) {
			wfDebug( __METHOD__ . ": File " . $this->getRel() . " went missing!\n" );

			return false;
		}

		$dbw->startAtomic( __METHOD__ );

		# Test to see if the row exists using INSERT IGNORE
		# This avoids race conditions by locking the row until the commit, and also
		# doesn't deadlock. SELECT FOR UPDATE causes a deadlock for every race condition.
		$dbw->insert( 'image',
			[
				'img_name' => $this->getName(),
				'img_size' => $this->size,
				'img_width' => intval( $this->width ),
				'img_height' => intval( $this->height ),
				'img_bits' => $this->bits,
				'img_media_type' => $this->media_type,
				'img_major_mime' => $this->major_mime,
				'img_minor_mime' => $this->minor_mime,
				'img_timestamp' => $timestamp,
				'img_description' => $comment,
				'img_user' => $user->getId(),
				'img_user_text' => $user->getName(),
				'img_metadata' => $dbw->encodeBlob( $this->metadata ),
				'img_sha1' => $this->sha1
			],
			__METHOD__,
			'IGNORE'
		);

		$reupload = ( $dbw->affectedRows() == 0 );
		if ( $reupload ) {
			if ( $allowTimeKludge ) {
				# Use LOCK IN SHARE MODE to ignore any transaction snapshotting
				$ltimestamp = $dbw->selectField(
					'image',
					'img_timestamp',
					[ 'img_name' => $this->getName() ],
					__METHOD__,
					[ 'LOCK IN SHARE MODE' ]
				);
				$lUnixtime = $ltimestamp ? wfTimestamp( TS_UNIX, $ltimestamp ) : false;
				# Avoid a timestamp that is not newer than the last version
				# TODO: the image/oldimage tables should be like page/revision with an ID field
				if ( $lUnixtime && wfTimestamp( TS_UNIX, $timestamp ) <= $lUnixtime ) {
					sleep( 1 ); // fast enough re-uploads would go far in the future otherwise
					$timestamp = $dbw->timestamp( $lUnixtime + 1 );
					$this->timestamp = wfTimestamp( TS_MW, $timestamp ); // DB -> TS_MW
				}
			}

			# (bug 34993) Note: $oldver can be empty here, if the previous
			# version of the file was broken. Allow registration of the new
			# version to continue anyway, because that's better than having
			# an image that's not fixable by user operations.
			# Collision, this is an update of a file
			# Insert previous contents into oldimage
			$dbw->insertSelect( 'oldimage', 'image',
				[
					'oi_name' => 'img_name',
					'oi_archive_name' => $dbw->addQuotes( $oldver ),
					'oi_size' => 'img_size',
					'oi_width' => 'img_width',
					'oi_height' => 'img_height',
					'oi_bits' => 'img_bits',
					'oi_timestamp' => 'img_timestamp',
					'oi_description' => 'img_description',
					'oi_user' => 'img_user',
					'oi_user_text' => 'img_user_text',
					'oi_metadata' => 'img_metadata',
					'oi_media_type' => 'img_media_type',
					'oi_major_mime' => 'img_major_mime',
					'oi_minor_mime' => 'img_minor_mime',
					'oi_sha1' => 'img_sha1'
				],
				[ 'img_name' => $this->getName() ],
				__METHOD__
			);

			# Update the current image row
			$dbw->update( 'image',
				[
					'img_size' => $this->size,
					'img_width' => intval( $this->width ),
					'img_height' => intval( $this->height ),
					'img_bits' => $this->bits,
					'img_media_type' => $this->media_type,
					'img_major_mime' => $this->major_mime,
					'img_minor_mime' => $this->minor_mime,
					'img_timestamp' => $timestamp,
					'img_description' => $comment,
					'img_user' => $user->getId(),
					'img_user_text' => $user->getName(),
					'img_metadata' => $dbw->encodeBlob( $this->metadata ),
					'img_sha1' => $this->sha1
				],
				[ 'img_name' => $this->getName() ],
				__METHOD__
			);
		}

		$descTitle = $this->getTitle();
		$descId = $descTitle->getArticleID();
		$wikiPage = new WikiFilePage( $descTitle );
		$wikiPage->setFile( $this );

		// Add the log entry...
		$logEntry = new ManualLogEntry( 'upload', $reupload ? 'overwrite' : 'upload' );
		$logEntry->setTimestamp( $this->timestamp );
		$logEntry->setPerformer( $user );
		$logEntry->setComment( $comment );
		$logEntry->setTarget( $descTitle );
		// Allow people using the api to associate log entries with the upload.
		// Log has a timestamp, but sometimes different from upload timestamp.
		$logEntry->setParameters(
			[
				'img_sha1' => $this->sha1,
				'img_timestamp' => $timestamp,
			]
		);
		// Note we keep $logId around since during new image
		// creation, page doesn't exist yet, so log_page = 0
		// but we want it to point to the page we're making,
		// so we later modify the log entry.
		// For a similar reason, we avoid making an RC entry
		// now and wait until the page exists.
		$logId = $logEntry->insert();

		if ( $descTitle->exists() ) {
			// Use own context to get the action text in content language
			$formatter = LogFormatter::newFromEntry( $logEntry );
			$formatter->setContext( RequestContext::newExtraneousContext( $descTitle ) );
			$editSummary = $formatter->getPlainActionText();

			$nullRevision = Revision::newNullRevision(
				$dbw,
				$descId,
				$editSummary,
				false,
				$user
			);
			if ( $nullRevision ) {
				$nullRevision->insertOn( $dbw );
				Hooks::run(
					'NewRevisionFromEditComplete',
					[ $wikiPage, $nullRevision, $nullRevision->getParentId(), $user ]
				);
				$wikiPage->updateRevisionOn( $dbw, $nullRevision );
				// Associate null revision id
				$logEntry->setAssociatedRevId( $nullRevision->getId() );
			}

			$newPageContent = null;
		} else {
			// Make the description page and RC log entry post-commit
			$newPageContent = ContentHandler::makeContent( $pageText, $descTitle );
		}

		# Defer purges, page creation, and link updates in case they error out.
		# The most important thing is that files and the DB registry stay synced.
		$dbw->endAtomic( __METHOD__ );

		# Do some cache purges after final commit so that:
		# a) Changes are more likely to be seen post-purge
		# b) They won't cause rollback of the log publish/update above
		DeferredUpdates::addUpdate(
			new AutoCommitUpdate(
				$dbw,
				__METHOD__,
				function () use (
					$reupload, $wikiPage, $newPageContent, $comment, $user,
					$logEntry, $logId, $descId, $tags
				) {
					# Update memcache after the commit
					$this->invalidateCache();

					$updateLogPage = false;
					if ( $newPageContent ) {
						# New file page; create the description page.
						# There's already a log entry, so don't make a second RC entry
						# CDN and file cache for the description page are purged by doEditContent.
						$status = $wikiPage->doEditContent(
							$newPageContent,
							$comment,
							EDIT_NEW | EDIT_SUPPRESS_RC,
							false,
							$user
						);

						if ( isset( $status->value['revision'] ) ) {
							/** @var $rev Revision */
							$rev = $status->value['revision'];
							// Associate new page revision id
							$logEntry->setAssociatedRevId( $rev->getId() );
						}
						// This relies on the resetArticleID() call in WikiPage::insertOn(),
						// which is triggered on $descTitle by doEditContent() above.
						if ( isset( $status->value['revision'] ) ) {
							/** @var $rev Revision */
							$rev = $status->value['revision'];
							$updateLogPage = $rev->getPage();
						}
					} else {
						# Existing file page: invalidate description page cache
						$wikiPage->getTitle()->invalidateCache();
						$wikiPage->getTitle()->purgeSquid();
						# Allow the new file version to be patrolled from the page footer
						Article::purgePatrolFooterCache( $descId );
					}

					# Update associated rev id. This should be done by $logEntry->insert() earlier,
					# but setAssociatedRevId() wasn't called at that point yet...
					$logParams = $logEntry->getParameters();
					$logParams['associated_rev_id'] = $logEntry->getAssociatedRevId();
					$update = [ 'log_params' => LogEntryBase::makeParamBlob( $logParams ) ];
					if ( $updateLogPage ) {
						# Also log page, in case where we just created it above
						$update['log_page'] = $updateLogPage;
					}
					$this->getRepo()->getMasterDB()->update(
						'logging',
						$update,
						[ 'log_id' => $logId ],
						__METHOD__
					);
					$this->getRepo()->getMasterDB()->insert(
						'log_search',
						[
							'ls_field' => 'associated_rev_id',
							'ls_value' => $logEntry->getAssociatedRevId(),
							'ls_log_id' => $logId,
						],
						__METHOD__
					);

					# Add change tags, if any
					if ( $tags ) {
						$logEntry->setTags( $tags );
					}

					# Uploads can be patrolled
					$logEntry->setIsPatrollable( true );

					# Now that the log entry is up-to-date, make an RC entry.
					$logEntry->publish( $logId );

					# Run hook for other updates (typically more cache purging)
					Hooks::run( 'FileUpload', [ $this, $reupload, !$newPageContent ] );

					if ( $reupload ) {
						# Delete old thumbnails
						$this->purgeThumbnails();
						# Remove the old file from the CDN cache
						DeferredUpdates::addUpdate(
							new CdnCacheUpdate( [ $this->getUrl() ] ),
							DeferredUpdates::PRESEND
						);
					} else {
						# Update backlink pages pointing to this title if created
						LinksUpdate::queueRecursiveJobsForTable( $this->getTitle(), 'imagelinks' );
					}

					$this->prerenderThumbnails();
				}
			),
			DeferredUpdates::PRESEND
		);

		if ( !$reupload ) {
			# This is a new file, so update the image count
			DeferredUpdates::addUpdate( SiteStatsUpdate::factory( [ 'images' => 1 ] ) );
		}

		# Invalidate cache for all pages using this file
		DeferredUpdates::addUpdate( new HTMLCacheUpdate( $this->getTitle(), 'imagelinks' ) );

		return true;
	}

	/**
	 * Move or copy a file to its public location. If a file exists at the
	 * destination, move it to an archive. Returns a FileRepoStatus object with
	 * the archive name in the "value" member on success.
	 *
	 * The archive name should be passed through to recordUpload for database
	 * registration.
	 *
	 * @param string|FSFile $src Local filesystem path or virtual URL to the source image
	 * @param int $flags A bitwise combination of:
	 *     File::DELETE_SOURCE    Delete the source file, i.e. move rather than copy
	 * @param array $options Optional additional parameters
	 * @return Status On success, the value member contains the
	 *     archive name, or an empty string if it was a new file.
	 */
	function publish( $src, $flags = 0, array $options = [] ) {
		return $this->publishTo( $src, $this->getRel(), $flags, $options );
	}

	/**
	 * Move or copy a file to a specified location. Returns a FileRepoStatus
	 * object with the archive name in the "value" member on success.
	 *
	 * The archive name should be passed through to recordUpload for database
	 * registration.
	 *
	 * @param string|FSFile $src Local filesystem path or virtual URL to the source image
	 * @param string $dstRel Target relative path
	 * @param int $flags A bitwise combination of:
	 *     File::DELETE_SOURCE    Delete the source file, i.e. move rather than copy
	 * @param array $options Optional additional parameters
	 * @return Status On success, the value member contains the
	 *     archive name, or an empty string if it was a new file.
	 */
	function publishTo( $src, $dstRel, $flags = 0, array $options = [] ) {
		$srcPath = ( $src instanceof FSFile ) ? $src->getPath() : $src;

		$repo = $this->getRepo();
		if ( $repo->getReadOnlyReason() !== false ) {
			return $this->readOnlyFatalStatus();
		}

		$this->lock(); // begin

		$archiveName = wfTimestamp( TS_MW ) . '!' . $this->getName();
		$archiveRel = 'archive/' . $this->getHashPath() . $archiveName;

		if ( $repo->hasSha1Storage() ) {
			$sha1 = $repo->isVirtualUrl( $srcPath )
				? $repo->getFileSha1( $srcPath )
				: FSFile::getSha1Base36FromPath( $srcPath );
			/** @var FileBackendDBRepoWrapper $wrapperBackend */
			$wrapperBackend = $repo->getBackend();
			$dst = $wrapperBackend->getPathForSHA1( $sha1 );
			$status = $repo->quickImport( $src, $dst );
			if ( $flags & File::DELETE_SOURCE ) {
				unlink( $srcPath );
			}

			if ( $this->exists() ) {
				$status->value = $archiveName;
			}
		} else {
			$flags = $flags & File::DELETE_SOURCE ? LocalRepo::DELETE_SOURCE : 0;
			$status = $repo->publish( $srcPath, $dstRel, $archiveRel, $flags, $options );

			if ( $status->value == 'new' ) {
				$status->value = '';
			} else {
				$status->value = $archiveName;
			}
		}

		$this->unlock(); // done

		return $status;
	}

	/** getLinksTo inherited */
	/** getExifData inherited */
	/** isLocal inherited */
	/** wasDeleted inherited */

	/**
	 * Move file to the new title
	 *
	 * Move current, old version and all thumbnails
	 * to the new filename. Old file is deleted.
	 *
	 * Cache purging is done; checks for validity
	 * and logging are caller's responsibility
	 *
	 * @param Title $target New file name
	 * @return Status
	 */
	function move( $target ) {
		if ( $this->getRepo()->getReadOnlyReason() !== false ) {
			return $this->readOnlyFatalStatus();
		}

		wfDebugLog( 'imagemove', "Got request to move {$this->name} to " . $target->getText() );
		$batch = new LocalFileMoveBatch( $this, $target );

		$this->lock(); // begin
		$batch->addCurrent();
		$archiveNames = $batch->addOlds();
		$status = $batch->execute();
		$this->unlock(); // done

		wfDebugLog( 'imagemove', "Finished moving {$this->name}" );

		// Purge the source and target files...
		$oldTitleFile = wfLocalFile( $this->title );
		$newTitleFile = wfLocalFile( $target );
		// To avoid slow purges in the transaction, move them outside...
		DeferredUpdates::addUpdate(
			new AutoCommitUpdate(
				$this->getRepo()->getMasterDB(),
				__METHOD__,
				function () use ( $oldTitleFile, $newTitleFile, $archiveNames ) {
					$oldTitleFile->purgeEverything();
					foreach ( $archiveNames as $archiveName ) {
						$oldTitleFile->purgeOldThumbnails( $archiveName );
					}
					$newTitleFile->purgeEverything();
				}
			),
			DeferredUpdates::PRESEND
		);

		if ( $status->isOK() ) {
			// Now switch the object
			$this->title = $target;
			// Force regeneration of the name and hashpath
			unset( $this->name );
			unset( $this->hashPath );
		}

		return $status;
	}

	/**
	 * Delete all versions of the file.
	 *
	 * Moves the files into an archive directory (or deletes them)
	 * and removes the database rows.
	 *
	 * Cache purging is done; logging is caller's responsibility.
	 *
	 * @param string $reason
	 * @param bool $suppress
	 * @param User|null $user
	 * @return Status
	 */
	function delete( $reason, $suppress = false, $user = null ) {
		if ( $this->getRepo()->getReadOnlyReason() !== false ) {
			return $this->readOnlyFatalStatus();
		}

		$batch = new LocalFileDeleteBatch( $this, $reason, $suppress, $user );

		$this->lock(); // begin
		$batch->addCurrent();
		// Get old version relative paths
		$archiveNames = $batch->addOlds();
		$status = $batch->execute();
		$this->unlock(); // done

		if ( $status->isOK() ) {
			DeferredUpdates::addUpdate( SiteStatsUpdate::factory( [ 'images' => -1 ] ) );
		}

		// To avoid slow purges in the transaction, move them outside...
		DeferredUpdates::addUpdate(
			new AutoCommitUpdate(
				$this->getRepo()->getMasterDB(),
				__METHOD__,
				function () use ( $archiveNames ) {
					$this->purgeEverything();
					foreach ( $archiveNames as $archiveName ) {
						$this->purgeOldThumbnails( $archiveName );
					}
				}
			),
			DeferredUpdates::PRESEND
		);

		// Purge the CDN
		$purgeUrls = [];
		foreach ( $archiveNames as $archiveName ) {
			$purgeUrls[] = $this->getArchiveUrl( $archiveName );
		}
		DeferredUpdates::addUpdate( new CdnCacheUpdate( $purgeUrls ), DeferredUpdates::PRESEND );

		return $status;
	}

	/**
	 * Delete an old version of the file.
	 *
	 * Moves the file into an archive directory (or deletes it)
	 * and removes the database row.
	 *
	 * Cache purging is done; logging is caller's responsibility.
	 *
	 * @param string $archiveName
	 * @param string $reason
	 * @param bool $suppress
	 * @param User|null $user
	 * @throws MWException Exception on database or file store failure
	 * @return Status
	 */
	function deleteOld( $archiveName, $reason, $suppress = false, $user = null ) {
		if ( $this->getRepo()->getReadOnlyReason() !== false ) {
			return $this->readOnlyFatalStatus();
		}

		$batch = new LocalFileDeleteBatch( $this, $reason, $suppress, $user );

		$this->lock(); // begin
		$batch->addOld( $archiveName );
		$status = $batch->execute();
		$this->unlock(); // done

		$this->purgeOldThumbnails( $archiveName );
		if ( $status->isOK() ) {
			$this->purgeDescription();
		}

		DeferredUpdates::addUpdate(
			new CdnCacheUpdate( [ $this->getArchiveUrl( $archiveName ) ] ),
			DeferredUpdates::PRESEND
		);

		return $status;
	}

	/**
	 * Restore all or specified deleted revisions to the given file.
	 * Permissions and logging are left to the caller.
	 *
	 * May throw database exceptions on error.
	 *
	 * @param array $versions Set of record ids of deleted items to restore,
	 *   or empty to restore all revisions.
	 * @param bool $unsuppress
	 * @return Status
	 */
	function restore( $versions = [], $unsuppress = false ) {
		if ( $this->getRepo()->getReadOnlyReason() !== false ) {
			return $this->readOnlyFatalStatus();
		}

		$batch = new LocalFileRestoreBatch( $this, $unsuppress );

		$this->lock(); // begin
		if ( !$versions ) {
			$batch->addAll();
		} else {
			$batch->addIds( $versions );
		}
		$status = $batch->execute();
		if ( $status->isGood() ) {
			$cleanupStatus = $batch->cleanup();
			$cleanupStatus->successCount = 0;
			$cleanupStatus->failCount = 0;
			$status->merge( $cleanupStatus );
		}
		$this->unlock(); // done

		return $status;
	}

	/** isMultipage inherited */
	/** pageCount inherited */
	/** scaleHeight inherited */
	/** getImageSize inherited */

	/**
	 * Get the URL of the file description page.
	 * @return string
	 */
	function getDescriptionUrl() {
		return $this->title->getLocalURL();
	}

	/**
	 * Get the HTML text of the description page
	 * This is not used by ImagePage for local files, since (among other things)
	 * it skips the parser cache.
	 *
	 * @param Language $lang What language to get description in (Optional)
	 * @return bool|mixed
	 */
	function getDescriptionText( $lang = null ) {
		$revision = Revision::newFromTitle( $this->title, false, Revision::READ_NORMAL );
		if ( !$revision ) {
			return false;
		}
		$content = $revision->getContent();
		if ( !$content ) {
			return false;
		}
		$pout = $content->getParserOutput( $this->title, null, new ParserOptions( null, $lang ) );

		return $pout->getText();
	}

	/**
	 * @param int $audience
	 * @param User $user
	 * @return string
	 */
	function getDescription( $audience = self::FOR_PUBLIC, User $user = null ) {
		$this->load();
		if ( $audience == self::FOR_PUBLIC && $this->isDeleted( self::DELETED_COMMENT ) ) {
			return '';
		} elseif ( $audience == self::FOR_THIS_USER
			&& !$this->userCan( self::DELETED_COMMENT, $user )
		) {
			return '';
		} else {
			return $this->description;
		}
	}

	/**
	 * @return bool|string
	 */
	function getTimestamp() {
		$this->load();

		return $this->timestamp;
	}

	/**
	 * @return bool|string
	 */
	public function getDescriptionTouched() {
		// The DB lookup might return false, e.g. if the file was just deleted, or the shared DB repo
		// itself gets it from elsewhere. To avoid repeating the DB lookups in such a case, we
		// need to differentiate between null (uninitialized) and false (failed to load).
		if ( $this->descriptionTouched === null ) {
			$cond = [
				'page_namespace' => $this->title->getNamespace(),
				'page_title' => $this->title->getDBkey()
			];
			$touched = $this->repo->getSlaveDB()->selectField( 'page', 'page_touched', $cond, __METHOD__ );
			$this->descriptionTouched = $touched ? wfTimestamp( TS_MW, $touched ) : false;
		}

		return $this->descriptionTouched;
	}

	/**
	 * @return string
	 */
	function getSha1() {
		$this->load();
		// Initialise now if necessary
		if ( $this->sha1 == '' && $this->fileExists ) {
			$this->lock(); // begin

			$this->sha1 = $this->repo->getFileSha1( $this->getPath() );
			if ( !wfReadOnly() && strval( $this->sha1 ) != '' ) {
				$dbw = $this->repo->getMasterDB();
				$dbw->update( 'image',
					[ 'img_sha1' => $this->sha1 ],
					[ 'img_name' => $this->getName() ],
					__METHOD__ );
				$this->invalidateCache();
			}

			$this->unlock(); // done
		}

		return $this->sha1;
	}

	/**
	 * @return bool Whether to cache in RepoGroup (this avoids OOMs)
	 */
	function isCacheable() {
		$this->load();

		// If extra data (metadata) was not loaded then it must have been large
		return $this->extraDataLoaded
		&& strlen( serialize( $this->metadata ) ) <= self::CACHE_FIELD_MAX_LEN;
	}

	/**
	 * @return Status
	 * @since 1.28
	 */
	public function acquireFileLock() {
		return $this->getRepo()->getBackend()->lockFiles(
			[ $this->getPath() ], LockManager::LOCK_EX, 10
		);
	}

	/**
	 * @return Status
	 * @since 1.28
	 */
	public function releaseFileLock() {
		return $this->getRepo()->getBackend()->unlockFiles(
			[ $this->getPath() ], LockManager::LOCK_EX
		);
	}

	/**
	 * Start an atomic DB section and lock the image for update
	 * or increments a reference counter if the lock is already held
	 *
	 * This method should not be used outside of LocalFile/LocalFile*Batch
	 *
	 * @throws LocalFileLockError Throws an error if the lock was not acquired
	 * @return bool Whether the file lock owns/spawned the DB transaction
	 */
	public function lock() {
		if ( !$this->locked ) {
			$logger = LoggerFactory::getInstance( 'LocalFile' );

			$dbw = $this->repo->getMasterDB();
			$makesTransaction = !$dbw->trxLevel();
			$dbw->startAtomic( self::ATOMIC_SECTION_LOCK );
			// Bug 54736: use simple lock to handle when the file does not exist.
			// SELECT FOR UPDATE prevents changes, not other SELECTs with FOR UPDATE.
			// Also, that would cause contention on INSERT of similarly named rows.
			$status = $this->acquireFileLock(); // represents all versions of the file
			if ( !$status->isGood() ) {
				$dbw->endAtomic( self::ATOMIC_SECTION_LOCK );
				$logger->warning( "Failed to lock '{file}'", [ 'file' => $this->name ] );

				throw new LocalFileLockError( $status );
			}
			// Release the lock *after* commit to avoid row-level contention.
			// Make sure it triggers on rollback() as well as commit() (T132921).
			$dbw->onTransactionResolution(
				function () use ( $logger ) {
					$status = $this->releaseFileLock();
					if ( !$status->isGood() ) {
						$logger->error( "Failed to unlock '{file}'", [ 'file' => $this->name ] );
					}
				},
				__METHOD__
			);
			// Callers might care if the SELECT snapshot is safely fresh
			$this->lockedOwnTrx = $makesTransaction;
		}

		$this->locked++;

		return $this->lockedOwnTrx;
	}

	/**
	 * Decrement the lock reference count and end the atomic section if it reaches zero
	 *
	 * This method should not be used outside of LocalFile/LocalFile*Batch
	 *
	 * The commit and loc release will happen when no atomic sections are active, which
	 * may happen immediately or at some point after calling this
	 */
	public function unlock() {
		if ( $this->locked ) {
			--$this->locked;
			if ( !$this->locked ) {
				$dbw = $this->repo->getMasterDB();
				$dbw->endAtomic( self::ATOMIC_SECTION_LOCK );
				$this->lockedOwnTrx = false;
			}
		}
	}

	/**
	 * @return Status
	 */
	protected function readOnlyFatalStatus() {
		return $this->getRepo()->newFatal( 'filereadonlyerror', $this->getName(),
			$this->getRepo()->getName(), $this->getRepo()->getReadOnlyReason() );
	}

	/**
	 * Clean up any dangling locks
	 */
	function __destruct() {
		$this->unlock();
	}
} // LocalFile class

# ------------------------------------------------------------------------------

/**
 * Helper class for file deletion
 * @ingroup FileAbstraction
 */
class LocalFileDeleteBatch {
	/** @var LocalFile */
	private $file;

	/** @var string */
	private $reason;

	/** @var array */
	private $srcRels = [];

	/** @var array */
	private $archiveUrls = [];

	/** @var array Items to be processed in the deletion batch */
	private $deletionBatch;

	/** @var bool Whether to suppress all suppressable fields when deleting */
	private $suppress;

	/** @var FileRepoStatus */
	private $status;

	/** @var User */
	private $user;

	/**
	 * @param File $file
	 * @param string $reason
	 * @param bool $suppress
	 * @param User|null $user
	 */
	function __construct( File $file, $reason = '', $suppress = false, $user = null ) {
		$this->file = $file;
		$this->reason = $reason;
		$this->suppress = $suppress;
		if ( $user ) {
			$this->user = $user;
		} else {
			global $wgUser;
			$this->user = $wgUser;
		}
		$this->status = $file->repo->newGood();
	}

	public function addCurrent() {
		$this->srcRels['.'] = $this->file->getRel();
	}

	/**
	 * @param string $oldName
	 */
	public function addOld( $oldName ) {
		$this->srcRels[$oldName] = $this->file->getArchiveRel( $oldName );
		$this->archiveUrls[] = $this->file->getArchiveUrl( $oldName );
	}

	/**
	 * Add the old versions of the image to the batch
	 * @return array List of archive names from old versions
	 */
	public function addOlds() {
		$archiveNames = [];

		$dbw = $this->file->repo->getMasterDB();
		$result = $dbw->select( 'oldimage',
			[ 'oi_archive_name' ],
			[ 'oi_name' => $this->file->getName() ],
			__METHOD__
		);

		foreach ( $result as $row ) {
			$this->addOld( $row->oi_archive_name );
			$archiveNames[] = $row->oi_archive_name;
		}

		return $archiveNames;
	}

	/**
	 * @return array
	 */
	protected function getOldRels() {
		if ( !isset( $this->srcRels['.'] ) ) {
			$oldRels =& $this->srcRels;
			$deleteCurrent = false;
		} else {
			$oldRels = $this->srcRels;
			unset( $oldRels['.'] );
			$deleteCurrent = true;
		}

		return [ $oldRels, $deleteCurrent ];
	}

	/**
	 * @return array
	 */
	protected function getHashes() {
		$hashes = [];
		list( $oldRels, $deleteCurrent ) = $this->getOldRels();

		if ( $deleteCurrent ) {
			$hashes['.'] = $this->file->getSha1();
		}

		if ( count( $oldRels ) ) {
			$dbw = $this->file->repo->getMasterDB();
			$res = $dbw->select(
				'oldimage',
				[ 'oi_archive_name', 'oi_sha1' ],
				[ 'oi_archive_name' => array_keys( $oldRels ),
					'oi_name' => $this->file->getName() ], // performance
				__METHOD__
			);

			foreach ( $res as $row ) {
				if ( rtrim( $row->oi_sha1, "\0" ) === '' ) {
					// Get the hash from the file
					$oldUrl = $this->file->getArchiveVirtualUrl( $row->oi_archive_name );
					$props = $this->file->repo->getFileProps( $oldUrl );

					if ( $props['fileExists'] ) {
						// Upgrade the oldimage row
						$dbw->update( 'oldimage',
							[ 'oi_sha1' => $props['sha1'] ],
							[ 'oi_name' => $this->file->getName(), 'oi_archive_name' => $row->oi_archive_name ],
							__METHOD__ );
						$hashes[$row->oi_archive_name] = $props['sha1'];
					} else {
						$hashes[$row->oi_archive_name] = false;
					}
				} else {
					$hashes[$row->oi_archive_name] = $row->oi_sha1;
				}
			}
		}

		$missing = array_diff_key( $this->srcRels, $hashes );

		foreach ( $missing as $name => $rel ) {
			$this->status->error( 'filedelete-old-unregistered', $name );
		}

		foreach ( $hashes as $name => $hash ) {
			if ( !$hash ) {
				$this->status->error( 'filedelete-missing', $this->srcRels[$name] );
				unset( $hashes[$name] );
			}
		}

		return $hashes;
	}

	protected function doDBInserts() {
		$now = time();
		$dbw = $this->file->repo->getMasterDB();
		$encTimestamp = $dbw->addQuotes( $dbw->timestamp( $now ) );
		$encUserId = $dbw->addQuotes( $this->user->getId() );
		$encReason = $dbw->addQuotes( $this->reason );
		$encGroup = $dbw->addQuotes( 'deleted' );
		$ext = $this->file->getExtension();
		$dotExt = $ext === '' ? '' : ".$ext";
		$encExt = $dbw->addQuotes( $dotExt );
		list( $oldRels, $deleteCurrent ) = $this->getOldRels();

		// Bitfields to further suppress the content
		if ( $this->suppress ) {
			$bitfield = 0;
			// This should be 15...
			$bitfield |= Revision::DELETED_TEXT;
			$bitfield |= Revision::DELETED_COMMENT;
			$bitfield |= Revision::DELETED_USER;
			$bitfield |= Revision::DELETED_RESTRICTED;
		} else {
			$bitfield = 'oi_deleted';
		}

		if ( $deleteCurrent ) {
			$dbw->insertSelect(
				'filearchive',
				'image',
				[
					'fa_storage_group' => $encGroup,
					'fa_storage_key' => $dbw->conditional(
						[ 'img_sha1' => '' ],
						$dbw->addQuotes( '' ),
						$dbw->buildConcat( [ "img_sha1", $encExt ] )
					),
					'fa_deleted_user' => $encUserId,
					'fa_deleted_timestamp' => $encTimestamp,
					'fa_deleted_reason' => $encReason,
					'fa_deleted' => $this->suppress ? $bitfield : 0,

					'fa_name' => 'img_name',
					'fa_archive_name' => 'NULL',
					'fa_size' => 'img_size',
					'fa_width' => 'img_width',
					'fa_height' => 'img_height',
					'fa_metadata' => 'img_metadata',
					'fa_bits' => 'img_bits',
					'fa_media_type' => 'img_media_type',
					'fa_major_mime' => 'img_major_mime',
					'fa_minor_mime' => 'img_minor_mime',
					'fa_description' => 'img_description',
					'fa_user' => 'img_user',
					'fa_user_text' => 'img_user_text',
					'fa_timestamp' => 'img_timestamp',
					'fa_sha1' => 'img_sha1'
				],
				[ 'img_name' => $this->file->getName() ],
				__METHOD__
			);
		}

		if ( count( $oldRels ) ) {
			$res = $dbw->select(
				'oldimage',
				OldLocalFile::selectFields(),
				[
					'oi_name' => $this->file->getName(),
					'oi_archive_name' => array_keys( $oldRels )
				],
				__METHOD__,
				[ 'FOR UPDATE' ]
			);
			$rowsInsert = [];
			foreach ( $res as $row ) {
				$rowsInsert[] = [
					// Deletion-specific fields
					'fa_storage_group' => 'deleted',
					'fa_storage_key' => ( $row->oi_sha1 === '' )
						? ''
						: "{$row->oi_sha1}{$dotExt}",
					'fa_deleted_user' => $this->user->getId(),
					'fa_deleted_timestamp' => $dbw->timestamp( $now ),
					'fa_deleted_reason' => $this->reason,
					// Counterpart fields
					'fa_deleted' => $this->suppress ? $bitfield : $row->oi_deleted,
					'fa_name' => $row->oi_name,
					'fa_archive_name' => $row->oi_archive_name,
					'fa_size' => $row->oi_size,
					'fa_width' => $row->oi_width,
					'fa_height' => $row->oi_height,
					'fa_metadata' => $row->oi_metadata,
					'fa_bits' => $row->oi_bits,
					'fa_media_type' => $row->oi_media_type,
					'fa_major_mime' => $row->oi_major_mime,
					'fa_minor_mime' => $row->oi_minor_mime,
					'fa_description' => $row->oi_description,
					'fa_user' => $row->oi_user,
					'fa_user_text' => $row->oi_user_text,
					'fa_timestamp' => $row->oi_timestamp,
					'fa_sha1' => $row->oi_sha1
				];
			}

			$dbw->insert( 'filearchive', $rowsInsert, __METHOD__ );
		}
	}

	function doDBDeletes() {
		$dbw = $this->file->repo->getMasterDB();
		list( $oldRels, $deleteCurrent ) = $this->getOldRels();

		if ( count( $oldRels ) ) {
			$dbw->delete( 'oldimage',
				[
					'oi_name' => $this->file->getName(),
					'oi_archive_name' => array_keys( $oldRels )
				], __METHOD__ );
		}

		if ( $deleteCurrent ) {
			$dbw->delete( 'image', [ 'img_name' => $this->file->getName() ], __METHOD__ );
		}
	}

	/**
	 * Run the transaction
	 * @return Status
	 */
	public function execute() {
		$repo = $this->file->getRepo();
		$this->file->lock();

		// Prepare deletion batch
		$hashes = $this->getHashes();
		$this->deletionBatch = [];
		$ext = $this->file->getExtension();
		$dotExt = $ext === '' ? '' : ".$ext";

		foreach ( $this->srcRels as $name => $srcRel ) {
			// Skip files that have no hash (e.g. missing DB record, or sha1 field and file source)
			if ( isset( $hashes[$name] ) ) {
				$hash = $hashes[$name];
				$key = $hash . $dotExt;
				$dstRel = $repo->getDeletedHashPath( $key ) . $key;
				$this->deletionBatch[$name] = [ $srcRel, $dstRel ];
			}
		}

		if ( !$repo->hasSha1Storage() ) {
			// Removes non-existent file from the batch, so we don't get errors.
			// This also handles files in the 'deleted' zone deleted via revision deletion.
			$checkStatus = $this->removeNonexistentFiles( $this->deletionBatch );
			if ( !$checkStatus->isGood() ) {
				$this->status->merge( $checkStatus );
				return $this->status;
			}
			$this->deletionBatch = $checkStatus->value;

			// Execute the file deletion batch
			$status = $this->file->repo->deleteBatch( $this->deletionBatch );
			if ( !$status->isGood() ) {
				$this->status->merge( $status );
			}
		}

		if ( !$this->status->isOK() ) {
			// Critical file deletion error; abort
			$this->file->unlock();

			return $this->status;
		}

		// Copy the image/oldimage rows to filearchive
		$this->doDBInserts();
		// Delete image/oldimage rows
		$this->doDBDeletes();

		// Commit and return
		$this->file->unlock();

		return $this->status;
	}

	/**
	 * Removes non-existent files from a deletion batch.
	 * @param array $batch
	 * @return Status
	 */
	protected function removeNonexistentFiles( $batch ) {
		$files = $newBatch = [];

		foreach ( $batch as $batchItem ) {
			list( $src, ) = $batchItem;
			$files[$src] = $this->file->repo->getVirtualUrl( 'public' ) . '/' . rawurlencode( $src );
		}

		$result = $this->file->repo->fileExistsBatch( $files );
		if ( in_array( null, $result, true ) ) {
			return Status::newFatal( 'backend-fail-internal',
				$this->file->repo->getBackend()->getName() );
		}

		foreach ( $batch as $batchItem ) {
			if ( $result[$batchItem[0]] ) {
				$newBatch[] = $batchItem;
			}
		}

		return Status::newGood( $newBatch );
	}
}

# ------------------------------------------------------------------------------

/**
 * Helper class for file undeletion
 * @ingroup FileAbstraction
 */
class LocalFileRestoreBatch {
	/** @var LocalFile */
	private $file;

	/** @var array List of file IDs to restore */
	private $cleanupBatch;

	/** @var array List of file IDs to restore */
	private $ids;

	/** @var bool Add all revisions of the file */
	private $all;

	/** @var bool Whether to remove all settings for suppressed fields */
	private $unsuppress = false;

	/**
	 * @param File $file
	 * @param bool $unsuppress
	 */
	function __construct( File $file, $unsuppress = false ) {
		$this->file = $file;
		$this->cleanupBatch = $this->ids = [];
		$this->ids = [];
		$this->unsuppress = $unsuppress;
	}

	/**
	 * Add a file by ID
	 * @param int $fa_id
	 */
	public function addId( $fa_id ) {
		$this->ids[] = $fa_id;
	}

	/**
	 * Add a whole lot of files by ID
	 * @param int[] $ids
	 */
	public function addIds( $ids ) {
		$this->ids = array_merge( $this->ids, $ids );
	}

	/**
	 * Add all revisions of the file
	 */
	public function addAll() {
		$this->all = true;
	}

	/**
	 * Run the transaction, except the cleanup batch.
	 * The cleanup batch should be run in a separate transaction, because it locks different
	 * rows and there's no need to keep the image row locked while it's acquiring those locks
	 * The caller may have its own transaction open.
	 * So we save the batch and let the caller call cleanup()
	 * @return Status
	 */
	public function execute() {
		/** @var Language */
		global $wgLang;

		$repo = $this->file->getRepo();
		if ( !$this->all && !$this->ids ) {
			// Do nothing
			return $repo->newGood();
		}

		$lockOwnsTrx = $this->file->lock();

		$dbw = $this->file->repo->getMasterDB();
		$status = $this->file->repo->newGood();

		$exists = (bool)$dbw->selectField( 'image', '1',
			[ 'img_name' => $this->file->getName() ],
			__METHOD__,
			// The lock() should already prevents changes, but this still may need
			// to bypass any transaction snapshot. However, if lock() started the
			// trx (which it probably did) then snapshot is post-lock and up-to-date.
			$lockOwnsTrx ? [] : [ 'LOCK IN SHARE MODE' ]
		);

		// Fetch all or selected archived revisions for the file,
		// sorted from the most recent to the oldest.
		$conditions = [ 'fa_name' => $this->file->getName() ];

		if ( !$this->all ) {
			$conditions['fa_id'] = $this->ids;
		}

		$result = $dbw->select(
			'filearchive',
			ArchivedFile::selectFields(),
			$conditions,
			__METHOD__,
			[ 'ORDER BY' => 'fa_timestamp DESC' ]
		);

		$idsPresent = [];
		$storeBatch = [];
		$insertBatch = [];
		$insertCurrent = false;
		$deleteIds = [];
		$first = true;
		$archiveNames = [];

		foreach ( $result as $row ) {
			$idsPresent[] = $row->fa_id;

			if ( $row->fa_name != $this->file->getName() ) {
				$status->error( 'undelete-filename-mismatch', $wgLang->timeanddate( $row->fa_timestamp ) );
				$status->failCount++;
				continue;
			}

			if ( $row->fa_storage_key == '' ) {
				// Revision was missing pre-deletion
				$status->error( 'undelete-bad-store-key', $wgLang->timeanddate( $row->fa_timestamp ) );
				$status->failCount++;
				continue;
			}

			$deletedRel = $repo->getDeletedHashPath( $row->fa_storage_key ) .
				$row->fa_storage_key;
			$deletedUrl = $repo->getVirtualUrl() . '/deleted/' . $deletedRel;

			if ( isset( $row->fa_sha1 ) ) {
				$sha1 = $row->fa_sha1;
			} else {
				// old row, populate from key
				$sha1 = LocalRepo::getHashFromKey( $row->fa_storage_key );
			}

			# Fix leading zero
			if ( strlen( $sha1 ) == 32 && $sha1[0] == '0' ) {
				$sha1 = substr( $sha1, 1 );
			}

			if ( is_null( $row->fa_major_mime ) || $row->fa_major_mime == 'unknown'
				|| is_null( $row->fa_minor_mime ) || $row->fa_minor_mime == 'unknown'
				|| is_null( $row->fa_media_type ) || $row->fa_media_type == 'UNKNOWN'
				|| is_null( $row->fa_metadata )
			) {
				// Refresh our metadata
				// Required for a new current revision; nice for older ones too. :)
				$props = RepoGroup::singleton()->getFileProps( $deletedUrl );
			} else {
				$props = [
					'minor_mime' => $row->fa_minor_mime,
					'major_mime' => $row->fa_major_mime,
					'media_type' => $row->fa_media_type,
					'metadata' => $row->fa_metadata
				];
			}

			if ( $first && !$exists ) {
				// This revision will be published as the new current version
				$destRel = $this->file->getRel();
				$insertCurrent = [
					'img_name' => $row->fa_name,
					'img_size' => $row->fa_size,
					'img_width' => $row->fa_width,
					'img_height' => $row->fa_height,
					'img_metadata' => $props['metadata'],
					'img_bits' => $row->fa_bits,
					'img_media_type' => $props['media_type'],
					'img_major_mime' => $props['major_mime'],
					'img_minor_mime' => $props['minor_mime'],
					'img_description' => $row->fa_description,
					'img_user' => $row->fa_user,
					'img_user_text' => $row->fa_user_text,
					'img_timestamp' => $row->fa_timestamp,
					'img_sha1' => $sha1
				];

				// The live (current) version cannot be hidden!
				if ( !$this->unsuppress && $row->fa_deleted ) {
					$status->fatal( 'undeleterevdel' );
					$this->file->unlock();
					return $status;
				}
			} else {
				$archiveName = $row->fa_archive_name;

				if ( $archiveName == '' ) {
					// This was originally a current version; we
					// have to devise a new archive name for it.
					// Format is <timestamp of archiving>!<name>
					$timestamp = wfTimestamp( TS_UNIX, $row->fa_deleted_timestamp );

					do {
						$archiveName = wfTimestamp( TS_MW, $timestamp ) . '!' . $row->fa_name;
						$timestamp++;
					} while ( isset( $archiveNames[$archiveName] ) );
				}

				$archiveNames[$archiveName] = true;
				$destRel = $this->file->getArchiveRel( $archiveName );
				$insertBatch[] = [
					'oi_name' => $row->fa_name,
					'oi_archive_name' => $archiveName,
					'oi_size' => $row->fa_size,
					'oi_width' => $row->fa_width,
					'oi_height' => $row->fa_height,
					'oi_bits' => $row->fa_bits,
					'oi_description' => $row->fa_description,
					'oi_user' => $row->fa_user,
					'oi_user_text' => $row->fa_user_text,
					'oi_timestamp' => $row->fa_timestamp,
					'oi_metadata' => $props['metadata'],
					'oi_media_type' => $props['media_type'],
					'oi_major_mime' => $props['major_mime'],
					'oi_minor_mime' => $props['minor_mime'],
					'oi_deleted' => $this->unsuppress ? 0 : $row->fa_deleted,
					'oi_sha1' => $sha1 ];
			}

			$deleteIds[] = $row->fa_id;

			if ( !$this->unsuppress && $row->fa_deleted & File::DELETED_FILE ) {
				// private files can stay where they are
				$status->successCount++;
			} else {
				$storeBatch[] = [ $deletedUrl, 'public', $destRel ];
				$this->cleanupBatch[] = $row->fa_storage_key;
			}

			$first = false;
		}

		unset( $result );

		// Add a warning to the status object for missing IDs
		$missingIds = array_diff( $this->ids, $idsPresent );

		foreach ( $missingIds as $id ) {
			$status->error( 'undelete-missing-filearchive', $id );
		}

		if ( !$repo->hasSha1Storage() ) {
			// Remove missing files from batch, so we don't get errors when undeleting them
			$checkStatus = $this->removeNonexistentFiles( $storeBatch );
			if ( !$checkStatus->isGood() ) {
				$status->merge( $checkStatus );
				return $status;
			}
			$storeBatch = $checkStatus->value;

			// Run the store batch
			// Use the OVERWRITE_SAME flag to smooth over a common error
			$storeStatus = $this->file->repo->storeBatch( $storeBatch, FileRepo::OVERWRITE_SAME );
			$status->merge( $storeStatus );

			if ( !$status->isGood() ) {
				// Even if some files could be copied, fail entirely as that is the
				// easiest thing to do without data loss
				$this->cleanupFailedBatch( $storeStatus, $storeBatch );
				$status->setOK( false );
				$this->file->unlock();

				return $status;
			}
		}

		// Run the DB updates
		// Because we have locked the image row, key conflicts should be rare.
		// If they do occur, we can roll back the transaction at this time with
		// no data loss, but leaving unregistered files scattered throughout the
		// public zone.
		// This is not ideal, which is why it's important to lock the image row.
		if ( $insertCurrent ) {
			$dbw->insert( 'image', $insertCurrent, __METHOD__ );
		}

		if ( $insertBatch ) {
			$dbw->insert( 'oldimage', $insertBatch, __METHOD__ );
		}

		if ( $deleteIds ) {
			$dbw->delete( 'filearchive',
				[ 'fa_id' => $deleteIds ],
				__METHOD__ );
		}

		// If store batch is empty (all files are missing), deletion is to be considered successful
		if ( $status->successCount > 0 || !$storeBatch || $repo->hasSha1Storage() ) {
			if ( !$exists ) {
				wfDebug( __METHOD__ . " restored {$status->successCount} items, creating a new current\n" );

				DeferredUpdates::addUpdate( SiteStatsUpdate::factory( [ 'images' => 1 ] ) );

				$this->file->purgeEverything();
			} else {
				wfDebug( __METHOD__ . " restored {$status->successCount} as archived versions\n" );
				$this->file->purgeDescription();
			}
		}

		$this->file->unlock();

		return $status;
	}

	/**
	 * Removes non-existent files from a store batch.
	 * @param array $triplets
	 * @return Status
	 */
	protected function removeNonexistentFiles( $triplets ) {
		$files = $filteredTriplets = [];
		foreach ( $triplets as $file ) {
			$files[$file[0]] = $file[0];
		}

		$result = $this->file->repo->fileExistsBatch( $files );
		if ( in_array( null, $result, true ) ) {
			return Status::newFatal( 'backend-fail-internal',
				$this->file->repo->getBackend()->getName() );
		}

		foreach ( $triplets as $file ) {
			if ( $result[$file[0]] ) {
				$filteredTriplets[] = $file;
			}
		}

		return Status::newGood( $filteredTriplets );
	}

	/**
	 * Removes non-existent files from a cleanup batch.
	 * @param array $batch
	 * @return array
	 */
	protected function removeNonexistentFromCleanup( $batch ) {
		$files = $newBatch = [];
		$repo = $this->file->repo;

		foreach ( $batch as $file ) {
			$files[$file] = $repo->getVirtualUrl( 'deleted' ) . '/' .
				rawurlencode( $repo->getDeletedHashPath( $file ) . $file );
		}

		$result = $repo->fileExistsBatch( $files );

		foreach ( $batch as $file ) {
			if ( $result[$file] ) {
				$newBatch[] = $file;
			}
		}

		return $newBatch;
	}

	/**
	 * Delete unused files in the deleted zone.
	 * This should be called from outside the transaction in which execute() was called.
	 * @return Status
	 */
	public function cleanup() {
		if ( !$this->cleanupBatch ) {
			return $this->file->repo->newGood();
		}

		$this->cleanupBatch = $this->removeNonexistentFromCleanup( $this->cleanupBatch );

		$status = $this->file->repo->cleanupDeletedBatch( $this->cleanupBatch );

		return $status;
	}

	/**
	 * Cleanup a failed batch. The batch was only partially successful, so
	 * rollback by removing all items that were succesfully copied.
	 *
	 * @param Status $storeStatus
	 * @param array $storeBatch
	 */
	protected function cleanupFailedBatch( $storeStatus, $storeBatch ) {
		$cleanupBatch = [];

		foreach ( $storeStatus->success as $i => $success ) {
			// Check if this item of the batch was successfully copied
			if ( $success ) {
				// Item was successfully copied and needs to be removed again
				// Extract ($dstZone, $dstRel) from the batch
				$cleanupBatch[] = [ $storeBatch[$i][1], $storeBatch[$i][2] ];
			}
		}
		$this->file->repo->cleanupBatch( $cleanupBatch );
	}
}

# ------------------------------------------------------------------------------

/**
 * Helper class for file movement
 * @ingroup FileAbstraction
 */
class LocalFileMoveBatch {
	/** @var LocalFile */
	protected $file;

	/** @var Title */
	protected $target;

	protected $cur;

	protected $olds;

	protected $oldCount;

	protected $archive;

	/** @var IDatabase */
	protected $db;

	/**
	 * @param File $file
	 * @param Title $target
	 */
	function __construct( File $file, Title $target ) {
		$this->file = $file;
		$this->target = $target;
		$this->oldHash = $this->file->repo->getHashPath( $this->file->getName() );
		$this->newHash = $this->file->repo->getHashPath( $this->target->getDBkey() );
		$this->oldName = $this->file->getName();
		$this->newName = $this->file->repo->getNameFromTitle( $this->target );
		$this->oldRel = $this->oldHash . $this->oldName;
		$this->newRel = $this->newHash . $this->newName;
		$this->db = $file->getRepo()->getMasterDB();
	}

	/**
	 * Add the current image to the batch
	 */
	public function addCurrent() {
		$this->cur = [ $this->oldRel, $this->newRel ];
	}

	/**
	 * Add the old versions of the image to the batch
	 * @return array List of archive names from old versions
	 */
	public function addOlds() {
		$archiveBase = 'archive';
		$this->olds = [];
		$this->oldCount = 0;
		$archiveNames = [];

		$result = $this->db->select( 'oldimage',
			[ 'oi_archive_name', 'oi_deleted' ],
			[ 'oi_name' => $this->oldName ],
			__METHOD__,
			[ 'LOCK IN SHARE MODE' ] // ignore snapshot
		);

		foreach ( $result as $row ) {
			$archiveNames[] = $row->oi_archive_name;
			$oldName = $row->oi_archive_name;
			$bits = explode( '!', $oldName, 2 );

			if ( count( $bits ) != 2 ) {
				wfDebug( "Old file name missing !: '$oldName' \n" );
				continue;
			}

			list( $timestamp, $filename ) = $bits;

			if ( $this->oldName != $filename ) {
				wfDebug( "Old file name doesn't match: '$oldName' \n" );
				continue;
			}

			$this->oldCount++;

			// Do we want to add those to oldCount?
			if ( $row->oi_deleted & File::DELETED_FILE ) {
				continue;
			}

			$this->olds[] = [
				"{$archiveBase}/{$this->oldHash}{$oldName}",
				"{$archiveBase}/{$this->newHash}{$timestamp}!{$this->newName}"
			];
		}

		return $archiveNames;
	}

	/**
	 * Perform the move.
	 * @return Status
	 */
	public function execute() {
		$repo = $this->file->repo;
		$status = $repo->newGood();
		$destFile = wfLocalFile( $this->target );

		$this->file->lock(); // begin
		$destFile->lock(); // quickly fail if destination is not available

		$triplets = $this->getMoveTriplets();
		$checkStatus = $this->removeNonexistentFiles( $triplets );
		if ( !$checkStatus->isGood() ) {
			$destFile->unlock();
			$this->file->unlock();
			$status->merge( $checkStatus ); // couldn't talk to file backend
			return $status;
		}
		$triplets = $checkStatus->value;

		// Verify the file versions metadata in the DB.
		$statusDb = $this->verifyDBUpdates();
		if ( !$statusDb->isGood() ) {
			$destFile->unlock();
			$this->file->unlock();
			$statusDb->setOK( false );

			return $statusDb;
		}

		if ( !$repo->hasSha1Storage() ) {
			// Copy the files into their new location.
			// If a prior process fataled copying or cleaning up files we tolerate any
			// of the existing files if they are identical to the ones being stored.
			$statusMove = $repo->storeBatch( $triplets, FileRepo::OVERWRITE_SAME );
			wfDebugLog( 'imagemove', "Moved files for {$this->file->getName()}: " .
				"{$statusMove->successCount} successes, {$statusMove->failCount} failures" );
			if ( !$statusMove->isGood() ) {
				// Delete any files copied over (while the destination is still locked)
				$this->cleanupTarget( $triplets );
				$destFile->unlock();
				$this->file->unlock();
				wfDebugLog( 'imagemove', "Error in moving files: "
					. $statusMove->getWikiText( false, false, 'en' ) );
				$statusMove->setOK( false );

				return $statusMove;
			}
			$status->merge( $statusMove );
		}

		// Rename the file versions metadata in the DB.
		$this->doDBUpdates();

		wfDebugLog( 'imagemove', "Renamed {$this->file->getName()} in database: " .
			"{$statusDb->successCount} successes, {$statusDb->failCount} failures" );

		$destFile->unlock();
		$this->file->unlock(); // done

		// Everything went ok, remove the source files
		$this->cleanupSource( $triplets );

		$status->merge( $statusDb );

		return $status;
	}

	/**
	 * Verify the database updates and return a new FileRepoStatus indicating how
	 * many rows would be updated.
	 *
	 * @return Status
	 */
	protected function verifyDBUpdates() {
		$repo = $this->file->repo;
		$status = $repo->newGood();
		$dbw = $this->db;

		$hasCurrent = $dbw->selectField(
			'image',
			'1',
			[ 'img_name' => $this->oldName ],
			__METHOD__,
			[ 'FOR UPDATE' ]
		);
		$oldRowCount = $dbw->selectField(
			'oldimage',
			'COUNT(*)',
			[ 'oi_name' => $this->oldName ],
			__METHOD__,
			[ 'FOR UPDATE' ]
		);

		if ( $hasCurrent ) {
			$status->successCount++;
		} else {
			$status->failCount++;
		}
		$status->successCount += $oldRowCount;
		// Bug 34934: oldCount is based on files that actually exist.
		// There may be more DB rows than such files, in which case $affected
		// can be greater than $total. We use max() to avoid negatives here.
		$status->failCount += max( 0, $this->oldCount - $oldRowCount );
		if ( $status->failCount ) {
			$status->error( 'imageinvalidfilename' );
		}

		return $status;
	}

	/**
	 * Do the database updates and return a new FileRepoStatus indicating how
	 * many rows where updated.
	 */
	protected function doDBUpdates() {
		$dbw = $this->db;

		// Update current image
		$dbw->update(
			'image',
			[ 'img_name' => $this->newName ],
			[ 'img_name' => $this->oldName ],
			__METHOD__
		);
		// Update old images
		$dbw->update(
			'oldimage',
			[
				'oi_name' => $this->newName,
				'oi_archive_name = ' . $dbw->strreplace( 'oi_archive_name',
					$dbw->addQuotes( $this->oldName ), $dbw->addQuotes( $this->newName ) ),
			],
			[ 'oi_name' => $this->oldName ],
			__METHOD__
		);
	}

	/**
	 * Generate triplets for FileRepo::storeBatch().
	 * @return array
	 */
	protected function getMoveTriplets() {
		$moves = array_merge( [ $this->cur ], $this->olds );
		$triplets = []; // The format is: (srcUrl, destZone, destUrl)

		foreach ( $moves as $move ) {
			// $move: (oldRelativePath, newRelativePath)
			$srcUrl = $this->file->repo->getVirtualUrl() . '/public/' . rawurlencode( $move[0] );
			$triplets[] = [ $srcUrl, 'public', $move[1] ];
			wfDebugLog(
				'imagemove',
				"Generated move triplet for {$this->file->getName()}: {$srcUrl} :: public :: {$move[1]}"
			);
		}

		return $triplets;
	}

	/**
	 * Removes non-existent files from move batch.
	 * @param array $triplets
	 * @return Status
	 */
	protected function removeNonexistentFiles( $triplets ) {
		$files = [];

		foreach ( $triplets as $file ) {
			$files[$file[0]] = $file[0];
		}

		$result = $this->file->repo->fileExistsBatch( $files );
		if ( in_array( null, $result, true ) ) {
			return Status::newFatal( 'backend-fail-internal',
				$this->file->repo->getBackend()->getName() );
		}

		$filteredTriplets = [];
		foreach ( $triplets as $file ) {
			if ( $result[$file[0]] ) {
				$filteredTriplets[] = $file;
			} else {
				wfDebugLog( 'imagemove', "File {$file[0]} does not exist" );
			}
		}

		return Status::newGood( $filteredTriplets );
	}

	/**
	 * Cleanup a partially moved array of triplets by deleting the target
	 * files. Called if something went wrong half way.
	 * @param array $triplets
	 */
	protected function cleanupTarget( $triplets ) {
		// Create dest pairs from the triplets
		$pairs = [];
		foreach ( $triplets as $triplet ) {
			// $triplet: (old source virtual URL, dst zone, dest rel)
			$pairs[] = [ $triplet[1], $triplet[2] ];
		}

		$this->file->repo->cleanupBatch( $pairs );
	}

	/**
	 * Cleanup a fully moved array of triplets by deleting the source files.
	 * Called at the end of the move process if everything else went ok.
	 * @param array $triplets
	 */
	protected function cleanupSource( $triplets ) {
		// Create source file names from the triplets
		$files = [];
		foreach ( $triplets as $triplet ) {
			$files[] = $triplet[0];
		}

		$this->file->repo->cleanupBatch( $files );
	}
}

class LocalFileLockError extends ErrorPageError {
	public function __construct( Status $status ) {
		parent::__construct(
			'actionfailed',
			$status->getMessage()
		);
	}

	public function report() {
		global $wgOut;
		$wgOut->setStatusCode( 429 );
		parent::report();
	}
}
