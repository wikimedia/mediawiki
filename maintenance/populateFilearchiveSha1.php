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
		$dbw = $this->getDB( DB_MASTER );
		$table = 'filearchive';
		$conds = [ 'fa_sha1' => '', 'fa_storage_key IS NOT NULL' ];

		if ( !$dbw->fieldExists( $table, 'fa_sha1', __METHOD__ ) ) {
			$this->output( "fa_sha1 column does not exist\n\n", true );

			return false;
		}

		$this->output( "Populating fa_sha1 field from fa_storage_key\n" );
		$endId = $dbw->selectField( $table, 'MAX(fa_id)', '', __METHOD__ );

		$batchSize = $this->getBatchSize();
		$done = 0;

		do {
			$res = $dbw->select(
				$table,
				[ 'fa_id', 'fa_storage_key' ],
				$conds,
				__METHOD__,
				[ 'LIMIT' => $batchSize ]
			);

			$i = 0;
			foreach ( $res as $row ) {
				if ( $row->fa_storage_key == '' ) {
					// Revision was missing pre-deletion
					continue;
				}
				$sha1 = LocalRepo::getHashFromKey( $row->fa_storage_key );
				$dbw->update( $table,
					[ 'fa_sha1' => $sha1 ],
					[ 'fa_id' => $row->fa_id ],
					__METHOD__
				);
				$lastId = $row->fa_id;
				$i++;
			}

			$done += $i;
			if ( $i !== $batchSize ) {
				break;
			}

			// print status and let replica DBs catch up
			$this->output( sprintf(
				"id %d done (up to %d), %5.3f%%  \r", $lastId, $endId, $lastId / $endId * 100 ) );
			wfWaitForSlaves();
		} while ( true );

		$processingTime = microtime( true ) - $startTime;
		$this->output( sprintf( "\nDone %d files in %.1f seconds\n", $done, $processingTime ) );

		return true; // we only updated *some* files, don't log
	}
}

$maintClass = PopulateFilearchiveSha1::class;
require_once RUN_MAINTENANCE_IF_MAIN;
