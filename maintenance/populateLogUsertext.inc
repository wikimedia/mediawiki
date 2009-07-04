<?php
/**
 * Makes the required database updates for the log_user_text column
 *
 * Run via update.php or directly through populateLogUsertext.php
 *
 * @file
 * @ingroup Maintenance
 */

define( 'LOG_USERTEXT_BATCH_SIZE', 100 );

function populate_logusertext( $db ) {
	$start = $db->selectField( 'logging', 'MIN(log_id)', false, __FUNCTION__ );
	if( !$start ) {
		echo "Nothing to do.\n";
		return true;
	}
	$end = $db->selectField( 'logging', 'MAX(log_id)', false, __FUNCTION__ );
	
	# Do remaining chunk
	$end += LOG_USERTEXT_BATCH_SIZE - 1;
	$blockStart = $start;
	$blockEnd = $start + LOG_USERTEXT_BATCH_SIZE - 1;
	while( $blockEnd <= $end ) {
		echo "...doing log_id from $blockStart to $blockEnd\n";
		$cond = "log_id BETWEEN $blockStart AND $blockEnd AND log_user = user_id";
		$res = $db->select( array('logging','user'), 
			array('log_id','user_name'), $cond, __FUNCTION__ );
		$batch = array();
		$db->begin();
		while( $row = $db->fetchObject( $res ) ) {
			$db->update( 'logging', array('log_user_text' => $row->user_name),
				array('log_id' => $row->log_id), __FUNCTION__ );
		}
		$db->commit();
		$blockStart += LOG_USERTEXT_BATCH_SIZE;
		$blockEnd += LOG_USERTEXT_BATCH_SIZE;
		wfWaitForSlaves( 5 );
	}
	if( $db->insert(
			'updatelog',
			array( 'ul_key' => 'populate log_usertext' ),
			__FUNCTION__,
			'IGNORE'
		)
	) {
		wfOut( "log_usertext population complete.\n" );
		return true;
	} else {
		wfOut( "Could not insert log_usertext population row.\n" );
		return false;
	}
}
