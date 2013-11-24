<?php
/**
 * Created on Oct 13, 2006
 *
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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
 * @ingroup API
 */
class ApiOpenSearch extends ApiBase {

	/**
	 * Override built-in handling of format parameter.
	 * Only JSON is supported.
	 *
	 * @return ApiFormatBase
	 */
	public function getCustomPrinter() {
		$params = $this->extractRequestParams();
		$format = $params['format'];
		$allowed = array( 'json', 'jsonfm' );
		if ( in_array( $format, $allowed ) ) {
			return $this->getMain()->createPrinterByName( $format );
		}

		return $this->getMain()->createPrinterByName( $allowed[0] );
	}

	public function execute() {
		global $wgEnableOpenSearchSuggest, $wgSearchSuggestCacheExpiry;
		$params = $this->extractRequestParams();
		$search = $params['search'];
		$limit = $params['limit'];
		$namespaces = $params['namespace'];
		$suggest = $params['suggest'];

		// Some script that was loaded regardless of wgEnableOpenSearchSuggest, likely cached.
		if ( $suggest && !$wgEnableOpenSearchSuggest ) {
			$searches = array();
		} else {
			// Open search results may be stored for a very long time
			$this->getMain()->setCacheMaxAge( $wgSearchSuggestCacheExpiry );
			$this->getMain()->setCacheMode( 'public' );

			$searches = PrefixSearch::titleSearch( $search, $limit,
				$namespaces );

			// if the content language has variants, try to retrieve fallback results
			$fallbackLimit = $limit - count( $searches );
			if ( $fallbackLimit > 0 ) {
				global $wgContLang;

				$fallbackSearches = $wgContLang->autoConvertToAllVariants( $search );
				$fallbackSearches = array_diff( array_unique( $fallbackSearches ), array( $search ) );

				foreach ( $fallbackSearches as $fbs ) {
					$fallbackSearchResult = PrefixSearch::titleSearch( $fbs, $fallbackLimit,
						$namespaces );
					$searches = array_merge( $searches, $fallbackSearchResult );
					$fallbackLimit -= count( $fallbackSearchResult );

					if ( $fallbackLimit == 0 ) {
						break;
					}
				}
			}
		}
		// Set top level elements
		$result = $this->getResult();
		$result->addValue( null, 0, $search );
		$result->addValue( null, 1, $searches );
	}

	public function getAllowedParams() {
		global $wgOpenSearchDefaultLimit;

		return array(
			'search' => null,
			'limit' => array(
				ApiBase::PARAM_DFLT => $wgOpenSearchDefaultLimit,
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
			'format' => array(
				ApiBase::PARAM_DFLT => 'json',
				ApiBase::PARAM_TYPE => array( 'json', 'jsonfm' ),
			)
		);
	}

	public function getParamDescription() {
		return array(
			'search' => 'Search string',
			'limit' => 'Maximum amount of results to return',
			'namespace' => 'Namespaces to search',
			'suggest' => 'Do nothing if $wgEnableOpenSearchSuggest is false',
			'format' => 'The format of the output',
		);
	}

	public function getDescription() {
		return 'Search the wiki using the OpenSearch protocol';
	}

	public function getExamples() {
		return array(
			'api.php?action=opensearch&search=Te'
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Opensearch';
	}
}
