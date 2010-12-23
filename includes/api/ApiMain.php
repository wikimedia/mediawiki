<?php
/**
 *
 *
 * Created on Sep 4, 2006
 *
 * Copyright © 2006 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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
 * @defgroup API API
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( 'ApiBase.php' );
}

/**
 * This is the main API class, used for both external and internal processing.
 * When executed, it will create the requested formatter object,
 * instantiate and execute an object associated with the needed action,
 * and use formatter to print results.
 * In case of an exception, an error message will be printed using the same formatter.
 *
 * To use API from another application, run it using FauxRequest object, in which
 * case any internal exceptions will not be handled but passed up to the caller.
 * After successful execution, use getResult() for the resulting data.
 *
 * @ingroup API
 */
class ApiMain extends ApiBase {

	/**
	 * When no format parameter is given, this format will be used
	 */
	const API_DEFAULT_FORMAT = 'xmlfm';

	/**
	 * List of available modules: action name => module class
	 */
	private static $Modules = array(
		'login' => 'ApiLogin',
		'logout' => 'ApiLogout',
		'query' => 'ApiQuery',
		'expandtemplates' => 'ApiExpandTemplates',
		'parse' => 'ApiParse',
		'opensearch' => 'ApiOpenSearch',
		'feedwatchlist' => 'ApiFeedWatchlist',
		'help' => 'ApiHelp',
		'paraminfo' => 'ApiParamInfo',
		'rsd' => 'ApiRsd',

		// Write modules
		'purge' => 'ApiPurge',
		'rollback' => 'ApiRollback',
		'delete' => 'ApiDelete',
		'undelete' => 'ApiUndelete',
		'protect' => 'ApiProtect',
		'block' => 'ApiBlock',
		'unblock' => 'ApiUnblock',
		'move' => 'ApiMove',
		'edit' => 'ApiEditPage',
		'upload' => 'ApiUpload',
		'emailuser' => 'ApiEmailUser',
		'watch' => 'ApiWatch',
		'patrol' => 'ApiPatrol',
		'import' => 'ApiImport',
		'userrights' => 'ApiUserrights',
	);

	/**
	 * List of available formats: format name => format class
	 */
	private static $Formats = array(
		'json' => 'ApiFormatJson',
		'jsonfm' => 'ApiFormatJson',
		'php' => 'ApiFormatPhp',
		'phpfm' => 'ApiFormatPhp',
		'wddx' => 'ApiFormatWddx',
		'wddxfm' => 'ApiFormatWddx',
		'xml' => 'ApiFormatXml',
		'xmlfm' => 'ApiFormatXml',
		'yaml' => 'ApiFormatYaml',
		'yamlfm' => 'ApiFormatYaml',
		'rawfm' => 'ApiFormatJson',
		'txt' => 'ApiFormatTxt',
		'txtfm' => 'ApiFormatTxt',
		'dbg' => 'ApiFormatDbg',
		'dbgfm' => 'ApiFormatDbg',
		'dump' => 'ApiFormatDump',
		'dumpfm' => 'ApiFormatDump',
	);

	/**
	 * List of user roles that are specifically relevant to the API.
	 * array( 'right' => array ( 'msg'    => 'Some message with a $1',
	 *                           'params' => array ( $someVarToSubst ) ),
	 *                          );
	 */
	private static $mRights = array(
		'writeapi' => array(
			'msg' => 'Use of the write API',
			'params' => array()
		),
		'apihighlimits'	=> array(
			'msg' => 'Use higher limits in API queries (Slow queries: $1 results; Fast queries: $2 results). The limits for slow queries also apply to multivalue parameters.',
			'params' => array( ApiBase::LIMIT_SML2, ApiBase::LIMIT_BIG2 )
		)
	);

	/**
	 * @var ApiFormatBase
	 */
	private $mPrinter;

	private $mModules, $mModuleNames, $mFormats, $mFormatNames;
	private $mResult, $mAction, $mShowVersions, $mEnableWrite, $mRequest;
	private $mInternalMode, $mSquidMaxage, $mModule;

	private $mCacheMode = 'private';
	private $mCacheControl = array();

