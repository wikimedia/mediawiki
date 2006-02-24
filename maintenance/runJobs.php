<?php

require_once( 'commandLine.inc' );
require_once( "$IP/includes/JobQueue.php" );

while ( false != ($job = Job::pop()) ) {
	wfWaitForSlaves( 5 );
	print $job->toString() . "\n";
	if ( !$job->run() ) {
		print "Error: {$job->error}\n";
	}
}

