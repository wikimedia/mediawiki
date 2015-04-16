<?php
/**
 *
 *
 * Created on July 30, 2007
 *
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

	/**
	 * When $wgSearchType is null, $wgSearchAlternatives[0] is null. Null isn't
	 * a valid option for an array for PARAM_TYPE, so we'll use a fake name
	 * that can't possibly be a class name and describes what the null behavior
	 * does
	 */
	const BACKEND_NULL_PARAM = 'database-backed';

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
		$limit = $params['limit'];
		$query = $params['search'];
		$what = $params['what'];
		$interwiki = $params['interwiki'];
		$searchInfo = array_flip( $params['info'] );
		$prop = array_flip( $params['prop'] );

		// Deprecated parameters
		if ( isset( $prop['hasrelated'] ) ) {
			$this->logFeatureUsage( 'action=search&srprop=hasrelated' );
			$this->setWarning( 'srprop=hasrelated has been deprecated' );
		}
		if ( isset( $prop['score'] ) ) {
			$this->logFeatureUsage( 'action=search&srprop=score' );
			$this->setWarning( 'srprop=score has been deprecated' );
		}

		// Create search engine instance and set options
		$search = isset( $params['backend'] ) && $params['backend'] != self::BACKEND_NULL_PARAM ?
			SearchEngine::create( $params['backend'] ) : SearchEngine::create();
		$search->setLimitOffset( $limit + 1, $params['offset'] );
		$search->setNamespaces( $params['namespace'] );

		$query = $search->transformSearchTerm( $query );
		$query = $search->replacePrefixes( $query );

		// Perform the actual search
		if ( $what == 'text' ) {
			$matches = $search->searchText( $query );
		} elseif ( $what == 'title' ) {
			$matches = $search->searchTitle( $query );
		} elseif ( $what == 'nearmatch' ) {
			$matches = SearchEngine::getNearMatchResultSet( $query );
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
		if ( is_null( $matches ) ) {
			$this->dieUsage( "{$what} search is disabled", "search-{$what}-disabled" );
		} elseif ( $matches instanceof Status && !$matches->isGood() ) {
			$this->dieUsage( $matches->getWikiText(), 'search-error' );
		}

		if ( $resultPageSet === null ) {
			$apiResult = $this->getResult();
			// Add search meta data to result
			if ( isset( $searchInfo['totalhits'] ) ) {
				$totalhits = $matches->getTotalHits();
				if ( $totalhits !== null ) {
					$apiResult->addValue( array( 'query', 'searchinfo' ),
						'totalhits', $totalhits );
				}
			}
			if ( isset( $searchInfo['suggestion'] ) && $matches->hasSuggestion() ) {
				$apiResult->addValue( array( 'query', 'searchinfo' ),
					'suggestion', $matches->getSuggestionQuery() );
			}
		}

		// Add the search results to the result
		$terms = $wgContLang->convertForSearchResult( $matches->termMatches() );
		$titles = array();
		$count = 0;
		$result = $matches->next();

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

			$title = $result->getTitle();
			if ( $resultPageSet === null ) {
				$vals = array();
				ApiQueryBase::addTitleInfo( $vals, $title );

				if ( isset( $prop['snippet'] ) ) {
					$vals['snippet'] = $result->getTextSnippet( $terms );
				}
				if ( isset( $prop['size'] ) ) {
					$vals['size'] = $result->getByteSize();
				}
				if ( isset( $prop['wordcount'] ) ) {
					$vals['wordcount'] = $result->getWordCount();
				}
				if ( isset( $prop['timestamp'] ) ) {
					$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $result->getTimestamp() );
				}
				if ( isset( $prop['titlesnippet'] ) ) {
					$vals['titlesnippet'] = $result->getTitleSnippet();
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

				// Add item to results and see whether it fits
				$fit = $apiResult->addValue( array( 'query', $this->getModuleName() ),
					null, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'offset', $params['offset'] + $count - 1 );
					break;
				}
			} else {
				$titles[] = $title;
			}

			$result = $matches->next();
		}

		$hasInterwikiResults = false;
		$totalhits = null;
		if ( $interwiki && $resultPageSet === null && $matches->hasInterwikiResults() ) {
			foreach ( $matches->getInterwikiResults() as $matches ) {
				$matches = $matches->getInterwikiResults();
				$hasInterwikiResults = true;

				// Include number of results if requested
				if ( $resultPageSet === null && isset( $searchInfo['totalhits'] ) ) {
					$totalhits += $matches->getTotalHits();
				}

				$result = $matches->next();
				while ( $result ) {
					$title = $result->getTitle();

					if ( $resultPageSet === null ) {
						$vals = array(
							'namespace' => $result->getInterwikiNamespaceText(),
							'title' => $title->getText(),
							'url' => $title->getFullUrl(),
						);

						// Add item to results and see whether it fits
						$fit = $apiResult->addValue(
							array( 'query', 'interwiki' . $this->getModuleName(), $result->getInterwikiPrefix()  ),
							null,
							$vals
						);

						if ( !$fit ) {
							// We hit the limit. We can't really provide any meaningful
							// pagination info so just bail out
							break;
						}
					} else {
						$titles[] = $title;
					}

					$result = $matches->next();
				}
			}
			if ( $totalhits !== null ) {
				$apiResult->addValue( array( 'query', 'interwikisearchinfo' ),
					'totalhits', $totalhits );
			}
		}

		if ( $resultPageSet === null ) {
			$apiResult->addIndexedTagName( array(
				'query', $this->getModuleName()
			), 'p' );
			if ( $hasInterwikiResults ) {
				$apiResult->addIndexedTagName( array(
					'query', 'interwiki' . $this->getModuleName()
				), 'p' );
			}
		} else {
			$resultPageSet->populateFromTitles( $titles );
			$offset = $params['offset'] + 1;
			foreach ( $titles as $index => $title ) {
				$resultPageSet->setGeneratorData( $title, array( 'index' => $index + $offset ) );
			}
		}
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		$params = array(
			'search' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			),
			'namespace' => array(
				ApiBase::PARAM_DFLT => NS_MAIN,
				ApiBase::PARAM_TYPE => 'namespace',
				ApiBase::PARAM_ISMULTI => true,
			),
			'what' => array(
				ApiBase::PARAM_DFLT => null,
				ApiBase::PARAM_TYPE => array(
					'title',
					'text',
					'nearmatch',
				)
			),
			'info' => array(
				ApiBase::PARAM_DFLT => 'totalhits|suggestion',
				ApiBase::PARAM_TYPE => array(
					'totalhits',
					'suggestion',
				),
				ApiBase::PARAM_ISMULTI => true,
			),
			'prop' => array(
				ApiBase::PARAM_DFLT => 'size|wordcount|timestamp|snippet',
				ApiBase::PARAM_TYPE => array(
					'size',
					'wordcount',
					'timestamp',
					'score',
					'snippet',
					'titlesnippet',
					'redirecttitle',
					'redirectsnippet',
					'sectiontitle',
					'sectionsnippet',
					'hasrelated',
				),
				ApiBase::PARAM_ISMULTI => true,
			),
			'offset' => array(
				ApiBase::PARAM_DFLT => 0,
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			),
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_SML1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_SML2
			),
			'interwiki' => false,
		);

		$alternatives = SearchEngine::getSearchTypes();
		if ( count( $alternatives ) > 1 ) {
			if ( $alternatives[0] === null ) {
				$alternatives[0] = self::BACKEND_NULL_PARAM;
			}
			$params['backend'] = array(
				ApiBase::PARAM_DFLT => $this->getConfig()->get( 'SearchType' ),
				ApiBase::PARAM_TYPE => $alternatives,
			);
		}

		return $params;
	}

	protected function getExamplesMessages() {
		return array(
			'action=query&list=search&srsearch=meaning'
				=> 'apihelp-query+search-example-simple',
			'action=query&list=search&srwhat=text&srsearch=meaning'
				=> 'apihelp-query+search-example-text',
			'action=query&generator=search&gsrsearch=meaning&prop=info'
				=> 'apihelp-query+search-example-generator',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Search';
	}
}
