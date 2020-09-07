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

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionArchiveRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\RevisionStoreRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\BlobStore;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\LoadBalancer;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script for finding and marking bad content blobs.
 *
 * @ingroup Maintenance
 */
class FindBadBlobs extends Maintenance {

	/**
	 * @var RevisionStore|null
	 */
	private $revisionStore;

	/**
	 * @var BlobStore|null
	 */
	private $blobStore;

	/**
	 * @var LoadBalancer|null
	 */
	private $loadBalancer;

	/**
	 * @var LBFactory
	 */
	private $lbFactory;

	public function __construct() {
		parent::__construct();

		$this->setBatchSize( 1000 );
		$this->addDescription( 'Find and mark bad content blobs. '
			. 'Use --scan-from to find revisions with bad blobs, use --mark to mark them.' );
		$this->addOption( 'scan-from', 'Start scanning revisions at the given date. '
			. 'Format: Anything supported by MediaWiki, e.g. YYYYMMDDHHMMSS or YYYY-MM-DD_HH:MM:SS',
			false, true );
		$this->addOption( 'revisions', 'A list of revision IDs to process, separated by comma or '
			. 'colon or whitespace. Revisions belonging to deleted pages will work. '
			. 'If set to "-" IDs are read from stdin, one per line.', false, true );
		$this->addOption( 'limit', 'Maximum number of revisions for --scan-from to scan. '
			. 'Default: 1000', false, true );
		$this->addOption( 'mark', 'Mark the blob as "known bad", to avoid errors when '
			. 'attempting to read it. The value given is the reason for marking the blob as bad, '
			. 'typically a ticket ID. Requires --revisions to also be set.', false, true );
	}

	public function initializeServices(
		?RevisionStore $revisionStore = null,
		?BlobStore $blobStore = null,
		?LoadBalancer $loadBalancer = null,
		?LBFactory $lbFactory = null
	) {
		$services = MediaWikiServices::getInstance();

		$this->revisionStore = $revisionStore ?? $this->revisionStore ?? $services->getRevisionStore();
		$this->blobStore = $blobStore ?? $this->blobStore ?? $services->getBlobStore();
		$this->loadBalancer = $loadBalancer ?? $this->loadBalancer ?? $services->getDBLoadBalancer();
		$this->lbFactory = $lbFactory ?? $this->lbFactory ?? $services->getDBLoadBalancerFactory();
	}

	/**
	 * @return string
	 */
	private function getStartTimestamp() {
		$tsOpt = $this->getOption( 'scan-from' );
		if ( strlen( $tsOpt ) < 14 ) {
			$this->fatalError( 'Bad timestamp: ' . $tsOpt
				. ', please provide time and date down to the second.' );
		}

		$ts = wfTimestamp( TS_MW, $tsOpt );
		if ( !$ts ) {
			$this->fatalError( 'Bad timestamp: ' . $tsOpt );
		}

		return $ts;
	}

	/**
	 * @return int[]
	 */
	private function getRevisionIds() {
		$opt = $this->getOption( 'revisions' );

		if ( $opt === '-' ) {
			$opt = stream_get_contents( STDIN );

			if ( !$opt ) {
				return [];
			}
		}

		return $this->parseIntList( $opt );
	}

