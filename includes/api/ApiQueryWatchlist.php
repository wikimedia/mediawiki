<?php


/*
 * Created on Sep 25, 2006
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

class ApiQueryWatchlist extends ApiQueryGeneratorBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'wl');
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator($resultPageSet) {
		$this->run($resultPageSet);
	}

	private function run($resultPageSet = null) {
		global $wgUser;

		if (!$wgUser->isLoggedIn())
			$this->dieUsage('You must be logged-in to have a watchlist', 'notloggedin');

		$allrev = $start = $end = $namespace = $dir = $limit = $prop = null;
		extract($this->extractRequestParams());

		$patrol = $timestamp = $user = $comment = false;
		if (!is_null($prop)) {
			if (!is_null($resultPageSet))
				$this->dieUsage('prop parameter may not be used in a generator', 'params');

			$user = (false !== array_search('user', $prop));
			$comment = (false !== array_search('comment', $prop));
			$timestamp = (false !== array_search('timestamp', $prop));
			$patrol = (false !== array_search('patrol', $prop));

			if ($patrol) {
				global $wgUseRCPatrol, $wgUser;
				if (!$wgUseRCPatrol || !$wgUser->isAllowed('patrol'))
					$this->dieUsage('patrol property is not available', 'patrol');
			}
		}

		if (is_null($resultPageSet)) {
			$this->addFields(array (
				'rc_cur_id',
				'rc_this_oldid',
				'rc_namespace',
				'rc_title',
				'rc_new',
				'rc_minor',
				'rc_timestamp'
			));

			$this->addFieldsIf('rc_user', $user);
			$this->addFieldsIf('rc_user_text', $user);
			$this->addFieldsIf('rc_comment', $comment);
			$this->addFieldsIf('rc_patrolled', $patrol);
		}
		elseif ($allrev) {
			$this->addFields(array (
				'rc_this_oldid',
				'rc_namespace',
				'rc_title',
				'rc_timestamp'
			));
		} else {
			$this->addFields(array (
				'rc_cur_id',
				'rc_namespace',
				'rc_title',
				'rc_timestamp'
			));
		}

		$this->addTables(array (
			'watchlist',
			'page',
			'recentchanges'
		));

		$userId = $wgUser->getID();
		$this->addWhere(array (
			'wl_namespace = rc_namespace',
			'wl_title = rc_title',
			'rc_cur_id = page_id',
			'wl_user' => $userId
		));
		$this->addWhereRange('rc_timestamp', $dir, $start, $end);
		$this->addWhereFld('wl_namespace', $namespace);
		$this->addWhereIf('rc_this_oldid=page_latest', !$allrev);
		$this->addWhereIf("rc_timestamp > ''", !isset ($start) && !isset ($end));

		$this->addOption('LIMIT', $limit +1);

		$data = array ();
		$count = 0;
		$res = $this->select(__METHOD__);

		$db = & $this->getDB();
		while ($row = $db->fetchObject($res)) {
			if (++ $count > $limit) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter('from', $row->rc_timestamp);
				break;
			}

			if (is_null($resultPageSet)) {
				$vals = $this->addRowInfo('rc', $row);
				if($vals)
					$data[] = $vals;
			} else {
				$title = Title :: makeTitle($row->rc_namespace, $row->rc_title);
				// skip any pages that user has no rights to read
				if ($title->userCanRead()) {
					if ($allrev) {
						$data[] = intval($row->rc_this_oldid);
					} else {
						$data[] = intval($row->rc_cur_id);
					}
				}
			}
		}

		$db->freeResult($res);

		if (is_null($resultPageSet)) {
			$this->getResult()->setIndexedTagName($data, 'item');
			$this->getResult()->addValue('query', $this->getModuleName(), $data);
		}
		elseif ($allrev) {
			$resultPageSet->populateFromRevisionIDs($data);
		} else {
			$resultPageSet->populateFromPageIDs($data);
		}
	}

	protected function getAllowedParams() {
		$namespaces = $this->getQuery()->getValidNamespaces();
		return array (
			'allrev' => false,
			'start' => array (
				ApiBase :: PARAM_TYPE => 'timestamp'
			),
			'end' => array (
				ApiBase :: PARAM_TYPE => 'timestamp'
			),
			'namespace' => array (
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => $namespaces
			),
			'dir' => array (
				ApiBase :: PARAM_DFLT => 'older',
				ApiBase :: PARAM_TYPE => array (
					'newer',
					'older'
				)
			),
			'limit' => array (
				ApiBase :: PARAM_DFLT => 10,
				ApiBase :: PARAM_TYPE => 'limit',
				ApiBase :: PARAM_MIN => 1,
				ApiBase :: PARAM_MAX1 => ApiBase :: LIMIT_BIG1,
				ApiBase :: PARAM_MAX2 => ApiBase :: LIMIT_BIG2
			),
			'prop' => array (
				APIBase :: PARAM_ISMULTI => true,
				APIBase :: PARAM_TYPE => array (
					'user',
					'comment',
					'timestamp',
					'patrol'
				)
			)
		);
	}

	protected function getParamDescription() {
		return array (
			'allrev' => 'Include multiple revisions of the same page within given timeframe.',
			'start' => 'The timestamp to start enumerating from.',
			'end' => 'The timestamp to end enumerating.',
			'namespace' => 'Filter changes to only the given namespace(s).',
			'dir' => 'In which direction to enumerate pages.',
			'limit' => 'How many total pages to return per request.',
			'prop' => 'Which additional items to get (non-generator mode only).'
		);
	}

	protected function getDescription() {
		return '';
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&list=watchlist',
			'api.php?action=query&list=watchlist&wlallrev',
			'api.php?action=query&generator=watchlist&prop=info',
			'api.php?action=query&generator=watchlist&gwlallrev&prop=revisions&rvprop=timestamp|user'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
?>
