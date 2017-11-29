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

namespace MediaWiki\Shell;

use Exception;
use MediaWiki\ProcOpenError;
use MediaWiki\ShellDisabledError;
use Profiler;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

/**
 * Class used for executing shell commands
 *
 * @since 1.30
 */
class Command {
	use LoggerAwareTrait;

	/** @var string */
	private $command = '';

	/** @var array */
	private $limits = [
		// seconds
		'time' => 180,
		// seconds
		'walltime' => 180,
		// KB
		'memory' => 307200,
		// KB
		'filesize' => 102400,
	];

	/** @var string[] */
	private $env = [];

	/** @var string */
	private $method;

	/** @var bool */
	private $useStderr = false;

	/** @var bool */
	private $everExecuted = false;

	/** @var string|false */
	private $cgroup = false;

	/**
	 * Constructor. Don't call directly, instead use Shell::command()
	 *
	 * @throws ShellDisabledError
	 */
	public function __construct() {
		if ( Shell::isDisabled() ) {
			throw new ShellDisabledError();
		}

		$this->setLogger( new NullLogger() );
	}

	/**
	 * Destructor. Makes sure programmer didn't forget to execute the command after all
	 */
	public function __destruct() {
		if ( !$this->everExecuted ) {
			$context = [ 'command' => $this->command ];
			$message = __CLASS__ . " was instantiated, but execute() was never called.";
			if ( $this->method ) {
				$message .= ' Calling method: {method}.';
				$context['method'] = $this->method;
			}
			$message .= ' Command: {command}';
			$this->logger->warning( $message, $context );
		}
	}

	/**
	 * Adds parameters to the command. All parameters are sanitized via Shell::escape().
	 * Null values are ignored.
	 *
	 * @param string|string[] $args,...
	 * @return $this
	 */
	public function params( /* ... */ ) {
		$args = func_get_args();
		if ( count( $args ) === 1 && is_array( reset( $args ) ) ) {
			// If only one argument has been passed, and that argument is an array,
			// treat it as a list of arguments
			$args = reset( $args );
		}
		$this->command = trim( $this->command . ' ' . Shell::escape( $args ) );

		return $this;
	}

	/**
	 * Adds unsafe parameters to the command. These parameters are NOT sanitized in any way.
	 * Null values are ignored.
	 *
	 * @param string|string[] $args,...
	 * @return $this
	 */
	public function unsafeParams( /* ... */ ) {
		$args = func_get_args();
		if ( count( $args ) === 1 && is_array( reset( $args ) ) ) {
			// If only one argument has been passed, and that argument is an array,
			// treat it as a list of arguments
			$args = reset( $args );
		}
		$args = array_filter( $args,
			function ( $value ) {
				return $value !== null;
			}
		);
		$this->command = trim( $this->command . ' ' . implode( ' ', $args ) );

		return $this;
	}

	/**
	 * Sets execution limits
	 *
	 * @param array $limits Associative array of limits. Keys (all optional):
	 *   filesize (for ulimit -f), memory, time, walltime.
	 * @return $this
	 */
	public function limits( array $limits ) {
		if ( !isset( $limits['walltime'] ) && isset( $limits['time'] ) ) {
			// Emulate the behavior of old wfShellExec() where walltime fell back on time
			// if the latter was overridden and the former wasn't
			$limits['walltime'] = $limits['time'];
		}
		$this->limits = $limits + $this->limits;

		return $this;
	}

	/**
	 * Sets environment variables which should be added to the executed command environment
	 *
	 * @param string[] $env array of variable name => value
	 * @return $this
	 */
	public function environment( array $env ) {
		$this->env = $env;

		return $this;
	}

	/**
	 * Sets calling function for profiler. By default, the caller for execute() will be used.
	 *
	 * @param string $method
	 * @return $this
	 */
	public function profileMethod( $method ) {
		$this->method = $method;

		return $this;
	}

	/**
	 * Controls whether stderr should be included in stdout, including errors from limit.sh.
	 * Default: don't include.
	 *
	 * @param bool $yesno
	 * @return $this
	 */
	public function includeStderr( $yesno = true ) {
		$this->useStderr = $yesno;

		return $this;
	}