	/**
	 * Constructs an instance of ApiMain that utilizes the module and format specified by $request.
	 *
	 * @param $request WebRequest - if this is an instance of FauxRequest, errors are thrown and no printing occurs
	 * @param $enableWrite bool should be set to true if the api may modify data
	 */
	public function __construct( $request, $enableWrite = false ) {
		$this->mInternalMode = ( $request instanceof FauxRequest );

		// Special handling for the main module: $parent === $this
		parent::__construct( $this, $this->mInternalMode ? 'main_int' : 'main' );

		if ( !$this->mInternalMode ) {
			// Impose module restrictions.
			// If the current user cannot read,
			// Remove all modules other than login
			global $wgUser;

			if ( $request->getVal( 'callback' ) !== null ) {
				// JSON callback allows cross-site reads.
				// For safety, strip user credentials.
				wfDebug( "API: stripping user credentials for JSON callback\n" );
				$wgUser = new User();
			}
		}

		global $wgAPIModules; // extension modules
		$this->mModules = $wgAPIModules + self::$Modules;

		$this->mModuleNames = array_keys( $this->mModules );
		$this->mFormats = self::$Formats;
		$this->mFormatNames = array_keys( $this->mFormats );

		$this->mResult = new ApiResult( $this );
		$this->mShowVersions = false;
		$this->mEnableWrite = $enableWrite;

		$this->mRequest = &$request;

		$this->mSquidMaxage = - 1; // flag for executeActionWithErrorHandling()
		$this->mCommit = false;
	}

	/**
	 * Return true if the API was started by other PHP code using FauxRequest
	 */
	public function isInternalMode() {
		return $this->mInternalMode;
	}

	/**
	 * Return the request object that contains client's request
	 * @return WebRequest
	 */
	public function getRequest() {
		return $this->mRequest;
	}

	/**
	 * Get the ApiResult object associated with current request
	 *
	 * @return ApiResult
	 */
	public function getResult() {
		return $this->mResult;
	}

	/**
	 * Get the API module object. Only works after executeAction()
	 */
	public function getModule() {
		return $this->mModule;
	}

	/**
	 * Get the result formatter object. Only works after setupExecuteAction()
	 *
	 * @return ApiFormatBase
	 */
	public function getPrinter() {
		return $this->mPrinter;
	}

	/**
	 * Set how long the response should be cached.
	 */
	public function setCacheMaxAge( $maxage ) {
		$this->setCacheControl( array(
			'max-age' => $maxage,
			's-maxage' => $maxage
		) );
	}

	/**
	 * Set the type of caching headers which will be sent.
	 *
	 * @param $mode String One of:
	 *    - 'public':     Cache this object in public caches, if the maxage or smaxage
	 *         parameter is set, or if setCacheMaxAge() was called. If a maximum age is
	 *         not provided by any of these means, the object will be private.
	 *    - 'private':    Cache this object only in private client-side caches.
	 *    - 'anon-public-user-private': Make this object cacheable for logged-out
	 *         users, but private for logged-in users. IMPORTANT: If this is set, it must be
	 *         set consistently for a given URL, it cannot be set differently depending on
	 *         things like the contents of the database, or whether the user is logged in.
	 *
	 *  If the wiki does not allow anonymous users to read it, the mode set here
	 *  will be ignored, and private caching headers will always be sent. In other words,
	 *  the "public" mode is equivalent to saying that the data sent is as public as a page
	 *  view.
	 *
	 *  For user-dependent data, the private mode should generally be used. The
	 *  anon-public-user-private mode should only be used where there is a particularly
	 *  good performance reason for caching the anonymous response, but where the
	 *  response to logged-in users may differ, or may contain private data.
	 *
	 *  If this function is never called, then the default will be the private mode.
	 */
	public function setCacheMode( $mode ) {
		if ( !in_array( $mode, array( 'private', 'public', 'anon-public-user-private' ) ) ) {
			wfDebug( __METHOD__ . ": unrecognised cache mode \"$mode\"\n" );
			// Ignore for forwards-compatibility
			return;
		}

		if ( !in_array( 'read', User::getGroupPermissions( array( '*' ) ), true ) ) {
			// Private wiki, only private headers
			if ( $mode !== 'private' ) {
				wfDebug( __METHOD__ . ": ignoring request for $mode cache mode, private wiki\n" );
				return;
			}
		}

		wfDebug( __METHOD__ . ": setting cache mode $mode\n" );
		$this->mCacheMode = $mode;
	}

