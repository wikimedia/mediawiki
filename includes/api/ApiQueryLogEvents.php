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
		$limit = $type = $from = $to = $dir = $user = $title = $namespace = null;
		extract($this->extractRequestParams());

		$db = $this->getDB();

		extract($db->tableNames('logging', 'page', 'user'), EXTR_PREFIX_ALL, 'tbl');

		$tables = "$tbl_logging LEFT OUTER JOIN $tbl_page ON log_namespace=page_namespace AND log_title=page_title " .
		"INNER JOIN $tbl_user ON user_id=log_user";

		$fields = array (
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
		);

		$where = array ();
		if (!is_null($type))
			$where['log_type'] = $type;

		if (!is_null($user)) {
			$userid = $db->selectField('user', 'user_id', array (
				'user_name' => $user
			));
			if (!$userid)
				$this->dieUsage("User name $user not found", 'param_user');
			$where['log_user'] = $userid;
		}

		if (!is_null($title)) {
			$titleObj = Title :: newFromText($title);
			if (is_null($titleObj))
				$this->dieUsage("Bad title value '$title'", 'param_title');
			$where['log_namespace'] = $titleObj->getNamespace();
			$where['log_title'] = $titleObj->getDBkey();
		}

		//		$where[] = "log_timestamp $direction '$safetime'";

		$options = array (
			'LIMIT' => $limit +1
		);

		$this->profileDBIn();
		$res = $db->select($tables, $fields, $where, __METHOD__, $options);
		$this->profileDBOut();

		$data = array ();
		$count = 0;
		while ($row = $db->fetchObject($res)) {
			if (++ $count > $limit) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter('from', ApiQueryBase :: keyToTitle($row->page_title));
				break;
			}

			$vals = array (
				'type' => $row->log_type,
				'action' => $row->log_action,
				'timestamp' => $row->log_timestamp,
				'comment' => $row->log_comment,
				'params' => $row->log_params,
				'pageid' => intval($row->page_id)
			);
			
			$title = Title :: makeTitle($row->log_namespace, $row->log_title);
			$vals['ns'] = $title->getNamespace();
			$vals['title'] = $title->getPrefixedText();

			if (!$row->log_user)
				$vals['anon'] = '';
			$vals['user'] = $row->user_name;
			
			$data[] = $vals;
		}
		$db->freeResult($res);

		ApiResult :: setIndexedTagName($data, 'item');
		$this->getResult()->addValue('query', $this->getModuleName(), $data);
	}

	protected function getAllowedParams() {

		return array (
			'limit' => array (
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
			'limit' => 'How many total items to return.'
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
		return __CLASS__ . ': $Id:$';
	}
}
?>