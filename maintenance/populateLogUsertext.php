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
require_once 'populateLogUsertext.inc';
	
$db =& wfGetDB( DB_MASTER );

populate_logusertext( $db );
