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

$optionsWithArgs = RecompressTracked::getOptionsWithArgs();
require( dirname( __FILE__ ) . '/../commandLine.inc' );

if ( count( $args ) < 1 ) {
	echo "Usage: php recompressTracked.php [options] <cluster> [... <cluster>...]
Moves blobs indexed by trackBlobs.php to a specified list of destination clusters, and recompresses them in the process. Restartable.

Options:
	--procs <procs>         Set the number of child processes (default 1)
	--copy-only             Copy only, do not update the text table. Restart without this option to complete.
	--debug-log <file>      Log debugging data to the specified file
	--info-log <file>       Log progress messages to the specified file
	--critical-log <file>   Log error messages to the specified file
";
	exit( 1 );
}

$job = RecompressTracked::newFromCommandLine( $args, $options );
$job->execute();

class RecompressTracked {
	var $destClusters;
	var $batchSize = 1000;
	var $orphanBatchSize = 1000;
	var $reportingInterval = 10;
	var $numProcs = 1;
	var $useDiff, $pageBlobClass, $orphanBlobClass;
	var $slavePipes, $slaveProcs, $prevSlaveId;
	var $copyOnly = false;
	var $isChild = false;
	var $slaveId = false;
	var $noCount = false;
	var $debugLog, $infoLog, $criticalLog;
	var $store;

	static $optionsWithArgs = array( 'procs', 'slave-id', 'debug-log', 'info-log', 'critical-log' );
	static $cmdLineOptionMap = array(
		'no-count' => 'noCount',
		'procs' => 'numProcs',
		'copy-only' => 'copyOnly',
		'child' => 'isChild',
		'slave-id' => 'slaveId',
		'debug-log' => 'debugLog',
		'info-log' => 'infoLog',
		'critical-log' => 'criticalLog',
	);

	static function getOptionsWithArgs() {
		return self::$optionsWithArgs;
	}

	static function newFromCommandLine( $args, $options ) {
		$jobOptions = array( 'destClusters' => $args );
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
		$this->store = new ExternalStoreDB;
		if ( !$this->isChild ) {
			$GLOBALS['wgDebugLogPrefix'] = "RCT M: ";
		} elseif ( $this->slaveId !== false ) {
			$GLOBALS['wgDebugLogPrefix'] = "RCT {$this->slaveId}: ";
		}
		$this->useDiff = function_exists( 'xdiff_string_bdiff' );
		$this->pageBlobClass = $this->useDiff ? 'DiffHistoryBlob' : 'ConcatenatedGzipHistoryBlob';
		$this->orphanBlobClass = 'ConcatenatedGzipHistoryBlob';
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
		if ( $this->slaveId !== false ) {
			$header .= "({$this->slaveId})";
		}
		$header .= ' ' . wfWikiID();
		wfErrorLog( sprintf( "%-50s %s\n", $header, $msg ), $file );
	}

	/**
	 * Wait until the selected slave has caught up to the master.
	 * This allows us to use the slave for things that were committed in a
	 * previous part of this batch process.
	 */
	function syncDBs() {
		$dbw = wfGetDB( DB_MASTER );
		$dbr = wfGetDB( DB_SLAVE );
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
		$this->startSlaveProcs();
		$this->doAllPages();
		$this->doAllOrphans();
		$this->killSlaveProcs();
	}

