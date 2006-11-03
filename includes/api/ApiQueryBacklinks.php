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

	private $rootTitle, $contRedirs, $contLevel, $contTitle, $contID;

	// output element name, database column field prefix, database table 
	private $backlinksSettings = array (
		'backlinks' => array (
			'code' => 'bl',
			'prefix' => 'pl',
			'linktbl' => 'pagelinks'
		),
		'embeddedin' => array (
			'code' => 'ei',
			'prefix' => 'tl',
			'linktbl' => 'templatelinks'
		),
		'imagelinks' => array (
			'code' => 'il',
			'prefix' => 'il',
			'linktbl' => 'imagelinks'
		)
	);

	public function __construct($query, $moduleName) {
		$code = $prefix = $linktbl = null;
		extract($this->backlinksSettings[$moduleName]);

		parent :: __construct($query, $moduleName, $code);
		$this->bl_ns = $prefix . '_namespace';
		$this->bl_from = $prefix . '_from';
		$this->bl_tables = array (
			$linktbl,
			'page'
		);
		$this->bl_code = $code;

		$this->hasNS = $moduleName !== 'imagelinks';
		if ($this->hasNS) {
			$this->bl_title = $prefix . '_title';
			$this->bl_sort = "{$this->bl_ns}, {$this->bl_title}, {$this->bl_from}";
			$this->bl_fields = array (
				$this->bl_ns,
				$this->bl_title
			);
		} else {
			$this->bl_title = $prefix . '_to';
			$this->bl_sort = "{$this->bl_title}, {$this->bl_from}";
			$this->bl_fields = array (
				$this->bl_title
			);
		}
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

		$this->addFields($this->bl_fields);
		if (is_null($resultPageSet))
			$this->addFields(array (
				'page_id',
				'page_namespace',
				'page_title'
			));
		else
			$this->addFields($resultPageSet->getPageTableFields()); // will include page_id

		$this->addTables($this->bl_tables);
		$this->addWhere($this->bl_from . '=page_id');

		if ($this->hasNS)
			$this->addWhereFld($this->bl_ns, $this->rootTitle->getNamespace());
		$this->addWhereFld($this->bl_title, $this->rootTitle->getDBkey());
		$this->addWhereFld('page_namespace', $namespace);
		$this->addOption('LIMIT', $limit +1);
		$this->addOption('ORDER BY', $this->bl_sort);

		if ($redirect)
			$this->addWhereFld('page_is_redirect', 0);

		$db = & $this->getDB();
		if (!is_null($continue)) {
			$plfrm = intval($this->contID);
			if ($this->contLevel == 0) {
				// For the first level, there is only one target title, so no need for complex filtering
				$this->addWhere($this->bl_from . '>=' . $plfrm);
			} else {
				$ns = $this->contTitle->getNamespace();
				$t = $db->addQuotes($this->contTitle->getDBkey());
				$whereWithoutNS = "{$this->bl_title}>$t OR ({$this->bl_title}=$t AND {$this->bl_from}>=$plfrm))";

				if ($this->hasNS)
					$this->addWhere("{$this->bl_ns}>$ns OR ({$this->bl_ns}=$ns AND ($whereWithoutNS)");
				else
					$this->addWhere($whereWithoutNS);
			}
		}

		$res = $this->select(__METHOD__);

		$count = 0;
		$data = array ();
		while ($row = $db->fetchObject($res)) {
			if (++ $count > $limit) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				if ($redirect) {
					$ns = $row-> {
						$this->bl_ns };
					$t = $row-> {
						$this->bl_title };
					$continue = $this->getContinueRedirStr(false, 0, $ns, $t, $row->page_id);
				} else
					$continue = $this->getContinueStr($row->page_id);
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
			$result->setIndexedTagName($data, $this->bl_code);
			$result->addValue('query', $this->getModuleName(), $data);
		}
	}

	protected function processContinue($continue, $redirect) {
		$pageSet = $this->getPageSet();
		$count = $pageSet->getTitleCount();
		if (!is_null($continue)) {
			if ($count !== 0)
				$this->dieUsage("When continuing the {$this->getModuleName()} query, no other titles may be provided", 'titles_on_continue');
			$this->parseContinueParam($continue, $redirect);

			// Skip all completed links
			$db = & $this->getDB();

		} else {
			if ($count !== 1)
				$this->dieUsage("The {$this->getModuleName()} query requires one title to start", 'bad_title_count');
			$this->rootTitle = current($pageSet->getTitles()); // only one title there
		}

		// only image titles are allowed for the root 
		if (!$this->hasNS && $this->rootTitle->getNamespace() !== NS_IMAGE)
			$this->dieUsage("The title for {$this->getModuleName()} query must be an image", 'bad_image_title');
	}

	protected function parseContinueParam($continue, $redirect) {
		$continueList = explode('|', $continue);
		if ($redirect) {
			//
			// expected redirect-mode parameter:
			// ns|db_key|step|level|ns|db_key|id
			// ns+db_key -- the root title
			// step = 1 or 2 - which step to continue from - 1-titles, 2-redirects
			// level -- how many levels to follow before starting enumerating.
			// if level > 0 -- ns+title to continue from, otherwise skip these 
			// id = last page_id to continue from
			//
			if (count($continueList) > 4) {
				$rootNs = intval($continueList[0]);
				if (($rootNs !== 0 || $continueList[0] === '0') && !empty ($continueList[1])) {
					$this->rootTitle = Title :: makeTitleSafe($rootNs, $continueList[1]);
					if ($this->rootTitle && $this->rootTitle->userCanRead()) {

						$step = intval($continueList[2]);
						if ($step === 1 || $step === 2) {
							$this->contRedirs = ($step === 2);

							$level = intval($continueList[3]);
							if ($level !== 0 || $continueList[3] === '0') {
								$this->contLevel = $level;

								if ($level === 0) {
									if (count($continueList) === 5) {
										$contID = intval($continueList[4]);
										if ($contID !== 0 || $continueList[4] === '0') {
											$this->contID = $contID;
											return; // done
										}
									}
								} else {
									if (count($continueList) === 7) {
										$contNs = intval($continueList[4]);
										if (($contNs !== 0 || $continueList[4] === '0') && !empty ($continueList[5])) {
											$this->contTitle = Title :: makeTitleSafe($contNs, $continueList[5]);

											$contID = intval($continueList[6]);
											if ($contID !== 0 || $continueList[6] === '0') {
												$this->contID = $contID;
												return; // done
											}
										}
									}
								}
							}
						}
					}
				}
			}
		} else {
			//
			// expected non-redirect-mode parameter:
			// ns|db_key|id
			// ns+db_key -- the root title
			// id = last page_id to continue from
			//
			if (count($continueList) === 3) {
				$rootNs = intval($continueList[0]);
				if (($rootNs !== 0 || $continueList[0] === '0') && !empty ($continueList[1])) {
					$this->rootTitle = Title :: makeTitleSafe($rootNs, $continueList[1]);
					if ($this->rootTitle && $this->rootTitle->userCanRead()) {

						$contID = intval($continueList[2]);
						if ($contID !== 0) {
							$this->contID = $contID;
							return; // done
						}
					}
				}
			}
		}

		$this->dieUsage("Invalid continue param. You should pass the original value returned by the previous query", "_badcontinue");
	}

	protected function getContinueStr($lastPageID) {
		return $this->rootTitle->getNamespace() .
		'|' . $this->rootTitle->getDBkey() .
		'|' . $lastPageID;
	}

	protected function getContinueRedirStr($isRedirPhase, $level, $ns, $title, $lastPageID) {
		return $this->rootTitle->getNamespace() .
		'|' . $this->rootTitle->getDBkey() .
		'|' . ($isRedirPhase ? 1 : 2) .
		'|' . $level .
		 ($level > 0 ? ('|' . $ns . '|' . $title) : '') .
		'|' . $lastPageID;
	}

	protected function getAllowedParams() {

		return array (
			'continue' => null,
			'namespace' => array (
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => 'namespace'
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
		switch ($this->getModuleName()) {
			case 'backlinks' :
				return 'Find all pages that link to the given page';
			case 'embeddedin' :
				return 'Find all pages that embed (transclude) the given title';
			case 'imagelinks' :
				return 'Find all pages that use the given image title.';
			default :
				ApiBase :: dieDebug(__METHOD__, 'Unknown module name');
		}
	}

	protected function getExamples() {
		static $examples = array (
			'backlinks' => array (
				"api.php?action=query&list=backlinks&titles=Main%20Page",
				"api.php?action=query&generator=backlinks&titles=Main%20Page&prop=info"
			),
			'embeddedin' => array (
				"api.php?action=query&list=embeddedin&titles=Template:Stub",
				"api.php?action=query&generator=embeddedin&titles=Template:Stub&prop=info"
			),
			'imagelinks' => array (
				"api.php?action=query&list=imagelinks&titles=Image:Albert%20Einstein%20Head.jpg",
				"api.php?action=query&generator=imagelinks&titles=Image:Albert%20Einstein%20Head.jpg&prop=info"
			)
		);

		return $examples[$this->getModuleName()];
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
?>