	/**
	 * @deprecated Private caching is now the default, so there is usually no
	 * need to call this function. If there is a need, you can use
	 * $this->setCacheMode('private')
	 */
	public function setCachePrivate() {
		$this->setCacheMode( 'private' );
	}

	/**
	 * Set directives (key/value pairs) for the Cache-Control header.
	 * Boolean values will be formatted as such, by including or omitting
	 * without an equals sign.
	 *
	 * Cache control values set here will only be used if the cache mode is not
	 * private, see setCacheMode().
	 */
	public function setCacheControl( $directives ) {
		$this->mCacheControl = $directives + $this->mCacheControl;
	}

	/**
	 * Make sure Vary: Cookie and friends are set. Use this when the output of a request
	 * may be cached for anons but may not be cached for logged-in users.
	 *
	 * WARNING: This function must be called CONSISTENTLY for a given URL. This means that a
	 * given URL must either always or never call this function; if it sometimes does and
	 * sometimes doesn't, stuff will break.
	 *
	 * @deprecated Use setCacheMode( 'anon-public-user-private' )
	 */
	public function setVaryCookie() {
		$this->setCacheMode( 'anon-public-user-private' );
	}

	/**
	 * Create an instance of an output formatter by its name
	 */
	public function createPrinterByName( $format ) {
		if ( !isset( $this->mFormats[$format] ) ) {
			$this->dieUsage( "Unrecognized format: {$format}", 'unknown_format' );
		}
		return new $this->mFormats[$format] ( $this, $format );
	}

	/**
	 * Execute api request. Any errors will be handled if the API was called by the remote client.
	 */
	public function execute() {
		$this->profileIn();
		if ( $this->mInternalMode ) {
			$this->executeAction();
		} else {
			$this->executeActionWithErrorHandling();
		}

		$this->profileOut();
	}

	/**
	 * Execute an action, and in case of an error, erase whatever partial results
	 * have been accumulated, and replace it with an error message and a help screen.
	 */
	protected function executeActionWithErrorHandling() {
		// In case an error occurs during data output,
		// clear the output buffer and print just the error information
		ob_start();

		try {
			$this->executeAction();
		} catch ( Exception $e ) {
			// Log it
			if ( $e instanceof MWException ) {
				wfDebugLog( 'exception', $e->getLogMessage() );
			}

			//
			// Handle any kind of exception by outputing properly formatted error message.
			// If this fails, an unhandled exception should be thrown so that global error
			// handler will process and log it.
			//

			$errCode = $this->substituteResultWithError( $e );

			// Error results should not be cached
			$this->setCacheMode( 'private' );

			$headerStr = 'MediaWiki-API-Error: ' . $errCode;
			if ( $e->getCode() === 0 ) {
				header( $headerStr );
			} else {
				header( $headerStr, true, $e->getCode() );
			}

			// Reset and print just the error message
			ob_clean();

			// If the error occured during printing, do a printer->profileOut()
			$this->mPrinter->safeProfileOut();
			$this->printResult( true );
		}

		// Send cache headers after any code which might generate an error, to
		// avoid sending public cache headers for errors.
		$this->sendCacheHeaders();

		if ( $this->mPrinter->getIsHtml() && !$this->mPrinter->isDisabled() ) {
			echo wfReportTime();
		}

		ob_end_flush();
	}

