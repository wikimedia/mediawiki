<?php

/*
 * Created on Oct 06, 2007
 *
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2007 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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

/**
 * @addtogroup API
 */
class ApiRender extends ApiBase {

	public function __construct($main, $action) {
		parent :: __construct($main, $action);
	}

	public function execute() {
		// Get parameters
		$params = $this->extractRequestParams();
		$text = $params['text'];
		$title = $params['title'];
		$retval = '';

		//Create title for parser
		$title_obj = Title :: newFromText($params['title']);
		if(!$title_obj)
			$title_obj = Title :: newFromText("API");	//  Default title is "API". For example, ExpandTemplates uses "ExpendTemplates" for it

		// Parse text
		global $wgParser;
		$p_result = $wgParser->parse( $text, $title_obj, new ParserOptions() );
		$retval = $p_result->getText();

		// Return result
		$result = $this->getResult();
		if( $this->isRaw() ) {
			ApiFormatRaw :: setRawData( $result, $retval, 'text/html' );
		}
		$retval_array = array();
		$result->setContent( $retval_array, $retval );
		$result->addValue( null, $this->getModuleName(), $retval_array );
	}

	public function supportRaw() {
		return true;
	}

	protected function getAllowedParams() {
		return array (
			'title' => array( 
				ApiBase :: PARAM_DFLT => 'API',
			),
			'text' => null
		);
	}

	protected function getParamDescription() {
		return array (
			'text' => 'Wikitext to render',
			'title' => 'Title of page',
		);
	}

	protected function getDescription() {
		return 'This module allows to get rendered wikitext';
	}

	protected function getExamples() {
		return array (
			'api.php?action=render&text={{Project:Sandbox}}'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}


