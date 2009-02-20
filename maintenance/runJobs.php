<?php
/**
 * This script starts pending jobs.
 *
 * Usage:
 *  --maxjobs <num> (default 10000)
 *  --type <job_cmd>
 *
 * @file
 * @ingroup Maintenance
 */

$optionsWithArgs = array( 'maxjobs', 'type', 'procs' );
$wgUseNormalUser = true;
require_once( 'commandLine.inc' );

if ( isset( $options['procs'] ) ) {
	$procs = intval( $options['procs'] );
	if ( $procs < 1 || $procs > 1000 ) {
		echo "Invalid argument to --procs\n";
		exit( 1 );
	}

	// Don't share DB or memcached connections
	$lb = wfGetLB();
	$lb->closeAll();
	$wgCaches = array();
	unset( $wgMemc );

	// Create the child processes
	$children = array();
	for ( $childId = 0; $childId < $procs; $childId++ ) {
		$pid = pcntl_fork();
		if ( $pid === -1 || $pid === false ) {
			echo "Error creating child processes\n";
			exit( 1 );
		}
		if ( !$pid ) {
			break;
		}

		$children[$pid] = true;
	}
	if ( $pid ) {
		// Parent process
		// Trap SIGTERM
		pcntl_signal( SIGTERM, 'handleTermSignal', false );
		// Wait for a child to exit
		$status = false;
		$termReceived = false;
		do {
			$deadPid = pcntl_wait( $status );
			// Run signal handlers
			if ( function_exists( 'pcntl_signal_dispatch' ) ) {
				pcntl_signal_dispatch();
			} else {
				declare (ticks=1) { $status = $status; } 
			}
			if ( $deadPid > 0 ) {
				unset( $children[$deadPid] );
			}
			// Respond to TERM signal
			if ( $termReceived ) {
				foreach ( $children as $childPid => $unused ) {
					posix_kill( $childPid, SIGTERM );
				}
				$termReceived = false;
			}
		} while ( count( $children ) );
		// All done
		exit( 0 );
	}

	// Set up this child
	$wgMemc = wfGetCache( $wgMainCacheType );
}

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
$conds = '';
if ($type !== false)
	$conds = "job_cmd = " . $dbw->addQuotes($type);

while ( $dbw->selectField( 'job', 'job_id', $conds, 'runJobs.php' ) ) {
	$offset=0;
	for (;;) {
		$job = ($type == false) ?
				Job::pop($offset)
				: Job::pop_type($type);

		if ($job == false)
			break;

		wfWaitForSlaves( 5 );
		$t = microtime( true );
		$offset=$job->id;
		$status = $job->run();
		$t = microtime( true ) - $t;
		$timeMs = intval( $t * 1000 );
		if ( !$job->run() ) {
			runJobsLog( $job->toString() . " t=$timeMs error={$job->error}" );
		} else {
			runJobsLog( $job->toString() . " t=$timeMs good" );
		}
		if ( $maxJobs && ++$n > $maxJobs ) {
			break 2;
		}
	}
}


function runJobsLog( $msg ) {
	print wfTimestamp( TS_DB ) . " $msg\n";
	wfDebugLog( 'runJobs', $msg );
}

function handleTermSignal( $signal ) {
	$GLOBALS['termReceived'] = true;
}

