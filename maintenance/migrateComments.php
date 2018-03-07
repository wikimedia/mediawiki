<?php
/**
 * Migrate comments from pre-1.30 columns to the 'comment' table
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
 * @ingroup Maintenance
 */

use Wikimedia\Rdbms\IDatabase;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that migrates comments from pre-1.30 columns to the
 * 'comment' table
 *
 * @ingroup Maintenance
 */
class MigrateComments extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Migrates comments from pre-1.30 columns to the \'comment\' table' );
		$this->setBatchSize( 100 );
	}

	protected function getUpdateKey() {
		return __CLASS__;
	}

	protected function updateSkippedMessage() {
		return 'comments already migrated.';
	}

	protected function doDBUpdates() {
		global $wgCommentTableSchemaMigrationStage;

		if ( $wgCommentTableSchemaMigrationStage < MIGRATION_WRITE_NEW ) {
			$this->output(
				"...cannot update while \$wgCommentTableSchemaMigrationStage < MIGRATION_WRITE_NEW\n"
			);
			return false;
		}

		$this->migrateToTemp(
			'revision', 'rev_id', 'rev_comment', 'revcomment_rev', 'revcomment_comment_id'
		);
		$this->migrate( 'archive', 'ar_id', 'ar_comment' );
		$this->migrate( 'ipblocks', 'ipb_id', 'ipb_reason' );
		$this->migrate( 'image', 'img_name', 'img_description' );
		$this->migrate( 'oldimage', [ 'oi_name', 'oi_timestamp' ], 'oi_description' );
		$this->migrate( 'filearchive', 'fa_id', 'fa_deleted_reason' );
		$this->migrate( 'filearchive', 'fa_id', 'fa_description' );
		$this->migrate( 'recentchanges', 'rc_id', 'rc_comment' );
		$this->migrate( 'logging', 'log_id', 'log_comment' );
		$this->migrate( 'protected_titles', [ 'pt_namespace', 'pt_title' ], 'pt_reason' );
		return true;
	}

	/**
	 * Fetch comment IDs for a set of comments
	 * @param IDatabase $dbw
	 * @param array &$comments Keys are comment names, values will be set to IDs.
	 * @return int Count of added comments
	 */
	private function loadCommentIDs( IDatabase $dbw, array &$comments ) {
		$count = 0;
		$needComments = $comments;

		while ( true ) {
			$where = [];
			foreach ( $needComments as $need => $dummy ) {
				$where[] = $dbw->makeList(
					[
						'comment_hash' => CommentStore::hash( $need, null ),
						'comment_text' => $need,
					],
					LIST_AND
				);
			}

			$res = $dbw->select(
				'comment',
				[ 'comment_id', 'comment_text' ],
				[
					$dbw->makeList( $where, LIST_OR ),
					'comment_data' => null,
				],
				__METHOD__
			);
			foreach ( $res as $row ) {
				$comments[$row->comment_text] = $row->comment_id;
				unset( $needComments[$row->comment_text] );
			}

			if ( !$needComments ) {
				break;
			}

			$dbw->insert(
				'comment',
				array_map( function ( $v ) {
					return [
						'comment_hash' => CommentStore::hash( $v, null ),
						'comment_text' => $v,
					];
				}, array_keys( $needComments ) ),
				__METHOD__
			);
			$count += $dbw->affectedRows();
		}
		return $count;
	}

	/**
	 * Migrate comments in a table.
	 *
	 * Assumes any row with the ID field non-zero have already been migrated.
	 * Assumes the new field name is the same as the old with '_id' appended.
	 * Blanks the old fields while migrating.
	 *
	 * @param string $table Table to migrate
	 * @param string|string[] $primaryKey Primary key of the table.
	 * @param string $oldField Old comment field name
	 */
	protected function migrate( $table, $primaryKey, $oldField ) {
		$newField = $oldField . '_id';
		$primaryKey = (array)$primaryKey;
		$pkFilter = array_flip( $primaryKey );
		$this->output( "Beginning migration of $table.$oldField to $table.$newField\n" );
		wfWaitForSlaves();

		$dbw = $this->getDB( DB_MASTER );
		$next = '1=1';
		$countUpdated = 0;
		$countComments = 0;
		while ( true ) {
			// Fetch the rows needing update
			$res = $dbw->select(
				$table,
				array_merge( $primaryKey, [ $oldField ] ),
				[
					$newField => 0,
					$next,
				],
				__METHOD__,
				[
					'ORDER BY' => $primaryKey,
					'LIMIT' => $this->getBatchSize(),
				]
			);
			if ( !$res->numRows() ) {
				break;
			}

			// Collect the distinct comments from those rows
			$comments = [];
			foreach ( $res as $row ) {
				$comments[$row->$oldField] = 0;
			}
			$countComments += $this->loadCommentIDs( $dbw, $comments );

			// Update the existing rows
			foreach ( $res as $row ) {
				$dbw->update(
					$table,
					[
						$newField => $comments[$row->$oldField],
						$oldField => '',
					],
					array_intersect_key( (array)$row, $pkFilter ) + [
						$newField => 0
					],
					__METHOD__
				);
				$countUpdated += $dbw->affectedRows();
			}

			// Calculate the "next" condition
			$next = '';
			$prompt = [];
			for ( $i = count( $primaryKey ) - 1; $i >= 0; $i-- ) {
				$field = $primaryKey[$i];
				$prompt[] = $row->$field;
				$value = $dbw->addQuotes( $row->$field );
				if ( $next === '' ) {
					$next = "$field > $value";
				} else {
					$next = "$field > $value OR $field = $value AND ($next)";
				}
			}
			$prompt = implode( ' ', array_reverse( $prompt ) );
			$this->output( "... $prompt\n" );
			wfWaitForSlaves();
		}

		$this->output(
			"Completed migration, updated $countUpdated row(s) with $countComments new comment(s)\n"
		);
	}

	/**
	 * Migrate comments in a table to a temporary table.
	 *
	 * Assumes any row with the ID field non-zero have already been migrated.
	 * Assumes the new table is named "{$table}_comment_temp", and it has two
	 * columns, in order, being the primary key of the original table and the
	 * comment ID field.
	 * Blanks the old fields while migrating.
	 *
	 * @param string $table Table to migrate
	 * @param string $primaryKey Primary key of the table.
	 * @param string $oldField Old comment field name
	 * @param string $newPrimaryKey Primary key of the new table.
	 * @param string $newField New comment field name
	 */
	protected function migrateToTemp( $table, $primaryKey, $oldField, $newPrimaryKey, $newField ) {
		$newTable = $table . '_comment_temp';
		$this->output( "Beginning migration of $table.$oldField to $newTable.$newField\n" );
		wfWaitForSlaves();

		$dbw = $this->getDB( DB_MASTER );
		$next = [];
		$countUpdated = 0;
		$countComments = 0;
		while ( true ) {
			// Fetch the rows needing update
			$res = $dbw->select(
				[ $table, $newTable ],
				[ $primaryKey, $oldField ],
				[ $newPrimaryKey => null ] + $next,
				__METHOD__,
				[
					'ORDER BY' => $primaryKey,
					'LIMIT' => $this->getBatchSize(),
				],
				[ $newTable => [ 'LEFT JOIN', "{$primaryKey}={$newPrimaryKey}" ] ]
			);
			if ( !$res->numRows() ) {
				break;
			}

			// Collect the distinct comments from those rows
			$comments = [];
			foreach ( $res as $row ) {
				$comments[$row->$oldField] = 0;
			}
			$countComments += $this->loadCommentIDs( $dbw, $comments );

			// Update rows
			$inserts = [];
			$updates = [];
			foreach ( $res as $row ) {
				$inserts[] = [
					$newPrimaryKey => $row->$primaryKey,
					$newField => $comments[$row->$oldField]
				];
				$updates[] = $row->$primaryKey;
			}
			$this->beginTransaction( $dbw, __METHOD__ );
			$dbw->insert( $newTable, $inserts, __METHOD__ );
			$dbw->update( $table, [ $oldField => '' ], [ $primaryKey => $updates ], __METHOD__ );
			$countUpdated += $dbw->affectedRows();
			$this->commitTransaction( $dbw, __METHOD__ );

			// Calculate the "next" condition
			$next = [ $primaryKey . ' > ' . $dbw->addQuotes( $row->$primaryKey ) ];
			$this->output( "... {$row->$primaryKey}\n" );
		}

		$this->output(
			"Completed migration, updated $countUpdated row(s) with $countComments new comment(s)\n"
		);
	}
}

$maintClass = MigrateComments::class;
require_once RUN_MAINTENANCE_IF_MAIN;
