<?php

/**
 * @addtogroup Media
 */
class ArchivedFile
{
	function ArchivedFile( $title, $id=0, $key='' ) {
		if( !is_object( $title ) ) {
			throw new MWException( 'ArchivedFile constructor given bogus title.' );
		}
		$this->id = -1;
		$this->title = $title;
		$this->name = $title->getDBKey();
		$this->group = '';
		$this->key = '';
		$this->size = 0;
		$this->bits = 0;
		$this->width = 0;
		$this->height = 0;
		$this->metaData = '';
		$this->mime = "unknown/unknown";
		$this->type = '';
		$this->description = '';
		$this->user = 0;
		$this->userText = '';
		$this->timestamp = NULL;
		$this->deleted = 0;
		# BC, load if these are specified
		if( $id || $key ) {
			$this->load();
		}
	}

	/**
	 * Loads a file object from the filearchive table
	 * @return ResultWrapper
	 */
	function load() {
		if( !is_object( $this->title ) ) {
			throw new MWException( 'ArchivedFile constructor given bogus title.' );
		}
		$conds = ($this->id) ? "fa_id = {$this->id}" : "fa_storage_key = '{$this->key}'";
		if( $this->title->getNamespace() == NS_IMAGE ) {
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
				array( 
					'fa_name' => $this->title->getDBKey(),
					$conds ),
				__METHOD__,
				array( 'ORDER BY' => 'fa_timestamp DESC' ) );
				
			if ( $dbr->numRows( $res ) == 0 ) {
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
			$this->metaData = $row->fa_metadata;
			$this->mime = "$row->fa_major_mime/$row->fa_minor_mime";
			$this->type = $row->fa_media_type;
			$this->description = $row->fa_description;
			$this->user = $row->fa_user;
			$this->userText = $row->fa_user_text;
			$this->timestamp = $row->fa_timestamp;
			$this->deleted = $row->fa_deleted;
		} else {
			throw new MWException( 'This title does not correspond to an image page.' );
			return;
		}
		return true;
	}

	/**
	 * Loads a file object from the filearchive table
	 * @return ResultWrapper
	 */	
	public static function newFromRow( $row ) {	
		$file = new ArchivedFile( Title::makeTitle( NS_IMAGE, $row->fa_name ) );
		
		$file->id = intval($row->fa_id);
		$file->name = $row->fa_name;
		$file->archive_name = $row->fa_archive_name;
		$file->group = $row->fa_storage_group;
		$file->key = $row->fa_storage_key;
		$file->size = $row->fa_size;
		$file->bits = $row->fa_bits;
		$file->width = $row->fa_width;
		$file->height = $row->fa_height;
		$file->metaData = $row->fa_metadata;
		$file->mime = "$row->fa_major_mime/$row->fa_minor_mime";
		$file->type = $row->fa_media_type;
		$file->description = $row->fa_description;
		$file->user = $row->fa_user;
		$file->userText = $row->fa_user_text;
		$file->timestamp = $row->fa_timestamp;
		$file->deleted = $row->fa_deleted;
		
		return $file;
	}

	/**
	 * int $field one of DELETED_* bitfield constants
	 * for file or revision rows
	 * @return bool
	 */
	function isDeleted( $field ) {
		return ($this->deleted & $field) == $field;
	}
	
	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this FileStore image file, if it's marked as deleted.
	 * @param int $field					
	 * @return bool
	 */
	function userCan( $field ) {
		if( isset($this->deleted) && ($this->deleted & $field) == $field ) {
		// images
			global $wgUser;
			$permission = ( $this->deleted & File::DELETED_RESTRICTED ) == File::DELETED_RESTRICTED
				? 'hiderevision'
				: 'deleterevision';
			wfDebug( "Checking for $permission due to $field match on $this->deleted\n" );
			return $wgUser->isAllowed( $permission );
		} else {
			return true;
		}
	}
}
