<?php
/**
 * Moves blobs indexed by trackBlobs.php to a specified list of destination
 * clusters, and recompresses them in the process.
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
 * @ingroup Maintenance ExternalStorage
 */

use MediaWiki\Storage\SqlBlobStore;
use Wikimedia\Rdbms\IMaintainableDatabase;
use MediaWiki\Logger\LegacyLogger;
use MediaWiki\MediaWikiServices;
use MediaWiki\Shell\Shell;

$optionsWithArgs = RecompressTracked::getOptionsWithArgs();
require __DIR__ . '/../commandLine.inc';

if ( count( $args ) < 1 ) {
	echo "Usage: php recompressTracked.php [options] <cluster> [... <cluster>...]
Moves blobs indexed by trackBlobs.php to a specified list of destination clusters,
and recompresses them in the process. Restartable.

Options:
	--procs <procs>       Set the number of child processes (default 1)
	--copy-only           Copy only, do not update the text table. Restart
	                      without this option to complete.
	--debug-log <file>    Log debugging data to the specified file
	--info-log <file>     Log progress messages to the specified file
	--critical-log <file> Log error messages to the specified file
";
	exit( 1 );
}

$job = RecompressTracked::newFromCommandLine( $args, $options );
$job->execute();

/**
 * Maintenance script that moves blobs indexed by trackBlobs.php to a specified
 * list of destination clusters, and recompresses them in the process.
 *
 * @ingroup Maintenance ExternalStorage
 */
class RecompressTracked {
	public $destClusters;
	public $batchSize = 1000;
	public $orphanBatchSize = 1000;
	public $reportingInterval = 10;
	public $numProcs = 1;
	public $numBatches = 0;
	public $pageBlobClass, $orphanBlobClass;
	public $childPipes, $childProcs, $prevChildId;
	public $copyOnly = false;
	public $isChild = false;
	public $childId = false;
	public $noCount = false;
	public $debugLog, $infoLog, $criticalLog;
	/** @var ExternalStoreDB */
	public $store;
	/** @var SqlBlobStore */
	private $blobStore;

	private static $optionsWithArgs = [
		'procs',
		'child-id',
		'debug-log',
		'info-log',
		'critical-log'
	];

	private static $cmdLineOptionMap = [
		'no-count' => 'noCount',
		'procs' => 'numProcs',
		'copy-only' => 'copyOnly',
		'child' => 'isChild',
		'child-id' => 'childId',
		'debug-log' => 'debugLog',
		'info-log' => 'infoLog',
		'critical-log' => 'criticalLog',
	];

	static function getOptionsWithArgs() {
		return self::$optionsWithArgs;
	}

	static function newFromCommandLine( $args, $options ) {
		$jobOptions = [ 'destClusters' => $args ];
		foreach ( self::$cmdLineOptionMap as $cmdOption => $classOption ) {
			if ( isset( $options[$cmdOption] ) ) {
				$jobOptions[$classOption] = $options[$cmdOption];
			}
		}

		return new self( $jobOptions );
	}

	function __construct( $options ) {
		foreach ( $options as $name => $value ) {
			$this->$name = $value;
		}
		$esFactory = MediaWikiServices::getInstance()->getExternalStoreFactory();
		$this->store = $esFactory->getStore( 'DB' );
		if ( !$this->isChild ) {
			$GLOBALS['wgDebugLogPrefix'] = "RCT M: ";
		} elseif ( $this->childId !== false ) {
			$GLOBALS['wgDebugLogPrefix'] = "RCT {$this->childId}: ";
		}
		$this->pageBlobClass = function_exists( 'xdiff_string_bdiff' ) ?
			DiffHistoryBlob::class : ConcatenatedGzipHistoryBlob::class;
		$this->orphanBlobClass = ConcatenatedGzipHistoryBlob::class;
		// @phan-suppress-next-line PhanAccessMethodInternal
		$this->blobStore = MediaWikiServices::getInstance()
			->getBlobStoreFactory()
			->newSqlBlobStore();
	}

	function debug( $msg ) {
		wfDebug( "$msg\n" );
		if ( $this->debugLog ) {
			$this->logToFile( $msg, $this->debugLog );
		}
	}

	function info( $msg ) {
		echo "$msg\n";
		if ( $this->infoLog ) {
			$this->logToFile( $msg, $this->infoLog );
		}
	}

