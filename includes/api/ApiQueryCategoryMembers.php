<?php

/*
 * Created on June 14, 2007
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
	require_once ("ApiQueryBase.php");
}

/**
 * A query module to enumerate pages that belong to a category.
 * 
 * @addtogroup API
 */
class ApiQueryCategoryMembers extends ApiQueryGeneratorBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'cm');
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator($resultPageSet) {
		$this->run($resultPageSet);
	}

	private function run($resultPageSet = null) {

		$params = $this->extractRequestParams();

		$category = $params['category'];
		if (is_null($category))
			$this->dieUsage("Category parameter is required", 'param_category');
		$categoryTitle = Title::makeTitleSafe( NS_CATEGORY, $category );
		if ( is_null( $categoryTitle ) )
			$this->dieUsage("Category name $category is not valid", 'param_category');
		
		$prop = array_flip($params['prop']);		
		$fld_ids = isset($prop['ids']);
		$fld_title = isset($prop['title']);
		$fld_sortkey = isset($prop['sortkey']);

		if (is_null($resultPageSet)) {
			$this->addFields(array('cl_from', 'cl_sortkey', 'page_namespace', 'page_title'));
			$this->addFieldsIf('page_id', $fld_ids);
		} else {
			$this->addFields($resultPageSet->getPageTableFields()); // will include page_ id, ns, title
			$this->addFields(array('cl_from', 'cl_sortkey'));
		}
		
		$this->addTables(array('page','categorylinks'));	// must be in this order for 'USE INDEX' 
		$this->addOption('USE INDEX', 'cl_sortkey');		// Not needed after bug 10280 is applied to servers

		$this->addWhere('cl_from=page_id');
		$this->setContinuation($params['continue']);		
		$this->addWhereFld('cl_to', $categoryTitle->getDBkey());
		$this->addWhereFld('page_namespace', $params['namespace']);
		$this->addOption('ORDER BY', "cl_to, cl_sortkey, cl_from");

		$limit = $params['limit'];
		$this->addOption('LIMIT', $limit +1);

		$db = $this->getDB();

		$data = array ();
		$count = 0;
		$lastSortKey = null;
		$res = $this->select(__METHOD__);
		while ($row = $db->fetchObject($res)) {
			if (++ $count > $limit) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter('continue', $this->getContinueStr($row, $lastSortKey));
				break;
			}

			$lastSortKey = $row->cl_sortkey;	// detect duplicate sortkeys 
			
			if (is_null($resultPageSet)) {
				$title = Title :: makeTitle($row->page_namespace, $row->page_title);
				if ($title->userCanRead()) {
					$vals = array();
					if ($fld_ids)
						$vals['pageid'] = intval($row->page_id); 
					if ($fld_title) {
						$vals['ns'] = intval($title->getNamespace());
						$vals['title'] = $title->getPrefixedText();
					}
					if ($fld_sortkey)
						$vals['sortkey'] = $row->cl_sortkey;
					$data[] = $vals;
				}
			} else {
				$resultPageSet->processDbRow($row);
			}
		}
		$db->freeResult($res);

		if (is_null($resultPageSet)) {
			$this->getResult()->setIndexedTagName($data, 'cm');
			$this->getResult()->addValue('query', $this->getModuleName(), $data);
		}
	}
	
	private function getContinueStr($row, $lastSortKey) {
		$ret = $row->cl_sortkey . '|';
		if ($row->cl_sortkey == $lastSortKey)	// duplicate sort key, add cl_from
			$ret .= $row->cl_from;
		return $ret;
	}
	
	/**
	 * Add DB WHERE clause to continue previous query based on 'continue' parameter 
	 */
	private function setContinuation($continue) {
		if (is_null($continue))
			return;	// This is not a continuation request
			
		$continueList = explode('|', $continue);
		$hasError = count($continueList) != 2;
		$from = 0;
		if (!$hasError && strlen($continueList[1]) > 0) {
			$from = intval($continueList[1]);
			$hasError = ($from == 0); 
		}
		
		if ($hasError)
			$this->dieUsage("Invalid continue param. You should pass the original value returned by the previous query", "badcontinue");

		$sortKey = $this->getDB()->addQuotes($continueList[0]);

		if ($from != 0) {
			// Duplicate sort key continue
			$this->addWhere( "cl_sortkey>$sortKey OR (cl_sortkey=$sortKey AND cl_from>=$from)" );						
		} else {
			$this->addWhere( "cl_sortkey>=$sortKey" );						
		}
	}

	protected function getAllowedParams() {
		return array (
			'category' => null,
			'prop' => array (
				ApiBase :: PARAM_DFLT => 'ids|title',
				ApiBase :: PARAM_ISMULTI => true,				
				ApiBase :: PARAM_TYPE => array (
					'ids',
					'title',
					'sortkey',
				)
			),
			'namespace' => array (
				ApiBase :: PARAM_ISMULTI => true,			
				ApiBase :: PARAM_TYPE => 'namespace',
			),
			'continue' => null,
			'limit' => array (
				ApiBase :: PARAM_TYPE => 'limit',
				ApiBase :: PARAM_DFLT => 10,
				ApiBase :: PARAM_MIN => 1,
				ApiBase :: PARAM_MAX => ApiBase :: LIMIT_BIG1,
				ApiBase :: PARAM_MAX2 => ApiBase :: LIMIT_BIG2
			),
		);
	}

	protected function getParamDescription() {
		return array (
			'category' => 'Which category to enumerate (required)',
			'prop' => 'What pieces of information to include',
			'namespace' => 'Only include pages in these namespaces',
			'continue' => 'For large categories, give the value retured from previous query',
			'limit' => 'The maximum number of pages to return.',
		);
	}

	protected function getDescription() {
		return 'List all pages in a given category';
	}

	protected function getExamples() {
		return array (
				"Get first 10 pages in the categories [[Physics]]:",
				"  api.php?action=query&list=categorymembers&cmcategory=Physics",
				"Get page info about first 10 pages in the categories [[Physics]]:",
				"  api.php?action=query&generator=categorymembers&gcmcategory=Physics&prop=info",
			);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}

