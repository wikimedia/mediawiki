<?php
/**
 * Class used for executing shell commands
 *
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

use Hooks;
use MediaWiki\MediaWikiServices;

/**
 * Executes shell commands
 *
 * @since 1.30
 *
 * Use call chaining with this class for expressiveness:
 *  $result = Shell::command( 'some command' )
 *       ->input( 'foo' )
 *       ->environment( [ 'ENVIRONMENT_VARIABLE' => 'VALUE' ] )
 *       ->limits( [ 'time' => 300 ] )
 *       ->execute();
 *
 *  ... = $result->getExitCode();
 *  ... = $result->getStdout();
 *  ... = $result->getStderr();
 */
class Shell {

	/**
	 * Apply a default set of restrictions for improved
	 * security out of the box.
	 *
	 * Equal to NO_ROOT | SECCOMP | PRIVATE_DEV | NO_LOCALSETTINGS
	 *
	 * @note This value will change over time to provide increased security
	 *       by default, and is not guaranteed to be backwards-compatible.
	 * @since 1.31
	 */
	const RESTRICT_DEFAULT = 39;

	/**
	 * Disallow any root access. Any setuid binaries
	 * will be run without elevated access.
	 *
	 * @since 1.31
	 */
	const NO_ROOT = 1;

	/**
	 * Use seccomp to block dangerous syscalls
	 * @see <https://en.wikipedia.org/wiki/seccomp>
	 *
	 * @since 1.31
	 */
	const SECCOMP = 2;

	/**
	 * Create a private /dev
	 *
	 * @since 1.31
	 */
	const PRIVATE_DEV = 4;

	/**
	 * Restrict the request to have no
	 * network access
	 *
	 * @since 1.31
	 */
	const NO_NETWORK = 8;

	/**
	 * Deny execve syscall with seccomp
	 * @see <https://en.wikipedia.org/wiki/exec_(system_call)>
	 *
	 * @since 1.31
	 */
	const NO_EXECVE = 16;

	/**
	 * Deny access to LocalSettings.php (MW_CONFIG_FILE)
	 *
	 * @since 1.31
	 */
	const NO_LOCALSETTINGS = 32;

	/**
	 * Don't apply any restrictions
	 *
	 * @since 1.31
	 */
	const RESTRICT_NONE = 0;

	/**
	 * Returns a new instance of Command class
	 *
	 * @param string|string[] $command String or array of strings representing the command to
	 * be executed, each value will be escaped.
	 *   Example:   [ 'convert', '-font', 'font name' ] would produce "'convert' '-font' 'font name'"
	 * @return Command
	 */
	public static function command( $command ) {
		$args = func_get_args();
		if ( count( $args ) === 1 && is_array( reset( $args ) ) ) {
			// If only one argument has been passed, and that argument is an array,
			// treat it as a list of arguments
			$args = reset( $args );
		}
		$command = MediaWikiServices::getInstance()
			->getShellCommandFactory()
			->create();

		return $command->params( $args );
	}

	/**
	 * Check if this class is effectively disabled via php.ini config
	 *
	 * @return bool
	 */
	public static function isDisabled() {
		static $disabled = null;

		if ( is_null( $disabled ) ) {
			if ( !function_exists( 'proc_open' ) ) {
				wfDebug( "proc_open() is disabled\n" );
				$disabled = true;
			} else {
				$disabled = false;
			}
		}

		return $disabled;
	}

	/**
	 * Version of escapeshellarg() that works better on Windows.
	 *
	 * Originally, this fixed the incorrect use of single quotes on Windows
	 * (https://bugs.php.net/bug.php?id=26285) and the locale problems on Linux in
	 * PHP 5.2.6+ (bug backported to earlier distro releases of PHP).
	 *
	 * @param string $args,... strings to escape and glue together, or a single array of
	 *     strings parameter. Null values are ignored.
	 * @return string
	 */
	public static function escape( /* ... */ ) {
		$args = func_get_args();
		if ( count( $args ) === 1 && is_array( reset( $args ) ) ) {
			// If only one argument has been passed, and that argument is an array,
			// treat it as a list of arguments
			$args = reset( $args );
		}

		$first = true;
		$retVal = '';
		foreach ( $args as $arg ) {
			if ( $arg === null ) {
				continue;
			}
			if ( !$first ) {
				$retVal .= ' ';
			} else {
				$first = false;
			}

			if ( wfIsWindows() ) {
				// Escaping for an MSVC-style command line parser and CMD.EXE
				// Refs:
				//  * https://web.archive.org/web/20020708081031/http://mailman.lyra.org/pipermail/scite-interest/2002-March/000436.html
				//  * https://technet.microsoft.com/en-us/library/cc723564.aspx
				//  * T15518
				//  * CR r63214
				// Double the backslashes before any double quotes. Escape the double quotes.
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
	 * Generate a Command object to run a MediaWiki CLI script.
	 * Note that $parameters should be a flat array and an option with an argument
	 * should consist of two consecutive items in the array (do not use "--option value").
	 *
	 * @param string $script MediaWiki CLI script with full path
	 * @param string[] $parameters Arguments and options to the script
	 * @param array $options Associative array of options:
	 *     'php': The path to the php executable
	 *     'wrapper': Path to a PHP wrapper to handle the maintenance script
	 * @return Command
	 */
	public static function makeScriptCommand( $script, $parameters, $options = [] ) {
		global $wgPhpCli;
		// Give site config file a chance to run the script in a wrapper.
		// The caller may likely want to call wfBasename() on $script.
		Hooks::run( 'wfShellWikiCmd', [ &$script, &$parameters, &$options ] );
		$cmd = isset( $options['php'] ) ? [ $options['php'] ] : [ $wgPhpCli ];
		if ( isset( $options['wrapper'] ) ) {
			$cmd[] = $options['wrapper'];
		}
		$cmd[] = $script;

		return self::command( $cmd )
			->params( $parameters )
			->restrict( self::RESTRICT_DEFAULT & ~self::NO_LOCALSETTINGS );
	}
}
