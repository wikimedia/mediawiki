<?php
/**
 * Deleted file in the 'filearchive' table.
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

use MediaWiki\MediaWikiServices;

/**
 * Class representing a row of the 'filearchive' table
 *
 * @ingroup FileAbstraction
 */
class ArchivedFile {
	/** @var int Filearchive row ID */
	private $id;

	/** @var string File name */
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

	/** @var string Metadata string */
	private $metadata;

	/** @var string MIME type */
	private $mime;

	/** @var string Media type */
	private $media_type;

	/** @var string Upload description */
	private $description;

	/** @var User|null Uploader */
	private $user;

	/** @var string Time of upload */
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

	/** @var Title */
	protected $title; # image title

	/**
	 * @throws MWException
	 * @param Title $title
	 * @param int $id
	 * @param string $key
	 * @param string $sha1
	 */
	function __construct( $title, $id = 0, $key = '', $sha1 = '' ) {
		$this->id = -1;
		$this->title = false;
		$this->name = false;
		$this->group = 'deleted'; // needed for direct use of constructor
		$this->key = '';
		$this->size = 0;
		$this->bits = 0;
		$this->width = 0;
		$this->height = 0;
		$this->metadata = '';
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
	}

	/**
	 * Loads a file object from the filearchive table
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

		if ( !count( $conds ) ) {
			throw new MWException( "No specific information for retrieving archived file" );
		}

		if ( !$this->title || $this->title->getNamespace() == NS_FILE ) {
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
	 * Fields in the filearchive table
	 * @deprecated since 1.31, use self::getQueryInfo() instead.
	 * @return string[]
	 */
	static function selectFields() {
		global $wgActorTableSchemaMigrationStage;

		if ( $wgActorTableSchemaMigrationStage & SCHEMA_COMPAT_READ_NEW ) {
			// If code is using this instead of self::getQueryInfo(), there's a
			// decent chance it's going to try to directly access
			// $row->fa_user or $row->fa_user_text and we can't give it
			// useful values here once those aren't being used anymore.
			throw new BadMethodCallException(
				'Cannot use ' . __METHOD__
					. ' when $wgActorTableSchemaMigrationStage has SCHEMA_COMPAT_READ_NEW'
			);
		}

		wfDeprecated( __METHOD__, '1.31' );
		return [
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
			'fa_user',
			'fa_user_text',
			'fa_actor' => 'NULL',
			'fa_timestamp',
			'fa_deleted',
			'fa_deleted_timestamp', /* Used by LocalFileRestoreBatch */
			'fa_sha1',
		] + MediaWikiServices::getInstance()->getCommentStore()->getFields( 'fa_description' );
	}

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new archivedfile object.
	 * @since 1.31
	 * @return array[] With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
	 */
	public static function getQueryInfo() {
		$commentQuery = MediaWikiServices::getInstance()->getCommentStore()->getJoin( 'fa_description' );
		$actorQuery = ActorMigration::newMigration()->getJoin( 'fa_user' );
		return [
			'tables' => [ 'filearchive' ] + $commentQuery['tables'] + $actorQuery['tables'],
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
			] + $commentQuery['fields'] + $actorQuery['fields'],
			'joins' => $commentQuery['joins'] + $actorQuery['joins'],
		];
	}

	/**
	 * Load ArchivedFile object fields from a DB row.
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
		$this->metadata = $row->fa_metadata;
		$this->mime = "$row->fa_major_mime/$row->fa_minor_mime";
		$this->media_type = $row->fa_media_type;
		$this->description = MediaWikiServices::getInstance()->getCommentStore()
			// Legacy because $row may have come from self::selectFields()
			->getCommentLegacy( wfGetDB( DB_REPLICA ), 'fa_description', $row )->text;
		$this->user = User::newFromAnyId( $row->fa_user, $row->fa_user_text, $row->fa_actor );
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
	 * Get handler-specific metadata
	 * @return string
	 */
	public function getMetadata() {
		$this->load();

		return $this->metadata;
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
	function getHandler() {
		if ( !isset( $this->handler ) ) {
			$this->handler = MediaHandler::getHandler( $this->getMimeType() );
		}

		return $this->handler;
	}

	/**
	 * Returns the number of pages of a multipage document, or false for
	 * documents which aren't multipage documents
	 * @return bool|int
	 */
	function pageCount() {
		if ( !isset( $this->pageCount ) ) {
			// @FIXME: callers expect File objects
			if ( $this->getHandler() && $this->handler->isMultiPage( $this ) ) {
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
	function getSha1() {
		$this->load();

		return $this->sha1;
	}

	/**
	 * Returns ID or name of user who uploaded the file
	 *
	 * @note Prior to MediaWiki 1.23, this method always
	 *   returned the user id, and was inconsistent with
	 *   the rest of the file classes.
	 * @param string $type 'text', 'id', or 'object'
	 * @return int|string|User|null
	 * @throws MWException
	 * @since 1.31 added 'object'
	 */
	public function getUser( $type = 'text' ) {
		$this->load();

		if ( $type === 'object' ) {
			return $this->user;
		} elseif ( $type === 'text' ) {
			return $this->user ? $this->user->getName() : '';
		} elseif ( $type === 'id' ) {
			return $this->user ? $this->user->getId() : 0;
		}

		throw new MWException( "Unknown type '$type'." );
	}

	/**
	 * Return upload description.
	 *
	 * @return string|int
	 */
	public function getDescription() {
		$this->load();
		if ( $this->isDeleted( File::DELETED_COMMENT ) ) {
			return 0;
		} else {
			return $this->description;
		}
	}

	/**
	 * Return the user ID of the uploader.
	 *
	 * @return int
	 */
	public function getRawUser() {
		return $this->getUser( 'id' );
	}

	/**
	 * Return the user name of the uploader.
	 *
	 * @return string
	 */
	public function getRawUserText() {
		return $this->getUser( 'text' );
	}

	/**
	 * Return upload description.
	 *
	 * @return string
	 */
	public function getRawDescription() {
		$this->load();

		return $this->description;
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
	 * @param null|User $user User object to check, or null to use $wgUser
	 * @return bool
	 */
	public function userCan( $field, User $user = null ) {
		$this->load();

		$title = $this->getTitle();
		return Revision::userCanBitfield( $this->deleted, $field, $user, $title ?: null );
	}
}
