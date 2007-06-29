<?php

/**
 * @addtogroup Media
 */
class ArchivedFile
{
	/**
	 * Returns a file object from the filearchive table
	 * @param $title, the corresponding image page title
	 * @param $id, the image id, a unique key
	 * @param $key, optional storage key
	 * @return ResultWrapper
	 */
	function ArchivedFile( $title, $id=0, $key='' ) {
		if( !is_object( $title ) ) {
			throw new MWException( 'ArchivedFile constructor given bogus title.' );
		}
		$conds = ($id) ? "fa_id = $id" : "fa_storage_key = '$key'";
		if( $title->getNamespace() == NS_IMAGE ) {
			$dbr = wfGetDB( DB_SLAVE );
			$res = $dbr->select( 'filearchive',
				array(
					'fa_id',
					'fa_name',
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
					'fa_name' => $title->getDbKey(),
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
			$this->mId = intval($row->fa_id);
			$this->mName = $row->fa_name;
			$this->mGroup = $row->fa_storage_group;
			$this->mKey = $row->fa_storage_key;
			$this->mSize = $row->fa_size;
			$this->mBits = $row->fa_bits;
			$this->mWidth = $row->fa_width;
			$this->mHeight = $row->fa_height;
			$this->mMetaData = $row->fa_metadata;
			$this->mMime = "$row->fa_major_mime/$row->fa_minor_mime";
			$this->mType = $row->fa_media_type;
			$this->mDescription = $row->fa_description;
			$this->mUser = $row->fa_user;
			$this->mUserText = $row->fa_user_text;
			$this->mTimestamp = $row->fa_timestamp;
			$this->mDeleted = $row->fa_deleted;		
		} else {
			throw new MWException( 'This title does not correspond to an image page.' );
			return;
		}
		return true;
	}

	/**
	 * int $field one of DELETED_* bitfield constants
	 * for file or revision rows
	 * @return bool
	 */
	function isDeleted( $field ) {
		return ($this->mDeleted & $field) == $field;
	}
	
	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this FileStore image file, if it's marked as deleted.
	 * @param int $field					
	 * @return bool
	 */
	function userCan( $field ) {
		if( isset($this->mDeleted) && ($this->mDeleted & $field) == $field ) {
		// images
			global $wgUser;
			$permission = ( $this->mDeleted & File::DELETED_RESTRICTED ) == File::DELETED_RESTRICTED
				? 'hiderevision'
				: 'deleterevision';
			wfDebug( "Checking for $permission due to $field match on $this->mDeleted\n" );
			return $wgUser->isAllowed( $permission );
		} else {
			return true;
		}
	}
}


