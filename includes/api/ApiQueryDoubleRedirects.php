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
 * Query module to enumerate double redirect pages.
 *
 * @ingroup API
 */
class ApiQueryDoubleRedirects extends ApiQueryGeneratorBase {
	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'do');
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
			"pa.page_namespace as namespace",
			"pa.page_title as title",
			"pa.page_id as pageid",
			"pb.page_namespace as nsb",
			"pb.page_title as tb",
			"pb.page_id as idb",
			"pc.page_namespace as nsc",
			"pc.page_title as tc",
			"pc.page_id as idc",
			)
		);
		$this->addTables($redirect, 'ra');
		$this->addTables($redirect, 'rb');
		$this->addTables($page, 'pa');
		$this->addTables($page, 'pb');
		$this->addTables($page, 'pc');
		$this->addWhere(array(
			"ra.rd_from=pa.page_id",
			"ra.rd_namespace=pb.page_namespace",
			"ra.rd_title=pb.page_title",
			"rb.rd_from=pb.page_id",
			"rb.rd_namespace=pc.page_namespace",
			"rb.rd_title=pc.page_title"
			)
		);
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
				$titleB = Title :: makeTitle($row->page_nsb, $row->tb);
				$titleC = Title :: makeTitle($row->page_nsc, $row->tc);
				$vals = array(
					'pageid' => $row->pageid,
					'ns' => intval($row->namespace),
					'title' => $title->getPrefixedText(),
					'idb' => $row->idb,
					'nsb' => intval($row->nsb),
					'tb' => $titleB->getPrefixedText(),
					'idc' => $row->idc,
					'nsc' => intval($row->nsc),
					'tc' => $titleC->getPrefixedText(),
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
		return 'Enumerate all double redirects';
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&list=doubleredirects',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id: ApiQueryDoubleRedirects.php 46845 2009-07-24 14:00:00Z alexsh $';
	}

}