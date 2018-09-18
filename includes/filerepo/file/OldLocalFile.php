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

use MediaWiki\MediaWikiServices;

/**
 * Class to represent a file in the oldimage table
 *
 * @ingroup FileAbstraction
 */
class OldLocalFile extends LocalFile {
	/** @var string|int Timestamp */
	protected $requestedTime;

	/** @var string Archive name */
	protected $archive_name;

	const CACHE_VERSION = 1;
	const MAX_CACHE_ROWS = 20;

	/**
	 * @param Title $title
	 * @param FileRepo $repo
	 * @param string|int|null $time
	 * @return self
	 * @throws MWException
	 */
	static function newFromTitle( $title, $repo, $time = null ) {
		# The null default value is only here to avoid an E_STRICT
		if ( $time === null ) {
			throw new MWException( __METHOD__ . ' got null for $time parameter' );
		}

		return new self( $title, $repo, $time, null );
	}

	/**
	 * @param Title $title
	 * @param FileRepo $repo
	 * @param string $archiveName
	 * @return self
	 */
	static function newFromArchiveName( $title, $repo, $archiveName ) {
		return new self( $title, $repo, null, $archiveName );
	}

	/**
	 * @param stdClass $row
	 * @param FileRepo $repo
	 * @return self
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
	 * @param string $sha1 Base-36 SHA-1
	 * @param LocalRepo $repo
	 * @param string|bool $timestamp MW_timestamp (optional)
	 *
	 * @return bool|OldLocalFile
	 */
	static function newFromKey( $sha1, $repo, $timestamp = false ) {
		$dbr = $repo->getReplicaDB();

		$conds = [ 'oi_sha1' => $sha1 ];
		if ( $timestamp ) {
			$conds['oi_timestamp'] = $dbr->timestamp( $timestamp );
		}

		$fileQuery = self::getQueryInfo();
		$row = $dbr->selectRow(
			$fileQuery['tables'], $fileQuery['fields'], $conds, __METHOD__, [], $fileQuery['joins']
		);
		if ( $row ) {
			return self::newFromRow( $row, $repo );
		} else {
			return false;
		}
	}

	/**
	 * Fields in the oldimage table
	 * @deprecated since 1.31, use self::getQueryInfo() instead.
	 * @return string[]
	 */
	static function selectFields() {
		global $wgActorTableSchemaMigrationStage;

		wfDeprecated( __METHOD__, '1.31' );
		if ( $wgActorTableSchemaMigrationStage & SCHEMA_COMPAT_READ_NEW ) {
			// If code is using this instead of self::getQueryInfo(), there's a
			// decent chance it's going to try to directly access
			// $row->oi_user or $row->oi_user_text and we can't give it
			// useful values here once those aren't being used anymore.
			throw new BadMethodCallException(
				'Cannot use ' . __METHOD__
					. ' when $wgActorTableSchemaMigrationStage has SCHEMA_COMPAT_READ_NEW'
			);
		}

		return [
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
			'oi_user',
			'oi_user_text',
			'oi_actor' => 'NULL',
			'oi_timestamp',
			'oi_deleted',
			'oi_sha1',
		] + MediaWikiServices::getInstance()->getCommentStore()->getFields( 'oi_description' );
	}

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new oldlocalfile object.
	 * @since 1.31
	 * @param string[] $options
	 *   - omit-lazy: Omit fields that are lazily cached.
	 * @return array[] With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
	 */
	public static function getQueryInfo( array $options = [] ) {
		$commentQuery = MediaWikiServices::getInstance()->getCommentStore()->getJoin( 'oi_description' );
		$actorQuery = ActorMigration::newMigration()->getJoin( 'oi_user' );
		$ret = [
			'tables' => [ 'oldimage' ] + $commentQuery['tables'] + $actorQuery['tables'],
			'fields' => [
				'oi_name',
				'oi_archive_name',
				'oi_size',
				'oi_width',
				'oi_height',
				'oi_bits',
				'oi_media_type',
				'oi_major_mime',
				'oi_minor_mime',
				'oi_timestamp',
				'oi_deleted',
				'oi_sha1',
			] + $commentQuery['fields'] + $actorQuery['fields'],
			'joins' => $commentQuery['joins'] + $actorQuery['joins'],
		];

		if ( in_array( 'omit-nonlazy', $options, true ) ) {
			// Internal use only for getting only the lazy fields
			$ret['fields'] = [];
		}
		if ( !in_array( 'omit-lazy', $options, true ) ) {
			// Note: Keep this in sync with self::getLazyCacheFields()
			$ret['fields'][] = 'oi_metadata';
		}

		return $ret;
	}

