<?php

/**
 * Created on Sep 7, 2006
 *
 * API for MediaWiki 1.8+
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
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( 'ApiBase.php' );
}

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

	private $mPropModuleNames, $mListModuleNames, $mMetaModuleNames;
	private $mPageSet;
	private $params, $redirect;

	private $mQueryPropModules = array(
		'info' => 'ApiQueryInfo',
		'revisions' => 'ApiQueryRevisions',
		'links' => 'ApiQueryLinks',
		'iwlinks' => 'ApiQueryIWLinks',
		'langlinks' => 'ApiQueryLangLinks',
		'images' => 'ApiQueryImages',
		'imageinfo' => 'ApiQueryImageInfo',
		'templates' => 'ApiQueryLinks',
		'categories' => 'ApiQueryCategories',
		'extlinks' => 'ApiQueryExternalLinks',
		'categoryinfo' => 'ApiQueryCategoryInfo',
		'duplicatefiles' => 'ApiQueryDuplicateFiles',
	);

	private $mQueryListModules = array(
		'allimages' => 'ApiQueryAllimages',
		'allpages' => 'ApiQueryAllpages',
		'alllinks' => 'ApiQueryAllLinks',
		'allcategories' => 'ApiQueryAllCategories',
		'allusers' => 'ApiQueryAllUsers',
		'backlinks' => 'ApiQueryBacklinks',
		'blocks' => 'ApiQueryBlocks',
		'categorymembers' => 'ApiQueryCategoryMembers',
		'deletedrevs' => 'ApiQueryDeletedrevs',
		'embeddedin' => 'ApiQueryBacklinks',
		'filearchive' => 'ApiQueryFilearchive',
		'imageusage' => 'ApiQueryBacklinks',
		'iwbacklinks' => 'ApiQueryIWBacklinks',
		'logevents' => 'ApiQueryLogEvents',
		'recentchanges' => 'ApiQueryRecentChanges',
		'search' => 'ApiQuerySearch',
		'tags' => 'ApiQueryTags',
		'usercontribs' => 'ApiQueryContributions',
		'watchlist' => 'ApiQueryWatchlist',
		'watchlistraw' => 'ApiQueryWatchlistRaw',
		'exturlusage' => 'ApiQueryExtLinksUsage',
		'users' => 'ApiQueryUsers',
		'random' => 'ApiQueryRandom',
		'protectedtitles' => 'ApiQueryProtectedTitles',
	);

	private $mQueryMetaModules = array(
		'siteinfo' => 'ApiQuerySiteinfo',
		'userinfo' => 'ApiQueryUserInfo',
		'allmessages' => 'ApiQueryAllmessages',
	);

	private $mSlaveDB = null;
	private $mNamedDB = array();

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );

		// Allow custom modules to be added in LocalSettings.php
		global $wgAPIPropModules, $wgAPIListModules, $wgAPIMetaModules;
		self::appendUserModules( $this->mQueryPropModules, $wgAPIPropModules );
		self::appendUserModules( $this->mQueryListModules, $wgAPIListModules );
		self::appendUserModules( $this->mQueryMetaModules, $wgAPIMetaModules );

		$this->mPropModuleNames = array_keys( $this->mQueryPropModules );
		$this->mListModuleNames = array_keys( $this->mQueryListModules );
		$this->mMetaModuleNames = array_keys( $this->mQueryMetaModules );

		// Allow the entire list of modules at first,
		// but during module instantiation check if it can be used as a generator.
		$this->mAllowedGenerators = array_merge( $this->mListModuleNames, $this->mPropModuleNames );
	}

	/**
	 * Helper function to append any add-in modules to the list
	 * @param $modules array Module array
	 * @param $newModules array Module array to add to $modules
	 */
	private static function appendUserModules( &$modules, $newModules ) {
		if ( is_array( $newModules ) ) {
			foreach ( $newModules as $moduleName => $moduleClass ) {
				$modules[$moduleName] = $moduleClass;
			}
		}
	}

	/**
	 * Gets a default slave database connection object
	 * @return Database
	 */
	public function getDB() {
		if ( !isset( $this->mSlaveDB ) ) {
			$this->profileDBIn();
			$this->mSlaveDB = wfGetDB( DB_SLAVE, 'api' );
			$this->profileDBOut();
		}
		return $this->mSlaveDB;
	}

	/**
	 * Get the query database connection with the given name.
	 * If no such connection has been requested before, it will be created.
	 * Subsequent calls with the same $name will return the same connection
	 * as the first, regardless of the values of $db and $groups
	 * @param $name string Name to assign to the database connection
	 * @param $db int One of the DB_* constants
	 * @param $groups array Query groups
	 * @return Database
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
	 * @return array(modulename => classname)
	 */
	function getModules() {
		return array_merge( $this->mQueryPropModules, $this->mQueryListModules, $this->mQueryMetaModules );
	}
	
	/**
	 * Get whether the specified module is a prop, list or a meta query module
	 * @param $moduleName string Name of the module to find type for
	 * @return mixed string or null
	 */
	function getModuleType( $moduleName ) {
		if ( array_key_exists ( $moduleName, $this->mQueryPropModules ) ) {
			return 'prop';
		}
		
		if ( array_key_exists ( $moduleName, $this->mQueryListModules ) ) {
			return 'list';
		}
		
		if ( array_key_exists ( $moduleName, $this->mQueryMetaModules ) ) {
			return 'meta';
		}
		
		return null;
	}

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
		$this->redirects = $this->params['redirects'];
		$this->convertTitles = $this->params['converttitles'];

		// Create PageSet
		$this->mPageSet = new ApiPageSet( $this, $this->redirects, $this->convertTitles );

		// Instantiate requested modules
		$modules = array();
		$this->instantiateModules( $modules, 'prop', $this->mQueryPropModules );
		$this->instantiateModules( $modules, 'list', $this->mQueryListModules );
		$this->instantiateModules( $modules, 'meta', $this->mQueryMetaModules );

		// If given, execute generator to substitute user supplied data with generated data.
		if ( isset( $this->params['generator'] ) ) {
			$this->executeGeneratorModule( $this->params['generator'], $modules );
		} else {
			// Append custom fields and populate page/revision information
			$this->addCustomFldsToPageSet( $modules, $this->mPageSet );
			$this->mPageSet->execute();
		}

		// Record page information (title, namespace, if exists, etc)
		$this->outputGeneralPageInfo();

		// Execute all requested modules.
		foreach ( $modules as $module ) {
			$module->profileIn();
			$module->execute();
			wfRunHooks( 'APIQueryAfterExecute', array( &$module ) );
			$module->profileOut();
		}
	}

	/**
	 * Query modules may optimize data requests through the $this->getPageSet() object
	 * by adding extra fields from the page table.
	 * This function will gather all the extra request fields from the modules.
	 * @param $modules array of module objects
	 * @param $pageSet ApiPageSet
	 */
	private function addCustomFldsToPageSet( $modules, $pageSet ) {
		// Query all requested modules.
		foreach ( $modules as $module ) {
			$module->requestExtraData( $pageSet );
		}
	}

	/**
	 * Create instances of all modules requested by the client
	 * @param $modules array to append instatiated modules to
	 * @param $param string Parameter name to read modules from
	 * @param $moduleList array(modulename => classname)
	 */
	private function instantiateModules( &$modules, $param, $moduleList ) {
		$list = @$this->params[$param];
		if ( !is_null ( $list ) ) {
			foreach ( $list as $moduleName ) {
				$modules[] = new $moduleList[$moduleName] ( $this, $moduleName );
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

		// Title normalizations
		$normValues = array();
		foreach ( $pageSet->getNormalizedTitles() as $rawTitleStr => $titleStr ) {
			$normValues[] = array(
				'from' => $rawTitleStr,
				'to' => $titleStr
			);
		}

		if ( count( $normValues ) ) {
			$result->setIndexedTagName( $normValues, 'n' );
			$result->addValue( 'query', 'normalized', $normValues );
		}
		
		// Title conversions
		$convValues = array();
		foreach ( $pageSet->getConvertedTitles() as $rawTitleStr => $titleStr ) {
			$convValues[] = array(
				'from' => $rawTitleStr,
				'to' => $titleStr
			);
		}

		if ( count( $convValues ) ) {
			$result->setIndexedTagName( $convValues, 'c' );
			$result->addValue( 'query', 'converted', $convValues );
		}		

		// Interwiki titles
		$intrwValues = array();
		foreach ( $pageSet->getInterwikiTitles() as $rawTitleStr => $interwikiStr ) {
			$intrwValues[] = array(
				'title' => $rawTitleStr,
				'iw' => $interwikiStr
			);
		}

		if ( count( $intrwValues ) ) {
			$result->setIndexedTagName( $intrwValues, 'i' );
			$result->addValue( 'query', 'interwiki', $intrwValues );
		}

		// Show redirect information
		$redirValues = array();
		foreach ( $pageSet->getRedirectTitles() as $titleStrFrom => $titleStrTo ) {
			$redirValues[] = array(
				'from' => strval( $titleStrFrom ),
				'to' => $titleStrTo
			);
		}

		if ( count( $redirValues ) ) {
			$result->setIndexedTagName( $redirValues, 'r' );
			$result->addValue( 'query', 'redirects', $redirValues );
		}

		//
		// Missing revision elements
		//
		$missingRevIDs = $pageSet->getMissingRevisionIDs();
		if ( count( $missingRevIDs ) ) {
			$revids = array();
			foreach ( $missingRevIDs as $revid ) {
				$revids[$revid] = array(
					'revid' => $revid
				);
			}
			$result->setIndexedTagName( $revids, 'rev' );
			$result->addValue( 'query', 'badrevids', $revids );
		}

		//
		// Page elements
		//
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
			if ( $title->getNamespace() == NS_SPECIAL && 
					!SpecialPage::exists( $title->getText() ) ) {
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
			$exporter = new WikiExporter( $this->getDB() );
			// WikiExporter writes to stdout, so catch its
			// output with an ob
			ob_start();
			$exporter->openStream();
			foreach ( @$pageSet->getGoodTitles() as $title ) {
				if ( $title->userCanRead() ) {
					$exporter->pageByTitle( $title );
				}
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
	}

	/**
	 * For generator mode, execute generator, and use its output as new
	 * ApiPageSet
	 * @param $generatorName string Module name
	 * @param $modules array of module objects
	 */
	protected function executeGeneratorModule( $generatorName, $modules ) {
		// Find class that implements requested generator
		if ( isset( $this->mQueryListModules[$generatorName] ) ) {
			$className = $this->mQueryListModules[$generatorName];
		} elseif ( isset( $this->mQueryPropModules[$generatorName] ) ) {
			$className = $this->mQueryPropModules[$generatorName];
		} else {
			ApiBase::dieDebug( __METHOD__, "Unknown generator=$generatorName" );
		}

		// Generator results
		$resultPageSet = new ApiPageSet( $this, $this->redirects, $this->convertTitles );

		// Create and execute the generator
		$generator = new $className ( $this, $generatorName );
		if ( !$generator instanceof ApiQueryGeneratorBase ) {
			$this->dieUsage( "Module $generatorName cannot be used as a generator", 'badgenerator' );
		}

		$generator->setGeneratorMode();

		// Add any additional fields modules may need
		$generator->requestExtraData( $this->mPageSet );
		$this->addCustomFldsToPageSet( $modules, $resultPageSet );

		// Populate page information with the original user input
		$this->mPageSet->execute();

		// populate resultPageSet with the generator output
		$generator->profileIn();
		$generator->executeGenerator( $resultPageSet );
		wfRunHooks( 'APIQueryGeneratorAfterExecute', array( &$generator, &$resultPageSet ) );
		$resultPageSet->finishPageSetGeneration();
		$generator->profileOut();

		// Swap the resulting pageset back in
		$this->mPageSet = $resultPageSet;
	}

	public function getAllowedParams() {
		return array(
			'prop' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => $this->mPropModuleNames
			),
			'list' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => $this->mListModuleNames
			),
			'meta' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => $this->mMetaModuleNames
			),
			'generator' => array(
				ApiBase::PARAM_TYPE => $this->mAllowedGenerators
			),
			'redirects' => false,
			'converttitles' => false,
			'indexpageids' => false,
			'export' => false,
			'exportnowrap' => false,
		);
	}

	/**
	 * Override the parent to generate help messages for all available query modules.
	 * @return string
	 */
	public function makeHelpMsg() {
		$msg = '';

		// Make sure the internal object is empty
		// (just in case a sub-module decides to optimize during instantiation)
		$this->mPageSet = null;
		$this->mAllowedGenerators = array(); // Will be repopulated

		$astriks = str_repeat( '--- ', 8 );
		$astriks2 = str_repeat( '*** ', 10 );
		$msg .= "\n$astriks Query: Prop  $astriks\n\n";
		$msg .= $this->makeHelpMsgHelper( $this->mQueryPropModules, 'prop' );
		$msg .= "\n$astriks Query: List  $astriks\n\n";
		$msg .= $this->makeHelpMsgHelper( $this->mQueryListModules, 'list' );
		$msg .= "\n$astriks Query: Meta  $astriks\n\n";
		$msg .= $this->makeHelpMsgHelper( $this->mQueryMetaModules, 'meta' );
		$msg .= "\n\n$astriks2 Modules: continuation  $astriks2\n\n";

		// Perform the base call last because the $this->mAllowedGenerators
		// will be updated inside makeHelpMsgHelper()
		// Use parent to make default message for the query module
		$msg = parent::makeHelpMsg() . $msg;

		return $msg;
	}

	/**
	 * For all modules in $moduleList, generate help messages and join them together
	 * @param $moduleList array(modulename => classname)
	 * @param $paramName string Parameter name
	 * @return string
	 */
	private function makeHelpMsgHelper( $moduleList, $paramName ) {
		$moduleDescriptions = array();

		foreach ( $moduleList as $moduleName => $moduleClass ) {
			$module = new $moduleClass ( $this, $moduleName, null );

			$msg = ApiMain::makeHelpMsgHeader( $module, $paramName );
			$msg2 = $module->makeHelpMsg();
			if ( $msg2 !== false ) {
				$msg .= $msg2;
			}
			if ( $module instanceof ApiQueryGeneratorBase ) {
				$this->mAllowedGenerators[] = $moduleName;
				$msg .= "Generator:\n  This module may be used as a generator\n";
			}
			$moduleDescriptions[] = $msg;
		}

		return implode( "\n", $moduleDescriptions );
	}

	/**
	 * Override to add extra parameters from PageSet
	 * @return string
	 */
	public function makeHelpMsgParameters() {
		$psModule = new ApiPageSet( $this );
		return $psModule->makeHelpMsgParameters() . parent::makeHelpMsgParameters();
	}

	public function shouldCheckMaxlag() {
		return true;
	}

	public function getParamDescription() {
		return array(
			'prop' => 'Which properties to get for the titles/revisions/pageids. Module help is available below',
			'list' => 'Which lists to get. Module help is available below',
			'meta' => 'Which metadata to get about the site. Module help is available below',
			'generator' => array( 'Use the output of a list as the input for other prop/list/meta items',
					'NOTE: generator parameter names must be prefixed with a \'g\', see examples' ),
			'redirects' => 'Automatically resolve redirects',
			'converttitles' => "Convert titles to other variants if necessary. Only works if the wiki's content language supports variant conversion.",
			'indexpageids' => 'Include an additional pageids section listing all returned page IDs',
			'export' => 'Export the current revisions of all given or generated pages',
			'exportnowrap' => 'Return the export XML without wrapping it in an XML result (same format as Special:Export). Can only be used with export',
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
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'badgenerator', 'info' => 'Module $generatorName cannot be used as a generator' ),
		) );
	}

	protected function getExamples() {
		return array(
			'api.php?action=query&prop=revisions&meta=siteinfo&titles=Main%20Page&rvprop=user|comment',
			'api.php?action=query&generator=allpages&gapprefix=API/&prop=revisions',
		);
	}

	public function getVersion() {
		$psModule = new ApiPageSet( $this );
		$vers = array();
		$vers[] = __CLASS__ . ': $Id$';
		$vers[] = $psModule->getVersion();
		return $vers;
	}
}