	function critical( $msg ) {
		echo "$msg\n";
		if ( $this->criticalLog ) {
			$this->logToFile( $msg, $this->criticalLog );
		}
	}

	function logToFile( $msg, $file ) {
		$header = '[' . date( 'd\TH:i:s' ) . '] ' . wfHostname() . ' ' . posix_getpid();
		if ( $this->childId !== false ) {
			$header .= "({$this->childId})";
		}
		$header .= ' ' . WikiMap::getCurrentWikiDbDomain()->getId();
		LegacyLogger::emit( sprintf( "%-50s %s\n", $header, $msg ), $file );
	}

	/**
	 * Wait until the selected replica DB has caught up to the master.
	 * This allows us to use the replica DB for things that were committed in a
	 * previous part of this batch process.
	 */
	function syncDBs() {
		$dbw = wfGetDB( DB_MASTER );
		$dbr = wfGetDB( DB_REPLICA );
		$pos = $dbw->getMasterPos();
		$dbr->masterPosWait( $pos, 100000 );
	}

	/**
	 * Execute parent or child depending on the isChild option
	 */
	function execute() {
		if ( $this->isChild ) {
			$this->executeChild();
		} else {
			$this->executeParent();
		}
	}

	/**
	 * Execute the parent process
	 */
	function executeParent() {
		if ( !$this->checkTrackingTable() ) {
			return;
		}

		$this->syncDBs();
		$this->startChildProcs();
		$this->doAllPages();
		$this->doAllOrphans();
		$this->killChildProcs();
	}

	/**
	 * Make sure the tracking table exists and isn't empty
	 * @return bool
	 */
	function checkTrackingTable() {
		$dbr = wfGetDB( DB_REPLICA );
		if ( !$dbr->tableExists( 'blob_tracking' ) ) {
			$this->critical( "Error: blob_tracking table does not exist" );

			return false;
		}
		$row = $dbr->selectRow( 'blob_tracking', '*', '', __METHOD__ );
		if ( !$row ) {
			$this->info( "Warning: blob_tracking table contains no rows, skipping this wiki." );

			return false;
		}

		return true;
	}

	/**
	 * Start the worker processes.
	 * These processes will listen on stdin for commands.
	 * This necessary because text recompression is slow: loading, compressing and
	 * writing are all slow.
	 */
	function startChildProcs() {
		$wiki = WikiMap::getWikiIdFromDbDomain( WikiMap::getCurrentWikiDbDomain() );

		$cmd = 'php ' . Shell::escape( __FILE__ );
		foreach ( self::$cmdLineOptionMap as $cmdOption => $classOption ) {
			if ( $cmdOption == 'child-id' ) {
				continue;
			} elseif ( in_array( $cmdOption, self::$optionsWithArgs ) && isset( $this->$classOption ) ) {
				$cmd .= " --$cmdOption " . Shell::escape( $this->$classOption );
			} elseif ( $this->$classOption ) {
				$cmd .= " --$cmdOption";
			}
		}
		$cmd .= ' --child' .
			' --wiki ' . Shell::escape( $wiki ) .
			' ' . Shell::escape( ...$this->destClusters );

		$this->childPipes = $this->childProcs = [];
		for ( $i = 0; $i < $this->numProcs; $i++ ) {
			$pipes = [];
			$spec = [
				[ 'pipe', 'r' ],
				[ 'file', 'php://stdout', 'w' ],
				[ 'file', 'php://stderr', 'w' ]
			];
			Wikimedia\suppressWarnings();
			$proc = proc_open( "$cmd --child-id $i", $spec, $pipes );
			Wikimedia\restoreWarnings();
			if ( !$proc ) {
				$this->critical( "Error opening child process: $cmd" );
				exit( 1 );
			}
			$this->childProcs[$i] = $proc;
			$this->childPipes[$i] = $pipes[0];
		}
		$this->prevChildId = -1;
	}

	/**
	 * Gracefully terminate the child processes
	 */
	function killChildProcs() {
		$this->info( "Waiting for child processes to finish..." );
		for ( $i = 0; $i < $this->numProcs; $i++ ) {
			$this->dispatchToChild( $i, 'quit' );
		}
		for ( $i = 0; $i < $this->numProcs; $i++ ) {
			$status = proc_close( $this->childProcs[$i] );
			if ( $status ) {
				$this->critical( "Warning: child #$i exited with status $status" );
			}
		}
		$this->info( "Done." );
	}

