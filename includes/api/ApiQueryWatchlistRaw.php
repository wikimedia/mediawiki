<?php

/*
 * Created on Oct 4, 2008
 *
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2008 Roan Kattouw <Firstname>.<Lastname>@home.nl
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
 * This query action allows clients to retrieve a list of pages
 * on the logged-in user's watchlist.
 *
 * @ingroup API
 */
class ApiQueryWatchlistRaw extends ApiQueryGeneratorBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'wr');
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator($resultPageSet) {
		$this->run($resultPageSet);
	}

	private function run($resultPageSet = null) {
		global $wgUser;

		$this->selectNamedDB('watchlist', DB_SLAVE, 'watchlist');

		$this->getMain()->setVaryCookie();
		if (!$wgUser->isLoggedIn())
			$this->dieUsage('You must be logged-in to have a watchlist', 'notloggedin');	
		$params = $this->extractRequestParams();
		$prop = array_flip((array)$params['prop']);
		$show = array_flip((array)$params['show']);
		if(isset($show['changed']) && isset($show['!changed']))
			$this->dieUsage("Incorrect parameter - mutually exclusive values may not be supplied", 'show');

		$this->addTables('watchlist');
		$this->addFields(array('wl_namespace', 'wl_title'));
		$this->addFieldsIf('wl_notificationtimestamp', isset($prop['changed']));
		$this->addWhereFld('wl_user', $wgUser->getId());
		$this->addWhereFld('wl_namespace', $params['namespace']);
		$this->addWhereIf('wl_notificationtimestamp IS NOT NULL', isset($show['changed']));
		$this->addWhereIf('wl_notificationtimestamp IS NULL', isset($show['!changed']));
		if(isset($params['continue']))
		{
			$cont = explode('|', $params['continue']);
			if(count($cont) != 2)
				$this->dieUsage("Invalid continue param. You should pass the " .
					"original value returned by the previous query", "_badcontinue");
			$ns = intval($cont[0]);
			$title = $this->getDB()->strencode($this->titleToKey($cont[1]));
			$this->addWhere("wl_namespace > '$ns' OR ".
					"(wl_namespace = '$ns' AND ".
					"wl_title >= '$title')");
		}
		// Don't ORDER BY wl_namespace if it's constant in the WHERE clause
		if(count($params['namespace']) == 1)
			$this->addOption('ORDER BY', 'wl_title');
		else
			$this->addOption('ORDER BY', 'wl_namespace, wl_title');
		$this->addOption('LIMIT', $params['limit'] + 1);
		$res = $this->select(__METHOD__);
		
		$db = $this->getDB();
		$titles = array();
		$count = 0;
		while($row = $db->fetchObject($res))
		{
			if(++$count > $params['limit'])
			{
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter('continue', $row->wl_namespace . '|' .
									$this->keyToTitle($row->wl_title));
				break;
			}
			$t = Title::makeTitle($row->wl_namespace, $row->wl_title);
			if(is_null($resultPageSet))
			{
				$vals = array();
				ApiQueryBase::addTitleInfo($vals, $t);
				if(isset($prop['changed']) && !is_null($row->wl_notificationtimestamp))
					$vals['changed'] = wfTimestamp(TS_ISO_8601, $row->wl_notificationtimestamp);
				$fit = $this->getResult()->addValue($this->getModuleName(), null, $vals);
				if(!$fit)
				{
					$this->setContinueEnumParameter('continue', $row->wl_namespace . '|' .
									$this->keyToTitle($row->wl_title));
					break;
				}
			}
			else
				$titles[] = $t;
		}
		if(is_null($resultPageSet))
			$this->getResult()->setIndexedTagName_internal($this->getModuleName(), 'wr');
		else
			$resultPageSet->populateFromTitles($titles);
	}

	public function getAllowedParams() {
		return array (
			'continue' => null,
			'namespace' => array (
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => 'namespace'
			),
			'limit' => array (
				ApiBase :: PARAM_DFLT => 10,
				ApiBase :: PARAM_TYPE => 'limit',
				ApiBase :: PARAM_MIN => 1,
				ApiBase :: PARAM_MAX => ApiBase :: LIMIT_BIG1,
				ApiBase :: PARAM_MAX2 => ApiBase :: LIMIT_BIG2
			),
			'prop' => array (
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => array (
					'changed',
				)
			),
			'show' => array (
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => array (
					'changed',
					'!changed',
				)
			)
		);
	}

	public function getParamDescription() {
		return array (
			'continue' => 'When more results are available, use this to continue',
			'namespace' => 'Only list pages in the given namespace(s).',
			'limit' => 'How many total results to return per request.',
			'prop' => 'Which additional properties to get (non-generator mode only).',
			'show' => 'Only list items that meet these criteria.',
		);
	}

	public function getDescription() {
		return "Get all pages on the logged in user's watchlist";
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&list=watchlistraw',
			'api.php?action=query&generator=watchlistraw&gwrshow=changed&prop=revisions',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
