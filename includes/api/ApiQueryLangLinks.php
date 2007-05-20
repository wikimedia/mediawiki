<?php

/*
 * Created on May 13, 2007
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
 * A query module to list all langlinks (links to correspanding foreign language pages).
 * 
 * @addtogroup API
 */
class ApiQueryLangLinks extends ApiQueryBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'll');
	}

	public function execute() {
		$this->addFields(array (
			'll_from',
			'll_lang',
			'll_title'
		));

		$this->addTables('langlinks');
		$this->addWhereFld('ll_from', array_keys($this->getPageSet()->getGoodTitles()));
		$this->addOption('ORDER BY', "ll_from, ll_lang");
		$res = $this->select(__METHOD__);

		$data = array();
		$lastId = 0;	// database has no ID 0	
		$db = $this->getDB();
		while ($row = $db->fetchObject($res)) {

			if ($lastId != $row->ll_from) {
				if($lastId != 0) {
					$this->addPageSubItems($lastId, $data);
					$data = array();
				}
				$lastId = $row->ll_from;
			}

			$entry = array('lang'=>$row->ll_lang);
			ApiResult :: setContent($entry, $row->ll_title);
			$data[] = $entry;
		}

		if($lastId != 0) {
			$this->addPageSubItems($lastId, $data);
		}

		$db->freeResult($res);
	}

	private function addPageSubItems($pageId, $data) {
		$result = $this->getResult();
		$result->setIndexedTagName($data, 'll');
		$result->addValue(array ('query', 'pages', intval($pageId)),
			'langlinks',
			$data);
	}

	protected function getDescription() {
		return 'Returns all interlanguage links from the given page(s)';
	}

	protected function getExamples() {
		return array (
				"Get interlanguage links from the [[Main Page]]:",
				"  api.php?action=query&prop=langlinks&titles=Main%20Page&redirects",
			);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
?>
