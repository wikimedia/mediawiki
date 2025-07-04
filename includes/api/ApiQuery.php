<?php
/**
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
 */

namespace MediaWiki\Api;

use DumpStringOutput;
use MediaWiki\Export\WikiExporterFactory;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWiki\Title\TitleFormatter;
use WikiExporter;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ScopedCallback;
use XmlDumpWriter;

/**
 * This is the main query class. It behaves similar to ApiMain: based on the
 * parameters given, it will create a list of titles to work on (an ApiPageSet
 * object), instantiate and execute various property/list/meta modules, and
 * assemble all resulting data into a single ApiResult object.
 *
 * In generator mode, a generator will be executed first to populate a second
 * ApiPageSet object, and that object will be used for all subsequent modules.
 *
 * @ingroup API
 */
class ApiQuery extends ApiBase {

	/**
	 * List of Api Query prop modules
	 */
	private const QUERY_PROP_MODULES = [
		'categories' => [
			'class' => ApiQueryCategories::class,
		],
		'categoryinfo' => [
			'class' => ApiQueryCategoryInfo::class,
		],
		'contributors' => [
			'class' => ApiQueryContributors::class,
			'services' => [
				'RevisionStore',
				'ActorMigration',
				'UserGroupManager',
				'GroupPermissionsLookup',
				'TempUserConfig'
			]
		],
		'deletedrevisions' => [
			'class' => ApiQueryDeletedRevisions::class,
			'services' => [
				'RevisionStore',
				'ContentHandlerFactory',
				'ParserFactory',
				'SlotRoleRegistry',
				'ChangeTagDefStore',
				'ChangeTagsStore',
				'LinkBatchFactory',
				'ContentRenderer',
				'ContentTransformer',
				'CommentFormatter',
				'TempUserCreator',
				'UserFactory',
			]
		],
		'duplicatefiles' => [
			'class' => ApiQueryDuplicateFiles::class,
			'services' => [
				'RepoGroup',
			]
		],
		'extlinks' => [
			'class' => ApiQueryExternalLinks::class,
			'services' => [
				'UrlUtils',
			],
		],
		'fileusage' => [
			'class' => ApiQueryBacklinksprop::class,
			'services' => [
				// Same as for linkshere, redirects, transcludedin
				'LinksMigration',
			]
		],
		'images' => [
			'class' => ApiQueryImages::class,
		],
		'imageinfo' => [
			'class' => ApiQueryImageInfo::class,
			'services' => [
				// Same as for stashimageinfo
				'RepoGroup',
				'ContentLanguage',
				'BadFileLookup',
			]
		],
		'info' => [
			'class' => ApiQueryInfo::class,
			'services' => [
				'ContentLanguage',
				'LinkBatchFactory',
				'NamespaceInfo',
				'TitleFactory',
				'TitleFormatter',
				'WatchedItemStore',
				'LanguageConverterFactory',
				'RestrictionStore',
				'LinksMigration',
				'TempUserCreator',
				'UserFactory',
				'IntroMessageBuilder',
				'PreloadedContentBuilder',
				'RevisionLookup',
				'UrlUtils',
			],
		],
		'links' => [
			'class' => ApiQueryLinks::class,
			'services' => [
				// Same as for templates
				'LinkBatchFactory',
				'LinksMigration',
			]
		],
		'linkshere' => [
			'class' => ApiQueryBacklinksprop::class,
			'services' => [
				// Same as for fileusage, redirects, transcludedin
				'LinksMigration',
			]
		],
		'iwlinks' => [
			'class' => ApiQueryIWLinks::class,
			'services' => [
				'UrlUtils',
			]
		],
		'langlinks' => [
			'class' => ApiQueryLangLinks::class,
			'services' => [
				'LanguageNameUtils',
				'ContentLanguage',
				'UrlUtils',
			]
		],
		'pageprops' => [
			'class' => ApiQueryPageProps::class,
			'services' => [
				'PageProps',
			]
		],
		'redirects' => [
			'class' => ApiQueryBacklinksprop::class,
			'services' => [
				// Same as for fileusage, linkshere, transcludedin
				'LinksMigration',
			]
		],
		'revisions' => [
			'class' => ApiQueryRevisions::class,
			'services' => [
				'RevisionStore',
				'ContentHandlerFactory',
				'ParserFactory',
				'SlotRoleRegistry',
				'ChangeTagDefStore',
				'ChangeTagsStore',
				'ActorMigration',
				'ContentRenderer',
				'ContentTransformer',
				'CommentFormatter',
				'TempUserCreator',
				'UserFactory',
				'TitleFormatter',
			]
		],
		'stashimageinfo' => [
			'class' => ApiQueryStashImageInfo::class,
			'services' => [
				// Same as for imageinfo
				'RepoGroup',
				'ContentLanguage',
				'BadFileLookup',
			]
		],
		'templates' => [
			'class' => ApiQueryLinks::class,
			'services' => [
				// Same as for links
				'LinkBatchFactory',
				'LinksMigration',
			]
		],
		'transcludedin' => [
			'class' => ApiQueryBacklinksprop::class,
			'services' => [
				// Same as for fileusage, linkshere, redirects
				'LinksMigration',
			]
		],
	];