	/**
	 * @inheritDoc
	 */
	public function execute() {
		$this->initializeServices();

		if ( $this->hasOption( 'revisions' ) ) {
			if ( $this->hasOption( 'scan-from' ) ) {
				$this->fatalError( 'Cannot use --revisions together with --scan-from' );
			}

			$ids = $this->getRevisionIds();

			$count = $this->scanRevisionsById( $ids );
		} elseif ( $this->hasOption( 'scan-from' ) ) {
			if ( $this->hasOption( 'mark' ) ) {
				$this->fatalError( 'Cannot use --mark with --scan-from, '
					. 'use --revisions to specify revisions to mark.' );
			}

			$fromTimestamp = $this->getStartTimestamp();
			$total = $this->getOption( 'limit', 1000 );

			$count = $this->scanRevisionsByTimestamp( $fromTimestamp, $total );

			$this->output( "The range of archive rows scanned is based on the range of revision IDs "
				. "scanned in the revision table.\n" );
		} else {
			if ( $this->hasOption( 'mark' ) ) {
				$this->fatalError( 'The --mark must be used together with --revisions' );
			} else {
				$this->fatalError( 'Must specify one of --revisions or --scan-from' );
			}
		}

		if ( $this->hasOption( 'mark' ) ) {
			$this->output( "Marked $count bad revisions.\n" );
		} else {
			$this->output( "Found $count bad revisions.\n" );

			if ( $count > 0 ) {
				$this->output( "On a unix/linux environment, you can use grep and cut to list of IDs\n" );
				$this->output( "that can then be used with the --revisions option. E.g.\n" );
				$this->output( "  grep '!.*Bad blob address' | cut -s -f 3\n" );
			}
		}
	}

	/**
	 * @param string $fromTimestamp
	 * @param int $total
	 *
	 * @return int
	 */
	private function scanRevisionsByTimestamp( $fromTimestamp, $total ) {
		$count = 0;
		$lastRevId = 0;
		$firstRevId = 0;
		$lastTimestamp = $fromTimestamp;
		$revisionRowsScanned = 0;
		$archiveRowsScanned = 0;

		$this->output( "Scanning revisions table, "
			. "$total rows starting at rev_timestamp $fromTimestamp\n" );

		while ( $revisionRowsScanned < $total ) {
			$batchSize = min( $total - $revisionRowsScanned, $this->getBatchSize() );
			$revisions = $this->loadRevisionsByTimestamp( $lastRevId, $lastTimestamp, $batchSize );
			if ( !$revisions ) {
				break;
			}

			foreach ( $revisions as $rev ) {
				// we are sorting by timestamp, so we may encounter revision IDs out of sequence
				$firstRevId = $firstRevId ? min( $firstRevId, $rev->getId() ) : $rev->getId();
				$lastRevId = max( $lastRevId, $rev->getId() );

				$count += $this->checkRevision( $rev );
			}

			$lastTimestamp = $rev->getTimestamp();
			$batchSize = count( $revisions );
			$revisionRowsScanned += $batchSize;
			$this->output(
				"\t- Scanned a batch of $batchSize revisions, "
				. "up to revision $lastRevId ($lastTimestamp)\n"
			);

			$this->waitForReplication();
		}

		// NOTE: the archive table isn't indexed by timestamp, so the best we can do is use the
		// revision ID just before the first revision ID we found above as the starting point
		// of the scan, and scan up to on revision after the last revision ID we found above.
		// If $firstRevId is 0, the loop body above didn't execute,
		// so we should skip the one below as well.
		$fromArchived = $this->getNextRevision( $firstRevId, '<', 'DESC' );
		$maxArchived = $this->getNextRevision( $lastRevId, '>', 'ASC' );
		$maxArchived = $maxArchived ?: PHP_INT_MAX;

		$this->output( "Scanning archive table by ar_rev_id, $fromArchived to $maxArchived\n" );
		while ( $firstRevId > 0 && $fromArchived < $maxArchived ) {
			$batchSize = min( $total - $archiveRowsScanned, $this->getBatchSize() );
			$revisions = $this->loadArchiveByRevisionId( $fromArchived, $maxArchived, $batchSize );
			if ( !$revisions ) {
				break;
			}
			/** @var RevisionRecord $rev */
			foreach ( $revisions as $rev ) {
				$count += $this->checkRevision( $rev );
			}
			$fromArchived = $rev->getId();
			$batchSize = count( $revisions );
			$archiveRowsScanned += $batchSize;
			$this->output(
				"\t- Scanned a batch of $batchSize archived revisions, "
				. "up to revision $fromArchived ($lastTimestamp)\n"
			);

			$this->waitForReplication();
		}

		return $count;
	}

