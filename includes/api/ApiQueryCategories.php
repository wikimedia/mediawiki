<?php

/*
 * Created on May 13, 2007
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
	require_once ("ApiQueryBase.php");
}

/**
 * @addtogroup API
 */
class ApiQueryCategories extends ApiQueryGeneratorBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'cl');
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator($resultPageSet) {
		$this->run($resultPageSet);
	}

	private function run($resultPageSet = null) {

		$params = $this->extractRequestParams();
		$prop = $params['prop'];

		$this->addFields(array (
			'cl_from',
			'cl_to'
		));
		
		$fld_sortkey = false;
		if (!is_null($prop)) {
			foreach($prop as $p) {
				switch ($p) {
					case 'sortkey':
						$this->addFields('cl_sortkey');
						$fld_sortkey = true;
						break;
					default :
						ApiBase :: dieDebug(__METHOD__, "Unknown prop=$p");
				}
			}
		}
		
		$this->addTables('categorylinks');
		$this->addWhereFld('cl_from', array_keys($this->getPageSet()->getGoodTitles()));
		$this->addOption('ORDER BY', "cl_from, cl_to");

		$db = $this->getDB();
		$res = $this->select(__METHOD__);

		if (is_null($resultPageSet)) {
			
			$data = array();
			$lastId = 0;	// database has no ID 0	
			while ($row = $db->fetchObject($res)) {
				if ($lastId != $row->cl_from) {
					if($lastId != 0) {
						$this->addPageSubItems($lastId, $data);
						$data = array();
					}
					$lastId = $row->cl_from;
				}
				
				$title = Title :: makeTitle(NS_CATEGORY, $row->cl_to);
				$vals = array();
				ApiQueryBase :: addTitleInfo($vals, $title);
				if ($fld_sortkey)
					$vals['sortkey'] = $row->cl_sortkey;
				$data[] = $vals;
			}

			if($lastId != 0) {
				$this->addPageSubItems($lastId, $data);
			}

		} else {

			$titles = array();
			while ($row = $db->fetchObject($res)) {
				$titles[] = Title :: makeTitle(NS_CATEGORY, $row->cl_to);
			}
			$resultPageSet->populateFromTitles($titles);
		}

		$db->freeResult($res);
	}

	private function addPageSubItems($pageId, $data) {
		$result = $this->getResult();
		$result->setIndexedTagName($data, 'cl');
		$result->addValue(array ('query', 'pages', intval($pageId)),
			'categories',
			$data);
	}

	protected function getAllowedParams() {
		return array (
			'prop' => array (
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => array (
					'sortkey',
				)
			)
		);
	}

	protected function getParamDescription() {
		return array (
			'prop' => 'Which additional properties to get for each category.',
		);
	}

	protected function getDescription() {
		return 'Returns all links from the given page(s)';
	}

	protected function getExamples() {
		return array (
				"Get a list of categories used in the [[Main Page]]:",
				"  api.php?action=query&prop=categories&titles=Albert%20Einstein",
				"Get information about all categories used in the [[Main Page]]:",
				"  api.php?action=query&generator=categories&titles=Albert%20Einstein&prop=info"
			);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
?>