	protected function sendCacheHeaders() {
		if ( $this->mCacheMode == 'private' ) {
			header( 'Cache-Control: private' );
			return;
		}

		if ( $this->mCacheMode == 'anon-public-user-private' ) {
			global $wgUseXVO, $wgOut;
			header( 'Vary: Accept-Encoding, Cookie' );
			if ( $wgUseXVO ) {
				header( $wgOut->getXVO() );
				if ( $wgOut->haveCacheVaryCookies() ) {
					// Logged in, mark this request private
					header( 'Cache-Control: private' );
					return;
				}
				// Logged out, send normal public headers below
			} elseif ( session_id() != '' ) {
				// Logged in or otherwise has session (e.g. anonymous users who have edited)
				// Mark request private
				header( 'Cache-Control: private' );
				return;
			} // else no XVO and anonymous, send public headers below
		}

		// If nobody called setCacheMaxAge(), use the (s)maxage parameters
		if ( !isset( $this->mCacheControl['s-maxage'] ) ) {
			$this->mCacheControl['s-maxage'] = $this->getParameter( 'smaxage' );
		}
		if ( !isset( $this->mCacheControl['max-age'] ) ) {
			$this->mCacheControl['max-age'] = $this->getParameter( 'maxage' );
		}

		if ( !$this->mCacheControl['s-maxage'] && !$this->mCacheControl['max-age'] ) {
			// Public cache not requested
			// Sending a Vary header in this case is harmless, and protects us
			// against conditional calls of setCacheMaxAge().
			header( 'Cache-Control: private' );
			return;
		}

		$this->mCacheControl['public'] = true;

		// Send an Expires header
		$maxAge = min( $this->mCacheControl['s-maxage'], $this->mCacheControl['max-age'] );
		$expiryUnixTime = ( $maxAge == 0 ? 1 : time() + $maxAge );
		header( 'Expires: ' . wfTimestamp( TS_RFC2822, $expiryUnixTime ) );

		// Construct the Cache-Control header
		$ccHeader = '';
		$separator = '';
		foreach ( $this->mCacheControl as $name => $value ) {
			if ( is_bool( $value ) ) {
				if ( $value ) {
					$ccHeader .= $separator . $name;
					$separator = ', ';
				}
			} else {
				$ccHeader .= $separator . "$name=$value";
				$separator = ', ';
			}
		}

		header( "Cache-Control: $ccHeader" );
	}

	/**
	 * Replace the result data with the information about an exception.
	 * Returns the error code
	 * @param $e Exception
	 */
	protected function substituteResultWithError( $e ) {
		// Printer may not be initialized if the extractRequestParams() fails for the main module
		if ( !isset ( $this->mPrinter ) ) {
			// The printer has not been created yet. Try to manually get formatter value.
			$value = $this->getRequest()->getVal( 'format', self::API_DEFAULT_FORMAT );
			if ( !in_array( $value, $this->mFormatNames ) ) {
				$value = self::API_DEFAULT_FORMAT;
			}

			$this->mPrinter = $this->createPrinterByName( $value );
			if ( $this->mPrinter->getNeedsRawData() ) {
				$this->getResult()->setRawMode();
			}
		}

		if ( $e instanceof UsageException ) {
			//
			// User entered incorrect parameters - print usage screen
			//
			$errMessage = $e->getMessageArray();

			// Only print the help message when this is for the developer, not runtime
			if ( $this->mPrinter->getWantsHelp() || $this->mAction == 'help' ) {
				ApiResult::setContent( $errMessage, $this->makeHelpMsg() );
			}

		} else {
			global $wgShowSQLErrors, $wgShowExceptionDetails;
			//
			// Something is seriously wrong
			//
			if ( ( $e instanceof DBQueryError ) && !$wgShowSQLErrors ) {
				$info = 'Database query error';
			} else {
				$info = "Exception Caught: {$e->getMessage()}";
			}

			$errMessage = array(
				'code' => 'internal_api_error_' . get_class( $e ),
				'info' => $info,
			);
			ApiResult::setContent( $errMessage, $wgShowExceptionDetails ? "\n\n{$e->getTraceAsString()}\n\n" : '' );
		}

		$this->getResult()->reset();
		$this->getResult()->disableSizeCheck();
		// Re-add the id
		$requestid = $this->getParameter( 'requestid' );
		if ( !is_null( $requestid ) ) {
			$this->getResult()->addValue( null, 'requestid', $requestid );
		}
		// servedby is especially useful when debugging errors
		$this->getResult()->addValue( null, 'servedby', wfHostName() );
		$this->getResult()->addValue( null, 'error', $errMessage );

		return $errMessage['code'];
	}

