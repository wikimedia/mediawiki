<?php

/*
 * Created on Monday, January 28, 2008
 *
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2008 Brent Garber
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
 * Query module to get list of random pages
 * 
 * @addtogroup API
 */
 
 class ApiQueryRandom extends ApiQueryGeneratorBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'rn');
	}
	
	public function execute() {
		$this->run();
	}
	
	public function executeGenerator($resultPageSet) {
		$this->run($resultPageSet);
	}

	public function run($resultPageSet = null) {
		$params = $this->extractRequestParams();
		$result = $this->getResult();
		$randstr = wfRandom();
		$data = array();

		$this->addTables('page');
		$this->addOption('LIMIT', $params['limit']);
		$this->addWhereFld('page_namespace', $params['namespace']);
		$this->addWhereRange('page_random', 'newer', $randstr, null);
		$this->addWhere(array('page_is_redirect' => 0));
		$this->addOption('USE INDEX', 'page_random');
		if(is_null($resultPageSet))
			$this->addFields(array('page_id', 'page_title', 'page_namespace'));
		else
			$this->addFields($resultPageSet->getPageTableFields());

		$db = $this->getDB();
		$res = $this->select(__METHOD__);
		while($row = $db->fetchObject($res)) {
			if(is_null($resultPageSet))
				$data[] = $this->extractRowInfo($row);
			else
				$resultPageSet->processDbRow($row);
		}
		$db->freeResult($res);

		if(is_null($resultPageSet)) {
			$result->setIndexedTagName($data, 'page');
			$result->addValue('query', $this->getModuleName(), $data);
		}		
	}

	private function extractRowInfo($row) {
		$title = Title::makeTitle($row->page_namespace, $row->page_title);
		$vals = array();
		$vals['title'] = $title->getPrefixedText();
		$vals['ns'] = $row->page_namespace;
		$vals['id'] = $row->page_id;
		return $vals;
	}
		
	protected function getAllowedParams() {
		return array (
			'namespace' => array(
				ApiBase :: PARAM_TYPE => 'namespace',
				ApiBase :: PARAM_ISMULTI => true
			),
			'limit' => array (
				ApiBase :: PARAM_TYPE => 'limit',
				ApiBase :: PARAM_DFLT => 1,
				ApiBase :: PARAM_MIN => 1,
				ApiBase :: PARAM_MAX => 10,
				ApiBase :: PARAM_MAX2 => 20
			),
		);
	}

	protected function getParamDescription() {
		return array (
			'namespace' => 'Return pages in these namespaces only',
			'limit' => 'Limit how many random pages will be returned'
		);
	}

	protected function getDescription() {
		return array(	'Get a set of random pages',
				'NOTE: When using a namespace filter, this module may return an empty result. In that case, retry the request'
		);
	}

	protected function getExamples() {
		return 'api.php?action=query&list=random&rnnamespace=0&rnlimit=2';
	}

	public function getVersion() {
		return __CLASS__ . ': $Id: ApiQueryRandom.php overlordq$';
	}
}
