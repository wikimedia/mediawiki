<?php
/**
 * API for MediaWiki 1.8+
 *
 * Created on Sep 25, 2006
 *
 * Copyright © 2010 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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

	private $params, $titles, $missing, $everything;

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'pp' );
	}

	public function execute() {
		$this->params = $this->extractRequestParams();

		$pageSet = $this->getPageSet();
		$this->titles = $pageSet->getGoodTitles();
		$this->missing = $pageSet->getMissingTitles();
		$this->everything = $this->titles + $this->missing;
		$result = $this->getResult();

		uasort( $this->everything, array( 'Title', 'compare' ) );
		if ( !is_null( $this->params['continue'] ) ) {
			// Throw away any titles we're gonna skip so they don't
			// clutter queries
			$cont = explode( '|', $this->params['continue'] );
			if ( count( $cont ) != 2 ) {
				$this->dieUsage( 'Invalid continue param. You should pass the original ' .
						'value returned by the previous query', '_badcontinue' );
			}
			$conttitle = Title::makeTitleSafe( $cont[0], $cont[1] );
			foreach ( $this->everything as $pageid => $title ) {
				if ( Title::compare( $title, $conttitle ) >= 0 ) {
					break;
				}
				unset( $this->titles[$pageid] );
				unset( $this->missing[$pageid] );
				unset( $this->everything[$pageid] );
			}
		}

		foreach ( $this->everything as $pageid => $title ) {
			$pageInfo = $this->extractPageInfo( $pageid, $title, $this->params['prop'] );
			$fit = $result->addValue( array(
				'query',
				'pages'
			), $pageid, $pageInfo );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'continue',
						$title->getNamespace() . '|' .
						$title->getText() );
				break;
			}
		}
	}

	/**
	 * Get a result array with information about a title
	 * @param $pageid int Page ID (negative for missing titles)
	 * @param $title Title object
	 * @return array
	 */
	private function extractPageInfo( $pageid, $title, $prop ) {
		global $wgPageProps;
		
		$pageInfo = array();
		if ( $title->exists() ) {
		
			$dbr = wfGetDB( DB_SLAVE );
		
			$res = $dbr->select(
				'page_props',
				array( 'pp_propname', 'pp_value' ),
				array( 'pp_page' => $pageid ),
				__METHOD__
			);
		
			foreach( $res as $row ) {
				if( isset( $wgPageProps[$row->pp_propname] ) ) {
					if( !is_null( $prop ) && !in_array( $row->pp_propname, $prop ) ) {
						continue;
					}
					$pageInfo[$row->pp_propname] = $row->pp_value;
				}
			}
			
		}

		return $pageInfo;
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		global $wgPageProps;
		
		return array(
			'prop' => array(
				ApiBase::PARAM_DFLT => null,
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array_keys( $wgPageProps )
			),
			'continue' => null,
		);
	}

	public function getParamDescription() {
		global $wgPageProps;
		
		$ret =  array(
			'prop' => array(
				'Which additional properties to get:',
			),
			'continue' => 'When more results are available, use this to continue',
		);
		
		//This mess of code first gets the length of the biggest propname, and adds two onto it to make 
		//the number of characters should be used before the dash. If the biggest propname is shorter than 12 characters, 
		//the number of characters before the dash become 14. 
		$maxLen = max( array_map( 'strlen', array_keys( $wgPageProps ) ) );
		$matchLen = $maxLen + 2;
		if( $maxLen < 12 ) {
			$matchLen = 14;
		}
		
		foreach( $wgPageProps as $propName => $desc ) {
			$pretext = " $propName" . str_repeat( ' ', $matchLen - strlen( $propName ) );
			
			$ret['prop'][] = "$pretext- $desc";
		}
		
		return $ret;
	}

	public function getDescription() {
		return 'Get various properties about a page...';
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