	/**
	 * Set up for the execution.
	 */
	protected function setupExecuteAction() {
		// First add the id to the top element
		$requestid = $this->getParameter( 'requestid' );
		if ( !is_null( $requestid ) ) {
			$this->getResult()->addValue( null, 'requestid', $requestid );
		}
		$servedby = $this->getParameter( 'servedby' );
		if ( $servedby ) {
			$this->getResult()->addValue( null, 'servedby', wfHostName() );
		}

		$params = $this->extractRequestParams();

		$this->mShowVersions = $params['version'];
		$this->mAction = $params['action'];

		if ( !is_string( $this->mAction ) ) {
			$this->dieUsage( 'The API requires a valid action parameter', 'unknown_action' );
		}

		return $params;
	}

	/**
	 * Set up the module for response
	 * @return ApiBase The module that will handle this action
	 */
	protected function setupModule() {
		// Instantiate the module requested by the user
		$module = new $this->mModules[$this->mAction] ( $this, $this->mAction );
		$this->mModule = $module;

		$moduleParams = $module->extractRequestParams();

		// Die if token required, but not provided (unless there is a gettoken parameter)
		$salt = $module->getTokenSalt();
		if ( $salt !== false && !isset( $moduleParams['gettoken'] ) ) {
			if ( !isset( $moduleParams['token'] ) ) {
				$this->dieUsageMsg( array( 'missingparam', 'token' ) );
			} else {
				global $wgUser;
				if ( !$wgUser->matchEditToken( $moduleParams['token'], $salt ) ) {
					$this->dieUsageMsg( array( 'sessionfailure' ) );
				}
			}
		}
		return $module;
	}

	/**
	 * Check the max lag if necessary
	 * @param $module ApiBase object: Api module being used
	 * @param $params Array an array containing the request parameters.
	 * @return boolean True on success, false should exit immediately
	 */
	protected function checkMaxLag( $module, $params ) {
		if ( $module->shouldCheckMaxlag() && isset( $params['maxlag'] ) ) {
			// Check for maxlag
			global $wgShowHostnames;
			$maxLag = $params['maxlag'];
			list( $host, $lag ) = wfGetLB()->getMaxLag();
			if ( $lag > $maxLag ) {
				header( 'Retry-After: ' . max( intval( $maxLag ), 5 ) );
				header( 'X-Database-Lag: ' . intval( $lag ) );
				if ( $wgShowHostnames ) {
					$this->dieUsage( "Waiting for $host: $lag seconds lagged", 'maxlag' );
				} else {
					$this->dieUsage( "Waiting for a database server: $lag seconds lagged", 'maxlag' );
				}
				return false;
			}
		}
		return true;
	}


	/**
	 * Check for sufficient permissions to execute
	 * @param $module ApiBase An Api module
	 */
	protected function checkExecutePermissions( $module ) {
		global $wgUser;
		if ( $module->isReadMode() && !in_array( 'read', User::getGroupPermissions( array( '*' ) ), true ) &&
			!$wgUser->isAllowed( 'read' ) )
		{
			$this->dieUsageMsg( array( 'readrequired' ) );
		}
		if ( $module->isWriteMode() ) {
			if ( !$this->mEnableWrite ) {
				$this->dieUsageMsg( array( 'writedisabled' ) );
			}
			if ( !$wgUser->isAllowed( 'writeapi' ) ) {
				$this->dieUsageMsg( array( 'writerequired' ) );
			}
			if ( wfReadOnly() ) {
				$this->dieReadOnly();
			}
		}
	}

	/**
	 * Check POST for external response and setup result printer
	 * @param $module ApiBase An Api module
	 * @param $params Array an array with the request parameters
	 */
	protected function setupExternalResponse( $module, $params ) {
		// Ignore mustBePosted() for internal calls
		if ( $module->mustBePosted() && !$this->mRequest->wasPosted() ) {
			$this->dieUsageMsg( array( 'mustbeposted', $this->mAction ) );
		}

		// See if custom printer is used
		$this->mPrinter = $module->getCustomPrinter();
		if ( is_null( $this->mPrinter ) ) {
			// Create an appropriate printer
			$this->mPrinter = $this->createPrinterByName( $params['format'] );
		}

		if ( $this->mPrinter->getNeedsRawData() ) {
			$this->getResult()->setRawMode();
		}
	}

