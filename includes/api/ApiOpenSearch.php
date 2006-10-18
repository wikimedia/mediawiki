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
	require_once ("ApiBase.php");
}

class ApiOpenSearch extends ApiBase {

	public function __construct($main, $action) {
		parent :: __construct($main, $action);
	}

	public function getCustomPrinter() {
		return $this->getMain()->createPrinterByName('json');
	}

	public function execute() {
		$search = null;
		extract($this->ExtractRequestParams());

		$title = Title :: newFromText($search);
		if(!$title)
			return; // Return empty result
			
		// Prepare nested request
		$params = new FauxRequest(array (
			'action' => 'query',
			'list' => 'allpages',
			'apnamespace' => $title->getNamespace(),
			'aplimit' => 10,
			'apprefix' => $title->getDBkey()
		));

		// Execute
		$module = new ApiMain($params);
		$module->execute();

		// Get clean data
		$data = & $module->getResultData();

		// Reformat useful data for future printing by JSON engine
		$srchres = array ();
		foreach ($data['query']['allpages'] as $pageid => & $pageinfo) {
			// Note: this data will no be printable by the xml engine
			// because it does not support lists of unnamed items
			$srchres[] = $pageinfo['title'];
		}

		// Set top level elements
		$result = $this->getResult();
		$result->addValue(null, 0, $search);
		$result->addValue(null, 1, $srchres);
	}

	protected function getAllowedParams() {
		return array (
			'search' => null
		);
	}

	protected function getParamDescription() {
		return array (
			'search' => 'Search string'
		);
	}

	protected function getDescription() {
		return 'This module implements OpenSearch protocol';
	}

	protected function getExamples() {
		return array (
			'api.php?action=opensearch&search=Te'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
?>