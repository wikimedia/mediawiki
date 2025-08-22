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

namespace MediaWiki\Debug;

use LogicException;
use MediaWiki\Api\ApiResult;
use MediaWiki\Context\IContextSource;
use MediaWiki\Html\Html;
use MediaWiki\Json\FormatJson;
use MediaWiki\Logger\LegacyLogger;
use MediaWiki\Output\OutputPage;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\ResourceLoader\ResourceLoader;
use MediaWiki\Utils\GitInfo;
use ReflectionMethod;
use UtfNormal;
use Wikimedia\WrappedString;
use Wikimedia\WrappedStringList;

/**
 * Debug toolbar.
 *
 * By default most of these methods do nothing, as enforced by self::$enabled = false.
 *
 * To enable the debug toolbar, use $wgDebugToolbar = true in LocalSettings.php.
 * That ensures MWDebug::init() is called from Setup.php.
 *
 * @since 1.19
 * @ingroup Debug
 */
class MWDebug {
	/**
	 * Log lines
	 *
	 * @var array
	 */
	protected static $log = [];

	/**
	 * Debug messages from wfDebug().
	 *
	 * @var array
	 */
	protected static $debug = [];

	/**
	 * SQL statements of the database queries.
	 *
	 * @var array
	 */
	protected static $query = [];

	/**
	 * Is the debugger enabled?
	 *
	 * @var bool
	 */
	protected static $enabled = false;

	/**
	 * Array of functions that have already been warned, formatted
	 * function-caller to prevent a buttload of warnings
	 *
	 * @var array
	 */
	protected static $deprecationWarnings = [];

	/**
	 * @var array Keys are regexes, values are optional callbacks to call if the filter is hit
	 */
	protected static $deprecationFilters = [];

	/**
	 * @internal For use by Setup.php only.
	 */
	public static function setup() {
		global $wgDebugToolbar,
			$wgUseCdn, $wgUseFileCache;

		if (
			// Easy to forget to falsify $wgDebugToolbar for static caches.
			// If file cache or CDN cache is on, just disable this (DWIMD).
			$wgUseCdn ||
			$wgUseFileCache ||
			// Keep MWDebug off on CLI. This prevents MWDebug from eating up
			// all the memory for logging SQL queries in maintenance scripts.
			MW_ENTRY_POINT === 'cli'
		) {
			return;
		}

		if ( $wgDebugToolbar ) {
			self::init();
		}
	}

	/**
	 * Enabled the debugger and load resource module.
	 * This is called by Setup.php when $wgDebugToolbar is true.
	 *
	 * @since 1.19
	 */
	public static function init() {
		self::$enabled = true;
	}

	/**
	 * Disable the debugger.
	 *
	 * @since 1.28
	 */
	public static function deinit() {
		self::$enabled = false;
	}

	/**
	 * Add ResourceLoader modules to the OutputPage object if debugging is
	 * enabled.
	 *
	 * @since 1.19
	 * @param OutputPage $out
	 */
	public static function addModules( OutputPage $out ) {
		if ( self::$enabled ) {
			$out->addModules( 'mediawiki.debug' );
		}
	}

	/**
	 * Adds a line to the log
	 *
	 * @since 1.19
	 * @param mixed $str
	 */
	public static function log( $str ) {
		if ( !self::$enabled ) {
			return;
		}
		if ( !is_string( $str ) ) {
			$str = print_r( $str, true );
		}
		self::$log[] = [
			'msg' => htmlspecialchars( $str ),
			'type' => 'log',
			'caller' => wfGetCaller(),
		];
	}

	/**
	 * Returns internal log array
	 * @since 1.19
	 * @return array
	 */
	public static function getLog() {
		return self::$log;
	}

	/**
	 * Clears internal log array and deprecation tracking
	 * @since 1.19
	 */
	public static function clearLog() {
		self::$log = [];
		self::$deprecationWarnings = [];
	}

