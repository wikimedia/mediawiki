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
 * A query action to return meta information about the wiki site.
 * 
 * @addtogroup API
 */
class ApiQuerySiteinfo extends ApiQueryBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'si');
	}

	public function execute() {

		$params = $this->extractRequestParams();

		foreach ($params['prop'] as $p) {
			switch ($p) {
				default :
					ApiBase :: dieDebug(__METHOD__, "Unknown prop=$p");
				case 'general' :
					$this->appendGeneralInfo($p);
					break;
				case 'namespaces' :
					$this->appendNamespaces($p);
					break;
				case 'interwikimap' :
					$filteriw = isset($params['filteriw']) ? $params['filteriw'] : false; 
					$this->appendInterwikiMap($p, $filteriw);
					break;
				case 'dbrepllag' :
					$this->appendDbReplLagInfo($p, $params['showalldb']);
					break;
			}
		}
	}

	protected function appendGeneralInfo($property) {
		global $wgSitename, $wgVersion, $wgCapitalLinks, $wgRightsCode, $wgRightsText, $wgLanguageCode;
		
		$data = array ();
		$mainPage = Title :: newFromText(wfMsgForContent('mainpage'));
		$data['mainpage'] = $mainPage->getText();
		$data['base'] = $mainPage->getFullUrl();
		$data['sitename'] = $wgSitename;
		$data['generator'] = "MediaWiki $wgVersion";
		$data['case'] = $wgCapitalLinks ? 'first-letter' : 'case-sensitive'; // 'case-insensitive' option is reserved for future
		if (isset($wgRightsCode))
			$data['rightscode'] = $wgRightsCode;
		$data['rights'] = $wgRightsText;
		$data['lang'] = $wgLanguageCode;
		
		$this->getResult()->addValue('query', $property, $data);
	}
	
	protected function appendNamespaces($property) {
		global $wgContLang;
		
		$data = array ();
		foreach ($wgContLang->getFormattedNamespaces() as $ns => $title) {
			$data[$ns] = array (
				'id' => $ns
			);
			ApiResult :: setContent($data[$ns], $title);
		}
		
		$this->getResult()->setIndexedTagName($data, 'ns');
		$this->getResult()->addValue('query', $property, $data);
	}
	
	protected function appendInterwikiMap($property, $filter) {

		$this->addTables('interwiki');
		$this->addFields(array('iw_prefix', 'iw_local', 'iw_url'));

		if($filter === 'local')
			$this->addWhere('iw_local = 1');
		else if($filter === '!local')
			$this->addWhere('iw_local = 0');
		else if($filter !== false)
			ApiBase :: dieDebug(__METHOD__, "Unknown filter=$filter");

		$this->addOption('ORDER BY', 'iw_prefix');
		
		$db = $this->getDB();
		$res = $this->select(__METHOD__);

		$data = array();
		while($row = $db->fetchObject($res))
		{
			$val['prefix'] = $row->iw_prefix;
			if ($row->iw_local == '1')
				$val['local'] = '';
//			$val['trans'] = intval($row->iw_trans);	// should this be exposed?
			$val['url'] = $row->iw_url;
				
			$data[] = $val;
		}
		$db->freeResult($res);
		
		$this->getResult()->setIndexedTagName($data, 'iw');
		$this->getResult()->addValue('query', $property, $data);
	}
	
	protected function appendDbReplLagInfo($property, $includeAll) {
		global $wgLoadBalancer;

		$data = array();
		
		if ($includeAll) {
			global $wgDBservers;
			$lags = $wgLoadBalancer->getLagTimes();
			foreach( $lags as $i => $lag ) {
				$data[] = array (
					'host' => $wgDBservers[$i]['host'],
					'lag' => $lag);
			}
		} else {
			list( $host, $lag ) = $wgLoadBalancer->getMaxLag();
			$data[] = array (
				'host' => $host,
				'lag' => $lag);
		}					

		$result = $this->getResult();
		$result->setIndexedTagName($data, 'db');
		$result->addValue('query', $property, $data);
	}	

	protected function getAllowedParams() {
		return array (
		
			'prop' => array (
				ApiBase :: PARAM_DFLT => 'general',
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => array (
					'general',
					'namespaces',
					'interwikimap',
					'dbrepllag',
				)),

			'filteriw' => array (
				ApiBase :: PARAM_TYPE => array (
					'local',
					'!local',
				)),
				
			'showalldb' => false,
		);
	}

	protected function getParamDescription() {
		return array (
			'prop' => array (
				'Which sysinfo properties to get:',
				' "general"      - Overall system information',
				' "namespaces"   - List of registered namespaces (localized)',
				' "interwikimap" - Return interwiki map (optionally filtered)',
				' "dbrepllag"    - Returns DB server with the highest replication lag',
			),
			'filteriw' =>  'Return only local or only nonlocal entries of the interwiki map',
			'showalldb' => 'List all DB servers, not just the one lagging the most',
		);
	}

	protected function getDescription() {
		return 'Return general information about the site.';
	}

	protected function getExamples() {
		return array(
			'api.php?action=query&meta=siteinfo&siprop=general|namespaces',
			'api.php?action=query&meta=siteinfo&siprop=interwikimap&sifilteriw=local',
			'api.php?action=query&meta=siteinfo&siprop=dbrepllag&sishowalldb',
			);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}

