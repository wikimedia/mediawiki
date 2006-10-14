<?php


/*
 * Created on Oct 13, 2006
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
	require_once ("ApiFormatBase.php");
}

class ApiOpenSearch extends ApiFormatBase {

	private $mResult = array();
	
	public function __construct($main, $action) {
		parent :: __construct($main, $action);
	}

	public function getMimeType() {
		return 'application/json';
	}

	public function execute() {
		$command = null;
		extract($this->ExtractRequestParams());
		
		// Prepare nested request
		$params = new FauxRequest(array (
			'action' => 'query',
			'list' => 'allpages',
			'apnamespace' => 0,
			'aplimit' => 10,
			'apprefix' => $command
		));
		
		// Execute
		$module = new ApiMain($params);
		$module->execute();
		
		// Get clean data
		$result =& $module->getResult();
		$result->SanitizeData();
		$data = $result->GetData();
		
		// Reformat useful data for future printing
		$result = array();
		foreach ($data['query']['allpages'] as $pageid => &$pageinfo) {
			$result[] = $pageinfo['title'];
		}
		
		$this->mResult = array($command, $result);
	}
	
	public function executePrinter() {
		$json = new Services_JSON();
		$this->printText($json->encode($this->mResult, true));
	}

	protected function GetAllowedParams() {
		return array (
			'command' => null
		);
	}

	protected function GetParamDescription() {
		return array (
			'command' => 'Search string'
		);
	}

	protected function GetDescription() {
		return 'This module implements OpenSearch protocol';
	}

	protected function GetExamples() {
		return array (
			'api.php?action=opensearch&command=Te'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
?>