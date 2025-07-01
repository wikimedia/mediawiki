<?php
/**
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

namespace MediaWiki\FileRepo\File;

use InvalidArgumentException;
use LockManager;
use MediaHandler;
use MediaWiki\Content\ContentHandler;
use MediaWiki\Context\RequestContext;
use MediaWiki\Deferred\AutoCommitUpdate;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Deferred\LinksUpdate\LinksUpdate;
use MediaWiki\Deferred\SiteStatsUpdate;
use MediaWiki\FileRepo\FileBackendDBRepoWrapper;
use MediaWiki\FileRepo\FileRepo;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\JobQueue\Jobs\HTMLCacheUpdateJob;
use MediaWiki\JobQueue\Jobs\ThumbnailRenderJob;
use MediaWiki\Language\Language;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Logging\LogEntryBase;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\Article;
use MediaWiki\Page\WikiFilePage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Permissions\Authority;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Status\Status;
use MediaWiki\Storage\PageUpdater;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MWFileProps;
use RuntimeException;
use stdClass;
use UnexpectedValueException;
use Wikimedia\FileBackend\FileBackend;
use Wikimedia\FileBackend\FileBackendError;
use Wikimedia\FileBackend\FSFile\FSFile;
use Wikimedia\Rdbms\Blob;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Local file in the wiki's own database.
 *
 * Provides methods to retrieve paths (physical, logical, URL),
 * to generate image thumbnails or for uploading.
 *
 * Note that only the repo object knows what its file class is called. You should
 * never name a file class explicitly outside of the repo class. Instead use the
 * repo's factory functions to generate file objects, for example:
 *
 * RepoGroup::singleton()->getLocalRepo()->newFile( $title );
 *
 * Consider the services container below;
 *
 * $services = MediaWikiServices::getInstance();
 *
 * The convenience services $services->getRepoGroup()->getLocalRepo()->newFile()
 * and $services->getRepoGroup()->findFile() should be sufficient in most cases.
 *
 * @TODO: DI - Instead of using MediaWikiServices::getInstance(), a service should
 * ideally accept a RepoGroup in its constructor and then, use $this->repoGroup->findFile()
 * and $this->repoGroup->getLocalRepo()->newFile().
 *
 * @stable to extend
 * @ingroup FileAbstraction
 */
class LocalFile extends File {
	private const VERSION = 13; // cache version

	private const CACHE_FIELD_MAX_LEN = 1000;

	/** @var string Metadata serialization: empty string. This is a compact non-legacy format. */
	private const MDS_EMPTY = 'empty';

	/** @var string Metadata serialization: some other string */
	private const MDS_LEGACY = 'legacy';

	/** @var string Metadata serialization: PHP serialize() */
	private const MDS_PHP = 'php';

	/** @var string Metadata serialization: JSON */
	private const MDS_JSON = 'json';

	/** @var int Maximum number of pages for which to trigger render jobs */
	private const MAX_PAGE_RENDER_JOBS = 50;

	/** @var bool Does the file exist on disk? (loadFromXxx) */
	protected $fileExists;

	/** @var int Id of the file */
	private $fileId;

	/** @var int Id of the file type */
	private $fileTypeId;

	/** @var int Image width */
	protected $width;

	/** @var int Image height */
	protected $height;

	/** @var int Returned by getimagesize (loadFromXxx) */
	protected $bits;

	/** @var string MEDIATYPE_xxx (bitmap, drawing, audio...) */
	protected $media_type;

	/** @var string MIME type, determined by MimeAnalyzer::guessMimeType */
	protected $mime;

	/** @var int Size in bytes (loadFromXxx) */
	protected $size;

	/** @var array Unserialized metadata */
	protected $metadataArray = [];

	/**
	 * One of the MDS_* constants, giving the format of the metadata as stored
	 * in the DB, or null if the data was not loaded from the DB.
	 *
	 * @var string|null
	 */
	protected $metadataSerializationFormat;

	/** @var string[] Map of metadata item name to blob address */
	protected $metadataBlobs = [];

	/**
	 * Map of metadata item name to blob address for items that exist but
	 * have not yet been loaded into $this->metadataArray
	 *
	 * @var string[]
	 */
	protected $unloadedMetadataBlobs = [];

	/** @var string SHA-1 base 36 content hash */
	protected $sha1;

	/** @var bool Whether or not core data has been loaded from the database (loadFromXxx) */
	protected $dataLoaded = false;

	/** @var bool Whether or not lazy-loaded data has been loaded from the database */
	protected $extraDataLoaded = false;

	/** @var int Bitfield akin to rev_deleted */
	protected $deleted;

	/** @var int id in file table, null on read old */
	protected $file_id;

	/** @var int id in filerevision table, null on read old */
	protected $filerevision_id;

	/** @var string */
	protected $repoClass = LocalRepo::class;

	/** @var int Number of line to return by nextHistoryLine() (constructor) */
	private $historyLine = 0;

	/** @var IResultWrapper|null Result of the query for the file's history (nextHistoryLine) */
	private $historyRes = null;

	/** @var string Major MIME type */
	private $major_mime;

	/** @var string Minor MIME type */
	private $minor_mime;

	/** @var string Upload timestamp */
	private $timestamp;

	/** @var UserIdentity|null Uploader */
	private $user;

	/** @var string Description of current revision of the file */
	private $description;

	/** @var string TS_MW timestamp of the last change of the file description */
	private $descriptionTouched;

	/** @var bool Whether the row was upgraded on load */
	private $upgraded;

	/** @var bool Whether the row was scheduled to upgrade on load */
	private $upgrading;

	/** @var int If >= 1 the image row is locked */
	private $locked;

	/** @var bool True if the image row is locked with a lock initiated transaction */
	private $lockedOwnTrx;

	/** @var bool True if file is not present in file system. Not to be cached in memcached */
	private $missing;

	/** @var MetadataStorageHelper */
	private $metadataStorageHelper;

	/** @var int */
	private $migrationStage = SCHEMA_COMPAT_OLD;

	// @note: higher than IDBAccessObject constants
	private const LOAD_ALL = 16; // integer; load all the lazy fields too (like metadata)

	private const ATOMIC_SECTION_LOCK = 'LocalFile::lockingTransaction';

	/**
	 * Create a LocalFile from a title
	 * Do not call this except from inside a repo class.
	 *
	 * Note: $unused param is only here to avoid an E_STRICT
	 *
	 * @stable to override
	 *
	 * @param Title $title
	 * @param LocalRepo $repo
	 * @param null $unused
	 *
	 * @return static
	 */
	public static function newFromTitle( $title, $repo, $unused = null ) {
		return new static( $title, $repo );
	}

	/**
	 * Create a LocalFile from a title
	 * Do not call this except from inside a repo class.
	 *
	 * @stable to override
	 *
	 * @param stdClass $row
	 * @param LocalRepo $repo
	 *
	 * @return static
	 */
	public static function newFromRow( $row, $repo ) {
		$title = Title::makeTitle( NS_FILE, $row->img_name );
		$file = new static( $title, $repo );
		$file->loadFromRow( $row );

		return $file;
	}