	/**
	 * Make sure the tracking table exists and isn't empty
	 */
	function checkTrackingTable() {
		$dbr = wfGetDB( DB_SLAVE );
		if ( !$dbr->tableExists( 'blob_tracking' ) ) {
			$this->critical( "Error: blob_tracking table does not exist" );
			return false;
		}
		$row = $dbr->selectRow( 'blob_tracking', '*', false, __METHOD__ );
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
	function startSlaveProcs() {
		$cmd = 'php ' . wfEscapeShellArg( __FILE__ );
		foreach ( self::$cmdLineOptionMap as $cmdOption => $classOption ) {
			if ( $cmdOption == 'slave-id' ) {
				continue;
			} elseif ( in_array( $cmdOption, self::$optionsWithArgs ) && isset( $this->$classOption ) ) {
				$cmd .= " --$cmdOption " . wfEscapeShellArg( $this->$classOption );
			} elseif ( $this->$classOption ) {
				$cmd .= " --$cmdOption";
			}
		}
		$cmd .= ' --child' .
			' --wiki ' . wfEscapeShellArg( wfWikiID() ) .
			' ' . call_user_func_array( 'wfEscapeShellArg', $this->destClusters );

		$this->slavePipes = $this->slaveProcs = array();
		for ( $i = 0; $i < $this->numProcs; $i++ ) {
			$pipes = false;
			$spec = array(
				array( 'pipe', 'r' ),
				array( 'file', 'php://stdout', 'w' ),
				array( 'file', 'php://stderr', 'w' )
			);
			wfSuppressWarnings();
			$proc = proc_open( "$cmd --slave-id $i", $spec, $pipes );
			wfRestoreWarnings();
			if ( !$proc ) {
				$this->critical( "Error opening slave process: $cmd" );
				exit( 1 );
			}
			$this->slaveProcs[$i] = $proc;
			$this->slavePipes[$i] = $pipes[0];
		}
		$this->prevSlaveId = -1;
	}

	/**
	 * Gracefully terminate the child processes
	 */
	function killSlaveProcs() {
		$this->info( "Waiting for slave processes to finish..." );
		for ( $i = 0; $i < $this->numProcs; $i++ ) {
			$this->dispatchToSlave( $i, 'quit' );
		}
		for ( $i = 0; $i < $this->numProcs; $i++ ) {
			$status = proc_close( $this->slaveProcs[$i] );
			if ( $status ) {
				$this->critical( "Warning: child #$i exited with status $status" );
			}
		}
		$this->info( "Done." );
	}

	/**
	 * Dispatch a command to the next available slave.
	 * This may block until a slave finishes its work and becomes available.
	 */
	function dispatch( /*...*/ ) {
		$args = func_get_args();
		$pipes = $this->slavePipes;
		$numPipes = stream_select( $x = array(), $pipes, $y = array(), 3600 );
		if ( !$numPipes ) {
			$this->critical( "Error waiting to write to slaves. Aborting" );
			exit( 1 );
		}
		for ( $i = 0; $i < $this->numProcs; $i++ ) {
			$slaveId = ( $i + $this->prevSlaveId + 1 ) % $this->numProcs;
			if ( isset( $pipes[$slaveId] ) ) {
				$this->prevSlaveId = $slaveId;
				$this->dispatchToSlave( $slaveId, $args );
				return;
			}
		}
		$this->critical( "Unreachable" );
		exit( 1 );
	}

	/**
	 * Dispatch a command to a specified slave
	 */
	function dispatchToSlave( $slaveId, $args ) {
		$args = (array)$args;
		$cmd = implode( ' ',  $args );
		fwrite( $this->slavePipes[$slaveId], "$cmd\n" );
	}

	/**
	 * Move all tracked pages to the new clusters
	 */
	function doAllPages() {
		$dbr = wfGetDB( DB_SLAVE );
		$i = 0;
		$startId = 0;
		if ( $this->noCount ) {
			$numPages = '[unknown]';
		} else {
			$numPages = $dbr->selectField( 'blob_tracking',
				'COUNT(DISTINCT bt_page)',
				# A condition is required so that this query uses the index
				array( 'bt_moved' => 0 ),
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
				array( 'bt_page' ),
				array(
					'bt_moved' => 0,
					'bt_page > ' . $dbr->addQuotes( $startId )
				),
				__METHOD__,
				array(
					'DISTINCT',
					'ORDER BY' => 'bt_page',
					'LIMIT' => $this->batchSize,
				)
			);
			if ( !$res->numRows() ) {
				break;
			}
			foreach ( $res as $row ) {
				$this->dispatch( 'doPage', $row->bt_page );
				$i++;
			}
			$startId = $row->bt_page;
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
	 */
	function report( $label, $current, $end ) {
		$this->numBatches++;
		if ( $current == $end || $this->numBatches >= $this->reportingInterval ) {
			$this->numBatches = 0;
			$this->info( "$label: $current / $end" );
			$this->waitForSlaves();
		}
	}

	/**
	 * Move all orphan text to the new clusters
	 */
	function doAllOrphans() {
		$dbr = wfGetDB( DB_SLAVE );
		$startId = 0;
		$i = 0;
		if ( $this->noCount ) {
			$numOrphans = '[unknown]';
		} else {
			$numOrphans = $dbr->selectField( 'blob_tracking',
				'COUNT(DISTINCT bt_text_id)',
				array( 'bt_moved' => 0, 'bt_page' => 0 ),
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
				array( 'bt_text_id' ),
				array(
					'bt_moved' => 0,
					'bt_page' => 0,
					'bt_text_id > ' . $dbr->addQuotes( $startId )
				),
				__METHOD__,
				array(
					'DISTINCT',
					'ORDER BY' => 'bt_text_id',
					'LIMIT' => $this->batchSize
				)
			);
			if ( !$res->numRows() ) {
				break;
			}
			$ids = array();
			foreach ( $res as $row ) {
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
				call_user_func_array( array( $this, 'dispatch' ), $args );
			}
			if ( count( $ids ) ) {
				$args = $ids;
				array_unshift( $args, 'doOrphanList' );
				call_user_func_array( array( $this, 'dispatch' ), $args );
			}

			$startId = $row->bt_text_id;
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
			$this->waitForSlaves();
		}
	}

	/**
	 * Move tracked text in a given page
	 */
	function doPage( $pageId ) {
		$title = Title::newFromId( $pageId );
		if ( $title ) {
			$titleText = $title->getPrefixedText();
		} else {
			$titleText = '[deleted]';
		}
		$dbr = wfGetDB( DB_SLAVE );

		// Finish any incomplete transactions
		if ( !$this->copyOnly ) {
			$this->finishIncompleteMoves( array( 'bt_page' => $pageId ) );
			$this->syncDBs();
		}

		$startId = 0;
		$trx = new CgzCopyTransaction( $this, $this->pageBlobClass );

		while ( true ) {
			$res = $dbr->select(
				array( 'blob_tracking', 'text' ),
				'*',
				array(
					'bt_page' => $pageId,
					'bt_text_id > ' . $dbr->addQuotes( $startId ),
					'bt_moved' => 0,
					'bt_new_url IS NULL',
					'bt_text_id=old_id',
				),
				__METHOD__,
				array(
					'ORDER BY' => 'bt_text_id',
					'LIMIT' => $this->batchSize
				)
			);
			if ( !$res->numRows() ) {
				break;
			}

			$lastTextId = 0;
			foreach ( $res as $row ) {
				if ( $lastTextId == $row->bt_text_id ) {
					// Duplicate (null edit)
					continue;
				}
				$lastTextId = $row->bt_text_id;
				// Load the text
				$text = Revision::getRevisionText( $row );
				if ( $text === false ) {
					$this->critical( "Error loading {$row->bt_rev_id}/{$row->bt_text_id}" );
					continue;
				}

				// Queue it
				if ( !$trx->addItem( $text, $row->bt_text_id ) ) {
					$this->debug( "$titleText: committing blob with " . $trx->getSize() . " items" );
					$trx->commit();
					$trx = new CgzCopyTransaction( $this, $this->pageBlobClass );
					$this->waitForSlaves();
				}
			}
			$startId = $row->bt_text_id;
		}

		$this->debug( "$titleText: committing blob with " . $trx->getSize() . " items" );
		$trx->commit();
	}

	/**
	 * Atomic move operation.
	 *
	 * Write the new URL to the text table and set the bt_moved flag.
	 *
	 * This is done in a single transaction to provide restartable behaviour
	 * without data loss.
	 *
	 * The transaction is kept short to reduce locking.
	 */
	function moveTextRow( $textId, $url ) {
		if ( $this->copyOnly ) {
			$this->critical( "Internal error: can't call moveTextRow() in --copy-only mode" );
			exit( 1 );
		}
		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin();
		$dbw->update( 'text',
			array( // set
				'old_text' => $url,
				'old_flags' => 'external,utf-8',
			),
			array( // where
				'old_id' => $textId
			),
			__METHOD__
		);
		$dbw->update( 'blob_tracking',
			array( 'bt_moved' => 1 ),
			array( 'bt_text_id' => $textId ),
			__METHOD__
		);
		$dbw->commit();
	}

	/**
	 * Moves are done in two phases: bt_new_url and then bt_moved.
	 *  - bt_new_url indicates that the text has been copied to the new cluster.
	 *  - bt_moved indicates that the text table has been updated.
	 *
	 * This function completes any moves that only have done bt_new_url. This
	 * can happen when the script is interrupted, or when --copy-only is used.
	 */
	function finishIncompleteMoves( $conds ) {
		$dbr = wfGetDB( DB_SLAVE );

		$startId = 0;
		$conds = array_merge( $conds, array(
			'bt_moved' => 0,
			'bt_new_url IS NOT NULL'
		) );
		while ( true ) {
			$res = $dbr->select( 'blob_tracking',
				'*',
				array_merge( $conds, array( 'bt_text_id > ' . $dbr->addQuotes( $startId ) ) ),
				__METHOD__,
				array(
					'ORDER BY' => 'bt_text_id',
					'LIMIT' => $this->batchSize,
				)
			);
			if ( !$res->numRows() ) {
				break;
			}
			$this->debug( 'Incomplete: ' . $res->numRows() . ' rows' );
			foreach ( $res as $row ) {
				$this->moveTextRow( $row->bt_text_id, $row->bt_new_url );
				if ( $row->bt_text_id % 10 == 0 ) {
					$this->waitForSlaves();
				}
			}
			$startId = $row->bt_text_id;
		}
	}

	/**
	 * Returns the name of the next target cluster
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
	 */
	function getExtDB( $cluster ) {
		$lb = wfGetLBFactory()->getExternalLB( $cluster );
		return $lb->getConnection( DB_MASTER );
	}

	/**
	 * Move an orphan text_id to the new cluster
	 */
	function doOrphanList( $textIds ) {
		// Finish incomplete moves
		if ( !$this->copyOnly ) {
			$this->finishIncompleteMoves( array( 'bt_text_id' => $textIds ) );
			$this->syncDBs();
		}

		$trx = new CgzCopyTransaction( $this, $this->orphanBlobClass );

		$res = wfGetDB( DB_SLAVE )->select(
			array( 'text', 'blob_tracking' ),
			array( 'old_id', 'old_text', 'old_flags' ),
			array(
				'old_id' => $textIds,
				'bt_text_id=old_id',
				'bt_moved' => 0,
			),
			__METHOD__,
			array( 'DISTINCT' )
		);

		foreach ( $res as $row ) {
			$text = Revision::getRevisionText( $row );
			if ( $text === false ) {
				$this->critical( "Error: cannot load revision text for old_id={$row->old_id}" );
				continue;
			}

			if ( !$trx->addItem( $text, $row->old_id ) ) {
				$this->debug( "[orphan]: committing blob with " . $trx->getSize() . " rows" );
				$trx->commit();
				$trx = new CgzCopyTransaction( $this, $this->orphanBlobClass );
				$this->waitForSlaves();
			}
		}
		$this->debug( "[orphan]: committing blob with " . $trx->getSize() . " rows" );
		$trx->commit();
	}

	/**
	 * Wait for slaves (quietly)
	 */
	function waitForSlaves() {
		$lb = wfGetLB();
		while ( true ) {
			list( $host, $maxLag ) = $lb->getMaxLag();
			if ( $maxLag < 2 ) {
				break;
			}
			sleep( 5 );
		}
	}
}

/**
 * Class to represent a recompression operation for a single CGZ blob
 */
class CgzCopyTransaction {
	var $parent;
	var $blobClass;
	var $cgz;
	var $referrers;

	/**
	 * Create a transaction from a RecompressTracked object
	 */
	function __construct( $parent, $blobClass ) {
		$this->blobClass = $blobClass;
		$this->cgz = false;
		$this->texts = array();
		$this->parent = $parent;
	}

	/**
	 * Add text.
	 * Returns false if it's ready to commit.
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
		$this->referrers = array();
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

		// Check to see if the target text_ids have been moved already.
		//
		// We originally read from the slave, so this can happen when a single
		// text_id is shared between multiple pages. It's rare, but possible
		// if a delete/move/undelete cycle splits up a null edit.
		//
		// We do a locking read to prevent closer-run race conditions.
		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin();
		$res = $dbw->select( 'blob_tracking',
			array( 'bt_text_id', 'bt_moved' ),
			array( 'bt_text_id' => array_keys( $this->referrers ) ),
			__METHOD__, array( 'FOR UPDATE' ) );
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
					$this->critical( "Warning: concurrent operation detected, are there two conflicting " .
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
		$targetDB->begin();
		$baseUrl = $this->parent->store->store( $targetCluster, serialize( $this->cgz ) );

		// Write the new URLs to the blob_tracking table
		foreach ( $this->referrers as $textId => $hash ) {
			$url = $baseUrl . '/' . $hash;
			$dbw->update( 'blob_tracking',
				array( 'bt_new_url' => $url ),
				array(
					'bt_text_id' => $textId,
					'bt_moved' => 0, # Check for concurrent conflicting update
				),
				__METHOD__
			);
		}

		$targetDB->commit();
		// Critical section here: interruption at this point causes blob duplication
		// Reversing the order of the commits would cause data loss instead
		$dbw->commit();

		// Write the new URLs to the text table and set the moved flag
		if ( !$this->parent->copyOnly ) {
			foreach ( $this->referrers as $textId => $hash ) {
				$url = $baseUrl . '/' . $hash;
				$this->parent->moveTextRow( $textId, $url );
			}
		}
	}
}