	/**
	 * List of Api Query list modules
	 */
	private const QUERY_LIST_MODULES = [
		'allcategories' => [
			'class' => ApiQueryAllCategories::class,
		],
		'alldeletedrevisions' => [
			'class' => ApiQueryAllDeletedRevisions::class,
			'services' => [
				'RevisionStore',
				'ContentHandlerFactory',
				'ParserFactory',
				'SlotRoleRegistry',
				'ChangeTagDefStore',
				'ChangeTagsStore',
				'NamespaceInfo',
				'ContentRenderer',
				'ContentTransformer',
				'CommentFormatter',
				'TempUserCreator',
				'UserFactory',
			]
		],
		'allfileusages' => [
			'class' => ApiQueryAllLinks::class,
			'services' => [
				// Same as for alllinks, allredirects, alltransclusions
				'NamespaceInfo',
				'GenderCache',
				'LinksMigration',
				'ConnectionProvider',
			]
		],
		'allimages' => [
			'class' => ApiQueryAllImages::class,
			'services' => [
				'RepoGroup',
				'GroupPermissionsLookup',
			]
		],
		'alllinks' => [
			'class' => ApiQueryAllLinks::class,
			'services' => [
				// Same as for allfileusages, allredirects, alltransclusions
				'NamespaceInfo',
				'GenderCache',
				'LinksMigration',
				'ConnectionProvider',
			]
		],
		'allpages' => [
			'class' => ApiQueryAllPages::class,
			'services' => [
				'NamespaceInfo',
				'GenderCache',
				'RestrictionStore',
			]
		],
		'allredirects' => [
			'class' => ApiQueryAllLinks::class,
			'services' => [
				// Same as for allfileusages, alllinks, alltransclusions
				'NamespaceInfo',
				'GenderCache',
				'LinksMigration',
				'ConnectionProvider',
			]
		],
		'allrevisions' => [
			'class' => ApiQueryAllRevisions::class,
			'services' => [
				'RevisionStore',
				'ContentHandlerFactory',
				'ParserFactory',
				'SlotRoleRegistry',
				'ActorMigration',
				'NamespaceInfo',
				'ChangeTagsStore',
				'ContentRenderer',
				'ContentTransformer',
				'CommentFormatter',
				'TempUserCreator',
				'UserFactory',
			]
		],
		'mystashedfiles' => [
			'class' => ApiQueryMyStashedFiles::class,
		],
		'alltransclusions' => [
			'class' => ApiQueryAllLinks::class,
			'services' => [
				// Same as for allfileusages, alllinks, allredirects
				'NamespaceInfo',
				'GenderCache',
				'LinksMigration',
				'ConnectionProvider',
			]
		],
		'allusers' => [
			'class' => ApiQueryAllUsers::class,
			'services' => [
				'UserFactory',
				'UserGroupManager',
				'GroupPermissionsLookup',
				'ContentLanguage',
				'TempUserConfig',
			]
		],
		'backlinks' => [
			'class' => ApiQueryBacklinks::class,
			'services' => [
				'LinksMigration',
				'ConnectionProvider',
			]
		],
		'blocks' => [
			'class' => ApiQueryBlocks::class,
			'services' => [
				'DatabaseBlockStore',
				'BlockActionInfo',
				'BlockRestrictionStore',
				'CommentStore',
				'HideUserUtils',
				'CommentFormatter',
			],
		],
		'categorymembers' => [
			'class' => ApiQueryCategoryMembers::class,
			'services' => [
				'CollationFactory',
			]
		],
		'codexicons' => [
			'class' => ApiQueryCodexIcons::class,
		],
		'deletedrevs' => [
			'class' => ApiQueryDeletedrevs::class,
			'services' => [
				'CommentStore',
				'RowCommentFormatter',
				'RevisionStore',
				'ChangeTagDefStore',
				'ChangeTagsStore',
				'LinkBatchFactory',
			],
		],
		'embeddedin' => [
			'class' => ApiQueryBacklinks::class,
			'services' => [
				'LinksMigration',
				'ConnectionProvider',
			]
		],
		'exturlusage' => [
			'class' => ApiQueryExtLinksUsage::class,
			'services' => [
				'UrlUtils',
			],
		],
		'filearchive' => [
			'class' => ApiQueryFilearchive::class,
			'services' => [
				'CommentStore',
				'CommentFormatter',
			],
		],
		'imageusage' => [
			'class' => ApiQueryBacklinks::class,
			'services' => [
				'LinksMigration',
				'ConnectionProvider',
			]
		],
		'iwbacklinks' => [
			'class' => ApiQueryIWBacklinks::class,
		],
		'langbacklinks' => [
			'class' => ApiQueryLangBacklinks::class,
		],
		'logevents' => [
			'class' => ApiQueryLogEvents::class,
			'services' => [
				'CommentStore',
				'RowCommentFormatter',
				'ChangeTagDefStore',
				'ChangeTagsStore',
				'UserNameUtils',
				'LogFormatterFactory',
			],
		],
		'pageswithprop' => [
			'class' => ApiQueryPagesWithProp::class,
		],
		'pagepropnames' => [
			'class' => ApiQueryPagePropNames::class,
		],
		'prefixsearch' => [
			'class' => ApiQueryPrefixSearch::class,
			'services' => [
				'SearchEngineConfig',
				'SearchEngineFactory',
			],
		],
		'protectedtitles' => [
			'class' => ApiQueryProtectedTitles::class,
			'services' => [
				'CommentStore',
				'RowCommentFormatter'
			],
		],
		'querypage' => [
			'class' => ApiQueryQueryPage::class,
			'services' => [
				'SpecialPageFactory',
			]
		],
		'random' => [
			'class' => ApiQueryRandom::class,
			'services' => [
				'ContentHandlerFactory'
			]
		],
		'recentchanges' => [
			'class' => ApiQueryRecentChanges::class,
			'services' => [
				'CommentStore',
				'RowCommentFormatter',
				'ChangeTagDefStore',
				'ChangeTagsStore',
				'SlotRoleStore',
				'SlotRoleRegistry',
				'UserNameUtils',
				'TempUserConfig',
				'LogFormatterFactory',
			],
		],
		'search' => [
			'class' => ApiQuerySearch::class,
			'services' => [
				'SearchEngineConfig',
				'SearchEngineFactory',
				'TitleMatcher',
			],
		],
		'tags' => [
			'class' => ApiQueryTags::class,
			'services' => [
				'ChangeTagsStore',
			]
		],
		'trackingcategories' => [
			'class' => ApiQueryTrackingCategories::class,
			'services' => [
				'TrackingCategories',
			]
		],
		'usercontribs' => [
			'class' => ApiQueryUserContribs::class,
			'services' => [
				'CommentStore',
				'UserIdentityLookup',
				'UserNameUtils',
				'RevisionStore',
				'ChangeTagDefStore',
				'ChangeTagsStore',
				'ActorMigration',
				'CommentFormatter',
			],
		],
		'users' => [
			'class' => ApiQueryUsers::class,
			'services' => [
				'UserNameUtils',
				'UserFactory',
				'UserGroupManager',
				'GenderCache',
				'AuthManager',
			],
		],
		'watchlist' => [
			'class' => ApiQueryWatchlist::class,
			'services' => [
				'CommentStore',
				'WatchedItemQueryService',
				'ContentLanguage',
				'NamespaceInfo',
				'GenderCache',
				'CommentFormatter',
				'TempUserConfig',
				'LogFormatterFactory',
			],
		],
		'watchlistraw' => [
			'class' => ApiQueryWatchlistRaw::class,
			'services' => [
				'WatchedItemQueryService',
				'ContentLanguage',
				'NamespaceInfo',
				'GenderCache',
			]
		],
	];

