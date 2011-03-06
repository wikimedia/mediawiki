<?php
/**
 * Purge squids pages for a given namespace
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
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

# TODO implements a page_touched condition

class PurgeNamespace extends Maintenance {
	public function __construct() {
		$this->mDescription = "Purge squids pages for a given namespace";
		$this->addOption( "namespace", "Namespace number", true, true );
		$this->setBatchSize( 100 );
		parent::__construct();
	}

	public function execute() {
		$dbr = wfGetDB( DB_SLAVE );
		$ns = $dbr->addQuotes( $this->getOption( 'namespace') );

		$result = $dbr->select(
			array( 'page' ),
			array( 'page_namespace', 'page_title' ),
			array( "page_namespace = $ns" ),
			__METHOD__,
			array( 'ORDER BY' => 'page_id' )
		);

		$start   = 0;
		$end = $dbr->numRows( $result );
		$this->output( "Will purge $end pages from namespace $ns\n" );

		# Do remaining chunk
		$end += $this->mBatchSize - 1;
		$blockStart = $start;
		$blockEnd = $start + $this->mBatchSize - 1;

		while( $blockEnd <= $end ) {
			# Select pages we will purge:
			$result = $dbr->select(
				array( 'page' ),
				array( 'page_namespace', 'page_title' ),
				array( "page_namespace = $ns" ),
				__METHOD__,
				array( # conditions
					'ORDER BY' => 'page_id',
					'LIMIT'    => $this->mBatchSize,
					'OFFSET'   => $blockStart,
			 	 )
			);

			# Initialize/reset URLs to be purged
			$urls = array();
			foreach( $result as $row ) {
				$title = Title::makeTitle( $row->page_namespace, $row->page_title );
				$url = $title->getFullUrl();
				$urls[] = $url;
			}

			$this->sendPurgeRequest( $urls );

			$blockStart += $this->mBatchSize;
			$blockEnd   += $this->mBatchSize;
		}

		$this->output( "Done!\n" );
	}

	private function sendPurgeRequest( $urls ) {
		$this->output( "Purging " . count( $urls ). " urls\n" );
		$u = new SquidUpdate( $urls );
		$u->doUpdate();
	}
}

$maintClass = "PurgeNamespace";
require_once( RUN_MAINTENANCE_IF_MAIN );