	/**
	 * Dispatch a command to the next available child process.
	 * This may block until a child process finishes its work and becomes available.
	 * @param array|string ...$args
	 */
	function dispatch( ...$args ) {
		$pipes = $this->childPipes;
		$x = [];
		$y = [];
		$numPipes = stream_select( $x, $pipes, $y, 3600 );
		if ( !$numPipes ) {
			$this->critical( "Error waiting to write to child process. Aborting" );
			exit( 1 );
		}
		for ( $i = 0; $i < $this->numProcs; $i++ ) {
			$childId = ( $i + $this->prevChildId + 1 ) % $this->numProcs;
			if ( isset( $pipes[$childId] ) ) {
				$this->prevChildId = $childId;
				$this->dispatchToChild( $childId, $args );

				return;
			}
		}
		$this->critical( "Unreachable" );
		exit( 1 );
	}

	/**
	 * Dispatch a command to a specified child process
	 * @param int $childId
	 * @param array|string $args
	 */
	function dispatchToChild( $childId, $args ) {
		$args = (array)$args;
		$cmd = implode( ' ', $args );
		fwrite( $this->childPipes[$childId], "$cmd\n" );
	}

	/**
	 * Move all tracked pages to the new clusters
	 */
	function doAllPages() {
		$dbr = wfGetDB( DB_REPLICA );
		$i = 0;
		$startId = 0;
		if ( $this->noCount ) {
			$numPages = '[unknown]';
		} else {
			$numPages = $dbr->selectField( 'blob_tracking',
				'COUNT(DISTINCT bt_page)',
				# A condition is required so that this query uses the index
				[ 'bt_moved' => 0 ],
				__METHOD__
			);
		}
		if ( $this->copyOnly ) {
			$this->info( "Copying pages..." );
		} else {
			$this->info( "Moving pages..." );
		}
		while ( true ) {
			$res = $dbr->select( 'blob_tracking',
				[ 'bt_page' ],
				[
					'bt_moved' => 0,
					'bt_page > ' . $dbr->addQuotes( $startId )
				],
				__METHOD__,
				[
					'DISTINCT',
					'ORDER BY' => 'bt_page',
					'LIMIT' => $this->batchSize,
				]
			);
			if ( !$res->numRows() ) {
				break;
			}
			foreach ( $res as $row ) {
				$startId = $row->bt_page;
				$this->dispatch( 'doPage', $row->bt_page );
				$i++;
			}
			$this->report( 'pages', $i, $numPages );
		}
		$this->report( 'pages', $i, $numPages );
		if ( $this->copyOnly ) {
			$this->info( "All page copies queued." );
		} else {
			$this->info( "All page moves queued." );
		}
	}

	/**
	 * Display a progress report
	 * @param string $label
	 * @param int $current
	 * @param int $end
	 */
	function report( $label, $current, $end ) {
		$this->numBatches++;
		if ( $current == $end || $this->numBatches >= $this->reportingInterval ) {
			$this->numBatches = 0;
			$this->info( "$label: $current / $end" );
			MediaWikiServices::getInstance()->getDBLoadBalancerFactory()->waitForReplication();
		}
	}

