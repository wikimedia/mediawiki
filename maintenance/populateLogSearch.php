<?php
/**
 * Makes the required database updates for populating the
 * log_search table retroactively
 *
 * @file
 * @ingroup Maintenance
 */

require_once 'commandLine.inc';
require_once 'populateLogSearch.inc';
	
$db =& wfGetDB( DB_MASTER );
if ( !$db->tableExists( 'log_search' ) ) {
	echo "log_search does not exist\n";
	exit( 1 );
}

migrate_log_params( $db );
