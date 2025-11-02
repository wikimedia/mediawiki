<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\FileRepo\File;

use InvalidArgumentException;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;

class FileSelectQueryBuilder extends SelectQueryBuilder {

	private int $migrationStage;

	/**
	 * @internal use ::newFor* instead.
	 * @param IReadableDatabase $db
	 * @param string $type either 'file', 'oldfile' or 'archivedfile'
	 * @param array $options
	 *   - omit-lazy: Omit fields that are lazily cached.
	 */
	public function __construct( IReadableDatabase $db, string $type = 'file', array $options = [] ) {
		parent::__construct( $db );
		$this->migrationStage = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::FileSchemaMigrationStage
		);
		if ( $type === 'file' ) {
			if ( $this->migrationStage & SCHEMA_COMPAT_READ_OLD ) {
				$this->initFileOld( $options );
			} else {
				$this->initFileNew( $options );
			}
		} elseif ( $type === 'oldfile' ) {
			if ( $this->migrationStage & SCHEMA_COMPAT_READ_OLD ) {
				$this->initOldFileOld( $options );
			} else {
				$this->initOldFileNew( $options );
			}
		} elseif ( $type === 'archivedfile' ) {
			$this->initArchivedFile( $options );
		} else {
			throw new InvalidArgumentException( "Type $type is not among accepted values" );
		}
	}

	public static function newForFile( IReadableDatabase $db, array $options = [] ): FileSelectQueryBuilder {
		return new FileSelectQueryBuilder( $db, 'file', $options );
	}

	public static function newForOldFile( IReadableDatabase $db, array $options = [] ): FileSelectQueryBuilder {
		return new FileSelectQueryBuilder( $db, 'oldfile', $options );
	}

	public static function newForArchivedFile( IReadableDatabase $db, array $options = [] ): FileSelectQueryBuilder {
		return new FileSelectQueryBuilder( $db, 'archivedfile', $options );
	}

	private function initFileOld( array $options ) {
		$this->table( 'image' )
			->join( 'actor', 'image_actor', 'actor_id=img_actor' )
			->join(
				'comment',
				'comment_img_description',
				'comment_img_description.comment_id = img_description_id'
			);

		if ( !in_array( 'omit-nonlazy', $options, true ) ) {
			$this->fields(
				[
					'img_name',
					'img_size',
					'img_width',
					'img_height',
					'img_metadata',
					'img_bits',
					'img_media_type',
					'img_major_mime',
					'img_minor_mime',
					'img_timestamp',
					'img_sha1',
					'img_actor',
					'img_user' => 'image_actor.actor_user',
					'img_user_text' => 'image_actor.actor_name',
					'img_description_text' => 'comment_img_description.comment_text',
					'img_description_data' => 'comment_img_description.comment_data',
					'img_description_cid' => 'comment_img_description.comment_id'
				]
			);
		}
		if ( !in_array( 'omit-lazy', $options, true ) ) {
			// Note: Keep this in sync with LocalFile::getLazyCacheFields() and
			// LocalFile::loadExtraFromDB()
			$this->field( 'img_metadata' );
		}
	}

	private function initFileNew( array $options ) {
		$subquery = $this->newSubquery();
		$subquery->table( 'file' )
			->join( 'filerevision', null, 'file_latest = fr_id' )
			->join( 'filetypes', null, 'file_type = ft_id' )
			->join( 'actor', 'image_actor', 'actor_id=fr_actor' )
			->join(
				'comment',
				'comment_img_description',
				'comment_img_description.comment_id = fr_description_id'
			);

		if ( !in_array( 'omit-nonlazy', $options, true ) ) {
			$subquery->fields(
				[
					'img_file_id' => 'file_id',
					'img_filerevision_id' => 'fr_id',
					'img_name' => 'file_name',
					'img_size' => 'fr_size',
					'img_width' => 'fr_width',
					'img_height' => 'fr_height',
					'img_metadata' => 'fr_metadata',
					'img_bits' => 'fr_bits',
					'img_media_type' => 'ft_media_type',
					'img_major_mime' => 'ft_major_mime',
					'img_minor_mime' => 'ft_minor_mime',
					'img_timestamp' => 'fr_timestamp',
					'img_sha1' => 'fr_sha1',
					'img_actor' => 'fr_actor',
					'img_user' => 'image_actor.actor_user',
					'img_user_text' => 'image_actor.actor_name',
					'img_description_text' => 'comment_img_description.comment_text',
					'img_description_data' => 'comment_img_description.comment_data',
					'img_description_cid' => 'comment_img_description.comment_id'
				]
			);
		}
		if ( !in_array( 'omit-lazy', $options, true ) ) {
			// Note: Keep this in sync with LocalFile::getLazyCacheFields() and
			// LocalFile::loadExtraFromDB()
			$subquery->field( 'fr_metadata', 'img_metadata' );
		}

		$subquery->where( [ 'file_deleted' => 0 ] );

		// Without the wrapper, callers can't make conditions
		// on the old field names so all of them would need updating.
		// See https://stackoverflow.com/a/8370146
		$this->field( '*' )
			->from( $subquery );
	}

	private function initOldFileOld( array $options ) {
		$this->table( 'oldimage' )
			->join( 'actor', 'oldimage_actor', 'actor_id=oi_actor' )
			->join(
				'comment',
				'comment_oi_description',
				'comment_oi_description.comment_id = oi_description_id'
			);

		if ( !in_array( 'omit-nonlazy', $options, true ) ) {
			$this->fields(
				[
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
					'oi_user_text' => 'oldimage_actor.actor_name',
					'oi_description_text' => 'comment_oi_description.comment_text',
					'oi_description_data' => 'comment_oi_description.comment_data',
					'oi_description_cid' => 'comment_oi_description.comment_id'
				]
			);
		}
		if ( !in_array( 'omit-lazy', $options, true ) ) {
			// Note: Keep this in sync with LocalFile::getLazyCacheFields() and
			// LocalFile::loadExtraFromDB()
			$this->field( 'oi_metadata' );
		}
	}

	private function initOldFileNew( array $options ) {
		$subquery = $this->newSubquery();
		$subquery->table( 'filerevision' )
			->join( 'file', null, 'fr_file = file_id' )
			->join( 'filetypes', null, 'file_type = ft_id' )
			->join( 'actor', 'oldimage_actor', 'actor_id = fr_actor' )
			->join(
				'comment',
				'comment_oi_description',
				'comment_oi_description.comment_id = fr_description_id'
			);

		if ( !in_array( 'omit-nonlazy', $options, true ) ) {
			$subquery->fields(
				[
					'oi_file_id' => 'file_id',
					'oi_filerevision_id' => 'fr_id',
					'oi_name' => 'file_name',
					'oi_archive_name' => 'fr_archive_name',
					'oi_size' => 'fr_size',
					'oi_width' => 'fr_width',
					'oi_height' => 'fr_height',
					'oi_bits' => 'fr_bits',
					'oi_media_type' => 'ft_media_type',
					'oi_major_mime' => 'ft_major_mime',
					'oi_minor_mime' => 'ft_minor_mime',
					'oi_timestamp' => 'fr_timestamp',
					'oi_deleted' => 'fr_deleted',
					'oi_sha1' => 'fr_sha1',
					'oi_actor' => 'fr_actor',
					'oi_user' => 'oldimage_actor.actor_user',
					'oi_user_text' => 'oldimage_actor.actor_name',
					'oi_description_text' => 'comment_oi_description.comment_text',
					'oi_description_data' => 'comment_oi_description.comment_data',
					'oi_description_cid' => 'comment_oi_description.comment_id'
				]
			);
		}
		if ( !in_array( 'omit-lazy', $options, true ) ) {
			// Note: Keep this in sync with LocalFile::getLazyCacheFields() and
			// LocalFile::loadExtraFromDB()
			$subquery->field( 'fr_metadata', 'oi_metadata' );
		}

		$subquery->where( 'file_latest != fr_id' );
		$this->field( '*' )
			->from( $subquery );
	}

	private function initArchivedFile( array $options ) {
		$this->table( 'filearchive' )
			->join( 'actor', 'filearchive_actor', 'actor_id=fa_actor' )
			->join(
				'comment',
				'comment_fa_description',
				'comment_fa_description.comment_id = fa_description_id'
			);

		if ( !in_array( 'omit-nonlazy', $options, true ) ) {
			$this->fields(
				[
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
					'fa_user_text' => 'filearchive_actor.actor_name',
					'fa_description_text' => 'comment_fa_description.comment_text',
					'fa_description_data' => 'comment_fa_description.comment_data',
					'fa_description_cid' => 'comment_fa_description.comment_id'
				]
			);
		}
		if ( !in_array( 'omit-lazy', $options, true ) ) {
			// Note: Keep this in sync with LocalFile::getLazyCacheFields() and
			// LocalFile::loadExtraFromDB()
			$this->field( 'fa_metadata' );
		}
	}
}
