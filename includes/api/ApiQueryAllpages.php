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

class ApiQueryAllpages extends ApiQueryBase {

	public function __construct($query, $moduleName, $generator = false) {
		parent :: __construct($query, $moduleName, $generator);
	}

	public function execute() {
		$aplimit = $apfrom = $apnamespace = $apfilterredir = null;
		extract($this->extractRequestParams());

		$db = $this->getDB();
		$where = array (
			'page_namespace' => $apnamespace
		);
		if (isset ($apfrom))
			$where[] = 'page_title>=' . $db->addQuotes(ApiQueryBase :: titleToKey($apfrom));

		if ($apfilterredir === 'redirects')
			$where['page_is_redirect'] = 1;
		elseif ($apfilterredir === 'nonredirects') $where['page_is_redirect'] = 0;

		$this->profileDBIn();
		$res = $db->select('page', array (
			'page_id',
			'page_namespace',
			'page_title'
		), $where, __CLASS__ . '::' . __METHOD__, array (
			'USE INDEX' => 'name_title',
			'LIMIT' => $aplimit +1,
			'ORDER BY' => 'page_namespace, page_title'
		));
		$this->profileDBOut();

		$data = array ();
		ApiResult :: setIndexedTagName($data, 'p');
		$count = 0;
		while ($row = $db->fetchObject($res)) {
			if (++ $count > $aplimit) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$msg = array (
					'continue' => 'apfrom=' . ApiQueryBase :: keyToTitle($row->page_title
				));
				$this->getResult()->addValue('query-status', 'allpages', $msg);
				break;
			}

			$title = Title :: makeTitle($row->page_namespace, $row->page_title);
			// skip any pages that user has no rights to read
			if ($title->userCanRead()) {

				$id = intval($row->page_id);
				$pagedata = array ();
				$pagedata['id'] = $id;
				if ($title->getNamespace() !== 0)
					$pagedata['ns'] = $title->getNamespace();
				$pagedata['title'] = $title->getPrefixedText();

				$data[$id] = $pagedata;
			}
		}
		$db->freeResult($res);
		$this->getResult()->addValue('query', 'allpages', $data);
	}

	protected function getAllowedParams() {

		global $wgContLang;
		$validNamespaces = array ();
		foreach (array_keys($wgContLang->getNamespaces()) as $ns) {
			if ($ns >= 0)
				$validNamespaces[] = $ns; // strval($ns);		
		}

		return array (
			'apfrom' => null,
			'apnamespace' => array (
				ApiBase :: PARAM_DFLT => 0,
				ApiBase :: PARAM_TYPE => $validNamespaces
			),
			'apfilterredir' => array (
				ApiBase :: PARAM_DFLT => 'all',
				ApiBase :: PARAM_TYPE => array (
					'all',
					'redirects',
					'nonredirects'
				)
			),
			'aplimit' => array (
				ApiBase :: PARAM_DFLT => 10,
				ApiBase :: PARAM_TYPE => 'limit',
				ApiBase :: PARAM_MIN => 1,
				ApiBase :: PARAM_MAX1 => 500,
				ApiBase :: PARAM_MAX2 => 5000
			)
		);
	}

	protected function getParamDescription() {
		return array (
			'apfrom' => 'The page title to start enumerating from.',
			'apnamespace' => 'The namespace to enumerate. Default 0 (Main).',
			'apfilterredir' => 'Which pages to list: "all" (default), "redirects", or "nonredirects"',
			'aplimit' => 'How many total pages to return'
		);
	}

	protected function getDescription() {
		return 'Enumerate all pages sequentially in a given namespace';
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&list=allpages',
			'api.php?action=query&list=allpages&apfrom=B&aplimit=5'
		);
	}

	public function getCanGenerate() {
		return true;
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
?>