	/**
	 * Sets cgroup for this command
	 *
	 * @param string|false $cgroup Absolute file path to the cgroup, or false to not use a cgroup
	 * @return $this
	 */
	public function cgroup( $cgroup ) {
		$this->cgroup = $cgroup;

		return $this;
	}

	/**
	 * Executes command. Afterwards, getExitCode() and getOutput() can be used to access execution
	 * results.
	 *
	 * @return Result
	 * @throws Exception
	 * @throws ProcOpenError
	 * @throws ShellDisabledError
	 */
	public function execute() {
		$this->everExecuted = true;

		$profileMethod = $this->method ?: wfGetCaller();

		$envcmd = '';
		foreach ( $this->env as $k => $v ) {
			if ( wfIsWindows() ) {
				/* Surrounding a set in quotes (method used by wfEscapeShellArg) makes the quotes themselves
				 * appear in the environment variable, so we must use carat escaping as documented in
				 * https://technet.microsoft.com/en-us/library/cc723564.aspx
				 * Note however that the quote isn't listed there, but is needed, and the parentheses
				 * are listed there but doesn't appear to need it.
				 */
				$envcmd .= "set $k=" . preg_replace( '/([&|()<>^"])/', '^\\1', $v ) . '&& ';
			} else {
				/* Assume this is a POSIX shell, thus required to accept variable assignments before the command
				 * http://www.opengroup.org/onlinepubs/009695399/utilities/xcu_chap02.html#tag_02_09_01
				 */
				$envcmd .= "$k=" . escapeshellarg( $v ) . ' ';
			}
		}

		$cmd = $envcmd . trim( $this->command );

		$useLogPipe = false;
		if ( is_executable( '/bin/bash' ) ) {
			$time = intval( $this->limits['time'] );
			$wallTime = intval( $this->limits['walltime'] );
			$mem = intval( $this->limits['memory'] );
			$filesize = intval( $this->limits['filesize'] );

			if ( $time > 0 || $mem > 0 || $filesize > 0 || $wallTime > 0 ) {
				$cmd = '/bin/bash ' . escapeshellarg( __DIR__ . '/limit.sh' ) . ' ' .
					   escapeshellarg( $cmd ) . ' ' .
					   escapeshellarg(
						   "MW_INCLUDE_STDERR=" . ( $this->useStderr ? '1' : '' ) . ';' .
						   "MW_CPU_LIMIT=$time; " .
						   'MW_CGROUP=' . escapeshellarg( $this->cgroup ) . '; ' .
						   "MW_MEM_LIMIT=$mem; " .
						   "MW_FILE_SIZE_LIMIT=$filesize; " .
						   "MW_WALL_CLOCK_LIMIT=$wallTime; " .
						   "MW_USE_LOG_PIPE=yes"
					   );
				$useLogPipe = true;
			} elseif ( $this->useStderr ) {
				$cmd .= ' 2>&1';
			}
		} elseif ( $this->useStderr ) {
			$cmd .= ' 2>&1';
		}
		wfDebug( __METHOD__ . ": $cmd\n" );

		// Don't try to execute commands that exceed Linux's MAX_ARG_STRLEN.
		// Other platforms may be more accomodating, but we don't want to be
		// accomodating, because very long commands probably include user
		// input. See T129506.
		if ( strlen( $cmd ) > SHELL_MAX_ARG_STRLEN ) {
			throw new Exception( __METHOD__ .
								 '(): total length of $cmd must not exceed SHELL_MAX_ARG_STRLEN' );
		}

		$desc = [
			0 => [ 'file', 'php://stdin', 'r' ],
			1 => [ 'pipe', 'w' ],
			2 => [ 'file', 'php://stderr', 'w' ],
		];
		if ( $useLogPipe ) {
			$desc[3] = [ 'pipe', 'w' ];
		}
		$pipes = null;
		$scoped = Profiler::instance()->scopedProfileIn( __FUNCTION__ . '-' . $profileMethod );
		$proc = proc_open( $cmd, $desc, $pipes );
		if ( !$proc ) {
			$this->logger->error( "proc_open() failed: {command}", [ 'command' => $cmd ] );
			throw new ProcOpenError();
		}
		$outBuffer = $logBuffer = '';
		$emptyArray = [];
		$status = false;
		$logMsg = false;

		/* According to the documentation, it is possible for stream_select()
		 * to fail due to EINTR. I haven't managed to induce this in testing
		 * despite sending various signals. If it did happen, the error
		 * message would take the form:
		 *
		 * stream_select(): unable to select [4]: Interrupted system call (max_fd=5)
		 *
		 * where [4] is the value of the macro EINTR and "Interrupted system
		 * call" is string which according to the Linux manual is "possibly"
		 * localised according to LC_MESSAGES.
		 */
		$eintr = defined( 'SOCKET_EINTR' ) ? SOCKET_EINTR : 4;
		$eintrMessage = "stream_select(): unable to select [$eintr]";

		$running = true;
		$timeout = null;
		$numReadyPipes = 0;

		while ( $running === true || $numReadyPipes !== 0 ) {
			if ( $running ) {
				$status = proc_get_status( $proc );
				// If the process has terminated, switch to nonblocking selects
				// for getting any data still waiting to be read.
				if ( !$status['running'] ) {
					$running = false;
					$timeout = 0;
				}
			}

			$readyPipes = $pipes;

			// Clear last error
			// @codingStandardsIgnoreStart Generic.PHP.NoSilencedErrors.Discouraged
			@trigger_error( '' );
			$numReadyPipes = @stream_select( $readyPipes, $emptyArray, $emptyArray, $timeout );
			if ( $numReadyPipes === false ) {
				// @codingStandardsIgnoreEnd
				$error = error_get_last();
				if ( strncmp( $error['message'], $eintrMessage, strlen( $eintrMessage ) ) == 0 ) {
					continue;
				} else {
					trigger_error( $error['message'], E_USER_WARNING );
					$logMsg = $error['message'];
					break;
				}
			}
			foreach ( $readyPipes as $fd => $pipe ) {
				$block = fread( $pipe, 65536 );
				if ( $block === '' ) {
					// End of file
					fclose( $pipes[$fd] );
					unset( $pipes[$fd] );
					if ( !$pipes ) {
						break 2;
					}
				} elseif ( $block === false ) {
					// Read error
					$logMsg = "Error reading from pipe";
					break 2;
				} elseif ( $fd == 1 ) {
					// From stdout
					$outBuffer .= $block;
				} elseif ( $fd == 3 ) {
					// From log FD
					$logBuffer .= $block;
					if ( strpos( $block, "\n" ) !== false ) {
						$lines = explode( "\n", $logBuffer );
						$logBuffer = array_pop( $lines );
						foreach ( $lines as $line ) {
							$this->logger->info( $line );
						}
					}
				}
			}
		}

		foreach ( $pipes as $pipe ) {
			fclose( $pipe );
		}

		// Use the status previously collected if possible, since proc_get_status()
		// just calls waitpid() which will not return anything useful the second time.
		if ( $running ) {
			$status = proc_get_status( $proc );
		}

		if ( $logMsg !== false ) {
			// Read/select error
			$retval = -1;
			proc_close( $proc );
		} elseif ( $status['signaled'] ) {
			$logMsg = "Exited with signal {$status['termsig']}";
			$retval = 128 + $status['termsig'];
			proc_close( $proc );
		} else {
			if ( $status['running'] ) {
				$retval = proc_close( $proc );
			} else {
				$retval = $status['exitcode'];
				proc_close( $proc );
			}
			if ( $retval == 127 ) {
				$logMsg = "Possibly missing executable file";
			} elseif ( $retval >= 129 && $retval <= 192 ) {
				$logMsg = "Probably exited with signal " . ( $retval - 128 );
			}
		}

		if ( $logMsg !== false ) {
			$this->logger->warning( "$logMsg: {command}", [ 'command' => $cmd ] );
		}

		return new Result( $retval, $outBuffer );
	}
}
