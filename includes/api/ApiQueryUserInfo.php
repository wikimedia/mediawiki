<?php

/*
 * Created on July 30, 2007
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
 * Query module to get information about the currently logged-in user
 * 
 * @addtogroup API
 */
class ApiQueryUserInfo extends ApiQueryBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'ui');
	}

	public function execute() {
		$params = $this->extractRequestParams();
		$result = $this->getResult();
		$r = array();

		if (!is_null($params['prop'])) {
			$this->prop = array_flip($params['prop']);
		} else {
			$this->prop = array();
		}
		$r['currentuser'] = $this->getCurrentUserInfo();
		
		if(is_array($params['users'])) {
			$r['users'] = $this->getOtherUsersInfo($params['users']);
			$result->setIndexedTagName($r['users'], 'user');
		}
		$result->addValue("query", $this->getModuleName(), $r);
	}
	
	protected function getOtherUsersInfo($users) {
		$goodNames = $retval = array();
		// Canonicalize user names
		foreach($users as $u) {
			$n = User::getCanonicalName($u);
			if($n === false) 
				$retval[] = array('name' => $u, 'invalid' => '');
			 else
				$goodNames[] = $n;
		}

		$db = $this->getDb();
		$userTable = $db->tableName('user');
		$tables = "$userTable AS u1";
		$this->addFields('u1.user_name');
		$this->addWhereFld('u1.user_name', $goodNames);
		$this->addFieldsIf('u1.user_editcount', isset($this->prop['editcount']));
		
		if(isset($this->prop['groups'])) {
			$ug = $db->tableName('user_groups');
			$tables = "$tables LEFT JOIN $ug ON ug_user=u1.user_id";
			$this->addFields('ug_group');
		}
		if(isset($this->prop['blockinfo'])) {
			$ipb = $db->tableName('ipblocks');
			$tables = "$tables LEFT JOIN $ipb ON ipb_user=u1.user_id";
			$tables = "$tables LEFT JOIN $userTable AS u2 ON ipb_by=u2.user_id";
			$this->addFields(array('ipb_reason', 'u2.user_name AS blocker_name'));
		}
		$this->addTables($tables);
		
		$data = array();
		$res = $this->select(__METHOD__);
		while(($r = $db->fetchObject($res))) {
			$data[$r->user_name]['name'] = $r->user_name;
			if(isset($this->prop['editcount']))
				$data[$r->user_name]['editcount'] = $r->user_editcount;
			if(isset($this->prop['groups']))
				// This row contains only one group, others will be added from other rows
				if(!is_null($r->ug_group))
					$data[$r->user_name]['groups'][] = $r->ug_group;
			if(isset($this->prop['blockinfo']))
				if(!is_null($r->blocker_name)) {
					$data[$r->user_name]['blockedby'] = $r->blocker_name;
					$data[$r->user_name]['blockreason'] = $r->ipb_reason;
				}
		}
		
		// Second pass: add result data to $retval
		foreach($goodNames as $u) {
			if(!isset($data[$u]))
				$retval[] = array('name' => $u, 'missing' => '');
			else {
				if(isset($this->prop['groups']) && isset($data[$u]['groups']))
					$this->getResult()->setIndexedTagName($data[$u]['groups'], 'g');
				$retval[] = $data[$u];
			}
		}
		return $retval;		
	}
	
	protected function getCurrentUserInfo() {
		global $wgUser;
		$result = $this->getResult();
		$vals = array();
		$vals['id'] = $wgUser->getId();
		$vals['name'] = $wgUser->getName();

		if( $wgUser->isAnon() ) $vals['anon'] = '';
		if (isset($this->prop['blockinfo'])) {
			if ($wgUser->isBlocked()) {
				$vals['blockedby'] = User::whoIs($wgUser->blockedBy());
				$vals['blockreason'] = $wgUser->blockedFor();
			}
		}		
		if (isset($this->prop['hasmsg']) && $wgUser->getNewtalk()) {
			$vals['messages'] = '';
		}
		if (isset($this->prop['groups'])) {
			$vals['groups'] = $wgUser->getGroups();
			$result->setIndexedTagName($vals['groups'], 'g');	// even if empty
		}
		if (isset($this->prop['rights'])) {
			$vals['rights'] = $wgUser->getRights();
			$result->setIndexedTagName($vals['rights'], 'r');	// even if empty
		}
		if (isset($this->prop['options'])) {
			$vals['options'] = (is_null($wgUser->mOptions) ? User::getDefaultOptions() : $wgUser->mOptions);
		}
		if (isset($this->prop['editcount'])) {
			$vals['editcount'] = $wgUser->getEditCount();
		}
		return $vals;
	}

	protected function getAllowedParams() {
		return array (
			'prop' => array (
				ApiBase :: PARAM_DFLT => NULL,
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => array (
					'blockinfo',
					'hasmsg',
					'groups',
					'rights',
					'options',
					'editcount'
				)
			),
			'users' => array(
				ApiBase :: PARAM_ISMULTI => true
			)
		);
	}

	protected function getParamDescription() {
		return array (
			'prop' => array(
				'What pieces of information to include',
				'  blockinfo - tags if the user is blocked, by whom, and for what reason',
				'  hasmsg    - adds a tag "message" if user has pending messages (current user only)',
				'  groups    - lists all the groups the user belongs to',
				'  rights    - lists of all rights the user has (current user only)',
				'  options   - lists all preferences the user has set (current user only)',
				'  editcount - adds the user\'s edit count'
			),
			'users' => 'A list of other users to obtain the same information for'
		);
	}

	protected function getDescription() {
		return 'Get information about the current user and other users';
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&meta=userinfo',
			'api.php?action=query&meta=userinfo&uiprop=blockinfo|groups|rights|hasmsg',
			'api.php?action=query&meta=userinfo&uioption=rememberpassword',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