	/**
	 * List of Api Query meta modules
	 */
	private const QUERY_META_MODULES = [
		'allmessages' => [
			'class' => ApiQueryAllMessages::class,
			'services' => [
				'ContentLanguage',
				'LanguageFactory',
				'LanguageNameUtils',
				'LocalisationCache',
				'MessageCache',
			]
		],
		'authmanagerinfo' => [
			'class' => ApiQueryAuthManagerInfo::class,
			'services' => [
				'AuthManager',
			]
		],
		'siteinfo' => [
			'class' => ApiQuerySiteinfo::class,
			'services' => [
				'UserOptionsLookup',
				'UserGroupManager',
				'HookContainer',
				'LanguageConverterFactory',
				'LanguageFactory',
				'LanguageNameUtils',
				'ContentLanguage',
				'NamespaceInfo',
				'InterwikiLookup',
				'ParserFactory',
				'MagicWordFactory',
				'SpecialPageFactory',
				'SkinFactory',
				'DBLoadBalancer',
				'ReadOnlyMode',
				'UrlUtils',
				'TempUserConfig',
				'GroupPermissionsLookup',
			]
		],
		'userinfo' => [
			'class' => ApiQueryUserInfo::class,
			'services' => [
				'TalkPageNotificationManager',
				'WatchedItemStore',
				'UserEditTracker',
				'UserOptionsLookup',
				'UserGroupManager',
			]
		],
		'filerepoinfo' => [
			'class' => ApiQueryFileRepoInfo::class,
			'services' => [
				'RepoGroup',
			]
		],
		'tokens' => [
			'class' => ApiQueryTokens::class,
		],
		'languageinfo' => [
			'class' => ApiQueryLanguageinfo::class,
			'services' => [
				'LanguageFactory',
				'LanguageNameUtils',
				'LanguageFallback',
				'LanguageConverterFactory',
			],
		],
	];

