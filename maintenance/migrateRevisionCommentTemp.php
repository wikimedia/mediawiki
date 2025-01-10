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

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that merges the revision_comment_temp table into the
 * revision table.
 *
 * @ingroup Maintenance
 * @since 1.40
 */
class MigrateRevisionCommentTemp extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Copy the data from the revision_comment_temp into the revision table'
		);
		$this->addOption(
			'sleep',
			'Sleep time (in seconds) between every batch. Default: 0',
			false,
			true
		);
	}

	protected function getUpdateKey() {
		return __CLASS__;
	}

	protected function doDBUpdates() {
		$dbw = $this->getDB( DB_PRIMARY );
		if ( !$dbw->fieldExists( 'revision', 'rev_comment_id', __METHOD__ ) ) {
			$this->output( "Run update.php to create rev_comment_id.\n" );
			return false;
		}
		if ( !$dbw->tableExists( 'revision_comment_temp', __METHOD__ ) ) {
			$this->output( "revision_comment_temp does not exist, so nothing to do.\n" );
			return true;
		}

		$this->output( "Merging the revision_comment_temp table into the revision table...\n" );
		$updated = 0;
		$highestRevId = (int)$dbw->newSelectQueryBuilder()
			->select( 'rev_id' )
			->from( 'revision' )
			->limit( 1 )
			->caller( __METHOD__ )
			->orderBy( 'rev_id', 'DESC' )
			->fetchField();
		$this->output( "Max rev_id $highestRevId.\n" );
		// Default batchSize from "$this->getBatchSize()" is 200, use 1000 to speed migration up
		// There is "$this->waitForReplication()" after each batch anyway
		$batchSize = 1000;
		$lowId = -1;
		$highId = $batchSize;
		while ( true ) {
			// `coalesce` covers case when some row is missing in revision_comment_temp.
			// Original script used `join` which skipped revision row when `revision_comment_temp` was null.
			//
			// Not sure whether we should try to fix the data first
			// RevisionSelectQueryBuilder::joinComment suggest that all revisions should have rev_comment_id set
			$query = "UPDATE revision
				SET rev_comment_id = COALESCE((SELECT revcomment_comment_id FROM revision_comment_temp WHERE rev_id=revcomment_rev), rev_comment_id)
				WHERE rev_id > $lowId AND rev_id <= $highId";
			$dbw->query( $query, __METHOD__ );
			$affected = $dbw->affectedRows();
			$updated += $affected;
			$this->output( "Updated $affected revision rows from $lowId to $highId\n" );
			$this->waitForReplication();

			if ( $highId > $highestRevId ) {
				// We reached the end
				break;
			}
			$lowId = $highId;
			$highId = $lowId + $batchSize;
		}
		$this->output(
			"Completed merge of revision_comment_temp into the revision table, "
			. "$updated rows updated.\n"
		);
		return true;
	}
}

// @codeCoverageIgnoreStart
$maintClass = MigrateRevisionCommentTemp::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
