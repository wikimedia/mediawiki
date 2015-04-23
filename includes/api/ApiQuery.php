<?php
/**
 *
 *
 * Created on Sep 7, 2006
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
 */

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
	private static $QueryPropModules = array(
		'categories' => 'ApiQueryCategories',
		'categoryinfo' => 'ApiQueryCategoryInfo',
		'contributors' => 'ApiQueryContributors',
		'deletedrevisions' => 'ApiQueryDeletedRevisions',
		'duplicatefiles' => 'ApiQueryDuplicateFiles',
		'extlinks' => 'ApiQueryExternalLinks',
		'fileusage' => 'ApiQueryBacklinksprop',
		'images' => 'ApiQueryImages',
		'imageinfo' => 'ApiQueryImageInfo',
		'info' => 'ApiQueryInfo',
		'links' => 'ApiQueryLinks',
		'linkshere' => 'ApiQueryBacklinksprop',
		'iwlinks' => 'ApiQueryIWLinks',
		'langlinks' => 'ApiQueryLangLinks',
		'pageprops' => 'ApiQueryPageProps',
		'redirects' => 'ApiQueryBacklinksprop',
		'revisions' => 'ApiQueryRevisions',
		'stashimageinfo' => 'ApiQueryStashImageInfo',
		'templates' => 'ApiQueryLinks',
		'transcludedin' => 'ApiQueryBacklinksprop',
	);

	/**
	 * List of Api Query list modules
	 * @var array
	 */
	private static $QueryListModules = array(
		'allcategories' => 'ApiQueryAllCategories',
		'alldeletedrevisions' => 'ApiQueryAllDeletedRevisions',
		'allfileusages' => 'ApiQueryAllLinks',
		'allimages' => 'ApiQueryAllImages',
		'alllinks' => 'ApiQueryAllLinks',
		'allpages' => 'ApiQueryAllPages',
		'allredirects' => 'ApiQueryAllLinks',
		'alltransclusions' => 'ApiQueryAllLinks',
		'allusers' => 'ApiQueryAllUsers',
		'backlinks' => 'ApiQueryBacklinks',
		'blocks' => 'ApiQueryBlocks',
		'categorymembers' => 'ApiQueryCategoryMembers',
		'deletedrevs' => 'ApiQueryDeletedrevs',
		'embeddedin' => 'ApiQueryBacklinks',
		'exturlusage' => 'ApiQueryExtLinksUsage',
		'filearchive' => 'ApiQueryFilearchive',
		'imageusage' => 'ApiQueryBacklinks',
		'iwbacklinks' => 'ApiQueryIWBacklinks',
		'langbacklinks' => 'ApiQueryLangBacklinks',
		'logevents' => 'ApiQueryLogEvents',
		'pageswithprop' => 'ApiQueryPagesWithProp',
		'pagepropnames' => 'ApiQueryPagePropNames',
		'prefixsearch' => 'ApiQueryPrefixSearch',
		'protectedtitles' => 'ApiQueryProtectedTitles',
		'querypage' => 'ApiQueryQueryPage',
		'random' => 'ApiQueryRandom',
		'recentchanges' => 'ApiQueryRecentChanges',
		'search' => 'ApiQuerySearch',
		'tags' => 'ApiQueryTags',
		'usercontribs' => 'ApiQueryContributions',
		'users' => 'ApiQueryUsers',
		'watchlist' => 'ApiQueryWatchlist',
		'watchlistraw' => 'ApiQueryWatchlistRaw',
	);

	/**
	 * List of Api Query meta modules
	 * @var array
	 */
	private static $QueryMetaModules = array(
		'allmessages' => 'ApiQueryAllMessages',
		'siteinfo' => 'ApiQuerySiteinfo',
		'userinfo' => 'ApiQueryUserInfo',
		'filerepoinfo' => 'ApiQueryFileRepoInfo',
		'tokens' => 'ApiQueryTokens',
	);

	/**
	 * @var ApiPageSet
	 */
	private $mPageSet;

	private $mParams;
	private $mNamedDB = array();
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

		Hooks::run( 'ApiQuery::moduleManager', array( $this->mModuleMgr ) );

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
	 * @param array $groups Query groups
	 * @return DatabaseBase
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
	 * Get the array mapping module names to class names
	 * @deprecated since 1.21, use getModuleManager()'s methods instead
	 * @return array Array(modulename => classname)
	 */
	public function getModules() {
		wfDeprecated( __METHOD__, '1.21' );

		return $this->getModuleManager()->getNamesWithClasses();
	}

	/**
	 * Get the generators array mapping module names to class names
	 * @deprecated since 1.21, list of generators is maintained by ApiPageSet
	 * @return array Array(modulename => classname)
	 */
	public function getGenerators() {
		wfDeprecated( __METHOD__, '1.21' );
		$gens = array();
		foreach ( $this->mModuleMgr->getNamesWithClasses() as $name => $class ) {
			if ( is_subclass_of( $class, 'ApiQueryGeneratorBase' ) ) {
				$gens[$name] = $class;
			}
		}

		return $gens;
	}

	/**
	 * Get whether the specified module is a prop, list or a meta query module
	 * @deprecated since 1.21, use getModuleManager()->getModuleGroup()
	 * @param string $moduleName Name of the module to find type for
	 * @return string|null
	 */
	function getModuleType( $moduleName ) {
		return $this->getModuleManager()->getModuleGroup( $moduleName );
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
		$allModules = array();
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
		/** @var $module ApiQueryBase */
		foreach ( $modules as $module ) {
			$params = $module->extractRequestParams();
			$cacheMode = $this->mergeCacheMode(
				$cacheMode, $module->getCacheMode( $params ) );
			$module->execute();
			Hooks::run( 'APIQueryAfterExecute', array( &$module ) );
		}

		// Set the cache mode
		$this->getMain()->setCacheMode( $cacheMode );

		// Write the continuation data into the result
		$this->setContinuationManager( null );
		if ( $this->mParams['continue'] === null ) {
			$data = $continuationManager->getRawContinuation();
			if ( $data ) {
				$this->getResult()->addValue( null, 'query-continue', $data,
					ApiResult::ADD_ON_TOP | ApiResult::NO_SIZE_CHECK );
			}
		} else {
			$continuationManager->setContinuationIntoResult( $this->getResult() );
		}

		if ( $this->mParams['continue'] === null && !$this->mParams['rawcontinue'] &&
			$this->getResult()->getResultData( 'query-continue' ) !== null
		) {
			$this->logFeatureUsage( 'action=query&!rawcontinue&!continue' );
			$this->setWarning(
				'Formatting of continuation data will be changing soon. ' .
				'To continue using the current formatting, use the \'rawcontinue\' parameter. ' .
				'To begin using the new format, pass an empty string for \'continue\' ' .
				'in the initial query.'
			);
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
					$this->dieUsageMsgOrDebug( array( 'mustbeposted', $moduleName ) );
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
		$pages = array();

		// Report any missing titles
		foreach ( $pageSet->getMissingTitles() as $fakeId => $title ) {
			$vals = array();
			ApiQueryBase::addTitleInfo( $vals, $title );
			$vals['missing'] = true;
			$pages[$fakeId] = $vals;
		}
		// Report any invalid titles
		foreach ( $pageSet->getInvalidTitles() as $fakeId => $title ) {
			$pages[$fakeId] = array( 'title' => $title, 'invalid' => true );
		}
		// Report any missing page ids
		foreach ( $pageSet->getMissingPageIDs() as $pageid ) {
			$pages[$pageid] = array(
				'pageid' => $pageid,
				'missing' => true
			);
		}
		// Report special pages
		/** @var $title Title */
		foreach ( $pageSet->getSpecialTitles() as $fakeId => $title ) {
			$vals = array();
			ApiQueryBase::addTitleInfo( $vals, $title );
			$vals['special'] = true;
			if ( $title->isSpecialPage() &&
				!SpecialPageFactory::exists( $title->getDBkey() )
			) {
				$vals['missing'] = true;
			} elseif ( $title->getNamespace() == NS_MEDIA &&
				!wfFindFile( $title )
			) {
				$vals['missing'] = true;
			}
			$pages[$fakeId] = $vals;
		}

		// Output general page information for found titles
		foreach ( $pageSet->getGoodTitles() as $pageid => $title ) {
			$vals = array();
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
			$this->dieUsage(
				'The value of $wgAPIMaxResultSize on this wiki is ' .
					'too small to hold basic result information',
				'badconfig'
			);
		}

		if ( $this->mParams['export'] ) {
			$this->doExport( $pageSet, $result );
		}
	}

	/**
	 * This method is called by the generator base when generator in the smart-continue
	 * mode tries to set 'query-continue' value. ApiQuery stores those values separately
	 * until the post-processing when it is known if the generation should continue or repeat.
	 * @deprecated since 1.24
	 * @param ApiQueryGeneratorBase $module Generator module
	 * @param string $paramName
	 * @param mixed $paramValue
	 * @return bool True if processed, false if this is a legacy continue
	 */
	public function setGeneratorContinue( $module, $paramName, $paramValue ) {
		wfDeprecated( __METHOD__, '1.24' );
		$this->getContinuationManager()->addGeneratorContinueParam( $module, $paramName, $paramValue );
		return $this->getParameter( 'continue' ) !== null;
	}

	/**
	 * @param ApiPageSet $pageSet Pages to be exported
	 * @param ApiResult $result Result to output to
	 */
	private function doExport( $pageSet, $result ) {
		$exportTitles = array();
		$titles = $pageSet->getGoodTitles();
		if ( count( $titles ) ) {
			$user = $this->getUser();
			/** @var $title Title */
			foreach ( $titles as $title ) {
				if ( $title->userCan( 'read', $user ) ) {
					$exportTitles[] = $title;
				}
			}
		}

		$exporter = new WikiExporter( $this->getDB() );
		// WikiExporter writes to stdout, so catch its
		// output with an ob
		ob_start();
		$exporter->openStream();
		foreach ( $exportTitles as $title ) {
			$exporter->pageByTitle( $title );
		}
		$exporter->closeStream();
		$exportxml = ob_get_contents();
		ob_end_clean();

		// Don't check the size of exported stuff
		// It's not continuable, so it would cause more
		// problems than it'd solve
		if ( $this->mParams['exportnowrap'] ) {
			$result->reset();
			// Raw formatter will handle this
			$result->addValue( null, 'text', $exportxml, ApiResult::NO_SIZE_CHECK );
			$result->addValue( null, 'mime', 'text/xml', ApiResult::NO_SIZE_CHECK );
		} else {
			$result->addValue( 'query', 'export', $exportxml, ApiResult::NO_SIZE_CHECK );
			$result->addValue( 'query', ApiResult::META_BC_SUBELEMENTS, array( 'export' ) );
		}
	}

	public function getAllowedParams( $flags = 0 ) {
		$result = array(
			'prop' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => 'submodule',
			),
			'list' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => 'submodule',
			),
			'meta' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => 'submodule',
			),
			'indexpageids' => false,
			'export' => false,
			'exportnowrap' => false,
			'iwurl' => false,
			'continue' => null,
			'rawcontinue' => false,
		);
		if ( $flags ) {
			$result += $this->getPageSet()->getFinalParams( $flags );
		}

		return $result;
	}

	/**
	 * Override the parent to generate help messages for all available query modules.
	 * @deprecated since 1.25
	 * @return string
	 */
	public function makeHelpMsg() {
		wfDeprecated( __METHOD__, '1.25' );

		// Use parent to make default message for the query module
		$msg = parent::makeHelpMsg();

		$querySeparator = str_repeat( '--- ', 12 );
		$moduleSeparator = str_repeat( '*** ', 14 );
		$msg .= "\n$querySeparator Query: Prop  $querySeparator\n\n";
		$msg .= $this->makeHelpMsgHelper( 'prop' );
		$msg .= "\n$querySeparator Query: List  $querySeparator\n\n";
		$msg .= $this->makeHelpMsgHelper( 'list' );
		$msg .= "\n$querySeparator Query: Meta  $querySeparator\n\n";
		$msg .= $this->makeHelpMsgHelper( 'meta' );
		$msg .= "\n\n$moduleSeparator Modules: continuation  $moduleSeparator\n\n";

		return $msg;
	}

	/**
	 * For all modules of a given group, generate help messages and join them together
	 * @deprecated since 1.25
	 * @param string $group Module group
	 * @return string
	 */
	private function makeHelpMsgHelper( $group ) {
		$moduleDescriptions = array();

		$moduleNames = $this->mModuleMgr->getNames( $group );
		sort( $moduleNames );
		foreach ( $moduleNames as $name ) {
			/**
			 * @var $module ApiQueryBase
			 */
			$module = $this->mModuleMgr->getModule( $name );

			$msg = ApiMain::makeHelpMsgHeader( $module, $group );
			$msg2 = $module->makeHelpMsg();
			if ( $msg2 !== false ) {
				$msg .= $msg2;
			}
			if ( $module instanceof ApiQueryGeneratorBase ) {
				$msg .= "Generator:\n  This module may be used as a generator\n";
			}
			$moduleDescriptions[] = $msg;
		}

		return implode( "\n", $moduleDescriptions );
	}

	public function shouldCheckMaxlag() {
		return true;
	}

	protected function getExamplesMessages() {
		return array(
			'action=query&prop=revisions&meta=siteinfo&' .
				'titles=Main%20Page&rvprop=user|comment&continue='
				=> 'apihelp-query-example-revisions',
			'action=query&generator=allpages&gapprefix=API/&prop=revisions&continue='
				=> 'apihelp-query-example-allpages',
		);
	}

	public function getHelpUrls() {
		return array(
			'https://www.mediawiki.org/wiki/API:Query',
			'https://www.mediawiki.org/wiki/API:Meta',
			'https://www.mediawiki.org/wiki/API:Properties',
			'https://www.mediawiki.org/wiki/API:Lists',
		);
	}
}
