<?php


/*
 * Created on Sep 7, 2006
 *
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2006 Yuri Astrakhan <FirstnameLastname@gmail.com>
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
	require_once ("ApiBase.php");
}

class ApiQuery extends ApiBase {

	private $mMetaModuleNames, $mPropModuleNames, $mListModuleNames;
	private $mData;

	private $mQueryMetaModules = array (
		'siteinfo' => 'ApiQuerySiteinfo'
	);
	//	'userinfo' => 'ApiQueryUserinfo',

	private $mQueryPropModules = array (
		'info' => 'ApiQueryInfo',
		'revisions' => 'ApiQueryRevisions'
	);
	//	'categories' => 'ApiQueryCategories',
	//	'imageinfo' => 'ApiQueryImageinfo',
	//	'langlinks' => 'ApiQueryLanglinks',
	//	'links' => 'ApiQueryLinks',
	//	'templates' => 'ApiQueryTemplates',

	private $mQueryListModules = array (
		'allpages' => 'ApiQueryAllpages'
	);
	//	'backlinks' => 'ApiQueryBacklinks',
	//	'categorymembers' => 'ApiQueryCategorymembers',
	//	'embeddedin' => 'ApiQueryEmbeddedin',
	//	'imagelinks' => 'ApiQueryImagelinks',
	//	'logevents' => 'ApiQueryLogevents',
	//	'recentchanges' => 'ApiQueryRecentchanges',
	//	'usercontribs' => 'ApiQueryUsercontribs',
	//	'users' => 'ApiQueryUsers',
	//	'watchlist' => 'ApiQueryWatchlist',

	private $mSlaveDB = null;

	public function __construct($main, $action) {
		parent :: __construct($main);
		$this->mMetaModuleNames = array_keys($this->mQueryMetaModules);
		$this->mPropModuleNames = array_keys($this->mQueryPropModules);
		$this->mListModuleNames = array_keys($this->mQueryListModules);

		// Allow the entire list of modules at first,
		// but during module instantiation check if it can be used as a generator.
		$this->mAllowedGenerators = array_merge($this->mListModuleNames, $this->mPropModuleNames);
	}

	public function GetDB() {
		if (!isset ($this->mSlaveDB))
			$this->mSlaveDB = & wfGetDB(DB_SLAVE);
		return $this->mSlaveDB;
	}

	public function GetData() {
		return $this->mData;
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
	public function Execute() {
		$meta = $prop = $list = $generator = $titles = $pageids = $revids = null;
		$redirects = null;
		extract($this->ExtractRequestParams());

		//
		// Create and initialize PageSet
		//
		// Only one of the titles/pageids/revids is allowed at the same time
		$dataSource = null;
		if (isset ($titles))
			$dataSource = 'titles';
		if (isset ($pageids)) {
			if (isset ($dataSource))
				$this->DieUsage("Cannot use 'pageids' at the same time as '$dataSource'", 'multisource');
			$dataSource = 'pageids';
		}
		if (isset ($revids)) {
			if (isset ($dataSource))
				$this->DieUsage("Cannot use 'revids' at the same time as '$dataSource'", 'multisource');
			$dataSource = 'revids';
		}

		$this->mData = new ApiPageSet($this, $redirects);

		switch ($dataSource) {
			case 'titles' :
				$this->mData->PopulateTitles($titles);
				break;
			case 'pageids' :
				$this->mData->PopulatePageIDs($pageids);
				break;
			case 'titles' :
				$this->mData->PopulateRevIDs($revids);
				break;
			default :
				// Do nothing - some queries do not need any of the data sources.
				break;
		}

		//
		// If generator is provided, get a new dataset to work on
		//
		if (isset ($generator))
			$this->ExecuteGenerator($generator, $redirects);

		// Instantiate required modules
		// During instantiation, modules may optimize data requests through the $this->mData object 
		// $this->mData will be lazy loaded when modules begin to request data during execution
		$modules = array ();
		if (isset ($meta))
			foreach ($meta as $moduleName)
				$modules[] = new $this->mQueryMetaModules[$moduleName] ($this, $moduleName);
		if (isset ($prop))
			foreach ($prop as $moduleName)
				$modules[] = new $this->mQueryPropModules[$moduleName] ($this, $moduleName);
		if (isset ($list))
			foreach ($list as $moduleName)
				$modules[] = new $this->mQueryListModules[$moduleName] ($this, $moduleName);

		// Title normalizations
		foreach ($this->mData->GetNormalizedTitles() as $rawTitleStr => $titleStr) {
			$this->GetResult()->AddMessage('query', 'normalized', array (
				'from' => $rawTitleStr,
				'to' => $titleStr
			), 'n');
		}

		// Show redirect information
		if ($redirects) {
			foreach ($this->mData->GetRedirectTitles() as $titleStrFrom => $titleStrTo) {
				$this->GetResult()->AddMessage('query', 'redirects', array (
					'from' => $titleStrFrom,
					'to' => $titleStrTo
				), 'r');
			}
		}

		// Execute all requested modules.
		foreach ($modules as $module)
			$module->Execute();
	}

	protected function ExecuteGenerator($generator, $redirects) {

		// Find class that implements requested generator
		if (isset ($this->mQueryListModules[$generator]))
			$className = $this->mQueryListModules[$generator];
		else
			if (isset ($this->mQueryPropModules[$generator]))
				$className = $this->mQueryPropModules[$generator];
			else
				$this->DieDebug("Unknown generator=$generator");

		$module = new $className ($this, $generator, true);

		// change $this->mData

		// TODO: implement
		$this->DieUsage("Generator execution has not been implemented", 'notimplemented');
	}

	protected function GetAllowedParams() {
		return array (
			'meta' => array (
				GN_ENUM_ISMULTI => true,
				GN_ENUM_TYPE => $this->mMetaModuleNames
			),
			'prop' => array (
				GN_ENUM_ISMULTI => true,
				GN_ENUM_TYPE => $this->mPropModuleNames
			),
			'list' => array (
				GN_ENUM_ISMULTI => true,
				GN_ENUM_TYPE => $this->mListModuleNames
			),
			//			'generator' => array (
			//				GN_ENUM_TYPE => $this->mAllowedGenerators
			//			),
			'titles' => array (
				GN_ENUM_ISMULTI => true
			),
			//			'pageids' => array (
			//				GN_ENUM_TYPE => 'integer',
			//				GN_ENUM_ISMULTI => true
			//			),
			//			'revids' => array (
			//				GN_ENUM_TYPE => 'integer',
			//				GN_ENUM_ISMULTI => true
			//			),
			'redirects' => false
		);
	}

	/**
	 * Override the parent to generate help messages for all available query modules.
	 */
	public function MakeHelpMsg() {

		// Use parent to make default message for the query module
		$msg = parent :: MakeHelpMsg();

		// Make sure the internal object is empty
		// (just in case a sub-module decides to optimize during instantiation)
		$this->mData = null;

		$astriks = str_repeat('--- ', 8);
		$msg .= "\n$astriks Query: Meta  $astriks\n\n";
		$msg .= $this->MakeHelpMsgHelper($this->mQueryMetaModules, 'meta');
		$msg .= "\n$astriks Query: Prop  $astriks\n\n";
		$msg .= $this->MakeHelpMsgHelper($this->mQueryPropModules, 'prop');
		$msg .= "\n$astriks Query: List  $astriks\n\n";
		$msg .= $this->MakeHelpMsgHelper($this->mQueryListModules, 'list');

		return $msg;
	}

	private function MakeHelpMsgHelper($moduleList, $paramName) {
		$msg = '';

		foreach ($moduleList as $moduleName => $moduleClass) {
			$msg .= "* $paramName=$moduleName *";
			$module = new $moduleClass ($this, $moduleName);
			$msg2 = $module->MakeHelpMsg();
			if ($msg2 !== false)
				$msg .= $msg2;
			$msg .= "\n";
			if ($module->GetCanGenerate())
				$msg .= "  * Can be used as a generator\n";
		}

		return $msg;
	}

	protected function GetParamDescription() {
		return array (
			'meta' => 'Which meta data to get about the site',
			'prop' => 'Which properties to get for the titles/revisions/pageids',
			'list' => 'Which lists to get',
			'generator' => 'Use the output of a list as the input for other prop/list/meta items',
			'titles' => 'A list of titles to work on',
			'pageids' => 'A list of page IDs to work on',
			'revids' => 'A list of revision IDs to work on',
			'redirects' => 'Automatically resolve redirects'
		);
	}

	protected function GetDescription() {
		return array (
			'Query API module allows applications to get needed pieces of data from the MediaWiki databases,',
			'and is loosely based on the Query API interface currently available on all MediaWiki servers.',
			'All data modifications will first have to use query to acquire a token to prevent abuse from malicious sites.'
		);
	}

	protected function GetExamples() {
		return array (
			'api.php?action=query&what=content&titles=ArticleA|ArticleB'
		);
	}
}
?>