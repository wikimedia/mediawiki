<?php
/**
 * Created on Oct 13, 2006
 *
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 * Copyright © 2008 Brion Vibber <brion@wikimedia.org>
 * Copyright © 2014 Brad Jorsch <bjorsch@wikimedia.org>
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

use MediaWiki\MediaWikiServices;

/**
 * @ingroup API
 */
class ApiOpenSearch extends ApiBase {

	private $format = null;
	private $fm = null;

	/**
	 * Get the output format
	 *
	 * @return string
	 */
	protected function getFormat() {
		if ( $this->format === null ) {
			$params = $this->extractRequestParams();
			$format = $params['format'];

			$allowedParams = $this->getAllowedParams();
			if ( !in_array( $format, $allowedParams['format'][ApiBase::PARAM_TYPE] ) ) {
				$format = $allowedParams['format'][ApiBase::PARAM_DFLT];
			}

			if ( substr( $format, -2 ) === 'fm' ) {
				$this->format = substr( $format, 0, -2 );
				$this->fm = 'fm';
			} else {
				$this->format = $format;
				$this->fm = '';
			}
		}
		return $this->format;
	}

	public function getCustomPrinter() {
		switch ( $this->getFormat() ) {
			case 'json':
				return new ApiOpenSearchFormatJson(
					$this->getMain(), $this->fm, $this->getParameter( 'warningsaserror' )
				);

			case 'xml':
				$printer = $this->getMain()->createPrinterByName( 'xml' . $this->fm );
				$printer->setRootElement( 'SearchSuggestion' );
				return $printer;

			default:
				ApiBase::dieDebug( __METHOD__, "Unsupported format '{$this->getFormat()}'" );
		}
	}

	public function execute() {
		$params = $this->extractRequestParams();
		$search = $params['search'];
		$limit = $params['limit'];
		$namespaces = $params['namespace'];
		$suggest = $params['suggest'];

		if ( $params['redirects'] === null ) {
			// Backwards compatibility, don't resolve for JSON.
			$resolveRedir = $this->getFormat() !== 'json';
		} else {
			$resolveRedir = $params['redirects'] === 'resolve';
		}

		$results = [];

		if ( !$suggest || $this->getConfig()->get( 'EnableOpenSearchSuggest' ) ) {
			// Open search results may be stored for a very long time
			$this->getMain()->setCacheMaxAge( $this->getConfig()->get( 'SearchSuggestCacheExpiry' ) );
			$this->getMain()->setCacheMode( 'public' );
			$this->search( $search, $limit, $namespaces, $resolveRedir, $results );

			// Allow hooks to populate extracts and images
			Hooks::run( 'ApiOpenSearchSuggest', [ &$results ] );

			// Trim extracts, if necessary
			$length = $this->getConfig()->get( 'OpenSearchDescriptionLength' );
			foreach ( $results as &$r ) {
				if ( is_string( $r['extract'] ) && !$r['extract trimmed'] ) {
					$r['extract'] = self::trimExtract( $r['extract'], $length );
				}
			}
		}

		// Populate result object
		$this->populateResult( $search, $results );
	}

