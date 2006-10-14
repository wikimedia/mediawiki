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

		$allrev = $start = $end = $namespace = $dir = $limit = null;
		extract($this->extractRequestParams());

		$db = $this->getDB();

		$dirNewer = ($dir === 'newer');
		$before = ($dirNewer ? '<=' : '>=');
		$after = ($dirNewer ? '>=' : '<=');

		$tables = array (
			'watchlist',
			'page',
			'recentchanges'
		);

		$options = array (
			'LIMIT' => $limit +1,
			'ORDER BY' => 'rc_timestamp' . ($dirNewer ? '' : ' DESC'),
			'USE INDEX' => 'rc_timestamp');

		if (is_null($resultPageSet)) {
			$fields = array (
				'rc_namespace AS page_namespace',
				'rc_title AS page_title',
				'rc_comment AS rev_comment',
				'rc_cur_id AS page_id',
				'rc_user AS rev_user',
				'rc_user_text AS rev_user_text',
				'rc_timestamp AS rev_timestamp',
				'rc_minor AS rev_minor_edit',
				'rc_this_oldid AS rev_id',
				'rc_last_oldid',
				'rc_id',
	//			'rc_patrolled',
				'rc_new AS page_is_new'
			);
		} elseif ($allrev) {
			$fields = array (
				'rc_this_oldid AS rev_id',
				'rc_namespace AS page_namespace',
				'rc_title AS page_title',
				'rc_timestamp AS rev_timestamp'
			);
		} else {
			$fields = array (
				'rc_cur_id AS page_id',
				'rc_namespace AS page_namespace',
				'rc_title AS page_title',
				'rc_timestamp AS rev_timestamp'
			);
		}

		$where = array (
			'wl_namespace = rc_namespace',
			'wl_title = rc_title',
			'rc_cur_id = page_id',
		'wl_user' => $wgUser->getID());

		if (!$allrev)
			$where[] = 'rc_this_oldid=page_latest';
		if (isset ($namespace))
			$where['wl_namespace'] = $namespace;
		if (isset ($start))
			$where[] = 'rev_timestamp' . $after . $db->addQuotes($start);
		if (isset ($end))
			$where[] = 'rev_timestamp' . $before . $db->addQuotes($end);

		$this->profileDBIn();
		$res = $db->select($tables, $fields, $where, __METHOD__, $options);
		$this->profileDBOut();

		$data = array ();
		$count = 0;
		while ($row = $db->fetchObject($res)) {
			if (++ $count > $limit) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$msg = array (
					'continue' => $this->encodeParamName('from'
				) . '=' . $row->rev_timestamp);
				$this->getResult()->addValue('query-status', 'watchlist', $msg);
				break;
			}

			$title = Title :: makeTitle($row->page_namespace, $row->page_title);
			// skip any pages that user has no rights to read
			if ($title->userCanRead()) {

				if (is_null($resultPageSet)) {
					$id = intval($row->page_id);

					$data[] = array (
						'ns' => $title->getNamespace(),
						'title' => $title->getPrefixedText(),
						'id' => intval($row->page_id),
						'comment' => $row->rev_comment,
						'isuser' => $row->rev_user,
						'user' => $row->rev_user_text,
						'timestamp' => $row->rev_timestamp,
						'minor' => $row->rev_minor_edit,
						'rev_id' => $row->rev_id,
						'rc_last_oldid' => $row->rc_last_oldid,
						'rc_id' => $row->rc_id,
//						'rc_patrolled' => $row->rc_patrolled,
						'isnew' => $row->page_is_new
					);
				} elseif ($allrev) {
					$data[] = intval($row->rev_id);
				} else {
					$data[] = intval($row->page_id);
				}				
			}
		}
		$db->freeResult($res);

		if (is_null($resultPageSet)) {
			ApiResult :: setIndexedTagName($data, 'p');
			$this->getResult()->addValue('query', 'watchlist', $data);
		} elseif ($allrev) {
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
				ApiBase :: PARAM_MAX1 => 500,
				ApiBase :: PARAM_MAX2 => 5000
			)
		);
	}

	protected function getParamDescription() {
		return array (
			'allrev' => 'Include multiple revisions of the same page within given timeframe',
			'start' => 'The timestamp to start enumerating from.',
			'end' => 'The timestamp to end enumerating.',
			'namespace' => 'Filter changes to only the given namespace(s).',
			'dir' => 'In which direction to enumerate pages "older" (default), or "newer")',
			'limit' => 'How many total pages to return per request'
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