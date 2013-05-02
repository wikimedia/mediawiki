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
		'allfileusages' => 'ApiQueryAllLinks',
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
		'pageswithprop' => 'ApiQueryPagesWithProp',
		'pagepropnames' => 'ApiQueryPagePropNames',
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
	);

	/**
	 * @var ApiPageSet
	 */
	private $mPageSet;

	private $mParams;
	private $mNamedDB = array();
	private $mModuleMgr;
	private $mGeneratorContinue;
	private $mUseLegacyContinue;

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
	 * @deprecated since 1.21, list of generators is maintained by ApiPageSet
	 * @return array array(modulename => classname)
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
		$this->mParams = $this->extractRequestParams();

		// $pagesetParams is a array of parameter names used by the pageset generator
		//   or null if pageset has already finished and is no longer needed
		// $completeModules is a set of complete modules with the name as key
		$this->initContinue( $pagesetParams, $completeModules );

		// Instantiate requested modules
		$allModules = array();
		$this->instantiateModules( $allModules, 'prop' );
		$propModules = $allModules; // Keep a copy
		$this->instantiateModules( $allModules, 'list' );
		$this->instantiateModules( $allModules, 'meta' );

		// Filter modules based on continue parameter
		$modules = $this->initModules( $allModules, $completeModules, $pagesetParams !== null );

		// Execute pageset if in legacy mode or if pageset is not done
		if ( $completeModules === null || $pagesetParams !== null ) {
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
			$module->profileIn();
			$module->execute();
			wfRunHooks( 'APIQueryAfterExecute', array( &$module ) );
			$module->profileOut();
		}

		// Set the cache mode
		$this->getMain()->setCacheMode( $cacheMode );

		if ( $completeModules === null ) {
			return; // Legacy continue, we are done
		}

		// Reformat query-continue result section
		$result = $this->getResult();
		$qc = $result->getData();
		if ( isset( $qc['query-continue'] ) ) {
			$qc = $qc['query-continue'];
			$result->unsetValue( null, 'query-continue' );
		} elseif ( $this->mGeneratorContinue !== null ) {
			$qc = array();
		} else {
			// no more "continue"s, we are done!
			return;
		}

		// we are done with all the modules that do not have result in query-continue
		$completeModules = array_merge( $completeModules, array_diff_key( $modules, $qc ) );
		if ( $pagesetParams !== null ) {
			// The pageset is still in use, check if all props have finished
			$incompleteProps = array_intersect_key( $propModules, $qc );
			if ( count( $incompleteProps ) > 0 ) {
				// Properties are not done, continue with the same pageset state - copy current parameters
				$main = $this->getMain();
				$contValues = array();
				foreach ( $pagesetParams as $param ) {
					// The param name is already prefix-encoded
					$contValues[$param] = $main->getVal( $param );
				}
			} elseif ( $this->mGeneratorContinue !== null ) {
				// Move to the next set of pages produced by pageset, properties need to be restarted
				$contValues = $this->mGeneratorContinue;
				$pagesetParams = array_keys( $contValues );
				$completeModules = array_diff_key( $completeModules, $propModules );
			} else {
				// Done with the pageset, finish up with the the lists and meta modules
				$pagesetParams = null;
			}
		}

		$continue = '||' . implode( '|', array_keys( $completeModules ) );
		if ( $pagesetParams !== null ) {
			// list of all pageset parameters to use in the next request
			$continue = implode( '|', $pagesetParams ) . $continue;
		} else {
			// we are done with the pageset
			$contValues = array();
			$continue = '-' . $continue;
		}
		$contValues['continue'] = $continue;
		foreach ( $qc as $qcModule ) {
			foreach ( $qcModule as $qcKey => $qcValue ) {
				$contValues[$qcKey] = $qcValue;
			}
		}
		$this->getResult()->addValue( null, 'continue', $contValues );
	}

	/**
	 * Parse 'continue' parameter into the list of complete modules and a list of generator parameters
	 * @param array|null $pagesetParams returns list of generator params or null if pageset is done
	 * @param array|null $completeModules returns list of finished modules (as keys), or null if legacy
	 */
	private function initContinue( &$pagesetParams, &$completeModules ) {
		$pagesetParams = array();
		$continue = $this->mParams['continue'];
		if ( $continue !== null ) {
			$this->mUseLegacyContinue = false;
			if ( $continue !== '' ) {
				// Format: ' pagesetParam1 | pagesetParam2 || module1 | module2 | module3 | ...
				// If pageset is done, use '-'
				$continue = explode( '||', $continue );
				$this->dieContinueUsageIf( count( $continue ) !== 2 );
				if ( $continue[0] === '-' ) {
					$pagesetParams = null; // No need to execute pageset
				} elseif ( $continue[0] !== '' ) {
					// list of pageset params that might need to be repeated
					$pagesetParams = explode( '|', $continue[0] );
				}
				$continue = $continue[1];
			}
			if ( $continue !== '' ) {
				$completeModules = array_flip( explode( '|', $continue ) );
			} else {
				$completeModules = array();
			}
		} else {
			$this->mUseLegacyContinue = true;
			$completeModules = null;
		}
	}

	/**
	 * Validate sub-modules, filter out completed ones, and do requestExtraData()
	 * @param array $allModules An dict of name=>instance of all modules requested by the client
	 * @param array|null $completeModules list of finished modules, or null if legacy continue
	 * @param bool $usePageset True if pageset will be executed
	 * @return array of modules to be processed during this execution
	 */
	private function initModules( $allModules, $completeModules, $usePageset ) {
		$modules = $allModules;
		$tmp = $completeModules;
		$wasPosted = $this->getRequest()->wasPosted();

		/** @var $module ApiQueryBase */
		foreach ( $allModules as $moduleName => $module ) {
			if ( !$wasPosted && $module->mustBePosted() ) {
				$this->dieUsageMsgOrDebug( array( 'mustbeposted', $moduleName ) );
			}
			if ( $completeModules !== null && array_key_exists( $moduleName, $completeModules ) ) {
				// If this module is done, mark all its params as used
				$module->extractRequestParams();
				// Make sure this module is not used during execution
				unset( $modules[$moduleName] );
				unset( $tmp[$moduleName] );
			} elseif ( $completeModules === null || $usePageset ) {
				// Query modules may optimize data requests through the $this->getPageSet()
				// object by adding extra fields from the page table.
				// This function will gather all the extra request fields from the modules.
				$module->requestExtraData( $this->mPageSet );
			} else {
				// Error - this prop module must have finished before generator is done
				$this->dieContinueUsageIf( $this->mModuleMgr->getModuleGroup( $moduleName ) === 'prop' );
			}
		}
		$this->dieContinueUsageIf( $completeModules !== null && count( $tmp ) !== 0 );
		return $modules;
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
	 * @param array $modules to append instantiated modules to
	 * @param string $param Parameter name to read modules from
	 */
	private function instantiateModules( &$modules, $param ) {
		if ( isset( $this->mParams[$param] ) ) {
			foreach ( $this->mParams[$param] as $moduleName ) {
				$instance = $this->mModuleMgr->getModule( $moduleName, $param );
				if ( $instance === null ) {
					ApiBase::dieDebug( __METHOD__, 'Error instantiating module' );
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

		// We don't check for a full result set here because we can't be adding
		// more than 380K. The maximum revision size is in the megabyte range,
		// and the maximum result size must be even higher than that.

		$values = $pageSet->getNormalizedTitlesAsResult( $result );
		if ( $values ) {
			$result->addValue( 'query', 'normalized', $values );
		}
		$values = $pageSet->getConvertedTitlesAsResult( $result );
		if ( $values ) {
			$result->addValue( 'query', 'converted', $values );
		}
		$values = $pageSet->getInterwikiTitlesAsResult( $result, $this->mParams['iwurl'] );
		if ( $values ) {
			$result->addValue( 'query', 'interwiki', $values );
		}
		$values = $pageSet->getRedirectTitlesAsResult( $result );
		if ( $values ) {
			$result->addValue( 'query', 'redirects', $values );
		}
		$values = $pageSet->getMissingRevisionIDsAsResult( $result );
		if ( $values ) {
			$result->addValue( 'query', 'badrevids', $values );
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
		/** @var $title Title */
		foreach ( $pageSet->getSpecialTitles() as $fakeId => $title ) {
			$vals = array();
			ApiQueryBase::addTitleInfo( $vals, $title );
			$vals['special'] = '';
			if ( $title->isSpecialPage() &&
					!SpecialPageFactory::exists( $title->getDBkey() ) ) {
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
			if ( $this->mParams['indexpageids'] ) {
				$pageIDs = array_keys( $pages );
				// json treats all map keys as strings - converting to match
				$pageIDs = array_map( 'strval', $pageIDs );
				$result->setIndexedTagName( $pageIDs, 'id' );
				$result->addValue( 'query', 'pageids', $pageIDs );
			}

			$result->setIndexedTagName( $pages, 'page' );
			$result->addValue( 'query', 'pages', $pages );
		}
		if ( $this->mParams['export'] ) {
			$this->doExport( $pageSet, $result );
		}
	}

	/**
	 * This method is called by the generator base when generator in the smart-continue
	 * mode tries to set 'query-continue' value. ApiQuery stores those values separately
	 * until the post-processing when it is known if the generation should continue or repeat.
	 * @param ApiQueryGeneratorBase $module generator module
	 * @param string $paramName
	 * @param mixed $paramValue
	 * @return bool true if processed, false if this is a legacy continue
	 */
	public function setGeneratorContinue( $module, $paramName, $paramValue ) {
		if ( $this->mUseLegacyContinue ) {
			return false;
		}
		$paramName = $module->encodeParamName( $paramName );
		if ( $this->mGeneratorContinue === null ) {
			$this->mGeneratorContinue = array();
		}
		$this->mGeneratorContinue[$paramName] = $paramValue;
		return true;
	}

	/**
	 * @param $pageSet ApiPageSet Pages to be exported
	 * @param $result ApiResult Result to output to
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
		$result->disableSizeCheck();
		if ( $this->mParams['exportnowrap'] ) {
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
			'continue' => null,
		);
		if ( $flags ) {
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

	public function getParamDescription() {
		return $this->getPageSet()->getFinalParamDescription() + array(
			'prop' => 'Which properties to get for the titles/revisions/pageids. Module help is available below',
			'list' => 'Which lists to get. Module help is available below',
			'meta' => 'Which metadata to get about the site. Module help is available below',
			'indexpageids' => 'Include an additional pageids section listing all returned page IDs',
			'export' => 'Export the current revisions of all given or generated pages',
			'exportnowrap' => 'Return the export XML without wrapping it in an XML result (same format as Special:Export). Can only be used with export',
			'iwurl' => 'Whether to get the full URL if the title is an interwiki link',
			'continue' => array(
				'When present, formats query-continue as key-value pairs that should simply be merged into the original request.',
				'This parameter must be set to an empty string in the initial query.',
				'This parameter is recommended for all new development, and will be made default in the next API version.' ),
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
			$this->getPageSet()->getFinalPossibleErrors()
		);
	}

	public function getExamples() {
		return array(
			'api.php?action=query&prop=revisions&meta=siteinfo&titles=Main%20Page&rvprop=user|comment&continue=',
			'api.php?action=query&generator=allpages&gapprefix=API/&prop=revisions&continue=',
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
