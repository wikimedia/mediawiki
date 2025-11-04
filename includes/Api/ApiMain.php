<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * @license GPL-2.0-or-later
 * @file
 * @defgroup API API
 */

namespace MediaWiki\Api;

use LogicException;
use MediaWiki;
use MediaWiki\Api\Validator\ApiParamValidator;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\Debug\MWDebug;
use MediaWiki\Exception\ILocalizedException;
use MediaWiki\Exception\MWExceptionHandler;
use MediaWiki\Exception\MWExceptionRenderer;
use MediaWiki\Html\Html;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\Profiler\ProfilingContext;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\WebRequest;
use MediaWiki\Request\WebRequestUpload;
use MediaWiki\Rest\HeaderParser\Origin;
use MediaWiki\StubObject\StubGlobalUser;
use MediaWiki\User\UserRigorOptions;
use MediaWiki\WikiMap\WikiMap;
use Profiler;
use Throwable;
use UnexpectedValueException;
use Wikimedia\AtEase\AtEase;
use Wikimedia\Message\ListType;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;
use Wikimedia\ScopedCallback;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\Timestamp\ConvertibleTimestamp;
use Wikimedia\Timestamp\TimestampException;

/**
 * This is the main API class, used for both external and internal processing.
 * When executed, it will create the requested formatter object,
 * instantiate and execute an object associated with the needed action,
 * and use formatter to print results.
 * In case of an exception, an error message will be printed using the same formatter.
 *
 * To use API from another application, run it using MediaWiki\Request\FauxRequest object, in which
 * case any internal exceptions will not be handled but passed up to the caller.
 * After successful execution, use getResult() for the resulting data.
 *
 * @newable
 * @note marked as newable in 1.35 for lack of a better alternative,
 *       but should use a factory in the future.
 * @ingroup API
 */
class ApiMain extends ApiBase {
	/**
	 * When no format parameter is given, this format will be used
	 */
	private const API_DEFAULT_FORMAT = 'jsonfm';

	/**
	 * When no uselang parameter is given, this language will be used
	 */
	private const API_DEFAULT_USELANG = 'user';

	/**
	 * List of available modules: action name => module class
	 */
	private const MODULES = [
		'login' => [
			'class' => ApiLogin::class,
			'services' => [
				'AuthManager',
				'UserIdentityUtils'
			],
		],
		'clientlogin' => [
			'class' => ApiClientLogin::class,
			'services' => [
				'AuthManager',
				'UrlUtils',
			],
		],
		'logout' => [
			'class' => ApiLogout::class,
		],
		'createaccount' => [
			'class' => ApiAMCreateAccount::class,
			'services' => [
				'AuthManager',
				'UrlUtils',
			],
		],
		'linkaccount' => [
			'class' => ApiLinkAccount::class,
			'services' => [
				'AuthManager',
				'UrlUtils',
			],
		],
		'unlinkaccount' => [
			'class' => ApiRemoveAuthenticationData::class,
			'services' => [
				'AuthManager',
			],
		],
		'changeauthenticationdata' => [
			'class' => ApiChangeAuthenticationData::class,
			'services' => [
				'AuthManager',
			],
		],
		'removeauthenticationdata' => [
			'class' => ApiRemoveAuthenticationData::class,
			'services' => [
				'AuthManager',
			],
		],
		'resetpassword' => [
			'class' => ApiResetPassword::class,
			'services' => [
				'PasswordReset',
			]
		],
		'query' => [
			'class' => ApiQuery::class,
			'services' => [
				'ObjectFactory',
				'WikiExporterFactory',
				'TitleFormatter',
				'TitleFactory',
			]
		],
		'expandtemplates' => [
			'class' => ApiExpandTemplates::class,
			'services' => [
				'RevisionStore',
				'ParserFactory',
			]
		],
		'parse' => [
			'class' => ApiParse::class,
			'services' => [
				'RevisionLookup',
				'SkinFactory',
				'LanguageNameUtils',
				'LinkBatchFactory',
				'LinkCache',
				'ContentHandlerFactory',
				'ParserFactory',
				'WikiPageFactory',
				'ContentRenderer',
				'ContentTransformer',
				'CommentFormatter',
				'TempUserCreator',
				'UserFactory',
				'UrlUtils',
				'TitleFormatter',
				'JsonCodec',
			]
		],
		'stashedit' => [
			'class' => ApiStashEdit::class,
			'services' => [
				'ContentHandlerFactory',
				'PageEditStash',
				'RevisionLookup',
				'StatsFactory',
				'WikiPageFactory',
				'TempUserCreator',
				'UserFactory',
			]
		],
		'opensearch' => [
			'class' => ApiOpenSearch::class,
			'services' => [
				'LinkBatchFactory',
				'SearchEngineConfig',
				'SearchEngineFactory',
				'UrlUtils',
			]
		],
		'feedcontributions' => [
			'class' => ApiFeedContributions::class,
			'services' => [
				'RevisionStore',
				'LinkRenderer',
				'LinkBatchFactory',
				'HookContainer',
				'DBLoadBalancerFactory',
				'NamespaceInfo',
				'UserFactory',
				'CommentFormatter',
			]
		],
		'feedrecentchanges' => [
			'class' => ApiFeedRecentChanges::class,
			'services' => [
				'SpecialPageFactory',
				'TempUserConfig',
			]
		],
		'feedwatchlist' => [
			'class' => ApiFeedWatchlist::class,
			'services' => [
				'ParserFactory',
			]
		],
		'help' => [
			'class' => ApiHelp::class,
			'services' => [
				'SkinFactory',
			]
		],
		'paraminfo' => [
			'class' => ApiParamInfo::class,
			'services' => [
				'UserFactory',
			],
		],
		'rsd' => [
			'class' => ApiRsd::class,
		],
		'compare' => [
			'class' => ApiComparePages::class,
			'services' => [
				'RevisionStore',
				'ArchivedRevisionLookup',
				'SlotRoleRegistry',
				'ContentHandlerFactory',
				'ContentTransformer',
				'CommentFormatter',
				'TempUserCreator',
				'UserFactory',
			]
		],
		'checktoken' => [
			'class' => ApiCheckToken::class,
		],
		'cspreport' => [
			'class' => ApiCSPReport::class,
			'services' => [
				'UrlUtils',
			]
		],
		'validatepassword' => [
			'class' => ApiValidatePassword::class,
			'services' => [
				'AuthManager',
				'UserFactory',
			]
		],

		// Write modules
		'purge' => [
			'class' => ApiPurge::class,
			'services' => [
				'WikiPageFactory',
				'TitleFormatter',
			],
		],
		'setnotificationtimestamp' => [
			'class' => ApiSetNotificationTimestamp::class,
			'services' => [
				'DBLoadBalancerFactory',
				'RevisionStore',
				'WatchedItemStore',
				'TitleFormatter',
				'TitleFactory',
			]
		],
		'rollback' => [
			'class' => ApiRollback::class,
			'services' => [
				'RollbackPageFactory',
				'WatchlistManager',
				'WatchedItemStore',
				'UserOptionsLookup',
			]
		],
		'delete' => [
			'class' => ApiDelete::class,
			'services' => [
				'RepoGroup',
				'WatchlistManager',
				'WatchedItemStore',
				'UserOptionsLookup',
				'DeletePageFactory',
			]
		],
		'undelete' => [
			'class' => ApiUndelete::class,
			'services' => [
				'WatchlistManager',
				'WatchedItemStore',
				'UserOptionsLookup',
				'UndeletePageFactory',
				'WikiPageFactory',
			]
		],
		'protect' => [
			'class' => ApiProtect::class,
			'services' => [
				'WatchlistManager',
				'WatchedItemStore',
				'UserOptionsLookup',
				'RestrictionStore',
			]
		],
		'block' => [
			'class' => ApiBlock::class,
			'services' => [
				'BlockPermissionCheckerFactory',
				'BlockUserFactory',
				'TitleFactory',
				'UserIdentityLookup',
				'WatchedItemStore',
				'BlockTargetFactory',
				'BlockActionInfo',
				'DatabaseBlockStore',
				'WatchlistManager',
				'UserOptionsLookup',
			]
		],
		'unblock' => [
			'class' => ApiUnblock::class,
			'services' => [
				'BlockPermissionCheckerFactory',
				'UnblockUserFactory',
				'UserIdentityLookup',
				'WatchedItemStore',
				'WatchlistManager',
				'UserOptionsLookup',
				'DatabaseBlockStore',
				'BlockTargetFactory',
			]
		],
		'move' => [
			'class' => ApiMove::class,
			'services' => [
				'MovePageFactory',
				'RepoGroup',
				'WatchlistManager',
				'WatchedItemStore',
				'UserOptionsLookup',
			]
		],
		'edit' => [
			'class' => ApiEditPage::class,
			'services' => [
				'ContentHandlerFactory',
				'RevisionLookup',
				'WatchedItemStore',
				'WikiPageFactory',
				'WatchlistManager',
				'UserOptionsLookup',
				'RedirectLookup',
				'TempUserCreator',
				'UserFactory',
			]
		],
		'upload' => [
			'class' => ApiUpload::class,
			'services' => [
				'JobQueueGroup',
				'WatchlistManager',
				'WatchedItemStore',
				'UserOptionsLookup',
			]
		],
		'filerevert' => [
			'class' => ApiFileRevert::class,
			'services' => [
				'RepoGroup',
			]
		],
		'emailuser' => [
			'class' => ApiEmailUser::class,
			'services' => [
				'EmailUserFactory',
				'UserFactory',
			]
		],
		'watch' => [
			'class' => ApiWatch::class,
			'services' => [
				'WatchlistManager',
				'TitleFormatter',
			]
		],
		'patrol' => [
			'class' => ApiPatrol::class,
			'services' => [
				'RevisionStore',
				'PatrolManager',
				'RecentChangeLookup',
			]
		],
		'import' => [
			'class' => ApiImport::class,
			'services' => [
				'WikiImporterFactory',
			]
		],
		'clearhasmsg' => [
			'class' => ApiClearHasMsg::class,
			'services' => [
				'TalkPageNotificationManager',
			]
		],
		'userrights' => [
			'class' => ApiUserrights::class,
			'services' => [
				'UserGroupManager',
				'WatchedItemStore',
				'WatchlistManager',
				'UserOptionsLookup',
				'UserGroupAssignmentService',
				'MultiFormatUserIdentityLookup',
			]
		],
		'options' => [
			'class' => ApiOptions::class,
			'services' => [
				'UserOptionsManager',
				'PreferencesFactory',
			],
		],
		'imagerotate' => [
			'class' => ApiImageRotate::class,
			'services' => [
				'RepoGroup',
				'TempFSFileFactory',
				'TitleFactory',
			]
		],
		'revisiondelete' => [
			'class' => ApiRevisionDelete::class,
		],
		'managetags' => [
			'class' => ApiManageTags::class,
		],
		'tag' => [
			'class' => ApiTag::class,
			'services' => [
				'DBLoadBalancerFactory',
				'RevisionStore',
				'ChangeTagsStore',
				'RecentChangeLookup',
			]
		],
		'mergehistory' => [
			'class' => ApiMergeHistory::class,
			'services' => [
				'MergeHistoryFactory',
			],
		],
		'setpagelanguage' => [
			'class' => ApiSetPageLanguage::class,
			'services' => [
				'DBLoadBalancerFactory',
				'LanguageNameUtils',
			]
		],
		'changecontentmodel' => [
			'class' => ApiChangeContentModel::class,
			'services' => [
				'ContentHandlerFactory',
				'ContentModelChangeFactory',
			]
		],
		'acquiretempusername' => [
			'class' => ApiAcquireTempUserName::class,
			'services' => [
				'TempUserCreator',
			]
		],
	];

