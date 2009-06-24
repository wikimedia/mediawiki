<?php
/**
 * Script for periodic off-peak updating of the search index
 *
 * Usage: php updateSearchIndex.php [-s START] [-e END] [-p POSFILE] [-l LOCKTIME] [-q]
 * Where START is the starting timestamp
 * END is the ending timestamp
 * POSFILE is a file to load timestamps from and save them to, searchUpdate.WIKI_ID.pos by default
 * LOCKTIME is how long the searchindex and revision tables will be locked for
 * -q means quiet
 *
 * @ingroup Maintenance
 */
 
require_once( "Maintenance.php" );

class UpdateSearchIndex extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Script for periodic off-peak updating of the search index";
		$this->addParam( 's', 'starting timestamp', false, true );
		$this->addParam( 'e', 'Ending timestamp', false, true );
		$this->addParam( 'p', 'File for saving/loading timestamps, searchUpdate.WIKI_ID.pos by default', false, true );
		$this->addParam( 'l', 'How long the searchindex and revision tables will be locked for', false, true );
	}

	public function execute() {
		$posFile = $this->getOption( 'p', 'searchUpdate.' . wfWikiId() . '.pos' );
		$end = $this->getOption( 'e', wfTimestampNow() );
		if ( $this->hasOption( 's' ) ) {
			$start = $this->getOption('s');
		} elseif( is_readable( 'searchUpdate.pos' ) ) {
			# B/c to the old position file name which was hardcoded
			# We can safely delete the file when we're done though.
			$start = file_get_contents( 'searchUpdate.pos' );
			unlink( 'searchUpdate.pos' );
		} else {
			$start = @file_get_contents( $posFile );
			if ( !$start ) {
				$start = wfTimestamp( TS_MW, time() - 86400 );
			}
		}
		$lockTime = $this->getOption( 'l', 20 );
		
		$this->updateSearchIndex( $start, $end, $lockTime );
		$file = fopen( $posFile, 'w' );
		fwrite( $file, $end );
		fclose( $file );
	}
	
	private function updateSearchIndex( $start, $end, $maxLockTime ) {
		global $wgDisableSearchUpdate;

		$wgDisableSearchUpdate = false;

		$dbw = wfGetDB( DB_MASTER );
		$recentchanges = $dbw->tableName( 'recentchanges' );

		$this->output( "Updating searchindex between $start and $end\n" );

		# Select entries from recentchanges which are on top and between the specified times
		$start = $dbw->strencode( $start );
		$end = $dbw->strencode( $end );

		$page = $dbw->tableName( 'page' );
		$sql = "SELECT rc_cur_id,rc_type,rc_moved_to_ns,rc_moved_to_title FROM $recentchanges
		  JOIN $page ON rc_cur_id=page_id AND rc_this_oldid=page_latest
		  WHERE rc_timestamp BETWEEN '$start' AND '$end'
		  ";
		$res = $dbw->query( $sql, __METHOD__ );


		# Lock searchindex
		if ( $maxLockTime ) {
			$this->output( "   --- Waiting for lock ---" );
			$this->lockSearchindex( $dbw );
			$lockTime = time();
			$this->output( "\n" );
		}

		# Loop through the results and do a search update
		while ( $row = $dbw->fetchObject( $res ) ) {
			# Allow reads to be processed
			if ( $maxLockTime && time() > $lockTime + $maxLockTime ) {
				$this->output( "    --- Relocking ---" );
				$this->relockSearchindex( $dbw );
				$lockTime = time();
				$this->output( "\n" );
			}
			if ( $row->rc_type == RC_LOG ) {
				continue;
			} elseif ( $row->rc_type == RC_MOVE || $row->rc_type == RC_MOVE_OVER_REDIRECT ) {
				# Rename searchindex entry
				$titleObj = Title::makeTitle( $row->rc_moved_to_ns, $row->rc_moved_to_title );
				$title = $titleObj->getPrefixedDBkey();
				$this->output( "$title..." );
				$u = new SearchUpdate( $row->rc_cur_id, $title, false );
				$this->output( "\n" );
			} else {
				// Get current revision
				$rev = Revision::loadFromPageId( $dbw, $row->rc_cur_id );
				if( $rev ) {
					$titleObj = $rev->getTitle();
					$title = $titleObj->getPrefixedDBkey();
					$this->output( $title );
					# Update searchindex
					$u = new SearchUpdate( $row->rc_cur_id, $titleObj->getText(), $rev->getText() );
					$u->doUpdate();
					$this->output( "\n" );
				}
			}
		}

		# Unlock searchindex
		if ( $maxLockTime ) {
			$this->output( "    --- Unlocking --" );
			$this->unlockSearchindex( $dbw );
			$this->output( "\n" );
		}
		$this->output( "Done\n" );
	}

	/**
	 * Lock the search index
	 * @param &$db Database object
	 */
	private function lockSearchindex( &$db ) {
		$write = array( 'searchindex' );
		$read = array( 'page', 'revision', 'text', 'interwiki' );
		$items = array();
	
		foreach( $write as $table ) {
			$items[] = $db->tableName( $table ) . ' LOW_PRIORITY WRITE';
		}
		foreach( $read as $table ) {
			$items[] = $db->tableName( $table ) . ' READ';
		}
		$sql = "LOCK TABLES " . implode( ',', $items );
		$db->query( $sql, 'updateSearchIndex.php ' . __METHOD__ );
	}

	/**
	 * Unlock the tables
	 * @param &$db Database object
	 */
	private function unlockSearchindex( &$db ) {
		$db->query( "UNLOCK TABLES", 'updateSearchIndex.php ' . __METHOD__ );
	}
	
	/**
	 * Unlock and lock again
	 * Since the lock is low-priority, queued reads will be able to complete
	 * @param &$db Database object
	 */
	private function relockSearchindex( &$db ) {
		$this->unlockSearchindex( $db );
		$this->lockSearchindex( $db );
	}
}

$maintClass = "UpdateSearchIndex";
require_once( DO_MAINTENANCE );
