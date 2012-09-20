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

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

/**
 * Maintenance script to populate the fa_sha1 field.
 *
 * @ingroup Maintenance
 * @since 1.20
 */
class PopulateFilearchiveSha1 extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Populate the fa_sha1 field from fa_storage_key";
	}

	protected function getUpdateKey() {
		return 'populate fa_sha1';
	}

	protected function updateSkippedMessage() {
		return 'fa_sha1 column of filearchive table already populated.';
	}

	public function doDBUpdates() {
		$t = -microtime( true );
		$dbw = wfGetDB( DB_MASTER );
		$conds = array( 'fa_sha1' => '', 'fa_storage_key IS NOT NULL' );
		$this->output( "Populating fa_sha1 field from fa_storage_key\n" );
		$res = $dbw->select( 'filearchive', array( 'fa_id', 'fa_storage_key' ), $conds, __METHOD__ );

		$numRows = $res->numRows();
		$i = 0;
		foreach ( $res as $row ) {
			if ( $i % $this->mBatchSize == 0 ) {
				$this->output( sprintf(
					"Done %d of %d, %5.3f%%  \r", $i, $numRows, $i / $numRows * 100 ) );
				wfWaitForSlaves();
			}

			$sha1 = LocalRepo::getHashFromKey( $row->fa_storage_key );
			$dbw->update( 'filearchive',
				array( 'fa_sha1' => $sha1 ),
				array( 'fa_id' => $row->fa_id ),
				__METHOD__
			);

			$i++;
		}
		$t += microtime( true );
		$this->output( sprintf( "\nDone %d files in %.1f seconds\n", $numRows, $t ) );

		return true; // we only updated *some* files, don't log
	}
}

$maintClass = "PopulateFilearchiveSha1";
require_once( RUN_MAINTENANCE_IF_MAIN );
