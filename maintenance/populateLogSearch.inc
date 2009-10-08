<?php
/**
 * Makes the required database updates for log display in Special:RevisionDelete
 *
 * Run via update.php or directly through populateLogSearch.php
 *
 * @file
 * @ingroup Maintenance
 */

define( 'LOG_SEARCH_BATCH_SIZE', 300 );

function migrate_log_params( $db ) {
	$start = $db->selectField( 'logging', 'MIN(log_id)', false, __FUNCTION__ );
	if( !$start ) {
		echo "Nothing to do.\n";
		return true;
	}
	$end = $db->selectField( 'logging', 'MAX(log_id)', false, __FUNCTION__ );
	
	# Do remaining chunk
	$end += LOG_SEARCH_BATCH_SIZE - 1;
	$blockStart = $start;
	$blockEnd = $start + LOG_SEARCH_BATCH_SIZE - 1;
	while( $blockEnd <= $end ) {
		echo "...doing log_id from $blockStart to $blockEnd\n";
		$cond = array("log_id BETWEEN $blockStart AND $blockEnd");
		# Applicable log types
		$cond['log_type'] = array('delete','suppress');
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
						echo "Invalid param type for $row->log_id\n";
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
		$blockStart += LOG_SEARCH_BATCH_SIZE;
		$blockEnd += LOG_SEARCH_BATCH_SIZE;
		wfWaitForSlaves( 5 );
	}
	if( $db->insert(
			'updatelog',
			array( 'ul_key' => 'populate log_search' ),
			__FUNCTION__,
			'IGNORE'
		)
	) {
		wfOut( "log_search population complete.\n" );
		return true;
	} else {
		wfOut( "Could not insert log_search population row.\n" );
		return false;
	}
}
