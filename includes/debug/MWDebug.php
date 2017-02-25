<?php
/**
 * Debug toolbar related code.
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

use MediaWiki\Logger\LegacyLogger;

/**
 * New debugger system that outputs a toolbar on page view.
 *
 * By default, most methods do nothing ( self::$enabled = false ). You have
 * to explicitly call MWDebug::init() to enabled them.
 *
 * @since 1.19
 */
class MWDebug {
	/**
	 * Log lines
	 *
	 * @var array $log
	 */
	protected static $log = [];

	/**
	 * Debug messages from wfDebug().
	 *
	 * @var array $debug
	 */
	protected static $debug = [];

	/**
	 * SQL statements of the database queries.
	 *
	 * @var array $query
	 */
	protected static $query = [];

	/**
	 * Is the debugger enabled?
	 *
	 * @var bool $enabled
	 */
	protected static $enabled = false;

	/**
	 * Array of functions that have already been warned, formatted
	 * function-caller to prevent a buttload of warnings
	 *
	 * @var array $deprecationWarnings
	 */
	protected static $deprecationWarnings = [];

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

		self::sendMessage( $msg, $callerDescription, 'warning', $level );

		if ( self::$enabled ) {
			self::$log[] = [
				'msg' => htmlspecialchars( $msg ),
				'type' => 'warn',
				'caller' => $callerDescription['func'],
			];
		}
	}

	/**
	 * Show a warning that $function is deprecated.
	 * This will send it to the following locations:
	 * - Debug toolbar, with one item per function and caller, if $wgDebugToolbar
	 *   is set to true.
	 * - PHP's error log, with level E_USER_DEPRECATED, if $wgDevelopmentWarnings
	 *   is set to true.
	 * - MediaWiki's debug log, if $wgDevelopmentWarnings is set to false.
	 *
	 * @since 1.19
	 * @param string $function Function that is deprecated.
	 * @param string|bool $version Version in which the function was deprecated.
	 * @param string|bool $component Component to which the function belongs.
	 *    If false, it is assumbed the function is in MediaWiki core.
	 * @param int $callerOffset How far up the callstack is the original
	 *    caller. 2 = function that called the function that called
	 *    MWDebug::deprecated() (Added in 1.20).
	 */
	public static function deprecated( $function, $version = false,
		$component = false, $callerOffset = 2
	) {
		$callerDescription = self::getCallerDescription( $callerOffset );
		$callerFunc = $callerDescription['func'];

		$sendToLog = true;

		// Check to see if there already was a warning about this function
		if ( isset( self::$deprecationWarnings[$function][$callerFunc] ) ) {
			return;
		} elseif ( isset( self::$deprecationWarnings[$function] ) ) {
			if ( self::$enabled ) {
				$sendToLog = false;
			} else {
				return;
			}
		}

		self::$deprecationWarnings[$function][$callerFunc] = true;

		if ( $version ) {
			global $wgDeprecationReleaseLimit;
			if ( $wgDeprecationReleaseLimit && $component === false ) {
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

			$component = $component === false ? 'MediaWiki' : $component;
			$msg = "Use of $function was deprecated in $component $version.";
		} else {
			$msg = "Use of $function is deprecated.";
		}

		if ( $sendToLog ) {
			global $wgDevelopmentWarnings; // we could have a more specific $wgDeprecationWarnings setting.
			self::sendMessage(
				$msg,
				$callerDescription,
				'deprecated',
				$wgDevelopmentWarnings ? E_USER_DEPRECATED : false
			);
		}

		if ( self::$enabled ) {
			$logMsg = htmlspecialchars( $msg ) .
				Html::rawElement( 'div', [ 'class' => 'mw-debug-backtrace' ],
					Html::element( 'span', [], 'Backtrace:' ) . wfBacktrace()
				);

			self::$log[] = [
				'msg' => $logMsg,
				'type' => 'deprecated',
				'caller' => $callerFunc,
			];
		}
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
	 * Send a message to the debug log and optionally also trigger a PHP
	 * error, depending on the $level argument.
	 *
	 * @param string $msg Message to send
	 * @param array $caller Caller description get from getCallerDescription()
	 * @param string $group Log group on which to send the message
	 * @param int|bool $level Error level to use; set to false to not trigger an error
	 */
	private static function sendMessage( $msg, $caller, $group, $level ) {
		$msg .= ' [Called from ' . $caller['func'] . ' in ' . $caller['file'] . ']';

		if ( $level !== false ) {
			trigger_error( $msg, $level );
		}

		wfDebugLog( $group, $msg, 'private' );
	}

	/**
	 * This is a method to pass messages from wfDebug to the pretty debugger.
	 * Do NOT use this method, use MWDebug::log or wfDebug()
	 *
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
			self::$debug[] = rtrim( UtfNormal\Validator::cleanUp( $str ) );
		}
	}

	/**
	 * Begins profiling on a database query
	 *
	 * @since 1.19
	 * @param string $sql
	 * @param string $function
	 * @param bool $isMaster
	 * @param float $runTime Query run time
	 * @return int ID number of the query to pass to queryTime or -1 if the
	 *  debugger is disabled
	 */
	public static function query( $sql, $function, $isMaster, $runTime ) {
		if ( !self::$enabled ) {
			return -1;
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
			'sql' => $sql,
			'function' => $function,
			'master' => (bool)$isMaster,
			'time' => $runTime,
		];

		return count( self::$query ) - 1;
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
			$size = filesize( $file );
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
	 * @return string
	 */
	public static function getDebugHTML( IContextSource $context ) {
		global $wgDebugComments;

		$html = '';

		if ( self::$enabled ) {
			MWDebug::log( 'MWDebug output complete' );
			$debugInfo = self::getDebugInfo( $context );

			// Cannot use OutputPage::addJsConfigVars because those are already outputted
			// by the time this method is called.
			$html = ResourceLoader::makeInlineScript(
				ResourceLoader::makeConfigSetScript( [ 'debugInfo' => $debugInfo ] )
			);
		}

		if ( $wgDebugComments ) {
			$html .= "<!-- Debug output:\n" .
				htmlspecialchars( implode( "\n", self::$debug ), ENT_NOQUOTES ) .
				"\n\n-->";
		}

		return $html;
	}

	/**
	 * Generate debug log in HTML for displaying at the bottom of the main
	 * content area.
	 * If $wgShowDebug is false, an empty string is always returned.
	 *
	 * @since 1.20
	 * @return string HTML fragment
	 */
	public static function getHTMLDebugLog() {
		global $wgShowDebug;

		if ( !$wgShowDebug ) {
			return '';
		}

		$ret = "\n<hr />\n<strong>Debug data:</strong><ul id=\"mw-debug-html\">\n";

		foreach ( self::$debug as $line ) {
			$display = nl2br( htmlspecialchars( trim( $line ) ) );

			$ret .= "<li><code>$display</code></li>\n";
		}

		$ret .= '</ul>' . "\n";

		return $ret;
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

		MWDebug::log( 'MWDebug output complete' );
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

		global $wgVersion, $wgRequestTime;
		$request = $context->getRequest();

		// HHVM's reported memory usage from memory_get_peak_usage()
		// is not useful when passing false, but we continue passing
		// false for consistency of historical data in zend.
		// see: https://github.com/facebook/hhvm/issues/2257#issuecomment-39362246
		$realMemoryUsage = wfIsHHVM();

		$branch = GitInfo::currentBranch();
		if ( GitInfo::isSHA1( $branch ) ) {
			// If it's a detached HEAD, the SHA1 will already be
			// included in the MW version, so don't show it.
			$branch = false;
		}

		return [
			'mwVersion' => $wgVersion,
			'phpEngine' => wfIsHHVM() ? 'HHVM' : 'PHP',
			'phpVersion' => wfIsHHVM() ? HHVM_VERSION : PHP_VERSION,
			'gitRevision' => GitInfo::headSHA1(),
			'gitBranch' => $branch,
			'gitViewUrl' => GitInfo::headViewUrl(),
			'time' => microtime( true ) - $wgRequestTime,
			'log' => self::$log,
			'debugLog' => self::$debug,
			'queries' => self::$query,
			'request' => [
				'method' => $request->getMethod(),
				'url' => $request->getRequestURL(),
				'headers' => $request->getAllHeaders(),
				'params' => $request->getValues(),
			],
			'memory' => $context->getLanguage()->formatSize( memory_get_usage( $realMemoryUsage ) ),
			'memoryPeak' => $context->getLanguage()->formatSize( memory_get_peak_usage( $realMemoryUsage ) ),
			'includes' => self::getFilesIncluded( $context ),
		];
	}
}
