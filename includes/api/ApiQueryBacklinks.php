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
	require_once ("ApiQueryBase.php");
}

class ApiQueryBacklinks extends ApiQueryGeneratorBase {

	private $rootTitle;

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'bl');
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator($resultPageSet) {
		$this->run($resultPageSet);
	}

	private function run($resultPageSet = null) {
		$continue = $namespace = $redirect = $limit = null;
		extract($this->extractRequestParams());

		if ($redirect)
			$this->dieDebug('Redirect is not yet been implemented', 'notimplemented');

		$this->processContinue($continue, $redirect);

		if (is_null($resultPageSet)) {
			$this->addFields( array (
				'page_id',
				'page_namespace',
				'page_title'
			));
		} else {
			$this->addFields( $resultPageSet->getPageTableFields());
		}

		$this->addTables(array (
			'pagelinks',
			'page'
		));
		$this->addWhere('pl_from=page_id');

		$this->addWhereFld('pl_namespace', $this->rootTitle->getNamespace());
		$this->addWhereFld('pl_title', $this->rootTitle->getDBkey());
		$this->addWhereFld('page_namespace', $namespace);
		$this->addOption('LIMIT', $limit +1);
		$this->addOption('ORDER BY', 'page_namespace, page_title');

		if ($redirect)
			$this->addWhereFld('page_is_redirect', 0);

		$db = & $this->getDB();
		if(!is_null($this->fromTitle)) {
			$ns = $this->fromTitle->getNamespace();
			$t = $db->addQuotes($this->fromTitle->getDBkey());
			$this->addWhere("(page_namespace>$ns OR (page_namespace=$ns AND page_title>=$t))");
		}
		
		$res = $this->select(__METHOD__);

		$count = 0;
		$data = array ();
		while ($row = $db->fetchObject($res)) {
			if (++ $count > $limit) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$continue = $this->rootTitle->getNamespace() . '|' . $this->rootTitle->getDBkey() . '|1|0|' . $row->page_namespace . '|' . $row->page_title;
				$this->setContinueEnumParameter('continue', $continue);
				break;
			}

			if (is_null($resultPageSet)) {
				$vals = $this->addRowInfo('page', $row);
				if ($vals)
					$data[intval($row->page_id)] = $vals;
			} else {
				$resultPageSet->processDbRow($row);
			}
		}
		$db->freeResult($res);

		if (is_null($resultPageSet)) {
			$result = $this->getResult();
			$result->setIndexedTagName($data, 'bl');
			$result->addValue('query', $this->getModuleName(), $data);
		}
	}

	protected function processContinue($continue, $redirect) {
		$pageSet = $this->getPageSet();
		$count = $pageSet->getGoodTitleCount();
		if (!is_null($continue)) {
			if ($count !== 0)
				$this->dieUsage('When continuing the backlink query, no other titles may be provided', 'titles_on_continue');
			$this->parseContinueParam($continue, $redirect);

			// Skip all completed links
			$db = & $this->getDB();

		} else {
			if ($count !== 1)
				$this->dieUsage('Backlinks requires one title to start the query', 'bad_title_count');
			$this->rootTitle = array_pop($pageSet->getGoodTitles()); // only one title there			
			$this->contFromRedirs = false;
			$this->contFromLevel = 0;
			// $this->contFromPageId = 0;
			$this->fromTitle = null;
		}
	}

	protected function parseContinueParam($continue, $redirect) {
		//
		// the parameter will be in the format:
		// ns|db_key|step|level|ns|db_key
		// ns+db_key -- the root title
		// step = 1 or 2 - which step to continue from - 1-titles, 2-redirects
		// level -- how many levels to follow before starting enumerating.
		// ns+title to continue from 
		//
		$continueList = explode('|', $continue);
		if (count($continueList) === 6) {
			$rootNs = intval($continueList[0]);
			if (($rootNs !== 0 || $continueList[0] === '0') && !empty ($continueList[1])) {
				$this->rootTitle = Title :: makeTitleSafe($rootNs, $continueList[1]);
				if ($this->rootTitle && $this->rootTitle->userCanRead()) {

					$step = intval($continueList[2]);
					if ($step === 1 || $step === 2) {
						$this->continueRedirs = ($step === 2);

						$level = intval($continueList[3]);
						if ($level !== 0 || $continueList[3] === '0') {
							$this->continueLevel = $level;

//							$pageid = intval($continueList[4]);
//							if ($pageid !== 0 || $continueList[4] === '0') {
//								$this->continuePageId = $pageid;

							$fromNs = intval($continueList[4]);
							if (($fromNs !== 0 || $continueList[4] === '0') && !empty ($continueList[5])) {
								$this->fromTitle = Title :: makeTitleSafe($fromNs, $continueList[5]);

								// When not processing redirects, only page through the non-redirects 
								// if ($redirect || ($step === 1 && $level === 0 && $pageid > 0)) {

								if ($redirect || ($step === 1 && $level === 0 && $this->fromTitle && $this->fromTitle->userCanRead())) {
									return; // done
								}
							}
						}
					}
				}
			}
		}

		$this->dieUsage("Invalid continue param. You should pass the original value returned by the previous query", "_badcontinue");
	}

	protected function getAllowedParams() {

		$namespaces = $this->getQuery()->getValidNamespaces();
		return array (
			'continue' => null,
			'namespace' => array (
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => $namespaces
			),
			'redirect' => false,
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
			'continue' => 'When more results are available, use this to continue.',
			'namespace' => 'The namespace to enumerate.',
			'redirect' => 'If linking page is a redirect, find all pages that link to that redirect (not implemented)',
			'limit' => 'How many total pages to return.'
		);
	}

	protected function getDescription() {
		return 'Find all pages that link to the given page';
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&list=backlinks&titles=Main%20Page',
			'api.php?action=query&generator=backlinks&titles=Main%20Page&prop=info',
			
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
?>