<?php

/**
 * Created on Oct 05, 2007
 *
 * API for MediaWiki 1.8+
 *
 * Copyright © 2007 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( "ApiBase.php" );
}

/**
 * API module that functions as a shortcut to the wikitext preprocessor. Expands
 * any templates in a provided string, and returns the result of this expansion
 * to the caller.
 *
 * @ingroup API
 */
class ApiExpandTemplates extends ApiBase {

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

	public function execute() {
		// Cache may vary on $wgUser because ParserOptions gets data from it
		$this->getMain()->setCacheMode( 'anon-public-user-private' );

		// Get parameters
		$params = $this->extractRequestParams();

		// Create title for parser
		$title_obj = Title::newFromText( $params['title'] );
		if ( !$title_obj ) {
			$title_obj = Title::newFromText( 'API' ); // default
		}

		$result = $this->getResult();

		// Parse text
		global $wgParser;
		$options = new ParserOptions();

		if ( $params['generatexml'] ) {
			$wgParser->startExternalParse( $title_obj, $options, OT_PREPROCESS );
			$dom = $wgParser->preprocessToDom( $params['text'] );
			if ( is_callable( array( $dom, 'saveXML' ) ) ) {
				$xml = $dom->saveXML();
			} else {
				$xml = $dom->__toString();
			}
			$xml_result = array();
			$result->setContent( $xml_result, $xml );
			$result->addValue( null, 'parsetree', $xml_result );
		}
		$retval = $wgParser->preprocess( $params['text'], $title_obj, $options );

		// Return result
		$retval_array = array();
		$result->setContent( $retval_array, $retval );
		$result->addValue( null, $this->getModuleName(), $retval_array );
	}

	public function getAllowedParams() {
		return array(
			'title' => array(
				ApiBase::PARAM_DFLT => 'API',
			),
			'text' => null,
			'generatexml' => false,
		);
	}

	public function getParamDescription() {
		return array(
			'text' => 'Wikitext to convert',
			'title' => 'Title of page',
			'generatexml' => 'Generate XML parse tree',
		);
	}

	public function getDescription() {
		return 'This module expand all templates in wikitext';
	}

	protected function getExamples() {
		return array(
			'api.php?action=expandtemplates&text={{Project:Sandbox}}'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
