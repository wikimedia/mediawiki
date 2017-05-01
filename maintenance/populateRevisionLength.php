<?php
/**
 * Populates the rev_len and ar_len fields when they are NULL.
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

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that populates the rev_len and ar_len fields when they are NULL.
 * This is the case for all revisions created before MW 1.10, as well as those affected
 * by T18748 (MW 1.10-1.13) and those affected by T135414 (MW 1.21-1.24).
 *
 * @ingroup Maintenance
 */
class PopulateRevisionLength extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Populates the rev_len and ar_len fields' );
		$this->setBatchSize( 200 );
	}

	protected function getUpdateKey() {
		return 'populate rev_len and ar_len';
	}

	public function doDBUpdates() {
		$dbw = $this->getDB( DB_MASTER );
		if ( !$dbw->tableExists( 'revision' ) ) {
			$this->error( "revision table does not exist", true );
		} elseif ( !$dbw->tableExists( 'archive' ) ) {
			$this->error( "archive table does not exist", true );
		} elseif ( !$dbw->fieldExists( 'revision', 'rev_len', __METHOD__ ) ) {
			$this->output( "rev_len column does not exist\n\n", true );

			return false;
		}

		$this->output( "Populating rev_len column\n" );
		$rev = $this->doLenUpdates( 'revision', 'rev_id', 'rev', Revision::selectFields() );

		$this->output( "Populating ar_len column\n" );
		$ar = $this->doLenUpdates( 'archive', 'ar_id', 'ar', Revision::selectArchiveFields() );

		$this->output( "rev_len and ar_len population complete "
			. "[$rev revision rows, $ar archive rows].\n" );

		return true;
	}

	/**
	 * @param string $table
	 * @param string $idCol
	 * @param string $prefix
	 * @param array $fields
	 * @return int
	 */
	protected function doLenUpdates( $table, $idCol, $prefix, $fields ) {
		$dbr = $this->getDB( DB_REPLICA );
		$dbw = $this->getDB( DB_MASTER );
		$start = $dbw->selectField( $table, "MIN($idCol)", false, __METHOD__ );
		$end = $dbw->selectField( $table, "MAX($idCol)", false, __METHOD__ );
		if ( !$start || !$end ) {
			$this->output( "...$table table seems to be empty.\n" );

			return 0;
		}

		# Do remaining chunks
		$blockStart = intval( $start );
		$blockEnd = intval( $start ) + $this->mBatchSize - 1;
		$count = 0;

		while ( $blockStart <= $end ) {
			$this->output( "...doing $idCol from $blockStart to $blockEnd\n" );
			$res = $dbr->select(
				$table,
				$fields,
				[
					"$idCol >= $blockStart",
					"$idCol <= $blockEnd",
					"{$prefix}_len IS NULL"
				],
				__METHOD__
			);

			if ( $res->numRows() > 0 ) {
				$this->beginTransaction( $dbw, __METHOD__ );
				# Go through and update rev_len from these rows.
				foreach ( $res as $row ) {
					if ( $this->upgradeRow( $row, $table, $idCol, $prefix ) ) {
						$count++;
					}
				}
				$this->commitTransaction( $dbw, __METHOD__ );
			}

			$blockStart += $this->mBatchSize;
			$blockEnd += $this->mBatchSize;
			wfWaitForSlaves();
		}

		return $count;
	}

	/**
	 * @param stdClass $row
	 * @param string $table
	 * @param string $idCol
	 * @param string $prefix
	 * @return bool
	 */
	protected function upgradeRow( $row, $table, $idCol, $prefix ) {
		$dbw = $this->getDB( DB_MASTER );

		$rev = ( $table === 'archive' )
			? Revision::newFromArchiveRow( $row )
			: new Revision( $row );

		$content = $rev->getContent();
		if ( !$content ) {
			# This should not happen, but sometimes does (bug 20757)
			$id = $row->$idCol;
			$this->output( "Content of $table $id unavailable!\n" );

			return false;
		}

		# Update the row...
		$dbw->update( $table,
			[ "{$prefix}_len" => $content->getSize() ],
			[ $idCol => $row->$idCol ],
			__METHOD__
		);

		return true;
	}
}

$maintClass = "PopulateRevisionLength";
require_once RUN_MAINTENANCE_IF_MAIN;
