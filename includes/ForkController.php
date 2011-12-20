<?php

/**
 * Class for managing forking command line scripts.
 * Currently just does forking and process control, but it could easily be extended
 * to provide IPC and job dispatch.
 *
 * This class requires the posix and pcntl extensions.
 *
 * @ingroup Maintenance
 */
class ForkController {
	var $children = array();
	var $termReceived = false;
	var $flags = 0, $procsToStart = 0;

	static $restartableSignals = array(
		SIGFPE,
		SIGILL,
		SIGSEGV,
		SIGBUS,
		SIGABRT,
		SIGSYS,
		SIGPIPE,
		SIGXCPU,
		SIGXFSZ,
	);

	/**
	 * Pass this flag to __construct() to cause the class to automatically restart
	 * workers that exit with non-zero exit status or a signal such as SIGSEGV.
	 */
	const RESTART_ON_ERROR = 1;

	public function __construct( $numProcs, $flags = 0 ) {
		if ( php_sapi_name() != 'cli' ) {
			throw new MWException( "ForkController cannot be used from the web." );
		}
		$this->procsToStart = $numProcs;
		$this->flags = $flags;
	}

	/**
	 * Start the child processes.
	 *
	 * This should only be called from the command line. It should be called
	 * as early as possible during execution.
	 *
	 * This will return 'child' in the child processes. In the parent process,
	 * it will run until all the child processes exit or a TERM signal is
	 * received. It will then return 'done'.
	 */
	public function start() {
		// Trap SIGTERM
		pcntl_signal( SIGTERM, array( $this, 'handleTermSignal' ), false );

		do {
			// Start child processes
			if ( $this->procsToStart ) {
				if ( $this->forkWorkers( $this->procsToStart ) == 'child' ) {
					return 'child';
				}
				$this->procsToStart = 0;
			}

			// Check child status
			$status = false;
			$deadPid = pcntl_wait( $status );

			if ( $deadPid > 0 ) {
				// Respond to child process termination
				unset( $this->children[$deadPid] );
				if ( $this->flags & self::RESTART_ON_ERROR ) {
					if ( pcntl_wifsignaled( $status ) ) {
						// Restart if the signal was abnormal termination
						// Don't restart if it was deliberately killed
						$signal = pcntl_wtermsig( $status );
						if ( in_array( $signal, self::$restartableSignals ) ) {
							echo "Worker exited with signal $signal, restarting\n";
							$this->procsToStart++;
						}
					} elseif ( pcntl_wifexited( $status ) ) {
						// Restart on non-zero exit status
						$exitStatus = pcntl_wexitstatus( $status );
						if ( $exitStatus != 0 ) {
							echo "Worker exited with status $exitStatus, restarting\n";
							$this->procsToStart++;
						} else {
							echo "Worker exited normally\n";
						}
					}
				}
				// Throttle restarts
				if ( $this->procsToStart ) {
					usleep( 500000 );
				}
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
		return 'done';
	}

	protected function prepareEnvironment() {
		global $wgMemc;
		// Don't share DB or memcached connections
		wfGetLBFactory()->destroyInstance();
		ObjectCache::clear();
		$wgMemc = null;
	}

	/**
	 * Fork a number of worker processes.
	 *
	 * @return string
	 */
	protected function forkWorkers( $numProcs ) {
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
				return 'child';
			} else {
				// This is the parent process
				$this->children[$pid] = true;
			}
		}

		return 'parent';
	}

	protected function initChild() {
		global $wgMemc, $wgMainCacheType;
		$wgMemc = wfGetCache( $wgMainCacheType );
		$this->children = null;
		pcntl_signal( SIGTERM, SIG_DFL );
	}

	protected function handleTermSignal( $signal ) {
		$this->termReceived = true;
	}
}