	/**
	 * @var ApiPageSet
	 */
	private $mPageSet;

	/** @var array */
	private $mParams;
	/** @var ApiModuleManager */
	private $mModuleMgr;

	private WikiExporterFactory $wikiExporterFactory;
	private TitleFormatter $titleFormatter;
	private TitleFactory $titleFactory;

	public function __construct(
		ApiMain $main,
		string $action,
		ObjectFactory $objectFactory,
		WikiExporterFactory $wikiExporterFactory,
		TitleFormatter $titleFormatter,
		TitleFactory $titleFactory
	) {
		parent::__construct( $main, $action );

		$this->mModuleMgr = new ApiModuleManager(
			$this,
			$objectFactory
		);

		// Allow custom modules to be added in LocalSettings.php
		$config = $this->getConfig();
		$this->mModuleMgr->addModules( self::QUERY_PROP_MODULES, 'prop' );
		$this->mModuleMgr->addModules( $config->get( MainConfigNames::APIPropModules ), 'prop' );
		$this->mModuleMgr->addModules( self::QUERY_LIST_MODULES, 'list' );
		$this->mModuleMgr->addModules( $config->get( MainConfigNames::APIListModules ), 'list' );
		$this->mModuleMgr->addModules( self::QUERY_META_MODULES, 'meta' );
		$this->mModuleMgr->addModules( $config->get( MainConfigNames::APIMetaModules ), 'meta' );

		$this->getHookRunner()->onApiQuery__moduleManager( $this->mModuleMgr );

		// Create PageSet that will process titles/pageids/revids/generator
		$this->mPageSet = new ApiPageSet( $this );
		$this->wikiExporterFactory = $wikiExporterFactory;
		$this->titleFormatter = $titleFormatter;
		$this->titleFactory = $titleFactory;
	}

