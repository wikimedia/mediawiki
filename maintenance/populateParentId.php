<?php
/*
 * Makes the required database updates for rev_parent_id
 * to be of any use. It can be used for some simple tracking
 * and to find new page edits by users.
 */

require_once( "Maintenance.php" );

class PopulateParentId extends Maintenance {

	// Batch size
	const BATCH_SIZE = 200;

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Populates rev_parent_id";
	}

	public function execute() {
		$db = wfGetDB( DB_MASTER );
		if ( !$db->tableExists( 'revision' ) ) {
			$this->error( "revision table does not exist\n", true );
		}
		$this->output( "Populating rev_parent_id column\n" );
		$start = $db->selectField( 'revision', 'MIN(rev_id)', false, __FUNCTION__ );
		$end = $db->selectField( 'revision', 'MAX(rev_id)', false, __FUNCTION__ );
		if( is_null( $start ) || is_null( $end ) ){
			$this->output( "...revision table seems to be empty.\n" );
			$db->insert( 'updatelog',
				array( 'ul_key' => 'populate rev_parent_id' ),
				__FUNCTION__,
				'IGNORE' );
			return;
		}
		# Do remaining chunk
		$end += self::BATCH_SIZE - 1;
		$blockStart = intval( $start );
		$blockEnd = intval( $start ) + self::BATCH_SIZE - 1;
		$count = 0;
		$changed = 0;
		while( $blockEnd <= $end ) {
			$this->output( "...doing rev_id from $blockStart to $blockEnd\n" );
			$cond = "rev_id BETWEEN $blockStart AND $blockEnd";
			$res = $db->select( 'revision', 
				array('rev_id','rev_page','rev_timestamp','rev_parent_id'), 
				$cond, __FUNCTION__ );
			# Go through and update rev_parent_id from these rows.
			# Assume that the previous revision of the title was
			# the original previous revision of the title when the
			# edit was made...
			foreach( $res as $row ) {
				# First, check rows with the same timestamp other than this one
				# with a smaller rev ID. The highest ID "wins". This avoids loops
				# as timestamp can only decrease and never loops with IDs (from parent to parent)
				$previousID = $db->selectField( 'revision', 'rev_id', 
					array( 'rev_page' => $row->rev_page, 'rev_timestamp' => $row->rev_timestamp,
						"rev_id < " . intval( $row->rev_id ) ), 
					__FUNCTION__,
					array( 'ORDER BY' => 'rev_id DESC' ) );
				# If there are none, check the the highest ID with a lower timestamp
				if( !$previousID ) {
					# Get the highest older timestamp
					$lastTimestamp = $db->selectField( 'revision', 'rev_timestamp', 
						array( 'rev_page' => $row->rev_page, "rev_timestamp < " . $db->addQuotes( $row->rev_timestamp ) ), 
						__FUNCTION__,
						array( 'ORDER BY' => 'rev_timestamp DESC' ) );
					# If there is one, let the highest rev ID win
					if( $lastTimestamp ) {
						$previousID = $db->selectField( 'revision', 'rev_id', 
							array( 'rev_page' => $row->rev_page, 'rev_timestamp' => $lastTimestamp ), 
							__FUNCTION__,
							array( 'ORDER BY' => 'rev_id DESC' ) );
					}
				}
				$previousID = intval($previousID);
				if( $previousID != $row->rev_parent_id )
					$changed++;
				# Update the row...
				$db->update( 'revision',
					array( 'rev_parent_id' => $previousID ),
					array( 'rev_id' => $row->rev_id ),
					__FUNCTION__ );
				$count++;
			}
			$blockStart += self::BATCH_SIZE - 1;
			$blockEnd += self::BATCH_SIZE - 1;
			wfWaitForSlaves( 5 );
		}
		$logged = $db->insert( 'updatelog',
			array( 'ul_key' => 'populate rev_parent_id' ),
			__FUNCTION__,
			'IGNORE' );
		if( $logged ) {
			$this->output( "rev_parent_id population complete ... {$count} rows [{$changed} changed]\n" );
			return true;
		} else {
			$this->output( "Could not insert rev_parent_id population row.\n" );
			return false;
		}
	}
}

$maintClass = "PopulateParentId";
require_once( DO_MAINTENANCE );
