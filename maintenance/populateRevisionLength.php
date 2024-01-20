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
		$dbw = $this->getDB( DB_PRIMARY );
		if ( !$dbw->tableExists( 'revision', __METHOD__ ) ) {
			$this->fatalError( "revision table does not exist" );
		} elseif ( !$dbw->tableExists( 'archive', __METHOD__ ) ) {
			$this->fatalError( "archive table does not exist" );
		} elseif ( !$dbw->fieldExists( 'revision', 'rev_len', __METHOD__ ) ) {
			$this->output( "rev_len column does not exist\n\n", true );

			return false;
		}

		$revisionStore = $this->getServiceContainer()->getRevisionStore();

		$this->output( "Populating rev_len column\n" );
		$rev = $this->doLenUpdates(
			'revision',
			'rev_id',
			'rev',
			$revisionStore->newSelectQueryBuilder( $this->getReplicaDB() )->joinComment()
		);

		$this->output( "Populating ar_len column\n" );
		$ar = $this->doLenUpdates(
			'archive',
			'ar_id',
			'ar',
			$revisionStore->newArchiveSelectQueryBuilder( $this->getReplicaDB() )->joinComment()
		);

		$this->output( "rev_len and ar_len population complete "
			. "[$rev revision rows, $ar archive rows].\n" );

		return true;
	}

	/**
	 * @param string $table
	 * @param string $idCol
	 * @param string $prefix
	 * @param \Wikimedia\Rdbms\SelectQueryBuilder $queryBuilder should use a replica db
	 * @return int
	 */
	protected function doLenUpdates( $table, $idCol, $prefix, $queryBuilder ) {
		$dbr = $this->getReplicaDB();
		$dbw = $this->getPrimaryDB();
		$batchSize = $this->getBatchSize();
		$start = $dbw->newSelectQueryBuilder()
			->select( "MIN($idCol)" )
			->from( $table )
			->caller( __METHOD__ )->fetchField();
		$end = $dbw->newSelectQueryBuilder()
			->select( "MAX($idCol)" )
			->from( $table )
			->caller( __METHOD__ )->fetchField();
		if ( !$start || !$end ) {
			$this->output( "...$table table seems to be empty.\n" );

			return 0;
		}

		# Do remaining chunks
		$blockStart = intval( $start );
		$blockEnd = intval( $start ) + $batchSize - 1;
		$count = 0;

		while ( $blockStart <= $end ) {
			$this->output( "...doing $idCol from $blockStart to $blockEnd\n" );

			$res = $queryBuilder
				->where( [
					"$idCol >= $blockStart",
					"$idCol <= $blockEnd",
					$dbr->expr( "{$prefix}_len", '=', null )
						->orExpr(
							$dbr->expr( "{$prefix}_len", '=', 0 )
								// sha1( "" )
								->and( "{$prefix}_sha1", '!=', 'phoiac9h4m842xq45sp7s6u21eteeq1' )
						),
				] )
				->caller( __METHOD__ )->fetchResultSet();

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

			$blockStart += $batchSize;
			$blockEnd += $batchSize;
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
		$dbw = $this->getPrimaryDB();

		$revFactory = $this->getServiceContainer()->getRevisionFactory();
		if ( $table === 'archive' ) {
			$revRecord = $revFactory->newRevisionFromArchiveRow( $row );
		} else {
			$revRecord = $revFactory->newRevisionFromRow( $row );
		}

		if ( !$revRecord ) {
			# This should not happen, but sometimes does (T22757)
			$id = $row->$idCol;
			$this->output( "RevisionRecord of $table $id unavailable!\n" );

			return false;
		}

		# Update the row...
		$dbw->newUpdateQueryBuilder()
			->update( $table )
			->set( [ "{$prefix}_len" => $revRecord->getSize() ] )
			->where( [ $idCol => $row->$idCol ] )
			->caller( __METHOD__ )->execute();

		return true;
	}
}

$maintClass = PopulateRevisionLength::class;
require_once RUN_MAINTENANCE_IF_MAIN;