	/**
	 * Adds a warning entry to the log
	 *
	 * @since 1.19
	 * @param string $msg
	 * @param int $callerOffset
	 * @param int $level A PHP error level. See sendMessage()
	 * @param string $log 'production' will always trigger a php error, 'auto'
	 *    will trigger an error if $wgDevelopmentWarnings is true, and 'debug'
	 *    will only write to the debug log(s).
	 */
	public static function warning( $msg, $callerOffset = 1, $level = E_USER_NOTICE, $log = 'auto' ) {
		global $wgDevelopmentWarnings;

		if ( $log === 'auto' && !$wgDevelopmentWarnings ) {
			$log = 'debug';
		}

		if ( $log === 'debug' ) {
			$level = false;
		}

		$callerDescription = self::getCallerDescription( $callerOffset );

		self::sendMessage(
			self::formatCallerDescription( $msg, $callerDescription ),
			'warning',
			$level );
	}

	/**
	 * Show a warning that $function is deprecated.
	 *
	 * @see deprecatedMsg()
	 * @since 1.19
	 *
	 * @param string $function Function that is deprecated.
	 * @param string|false $version Version in which the function was deprecated.
	 * @param string|bool $component Component to which the function belongs.
	 *    If false, it is assumed the function is in MediaWiki core.
	 * @param int $callerOffset How far up the callstack is the original
	 *    caller. 2 = function that called the function that called
	 *    MWDebug::deprecated() (Added in 1.20).
	 */
	public static function deprecated( $function, $version = false,
		$component = false, $callerOffset = 2
	) {
		if ( $version ) {
			$component = $component ?: 'MediaWiki';
			$msg = "Use of $function was deprecated in $component $version.";
		} else {
			$msg = "Use of $function is deprecated.";
		}
		self::deprecatedMsg( $msg, $version, $component, $callerOffset + 1 );
	}

	/**
	 * Show a warning if $method declared in $class is overridden in $instance.
	 *
	 * @since 1.36
	 * @see deprecatedMsg()
	 *
	 * @phpcs:ignore MediaWiki.Commenting.FunctionComment.ObjectTypeHintParam
	 * @param object $instance Object on which to detect deprecated overrides (typically $this).
	 * @param class-string $class Class declaring the deprecated method (typically __CLASS__ )
	 * @param string $method The name of the deprecated method.
	 * @param string|false $version Version in which the method was deprecated.
	 *   Does not issue deprecation warnings if false.
	 * @param string|bool $component Component to which the class belongs.
	 *    If false, it is assumed the class is in MediaWiki core.
	 * @param int $callerOffset How far up the callstack is the original
	 *    caller. 2 = function that called the function that called
	 *    MWDebug::detectDeprecatedOverride()
	 *
	 * @return bool True if the method was overridden, false otherwise. If the method
	 *         was overridden, it should be called. The deprecated method's default
	 *         implementation should call MWDebug::deprecated().
	 */
	public static function detectDeprecatedOverride( $instance, $class, $method, $version = false,
		$component = false, $callerOffset = 2
	) {
		$reflectionMethod = new ReflectionMethod( $instance, $method );
		$declaringClass = $reflectionMethod->getDeclaringClass()->getName();

		if ( $declaringClass === $class ) {
			// not overridden, nothing to do
			return false;
		}

		if ( $version ) {
			$component = $component ?: 'MediaWiki';
			$msg = "$declaringClass overrides $method which was deprecated in $component $version.";
			self::deprecatedMsg( $msg, $version, $component, $callerOffset + 1 );
		}

		return true;
	}

