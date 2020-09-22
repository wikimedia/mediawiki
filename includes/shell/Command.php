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
use Wikimedia\AtEase\AtEase;

/**
 * Class used for executing shell commands
 *
 * @since 1.30
 */
class Command {
	use LoggerAwareTrait;

	/** @var string */
	protected $command = '';

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

	/** @var string */
	private $inputString = '';

	/** @var bool */
	private $doIncludeStderr = false;

	/** @var bool */
	private $doLogStderr = false;

	/** @var bool */
	private $doPassStdin = false;

	/** @var bool */
	private $doPassStderr = false;

	/** @var bool */
	private $everExecuted = false;

	/** @var string|false */
	private $cgroup = false;

	/**
	 * Bitfield with restrictions
	 *
	 * @var int
	 */
	protected $restrictions = 0;

	/**
	 * Don't call directly, instead use Shell::command()
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
	 * Makes sure the programmer didn't forget to execute the command after all
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
	 * @param string|string[] ...$args
	 * @return $this
	 */
	public function params( ...$args ): Command {
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
	 * @param string|string[] ...$args
	 * @return $this
	 */
	public function unsafeParams( ...$args ): Command {
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
	public function limits( array $limits ): Command {
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
	public function environment( array $env ): Command {
		$this->env = $env;

		return $this;
	}

	/**
	 * Sets calling function for profiler. By default, the caller for execute() will be used.
	 *
	 * @param string $method
	 * @return $this
	 */
	public function profileMethod( string $method ): Command {
		$this->method = $method;

		return $this;
	}

	/**
	 * Sends the provided input to the command. Defaults to an empty string.
	 * If you want to pass stdin through to the command instead, use
	 * passStdin().
	 *
	 * @param string $inputString
	 * @return $this
	 */
	public function input( string $inputString ): Command {
		$this->inputString = $inputString;

		return $this;
	}

	/**
	 * Controls whether stdin is passed through to the command, so that the
	 * user can interact with the command when it is run in CLI mode. If this
	 * is enabled:
	 *   - The wall clock timeout will be disabled to avoid stopping the
	 *     process with SIGTTIN/SIGTTOU (T206957).
	 *   - The string specified with input() will be ignored.
	 *
	 * @param bool $yesno
	 * @return $this
	 */
	public function passStdin( bool $yesno = true ): Command {
		$this->doPassStdin = $yesno;

		return $this;
	}

	/**
	 * If this is set to true, text written to stderr by the command will be
	 * passed through to PHP's stderr. To avoid SIGTTIN/SIGTTOU, and to support
	 * Result::getStderr(), the file descriptor is not passed through, we just
	 * copy the data to stderr as we receive it.
	 *
	 * @param bool $yesno
	 * @return $this
	 */
	public function forwardStderr( bool $yesno = true ): Command {
		$this->doPassStderr = $yesno;

		return $this;
	}

	/**
	 * Controls whether stderr should be included in stdout, including errors from limit.sh.
	 * Default: don't include.
	 *
	 * @param bool $yesno
	 * @return $this
	 */
	public function includeStderr( bool $yesno = true ): Command {
		$this->doIncludeStderr = $yesno;

		return $this;
	}

	/**
	 * When enabled, text sent to stderr will be logged with a level of 'error'.
	 *
	 * @param bool $yesno
	 * @return $this
	 */
	public function logStderr( bool $yesno = true ): Command {
		$this->doLogStderr = $yesno;

		return $this;
	}

	/**
	 * Sets cgroup for this command
	 *
	 * @param string|false $cgroup Absolute file path to the cgroup, or false to not use a cgroup
	 * @return $this
	 */
	public function cgroup( $cgroup ): Command {
		$this->cgroup = $cgroup;

		return $this;
	}

	/**
	 * Set restrictions for this request, overwriting any previously set restrictions.
	 *
	 * Add the "no network" restriction:
	 * @code
	 * 	$command->restrict( Shell::RESTRICT_DEFAULT | Shell::NO_NETWORK );
	 * @endcode
	 *
	 * Allow LocalSettings.php access:
	 * @code
	 * 	$command->restrict( Shell::RESTRICT_DEFAULT & ~Shell::NO_LOCALSETTINGS );
	 * @endcode
	 *
	 * Disable all restrictions:
	 * @code
	 *  $command->restrict( Shell::RESTRICT_NONE );
	 * @endcode
	 *
	 * @since 1.31
	 * @param int $restrictions
	 * @return $this
	 */
	public function restrict( int $restrictions ): Command {
		$this->restrictions = $restrictions;

		return $this;
	}

	/**
	 * Bitfield helper on whether a specific restriction is enabled
	 *
	 * @param int $restriction
	 *
	 * @return bool
	 */
	protected function hasRestriction( int $restriction ): bool {
		return ( $this->restrictions & $restriction ) === $restriction;
	}

	/**
	 * If called, only the files/directories that are
	 * whitelisted will be available to the shell command.
	 *
	 * limit.sh will always be whitelisted
	 *
	 * @param string[] $paths
	 *
	 * @return $this
	 */
	public function whitelistPaths( array $paths ): Command {
		// Default implementation is a no-op
		return $this;
	}

	/**
	 * String together all the options and build the final command
	 * to execute
	 *
	 * @param string $command Already-escaped command to run
	 * @return array [ command, whether to use log pipe ]
	 */
	protected function buildFinalCommand( string $command ): array {
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

		$useLogPipe = false;
		$cmd = $envcmd . trim( $command );

		if ( is_executable( '/bin/bash' ) ) {
			$time = intval( $this->limits['time'] );
			$wallTime = $this->doPassStdin ? 0 : intval( $this->limits['walltime'] );
			$mem = intval( $this->limits['memory'] );
			$filesize = intval( $this->limits['filesize'] );

			if ( $time > 0 || $mem > 0 || $filesize > 0 || $wallTime > 0 ) {
				$cmd = '/bin/bash ' . escapeshellarg( __DIR__ . '/limit.sh' ) . ' ' .
					escapeshellarg( $cmd ) . ' ' .
					escapeshellarg(
						"MW_INCLUDE_STDERR=" . ( $this->doIncludeStderr ? '1' : '' ) . ';' .
						"MW_CPU_LIMIT=$time; " .
						'MW_CGROUP=' . escapeshellarg( $this->cgroup ) . '; ' .
						"MW_MEM_LIMIT=$mem; " .
						"MW_FILE_SIZE_LIMIT=$filesize; " .
						"MW_WALL_CLOCK_LIMIT=$wallTime; " .
						"MW_USE_LOG_PIPE=yes"
					);
				$useLogPipe = true;
			}
		}
		if ( !$useLogPipe && $this->doIncludeStderr ) {
			$cmd .= ' 2>&1';
		}

		if ( wfIsWindows() ) {
			$cmd = 'cmd.exe /c "' . $cmd . '"';
		}

		return [ $cmd, $useLogPipe ];
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
	public function execute(): Result {
		$this->everExecuted = true;

		$profileMethod = $this->method ?: wfGetCaller();

		list( $cmd, $useLogPipe ) = $this->buildFinalCommand( $this->command );

		$this->logger->debug( __METHOD__ . ": $cmd" );

		// Don't try to execute commands that exceed Linux's MAX_ARG_STRLEN.
		// Other platforms may be more accomodating, but we don't want to be
		// accomodating, because very long commands probably include user
		// input. See T129506.
		if ( strlen( $cmd ) > SHELL_MAX_ARG_STRLEN ) {
			throw new Exception( __METHOD__ .
				'(): total length of $cmd must not exceed SHELL_MAX_ARG_STRLEN' );
		}

		$desc = [
			0 => $this->doPassStdin ? [ 'file', 'php://stdin', 'r' ] : [ 'pipe', 'r' ],
			1 => [ 'pipe', 'w' ],
			2 => [ 'pipe', 'w' ],
		];
		if ( $useLogPipe ) {
			$desc[3] = [ 'pipe', 'w' ];
		}
		$pipes = null;
		$scoped = Profiler::instance()->scopedProfileIn( __FUNCTION__ . '-' . $profileMethod );
		$proc = null;

		if ( wfIsWindows() ) {
			// Windows Shell bypassed, but command run is "cmd.exe /C "{$cmd}"
			// This solves some shell parsing issues, see T207248
			$proc = proc_open( $cmd, $desc, $pipes, null, null, [ 'bypass_shell' => true ] );
		} else {
			$proc = proc_open( $cmd, $desc, $pipes );
		}

		if ( !$proc ) {
			$this->logger->error( "proc_open() failed: {command}", [ 'command' => $cmd ] );
			throw new ProcOpenError();
		}

		$buffers = [
			0 => $this->inputString, // input
			1 => '', // stdout
			2 => null, // stderr
			3 => '', // log
		];
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

		/* The select(2) system call only guarantees a "sufficiently small write"
		 * can be made without blocking. And on Linux the read might block too
		 * in certain cases, although I don't know if any of them can occur here.
		 * Regardless, set all the pipes to non-blocking to avoid T184171.
		 */
		foreach ( $pipes as $pipe ) {
			stream_set_blocking( $pipe, false );
		}

		$running = true;
		$timeout = null;
		$numReadyPipes = 0;

		while ( $pipes && ( $running === true || $numReadyPipes !== 0 ) ) {
			if ( $running ) {
				$status = proc_get_status( $proc );
				// If the process has terminated, switch to nonblocking selects
				// for getting any data still waiting to be read.
				if ( !$status['running'] ) {
					$running = false;
					$timeout = 0;
				}
			}

			error_clear_last();

			$readPipes = array_filter( $pipes, function ( $fd ) use ( $desc ) {
				return $desc[$fd][0] === 'pipe' && $desc[$fd][1] === 'r';
			}, ARRAY_FILTER_USE_KEY );
			$writePipes = array_filter( $pipes, function ( $fd ) use ( $desc ) {
				return $desc[$fd][0] === 'pipe' && $desc[$fd][1] === 'w';
			}, ARRAY_FILTER_USE_KEY );
			// stream_select parameter names are from the POV of us being able to do the operation;
			// proc_open desriptor types are from the POV of the process doing it.
			// So $writePipes is passed as the $read parameter and $readPipes as $write.
			AtEase::suppressWarnings();
			$numReadyPipes = stream_select( $writePipes, $readPipes, $emptyArray, $timeout );
			AtEase::restoreWarnings();
			if ( $numReadyPipes === false ) {
				$error = error_get_last();
				if ( strncmp( $error['message'], $eintrMessage, strlen( $eintrMessage ) ) == 0 ) {
					continue;
				} else {
					trigger_error( $error['message'], E_USER_WARNING );
					$logMsg = $error['message'];
					break;
				}
			}
			foreach ( $writePipes + $readPipes as $fd => $pipe ) {
				// True if a pipe is unblocked for us to write into, false if for reading from
				$isWrite = array_key_exists( $fd, $readPipes );

				if ( $isWrite ) {
					// Don't bother writing if the buffer is empty
					if ( $buffers[$fd] === '' ) {
						fclose( $pipes[$fd] );
						unset( $pipes[$fd] );
						continue;
					}
					$res = fwrite( $pipe, $buffers[$fd], 65536 );
				} else {
					$res = fread( $pipe, 65536 );
				}

				if ( $res === false ) {
					$logMsg = 'Error ' . ( $isWrite ? 'writing to' : 'reading from' ) . ' pipe';
					break 2;
				}

				if ( $res === '' || $res === 0 ) {
					// End of file?
					if ( feof( $pipe ) ) {
						fclose( $pipes[$fd] );
						unset( $pipes[$fd] );
					}
				} elseif ( $isWrite ) {
					$buffers[$fd] = (string)substr( $buffers[$fd], $res );
					if ( $buffers[$fd] === '' ) {
						fclose( $pipes[$fd] );
						unset( $pipes[$fd] );
					}
				} else {
					$buffers[$fd] .= $res;
					if ( $fd === 3 && strpos( $res, "\n" ) !== false ) {
						// For the log FD, every line is a separate log entry.
						$lines = explode( "\n", $buffers[3] );
						$buffers[3] = array_pop( $lines );
						foreach ( $lines as $line ) {
							$this->logger->info( $line );
						}
					}
					if ( $fd === 2 && $this->doPassStderr ) {
						fwrite( STDERR, $res );
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

		// @phan-suppress-next-line PhanImpossibleCondition
		if ( $buffers[2] && $this->doLogStderr ) {
			$this->logger->error( "Error running {command}: {error}", [
				'command' => $cmd,
				'error' => $buffers[2],
				'exitcode' => $retval,
				'exception' => new Exception( 'Shell error' ),
			] );
		}

		return new Result( $retval, $buffers[1], $buffers[2] );
	}

	/**
	 * Returns the final command line before environment/limiting, etc are applied.
	 * Use string conversion only for debugging, don't try to pass this to
	 * some other execution medium.
	 *
	 * @return string
	 */
	public function __toString(): string {
		return "#Command: {$this->command}";
	}
}
