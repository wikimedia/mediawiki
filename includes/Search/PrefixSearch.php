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

	/**
	 * Prefix search special-case for Special: namespace.
	 *
	 * @param string $search Term
	 * @param int $limit Max number of items to return
	 * @param int $offset Number of items to offset
	 * @return array
	 */
	protected function specialSearch( $search, $limit, $offset ) {
		$searchParts = explode( '/', $search, 2 );
		$searchKey = $searchParts[0];
		$subpageSearch = $searchParts[1] ?? null;

		// Handle subpage search separately.
		$spFactory = MediaWikiServices::getInstance()->getSpecialPageFactory();
		if ( $subpageSearch !== null ) {
			// Try matching the full search string as a page name
			$specialTitle = Title::makeTitleSafe( NS_SPECIAL, $searchKey );
			if ( !$specialTitle ) {
				return [];
			}
			$special = $spFactory->getPage( $specialTitle->getText() );
			if ( $special ) {
				$subpages = $special->prefixSearchSubpages( $subpageSearch, $limit, $offset );
				return array_map( $specialTitle->getSubpage( ... ), $subpages );
			} else {
				return [];
			}
		}

		# normalize searchKey, so aliases with spaces can be found - T27675
		$contLang = MediaWikiServices::getInstance()->getContentLanguage();
		$searchKey = str_replace( ' ', '_', $searchKey );
		$searchKey = $contLang->caseFold( $searchKey );

		// Unlike SpecialPage itself, we want the canonical forms of both
		// canonical and alias title forms...
		$keys = [];
		$listedPages = $spFactory->getListedPages();
		foreach ( $listedPages as $specialPage ) {
			$page = $specialPage->getLocalName();
			$keys[$contLang->caseFold( $page )] = [ 'page' => $page, 'rank' => 0 ];
		}

		// Typing "special:" just lists all special pages by their primary name. No need to list the
		// same special pages again by all their aliases.
		if ( $searchKey !== '' ) {
			foreach ( $contLang->getSpecialPageAliases() as $page => $aliases ) {
				// Exclude aliases for unlisted or undefined pages (T22885),
				// e.g. if an extension registers a page based on site configuration.
				if ( !isset( $listedPages[$page] ) ) {
					continue;
				}

				// No need to even consider aliases (and as a result list the same special page
				// multiple times) when the primary page name already matches.
				if ( str_starts_with( $contLang->caseFold( $page ), $searchKey ) ) {
					continue;
				}

				foreach ( $aliases as $key => $alias ) {
					$pageKey = $contLang->caseFold( $alias );
					$keys[$pageKey] = [ 'page' => $alias, 'rank' => $key ];
					// Stop considering later aliases (and as a result list the same special page
					// multiple times) when there was already a match.
					if ( str_starts_with( $pageKey, $searchKey ) ) {
						break;
					}
				}
			}
		}

		ksort( $keys );

		$matches = [];
		foreach ( $keys as $pageKey => $page ) {
			if ( $searchKey === '' || str_starts_with( $pageKey, $searchKey ) ) {
				// T29671: Don't use SpecialPage::getTitleFor() here because it
				// localizes its input leading to searches for e.g. Special:All
				// returning Spezial:MediaWiki-Systemnachrichten and returning
				// Spezial:Alle_Seiten twice when $wgLanguageCode == 'de'
				$matches[$page['rank']][] = Title::makeTitleSafe( NS_SPECIAL, $page['page'] );

				if ( isset( $matches[0] ) && count( $matches[0] ) >= $limit + $offset ) {
					// We have enough items in primary rank, no use to continue
					break;
				}
			}

		}

		// Ensure keys are in order
		ksort( $matches );
		// Flatten the array
		$matches = array_reduce( $matches, 'array_merge', [] );

		return array_slice( $matches, $offset, $limit );
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
