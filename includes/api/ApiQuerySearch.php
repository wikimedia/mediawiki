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

namespace MediaWiki\Api;

use ISearchResultSet;
use MediaWiki\Search\TitleMatcher;
use MediaWiki\Status\Status;
use SearchEngine;
use SearchEngineConfig;
use SearchEngineFactory;
use SearchResult;
use Wikimedia\HtmlArmor\HtmlArmor;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\EnumDef;

/**
 * Query module to perform full text search within wiki titles and content
 *
 * @ingroup API
 */
class ApiQuerySearch extends ApiQueryGeneratorBase {
	use \MediaWiki\Api\SearchApi;

	private TitleMatcher $titleMatcher;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		SearchEngineConfig $searchEngineConfig,
		SearchEngineFactory $searchEngineFactory,
		TitleMatcher $titleMatcher
	) {
		parent::__construct( $query, $moduleName, 'sr' );
		// Services also needed in SearchApi trait
		$this->searchEngineConfig = $searchEngineConfig;
		$this->searchEngineFactory = $searchEngineFactory;
		$this->titleMatcher = $titleMatcher;
	}

	public function execute() {
		$this->run();
	}

	/** @inheritDoc */
	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param ApiPageSet|null $resultPageSet
	 * @return void
	 */
	private function run( $resultPageSet = null ) {
		$params = $this->extractRequestParams();

		// Extract parameters
		$query = $params['search'];
		$what = $params['what'];
		$interwiki = $params['interwiki'];
		$searchInfo = array_fill_keys( $params['info'], true );
		$prop = array_fill_keys( $params['prop'], true );

		// Create search engine instance and set options
		$search = $this->buildSearchEngine( $params );
		if ( isset( $params['sort'] ) ) {
			$search->setSort( $params['sort'] );
		}
		$search->setFeatureData( 'rewrite', (bool)$params['enablerewrites'] );
		$search->setFeatureData( 'interwiki', (bool)$interwiki );
		// Hint to some SearchEngines about what snippets we would like returned
		$search->setFeatureData( 'snippets', $this->decideSnippets( $prop ) );

		$nquery = $search->replacePrefixes( $query );
		if ( $nquery !== $query ) {
			$query = $nquery;
			wfDeprecatedMsg( 'SearchEngine::replacePrefixes() is overridden by ' .
				get_class( $search ) . ', this was deprecated in MediaWiki 1.32',
				'1.32' );
		}
		// Perform the actual search
		if ( $what == 'text' ) {
			$matches = $search->searchText( $query );
		} elseif ( $what == 'title' ) {
			$matches = $search->searchTitle( $query );
		} elseif ( $what == 'nearmatch' ) {
			// near matches must receive the user input as provided, otherwise
			// the near matches within namespaces are lost.
			$matches = $this->titleMatcher->getNearMatchResultSet( $params['search'] );
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
			if ( $matches === null ) {
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
		} elseif ( $matches === null ) {
			$this->dieWithError( [ 'apierror-searchdisabled', $what ], "search-{$what}-disabled" );
		}

		$apiResult = $this->getResult();
		// Add search meta data to result
		if ( isset( $searchInfo['totalhits'] ) ) {
			$totalhits = $matches->getTotalHits();
			if ( $totalhits !== null ) {
				$apiResult->addValue( [ 'query', 'searchinfo' ],
					'totalhits', $totalhits );
				if ( $matches->isApproximateTotalHits() ) {
					$apiResult->addValue( [ 'query', 'searchinfo' ],
						'approximate_totalhits', $matches->isApproximateTotalHits() );
				}
			}
		}
		if ( isset( $searchInfo['suggestion'] ) && $matches->hasSuggestion() ) {
			$apiResult->addValue( [ 'query', 'searchinfo' ],
				'suggestion', $matches->getSuggestionQuery() );
			$apiResult->addValue( [ 'query', 'searchinfo' ],
				'suggestionsnippet', HtmlArmor::getHtml( $matches->getSuggestionSnippet() ) );
		}
		if ( isset( $searchInfo['rewrittenquery'] ) && $matches->hasRewrittenQuery() ) {
			$apiResult->addValue( [ 'query', 'searchinfo' ],
				'rewrittenquery', $matches->getQueryAfterRewrite() );
			$apiResult->addValue( [ 'query', 'searchinfo' ],
				'rewrittenquerysnippet', HtmlArmor::getHtml( $matches->getQueryAfterRewriteSnippet() ) );
		}

		$titles = [];
		$data = [];
		$count = 0;

		if ( $matches->hasMoreResults() ) {
			$this->setContinueEnumParameter( 'offset', $params['offset'] + $params['limit'] );
		}

		foreach ( $matches as $result ) {
			$count++;
			// Silently skip broken and missing titles
			if ( $result->isBrokenTitle() || $result->isMissingRevision() ) {
				continue;
			}

			$vals = $this->getSearchResultData( $result, $prop );

			if ( $resultPageSet === null ) {
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
				$data[] = $vals ?: [];
			}
		}

		// Here we assume interwiki results do not count with
		// regular search results. We may want to reconsider this
		// if we ever return a lot of interwiki results or want pagination
		// for them.
		// Interwiki results inside main result set
		$canAddInterwiki = (bool)$params['enablerewrites'] && ( $resultPageSet === null );
		if ( $canAddInterwiki ) {
			$this->addInterwikiResults( $matches, $apiResult, $prop, 'additional',
				ISearchResultSet::INLINE_RESULTS );
		}

		// Interwiki results outside main result set
		if ( $interwiki && $resultPageSet === null ) {
			$this->addInterwikiResults( $matches, $apiResult, $prop, 'interwiki',
				ISearchResultSet::SECONDARY_RESULTS );
		}

		if ( $resultPageSet === null ) {
			$apiResult->addIndexedTagName( [
				'query', $this->getModuleName()
			], 'p' );
		} else {
			$resultPageSet->setRedirectMergePolicy( static function ( $current, $new ) {
				if ( !isset( $current['index'] ) || $new['index'] < $current['index'] ) {
					$current['index'] = $new['index'];
				}
				return $current;
			} );
			$resultPageSet->populateFromTitles( $titles );
			$offset = $params['offset'] + 1;
			foreach ( $titles as $index => $title ) {
				$resultPageSet->setGeneratorData(
					$title,
					$data[ $index ] + [ 'index' => $index + $offset ]
				);
			}
		}
	}

	/**
	 * Assemble search result data.
	 * @param SearchResult $result Search result
	 * @param array $prop Props to extract (as keys)
	 * @return array|null Result data or null if result is broken in some way.
	 */
	private function getSearchResultData( SearchResult $result, $prop ) {
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
			$vals['snippet'] = $result->getTextSnippet();
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
		if ( $result->getRedirectTitle() !== null ) {
			if ( isset( $prop['redirecttitle'] ) ) {
				$vals['redirecttitle'] = $result->getRedirectTitle()->getPrefixedText();
			}
			if ( isset( $prop['redirectsnippet'] ) ) {
				$vals['redirectsnippet'] = $result->getRedirectSnippet();
			}
		}
		if ( $result->getSectionTitle() !== null ) {
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
	 * @param ISearchResultSet $matches
	 * @param ApiResult $apiResult
	 * @param array $prop Props to extract (as keys)
	 * @param string $section Section name where results would go
	 * @param int $type Interwiki result type
	 * @return int|null Number of total hits in the data or null if none was produced
	 */
	private function addInterwikiResults(
		ISearchResultSet $matches, ApiResult $apiResult, $prop,
		$section, $type
	) {
		$totalhits = null;
		$approximateTotalHits = false;
		if ( $matches->hasInterwikiResults( $type ) ) {
			foreach ( $matches->getInterwikiResults( $type ) as $interwikiMatches ) {
				// Include number of results if requested
				$interwikiTotalHits = $interwikiMatches->getTotalHits();
				if ( $interwikiTotalHits !== null ) {
					$totalhits += $interwikiTotalHits;
					$approximateTotalHits = $approximateTotalHits || $interwikiMatches->isApproximateTotalHits();
				}

				foreach ( $interwikiMatches as $result ) {
					$title = $result->getTitle();
					$vals = $this->getSearchResultData( $result, $prop );

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
				}
			}
			if ( $totalhits !== null ) {
				$apiResult->addValue( [ 'query', $section . 'searchinfo' ], 'totalhits', $totalhits );
				if ( $approximateTotalHits ) {
					$apiResult->addValue( [ 'query', $section . 'searchinfo' ], 'approximate_totalhits', true );
				}
				$apiResult->addIndexedTagName( [
					'query', $section . $this->getModuleName()
				], 'p' );
			}
		}
		return $totalhits;
	}

	private function decideSnippets( array $prop ): array {
		// Field names align with definitions in ContentHandler::getFieldsForSearchIndex.
		// Except `redirect` which isn't explicitly created, but refers to the title of
		// pages that redirect to the result page.
		$fields = [];
		if ( isset( $prop['titlesnippet'] ) ) {
			$fields[] = 'title';
		}
		// checking snippet and title variants is a bit special cased, but some search
		// engines generate the title variant from the snippet and thus must have the
		// snippet requested to provide the title.
		if ( isset( $prop['redirectsnippet'] ) || isset( $prop['redirecttitle'] ) ) {
			$fields[] = 'redirect';
		}
		if ( isset( $prop['categorysnippet'] ) ) {
			$fields[] = 'category';
		}
		if ( isset( $prop['sectionsnippet'] ) || isset( $prop['sectiontitle'] ) ) {
			$fields[] = 'heading';
		}
		return $fields;
	}

	/** @inheritDoc */
	public function getCacheMode( $params ) {
		return 'public';
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		$allowedParams = $this->buildCommonApiParams() + [
			'what' => [
				ParamValidator::PARAM_TYPE => [
					'title',
					'text',
					'nearmatch',
				]
			],
			'info' => [
				ParamValidator::PARAM_DEFAULT => 'totalhits|suggestion|rewrittenquery',
				ParamValidator::PARAM_TYPE => [
					'totalhits',
					'suggestion',
					'rewrittenquery',
				],
				ParamValidator::PARAM_ISMULTI => true,
			],
			'prop' => [
				ParamValidator::PARAM_DEFAULT => 'size|wordcount|timestamp|snippet',
				ParamValidator::PARAM_TYPE => [
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
				ParamValidator::PARAM_ISMULTI => true,
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
				EnumDef::PARAM_DEPRECATED_VALUES => [
					'score' => true,
					'hasrelated' => true
				],
			],
			'interwiki' => false,
			'enablerewrites' => false,
		];

		// Generators only add info/properties if explicitly requested. T263841
		if ( $this->isInGeneratorMode() ) {
			$allowedParams['prop'][ParamValidator::PARAM_DEFAULT] = '';
			$allowedParams['info'][ParamValidator::PARAM_DEFAULT] = '';
		}

		// If we have more than one engine the list of available sorts is
		// difficult to represent. For now don't expose it.
		$alternatives = $this->searchEngineConfig->getSearchTypes();
		if ( count( $alternatives ) == 1 ) {
			$allowedParams['sort'] = [
				ParamValidator::PARAM_DEFAULT => SearchEngine::DEFAULT_SORT,
				ParamValidator::PARAM_TYPE => $this->searchEngineFactory->create()->getValidSorts(),
			];
		}

		return $allowedParams;
	}

	/** @inheritDoc */
	public function getSearchProfileParams() {
		return [
			'qiprofile' => [
				'profile-type' => SearchEngine::FT_QUERY_INDEP_PROFILE_TYPE,
				'help-message' => 'apihelp-query+search-param-qiprofile',
			],
		];
	}

	/** @inheritDoc */
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

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Search';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQuerySearch::class, 'ApiQuerySearch' );
