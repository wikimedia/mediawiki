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
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to cleanup old and invalid querycache rows.
 *
 * @ingroup Maintenance
 */
class CleanupQueryCache extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( "Cleanup old and invalid querycache rows." );
		$this->setBatchSize( 1000 );
	}

	public function execute() {
		global $wgMiserMode;

		if ( !$wgMiserMode ) {
			$this->output( "\$wgMiserMode is disabled, so QueryPages aren't cached. Nothing to do.\n" );
			// TODO: Just truncate the table?
			return;
		}

		$dbw = $this->getDB( DB_MASTER );

		$queryCache = [
			'querycache' => 'qc_type',
			'querycachetwo' => 'qcc_type',
			'querycache_info' => 'qci_type'
		];

		$qcType = $dbw->makeList( array_filter( array_map(
			function ( $a ) {
				// $a[1] = Special page name
				// Only cached query pages should be in the querycache table
				if ( SpecialPageFactory::getPage( $a[1] )->isCached() ) {
					return $a[1];
				}
				return null;
			},
			QueryPage::getPages()
		) ) );

		foreach ( $queryCache as $table => $col ) {
			$deletedRows = 0;
			do {
				$query = "DELETE FROM " . $dbw->tableName( $table ) .
					" WHERE $col NOT IN ($qcType) LIMIT " . $this->mBatchSize;

				$dbw->query( $query, __METHOD__ );

				$affected = $dbw->affectedRows();
				$deletedRows += $affected;
				$this->output( "$deletedRows $table rows deleted\n" );

				wfWaitForSlaves();
			} while ( $affected === $this->mBatchSize );
		}

		$this->output( "Done!\n" );
	}

	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}
}

$maintClass = "CleanupQueryCache";
require_once RUN_MAINTENANCE_IF_MAIN;
