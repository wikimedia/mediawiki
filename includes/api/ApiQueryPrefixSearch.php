<?php

/**
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
 * @since 1.23
 */

namespace MediaWiki\Api;

use SearchEngine;
use SearchEngineConfig;
use SearchEngineFactory;

/**
 * @ingroup API
 */
class ApiQueryPrefixSearch extends ApiQueryGeneratorBase {
	use \MediaWiki\Api\SearchApi;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		SearchEngineConfig $searchEngineConfig,
		SearchEngineFactory $searchEngineFactory
	) {
		parent::__construct( $query, $moduleName, 'ps' );
		// Services needed in SearchApi trait
		$this->searchEngineConfig = $searchEngineConfig;
		$this->searchEngineFactory = $searchEngineFactory;
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
	 */
	private function run( $resultPageSet = null ) {
		$params = $this->extractRequestParams();
		$search = $params['search'];
		$limit = $params['limit'];
		$offset = $params['offset'];

		$searchEngine = $this->buildSearchEngine( $params );
		$suggestions = $searchEngine->completionSearchWithVariants( $search );
		$titles = $searchEngine->extractTitles( $suggestions );

		if ( $suggestions->hasMoreResults() ) {
			$this->setContinueEnumParameter( 'offset', $offset + $limit );
		}

		if ( $resultPageSet ) {
			$resultPageSet->setRedirectMergePolicy( static function ( array $current, array $new ) {
				if ( !isset( $current['index'] ) || $new['index'] < $current['index'] ) {
					$current['index'] = $new['index'];
				}
				return $current;
			} );
			$resultPageSet->populateFromTitles( $titles );
			foreach ( $titles as $index => $title ) {
				$resultPageSet->setGeneratorData( $title, [ 'index' => $index + $offset + 1 ] );
			}
		} else {
			$result = $this->getResult();
			$count = 0;
			foreach ( $titles as $title ) {
				$vals = [
					'ns' => $title->getNamespace(),
					'title' => $title->getPrefixedText(),
				];
				if ( $title->isSpecialPage() ) {
					$vals['special'] = true;
				} else {
					$vals['pageid'] = (int)$title->getArticleID();
				}
				$fit = $result->addValue( [ 'query', $this->getModuleName() ], null, $vals );
				++$count;
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'offset', $offset + $count );
					break;
				}
			}
			$result->addIndexedTagName(
				[ 'query', $this->getModuleName() ], $this->getModulePrefix()
			);
		}
	}

	/** @inheritDoc */
	public function getCacheMode( $params ) {
		return 'public';
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return $this->buildCommonApiParams();
	}

	/** @inheritDoc */
	public function getSearchProfileParams() {
		return [
			'profile' => [
				'profile-type' => SearchEngine::COMPLETION_PROFILE_TYPE,
				'help-message' => 'apihelp-query+prefixsearch-param-profile',
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&list=prefixsearch&pssearch=meaning'
				=> 'apihelp-query+prefixsearch-example-simple',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Prefixsearch';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryPrefixSearch::class, 'ApiQueryPrefixSearch' );
