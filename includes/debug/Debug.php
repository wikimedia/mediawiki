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

/**
 * New debugger system that outputs a toolbar on page view.
 *
 * By default, most methods do nothing ( self::$enabled = false ). You have
 * to explicitly call MWDebug::init() to enabled them.
 *
 * @todo Profiler support
 *
 * @since 1.19
 */
class MWDebug {

	/**
	 * Log lines
	 *
	 * @var array $log
	 */
	protected static $log = array();

	/**
	 * Debug messages from wfDebug().
	 *
	 * @var array $debug
	 */
	protected static $debug = array();

	/**
	 * SQL statements of the databses queries.
	 *
	 * @var array $query
	 */
	protected static $query = array();

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
	protected static $deprecationWarnings = array();

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
	 * Add ResourceLoader modules to the OutputPage object if debugging is
	 * enabled.
	 *
	 * @since 1.19
	 * @param $out OutputPage
	 */
	public static function addModules( OutputPage $out ) {
		if ( self::$enabled ) {
			$out->addModules( 'mediawiki.debug.init' );
		}
	}

	/**
	 * Adds a line to the log
	 *
	 * @todo Add support for passing objects
	 *
	 * @since 1.19
	 * @param $str string
	 */
	public static function log( $str ) {
		if ( !self::$enabled ) {
			return;
		}

		self::$log[] = array(
			'msg' => htmlspecialchars( $str ),
			'type' => 'log',
			'caller' => wfGetCaller(),
		);
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
		self::$log = array();
		self::$deprecationWarnings = array();
	}

	/**
	 * Adds a warning entry to the log
	 *
	 * @since 1.19
	 * @param $msg string
	 * @param $callerOffset int
	 * @param $level int A PHP error level. See sendWarning()
	 * @param $log string: 'production' will always trigger a php error, 'auto'
	 *        will trigger an error if $wgDevelopmentWarnings is true, and 'debug'
	 *        will only write to the debug log(s).
	 *
	 * @return mixed
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

		self::sendWarning( $msg, $callerDescription, $level );

		if ( self::$enabled ) {
			self::$log[] = array(
				'msg' => htmlspecialchars( $msg ),
				'type' => 'warn',
				'caller' => $callerDescription['func'],
			);
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
	 *     If false, it is assumbed the function is in MediaWiki core.
	 * @param $callerOffset integer: How far up the callstack is the original
	 *    caller. 2 = function that called the function that called
	 *    MWDebug::deprecated() (Added in 1.20).
	 * @return mixed
	 */
	public static function deprecated( $function, $version = false, $component = false, $callerOffset = 2 ) {
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
			self::sendWarning( $msg, $callerDescription, $wgDevelopmentWarnings ? E_USER_DEPRECATED : false );
		}

