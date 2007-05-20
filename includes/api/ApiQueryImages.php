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
class ApiQueryImages extends ApiQueryGeneratorBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'im');
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator($resultPageSet) {
		$this->run($resultPageSet);
	}

	private function run($resultPageSet = null) {

		if ($this->getPageSet()->getGoodTitleCount() == 0)
			return;	// nothing to do

		$this->addFields(array (
			'il_from',
			'il_to'
		));

		$this->addTables('imagelinks');
		$this->addWhereFld('il_from', array_keys($this->getPageSet()->getGoodTitles()));
		$this->addOption('ORDER BY', "il_from, il_to");

		$db = $this->getDB();
		$res = $this->select(__METHOD__);

		if (is_null($resultPageSet)) {
			
			$data = array();
			$lastId = 0;	// database has no ID 0	
			while ($row = $db->fetchObject($res)) {
				if ($lastId != $row->il_from) {
					if($lastId != 0) {
						$this->addPageSubItems($lastId, $data);
						$data = array();
					}
					$lastId = $row->il_from;
				}
				
				$title = Title :: makeTitle(NS_IMAGE, $row->il_to);
				// do not check userCanRead() -- page content is already accessible,
				// and images are listed there.

				$vals = array();
				ApiQueryBase :: addTitleInfo($vals, $title);
				$data[] = $vals;
			}

			if($lastId != 0) {
				$this->addPageSubItems($lastId, $data);
			}

		} else {

			$titles = array();
			while ($row = $db->fetchObject($res)) {
				$titles[] = Title :: makeTitle(NS_IMAGE, $row->il_to);
			}
			$resultPageSet->populateFromTitles($titles);
		}

		$db->freeResult($res);
	}

	private function addPageSubItems($pageId, $data) {
		$result = $this->getResult();
		$result->setIndexedTagName($data, 'im');
		$result->addValue(array ('query', 'pages', intval($pageId)),
			'images',
			$data);
	}

	protected function getDescription() {
		return 'Returns all links from the given page(s)';
	}

	protected function getExamples() {
		return array (
				"Get a list of images used in the [[Main Page]]:",
				"  api.php?action=query&prop=images&titles=Main%20Page",
				"Get information about all images used in the [[Main Page]]:",
				"  api.php?action=query&generator=images&titles=Main%20Page&prop=info"
			);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
?>
