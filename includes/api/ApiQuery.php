<?php

/*
 * Created on Sep 7, 2006
 *
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2006 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

if (!defined('MEDIAWIKI')) {
	// Eclipse helper - will be ignored in production
	require_once ('ApiBase.php');
}

/**
 * This is the main query class. It behaves similar to ApiMain: based on the parameters given,
 * it will create a list of titles to work on (an instance of the ApiPageSet object)
 * instantiate and execute various property/list/meta modules,
 * and assemble all resulting data into a single ApiResult object.
 * 
 * In the generator mode, a generator will be first executed to populate a second ApiPageSet object,
 * and that object will be used for all subsequent modules.
 * 
 * @addtogroup API
 */
class ApiQuery extends ApiBase {

	private $mPropModuleNames, $mListModuleNames, $mMetaModuleNames;
	private $mPageSet;
	private $params, $redirect;

	private $mQueryPropModules = array (
		'info' => 'ApiQueryInfo',
		'revisions' => 'ApiQueryRevisions',
		'links' => 'ApiQueryLinks',
		'langlinks' => 'ApiQueryLangLinks',
		'images' => 'ApiQueryImages',
		'templates' => 'ApiQueryLinks',
		'categories' => 'ApiQueryCategories',
		'extlinks' => 'ApiQueryExternalLinks',
	);
	//	'categories' => 'ApiQueryCategories',
	//	'imageinfo' => 'ApiQueryImageinfo',
	//	'templates' => 'ApiQueryTemplates',

	private $mQueryListModules = array (
		'allpages' => 'ApiQueryAllpages',
		'logevents' => 'ApiQueryLogEvents',
		'watchlist' => 'ApiQueryWatchlist',
		'recentchanges' => 'ApiQueryRecentChanges',
		'backlinks' => 'ApiQueryBacklinks',
		'embeddedin' => 'ApiQueryBacklinks',
		'imageusage' => 'ApiQueryBacklinks',
		'usercontribs' => 'ApiQueryContributions'
	);
	//	'categorymembers' => 'ApiQueryCategorymembers',
	//	'recentchanges' => 'ApiQueryRecentchanges',
	//	'users' => 'ApiQueryUsers',
	//	'watchlist' => 'ApiQueryWatchlist',

	private $mQueryMetaModules = array (
		'siteinfo' => 'ApiQuerySiteinfo'
	);
	//	'userinfo' => 'ApiQueryUserinfo',

	private $mSlaveDB = null;
	private $mNamedDB = array();

	public function __construct($main, $action) {
		parent :: __construct($main, $action);

		// Allow custom modules to be added in LocalSettings.php		
		global $wgApiQueryPropModules, $wgApiQueryListModules, $wgApiQueryMetaModules;
		self :: appendUserModules($this->mQueryPropModules, $wgApiQueryPropModules);
		self :: appendUserModules($this->mQueryListModules, $wgApiQueryListModules);
		self :: appendUserModules($this->mQueryMetaModules, $wgApiQueryMetaModules);

		$this->mPropModuleNames = array_keys($this->mQueryPropModules);
		$this->mListModuleNames = array_keys($this->mQueryListModules);
		$this->mMetaModuleNames = array_keys($this->mQueryMetaModules);

		// Allow the entire list of modules at first,
		// but during module instantiation check if it can be used as a generator.
		$this->mAllowedGenerators = array_merge($this->mListModuleNames, $this->mPropModuleNames);
	}

	private static function appendUserModules(&$modules, $newModules) {
		if (is_array( $newModules )) {
			foreach ( $newModules as $moduleName => $moduleClass) {
				$modules[$moduleName] = $moduleClass;
			}
		}
	}

	public function getDB() {
		if (!isset ($this->mSlaveDB)) {
			$this->profileDBIn();
			$this->mSlaveDB = wfGetDB(DB_SLAVE);
			$this->profileDBOut();
		}
		return $this->mSlaveDB;
	}

	/**
	 * Get the query database connection with the given name.
	 * If no such connection has been requested before, it will be created. 
	 * Subsequent calls with the same $name will return the same connection 
	 * as the first, regardless of $db or $groups new values. 
	 */
	public function getNamedDB($name, $db, $groups) {
		if (!array_key_exists($name, $this->mNamedDB)) {
			$this->profileDBIn();
			$this->mNamedDB[$name] = wfGetDB($db, $groups);
			$this->profileDBOut();
		}
		return $this->mNamedDB[$name];
	}

	public function getPageSet() {
		return $this->mPageSet;
	}

