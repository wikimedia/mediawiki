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

/**
 * Old file in the oldimage table.
 *
 * @stable to extend
 * @ingroup FileAbstraction
 */
class OldLocalFile extends LocalFile {
	/** @var string|int Timestamp */
	protected $requestedTime;

	/** @var string Archive name */
	protected $archive_name;

	public const CACHE_VERSION = 1;

	/**
	 * @stable to override
	 * @param Title $title
	 * @param LocalRepo $repo
	 * @param string|int|null $time
	 * @return static
	 * @throws MWException
	 */
	public static function newFromTitle( $title, $repo, $time = null ) {
		# The null default value is only here to avoid an E_STRICT
		if ( $time === null ) {
			throw new MWException( __METHOD__ . ' got null for $time parameter' );
		}

		return new static( $title, $repo, $time, null );
	}

	/**
	 * @stable to override
	 *
	 * @param Title $title
	 * @param LocalRepo $repo
	 * @param string $archiveName
	 * @return static
	 */
	public static function newFromArchiveName( $title, $repo, $archiveName ) {
		return new static( $title, $repo, null, $archiveName );
	}

	/**
	 * @stable to override
	 *
	 * @param stdClass $row
	 * @param LocalRepo $repo
	 * @return static
	 */
	public static function newFromRow( $row, $repo ) {
		$title = Title::makeTitle( NS_FILE, $row->oi_name );
		$file = new static( $title, $repo, null, $row->oi_archive_name );
		$file->loadFromRow( $row, 'oi_' );

		return $file;
	}