	/**
	 * Log a deprecation warning with arbitrary message text. A caller
	 * description will be appended. If the message has already been sent for
	 * this caller, it won't be sent again.
	 *
	 * Although there are component and version parameters, they are not
	 * automatically appended to the message. The message text should include
	 * information about when the thing was deprecated.
	 *
	 * The warning will be sent to the following locations:
	 * - Debug toolbar, with one item per function and caller, if $wgDebugToolbar
	 *   is set to true.
	 * - PHP's error log, with level E_USER_DEPRECATED, if $wgDevelopmentWarnings
	 *   is set to true. This is the case in phpunit tests by default, and will
	 *   cause tests to fail.
	 * - MediaWiki's debug log, if $wgDevelopmentWarnings is set to false.
	 *
	 * @since 1.35
	 *
	 * @param string $msg The message
	 * @param string|false $version Version of MediaWiki that the function
	 *    was deprecated in.
	 * @param string|bool $component Component to which the function belongs.
	 *    If false, it is assumed the function is in MediaWiki core.
	 * @param int|false $callerOffset How far up the call stack is the original
	 *    caller. 2 = function that called the function that called us. If false,
	 *    the caller description will not be appended.
	 */
	public static function deprecatedMsg( $msg, $version = false,
		$component = false, $callerOffset = 2
	) {
		if ( $callerOffset === false ) {
			$callerFunc = '';
			$rawMsg = $msg;
		} else {
			$callerDescription = self::getCallerDescription( $callerOffset );
			$callerFunc = $callerDescription['func'];
			$rawMsg = self::formatCallerDescription( $msg, $callerDescription );
		}

		$sendToLog = true;

		// Check to see if there already was a warning about this function
		if ( isset( self::$deprecationWarnings[$msg][$callerFunc] ) ) {
			return;
		} elseif ( isset( self::$deprecationWarnings[$msg] ) ) {
			if ( self::$enabled ) {
				$sendToLog = false;
			} else {
				return;
			}
		}

		self::$deprecationWarnings[$msg][$callerFunc] = true;

		if ( $version ) {
			global $wgDeprecationReleaseLimit;

			$component = $component ?: 'MediaWiki';
			if ( $wgDeprecationReleaseLimit && $component === 'MediaWiki' ) {
				# Strip -* off the end of $version so that branches can use the
				# format #.##-branchname to avoid issues if the branch is merged into
				# a version of MediaWiki later than what it was branched from
				$comparableVersion = preg_replace( '/-.*$/', '', $version );

				# If the comparableVersion is larger than our release limit then
				# skip the warning message for the deprecation
				if ( version_compare( $wgDeprecationReleaseLimit, $comparableVersion, '<' ) ) {
					$sendToLog = false;
				}
			}
		}

		self::sendRawDeprecated(
			$rawMsg,
			$sendToLog,
			$callerFunc
		);
	}

	/**
	 * Send a raw deprecation message to the log and the debug toolbar,
	 * without filtering of duplicate messages. A caller description will
	 * not be appended.
	 *
	 * @param string $msg The complete message including relevant caller information.
	 * @param bool $sendToLog If true, the message will be sent to the debug
	 *   toolbar, the debug log, and raised as a warning to PHP. If false, the message
	 *   will only be sent to the debug toolbar.
	 * @param string $callerFunc The caller, for display in the debug toolbar's
	 *   caller column.
	 */
	public static function sendRawDeprecated( $msg, $sendToLog = true, $callerFunc = '' ) {
		foreach ( self::$deprecationFilters as $filter => $callback ) {
			if ( preg_match( $filter, $msg ) ) {
				if ( is_callable( $callback ) ) {
					$callback();
				}
				return;
			}
		}

		if ( $sendToLog ) {
			trigger_error( $msg, E_USER_DEPRECATED );
		}
	}

