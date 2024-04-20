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
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\SelectQueryBuilder;

class FileSelectQueryBuilder extends SelectQueryBuilder {

	/**
	 * @internal use ::newFor* instead.
	 * @param IReadableDatabase $db
	 * @param string $type either 'file', 'oldfile' or 'archivedfile'
	 * @param array $options
	 *   - omit-lazy: Omit fields that are lazily cached.
	 */
	public function __construct( IReadableDatabase $db, string $type = 'file', array $options = [] ) {
		parent::__construct( $db );
		if ( $type === 'file' ) {
			$this->initFile( $options );
		} elseif ( $type === 'oldfile' ) {
			$this->initOldFile( $options );
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

	private function initFile( $options ) {
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

	private function initOldFile( $options ) {
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

	private function initArchivedFile( $options ) {
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
