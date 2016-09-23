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

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @since 1.28
 */
class ShellExec implements LoggerAwareInterface {

	/**
	 * @var string
	 */
	private $shellLocale;

	/**
	 * @var bool
	 */
	private $setShellLocale = false;

	/**
	 * @var array Default limits
	 */
	private $defaultLimits;

	/**
	 * @var string|bool
	 */
	private $shellCGroup;

	/**
	 * @var bool|callable
	 */
	private $profileCallback;

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @param array $limits array with Default limits(filesize, memory, time, walltime)
	 *   which can be overridden per shell exec.
	 * @param string $shellLocale
	 * @param string|bool $shellCGroup
	 * @param callable|bool $profileCallback
	 */
	public function __construct( array $limits, $shellLocale = 'en_US.utf8', $shellCGroup = false, $profileCallback = false ) {
		$this->defaultLimits = $limits;
		$this->shellLocale = $shellLocale;
		$this->shellCGroup = $shellCGroup;
		if ( $profileCallback !== false && !is_callable( $profileCallback ) ) {
			throw new InvalidArgumentException( '$profileCallback is not a valid callable' );
		}
		$this->profileCallback = $profileCallback;
		$this->logger = new NullLogger();

	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Workaround for http://bugs.php.net/bug.php?id=45132
	 * escapeshellarg() destroys non-ASCII characters if LANG is not a UTF-8 locale
	 */
	private function initShellLocale() {
		if ( !$this->setShellLocale ) {
			$this->setShellLocale = true;
			putenv( "LC_CTYPE={$this->shellLocale}" );
			setlocale( LC_CTYPE, $this->shellLocale );
		}
	}

	/**
	 * Check whether shelling out is effectively disabling via
	 * php.ini config
	 *
	 * @return bool
	 */
	public function isDisabled() {
		static $disabled = null;
		if ( $disabled === null ) {
			if ( !function_exists( 'proc_open' ) ) {
				$this->logger->info( "proc_open() is disabled\n" );
				$disabled = true;
			} else {
				$disabled = false;
			}
		}

		return $disabled;
	}

	/**
	 * Whether we are running on Windows or not
	 *
	 * @return bool
	 */
	private function isWindows() {
		static $isWindows = null;
		if ( $isWindows === null ) {
			$isWindows = strtoupper( substr( PHP_OS, 0, 3 ) ) === 'WIN';
		}
		return $isWindows;
	}

	/**
	 * Windows-compatible version of escapeshellarg()
	 * Windows doesn't recognise single-quotes in the shell, but the escapeshellarg()
	 * function puts single quotes in regardless of OS.
	 *
	 * Also fixes the locale problems on Linux in PHP 5.2.6+ (bug backported to
	 * earlier distro releases of PHP)
	 *
	 * @param string ... strings to escape and glue together, or a single array of strings parameter
	 * @return string
	 */
	public function escapeShellArg( /*...*/ ) {
		$this->initShellLocale();

		$args = func_get_args();
		if ( count( $args ) === 1 && is_array( reset( $args ) ) ) {
			// If only one argument has been passed, and that argument is an array,
			// treat it as a list of arguments
			$args = reset( $args );
		}

		$first = true;
		$retVal = '';
		foreach ( $args as $arg ) {
			if ( !$first ) {
				$retVal .= ' ';
			} else {
				$first = false;
			}

			if ( $this->isWindows() ) {
				// Escaping for an MSVC-style command line parser and CMD.EXE
				// @codingStandardsIgnoreStart For long URLs
				// Refs:
				//  * http://web.archive.org/web/20020708081031/http://mailman.lyra.org/pipermail/scite-interest/2002-March/000436.html
				//  * http://technet.microsoft.com/en-us/library/cc723564.aspx
				//  * T15518
				//  * CR r63214
				// Double the backslashes before any double quotes. Escape the double quotes.
				// @codingStandardsIgnoreEnd
				$tokens = preg_split( '/(\\\\*")/', $arg, -1, PREG_SPLIT_DELIM_CAPTURE );
				$arg = '';
				$iteration = 0;
				foreach ( $tokens as $token ) {
					if ( $iteration % 2 == 1 ) {
						// Delimiter, a double quote preceded by zero or more slashes
						$arg .= str_replace( '\\', '\\\\', substr( $token, 0, -1 ) ) . '\\"';
					} elseif ( $iteration % 4 == 2 ) {
						// ^ in $token will be outside quotes, need to be escaped
						$arg .= str_replace( '^', '^^', $token );
					} else { // $iteration % 4 == 0
						// ^ in $token will appear inside double quotes, so leave as is
						$arg .= $token;
					}
					$iteration++;
				}
				// Double the backslashes before the end of the string, because
				// we will soon add a quote
				$m = [];
				if ( preg_match( '/^(.*?)(\\\\+)$/', $arg, $m ) ) {
					$arg = $m[1] . str_replace( '\\', '\\\\', $m[2] );
				}

				// Add surrounding quotes
				$retVal .= '"' . $arg . '"';
			} else {
				$retVal .= escapeshellarg( $arg );
			}
		}
		return $retVal;
	}


	/**
	 * Execute a shell command, with time and memory limits mirrored from the PHP
	 * configuration if supported.
	 *
	 * @param string|string[] $cmd If string, a properly shell-escaped command line,
	 *   or an array of unescaped arguments, in which case each value will be escaped
	 *   Example:   [ 'convert', '-font', 'font name' ] would produce "'convert' '-font' 'font name'"
	 * @param null|mixed &$retval Optional, will receive the program's exit code.
	 *   (non-zero is usually failure). If there is an error from
	 *   read, select, or proc_open(), this will be set to -1.
	 * @param array $environ Optional environment variables which should be
	 *   added to the executed command environment.
	 * @param array $limits Optional array with limits(filesize, memory, time, walltime)
	 *   this overwrites the global wgMaxShell* limits.
	 * @param array $options Array of options:
	 *   - duplicateStderr: Set this to true to duplicate stderr to stdout,
	 *     including errors from limit.sh
	 *   - profileMethod: By default this function will profile based on the calling
	 *     method. Set this to a string for an alternative method to profile from
	 *
	 * @return string Collected stdout as a string
	 */
	function shellExec( $cmd, &$retval = null, $environ = [],
		$limits = [], $options = []
	) {
		if ( $this->isDisabled() ) {
			$retval = 1;
			return 'Unable to run external programs, proc_open() is disabled.';
		}

		$includeStderr = isset( $options['duplicateStderr'] ) && $options['duplicateStderr'];
		$profileMethod = isset( $options['profileMethod'] ) ? $options['profileMethod'] : wfGetCaller();

		$this->initShellLocale();

		$envcmd = '';
		foreach ( $environ as $k => $v ) {
			if ( $this->isWindows() ) {
				/* Surrounding a set in quotes (method used by wfEscapeShellArg) makes the quotes themselves
				 * appear in the environment variable, so we must use carat escaping as documented in
				 * http://technet.microsoft.com/en-us/library/cc723564.aspx
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
		if ( is_array( $cmd ) ) {
			$cmd = $this->escapeShellArg( $cmd );
		}

		$cmd = $envcmd . $cmd;

		$useLogPipe = false;
		if ( is_executable( '/bin/bash' ) ) {
			$time = intval( isset( $limits['time'] ) ? $limits['time'] : $this->defaultLimits['time'] );
			if ( isset( $limits['walltime'] ) ) {
				$wallTime = intval( $limits['walltime'] );
			} elseif ( isset( $limits['time'] ) ) {
				$wallTime = $time;
			} else {
				$wallTime = intval( $this->defaultLimits['walltime'] );
			}
			$mem = intval( isset( $limits['memory'] ) ? $limits['memory'] : $this->defaultLimits['memory'] );
			$filesize = intval( isset( $limits['filesize'] ) ? $limits['filesize'] : $this->defaultLimits['filesize'] );

			if ( $time > 0 || $mem > 0 || $filesize > 0 || $wallTime > 0 ) {
				$limitScript = __DIR__ . '/limit.sh';
				$cmd = '/bin/bash ' . escapeshellarg( $limitScript ) . ' ' .
					escapeshellarg( $cmd ) . ' ' .
					escapeshellarg(
						"MW_INCLUDE_STDERR=" . ( $includeStderr ? '1' : '' ) . ';' .
						"MW_CPU_LIMIT=$time; " .
						'MW_CGROUP=' . escapeshellarg( $this->shellCGroup ) . '; ' .
						"MW_MEM_LIMIT=$mem; " .
						"MW_FILE_SIZE_LIMIT=$filesize; " .
						"MW_WALL_CLOCK_LIMIT=$wallTime; " .
						"MW_USE_LOG_PIPE=yes"
					);
				$useLogPipe = true;
			} elseif ( $includeStderr ) {
				$cmd .= ' 2>&1';
			}
		} elseif ( $includeStderr ) {
			$cmd .= ' 2>&1';
		}
		$this->logger->debug( "wfShellExec: $cmd\n" );

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
			2 => [ 'file', 'php://stderr', 'w' ] ];
		if ( $useLogPipe ) {
			$desc[3] = [ 'pipe', 'w' ];
		}
		$pipes = null;
		if ( $this->profileCallback !== false ) {
			$scoped = call_user_func( $this->profileCallback, __METHOD__ . '-' . $profileMethod );
		}
		$proc = proc_open( $cmd, $desc, $pipes );
		if ( !$proc ) {
			$this->logger->warning( 'exec', "proc_open() failed: $cmd" );
			$retval = -1;
			return '';
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
							$this->logger->info( 'exec', $line );
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
			$this->logger->info( 'exec', "$logMsg: $cmd" );
		}

		return $outBuffer;
	}
}
