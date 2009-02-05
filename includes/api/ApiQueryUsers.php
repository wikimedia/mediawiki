<?php

/*
 * Created on July 30, 2007
 *
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2007 Roan Kattouw <Firstname>.<Lastname>@home.nl
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
 * Query module to get information about a list of users
 *
 * @ingroup API
 */

 class ApiQueryUsers extends ApiQueryBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'us');
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

		$users = (array)$params['users'];
		$goodNames = $done = array();
		$result = $this->getResult();
		// Canonicalize user names
		foreach($users as $u) {
			$n = User::getCanonicalName($u);
			if($n === false || $n === '')
			{
				$vals = array('name' => $u, 'invalid' => '');
				$fit = $result->addValue(array('query', $this->getModuleName()),
						null, $vals);
				if(!$fit)
				{
					$this->setContinueEnumParameter('users',
							implode('|', array_diff($users, $done)));
					$goodNames = array();
					break;
				}
				$done[] = $u;
			}
			 else
				$goodNames[] = $n;
		}
		if(count($goodNames))
		{
			$db = $this->getDb();
			$this->addTables('user', 'u1');
			$this->addFields('u1.user_name');
			$this->addWhereFld('u1.user_name', $goodNames);
			$this->addFieldsIf('u1.user_editcount', isset($this->prop['editcount']));
			$this->addFieldsIf('u1.user_registration', isset($this->prop['registration']));

			if(isset($this->prop['groups'])) {
				$this->addTables('user_groups');
				$this->addJoinConds(array('user_groups' => array('LEFT JOIN', 'ug_user=u1.user_id')));
				$this->addFields('ug_group');
			}
			if(isset($this->prop['blockinfo'])) {
				$this->addTables('ipblocks');
				$this->addTables('user', 'u2');
				$u2 = $this->getAliasedName('user', 'u2');
				$this->addJoinConds(array(
					'ipblocks' => array('LEFT JOIN', 'ipb_user=u1.user_id'),
					$u2 => array('LEFT JOIN', 'ipb_by=u2.user_id')));
				$this->addFields(array('ipb_reason', 'u2.user_name blocker_name'));
			}

			$data = array();
			$res = $this->select(__METHOD__);
			while(($r = $db->fetchObject($res))) {
				$data[$r->user_name]['name'] = $r->user_name;
				if(isset($this->prop['editcount']))
					$data[$r->user_name]['editcount'] = $r->user_editcount;
				if(isset($this->prop['registration']))
					$data[$r->user_name]['registration'] = wfTimestampOrNull(TS_ISO_8601, $r->user_registration);
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
			$done[] = $u;
		}
		return $this->getResult()->setIndexedTagName_internal(array('query', $this->getModuleName()), 'user');
	}

	public function getAllowedParams() {
		return array (
			'prop' => array (
				ApiBase :: PARAM_DFLT => NULL,
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => array (
					'blockinfo',
					'groups',
					'editcount',
					'registration',
					'emailable',
				)
			),
			'users' => array(
				ApiBase :: PARAM_ISMULTI => true
			)
		);
	}

	public function getParamDescription() {
		return array (
			'prop' => array(
				'What pieces of information to include',
				'  blockinfo    - tags if the user is blocked, by whom, and for what reason',
				'  groups       - lists all the groups the user belongs to',
				'  editcount    - adds the user\'s edit count',
				'  registration - adds the user\'s registration timestamp',
				'  emailable    - tags if the user can and wants to receive e-mail through [[Special:Emailuser]]',
			),
			'users' => 'A list of users to obtain the same information for'
		);
	}

	public function getDescription() {
		return 'Get information about a list of users';
	}

	protected function getExamples() {
		return 'api.php?action=query&list=users&ususers=brion|TimStarling&usprop=groups|editcount';
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
