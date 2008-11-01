<?php

$optionsWithArgs = RecompressTracked::getOptionsWithArgs();
require( dirname( __FILE__ ) .'/../commandLine.inc' );

if ( count( $args ) < 1 ) {
	echo "Usage: php recompressTracked.php [options] <cluster> [... <cluster>...]
Moves blobs indexed by trackBlobs.php to a specified list of destination clusters, and recompresses them in the process. Restartable.

Options: 
    --procs <procs>     Set the number of child processes (default 8)
    --copy-only         Copy only, do not update the text table. Restart without this option to complete.
";
	exit( 1 );
}

$job = RecompressTracked::newFromCommandLine( $args, $options );
$job->execute();

class RecompressTracked {
	var $destClusters;
	var $batchSize = 1000;
	var $reportingInterval = 10;
	var $numProcs = 8;
	var $useDiff, $pageBlobClass, $orphanBlobClass;
	var $slavePipes, $slaveProcs, $prevSlaveId;
	var $copyOnly = false;
	var $isChild = false;
	var $slaveId = false;
	var $store;

	static $optionsWithArgs = array( 'procs', 'slave-id' );
	static $cmdLineOptionMap = array(
		'procs' => 'numProcs',
		'copy-only' => 'copyOnly',
		'child' => 'isChild',
		'slave-id' => 'slaveId',
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
			echo "Error: blob_tracking table does not exist\n";
			return false;
		}
		$row = $dbr->selectRow( 'blob_tracking', '*', false, __METHOD__ );
		if ( !$row ) {
			echo "Warning: blob_tracking table contains no rows, skipping this wiki.\n";
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
			if ( in_array( $cmdOption, self::$optionsWithArgs ) ) {
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
				array( 'file', '/dev/stdout', 'w' ),
				array( 'file', '/dev/stderr', 'w' )
			);
			wfSuppressWarnings();
			$proc = proc_open( "$cmd --slave-id $i", $spec, $pipes );
			wfRestoreWarnings();
			if ( !$proc ) {
				echo "Error opening slave process\n";
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
		for ( $i = 0; $i < $this->numProcs; $i++ ) {
			$this->dispatchToSlave( $i, 'quit' );
		}
		for ( $i = 0; $i < $this->numProcs; $i++ ) {
			proc_close( $this->slaveProcs[$i] );
		}
	}

	/**
	 * Dispatch a command to the next available slave.
	 * This may block until a slave finishes its work and becomes available.
	 */
	function dispatch( /*...*/ ) {
		$args = func_get_args();
		$pipes = $this->slavePipes;
		$numPipes = stream_select( $x=array(), $pipes, $y=array(), 3600 );
		if ( !$numPipes ) {
			echo "Error waiting to write to slaves. Aborting\n";
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
		echo "Unreachable\n";
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
		$startId = 0;
		$endId = $dbr->selectField( 'blob_tracking', 'MAX(bt_page)', 
			# A condition is required so that this query uses the index
			array( 'bt_moved' => 0 ),
			__METHOD__ );
		echo "Moving pages...\n";
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
			}
			$startId = $row->bt_page;
			$this->report( $startId, $endId );
		}
		echo "Done moving pages.\n";
	}

	/**
	 * Display a progress report
	 */
	function report( $start, $end ) {
		$this->numBatches++;
		if ( $this->numBatches >= $this->reportingInterval ) {
			$this->numBatches = 0;
			echo "$start / $end\n";
			wfWaitForSlaves( 5 );
		}
	}

	/**
	 * Move all orphan text to the new clusters
	 */
	function doAllOrphans() {
		$dbr = wfGetDB( DB_SLAVE );
		$startId = 0;
		$endId = $dbr->selectField( 'blob_tracking', 'MAX(bt_text_id)', 
			array( 'bt_moved' => 0, 'bt_page' => 0 ),
			__METHOD__ );
		if ( !$endId ) {
			return;
		}
		echo "Moving orphans...\n";

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
			$args = array( 'doOrphanList' );
			foreach ( $res as $row ) {
				$args[] = $row->bt_text_id;
			}
			call_user_func_array( array( $this, 'dispatch' ), $args );
			$startId = $row->bt_text_id;
			$this->report( $startId, $endId );
		}
		echo "Done moving orphans.\n";
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
					echo "Error loading {$row->bt_rev_id}/{$row->bt_text_id}\n";
					continue;
				}

				// Queue it
				if ( !$trx->addItem( $text, $row->bt_text_id ) ) {
					$this->debug( "$titleText: committing blob with " . $trx->getSize() . " items" );
					$trx->commit();
					$trx = new CgzCopyTransaction( $this, $this->pageBlobClass );
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
		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin();
		$dbw->update( 'text',
			array( // set
				'old_text' => $url,
				'old_flags' => 'external,utf8',
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
		));
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
		$this->finishIncompleteMoves( array( 'bt_text_id' => $textIds ) );
		
		$trx = new CgzCopyTransaction( $this, $this->orphanBlobClass );
		foreach ( $textIds as $textId ) {
			$row = wfGetDB( DB_SLAVE )->selectRow( 'text', array( 'old_text', 'old_flags' ), 
				array( 'old_id' => $textId ), __METHOD__ );
			$text = Revision::getRevisionText( $row );
			if ( $text === false ) {
				echo "Error: cannot load revision text for $textId\n";
				continue;
			}
			
			if ( !$trx->addItem( $text, $textId ) ) {
				$this->debug( "[orphan]: committing blob with " . $trx->getSize() . " rows" );
				$trx->commit();
				$trx = new CgzCopyTransaction( $this, $this->orphanBlobClass );
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
					echo "Warning: concurrent operation detected, are there two conflicting " .
						"processes running, doing the same job?\n";
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

