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

	/** @var int User ID of uploader */
	private $user;

	/** @var string User name of uploader */
	private $user_text;

	/** @var string Time of upload */
	private $timestamp;

	/** @var bool Whether or not all this has been loaded from the database (loadFromXxx) */
	private $dataLoaded;

	/** @var int Bitfield akin to rev_deleted */
	private $deleted;

	/** @var string SHA-1 hash of file content */
	private $sha1;

	/** @var string Number of pages of a multipage document, or false for
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
		$this->user = 0;
		$this->user_text = '';
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
		$conds = array();

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
			$dbr = wfGetDB( DB_SLAVE );
			$row = $dbr->selectRow(
				'filearchive',
				self::selectFields(),
				$conds,
				__METHOD__,
				array( 'ORDER BY' => 'fa_timestamp DESC' )
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
	 * @return array
	 */
	static function selectFields() {
		return array(
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
			'fa_description',
			'fa_user',
			'fa_user_text',
			'fa_timestamp',
			'fa_deleted',
			'fa_deleted_timestamp', /* Used by LocalFileRestoreBatch */
			'fa_sha1',
		);
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
		$this->description = $row->fa_description;
		$this->user = $row->fa_user;
		$this->user_text = $row->fa_user_text;
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
	 * @param string $type 'text' or 'id'
	 * @return int|string
	 * @throws MWException
	 */
	public function getUser( $type = 'text' ) {
		$this->load();

		if ( $type == 'text' ) {
			return $this->user_text;
		} elseif ( $type == 'id' ) {
			return (int)$this->user;
		}

		throw new MWException( "Unknown type '$type'." );
	}

	/**
	 * Return the user name of the uploader.
	 *
	 * @deprecated since 1.23 Use getUser( 'text' ) instead.
	 * @return string
	 */
	public function getUserText() {
		wfDeprecated( __METHOD__, '1.23' );
		$this->load();
		if ( $this->isDeleted( File::DELETED_USER ) ) {
			return 0;
		} else {
			return $this->user_text;
		}
	}

	/**
	 * Return upload description.
	 *
	 * @return string
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
		$this->load();

		return $this->user;
	}

	/**
	 * Return the user name of the uploader.
	 *
	 * @return string
	 */
	public function getRawUserText() {
		$this->load();

		return $this->user_text;
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
