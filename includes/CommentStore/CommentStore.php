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

namespace MediaWiki\CommentStore;

use Language;
use MediaWiki\MediaWikiServices;

/**
 * @defgroup CommentStore CommentStore
 *
 * The Comment store in MediaWiki is responsible for storing edit summaries,
 * log action comments and other such short strings (referred to as "comments").
 *
 * The CommentStore class handles the database abstraction for reading
 * and writing comments, which are represented by CommentStoreComment objects.
 *
 * Data is internally stored in the `comment` table.
 */

/**
 * Handle database storage of comments such as edit summaries and log reasons.
 *
 * @ingroup CommentStore
 * @since 1.30
 */
class CommentStore extends CommentStoreBase {
	/**
	 * Define fields that use temporary tables for transitional purposes
	 * Array keys are field names, values are arrays with these possible fields:
	 *  - table: Temporary table name
	 *  - pk: Temporary table column referring to the main table's primary key
	 *  - field: Temporary table column referring comment.comment_id
	 *  - joinPK: Main table's primary key
	 *  - stage: Migration stage
	 *  - deprecatedIn: Version when using insertWithTempTable() was deprecated
	 */
	protected const TEMP_TABLES = [
		'rev_comment' => [
			'table' => 'revision_comment_temp',
			'pk' => 'revcomment_rev',
			'field' => 'revcomment_comment_id',
			'joinPK' => 'rev_id',
			'stage' => MIGRATION_OLD,
			'deprecatedIn' => null,
		],
	];

	/**
	 * @param Language $lang Language to use for comment truncation. Defaults
	 *  to content language.
	 * @param int $stage One of the MIGRATION_* constants, or an appropriate
	 *  combination of SCHEMA_COMPAT_* constants. Always MIGRATION_NEW for
	 *  MediaWiki core since 1.33.
	 * @param array $tempTableStageOverrides
	 */
	public function __construct( Language $lang, $stage, $tempTableStageOverrides ) {
		parent::__construct( self::TEMP_TABLES, $lang, $stage );

		foreach ( $tempTableStageOverrides as $key => $stageOverride ) {
			$this->tempTables[$key]['stage'] = $stageOverride;
		}
	}

	/**
	 * @since 1.31
	 * @deprecated in 1.31 Use DI to inject a CommentStore instance into your class. Hard-deprecated since 1.40.
	 * @return CommentStore
	 */
	public static function getStore() {
		wfDeprecated( __METHOD__, '1.31' );
		return MediaWikiServices::getInstance()->getCommentStore();
	}
}

class_alias( CommentStore::class, 'CommentStore' );
