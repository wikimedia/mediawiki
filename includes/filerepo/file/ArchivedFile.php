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

use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Authority;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\User\UserIdentity;
use Wikimedia\Rdbms\Blob;
use Wikimedia\Rdbms\IDatabase;

/**
 * Deleted file in the 'filearchive' table.
 *
 * @stable to extend
 * @ingroup FileAbstraction
 */
class ArchivedFile {

	// Audience options for ::getDescription() and ::getUploader()
	public const FOR_PUBLIC = 1;
	public const FOR_THIS_USER = 2;
	public const RAW = 3;

	/** @var string Metadata serialization: empty string. This is a compact non-legacy format. */
	private const MDS_EMPTY = 'empty';

	/** @var string Metadata serialization: some other string */
	private const MDS_LEGACY = 'legacy';

	/** @var string Metadata serialization: PHP serialize() */
	private const MDS_PHP = 'php';

	/** @var string Metadata serialization: JSON */
	private const MDS_JSON = 'json';

	/** @var int Filearchive row ID */
	private $id;

	/** @var string|false File name */
	private $name;

	/** @var string FileStore storage group */
	private $group;

	/** @var string FileStore SHA-1 key */
	private $key;

	/** @var int File size in bytes */
	private $size;

	/** @var int Size in bytes */
	private $bits;

	/** @var int Width */
	private $width;

	/** @var int Height */
	private $height;

	/** @var array Unserialized metadata */
	protected $metadataArray = [];

	/** @var bool Whether or not lazy-loaded data has been loaded from the database */
	protected $extraDataLoaded = false;

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

	/** @var string MIME type */
	private $mime;

	/** @var string Media type */
	private $media_type;

	/** @var string Upload description */
	private $description;

	/** @var UserIdentity|null Uploader */
	private $user;

	/** @var string|null Time of upload */
	private $timestamp;

	/** @var bool Whether or not all this has been loaded from the database (loadFromXxx) */
	private $dataLoaded;

	/** @var int Bitfield akin to rev_deleted */
	private $deleted;

	/** @var string SHA-1 hash of file content */
	private $sha1;

	/** @var int|false Number of pages of a multipage document, or false for
	 * documents which aren't multipage documents
	 */
	private $pageCount;

	/** @var string Original base filename */
	private $archive_name;

	/** @var MediaHandler */
	protected $handler;

	/** @var Title|null */
	protected $title; # image title

	/** @var bool */
	protected $exists;

	/** @var LocalRepo */
	private $repo;

	/** @var MetadataStorageHelper */
	private $metadataStorageHelper;

	/**
	 * @stable to call
	 * @throws MWException
	 * @param Title|null $title
	 * @param int $id
	 * @param string $key
	 * @param string $sha1
	 */
	public function __construct( $title, $id = 0, $key = '', $sha1 = '' ) {
		$this->id = -1;
		$this->title = null;
		$this->name = false;
		$this->group = 'deleted'; // needed for direct use of constructor
		$this->key = '';
		$this->size = 0;
		$this->bits = 0;
		$this->width = 0;
		$this->height = 0;
		$this->mime = "unknown/unknown";
		$this->media_type = '';
		$this->description = '';
		$this->user = null;
		$this->timestamp = null;
		$this->deleted = 0;
		$this->dataLoaded = false;
		$this->exists = false;
		$this->sha1 = '';

		if ( $title instanceof Title ) {
			$this->title = File::normalizeTitle( $title, 'exception' );
			$this->name = $title->getDBkey();
		}

		if ( $id ) {
			$this->id = $id;
		}

		if ( $key ) {
			$this->key = $key;
		}

		if ( $sha1 ) {
			$this->sha1 = $sha1;
		}

		if ( !$id && !$key && !( $title instanceof Title ) && !$sha1 ) {
			throw new MWException( "No specifications provided to ArchivedFile constructor." );
		}

		$this->repo = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo();
		$this->metadataStorageHelper = new MetadataStorageHelper( $this->repo );
	}

