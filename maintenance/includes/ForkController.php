<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Maintenance;

use MediaWiki\MediaWikiServices;
use RuntimeException;
use Wikimedia\ObjectCache\RedisConnectionPool;

/**
 * Manage forking inside CLI maintenance scripts.
 *
 * Only handles forking and process control. In the future, this could
 * be extended to provide IPC and job dispatch.
 *
 * This class requires the posix and pcntl extensions.
 *
 * @ingroup Maintenance
 */
class ForkController {
	/** @var array|null */
	protected $children = [];

	/** @var int */
	protected $childNumber = 0;

	/** @var bool */
	protected $termReceived = false;

	/** @var int */
	protected $flags = 0;

	/** @var int */
	protected $procsToStart = 0;

	/** @var int[] */
	protected static $RESTARTABLE_SIGNALS = [];

	/** @var int[] */
	protected $exitStatuses = [];

	/**
	 * Pass this flag to __construct() to cause the class to automatically restart
	 * workers that exit with non-zero exit status or a signal such as SIGSEGV.
	 */
	private const RESTART_ON_ERROR = 1;

	/**
	 * @param int $numProcs The number of worker processes to fork
	 * @param int $flags
	 */
	public function __construct( $numProcs, $flags = 0 ) {
		if ( !wfIsCLI() ) {
			throw new RuntimeException( "MediaWiki\Maintenance\ForkController cannot be used from the web." );
		} elseif ( !extension_loaded( 'pcntl' ) ) {
			throw new RuntimeException(
				'MediaWiki\Maintenance\ForkController requires pcntl extension to be installed.'
			);
		} elseif ( !extension_loaded( 'posix' ) ) {
			throw new RuntimeException(
				'MediaWiki\Maintenance\ForkController requires posix extension to be installed.'
			);
		}
		$this->procsToStart = $numProcs;
		$this->flags = $flags;

		// Define this only after confirming PCNTL support
		self::$RESTARTABLE_SIGNALS = [
			SIGFPE, SIGILL, SIGSEGV, SIGBUS,
			SIGABRT, SIGSYS, SIGPIPE, SIGXCPU, SIGXFSZ,
		];
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
	 *
	 * @return string
	 */
	public function start() {
		// Trap SIGTERM
		pcntl_signal( SIGTERM, [ $this, 'handleTermSignal' ], false );

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
						if ( in_array( $signal, self::$RESTARTABLE_SIGNALS ) ) {
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

				if ( pcntl_wifexited( $status ) ) {
					$exitStatus = pcntl_wexitstatus( $status );
					echo "Worker exited with status $exitStatus\n";
					$this->exitStatuses[] = $exitStatus;
				}

				// Throttle restarts
				if ( $this->procsToStart ) {
					usleep( 500_000 );
				}
			}

			// Run signal handlers
			if ( function_exists( 'pcntl_signal_dispatch' ) ) {
				pcntl_signal_dispatch();
			} else {
				declare( ticks=1 ) {
					// @phan-suppress-next-line PhanPluginDuplicateExpressionAssignment
					$status = $status;
				}
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

	/**
	 * Return true if all completed child processes exited with an exit
	 * status / return code of 0.
	 *
	 * @return bool
	 */
	public function allSuccessful(): bool {
		return array_reduce(
			$this->exitStatuses,
			static fn ( $acc, $status ) => $acc && ( $status === 0 ),
			true
		);
	}

	/**
	 * Get the number of the child currently running. Note, this
	 * is not the pid, but rather which of the total number of children
	 * we are
	 * @return int
	 */
	public function getChildNumber() {
		return $this->childNumber;
	}

	protected function prepareEnvironment() {
		// Don't share DB, storage, or memcached connections
		MediaWikiServices::resetChildProcessServices();
		MediaWikiServices::getInstance()->getObjectCacheFactory()->clear();
		RedisConnectionPool::destroySingletons();
	}

	/**
	 * Fork a number of worker processes.
	 *
	 * @param int $numProcs
	 * @return string
	 */
	protected function forkWorkers( $numProcs ) {
		$this->prepareEnvironment();

		// Create the child processes
		for ( $i = 0; $i < $numProcs; $i++ ) {
			// Do the fork
			$pid = pcntl_fork();
			if ( $pid === -1 ) {
				echo "Error creating child processes\n";
				exit( 1 );
			}

			if ( !$pid ) {
				$this->initChild();
				$this->childNumber = $i;
				return 'child';
			} else {
				// This is the parent process
				$this->children[$pid] = true;
			}
		}

		return 'parent';
	}

	protected function initChild() {
		$this->children = null;
		pcntl_signal( SIGTERM, SIG_DFL );
	}

	protected function handleTermSignal( int $signal ) {
		$this->termReceived = true;
	}
}

/** @deprecated class alias since 1.40 */
class_alias( ForkController::class, 'ForkController' );
