<?php

/*
 * Created on May 12, 2007
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
class ApiQueryLinks extends ApiQueryGeneratorBase {

	const LINKS = 'links';
	const TEMPLATES = 'templates';

	private $table, $prefix, $description;

	public function __construct($query, $moduleName) {
		
		switch ($moduleName) {
			case self::LINKS :
				$this->table = 'pagelinks';
				$this->prefix = 'pl';
				$this->description = 'link';
				break;
			case self::TEMPLATES :
				$this->table = 'templatelinks';
				$this->prefix = 'tl';
				$this->description = 'template';
				break;
			default :
				ApiBase :: dieDebug(__METHOD__, 'Unknown module name');
		}

		parent :: __construct($query, $moduleName, $this->prefix);
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator($resultPageSet) {
		$this->run($resultPageSet);
	}

	private function run($resultPageSet = null) {

		$this->addFields(array (
			$this->prefix . '_from pl_from',
			$this->prefix . '_namespace pl_namespace',
			$this->prefix . '_title pl_title'
		));

		$this->addTables($this->table);
		$this->addWhereFld($this->prefix . '_from', array_keys($this->getPageSet()->getGoodTitles()));
		$this->addOption('ORDER BY', str_replace('pl_', $this->prefix . '_', 'pl_from, pl_namespace, pl_title'));

		$db = $this->getDB();
		$res = $this->select(__METHOD__);

		if (is_null($resultPageSet)) {
			
			$data = array();
			$lastId = 0;	// database has no ID 0	
			while ($row = $db->fetchObject($res)) {
				if ($lastId != $row->pl_from) {
					if($lastId != 0) {
						$this->addPageSubItems($lastId, $data);
						$data = array();
					}
					$lastId = $row->pl_from;
				}
				$vals = $this->addRowInfo('pl', $row);
				if ($vals)
					$data[] = $vals;
			}

			if($lastId != 0) {
				$this->addPageSubItems($lastId, $data);
			}

		} else {

			$titles = array();
			while ($row = $db->fetchObject($res)) {
				$titles[] = Title :: makeTitle($row->pl_namespace, $row->pl_title);
			}
			$resultPageSet->populateFromTitles($titles);
		}

		$db->freeResult($res);
	}

	private function addPageSubItems($pageId, $data) {
		$result = $this->getResult();
		$result->setIndexedTagName($data, $this->prefix);
		$result->addValue(array ('query', 'pages', intval($pageId)),
			$this->getModuleName(),
			$data);
	}

	protected function getDescription() {
		return "Returns all {$this->description}s from the given page(s)";
	}

	protected function getExamples() {
		return array (
				"Get {$this->description}s from the [[Main Page]]:",
				"  api.php?action=query&prop={$this->getModuleName()}&titles=Main%20Page",
				"Get information about the {$this->description} pages in the [[Main Page]]:",
				"  api.php?action=query&generator={$this->getModuleName()}&titles=Main%20Page&prop=info"
			);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
?>
