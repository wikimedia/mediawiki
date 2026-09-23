<?php
/**
 * Prefix search of page names.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Search;

use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleParser;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;

/**
 * Handles searching prefixes of titles and finding any page
 * names that match. Used largely by the OpenSearch implementation.
 * @deprecated Since 1.27, Use SearchEngine::defaultPrefixSearch or SearchEngine::completionSearch
 *
 * @stable to extend
 * @ingroup Search
 */
abstract class PrefixSearch {
	/**
	 * @var SpecialPageSuggester|null (lazy loaded)
	 */
	private ?SpecialPageSuggester $specialPageSuggester = null;

	/**
	 * Do a prefix search of titles and return a list of matching page names.
	 *
	 * @param string $search
	 * @param int $limit
	 * @param array $namespaces Used if query is not explicitly prefixed
	 * @param int $offset How many results to offset from the beginning
	 * @return (Title|string)[]
	 */
	public function search( $search, $limit, $namespaces = [], $offset = 0 ) {
		$search = trim( $search );
		if ( $search == '' ) {
			return []; // Return empty result
		}

		$hasNamespace = SearchEngine::parseNamespacePrefixes( $search, false, true );
		if ( $hasNamespace !== false ) {
			[ $search, $namespaces ] = $hasNamespace;
		}

		return $this->searchBackend( $namespaces, $search, $limit, $offset );
	}

	/**
	 * Do a prefix search for all possible variants of the prefix
	 * @param string $search
	 * @param int $limit
	 * @param array $namespaces
	 * @param int $offset How many results to offset from the beginning
	 *
	 * @return (Title|string)[]
	 */
	public function searchWithVariants( $search, $limit, array $namespaces, $offset = 0 ) {
		$searches = $this->search( $search, $limit, $namespaces, $offset );

		// if the content language has variants, try to retrieve fallback results
		$fallbackLimit = $limit - count( $searches );
		if ( $fallbackLimit > 0 ) {
			$services = MediaWikiServices::getInstance();
			$fallbackSearches = $services->getLanguageConverterFactory()
				->getLanguageConverter( $services->getContentLanguage() )
				->autoConvertToAllVariants( $search );
			$fallbackSearches = array_diff( array_unique( $fallbackSearches ), [ $search ] );

			foreach ( $fallbackSearches as $fbs ) {
				$fallbackSearchResult = $this->search( $fbs, $fallbackLimit, $namespaces );
				$searches = array_merge( $searches, $fallbackSearchResult );
				$fallbackLimit -= count( $fallbackSearchResult );

				if ( $fallbackLimit == 0 ) {
					break;
				}
			}
		}
		return $searches;
	}

	/**
	 * When implemented in a descendant class, receives an array of Title objects and returns
	 * either an unmodified array or an array of strings corresponding to titles passed to it.
	 *
	 * @param Title[] $titles
	 * @return (Title|string)[]
	 */
	abstract protected function titles( array $titles );

	/**
	 * When implemented in a descendant class, receives an array of titles as strings and returns
	 * either an unmodified array or an array of Title objects corresponding to strings received.
	 *
	 * @param string[] $strings
	 * @return (Title|string)[]
	 */
	abstract protected function strings( array $strings );

	/**
	 * Do a prefix search of titles and return a list of matching page names.
	 * @param int[] $namespaces
	 * @param string $search
	 * @param int $limit
	 * @param int $offset How many results to offset from the beginning
	 * @return (Title|string)[]
	 */
	protected function searchBackend( $namespaces, $search, $limit, $offset ) {
		if ( count( $namespaces ) == 1 ) {
			$ns = $namespaces[0];
			if ( $ns == NS_MEDIA ) {
				$namespaces = [ NS_FILE ];
			} elseif ( $ns == NS_SPECIAL ) {
				return $this->titles( $this->specialSearch( $search, $limit, $offset ) );
			}
		}
		return $this->titles( $this->defaultSearchBackend( $namespaces, $search, $limit, $offset ) );
	}

	private function getSpecialPageSuggester(): SpecialPageSuggester {
		if ( $this->specialPageSuggester === null ) {
			$this->specialPageSuggester = new SpecialPageSuggester(
				MediaWikiServices::getInstance()->getSpecialPageFactory(),
				MediaWikiServices::getInstance()->getContentLanguage(),
			);
		}
		return $this->specialPageSuggester;
	}

	/**
	 * Prefix search special-case for Special: namespace.
	 *
	 * @param string $search Term
	 * @param int $limit Max number of items to return
	 * @param int $offset Number of items to offset
	 * @return array
	 */
	protected function specialSearch( $search, $limit, $offset ) {
		return $this->getSpecialPageSuggester()->suggest( $search, $limit, $offset );
	}

	/**
	 * This is case-sensitive (First character may
	 * be automatically capitalized by Title::secureAndSpit()
	 * later on depending on $wgCapitalLinks)
	 *
	 * @param int[]|null $namespaces Namespaces to search in
	 * @param string $search Term
	 * @param int $limit Max number of items to return
	 * @param int $offset Number of items to skip
	 * @return Title[]
	 */
	public function defaultSearchBackend( $namespaces, $search, $limit, $offset ) {
		if ( !$namespaces ) {
			$namespaces = [ NS_MAIN ];
		}

		if ( in_array( NS_SPECIAL, $namespaces ) ) {
			// For now, if special is included, ignore the other namespaces
			return $this->specialSearch( $search, $limit, $offset );
		}

		// Construct suitable prefix for each namespace. They differ in cases where
		// some namespaces always capitalize and some don't.
		$prefixes = [];
		// Allow to do a prefix search for e.g. "Talk:"
		if ( $search === '' ) {
			$prefixes[$search] = $namespaces;
		} else {
			// Don't just ignore input like "[[Foo]]", but try to search for "Foo"
			$search = preg_replace( TitleParser::getTitleInvalidRegex(), '', $search );
			foreach ( $namespaces as $namespace ) {
				$title = Title::makeTitleSafe( $namespace, $search );
				if ( $title ) {
					$prefixes[$title->getDBkey()][] = $namespace;
				}
			}
		}
		if ( !$prefixes ) {
			return [];
		}

		$services = MediaWikiServices::getInstance();
		$dbr = $services->getConnectionProvider()->getReplicaDatabase();
		// Often there is only one prefix that applies to all requested namespaces,
		// but sometimes there are two if some namespaces do not always capitalize.
		$conds = [];
		foreach ( $prefixes as $prefix => $namespaces ) {
			$expr = $dbr->expr( 'page_namespace', '=', $namespaces );
			if ( $prefix !== '' ) {
				$expr = $expr->and(
					'page_title',
					IExpression::LIKE,
					new LikeValue( (string)$prefix, $dbr->anyString() )
				);
			}
			$conds[] = $expr;
		}

		$queryBuilder = $dbr->newSelectQueryBuilder()
			->select( [ 'page_id', 'page_namespace', 'page_title' ] )
			->from( 'page' )
			->where( $dbr->orExpr( $conds ) )
			->orderBy( [ 'page_title', 'page_namespace' ] )
			->limit( $limit )
			->offset( $offset );
		$res = $queryBuilder->caller( __METHOD__ )->fetchResultSet();

		return iterator_to_array( $services->getTitleFactory()->newTitleArrayFromResult( $res ) );
	}
}

/** @deprecated class alias since 1.46 */
class_alias( PrefixSearch::class, 'PrefixSearch' );