	/**
	 * Loads a file object from the filearchive table
	 * @stable to override
	 * @throws MWException
	 * @return bool|null True on success or null
	 */
	public function load() {
		if ( $this->dataLoaded ) {
			return true;
		}
		$conds = [];

		if ( $this->id > 0 ) {
			$conds['fa_id'] = $this->id;
		}
		if ( $this->key ) {
			$conds['fa_storage_group'] = $this->group;
			$conds['fa_storage_key'] = $this->key;
		}
		if ( $this->title ) {
			$conds['fa_name'] = $this->title->getDBkey();
		}
		if ( $this->sha1 ) {
			$conds['fa_sha1'] = $this->sha1;
		}

		if ( $conds === [] ) {
			throw new MWException( "No specific information for retrieving archived file" );
		}

		if ( !$this->title || $this->title->getNamespace() === NS_FILE ) {
			$this->dataLoaded = true; // set it here, to have also true on miss
			$dbr = wfGetDB( DB_REPLICA );
			$fileQuery = self::getQueryInfo();
			$row = $dbr->selectRow(
				$fileQuery['tables'],
				$fileQuery['fields'],
				$conds,
				__METHOD__,
				[ 'ORDER BY' => 'fa_timestamp DESC' ],
				$fileQuery['joins']
			);
			if ( !$row ) {
				// this revision does not exist?
				return null;
			}

			// initialize fields for filestore image object
			$this->loadFromRow( $row );
		} else {
			throw new MWException( 'This title does not correspond to an image page.' );
		}
		$this->exists = true;

		return true;
	}