	/**
	 * Move all orphan text to the new clusters
	 */
	function doAllOrphans() {
		$dbr = wfGetDB( DB_REPLICA );
		$startId = 0;
		$i = 0;
		if ( $this->noCount ) {
			$numOrphans = '[unknown]';
		} else {
			$numOrphans = $dbr->selectField( 'blob_tracking',
				'COUNT(DISTINCT bt_text_id)',
				[ 'bt_moved' => 0, 'bt_page' => 0 ],
				__METHOD__ );
			if ( !$numOrphans ) {
				return;
			}
		}
		if ( $this->copyOnly ) {
			$this->info( "Copying orphans..." );
		} else {
			$this->info( "Moving orphans..." );
		}

		while ( true ) {
			$res = $dbr->select( 'blob_tracking',
				[ 'bt_text_id' ],
				[
					'bt_moved' => 0,
					'bt_page' => 0,
					'bt_text_id > ' . $dbr->addQuotes( $startId )
				],
				__METHOD__,
				[
					'DISTINCT',
					'ORDER BY' => 'bt_text_id',
					'LIMIT' => $this->batchSize
				]
			);
			if ( !$res->numRows() ) {
				break;
			}
			$ids = [];
			foreach ( $res as $row ) {
				$startId = $row->bt_text_id;
				$ids[] = $row->bt_text_id;
				$i++;
			}
			// Need to send enough orphan IDs to the child at a time to fill a blob,
			// so orphanBatchSize needs to be at least ~100.
			// batchSize can be smaller or larger.
			while ( count( $ids ) > $this->orphanBatchSize ) {
				$args = array_slice( $ids, 0, $this->orphanBatchSize );
				$ids = array_slice( $ids, $this->orphanBatchSize );
				array_unshift( $args, 'doOrphanList' );
				$this->dispatch( ...$args );
			}
			if ( count( $ids ) ) {
				$args = $ids;
				array_unshift( $args, 'doOrphanList' );
				$this->dispatch( ...$args );
			}

			$this->report( 'orphans', $i, $numOrphans );
		}
		$this->report( 'orphans', $i, $numOrphans );
		$this->info( "All orphans queued." );
	}

	/**
	 * Main entry point for worker processes
	 */
	function executeChild() {
		$this->debug( 'starting' );
		$this->syncDBs();

		while ( !feof( STDIN ) ) {
			$line = rtrim( fgets( STDIN ) );
			if ( $line == '' ) {
				continue;
			}
			$this->debug( $line );
			$args = explode( ' ', $line );
			$cmd = array_shift( $args );
			switch ( $cmd ) {
				case 'doPage':
					$this->doPage( intval( $args[0] ) );
					break;
				case 'doOrphanList':
					$this->doOrphanList( array_map( 'intval', $args ) );
					break;
				case 'quit':
					return;
			}
			MediaWikiServices::getInstance()->getDBLoadBalancerFactory()->waitForReplication();
		}
	}

	/**
	 * Move tracked text in a given page
	 *
	 * @param int $pageId
	 */
	function doPage( $pageId ) {
		$title = Title::newFromID( $pageId );
		if ( $title ) {
			$titleText = $title->getPrefixedText();
		} else {
			$titleText = '[deleted]';
		}
		$dbr = wfGetDB( DB_REPLICA );

		// Finish any incomplete transactions
		if ( !$this->copyOnly ) {
			$this->finishIncompleteMoves( [ 'bt_page' => $pageId ] );
			$this->syncDBs();
		}

		$startId = 0;
		$trx = new CgzCopyTransaction( $this, $this->pageBlobClass );

		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		while ( true ) {
			$res = $dbr->select(
				[ 'blob_tracking', 'text' ],
				'*',
				[
					'bt_page' => $pageId,
					'bt_text_id > ' . $dbr->addQuotes( $startId ),
					'bt_moved' => 0,
					'bt_new_url IS NULL',
					'bt_text_id=old_id',
				],
				__METHOD__,
				[
					'ORDER BY' => 'bt_text_id',
					'LIMIT' => $this->batchSize
				]
			);
			if ( !$res->numRows() ) {
				break;
			}

			$lastTextId = 0;
			foreach ( $res as $row ) {
				$startId = $row->bt_text_id;
				if ( $lastTextId == $row->bt_text_id ) {
					// Duplicate (null edit)
					continue;
				}
				$lastTextId = $row->bt_text_id;
				// Load the text
				$text = $this->blobStore->expandBlob( $row->old_text, $row->old_flags );
				if ( $text === false ) {
					$this->critical( "Error loading {$row->bt_rev_id}/{$row->bt_text_id}" );
					continue;
				}

				// Queue it
				if ( !$trx->addItem( $text, $row->bt_text_id ) ) {
					$this->debug( "$titleText: committing blob with " . $trx->getSize() . " items" );
					$trx->commit();
					$trx = new CgzCopyTransaction( $this, $this->pageBlobClass );
					$lbFactory->waitForReplication();
				}
			}
		}

		$this->debug( "$titleText: committing blob with " . $trx->getSize() . " items" );
		$trx->commit();
	}