	/**
	 * @param int $afterId
	 * @param string $fromTimestamp
	 * @param int $batchSize
	 *
	 * @return RevisionStoreRecord[]
	 */
	private function loadRevisionsByTimestamp( int $afterId, string $fromTimestamp, $batchSize ) {
		$db = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		$queryInfo = $this->revisionStore->getQueryInfo();
		$quotedTimestamp = $db->addQuotes( $fromTimestamp );
		$rows = $db->select(
			$queryInfo['tables'],
			$queryInfo['fields'],
			"rev_timestamp > $quotedTimestamp OR "
			. "(rev_timestamp = $quotedTimestamp AND rev_id > $afterId )",
			__METHOD__,
			[
				'USE INDEX' => [ 'revision' => 'rev_timestamp' ],
				'ORDER BY' => 'rev_timestamp, rev_id',
				'LIMIT' => $batchSize,
			],
			$queryInfo['joins']
		);
		$result = $this->revisionStore->newRevisionsFromBatch( $rows, [ 'slots' => true ] );
		$this->handleStatus( $result );

		$records = array_filter( $result->value );

		'@phan-var RevisionStoreRecord[] $records';
		return $records;
	}

	/**
	 * @param int $afterId
	 * @param int $uptoId
	 * @param int $batchSize
	 *
	 * @return RevisionArchiveRecord[]
	 */
	private function loadArchiveByRevisionId( int $afterId, int $uptoId, $batchSize ) {
		$db = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		$queryInfo = $this->revisionStore->getArchiveQueryInfo();
		$rows = $db->select(
			$queryInfo['tables'],
			$queryInfo['fields'],
			[ "ar_rev_id > $afterId", "ar_rev_id <= $uptoId" ],
			__METHOD__,
			[ 'LIMIT' => $batchSize, 'ORDER BY' => 'ar_rev_id' ],
			$queryInfo['joins']
		);
		$result = $this->revisionStore->newRevisionsFromBatch(
			$rows,
			[ 'archive' => true, 'slots' => true ]
		);
		$this->handleStatus( $result );

		$records = array_filter( $result->value );

		'@phan-var RevisionArchiveRecord[] $records';
		return $records;
	}

	/**
	 * Returns the revision ID next to $revId, according to $comp and $dir
	 *
	 * @param int $revId
	 * @param string $comp the comparator, either '<' or '>', to go with $dir
	 * @param string $dir the sort direction to go with $comp, either 'ARC' or 'DESC'
	 *
	 * @return int
	 */
	private function getNextRevision( int $revId, string $comp, string $dir ) {
		$db = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		$next = $db->selectField(
			'revision',
			'rev_id',
			"rev_id $comp $revId",
			__METHOD__,
			[ 'ORDER BY' => "rev_id $dir" ]
		);
		return (int)$next;
	}

	/**
	 * @param array $ids
	 *
	 * @return int
	 */
	private function scanRevisionsById( array $ids ) {
		$count = 0;
		$total = count( $ids );

		$this->output( "Scanning $total ids\n" );

		foreach ( array_chunk( $ids, $this->getBatchSize() ) as $batch ) {
			$revisions = $this->loadRevisionsById( $batch );

			if ( !$revisions ) {
				continue;
			}

			/** @var RevisionRecord $rev */
			foreach ( $revisions as $rev ) {
				$count += $this->checkRevision( $rev );
			}

			$batchSize = count( $revisions );
			$this->output( "\t- Scanned a batch of $batchSize revisions\n" );
		}

		return $count;
	}

