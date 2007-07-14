<?php

/*
 * Created on Oct 19, 2006
 *
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2006 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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
 * A query action to enumerate the recent changes that were done to the wiki.
 * Various filters are supported.
 * 
 * @addtogroup API
 */
class ApiQueryRecentChanges extends ApiQueryBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'rc');
	}

	private $fld_comment = false, $fld_user = false, $fld_flags = false,
			$fld_timestamp = false, $fld_title = false, $fld_ids = false,
			$fld_sizes = false;
	 
	public function execute() {
		$limit = $prop = $namespace = $show = $dir = $start = $end = null;
		extract($this->extractRequestParams());

		$this->addTables('recentchanges');
		$this->addWhereRange('rc_timestamp', $dir, $start, $end);
		$this->addWhereFld('rc_namespace', $namespace);
		$this->addWhereFld('rc_deleted', 0);

		if (!is_null($show)) {
			$show = array_flip($show);
			if ((isset ($show['minor']) && isset ($show['!minor'])) || (isset ($show['bot']) && isset ($show['!bot'])) || (isset ($show['anon']) && isset ($show['!anon'])))
				$this->dieUsage("Incorrect parameter - mutually exclusive values may not be supplied", 'show');

			$this->addWhereIf('rc_minor = 0', isset ($show['!minor']));
			$this->addWhereIf('rc_minor != 0', isset ($show['minor']));
			$this->addWhereIf('rc_bot = 0', isset ($show['!bot']));
			$this->addWhereIf('rc_bot != 0', isset ($show['bot']));
			$this->addWhereIf('rc_user = 0', isset ($show['anon']));
			$this->addWhereIf('rc_user != 0', isset ($show['!anon']));
		}

		$this->addFields(array (
			'rc_timestamp',
			'rc_namespace',
			'rc_title',
			'rc_type',
			'rc_moved_to_ns',
			'rc_moved_to_title'
		));

		if (!is_null($prop)) {
			$prop = array_flip($prop);

			$this->fld_comment = isset ($prop['comment']);
			$this->fld_user = isset ($prop['user']);
			$this->fld_flags = isset ($prop['flags']);
			$this->fld_timestamp = isset ($prop['timestamp']);
			$this->fld_title = isset ($prop['title']);
			$this->fld_ids = isset ($prop['ids']);
			$this->fld_sizes = isset ($prop['sizes']);
			 
			$this->addFieldsIf('rc_cur_id', $this->fld_ids);			
			$this->addFieldsIf('rc_this_oldid', $this->fld_ids);			
			$this->addFieldsIf('rc_last_oldid', $this->fld_ids);			
			$this->addFieldsIf('rc_comment', $this->fld_comment);			
			$this->addFieldsIf('rc_user', $this->fld_user);
			$this->addFieldsIf('rc_user_text', $this->fld_user);
			$this->addFieldsIf('rc_minor', $this->fld_flags);
			$this->addFieldsIf('rc_bot', $this->fld_flags);
			$this->addFieldsIf('rc_new', $this->fld_flags);
			$this->addFieldsIf('rc_old_len', $this->fld_sizes);
			$this->addFieldsIf('rc_new_len', $this->fld_sizes);
		}

		$this->addOption('LIMIT', $limit +1);
		$this->addOption('USE INDEX', 'rc_timestamp');

		$data = array ();
		$count = 0;
		$db = $this->getDB();
		$res = $this->select(__METHOD__);
		
		while ($row = $db->fetchObject($res)) {
			if (++ $count > $limit) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter('start', wfTimestamp(TS_ISO_8601, $row->rc_timestamp));
				break;
			}

			$vals = $this->extractRowInfo($row);
			if($vals)
				$data[] = $vals;
		}
		$db->freeResult($res);

		$result = $this->getResult();
		$result->setIndexedTagName($data, 'rc');
		$result->addValue('query', $this->getModuleName(), $data);
	}

	private function extractRowInfo($row) {
		$movedToTitle = false;
		if (!empty($row->rc_moved_to_title))
			$movedToTitle = Title :: makeTitle($row->rc_moved_to_ns, $row->rc_moved_to_title);

		$title = Title :: makeTitle($row->rc_namespace, $row->rc_title);
		$vals = array ();

		$vals['type'] = intval($row->rc_type);

		if ($this->fld_title) {
			ApiQueryBase :: addTitleInfo($vals, $title);
			if ($movedToTitle)
				ApiQueryBase :: addTitleInfo($vals, $movedToTitle, "new_");
		}

		if ($this->fld_ids) {
			$vals['pageid'] = intval($row->rc_cur_id);
			$vals['revid'] = intval($row->rc_this_oldid);
			$vals['old_revid'] = intval( $row->rc_last_oldid );
		}

		if ($this->fld_user) {
			$vals['user'] = $row->rc_user_text;
			if(!$row->rc_user)
				$vals['anon'] = '';
		}

		if ($this->fld_flags) {
			if ($row->rc_bot)
				$vals['bot'] = '';
			if ($row->rc_new)
				$vals['new'] = '';
			if ($row->rc_minor)
				$vals['minor'] = '';
		}

		if ($this->fld_sizes) {
			$vals['oldlen'] = intval($row->rc_old_len);
			$vals['newlen'] = intval($row->rc_new_len);
		}
		
		if ($this->fld_timestamp)
			$vals['timestamp'] = wfTimestamp(TS_ISO_8601, $row->rc_timestamp);

		if ($this->fld_comment && !empty ($row->rc_comment)) {
			$vals['comment'] = $row->rc_comment;
		}

		return $vals;
	}

	protected function getAllowedParams() {
		return array (
			'start' => array (
				ApiBase :: PARAM_TYPE => 'timestamp'
			),
			'end' => array (
				ApiBase :: PARAM_TYPE => 'timestamp'
			),
			'dir' => array (
				ApiBase :: PARAM_DFLT => 'older',
				ApiBase :: PARAM_TYPE => array (
					'newer',
					'older'
				)
			),
			'namespace' => array (
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => 'namespace'
			),
			'prop' => array (
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_DFLT => 'title|timestamp|ids',
				ApiBase :: PARAM_TYPE => array (
					'user',
					'comment',
					'flags',
					'timestamp',
					'title',
					'ids',
					'sizes'
				)
			),
			'show' => array (
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => array (
					'minor',
					'!minor',
					'bot',
					'!bot',
					'anon',
					'!anon'
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
			'start' => 'The timestamp to start enumerating from.',
			'end' => 'The timestamp to end enumerating.',
			'dir' => 'In which direction to enumerate.',
			'namespace' => 'Filter log entries to only this namespace(s)',
			'prop' => 'Include additional pieces of information',
			'show' => array (
				'Show only items that meet this criteria.',
				'For example, to see only minor edits done by logged-in users, set show=minor|!anon'
			),
			'limit' => 'How many total pages to return.'
		);
	}

	protected function getDescription() {
		return 'Enumerate recent changes';
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&list=recentchanges'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}