	/**
	 * Execute the actual module, without any error handling
	 */
	protected function executeAction() {
		$params = $this->setupExecuteAction();
		$module = $this->setupModule();

		$this->checkExecutePermissions( $module );

		if ( !$this->checkMaxLag( $module, $params ) ) {
			return;
		}

		if ( !$this->mInternalMode ) {
			$this->setupExternalResponse( $module, $params );
		}

		// Execute
		$module->profileIn();
		$module->execute();
		wfRunHooks( 'APIAfterExecute', array( &$module ) );
		$module->profileOut();

		if ( !$this->mInternalMode ) {
			// Print result data
			$this->printResult( false );
		}
	}

	/**
	 * Print results using the current printer
	 */
	protected function printResult( $isError ) {
		$this->getResult()->cleanUpUTF8();
		$printer = $this->mPrinter;
		$printer->profileIn();

		/**
		 * If the help message is requested in the default (xmlfm) format,
		 * tell the printer not to escape ampersands so that our links do
		 * not break.
		 */
		$printer->setUnescapeAmps( ( $this->mAction == 'help' || $isError )
				&& $printer->getFormat() == 'XML' && $printer->getIsHtml() );

		$printer->initPrinter( $isError );

		$printer->execute();
		$printer->closePrinter();
		$printer->profileOut();
	}

	public function isReadMode() {
		return false;
	}

