<?php
/**
 * Based on runJobs.php
 *
 * Report number of jobs currently waiting in master database.
 *
 * @file
 * @ingroup Maintenance
 * @author Tim Starling
 * @author Ashar Voultoiz
 */
require_once( 'commandLine.inc' );

$dbw = wfGetDB( DB_MASTER );
$count = $dbw->selectField( 'job', 'count(*)', '', 'runJobs.php' );
print $count."\n";