	/**
	 * Overrides to return this instance's module manager.
	 * @return ApiModuleManager
	 */
	public function getModuleManager() {
		return $this->mModuleMgr;
	}

	/**
	 * Gets the set of pages the user has requested (or generated)
	 * @return ApiPageSet
	 */
	public function getPageSet() {
		return $this->mPageSet;
	}

	/**
	 * @return ApiFormatRaw|null
	 */
	public function getCustomPrinter() {
		// If &exportnowrap is set, use the raw formatter
		if ( $this->getParameter( 'export' ) &&
			$this->getParameter( 'exportnowrap' )
		) {
			return new ApiFormatRaw( $this->getMain(),
				$this->getMain()->createPrinterByName( 'xml' ) );
		} else {
			return null;
		}
	}

	/**
	 * Query execution happens in the following steps:
	 * #1 Create a PageSet object with any pages requested by the user
	 * #2 If using a generator, execute it to get a new ApiPageSet object
	 * #3 Instantiate all requested modules.
	 *    This way the PageSet object will know what shared data is required,
	 *    and minimize DB calls.
	 * #4 Output all normalization and redirect resolution information
	 * #5 Execute all requested modules
	 */
	public function execute() {
		$this->mParams = $this->extractRequestParams();

		// Instantiate requested modules
		$allModules = [];
		$this->instantiateModules( $allModules, 'prop' );
		$propModules = array_keys( $allModules );
		$this->instantiateModules( $allModules, 'list' );
		$this->instantiateModules( $allModules, 'meta' );

		// Filter modules based on continue parameter
		$continuationManager = new ApiContinuationManager( $this, $allModules, $propModules );
		$this->setContinuationManager( $continuationManager );
		/** @var ApiQueryBase[] $modules */
		$modules = $continuationManager->getRunModules();
		'@phan-var ApiQueryBase[] $modules';

		// Allow extensions to stop execution for arbitrary reasons.
		$message = 'hookaborted';
		if ( !$this->getHookRunner()->onApiQueryCheckCanExecute( $modules, $this->getUser(), $message ) ) {
			$this->dieWithError( $message );
		}

		$statsFactory = MediaWikiServices::getInstance()->getStatsFactory();

		if ( !$continuationManager->isGeneratorDone() ) {
			// Query modules may optimize data requests through the $this->getPageSet()
			// object by adding extra fields from the page table.
			foreach ( $modules as $module ) {
				// Augment api-query.$module.executeTiming metric with timings for requestExtraData()
				$timer = $statsFactory->getTiming( 'api_query_extraDataTiming_seconds' )
					->setLabel( 'module', $module->getModuleName() )
					->copyToStatsdAt( 'api-query.' . $module->getModuleName() . '.extraDataTiming' )
					->start();
				$module->requestExtraData( $this->mPageSet );
				$timer->stop();
			}
			// Populate page/revision information
			$this->mPageSet->execute();
			// Record page information (title, namespace, if exists, etc)
			$this->outputGeneralPageInfo();
		} else {
			$this->mPageSet->executeDryRun();
		}

		$cacheMode = $this->mPageSet->getCacheMode();

		// Execute all unfinished modules
		foreach ( $modules as $module ) {
			// Break down of the api.query.executeTiming metric by query module.
			$timer = $statsFactory->getTiming( 'api_query_executeTiming_seconds' )
				->setLabel( 'module', $module->getModuleName() )
				->copyToStatsdAt( 'api-query.' . $module->getModuleName() . '.executeTiming' )
				->start();

			$params = $module->extractRequestParams();
			$cacheMode = $this->mergeCacheMode(
				$cacheMode, $module->getCacheMode( $params ) );
			$scope = LoggerFactory::getContext()->addScoped( [
				'context.api_query_module_name' => $module->getModuleName(),
			] );
			$module->execute();
			ScopedCallback::consume( $scope );

			$timer->stop();

			$this->getHookRunner()->onAPIQueryAfterExecute( $module );
		}

		// Set the cache mode
		$this->getMain()->setCacheMode( $cacheMode );

		// Write the continuation data into the result
		$this->setContinuationManager( null );
		if ( $this->mParams['rawcontinue'] ) {
			$data = $continuationManager->getRawNonContinuation();
			if ( $data ) {
				$this->getResult()->addValue( null, 'query-noncontinue', $data,
					ApiResult::ADD_ON_TOP | ApiResult::NO_SIZE_CHECK );
			}
			$data = $continuationManager->getRawContinuation();
			if ( $data ) {
				$this->getResult()->addValue( null, 'query-continue', $data,
					ApiResult::ADD_ON_TOP | ApiResult::NO_SIZE_CHECK );
			}
		} else {
			$continuationManager->setContinuationIntoResult( $this->getResult() );
		}
	}

