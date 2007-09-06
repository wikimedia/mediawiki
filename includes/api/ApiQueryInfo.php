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
 * A query module to show basic page information.
 * 
 * @addtogroup API
 */
class ApiQueryInfo extends ApiQueryBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'in');
	}

	public function requestExtraData($pageSet) {
		$pageSet->requestField('page_is_redirect');
		$pageSet->requestField('page_is_new');
		$pageSet->requestField('page_counter');
		$pageSet->requestField('page_touched');
		$pageSet->requestField('page_latest');
		$pageSet->requestField('page_len');
	}

	public function execute() {

		global $wgUser;

		$params = $this->extractRequestParams();
		$fld_protection = false;
		if(!is_null($params['prop'])) {
			$prop = array_flip($params['prop']);
			$fld_protection = isset($prop['protection']);
		}
		if(!is_null($params['token'])) {
			$token = $params['token'];
			$tok_edit = $this->getTokenFlag($token, 'edit');
			$tok_delete = $this->getTokenFlag($token, 'delete');
			$tok_protect = $this->getTokenFlag($token, 'protect');
			$tok_move = $this->getTokenFlag($token, 'move');
		}
		
		$pageSet = $this->getPageSet();
		$titles = $pageSet->getGoodTitles();
		$result = $this->getResult();

		$pageIsRedir = $pageSet->getCustomField('page_is_redirect');
		$pageIsNew = $pageSet->getCustomField('page_is_new');
		$pageCounter = $pageSet->getCustomField('page_counter');
		$pageTouched = $pageSet->getCustomField('page_touched');
		$pageLatest = $pageSet->getCustomField('page_latest');
		$pageLength = $pageSet->getCustomField('page_len');

		if ($fld_protection && count($titles) > 0) {
			$this->addTables('page_restrictions');
			$this->addFields(array('pr_page', 'pr_type', 'pr_level', 'pr_expiry'));
			$this->addWhereFld('pr_page', array_keys($titles));

			$db = $this->getDB();
			$res = $this->select(__METHOD__);
			while($row = $db->fetchObject($res)) {
				$protections[$row->pr_page][] = array(
								'type' => $row->pr_type,
								'level' => $row->pr_level,
								'expiry' => Block::decodeExpiry( $row->pr_expiry, TS_ISO_8601 )
							);
			}
			$db->freeResult($res);
		}
		
		foreach ( $titles as $pageid => $title ) {
			$pageInfo = array (
				'touched' => wfTimestamp(TS_ISO_8601, $pageTouched[$pageid]),
				'lastrevid' => intval($pageLatest[$pageid]),
				'counter' => intval($pageCounter[$pageid]),
				'length' => intval($pageLength[$pageid]),
			);

			if ($pageIsRedir[$pageid])
				$pageInfo['redirect'] = '';

			if ($pageIsNew[$pageid])
				$pageInfo['new'] = '';

			if (!is_null($token)) {
				// Currently all tokens are generated the same way, but it might change
				if ($tok_edit)
					$pageInfo['edittoken'] = $wgUser->editToken();
				if ($tok_delete)
					$pageInfo['deletetoken'] = $wgUser->editToken();
				if ($tok_protect)
					$pageInfo['protecttoken'] = $wgUser->editToken();
				if ($tok_move)
					$pageInfo['movetoken'] = $wgUser->editToken();
			}
			
			if($fld_protection) {
				if (isset($protections[$pageid])) {
					$pageInfo['protection'] = $protections[$pageid];
					$result->setIndexedTagName($pageInfo['protection'], 'pr');
				} else {
					$pageInfo['protection'] = array();
				}
			}

			$result->addValue(array (
				'query',
				'pages'
			), $pageid, $pageInfo);
		}

		// Get edit tokens for missing titles if requested
		// Delete, protect and move tokens are N/A for missing titles anyway
		if($tok_edit)
		{
			$missing = $pageSet->getMissingTitles();
			$res = &$result->getData();
			foreach($missing as $pageid => $title)
				$res['query']['pages'][$pageid]['edittoken'] = $wgUser->editToken();
		}
	}

	protected function getAllowedParams() {
		return array (
			'prop' => array (
				ApiBase :: PARAM_DFLT => NULL,
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => array (
					'protection'
				)),
			'token' => array (
				ApiBase :: PARAM_DFLT => NULL,
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => array (
					'edit',
					'delete',
					'protect',
					'move',
				)),
		);
	}

	protected function getParamDescription() {
		return array (
			'prop' => array (
				'Which additional properties to get:',
				' "protection"   - List the protection level of each page'
			),
			'token' => 'Request a token to perform a data-modifying action on a page',
		);
	}


	protected function getDescription() {
		return 'Get basic page information such as namespace, title, last touched date, ...';
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&prop=info&titles=Main%20Page',
			'api.php?action=query&prop=info&inprop=protection&titles=Main%20Page'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}

