<?php

/*
 * Created on December 12, 2007
 *
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2007 Roan Kattouw <Firstname>.<Lastname>@home.nl
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
 * Query module to enumerate all categories, even the ones that don't have
 * category pages.
 * 
 * @addtogroup API
 */
class ApiQueryAllCategories extends ApiQueryGeneratorBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'ac');
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator($resultPageSet) {
		$this->run($resultPageSet);
	}

	private function run($resultPageSet = null) {

		$db = $this->getDB();
		$params = $this->extractRequestParams();

		$this->addTables('categorylinks');
		$this->addFields('cl_to');
		
		if (!is_null($params['from']))
			$this->addWhere('cl_to>=' . $db->addQuotes(ApiQueryBase :: titleToKey($params['from'])));
		if (isset ($params['prefix']))
			$this->addWhere("cl_to LIKE '" . $db->escapeLike(ApiQueryBase :: titleToKey($params['prefix'])) . "%'");

		$this->addOption('LIMIT', $params['limit']+1);
		$this->addOption('ORDER BY', 'cl_to' . ($params['dir'] == 'ZtoA' ? ' DESC' : ''));
		$this->addOption('DISTINCT');

		$res = $this->select(__METHOD__);

		$pages = array();
		$count = 0;
		while ($row = $db->fetchObject($res)) {
			if (++ $count > $params['limit']) {
				// We've reached the one extra which shows that there are additional cats to be had. Stop here...
				// TODO: Security issue - if the user has no right to view next title, it will still be shown
				$this->setContinueEnumParameter('from', ApiQueryBase :: keyToTitle($row->cl_to));
				break;
			}
			
			// Normalize titles
			$titleObj = Title::makeTitle(NS_CATEGORY, $row->cl_to);
			if(!is_null($resultPageSet))
				$pages[] = $titleObj->getPrefixedText();
			else
				// Don't show "Category:" everywhere in non-generator mode
				$pages[] = $titleObj->getText();
		}
		$db->freeResult($res);

		if (is_null($resultPageSet)) {
			$result = $this->getResult();
			$result->setIndexedTagName($pages, 'c');
			$result->addValue('query', $this->getModuleName(), $pages);
		} else {
			$resultPageSet->populateFromTitles($pages);
		}
	}

	protected function getAllowedParams() {
		return array (
			'from' => null,
			'prefix' => null,
			'dir' => array(
				ApiBase :: PARAM_DFLT => 'ascending',
				ApiBase :: PARAM_TYPE => array(
					'ascending',
					'descending'
				),
			),
			'limit' => array (
				ApiBase :: PARAM_DFLT => 10,
				ApiBase :: PARAM_TYPE => 'limit',
				ApiBase :: PARAM_MIN => 1,
				ApiBase :: PARAM_MAX => ApiBase :: LIMIT_BIG1,
				ApiBase :: PARAM_MAX2 => ApiBase :: LIMIT_BIG2
			)
		);
	}

	protected function getParamDescription() {
		return array (
			'from' => 'The category to start enumerating from.',
			'prefix' => 'Search for all category titles that begin with this value.',
			'dir' => 'Direction to sort in.',
			'limit' => 'How many categories to return.'
		);
	}

	protected function getDescription() {
		return 'Enumerate all categories';
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&generator=allcategories&gacprefix=List&prop=info',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id: ApiQueryAllLinks.php 28216 2007-12-06 18:33:18Z vasilievvv $';
	}
}