	/**
	 * Perform the search
	 *
	 * @param string $search Text to search
	 * @param int $limit Maximum items to return
	 * @param array $namespaces Namespaces to search
	 * @param bool $resolveRedir Whether to resolve redirects
	 * @param array &$results Put results here. Keys have to be integers.
	 */
	protected function search( $search, $limit, $namespaces, $resolveRedir, &$results ) {
		$searchEngine = MediaWikiServices::getInstance()->newSearchEngine();
		$searchEngine->setLimitOffset( $limit );
		$searchEngine->setNamespaces( $namespaces );
		$titles = $searchEngine->extractTitles( $searchEngine->completionSearchWithVariants( $search ) );

		if ( !$titles ) {
			return;
		}

		// Special pages need unique integer ids in the return list, so we just
		// assign them negative numbers because those won't clash with the
		// always positive articleIds that non-special pages get.
		$nextSpecialPageId = -1;

		if ( $resolveRedir ) {
			// Query for redirects
			$redirects = [];
			$lb = new LinkBatch( $titles );
			if ( !$lb->isEmpty() ) {
				$db = $this->getDB();
				$res = $db->select(
					[ 'page', 'redirect' ],
					[ 'page_namespace', 'page_title', 'rd_namespace', 'rd_title' ],
					[
						'rd_from = page_id',
						'rd_interwiki IS NULL OR rd_interwiki = ' . $db->addQuotes( '' ),
						$lb->constructSet( 'page', $db ),
					],
					__METHOD__
				);
				foreach ( $res as $row ) {
					$redirects[$row->page_namespace][$row->page_title] =
						[ $row->rd_namespace, $row->rd_title ];
				}
			}

			// Bypass any redirects
			$seen = [];
			foreach ( $titles as $title ) {
				$ns = $title->getNamespace();
				$dbkey = $title->getDBkey();
				$from = null;
				if ( isset( $redirects[$ns][$dbkey] ) ) {
					list( $ns, $dbkey ) = $redirects[$ns][$dbkey];
					$from = $title;
					$title = Title::makeTitle( $ns, $dbkey );
				}
				if ( !isset( $seen[$ns][$dbkey] ) ) {
					$seen[$ns][$dbkey] = true;
					$resultId = $title->getArticleID();
					if ( $resultId === 0 ) {
						$resultId = $nextSpecialPageId;
						$nextSpecialPageId -= 1;
					}
					$results[$resultId] = [
						'title' => $title,
						'redirect from' => $from,
						'extract' => false,
						'extract trimmed' => false,
						'image' => false,
						'url' => wfExpandUrl( $title->getFullURL(), PROTO_CURRENT ),
					];
				}
			}
		} else {
			foreach ( $titles as $title ) {
				$resultId = $title->getArticleID();
				if ( $resultId === 0 ) {
					$resultId = $nextSpecialPageId;
					$nextSpecialPageId -= 1;
				}
				$results[$resultId] = [
					'title' => $title,
					'redirect from' => null,
					'extract' => false,
					'extract trimmed' => false,
					'image' => false,
					'url' => wfExpandUrl( $title->getFullURL(), PROTO_CURRENT ),
				];
			}
		}
	}

	/**
	 * @param string $search
	 * @param array &$results
	 */
	protected function populateResult( $search, &$results ) {
		$result = $this->getResult();

		switch ( $this->getFormat() ) {
			case 'json':
				// http://www.opensearch.org/Specifications/OpenSearch/Extensions/Suggestions/1.1
				$result->addArrayType( null, 'array' );
				$result->addValue( null, 0, strval( $search ) );
				$terms = [];
				$descriptions = [];
				$urls = [];
				foreach ( $results as $r ) {
					$terms[] = $r['title']->getPrefixedText();
					$descriptions[] = strval( $r['extract'] );
					$urls[] = $r['url'];
				}
				$result->addValue( null, 1, $terms );
				$result->addValue( null, 2, $descriptions );
				$result->addValue( null, 3, $urls );
				break;

			case 'xml':
				// http://msdn.microsoft.com/en-us/library/cc891508%28v=vs.85%29.aspx
				$imageKeys = [
					'source' => true,
					'alt' => true,
					'width' => true,
					'height' => true,
					'align' => true,
				];
				$items = [];
				foreach ( $results as $r ) {
					$item = [
						'Text' => $r['title']->getPrefixedText(),
						'Url' => $r['url'],
					];
					if ( is_string( $r['extract'] ) && $r['extract'] !== '' ) {
						$item['Description'] = $r['extract'];
					}
					if ( is_array( $r['image'] ) && isset( $r['image']['source'] ) ) {
						$item['Image'] = array_intersect_key( $r['image'], $imageKeys );
					}
					ApiResult::setSubelementsList( $item, array_keys( $item ) );
					$items[] = $item;
				}
				ApiResult::setIndexedTagName( $items, 'Item' );
				$result->addValue( null, 'version', '2.0' );
				$result->addValue( null, 'xmlns', 'http://opensearch.org/searchsuggest2' );
				$result->addValue( null, 'Query', strval( $search ) );
				$result->addSubelementsList( null, 'Query' );
				$result->addValue( null, 'Section', $items );
				break;

			default:
				ApiBase::dieDebug( __METHOD__, "Unsupported format '{$this->getFormat()}'" );
		}
	}