	/**
	 * See ApiBase for description.
	 */
	public function getAllowedParams() {
		return array(
			'format' => array(
				ApiBase::PARAM_DFLT => ApiMain::API_DEFAULT_FORMAT,
				ApiBase::PARAM_TYPE => $this->mFormatNames
			),
			'action' => array(
				ApiBase::PARAM_DFLT => 'help',
				ApiBase::PARAM_TYPE => $this->mModuleNames
			),
			'version' => false,
			'maxlag'  => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
			'smaxage' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_DFLT => 0
			),
			'maxage' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_DFLT => 0
			),
			'requestid' => null,
			'servedby'  => false,
		);
	}

	/**
	 * See ApiBase for description.
	 */
	public function getParamDescription() {
		return array(
			'format' => 'The format of the output',
			'action' => 'What action you would like to perform. See below for module help',
			'version' => 'When showing help, include version for each module',
			'maxlag' => 'Maximum lag',
			'smaxage' => 'Set the s-maxage header to this many seconds. Errors are never cached',
			'maxage' => 'Set the max-age header to this many seconds. Errors are never cached',
			'requestid' => 'Request ID to distinguish requests. This will just be output back to you',
			'servedby' => 'Include the hostname that served the request in the results. Unconditionally shown on error',
		);
	}

	/**
	 * See ApiBase for description.
	 */
	public function getDescription() {
		return array(
			'',
			'',
			'******************************************************************************************',
			'**                                                                                      **',
			'**              This is an auto-generated MediaWiki API documentation page              **',
			'**                                                                                      **',
			'**                            Documentation and Examples:                               **',
			'**                         http://www.mediawiki.org/wiki/API                            **',
			'**                                                                                      **',
			'******************************************************************************************',
			'',
			'Status:                All features shown on this page should be working, but the API',
			'                       is still in active development, and  may change at any time.',
			'                       Make sure to monitor our mailing list for any updates',
			'',
			'Documentation:         http://www.mediawiki.org/wiki/API',
			'Mailing list:          http://lists.wikimedia.org/mailman/listinfo/mediawiki-api',
			'Api Announcements:     http://lists.wikimedia.org/mailman/listinfo/mediawiki-api-announce',
			'Bugs & Requests:       http://bugzilla.wikimedia.org/buglist.cgi?component=API&bug_status=NEW&bug_status=ASSIGNED&bug_status=REOPENED&order=bugs.delta_ts',
			'',
			'',
			'',
			'',
			'',
		);
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'readonlytext' ),
			array( 'code' => 'unknown_format', 'info' => 'Unrecognized format: format' ),
			array( 'code' => 'unknown_action', 'info' => 'The API requires a valid action parameter' ),
			array( 'code' => 'maxlag', 'info' => 'Waiting for host: x seconds lagged' ),
			array( 'code' => 'maxlag', 'info' => 'Waiting for a database server: x seconds lagged' ),
		) );
	}

	/**
	 * Returns an array of strings with credits for the API
	 */
	protected function getCredits() {
		return array(
			'API developers:',
			'    Roan Kattouw <Firstname>.<Lastname>@gmail.com (lead developer Sep 2007-present)',
			'    Victor Vasiliev - vasilvv at gee mail dot com',
			'    Bryan Tong Minh - bryan . tongminh @ gmail . com',
			'    Sam Reed - sam @ reedyboy . net',
			'    Yuri Astrakhan <Firstname><Lastname>@gmail.com (creator, lead developer Sep 2006-Sep 2007)',
			'',
			'Please send your comments, suggestions and questions to mediawiki-api@lists.wikimedia.org',
			'or file a bug report at http://bugzilla.wikimedia.org/'
		);
	}
	/**
	 * Sets whether the pretty-printer should format *bold* and $italics$
	 */
	public function setHelp( $help = true ) {
		$this->mPrinter->setHelp( $help );
	}

	/**
	 * Override the parent to generate help messages for all available modules.
	 */
	public function makeHelpMsg() {
		global $wgMemc, $wgAPICacheHelp, $wgAPICacheHelpTimeout;
		$this->setHelp();
		// Get help text from cache if present
		$key = wfMemcKey( 'apihelp', $this->getModuleName(),
			SpecialVersion::getVersion( 'nodb' ) .
			$this->getMain()->getShowVersions() );
		if ( $wgAPICacheHelp ) {
			$cached = $wgMemc->get( $key );
			if ( $cached ) {
				return $cached;
			}
		}
		$retval = $this->reallyMakeHelpMsg();
		if ( $wgAPICacheHelp ) {
			$wgMemc->set( $key, $retval, $wgAPICacheHelpTimeout );
		}
		return $retval;
	}

	public function reallyMakeHelpMsg() {
		$this->setHelp();

		// Use parent to make default message for the main module
		$msg = parent::makeHelpMsg();

		$astriks = str_repeat( '*** ', 10 );
		$msg .= "\n\n$astriks Modules  $astriks\n\n";
		foreach ( array_keys( $this->mModules ) as $moduleName ) {
			$module = new $this->mModules[$moduleName] ( $this, $moduleName );
			$msg .= self::makeHelpMsgHeader( $module, 'action' );
			$msg2 = $module->makeHelpMsg();
			if ( $msg2 !== false ) {
				$msg .= $msg2;
			}
			$msg .= "\n";
		}

		$msg .= "\n$astriks Permissions $astriks\n\n";
		foreach ( self::$mRights as $right => $rightMsg ) {
			$groups = User::getGroupsWithPermission( $right );
			$msg .= "* " . $right . " *\n  " . wfMsgReplaceArgs( $rightMsg[ 'msg' ], $rightMsg[ 'params' ] ) .
						"\nGranted to:\n  " . str_replace( '*', 'all', implode( ', ', $groups ) ) . "\n\n";

		}

		$msg .= "\n$astriks Formats  $astriks\n\n";
		foreach ( array_keys( $this->mFormats ) as $formatName ) {
			$module = $this->createPrinterByName( $formatName );
			$msg .= self::makeHelpMsgHeader( $module, 'format' );
			$msg2 = $module->makeHelpMsg();
			if ( $msg2 !== false ) {
				$msg .= $msg2;
			}
			$msg .= "\n";
		}

		$msg .= "\n*** Credits: ***\n   " . implode( "\n   ", $this->getCredits() ) . "\n";

		return $msg;
	}

	/**
	 * @static
	 * @param  $module ApiBase
	 * @param  $paramName String What type of request is this? e.g. action, query, list, prop, meta, format
	 * @return string
	 */
	public static function makeHelpMsgHeader( $module, $paramName ) {
		$modulePrefix = $module->getModulePrefix();
		if ( strval( $modulePrefix ) !== '' ) {
			$modulePrefix = "($modulePrefix) ";
		}

		return "* $paramName={$module->getModuleName()} $modulePrefix*";
	}

	private $mIsBot = null;
	private $mIsSysop = null;
	private $mCanApiHighLimits = null;

	/**
	 * Returns true if the currently logged in user is a bot, false otherwise
	 * OBSOLETE, use canApiHighLimits() instead
	 */
	public function isBot() {
		if ( !isset( $this->mIsBot ) ) {
			global $wgUser;
			$this->mIsBot = $wgUser->isAllowed( 'bot' );
		}
		return $this->mIsBot;
	}

	/**
	 * Similar to isBot(), this method returns true if the logged in user is
	 * a sysop, and false if not.
	 * OBSOLETE, use canApiHighLimits() instead
	 */
	public function isSysop() {
		if ( !isset( $this->mIsSysop ) ) {
			global $wgUser;
			$this->mIsSysop = in_array( 'sysop', $wgUser->getGroups() );
		}

		return $this->mIsSysop;
	}

	/**
	 * Check whether the current user is allowed to use high limits
	 * @return bool
	 */
	public function canApiHighLimits() {
		if ( !isset( $this->mCanApiHighLimits ) ) {
			global $wgUser;
			$this->mCanApiHighLimits = $wgUser->isAllowed( 'apihighlimits' );
		}

		return $this->mCanApiHighLimits;
	}

	/**
	 * Check whether the user wants us to show version information in the API help
	 * @return bool
	 */
	public function getShowVersions() {
		return $this->mShowVersions;
	}

	/**
	 * Returns the version information of this file, plus it includes
	 * the versions for all files that are not callable proper API modules
	 */
	public function getVersion() {
		$vers = array ();
		$vers[] = 'MediaWiki: ' . SpecialVersion::getVersion() . "\n    http://svn.wikimedia.org/viewvc/mediawiki/trunk/phase3/";
		$vers[] = __CLASS__ . ': $Id$';
		$vers[] = ApiBase::getBaseVersion();
		$vers[] = ApiFormatBase::getBaseVersion();
		$vers[] = ApiQueryBase::getBaseVersion();
		return $vers;
	}

	/**
	 * Add or overwrite a module in this ApiMain instance. Intended for use by extending
	 * classes who wish to add their own modules to their lexicon or override the
	 * behavior of inherent ones.
	 *
	 * @param $mdlName String The identifier for this module.
	 * @param $mdlClass String The class where this module is implemented.
	 */
	protected function addModule( $mdlName, $mdlClass ) {
		$this->mModules[$mdlName] = $mdlClass;
	}

	/**
	 * Add or overwrite an output format for this ApiMain. Intended for use by extending
	 * classes who wish to add to or modify current formatters.
	 *
	 * @param $fmtName The identifier for this format.
	 * @param $fmtClass The class implementing this format.
	 */
	protected function addFormat( $fmtName, $fmtClass ) {
		$this->mFormats[$fmtName] = $fmtClass;
	}

	/**
	 * Get the array mapping module names to class names
	 */
	function getModules() {
		return $this->mModules;
	}
}

/**
 * This exception will be thrown when dieUsage is called to stop module execution.
 * The exception handling code will print a help screen explaining how this API may be used.
 *
 * @ingroup API
 */
class UsageException extends Exception {

	private $mCodestr;
	private $mExtraData;

	public function __construct( $message, $codestr, $code = 0, $extradata = null ) {
		parent::__construct( $message, $code );
		$this->mCodestr = $codestr;
		$this->mExtraData = $extradata;
	}

	public function getCodeString() {
		return $this->mCodestr;
	}

	public function getMessageArray() {
		$result = array(
			'code' => $this->mCodestr,
			'info' => $this->getMessage()
		);
		if ( is_array( $this->mExtraData ) ) {
			$result = array_merge( $result, $this->mExtraData );
		}
		return $result;
	}

	public function __toString() {
		return "{$this->getCodeString()}: {$this->getMessage()}";
	}
}