	/**
	 * @param int[] $ids
	 *
	 * @return RevisionRecord[]
	 */
	private function loadRevisionsById( array $ids ) {
		$db = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		$queryInfo = $this->revisionStore->getQueryInfo();

		$rows = $db->select(
			$queryInfo['tables'],
			$queryInfo['fields'],
			[
				'rev_id ' => $ids,
			],
			__METHOD__,
			[],
			$queryInfo['joins']
		);

		$result = $this->revisionStore->newRevisionsFromBatch( $rows, [ 'slots' => true ] );

		$this->handleStatus( $result );

		$revisions = array_filter( $result->value );
		'@phan-var RevisionArchiveRecord[] $revisions';

		// if not all revisions were found, check the archive table.
		if ( count( $revisions ) < count( $ids ) ) {
			$archiveQueryInfo = $this->revisionStore->getArchiveQueryInfo();
			$remainingIds = array_diff( $ids, array_keys( $revisions ) );

			$rows = $db->select(
				$archiveQueryInfo['tables'],
				$archiveQueryInfo['fields'],
				[
					'ar_rev_id ' => $remainingIds,
				],
				__METHOD__,
				[],
				$archiveQueryInfo['joins']
			);

			$archiveResult = $this->revisionStore->newRevisionsFromBatch(
				$rows,
				[ 'slots' => true, 'archive' => true ]
			);

			$this->handleStatus( $archiveResult );

			// don't use array_merge, since it will re-index
			$revisions = $revisions + array_filter( $archiveResult->value );
		}

		return $revisions;
	}

	/**
	 * @param RevisionRecord $rev
	 *
	 * @return int
	 */
	private function checkRevision( RevisionRecord $rev ) {
		$count = 0;
		foreach ( $rev->getSlots()->getSlots() as $slot ) {
			$count += $this->checkSlot( $rev, $slot );
		}

		if ( $count === 0 && $this->hasOption( 'mark' ) ) {
			$this->output( "\t# No bad blob found on revision {$rev->getId()}, skipped!\n" );
		}

		return $count;
	}

	/**
	 * @param RevisionRecord $rev
	 * @param SlotRecord $slot
	 *
	 * @return int
	 */
	private function checkSlot( RevisionRecord $rev, SlotRecord $slot ) {
		$address = $slot->getAddress();
		$error = null;
		$type = null;

		try {
			$this->blobStore->getBlob( $address );
			// nothing to do
			return 0;
		} catch ( Exception $ex ) {
			$error = $ex->getMessage();
			$type = get_class( $ex );
		}

		// NOTE: output the revision ID again at the end in a separate column for easy processing
		// via the "cut" shell command.
		$this->output( "\t! Found bad blob on revision {$rev->getId()} ({$slot->getRole()} slot): "
			. "content_id={$slot->getContentId()}, address=<{$slot->getAddress()}>, "
			. "error='$error', type='$type'. ID:\t{$rev->getId()}\n" );

		if ( $this->hasOption( 'mark' ) ) {
			$newAddress = $this->markBlob( $rev, $slot, $error );
			$this->output( "\tChanged address to <$newAddress>\n" );
		}

		return 1;
	}

	/**
	 * @param RevisionRecord $rev
	 * @param SlotRecord $slot
	 * @param string|null $error
	 *
	 * @return false|string
	 */
	private function markBlob( RevisionRecord $rev, SlotRecord $slot, string $error = null ) {
		$args = [];

		if ( $this->hasOption( 'mark' ) ) {
			$args['reason'] = $this->getOption( 'mark' );
		}

		if ( $error ) {
			$args['error'] = $error;
		}

		$address = $slot->getAddress() ?: 'empty';
		$badAddress = 'bad:' . urlencode( $address );

		if ( $args ) {
			$badAddress .= '?' . wfArrayToCgi( $args );
		}

		$badAddress = substr( $badAddress, 0, 255 );

		$dbw = $this->loadBalancer->getConnectionRef( DB_MASTER );
		$dbw->update(
			'content',
			[ 'content_address' => $badAddress ],
			[ 'content_id' => $slot->getContentId() ],
			__METHOD__
		);

		return $badAddress;
	}

	private function waitForReplication() {
		return $this->lbFactory->waitForReplication();
	}

	private function handleStatus( StatusValue $status ) {
		if ( !$status->isOK() ) {
			$this->fatalError(
				Status::wrap( $status )->getMessage( false, false, 'en' )->text()
			);
		}
		if ( !$status->isGood() ) {
			$this->error(
				"\t! " . Status::wrap( $status )->getMessage( false, false, 'en' )->text()
			);
		}
	}

}

$maintClass = FindBadBlobs::class;
require_once RUN_MAINTENANCE_IF_MAIN;