	/**
	 * Loads a file object from the filearchive table
	 * @stable to override
	 *
	 * @param stdClass $row
	 * @return ArchivedFile
	 */
	public static function newFromRow( $row ) {
		$file = new ArchivedFile( Title::makeTitle( NS_FILE, $row->fa_name ) );
		$file->loadFromRow( $row );

		return $file;
	}

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new archivedfile object.
	 *
	 * Since 1.34, fa_user and fa_user_text have not been present in the
	 * database, but they continue to be available in query results as an
	 * alias.
	 *
	 * @since 1.31
	 * @stable to override
	 * @return array[] With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()` or `SelectQueryBuilder::tables`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()` or `SelectQueryBuilder::fields`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()` or `SelectQueryBuilder::joinConds`
	 * @phan-return array{tables:string[],fields:string[],joins:array}
	 */
	public static function getQueryInfo() {
		$commentQuery = MediaWikiServices::getInstance()->getCommentStore()->getJoin( 'fa_description' );
		return [
			'tables' => [
				'filearchive',
				'filearchive_actor' => 'actor'
			] + $commentQuery['tables'],
			'fields' => [
				'fa_id',
				'fa_name',
				'fa_archive_name',
				'fa_storage_key',
				'fa_storage_group',
				'fa_size',
				'fa_bits',
				'fa_width',
				'fa_height',
				'fa_metadata',
				'fa_media_type',
				'fa_major_mime',
				'fa_minor_mime',
				'fa_timestamp',
				'fa_deleted',
				'fa_deleted_timestamp', /* Used by LocalFileRestoreBatch */
				'fa_sha1',
				'fa_actor',
				'fa_user' => 'filearchive_actor.actor_user',
				'fa_user_text' => 'filearchive_actor.actor_name'
			] + $commentQuery['fields'],
			'joins' => [
				'filearchive_actor' => [ 'JOIN', 'actor_id=fa_actor' ]
			] + $commentQuery['joins'],
		];
	}

	/**
	 * Load ArchivedFile object fields from a DB row.
	 * @stable to override
	 *
	 * @param stdClass $row Object database row
	 * @since 1.21
	 */
	public function loadFromRow( $row ) {
		$this->id = intval( $row->fa_id );
		$this->name = $row->fa_name;
		$this->archive_name = $row->fa_archive_name;
		$this->group = $row->fa_storage_group;
		$this->key = $row->fa_storage_key;
		$this->size = $row->fa_size;
		$this->bits = $row->fa_bits;
		$this->width = $row->fa_width;
		$this->height = $row->fa_height;
		$this->loadMetadataFromDbFieldValue(
			$this->repo->getReplicaDB(), $row->fa_metadata );
		$this->mime = "$row->fa_major_mime/$row->fa_minor_mime";
		$this->media_type = $row->fa_media_type;
		$services = MediaWikiServices::getInstance();
		$this->description = $services->getCommentStore()
			// Legacy because $row may have come from self::selectFields()
			->getCommentLegacy( wfGetDB( DB_REPLICA ), 'fa_description', $row )->text;
		$this->user = $services->getUserFactory()
			->newFromAnyId( $row->fa_user, $row->fa_user_text, $row->fa_actor );
		$this->timestamp = $row->fa_timestamp;
		$this->deleted = $row->fa_deleted;
		if ( isset( $row->fa_sha1 ) ) {
			$this->sha1 = $row->fa_sha1;
		} else {
			// old row, populate from key
			$this->sha1 = LocalRepo::getHashFromKey( $this->key );
		}
		if ( !$this->title ) {
			$this->title = Title::makeTitleSafe( NS_FILE, $row->fa_name );
		}
	}

	/**
	 * Return the associated title object
	 *
	 * @return Title
	 */
	public function getTitle() {
		if ( !$this->title ) {
			$this->load();
		}
		return $this->title;
	}

	/**
	 * Return the file name
	 *
	 * @return string
	 */
	public function getName() {
		if ( $this->name === false ) {
			$this->load();
		}

		return $this->name;
	}

	/**
	 * @return int
	 */
	public function getID() {
		$this->load();

		return $this->id;
	}

	/**
	 * @return bool
	 */
	public function exists() {
		$this->load();

		return $this->exists;
	}

	/**
	 * Return the FileStore key
	 * @return string
	 */
	public function getKey() {
		$this->load();

		return $this->key;
	}

	/**
	 * Return the FileStore key (overriding base File class)
	 * @return string
	 */
	public function getStorageKey() {
		return $this->getKey();
	}

	/**
	 * Return the FileStore storage group
	 * @return string
	 */
	public function getGroup() {
		return $this->group;
	}

	/**
	 * Return the width of the image
	 * @return int
	 */
	public function getWidth() {
		$this->load();

		return $this->width;
	}

	/**
	 * Return the height of the image
	 * @return int
	 */
	public function getHeight() {
		$this->load();

		return $this->height;
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
	 * @since 1.39
	 * @return array
	 */
	public function getMetadataArray(): array {
		$this->load();
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
		$this->load();
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
	 * @param IDatabase $db
	 * @return string|Blob
	 */
	public function getMetadataForDb( IDatabase $db ) {
		$this->load();
		if ( !$this->metadataArray && !$this->metadataBlobs ) {
			$s = '';
		} elseif ( $this->repo->isJsonMetadataEnabled() ) {
			$s = $this->getJsonMetadata();
		} else {
			$s = serialize( $this->getMetadataArray() );
		}
		if ( !is_string( $s ) ) {
			throw new MWException( 'Could not serialize image metadata value for DB' );
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

		list( $s, $blobAddresses ) = $this->metadataStorageHelper->getJsonMetadata( $this, $envelope );

		// Repeated calls to this function should not keep inserting more blobs
		$this->metadataBlobs += $blobAddresses;

		return $s;
	}

	/**
	 * Unserialize a metadata blob which came from the database and store it
	 * in $this.
	 *
	 * @since 1.39
	 * @param IDatabase $db
	 * @param string|Blob $metadataBlob
	 */
	protected function loadMetadataFromDbFieldValue( IDatabase $db, $metadataBlob ) {
		$this->loadMetadataFromString( $db->decodeBlob( $metadataBlob ) );
	}

	/**
	 * Unserialize a metadata string which came from some non-DB source, or is
	 * the return value of IDatabase::decodeBlob().
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
	 * Return the size of the image file, in bytes
	 * @return int
	 */
	public function getSize() {
		$this->load();

		return $this->size;
	}

	/**
	 * Return the bits of the image file, in bytes
	 * @return int
	 */
	public function getBits() {
		$this->load();

		return $this->bits;
	}

	/**
	 * Returns the MIME type of the file.
	 * @return string
	 */
	public function getMimeType() {
		$this->load();

		return $this->mime;
	}

	/**
	 * Get a MediaHandler instance for this file
	 * @return MediaHandler
	 */
	private function getHandler() {
		if ( !isset( $this->handler ) ) {
			$this->handler = MediaHandler::getHandler( $this->getMimeType() );
		}

		return $this->handler;
	}

	/**
	 * Returns the number of pages of a multipage document, or false for
	 * documents which aren't multipage documents
	 * @stable to override
	 * @return int|false
	 */
	public function pageCount() {
		if ( !isset( $this->pageCount ) ) {
			// @FIXME: callers expect File objects
			// @phan-suppress-next-line PhanTypeMismatchArgument
			if ( $this->getHandler() && $this->handler->isMultiPage( $this ) ) {
				// @phan-suppress-next-line PhanTypeMismatchArgument
				$this->pageCount = $this->handler->pageCount( $this );
			} else {
				$this->pageCount = false;
			}
		}

		return $this->pageCount;
	}

	/**
	 * Return the type of the media in the file.
	 * Use the value returned by this function with the MEDIATYPE_xxx constants.
	 * @return string
	 */
	public function getMediaType() {
		$this->load();

		return $this->media_type;
	}

	/**
	 * Return upload timestamp.
	 *
	 * @return string
	 */
	public function getTimestamp() {
		$this->load();

		return wfTimestamp( TS_MW, $this->timestamp );
	}

	/**
	 * Get the SHA-1 base 36 hash of the file
	 *
	 * @return string
	 * @since 1.21
	 */
	public function getSha1() {
		$this->load();

		return $this->sha1;
	}

	/**
	 * @since 1.37
	 * @stable to override
	 * @param int $audience One of:
	 *   File::FOR_PUBLIC       to be displayed to all users
	 *   File::FOR_THIS_USER    to be displayed to the given user
	 *   File::RAW              get the description regardless of permissions
	 * @param Authority|null $performer to check for, only if FOR_THIS_USER is
	 *   passed to the $audience parameter
	 * @return UserIdentity|null
	 */
	public function getUploader( int $audience = self::FOR_PUBLIC, Authority $performer = null ): ?UserIdentity {
		$this->load();
		if ( $audience === self::FOR_PUBLIC && $this->isDeleted( File::DELETED_USER ) ) {
			return null;
		} elseif ( $audience === self::FOR_THIS_USER && !$this->userCan( File::DELETED_USER, $performer ) ) {
			return null;
		} else {
			return $this->user;
		}
	}

	/**
	 * Return upload description.
	 *
	 * @since 1.37 the method takes $audience and $performer parameters.
	 * @param int $audience One of:
	 *   File::FOR_PUBLIC       to be displayed to all users
	 *   File::FOR_THIS_USER    to be displayed to the given user
	 *   File::RAW              get the description regardless of permissions
	 * @param Authority|null $performer to check for, only if FOR_THIS_USER is
	 *   passed to the $audience parameter
	 * @return string
	 */
	public function getDescription( int $audience = self::FOR_PUBLIC, Authority $performer = null ): string {
		$this->load();
		if ( $audience === self::FOR_PUBLIC && $this->isDeleted( File::DELETED_COMMENT ) ) {
			return '';
		} elseif ( $audience === self::FOR_THIS_USER && !$this->userCan( File::DELETED_COMMENT, $performer ) ) {
			return '';
		} else {
			return $this->description;
		}
	}

	/**
	 * Returns the deletion bitfield
	 * @return int
	 */
	public function getVisibility() {
		$this->load();

		return $this->deleted;
	}

	/**
	 * for file or revision rows
	 *
	 * @param int $field One of DELETED_* bitfield constants
	 * @return bool
	 */
	public function isDeleted( $field ) {
		$this->load();

		return ( $this->deleted & $field ) == $field;
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this FileStore image file, if it's marked as deleted.
	 * @param int $field
	 * @param Authority $performer
	 * @return bool
	 */
	public function userCan( $field, Authority $performer ) {
		$this->load();
		$title = $this->getTitle();

		return RevisionRecord::userCanBitfield(
			$this->deleted,
			$field,
			$performer,
			$title ?: null
		);
	}
}