	/**
	 * Update a cache mode string, applying the cache mode of a new module to it.
	 * The cache mode may increase in the level of privacy, but public modules
	 * added to private data do not decrease the level of privacy.
	 *
	 * @param string $cacheMode
	 * @param string $modCacheMode
	 * @return string
	 */
	protected function mergeCacheMode( $cacheMode, $modCacheMode ) {
		if ( $modCacheMode === 'anon-public-user-private' ) {
			if ( $cacheMode !== 'private' ) {
				$cacheMode = 'anon-public-user-private';
			}
		} elseif ( $modCacheMode === 'public' ) {
			// do nothing, if it's public already it will stay public
		} else {
			$cacheMode = 'private';
		}

		return $cacheMode;
	}

	/**
	 * Create instances of all modules requested by the client
	 * @param array &$modules To append instantiated modules to
	 * @param string $param Parameter name to read modules from
	 */
	private function instantiateModules( &$modules, $param ) {
		$wasPosted = $this->getRequest()->wasPosted();
		if ( isset( $this->mParams[$param] ) ) {
			foreach ( $this->mParams[$param] as $moduleName ) {
				$instance = $this->mModuleMgr->getModule( $moduleName, $param );
				if ( $instance === null ) {
					ApiBase::dieDebug( __METHOD__, 'Error instantiating module' );
				}
				if ( !$wasPosted && $instance->mustBePosted() ) {
					$this->dieWithErrorOrDebug( [ 'apierror-mustbeposted', $moduleName ] );
				}
				// Ignore duplicates. TODO 2.0: die()?
				if ( !array_key_exists( $moduleName, $modules ) ) {
					$modules[$moduleName] = $instance;
				}
			}
		}
	}

