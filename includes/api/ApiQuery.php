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
		'duplicatefiles' => 'ApiQueryDuplicateFiles',
		'extlinks' => 'ApiQueryExternalLinks',
		'images' => 'ApiQueryImages',
		'imageinfo' => 'ApiQueryImageInfo',
		'info' => 'ApiQueryInfo',
		'links' => 'ApiQueryLinks',
		'iwlinks' => 'ApiQueryIWLinks',
		'langlinks' => 'ApiQueryLangLinks',
		'pageprops' => 'ApiQueryPageProps',
		'revisions' => 'ApiQueryRevisions',
		'stashimageinfo' => 'ApiQueryStashImageInfo',
		'templates' => 'ApiQueryLinks',
	);

	/**
	 * List of Api Query list modules
	 * @var array
	 */
	private static $QueryListModules = array(
		'allcategories' => 'ApiQueryAllCategories',
		'allimages' => 'ApiQueryAllImages',
		'alllinks' => 'ApiQueryAllLinks',
		'allpages' => 'ApiQueryAllPages',
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
	);

	/**
	 * List of Api Query generator modules
	 * Defined in code, rather than being derived at runtime,
	 * due to performance reasons
	 * @var array
	 */
	private $mQueryGenerators = array(
		'allcategories' => 'ApiQueryAllCategories',
		'allimages' => 'ApiQueryAllImages',
		'alllinks' => 'ApiQueryAllLinks',
		'allpages' => 'ApiQueryAllPages',
		'alltransclusions' => 'ApiQueryAllLinks',
		'backlinks' => 'ApiQueryBacklinks',
		'categories' => 'ApiQueryCategories',
		'categorymembers' => 'ApiQueryCategoryMembers',
		'duplicatefiles' => 'ApiQueryDuplicateFiles',
		'embeddedin' => 'ApiQueryBacklinks',
		'exturlusage' => 'ApiQueryExtLinksUsage',
		'images' => 'ApiQueryImages',
		'imageusage' => 'ApiQueryBacklinks',
		'iwbacklinks' => 'ApiQueryIWBacklinks',
		'langbacklinks' => 'ApiQueryLangBacklinks',
		'links' => 'ApiQueryLinks',
		'protectedtitles' => 'ApiQueryProtectedTitles',
		'querypage' => 'ApiQueryQueryPage',
		'random' => 'ApiQueryRandom',
		'recentchanges' => 'ApiQueryRecentChanges',
		'search' => 'ApiQuerySearch',
		'templates' => 'ApiQueryLinks',
		'watchlist' => 'ApiQueryWatchlist',
		'watchlistraw' => 'ApiQueryWatchlistRaw',
	);

	/**
	 * @var ApiPageSet
	 */
	private $mPageSet;

	private $params, $iwUrl;
	private $mNamedDB = array();
	private $mModuleMgr;

	/**
	 * @param $main ApiMain
	 * @param $action string
	 */
	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );

		$this->mModuleMgr = new ApiModuleManager( $this );

		// Allow custom modules to be added in LocalSettings.php
		global $wgAPIPropModules, $wgAPIListModules, $wgAPIMetaModules;
		$this->mModuleMgr->addModules( self::$QueryPropModules, 'prop' );
		$this->mModuleMgr->addModules( $wgAPIPropModules, 'prop' );
		$this->mModuleMgr->addModules( self::$QueryListModules, 'list' );
		$this->mModuleMgr->addModules( $wgAPIListModules, 'list' );
		$this->mModuleMgr->addModules( self::$QueryMetaModules, 'meta' );
		$this->mModuleMgr->addModules( $wgAPIMetaModules, 'meta' );

		global $wgAPIGeneratorModules;
		if ( is_array( $wgAPIGeneratorModules ) ) {
			foreach ( $wgAPIGeneratorModules as $moduleName => $moduleClass ) {
				$this->mQueryGenerators[$moduleName] = $moduleClass;
			}
		}

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
	 * @param $name string Name to assign to the database connection
	 * @param $db int One of the DB_* constants
	 * @param $groups array Query groups
	 * @return DatabaseBase
	 */
	public function getNamedDB( $name, $db, $groups ) {
		if ( !array_key_exists( $name, $this->mNamedDB ) ) {
			$this->profileDBIn();
			$this->mNamedDB[$name] = wfGetDB( $db, $groups );
			$this->profileDBOut();
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
	 * @return array array(modulename => classname)
	 */
	public function getModules() {
		wfDeprecated( __METHOD__, '1.21' );
		return $this->getModuleManager()->getNamesWithClasses();
	}

	/**
	 * Get the generators array mapping module names to class names
	 * @return array array(modulename => classname)
	 */
	public function getGenerators() {
		return $this->mQueryGenerators;
	}

	/**
	 * Get whether the specified module is a prop, list or a meta query module
	 * @deprecated since 1.21, use getModuleManager()->getModuleGroup()
	 * @param $moduleName string Name of the module to find type for
	 * @return mixed string or null
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
				$this->getParameter( 'exportnowrap' ) )
		{
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
		$this->params = $this->extractRequestParams();
		$this->iwUrl = $this->params['iwurl'];

		// Instantiate requested modules
		$modules = array();
		$this->instantiateModules( $modules, 'prop' );
		$this->instantiateModules( $modules, 'list' );
		$this->instantiateModules( $modules, 'meta' );

		// Query modules may optimize data requests through the $this->getPageSet()
		// object by adding extra fields from the page table.
		// This function will gather all the extra request fields from the modules.
		foreach ( $modules as $module ) {
			$module->requestExtraData( $this->mPageSet );
		}

		// Populate page/revision information
		$this->mPageSet->execute();
		$cacheMode = $this->mPageSet->getCacheMode();

		// Record page information (title, namespace, if exists, etc)
		$this->outputGeneralPageInfo();

		// Execute all requested modules.
		/**
		 * @var $module ApiQueryBase
		 */
		foreach ( $modules as $module ) {
			$params = $module->extractRequestParams();
			$cacheMode = $this->mergeCacheMode(
				$cacheMode, $module->getCacheMode( $params ) );
			$module->profileIn();
			$module->execute();
			wfRunHooks( 'APIQueryAfterExecute', array( &$module ) );
			$module->profileOut();
		}

		// Set the cache mode
		$this->getMain()->setCacheMode( $cacheMode );
	}

	/**
	 * Update a cache mode string, applying the cache mode of a new module to it.
	 * The cache mode may increase in the level of privacy, but public modules
	 * added to private data do not decrease the level of privacy.
	 *
	 * @param $cacheMode string
	 * @param $modCacheMode string
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
	 * @param $modules Array to append instantiated modules to
	 * @param $param string Parameter name to read modules from
	 */
	private function instantiateModules( &$modules, $param ) {
		if ( isset( $this->params[$param] ) ) {
			foreach ( $this->params[$param] as $moduleName ) {
				$modules[] = $this->mModuleMgr->getModule( $moduleName );
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

		// We don't check for a full result set here because we can't be adding
		// more than 380K. The maximum revision size is in the megabyte range,
		// and the maximum result size must be even higher than that.

		$normValues = $pageSet->getNormalizedTitlesAsResult( $result );
		if ( $normValues ) {
			$result->addValue( 'query', 'normalized', $normValues );
		}
		$convValues = $pageSet->getConvertedTitlesAsResult( $result );
		if ( $convValues  ) {
			$result->addValue( 'query', 'converted', $convValues );
		}
		$intrwValues = $pageSet->getInterwikiTitlesAsResult( $result, $this->iwUrl );
		if ( $intrwValues ) {
			$result->addValue( 'query', 'interwiki', $intrwValues );
		}
		$redirValues = $pageSet->getRedirectTitlesAsResult( $result );
		if ( $redirValues ) {
			$result->addValue( 'query', 'redirects', $redirValues );
		}
		$missingRevIDs = $pageSet->getMissingRevisionIDsAsResult( $result );
		if ( $missingRevIDs ) {
			$result->addValue( 'query', 'badrevids', $missingRevIDs );
		}

		// Page elements
		$pages = array();

		// Report any missing titles
		foreach ( $pageSet->getMissingTitles() as $fakeId => $title ) {
			$vals = array();
			ApiQueryBase::addTitleInfo( $vals, $title );
			$vals['missing'] = '';
			$pages[$fakeId] = $vals;
		}
		// Report any invalid titles
		foreach ( $pageSet->getInvalidTitles() as $fakeId => $title ) {
			$pages[$fakeId] = array( 'title' => $title, 'invalid' => '' );
		}
		// Report any missing page ids
		foreach ( $pageSet->getMissingPageIDs() as $pageid ) {
			$pages[$pageid] = array(
				'pageid' => $pageid,
				'missing' => ''
			);
		}
		// Report special pages
		foreach ( $pageSet->getSpecialTitles() as $fakeId => $title ) {
			$vals = array();
			ApiQueryBase::addTitleInfo( $vals, $title );
			$vals['special'] = '';
			if ( $title->isSpecialPage() &&
					!SpecialPageFactory::exists( $title->getDbKey() ) ) {
				$vals['missing'] = '';
			} elseif ( $title->getNamespace() == NS_MEDIA &&
					!wfFindFile( $title ) ) {
				$vals['missing'] = '';
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
			if ( $this->params['indexpageids'] ) {
				$pageIDs = array_keys( $pages );
				// json treats all map keys as strings - converting to match
				$pageIDs = array_map( 'strval', $pageIDs );
				$result->setIndexedTagName( $pageIDs, 'id' );
				$result->addValue( 'query', 'pageids', $pageIDs );
			}

			$result->setIndexedTagName( $pages, 'page' );
			$result->addValue( 'query', 'pages', $pages );
		}
		if ( $this->params['export'] ) {
			$this->doExport( $pageSet, $result );
		}
	}

	/**
	 * @param  $pageSet ApiPageSet Pages to be exported
	 * @param  $result ApiResult Result to output to
	 */
	private function doExport( $pageSet, $result ) {
		$exportTitles = array();
		$titles = $pageSet->getGoodTitles();
		if ( count( $titles ) ) {
			$user = $this->getUser();
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
		$result->disableSizeCheck();
		if ( $this->params['exportnowrap'] ) {
			$result->reset();
			// Raw formatter will handle this
			$result->addValue( null, 'text', $exportxml );
			$result->addValue( null, 'mime', 'text/xml' );
		} else {
			$r = array();
			ApiResult::setContent( $r, $exportxml );
			$result->addValue( 'query', 'export', $r );
		}
		$result->enableSizeCheck();
	}

	/*
	 * Create a generator object of the given type and return it
	 * @param $generatorName string Module name
	 * @return ApiQueryGeneratorBase
	 * @deprecated since 1.21, generators are maintained by ApiPageSet
	 */
	public function newGenerator( $generatorName ) {
		wfDeprecated( __METHOD__, '1.21' );
		$generator = $this->mModuleMgr->getModule( $generatorName );
		if( $generator === null ) {
			$this->dieUsage( "Unknown generator=$generatorName", 'badgenerator' );
		}
		if( !$generator instanceof ApiQueryGeneratorBase ) {
			$this->dieUsage( "Module $generatorName cannot be used as a generator", 'badgenerator' );
		}
		$generator->setGeneratorMode();
		return $generator;
	}

	public function getAllowedParams( $flags = 0 ) {
		$result = array(
			'prop' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => $this->mModuleMgr->getNames( 'prop' )
			),
			'list' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => $this->mModuleMgr->getNames( 'list' )
			),
			'meta' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => $this->mModuleMgr->getNames( 'meta' )
			),
			'indexpageids' => false,
			'export' => false,
			'exportnowrap' => false,
			'iwurl' => false,
		);
		if( $flags ) {
			$result += $this->getPageSet()->getFinalParams( $flags );
		}
		return $result;
	}

	/**
	 * Override the parent to generate help messages for all available query modules.
	 * @return string
	 */
	public function makeHelpMsg() {

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
	 * @param $group string Module group
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

	public function getParamDescription() {
		return $this->getPageSet()->getParamDescription() + array(
			'prop' => 'Which properties to get for the titles/revisions/pageids. Module help is available below',
			'list' => 'Which lists to get. Module help is available below',
			'meta' => 'Which metadata to get about the site. Module help is available below',
			'indexpageids' => 'Include an additional pageids section listing all returned page IDs',
			'export' => 'Export the current revisions of all given or generated pages',
			'exportnowrap' => 'Return the export XML without wrapping it in an XML result (same format as Special:Export). Can only be used with export',
			'iwurl' => 'Whether to get the full URL if the title is an interwiki link',
		);
	}

	public function getDescription() {
		return array(
			'Query API module allows applications to get needed pieces of data from the MediaWiki databases,',
			'and is loosely based on the old query.php interface.',
			'All data modifications will first have to use query to acquire a token to prevent abuse from malicious sites'
		);
	}

	public function getPossibleErrors() {
		return array_merge(
			parent::getPossibleErrors(),
			$this->getPageSet()->getPossibleErrors()
		);
	}

	public function getExamples() {
		return array(
			'api.php?action=query&prop=revisions&meta=siteinfo&titles=Main%20Page&rvprop=user|comment',
			'api.php?action=query&generator=allpages&gapprefix=API/&prop=revisions',
		);
	}

	public function getHelpUrls() {
		return array(
			'https://www.mediawiki.org/wiki/API:Meta',
			'https://www.mediawiki.org/wiki/API:Properties',
			'https://www.mediawiki.org/wiki/API:Lists',
		);
	}
}
