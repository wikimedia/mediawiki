<?php
/**
 * Old file in the oldimage table.
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
 * Class to represent a file in the oldimage table
 *
 * @ingroup FileAbstraction
 */
class OldLocalFile extends LocalFile {
	var $requestedTime, $archive_name;

	const CACHE_VERSION = 1;
	const MAX_CACHE_ROWS = 20;

	/**
	 * @param $title Title
	 * @param $repo FileRepo
	 * @param $time null
	 * @return OldLocalFile
	 * @throws MWException
	 */
	static function newFromTitle( $title, $repo, $time = null ) {
		# The null default value is only here to avoid an E_STRICT
		if ( $time === null ) {
			throw new MWException( __METHOD__.' got null for $time parameter' );
		}
		return new self( $title, $repo, $time, null );
	}

	/**
	 * @param $title Title
	 * @param $repo FileRepo
	 * @param $archiveName
	 * @return OldLocalFile
	 */
	static function newFromArchiveName( $title, $repo, $archiveName ) {
		return new self( $title, $repo, null, $archiveName );
	}

	/**
	 * @param $row
	 * @param $repo FileRepo
	 * @return OldLocalFile
	 */
	static function newFromRow( $row, $repo ) {
		$title = Title::makeTitle( NS_FILE, $row->oi_name );
		$file = new self( $title, $repo, null, $row->oi_archive_name );
		$file->loadFromRow( $row, 'oi_' );
		return $file;
	}

	/**
	 * Create a OldLocalFile from a SHA-1 key
	 * Do not call this except from inside a repo class.
	 *
	 * @param $sha1 string base-36 SHA-1
	 * @param $repo LocalRepo
	 * @param string|bool $timestamp MW_timestamp (optional)
	 *
	 * @return bool|OldLocalFile
	 */
	static function newFromKey( $sha1, $repo, $timestamp = false ) {
		$dbr = $repo->getSlaveDB();

		$conds = array( 'oi_sha1' => $sha1 );
		if ( $timestamp ) {
			$conds['oi_timestamp'] = $dbr->timestamp( $timestamp );
		}

		$row = $dbr->selectRow( 'oldimage', self::selectFields(), $conds, __METHOD__ );
		if ( $row ) {
			return self::newFromRow( $row, $repo );
		} else {
			return false;
		}
	}

	/**
	 * Fields in the oldimage table
	 * @return array
	 */
	static function selectFields() {
		return array(
			'oi_name',
			'oi_archive_name',
			'oi_size',
			'oi_width',
			'oi_height',
			'oi_metadata',
			'oi_bits',
			'oi_media_type',
			'oi_major_mime',
			'oi_minor_mime',
			'oi_description',
			'oi_user',
			'oi_user_text',
			'oi_timestamp',
			'oi_deleted',
			'oi_sha1',
		);
	}

	/**
	 * @param $title Title
	 * @param $repo FileRepo
	 * @param $time String: timestamp or null to load by archive name
	 * @param $archiveName String: archive name or null to load by timestamp
	 * @throws MWException
	 */
	function __construct( $title, $repo, $time, $archiveName ) {
		parent::__construct( $title, $repo );
		$this->requestedTime = $time;
		$this->archive_name = $archiveName;
		if ( is_null( $time ) && is_null( $archiveName ) ) {
			throw new MWException( __METHOD__.': must specify at least one of $time or $archiveName' );
		}
	}

	/**
	 * @return bool
	 */
	function getCacheKey() {
		return false;
	}

	/**
	 * @return String
	 */
	function getArchiveName() {
		if ( !isset( $this->archive_name ) ) {
			$this->load();
		}
		return $this->archive_name;
	}

	/**
	 * @return bool
	 */
	function isOld() {
		return true;
	}

	/**
	 * @return bool
	 */
	function isVisible() {
		return $this->exists() && !$this->isDeleted(File::DELETED_FILE);
	}

	function loadFromDB() {
		wfProfileIn( __METHOD__ );
		$this->dataLoaded = true;
		$dbr = $this->repo->getSlaveDB();
		$conds = array( 'oi_name' => $this->getName() );
		if ( is_null( $this->requestedTime ) ) {
			$conds['oi_archive_name'] = $this->archive_name;
		} else {
			$conds[] = 'oi_timestamp = ' . $dbr->addQuotes( $dbr->timestamp( $this->requestedTime ) );
		}
		$row = $dbr->selectRow( 'oldimage', $this->getCacheFields( 'oi_' ),
			$conds, __METHOD__, array( 'ORDER BY' => 'oi_timestamp DESC' ) );
		if ( $row ) {
			$this->loadFromRow( $row, 'oi_' );
		} else {
			$this->fileExists = false;
		}
		wfProfileOut( __METHOD__ );
	}

	/**
	 * @param $prefix string
	 * @return array
	 */
	function getCacheFields( $prefix = 'img_' ) {
		$fields = parent::getCacheFields( $prefix );
		$fields[] = $prefix . 'archive_name';
		$fields[] = $prefix . 'deleted';
		return $fields;
	}

