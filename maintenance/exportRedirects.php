<?php
/**
 * Dump redirects, one JSON object per batch and line.
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
class ExportRedirects extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Dump page deletions, one batch at a time' );
        $this->addOption( 'start_page_id', 'Start from this page_id' );
		$this->addOption( 'limit', 'Limit the batch size to this value.' );
	}

	public function execute() {
		// Delay for replication lag. Not really.
		// wfWaitForSlaves();

        $start_page_id = $this->getOption( 'start_page_id' );
		$limit = intval( $this->getOption( 'limit', 100 ) );

		do {
			$dbr = $this->getDB( DB_SLAVE, 'vslow' );
			$sql = "SELECT page.page_id, page.page_namespace, page.page_title, page.page_latest, redirect.rd_namespace, redirect.rd_title " .
                "FROM page RIGHT JOIN redirect ON page.page_id = redirect.rd_from ";

			if ( !is_null( $start_page_id ) ) {
                $escaped_page_id = $dbr->addQuotes( $start_page_id );
				$sql = $sql . "WHERE redirect.rd_from >= {$escaped_page_id} ";
			}
			$sql = $sql . "ORDER BY rd_from LIMIT {$limit}";

			$batchResult = $dbr->query( $sql );
			$rows = [];
			foreach ( $batchResult as $row ) {
				$rows[] = $row;
                $start_page_id = $row->page_id;
			}
			// Emit one JSON object per line.
			$this->output( json_encode( $rows ) . "\n" );
		} while ( count( $rows ) );
	}
}

$maintClass = "ExportRedirects";
require_once RUN_MAINTENANCE_IF_MAIN;
