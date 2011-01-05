<?php
/**
 *
 *
 * Created on Oct 13, 2006
 *
 * Copyright © 2006 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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
 *
 * @file
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( "ApiBase.php" );
}

/**
 * @ingroup API
 */
class ApiOpenSearch extends ApiBase {

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

	public function getCustomPrinter() {
		return $this->getMain()->createPrinterByName( 'json' );
	}

	public function execute() {
		global $wgEnableOpenSearchSuggest, $wgSearchSuggestCacheExpiry;
		$params = $this->extractRequestParams();
		$search = $params['search'];
		$limit = $params['limit'];
		$namespaces = $params['namespace'];
		$suggest = $params['suggest'];

		// MWSuggest or similar hit
		if ( $suggest && !$wgEnableOpenSearchSuggest ) {
			$srchres = array();
		} else {
			// Open search results may be stored for a very long
			// time
			$this->getMain()->setCacheMaxAge( $wgSearchSuggestCacheExpiry );
			$this->getMain()->setCacheMode( 'public' );

			$srchres = PrefixSearch::titleSearch( $search, $limit,
				$namespaces );
			
			// if the content language has variants, try to retrieve fallback results
			$fblimit = $limit - count( $srchres );
			if ( $fblimit > 0 ) {
				global $wgContLang;
				$fbsearchs = $wgContLang->autoConvertToAllVariants( $search );
				$fbsearchs = array_diff( array_unique( $fbsearchs ), ( array ) $search );
				foreach ( $fbsearchs as $fbsearch ) {
					$_srchres = PrefixSearch::titleSearch( $fbsearch, $fblimit,
						$namespaces );
					$srchres = array_merge( $srchres, $_srchres );
					$fblimit -= count( $_srchres );
					if ( $fblimit == 0 ) {
						break;
					}
				}
			}
		}
		// Set top level elements
		$result = $this->getResult();
		$result->addValue( null, 0, $search );
		$result->addValue( null, 1, $srchres );
	}

	public function getAllowedParams() {
		return array(
			'search' => null,
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => 100,
				ApiBase::PARAM_MAX2 => 100
			),
			'namespace' => array(
				ApiBase::PARAM_DFLT => NS_MAIN,
				ApiBase::PARAM_TYPE => 'namespace',
				ApiBase::PARAM_ISMULTI => true
			),
			'suggest' => false,
		);
	}

	public function getParamDescription() {
		return array(
			'search' => 'Search string',
			'limit' => 'Maximum amount of results to return',
			'namespace' => 'Namespaces to search',
			'suggest' => 'Do nothing if $wgEnableOpenSearchSuggest is false',
		);
	}

	public function getDescription() {
		return 'This module implements OpenSearch protocol';
	}

	protected function getExamples() {
		return array(
			'api.php?action=opensearch&search=Te'
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