	/**
	 * @return string
	 */
	function getRel() {
		return 'archive/' . $this->getHashPath() . $this->getArchiveName();
	}

	/**
	 * @return string
	 */
	function getUrlRel() {
		return 'archive/' . $this->getHashPath() . rawurlencode( $this->getArchiveName() );
	}

	function upgradeRow() {
		wfProfileIn( __METHOD__ );
		$this->loadFromFile();

		# Don't destroy file info of missing files
		if ( !$this->fileExists ) {
			wfDebug( __METHOD__.": file does not exist, aborting\n" );
			wfProfileOut( __METHOD__ );
			return;
		}

		$dbw = $this->repo->getMasterDB();
		list( $major, $minor ) = self::splitMime( $this->mime );

		wfDebug(__METHOD__.': upgrading '.$this->archive_name." to the current schema\n");
		$dbw->update( 'oldimage',
			array(
				'oi_size'       => $this->size, // sanity
				'oi_width'      => $this->width,
				'oi_height'     => $this->height,
				'oi_bits'       => $this->bits,
				'oi_media_type' => $this->media_type,
				'oi_major_mime' => $major,
				'oi_minor_mime' => $minor,
				'oi_metadata'   => $this->metadata,
				'oi_sha1'       => $this->sha1,
			), array(
				'oi_name' => $this->getName(),
				'oi_archive_name' => $this->archive_name ),
			__METHOD__
		);
		wfProfileOut( __METHOD__ );
	}

	/**
	 * @param $field Integer: one of DELETED_* bitfield constants
	 *               for file or revision rows
	 * @return bool
	 */
	function isDeleted( $field ) {
		$this->load();
		return ($this->deleted & $field) == $field;
	}

	/**
	 * Returns bitfield value
	 * @return int
	 */
	function getVisibility() {
		$this->load();
		return (int)$this->deleted;
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this image file, if it's marked as deleted.
	 *
	 * @param $field Integer
	 * @param $user User object to check, or null to use $wgUser
	 * @return bool
	 */
	function userCan( $field, User $user = null ) {
		$this->load();
		return Revision::userCanBitfield( $this->deleted, $field, $user );
	}

	/**
	 * Upload a file directly into archive. Generally for Special:Import.
	 *
	 * @param $srcPath string File system path of the source file
	 * @param $archiveName string Full archive name of the file, in the form
	 *     $timestamp!$filename, where $filename must match $this->getName()
	 *
	 * @param $timestamp string
	 * @param $comment string
	 * @param $user
	 * @param $flags int
	 * @return FileRepoStatus
	 */
	function uploadOld( $srcPath, $archiveName, $timestamp, $comment, $user, $flags = 0 ) {
		$this->lock();

		$dstRel = 'archive/' . $this->getHashPath() . $archiveName;
		$status = $this->publishTo( $srcPath, $dstRel,
			$flags & File::DELETE_SOURCE ? FileRepo::DELETE_SOURCE : 0
		);

		if ( $status->isGood() ) {
			if ( !$this->recordOldUpload( $srcPath, $archiveName, $timestamp, $comment, $user ) ) {
				$status->fatal( 'filenotfound', $srcPath );
			}
		}

		$this->unlock();

		return $status;
	}

	/**
	 * Record a file upload in the oldimage table, without adding log entries.
	 *
	 * @param $srcPath string File system path to the source file
	 * @param $archiveName string The archive name of the file
	 * @param $timestamp string
	 * @param $comment string Upload comment
	 * @param $user User User who did this upload
	 * @return bool
	 */
	function recordOldUpload( $srcPath, $archiveName, $timestamp, $comment, $user ) {
		$dbw = $this->repo->getMasterDB();
		$dbw->begin( __METHOD__ );

		$dstPath = $this->repo->getZonePath( 'public' ) . '/' . $this->getRel();
		$props = $this->repo->getFileProps( $dstPath );
		if ( !$props['fileExists'] ) {
			return false;
		}

		$dbw->insert( 'oldimage',
			array(
				'oi_name'         => $this->getName(),
				'oi_archive_name' => $archiveName,
				'oi_size'         => $props['size'],
				'oi_width'        => intval( $props['width'] ),
				'oi_height'       => intval( $props['height'] ),
				'oi_bits'         => $props['bits'],
				'oi_timestamp'    => $dbw->timestamp( $timestamp ),
				'oi_description'  => $comment,
				'oi_user'         => $user->getId(),
				'oi_user_text'    => $user->getName(),
				'oi_metadata'     => $props['metadata'],
				'oi_media_type'   => $props['media_type'],
				'oi_major_mime'   => $props['major_mime'],
				'oi_minor_mime'   => $props['minor_mime'],
				'oi_sha1'         => $props['sha1'],
			), __METHOD__
		);

		$dbw->commit( __METHOD__ );

		return true;
	}

}
