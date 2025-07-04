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
use LogicException;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Authority;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use MWFileProps;
use RuntimeException;
use stdClass;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;

/**
 * Old file in the oldimage table.
 *
 * @stable to extend
 * @ingroup FileAbstraction
 */
class OldLocalFile extends LocalFile {
	/** @var string|int Timestamp */
	protected $requestedTime;

	/** @var string|null Archive name */
	protected $archive_name;

	public const CACHE_VERSION = 1;

	/**
	 * @stable to override
	 * @param Title $title
	 * @param LocalRepo $repo
	 * @param string|int|null $time
	 * @return static
	 */
	public static function newFromTitle( $title, $repo, $time = null ) {
		# The null default value is only here to avoid an E_STRICT
		if ( $time === null ) {
			throw new InvalidArgumentException( __METHOD__ . ' got null for $time parameter' );
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
	 * @param string|false $timestamp MW_timestamp (optional)
	 *
	 * @return static|false
	 */
	public static function newFromKey( $sha1, $repo, $timestamp = false ) {
		$dbr = $repo->getReplicaDB();
		$queryBuilder = FileSelectQueryBuilder::newForOldFile( $dbr );

		$queryBuilder->where( [ 'oi_sha1' => $sha1 ] );
		if ( $timestamp ) {
			$queryBuilder->andWhere( [ 'oi_timestamp' => $dbr->timestamp( $timestamp ) ] );
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
	 * a new oldlocalfile object.
	 *
	 * Since 1.34, oi_user and oi_user_text have not been present in the
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
		$queryInfo = FileSelectQueryBuilder::newForOldFile( $dbr, $options )->getQueryInfo();
		return [
			'tables' => $queryInfo['tables'],
			'fields' => $queryInfo['fields'],
			'joins' => $queryInfo['join_conds'],
		];
	}

	/**
	 * @stable to call
	 *
	 * @param Title $title
	 * @param LocalRepo $repo
	 * @param string|int|null $time Timestamp or null to load by archive name
	 * @param string|null $archiveName Archive name or null to load by timestamp
	 */
	public function __construct( $title, $repo, $time, $archiveName ) {
		parent::__construct( $title, $repo );
		$this->requestedTime = $time;
		$this->archive_name = $archiveName;
		if ( $time === null && $archiveName === null ) {
			throw new LogicException( __METHOD__ . ': must specify at least one of $time or $archiveName' );
		}
	}

	/** @inheritDoc */
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
		if ( $this->archive_name === null ) {
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

		$dbr = ( $flags & IDBAccessObject::READ_LATEST )
			? $this->repo->getPrimaryDB()
			: $this->repo->getReplicaDB();
		$queryBuilder = $this->buildQueryBuilderForLoad( $dbr, [] );
		$row = $queryBuilder->caller( __METHOD__ )->fetchRow();
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
		$queryBuilder = $this->buildQueryBuilderForLoad( $dbr );

		// In theory the file could have just been renamed/deleted...oh well
		$row = $queryBuilder->caller( __METHOD__ )->fetchRow();

		if ( !$row ) { // fallback to primary DB
			$dbr = $this->repo->getPrimaryDB();
			$queryBuilder = $this->buildQueryBuilderForLoad( $dbr );
			$row = $queryBuilder->caller( __METHOD__ )->fetchRow();
		}

		if ( $row ) {
			foreach ( $this->unprefixRow( $row, 'oi_' ) as $name => $value ) {
				$this->$name = $value;
			}
		} else {
			throw new RuntimeException( "Could not find data for image '{$this->archive_name}'." );
		}
	}

	private function buildQueryBuilderForLoad(
		IReadableDatabase $dbr, array $options = [ 'omit-nonlazy' ]
	): FileSelectQueryBuilder {
		$queryBuilder = FileSelectQueryBuilder::newForOldFile( $dbr, $options );
		$queryBuilder->where( [ 'oi_name' => $this->getName() ] )
			->orderBy( 'oi_timestamp', SelectQueryBuilder::SORT_DESC );
		if ( $this->requestedTime === null ) {
			$queryBuilder->andWhere( [ 'oi_archive_name' => $this->archive_name ] );
		} else {
			$queryBuilder->andWhere( [ 'oi_timestamp' => $dbr->timestamp( $this->requestedTime ) ] );
		}
		return $queryBuilder;
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
		[ $major, $minor ] = self::splitMime( $this->mime );
		$metadata = $this->getMetadataForDb( $dbw );

		wfDebug( __METHOD__ . ': upgrading ' . $this->archive_name . " to the current schema" );
		$dbw->newUpdateQueryBuilder()
			->update( 'oldimage' )
			->set( [
				'oi_size' => $this->size,
				'oi_width' => $this->width,
				'oi_height' => $this->height,
				'oi_bits' => $this->bits,
				'oi_media_type' => $this->media_type,
				'oi_major_mime' => $major,
				'oi_minor_mime' => $minor,
				'oi_metadata' => $metadata,
				'oi_sha1' => $this->sha1,
			] )
			->where( [
				'oi_name' => $this->getName(),
				'oi_archive_name' => $this->archive_name,
			] )
			->caller( __METHOD__ )->execute();

		$migrationStage = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::FileSchemaMigrationStage
		);
		if ( $migrationStage & SCHEMA_COMPAT_WRITE_NEW ) {
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
				->where( [
					'fr_file' => $this->acquireFileIdFromName(),
					'fr_archive_name' => $this->archive_name,
				] )
				->caller( __METHOD__ )->execute();
		}
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
		$dbw->newInsertQueryBuilder()
			->insertInto( 'oldimage' )
			->row( [
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
			] + $commentFields )
			->caller( __METHOD__ )->execute();

		$migrationStage = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::FileSchemaMigrationStage
		);
		if ( $migrationStage & SCHEMA_COMPAT_WRITE_NEW ) {
			$commentFields = $services->getCommentStore()
				->insert( $dbw, 'fr_description', $comment );
			$dbw->newInsertQueryBuilder()
				->insertInto( 'filerevision' )
				->ignore()
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
					] + $commentFields )
				->caller( __METHOD__ )->execute();
		}

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

/** @deprecated class alias since 1.44 */
class_alias( OldLocalFile::class, 'OldLocalFile' );
