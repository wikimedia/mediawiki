<?php
/**
 *
 *
 * Created on Sep 4, 2006
 *
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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
	const API_DEFAULT_FORMAT = 'jsonfm';

	/**
	 * List of available modules: action name => module class
	 */
	private static $Modules = array(
		'login' => 'ApiLogin',
		'logout' => 'ApiLogout',
		'createaccount' => 'ApiCreateAccount',
		'query' => 'ApiQuery',
		'expandtemplates' => 'ApiExpandTemplates',
		'parse' => 'ApiParse',
		'stashedit' => 'ApiStashEdit',
		'opensearch' => 'ApiOpenSearch',
		'feedcontributions' => 'ApiFeedContributions',
		'feedrecentchanges' => 'ApiFeedRecentChanges',
		'feedwatchlist' => 'ApiFeedWatchlist',
		'help' => 'ApiHelp',
		'paraminfo' => 'ApiParamInfo',
		'rsd' => 'ApiRsd',
		'compare' => 'ApiComparePages',
		'tokens' => 'ApiTokens',
		'checktoken' => 'ApiCheckToken',

		// Write modules
		'purge' => 'ApiPurge',
		'setnotificationtimestamp' => 'ApiSetNotificationTimestamp',
		'rollback' => 'ApiRollback',
		'delete' => 'ApiDelete',
		'undelete' => 'ApiUndelete',
		'protect' => 'ApiProtect',
		'block' => 'ApiBlock',
		'unblock' => 'ApiUnblock',
		'move' => 'ApiMove',
		'edit' => 'ApiEditPage',
		'upload' => 'ApiUpload',
		'filerevert' => 'ApiFileRevert',
		'emailuser' => 'ApiEmailUser',
		'watch' => 'ApiWatch',
		'patrol' => 'ApiPatrol',
		'import' => 'ApiImport',
		'clearhasmsg' => 'ApiClearHasMsg',
		'userrights' => 'ApiUserrights',
		'options' => 'ApiOptions',
		'imagerotate' => 'ApiImageRotate',
		'revisiondelete' => 'ApiRevisionDelete',
		'managetags' => 'ApiManageTags',
		'tag' => 'ApiTag',
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
		'none' => 'ApiFormatNone',
	);

	// @codingStandardsIgnoreStart String contenation on "msg" not allowed to break long line
	/**
	 * List of user roles that are specifically relevant to the API.
	 * array( 'right' => array ( 'msg'    => 'Some message with a $1',
	 *                           'params' => array ( $someVarToSubst ) ),
	 *                          );
	 */
	private static $mRights = array(
		'writeapi' => array(
			'msg' => 'right-writeapi',
			'params' => array()
		),
		'apihighlimits' => array(
			'msg' => 'api-help-right-apihighlimits',
			'params' => array( ApiBase::LIMIT_SML2, ApiBase::LIMIT_BIG2 )
		)
	);
	// @codingStandardsIgnoreEnd

	/**
	 * @var ApiFormatBase
	 */
	private $mPrinter;

	private $mModuleMgr, $mResult, $mErrorFormatter, $mContinuationManager;
	private $mAction;
	private $mEnableWrite;
	private $mInternalMode, $mSquidMaxage, $mModule;

	private $mCacheMode = 'private';
	private $mCacheControl = array();
	private $mParamsUsed = array();

	/**
	 * Constructs an instance of ApiMain that utilizes the module and format specified by $request.
	 *
	 * @param IContextSource|WebRequest $context If this is an instance of
	 *    FauxRequest, errors are thrown and no printing occurs
	 * @param bool $enableWrite Should be set to true if the api may modify data
	 */
	public function __construct( $context = null, $enableWrite = false ) {
		if ( $context === null ) {
			$context = RequestContext::getMain();
		} elseif ( $context instanceof WebRequest ) {
			// BC for pre-1.19
			$request = $context;
			$context = RequestContext::getMain();
		}
		// We set a derivative context so we can change stuff later
		$this->setContext( new DerivativeContext( $context ) );

		if ( isset( $request ) ) {
			$this->getContext()->setRequest( $request );
		}

		$this->mInternalMode = ( $this->getRequest() instanceof FauxRequest );

		// Special handling for the main module: $parent === $this
		parent::__construct( $this, $this->mInternalMode ? 'main_int' : 'main' );

		if ( !$this->mInternalMode ) {
			// Impose module restrictions.
			// If the current user cannot read,
			// Remove all modules other than login
			global $wgUser;

			if ( $this->lacksSameOriginSecurity() ) {
				// If we're in a mode that breaks the same-origin policy, strip
				// user credentials for security.
				wfDebug( "API: stripping user credentials when the same-origin policy is not applied\n" );
				$wgUser = new User();
				$this->getContext()->setUser( $wgUser );
			}
		}

		$uselang = $this->getParameter( 'uselang' );
		if ( $uselang === 'user' ) {
			// Assume the parent context is going to return the user language
			// for uselang=user (see T85635).
		} else {
			if ( $uselang === 'content' ) {
				global $wgContLang;
				$uselang = $wgContLang->getCode();
			}
			$code = RequestContext::sanitizeLangCode( $uselang );
			$this->getContext()->setLanguage( $code );
			if ( !$this->mInternalMode ) {
				global $wgLang;
				$wgLang = $this->getContext()->getLanguage();
				RequestContext::getMain()->setLanguage( $wgLang );
			}
		}

		$config = $this->getConfig();
		$this->mModuleMgr = new ApiModuleManager( $this );
		$this->mModuleMgr->addModules( self::$Modules, 'action' );
		$this->mModuleMgr->addModules( $config->get( 'APIModules' ), 'action' );
		$this->mModuleMgr->addModules( self::$Formats, 'format' );
		$this->mModuleMgr->addModules( $config->get( 'APIFormatModules' ), 'format' );

		Hooks::run( 'ApiMain::moduleManager', array( $this->mModuleMgr ) );

		$this->mResult = new ApiResult( $this->getConfig()->get( 'APIMaxResultSize' ) );
		$this->mErrorFormatter = new ApiErrorFormatter_BackCompat( $this->mResult );
		$this->mResult->setErrorFormatter( $this->mErrorFormatter );
		$this->mResult->setMainForContinuation( $this );
		$this->mContinuationManager = null;
		$this->mEnableWrite = $enableWrite;

		$this->mSquidMaxage = -1; // flag for executeActionWithErrorHandling()
		$this->mCommit = false;
	}

	/**
	 * Return true if the API was started by other PHP code using FauxRequest
	 * @return bool
	 */
	public function isInternalMode() {
		return $this->mInternalMode;
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
	 * Get the ApiErrorFormatter object associated with current request
	 * @return ApiErrorFormatter
	 */
	public function getErrorFormatter() {
		return $this->mErrorFormatter;
	}

	/**
	 * Get the continuation manager
	 * @return ApiContinuationManager|null
	 */
	public function getContinuationManager() {
		return $this->mContinuationManager;
	}

	/**
	 * Set the continuation manager
	 * @param ApiContinuationManager|null
	 */
	public function setContinuationManager( $manager ) {
		if ( $manager !== null ) {
			if ( !$manager instanceof ApiContinuationManager ) {
				throw new InvalidArgumentException( __METHOD__ . ': Was passed ' .
					is_object( $manager ) ? get_class( $manager ) : gettype( $manager )
				);
			}
			if ( $this->mContinuationManager !== null ) {
				throw new UnexpectedValueException(
					__METHOD__ . ': tried to set manager from ' . $manager->getSource() .
					' when a manager is already set from ' . $this->mContinuationManager->getSource()
				);
			}
		}
		$this->mContinuationManager = $manager;
	}

	/**
	 * Get the API module object. Only works after executeAction()
	 *
	 * @return ApiBase
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
	 *
	 * @param int $maxage
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
	 * @param string $mode One of:
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

		if ( !User::isEveryoneAllowed( 'read' ) ) {
			// Private wiki, only private headers
			if ( $mode !== 'private' ) {
				wfDebug( __METHOD__ . ": ignoring request for $mode cache mode, private wiki\n" );

				return;
			}
		}

		if ( $mode === 'public' && $this->getParameter( 'uselang' ) === 'user' ) {
			// User language is used for i18n, so we don't want to publicly
			// cache. Anons are ok, because if they have non-default language
			// then there's an appropriate Vary header set by whatever set
			// their non-default language.
			wfDebug( __METHOD__ . ": downgrading cache mode 'public' to " .
				"'anon-public-user-private' due to uselang=user\n" );
			$mode = 'anon-public-user-private';
		}

		wfDebug( __METHOD__ . ": setting cache mode $mode\n" );
		$this->mCacheMode = $mode;
	}

	/**
	 * Set directives (key/value pairs) for the Cache-Control header.
	 * Boolean values will be formatted as such, by including or omitting
	 * without an equals sign.
	 *
	 * Cache control values set here will only be used if the cache mode is not
	 * private, see setCacheMode().
	 *
	 * @param array $directives
	 */
	public function setCacheControl( $directives ) {
		$this->mCacheControl = $directives + $this->mCacheControl;
	}

	/**
	 * Create an instance of an output formatter by its name
	 *
	 * @param string $format
	 *
	 * @return ApiFormatBase
	 */
	public function createPrinterByName( $format ) {
		$printer = $this->mModuleMgr->getModule( $format, 'format' );
		if ( $printer === null ) {
			$this->dieUsage( "Unrecognized format: {$format}", 'unknown_format' );
		}

		return $printer;
	}

	/**
	 * Execute api request. Any errors will be handled if the API was called by the remote client.
	 */
	public function execute() {
		if ( $this->mInternalMode ) {
			$this->executeAction();
		} else {
			$this->executeActionWithErrorHandling();
		}
	}

	/**
	 * Execute an action, and in case of an error, erase whatever partial results
	 * have been accumulated, and replace it with an error message and a help screen.
	 */
	protected function executeActionWithErrorHandling() {
		// Verify the CORS header before executing the action
		if ( !$this->handleCORS() ) {
			// handleCORS() has sent a 403, abort
			return;
		}

		// Exit here if the request method was OPTIONS
		// (assume there will be a followup GET or POST)
		if ( $this->getRequest()->getMethod() === 'OPTIONS' ) {
			return;
		}

		// In case an error occurs during data output,
		// clear the output buffer and print just the error information
		ob_start();

		$t = microtime( true );
		try {
			$this->executeAction();
		} catch ( Exception $e ) {
			$this->handleException( $e );
		}

		// Log the request whether or not there was an error
		$this->logRequest( microtime( true ) - $t );

		// Send cache headers after any code which might generate an error, to
		// avoid sending public cache headers for errors.
		$this->sendCacheHeaders();

		ob_end_flush();
	}

	/**
	 * Handle an exception as an API response
	 *
	 * @since 1.23
	 * @param Exception $e
	 */
	protected function handleException( Exception $e ) {
		// Bug 63145: Rollback any open database transactions
		if ( !( $e instanceof UsageException ) ) {
			// UsageExceptions are intentional, so don't rollback if that's the case
			try {
				MWExceptionHandler::rollbackMasterChangesAndLog( $e );
			} catch ( DBError $e2 ) {
				// Rollback threw an exception too. Log it, but don't interrupt
				// our regularly scheduled exception handling.
				MWExceptionHandler::logException( $e2 );
			}
		}

		// Allow extra cleanup and logging
		Hooks::run( 'ApiMain::onException', array( $this, $e ) );

		// Log it
		if ( !( $e instanceof UsageException ) ) {
			MWExceptionHandler::logException( $e );
		}

		// Handle any kind of exception by outputting properly formatted error message.
		// If this fails, an unhandled exception should be thrown so that global error
		// handler will process and log it.

		$errCode = $this->substituteResultWithError( $e );

		// Error results should not be cached
		$this->setCacheMode( 'private' );

		$response = $this->getRequest()->response();
		$headerStr = 'MediaWiki-API-Error: ' . $errCode;
		if ( $e->getCode() === 0 ) {
			$response->header( $headerStr );
		} else {
			$response->header( $headerStr, true, $e->getCode() );
		}

		// Reset and print just the error message
		ob_clean();

		// Printer may not be initialized if the extractRequestParams() fails for the main module
		$this->createErrorPrinter();

		try {
			$this->printResult( true );
		} catch ( UsageException $ex ) {
			// The error printer itself is failing. Try suppressing its request
			// parameters and redo.
			$this->setWarning(
				'Error printer failed (will retry without params): ' . $ex->getMessage()
			);
			$this->mPrinter = null;
			$this->createErrorPrinter();
			$this->mPrinter->forceDefaultParams();
			$this->printResult( true );
		}
	}

	/**
	 * Handle an exception from the ApiBeforeMain hook.
	 *
	 * This tries to print the exception as an API response, to be more
	 * friendly to clients. If it fails, it will rethrow the exception.
	 *
	 * @since 1.23
	 * @param Exception $e
	 * @throws Exception
	 */
	public static function handleApiBeforeMainException( Exception $e ) {
		ob_start();

		try {
			$main = new self( RequestContext::getMain(), false );
			$main->handleException( $e );
		} catch ( Exception $e2 ) {
			// Nope, even that didn't work. Punt.
			throw $e;
		}

		// Log the request and reset cache headers
		$main->logRequest( 0 );
		$main->sendCacheHeaders();

		ob_end_flush();
	}

	/**
	 * Check the &origin= query parameter against the Origin: HTTP header and respond appropriately.
	 *
	 * If no origin parameter is present, nothing happens.
	 * If an origin parameter is present but doesn't match the Origin header, a 403 status code
	 * is set and false is returned.
	 * If the parameter and the header do match, the header is checked against $wgCrossSiteAJAXdomains
	 * and $wgCrossSiteAJAXdomainExceptions, and if the origin qualifies, the appropriate CORS
	 * headers are set.
	 * http://www.w3.org/TR/cors/#resource-requests
	 * http://www.w3.org/TR/cors/#resource-preflight-requests
	 *
	 * @return bool False if the caller should abort (403 case), true otherwise (all other cases)
	 */
	protected function handleCORS() {
		$originParam = $this->getParameter( 'origin' ); // defaults to null
		if ( $originParam === null ) {
			// No origin parameter, nothing to do
			return true;
		}

		$request = $this->getRequest();
		$response = $request->response();

		// Origin: header is a space-separated list of origins, check all of them
		$originHeader = $request->getHeader( 'Origin' );
		if ( $originHeader === false ) {
			$origins = array();
		} else {
			$originHeader = trim( $originHeader );
			$origins = preg_split( '/\s+/', $originHeader );
		}

		if ( !in_array( $originParam, $origins ) ) {
			// origin parameter set but incorrect
			// Send a 403 response
			$message = HttpStatus::getMessage( 403 );
			$response->header( "HTTP/1.1 403 $message", true, 403 );
			$response->header( 'Cache-Control: no-cache' );
			echo "'origin' parameter does not match Origin header\n";

			return false;
		}

		$config = $this->getConfig();
		$matchOrigin = count( $origins ) === 1 && self::matchOrigin(
			$originParam,
			$config->get( 'CrossSiteAJAXdomains' ),
			$config->get( 'CrossSiteAJAXdomainExceptions' )
		);

		if ( $matchOrigin ) {
			$requestedMethod = $request->getHeader( 'Access-Control-Request-Method' );
			$preflight = $request->getMethod() === 'OPTIONS' && $requestedMethod !== false;
			if ( $preflight ) {
				// This is a CORS preflight request
				if ( $requestedMethod !== 'POST' && $requestedMethod !== 'GET' ) {
					// If method is not a case-sensitive match, do not set any additional headers and terminate.
					return true;
				}
				// We allow the actual request to send the following headers
				$requestedHeaders = $request->getHeader( 'Access-Control-Request-Headers' );
				if ( $requestedHeaders !== false ) {
					if ( !self::matchRequestedHeaders( $requestedHeaders ) ) {
						return true;
					}
					$response->header( 'Access-Control-Allow-Headers: ' . $requestedHeaders );
				}

				// We only allow the actual request to be GET or POST
				$response->header( 'Access-Control-Allow-Methods: POST, GET' );
			}

			$response->header( "Access-Control-Allow-Origin: $originHeader" );
			$response->header( 'Access-Control-Allow-Credentials: true' );
			$response->header( "Timing-Allow-Origin: $originHeader" ); # http://www.w3.org/TR/resource-timing/#timing-allow-origin

			if ( !$preflight ) {
				$response->header( 'Access-Control-Expose-Headers: MediaWiki-API-Error, Retry-After, X-Database-Lag' );
			}
		}

		$this->getOutput()->addVaryHeader( 'Origin' );
		return true;
	}

	/**
	 * Attempt to match an Origin header against a set of rules and a set of exceptions
	 * @param string $value Origin header
	 * @param array $rules Set of wildcard rules
	 * @param array $exceptions Set of wildcard rules
	 * @return bool True if $value matches a rule in $rules and doesn't match
	 *    any rules in $exceptions, false otherwise
	 */
	protected static function matchOrigin( $value, $rules, $exceptions ) {
		foreach ( $rules as $rule ) {
			if ( preg_match( self::wildcardToRegex( $rule ), $value ) ) {
				// Rule matches, check exceptions
				foreach ( $exceptions as $exc ) {
					if ( preg_match( self::wildcardToRegex( $exc ), $value ) ) {
						return false;
					}
				}

				return true;
			}
		}

		return false;
	}

	/**
	 * Attempt to validate the value of Access-Control-Request-Headers against a list
	 * of headers that we allow the follow up request to send.
	 *
	 * @param string $requestedHeaders Comma seperated list of HTTP headers
	 * @return bool True if all requested headers are in the list of allowed headers
	 */
	protected static function matchRequestedHeaders( $requestedHeaders ) {
		if ( trim( $requestedHeaders ) === '' ) {
			return true;
		}
		$requestedHeaders = explode( ',', $requestedHeaders );
		$allowedAuthorHeaders = array_flip( array(
			/* simple headers (see spec) */
			'accept',
			'accept-language',
			'content-language',
			'content-type',
			/* non-authorable headers in XHR, which are however requested by some UAs */
			'accept-encoding',
			'dnt',
			'origin',
			/* MediaWiki whitelist */
			'api-user-agent',
		) );
		foreach ( $requestedHeaders as $rHeader ) {
			$rHeader = strtolower( trim( $rHeader ) );
			if ( !isset( $allowedAuthorHeaders[$rHeader] ) ) {
				wfDebugLog( 'api', 'CORS preflight failed on requested header: ' . $rHeader );
				return false;
			}
		}
		return true;
	}

	/**
	 * Helper function to convert wildcard string into a regex
	 * '*' => '.*?'
	 * '?' => '.'
	 *
	 * @param string $wildcard String with wildcards
	 * @return string Regular expression
	 */
	protected static function wildcardToRegex( $wildcard ) {
		$wildcard = preg_quote( $wildcard, '/' );
		$wildcard = str_replace(
			array( '\*', '\?' ),
			array( '.*?', '.' ),
			$wildcard
		);

		return "/^https?:\/\/$wildcard$/";
	}

	protected function sendCacheHeaders() {
		$response = $this->getRequest()->response();
		$out = $this->getOutput();

		$config = $this->getConfig();

		if ( $config->get( 'VaryOnXFP' ) ) {
			$out->addVaryHeader( 'X-Forwarded-Proto' );
		}

		// The logic should be:
		// $this->mCacheControl['max-age'] is set?
		//    Use it, the module knows better than our guess.
		// !$this->mModule || $this->mModule->isWriteMode(), and mCacheMode is private?
		//    Use 0 because we can guess caching is probably the wrong thing to do.
		// Use $this->getParameter( 'maxage' ), which already defaults to 0.
		$maxage = 0;
		if ( isset( $this->mCacheControl['max-age'] ) ) {
			$maxage = $this->mCacheControl['max-age'];
		} elseif ( ( $this->mModule && !$this->mModule->isWriteMode() ) ||
			$this->mCacheMode !== 'private'
		) {
			$maxage = $this->getParameter( 'maxage' );
		}
		$privateCache = 'private, must-revalidate, max-age=' . $maxage;

		if ( $this->mCacheMode == 'private' ) {
			$response->header( "Cache-Control: $privateCache" );
			return;
		}

		$useXVO = $config->get( 'UseXVO' );
		if ( $this->mCacheMode == 'anon-public-user-private' ) {
			$out->addVaryHeader( 'Cookie' );
			$response->header( $out->getVaryHeader() );
			if ( $useXVO ) {
				$response->header( $out->getXVO() );
				if ( $out->haveCacheVaryCookies() ) {
					// Logged in, mark this request private
					$response->header( "Cache-Control: $privateCache" );
					return;
				}
				// Logged out, send normal public headers below
			} elseif ( session_id() != '' ) {
				// Logged in or otherwise has session (e.g. anonymous users who have edited)
				// Mark request private
				$response->header( "Cache-Control: $privateCache" );

				return;
			} // else no XVO and anonymous, send public headers below
		}

		// Send public headers
		$response->header( $out->getVaryHeader() );
		if ( $useXVO ) {
			$response->header( $out->getXVO() );
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
			$response->header( "Cache-Control: $privateCache" );

			return;
		}

		$this->mCacheControl['public'] = true;

		// Send an Expires header
		$maxAge = min( $this->mCacheControl['s-maxage'], $this->mCacheControl['max-age'] );
		$expiryUnixTime = ( $maxAge == 0 ? 1 : time() + $maxAge );
		$response->header( 'Expires: ' . wfTimestamp( TS_RFC2822, $expiryUnixTime ) );

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

		$response->header( "Cache-Control: $ccHeader" );
	}

	/**
	 * Create the printer for error output
	 */
	private function createErrorPrinter() {
		if ( !isset( $this->mPrinter ) ) {
			$value = $this->getRequest()->getVal( 'format', self::API_DEFAULT_FORMAT );
			if ( !$this->mModuleMgr->isDefined( $value, 'format' ) ) {
				$value = self::API_DEFAULT_FORMAT;
			}
			$this->mPrinter = $this->createPrinterByName( $value );
		}

		// Printer may not be able to handle errors. This is particularly
		// likely if the module returns something for getCustomPrinter().
		if ( !$this->mPrinter->canPrintErrors() ) {
			$this->mPrinter = $this->createPrinterByName( self::API_DEFAULT_FORMAT );
		}
	}

	/**
	 * Replace the result data with the information about an exception.
	 * Returns the error code
	 * @param Exception $e
	 * @return string
	 */
	protected function substituteResultWithError( $e ) {
		$result = $this->getResult();
		$config = $this->getConfig();

		if ( $e instanceof UsageException ) {
			// User entered incorrect parameters - generate error response
			$errMessage = $e->getMessageArray();
			$link = wfExpandUrl( wfScript( 'api' ) );
			ApiResult::setContentValue( $errMessage, 'docref', "See $link for API usage" );
		} else {
			// Something is seriously wrong
			if ( ( $e instanceof DBQueryError ) && !$config->get( 'ShowSQLErrors' ) ) {
				$info = 'Database query error';
			} else {
				$info = "Exception Caught: {$e->getMessage()}";
			}

			$errMessage = array(
				'code' => 'internal_api_error_' . get_class( $e ),
				'info' => '[' . MWExceptionHandler::getLogId( $e ) . '] ' . $info,
			);
			if ( $config->get( 'ShowExceptionDetails' ) ) {
				ApiResult::setContentValue(
					$errMessage,
					'trace',
					MWExceptionHandler::getRedactedTraceAsString( $e )
				);
			}
		}

		// Remember all the warnings to re-add them later
		$warnings = $result->getResultData( array( 'warnings' ) );

		$result->reset();
		// Re-add the id
		$requestid = $this->getParameter( 'requestid' );
		if ( !is_null( $requestid ) ) {
			$result->addValue( null, 'requestid', $requestid, ApiResult::NO_SIZE_CHECK );
		}
		if ( $config->get( 'ShowHostnames' ) ) {
			// servedby is especially useful when debugging errors
			$result->addValue( null, 'servedby', wfHostName(), ApiResult::NO_SIZE_CHECK );
		}
		if ( $warnings !== null ) {
			$result->addValue( null, 'warnings', $warnings, ApiResult::NO_SIZE_CHECK );
		}

		$result->addValue( null, 'error', $errMessage, ApiResult::NO_SIZE_CHECK );

		return $errMessage['code'];
	}

	/**
	 * Set up for the execution.
	 * @return array
	 */
	protected function setupExecuteAction() {
		// First add the id to the top element
		$result = $this->getResult();
		$requestid = $this->getParameter( 'requestid' );
		if ( !is_null( $requestid ) ) {
			$result->addValue( null, 'requestid', $requestid );
		}

		if ( $this->getConfig()->get( 'ShowHostnames' ) ) {
			$servedby = $this->getParameter( 'servedby' );
			if ( $servedby ) {
				$result->addValue( null, 'servedby', wfHostName() );
			}
		}

		if ( $this->getParameter( 'curtimestamp' ) ) {
			$result->addValue( null, 'curtimestamp', wfTimestamp( TS_ISO_8601, time() ),
				ApiResult::NO_SIZE_CHECK );
		}

		$params = $this->extractRequestParams();

		$this->mAction = $params['action'];

		if ( !is_string( $this->mAction ) ) {
			$this->dieUsage( 'The API requires a valid action parameter', 'unknown_action' );
		}

		return $params;
	}

	/**
	 * Set up the module for response
	 * @return ApiBase The module that will handle this action
	 * @throws MWException
	 * @throws UsageException
	 */
	protected function setupModule() {
		// Instantiate the module requested by the user
		$module = $this->mModuleMgr->getModule( $this->mAction, 'action' );
		if ( $module === null ) {
			$this->dieUsage( 'The API requires a valid action parameter', 'unknown_action' );
		}
		$moduleParams = $module->extractRequestParams();

		// Check token, if necessary
		if ( $module->needsToken() === true ) {
			throw new MWException(
				"Module '{$module->getModuleName()}' must be updated for the new token handling. " .
				"See documentation for ApiBase::needsToken for details."
			);
		}
		if ( $module->needsToken() ) {
			if ( !$module->mustBePosted() ) {
				throw new MWException(
					"Module '{$module->getModuleName()}' must require POST to use tokens."
				);
			}

			if ( !isset( $moduleParams['token'] ) ) {
				$this->dieUsageMsg( array( 'missingparam', 'token' ) );
			}

			if ( !$this->getConfig()->get( 'DebugAPI' ) &&
				array_key_exists(
					$module->encodeParamName( 'token' ),
					$this->getRequest()->getQueryValues()
				)
			) {
				$this->dieUsage(
					"The '{$module->encodeParamName( 'token' )}' parameter was found in the query string, but must be in the POST body",
					'mustposttoken'
				);
			}

			if ( !$module->validateToken( $moduleParams['token'], $moduleParams ) ) {
				$this->dieUsageMsg( 'sessionfailure' );
			}
		}

		return $module;
	}

	/**
	 * Check the max lag if necessary
	 * @param ApiBase $module Api module being used
	 * @param array $params Array an array containing the request parameters.
	 * @return bool True on success, false should exit immediately
	 */
	protected function checkMaxLag( $module, $params ) {
		if ( $module->shouldCheckMaxlag() && isset( $params['maxlag'] ) ) {
			// Check for maxlag
			$maxLag = $params['maxlag'];
			list( $host, $lag ) = wfGetLB()->getMaxLag();
			if ( $lag > $maxLag ) {
				$response = $this->getRequest()->response();

				$response->header( 'Retry-After: ' . max( intval( $maxLag ), 5 ) );
				$response->header( 'X-Database-Lag: ' . intval( $lag ) );

				if ( $this->getConfig()->get( 'ShowHostnames' ) ) {
					$this->dieUsage( "Waiting for $host: $lag seconds lagged", 'maxlag' );
				}

				$this->dieUsage( "Waiting for a database server: $lag seconds lagged", 'maxlag' );
			}
		}

		return true;
	}

	/**
	 * Check for sufficient permissions to execute
	 * @param ApiBase $module An Api module
	 */
	protected function checkExecutePermissions( $module ) {
		$user = $this->getUser();
		if ( $module->isReadMode() && !User::isEveryoneAllowed( 'read' ) &&
			!$user->isAllowed( 'read' )
		) {
			$this->dieUsageMsg( 'readrequired' );
		}
		if ( $module->isWriteMode() ) {
			if ( !$this->mEnableWrite ) {
				$this->dieUsageMsg( 'writedisabled' );
			}
			if ( !$user->isAllowed( 'writeapi' ) ) {
				$this->dieUsageMsg( 'writerequired' );
			}
			if ( wfReadOnly() ) {
				$this->dieReadOnly();
			}
		}

		// Allow extensions to stop execution for arbitrary reasons.
		$message = false;
		if ( !Hooks::run( 'ApiCheckCanExecute', array( $module, $user, &$message ) ) ) {
			$this->dieUsageMsg( $message );
		}
	}

	/**
	 * Check asserts of the user's rights
	 * @param array $params
	 */
	protected function checkAsserts( $params ) {
		if ( isset( $params['assert'] ) ) {
			$user = $this->getUser();
			switch ( $params['assert'] ) {
				case 'user':
					if ( $user->isAnon() ) {
						$this->dieUsage( 'Assertion that the user is logged in failed', 'assertuserfailed' );
					}
					break;
				case 'bot':
					if ( !$user->isAllowed( 'bot' ) ) {
						$this->dieUsage( 'Assertion that the user has the bot right failed', 'assertbotfailed' );
					}
					break;
			}
		}
	}

	/**
	 * Check POST for external response and setup result printer
	 * @param ApiBase $module An Api module
	 * @param array $params An array with the request parameters
	 */
	protected function setupExternalResponse( $module, $params ) {
		if ( !$this->getRequest()->wasPosted() && $module->mustBePosted() ) {
			// Module requires POST. GET request might still be allowed
			// if $wgDebugApi is true, otherwise fail.
			$this->dieUsageMsgOrDebug( array( 'mustbeposted', $this->mAction ) );
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
		$this->mModule = $module;

		$this->checkExecutePermissions( $module );

		if ( !$this->checkMaxLag( $module, $params ) ) {
			return;
		}

		if ( !$this->mInternalMode ) {
			$this->setupExternalResponse( $module, $params );
		}

		$this->checkAsserts( $params );

		// Execute
		$module->execute();
		Hooks::run( 'APIAfterExecute', array( &$module ) );

		$this->reportUnusedParams();

		if ( !$this->mInternalMode ) {
			//append Debug information
			MWDebug::appendDebugInfoToApiResult( $this->getContext(), $this->getResult() );

			// Print result data
			$this->printResult( false );
		}
	}

	/**
	 * Log the preceding request
	 * @param int $time Time in seconds
	 */
	protected function logRequest( $time ) {
		$request = $this->getRequest();
		$milliseconds = $time === null ? '?' : round( $time * 1000 );
		$s = 'API' .
			' ' . $request->getMethod() .
			' ' . wfUrlencode( str_replace( ' ', '_', $this->getUser()->getName() ) ) .
			' ' . $request->getIP() .
			' T=' . $milliseconds . 'ms';
		foreach ( $this->getParamsUsed() as $name ) {
			$value = $request->getVal( $name );
			if ( $value === null ) {
				continue;
			}
			$s .= ' ' . $name . '=';
			if ( strlen( $value ) > 256 ) {
				$encValue = $this->encodeRequestLogValue( substr( $value, 0, 256 ) );
				$s .= $encValue . '[...]';
			} else {
				$s .= $this->encodeRequestLogValue( $value );
			}
		}
		$s .= "\n";
		wfDebugLog( 'api', $s, 'private' );
	}

	/**
	 * Encode a value in a format suitable for a space-separated log line.
	 * @param string $s
	 * @return string
	 */
	protected function encodeRequestLogValue( $s ) {
		static $table;
		if ( !$table ) {
			$chars = ';@$!*(),/:';
			$numChars = strlen( $chars );
			for ( $i = 0; $i < $numChars; $i++ ) {
				$table[rawurlencode( $chars[$i] )] = $chars[$i];
			}
		}

		return strtr( rawurlencode( $s ), $table );
	}

	/**
	 * Get the request parameters used in the course of the preceding execute() request
	 * @return array
	 */
	protected function getParamsUsed() {
		return array_keys( $this->mParamsUsed );
	}

	/**
	 * Get a request value, and register the fact that it was used, for logging.
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public function getVal( $name, $default = null ) {
		$this->mParamsUsed[$name] = true;

		$ret = $this->getRequest()->getVal( $name );
		if ( $ret === null ) {
			if ( $this->getRequest()->getArray( $name ) !== null ) {
				// See bug 10262 for why we don't just join( '|', ... ) the
				// array.
				$this->setWarning(
					"Parameter '$name' uses unsupported PHP array syntax"
				);
			}
			$ret = $default;
		}
		return $ret;
	}

	/**
	 * Get a boolean request value, and register the fact that the parameter
	 * was used, for logging.
	 * @param string $name
	 * @return bool
	 */
	public function getCheck( $name ) {
		return $this->getVal( $name, null ) !== null;
	}

	/**
	 * Get a request upload, and register the fact that it was used, for logging.
	 *
	 * @since 1.21
	 * @param string $name Parameter name
	 * @return WebRequestUpload
	 */
	public function getUpload( $name ) {
		$this->mParamsUsed[$name] = true;

		return $this->getRequest()->getUpload( $name );
	}

	/**
	 * Report unused parameters, so the client gets a hint in case it gave us parameters we don't know,
	 * for example in case of spelling mistakes or a missing 'g' prefix for generators.
	 */
	protected function reportUnusedParams() {
		$paramsUsed = $this->getParamsUsed();
		$allParams = $this->getRequest()->getValueNames();

		if ( !$this->mInternalMode ) {
			// Printer has not yet executed; don't warn that its parameters are unused
			$printerParams = array_map(
				array( $this->mPrinter, 'encodeParamName' ),
				array_keys( $this->mPrinter->getFinalParams() ?: array() )
			);
			$unusedParams = array_diff( $allParams, $paramsUsed, $printerParams );
		} else {
			$unusedParams = array_diff( $allParams, $paramsUsed );
		}

		if ( count( $unusedParams ) ) {
			$s = count( $unusedParams ) > 1 ? 's' : '';
			$this->setWarning( "Unrecognized parameter$s: '" . implode( $unusedParams, "', '" ) . "'" );
		}
	}

	/**
	 * Print results using the current printer
	 *
	 * @param bool $isError
	 */
	protected function printResult( $isError ) {
		if ( $this->getConfig()->get( 'DebugAPI' ) !== false ) {
			$this->setWarning( 'SECURITY WARNING: $wgDebugAPI is enabled' );
		}

		$printer = $this->mPrinter;
		$printer->initPrinter( false );
		$printer->execute();
		$printer->closePrinter();
	}

	/**
	 * @return bool
	 */
	public function isReadMode() {
		return false;
	}

	/**
	 * See ApiBase for description.
	 *
	 * @return array
	 */
	public function getAllowedParams() {
		return array(
			'action' => array(
				ApiBase::PARAM_DFLT => 'help',
				ApiBase::PARAM_TYPE => 'submodule',
			),
			'format' => array(
				ApiBase::PARAM_DFLT => ApiMain::API_DEFAULT_FORMAT,
				ApiBase::PARAM_TYPE => 'submodule',
			),
			'maxlag' => array(
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
			'assert' => array(
				ApiBase::PARAM_TYPE => array( 'user', 'bot' )
			),
			'requestid' => null,
			'servedby' => false,
			'curtimestamp' => false,
			'origin' => null,
			'uselang' => array(
				ApiBase::PARAM_DFLT => 'user',
			),
		);
	}

	/** @see ApiBase::getExamplesMessages() */
	protected function getExamplesMessages() {
		return array(
			'action=help'
				=> 'apihelp-help-example-main',
			'action=help&recursivesubmodules=1'
				=> 'apihelp-help-example-recursive',
		);
	}

	public function modifyHelp( array &$help, array $options ) {
		// Wish PHP had an "array_insert_before". Instead, we have to manually
		// reindex the array to get 'permissions' in the right place.
		$oldHelp = $help;
		$help = array();
		foreach ( $oldHelp as $k => $v ) {
			if ( $k === 'submodules' ) {
				$help['permissions'] = '';
			}
			$help[$k] = $v;
		}
		$help['credits'] = '';

		// Fill 'permissions'
		$help['permissions'] .= Html::openElement( 'div',
			array( 'class' => 'apihelp-block apihelp-permissions' ) );
		$m = $this->msg( 'api-help-permissions' );
		if ( !$m->isDisabled() ) {
			$help['permissions'] .= Html::rawElement( 'div', array( 'class' => 'apihelp-block-head' ),
				$m->numParams( count( self::$mRights ) )->parse()
			);
		}
		$help['permissions'] .= Html::openElement( 'dl' );
		foreach ( self::$mRights as $right => $rightMsg ) {
			$help['permissions'] .= Html::element( 'dt', null, $right );

			$rightMsg = $this->msg( $rightMsg['msg'], $rightMsg['params'] )->parse();
			$help['permissions'] .= Html::rawElement( 'dd', null, $rightMsg );

			$groups = array_map( function ( $group ) {
				return $group == '*' ? 'all' : $group;
			}, User::getGroupsWithPermission( $right ) );

			$help['permissions'] .= Html::rawElement( 'dd', null,
				$this->msg( 'api-help-permissions-granted-to' )
					->numParams( count( $groups ) )
					->params( $this->getLanguage()->commaList( $groups ) )
					->parse()
			);
		}
		$help['permissions'] .= Html::closeElement( 'dl' );
		$help['permissions'] .= Html::closeElement( 'div' );

		// Fill 'credits', if applicable
		if ( empty( $options['nolead'] ) ) {
			$help['credits'] .= Html::element( 'h' . min( 6, $options['headerlevel'] + 1 ),
				array( 'id' => '+credits', 'class' => 'apihelp-header' ),
				$this->msg( 'api-credits-header' )->parse()
			);
			$help['credits'] .= $this->msg( 'api-credits' )->useDatabase( false )->parseAsBlock();
		}
	}

	private $mCanApiHighLimits = null;

	/**
	 * Check whether the current user is allowed to use high limits
	 * @return bool
	 */
	public function canApiHighLimits() {
		if ( !isset( $this->mCanApiHighLimits ) ) {
			$this->mCanApiHighLimits = $this->getUser()->isAllowed( 'apihighlimits' );
		}

		return $this->mCanApiHighLimits;
	}

	/**
	 * Overrides to return this instance's module manager.
	 * @return ApiModuleManager
	 */
	public function getModuleManager() {
		return $this->mModuleMgr;
	}

	/**
	 * Fetches the user agent used for this request
	 *
	 * The value will be the combination of the 'Api-User-Agent' header (if
	 * any) and the standard User-Agent header (if any).
	 *
	 * @return string
	 */
	public function getUserAgent() {
		return trim(
			$this->getRequest()->getHeader( 'Api-user-agent' ) . ' ' .
			$this->getRequest()->getHeader( 'User-agent' )
		);
	}

	/************************************************************************//**
	 * @name   Deprecated
	 * @{
	 */

	/**
	 * Sets whether the pretty-printer should format *bold* and $italics$
	 *
	 * @deprecated since 1.25
	 * @param bool $help
	 */
	public function setHelp( $help = true ) {
		wfDeprecated( __METHOD__, '1.25' );
		$this->mPrinter->setHelp( $help );
	}

	/**
	 * Override the parent to generate help messages for all available modules.
	 *
	 * @deprecated since 1.25
	 * @return string
	 */
	public function makeHelpMsg() {
		wfDeprecated( __METHOD__, '1.25' );
		global $wgMemc;
		$this->setHelp();
		// Get help text from cache if present
		$key = wfMemcKey( 'apihelp', $this->getModuleName(),
			str_replace( ' ', '_', SpecialVersion::getVersion( 'nodb' ) ) );

		$cacheHelpTimeout = $this->getConfig()->get( 'APICacheHelpTimeout' );
		if ( $cacheHelpTimeout > 0 ) {
			$cached = $wgMemc->get( $key );
			if ( $cached ) {
				return $cached;
			}
		}
		$retval = $this->reallyMakeHelpMsg();
		if ( $cacheHelpTimeout > 0 ) {
			$wgMemc->set( $key, $retval, $cacheHelpTimeout );
		}

		return $retval;
	}

	/**
	 * @deprecated since 1.25
	 * @return mixed|string
	 */
	public function reallyMakeHelpMsg() {
		wfDeprecated( __METHOD__, '1.25' );
		$this->setHelp();

		// Use parent to make default message for the main module
		$msg = parent::makeHelpMsg();

		$astriks = str_repeat( '*** ', 14 );
		$msg .= "\n\n$astriks Modules  $astriks\n\n";

		foreach ( $this->mModuleMgr->getNames( 'action' ) as $name ) {
			$module = $this->mModuleMgr->getModule( $name );
			$msg .= self::makeHelpMsgHeader( $module, 'action' );

			$msg2 = $module->makeHelpMsg();
			if ( $msg2 !== false ) {
				$msg .= $msg2;
			}
			$msg .= "\n";
		}

		$msg .= "\n$astriks Permissions $astriks\n\n";
		foreach ( self::$mRights as $right => $rightMsg ) {
			$rightsMsg = $this->msg( $rightMsg['msg'], $rightMsg['params'] )
				->useDatabase( false )
				->inLanguage( 'en' )
				->text();
			$groups = User::getGroupsWithPermission( $right );
			$msg .= "* " . $right . " *\n  $rightsMsg" .
				"\nGranted to:\n  " . str_replace( '*', 'all', implode( ', ', $groups ) ) . "\n\n";
		}

		$msg .= "\n$astriks Formats  $astriks\n\n";
		foreach ( $this->mModuleMgr->getNames( 'format' ) as $name ) {
			$module = $this->mModuleMgr->getModule( $name );
			$msg .= self::makeHelpMsgHeader( $module, 'format' );
			$msg2 = $module->makeHelpMsg();
			if ( $msg2 !== false ) {
				$msg .= $msg2;
			}
			$msg .= "\n";
		}

		$credits = $this->msg( 'api-credits' )->useDatabase( 'false' )->inLanguage( 'en' )->text();
		$credits = str_replace( "\n", "\n   ", $credits );
		$msg .= "\n*** Credits: ***\n   $credits\n";

		return $msg;
	}

	/**
	 * @deprecated since 1.25
	 * @param ApiBase $module
	 * @param string $paramName What type of request is this? e.g. action,
	 *    query, list, prop, meta, format
	 * @return string
	 */
	public static function makeHelpMsgHeader( $module, $paramName ) {
		wfDeprecated( __METHOD__, '1.25' );
		$modulePrefix = $module->getModulePrefix();
		if ( strval( $modulePrefix ) !== '' ) {
			$modulePrefix = "($modulePrefix) ";
		}

		return "* $paramName={$module->getModuleName()} $modulePrefix*";
	}

	/**
	 * Check whether the user wants us to show version information in the API help
	 * @return bool
	 * @deprecated since 1.21, always returns false
	 */
	public function getShowVersions() {
		wfDeprecated( __METHOD__, '1.21' );

		return false;
	}

	/**
	 * Add or overwrite a module in this ApiMain instance. Intended for use by extending
	 * classes who wish to add their own modules to their lexicon or override the
	 * behavior of inherent ones.
	 *
	 * @deprecated since 1.21, Use getModuleManager()->addModule() instead.
	 * @param string $name The identifier for this module.
	 * @param ApiBase $class The class where this module is implemented.
	 */
	protected function addModule( $name, $class ) {
		$this->getModuleManager()->addModule( $name, 'action', $class );
	}

	/**
	 * Add or overwrite an output format for this ApiMain. Intended for use by extending
	 * classes who wish to add to or modify current formatters.
	 *
	 * @deprecated since 1.21, Use getModuleManager()->addModule() instead.
	 * @param string $name The identifier for this format.
	 * @param ApiFormatBase $class The class implementing this format.
	 */
	protected function addFormat( $name, $class ) {
		$this->getModuleManager()->addModule( $name, 'format', $class );
	}

	/**
	 * Get the array mapping module names to class names
	 * @deprecated since 1.21, Use getModuleManager()'s methods instead.
	 * @return array
	 */
	function getModules() {
		return $this->getModuleManager()->getNamesWithClasses( 'action' );
	}

	/**
	 * Returns the list of supported formats in form ( 'format' => 'ClassName' )
	 *
	 * @since 1.18
	 * @deprecated since 1.21, Use getModuleManager()'s methods instead.
	 * @return array
	 */
	public function getFormats() {
		return $this->getModuleManager()->getNamesWithClasses( 'format' );
	}

	/**@}*/

}

/**
 * This exception will be thrown when dieUsage is called to stop module execution.
 *
 * @ingroup API
 */
class UsageException extends MWException {

	private $mCodestr;

	/**
	 * @var null|array
	 */
	private $mExtraData;

	/**
	 * @param string $message
	 * @param string $codestr
	 * @param int $code
	 * @param array|null $extradata
	 */
	public function __construct( $message, $codestr, $code = 0, $extradata = null ) {
		parent::__construct( $message, $code );
		$this->mCodestr = $codestr;
		$this->mExtraData = $extradata;
	}

	/**
	 * @return string
	 */
	public function getCodeString() {
		return $this->mCodestr;
	}

	/**
	 * @return array
	 */
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

	/**
	 * @return string
	 */
	public function __toString() {
		return "{$this->getCodeString()}: {$this->getMessage()}";
	}
}

/**
 * For really cool vim folding this needs to be at the end:
 * vim: foldmarker=@{,@} foldmethod=marker
 */
