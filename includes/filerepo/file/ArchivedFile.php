<?php
/**
 * Deleted file in the 'filearchive' table
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
	/**#@+
	 * @private
	 */
	var $id, # filearchive row ID
		$name, # image name
		$group,	# FileStore storage group
		$key, # FileStore sha1 key
		$size, # file dimensions
		$bits,	# size in bytes
		$width, # width
		$height, # height
		$metadata, # metadata string
		$mime, # mime type
		$media_type, # media type
		$description, # upload description
		$user, # user ID of uploader
		$user_text, # user name of uploader
		$timestamp, # time of upload
		$dataLoaded, # Whether or not all this has been loaded from the database (loadFromXxx)
		$deleted, # Bitfield akin to rev_deleted
		$pageCount,
		$archive_name;

	/**
	 * @var MediaHandler
	 */
	var $handler;
	/**
	 * @var Title
	 */
	var $title; # image title

	/**#@-*/

	/**
	 * @throws MWException
	 * @param Title $title
	 * @param int $id
	 * @param string $key
	 */
	function __construct( $title, $id=0, $key='' ) {
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

		if( $title instanceof Title ) {
			$this->title = File::normalizeTitle( $title, 'exception' );
			$this->name = $title->getDBkey();
		}

		if ($id) {
			$this->id = $id;
		}

		if ($key) {
			$this->key = $key;
		}

		if ( !$id && !$key && !( $title instanceof Title ) ) {
			throw new MWException( "No specifications provided to ArchivedFile constructor." );
		}
	}

	/**
	 * Loads a file object from the filearchive table
	 * @return true on success or null
	 */
	public function load() {
		if ( $this->dataLoaded ) {
			return true;
		}
		$conds = array();

		if( $this->id > 0 ) {
			$conds['fa_id'] = $this->id;
		}
		if( $this->key ) {
			$conds['fa_storage_group'] = $this->group;
			$conds['fa_storage_key'] = $this->key;
		}
		if( $this->title ) {
			$conds['fa_name'] = $this->title->getDBkey();
		}

		if( !count($conds)) {
			throw new MWException( "No specific information for retrieving archived file" );
		}

		if( !$this->title || $this->title->getNamespace() == NS_FILE ) {
			$dbr = wfGetDB( DB_SLAVE );
			$res = $dbr->select( 'filearchive',
				array(
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
					'fa_deleted' ),
				$conds,
				__METHOD__,
				array( 'ORDER BY' => 'fa_timestamp DESC' ) );
			if ( $res == false || $dbr->numRows( $res ) == 0 ) {
			// this revision does not exist?
				return;
			}
			$ret = $dbr->resultObject( $res );
			$row = $ret->fetchObject();

			// initialize fields for filestore image object
			$this->id = intval($row->fa_id);
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
		} else {
			throw new MWException( 'This title does not correspond to an image page.' );
		}
		$this->dataLoaded = true;
		$this->exists = true;

		return true;
	}

	/**
	 * Loads a file object from the filearchive table
	 *
	 * @param $row
	 *
	 * @return ArchivedFile
	 */
	public static function newFromRow( $row ) {
		$file = new ArchivedFile( Title::makeTitle( NS_FILE, $row->fa_name ) );

		$file->id = intval($row->fa_id);
		$file->name = $row->fa_name;
		$file->archive_name = $row->fa_archive_name;
		$file->group = $row->fa_storage_group;
		$file->key = $row->fa_storage_key;
		$file->size = $row->fa_size;
		$file->bits = $row->fa_bits;
		$file->width = $row->fa_width;
		$file->height = $row->fa_height;
		$file->metadata = $row->fa_metadata;
		$file->mime = "$row->fa_major_mime/$row->fa_minor_mime";
		$file->media_type = $row->fa_media_type;
		$file->description = $row->fa_description;
		$file->user = $row->fa_user;
		$file->user_text = $row->fa_user_text;
		$file->timestamp = $row->fa_timestamp;
		$file->deleted = $row->fa_deleted;

		return $file;
	}

	/**
	 * Return the associated title object
	 *
	 * @return Title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Return the file name
	 *
	 * @return string
	 */
	public function getName() {
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
	 * Returns the mime type of the file.
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
	 * Return the user ID of the uploader.
	 *
	 * @return int
	 */
	public function getUser() {
		$this->load();
		if( $this->isDeleted( File::DELETED_USER ) ) {
			return 0;
		} else {
			return $this->user;
		}
	}

	/**
	 * Return the user name of the uploader.
	 *
	 * @return string
	 */
	public function getUserText() {
		$this->load();
		if( $this->isDeleted( File::DELETED_USER ) ) {
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
		if( $this->isDeleted( File::DELETED_COMMENT ) ) {
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
	 * @param $field Integer: one of DELETED_* bitfield constants
	 * @return bool
	 */
	public function isDeleted( $field ) {
		$this->load();
		return ($this->deleted & $field) == $field;
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this FileStore image file, if it's marked as deleted.
	 * @param $field Integer
	 * @param $user User object to check, or null to use $wgUser
	 * @return bool
	 */
	public function userCan( $field, User $user = null ) {
		$this->load();
		return Revision::userCanBitfield( $this->deleted, $field, $user );
	}
}
