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

require_once( "Maintenance.php" );

class RunJobs extends Maintenance {
	public function __construct() {
		global $wgUseNormalUser;
		parent::__construct();
		$this->mDescription = "Run pending jobs";
		$this->addParam( 'maxjobs', 'Maximum number of jobs to run', false, true );
		$this->addParam( 'type', 'Type of job to run', false, true );
		$this->addParam( 'procs', 'Number of processes to use', false, true );
		$wgUseNormalUser = true;
	}

	public function execute() {
		global $wgTitle;
		if ( $this->hasOption( 'procs' ) ) {
			$procs = intval( $this->getOption('procs') );
			if ( $procs < 1 || $procs > 1000 ) {
				$this->error( "Invalid argument to --procs\n", true );
			}
			$fc = new ForkController( $procs );
			if ( $fc->start( $procs ) != 'child' ) {
				exit( 0 );
			}
		}
		$maxJobs = $this->getOption( 'maxjobs', 10000 );
		$type = $this->getOption( 'type', false );
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
				if ( !$status ) {
					$this->runJobsLog( $job->toString() . " t=$timeMs error={$job->error}" );
				} else {
					$this->runJobsLog( $job->toString() . " t=$timeMs good" );
				}
				if ( $maxJobs && ++$n > $maxJobs ) {
					break 2;
				}
			}
		}
	}

	/**
	 * Log the job message
	 * @param $msg String The message to log
	 */
	private function runJobsLog( $msg ) {
		$this->output( wfTimestamp( TS_DB ) . " $msg\n" );
		wfDebugLog( 'runJobs', $msg );
	}
}

$maintClass = "RunJobs";
require_once( DO_MAINTENANCE );