	/**
	 * Appends an element for each page in the current pageSet with the
	 * most general information (id, title), plus any title normalizations
	 * and missing or invalid title/pageids/revids.
	 */
	private function outputGeneralPageInfo() {
		$pageSet = $this->getPageSet();
		$result = $this->getResult();

		// We can't really handle max-result-size failure here, but we need to
		// check anyway in case someone set the limit stupidly low.
		$fit = true;

		$values = $pageSet->getNormalizedTitlesAsResult( $result );
		if ( $values ) {
			// @phan-suppress-next-line PhanRedundantCondition
			$fit = $fit && $result->addValue( 'query', 'normalized', $values );
		}
		$values = $pageSet->getConvertedTitlesAsResult( $result );
		if ( $values ) {
			$fit = $fit && $result->addValue( 'query', 'converted', $values );
		}
		$values = $pageSet->getInterwikiTitlesAsResult( $result, $this->mParams['iwurl'] );
		if ( $values ) {
			$fit = $fit && $result->addValue( 'query', 'interwiki', $values );
		}
		$values = $pageSet->getRedirectTitlesAsResult( $result );
		if ( $values ) {
			$fit = $fit && $result->addValue( 'query', 'redirects', $values );
		}
		$values = $pageSet->getMissingRevisionIDsAsResult( $result );
		if ( $values ) {
			$fit = $fit && $result->addValue( 'query', 'badrevids', $values );
		}

		// Page elements
		// Cannot use ApiPageSet::getInvalidTitlesAndRevisions, it does not set $fakeId
		$pages = [];

		// Report any missing titles
		foreach ( $pageSet->getMissingPages() as $fakeId => $page ) {
			$vals = [];
			$vals['ns'] = $page->getNamespace();
			$vals['title'] = $this->titleFormatter->getPrefixedText( $page );
			$vals['missing'] = true;
			$title = $this->titleFactory->newFromPageIdentity( $page );
			if ( $title->isKnown() ) {
				$vals['known'] = true;
			}
			$pages[$fakeId] = $vals;
		}
		// Report any invalid titles
		foreach ( $pageSet->getInvalidTitlesAndReasons() as $fakeId => $data ) {
			$pages[$fakeId] = $data + [ 'invalid' => true ];
		}
		// Report any missing page ids
		foreach ( $pageSet->getMissingPageIDs() as $pageid ) {
			$pages[$pageid] = [
				'pageid' => $pageid,
				'missing' => true,
			];
		}
		// Report special pages
		/** @var \MediaWiki\Page\PageReference $page */
		foreach ( $pageSet->getSpecialPages() as $fakeId => $page ) {
			$vals = [];
			$vals['ns'] = $page->getNamespace();
			$vals['title'] = $this->titleFormatter->getPrefixedText( $page );
			$vals['special'] = true;
			$title = $this->titleFactory->newFromPageReference( $page );
			if ( !$title->isKnown() ) {
				$vals['missing'] = true;
			}
			$pages[$fakeId] = $vals;
		}

		// Output general page information for found titles
		foreach ( $pageSet->getGoodPages() as $pageid => $page ) {
			$vals = [];
			$vals['pageid'] = $pageid;
			$vals['ns'] = $page->getNamespace();
			$vals['title'] = $this->titleFormatter->getPrefixedText( $page );
			$pages[$pageid] = $vals;
		}

		if ( count( $pages ) ) {
			$pageSet->populateGeneratorData( $pages );
			ApiResult::setArrayType( $pages, 'BCarray' );

			if ( $this->mParams['indexpageids'] ) {
				$pageIDs = array_keys( ApiResult::stripMetadataNonRecursive( $pages ) );
				// json treats all map keys as strings - converting to match
				$pageIDs = array_map( 'strval', $pageIDs );
				ApiResult::setIndexedTagName( $pageIDs, 'id' );
				$fit = $fit && $result->addValue( 'query', 'pageids', $pageIDs );
			}

			ApiResult::setIndexedTagName( $pages, 'page' );
			$fit = $fit && $result->addValue( 'query', 'pages', $pages );
		}

		if ( !$fit ) {
			$this->dieWithError( 'apierror-badconfig-resulttoosmall', 'badconfig' );
		}

		if ( $this->mParams['export'] ) {
			$this->doExport( $pageSet, $result );
		}
	}

