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
	 * This does nothing atm, there's not frontend for it
	 *
	 * @todo Add error and warning log type
	 * @todo Add support for passing objects
	 *
	 * @param $str string
	 */
	public static function log( $str ) {
		if ( !self::$enabled ) {
			return;
		}

		self::$log[] = $str;
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
		$debugInfo = array(
			'mwVersion' => $wgVersion,
			'phpVersion' => PHP_VERSION,
			'time' => microtime( true ) - $wgRequestTime,
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