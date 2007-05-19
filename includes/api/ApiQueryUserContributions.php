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

/**
 * @addtogroup API
 */
class ApiQueryContributions extends ApiQueryBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'uc');
	}

	public function execute() {

		$this->selectNamedDB('contributions', DB_SLAVE, 'contributions');

		//Blank all our variables
		$limit = $user = $start = $end = $dir = null;

		//Get our parameters out
		extract($this->extractRequestParams());

		//Get a database instance
		$db = $this->getDB();

		if (is_null($user))
			$this->dieUsage("User parameter may not be empty", 'param_user');

		//Get the table names
		list ($tbl_page, $tbl_revision) = $db->tableNamesN('page', 'revision');

		//We're after the revision table, and the corresponding page row for
		//anything we retrieve.
		$this->addTables("$tbl_revision LEFT OUTER JOIN $tbl_page ON " .
			"page_id=rev_page");

		//We want to know the namespace, title, new-ness, and ID of a page,
		// and the id, text-id, timestamp, minor-status, summary and page
		// of a revision.
		$this->addFields(array('page_namespace', 'page_title', 'page_is_new',
			'rev_id', 'rev_text_id', 'rev_timestamp', 'rev_minor_edit',
				'rev_comment', 'rev_page'));

		// We only want pages by the specified user.
		$this->addWhereFld('rev_user_text', $user);
		// ... and in the specified timeframe.
		$this->addWhereRange('rev_timestamp', $dir, $start, $end );

		$this->addOption('LIMIT', $limit + 1);

		//Initialise some variables
		$data = array ();
		$count = 0;

		//Do the actual query.
		$res = $this->select( __METHOD__ );

		//Fetch each row
		while ( $row = $db->fetchObject( $res ) ) {
			if (++ $count > $limit) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter('start', $row->rev_timestamp);
				break;
			}

			//There's a fancy function in ApiQueryBase that does
			// most of the work for us. Use that for the page
			// and revision.
			$revvals = $this->addRowInfo('rev', $row);
			$pagevals = $this->addRowInfo('page', $row);

			//If we got data on the revision only, use only
			// that data.
			if($revvals && !$pagevals) {
				$data[] = $revvals;
			}
			//If we got data on the page only, use only
			// that data.
			else if($pagevals && !$revvals) {
				$data[] = $pagevals;
			}
			//... and if we got data on both the revision and
			// the page, merge the data and send it out.
			else if($pagevals && $revvals) {
				$data[] = array_merge($revvals, $pagevals);
			}
		}

		//Free the database record so the connection can get on with other stuff
		$db->freeResult($res);

		//And send the whole shebang out as output.
		$this->getResult()->setIndexedTagName($data, 'item');
		$this->getResult()->addValue('query', $this->getModuleName(), $data);
	}

	protected function getAllowedParams() {
		return array (
			'limit' => array (
				ApiBase :: PARAM_DFLT => 10,
				ApiBase :: PARAM_TYPE => 'limit',
				ApiBase :: PARAM_MIN => 1,
				ApiBase :: PARAM_MAX => ApiBase :: LIMIT_BIG1,
				ApiBase :: PARAM_MAX2 => ApiBase :: LIMIT_BIG2
			),
			'start' => array (
				ApiBase :: PARAM_TYPE => 'timestamp'
			),
			'end' => array (
				ApiBase :: PARAM_TYPE => 'timestamp'
			),
			'user' => array (
				ApiBase :: PARAM_TYPE => 'user'
			),
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
			'api.php?action=query&list=usercontribs&ucuser=YurikBot'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
?>