	/**
	 * @param Title $title
	 * @param FileRepo $repo
	 * @param string|int|null $time Timestamp or null to load by archive name
	 * @param string|null $archiveName Archive name or null to load by timestamp
	 * @throws MWException
	 */
	function __construct( $title, $repo, $time, $archiveName ) {
		parent::__construct( $title, $repo );
		$this->requestedTime = $time;
		$this->archive_name = $archiveName;
		if ( is_null( $time ) && is_null( $archiveName ) ) {
			throw new MWException( __METHOD__ . ': must specify at least one of $time or $archiveName' );
		}
	}

	/**
	 * @return bool
	 */
	function getCacheKey() {
		return false;
	}

	/**
	 * @return string
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
		return $this->exists() && !$this->isDeleted( File::DELETED_FILE );
	}

	function loadFromDB( $flags = 0 ) {
		$this->dataLoaded = true;

		$dbr = ( $flags & self::READ_LATEST )
			? $this->repo->getMasterDB()
			: $this->repo->getReplicaDB();

		$conds = [ 'oi_name' => $this->getName() ];
		if ( is_null( $this->requestedTime ) ) {
			$conds['oi_archive_name'] = $this->archive_name;
		} else {
			$conds['oi_timestamp'] = $dbr->timestamp( $this->requestedTime );
		}
		$fileQuery = static::getQueryInfo();
		$row = $dbr->selectRow(
			$fileQuery['tables'],
			$fileQuery['fields'],
			$conds,
			__METHOD__,
			[ 'ORDER BY' => 'oi_timestamp DESC' ],
			$fileQuery['joins']
		);
		if ( $row ) {
			$this->loadFromRow( $row, 'oi_' );
		} else {
			$this->fileExists = false;
		}
	}

	/**
	 * Load lazy file metadata from the DB
	 */
	protected function loadExtraFromDB() {
		$this->extraDataLoaded = true;
		$dbr = $this->repo->getReplicaDB();
		$conds = [ 'oi_name' => $this->getName() ];
		if ( is_null( $this->requestedTime ) ) {
			$conds['oi_archive_name'] = $this->archive_name;
		} else {
			$conds['oi_timestamp'] = $dbr->timestamp( $this->requestedTime );
		}
		$fileQuery = static::getQueryInfo( [ 'omit-nonlazy' ] );
		// In theory the file could have just been renamed/deleted...oh well
		$row = $dbr->selectRow(
			$fileQuery['tables'],
			$fileQuery['fields'],
			$conds,
			__METHOD__,
			[ 'ORDER BY' => 'oi_timestamp DESC' ],
			$fileQuery['joins']
		);

		if ( !$row ) { // fallback to master
			$dbr = $this->repo->getMasterDB();
			$row = $dbr->selectRow(
				$fileQuery['tables'],
				$fileQuery['fields'],
				$conds,
				__METHOD__,
				[ 'ORDER BY' => 'oi_timestamp DESC' ],
				$fileQuery['joins']
			);
		}

		if ( $row ) {
			foreach ( $this->unprefixRow( $row, 'oi_' ) as $name => $value ) {
				$this->$name = $value;
			}
		} else {
			throw new MWException( "Could not find data for image '{$this->archive_name}'." );
		}
	}

