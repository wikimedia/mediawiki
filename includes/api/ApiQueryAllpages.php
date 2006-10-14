<?php


/*
 * Created on Sep 25, 2006
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
	require_once ('ApiQueryBase.php');
}

class ApiQueryAllpages extends ApiQueryGeneratorBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'ap');
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator($resultPageSet) {
		if ($resultPageSet->isResolvingRedirects())
			$this->dieUsage('Use "gapfilterredir=nonredirects" option instead of "redirects" when using allpages as a generator', 'params');

		$this->run($resultPageSet);
	}

	private function run($resultPageSet = null) {
		$limit = $from = $namespace = $filterredir = null;
		extract($this->extractRequestParams());

		$db = $this->getDB();

		$where = array (
			'page_namespace' => $namespace
		);

		if (isset ($from)) {
			$where[] = 'page_title>=' . $db->addQuotes(ApiQueryBase :: titleToKey($from));
		}
		
		if (isset ($prefix)) {
			$where[] = "page_title LIKE '{$db->strencode(ApiQueryBase :: titleToKey($prefix))}%'";
		}

		if ($filterredir === 'redirects') {
			$where['page_is_redirect'] = 1;
		}
		elseif ($filterredir === 'nonredirects') {
			$where['page_is_redirect'] = 0;
		}

		if (is_null($resultPageSet)) {
			$fields = array (
				'page_id',
				'page_namespace',
				'page_title'
			);
		} else {
			$fields = $resultPageSet->getPageTableFields();
		}

		$options = array (
			'USE INDEX' => 'name_title',
			'LIMIT' => $limit +1,
			'ORDER BY' => 'page_namespace, page_title'
		);

		$this->profileDBIn();
		$res = $db->select('page', $fields, $where, __METHOD__, $options);
		$this->profileDBOut();

		$data = array ();
		$count = 0;
		while ($row = $db->fetchObject($res)) {
			if (++ $count > $limit) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$msg = array (
					'continue' => $this->encodeParamName('from'
				) . '=' . ApiQueryBase :: keyToTitle($row->page_title));
				$this->getResult()->addValue('query-status', 'allpages', $msg);
				break;
			}

			$title = Title :: makeTitle($row->page_namespace, $row->page_title);
			// skip any pages that user has no rights to read
			if ($title->userCanRead()) {

				if (is_null($resultPageSet)) {
					$id = intval($row->page_id);
					$data[$id] = array (
						'id' => $id,
						'ns' => $title->getNamespace(),
						'title' => $title->getPrefixedText());
				} else {
					$resultPageSet->processDbRow($row);
				}
			}
		}
		$db->freeResult($res);

		if (is_null($resultPageSet)) {
			ApiResult :: setIndexedTagName($data, 'p');
			$this->getResult()->addValue('query', 'allpages', $data);
		}
	}

	protected function getAllowedParams() {

		return array (
			'from' => null,
			'prefix' => null,
			'namespace' => array (
				ApiBase :: PARAM_DFLT => 0,
				ApiBase :: PARAM_TYPE => $this->getQuery()->getValidNamespaces()),
			'filterredir' => array (
				ApiBase :: PARAM_DFLT => 'all',
				ApiBase :: PARAM_TYPE => array (
					'all',
					'redirects',
					'nonredirects'
				)),
			'limit' => array (
				ApiBase :: PARAM_DFLT => 10,
				ApiBase :: PARAM_TYPE => 'limit',
				ApiBase :: PARAM_MIN => 1,
				ApiBase :: PARAM_MAX1 => 500,
				ApiBase :: PARAM_MAX2 => 5000
		));
	}

	protected function getParamDescription() {
		return array (
			'from' => 'The page title to start enumerating from.',
			'prefix' => 'Search for all page titles that begin with this value.',
			'namespace' => 'The namespace to enumerate. Default 0 (Main).',
			'filterredir' => 'Which pages to list: "all" (default), "redirects", or "nonredirects"',
			'limit' => 'How many total pages to return'
		);
	}

	protected function getDescription() {
		return 'Enumerate all pages sequentially in a given namespace';
	}

	protected function getExamples() {
		return array (
			'Simple Use',
			' Show a list of pages starting at the letter "B"',
			'  api.php?action=query&list=allpages&apfrom=B',
			'Using as Generator',
			' Show info about 4 pages starting at the letter "T"',
			'  api.php?action=query&generator=allpages&gaplimit=4&gapfrom=T&prop=info',
			' Show content of first 2 non-redirect pages begining at "Re"',
			'  api.php?action=query&generator=allpages&gaplimit=2&gapfilterredir=nonredirects&gapfrom=Re&prop=revisions&rvprop=content'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
?>