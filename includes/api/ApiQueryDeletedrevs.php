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
 * @ingroup API
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
		$params = $this->extractRequestParams(false);
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
		
		// This module operates in three modes:
		// 'revs': List deleted revs for certain titles
		// 'user': List deleted revs by a certain user
		// 'all': List all deleted revs
		$mode = 'all';
		if(count($titles) > 0)
			$mode = 'revs';
		else if(!is_null($params['user']))
			$mode = 'user';
		
		if(!is_null($params['user']) && !is_null($params['excludeuser']))
				$this->dieUsage('user and excludeuser cannot be used together', 'badparams');

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

			// This also means stricter restrictions
			if(!$wgUser->isAllowed('undelete'))
				$this->dieUsage('You don\'t have permission to view deleted revision content', 'permissiondenied');
		}
		// Check limits
		$userMax = $fld_content ? ApiBase :: LIMIT_SML1 : ApiBase :: LIMIT_BIG1;
		$botMax  = $fld_content ? ApiBase :: LIMIT_SML2 : ApiBase :: LIMIT_BIG2;

		$limit = $params['limit'];

		if( $limit == 'max' ) {
			$limit = $this->getMain()->canApiHighLimits() ? $botMax : $userMax;
			$this->getResult()->addValue( 'limits', $this->getModuleName(), $limit );
		}

		$this->validateLimit('limit', $limit, 1, $userMax, $botMax);

		if($fld_token)
			// Undelete tokens are identical for all pages, so we cache one here
			$token = $wgUser->editToken();

		// We need a custom WHERE clause that matches all titles.
		if($mode == 'revs')
		{
			$lb = new LinkBatch($titles);
			$where = $lb->constructSet('ar', $db);
			$this->addWhere($where);
		}
		elseif($mode == 'all')
		{
			$this->addWhereFld('ar_namespace', $params['namespace']);
			if(!is_null($params['from']))
			{
				$from = $this->getDB()->strencode($this->titleToKey($params['from']));
				$this->addWhere("ar_title >= '$from'");
			}
		}
		
		if(!is_null($params['user'])) {
			$this->addWhereFld('ar_user_text', $params['user']);
		} elseif(!is_null($params['excludeuser'])) {
			$this->addWhere('ar_user_text != ' .
				$this->getDB()->addQuotes($params['excludeuser']));
		}
		
		if(!is_null($params['continue']) && $mode == 'all')
		{
			$cont = explode('|', $params['continue']);
			if(count($cont) != 2)
				$this->dieUsage("Invalid continue param. You should pass the original value returned by the previous query", "badcontinue");
			$title = $this->getDB()->strencode($this->titleToKey($cont[0]));
			$ts = $this->getDB()->strencode($cont[1]);
			$this->addWhere("ar_title > '$title' OR " .
					"(ar_title = '$title' AND " .
					"ar_timestamp >= '$ts')");
		}

		$this->addOption('LIMIT', $limit + 1);
		$this->addOption('USE INDEX', array('archive' => ($mode == 'user' ? 'usertext_timestamp' : 'name_title_timestamp')));
		if($mode == 'all')
		{
			if($params['unique'])
			{
				$this->addOption('GROUP BY', 'ar_title');
				$this->addOption('ORDER BY', 'ar_title');
			}
			else
				$this->addOption('ORDER BY', 'ar_title, ar_timestamp');
		}
		else
		{
			if($mode == 'revs')
			{
				// Sort by ns and title in the same order as timestamp for efficiency
				$this->addWhereRange('ar_namespace', $params['dir'], null, null);
				$this->addWhereRange('ar_title', $params['dir'], null, null);
			}
			$this->addWhereRange('ar_timestamp', $params['dir'], $params['start'], $params['end']);
		}
		$res = $this->select(__METHOD__);
		$pageMap = array(); // Maps ns&title to (fake) pageid
		$count = 0;
		$newPageID = 0;
		while($row = $db->fetchObject($res))
		{
			if(++$count > $limit)
			{
				// We've had enough
				if($mode == 'all')
					$this->setContinueEnumParameter('continue', $this->keyToTitle($row->ar_title) . '|' .
						$row->ar_timestamp);
				else
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

			if(!isset($pageMap[$row->ar_namespace][$row->ar_title]))
			{
				$pageID = $newPageID++;
				$pageMap[$row->ar_namespace][$row->ar_title] = $pageID;
				$t = Title::makeTitle($row->ar_namespace, $row->ar_title);
				$a = array(
					'title' => $t->getPrefixedText(),
					'ns' => intval($row->ar_namespace),
					'revisions' => array($rev)
				);
				$result->setIndexedTagName($a['revisions'], 'rev');
				if($fld_token)
					$a['token'] = $token;
				$fit = $result->addValue(array('query', $this->getModuleName()), $pageID, $a);
			}
			else
			{
				$pageID = $pageMap[$row->ar_namespace][$row->ar_title];
				$fit = $result->addValue(
					array('query', $this->getModuleName(), $pageID, 'revisions'),
					null, $rev);
			}
			if(!$fit)
			{
				$this->setContinueEnumParameter('start', wfTimestamp(TS_ISO_8601, $row->ar_timestamp));
				break;
			}
		}
		$db->freeResult($res);
		$result->setIndexedTagName_internal(array('query', $this->getModuleName()), 'page');
	}

	public function getAllowedParams() {
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
			'from' => null,
			'continue' => null,
			'unique' => false,
			'user' => array(
				ApiBase :: PARAM_TYPE => 'user'
			),
			'excludeuser' => array(
				ApiBase :: PARAM_TYPE => 'user'
			),
			'namespace' => array(
				ApiBase :: PARAM_TYPE => 'namespace',
				ApiBase :: PARAM_DFLT => 0,
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
			),
		);
	}

	public function getParamDescription() {
		return array (
			'start' => 'The timestamp to start enumerating from. (1,2)',
			'end' => 'The timestamp to stop enumerating at. (1,2)',
			'dir' => 'The direction in which to enumerate. (1,2)',
			'limit' => 'The maximum amount of revisions to list',
			'prop' => 'Which properties to get',
			'namespace' => 'Only list pages in this namespace (3)',
			'user' => 'Only list revisions by this user',
			'excludeuser' => 'Don\'t list revisions by this user',
			'from' => 'Start listing at this title (3)',
			'continue' => 'When more results are available, use this to continue (3)',
			'unique' => 'List only one revision for each page (3)',
		);
	}

	public function getDescription() {
		return array(	'List deleted revisions.',
				'This module operates in three modes:',
				'1) List deleted revisions for the given title(s), sorted by timestamp',
				'2) List deleted contributions for the given user, sorted by timestamp (no titles specified)',
				'3) List all deleted revisions in the given namespace, sorted by title and timestamp (no titles specified, druser not set)',
				'Certain parameters only apply to some modes and are ignored in others.',
				'For instance, a parameter marked (1) only applies to mode 1 and is ignored in modes 2 and 3.',
		);
	}

	protected function getExamples() {
		return array (
			'List the last deleted revisions of Main Page and Talk:Main Page, with content (mode 1):',
			'  api.php?action=query&list=deletedrevs&titles=Main%20Page|Talk:Main%20Page&drprop=user|comment|content',
			'List the last 50 deleted contributions by Bob (mode 2):',
			'  api.php?action=query&list=deletedrevs&druser=Bob&drlimit=50',
			'List the first 50 deleted revisions in the main namespace (mode 3):',
			'  api.php?action=query&list=deletedrevs&drdir=newer&drlimit=50',
			'List the first 50 deleted pages in the Talk namespace (mode 3):',
			'  api.php?action=query&list=deletedrevs&drdir=newer&drlimit=50&drnamespace=1&drunique',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}