	/**
	 * Atomic move operation.
	 *
	 * Write the new URL to the text table and set the bt_moved flag.
	 *
	 * This is done in a single transaction to provide restartable behavior
	 * without data loss.
	 *
	 * The transaction is kept short to reduce locking.
	 *
	 * @param int $textId
	 * @param string $url
	 */
	function moveTextRow( $textId, $url ) {
		if ( $this->copyOnly ) {
			$this->critical( "Internal error: can't call moveTextRow() in --copy-only mode" );
			exit( 1 );
		}
		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin( __METHOD__ );
		$dbw->update( 'text',
			[ // set
				'old_text' => $url,
				'old_flags' => 'external,utf-8',
			],
			[ // where
				'old_id' => $textId
			],
			__METHOD__
		);
		$dbw->update( 'blob_tracking',
			[ 'bt_moved' => 1 ],
			[ 'bt_text_id' => $textId ],
			__METHOD__
		);
		$dbw->commit( __METHOD__ );
	}

	/**
	 * Moves are done in two phases: bt_new_url and then bt_moved.
	 *  - bt_new_url indicates that the text has been copied to the new cluster.
	 *  - bt_moved indicates that the text table has been updated.
	 *
	 * This function completes any moves that only have done bt_new_url. This
	 * can happen when the script is interrupted, or when --copy-only is used.
	 *
	 * @param array $conds
	 */
	function finishIncompleteMoves( $conds ) {
		$dbr = wfGetDB( DB_REPLICA );
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();

		$startId = 0;
		$conds = array_merge( $conds, [
			'bt_moved' => 0,
			'bt_new_url IS NOT NULL'
		] );
		while ( true ) {
			$res = $dbr->select( 'blob_tracking',
				'*',
				array_merge( $conds, [ 'bt_text_id > ' . $dbr->addQuotes( $startId ) ] ),
				__METHOD__,
				[
					'ORDER BY' => 'bt_text_id',
					'LIMIT' => $this->batchSize,
				]
			);
			if ( !$res->numRows() ) {
				break;
			}
			$this->debug( 'Incomplete: ' . $res->numRows() . ' rows' );
			foreach ( $res as $row ) {
				$startId = $row->bt_text_id;
				$this->moveTextRow( $row->bt_text_id, $row->bt_new_url );
				if ( $row->bt_text_id % 10 == 0 ) {
					$lbFactory->waitForReplication();
				}
			}
		}
	}

	/**
	 * Returns the name of the next target cluster
	 * @return string
	 */
	function getTargetCluster() {
		$cluster = next( $this->destClusters );
		if ( $cluster === false ) {
			$cluster = reset( $this->destClusters );
		}

		return $cluster;
	}

	/**
	 * Gets a DB master connection for the given external cluster name
	 * @param string $cluster
	 * @return IMaintainableDatabase
	 */
	function getExtDB( $cluster ) {
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$lb = $lbFactory->getExternalLB( $cluster );

		return $lb->getMaintenanceConnectionRef( DB_MASTER );
	}

	/**
	 * Move an orphan text_id to the new cluster
	 *
	 * @param array $textIds
	 */
	function doOrphanList( $textIds ) {
		// Finish incomplete moves
		if ( !$this->copyOnly ) {
			$this->finishIncompleteMoves( [ 'bt_text_id' => $textIds ] );
			$this->syncDBs();
		}

		$trx = new CgzCopyTransaction( $this, $this->orphanBlobClass );

		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$res = wfGetDB( DB_REPLICA )->select(
			[ 'text', 'blob_tracking' ],
			[ 'old_id', 'old_text', 'old_flags' ],
			[
				'old_id' => $textIds,
				'bt_text_id=old_id',
				'bt_moved' => 0,
			],
			__METHOD__,
			[ 'DISTINCT' ]
		);

		foreach ( $res as $row ) {
			$text = $this->blobStore->expandBlob( $row->old_text, $row->old_flags );
			if ( $text === false ) {
				$this->critical( "Error: cannot load revision text for old_id={$row->old_id}" );
				continue;
			}

			if ( !$trx->addItem( $text, $row->old_id ) ) {
				$this->debug( "[orphan]: committing blob with " . $trx->getSize() . " rows" );
				$trx->commit();
				$trx = new CgzCopyTransaction( $this, $this->orphanBlobClass );
				$lbFactory->waitForReplication();
			}
		}
		$this->debug( "[orphan]: committing blob with " . $trx->getSize() . " rows" );
		$trx->commit();
	}
}

/**
 * Class to represent a recompression operation for a single CGZ blob
 */
