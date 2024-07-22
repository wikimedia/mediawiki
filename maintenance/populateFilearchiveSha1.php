<?php
/**
 * Optional upgrade script to populate the fa_sha1 field
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
 * Maintenance script to populate the fa_sha1 field.
 *
 * @ingroup Maintenance
 * @since 1.21
 */
class PopulateFilearchiveSha1 extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Populate the fa_sha1 field from fa_storage_key' );
	}

	protected function getUpdateKey() {
		return 'populate fa_sha1';
	}

	protected function updateSkippedMessage() {
		return 'fa_sha1 column of filearchive table already populated.';
	}

	public function doDBUpdates() {
		$startTime = microtime( true );
		$dbw = $this->getPrimaryDB();
		$table = 'filearchive';

		if ( !$this->getDB( DB_PRIMARY )->fieldExists( $table, 'fa_sha1', __METHOD__ ) ) {
			$this->output( "fa_sha1 column does not exist\n\n", true );

			return false;
		}

		$this->output( "Populating fa_sha1 field from fa_storage_key\n" );
		$endId = $dbw->newSelectQueryBuilder()
			->select( 'MAX(fa_id)' )
			->from( $table )
			->caller( __METHOD__ )->fetchField();

		$batchSize = $this->getBatchSize();
		$done = 0;

		do {
			$res = $dbw->newSelectQueryBuilder()
				->select( [ 'fa_id', 'fa_storage_key' ] )
				->from( $table )
				->where( [ 'fa_sha1' => '', $dbw->expr( 'fa_storage_key', '!=', null ) ] )
				->limit( $batchSize )
				->caller( __METHOD__ )->fetchResultSet();

			$i = 0;
			foreach ( $res as $row ) {
				if ( $row->fa_storage_key == '' ) {
					// Revision was missing pre-deletion
					continue;
				}
				$sha1 = LocalRepo::getHashFromKey( $row->fa_storage_key );
				$dbw->newUpdateQueryBuilder()
					->update( $table )
					->set( [ 'fa_sha1' => $sha1 ] )
					->where( [ 'fa_id' => $row->fa_id ] )
					->caller( __METHOD__ )
					->execute();
				$lastId = $row->fa_id;
				$i++;
			}

			$done += $i;
			if ( $i !== $batchSize ) {
				break;
			}

			// print status and let replica DBs catch up
			$this->output( sprintf(
				// @phan-suppress-next-line PhanPossiblyUndeclaredVariable $lastId is set for non-empty $res
				"id %d done (up to %d), %5.3f%%  \r", $lastId, $endId, $lastId / $endId * 100 ) );
			$this->waitForReplication();
		} while ( true );

		$processingTime = microtime( true ) - $startTime;
		$this->output( sprintf( "\nDone %d files in %.1f seconds\n", $done, $processingTime ) );

		// we only updated *some* files, don't log
		return true;
	}
}

$maintClass = PopulateFilearchiveSha1::class;
require_once RUN_MAINTENANCE_IF_MAIN;
