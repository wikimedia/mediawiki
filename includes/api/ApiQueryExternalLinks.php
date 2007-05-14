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
class ApiQueryExternalLinks extends ApiQueryBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'el');
	}

	public function execute() {

		$this->addFields(array (
			'el_from',
			'el_to'
		));
		
		$this->addTables('externallinks');
		$this->addWhereFld('el_from', array_keys($this->getPageSet()->getGoodTitles()));

		$db = $this->getDB();
		$res = $this->select(__METHOD__);
			
		$data = array();
		$lastId = 0;	// database has no ID 0	
		while ($row = $db->fetchObject($res)) {
			if ($lastId != $row->el_from) {
				if($lastId != 0) {
					$this->addPageSubItems($lastId, $data);
					$data = array();
				}
				$lastId = $row->el_from;
			}
			
			$entry = array();
			ApiResult :: setContent($entry, $row->el_to);
			$data[] = $entry;
		}

		if($lastId != 0) {
			$this->addPageSubItems($lastId, $data);
		}

		$db->freeResult($res);
	}

	private function addPageSubItems($pageId, $data) {
		$result = $this->getResult();
		$result->setIndexedTagName($data, 'el');
		$result->addValue(array ('query', 'pages', intval($pageId)),
			'extlinks',
			$data);
	}

	protected function getDescription() {
		return 'Returns all external urls (not interwikies) from the given page(s)';
	}

	protected function getExamples() {
		return array (
				"Get a list of external links on the [[Main Page]]:",
				"  api.php?action=query&prop=extlinks&titles=Main%20Page",
			);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
?>
