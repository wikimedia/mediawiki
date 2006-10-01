<?php


/*
 * Created on Sep 7, 2006
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

class ApiQueryRevisions extends ApiQueryBase {

	public function __construct($query, $moduleName, $generator = false) {
		parent :: __construct($query, $moduleName, $generator);
	}

	public function execute() {
		$rvlimit = $rvstartid = $rvendid = $rvstart = $rvend = $rvdir = $rvprop = null;
		extract($this->extractRequestParams());

		// true when ordered by timestamp from older to newer, false otherwise
		$dirNewer = ($rvdir === 'newer');

		// If any of those parameters are used, work in 'enumeration' mode.
		// Enum mode can only be used when exactly one page is provided.
		// Enumerating revisions on multiple pages make it extremelly 
		// difficult to manage continuations and require additional sql indexes  
		$enumRevMode = ($rvlimit !== 0 || $rvstartid !== 0 || $rvendid !== 0 || $dirNewer || isset ($rvstart) || isset ($rvend));

		$data = $this->getData();
		$pageCount = $data->getGoodTitleCount();
		$revCount = $data->getRevisionCount();

		// Optimization -- nothing to do
		if ($revCount === 0 && $pageCount === 0)
			return;

		if ($revCount > 0 && $pageCount > 0)
			$this->dieUsage('The revids= parameter may not be used with titles, pageids, or generator options.', 'rv_revids');

		if ($revCount > 0 && $enumRevMode)
			$this->dieUsage('The revids= parameter may not be used with the list options (rvlimit, rvstartid, rvendid, dirNewer, rvstart, rvend).', 'rv_revids');

		if ($revCount === 0 && $pageCount > 1 && $enumRevMode)
			$this->dieUsage('titles, pageids or a generator was used to supply multiple pages, but the rvlimit, rvstartid, rvendid, dirNewer, rvstart, and rvend parameters may only be used on a single page.', 'rv_multpages');

		$tables = array (
			'revision'
		);
		$fields = array (
			'rev_id',
			'rev_page',
			'rev_text_id',
			'rev_minor_edit'
		);
		$conds = array (
			'rev_deleted' => 0
		);
		$options = array ();

		$showTimestamp = $showUser = $showComment = $showContent = false;
		if (isset ($rvprop)) {
			foreach ($rvprop as $prop) {
				switch ($prop) {
					case 'timestamp' :
						$fields[] = 'rev_timestamp';
						$showTimestamp = true;
						break;
					case 'user' :
						$fields[] = 'rev_user';
						$fields[] = 'rev_user_text';
						$showUser = true;
						break;
					case 'comment' :
						$fields[] = 'rev_comment';
						$showComment = true;
						break;
					case 'content' :
						// todo: check the page count/limit when requesting content
						//$this->validateLimit( 'content: (rvlimit*pages)+revids',
						//$rvlimit * count($this->existingPageIds) + count($this->revIdsArray), 50, 200 );
						$tables[] = 'text';
						$conds[] = 'rev_text_id=old_id';
						$fields[] = 'old_id';
						$fields[] = 'old_text';
						$fields[] = 'old_flags';
						$showContent = true;
						break;
					default :
						ApiBase :: dieDebug("unknown rvprop $prop");
				}
			}
		}

		$userMax = ($showContent ? 50 : 500);
		$botMax = ($showContent ? 200 : 10000);

		if ($enumRevMode) {

			// This is mostly to prevent parameter errors (and optimize sql?)
			if ($rvstartid !== 0 && isset ($rvstart))
				$this->dieUsage('rvstart and rvstartid cannot be used together', 'rv_badparams');

			if ($rvendid !== 0 && isset ($rvend))
				$this->dieUsage('rvend and rvend cannot be used together', 'rv_badparams');

			$options['ORDER BY'] = 'rev_timestamp' . ($dirNewer ? '' : ' DESC');
			$before = ($dirNewer ? '<=' : '>=');
			$after = ($dirNewer ? '>=' : '<=');

			if ($rvstartid !== 0)
				$conds[] = 'rev_id' . $after . intval($rvstartid);
			if ($rvendid !== 0)
				$conds[] = 'rev_id' . $before . intval($rvendid);
			if (isset ($rvstart))
				$conds[] = 'rev_timestamp' . $after . $this->prepareTimestamp($rvstart);
			if (isset ($rvend))
				$conds[] = 'rev_timestamp' . $before . $this->prepareTimestamp($rvend);

			// must manually initialize unset rvlimit
			if (!isset ($rvlimit))
				$rvlimit = 10;

			$this->validateLimit('rvlimit', $rvlimit, 1, $userMax, $botMax);

			// There is only one ID, use it
			$conds['rev_page'] = array_pop(array_keys($data->getGoodTitles()));

		} else
			if ($pageCount > 0) {
				// When working in multi-page non-enumeration mode,
				// limit to the latest revision only
				$tables[] = 'page';
				$conds[] = 'page_id=rev_page';
				$conds[] = 'page_latest=rev_id';
				$this->validateLimit('page_count', $pageCount, 1, $userMax, $botMax);

				// Get all page IDs
				$conds['page_id'] = array_keys($data->getGoodTitles());

				$rvlimit = $pageCount; // assumption testing -- we should never get more then $pageCount rows.
			} else
				if ($revCount > 0) {
					$this->validateLimit('rev_count', $revCount, 1, $userMax, $botMax);

					// Get all revision IDs
					$conds['rev_id'] = array_keys($data->getRevisionIDs());

					$rvlimit = $revCount; // assumption testing -- we should never get more then $revCount rows.
				} else
					ApiBase :: dieDebug('param validation?');

		$options['LIMIT'] = $rvlimit +1;

		$db = $this->getDB();
		$this->profileDBIn();
		$res = $db->select($tables, $fields, $conds, __CLASS__ . '::' . __FUNCTION__, $options);
		$this->profileDBOut();

		$data = array ();
		$count = 0;
		while ($row = $db->fetchObject($res)) {

			if (++ $count > $rvlimit) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				if (!$enumRevMode)
					ApiBase :: dieDebug('Got more rows then expected'); // bug report

				$startStr = 'rvstartid=' . $row->rev_id;
				$msg = array (
					'continue' => $startStr
				);
				$this->getResult()->addValue('query-status', 'revisions', $msg);
				break;
			}

			$vals = array (
				'revid' => intval($row->rev_id
			), 'oldid' => intval($row->rev_text_id));

			if ($row->rev_minor_edit) {
				$vals['minor'] = '';
			}

			if ($showTimestamp)
				$vals['timestamp'] = wfTimestamp(TS_ISO_8601, $row->rev_timestamp);

			if ($showUser) {
				$vals['user'] = $row->rev_user_text;
				if (!$row->rev_user)
					$vals['anon'] = '';
			}

			if ($showComment)
				$vals['comment'] = $row->rev_comment;

			if ($showContent) {
				ApiResult :: addContent($vals, Revision :: getRevisionText($row));
			}

			$this->getResult()->addValue(array (
				'query',
				'pages',
				intval($row->rev_page
			), 'revisions'), intval($row->rev_id), $vals);
		}
		$db->freeResult($res);

		// Ensure that all revisions are shown as '<r>' elements
		$data = & $this->getResultData();
		foreach ($data['query']['pages'] as & $page) {
			if (isset ($page['revisions'])) {
				ApiResult :: setIndexedTagName($page['revisions'], 'rev');
			}
		}
	}

	protected function getAllowedParams() {
		return array (
			'rvlimit' => array (
				GN_ENUM_DFLT => 0,
				GN_ENUM_TYPE => 'limit',
				GN_ENUM_MIN => 0,
				GN_ENUM_MAX1 => 50,
				GN_ENUM_MAX2 => 500
			),
			'rvstartid' => 0,
			'rvendid' => 0,
			'rvstart' => array (
				GN_ENUM_TYPE => 'timestamp'
			),
			'rvend' => array (
				GN_ENUM_TYPE => 'timestamp'
			),
			'rvdir' => array (
				GN_ENUM_DFLT => 'older',
				GN_ENUM_TYPE => array (
					'newer',
					'older'
				)
			),
			'rvprop' => array (
				GN_ENUM_ISMULTI => true,
				GN_ENUM_TYPE => array (
					'timestamp',
					'user',
					'comment',
					'content'
				)
			)
		);
	}

	protected function getDescription() {
		return array (
			'Get revision information.',
			'This module may be used in several ways:',
			' 1) Get data about a set of pages (last revision), by setting titles or pageids parameter.',
			' 2) Get revisions for one given page, by using titles/pageids with rvstart/rvend/rvlimit params.',
			' 3) Get data about a set of revisions by setting their IDs with revids parameter.'
		);
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&prop=revisions&titles=Main%20Page&rvprop=timestamp|user|comment|content'
		);
	}
}
?>