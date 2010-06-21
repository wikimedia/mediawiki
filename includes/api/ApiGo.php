<?php

/**
 * Created on Mar 30, 2010
 * API for MediaWiki 1.8+
 *
 * Copyright © 2010 Matthew Britton <Firstname>.<Lastname>@btinternet.com
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
* API module to determine the result of a "Go" search
*
 * @ingroup API
 */
class ApiGo extends ApiBase {
	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

	public function execute() {
		$params = $this->extractRequestParams();
		$text = $params['text'];

		if ( is_null( $text ) ) {
			$this->dieUsageMsg( array( 'missingparam', 'text' ) );
		}
		
		// Strip underscores
		$text = str_replace( '_', ' ', $text );
		
		$nearMatch = SearchEngine::getNearMatch( $text );

		$this->getResult()->addValue( null, $this->getModuleName(), array( 'text' => $text, 'result' => $nearMatch ) );
	}

	public function mustBePosted() {
		return false;
	}

	public function isWriteMode() {
		return false;
	}

	public function getAllowedParams() {
		return array(
			'text' => null,
		);
	}

	public function getParamDescription() {
		return array(
			'text' => 'Text to try a "Go" match for'
		);
	}

	public function getDescription() {
		return 'Determine the title one will be taken to by a "Go" search, if any';
	}
	
	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'missingparam', 'text' )
		) );
	}

	protected function getExamples() {
		return array(
			'api.php?action=go&text=Foo'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
