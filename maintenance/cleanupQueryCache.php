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
 * Maintenance script to cleanup invalid querycache rows.
 *
 * @ingroup Maintenance
 */
class CleanupQueryCache extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( "Cleanup invalid querycache rows." );
	}

	public function execute() {
		$dbw = $this->getDB( DB_MASTER );

		$deletedRows = 0;
		do {
			$query = "DELETE FROM " . $dbw->tableName( 'querycache' ) .
				" WHERE qc_type = '' LIMIT 1000";

			$dbw->query( $query, __METHOD__ );

			$affected = $dbw->affectedRows();
			$deletedRows += $affected;
			$this->output( "$deletedRows rows deleted\n" );
			wfWaitForSlaves();
		} while ( $affected );

		$this->output( "Done!\n" );
	}

	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}
}

$maintClass = "CleanupQueryCache";
require_once RUN_MAINTENANCE_IF_MAIN;
