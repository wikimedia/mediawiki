<?php

/**
 * New debugger system that outputs a toolbar on page view
 *
 * @todo Profiler support
 */
class MWDebug {

	/**
	 * Log lines
	 *
	 * @var array
	 */
	protected static $log = array();

	/**
	 * Debug messages from wfDebug()
	 *
	 * @var array
	 */
	protected static $debug = array();

	/**
	 * Queries
	 *
	 * @var array
	 */
	protected static $query = array();

	/**
	 * Request information
	 *
	 * @var array
	 */
	protected static $request = array();

	/**
	 * Is the debugger enabled?
	 *
	 * @var bool
	 */
	protected static $enabled = true;

	/**
	 * Array of functions that have already been warned, formatted
	 * function-caller to prevent a buttload of warnings
	 *
	 * @var array
	 */
	protected static $deprecationWarnings = array();

	/**
	 * Called in Setup.php, initializes the debugger if it is enabled with
	 * $wgDebugToolbar
	 */
	public static function init() {
		global $wgDebugToolbar;

		if ( !$wgDebugToolbar ) {
			self::$enabled = false;
			return;
		}

		RequestContext::getMain()->getOutput()->addModules( 'mediawiki.debug' );
	}

	/**
	 * Adds a line to the log
	 *
	 * @todo Add support for passing objects
	 *
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
	 * Adds a warning entry to the log
	 *
	 * @param $msg
	 * @param int $callerOffset
	 * @return mixed
	 */
	public static function warning( $msg, $callerOffset = 1 ) {
		if ( !self::$enabled ) {
			return;
		}

		// Check to see if there was already a deprecation notice, so not to
		// get a duplicate warning
		if ( count( self::$log ) ) {
			$lastLog = self::$log[ count( self::$log ) - 1 ];
			if ( $lastLog['type'] == 'deprecated' && $lastLog['caller'] == wfGetCaller( $callerOffset + 1 ) ) {
				return;
			}
		}

		self::$log[] = array(
			'msg' => htmlspecialchars( $msg ),
			'type' => 'warn',
			'caller' => wfGetCaller( $callerOffset ),
		);
	}

	/**
	 * Adds a depreciation entry to the log, along with a backtrace
	 *
	 * @param $function
	 * @param $version
	 * @param $component
	 * @return mixed
	 */
	public static function deprecated( $function, $version, $component ) {
		if ( !self::$enabled ) {
			return;
		}

		// Chain: This function -> wfDeprecated -> deprecatedFunction -> caller
		$caller = wfGetCaller( 4 );

		// Check to see if there already was a warning about this function
		$functionString = "$function-$caller";
		if ( in_array( $functionString, self::$deprecationWarnings ) ) {
			return;
		}

		$version = $version === false ? '(unknown version)' : $version;
		$component = $component === false ? 'MediaWiki' : $component;
		$msg = htmlspecialchars( "Use of function $function was deprecated in $component $version" );
		$msg .= Html::rawElement( 'div', array( 'class' => 'mw-debug-backtrace' ),
			Html::element( 'span', array(), 'Backtrace:' )
			 . wfBacktrace()
		);

		self::$deprecationWarnings[] = $functionString;
		self::$log[] = array(
			'msg' => $msg,
			'type' => 'deprecated',
			'caller' => $caller,
		);
	}

	/**
	 * This is a method to pass messages from wfDebug to the pretty debugger.
	 * Do NOT use this method, use MWDebug::log or wfDebug()
	 *
	 * @param $str string
	 */
	public static function debugMsg( $str ) {
		if ( !self::$enabled ) {
			return;
		}

		self::$debug[] = trim( $str );
	}

	/**
	 * Begins profiling on a database query
	 *
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
			'master' => (bool) $isMaster,
			'time' > 0.0,
			'_start' => wfTime(),
		);

		return count( self::$query ) - 1;
	}

	/**
	 * Calculates how long a query took.
	 *
	 * @param $id int
	 */
	public static function queryTime( $id ) {
		if ( $id === -1 || !self::$enabled ) {
			return;
		}

		self::$query[$id]['time'] = wfTime() - self::$query[$id]['_start'];
		unset( self::$query[$id]['_start'] );
	}

	/**
	 * Processes a WebRequest object
	 *
	 * @param $request WebRequest
	 */
	public static function processRequest( WebRequest $request ) {
		if ( !self::$enabled ) {
			return;
		}

		self::$request = array(
			'method' => $_SERVER['REQUEST_METHOD'],
			'url' => $request->getRequestURL(),
			'headers' => $request->getAllHeaders(),
			'params' => $request->getValues(),
		);
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
	 * @param $context IContextSource
	 * @return string
	 */
	public static function getDebugHTML( IContextSource $context ) {
		if ( !self::$enabled ) {
			return '';
		}

		global $wgVersion, $wgRequestTime;
		wfWarn( 'johnduhart is pretty cool' );
		wfDeprecated( __METHOD__, '1.19' );
		MWDebug::log( 'MWDebug output complete' );
		$debugInfo = array(
			'mwVersion' => $wgVersion,
			'phpVersion' => PHP_VERSION,
			'time' => wfTime() - $wgRequestTime,
			'log' => self::$log,
			'debugLog' => self::$debug,
			'queries' => self::$query,
			'request' => self::$request,
			'memory' => $context->getLanguage()->formatSize( memory_get_usage() ),
			'memoryPeak' => $context->getLanguage()->formatSize( memory_get_peak_usage() ),
			'includes' => self::getFilesIncluded( $context ),
		);
		// TODO: Clean this up
		$html = Html::openElement( 'script' );
		$html .= 'var debugInfo = ' . Xml::encodeJsVar( $debugInfo ) . ';';
		$html .= " $(function() { mw.loader.using( 'mediawiki.debug', function() { mw.Debug.init( debugInfo ) } ); }); ";
		$html .= Html::closeElement( 'script' );

		return $html;
	}
}