	/**
	 * Query execution happens in the following steps:
	 * #1 Create a PageSet object with any pages requested by the user
	 * #2 If using generator, execute it to get a new PageSet object
	 * #3 Instantiate all requested modules. 
	 *    This way the PageSet object will know what shared data is required,
	 *    and minimize DB calls. 
	 * #4 Output all normalization and redirect resolution information
	 * #5 Execute all requested modules
	 */
	public function execute() {
		
		$this->params = $this->extractRequestParams();
		
		//
		// Create PageSet
		//
		$this->mPageSet = new ApiPageSet($this, $this->redirects);

		//
		// Instantiate requested modules
		//
		$modules = array ();
		$this->InstantiateModules($modules, 'prop', $this->mQueryPropModules);
		$this->InstantiateModules($modules, 'list', $this->mQueryListModules);
		$this->InstantiateModules($modules, 'meta', $this->mQueryMetaModules);

		// Modules may optimize data requests through the $this->getPageSet() object 
		// Execute all requested modules.
		foreach ($modules as $module) {
			$module->requestExtraData();
		}

		//
		// Populate page information for the given pageSet
		//
		$this->mPageSet->execute();

		//
		// If given, execute generator to substitute user supplied data with generated data.  
		//
		if (isset ($this->params['generator']))
			$this->executeGeneratorModule($this->params['generator']);

		//
		// Record page information (title, namespace, if exists, etc)
		//
		$this->outputGeneralPageInfo();

		//
		// Execute all requested modules.
		//
		foreach ($modules as $module) {
			$module->profileIn();
			$module->execute();
			$module->profileOut();
		}
	}

	/**
	 * Create instances of all modules requested by the client 
	 */
	private function InstantiateModules(&$modules, $param, $moduleList) {
		$list = $this->params[$param];
		if (isset ($list))
			foreach ($list as $moduleName)
				$modules[] = new $moduleList[$moduleName] ($this, $moduleName);
	}

	private function outputGeneralPageInfo() {

		$pageSet = $this->getPageSet();
		$result = $this->getResult();

		// Title normalizations
		$normValues = array ();
		foreach ($pageSet->getNormalizedTitles() as $rawTitleStr => $titleStr) {
			$normValues[] = array (
				'from' => $rawTitleStr,
				'to' => $titleStr
			);
		}

		if (!empty ($normValues)) {
			$result->setIndexedTagName($normValues, 'n');
			$result->addValue('query', 'normalized', $normValues);
		}

		// Show redirect information
		$redirValues = array ();
		foreach ($pageSet->getRedirectTitles() as $titleStrFrom => $titleStrTo) {
			$redirValues[] = array (
				'from' => $titleStrFrom,
				'to' => $titleStrTo
			);
		}

		if (!empty ($redirValues)) {
			$result->setIndexedTagName($redirValues, 'r');
			$result->addValue('query', 'redirects', $redirValues);
		}

		//
		// Missing revision elements
		//
		$missingRevIDs = $pageSet->getMissingRevisionIDs();
		if (!empty ($missingRevIDs)) {
			$revids = array ();
			foreach ($missingRevIDs as $revid) {
				$revids[$revid] = array (
					'revid' => $revid
				);
			}
			$result->setIndexedTagName($revids, 'rev');
			$result->addValue('query', 'badrevids', $revids);
		}

		//
		// Page elements
		//
		$pages = array ();

		// Report any missing titles
		$fakepageid = -1;
		foreach ($pageSet->getMissingTitles() as $title) {
			$vals = array();
			ApiQueryBase :: addTitleInfo($vals, $title, true);
			$vals['missing'] = '';
			$pages[$fakepageid--] = $vals;
		}

		// Report any missing page ids
		foreach ($pageSet->getMissingPageIDs() as $pageid) {
			$pages[$pageid] = array (
				'pageid' => $pageid,
				'missing' => ''
			);
		}

		// Output general page information for found titles
		foreach ($pageSet->getGoodTitles() as $pageid => $title) {
			$vals = array();
			$vals['pageid'] = $pageid;
			ApiQueryBase :: addTitleInfo($vals, $title, true);
			$pages[$pageid] = $vals;
		}

		if (!empty ($pages)) {
			
			if ($this->params['indexpageids']) {
				$pageIDs = array_keys($pages);
				// json treats all map keys as strings - converting to match
				$pageIDs = array_map('strval', $pageIDs);
				$result->setIndexedTagName($pageIDs, 'id');
				$result->addValue('query', 'pageids', $pageIDs);
			}
						
			$result->setIndexedTagName($pages, 'page');
			$result->addValue('query', 'pages', $pages);
		}
	}

