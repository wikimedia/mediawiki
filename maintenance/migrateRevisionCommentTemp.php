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

use MediaWiki\Maintenance\LoggedUpdateMaintenance;

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

	/** @inheritDoc */
	protected function getUpdateKey() {
		return __CLASS__;
	}

	/** @inheritDoc */
	protected function doDBUpdates() {
		$batchSize = $this->getBatchSize();

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
		$conds = [];
		$updated = 0;
		$sleep = (int)$this->getOption( 'sleep', 0 );
		while ( true ) {
			$res = $dbw->newSelectQueryBuilder()
				->select( [ 'rev_id', 'revcomment_comment_id' ] )
				->from( 'revision' )
				->join( 'revision_comment_temp', null, 'rev_id=revcomment_rev' )
				->where( [ 'rev_comment_id' => 0 ] )
				->andWhere( $conds )
				->limit( $batchSize )
				->orderBy( 'rev_id' )
				->caller( __METHOD__ )
				->fetchResultSet();

			$numRows = $res->numRows();

			$last = null;
			foreach ( $res as $row ) {
				$last = $row->rev_id;
				$dbw->newUpdateQueryBuilder()
					->update( 'revision' )
					->set( [ 'rev_comment_id' => $row->revcomment_comment_id ] )
					->where( [ 'rev_id' => $row->rev_id ] )
					->caller( __METHOD__ )->execute();
				$updated += $dbw->affectedRows();
			}

			if ( $numRows < $batchSize ) {
				// We must have reached the end
				break;
			}

			// @phan-suppress-next-line PhanTypeSuspiciousStringExpression last is not-null when used
			$this->output( "... rev_id=$last, updated $updated\n" );
			$conds = [ $dbw->expr( 'rev_id', '>', $last ) ];

			// Sleep between batches for replication to catch up
			$this->waitForReplication();
			if ( $sleep > 0 ) {
				sleep( $sleep );
			}
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
