<?php
/**
 * Send purge requests for pages edited in date range to squid/varnish.
 *
 * @section LICENSE
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
 * Maintenance script that sends purge requests for pages edited in a date
 * range to squid/varnish.
 *
 * Can be used to recover from an HTCP message partition or other major cache
 * layer interruption.
 *
 * @ingroup Maintenance
 */
class PurgeChangedPages extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Send purge requests for edits in date range to squid/varnish';
		$this->addOption( 'starttime', 'Starting timestamp', true, true );
		$this->addOption( 'endtime', 'Ending timestamp', true, true );
		$this->addOption( 'htcp-dest', 'HTCP announcement destination (IP:port)', false, true );
		$this->addOption( 'dry-run', 'Do not send purge requests' );
		$this->addOption( 'verbose', 'Show more output', false, false, 'v' );
		$this->setBatchSize( 100 );
	}

	public function execute() {
		global $wgHTCPRouting;

		if ( $this->hasOption( 'htcp-dest' ) ) {
			$parts = explode( ':', $this->getOption( 'htcp-dest' ) );
			if ( count( $parts ) < 2 ) {
				// Add default htcp port
				$parts[] = '4827';
			}

			// Route all HTCP messages to provided host:port
			$wgHTCPRouting = array(
				'' => array( 'host' => $parts[0], 'port' => $parts[1] ),
			);
			if ( $this->hasOption( 'verbose' ) ) {
				$this->output( "HTCP broadcasts to {$parts[0]}:{$parts[1]}\n" );
			}
		}

		$start = $this->getOption( 'starttime' );
		$end = $this->getOption( 'endtime' );

		$dbr = $this->getDB( DB_SLAVE );

		$encMinTime = $dbr->addQuotes( $dbr->timestamp( $start ) );
		$encMaxTime = $dbr->addQuotes( $dbr->timestamp( $end ) );
		$lastSeenId = 0;

		while ( true ) {
			// Find next N pages that were changed in the timerange.
			// We do this using a complicated UNION to improve performance on very
			// large revision tables (eg enwiki).
			$tables = array( 'page', 'revision' );
			$fields = array(
				'rev_id',
				'rev_timestamp',
				'page_namespace',
				'page_title',
			);
			$joinConds = array( 'page' => array( 'INNER JOIN', 'rev_page=page_id' ) );
			$orderBy = 'rev_timestamp, rev_id';

			// Collect changes in the initial second that we haven't seen yet
			$sqlInFirstSecond = $dbr->selectSQLText(
				$tables,
				$fields,
				array(
					"rev_timestamp = {$encMinTime}",
					"rev_id > {$lastSeenId}",
				),
				__METHOD__,
				array(
					'ORDER BY' => $orderBy,
					'LIMIT' => $this->mBatchSize,
				),
				$joinConds
			);

			// Collect changes after the first second and on or before the last second
			$sqlUntilMax = $dbr->selectSQLText(
				$tables,
				$fields,
				array(
					"rev_timestamp > {$encMinTime}",
					"rev_timestamp <= {$encMaxTime}",
				),
				__METHOD__,
				array(
					'ORDER BY' => $orderBy,
					'LIMIT' => $this->mBatchSize,
				),
				$joinConds
			);

			// Merge the two collections and sort the result set
			$sql = $dbr->unionQueries(
				array( $sqlInFirstSecond, $sqlUntilMax ), false );
			$sql = $dbr->limitResult(
				"{$sql} ORDER BY {$orderBy}", $this->mBatchSize, false );

			// Execute the query
			$res = $dbr->query( $sql, __METHOD__ );
			if ( !$res->numRows() ) {
				// Nothing more found so we are done
				break;
			}

			// Create list of URLs from page_namespace + page_title
			$lastRow = null;
			$urls = array();
			foreach ( $res as $row ) {
				$lastRow = $row;
				$title = Title::makeTitle( $row->page_namespace, $row->page_title );
				$urls[] = $title->getInternalURL();
			}

			// Track max id and timestamp to limit the next query
			$lastSeenId = $lastRow->rev_id;
			$encMinTime = $dbr->addQuotes(
				$dbr->timestamp( $lastRow->rev_timestamp ) );

			if ( $this->hasOption( 'dry-run' ) || $this->hasOption( 'verbose' ) ) {
				$this->output( implode( "\n", $urls ) . "\n" );
			}
			if ( $this->hasOption( 'dry-run' ) ) {
				continue;
			}

			// Send batch of purge requests out to squids
			$squid = new SquidUpdate( $urls );
			$squid->doUpdate();
		}

		$this->output( "Done!\n" );
	}

}

$maintClass = "PurgeChangedPages";
require_once RUN_MAINTENANCE_IF_MAIN;
