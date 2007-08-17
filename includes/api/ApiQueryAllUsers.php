<?php

/*
 * Created on July 7, 2007
 *
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2007 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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
			$fld_groups = isset($prop['groups']);
		} else {
			$fld_editcount = $fld_groups = false;
		}

		$limit = $params['limit'];
		$tables = $db->tableName('user');
		
		if( !is_null( $params['from'] ) )
			$this->addWhere( 'user_name >= ' . $db->addQuotes( self::keyToTitle( $params['from'] ) ) );
		
		if( isset( $params['prefix'] ) )
			$this->addWhere( 'user_name LIKE "' . $db->escapeLike( self::keyToTitle( $params['prefix'] ) ) . '%"' );

		if (!is_null($params['group'])) {
			// Filter only users that belong to a given group
			$tblName = $db->tableName('user_groups');
			$tables = "$tables INNER JOIN $tblName ug1 ON ug1.ug_user=user_id";
			$this->addWhereFld('ug1.ug_group', $params['group']);
		}

		if ($fld_groups) {
			// Show the groups the given users belong to
			// request more than needed to avoid not getting all rows that belong to one user
			$groupCount = count(User::getAllGroups());
			$sqlLimit = $limit+$groupCount+1;

			$tblName = $db->tableName('user_groups');
			$tables = "$tables LEFT JOIN $tblName ug2 ON ug2.ug_user=user_id";
			$this->addFields('ug2.ug_group ug_group2');
		} else {
			$sqlLimit = $limit+1;
		}

		$this->addOption('LIMIT', $sqlLimit);
		$this->addTables($tables);

		$this->addFields('user_name');
		$this->addFieldsIf('user_editcount', $fld_editcount);

		$this->addOption('ORDER BY', 'user_name');

		$res = $this->select(__METHOD__);

		$data = array ();
		$count = 0;
		$lastUserData = false;
		$lastUser = false;
		$result = $this->getResult();
				
		//
		// This loop keeps track of the last entry.
		// For each new row, if the new row is for different user then the last, the last entry is added to results.
		// Otherwise, the group of the new row is appended to the last entry.
		// The setContinue... is more complex because of this, and takes into account the higher sql limit
		// to make sure all rows that belong to the same user are received.
		//
		while (true) {
			
			$row = $db->fetchObject($res);
			$count++;
			
			if (!$row || $lastUser != $row->user_name) {
				// Save the last pass's user data
				if (is_array($lastUserData))
					$data[] = $lastUserData;
				
				// No more rows left
				if (!$row)
					break;

				if ($count > $limit) {
					// We've reached the one extra which shows that there are additional pages to be had. Stop here...
					$this->setContinueEnumParameter('from', ApiQueryBase :: keyToTitle($row->user_name));
					break;
				}

				// Record new user's data
				$lastUser = $row->user_name;
				$lastUserData = array( 'name' => $lastUser );
				if ($fld_editcount)
					$lastUserData['editcount'] = intval($row->user_editcount);
					
			}
			
			if ($sqlLimit == $count) {
				// BUG!  database contains group name that User::getAllGroups() does not return
				// TODO: should handle this more gracefully
				ApiBase :: dieDebug(__METHOD__, 
					'MediaWiki configuration error: the database contains more user groups than known to User::getAllGroups() function');
			}
								
			// Add user's group info
			if ($fld_groups && !is_null($row->ug_group2)) {
				$lastUserData['groups'][] = $row->ug_group2;
				$result->setIndexedTagName($lastUserData['groups'], 'g');
			}
		}
		
		$db->freeResult($res);

		$result->setIndexedTagName($data, 'u');
		$result->addValue('query', $this->getModuleName(), $data);
	}

	protected function getAllowedParams() {
		return array (
			'from' => null,
			'prefix' => null,
			'group' => array(
				ApiBase :: PARAM_TYPE => User::getAllGroups()
			),
			'prop' => array (
				ApiBase :: PARAM_ISMULTI => true, 
				ApiBase :: PARAM_TYPE => array (
					'editcount',
					'groups',
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
			'prefix' => 'Search for all page titles that begin with this value.',
			'group' => 'Limit users to a given group name',
			'prop' => array(
				'What pieces of information to include.',
				'`groups` property uses more server resources and may return fewer results than the limit.'),
			'limit' => 'How many total user names to return.',
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
