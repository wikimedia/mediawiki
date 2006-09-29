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
	require_once ("ApiQueryBase.php");
}

class ApiQueryRevisions extends ApiQueryBase {

	public function __construct($query, $moduleName, $generator = false) {
		parent :: __construct($query, $moduleName, $generator);
	}

	public function execute() {
		$rvrevids = $rvlimit = $rvstartid = $rvendid = $rvstart = $rvend = $rvdir = $rvprop = null;
		extract($this->extractRequestParams());

		//
		// Parameter validation
		//

		// true when ordered by timestamp from older to newer, false otherwise
		$dirNewer = ($rvdir === 'newer');

		// If any of those parameters are used, we can only work with a single page
		// Enumerating revisions on multiple pages make it extremelly 
		// difficult to manage continuations and require additional sql indexes  
		$enumRevMode = ($rvlimit !== 0 || $rvstartid !== 0 || $rvendid !== 0 || $dirNewer || isset ($rvstart) || isset ($rvend));

		if ($rvstartid !== 0 || $rvendid !== 0)
			$this->dieUsage('rvstartid/rvendid not implemented', 'notimplemented');

		$data = $this->getData();
		$pageCount = $data->getPageCount();

		if (!empty ($rvrevids)) {
			if ($pageCount > 0)
				$this->dieUsage('The rvrevids= parameter may not be used with titles, pageids, and generator options.', 'rv_rvrevids');

			if ($enumRevMode)
				$this->dieUsage('The rvrevids= parameter may not be used with the list options (rvlimit, rvstartid, rvendid, dirNewer, rvstart, rvend).', 'rv_rvrevids');
		} else {
			if ($pageCount < 1)
				$this->dieUsage('No pages were given. Please use titles, pageids or a generator to provide page(s) to work on.', 'rv_no_pages');

			if ($enumRevMode && $pageCount > 1)
				$this->dieUsage('titles, pageids or a generator was used to supply multiple pages, but the rvlimit, rvstartid, rvendid, dirNewer, rvstart, and rvend parameters may only be used on a single page.', 'rv_multpages');
		}

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
						$this->dieDebug("unknown rvprop $prop");
				}
			}
		}

		$userMax = ($showContent ? 50 : 500);
		$botMax = ($showContent ? 200 : 10000);

		if ($enumRevMode) {

			if (isset ($rvstart))
				$conds[] = 'rev_timestamp >= ' . $this->prepareTimestamp($rvstart);
			if (isset ($rvend))
				$conds[] = 'rev_timestamp <= ' . $this->prepareTimestamp($rvend);

			// must manually initialize unset rvlimit
			if (!isset ($rvlimit))
				$rvlimit = 10;

			$options['ORDER BY'] = 'rev_timestamp' . ($dirNewer ? '' : ' DESC');

			$this->validateLimit('rvlimit', $rvlimit, 1, $userMax, $botMax);

			// There is only one ID
			$conds['rev_page'] = array_keys($data->getGoodTitles());

		} else {
			// When working in multi-page non-enumeration mode,
			// limit to the latest revision only
			$tables[] = 'page';
			$conds[] = 'page_id=rev_page';
			$conds[] = 'page_latest=rev_id';
			$this->validateLimit('page_count', $pageCount, 1, $userMax, $botMax);

			// Get all page IDs
			$conds['page_id'] = array_keys($data->getGoodTitles());
            
            $rvlimit = $pageCount; // assumption testing -- we should never get more then $pageCount rows.
		}

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
                    $this->dieDebug('Got more rows then expected'); // bug report
                
				$startStr = 'rvstartid=' . $row->rev_id;
				$msg = array (
					'continue' => $startStr
				);
				$this->getResult()->addMessage('query-status', 'revisions', $msg);
				break;
			}

			$vals = array (
				'revid' => intval($row->rev_id),
				'oldid' => intval($row->rev_text_id
			));

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
				$vals['xml:space'] = 'preserve';
				$vals['*'] = Revision :: getRevisionText($row);
			} else {
				$vals['*'] = ''; // Force all elements to be attributes
			}

			$data[$row->rev_page]['revisions']['_element'] = 'rv';
			$data[$row->rev_page]['revisions'][$row->rev_id] = $vals;
		}
		$db->freeResult($res);

		$this->getResult()->addMessage('query', 'allpages', $data);
	}

	protected function getAllowedParams() {
		return array (
			'rvrevids' => array (
				GN_ENUM_ISMULTI => true,
				GN_ENUM_TYPE => 'integer'
			),
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
		return 'module a';
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&prop=revisions&titles=ArticleA&rvprop=timestamp|user|comment|content'
		);
	}
}
?>