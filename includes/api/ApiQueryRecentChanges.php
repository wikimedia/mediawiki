<?php


/*
 * Created on Oct 19, 2006
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

class ApiQueryRecentChanges extends ApiQueryBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'rc');
	}

	public function execute() {
		$limit = $prop = $from = $namespace = $hide = $dir = $start = $end = null;
		extract($this->extractRequestParams());

		$this->addTables('recentchanges');
		$this->addWhereRange('rc_timestamp', $dir, $start, $end);
		$this->addWhereFld('rc_namespace', $namespace);

		if (!is_null($hide)) {
			$hide = array_flip($hide);
			if(isset ($hide['anons']) && isset ($hide['liu'])) 
				$this->dieUsage( "Both 'anons' and 'liu' cannot be set at the same time", 'hide' );
			$this->addWhereIf('rc_minor = 0', isset ($hide['minor']));
			$this->addWhereIf('rc_bot = 0', isset ($hide['bots']));
			$this->addWhereIf('rc_user != 0', isset ($hide['anons']));
			$this->addWhereIf('rc_user = 0', isset ($hide['liu']));
		}

		$this->addFields(array (
			'rc_timestamp',
			'rc_namespace',
			'rc_title',
			'rc_cur_id',
			'rc_this_oldid',
			'rc_last_oldid',
			'rc_type',
			'rc_moved_to_ns',
			'rc_moved_to_title'
		));

		if (!is_null($prop)) {
			$prop = array_flip($prop);
			$this->addFieldsIf('rc_comment', isset ($prop['comment']));
			if (isset ($prop['user'])) {
				$this->addFields('rc_user');
				$this->addFields('rc_user_text');
			}
			if (isset ($prop['flags'])) {
				$this->addFields('rc_minor');
				$this->addFields('rc_bot');
				$this->addFields('rc_new');
			}
		}

		$this->addOption('LIMIT', $limit +1);

		$data = array ();
		$count = 0;
		$db = & $this->getDB();
		$res = $this->select(__METHOD__);
		while ($row = $db->fetchObject($res)) {
			if (++ $count > $limit) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter('start', $row->rc_timestamp);
				break;
			}

			$vals = $this->addRowInfo('rc', $row);
			if($vals)
				$data[] = $vals;
		}
		$db->freeResult($res);

		$result = $this->getResult();
		$result->setIndexedTagName($data, 'rc');
		$result->addValue('query', $this->getModuleName(), $data);
	}

	protected function getAllowedParams() {
		$namespaces = $this->getQuery()->getValidNamespaces();
		return array (
			'dir' => array (
				ApiBase :: PARAM_DFLT => 'older',
				ApiBase :: PARAM_TYPE => array (
					'newer',
					'older'
				)
			),
			'start' => array (
				ApiBase :: PARAM_TYPE => 'timestamp'
			),
			'end' => array (
				ApiBase :: PARAM_TYPE => 'timestamp'
			),
			'namespace' => array (
				ApiBase :: PARAM_DFLT => 0,
				ApiBase :: PARAM_TYPE => $namespaces
			),
			'prop' => array (
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => array (
					'user',
					'comment',
					'flags'
				)
			),
			'hide' => array (
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => array (
					'minor',
					'bots',
					'anons',
					'liu'
				)
			),
			'limit' => array (
				ApiBase :: PARAM_DFLT => 10,
				ApiBase :: PARAM_TYPE => 'limit',
				ApiBase :: PARAM_MIN => 1,
				ApiBase :: PARAM_MAX1 => ApiBase :: LIMIT_BIG1,
				ApiBase :: PARAM_MAX2 => ApiBase :: LIMIT_BIG2
			)
		);
	}

	protected function getParamDescription() {
		return array (
			'start' => 'The timestamp to start enumerating from.',
			'end' => 'The timestamp to end enumerating.',
			'limit' => 'How many total pages to return.'
		);
	}

	protected function getDescription() {
		return 'Enumerate recent changes';
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&list=recentchanges',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
?>