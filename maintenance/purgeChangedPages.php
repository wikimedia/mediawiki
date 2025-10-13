<?php
/**
 * Send purge requests for pages edited in date range to squid/varnish.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Title\Title;
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

		$dbr = $this->getReplicaDB();
		$minTime = $dbr->timestamp( $this->getOption( 'starttime' ) );
		$maxTime = $dbr->timestamp( $this->getOption( 'endtime' ) );

		if ( $maxTime < $minTime ) {
			$this->error( "\nERROR: starttime after endtime\n" );
			$this->maybeHelp( true );
		}

		$stuckCount = 0;
		while ( true ) {
			// Adjust bach size if we are stuck in a second that had many changes
			$bSize = ( $stuckCount + 1 ) * $this->getBatchSize();

			$res = $dbr->newSelectQueryBuilder()
				->select( [ 'rev_timestamp', 'page_namespace', 'page_title', ] )
				->from( 'revision' )
				->join( 'page', null, 'rev_page=page_id' )
				->where( [
					$dbr->expr( 'rev_timestamp', '>', $minTime ),
					$dbr->expr( 'rev_timestamp', '<=', $maxTime ),
				] )
				// Only get rows where the revision is the latest for the page.
				// Other revisions would be duplicate and we don't need to purge if
				// there has been an edit after the interesting time window.
				->andWhere( "page_latest = rev_id" )
				->orderBy( 'rev_timestamp' )
				->limit( $bSize )
				->caller( __METHOD__ )->fetchResultSet();

			if ( !$res->numRows() ) {
				// nothing more found so we are done
				break;
			}

			// Kludge to not get stuck in loops for batches with the same timestamp
			[ $rows, $lastTime ] = $this->pageableSortedRows( $res, 'rev_timestamp', $bSize );
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
			$hcu = $this->getServiceContainer()->getHtmlCacheUpdater();
			$hcu->purgeUrls( $urls, $hcu::PURGE_NAIVE );

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

		for ( $i = count( $rows ); $i--; ) {
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

// @codeCoverageIgnoreStart
$maintClass = PurgeChangedPages::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
