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
class PurgeEditedFiles extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Send purge requests for edits in date range to squid/varnish';
		$this->addOption( 'starttime', 'Starting timestamp', true, true );
		$this->addOption( 'endtime', 'Ending timestamp', true, true );
		$this->addOption( 'htcp-dest', 'HTCP announcement destination (host:port)', false, true );
		$this->addOption( 'dryrun', 'Do not send purge requests' );
		$this->addOption( 'verbose', 'Show more output', false, false, 'v' );
		$this->setBatchSize( 100 );
	}

	public function execute() {
		global $wgHTCPRouting;

		if ( $this->hasOption( 'htcp-dest' ) ) {
			$parts = explode( ':', $this->getOption( 'htcp-dest' ) );
			if ( count( $parts ) < 2 ) {
				// add default htcp port
				$parts[] = '4827';
			}

			// route all HTCP messages to provided host:port
			$wgHTCPRouting = array(
				'' => array( 'host' => $parts[0], 'port' => $parts[1] ),
			);
			if ( $this->hasOption( 'verbose' ) ) {
				$this->output( "HTCP broadcasts to {$parts[0]}:{$parts[1]}\n" );
			}
		}

		$repo = RepoGroup::singleton()->getLocalRepo();
		$dbr = $repo->getSlaveDB();

		$start = $this->getOption( 'starttime' );
		$end = $this->getOption( 'endtime' );

		$minUpdate = $dbr->addQuotes( $dbr->timestamp( $start ) );
		$maxUpdate = $dbr->addQuotes( $dbr->timestamp( $end ) );
		$minId = -1;

		while ( true ) {
			// find next N pages that were changed in the timerange
			$res = $dbr->select(
				array( 'page', 'revision' ),
				array( 'page_id', 'page_namespace', 'page_title' ),
				'page_id = rev_page'
				. ' AND rev_id = page_latest'
				. " AND rev_timestamp >= {$minUpdate} "
				. " AND rev_timestamp <= {$maxUpdate} "
				. " AND page_id > {$minId} ",
				__METHOD__,
				array(
					'ORDER BY' => 'page_id',
					'LIMIT' => $this->mBatchSize,
				)
			);

			if ( !$res->numRows() ) {
				// nothing more found so we are done
				break;
			}

			// create list of URLs from page_namespace + page_title
			$urls = array();
			foreach ( $res as $row ) {
				$title = Title::makeTitle( $row->page_namespace, $row->page_title );
				$url = $title->getInternalURL();
				$urls[] = $url;
				// keep track of page_id for next query
				$minId = $row->page_id;
			}

			// send batch of purge requests out to squids
			$this->sendPurgeRequest( $urls );
		}

		$this->output( "Done!\n" );
	}

	/**
	 * Helper to purge an array of $urls
	 * @param $urls array List of URLS to purge from squids
	 */
	private function sendPurgeRequest( $urls ) {
		if ( $this->hasOption( 'dryrun' )
			|| $this->hasOption( 'verbose' ) ) {
			$this->output( implode( "\n", $urls ) . "\n" );
		}

		if ( $this->hasOption( 'dryrun' ) ) {
			return;
		}

		$u = new SquidUpdate( $urls );
		$u->doUpdate();
	}

}

$maintClass = "PurgeEditedFiles";
require_once RUN_MAINTENANCE_IF_MAIN;
