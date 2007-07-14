<?php

/*
 * Created on July 7, 2007
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
 * @addtogroup API
 */
class ApiQueryExtLinksUsage extends ApiQueryGeneratorBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'eu');
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator($resultPageSet) {
		$this->run($resultPageSet);
	}

	private function run($resultPageSet = null) {

		$params = $this->extractRequestParams();

		$protocol = $params['protocol'];
		$query = $params['query'];
		if (is_null($query))
			$this->dieUsage('Missing required query parameter', 'params');
		
		// Find the right prefix
		global $wgUrlProtocols;
		foreach ($wgUrlProtocols as $p) {
			if( substr( $p, 0, strlen( $protocol ) ) === $protocol ) {
				$protocol = $p;
				break;
			}
		}
		
		$likeQuery = LinkFilter::makeLike($query , $protocol);
		if (!$likeQuery)
			$this->dieUsage('Invalid query', 'bad_query');
		$likeQuery = substr($likeQuery, 0, strpos($likeQuery,'%')+1);

		$this->addTables(array('page','externallinks'));	// must be in this order for 'USE INDEX' 
		$this->addOption('USE INDEX', 'el_index');

		$db = $this->getDB();
		$this->addWhere('page_id=el_from');
		$this->addWhere('el_index LIKE ' . $db->addQuotes( $likeQuery ));
		$this->addWhereFld('page_namespace', $params['namespace']);

		$prop = array_flip($params['prop']);
		$fld_ids = isset($prop['ids']);
		$fld_title = isset($prop['title']);
		$fld_url = isset($prop['url']);
		
		if (is_null($resultPageSet)) {
			$this->addFields(array (
				'page_id',
				'page_namespace',
				'page_title'
			));
			$this->addFieldsIf('el_to', $fld_url);			
		} else {
			$this->addFields($resultPageSet->getPageTableFields());
		}

		$limit = $params['limit'];
		$offset = $params['offset'];
		$this->addOption('LIMIT', $limit +1);
		if (isset ($offset))
			$this->addOption('OFFSET', $offset);

		$res = $this->select(__METHOD__);

		$data = array ();
		$count = 0;
		while ($row = $db->fetchObject($res)) {
			if (++ $count > $limit) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter('offset', $offset+$limit+1);
				break;
			}

			if (is_null($resultPageSet)) {
				$vals = array();
				if ($fld_ids)
					$vals['pageid'] = intval($row->page_id);
				if ($fld_title) {
					$title = Title :: makeTitle($row->page_namespace, $row->page_title);
					$vals['ns'] = intval($title->getNamespace());
					$vals['title'] = $title->getPrefixedText();
				}
				if ($fld_url)
					$vals['url'] = $row->el_to;
				$data[] = $vals;
			} else {
				$resultPageSet->processDbRow($row);
			}
		}
		$db->freeResult($res);

		if (is_null($resultPageSet)) {
			$result = $this->getResult();
			$result->setIndexedTagName($data, 'p');
			$result->addValue('query', $this->getModuleName(), $data);
		}
	}

	protected function getAllowedParams() {
		global $wgUrlProtocols;
		$protocols = array();
		foreach ($wgUrlProtocols as $p) {
			$protocols[] = substr($p, 0, strpos($p,':'));
		}
		
		return array (
			'prop' => array (
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_DFLT => 'ids|title|url',
				ApiBase :: PARAM_TYPE => array (
					'ids',
					'title',
					'url'
				)
			),
			'offset' => array (
				ApiBase :: PARAM_TYPE => 'integer'
			),
			'protocol' => array (
				ApiBase :: PARAM_TYPE => $protocols,
				ApiBase :: PARAM_DFLT => 'http',
			),
			'query' => null,
			'namespace' => array (
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => 'namespace'
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
			'prop' => 'What pieces of information to include',
			'offset' => 'Used for paging. Use the value returned for "continue"',
			'protocol' => 'Protocol of the url',
			'query' => 'Search string without protocol. See [[Special:LinkSearch]]',
			'namespace' => 'The page namespace(s) to enumerate.',
			'limit' => 'How many entries to return.'
		);
	}

	protected function getDescription() {
		return 'Enumerate pages that contain a given URL';
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&list=exturlusage&euquery=www.mediawiki.org'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
?>