	/**
	 * Create a LocalFile from a SHA-1 key
	 * Do not call this except from inside a repo class.
	 *
	 * @stable to override
	 *
	 * @param string $sha1 Base-36 SHA-1
	 * @param LocalRepo $repo
	 * @param string|false $timestamp MW_timestamp (optional)
	 * @return static|false
	 */
	public static function newFromKey( $sha1, $repo, $timestamp = false ) {
		$dbr = $repo->getReplicaDB();
		$queryBuilder = FileSelectQueryBuilder::newForFile( $dbr );

		$queryBuilder->where( [ 'img_sha1' => $sha1 ] );

		if ( $timestamp ) {
			$queryBuilder->andWhere( [ 'img_timestamp' => $dbr->timestamp( $timestamp ) ] );
		}

		$row = $queryBuilder->caller( __METHOD__ )->fetchRow();
		if ( $row ) {
			return static::newFromRow( $row, $repo );
		} else {
			return false;
		}
	}

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new localfile object.
	 *
	 * Since 1.34, img_user and img_user_text have not been present in the
	 * database, but they continue to be available in query results as
	 * aliases.
	 *
	 * @since 1.31
	 * @stable to override
	 *
	 * @deprecated since 1.41 use FileSelectQueryBuilder instead
	 * @param string[] $options
	 *   - omit-lazy: Omit fields that are lazily cached.
	 * @return array[] With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()` or `SelectQueryBuilder::tables`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()` or `SelectQueryBuilder::fields`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()` or `SelectQueryBuilder::joinConds`
	 * @phan-return array{tables:string[],fields:string[],joins:array}
	 */
	public static function getQueryInfo( array $options = [] ) {
		wfDeprecated( __METHOD__, '1.41' );
		$dbr = MediaWikiServices::getInstance()->getConnectionProvider()->getReplicaDatabase();
		$queryInfo = FileSelectQueryBuilder::newForFile( $dbr, $options )->getQueryInfo();
		// needs remapping...
		return [
			'tables' => $queryInfo['tables'],
			'fields' => $queryInfo['fields'],
			'joins' => $queryInfo['join_conds'],
		];
	}

	/**
	 * Do not call this except from inside a repo class.
	 * @stable to call
	 *
	 * @param Title $title
	 * @param LocalRepo $repo
	 */
	public function __construct( $title, $repo ) {
		parent::__construct( $title, $repo );
		$this->metadataStorageHelper = new MetadataStorageHelper( $repo );
		$this->migrationStage = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::FileSchemaMigrationStage
		);

		$this->assertRepoDefined();
		$this->assertTitleDefined();
	}

	/**
	 * @return LocalRepo|false
	 */
	public function getRepo() {
		return $this->repo;
	}

	/**
	 * Get the memcached key for the main data for this file, or false if
	 * there is no access to the shared cache.
	 * @stable to override
	 * @return string|false
	 */
	protected function getCacheKey() {
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
			$this->loadFromDB( IDBAccessObject::READ_NORMAL );

			return;
		}

		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$cachedValues = $cache->getWithSetCallback(
			$key,
			$cache::TTL_WEEK,
			function ( $oldValue, &$ttl, array &$setOpts ) use ( $cache ) {
				$setOpts += Database::getCacheSetOptions( $this->repo->getReplicaDB() );

				$this->loadFromDB( IDBAccessObject::READ_NORMAL );

				$fields = $this->getCacheFields( '' );
				$cacheVal = [];
				$cacheVal['fileExists'] = $this->fileExists;
				if ( $this->fileExists ) {
					foreach ( $fields as $field ) {
						$cacheVal[$field] = $this->$field;
					}
				}
				if ( $this->user ) {
					$cacheVal['user'] = $this->user->getId();
					$cacheVal['user_text'] = $this->user->getName();
				}

				// Don't cache metadata items stored as blobs, since they tend to be large
				if ( $this->metadataBlobs ) {
					$cacheVal['metadata'] = array_diff_key(
						$this->metadataArray, $this->metadataBlobs );
					// Save the blob addresses
					$cacheVal['metadataBlobs'] = $this->metadataBlobs;
				} else {
					$cacheVal['metadata'] = $this->metadataArray;
				}

				// Strip off excessive entries from the subset of fields that can become large.
				// If the cache value gets too large and might not fit in the cache,
				// causing repeat database queries for each access to the file.
				foreach ( $this->getLazyCacheFields( '' ) as $field ) {
					if ( isset( $cacheVal[$field] )
						&& strlen( serialize( $cacheVal[$field] ) ) > 100 * 1024
					) {
						unset( $cacheVal[$field] ); // don't let the value get too big
						if ( $field === 'metadata' ) {
							unset( $cacheVal['metadataBlobs'] );
						}
					}
				}

				if ( $this->fileExists ) {
					$ttl = $cache->adaptiveTTL( (int)wfTimestamp( TS_UNIX, $this->timestamp ), $ttl );
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

		$this->repo->getPrimaryDB()->onTransactionPreCommitOrIdle(
			static function () use ( $key ) {
				MediaWikiServices::getInstance()->getMainWANObjectCache()->delete( $key );
			},
			__METHOD__
		);
	}

	/**
	 * Load metadata from the file itself
	 *
	 * @internal
	 * @param string|null $path The path or virtual URL to load from, or null
	 * to use the previously stored file.
	 */
	public function loadFromFile( $path = null ) {
		$props = $this->repo->getFileProps( $path ?? $this->getVirtualUrl() );
		$this->setProps( $props );
	}

	/**
	 * Returns the list of object properties that are included as-is in the cache.
	 * @stable to override
	 * @param string $prefix Must be the empty string
	 * @return string[]
	 * @since 1.31 No longer accepts a non-empty $prefix
	 */
	protected function getCacheFields( $prefix = 'img_' ) {
		if ( $prefix !== '' ) {
			throw new InvalidArgumentException(
				__METHOD__ . ' with a non-empty prefix is no longer supported.'
			);
		}

		// See self::getQueryInfo() for the fetching of the data from the DB,
		// self::loadFromRow() for the loading of the object from the DB row,
		// and self::loadFromCache() for the caching, and self::setProps() for
		// populating the object from an array of data.
		return [ 'size', 'width', 'height', 'bits', 'media_type',
			'major_mime', 'minor_mime', 'timestamp', 'sha1', 'description' ];
	}

	/**
	 * Returns the list of object properties that are included as-is in the
	 * cache, only when they're not too big, and are lazily loaded by self::loadExtraFromDB().
	 * @param string $prefix Must be the empty string
	 * @return string[]
	 * @since 1.31 No longer accepts a non-empty $prefix
	 */
	protected function getLazyCacheFields( $prefix = 'img_' ) {
		if ( $prefix !== '' ) {
			throw new InvalidArgumentException(
				__METHOD__ . ' with a non-empty prefix is no longer supported.'
			);
		}

		// Keep this in sync with the omit-lazy option in self::getQueryInfo().
		return [ 'metadata' ];
	}

	/**
	 * Load file metadata from the DB
	 * @stable to override
	 * @param int $flags
	 */
	protected function loadFromDB( $flags = 0 ) {
		$fname = static::class . '::' . __FUNCTION__;

		# Unconditionally set loaded=true, we don't want the accessors constantly rechecking
		$this->dataLoaded = true;
		$this->extraDataLoaded = true;

		$dbr = ( $flags & IDBAccessObject::READ_LATEST )
			? $this->repo->getPrimaryDB()
			: $this->repo->getReplicaDB();
		$queryBuilder = FileSelectQueryBuilder::newForFile( $dbr );

		$queryBuilder->where( [ 'img_name' => $this->getName() ] );
		$row = $queryBuilder->caller( $fname )->fetchRow();

		if ( $row ) {
			$this->loadFromRow( $row );
		} else {
			$this->fileExists = false;
		}
	}

	/**
	 * Load lazy file metadata from the DB.
	 * This covers fields that are sometimes not cached.
	 * @stable to override
	 */
	protected function loadExtraFromDB() {
		if ( !$this->title ) {
			return; // Avoid hard failure when the file does not exist. T221812
		}

		$fname = static::class . '::' . __FUNCTION__;

		# Unconditionally set loaded=true, we don't want the accessors constantly rechecking
		$this->extraDataLoaded = true;

		$db = $this->repo->getReplicaDB();
		$fieldMap = $this->loadExtraFieldsWithTimestamp( $db, $fname );
		if ( !$fieldMap ) {
			$db = $this->repo->getPrimaryDB();
			$fieldMap = $this->loadExtraFieldsWithTimestamp( $db, $fname );
		}

		if ( $fieldMap ) {
			if ( isset( $fieldMap['metadata'] ) ) {
				$this->loadMetadataFromDbFieldValue( $db, $fieldMap['metadata'] );
			}
		} else {
			throw new RuntimeException( "Could not find data for image '{$this->getName()}'." );
		}
	}

	/**
	 * @param IReadableDatabase $dbr
	 * @param string $fname
	 * @return string[]|false
	 */
	private function loadExtraFieldsWithTimestamp( IReadableDatabase $dbr, $fname ) {
		$fieldMap = false;

		$queryBuilder = FileSelectQueryBuilder::newForFile( $dbr, [ 'omit-nonlazy' ] );
		$queryBuilder->where( [ 'img_name' => $this->getName() ] )
			->andWhere( [ 'img_timestamp' => $dbr->timestamp( $this->getTimestamp() ) ] );
		$row = $queryBuilder->caller( $fname )->fetchRow();
		if ( $row ) {
			$fieldMap = $this->unprefixRow( $row, 'img_' );
		} else {
			# File may have been uploaded over in the meantime; check the old versions
			$queryBuilder = FileSelectQueryBuilder::newForOldFile( $dbr, [ 'omit-nonlazy' ] );
			$row = $queryBuilder->where( [ 'oi_name' => $this->getName() ] )
				->andWhere( [ 'oi_timestamp' => $dbr->timestamp( $this->getTimestamp() ) ] )
				->caller( __METHOD__ )->fetchRow();
			if ( $row ) {
				$fieldMap = $this->unprefixRow( $row, 'oi_' );
			}
		}

		return $fieldMap;
	}

	/**
	 * @param array|stdClass $row
	 * @param string $prefix
	 * @return array
	 */
	protected function unprefixRow( $row, $prefix = 'img_' ) {
		$array = (array)$row;
		$prefixLength = strlen( $prefix );

		// Double check prefix once
		if ( substr( array_key_first( $array ), 0, $prefixLength ) !== $prefix ) {
			throw new InvalidArgumentException( __METHOD__ . ': incorrect $prefix parameter' );
		}

		$decoded = [];
		foreach ( $array as $name => $value ) {
			$decoded[substr( $name, $prefixLength )] = $value;
		}

		return $decoded;
	}

	/**
	 * Load file metadata from a DB result row
	 * @stable to override
	 *
	 * Passing arbitrary fields in the row and expecting them to be translated
	 * to property names on $this is deprecated since 1.37. Instead, override
	 * loadFromRow(), and clone and unset the extra fields before passing them
	 * to the parent.
	 *
	 * After the deprecation period has passed, extra fields will be ignored,
	 * and the deprecation warning will be removed.
	 *
	 * @param stdClass $row
	 * @param string $prefix
	 */
	public function loadFromRow( $row, $prefix = 'img_' ) {
		$this->dataLoaded = true;

		$unprefixed = $this->unprefixRow( $row, $prefix );

		$this->name = $unprefixed['name'];
		$this->media_type = $unprefixed['media_type'];

		$services = MediaWikiServices::getInstance();
		$this->description = $services->getCommentStore()
			->getComment( "{$prefix}description", $row )->text;

		$this->user = $services->getUserFactory()->newFromAnyId(
			$unprefixed['user'] ?? null,
			$unprefixed['user_text'] ?? null,
			$unprefixed['actor'] ?? null
		);

		$this->timestamp = wfTimestamp( TS_MW, $unprefixed['timestamp'] );

		$this->loadMetadataFromDbFieldValue(
			$this->repo->getReplicaDB(), $unprefixed['metadata'] );

		if ( empty( $unprefixed['major_mime'] ) ) {
			$this->major_mime = 'unknown';
			$this->minor_mime = 'unknown';
			$this->mime = 'unknown/unknown';
		} else {
			if ( !$unprefixed['minor_mime'] ) {
				$unprefixed['minor_mime'] = 'unknown';
			}
			$this->major_mime = $unprefixed['major_mime'];
			$this->minor_mime = $unprefixed['minor_mime'];
			$this->mime = $unprefixed['major_mime'] . '/' . $unprefixed['minor_mime'];
		}

		// Trim zero padding from char/binary field
		$this->sha1 = rtrim( $unprefixed['sha1'], "\0" );

		// Normalize some fields to integer type, per their database definition.
		// Use unary + so that overflows will be upgraded to double instead of
		// being truncated as with intval(). This is important to allow > 2 GiB
		// files on 32-bit systems.
		$this->size = +$unprefixed['size'];
		$this->width = +$unprefixed['width'];
		$this->height = +$unprefixed['height'];
		$this->bits = +$unprefixed['bits'];

		// Check for extra fields (deprecated since MW 1.37)
		$extraFields = array_diff(
			array_keys( $unprefixed ),
			[
				'name', 'media_type', 'description_text', 'description_data',
				'description_cid', 'user', 'user_text', 'actor', 'timestamp',
				'metadata', 'major_mime', 'minor_mime', 'sha1', 'size', 'width',
				'height', 'bits', 'file_id', 'filerevision_id'
			]
		);
		if ( $extraFields ) {
			wfDeprecatedMsg(
				'Passing extra fields (' .
				implode( ', ', $extraFields )
				. ') to ' . __METHOD__ . ' was deprecated in MediaWiki 1.37. ' .
				'Property assignment will be removed in a later version.',
				'1.37' );
			foreach ( $extraFields as $field ) {
				$this->$field = $unprefixed[$field];
			}
		}

		$this->fileExists = true;
	}

	/**
	 * Load file metadata from cache or DB, unless already loaded
	 * @stable to override
	 * @param int $flags
	 */
	public function load( $flags = 0 ) {
		if ( !$this->dataLoaded ) {
			if ( $flags & IDBAccessObject::READ_LATEST ) {
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
	 * @internal
	 */
	public function maybeUpgradeRow() {
		if ( MediaWikiServices::getInstance()->getReadOnlyMode()->isReadOnly() || $this->upgrading ) {
			return;
		}

		$upgrade = false;
		$reserialize = false;
		if ( $this->media_type === null || $this->mime == 'image/svg' ) {
			$upgrade = true;
		} else {
			$handler = $this->getHandler();
			if ( $handler ) {
				$validity = $handler->isFileMetadataValid( $this );
				if ( $validity === MediaHandler::METADATA_BAD ) {
					$upgrade = true;
				} elseif ( $validity === MediaHandler::METADATA_COMPATIBLE
					&& $this->repo->isMetadataUpdateEnabled()
				) {
					$upgrade = true;
				} elseif ( $this->repo->isJsonMetadataEnabled()
					&& $this->repo->isMetadataReserializeEnabled()
				) {
					if ( $this->repo->isSplitMetadataEnabled() && $this->isMetadataOversize() ) {
						$reserialize = true;
					} elseif ( $this->metadataSerializationFormat !== self::MDS_EMPTY &&
						$this->metadataSerializationFormat !== self::MDS_JSON ) {
						$reserialize = true;
					}
				}
			}
		}

		if ( $upgrade || $reserialize ) {
			$this->upgrading = true;
			// Defer updates unless in auto-commit CLI mode
			DeferredUpdates::addCallableUpdate( function () use ( $upgrade ) {
				$this->upgrading = false; // avoid duplicate updates
				try {
					if ( $upgrade ) {
						$this->upgradeRow();
					} else {
						$this->reserializeMetadata();
					}
				} catch ( LocalFileLockError ) {
					// let the other process handle it (or do it next time)
				}
			} );
		}
	}

	/**
	 * @return bool Whether upgradeRow() ran for this object
	 */
	public function getUpgraded() {
		return $this->upgraded;
	}

	/**
	 * This is mostly for the migration period.
	 *
	 * Since 1.44
	 * @return int|false
	 */
	public function getFileIdFromName() {
		if ( !$this->fileId ) {
			$dbw = $this->repo->getPrimaryDB();
			$id = $dbw->newSelectQueryBuilder()
				->select( 'file_id' )
				->from( 'file' )
				->where( [
					'file_name' => $this->getName(),
					'file_deleted' => 0
				] )
				->caller( __METHOD__ )
				->fetchField();
			$this->fileId = $id;
		}

		return $this->fileId;
	}

	/**
	 * This is mostly for the migration period.
	 *
	 * @internal
	 * @return int
	 */
	public function acquireFileIdFromName() {
		$dbw = $this->repo->getPrimaryDB();
		$id = $this->getFileIdFromName();
		if ( $id ) {
			return $id;
		}
		$id = $dbw->newSelectQueryBuilder()
			->select( 'file_id' )
			->from( 'file' )
			->where( [
				'file_name' => $this->getName(),
			] )
			->caller( __METHOD__ )
			->fetchField();
		if ( !$id ) {
			$dbw->newInsertQueryBuilder()
				->insertInto( 'file' )
				->row( [
					'file_name' => $this->getName(),
					// The value will be updated later
					'file_latest' => 0,
					'file_deleted' => 0,
					'file_type' => $this->getFileTypeId(),
				] )
				->caller( __METHOD__ )->execute();
			$insertId = $dbw->insertId();
			if ( !$insertId ) {
				throw new RuntimeException( 'File entry could not be inserted' );
			}
			return $insertId;
		} else {
			// Undelete
			$dbw->newUpdateQueryBuilder()
				->update( 'file' )
				->set( [ 'file_deleted' => 0 ] )
				->where( [ 'file_id' => $id ] )
				->caller( __METHOD__ )->execute();
			return $id;
		}
	}

	protected function getFileTypeId(): int {
		if ( $this->fileTypeId ) {
			return $this->fileTypeId;
		}
		[ $major, $minor ] = self::splitMime( $this->mime );
		$dbw = $this->repo->getPrimaryDB();
		$id = $dbw->newSelectQueryBuilder()
			->select( 'ft_id' )
			->from( 'filetypes' )
			->where( [
				'ft_media_type' => $this->getMediaType(),
				'ft_major_mime' => $major,
				'ft_minor_mime' => $minor,
			] )
			->caller( __METHOD__ )
			->fetchField();
		if ( $id ) {
			$this->fileTypeId = $id;
			return $id;
		}
		$dbw->newInsertQueryBuilder()
			->insertInto( 'filetypes' )
			->row( [
				'ft_media_type' => $this->getMediaType(),
				'ft_major_mime' => $major,
				'ft_minor_mime' => $minor,
			] )
			->caller( __METHOD__ )->execute();

		$id = $dbw->insertId();
		if ( !$id ) {
			throw new RuntimeException( 'File entry could not be inserted' );
		}

		$this->fileTypeId = $id;
		return $id;
	}

	/**
	 * Fix assorted version-related problems with the image row by reloading it from the file
	 * @stable to override
	 */
	public function upgradeRow() {
		$dbw = $this->repo->getPrimaryDB();

		// Make a DB query condition that will fail to match the image row if the
		// image was reuploaded while the upgrade was in process.
		$freshnessCondition = [ 'img_timestamp' => $dbw->timestamp( $this->getTimestamp() ) ];

		$this->loadFromFile();

		# Don't destroy file info of missing files
		if ( !$this->fileExists ) {
			wfDebug( __METHOD__ . ": file does not exist, aborting" );

			return;
		}

		[ $major, $minor ] = self::splitMime( $this->mime );

		wfDebug( __METHOD__ . ': upgrading ' . $this->getName() . " to the current schema" );

		$metadata = $this->getMetadataForDb( $dbw );
		$dbw->newUpdateQueryBuilder()
			->update( 'image' )
			->set( [
				'img_size' => $this->size,
				'img_width' => $this->width,
				'img_height' => $this->height,
				'img_bits' => $this->bits,
				'img_media_type' => $this->media_type,
				'img_major_mime' => $major,
				'img_minor_mime' => $minor,
				'img_metadata' => $metadata,
				'img_sha1' => $this->sha1,
			] )
			->where( [ 'img_name' => $this->getName() ] )
			->andWhere( $freshnessCondition )
			->caller( __METHOD__ )->execute();

		if ( $this->migrationStage & SCHEMA_COMPAT_WRITE_NEW ) {
			$dbw->newUpdateQueryBuilder()
				->update( 'filerevision' )
				->set( [
					'fr_size' => $this->size,
					'fr_width' => $this->width,
					'fr_height' => $this->height,
					'fr_bits' => $this->bits,
					'fr_metadata' => $metadata,
					'fr_sha1' => $this->sha1,
				] )
				->where( [ 'fr_file' => $this->acquireFileIdFromName() ] )
				->andWhere( [ 'fr_timestamp' => $dbw->timestamp( $this->getTimestamp() ) ] )
				->caller( __METHOD__ )->execute();
		}

		$this->invalidateCache();

		$this->upgraded = true; // avoid rework/retries
	}

	/**
	 * Write the metadata back to the database with the current serialization
	 * format.
	 */
	protected function reserializeMetadata() {
		if ( MediaWikiServices::getInstance()->getReadOnlyMode()->isReadOnly() ) {
			return;
		}
		$dbw = $this->repo->getPrimaryDB();
		$metadata = $this->getMetadataForDb( $dbw );
		$dbw->newUpdateQueryBuilder()
			->update( 'image' )
			->set( [ 'img_metadata' => $metadata ] )
			->where( [
				'img_name' => $this->name,
				'img_timestamp' => $dbw->timestamp( $this->timestamp ),
			] )
			->caller( __METHOD__ )->execute();
		if ( $this->migrationStage & SCHEMA_COMPAT_WRITE_NEW ) {
			$dbw->newUpdateQueryBuilder()
				->update( 'filerevision' )
				->set( [ 'fr_metadata' => $metadata ] )
				->where( [ 'fr_file' => $this->acquireFileIdFromName() ] )
				->andWhere( [ 'fr_timestamp' => $dbw->timestamp( $this->getTimestamp() ) ] )
				->caller( __METHOD__ )->execute();
		}
		$this->upgraded = true;
	}

	/**
	 * Set properties in this object to be equal to those given in the
	 * associative array $info. Only cacheable fields can be set.
	 * All fields *must* be set in $info except for getLazyCacheFields().
	 *
	 * If 'mime' is given, it will be split into major_mime/minor_mime.
	 * If major_mime/minor_mime are given, $this->mime will also be set.
	 *
	 * @stable to override
	 * @unstable to call
	 * @param array $info
	 */
	public function setProps( $info ) {
		$this->dataLoaded = true;
		$fields = $this->getCacheFields( '' );
		$fields[] = 'fileExists';

		foreach ( $fields as $field ) {
			if ( isset( $info[$field] ) ) {
				$this->$field = $info[$field];
			}
		}

		// Only our own cache sets these properties, so they both should be present.
		if ( isset( $info['user'] ) &&
			isset( $info['user_text'] ) &&
			$info['user_text'] !== ''
		) {
			$this->user = new UserIdentityValue( $info['user'], $info['user_text'] );
		}

		// Fix up mime fields
		if ( isset( $info['major_mime'] ) ) {
			$this->mime = "{$info['major_mime']}/{$info['minor_mime']}";
		} elseif ( isset( $info['mime'] ) ) {
			$this->mime = $info['mime'];
			[ $this->major_mime, $this->minor_mime ] = self::splitMime( $this->mime );
		}

		if ( isset( $info['metadata'] ) ) {
			if ( is_string( $info['metadata'] ) ) {
				$this->loadMetadataFromString( $info['metadata'] );
			} elseif ( is_array( $info['metadata'] ) ) {
				$this->metadataArray = $info['metadata'];
				if ( isset( $info['metadataBlobs'] ) ) {
					$this->metadataBlobs = $info['metadataBlobs'];
					$this->unloadedMetadataBlobs = array_diff_key(
						$this->metadataBlobs,
						$this->metadataArray
					);
				} else {
					$this->metadataBlobs = [];
					$this->unloadedMetadataBlobs = [];
				}
			} else {
				$logger = LoggerFactory::getInstance( 'LocalFile' );
				$logger->warning( __METHOD__ . ' given invalid metadata of type ' .
					get_debug_type( $info['metadata'] ) );
				$this->metadataArray = [];
			}
			$this->extraDataLoaded = true;
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
	 * Checks if this file exists in its parent repo, as referenced by its
	 * virtual URL.
	 * @stable to override
	 *
	 * @return bool
	 */
	public function isMissing() {
		if ( $this->missing === null ) {
			$fileExists = $this->repo->fileExists( $this->getVirtualUrl() );
			$this->missing = !$fileExists;
		}

		return $this->missing;
	}

	/**
	 * Return the width of the image
	 * @stable to override
	 *
	 * @param int $page
	 * @return int
	 */
	public function getWidth( $page = 1 ) {
		$page = (int)$page;
		if ( $page < 1 ) {
			$page = 1;
		}

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
	 * @stable to override
	 *
	 * @param int $page
	 * @return int
	 */
	public function getHeight( $page = 1 ) {
		$page = (int)$page;
		if ( $page < 1 ) {
			$page = 1;
		}

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
	 * Get short description URL for a file based on the page ID.
	 * @stable to override
	 *
	 * @return string|null
	 * @since 1.27
	 */
	public function getDescriptionShortUrl() {
		if ( !$this->title ) {
			return null; // Avoid hard failure when the file does not exist. T221812
		}

		$pageId = $this->title->getArticleID();

		if ( $pageId ) {
			$url = $this->repo->makeUrl( [ 'curid' => $pageId ] );
			if ( $url !== false ) {
				return $url;
			}
		}
		return null;
	}

	/**
	 * Get handler-specific metadata as a serialized string
	 *
	 * @deprecated since 1.37 use getMetadataArray() or getMetadataItem()
	 * @return string
	 */
	public function getMetadata() {
		$data = $this->getMetadataArray();
		if ( !$data ) {
			return '';
		} elseif ( array_keys( $data ) === [ '_error' ] ) {
			// Legacy error encoding
			return $data['_error'];
		} else {
			return serialize( $this->getMetadataArray() );
		}
	}

	/**
	 * Get unserialized handler-specific metadata
	 *
	 * @since 1.37
	 * @return array
	 */
	public function getMetadataArray(): array {
		$this->load( self::LOAD_ALL );
		if ( $this->unloadedMetadataBlobs ) {
			return $this->getMetadataItems(
				array_unique( array_merge(
					array_keys( $this->metadataArray ),
					array_keys( $this->unloadedMetadataBlobs )
				) )
			);
		}
		return $this->metadataArray;
	}

	public function getMetadataItems( array $itemNames ): array {
		$this->load( self::LOAD_ALL );
		$result = [];
		$addresses = [];
		foreach ( $itemNames as $itemName ) {
			if ( array_key_exists( $itemName, $this->metadataArray ) ) {
				$result[$itemName] = $this->metadataArray[$itemName];
			} elseif ( isset( $this->unloadedMetadataBlobs[$itemName] ) ) {
				$addresses[$itemName] = $this->unloadedMetadataBlobs[$itemName];
			}
		}

		if ( $addresses ) {
			$resultFromBlob = $this->metadataStorageHelper->getMetadataFromBlobStore( $addresses );
			foreach ( $addresses as $itemName => $address ) {
				unset( $this->unloadedMetadataBlobs[$itemName] );
				$value = $resultFromBlob[$itemName] ?? null;
				if ( $value !== null ) {
					$result[$itemName] = $value;
					$this->metadataArray[$itemName] = $value;
				}
			}
		}
		return $result;
	}

	/**
	 * Serialize the metadata array for insertion into img_metadata, oi_metadata
	 * or fa_metadata.
	 *
	 * If metadata splitting is enabled, this may write blobs to the database,
	 * returning their addresses.
	 *
	 * @internal
	 * @param IReadableDatabase $db
	 * @return string|Blob
	 */
	public function getMetadataForDb( IReadableDatabase $db ) {
		$this->load( self::LOAD_ALL );
		if ( !$this->metadataArray && !$this->metadataBlobs ) {
			$s = '';
		} elseif ( $this->repo->isJsonMetadataEnabled() ) {
			$s = $this->getJsonMetadata();
		} else {
			$s = serialize( $this->getMetadataArray() );
		}
		if ( !is_string( $s ) ) {
			throw new RuntimeException( 'Could not serialize image metadata value for DB' );
		}
		return $db->encodeBlob( $s );
	}

	/**
	 * Get metadata in JSON format ready for DB insertion, optionally splitting
	 * items out to BlobStore.
	 *
	 * @return string
	 */
	private function getJsonMetadata() {
		// Directly store data that is not already in BlobStore
		$envelope = [
			'data' => array_diff_key( $this->metadataArray, $this->metadataBlobs )
		];

		// Also store the blob addresses
		if ( $this->metadataBlobs ) {
			$envelope['blobs'] = $this->metadataBlobs;
		}

		[ $s, $blobAddresses ] = $this->metadataStorageHelper->getJsonMetadata( $this, $envelope );

		// Repeated calls to this function should not keep inserting more blobs
		$this->metadataBlobs += $blobAddresses;

		return $s;
	}

	/**
	 * Determine whether the loaded metadata may be a candidate for splitting, by measuring its
	 * serialized size. Helper for maybeUpgradeRow().
	 *
	 * @return bool
	 */
	private function isMetadataOversize() {
		if ( !$this->repo->isSplitMetadataEnabled() ) {
			return false;
		}
		$threshold = $this->repo->getSplitMetadataThreshold();
		$directItems = array_diff_key( $this->metadataArray, $this->metadataBlobs );
		foreach ( $directItems as $value ) {
			if ( strlen( $this->metadataStorageHelper->jsonEncode( $value ) ) > $threshold ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Unserialize a metadata blob which came from the database and store it
	 * in $this.
	 *
	 * @since 1.37
	 * @param IReadableDatabase $db
	 * @param string|Blob $metadataBlob
	 */
	protected function loadMetadataFromDbFieldValue( IReadableDatabase $db, $metadataBlob ) {
		$this->loadMetadataFromString( $db->decodeBlob( $metadataBlob ) );
	}

	/**
	 * Unserialize a metadata string which came from some non-DB source, or is
	 * the return value of IReadableDatabase::decodeBlob().
	 *
	 * @since 1.37
	 * @param string $metadataString
	 */
	protected function loadMetadataFromString( $metadataString ) {
		$this->extraDataLoaded = true;
		$this->metadataArray = [];
		$this->metadataBlobs = [];
		$this->unloadedMetadataBlobs = [];
		$metadataString = (string)$metadataString;
		if ( $metadataString === '' ) {
			$this->metadataSerializationFormat = self::MDS_EMPTY;
			return;
		}
		if ( $metadataString[0] === '{' ) {
			$envelope = $this->metadataStorageHelper->jsonDecode( $metadataString );
			if ( !$envelope ) {
				// Legacy error encoding
				$this->metadataArray = [ '_error' => $metadataString ];
				$this->metadataSerializationFormat = self::MDS_LEGACY;
			} else {
				$this->metadataSerializationFormat = self::MDS_JSON;
				if ( isset( $envelope['data'] ) ) {
					$this->metadataArray = $envelope['data'];
				}
				if ( isset( $envelope['blobs'] ) ) {
					$this->metadataBlobs = $this->unloadedMetadataBlobs = $envelope['blobs'];
				}
			}
		} else {
			// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
			$data = @unserialize( $metadataString );
			if ( !is_array( $data ) ) {
				// Legacy error encoding
				$data = [ '_error' => $metadataString ];
				$this->metadataSerializationFormat = self::MDS_LEGACY;
			} else {
				$this->metadataSerializationFormat = self::MDS_PHP;
			}
			$this->metadataArray = $data;
		}
	}

	/**
	 * @stable to override
	 * @return int
	 */
	public function getBitDepth() {
		$this->load();

		return (int)$this->bits;
	}

	/**
	 * Returns the size of the image file, in bytes
	 * @stable to override
	 * @return int
	 */
	public function getSize() {
		$this->load();

		return $this->size;
	}

	/**
	 * Returns the MIME type of the file.
	 * @stable to override
	 * @return string
	 */
	public function getMimeType() {
		$this->load();

		return $this->mime;
	}

	/**
	 * Returns the type of the media in the file.
	 * Use the value returned by this function with the MEDIATYPE_xxx constants.
	 * @stable to override
	 * @return string
	 */
	public function getMediaType() {
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
	 * @stable to override
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
	 * Get all thumbnail names previously generated for this file.
	 *
	 * This should be called during POST requests only (and other db-writing
	 * contexts) as it may involve connections across multiple data centers
	 * (e.g. both backends of a FileBackendMultiWrite setup).
	 *
	 * @stable to override
	 * @param string|false $archiveName Name of an archive file, default false
	 * @return array First element is the base dir, then files in that base dir.
	 */
	protected function getThumbnails( $archiveName = false ) {
		if ( $archiveName ) {
			$dir = $this->getArchiveThumbPath( $archiveName );
		} else {
			$dir = $this->getThumbPath();
		}

		$backend = $this->repo->getBackend();
		$files = [ $dir ];
		try {
			$iterator = $backend->getFileList( [ 'dir' => $dir, 'forWrite' => true ] );
			if ( $iterator !== null ) {
				foreach ( $iterator as $file ) {
					$files[] = $file;
				}
			}
		} catch ( FileBackendError ) {
		} // suppress (T56674)

		return $files;
	}

	/**
	 * Delete all previously generated thumbnails, refresh metadata in memcached and purge the CDN.
	 * @stable to override
	 *
	 * @param array $options An array potentially with the key forThumbRefresh.
	 *
	 * @note This used to purge old thumbnails by default as well, but doesn't anymore.
	 */
	public function purgeCache( $options = [] ) {
		// Refresh metadata in memcached, but don't touch thumbnails or CDN
		$this->maybeUpgradeRow();
		$this->invalidateCache();

		// Delete thumbnails
		$this->purgeThumbnails( $options );

		// Purge CDN cache for this file
		$hcu = MediaWikiServices::getInstance()->getHtmlCacheUpdater();
		$hcu->purgeUrls(
			$this->getUrl(),
			!empty( $options['forThumbRefresh'] )
				? $hcu::PURGE_PRESEND // just a manual purge
				: $hcu::PURGE_INTENT_TXROUND_REFLECTED
		);
	}

	/**
	 * Delete cached transformed files for an archived version only.
	 * @stable to override
	 * @param string $archiveName Name of the archived file
	 */
	public function purgeOldThumbnails( $archiveName ) {
		// Get a list of old thumbnails
		$thumbs = $this->getThumbnails( $archiveName );

		// Delete thumbnails from storage, and prevent the directory itself from being purged
		$dir = array_shift( $thumbs );
		$this->purgeThumbList( $dir, $thumbs );

		$urls = [];
		foreach ( $thumbs as $thumb ) {
			$urls[] = $this->getArchiveThumbUrl( $archiveName, $thumb );
		}

		// Purge any custom thumbnail caches
		$this->getHookRunner()->onLocalFilePurgeThumbnails( $this, $archiveName, $urls );

		// Purge the CDN
		$hcu = MediaWikiServices::getInstance()->getHtmlCacheUpdater();
		$hcu->purgeUrls( $urls, $hcu::PURGE_PRESEND );
	}

	/**
	 * Delete cached transformed files for the current version only.
	 * @stable to override
	 * @param array $options
	 * @phan-param array{forThumbRefresh?:bool} $options
	 */
	public function purgeThumbnails( $options = [] ) {
		$thumbs = $this->getThumbnails();

		// Delete thumbnails from storage, and prevent the directory itself from being purged
		$dir = array_shift( $thumbs );
		$this->purgeThumbList( $dir, $thumbs );

		// Always purge all files from CDN regardless of handler filters
		$urls = [];
		foreach ( $thumbs as $thumb ) {
			$urls[] = $this->getThumbUrl( $thumb );
		}

		// Give the media handler a chance to filter the file purge list
		if ( !empty( $options['forThumbRefresh'] ) ) {
			$handler = $this->getHandler();
			if ( $handler ) {
				$handler->filterThumbnailPurgeList( $thumbs, $options );
			}
		}

		// Purge any custom thumbnail caches
		$this->getHookRunner()->onLocalFilePurgeThumbnails( $this, false, $urls );

		// Purge the CDN
		$hcu = MediaWikiServices::getInstance()->getHtmlCacheUpdater();
		$hcu->purgeUrls(
			$urls,
			!empty( $options['forThumbRefresh'] )
				? $hcu::PURGE_PRESEND // just a manual purge
				: $hcu::PURGE_INTENT_TXROUND_REFLECTED
		);
	}

	/**
	 * Prerenders a configurable set of thumbnails
	 * @stable to override
	 *
	 * @since 1.28
	 */
	public function prerenderThumbnails() {
		$uploadThumbnailRenderMap = MediaWikiServices::getInstance()
			->getMainConfig()->get( MainConfigNames::UploadThumbnailRenderMap );

		$jobs = [];

		$sizes = $uploadThumbnailRenderMap;
		rsort( $sizes );

		foreach ( $sizes as $size ) {
			if ( $this->isMultipage() ) {
				// (T309114) Only trigger render jobs up to MAX_PAGE_RENDER_JOBS to avoid
				// a flood of jobs for huge files.
				$pageLimit = min( $this->pageCount(), self::MAX_PAGE_RENDER_JOBS );

				$jobs[] = new ThumbnailRenderJob(
					$this->getTitle(),
					[
					'transformParams' => [ 'width' => $size, 'page' => 1 ],
					'enqueueNextPage' => true,
					'pageLimit' => $pageLimit
					]
				);
			} elseif ( $this->isVectorized() || $this->getWidth() > $size ) {
				$jobs[] = new ThumbnailRenderJob(
					$this->getTitle(),
					[ 'transformParams' => [ 'width' => $size ] ]
				);
			}
		}

		if ( $jobs ) {
			MediaWikiServices::getInstance()->getJobQueueGroup()->lazyPush( $jobs );
		}
	}

	/**
	 * Delete a list of thumbnails visible at urls
	 * @stable to override
	 * @param string $dir Base dir of the files.
	 * @param array $files Array of strings: relative filenames (to $dir)
	 */
	protected function purgeThumbList( $dir, $files ) {
		$fileListDebug = strtr(
			var_export( $files, true ),
			[ "\n" => '' ]
		);
		wfDebug( __METHOD__ . ": $fileListDebug" );

		if ( $this->repo->supportsSha1URLs() ) {
			$reference = $this->getSha1();
		} else {
			$reference = $this->getName();
		}

		$purgeList = [];
		foreach ( $files as $file ) {
			# Check that the reference (filename or sha1) is part of the thumb name
			# This is a basic check to avoid erasing unrelated directories
			if ( str_contains( $file, $reference )
				|| str_contains( $file, "-thumbnail" ) // "short" thumb name
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
	 * @stable to override
	 * @param int|null $limit Optional: Limit to number of results
	 * @param string|int|null $start Optional: Timestamp, start from
	 * @param string|int|null $end Optional: Timestamp, end at
	 * @param bool $inc
	 * @return OldLocalFile[] Guaranteed to be in descending order
	 */
	public function getHistory( $limit = null, $start = null, $end = null, $inc = true ) {
		if ( !$this->exists() ) {
			return []; // Avoid hard failure when the file does not exist. T221812
		}

		$dbr = $this->repo->getReplicaDB();
		$oldFileQuery = FileSelectQueryBuilder::newForOldFile( $dbr )->getQueryInfo();

		$tables = $oldFileQuery['tables'];
		$fields = $oldFileQuery['fields'];
		$join_conds = $oldFileQuery['join_conds'];
		$conds = $opts = [];
		$eq = $inc ? '=' : '';
		$conds[] = $dbr->expr( 'oi_name', '=', $this->title->getDBkey() );

		if ( $start ) {
			$conds[] = $dbr->expr( 'oi_timestamp', "<$eq", $dbr->timestamp( $start ) );
		}

		if ( $end ) {
			$conds[] = $dbr->expr( 'oi_timestamp', ">$eq", $dbr->timestamp( $end ) );
		}

		if ( $limit ) {
			$opts['LIMIT'] = $limit;
		}

		// Search backwards for time > x queries
		$order = ( !$start && $end !== null ) ? 'ASC' : 'DESC';
		$opts['ORDER BY'] = "oi_timestamp $order";
		$opts['USE INDEX'] = [ 'oldimage' => 'oi_name_timestamp' ];

		$this->getHookRunner()->onLocalFile__getHistory( $this, $tables, $fields,
			$conds, $opts, $join_conds );

		$res = $dbr->newSelectQueryBuilder()
			->tables( $tables )
			->fields( $fields )
			->conds( $conds )
			->caller( __METHOD__ )
			->options( $opts )
			->joinConds( $join_conds )
			->fetchResultSet();
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
	 * @stable to override
	 * @return stdClass|false
	 */
	public function nextHistoryLine() {
		if ( !$this->exists() ) {
			return false; // Avoid hard failure when the file does not exist. T221812
		}

		# Polymorphic function name to distinguish foreign and local fetches
		$fname = static::class . '::' . __FUNCTION__;

		$dbr = $this->repo->getReplicaDB();

		if ( $this->historyLine == 0 ) { // called for the first time, return line from cur
			$queryBuilder = FileSelectQueryBuilder::newForFile( $dbr );

			$queryBuilder->fields( [ 'oi_archive_name' => $dbr->addQuotes( '' ), 'oi_deleted' => '0' ] )
				->where( [ 'img_name' => $this->title->getDBkey() ] );
			$this->historyRes = $queryBuilder->caller( $fname )->fetchResultSet();

			if ( $this->historyRes->numRows() == 0 ) {
				$this->historyRes = null;

				return false;
			}
		} elseif ( $this->historyLine == 1 ) {
			$queryBuilder = FileSelectQueryBuilder::newForOldFile( $dbr );

			$this->historyRes = $queryBuilder->where( [ 'oi_name' => $this->title->getDBkey() ] )
				->orderBy( 'oi_timestamp', SelectQueryBuilder::SORT_DESC )
				->caller( $fname )->fetchResultSet();
		}
		$this->historyLine++;

		return $this->historyRes->fetchObject();
	}

	/**
	 * Reset the history pointer to the first element of the history
	 * @stable to override
	 */
	public function resetHistory() {
		$this->historyLine = 0;

		if ( $this->historyRes !== null ) {
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
	 * @param int $flags Flags for publish()
	 * @param array|false $props File properties, if known. This can be used to
	 *   reduce the upload time when uploading virtual URLs for which the file
	 *   info is already known
	 * @param string|false $timestamp Timestamp for img_timestamp, or false to use the
	 *   current time. Can be in any format accepted by ConvertibleTimestamp.
	 * @param Authority|null $uploader object or null to use the context authority
	 * @param string[] $tags Change tags to add to the log entry and page revision.
	 *   (This doesn't check $uploader's permissions.)
	 * @param bool $createNullRevision Set to false to avoid creation of a null revision on file
	 *   upload, see T193621
	 * @param bool $revert If this file upload is a revert
	 * @return Status On success, the value member contains the
	 *     archive name, or an empty string if it was a new file.
	 */
	public function upload( $src, $comment, $pageText, $flags = 0, $props = false,
		$timestamp = false, ?Authority $uploader = null, $tags = [],
		$createNullRevision = true, $revert = false
	) {
		if ( $this->getRepo()->getReadOnlyReason() !== false ) {
			return $this->readOnlyFatalStatus();
		} elseif ( MediaWikiServices::getInstance()->getRevisionStore()->isReadOnly() ) {
			// Check this in advance to avoid writing to FileBackend and the file tables,
			// only to fail on insert the revision due to the text store being unavailable.
			return $this->readOnlyFatalStatus();
		}

		$srcPath = ( $src instanceof FSFile ) ? $src->getPath() : $src;
		if ( !$props ) {
			if ( FileRepo::isVirtualUrl( $srcPath )
				|| FileBackend::isStoragePath( $srcPath )
			) {
				$props = $this->repo->getFileProps( $srcPath );
			} else {
				$mwProps = new MWFileProps( MediaWikiServices::getInstance()->getMimeAnalyzer() );
				$props = $mwProps->getPropsFromPath( $srcPath, true );
			}
		}

		$options = [];
		$handler = MediaHandler::getHandler( $props['mime'] );
		if ( $handler ) {
			if ( is_string( $props['metadata'] ) ) {
				// This supports callers directly fabricating a metadata
				// property using serialize(). Normally the metadata property
				// comes from MWFileProps, in which case it won't be a string.
				// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
				$metadata = @unserialize( $props['metadata'] );
			} else {
				$metadata = $props['metadata'];
			}

			if ( is_array( $metadata ) ) {
				$options['headers'] = $handler->getContentHeaders( $metadata );
			}
		} else {
			$options['headers'] = [];
		}

		// Trim spaces on user supplied text
		$comment = trim( $comment );

		$status = $this->publish( $src, $flags, $options );

		if ( $status->successCount >= 2 ) {
			// There will be a copy+(one of move,copy,store).
			// The first succeeding does not commit us to updating the DB
			// since it simply copied the current version to a timestamped file name.
			// It is only *preferable* to avoid leaving such files orphaned.
			// Once the second operation goes through, then the current version was
			// updated and we must therefore update the DB too.
			$oldver = $status->value;

			$uploadStatus = $this->recordUpload3(
				$oldver,
				$comment,
				$pageText,
				$uploader ?? RequestContext::getMain()->getAuthority(),
				$props,
				$timestamp,
				$tags,
				$createNullRevision,
				$revert
			);
			if ( !$uploadStatus->isOK() ) {
				if ( $uploadStatus->hasMessage( 'filenotfound' ) ) {
					// update filenotfound error with more specific path
					$status->fatal( 'filenotfound', $srcPath );
				} else {
					$status->merge( $uploadStatus );
				}
			}
		}

		return $status;
	}

	/**
	 * Record a file upload in the upload log and the image table (version 3)
	 * @since 1.35
	 * @stable to override
	 * @param string $oldver
	 * @param string $comment
	 * @param string $pageText File description page text (only used for new uploads)
	 * @param Authority $performer
	 * @param array|false $props
	 * @param string|false $timestamp Can be in any format accepted by ConvertibleTimestamp
	 * @param string[] $tags
	 * @param bool $createNullRevision Set to false to avoid creation of a null revision on file
	 *   upload, see T193621
	 * @param bool $revert If this file upload is a revert
	 * @return Status
	 */
	public function recordUpload3(
		string $oldver,
		string $comment,
		string $pageText,
		Authority $performer,
		$props = false,
		$timestamp = false,
		$tags = [],
		bool $createNullRevision = true,
		bool $revert = false
	): Status {
		$dbw = $this->repo->getPrimaryDB();

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
		$props['timestamp'] = wfTimestamp( TS_MW, $timestamp ); // DB -> TS_MW
		$this->setProps( $props );

		# Fail now if the file isn't there
		if ( !$this->fileExists ) {
			wfDebug( __METHOD__ . ": File " . $this->getRel() . " went missing!" );

			return Status::newFatal( 'filenotfound', $this->getRel() );
		}

		$mimeAnalyzer = MediaWikiServices::getInstance()->getMimeAnalyzer();
		if ( !$mimeAnalyzer->isValidMajorMimeType( $this->major_mime ) ) {
			$this->major_mime = 'unknown';
		}

		$actorNormalizaton = MediaWikiServices::getInstance()->getActorNormalization();

		// T391473: File uploads can involve moving a lot of bytes around. Sometimes in
		// that time the DB connection can timeout. Normally this is automatically
		// reconnected, but reconnection does not work inside atomic sections.
		// Ping the DB to ensure it is still there prior to entering the atomic
		// section. TODO: Refactor upload jobs to be smarter about implicit transactions.
		$dbw->ping();
		$dbw->startAtomic( __METHOD__ );

		$actorId = $actorNormalizaton->acquireActorId( $performer->getUser(), $dbw );
		$this->user = $performer->getUser();

		# Test to see if the row exists using INSERT IGNORE
		# This avoids race conditions by locking the row until the commit, and also
		# doesn't deadlock. SELECT FOR UPDATE causes a deadlock for every race condition.
		$commentStore = MediaWikiServices::getInstance()->getCommentStore();
		$commentFields = $commentStore->insert( $dbw, 'img_description', $comment );
		$actorFields = [ 'img_actor' => $actorId ];
		$dbw->newInsertQueryBuilder()
			->insertInto( 'image' )
			->ignore()
			->row( [
				'img_name' => $this->getName(),
				'img_size' => $this->size,
				'img_width' => intval( $this->width ),
				'img_height' => intval( $this->height ),
				'img_bits' => $this->bits,
				'img_media_type' => $this->media_type,
				'img_major_mime' => $this->major_mime,
				'img_minor_mime' => $this->minor_mime,
				'img_timestamp' => $dbw->timestamp( $timestamp ),
				'img_metadata' => $this->getMetadataForDb( $dbw ),
				'img_sha1' => $this->sha1
			] + $commentFields + $actorFields )
			->caller( __METHOD__ )->execute();
		$reupload = ( $dbw->affectedRows() == 0 );

		$latestFileRevId = null;
		if ( $this->migrationStage & SCHEMA_COMPAT_WRITE_NEW ) {
			if ( $reupload ) {
				$latestFileRevId = $dbw->newSelectQueryBuilder()
					->select( 'fr_id' )
					->from( 'filerevision' )
					->where( [ 'fr_file' => $this->acquireFileIdFromName() ] )
					->orderBy( 'fr_timestamp', 'DESC' )
					->caller( __METHOD__ )
					->fetchField();
			}
			$commentFieldsNew = $commentStore->insert( $dbw, 'fr_description', $comment );
			$dbw->newInsertQueryBuilder()
				->insertInto( 'filerevision' )
				->row( [
						'fr_file' => $this->acquireFileIdFromName(),
						'fr_size' => $this->size,
						'fr_width' => intval( $this->width ),
						'fr_height' => intval( $this->height ),
						'fr_bits' => $this->bits,
						'fr_actor' => $actorId,
						'fr_deleted' => 0,
						'fr_timestamp' => $dbw->timestamp( $timestamp ),
						'fr_metadata' => $this->getMetadataForDb( $dbw ),
						'fr_sha1' => $this->sha1
					] + $commentFieldsNew )
				->caller( __METHOD__ )->execute();
			$dbw->newUpdateQueryBuilder()
				->update( 'file' )
				->set( [ 'file_latest' => $dbw->insertId() ] )
				->where( [ 'file_id' => $this->getFileIdFromName() ] )
				->caller( __METHOD__ )->execute();
		}

		if ( $reupload ) {
			$row = $dbw->newSelectQueryBuilder()
				->select( [ 'img_timestamp', 'img_sha1' ] )
				->from( 'image' )
				->where( [ 'img_name' => $this->getName() ] )
				->caller( __METHOD__ )->fetchRow();

			if ( $row && $row->img_sha1 === $this->sha1 ) {
				$dbw->endAtomic( __METHOD__ );
				wfDebug( __METHOD__ . ": File " . $this->getRel() . " already exists!" );
				$title = Title::newFromText( $this->getName(), NS_FILE );
				return Status::newFatal( 'fileexists-no-change', $title->getPrefixedText() );
			}

			if ( $allowTimeKludge ) {
				# Use LOCK IN SHARE MODE to ignore any transaction snapshotting
				$lUnixtime = $row ? (int)wfTimestamp( TS_UNIX, $row->img_timestamp ) : false;
				# Avoid a timestamp that is not newer than the last version
				# TODO: the image/oldimage tables should be like page/revision with an ID field
				if ( $lUnixtime && (int)wfTimestamp( TS_UNIX, $timestamp ) <= $lUnixtime ) {
					sleep( 1 ); // fast enough re-uploads would go far in the future otherwise
					$timestamp = $dbw->timestamp( $lUnixtime + 1 );
					$this->timestamp = wfTimestamp( TS_MW, $timestamp ); // DB -> TS_MW
				}
			}

			$tables = [ 'image' ];
			$fields = [
				'oi_name' => 'img_name',
				'oi_archive_name' => $dbw->addQuotes( $oldver ),
				'oi_size' => 'img_size',
				'oi_width' => 'img_width',
				'oi_height' => 'img_height',
				'oi_bits' => 'img_bits',
				'oi_description_id' => 'img_description_id',
				'oi_timestamp' => 'img_timestamp',
				'oi_metadata' => 'img_metadata',
				'oi_media_type' => 'img_media_type',
				'oi_major_mime' => 'img_major_mime',
				'oi_minor_mime' => 'img_minor_mime',
				'oi_sha1' => 'img_sha1',
				'oi_actor' => 'img_actor',
			];

			if ( ( $this->migrationStage & SCHEMA_COMPAT_WRITE_NEW ) && $latestFileRevId && $oldver ) {
				$dbw->newUpdateQueryBuilder()
					->update( 'filerevision' )
					->set( [ 'fr_archive_name' => $oldver ] )
					->where( [ 'fr_id' => $latestFileRevId ] )
					->caller( __METHOD__ )->execute();
			}

			$joins = [];
			# (T36993) Note: $oldver can be empty here, if the previous
			# version of the file was broken. Allow registration of the new
			# version to continue anyway, because that's better than having
			# an image that's not fixable by user operations.
			# Collision, this is an update of a file
			# Insert previous contents into oldimage
			$dbw->insertSelect( 'oldimage', $tables, $fields,
				[ 'img_name' => $this->getName() ], __METHOD__, [], [], $joins );

			# Update the current image row
			$dbw->newUpdateQueryBuilder()
				->update( 'image' )
				->set( [
					'img_size' => $this->size,
					'img_width' => intval( $this->width ),
					'img_height' => intval( $this->height ),
					'img_bits' => $this->bits,
					'img_media_type' => $this->media_type,
					'img_major_mime' => $this->major_mime,
					'img_minor_mime' => $this->minor_mime,
					'img_timestamp' => $dbw->timestamp( $timestamp ),
					'img_metadata' => $this->getMetadataForDb( $dbw ),
					'img_sha1' => $this->sha1
				] + $commentFields + $actorFields )
				->where( [ 'img_name' => $this->getName() ] )
				->caller( __METHOD__ )->execute();
		}

		$descTitle = $this->getTitle();
		$descId = $descTitle->getArticleID();
		$wikiPage = MediaWikiServices::getInstance()->getWikiPageFactory()->newFromTitle( $descTitle );
		if ( !$wikiPage instanceof WikiFilePage ) {
			throw new UnexpectedValueException( 'Cannot obtain instance of WikiFilePage for ' . $this->getName()
				. ', got instance of ' . get_class( $wikiPage ) );
		}
		$wikiPage->setFile( $this );

		// Determine log action. If reupload is done by reverting, use a special log_action.
		if ( $revert ) {
			$logAction = 'revert';
		} elseif ( $reupload ) {
			$logAction = 'overwrite';
		} else {
			$logAction = 'upload';
		}
		// Add the log entry...
		$logEntry = new ManualLogEntry( 'upload', $logAction );
		$logEntry->setTimestamp( $this->timestamp );
		$logEntry->setPerformer( $performer->getUser() );
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
			if ( $createNullRevision ) {
				$services = MediaWikiServices::getInstance();
				// Use own context to get the action text in content language
				$formatter = $services->getLogFormatterFactory()->newFromEntry( $logEntry );
				$formatter->setContext( RequestContext::newExtraneousContext( $descTitle ) );
				$editSummary = $formatter->getPlainActionText();

				$nullRevRecord = $wikiPage->newPageUpdater( $performer->getUser() )
					->setCause( PageUpdater::CAUSE_UPLOAD )
					->saveDummyRevision( $editSummary, EDIT_SILENT );

				// Associate null revision id
				$logEntry->setAssociatedRevId( $nullRevRecord->getId() );
			}

			$newPageContent = null;
		} else {
			// Make the description page and RC log entry post-commit
			$newPageContent = ContentHandler::makeContent( $pageText, $descTitle );
		}

		// NOTE: Even after ending this atomic section, we are probably still in the implicit
		// transaction started by any prior master query in the request. We cannot yet safely
		// schedule jobs, see T263301.
		$dbw->endAtomic( __METHOD__ );
		$fname = __METHOD__;

		# Do some cache purges after final commit so that:
		# a) Changes are more likely to be seen post-purge
		# b) They won't cause rollback of the log publish/update above
		$purgeUpdate = new AutoCommitUpdate(
			$dbw,
			__METHOD__,
			function () use (
				$reupload, $wikiPage, $newPageContent, $comment, $performer,
				$logEntry, $logId, $descId, $tags, $fname
			) {
				# Update memcache after the commit
				$this->invalidateCache();

				$updateLogPage = false;
				if ( $newPageContent ) {
					# New file page; create the description page.
					# There's already a log entry, so don't make a second RC entry
					# CDN and file cache for the description page are purged by doUserEditContent.
					$revRecord = $wikiPage->newPageUpdater( $performer )
						->setCause( PageUpdater::CAUSE_UPLOAD )
						->setContent( SlotRecord::MAIN, $newPageContent )
						->saveRevision( $comment, EDIT_NEW | EDIT_SUPPRESS_RC );

					if ( $revRecord ) {
						// Associate new page revision id
						$logEntry->setAssociatedRevId( $revRecord->getId() );

						// This relies on the resetArticleID() call in WikiPage::insertOn(),
						// which is triggered on $descTitle by doUserEditContent() above.
						$updateLogPage = $revRecord->getPageId();
					}
				} else {
					# Existing file page: invalidate description page cache
					$title = $wikiPage->getTitle();
					$title->invalidateCache();
					$hcu = MediaWikiServices::getInstance()->getHtmlCacheUpdater();
					$hcu->purgeTitleUrls( $title, $hcu::PURGE_INTENT_TXROUND_REFLECTED );
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
				$this->getRepo()->getPrimaryDB()->newUpdateQueryBuilder()
					->update( 'logging' )
					->set( $update )
					->where( [ 'log_id' => $logId ] )
					->caller( $fname )->execute();

				$this->getRepo()->getPrimaryDB()->newInsertQueryBuilder()
					->insertInto( 'log_search' )
					->row( [
						'ls_field' => 'associated_rev_id',
						'ls_value' => (string)$logEntry->getAssociatedRevId(),
						'ls_log_id' => $logId,
					] )
					->caller( $fname )->execute();

				# Add change tags, if any
				if ( $tags ) {
					$logEntry->addTags( $tags );
				}

				# Uploads can be patrolled
				$logEntry->setIsPatrollable( true );

				# Now that the log entry is up-to-date, make an RC entry.
				$logEntry->publish( $logId );

				# Run hook for other updates (typically more cache purging)
				$this->getHookRunner()->onFileUpload( $this, $reupload, !$newPageContent );

				if ( $reupload ) {
					# Delete old thumbnails
					$this->purgeThumbnails();
					# Remove the old file from the CDN cache
					$hcu = MediaWikiServices::getInstance()->getHtmlCacheUpdater();
					$hcu->purgeUrls( $this->getUrl(), $hcu::PURGE_INTENT_TXROUND_REFLECTED );
				} else {
					# Update backlink pages pointing to this title if created
					$blcFactory = MediaWikiServices::getInstance()->getBacklinkCacheFactory();
					LinksUpdate::queueRecursiveJobsForTable(
						$this->getTitle(),
						'imagelinks',
						'upload-image',
						$performer->getUser()->getName(),
						$blcFactory->getBacklinkCache( $this->getTitle() )
					);
				}

				$this->prerenderThumbnails();
			}
		);

		# Invalidate cache for all pages using this file
		$cacheUpdateJob = HTMLCacheUpdateJob::newForBacklinks(
			$this->getTitle(),
			'imagelinks',
			[ 'causeAction' => 'file-upload', 'causeAgent' => $performer->getUser()->getName() ]
		);

		// NOTE: We are probably still in the implicit transaction started by DBO_TRX. We should
		// only schedule jobs after that transaction was committed, so a job queue failure
		// doesn't cause the upload to fail (T263301). Also, we should generally not schedule any
		// Jobs or the DeferredUpdates that assume the update is complete until after the
		// transaction has been committed and we are sure that the upload was indeed successful.
		$dbw->onTransactionCommitOrIdle( static function () use ( $reupload, $purgeUpdate, $cacheUpdateJob ) {
			DeferredUpdates::addUpdate( $purgeUpdate, DeferredUpdates::PRESEND );

			if ( !$reupload ) {
				// This is a new file, so update the image count
				DeferredUpdates::addUpdate( SiteStatsUpdate::factory( [ 'images' => 1 ] ) );
			}

			MediaWikiServices::getInstance()->getJobQueueGroup()->lazyPush( $cacheUpdateJob );
		}, __METHOD__ );

		return Status::newGood();
	}

	/**
	 * Move or copy a file to its public location. If a file exists at the
	 * destination, move it to an archive. Returns a Status object with
	 * the archive name in the "value" member on success.
	 *
	 * The archive name should be passed through to recordUpload for database
	 * registration.
	 *
	 * @stable to override
	 * @param string|FSFile $src Local filesystem path or virtual URL to the source image
	 * @param int $flags A bitwise combination of:
	 *     File::DELETE_SOURCE    Delete the source file, i.e. move rather than copy
	 * @param array $options Optional additional parameters
	 * @return Status On success, the value member contains the
	 *     archive name, or an empty string if it was a new file.
	 */
	public function publish( $src, $flags = 0, array $options = [] ) {
		return $this->publishTo( $src, $this->getRel(), $flags, $options );
	}

	/**
	 * Move or copy a file to a specified location. Returns a Status
	 * object with the archive name in the "value" member on success.
	 *
	 * The archive name should be passed through to recordUpload for database
	 * registration.
	 *
	 * @stable to override
	 * @param string|FSFile $src Local filesystem path or virtual URL to the source image
	 * @param string $dstRel Target relative path
	 * @param int $flags A bitwise combination of:
	 *     File::DELETE_SOURCE    Delete the source file, i.e. move rather than copy
	 * @param array $options Optional additional parameters
	 * @return Status On success, the value member contains the
	 *     archive name, or an empty string if it was a new file.
	 */
	protected function publishTo( $src, $dstRel, $flags = 0, array $options = [] ) {
		$srcPath = ( $src instanceof FSFile ) ? $src->getPath() : $src;

		$repo = $this->getRepo();
		if ( $repo->getReadOnlyReason() !== false ) {
			return $this->readOnlyFatalStatus();
		}

		$status = $this->acquireFileLock();
		if ( !$status->isOK() ) {
			return $status;
		}

		if ( $this->isOld() ) {
			$archiveRel = $dstRel;
			$archiveName = basename( $archiveRel );
		} else {
			$archiveName = wfTimestamp( TS_MW ) . '!' . $this->getName();
			$archiveRel = $this->getArchiveRel( $archiveName );
		}

		if ( $repo->hasSha1Storage() ) {
			$sha1 = FileRepo::isVirtualUrl( $srcPath )
				? $repo->getFileSha1( $srcPath )
				: FSFile::getSha1Base36FromPath( $srcPath );
			/** @var FileBackendDBRepoWrapper $wrapperBackend */
			$wrapperBackend = $repo->getBackend();
			'@phan-var FileBackendDBRepoWrapper $wrapperBackend';
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

		$this->releaseFileLock();
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
	 * @stable to override
	 * @param Title $target New file name
	 * @return Status
	 */
	public function move( $target ) {
		$localRepo = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo();
		if ( $this->getRepo()->getReadOnlyReason() !== false ) {
			return $this->readOnlyFatalStatus();
		}

		wfDebugLog( 'imagemove', "Got request to move {$this->name} to " . $target->getText() );
		$batch = new LocalFileMoveBatch( $this, $target );

		$status = $batch->addCurrent();
		if ( !$status->isOK() ) {
			return $status;
		}
		$archiveNames = $batch->addOlds();
		$status = $batch->execute();

		wfDebugLog( 'imagemove', "Finished moving {$this->name}" );

		// Purge the source and target files outside the transaction...
		$oldTitleFile = $localRepo->newFile( $this->title );
		$newTitleFile = $localRepo->newFile( $target );
		DeferredUpdates::addUpdate(
			new AutoCommitUpdate(
				$this->getRepo()->getPrimaryDB(),
				__METHOD__,
				static function () use ( $oldTitleFile, $newTitleFile, $archiveNames ) {
					$oldTitleFile->purgeEverything();
					foreach ( $archiveNames as $archiveName ) {
						/** @var OldLocalFile $oldTitleFile */
						'@phan-var OldLocalFile $oldTitleFile';
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
			$this->name = null;
			$this->hashPath = null;
		}

		return $status;
	}

	/**
	 * Delete all versions of the file.
	 *
	 * @since 1.35
	 *
	 * Moves the files into an archive directory (or deletes them)
	 * and removes the database rows.
	 *
	 * Cache purging is done; logging is caller's responsibility.
	 * @stable to override
	 *
	 * @param string $reason
	 * @param UserIdentity $user
	 * @param bool $suppress
	 * @return Status
	 */
	public function deleteFile( $reason, UserIdentity $user, $suppress = false ) {
		if ( $this->getRepo()->getReadOnlyReason() !== false ) {
			return $this->readOnlyFatalStatus();
		}

		$batch = new LocalFileDeleteBatch( $this, $user, $reason, $suppress );

		$batch->addCurrent();
		// Get old version relative paths
		$archiveNames = $batch->addOlds();
		$status = $batch->execute();

		if ( $status->isOK() ) {
			DeferredUpdates::addUpdate( SiteStatsUpdate::factory( [ 'images' => -1 ] ) );
		}

		// To avoid slow purges in the transaction, move them outside...
		DeferredUpdates::addUpdate(
			new AutoCommitUpdate(
				$this->getRepo()->getPrimaryDB(),
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

		$hcu = MediaWikiServices::getInstance()->getHtmlCacheUpdater();
		$hcu->purgeUrls( $purgeUrls, $hcu::PURGE_INTENT_TXROUND_REFLECTED );

		return $status;
	}

	/**
	 * Delete an old version of the file.
	 *
	 * @since 1.35
	 * @stable to override
	 *
	 * Moves the file into an archive directory (or deletes it)
	 * and removes the database row.
	 *
	 * Cache purging is done; logging is caller's responsibility.
	 *
	 * @param string $archiveName
	 * @param string $reason
	 * @param UserIdentity $user
	 * @param bool $suppress
	 * @return Status
	 */
	public function deleteOldFile( $archiveName, $reason, UserIdentity $user, $suppress = false ) {
		if ( $this->getRepo()->getReadOnlyReason() !== false ) {
			return $this->readOnlyFatalStatus();
		}

		$batch = new LocalFileDeleteBatch( $this, $user, $reason, $suppress );

		$batch->addOld( $archiveName );
		$status = $batch->execute();

		$this->purgeOldThumbnails( $archiveName );
		if ( $status->isOK() ) {
			$this->purgeDescription();
		}

		$url = $this->getArchiveUrl( $archiveName );
		$hcu = MediaWikiServices::getInstance()->getHtmlCacheUpdater();
		$hcu->purgeUrls( $url, $hcu::PURGE_INTENT_TXROUND_REFLECTED );

		return $status;
	}

	/**
	 * Restore all or specified deleted revisions to the given file.
	 * Permissions and logging are left to the caller.
	 *
	 * May throw database exceptions on error.
	 * @stable to override
	 *
	 * @param int[] $versions Set of record ids of deleted items to restore,
	 *   or empty to restore all revisions.
	 * @param bool $unsuppress
	 * @return Status
	 */
	public function restore( $versions = [], $unsuppress = false ) {
		if ( $this->getRepo()->getReadOnlyReason() !== false ) {
			return $this->readOnlyFatalStatus();
		}

		$batch = new LocalFileRestoreBatch( $this, $unsuppress );

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

		return $status;
	}

	/** isMultipage inherited */
	/** pageCount inherited */
	/** scaleHeight inherited */
	/** getImageSize inherited */

	/**
	 * Get the URL of the file description page.
	 * @stable to override
	 * @return string|false
	 */
	public function getDescriptionUrl() {
		// Avoid hard failure when the file does not exist. T221812
		return $this->title ? $this->title->getLocalURL() : false;
	}

	/**
	 * Get the HTML text of the description page
	 * This is not used by ImagePage for local files, since (among other things)
	 * it skips the parser cache.
	 * @stable to override
	 *
	 * @param Language|null $lang What language to get description in (Optional)
	 * @return string|false
	 */
	public function getDescriptionText( ?Language $lang = null ) {
		if ( !$this->title ) {
			return false; // Avoid hard failure when the file does not exist. T221812
		}

		$services = MediaWikiServices::getInstance();
		$page = $services->getPageStore()->getPageByReference( $this->getTitle() );
		if ( !$page ) {
			return false;
		}

		if ( $lang ) {
			$parserOptions = ParserOptions::newFromUserAndLang(
				RequestContext::getMain()->getUser(),
				$lang
			);
		} else {
			$parserOptions = ParserOptions::newFromContext( RequestContext::getMain() );
		}

		$parseStatus = $services->getParserOutputAccess()
			->getParserOutput( $page, $parserOptions );

		if ( !$parseStatus->isGood() ) {
			// Rendering failed.
			return false;
		}
		// TODO T371004 move runOutputPipeline out of $parserOutput
		return $parseStatus->getValue()->runOutputPipeline( $parserOptions, [] )->getContentHolderText();
	}

	/**
	 * @since 1.37
	 * @stable to override
	 * @param int $audience
	 * @param Authority|null $performer
	 * @return UserIdentity|null
	 */
	public function getUploader( int $audience = self::FOR_PUBLIC, ?Authority $performer = null ): ?UserIdentity {
		$this->load();
		if ( $audience === self::FOR_PUBLIC && $this->isDeleted( self::DELETED_USER ) ) {
			return null;
		} elseif ( $audience === self::FOR_THIS_USER && !$this->userCan( self::DELETED_USER, $performer ) ) {
			return null;
		} else {
			return $this->user;
		}
	}

	/**
	 * @stable to override
	 * @param int $audience
	 * @param Authority|null $performer
	 * @return string
	 */
	public function getDescription( $audience = self::FOR_PUBLIC, ?Authority $performer = null ) {
		$this->load();
		if ( $audience == self::FOR_PUBLIC && $this->isDeleted( self::DELETED_COMMENT ) ) {
			return '';
		} elseif ( $audience == self::FOR_THIS_USER && !$this->userCan( self::DELETED_COMMENT, $performer ) ) {
			return '';
		} else {
			return $this->description;
		}
	}

	/**
	 * @stable to override
	 * @return string|false TS_MW timestamp, a string with 14 digits
	 */
	public function getTimestamp() {
		$this->load();

		return $this->timestamp;
	}

	/**
	 * @stable to override
	 * @return string|false
	 */
	public function getDescriptionTouched() {
		if ( !$this->exists() ) {
			return false; // Avoid hard failure when the file does not exist. T221812
		}

		// The DB lookup might return false, e.g. if the file was just deleted, or the shared DB repo
		// itself gets it from elsewhere. To avoid repeating the DB lookups in such a case, we
		// need to differentiate between null (uninitialized) and false (failed to load).
		if ( $this->descriptionTouched === null ) {
			$touched = $this->repo->getReplicaDB()->newSelectQueryBuilder()
				->select( 'page_touched' )
				->from( 'page' )
				->where( [ 'page_namespace' => $this->title->getNamespace() ] )
				->andWhere( [ 'page_title' => $this->title->getDBkey() ] )
				->caller( __METHOD__ )->fetchField();
			$this->descriptionTouched = $touched ? wfTimestamp( TS_MW, $touched ) : false;
		}

		return $this->descriptionTouched;
	}

	/**
	 * @stable to override
	 * @return string|false
	 */
	public function getSha1() {
		$this->load();
		return $this->sha1;
	}

	/**
	 * @return bool Whether to cache in RepoGroup (this avoids OOMs)
	 */
	public function isCacheable() {
		$this->load();

		// If extra data (metadata) was not loaded then it must have been large
		return $this->extraDataLoaded
			&& strlen( serialize( $this->metadataArray ) ) <= self::CACHE_FIELD_MAX_LEN;
	}

	/**
	 * Acquire an exclusive lock on the file, indicating an intention to write
	 * to the file backend.
	 *
	 * @param float|int $timeout The timeout in seconds
	 * @return Status
	 * @since 1.28
	 */
	public function acquireFileLock( $timeout = 0 ) {
		return Status::wrap( $this->getRepo()->getBackend()->lockFiles(
			[ $this->getPath() ], LockManager::LOCK_EX, $timeout
		) );
	}

	/**
	 * Release a lock acquired with acquireFileLock().
	 *
	 * @return Status
	 * @since 1.28
	 */
	public function releaseFileLock() {
		return Status::wrap( $this->getRepo()->getBackend()->unlockFiles(
			[ $this->getPath() ], LockManager::LOCK_EX
		) );
	}

	/**
	 * Start an atomic DB section and lock the image for update
	 * or increments a reference counter if the lock is already held
	 *
	 * This method should not be used outside of LocalFile/LocalFile*Batch
	 *
	 * @deprecated since 1.38 Use acquireFileLock()
	 * @throws LocalFileLockError Throws an error if the lock was not acquired
	 * @return bool Whether the file lock owns/spawned the DB transaction
	 */
	public function lock() {
		if ( !$this->locked ) {
			$logger = LoggerFactory::getInstance( 'LocalFile' );

			$dbw = $this->repo->getPrimaryDB();
			$makesTransaction = !$dbw->trxLevel();
			$dbw->startAtomic( self::ATOMIC_SECTION_LOCK );
			// T56736: use simple lock to handle when the file does not exist.
			// SELECT FOR UPDATE prevents changes, not other SELECTs with FOR UPDATE.
			// Also, that would cause contention on INSERT of similarly named rows.
			$status = $this->acquireFileLock( 10 ); // represents all versions of the file
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
	 * The commit and lock release will happen when no atomic sections are active, which
	 * may happen immediately or at some point after calling this
	 *
	 * @deprecated since 1.38 Use releaseFileLock()
	 */
	public function unlock() {
		if ( $this->locked ) {
			--$this->locked;
			if ( !$this->locked ) {
				$dbw = $this->repo->getPrimaryDB();
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
	public function __destruct() {
		$this->unlock();
	}
}

/** @deprecated class alias since 1.44 */
class_alias( LocalFile::class, 'LocalFile' );
