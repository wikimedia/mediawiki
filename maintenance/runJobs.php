<?php

require_once( 'commandLine.inc' );
require_once( "$IP/includes/JobQueue.php" );
require_once( "$IP/includes/FakeTitle.php" );

// Trigger errors on inappropriate use of $wgTitle
$wgTitle = new FakeTitle;

$dbw =& wfGetDB( DB_MASTER );
while ( $dbw->selectField( 'job', 'count(*)', '', 'runJobs.php' ) ) {
	while ( false != ($job = Job::pop()) ) {
		wfWaitForSlaves( 5 );
		print $job->id . "  " . $job->toString() . "\n";
		if ( !$job->run() ) {
			print "Error: {$job->error}\n";
		}
	}
}
?>
