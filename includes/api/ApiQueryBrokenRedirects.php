<?php

/*
 * Created on Sep 25, 2006
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
 * Query module to enumerate all available pages.
 *
 * @ingroup API
 */
class ApiQueryBrokenRedirects extends ApiQueryGeneratorBase {
	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'br');
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator($resultPageSet) {
		if ($resultPageSet->isResolvingRedirects())
			$this->dieUsage('Use "gapfilterredir=nonredirects" option instead of "redirects" when using allpages as a generator', 'params');

		$this->run($resultPageSet);
	}

	private function run($resultPageSet = null) {
		$db = $this->getDB();
		$params = $this->extractRequestParams();
		list( $page, $redirect ) = $db->tableNamesN( 'page', 'redirect' );
		
		$this->addFields( array(
			"'BrokenRedirects' AS type",
			"p1.page_namespace AS namespace",
			"p1.page_title AS title",
			"p1.page_id AS pageid",
			"rd_namespace",
			"rd_title",
		));
		$this->addTables("redirect AS rd JOIN page p1 ON (rd.rd_from=p1.page_id) LEFT JOIN page AS p2 ON (rd_namespace=p2.page_namespace AND rd_title=p2.page_title )");
		# I don't know why these two not work ~~Alexsh
		#$this->addJoinConds(array("$page AS p1" => array('JOIN', 'rd.rd_from=p1.page_id')));
		#$this->addJoinConds(array("$page AS p2" => array('LEFT JOIN', 'rd_namespace=p2.page_namespace AND rd_title=p2.page_title')));
		$this->addWhere( array(
			"rd_namespace >= 0",
			"p2.page_namespace IS NULL",
		));

		$limit = $params['limit'];
		$this->addOption('LIMIT', $limit+1);
		if(!is_null($params['offset']))
			$this->addOption('OFFSET', $params['offset']);
		
		$res = $this->select(__METHOD__);
		$result = $this->getResult();
		$count = 0;
		while ($row = $db->fetchObject($res)) {
			if (++ $count > $limit) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				// TODO: Security issue - if the user has no right to view next title, it will still be shown
				$this->setContinueEnumParameter('offset', @$params['offset'] + $params['limit']);
				break;
			}
			if (is_null($resultPageSet)) {
				$title = Title :: makeTitle($row->page_namespace, $row->title);
				$rdtitle = Title :: makeTitle($row->page_namespace, $row->rd_title);
				$vals = array(
					'pageid' => intval($row->pageid),
					'ns' => intval($row->namespace),
					'title' => $title->getPrefixedText(),
					'target' => $rdtitle->getPrefixedText()
				);
				$fit = $result->addValue(array('query', $this->getModuleName()), null, $vals);
				if(!$fit)
				{
					$this->setContinueEnumParameter('offset', @$params['offset'] + $count - 1);
					break;
				}
			} else {
				$resultPageSet->processDbRow($row);
			}
		}
		$db->freeResult($res);

		if (is_null($resultPageSet)) {
			$result->setIndexedTagName_internal(array('query', $this->getModuleName()), 'p');
		}
	}

	public function getAllowedParams() {
		return array (
			'limit' => array(
				ApiBase :: PARAM_DFLT => 10,
				ApiBase :: PARAM_TYPE => 'limit',
				ApiBase :: PARAM_MIN => 1,
				ApiBase :: PARAM_MAX => ApiBase :: LIMIT_BIG1,
				ApiBase :: PARAM_MAX2 => ApiBase :: LIMIT_BIG2
			),
			'offset' => null,
		);
	}

	public function getParamDescription() {
		return array(
			'limit' => 'How many links to return',
			'offset' => 'When more results are available, use this to continue',
		);
	}

	public function getDescription() {
		return 'Enumerate all broken redirects';
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&list=brokenredirects',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id: ApiQueryBrokenRedirects.php 46845 2009-07-23 14:00:00Z alexsh $';
	}

}