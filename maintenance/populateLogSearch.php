<?php
/**
 * Makes the required database updates for Special:ProtectedPages
 * to show all protected pages, even ones before the page restrictions
 * schema change. All remaining page_restriction column values are moved
 * to the new table.
 *
 * @file
 * @ingroup Maintenance
 */

define( 'BATCH_SIZE', 100 );

require_once 'commandLine.inc';
	
$db =& wfGetDB( DB_MASTER );
if ( !$db->tableExists( 'log_search' ) ) {
	echo "log_search does not exist\n";
	exit( 1 );
}

migrate_log_params( $db );

function migrate_log_params( $db ) {
	$start = $db->selectField( 'logging', 'MIN(log_id)', false, __FUNCTION__ );
	if( !$start ) {
		die("Nothing to do.\n");
	}
	$end = $db->selectField( 'logging', 'MAX(log_id)', false, __FUNCTION__ );
	
	# Do remaining chunk
	$end += BATCH_SIZE - 1;
	$blockStart = $start;
	$blockEnd = $start + BATCH_SIZE - 1;
	while( $blockEnd <= $end ) {
		echo "...doing log_id from $blockStart to $blockEnd\n";
		$cond = "log_id BETWEEN $blockStart AND $blockEnd";
		$res = $db->select( 'logging', '*', $cond, __FUNCTION__ );
		$batch = array();
		while( $row = $db->fetchObject( $res ) ) {
			// RevisionDelete logs - revisions
			if( LogEventsList::typeAction( $row, array('delete','suppress'), 'revision' ) ) {
				$params = LogPage::extractParams( $row->log_params );
				// Param format: <urlparam> <item CSV> [<ofield> <nfield>]
				if( count($params) >= 2 ) {
					$field = RevisionDeleter::getRelationType($params[0]);
					// B/C, the params may start with a title key
					if( $field == null ) {
						array_shift($params);
						$field = RevisionDeleter::getRelationType($params[0]);
					}
					if( $field == null ) {
						echo "Invalid param type for $row->log_id";
						continue; // skip this row
					}
					$items = explode(',',$params[1]);
					$log = new LogPage( $row->log_type );
					$log->addRelations( $field, $items, $row->log_id );
				}
			// RevisionDelete logs - log events
			} else if( LogEventsList::typeAction( $row, array('delete','suppress'), 'event' ) ) {
				$params = LogPage::extractParams( $row->log_params );
				// Param format: <item CSV> [<ofield> <nfield>]
				if( count($params) >= 1 ) {
					$items = explode(',',$params[0]);
					$log = new LogPage( $row->log_type );
					$log->addRelations( 'log_id', $items, $row->log_id );
				}
			}
		}
		$blockStart += BATCH_SIZE - 1;
		$blockEnd += BATCH_SIZE - 1;
		wfWaitForSlaves( 5 );
	}
	echo "...Done!\n";
}
