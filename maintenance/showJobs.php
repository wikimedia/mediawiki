<?php
/**
 * Based on runJobs.php
 *
 * @file
 * @ingroup Maintenance
 * @author Tim Starling
 * @author Ashar Voultoiz
 */
require_once( 'commandLine.inc' );
require_once( "$IP/includes/JobQueue.php" );
require_once( "$IP/includes/FakeTitle.php" );

// Trigger errors on inappropriate use of $wgTitle
$wgTitle = new FakeTitle;

$dbw = wfGetDB( DB_MASTER );
$count = $dbw->selectField( 'job', 'count(*)', '', 'runJobs.php' );
print $count."\n";


