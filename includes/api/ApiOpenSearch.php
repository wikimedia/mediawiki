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
				return $this->getMain()->createPrinterByName( 'json' . $this->fm );

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

		$results = array();

		if ( !$suggest || $this->getConfig()->get( 'EnableOpenSearchSuggest' ) ) {
			// Open search results may be stored for a very long time
			$this->getMain()->setCacheMaxAge( $this->getConfig()->get( 'SearchSuggestCacheExpiry' ) );
			$this->getMain()->setCacheMode( 'public' );
			$this->search( $search, $limit, $namespaces, $resolveRedir, $results );

			// Allow hooks to populate extracts and images
			wfRunHooks( 'ApiOpenSearchSuggest', array( &$results ) );

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
	 * @param array &$results Put results here
	 */
	protected function search( $search, $limit, $namespaces, $resolveRedir, &$results ) {
		// Find matching titles as Title objects
		$searcher = new TitlePrefixSearch;
		$titles = $searcher->searchWithVariants( $search, $limit, $namespaces );

		if ( $resolveRedir ) {
			// Query for redirects
			$db = $this->getDb();
			$lb = new LinkBatch( $titles );
			$res = $db->select(
				array( 'page', 'redirect' ),
				array( 'page_namespace', 'page_title', 'rd_namespace', 'rd_title' ),
				array(
					'rd_from = page_id',
					'rd_interwiki IS NULL OR rd_interwiki = ' . $db->addQuotes( '' ),
					$lb->constructSet( 'page', $db ),
				),
				__METHOD__
			);
			$redirects = array();
			foreach ( $res as $row ) {
				$redirects[$row->page_namespace][$row->page_title] =
					array( $row->rd_namespace, $row->rd_title );
			}

			// Bypass any redirects
			$seen = array();
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
					$results[$title->getArticleId()] = array(
						'title' => $title,
						'redirect from' => $from,
						'extract' => false,
						'extract trimmed' => false,
						'image' => false,
						'url' => wfExpandUrl( $title->getFullUrl(), PROTO_CURRENT ),
					);
				}
			}
		} else {
			foreach ( $titles as $title ) {
				$results[$title->getArticleId()] = array(
					'title' => $title,
					'redirect from' => null,
					'extract' => false,
					'extract trimmed' => false,
					'image' => false,
					'url' => wfExpandUrl( $title->getFullUrl(), PROTO_CURRENT ),
				);
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
				$result->addValue( null, 0, strval( $search ) );
				$terms = array();
				$descriptions = array();
				$urls = array();
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
				$imageKeys = array(
					'source' => true,
					'alt' => true,
					'width' => true,
					'height' => true,
					'align' => true,
				);
				$items = array();
				foreach ( $results as $r ) {
					$item = array();
					$result->setContent( $item, $r['title']->getPrefixedText(), 'Text' );
					$result->setContent( $item, $r['url'], 'Url' );
					if ( is_string( $r['extract'] ) && $r['extract'] !== '' ) {
						$result->setContent( $item, $r['extract'], 'Description' );
					}
					if ( is_array( $r['image'] ) && isset( $r['image']['source'] ) ) {
						$item['Image'] = array_intersect_key( $r['image'], $imageKeys );
					}
					$items[] = $item;
				}
				$result->setIndexedTagName( $items, 'Item' );
				$result->addValue( null, 'version', '2.0' );
				$result->addValue( null, 'xmlns', 'http://opensearch.org/searchsuggest2' );
				$query = array();
				$result->setContent( $query, strval( $search ) );
				$result->addValue( null, 'Query', $query );
				$result->addValue( null, 'Section', $items );
				break;

			default:
				ApiBase::dieDebug( __METHOD__, "Unsupported format '{$this->getFormat()}'" );
		}
	}

	public function getAllowedParams() {
		return array(
			'search' => null,
			'limit' => array(
				ApiBase::PARAM_DFLT => $this->getConfig()->get( 'OpenSearchDefaultLimit' ),
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
			'redirects' => array(
				ApiBase::PARAM_TYPE => array( 'return', 'resolve' ),
			),
			'format' => array(
				ApiBase::PARAM_DFLT => 'json',
				ApiBase::PARAM_TYPE => array( 'json', 'jsonfm', 'xml', 'xmlfm' ),
			)
		);
	}

	protected function getExamplesMessages() {
		return array(
			'action=opensearch&search=Te'
				=> 'apihelp-opensearch-example-te',
		);
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
	 * @param int $len Target length; actual result will continue to the end of a sentence.
	 * @return string
	 */
	public static function trimExtract( $text, $length ) {
		static $regex = null;

		if ( $regex === null ) {
			$endchars = array(
				'([^\d])\.\s', '\!\s', '\?\s', // regular ASCII
				'。', // full-width ideographic full-stop
				'．', '！', '？', // double-width roman forms
				'｡', // half-width ideographic full stop
			);
			$endgroup = implode( '|', $endchars );
			$end = "(?:$endgroup)";
			$sentence = ".{{$length},}?$end+";
			$regex = "/^($sentence)/u";
		}

		$matches = array();
		if ( preg_match( $regex, $text, $matches ) ) {
			return trim( $matches[1] );
		} else {
			// Just return the first line
			$lines = explode( "\n", $text );
			return trim( $lines[0] );
		}
	}

	/**
	 * Fetch the template for a type.
	 *
	 * @param string $type MIME type
	 * @return string
	 */
	public static function getOpenSearchTemplate( $type ) {
		global $wgOpenSearchTemplate, $wgCanonicalServer;

		if ( $wgOpenSearchTemplate && $type === 'application/x-suggestions+json' ) {
			return $wgOpenSearchTemplate;
		}

		$ns = implode( '|', SearchEngine::defaultNamespaces() );
		if ( !$ns ) {
			$ns = "0";
		}

		switch ( $type ) {
			case 'application/x-suggestions+json':
				return $wgCanonicalServer . wfScript( 'api' )
					. '?action=opensearch&search={searchTerms}&namespace=' . $ns;

			case 'application/x-suggestions+xml':
				return $wgCanonicalServer . wfScript( 'api' )
					. '?action=opensearch&format=xml&search={searchTerms}&namespace=' . $ns;

			default:
				throw new MWException( __METHOD__ . ": Unknown type '$type'" );
		}
	}
}
