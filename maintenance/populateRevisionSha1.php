<?php
/**
 * Fills the rev_sha1 and ar_sha1 columns of revision
 * and archive tables for revisions created before MW 1.19.
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
 * Maintenance script that fills the rev_sha1 and ar_sha1 columns of revision
 * and archive tables for revisions created before MW 1.19.
 *
 * @ingroup Maintenance
 */
class PopulateRevisionSha1 extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Populates the rev_sha1 and ar_sha1 fields' );
		$this->setBatchSize( 200 );
	}

	protected function getUpdateKey() {
		return 'populate rev_sha1';
	}

	protected function doDBUpdates() {
		$db = $this->getDB( DB_PRIMARY );

		if ( !$db->tableExists( 'revision', __METHOD__ ) ) {
			$this->fatalError( "revision table does not exist" );
		} elseif ( !$db->tableExists( 'archive', __METHOD__ ) ) {
			$this->fatalError( "archive table does not exist" );
		} elseif ( !$db->fieldExists( 'revision', 'rev_sha1', __METHOD__ ) ) {
			$this->output( "rev_sha1 column does not exist\n\n", true );
			return false;
		}

		$revStore = $this->getServiceContainer()->getRevisionStore();

		$this->output( "Populating rev_sha1 column\n" );
		$rc = $this->doSha1Updates( $revStore, 'revision', 'rev_id',
			$revStore->newSelectQueryBuilder( $this->getPrimaryDB() )->joinComment(),
			'rev'
		);

		$this->output( "Populating ar_sha1 column\n" );
		$ac = $this->doSha1Updates( $revStore, 'archive', 'ar_rev_id',
			$revStore->newArchiveSelectQueryBuilder( $this->getPrimaryDB() )->joinComment(),
			'ar'
		);

		$this->output( "rev_sha1 and ar_sha1 population complete "
			. "[$rc revision rows, $ac archive rows].\n" );

		return true;
	}

	/**
	 * @param MediaWiki\Revision\RevisionStore $revStore
	 * @param string $table
	 * @param string $idCol
	 * @param \Wikimedia\Rdbms\SelectQueryBuilder $queryBuilder should use a primary db
	 * @param string $prefix
	 * @return int Rows changed
	 */
	protected function doSha1Updates( $revStore, $table, $idCol, $queryBuilder, $prefix ) {
		$db = $this->getPrimaryDB();
		$batchSize = $this->getBatchSize();
		$start = $db->newSelectQueryBuilder()
			->select( "MIN($idCol)" )
			->from( $table )
			->caller( __METHOD__ )->fetchField();
		$end = $db->newSelectQueryBuilder()
			->select( "MAX($idCol)" )
			->from( $table )
			->caller( __METHOD__ )->fetchField();
		if ( !$start || !$end ) {
			$this->output( "...$table table seems to be empty.\n" );

			return 0;
		}

		$count = 0;
		# Do remaining chunk
		$end += $batchSize - 1;
		$blockStart = $start;
		$blockEnd = $start + $batchSize - 1;
		while ( $blockEnd <= $end ) {
			$this->output( "...doing $idCol from $blockStart to $blockEnd\n" );

			$cond = "$idCol BETWEEN " . (int)$blockStart . " AND " . (int)$blockEnd .
				" AND $idCol IS NOT NULL AND {$prefix}_sha1 = ''";

			$res = $queryBuilder->where( $cond )
				->caller( __METHOD__ )->fetchResultSet();

			$this->beginTransaction( $db, __METHOD__ );
			foreach ( $res as $row ) {
				if ( $this->upgradeRow( $revStore, $row, $table, $idCol, $prefix ) ) {
					$count++;
				}
			}
			$this->commitTransaction( $db, __METHOD__ );

			$blockStart += $batchSize;
			$blockEnd += $batchSize;
		}

		return $count;
	}

	/**
	 * @param MediaWiki\Revision\RevisionStore $revStore
	 * @param stdClass $row
	 * @param string $table
	 * @param string $idCol
	 * @param string $prefix
	 * @return bool
	 */
	protected function upgradeRow( $revStore, $row, $table, $idCol, $prefix ) {
		$db = $this->getPrimaryDB();

		// Create a revision and use it to get the sha1 from the content table, if possible.
		try {
			$rev = ( $table === 'archive' )
				? $revStore->newRevisionFromArchiveRow( $row )
				: $revStore->newRevisionFromRow( $row );
			$sha1 = $rev->getSha1();
		} catch ( Exception $e ) {
			$this->output( "Data of revision with {$idCol}={$row->$idCol} unavailable!\n" );
			return false; // T24624? T22757?
		}

		$db->newUpdateQueryBuilder()
			->update( $table )
			->set( [ "{$prefix}_sha1" => $sha1 ] )
			->where( [ $idCol => $row->$idCol ] )
			->caller( __METHOD__ )->execute();

		return true;
	}
}

$maintClass = PopulateRevisionSha1::class;
require_once RUN_MAINTENANCE_IF_MAIN;
