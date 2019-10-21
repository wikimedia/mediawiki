<?php
/**
 * Send purge requests for pages edited in date range to squid/varnish.
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

require_once __DIR__ . '/Maintenance.php';

use Wikimedia\Rdbms\IResultWrapper;

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
		$this->addDescription( 'Send purge requests for edits in date range to squid/varnish' );
		$this->addOption( 'starttime', 'Starting timestamp', true, true );
		$this->addOption( 'endtime', 'Ending timestamp', true, true );
		$this->addOption( 'htcp-dest', 'HTCP announcement destination (IP:port)', false, true );
		$this->addOption( 'sleep-per-batch', 'Milliseconds to sleep between batches', false, true );
		$this->addOption( 'dry-run', 'Do not send purge requests' );
		$this->addOption( 'verbose', 'Show more output', false, false, 'v' );
		$this->setBatchSize( 100 );
	}

	public function execute() {
		global $wgHTCPRouting;

		if ( $this->hasOption( 'htcp-dest' ) ) {
			$parts = explode( ':', $this->getOption( 'htcp-dest' ), 2 );
			if ( count( $parts ) < 2 ) {
				// Add default htcp port
				$parts[] = '4827';
			}

			// Route all HTCP messages to provided host:port
			$wgHTCPRouting = [
				'' => [ 'host' => $parts[0], 'port' => $parts[1] ],
			];
			if ( $this->hasOption( 'verbose' ) ) {
				$this->output( "HTCP broadcasts to {$parts[0]}:{$parts[1]}\n" );
			}
		}

		$dbr = $this->getDB( DB_REPLICA );
		$minTime = $dbr->timestamp( $this->getOption( 'starttime' ) );
		$maxTime = $dbr->timestamp( $this->getOption( 'endtime' ) );

		if ( $maxTime < $minTime ) {
			$this->error( "\nERROR: starttime after endtime\n" );
			$this->maybeHelp( true );
		}

		$stuckCount = 0; // loop breaker
		while ( true ) {
			// Adjust bach size if we are stuck in a second that had many changes
			$bSize = ( $stuckCount + 1 ) * $this->getBatchSize();

			$res = $dbr->select(
				[ 'page', 'revision' ],
				[
					'rev_timestamp',
					'page_namespace',
					'page_title',
				],
				[
					"rev_timestamp > " . $dbr->addQuotes( $minTime ),
					"rev_timestamp <= " . $dbr->addQuotes( $maxTime ),
					// Only get rows where the revision is the latest for the page.
					// Other revisions would be duplicate and we don't need to purge if
					// there has been an edit after the interesting time window.
					"page_latest = rev_id",
				],
				__METHOD__,
				[ 'ORDER BY' => 'rev_timestamp', 'LIMIT' => $bSize ],
				[
					'page' => [ 'JOIN', 'rev_page=page_id' ],
				]
			);

			if ( !$res->numRows() ) {
				// nothing more found so we are done
				break;
			}

			// Kludge to not get stuck in loops for batches with the same timestamp
			list( $rows, $lastTime ) = $this->pageableSortedRows( $res, 'rev_timestamp', $bSize );
			if ( !count( $rows ) ) {
				++$stuckCount;
				continue;
			}
			// Reset suck counter
			$stuckCount = 0;

			$this->output( "Processing changes from {$minTime} to {$lastTime}.\n" );

			// Advance past the last row next time
			$minTime = $lastTime;

			// Create list of URLs from page_namespace + page_title
			$urls = [];
			foreach ( $rows as $row ) {
				$title = Title::makeTitle( $row->page_namespace, $row->page_title );
				$urls[] = $title->getInternalURL();
			}

			if ( $this->hasOption( 'dry-run' ) || $this->hasOption( 'verbose' ) ) {
				$this->output( implode( "\n", $urls ) . "\n" );
				if ( $this->hasOption( 'dry-run' ) ) {
					continue;
				}
			}

			// Send batch of purge requests out to CDN servers
			$cdn = new CdnCacheUpdate( $urls, count( $urls ) );
			$cdn->doUpdate();

			if ( $this->hasOption( 'sleep-per-batch' ) ) {
				// sleep-per-batch is milliseconds, usleep wants micro seconds.
				usleep( 1000 * (int)$this->getOption( 'sleep-per-batch' ) );
			}
		}

		$this->output( "Done!\n" );
	}

	/**
	 * Remove all the rows in a result set with the highest value for column
	 * $column unless the number of rows is less $limit. This returns the new
	 * array of rows and the highest value of column $column for the rows left.
	 * The ordering of rows is maintained.
	 *
	 * This is useful for paging on mostly-unique values that may sometimes
	 * have large clumps of identical values. It should be safe to do the next
	 * query on items with a value higher than the highest of the rows returned here.
	 * If this returns an empty array for a non-empty query result, then all the rows
	 * had the same column value and the query should be repeated with a higher LIMIT.
	 *
	 * @todo move this elsewhere
	 *
	 * @param IResultWrapper $res Query result sorted by $column (ascending)
	 * @param string $column
	 * @param int $limit
	 * @return array (array of rows, string column value)
	 */
	protected function pageableSortedRows( IResultWrapper $res, $column, $limit ) {
		$rows = iterator_to_array( $res, false );

		// Nothing to do
		if ( !$rows ) {
			return [ [], null ];
		}

		$lastValue = end( $rows )->$column;
		if ( count( $rows ) < $limit ) {
			return [ $rows, $lastValue ];
		}

		for ( $i = count( $rows ) - 1; $i >= 0; --$i ) {
			if ( $rows[$i]->$column !== $lastValue ) {
				break;
			}

			unset( $rows[$i] );
		}

		// No more rows left
		if ( !$rows ) {
			return [ [], null ];
		}

		return [ $rows, end( $rows )->$column ];
	}
}

$maintClass = PurgeChangedPages::class;
require_once RUN_MAINTENANCE_IF_MAIN;