	/**
	 * @param ApiPageSet $pageSet Pages to be exported
	 * @param ApiResult $result Result to output to
	 */
	private function doExport( $pageSet, $result ) {
		$exportTitles = [];
		$titles = $pageSet->getGoodPages();
		if ( count( $titles ) ) {
			/** @var Title $title */
			foreach ( $titles as $title ) {
				if ( $this->getAuthority()->authorizeRead( 'read', $title ) ) {
					$exportTitles[] = $title;
				}
			}
		}

		$exporter = $this->wikiExporterFactory->getWikiExporter( $this->getDB() );
		$sink = new DumpStringOutput;
		$exporter->setOutputSink( $sink );
		$exporter->setSchemaVersion( $this->mParams['exportschema'] );
		$exporter->openStream();
		foreach ( $exportTitles as $title ) {
			$exporter->pageByTitle( $title );
		}
		$exporter->closeStream();

		// Don't check the size of exported stuff
		// It's not continuable, so it would cause more
		// problems than it'd solve
		if ( $this->mParams['exportnowrap'] ) {
			$result->reset();
			// Raw formatter will handle this
			$result->addValue( null, 'text', $sink, ApiResult::NO_SIZE_CHECK );
			$result->addValue( null, 'mime', 'text/xml', ApiResult::NO_SIZE_CHECK );
			$result->addValue( null, 'filename', 'export.xml', ApiResult::NO_SIZE_CHECK );
		} else {
			$result->addValue( 'query', 'export', $sink, ApiResult::NO_SIZE_CHECK );
			$result->addValue( 'query', ApiResult::META_BC_SUBELEMENTS, [ 'export' ] );
		}
	}

	/** @inheritDoc */
	public function getAllowedParams( $flags = 0 ) {
		$result = [
			'prop' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => 'submodule',
			],
			'list' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => 'submodule',
			],
			'meta' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => 'submodule',
			],
			'indexpageids' => false,
			'export' => false,
			'exportnowrap' => false,
			'exportschema' => [
				ParamValidator::PARAM_DEFAULT => WikiExporter::schemaVersion(),
				ParamValidator::PARAM_TYPE => XmlDumpWriter::$supportedSchemas,
			],
			'iwurl' => false,
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'rawcontinue' => false,
		];
		if ( $flags ) {
			$result += $this->getPageSet()->getFinalParams( $flags );
		}

		return $result;
	}

	/** @inheritDoc */
	public function isReadMode() {
		// We need to make an exception for certain meta modules that should be
		// accessible even without the 'read' right. Restrict the exception as
		// much as possible: no other modules allowed, and no pageset
		// parameters either. We do allow the 'rawcontinue' and 'indexpageids'
		// parameters since frameworks might add these unconditionally and they
		// can't expose anything here.
		$allowedParams = [ 'rawcontinue' => 1, 'indexpageids' => 1 ];
		$this->mParams = $this->extractRequestParams();
		$request = $this->getRequest();
		foreach ( $this->mParams + $this->getPageSet()->extractRequestParams() as $param => $value ) {
			$needed = $param === 'meta';
			if ( !isset( $allowedParams[$param] ) && $request->getCheck( $param ) !== $needed ) {
				return true;
			}
		}

		// Ask each module if it requires read mode. Any true => this returns
		// true.
		$modules = [];
		$this->instantiateModules( $modules, 'meta' );
		foreach ( $modules as $module ) {
			if ( $module->isReadMode() ) {
				return true;
			}
		}

		return false;
	}

	/** @inheritDoc */
	public function isWriteMode() {
		// Ask each module if it requires write mode. If any require write mode this returns true.
		$modules = [];
		$this->mParams = $this->extractRequestParams();
		$this->instantiateModules( $modules, 'list' );
		$this->instantiateModules( $modules, 'meta' );
		$this->instantiateModules( $modules, 'prop' );
		foreach ( $modules as $module ) {
			if ( $module->isWriteMode() ) {
				return true;
			}
		}

		return false;
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		$title = Title::newMainPage()->getPrefixedText();
		$mp = rawurlencode( $title );

		return [
			'action=query&prop=revisions&meta=siteinfo&' .
				"titles={$mp}&rvprop=user|comment&continue="
				=> 'apihelp-query-example-revisions',
			'action=query&generator=allpages&gapprefix=API/&prop=revisions&continue='
				=> 'apihelp-query-example-allpages',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return [
			'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Query',
			'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Meta',
			'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Properties',
			'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Lists',
		];
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQuery::class, 'ApiQuery' );
