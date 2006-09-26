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
	require_once ("ApiQueryBase.php");
}

class ApiQueryAllpages extends ApiQueryBase {

	public function __construct($query, $moduleName, $generator = false) {
		parent :: __construct($query, $moduleName, $generator);
	}

	public function Execute() {
		$aplimit = $apfrom = $apnamespace = $apfilterredir = null;
		extract($this->ExtractRequestParams());

		$db = $this->GetDB();
		$where = array (
			'page_namespace' => $apnamespace
		);
		if (isset ($apfrom))
			$where[] = 'page_title>=' . $db->addQuotes(ApiQueryBase :: TitleToKey($apfrom));

		if ($apfilterredir === 'redirects')
			$where['page_is_redirect'] = 1;
		else
			if ($apfilterredir === 'nonredirects')
				$where['page_is_redirect'] = 0;

		$res = $db->select('page', array (
			'page_id',
			'page_namespace',
			'page_title'
		), $where, __CLASS__ . '::' . __METHOD__, array (
			'USE INDEX' => 'name_title',
			'LIMIT' => $aplimit +1,
			'ORDER BY' => 'page_namespace, page_title'
		));

		$data = array ();
		$data['_element'] = 'p';
		$count = 0;
		while ($row = $db->fetchObject($res)) {
			if (++ $count > $aplimit) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$msg = array (
					'continue' => 'apfrom=' . ApiQueryBase :: KeyToTitle($row->page_title
				));
				$this->GetResult()->AddMessage('query-status', 'allpages', $msg);
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
				$pagedata['*'] = '';

				$data[$id] = $pagedata;
			}
		}
		$db->freeResult($res);
		$this->GetResult()->AddMessage('query', 'allpages', $data);
	}

	protected function GetAllowedParams() {

		global $wgContLang;
		$validNamespaces = array ();
		foreach (array_keys($wgContLang->getNamespaces()) as $ns) {
			if ($ns >= 0)
				$validNamespaces[] = $ns; // strval($ns);		
		}

		return array (
			'apfrom' => null,
			'apnamespace' => array (
				GN_ENUM_DFLT => 0,
				GN_ENUM_TYPE => $validNamespaces
			),
			'apfilterredir' => array (
				GN_ENUM_DFLT => 'all',
				GN_ENUM_TYPE => array (
					'all',
					'redirects',
					'nonredirects'
				)
			),
			'aplimit' => array (
				GN_ENUM_DFLT => 10,
				GN_ENUM_TYPE => 'limit',
				GN_ENUM_MIN => 1,
				GN_ENUM_MAX1 => 500,
				GN_ENUM_MAX2 => 5000
			)
		);
	}

	protected function GetParamDescription() {
		return array ();
	}

	protected function GetDescription() {
		return 'Enumerate all pages sequentially in a given namespace';
	}

	protected function GetExamples() {
		return array (
			'api.php?action=query&list=allpages',
			'api.php?action=query&list=allpages&apfrom=B&aplimit=5'
		);
	}
	public function GetCanGenerate() {
		return true;
	}
}
?>