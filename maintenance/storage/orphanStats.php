<?php
/**
 * Show some statistics on the blob_orphans table, created with trackBlobs.php.
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
 * @ingroup Maintenance ExternalStorage
 */

require_once __DIR__ . '/../Maintenance.php';

/**
 * Maintenance script that shows some statistics on the blob_orphans table,
 * created with trackBlobs.php.
 *
 * @ingroup Maintenance ExternalStorage
 */
class OrphanStats extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			"Show some statistics on the blob_orphans table, created with trackBlobs.php" );
	}

	protected function &getDB( $cluster, $groups = [], $wiki = false ) {
		$lb = wfGetLBFactory()->getExternalLB( $cluster );

		return $lb->getConnection( DB_REPLICA );
	}

	public function execute() {
		$dbr = $this->getDB( DB_REPLICA );
		if ( !$dbr->tableExists( 'blob_orphans' ) ) {
			$this->error( "blob_orphans doesn't seem to exist, need to run trackBlobs.php first", true );
		}
		$res = $dbr->select( 'blob_orphans', '*', false, __METHOD__ );

		$num = 0;
		$totalSize = 0;
		$hashes = [];
		$maxSize = 0;

		foreach ( $res as $boRow ) {
			$extDB = $this->getDB( $boRow->bo_cluster );
			$blobRow = $extDB->selectRow(
				'blobs',
				'*',
				[ 'blob_id' => $boRow->bo_blob_id ],
				__METHOD__
			);

			$num++;
			$size = strlen( $blobRow->blob_text );
			$totalSize += $size;
			$hashes[sha1( $blobRow->blob_text )] = true;
			$maxSize = max( $size, $maxSize );
		}
		unset( $res );

		$this->output( "Number of orphans: $num\n" );
		if ( $num > 0 ) {
			$this->output( "Average size: " . round( $totalSize / $num, 0 ) . " bytes\n" .
				"Max size: $maxSize\n" .
				"Number of unique texts: " . count( $hashes ) . "\n" );
		}
	}
}

$maintClass = "OrphanStats";
require_once RUN_MAINTENANCE_IF_MAIN;
