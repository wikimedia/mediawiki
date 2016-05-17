<?php
/**
 * Dump revision restrictions, one batch at a time.
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
 * @author Gabriel Wicke <gwicke@wikimedia.org>
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to dump revision restrictions, one batch at a time.
 *
 * @ingroup Maintenance
 */
class ExportRevisionRestrictions extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Dump revision restrictions, one batch at a time.' );
		$this->addOption( 'start', 'Start from this revision.' );
		$this->addOption( 'limit', 'Limit the batch size to this value.' );
	}

	public function execute() {
		// Delay for replication lag
		wfWaitForSlaves();

		$start = intval( $this->getOption( 'start', '0' ) );
		$limit = intval( $this->getOption( 'limit', 1000 ) );

		$dbr = $this->getDB( DB_SLAVE, 'vslow' );
		$sql = "SELECT page_title, page_id, rev_id, rev_deleted "
				. "FROM page JOIN revision on page_id = rev_page "
				. "WHERE rev_id > {$start} AND rev_deleted != 0 LIMIT {$limit}";
		$batchResult = $dbr->query( $sql );
		$rows = [];
		foreach ( $batchResult as $row ) {
			$rows[] = $row;
		}
		$this->output( json_encode( $rows ) );
	}
}

$maintClass = "ExportRevisionRestrictions";
require_once RUN_MAINTENANCE_IF_MAIN;