	/**
	 * Create a OldLocalFile from a SHA-1 key
	 * Do not call this except from inside a repo class.
	 *
	 * @stable to override
	 *
	 * @param string $sha1 Base-36 SHA-1
	 * @param LocalRepo $repo
	 * @param string|bool $timestamp MW_timestamp (optional)
	 *
	 * @return bool|OldLocalFile
	 */
	public static function newFromKey( $sha1, $repo, $timestamp = false ) {
		$dbr = $repo->getReplicaDB();

		$conds = [ 'oi_sha1' => $sha1 ];
		if ( $timestamp ) {
			$conds['oi_timestamp'] = $dbr->timestamp( $timestamp );
		}

		$fileQuery = static::getQueryInfo();
		$row = $dbr->selectRow(
			$fileQuery['tables'], $fileQuery['fields'], $conds, __METHOD__, [], $fileQuery['joins']
		);
		if ( $row ) {
			return static::newFromRow( $row, $repo );
		} else {
			return false;
		}
	}

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new oldlocalfile object.
	 *
	 * Since 1.34, oi_user and oi_user_text have not been present in the
	 * database, but they continue to be available in query results as
	 * aliases.
	 *
	 * @since 1.31
	 * @stable to override
	 *
	 * @param string[] $options
	 *   - omit-lazy: Omit fields that are lazily cached.
	 * @return array[] With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()` or `SelectQueryBuilder::tables`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()` or `SelectQueryBuilder::fields`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()` or `SelectQueryBuilder::joinConds`
	 * @phan-return array{tables:string[],fields:string[],joins:array}
	 */
	public static function getQueryInfo( array $options = [] ) {
		$commentQuery = MediaWikiServices::getInstance()->getCommentStore()->getJoin( 'oi_description' );
		$ret = [
			'tables' => [
				'oldimage',
				'oldimage_actor' => 'actor'
			] + $commentQuery['tables'],
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
				'oi_actor',
				'oi_user' => 'oldimage_actor.actor_user',
				'oi_user_text' => 'oldimage_actor.actor_name'
			] + $commentQuery['fields'],
			'joins' => [
				'oldimage_actor' => [ 'JOIN', 'actor_id=oi_actor' ]
			] + $commentQuery['joins'],
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
	 * @stable to call
	 *
	 * @param Title $title
	 * @param LocalRepo $repo
	 * @param string|int|null $time Timestamp or null to load by archive name
	 * @param string|null $archiveName Archive name or null to load by timestamp
	 * @throws MWException
	 */
	public function __construct( $title, $repo, $time, $archiveName ) {
		parent::__construct( $title, $repo );
		$this->requestedTime = $time;
		$this->archive_name = $archiveName;
		if ( $time === null && $archiveName === null ) {
			throw new MWException( __METHOD__ . ': must specify at least one of $time or $archiveName' );
		}
	}

	public function loadFromRow( $row, $prefix = 'img_' ) {
		$this->archive_name = $row->{"{$prefix}archive_name"};
		$this->deleted = $row->{"{$prefix}deleted"};
		$row = clone $row;
		unset( $row->{"{$prefix}archive_name"} );
		unset( $row->{"{$prefix}deleted"} );
		parent::loadFromRow( $row, $prefix );
	}

	/**
	 * @stable to override
	 * @return bool
	 */
	protected function getCacheKey() {
		return false;
	}

	/**
	 * @stable to override
	 * @return string
	 */
	public function getArchiveName() {
		if ( !isset( $this->archive_name ) ) {
			$this->load();
		}

		return $this->archive_name;
	}

	/**
	 * @return bool
	 */
	public function isOld() {
		return true;
	}

	/**
	 * @return bool
	 */
	public function isVisible() {
		return $this->exists() && !$this->isDeleted( File::DELETED_FILE );
	}

	/**
	 * @stable to override
	 * @param int $flags
	 */
	protected function loadFromDB( $flags = 0 ) {
		$this->dataLoaded = true;

		$dbr = ( $flags & self::READ_LATEST )
			? $this->repo->getPrimaryDB()
			: $this->repo->getReplicaDB();

		$conds = [ 'oi_name' => $this->getName() ];
		if ( $this->requestedTime === null ) {
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
	 * @stable to override
	 */
	protected function loadExtraFromDB() {
		$this->extraDataLoaded = true;
		$dbr = $this->repo->getReplicaDB();
		$conds = [ 'oi_name' => $this->getName() ];
		if ( $this->requestedTime === null ) {
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

		if ( !$row ) { // fallback to primary DB
			$dbr = $this->repo->getPrimaryDB();
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

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	protected function getCacheFields( $prefix = 'img_' ) {
		$fields = parent::getCacheFields( $prefix );
		$fields[] = $prefix . 'archive_name';
		$fields[] = $prefix . 'deleted';

		return $fields;
	}

	/**
	 * @return string
	 * @stable to override
	 */
	public function getRel() {
		return $this->getArchiveRel( $this->getArchiveName() );
	}

	/**
	 * @return string
	 * @stable to override
	 */
	public function getUrlRel() {
		return $this->getArchiveRel( rawurlencode( $this->getArchiveName() ) );
	}

	/**
	 * @stable to override
	 */
	public function upgradeRow() {
		$this->loadFromFile();

		# Don't destroy file info of missing files
		if ( !$this->fileExists ) {
			wfDebug( __METHOD__ . ": file does not exist, aborting" );

			return;
		}

		$dbw = $this->repo->getPrimaryDB();
		list( $major, $minor ) = self::splitMime( $this->mime );

		wfDebug( __METHOD__ . ': upgrading ' . $this->archive_name . " to the current schema" );
		$dbw->update( 'oldimage',
			[
				'oi_size' => $this->size,
				'oi_width' => $this->width,
				'oi_height' => $this->height,
				'oi_bits' => $this->bits,
				'oi_media_type' => $this->media_type,
				'oi_major_mime' => $major,
				'oi_minor_mime' => $minor,
				'oi_metadata' => $this->getMetadataForDb( $dbw ),
				'oi_sha1' => $this->sha1,
			], [
				'oi_name' => $this->getName(),
				'oi_archive_name' => $this->archive_name ],
			__METHOD__
		);
	}

	protected function reserializeMetadata() {
		// TODO: implement this and make it possible to hit it from refreshImageMetadata.php
		// It can be hit from action=purge but that's not very useful if the
		// goal is to reserialize the whole oldimage table.
	}

	/**
	 * @param int $field One of DELETED_* bitfield constants for file or
	 *   revision rows
	 * @return bool
	 */
	public function isDeleted( $field ) {
		$this->load();

		return ( $this->deleted & $field ) == $field;
	}

	/**
	 * Returns bitfield value
	 * @return int
	 */
	public function getVisibility() {
		$this->load();

		return (int)$this->deleted;
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this image file, if it's marked as deleted.
	 *
	 * @param int $field
	 * @param Authority $performer User object to check
	 * @return bool
	 */
	public function userCan( $field, Authority $performer ) {
		$this->load();

		return RevisionRecord::userCanBitfield(
			$this->deleted,
			$field,
			$performer
		);
	}

	/**
	 * Upload a file directly into archive. Generally for Special:Import.
	 *
	 * @param string $srcPath File system path of the source file
	 * @param string $timestamp
	 * @param string $comment
	 * @param UserIdentity $user
	 * @return Status
	 */
	public function uploadOld( $srcPath, $timestamp, $comment, UserIdentity $user ) {
		$archiveName = $this->getArchiveName();
		$dstRel = $this->getArchiveRel( $archiveName );
		$status = $this->publishTo( $srcPath, $dstRel );

		if ( $status->isGood() &&
			!$this->recordOldUpload( $srcPath, $archiveName, $timestamp, $comment, $user )
		) {
			$status->fatal( 'filenotfound', $srcPath );
		}

		return $status;
	}

	/**
	 * Record a file upload in the oldimage table, without adding log entries.
	 * @stable to override
	 *
	 * @param string $srcPath File system path to the source file
	 * @param string $archiveName The archive name of the file
	 * @param string $timestamp
	 * @param string $comment Upload comment
	 * @param UserIdentity $user User who did this upload
	 * @return bool
	 */
	protected function recordOldUpload( $srcPath, $archiveName, $timestamp, $comment, $user ) {
		$dbw = $this->repo->getPrimaryDB();

		$services = MediaWikiServices::getInstance();
		$mwProps = new MWFileProps( $services->getMimeAnalyzer() );
		$props = $mwProps->getPropsFromPath( $srcPath, true );
		if ( !$props['fileExists'] ) {
			return false;
		}
		$this->setProps( $props );

		$dbw->startAtomic( __METHOD__ );
		$commentFields = $services->getCommentStore()
			->insert( $dbw, 'oi_description', $comment );
		$actorId = $services->getActorNormalization()
			->acquireActorId( $user, $dbw );
		$dbw->insert( 'oldimage',
			[
				'oi_name' => $this->getName(),
				'oi_archive_name' => $archiveName,
				'oi_size' => $props['size'],
				'oi_width' => intval( $props['width'] ),
				'oi_height' => intval( $props['height'] ),
				'oi_bits' => $props['bits'],
				'oi_actor' => $actorId,
				'oi_timestamp' => $dbw->timestamp( $timestamp ),
				'oi_metadata' => $this->getMetadataForDb( $dbw ),
				'oi_media_type' => $props['media_type'],
				'oi_major_mime' => $props['major_mime'],
				'oi_minor_mime' => $props['minor_mime'],
				'oi_sha1' => $props['sha1'],
			] + $commentFields, __METHOD__
		);
		$dbw->endAtomic( __METHOD__ );

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
