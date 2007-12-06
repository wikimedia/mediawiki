<?php

/*
 * Created on July 7, 2007
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
 * Query module to enumerate links from all pages together.
 * 
 * @addtogroup API
 */
class ApiQueryAllLinks extends ApiQueryGeneratorBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'al');
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

		$prop = array_flip($params['prop']);
		$fld_ids = isset($prop['ids']);
		$fld_title = isset($prop['title']);

		if ($params['unique']) {
			if (!is_null($resultPageSet))
				$this->dieUsage($this->getModuleName() . ' cannot be used as a generator in unique links mode', 'params');
			if ($fld_ids)
				$this->dieUsage($this->getModuleName() . ' cannot return corresponding page ids in unique links mode', 'params');
			$this->addOption('DISTINCT');
		}

		$this->addTables('pagelinks');
		$this->addWhereFld('pl_namespace', $params['namespace']);
		
		if (!is_null($params['from']))
			$this->addWhere('pl_title>=' . $db->addQuotes(ApiQueryBase :: titleToKey($params['from'])));
		if (isset ($params['prefix']))
			$this->addWhere("pl_title LIKE '" . $db->escapeLike(ApiQueryBase :: titleToKey($params['prefix'])) . "%'");

		if (is_null($resultPageSet)) {
			$this->addFields(array (
				'pl_namespace',
				'pl_title'
			));
			$this->addFieldsIf('pl_from', $fld_ids);
		} else {
			$this->addFields('pl_from');
			$pageids = array();
		}

		$this->addOption('USE INDEX', 'pl_namespace');
		$limit = $params['limit'];
		$this->addOption('LIMIT', $limit+1);
		$this->addOption('ORDER BY', 'pl_namespace, pl_title');

		$res = $this->select(__METHOD__);

		$data = array ();
		$count = 0;
		while ($row = $db->fetchObject($res)) {
			if (++ $count > $limit) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				// TODO: Security issue - if the user has no right to view next title, it will still be shown
				$this->setContinueEnumParameter('from', ApiQueryBase :: keyToTitle($row->pl_title));
				break;
			}

			if (is_null($resultPageSet)) {
				$vals = array();
				if ($fld_ids)
					$vals['fromid'] = intval($row->pl_from);
				if ($fld_title) {
					$title = Title :: makeTitle($row->pl_namespace, $row->pl_title);
					$vals['ns'] = intval($title->getNamespace());
					$vals['title'] = $title->getPrefixedText();
				}
				$data[] = $vals;
			} else {
				$pageids[] = $row->pl_from;
			}
		}
		$db->freeResult($res);

		if (is_null($resultPageSet)) {
			$result = $this->getResult();
			$result->setIndexedTagName($data, 'l');
			$result->addValue('query', $this->getModuleName(), $data);
		} else {
			$resultPageSet->populateFromPageIDs($pageids);
		}
	}

	protected function getAllowedParams() {
		return array (
			'from' => null,
			'prefix' => null,
			'unique' => false,
			'prop' => array (
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_DFLT => 'title',
				ApiBase :: PARAM_TYPE => array (
					'ids',
					'title'
				)
			),
			'namespace' => array (
				ApiBase :: PARAM_DFLT => 0,
				ApiBase :: PARAM_TYPE => 'namespace'
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
			'from' => 'The page title to start enumerating from.',
			'prefix' => 'Search for all page titles that begin with this value.',
			'unique' => 'Only show unique links. Cannot be used with generator or prop=ids',
			'prop' => 'What pieces of information to include',
			'namespace' => 'The namespace to enumerate.',
			'limit' => 'How many total links to return.'
		);
	}

	protected function getDescription() {
		return 'Enumerate all links that point to a given namespace';
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&list=alllinks&alunique&alfrom=B',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