	public function getAllowedParams() {
		return [
			'search' => null,
			'limit' => [
				ApiBase::PARAM_DFLT => $this->getConfig()->get( 'OpenSearchDefaultLimit' ),
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => 100,
				ApiBase::PARAM_MAX2 => 100
			],
			'namespace' => [
				ApiBase::PARAM_DFLT => NS_MAIN,
				ApiBase::PARAM_TYPE => 'namespace',
				ApiBase::PARAM_ISMULTI => true
			],
			'suggest' => false,
			'redirects' => [
				ApiBase::PARAM_TYPE => [ 'return', 'resolve' ],
			],
			'format' => [
				ApiBase::PARAM_DFLT => 'json',
				ApiBase::PARAM_TYPE => [ 'json', 'jsonfm', 'xml', 'xmlfm' ],
			],
			'warningsaserror' => false,
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=opensearch&search=Te'
				=> 'apihelp-opensearch-example-te',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Opensearch';
	}

	/**
	 * Trim an extract to a sensible length.
	 *
	 * Adapted from Extension:OpenSearchXml, which adapted it from
	 * Extension:ActiveAbstract.
	 *
	 * @param string $text
	 * @param int $length Target length; actual result will continue to the end of a sentence.
	 * @return string
	 */
	public static function trimExtract( $text, $length ) {
		static $regex = null;

		if ( $regex === null ) {
			$endchars = [
				'([^\d])\.\s', '\!\s', '\?\s', // regular ASCII
				'。', // full-width ideographic full-stop
				'．', '！', '？', // double-width roman forms
				'｡', // half-width ideographic full stop
			];
			$endgroup = implode( '|', $endchars );
			$end = "(?:$endgroup)";
			$sentence = ".{{$length},}?$end+";
			$regex = "/^($sentence)/u";
		}

		$matches = [];
		if ( preg_match( $regex, $text, $matches ) ) {
			return trim( $matches[1] );
		} else {
			// Just return the first line
			return trim( explode( "\n", $text )[0] );
		}
	}

	/**
	 * Fetch the template for a type.
	 *
	 * @param string $type MIME type
	 * @return string
	 * @throws MWException
	 */
	public static function getOpenSearchTemplate( $type ) {
		$config = MediaWikiServices::getInstance()->getSearchEngineConfig();
		$template = $config->getConfig()->get( 'OpenSearchTemplate' );

		if ( $template && $type === 'application/x-suggestions+json' ) {
			return $template;
		}

		$ns = implode( '|', $config->defaultNamespaces() );
		if ( !$ns ) {
			$ns = '0';
		}

		switch ( $type ) {
			case 'application/x-suggestions+json':
				return $config->getConfig()->get( 'CanonicalServer' ) . wfScript( 'api' )
					. '?action=opensearch&search={searchTerms}&namespace=' . $ns;

			case 'application/x-suggestions+xml':
				return $config->getConfig()->get( 'CanonicalServer' ) . wfScript( 'api' )
					. '?action=opensearch&format=xml&search={searchTerms}&namespace=' . $ns;

			default:
				throw new MWException( __METHOD__ . ": Unknown type '$type'" );
		}
	}
}

class ApiOpenSearchFormatJson extends ApiFormatJson {
	private $warningsAsError = false;

	public function __construct( ApiMain $main, $fm, $warningsAsError ) {
		parent::__construct( $main, "json$fm" );
		$this->warningsAsError = $warningsAsError;
	}

	public function execute() {
		if ( !$this->getResult()->getResultData( 'error' ) ) {
			$result = $this->getResult();

			// Ignore warnings or treat as errors, as requested
			$warnings = $result->removeValue( 'warnings', null );
			if ( $this->warningsAsError && $warnings ) {
				$this->dieUsage(
					'Warnings cannot be represented in OpenSearch JSON format', 'warnings', 0,
					[ 'warnings' => $warnings ]
				);
			}

			// Ignore any other unexpected keys (e.g. from $wgDebugToolbar)
			$remove = array_keys( array_diff_key(
				$result->getResultData(),
				[ 0 => 'search', 1 => 'terms', 2 => 'descriptions', 3 => 'urls' ]
			) );
			foreach ( $remove as $key ) {
				$result->removeValue( $key, null );
			}
		}

		parent::execute();
	}
}
