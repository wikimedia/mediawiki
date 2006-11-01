<?php


/*
 * Created on Oct 16, 2006
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

class ApiQueryLogEvents extends ApiQueryBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'le');
	}

	public function execute() {
		$limit = $type = $start = $end = $dir = $user = $title = null;
		extract($this->extractRequestParams());

		$db = & $this->getDB();

		extract($db->tableNames('logging', 'page', 'user'), EXTR_PREFIX_ALL, 'tbl');

		$this->addOption('STRAIGHT_JOIN');
		$this->addTables("$tbl_logging LEFT OUTER JOIN $tbl_page ON " .
		"log_namespace=page_namespace AND log_title=page_title " .
		"INNER JOIN $tbl_user ON user_id=log_user");

		$this->addFields(array (
			'log_type',
			'log_action',
			'log_timestamp',
			'log_user',
			'user_name',
			'log_namespace',
			'log_title',
			'page_id',
			'log_comment',
			'log_params'
		));

		$this->addWhereFld('log_type', $type);
		$this->addWhereRange('log_timestamp', $dir, $start, $end);
		$this->addOption('LIMIT', $limit +1);

		if (!is_null($user)) {
			$userid = $db->selectField('user', 'user_id', array (
				'user_name' => $user
			));
			if (!$userid)
				$this->dieUsage("User name $user not found", 'param_user');
			$this->addWhereFld('log_user', $userid);
		}

		if (!is_null($title)) {
			$titleObj = Title :: newFromText($title);
			if (is_null($titleObj))
				$this->dieUsage("Bad title value '$title'", 'param_title');
			$this->addWhereFld('log_namespace', $titleObj->getNamespace());
			$this->addWhereFld('log_title', $titleObj->getDBkey());
		}

		$data = array ();
		$count = 0;
		$res = $this->select(__METHOD__);
		while ($row = $db->fetchObject($res)) {
			if (++ $count > $limit) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter('start', $row->log_timestamp);
				break;
			}

			$vals = $this->addRowInfo('log', $row);
			if($vals)
				$data[] = $vals;
		}
		$db->freeResult($res);

		$this->getResult()->setIndexedTagName($data, 'item');
		$this->getResult()->addValue('query', $this->getModuleName(), $data);
	}

	protected function getAllowedParams() {
		return array (
			'limit' => array (
				ApiBase :: PARAM_DFLT => 10,
				ApiBase :: PARAM_TYPE => 'limit',
				ApiBase :: PARAM_MIN => 1,
				ApiBase :: PARAM_MAX1 => ApiBase :: LIMIT_BIG1,
				ApiBase :: PARAM_MAX2 => ApiBase :: LIMIT_BIG2
			),
			'type' => array (
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => array (
					'block',
					'protect',
					'rights',
					'delete',
					'upload',
					'move',
					'import',
					'renameuser',
					'newusers',
					'makebot'
				)
			),
			'start' => array (
				ApiBase :: PARAM_TYPE => 'timestamp'
			),
			'end' => array (
				ApiBase :: PARAM_TYPE => 'timestamp'
			),
			'dir' => array (
				ApiBase :: PARAM_DFLT => 'older',
				ApiBase :: PARAM_TYPE => array (
					'newer',
					'older'
				)
			),
			'user' => null,
			'title' => null
		);
	}

	protected function getParamDescription() {
		return array (
			'limit' => '',
			'type' => '',
			'start' => '',
			'end' => '',
			'dir' => '',
			'user' => '',
			'title' => ''
		);
	}

	protected function getDescription() {
		return 'Get events from logs.';
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&list=logevents'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
?>
