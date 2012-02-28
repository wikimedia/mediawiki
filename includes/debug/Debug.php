<?php

/**
 * New debugger system that outputs a toolbar on page view
 *
 * By default, most methods do nothing ( self::$enabled = false ). You have
 * to explicitly call MWDebug::init() to enabled them.
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
	protected static $deprecationWarnings = array();

	/**
	 * Enabled the debugger and load resource module.
	 * This is called by Setup.php when $wgDebugToolbar is true.
	 */
	public static function init() {
		self::$enabled = true;
	}

	/**
	 * Add ResourceLoader modules to the OutputPage object if debugging is
	 * enabled.
	 *
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
	 */
	public static function getLog() {
		return self::$log;
	}

	/**
	 * Clears internal log array and deprecation tracking
	 */
	public static function clearLog() {
		self::$log = array();
		self::$deprecationWarnings = array();
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
		$logCount = count( self::$log );
		if ( $logCount ) {
			$lastLog = self::$log[ $logCount - 1 ];
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
			'time' => 0.0,
			'_start' => microtime( true ),
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
	 * @param $context IContextSource
	 * @return string
	 */
	public static function getDebugHTML( IContextSource $context ) {
		if ( !self::$enabled ) {
			return '';
		}

		global $wgVersion, $wgRequestTime;
		MWDebug::log( 'MWDebug output complete' );
		$request = $context->getRequest();
		$debugInfo = array(
			'mwVersion' => $wgVersion,
			'phpVersion' => PHP_VERSION,
			'time' => microtime( true ) - $wgRequestTime,
			'log' => self::$log,
			'debugLog' => self::$debug,
			'queries' => self::$query,
			'request' => array(
				'method' => $_SERVER['REQUEST_METHOD'],
				'url' => $request->getRequestURL(),
				'headers' => $request->getAllHeaders(),
				'params' => $request->getValues(),
			),
			'memory' => $context->getLanguage()->formatSize( memory_get_usage() ),
			'memoryPeak' => $context->getLanguage()->formatSize( memory_get_peak_usage() ),
			'includes' => self::getFilesIncluded( $context ),
		);

		// Cannot use OutputPage::addJsConfigVars because those are already outputted
		// by the time this method is called.
		$html = Html::inlineScript(
			ResourceLoader::makeLoaderConditionalScript(
				ResourceLoader::makeConfigSetScript( array( 'debugInfo' => $debugInfo ) )
			)
		);

		return $html;
	}
}
