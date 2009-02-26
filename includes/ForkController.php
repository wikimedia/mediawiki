<?php

/**
 * Class for managing forking command line scripts.
 * Currently just does forking and process control, but it could easily be extended 
 * to provide IPC and job dispatch.
 */
class ForkController {
	var $children = array();
	var $termReceived = false;

	public function __construct() {
		if ( php_sapi_name() != 'cli' ) {
			throw new MWException( "MultiProcess cannot be used from the web." );
		}
	}

	protected function prepareEnvironment() {
		global $wgCaches, $wgMemc;
		// Don't share DB or memcached connections
		wfGetLBFactory()->destroyInstance();
		$wgCaches = array();
		unset( $wgMemc );
	}

	/**
	 * Fork a number of worker processes.
	 *
	 * This should only be called from the command line. It should be called 
	 * as early as possible during execution. It will return 'child' in the 
	 * child processes and 'parent' in the parent process. The parent process
	 * should then call monitor(). 
	 *
	 * This function requires the posix and pcntl extensions.
	 */
	public function forkWorkers( $numProcs ) {
		global $wgMemc, $wgCaches, $wgMainCacheType;
	
		$this->prepareEnvironment();

		// Create the child processes
		for ( $i = 0; $i < $numProcs; $i++ ) {
			// Do the fork
			$pid = pcntl_fork();
			if ( $pid === -1 || $pid === false ) {
				echo "Error creating child processes\n";
				exit( 1 );
			}

			if ( !$pid ) {
				$this->initChild();
				$this->children = null;
				return 'child';
			} else {
				// This is the parent process
				$this->children[$pid] = true;
			}
		}

		return 'parent';
	}

	/**
	 * The parent process main loop
	 */
	public function runParent() {
		// Trap SIGTERM
		pcntl_signal( SIGTERM, array( $this, 'handleTermSignal' ), false );

		do {
			$status = false;
			$deadPid = pcntl_wait( $status );

			if ( $deadPid > 0 ) {
				unset( $this->children[$deadPid] );
			}

			// Run signal handlers
			if ( function_exists( 'pcntl_signal_dispatch' ) ) {
				pcntl_signal_dispatch();
			} else {
				declare (ticks=1) { $status = $status; } 
			}
			// Respond to TERM signal
			if ( $this->termReceived ) {
				foreach ( $this->children as $childPid => $unused ) {
					posix_kill( $childPid, SIGTERM );
				}
				$this->termReceived = false;
			}
		} while ( count( $this->children ) );
		pcntl_signal( SIGTERM, SIG_DFL );
	}

	protected function initChild() {
		global $wgMemc, $wgMainCacheType;
		$wgMemc = wfGetCache( $wgMainCacheType );
	}

	protected function handleTermSignal( $signal ) {
		$this->termReceived = true;
	}
}
