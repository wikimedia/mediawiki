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

use MediaWiki\Logger\LoggerFactory;

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
	private static $Modules = [
		'login' => 'ApiLogin',
		'clientlogin' => 'ApiClientLogin',
		'logout' => 'ApiLogout',
		'createaccount' => 'ApiAMCreateAccount',
		'linkaccount' => 'ApiLinkAccount',
		'unlinkaccount' => 'ApiRemoveAuthenticationData',
		'changeauthenticationdata' => 'ApiChangeAuthenticationData',
		'removeauthenticationdata' => 'ApiRemoveAuthenticationData',
		'resetpassword' => 'ApiResetPassword',
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
		'cspreport' => 'ApiCSPReport',

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
		'mergehistory' => 'ApiMergeHistory',
	];

	/**
	 * List of available formats: format name => format class
	 */
	private static $Formats = [
		'json' => 'ApiFormatJson',
		'jsonfm' => 'ApiFormatJson',
		'php' => 'ApiFormatPhp',
		'phpfm' => 'ApiFormatPhp',
		'xml' => 'ApiFormatXml',
		'xmlfm' => 'ApiFormatXml',
		'rawfm' => 'ApiFormatJson',
		'none' => 'ApiFormatNone',
	];

	// @codingStandardsIgnoreStart String contenation on "msg" not allowed to break long line
	/**
	 * List of user roles that are specifically relevant to the API.
	 * [ 'right' => [ 'msg'    => 'Some message with a $1',
	 *                'params' => [ $someVarToSubst ] ],
	 * ];
	 */
	private static $mRights = [
		'writeapi' => [
			'msg' => 'right-writeapi',
			'params' => []
		],
		'apihighlimits' => [
			'msg' => 'api-help-right-apihighlimits',
			'params' => [ ApiBase::LIMIT_SML2, ApiBase::LIMIT_BIG2 ]
		]
	];
	// @codingStandardsIgnoreEnd

	/**
	 * @var ApiFormatBase
	 */
	private $mPrinter;

	private $mModuleMgr, $mResult, $mErrorFormatter;
	/** @var ApiContinuationManager|null */
	private $mContinuationManager;
	private $mAction;
	private $mEnableWrite;
	private $mInternalMode, $mSquidMaxage;
	/** @var ApiBase */
	private $mModule;

	private $mCacheMode = 'private';
	private $mCacheControl = [];
	private $mParamsUsed = [];
	private $mParamsSensitive = [];

	/** @var bool|null Cached return value from self::lacksSameOriginSecurity() */
	private $lacksSameOriginSecurity = null;

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
		} else {
			$request = $this->getRequest();
		}

		$this->mInternalMode = ( $request instanceof FauxRequest );

		// Special handling for the main module: $parent === $this
		parent::__construct( $this, $this->mInternalMode ? 'main_int' : 'main' );

		$config = $this->getConfig();

		if ( !$this->mInternalMode ) {
			// Log if a request with a non-whitelisted Origin header is seen
			// with session cookies.
			$originHeader = $request->getHeader( 'Origin' );
			if ( $originHeader === false ) {
				$origins = [];
			} else {
				$originHeader = trim( $originHeader );
				$origins = preg_split( '/\s+/', $originHeader );
			}
			$sessionCookies = array_intersect(
				array_keys( $_COOKIE ),
				MediaWiki\Session\SessionManager::singleton()->getVaryCookies()
			);
			if ( $origins && $sessionCookies && (
				count( $origins ) !== 1 || !self::matchOrigin(
					$origins[0],
					$config->get( 'CrossSiteAJAXdomains' ),
					$config->get( 'CrossSiteAJAXdomainExceptions' )
				)
			) ) {
				LoggerFactory::getInstance( 'cors' )->warning(
					'Non-whitelisted CORS request with session cookies', [
						'origin' => $originHeader,
						'cookies' => $sessionCookies,
						'ip' => $request->getIP(),
						'userAgent' => $this->getUserAgent(),
						'wiki' => wfWikiID(),
					]
				);
			}

			// If we're in a mode that breaks the same-origin policy, strip
			// user credentials for security.
			if ( $this->lacksSameOriginSecurity() ) {
				global $wgUser;
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

		$this->mModuleMgr = new ApiModuleManager( $this );
		$this->mModuleMgr->addModules( self::$Modules, 'action' );
		$this->mModuleMgr->addModules( $config->get( 'APIModules' ), 'action' );
		$this->mModuleMgr->addModules( self::$Formats, 'format' );
		$this->mModuleMgr->addModules( $config->get( 'APIFormatModules' ), 'format' );

		Hooks::run( 'ApiMain::moduleManager', [ $this->mModuleMgr ] );

		$this->mResult = new ApiResult( $this->getConfig()->get( 'APIMaxResultSize' ) );
		$this->mErrorFormatter = new ApiErrorFormatter_BackCompat( $this->mResult );
		$this->mResult->setErrorFormatter( $this->mErrorFormatter );
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
	 * Get the security flag for the current request
	 * @return bool
	 */
	public function lacksSameOriginSecurity() {
		if ( $this->lacksSameOriginSecurity !== null ) {
			return $this->lacksSameOriginSecurity;
		}

		$request = $this->getRequest();

		// JSONP mode
		if ( $request->getVal( 'callback' ) !== null ) {
			$this->lacksSameOriginSecurity = true;
			return true;
		}

		// Anonymous CORS
		if ( $request->getVal( 'origin' ) === '*' ) {
			$this->lacksSameOriginSecurity = true;
			return true;
		}

		// Header to be used from XMLHTTPRequest when the request might
		// otherwise be used for XSS.
		if ( $request->getHeader( 'Treat-as-Untrusted' ) !== false ) {
			$this->lacksSameOriginSecurity = true;
			return true;
		}

		// Allow extensions to override.
		$this->lacksSameOriginSecurity = !Hooks::run( 'RequestHasSameOriginSecurity', [ $request ] );
		return $this->lacksSameOriginSecurity;
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
		$this->setCacheControl( [
			'max-age' => $maxage,
			's-maxage' => $maxage
		] );
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
		if ( !in_array( $mode, [ 'private', 'public', 'anon-public-user-private' ] ) ) {
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
		$obLevel = ob_get_level();
		ob_start();

		$t = microtime( true );
		$isError = false;
		try {
			$this->executeAction();
			$runTime = microtime( true ) - $t;
			$this->logRequest( $runTime );
			if ( $this->mModule->isWriteMode() && $this->getRequest()->wasPosted() ) {
				$this->getStats()->timing(
					'api.' . $this->mModule->getModuleName() . '.executeTiming', 1000 * $runTime
				);
			}
		} catch ( Exception $e ) {
			$this->handleException( $e );
			$this->logRequest( microtime( true ) - $t, $e );
			$isError = true;
		}

		// Commit DBs and send any related cookies and headers
		MediaWiki::preOutputCommit( $this->getContext() );

		// Send cache headers after any code which might generate an error, to
		// avoid sending public cache headers for errors.
		$this->sendCacheHeaders( $isError );

		// Executing the action might have already messed with the output
		// buffers.
		while ( ob_get_level() > $obLevel ) {
			ob_end_flush();
		}
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
		Hooks::run( 'ApiMain::onException', [ $this, $e ] );

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
			$main->logRequest( 0, $e );
		} catch ( Exception $e2 ) {
			// Nope, even that didn't work. Punt.
			throw $e;
		}

		// Reset cache headers
		$main->sendCacheHeaders( true );

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

		$matchOrigin = false;
		$allowTiming = false;
		$varyOrigin = true;

		if ( $originParam === '*' ) {
			// Request for anonymous CORS
			$matchOrigin = true;
			$allowOrigin = '*';
			$allowCredentials = 'false';
			$varyOrigin = false; // No need to vary
		} else {
			// Non-anonymous CORS, check we allow the domain

			// Origin: header is a space-separated list of origins, check all of them
			$originHeader = $request->getHeader( 'Origin' );
			if ( $originHeader === false ) {
				$origins = [];
			} else {
				$originHeader = trim( $originHeader );
				$origins = preg_split( '/\s+/', $originHeader );
			}

			if ( !in_array( $originParam, $origins ) ) {
				// origin parameter set but incorrect
				// Send a 403 response
				$response->statusHeader( 403 );
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

			$allowOrigin = $originHeader;
			$allowCredentials = 'true';
			$allowTiming = $originHeader;
		}

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

			$response->header( "Access-Control-Allow-Origin: $allowOrigin" );
			$response->header( "Access-Control-Allow-Credentials: $allowCredentials" );
			// http://www.w3.org/TR/resource-timing/#timing-allow-origin
			if ( $allowTiming !== false ) {
				$response->header( "Timing-Allow-Origin: $allowTiming" );
			}

			if ( !$preflight ) {
				$response->header(
					'Access-Control-Expose-Headers: MediaWiki-API-Error, Retry-After, X-Database-Lag'
				);
			}
		}

		if ( $varyOrigin ) {
			$this->getOutput()->addVaryHeader( 'Origin' );
		}

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
		$allowedAuthorHeaders = array_flip( [
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
		] );
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
			[ '\*', '\?' ],
			[ '.*?', '.' ],
			$wildcard
		);

		return "/^https?:\/\/$wildcard$/";
	}

	/**
	 * Send caching headers
	 * @param bool $isError Whether an error response is being output
	 * @since 1.26 added $isError parameter
	 */
	protected function sendCacheHeaders( $isError ) {
		$response = $this->getRequest()->response();
		$out = $this->getOutput();

		$out->addVaryHeader( 'Treat-as-Untrusted' );

		$config = $this->getConfig();

		if ( $config->get( 'VaryOnXFP' ) ) {
			$out->addVaryHeader( 'X-Forwarded-Proto' );
		}

		if ( !$isError && $this->mModule &&
			( $this->getRequest()->getMethod() === 'GET' || $this->getRequest()->getMethod() === 'HEAD' )
		) {
			$etag = $this->mModule->getConditionalRequestData( 'etag' );
			if ( $etag !== null ) {
				$response->header( "ETag: $etag" );
			}
			$lastMod = $this->mModule->getConditionalRequestData( 'last-modified' );
			if ( $lastMod !== null ) {
				$response->header( 'Last-Modified: ' . wfTimestamp( TS_RFC2822, $lastMod ) );
			}
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

		$useKeyHeader = $config->get( 'UseKeyHeader' );
		if ( $this->mCacheMode == 'anon-public-user-private' ) {
			$out->addVaryHeader( 'Cookie' );
			$response->header( $out->getVaryHeader() );
			if ( $useKeyHeader ) {
				$response->header( $out->getKeyHeader() );
				if ( $out->haveCacheVaryCookies() ) {
					// Logged in, mark this request private
					$response->header( "Cache-Control: $privateCache" );
					return;
				}
				// Logged out, send normal public headers below
			} elseif ( MediaWiki\Session\SessionManager::getGlobalSession()->isPersistent() ) {
				// Logged in or otherwise has session (e.g. anonymous users who have edited)
				// Mark request private
				$response->header( "Cache-Control: $privateCache" );

				return;
			} // else no Key and anonymous, send public headers below
		}

		// Send public headers
		$response->header( $out->getVaryHeader() );
		if ( $useKeyHeader ) {
			$response->header( $out->getKeyHeader() );
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
	 * Create an error message for the given exception.
	 *
	 * If the exception is a UsageException then
	 * UsageException::getMessageArray() will be called to create the message.
	 *
	 * @param Exception $e
	 * @return array ['code' => 'some string', 'info' => 'some other string']
	 * @since 1.27
	 */
	protected function errorMessageFromException( $e ) {
		if ( $e instanceof UsageException ) {
			// User entered incorrect parameters - generate error response
			$errMessage = $e->getMessageArray();
		} else {
			$config = $this->getConfig();
			// Something is seriously wrong
			if ( ( $e instanceof DBQueryError ) && !$config->get( 'ShowSQLErrors' ) ) {
				$info = 'Database query error';
			} else {
				$info = "Exception Caught: {$e->getMessage()}";
			}

			$errMessage = [
				'code' => 'internal_api_error_' . get_class( $e ),
				'info' => '[' . WebRequest::getRequestId() . '] ' . $info,
			];
		}
		return $errMessage;
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

		$errMessage = $this->errorMessageFromException( $e );
		if ( $e instanceof UsageException ) {
			// User entered incorrect parameters - generate error response
			$link = wfExpandUrl( wfScript( 'api' ) );
			ApiResult::setContentValue( $errMessage, 'docref', "See $link for API usage" );
		} else {
			// Something is seriously wrong
			if ( $config->get( 'ShowExceptionDetails' ) ) {
				ApiResult::setContentValue(
					$errMessage,
					'trace',
					MWExceptionHandler::getRedactedTraceAsString( $e )
				);
			}
		}

		// Remember all the warnings to re-add them later
		$warnings = $result->getResultData( [ 'warnings' ] );

		$result->reset();
		// Re-add the id
		$requestid = $this->getParameter( 'requestid' );
		if ( !is_null( $requestid ) ) {
			$result->addValue( null, 'requestid', $requestid, ApiResult::NO_SIZE_CHECK );
		}
		if ( $config->get( 'ShowHostnames' ) ) {
			// servedby is especially useful when debugging errors
			$result->addValue( null, 'servedby', wfHostname(), ApiResult::NO_SIZE_CHECK );
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
				$result->addValue( null, 'servedby', wfHostname() );
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
				'See documentation for ApiBase::needsToken for details.'
			);
		}
		if ( $module->needsToken() ) {
			if ( !$module->mustBePosted() ) {
				throw new MWException(
					"Module '{$module->getModuleName()}' must require POST to use tokens."
				);
			}

			if ( !isset( $moduleParams['token'] ) ) {
				$this->dieUsageMsg( [ 'missingparam', 'token' ] );
			}

			$module->requirePostedParameters( [ 'token' ] );

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
	 * Check selected RFC 7232 precondition headers
	 *
	 * RFC 7232 envisions a particular model where you send your request to "a
	 * resource", and for write requests that you can read "the resource" by
	 * changing the method to GET. When the API receives a GET request, it
	 * works out even though "the resource" from RFC 7232's perspective might
	 * be many resources from MediaWiki's perspective. But it totally fails for
	 * a POST, since what HTTP sees as "the resource" is probably just
	 * "/api.php" with all the interesting bits in the body.
	 *
	 * Therefore, we only support RFC 7232 precondition headers for GET (and
	 * HEAD). That means we don't need to bother with If-Match and
	 * If-Unmodified-Since since they only apply to modification requests.
	 *
	 * And since we don't support Range, If-Range is ignored too.
	 *
	 * @since 1.26
	 * @param ApiBase $module Api module being used
	 * @return bool True on success, false should exit immediately
	 */
	protected function checkConditionalRequestHeaders( $module ) {
		if ( $this->mInternalMode ) {
			// No headers to check in internal mode
			return true;
		}

		if ( $this->getRequest()->getMethod() !== 'GET' && $this->getRequest()->getMethod() !== 'HEAD' ) {
			// Don't check POSTs
			return true;
		}

		$return304 = false;

		$ifNoneMatch = array_diff(
			$this->getRequest()->getHeader( 'If-None-Match', WebRequest::GETHEADER_LIST ) ?: [],
			[ '' ]
		);
		if ( $ifNoneMatch ) {
			if ( $ifNoneMatch === [ '*' ] ) {
				// API responses always "exist"
				$etag = '*';
			} else {
				$etag = $module->getConditionalRequestData( 'etag' );
			}
		}
		if ( $ifNoneMatch && $etag !== null ) {
			$test = substr( $etag, 0, 2 ) === 'W/' ? substr( $etag, 2 ) : $etag;
			$match = array_map( function ( $s ) {
				return substr( $s, 0, 2 ) === 'W/' ? substr( $s, 2 ) : $s;
			}, $ifNoneMatch );
			$return304 = in_array( $test, $match, true );
		} else {
			$value = trim( $this->getRequest()->getHeader( 'If-Modified-Since' ) );

			// Some old browsers sends sizes after the date, like this:
			//  Wed, 20 Aug 2003 06:51:19 GMT; length=5202
			// Ignore that.
			$i = strpos( $value, ';' );
			if ( $i !== false ) {
				$value = trim( substr( $value, 0, $i ) );
			}

			if ( $value !== '' ) {
				try {
					$ts = new MWTimestamp( $value );
					if (
						// RFC 7231 IMF-fixdate
						$ts->getTimestamp( TS_RFC2822 ) === $value ||
						// RFC 850
						$ts->format( 'l, d-M-y H:i:s' ) . ' GMT' === $value ||
						// asctime (with and without space-padded day)
						$ts->format( 'D M j H:i:s Y' ) === $value ||
						$ts->format( 'D M  j H:i:s Y' ) === $value
					) {
						$lastMod = $module->getConditionalRequestData( 'last-modified' );
						if ( $lastMod !== null ) {
							// Mix in some MediaWiki modification times
							$modifiedTimes = [
								'page' => $lastMod,
								'user' => $this->getUser()->getTouched(),
								'epoch' => $this->getConfig()->get( 'CacheEpoch' ),
							];
							if ( $this->getConfig()->get( 'UseSquid' ) ) {
								// T46570: the core page itself may not change, but resources might
								$modifiedTimes['sepoch'] = wfTimestamp(
									TS_MW, time() - $this->getConfig()->get( 'SquidMaxage' )
								);
							}
							Hooks::run( 'OutputPageCheckLastModified', [ &$modifiedTimes, $this->getOutput() ] );
							$lastMod = max( $modifiedTimes );
							$return304 = wfTimestamp( TS_MW, $lastMod ) <= $ts->getTimestamp( TS_MW );
						}
					}
				} catch ( TimestampException $e ) {
					// Invalid timestamp, ignore it
				}
			}
		}

		if ( $return304 ) {
			$this->getRequest()->response()->statusHeader( 304 );

			// Avoid outputting the compressed representation of a zero-length body
			MediaWiki\suppressWarnings();
			ini_set( 'zlib.output_compression', 0 );
			MediaWiki\restoreWarnings();
			wfClearOutputBuffers();

			return false;
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
			} elseif ( !$user->isAllowed( 'writeapi' ) ) {
				$this->dieUsageMsg( 'writerequired' );
			} elseif ( $this->getRequest()->getHeader( 'Promise-Non-Write-API-Action' ) ) {
				$this->dieUsage(
					'Promise-Non-Write-API-Action HTTP header cannot be sent to write API modules',
					'promised-nonwrite-api'
				);
			}

			$this->checkReadOnly( $module );
		}

		// Allow extensions to stop execution for arbitrary reasons.
		$message = false;
		if ( !Hooks::run( 'ApiCheckCanExecute', [ $module, $user, &$message ] ) ) {
			$this->dieUsageMsg( $message );
		}
	}

	/**
	 * Check if the DB is read-only for this user
	 * @param ApiBase $module An Api module
	 */
	protected function checkReadOnly( $module ) {
		if ( wfReadOnly() ) {
			$this->dieReadOnly();
		}

		if ( $module->isWriteMode()
			&& $this->getUser()->isBot()
			&& wfGetLB()->getServerCount() > 1
		) {
			$this->checkBotReadOnly();
		}
	}

	/**
	 * Check whether we are readonly for bots
	 */
	private function checkBotReadOnly() {
		// Figure out how many servers have passed the lag threshold
		$numLagged = 0;
		$lagLimit = $this->getConfig()->get( 'APIMaxLagThreshold' );
		$laggedServers = [];
		$loadBalancer = wfGetLB();
		foreach ( $loadBalancer->getLagTimes() as $serverIndex => $lag ) {
			if ( $lag > $lagLimit ) {
				++$numLagged;
				$laggedServers[] = $loadBalancer->getServerName( $serverIndex ) . " ({$lag}s)";
			}
		}

		// If a majority of replica DBs are too lagged then disallow writes
		$replicaCount = wfGetLB()->getServerCount() - 1;
		if ( $numLagged >= ceil( $replicaCount / 2 ) ) {
			$laggedServers = implode( ', ', $laggedServers );
			wfDebugLog(
				'api-readonly',
				"Api request failed as read only because the following DBs are lagged: $laggedServers"
			);

			$parsed = $this->parseMsg( [ 'readonlytext' ] );
			$this->dieUsage(
				$parsed['info'],
				$parsed['code'],
				/* http error */
				0,
				[ 'readonlyreason' => "Waiting for $numLagged lagged database(s)" ]
			);
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
		if ( isset( $params['assertuser'] ) ) {
			$assertUser = User::newFromName( $params['assertuser'], false );
			if ( !$assertUser || !$this->getUser()->equals( $assertUser ) ) {
				$this->dieUsage(
					'Assertion that the user is "' . $params['assertuser'] . '" failed',
					'assertnameduserfailed'
				);
			}
		}
	}

	/**
	 * Check POST for external response and setup result printer
	 * @param ApiBase $module An Api module
	 * @param array $params An array with the request parameters
	 */
	protected function setupExternalResponse( $module, $params ) {
		$request = $this->getRequest();
		if ( !$request->wasPosted() && $module->mustBePosted() ) {
			// Module requires POST. GET request might still be allowed
			// if $wgDebugApi is true, otherwise fail.
			$this->dieUsageMsgOrDebug( [ 'mustbeposted', $this->mAction ] );
		}

		// See if custom printer is used
		$this->mPrinter = $module->getCustomPrinter();
		if ( is_null( $this->mPrinter ) ) {
			// Create an appropriate printer
			$this->mPrinter = $this->createPrinterByName( $params['format'] );
		}

		if ( $request->getProtocol() === 'http' && (
			$request->getSession()->shouldForceHTTPS() ||
			( $this->getUser()->isLoggedIn() &&
				$this->getUser()->requiresHTTPS() )
		) ) {
			$this->logFeatureUsage( 'https-expected' );
			$this->setWarning( 'HTTP used when HTTPS was expected' );
		}
	}

	/**
	 * Execute the actual module, without any error handling
	 */
	protected function executeAction() {
		$params = $this->setupExecuteAction();
		$module = $this->setupModule();
		$this->mModule = $module;

		if ( !$this->mInternalMode ) {
			$this->setRequestExpectations( $module );
		}

		$this->checkExecutePermissions( $module );

		if ( !$this->checkMaxLag( $module, $params ) ) {
			return;
		}

		if ( !$this->checkConditionalRequestHeaders( $module ) ) {
			return;
		}

		if ( !$this->mInternalMode ) {
			$this->setupExternalResponse( $module, $params );
		}

		$this->checkAsserts( $params );

		// Execute
		$module->execute();
		Hooks::run( 'APIAfterExecute', [ &$module ] );

		$this->reportUnusedParams();

		if ( !$this->mInternalMode ) {
			// append Debug information
			MWDebug::appendDebugInfoToApiResult( $this->getContext(), $this->getResult() );

			// Print result data
			$this->printResult( false );
		}
	}

	/**
	 * Set database connection, query, and write expectations given this module request
	 * @param ApiBase $module
	 */
	protected function setRequestExpectations( ApiBase $module ) {
		$limits = $this->getConfig()->get( 'TrxProfilerLimits' );
		$trxProfiler = Profiler::instance()->getTransactionProfiler();
		$trxProfiler->setLogger( LoggerFactory::getInstance( 'DBPerformance' ) );
		if ( $this->getRequest()->hasSafeMethod() ) {
			$trxProfiler->setExpectations( $limits['GET'], __METHOD__ );
		} elseif ( $this->getRequest()->wasPosted() && !$module->isWriteMode() ) {
			$trxProfiler->setExpectations( $limits['POST-nonwrite'], __METHOD__ );
			$this->getRequest()->markAsSafeRequest();
		} else {
			$trxProfiler->setExpectations( $limits['POST'], __METHOD__ );
		}
	}

	/**
	 * Log the preceding request
	 * @param float $time Time in seconds
	 * @param Exception $e Exception caught while processing the request
	 */
	protected function logRequest( $time, $e = null ) {
		$request = $this->getRequest();
		$logCtx = [
			'ts' => time(),
			'ip' => $request->getIP(),
			'userAgent' => $this->getUserAgent(),
			'wiki' => wfWikiID(),
			'timeSpentBackend' => (int) round( $time * 1000 ),
			'hadError' => $e !== null,
			'errorCodes' => [],
			'params' => [],
		];

		if ( $e ) {
			$logCtx['errorCodes'][] = $this->errorMessageFromException( $e )['code'];
		}

		// Construct space separated message for 'api' log channel
		$msg = "API {$request->getMethod()} " .
			wfUrlencode( str_replace( ' ', '_', $this->getUser()->getName() ) ) .
			" {$logCtx['ip']} " .
			"T={$logCtx['timeSpentBackend']}ms";

		$sensitive = array_flip( $this->getSensitiveParams() );
		foreach ( $this->getParamsUsed() as $name ) {
			$value = $request->getVal( $name );
			if ( $value === null ) {
				continue;
			}

			if ( isset( $sensitive[$name] ) ) {
				$value = '[redacted]';
				$encValue = '[redacted]';
			} elseif ( strlen( $value ) > 256 ) {
				$value = substr( $value, 0, 256 );
				$encValue = $this->encodeRequestLogValue( $value ) . '[...]';
			} else {
				$encValue = $this->encodeRequestLogValue( $value );
			}

			$logCtx['params'][$name] = $value;
			$msg .= " {$name}={$encValue}";
		}

		wfDebugLog( 'api', $msg, 'private' );
		// ApiAction channel is for structured data consumers
		wfDebugLog( 'ApiAction', '', 'private', $logCtx );
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
	 * Mark parameters as used
	 * @param string|string[] $params
	 */
	public function markParamsUsed( $params ) {
		$this->mParamsUsed += array_fill_keys( (array)$params, true );
	}

	/**
	 * Get the request parameters that should be considered sensitive
	 * @since 1.28
	 * @return array
	 */
	protected function getSensitiveParams() {
		return array_keys( $this->mParamsSensitive );
	}

	/**
	 * Mark parameters as sensitive
	 * @since 1.28
	 * @param string|string[] $params
	 */
	public function markParamsSensitive( $params ) {
		$this->mParamsSensitive += array_fill_keys( (array)$params, true );
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
				// See bug 10262 for why we don't just implode( '|', ... ) the
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
				[ $this->mPrinter, 'encodeParamName' ],
				array_keys( $this->mPrinter->getFinalParams() ?: [] )
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
		return [
			'action' => [
				ApiBase::PARAM_DFLT => 'help',
				ApiBase::PARAM_TYPE => 'submodule',
			],
			'format' => [
				ApiBase::PARAM_DFLT => ApiMain::API_DEFAULT_FORMAT,
				ApiBase::PARAM_TYPE => 'submodule',
			],
			'maxlag' => [
				ApiBase::PARAM_TYPE => 'integer'
			],
			'smaxage' => [
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_DFLT => 0
			],
			'maxage' => [
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_DFLT => 0
			],
			'assert' => [
				ApiBase::PARAM_TYPE => [ 'user', 'bot' ]
			],
			'assertuser' => [
				ApiBase::PARAM_TYPE => 'user',
			],
			'requestid' => null,
			'servedby' => false,
			'curtimestamp' => false,
			'origin' => null,
			'uselang' => [
				ApiBase::PARAM_DFLT => 'user',
			],
		];
	}

	/** @see ApiBase::getExamplesMessages() */
	protected function getExamplesMessages() {
		return [
			'action=help'
				=> 'apihelp-help-example-main',
			'action=help&recursivesubmodules=1'
				=> 'apihelp-help-example-recursive',
		];
	}

	public function modifyHelp( array &$help, array $options, array &$tocData ) {
		// Wish PHP had an "array_insert_before". Instead, we have to manually
		// reindex the array to get 'permissions' in the right place.
		$oldHelp = $help;
		$help = [];
		foreach ( $oldHelp as $k => $v ) {
			if ( $k === 'submodules' ) {
				$help['permissions'] = '';
			}
			$help[$k] = $v;
		}
		$help['datatypes'] = '';
		$help['credits'] = '';

		// Fill 'permissions'
		$help['permissions'] .= Html::openElement( 'div',
			[ 'class' => 'apihelp-block apihelp-permissions' ] );
		$m = $this->msg( 'api-help-permissions' );
		if ( !$m->isDisabled() ) {
			$help['permissions'] .= Html::rawElement( 'div', [ 'class' => 'apihelp-block-head' ],
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

		// Fill 'datatypes' and 'credits', if applicable
		if ( empty( $options['nolead'] ) ) {
			$level = $options['headerlevel'];
			$tocnumber = &$options['tocnumber'];

			$header = $this->msg( 'api-help-datatypes-header' )->parse();

			// Add an additional span with sanitized ID
			if ( !$this->getConfig()->get( 'ExperimentalHtmlIds' ) ) {
				$header = Html::element( 'span', [ 'id' => Sanitizer::escapeId( 'main/datatypes' ) ] ) .
					$header;
			}
			$help['datatypes'] .= Html::rawElement( 'h' . min( 6, $level ),
				[ 'id' => 'main/datatypes', 'class' => 'apihelp-header' ],
				$header
			);
			$help['datatypes'] .= $this->msg( 'api-help-datatypes' )->parseAsBlock();
			if ( !isset( $tocData['main/datatypes'] ) ) {
				$tocnumber[$level]++;
				$tocData['main/datatypes'] = [
					'toclevel' => count( $tocnumber ),
					'level' => $level,
					'anchor' => 'main/datatypes',
					'line' => $header,
					'number' => implode( '.', $tocnumber ),
					'index' => false,
				];
			}

			// Add an additional span with sanitized ID
			if ( !$this->getConfig()->get( 'ExperimentalHtmlIds' ) ) {
				$header = Html::element( 'span', [ 'id' => Sanitizer::escapeId( 'main/credits' ) ] ) .
					$header;
			}
			$header = $this->msg( 'api-credits-header' )->parse();
			$help['credits'] .= Html::rawElement( 'h' . min( 6, $level ),
				[ 'id' => 'main/credits', 'class' => 'apihelp-header' ],
				$header
			);
			$help['credits'] .= $this->msg( 'api-credits' )->useDatabase( false )->parseAsBlock();
			if ( !isset( $tocData['main/credits'] ) ) {
				$tocnumber[$level]++;
				$tocData['main/credits'] = [
					'toclevel' => count( $tocnumber ),
					'level' => $level,
					'anchor' => 'main/credits',
					'line' => $header,
					'number' => implode( '.', $tocnumber ),
					'index' => false,
				];
			}
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

		// This should never happen, so throw an exception about it that will
		// hopefully get logged with a backtrace (T138585)
		if ( !is_string( $codestr ) || $codestr === '' ) {
			throw new InvalidArgumentException( 'Invalid $codestr, was ' .
				( $codestr === '' ? 'empty string' : gettype( $codestr ) )
			);
		}
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
		$result = [
			'code' => $this->mCodestr,
			'info' => $this->getMessage()
		];
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