class CgzCopyTransaction {
	/** @var RecompressTracked */
	public $parent;
	public $blobClass;
	/** @var ConcatenatedGzipHistoryBlob|false */
	public $cgz;
	public $referrers;
	/** @var array */
	private $texts;

	/**
	 * Create a transaction from a RecompressTracked object
	 * @param RecompressTracked $parent
	 * @param string $blobClass
	 */
	function __construct( $parent, $blobClass ) {
		$this->blobClass = $blobClass;
		$this->cgz = false;
		$this->texts = [];
		$this->parent = $parent;
	}

	/**
	 * Add text.
	 * Returns false if it's ready to commit.
	 * @param string $text
	 * @param int $textId
	 * @return bool
	 */
	function addItem( $text, $textId ) {
		if ( !$this->cgz ) {
			$class = $this->blobClass;
			$this->cgz = new $class;
		}
		$hash = $this->cgz->addItem( $text );
		$this->referrers[$textId] = $hash;
		$this->texts[$textId] = $text;

		return $this->cgz->isHappy();
	}

	function getSize() {
		return count( $this->texts );
	}

	/**
	 * Recompress text after some aberrant modification
	 */
	function recompress() {
		$class = $this->blobClass;
		$this->cgz = new $class;
		$this->referrers = [];
		foreach ( $this->texts as $textId => $text ) {
			$hash = $this->cgz->addItem( $text );
			$this->referrers[$textId] = $hash;
		}
	}

	/**
	 * Commit the blob.
	 * Does nothing if no text items have been added.
	 * May skip the move if --copy-only is set.
	 */
	function commit() {
		$originalCount = count( $this->texts );
		if ( !$originalCount ) {
			return;
		}

		/* Check to see if the target text_ids have been moved already.
		 *
		 * We originally read from the replica DB, so this can happen when a single
		 * text_id is shared between multiple pages. It's rare, but possible
		 * if a delete/move/undelete cycle splits up a null edit.
		 *
		 * We do a locking read to prevent closer-run race conditions.
		 */
		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin( __METHOD__ );
		$res = $dbw->select( 'blob_tracking',
			[ 'bt_text_id', 'bt_moved' ],
			[ 'bt_text_id' => array_keys( $this->referrers ) ],
			__METHOD__, [ 'FOR UPDATE' ] );
		$dirty = false;
		foreach ( $res as $row ) {
			if ( $row->bt_moved ) {
				# This row has already been moved, remove it
				$this->parent->debug( "TRX: conflict detected in old_id={$row->bt_text_id}" );
				unset( $this->texts[$row->bt_text_id] );
				$dirty = true;
			}
		}

		// Recompress the blob if necessary
		if ( $dirty ) {
			if ( !count( $this->texts ) ) {
				// All have been moved already
				if ( $originalCount > 1 ) {
					// This is suspcious, make noise
					$this->parent->critical(
						"Warning: concurrent operation detected, are there two conflicting " .
						"processes running, doing the same job?" );
				}

				return;
			}
			$this->recompress();
		}

		// Insert the data into the destination cluster
		$targetCluster = $this->parent->getTargetCluster();
		$store = $this->parent->store;
		$targetDB = $store->getMaster( $targetCluster );
		$targetDB->clearFlag( DBO_TRX ); // we manage the transactions
		$targetDB->begin( __METHOD__ );
		$baseUrl = $this->parent->store->store( $targetCluster, serialize( $this->cgz ) );

		// Write the new URLs to the blob_tracking table
		foreach ( $this->referrers as $textId => $hash ) {
			$url = $baseUrl . '/' . $hash;
			$dbw->update( 'blob_tracking',
				[ 'bt_new_url' => $url ],
				[
					'bt_text_id' => $textId,
					'bt_moved' => 0, # Check for concurrent conflicting update
				],
				__METHOD__
			);
		}

		$targetDB->commit( __METHOD__ );
		// Critical section here: interruption at this point causes blob duplication
		// Reversing the order of the commits would cause data loss instead
		$dbw->commit( __METHOD__ );

		// Write the new URLs to the text table and set the moved flag
		if ( !$this->parent->copyOnly ) {
			foreach ( $this->referrers as $textId => $hash ) {
				$url = $baseUrl . '/' . $hash;
				$this->parent->moveTextRow( $textId, $url );
			}
		}
	}
}
