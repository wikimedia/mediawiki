<?php
/**
 * Dump revision restrictions, one JSON object per batch and line.
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
 * @author Petr Pchelko <ppchelko@wikimedia.org>
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to dump revision restrictions, one JSON object per batch
 * and line.
 *
 * @ingroup Maintenance
 */
class ExportRevisionRestrictions extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Dump page deletions, one batch at a time' );
        $this->addOption( 'start_ns', 'Start from this namespace number' );
        $this->addOption( 'start_title', 'Start from this page title in alphabetical order.' );
		$this->addOption( 'limit', 'Limit the batch size to this value.' );
	}

	public function execute() {
		// Delay for replication lag. Not really.
		// wfWaitForSlaves();

        $start_ns = $this->getOption( 'start_ns' );
		$start_title = $this->getOption( 'start_title' );
		$limit = intval( $this->getOption( 'limit', 100 ) );

		do {
			$dbr = $this->getDB( DB_SLAVE, 'vslow' );
			$sql = "SELECT ar_namespace as namespace, ar_title as title, MAX(ar_rev_id) as rev_id "
				. "FROM archive ";

			if ( !is_null( $start_ns ) && !is_null( $start_title ) ) {
				$sql = $sql . "WHERE ar_namespace > \"{$start_ns}\" AND ar_title > \"{$start_title}\" ";
			}
			$sql = $sql . "GROUP BY ar_namespace, ar_title ORDER BY ar_namespace, ar_title ASC LIMIT {$limit}";

			$batchResult = $dbr->query( $sql );
			$rows = [];
			foreach ( $batchResult as $row ) {
				$rows[] = $row;
                $start_ns = $row->namespace;
				$start_title = $row->title;
			}
			// Emit one JSON object per line.
			$this->output( json_encode( $rows ) . "\n" );
		} while ( count( $rows ) );
	}
}

$maintClass = "ExportRevisionRestrictions";
require_once RUN_MAINTENANCE_IF_MAIN;
