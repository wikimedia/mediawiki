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

require_once 'commandLine.inc';
require_once 'populateLogSearch.inc';
	
$db =& wfGetDB( DB_MASTER );
if ( !$db->tableExists( 'log_search' ) ) {
	echo "log_search does not exist\n";
	exit( 1 );
}

migrate_log_params( $db );
