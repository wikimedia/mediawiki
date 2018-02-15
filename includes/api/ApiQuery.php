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

use Wikimedia\Rdbms\IDatabase;

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
	 * @var array
	 */
	private static $QueryPropModules = [
		'categories' => ApiQueryCategories::class,
		'categoryinfo' => ApiQueryCategoryInfo::class,
		'contributors' => ApiQueryContributors::class,
		'deletedrevisions' => ApiQueryDeletedRevisions::class,
		'duplicatefiles' => ApiQueryDuplicateFiles::class,
		'extlinks' => ApiQueryExternalLinks::class,
		'fileusage' => ApiQueryBacklinksprop::class,
		'images' => ApiQueryImages::class,
		'imageinfo' => ApiQueryImageInfo::class,
		'info' => ApiQueryInfo::class,
		'links' => ApiQueryLinks::class,
		'linkshere' => ApiQueryBacklinksprop::class,
		'iwlinks' => ApiQueryIWLinks::class,
		'langlinks' => ApiQueryLangLinks::class,
		'pageprops' => ApiQueryPageProps::class,
		'redirects' => ApiQueryBacklinksprop::class,
		'revisions' => ApiQueryRevisions::class,
		'stashimageinfo' => ApiQueryStashImageInfo::class,
		'templates' => ApiQueryLinks::class,
		'transcludedin' => ApiQueryBacklinksprop::class,
	];

	/**
	 * List of Api Query list modules
	 * @var array
	 */
	private static $QueryListModules = [
		'allcategories' => ApiQueryAllCategories::class,
		'alldeletedrevisions' => ApiQueryAllDeletedRevisions::class,
		'allfileusages' => ApiQueryAllLinks::class,
		'allimages' => ApiQueryAllImages::class,
		'alllinks' => ApiQueryAllLinks::class,
		'allpages' => ApiQueryAllPages::class,
		'allredirects' => ApiQueryAllLinks::class,
		'allrevisions' => ApiQueryAllRevisions::class,
		'mystashedfiles' => ApiQueryMyStashedFiles::class,
		'alltransclusions' => ApiQueryAllLinks::class,
		'allusers' => ApiQueryAllUsers::class,
		'backlinks' => ApiQueryBacklinks::class,
		'blocks' => ApiQueryBlocks::class,
		'categorymembers' => ApiQueryCategoryMembers::class,
		'deletedrevs' => ApiQueryDeletedrevs::class,
		'embeddedin' => ApiQueryBacklinks::class,
		'exturlusage' => ApiQueryExtLinksUsage::class,
		'filearchive' => ApiQueryFilearchive::class,
		'imageusage' => ApiQueryBacklinks::class,
		'iwbacklinks' => ApiQueryIWBacklinks::class,
		'langbacklinks' => ApiQueryLangBacklinks::class,
		'logevents' => ApiQueryLogEvents::class,
		'pageswithprop' => ApiQueryPagesWithProp::class,
		'pagepropnames' => ApiQueryPagePropNames::class,
		'prefixsearch' => ApiQueryPrefixSearch::class,
		'protectedtitles' => ApiQueryProtectedTitles::class,
		'querypage' => ApiQueryQueryPage::class,
		'random' => ApiQueryRandom::class,
		'recentchanges' => ApiQueryRecentChanges::class,
		'search' => ApiQuerySearch::class,
		'tags' => ApiQueryTags::class,
		'usercontribs' => ApiQueryContributions::class,
		'users' => ApiQueryUsers::class,
		'watchlist' => ApiQueryWatchlist::class,
		'watchlistraw' => ApiQueryWatchlistRaw::class,
	];

	/**
	 * List of Api Query meta modules
	 * @var array
	 */
	private static $QueryMetaModules = [
		'allmessages' => ApiQueryAllMessages::class,
		'authmanagerinfo' => ApiQueryAuthManagerInfo::class,
		'siteinfo' => ApiQuerySiteinfo::class,
		'userinfo' => ApiQueryUserInfo::class,
		'filerepoinfo' => ApiQueryFileRepoInfo::class,
		'tokens' => ApiQueryTokens::class,
	];

	/**
	 * @var ApiPageSet
	 */
	private $mPageSet;

	private $mParams;
	private $mNamedDB = [];
	private $mModuleMgr;

	/**
	 * @param ApiMain $main
	 * @param string $action
	 */
	public function __construct( ApiMain $main, $action ) {
		parent::__construct( $main, $action );

		$this->mModuleMgr = new ApiModuleManager( $this );

		// Allow custom modules to be added in LocalSettings.php
		$config = $this->getConfig();
		$this->mModuleMgr->addModules( self::$QueryPropModules, 'prop' );
		$this->mModuleMgr->addModules( $config->get( 'APIPropModules' ), 'prop' );
		$this->mModuleMgr->addModules( self::$QueryListModules, 'list' );
		$this->mModuleMgr->addModules( $config->get( 'APIListModules' ), 'list' );
		$this->mModuleMgr->addModules( self::$QueryMetaModules, 'meta' );
		$this->mModuleMgr->addModules( $config->get( 'APIMetaModules' ), 'meta' );

		Hooks::run( 'ApiQuery::moduleManager', [ $this->mModuleMgr ] );

		// Create PageSet that will process titles/pageids/revids/generator
		$this->mPageSet = new ApiPageSet( $this );
	}

	/**
	 * Overrides to return this instance's module manager.
	 * @return ApiModuleManager
	 */
	public function getModuleManager() {
		return $this->mModuleMgr;
	}

	/**
	 * Get the query database connection with the given name.
	 * If no such connection has been requested before, it will be created.
	 * Subsequent calls with the same $name will return the same connection
	 * as the first, regardless of the values of $db and $groups
	 * @param string $name Name to assign to the database connection
	 * @param int $db One of the DB_* constants
	 * @param string|string[] $groups Query groups
	 * @return IDatabase
	 */
	public function getNamedDB( $name, $db, $groups ) {
		if ( !array_key_exists( $name, $this->mNamedDB ) ) {
			$this->mNamedDB[$name] = wfGetDB( $db, $groups );
		}

		return $this->mNamedDB[$name];
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
		$modules = $continuationManager->getRunModules();

		if ( !$continuationManager->isGeneratorDone() ) {
			// Query modules may optimize data requests through the $this->getPageSet()
			// object by adding extra fields from the page table.
			foreach ( $modules as $module ) {
				$module->requestExtraData( $this->mPageSet );
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
		/** @var ApiQueryBase $module */
		foreach ( $modules as $module ) {
			$params = $module->extractRequestParams();
			$cacheMode = $this->mergeCacheMode(
				$cacheMode, $module->getCacheMode( $params ) );
			$module->execute();
			Hooks::run( 'APIQueryAfterExecute', [ &$module ] );
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
		} else { // private
			$cacheMode = 'private';
		}

		return $cacheMode;
	}

	/**
	 * Create instances of all modules requested by the client
	 * @param array $modules To append instantiated modules to
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
		$pages = [];

		// Report any missing titles
		foreach ( $pageSet->getMissingTitles() as $fakeId => $title ) {
			$vals = [];
			ApiQueryBase::addTitleInfo( $vals, $title );
			$vals['missing'] = true;
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
		/** @var Title $title */
		foreach ( $pageSet->getSpecialTitles() as $fakeId => $title ) {
			$vals = [];
			ApiQueryBase::addTitleInfo( $vals, $title );
			$vals['special'] = true;
			if ( !$title->isKnown() ) {
				$vals['missing'] = true;
			}
			$pages[$fakeId] = $vals;
		}

		// Output general page information for found titles
		foreach ( $pageSet->getGoodTitles() as $pageid => $title ) {
			$vals = [];
			$vals['pageid'] = $pageid;
			ApiQueryBase::addTitleInfo( $vals, $title );
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
		$titles = $pageSet->getGoodTitles();
		if ( count( $titles ) ) {
			$user = $this->getUser();
			/** @var Title $title */
			foreach ( $titles as $title ) {
				if ( $title->userCan( 'read', $user ) ) {
					$exportTitles[] = $title;
				}
			}
		}

		$exporter = new WikiExporter( $this->getDB() );
		$sink = new DumpStringOutput;
		$exporter->setOutputSink( $sink );
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

	public function getAllowedParams( $flags = 0 ) {
		$result = [
			'prop' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => 'submodule',
			],
			'list' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => 'submodule',
			],
			'meta' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => 'submodule',
			],
			'indexpageids' => false,
			'export' => false,
			'exportnowrap' => false,
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

	public function isReadMode() {
		// We need to make an exception for certain meta modules that should be
		// accessible even without the 'read' right. Restrict the exception as
		// much as possible: no other modules allowed, and no pageset
		// parameters either. We do allow the 'rawcontinue' and 'indexpageids'
		// parameters since frameworks might add these unconditionally and they
		// can't expose anything here.
		$this->mParams = $this->extractRequestParams();
		$params = array_filter(
			array_diff_key(
				$this->mParams + $this->getPageSet()->extractRequestParams(),
				[ 'rawcontinue' => 1, 'indexpageids' => 1 ]
			)
		);
		if ( array_keys( $params ) !== [ 'meta' ] ) {
			return true;
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

	protected function getExamplesMessages() {
		return [
			'action=query&prop=revisions&meta=siteinfo&' .
				'titles=Main%20Page&rvprop=user|comment&continue='
				=> 'apihelp-query-example-revisions',
			'action=query&generator=allpages&gapprefix=API/&prop=revisions&continue='
				=> 'apihelp-query-example-allpages',
		];
	}

	public function getHelpUrls() {
		return [
			'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Query',
			'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Meta',
			'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Properties',
			'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Lists',
		];
	}
}
