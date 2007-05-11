<?php

$optionsWithArgs = array( 'maxjobs' );
$wgUseNormalUser = true;
require_once( 'commandLine.inc' );
require_once( "$IP/includes/JobQueue.php" );
require_once( "$IP/includes/FakeTitle.php" );

if ( isset( $options['maxjobs'] ) ) {
	$maxJobs = $options['maxjobs'];
} else {
	$maxJobs = 10000;
}

$type = false;
if ( isset( $options['type'] ) )
	$type = $options['type'];

$wgTitle = Title::newFromText( 'RunJobs.php' );

$dbw = wfGetDB( DB_MASTER );
$n = 0;
$conds = array();
if ($type !== false)
	$conds = array('job_cmd' => $type);

while ( $dbw->selectField( 'job', 'count(*)', $conds, 'runJobs.php' ) ) {
	$offset=0;
	for (;;) {
		$job = ($type == false) ?
				Job::pop($offset, $type)
				: Job::pop_type($type);

		if ($job == false)
			break;

		wfWaitForSlaves( 5 );
		print $job->id . "  " . $job->toString() . "\n";
		$offset=$job->id;
		if ( !$job->run() ) {
			print "Error: {$job->error}\n";
		}
		if ( $maxJobs && ++$n > $maxJobs ) {
			break 2;
		}
	}
}
?>
