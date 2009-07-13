<?php

/*
 * Created on Jul 12, 2009
 *
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2009 Bryan Tong Minh <Bryan.TongMinh@Gmail.com>
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
 * Query module to access querypages
 *
 * @ingroup API
 */
class ApiQueryQuerypage extends ApiQueryBase {
	static $queryPages = array( 
		'ancientpages' => 'AncientPagesPage',
		'brokenredirects' => 'BrokenRedirectsPage',
		'deadendpages' => 'DeadendPagesPage',
		'disambiguations' => 'DisambiguationsPage',	
	);
	
	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'qp');
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator($resultPageSet) {
		$this->run( $resultPageSet );
	}

	private function run($resultPageSet = null) {
		$params = $this->extractRequestParams();
		$offset = $params['offset'];
		$limit = $params['limit'];
		
		// Try to find an entry in $wgQueryPages
		$name = $params['querypage'];
		if ( is_null( $name ) )
			$this->dieUsageMsg( array( 'missingparam', 'querypage' ) );
		if ( !isset( self::$queryPages[$name] ) )
			$this->dieUsage( 'Querypage unrecognized', 'unknownquerypage' );
		
		// Get the result from the query page
		$class = self::$queryPages[$name];
		$queryPage = new $class;
		$result = $queryPage->reallyDoQuery( $offset, $limit + 1 );
		
		// Output the result
		$apiResult = $this->getResult();
		$resultPath = array( 'query', $this->getModuleName() );
		$count = 0;
		while ( $row = $result['dbr']->fetchObject( $result['result'] ) ) {
			if ( ++ $count > $limit ) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'offset', $offset + $count - 1 );
				break;
			}
						
			if ( is_null( $resultPageSet ) ) {
				// Normal mode; let the query page make a sensible result out of it
				$vals = $queryPage->formatApiResult( $row );
				$fit = $apiResult->addValue( $resultPath, null, $vals );
				if( !$fit )
				{
					$this->setContinueEnumParameter( 'offset', $offset + $count );
					break;
				}
			} else {
				// Generator mode; not yet supported
				$resultPageSet->processDbRow( $row );
			}
		}
		// Set XML element to 'p'
		if ( is_null( $resultPageSet ) ) {
			$apiResult->setIndexedTagName_internal( array( 'query', $this->getModuleName()), 'p' );
		}		
		
		// Set meta information
		if ( $result['cached'] ) {
			// Set cached date if available, else simply true
			$apiResult->addValue( $resultPath, 'cached',
				$result === true ? true : wfTimestamp( TS_ISO_8601, $result['cached'] ) );
		}
		if ( $result['disabled'] )
			// No further updates will be performed
			$apiResult->addValue( $resultPath, 'disabled', true ); 


	}

	public function getAllowedParams() {
		return array (
			'offset' => 0,
			'limit' => array (
				ApiBase :: PARAM_DFLT => 10,
				ApiBase :: PARAM_TYPE => 'limit',
				ApiBase :: PARAM_MIN => 1,
				ApiBase :: PARAM_MAX => ApiBase :: LIMIT_BIG1,
				ApiBase :: PARAM_MAX2 => ApiBase :: LIMIT_BIG2
			),
			'querypage' => array(
				ApiBase :: PARAM_TYPE => array_keys( self::$queryPages )
			),
		);
	}

	public function getParamDescription() {
		return array (
			'offset' => 'The offset to start enumerating from.',
			'limit' => 'How many total pages to return.',
			'querypage' => 'Which querypage to use',
		);
	}

	public function getDescription() {
		return 'Query one of the builtin query pages.';
	}

	protected function getExamples() {
		return array (
			' Query a list of broken redirects',
			'  api.php?action=query&list=querypage&qpquerypage=brokenredirects',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id:$';
	}
}