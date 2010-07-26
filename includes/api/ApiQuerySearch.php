<?php

/*
 * Created on July 30, 2007
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

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once ( 'ApiQueryBase.php' );
}

/**
 * Query module to perform full text search within wiki titles and content
 *
 * @ingroup API
 */
class ApiQuerySearch extends ApiQueryGeneratorBase {

	public function __construct( $query, $moduleName ) {
		parent :: __construct( $query, $moduleName, 'sr' );
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	private function run( $resultPageSet = null ) {
		global $wgContLang;
		$params = $this->extractRequestParams();

		// Extract parameters
		$limit = $params['limit'];
		$query = $params['search'];
		$what = $params['what'];
		$searchInfo = array_flip( $params['info'] );
		$prop = array_flip( $params['prop'] );
		
		if ( strval( $query ) === '' )
			$this->dieUsage( "empty search string is not allowed", 'param-search' );

		// Create search engine instance and set options
		$search = SearchEngine::create();
		$search->setLimitOffset( $limit + 1, $params['offset'] );
		$search->setNamespaces( $params['namespace'] );
		$search->showRedirects = $params['redirects'];

		// Perform the actual search
		if ( $what == 'text' ) {
			$matches = $search->searchText( $query );
		} elseif ( $what == 'title' ) {
			$matches = $search->searchTitle( $query );
		} else {
			// We default to title searches; this is a terrible legacy
			// of the way we initially set up the MySQL fulltext-based
			// search engine with separate title and text fields.
			// In the future, the default should be for a combined index.
			$what = 'title';
			$matches = $search->searchTitle( $query );
			
			// Not all search engines support a separate title search,
			// for instance the Lucene-based engine we use on Wikipedia.
			// In this case, fall back to full-text search (which will
			// include titles in it!)
			if ( is_null( $matches ) ) {
				$what = 'text';
				$matches = $search->searchText( $query );
			}
		}
		if ( is_null( $matches ) )
			$this->dieUsage( "{$what} search is disabled", "search-{$what}-disabled" );
		
		// Add search meta data to result
		if ( isset( $searchInfo['totalhits'] ) ) {
			$totalhits = $matches->getTotalHits();
			if ( $totalhits !== null ) {
				$this->getResult()->addValue( array( 'query', 'searchinfo' ),
						'totalhits', $totalhits );
			}
		}
		if ( isset( $searchInfo['suggestion'] ) && $matches->hasSuggestion() ) {
			$this->getResult()->addValue( array( 'query', 'searchinfo' ),
						'suggestion', $matches->getSuggestionQuery() );
		}

		// Add the search results to the result
		$terms = $wgContLang->convertForSearchResult( $matches->termMatches() );
		$titles = array ();
		$count = 0;
		while ( $result = $matches->next() ) {
			if ( ++ $count > $limit ) {
				// We've reached the one extra which shows that there are additional items to be had. Stop here...
				$this->setContinueEnumParameter( 'offset', $params['offset'] + $params['limit'] );
				break;
			}

			// Silently skip broken and missing titles
			if ( $result->isBrokenTitle() || $result->isMissingRevision() )
				continue;
			
			$title = $result->getTitle();
			if ( is_null( $resultPageSet ) ) {
				$vals = array();
				ApiQueryBase::addTitleInfo( $vals, $title );
				
				if ( isset( $prop['snippet'] ) )
					$vals['snippet'] = $result->getTextSnippet( $terms );
				if ( isset( $prop['size'] ) )
					$vals['size'] = $result->getByteSize();
				if ( isset( $prop['wordcount'] ) )
					$vals['wordcount'] = $result->getWordCount();
				if ( isset( $prop['timestamp'] ) )
					$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $result->getTimestamp() );
				
				// Add item to results and see whether it fits
				$fit = $this->getResult()->addValue( array( 'query', $this->getModuleName() ),
						null, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'offset', $params['offset'] + $count - 1 );
					break;
				}
			} else {
				$titles[] = $title;
			}
		}

		if ( is_null( $resultPageSet ) ) {
			$this->getResult()->setIndexedTagName_internal( array(
						'query', $this->getModuleName()
					), 'p' );
		} else {
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		return array (
			'search' => null,
			'namespace' => array (
				ApiBase :: PARAM_DFLT => 0,
				ApiBase :: PARAM_TYPE => 'namespace',
				ApiBase :: PARAM_ISMULTI => true,
			),
			'what' => array (
				ApiBase :: PARAM_DFLT => null,
				ApiBase :: PARAM_TYPE => array (
					'title',
					'text',
				)
			),
			'info' => array(
				ApiBase :: PARAM_DFLT => 'totalhits|suggestion',
				ApiBase :: PARAM_TYPE => array (
					'totalhits',
					'suggestion',
				),
				ApiBase :: PARAM_ISMULTI => true,
			),
			'prop' => array(
				ApiBase :: PARAM_DFLT => 'size|wordcount|timestamp|snippet',
				ApiBase :: PARAM_TYPE => array (
					'size',
					'wordcount',
					'timestamp',
					'snippet',
				),
				ApiBase :: PARAM_ISMULTI => true,
			),
			'redirects' => false,
			'offset' => 0,
			'limit' => array (
				ApiBase :: PARAM_DFLT => 10,
				ApiBase :: PARAM_TYPE => 'limit',
				ApiBase :: PARAM_MIN => 1,
				ApiBase :: PARAM_MAX => ApiBase :: LIMIT_SML1,
				ApiBase :: PARAM_MAX2 => ApiBase :: LIMIT_SML2
			)
		);
	}

	public function getParamDescription() {
		return array (
			'search' => 'Search for all page titles (or content) that has this value.',
			'namespace' => 'The namespace(s) to enumerate.',
			'what' => 'Search inside the text or titles.',
			'info' => 'What metadata to return.',
			'prop' => 'What properties to return.',
			'redirects' => 'Include redirect pages in the search.',
			'offset' => 'Use this value to continue paging (return by query)',
			'limit' => 'How many total pages to return.'
		);
	}

	public function getDescription() {
		return 'Perform a full text search';
	}
	
	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'param-search', 'info' => 'empty search string is not allowed' ),
			array( 'code' => 'search-text-disabled', 'info' => 'text search is disabled' ),
			array( 'code' => 'search-title-disabled', 'info' => 'title search is disabled' ),
		) );
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&list=search&srsearch=meaning',
			'api.php?action=query&list=search&srwhat=text&srsearch=meaning',
			'api.php?action=query&generator=search&gsrsearch=meaning&prop=info',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
