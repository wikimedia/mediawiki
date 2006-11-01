<?php


/*
 * Created on Oct 16, 2006
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

class ApiQueryContributions extends ApiQueryBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'uc');
	}

	public function execute() {

		//Blank all our variables
		$limit = $user = $start = $end = $user = null;

		//Get our parameters out
		extract($this->extractRequestParams());

		//Get a database instance
		$db = & $this->getDB();

		$userid = $db->selectField('user', 'user_id', array (
			'user_name' => $user
		));
		if (!$userid)
			$this->dieUsage("User name $user not found", 'param_user');

		//Extract the table names, in case we have a prefix
		extract($db->tableNames( 'page', 'revision'), EXTR_PREFIX_ALL, 'tbl');

		$this->addOption('STRAIGHT_JOIN');

		$this->addTables("$tbl_revision LEFT OUTER JOIN $tbl_page ON " .
			"page_id=rev_page");

		$this->addFields(array('page_namespace', 'page_title', 'page_is_new',
			'rev_id', 'rev_text_id', 'rev_timestamp', 'rev_minor_edit',
				'rev_comment', 'rev_page'));

		$this->addWhereFld('rev_user_text', $user);
		$this->addWhereRange('rev_timestamp', $dir, $start, $end );

		$this->addOption('LIMIT', $limit + 1);

		$this->addOption('ORDER BY', 'rev_timestamp DESC');

		//Initialise some variables
		$data = array ();
		$count = 0;

		//Do the actual query.
		$res = $this->select( __METHOD__ );

		//Fetch each row
		while ( $row = $db->fetchObject( $res ) ) {
			if (++ $count > $limit) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter('start', ApiQueryBase :: keyToTitle($row->rev_timestamp));
				break;
			}

			//Add it to the $vals structure. I don't really understand this yet.
			$revvals = $this->addRowInfo('rev', $row);
			$pagevals = $this->addRowInfo('page', $row);

			if($revvals && !$pagevals)
				$data[] = $revvals;
			else if($pagevals && !$revvals)
				$data[] = $pagevals;
			else if($pagevals && $revvals)
				$data[] = array_merge($revvals, $pagevals);
		}

		//Free the database record so the connection can get on with other stuff
		$db->freeResult($res);

		$this->getResult()->setIndexedTagName($data, 'item');
		$this->getResult()->addValue('query', $this->getModuleName(), $data);
	}

	protected function getAllowedParams() {
		return array (
			'limit' => array (
				ApiBase :: PARAM_DFLT => 50,
				ApiBase :: PARAM_TYPE => 'limit',
				ApiBase :: PARAM_MIN => 1,
				ApiBase :: PARAM_MAX1 => ApiBase :: LIMIT_BIG1,
				ApiBase :: PARAM_MAX2 => ApiBase :: LIMIT_BIG2
			),
			'start' => array (
				ApiBase :: PARAM_TYPE => 'timestamp'
			),
			'end' => array (
				ApiBase :: PARAM_TYPE => 'timestamp'
			),
			'user' => null,
			'dir' => array (
				ApiBase :: PARAM_DFLT => 'older',
				ApiBase :: PARAM_TYPE => array (
					'newer',
					'older'
				)
			)
		);
	}

	protected function getParamDescription() {
		return array (
			'limit' => 'The maximum number of contributions to return.',
			'start' => 'The start timestamp to return from.',
			'end' => 'The end timestamp to return to.',
			'user' => 'The user to retrieve contributions for.',
			'dir' => 'The direction to search (older or newer).'
		);
	}

	protected function getDescription() {
		return 'Get edits by a user..';
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&list=usercontribs&ucuser=Werdna'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id: ApiQueryContributions.php 17335 2006-11-01 09:36:00Z Werdna $';
	}
}
?>
