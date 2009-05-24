<?php

/*
 * Created on Sep 25, 2006
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
	require_once ('ApiQueryBase.php');
}

/**
 * A query module to show basic page information.
 *
 * @ingroup API
 */
class ApiQueryInfo extends ApiQueryBase {
	
	private $fld_protection = false, $fld_talkid = false,
		$fld_subjectid = false, $fld_url = false,
		$fld_readable = false;

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'in');
	}

	public function requestExtraData($pageSet) {
		$pageSet->requestField('page_restrictions');
		$pageSet->requestField('page_is_redirect');
		$pageSet->requestField('page_is_new');
		$pageSet->requestField('page_counter');
		$pageSet->requestField('page_touched');
		$pageSet->requestField('page_latest');
		$pageSet->requestField('page_len');
	}

	/**
	 * Get an array mapping token names to their handler functions.
	 * The prototype for a token function is func($pageid, $title)
	 * it should return a token or false (permission denied)
	 * @return array(tokenname => function)
	 */
	protected function getTokenFunctions() {
		// Don't call the hooks twice
		if(isset($this->tokenFunctions))
			return $this->tokenFunctions;

		// If we're in JSON callback mode, no tokens can be obtained
		if(!is_null($this->getMain()->getRequest()->getVal('callback')))
			return array();

		$this->tokenFunctions = array(
			'edit' => array( 'ApiQueryInfo', 'getEditToken' ),
			'delete' => array( 'ApiQueryInfo', 'getDeleteToken' ),
			'protect' => array( 'ApiQueryInfo', 'getProtectToken' ),
			'move' => array( 'ApiQueryInfo', 'getMoveToken' ),
			'block' => array( 'ApiQueryInfo', 'getBlockToken' ),
			'unblock' => array( 'ApiQueryInfo', 'getUnblockToken' ),
			'email' => array( 'ApiQueryInfo', 'getEmailToken' ),
			'import' => array( 'ApiQueryInfo', 'getImportToken' ),
		);
		wfRunHooks('APIQueryInfoTokens', array(&$this->tokenFunctions));
		return $this->tokenFunctions;
	}

	public static function getEditToken($pageid, $title)
	{
		// We could check for $title->userCan('edit') here,
		// but that's too expensive for this purpose
		// and would break caching
		global $wgUser;
		if(!$wgUser->isAllowed('edit'))
			return false;
		
		// The edit token is always the same, let's exploit that
		static $cachedEditToken = null;
		if(!is_null($cachedEditToken))
			return $cachedEditToken;

		$cachedEditToken = $wgUser->editToken();
		return $cachedEditToken;
	}
	
	public static function getDeleteToken($pageid, $title)
	{
		global $wgUser;
		if(!$wgUser->isAllowed('delete'))
			return false;			

		static $cachedDeleteToken = null;
		if(!is_null($cachedDeleteToken))
			return $cachedDeleteToken;

		$cachedDeleteToken = $wgUser->editToken();
		return $cachedDeleteToken;
	}

	public static function getProtectToken($pageid, $title)
	{
		global $wgUser;
		if(!$wgUser->isAllowed('protect'))
			return false;

		static $cachedProtectToken = null;
		if(!is_null($cachedProtectToken))
			return $cachedProtectToken;

		$cachedProtectToken = $wgUser->editToken();
		return $cachedProtectToken;
	}

	public static function getMoveToken($pageid, $title)
	{
		global $wgUser;
		if(!$wgUser->isAllowed('move'))
			return false;

		static $cachedMoveToken = null;
		if(!is_null($cachedMoveToken))
			return $cachedMoveToken;

		$cachedMoveToken = $wgUser->editToken();
		return $cachedMoveToken;
	}

	public static function getBlockToken($pageid, $title)
	{
		global $wgUser;
		if(!$wgUser->isAllowed('block'))
			return false;

		static $cachedBlockToken = null;
		if(!is_null($cachedBlockToken))
			return $cachedBlockToken;

		$cachedBlockToken = $wgUser->editToken();
		return $cachedBlockToken;
	}

	public static function getUnblockToken($pageid, $title)
	{
		// Currently, this is exactly the same as the block token
		return self::getBlockToken($pageid, $title);
	}

	public static function getEmailToken($pageid, $title)
	{
		global $wgUser;
		if(!$wgUser->canSendEmail() || $wgUser->isBlockedFromEmailUser())
			return false;

		static $cachedEmailToken = null;
		if(!is_null($cachedEmailToken))
			return $cachedEmailToken;

		$cachedEmailToken = $wgUser->editToken();
		return $cachedEmailToken;
	}
	
	public static function getImportToken($pageid, $title)
	{
		global $wgUser;
		if(!$wgUser->isAllowed('import'))
			return false;

		static $cachedImportToken = null;
		if(!is_null($cachedImportToken))
			return $cachedImportToken;

		$cachedImportToken = $wgUser->editToken();
		return $cachedImportToken;
	}

	public function execute() {
		$this->params = $this->extractRequestParams();
		if(!is_null($this->params['prop'])) {
			$prop = array_flip($this->params['prop']);
			$this->fld_protection = isset($prop['protection']);
			$this->fld_talkid = isset($prop['talkid']);
			$this->fld_subjectid = isset($prop['subjectid']);
			$this->fld_url = isset($prop['url']);
			$this->fld_readable = isset($prop['readable']);
		}

		$pageSet = $this->getPageSet();
		$this->titles = $pageSet->getGoodTitles();
		$this->missing = $pageSet->getMissingTitles();
		$this->everything = $this->titles + $this->missing;
		$result = $this->getResult();

		uasort($this->everything, array('Title', 'compare'));
		if(!is_null($this->params['continue']))
		{
			// Throw away any titles we're gonna skip so they don't
			// clutter queries
			$cont = explode('|', $this->params['continue']);
			if(count($cont) != 2)
				$this->dieUsage("Invalid continue param. You should pass the original " .
						"value returned by the previous query", "_badcontinue");
			$conttitle = Title::makeTitleSafe($cont[0], $cont[1]);
			foreach($this->everything as $pageid => $title)
			{
				if(Title::compare($title, $conttitle) >= 0)
					break;
				unset($this->titles[$pageid]);
				unset($this->missing[$pageid]);
				unset($this->everything[$pageid]);
			}
		}

		$this->pageRestrictions = $pageSet->getCustomField('page_restrictions');
		$this->pageIsRedir = $pageSet->getCustomField('page_is_redirect');
		$this->pageIsNew = $pageSet->getCustomField('page_is_new');
		$this->pageCounter = $pageSet->getCustomField('page_counter');
		$this->pageTouched = $pageSet->getCustomField('page_touched');
		$this->pageLatest = $pageSet->getCustomField('page_latest');
		$this->pageLength = $pageSet->getCustomField('page_len');

		$db = $this->getDB();
		// Get protection info if requested
		if ($this->fld_protection)
			$this->getProtectionInfo();

		// Run the talkid/subjectid query if requested
		if($this->fld_talkid || $this->fld_subjectid)
			$this->getTSIDs();

		foreach($this->everything as $pageid => $title) {
			$pageInfo = $this->extractPageInfo($pageid, $title);
			$fit = $result->addValue(array (
				'query',
				'pages'
			), $pageid, $pageInfo);
			if(!$fit)
			{
				$this->setContinueEnumParameter('continue',
						$title->getNamespace() . '|' .
						$title->getText());
				break;
			}
		}
	}

	/**
	 * Get a result array with information about a title
	 * @param $pageid int Page ID (negative for missing titles)
	 * @param $title Title object
	 * @return array
	 */
	private function extractPageInfo($pageid, $title)
	{
		$pageInfo = array();
		if($title->exists())
		{
			$pageInfo['touched'] = wfTimestamp(TS_ISO_8601, $this->pageTouched[$pageid]);
			$pageInfo['lastrevid'] = intval($this->pageLatest[$pageid]);
			$pageInfo['counter'] = intval($this->pageCounter[$pageid]);
			$pageInfo['length'] = intval($this->pageLength[$pageid]);
			if ($this->pageIsRedir[$pageid])
				$pageInfo['redirect'] = '';
			if ($this->pageIsNew[$pageid])
				$pageInfo['new'] = '';
		}

		if (!is_null($this->params['token'])) {
			$tokenFunctions = $this->getTokenFunctions();
			$pageInfo['starttimestamp'] = wfTimestamp(TS_ISO_8601, time());
			foreach($this->params['token'] as $t)
			{
				$val = call_user_func($tokenFunctions[$t], $pageid, $title);
				if($val === false)
					$this->setWarning("Action '$t' is not allowed for the current user");
				else
					$pageInfo[$t . 'token'] = $val;
			}
		}

		if($this->fld_protection) {
			$pageInfo['protection'] = array();
			if (isset($this->protections[$title->getNamespace()][$title->getDBkey()])) 
				$pageInfo['protection'] =
					$this->protections[$title->getNamespace()][$title->getDBkey()];
			$this->getResult()->setIndexedTagName($pageInfo['protection'], 'pr');
		}
		if($this->fld_talkid && isset($this->talkids[$title->getNamespace()][$title->getDBkey()]))
			$pageInfo['talkid'] = $this->talkids[$title->getNamespace()][$title->getDBkey()];
		if($this->fld_subjectid && isset($this->subjectids[$title->getNamespace()][$title->getDBkey()]))
			$pageInfo['subjectid'] = $this->subjectids[$title->getNamespace()][$title->getDBkey()];
		if($this->fld_url) {
			$pageInfo['fullurl'] = $title->getFullURL();
			$pageInfo['editurl'] = $title->getFullURL('action=edit');
		}
		if($this->fld_readable)
			if($title->userCanRead())
				$pageInfo['readable'] = '';
		return $pageInfo;
	}

	/**
	 * Get information about protections and put it in $protections
	 */
	private function getProtectionInfo()
	{
		$this->protections = array();
		$db = $this->getDB();

		// Get normal protections for existing titles
		if(count($this->titles))
		{
			$this->addTables(array('page_restrictions', 'page'));
			$this->addWhere('page_id=pr_page');
			$this->addFields(array('pr_page', 'pr_type', 'pr_level',
					'pr_expiry', 'pr_cascade', 'page_namespace',
					'page_title'));
			$this->addWhereFld('pr_page', array_keys($this->titles));

			$res = $this->select(__METHOD__);
			while($row = $db->fetchObject($res)) {
				$a = array(
					'type' => $row->pr_type,
					'level' => $row->pr_level,
					'expiry' => Block::decodeExpiry($row->pr_expiry, TS_ISO_8601)
				);
				if($row->pr_cascade)
					$a['cascade'] = '';
				$this->protections[$row->page_namespace][$row->page_title][] = $a;

				# Also check old restrictions
				if($this->pageRestrictions[$row->pr_page]) {
					$restrictions = explode(':', trim($this->pageRestrictions[$row->pr_page]));
					foreach($restrictions as $restrict) {
						$temp = explode('=', trim($restrict));
						if(count($temp) == 1) {
							// old old format should be treated as edit/move restriction
							$restriction = trim($temp[0]);

							if($restriction == '')
								continue;
							$this->protections[$row->page_namespace][$row->page_title][] = array(
								'type' => 'edit',
								'level' => $restriction,
								'expiry' => 'infinity',
							);
							$this->protections[$row->page_namespace][$row->page_title][] = array(
								'type' => 'move',
								'level' => $restriction,
								'expiry' => 'infinity',
							);
						} else {
							$restriction = trim($temp[1]);
							if($restriction == '')
								continue;
							$this->protections[$row->page_namespace][$row->page_title][] = array(
								'type' => $temp[0],
								'level' => $restriction,
								'expiry' => 'infinity',
							);
						}
					}
				}
			}
			$db->freeResult($res);
		}

		// Get protections for missing titles
		if(count($this->missing))
		{
			$this->resetQueryParams();
			$lb = new LinkBatch($this->missing);
			$this->addTables('protected_titles');
			$this->addFields(array('pt_title', 'pt_namespace', 'pt_create_perm', 'pt_expiry'));
			$this->addWhere($lb->constructSet('pt', $db));
			$res = $this->select(__METHOD__);
			while($row = $db->fetchObject($res)) {
				$this->protections[$row->pt_namespace][$row->pt_title][] = array(
					'type' => 'create',
					'level' => $row->pt_create_perm,
					'expiry' => Block::decodeExpiry($row->pt_expiry, TS_ISO_8601)
				);
			}
			$db->freeResult($res);
		}

		// Cascading protections
		$images = $others = array();
		foreach ($this->everything as $title)
			if ($title->getNamespace() == NS_FILE)
				$images[] = $title->getDBkey();
			else
				$others[] = $title;

		if (count($others)) {
			// Non-images: check templatelinks
			$lb = new LinkBatch($others);
			$this->resetQueryParams();
			$this->addTables(array('page_restrictions', 'page', 'templatelinks'));
			$this->addFields(array('pr_type', 'pr_level', 'pr_expiry',
					'page_title', 'page_namespace',
					'tl_title', 'tl_namespace'));
			$this->addWhere($lb->constructSet('tl', $db));
			$this->addWhere('pr_page = page_id');
			$this->addWhere('pr_page = tl_from');
			$this->addWhereFld('pr_cascade', 1);

			$res = $this->select(__METHOD__);
			while($row = $db->fetchObject($res)) {
				$source = Title::makeTitle($row->page_namespace, $row->page_title);
				$this->protections[$row->tl_namespace][$row->tl_title][] = array(
					'type' => $row->pr_type,
					'level' => $row->pr_level,
					'expiry' => Block::decodeExpiry($row->pr_expiry, TS_ISO_8601),
					'source' => $source->getPrefixedText()
				);
			}
			$db->freeResult($res);
		}

		if (count($images)) {
			// Images: check imagelinks
			$this->resetQueryParams();
			$this->addTables(array('page_restrictions', 'page', 'imagelinks'));
			$this->addFields(array('pr_type', 'pr_level', 'pr_expiry', 
					'page_title', 'page_namespace', 'il_to'));
			$this->addWhere('pr_page = page_id');
			$this->addWhere('pr_page = il_from');
			$this->addWhereFld('pr_cascade', 1);
			$this->addWhereFld('il_to', $images);
			
			$res = $this->select(__METHOD__);
			while($row = $db->fetchObject($res)) {
				$source = Title::makeTitle($row->page_namespace, $row->page_title);
				$this->protections[NS_FILE][$row->il_to][] = array(
					'type' => $row->pr_type,
					'level' => $row->pr_level,
					'expiry' => Block::decodeExpiry($row->pr_expiry, TS_ISO_8601),
					'source' => $source->getPrefixedText()
				);
			}
			$db->freeResult($res);
		}
	}

	/**
	 * Get talk page IDs (if requested) and subject page IDs (if requested)
	 * and put them in $talkids and $subjectids 
	 */
	private function getTSIDs()
	{
		$getTitles = $this->talkids = $this->subjectids = array();
		$db = $this->getDB();
		foreach($this->everything as $t)
		{
			if(MWNamespace::isTalk($t->getNamespace()))
			{
				if($this->fld_subjectid)
					$getTitles[] = $t->getSubjectPage();
			}
			else if($this->fld_talkid)
				$getTitles[] = $t->getTalkPage();
		}
		if(!count($getTitles))
			return;
		
		// Construct a custom WHERE clause that matches
		// all titles in $getTitles
		$lb = new LinkBatch($getTitles);
		$this->resetQueryParams();
		$this->addTables('page');
		$this->addFields(array('page_title', 'page_namespace', 'page_id'));
		$this->addWhere($lb->constructSet('page', $db));
		$res = $this->select(__METHOD__);
		while($row = $db->fetchObject($res))
		{
			if(MWNamespace::isTalk($row->page_namespace))
				$this->talkids[MWNamespace::getSubject($row->page_namespace)][$row->page_title] =
						intval($row->page_id);
			else
				$this->subjectids[MWNamespace::getTalk($row->page_namespace)][$row->page_title] =
						intval($row->page_id);
		}
	}

	public function getAllowedParams() {
		return array (
			'prop' => array (
				ApiBase :: PARAM_DFLT => NULL,
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => array (
					'protection',
					'talkid',
					'subjectid',
					'url',
					'readable',
				)),
			'token' => array (
				ApiBase :: PARAM_DFLT => NULL,
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => array_keys($this->getTokenFunctions())
			),
			'continue' => null,
		);
	}

	public function getParamDescription() {
		return array (
			'prop' => array (
				'Which additional properties to get:',
				' protection   - List the protection level of each page',
				' talkid       - The page ID of the talk page for each non-talk page',
				' subjectid    - The page ID of the parent page for each talk page'
			),
			'token' => 'Request a token to perform a data-modifying action on a page',
			'continue' => 'When more results are available, use this to continue',
		);
	}

	public function getDescription() {
		return 'Get basic page information such as namespace, title, last touched date, ...';
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&prop=info&titles=Main%20Page',
			'api.php?action=query&prop=info&inprop=protection&titles=Main%20Page'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
