<?php

/*
 * Created on Dec 01, 2007
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
class ApiParse extends ApiBase {

	public function __construct($main, $action) {
		parent :: __construct($main, $action);
	}

	public function execute() {
		// Get parameters
		$params = $this->extractRequestParams();
		$text = $params['text'];
		$title = $params['title'];
		$prop = array_flip($params['prop']);

		//Create title for parser
		$title_obj = Title :: newFromText($params['title']);
		if(!$title_obj)
			$title_obj = Title :: newFromText("API");	//  Default title is "API". For example, ExpandTemplates uses "ExpendTemplates" for it

		// Parse text
		global $wgParser;
		$p_result = $wgParser->parse( $text, $title_obj, new ParserOptions() );

		// Return result
		$result = $this->getResult();
		$result_array = array();
		if(isset($prop['text'])) {
			$result_array['text'] = array();
			$result->setContent($result_array['text'], $p_result->getText());
		}
		if(isset($prop['langlinks']))
			$result_array['langlinks'] = $this->formatLangLinks($p_result->getLanguageLinks());
		if(isset($prop['categories']))
			$result_array['categories'] = $this->formatCategoryLinks($p_result->getCategories());
		if(isset($prop['links']))
			$result_array['links'] = $this->formatLinks($p_result->getLinks());
		if(isset($prop['templates']))
			$result_array['templates'] = $this->formatLinks($p_result->getTemplates());
		if(isset($prop['images']))
			$result_array['images'] = array_keys($p_result->getImages());
		if(isset($prop['externallinks']))
			$result_array['externallinks'] = array_keys($p_result->getExternalLinks());
		if(isset($prop['sections']))
			$result_array['sections'] = $p_result->getSections();
		
		$result_mapping = array(
			'langlinks' => 'll',
			'categories' => 'cl',
			'links' => 'pl',
			'templates' => 'tl',
			'images' => 'img',
			'externallinks' => 'el',
			'sections' => 's',
		);
		$this->setIndexedTagNames( $result_array, $result_mapping );
		$result->addValue( null, $this->getModuleName(), $result_array );
	}
	
	private function formatLangLinks( $links ) {
		$result = array();
		foreach( $links as $link ) {
			$entry = array();
			$bits = split( ':', $link, 2 );
			$entry['lang'] = $bits[0];
			$this->getResult()->setContent( $entry, $bits[1] );
			$result[] = $entry;
		}
		return $result;
	}
	
	private function formatCategoryLinks( $links ) {
		$result = array();
		foreach( $links as $link => $sortkey ) {
			$entry = array();
			$entry['sortkey'] = $sortkey;
			$this->getResult()->setContent( $entry, $link );
			$result[] = $entry;
		}
		return $result;
	}
	
	private function formatLinks( $links ) {
		$result = array();
		foreach( $links as $ns => $nslinks ) {
			foreach( $nslinks as $title => $id ) {
				$entry = array();
				$entry['ns'] = $ns;
				$this->getResult()->setContent( $entry, Title::makeTitle( $ns, $title )->getFullText() );
				if( $id != 0 )
					$entry['exists'] = '';
				$result[] = $entry;
			}
		}
		return $result;
	}
	
	private function setIndexedTagNames( &$array, $mapping ) {
		foreach( $mapping as $key => $name ) {
			if( isset( $array[$key] ) )
				$this->getResult()->setIndexedTagName( $array[$key], $name );
		}
	}

	protected function getAllowedParams() {
		return array (
			'title' => array( 
				ApiBase :: PARAM_DFLT => 'API',
			),
			'text' => null,
			'prop' => array(
				ApiBase :: PARAM_DFLT => 'text|langlinks|categories|links|templates|images|externallinks|sections',
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_TYPE => array(
					'text',
					'langlinks',
					'categories',
					'links',
					'templates',
					'images',
					'externallinks',
					'sections'
				)
			)
		);
	}

	protected function getParamDescription() {
		return array (
			'text' => 'Wikitext to parse',
			'title' => 'Title of page',
			'prop' => 'Which pieces of information to get'
		);
	}

	protected function getDescription() {
		return 'This module parses wikitext and returns parser output';
	}

	protected function getExamples() {
		return array (
			'api.php?action=parse&text={{Project:Sandbox}}'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}