		if ( self::$enabled ) {
			$logMsg = htmlspecialchars( $msg ) .
				Html::rawElement( 'div', array( 'class' => 'mw-debug-backtrace' ),
					Html::element( 'span', array(), 'Backtrace:' ) . wfBacktrace()
				);

			self::$log[] = array(
				'msg' => $logMsg,
				'type' => 'deprecated',
				'caller' => $callerFunc,
			);
		}
	}

	/**
	 * Get an array describing the calling function at a specified offset.
	 *
	 * @param $callerOffset integer: How far up the callstack is the original
	 *    caller. 0 = function that called getCallerDescription()
	 * @return array with two keys: 'file' and 'func'
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

		return array( 'file' => $file, 'func' => $func );
	}

	/**
	 * Send a warning to the debug log and optionally also trigger a PHP
	 * error, depending on the $level argument.
	 *
	 * @param $msg string Message to send
	 * @param $caller array caller description get from getCallerDescription()
	 * @param $level int|bool error level to use; set to false to not trigger an error
	 */
	private static function sendWarning( $msg, $caller, $level ) {
		$msg .= ' [Called from ' . $caller['func'] . ' in ' . $caller['file'] . ']';

		if ( $level !== false ) {
			trigger_error( $msg, $level );
		}

		wfDebug( "$msg\n" );
	}

	/**
	 * This is a method to pass messages from wfDebug to the pretty debugger.
	 * Do NOT use this method, use MWDebug::log or wfDebug()
	 *
	 * @since 1.19
	 * @param $str string
	 */
	public static function debugMsg( $str ) {
		global $wgDebugComments, $wgShowDebug;

		if ( self::$enabled || $wgDebugComments || $wgShowDebug ) {
			self::$debug[] = rtrim( UtfNormal::cleanUp( $str ) );
		}
	}

	/**
	 * Begins profiling on a database query
	 *
	 * @since 1.19
	 * @param $sql string
	 * @param $function string
	 * @param $isMaster bool
	 * @return int ID number of the query to pass to queryTime or -1 if the
	 *  debugger is disabled
	 */
	public static function query( $sql, $function, $isMaster ) {
		if ( !self::$enabled ) {
			return -1;
		}

		self::$query[] = array(
			'sql' => $sql,
			'function' => $function,
			'master' => (bool)$isMaster,
			'time' => 0.0,
			'_start' => microtime( true ),
		);

		return count( self::$query ) - 1;
	}

	/**
	 * Calculates how long a query took.
	 *
	 * @since 1.19
	 * @param $id int
	 */
	public static function queryTime( $id ) {
		if ( $id === -1 || !self::$enabled ) {
			return;
		}

		self::$query[$id]['time'] = microtime( true ) - self::$query[$id]['_start'];
		unset( self::$query[$id]['_start'] );
	}

	/**
	 * Returns a list of files included, along with their size
	 *
	 * @param $context IContextSource
	 * @return array
	 */
	protected static function getFilesIncluded( IContextSource $context ) {
		$files = get_included_files();
		$fileList = array();
		foreach ( $files as $file ) {
			$size = filesize( $file );
			$fileList[] = array(
				'name' => $file,
				'size' => $context->getLanguage()->formatSize( $size ),
			);
		}

		return $fileList;
	}

	/**
	 * Returns the HTML to add to the page for the toolbar
	 *
	 * @since 1.19
	 * @param $context IContextSource
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
			$html = Html::inlineScript(
				ResourceLoader::makeLoaderConditionalScript(
					ResourceLoader::makeConfigSetScript( array( 'debugInfo' => $debugInfo ) )
				)
			);
		}

		if ( $wgDebugComments ) {
			$html .= "<!-- Debug output:\n" .
				htmlspecialchars( implode( "\n", self::$debug ) ) .
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
		global $wgDebugTimestamps, $wgShowDebug;

		if ( !$wgShowDebug ) {
			return '';
		}

		$curIdent = 0;
		$ret = "\n<hr />\n<strong>Debug data:</strong><ul id=\"mw-debug-html\">\n<li>";

		foreach ( self::$debug as $line ) {
			$pre = '';
			if ( $wgDebugTimestamps ) {
				$matches = array();
				if ( preg_match( '/^(\d+\.\d+ {1,3}\d+.\dM\s{2})/', $line, $matches ) ) {
					$pre = $matches[1];
					$line = substr( $line, strlen( $pre ) );
				}
			}
			$display = ltrim( $line );
			$ident = strlen( $line ) - strlen( $display );
			$diff = $ident - $curIdent;

			$display = $pre . $display;
			if ( $display == '' ) {
				$display = "\xc2\xa0";
			}

			if ( !$ident && $diff < 0 && substr( $display, 0, 9 ) != 'Entering ' && substr( $display, 0, 8 ) != 'Exiting ' ) {
				$ident = $curIdent;
				$diff = 0;
				$display = '<span style="background:yellow;">' . nl2br( htmlspecialchars( $display ) ) . '</span>';
			} else {
				$display = nl2br( htmlspecialchars( $display ) );
			}

			if ( $diff < 0 ) {
				$ret .= str_repeat( "</li></ul>\n", -$diff ) . "</li><li>\n";
			} elseif ( $diff == 0 ) {
				$ret .= "</li><li>\n";
			} else {
				$ret .= str_repeat( "<ul><li>\n", $diff );
			}
			$ret .= "<tt>$display</tt>\n";

			$curIdent = $ident;
		}

		$ret .= str_repeat( '</li></ul>', $curIdent ) . "</li>\n</ul>\n";

		return $ret;
	}

	/**
	 * Append the debug info to given ApiResult
	 *
	 * @param $context IContextSource
	 * @param $result ApiResult
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

		$result->setIndexedTagName( $debugInfo, 'debuginfo' );
		$result->setIndexedTagName( $debugInfo['log'], 'line' );
		$result->setIndexedTagName( $debugInfo['debugLog'], 'msg' );
		$result->setIndexedTagName( $debugInfo['queries'], 'query' );
		$result->setIndexedTagName( $debugInfo['includes'], 'queries' );
		$result->addValue( null, 'debuginfo', $debugInfo );
	}

	/**
	 * Returns the HTML to add to the page for the toolbar
	 *
	 * @param $context IContextSource
	 * @return array
	 */
	public static function getDebugInfo( IContextSource $context ) {
		if ( !self::$enabled ) {
			return array();
		}

		global $wgVersion, $wgRequestTime;
		$request = $context->getRequest();
		return array(
			'mwVersion' => $wgVersion,
			'phpVersion' => PHP_VERSION,
			'gitRevision' => GitInfo::headSHA1(),
			'gitBranch' => GitInfo::currentBranch(),
			'gitViewUrl' => GitInfo::headViewUrl(),
			'time' => microtime( true ) - $wgRequestTime,
			'log' => self::$log,
			'debugLog' => self::$debug,
			'queries' => self::$query,
			'request' => array(
				'method' => $request->getMethod(),
				'url' => $request->getRequestURL(),
				'headers' => $request->getAllHeaders(),
				'params' => $request->getValues(),
			),
			'memory' => $context->getLanguage()->formatSize( memory_get_usage() ),
			'memoryPeak' => $context->getLanguage()->formatSize( memory_get_peak_usage() ),
			'includes' => self::getFilesIncluded( $context ),
		);
	}
}