	/**
	 * Deprecation messages matching the supplied regex will be suppressed.
	 * Use this to filter deprecation warnings when testing deprecated code.
	 *
	 * @param string $regex
	 * @param ?callable $callback To call if $regex is hit
	 */
	public static function filterDeprecationForTest(
		string $regex, ?callable $callback = null
	): void {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new LogicException( __METHOD__ . ' can only be used in tests' );
		}
		self::$deprecationFilters[$regex] = $callback;
	}

	/**
	 * Clear all deprecation filters.
	 */
	public static function clearDeprecationFilters() {
		self::$deprecationFilters = [];
	}

	/**
	 * Get an array describing the calling function at a specified offset.
	 *
	 * @param int $callerOffset How far up the callstack is the original
	 *    caller. 0 = function that called getCallerDescription()
	 * @return array Array with two keys: 'file' and 'func'
	 */
	private static function getCallerDescription( $callerOffset ) {
		$callers = wfDebugBacktrace();

		if ( isset( $callers[$callerOffset] ) ) {
			$callerfile = $callers[$callerOffset];
			if ( isset( $callerfile['file'] ) && isset( $callerfile['line'] ) ) {
				$file = $callerfile['file'] . ' at line ' . $callerfile['line'];
			} else {
				$file = '(internal function)';
			}
		} else {
			$file = '(unknown location)';
		}

		if ( isset( $callers[$callerOffset + 1] ) ) {
			$callerfunc = $callers[$callerOffset + 1];
			$func = '';
			if ( isset( $callerfunc['class'] ) ) {
				$func .= $callerfunc['class'] . '::';
			}
			if ( isset( $callerfunc['function'] ) ) {
				$func .= $callerfunc['function'];
			}
		} else {
			$func = 'unknown';
		}

		return [ 'file' => $file, 'func' => $func ];
	}

	/**
	 * Append a caller description to an error message
	 *
	 * @param string $msg
	 * @param array $caller Caller description from getCallerDescription()
	 * @return string
	 */
	private static function formatCallerDescription( $msg, $caller ) {
		// When changing this, update the below parseCallerDescription() method  as well.
		return $msg . ' [Called from ' . $caller['func'] . ' in ' . $caller['file'] . ']';
	}

	/**
	 * Append a caller description to an error message
	 *
	 * @internal For use by MWExceptionHandler to override 'exception.file' in error logs.
	 * @param string $msg Formatted message from formatCallerDescription() and getCallerDescription()
	 * @return null|array<string,string> Null if unable to recognise all parts, or array with:
	 *  - 'file': string of file path
	 *  - 'line': string of line number
	 *  - 'func': string of function or method name
	 *  - 'message': Re-formatted version of $msg for use with ErrorException,
	 *  so as to not include file/line twice.
	 */
	public static function parseCallerDescription( $msg ) {
		$match = null;
		preg_match( '/(.*) \[Called from ([^ ]+) in ([^ ]+) at line (\d+)\]$/', $msg, $match );
		if ( $match ) {
			return [
				'message' => "{$match[1]} [Called from {$match[2]}]",
				'func' => $match[2],
				'file' => $match[3],
				'line' => $match[4],
			];
		} else {
			return null;
		}
	}

	/**
	 * Send a message to the debug log and optionally also trigger a PHP
	 * error, depending on the $level argument.
	 *
	 * @param string $msg Message to send
	 * @param string $group Log group on which to send the message
	 * @param int|bool $level Error level to use; set to false to not trigger an error
	 */
	private static function sendMessage( $msg, $group, $level ) {
		if ( $level !== false ) {
			trigger_error( $msg, $level );
		}

		wfDebugLog( $group, $msg );
	}

	/**
	 * This method receives messages from LoggerFactory, wfDebugLog, and MWExceptionHandler.
	 *
	 * Do NOT call this method directly.
	 *
	 * @internal For use by MWExceptionHandler and LegacyLogger only
	 * @since 1.19
	 * @param string $str
	 * @param array $context
	 */
	public static function debugMsg( $str, $context = [] ) {
		global $wgDebugComments, $wgShowDebug;

		if ( self::$enabled || $wgDebugComments || $wgShowDebug ) {
			if ( $context ) {
				$prefix = '';
				if ( isset( $context['prefix'] ) ) {
					$prefix = $context['prefix'];
				} elseif ( isset( $context['channel'] ) && $context['channel'] !== 'wfDebug' ) {
					$prefix = "[{$context['channel']}] ";
				}
				if ( isset( $context['seconds_elapsed'] ) && isset( $context['memory_used'] ) ) {
					$prefix .= "{$context['seconds_elapsed']} {$context['memory_used']}  ";
				}
				$str = LegacyLogger::interpolate( $str, $context );
				$str = $prefix . $str;
			}
			$str = rtrim( UtfNormal\Validator::cleanUp( $str ) );
			self::$debug[] = $str;
			if ( isset( $context['channel'] ) && $context['channel'] === 'error' ) {
				$message = isset( $context['exception'] )
					? $context['exception']->getMessage()
					: $str;
				$real = self::parseCallerDescription( $message );
				if ( $real ) {
					// from wfLogWarning()
					$message = $real['message'];
					$caller = $real['func'];
				} else {
					$trace = isset( $context['exception'] ) ? $context['exception']->getTrace() : [];
					if ( ( $trace[5]['function'] ?? null ) === 'wfDeprecated' ) {
						// from MWExceptionHandler/trigger_error/MWDebug/MWDebug/MWDebug/wfDeprecated()
						$offset = 6;
					} elseif ( ( $trace[1]['function'] ?? null ) === 'trigger_error' ) {
						// from trigger_error
						$offset = 2;
					} else {
						// built-in PHP error
						$offset = 1;
					}
					$frame = $trace[$offset] ?? $trace[0];
					$caller = ( isset( $frame['class'] ) ? $frame['class'] . '::' : '' )
						. $frame['function'];
				}

				self::$log[] = [
					'msg' => htmlspecialchars( $message ),
					'type' => 'warn',
					'caller' => $caller,
				];
			}
		}
	}

	/**
	 * Begins profiling on a database query
	 *
	 * @since 1.19
	 * @param string $sql
	 * @param string $function
	 * @param float $runTime Query run time
	 * @param string $dbhost
	 * @return bool True if debugger is enabled, false otherwise
	 */
	public static function query( $sql, $function, $runTime, $dbhost ) {
		if ( !self::$enabled ) {
			return false;
		}

		// Replace invalid UTF-8 chars with a square UTF-8 character
		// This prevents json_encode from erroring out due to binary SQL data
		$sql = preg_replace(
			'/(
				[\xC0-\xC1] # Invalid UTF-8 Bytes
				| [\xF5-\xFF] # Invalid UTF-8 Bytes
				| \xE0[\x80-\x9F] # Overlong encoding of prior code point
				| \xF0[\x80-\x8F] # Overlong encoding of prior code point
				| [\xC2-\xDF](?![\x80-\xBF]) # Invalid UTF-8 Sequence Start
				| [\xE0-\xEF](?![\x80-\xBF]{2}) # Invalid UTF-8 Sequence Start
				| [\xF0-\xF4](?![\x80-\xBF]{3}) # Invalid UTF-8 Sequence Start
				| (?<=[\x0-\x7F\xF5-\xFF])[\x80-\xBF] # Invalid UTF-8 Sequence Middle
				| (?<![\xC2-\xDF]|[\xE0-\xEF]|[\xE0-\xEF][\x80-\xBF]|[\xF0-\xF4]
					| [\xF0-\xF4][\x80-\xBF]|[\xF0-\xF4][\x80-\xBF]{2})[\x80-\xBF] # Overlong Sequence
				| (?<=[\xE0-\xEF])[\x80-\xBF](?![\x80-\xBF]) # Short 3 byte sequence
				| (?<=[\xF0-\xF4])[\x80-\xBF](?![\x80-\xBF]{2}) # Short 4 byte sequence
				| (?<=[\xF0-\xF4][\x80-\xBF])[\x80-\xBF](?![\x80-\xBF]) # Short 4 byte sequence (2)
			)/x',
			'■',
			$sql
		);

		// last check for invalid utf8
		$sql = UtfNormal\Validator::cleanUp( $sql );

		self::$query[] = [
			'sql' => "$dbhost: $sql",
			'function' => $function,
			'time' => $runTime,
		];

		return true;
	}

	/**
	 * Returns a list of files included, along with their size
	 *
	 * @param IContextSource $context
	 * @return array
	 */
	protected static function getFilesIncluded( IContextSource $context ) {
		$files = get_included_files();
		$fileList = [];
		foreach ( $files as $file ) {
			// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
			$size = @filesize( $file );
			if ( $size === false ) {
				// Certain files that have been included might then be deleted. This is especially likely to happen
				// in tests, see T351986.
				// Just use a size of 0, but include these files here to try and be as useful as possible.
				$size = 0;
			}
			$fileList[] = [
				'name' => $file,
				'size' => $context->getLanguage()->formatSize( $size ),
			];
		}

		return $fileList;
	}

	/**
	 * Returns the HTML to add to the page for the toolbar
	 *
	 * @since 1.19
	 * @param IContextSource $context
	 * @return WrappedStringList
	 */
	public static function getDebugHTML( IContextSource $context ) {
		global $wgDebugComments;

		$html = [];

		if ( self::$enabled ) {
			self::log( 'MWDebug output complete' );
			$debugInfo = self::getDebugInfo( $context );

			// Cannot use OutputPage::addJsConfigVars because those are already outputted
			// by the time this method is called.
			$html[] = ResourceLoader::makeInlineScript(
				'mw.config.set('
					. FormatJson::encode( [ 'debugInfo' => $debugInfo ] )
					. ');'
			);
		}

		if ( $wgDebugComments ) {
			$html[] = '<!-- Debug output:';
			foreach ( self::$debug as $line ) {
				$html[] = htmlspecialchars( $line, ENT_NOQUOTES );
			}
			$html[] = '-->';
		}

		return WrappedString::join( "\n", $html );
	}

	/**
	 * Generate debug log in HTML for displaying at the bottom of the main
	 * content area.
	 * If $wgShowDebug is false, an empty string is always returned.
	 *
	 * @since 1.20
	 * @return WrappedStringList HTML fragment
	 */
	public static function getHTMLDebugLog() {
		global $wgShowDebug;

		$html = [];
		if ( $wgShowDebug ) {
			$html[] = Html::openElement( 'div', [ 'id' => 'mw-html-debug-log' ] );
			$html[] = "<hr />\n<strong>Debug data:</strong><ul id=\"mw-debug-html\">";

			foreach ( self::$debug as $line ) {
				$display = nl2br( htmlspecialchars( trim( $line ) ) );

				$html[] = "<li><code>$display</code></li>";
			}

			$html[] = '</ul>';
			$html[] = '</div>';
		}
		return WrappedString::join( "\n", $html );
	}

	/**
	 * Append the debug info to given ApiResult
	 *
	 * @param IContextSource $context
	 * @param ApiResult $result
	 */
	public static function appendDebugInfoToApiResult( IContextSource $context, ApiResult $result ) {
		if ( !self::$enabled ) {
			return;
		}

		// output errors as debug info, when display_errors is on
		// this is necessary for all non html output of the api, because that clears all errors first
		$obContents = ob_get_contents();
		if ( $obContents ) {
			$obContentArray = explode( '<br />', $obContents );
			foreach ( $obContentArray as $obContent ) {
				if ( trim( $obContent ) ) {
					self::debugMsg( Sanitizer::stripAllTags( $obContent ) );
				}
			}
		}

		self::log( 'MWDebug output complete' );
		$debugInfo = self::getDebugInfo( $context );

		ApiResult::setIndexedTagName( $debugInfo, 'debuginfo' );
		ApiResult::setIndexedTagName( $debugInfo['log'], 'line' );
		ApiResult::setIndexedTagName( $debugInfo['debugLog'], 'msg' );
		ApiResult::setIndexedTagName( $debugInfo['queries'], 'query' );
		ApiResult::setIndexedTagName( $debugInfo['includes'], 'queries' );
		$result->addValue( null, 'debuginfo', $debugInfo );
	}

	/**
	 * Returns the HTML to add to the page for the toolbar
	 *
	 * @param IContextSource $context
	 * @return array
	 */
	public static function getDebugInfo( IContextSource $context ) {
		if ( !self::$enabled ) {
			return [];
		}

		$request = $context->getRequest();

		$branch = GitInfo::currentBranch();
		if ( GitInfo::isSHA1( $branch ) ) {
			// If it's a detached HEAD, the SHA1 will already be
			// included in the MW version, so don't show it.
			$branch = false;
		}

		return [
			'mwVersion' => MW_VERSION,
			'phpEngine' => 'PHP',
			'phpVersion' => PHP_VERSION,
			'gitRevision' => GitInfo::headSHA1(),
			'gitBranch' => $branch,
			'gitViewUrl' => GitInfo::headViewUrl(),
			'time' => $request->getElapsedTime(),
			'log' => self::$log,
			'debugLog' => self::$debug,
			'queries' => self::$query,
			'request' => [
				'method' => $request->getMethod(),
				'url' => $request->getRequestURL(),
				'headers' => $request->getAllHeaders(),
				'params' => $request->getValues(),
			],
			'memory' => $context->getLanguage()->formatSize( memory_get_usage() ),
			'memoryPeak' => $context->getLanguage()->formatSize( memory_get_peak_usage() ),
			'includes' => self::getFilesIncluded( $context ),
		];
	}
}

/** @deprecated class alias since 1.43 */
class_alias( MWDebug::class, 'MWDebug' );
