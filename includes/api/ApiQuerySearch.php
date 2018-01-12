<?php
/**
 * Copyright © 2007 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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

/**
 * Query module to perform full text search within wiki titles and content
 *
 * @ingroup API
 */
class ApiQuerySearch extends ApiQueryGeneratorBase {
	use SearchApi;

	/** @var array list of api allowed params */
	private $allowedParams;

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'sr' );
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param ApiPageSet $resultPageSet
	 * @return void
	 */
	private function run( $resultPageSet = null ) {
		global $wgContLang;
		$params = $this->extractRequestParams();

		// Extract parameters
		$query = $params['search'];
		$what = $params['what'];
		$interwiki = $params['interwiki'];
		$searchInfo = array_flip( $params['info'] );
		$prop = array_flip( $params['prop'] );

		// Create search engine instance and set options
		$search = $this->buildSearchEngine( $params );
		$search->setFeatureData( 'rewrite', (bool)$params['enablerewrites'] );
		$search->setFeatureData( 'interwiki', (bool)$interwiki );

		$query = $search->transformSearchTerm( $query );
		$query = $search->replacePrefixes( $query );

		// Perform the actual search
		if ( $what == 'text' ) {
			$matches = $search->searchText( $query );
		} elseif ( $what == 'title' ) {
			$matches = $search->searchTitle( $query );
		} elseif ( $what == 'nearmatch' ) {
			// near matches must receive the user input as provided, otherwise
			// the near matches within namespaces are lost.
			$matches = $search->getNearMatcher( $this->getConfig() )
				->getNearMatchResultSet( $params['search'] );
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

		if ( $matches instanceof Status ) {
			$status = $matches;
			$matches = $status->getValue();
		} else {
			$status = null;
		}

		if ( $status ) {
			if ( $status->isOK() ) {
				$this->getMain()->getErrorFormatter()->addMessagesFromStatus(
					$this->getModuleName(),
					$status
				);
			} else {
				$this->dieStatus( $status );
			}
		} elseif ( is_null( $matches ) ) {
			$this->dieWithError( [ 'apierror-searchdisabled', $what ], "search-{$what}-disabled" );
		}

		if ( $resultPageSet === null ) {
			$apiResult = $this->getResult();
			// Add search meta data to result
			if ( isset( $searchInfo['totalhits'] ) ) {
				$totalhits = $matches->getTotalHits();
				if ( $totalhits !== null ) {
					$apiResult->addValue( [ 'query', 'searchinfo' ],
						'totalhits', $totalhits );
				}
			}
			if ( isset( $searchInfo['suggestion'] ) && $matches->hasSuggestion() ) {
				$apiResult->addValue( [ 'query', 'searchinfo' ],
					'suggestion', $matches->getSuggestionQuery() );
				$apiResult->addValue( [ 'query', 'searchinfo' ],
					'suggestionsnippet', $matches->getSuggestionSnippet() );
			}
			if ( isset( $searchInfo['rewrittenquery'] ) && $matches->hasRewrittenQuery() ) {
				$apiResult->addValue( [ 'query', 'searchinfo' ],
					'rewrittenquery', $matches->getQueryAfterRewrite() );
				$apiResult->addValue( [ 'query', 'searchinfo' ],
					'rewrittenquerysnippet', $matches->getQueryAfterRewriteSnippet() );
			}
		}

		// Add the search results to the result
		$terms = $wgContLang->convertForSearchResult( $matches->termMatches() );
		$titles = [];
		$count = 0;
		$result = $matches->next();
		$limit = $params['limit'];

		while ( $result ) {
			if ( ++$count > $limit ) {
				// We've reached the one extra which shows that there are
				// additional items to be had. Stop here...
				$this->setContinueEnumParameter( 'offset', $params['offset'] + $params['limit'] );
				break;
			}

			// Silently skip broken and missing titles
			if ( $result->isBrokenTitle() || $result->isMissingRevision() ) {
				$result = $matches->next();
				continue;
			}

			if ( $resultPageSet === null ) {
				$vals = $this->getSearchResultData( $result, $prop, $terms );
				if ( $vals ) {
					// Add item to results and see whether it fits
					$fit = $apiResult->addValue( [ 'query', $this->getModuleName() ], null, $vals );
					if ( !$fit ) {
						$this->setContinueEnumParameter( 'offset', $params['offset'] + $count - 1 );
						break;
					}
				}
			} else {
				$titles[] = $result->getTitle();
			}

			$result = $matches->next();
		}

		// Here we assume interwiki results do not count with
		// regular search results. We may want to reconsider this
		// if we ever return a lot of interwiki results or want pagination
		// for them.
		// Interwiki results inside main result set
		$canAddInterwiki = (bool)$params['enablerewrites'] && ( $resultPageSet === null );
		if ( $canAddInterwiki ) {
			$this->addInterwikiResults( $matches, $apiResult, $prop, $terms, 'additional',
				SearchResultSet::INLINE_RESULTS );
		}

		// Interwiki results outside main result set
		if ( $interwiki && $resultPageSet === null ) {
			$this->addInterwikiResults( $matches, $apiResult, $prop, $terms, 'interwiki',
				SearchResultSet::SECONDARY_RESULTS );
		}

		if ( $resultPageSet === null ) {
			$apiResult->addIndexedTagName( [
				'query', $this->getModuleName()
			], 'p' );
		} else {
			$resultPageSet->setRedirectMergePolicy( function ( $current, $new ) {
				if ( !isset( $current['index'] ) || $new['index'] < $current['index'] ) {
					$current['index'] = $new['index'];
				}
				return $current;
			} );
			$resultPageSet->populateFromTitles( $titles );
			$offset = $params['offset'] + 1;
			foreach ( $titles as $index => $title ) {
				$resultPageSet->setGeneratorData( $title, [ 'index' => $index + $offset ] );
			}
		}
	}

	/**
	 * Assemble search result data.
	 * @param SearchResult $result Search result
	 * @param array        $prop Props to extract (as keys)
	 * @param array        $terms Terms list
	 * @return array|null Result data or null if result is broken in some way.
	 */
	private function getSearchResultData( SearchResult $result, $prop, $terms ) {
		// Silently skip broken and missing titles
		if ( $result->isBrokenTitle() || $result->isMissingRevision() ) {
			return null;
		}

		$vals = [];

		$title = $result->getTitle();
		ApiQueryBase::addTitleInfo( $vals, $title );
		$vals['pageid'] = $title->getArticleID();

		if ( isset( $prop['size'] ) ) {
			$vals['size'] = $result->getByteSize();
		}
		if ( isset( $prop['wordcount'] ) ) {
			$vals['wordcount'] = $result->getWordCount();
		}
		if ( isset( $prop['snippet'] ) ) {
			$vals['snippet'] = $result->getTextSnippet( $terms );
		}
		if ( isset( $prop['timestamp'] ) ) {
			$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $result->getTimestamp() );
		}
		if ( isset( $prop['titlesnippet'] ) ) {
			$vals['titlesnippet'] = $result->getTitleSnippet();
		}
		if ( isset( $prop['categorysnippet'] ) ) {
			$vals['categorysnippet'] = $result->getCategorySnippet();
		}
		if ( !is_null( $result->getRedirectTitle() ) ) {
			if ( isset( $prop['redirecttitle'] ) ) {
				$vals['redirecttitle'] = $result->getRedirectTitle()->getPrefixedText();
			}
			if ( isset( $prop['redirectsnippet'] ) ) {
				$vals['redirectsnippet'] = $result->getRedirectSnippet();
			}
		}
		if ( !is_null( $result->getSectionTitle() ) ) {
			if ( isset( $prop['sectiontitle'] ) ) {
				$vals['sectiontitle'] = $result->getSectionTitle()->getFragment();
			}
			if ( isset( $prop['sectionsnippet'] ) ) {
				$vals['sectionsnippet'] = $result->getSectionSnippet();
			}
		}
		if ( isset( $prop['isfilematch'] ) ) {
			$vals['isfilematch'] = $result->isFileMatch();
		}

		if ( isset( $prop['extensiondata'] ) ) {
			$extra = $result->getExtensionData();
			// Add augmented data to the result. The data would be organized as a map:
			// augmentorName => data
			if ( $extra ) {
				$vals['extensiondata'] = ApiResult::addMetadataToResultVars( $extra );
			}
		}

		return $vals;
	}

	/**
	 * Add interwiki results as a section in query results.
	 * @param SearchResultSet $matches
	 * @param ApiResult       $apiResult
	 * @param array           $prop Props to extract (as keys)
	 * @param array           $terms Terms list
	 * @param string          $section Section name where results would go
	 * @param int             $type Interwiki result type
	 * @return int|null Number of total hits in the data or null if none was produced
	 */
	private function addInterwikiResults(
		SearchResultSet $matches, ApiResult $apiResult, $prop,
		$terms, $section, $type
	) {
		$totalhits = null;
		if ( $matches->hasInterwikiResults( $type ) ) {
			foreach ( $matches->getInterwikiResults( $type ) as $interwikiMatches ) {
				// Include number of results if requested
				$totalhits += $interwikiMatches->getTotalHits();

				$result = $interwikiMatches->next();
				while ( $result ) {
					$title = $result->getTitle();
					$vals = $this->getSearchResultData( $result, $prop, $terms );

					$vals['namespace'] = $result->getInterwikiNamespaceText();
					$vals['title'] = $title->getText();
					$vals['url'] = $title->getFullURL();

					// Add item to results and see whether it fits
					$fit = $apiResult->addValue( [
							'query',
							$section . $this->getModuleName(),
							$result->getInterwikiPrefix()
						], null, $vals );

					if ( !$fit ) {
						// We hit the limit. We can't really provide any meaningful
						// pagination info so just bail out
						break;
					}

					$result = $interwikiMatches->next();
				}
			}
			if ( $totalhits !== null ) {
				$apiResult->addValue( [ 'query', $section . 'searchinfo' ], 'totalhits', $totalhits );
				$apiResult->addIndexedTagName( [
					'query', $section . $this->getModuleName()
				], 'p' );
			}
		}
		return $totalhits;
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		if ( $this->allowedParams !== null ) {
			return $this->allowedParams;
		}

		$this->allowedParams = $this->buildCommonApiParams() + [
			'what' => [
				ApiBase::PARAM_TYPE => [
					'title',
					'text',
					'nearmatch',
				]
			],
			'info' => [
				ApiBase::PARAM_DFLT => 'totalhits|suggestion|rewrittenquery',
				ApiBase::PARAM_TYPE => [
					'totalhits',
					'suggestion',
					'rewrittenquery',
				],
				ApiBase::PARAM_ISMULTI => true,
			],
			'prop' => [
				ApiBase::PARAM_DFLT => 'size|wordcount|timestamp|snippet',
				ApiBase::PARAM_TYPE => [
					'size',
					'wordcount',
					'timestamp',
					'snippet',
					'titlesnippet',
					'redirecttitle',
					'redirectsnippet',
					'sectiontitle',
					'sectionsnippet',
					'isfilematch',
					'categorysnippet',
					'score', // deprecated
					'hasrelated', // deprecated
					'extensiondata',
				],
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
				ApiBase::PARAM_DEPRECATED_VALUES => [
					'score' => true,
					'hasrelated' => true
				],
			],
			'interwiki' => false,
			'enablerewrites' => false,
		];

		return $this->allowedParams;
	}

	public function getSearchProfileParams() {
		return [
			'qiprofile' => [
				'profile-type' => SearchEngine::FT_QUERY_INDEP_PROFILE_TYPE,
				'help-message' => 'apihelp-query+search-param-qiprofile',
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&list=search&srsearch=meaning'
				=> 'apihelp-query+search-example-simple',
			'action=query&list=search&srwhat=text&srsearch=meaning'
				=> 'apihelp-query+search-example-text',
			'action=query&generator=search&gsrsearch=meaning&prop=info'
				=> 'apihelp-query+search-example-generator',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Search';
	}
}