	/**
	 * List of available formats: format name => format class
	 */
	private const FORMATS = [
		'json' => [
			'class' => ApiFormatJson::class,
		],
		'jsonfm' => [
			'class' => ApiFormatJson::class,
		],
		'php' => [
			'class' => ApiFormatPhp::class,
		],
		'phpfm' => [
			'class' => ApiFormatPhp::class,
		],
		'xml' => [
			'class' => ApiFormatXml::class,
		],
		'xmlfm' => [
			'class' => ApiFormatXml::class,
		],
		'rawfm' => [
			'class' => ApiFormatJson::class,
		],
		'none' => [
			'class' => ApiFormatNone::class,
		],
	];

	/**
	 * List of user roles that are specifically relevant to the API.
	 * [ 'right' => [ 'msg'    => 'Some message with a $1',
	 *                'params' => [ $someVarToSubst ] ],
	 * ];
	 */
	private const RIGHTS_MAP = [
		'apihighlimits' => [
			'msg' => 'api-help-right-apihighlimits',
			'params' => [ ApiBase::LIMIT_SML2, ApiBase::LIMIT_BIG2 ]
		]
	];

	/** @var ApiFormatBase|null */
	private $mPrinter;

	/** @var ApiModuleManager */
	private $mModuleMgr;

	/** @var ApiResult */
	private $mResult;

	/** @var ApiErrorFormatter */
	private $mErrorFormatter;

	/** @var ApiParamValidator */
	private $mParamValidator;

	/** @var ApiContinuationManager|null */
	private $mContinuationManager;

	/** @var string|null */
	private $mAction;

	/** @var bool */
	private $mEnableWrite;

	/** @var bool */
	private $mInternalMode;

	/** @var ApiBase */
	private $mModule;

	/** @var string */
	private $mCacheMode = 'private';

	/** @var array */
	private $mCacheControl = [];

	/** @var array */
	private $mParamsUsed = [];

	/** @var array */
	private $mParamsSensitive = [];

	/** @var bool|null Cached return value from self::lacksSameOriginSecurity() */
	private $lacksSameOriginSecurity = null;

	/** @var StatsFactory */
	private $statsFactory;

	/**
	 * Constructs an instance of ApiMain that utilizes the module and format specified by $request.
	 *
	 * @stable to call
	 * @param IContextSource|WebRequest|null $context If this is an instance of
	 *    MediaWiki\Request\FauxRequest, errors are thrown and no printing occurs
	 * @param bool $enableWrite Should be set to true if the api may modify data
	 * @param bool|null $internal Whether the API request is an internal faux
	 *        request. If null or not given, the request is assumed to be internal
	 *        if $context contains a FauxRequest.
	 */
	public function __construct( $context = null, $enableWrite = false, $internal = null ) {
		if ( $context === null ) {
			$context = RequestContext::getMain();
		} elseif ( $context instanceof WebRequest ) {
			// BC for pre-1.19
			$request = $context;
			$context = RequestContext::getMain();
		}
		// We set a derivative context so we can change stuff later
		$derivativeContext = new DerivativeContext( $context );
		$this->setContext( $derivativeContext );

		if ( isset( $request ) ) {
			$derivativeContext->setRequest( $request );
		} else {
			$request = $this->getRequest();
		}

		$this->mInternalMode = $internal ?? ( $request instanceof FauxRequest );

		// Special handling for the main module: $parent === $this
		parent::__construct( $this, $this->mInternalMode ? 'main_int' : 'main' );

		$config = $this->getConfig();
		// TODO inject stuff, see T265644
		$services = MediaWikiServices::getInstance();

		if ( !$this->mInternalMode ) {
			// If we're in a mode that breaks the same-origin policy, strip
			// user credentials for security.
			if ( $this->lacksSameOriginSecurity() ) {
				wfDebug( "API: stripping user credentials when the same-origin policy is not applied" );
				$user = $services->getUserFactory()->newAnonymous();
				StubGlobalUser::setUser( $user );
				$derivativeContext->setUser( $user );
				$request->response()->header( 'MediaWiki-Login-Suppressed: true' );
			}
		}

		$this->mParamValidator = new ApiParamValidator(
			$this,
			$services->getObjectFactory()
		);

		$this->statsFactory = $services->getStatsFactory();

		$this->mResult =
			new ApiResult( $this->getConfig()->get( MainConfigNames::APIMaxResultSize ) );

		// Setup uselang. This doesn't use $this->getParameter()
		// because we're not ready to handle errors yet.
		// Optimisation: Avoid slow getVal(), this isn't user-generated content.
		$uselang = $request->getRawVal( 'uselang' ) ?? self::API_DEFAULT_USELANG;
		if ( $uselang === 'user' ) {
			// Assume the parent context is going to return the user language
			// for uselang=user (see T85635).
		} else {
			if ( $uselang === 'content' ) {
				$uselang = $services->getContentLanguageCode()->toString();
			}
			$code = RequestContext::sanitizeLangCode( $uselang );
			$derivativeContext->setLanguage( $code );
			if ( !$this->mInternalMode ) {
				// phpcs:disable MediaWiki.Usage.ExtendClassUsage.FunctionVarUsage
				global $wgLang;
				$wgLang = $derivativeContext->getLanguage();
				RequestContext::getMain()->setLanguage( $wgLang );
				// phpcs:enable
			}
		}

		// Set up the error formatter. This doesn't use $this->getParameter()
		// because we're not ready to handle errors yet.
		// Optimisation: Avoid slow getVal(), this isn't user-generated content.
		$errorFormat = $request->getRawVal( 'errorformat' ) ?? 'bc';
		$errorLangCode = $request->getRawVal( 'errorlang' ) ?? 'uselang';
		$errorsUseDB = $request->getCheck( 'errorsuselocal' );
		if ( in_array( $errorFormat, [ 'plaintext', 'wikitext', 'html', 'raw', 'none' ], true ) ) {
			if ( $errorLangCode === 'uselang' ) {
				$errorLang = $this->getLanguage();
			} elseif ( $errorLangCode === 'content' ) {
				$errorLang = $services->getContentLanguage();
			} else {
				$errorLangCode = RequestContext::sanitizeLangCode( $errorLangCode );
				$errorLang = $services->getLanguageFactory()->getLanguage( $errorLangCode );
			}
			$this->mErrorFormatter = new ApiErrorFormatter(
				$this->mResult,
				$errorLang,
				$errorFormat,
				$errorsUseDB
			);
		} else {
			$this->mErrorFormatter = new ApiErrorFormatter_BackCompat( $this->mResult );
		}
		$this->mResult->setErrorFormatter( $this->getErrorFormatter() );

		$this->mModuleMgr = new ApiModuleManager(
			$this,
			$services->getObjectFactory()
		);
		$this->mModuleMgr->addModules( self::MODULES, 'action' );
		$this->mModuleMgr->addModules( $config->get( MainConfigNames::APIModules ), 'action' );
		$this->mModuleMgr->addModules( self::FORMATS, 'format' );
		$this->mModuleMgr->addModules( $config->get( MainConfigNames::APIFormatModules ), 'format' );

		$this->getHookRunner()->onApiMain__moduleManager( $this->mModuleMgr );

		$this->mContinuationManager = null;
		$this->mEnableWrite = $enableWrite;
	}

