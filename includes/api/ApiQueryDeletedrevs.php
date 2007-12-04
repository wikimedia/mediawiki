<?php

/*
 * Created on Jul 2, 2007
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
 * Query module to enumerate all available pages.
 * 
 * @addtogroup API
 */
class ApiQueryDeletedrevs extends ApiQueryBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'dr');
	}

	public function execute() {

		global $wgUser;
		// Before doing anything at all, let's check permissions
		if(!$wgUser->isAllowed('deletedhistory'))
			$this->dieUsage('You don\'t have permission to view deleted revision information', 'permissiondenied');

		$db = $this->getDB();
		$params = $this->extractRequestParams();
		$prop = array_flip($params['prop']);
		$fld_revid = isset($prop['revid']);
		$fld_user = isset($prop['user']);
		$fld_comment = isset($prop['comment']);
		$fld_minor = isset($prop['minor']);
		$fld_len = isset($prop['len']);
		$fld_content = isset($prop['content']);
		$fld_token = isset($prop['token']);

		$result = $this->getResult();
		$pageSet = $this->getPageSet();
		$titles = $pageSet->getTitles();
		$data = array();

		$this->addTables('archive');
		$this->addFields(array('ar_title', 'ar_namespace', 'ar_timestamp'));
		if($fld_revid)
			$this->addFields('ar_rev_id');
		if($fld_user)
			$this->addFields('ar_user_text');
		if($fld_comment)
			$this->addFields('ar_comment');
		if($fld_minor)
			$this->addFields('ar_minor_edit');
		if($fld_len)
			$this->addFields('ar_len');
		if($fld_content)
		{
			$this->addTables('text');
			$this->addFields(array('ar_text', 'ar_text_id', 'old_text', 'old_flags'));
			$this->addWhere('ar_text_id = old_id');

			// This also means stricter limits and stricter restrictions
			if(!$wgUser->isAllowed('undelete'))
				$this->dieUsage('You don\'t have permission to view deleted revision content', 'permissiondenied');
			$userMax = 50;
			$botMax = 200;
			$this->validateLimit('limit', $params['limit'], 1, $userMax, $botMax);
		}
		if($fld_token)
			// Undelete tokens are identical for all pages, so we cache one here
			$token = $wgUser->editToken();

		// We need a custom WHERE clause that matches all titles.
		if(count($titles) > 0)
		{
			$lb = new LinkBatch($titles);
			$where = $lb->constructSet('ar', $db);
			$this->addWhere($where);
		}

		$this->addOption('LIMIT', $params['limit'] + 1);
		$this->addWhereRange('ar_timestamp', $params['dir'], $params['start'], $params['end']);
		if(isset($params['namespace']))
			$this->addWhereFld('ar_namespace', $params['namespace']);
		$res = $this->select(__METHOD__);
		$pages = array();
		$count = 0;
		// First populate the $pages array
		while($row = $db->fetchObject($res))
		{
			if($count++ == $params['limit'])
			{
				// We've had enough
				$this->setContinueEnumParameter('start', wfTimestamp(TS_ISO_8601, $row->ar_timestamp));
				break;
			}

			$rev = array();
			$rev['timestamp'] = wfTimestamp(TS_ISO_8601, $row->ar_timestamp);
			if($fld_revid)
				$rev['revid'] = $row->ar_rev_id;
			if($fld_user)
				$rev['user'] = $row->ar_user_text;
			if($fld_comment)
				$rev['comment'] = $row->ar_comment;
			if($fld_minor)
				if($row->ar_minor_edit == 1)
					$rev['minor'] = '';
			if($fld_len)
				$rev['len'] = $row->ar_len;
			if($fld_content)
				ApiResult::setContent($rev, Revision::getRevisionText($row));

			$t = Title::makeTitle($row->ar_namespace, $row->ar_title);
			if(!isset($pages[$t->getPrefixedText()]))
			{
				$pages[$t->getPrefixedText()] = array(
					'title' => $t->getPrefixedText(),
					'ns' => intval($row->ar_namespace),
					'revisions' => array($rev)
				);
				if($fld_token)
					$pages[$t->getPrefixedText()]['token'] = $token;
			}
			else
				$pages[$t->getPrefixedText()]['revisions'][] = $rev;
		}
		$db->freeResult($res);

		// We don't want entire pagenames as keys, so let's make this array indexed
		foreach($pages as $page)
		{
			$result->setIndexedTagName($page['revisions'], 'rev');
			$data[] = $page;
		}
		$result->setIndexedTagName($data, 'page');
		$result->addValue('query', $this->getModuleName(), $data);
		}

	protected function getAllowedParams() {
		return array (
			'start' => array(
				ApiBase :: PARAM_TYPE => 'timestamp'
			),
			'end' => array(
				ApiBase :: PARAM_TYPE => 'timestamp',
			),
			'dir' => array(
				ApiBase :: PARAM_TYPE => array(
					'newer',
					'older'
				),
				ApiBase :: PARAM_DFLT => 'older'
			),
			'namespace' => array(
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => 'namespace'
			),
			'limit' => array(
				ApiBase :: PARAM_DFLT => 10,
				ApiBase :: PARAM_TYPE => 'limit',
				ApiBase :: PARAM_MIN => 1,
				ApiBase :: PARAM_MAX => ApiBase :: LIMIT_BIG1,
				ApiBase :: PARAM_MAX2 => ApiBase :: LIMIT_BIG2
			),
			'prop' => array(
				ApiBase :: PARAM_DFLT => 'user|comment',
				ApiBase :: PARAM_TYPE => array(
					'revid',
					'user',
					'comment',
					'minor',
					'len',
					'content',
					'token'
				),
				ApiBase :: PARAM_ISMULTI => true
			)
		);
	}

	protected function getParamDescription() {
		return array (
			'start' => 'The timestamp to start enumerating from',
			'end' => 'The timestamp to stop enumerating at',
			'dir' => 'The direction in which to enumerate',
			'namespace' => 'The namespaces to search in',
			'limit' => 'The maximum amount of revisions to list',
			'prop' => 'Which properties to get'
		);
	}

	protected function getDescription() {
		return 'List deleted revisions.';
	}

	protected function getExamples() {
		return array (
			'List the first 50 deleted revisions in the Category and Category talk namespaces',
			'  api.php?action=query&list=deletedrevs&drdir=newer&drlimit=50&drnamespace=14|15',
			'List the last deleted revisions of Main Page and Talk:Main Page, with content:',
			'  api.php?action=query&list=deletedrevs&titles=Main%20Page|Talk:Main%20Page&drprop=user|comment|content'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id: ApiQueryDeletedrevs.php 23531 2007-06-30 01:19:14Z simetrical $';
	}
}
