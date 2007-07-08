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
 * Query module to enumerate all registered users.
 * 
 * @addtogroup API
 */
class ApiQueryAllUsers extends ApiQueryBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'au');
	}

	public function execute() {
		$db = $this->getDB();
		$params = $this->extractRequestParams();

		$prop = $params['prop'];
		if (!is_null($prop)) {
			$prop = array_flip($prop);
			$fld_editcount = isset($prop['editcount']);
		} else {
			$fld_editcount = false;
		}

		$this->addTables('user');
		
		if (!is_null($params['from']))
			$this->addWhere('user_name>=' . $db->addQuotes(ApiQueryBase :: titleToKey($params['from'])));

		$this->addFields('user_name');
		$this->addFieldsIf('user_editcount', $fld_editcount);

		$limit = $params['limit'];
		$this->addOption('LIMIT', $limit+1);
		$this->addOption('ORDER BY', 'user_name');

		$res = $this->select(__METHOD__);

		$data = array ();
		$count = 0;
		while ($row = $db->fetchObject($res)) {
			if (++ $count > $limit) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter('from', ApiQueryBase :: keyToTitle($row->user_name));
				break;
			}

			$vals = array( 'name' => $row->user_name );
			if ($fld_editcount) {
				$vals['editcount'] = intval($row->user_editcount);
			}
			$data[] = $vals;
		}
		$db->freeResult($res);

		$result = $this->getResult();
		$result->setIndexedTagName($data, 'u');
		$result->addValue('query', $this->getModuleName(), $data);
	}

	protected function getAllowedParams() {
		return array (
			'from' => null,
			'prop' => array (
				ApiBase :: PARAM_ISMULTI => true, 
				ApiBase :: PARAM_TYPE => array (
					'editcount'
				)
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
			'from' => 'The user name to start enumerating from.',
			'prop' => 'What pieces of information to include',
			'limit' => 'How many total user names to return.'
		);
	}

	protected function getDescription() {
		return 'Enumerate all registered users';
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&list=allusers&aufrom=Y',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