	/**
	 * Return true if the API was started by other PHP code using MediaWiki\Request\FauxRequest
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
		if ( $request->getCheck( 'callback' ) ||
			// Anonymous CORS
			$request->getRawVal( 'origin' ) === '*' ||
			// Header to be used from XMLHTTPRequest when the request might
			// otherwise be used for XSS.
			$request->getHeader( 'Treat-as-Untrusted' ) !== false ||
			(
				// Authenticated CORS with unsupported session provider (including preflight request)
				$request->getCheck( 'crossorigin' ) &&
				!$request->getSession()->getProvider()->safeAgainstCsrf()
			)
		) {
			$this->lacksSameOriginSecurity = true;
			return true;
		}

		// Allow extensions to override.
		$this->lacksSameOriginSecurity = !$this->getHookRunner()
			->onRequestHasSameOriginSecurity( $request );
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
	 * @return ApiContinuationManager|null
	 */
	public function getContinuationManager() {
		return $this->mContinuationManager;
	}

	/**
	 * @param ApiContinuationManager|null $manager
	 */
	public function setContinuationManager( ?ApiContinuationManager $manager = null ) {
		if ( $manager !== null && $this->mContinuationManager !== null ) {
			throw new UnexpectedValueException(
				__METHOD__ . ': tried to set manager from ' . $manager->getSource() .
				' when a manager is already set from ' . $this->mContinuationManager->getSource()
			);
		}
		$this->mContinuationManager = $manager;
	}

	public function getParamValidator(): ApiParamValidator {
		return $this->mParamValidator;
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
			wfDebug( __METHOD__ . ": unrecognised cache mode \"$mode\"" );

			// Ignore for forwards-compatibility
			return;
		}

		if ( !$this->getPermissionManager()->isEveryoneAllowed( 'read' ) ) {
			// Private wiki, only private headers
			if ( $mode !== 'private' ) {
				wfDebug( __METHOD__ . ": ignoring request for $mode cache mode, private wiki" );

				return;
			}
		}

		if ( $mode === 'public' && $this->getParameter( 'uselang' ) === 'user' ) {
			// User language is used for i18n, so we don't want to publicly
			// cache. Anons are ok, because if they have non-default language
			// then there's an appropriate Vary header set by whatever set
			// their non-default language.
			wfDebug( __METHOD__ . ": downgrading cache mode 'public' to " .
				"'anon-public-user-private' due to uselang=user" );
			$mode = 'anon-public-user-private';
		}

