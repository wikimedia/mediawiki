<?php
/**
 * API for MediaWiki 1.8+
 *
 * Created on Aug 7, 2010
 *
 * Copyright © 2010 soxred93, Bryan Tong Minh
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
	require_once( 'ApiQueryBase.php' );
}

/**
 * A query module to show basic page information.
 *
 * @ingroup API
 */
class ApiQueryPageProps extends ApiQueryBase {

	private $params;

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'pp' );
	}

	public function execute() {
		$this->params = $this->extractRequestParams();
		
		$pages = $this->getPageSet()->getGoodTitles();
		
		$this->addTables( 'page_props' );
		$this->addFields( array( 'pp_page', 'pp_propname', 'pp_value' ) );
		$this->addWhereFld( 'pp_page',  array_keys( $pages ) );
		
		if ( $this->params['continue'] ) {
			$this->addWhereFld( 'pp_page >=' . intval( $this->params['continue'] ) );
		}
		
		$this->addOption( 'ORDER BY', 'pp_page' );
		
		$res = $this->select( __METHOD__ );
		$currentPage = 0;
		$props = array();
		$result = $this->getResult();
		foreach ( $res as $row ) {
			if ( $currentPage != $row->pp_page ) {
				if ( $currentPage ) {
					if ( !$this->addPageProps( $result, $currentPage, $props ) ) {
						break;
					}
					
					$props = array();
				} else {
					$currentPage = $row->pp_page;
				}
			}
			
			$props[$row->pp_propname] = $row->pp_value;
		}
		
		if ( count( $props ) ) {
			$this->addPageProps( $result, $currentPage, $props );
		}
	}

	/**
	 * Add page properties to an ApiResult, adding a continue 
	 * parameter if it doesn't fit.
	 *
	 * @param $result ApiResult
	 * @param $page int
	 * @param $props array
	 * @return bool True if it fits in the result
	 */
	private function addPageProps( $result, $page, $props ) {
		$fit = $result->addValue( array( 'query', 'pages' ), $page, $props );
		
		if ( !$fit ) {
			$this->setContinueEnumParameter( 'continue', $page );
		}
		return $fit;
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {		
		return array( 'continue' => null );
	}

	public function getParamDescription() {
		return  array( 'continue' => 'When more results are available, use this to continue' );
	}

	public function getDescription() {
		return 'Get various properties defined in the page content';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => '_badcontinue', 'info' => 'Invalid continue param. You should pass the original value returned by the previous query' ),
		) );
	}

	protected function getExamples() {
		return array(
			'api.php?action=query&prop=pageprops&titles=Category:Foo',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
