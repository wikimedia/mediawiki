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
		$rvlimit = $rvstartid = $rvendid = $rvstart = $rvend = $rvdir = $rvprop = null;
		extract($this->extractRequestParams());

		//
		// Parameter validation
		//

		// true when ordered by timestamp from older to newer, false otherwise
		$dirNewer = ($rvdir === 'newer');

		// If any of those parameters are used, we work with single page only
		$singePageMode = ($rvlimit !== 0 || $rvstartid !== 0 || $rvendid !== 0 || $dirNewer || isset ($rvstart) || isset ($rvend));

		if ($rvstartid !== 0 || $rvendid !== 0)
			$this->dieUsage('rvstartid/rvendid not implemented', 'notimplemented');

		$data = $this->getData();
		$pageCount = $data->getPageCount();
		if ($singePageMode && $pageCount > 1)
			$this->dieUsage('You have supplied multiple pages, but the specified revisions parameters may only be used with one page.', 'rv_multpages');

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
						break;
					default :
						$this->dieDebug("unknown rvprop $prop");
				}
			}
		}

		if (isset ($rvstart))
			$conds[] = 'rev_timestamp >= ' . $this->prepareTimestamp($rvstart);
		if (isset ($rvend))
			$conds[] = 'rev_timestamp <= ' . $this->prepareTimestamp($rvend);

		if ($singePageMode) {
			if (!isset ($rvlimit))
				$rvlimit = 10;

			$options['LIMIT'] = $rvlimit + 1;
			$options['ORDER BY'] = 'rev_timestamp' . ($dirNewer ? '' : ' DESC');
			
			// get the first (and only) pageid => title pair
			foreach($data->getGoodTitles() as $pageId => $titleObj) {
				$conds['rev_page'] = $pageId;
				break;
			}
		}

		$db = $this->getDB();
		$this->profileDBIn();
		$res = $db->select($tables, $fields, $conds, __CLASS__ . '::' . __FUNCTION__, $options);
		$this->profileDBOut();

		$data = array ();
		$count = 0;
		while ($row = $db->fetchObject($res)) {

			if (++ $count > $rvlimit) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$startStr = 'rvstartid=' . $row->rev_id;
				$msg = array ('continue' => $startStr );
				$this->getResult()->addMessage('query-status', 'revisions', $msg);
				break;
			}


			$revid = intval($row->rev_id);
			$pageid = intval($row->rev_page);

			$vals = array (
				'revid' => $revid,
				'oldid' => intval($row->rev_text_id
			));

			if( $row->rev_minor_edit ) {
				$vals['minor'] = '';
			}
	
			if ($showTimestamp)
				$vals['timestamp'] = wfTimestamp(TS_ISO_8601, $row->rev_timestamp);
	
			if ($showUser) {
				$vals['user'] = $row->rev_user_text;
				if( !$row->rev_user )
					$vals['anon'] = '';
			}

			if ($showComment)
				$vals['comment'] = $row->rev_comment;

			if ($showContent) {
				$vals['xml:space'] = 'preserve';
				$vals['*'] = Revision::getRevisionText( $row );
			} else {
				$vals['*'] = '';	// Force all elements to be attributes
			}

			$data[$pageid]['revisions']['_element'] = 'rv';
			$data[$pageid]['revisions'][$revid] = $vals;
		}
		$db->freeResult($res);

		$this->getResult()->addMessage('query', 'allpages', $data);
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
		return 'module a';
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&prop=revisions&titles=ArticleA&rvprop=timestamp|user|comment|content'
		);
	}
}
?>