		wfDebug( __METHOD__ . ": setting cache mode $mode" );
		$this->mCacheMode = $mode;
	}

	/** @inheritDoc */
	public function getCacheMode() {
		return $this->mCacheMode;
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
		$printer = $this->mModuleMgr->getModule( $format, 'format', /* $ignoreCache */ true );
		if ( $printer === null ) {
			$this->dieWithError(
				[ 'apierror-unknownformat', wfEscapeWikiText( $format ) ], 'unknown_format'
			);
		}

		// @phan-suppress-next-line PhanTypeMismatchReturnSuperType
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

			$this->statsFactory->getTiming( 'api_executeTiming_seconds' )
				->setLabel( 'module', $this->mModule->getModuleName() )
				->copyToStatsdAt( 'api.' . $this->mModule->getModuleName() . '.executeTiming' )
				->observe( 1000 * $runTime );

			$this->recordUnifiedMetrics( $runTime );

		} catch ( Throwable $e ) {
			// If executeAction threw before the time was set, reset it
			$runTime ??= microtime( true ) - $t;
			$this->handleException( $e, $runTime );
			$this->logRequest( microtime( true ) - $t, $e );
			$isError = true;
		}

		// Disable the client cache on the output so that BlockManager::trackBlockWithCookie is executed
		// as part of MediaWiki::preOutputCommit().
		if (
			$this->mCacheMode === 'private'
			|| (
				$this->mCacheMode === 'anon-public-user-private'
				&& $this->getRequest()->getSession()->isPersistent()
			)
		) {
			$this->getContext()->getOutput()->disableClientCache();
			$this->getContext()->getOutput()->considerCacheSettingsFinal();
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
	 * Handle a throwable as an API response
	 *
	 * @since 1.23
	 * @param Throwable $e
	 * @param float $latency Optional value for process runtime, in microseconds, for metrics
	 */
	protected function handleException( Throwable $e, $latency = 0 ) {
		$statsModuleName = $this->mModule ? $this->mModule->getModuleName() : 'main';

		// Collect stats on errors (T396613).
		// NOTE: We only count fatal errors, a mere call to addError() or
		// addWarning() does not count towards these states. That could
		// be added in the future, but should use a different stats key.
		$stats = $this->statsFactory->getCounter( 'api_errors' )
			->setLabel( 'module', $statsModuleName );

		// T65145: Rollback any open database transactions
		if ( !$e instanceof ApiUsageException ) {
			// ApiUsageExceptions are intentional, so don't rollback if that's the case
			MWExceptionHandler::rollbackPrimaryChangesAndLog(
				$e,
				MWExceptionHandler::CAUGHT_BY_ENTRYPOINT
			);
			$stats->setLabel( 'exception_cause', 'server-error' );
		} else {
			$stats->setLabel( 'exception_cause', 'client-error' );
		}

		// Allow extra cleanup and logging
		$this->getHookRunner()->onApiMain__onException( $this, $e );

		// Handle any kind of exception by outputting properly formatted error message.
		// If this fails, an unhandled exception should be thrown so that global error
		// handler will process and log it.

		$errCodes = $this->substituteResultWithError( $e );
		sort( $errCodes );

		// Error results should not be cached
		$this->setCacheMode( 'private' );

		$response = $this->getRequest()->response();
		$headerStr = 'MediaWiki-API-Error: ' . implode( ', ', $errCodes );
		$response->header( $headerStr );

		// Reset and print just the error message
		ob_clean();

		// Printer may not be initialized if the extractRequestParams() fails for the main module
		$this->createErrorPrinter();

		$stats->setLabel( 'error_code', implode( '_', $errCodes ) );
		$stats->increment();

		// Unified metrics
		$this->recordUnifiedMetrics(
			$latency,
			[
				'status' => implode( '_', $errCodes ), // Failure codes
			]
		);

		// Get desired HTTP code from an ApiUsageException. Don't use codes from other
		// exception types, as they are unlikely to be intended as an HTTP code.
		$httpCode = $e instanceof ApiUsageException ? $e->getCode() : 0;

		$failed = false;
		try {
			$this->printResult( $httpCode );
		} catch ( ApiUsageException $ex ) {
			// The error printer itself is failing. Try suppressing its request
			// parameters and redo.
			$failed = true;
			$this->addWarning( 'apiwarn-errorprinterfailed' );
			foreach ( $ex->getStatusValue()->getMessages() as $error ) {
				try {
					$this->mPrinter->addWarning( $error );
				} catch ( Throwable ) {
					// WTF?
					$this->addWarning( $error );
				}
			}
		}
		if ( $failed ) {
			$this->mPrinter = null;
			$this->createErrorPrinter();
			// @phan-suppress-next-line PhanNonClassMethodCall False positive
			$this->mPrinter->forceDefaultParams();
			if ( $httpCode ) {
				$response->statusHeader( 200 ); // Reset in case the fallback doesn't want a non-200
			}
			$this->printResult( $httpCode );
		}
	}

	/**
	 * Handle a throwable from the ApiBeforeMain hook.
	 *
	 * This tries to print the throwable as an API response, to be more
	 * friendly to clients. If it fails, it will rethrow the throwable.
	 *
	 * @since 1.23
	 * @param Throwable $e
	 * @throws Throwable
	 */
	public static function handleApiBeforeMainException( Throwable $e ) {
		ob_start();

		try {
			$main = new self( RequestContext::getMain(), false );
			$main->handleException( $e );
			$main->logRequest( 0, $e );
		} catch ( Throwable ) {
			// Nope, even that didn't work. Punt.
			throw $e;
		}

		// Reset cache headers
		$main->sendCacheHeaders( true );

		ob_end_flush();
	}

	/**
	 * Check the &origin= and/or &crossorigin= query parameters and respond appropriately.
	 *
	 * If no origin or crossorigin parameter is present, nothing happens.
	 * If both are present, a 403 status code is set and false is returned.
	 *
	 * If an origin parameter is present but doesn't match the Origin header, a 403 status code
	 * is set and false is returned.
	 * If the parameter and the header do match, the header is checked against $wgCrossSiteAJAXdomains
	 * and $wgCrossSiteAJAXdomainExceptions, and if the origin qualifies, the appropriate CORS
	 * headers are set.
	 * https://www.w3.org/TR/cors/#resource-requests
	 * https://www.w3.org/TR/cors/#resource-preflight-requests
	 *
	 * If the crossorigin parameter is set, but the current session provider is not safe against CSRF,
	 * a 403 status code is set and false is returned.
	 * If it is set and the session is safe, then the appropriate CORS headers are set.
	 *
	 * @return bool False if the caller should abort (403 case), true otherwise (all other cases)
	 */
	protected function handleCORS() {
		$originParam = $this->getParameter( 'origin' ); // defaults to null
		$crossOriginParam = $this->getParameter( 'crossorigin' ); // defaults to false
		if ( $originParam === null && !$crossOriginParam ) {
			// No origin/crossorigin parameter, nothing to do
			return true;
		}

		$request = $this->getRequest();
		$response = $request->response();
		$requestedMethod = $request->getHeader( 'Access-Control-Request-Method' );
		$preflight = $request->getMethod() === 'OPTIONS' && $requestedMethod !== false;

		$allowTiming = false;
		$varyOrigin = true;

		if ( $originParam !== null && $crossOriginParam ) {
			$response->statusHeader( 403 );
			$response->header( 'Cache-control: no-cache' );
			echo "'origin' and 'crossorigin' parameters cannot be used together\n";

			return false;
		}
		if ( $crossOriginParam && !$request->getSession()->getProvider()->safeAgainstCsrf() && !$preflight ) {
			$response->statusHeader( 403 );
			$response->header( 'Cache-control: no-cache' );
			$language = MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'en' );
			$described = $request->getSession()->getProvider()->describe( $language );
			echo "'crossorigin' cannot be used with $described\n";

			return false;
		}

		if ( $originParam === '*' || $crossOriginParam ) {
			// Request for CORS without browser-supplied credentials (e.g. cookies):
			// may be anonymous (origin=*) or authenticated with request-supplied
			// credentials (crossorigin=1 + Authorization header).
			// Technically we should check for the presence of an Origin header
			// and not process it as CORS if it's not set, but that would
			// require us to vary on Origin for all 'origin=*' requests which
			// we don't want to do.
			$matchedOrigin = true;
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
			$origin = Origin::parseHeaderList( $origins );
			$matchedOrigin = $origin->match(
				$config->get( MainConfigNames::CrossSiteAJAXdomains ),
				$config->get( MainConfigNames::CrossSiteAJAXdomainExceptions )
			);

			$allowOrigin = $originHeader;
			$allowCredentials = 'true';
			$allowTiming = $originHeader;
		}

		if ( $matchedOrigin ) {
			if ( $preflight ) {
				// We allow the actual request to send the following headers
				$requestedHeaders = $request->getHeader( 'Access-Control-Request-Headers' );
				$allowedHeaders = $this->getConfig()->get( MainConfigNames::AllowedCorsHeaders );
				if ( $requestedHeaders !== false ) {
					if ( !self::matchRequestedHeaders( $requestedHeaders, $allowedHeaders ) ) {
						$response->header( 'MediaWiki-CORS-Rejection: Unsupported header requested in preflight' );
						return true;
					}
					$response->header( 'Access-Control-Allow-Headers: ' . $requestedHeaders );
				}

				// We only allow the actual request to be GET, POST, or HEAD
				$response->header( 'Access-Control-Allow-Methods: POST, GET, HEAD' );
			}

			$response->header( "Access-Control-Allow-Origin: $allowOrigin" );
			$response->header( "Access-Control-Allow-Credentials: $allowCredentials" );
			// https://www.w3.org/TR/resource-timing/#timing-allow-origin
			if ( $allowTiming !== false ) {
				$response->header( "Timing-Allow-Origin: $allowTiming" );
			}

			if ( !$preflight ) {
				$response->header(
					'Access-Control-Expose-Headers: MediaWiki-API-Error, Retry-After, X-Database-Lag, '
					. 'MediaWiki-Login-Suppressed'
				);
			}
		} else {
			$response->header( 'MediaWiki-CORS-Rejection: Origin mismatch' );
		}

		if ( $varyOrigin ) {
			$this->getOutput()->addVaryHeader( 'Origin' );
		}

		return true;
	}

	/**
	 * Attempt to validate the value of Access-Control-Request-Headers against a list
	 * of headers that we allow the follow up request to send.
	 *
	 * @param string $requestedHeaders Comma separated list of HTTP headers
	 * @param string[] $allowedHeaders List of allowed HTTP headers
	 * @return bool True if all requested headers are in the list of allowed headers
	 */
	protected static function matchRequestedHeaders( $requestedHeaders, $allowedHeaders ) {
		if ( trim( $requestedHeaders ) === '' ) {
			return true;
		}
		$requestedHeaders = explode( ',', $requestedHeaders );
		$allowedHeaders = array_change_key_case(
			array_fill_keys( $allowedHeaders, true ), CASE_LOWER );
		foreach ( $requestedHeaders as $rHeader ) {
			$rHeader = strtolower( trim( $rHeader ) );
			if ( !isset( $allowedHeaders[$rHeader] ) ) {
				LoggerFactory::getInstance( 'api-warning' )->warning(
					'CORS preflight failed on requested header: {header}', [
						'header' => $rHeader
					]
				);
				return false;
			}
		}
		return true;
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

		if ( $config->get( MainConfigNames::VaryOnXFP ) ) {
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
		} elseif ( ( !$isError && $this->mModule && !$this->mModule->isWriteMode() ) ||
			$this->mCacheMode !== 'private'
		) {
			$maxage = $this->getParameter( 'maxage' );
		}
		$privateCache = 'private, must-revalidate, max-age=' . $maxage;

		if ( $this->mCacheMode == 'private' ) {
			$response->header( "Cache-Control: $privateCache" );
			return;
		}

		if ( $this->mCacheMode == 'anon-public-user-private' ) {
			$out->addVaryHeader( 'Cookie' );
			$response->header( $out->getVaryHeader() );
			if ( $this->getRequest()->getSession()->isPersistent() ) {
				// Logged in or otherwise has session (e.g. anonymous users who have edited)
				// Mark request private
				$response->header( "Cache-Control: $privateCache" );

				return;
			} // else anonymous, send public headers below
		}

		// Send public headers
		$response->header( $out->getVaryHeader() );

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
		if ( !$this->mPrinter ) {
			$value = $this->getRequest()->getVal( 'format', self::API_DEFAULT_FORMAT );
			if ( !$this->mModuleMgr->isDefined( $value, 'format' ) ) {
				$value = self::API_DEFAULT_FORMAT;
			}
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable getVal does not return null here
			$this->mPrinter = $this->createPrinterByName( $value );
		}

		// Printer may not be able to handle errors. This is particularly
		// likely if the module returns something for getCustomPrinter().
		if ( !$this->mPrinter->canPrintErrors() ) {
			$this->mPrinter = $this->createPrinterByName( self::API_DEFAULT_FORMAT );
		}
	}

	/**
	 * Create an error message for the given throwable.
	 *
	 * If an ApiUsageException, errors/warnings will be extracted from the
	 * embedded StatusValue.
	 *
	 * Any other throwable will be returned with a generic code and wrapper
	 * text around the throwable's (presumably English) message as a single
	 * error (no warnings).
	 *
	 * @param Throwable $e
	 * @param string $type 'error' or 'warning'
	 * @return ApiMessage[]
	 * @since 1.27
	 */
	protected function errorMessagesFromException( Throwable $e, $type = 'error' ) {
		$messages = [];
		if ( $e instanceof ApiUsageException ) {
			foreach ( $e->getStatusValue()->getMessages( $type ) as $msg ) {
				$messages[] = ApiMessage::create( $msg );
			}
		} elseif ( $type !== 'error' ) {
			// None of the rest have any messages for non-error types
		} else {
			// TODO: Avoid embedding arbitrary class names in the error code.
			$class = preg_replace( '#^Wikimedia\\\\Rdbms\\\\#', '', get_class( $e ) );
			$code = 'internal_api_error_' . $class;
			$data = [ 'errorclass' => get_class( $e ) ];
			if ( MWExceptionRenderer::shouldShowExceptionDetails() ) {
				if ( $e instanceof ILocalizedException ) {
					$msg = $e->getMessageObject();
				} elseif ( $e instanceof MessageSpecifier ) {
					$msg = Message::newFromSpecifier( $e );
				} else {
					$msg = wfEscapeWikiText( $e->getMessage() );
				}
				$params = [ 'apierror-exceptioncaught', WebRequest::getRequestId(), $msg ];
			} else {
				$params = [ 'apierror-exceptioncaughttype', WebRequest::getRequestId(), get_class( $e ) ];
			}

			$messages[] = ApiMessage::create( $params, $code, $data );
		}
		return $messages;
	}

	/**
	 * Replace the result data with the information about a throwable.
	 * @param Throwable $e
	 * @return string[] Error codes
	 */
	protected function substituteResultWithError( Throwable $e ) {
		$result = $this->getResult();
		$formatter = $this->getErrorFormatter();
		$config = $this->getConfig();
		$errorCodes = [];

		// Remember existing warnings and errors across the reset
		$errors = $result->getResultData( [ 'errors' ] );
		$warnings = $result->getResultData( [ 'warnings' ] );
		$result->reset();
		if ( $warnings !== null ) {
			$result->addValue( null, 'warnings', $warnings, ApiResult::NO_SIZE_CHECK );
		}
		if ( $errors !== null ) {
			$result->addValue( null, 'errors', $errors, ApiResult::NO_SIZE_CHECK );

			// Collect the copied error codes for the return value
			foreach ( $errors as $error ) {
				if ( isset( $error['code'] ) ) {
					$errorCodes[$error['code']] = true;
				}
			}
		}

		// Add errors from the exception
		$modulePath = $e instanceof ApiUsageException ? $e->getModulePath() : null;
		foreach ( $this->errorMessagesFromException( $e, 'error' ) as $msg ) {
			if ( ApiErrorFormatter::isValidApiCode( $msg->getApiCode() ) ) {
				$errorCodes[$msg->getApiCode()] = true;
			} else {
				LoggerFactory::getInstance( 'api-warning' )->error( 'Invalid API error code "{code}"', [
					'code' => $msg->getApiCode(),
					'exception' => $e,
				] );
				$errorCodes['<invalid-code>'] = true;
			}
			$formatter->addError( $modulePath, $msg );
		}
		foreach ( $this->errorMessagesFromException( $e, 'warning' ) as $msg ) {
			$formatter->addWarning( $modulePath, $msg );
		}

		// Add additional data. Path depends on whether we're in BC mode or not.
		// Data depends on the type of exception.
		if ( $formatter instanceof ApiErrorFormatter_BackCompat ) {
			$path = [ 'error' ];
		} else {
			$path = null;
		}
		if ( $e instanceof ApiUsageException ) {
			$link = (string)MediaWikiServices::getInstance()->getUrlUtils()->expand( wfScript( 'api' ) );
			$result->addContentValue(
				$path,
				'docref',
				trim(
					$this->msg( 'api-usage-docref', $link )->inLanguage( $formatter->getLanguage() )->text()
					. ' '
					. $this->msg( 'api-usage-mailinglist-ref' )->inLanguage( $formatter->getLanguage() )->text()
				)
			);
		} elseif ( $config->get( MainConfigNames::ShowExceptionDetails ) ) {
			$result->addContentValue(
				$path,
				'trace',
				$this->msg( 'api-exception-trace',
					get_class( $e ),
					$e->getFile(),
					$e->getLine(),
					MWExceptionHandler::getRedactedTraceAsString( $e )
				)->inLanguage( $formatter->getLanguage() )->text()
			);
		}

		// Add the id and such
		$this->addRequestedFields( [ 'servedby' ] );

		return array_keys( $errorCodes );
	}

	/**
	 * Add requested fields to the result
	 * @param string[] $force Which fields to force even if not requested. Accepted values are:
	 *  - servedby
	 */
	protected function addRequestedFields( $force = [] ) {
		$result = $this->getResult();

		$requestid = $this->getParameter( 'requestid' );
		if ( $requestid !== null ) {
			$result->addValue( null, 'requestid', $requestid, ApiResult::NO_SIZE_CHECK );
		}

		if ( $this->getConfig()->get( MainConfigNames::ShowHostnames ) && (
			in_array( 'servedby', $force, true ) || $this->getParameter( 'servedby' )
		) ) {
			$result->addValue( null, 'servedby', wfHostname(), ApiResult::NO_SIZE_CHECK );
		}

		if ( $this->getParameter( 'curtimestamp' ) ) {
			$result->addValue( null, 'curtimestamp', wfTimestamp( TS_ISO_8601 ), ApiResult::NO_SIZE_CHECK );
		}

		if ( $this->getParameter( 'responselanginfo' ) ) {
			$result->addValue(
				null,
				'uselang',
				$this->getLanguage()->getCode(),
				ApiResult::NO_SIZE_CHECK
			);
			$result->addValue(
				null,
				'errorlang',
				$this->getErrorFormatter()->getLanguage()->getCode(),
				ApiResult::NO_SIZE_CHECK
			);
		}
	}

	/**
	 * Set up for the execution.
	 * @return array
	 */
	protected function setupExecuteAction() {
		$this->addRequestedFields();

		$params = $this->extractRequestParams();
		$this->mAction = $params['action'];

		return $params;
	}

	/**
	 * Set up the module for response
	 * @return ApiBase The module that will handle this action
	 * @throws ApiUsageException
	 */
	protected function setupModule() {
		// Instantiate the module requested by the user
		$module = $this->mModuleMgr->getModule( $this->mAction, 'action' );
		if ( $module === null ) {
			// Probably can't happen
			// @codeCoverageIgnoreStart
			$this->dieWithError(
				[ 'apierror-unknownaction', wfEscapeWikiText( $this->mAction ) ],
				'unknown_action'
			);
			// @codeCoverageIgnoreEnd
		}
		$moduleParams = $module->extractRequestParams();

		// Check token, if necessary
		if ( $module->needsToken() === true ) {
			throw new LogicException(
				"Module '{$module->getModuleName()}' must be updated for the new token handling. " .
				'See documentation for ApiBase::needsToken for details.'
			);
		}
		if ( $module->needsToken() ) {
			if ( !$module->mustBePosted() ) {
				throw new LogicException(
					"Module '{$module->getModuleName()}' must require POST to use tokens."
				);
			}

			if ( !isset( $moduleParams['token'] ) ) {
				// Probably can't happen
				// @codeCoverageIgnoreStart
				$module->dieWithError( [ 'apierror-missingparam', 'token' ] );
				// @codeCoverageIgnoreEnd
			}

			$module->requirePostedParameters( [ 'token' ] );

			if ( !$module->validateToken( $moduleParams['token'], $moduleParams ) ) {
				$module->dieWithError( 'apierror-badtoken' );
			}
		}

		// @phan-suppress-next-line PhanTypeMismatchReturnNullable T240141
		return $module;
	}

	/**
	 * @return array
	 */
	private function getMaxLag() {
		$services = MediaWikiServices::getInstance();
		$dbLag = $services->getDBLoadBalancer()->getMaxLag();
		$lagInfo = [
			'host' => $dbLag[0],
			'lag' => $dbLag[1],
			'type' => 'db'
		];

		$jobQueueLagFactor =
			$this->getConfig()->get( MainConfigNames::JobQueueIncludeInMaxLagFactor );
		if ( $jobQueueLagFactor ) {
			// Turn total number of jobs into seconds by using the configured value
			$totalJobs = array_sum( $services->getJobQueueGroup()->getQueueSizes() );
			$jobQueueLag = $totalJobs / (float)$jobQueueLagFactor;
			if ( $jobQueueLag > $lagInfo['lag'] ) {
				$lagInfo = [
					'host' => wfHostname(), // XXX: Is there a better value that could be used?
					'lag' => $jobQueueLag,
					'type' => 'jobqueue',
					'jobs' => $totalJobs,
				];
			}
		}

		$this->getHookRunner()->onApiMaxLagInfo( $lagInfo );

		return $lagInfo;
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
			$lagInfo = $this->getMaxLag();
			if ( $lagInfo['lag'] > $maxLag ) {
				$response = $this->getRequest()->response();

				$response->header( 'Retry-After: ' . max( (int)$maxLag, 5 ) );
				$response->header( 'X-Database-Lag: ' . (int)$lagInfo['lag'] );

				if ( $this->getConfig()->get( MainConfigNames::ShowHostnames ) ) {
					$this->dieWithError(
						[ 'apierror-maxlag', $lagInfo['lag'], $lagInfo['host'] ],
						'maxlag',
						$lagInfo
					);
				}

				$this->dieWithError( [ 'apierror-maxlag-generic', $lagInfo['lag'] ], 'maxlag', $lagInfo );
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
			// @phan-suppress-next-line PhanImpossibleTypeComparison
			if ( $ifNoneMatch === [ '*' ] ) {
				// API responses always "exist"
				$etag = '*';
			} else {
				$etag = $module->getConditionalRequestData( 'etag' );
			}
		}
		// @phan-suppress-next-line PhanPossiblyUndeclaredVariable $etag is declared when $ifNoneMatch is true
		if ( $ifNoneMatch && $etag !== null ) {
			$test = str_starts_with( $etag, 'W/' ) ? substr( $etag, 2 ) : $etag;
			$match = array_map( static function ( $s ) {
				return str_starts_with( $s, 'W/' ) ? substr( $s, 2 ) : $s;
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
					$ts = new ConvertibleTimestamp( $value );
					if (
						// RFC 7231 IMF-fixdate
						$ts->getTimestamp( TS_RFC2822 ) === $value ||
						// RFC 850
						$ts->format( 'l, d-M-y H:i:s' ) . ' GMT' === $value ||
						// asctime (with and without space-padded day)
						$ts->format( 'D M j H:i:s Y' ) === $value ||
						$ts->format( 'D M  j H:i:s Y' ) === $value
					) {
						$config = $this->getConfig();
						$lastMod = $module->getConditionalRequestData( 'last-modified' );
						if ( $lastMod !== null ) {
							// Mix in some MediaWiki modification times
							$modifiedTimes = [
								'page' => $lastMod,
								'user' => $this->getUser()->getTouched(),
								'epoch' => $config->get( MainConfigNames::CacheEpoch ),
							];

							if ( $config->get( MainConfigNames::UseCdn ) ) {
								// T46570: the core page itself may not change, but resources might
								$modifiedTimes['sepoch'] = wfTimestamp(
									TS_MW, time() - $config->get( MainConfigNames::CdnMaxAge )
								);
							}
							$this->getHookRunner()->onOutputPageCheckLastModified( $modifiedTimes, $this->getOutput() );
							$lastMod = max( $modifiedTimes );
							$return304 = wfTimestamp( TS_MW, $lastMod ) <= $ts->getTimestamp( TS_MW );
						}
					}
				} catch ( TimestampException ) {
					// Invalid timestamp, ignore it
				}
			}
		}

		if ( $return304 ) {
			$this->getRequest()->response()->statusHeader( 304 );

			// Avoid outputting the compressed representation of a zero-length body
			AtEase::suppressWarnings();
			ini_set( 'zlib.output_compression', 0 );
			AtEase::restoreWarnings();
			wfResetOutputBuffers( false );

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
		if ( $module->isReadMode() && !$this->getPermissionManager()->isEveryoneAllowed( 'read' ) &&
			!$this->getAuthority()->isAllowed( 'read' )
		) {
			$this->dieWithError( 'apierror-readapidenied' );
		}

		if ( $module->isWriteMode() ) {
			if ( !$this->mEnableWrite ) {
				$this->dieWithError( 'apierror-noapiwrite' );
			} elseif ( $this->getRequest()->getHeader( 'Promise-Non-Write-API-Action' ) ) {
				$this->dieWithError( 'apierror-promised-nonwrite-api' );
			}

			$this->checkReadOnly( $module );
		}

		// Allow extensions to stop execution for arbitrary reasons.
		// TODO: change hook to accept Authority
		$message = 'hookaborted';
		if ( !$this->getHookRunner()->onApiCheckCanExecute( $module, $user, $message ) ) {
			$this->dieWithError( $message );
		}
	}

	/**
	 * Check if the DB is read-only for this user
	 * @param ApiBase $module An Api module
	 */
	protected function checkReadOnly( $module ) {
		if ( MediaWikiServices::getInstance()->getReadOnlyMode()->isReadOnly() ) {
			$this->dieReadOnly();
		}

		if ( $module->isWriteMode()
			&& $this->getUser()->isBot()
			&& MediaWikiServices::getInstance()->getDBLoadBalancer()->hasReplicaServers()
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
		$lagLimit = $this->getConfig()->get( MainConfigNames::APIMaxLagThreshold );
		$laggedServers = [];
		$loadBalancer = MediaWikiServices::getInstance()->getDBLoadBalancer();
		foreach ( $loadBalancer->getLagTimes() as $serverIndex => $lag ) {
			if ( $lag > $lagLimit ) {
				++$numLagged;
				$laggedServers[] = $loadBalancer->getServerName( $serverIndex ) . " ({$lag}s)";
			}
		}

		// If a majority of replica DBs are too lagged then disallow writes
		$replicaCount = $loadBalancer->getServerCount() - 1;
		if ( $numLagged >= ceil( $replicaCount / 2 ) ) {
			$laggedServers = implode( ', ', $laggedServers );
			wfDebugLog(
				'api-readonly', // Deprecate this channel in favor of api-warning?
				"Api request failed as read only because the following DBs are lagged: $laggedServers"
			);
			LoggerFactory::getInstance( 'api-warning' )->warning(
				"Api request failed as read only because the following DBs are lagged: {laggeddbs}", [
					'laggeddbs' => $laggedServers,
				]
			);

			$this->dieWithError(
				'readonly_lag',
				'readonly',
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
				case 'anon':
					if ( $user->isRegistered() ) {
						$this->dieWithError( 'apierror-assertanonfailed' );
					}
					break;
				case 'user':
					if ( !$user->isRegistered() ) {
						$this->dieWithError( 'apierror-assertuserfailed' );
					}
					break;
				case 'bot':
					if ( !$this->getAuthority()->isAllowed( 'bot' ) ) {
						$this->dieWithError( 'apierror-assertbotfailed' );
					}
					break;
			}
		}
		if ( isset( $params['assertuser'] ) ) {
			// TODO inject stuff, see T265644
			$assertUser = MediaWikiServices::getInstance()->getUserFactory()
				->newFromName( $params['assertuser'], UserRigorOptions::RIGOR_NONE );
			if ( !$assertUser || !$this->getUser()->equals( $assertUser ) ) {
				$this->dieWithError(
					[ 'apierror-assertnameduserfailed', wfEscapeWikiText( $params['assertuser'] ) ]
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
		$validMethods = [ 'GET', 'HEAD', 'POST', 'OPTIONS' ];
		$request = $this->getRequest();

		if ( !in_array( $request->getMethod(), $validMethods ) ) {
			$this->dieWithError( 'apierror-invalidmethod', null, null, 405 );
		}

		if ( !$request->wasPosted() && $module->mustBePosted() ) {
			// Module requires POST. GET request might still be allowed
			// if $wgDebugApi is true, otherwise fail.
			$this->dieWithErrorOrDebug( [ 'apierror-mustbeposted', $this->mAction ] );
		}

		if ( $request->wasPosted() ) {
			if ( !$request->getHeader( 'Content-Type' ) ) {
				$this->addDeprecation(
					'apiwarn-deprecation-post-without-content-type', 'post-without-content-type'
				);
			}
			$contentLength = $request->getHeader( 'Content-Length' );
			$maxPostSize = wfShorthandToInteger( ini_get( 'post_max_size' ), 0 );
			if ( $maxPostSize && $contentLength > $maxPostSize ) {
				$this->dieWithError(
					[ 'apierror-http-contenttoolarge', Message::sizeParam( $maxPostSize ) ],
					null, null, 413
				);
			}
		}

		// See if custom printer is used
		$this->mPrinter = $module->getCustomPrinter() ??
			// Create an appropriate printer if not set
			$this->createPrinterByName( $params['format'] );

		if ( $request->getProtocol() === 'http' &&
			(
				$this->getConfig()->get( MainConfigNames::ForceHTTPS ) ||
				$request->getSession()->shouldForceHTTPS() ||
				$this->getUser()->requiresHTTPS()
			)
		) {
			$this->addDeprecation( 'apiwarn-deprecation-httpsexpected', 'https-expected' );
		}
	}

	/**
	 * Execute the actual module, without any error handling
	 */
	protected function executeAction() {
		$params = $this->setupExecuteAction();

		// Check asserts early so e.g. errors in parsing a module's parameters due to being
		// logged out don't override the client's intended "am I logged in?" check.
		$this->checkAsserts( $params );

		$module = $this->setupModule();
		$this->mModule = $module;

		if ( !$this->mInternalMode ) {
			ProfilingContext::singleton()->init( MW_ENTRY_POINT, $module->getModuleName() );
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

		$scope = LoggerFactory::getContext()->addScoped( [
			'context.api_module_name' => $module->getModuleName(),
			'context.api_client_useragent' => $this->getUserAgent(),
		] );
		$module->execute();
		ScopedCallback::consume( $scope );
		$this->getHookRunner()->onAPIAfterExecute( $module );

		$this->reportUnusedParams();

		if ( !$this->mInternalMode ) {
			MWDebug::appendDebugInfoToApiResult( $this->getContext(), $this->getResult() );

			$this->printResult();
		}
	}

	/**
	 * Set database connection, query, and write expectations given this module request
	 */
	protected function setRequestExpectations( ApiBase $module ) {
		$request = $this->getRequest();

		$trxLimits = $this->getConfig()->get( MainConfigNames::TrxProfilerLimits );
		$trxProfiler = Profiler::instance()->getTransactionProfiler();
		$trxProfiler->setLogger( LoggerFactory::getInstance( 'rdbms' ) );
		$trxProfiler->setStatsFactory( MediaWikiServices::getInstance()->getStatsFactory() );
		$trxProfiler->setRequestMethod( $request->getMethod() );
		if ( $request->hasSafeMethod() ) {
			$trxProfiler->setExpectations( $trxLimits['GET'], __METHOD__ );
		} elseif ( $request->wasPosted() && !$module->isWriteMode() ) {
			$trxProfiler->setExpectations( $trxLimits['POST-nonwrite'], __METHOD__ );
		} else {
			$trxProfiler->setExpectations( $trxLimits['POST'], __METHOD__ );
		}
	}

	/**
	 * Log the preceding request
	 * @param float $time Time in seconds
	 * @param Throwable|null $e Throwable caught while processing the request
	 */
	protected function logRequest( $time, ?Throwable $e = null ) {
		$request = $this->getRequest();

		$user = $this->getUser();
		$performer = [
			'user_text' => $user->getName(),
		];
		if ( $user->isRegistered() ) {
			$performer['user_id'] = $user->getId();
		}
		$logCtx = [
			// https://gerrit.wikimedia.org/g/mediawiki/event-schemas/+/master/jsonschema/mediawiki/api/request
			'$schema' => '/mediawiki/api/request/1.0.0',
			'meta' => [
				'request_id' => WebRequest::getRequestId(),
				'id' => MediaWikiServices::getInstance()
					->getGlobalIdGenerator()->newUUIDv4(),
				'domain' => $this->getConfig()->get( MainConfigNames::ServerName ),
				// If using the EventBus extension (as intended) with this log channel,
				// this stream name will map to a Kafka topic.
				'stream' => 'mediawiki.api-request'
			],
			'http' => [
				'method' => $request->getMethod(),
				'client_ip' => $request->getIP()
			],
			'performer' => $performer,
			'database' => WikiMap::getCurrentWikiDbDomain()->getId(),
			'backend_time_ms' => (int)round( $time * 1000 ),
		];

		// If set, these headers will be logged in http.request_headers.
		$httpRequestHeadersToLog = [ 'accept-language', 'referer', 'user-agent' ];
		foreach ( $httpRequestHeadersToLog as $header ) {
			if ( $request->getHeader( $header ) ) {
				// Set the header in http.request_headers
				$logCtx['http']['request_headers'][$header] = $request->getHeader( $header );
			}
		}

		if ( $e ) {
			$logCtx['api_error_codes'] = [];
			foreach ( $this->errorMessagesFromException( $e ) as $msg ) {
				$logCtx['api_error_codes'][] = $msg->getApiCode();
			}
		}

		// Construct space separated message for 'api' log channel
		$msg = "API {$request->getMethod()} " .
			wfUrlencode( str_replace( ' ', '_', $this->getUser()->getName() ) ) .
			" {$logCtx['http']['client_ip']} " .
			"T={$logCtx['backend_time_ms']}ms";

		$sensitive = array_fill_keys( $this->getSensitiveParams(), true );
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

		// Log an unstructured message to the api channel.
		wfDebugLog( 'api', $msg, 'private' );

		// The api-request channel a structured data log channel.
		wfDebugLog( 'api-request', '', 'private', $logCtx );
	}

	/**
	 * Encode a value in a format suitable for a space-separated log line.
	 * @param string $s
	 * @return string
	 */
	protected function encodeRequestLogValue( $s ) {
		static $table = [];
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
	 * @since 1.29
	 * @return array
	 */
	protected function getSensitiveParams() {
		return array_keys( $this->mParamsSensitive );
	}

	/**
	 * Mark parameters as sensitive
	 *
	 * This is called automatically for you when declaring a parameter
	 * with ApiBase::PARAM_SENSITIVE.
	 *
	 * @since 1.29
	 * @param string|string[] $params
	 */
	public function markParamsSensitive( $params ) {
		$this->mParamsSensitive += array_fill_keys( (array)$params, true );
	}

	/**
	 * Get a request value, and register the fact that it was used, for logging.
	 * @param string $name
	 * @param string|null $default
	 * @return string|null
	 */
	public function getVal( $name, $default = null ) {
		$this->mParamsUsed[$name] = true;

		$ret = $this->getRequest()->getVal( $name );
		if ( $ret === null ) {
			if ( $this->getRequest()->getArray( $name ) !== null ) {
				// See T12262 for why we don't just implode( '|', ... ) the
				// array.
				$this->addWarning( [ 'apiwarn-unsupportedarray', $name ] );
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
		$this->mParamsUsed[$name] = true;
		return $this->getRequest()->getCheck( $name );
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
			$printerParams = $this->mPrinter->encodeParamName(
				array_keys( $this->mPrinter->getFinalParams() ?: [] )
			);
			$unusedParams = array_diff( $allParams, $paramsUsed, $printerParams );
		} else {
			$unusedParams = array_diff( $allParams, $paramsUsed );
		}

		if ( count( $unusedParams ) ) {
			$this->addWarning( [
				'apierror-unrecognizedparams',
				Message::listParam( array_map( 'wfEscapeWikiText', $unusedParams ), ListType::COMMA ),
				count( $unusedParams )
			] );
		}
	}

	/**
	 * Print results using the current printer
	 *
	 * @param int $httpCode HTTP status code, or 0 to not change
	 */
	protected function printResult( $httpCode = 0 ) {
		if ( $this->getConfig()->get( MainConfigNames::DebugAPI ) !== false ) {
			$this->addWarning( 'apiwarn-wgdebugapi' );
		}

		$printer = $this->mPrinter;
		$printer->initPrinter( false );
		if ( $httpCode ) {
			$printer->setHttpStatus( $httpCode );
		}
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
				ParamValidator::PARAM_DEFAULT => 'help',
				ParamValidator::PARAM_TYPE => 'submodule',
			],
			'format' => [
				ParamValidator::PARAM_DEFAULT => self::API_DEFAULT_FORMAT,
				ParamValidator::PARAM_TYPE => 'submodule',
			],
			'maxlag' => [
				ParamValidator::PARAM_TYPE => 'integer'
			],
			'smaxage' => [
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_DEFAULT => 0,
				IntegerDef::PARAM_MIN => 0,
			],
			'maxage' => [
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_DEFAULT => 0,
				IntegerDef::PARAM_MIN => 0,
			],
			'assert' => [
				ParamValidator::PARAM_TYPE => [ 'anon', 'user', 'bot' ]
			],
			'assertuser' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'temp' ],
			],
			'requestid' => null,
			'servedby' => false,
			'curtimestamp' => false,
			'responselanginfo' => false,
			'origin' => null,
			'crossorigin' => false,
			'uselang' => [
				ParamValidator::PARAM_DEFAULT => self::API_DEFAULT_USELANG,
			],
			'variant' => null,
			'errorformat' => [
				ParamValidator::PARAM_TYPE => [ 'plaintext', 'wikitext', 'html', 'raw', 'none', 'bc' ],
				ParamValidator::PARAM_DEFAULT => 'bc',
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'errorlang' => [
				ParamValidator::PARAM_DEFAULT => 'uselang',
			],
			'errorsuselocal' => [
				ParamValidator::PARAM_DEFAULT => false,
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=help'
				=> 'apihelp-help-example-main',
			'action=help&recursivesubmodules=1'
				=> 'apihelp-help-example-recursive',
		];
	}

	/**
	 * @inheritDoc
	 * @phan-param array{nolead?:bool,headerlevel?:int,tocnumber?:int[]} $options
	 */
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
		$help['templatedparams'] = '';
		$help['credits'] = '';

		// Fill 'permissions'
		$help['permissions'] .= Html::openElement( 'div',
			[ 'class' => [ 'apihelp-block', 'apihelp-permissions' ] ] );
		$m = $this->msg( 'api-help-permissions' );
		if ( !$m->isDisabled() ) {
			$help['permissions'] .= Html::rawElement( 'div', [ 'class' => 'apihelp-block-head' ],
				$m->numParams( count( self::RIGHTS_MAP ) )->parse()
			);
		}
		$help['permissions'] .= Html::openElement( 'dl' );
		// TODO inject stuff, see T265644
		$groupPermissionsLookup = MediaWikiServices::getInstance()->getGroupPermissionsLookup();
		foreach ( self::RIGHTS_MAP as $right => $rightMsg ) {
			$help['permissions'] .= Html::element( 'dt', [], $right );

			$rightMsg = $this->msg( $rightMsg['msg'], $rightMsg['params'] )->parse();
			$help['permissions'] .= Html::rawElement( 'dd', [], $rightMsg );

			$groups = array_map( static function ( $group ) {
				return $group == '*' ? 'all' : $group;
			}, $groupPermissionsLookup->getGroupsWithPermission( $right ) );

			$help['permissions'] .= Html::rawElement( 'dd', [],
				$this->msg( 'api-help-permissions-granted-to' )
					->numParams( count( $groups ) )
					->params( Message::listParam( $groups ) )
					->parse()
			);
		}
		$help['permissions'] .= Html::closeElement( 'dl' );
		$help['permissions'] .= Html::closeElement( 'div' );

		// Fill 'datatypes', 'templatedparams', and 'credits', if applicable
		if ( empty( $options['nolead'] ) ) {
			// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset Must set when nolead is not set
			$level = $options['headerlevel'];
			// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset Must set when nolead is not set
			$tocnumber = &$options['tocnumber'];

			$header = $this->msg( 'api-help-datatypes-header' )->parse();
			$headline = Html::rawElement(
				'h' . min( 6, $level ),
				[ 'class' => 'apihelp-header', 'id' => 'main/datatypes' ],
				$header
			);
			$help['datatypes'] .= $headline;
			$help['datatypes'] .= $this->msg( 'api-help-datatypes-top' )->parseAsBlock();
			$help['datatypes'] .= '<dl>';
			foreach ( $this->getParamValidator()->knownTypes() as $type ) {
				$m = $this->msg( "api-help-datatype-$type" );
				if ( !$m->isDisabled() ) {
					$help['datatypes'] .= Html::element( 'dt', [ 'id' => "main/datatype/$type" ], $type );
					$help['datatypes'] .= Html::rawElement( 'dd', [], $m->parseAsBlock() );
				}
			}
			$help['datatypes'] .= '</dl>';
			if ( !isset( $tocData['main/datatypes'] ) ) {
				$tocnumber[$level]++;
				$tocData['main/datatypes'] = [
					'toclevel' => count( $tocnumber ),
					'level' => $level,
					'anchor' => 'main/datatypes',
					'line' => $header,
					'number' => implode( '.', $tocnumber ),
					'index' => '',
				];
			}

			$header = $this->msg( 'api-help-templatedparams-header' )->parse();
			$headline = Html::rawElement(
				'h' . min( 6, $level ),
				[ 'class' => 'apihelp-header', 'id' => 'main/templatedparams' ],
				$header
			);
			$help['templatedparams'] .= $headline;
			$help['templatedparams'] .= $this->msg( 'api-help-templatedparams' )->parseAsBlock();
			if ( !isset( $tocData['main/templatedparams'] ) ) {
				$tocnumber[$level]++;
				$tocData['main/templatedparams'] = [
					'toclevel' => count( $tocnumber ),
					'level' => $level,
					'anchor' => 'main/templatedparams',
					'line' => $header,
					'number' => implode( '.', $tocnumber ),
					'index' => '',
				];
			}

			$header = $this->msg( 'api-credits-header' )->parse();
			$headline = Html::rawElement(
				'h' . min( 6, $level ),
				[ 'class' => 'apihelp-header', 'id' => 'main/credits' ],
				$header
			);
			$help['credits'] .= $headline;
			$help['credits'] .= $this->msg( 'api-credits' )->useDatabase( false )->parseAsBlock();
			if ( !isset( $tocData['main/credits'] ) ) {
				$tocnumber[$level]++;
				$tocData['main/credits'] = [
					'toclevel' => count( $tocnumber ),
					'level' => $level,
					'anchor' => 'main/credits',
					'line' => $header,
					'number' => implode( '.', $tocnumber ),
					'index' => '',
				];
			}
		}
	}

	/** @var bool|null */
	private $mCanApiHighLimits = null;

	/**
	 * Check whether the current user is allowed to use high limits
	 * @return bool
	 */
	public function canApiHighLimits() {
		if ( $this->mCanApiHighLimits === null ) {
			$this->mCanApiHighLimits = $this->getAuthority()->isAllowed( 'apihighlimits' );
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
	 * This returns the value of the 'Api-User-Agent' header, if any,
	 * or the standard User-Agent header, otherwise.
	 *
	 * @return string
	 */
	public function getUserAgent() {
		$agent = (string)$this->getRequest()->getHeader( 'Api-user-agent' );
		if ( $agent == '' ) {
			$agent = $this->getRequest()->getHeader( 'User-agent' );
		}

		return $agent;
	}

	/**
	 * Record unified metrics for the API
	 *
	 * @param float $latency Optional value for process runtime, in microseconds, for metrics
	 * @param array $detailLabels Additional or override labels for the metrics
	 */
	protected function recordUnifiedMetrics( $latency, $detailLabels = [] ) {
		// The concept of "module" is different in Action API and REST API
		// in REST API, it represents the "collection" of endpoints
		// in Action API, it represents the "module" of the API (or an endpoint)
		// In order to make the metrics consistent, we want the module to also reflect
		// the "collection" of endpoints. The closest we can get is to use the namespace
		// of the API module, and get the area of the code or extension it belongs to.
		$module = __NAMESPACE__;

		// Get the endpoint representation, which for the moment is the module name.
		// However, there may be cases (in error states) where the module name is not
		// yet set; for those cases, we will use 'main', as is used in the handleException method.
		// The module path is still stored in `path` label, which should be enough to
		// get information about the endpoint even in those cases.
		$endpoint = $this->mModule ? $this->mModule->getModuleName() : 'main';

		// The "path" should give us useful and consistent information about the endpoint.
		// The ->getModulePath() method is calculating the module parents' names, and those
		// aren't always available, and may miss some of the separation of props in the query
		// that may result in the same endpoint being represented differently.
		// So in order to be able to get consistent information about the endpoint, we will use
		// the "path" label to represent the query parameters and their values.
		// This will also allow us to use regular expressions to match whatever module we are
		// interested in for dashboards or alerts more consistently.
		$params = $this->extractRequestParams( [ 'safeMode' => true ] );
		$path = implode(
			'&',
			array_map(
				static function ( $key, $value ): string	 {
					return $key . '=' . $value;
				},
				array_keys( $params ),
				$params
			)
		);

		// Unified metrics
		$metricsLabels = array_merge( [
			// This should represent the "collection" of endpoints
			'api_module' => $module,
			// This is the endpoint that is being executed
			'api_endpoint' => $endpoint,
			'path' => $path,
			'method' => $this->getRequest()->getMethod(),
			'status' => "200", // Success
		], $detailLabels );

		// Hit metrics
		$metricHitStats = $this->statsFactory->getCounter( 'action_api_modules_hit_total' )
			->setLabel( 'api_type', 'ACTION_API' );
		foreach ( $metricsLabels as $label => $value ) {
			if ( $value ) {
				$metricHitStats->setLabel( $label, $value );
			}
		}

		// Latency metrics
		$metricLatencyStats = $this->statsFactory->getTiming( 'action_api_modules_latency' )
			->setLabel( 'api_type', 'ACTION_API' );
		foreach ( $metricsLabels as $label => $value ) {
			if ( $value ) {
				$metricLatencyStats->setLabel( $label, $value );
			}
		}
		$metricLatencyStats->observeNanoseconds( $latency );

		$metricHitStats->increment();
	}

}

/**
 * For really cool vim folding this needs to be at the end:
 * vim: foldmarker=@{,@} foldmethod=marker
 */

/** @deprecated class alias since 1.43 */
class_alias( ApiMain::class, 'ApiMain' );