	protected function executeGeneratorModule($generatorName) {

		// Find class that implements requested generator
		if (isset ($this->mQueryListModules[$generatorName])) {
			$className = $this->mQueryListModules[$generatorName];
		} elseif (isset ($this->mQueryPropModules[$generatorName])) {
			$className = $this->mQueryPropModules[$generatorName];
		} else {
			ApiBase :: dieDebug(__METHOD__, "Unknown generator=$generatorName");
		}

		// Generator results 
		$resultPageSet = new ApiPageSet($this, $this->redirects);

		// Create and execute the generator
		$generator = new $className ($this, $generatorName);
		if (!$generator instanceof ApiQueryGeneratorBase)
			$this->dieUsage("Module $generatorName cannot be used as a generator", "badgenerator");

		$generator->setGeneratorMode();
		$generator->requestExtraData();

		// populate resultPageSet with the generator output
		$generator->profileIn();
		$generator->executeGenerator($resultPageSet);
		$resultPageSet->finishPageSetGeneration();
		$generator->profileOut();

		// Swap the resulting pageset back in
		$this->mPageSet = $resultPageSet;
	}

	/**
	 * Returns the list of allowed parameters for this module.
	 * Qurey module also lists all ApiPageSet parameters as its own. 
	 */
	protected function getAllowedParams() {
		return array (
			'prop' => array (
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => $this->mPropModuleNames
			),
			'list' => array (
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => $this->mListModuleNames
			),
			'meta' => array (
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => $this->mMetaModuleNames
			),
			'generator' => array (
				ApiBase :: PARAM_TYPE => $this->mAllowedGenerators
			),
			'redirects' => false,
			'indexpageids' => false,
		);
	}

	/**
	 * Override the parent to generate help messages for all available query modules.
	 */
	public function makeHelpMsg() {

		// Use parent to make default message for the query module
		$msg = parent :: makeHelpMsg();

		// Make sure the internal object is empty
		// (just in case a sub-module decides to optimize during instantiation)
		$this->mPageSet = null;

		$astriks = str_repeat('--- ', 8);
		$msg .= "\n$astriks Query: Prop  $astriks\n\n";
		$msg .= $this->makeHelpMsgHelper($this->mQueryPropModules, 'prop');
		$msg .= "\n$astriks Query: List  $astriks\n\n";
		$msg .= $this->makeHelpMsgHelper($this->mQueryListModules, 'list');
		$msg .= "\n$astriks Query: Meta  $astriks\n\n";
		$msg .= $this->makeHelpMsgHelper($this->mQueryMetaModules, 'meta');

		return $msg;
	}

	private function makeHelpMsgHelper($moduleList, $paramName) {

		$moduleDscriptions = array ();

		foreach ($moduleList as $moduleName => $moduleClass) {
			$msg = "* $paramName=$moduleName *";
			$module = new $moduleClass ($this, $moduleName, null);
			$msg2 = $module->makeHelpMsg();
			if ($msg2 !== false)
				$msg .= $msg2;
			if ($module instanceof ApiQueryGeneratorBase)
				$msg .= "Generator:\n  This module may be used as a generator\n";
			$moduleDscriptions[] = $msg;
		}

		return implode("\n", $moduleDscriptions);
	}

	/**
	 * Override to add extra parameters from PageSet
	 */
	public function makeHelpMsgParameters() {
		$psModule = new ApiPageSet($this);
		return $psModule->makeHelpMsgParameters() . parent :: makeHelpMsgParameters();
	}

	protected function getParamDescription() {
		return array (
			'prop' => 'Which properties to get for the titles/revisions/pageids',
			'list' => 'Which lists to get',
			'meta' => 'Which meta data to get about the site',
			'generator' => 'Use the output of a list as the input for other prop/list/meta items',
			'redirects' => 'Automatically resolve redirects',
			'indexpageids' => 'Include an additional pageids section listing all returned page IDs.'
		);
	}

	protected function getDescription() {
		return array (
			'Query API module allows applications to get needed pieces of data from the MediaWiki databases,',
			'and is loosely based on the Query API interface currently available on all MediaWiki servers.',
			'All data modifications will first have to use query to acquire a token to prevent abuse from malicious sites.'
		);
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&prop=revisions&meta=siteinfo&titles=Main%20Page&rvprop=user|comment'
		);
	}

	public function getVersion() {
		$psModule = new ApiPageSet($this);
		$vers = array ();
		$vers[] = __CLASS__ . ': $Id$';
		$vers[] = $psModule->getVersion();
		return $vers;
	}
}
?>