	/** @inheritDoc */
	protected function getCacheFields( $prefix = 'img_' ) {
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
		$this->loadFromFile();

		# Don't destroy file info of missing files
		if ( !$this->fileExists ) {
			wfDebug( __METHOD__ . ": file does not exist, aborting\n" );

			return;
		}

		$dbw = $this->repo->getMasterDB();
		list( $major, $minor ) = self::splitMime( $this->mime );

		wfDebug( __METHOD__ . ': upgrading ' . $this->archive_name . " to the current schema\n" );
		$dbw->update( 'oldimage',
			[
				'oi_size' => $this->size, // sanity
				'oi_width' => $this->width,
				'oi_height' => $this->height,
				'oi_bits' => $this->bits,
				'oi_media_type' => $this->media_type,
				'oi_major_mime' => $major,
				'oi_minor_mime' => $minor,
				'oi_metadata' => $this->metadata,
				'oi_sha1' => $this->sha1,
			], [
				'oi_name' => $this->getName(),
				'oi_archive_name' => $this->archive_name ],
			__METHOD__
		);
	}

	/**
	 * @param int $field One of DELETED_* bitfield constants for file or
	 *   revision rows
	 * @return bool
	 */
	function isDeleted( $field ) {
		$this->load();

		return ( $this->deleted & $field ) == $field;
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
	 * @param int $field
	 * @param User|null $user User object to check, or null to use $wgUser
	 * @return bool
	 */
	function userCan( $field, User $user = null ) {
		$this->load();

		return Revision::userCanBitfield( $this->deleted, $field, $user );
	}

	/**
	 * Upload a file directly into archive. Generally for Special:Import.
	 *
	 * @param string $srcPath File system path of the source file
	 * @param string $archiveName Full archive name of the file, in the form
	 *   $timestamp!$filename, where $filename must match $this->getName()
	 * @param string $timestamp
	 * @param string $comment
	 * @param User $user
	 * @return Status
	 */
	function uploadOld( $srcPath, $archiveName, $timestamp, $comment, $user ) {
		$this->lock();

		$dstRel = 'archive/' . $this->getHashPath() . $archiveName;
		$status = $this->publishTo( $srcPath, $dstRel );

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
	 * @param string $srcPath File system path to the source file
	 * @param string $archiveName The archive name of the file
	 * @param string $timestamp
	 * @param string $comment Upload comment
	 * @param User $user User who did this upload
	 * @return bool
	 */
	protected function recordOldUpload( $srcPath, $archiveName, $timestamp, $comment, $user ) {
		$dbw = $this->repo->getMasterDB();

		$dstPath = $this->repo->getZonePath( 'public' ) . '/' . $this->getRel();
		$props = $this->repo->getFileProps( $dstPath );
		if ( !$props['fileExists'] ) {
			return false;
		}

		$commentFields = MediaWikiServices::getInstance()->getCommentStore()
			->insert( $dbw, 'oi_description', $comment );
		$actorFields = ActorMigration::newMigration()->getInsertValues( $dbw, 'oi_user', $user );
		$dbw->insert( 'oldimage',
			[
				'oi_name' => $this->getName(),
				'oi_archive_name' => $archiveName,
				'oi_size' => $props['size'],
				'oi_width' => intval( $props['width'] ),
				'oi_height' => intval( $props['height'] ),
				'oi_bits' => $props['bits'],
				'oi_timestamp' => $dbw->timestamp( $timestamp ),
				'oi_metadata' => $props['metadata'],
				'oi_media_type' => $props['media_type'],
				'oi_major_mime' => $props['major_mime'],
				'oi_minor_mime' => $props['minor_mime'],
				'oi_sha1' => $props['sha1'],
			] + $commentFields + $actorFields, __METHOD__
		);

		return true;
	}

	/**
	 * If archive name is an empty string, then file does not "exist"
	 *
	 * This is the case for a couple files on Wikimedia servers where
	 * the old version is "lost".
	 * @return bool
	 */
	public function exists() {
		$archiveName = $this->getArchiveName();
		if ( $archiveName === '' || !is_string( $archiveName ) ) {
			return false;
		}
		return parent::exists();
	}
}
